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
use Altum\Meta;
use Altum\Models\User;
use Altum\Title;

defined('ALTUMCODE') || die();

class Audit extends Controller {
    public $audit;
    public $audit_user;

    public function index()
    {
        /* Make sure there are no extra URL additions */
        if(isset($this->params[1])) {
            redirect('not-found');
        }

        $audit_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$audit = db()->where('audit_id', $audit_id)->getOne('audits')) {
            redirect('not-found');
        }
        foreach(['data', 'issues', 'settings'] as $key) $audit->{$key} = json_decode($audit->{$key} ?? '');
        \Altum\Helpers\Audit::ensure_pagespeed_scores($audit);

        /* Associate guest audit with logged in user, deduct 1 audit credit & 1 report view credit */
        $guest_created_audits = session_get('guest_created_audits') ?? [];
        $is_guest_created_audit_by_current_session = in_array((int) $audit->audit_id, $guest_created_audits);

        if(is_logged_in() && empty($audit->user_id) && ($audit->uploader_id == md5(get_ip()) || $is_guest_created_audit_by_current_session)) {
            db()->where('audit_id', $audit->audit_id)->update('audits', [
                'user_id' => $this->user->user_id
            ]);
            $audit->user_id = $this->user->user_id;

            /* Deduct 1 audit credit */
            if($this->user->plan_id == 'free') {
                db()->rawQuery("INSERT INTO ip_tracking (ip_md5, type, count, last_datetime) VALUES (?, 'audit_run_account', 1, NOW()) ON DUPLICATE KEY UPDATE count = count + 1, last_datetime = NOW()", [$this->user->user_id]);
            } else {
                db()->where('user_id', $this->user->user_id)->update('users', [
                    'audit_audits_current_month' => db()->inc()
                ]);
            }

            /* Deduct 1 report view credit */
            db()->rawQuery("INSERT INTO ip_tracking (ip_md5, type, count, last_datetime) VALUES (?, 'report_view_account', 1, NOW()) ON DUPLICATE KEY UPDATE count = count + 1, last_datetime = NOW()", [$this->user->user_id]);

            check_and_send_50_percent_notification($this->user);
        }

        /* Public audit */
        if($audit->settings && isset($audit->settings->is_public) && !$audit->settings->is_public) {
            /* Make sure the current user has access */
            if(($audit->uploader_id != md5(get_ip())) && (!$audit->user_id || ($audit->user_id != $this->user->user_id))) {
                redirect('not-found');
            }
        }

        /* Audit */
        $audit->full_url = (isset(\Altum\Router::$data['domain']) ? \Altum\Router::$data['domain']->url : url()) . 'audit/' . $audit_id;

        /* User of the audit */
        $this->audit_user = (new User())->get_user_by_user_id($audit->user_id);

        /* Meta */
        Meta::set_canonical_url($audit->full_url);

        /* Check if the user has access to the page */
        $has_access = !$audit->settings->password || ($audit->settings->password && isset($_COOKIE['password_' . $audit->audit_id]) && $_COOKIE['password_' . $audit->audit_id] == $audit->settings->password);

        /* Do not let the user have password protection if the plan doesn't allow it */
        if($this->audit_user && $this->audit_user->plan_settings && !$this->audit_user->plan_settings->password_protection_is_enabled) {
            $has_access = true;
        }

        /* White label */
        if(settings()->main->white_labeling_is_enabled && $this->audit_user && $this->audit_user->plan_settings && $this->audit_user->plan_settings->white_labeling_is_enabled && \Altum\Router::$controller_key != 'invoice' && \Altum\Router::$path != 'admin') {
            if($this->audit_user->preferences->white_label_title) {
                settings()->main->title = $this->audit_user->preferences->white_label_title;
                Title::initialize(settings()->main->title);
            }

            if($this->audit_user->preferences->white_label_logo_light) {
                settings()->main->logo_light = $this->audit_user->preferences->white_label_logo_light;
                settings()->main->logo_light_full_url = \Altum\Uploads::get_full_url('users') . settings()->main->logo_light;
            }

            if($this->audit_user->preferences->white_label_logo_dark) {
                settings()->main->logo_dark = $this->audit_user->preferences->white_label_logo_dark;
                settings()->main->logo_dark_full_url = \Altum\Uploads::get_full_url('users') . settings()->main->logo_dark;
            }

            if($this->audit_user->preferences->white_label_favicon) {
                settings()->main->favicon = $this->audit_user->preferences->white_label_favicon;
                settings()->main->favicon_full_url = \Altum\Uploads::get_full_url('users') . settings()->main->favicon;
            }
        }

