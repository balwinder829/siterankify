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

class AuditRefresh extends Controller {

    public function index() {

        $start_time = time();
        set_time_limit(0);

        /* Bypass plan limits */
        if(!$this->user) {
            $this->user = new \stdClass();
            $this->user->plan_settings = settings()->plan_guest->settings;
        }

        if($this->user->plan_settings) {
            if(!isset($this->user->plan_settings->audits_retention)) $this->user->plan_settings->audits_retention = 365;
        }

        $db_guest_status = settings()->plan_guest->status;
        settings()->plan_guest->status = 1;


        if(empty($_POST)) redirect();

        $error_redirect = is_logged_in() ? 'archived-audits' : 'seo';

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create.audits')) {
            Alerts::add_error(l('global.info_message.team_no_access'));
            redirect($error_redirect);
        }

        /* Check for the plan limit */
        $ip_md5 = md5(get_ip());
        $audits_current_month = get_user_audits_used($this->user);

        /* Enforce daily limit for Guest users (not logged in) */
        if(!is_logged_in()) {
            $guest_limit = (int) (settings()->main->guest_audits_limit ?? 5);

            if ($guest_limit <= 0) {
                $limit_message = 'Audit limit reached: Guest auditing is disabled. Please register or log in to continue.';
                Alerts::add_error($limit_message);
                redirect($error_redirect);
            }

            $audits_today = db()
                ->where('uploader_id', $ip_md5)
                ->where('datetime', date('Y-m-d 00:00:00'), '>=')
                ->where('datetime', date('Y-m-d 23:59:59'), '<=')
                ->getValue('audits', 'count(*)');

            if($audits_today >= $guest_limit) {
                $limit_message = 'Audit limit reached: Guest users can only audit ' . $guest_limit . ' websites per day. Please register or log in to continue.';
                Alerts::add_error($limit_message);
                redirect($error_redirect);
            }
        }

