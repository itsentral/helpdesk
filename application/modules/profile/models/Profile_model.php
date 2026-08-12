<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Profile_model extends BF_Model
{
    protected $ENABLE_ADD;
    protected $ENABLE_MANAGE;
    protected $ENABLE_VIEW;
    protected $ENABLE_DELETE;

    public function __construct()
    {
        parent::__construct();

        $this->ENABLE_ADD     = has_permission('Profile.Add');
        $this->ENABLE_MANAGE  = has_permission('Profile.Manage');
        $this->ENABLE_VIEW    = has_permission('Profile.View');
        $this->ENABLE_DELETE  = has_permission('Profile.Delete');

        $this->check_tables_and_columns();
    }

    /**
     * Memastikan kolom is_email_verified & email_verified_at pada tabel users serta tabel user_email_otps sudah ada
     */
    public function check_tables_and_columns()
    {
        if ($this->db->table_exists('users')) {
            if (!$this->db->field_exists('is_email_verified', 'users')) {
                $this->db->query("ALTER TABLE `users` ADD COLUMN `is_email_verified` TINYINT(1) NOT NULL DEFAULT 0;");
            }
            if (!$this->db->field_exists('email_verified_at', 'users')) {
                $this->db->query("ALTER TABLE `users` ADD COLUMN `email_verified_at` DATETIME NULL;");
            }
            if (!$this->db->field_exists('notif_email_ticket', 'users')) {
                $this->db->query("ALTER TABLE `users` ADD COLUMN `notif_email_ticket` TINYINT(1) NOT NULL DEFAULT 1;");
            }
            if (!$this->db->field_exists('notif_email_pm', 'users')) {
                $this->db->query("ALTER TABLE `users` ADD COLUMN `notif_email_pm` TINYINT(1) NOT NULL DEFAULT 1;");
            }
        }

        if (!$this->db->table_exists('user_email_otps')) {
            $sql = "CREATE TABLE IF NOT EXISTS `user_email_otps` (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `user_id` VARCHAR(50) NOT NULL,
              `email` VARCHAR(255) NOT NULL,
              `otp_code` VARCHAR(10) NOT NULL,
              `expires_at` DATETIME NOT NULL,
              `is_used` TINYINT(1) NOT NULL DEFAULT 0,
              `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`),
              INDEX `idx_user_email` (`user_id`, `email`),
              INDEX `idx_otp` (`otp_code`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            $this->db->query($sql);
        }
    }

    public function get_user_by_id($id_user)
    {
        $this->db->where('id_user', $id_user);
        return $this->db->get('users')->row();
    }

    public function get_client_apps($id_user)
    {
        $this->db->select("GROUP_CONCAT(helpdesk_client.name_app SEPARATOR ', ') as client_apps");
        $this->db->from('helpdesk_user_client');
        $this->db->join(
            'helpdesk_client',
            'helpdesk_client.id = helpdesk_user_client.client_id 
             AND helpdesk_client.is_delete = 0',
            'left'
        );
        $this->db->where('helpdesk_user_client.id_user', $id_user);
        $this->db->where('helpdesk_user_client.is_active', 1);

        return $this->db->get()->row();
    }

    public function update_password($id_user, $password_hashed)
    {
        return $this->db
            ->where('id_user', $id_user)
            ->update('users', [
                'password' => $password_hashed
            ]);
    }

    public function is_username_exist($username, $id_user)
    {
        return $this->db
            ->where('username', $username)
            ->where('id_user !=', $id_user)
            ->get('users')
            ->num_rows();
    }

    public function update_username($id_user, $username)
    {
        return $this->db
            ->where('id_user', $id_user)
            ->update('users', [
                'username' => $username
            ]);
    }

    public function get_user_photo($id_user)
    {
        return $this->db
            ->select('photo')
            ->where('id_user', $id_user)
            ->get('users')
            ->row();
    }

    public function update_photo($id_user, $photo_name)
    {
        return $this->db
            ->where('id_user', $id_user)
            ->update('users', [
                'photo' => $photo_name
            ]);
    }

    public function delete_photo($id_user)
    {
        return $this->db
            ->where('id_user', $id_user)
            ->update('users', [
                'photo' => NULL
            ]);
    }

    /**
     * Simpan record OTP baru
     */
    public function save_otp($user_id, $email, $otp_code, $expires_at)
    {
        $data = [
            'user_id'    => $user_id,
            'email'      => $email,
            'otp_code'   => $otp_code,
            'expires_at' => $expires_at,
            'is_used'    => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];
        return $this->db->insert('user_email_otps', $data);
    }

    /**
     * Ambil OTP terakhir yang dibuat untuk user & email ini
     */
    public function get_last_otp($user_id, $email)
    {
        return $this->db
            ->where('user_id', $user_id)
            ->where('email', $email)
            ->order_by('id', 'DESC')
            ->get('user_email_otps')
            ->row();
    }

    /**
     * Cari OTP aktif & valid
     */
    public function get_valid_otp($user_id, $email, $otp_code)
    {
        $now = date('Y-m-d H:i:s');
        return $this->db
            ->where('user_id', $user_id)
            ->where('email', $email)
            ->where('otp_code', $otp_code)
            ->where('is_used', 0)
            ->where('expires_at >=', $now)
            ->order_by('id', 'DESC')
            ->get('user_email_otps')
            ->row();
    }

    /**
     * Tandai OTP sudah digunakan
     */
    public function mark_otp_used($otp_id)
    {
        return $this->db
            ->where('id', $otp_id)
            ->update('user_email_otps', ['is_used' => 1]);
    }

    /**
     * Update email pengguna dan status is_email_verified = 1
     */
    public function update_user_email_verified($user_id, $email)
    {
        $data = [
            'email'             => $email,
            'is_email_verified' => 1,
            'email_verified_at' => date('Y-m-d H:i:s')
        ];
        return $this->db
            ->where('id_user', $user_id)
            ->update('users', $data);
    }

    /**
     * Update preferensi notifikasi email (ticket & project management)
     */
    public function update_email_notifications($id_user, $field, $value)
    {
        if (!in_array($field, ['notif_email_ticket', 'notif_email_pm'])) {
            return false;
        }
        return $this->db
            ->where('id_user', $id_user)
            ->update('users', [
                $field => $value ? 1 : 0
            ]);
    }
}
