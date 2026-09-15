<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Project_report_model
 *
 * Menyediakan data untuk dashboard "Laporan & Jadwal Proyek" (per-project),
 * berbasis struktur tahapan/manhour: pm_modules, pm_module_tahapan,
 * pm_tahapan_tasks, pm_module_meetings.
 */
class Project_report_model extends BF_Model
{
    protected $table_name = 'pm_projects';
    protected $key        = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Daftar project untuk dropdown filter (tidak termasuk yang dihapus).
     * $allowed_ids: null = semua project (admin); array = batasi ke id tsb.
     */
    public function get_project_options($allowed_ids = null)
    {
        $this->db->select('id, project_code, project_name, status');
        $this->db->from('pm_projects');
        $this->db->where('deleted', 0);

        if (is_array($allowed_ids)) {
            if (empty($allowed_ids)) {
                return array(); // user tidak terlibat di project mana pun
            }
            $this->db->where_in('id', $allowed_ids);
        }

        $this->db->order_by('id', 'DESC');
        return $this->db->get()->result_array();
    }

    /**
     * Header project + nama-nama role (PM, BA, Programmer, QA).
     */
    public function get_project_header($project_id)
    {
        $this->db->select('p.*, c.name_app as client_name, u.nm_lengkap as pm_name');
        $this->db->from('pm_projects p');
        $this->db->join('helpdesk_client c', 'c.id = p.client_id', 'left');
        $this->db->join('users u', 'u.id_user = p.pm_id', 'left');
        $this->db->where('p.id', $project_id);
        $this->db->where('p.deleted', 0);
        $project = $this->db->get()->row_array();

        if ($project) {
            $project['ba_names']         = $this->get_role_names($project_id, 'ba');
            $project['programmer_names'] = $this->get_role_names($project_id, 'programmer');
            $project['qa_names']         = $this->get_role_names($project_id, 'qa');
        }

        return $project;
    }

    /**
     * Nama user per role sebagai string dipisah koma.
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
            if (!empty($r['nm_lengkap'])) $names[] = $r['nm_lengkap'];
        }
        return $names ? implode(', ', $names) : '-';
    }

    /**
     * Daftar modul + tahapan untuk sebuah project (exclude modul dihapus),
     * dengan agregasi manhour dan info PIC utama.
     */
    public function get_modules_detail($project_id)
    {
        $this->db->select('m.*');
        $this->db->from('pm_modules m');
        $this->db->where('m.project_id', $project_id);
        $this->db->where('m.is_deleted', 0);
        $this->db->order_by('m.module_order', 'ASC');
        $modules = $this->db->get()->result_array();

        foreach ($modules as &$mod) {
            // Tahapan modul
            $this->db->select('t.*, u.nm_lengkap as pic_name');
            $this->db->from('pm_module_tahapan t');
            $this->db->join('users u', 'u.id_user = t.pic_user_id', 'left');
            $this->db->where('t.module_id', $mod['id']);
            $this->db->order_by('t.tahapan_order', 'ASC');
            $tahapan = $this->db->get()->result_array();

            $total_tahapan    = count($tahapan);
            $finished_tahapan = 0;
            $plan_mh          = 0.0;
            $actual_mh        = 0.0;
            $pic_counter      = array();     // hitung PIC utama (paling banyak muncul)
            $last_due_date    = null;        // due date modul = due date tahapan terakhir

            foreach ($tahapan as $t) {
                if ($t['status'] === 'finish') $finished_tahapan++;
                $plan_mh   += (float)$t['plan_manhour'];
                $actual_mh += (float)$t['actual_manhour'];

                if (!empty($t['pic_name'])) {
                    if (!isset($pic_counter[$t['pic_name']])) $pic_counter[$t['pic_name']] = 0;
                    $pic_counter[$t['pic_name']]++;
                }
                if (!empty($t['plan_due_date'])) {
                    $last_due_date = $t['plan_due_date'];
                }
            }

            // Manhour meeting/others per modul
            $meeting_mh = $this->get_module_meeting_manhour($mod['id']);

            // PIC utama = yang paling sering jadi PIC tahapan
            $pic_utama = '-';
            if (!empty($pic_counter)) {
                arsort($pic_counter);
                $pic_utama = key($pic_counter);
            }

            $plan_total   = $plan_mh;                 // meeting tidak punya plan
            $actual_total = $actual_mh + $meeting_mh;

            $mod['tahapan']          = $tahapan;
            $mod['total_tahapan']    = $total_tahapan;
            $mod['finished_tahapan'] = $finished_tahapan;
            $mod['plan_manhour']     = $plan_total;
            $mod['actual_manhour']   = $actual_total;
            $mod['meeting_manhour']  = $meeting_mh;
            $mod['selisih_manhour']  = $actual_total - $plan_total;
            $mod['pic_utama']        = $pic_utama;
            $mod['due_date_modul']   = $last_due_date;
            $mod['progress_pct']     = ($plan_total > 0)
                ? min(100, round(($actual_total / $plan_total) * 100))
                : 0;

            // Label status modul
            if ($mod['status'] === 'finish') {
                $mod['status_label'] = 'Finish';
            } elseif ($finished_tahapan > 0 || $actual_total > 0) {
                $mod['status_label'] = 'In Progress';
            } else {
                $mod['status_label'] = 'Belum Mulai';
            }
        }
        unset($mod);

        return $modules;
    }

