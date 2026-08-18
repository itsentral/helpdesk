<?php defined('BASEPATH') or exit('No direct script access allowed');

class Non_project_activities extends Admin_Controller
{
    protected $viewPermission   = 'NonProjectActivities.View';
    protected $addPermission    = 'NonProjectActivities.Add';
    protected $managePermission = 'NonProjectActivities.Manage';
    protected $deletePermission = 'NonProjectActivities.Delete';

    protected $id_user;
    protected $datetime;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('non_project_activities/Activity_model');

        date_default_timezone_set('Asia/Bangkok');
        $this->id_user  = $this->auth->user_id();
        $this->datetime = date('Y-m-d H:i:s');
    }

    /**
     * Display list of activities
     * Admin sees all activities, regular user sees only their own
     */
    public function index()
    {
        $this->template->title('Non Project Activities');
        $this->template->page_icon('fa fa-tasks');

        if ($this->auth->is_admin()) {
            $data['activities'] = $this->Activity_model->get_all_activities();
        } else {
            $data['activities'] = $this->Activity_model->get_activities($this->id_user);
        }

        // Get attachments for each activity
        foreach ($data['activities'] as &$activity) {
            $activity['attachments'] = $this->Activity_model->get_attachments($activity['id']);
            $activity['attachment_count'] = count($activity['attachments']);
        }

        $data['is_admin'] = $this->auth->is_admin();

        $this->template->set($data);
        $this->template->render('index');
    }

    /**
     * Show create form
     */
    public function create()
    {
        $this->template->title('Tambah Aktivitas Non Project');
        $this->template->page_icon('fa fa-plus-circle');

        $data['activity']    = null;
        $data['attachments'] = array();
        $data['form_action'] = site_url('non_project_activities/store');
        $data['readonly']    = false;

        $this->template->set($data);
        $this->template->render('form');
    }

    /**
     * Store new activity with attachments
     */
    public function store()
    {
        // Validate required fields
        $activity_description = trim($this->input->post('activity_description'));
        $manhour              = $this->input->post('manhour');
        $activity_date        = $this->input->post('activity_date');
        $remarks              = $this->input->post('remarks');

        if (empty($activity_description)) {
            $this->session->set_flashdata('error', 'Aktivitas wajib diisi');
            redirect('non_project_activities/create');
            return;
        }

        if (empty($manhour) || (float)$manhour < 0.5) {
            $this->session->set_flashdata('error', 'Man hour wajib diisi minimal 0.5');
            redirect('non_project_activities/create');
            return;
        }

        // Save activity
        $activity_data = array(
            'user_id'              => $this->id_user,
            'activity_date'        => $activity_date ? $activity_date : date('Y-m-d'),
            'activity_description' => htmlspecialchars($activity_description, ENT_QUOTES, 'UTF-8'),
            'manhour'              => (float)$manhour,
            'remarks'              => $remarks ? htmlspecialchars(trim($remarks), ENT_QUOTES, 'UTF-8') : null,
            'created_at'           => $this->datetime
        );

        $activity_id = $this->Activity_model->create_activity($activity_data);

        // Handle multi-upload attachments
        if (!empty($_FILES['attachments']['name'][0])) {
            $total_files = count($_FILES['attachments']['name']);
            $catatan_arr = $this->input->post('catatan_attachment');

            for ($i = 0; $i < $total_files; $i++) {
                if (empty($_FILES['attachments']['name'][$i])) continue;

                $upload_result = $this->_upload_file('attachments', $i);
                if ($upload_result) {
                    $this->Activity_model->save_attachment(array(
                        'activity_id'        => $activity_id,
                        'file_name_original' => $upload_result['file_name_original'],
                        'file_name_hash'     => $upload_result['file_name_hash'],
                        'catatan'            => isset($catatan_arr[$i]) ? htmlspecialchars(trim($catatan_arr[$i]), ENT_QUOTES, 'UTF-8') : null,
                        'created_at'         => $this->datetime
                    ));
                }
            }
        }

        $this->session->set_flashdata('success', 'Aktivitas berhasil disimpan');
        redirect('non_project_activities');
    }

    /**
     * View activity detail (read-only mode)
     */
    public function view($id)
    {
        $activity = $this->Activity_model->get_activity_by_id($id);

        if (!$activity) {
            show_404();
            return;
        }

        // Ownership check (admin can access all)
        if (!$this->auth->is_admin() && $activity['user_id'] != $this->id_user) {
            show_404();
            return;
        }

        $this->template->title('Detail Aktivitas Non Project');
        $this->template->page_icon('fa fa-eye');

        $data['activity']    = $activity;
        $data['attachments'] = $this->Activity_model->get_attachments($id);
        $data['form_action'] = '';
        $data['readonly']    = true;

        $this->template->set($data);
        $this->template->render('form');
    }

    /**
     * Show edit form for an activity
     */
    public function edit($id)
    {
        $activity = $this->Activity_model->get_activity_by_id($id);

        // Check if activity exists
        if (!$activity) {
            show_404();
            return;
        }

        // Ownership check (admin can access all)
        if (!$this->auth->is_admin() && $activity['user_id'] != $this->id_user) {
            show_404();
            return;
        }

        $this->template->title('Edit Aktivitas Non Project');
        $this->template->page_icon('fa fa-edit');

        $data['activity']    = $activity;
        $data['attachments'] = $this->Activity_model->get_attachments($id);
        $data['form_action'] = site_url('non_project_activities/update');
        $data['readonly']    = false;

        $this->template->set($data);
        $this->template->render('form');
    }

    /**
     * Update an existing activity and handle new attachments
     */
    public function update()
    {
        $id = $this->input->post('id');
        $activity = $this->Activity_model->get_activity_by_id($id);

        // Check existence
        if (!$activity) {
            show_404();
            return;
        }

        // Ownership check
        if (!$this->auth->is_admin() && $activity['user_id'] != $this->id_user) {
            show_404();
            return;
        }

        // Validate required fields
        $activity_description = trim($this->input->post('activity_description'));
        $manhour              = $this->input->post('manhour');
        $activity_date        = $this->input->post('activity_date');
        $remarks              = $this->input->post('remarks');

        if (empty($activity_description)) {
            $this->session->set_flashdata('error', 'Aktivitas wajib diisi');
            redirect('non_project_activities/edit/' . $id);
            return;
        }

        if (empty($manhour) || (float)$manhour < 0.5) {
            $this->session->set_flashdata('error', 'Man hour wajib diisi minimal 0.5');
            redirect('non_project_activities/edit/' . $id);
            return;
        }

        // Update activity data
        $update_data = array(
            'activity_date'        => $activity_date ? $activity_date : date('Y-m-d'),
            'activity_description' => htmlspecialchars($activity_description, ENT_QUOTES, 'UTF-8'),
            'manhour'              => (float)$manhour,
            'remarks'              => $remarks ? htmlspecialchars(trim($remarks), ENT_QUOTES, 'UTF-8') : null,
            'updated_at'           => $this->datetime
        );

        $this->Activity_model->update_activity($id, $update_data);

        // Handle new attachments
        if (!empty($_FILES['attachments']['name'][0])) {
            $total_files = count($_FILES['attachments']['name']);
            $catatan_arr = $this->input->post('catatan_attachment');

            for ($i = 0; $i < $total_files; $i++) {
                if (empty($_FILES['attachments']['name'][$i])) continue;

                $upload_result = $this->_upload_file('attachments', $i);
                if ($upload_result) {
                    $this->Activity_model->save_attachment(array(
                        'activity_id'        => $id,
                        'file_name_original' => $upload_result['file_name_original'],
                        'file_name_hash'     => $upload_result['file_name_hash'],
                        'catatan'            => isset($catatan_arr[$i]) ? htmlspecialchars(trim($catatan_arr[$i]), ENT_QUOTES, 'UTF-8') : null,
                        'created_at'         => $this->datetime
                    ));
                }
            }
        }

        $this->session->set_flashdata('success', 'Aktivitas berhasil diperbarui');
        redirect('non_project_activities');
    }

    /**
     * Delete activity via AJAX (soft-delete)
     */
    public function delete()
    {
        $id = $this->input->post('id');
        $activity = $this->Activity_model->get_activity_by_id($id);

        if (!$activity) {
            echo json_encode(array('status' => 'error', 'message' => 'Aktivitas tidak ditemukan'));
            return;
        }

        // Ownership check
        if (!$this->auth->is_admin() && $activity['user_id'] != $this->id_user) {
            echo json_encode(array('status' => 'error', 'message' => 'Anda tidak memiliki akses'));
            return;
        }

        $this->Activity_model->delete_activity($id);
        echo json_encode(array('status' => 'success', 'message' => 'Aktivitas berhasil dihapus'));
    }

    /**
     * Delete individual attachment via AJAX (hard delete + remove physical file)
     */
    public function delete_attachment()
    {
        $id = $this->input->post('id');
        $attachment = $this->Activity_model->get_attachment_by_id($id);

        if (!$attachment) {
            echo json_encode(array('status' => 'error', 'message' => 'Lampiran tidak ditemukan'));
            return;
        }

        // Ownership check via activity
        $activity = $this->Activity_model->get_activity_by_id($attachment['activity_id']);
        if (!$activity || (!$this->auth->is_admin() && $activity['user_id'] != $this->id_user)) {
            echo json_encode(array('status' => 'error', 'message' => 'Anda tidak memiliki akses'));
            return;
        }

        // Delete physical file
        $file_path = FCPATH . 'uploads/non_project/' . $attachment['file_name_hash'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        // Delete DB record (hard delete)
        $this->Activity_model->delete_attachment($id);

        echo json_encode(array('status' => 'success', 'message' => 'Lampiran berhasil dihapus'));
    }

    /**
     * Update attachment (catatan or replace file) via AJAX
     */
    public function update_attachment()
    {
        $id = $this->input->post('id');
        $attachment = $this->Activity_model->get_attachment_by_id($id);

        if (!$attachment) {
            echo json_encode(array('status' => 'error', 'message' => 'Lampiran tidak ditemukan'));
            return;
        }

        // Ownership check via activity
        $activity = $this->Activity_model->get_activity_by_id($attachment['activity_id']);
        if (!$activity || (!$this->auth->is_admin() && $activity['user_id'] != $this->id_user)) {
            echo json_encode(array('status' => 'error', 'message' => 'Anda tidak memiliki akses'));
            return;
        }

        $update_data = array();

        // Update catatan
        $catatan = $this->input->post('catatan');
        if ($catatan !== null) {
            $update_data['catatan'] = htmlspecialchars(trim($catatan), ENT_QUOTES, 'UTF-8');
        }

        // Replace file if new file uploaded
        if (!empty($_FILES['attachment_file']['name'])) {
            $allowed_extensions = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png');
            $max_size = 5 * 1024 * 1024;

            $original_filename = $_FILES['attachment_file']['name'];
            $tmp_name          = $_FILES['attachment_file']['tmp_name'];
            $file_size         = $_FILES['attachment_file']['size'];
            $file_error        = $_FILES['attachment_file']['error'];

            if ($file_error !== UPLOAD_ERR_OK) {
                echo json_encode(array('status' => 'error', 'message' => 'Gagal upload file'));
                return;
            }

            $ext = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed_extensions)) {
                echo json_encode(array('status' => 'error', 'message' => 'Tipe file tidak diizinkan'));
                return;
            }

            if ($file_size > $max_size) {
                echo json_encode(array('status' => 'error', 'message' => 'Ukuran file melebihi batas 5MB'));
                return;
            }

            // Delete old physical file
            $old_path = FCPATH . 'uploads/non_project/' . $attachment['file_name_hash'];
            if (file_exists($old_path)) {
                unlink($old_path);
            }

            // Upload new file
            $upload_dir = FCPATH . 'uploads/non_project/';
            $new_name = md5(uniqid(mt_rand())) . '.' . $ext;
            $dest = $upload_dir . $new_name;

            if (move_uploaded_file($tmp_name, $dest)) {
                $update_data['file_name_hash']     = $new_name;
                $update_data['file_name_original'] = htmlspecialchars($original_filename, ENT_QUOTES, 'UTF-8');
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Gagal menyimpan file'));
                return;
            }
        }

        if (!empty($update_data)) {
            $this->Activity_model->update_attachment($id, $update_data);
        }

        // Return updated data for UI refresh
        $updated = $this->Activity_model->get_attachment_by_id($id);
        echo json_encode(array(
            'status'  => 'success',
            'message' => 'Lampiran berhasil diperbarui',
            'data'    => $updated
        ));
    }

    /**
     * Download attachment file
     */
    public function download($id)
    {
        $attachment = $this->Activity_model->get_attachment_by_id($id);

        if (!$attachment) {
            show_404();
            return;
        }

        // Ownership check via activity
        $activity = $this->Activity_model->get_activity_by_id($attachment['activity_id']);
        if (!$activity || (!$this->auth->is_admin() && $activity['user_id'] != $this->id_user)) {
            show_404();
            return;
        }

        $file_path = FCPATH . 'uploads/non_project/' . $attachment['file_name_hash'];

        if (!file_exists($file_path)) {
            $this->session->set_flashdata('error', 'File tidak ditemukan');
            redirect('non_project_activities');
            return;
        }

        // Force download with original filename
        $this->load->helper('download');
        force_download($attachment['file_name_original'], file_get_contents($file_path));
    }

    /**
     * Upload file helper - validates extension & size, generates hash name
     *
     * @param string $field_name The file input field name
     * @param int $index The index for array-based file inputs
     * @return array|null Array with file_name_original and file_name_hash, or null on failure
     */
    private function _upload_file($field_name, $index)
    {
        $allowed_extensions = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png');
        $max_size = 5 * 1024 * 1024; // 5MB

        $original_filename = $_FILES[$field_name]['name'][$index];
        $tmp_name          = $_FILES[$field_name]['tmp_name'][$index];
        $file_size         = $_FILES[$field_name]['size'][$index];
        $file_error        = $_FILES[$field_name]['error'][$index];

        // Check for upload errors
        if ($file_error !== UPLOAD_ERR_OK) return null;

        // Validate extension
        $ext = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed_extensions)) return null;

        // Validate file size
        if ($file_size > $max_size) return null;

        // Generate hash name
        $upload_dir = FCPATH . 'uploads/non_project/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $new_name = md5(uniqid(mt_rand())) . '.' . $ext;
        $dest = $upload_dir . $new_name;

        if (move_uploaded_file($tmp_name, $dest)) {
            return array(
                'file_name_hash'     => $new_name,
                'file_name_original' => htmlspecialchars($original_filename, ENT_QUOTES, 'UTF-8')
            );
        }

        return null;
    }
}
