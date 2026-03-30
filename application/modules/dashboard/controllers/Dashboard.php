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
        $user_info = $this->db->get_where('users', ['id_user' => $user_id])->row();
        $can_export = ($user_info->is_ba == 1 || $user_id == 7) ? 1 : 0;

        $data = [
            'clients'       => $clients,
            'client_count'  => count($clients),
            'can_export'    => $can_export,
            'is_programmer' => (int)($user_info->is_programmer ?? 0),
            'is_ba'         => (int)($user_info->is_ba ?? 0),
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
            'manhour_data'  => $this->dashboard_model->get_manhour_data($client_id, $date_from, $date_to)
        ];

        echo json_encode($result);
    }

    public function get_ticket_detail()
    {
        $client_id  = $this->input->get('client_id');
        $date       = $this->input->get('date');
        $category   = $this->input->get('category') ?? 'all';
        $date_from  = $this->input->get('date_from'); // ← tambah ini

        $data = [
            'tickets'   => $this->dashboard_model->get_tickets_by_date($client_id, $date, $category, $date_from),
            'date'      => $date,
            'category'  => $category,
            'client_id' => $client_id,
            'is_carry_over' => ($date === 'carry_over'),
        ];

        $this->template->render('ticket_detail_content', $data);
    }

    public function print_weekly_report()
    {
        $client_id  = $this->input->get('client_id');
        $date_from  = $this->input->get('date_from');
        $date_to    = $this->input->get('date_to');

        $daily_data        = $this->dashboard_model->get_daily_data($client_id, $date_from, $date_to);
        $open_extended     = $this->dashboard_model->get_open_data_extended($client_id, $date_from, $date_to);
        $total_tickets     = $this->dashboard_model->get_total_tickets($client_id, $date_from, $date_to);
        $all_tickets       = $this->dashboard_model->get_tickets_by_date_range($client_id, $date_from, $date_to);
        $open_carry_over   = $this->dashboard_model->get_open_tickets_extended($client_id, $date_from, $date_to);
        $client_info       = $this->dashboard_model->get_client_info($client_id);

        $bugs_open   = 0;
        $issues_open = 0;

        foreach ($open_extended['bugs_open'] as $row) {
            $bugs_open += $row->total;
        }
        foreach ($open_extended['issues_open'] as $row) {
            $issues_open += $row->total;
        }

        $data = [
            'client_info'     => $client_info,
            'date_from'       => $date_from,
            'date_to'         => $date_to,
            'daily_data'      => $daily_data,
            'open_extended'   => $open_extended,
            'total_tickets'   => $total_tickets,
            'all_tickets'     => $all_tickets,
            'open_carry_over' => $open_carry_over, // <-- baru
            'bugs_open'       => $bugs_open,
            'issues_open'     => $issues_open
        ];

        $this->load->view('print_weekly_report', $data);
    }

    public function print_monthly_report()
    {
        $client_id  = $this->input->get('client_id');
        $date_from  = $this->input->get('date_from');
        $date_to    = $this->input->get('date_to');

        $daily_data    = $this->dashboard_model->get_daily_data($client_id, $date_from, $date_to);
        $total_tickets = $this->dashboard_model->get_total_tickets($client_id, $date_from, $date_to);
        $all_tickets   = $this->dashboard_model->get_tickets_by_date_range($client_id, $date_from, $date_to);
        $client_info   = $this->dashboard_model->get_client_info($client_id);
        $manhour_data = $this->dashboard_model->get_manhour_data($client_id, $date_from, $date_to);

        $mh_plan_map   = [];
        $mh_actual_map = [];

        foreach ($manhour_data['plan'] as $item) {
            $mh_plan_map[$item->date] = (float) $item->total;
        }
        foreach ($manhour_data['actual'] as $item) {
            $mh_actual_map[$item->date] = (float) $item->total;
        }


        $bugs_open   = 0;
        $issues_open = 0;

        foreach ($all_tickets as $ticket) {
            if ($ticket->status == 0) {
                if (in_array(strtolower($ticket->sub_category_name), ['bugs program', 'bugs konsep'])) {
                    $bugs_open++;
                } else if (strtolower($ticket->sub_category_name) == 'user issue') {
                    $issues_open++;
                }
            }
        }

        // Group tickets per minggu
        $weeks = [];
        $first_date = new DateTime($date_from);
        $last_date  = new DateTime($date_to);

        $current = clone $first_date;
        $week_num = 1;

        while ($current <= $last_date) {
            $week_start = clone $current;
            $week_end   = clone $current;
            $week_end->modify('+6 days');
            if ($week_end > $last_date) $week_end = clone $last_date;

            $weeks[] = [
                'week_num'   => $week_num,
                'date_start' => $week_start->format('Y-m-d'),
                'date_end'   => $week_end->format('Y-m-d'),
                'bugs'       => 0,
                'issues'     => 0,
                'total'      => 0,
            ];

            $current->modify('+7 days');
            $week_num++;
        }

        // Mapping daily_data ke map
        $bugs_map   = [];
        $issues_map = [];

        foreach ($daily_data['bugs'] as $item) {
            $bugs_map[$item->date] = (int) $item->total;
        }
        foreach ($daily_data['issues'] as $item) {
            $issues_map[$item->date] = (int) $item->total;
        }

        // Sum per minggu
        foreach ($weeks as &$week) {
            $start = new DateTime($week['date_start']);
            $end   = new DateTime($week['date_end']);
            $d     = clone $start;

            $week['man_hour_plan']   = 0;
            $week['man_hour_actual'] = 0;

            while ($d <= $end) {
                $key = $d->format('Y-m-d');
                $week['bugs']   += $bugs_map[$key]   ?? 0;
                $week['issues'] += $issues_map[$key] ?? 0;
                $week['man_hour_plan']   += $mh_plan_map[$key]   ?? 0;
                $week['man_hour_actual'] += $mh_actual_map[$key] ?? 0;
                $d->modify('+1 day');
            }

            $week['total'] = $week['bugs'] + $week['issues'];
        }
        unset($week);

        $data = [
            'client_info'   => $client_info,
            'date_from'     => $date_from,
            'date_to'       => $date_to,
            'daily_data'    => $daily_data,
            'total_tickets' => $total_tickets,
            'bugs_open'     => $bugs_open,
            'issues_open'   => $issues_open,
            'weeks'         => $weeks,
            'manhour_data' => $manhour_data,
        ];

        $this->load->view('print_monthly_report', $data);
    }

    public function get_my_priorities()
    {
        $user_id = $this->auth->user_id();
        $priorities = $this->dashboard_model->get_my_priorities($user_id);
        echo json_encode($priorities);
    }

    public function get_carry_over_count()
    {
        $client_id  = $this->input->get('client_id');
        $date_from  = $this->input->get('date_from');

        $extended_from = date('Y-m-d', strtotime($date_from . ' -90 days'));

        // Count bugs carry over (open, sebelum date_from)
        $bugs_carry = $this->db
            ->from('helpdesk h')
            ->join('helpdesk_sub_category hsc', 'hsc.id = h.sub_category_id', 'left')
            ->where('h.client_id', $client_id)
            ->where('h.is_delete', 0)
            ->where('h.status', 0)
            ->where_in('hsc.sub_name', ['bugs program', 'bugs konsep'])
            ->where('DATE(h.create_date) >=', $extended_from)
            ->where('DATE(h.create_date) <', $date_from)
            ->count_all_results();

        // Count issues carry over (open, sebelum date_from)
        $issues_carry = $this->db
            ->from('helpdesk h')
            ->join('helpdesk_sub_category hsc', 'hsc.id = h.sub_category_id', 'left')
            ->where('h.client_id', $client_id)
            ->where('h.is_delete', 0)
            ->where('h.status', 0)
            ->where('hsc.sub_name', 'user issue')
            ->where('DATE(h.create_date) >=', $extended_from)
            ->where('DATE(h.create_date) <', $date_from)
            ->count_all_results();

        echo json_encode([
            'bugs'   => $bugs_carry,
            'issues' => $issues_carry,
            'total'  => $bugs_carry + $issues_carry,
        ]);
    }
}
