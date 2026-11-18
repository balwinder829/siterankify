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

defined('ALTUMCODE') || die();

class AdminTicketsCategories extends Controller {

    public function index() {

        /* Main View */
        $view = new \Altum\View('admin/tickets-categories/index', (array) $this);

        $this->add_view_content('content', $view->run());

    }

    public function delete() {

        \Altum\Authentication::guard('admin');

        if(empty($_POST)) {
            redirect('admin/tickets-categories');
        }

        $ticket_category_id = (int) $_POST['ticket_category_id'];

        if(!\Altum\Csrf::check()) {
            \Altum\Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$ticket_category = db()->where('ticket_category_id', $ticket_category_id)->getOne('tickets_categories')) {
            redirect('admin/tickets-categories');
        }

        if(!\Altum\Alerts::has_field_errors() && !\Altum\Alerts::has_errors()) {

            /* Delete the record */
            db()->where('ticket_category_id', $ticket_category_id)->delete('tickets_categories');

            /* Set a nice success message */
            \Altum\Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $ticket_category->name . '</strong>'));

        }

        redirect('admin/tickets-categories');
    }

}
