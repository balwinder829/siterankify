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

class AdminSystemPageUpdate extends Controller {

    public function index() {

        $slug = isset($this->params[0]) ? input_clean($this->params[0]) : null;

        $system_pages_seo = (array) settings()->system_pages_seo;

        /* Check if resource exists */
        if(!array_key_exists($slug, $system_pages_seo)) {
            redirect('admin/pages');
        }

        $page_seo = (object) $system_pages_seo[$slug];

        if(!empty($_POST)) {
            /* Filter some of the variables */
            $title = input_clean($_POST['title'], 256);
            $description = input_clean($_POST['description'], 256);
            $keywords = input_clean($_POST['keywords'], 256);
            $content = $_POST['content'];
            $location = input_clean($_POST['location'], 256);
            $is_indexable = (int) isset($_POST['is_indexable']);

            //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            /* Image upload */
            $image = $page_seo->image ?? null;
            if(!empty($_FILES['image']['name']) || isset($_POST['image_remove'])) {
                $image = \Altum\Uploads::process_upload($image, 'pages', 'image', 'image_remove', null);
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $system_pages_seo[$slug] = [
                    'title' => $title,
                    'description' => $description,
                    'keywords' => $keywords,
                    'content' => $content,
                    'location' => $location,
                    'image' => $image,
                    'is_indexable' => $is_indexable,
                ];

                $value = json_encode($system_pages_seo);

                /* Update the database */
                db()->where('`key`', 'system_pages_seo')->update('settings', ['value' => $value]);

                /* Clear the cache */
                cache()->deleteItem('settings');

                /* Set a nice success message */
                Alerts::add_success(l('global.success_message.update2'));

                /* Refresh the page */
                redirect('admin/system-page-update/' . $slug);
            }
        }

        /* Set the title */
        \Altum\Title::set(l('admin_page_update.title'));

        /* Main View */
        $data = [
            'slug' => $slug,
            'page_seo' => $page_seo,
        ];

        $view = new \Altum\View('admin/system-page-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
