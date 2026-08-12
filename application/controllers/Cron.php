<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Antigravity
 *
 * This controller acts as a background worker (Cron) to send queued emails.
 * Uses active configuration from email_configurations master table (or settings fallback).
 * Uses the email_template view to wrap the message body with branded HTML layout.
 */

class Cron extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Dipanggil oleh Email_runner->_trigger_background_worker()
     */
    public function process_email_queue()
    {
        // Abaikan koneksi user yang terputus (cURL timeout 1 detik dari runner) agar proses terus berjalan.
        ignore_user_abort(true);
        set_time_limit(0);

        // Otomatis buat/perbarui skema tabel email_queues saat pertama kali dijalankan di server live
        $this->_check_table_and_migrate();

        // 0. Mutex / Process Locking menggunakan flock untuk mencegah multiple worker running bersamaan
        $lock_file = APPPATH . 'cache/email_cron.lock';
        $fp = @fopen($lock_file, 'c+');
        if ($fp) {
            if (!@flock($fp, LOCK_EX | LOCK_NB)) {
                // Worker lain sedang berjalan, keluar dengan aman
                @fclose($fp);
                return;
            }
        }

        // 0b. Reset antrean status 'PRG' (Processing) yang gantung > 5 menit kembali ke 'PND'
        $five_mins_ago = date('Y-m-d H:i:s', strtotime('-5 minutes'));
        $this->db->where('status', 'PRG');
        $this->db->where('created_at <', $five_mins_ago);
        $this->db->update('email_queues', ['status' => 'PND']);

        // 1. Ambil kandidat antrean pending (batch per 5 email)
        $candidates = $this->db->get_where('email_queues', ['status' => 'PND'], 5)->result();

        if (empty($candidates)) {
            if ($fp) {
                @flock($fp, LOCK_UN);
                @fclose($fp);
            }
            echo "[" . date('Y-m-d H:i:s') . "] No pending queues.\n";
            return;
        }

        // 1b. Atomic Status Claim: Klaim kandidat antrean dari status 'PND' ke 'PRG' (Processing)
        $candidate_ids = array_column($candidates, 'id');
        $this->db->where_in('id', $candidate_ids);
        $this->db->where('status', 'PND');
        $this->db->update('email_queues', ['status' => 'PRG']);

        if ($this->db->affected_rows() == 0) {
            if ($fp) {
                @flock($fp, LOCK_UN);
                @fclose($fp);
            }
            return;
        }

        // Ambil hanya antrean yang berhasil diklaim secara atomik oleh worker ini
        $queues = $this->db->where_in('id', $candidate_ids)
            ->where('status', 'PRG')
            ->get('email_queues')
            ->result();

        if (empty($queues)) {
            if ($fp) {
                @flock($fp, LOCK_UN);
                @fclose($fp);
            }
            return;
        }

        // 2. Ambil Konfigurasi SMTP (Prioritas 1: Master email_configurations, Fallback: Tabel settings)
        $this->load->library('encryption');

        $active_config_id = null;
        $smtp_host    = '';
        $smtp_port    = 465;
        $smtp_user    = '';
        $smtp_pass    = '';
        $smtp_crypto  = 'ssl';
        $sender_name  = 'Helpdesk System';
        $sender_email = '';
        $reply_to_name  = null;
        $reply_to_email = null;

        if ($this->db->table_exists('email_configurations')) {
            $active_config = $this->db->get_where('email_configurations', ['is_active' => 1])->row();
            if (!$active_config) {
                // Fallback: Ambil record paling terakhir
                $active_config = $this->db->order_by('id', 'DESC')->get('email_configurations')->row();
            }

            if ($active_config) {
                $active_config_id = $active_config->id;
                $smtp_host        = $active_config->smtp_host;
                $smtp_port        = $active_config->smtp_port;
                $smtp_user        = $active_config->smtp_user;
                $decrypted        = $this->encryption->decrypt($active_config->smtp_pass);
                $smtp_pass        = ($decrypted !== FALSE && $decrypted !== '') ? $decrypted : $active_config->smtp_pass;
                $smtp_crypto      = $active_config->smtp_crypto;
                $sender_name      = !empty($active_config->sender_name) ? $active_config->sender_name : 'Helpdesk System';
                $sender_email     = !empty($active_config->sender_email) ? $active_config->sender_email : $smtp_user;
                $reply_to_name    = $active_config->reply_to_name;
                $reply_to_email   = $active_config->reply_to_email;
            }
        }

        if (empty($smtp_host) || empty($smtp_user) || empty($smtp_pass)) {
            // Kembalikan antrean ke PND agar dapat dicoba lagi setelah admin melengkapi SMTP
            $this->db->where_in('id', $candidate_ids);
            $this->db->update('email_queues', ['status' => 'PND', 'error_msg' => 'Konfigurasi SMTP belum diisi']);

            if ($fp) {
                @flock($fp, LOCK_UN);
                @fclose($fp);
            }
            echo "[" . date('Y-m-d H:i:s') . "] SMTP Configuration is missing.\n";
            return;
        }

        // Bersihkan host dari awalan ssl:// atau tls:// karena di-handle oleh smtp_crypto di CI3
        $clean_host = str_replace(['ssl://', 'tls://'], '', $smtp_host);

        // 3. Set Config CI3 Email Library
        $config = [
            'protocol'    => 'smtp',
            'smtp_host'   => $clean_host,
            'smtp_port'   => $smtp_port,
            'smtp_user'   => $smtp_user,
            'smtp_pass'   => $smtp_pass,
            'smtp_crypto' => !empty($smtp_crypto) ? $smtp_crypto : 'ssl',
            'mailtype'    => 'html',
            'charset'     => 'utf-8',
            'newline'     => "\r\n",
            'crlf'        => "\r\n",
            'wordwrap'    => TRUE
        ];

        $this->load->library('email');
        $this->email->initialize($config);

        // 4. Looping Pengiriman
        $tmpl_file = APPPATH . 'modules/email_setting/views/email_template.php';
        $template_raw = file_exists($tmpl_file) ? file_get_contents($tmpl_file) : '<div>{{content}}</div>';

        $success_count = 0;
        $last_error_msg = null;

        foreach ($queues as $q) {
            $this->email->clear();

            $this->email->from($sender_email, $sender_name);
            if (!empty($reply_to_email)) {
                $this->email->reply_to($reply_to_email, !empty($reply_to_name) ? $reply_to_name : $sender_name);
            }
            $this->email->to($q->to_email);
            $this->email->subject($q->subject);

            // Ambil data perusahaan untuk placeholder dinamis
            $company = $this->db->get_where('companies', ['id_perusahaan' => $q->company_id])->row();

            $htmlMessage = $template_raw;

            // Ganti Placeholder Dasar
            $htmlMessage = str_replace('{{content}}', $q->message, $htmlMessage);
            $htmlMessage = str_replace('{{subject}}', $q->subject, $htmlMessage);

            // Tentukan Nilai untuk Placeholder
            $final_name    = $company ? $company->nm_perusahaan : '';
            $final_address = $company ? $company->alamat : '';
            $final_logo    = ($company && !empty($company->logo)) ? base_url($company->path_logo . $company->id_perusahaan . '/' . $company->logo) : '';

            $htmlMessage = str_replace('{{company_name}}', $final_name, $htmlMessage);
            $htmlMessage = str_replace('{{company_address}}', $final_address, $htmlMessage);
            $htmlMessage = str_replace('{{company_logo}}', $final_logo, $htmlMessage);

            // Ganti Action URL (Jika kosong, arahkan ke Home)
            $final_url = (!empty($q->action_url)) ? $q->action_url : base_url();
            $htmlMessage = str_replace('{{action_url}}', $final_url, $htmlMessage);

            $this->email->message($htmlMessage);

            if ($this->email->send()) {
                // Success
                $success_count++;
                $this->db->update('email_queues', [
                    'status'   => 'SND',
                    'sent_at'  => date('Y-m-d H:i:s'),
                    'attempts' => $q->attempts + 1
                ], ['id' => $q->id]);
            } else {
                // Failed
                $error_msg = $this->email->print_debugger(['headers']);
                $clean_error = strip_tags($error_msg);

                if (strpos($clean_error, 'The following SMTP error was encountered:') !== false) {
                    $parts = explode('The following SMTP error was encountered:', $clean_error);
                    $clean_error = 'SMTP Error: ' . trim(end($parts));
                }

                if (strlen($clean_error) > 1000) {
                    $clean_error = substr($clean_error, 0, 1000);
                }

                $last_error_msg = $clean_error;

                $new_status = ($q->attempts >= 3) ? 'FAI' : 'PND'; // 3x gagal = FAI

                $this->db->update('email_queues', [
                    'status'    => $new_status,
                    'error_msg' => $clean_error,
                    'attempts'  => $q->attempts + 1
                ], ['id' => $q->id]);
            }
        }

        // 5. Update Status SMTP Log pada Master Konfigurasi
        if ($active_config_id) {
            $now = date('Y-m-d H:i:s');
            if ($success_count > 0) {
                $this->db->update('email_configurations', [
                    'last_success_at' => $now
                ], ['id' => $active_config_id]);
            }
            if (!empty($last_error_msg)) {
                $this->db->update('email_configurations', [
                    'last_error_at'  => $now,
                    'last_error_msg' => $last_error_msg
                ], ['id' => $active_config_id]);
            }
        }

        // Lepas file lock jika ada
        if ($fp) {
            @flock($fp, LOCK_UN);
            @fclose($fp);
        }

        echo "[" . date('Y-m-d H:i:s') . "] Batch processed " . count($queues) . " emails.\n";
    }

    /**
     * Otomatis membuat tabel email_queues jika belum ada
     * dan memastikan kolom status mendukung 'PRG' (Processing) saat di-deploy ke server live.
     */
    private function _check_table_and_migrate()
    {
        if (!$this->db->table_exists('email_queues')) {
            $sql = "CREATE TABLE IF NOT EXISTS `email_queues` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `company_id` int(11) DEFAULT 1,
              `to_email` text NOT NULL,
              `subject` varchar(255) NOT NULL,
              `message` longtext NOT NULL,
              `action_url` text DEFAULT NULL,
              `status` varchar(10) NOT NULL DEFAULT 'PND' COMMENT 'PND=Pending, PRG=Processing, SND=Sent, FAI=Failed',
              `attempts` int(11) NOT NULL DEFAULT 0,
              `error_msg` text DEFAULT NULL,
              `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `sent_at` datetime DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `idx_status` (`status`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            $this->db->query($sql);
        } else {
            // Pastikan kolom status adalah VARCHAR(10) agar mendukung status 'PRG'
            $fields = $this->db->field_data('email_queues');
            foreach ($fields as $field) {
                if ($field->name == 'status') {
                    if (strpos(strtolower($field->type), 'enum') !== false || $field->max_length < 10) {
                        $this->db->query("ALTER TABLE `email_queues` MODIFY COLUMN `status` VARCHAR(10) NOT NULL DEFAULT 'PND' COMMENT 'PND=Pending, PRG=Processing, SND=Sent, FAI=Failed'");
                    }
                    break;
                }
            }
        }
    }
}
