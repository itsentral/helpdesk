<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Productivity_report extends Admin_Controller
{
    protected $viewPermission   = 'Productivity_report.View';
    protected $addPermission    = 'Productivity_report.Add';
    protected $managePermission = 'Productivity_report.Manage';
    protected $deletePermission = 'Productivity_report.Delete';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('productivity_report/productivity_report_model');
        $this->template->page_icon('ti ti-chart-bar');
    }

    // ── Page utama ────────────────────────────────────────────────────────────
    public function index()
    {
        $data = [
            'clients'    => $this->productivity_report_model->get_clients(),
            'categories' => $this->productivity_report_model->get_categories(),
        ];

        $this->template->title('Productivity Report');
        $this->template->render('index', $data);
    }

    // ── AJAX JSON: summary cards + data rows ──────────────────────────────────
    public function get_productivity_data()
    {
        $date_from   = $this->input->post('date_from');
        $date_to     = $this->input->post('date_to');
        $client_id   = $this->input->post('client_id');
        $category_id = $this->input->post('category_id');

        if (empty($date_from) || empty($date_to)) {
            return $this->_json_error('Date range is required');
        }

        $programmers = $this->productivity_report_model->get_programmer_productivity(
            $date_from,
            $date_to,
            $client_id,
            $category_id
        );
        $bas = $this->productivity_report_model->get_ba_productivity(
            $date_from,
            $date_to,
            $client_id,
            $category_id
        );

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status'             => 'success',
                'programmers'        => $programmers,
                'bas'                => $bas,
                'programmer_summary' => $this->_calculate_summary($programmers),
                'ba_summary'         => $this->_calculate_summary($bas),
            ]));
    }

    // ── AJAX HTML: partial table programmer ───────────────────────────────────
    public function get_list_programmer()
    {
        $date_from   = $this->input->post('date_from');
        $date_to     = $this->input->post('date_to');
        $client_id   = $this->input->post('client_id');
        $category_id = $this->input->post('category_id');

        $data['rows'] = $this->productivity_report_model->get_programmer_productivity(
            $date_from,
            $date_to,
            $client_id,
            $category_id
        );
        $data['summary'] = $this->_calculate_summary($data['rows']);
        $data['role']    = 'programmer';
        $data['label']   = 'Programmer';

        $this->template->render('table/list_programmer', $data);
    }

    // ── AJAX HTML: partial table BA ───────────────────────────────────────────
    public function get_list_ba()
    {
        $date_from   = $this->input->post('date_from');
        $date_to     = $this->input->post('date_to');
        $client_id   = $this->input->post('client_id');
        $category_id = $this->input->post('category_id');

        $data['rows'] = $this->productivity_report_model->get_ba_productivity(
            $date_from,
            $date_to,
            $client_id,
            $category_id
        );
        $data['summary'] = $this->_calculate_summary($data['rows']);
        $data['role']    = 'ba';
        $data['label']   = 'Business Analyst';

        $this->template->render('table/list_ba', $data);
    }

    // ── AJAX HTML: partial drill-down detail ──────────────────────────────────
    public function get_ticket_detail()
    {
        $user_id     = $this->input->post('user_id');
        $role        = $this->input->post('role');
        $date_from   = $this->input->post('date_from');
        $date_to     = $this->input->post('date_to');
        $client_id   = $this->input->post('client_id');
        $category_id = $this->input->post('category_id');

        $data['tickets'] = $this->productivity_report_model->get_ticket_detail(
            $user_id,
            $role,
            $date_from,
            $date_to,
            $client_id,
            $category_id
        );

        // Render sebagai HTML partial (bukan JSON)
        $this->load->view('productivity_report/table/detail_ticket', $data);
    }

    // ─── Private Helpers ──────────────────────────────────────────────────────

    private function _calculate_summary(array $rows): array
    {
        $summary = [
            'total_ticket'          => 0,
            'total_man_hour_plan'   => 0,
            'total_man_hour_actual' => 0,
            'total_done'            => 0,
            'total_close'           => 0,
            'total_open'            => 0,
            'total_process'         => 0,
            'total_pending'         => 0,
            'total_revisi'          => 0,
            'mh_plan_completed'     => 0,
            'mh_actual_completed'   => 0,
        ];

        foreach ($rows as $row) {
            $summary['total_ticket']          += (int)$row->total_ticket;
            $summary['total_man_hour_plan']   += (float)$row->total_man_hour_plan;
            $summary['total_man_hour_actual'] += (float)$row->total_man_hour_actual;
            $summary['total_done']            += (int)$row->total_done;
            $summary['total_close']           += (int)$row->total_close;
            $summary['total_open']            += (int)$row->total_open;
            $summary['total_process']         += (int)$row->total_process;
            $summary['total_pending']         += (int)$row->total_pending;
            $summary['total_revisi']          += (int)$row->total_revisi;
            $summary['mh_plan_completed']     += (float)$row->mh_plan_completed;
            $summary['mh_actual_completed']   += (float)$row->mh_actual_completed;
        }

        return $summary;
    }

    private function _json_error(string $message): void
    {
        echo json_encode(['status' => 'error', 'message' => $message]);
    }
}
