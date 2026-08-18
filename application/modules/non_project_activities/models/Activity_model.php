<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Activity_model extends BF_Model
{
    protected $table_name = 'npa_activities';
    protected $key        = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get activities for a specific user (non-deleted, ordered by date descending)
     *
     * @param int $user_id
     * @return array
     */
    public function get_activities($user_id)
    {
        $this->db->select('*');
        $this->db->from('npa_activities');
        $this->db->where('user_id', $user_id);
        $this->db->where('is_deleted', 0);
        $this->db->order_by('activity_date', 'DESC');

        return $this->db->get()->result_array();
    }

    /**
     * Get all activities (for admin view) with user name joined
     *
     * @return array
     */
    public function get_all_activities()
    {
        $this->db->select('a.*, u.nm_lengkap as user_name');
        $this->db->from('npa_activities a');
        $this->db->join('users u', 'u.id_user = a.user_id', 'left');
        $this->db->where('a.is_deleted', 0);
        $this->db->order_by('a.activity_date', 'DESC');

        return $this->db->get()->result_array();
    }

    /**
     * Get activities grouped by date for a user
     *
     * @param int $user_id
     * @return array Keyed by activity_date
     */
    public function get_activities_grouped($user_id)
    {
        $activities = $this->get_activities($user_id);
        return $this->_group_by_date($activities);
    }

    /**
     * Get all activities grouped by date (admin)
     *
     * @return array Keyed by activity_date
     */
    public function get_all_activities_grouped()
    {
        $activities = $this->get_all_activities();
        return $this->_group_by_date($activities);
    }

    /**
     * Get all activities for a user on a specific date
     *
     * @param int $user_id
     * @param string $date (Y-m-d)
     * @return array
     */
    public function get_activities_by_date($user_id, $date)
    {
        $this->db->select('*');
        $this->db->from('npa_activities');
        $this->db->where('user_id', $user_id);
        $this->db->where('activity_date', $date);
        $this->db->where('is_deleted', 0);
        $this->db->order_by('id', 'ASC');

        return $this->db->get()->result_array();
    }

    /**
     * Helper: group activities array by activity_date
     */
    private function _group_by_date($activities)
    {
        $grouped = array();
        foreach ($activities as $act) {
            $date = $act['activity_date'];
            if (!isset($grouped[$date])) {
                $grouped[$date] = array(
                    'activity_date' => $date,
                    'user_id'       => $act['user_id'],
                    'user_name'     => isset($act['user_name']) ? $act['user_name'] : '',
                    'created_at'    => $act['created_at'],
                    'items'         => array(),
                    'total_manhour' => 0,
                    'attachment_count' => 0,
                    'attachments'   => array(),
                );
            }
            $grouped[$date]['items'][] = $act;
            $grouped[$date]['total_manhour'] += (float)$act['manhour'];
            // Keep earliest created_at for deadline calc
            if ($act['created_at'] < $grouped[$date]['created_at']) {
                $grouped[$date]['created_at'] = $act['created_at'];
            }
        }
        return array_values($grouped);
    }

    /**
     * Get a single activity by ID (non-deleted only)
     *
     * @param int $id
     * @return array|null
     */
    public function get_activity_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('npa_activities');
        $this->db->where('id', $id);
        $this->db->where('is_deleted', 0);

        $result = $this->db->get()->row_array();

        return $result ? $result : null;
    }

    /**
     * Create a new activity record
     *
     * @param array $data
     * @return int Insert ID
     */
    public function create_activity($data)
    {
        $this->db->insert('npa_activities', $data);

        return $this->db->insert_id();
    }

    /**
     * Update an existing activity record
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update_activity($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('npa_activities', $data);

        return $this->db->affected_rows() > 0;
    }

    /**
     * Soft-delete an activity (set is_deleted = 1)
     * Attachments are excluded implicitly when activity is not visible
     *
     * @param int $id
     * @return bool
     */
    public function delete_activity($id)
    {
        $this->db->where('id', $id);
        $this->db->update('npa_activities', array('is_deleted' => 1));

        return $this->db->affected_rows() > 0;
    }

    /**
     * Get all attachments for a specific activity
     *
     * @param int $activity_id
     * @return array
     */
    public function get_attachments($activity_id)
    {
        $this->db->select('*');
        $this->db->from('npa_attachments');
        $this->db->where('activity_id', $activity_id);
        $this->db->order_by('id', 'ASC');

        return $this->db->get()->result_array();
    }

    /**
     * Get a single attachment by ID
     *
     * @param int $id
     * @return array|null
     */
    public function get_attachment_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('npa_attachments');
        $this->db->where('id', $id);

        $result = $this->db->get()->row_array();

        return $result ? $result : null;
    }

    /**
     * Save a new attachment record
     *
     * @param array $data
     * @return int Insert ID
     */
    public function save_attachment($data)
    {
        $this->db->insert('npa_attachments', $data);

        return $this->db->insert_id();
    }

    /**
     * Update an attachment record (catatan or file replacement)
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update_attachment($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('npa_attachments', $data);

        return $this->db->affected_rows() > 0;
    }

    /**
     * Delete an attachment record (hard delete) and return file_name_hash
     * for physical file deletion by the caller
     *
     * @param int $id
     * @return string|false file_name_hash on success, false on failure
     */
    public function delete_attachment($id)
    {
        // Get the record first to retrieve the file_name_hash
        $attachment = $this->get_attachment_by_id($id);

        if (!$attachment) {
            return false;
        }

        $file_name_hash = $attachment['file_name_hash'];

        // Hard delete the record from database
        $this->db->where('id', $id);
        $this->db->delete('npa_attachments');

        return $file_name_hash;
    }
}
