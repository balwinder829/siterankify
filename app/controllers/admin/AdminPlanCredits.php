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
use Altum\Models\Plan;

defined('ALTUMCODE') || die();

class AdminPlanCredits extends Controller {

    public function index() {

        $plans = db()->orderBy('`order`', 'ASC')->get('plans');

        foreach($plans as $plan) {
            $plan->settings = json_decode($plan->settings ?? '');
            $plan->additional_settings = json_decode($plan->additional_settings ?? '');
            $plan->translations = json_decode($plan->translations ?? '');
            $plan->prices = json_decode($plan->prices);
        }

        /* Set for exporting */
        $all_plans = $plans;
        if(isset(settings()->plan_guest)) $all_plans[] = settings()->plan_guest;
        $all_plans[] = settings()->plan_free;
        $all_plans[] = settings()->plan_custom;

        /* Main View */
        $data = [
            'plans' => $all_plans,
        ];

        $view = new \Altum\View('admin/plan-credits/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function update() {

        if(empty($_POST)) {
            redirect('admin/plan-credits');
        }

        $plan_id = $_POST['plan_id'];

        //ALTUMCODE:DEMO if(DEMO) { Alerts::add_error('This command is blocked on the demo.'); redirect('admin/plan-credits'); }

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            redirect('admin/plan-credits');
        }

        $audits_limit = (int) $_POST['audits_per_month_limit'];
        $views_limit = (int) $_POST['report_views_limit'];
        $pdf_limit = (int) $_POST['pdf_downloads_limit'];
        $update_users = isset($_POST['submit_update_users_plan_settings']) || isset($_POST['update_users_plan_settings']);

        switch($plan_id) {

            case 'guest':
            case 'free':
            case 'custom':
                $setting_key = 'plan_' . $plan_id;
                
                $plan = settings()->{$setting_key};
                $plan->settings->audits_per_month_limit = $audits_limit;
                $plan->settings->report_views_limit = $views_limit;
                $plan->settings->pdf_downloads_limit = $pdf_limit;

                $setting_value = json_encode($plan);

                db()->where('`key`', $setting_key)->update('settings', ['value' => $setting_value]);

                /* Clear the cache */
                cache()->deleteItem('settings');

                if($update_users) {
                    $plan_settings = json_encode($plan->settings);
                    db()->where('plan_id', $plan_id)->update('users', ['plan_settings' => $plan_settings]);
                    cache()->clear();
                }

                Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $plan->name . '</strong>'));
                break;

            default:
                $plan_id = (int) $plan_id;
                
                if(!$plan = db()->where('plan_id', $plan_id)->getOne('plans')) {
                    redirect('admin/plan-credits');
                }

                $plan_settings = json_decode($plan->settings, true);
                $plan_settings['audits_per_month_limit'] = $audits_limit;
                $plan_settings['report_views_limit'] = $views_limit;
                $plan_settings['pdf_downloads_limit'] = $pdf_limit;
                
                $settings_encoded = json_encode($plan_settings);

                db()->where('plan_id', $plan_id)->update('plans', ['settings' => $settings_encoded]);

                /* Clear the cache */
                cache()->deleteItem('plans');

                if($update_users) {
                    db()->where('plan_id', $plan_id)->update('users', ['plan_settings' => $settings_encoded]);
                    cache()->clear();
                }

                Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $plan->name . '</strong>'));
                break;
        }

        redirect('admin/plan-credits');
    }
}
