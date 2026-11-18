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

namespace Altum;

defined('ALTUMCODE') || die();

class Currency {
    public static $currency = null;
    public static $default_currency = null;

    /* Languages directory path */
    public static $path = APP_PATH . 'languages/';

    public static function initialize() {

        self::$default_currency = settings()->payment->default_currency;
        self::$currency = self::$default_currency;

        // Always ensure both USD and INR exist in settings for admin panel
        if (\Altum\Router::$path == 'admin') {
            if (!isset(settings()->payment->currencies)) {
                settings()->payment->currencies = new \stdClass();
            }
            if (!isset(settings()->payment->currencies->USD)) {
                settings()->payment->currencies->USD = new \stdClass();
                settings()->payment->currencies->USD->symbol = '$';
                settings()->payment->currencies->USD->default_payment_processor = 'stripe';
            }
            if (!isset(settings()->payment->currencies->INR)) {
                settings()->payment->currencies->INR = new \stdClass();
                settings()->payment->currencies->INR->symbol = '₹';
                settings()->payment->currencies->INR->default_payment_processor = 'razorpay';
            }
        }

        // Dynamic country IP-based payment and currency resolution
        $ip = get_ip();
        $country_code = null;

        // Cookie check to allow user selection override
        if (isset($_COOKIE['set_currency']) && in_array($_COOKIE['set_currency'], ['USD', 'INR'])) {
            $country_code = ($_COOKIE['set_currency'] === 'INR') ? 'IN' : 'US';
        }

        // Session check to allow testing overrides
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_GET['test_country'])) {
            $_SESSION['test_country'] = mb_strtoupper(trim($_GET['test_country']));
        }

        if (isset($_SESSION['test_country'])) {
            $country_code = $_SESSION['test_country'];
        }

        if (!$country_code && $ip && $ip !== '127.0.0.1' && $ip !== '::1') {
            try {
                $maxmind = get_maxmind_reader_country()->get($ip);
                $country_code = isset($maxmind) && isset($maxmind['country']) ? $maxmind['country']['iso_code'] : null;
            } catch(\Exception $exception) {
                /* Ignore */
            }
        }

        // Fallback for local development/Wamp (default to India)
        if (!$country_code) {
            $country_code = 'IN';
        }

        if ($country_code === 'IN') {
            self::$currency = 'INR';

            // Inject INR settings dynamically if not defined
            if (!isset(settings()->payment->currencies)) {
                settings()->payment->currencies = new \stdClass();
            }
            if (!isset(settings()->payment->currencies->INR)) {
                settings()->payment->currencies->INR = new \stdClass();
                settings()->payment->currencies->INR->symbol = '₹';
                settings()->payment->currencies->INR->default_payment_processor = 'razorpay';
            }

            // Ensure Razorpay is enabled and has INR supported
            if (isset(settings()->razorpay)) {
                settings()->razorpay->is_enabled = true;
                if (!isset(settings()->razorpay->currencies) || !is_array(settings()->razorpay->currencies)) {
                    settings()->razorpay->currencies = [];
                }
                if (!in_array('INR', settings()->razorpay->currencies)) {
                    settings()->razorpay->currencies[] = 'INR';
                }
            }

            // Ensure Stripe and PayPal support INR dynamically so they display alongside Razorpay
            foreach (['stripe', 'paypal'] as $gw) {
                if (isset(settings()->$gw)) {
                    if (!isset(settings()->$gw->currencies) || !is_array(settings()->$gw->currencies)) {
                        settings()->$gw->currencies = [];
                    }
                    if (!in_array('INR', settings()->$gw->currencies)) {
                        settings()->$gw->currencies[] = 'INR';
                    }
                }
            }
        } else {
            self::$currency = 'USD';

            if (!isset(settings()->payment->currencies)) {
                settings()->payment->currencies = new \stdClass();
            }
            if (!isset(settings()->payment->currencies->USD)) {
                settings()->payment->currencies->USD = new \stdClass();
                settings()->payment->currencies->USD->symbol = '$';
                settings()->payment->currencies->USD->default_payment_processor = 'stripe';
            }

            // Keep Razorpay enabled and allow USD payments
            if (isset(settings()->razorpay)) {
                settings()->razorpay->is_enabled = true;
                if (!isset(settings()->razorpay->currencies) || !is_array(settings()->razorpay->currencies)) {
                    settings()->razorpay->currencies = [];
                }
                if (!in_array('USD', settings()->razorpay->currencies)) {
                    settings()->razorpay->currencies[] = 'USD';
                }
            }
        }

    }

    public static function get_usd_to_inr_rate() {
        static $rate = null;
        if ($rate !== null) {
            return $rate;
        }

        $rate = 85.0; // default fallback
        try {
            $cache_instance = cache()->getItem('usd_to_inr_rate');
            if (is_null($cache_instance->get())) {
                $response = @file_get_contents('https://open.er-api.com/v6/latest/USD');
                if ($response) {
                    $data = json_decode($response);
                    if (isset($data->rates->INR)) {
                        $rate = (float) $data->rates->INR;
                        cache()->save($cache_instance->set($rate)->expiresAfter(86400)); // cache for 24h
                    }
                }
            } else {
                $rate = $cache_instance->get();
            }
        } catch (\Exception $e) {
            // Fallback
        }

        return $rate;
    }

}
