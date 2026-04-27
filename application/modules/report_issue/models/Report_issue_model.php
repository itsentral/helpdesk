<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * @author Yunas Handra
 * @copyright Copyright (c) 2016, Yunas Handra
 * 
 * This is model class for table "log_5masterbarang"
 */

class Report_issue_model extends BF_Model
{

    protected $ENABLE_ADD;
    protected $ENABLE_MANAGE;
    protected $ENABLE_VIEW;
    protected $ENABLE_DELETE;

    /**
     * @var string  User Table Name
     */
    protected $table_name = 'log_5masterbarang';
    protected $key        = 'kodebarang';

    /**
     * @var string Field name to use for the created time column in the DB table
     * if $set_created is enabled.
     */
    protected $created_field = 'created_on';

    /**
     * @var string Field name to use for the modified time column in the DB
     * table if $set_modified is enabled.
     */
    protected $modified_field = 'modified_on';

    /**
     * @var bool Set the created time automatically on a new record (if true)
     */
    protected $set_created = true;

    /**
     * @var bool Set the modified time automatically on editing a record (if true)
     */
    protected $set_modified = true;
    /**
     * @var string The type of date/time field used for $created_field and $modified_field.
     * Valid values are 'int', 'datetime', 'date'.
     */
    /**
     * @var bool Enable/Disable soft deletes.
     * If false, the delete() method will perform a delete of that row.
     * If true, the value in $deleted_field will be set to 1.
     */
    protected $soft_deletes = true;

    protected $date_format = 'datetime';

    /**
     * @var bool If true, will log user id in $created_by_field, $modified_by_field,
     * and $deleted_by_field.
     */
    protected $log_user = true;

    /**
     * Function construct used to load some library, do some actions, etc.
     */
    public function __construct()
    {
        parent::__construct();

        $this->ENABLE_ADD     = has_permission('Report_issue.Add');
        $this->ENABLE_MANAGE  = has_permission('Report_issue.Manage');
        $this->ENABLE_VIEW    = has_permission('Report_issue.View');
        $this->ENABLE_DELETE  = has_permission('Report_issue.Delete');
    }

    public function get_user_clients($user_id)
    {
        return $this->db
            ->select('huc.*, hc.name_app')
            ->from('helpdesk_user_client huc')
            ->join('helpdesk_client hc', 'hc.id = huc.client_id')
            ->where('huc.id_user', $user_id)
            ->where('huc.is_active', 1)
            ->where('hc.is_delete', 0)
            ->get()
            ->result();
    }

    public function get_status_data($client_id, $date_from, $date_to)
    {
        return $this->db
            ->select('status, COUNT(*) as total')
            ->from('helpdesk')
            ->where('client_id', $client_id)
            ->where('is_delete', 0)
            ->where('DATE(create_date) >=', $date_from)
            ->where('DATE(create_date) <=', $date_to)
            ->group_by('status')
            ->get()
            ->result();
    }

    public function get_category_data($client_id, $date_from, $date_to)
    {
        return $this->db
            ->select('category_name, COUNT(*) as total')
            ->from('helpdesk')
            ->where('client_id', $client_id)
            ->where('is_delete', 0)
            ->where('DATE(create_date) >=', $date_from)
            ->where('DATE(create_date) <=', $date_to)
            ->group_by('category_name')
            ->get()
            ->result();
    }

