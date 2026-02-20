<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * @author CokesHome
 * @copyright Copyright (c) 2015, CokesHome
 * 
 * This is model class for table "users"
 */

class Users_model extends BF_Model
{
    /**
     * @var string  User Table Name
     */
    protected $table_name = 'users';
    protected $key        = 'id_user';

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
    protected $set_created = TRUE;

    /**
     * @var bool Set the modified time automatically on editing a record (if true)
     */
    protected $set_modified = false;

    /**
     * @var bool Enable/Disable soft deletes.
     * If false, the delete() method will perform a delete of that row.
     * If true, the value in $deleted_field will be set to 1.
     */
    protected $soft_deletes = TRUE;

    /**
     * @var string The type of date/time field used for $created_field and $modified_field.
     * Valid values are 'int', 'datetime', 'date'.
     */
    protected $date_format = 'datetime';
    //--------------------------------------------------------------------

    /**
     * @var bool If true, will log user id in $created_by_field, $modified_by_field,
     * and $deleted_by_field.
     */
    protected $log_user = false;

    /**
     * Function construct used to load some library, do some actions, etc.
     */
    public function __construct()
    {
        parent::__construct();
    }

    // public function get_users_with_client_apps()
    // {
    //     return $this->db
    //         ->select("users.*, 
    //              GROUP_CONCAT(helpdesk_client.name_app SEPARATOR ', ') as client_apps")
    //         ->from('users')
    //         ->join('helpdesk_user_client', 'helpdesk_user_client.id_user = users.id_user', 'left')
    //         ->join(
    //             'helpdesk_client',
    //             'helpdesk_client.id = helpdesk_user_client.client_id AND helpdesk_client.is_delete = 0',
    //             'left'
    //         )
    //         ->where('users.deleted', 0)
    //         ->where('users.username !=', 'json')
    //         ->group_by('users.id_user')
    //         ->order_by('users.nm_lengkap', 'ASC')
    //         ->get()
    //         ->result_array();
    // }

    public function get_users_with_client_apps($filter_type = 'all')
    {
        $this->db->select("users.*, 
         GROUP_CONCAT(helpdesk_client.name_app SEPARATOR ', ') as client_apps")
            ->from('users')
            ->join('helpdesk_user_client', 'helpdesk_user_client.id_user = users.id_user', 'left')
            ->join(
                'helpdesk_client',
                'helpdesk_client.id = helpdesk_user_client.client_id AND helpdesk_client.is_delete = 0',
                'left'
            )
            ->where('users.deleted', 0)
            ->where('users.username !=', 'json');

        // Apply filter based on type
        if ($filter_type === 'internal') {
            $this->db->where('users.status', 0);
        } elseif ($filter_type === 'external') {
            $this->db->where('users.status', 1);
        } elseif ($filter_type === 'ba') {
            $this->db->where('users.is_ba', 1);
        }
        // 'all' tidak perlu kondisi tambahan

        return $this->db->group_by('users.id_user')
            ->order_by('users.nm_lengkap', 'ASC')
            ->get()
            ->result_array();
    }

    public function create_notification($data)
    {
        return $this->db->insert('helpdesk_notifications', $data);
    }

    public function create_bulk_notification($data_array)
    {
        return $this->db->insert_batch('helpdesk_notifications', $data_array);
    }

    public function get_notifications($user_id, $limit = 20)
    {
        return $this->db
            ->select('n.*, h.status as ticket_status')
            ->from('helpdesk_notifications n')
            ->join('helpdesk h', 'h.id = n.helpdesk_id', 'left')
            ->where('n.user_id', $user_id)
            ->order_by('n.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->result();
    }

    public function get_unread_count($user_id)
    {
        return $this->db
            ->where('user_id', $user_id)
            ->where('is_read', 0)
            ->count_all_results('helpdesk_notifications');
    }

    public function mark_as_read($id, $user_id)
    {
        return $this->db
            ->where('id', $id)
            ->where('user_id', $user_id)
            ->update('helpdesk_notifications', ['is_read' => 1]);
    }

    public function mark_all_read($user_id)
    {
        return $this->db
            ->where('user_id', $user_id)
            ->where('is_read', 0)
            ->update('helpdesk_notifications', ['is_read' => 1]);
    }
}
