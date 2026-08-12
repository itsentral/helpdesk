<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Profile Controller
 * Manage user profile, photo, username, and password
 */
class Profile extends Admin_Controller
{
  protected $viewPermission = "Profile.View";
  protected $managePermission = "Profile.Manage";

  public function __construct()
  {
    parent::__construct();
    $this->load->model('Profile_model');
    $this->load->library('upload');
    $this->template->page_icon('fa fa-user');
  }

  public function index()
  {
    // $this->auth->restrict($this->viewPermission);

    $id_user = $this->auth->user_id();
    $data['user'] = $this->Profile_model->get_user_by_id($id_user);
    $client_data = $this->Profile_model->get_client_apps($id_user);
    $data['client_apps'] = $client_data ? $client_data->client_apps : '-';

    history("View profile");

    $this->template->title('My Profile');
    $this->template->render('index', $data);
  }


  public function update_photo()
  {
    // $this->auth->restrict($this->managePermission);

    $id_user = $this->auth->user_id();

    if (empty($_FILES['photo']['name'])) {
      echo json_encode([
        'status'  => 0,
        'message' => 'Tidak ada file yang diupload'
      ]);
      return;
    }

    $config['upload_path']   = './assets/images/users/';
    $config['allowed_types'] = 'jpg|jpeg|png|gif';
    $config['max_size']      = 2048;
    $config['encrypt_name']  = TRUE;

    // buat folder jika belum ada
    if (!is_dir($config['upload_path'])) {
      mkdir($config['upload_path'], 0777, TRUE);
    }

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('photo')) {
      echo json_encode([
        'status'  => 0,
        'message' => $this->upload->display_errors('', '')
      ]);
      return;
    }


    $upload_data = $this->upload->data();
    $photo_name  = $upload_data['file_name'];
    $old_photo = $this->Profile_model->get_user_photo($id_user);

    if ($old_photo && !empty($old_photo->photo)) {
      $old_path = './assets/images/users/' . $old_photo->photo;
      if (file_exists($old_path)) {
        unlink($old_path);
      }
    }

    $result = $this->Profile_model->update_photo($id_user, $photo_name);

    if ($result) {

      $keterangan = "SUKSES, update foto profile user ID: $id_user";
      $status = 1;

      $response = [
        'status'    => 1,
        'message'   => 'Foto profile berhasil diupdate',
        'photo_url' => base_url('assets/images/users/' . $photo_name)
      ];
    } else {

      $keterangan = "GAGAL, update foto profile user ID: $id_user";
      $status = 0;

      $response = [
        'status'  => 0,
        'message' => 'Gagal mengupdate foto profile'
      ];
    }

    $sql = $this->db->last_query();
    simpan_aktifitas(
      $this->managePermission,
      $id_user,
      $keterangan,
      1,
      $sql,
      $status
    );

