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

use Altum\Models\Plan;

defined('ALTUMCODE') || die();

class AdminUserView extends Controller {

    public function index() {

        $user_id = (isset($this->params[0])) ? (int) $this->params[0] : null;

        /* Check if user exists */
        if(!$user = db()->where('user_id', $user_id)->getOne('users')) {
            redirect('admin/users');
        }

        if(!empty($_POST)) {
            // Check CSRF token
            \Altum\Csrf::check();

            $update_data = [];

            if(isset($_POST['status'])) {
                $update_data['status'] = (int)$_POST['status'];
                $msg = 'User status updated successfully!';
            } else {
                $update_data['extra_report_views'] = isset($_POST['extra_report_views']) ? (int)$_POST['extra_report_views'] : 0;
                $update_data['extra_pdf_downloads'] = isset($_POST['extra_pdf_downloads']) ? (int)$_POST['extra_pdf_downloads'] : 0;
                $msg = 'User extra credits updated successfully!';
            }

            db()->where('user_id', $user_id)->update('users', $update_data);

            \Altum\Alerts::add_success($msg);
            redirect('admin/user-view/' . $user_id);
        }

        /* Get widget stats */
        $websites = db()->where('user_id', $user_id)->getValue('websites', 'count(`website_id`)');
        $audits = db()->where('user_id', $user_id)->getValue('audits', 'count(`audit_id`)');
        $archived_audits = db()->where('user_id', $user_id)->getValue('archived_audits', 'count(`archived_audit_id`)');
        $domains = db()->where('user_id', $user_id)->getValue('domains', 'count(`domain_id`)');
        $notification_handlers = db()->where('user_id', $user_id)->getValue('notification_handlers', 'count(`notification_handler_id`)');
        $payments = 0;
        if(in_array(settings()->license->type, ['Extended License', 'extended'])) {
            try {
                $payments = db()->where('user_id', $user_id)->getValue('payments', 'count(`id`)');
            } catch (\Exception $exception) {
                /* Keep admin user view available even if optional payment tables are missing. */
                $payments = 0;
            }
        }

        /* Get the current plan details */
        $user->plan = (new Plan())->get_plan_by_id($user->plan_id);

        /* Check if its a custom plan */
        if($user->plan_id == 'custom') {
            $user->plan->settings = $user->plan_settings;
        }

        $user->billing = json_decode($user->billing ?? '');

        /* Main View */
        $data = [
            'user' => $user,
            'websites' => $websites,
            'audits' => $audits,
            'archived_audits' => $archived_audits,
            'domains' => $domains,
            'notification_handlers' => $notification_handlers,
            'payments' => $payments,
        ];

        $view = new \Altum\View('admin/user-view/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
