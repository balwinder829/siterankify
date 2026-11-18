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

namespace Altum\Models;

defined('ALTUMCODE') || die();

class Payments extends Model {

    public function webhook_process_payment($payment_processor, $external_payment_id, $payment_total, $payment_currency, $user_id, $plan_id, $payment_frequency, $code, $discount_amount, $base_amount, $taxes_ids, $payment_type, $payment_subscription_id, $payer_email, $payer_name) {
        
        /* Acquire a lock based on the external payment ID to prevent duplicate concurrent runs */
        $lock_handle = null;
        if (!empty($external_payment_id)) {
            $lock_file = UPLOADS_PATH . 'payment_lock_' . md5($payment_processor . '_' . $external_payment_id);
            $lock_handle = fopen($lock_file, 'w+');
            if ($lock_handle) {
                flock($lock_handle, LOCK_EX);
            }
        }

        try {
            /* Get the plan details */
            $plan = db()->where('plan_id', $plan_id)->getOne('plans');

            /* Just make sure the plan is still existing */
            if(!$plan) {
                return false;
            }

            /* Make sure the transaction is not already existing */
            if(db()->where('payment_id', $external_payment_id)->where('processor', $payment_processor)->has('payments')) {
                return false;
            }

            /* Make sure the account still exists */
            $user = db()->where('user_id', $user_id)->getOne('users');

            if(!$user) {
                return false;
            }

            /* Unsubscribe from the previous plan if needed */
            if(!empty($user->payment_subscription_id) && ($payment_subscription_id && $user->payment_subscription_id != $payment_subscription_id)) {
                try {
                    (new User())->cancel_subscription($user_id);
                } catch (\Exception $exception) {
                    if(DEBUG) {
                        error_log($exception->getMessage());
                    }
                    echo $exception->getMessage();
                    http_response_code(400); die();
                }
            }

            /* Check for potential trial plans */
            if($payment_total == 0) {
                /* Determine the expiration date of the plan */
                $plan_expiration_date = (new \DateTime())->modify('+' . $plan->trial_days . ' days')->format('Y-m-d H:i:s');

                /* Database query */
                db()->where('user_id', $user->user_id)->update('users', [
                    'plan_id' => $plan->plan_id,
                    'plan_settings' => $plan->settings,
                    'plan_expiration_date' => $plan_expiration_date,
                    'plan_trial_done' => 1,
                    'payment_subscription_id' => $payment_subscription_id,
                    'payment_processor' => $payment_processor,
                    'payment_total_amount' => $base_amount - $discount_amount,
                    'payment_currency' => $payment_currency,
                    'has_downloaded_report' => 0,
                ]);

                /* Clear the cache */
                cache()->deleteItemsByTag('user_id=' . $user->user_id);

                return;
            }

            /* Codes */
            $code = (new Payments())->codes_payment_check($code, $user);

            /* Currency exchange in case its needed */
            $total_amount_default_currency = $payment_total;

            if(settings()->payment->default_currency != $payment_currency && settings()->payment->currency_exchange_api_key) {
                try {
                    $response = \Unirest\Request::get('https://api.freecurrencyapi.com/v1/latest?apikey=' . settings()->payment->currency_exchange_api_key . '&base_currency=' . $payment_currency . '&currencies=' . settings()->payment->default_currency);

                    if($response->code == 200) {
                        $total_amount_default_currency = $payment_total * $response->body->data->{settings()->payment->default_currency};
                        $total_amount_default_currency = number_format($total_amount_default_currency, 2, '.', '');
                    }
                } catch (\Exception $exception) {
                    /* :) */
                }
            }

            if(empty($payer_email)) {
                $payer_email = $user->email;
            }

            if(empty($payer_name)) {
                $payer_name = $user->name;
            }

            $payment_datetime = get_date();

            /* Add a log into the database */
            $payment_id = db()->insert('payments', [
                'user_id' => $user_id,
                'plan_id' => $plan_id,
                'processor' => $payment_processor,
                'type' => $payment_type,
                'frequency' => $payment_frequency,
                'code' => $code ? $code->code : null,
                'discount_amount' => $discount_amount,
                'base_amount' => $base_amount,
                'email' => $payer_email,
                'payment_id' => $external_payment_id,
                'name' => $payer_name,
                'plan' => json_encode(db()->where('plan_id', $plan_id)->getOne('plans', ['plan_id', 'name'])),
                'billing' => settings()->payment->taxes_and_billing_is_enabled && $user->billing ? $user->billing : null,
                'business' => json_encode(settings()->business),
                'taxes_ids' => $taxes_ids,
                'total_amount' => $payment_total,
                'total_amount_default_currency' => $total_amount_default_currency,
                'currency' => $payment_currency,
                'datetime' => $payment_datetime
            ]);

            /* Update the user with the new plan */
            $current_plan_expiration_date = $plan_id == $user->plan_id ? $user->plan_expiration_date : '';
            if (empty($current_plan_expiration_date) || new \DateTime($current_plan_expiration_date) < new \DateTime()) {
                $current_plan_expiration_date = '';
            }
            $modifier = match ($payment_frequency) {
                'monthly' => '+30 days +12 hours',
                'quarterly' => '+3 months +12 hours',
                'biannual' => '+6 months +12 hours',
                'annual' => '+12 months +12 hours',
                'lifetime' => '+100 years +12 hours',
            };
            $plan_expiration_date = (new \DateTime($current_plan_expiration_date))->modify($modifier)->format('Y-m-d H:i:s');

            /* Database query */
            $preferences = json_decode($user->preferences ?? '{}');
            if (!is_object($preferences)) {
                $preferences = new \stdClass();
            }
            
            $has_logo = false;
            if (isset($_GET['logo_addon']) && $_GET['logo_addon'] == 1) {
                $has_logo = true;
            } else {
                if ($payment_currency == 'INR' && $payment_total >= 640) {
                    $has_logo = true;
                } elseif ($payment_currency == 'USD' && $payment_total >= 7) {
                    $has_logo = true;
                }
            }

            $extra_pdf_downloads = 0;
            if ($plan_id == 1) {
                if ($has_logo) {
                    $preferences->has_logo_addon = 1;
                    $extra_pdf_downloads = 1;
                } else {
                    unset($preferences->has_logo_addon);
                    $extra_pdf_downloads = 0;
                }
            } elseif (in_array($plan_id, [2, 3])) {
                unset($preferences->has_logo_addon);
                $extra_pdf_downloads = 0;
            } else {
                $extra_pdf_downloads = (int) ($user->extra_pdf_downloads ?? 0);
            }

            db()->where('user_id', $user_id)->update('users', [
                'plan_id' => $plan_id,
                'plan_settings' => $plan->settings,
                'plan_expiration_date' => $plan_expiration_date,
                'plan_expiry_reminder' => 0,
                'plan_trial_done' => 1,
                'payment_subscription_id' => $payment_subscription_id,
                'payment_processor' => $payment_processor,
                'payment_total_amount' => $payment_total,
                'payment_currency' => $payment_currency,
                'has_downloaded_report' => 0,
                'preferences' => json_encode($preferences),
                'extra_pdf_downloads' => $extra_pdf_downloads,
            ]);

            /* Run potential hooks */
            \Altum\CustomHooks::user_payment_finished(['user' => $user, 'plan' => $plan]);

            /* Clear the cache */
            cache()->deleteItemsByTag('user_id=' . $user_id);

            // Build Taxes HTML for email
            $taxes_html = '';
            $decoded_taxes_ids = json_decode($taxes_ids ?? '[]');
            $gst_added = false;
            if (is_array($decoded_taxes_ids) && count($decoded_taxes_ids) > 0) {
                foreach ($decoded_taxes_ids as $tax_id) {
                    if ($tax_id == 9999) {
                        $gst_added = true;
                        $taxes_html .= '
                        <tr>
                            <td style="padding: 8px; border-left: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000;">GST (18%):</td>
                            <td style="padding: 8px; border-left: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000; text-align: right; font-weight: bold;">' . number_format($base_amount * 0.18, 2) . ' ' . $payment_currency . '</td>
                        </tr>';
                    } else {
                        $tax_row = db()->where('tax_id', $tax_id)->getOne('taxes');
                        if ($tax_row) {
                            $tax_val = $tax_row->value_type == 'percentage' ? number_format($base_amount * ($tax_row->value / 100), 2) : number_format($tax_row->value, 2);
                            $taxes_html .= '
                            <tr>
                                <td style="padding: 8px; border-left: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000;">' . $tax_row->name . ' (' . $tax_row->value . ($tax_row->value_type == 'percentage' ? '%' : '') . '):</td>
                                <td style="padding: 8px; border-left: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000; text-align: right; font-weight: bold;">' . $tax_val . ' ' . $payment_currency . '</td>
                            </tr>';
                        }
                    }
                }
            }

            if ($payment_currency === 'USD' && !$gst_added) {
                $taxes_html .= '
                <tr>
                    <td style="padding: 8px; border-left: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000;">GST (18%):</td>
                    <td style="padding: 8px; border-left: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000; text-align: right; font-weight: bold;">' . number_format($base_amount * 0.18, 2) . ' ' . $payment_currency . '</td>
                </tr>';
            }

            $billing = settings()->payment->taxes_and_billing_is_enabled && $user->billing ? json_decode($user->billing) : null;
            $billing_html = '';
            if ($billing && !empty($billing->name)) {
                $billing_html .= '<div style="font-weight: bold;">' . $billing->name . '</div>';
                $billing_html .= '<div>' . $billing->address . '</div>';
                $billing_html .= '<div>' . $billing->city . (isset($billing->county) && $billing->county ? ', ' . $billing->county : '') . (isset($billing->zip) && $billing->zip ? ' - ' . $billing->zip : '') . '</div>';
                if (isset($billing->type) && $billing->type == 'business' && !empty($billing->tax_id)) {
                    $billing_html .= '<div style="font-weight: bold; margin-top: 4px;">GST - ' . $billing->tax_id . '</div>';
                }
            } else {
                $billing_html .= '<div style="font-weight: bold;">' . $payer_name . '</div>';
                $billing_html .= '<div>' . $payer_email . '</div>';
            }

            $tax_rate_val = 0;
            $tax_amount_val = 0;
            if (is_array($decoded_taxes_ids) && count($decoded_taxes_ids) > 0) {
                foreach ($decoded_taxes_ids as $tax_id) {
                    if ($tax_id == 9999) {
                        $tax_rate_val += 18;
                        $tax_amount_val += $base_amount * 0.18;
                    } else {
                        $tax_row = db()->where('tax_id', $tax_id)->getOne('taxes');
                        if ($tax_row) {
                            $tax_rate_val += $tax_row->value;
                            $tax_amount_val += ($tax_row->value_type == 'percentage' ? ($base_amount * ($tax_row->value / 100)) : $tax_row->value);
                        }
                    }
                }
            }
            if ($payment_currency === 'USD' && !$gst_added) {
                $tax_rate_val += 18;
                $tax_amount_val += $base_amount * 0.18;
            }

            // Build particulars table rows with empty filler rows
            $particulars_rows = '
            <tr>
                <td style="padding: 10px; border-left: 1px solid #000; border-right: 1px solid #000; font-size: 14px; font-weight: bold; vertical-align: top;">
                    ' . $plan->name . ' Plan (' . l('invoice.frequency.' . $payment_frequency) . ')
                </td>
                <td style="padding: 10px; border-left: 1px solid #000; border-right: 1px solid #000; font-size: 14px; text-align: center; vertical-align: top;">
                    ' . ($tax_amount_val > 0 ? '✔' : '') . '
                </td>
                <td style="padding: 10px; border-left: 1px solid #000; border-right: 1px solid #000; font-size: 14px; text-align: right; font-weight: bold; vertical-align: top;">
                    ' . number_format($base_amount, 2) . '
                </td>
            </tr>';

            if ($discount_amount > 0) {
                $particulars_rows .= '
                <tr>
                    <td style="padding: 10px; border-left: 1px solid #000; border-right: 1px solid #000; font-size: 14px; vertical-align: top;">
                        Discount' . ($code ? ': ' . $code->code : '') . '
                    </td>
                    <td style="padding: 10px; border-left: 1px solid #000; border-right: 1px solid #000; font-size: 14px; text-align: center;"></td>
                    <td style="padding: 10px; border-left: 1px solid #000; border-right: 1px solid #000; font-size: 14px; text-align: right; color: red;">
                        -' . number_format($discount_amount, 2) . '
                    </td>
                </tr>';
            }

            // Add empty filler rows to match the view invoice style
            $filler_count = $discount_amount > 0 ? 5 : 6;
            for ($i = 0; $i < $filler_count; $i++) {
                $border_bottom_style = ($i == $filler_count - 1) ? ' border-bottom: 1px solid #000;' : '';
                $particulars_rows .= '
                <tr>
                    <td style="padding: 10px; border-left: 1px solid #000; border-right: 1px solid #000;' . $border_bottom_style . ' height: 30px;"></td>
                    <td style="padding: 10px; border-left: 1px solid #000; border-right: 1px solid #000;' . $border_bottom_style . '"></td>
                    <td style="padding: 10px; border-left: 1px solid #000; border-right: 1px solid #000;' . $border_bottom_style . '"></td>
                </tr>';
            }

            $logo_src = settings()->main->logo_light ? settings()->main->logo_light_full_url : '';
            $logo_html = $logo_src ? '<img src="' . $logo_src . '" style="max-height: 70px; margin-bottom: 10px;" alt="logo" />' : '<h2 style="font-weight: bold; color: #1b365d; margin: 0 0 10px 0;">' . (settings()->business->brand_name ?? 'INFOTRENCH TECHNOLOGIES LLP') . '</h2>';

            $invoice_html = '
            <br />
            <div style="background: #ffffff; padding: 30px; font-family: Arial, sans-serif; color: #000; max-width: 700px; margin: 0 auto; border: 1px solid #ddd; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">
                <!-- Header Table -->
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 25px;">
                    <tr>
                        <td style="width: 60%; vertical-align: top; text-align: left;">
                            ' . $logo_html . '
                            <div style="line-height: 1.4; font-size: 13px; margin-top: 10px;">
                                <div>504, Navniti Apartment</div>
                                <div>Patparganj Delhi - 92</div>
                                <div>Phone: +91 9540041787</div>
                                <div>Phone: <img src="https://img.icons8.com/color/24/000000/whatsapp--v1.png" style="vertical-align: middle; width: 14px; height: 14px; margin-right: 3px;" />+91 7838025290</div>
                                <div>Website: ' . $_SERVER['HTTP_HOST'] . '</div>
                            </div>
                        </td>
                        <td style="width: 40%; vertical-align: top; text-align: right;">
                            <div style="color: #4f81bd; font-size: 24px; text-transform: uppercase; margin-bottom: 10px;">TAX Invoice</div>
                            <table style="width: 100%; border-collapse: collapse; border: 1px solid #000; font-size: 13px; text-align: center;">
                                <thead>
                                    <tr style="background-color: #f2f2f2;">
                                        <th style="border: 1px solid #000; padding: 6px; font-weight: bold;">DATE</th>
                                        <th style="border: 1px solid #000; padding: 6px; font-weight: bold;">INVOICE</th>
                                        <th style="border: 1px solid #000; padding: 6px; font-weight: bold;">GST</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="border: 1px solid #000; padding: 6px;">' . \Altum\Date::get($payment_datetime, 1) . '</td>
                                        <td style="border: 1px solid #000; padding: 6px;">' . (settings()->business->invoice_nr_prefix ?? '') . str_pad($payment_id, 5, '0', STR_PAD_LEFT) . '</td>
                                        <td style="border: 1px solid #000; padding: 6px;">' . (settings()->business->tax_id ?? '07AAFFI2969B1ZH') . '</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>

                <!-- BILL TO Section -->
                <div style="background-color: #1b365d; color: #ffffff; padding: 6px 12px; font-weight: bold; font-size: 14px; text-transform: uppercase; margin-bottom: 10px;">
                    BILL TO
                </div>
                <table style="width: 100%; border-collapse: collapse; font-size: 13px; line-height: 1.5; margin-bottom: 25px;">
                    <tr>
                        <td style="width: 55%; vertical-align: top; text-align: left; padding-right: 15px;">
                            ' . $billing_html . '
                        </td>
                        <td style="width: 45%; vertical-align: top; text-align: left; padding-left: 15px; border-left: 2px solid #000;">
                            <div style="font-weight: bold;">Infotrench Technologies LLP</div>
                            <div>GST: 09AAFFI2969B1ZD</div>
                            <div>Address: Greater Noida WEST</div>
                        </td>
                    </tr>
                </table>

                <!-- Particulars Table -->
                <table style="width: 100%; border-collapse: collapse; border: 1px solid #000; margin-bottom: 25px;">
                    <thead>
                        <tr style="background-color: #1b365d; color: #ffffff;">
                            <th style="padding: 10px; text-align: left; border: 1px solid #000; font-size: 13px; text-transform: uppercase;">PARTICULAR</th>
                            <th style="padding: 10px; text-align: center; border: 1px solid #000; font-size: 13px; text-transform: uppercase; width: 15%;">TAXED</th>
                            <th style="padding: 10px; text-align: right; border: 1px solid #000; font-size: 13px; text-transform: uppercase; width: 20%;">AMOUNT</th>
                        </tr>
                    </thead>
                    <tbody>
                        ' . $particulars_rows . '
                    </tbody>
                </table>

                <!-- Bottom Totals & Payments Row -->
                <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                    <tr>
                        <!-- Left Column: Comments -->
                        <td style="width: 55%; vertical-align: top; padding-right: 15px;">
                            <div style="background-color: #1b365d; color: #ffffff; padding: 6px 12px; font-weight: bold; font-size: 13px; text-transform: uppercase; margin-bottom: 10px;">
                                OTHER COMMENTS
                            </div>
                            <div style="border: 1px solid #000; min-height: 100px; padding: 10px; font-size: 12px; line-height: 1.4;">
                                Thank you for your purchase. Please keep this invoice for your records.
                            </div>
                        </td>
                        <!-- Right Column: Totals & Payments -->
                        <td style="width: 45%; vertical-align: top;">
                            <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                                <tr>
                                    <td style="padding: 6px; text-align: left;">Subtotal</td>
                                    <td style="padding: 6px; text-align: right; font-weight: bold;">' . number_format($base_amount, 2) . '</td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px; text-align: left;">Tax amount</td>
                                    <td style="padding: 6px; text-align: right; font-weight: bold;">' . number_format($tax_amount_val, 2) . '</td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px; text-align: left; vertical-align: middle;">Tax rate</td>
                                    <td style="padding: 6px; text-align: right;">
                                        <span style="border: 1px solid #000; padding: 2px 8px; font-weight: bold; background-color: #ffffff;">' . number_format($tax_rate_val, 3) . '%</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px; text-align: left;">Other</td>
                                    <td style="padding: 6px; text-align: right; font-weight: bold;">-</td>
                                </tr>
                                <tr style="background-color: #b4c6e7; font-weight: bold;">
                                    <td style="padding: 8px; border-top: 1px solid #000; border-bottom: 1px solid #000; text-transform: uppercase;">TOTAL</td>
                                    <td style="padding: 8px; border-top: 1px solid #000; border-bottom: 1px solid #000; text-align: right; font-size: 14px;">' . number_format($payment_total, 2) . ' ' . $payment_currency . '</td>
                                </tr>
                            </table>

                            <div style="font-size: 12px; line-height: 1.5; margin-top: 20px; text-align: left;">
                                <div style="font-weight: bold;">Make all cheques payable to</div>
                                <div style="font-weight: bold; color: #1b365d; font-size: 13px;">' . (settings()->business->name ?? 'Infotrench Technologies LLP') . '</div>
                                <div style="margin-top: 4px;">Account no - 081605009654</div>
                                <div>ifsc code : ICIC0000816</div>
                            </div>
                        </td>
                    </tr>
                </table>

                <!-- Footer -->
                <div style="margin-top: 35px; border-top: 1px dashed #000; padding-top: 15px; text-align: center; font-size: 12px; line-height: 1.5;">
                    <div>If you have any questions about this invoice, please contact</div>
                    <div style="font-weight: bold;">[Name: Rahul, Phone #9540041787, info@infotrench.com]</div>
                    <div style="font-style: italic; font-weight: bold; font-size: 14px; margin-top: 10px;">Thank You For Your Business!</div>
                </div>
            </div>
            <br />';

            $email_body = l('global.emails.user_payment.body');
            if ($payment_frequency == 'lifetime') {
                $email_body = str_replace(
                    ['until <strong>{{PLAN_EXPIRATION_DATE}}</strong>', 'until {{PLAN_EXPIRATION_DATE}}'],
                    'with <strong>Lifetime Access</strong>',
                    $email_body
                );
            }

            /* Send notification to the user */
            $email_template = get_email_template(
                [],
                'Invoice - ' . l('global.emails.user_payment.subject'),
                [
                    '{{NAME}}' => $user->name,
                    '{{PLAN_NAME}}' => $plan->name,
                    '{{PLAN_EXPIRATION_DATE}}' => \Altum\Date::get($plan_expiration_date, 2),
                    '{{USER_PLAN_LINK}}' => url('account-plan'),
                    '{{USER_PAYMENTS_LINK}}' => url('account-payments'),
                    '{{INVOICE_LINK}}' => url('invoice/' . $payment_id . '?download=1&token=' . md5($payment_id . $payment_datetime)),
                ],
                $email_body . $invoice_html . '<br /><br /><a href="{{INVOICE_LINK}}" class="cta">Download Invoice</a>'
            );

            send_mail($user->email, $email_template->subject, $email_template->body, ['anti_phishing_code' => $user->anti_phishing_code, 'language' => $user->language]);

            /* Send notification to admin if needed */
            if(settings()->email_notifications->new_payment && !empty(settings()->email_notifications->emails)) {

                $email_template = get_email_template(
                    [
                        '{{PROCESSOR}}' => l('pay.custom_plan.' . $payment_processor),
                        '{{TOTAL_AMOUNT}}' => $payment_total,
                        '{{CURRENCY}}' => $payment_currency,
                    ],
                    l('global.emails.admin_new_payment_notification.subject'),
                    [
                        '{{PROCESSOR}}' => l('pay.custom_plan.' . $payment_processor),
                        '{{TOTAL_AMOUNT}}' => $payment_total,
                        '{{CURRENCY}}' => $payment_currency,
                        '{{NAME}}' => $user->name,
                        '{{EMAIL}}' => $user->email,
                        '{{PLAN_NAME}}' => $plan->name,
                        '{{PAYMENT_FREQUENCY}}' => l('plan.custom_plan.' . $payment_frequency),
                        '{{PAYMENT_TYPE}}' => l('pay.custom_plan.' . $payment_type . '_type'),
                        '{{PAYMENT_ID}}' => $payment_id,
                        '{{EXTERNAL_PAYMENT_ID}}' => $external_payment_id,
                        '{{PAYMENT_LINK}}' => url('admin/payments?id=' . $payment_id),
                        '{{INVOICE_LINK}}' => url('admin/invoice/' . $payment_id),
                        '{{DATE}}' => get_date(),
                        '{{DATE_TIMEZONE}}' => \Altum\Date::$default_timezone,
                        '{{CODE}}' => $code ?: l('global.none'),
                        '{{CODE_DETAILS}}' => $code ? '(-' . $discount_amount . ' ' . $payment_currency . ')' : '',
                        '{{DISCOUNT_AMOUNT}}' => $discount_amount,
                        '{{PAYMENT_STATUS}}' => l('account_payments.status_approved'),
                    ],
                    '<div style="background: #f9f9f9; padding: 25px; border-radius: 12px; font-family: sans-serif; border: 1px solid #eee;">' .
                    '<h2 style="color: #333; margin-top: 0; font-size: 20px;">New Payment Received</h2>' .
                    '<p style="color: #666; font-size: 14px; margin-bottom: 20px;">A new payment has been successfully processed on SiteAnalysisTool.</p>' .
                    '<table style="width: 100%; border-collapse: collapse; margin-bottom: 25px;">' .
                    '<tr><td style="padding: 10px 0; border-bottom: 1px solid #eee; color: #888; font-size: 13px;">User Info</td><td style="padding: 10px 0; border-bottom: 1px solid #eee; text-align: right; font-weight: bold; font-size: 14px;">{{NAME}}<br><small style="font-weight: normal; color: #999;">{{EMAIL}}</small></td></tr>' .
                    '<tr><td style="padding: 10px 0; border-bottom: 1px solid #eee; color: #888; font-size: 13px;">Plan Purchased</td><td style="padding: 10px 0; border-bottom: 1px solid #eee; text-align: right; font-weight: bold; font-size: 14px;">{{PLAN_NAME}}</td></tr>' .
                    '<tr><td style="padding: 10px 0; border-bottom: 1px solid #eee; color: #888; font-size: 13px;">Total Amount</td><td style="padding: 10px 0; border-bottom: 1px solid #eee; text-align: right; font-weight: bold; font-size: 16px; color: #28a745;">{{TOTAL_AMOUNT}} {{CURRENCY}}</td></tr>' .
                    '<tr><td style="padding: 10px 0; border-bottom: 1px solid #eee; color: #888; font-size: 13px;">Processor</td><td style="padding: 10px 0; border-bottom: 1px solid #eee; text-align: right; font-size: 14px; font-weight: bold;">{{PROCESSOR}}</td></tr>' .
                    '<tr><td style="padding: 10px 0; color: #888; font-size: 13px;">Payment ID</td><td style="padding: 10px 0; text-align: right; font-family: monospace; font-size: 13px;">#{{PAYMENT_ID}}</td></tr>' .
                    '</table>' .
                    '<div style="text-align: center; margin-top: 10px;">' .
                    '<a href="{{PAYMENT_LINK}}" style="display: inline-block; padding: 12px 22px; background-color: #3f3d56; color: #ffffff !important; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 14px; margin-right: 12px;">View Payment</a>' .
                    '<a href="{{INVOICE_LINK}}" style="display: inline-block; padding: 12px 22px; background-color: #6c63ff; color: #ffffff !important; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 14px;">View Invoice</a>' .
                    '</div></div>'
                );

                send_mail(explode(',', settings()->email_notifications->emails), $email_template->subject, $email_template->body);

            }

            /* Send webhook notification if needed */
            if(settings()->webhooks->payment_new) {

                fire_and_forget('post', settings()->webhooks->payment_new, [
                    'user_id' => $user->user_id,
                    'email' => $user->email,
                    'name' => $user->name,
                    'plan_id' => $plan_id,
                    'plan_expiration_date' => $plan_expiration_date,
                    'payment_id' => $payment_id,
                    'payment_processor' => $payment_processor,
                    'payment_type' => $payment_type,
                    'payment_frequency' => $payment_frequency,
                    'payment_total_amount' => $payment_total,
                    'payment_currency' => $payment_currency,
                    'payment_code' => $code->code,
                    'datetime' => $payment_datetime,
                ]);

            }

            if(settings()->internal_notifications->admins_is_enabled && settings()->internal_notifications->new_payment) {
                db()->insert('internal_notifications', [
                    'for_who' => 'admin',
                    'from_who' => 'system',
                    'icon' => 'fas fa-credit-card',
                    'title' => l('global.notifications.new_payment.title'),
                    'description' => sprintf(l('global.notifications.new_payment.description'), $user->name, $user->email, $payment_total, $payment_currency, l('pay.custom_plan.' . $payment_processor)),
                    'url' => 'admin/payments',
                    'datetime' => get_date(),
                ]);
            }

            /* Affiliate */
            (new Payments())->affiliate_payment_check($payment_id, $total_amount_default_currency, settings()->payment->default_currency, $user);
        } finally {
            if ($lock_handle) {
                flock($lock_handle, LOCK_UN);
                fclose($lock_handle);
                @unlink($lock_file);
            }
        }
    }

    public function codes_payment_check($code, $user) {
        /* Make sure the code exists */
        $codes_code = db()->where('code', $code)->where('type', 'discount')->getOne('codes');

        if($codes_code) {
            /* Check if we should insert the usage of the code or not */
            if(!db()->where('user_id', $user->user_id)->where('code_id', $codes_code->code_id)->has('redeemed_codes')) {

                /* Update the code usage */
                db()->where('code_id', $codes_code->code_id)->update('codes', ['redeemed' => db()->inc()]);

                /* Add log for the redeemed code */
                db()->insert('redeemed_codes', [
                    'code_id'   => $codes_code->code_id,
                    'user_id'   => $user->user_id,
                    'datetime'  => get_date()
                ]);
            }

            return $codes_code;
        }

        return null;
    }

    public function affiliate_payment_check($payment_id, $payment_total, $payment_currency, $user) {
        if(\Altum\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled && $user->referred_by) {
            if((settings()->affiliate->commission_type == 'once' && !$user->referred_by_has_converted) || settings()->affiliate->commission_type == 'forever') {
                $referral_user = db()->where('user_id', $user->referred_by)->getOne('users', ['user_id', 'email', 'status', 'plan_settings']);
                $referral_user->plan_settings = json_decode($referral_user->plan_settings);

                /* Make sure the referral user is active and existing */
                if($referral_user && $referral_user->status == 1) {
                    $amount = number_format($payment_total * (float) $referral_user->plan_settings->affiliate_commission_percentage / 100, 2, '.', '');

                    /* Insert the affiliate commission */
                    db()->insert('affiliates_commissions', [
                        'user_id' => $referral_user->user_id,
                        'referred_user_id' => $user->user_id,
                        'payment_id' => $payment_id,
                        'amount' => $amount,
                        'currency' => $payment_currency,
                        'datetime' => get_date()
                    ]);

                    /* Update the referred user */
                    db()->where('user_id', $user->user_id)->update('users', ['referred_by_has_converted' => 1]);
                }
            }
        }
    }

}