    echo json_encode($response);
  }


  public function update_username()
  {
    // $this->auth->restrict($this->managePermission);

    $id_user = $this->auth->user_id();
    $new_username = $this->input->post('username');

    // Validation
    $this->form_validation->set_rules(
      'username',
      'Username',
      'required|min_length[4]|max_length[30]|alpha_dash'
    );

    if ($this->form_validation->run() == FALSE) {

      $response = [
        'status'  => 0,
        'message' => validation_errors()
      ];
    } else {



      // cek username
      $check = $this->Profile_model->is_username_exist(
        $new_username,
        $id_user
      );

      if ($check > 0) {

        $response = [
          'status'  => 0,
          'message' => 'Username sudah digunakan'
        ];
      } else {

        $result = $this->Profile_model->update_username(
          $id_user,
          $new_username
        );

        if ($result) {

          $keterangan = "SUKSES, update username user ID: $id_user menjadi $new_username";
          $status = 1;

          $response = [
            'status'  => 1,
            'message' => 'Username berhasil diupdate'
          ];
        } else {

          $keterangan = "GAGAL, update username user ID: $id_user";
          $status = 0;

          $response = [
            'status'  => 0,
            'message' => 'Gagal mengupdate username'
          ];
        }

        $sql = $this->db->last_query();
        simpan_aktifitas(
          $this->managePermission,
          $id_user,
          $keterangan,
          1,
          $sql,
          $status
        );
      }
    }

    echo json_encode($response);
  }


  public function update_password()
  {
    // $this->auth->restrict($this->managePermission);

    $id_user = $this->auth->user_id();

    $current_password = $this->input->post('current_password');
    $new_password     = $this->input->post('new_password');

    // Validation
    $this->form_validation->set_rules('current_password', 'Password Lama', 'required');
    $this->form_validation->set_rules('new_password', 'Password Baru', 'required|min_length[5]');
    $this->form_validation->set_rules('confirm_password', 'Konfirmasi Password', 'required|matches[new_password]');

    if ($this->form_validation->run() == FALSE) {

      $response = [
        'status'  => 0,
        'message' => validation_errors()
      ];
    } else {



      // ambil user
      $user = $this->Profile_model->get_user_by_id($id_user);

      // cek password lama
      if (!password_verify($current_password, $user->password)) {

        $response = [
          'status'  => 0,
          'message' => 'Password lama tidak sesuai'
        ];
      } else {

        /**
         * Generate cost bcrypt otomatis
         */
        $timeTarget = 0.05;
        $cost = 8;

        do {
          $cost++;
          $start = microtime(true);
          password_hash("test", PASSWORD_BCRYPT, ["cost" => $cost]);
          $end = microtime(true);
        } while (($end - $start) < $timeTarget);

        $password_hashed = password_hash(
          $new_password,
          PASSWORD_BCRYPT,
          ['cost' => $cost]
        );

        // update password
        $result = $this->Profile_model->update_password(
          $id_user,
          $password_hashed
        );

        if ($result) {

          $keterangan = "SUKSES, update password user ID: $id_user";
          $status = 1;

          $response = [
            'status'  => 1,
            'message' => 'Password berhasil diupdate'
          ];
        } else {

          $keterangan = "GAGAL, update password user ID: $id_user";
          $status = 0;

          $response = [
            'status'  => 0,
            'message' => 'Gagal mengupdate password'
          ];
        }

        $sql = $this->db->last_query();
        simpan_aktifitas(
          $this->managePermission,
          $id_user,
          $keterangan,
          1,
          $sql,
          $status
        );
      }
    }

    echo json_encode($response);
  }

  public function update_info()
  {
    // $this->auth->restrict($this->managePermission);

    $id_user = $this->auth->user_id();
    $current_user = $this->Profile_model->get_user_by_id($id_user);

    $this->form_validation->set_rules('nm_lengkap', 'Nama Lengkap', 'required');
    $this->form_validation->set_rules('hp', 'No. HP', 'required');
    $this->form_validation->set_rules('alamat', 'Alamat', 'required');
    $this->form_validation->set_rules('kota', 'Kota', 'required');

    $post_email = trim((string) $this->input->post('email'));
    if (!empty($post_email)) {
      $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
    }

    if ($this->form_validation->run() == FALSE) {
      $response = [
        'status' => 0,
        'message' => validation_errors()
      ];
    } else {
      $email_changed = false;
      if (!empty($post_email) && $current_user) {
        $email_changed = (strtolower($current_user->email) !== strtolower($post_email));
      }

      if ($email_changed) {
        $this->db->where('email', $post_email);
        $this->db->where('id_user !=', $id_user);
        $check = $this->db->get('users')->num_rows();

        if ($check > 0) {
          echo json_encode([
            'status'  => 0,
            'message' => 'Email sudah digunakan oleh pengguna lain.'
          ]);
          return;
        }
      }

      $data = [
        'nm_lengkap' => $this->input->post('nm_lengkap'),
        'hp'         => $this->input->post('hp'),
        'alamat'     => $this->input->post('alamat'),
        'kota'       => $this->input->post('kota')
      ];

      if (!empty($post_email) && !$email_changed) {
        $data['email'] = $post_email;
      }

      $this->db->where('id_user', $id_user);
      $result = $this->db->update('users', $data);

      if ($result) {
        $keterangan = "SUKSES, update informasi profile user ID: $id_user";
        $status = 1;

        $msg = 'Informasi profile berhasil diupdate.';
        if ($email_changed) {
          $msg .= ' Catatan: Untuk memperbarui alamat email ke (' . htmlspecialchars($post_email) . '), silakan klik tombol Verifikasi / OTP.';
        }

        $response = [
          'status'  => 1,
          'message' => $msg
        ];
      } else {
        $keterangan = "GAGAL, update informasi profile user ID: $id_user";
        $status = 0;

        $response = [
          'status'  => 0,
          'message' => 'Gagal mengupdate informasi profile'
        ];
      }

      $sql = $this->db->last_query();
      simpan_aktifitas($this->managePermission, $id_user, $keterangan, 1, $sql, $status);
    }

    echo json_encode($response);
  }

  /**
   * Kirim Kode OTP Verifikasi Email (AJAX)
   */
  public function send_email_otp()
  {
    $id_user = $this->auth->user_id();
    if (!$id_user) {
      echo json_encode(['status' => 0, 'message' => 'Sesi login telah berakhir.']);
      return;
    }

    $email = trim($this->input->post('email'));

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      echo json_encode(['status' => 0, 'message' => 'Alamat email tidak valid.']);
      return;
    }

    // Cek apakah email sudah terverifikasi sebelumnya dan sama dengan yang lama
    $current_user = $this->Profile_model->get_user_by_id($id_user);
    if ($current_user && isset($current_user->is_email_verified) && $current_user->is_email_verified == 1) {
      if (strtolower(trim($current_user->email)) === strtolower($email)) {
        echo json_encode([
          'status'  => 0,
          'message' => 'Email ini sudah terverifikasi sebelumnya. Jika ingin mengganti email, silakan masukkan alamat email yang berbeda.'
        ]);
        return;
      }
    }

    // Cek apakah email sudah digunakan oleh user lain
    $this->db->where('email', $email);
    $this->db->where('id_user !=', $id_user);
    $check = $this->db->get('users')->num_rows();

    if ($check > 0) {
      echo json_encode(['status' => 0, 'message' => 'Email ini sudah digunakan oleh akun lain.']);
      return;
    }

    // Cek rate limiting (minimal 60 detik sebelum minta OTP baru)
    $last_otp = $this->Profile_model->get_last_otp($id_user, $email);
    if ($last_otp) {
      $created_time = strtotime($last_otp->created_at);
      $time_diff = time() - $created_time;
      if ($time_diff < 60) {
        $remaining = 60 - $time_diff;
        echo json_encode([
          'status'  => 0,
          'message' => 'Harap tunggu ' . $remaining . ' detik sebelum meminta kode OTP kembali.'
        ]);
        return;
      }
    }

    // Generate OTP 6-digit angka
    $otp_code   = sprintf("%06d", mt_rand(1, 999999));
    $expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));

    // Simpan ke database
    $saved = $this->Profile_model->save_otp($id_user, $email, $otp_code, $expires_at);
    if (!$saved) {
      echo json_encode(['status' => 0, 'message' => 'Gagal membuat kode OTP. Silakan coba lagi.']);
      return;
    }

    // Load model email setting & kirim email OTP
    $this->load->model('email_setting/Email_configuration_model', 'email_cfg');

    $user_data = $this->Profile_model->get_user_by_id($id_user);
    $user_name = $user_data ? $user_data->nm_lengkap : 'Pengguna';

    $subject = "Kode OTP Verifikasi Email - Helpdesk System";
    $htmlMessage = '
    <div style="font-family: Arial, sans-serif; padding: 25px; border: 1px solid #e0e0e0; border-radius: 8px; max-width: 550px; margin: 0 auto; background-color: #ffffff;">
        <h2 style="color: #2563eb; margin-top: 0;">Verifikasi Email Anda</h2>
        <p>Halo <strong>' . htmlspecialchars($user_name) . '</strong>,</p>
        <p>Anda telah meminta kode OTP untuk verifikasi alamat email (<strong>' . htmlspecialchars($email) . '</strong>) pada akun Helpdesk System.</p>
        <div style="background-color: #f1f5f9; border-radius: 8px; padding: 15px; text-align: center; margin: 20px 0;">
            <span style="font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #1e293b;">' . $otp_code . '</span>
        </div>
        <p style="color: #64748b; font-size: 14px;">Kode OTP ini berlaku selama <strong>5 menit</strong>. Harap tidak membagikan kode ini kepada siapapun demi keamanan akun Anda.</p>
        <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 20px 0;" />
        <p style="font-size: 12px; color: #94a3b8;">Email ini dikirim secara otomatis oleh Helpdesk System. Jika Anda tidak merasa melakukan permintaan ini, silakan abaikan email ini.</p>
    </div>';

    $send_res = $this->email_cfg->send_email_active($email, $subject, $htmlMessage);

    if ($send_res['status']) {
      echo json_encode([
        'status'     => 1,
        'message'    => 'Kode OTP berhasil dikirim ke <strong>' . htmlspecialchars($email) . '</strong>. Silakan periksa inbox/spam email Anda.',
        'expires_in' => 300
      ]);
    } else {
      echo json_encode([
        'status'  => 0,
        'message' => 'Gagal mengirim email OTP: ' . $send_res['message']
      ]);
    }
  }

  /**
   * Verifikasi Kode OTP Email (AJAX)
   */
  public function verify_email_otp()
  {
    $id_user = $this->auth->user_id();
    if (!$id_user) {
      echo json_encode(['status' => 0, 'message' => 'Sesi login telah berakhir.']);
      return;
    }

    $email    = trim($this->input->post('email'));
    $otp_code = trim($this->input->post('otp_code'));

    if (empty($email) || empty($otp_code)) {
      echo json_encode(['status' => 0, 'message' => 'Email dan Kode OTP wajib diisi.']);
      return;
    }

    $valid_otp = $this->Profile_model->get_valid_otp($id_user, $email, $otp_code);

    if (!$valid_otp) {
      echo json_encode(['status' => 0, 'message' => 'Kode OTP salah atau telah kadaluarsa. Silakan minta kode baru.']);
      return;
    }

    // Tandai OTP terpakai
    $this->Profile_model->mark_otp_used($valid_otp->id);

    // Update email & flag is_email_verified di tabel users
    $update_res = $this->Profile_model->update_user_email_verified($id_user, $email);

    if ($update_res) {
      $keterangan = "SUKSES, verifikasi email OTP user ID: $id_user dengan email: $email";
      simpan_aktifitas($this->managePermission, $id_user, $keterangan, 1, $this->db->last_query(), 1);

      echo json_encode([
        'status'  => 1,
        'message' => 'Email berhasil diverifikasi!'
      ]);
    } else {
      echo json_encode([
        'status'  => 0,
        'message' => 'Gagal memperbarui status verifikasi email di database.'
      ]);
    }
  }

  public function delete_photo()
  {
    if (!$this->input->is_ajax_request()) {
      show_404();
      return;
    }

    $id_user = $this->auth->user_id();

    if (!$id_user) {
      echo json_encode([
        'status'  => 0,
        'message' => 'User tidak terautentikasi'
      ]);
      return;
    }

    $this->load->model('Profile_model');

    $user = $this->Profile_model->get_user_photo($id_user);

    if (!$user || empty($user->photo)) {
      echo json_encode([
        'status'  => 0,
        'message' => 'Tidak ada foto yang dapat dihapus'
      ]);
      return;
    }

    // hapus file fisik
    $file_path = './assets/images/users/' . $user->photo;
    if (file_exists($file_path)) {
      unlink($file_path);
    }

    $result = $this->Profile_model->delete_photo($id_user);

    if ($result !== FALSE) {

      $keterangan = "SUKSES, hapus foto profile user ID: $id_user";
      $status = 1;

      $response = [
        'status'         => 1,
        'message'        => 'Foto profile berhasil dihapus',
        'default_photo'  => base_url('assets/images/male-def.png')
      ];

      $sql = $this->db->last_query();
      simpan_aktifitas(
        $this->managePermission,
        $id_user,
        $keterangan,
        1,
        $sql,
        $status
      );
    } else {

      $response = [
        'status'  => 0,
        'message' => 'Gagal menghapus foto profile dari database'
      ];
    }

    echo json_encode($response);
  }

  /**
   * Update preferensi notifikasi email (AJAX)
   */
  public function update_notification_setting()
  {
    $id_user = $this->auth->user_id();
    if (!$id_user) {
      echo json_encode(['status' => 0, 'message' => 'Sesi login telah berakhir.']);
      return;
    }

    $user = $this->Profile_model->get_user_by_id($id_user);
    if (!$user || (isset($user->is_email_verified) && $user->is_email_verified != 1)) {
      echo json_encode([
        'status'  => 0,
        'message' => 'Anda harus memverifikasi email terlebih dahulu untuk mengatur notifikasi email.'
      ]);
      return;
    }

    $field = trim((string)$this->input->post('field'));
    $val   = intval($this->input->post('value'));

    if (!in_array($field, ['notif_email_ticket', 'notif_email_pm'])) {
      echo json_encode(['status' => 0, 'message' => 'Pengaturan tidak valid.']);
      return;
    }

    $result = $this->Profile_model->update_email_notifications($id_user, $field, $val);

    if ($result) {
      $field_label = ($field === 'notif_email_ticket') ? 'Notifikasi Email Ticket' : 'Notifikasi Email Project Management';
      $status_label = ($val == 1) ? 'diaktifkan' : 'dinonaktifkan';

      echo json_encode([
        'status'  => 1,
        'message' => $field_label . ' berhasil ' . $status_label . '.'
      ]);
    } else {
      echo json_encode([
        'status'  => 0,
        'message' => 'Gagal memperbarui pengaturan notifikasi.'
      ]);
    }
  }
}
