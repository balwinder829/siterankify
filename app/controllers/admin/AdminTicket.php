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

class AdminTicket extends Controller {

    public function index() {

        \Altum\Authentication::guard('admin');

        $ticket_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Check for status update via URL */
        if(isset($this->params[1]) && $this->params[1] == 'status' && isset($this->params[2])) {
            $status = in_array($this->params[2], ['open', 'pending', 'resolved', 'closed']) ? $this->params[2] : 'open';
            
            db()->where('ticket_id', $ticket_id)->update('tickets', ['status' => $status]);
            
            Alerts::add_success(l('global.success_message.update2'));
            redirect('admin/ticket/' . $ticket_id);
        }

        /* Get the ticket with user and category details */
        $ticket = database()->query("
            SELECT `tickets`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`, `users`.`avatar` AS `user_avatar`, `tickets_categories`.`name` AS `category_name`
            FROM `tickets`
            LEFT JOIN `users` ON `tickets`.`user_id` = `users`.`user_id`
            LEFT JOIN `tickets_categories` ON `tickets`.`ticket_category_id` = `tickets_categories`.`ticket_category_id`
            WHERE `tickets`.`ticket_id` = {$ticket_id}
        ")->fetch_object();

        if(!$ticket) {
            redirect('admin/tickets');
        }

        /* Handle Reply Submission */
        if(!empty($_POST)) {
            
            if(isset($_POST['message'])) {
                $_POST['message'] = trim(query_clean($_POST['message']));

                /* Check for any errors */
                if(!\Altum\Csrf::check()) {
                    Alerts::add_error(l('global.error_message.invalid_csrf_token'));
                }

                if(mb_strlen($_POST['message']) < 1 || mb_strlen($_POST['message']) > 10000) {
                    Alerts::add_field_error('message', l('global.error_message.invalid_length'));
                }

                if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                    /* Database query */
                    db()->insert('tickets_replies', [
                        'ticket_id' => $ticket_id,
                        'user_id' => $this->user->user_id,
                        'message' => $_POST['message'],
                        'datetime' => \Altum\Date::$date,
                    ]);

                    /* Determine new status */
                    $status = isset($_POST['close_ticket']) ? 'closed' : 'pending';

                    /* Update the ticket */
                    db()->where('ticket_id', $ticket_id)->update('tickets', [
                        'status' => $status,
                        'last_reply' => 'admin',
                        'last_datetime' => \Altum\Date::$date,
                    ]);

                    /* Set a nice success message */
                    Alerts::add_success(l('global.success_message.create2'));

                    redirect('admin/ticket/' . $ticket_id);
                }
            }
        }

        /* Get replies with user details */
        $ticket_replies = [];
        $replies_result = database()->query("
            SELECT `tickets_replies`.*, `users`.`name`, `users`.`email`, `users`.`avatar`, `users`.`type`
            FROM `tickets_replies`
            LEFT JOIN `users` ON `tickets_replies`.`user_id` = `users`.`user_id`
            WHERE `tickets_replies`.`ticket_id` = {$ticket_id}
            ORDER BY `tickets_replies`.`ticket_reply_id` ASC
        ");
        while($row = $replies_result->fetch_object()) {
            $ticket_replies[] = $row;
        }

        /* Prepare the view */
        $data = [
            'ticket' => $ticket,
            'ticket_replies' => $ticket_replies,
        ];

        $view = new \Altum\View('admin/ticket/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