        /* Report Logo */
        $owner_has_logo_permission = $this->audit_user && (
            $this->audit_user->type > 0 || // admin
            $this->audit_user->plan_id == 'free' ||
            in_array($this->audit_user->plan_id, [2, 3]) ||
            ($this->audit_user->plan_id == 1 && !empty($this->audit_user->preferences->has_logo_addon))
        );
        if($owner_has_logo_permission && isset($this->audit_user->preferences->white_label_report_logo) && $this->audit_user->preferences->white_label_report_logo) {
            settings()->main->original_report_logo_full_url = isset(settings()->main->report_logo_full_url) ? settings()->main->report_logo_full_url : null;
            settings()->main->report_logo_full_url = \Altum\Uploads::get_full_url('users') . $this->audit_user->preferences->white_label_report_logo;
        }

        /* Check if the password form is submitted */
        if(!$has_access && !empty($_POST)) {

            /* Check for any errors */
            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!password_verify($_POST['password'], $audit->settings->password)) {
                Alerts::add_field_error('password', l('audits.password.error_message'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Set a cookie */
                setcookie('password_' . $audit->audit_id, $audit->settings->password, time() + 60 * 60 * 24 * 30);

                header('Location: ' . $_SERVER['REQUEST_URI']);
                die();

            }

        }

        /* Display the password form */
        if(!$has_access) {

            /* Set a custom title */
            Title::set(l('audits.password.title'));

            /* Main View */
            $data = [];
            $view = new \Altum\View('audit/password', (array)$this);
            $this->add_view_content('content', $view->run($data));

        }

        /* Show audit */
        else {

            /* Track and check views limit */
            $view_count = 0;
            $view_limit = 10; // Default guest limit
            if(is_logged_in()) {
                $cycle_start = get_user_cycle_start($this->user);

                $track_view = db()
                    ->where('ip_md5', (string)$this->user->user_id)
                    ->where('type', 'report_view_account')
                    ->getOne('ip_tracking');

                if ($track_view) {
                    if ($cycle_start && $track_view->last_datetime < $cycle_start) {
                        db()->where('id', $track_view->id)->update('ip_tracking', ['count' => 0]);
                        $view_count = 0;
                    } else {
                        $view_count = $track_view->count;
                    }
                } else {
                    $view_count = 0;
                }
                
                // Limit based on plan
                if(isset($this->user->plan_settings->report_views_limit)) {
                    $view_limit = $this->user->plan_settings->report_views_limit;
                } else if ($this->user->plan_id == 'free') {
                    $view_limit = 15;
                } else if ($this->user->plan_id == 1) { // Premier Plan
                    $view_limit = 50;
                } else if ($this->user->plan_id == 2) { // Monthly Plan
                    $view_limit = 100;
                } else if ($this->user->plan_id == 3) { // Yearly Plan
                    $view_limit = 120;
                } else {
                    $view_limit = 15;
                }

                if ($view_limit != -1) {
                    $view_limit += (int) ($this->user->extra_report_views ?? 0);
                }
            } else {
                $ip_md5 = md5(get_ip());
                $view_count = db()
                    ->where('ip_md5', $ip_md5)
                    ->where('type', 'report_view_ip')
                    ->getValue('ip_tracking', '`count`') ?? 0;
                $view_limit = settings()->main->guest_report_views_limit ?? 10;
            }

            /* Get archived audits data */
            $archived_audits = $audit->total_refreshes > 0 ?
                db()->where('audit_id', $audit_id)->orderBy('`archived_audit_id`', 'DESC')->get('archived_audits', 30, ['archived_audit_id', 'score', 'datetime'])
                : [];

            $archived_audits = array_reverse($archived_audits);

            /* Set a custom title */
            Title::set(sprintf(l('audit.title'), string_truncate(remove_url_protocol_from_url($audit->url), 32)));

            /* Global websites stats */
            $audits_stats = (new \Altum\Models\Audit())->get_stats();

            /* Get download tracking count */
            if(is_logged_in()) {
                $cycle_start = get_user_cycle_start($this->user);

                $track = db()
                    ->where('ip_md5', (string)$this->user->user_id)
                    ->where('type', 'report_download_account')
                    ->getOne('ip_tracking');

                if ($track) {
                    if ($cycle_start && $track->last_datetime < $cycle_start) {
                        db()->where('id', $track->id)->update('ip_tracking', ['count' => 0]);
                        $download_count = 0;
                    } else {
                        $download_count = $track->count;
                    }
                } else {
                    $download_count = 0;
                }
            } else {
                $ip_md5 = md5(get_ip());
                $download_count = db()
                    ->where('ip_md5', $ip_md5)
                    ->where('type', 'report_download_ip')
                    ->getValue('ip_tracking', '`count`') ?? 0;
            }

            $data = [
                'audit' => $audit,
                'archived_audits' => $archived_audits,
                'audits_stats' => $audits_stats,
                'download_count' => $download_count,
                'view_count' => $view_count,
                'view_limit' => $view_limit,
            ];
            $has_full_access = true;
            if(!\Altum\Authentication::is_admin()) {
                if ($view_limit != -1 && $view_count >= $view_limit) {
                    $has_full_access = false;
                }
            }
            $data['has_full_access'] = $has_full_access;

            $view = new \Altum\View('audit/index', (array)$this);
            $this->add_view_content('content', $view->run($data));

        }
    }

