<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Project_report
 *
 * Dashboard "Laporan & Jadwal Proyek" (per-project).
 * - index()        : tampilan dashboard (tab Laporan + tab Jadwal)
 * - print_report() : halaman print-friendly (cetak / Save as PDF via browser)
 * - print_gantt()  : halaman print khusus gantt chart jadwal
 * - export_excel() : export laporan + jadwal ke Excel (HTML table)
 */
class Project_report extends Admin_Controller
{
    protected $viewPermission = 'Projects.View';

    protected $id_user;
    protected $datetime;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'projects_management/Project_report_model',
            'projects_management/Project_model'
        ));

        date_default_timezone_set('Asia/Bangkok');
        $this->id_user  = $this->auth->user_id();
        $this->datetime = date('Y-m-d H:i:s');
    }

    /**
     * Dashboard laporan & jadwal untuk 1 project terpilih.
     * Project bisa dipilih via segment URL atau query string ?project_id=
     */
    public function index($project_id = null)
    {
        if (empty($project_id)) {
            $project_id = $this->input->get('project_id');
        }

        $this->template->title('Laporan & Jadwal Proyek');
        $this->template->page_icon('fa fa-file-text-o');

        $data = $this->_build_report_data($project_id);

        $this->template->set($data);
        $this->template->render('project_report/index');
    }

    /**
     * Kumpulkan seluruh data yang dibutuhkan view laporan & jadwal.
     */
    private function _build_report_data($project_id)
    {
        $is_admin = $this->auth->is_admin();

        // Admin: semua project. Non-admin: hanya project yang melibatkan user.
        if ($is_admin) {
            $allowed_ids = null; // null = tanpa filter
        } else {
            $allowed_ids = $this->Project_model->get_user_involved_project_ids($this->id_user);
            $allowed_ids = array_map('intval', (array)$allowed_ids);
        }

        $data = array();
        $data['is_admin'] = $is_admin;
        $data['projects'] = $this->Project_report_model->get_project_options($allowed_ids);

        // Validasi: project yang diminta harus termasuk yang boleh dilihat user
        $allowed_view = true;
        if (!empty($project_id) && $allowed_ids !== null && !in_array((int)$project_id, $allowed_ids)) {
            $allowed_view = false;
        }

        // Default: pilih project pertama jika belum ada / tidak diizinkan
        if ((empty($project_id) || !$allowed_view) && !empty($data['projects'])) {
            $project_id   = $data['projects'][0]['id'];
            $allowed_view = true;
        }
        $data['project_id'] = $project_id;

        $data['project']  = null;
        $data['modules']  = array();
        $data['others']   = array();
        $data['summary']  = null;
        $data['schedule'] = array('rows' => array(), 'months' => array(), 'total_days' => 1, 'today_offset' => null, 'year' => date('Y'));

        if (!empty($project_id) && $allowed_view) {
            $project = $this->Project_report_model->get_project_header($project_id);
            if ($project) {
                $modules           = $this->Project_report_model->get_modules_detail($project_id);
                $data['project']   = $project;
                $data['modules']   = $modules;
                $data['others']    = $this->Project_report_model->get_others_activities($project_id);
                $data['summary']   = $this->Project_report_model->get_summary($project_id, $modules);
                $data['schedule']  = $this->Project_report_model->get_schedule_data($project, $modules);
            }
        }

        $data['report_date'] = date('d M Y');

        return $data;
    }

    /**
     * Halaman print-friendly (untuk dicetak / Save as PDF via browser).
     * Menampilkan halaman HTML standalone yang otomatis memanggil window.print().
     */
    public function print_report($project_id = null)
    {
        if (empty($project_id)) {
            $project_id = $this->input->get('project_id');
        }

        $data = $this->_build_report_data($project_id);

        if (empty($data['project'])) {
            show_error('Project tidak ditemukan.', 404);
            return;
        }

        // View standalone (tidak melalui template) supaya hasil print bersih
        $this->load->view('project_report/print', $data);
    }

    /**
     * Halaman print khusus GANTT CHART jadwal (standalone, auto window.print()).
     */
    public function print_gantt($project_id = null)
    {
        if (empty($project_id)) {
            $project_id = $this->input->get('project_id');
        }

        $data = $this->_build_report_data($project_id);

        if (empty($data['project'])) {
            show_error('Project tidak ditemukan.', 404);
            return;
        }

        $this->load->view('project_report/print_gantt', $data);
    }

    /**
     * Export laporan + jadwal ke Excel (HTML table + header .xls).
     */
    public function export_excel($project_id = null)
    {
        if (empty($project_id)) {
            $project_id = $this->input->get('project_id');
        }

        $data = $this->_build_report_data($project_id);

        if (empty($data['project'])) {
            show_error('Project tidak ditemukan.', 404);
            return;
        }

        $filename = 'Laporan_Proyek_' . preg_replace('/[^A-Za-z0-9]/', '_', $data['project']['project_code']) . '_' . date('Ymd_His') . '.xls';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Pragma: no-cache');
        header('Expires: 0');

        $this->load->view('project_report/excel', $data);
    }
}
