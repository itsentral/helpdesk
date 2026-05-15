<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ticket_model extends BF_Model
{
    protected $ENABLE_ADD;
    protected $ENABLE_MANAGE;
    protected $ENABLE_VIEW;
    protected $ENABLE_DELETE;

    public function __construct()
    {
        parent::__construct();

        $this->ENABLE_ADD     = has_permission('Ticket.Add');
        $this->ENABLE_MANAGE  = has_permission('Ticket.Manage');
        $this->ENABLE_VIEW    = has_permission('Ticket.View');
        $this->ENABLE_DELETE  = has_permission('Ticket.Delete');
    }

    // public function get_all_ticket($client_id = null)
    // {
    //     $user_id = $this->auth->user_id();

    //     $this->db->select('helpdesk.*, hsc.sub_name');
    //     $this->db->from('helpdesk');
    //     $this->db->join('helpdesk_sub_category hsc', 'hsc.id = helpdesk.sub_category_id', 'left');
    //     $this->db->join('users u', 'u.id_user = ' . (int)$user_id, 'left');
    //     $this->db->join(
    //         'helpdesk_user_client huc',
    //         'huc.client_id = helpdesk.client_id 
    //     AND huc.id_user = ' . (int)$user_id . '
    //     AND huc.is_active = 1',
    //         'left'
    //     );

    //     $this->db->order_by("CASE 
    //         WHEN LOWER(hsc.sub_name) LIKE '%bugs konsep%' THEN 1
    //         WHEN LOWER(hsc.sub_name) LIKE '%bugs program%' THEN 2
    //         ELSE 3
    //     END", '', FALSE);

    //     $this->db->order_by('helpdesk.id', 'DESC');
    //     $this->db->where('helpdesk.is_delete', 0);
    //     $this->db->where('helpdesk.status !=', 3);
    //     $this->db->where('helpdesk.is_approve !=', 1);

    //     if (!empty($client_id)) {
    //         $this->db->where('helpdesk.client_id', $client_id);
    //     }

    //     if ($user_id != 7) { // admin
    //         $this->db->group_start()
    //             ->where('helpdesk.create_by_id', $user_id)
    //             ->or_where('helpdesk.pic_id', $user_id)
    //             ->or_where('helpdesk.approval_by_id', $user_id)
    //             ->or_group_start()
    //             ->where('u.is_ba', 1)
    //             ->where('huc.client_id IS NOT NULL', null, false)
    //             ->group_end()
    //             ->group_end();
    //     }

    //     return $this->db->get()->result_array();
    // }

    public function get_all_ticket($client_id = null, $status_filter = null)
    {
        $user_id = $this->auth->user_id();

        $this->db->select('helpdesk.*, hsc.sub_name');
        $this->db->from('helpdesk');
        $this->db->join('helpdesk_sub_category hsc', 'hsc.id = helpdesk.sub_category_id', 'left');
        $this->db->join('users u', 'u.id_user = ' . (int)$user_id, 'left');
        $this->db->join(
            'helpdesk_user_client huc',
            'huc.client_id = helpdesk.client_id 
            AND huc.id_user = ' . (int)$user_id . '
            AND huc.is_active = 1',
            'left'
        );

        $this->db->order_by("CASE 
        WHEN LOWER(hsc.sub_name) LIKE '%bugs konsep%' THEN 1
        WHEN LOWER(hsc.sub_name) LIKE '%bugs program%' THEN 2
        ELSE 3
        END", '', FALSE);

        $this->db->order_by('helpdesk.id', 'DESC');
        $this->db->where('helpdesk.is_delete', 0);
        $this->db->where('helpdesk.status !=', 3);
        $this->db->where('helpdesk.is_approve !=', 1);

        if (!empty($client_id)) {
            $this->db->where('helpdesk.client_id', $client_id);
        }

        // ===== Filter Status =====
        if ($status_filter !== null && $status_filter !== '') {
            switch ($status_filter) {
                case 'waiting_approval':
                    $this->db->where('helpdesk.status', 4);
                    $this->db->where('helpdesk.is_approve', 0);
                    $this->db->group_start()
                        ->where('helpdesk.current_approval_level', 0)
                        ->or_where('helpdesk.current_approval_level IS NULL', null, false)
                        ->group_end();
                    break;

                case 'waiting_creator':
                    $this->db->where('helpdesk.status', 4);
                    $this->db->where('helpdesk.is_approve', 0);
                    $this->db->where('helpdesk.current_approval_level', 1);
                    $this->db->where('helpdesk.approval_level >=', 2);
                    break;

                case 'no_pic':
                    // PIC belum ditunjuk, bukan status cancel
                    $this->db->where('(helpdesk.pic_id IS NULL OR helpdesk.pic_id = "")', null, false);
                    $this->db->where('helpdesk.status !=', 3);
                    break;

                default:
                    // Filter by status biasa (0,1,2,4,6)
                    if (is_numeric($status_filter)) {
                        $this->db->where('helpdesk.status', (int)$status_filter);
                    }
                    break;
            }
        }

        if ($user_id != 7) { // admin
            $this->db->group_start()
                ->where('helpdesk.create_by_id', $user_id)
                ->or_where('helpdesk.pic_id', $user_id)
                ->or_where('helpdesk.approval_by_id', $user_id)
                ->or_group_start()
                ->where('u.is_ba', 1)
                ->where('huc.client_id IS NOT NULL', null, false)
                ->group_end()
                ->group_end();
        }

        return $this->db->get()->result_array();
    }

    public function get_approved_ticket($client_id = null, $date_from = null, $date_to = null)
    {
        $user_id = $this->auth->user_id();

        $this->db->select('helpdesk.*, hsc.sub_name');
        $this->db->from('helpdesk');
        $this->db->join('helpdesk_sub_category hsc', 'hsc.id = helpdesk.sub_category_id', 'left');
        $this->db->join('users u', 'u.id_user = ' . (int)$user_id, 'left');
        $this->db->join(
            'helpdesk_user_client huc',
            'huc.client_id = helpdesk.client_id 
        AND huc.id_user = ' . (int)$user_id . '
        AND huc.is_active = 1',
            'left'
        );

        $this->db->order_by('helpdesk.id', 'DESC');
        $this->db->where('helpdesk.is_delete', 0);
        $this->db->where('helpdesk.is_approve', 1);

        if (!empty($client_id)) {
            $this->db->where('helpdesk.client_id', $client_id);
        }

        if (!empty($date_from)) {
            $this->db->where('DATE(helpdesk.create_date) >=', $date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('DATE(helpdesk.create_date) <=', $date_to);
        }

        if ($user_id != 7) {
            $this->db->group_start()
                ->where('helpdesk.create_by_id', $user_id)
                ->or_where('helpdesk.pic_id', $user_id)
                ->or_where('helpdesk.approval_by_id', $user_id)
                ->or_group_start()
                ->where('u.is_ba', 1)
                ->where('huc.client_id IS NOT NULL', null, false)
                ->group_end()
                ->group_end();
        }

        return $this->db->get()->result_array();
    }

    public function get_cancel_ticket($client_id = null)
    {
        $user_id = $this->auth->user_id();

        $this->db->select('helpdesk.*, hsc.sub_name');
        $this->db->from('helpdesk');
        $this->db->join('helpdesk_sub_category hsc', 'hsc.id = helpdesk.sub_category_id', 'left');
        $this->db->join('users u', 'u.id_user = ' . (int)$user_id, 'left');
        $this->db->join(
            'helpdesk_user_client huc',
            'huc.client_id = helpdesk.client_id 
            AND huc.id_user = ' . (int)$user_id . '
            AND huc.is_active = 1',
            'left'
        );

        $this->db->order_by('helpdesk.id', 'DESC');
        $this->db->where('helpdesk.is_delete', 0);
        $this->db->where('helpdesk.status', 3);

        if (!empty($client_id)) {
            $this->db->where('helpdesk.client_id', $client_id);
        }

        if ($user_id != 7) { // 7 = admin
            $this->db->group_start()
                ->where('helpdesk.create_by_id', $user_id)
                ->or_where('helpdesk.pic_id', $user_id)
                ->or_where('helpdesk.approval_by_id', $user_id)
                ->or_group_start()
                ->where('u.is_ba', 1)
                ->where('huc.client_id IS NOT NULL', null, false)
                ->group_end()
                ->group_end();
        }

        return $this->db->get()->result_array();
    }

    // ADD TICKET FUNCTION
    public function get_categories()
    {
        $this->db->select('*');
        $this->db->from('helpdesk_category');
        $this->db->where('is_delete', 0);
        $this->db->order_by('category_name', 'ASC');
        return $this->db->get()->result();
    }

    public function get_sub_categories($category_id)
    {
        $this->db->select('*');
        $this->db->from('helpdesk_sub_category');
        $this->db->where('id_category', $category_id);
        $this->db->where('is_delete', 0);
        $this->db->order_by('sub_name', 'ASC');
        return $this->db->get()->result();
    }

    public function insert_ticket($data)
    {
        $this->db->insert('helpdesk', $data);

        return $this->db->insert_id();
    }

    public function update_ticket($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('helpdesk', $data);
    }

    public function get_last_ticket_number($yearMonth)
    {
        $this->db->select('no_ticket');
        $this->db->from('helpdesk');
        $this->db->like('no_ticket', 'HD' . $yearMonth, 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        return $this->db->get()->row();
    }

    public function get_category_by_id($id)
    {
        $this->db->from('helpdesk_category');
        $this->db->where('id', $id);
        $this->db->where('is_delete', 0);
        return $this->db->get()->row();
    }

    public function get_sub_category_by_id($id)
    {
        $this->db->from('helpdesk_sub_category');
        $this->db->where('id', $id);
        $this->db->where('is_delete', 0);
        return $this->db->get()->row();
    }

    public function get_users()
    {
        $this->db->select('id_user, username, nm_lengkap, email, is_ba');
        $this->db->from('users');
        $this->db->where('st_aktif', 1);
        $this->db->where('deleted', 0);
        $this->db->order_by('nm_lengkap', 'ASC');
        return $this->db->get()->result();
    }

    public function get_users_by_client($client_id)
    {
        $this->db->select('u.id_user, u.username, u.nm_lengkap, u.email');
        $this->db->from('users u');
        $this->db->join('helpdesk_user_client huc', 'huc.id_user = u.id_user', 'inner');
        $this->db->where('u.st_aktif', 1);
        $this->db->where('u.deleted', 0);
        $this->db->where('huc.client_id', $client_id);
        $this->db->where('huc.is_active', 1);
        $this->db->where('huc.id_user !=', 7);
        $this->db->order_by('u.nm_lengkap', 'ASC');
        return $this->db->get()->result();
    }

    public function get_user_by_id($id)
    {
        $this->db->select('id_user, username, nm_lengkap, email, status, is_ba');
        $this->db->from('users');
        $this->db->where('id_user', $id);
        return $this->db->get()->row();
    }

    public function get_user_clients($user_id)
    {
        $this->db->select('hc.id, hc.name_app, hc.remark');
        $this->db->from('helpdesk_user_client huc');
        $this->db->join('helpdesk_client hc', 'hc.id = huc.client_id');
        $this->db->where('huc.id_user', $user_id);
        $this->db->where('huc.is_active', 1);
        $this->db->where('hc.is_delete', 0);
        $this->db->order_by('hc.name_app', 'ASC');
        return $this->db->get()->result();
    }

    public function get_all_clients()
    {
        $this->db->select('id, name_app, remark');
        $this->db->from('helpdesk_client');
        $this->db->where('is_delete', 0);
        $this->db->order_by('name_app', 'ASC');
        return $this->db->get()->result();
    }

    public function get_client_by_id($id)
    {
        $this->db->select('id, name_app, remark');
        $this->db->from('helpdesk_client');
        $this->db->where('id', $id);
        $this->db->where('is_delete', 0);
        return $this->db->get()->row();
    }

    public function get_ticket_detail($id)
    {
        $this->db->select('h.*, hc.category_name, hsc.sub_name as sub_name, 
                            u_pic.nm_lengkap as pic_name, u_pic.username as pic_username,
                            u_approval.nm_lengkap as approval_name, u_approval.username as approval_username,
                            client.name_app as client_name, client.remark as client_remark');
        $this->db->from('helpdesk h');
        $this->db->join('helpdesk_category hc', 'hc.id = h.category_id', 'left');
        $this->db->join('helpdesk_sub_category hsc', 'hsc.id = h.sub_category_id', 'left');
        $this->db->join('users u_pic', 'u_pic.id_user = h.pic_id', 'left');
        $this->db->join('users u_approval', 'u_approval.id_user = h.approval_by_id', 'left');
        $this->db->join('helpdesk_client client', 'client.id = h.client_id', 'left');
        $this->db->where('h.id', $id);
        $this->db->where('h.is_delete', 0);

        return $this->db->get()->row();
    }

    public function get_ticket_by_id($id)
    {
        $this->db->select('h.*, hc.category_name, hsc.sub_name as sub_name');
        $this->db->from('helpdesk h');
        $this->db->join('helpdesk_category hc', 'hc.id = h.category_id', 'left');
        $this->db->join('helpdesk_sub_category hsc', 'hsc.id = h.sub_category_id', 'left');
        $this->db->where('h.id', $id);
        $this->db->where('h.is_delete', 0);

        return $this->db->get()->row();
    }

    public function update_ticket_status($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->where('is_delete', 0);
        return $this->db->update('helpdesk', $data);
    }

    public function check_ticket_exists($id)
    {
        $this->db->select('id');
        $this->db->from('helpdesk');
        $this->db->where('id', $id);
        $this->db->where('is_delete', 0);
        $query = $this->db->get();
        return ($query->num_rows() > 0);
    }

    public function update_ticket_approval($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->where('is_delete', 0);
        return $this->db->update('helpdesk', $data);
    }

    public function save_history($data = [])
    {
        $insert = [
            'helpdesk_id'  => $data['helpdesk_id'],
            'no_ticket'    => $data['no_ticket'],
            'action_type'  => $data['action_type'],
            'description'  => $data['description'] ?? null,
            'cause_pic'    => $data['cause_pic'] ?? null,
            'old_status'   => $data['old_status'] ?? null,
            'new_status'   => $data['new_status'] ?? null,
            'action_by'    => $this->auth->nama(),
            'action_by_id' => $this->auth->user_id(),
            'action_date'  => date('Y-m-d H:i:s')
        ];

        return $this->db->insert('helpdesk_history', $insert);
    }

    public function insert_attachment($data)
    {
        return $this->db->insert('helpdesk_attachments', $data);
    }

    public function get_attachments_by_helpdesk($helpdesk_id)
    {
        return $this->db->where('helpdesk_id', $helpdesk_id)
            ->where('is_delete', 0)
            ->get('helpdesk_attachments')
            ->result();
    }

    public function get_attachment_by_id($id)
    {
        return $this->db->where('id', $id)->get('helpdesk_attachments')->row();
    }

    public function delete_attachment($id)
    {
        return $this->db->where('id', $id)
            ->update('helpdesk_attachments', ['is_delete' => 1]);
    }

    public function get_ticket_history($helpdesk_id)
    {
        $this->db->select('*');
        $this->db->from('helpdesk_history');
        $this->db->where('helpdesk_id', $helpdesk_id);
        $this->db->order_by('action_date', 'DESC');

        return $this->db->get()->result_array();
    }

    public function get_chat_messages($helpdesk_id)
    {
        $this->db->order_by('create_date', 'ASC');
        $this->db->where('helpdesk_id', $helpdesk_id);
        return $this->db->get('helpdesk_chat')->result_array();
    }

    public function insert_chat_message($data)
    {
        return $this->db->insert('helpdesk_chat', $data);
    }

    public function get_chat_by_id($chat_id)
    {
        return $this->db->get_where('helpdesk_chat', ['id' => $chat_id])->row_array();
    }

    public function get_unread_chat_count($helpdesk_id, $user_id)
    {
        $this->db->select('COUNT(hc.id) as unread_count');
        $this->db->from('helpdesk_chat hc');
        $this->db->join(
            'helpdesk_chat_read_status hcrs',
            "hc.id = hcrs.chat_id AND hcrs.user_id = '$user_id'",
            'left'
        );
        $this->db->where('hc.helpdesk_id', $helpdesk_id);
        $this->db->where('hc.sender_id !=', $user_id);
        $this->db->group_start();
        $this->db->where('hcrs.id IS NULL'); // Belum ada record read status
        $this->db->or_where('hcrs.is_read', 0); // belum dibaca
        $this->db->group_end();

        $result = $this->db->get()->row();
        return $result ? (int)$result->unread_count : 0;
    }

    public function mark_chat_as_read($helpdesk_id, $user_id)
    {
        $this->db->select('hc.id as chat_id');
        $this->db->from('helpdesk_chat hc');
        $this->db->join(
            'helpdesk_chat_read_status hcrs',
            "hc.id = hcrs.chat_id AND hcrs.user_id = '$user_id'",
            'left'
        );
        $this->db->where('hc.helpdesk_id', $helpdesk_id);
        $this->db->where('hc.sender_id !=', $user_id);
        $this->db->group_start();
        $this->db->where('hcrs.id IS NULL'); // Belum ada record
        $this->db->or_where('hcrs.is_read', 0); // belum dibaca
        $this->db->group_end();

        $unread_chats = $this->db->get()->result_array();

        $this->db->trans_start();

        foreach ($unread_chats as $chat) {
            $exists = $this->db->get_where('helpdesk_chat_read_status', [
                'chat_id' => $chat['chat_id'],
                'user_id' => $user_id
            ])->row();

            if ($exists) {
                // Update jika sudah ada
                $this->db->where('id', $exists->id);
                $this->db->update('helpdesk_chat_read_status', [
                    'is_read' => 1,
                    'read_date' => date('Y-m-d H:i:s')
                ]);
            } else {
                // Insert baru
                $this->db->insert('helpdesk_chat_read_status', [
                    'chat_id' => $chat['chat_id'],
                    'user_id' => $user_id,
                    'is_read' => 1,
                    'read_date' => date('Y-m-d H:i:s')
                ]);
            }
        }

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function get_all_unread_counts($user_id)
    {
        $this->db->select('hc.helpdesk_id, COUNT(hc.id) as unread_count');
        $this->db->from('helpdesk_chat hc');
        $this->db->join('helpdesk h', 'h.id = hc.helpdesk_id', 'inner');
        $this->db->join(
            'helpdesk_chat_read_status hcrs',
            "hc.id = hcrs.chat_id AND hcrs.user_id = '$user_id'",
            'left'
        );
        $this->db->join(
            'helpdesk_user_client huc',
            'huc.client_id = h.client_id AND huc.id_user = ' . (int)$user_id . ' AND huc.is_active = 1',
            'left'
        );
        $this->db->join('users u', 'u.id_user = ' . (int)$user_id, 'left');

        $this->db->where('hc.sender_id !=', $user_id);

        $this->db->group_start();
        $this->db->where('hcrs.id IS NULL'); // Belum ada record
        $this->db->or_where('hcrs.is_read', 0); // Atau belum dibaca
        $this->db->group_end();

        $this->db->where('h.is_delete', 0);

        // Filter berdasarkan role/permission
        if ($user_id != 7) { // admin
            $this->db->group_start()
                ->where('h.create_by_id', $user_id)
                ->or_where('h.pic_id', $user_id)
                ->or_where('h.approval_by_id', $user_id)
                ->or_group_start()
                ->where('u.is_ba', 1)
                ->where('huc.client_id IS NOT NULL', null, false)
                ->group_end()
                ->group_end();
        }

        $this->db->group_by('hc.helpdesk_id');

        $result = $this->db->get()->result_array();

        $unread_counts = [];
        foreach ($result as $row) {
            $unread_counts[$row['helpdesk_id']] = $row['unread_count'];
        }

        return $unread_counts;
    }

    public function get_chat_read_status($chat_id)
    {
        $this->db->select('hcrs.user_id, hcrs.read_date, u.nm_lengkap');
        $this->db->from('helpdesk_chat_read_status hcrs');
        $this->db->join('users u', 'u.id_user = hcrs.user_id', 'left');
        $this->db->where('hcrs.chat_id', $chat_id);
        $this->db->where('hcrs.is_read', 1);
        $this->db->order_by('hcrs.read_date', 'ASC');

        return $this->db->get()->result_array();
    }

    public function get_chat_readers_detail($chat_id)
    {
        $this->db->select('hcrs.user_id, hcrs.read_date, u.nm_lengkap, u.username');
        $this->db->from('helpdesk_chat_read_status hcrs');
        $this->db->join('users u', 'u.id_user = hcrs.user_id', 'left');
        $this->db->where('hcrs.chat_id', $chat_id);
        $this->db->where('hcrs.is_read', 1);
        $this->db->order_by('hcrs.read_date', 'ASC');

        return $this->db->get()->result_array();
    }

    public function get_chat_messages_with_read_status($helpdesk_id, $current_user_id)
    {
        $escaped_user_id = $this->db->escape($current_user_id);
        $escaped_helpdesk_id = $this->db->escape($helpdesk_id);

        $sql = "
                SELECT 
                    hc.*, 
                    (SELECT COUNT(*) 
                    FROM helpdesk_chat_read_status hcrs2 
                    WHERE hcrs2.chat_id = hc.id AND hcrs2.is_read = 1) as total_readers,
                    (SELECT COUNT(*) 
                    FROM helpdesk_chat_read_status hcrs3 
                    WHERE hcrs3.chat_id = hc.id 
                        AND hcrs3.user_id = {$escaped_user_id}
                        AND hcrs3.is_read = 1) as is_read_by_me
                FROM helpdesk_chat hc
                WHERE hc.helpdesk_id = {$escaped_helpdesk_id}
                ORDER BY hc.create_date ASC
            ";

        return $this->db->query($sql)->result();
    }

    public function soft_delete_chat($chat_id, $user_id)
    {
        $this->db->where('id', $chat_id);
        $this->db->where('sender_id', $user_id); 
        return $this->db->update('helpdesk_chat', [
            'is_delete' => 1
        ]);
    }

    public function update_chat_message($chat_id, $user_id, $new_message)
    {
        $this->db->where('id', $chat_id);
        $this->db->where('sender_id', $user_id);
        return $this->db->update('helpdesk_chat', [
            'message' => $new_message
        ]);
    }
}