    public function download_track() {
        if(is_logged_in()) {
            $id = (string)$this->user->user_id;
            $type = 'report_download_account';
            $user_identifier = $this->user->name . ' (' . $this->user->email . ')';

            $cycle_start = get_user_cycle_start($this->user);

            $track = db()
                ->where('ip_md5', $id)
                ->where('type', $type)
                ->getOne('ip_tracking');

            if ($track && $cycle_start && $track->last_datetime < $cycle_start) {
                db()->where('id', $track->id)->update('ip_tracking', ['count' => 0]);
            }
        } else {
            $id = md5(get_ip());
            $type = 'report_download_ip';
            $user_identifier = 'Guest (' . get_ip() . ')';
        }

        /* Update or insert tracking */
        db()->rawQuery("INSERT INTO ip_tracking (ip_md5, type, count, last_datetime) VALUES (?, ?, 1, NOW()) ON DUPLICATE KEY UPDATE count = count + 1, last_datetime = NOW()", [$id, $type]);

        if (is_logged_in()) {
            check_and_send_50_percent_notification($this->user);
        }

        /* Legacy support for free plan users if needed */
        if(is_logged_in() && $this->user->plan_id == 'free') {
            db()->where('user_id', $this->user->user_id)->update('users', ['has_downloaded_report' => 1]);
        }

        /* Unset has_logo_addon for plan 1 users if they downloaded with custom branding/logo */
        if(is_logged_in() && $this->user->plan_id == 1) {
            $with_logo = isset($_POST['with_logo']) && $_POST['with_logo'] == 1;
            if($with_logo && !empty($this->user->preferences->has_logo_addon)) {
                $preferences = json_decode($this->user->preferences ?? '{}');
                if(is_object($preferences)) {
                    unset($preferences->has_logo_addon);
                    db()->where('user_id', $this->user->user_id)->update('users', [
                        'preferences' => json_encode($preferences)
                    ]);
                    cache()->deleteItemsByTag('user_id=' . $this->user->user_id);
                }
            }
        }


        /* Send notification to admin */
        if(!empty(settings()->email_notifications->emails)) {
            $audit_url = isset($_POST['audit_url']) && !empty($_POST['audit_url']) ? query_clean($_POST['audit_url']) : 'Unknown';
            $audit_id = isset($_POST['audit_id']) && !empty($_POST['audit_id']) ? (int) $_POST['audit_id'] : 0;

            $email_template = [
                'subject' => 'SEO Audit Report Downloaded - ' . settings()->main->title,
                'body' => '<h3>SEO Audit Report Downloaded</h3>' .
                    '<p>A user has just downloaded an SEO audit report with the following details:</p>' .
                    '<div style="background: #ffffff; padding: 20px; border-radius: 8px; border: 1px solid #e0e0e0;">' .
                    '<table border="0" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse; color: #444;">' .
                    '<tr><td style="font-weight: bold; width: 120px; border-bottom: 1px solid #f0f0f0;">User:</td><td style="border-bottom: 1px solid #f0f0f0;">' . $user_identifier . '</td></tr>' .
                    '<tr><td style="font-weight: bold; border-bottom: 1px solid #f0f0f0;">Website:</td><td style="border-bottom: 1px solid #f0f0f0;"><a href="' . $audit_url . '" target="_blank">' . $audit_url . '</a></td></tr>' .
                    '<tr><td style="font-weight: bold; border-bottom: 1px solid #f0f0f0;">Audit ID:</td><td style="border-bottom: 1px solid #f0f0f0;">#' . $audit_id . '</td></tr>' .
                    '<tr><td style="font-weight: bold;">Date:</td><td>' . get_date() . '</td></tr>' .
                    '</table>' .
                    '</div>' .
                    '<br /><br />' .
                    '<div style="text-align: center;">' .
                    '<a href="' . url('audit/' . $audit_id) . '" class="cta">View Detailed Audit Report</a>' .
                    '</div>'
            ];

            send_mail(explode(',', settings()->email_notifications->emails), $email_template['subject'], $email_template['body']);
        }

        header('Content-Type: application/json');
        echo json_encode(['status' => 'success']);
        die();
    }

