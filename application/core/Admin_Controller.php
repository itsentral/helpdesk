<?php defined('BASEPATH') || exit('No direct script access allowed');

class Admin_Controller extends Base_Controller
{
    protected $pager;
    protected $limit;
    protected $user_data;

    public function __construct()
    {
        $this->autoload['helpers'][]   = 'form';
        $this->autoload['libraries'][] = 'Template';
        $this->autoload['libraries'][] = 'users/auth';

        parent::__construct();

        $this->load->model('identitas_model');

        /*Check If user has logged in*/
        // if (!$this->auth->is_login())
        // {
        //     redirect('login');
        // }

        /*Check If user has logged in*/
        if (!$this->auth->is_login()) {
            if ($this->input->is_ajax_request()) {
                echo json_encode([
                    'status'   => 0,
                    'message'  => 'Session expired. Please login again.',
                    'redirect' => base_url('login')
                ]);
                exit;
            }
            redirect('login');
        }

        $login_time   = $this->session->userdata('login_time');
        $max_lifetime = 14400; // 4 jam dalam detik (4 * 3600)

        if ($login_time && (time() - $login_time) > $max_lifetime) {
            // Hapus session / logout
            if (method_exists($this->auth, 'logout')) {
                $this->auth->logout();
            } else {
                $this->session->sess_destroy();
            }

            // Respon jika request via AJAX
            if ($this->input->is_ajax_request()) {
                echo json_encode([
                    'status'   => 0,
                    'message'  => 'Masa sesi 4 jam telah habis. Silakan login kembali.',
                    'redirect' => base_url('login')
                ]);
                exit;
            }

            // Respon jika request biasa
            redirect('login');
            exit;
        }
        /* ------------------------------------------------------------- */

        $idt = $this->identitas_model->find(1);

        $this->user_data = $this->auth->userdata();

        // Update last_activity setiap request
        $id_user = $this->auth->user_id();
        if (!empty($id_user)) {
            $this->db->where('id_user', $id_user);
            $this->db->update('users', ['last_activity' => date('Y-m-d H:i:s')]);

            if (!$this->session->userdata('login_at_set')) {
                $this->db->where('id_user', $id_user);
                $this->db->update('users', ['login_at' => date('Y-m-d H:i:s')]);
                
                // SIMPAN TIMESTAMP DETIK SAAT LOGIN PERTAMA KALI
                $this->session->set_userdata('login_time', time());
                $this->session->set_userdata('login_at_set', true);
            }
        }

        $this->form_validation->set_error_delimiters('', '');

        // Pagination config
        $this->pager = array(
            'full_tag_open'     => '<ul class="pagination pull-right" style="margin: 0 0 0;">',
            'full_tag_close'    => '</ul>',
            'next_link'         => '&rarr;',
            'prev_link'         => '&larr;',
            'next_tag_open'     => '<li>',
            'next_tag_close'    => '</li>',
            'prev_tag_open'     => '<li>',
            'prev_tag_close'    => '</li>',
            'first_tag_open'    => '<li>',
            'first_tag_close'   => '</li>',
            'last_tag_open'     => '<li>',
            'last_tag_close'    => '</li>',
            'cur_tag_open'      => '<li class="active"><a href="#">',
            'cur_tag_close'     => '</a></li>',
            'num_tag_open'      => '<li>',
            'num_tag_close'     => '</li>',
        );

        // Basic setup
        $this->template->set('userData', $this->user_data);
        $this->template->set('idt', $idt);
        $this->template->set_theme('admin');
        $this->template->set_layout('index');
        //Overwrite if the request is ajax
        if ($this->input->is_ajax_request()) {
            $this->template->set_layout('ajax');
        }

        $this->form_validation->set_error_delimiters('<p>', '</p>');
    }
}
/* End of file Admin_Controller.php */
