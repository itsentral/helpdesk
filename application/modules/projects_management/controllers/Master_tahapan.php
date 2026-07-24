<?php defined('BASEPATH') or exit('No direct script access allowed');

class Master_tahapan extends Admin_Controller
{
    protected $id_user;
    protected $datetime;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('projects_management/Module_model');

        date_default_timezone_set('Asia/Bangkok');
        $this->id_user  = $this->auth->user_id();
        $this->datetime = date('Y-m-d H:i:s');
    }

    public function index()
    {
        $this->template->title('Master Tahapan');
        $this->template->page_icon('fa fa-list-ol');

        $data['tahapan'] = $this->db->where('is_deleted', 0)->order_by('tahapan_order', 'ASC')->get('pm_master_tahapan')->result_array();

        $this->template->set($data);
        $this->template->render('master_tahapan/index');
    }

    /**
     * AJAX: Save (create or update)
     */
    public function save()
    {
        $id             = $this->input->post('id');
        $tahapan_order  = $this->input->post('tahapan_order');
        $tahapan_name   = trim($this->input->post('tahapan_name'));
        $default_role   = $this->input->post('default_role');

        if (empty($tahapan_name) || empty($tahapan_order) || empty($default_role)) {
            echo json_encode(array('status' => 0, 'pesan' => 'Semua field wajib diisi.'));
            return;
        }

        $data = array(
            'tahapan_order' => (int)$tahapan_order,
            'tahapan_name'  => $tahapan_name,
            'default_role'  => $default_role,
            'updated_at'    => $this->datetime
        );

        if (!empty($id)) {
            // Update
            $this->db->where('id', $id);
            $this->db->update('pm_master_tahapan', $data);
            echo json_encode(array('status' => 1, 'pesan' => 'Tahapan berhasil diupdate.'));
        } else {
            // Create
            $data['is_active']  = 1;
            $data['created_at'] = $this->datetime;
            $this->db->insert('pm_master_tahapan', $data);
            echo json_encode(array('status' => 1, 'pesan' => 'Tahapan berhasil ditambahkan.'));
        }
    }

    /**
     * AJAX: Toggle active/inactive
     */
    public function toggle_active()
    {
        $id = $this->input->post('id');
        if (empty($id)) {
            echo json_encode(array('status' => 0, 'pesan' => 'ID tidak valid.'));
            return;
        }

        $row = $this->db->get_where('pm_master_tahapan', array('id' => $id))->row_array();
        if (!$row) {
            echo json_encode(array('status' => 0, 'pesan' => 'Data tidak ditemukan.'));
            return;
        }

        $new_status = $row['is_active'] ? 0 : 1;
        $this->db->where('id', $id);
        $this->db->update('pm_master_tahapan', array('is_active' => $new_status, 'updated_at' => $this->datetime));

        echo json_encode(array('status' => 1, 'pesan' => 'Status berhasil diubah.'));
    }

    /**
     * AJAX: Delete (soft delete)
     */
    public function delete()
    {
        $id = $this->input->post('id');
        if (empty($id)) {
            echo json_encode(array('status' => 0, 'pesan' => 'ID tidak valid.'));
            return;
        }

        $this->db->where('id', $id);
        $this->db->update('pm_master_tahapan', array('is_deleted' => 1, 'updated_at' => $this->datetime));
        echo json_encode(array('status' => 1, 'pesan' => 'Tahapan berhasil dihapus.'));
    }
}
