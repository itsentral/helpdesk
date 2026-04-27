<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * @author Yunas Handra
 * @copyright Copyright (c) 2016, Yunas Handra
 * 
 * This is model class for table "log_5masterbarang"
 */

class Dashboard_model extends BF_Model
{

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

    // ─────────────────────────────────────────────────
    // METHOD 1 — Ambil semua sub category aktif
    // ─────────────────────────────────────────────────
    public function get_all_subcategories()
    {
        return $this->db
            ->select('MIN(hsc.id) as id, hsc.sub_name')
            ->from('helpdesk_sub_category hsc')
            ->group_by('hsc.sub_name')
            ->order_by('hsc.sub_name')
            ->get()
            ->result();
    }

    public function get_project_issues($date_from, $date_to, $subcat_names = [], $all_time = false)
    {
        $this->db->select('
        hc.id      AS client_id,
        hc.name_app AS client_name,
        h.id       AS ticket_id,
        h.no_ticket,
        h.report,
        h.category_name,
        h.sub_category_name,
        h.status,
        h.due_date,
        h.pic,
        h.pic_id,
        h.update_date
    ');
        $this->db->from('helpdesk_client hc');
        $this->db->join(
            'helpdesk h',
            'h.client_id = hc.id
         AND h.is_delete = 0
         AND h.status IN (0,1,2,4,6)'
                . (!$all_time
                    ? " AND DATE(h.update_date) >= '{$date_from}' AND DATE(h.update_date) <= '{$date_to}'"
                    : '')
                . (!empty($subcat_names)
                    ? " AND h.sub_category_name IN ('" . implode("','", array_map([$this->db, 'escape_str'], $subcat_names)) . "')"
                    : ''),
            'left'
        );
        $this->db->where('hc.is_delete', 0);
        $this->db->order_by('hc.name_app, h.due_date', 'ASC');

        return $this->db->get()->result();
    }

    public function get_tickets_by_client($client_id, $date_from, $date_to, $subcat_names = [], $all_time = false)
    {
        $this->db
            ->select('
            h.id,
            h.no_ticket,
            h.report,
            h.client_id,
            h.client_name,
            h.category_name,
            h.sub_category_name,
            h.status,
            h.due_date,
            h.pic,
            h.pic_id,
            h.update_date
        ')
            ->from('helpdesk h')
            ->where('h.client_id', $client_id)
            ->where('h.is_delete', 0)
            ->where_in('h.status', [0, 1, 2, 4, 6]);

        if (!$all_time) {
            $this->db
                ->where('DATE(h.update_date) >=', $date_from)
                ->where('DATE(h.update_date) <=', $date_to);
        }

        if (!empty($subcat_names)) {
            $this->db->where_in('h.sub_category_name', $subcat_names);
        }

        $this->db->order_by('h.due_date', 'ASC');

        return $this->db->get()->result();
    }
}
