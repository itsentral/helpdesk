<?php defined('BASEPATH') or exit('No direct script access allowed');

class Reports extends Admin_Controller
{
    protected $id_user;
    protected $datetime;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'projects_management/Project_model',
            'projects_management/Report_model'
        ));

        date_default_timezone_set('Asia/Bangkok');
        $this->id_user  = $this->auth->user_id();
        $this->datetime = date('Y-m-d H:i:s');
    }

    public function index()
    {
        $this->template->title('Reports & Analytics');
        $this->template->page_icon('fa fa-bar-chart');

        $project_id         = $this->input->get('project_id');
        $data['project_id'] = $project_id;
        $data['projects']   = $this->Project_model->get_projects();
        $data['gantt_data'] = $this->Report_model->get_gantt_data($project_id);
        $data['workload']   = $this->Report_model->get_workload_report($project_id);
        $data['budget']     = $this->Report_model->get_budget_costing_report();

        $this->template->set($data);
        $this->template->render('reports/index');
    }

    public function gantt()
    {
        $this->template->title('Gantt Chart Timeline');
        $this->template->page_icon('fa fa-align-left');

        $project_id         = $this->input->get('project_id');
        $data['project_id'] = $project_id;
        $data['projects']   = $this->Project_model->get_projects();
        $data['tasks']      = $this->Report_model->get_gantt_data($project_id);

        $this->template->set($data);
        $this->template->render('reports/gantt');
    }

    public function workload()
    {
        $this->template->title('Resource Workload Report');
        $this->template->page_icon('fa fa-users');

        $project_id         = $this->input->get('project_id');
        $data['project_id'] = $project_id;
        $data['projects']   = $this->Project_model->get_projects();
        $data['workload']   = $this->Report_model->get_workload_report($project_id);

        $this->template->set($data);
        $this->template->render('reports/workload');
    }

    public function budget()
    {
        $this->template->title('Project Costing vs Budget');
        $this->template->page_icon('fa fa-money');

        $data['projects']   = $this->Report_model->get_budget_costing_report();

        $this->template->set($data);
        $this->template->render('reports/budget');
    }

    public function export_excel()
    {
        $report_type = $this->input->get('type');
        $project_id  = $this->input->get('project_id');

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Report_Project_" . date('Ymd_His') . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        if ($report_type === 'workload') {
            $data['workload'] = $this->Report_model->get_workload_report($project_id);
            $this->load->view('reports/excel_workload', $data);
        } else {
            $data['projects'] = $this->Report_model->get_budget_costing_report();
            $this->load->view('reports/excel_budget', $data);
        }
    }
}
