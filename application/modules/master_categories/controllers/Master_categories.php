<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master_categories extends Admin_Controller
{
  //Permission
  protected $viewPermission   = 'Master_categories.View';
  protected $addPermission    = 'Master_categories.Add';
  protected $managePermission = 'Master_categories.Manage';
  protected $deletePermission = 'Master_categories.Delete';

  protected $id_user;
  protected $datetime;

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array(
      'Master_categories/Master_categories_model'
    ));
    $this->template->title('Manage Master Categories');
    $this->template->page_icon('fa fa-building-o');

    date_default_timezone_set('Asia/Bangkok');

    $this->id_user  = $this->auth->user_id();
    $this->datetime = date('Y-m-d H:i:s');
  }

  // CATEGORY FUNCTIONS

  public function index()
  {
    $this->auth->restrict($this->viewPermission);

    $this->template->title('Manage Helpdesk Stages');
    $this->template->page_icon('fa fa-table');
    $this->template->render('index_category');
  }

  public function get_list_category()
  {
    $this->auth->restrict($this->viewPermission);
    $helpdesk = $this->Master_categories_model->get_all_category();

    $data['helpdesk'] = $helpdesk;
    $this->template->render('table/list_helpdesk_category', $data);
  }

  public function add_category()
  {
    $this->auth->restrict($this->addPermission);

    if ($this->input->post()) {
      $category_name = $this->input->post('category_name');
      $remark = $this->input->post('remark');

      // Validasi
      if (empty($category_name)) {
        echo json_encode([
          'status'  => 0,
          'message' => 'Nama tahapan wajib diisi!'
        ]);
        return;
      }

      // Cek duplikat
      if ($this->Master_categories_model->check_category_exists($category_name)) {
        echo json_encode([
          'status'  => 0,
          'message' => 'Nama tahapan sudah terdaftar!'
        ]);
        return;
      }

      $data = [
        'category_name' => $category_name,
        'remark'        => $remark,
        'create_date'   => $this->datetime,
        'create_by_id'  => $this->id_user,
        'create_by'     => $this->session->app_session['nm_lengkap'],
        'is_delete'     => 0
      ];

      $insert = $this->Master_categories_model->insert_category($data);

      if ($insert) {
        $result = [
          'status'  => 1,
          'message' => 'Tahapan berhasil ditambahkan.'
        ];
      } else {
        $result = [
          'status'  => 0,
          'message' => 'Gagal menambahkan tahapan.'
        ];
      }

      echo json_encode($result);
    } else {
      $this->template->render('table/form_category');
    }
  }


  public function edit_category($id)
  {
    $this->auth->restrict($this->managePermission);

    if ($this->input->post()) {
      $category_name = $this->input->post('category_name');
      $remark = $this->input->post('remark');

      // Validasi
      if (empty($category_name)) {
        echo json_encode([
          'status'  => 0,
          'message' => 'Nama tahapan wajib diisi!'
        ]);
        return;
      }

      // Cek duplikat (kecuali data yang sedang diedit)
      if ($this->Master_categories_model->check_category_exists($category_name, $id)) {
        echo json_encode([
          'status'  => 0,
          'message' => 'Nama tahapan sudah digunakan!'
        ]);
        return;
      }

      $data = [
        'category_name' => $category_name,
        'remark'        => $remark,
        // Jika ini edit, sebaiknya update field update_date & update_by
        'update_date'   => $this->datetime,
        'update_by_id'  => $this->id_user,
        'update_by'     => $this->session->app_session['nm_lengkap'],
      ];

      $update = $this->Master_categories_model->update_category($id, $data);

      if ($update) {
        $result = [
          'status'  => 1,
          'message' => 'Tahapan berhasil diperbarui.'
        ];
      } else {
        $result = [
          'status'  => 0,
          'message' => 'Gagal memperbarui tahapan.'
        ];
      }

      echo json_encode($result);
    } else {
      $category = $this->Master_categories_model->get_category_by_id($id);
      $data['category'] = $category;
      $this->template->render('table/form_category', $data);
    }
  }


  public function check_sub_category_count()
  {
    $this->auth->restrict($this->deletePermission);

    $id = $this->input->post('id');

    if (empty($id)) {
      $result = array(
        'status' => 0,
        'count' => 0
      );
      echo json_encode($result);
      return;
    }

    $count_sub = $this->Master_categories_model->count_sub_category($id);

    $result = array(
      'status' => 1,
      'count' => $count_sub
    );

    echo json_encode($result);
  }

  public function delete_category()
  {
    $this->auth->restrict($this->deletePermission);

    $id = $this->input->post('id');

    if (empty($id)) {
      echo json_encode([
        'status'  => 0,
        'message' => 'ID tahapan wajib diisi!'
      ]);
      return;
    }

    $count_sub = $this->Master_categories_model->count_sub_category($id);

    $this->db->trans_start();

    if ($count_sub > 0) {
      $this->Master_categories_model->delete_all_sub_categories($id, $this->session->app_session['nm_lengkap']);
    }

    $delete = $this->Master_categories_model->delete_category($id, $this->session->app_session['nm_lengkap']);

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE || !$delete) {
      $result = [
        'status'  => 0,
        'message' => 'Gagal menghapus tahapan.'
      ];
    } else {
      $message = 'Tahapan berhasil dihapus.';
      if ($count_sub > 0) {
        $message .= ' (' . $count_sub . ' kategori juga ikut dihapus)';
      }

      $result = [
        'status'  => 1,
        'message' => $message
      ];
    }

    echo json_encode($result);
  }


  // SUB CATEGORY FUNCTIONS

  public function get_sub_categories()
  {
    $this->auth->restrict($this->viewPermission);

    $id_category = $this->input->get('id_category');
    $sub_categories = $this->Master_categories_model->get_sub_categories_by_category($id_category);

    $data['sub_categories'] = $sub_categories;
    $this->load->view('table/list_sub_category', $data);
  }

  public function get_sub_category_count()
  {
    $this->auth->restrict($this->viewPermission);

    $id_category = $this->input->get('id_category');
    $count = $this->Master_categories_model->count_sub_category($id_category);

    $result = array(
      'status' => 1,
      'count' => $count
    );

    echo json_encode($result);
  }

  public function add_sub_category()
  {
    $this->auth->restrict($this->addPermission);

    if ($this->input->post()) {
      $id_category = $this->input->post('id_category');
      $sub_name    = $this->input->post('sub_name');
      $remark      = $this->input->post('remark');

      // Validasi
      if (empty($sub_name)) {
        echo json_encode([
          'status'  => 0,
          'message' => 'Nama kategori wajib diisi!'
        ]);
        return;
      }

      // Cek duplikat kategori dalam tahapan yang sama
      if ($this->Master_categories_model->check_sub_category_exists($id_category, $sub_name)) {
        echo json_encode([
          'status'  => 0,
          'message' => 'Nama kategori sudah ada pada tahapan ini!'
        ]);
        return;
      }

      $data = [
        'id_category'  => $id_category,
        'sub_name'     => $sub_name,
        'remark'       => $remark,
        'create_date'  => $this->datetime,
        'create_by_id' => $this->id_user,
        'create_by'    => $this->session->app_session['nm_lengkap'],
        'is_delete'    => 0
      ];

      $insert = $this->Master_categories_model->insert_sub_category($data);

      if ($insert) {
        $result = [
          'status'  => 1,
          'message' => 'kategori berhasil ditambahkan.'
        ];
      } else {
        $result = [
          'status'  => 0,
          'message' => 'Gagal menambahkan kategori.'
        ];
      }

      echo json_encode($result);
    }
  }


  public function edit_sub_category($id)
  {
    $this->auth->restrict($this->managePermission);

    if ($this->input->post()) {
      $sub_name = $this->input->post('sub_name');
      $remark   = $this->input->post('remark');

      if (empty($sub_name)) {
        echo json_encode([
          'status'  => 0,
          'message' => 'Nama kategori wajib diisi!'
        ]);
        return;
      }

      $current_sub = $this->Master_categories_model->get_sub_category_by_id($id);

      if (!$current_sub) {
        echo json_encode([
          'status'  => 0,
          'message' => 'kategori tidak ditemukan!'
        ]);
        return;
      }

      if ($this->Master_categories_model->check_sub_category_exists(
        $current_sub->id_category,
        $sub_name,
        $id
      )) {
        echo json_encode([
          'status'  => 0,
          'message' => 'Nama kategori sudah digunakan pada tahapan ini!'
        ]);
        return;
      }

      $data = [
        'sub_name'     => $sub_name,
        'remark'       => $remark,
        'update_date'  => $this->datetime,
        'update_by_id' => $this->id_user,
        'update_by'    => $this->session->app_session['nm_lengkap'],
      ];

      $update = $this->Master_categories_model->update_sub_category($id, $data);

      if ($update) {
        $result = [
          'status'  => 1,
          'message' => 'kategori berhasil diperbarui.'
        ];
      } else {
        $result = [
          'status'  => 0,
          'message' => 'Gagal memperbarui kategori.'
        ];
      }

      echo json_encode($result);
    }
  }


  public function delete_sub_category()
  {
    $this->auth->restrict($this->deletePermission);

    $id = $this->input->post('id');

    if (empty($id)) {
      echo json_encode([
        'status'  => 0,
        'message' => 'ID kategori wajib diisi!'
      ]);
      return;
    }

    $delete = $this->Master_categories_model->delete_sub_category($id, $this->id_user);

    if ($delete) {
      $result = [
        'status'  => 1,
        'message' => 'kategori berhasil dihapus.'
      ];
    } else {
      $result = [
        'status'  => 0,
        'message' => 'Gagal menghapus kategori.'
      ];
    }

    echo json_encode($result);
  }
}
