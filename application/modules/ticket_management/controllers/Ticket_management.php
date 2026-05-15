<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ticket_management extends Admin_Controller
{
  //Permission
  protected $viewPermission   = 'Ticket_Management.View';
  protected $addPermission    = 'Ticket_Management.Add';
  protected $managePermission = 'Ticket_Management.Manage';
  protected $deletePermission = 'Ticket_Management.Delete';

  protected $id_user;
  protected $datetime;

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array(
      'Ticket_management/Ticket_management_model'
    ));
    $this->template->title('Ticket Management');
    $this->template->page_icon('fa fa-building-o');

    date_default_timezone_set('Asia/Bangkok');

    $this->id_user  = $this->auth->user_id();
    $this->datetime = date('Y-m-d H:i:s');
  }

  public function index()
  {
    $this->auth->restrict($this->viewPermission);
    $this->template->title('Ticket Management');
    $this->template->page_icon('fa fa-table');
    $this->template->render('index');
  }

  public function get_list_programmer()
  {
    $this->auth->restrict($this->viewPermission);

    $sort = $this->input->get('sort'); // 'due_date' atau default
    $ticket = $this->Ticket_management_model->get_list_programmer();

    if ($sort === 'due_date') {
      $ticket = $this->Ticket_management_model->sort_by_due_date($ticket);
    }

    $data['ticket']      = $ticket;
    $data['active_sort'] = $sort;
    $data['user']        = $this->session->userdata('app_session');

    $this->template->render('table/list_ticket_programmer', $data);
  }

  public function get_list_ba()
  {
    $this->auth->restrict($this->viewPermission);

    $sort = $this->input->get('sort');
    $ticket = $this->Ticket_management_model->get_list_ba();

    if ($sort === 'due_date') {
      $ticket = $this->Ticket_management_model->sort_by_due_date($ticket);
    }

    $data['ticket']      = $ticket;
    $data['active_sort'] = $sort;
    $data['user']        = $this->session->userdata('app_session');

    $this->template->render('table/list_ticket_ba', $data);
  }

  public function update_order()
  {
    $this->auth->restrict($this->managePermission);

    $type   = $this->input->post('type');   // 'programmer' atau 'ba'
    $orders = $this->input->post('orders'); // array of {id, order}

    $field = $type === 'ba' ? 'order_ba' : 'order_programmer';

    $result = $this->Ticket_management_model->update_order($orders, $field);

    echo json_encode(['success' => $result]);
  }
}
