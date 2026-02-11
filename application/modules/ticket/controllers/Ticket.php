<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ticket extends Admin_Controller
{
  //Permission
  protected $viewPermission   = 'Ticket.View';
  protected $addPermission    = 'Ticket.Add';
  protected $managePermission = 'Ticket.Manage';
  protected $deletePermission = 'Ticket.Delete';

  protected $id_user;
  protected $datetime;

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array(
      'Ticket/Ticket_model'
    ));
    $this->template->title('Manage Ticket');
    $this->template->page_icon('fa fa-building-o');

    date_default_timezone_set('Asia/Bangkok');

    $this->id_user  = $this->auth->user_id();
    $this->datetime = date('Y-m-d H:i:s');
  }

  public function index()
  {
    $this->auth->restrict($this->viewPermission);
    $this->template->title('Manage Helpdesk');
    $this->template->page_icon('fa fa-table');
    $this->template->render('index');
  }

  public function get_list_ticket()
  {
    $this->auth->restrict($this->viewPermission);

    $user_id = $this->auth->user_id();
    $helpdesk = $this->Ticket_model->get_all_ticket();
    $unread_counts = $this->Ticket_model->get_all_unread_counts($user_id);

    $data['helpdesk'] = $helpdesk;
    $data['unread_counts'] = $unread_counts;

    $this->template->render('table/list_helpdesk', $data);
  }

  public function get_list_approved()
  {
    $this->auth->restrict($this->viewPermission);
    $helpdesk = $this->Ticket_model->get_approved_ticket();
    $data['helpdesk'] = $helpdesk;
    $this->template->render('table/list_approved', $data);
  }

  public function get_list_cancel()
  {
    $this->auth->restrict($this->viewPermission);
    $helpdesk = $this->Ticket_model->get_cancel_ticket();
    $data['helpdesk'] = $helpdesk;
    $this->template->render('table/list_cancel', $data);
  }

  // TICKET FUNCTION
  public function add_ticket()
  {
    $this->auth->restrict($this->addPermission);

    $current_user_id = $this->auth->user_id();
    $current_user = $this->Ticket_model->get_user_by_id($current_user_id);
    $user_clients = $this->Ticket_model->get_user_clients($current_user_id);

    $data = [
      'categories' => $this->Ticket_model->get_categories(),
      'sub_categories' => [],
      'users' => $this->Ticket_model->get_users(),
      'clients' => $user_clients,
      'helpdesk' => null,
      'attachments' => [],
      'is_external' => ($current_user && $current_user->status == 1),
      'view_mode' => false
    ];

    $this->template->title('Add Helpdesk Ticket');
    $this->template->page_icon('fa-solid fa-plus');
    $this->template->render('form_ticket', $data);
  }

  public function edit_ticket($id)
  {
    $this->auth->restrict($this->managePermission);

    $helpdesk = $this->Ticket_model->get_ticket_by_id($id);

    if (!$helpdesk) {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">Data tidak ditemukan</div>');
      redirect('ticket');
    }

    $current_user_id = $this->auth->user_id();
    $current_user = $this->Ticket_model->get_user_by_id($current_user_id);
    $user_clients = $this->Ticket_model->get_user_clients($current_user_id);
    $attachments = $this->Ticket_model->get_attachments_by_helpdesk($id);
    $sub_categories = [];
    if (!empty($helpdesk->category_id)) {
      $sub_categories = $this->Ticket_model->get_sub_categories($helpdesk->category_id);
    }

    $data = [
      'categories' => $this->Ticket_model->get_categories(),
      'sub_categories' => $sub_categories,
      'users' => $this->Ticket_model->get_users(),
      'clients' => $user_clients,
      'helpdesk' => $helpdesk,
      'attachments' => $attachments,
      'is_external' => ($current_user && $current_user->status == 1),
      'view_mode' => false
    ];

    $this->template->title('Edit Helpdesk Ticket');
    $this->template->page_icon('fa-solid fa-pen-to-square');
    $this->template->render('form_ticket', $data);
  }

  public function view_ticket($id)
  {
    $this->auth->restrict($this->viewPermission);

    $helpdesk = $this->Ticket_model->get_ticket_detail($id);
    $attachments = $this->Ticket_model->get_attachments_by_helpdesk($id);

    if (!$helpdesk) {
      $this->session->set_flashdata('message', '<div class="alert alert-danger">Data tidak ditemukan</div>');
      redirect('ticket');
    }


    $data = [
      'categories' => $this->Ticket_model->get_categories(),
      'sub_categories' => [],
      'users' => $this->Ticket_model->get_users(),
      'clients' => [],
      'helpdesk' => $helpdesk,
      'attachments' => $attachments,
      'view_mode' => true
    ];

    $this->template->title('View Helpdesk Ticket');
    $this->template->page_icon('fa-solid fa-eye');
    $this->template->render('form_ticket', $data);
  }

  public function get_pic_by_client()
  {
    $client_id = $this->input->post('client_id');

    if (empty($client_id)) {
      echo json_encode([
        'status' => 0,
        'message' => 'Client ID tidak valid',
        'data' => []
      ]);
      return;
    }

    $users = $this->Ticket_model->get_users_by_client($client_id);

    echo json_encode([
      'status' => 1,
      'message' => 'Success',
      'data' => $users
    ]);
  }

  public function get_sub_categories_select()
  {
    $category_id = $this->input->post('category_id');

    $sub_categories = $this->Ticket_model->get_sub_categories($category_id);

    if ($sub_categories) {
      $response = [
        'status' => 1,
        'data' => $sub_categories
      ];
    } else {
      $response = [
        'status' => 0,
        'data' => [],
        'message' => 'Sub category tidak ditemukan'
      ];
    }

    echo json_encode($response);
  }

  public function save_ticket()
  {
    $id = $this->input->post('id');

    // PERMISSION & OLD DATA
    if ($id) {
      $this->auth->restrict($this->managePermission);
      $old_ticket = $this->Ticket_model->get_ticket_by_id($id);
    } else {
      $this->auth->restrict($this->addPermission);
      $old_ticket = null;
    }

    // DATA MASTER
    $pic_id = $this->input->post('pic_id');
    $user_pic = $this->Ticket_model->get_user_by_id($pic_id);
    $pic_name = $user_pic ? $user_pic->nm_lengkap : '';

    $approval_id = $this->input->post('approval_by_id');
    $approval_name = '';
    if ($approval_id) {
      $user_approval = $this->Ticket_model->get_user_by_id($approval_id);
      $approval_name = $user_approval ? $user_approval->nm_lengkap : '';
    }

    $client_id = $this->input->post('client_id');
    $client_name = '';
    if ($client_id) {
      $client = $this->Ticket_model->get_client_by_id($client_id);
      $client_name = $client ? $client->name_app : '';
    }

    $category_id = $this->input->post('category_id');
    $category = $this->Ticket_model->get_category_by_id($category_id);
    $category_name = $category ? $category->category_name : '';

    $sub_category_id = $this->input->post('sub_category_id');
    $sub_category = $this->Ticket_model->get_sub_category_by_id($sub_category_id);
    $sub_category_name = $sub_category ? $sub_category->sub_name : '';

    // LOGIC APPROVAL LEVEL
    $create_by_id = $id ? $old_ticket->create_by_id : $this->auth->user_id();

    $approval_level = 1;
    if (!empty($approval_id) && $create_by_id != $approval_id) {
      $approval_level = 2;
    }

    // DATA UTAMA
    $data = [
      'report'            => $this->input->post('report'),
      'category_id'       => $category_id,
      'category_name'     => $category_name,
      'sub_category_id'   => $sub_category_id,
      'sub_category_name' => $sub_category_name,
      'causes'            => $this->input->post('causes'),
      'action_plan'       => $this->input->post('action_plan'),
      'due_date'          => $this->input->post('due_date'),
      'man_hour_plan'     => $this->input->post('man_hour_plan'),
      'pic_id'            => $pic_id,
      'pic'               => $pic_name,
      'client_id'         => $client_id,
      'client_name'       => $client_name,
      'approval_by_id'    => $approval_id,
      'approval_by'       => $approval_name,
      'approval_level'    => $approval_level,
      'update_date'       => date('Y-m-d H:i:s'),
      'update_by'         => $this->auth->user_id()
    ];

    // UPDATE
    if ($id) {
      $result = $this->Ticket_model->update_ticket($id, $data);
      if ($result) {
        $this->handle_file_upload($id);
      }
      if ($result && $old_ticket) {

        $new_status = $this->input->post('status');
        if ($new_status !== null && $old_ticket->status != $new_status) {

          $this->Ticket_model->save_history([
            'helpdesk_id' => $id,
            'no_ticket'   => $old_ticket->no_ticket,
            'action_type' => 1, // update status
            'old_status'  => $old_ticket->status,
            'new_status'  => $new_status,
            'description' => 'Status ticket diubah'
          ]);
        } else {
          $this->Ticket_model->save_history([
            'helpdesk_id' => $id,
            'no_ticket'   => $old_ticket->no_ticket,
            'action_type' => 8, // update data
            'description' => 'Data ticket diperbarui'
          ]);
        }
      }

      $message = 'Data berhasil diupdate';
    }

    // INSERT
    else {
      $data['no_ticket']    = $this->generate_ticket_number();
      $data['status']       = 0;
      $data['create_by_id'] = $this->auth->user_id();
      $data['create_by']    = $this->auth->user_name();
      $data['create_date']  = date('Y-m-d H:i:s');
      $data['is_delete']    = 0;

      $insert_id = $this->Ticket_model->insert_ticket($data);

      if ($insert_id) {
        $this->handle_file_upload($insert_id);
        // HISTORY CREATE
        $this->Ticket_model->save_history([
          'helpdesk_id' => $insert_id,
          'no_ticket'   => $data['no_ticket'],
          'action_type' => 0, // create
          'description' => 'Ticket dibuat'
        ]);
      }

      $result  = $insert_id;
      $message = 'Data berhasil disimpan';
    }

    echo json_encode([
      'status'  => $result ? 1 : 0,
      'message' => $result ? $message : 'Gagal menyimpan data'
    ]);
  }

  private function handle_file_upload($helpdesk_id)
  {
    if (!empty($_FILES['attachments']['name'][0])) {
      $upload_path = './uploads/helpdesk/' . date('Y/m') . '/';

      if (!is_dir($upload_path)) {
        mkdir($upload_path, 0755, true);
      }

      $files_count = count($_FILES['attachments']['name']);

      for ($i = 0; $i < $files_count; $i++) {
        if ($_FILES['attachments']['error'][$i] == 0) {
          $file_name_original = $_FILES['attachments']['name'][$i];
          $file_tmp  = $_FILES['attachments']['tmp_name'][$i];
          $file_size = $_FILES['attachments']['size'][$i];
          $file_type = $_FILES['attachments']['type'][$i];

          if ($file_size > 2048000) {
            continue;
          }

          $file_ext = pathinfo($file_name_original, PATHINFO_EXTENSION);
          $new_file_name = 'ticket_' . $helpdesk_id . '_' . time() . '_' . uniqid() . '.' . $file_ext;
          $file_path = $upload_path . $new_file_name;

          if (move_uploaded_file($file_tmp, $file_path)) {
            $this->Ticket_model->insert_attachment([
              'helpdesk_id'         => $helpdesk_id,
              'file_name'           => $new_file_name,
              'file_name_original'  => $file_name_original,
              'file_type'           => $file_type,
              'file_size'           => $file_size,
              'uploaded_by'         => $this->auth->user_name(),
              'uploaded_by_id'      => $this->auth->user_id(),
              'uploaded_date'       => date('Y-m-d H:i:s')
            ]);
          }
        }
      }
    }
  }

  public function download_attachment($id)
  {
    $attachment = $this->Ticket_model->get_attachment_by_id($id);

    if (!$attachment || $attachment->is_delete == 1) {
      show_404();
      return;
    }

    $date_folder = date('Y/m', strtotime($attachment->uploaded_date));
    $file_path = './uploads/helpdesk/' . $date_folder . '/' . $attachment->file_name;

    if (!file_exists($file_path)) {
      show_404();
      return;
    }

    $this->load->helper('download');
    force_download($attachment->file_name_original, file_get_contents($file_path));
  }

  public function delete_attachment()
  {
    $id = $this->input->post('id');

    $attachment = $this->Ticket_model->get_attachment_by_id($id);

    if ($attachment) {
      $date_folder = date('Y/m', strtotime($attachment->uploaded_date));
      $file_path = './uploads/helpdesk/' . $date_folder . '/' . $attachment->file_name;

      if (file_exists($file_path)) {
        unlink($file_path);
      }

      $result = $this->Ticket_model->delete_attachment($id);

      echo json_encode([
        'status'  => $result ? 1 : 0,
        'message' => $result ? 'File berhasil dihapus' : 'Gagal menghapus file'
      ]);
    } else {
      echo json_encode([
        'status'  => 0,
        'message' => 'File tidak ditemukan'
      ]);
    }
  }

  private function generate_ticket_number()
  {
    // Format: HDYYMMXXXX
    $prefix = 'HD';
    $year = date('y');
    $month = date('m');
    $yearMonth = $year . $month;
    $last_ticket = $this->Ticket_model->get_last_ticket_number($yearMonth);

    if ($last_ticket) {
      $last_number = (int) substr($last_ticket->no_ticket, -4);
      $new_number = $last_number + 1;
    } else {
      $new_number = 1;
    }

    // Format: HDYYMMXXXX
    $ticket_number = $prefix . $yearMonth . str_pad($new_number, 4, '0', STR_PAD_LEFT);

    return $ticket_number;
  }

  public function update_status()
  {
    if (!has_permission('Helpdesk.Manage')) {
      echo json_encode([
        'status' => 0,
        'message' => 'Anda tidak memiliki izin untuk mengubah status ticket'
      ]);
      return;
    }

    $id             = $this->input->post('id');
    $status         = $this->input->post('status');
    $current_status = $this->input->post('current_status');

    if (empty($id) || !is_numeric($status)) {
      echo json_encode([
        'status' => 0,
        'message' => 'Data tidak valid'
      ]);
      return;
    }

    $old_ticket = $this->Ticket_model->get_ticket_by_id($id);
    if (!$old_ticket) {
      echo json_encode([
        'status' => 0,
        'message' => 'Ticket tidak ditemukan'
      ]);
      return;
    }

    // VALIDASI Man Hour Plan jika status berubah ke Process (1)
    if ((int)$status === 1) {
      $man_hour_plan = $this->input->post('man_hour_plan');
      $existing_plan = $old_ticket->man_hour_plan ?? 0;

      // Jika existing plan kosong DAN input juga kosong
      if ((!$existing_plan || $existing_plan == 0) && (!$man_hour_plan || $man_hour_plan <= 0)) {
        echo json_encode([
          'status' => 0,
          'message' => 'Man Hour Plan wajib diisi sebelum memproses ticket'
        ]);
        return;
      }
    }

    // Validasi Man Hour Actual jika status berubah dari Process (1) ke Done (4)
    if ((int)$status === 4 && (int)$current_status === 1) {
      $man_hour_actual = $this->input->post('man_hour_actual');

      if (empty($man_hour_actual) || $man_hour_actual <= 0) {
        echo json_encode([
          'status' => 0,
          'message' => 'Man Hour Actual wajib diisi saat mengubah status ke Done'
        ]);
        return;
      }
    }

    $statusText = [
      0 => 'Open',
      1 => 'Process',
      2 => 'Pending',
      3 => 'Cancel',
      4 => 'Done',
      5 => 'Close',
      6 => 'Revisi'
    ];

    $statusName = $statusText[$status] ?? 'Unknown';

    $data = [
      'status'       => $status,
      'update_date'  => date('Y-m-d H:i:s'),
      'update_by'    => $this->auth->user_id(),
      'update_by_id' => $this->auth->user_id()
    ];

    // Handle Cancel Reason
    if ($status == 3 && $this->input->post('cancel_reason')) {
      $data['cancel_reason'] = $this->input->post('cancel_reason');
    }

    // Handle Man Hour Plan jika diisi (saat process)
    if ((int)$status === 1 && $this->input->post('man_hour_plan')) {
      $data['man_hour_plan'] = (float)$this->input->post('man_hour_plan');
    }

    // Handle Man Hour Actual - Tambahkan jika ada nilai sebelumnya (untuk kasus revisi)
    if ((int)$status === 4 && $this->input->post('man_hour_actual')) {
      $new_man_hour = (float)$this->input->post('man_hour_actual');
      $old_man_hour = (float)($old_ticket->man_hour_actual ?? 0);

      $data['man_hour_actual'] = $old_man_hour > 0 ? ($old_man_hour + $new_man_hour) : $new_man_hour;
    }

    // Reset approval jika dari rejected ke process
    if ((int)$status === 1 && (int)$old_ticket->is_approve === 2) {
      $data['is_approve']         = 0;
      $data['approval_reason']    = null;
      $data['approval_2_reason']  = null;
    }

    $result = $this->Ticket_model->update_ticket_status($id, $data);
    $description = 'Status diubah menjadi ' . $statusName;

    if ((int)$status === 4) {
      $description .= ' dan menunggu approval';
    }

    if ($result) {
      $this->Ticket_model->save_history([
        'helpdesk_id'  => $id,
        'no_ticket'    => $old_ticket->no_ticket,
        'action_type'  => 1,
        'old_status'   => $old_ticket->status,
        'new_status'   => $status,
        'description'  => $description,
        'cause_pic'    => $this->input->post('cancel_reason') ?: null,
        'action_by'    => $this->auth->nama(),
        'action_by_id' => $this->auth->user_id(),
        'action_date'  => date('Y-m-d H:i:s')
      ]);

      echo json_encode([
        'status' => 1,
        'message' => "Status ticket berhasil diubah menjadi {$statusName}"
      ]);
    } else {
      echo json_encode([
        'status' => 0,
        'message' => 'Gagal mengubah status ticket'
      ]);
    }
  }

  public function get_ticket_details($id)
  {
    if (!has_permission('Helpdesk.View')) {
      echo json_encode(['status' => 0, 'message' => 'Access denied']);
      return;
    }
    $ticket = $this->Ticket_model->get_ticket_by_id($id);

    if ($ticket) {
      echo json_encode([
        'status' => 1,
        'data' => [
          'no_ticket' => $ticket->no_ticket,
          'report' => $ticket->report,
          'pic' => $ticket->pic,
          'current_status' => $ticket->status
        ]
      ]);
    } else {
      echo json_encode(['status' => 0, 'message' => 'Ticket tidak ditemukan']);
    }
  }

  public function update_approval()
  {
    $id     = $this->input->post('id');
    $action = $this->input->post('action'); // approve | reject
    $reason = trim($this->input->post('approval_reason'));

    if (!$id || !$action || $reason === '') {
      return $this->_json(0, 'Data tidak valid');
    }

    $userId = $this->auth->user_id();
    $userNm = $this->auth->nama();
    $now    = date('Y-m-d H:i:s');

    $ticket = $this->Ticket_model->get_ticket_by_id($id);
    if (!$ticket) {
      return $this->_json(0, 'Ticket tidak ditemukan');
    }

    // REJECT (FINAL)
    if ($action === 'reject') {

      $this->Ticket_model->update_ticket_approval($id, [
        'is_approve'             => 2,
        'status'                 => 6, // revisi
        'current_approval_level' => 0,
        'approval_reason'        => $reason,
        'approval_2_reason'      => null,
        'approval_date'          => $now,
        'update_date'            => $now
      ]);

      $this->Ticket_model->save_history([
        'helpdesk_id'  => $id,
        'no_ticket'    => $ticket->no_ticket,
        'action_type'  => 5, // reject
        'description'  => 'Ticket di-reject',
        'cause_pic'    => $reason,
        'old_status'   => $ticket->status,
        'new_status'   => 6,
        'action_by'    => $userNm,
        'action_by_id' => $userId,
        'action_date'  => $now
      ]);

      return $this->_json(1, 'Ticket berhasil di-reject');
    }

    // APPROVAL
    $nextLevel = (int)$ticket->current_approval_level + 1;

    // LEVEL 1 APPROVAL
    if ($nextLevel === 1 && (int)$ticket->approval_level >= 1) {

      $update = [
        'approval_reason'        => $reason,
        'current_approval_level' => 1,
        'approval_by'            => $userNm,
        'approval_by_id'         => $userId,
        'approval_date'          => $now,
        'update_date'            => $now
      ];

      // FINAL JIKA CUMA 1 LEVEL
      if ((int)$ticket->approval_level === 1) {
        $update['is_approve'] = 1;
        $update['status']     = 5; // close
      }

      $this->Ticket_model->update_ticket_approval($id, $update);

      $this->Ticket_model->save_history([
        'helpdesk_id'  => $id,
        'no_ticket'    => $ticket->no_ticket,
        'action_type'  => ((int)$ticket->approval_level === 1) ? 7 : 4,
        'description'  => ((int)$ticket->approval_level === 1)
          ? 'Final approval oleh pembuat'
          : 'Approval level 1',
        'cause_pic'    => $reason,
        'old_status'   => $ticket->status,
        'new_status'   => ((int)$ticket->approval_level === 1) ? 5 : $ticket->status,
        'action_by'    => $userNm,
        'action_by_id' => $userId,
        'action_date'  => $now
      ]);

      return $this->_json(
        1,
        ((int)$ticket->approval_level === 1)
          ? 'Ticket berhasil di-approve dan ditutup'
          : 'Approval level 1 berhasil'
      );
    }

    // LEVEL 2 (FINAL)
    if ($nextLevel === 2 && (int)$ticket->approval_level === 2) {

      $this->Ticket_model->update_ticket_approval($id, [
        'approval_2_reason'      => $reason,
        'current_approval_level' => 2,
        'is_approve'             => 1,
        'status'                 => 5, // close
        'update_date'            => $now
      ]);

      $this->Ticket_model->save_history([
        'helpdesk_id'  => $id,
        'no_ticket'    => $ticket->no_ticket,
        'action_type'  => 7, // approval pembuat
        'description'  => 'Final approval level 2 oleh pembuat',
        'cause_pic'    => $reason,
        'old_status'   => $ticket->status,
        'new_status'   => 5,
        'action_by'    => $userNm,
        'action_by_id' => $userId,
        'action_date'  => $now
      ]);

      return $this->_json(1, 'Approval final berhasil, ticket ditutup');
    }

    return $this->_json(0, 'Approval tidak valid');
  }



  private function _json($status, $message)
  {
    echo json_encode(compact('status', 'message'));
    exit;
  }


  public function get_ticket_history($id)
  {
    $this->auth->restrict($this->viewPermission);

    $history = $this->Ticket_model->get_ticket_history($id);

    if ($history) {
      $result = [
        'status' => 1,
        'data' => $history
      ];
    } else {
      $result = [
        'status' => 0,
        'message' => 'History tidak ditemukan'
      ];
    }

    echo json_encode($result);
  }

  public function download_chat_file($chat_id)
  {
    $this->auth->restrict($this->viewPermission);

    $chat = $this->Ticket_model->get_chat_by_id($chat_id);

    if (!$chat || empty($chat['file_name'])) {
      show_404();
      return;
    }

    $file_path = './uploads/helpdesk_chat/' . $chat['file_name'];

    if (!file_exists($file_path)) {
      show_404();
      return;
    }

    $this->load->helper('download');
    force_download($file_path, NULL);
  }

  public function get_unread_chat_counts()
  {
    $this->auth->restrict($this->viewPermission);

    $user_id = $this->auth->user_id();
    $unread_counts = $this->Ticket_model->get_all_unread_counts($user_id);

    echo json_encode(['status' => 1, 'data' => $unread_counts]);
  }

  public function mark_chat_read()
  {
    $this->auth->restrict($this->viewPermission);

    $helpdesk_id = $this->input->post('helpdesk_id');
    $user_id = $this->auth->user_id();

    if (empty($helpdesk_id)) {
      echo json_encode(['status' => 0, 'message' => 'Helpdesk ID required']);
      return;
    }

    $success = $this->Ticket_model->mark_chat_as_read($helpdesk_id, $user_id);

    if ($success) {
      echo json_encode(['status' => 1, 'message' => 'Chat marked as read']);
    } else {
      echo json_encode(['status' => 0, 'message' => 'Failed to mark chat as read']);
    }
  }

  public function send_chat_message()
  {
    // $this->auth->restrict($this->managePermission);

    $helpdesk_id = $this->input->post('helpdesk_id');
    $message = trim($this->input->post('message'));
    $user_id = $this->auth->user_id();
    $user_name = $this->auth->user_name();

    if (empty($helpdesk_id)) {
      echo json_encode(['status' => 0, 'message' => 'Helpdesk ID required']);
      return;
    }

    $hasMessage = !empty($message);
    $hasFile = !empty($_FILES['chat_file']['name']);

    if (!$hasMessage && !$hasFile) {
      echo json_encode(['status' => 0, 'message' => 'Message atau file harus diisi']);
      return;
    }

    $data = [
      'helpdesk_id' => $helpdesk_id,
      'message' => $message ?: '',
      'sender_id' => $user_id,
      'sender_name' => $user_name,
      'create_date' => date('Y-m-d H:i:s'),
      'is_read' => 0
    ];

    if ($hasFile) {
      $upload_path = './uploads/helpdesk_chat/';

      if (!is_dir($upload_path)) {
        mkdir($upload_path, 0755, TRUE);
      }

      $file_name = $_FILES['chat_file']['name'];
      $file_tmp = $_FILES['chat_file']['tmp_name'];
      $file_size = $_FILES['chat_file']['size'];
      $file_error = $_FILES['chat_file']['error'];

      // Validasi error upload
      if ($file_error !== UPLOAD_ERR_OK) {
        echo json_encode(['status' => 0, 'message' => 'Upload error code: ' . $file_error]);
        return;
      }

      // Validasi ekstensi
      $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip', 'rar'];
      $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

      if (!in_array($file_ext, $allowed_ext)) {
        echo json_encode(['status' => 0, 'message' => 'File type not allowed: ' . $file_ext]);
        return;
      }

      // Validasi size (2MB)
      if ($file_size > 2048000) {
        echo json_encode(['status' => 0, 'message' => 'File size exceeds 2MB']);
        return;
      }

      if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif'])) {
        $image_info = getimagesize($file_tmp);
        if ($image_info === false) {
          echo json_encode(['status' => 0, 'message' => 'Invalid image file']);
          return;
        }
      }

      $new_file_name = 'chat_' . time() . '_' . uniqid() . '.' . $file_ext;
      $destination = $upload_path . $new_file_name;

      if (move_uploaded_file($file_tmp, $destination)) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $detected_mime = finfo_file($finfo, $destination);
        finfo_close($finfo);

        $data['file_name'] = $new_file_name;
        $data['original_name'] = $file_name;
        $data['file_type'] = $detected_mime;
        $data['file_size'] = $file_size;
      } else {
        echo json_encode(['status' => 0, 'message' => 'Failed to move uploaded file']);
        return;
      }
    }

    $insert = $this->Ticket_model->insert_chat_message($data);

    if ($insert) {
      echo json_encode(['status' => 1, 'message' => 'Message sent successfully']);
    } else {
      echo json_encode(['status' => 0, 'message' => 'Failed to send message']);
    }
  }

  public function get_chat_messages($helpdesk_id)
  {
    $this->auth->restrict($this->viewPermission);
    $user_id = $this->auth->user_id();
    $messages = $this->Ticket_model->get_chat_messages_with_read_status($helpdesk_id, $user_id);

    $formatted_messages = [];
    foreach ($messages as $message) {
      $formatted_messages[] = [
        'id' => $message->id,
        'message' => $message->message,
        'sender_id' => $message->sender_id,
        'sender_name' => $message->sender_name,
        'file_name' => $message->file_name,
        'original_name' => $message->original_name,
        'file_type' => $message->file_type,
        'file_size' => $message->file_size,
        'create_date' => $message->create_date,
        'is_sent_by_me' => $message->sender_id == $user_id,
        'read_count' => $message->total_readers,
        'is_read_by_me' => $message->is_read_by_me
      ];
    }

    echo json_encode(['status' => 1, 'data' => $formatted_messages]);
  }

  public function get_chat_readers($chat_id)
  {
    $this->auth->restrict($this->viewPermission);

    $readers = $this->Ticket_model->get_chat_readers_detail($chat_id);

    $formatted_readers = [];
    foreach ($readers as $reader) {
      $formatted_readers[] = [
        'user_id' => $reader['user_id'],
        'name' => $reader['nm_lengkap'] ?? $reader['username'] ?? 'Unknown',
        'read_date' => $reader['read_date'],
        'read_time_formatted' => $this->format_read_time($reader['read_date'])
      ];
    }

    echo json_encode([
      'status' => 1,
      'data' => $formatted_readers,
      'total' => count($formatted_readers)
    ]);
  }

  private function format_read_time($datetime)
  {
    if (empty($datetime)) return '-';

    $time = strtotime($datetime);
    $now = time();
    $diff = $now - $time;

    if ($diff < 60) {
      return 'Baru saja';
    } elseif ($diff < 3600) {
      $minutes = floor($diff / 60);
      return "{$minutes} menit yang lalu";
    } elseif ($diff < 86400) {
      $hours = floor($diff / 3600);
      return "{$hours} jam yang lalu";
    } else {
      return date('d/m/Y H:i', $time);
    }
  }
}
