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

class AdminTestimonials extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters([], ['name', 'attribute', 'text'], ['testimonial_id', 'datetime']));
        $filters->set_default_order_by('testimonial_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `testimonials` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/testimonials?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $testimonials = [];
        $testimonials_result = database()->query("
            SELECT
                *
            FROM
                `testimonials`
            WHERE
                1 = 1
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
                  
            {$paginator->get_sql_limit()}
        ");
        while($row = $testimonials_result->fetch_object()) {
            $testimonials[] = $row;
        }

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'testimonials' => $testimonials,
            'paginator' => $paginator,
            'pagination' => $pagination,
            'filters' => $filters,
        ];

        $view = new \Altum\View('admin/testimonials/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('admin/testimonials');
        }

        if(empty($_POST['selected'])) {
            redirect('admin/testimonials');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/testimonials');
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
                        $this->delete_testimonial($id);
                    }

                    break;
            }

            session_start();

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/testimonials');
    }

    public function delete() {

        $testimonial_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$testimonial = db()->where('testimonial_id', $testimonial_id)->getOne('testimonials', ['testimonial_id', 'name'])) {
            redirect('admin/testimonials');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            $this->delete_testimonial($testimonial->testimonial_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $testimonial->name . '</strong>'));

        }

        redirect('admin/testimonials');
    }

    private function delete_testimonial($testimonial_id) {
        $testimonial = db()->where('testimonial_id', $testimonial_id)->getOne('testimonials', ['testimonial_id', 'image']);

        if(!$testimonial) return;

        /* Delete client avatar file if exists */
        if(!empty($testimonial->image)) {
            \Altum\Uploads::delete_uploaded_file($testimonial->image, 'testimonials');
        }

        /* Delete record from database */
        db()->where('testimonial_id', $testimonial_id)->delete('testimonials');
    }

}
