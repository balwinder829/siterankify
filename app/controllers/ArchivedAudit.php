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
use Altum\Title;

defined('ALTUMCODE') || die();

class ArchivedAudit extends Controller {
    public $audit;

    public function index()
    {

        $archived_audit_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$archived_audit = db()->where('archived_audit_id', $archived_audit_id)->getOne('archived_audits')) {
            redirect();
        }
        foreach(['data', 'issues'] as $key) $archived_audit->{$key} = json_decode($archived_audit->{$key} ?? '');

        /* Get main audit */
        if(!$audit = db()->where('audit_id', $archived_audit->audit_id)->getOne('audits')) {
            redirect();
        }
        foreach(['data', 'issues', 'settings'] as $key) $audit->{$key} = json_decode($audit->{$key} ?? '');
        \Altum\Helpers\Audit::ensure_pagespeed_scores($audit);

        /* Public audit */
        if(!$audit->settings->is_public) {

            /* Make sure the current user has access */
            if(($audit->uploader_id != md5(get_ip())) && (!$audit->user_id || ($audit->user_id != $this->user->user_id))) {
                redirect();
            }

        }

        /* Audit */
        $audit->full_url = (isset(\Altum\Router::$data['domain']) ? \Altum\Router::$data['domain']->url : url()) . 'archived-audit/' . $archived_audit_id;

        /* Meta */
        Meta::set_canonical_url($audit->full_url);

        /* User of the audit */
        $this->audit_user = (new \Altum\Models\User())->get_user_by_user_id($audit->user_id);

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

        /* Check if the user has access to the page */
        $has_access = !$audit->settings->password || ($audit->settings->password && isset($_COOKIE['password_' . $audit->audit_id]) && $_COOKIE['password_' . $audit->audit_id] == $audit->settings->password);

        /* Do not let the user have password protection if the plan doesn't allow it */
        if($this->audit_user && $this->audit_user->plan_settings && !$this->audit_user->plan_settings->password_protection_is_enabled) {
            $has_access = true;
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

            /* Get archived audits data */
            $archived_audits_left = db()->where('audit_id', $audit->audit_id)->where('archived_audit_id', $archived_audit->archived_audit_id, '<')->orderBy('`archived_audit_id`', 'DESC')->get('archived_audits', 15, ['archived_audit_id', 'score', 'datetime']);
            $archived_audits_left = array_reverse($archived_audits_left);

            $archived_audits_right = db()->where('audit_id', $audit->audit_id)->where('archived_audit_id', $archived_audit->archived_audit_id, '>')->orderBy('`archived_audit_id`', 'DESC')->get('archived_audits', 15, ['archived_audit_id', 'score', 'datetime']);
            $archived_audits_right = array_reverse($archived_audits_right);

            /* Set a custom title */
            Title::set(sprintf(l('archived_audit.title'), string_truncate(remove_url_protocol_from_url($archived_audit->url), 32)));

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
                'archived_audit' => $archived_audit,
                'archived_audits_left' => $archived_audits_left,
                'archived_audits_right' => $archived_audits_right,
                'audit' => $audit,
                'audits_stats' => $audits_stats,
                'download_count' => $download_count,
            ];

            $view = new \Altum\View('archived-audit/index', (array)$this);
            $this->add_view_content('content', $view->run($data));

        }
    }

}
