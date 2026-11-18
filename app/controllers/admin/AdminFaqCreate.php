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

class AdminFaqCreate extends Controller {

    public function index() {

        if(!empty($_POST)) {
            /* Filter some of the variables */
            $_POST['question'] = input_clean($_POST['question'], 256);
            $_POST['answer'] = input_clean($_POST['answer']);

            /* Check for any errors */
            $required_fields = ['question', 'answer'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || trim($_POST[$field]) === '') {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            /* If there are no errors, continue */
            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Database query */
                db()->insert('faqs', [
                    'question' => $_POST['question'],
                    'answer' => $_POST['answer'],
                    'pages' => !empty($_POST['pages']) ? json_encode($_POST['pages']) : null,
                    'datetime' => get_date(),
                ]);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['question'] . '</strong>'));

                redirect('admin/faqs');
            }

        }

        /* Set default values */
        $values = [
            'question' => $_POST['question'] ?? '',
            'answer' => $_POST['answer'] ?? '',
            'pages' => $_POST['pages'] ?? [],
        ];

        $available_pages = [
            'index' => 'Home Page',
            'plan' => 'Pricing / Plan Page',
        ];

        $custom_pages_result = database()->query("SELECT * FROM `pages` WHERE `is_published` = 1 ORDER BY `title` ASC");
        while($row = $custom_pages_result->fetch_object()) {
            $available_pages[$row->url] = $row->title;
        }

        $data = [
            'values' => $values,
            'available_pages' => $available_pages,
        ];

        /* Main View */
        $view = new \Altum\View('admin/faq-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));
    }

}