    public function get_daily_data($client_id, $date_from, $date_to)
    {
        // Get bugs & error data (total)
        $bugs_data = $this->db
            ->select('DATE(h.create_date) as date, COUNT(*) as total')
            ->from('helpdesk h')
            ->join('helpdesk_sub_category hsc', 'hsc.id = h.sub_category_id', 'left')
            ->where('h.client_id', $client_id)
            ->where('h.is_delete', 0)
            ->where_in('hsc.sub_name', ['bugs program', 'bugs konsep'])
            ->where('DATE(h.create_date) >=', $date_from)
            ->where('DATE(h.create_date) <=', $date_to)
            ->group_by('DATE(h.create_date)')
            ->order_by('date', 'ASC')
            ->get()
            ->result();

        // Get bugs & error data (open only - status = 0)
        $bugs_open_data = $this->db
            ->select('DATE(h.create_date) as date, COUNT(*) as total')
            ->from('helpdesk h')
            ->join('helpdesk_sub_category hsc', 'hsc.id = h.sub_category_id', 'left')
            ->where('h.client_id', $client_id)
            ->where('h.is_delete', 0)
            ->where('h.status', 0)
            ->where_in('hsc.sub_name', ['bugs program', 'bugs konsep'])
            ->where('DATE(h.create_date) >=', $date_from)
            ->where('DATE(h.create_date) <=', $date_to)
            ->group_by('DATE(h.create_date)')
            ->order_by('date', 'ASC')
            ->get()
            ->result();

        // Get issues data (total)
        $issues_data = $this->db
            ->select('DATE(h.create_date) as date, COUNT(*) as total')
            ->from('helpdesk h')
            ->join('helpdesk_sub_category hsc', 'hsc.id = h.sub_category_id', 'left')
            ->where('h.client_id', $client_id)
            ->where('h.is_delete', 0)
            ->where('hsc.sub_name', 'user issue')
            ->where('DATE(h.create_date) >=', $date_from)
            ->where('DATE(h.create_date) <=', $date_to)
            ->group_by('DATE(h.create_date)')
            ->order_by('date', 'ASC')
            ->get()
            ->result();

        // Get issues data (open only - status = 0)
        $issues_open_data = $this->db
            ->select('DATE(h.create_date) as date, COUNT(*) as total')
            ->from('helpdesk h')
            ->join('helpdesk_sub_category hsc', 'hsc.id = h.sub_category_id', 'left')
            ->where('h.client_id', $client_id)
            ->where('h.is_delete', 0)
            ->where('h.status', 0)
            ->where('hsc.sub_name', 'user issue')
            ->where('DATE(h.create_date) >=', $date_from)
            ->where('DATE(h.create_date) <=', $date_to)
            ->group_by('DATE(h.create_date)')
            ->order_by('date', 'ASC')
            ->get()
            ->result();

        return [
            'bugs' => $bugs_data,
            'bugs_open' => $bugs_open_data,
            'issues' => $issues_data,
            'issues_open' => $issues_open_data
        ];
    }

    public function get_total_tickets($client_id, $date_from, $date_to)
    {
        // Total bugs & error
        $total_bugs = $this->db
            ->from('helpdesk h')
            ->join('helpdesk_sub_category hsc', 'hsc.id = h.sub_category_id', 'left')
            ->where('h.client_id', $client_id)
            ->where('h.is_delete', 0)
            ->where_in('hsc.sub_name', ['bugs program', 'bugs konsep'])
            ->where('DATE(h.create_date) >=', $date_from)
            ->where('DATE(h.create_date) <=', $date_to)
            ->count_all_results();

        // Total bugs & error yang open
        $open_bugs = $this->db
            ->from('helpdesk h')
            ->join('helpdesk_sub_category hsc', 'hsc.id = h.sub_category_id', 'left')
            ->where('h.client_id', $client_id)
            ->where('h.is_delete', 0)
            ->where('h.status', 0) // status open
            ->where_in('hsc.sub_name', ['bugs program', 'bugs konsep'])
            ->where('DATE(h.create_date) >=', $date_from)
            ->where('DATE(h.create_date) <=', $date_to)
            ->count_all_results();

        // Total user issues
        $total_issues = $this->db
            ->from('helpdesk h')
            ->join('helpdesk_sub_category hsc', 'hsc.id = h.sub_category_id', 'left')
            ->where('h.client_id', $client_id)
            ->where('h.is_delete', 0)
            ->where('hsc.sub_name', 'user issue')
            ->where('DATE(h.create_date) >=', $date_from)
            ->where('DATE(h.create_date) <=', $date_to)
            ->count_all_results();

        // Total user issues yang open
        $open_issues = $this->db
            ->from('helpdesk h')
            ->join('helpdesk_sub_category hsc', 'hsc.id = h.sub_category_id', 'left')
            ->where('h.client_id', $client_id)
            ->where('h.is_delete', 0)
            ->where('h.status', 0) // status open
            ->where('hsc.sub_name', 'user issue')
            ->where('DATE(h.create_date) >=', $date_from)
            ->where('DATE(h.create_date) <=', $date_to)
            ->count_all_results();

        return [
            'total' => $total_bugs + $total_issues,
            'bugs' => $total_bugs,
            'issues' => $total_issues,
            'open_bugs' => $open_bugs,
            'open_issues' => $open_issues,
            'total_open' => $open_bugs + $open_issues
        ];
    }

