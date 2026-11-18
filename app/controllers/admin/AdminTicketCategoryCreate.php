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

class AdminTicketCategoryCreate extends Controller {

    public function index() {

        if(!empty($_POST)) {
            /* Filter some the variables */
            $_POST['name'] = trim(query_clean($_POST['name']));

            // Check for any errors
            if(!\Altum\Csrf::check()) {
                \Altum\Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!\Altum\Alerts::has_field_errors() && !\Altum\Alerts::has_errors()) {

                /* Database query */
                db()->insert('tickets_categories', [
                    'name' => $_POST['name'],
                    'datetime' => \Altum\Date::$date,
                ]);

                /* Set a nice success message */
                \Altum\Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['name'] . '</strong>'));

                redirect('admin/tickets-categories');
            }
        }

        /* Main View */
        $view = new \Altum\View('admin/ticket-category-create/index', (array) $this);

        $this->add_view_content('content', $view->run());

    }

}
