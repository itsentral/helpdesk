<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends Admin_Controller
{
	/*
 * @author Yunaz
 * @copyright Copyright (c) 2016, Yunaz
 *
 */
	public function __construct()
	{
		parent::__construct();

		$this->load->model('dashboard/dashboard_model');

		$this->template->page_icon('ti ti-lock-access');
	}

	public function index()
    {
        $user_id = $this->auth->user_id();
        $clients = $this->dashboard_model->get_user_clients($user_id);
        $data = [
            'clients'       => $clients,
            'client_count'  => count($clients)
        ];

        $this->template->title('Dashboard');
        $this->template->page_icon('ti ti-lock-access');
        $this->template->render('index', $data);
    }

    public function get_dashboard_data()
    {
        $client_id = $this->input->post('client_id');
        $date_from = $this->input->post('date_from');
        $date_to   = $this->input->post('date_to');

        $result = [
            'status_data'   => $this->dashboard_model->get_status_data($client_id, $date_from, $date_to),
            'category_data' => $this->dashboard_model->get_category_data($client_id, $date_from, $date_to),
            'daily_data'    => $this->dashboard_model->get_daily_data($client_id, $date_from, $date_to),
            'total_tickets' => $this->dashboard_model->get_total_tickets($client_id, $date_from, $date_to),
        ];

        echo json_encode($result);
    }
}