    public function get_tickets_by_date_range($client_id, $date_from, $date_to)
    {
        return $this->db
            ->select('h.*, hc.category_name, hsc.sub_name as sub_category_name')
            ->from('helpdesk h')
            ->join('helpdesk_category hc', 'hc.id = h.category_id', 'left')
            ->join('helpdesk_sub_category hsc', 'hsc.id = h.sub_category_id', 'left')
            ->where('h.client_id', $client_id)
            ->where('h.is_delete', 0)
            ->where_in('hsc.sub_name', ['bugs program', 'bugs konsep', 'user issue'])
            ->where('DATE(h.create_date) >=', $date_from)
            ->where('DATE(h.create_date) <=', $date_to)
            ->order_by('h.create_date', 'ASC')
            ->get()
            ->result();
    }

    public function get_client_info($client_id)
    {
        return $this->db
            ->select('*')
            ->from('helpdesk_client')
            ->where('id', $client_id)
            ->get()
            ->row();
    }

    // public function get_tickets_by_date($client_id, $date, $category = 'all')
    // {
    //     $this->db
    //         ->select('h.*, hc.category_name, hsc.sub_name as sub_category_name')
    //         ->from('helpdesk h')
    //         ->join('helpdesk_category hc', 'hc.id = h.category_id', 'left')
    //         ->join('helpdesk_sub_category hsc', 'hsc.id = h.sub_category_id', 'left')
    //         ->where('h.client_id', $client_id)
    //         ->where('h.is_delete', 0)
    //         ->where('DATE(h.create_date)', $date);

    //     // Filter by sub category
    //     if ($category === 'bugs') {
    //         $this->db->where_in('hsc.sub_name', ['bugs program', 'bugs konsep']);
    //     } else if ($category === 'issues') {
    //         $this->db->where('hsc.sub_name', 'user issue');
    //     } else {
    //         // 'all' = tetap harus filter hanya bugs & user issue
    //         $this->db->where_in('hsc.sub_name', ['bugs program', 'bugs konsep', 'user issue']);
    //     }

    //     return $this->db
    //         ->order_by('h.create_date', 'DESC')
    //         ->get()
    //         ->result();
    // }

    public function get_tickets_by_date($client_id, $date, $category = 'all', $date_from = null)
    {
        if ($date === 'carry_over' && $date_from) {
            $extended_from = date('Y-m-d', strtotime($date_from . ' -90 days'));

            $this->db
                ->select('h.*, hc.category_name, hsc.sub_name as sub_category_name')
                ->from('helpdesk h')
                ->join('helpdesk_category hc', 'hc.id = h.category_id', 'left')
                ->join('helpdesk_sub_category hsc', 'hsc.id = h.sub_category_id', 'left')
                ->where('h.client_id', $client_id)
                ->where('h.is_delete', 0)
                ->where('h.status', 0)
                ->where('DATE(h.create_date) >=', $extended_from)
                ->where('DATE(h.create_date) <', $date_from);

            if ($category === 'bugs') {
                $this->db->where_in('hsc.sub_name', ['bugs program', 'bugs konsep']);
            } elseif ($category === 'issues') {
                $this->db->where('hsc.sub_name', 'user issue');
            } else {
                $this->db->where_in('hsc.sub_name', ['bugs program', 'bugs konsep', 'user issue']);
            }

            return $this->db->order_by('h.create_date', 'ASC')->get()->result();
        }

        // Logic normal (tidak berubah)
        $this->db
            ->select('h.*, hc.category_name, hsc.sub_name as sub_category_name')
            ->from('helpdesk h')
            ->join('helpdesk_category hc', 'hc.id = h.category_id', 'left')
            ->join('helpdesk_sub_category hsc', 'hsc.id = h.sub_category_id', 'left')
            ->where('h.client_id', $client_id)
            ->where('h.is_delete', 0)
            ->where('DATE(h.create_date)', $date);

        if ($category === 'bugs') {
            $this->db->where_in('hsc.sub_name', ['bugs program', 'bugs konsep']);
        } elseif ($category === 'issues') {
            $this->db->where('hsc.sub_name', 'user issue');
        } else {
            $this->db->where_in('hsc.sub_name', ['bugs program', 'bugs konsep', 'user issue']);
        }

        return $this->db->order_by('h.create_date', 'DESC')->get()->result();
    }

