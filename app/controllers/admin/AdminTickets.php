<?php
/*
 * Copyright (c) 2025 AltumCode (https://altumcode.com/)
 *
 * This software is licensed exclusively by AltumCode and is sold only via https://altumcode.com/.
 * Unauthorized distribution, modification, or use of this software without a valid license is not permitted and may be subject to applicable legal actions.
 *
 * 🌍 View all other existing AltumCode projects via https://altumcode.com/
 * 📧 Get in touch for support or general queries via https://altumcode.com/contact
 * 📤 Download the latest version via https://altumcode.com/downloads
 *
 * 🐦 X/Twitter: https://x.com/AltumCode
 * 📘 Facebook: https://facebook.com/altumcode
 * 📸 Instagram: https://instagram.com/altumcode
 */

namespace Altum\Controllers;

use Altum\Alerts;

defined('ALTUMCODE') || die();

class AdminTickets extends Controller {

    public function index() {

        \Altum\Authentication::guard('admin');

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id', 'ticket_category_id', 'status', 'priority'], ['subject'], ['ticket_id', 'datetime', 'last_datetime']));
        $filters->set_default_order_by('ticket_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `tickets` WHERE 1=1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/tickets?' . $filters->get_get() . '&page=%d')));

        /* Get the tickets list */
        $tickets = [];
        $tickets_result = database()->query("
            SELECT `tickets`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`, `tickets_categories`.`name` AS `category_name`
            FROM `tickets`
            LEFT JOIN `users` ON `tickets`.`user_id` = `users`.`user_id`
            LEFT JOIN `tickets_categories` ON `tickets`.`ticket_category_id` = `tickets_categories`.`ticket_category_id`
            WHERE 1=1 {$filters->get_sql_where()} {$filters->get_sql_order_by()} {$paginator->get_sql_limit()}
        ");
        while($row = $tickets_result->fetch_object()) {
            $tickets[] = $row;
        }

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Get categories */
        $categories = db()->get('tickets_categories');

        /* Prepare the view */
        $data = [
            'tickets' => $tickets,
            'total_tickets' => $total_rows,
            'pagination' => $pagination,
            'filters' => $filters,
            'categories' => $categories,
        ];

        $view = new \Altum\View('admin/tickets/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function delete() {

        \Altum\Authentication::guard('admin');

        if(empty($_POST)) {
            redirect('admin/tickets');
        }

        $ticket_id = (int) query_clean($_POST['ticket_id']);

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$ticket = db()->where('ticket_id', $ticket_id)->getOne('tickets', ['ticket_id', 'subject'])) {
            redirect('admin/tickets');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Database query */
            (new \Altum\Models\Ticket())->delete($ticket_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $ticket->subject . '</strong>'));

            redirect('admin/tickets');
        }

        redirect('admin/tickets');
    }
}
