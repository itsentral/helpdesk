<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ticket_management_model extends BF_Model
{
    protected $ENABLE_ADD;
    protected $ENABLE_MANAGE;
    protected $ENABLE_VIEW;
    protected $ENABLE_DELETE;

    public function __construct()
    {
        parent::__construct();

        $this->ENABLE_ADD     = has_permission('Ticket_Management.Add');
        $this->ENABLE_MANAGE  = has_permission('Ticket_Management.Manage');
        $this->ENABLE_VIEW    = has_permission('Ticket_Management.View');
        $this->ENABLE_DELETE  = has_permission('Ticket_Management.Delete');
    }

    public function get_list_programmer()
    {
        // Ambil semua programmer
        $this->db->select('u.id_user as pic_id, u.nm_lengkap as pic, u.is_programmer, u.is_ba');
        $this->db->from('users u');
        $this->db->where('u.is_programmer', 1);
        $users = $this->db->get()->result();

        // Ambil semua ticket programmer
        $this->db->select('h.*, u.is_programmer, u.is_ba');
        $this->db->from('helpdesk h');
        $this->db->join('users u', 'u.id_user = h.pic_id', 'left');
        $this->db->where('u.is_programmer', 1);
        $this->db->where('h.is_approve', 0);
        $this->db->where('h.status !=', 3);
        $this->db->where('h.status !=', 5);
        $this->db->where('h.is_delete', 0);
        $this->db->order_by('h.pic', 'ASC');
        $this->db->order_by('h.order_programmer', 'ASC');
        $tickets = $this->db->get()->result();

        // Gabungkan: semua user + ticket mereka (bisa kosong)
        return $this->_merge_users_tickets($users, $tickets);
    }

    public function get_list_ba()
    {
        // Ambil semua BA
        $this->db->select('u.id_user as pic_id, u.nm_lengkap as pic, u.is_programmer, u.is_ba');
        $this->db->from('users u');
        $this->db->where('u.is_ba', 1);
        $users = $this->db->get()->result();

        // Ambil semua ticket BA
        $this->db->select('h.*, u.is_programmer, u.is_ba');
        $this->db->from('helpdesk h');
        $this->db->join('users u', 'u.id_user = h.pic_id', 'left');
        $this->db->where('u.is_ba', 1);
        $this->db->where('h.is_approve', 0);
        $this->db->where('h.status !=', 3);
        $this->db->where('h.status !=', 5);
        $this->db->where('h.is_delete', 0);
        $this->db->order_by('h.pic', 'ASC');
        $this->db->order_by('h.order_ba', 'ASC');
        $tickets = $this->db->get()->result();

        return $this->_merge_users_tickets($users, $tickets);
    }

    public function update_order($orders, $field)
    {
        // $orders = [['id' => 1, 'order' => 0], ['id' => 2, 'order' => 1], ...]
        // $field  = 'order_programmer' atau 'order_ba'

        $allowedFields = ['order_programmer', 'order_ba'];
        if (!in_array($field, $allowedFields)) return false;

        foreach ($orders as $item) {
            $this->db->where('id', (int)$item['id']);
            $this->db->update('helpdesk', [$field => (int)$item['order']]);
        }
        return true;
    }

    private function _merge_users_tickets($users, $tickets)
    {
        $ticketMap = [];
        foreach ($tickets as $t) {
            $ticketMap[$t->pic_id][] = $t;
        }

        $result = [];
        foreach ($users as $u) {
            $dummy = new stdClass();
            $dummy->pic_id        = $u->pic_id;
            $dummy->pic           = $u->pic;
            $dummy->is_programmer = $u->is_programmer;
            $dummy->is_ba         = $u->is_ba;

            $tickets_raw = $ticketMap[$u->pic_id] ?? [];

            // Pisahkan done (status=4) dan non-done, done selalu di bawah
            $nonDone = array_filter($tickets_raw, fn($t) => $t->status != 4);
            $done    = array_filter($tickets_raw, fn($t) => $t->status == 4);

            $dummy->_tickets = array_merge(array_values($nonDone), array_values($done));
            $result[] = $dummy;
        }

        return $result;
    }

    public function sort_by_due_date($ticket_groups)
    {
        foreach ($ticket_groups as &$group) {
            // Pisah done dan non-done
            $nonDone = array_filter($group->_tickets, fn($t) => $t->status != 4);
            $done    = array_filter($group->_tickets, fn($t) => $t->status == 4);

            // Sort non-done by due_date ASC (null/kosong ke paling bawah)
            usort($nonDone, function ($a, $b) {
                if (empty($a->due_date) && empty($b->due_date)) return 0;
                if (empty($a->due_date)) return 1;  // null ke bawah
                if (empty($b->due_date)) return -1;
                return strcmp($a->due_date, $b->due_date);
            });

            $group->_tickets = array_merge(array_values($nonDone), array_values($done));
        }
        return $ticket_groups;
    }
}
