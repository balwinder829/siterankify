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

defined('ALTUMCODE') || die();

function settings() {
    if(!\Altum\Settings::$settings) {
        \Altum\Settings::initialize();
    }

    return \Altum\Settings::$settings;
}

function get_settings_custom_head_js($key = 'head_js') {
    $head_js = settings()->custom->{$key};

    /* Dynamic variables processing */
    $replacers = [
        '{{WEBSITE_TITLE}}' => settings()->main->title,
        '{{USER:NAME}}' => is_logged_in() ? \Altum\Authentication::$user->name : '',
        '{{USER:EMAIL}}' => is_logged_in() ? \Altum\Authentication::$user->email : '',
        '{{USER:CONTINENT_NAME}}' => is_logged_in() ? get_continent_from_continent_code(\Altum\Authentication::$user->continent_code) : '',
        '{{USER:COUNTRY_NAME}}' => is_logged_in() ? get_country_from_country_code(\Altum\Authentication::$user->country) : '',
        '{{USER:CITY_NAME}}' => is_logged_in() ? \Altum\Authentication::$user->city_name : '',
        '{{USER:DEVICE_TYPE}}' => is_logged_in() ? l('global.device.' . \Altum\Authentication::$user->device_type) : '',
        '{{USER:OS_NAME}}' => is_logged_in() ? \Altum\Authentication::$user->os_name : '',
        '{{USER:BROWSER_NAME}}' => is_logged_in() ? \Altum\Authentication::$user->browser_name : '',
        '{{USER:BROWSER_LANGUAGE}}' => is_logged_in() ? get_language_from_locale(\Altum\Authentication::$user->browser_language) : '',
        '{{USER:USER_ID}}' => json_encode(is_logged_in() ? \Altum\Authentication::$user->user_id : ''),
        '{{USER:PLAN_ID}}' => json_encode(is_logged_in() ? \Altum\Authentication::$user->plan_id : ''),
    ];

    $head_js = str_replace(
        array_keys($replacers),
        array_values($replacers),
        $head_js
    );

    return $head_js;
}

function db() {
    if(!\Altum\Database::$db) {
        \Altum\Database::initialize();
    }

    return \Altum\Database::$db;
}

function database() {
    if(!\Altum\Database::$database) {
        \Altum\Database::initialize();
    }

    return \Altum\Database::$database;
}

function language($language = null) {
    return \Altum\Language::get($language);
}

function l($key, $language = null, $null_coalesce = false) {
    return \Altum\Language::get($language)[$key] ?? \Altum\Language::get(\Altum\Language::$main_name)[$key] ?? ($null_coalesce ? null : $key);
}

function currency() {
    if(!\Altum\Currency::$currency) {
        \Altum\Currency::initialize();
    }

    return \Altum\Currency::$currency;
}

function cache($adapter = 'adapter') {
    return \Altum\Cache::${$adapter};
}

function get_date($format = 'Y-m-d H:i:s') {
    return date($format);
}

function is_logged_in() {
    return \Altum\Authentication::check();
}

function user() {
    return \Altum\Authentication::$user;
}

function get_user_cycle_start($user) {
    $cycle_start = null;
    $latest_payment = db()
        ->where('user_id', $user->user_id)
        ->where('status', 1)
        ->orderBy('id', 'DESC')
        ->getOne('payments', ['datetime']);
    if ($latest_payment) {
        $cycle_start = $latest_payment->datetime;
        
        $now = new \DateTime();
        if ($user->plan_id == 2) {
            $exp_date = new \DateTime($user->plan_expiration_date);
            if ($exp_date > $now) {
                $calc_start_dt = clone $exp_date;
                $calc_start_dt->modify('-1 month');
                $calc_start = $calc_start_dt->format('Y-m-d H:i:s');
                if ($calc_start > $cycle_start && $calc_start <= $now->format('Y-m-d H:i:s')) {
                    $cycle_start = $calc_start;
                }
            }
        } else if ($user->plan_id == 3) {
            $exp_date = new \DateTime($user->plan_expiration_date);
            if ($exp_date > $now) {
                $calc_start_dt = clone $exp_date;
                $calc_start_dt->modify('-1 year');
                $calc_start = $calc_start_dt->format('Y-m-d H:i:s');
                if ($calc_start > $cycle_start && $calc_start <= $now->format('Y-m-d H:i:s')) {
                    $cycle_start = $calc_start;
                }
            }
        }
    }

    if (!$cycle_start) {
        // Calculate cycle start based on registration date (datetime in users table)
        try {
            $reg_datetime = new \DateTime($user->datetime);
            $now = new \DateTime();
            $diff = $reg_datetime->diff($now);
            $months_diff = ($diff->y * 12) + $diff->m;
            
            $cycle_start_dt = clone $reg_datetime;
            $cycle_start_dt->modify("+" . $months_diff . " months");
            if ($cycle_start_dt > $now) {
                $cycle_start_dt->modify("-1 month");
            }
            $cycle_start = $cycle_start_dt->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            $cycle_start = date('Y-m-01 00:00:00');
        }
    }

    return $cycle_start;
}

function get_user_audits_used($user) {
    if ($user->plan_id == 'free') {
        $cycle_start = get_user_cycle_start($user);
        $track = db()
            ->where('ip_md5', (string)$user->user_id)
            ->where('type', 'audit_run_account')
            ->getOne('ip_tracking');

        if ($track) {
            if ($cycle_start && $track->last_datetime < $cycle_start) {
                db()->where('id', $track->id)->update('ip_tracking', ['count' => 0]);
                return 0;
            }
            return $track->count;
        }
        return 0;
    }
    return db()->where('user_id', $user->user_id)->getValue('users', '`audit_audits_current_month`') ?? 0;
}

