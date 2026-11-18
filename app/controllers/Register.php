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
use Altum\Captcha;
use Altum\Logger;
use Altum\Models\User;

defined('ALTUMCODE') || die();

class Register extends Controller {

    public function index() {

        \Altum\Authentication::guard('guest');

        /* Check for a special registration identifier */
        $unique_registration_identifier = isset($_GET['unique_registration_identifier'], $_GET['email']) && $_GET['unique_registration_identifier'] == md5($_GET['email'] . $_GET['email']) ? query_clean($_GET['unique_registration_identifier']) : null;

        /* Check if Registration is enabled first */
        if(!settings()->users->register_is_enabled && (!\Altum\Plugin::is_active('teams') || (\Altum\Plugin::is_active('teams') && !$unique_registration_identifier))) {
            redirect('not-found');
        }

        \Altum\CustomHooks::user_initiate_registration();

        $redirect = process_and_get_redirect_params() ?? 'dashboard';
        $redirect_append = $redirect ? '?redirect=' . $redirect : null;

        if(isset($_GET['restart'])) {
            session_unset_key('otp_register_required');
            session_unset_key('otp_register_code');
            session_unset_key('otp_register_name');
            session_unset_key('otp_register_email');
            session_unset_key('otp_register_password');
            session_unset_key('otp_register_is_newsletter_subscribed');
            redirect('register' . $redirect_append);
        }

        /* Set meta tags */
        $system_page_seo = settings()->system_pages_seo->register ?? null;
        if($system_page_seo->title) \Altum\Title::set($system_page_seo->title);
        if($system_page_seo->description) \Altum\Meta::$description = $system_page_seo->description;
        if($system_page_seo->keywords) \Altum\Meta::$keywords = $system_page_seo->keywords;
        \Altum\Meta::set_robots(($system_page_seo->is_indexable ?? false) ? 'index, follow' : 'noindex, nofollow');

        /* Default variables */
        $values = [
            'name' => isset($_GET['name']) ? query_clean($_GET['name']) : '',
            'email' => isset($_GET['email']) && is_string($_GET['email']) ? query_clean($_GET['email']) : '',
            'password' => ''
        ];

        /* Initiate captcha */
        $captcha = new Captcha();

        if(!empty($_POST) && !settings()->users->register_only_social_logins) {

            /* Clear any previous alerts/errors from session */
            Alerts::clear_field_errors();
            session_unset_key('error');
            session_unset_key('success');
            session_unset_key('info');
            session_unset_key('warning');

            if(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['name'])) {
                session_unset_key('otp_register_required');
                session_unset_key('otp_register_code');
                session_unset_key('otp_register_name');
                session_unset_key('otp_register_email');
                session_unset_key('otp_register_password');
                session_unset_key('otp_register_is_newsletter_subscribed');
            }

            if(session_has('otp_register_required')) {
                $_POST['otp_token'] = isset($_POST['otp_token']) ? query_clean(str_replace(' ', '', $_POST['otp_token'] ?? '')) : null;
                if(empty($_POST['otp_token'])) {
                    Alerts::add_field_error('otp_token', l('global.error_message.empty_field'));
                } else if($_POST['otp_token'] == session_get('otp_register_code')) {
                    // OTP is valid! Proceed with registration using session values
                    $_POST['name'] = session_get('otp_register_name');
                    $_POST['email'] = session_get('otp_register_email');
                    $_POST['password'] = session_get('otp_register_password');
                    $_POST['is_newsletter_subscribed'] = session_get('otp_register_is_newsletter_subscribed');

                    /* Define some needed variables */
                    $active 	                = (int) !settings()->users->email_confirmation;
                    $email_code                 = md5(uniqid('', true) . random_bytes(16));

                    /* Determine what plan is set by default */
                    $plan_id                    = 'free';
                    $plan_settings              = json_encode(settings()->plan_free->settings ?? '');
                    $plan_expiration_date       = get_date();

                    $registered_user = (new User())->create(
                        $_POST['email'],
                        $_POST['password'],
                        $_POST['name'],
                        (int) !settings()->users->email_confirmation,
                        'direct',
                        $email_code,
                        null,
                        $_POST['is_newsletter_subscribed'],
                        $plan_id,
                        $plan_settings,
                        $plan_expiration_date,
                        settings()->main->default_timezone
                    );

                    /* Log the action */
                    Logger::users($registered_user['user_id'], 'register.success');

                    session_unset_key('otp_register_required');
                    session_unset_key('otp_register_code');
                    session_unset_key('otp_register_name');
                    session_unset_key('otp_register_email');
                    session_unset_key('otp_register_password');
                    session_unset_key('otp_register_is_newsletter_subscribed');

                    /* If active = 1 then login the user, else send the user an activation email */
                    if($active == '1') {

                        /* Send a welcome email if needed */
                        if(settings()->users->welcome_email_is_enabled) {

                            $email_template = get_email_template(
                                [],
                                l('global.emails.user_welcome.subject'),
                                [
                                    '{{NAME}}' => $_POST['name'],
                                    '{{URL}}' => url(),
                                    '{{DASHBOARD_LINK}}' => url('dashboard'),
                                ],
                                l('global.emails.user_welcome.body')
                            );

                            send_mail($_POST['email'], $email_template->subject, $email_template->body, ['capitalize' => true]);

                        }

                        /* Send notification to admin if needed */
                        if(settings()->email_notifications->new_user && !empty(settings()->email_notifications->emails)) {
                            /* Prepare the email */
                            $email_template = get_email_template(
                                [],
                                l('global.emails.admin_new_user_notification.subject'),
                                [
                                    '{{NAME}}' => str_replace('.', '. ', $_POST['name']),
                                    '{{EMAIL}}' => $_POST['email'],
                                    '{{SOURCE}}' => $registered_user['source'],
                                    '{{IP}}' => $registered_user['ip'],
                                    '{{COUNTRY_NAME}}' => $registered_user['country'] ? get_country_from_country_code($registered_user['country']) : l('global.unknown'),
                                    '{{CITY_NAME}}' => $registered_user['city_name'] ?? l('global.unknown'),
                                    '{{DEVICE_TYPE}}' => l('global.device.' . $registered_user['device_type']),
                                    '{{OS_NAME}}' => $registered_user['os_name'],
                                    '{{BROWSER_NAME}}' => $registered_user['browser_name'],
                                    '{{USER_LINK}}' => url('admin/user-view/' . $registered_user['user_id']),
                                ],
                                l('global.emails.admin_new_user_notification.body')
                            );

                            send_mail(explode(',', settings()->email_notifications->emails), $email_template->subject, $email_template->body);
                        }

                        /* Send webhook notification if needed */
                        if(settings()->webhooks->user_new) {
                            fire_and_forget('post', settings()->webhooks->user_new, [
                                'user_id' => $registered_user['user_id'],
                                'email' => $_POST['email'],
                                'name' => $_POST['name'],
                                'source' => 'direct',
                                'is_newsletter_subscribed' => $_POST['is_newsletter_subscribed'],
                                'datetime' => get_date(),
                            ]);
                        }

                        /* Send internal notification if needed */
                        if(settings()->internal_notifications->admins_is_enabled && settings()->internal_notifications->new_user) {
                            db()->insert('internal_notifications', [
                                'for_who' => 'admin',
                                'from_who' => 'system',
                                'icon' => 'fas fa-user',
                                'title' => l('global.notifications.new_user.title'),
                                'description' => sprintf(l('global.notifications.new_user.description'), $_POST['name'], $_POST['email']),
                                'url' => 'admin/user-view/' . $registered_user['user_id'],
                                'datetime' => get_date(),
                            ]);
                        }

                        /* Send internal notification if needed */
                        if(settings()->internal_notifications->admins_is_enabled && settings()->internal_notifications->new_newsletter_subscriber && $_POST['is_newsletter_subscribed']) {
                            db()->insert('internal_notifications', [
                                'for_who' => 'admin',
                                'from_who' => 'system',
                                'icon' => 'fas fa-newspaper',
                                'title' => l('global.notifications.new_newsletter_subscriber.title'),
                                'description' => sprintf(l('global.notifications.new_newsletter_subscriber.description'), $_POST['name'], $_POST['email']),
                                'url' => 'admin/user-view/' . $registered_user['user_id'],
                                'datetime' => get_date(),
                            ]);
                        }

                        /* Set a nice success message */
                        Alerts::add_success(l('register.success_message.login'));

                        session_set('user_id', $registered_user['user_id']);
                        session_set('user_password_hash', md5($registered_user['password']));

                        /* Link guest audits to the user */
                        $ip_md5 = md5(get_ip());
                        $guest_audits_count = db()->where('uploader_id', $ip_md5)->where('(user_id IS NULL OR user_id = 0)')->getValue('audits', 'count(*)');
                        if($guest_audits_count > 0) {
                            db()->where('uploader_id', $ip_md5)->where('(user_id IS NULL OR user_id = 0)')->update('audits', ['user_id' => $registered_user['user_id']]);

                            /* Deduct audit credits for the linked audits */
                            if($plan_id == 'free') {
                                db()->rawQuery("INSERT INTO ip_tracking (ip_md5, type, count, last_datetime) VALUES (?, 'audit_run_account', ?, NOW()) ON DUPLICATE KEY UPDATE count = count + ?, last_datetime = NOW()", [$registered_user['user_id'], $guest_audits_count, $guest_audits_count]);
                            } else {
                                db()->where('user_id', $registered_user['user_id'])->update('users', [
                                    'audit_audits_current_month' => db()->inc($guest_audits_count)
                                ]);
                            }

                            /* Deduct report view credits for the linked audits */
                            db()->rawQuery("INSERT INTO ip_tracking (ip_md5, type, count, last_datetime) VALUES (?, 'report_view_account', ?, NOW()) ON DUPLICATE KEY UPDATE count = count + ?, last_datetime = NOW()", [$registered_user['user_id'], $guest_audits_count, $guest_audits_count]);
                        }

                        Logger::users($registered_user['user_id'], 'login.success');

                        redirect(append_query_param($redirect, 'welcome=' . $registered_user['user_id']));
                    } else {

                        /* Prepare the email */
                        $email_template = get_email_template(
                            [
                                '{{NAME}}' => str_replace('.', '. ', $_POST['name']),
                            ],
                            l('global.emails.user_activation.subject'),
                            [
                                '{{ACTIVATION_LINK}}' => url('activate-user?email=' . md5($_POST['email']) . '&email_activation_code=' . $email_code . '&type=user_activation' . '&redirect=' . $redirect),
                                '{{NAME}}' => str_replace('.', '. ', $_POST['name']),
                            ],
                            l('global.emails.user_activation.body')
                        );

                        send_mail($_POST['email'], $email_template->subject, $email_template->body, ['capitalize' => true]);

                        /* Set a nice success message */
                        Alerts::add_success(l('register.success_message.registration'));
                    }
                } else {
                    Alerts::add_field_error('otp_token', 'Invalid OTP code.');
                }
            } else {

                /* Clean some posted variables */
                $_POST['name'] = input_clean_name($_POST['name'], 64);
                $_POST['email'] = input_clean_email($_POST['email'] ?? '');
                $_POST['is_newsletter_subscribed'] = settings()->users->register_display_newsletter_checkbox && isset($_POST['is_newsletter_subscribed']);

                /* Default variables */
                $values['name'] = $_POST['name'];
                $values['email'] = $_POST['email'];
                $values['password'] = $_POST['password'];

                /* Check for any errors */
                $required_fields = ['name', 'email' ,'password'];
                foreach($required_fields as $field) {
                    if(!isset($_POST[$field]) || trim($_POST[$field]) === '') {
                        Alerts::add_field_error($field, l('global.error_message.empty_field'));
                    }
                }

                if(settings()->captcha->register_is_enabled && !$captcha->is_valid()) {
                    Alerts::add_field_error('captcha', l('global.error_message.invalid_captcha'));
                }
                if(mb_strlen($_POST['name']) < 1 || mb_strlen($_POST['name']) > 64) {
                    Alerts::add_field_error('name', l('register.error_message.name_length'));
                }
                if(db()->where('email', $_POST['email'])->has('users')) {
                    Alerts::add_field_error('email', l('register.error_message.email_exists'));
                }
                if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    Alerts::add_field_error('email', l('global.error_message.invalid_email'));
                }
                if(!settings()->users->email_aliases_is_enabled && str_contains($_POST['email'], '+')) {
                    Alerts::add_field_error('email', l('register.error_message.email_aliases_not_allowed'));
                }
                if(mb_strlen($_POST['password']) < 8 || mb_strlen($_POST['password']) > 64) {
                    Alerts::add_field_error('password', l('global.error_message.password_length'));
                } else if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/', $_POST['password'])) {
                    Alerts::add_field_error('password', l('global.error_message.password_strength'));
                }

