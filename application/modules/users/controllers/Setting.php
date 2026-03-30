<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author CokesHome
 * @copyright Copyright (c) 2015, CokesHome
 *
 * This is controller for Users Management
 */

class Setting extends Admin_Controller
{

    /**
     * Load the models, library, etc
     *
     *
     */

    //Permissions
    protected $viewPermission   = "Users.View";
    protected $addPermission    = "Users.Add";
    protected $managePermission = "Users.Manage";
    protected $deletePermission = "Users.Delete";

    public function __construct()
    {
        parent::__construct();
        $this->lang->load('users');
        $this->load->model(array(
            'users/users_model',
            'users/groups_model',
            'users/user_groups_model',
            'users/permissions_model',
            'users/user_permissions_model',
            'Cabang/Cabang_model',
        ));

        $this->template->page_icon('fa fa-users');
    }

    public function index()
    {
        $this->auth->restrict($this->viewPermission);

        $data = [
            'ENABLE_ADD' => has_permission($this->addPermission),
            'ENABLE_MANAGE' => has_permission($this->managePermission),
            'ENABLE_DELETE' => has_permission($this->deletePermission)
        ];

        history("View users");
        $this->template->title(lang('users_manage_title'));
        $this->template->render('list', $data);
    }

    public function get_data()
    {
        $this->auth->restrict($this->viewPermission);
        $filter_type = $this->input->get('filter_type') ?? 'all';

        $users = $this->users_model->get_users_with_client_apps($filter_type);

        $data = [
            'users' => $users,
            'ENABLE_MANAGE' => has_permission($this->managePermission)
        ];
        // var_dump($data);die;
        $this->template->render('user_table', $data);
    }

    public function toggle_status()
    {
        $this->auth->restrict($this->managePermission);

        $id_user = $this->input->post('id_user');
        $current_status = $this->input->post('current_status');

        $new_status = ($current_status == 1) ? 0 : 1;

        $data = ['st_aktif' => $new_status];
        $result = $this->users_model->update($id_user, $data);

        if ($result) {
            $status_text = ($new_status == 1) ? 'Aktif' : 'Non-Aktif';
            $keterangan = "SUKSES, ubah status user ID: $id_user menjadi $status_text";
            $status = 1;
        } else {
            $keterangan = "GAGAL, ubah status user ID: $id_user";
            $status = 0;
        }

        $nm_hak_akses = $this->managePermission;
        $kode_universal = $id_user;
        $jumlah = 1;
        $sql = $this->db->last_query();
        simpan_aktifitas($nm_hak_akses, $kode_universal, $keterangan, $jumlah, $sql, $status);

        echo json_encode([
            'status' => $result,
            'new_status' => $new_status,
            'message' => $keterangan
        ]);
    }

    public function create()
    {
        $this->auth->restrict($this->addPermission);

        if (isset($_POST['save'])) {
            if ($this->save_user()) {
                $this->template->set_message(lang('users_create_success'), 'success');
                redirect('users/setting');
            }
        }

        // Get client list for helpdesk
        $clients = $this->db->get_where('helpdesk_client', array('is_delete' => 0))->result_array();
        $this->template->set('clients', $clients);

        $this->template->title(lang('users_new_title'));
        $this->template->page_icon('fa fa-user');
        $this->template->render('users_form');
    }

    public function edit($id = 0)
    {
        $this->auth->restrict($this->managePermission);

        if ($id == 0 || is_numeric($id) == FALSE) {
            $this->template->set_message(lang('users_invalid_id'), 'error');
            redirect('users/setting');
        }

        if (isset($_POST['save'])) {
            if ($this->save_user("update", $id)) {
                $this->template->set_message(lang('users_edit_success'), 'success');
                redirect('users/setting');
            }
        }

        $data = $this->users_model->find($id);

        if ($data) {
            if ($data->deleted == 1) {
                $this->template->set_message(lang('users_already_deleted'), 'error');
                redirect('users/setting');
            }
        }

        $clients = $this->db->get_where('helpdesk_client', array('is_delete' => 0))->result_array();
        $assigned_clients = $this->db->get_where('helpdesk_user_client', array('id_user' => $id, 'is_active' => 1))->result_array();
        $selected_clients = array_column($assigned_clients, 'client_id');

        $this->template->set('clients', $clients);
        $this->template->set('selected_clients', $selected_clients);
        $this->template->set('data', $data);
        $this->template->title(lang('users_edit_title'));
        $this->template->page_icon('fa fa-user');
        $this->template->render('users_form');
    }

