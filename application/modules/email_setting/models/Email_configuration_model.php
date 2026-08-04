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

        // Auto-migrate dari tabel settings jika email_configurations masih kosong
        if ($this->db->count_all($this->table) == 0) {
            $this->db->like('setting_name', 'smtp_');
            $settings = $this->db->get('settings')->result();

            $existing = [];
            foreach ($settings as $s) {
                $existing[$s->setting_name] = $s->value;
            }

            if (!empty($existing['smtp_user'])) {
                $host = isset($existing['smtp_host']) ? $existing['smtp_host'] : 'ssl://smtp.googlemail.com';
                $provider = 'custom';
                if (strpos($host, 'googlemail.com') !== false || strpos($host, 'gmail.com') !== false) {
                    $provider = 'gmail';
                }

                $pass = isset($existing['smtp_pass']) ? $existing['smtp_pass'] : '';

                $insert_data = [
                    'title'         => 'Konfigurasi Utama (Migrasi)',
                    'provider'      => $provider,
                    'smtp_host'     => $host,
                    'smtp_port'     => isset($existing['smtp_port']) ? $existing['smtp_port'] : 465,
                    'smtp_user'     => $existing['smtp_user'],
                    'smtp_pass'     => $pass,
                    'smtp_crypto'   => isset($existing['smtp_crypto']) ? $existing['smtp_crypto'] : 'ssl',
                    'sender_name'   => 'E-Library Notification System',
                    'sender_email'  => $existing['smtp_user'],
                    'is_active'     => 1,
                    'created_at'    => date('Y-m-d H:i:s')
                ];
                $this->db->insert($this->table, $insert_data);
            }
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
                'badge'       => 'badge-danger',
                'icon'        => 'fab fa-google text-danger',
                'smtp_host'   => 'ssl://smtp.googlemail.com',
                'smtp_port'   => 465,
                'smtp_crypto' => 'ssl',
                'note'        => 'Gunakan App Password 16 karakter dari Google Security Account.'
            ],
            'brevo' => [
                'name'        => 'Brevo (Sendinblue)',
                'badge'       => 'badge-primary',
                'icon'        => 'fas fa-paper-plane text-primary',
                'smtp_host'   => 'smtp-relay.brevo.com',
                'smtp_port'   => 587,
                'smtp_crypto' => 'tls',
                'note'        => 'Gunakan SMTP Key yang dibuat di dashboard Brevo.'
            ],
            'hostinger' => [
                'name'        => 'Hostinger',
                'badge'       => 'badge-purple',
                'icon'        => 'fas fa-server text-purple',
                'smtp_host'   => 'smtp.hostinger.com',
                'smtp_port'   => 465,
                'smtp_crypto' => 'ssl',
                'note'        => 'Gunakan alamat email dan password akun email Hostinger Anda.'
            ],
            'mailgun' => [
                'name'        => 'Mailgun',
                'badge'       => 'badge-warning',
                'icon'        => 'fas fa-envelope-open text-warning',
                'smtp_host'   => 'smtp.mailgun.org',
                'smtp_port'   => 587,
                'smtp_crypto' => 'tls',
                'note'        => 'Gunakan SMTP Credentials dari domain Mailgun Anda.'
            ],
            'amazon_ses' => [
                'name'        => 'Amazon SES',
                'badge'       => 'badge-warning',
                'icon'        => 'fab fa-aws text-warning',
                'smtp_host'   => 'email-smtp.us-east-1.amazonaws.com',
                'smtp_port'   => 587,
                'smtp_crypto' => 'tls',
                'note'        => 'Sesuaikan host dengan AWS Region (contoh: email-smtp.us-east-1.amazonaws.com).'
            ],
            'smtp2go' => [
                'name'        => 'SMTP2GO',
                'badge'       => 'badge-info',
                'icon'        => 'fas fa-rocket text-info',
                'smtp_host'   => 'mail.smtp2go.com',
                'smtp_port'   => 2525,
                'smtp_crypto' => 'tls',
                'note'        => 'SMTP2GO mendukung port 2525, 587, 8025, atau 465 (SSL).'
            ],
            'microsoft365' => [
                'name'        => 'Microsoft 365 / Outlook',
                'badge'       => 'badge-info',
                'icon'        => 'fab fa-microsoft text-info',
                'smtp_host'   => 'smtp.office365.com',
                'smtp_port'   => 587,
                'smtp_crypto' => 'tls',
                'note'        => 'Pastikan Authenticated SMTP diaktifkan di Microsoft 365 Admin Center.'
            ],
            'custom' => [
                'name'        => 'Custom SMTP',
                'badge'       => 'badge-secondary',
                'icon'        => 'fas fa-cogs text-secondary',
                'smtp_host'   => '',
                'smtp_port'   => 587,
                'smtp_crypto' => 'tls',
                'note'        => 'Isi konfigurasi SMTP server secara manual sesuai provider Anda.'
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

        // Encrypt pass jika diisi
        if (!empty($data['smtp_pass'])) {
            $data['smtp_pass'] = $this->encryption->encrypt($data['smtp_pass']);
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
}