    public function upload_report_logo() {
        \Altum\Authentication::guard();

        if(is_null($this->user->preferences)) {
            $this->user->preferences = new \StdClass();
        }

        if(!empty($_FILES) || !empty($_POST)) {
            /* Process upload of report_logo */
            $new_logo = $this->user->preferences->white_label_report_logo ?? null;
            if(isset($_FILES['white_label_report_logo']) && !empty($_FILES['white_label_report_logo']['name'])) {
                $new_logo = \Altum\Uploads::process_upload($this->user->preferences->white_label_report_logo ?? null, 'users', 'white_label_report_logo', 'white_label_report_logo_remove', null);
            }
            
            if(!Alerts::has_errors()) {
                $this->user->preferences->white_label_report_logo = $new_logo;
                
                if (isset($_POST['company_name'])) {
                    $this->user->preferences->white_label_company_name = $_POST['company_name'];
                }
                if (isset($_POST['domain_name'])) {
                    $this->user->preferences->white_label_domain_name = $_POST['domain_name'];
                }
                if (isset($_POST['email'])) {
                    $this->user->preferences->white_label_email = $_POST['email'];
                }
                if (isset($_POST['phone'])) {
                    $this->user->preferences->white_label_phone = $_POST['phone'];
                }

                $preferences_array = [
                    'white_label_title' => $this->user->preferences->white_label_title ?? '',
                    'white_label_company_name' => $this->user->preferences->white_label_company_name ?? '',
                    'white_label_domain_name' => $this->user->preferences->white_label_domain_name ?? '',
                    'white_label_email' => $this->user->preferences->white_label_email ?? '',
                    'white_label_phone' => $this->user->preferences->white_label_phone ?? '',
                    'white_label_logo_light' => $this->user->preferences->white_label_logo_light ?? null,
                    'white_label_logo_dark' => $this->user->preferences->white_label_logo_dark ?? null,
                    'white_label_favicon' => $this->user->preferences->white_label_favicon ?? null,
                    'white_label_report_logo' => $new_logo,
                    'default_results_per_page' => $this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page,
                    'default_order_type' => $this->user->preferences->default_order_type ?? settings()->main->default_order_type,
                    'websites_default_order_by' => $this->user->preferences->websites_default_order_by ?? 'website_id',
                    'audits_default_order_by' => $this->user->preferences->audits_default_order_by ?? 'audit_id',
                    'archived_audits_default_order_by' => $this->user->preferences->archived_audits_default_order_by ?? 'archived_audit_id',
                    'notification_handlers_default_order_by' => $this->user->preferences->notification_handlers_default_order_by ?? 'notification_handler_id',
                    'domains_default_order_by' => $this->user->preferences->domains_default_order_by ?? 'domain_id',
                    'dashboard' => (array) ($this->user->preferences->dashboard ?? []),
                ];

                if(isset($this->user->preferences->has_logo_addon)) {
                    $preferences_array['has_logo_addon'] = $this->user->preferences->has_logo_addon;
                }

                $preferences = json_encode($preferences_array);

                /* Database query */
                db()->where('user_id', $this->user->user_id)->update('users', [
                    'preferences' => $preferences,
                ]);

                /* Clear cache */
                cache()->deleteItemsByTag('user_id=' . $this->user->user_id);

                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'success',
                    'logo_url' => $new_logo ? \Altum\Uploads::get_full_url('users') . $new_logo : ''
                ]);
                exit;
            }
        }

        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Failed to upload logo.']);
        exit;
    }
}
