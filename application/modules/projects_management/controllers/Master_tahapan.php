<?php defined('BASEPATH') or exit('No direct script access allowed');

class Master_tahapan extends Admin_Controller
{
    protected $id_user;
    protected $datetime;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('projects_management/Module_model');

        date_default_timezone_set('Asia/Bangkok');
        $this->id_user  = $this->auth->user_id();
        $this->datetime = date('Y-m-d H:i:s');
    }

    public function index()
    {
        $this->template->title('Master Tahapan');
        $this->template->page_icon('fa fa-list-ol');

        $data['tahapan'] = $this->db->where('is_deleted', 0)->order_by('tahapan_order', 'ASC')->get('pm_master_tahapan')->result_array();

        $this->template->set($data);
        $this->template->render('master_tahapan/index');
    }

    /**
     * AJAX: Save (create or update)
     */
    public function save()
    {
        $id              = $this->input->post('id');
        $tahapan_order   = $this->input->post('tahapan_order');
        $tahapan_name    = trim($this->input->post('tahapan_name'));
        $default_role    = $this->input->post('default_role');
        $conflict_action = $this->input->post('conflict_action'); // 'swap' or 'shift'

        if (empty($tahapan_name) || empty($tahapan_order) || empty($default_role)) {
            echo json_encode(array('status' => 0, 'pesan' => 'Semua field wajib diisi.'));
            return;
        }

        $order = (int)$tahapan_order;
        if ($order <= 0) {
            echo json_encode(array('status' => 0, 'pesan' => 'Urutan harus berupa angka positif minimal 1.'));
            return;
        }

        // Cek duplikasi urutan (tahapan_order)
        $this->db->where('tahapan_order', $order);
        $this->db->where('is_deleted', 0);
        if (!empty($id)) {
            $this->db->where('id !=', $id);
        }
        $existing = $this->db->get('pm_master_tahapan')->row_array();

        if ($existing) {
            // Jika bentrok dan belum ada persetujuan konfirmasi
            if (empty($conflict_action)) {
                $action_type = !empty($id) ? 'swap' : 'shift';
                echo json_encode(array(
                    'status'         => 'confirm',
                    'action_type'    => $action_type,
                    'conflict_name'  => $existing['tahapan_name'],
                    'conflict_order' => $order,
                    'pesan'          => !empty($id)
                        ? 'Urutan ke-' . $order . ' sudah digunakan oleh tahapan "' . html_escape($existing['tahapan_name']) . '". Apakah Anda ingin menukar posisi urutan kedua tahapan ini?'
                        : 'Urutan ke-' . $order . ' sudah digunakan oleh tahapan "' . html_escape($existing['tahapan_name']) . '". Apakah Anda ingin menyisipkan di urutan ke-' . $order . ' dan menggeser tahapan setelahnya ke bawah?'
                ));
                return;
            }

            // Jika conflict_action = swap (pada mode Edit)
            if (!empty($id) && $conflict_action === 'swap') {
                $current_row = $this->db->get_where('pm_master_tahapan', array('id' => $id))->row_array();
                $old_order   = $current_row ? (int)$current_row['tahapan_order'] : 0;

                $this->db->trans_start();

                // 1. Beri offset sementara pada record yang bentrok
                $this->db->where('id', $existing['id'])->update('pm_master_tahapan', array(
                    'tahapan_order' => 10000 + $order,
                    'updated_at'    => $this->datetime
                ));

                // 2. Update record yang sedang diedit ke urutan baru
                $this->db->where('id', $id)->update('pm_master_tahapan', array(
                    'tahapan_order' => $order,
                    'tahapan_name'  => $tahapan_name,
                    'default_role'  => $default_role,
                    'updated_at'    => $this->datetime
                ));

                // 3. Update record yang bentrok ke urutan lama
                $this->db->where('id', $existing['id'])->update('pm_master_tahapan', array(
                    'tahapan_order' => $old_order > 0 ? $old_order : 10000,
                    'updated_at'    => $this->datetime
                ));

                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    echo json_encode(array('status' => 0, 'pesan' => 'Gagal menukar urutan tahapan.'));
                } else {
                    echo json_encode(array('status' => 1, 'pesan' => 'Tahapan berhasil diperbarui dan urutan berhasil ditukar.'));
                }
                return;
            }

            // Jika conflict_action = shift (pada mode Tambah Baru)
            if (empty($id) && $conflict_action === 'shift') {
                $this->db->trans_start();

                // Geser ke bawah (+1) semua tahapan yang order >= $order mulai dari yang paling besar (DESC)
                $stages_to_shift = $this->db->where('tahapan_order >=', $order)
                                            ->where('is_deleted', 0)
                                            ->order_by('tahapan_order', 'DESC')
                                            ->get('pm_master_tahapan')
                                            ->result_array();

                foreach ($stages_to_shift as $st) {
                    $this->db->where('id', $st['id'])->update('pm_master_tahapan', array(
                        'tahapan_order' => $st['tahapan_order'] + 1,
                        'updated_at'    => $this->datetime
                    ));
                }

                // Insert tahapan baru pada posisi $order
                $new_data = array(
                    'tahapan_order' => $order,
                    'tahapan_name'  => $tahapan_name,
                    'default_role'  => $default_role,
                    'is_active'     => 1,
                    'created_at'    => $this->datetime,
                    'updated_at'    => $this->datetime
                );
                $this->db->insert('pm_master_tahapan', $new_data);

                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    echo json_encode(array('status' => 0, 'pesan' => 'Gagal menyisipkan tahapan.'));
                } else {
                    echo json_encode(array('status' => 1, 'pesan' => 'Tahapan baru berhasil disisipkan dan tahapan berikutnya digeser.'));
                }
                return;
            }
        }

        $data = array(
            'tahapan_order' => $order,
            'tahapan_name'  => $tahapan_name,
            'default_role'  => $default_role,
            'updated_at'    => $this->datetime
        );

        if (!empty($id)) {
            // Update
            $this->db->where('id', $id);
            $this->db->update('pm_master_tahapan', $data);
            echo json_encode(array('status' => 1, 'pesan' => 'Tahapan berhasil diupdate.'));
        } else {
            // Create
            $data['is_active']  = 1;
            $data['created_at'] = $this->datetime;
            $this->db->insert('pm_master_tahapan', $data);
            echo json_encode(array('status' => 1, 'pesan' => 'Tahapan berhasil ditambahkan.'));
        }
    }

    /**
     * AJAX: Toggle active/inactive
     */
    public function toggle_active()
    {
        $id = $this->input->post('id');
        if (empty($id)) {
            echo json_encode(array('status' => 0, 'pesan' => 'ID tidak valid.'));
            return;
        }

        $row = $this->db->get_where('pm_master_tahapan', array('id' => $id))->row_array();
        if (!$row) {
            echo json_encode(array('status' => 0, 'pesan' => 'Data tidak ditemukan.'));
            return;
        }

        $new_status = $row['is_active'] ? 0 : 1;
        $this->db->where('id', $id);
        $this->db->update('pm_master_tahapan', array('is_active' => $new_status, 'updated_at' => $this->datetime));

        echo json_encode(array('status' => 1, 'pesan' => 'Status berhasil diubah.'));
    }

    /**
     * AJAX: Delete (soft delete)
     */
    public function delete()
    {
        $id = $this->input->post('id');
        if (empty($id)) {
            echo json_encode(array('status' => 0, 'pesan' => 'ID tidak valid.'));
            return;
        }

        $this->db->where('id', $id);
        $this->db->update('pm_master_tahapan', array('is_deleted' => 1, 'updated_at' => $this->datetime));
        echo json_encode(array('status' => 1, 'pesan' => 'Tahapan berhasil dihapus.'));
    }

    /**
     * AJAX: Reorder stages
     */
    public function reorder()
    {
        $orders = $this->input->post('orders');
        if (empty($orders) || !is_array($orders)) {
            echo json_encode(array('status' => 0, 'pesan' => 'Data urutan tidak valid.'));
            return;
        }

        $this->db->trans_start();

        // 1. Temporary offset to prevent duplicate/collision issues
        foreach ($orders as $item) {
            $id    = (int) $item['id'];
            $order = (int) $item['order'];
            if ($id > 0 && $order > 0) {
                $this->db->where('id', $id)->update('pm_master_tahapan', array(
                    'tahapan_order' => 10000 + $order,
                    'updated_at'    => $this->datetime
                ));
            }
        }

        // 2. Set actual new orders
        foreach ($orders as $item) {
            $id    = (int) $item['id'];
            $order = (int) $item['order'];
            if ($id > 0 && $order > 0) {
                $this->db->where('id', $id)->update('pm_master_tahapan', array(
                    'tahapan_order' => $order,
                    'updated_at'    => $this->datetime
                ));
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(array('status' => 0, 'pesan' => 'Gagal mengubah urutan tahapan.'));
        } else {
            echo json_encode(array('status' => 1, 'pesan' => 'Urutan tahapan berhasil diperbarui.'));
        }
    }
}
