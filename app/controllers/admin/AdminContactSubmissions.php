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

class AdminContactSubmissions extends Controller {

    public function index() {

        /* Soft-check table existence to avoid fatal errors on fresh installs */
        $table_check = database()->query("SHOW TABLES LIKE 'contact_submissions'");
        $table_exists = $table_check && $table_check->num_rows;

        if(!$table_exists) {
            Alerts::add_error('Missing database table <strong>contact_submissions</strong>. Run <code>database/contact_submissions.sql</code> and refresh this page.');
        }

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id', 'source', 'is_read'], ['name', 'email', 'subject', 'message', 'ip'], ['contact_submission_id', 'datetime']));
        $filters->set_default_order_by('contact_submission_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = 0;
        if($table_exists) {
            $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `contact_submissions` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        }

        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/contact-submissions?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $contact_submissions = [];

        if($table_exists) {
            $contact_submissions_result = database()->query("
                SELECT
                    `contact_submissions`.*,
                    `users`.`name` AS `user_name`,
                    `users`.`email` AS `user_email`,
                    `users`.`avatar` AS `user_avatar`
                FROM
                    `contact_submissions`
                LEFT JOIN
                    `users` ON `contact_submissions`.`user_id` = `users`.`user_id`
                WHERE
                    1 = 1
                    {$filters->get_sql_where('contact_submissions')}
                    {$filters->get_sql_order_by('contact_submissions')}

                {$paginator->get_sql_limit()}
            ");

            if($contact_submissions_result) {
                while($row = $contact_submissions_result->fetch_object()) {
                    $contact_submissions[] = $row;
                }
            }
        }

        /* Export handler */
        process_export_json($contact_submissions, ['contact_submission_id', 'user_id', 'source', 'name', 'email', 'subject', 'message', 'ip', 'user_agent', 'is_read', 'datetime']);
        process_export_csv($contact_submissions, ['contact_submission_id', 'user_id', 'source', 'name', 'email', 'subject', 'message', 'ip', 'user_agent', 'is_read', 'datetime']);

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'contact_submissions' => $contact_submissions,
            'paginator' => $paginator,
            'pagination' => $pagination,
            'filters' => $filters,
            'table_exists' => $table_exists,
        ];

        $view = new \Altum\View('admin/contact-submissions/index', (array) $this);

        $this->add_view_content('content', $view->run($data));
    }

    public function bulk() {

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('admin/contact-submissions');
        }

        if(empty($_POST['selected'])) {
            redirect('admin/contact-submissions');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/contact-submissions');
        }

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            set_time_limit(0);
            session_write_close();

            switch($_POST['type']) {
                case 'delete':
                    foreach($_POST['selected'] as $contact_submission_id) {
                        db()->where('contact_submission_id', (int) $contact_submission_id)->delete('contact_submissions');
                    }
                    break;
            }

            session_start();

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));
        }

        redirect('admin/contact-submissions');
    }

    public function delete() {

        $contact_submission_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$contact_submission = db()->where('contact_submission_id', $contact_submission_id)->getOne('contact_submissions')) {
            redirect('admin/contact-submissions');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
            db()->where('contact_submission_id', $contact_submission_id)->delete('contact_submissions');
            Alerts::add_success(l('global.success_message.delete2'));
        }

        redirect('admin/contact-submissions');
    }
}


