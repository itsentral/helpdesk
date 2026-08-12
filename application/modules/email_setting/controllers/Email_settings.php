<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author Antigravity
 *
 * Controller for Email Settings & Master Email Configurations
 */

class Email_settings extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('email_setting/Email_configuration_model', 'email_cfg');
        $this->load->library('encryption');
        $this->template->set([
            'title' => 'Pengaturan & Master Server Email',
            'icon'  => 'fa fa-envelope'
        ]);
    }

    /**
     * Halaman Master List Pengaturan Email
     */
    public function index()
    {
        $configs   = $this->email_cfg->get_all();
        $providers = $this->email_cfg->get_providers();
        $active    = $this->email_cfg->get_active();

        $this->template->set([
            'configs'   => $configs,
            'providers' => $providers,
            'active'    => $active
        ]);
        $this->template->render('email_settings');
    }

    /**
     * Detail Konfigurasi untuk Edit Form (AJAX)
     */
    public function get_config($id)
    {
        $config = $this->email_cfg->get_by_id($id);
        if (!$config) {
            echo json_encode(['status' => 0, 'msg' => 'Konfigurasi tidak ditemukan.']);
            return;
        }

        // Dekripsi password untuk keperluan form edit
        $decrypted_pass = '';
        if (!empty($config->smtp_pass)) {
            $dec = $this->encryption->decrypt($config->smtp_pass);
            $decrypted_pass = ($dec !== FALSE && $dec !== '') ? $dec : $config->smtp_pass;
        }

        $response = [
            'status' => 1,
            'data'   => [
                'id'             => $config->id,
                'title'          => $config->title,
                'provider'       => $config->provider,
                'smtp_host'      => $config->smtp_host,
                'smtp_port'      => $config->smtp_port,
                'smtp_user'      => $config->smtp_user,
                'smtp_pass'      => $decrypted_pass,
                'smtp_crypto'    => $config->smtp_crypto,
                'sender_name'    => $config->sender_name,
                'sender_email'   => $config->sender_email,
                'reply_to_name'  => $config->reply_to_name,
                'reply_to_email' => $config->reply_to_email,
                'is_active'      => $config->is_active
            ]
        ];

        echo json_encode($response);
    }

    /**
     * Simpan / Tambah / Update Konfigurasi Email (AJAX)
     */
    public function save_config()
    {
        $post = $this->input->post();
        if (!$post) {
            echo json_encode(['status' => 0, 'msg' => 'Data tidak valid.']);
            return;
        }

        $id = isset($post['id']) && !empty($post['id']) ? $post['id'] : null;

        $title        = trim($post['title']);
        $provider     = trim($post['provider']);
        $smtp_host    = trim($post['smtp_host']);
        $smtp_port    = intval($post['smtp_port']);
        $smtp_user    = trim($post['smtp_user']);
        $smtp_pass    = trim($post['smtp_pass']);
        $smtp_crypto  = trim($post['smtp_crypto']);
        $sender_name  = trim($post['sender_name']);
        $sender_email = trim($post['sender_email']);

        if (empty($title) || empty($smtp_host) || empty($smtp_user) || empty($sender_email)) {
            echo json_encode(['status' => 0, 'msg' => 'Harap lengkapi semua bidang wajib (Judul, Host, Username, & Sender Email).']);
            return;
        }

        $save_data = [
            'title'          => $title,
            'provider'       => !empty($provider) ? $provider : 'custom',
            'smtp_host'      => $smtp_host,
            'smtp_port'      => $smtp_port > 0 ? $smtp_port : 587,
            'smtp_user'      => $smtp_user,
            'smtp_pass'      => $smtp_pass,
            'smtp_crypto'    => in_array($smtp_crypto, ['ssl', 'tls', 'none']) ? $smtp_crypto : 'tls',
            'sender_name'    => !empty($sender_name) ? $sender_name : 'Helpdesk System',
            'sender_email'   => $sender_email,
            'reply_to_name'  => !empty($post['reply_to_name']) ? trim($post['reply_to_name']) : NULL,
            'reply_to_email' => !empty($post['reply_to_email']) ? trim($post['reply_to_email']) : NULL,
            'is_active'      => isset($post['is_active']) && $post['is_active'] == 1 ? 1 : 0
        ];

        $res = $this->email_cfg->save($save_data, $id);

        if ($res) {
            echo json_encode(['status' => 1, 'msg' => 'Konfigurasi email berhasil disimpan.']);
        } else {
            echo json_encode(['status' => 0, 'msg' => 'Gagal menyimpan konfigurasi email.']);
        }
    }

    /**
     * Setel Konfigurasi Email sebagai Aktif (AJAX)
     */
    public function set_active()
    {
        $id = $this->input->post('id');
        if (!$id) {
            echo json_encode(['status' => 0, 'msg' => 'ID Konfigurasi tidak valid.']);
            return;
        }

        $res = $this->email_cfg->set_active($id);
        if ($res) {
            echo json_encode(['status' => 1, 'msg' => 'Konfigurasi email aktif berhasil diubah.']);
        } else {
            echo json_encode(['status' => 0, 'msg' => 'Gagal mengubah status aktif.']);
        }
    }

    /**
     * Hapus Konfigurasi Email (AJAX)
     */
    public function delete_config()
    {
        $id = $this->input->post('id');
        if (!$id) {
            echo json_encode(['status' => 0, 'msg' => 'ID Konfigurasi tidak valid.']);
            return;
        }

        $res = $this->email_cfg->delete($id);
        if ($res) {
            echo json_encode(['status' => 1, 'msg' => 'Konfigurasi email berhasil dihapus.']);
        } else {
            echo json_encode(['status' => 0, 'msg' => 'Gagal menghapus konfigurasi email.']);
        }
    }

    /**
     * Pengujian Pengiriman Email (Direct Live Test & Log Status SMTP)
     */
    public function test_config()
    {
        $post = $this->input->post();
        if (!$post) {
            echo json_encode(['status' => 0, 'msg' => 'Data pengujian tidak valid.']);
            return;
        }

        $target_email = trim($post['target_email']);
        if (empty($target_email) || !filter_var($target_email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['status' => 0, 'msg' => 'Alamat email tujuan test tidak valid.']);
            return;
        }

        $config_id = isset($post['config_id']) && !empty($post['config_id']) ? $post['config_id'] : null;

        // Ambil data konfigurasi: dari ID jika diset, atau dari input form modal
        if ($config_id) {
            $cfg = $this->email_cfg->get_by_id($config_id);
            if (!$cfg) {
                echo json_encode(['status' => 0, 'msg' => 'Konfigurasi email tidak ditemukan.']);
                return;
            }
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
        } else {
            // Ambil dari input form
            $smtp_host    = trim($post['smtp_host']);
            $smtp_port    = intval($post['smtp_port']);
            $smtp_user    = trim($post['smtp_user']);
            $smtp_pass    = trim($post['smtp_pass']);
            $smtp_crypto  = trim($post['smtp_crypto']);
            $sender_name  = trim($post['sender_name']);
            $sender_email = trim($post['sender_email']);
            $reply_name   = !empty($post['reply_to_name']) ? trim($post['reply_to_name']) : null;
            $reply_email  = !empty($post['reply_to_email']) ? trim($post['reply_to_email']) : null;
        }

        if (empty($smtp_host) || empty($smtp_user) || empty($smtp_pass) || empty($sender_email)) {
            echo json_encode(['status' => 0, 'msg' => 'Pengaturan SMTP & Sender harus diisi lengkap untuk pengujian.']);
            return;
        }

        // Clean host dari ssl:// atau tls://
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

        $this->email->from($sender_email, !empty($sender_name) ? $sender_name : 'Helpdesk Notification System');
        if (!empty($reply_email)) {
            $this->email->reply_to($reply_email, !empty($reply_name) ? $reply_name : $sender_name);
        }
        $this->email->to($target_email);

        $subject = "Helpdesk: Test Configuration Server - " . date('d M Y H:i:s');
        $htmlMessage = '
        <div style="font-family: Arial, sans-serif; padding: 20px; border: 1px solid #e0e0e0; border-radius: 8px; max-width: 600px; margin: 0 auto; background-color: #ffffff;">
            <h2 style="color: #2b77d9; margin-top: 0;"><i class="fa fa-check-circle"></i> Uji Coba Email Server Berhasil!</h2>
            <p>Halo,</p>
            <p>Jika Anda menerima email ini, berarti pengujian konfigurasi SMTP/Server Email di aplikasi <strong>Helpdesk System</strong> telah sukses beroperasi.</p>
            <table style="width: 100%; border-collapse: collapse; margin-top: 15px; background: #f9f9f9; padding: 10px; border-radius: 5px;">
                <tr><td style="padding: 6px; font-weight: bold; width: 35%;">Server / Provider:</td><td style="padding: 6px;">' . htmlspecialchars($smtp_host) . '</td></tr>
                <tr><td style="padding: 6px; font-weight: bold;">Port & Crypto:</td><td style="padding: 6px;">' . $smtp_port . ' (' . strtoupper($smtp_crypto) . ')</td></tr>
                <tr><td style="padding: 6px; font-weight: bold;">Sender Email:</td><td style="padding: 6px;">' . htmlspecialchars($sender_email) . '</td></tr>
                <tr><td style="padding: 6px; font-weight: bold;">Waktu Pengujian:</td><td style="padding: 6px;">' . date('d M Y H:i:s') . ' WIB</td></tr>
            </table>
            <p style="margin-top: 20px; font-size: 12px; color: #888;">Email ini dikirim secara otomatis oleh fitur pengujian master email Helpdesk System.</p>
        </div>';

        $this->email->subject($subject);
        $this->email->message($htmlMessage);

        $now = date('Y-m-d H:i:s');
        if ($this->email->send()) {
            // Update Status Log jika config_id ada
            if ($config_id) {
                $this->email_cfg->update_status($config_id, [
                    'last_test_at'     => $now,
                    'last_test_status' => 'success',
                    'last_success_at'  => $now,
                    'last_error_msg'   => NULL
                ]);
            }
            echo json_encode([
                'status' => 1,
                'msg'    => 'Test email berhasil dikirim ke <strong>' . htmlspecialchars($target_email) . '</strong>! Silakan periksa inbox/spam folder Anda.'
            ]);
        } else {
            $error_msg = $this->email->print_debugger(['headers']);
            $clean_error = strip_tags($error_msg);
            if (strpos($clean_error, 'The following SMTP error was encountered:') !== false) {
                $parts = explode('The following SMTP error was encountered:', $clean_error);
                $clean_error = 'SMTP Error: ' . trim(end($parts));
            }
            if (strlen($clean_error) > 500) {
                $clean_error = substr($clean_error, 0, 500);
            }

            if ($config_id) {
                $this->email_cfg->update_status($config_id, [
                    'last_test_at'     => $now,
                    'last_test_status' => 'failed',
                    'last_error_at'    => $now,
                    'last_error_msg'   => $clean_error
                ]);
            }
            echo json_encode([
                'status' => 0,
                'msg'    => 'Pengujian email gagal: ' . htmlspecialchars($clean_error)
            ]);
        }
    }

    /**
     * Halaman Dashboard Antrean Email (Queue List)
     */
    public function queue()
    {
        $this->template->set([
            'title' => 'Daftar Antrean Email',
            'icon'  => 'fa fa-list-alt'
        ]);
        $this->template->render('email_queue');
    }

    /**
     * Datatables JSON untuk email queue list
     */
    public function get_queue_json()
    {
        $draw   = intval($this->input->post('draw'));
        $start  = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $post_search = $this->input->post('search');
        $search = is_array($post_search) && isset($post_search['value']) ? $post_search['value'] : '';
        $status = $this->input->post('status_filter');

        $this->db->from('email_queues');
        if (!empty($status)) {
            $this->db->where('status', $status);
        }
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('to_email', $search);
            $this->db->or_like('subject', $search);
            $this->db->group_end();
        }

        $totalFiltered = $this->db->count_all_results('', false);
        $totalRecords  = $this->db->count_all('email_queues');

        if (!empty($status)) {
            $this->db->where('status', $status);
        }
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('to_email', $search);
            $this->db->or_like('subject', $search);
            $this->db->group_end();
        }
        $this->db->order_by('id', 'DESC');
        if ($length > 0) {
            $this->db->limit($length, $start);
        }
        $query = $this->db->get('email_queues');
        $results = $query->result();

        $data = [];
        $no = $start + 1;
        foreach ($results as $r) {
            $status_badge = '';
            if ($r->status == 'PND') {
                $status_badge = '<span class="badge badge-warning"><i class="fa fa-clock-o mr-1"></i> Pending</span>';
            } elseif ($r->status == 'PRG') {
                $status_badge = '<span class="badge badge-info"><i class="fa fa-spinner fa-spin mr-1"></i> Processing</span>';
            } elseif ($r->status == 'SND') {
                $status_badge = '<span class="badge badge-success"><i class="fa fa-check mr-1"></i> Sent</span>';
            } else {
                $status_badge = '<span class="badge badge-danger" title="' . htmlspecialchars($r->error_msg) . '"><i class="fa fa-exclamation-triangle mr-1"></i> Failed</span>';
            }

            $actions = '<button type="button" class="btn btn-sm btn-info btn-preview" data-id="' . $r->id . '" title="Preview Email"><i class="fa fa-eye"></i></button> ';
            if ($r->status == 'FAI') {
                $actions .= '<button type="button" class="btn btn-sm btn-warning btn-resend" data-id="' . $r->id . '" title="Kirim Ulang"><i class="fa fa-refresh"></i></button>';
            }

            $data[] = [
                'no'         => $no++,
                'id'         => $r->id,
                'to_email'   => htmlspecialchars($r->to_email),
                'subject'    => htmlspecialchars($r->subject),
                'status'     => $status_badge,
                'attempts'   => $r->attempts,
                'created_at' => $r->created_at ? date('d-m-Y H:i', strtotime($r->created_at)) : '-',
                'sent_at'    => $r->sent_at ? date('d-m-Y H:i', strtotime($r->sent_at)) : '-',
                'actions'    => $actions
            ];
        }

        echo json_encode([
            'draw'            => $draw,
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data'            => $data
        ]);
    }

    /**
     * Memasukkan kembali email yang gagal ke antrean (Resend)
     */
    public function resend_queue()
    {
        $id = $this->input->post('id');
        if (!$id) {
            echo json_encode(['status' => 0, 'msg' => 'ID tidak valid.']);
            return;
        }

        $this->db->where('id', $id);
        $this->db->where('status', 'FAI');
        $this->db->update('email_queues', [
            'status'    => 'PND',
            'attempts'  => 0,
            'error_msg' => NULL
        ]);

        if ($this->db->affected_rows() > 0) {
            // Trigger background worker
            $this->load->library('email_runner');
            $this->email_runner->trigger_worker();
            echo json_encode(['status' => 1, 'msg' => 'Email berhasil dimasukkan kembali ke antrean.']);
        } else {
            echo json_encode(['status' => 0, 'msg' => 'Email tidak ditemukan atau bukan status Failed.']);
        }
    }

    /**
     * Hapus semua email yang sudah terkirim (status SND) dari queue
     */
    public function clear_sent()
    {
        $this->db->where('status', 'SND');
        $this->db->delete('email_queues');

        echo json_encode(['status' => 1, 'msg' => 'Riwayat email terkirim berhasil dibersihkan.']);
    }

    /**
     * Get summary counts for email queue dashboard
     */
    public function get_queue_counts()
    {
        $pending = $this->db->where_in('status', ['PND', 'PRG'])->count_all_results('email_queues');
        $sent    = $this->db->where('status', 'SND')->count_all_results('email_queues');
        $failed  = $this->db->where('status', 'FAI')->count_all_results('email_queues');
        $total   = $this->db->count_all('email_queues');

        echo json_encode([
            'status'  => 1,
            'pending' => $pending,
            'sent'    => $sent,
            'failed'  => $failed,
            'total'   => $total
        ]);
    }

    /**
     * Editor Template Email
     */
    /**
     * Editor Template Email
     */
    public function template()
    {
        $tmpl_file = APPPATH . 'modules/email_setting/views/email_template.php';
        $full_html = file_exists($tmpl_file) ? file_get_contents($tmpl_file) : '';

        preg_match('/<style>(.*?)<\/style>/s', $full_html, $css_match);
        $template_css = isset($css_match[1]) ? trim($css_match[1]) : '';

        preg_match('/<body.*?>(.*?)<\/body>/s', $full_html, $body_match);
        $template_body = isset($body_match[1]) ? trim($body_match[1]) : $full_html;

        $email_vars = [
            'email_vars_company_name'    => '',
            'email_vars_company_address' => '',
            'email_vars_company_logo'    => ''
        ];

        $this->template->set([
            'title'         => 'Edit Template Email',
            'icon'          => 'fa fa-edit',
            'template_body' => $template_body,
            'template_css'  => $template_css,
            'email_vars'    => $email_vars
        ]);
        $this->template->render('email_template_editor');
    }

    public function save_template()
    {
        $template_body = $this->input->post('template_body');
        $template_css  = $this->input->post('template_css');

        if ($template_body) {
            $tmpl_file = APPPATH . 'modules/email_setting/views/email_template.php';
            $full_html = '<!DOCTYPE html>' . "\n" . '<html lang="en">' . "\n" . '<head>' . "\n" . '    <meta charset="UTF-8">' . "\n" . '    <meta name="viewport" content="width=device-width, initial-scale=1.0">' . "\n" . '    <style>' . "\n" . $template_css . "\n" . '    </style>' . "\n" . '</head>' . "\n" . '<body class="email-template">' . "\n" . $template_body . "\n" . '</body>' . "\n" . '</html>';

            @file_put_contents($tmpl_file, $full_html);
            $return = ['status' => 1, 'msg' => 'Template email berhasil disimpan.'];
        } else {
            $return = ['status' => 0, 'msg' => 'Konten template tidak boleh kosong.'];
        }

        echo json_encode($return);
    }

    /**
     * Preview queued email HTML fully compiled
     */
    public function preview($id)
    {
        $q = $this->db->get_where('email_queues', ['id' => $id])->row();
        if ($q) {
            $company = $this->db->get_where('companies', ['id_perusahaan' => $q->company_id])->row();

            $tmpl_file = APPPATH . 'modules/email_setting/views/email_template.php';
            if (file_exists($tmpl_file)) {
                $htmlMessage = file_get_contents($tmpl_file);
            } else {
                $htmlMessage = '<div>{{content}}</div>';
            }

            $htmlMessage = str_replace('{{content}}', $q->message, $htmlMessage);
            $htmlMessage = str_replace('{{subject}}', $q->subject, $htmlMessage);

            $final_name    = $company ? $company->nm_perusahaan : '';
            $final_address = $company ? $company->alamat : '';
            $final_logo    = ($company && !empty($company->logo)) ? base_url($company->path_logo . $company->id_perusahaan . '/' . $company->logo) : '';

            $htmlMessage = str_replace('{{company_name}}', $final_name, $htmlMessage);
            $htmlMessage = str_replace('{{company_address}}', $final_address, $htmlMessage);
            $htmlMessage = str_replace('{{company_logo}}', $final_logo, $htmlMessage);

            $final_url = (!empty($q->action_url)) ? $q->action_url : base_url();
            $htmlMessage = str_replace('{{action_url}}', $final_url, $htmlMessage);

            echo $htmlMessage;
        } else {
            echo "Email tidak ditemukan.";
        }
    }
}
