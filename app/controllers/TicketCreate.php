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

class TicketCreate extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        if(!empty($_POST)) {
            $_POST['subject'] = trim(query_clean($_POST['subject']));
            $_POST['message'] = trim(query_clean($_POST['message']));
            $_POST['ticket_category_id'] = empty($_POST['ticket_category_id']) ? null : (int) $_POST['ticket_category_id'];
            $_POST['priority'] = in_array($_POST['priority'], ['low', 'medium', 'high']) ? $_POST['priority'] : 'low';

            /* Check for any errors */
            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(mb_strlen($_POST['subject']) < 1 || mb_strlen($_POST['subject']) > 128) {
                Alerts::add_field_error('subject', l('global.error_message.invalid_length'));
            }

            if(mb_strlen($_POST['message']) < 1 || mb_strlen($_POST['message']) > 10000) {
                Alerts::add_field_error('message', l('global.error_message.invalid_length'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Database query */
                $ticket_id = db()->insert('tickets', [
                    'user_id' => $this->user->user_id,
                    'ticket_category_id' => $_POST['ticket_category_id'],
                    'subject' => $_POST['subject'],
                    'status' => 'open',
                    'priority' => $_POST['priority'],
                    'last_reply' => 'user',
                    'datetime' => \Altum\Date::$date,
                    'last_datetime' => \Altum\Date::$date,
                ]);

                /* Insert the first message */
                db()->insert('tickets_replies', [
                    'ticket_id' => $ticket_id,
                    'user_id' => $this->user->user_id,
                    'message' => $_POST['message'],
                    'datetime' => \Altum\Date::$date,
                ]);

                /* Set a nice success message */
                Alerts::add_success(l('global.success_message.create2'));

                redirect('tickets');
            }
        }

        /* Get categories */
        $categories = db()->where('is_enabled', 1)->get('tickets_categories');

        /* Prepare the view */
        $data = [
            'categories' => $categories,
            'values' => [
                'subject' => $_POST['subject'] ?? '',
                'message' => $_POST['message'] ?? '',
            ]
        ];

        $view = new \Altum\View('ticket-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
