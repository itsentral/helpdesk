<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users extends Front_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('identitas_model'));
        $this->load->library('users/auth');

        $this->site_key   = defined('RECAPTCHA_SITE_KEY')   ? RECAPTCHA_SITE_KEY   : '';
        $this->secret_key = defined('RECAPTCHA_SECRET_KEY') ? RECAPTCHA_SECRET_KEY : '';

        $this->id_user  = $this->auth->user_id();
    }

    public function index()
    {
        redirect('users/setting');
    }

    public function login()
    {
        if ($this->auth->is_login()) {
            history("Login");
            redirect('/');
        }

        $identitas = $this->identitas_model->find_by(['ididentitas' => 1]);

        if ($this->input->post('login')) {
            $token = $this->input->post('g-recaptcha-response', true);
            list($ok, $info) = $this->verify_recaptcha_v3($token, 'login', 0.5);
            if (!$ok) {
                $this->session->set_flashdata('error', 'Verifikasi reCAPTCHA gagal: ' . $info);
                redirect('users/login');
                return;
            }

            $username = $this->input->post('username', true);
            $password = $this->input->post('password', true);

            // Coba login dan tangkap hasilnya
            $login_result = $this->auth->login($username, $password);

            // Jika login gagal, set flashdata error
            if (!$login_result) {
                $this->session->set_flashdata('error', 'Username atau password salah!');
                redirect('users/login');
                return;
            }

            // Jika berhasil, akan diarahkan ke halaman utama oleh auth library
        }

        $this->template->set('recaptcha_site_key', $this->site_key);
        $this->template->set('idt', $identitas);

        // Tambahkan ini untuk meneruskan flashdata ke view
        $error_message = $this->session->flashdata('error');
        $this->template->set('login_error', $error_message);

        $this->template->set_theme('default');
        $this->template->set_layout('login');
        $this->template->title('Login');
        $this->template->render('login_animate');
    }

    public function logout()
    {
        if (!empty($this->id_user)) {
            history("Logout");

            $this->db->where('id_user', $this->id_user);
            $this->db->update('users', [
                'last_activity' => null,
                'login_at'      => null,
            ]);
        }
        $this->auth->logout();
    }

    /** Verifikasi reCAPTCHA v3 */
    private function verify_recaptcha_v3($token, $expectedAction = 'login', $threshold = 0.5)
    {
        if (empty($this->secret_key)) return [false, 'Secret key kosong'];
        if (empty($token))            return [false, 'Token kosong'];

        $postData = http_build_query([
            'secret'   => $this->secret_key,
            'response' => $token,
            'remoteip' => $this->input->ip_address()
        ]);

        $ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $postData,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 10,
        ]);
        $raw = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($raw === false) return [false, 'cURL error: ' . $err];

        $res = json_decode($raw, true);
        if (empty($res['success'])) return [false, 'Gagal: ' . json_encode($res['error-codes'] ?? [])];

        if (!empty($expectedAction) && isset($res['action']) && $res['action'] !== $expectedAction) {
            return [false, 'Action mismatch'];
        }

        $score = isset($res['score']) ? (float)$res['score'] : 0.0;
        if ($score < $threshold) return [false, 'Skor rendah (' . $score . ')'];

        return [true, $score];
    }

    public function get_notifications()
    {
        $this->load->model('Users_model');
        $user_id = $this->auth->user_id();

        $notifications = $this->Users_model->get_notifications($user_id);
        $unread_count  = $this->Users_model->get_unread_count($user_id);

        echo json_encode([
            'status'        => 1,
            'unread_count'  => $unread_count,
            'notifications' => $notifications,
        ]);
    }

    public function mark_notification_read()
    {
        $this->load->model('Users_model');
        $id      = $this->input->post('id');
        $user_id = $this->auth->user_id();

        if ($id === 'all') {
            $this->Users_model->mark_all_read($user_id);
        } else {
            $this->Users_model->mark_as_read($id, $user_id);
        }

        echo json_encode(['status' => 1]);
    }

    public function get_online_users()
    {
        $this->load->model('Users_model');
        $online = $this->Users_model->get_online_users();

        echo json_encode([
            'status' => 1,
            'count'  => count($online),
            'users'  => $online,
        ]);
    }

    public function heartbeat()
    {
        $id_user = $this->auth->user_id();
        if (!empty($id_user)) {
            $this->db->where('id_user', $id_user);
            $this->db->update('users', ['last_activity' => date('Y-m-d H:i:s')]);
        }
        echo json_encode(['status' => 1]);
    }
}
