<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Project_model extends BF_Model
{
    protected $table_name = 'pm_projects';
    protected $key        = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    public function generate_project_code()
    {
        $year = date('Y');
        $prefix = 'PRJ-' . $year . '-';

        $this->db->select('project_code');
        $this->db->like('project_code', $prefix, 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('pm_projects');

        if ($query->num_rows() > 0) {
            $last_code = $query->row()->project_code;
            $num = (int) substr($last_code, strrpos($last_code, '-') + 1);
            $next_num = sprintf('%03d', $num + 1);
        } else {
            $next_num = '001';
        }

        return $prefix . $next_num;
    }

    public function get_projects($status = null, $client_id = null, $user_id = null)
    {
        $this->db->select('p.*, c.name_app as client_name, u.nm_lengkap as pm_name, cb.nm_lengkap as creator_name');
        $this->db->from('pm_projects p');
        $this->db->join('helpdesk_client c', 'c.id = p.client_id', 'left');
        $this->db->join('users u', 'u.id_user = p.pm_id', 'left');
        $this->db->join('users cb', 'cb.id_user = p.created_by', 'left');
        $this->db->where('p.deleted', 0);

        if (!empty($status)) {
            $this->db->where('p.status', $status);
        }
        if (!empty($client_id)) {
            $this->db->where('p.client_id', $client_id);
        }
        if (!empty($user_id)) {
            $this->db->group_start();
            $this->db->where('p.pm_id', $user_id);
            $this->db->or_where('p.id IN (SELECT project_id FROM pm_project_members WHERE user_id = ' . (int)$user_id . ')', NULL, FALSE);
            $this->db->or_where('p.id IN (SELECT project_id FROM pm_project_roles WHERE user_id = ' . (int)$user_id . ')', NULL, FALSE);
            $this->db->group_end();
        }

        $this->db->order_by('p.id', 'DESC');
        $projects = $this->db->get()->result_array();

        foreach ($projects as &$p) {
            $p['total_tasks'] = $this->db->where('project_id', $p['id'])->where('deleted', 0)->count_all_results('pm_tasks');
            $p['completed_tasks'] = $this->db->where('project_id', $p['id'])->where('status', 'Done')->where('deleted', 0)->count_all_results('pm_tasks');
            $p['progress'] = ($p['total_tasks'] > 0) ? round(($p['completed_tasks'] / $p['total_tasks']) * 100) : 0;
            $p['members_count'] = $this->db->where('project_id', $p['id'])->count_all_results('pm_project_members');

            // Modul count (exclude deleted)
            $p['total_modules'] = $this->db->where('project_id', $p['id'])->where('is_deleted', 0)->count_all_results('pm_modules');
            $p['finished_modules'] = $this->db->where('project_id', $p['id'])->where('status', 'finish')->where('is_deleted', 0)->count_all_results('pm_modules');

            // Get role names (multi-user)
            $p['ba_names'] = $this->get_role_names($p['id'], 'ba');
            $p['programmer_names'] = $this->get_role_names($p['id'], 'programmer');
            $p['qa_names'] = $this->get_role_names($p['id'], 'qa');
        }

        return $projects;
    }

    /**
     * Get role user names as comma-separated string
     */
    public function get_role_names($project_id, $role)
    {
        $this->db->select('u.nm_lengkap');
        $this->db->from('pm_project_roles pr');
        $this->db->join('users u', 'u.id_user = pr.user_id', 'left');
        $this->db->where('pr.project_id', $project_id);
        $this->db->where('pr.role', $role);
        $result = $this->db->get()->result_array();

        $names = array();
        foreach ($result as $r) {
            if ($r['nm_lengkap']) $names[] = $r['nm_lengkap'];
        }
        return implode(', ', $names);
    }

    /**
     * Get role user IDs as array
     */
    public function get_role_user_ids($project_id, $role)
    {
        $this->db->select('pr.id as role_id, pr.user_id, u.nm_lengkap');
        $this->db->from('pm_project_roles pr');
        $this->db->join('users u', 'u.id_user = pr.user_id', 'left');
        $this->db->where('pr.project_id', $project_id);
        $this->db->where('pr.role', $role);
        return $this->db->get()->result_array();
    }

    public function get_project_by_id($id)
    {
        $this->db->select('p.*, c.name_app as client_name, u.nm_lengkap as pm_name');
        $this->db->from('pm_projects p');
        $this->db->join('helpdesk_client c', 'c.id = p.client_id', 'left');
        $this->db->join('users u', 'u.id_user = p.pm_id', 'left');
        $this->db->where('p.id', $id);
        $this->db->where('p.deleted', 0);
        $project = $this->db->get()->row_array();

        if ($project) {
            $total_tasks = $this->db->where('project_id', $id)->where('deleted', 0)->count_all_results('pm_tasks');
            $completed_tasks = $this->db->where('project_id', $id)->where('status', 'Done')->where('deleted', 0)->count_all_results('pm_tasks');
            $project['total_tasks'] = $total_tasks;
            $project['completed_tasks'] = $completed_tasks;
            $project['progress'] = ($total_tasks > 0) ? round(($completed_tasks / $total_tasks) * 100) : 0;

            // Role names
            $project['ba_names'] = $this->get_role_names($id, 'ba');
            $project['programmer_names'] = $this->get_role_names($id, 'programmer');
            $project['qa_names'] = $this->get_role_names($id, 'qa');
        }

        return $project;
    }

    public function get_project_members($project_id)
    {
        $this->db->select('pm.*, u.nm_lengkap, u.username, u.email, u.photo');
        $this->db->from('pm_project_members pm');
        $this->db->join('users u', 'u.id_user = pm.user_id', 'left');
        $this->db->where('pm.project_id', $project_id);
        return $this->db->get()->result_array();
    }

    public function get_kpi_summary()
    {
        $total = $this->db->where('deleted', 0)->count_all_results('pm_projects');
        $in_progress = $this->db->where('deleted', 0)->where('status', 'In Progress')->count_all_results('pm_projects');
        $completed = $this->db->where('deleted', 0)->where('status', 'Completed')->count_all_results('pm_projects');
        $planning = $this->db->where('deleted', 0)->where('status', 'Planning')->count_all_results('pm_projects');
        $on_hold = $this->db->where('deleted', 0)->where('status', 'On Hold')->count_all_results('pm_projects');
        
        $today = date('Y-m-d');
        $delay = $this->db->where('deleted', 0)
            ->where_in('status', array('Planning', 'In Progress'))
            ->where('end_date <', $today)
            ->count_all_results('pm_projects');

        return array(
            'total' => $total,
            'in_progress' => $in_progress,
            'completed' => $completed,
            'planning' => $planning,
            'on_hold' => $on_hold,
            'delay' => $delay
        );
    }
}
