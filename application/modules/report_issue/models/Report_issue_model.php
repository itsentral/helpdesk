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
     * Mapping kategori chart/report ke sub_name di helpdesk_sub_category.
     * Dipakai bareng oleh laporan mingguan/bulanan (2 kategori: bugs & issues)
     * dan laporan tahunan (4 kategori: bugs, issues, request, development).
     *
     * @var array
     */
    protected $category_sub_map = [
        'bugs'        => ['bugs program', 'bugs konsep'],
        'issues'      => ['user issue'],
        'request'     => ['request'],
        'development' => ['development'],
    ];

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

    /**
     * Versi bulanan dari get_daily_data(), dipakai untuk laporan Tahunan.
     * Data digroup per bulan (YYYY-MM) untuk 4 kategori: bugs, issues, request, development.
     * Masing-masing kategori mengembalikan total & open (8 array total).
     */
    public function get_monthly_data($client_id, $date_from, $date_to)
    {
        $result = [];

        foreach ($this->category_sub_map as $key => $subs) {
            $result[$key] = $this->db
                ->select("DATE_FORMAT(h.create_date, '%Y-%m') as month, COUNT(*) as total")
                ->from('helpdesk h')
                ->join('helpdesk_sub_category hsc', 'hsc.id = h.sub_category_id', 'left')
                ->where('h.client_id', $client_id)
                ->where('h.is_delete', 0)
                ->where_in('hsc.sub_name', $subs)
                ->where('DATE(h.create_date) >=', $date_from)
                ->where('DATE(h.create_date) <=', $date_to)
                ->group_by("DATE_FORMAT(h.create_date, '%Y-%m')")
                ->order_by('month', 'ASC')
                ->get()
                ->result();

            $result[$key . '_open'] = $this->db
                ->select("DATE_FORMAT(h.create_date, '%Y-%m') as month, COUNT(*) as total")
                ->from('helpdesk h')
                ->join('helpdesk_sub_category hsc', 'hsc.id = h.sub_category_id', 'left')
                ->where('h.client_id', $client_id)
                ->where('h.is_delete', 0)
                ->where('h.status', 0)
                ->where_in('hsc.sub_name', $subs)
                ->where('DATE(h.create_date) >=', $date_from)
                ->where('DATE(h.create_date) <=', $date_to)
                ->group_by("DATE_FORMAT(h.create_date, '%Y-%m')")
                ->order_by('month', 'ASC')
                ->get()
                ->result();
        }

        return $result;
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

    /**
     * Versi 4 kategori dari get_total_tickets(), dipakai untuk laporan Tahunan.
     * Mengembalikan total & open per kategori (bugs, issues, request, development)
     * plus grand total & grand total open.
     */
    public function get_total_tickets_yearly($client_id, $date_from, $date_to)
    {
        $totals      = [];
        $grand_total = 0;
        $grand_open  = 0;

        foreach ($this->category_sub_map as $key => $subs) {
            $total = $this->db
                ->from('helpdesk h')
                ->join('helpdesk_sub_category hsc', 'hsc.id = h.sub_category_id', 'left')
                ->where('h.client_id', $client_id)
                ->where('h.is_delete', 0)
                ->where_in('hsc.sub_name', $subs)
                ->where('DATE(h.create_date) >=', $date_from)
                ->where('DATE(h.create_date) <=', $date_to)
                ->count_all_results();

            $open = $this->db
                ->from('helpdesk h')
                ->join('helpdesk_sub_category hsc', 'hsc.id = h.sub_category_id', 'left')
                ->where('h.client_id', $client_id)
                ->where('h.is_delete', 0)
                ->where('h.status', 0)
                ->where_in('hsc.sub_name', $subs)
                ->where('DATE(h.create_date) >=', $date_from)
                ->where('DATE(h.create_date) <=', $date_to)
                ->count_all_results();

            $totals[$key]            = $total;
            $totals['open_' . $key]  = $open;

            $grand_total += $total;
            $grand_open  += $open;
        }

        $totals['total']      = $grand_total;
        $totals['total_open'] = $grand_open;

        return $totals;
    }

    public function get_tickets_by_date_range($client_id, $date_from, $date_to, $all_categories = false)
    {
        $subs = $all_categories
            ? ['bugs program', 'bugs konsep', 'user issue', 'request', 'development']
            : ['bugs program', 'bugs konsep', 'user issue'];

        return $this->db
            ->select('h.*, hc.category_name, hsc.sub_name as sub_category_name')
            ->from('helpdesk h')
            ->join('helpdesk_category hc', 'hc.id = h.category_id', 'left')
            ->join('helpdesk_sub_category hsc', 'hsc.id = h.sub_category_id', 'left')
            ->where('h.client_id', $client_id)
            ->where('h.is_delete', 0)
            ->where_in('hsc.sub_name', $subs)
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

    /**
     * @param string $mode 'day' (default, $date = 'YYYY-MM-DD') atau 'month' ($date = 'YYYY-MM').
     *                     Tidak berlaku ketika $date === 'carry_over'.
     * @param bool $all_categories Jika true, opsi kategori 'all' mencakup 4 kategori
     *                             (bugs, issues, request, development) - dipakai laporan Tahunan.
     *                             Jika false (default), 'all' hanya bugs & issues (perilaku lama).
     */
    public function get_tickets_by_date($client_id, $date, $category = 'all', $date_from = null, $mode = 'day', $all_categories = false)
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

            $this->_apply_category_filter($category, $all_categories);

            return $this->db->order_by('h.create_date', 'ASC')->get()->result();
        }

        $this->db
            ->select('h.*, hc.category_name, hsc.sub_name as sub_category_name')
            ->from('helpdesk h')
            ->join('helpdesk_category hc', 'hc.id = h.category_id', 'left')
            ->join('helpdesk_sub_category hsc', 'hsc.id = h.sub_category_id', 'left')
            ->where('h.client_id', $client_id)
            ->where('h.is_delete', 0);

        if ($mode === 'month') {
            $this->db->where("DATE_FORMAT(h.create_date, '%Y-%m') =", $date);
        } else {
            $this->db->where('DATE(h.create_date)', $date);
        }

        $this->_apply_category_filter($category, $all_categories);

        return $this->db->order_by('h.create_date', 'DESC')->get()->result();
    }

    /**
     * Helper: apply where_in hsc.sub_name berdasarkan kategori yang diminta.
     * Dipakai bareng oleh get_tickets_by_date().
     */
    private function _apply_category_filter($category, $all_categories = false)
    {
        if (isset($this->category_sub_map[$category])) {
            $this->db->where_in('hsc.sub_name', $this->category_sub_map[$category]);
            return;
        }

        if ($all_categories) {
            $all_subs = call_user_func_array('array_merge', array_values($this->category_sub_map));
            $this->db->where_in('hsc.sub_name', $all_subs);
        } else {
            $this->db->where_in('hsc.sub_name', ['bugs program', 'bugs konsep', 'user issue']);
        }
    }

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

    /**
     * Versi bulanan dari get_manhour_data(), dipakai untuk laporan Tahunan.
     */
    public function get_manhour_data_monthly($client_id, $date_from, $date_to)
    {
        $plan_data = $this->db
            ->select("DATE_FORMAT(h.create_date, '%Y-%m') as month, SUM(h.man_hour_plan) as total")
            ->from('helpdesk h')
            ->where('h.client_id', $client_id)
            ->where('h.is_delete', 0)
            ->where('DATE(h.create_date) >=', $date_from)
            ->where('DATE(h.create_date) <=', $date_to)
            ->group_by("DATE_FORMAT(h.create_date, '%Y-%m')")
            ->order_by('month', 'ASC')
            ->get()
            ->result();

        $actual_data = $this->db
            ->select("DATE_FORMAT(h.create_date, '%Y-%m') as month, SUM(h.man_hour_actual) as total")
            ->from('helpdesk h')
            ->where('h.client_id', $client_id)
            ->where('h.is_delete', 0)
            ->where('DATE(h.create_date) >=', $date_from)
            ->where('DATE(h.create_date) <=', $date_to)
            ->group_by("DATE_FORMAT(h.create_date, '%Y-%m')")
            ->order_by('month', 'ASC')
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