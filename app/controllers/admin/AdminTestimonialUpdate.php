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

class AdminTestimonialUpdate extends Controller {

    public function index() {

        $testimonial_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$testimonial = db()->where('testimonial_id', $testimonial_id)->getOne('testimonials')) {
            redirect('admin/testimonials');
        }

        if(!empty($_POST)) {
            /* Filter some of the variables */
            $_POST['name'] = input_clean($_POST['name'], 256);
            $_POST['attribute'] = input_clean($_POST['attribute'], 256);
            $_POST['text'] = input_clean($_POST['text']);

            /* Check for any errors */
            $required_fields = ['name', 'text'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || trim($_POST[$field]) === '') {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            /* Image Upload */
            $image_new_name = \Altum\Uploads::process_upload($testimonial->image, 'testimonials', 'image', 'image_remove', null);

            /* If there are no errors, continue */
            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Database query */
                db()->where('testimonial_id', $testimonial->testimonial_id)->update('testimonials', [
                    'name' => $_POST['name'],
                    'attribute' => $_POST['attribute'],
                    'text' => $_POST['text'],
                    'image' => $image_new_name ?? null,
                ]);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $_POST['name'] . '</strong>'));

                redirect('admin/testimonials');
            }

        }

        $data = [
            'testimonial' => $testimonial,
        ];

        /* Main View */
        $view = new \Altum\View('admin/testimonial-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));
    }

}
