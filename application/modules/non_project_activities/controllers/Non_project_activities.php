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
            $data['date_groups'] = $this->Activity_model->get_all_activities_grouped();
        } else {
            $data['date_groups'] = $this->Activity_model->get_activities_grouped($this->id_user);
        }

        // Get attachments for each group
        foreach ($data['date_groups'] as &$group) {
            $group['attachments'] = array();
            $group['attachment_count'] = 0;
            foreach ($group['items'] as &$item) {
                $atts = $this->Activity_model->get_attachments($item['id']);
                $item['attachments'] = $atts;
                $group['attachments'] = array_merge($group['attachments'], $atts);
            }
            $group['attachment_count'] = count($group['attachments']);
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

        $data['activity_date'] = date('Y-m-d');
        $data['activities']    = array();
        $data['attachments']   = array();
        $data['form_action']   = site_url('non_project_activities/store');
        $data['readonly']      = false;
        $data['is_edit']       = false;

        $this->template->set($data);
        $this->template->render('form');
    }

    /**
     * Store new activity/activities with attachments
     */
    public function store()
    {
        $activity_date = $this->input->post('activity_date');

        // Support multi-activity input (array fields)
        $descriptions = $this->input->post('activity_description');
        $manhours     = $this->input->post('manhour');
        $remarks_arr  = $this->input->post('remarks');

        // Normalize: if single input (edit fallback), wrap in array
        if (!is_array($descriptions)) {
            $descriptions = array($descriptions);
            $manhours     = array($manhours);
            $remarks_arr  = array($remarks_arr);
        }

        // Validate at least one activity row
        $valid_count = 0;
        foreach ($descriptions as $i => $desc) {
            if (!empty(trim($desc))) $valid_count++;
        }

        if ($valid_count === 0) {
            $this->session->set_flashdata('error', 'Minimal satu aktivitas wajib diisi');
            redirect('non_project_activities/create');
            return;
        }

        // Validate each activity row
        foreach ($descriptions as $i => $desc) {
            $desc = trim($desc);
            if (empty($desc)) continue; // skip empty rows

            $mh = isset($manhours[$i]) ? (float)$manhours[$i] : 0;
            if ($mh < 0.5) {
                $this->session->set_flashdata('error', 'Man hour pada aktivitas ke-' . ($i + 1) . ' wajib minimal 0.5');
                redirect('non_project_activities/create');
                return;
            }
        }

        // Save each activity
        $saved_ids = array();
        foreach ($descriptions as $i => $desc) {
            $desc = trim($desc);
            if (empty($desc)) continue;

            $mh = isset($manhours[$i]) ? (float)$manhours[$i] : 0.5;
            $rmk = isset($remarks_arr[$i]) ? trim($remarks_arr[$i]) : '';

            $activity_data = array(
                'user_id'              => $this->id_user,
                'activity_date'        => $activity_date ? $activity_date : date('Y-m-d'),
                'activity_description' => htmlspecialchars($desc, ENT_QUOTES, 'UTF-8'),
                'manhour'              => $mh,
                'remarks'              => $rmk ? htmlspecialchars($rmk, ENT_QUOTES, 'UTF-8') : null,
                'created_at'           => $this->datetime
            );

            $activity_id = $this->Activity_model->create_activity($activity_data);
            $saved_ids[] = $activity_id;
        }

        // Handle multi-upload attachments - attach to FIRST activity
        $primary_id = $saved_ids[0];
        if (!empty($_FILES['attachments']['name'][0])) {
            $total_files = count($_FILES['attachments']['name']);
            $catatan_arr = $this->input->post('catatan_attachment');

            for ($i = 0; $i < $total_files; $i++) {
                if (empty($_FILES['attachments']['name'][$i])) continue;

                $upload_result = $this->_upload_file('attachments', $i);
                if ($upload_result) {
                    $this->Activity_model->save_attachment(array(
                        'activity_id'        => $primary_id,
                        'file_name_original' => $upload_result['file_name_original'],
                        'file_name_hash'     => $upload_result['file_name_hash'],
                        'catatan'            => isset($catatan_arr[$i]) ? htmlspecialchars(trim($catatan_arr[$i]), ENT_QUOTES, 'UTF-8') : null,
                        'created_at'         => $this->datetime
                    ));
                }
            }
        }

        $count = count($saved_ids);
        $this->session->set_flashdata('success', $count . ' aktivitas berhasil disimpan');
        redirect('non_project_activities');
    }

    /**
     * View activities detail for a date (read-only mode)
     * $id = any activity_id on that date (used to determine user + date)
     */
    public function view($id)
    {
        $activity = $this->Activity_model->get_activity_by_id($id);

        if (!$activity) {
            show_404();
            return;
        }

        if (!$this->auth->is_admin() && $activity['user_id'] != $this->id_user) {
            show_404();
            return;
        }

        // Load all activities on the same date for this user
        $activities = $this->Activity_model->get_activities_by_date($activity['user_id'], $activity['activity_date']);

        // Collect all attachments across all activities on this date
        $all_attachments = array();
        foreach ($activities as &$act) {
            $act['attachments'] = $this->Activity_model->get_attachments($act['id']);
            $all_attachments = array_merge($all_attachments, $act['attachments']);
        }

        $this->template->title('Detail Aktivitas Non Project');
        $this->template->page_icon('fa fa-eye');

        $data['activity_date'] = $activity['activity_date'];
        $data['activities']    = $activities;
        $data['attachments']   = $all_attachments;
        $data['form_action']   = '';
        $data['readonly']      = true;
        $data['is_edit']       = true;

        $this->template->set($data);
        $this->template->render('form');
    }

    /**
     * Edit activities for a date
     * $id = any activity_id on that date
     */
    public function edit($id)
    {
        $activity = $this->Activity_model->get_activity_by_id($id);

        if (!$activity) {
            show_404();
            return;
        }

        if (!$this->auth->is_admin() && $activity['user_id'] != $this->id_user) {
            show_404();
            return;
        }

        // Load all activities on the same date for this user
        $activities = $this->Activity_model->get_activities_by_date($activity['user_id'], $activity['activity_date']);

        // Collect all attachments
        $all_attachments = array();
        foreach ($activities as &$act) {
            $act['attachments'] = $this->Activity_model->get_attachments($act['id']);
            $all_attachments = array_merge($all_attachments, $act['attachments']);
        }

        $this->template->title('Edit Aktivitas Non Project');
        $this->template->page_icon('fa fa-edit');

        $data['activity_date'] = $activity['activity_date'];
        $data['activities']    = $activities;
        $data['attachments']   = $all_attachments;
        $data['form_action']   = site_url('non_project_activities/update');
        $data['readonly']      = false;
        $data['is_edit']       = true;

        $this->template->set($data);
        $this->template->render('form');
    }

    /**
     * Update activities for a date - handles multi-row editing
     */
    public function update()
    {
        $activity_date  = $this->input->post('activity_date');
        $existing_ids   = $this->input->post('existing_id');    // array of existing activity IDs
        $descriptions   = $this->input->post('activity_description'); // array
        $manhours       = $this->input->post('manhour');         // array
        $remarks_arr    = $this->input->post('remarks');         // array
        $reference_id   = $this->input->post('reference_id');    // first activity id for redirect

        if (!is_array($descriptions)) {
            $descriptions = array($descriptions);
            $manhours     = array($manhours);
            $remarks_arr  = array($remarks_arr);
            $existing_ids = array($existing_ids);
        }

        // Validate at least one activity
        $valid_count = 0;
        foreach ($descriptions as $i => $desc) {
            if (!empty(trim($desc))) $valid_count++;
        }
        if ($valid_count === 0) {
            $this->session->set_flashdata('error', 'Minimal satu aktivitas wajib diisi');
            redirect('non_project_activities/edit/' . $reference_id);
            return;
        }

        // Validate manhour for each filled row
        foreach ($descriptions as $i => $desc) {
            if (empty(trim($desc))) continue;
            $mh = isset($manhours[$i]) ? (float)$manhours[$i] : 0;
            if ($mh < 0.5) {
                $this->session->set_flashdata('error', 'Man hour pada aktivitas ke-' . ($i + 1) . ' wajib minimal 0.5');
                redirect('non_project_activities/edit/' . $reference_id);
                return;
            }
        }

        // Ownership check on first existing record
        if (!empty($existing_ids[0])) {
            $check = $this->Activity_model->get_activity_by_id($existing_ids[0]);
            if (!$check || (!$this->auth->is_admin() && $check['user_id'] != $this->id_user)) {
                show_404();
                return;
            }
        }

        // Process each row
        $primary_id = null;
        foreach ($descriptions as $i => $desc) {
            $desc = trim($desc);
            $mh   = isset($manhours[$i]) ? (float)$manhours[$i] : 0.5;
            $rmk  = isset($remarks_arr[$i]) ? trim($remarks_arr[$i]) : '';
            $eid  = isset($existing_ids[$i]) ? $existing_ids[$i] : '';

            if (empty($desc)) {
                // If existing row is now empty, soft-delete it
                if (!empty($eid)) {
                    $this->Activity_model->delete_activity($eid);
                }
                continue;
            }

            $data = array(
                'activity_date'        => $activity_date ? $activity_date : date('Y-m-d'),
                'activity_description' => htmlspecialchars($desc, ENT_QUOTES, 'UTF-8'),
                'manhour'              => $mh,
                'remarks'              => $rmk ? htmlspecialchars($rmk, ENT_QUOTES, 'UTF-8') : null,
                'updated_at'           => $this->datetime
            );

            if (!empty($eid)) {
                // Update existing record
                $this->Activity_model->update_activity($eid, $data);
                if (!$primary_id) $primary_id = $eid;
            } else {
                // Insert new record
                $data['user_id']    = $this->id_user;
                $data['created_at'] = $this->datetime;
                $new_id = $this->Activity_model->create_activity($data);
                if (!$primary_id) $primary_id = $new_id;
            }
        }

        // Handle new attachments - attach to first activity
        if ($primary_id && !empty($_FILES['attachments']['name'][0])) {
            $total_files = count($_FILES['attachments']['name']);
            $catatan_arr_att = $this->input->post('catatan_attachment');

            for ($i = 0; $i < $total_files; $i++) {
                if (empty($_FILES['attachments']['name'][$i])) continue;

                $upload_result = $this->_upload_file('attachments', $i);
                if ($upload_result) {
                    $this->Activity_model->save_attachment(array(
                        'activity_id'        => $primary_id,
                        'file_name_original' => $upload_result['file_name_original'],
                        'file_name_hash'     => $upload_result['file_name_hash'],
                        'catatan'            => isset($catatan_arr_att[$i]) ? htmlspecialchars(trim($catatan_arr_att[$i]), ENT_QUOTES, 'UTF-8') : null,
                        'created_at'         => $this->datetime
                    ));
                }
            }
        }

        $this->session->set_flashdata('success', 'Aktivitas berhasil diperbarui');
        redirect('non_project_activities');
    }

    /**
     * Delete activity/activities via AJAX (soft-delete)
     * Can delete a single activity or all activities on a date
     */
    public function delete()
    {
        $id   = $this->input->post('id');
        $date = $this->input->post('date');

        if (!empty($date)) {
            // Delete all activities on this date for this user
            $activities = $this->Activity_model->get_activities_by_date($this->id_user, $date);
            if (empty($activities)) {
                echo json_encode(array('status' => 'error', 'message' => 'Aktivitas tidak ditemukan'));
                return;
            }
            foreach ($activities as $act) {
                if (!$this->auth->is_admin() && $act['user_id'] != $this->id_user) continue;
                $this->Activity_model->delete_activity($act['id']);
            }
            echo json_encode(array('status' => 'success', 'message' => 'Semua aktivitas pada tanggal tersebut berhasil dihapus'));
        } else {
            // Delete single activity
            $activity = $this->Activity_model->get_activity_by_id($id);
            if (!$activity) {
                echo json_encode(array('status' => 'error', 'message' => 'Aktivitas tidak ditemukan'));
                return;
            }
            if (!$this->auth->is_admin() && $activity['user_id'] != $this->id_user) {
                echo json_encode(array('status' => 'error', 'message' => 'Anda tidak memiliki akses'));
                return;
            }
            $this->Activity_model->delete_activity($id);
            echo json_encode(array('status' => 'success', 'message' => 'Aktivitas berhasil dihapus'));
        }
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