        /* Monthly limit check for logged-in users */
        if(is_logged_in() && $this->user->plan_settings->audits_per_month_limit != -1 && $audits_current_month >= $this->user->plan_settings->audits_per_month_limit) {
            Alerts::add_error(l('global.info_message.plan_feature_limit') . (settings()->payment->is_enabled ? ' <a href="' . url('plan') . '" class="font-weight-bold text-reset">' . l('global.info_message.plan_upgrade') . '.</a>' : null));
            redirect($error_redirect);
        }



        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        /* Check for any errors */
        $required_fields = ['audit_id'];
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) ||(isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                Alerts::add_field_error($field, l('global.error_message.empty_field'));
            }
        }

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$audit = db()->where('audit_id', $_POST['audit_id'])->getOne('audits')) {
            redirect($error_redirect);
        }
        foreach(['settings'] as $key) $audit->{$key} = json_decode($audit->{$key} ?? '');


        $has_permission = false;
        if($audit->user_id && $this->user && $audit->user_id == $this->user->user_id) {
            $has_permission = true;
        } elseif(!$audit->user_id && $audit->uploader_id && $audit->uploader_id == md5(get_ip())) {
            $has_permission = true;
        }

        if(!$has_permission) {
            redirect($error_redirect);
        }

        /* Send the main request */
        try {
            $response = \Altum\Helpers\Audit::process_request($audit->url);
        } catch(\Exception $exception) {
            Alerts::add_error($exception->getMessage());
        }

        /* Single URL processing */
        $data = \Altum\Helpers\Audit::process_request_response($audit->url, $response, null, $start_time);
        $data_not_found = \Altum\Helpers\Audit::process_not_found($data['parsed_url']);
        $data_robots = \Altum\Helpers\Audit::process_robots($data['parsed_url']);

        /* Merge data */
        $data = array_merge($data, $data_not_found, $data_robots);

        /* Clean data */
        $data = convert_array_to_utf8($data);

        /* Prepare full data to be inserted */
        $data_json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        /* Process data */
        $audit_data = \Altum\Helpers\Audit::process_audit_data($data);
        $issues = [
            'major' => $audit_data['major_issues'],
            'moderate' => $audit_data['moderate_issues'],
            'minor' => $audit_data['minor_issues'],
            'potential_major_issues' => $audit_data['potential_major_issues'],
            'potential_moderate_issues' => $audit_data['potential_moderate_issues'],
            'potential_minor_issues' => $audit_data['potential_minor_issues'],
            'total_tests' => $audit_data['total_tests'],
            'passed_tests' => $audit_data['passed_tests'],
        ];

        if(settings()->audits->ai_is_enabled && settings()->audits->openai_api_key) {
            $ai_summary = \Altum\Helpers\Audit::process_ai_summary($data, $issues);
        }

        /* Score */
        $score = $audit_data['score'];

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Insert a log of the current update as old */
            try {
                db()->insert('archived_audits', [
                    'audit_id' => $audit->audit_id,
                    'user_id' => $audit->user_id,
                    'domain_id' => $audit->domain_id,
                    'uploader_id' => $audit->uploader_id,
                    'website_id' => $audit->website_id,
                    'host' => $audit->host,
                    'url' => $audit->url,
                    'ttfb' => $audit->ttfb,
                    'response_time' => $audit->response_time,
                    'average_download_speed' => $audit->average_download_speed,
                    'page_size' => $audit->page_size,
                    'http_requests' => $audit->http_requests,
                    'is_https' => $audit->is_https,
                    'is_ssl_valid' => $audit->is_ssl_valid,
                    'http_protocol' => $audit->http_protocol,
                    'title' => $audit->title,
                    'meta_description' => $audit->meta_description,
                    'meta_keywords' => $audit->meta_keywords,
                    'data' => $audit->data,
                    'ai_summary' => $audit->ai_summary ?? null,
                    'issues' => $audit->issues,
                    'score' => $audit->score,
                    'total_tests' => $audit->total_tests ?? null,
                    'passed_tests' => $audit->passed_tests ?? null,
                    'total_issues' => $audit->total_issues ?? null,
                    'major_issues' => $audit->major_issues ?? null,
                    'moderate_issues' => $audit->moderate_issues ?? null,
                    'minor_issues' => $audit->minor_issues ?? null,
                    'expiration_datetime' => $audit->expiration_datetime,
                    'datetime' => $audit->last_refresh_datetime ?: $audit->datetime,
                ]);
            } catch (\Exception $e) {
                // Ignore insert error if columns are missing in archived_audits
            }

            /* Prepare expiration date */
            $expiration_datetime = (new \DateTime())->modify('+' . ($this->user->plan_settings->audits_retention ?? 90) . ' days')->format('Y-m-d H:i:s');

            /* Next refresh date */
            $next_refresh_datetime = $audit->settings->audit_check_interval ? (new \DateTime())->modify('+' . $audit->settings->audit_check_interval . ' seconds')->format('Y-m-d H:i:s') : null;

            /* Update the main audit */
            db()->where('audit_id', $audit->audit_id)->update('audits', [
                'ttfb' => $data['ttfb'],
                'response_time' => $data['response_time'],
                'average_download_speed' => $data['average_download_speed'],
                'page_size' => $data['page_size'],
                'http_requests' => $data['http_requests'],
                'is_https' => $data['is_https'],
                'is_ssl_valid' => $data['is_ssl_valid'],
                'http_protocol' => $data['http_protocol'],
                'title' => $data['title'],
                'meta_description' => $data['meta_description'],
                'meta_keywords' => $data['meta_keywords'],
                'data' => $data_json,
                'ai_summary' => $ai_summary ?? null,
                'issues' => json_encode($issues),
                'score' => $score,
                'total_tests' => $audit_data['total_tests'],
                'passed_tests' => $audit_data['passed_tests'],
                'total_issues' => $audit_data['total_issues'],
                'major_issues' => $audit_data['found_major_issues'],
                'moderate_issues' => $audit_data['found_moderate_issues'],
                'minor_issues' => $audit_data['found_minor_issues'],
                'total_refreshes' => db()->inc(),
                'next_refresh_datetime' => $next_refresh_datetime,
                'last_refresh_datetime' => get_date(),
                'expiration_datetime' => $expiration_datetime,
            ]);

            (new \Altum\Models\Website())->refresh_stats($audit->website_id);

            /* Increment report view count when audit is refreshed */
            if(!\Altum\Authentication::is_admin()) {
                if(is_logged_in()) {
                    $id = (string)$this->user->user_id;
                    $type = 'report_view_account';
                } else {
                    $id = md5(get_ip());
                    $type = 'report_view_ip';
                }
                db()->rawQuery("INSERT INTO ip_tracking (ip_md5, type, count, last_datetime) VALUES (?, ?, 1, NOW()) ON DUPLICATE KEY UPDATE count = count + 1, last_datetime = NOW()", [$id, $type]);
                if (is_logged_in()) {
                    check_and_send_50_percent_notification($this->user);
                }
            }

            /* Database query */
            if(is_logged_in()) {
                db()->where('user_id', $this->user->user_id)->update('users', [
                    'audit_audits_current_month' => db()->inc()
                ]);

                // Track audit run in ip_tracking for free plan cycle resets
                db()->rawQuery("INSERT INTO ip_tracking (ip_md5, type, count, last_datetime) VALUES (?, 'audit_run_account', 1, NOW()) ON DUPLICATE KEY UPDATE count = count + 1, last_datetime = NOW()", [$this->user->user_id]);

                /* Clear cache */
                cache()->deleteItemsByTag('user_id=' . $this->user->user_id);

                check_and_send_50_percent_notification($this->user);
            }

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('audits.success_message.processed_refresh'), '<strong>' . remove_url_protocol_from_url($audit->url) . '</strong>'));
            redirect('audit/' . $audit->audit_id);
        }

        redirect($error_redirect);
    }

}
