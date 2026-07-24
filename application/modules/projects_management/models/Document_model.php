<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Document_model extends BF_Model
{
    protected $table_name = 'pm_documents';
    protected $key        = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_documents($project_id = null, $category = null)
    {
        $this->db->select('d.*, p.project_code, p.project_name, t.task_name, u.nm_lengkap as uploader_name');
        $this->db->from('pm_documents d');
        $this->db->join('pm_projects p', 'p.id = d.project_id', 'left');
        $this->db->join('pm_tasks t', 't.id = d.task_id', 'left');
        $this->db->join('users u', 'u.id_user = d.uploaded_by', 'left');

        if (!empty($project_id)) {
            $this->db->where('d.project_id', $project_id);
        }
        if (!empty($category)) {
            $this->db->where('d.category', $category);
        }

        $this->db->order_by('d.id', 'DESC');
        return $this->db->get()->result_array();
    }
}
