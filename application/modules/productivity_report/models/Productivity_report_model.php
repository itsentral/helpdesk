<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Productivity_report_model extends BF_Model
{
    protected $ENABLE_ADD;
    protected $ENABLE_MANAGE;
    protected $ENABLE_VIEW;
    protected $ENABLE_DELETE;

    public function __construct()
    {
        parent::__construct();

        $this->ENABLE_ADD    = has_permission('Productivity_report.Add');
        $this->ENABLE_MANAGE = has_permission('Productivity_report.Manage');
        $this->ENABLE_VIEW   = has_permission('Productivity_report.View');
        $this->ENABLE_DELETE = has_permission('Productivity_report.Delete');
    }

    public function get_clients()
    {
        return $this->db
            ->select('id, name_app')
            ->from('helpdesk_client')
            ->where('is_delete', 0)
            ->order_by('name_app', 'ASC')
            ->get()
            ->result();
    }

    public function get_categories()
    {
        return $this->db
            ->select('id, category_name')
            ->from('helpdesk_category')
            ->order_by('category_name', 'ASC')
            ->get()
            ->result();
    }

    public function get_programmer_productivity($date_from, $date_to, $client_id = null, $category_id = null)
    {
        $this->db->select([
            'u.id_user',
            'u.nm_lengkap AS nama',
            '"Programmer" AS role',
            'COUNT(h.id) AS total_ticket',
            'SUM(h.man_hour_plan) AS total_man_hour_plan',
            'SUM(h.man_hour_actual) AS total_man_hour_actual',
            'SUM(CASE WHEN h.status = 4 THEN 1 ELSE 0 END) AS total_done',
            'SUM(CASE WHEN h.status = 5 THEN 1 ELSE 0 END) AS total_close',
            'SUM(CASE WHEN h.status = 0 THEN 1 ELSE 0 END) AS total_open',
            'SUM(CASE WHEN h.status = 1 THEN 1 ELSE 0 END) AS total_process',
            'SUM(CASE WHEN h.status = 2 THEN 1 ELSE 0 END) AS total_pending',
            'SUM(CASE WHEN h.status = 6 THEN 1 ELSE 0 END) AS total_revisi',
            'SUM(CASE WHEN h.status IN (4,5) THEN h.man_hour_plan   ELSE 0 END) AS mh_plan_completed',
            'SUM(CASE WHEN h.status IN (4,5) THEN h.man_hour_actual ELSE 0 END) AS mh_actual_completed',
        ], false);

        $this->db->from('users u');
        $this->db->join('helpdesk h', 'h.pic_id = u.id_user AND h.is_delete = 0', 'inner');
        $this->db->where('h.status !=', 3);
        $this->db->where('DATE(h.update_date) >=', $date_from);
        $this->db->where('DATE(h.update_date) <=', $date_to);

        if (!empty($client_id)) {
            $this->db->where('h.client_id', $client_id);
        }

        if (!empty($category_id)) {
            $this->db->where('h.category_id', $category_id);
        }

        $this->db->where('u.is_programmer', 1);
        $this->db->where('u.deleted', 0);

        $this->db->group_by('u.id_user');
        // $this->db->order_by('total_man_hour_actual', 'DESC');
        $this->db->order_by('u.nm_lengkap', 'ASC');

        return $this->db->get()->result();
    }

    public function get_ba_productivity($date_from, $date_to, $client_id = null, $category_id = null)
    {
        $this->db->select([
            'u.id_user',
            'u.nm_lengkap AS nama',
            '"Business Analyst" AS role',
            'COUNT(h.id) AS total_ticket',
            'SUM(h.man_hour_plan) AS total_man_hour_plan',
            'SUM(h.man_hour_actual) AS total_man_hour_actual',
            'SUM(CASE WHEN h.status = 4 THEN 1 ELSE 0 END) AS total_done',
            'SUM(CASE WHEN h.status = 5 THEN 1 ELSE 0 END) AS total_close',
            'SUM(CASE WHEN h.status = 0 THEN 1 ELSE 0 END) AS total_open',
            'SUM(CASE WHEN h.status = 1 THEN 1 ELSE 0 END) AS total_process',
            'SUM(CASE WHEN h.status = 2 THEN 1 ELSE 0 END) AS total_pending',
            'SUM(CASE WHEN h.status = 6 THEN 1 ELSE 0 END) AS total_revisi',
            'SUM(CASE WHEN h.status IN (4,5) THEN h.man_hour_plan   ELSE 0 END) AS mh_plan_completed',
            'SUM(CASE WHEN h.status IN (4,5) THEN h.man_hour_actual ELSE 0 END) AS mh_actual_completed',
        ], false);

        $this->db->from('users u');
        $this->db->join('helpdesk h', 'h.pic_id = u.id_user AND h.is_delete = 0', 'inner');
        $this->db->where('h.status !=', 3);
        $this->db->where('DATE(h.update_date) >=', $date_from);
        $this->db->where('DATE(h.update_date) <=', $date_to);

        if (!empty($client_id)) {
            $this->db->where('h.client_id', $client_id);
        }

        if (!empty($category_id)) {
            $this->db->where('h.category_id', $category_id);
        }

        $this->db->where('u.is_ba', 1);
        $this->db->where('u.deleted', 0);

        $this->db->group_by('u.id_user');
        // $this->db->order_by('total_man_hour_actual', 'DESC');
        $this->db->order_by('u.nm_lengkap', 'ASC');

        return $this->db->get()->result();
    }

    public function get_ticket_detail($user_id, $role, $date_from, $date_to, $client_id = null, $category_id = null)
    {
        $this->db->select([
            'h.id',
            'h.no_ticket',
            'h.report',
            'h.client_name',
            'h.category_name',
            'h.sub_category_name',
            'h.man_hour_plan',
            'h.man_hour_actual',
            'h.status',
            'h.due_date',
            'h.pic',
            'h.update_date',
        ], false);

        $this->db->from('helpdesk h');
        $this->db->where('h.is_delete', 0);
        $this->db->where('h.status !=', 3);
        $this->db->where('DATE(h.update_date) >=', $date_from);
        $this->db->where('DATE(h.update_date) <=', $date_to);

        if (!empty($client_id)) {
            $this->db->where('h.client_id', $client_id);
        }
        if (!empty($category_id)) {
            $this->db->where('h.category_id', $category_id);
        }

        $this->db->where('h.pic_id =', (int)$user_id);

        $this->db->order_by('h.update_date', 'DESC');

        return $this->db->get()->result();
    }
}