    /**
     * Total manhour meeting untuk sebuah modul.
     */
    public function get_module_meeting_manhour($module_id)
    {
        $this->db->select_sum('manhour');
        $this->db->where('module_id', $module_id);
        $row = $this->db->get('pm_module_meetings')->row();
        return ($row && $row->manhour) ? (float)$row->manhour : 0.0;
    }

    /**
     * Daftar aktivitas "Others" (meeting) untuk seluruh modul dalam project.
     */
    public function get_others_activities($project_id)
    {
        $this->db->select('mm.task_date, mm.task_description, mm.manhour, mm.remarks,
                           m.module_name, u.nm_lengkap as user_name');
        $this->db->from('pm_module_meetings mm');
        $this->db->join('pm_modules m', 'm.id = mm.module_id', 'left');
        $this->db->join('users u', 'u.id_user = mm.user_id', 'left');
        $this->db->where('mm.project_id', $project_id);
        $this->db->order_by('mm.task_date', 'ASC');
        $this->db->order_by('mm.id', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * Ringkasan manhour + progress level project.
     * $modules = hasil get_modules_detail() (untuk hindari query ulang).
     */
    public function get_summary($project_id, $modules = null)
    {
        if ($modules === null) {
            $modules = $this->get_modules_detail($project_id);
        }

        $plan_total   = 0.0;
        $actual_total = 0.0;
        $total_modul  = count($modules);
        $finish_modul = 0;

        foreach ($modules as $mod) {
            $plan_total   += (float)$mod['plan_manhour'];
            $actual_total += (float)$mod['actual_manhour'];
            if ($mod['status'] === 'finish') $finish_modul++;
        }

        return array(
            'plan_manhour'   => $plan_total,
            'actual_manhour' => $actual_total,
            'selisih'        => $actual_total - $plan_total,
            'total_modules'  => $total_modul,
            'finish_modules' => $finish_modul,
            'progress_pct'   => ($total_modul > 0) ? round(($finish_modul / $total_modul) * 100, 1) : 0,
        );
    }

    /**
     * Data jadwal (gantt) per modul dengan skala MINGGUAN.
     *
     * Rentang waktu = awal bulan start project s/d akhir bulan target selesai,
     * dibagi menjadi kolom minggu (7 hari) supaya lebih ringkas dibanding harian.
     * Header bulan span sesuai jumlah minggu yang jatuh di bulan tsb.
     * Posisi & lebar bar dinyatakan dalam OFFSET MINGGU + JUMLAH MINGGU.
     *
     * $modules = hasil get_modules_detail().
     */
    public function get_schedule_data($project, $modules)
    {
        $start = !empty($project['start_date']) ? strtotime($project['start_date']) : strtotime(date('Y-01-01'));
        $end   = !empty($project['end_date'])   ? strtotime($project['end_date'])   : strtotime('+2 months', $start);
        if ($end <= $start) {
            $end = strtotime('+2 months', $start);
        }

        $day  = 86400;
        $week = 7 * $day;

        // Rentang tampilan: awal bulan start s/d akhir bulan end
        $range_start = strtotime(date('Y-m-01', $start));
        $range_end   = strtotime(date('Y-m-t', $end));
        $total_days  = (int)round(($range_end - $range_start) / $day) + 1;
        $total_weeks = max(1, (int)ceil($total_days / 7));

        // Susunan bulan (header) -> berapa minggu yang "dimiliki" tiap bulan.
        // Sebuah minggu dihitung milik bulan dari tanggal awal minggu tsb.
        $months = array();
        for ($w = 0; $w < $total_weeks; $w++) {
            $wk_start = $range_start + ($w * $week);
            $ym = date('Y-m', $wk_start);
            if (!isset($months[$ym])) {
                $months[$ym] = array(
                    'label' => $this->_month_label_id($wk_start),
                    'year'  => date('Y', $wk_start),
                    'weeks' => 0,
                    'ym'    => $ym,
                );
            }
            $months[$ym]['weeks']++;
        }
        $months = array_values($months);

        // Slot durasi per modul (proksi) dari rentang aktual project
        $total_modul = max(1, count($modules));
        $slot_span   = ($end - $start) / $total_modul;

        // Lebar minimum bar (biar tidak terlalu pendek)
        $min_weeks = 3;

        $rows = array();
        $i = 0;
        foreach ($modules as $mod) {
            // Start proksi berdasar urutan modul
            $bar_start = $start + ($i * $slot_span);

            // Akhir bar = due date tahapan ke-10 (Go Live). Fallback: due date
            // tahapan terisi terakhir, lalu slot proksi.
            $due10 = $this->_get_tahapan_due_date($mod['tahapan'], 10);
            if (!empty($due10)) {
                $bar_end = strtotime($due10);
            } else {
                $bar_end = $bar_start + $slot_span;
            }

            // Jaga agar bar tidak mundur / tidak terlalu pendek
            if ($bar_end <= $bar_start) {
                $bar_end = $bar_start + ($min_weeks * $week);
            }

            $offset_weeks = max(0, (int)floor(($bar_start - $range_start) / $week));
            $len_weeks    = max($min_weeks, (int)round(($bar_end - $bar_start) / $week));
            if ($offset_weeks + $len_weeks > $total_weeks) {
                $len_weeks = max($min_weeks, $total_weeks - $offset_weeks);
            }

            $rows[] = array(
                'module_name'      => $mod['module_name'],
                'pic_utama'        => $mod['pic_utama'],
                'status'           => $mod['status'],
                'status_label'     => $mod['status_label'],
                'finished_tahapan' => $mod['finished_tahapan'],
                'total_tahapan'    => $mod['total_tahapan'],
                'progress_pct'     => $mod['progress_pct'],
                'offset_weeks'     => $offset_weeks,
                'len_weeks'        => $len_weeks,
                'start_date'       => date('Y-m-d', $bar_start),
                'end_date'         => date('Y-m-d', $bar_end),
                'due_date_10'      => !empty($due10) ? $due10 : null,
                'tahapan'          => $mod['tahapan'],
                'plan_manhour'     => $mod['plan_manhour'],
                'actual_manhour'   => $mod['actual_manhour'],
                'selisih_manhour'  => $mod['selisih_manhour'],
            );
            $i++;
        }

        // Offset minggu (pecahan) untuk garis "hari ini"
        $today         = strtotime(date('Y-m-d'));
        $today_week     = null;
        if ($today >= $range_start && $today <= $range_end) {
            $today_week = round(($today - $range_start) / $week, 2);
        }

        return array(
            'rows'         => $rows,
            'months'       => $months,
            'total_weeks'  => $total_weeks,
            'today_week'   => $today_week,
            'range_start'  => date('Y-m-d', $range_start),
            'range_end'    => date('Y-m-d', $range_end),
            'year'         => date('Y', $start),
        );
    }

    /**
     * Ambil plan_due_date pada tahapan dengan urutan tertentu (mis. 10 = Go Live).
     * Mengembalikan string tanggal (Y-m-d) atau null bila kosong/tidak ada.
     */
    private function _get_tahapan_due_date($tahapan, $order)
    {
        if (empty($tahapan) || !is_array($tahapan)) return null;
        foreach ($tahapan as $t) {
            if ((int)$t['tahapan_order'] === (int)$order) {
                $dd = isset($t['plan_due_date']) ? $t['plan_due_date'] : null;
                return (!empty($dd) && $dd !== '0000-00-00') ? $dd : null;
            }
        }
        return null;
    }

    /**
     * Label bulan singkat dalam Bahasa Indonesia.
     */
    private function _month_label_id($ts)
    {
        $map = array(1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
                     7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des');
        $m = (int)date('n', $ts);
        return $map[$m] . ' ' . date('Y', $ts);
    }
}
