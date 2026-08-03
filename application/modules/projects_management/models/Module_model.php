<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Module_model extends BF_Model
{
    protected $table_name = 'pm_modules';
    protected $key        = 'id';

    // 12 Fixed tahapan sesuai konsep Excel
    // DEPRECATED: Sekarang ditarik dari tabel pm_master_tahapan
    public static $fixed_tahapan = array();

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get master tahapan dari database (menggantikan hardcode)
     */
    public function get_master_tahapan()
    {
        $this->db->where('is_active', 1);
        $this->db->where('is_deleted', 0);
        $this->db->order_by('tahapan_order', 'ASC');
        $result = $this->db->get('pm_master_tahapan')->result_array();

        // Format ke struktur yang sama dengan fixed_tahapan lama
        $tahapan = array();
        foreach ($result as $r) {
            $tahapan[] = array(
                'order' => (int)$r['tahapan_order'],
                'name'  => $r['tahapan_name'],
                'role'  => $r['default_role']
            );
        }
        return $tahapan;
    }

    /**
     * Get modules by project
     */
    public function get_modules($project_id)
    {
        $this->db->where('project_id', $project_id);
        $this->db->where('is_deleted', 0);
        $this->db->order_by('module_order', 'ASC');
        return $this->db->get('pm_modules')->result_array();
    }

    /**
     * Get single module
     */
    public function get_module_by_id($id)
    {
        return $this->db->get_where('pm_modules', array('id' => $id))->row_array();
    }

    /**
     * Get modules with tahapan for update/detail view
     */
    public function get_modules_with_tahapan($project_id)
    {
        $modules = $this->get_modules($project_id);

        foreach ($modules as &$mod) {
            $this->db->select('t.*, u.nm_lengkap as pic_name');
            $this->db->from('pm_module_tahapan t');
            $this->db->join('users u', 'u.id_user = t.pic_user_id', 'left');
            $this->db->where('t.module_id', $mod['id']);
            $this->db->order_by('t.tahapan_order', 'ASC');
            $mod['tahapan'] = $this->db->get()->result_array();

            // Calculate totals
            $total = count($mod['tahapan']);
            $finished = 0;
            foreach ($mod['tahapan'] as $t) {
                if ($t['status'] === 'finish') $finished++;
            }
            $mod['total_tahapan'] = $total;
            $mod['finished_tahapan'] = $finished;
            $mod['all_finished'] = ($total > 0 && $finished >= $total);

            // Check if module has any finished tahapan
            $finished_count = $this->db->where('module_id', $mod['id'])->where('status', 'finish')->count_all_results('pm_module_tahapan');
            $mod['has_finished_tahapan'] = ($finished_count > 0);

            // Get meetings
            $mod['meetings'] = $this->get_module_meetings($mod['id']);
            $mod['meeting_manhour'] = $this->get_module_meeting_manhour($mod['id']);

            // Get rollback history
            $mod['rollback_history'] = $this->get_rollback_history($mod['id']);
        }

        return $modules;
    }

    /**
     * Get single tahapan by id
     */
    public function get_tahapan_by_id($id)
    {
        $this->db->select('t.*, u.nm_lengkap as pic_name, m.module_name, m.project_id');
        $this->db->from('pm_module_tahapan t');
        $this->db->join('users u', 'u.id_user = t.pic_user_id', 'left');
        $this->db->join('pm_modules m', 'm.id = t.module_id', 'left');
        $this->db->where('t.id', $id);
        return $this->db->get()->row_array();
    }

    /**
     * Get tasks/pekerjaan for a tahapan
     */
    public function get_tahapan_tasks($tahapan_id)
    {
        $this->db->select('tt.*, u.nm_lengkap as user_name');
        $this->db->from('pm_tahapan_tasks tt');
        $this->db->join('users u', 'u.id_user = tt.user_id', 'left');
        $this->db->where('tt.tahapan_id', $tahapan_id);
        $this->db->order_by('tt.task_date', 'ASC');
        $this->db->order_by('tt.id', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * Get total actual manhour for a tahapan
     */
    public function get_tahapan_actual_manhour($tahapan_id)
    {
        $this->db->select_sum('manhour');
        $this->db->where('tahapan_id', $tahapan_id);
        $result = $this->db->get('pm_tahapan_tasks')->row();
        return $result && $result->manhour ? (float)$result->manhour : 0;
    }

    /**
     * Insert module + generate 12 tahapan
     * $pic_data = array of ['tahapan_order' => user_id, ...]
     * $plan_data = array of ['tahapan_order' => ['manhour' => x, 'due_date' => y], ...]
     */
    public function create_module_with_tahapan($project_id, $module_name, $module_order, $pic_data = array(), $plan_data = array())
    {
        $datetime = date('Y-m-d H:i:s');

        // Insert module
        $this->db->insert('pm_modules', array(
            'project_id'   => $project_id,
            'module_name'  => $module_name,
            'module_order' => $module_order,
            'status'       => 'progress',
            'created_at'   => $datetime
        ));
        $module_id = $this->db->insert_id();

        // Insert tahapan dari master
        $master_tahapan = $this->get_master_tahapan();
        foreach ($master_tahapan as $step) {
            $order = $step['order'];
            $pic_user_id = isset($pic_data[$order]) ? $pic_data[$order] : NULL;
            $manhour = isset($plan_data[$order]['manhour']) ? (float)$plan_data[$order]['manhour'] : 0;
            $due_date = isset($plan_data[$order]['due_date']) && !empty($plan_data[$order]['due_date']) ? $plan_data[$order]['due_date'] : NULL;

            // Tahapan pertama = active, sisanya locked
            $status = ($order === 1) ? 'active' : 'locked';

            $this->db->insert('pm_module_tahapan', array(
                'module_id'      => $module_id,
                'project_id'     => $project_id,
                'tahapan_order'  => $order,
                'tahapan_name'   => $step['name'],
                'tahapan_role'   => $step['role'],
                'pic_user_id'    => $pic_user_id,
                'plan_manhour'   => $manhour,
                'plan_due_date'  => $due_date,
                'actual_manhour' => 0,
                'status'         => $status,
                'is_plan_locked' => 1,
                'created_at'     => $datetime
            ));
        }

        return $module_id;
    }

    /**
     * Finish tahapan -> activate next tahapan -> update project status
     */
    public function finish_tahapan($tahapan_id)
    {
        $tahapan = $this->get_tahapan_by_id($tahapan_id);
        if (!$tahapan) return false;

        $datetime = date('Y-m-d H:i:s');

        // Update current tahapan to finish
        $this->db->where('id', $tahapan_id);
        $this->db->update('pm_module_tahapan', array(
            'status'             => 'finish',
            'actual_finish_date' => date('Y-m-d'),
            'updated_at'         => $datetime
        ));

        // Activate next tahapan (tahapan_order + 1) if exists
        $next_order = $tahapan['tahapan_order'] + 1;
        $this->db->where('module_id', $tahapan['module_id']);
        $this->db->where('tahapan_order', $next_order);
        $next = $this->db->get('pm_module_tahapan')->row_array();

        if ($next && $next['status'] === 'locked') {
            $this->db->where('id', $next['id']);
            $this->db->update('pm_module_tahapan', array(
                'status'     => 'active',
                'updated_at' => $datetime
            ));
        }

        // Auto-update project status
        $this->update_project_status($tahapan['project_id']);

        return true;
    }

    /**
     * Finish module -> update project status
     */
    public function finish_module($module_id)
    {
        $this->db->where('id', $module_id);
        $this->db->update('pm_modules', array(
            'status'      => 'finish',
            'finished_at' => date('Y-m-d H:i:s')
        ));

        // Update project counters
        $module = $this->get_module_by_id($module_id);
        if ($module) {
            $finished_count = $this->db->where('project_id', $module['project_id'])
                ->where('status', 'finish')
                ->where('is_deleted', 0)
                ->count_all_results('pm_modules');
            $total_count = $this->db->where('project_id', $module['project_id'])
                ->where('is_deleted', 0)
                ->count_all_results('pm_modules');

            $this->db->where('id', $module['project_id']);
            $this->db->update('pm_projects', array(
                'finished_modules' => $finished_count,
                'total_modules'    => $total_count
            ));

            // Auto-update project status
            $this->update_project_status($module['project_id']);
        }

        return true;
    }

    /**
     * Auto-update project status based on progress:
     * - Planning: Belum ada tahapan yang finish
     * - In Progress: Minimal 1 tahapan finish, tapi belum semua modul finish
     * - Completed: Semua modul sudah finish
     */
    public function update_project_status($project_id)
    {
        // Cek apakah project di-hold manual atau sudah completed (jangan override)
        $project = $this->db->get_where('pm_projects', array('id' => $project_id))->row_array();
        if (!$project || $project['status'] === 'On Hold' || $project['status'] === 'Completed') {
            return;
        }

        $finished_tahapan = $this->db->where('project_id', $project_id)->where('status', 'finish')->count_all_results('pm_module_tahapan');

        $new_status = 'Planning';

        if ($finished_tahapan > 0) {
            $new_status = 'In Progress';
        }

        $this->db->where('id', $project_id);
        $this->db->update('pm_projects', array('status' => $new_status));
    }

    /**
     * Save task/pekerjaan ke tahapan (with version)
     */
    public function save_tahapan_task($data)
    {
        // Auto-set version dari current_version tahapan
        if (!isset($data['version']) && isset($data['tahapan_id'])) {
            $tahapan = $this->db->get_where('pm_module_tahapan', array('id' => $data['tahapan_id']))->row_array();
            $data['version'] = $tahapan ? (int)$tahapan['current_version'] : 1;
        }

        $this->db->insert('pm_tahapan_tasks', $data);
        $id = $this->db->insert_id();

        // Update actual_manhour on tahapan
        $total = $this->get_tahapan_actual_manhour($data['tahapan_id']);
        $this->db->where('id', $data['tahapan_id']);
        $this->db->update('pm_module_tahapan', array(
            'actual_manhour' => $total,
            'updated_at'     => date('Y-m-d H:i:s')
        ));

        // Auto-update project status
        if (!empty($data['project_id'])) {
            $this->update_project_status($data['project_id']);
        }

        return $id;
    }

    /**
     * Rollback tahapan ke step tertentu
     * - Step yang dipilih jadi active, current_version increment
     * - Semua step setelahnya jadi locked, current_version increment
     */
    public function rollback_to_tahapan($module_id, $target_order, $from_order, $reason, $user_id)
    {
        $datetime = date('Y-m-d H:i:s');
        $module = $this->get_module_by_id($module_id);
        if (!$module) return false;

        // Get all tahapan for this module
        $this->db->where('module_id', $module_id);
        $this->db->where('tahapan_order >=', $target_order);
        $this->db->order_by('tahapan_order', 'ASC');
        $tahapan_to_reset = $this->db->get('pm_module_tahapan')->result_array();

        foreach ($tahapan_to_reset as $t) {
            $new_version = (int)$t['current_version'] + 1;
            $new_status = ($t['tahapan_order'] == $target_order) ? 'active' : 'locked';

            $this->db->where('id', $t['id']);
            $this->db->update('pm_module_tahapan', array(
                'status'             => $new_status,
                'current_version'    => $new_version,
                'actual_finish_date' => NULL,
                'updated_at'         => $datetime
            ));
        }

        // Log rollback
        $this->db->insert('pm_rollback_history', array(
            'module_id'              => $module_id,
            'project_id'             => $module['project_id'],
            'rolled_back_from_order' => $from_order,
            'rolled_back_to_order'   => $target_order,
            'reason'                 => $reason,
            'rolled_back_by'         => $user_id,
            'created_at'             => $datetime
        ));

        // Update project status
        $this->update_project_status($module['project_id']);

        return true;
    }

    /**
     * Get tahapan tasks grouped by version
     */
    public function get_tahapan_tasks_by_version($tahapan_id)
    {
        $tasks = $this->get_tahapan_tasks($tahapan_id);

        $grouped = array();
        foreach ($tasks as $t) {
            $v = isset($t['version']) ? (int)$t['version'] : 1;
            $grouped[$v][] = $t;
        }

        return $grouped;
    }

    /**
     * Get rollback history for a module
     */
    public function get_rollback_history($module_id)
    {
        $this->db->select('r.*, u.nm_lengkap as user_name, t_to.tahapan_name as to_tahapan_name, t_from.tahapan_name as from_tahapan_name');
        $this->db->from('pm_rollback_history r');
        $this->db->join('users u', 'u.id_user = r.rolled_back_by', 'left');
        $this->db->join('pm_module_tahapan t_to', 't_to.module_id = r.module_id AND t_to.tahapan_order = r.rolled_back_to_order', 'left');
        $this->db->join('pm_module_tahapan t_from', 't_from.module_id = r.module_id AND t_from.tahapan_order = r.rolled_back_from_order', 'left');
        $this->db->where('r.module_id', $module_id);
        $this->db->order_by('r.created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    /**
     * Get rollback history relevant to a specific tahapan (all rollbacks targeting this order or above)
     */
    public function get_rollback_history_for_tahapan($module_id, $tahapan_order)
    {
        $this->db->select('r.*, u.nm_lengkap as user_name');
        $this->db->from('pm_rollback_history r');
        $this->db->join('users u', 'u.id_user = r.rolled_back_by', 'left');
        $this->db->where('r.module_id', $module_id);
        $this->db->where('r.rolled_back_to_order <=', $tahapan_order);
        $this->db->order_by('r.created_at', 'ASC');
        return $this->db->get()->result_array();
    }

    // ========== MEETING / OTHERS ==========

    /**
     * Get meetings for a module
     */
    public function get_module_meetings($module_id)
    {
        $this->db->select('m.*, u.nm_lengkap as user_name');
        $this->db->from('pm_module_meetings m');
        $this->db->join('users u', 'u.id_user = m.user_id', 'left');
        $this->db->where('m.module_id', $module_id);
        $this->db->order_by('m.task_date', 'ASC');
        $this->db->order_by('m.id', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * Get total meeting manhour for a module
     */
    public function get_module_meeting_manhour($module_id)
    {
        $this->db->select_sum('manhour');
        $this->db->where('module_id', $module_id);
        $result = $this->db->get('pm_module_meetings')->row();
        return $result && $result->manhour ? (float)$result->manhour : 0;
    }

    /**
     * Save meeting entry
     */
    public function save_meeting($data)
    {
        $this->db->insert('pm_module_meetings', $data);
        return $this->db->insert_id();
    }
}
