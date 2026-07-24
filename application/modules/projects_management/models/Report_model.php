<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Report_model extends BF_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_gantt_data($project_id = null)
    {
        $this->db->select('t.id, t.task_name as text, t.start_date, t.due_date, t.progress, t.status, p.project_name, u.nm_lengkap as assignee');
        $this->db->from('pm_tasks t');
        $this->db->join('pm_projects p', 'p.id = t.project_id', 'left');
        $this->db->join('users u', 'u.id_user = t.assignee_id', 'left');
        $this->db->where('t.deleted', 0);
        $this->db->where('t.start_date IS NOT NULL', NULL, FALSE);
        $this->db->where('t.due_date IS NOT NULL', NULL, FALSE);

        if (!empty($project_id)) {
            $this->db->where('t.project_id', $project_id);
        }

        $this->db->order_by('t.start_date', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_workload_report($project_id = null)
    {
        $this->db->select('u.id_user, u.nm_lengkap, u.username, 
            COUNT(t.id) as total_assigned_tasks,
            SUM(CASE WHEN t.status = "Done" THEN 1 ELSE 0 END) as completed_tasks,
            SUM(t.estimated_hours) as total_estimated_hours,
            SUM(t.actual_hours) as total_actual_hours');
        $this->db->from('users u');
        $this->db->join('pm_tasks t', 't.assignee_id = u.id_user AND t.deleted = 0', 'inner');

        if (!empty($project_id)) {
            $this->db->where('t.project_id', $project_id);
        }

        $this->db->group_by('u.id_user');
        $this->db->order_by('total_assigned_tasks', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_budget_costing_report()
    {
        $this->db->select('p.id, p.project_code, p.project_name, p.budget, p.status, c.name_app as client_name, u.nm_lengkap as pm_name');
        $this->db->from('pm_projects p');
        $this->db->join('helpdesk_client c', 'c.id = p.client_id', 'left');
        $this->db->join('users u', 'u.id_user = p.pm_id', 'left');
        $this->db->where('p.deleted', 0);
        $this->db->order_by('p.id', 'DESC');
        $projects = $this->db->get()->result_array();

        foreach ($projects as &$p) {
            $this->db->select_sum('hours_worked');
            $this->db->where('project_id', $p['id']);
            $this->db->where('approval_status', 'Approved');
            $ts_res = $this->db->get('pm_timesheets')->row();
            $p['total_logged_hours'] = $ts_res ? ($ts_res->hours_worked ?? 0) : 0;
            
            $this->db->select_sum('estimated_hours');
            $this->db->select_sum('actual_hours');
            $this->db->where('project_id', $p['id']);
            $this->db->where('deleted', 0);
            $task_res = $this->db->get('pm_tasks')->row();
            $p['total_estimated_hours'] = $task_res ? ($task_res->estimated_hours ?? 0) : 0;
            $p['total_actual_hours'] = $task_res ? ($task_res->actual_hours ?? 0) : 0;
        }

        return $projects;
    }
}