                /* Make sure the domain is not blacklisted */
                $email_domain = get_domain_from_email($_POST['email']);
                if(settings()->users->blacklisted_domains && in_array($email_domain, settings()->users->blacklisted_domains)) {
                    Alerts::add_field_error('email', l('register.error_message.blacklisted_domain'));
                }

                /* Email shield plugin */
                if(
                    \Altum\Plugin::is_active('email-shield') 
                    && settings()->email_shield->is_enabled 
                    && !in_array($email_domain, settings()->email_shield->whitelisted_domains ?? [])
                    && !\Altum\Plugin\EmailShield::validate($email_domain)
                ) {
                    Alerts::add_field_error('email', l('register.error_message.blacklisted_domain'));
                }

                /* Detect the location */
                try {
                    $maxmind = (get_maxmind_reader_country())->get(get_ip());
                } catch(\Exception $exception) { /* :) */ }
                $country = isset($maxmind) && isset($maxmind['country']) ? $maxmind['country']['iso_code'] : null;

                /* Make sure the country is not blacklisted */
                if($country && in_array($country, settings()->users->blacklisted_countries ?? [])) {
                    Alerts::add_error(l('register.error_message.blacklisted_country'));
                }

                /* Make sure the IP is not blacklisted */
                if(in_array(get_ip(), settings()->users->blacklisted_ips ?? [])) {
                    Alerts::add_error(l('register.error_message.blacklisted_country'));
                }
                /* Make sure to check against the limiter */
                if(settings()->users->register_lockout_is_enabled) {
                    $days_ago_datetime = (new \DateTime())->modify('-' . settings()->users->register_lockout_time . ' days')->format('Y-m-d H:i:s');

                    $recent_registrations = db()->where('ip', get_ip())->where('type', 'register.success')->where('datetime', $days_ago_datetime, '>=')->getValue('users_logs', 'COUNT(*)');

                    if($recent_registrations >= settings()->users->register_lockout_max_registrations) {
                        Alerts::add_error(sprintf(l('global.error_message.limit_try_again'), settings()->users->register_lockout_time, l('global.date.days')));
                        setcookie('register_lockout', 'true', time()+60*60*24*settings()->users->register_lockout_time, COOKIE_PATH);
                        $_COOKIE['register_lockout'] = 'true';
                    }
                }

                if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                    $otp = rand(100000, 999999);

                    session_set('otp_register_required', true);
                    session_set('otp_register_code', $otp);
                    session_set('otp_register_name', $_POST['name']);
                    session_set('otp_register_email', $_POST['email']);
                    session_set('otp_register_password', $_POST['password']);
                    session_set('otp_register_is_newsletter_subscribed', $_POST['is_newsletter_subscribed']);

                    $email_template = get_email_template(
                        [],
                        'Your Registration OTP Code',
                        [],
                        'Hello ' . $_POST['name'] . ', your registration OTP code is: <strong>' . $otp . '</strong>. Please enter this code on the registration page to complete your registration.'
                    );

                    send_mail($_POST['email'], $email_template->subject, $email_template->body, ['capitalize' => true]);

                    Alerts::add_info('A verification code has been sent to your email.');

                    redirect('register' . $redirect_append);
                }
            }
        }

        /* Main View */
        $data = [
            'values' => $values,
            'captcha' => $captcha,
            'redirect_append' => $redirect_append,
        ];

        $view = new \Altum\View('register/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