    // public function get_daily_data_with_status($client_id, $date_from, $date_to)
    // {
    //     // Get bugs & error data (total)
    //     $bugs_data = $this->db
    //         ->select('DATE(h.create_date) as date, COUNT(*) as total')
    //         ->from('helpdesk h')
    //         ->join('helpdesk_sub_category hsc', 'hsc.id = h.sub_category_id', 'left')
    //         ->where('h.client_id', $client_id)
    //         ->where('h.is_delete', 0)
    //         ->where_in('hsc.sub_name', ['bugs program', 'bugs konsep'])
    //         ->where('DATE(h.create_date) >=', $date_from)
    //         ->where('DATE(h.create_date) <=', $date_to)
    //         ->group_by('DATE(h.create_date)')
    //         ->order_by('date', 'ASC')
    //         ->get()
    //         ->result();

    //     // Get bugs & error data (open only - status = 0)
    //     $bugs_open_data = $this->db
    //         ->select('DATE(h.create_date) as date, COUNT(*) as total')
    //         ->from('helpdesk h')
    //         ->join('helpdesk_sub_category hsc', 'hsc.id = h.sub_category_id', 'left')
    //         ->where('h.client_id', $client_id)
    //         ->where('h.is_delete', 0)
    //         ->where('h.status', 0)
    //         ->where_in('hsc.sub_name', ['bugs program', 'bugs konsep'])
    //         ->where('DATE(h.create_date) >=', $date_from)
    //         ->where('DATE(h.create_date) <=', $date_to)
    //         ->group_by('DATE(h.create_date)')
    //         ->order_by('date', 'ASC')
    //         ->get()
    //         ->result();

    //     // Get issues data (total)
    //     $issues_data = $this->db
    //         ->select('DATE(h.create_date) as date, COUNT(*) as total')
    //         ->from('helpdesk h')
    //         ->join('helpdesk_sub_category hsc', 'hsc.id = h.sub_category_id', 'left')
    //         ->where('h.client_id', $client_id)
    //         ->where('h.is_delete', 0)
    //         ->where('hsc.sub_name', 'user issue')
    //         ->where('DATE(h.create_date) >=', $date_from)
    //         ->where('DATE(h.create_date) <=', $date_to)
    //         ->group_by('DATE(h.create_date)')
    //         ->order_by('date', 'ASC')
    //         ->get()
    //         ->result();

    //     // Get issues data (open only - status = 0)
    //     $issues_open_data = $this->db
    //         ->select('DATE(h.create_date) as date, COUNT(*) as total')
    //         ->from('helpdesk h')
    //         ->join('helpdesk_sub_category hsc', 'hsc.id = h.sub_category_id', 'left')
    //         ->where('h.client_id', $client_id)
    //         ->where('h.is_delete', 0)
    //         ->where('h.status', 0)
    //         ->where('hsc.sub_name', 'user issue')
    //         ->where('DATE(h.create_date) >=', $date_from)
    //         ->where('DATE(h.create_date) <=', $date_to)
    //         ->group_by('DATE(h.create_date)')
    //         ->order_by('date', 'ASC')
    //         ->get()
    //         ->result();

    //     return [
    //         'bugs' => $bugs_data,
    //         'bugs_open' => $bugs_open_data,
    //         'issues' => $issues_data,
    //         'issues_open' => $issues_open_data
    //     ];
    // }

    public function get_manhour_data($client_id, $date_from, $date_to)
    {
        // Man hour plan per hari
        $plan_data = $this->db
            ->select('DATE(h.create_date) as date, SUM(h.man_hour_plan) as total')
            ->from('helpdesk h')
            ->where('h.client_id', $client_id)
            ->where('h.is_delete', 0)
            ->where('DATE(h.create_date) >=', $date_from)
            ->where('DATE(h.create_date) <=', $date_to)
            ->group_by('DATE(h.create_date)')
            ->order_by('date', 'ASC')
            ->get()
            ->result();

        // Man hour actual per hari
        $actual_data = $this->db
            ->select('DATE(h.create_date) as date, SUM(h.man_hour_actual) as total')
            ->from('helpdesk h')
            ->where('h.client_id', $client_id)
            ->where('h.is_delete', 0)
            ->where('DATE(h.create_date) >=', $date_from)
            ->where('DATE(h.create_date) <=', $date_to)
            ->group_by('DATE(h.create_date)')
            ->order_by('date', 'ASC')
            ->get()
            ->result();

        return [
            'plan'   => $plan_data,
            'actual' => $actual_data,
        ];
    }

