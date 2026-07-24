<?php defined('BASEPATH') or exit('No direct script access allowed');

class Projects_management extends Admin_Controller
{
    protected $viewPermission   = 'Projects.View';
    protected $addPermission    = 'Projects.Add';
    protected $managePermission = 'Projects.Manage';
    protected $deletePermission = 'Projects.Delete';

    protected $id_user;
    protected $datetime;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'projects_management/Project_model',
            'projects_management/Module_model',
            'projects_management/Document_model'
        ));

        date_default_timezone_set('Asia/Bangkok');
        $this->id_user  = $this->auth->user_id();
        $this->datetime = date('Y-m-d H:i:s');
    }

    public function index()
    {
        $this->template->title('Project Management');
        $this->template->page_icon('fa fa-cubes');

        $data['kpi']          = $this->Project_model->get_kpi_summary();
        $data['projects']     = $this->Project_model->get_projects(null, null, $this->auth->is_admin() ? null : $this->id_user);

        $this->template->set($data);
        $this->template->render('dashboard');
    }

    public function master()
    {
        $this->template->title('Master Project');
        $this->template->page_icon('fa fa-list');

        $status    = $this->input->get('status');
        $client_id = $this->input->get('client_id');

        $data['projects']       = $this->Project_model->get_projects($status, $client_id, $this->auth->is_admin() ? null : $this->id_user);
        $data['clients']        = $this->db->get_where('helpdesk_client', array('is_delete' => 0))->result_array();
        $data['users']          = $this->db->get_where('users', array('st_aktif' => 1))->result_array();
        $data['current_user_id'] = $this->id_user;
        $data['is_admin']       = $this->auth->is_admin();

        $this->template->set($data);
        $this->template->render('projects/index');
    }

    public function create()
    {
        if ($this->input->post()) {
            $project_name          = trim($this->input->post('project_name'));
            $client_id             = $this->input->post('client_id');
            $pm_id                 = $this->input->post('pm_id');
            $ba_ids                = $this->input->post('ba_ids');         // array (multi-select)
            $programmer_ids        = $this->input->post('programmer_ids'); // array (multi-select)
            $qa_id                 = $this->input->post('qa_id');
            $end_date              = $this->input->post('end_date');
            $start_date            = date('Y-m-d');
            $target_mh_pm          = $this->input->post('target_mh_pm');
            $target_mh_qa          = $this->input->post('target_mh_qa');
            $target_mh_ba          = $this->input->post('target_mh_ba');
            $target_mh_programmer  = $this->input->post('target_mh_programmer');

            if (empty($project_name) || empty($client_id) || empty($pm_id) || empty($end_date)) {
                echo json_encode(array('status' => 0, 'pesan' => 'Field wajib (Client, Nama Project, PM, Target date selesai) harus diisi!'));
                return;
            }

            $project_code = $this->Project_model->generate_project_code();

            $insert_data = array(
                'project_code'          => $project_code,
                'project_name'          => $project_name,
                'client_id'             => !empty($client_id) ? $client_id : NULL,
                'pm_id'                 => $pm_id,
                'start_date'            => $start_date,
                'end_date'              => $end_date,
                'status'                => 'Planning',
                'budget'                => 0.00,
                'target_mh_pm'          => $target_mh_pm ? (float)$target_mh_pm : 0.00,
                'target_mh_qa'          => $target_mh_qa ? (float)$target_mh_qa : 0.00,
                'target_mh_ba'          => $target_mh_ba ? (float)$target_mh_ba : 0.00,
                'target_mh_programmer'  => $target_mh_programmer ? (float)$target_mh_programmer : 0.00,
                'created_by'            => $this->id_user,
                'created_at'            => $this->datetime
            );

            $this->db->trans_start();
            $this->db->insert('pm_projects', $insert_data);
            $project_id = $this->db->insert_id();

            // Save multi-user roles to pm_project_roles
            if (!empty($ba_ids) && is_array($ba_ids)) {
                foreach ($ba_ids as $uid) {
                    if (!empty($uid)) {
                        $this->db->insert('pm_project_roles', array(
                            'project_id' => $project_id, 'user_id' => $uid, 'role' => 'ba', 'created_at' => $this->datetime
                        ));
                    }
                }
            }
            if (!empty($programmer_ids) && is_array($programmer_ids)) {
                foreach ($programmer_ids as $uid) {
                    if (!empty($uid)) {
                        $this->db->insert('pm_project_roles', array(
                            'project_id' => $project_id, 'user_id' => $uid, 'role' => 'programmer', 'created_at' => $this->datetime
                        ));
                    }
                }
            }
            if (!empty($qa_id)) {
                $this->db->insert('pm_project_roles', array(
                    'project_id' => $project_id, 'user_id' => $qa_id, 'role' => 'qa', 'created_at' => $this->datetime
                ));
            }
            $this->db->insert('pm_project_roles', array(
                'project_id' => $project_id, 'user_id' => $pm_id, 'role' => 'pm', 'created_at' => $this->datetime
            ));

            // Save modules & tahapan
            $module_names    = $this->input->post('module_names');
            $tahapan_pic     = $this->input->post('tahapan_pic');
            $tahapan_manhour = $this->input->post('tahapan_manhour');
            $tahapan_duedate = $this->input->post('tahapan_duedate');

            if (!empty($module_names) && is_array($module_names)) {
                foreach ($module_names as $idx => $mod_name) {
                    if (empty(trim($mod_name))) continue;

                    $pic_data = array();
                    $plan_data = array();

                    if (isset($tahapan_pic[$idx]) && is_array($tahapan_pic[$idx])) {
                        foreach ($tahapan_pic[$idx] as $order => $uid) {
                            if (!empty($uid)) $pic_data[(int)$order] = (int)$uid;
                        }
                    }
                    if (isset($tahapan_manhour[$idx]) && is_array($tahapan_manhour[$idx])) {
                        foreach ($tahapan_manhour[$idx] as $order => $mh) {
                            $plan_data[(int)$order]['manhour'] = (float)$mh;
                        }
                    }
                    if (isset($tahapan_duedate[$idx]) && is_array($tahapan_duedate[$idx])) {
                        foreach ($tahapan_duedate[$idx] as $order => $dd) {
                            $plan_data[(int)$order]['due_date'] = $dd;
                        }
                    }

                    $this->Module_model->create_module_with_tahapan(
                        $project_id, trim($mod_name), ($idx + 1), $pic_data, $plan_data
                    );
                }

                $total_mod = $this->db->where('project_id', $project_id)->count_all_results('pm_modules');
                $this->db->where('id', $project_id);
                $this->db->update('pm_projects', array('total_modules' => $total_mod));
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                echo json_encode(array('status' => 0, 'pesan' => 'Gagal menyimpan data project.'));
            } else {
                echo json_encode(array('status' => 1, 'pesan' => 'Project berhasil ditambahkan dengan kode ' . $project_code));
            }
            return;
        }

        $data['project_code'] = $this->Project_model->generate_project_code();
        $data['clients']      = $this->db->get_where('helpdesk_client', array('is_delete' => 0))->result_array();
        $data['users']        = $this->db->get_where('users', array('st_aktif' => 1))->result_array();
        $data['master_tahapan'] = $this->Module_model->get_master_tahapan();

        $this->template->title('Tambah Project Baru');
        $this->template->page_icon('fa fa-plus-circle');
        $this->template->set($data);
        $this->template->render('projects/create');
    }

    /**
     * DETAIL / UPDATE PROJECT - View per modul & tahapan (sequential)
     */
    public function detail($id)
    {
        $project = $this->Project_model->get_project_by_id($id);
        if (!$project) {
            show_404();
            return;
        }

        $this->template->title('Detail Project - ' . $project['project_name']);
        $this->template->page_icon('fa fa-folder-open');

        $data['project']    = $project;
        $data['modules']    = $this->Module_model->get_modules_with_tahapan($id);
        $data['ba_users']   = $this->Project_model->get_role_user_ids($id, 'ba');
        $data['prog_users'] = $this->Project_model->get_role_user_ids($id, 'programmer');
        $data['qa_users']   = $this->Project_model->get_role_user_ids($id, 'qa');
        $data['readonly']       = true;
        $data['current_user_id'] = $this->id_user;
        $data['is_admin']       = $this->auth->is_admin();

        $this->template->set($data);
        $this->template->render('projects/detail_v2');
    }

    /**
     * Update view - full action enabled
     */
    public function update($id)
    {
        $project = $this->Project_model->get_project_by_id($id);
        if (!$project) {
            show_404();
            return;
        }

        $this->template->title('Update Project - ' . $project['project_name']);
        $this->template->page_icon('fa fa-pencil-square-o');

        $data['project']    = $project;
        $data['modules']    = $this->Module_model->get_modules_with_tahapan($id);
        $data['ba_users']   = $this->Project_model->get_role_user_ids($id, 'ba');
        $data['prog_users'] = $this->Project_model->get_role_user_ids($id, 'programmer');
        $data['qa_users']   = $this->Project_model->get_role_user_ids($id, 'qa');
        $data['readonly']       = false;
        $data['current_user_id'] = $this->id_user;
        $data['is_admin']       = $this->auth->is_admin();

        $this->template->set($data);
        $this->template->render('projects/detail_v2');
    }

    /**
     * AJAX: Get input pekerjaan modal content
     */
    public function input_pekerjaan($tahapan_id)
    {
        $tahapan = $this->Module_model->get_tahapan_by_id($tahapan_id);
        if (!$tahapan) {
            echo '<div class="modal-body text-center text-danger">Tahapan tidak ditemukan.</div>';
            return;
        }

        $data['tahapan']          = $tahapan;
        $data['tasks_by_version'] = $this->Module_model->get_tahapan_tasks_by_version($tahapan_id);
        $data['total_manhour']    = $this->Module_model->get_tahapan_actual_manhour($tahapan_id);
        $data['rollback_history'] = $this->Module_model->get_rollback_history($tahapan['module_id']);

        $this->load->view('projects/input_pekerjaan', $data);
    }

    /**
     * AJAX: View pekerjaan modal (read only)
     */
    public function view_pekerjaan($tahapan_id)
    {
        $tahapan = $this->Module_model->get_tahapan_by_id($tahapan_id);
        if (!$tahapan) {
            echo '<div class="modal-body text-center text-danger">Tahapan tidak ditemukan.</div>';
            return;
        }

        $data['tahapan']          = $tahapan;
        $data['tasks_by_version'] = $this->Module_model->get_tahapan_tasks_by_version($tahapan_id);
        $data['total_manhour']    = $this->Module_model->get_tahapan_actual_manhour($tahapan_id);
        $data['rollback_history'] = $this->Module_model->get_rollback_history($tahapan['module_id']);

        $this->load->view('projects/view_pekerjaan', $data);
    }

    /**
     * AJAX: Save task/pekerjaan (Save Draft) - single task
     */
    public function save_task()
    {
        $tahapan_id       = $this->input->post('tahapan_id');
        $module_id        = $this->input->post('module_id');
        $project_id       = $this->input->post('project_id');
        $task_date        = $this->input->post('task_date');
        $task_description = trim($this->input->post('task_description'));
        $manhour          = $this->input->post('manhour');
        $remarks          = $this->input->post('remarks');

        if (empty($tahapan_id) || empty($task_date) || empty($task_description) || empty($manhour)) {
            echo json_encode(array('status' => 0, 'pesan' => 'Tanggal, aktivitas, dan manhour wajib diisi!'));
            return;
        }

        $tahapan = $this->Module_model->get_tahapan_by_id($tahapan_id);
        if (!$tahapan || $tahapan['status'] !== 'active') {
            echo json_encode(array('status' => 0, 'pesan' => 'Tahapan ini tidak dalam status aktif.'));
            return;
        }

        $file_name_hash = NULL;
        $file_name_original = NULL;

        if (!empty($_FILES['task_file']['name'])) {
            $upload_result = $this->_upload_task_file('task_file');
            if ($upload_result) {
                $file_name_hash = $upload_result['file_name_hash'];
                $file_name_original = $upload_result['file_name_original'];
            }
        }

        $this->Module_model->save_tahapan_task(array(
            'tahapan_id'         => $tahapan_id,
            'module_id'          => $module_id,
            'project_id'         => $project_id,
            'user_id'            => $this->id_user,
            'task_date'          => $task_date,
            'task_description'   => htmlspecialchars($task_description, ENT_QUOTES, 'UTF-8'),
            'manhour'            => (float)$manhour,
            'remarks'            => $remarks ? htmlspecialchars($remarks, ENT_QUOTES, 'UTF-8') : NULL,
            'file_name_hash'     => $file_name_hash,
            'file_name_original' => $file_name_original,
            'created_at'         => $this->datetime
        ));

        echo json_encode(array('status' => 1, 'pesan' => 'Pekerjaan berhasil disimpan.'));
    }

    /**
     * AJAX: Save multiple tasks at once (bulk)
     */
    public function save_tasks_bulk()
    {
        $tahapan_id = $this->input->post('tahapan_id');
        $module_id  = $this->input->post('module_id');
        $project_id = $this->input->post('project_id');
        $tasks      = $this->input->post('tasks');

        if (empty($tahapan_id) || empty($tasks) || !is_array($tasks)) {
            echo json_encode(array('status' => 0, 'pesan' => 'Tidak ada task untuk disimpan.'));
            return;
        }

        // Block jika project On Hold atau Completed
        $project = $this->Project_model->get_project_by_id($project_id);
        if ($project && in_array($project['status'], array('On Hold', 'Completed'))) {
            echo json_encode(array('status' => 0, 'pesan' => 'Project sedang ' . $project['status'] . '. Tidak bisa menambah task.'));
            return;
        }

        $tahapan = $this->Module_model->get_tahapan_by_id($tahapan_id);
        if (!$tahapan || $tahapan['status'] !== 'active') {
            echo json_encode(array('status' => 0, 'pesan' => 'Tahapan ini tidak dalam status aktif.'));
            return;
        }

        $saved = 0;
        $today = date('Y-m-d');
        foreach ($tasks as $idx => $task) {
            $task_desc = isset($task['task_description']) ? trim($task['task_description']) : '';
            $manhour   = isset($task['manhour']) ? (float)$task['manhour'] : 0;
            $remarks   = isset($task['remarks']) ? $task['remarks'] : '';

            if (empty($task_desc) || $manhour <= 0) continue;

            // Handle file upload per task
            $file_name_hash = NULL;
            $file_name_original = NULL;
            $file_key  = 'task_files';

            if (isset($_FILES[$file_key]['name'][$idx]) && !empty($_FILES[$file_key]['name'][$idx])) {
                $upload_result = $this->_upload_task_file_from_array($file_key, $idx);
                if ($upload_result) {
                    $file_name_hash = $upload_result['file_name_hash'];
                    $file_name_original = $upload_result['file_name_original'];
                }
            }

            $this->Module_model->save_tahapan_task(array(
                'tahapan_id'         => $tahapan_id,
                'module_id'          => $module_id,
                'project_id'         => $project_id,
                'user_id'            => $this->id_user,
                'task_date'          => $today,
                'task_description'   => htmlspecialchars($task_desc, ENT_QUOTES, 'UTF-8'),
                'manhour'            => $manhour,
                'remarks'            => $remarks ? htmlspecialchars($remarks, ENT_QUOTES, 'UTF-8') : NULL,
                'file_name_hash'     => $file_name_hash,
                'file_name_original' => $file_name_original,
                'created_at'         => $this->datetime
            ));
            $saved++;
        }

        if ($saved > 0) {
            echo json_encode(array('status' => 1, 'pesan' => $saved . ' task berhasil disimpan.'));
        } else {
            echo json_encode(array('status' => 0, 'pesan' => 'Tidak ada task valid untuk disimpan.'));
        }
    }

    /**
     * Helper: Upload single file
     */
    private function _upload_task_file($field_name)
    {
        $allowed_extensions = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png');
        $original_filename  = $_FILES[$field_name]['name'];
        $ext = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed_extensions)) return NULL;

        $upload_dir = FCPATH . 'uploads/projects_management/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        $config['upload_path']   = $upload_dir;
        $config['allowed_types'] = 'pdf|doc|docx|xls|xlsx|jpg|jpeg|png';
        $config['max_size']      = 10240;
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config, 'task_upload');
        $this->task_upload->initialize($config);

        if ($this->task_upload->do_upload($field_name)) {
            $upload_data = $this->task_upload->data();
            return array(
                'file_name_hash'     => $upload_data['file_name'],
                'file_name_original' => htmlspecialchars($original_filename, ENT_QUOTES, 'UTF-8')
            );
        }
        return NULL;
    }

    /**
     * Helper: Upload file from array ($_FILES[key][name][index])
     */
    private function _upload_task_file_from_array($field_name, $index)
    {
        $allowed_extensions = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png');
        $original_filename  = $_FILES[$field_name]['name'][$index];
        $ext = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed_extensions)) return NULL;

        $upload_dir = FCPATH . 'uploads/projects_management/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        $tmp_name = $_FILES[$field_name]['tmp_name'][$index];
        $new_name = md5(uniqid(mt_rand())) . '.' . $ext;
        $dest     = $upload_dir . $new_name;

        if (move_uploaded_file($tmp_name, $dest)) {
            return array(
                'file_name_hash'     => $new_name,
                'file_name_original' => htmlspecialchars($original_filename, ENT_QUOTES, 'UTF-8')
            );
        }
        return NULL;
    }

    /**
     * AJAX: Finish tahapan (sequential - unlock next)
     */
    public function finish_tahapan()
    {
        $tahapan_id = $this->input->post('tahapan_id');

        if (empty($tahapan_id)) {
            echo json_encode(array('status' => 0, 'pesan' => 'ID Tahapan tidak valid.'));
            return;
        }

        $tahapan = $this->Module_model->get_tahapan_by_id($tahapan_id);
        if (!$tahapan || $tahapan['status'] !== 'active') {
            echo json_encode(array('status' => 0, 'pesan' => 'Tahapan tidak dalam status aktif.'));
            return;
        }

        // Block jika project On Hold atau Completed
        $project = $this->Project_model->get_project_by_id($tahapan['project_id']);
        if ($project && in_array($project['status'], array('On Hold', 'Completed'))) {
            echo json_encode(array('status' => 0, 'pesan' => 'Project sedang ' . $project['status'] . '. Tidak bisa finish tahapan.'));
            return;
        }

        $this->Module_model->finish_tahapan($tahapan_id);

        echo json_encode(array('status' => 1, 'pesan' => 'Tahapan berhasil ditandai selesai. Tahapan berikutnya telah dibuka.'));
    }

    /**
     * AJAX: Finish module
     */
    public function finish_module()
    {
        $module_id = $this->input->post('module_id');

        if (empty($module_id)) {
            echo json_encode(array('status' => 0, 'pesan' => 'ID Modul tidak valid.'));
            return;
        }

        $module = $this->Module_model->get_module_by_id($module_id);
        if (!$module) {
            echo json_encode(array('status' => 0, 'pesan' => 'Modul tidak ditemukan.'));
            return;
        }

        $this->Module_model->finish_module($module_id);

        echo json_encode(array('status' => 1, 'pesan' => 'Modul "' . $module['module_name'] . '" berhasil ditandai finish.'));
    }

    /**
     * AJAX: Delete module (soft delete - hapus modul beserta tahapan & task)
     */
    public function delete_module()
    {
        $module_id = $this->input->post('module_id');

        if (empty($module_id)) {
            echo json_encode(array('status' => 0, 'pesan' => 'ID Modul tidak valid.'));
            return;
        }

        $module = $this->Module_model->get_module_by_id($module_id);
        if (!$module) {
            echo json_encode(array('status' => 0, 'pesan' => 'Modul tidak ditemukan.'));
            return;
        }

        if ($module['status'] === 'finish') {
            echo json_encode(array('status' => 0, 'pesan' => 'Modul yang sudah finish tidak bisa dihapus.'));
            return;
        }

        // Soft delete module
        $this->db->where('id', $module_id);
        $this->db->update('pm_modules', array('is_deleted' => 1));

        // Update project counters (exclude deleted)
        $total_mod = $this->db->where('project_id', $module['project_id'])->where('is_deleted', 0)->count_all_results('pm_modules');
        $finished_mod = $this->db->where('project_id', $module['project_id'])->where('status', 'finish')->where('is_deleted', 0)->count_all_results('pm_modules');
        $this->db->where('id', $module['project_id']);
        $this->db->update('pm_projects', array('total_modules' => $total_mod, 'finished_modules' => $finished_mod));

        echo json_encode(array('status' => 1, 'pesan' => 'Modul "' . $module['module_name'] . '" berhasil dihapus.'));
    }

    /**
     * AJAX: Rollback tahapan ke step sebelumnya
     */
    public function rollback_tahapan()
    {
        $module_id    = $this->input->post('module_id');
        $target_order = $this->input->post('target_order');
        $reason       = trim($this->input->post('reason'));

        if (empty($module_id) || empty($target_order) || empty($reason)) {
            echo json_encode(array('status' => 0, 'pesan' => 'Module, tahapan tujuan, dan alasan wajib diisi.'));
            return;
        }

        $module = $this->Module_model->get_module_by_id($module_id);
        if (!$module) {
            echo json_encode(array('status' => 0, 'pesan' => 'Modul tidak ditemukan.'));
            return;
        }

        // Block jika project On Hold / Completed
        $project = $this->Project_model->get_project_by_id($module['project_id']);
        if ($project && in_array($project['status'], array('On Hold', 'Completed'))) {
            echo json_encode(array('status' => 0, 'pesan' => 'Project sedang ' . $project['status'] . '.'));
            return;
        }

        $this->Module_model->rollback_to_tahapan($module_id, (int)$target_order, (int)$this->input->post('from_order'), $reason, $this->id_user);

        echo json_encode(array('status' => 1, 'pesan' => 'Tahapan berhasil dikembalikan ke step ' . $target_order . '. Versi baru dimulai.'));
    }

    /**
     * AJAX: Save meeting/others entry
     */
    public function save_meeting()
    {
        $module_id        = $this->input->post('module_id');
        $project_id       = $this->input->post('project_id');
        $task_description = trim($this->input->post('task_description'));
        $manhour          = $this->input->post('manhour');
        $remarks          = $this->input->post('remarks');

        if (empty($module_id) || empty($task_description) || empty($manhour)) {
            echo json_encode(array('status' => 0, 'pesan' => 'Deskripsi dan manhour wajib diisi.'));
            return;
        }

        $file_name_hash = NULL;
        $file_name_original = NULL;

        if (!empty($_FILES['meeting_file']['name'])) {
            $upload_result = $this->_upload_task_file('meeting_file');
            if ($upload_result) {
                $file_name_hash = $upload_result['file_name_hash'];
                $file_name_original = $upload_result['file_name_original'];
            }
        }

        $this->Module_model->save_meeting(array(
            'module_id'          => $module_id,
            'project_id'         => $project_id,
            'user_id'            => $this->id_user,
            'task_date'          => date('Y-m-d'),
            'task_description'   => htmlspecialchars($task_description, ENT_QUOTES, 'UTF-8'),
            'manhour'            => (float)$manhour,
            'remarks'            => $remarks ? htmlspecialchars($remarks, ENT_QUOTES, 'UTF-8') : NULL,
            'file_name_original' => $file_name_original,
            'file_name_hash'     => $file_name_hash,
            'created_at'         => $this->datetime
        ));

        echo json_encode(array('status' => 1, 'pesan' => 'Meeting/Others berhasil disimpan.'));
    }

    /**
     * AJAX: Set project status manually (On Hold / In Progress / Completed)
     */
    public function set_project_status()
    {
        $project_id = $this->input->post('project_id');
        $status     = $this->input->post('status');

        if (empty($project_id) || empty($status)) {
            echo json_encode(array('status' => 0, 'pesan' => 'Parameter tidak valid.'));
            return;
        }

        $allowed = array('On Hold', 'In Progress', 'Completed');
        if (!in_array($status, $allowed)) {
            echo json_encode(array('status' => 0, 'pesan' => 'Status tidak dikenali.'));
            return;
        }

        // Jika mau set Completed, validasi semua modul harus sudah finish
        if ($status === 'Completed') {
            $total_modules = $this->db->where('project_id', $project_id)->count_all_results('pm_modules');
            $finished_modules = $this->db->where('project_id', $project_id)->where('status', 'finish')->count_all_results('pm_modules');

            if ($total_modules === 0 || $finished_modules < $total_modules) {
                echo json_encode(array('status' => 0, 'pesan' => 'Tidak bisa finish project. Masih ada modul yang belum selesai.'));
                return;
            }
        }

        $this->db->where('id', $project_id);
        $this->db->update('pm_projects', array('status' => $status));

        echo json_encode(array('status' => 1, 'pesan' => 'Status project berhasil diubah menjadi ' . $status . '.'));
    }

    /**
     * AJAX: Soft delete project (hanya jika status Planning)
     */
    public function delete_project()
    {
        $project_id = $this->input->post('project_id');

        if (empty($project_id)) {
            echo json_encode(array('status' => 0, 'pesan' => 'ID Project tidak valid.'));
            return;
        }

        $project = $this->Project_model->get_project_by_id($project_id);
        if (!$project) {
            echo json_encode(array('status' => 0, 'pesan' => 'Project tidak ditemukan.'));
            return;
        }

        if ($project['status'] !== 'Planning') {
            echo json_encode(array('status' => 0, 'pesan' => 'Hanya project dengan status Planning yang bisa dihapus.'));
            return;
        }

        $this->db->where('id', $project_id);
        $this->db->update('pm_projects', array('deleted' => 1));

        echo json_encode(array('status' => 1, 'pesan' => 'Project berhasil dihapus.'));
    }

    public function add_member()
    {
        $project_id = $this->input->post('project_id');
        $user_id    = $this->input->post('user_id');
        $role       = $this->input->post('role');

        if (empty($project_id) || empty($user_id) || empty($role)) {
            echo json_encode(array('status' => 0, 'pesan' => 'Pilih member dan role!'));
            return;
        }

        $exists = $this->db->get_where('pm_project_members', array('project_id' => $project_id, 'user_id' => $user_id))->num_rows();
        if ($exists > 0) {
            echo json_encode(array('status' => 0, 'pesan' => 'Member sudah terdaftar dalam project ini.'));
            return;
        }

        $insert = $this->db->insert('pm_project_members', array(
            'project_id' => $project_id,
            'user_id'    => $user_id,
            'role'       => $role,
            'created_at' => $this->datetime
        ));

        if ($insert) {
            echo json_encode(array('status' => 1, 'pesan' => 'Member berhasil ditambahkan ke project.'));
        } else {
            echo json_encode(array('status' => 0, 'pesan' => 'Gagal menambahkan member.'));
        }
    }

    /**
     * AJAX: Add module to existing project
     */
    public function add_module()
    {
        $project_id  = $this->input->post('project_id');
        $module_name = trim($this->input->post('module_name'));

        if (empty($project_id) || empty($module_name)) {
            echo json_encode(array('status' => 0, 'pesan' => 'Nama modul wajib diisi!'));
            return;
        }

        // Validasi status project — tidak bisa tambah modul jika Completed atau On Hold
        $project = $this->Project_model->get_project_by_id($project_id);
        if ($project && in_array($project['status'], array('Completed', 'On Hold'))) {
            echo json_encode(array('status' => 0, 'pesan' => 'Tidak bisa menambah modul. Project berstatus ' . $project['status'] . '.'));
            return;
        }

        // Check duplicate module name in same project
        $exists = $this->db->get_where('pm_modules', array('project_id' => $project_id, 'module_name' => $module_name))->num_rows();
        if ($exists > 0) {
            echo json_encode(array('status' => 0, 'pesan' => 'Modul dengan nama "' . $module_name . '" sudah ada di project ini.'));
            return;
        }

        // Get next module order
        $this->db->select_max('module_order');
        $this->db->where('project_id', $project_id);
        $max = $this->db->get('pm_modules')->row();
        $next_order = ($max && $max->module_order) ? $max->module_order + 1 : 1;

        // Get PIC data from post (tahapan_pic[order] = user_id)
        $tahapan_pic     = $this->input->post('tahapan_pic');
        $tahapan_manhour = $this->input->post('tahapan_manhour');
        $tahapan_duedate = $this->input->post('tahapan_duedate');

        $pic_data  = array();
        $plan_data = array();

        if (!empty($tahapan_pic) && is_array($tahapan_pic)) {
            foreach ($tahapan_pic as $order => $uid) {
                if (!empty($uid)) $pic_data[(int)$order] = (int)$uid;
            }
        }
        if (!empty($tahapan_manhour) && is_array($tahapan_manhour)) {
            foreach ($tahapan_manhour as $order => $mh) {
                $plan_data[(int)$order]['manhour'] = (float)$mh;
            }
        }
        if (!empty($tahapan_duedate) && is_array($tahapan_duedate)) {
            foreach ($tahapan_duedate as $order => $dd) {
                $plan_data[(int)$order]['due_date'] = $dd;
            }
        }

        $this->Module_model->create_module_with_tahapan($project_id, $module_name, $next_order, $pic_data, $plan_data);

        // Update project total_modules
        $total_mod = $this->db->where('project_id', $project_id)->count_all_results('pm_modules');
        $this->db->where('id', $project_id);
        $this->db->update('pm_projects', array('total_modules' => $total_mod));

        echo json_encode(array('status' => 1, 'pesan' => 'Modul "' . $module_name . '" berhasil ditambahkan!'));
    }

    /**
     * AJAX: Get add module form modal content
     */
    public function get_add_module_form($project_id)
    {
        $project = $this->Project_model->get_project_by_id($project_id);
        if (!$project) {
            echo '<div class="modal-body text-danger">Project tidak ditemukan.</div>';
            return;
        }

        $data['project']        = $project;
        $data['ba_users']       = $this->Project_model->get_role_user_ids($project_id, 'ba');
        $data['prog_users']     = $this->Project_model->get_role_user_ids($project_id, 'programmer');
        $data['qa_users']       = $this->Project_model->get_role_user_ids($project_id, 'qa');
        $data['master_tahapan'] = $this->Module_model->get_master_tahapan();

        $this->load->view('projects/add_module_form', $data);
    }

    public function remove_member()
    {
        $id = $this->input->post('id');
        if (!empty($id)) {
            $this->db->delete('pm_project_members', array('id' => $id));
            echo json_encode(array('status' => 1, 'pesan' => 'Member berhasil dihapus.'));
        } else {
            echo json_encode(array('status' => 0, 'pesan' => 'ID tidak ditemukan.'));
        }
    }

    /**
     * AJAX: Add role member to project (BA/Programmer/QA)
     */
    public function add_role_member()
    {
        $project_id = $this->input->post('project_id');
        $user_id    = $this->input->post('user_id');
        $role       = $this->input->post('role');

        if (empty($project_id) || empty($user_id) || empty($role)) {
            echo json_encode(array('status' => 0, 'pesan' => 'User dan role wajib diisi!'));
            return;
        }

        // Check duplicate
        $exists = $this->db->get_where('pm_project_roles', array(
            'project_id' => $project_id, 'user_id' => $user_id, 'role' => $role
        ))->num_rows();

        if ($exists > 0) {
            echo json_encode(array('status' => 0, 'pesan' => 'User sudah terdaftar dengan role ini di project.'));
            return;
        }

        $this->db->insert('pm_project_roles', array(
            'project_id' => $project_id,
            'user_id'    => $user_id,
            'role'       => $role,
            'created_at' => $this->datetime
        ));

        echo json_encode(array('status' => 1, 'pesan' => 'Anggota berhasil ditambahkan.'));
    }

    /**
     * AJAX: Remove role member from project
     */
    public function remove_role_member()
    {
        $id = $this->input->post('id');
        if (!empty($id)) {
            $this->db->delete('pm_project_roles', array('id' => $id));
            echo json_encode(array('status' => 1, 'pesan' => 'Anggota berhasil dihapus.'));
        } else {
            echo json_encode(array('status' => 0, 'pesan' => 'ID tidak ditemukan.'));
        }
    }
}
