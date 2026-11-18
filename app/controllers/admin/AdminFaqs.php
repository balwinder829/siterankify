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

class AdminFaqs extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters([], ['question', 'answer'], ['faq_id', 'datetime']));
        $filters->set_default_order_by('faq_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `faqs` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/faqs?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $faqs = [];
        $faqs_result = database()->query("
            SELECT
                *
            FROM
                `faqs`
            WHERE
                1 = 1
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
                  
            {$paginator->get_sql_limit()}
        ");
        while($row = $faqs_result->fetch_object()) {
            $row->pages = json_decode($row->pages ?? '[]') ?? [];
            $faqs[] = $row;
        }

        $available_pages = [
            'index' => l('index.title'),
            'plan' => l('plan.breadcrumb'),
        ];

        $custom_pages_result = database()->query("SELECT * FROM `pages` WHERE `is_published` = 1 ORDER BY `title` ASC");
        while($row = $custom_pages_result->fetch_object()) {
            $available_pages[$row->url] = $row->title;
        }

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'faqs' => $faqs,
            'paginator' => $paginator,
            'pagination' => $pagination,
            'filters' => $filters,
            'available_pages' => $available_pages,
        ];

        $view = new \Altum\View('admin/faqs/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('admin/faqs');
        }

        if(empty($_POST['selected'])) {
            redirect('admin/faqs');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/faqs');
        }

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            set_time_limit(0);

            session_write_close();

            switch($_POST['type']) {
                case 'delete':

                    foreach($_POST['selected'] as $id) {
                        $this->delete_faq($id);
                    }

                    break;
            }

            session_start();

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/faqs');
    }

    public function delete() {

        $faq_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$faq = db()->where('faq_id', $faq_id)->getOne('faqs', ['faq_id', 'question'])) {
            redirect('admin/faqs');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            $this->delete_faq($faq->faq_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $faq->question . '</strong>'));

        }

        redirect('admin/faqs');
    }

    private function delete_faq($faq_id) {
        /* Delete record from database */
        db()->where('faq_id', $faq_id)->delete('faqs');
    }

}