    protected function save_user($type = 'insert', $id = 0)
    {
        if ($type == "insert") {
            $extra_rule = "|unique[users.username]";
            $rule_email = "|unique[users.email]";
        } else {
            $_POST['id_user'] = $id;
            $extra_rule = "|unique[users.username, users.id_user]";
            $rule_email = "|unique[users.email, users.id_user]";
        }

        $this->form_validation->set_rules('username', 'lang:users_username', 'required' . $extra_rule);

        if ($type == "insert") {
            $this->form_validation->set_rules('password', 'lang:users_password', 'required');
            $this->form_validation->set_rules('re-password', 'lang:users_repassword', 'required|matches[password]');
        } else {
            if (!empty($_POST['password'])) {
                $this->form_validation->set_rules('password', 'lang:users_password', 'required');
                $this->form_validation->set_rules('re-password', 'lang:users_repassword', 'required|matches[password]');
            }
        }

        $this->form_validation->set_rules('email', 'lang:users_email', 'required|valid_email' . $rule_email);
        $this->form_validation->set_rules('nm_lengkap', 'lang:users_nm_lengkap', 'required');
        $this->form_validation->set_rules('alamat', 'lang:users_alamat', 'required');
        $this->form_validation->set_rules('kota', 'lang:users_kota', 'required');
        $this->form_validation->set_rules('hp', 'lang:users_hp', 'required');
        $this->form_validation->set_rules('status', 'lang:users_status', 'required|in_list[0,1]');
        $this->form_validation->set_rules('role_user', 'Role User', 'required|in_list[ba,programmer,user]');

        if ($this->form_validation->run($this) === FALSE) {
            $this->template->set_message(validation_errors(), 'error');
            return FALSE;
        }

        $username   = $this->input->post('username');
        $password   = $this->input->post('password');
        $email      = $this->input->post('email');
        $nm_lengkap = $this->input->post('nm_lengkap');
        $alamat     = $this->input->post('alamat');
        $kota       = $this->input->post('kota');
        $hp         = $this->input->post('hp');
        $kdcab      = $this->input->post('kdcab');
        $status     = $this->input->post('status');
        $client_ids = $this->input->post('client_ids');
        $role_user     = $this->input->post('role_user');
        $is_ba          = ($role_user === 'ba') ? 1 : 0;
        $is_programmer  = ($role_user === 'programmer') ? 1 : 0;

        $timeTarget = 0.05;
        $cost = 8;
        do {
            $cost++;
            $start = microtime(true);
            password_hash("test", PASSWORD_BCRYPT, ["cost" => $cost]);
            $end = microtime(true);
        } while (($end - $start) < $timeTarget);

        $options = [
            'cost' => $cost,
            // 'salt' => bin2hex(openssl_random_pseudo_bytes(22))
        ];

        $password_hashed = password_hash($password, PASSWORD_BCRYPT, $options);

        if ($type == 'insert') {
            $data_insert = array(
                'username' => $username,
                'password' => $password_hashed,
                'email'    => $email,
                'nm_lengkap' => $nm_lengkap,
                'alamat'   => $alamat,
                'kota'     => $kota,
                'hp'       => $hp,
                'ip'       => $this->input->ip_address(),
                'st_aktif' => 1,
                'kdcab'    => $kdcab,
                'status'   => $status,
                'is_ba'         => $is_ba,
                'is_programmer' => $is_programmer
            );

            $result = $this->users_model->insert($data_insert);

            if ($result) {
                // Get Default user group
                $dt_group = $this->groups_model->find_by(array('st_default' => 1));
                if ($dt_group) {
                    $id_group = $dt_group->id_group;
                    $insert_group = array(
                        'id_user' => $result,
                        'id_group' => $id_group
                    );
                    $this->user_groups_model->insert($insert_group);
                }

                // Insert helpdesk client assignments
                if (!empty($client_ids) && is_array($client_ids)) {
                    $this->save_helpdesk_clients($result, $client_ids);
                }

                return TRUE;
            } else {
                $this->template->set_message(lang('users_create_fail') . $this->users_model->error, 'error');
                return FALSE;
            }
        } else {
            $data_insert = array(
                'username' => $username,
                'email'    => $email,
                'nm_lengkap' => $nm_lengkap,
                'alamat'   => $alamat,
                'kota'     => $kota,
                'hp'       => $hp,
                'ip'       => $this->input->ip_address(),
                'kdcab'    => $kdcab,
                'status'   => $status,
                'is_ba'         => $is_ba,
                'is_programmer' => $is_programmer
            );

            if (isset($_POST['password']) && !empty($_POST['password'])) {
                $data_insert['password'] = $password_hashed;
            }

            $result = $this->users_model->update($id, $data_insert);

            if ($result !== FALSE) {
                // Update helpdesk client assignments
                if (isset($client_ids) && is_array($client_ids)) {
                    $this->save_helpdesk_clients($id, $client_ids);
                } else {
                    // If no clients selected, deactivate all
                    $this->db->where('id_user', $id);
                    $this->db->update('helpdesk_user_client', array('is_active' => 0));
                }

                return TRUE;
            } else {
                $this->template->set_message(lang('users_edit_fail') . $this->users_model->error, 'error');
                return FALSE;
            }
        }
    }