function get_user_limits_and_usage($user) {
    if (!is_object($user->plan_settings)) {
        $user->plan_settings = json_decode($user->plan_settings ?? '');
    }
    
    // Audits
    $audits_limit = $user->plan_settings->audits_per_month_limit ?? 0;
    $audits_used = get_user_audits_used($user);
    $audits_left = $audits_limit == -1 ? -1 : max(0, $audits_limit - $audits_used);

    // Views
    $views_limit = $user->plan_settings->report_views_limit ?? null;
    if ($views_limit === null) {
        if ($user->plan_id == 'free') $views_limit = 15;
        else if ($user->plan_id == 1) $views_limit = 50;
        else if ($user->plan_id == 2) $views_limit = 100;
        else if ($user->plan_id == 3) $views_limit = 120;
        else $views_limit = 15;
    }
    if ($views_limit != -1) {
        $views_limit += ($user->extra_report_views ?? 0);
    }
    
    $cycle_start = get_user_cycle_start($user);
    $track_view = db()
        ->where('ip_md5', (string)$user->user_id)
        ->where('type', 'report_view_account')
        ->getOne('ip_tracking');
    $views_used = 0;
    if ($track_view) {
        if ($cycle_start && $track_view->last_datetime < $cycle_start) {
            db()->where('id', $track_view->id)->update('ip_tracking', ['count' => 0]);
        } else {
            $views_used = $track_view->count;
        }
    }
    $views_left = $views_limit == -1 ? -1 : max(0, $views_limit - $views_used);

    // PDF
    $pdf_limit = $user->plan_settings->pdf_downloads_limit ?? null;
    if ($pdf_limit === null) {
        if ($user->plan_id == 'free') $pdf_limit = 1;
        else if ($user->plan_id == 1) $pdf_limit = 1;
        else if ($user->plan_id == 2) $pdf_limit = 10;
        else if ($user->plan_id == 3) $pdf_limit = 120;
        else $pdf_limit = 10;
    }
    if ($pdf_limit != -1) {
        $pdf_limit += ($user->extra_pdf_downloads ?? 0);
    }
    
    $track_pdf = db()
        ->where('ip_md5', (string)$user->user_id)
        ->where('type', 'report_download_account')
        ->getOne('ip_tracking');
    $pdf_used = 0;
    if ($track_pdf) {
        if ($cycle_start && $track_pdf->last_datetime < $cycle_start) {
            db()->where('id', $track_pdf->id)->update('ip_tracking', ['count' => 0]);
        } else {
            $pdf_used = $track_pdf->count;
        }
    }
    $pdf_left = $pdf_limit == -1 ? -1 : max(0, $pdf_limit - $pdf_used);

    return [
        'audits' => [
            'limit' => $audits_limit,
            'used' => $audits_used,
            'left' => $audits_left,
        ],
        'views' => [
            'limit' => $views_limit,
            'used' => $views_used,
            'left' => $views_left,
        ],
        'pdf' => [
            'limit' => $pdf_limit,
            'used' => $pdf_used,
            'left' => $pdf_left,
        ]
    ];
}

function check_and_send_50_percent_notification($user) {
    if (!$user || $user->plan_id == 'free') {
        return;
    }

    $cycle_start = get_user_cycle_start($user);
    if (!$cycle_start) {
        $cycle_start = $user->datetime;
    }

    $preferences = is_string($user->preferences) ? json_decode($user->preferences ?? '{}') : $user->preferences;
    if (!is_object($preferences)) {
        $preferences = new \stdClass();
    }

    if (isset($preferences->sent_50_percent_email_cycle) && $preferences->sent_50_percent_email_cycle === $cycle_start) {
        return;
    }

    $limits_and_usage = get_user_limits_and_usage($user);

    $is_50_reached = false;

    // Check audits
    $audits = $limits_and_usage['audits'];
    if ($audits['limit'] > 0 && ($audits['used'] / $audits['limit']) >= 0.5) {
        $is_50_reached = true;
    }

    // Check views
    $views = $limits_and_usage['views'];
    if ($views['limit'] > 0 && ($views['used'] / $views['limit']) >= 0.5) {
        $is_50_reached = true;
    }

    // Check pdf
    $pdf = $limits_and_usage['pdf'];
    if ($pdf['limit'] > 0 && ($pdf['used'] / $pdf['limit']) >= 0.5) {
        $is_50_reached = true;
    }

    if ($is_50_reached) {
        $email_template = get_email_template(
            [],
            l('global.emails.user_50_percent_usage.subject', $user->language),
            [
                '{{NAME}}' => $user->name,
                '{{AUDITS_USED}}' => $audits['used'],
                '{{AUDITS_LIMIT}}' => $audits['limit'] == -1 ? 'Unlimited' : $audits['limit'],
                '{{VIEWS_USED}}' => $views['used'],
                '{{VIEWS_LIMIT}}' => $views['limit'] == -1 ? 'Unlimited' : $views['limit'],
                '{{PDF_USED}}' => $pdf['used'],
                '{{PDF_LIMIT}}' => $pdf['limit'] == -1 ? 'Unlimited' : $pdf['limit'],
                '{{PLAN_UPGRADE_LINK}}' => url('plan'),
            ],
            l('global.emails.user_50_percent_usage.body', $user->language)
        );

        send_mail($user->email, $email_template->subject, $email_template->body, ['anti_phishing_code' => $user->anti_phishing_code, 'language' => $user->language]);

        $preferences->sent_50_percent_email_cycle = $cycle_start;
        db()->where('user_id', $user->user_id)->update('users', ['preferences' => json_encode($preferences)]);
        cache()->deleteItemsByTag('user_id=' . $user->user_id);
    }
}