    public function get_open_data_extended($client_id, $date_from, $date_to)
    {
        // Hitung 30 hari sebelum date_from
        $extended_from = date('Y-m-d', strtotime($date_from . ' -90 days'));

        // Bugs open per hari (dari extended_from sampai date_to)
        $bugs_open_data = $this->db
            ->select('DATE(h.create_date) as date, COUNT(*) as total')
            ->from('helpdesk h')
            ->join('helpdesk_sub_category hsc', 'hsc.id = h.sub_category_id', 'left')
            ->where('h.client_id', $client_id)
            ->where('h.is_delete', 0)
            ->where('h.status', 0)
            ->where_in('hsc.sub_name', ['bugs program', 'bugs konsep'])
            ->where('DATE(h.create_date) >=', $extended_from)
            ->where('DATE(h.create_date) <=', $date_to)
            ->group_by('DATE(h.create_date)')
            ->order_by('date', 'ASC')
            ->get()
            ->result();

        // Issues open per hari (dari extended_from sampai date_to)
        $issues_open_data = $this->db
            ->select('DATE(h.create_date) as date, COUNT(*) as total')
            ->from('helpdesk h')
            ->join('helpdesk_sub_category hsc', 'hsc.id = h.sub_category_id', 'left')
            ->where('h.client_id', $client_id)
            ->where('h.is_delete', 0)
            ->where('h.status', 0)
            ->where('hsc.sub_name', 'user issue')
            ->where('DATE(h.create_date) >=', $extended_from)
            ->where('DATE(h.create_date) <=', $date_to)
            ->group_by('DATE(h.create_date)')
            ->order_by('date', 'ASC')
            ->get()
            ->result();

        return [
            'bugs_open'   => $bugs_open_data,
            'issues_open' => $issues_open_data,
            'extended_from' => $extended_from,
        ];
    }

    public function get_open_tickets_extended($client_id, $date_from, $date_to)
    {
        $extended_from = date('Y-m-d', strtotime($date_from . ' -90 days'));

        return $this->db
            ->select('h.*, hc.category_name, hsc.sub_name as sub_category_name')
            ->from('helpdesk h')
            ->join('helpdesk_category hc', 'hc.id = h.category_id', 'left')
            ->join('helpdesk_sub_category hsc', 'hsc.id = h.sub_category_id', 'left')
            ->where('h.client_id', $client_id)
            ->where('h.is_delete', 0)
            ->where('h.status', 0) // hanya yang open
            ->where_in('hsc.sub_name', ['bugs program', 'bugs konsep', 'user issue'])
            ->where('DATE(h.create_date) >=', $extended_from)
            ->where('DATE(h.create_date) <', $date_from) // hanya yang SEBELUM periode minggu ini
            ->order_by('h.create_date', 'ASC')
            ->get()
            ->result();
    }

    public function get_my_priorities($user_id)
    {
        $user_info = $this->db->get_where('users', ['id_user' => $user_id])->row();
        if (!$user_info) return [];

        $field = null;
        if ($user_info->is_programmer == 1) {
            $field = 'h.order_programmer';
        } elseif ($user_info->is_ba == 1) {
            $field = 'h.order_ba';
        }

        if (!$field) return [];

        $this->db->select('h.id, h.no_ticket, h.report, h.client_name, h.sub_category_name, h.status, h.due_date, h.pic');
        $this->db->from('helpdesk h');
        $this->db->where('h.pic_id', $user_id);
        $this->db->where('h.is_approve', 0);
        $this->db->where('h.is_delete', 0);
        $this->db->where_in('h.status', [0, 1, 2, 6]); // open, process, pending, revisi
        $this->db->order_by($field, 'ASC');
        $this->db->limit(3);

        return $this->db->get()->result();
    }
}
