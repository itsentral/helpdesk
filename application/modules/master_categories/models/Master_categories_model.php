<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Master_categories_model extends BF_Model
{
    protected $ENABLE_ADD;
    protected $ENABLE_MANAGE;
    protected $ENABLE_VIEW;
    protected $ENABLE_DELETE;

    public function __construct()
    {
        parent::__construct();

        $this->ENABLE_ADD     = has_permission('Master_categories.Add');
        $this->ENABLE_MANAGE  = has_permission('Master_categories.Manage');
        $this->ENABLE_VIEW    = has_permission('Master_categories.View');
        $this->ENABLE_DELETE  = has_permission('Master_categories.Delete');
    }

    // SUB CATEGORY MODEL FUNCTIONS

    public function get_all_category()
    {
        $this->db->from('helpdesk_category');
        $this->db->where('is_delete', 0);
        $this->db->order_by('id', 'DESC');
        return $this->db->get()->result();
    }

    public function insert_category($data)
    {
        $this->db->insert('helpdesk_category', $data);
        return $this->db->insert_id();
    }

    public function update_category($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('helpdesk_category', $data);
    }

    public function delete_category($id, $user_id)
    {
        $data = [
            'is_delete'   => 1,
            'create_by'   => $user_id,
            'create_date' => date('Y-m-d H:i:s')
        ];

        $this->db->where('id', $id);
        return $this->db->update('helpdesk_category', $data);
    }

    public function check_category_exists($category_name, $exclude_id = null)
    {
        $this->db->select('id');
        $this->db->from('helpdesk_category');
        $this->db->where('category_name', $category_name);
        $this->db->where('is_delete', 0);

        if ($exclude_id !== null) {
            $this->db->where('id !=', $exclude_id);
        }

        return $this->db->get()->num_rows() > 0;
    }

    // SUB CATEGORY MODEL FUNCTIONS

    public function get_sub_categories_by_category($id_category)
    {
        $this->db->from('helpdesk_sub_category');
        $this->db->where('id_category', $id_category);
        $this->db->where('is_delete', 0);
        $this->db->order_by('id', 'ASC');
        return $this->db->get()->result();
    }

    public function get_sub_category_by_id($id_category)
    {
        $this->db->from('helpdesk_sub_category');
        $this->db->where('id_category', $id_category);
        $this->db->where('is_delete', 0);
        $this->db->order_by('id', 'ASC');
        return $this->db->get()->row();
    }

    public function count_sub_category($id_category)
    {
        $this->db->from('helpdesk_sub_category');
        $this->db->where('id_category', $id_category);
        $this->db->where('is_delete', 0);
        return $this->db->count_all_results();
    }

    public function insert_sub_category($data)
    {
        $this->db->insert('helpdesk_sub_category', $data);
        return $this->db->insert_id();
    }

    public function update_sub_category($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('helpdesk_sub_category', $data);
    }

    public function delete_sub_category($id, $user_id)
    {
        $data = [
            'is_delete'   => 1,
            'create_by'   => $user_id,
            'create_date' => date('Y-m-d H:i:s')
        ];

        $this->db->where('id', $id);
        return $this->db->update('helpdesk_sub_category', $data);
    }

    public function check_sub_category_exists($id_category, $sub_name, $exclude_id = null)
    {
        $this->db->select('id');
        $this->db->from('helpdesk_sub_category');
        $this->db->where('id_category', $id_category);
        $this->db->where('sub_name', $sub_name);
        $this->db->where('is_delete', 0);

        if ($exclude_id !== null) {
            $this->db->where('id !=', $exclude_id);
        }

        return $this->db->get()->num_rows() > 0;
    }

    public function delete_all_sub_categories($id_category, $deleted_by)
    {
        $data = array(
            'is_delete' => 1,
            'delete_by' => $deleted_by,
            'delete_date' => date('Y-m-d H:i:s')
        );

        $this->db->where('id_category', $id_category);
        $this->db->where('is_delete', 0);
        return $this->db->update('helpdesk_sub_category', $data);
    }
}
