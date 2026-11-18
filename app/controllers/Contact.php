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
use Altum\Captcha;

defined('ALTUMCODE') || die();

class Contact extends Controller {

    public function index() {

        $can_email = settings()->email_notifications->contact && !empty(settings()->email_notifications->emails);

        /* Determine if DB storage is available */
        $table_check = database()->query("SHOW TABLES LIKE 'contact_submissions'");
        $can_store = $table_check && $table_check->num_rows;

        /* Only block submissions if we cannot email and we cannot store */
        if(!$can_email && !$can_store && !empty($_POST)) {
            if(isset($_POST['ajax'])) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Contact system is currently disabled.'
                ]);
                die();
            }

            Alerts::add_error('Contact system is currently disabled.');
        }

        /* Initiate captcha */
        $captcha = new Captcha();

        if(!empty($_POST)) {
            $_POST['name'] = input_clean($_POST['name'], 64);
            $_POST['email'] = input_clean_email($_POST['email'] ?? '');
            $_POST['subject'] = input_clean($_POST['subject'], 128);
            $_POST['message'] = input_clean($_POST['message'], 2048);

            //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Check for any errors */
            $required_fields = ['name', 'email', 'subject', 'message'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || trim($_POST[$field]) === '') {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!Alerts::has_field_errors('email') && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                Alerts::add_field_error('email', l('global.error_message.invalid_email'));
            }

            if(!Alerts::has_field_errors('name') && mb_strlen($_POST['name']) < 2) {
                Alerts::add_field_error('name', l('global.error_message.short_value'));
            }

            if(!Alerts::has_field_errors('message') && mb_strlen($_POST['message']) < 10) {
                Alerts::add_field_error('message', l('global.error_message.short_value'));
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(settings()->captcha->contact_is_enabled && !$captcha->is_valid()) {
                Alerts::add_field_error('captcha', l('global.error_message.invalid_captcha'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Save submission (non-blocking if table is missing) */
                $source = input_clean($_POST['source'] ?? 'contact', 32);

                if($can_store) {
                    db()->insert('contact_submissions', [
                        'user_id' => is_logged_in() ? $this->user->user_id : null,
                        'source' => $source,
                        'name' => $_POST['name'],
                        'email' => $_POST['email'],
                        'subject' => $_POST['subject'],
                        'message' => $_POST['message'],
                        'ip' => get_ip(),
                        'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? input_clean($_SERVER['HTTP_USER_AGENT'], 512) : null,
                        'is_read' => 0,
                        'datetime' => get_date(),
                    ]);
                }

                $email_was_sent = null;
                $email_warning = null;

                /* Prepare & send the email (optional) */
                if($can_email) {
                    $email_template = get_email_template(
                        [
                            '{{NAME}}' => str_replace('.', '. ', $_POST['name']),
                            '{{SUBJECT}}' => $_POST['subject'],
                        ],
                        l('global.emails.admin_contact.subject'),
                        [
                            '{{NAME}}' => str_replace('.', '. ', $_POST['name']),
                            '{{EMAIL}}' => $_POST['email'],
                            '{{MESSAGE}}' => $_POST['message'],
                        ],
                        l('global.emails.admin_contact.body')
                    );

                    $email_was_sent = (bool) send_mail(explode(',', settings()->email_notifications->emails), $email_template->subject, $email_template->body, [], $_POST['email']);
                }

                if(!$can_email || empty(settings()->smtp->host)) {
                    $email_warning = l('contact.email_warning.smtp_not_configured');
                } elseif($email_was_sent === false) {
                    $email_warning = l('contact.email_warning.smtp_failed');
                }

                /* Send webhook notification if needed */
                if(settings()->webhooks->contact) {
                    fire_and_forget('post', settings()->webhooks->contact, [
                        'name' => $_POST['name'],
                        'email' => $_POST['email'],
                        'subject' => $_POST['subject'],
                        'message' => $_POST['message'],
                        'datetime' => get_date(),
                    ]);
                }

                /* Set a nice success message */
                if(isset($_POST['ajax'])) {
                    $success_message = l('contact.success_message');
                    if($email_warning) {
                        $success_message .= ' ' . $email_warning;
                    }

                    echo json_encode([
                        'status' => 'success',
                        'message' => $success_message
                    ]);
                    die();
                }

                Alerts::add_success(l('contact.success_message'));
                if($email_warning) {
                    Alerts::add_info($email_warning);
                }

                redirect('contact');
            } else {
                /* If there are errors and it's an AJAX request, return the errors */
                if(isset($_POST['ajax'])) {
                    $errors = [];
                    $field_label_map = [
                        'name' => l('global.name'),
                        'email' => l('global.email'),
                        'subject' => l('contact.subject'),
                        'message' => l('contact.message'),
                        'captcha' => l('global.captcha'),
                    ];

                    $alerts = session_get('error', []);
                    foreach($alerts as $messages) {
                        foreach($messages as $message) {
                            $errors[] = $message;
                        }
                    }

                    $field_errors = session_get('field_errors', []);
                    foreach($field_errors as $field => $messages) {
                        $label = $field_label_map[$field] ?? ucfirst(str_replace('_', ' ', $field));
                        foreach($messages as $message) {
                            $errors[] = $label . ': ' . $message;
                        }
                    }

                    $errors = array_values(array_unique($errors));

                    /* Clear after reading so they don't persist */
                    session_unset_key('error');
                    session_unset_key('field_errors');

                    echo json_encode([
                        'status' => 'error',
                        'message' => $errors,
                        'field_errors' => $field_errors
                    ]);
                    die();
                }
            }
        }

        $values = [
            'name' => is_logged_in() ? $this->user->name : ($_POST['name'] ??  ''),
            'email' => is_logged_in() ? $this->user->email : ($_POST['email'] ??  ''),
            'subject' => $_POST['subject'] ?? $_GET['subject'] ?? '',
            'message' => $_POST['message'] ?? $_GET['message'] ?? '',
        ];

        /* Set meta tags */
        $system_page_seo = settings()->system_pages_seo->contact ?? null;
        if($system_page_seo->title) \Altum\Title::set($system_page_seo->title);
        if($system_page_seo->description) \Altum\Meta::$description = $system_page_seo->description;
        if($system_page_seo->keywords) \Altum\Meta::$keywords = $system_page_seo->keywords;
        \Altum\Meta::set_robots(($system_page_seo->is_indexable ?? true) ? 'index, follow' : 'noindex, nofollow');

        /* Prepare the view */
        $data = [
            'captcha' => $captcha,
            'values' => $values,
            'system_page_seo' => $system_page_seo,
        ];

        $view = new \Altum\View('contact/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}


