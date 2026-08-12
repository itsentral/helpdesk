<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Email_configuration_model extends CI_Model
{
    protected $table = 'email_configurations';

    public function __construct()
    {
        parent::__construct();
        $this->check_table_and_migrate();
    }

    /**
     * Pengecekan keberadaan tabel email_configurations dan migrasi data otomatis dari settings jika kosong
     */
    public function check_table_and_migrate()
    {
        if (!$this->db->table_exists($this->table)) {
            $sql = "CREATE TABLE IF NOT EXISTS `{$this->table}` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `title` varchar(100) NOT NULL COMMENT 'Nama Identitas Konfigurasi',
              `provider` varchar(50) NOT NULL DEFAULT 'custom' COMMENT 'gmail, brevo, hostinger, mailgun, amazon_ses, smtp2go, microsoft365, custom',
              `smtp_host` varchar(255) NOT NULL,
              `smtp_port` int(5) NOT NULL DEFAULT '465',
              `smtp_user` varchar(255) NOT NULL,
              `smtp_pass` text NOT NULL COMMENT 'Encrypted password',
              `smtp_crypto` enum('ssl','tls','none') NOT NULL DEFAULT 'ssl',
              `sender_name` varchar(150) NOT NULL,
              `sender_email` varchar(255) NOT NULL,
              `reply_to_name` varchar(150) DEFAULT NULL,
              `reply_to_email` varchar(255) DEFAULT NULL,
              `is_active` tinyint(1) NOT NULL DEFAULT '0',
              `last_test_at` datetime DEFAULT NULL,
              `last_test_status` enum('success','failed') DEFAULT NULL,
              `last_success_at` datetime DEFAULT NULL,
              `last_error_at` datetime DEFAULT NULL,
              `last_error_msg` text DEFAULT NULL,
              `created_by` varchar(50) DEFAULT NULL,
              `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            $this->db->query($sql);
        }
    }

    /**
     * Mengambil daftar preset provider standar
     */
    public function get_providers()
    {
        return [
            'gmail' => [
                'name'        => 'Gmail',
                'badge'       => 'bg-danger text-white',
                'icon'        => 'ti ti-brand-google text-danger',
                'smtp_host'   => 'ssl://smtp.googlemail.com',
                'smtp_port'   => 465,
                'smtp_crypto' => 'ssl',
                'note'        => 'Gunakan App Password 16 karakter dari Google Security Account.'
            ],
            'brevo' => [
                'name'        => 'Brevo (Sendinblue)',
                'badge'       => 'bg-primary text-white',
                'icon'        => 'ti ti-send text-primary',
                'smtp_host'   => 'smtp-relay.brevo.com',
                'smtp_port'   => 587,
                'smtp_crypto' => 'tls',
                'note'        => 'Gunakan SMTP Key yang dibuat di dashboard Brevo.'
            ],
            'hostinger' => [
                'name'        => 'Hostinger',
                'badge'       => 'bg-purple text-white',
                'icon'        => 'ti ti-server text-purple',
                'smtp_host'   => 'smtp.hostinger.com',
                'smtp_port'   => 465,
                'smtp_crypto' => 'ssl',
                'note'        => 'Gunakan alamat email dan password akun email Hostinger Anda.'
            ],
            'mailgun' => [
                'name'        => 'Mailgun',
                'badge'       => 'bg-warning text-dark',
                'icon'        => 'ti ti-mail-opened text-warning',
                'smtp_host'   => 'smtp.mailgun.org',
                'smtp_port'   => 587,
                'smtp_crypto' => 'tls',
                'note'        => 'Gunakan SMTP Credentials dari domain Mailgun Anda.'
            ],
            'amazon_ses' => [
                'name'        => 'Amazon SES',
                'badge'       => 'bg-warning text-dark',
                'icon'        => 'ti ti-brand-amazon text-warning',
                'smtp_host'   => 'email-smtp.us-east-1.amazonaws.com',
                'smtp_port'   => 587,
                'smtp_crypto' => 'tls',
                'note'        => 'Sesuaikan host dengan AWS Region (contoh: email-smtp.us-east-1.amazonaws.com).'
            ],
            'smtp2go' => [
                'name'        => 'SMTP2GO',
                'badge'       => 'bg-info text-white',
                'icon'        => 'ti ti-rocket text-info',
                'smtp_host'   => 'mail.smtp2go.com',
                'smtp_port'   => 2525,
                'smtp_crypto' => 'tls',
                'note'        => 'SMTP2GO mendukung port 2525, 587, 8025, atau 465 (SSL).'
            ],
            'microsoft365' => [
                'name'        => 'Microsoft 365 / Outlook',
                'badge'       => 'bg-info text-white',
                'icon'        => 'ti ti-brand-windows text-info',
                'smtp_host'   => 'smtp.office365.com',
                'smtp_port'   => 587,
                'smtp_crypto' => 'tls',
                'note'        => 'Pastikan Authenticated SMTP diaktifkan di Microsoft 365 Admin Center.'
            ],
            'custom' => [
                'name'        => 'Custom / Other SMTP',
                'badge'       => 'bg-secondary text-white',
                'icon'        => 'ti ti-settings text-secondary',
                'smtp_host'   => '',
                'smtp_port'   => 587,
                'smtp_crypto' => 'tls',
                'note'        => 'Isi detail server SMTP secara manual sesuai petunjuk penyedia hosting/email Anda.'
            ]
        ];
    }

    /**
     * Mengambil semua akun konfigurasi
     */
    public function get_all()
    {
        $this->db->order_by('is_active', 'DESC');
        $this->db->order_by('id', 'DESC');
        return $this->db->get($this->table)->result();
    }

    /**
     * Mengambil konfigurasi aktif saat ini
     */
    public function get_active()
    {
        $row = $this->db->get_where($this->table, ['is_active' => 1])->row();
        if (!$row) {
            // Fallback: ambil baris terbaru
            $row = $this->db->order_by('id', 'DESC')->get($this->table)->row();
        }
        return $row;
    }

    /**
     * Mengambil konfigurasi berdasarkan ID
     */
    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    /**
     * Menandai 1 akun sebagai Aktif (dan nonaktifkan yang lain)
     */
    public function set_active($id)
    {
        $this->db->trans_begin();
        // Setel semua is_active = 0
        $this->db->update($this->table, ['is_active' => 0]);
        // Setel id target is_active = 1
        $this->db->update($this->table, ['is_active' => 1], ['id' => $id]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    /**
     * Simpan / Edit data konfigurasi email
     */
    public function save($data, $id = null)
    {
        $this->load->library('encryption');

        // Encrypt pass jika diisi (fallback ke plain text jika encryption_key belum diset di config)
        if (!empty($data['smtp_pass'])) {
            $enc_pass = $this->encryption->encrypt($data['smtp_pass']);
            $data['smtp_pass'] = ($enc_pass !== FALSE && $enc_pass !== '') ? $enc_pass : $data['smtp_pass'];
        } else {
            unset($data['smtp_pass']); // Jika edit dan password tidak diisi ulang, pertahankan yang lama
        }

        if (isset($data['is_active']) && $data['is_active'] == 1) {
            $this->db->update($this->table, ['is_active' => 0]);
        }

        if ($id) {
            $data['updated_at'] = date('Y-m-d H:i:s');
            $this->db->where('id', $id);
            return $this->db->update($this->table, $data);
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $inserted = $this->db->insert($this->table, $data);
            $new_id = $this->db->insert_id();

            // Jika ini record pertama, jadikan aktif secara otomatis
            if ($this->db->count_all($this->table) == 1) {
                $this->db->update($this->table, ['is_active' => 1], ['id' => $new_id]);
            }
            return $inserted;
        }
    }

    /**
     * Hapus konfigurasi
     */
    public function delete($id)
    {
        $config = $this->get_by_id($id);
        $res = $this->db->delete($this->table, ['id' => $id]);

        // Jika yang dihapus adalah akun aktif, aktifkan record terbaru yang ada
        if ($config && $config->is_active == 1) {
            $latest = $this->db->order_by('id', 'DESC')->get($this->table)->row();
            if ($latest) {
                $this->db->update($this->table, ['is_active' => 1], ['id' => $latest->id]);
            }
        }

        return $res;
    }

    /**
     * Update log status SMTP (Last Test, Last Success, Last Error)
     */
    public function update_status($id, $status_data)
    {
        if (!$id) return false;
        $this->db->where('id', $id);
        return $this->db->update($this->table, $status_data);
    }

    /**
     * Mengirim email menggunakan konfigurasi SMTP aktif saat ini
     */
    public function send_email_active($target_email, $subject, $htmlMessage)
    {
        $cfg = $this->get_active();
        if (!$cfg) {
            return ['status' => false, 'message' => 'Konfigurasi email aktif belum diatur.'];
        }

        $this->load->library('encryption');
        $smtp_host    = $cfg->smtp_host;
        $smtp_port    = $cfg->smtp_port;
        $smtp_user    = $cfg->smtp_user;
        $decrypted    = $this->encryption->decrypt($cfg->smtp_pass);
        $smtp_pass    = ($decrypted !== FALSE && $decrypted !== '') ? $decrypted : $cfg->smtp_pass;
        $smtp_crypto  = $cfg->smtp_crypto;
        $sender_name  = $cfg->sender_name;
        $sender_email = $cfg->sender_email;
        $reply_name   = $cfg->reply_to_name;
        $reply_email  = $cfg->reply_to_email;

        $clean_host = str_replace(['ssl://', 'tls://'], '', $smtp_host);

        $config = [
            'protocol'    => 'smtp',
            'smtp_host'   => $clean_host,
            'smtp_port'   => $smtp_port > 0 ? $smtp_port : 465,
            'smtp_user'   => $smtp_user,
            'smtp_pass'   => $smtp_pass,
            'smtp_crypto' => $smtp_crypto ? $smtp_crypto : 'ssl',
            'mailtype'    => 'html',
            'charset'     => 'utf-8',
            'newline'     => "\r\n",
            'crlf'        => "\r\n",
            'wordwrap'    => TRUE
        ];

        $this->load->library('email');
        $this->email->initialize($config);
        $this->email->clear();

        $this->email->from($sender_email, !empty($sender_name) ? $sender_name : 'Helpdesk System');
        if (!empty($reply_email)) {
            $this->email->reply_to($reply_email, !empty($reply_name) ? $reply_name : $sender_name);
        }
        $this->email->to($target_email);
        $this->email->subject($subject);
        $this->email->message($htmlMessage);

        if ($this->email->send()) {
            return ['status' => true, 'message' => 'Email berhasil dikirim.'];
        } else {
            $error_msg = $this->email->print_debugger(['headers']);
            $clean_error = strip_tags($error_msg);
            if (strpos($clean_error, 'The following SMTP error was encountered:') !== false) {
                $parts = explode('The following SMTP error was encountered:', $clean_error);
                $clean_error = 'SMTP Error: ' . trim(end($parts));
            }
            if (strlen($clean_error) > 300) {
                $clean_error = substr($clean_error, 0, 300);
            }
            return ['status' => false, 'message' => $clean_error];
        }
    }
}