    protected function save_helpdesk_clients($user_id, $client_ids)
    {
        // Start transaction
        $this->db->trans_start();

        // Deactivate all existing assignments for this user
        $this->db->where('id_user', $user_id);
        $this->db->update('helpdesk_user_client', array('is_active' => 0));

        // Process selected clients
        if (!empty($client_ids)) {
            foreach ($client_ids as $client_id) {
                // Validate client exists and not deleted
                $client_exists = $this->db->get_where('helpdesk_client', array(
                    'id' => $client_id,
                    'is_delete' => 0
                ))->num_rows();

                if ($client_exists > 0) {
                    // Check if record exists in helpdesk_user_client
                    $existing = $this->db->get_where('helpdesk_user_client', array(
                        'id_user' => $user_id,
                        'client_id' => $client_id
                    ))->row();

                    if ($existing) {
                        // Reactivate existing record
                        $this->db->where('id', $existing->id);
                        $this->db->update('helpdesk_user_client', array('is_active' => 1));
                    } else {
                        // Insert new record
                        $data = array(
                            'id_user' => $user_id,
                            'client_id' => $client_id,
                            'is_active' => 1,
                            'create_date' => date('Y-m-d H:i:s'),
                            'create_by' => $this->auth->nama()
                        );
                        $this->db->insert('helpdesk_user_client', $data);
                    }
                }
            }
        }

        // Complete transaction
        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function default_select($val)
    {
        return $val == "" ? FALSE : TRUE;
    }

    public function getArray($table, $WHERE = array(), $keyArr = '', $valArr = '')
    {
        if ($WHERE) {
            $query = $this->db->get_where($table, $WHERE);
        } else {
            $query = $this->db->get($table);
        }
        $dataArr    = $query->result_array();

        if (!empty($keyArr)) {
            $Arr_Data    = array();
            foreach ($dataArr as $key => $val) {
                $nilai_id                    = $val[$keyArr];
                if (!empty($valArr)) {
                    $nilai_val                = $val[$valArr];
                    $Arr_Data[$nilai_id]    = $nilai_val;
                } else {
                    $Arr_Data[$nilai_id]    = $val;
                }
            }

            return $Arr_Data;
        } else {
            return $dataArr;
        }
    }

    public function permission($id = 0)
    {
        $this->auth->restrict($this->managePermission);

        if ($id == 0 || is_numeric($id) == FALSE || $id == 1) {
            $this->template->set_message(lang('users_invalid_id'), 'error');
            redirect('users/setting');
        }

        if (isset($_POST['save'])) {
            if ($this->save_permission($id)) {
                $this->template->set_message(lang('users_permission_edit_success'), 'success');
            }
        }

        // User data
        $data = $this->users_model->find($id);

        if ($data) {
            if ($data->deleted == 1) {
                $this->template->set_message(lang('users_already_deleted'), 'error');
                redirect('users/setting');
            }
        }

        // Get all menus (parent menus)
        $permissions_menu = $this->db->select('id, title as nama_menu, parent_id')
            ->from('menus')
            ->where('parent_id', 0)
            ->order_by('title', 'ASC')
            ->get()
            ->result_array();

        // Get submenu/child menus
        $ArrPermissionDetail = array();
        $submenus = $this->db->select('id, title as nama_menu, parent_id')
            ->from('menus')
            ->where('parent_id >', 0)
            ->order_by('parent_id, title', 'ASC')
            ->get()
            ->result_array();

        foreach ($submenus as $submenu) {
            $ArrPermissionDetail[$submenu['parent_id']][] = $submenu;
        }

        // Get all permissions with actions
        $all_permissions = $this->db->select('id_permission, nm_permission, id_menu, nm_menu')
            ->from('permissions')
            ->order_by('id_menu, nm_permission', 'ASC')
            ->get()
            ->result_array();

        // Group permissions by menu_id
        $ArrActionPers = array();
        foreach ($all_permissions as $perm) {
            $ArrActionPers[$perm['id_menu']][] = array(
                'id_permission' => $perm['id_permission'],
                'nm_permission' => $perm['nm_permission'],
                'nm_menu' => $perm['nm_menu']
            );
        }

        // Get user's authorized permissions (from role and user)
        $auth_permissions = $this->get_auth_permission($id);

        // Send data to view
        $this->template->set('data', $data);
        $this->template->set('permissions', $permissions_menu);
        $this->template->set('ArrPermissionDetail', $ArrPermissionDetail);
        $this->template->set('ArrActionPers', $ArrActionPers);
        $this->template->set('auth_permissions', $auth_permissions);

        $this->template->title(lang('users_edit_perm_title'));
        $this->template->page_icon('fa fa-shield');
        $this->template->render('user_permissions');
    }

    protected function save_permission($id_user = 0)
    {
        if ($id_user == 0 || $id_user == "") {
            $this->template->set_message(lang('users_invalid_id'), 'error');
            return FALSE;
        }

        $id_permissions = $this->input->post('id_permissions');

        $insert_data = array();
        if ($id_permissions) {
            foreach ($id_permissions as $key => $idp) {
                $insert_data[] = array(
                    'id_user' => $id_user,
                    'id_permission' => $idp
                );
            }
        }

        //Delete Fisrt All Previous user permission
        $result = $this->user_permissions_model->delete_where(array('id_user' => $id_user));

        //Insert New one
        if ($insert_data) {
            $result = $this->user_permissions_model->insert_batch($insert_data);
        }

        if ($result === FALSE) {
            $this->template->set_message(lang('users_permission_edit_fail'), 'error');
            return FALSE;
        }

        unset($_POST['save']);

        return $result;
    }

    protected function get_auth_permission($id = 0)
    {
        $role_permissions = $this->users_model->select("permissions.*")
            ->join("user_groups", "users.id_user = user_groups.id_user")
            ->join("group_permissions", "user_groups.id_group = group_permissions.id_group")
            ->join("permissions", "group_permissions.id_permission = permissions.id_permission")
            ->where("users.id_user", $id)
            ->find_all();

        $user_permissions = $this->users_model->select("permissions.*")
            ->join("user_permissions", "users.id_user = user_permissions.id_user")
            ->join("permissions", "user_permissions.id_permission = permissions.id_permission")
            ->where("users.id_user", $id)
            ->find_all();

        $merge = array();
        if ($role_permissions) {
            foreach ($role_permissions as $key => $rp) {
                if (!isset($merge[$rp->id_permission])) {
                    $rp->is_role_permission = 1;
                    $merge[$rp->id_permission] = $rp;
                }
            }
        }

        if ($user_permissions) {
            foreach ($user_permissions as $key => $up) {
                if (!isset($merge[$up->id_permission])) {
                    $up->is_role_permission = 0;
                    $merge[$up->id_permission] = $up;
                }
            }
        }

        return $merge;
    }
}
