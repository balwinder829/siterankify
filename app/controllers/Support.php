<?php
namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Captcha;

defined('ALTUMCODE') || die();

class Support extends Controller {

    public function index() {

        $can_email = (bool) settings()->email_notifications->contact;

        /* Determine if DB storage is available */
        $table_check = database()->query("SHOW TABLES LIKE 'contact_submissions'");
        $can_store = $table_check && $table_check->num_rows;

        /* Initiate captcha */
        $captcha = new Captcha();

        if(!empty($_POST)) {
            $_POST['name'] = input_clean($_POST['name'], 64);
            $_POST['email'] = input_clean_email($_POST['email'] ?? '');
            $_POST['subject'] = input_clean($_POST['subject'], 128);
            $_POST['message'] = input_clean($_POST['message'], 2048);

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

                if($can_store) {
                    db()->insert('contact_submissions', [
                        'user_id' => is_logged_in() ? $this->user->user_id : null,
                        'source' => 'support',
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

                if($can_email) {
                    $email_template = get_email_template(
                        [
                            '{{NAME}}' => $_POST['name'],
                            '{{SUBJECT}}' => $_POST['subject'],
                        ],
                        'Support Request: {{SUBJECT}} - {{NAME}}',
                        [
                            '{{NAME}}' => $_POST['name'],
                            '{{EMAIL}}' => $_POST['email'],
                            '{{SUBJECT}}' => $_POST['subject'],
                            '{{MESSAGE}}' => nl2br($_POST['message']),
                            '{{IP}}' => get_ip(),
                            '{{DATE}}' => get_date(),
                            '{{WEBSITE_TITLE}}' => settings()->main->title,
                            '{{ADMIN_LINK}}' => url('admin/contact-submissions'),
                        ],
                        'Hello Administrator,<br /><br />A new support request has been submitted from the <strong>Support Center</strong> on {{WEBSITE_TITLE}}.<br /><br /><table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;"><tr><td style="padding: 5px 0; font-weight: bold; width: 120px;">Name:</td><td style="padding: 5px 0;">{{NAME}}</td></tr><tr><td style="padding: 5px 0; font-weight: bold;">Email:</td><td style="padding: 5px 0;">{{EMAIL}}</td></tr><tr><td style="padding: 5px 0; font-weight: bold;">Subject:</td><td style="padding: 5px 0;">{{SUBJECT}}</td></tr></table><strong>Message:</strong><br /><div style="background: #f9f9f9; padding: 15px; border-radius: 5px; border: 1px solid #eee; margin-top: 10px;">{{MESSAGE}}</div><br /><a href="{{ADMIN_LINK}}" style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">View in Admin Panel</a><br /><br /><hr style="border: 0; border-top: 1px solid #eee;" /><small style="color: #888;">Submission Details:<br />IP Address: {{IP}}<br />Date: {{DATE}}</small>'
                    );

                    $email_was_sent = (bool) send_mail([get_support_email()], $email_template->subject, $email_template->body, [], $_POST['email']);
                }

                if(!$can_email || empty(settings()->smtp->host)) {
                    $email_warning = l('contact.email_warning.smtp_not_configured');
                } elseif($email_was_sent === false) {
                    $email_warning = l('contact.email_warning.smtp_failed');
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

                redirect('support');

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

        $system_page_seo = settings()->system_pages_seo->support ?? null;

        $data = [
            'captcha' => $captcha,
            'values' => $values,
            'system_page_seo' => $system_page_seo,
        ];

        $view = new \Altum\View('support/index', (array) $this);
        $this->add_view_content('content', $view->run($data));
    }
}
