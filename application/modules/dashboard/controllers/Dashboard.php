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
        $user_info = $this->db->get_where('users', ['id_user' => $user_id])->row();

        $data = [
        'is_programmer' => (int)($user_info->is_programmer ?? 0),
        'is_ba'         => (int)($user_info->is_ba ?? 0),
        'is_admin'      => ($user_info->id_user == 7) ? 1 : 0,
        'is_exclude'    => ($user_info->id_user == 231) ? 1 : 0,
    ];
        // var_dump($user_info);die;

        $this->template->title('Dashboard');
        $this->template->page_icon('ti ti-lock-access');
        $this->template->render('index', $data);
    }

    public function get_my_priorities()
    {
        $user_id = $this->auth->user_id();
        $priorities = $this->dashboard_model->get_my_priorities($user_id);
        echo json_encode($priorities);
    }

    public function get_all_subcategories()
    {
        $subcats = $this->dashboard_model->get_all_subcategories();
        echo json_encode($subcats);
    }

    public function get_project_issues()
    {
        $date_from    = $this->input->get('date_from')    ?: date('Y-m-01');
        $date_to      = $this->input->get('date_to')      ?: date('Y-m-d');
        $subcat_names = $this->input->get('subcat_names') ?: [];
        $all_time     = $this->input->get('all_time') === '1';

        $subcat_names = array_filter(array_map('trim', (array)$subcat_names));

        $rows = $this->dashboard_model->get_project_issues(
            $date_from,
            $date_to,
            $subcat_names,
            $all_time
        );

        $summary    = ['total' => 0, 'open' => 0, 'process' => 0, 'pending' => 0, 'done' => 0, 'revisi' => 0];
        $status_map = [0 => 'open', 1 => 'process', 2 => 'pending', 4 => 'done', 6 => 'revisi'];
        $clients_raw = [];

        foreach ($rows as $r) {
            $cid = $r->client_id;

            if (!isset($clients_raw[$cid])) {
                $clients_raw[$cid] = [
                    'client_id'   => $cid,
                    'client_name' => $r->client_name,
                    'total'       => 0,
                    'open'        => 0,
                    'process'     => 0,
                    'pending'     => 0,
                    'done'        => 0,
                    'revisi'      => 0,
                    'tickets'     => [],
                ];
            }

            if (is_null($r->ticket_id)) continue;

            $clients_raw[$cid]['total']++;
            if (isset($status_map[$r->status])) {
                $clients_raw[$cid][$status_map[$r->status]]++;
            }

            $summary['total']++;
            if (isset($status_map[$r->status])) {
                $summary[$status_map[$r->status]]++;
            }

            $clients_raw[$cid]['tickets'][] = $r;
        }

        // client tanpa tiket tetap tampil di bawah
        usort($clients_raw, fn($a, $b) => $b['total'] - $a['total']);

        // ── Status counts untuk donut ─────────────────────
        $status_label_map = [0 => 'Open', 1 => 'Process', 2 => 'Pending', 4 => 'Done', 6 => 'Revisi'];
        $status_raw = [];
        foreach ($clients_raw as $c) {
            foreach ($c['tickets'] as $t) {
                if (!isset($status_label_map[$t->status])) continue;
                $status_raw[$t->status] = ($status_raw[$t->status] ?? 0) + 1;
            }
        }
        $status_counts = [];
        foreach ($status_raw as $code => $count) {
            $status_counts[] = ['label' => $status_label_map[$code], 'count' => $count];
        }

        // ── Count per Sub Category ────────────────────────
        $subcat_raw = [];
        foreach ($clients_raw as $c) {
            foreach ($c['tickets'] as $t) {
                $name = trim($t->sub_category_name ?: 'Unknown');
                $subcat_raw[$name] = ($subcat_raw[$name] ?? 0) + 1;
            }
        }
        arsort($subcat_raw);
        $subcat_counts = [];
        foreach ($subcat_raw as $name => $count) {
            $subcat_counts[] = ['name' => $name, 'count' => $count];
        }

        echo json_encode([
            'summary'       => $summary,
            'status_counts' => $status_counts,
            'subcat_counts' => $subcat_counts,
            'clients'       => array_values($clients_raw),
        ]);
    }

    public function render_modal_project()
    {
        $client_id    = $this->input->get('client_id');
        $client_name  = $this->input->get('client_name');
        $date_from    = $this->input->get('date_from')    ?: date('Y-m-01');
        $date_to      = $this->input->get('date_to')      ?: date('Y-m-d');
        $all_time     = $this->input->get('all_time') === '1';
        $subcat_names = array_filter(array_map('trim', (array)$this->input->get('subcat_names')));

        $tickets = $this->dashboard_model->get_tickets_by_client(
            $client_id,
            $date_from,
            $date_to,
            $subcat_names,
            $all_time
        );

        $status_map = [0 => 'open', 1 => 'process', 2 => 'pending', 4 => 'done', 6 => 'revisi'];
        $summary = ['total' => 0, 'open' => 0, 'process' => 0, 'pending' => 0, 'done' => 0, 'revisi' => 0];
        foreach ($tickets as $t) {
            $summary['total']++;
            if (isset($status_map[$t->status])) {
                $summary[$status_map[$t->status]]++;
            }
        }

        $data = [
            'client_name' => $client_name,
            'tickets'     => $tickets,
            'summary'     => $summary,
        ];

        $this->load->view('dashboard/modal_projects', $data);
    }

    public function render_project_cards()
    {
        $date_from    = $this->input->get('date_from')    ?: date('Y-m-01');
        $date_to      = $this->input->get('date_to')      ?: date('Y-m-d');
        $all_time     = $this->input->get('all_time') === '1';
        $subcat_names = array_filter(array_map('trim', (array)$this->input->get('subcat_names')));

        $rows = $this->dashboard_model->get_project_issues(
            $date_from,
            $date_to,
            $subcat_names,
            $all_time
        );

        $status_map  = [0 => 'open', 1 => 'process', 2 => 'pending', 4 => 'done', 6 => 'revisi'];
        $clients_raw = [];

        foreach ($rows as $r) {
            $cid = $r->client_id;
            if (!isset($clients_raw[$cid])) {
                $clients_raw[$cid] = [
                    'client_id'   => $cid,
                    'client_name' => $r->client_name,
                    'total'       => 0,
                    'open'        => 0,
                    'process'     => 0,
                    'pending'     => 0,
                    'done'        => 0,
                    'revisi'      => 0,
                ];
            }
            if (is_null($r->ticket_id)) continue;

            $clients_raw[$cid]['total']++;
            if (isset($status_map[$r->status])) {
                $clients_raw[$cid][$status_map[$r->status]]++;
            }
        }

        usort($clients_raw, fn($a, $b) => $b['total'] - $a['total']);

        $data['clients'] = array_values($clients_raw);
        $this->load->view('dashboard/project_cards', $data);
    }
}
