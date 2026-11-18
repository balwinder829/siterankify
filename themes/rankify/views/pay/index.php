<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<script>
    'use strict';

    /* Declare some used variables inside javascript */
    window.altum.plan_id = $('input[name="plan_id"]').val();
    window.altum.monthly_price = $('input[name="monthly_price"]').val();
    window.altum.quarterly_price = $('input[name="quarterly_price"]').val();
    window.altum.biannual_price = $('input[name="biannual_price"]').val();
    window.altum.annual_price = $('input[name="annual_price"]').val();
    window.altum.lifetime_price = parseFloat($('input[name="lifetime_price"]').attr('data-base-price') || $('input[name="lifetime_price"]').val() || 0);
    window.altum.code = null;
    window.altum.allowed_trials = <?= settings()->payment->trial_require_card && $data->plan->trial_days && !$this->user->plan_trial_done && !isset($_GET['trial_skip']) ? json_encode(['stripe']) : json_encode([]) ?>;

    window.altum.payment_type_one_time_enabled = <?= json_encode((bool) in_array(settings()->payment->type, ['one_time', 'both'])) ?>;
    window.altum.payment_type_recurring_enabled = <?= json_encode((bool) in_array(settings()->payment->type, ['recurring', 'both'])) ?>;

    window.altum.taxes = <?= json_encode($data->plan_taxes ? $data->plan_taxes : null) ?>;
    window.altum.plan_prices = <?= json_encode($data->plan->prices) ?>;
    window.altum.currency = <?= json_encode(currency()) ?>;
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<div class="max-w-6xl mx-auto space-y-6 font-sans">
    <div class="flex items-center justify-between gap-4 d-print-none mb-4">
        <a href="<?= url('plan') ?>" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-slate-200/90 hover:bg-slate-50 text-slate-700 font-bold text-xs shadow-2xs transition-all text-decoration-none">
            <i class="fas fa-arrow-left text-[10px]"></i>
            <span>Back to Pricing</span>
        </a>
        <span class="text-xs font-semibold text-slate-400 hidden sm:flex items-center gap-1.5">
            <i class="fas fa-shield-alt text-emerald-500"></i>
            <span>256-Bit SSL Encrypted Checkout</span>
        </span>
    </div>

    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
        <nav aria-label="breadcrumb">
            <ol class="custom-breadcrumbs small">
                <li><a href="<?= url() ?>"><?= l('index.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
                <li><a href="<?= url('plan') ?>"><?= l('plan.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
                <?php if(settings()->payment->taxes_and_billing_is_enabled): ?>
                    <li><a href="<?= url('pay-billing/' . $data->plan_id) ?>"><?= l('pay_billing.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
                <?php endif ?>
                <li class="active" aria-current="page"><?= sprintf(l('pay.breadcrumb'), $data->plan->translations->{\Altum\Language::$name}->name ?? $data->plan->name) ?></li>
            </ol>
        </nav>
    <?php endif ?>

    <?php 
    $plan_display_name = $data->plan->translations->{\Altum\Language::$name}->name ?? $data->plan->name;
    $plan_display_name_cleaned = preg_replace('/\s+plan$/i', '', $plan_display_name);
    if(!settings()->payment->trial_require_card && $data->plan->trial_days && !$this->user->plan_trial_done && !isset($_GET['trial_skip'])): 
    ?>
        <div class="d-flex align-items-center mb-5">
            <h1 class="h3 m-0"><?= sprintf(l('pay.trial.header'), $plan_display_name_cleaned) ?></h1>
            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('pay.trial.subheader') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>

        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="row">
                <div class="col-12 col-xl-8 order-1 order-xl-0">
                    <button type="submit" name="submit" class="w-full py-3.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-500/20 transition-all border-0 cursor-pointer block"><?= sprintf(l('pay.trial.trial_start'), $data->plan->trial_days) ?></button>
                    <a href="<?= url('pay/' . $data->plan_id . '?trial_skip=true' . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null)) ?>" class="w-full py-3.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-sm transition-all border border-slate-200 text-center block text-decoration-none mt-2"><?= l('pay.trial.trial_skip') ?></a>

                    <div class="mt-3 text-muted text-center">
                        <small>
                            <?= sprintf(
                                    l('pay.accept'),
                                    '<a href="' . settings()->main->terms_and_conditions_url . '" target="_blank">' . l('global.terms_and_conditions') . '</a>',
                                    '<a href="' . settings()->main->privacy_policy_url . '" target="_blank">' . l('global.privacy_policy') . '</a>'
                             ) ?>
                        </small>
                    </div>

                </div>

                <div class="mb-5 col-12 col-xl-4 order-0 order-xl-1">
                    <div>
                        <div>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="flex h-2 w-2 rounded-full bg-blue-600"></span>
                                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider m-0"><?= l('pay.plan_details') ?></h2>
                            </div>

                            <div class="card border border-slate-200 rounded-2xl shadow-xs">
                                <div class="card-body p-4 sm:p-5">
                                    <?= (new \Altum\View('partials/plan_features'))->run(['plan_settings' => $data->plan->settings, 'plan_id' => $data->plan_id]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row"><div class="col-12 col-xl-8"></div></div>
        </form>

    <?php elseif(is_numeric($data->plan_id)): ?>

        <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <span class="text-[11px] font-bold text-blue-600 uppercase tracking-wider">Plan Checkout</span>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight m-0 mt-0.5"><?= sprintf(l('pay.custom_plan.header'), $plan_display_name_cleaned) ?></h1>
            </div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-50 border border-blue-200/60 text-blue-700 font-bold text-xs self-start sm:self-auto">
                <i class="fas fa-gem text-blue-600 text-xs"></i>
                <span><?= $plan_display_name ?></span>
            </div>
        </div>

        <form action="" method="post" enctype="multipart/form-data" role="form">
            <input type="hidden" name="plan_id" value="<?= $data->plan_id ?>" />
            <input type="hidden" name="monthly_price" value="<?= $data->plan->prices->monthly->{currency()} ?? 0 ?>" />
            <input type="hidden" name="quarterly_price" value="<?= $data->plan->prices->quarterly->{currency()} ?? 0 ?>" />
            <input type="hidden" name="biannual_price" value="<?= $data->plan->prices->biannual->{currency()} ?? 0 ?>" />
            <input type="hidden" name="annual_price" value="<?= $data->plan->prices->annual->{currency()} ?? 0 ?>" />
            <?php
            $lifetime_price_raw = (float) ($data->plan->prices->lifetime->{currency()} ?? 0);
            $addon_amount = (currency() === 'INR') ? 170.0 : 2.0;
            $lifetime_price = $lifetime_price_raw;
            if ($data->plan_id == 1 && isset($_GET['logo_addon']) && $_GET['logo_addon'] == 1) {
                $lifetime_price += $addon_amount;
            }
            ?>
            <input type="hidden" name="lifetime_price" value="<?= $lifetime_price ?>"
                   data-base-price="<?= $lifetime_price_raw ?>" />
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="row">
                <div class="col-12 col-lg-8">

                    <div class="flex items-center gap-2 mb-3">
                        <span class="flex h-2 w-2 rounded-full bg-blue-600"></span>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider m-0"><?= l('pay.custom_plan.payment_frequency') ?></h2>
                    </div>

                    <div>
                        <div class="row d-flex align-items-stretch mx-n2">

                            <?php
                            /* gather prices in current currency */
                            $monthly_price = (float) ($data->plan->prices->monthly->{currency()} ?? 0);
                            $quarterly_price = (float) ($data->plan->prices->quarterly->{currency()} ?? 0);
                            $biannual_price = (float) ($data->plan->prices->biannual->{currency()} ?? 0);
                            $annual_price = (float) ($data->plan->prices->annual->{currency()} ?? 0);

                            /* decide comparison base: monthly → quarterly → biannual */
                            $base_months = 0;
                            $base_price = 0;
                            $base_label = null;

                            if($monthly_price > 0) {
                                /* compare everything vs monthly */
                                $base_months = 1;
                                $base_price = $monthly_price;
                                $base_label = 'monthly';
                            } elseif($quarterly_price > 0) {
                                /* compare everything vs quarterly */
                                $base_months = 3;
                                $base_price = $quarterly_price;
                                $base_label = 'quarterly';
                            } elseif($biannual_price > 0) {
                                /* compare everything vs biannual */
                                $base_months = 6;
                                $base_price = $biannual_price;
                                $base_label = 'biannual';
                            }

                            /* compute savings (no lifetime), never negative, and never for the base itself */
                            $quarterly_price_savings = 0;
                            if($quarterly_price > 0 && $base_months > 0 && $base_label !== 'quarterly') {
                                $quarterly_price_savings = ceil(($base_price * (3 / $base_months)) - $quarterly_price);
                                $quarterly_price_savings = $quarterly_price_savings > 0 ? $quarterly_price_savings : 0;
                            }

                            $biannual_price_savings = 0;
                            if($biannual_price > 0 && $base_months > 0 && $base_label !== 'biannual') {
                                $biannual_price_savings = ceil(($base_price * (6 / $base_months)) - $biannual_price);
                                $biannual_price_savings = $biannual_price_savings > 0 ? $biannual_price_savings : 0;
                            }
                            
                            /* Hide savings badges if testing parameter is active */
                            if (isset($_GET['testing']) && $_GET['testing'] == '91a7c5b632ef4801') {
                                $quarterly_price_savings = 0;
                                $biannual_price_savings = 0;
                                $annual_price_savings = 0;
                            }

                            /* choose checked radio: prefer configured if available, else first available in order */
                            $default_checked_frequency = null;
                            $wanted = settings()->payment->default_payment_frequency ?? 'monthly';
                            if($wanted === 'monthly' && $monthly_price > 0) { $default_checked_frequency = 'monthly'; }
                            elseif($wanted === 'quarterly' && $quarterly_price > 0) { $default_checked_frequency = 'quarterly'; }
                            elseif($wanted === 'biannual' && $biannual_price > 0) { $default_checked_frequency = 'biannual'; }
                            elseif($wanted === 'annual' && $annual_price > 0) { $default_checked_frequency = 'annual'; }
                            elseif($wanted === 'lifetime' && $lifetime_price > 0) { $default_checked_frequency = 'lifetime'; }
                            elseif($monthly_price > 0) { $default_checked_frequency = 'monthly'; }
                            elseif($quarterly_price > 0) { $default_checked_frequency = 'quarterly'; }
                            elseif($biannual_price > 0) { $default_checked_frequency = 'biannual'; }
                            elseif($annual_price > 0) { $default_checked_frequency = 'annual'; }
                            elseif($lifetime_price > 0) { $default_checked_frequency = 'lifetime'; }
                            ?>

                            <?php if($monthly_price > 0): ?>
                                <label class="col-12 p-2 custom-radio-box m-0">
                                    <input type="radio" id="monthly_price" name="payment_frequency" value="monthly" class="custom-control-input" required="required" <?= $default_checked_frequency === 'monthly' ? 'checked="checked"' : null ?>>

                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between">
                                            <div class="card-title mb-0"><?= l('pay.custom_plan.monthly') ?></div>
                                            <div class="d-flex align-items-center">
                                                <span id="monthly_price_amount" class="custom-radio-box-main-text"><?= nr($monthly_price, 2) ?></span>
                                                <span class="ml-1"><?= currency() ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            <?php endif ?>

                            <?php if($quarterly_price > 0): ?>
                                <label class="col-12 p-2 custom-radio-box m-0">
                                    <input type="radio" id="quarterly_price" name="payment_frequency" value="quarterly" class="custom-control-input" required="required" <?= $default_checked_frequency === 'quarterly' ? 'checked="checked"' : null ?>>

                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between">
                                            <div class="card-title mb-0"><?= l('pay.custom_plan.quarterly') ?></div>

                                            <div class="d-flex align-items-center">
                                                <?php if($quarterly_price_savings > 0): ?>
                                                    <div class="payment-price-savings mr-2">
                                                        <span><?= sprintf(l('pay.custom_plan.savings'), '<span class="badge badge-success">-' . nr($quarterly_price_savings, 2, false), currency() . '</span>') ?></span>
                                                    </div>
                                                <?php endif ?>
                                                <span id="quarterly_price_amount" class="custom-radio-box-main-text"><?= nr($quarterly_price, 2) ?></span>
                                                <span class="ml-1"><?= currency() ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            <?php endif ?>

                            <?php if($biannual_price > 0): ?>
                                <label class="col-12 p-2 custom-radio-box m-0">
                                    <input type="radio" id="biannual_price" name="payment_frequency" value="biannual" class="custom-control-input" required="required" <?= $default_checked_frequency === 'biannual' ? 'checked="checked"' : null ?>>

                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between">
                                            <div class="card-title mb-0"><?= l('pay.custom_plan.biannual') ?></div>

                                            <div class="d-flex align-items-center">
                                                <?php if($biannual_price_savings > 0): ?>
                                                    <div class="payment-price-savings mr-2">
                                                        <span><?= sprintf(l('pay.custom_plan.savings'), '<span class="badge badge-success">-' . nr($biannual_price_savings, 2, false), currency() . '</span>') ?></span>
                                                    </div>
                                                <?php endif ?>
                                                <span id="biannual_price_amount" class="custom-radio-box-main-text"><?= nr($biannual_price, 2) ?></span>
                                                <span class="ml-1"><?= currency() ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            <?php endif ?>

                            <?php if($annual_price > 0): ?>
                                <label class="col-12 p-2 custom-radio-box m-0">
                                    <input type="radio" id="annual_price" name="payment_frequency" value="annual" class="custom-control-input" required="required" <?= $default_checked_frequency === 'annual' ? 'checked="checked"' : null ?>>

                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between">
                                            <div class="card-title mb-0"><?= l('pay.custom_plan.annual') ?></div>

                                            <div class="d-flex align-items-center">
                                                <?php if($annual_price_savings > 0): ?>
                                                    <div class="payment-price-savings mr-2">
                                                        <span><?= sprintf(l('pay.custom_plan.savings'), '<span class="badge badge-success">-' . nr($annual_price_savings, 2, false), currency() . '</span>') ?></span>
                                                    </div>
                                                <?php endif ?>
                                                <span id="annual_price_amount" class="custom-radio-box-main-text"><?= nr($annual_price, 2) ?></span>
                                                <span class="ml-1"><?= currency() ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            <?php endif ?>

                            <?php if($lifetime_price > 0): ?>
                                <label class="col-12 p-2 custom-radio-box m-0">
                                    <input type="radio" id="lifetime_price" name="payment_frequency" value="lifetime" class="custom-control-input" required="required" <?= $default_checked_frequency === 'lifetime' ? 'checked="checked"' : null ?>>

                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between">
                                            <div class="card-title mb-0"><?= l('pay.custom_plan.lifetime') ?></div>

                                            <div class="d-flex align-items-center">
                                                <div class="payment-price-savings mr-2">
                                                    <small><?= l('pay.custom_plan.lifetime_help') ?></small>
                                                </div>
                                                <span id="lifetime_price_amount" class="custom-radio-box-main-text"><?= nr($lifetime_price, 2) ?></span>
                                                <span class="ml-1"><?= currency() ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            <?php endif ?>

                        </div>
                    </div>

                    <div class="flex items-center gap-2 mt-8 mb-3">
                        <span class="flex h-2 w-2 rounded-full bg-blue-600"></span>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider m-0"><?= l('pay.custom_plan.payment_processor') ?></h2>
                    </div>

                    <?php
                    $at_least_one_payment_processor_is_enabled = null;
                    foreach($data->payment_processors as $key => $value) {
                        if(settings()->{$key}->is_enabled && in_array(currency(), settings()->{$key}->currencies ?? []))  {
                            $at_least_one_payment_processor_is_enabled = true;
                            break;
                        }
                    }
                    ?>


                    <?php if(!$at_least_one_payment_processor_is_enabled): ?>
                        <div class="row d-flex align-items-stretch mx-n2">
                            <label class="col-12 p-2 custom-radio-box m-0">
                                <div class="card border-warning">
                                    <div class="card-body d-flex align-items-center justify-content-between">
                                        <div class="card-title mb-0"><?= l('pay.custom_plan.no_processor') ?></div>

                                        <div>
                                            <span class="custom-radio-box-main-icon"><i class="fas fa-fw fa-exclamation-triangle"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    <?php else: ?>

                        <div>
                            <div class="row d-flex align-items-stretch mx-n2">
                                <?php foreach($data->payment_processors as $key => $value): ?>
                                    <?php if(settings()->{$key}->is_enabled && in_array(currency(), settings()->{$key}->currencies ?? [])): ?>
                                        <label class="col-6 p-2 custom-radio-box m-0">
                                            <input type="radio" name="payment_processor" value="<?= $key ?>" class="custom-control-input" required="required" <?= $key == settings()->payment->currencies->{currency()}->default_payment_processor ? 'checked="checked"' : null ?>>

                                            <div class="card">
                                                <div class="card-body d-flex flex-column align-items-center justify-content-between">
                                                    <div class="mb-3">
                                                        <span class="custom-radio-box-main-icon">
                                                            <i class="<?= $value['icon'] ?> fa-fw" style="color: <?= $value['color'] ?>"></i>
                                                        </span>
                                                    </div>

                                                    <div class="card-title mb-0"><?= l('pay.custom_plan.' . $key) ?></div>
                                                </div>
                                            </div>
                                        </label>
                                    <?php endif ?>
                                <?php endforeach ?>
                            </div>
                        </div>

                        <div id="offline_payment_processor_wrapper" style="display: none;">
                            <div class="form-group mt-4">
                                <label><?= l('pay.custom_plan.offline_payment_instructions') ?></label>
                                <div class="card"><div class="card-body"><?= nl2br(settings()->offline_payment->instructions) ?></div></div>
                            </div>

                            <div class="form-group mt-4">
                                <label><?= l('pay.custom_plan.offline_payment_proof') ?></label>
                                <input id="offline_payment_proof" type="file" name="offline_payment_proof" accept="<?= \Altum\Uploads::get_whitelisted_file_extensions_accept('offline_payment_proofs') ?>" class="form-control-file altum-file-input" />
                                <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('offline_payment_proofs'))  . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->offline_payment->proof_size_limit) ?></small>
                            </div>
                        </div>

                        <?php if(settings()->plisio->is_enabled): ?>
                            <?php $cryptocurrencies = require APP_PATH . 'includes/plisio_cryptocurrencies.php' ?>

                            <div id="plisio_processor_wrapper" style="display: none;">
                                <h2 class="h5 mt-5 mb-4"><i class="fas fa-fw fa-sm fa-coins mr-2"></i> <?= l('pay.custom_plan.cryptocurrency') ?></h2>

                                <div class="row d-flex align-items-stretch mx-n2">
                                    <?php foreach($cryptocurrencies as $token => $cryptocurrency): ?>
                                        <?php if(!in_array($token, settings()->plisio->accepted_cryptocurrencies ?? [])) continue; ?>

                                        <label class="col-6 p-2 custom-radio-box m-0">
                                            <input type="radio" name="cryptocurrency" value="<?= $token ?>" class="custom-control-input" <?= $token == settings()->plisio->default_cryptocurrency ? 'checked="checked"' : null ?>>

                                            <div class="card">
                                                <div class="card-body d-flex align-items-center">
                                                    <img src="<?= ASSETS_FULL_URL . 'images/cryptocurrencies/' . $cryptocurrency['icon'] ?>" class="cryptocurrency-icon mr-3" />

                                                    <div>
                                                        <div class="card-title mb-0"><?= $cryptocurrency['name'] ?></div>
                                                        <span class="small text-muted"><?= $cryptocurrency['code'] ?></span>
                                                    </div>

                                                </div>
                                            </div>
                                        </label>
                                    <?php endforeach ?>
                                </div>
                            </div>
                        <?php endif ?>

                        <?php if(settings()->plisio_whitelabel->is_enabled): ?>
                            <?php $cryptocurrencies = require APP_PATH . 'includes/plisio_cryptocurrencies.php' ?>

                            <div id="plisio_whitelabel_processor_wrapper" style="display: none;">
                                <h2 class="h5 mt-5 mb-4"><i class="fas fa-fw fa-sm fa-coins mr-2"></i> <?= l('pay.custom_plan.cryptocurrency') ?></h2>

                                <div class="row d-flex align-items-stretch mx-n2">
                                    <?php foreach($cryptocurrencies as $token => $cryptocurrency): ?>
                                        <?php if(!in_array($token, settings()->plisio_whitelabel->accepted_cryptocurrencies ?? [])) continue; ?>

                                        <label class="col-6 p-2 custom-radio-box m-0">
                                            <input type="radio" name="cryptocurrency" value="<?= $token ?>" class="custom-control-input" <?= $token == settings()->plisio_whitelabel->default_cryptocurrency ? 'checked="checked"' : null ?>>

                                            <div class="card">
                                                <div class="card-body d-flex align-items-center">
                                                    <img src="<?= ASSETS_FULL_URL . 'images/cryptocurrencies/' . $cryptocurrency['icon'] ?>" class="cryptocurrency-icon mr-3" />

                                                    <div>
                                                        <div class="card-title mb-0"><?= $cryptocurrency['name'] ?></div>
                                                        <span class="small text-muted"><?= $cryptocurrency['code'] ?></span>
                                                    </div>

                                                </div>
                                            </div>
                                        </label>
                                    <?php endforeach ?>
                                </div>
                            </div>
                        <?php endif ?>
                    <?php endif ?>

                    <div class="flex items-center gap-2 mt-8 mb-3">
                        <span class="flex h-2 w-2 rounded-full bg-blue-600"></span>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider m-0"><?= l('pay.custom_plan.payment_type') ?></h2>
                    </div>

                    <div>
                        <div class="row d-flex align-items-stretch mx-n2">

                            <label class="col-12 p-2 custom-radio-box m-0" id="one_time_type_label" <?= in_array(settings()->payment->type, ['one_time', 'both']) ? null : 'style="display: none"' ?>>
                                <input type="radio" id="one_time_type" name="payment_type" value="one_time" class="custom-control-input" required="required">

                                <div class="card">
                                    <div class="card-body d-flex align-items-center justify-content-between">

                                        <div class="card-title mb-0"><?= l('pay.custom_plan.one_time_type') ?></div>

                                        <div>
                                            <span class="custom-radio-box-main-icon"><i class="fas fa-fw fa-hand-holding-usd"></i></span>
                                        </div>

                                    </div>
                                </div>
                            </label>

                            <label class="col-12 p-2 custom-radio-box m-0" id="recurring_type_label" <?= in_array(settings()->payment->type, ['recurring', 'both']) ? null : 'style="display: none"' ?>>
                                <input type="radio" id="recurring_type" name="payment_type" value="recurring" class="custom-control-input" required="required">

                                <div class="card">
                                    <div class="card-body d-flex align-items-center justify-content-between">

                                        <div class="card-title mb-0"><?= l('pay.custom_plan.recurring_type') ?></div>

                                        <div>
                                            <span class="custom-radio-box-main-icon"><i class="fas fa-fw fa-sync-alt"></i></span>
                                        </div>

                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <?php if($data->plan_id == 1): ?>
                        <?php
                        $is_upgrade_only_addon = false;
                        ?>
                        <div class="flex items-center gap-2 mt-8 mb-3">
                            <span class="flex h-2 w-2 rounded-full bg-blue-600"></span>
                            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider m-0">Extra PDF Download</h2>
                        </div>

                        <div class="card mb-4 border rounded-2xl p-1" id="logo_addon_card" style="border-color: <?= (isset($_GET['logo_addon']) && $_GET['logo_addon'] == 1) ? '#2563eb' : '#e2e8f0' ?> !important; transition: border-color 0.2s;">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start">
                                    <!-- Visible checkbox -->
                                    <div class="mr-3" style="padding-top: 2px;">
                                        <input type="checkbox"
                                               id="logo_addon_checkout"
                                               class="h-4 w-4 rounded text-blue-600 focus:ring-blue-500 border-slate-300 shrink-0 cursor-pointer"
                                               <?= (isset($_GET['logo_addon']) && $_GET['logo_addon'] == 1) ? 'checked' : '' ?>>
                                    </div>
                                    <label for="logo_addon_checkout" style="cursor:pointer;margin:0;flex:1;">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                                            <div>
                                                <div class="font-bold mb-1 text-slate-900 text-sm">
                                                    Add Extra PDF Download
                                                    <span class="px-2 py-0.5 rounded-full bg-blue-50 text-blue-600 text-xs font-bold ml-2"><?= currency() === 'INR' ? '+ ₹170' : '+ $2' ?></span>
                                                </div>
                                                <small class="text-slate-500 text-xs">Get extra PDF download limits on your account.</small>
                                            </div>
                                            <i class="fas fa-file-pdf text-slate-400 text-xl ml-3 d-none d-sm-block"></i>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    <?php endif ?>

                </div>

                <div class="mt-5 mt-lg-0 col-12 col-lg-4">
                    <div class="sticky-top" style="top: 5rem; z-index: 10;">
                        <div class="mb-5">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="flex h-2 w-2 rounded-full bg-fuchsia-600"></span>
                                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider m-0"><?= l('pay.plan_details') ?></h2>
                            </div>

                            <div class="pt-2">
                                <div class="card border border-slate-200/90 rounded-2xl shadow-xs">
                                    <div class="card-body p-4 sm:p-5">
                                        <?= (new \Altum\View('partials/plan_features'))->run(['plan_settings' => $data->plan->settings, 'plan_id' => $data->plan_id]) ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden">
                            <div class="card-header bg-slate-50 border-b border-slate-100 text-slate-800 font-bold text-xs uppercase tracking-wider py-3.5 px-5">
                                <?= l('pay.custom_plan.summary.header') ?>
                            </div>

                            <div class="card-body p-5">

                                <div>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="text-muted">
                                            <?= l('pay.custom_plan.summary.plan') ?>
                                        </span>

                                        <span>
                                            <?= $data->plan->translations->{\Altum\Language::$name}->name ?? $data->plan->name ?>
                                        </span>
                                    </div>

                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="text-muted">
                                            <?= l('pay.custom_plan.summary.payment_frequency') ?>
                                        </span>

                                        <div id="summary_payment_frequency_monthly" style="display: none;">
                                            <div class="d-flex flex-column">
                                                <span class="text-right">
                                                    <?= l('pay.custom_plan.summary.monthly') ?>
                                                </span>
                                                <small class="text-right text-muted">
                                                    <?= l('pay.custom_plan.summary.monthly_help') ?>
                                                </small>
                                            </div>
                                        </div>

                                        <div id="summary_payment_frequency_quarterly" style="display: none;">
                                            <div class="d-flex flex-column">
                                                <span class="text-right">
                                                    <?= l('pay.custom_plan.summary.quarterly') ?>
                                                </span>
                                                <small class="text-right text-muted">
                                                    <?= l('pay.custom_plan.summary.quarterly_help') ?>
                                                </small>
                                            </div>
                                        </div>

                                        <div id="summary_payment_frequency_biannual" style="display: none;">
                                            <div class="d-flex flex-column">
                                                <span class="text-right">
                                                    <?= l('pay.custom_plan.summary.biannual') ?>
                                                </span>
                                                <small class="text-right text-muted">
                                                    <?= l('pay.custom_plan.summary.biannual_help') ?>
                                                </small>
                                            </div>
                                        </div>

                                        <div id="summary_payment_frequency_annual" style="display: none;">
                                            <div class="d-flex flex-column">
                                                <span class="text-right">
                                                    <?= l('pay.custom_plan.summary.annual') ?>
                                                </span>
                                                <small class="text-right text-muted">
                                                    <?= l('pay.custom_plan.summary.annual_help') ?>
                                                </small>
                                            </div>
                                        </div>

                                        <div id="summary_payment_frequency_lifetime" style="display: none;">
                                            <div class="d-flex flex-column">
                                                <span class="text-right">
                                                    <?= l('pay.custom_plan.summary.lifetime') ?>
                                                </span>
                                                <small class="text-right text-muted">
                                                    <?= l('pay.custom_plan.summary.lifetime_help') ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="text-muted">
                                            <?= l('pay.custom_plan.summary.payment_type') ?>
                                        </span>

                                        <div id="summary_payment_type_one_time" style="display: none;">
                                            <div class="d-flex flex-column">
                                                <span class="text-right">
                                                    <?= l('pay.custom_plan.summary.one_time') ?>
                                                </span>
                                                <small class="text-right text-muted">
                                                    <?= l('pay.custom_plan.summary.one_time_help') ?>
                                                </small>
                                            </div>
                                        </div>

                                        <div id="summary_payment_type_recurring" style="display: none;">
                                            <div class="d-flex flex-column">
                                                <span class="text-right">
                                                    <?= l('pay.custom_plan.summary.recurring') ?>
                                                </span>
                                                <small class="text-right text-muted">
                                                    <?= l('pay.custom_plan.summary.recurring_help') ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="text-muted">
                                            <?= l('pay.custom_plan.summary.payment_processor') ?>
                                        </span>

                                        <?php foreach($data->payment_processors as $key => $value): ?>
                                            <?php if(settings()->{$key}->is_enabled): ?>
                                                <span data-summary-payment-processor="<?= $key ?>" class="d-none">
                                                    <?= l('pay.custom_plan.' . $key) ?>
                                                </span>
                                            <?php endif ?>
                                        <?php endforeach ?>
                                    </div>

                                    <?php
                                    /* For upgrade-only users (already own the plan), hide the plan price
                                     * row initially — they only pay for the addon, not the plan again. */
                                     $is_upgrade_plan_row = false;
                                    ?>
                                    <div class="d-flex justify-content-between mb-3 <?= $is_upgrade_plan_row ? 'd-none' : '' ?>" id="summary_plan_price_row">
                                        <span class="text-muted">
                                            <?= l('pay.custom_plan.summary.plan_price') ?>
                                        </span>

                                        <div>
                                            <span id="summary_plan_price"></span>

                                            <span class="text-muted payment-currency"><?= currency() ?></span>
                                        </div>
                                    </div>

                                    <div id="summary_logo_addon" class="d-none">
                                        <div class="d-flex justify-content-between mb-3">
                                            <span class="text-muted">
                                                Extra PDF Download
                                            </span>

                                            <div>
                                                <span class="logo-addon-value"></span>

                                                <span class="text-muted payment-currency"><?= currency() ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="summary_discount" class="d-none">
                                        <div class="d-flex justify-content-between mb-3">
                                            <span class="text-muted">
                                                <?= l('pay.custom_plan.summary.discount') ?>
                                            </span>

                                            <div>
                                                <span class="discount-value"></span>

                                                <span class="text-muted payment-currency"><?= currency() ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="summary_taxes">
                                        <?php if($data->plan_taxes): ?>
                                            <?php foreach($data->plan_taxes as $row): ?>

                                                <div id="summary_tax_id_<?= $row->tax_id ?>" class="d-flex justify-content-between mb-3">
                                                    <div class="d-flex flex-column">
                                                        <span class="text-muted">
                                                            <?= $row->name ?>

                                                            <span data-toggle="tooltip" title="<?= $row->description ?>"><i class="fas fa-fw fa-sm fa-circle-question"></i></span>
                                                        </span>
                                                        <small class="text-muted">
                                                            <?= l('pay.custom_plan.summary.' . ($row->type == 'inclusive' ? 'tax_inclusive' : 'tax_exclusive')) ?>
                                                        </small>
                                                    </div>

                                                    <span>
                                                        <?php if($row->value_type == 'percentage'): ?>

                                                            <span class="tax-value"></span>
                                                            <span class="text-muted payment-currency"><?= currency() ?></span>
                                                            <span class="tax-details text-muted">(<?= $row->value ?>%)</span>

                                                        <?php elseif($row->value_type == 'fixed'): ?>

                                                            <span class="tax-value"></span>
                                                            <span class="tax-details"><?= '+' . $row->value ?> <span class="text-muted payment-currency"><?= currency() ?></span></span>

                                                        <?php endif ?>
                                                    </span>
                                                </div>

                                            <?php endforeach ?>
                                        <?php endif ?>
                                    </div>
                                </div>

                                <?php if(settings()->payment->codes_is_enabled): ?>
                                    <div class="mt-4">
                                        <button type="button" id="code_button" class="w-full py-2.5 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs border border-slate-200 transition-all cursor-pointer flex items-center justify-center gap-2">
                                            <i class="fas fa-tags text-slate-400 text-xs"></i>
                                            <span><?= l('pay.custom_plan.code_button') ?></span>
                                        </button>

                                        <div style="display: none;" id="code_block">
                                            <div class="form-group">
                                                <label for="code"><i class="fas fa-fw fa-sm fa-tags mr-1"></i> <?= l('pay.custom_plan.code') ?></label>
                                                <input id="code" type="text" name="code" class="form-control" />
                                                <div id="code_help"></div>
                                            </div>
                                        </div>
                                    </div>

                                <?php ob_start() ?>
                                    <script>
                                        'use strict';

                                        document.querySelector('#code_button').addEventListener('click', event => {
                                            document.querySelector('#code_block').style.display = '';
                                            document.querySelector('#code_button').style.display = 'none';

                                            event.preventDefault();
                                        });

                                        /* Function to check the discount code */
                                        let check_code = () => {
                                            let code = document.querySelector('input[name="code"]').value;

                                            /* Reset */
                                            if(code.trim() == '') {
                                                document.querySelector('input[name="code"]').classList.remove('is-invalid');
                                                document.querySelector('input[name="code"]').classList.remove('is-valid');
                                                altum.code = null;

                                                /* Change submit text */
                                                check_payment_submit_text();

                                                calculate_prices();

                                                return;
                                            }

                                            fetch(`${url}pay/code`, {
                                                method: 'POST',
                                                body: JSON.stringify({
                                                    code, global_token, plan_id: altum.plan_id
                                                }),
                                                headers: {
                                                    'Content-Type': 'application/json; charset=UTF-8'
                                                }
                                            })
                                                .then(response => {
                                                    return response.ok ? response.json() : Promise.reject(response);
                                                })
                                                .then(data => {
                                                    document.querySelector('#code_help').innerHTML = data.message;

                                                    if(data.status == 'success') {
                                                        document.querySelector('input[name="code"]').classList.add('is-valid');
                                                        document.querySelector('input[name="code"]').classList.remove('is-invalid');
                                                        document.querySelector('#code_help').classList.add('valid-feedback');
                                                        document.querySelector('#code_help').classList.remove('invalid-feedback');

                                                        /* Set the code variable */
                                                        altum.code = data.details.code;

                                                        /* Change submit text */
                                                        if(data.details.submit_text) {
                                                            document.querySelector('#submit_default_text').classList.add('d-none');
                                                            document.querySelector('#submit_trial_text').classList.add('d-none');
                                                            document.querySelector('#submit_text').classList.remove('d-none');
                                                            document.querySelector('#submit_text').innerText = data.details.submit_text;
                                                        } else {
                                                            check_payment_submit_text();
                                                        }

                                                    } else {
                                                        document.querySelector('input[name="code"]').classList.add('is-invalid');
                                                        document.querySelector('input[name="code"]').classList.remove('is-valid');
                                                        document.querySelector('#code_help').classList.add('invalid-feedback');
                                                        document.querySelector('#code_help').classList.remove('valid-feedback');

                                                        /* Set the code variable */
                                                        altum.code = null;

                                                        /* Change submit text */
                                                        check_payment_submit_text();
                                                    }

                                                    calculate_prices();
                                                })
                                                .catch(error => {});

                                        };

                                        /* Writing handler on the input */
                                        let timer = null;
                                        let timer_function = () => {
                                            clearTimeout(timer);

                                            timer = setTimeout(() => {
                                                check_code();
                                            }, 500);
                                        }

                                        document.querySelector('input[name="code"]').addEventListener('change', timer_function);
                                        document.querySelector('input[name="code"]').addEventListener('paste', timer_function);
                                        document.querySelector('input[name="code"]').addEventListener('keyup', timer_function);

                                        /* Autofill code field on header query */
                                        let current_url = new URL(window.location.href);

                                        if(current_url.searchParams.get('code')) {
                                            document.querySelector('#code_button').click();
                                            document.querySelector('input[name="code"]').value = current_url.searchParams.get('code');
                                            check_code();
                                        }

                                    </script>
                                    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
                                <?php endif ?>

                            </div>

                            <div class="card-footer bg-white border-t border-slate-100 p-5">
                                <div class="d-flex justify-content-between font-weight-bold text-slate-900 pb-3 border-b border-slate-100 mb-4">
                                    <span class="text-sm font-bold uppercase tracking-wider text-slate-700">
                                        <?= l('pay.custom_plan.summary.total') ?>
                                    </span>

                                    <div class="text-xl font-black text-slate-900">
                                        <span id="summary_total"></span>
                                        <span class="text-xs text-slate-400 font-bold payment-currency"><?= currency() ?></span>
                                    </div>
                                </div>

                                <div>
                                    <button type="submit" name="submit" class="w-full py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-500/20 transition-all cursor-pointer border-0 flex items-center justify-center gap-2">
                                        <i class="fas fa-lock text-xs"></i>
                                        <span id="submit_default_text"><?= l('pay.custom_plan.pay') ?></span>
                                        <span id="submit_text" class="d-none"><?= l('pay.custom_plan.pay') ?></span>
                                        <span id="submit_trial_text" class="d-none"><?= sprintf(l('pay.trial.trial_start'), $data->plan->trial_days) ?></span>
                                    </button>
                                </div>

                                <div class="mt-3 text-slate-400 text-center text-[11px]">
                                    <?= sprintf(
                                            l('pay.accept'),
                                            '<a href="' . settings()->main->terms_and_conditions_url . '" target="_blank" class="text-slate-600 font-semibold text-decoration-none">' . l('global.terms_and_conditions') . '</a>',
                                            '<a href="' . settings()->main->privacy_policy_url . '" target="_blank" class="text-slate-600 font-semibold text-decoration-none">' . l('global.privacy_policy') . '</a>'
                                    ) ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>

    <?php endif ?>
</div>


<?php ob_start() ?>
<script>
    'use strict';

    /* Attention message */
    let default_page_title = document.title;

    document.addEventListener('visibilitychange', event => {
        let is_page_active = !document.hidden;

        document.title = is_page_active ? default_page_title : <?= json_encode(l('pay.attention_title') . ' - ' . \Altum\Title::$site_title) ?>;
    });

    /* Cached elements */
    const $payment_frequency_inputs = $('[name="payment_frequency"]');
    const $payment_processor_inputs = $('[name="payment_processor"]');
    const $payment_type_inputs = $('[name="payment_type"]');
    const $summary_default = $('#submit_default_text');
    const $summary_normal = $('#submit_text');
    const $summary_trial = $('#submit_trial_text');

    /* Helpers */
    let apply_default_payment_type = () => {
        let default_payment_type = <?= json_encode(settings()->payment->default_payment_type ?? 'one_time') ?>;
        if($(`#${default_payment_type}_type`).is(':visible')) {
            $(`#${default_payment_type}_type`).click();
        } else {
            $('[name="payment_type"]').filter(':visible:first').click();
        }
    };

    /* Handlers */
    let check_payment_frequency = () => {
        let payment_frequency = document.querySelector('[name="payment_frequency"]:checked')?.value;
        const all_frequencies = ['monthly', 'quarterly', 'biannual', 'annual', 'lifetime'];

        /* Hide all summary sections first */
        all_frequencies.forEach(freq => {
            $(`#summary_payment_frequency_${freq}`).hide();
        });

        /* Hide both payment type labels by default */
        $('#one_time_type_label').hide();
        $('#recurring_type_label').hide();

        if(payment_frequency && all_frequencies.includes(payment_frequency)) {
            $(`#summary_payment_frequency_${payment_frequency}`).show();

            if(payment_frequency === 'lifetime') {
                /* Only one-time option for lifetime */
                $('#one_time_type_label').show();
            } else {
                /* Show available payment types */
                if(altum.payment_type_one_time_enabled) {
                    $('#one_time_type_label').show();
                }
                if(altum.payment_type_recurring_enabled) {
                    $('#recurring_type_label').show();
                }
            }
        }

        apply_default_payment_type();
    };

    let check_payment_processor = () => {
        let payment_processor = document.querySelector('[name="payment_processor"]:checked')?.value;

        if(!payment_processor) {
            return;
        }

        document.querySelectorAll(`[data-summary-payment-processor]:not([data-summary-payment-processor="${payment_processor}"])`).forEach(element => {
            element.classList.add('d-none');
        });

        document.querySelector(`[data-summary-payment-processor="${payment_processor}"]`).classList.remove('d-none');

        <?php
        $one_time_payment_processors = array_keys(array_filter($data->payment_processors, function ($item) {
            return $item['payment_type'] === ['one_time'];
        }));
        ?>
        let one_time_payment_processors = <?= json_encode($one_time_payment_processors) ?>;
        if(one_time_payment_processors.includes(payment_processor)) {
            $('#recurring_type_label').hide();
            $('#one_time_type_label').show();
        }

        const processor_map = {
            offline_payment: '#offline_payment_processor_wrapper',
            plisio: '#plisio_processor_wrapper',
            plisio_whitelabel: '#plisio_whitelabel_processor_wrapper'
        };

        $(Object.values(processor_map).join(',')).hide();

        if(processor_map[payment_processor]) {
            $(processor_map[payment_processor]).show();
        }

        apply_default_payment_type();
    };

    let check_payment_submit_text = () => {
        /* Check for trials */
        let payment_processor = document.querySelector('[name="payment_processor"]:checked')?.value;
        let payment_frequency = document.querySelector('[name="payment_frequency"]:checked')?.value;
        let payment_type = document.querySelector('[name="payment_type"]:checked')?.value;

        /* Change submit text */
        if(window.altum.allowed_trials.includes(payment_processor) && payment_frequency != 'lifetime' && payment_type == 'recurring') {
            $summary_default.addClass('d-none');
            $summary_normal.addClass('d-none');
            $summary_trial.removeClass('d-none');
        } else {
            $summary_default.addClass('d-none');
            $summary_normal.removeClass('d-none');
            $summary_trial.addClass('d-none');
        }
    };

    let calculate_prices = () => {
        let payment_frequency = document.querySelector('[name="payment_frequency"]:checked')?.value;

        let full_price = 0;
        let exclusive_taxes = 0;
        let price_without_inclusive_taxes = 0;
        let price_with_taxes = 0;

        let currency = window.altum.currency;
        let payment_processor = document.querySelector('[name="payment_processor"]:checked')?.value;

        if (payment_processor === 'paypal' && currency === 'INR') {
            currency = 'USD';
        }

        document.querySelectorAll('.payment-currency').forEach(el => {
            el.textContent = currency;
        });

        if (window.altum.plan_prices && window.altum.plan_prices[payment_frequency]) {
            let base_price_raw = parseFloat(window.altum.plan_prices[payment_frequency][currency] || 0);
            let is_testing = new URLSearchParams(window.location.search).get('testing') === '91a7c5b632ef4801';
            if (is_testing && base_price_raw > 0) {
                if (currency === 'INR') {
                    full_price = 1.0;
                } else {
                    full_price = 0.10;
                }
            } else {
                full_price = base_price_raw;
            }
        } else {
            full_price = altum[`${payment_frequency}_price`];
        }

        let price = parseFloat(full_price);
        let plan_price = price;
        let logo_addon_price = 0;

        const addonCheckbox = document.getElementById('logo_addon_checkout');
        const hasLogoAddon = addonCheckbox ? addonCheckbox.checked : (new URLSearchParams(window.location.search).get('logo_addon') === '1');

        if (altum.plan_id == '1' && hasLogoAddon) {
            const is_upgrade_only = false;
            const is_inr = (currency === 'INR');
            logo_addon_price = is_inr ? 170.0 : 2.0;

            if (is_upgrade_only) {
                /* User already owns the plan — plan price = 0, only charge addon */
                plan_price = 0;
            } else {
                /* New buyer: plan price is total minus addon */
                plan_price = Math.max(0, price - logo_addon_price);
            }

            document.querySelector('#summary_logo_addon').classList.remove('d-none');
            document.querySelector('#summary_logo_addon .logo-addon-value').innerHTML = nr(logo_addon_price, 2);
        } else {
            document.querySelector('#summary_logo_addon').classList.add('d-none');
        }

        /* Display the price; toggle visibility via Bootstrap d-none class (uses !important,
         * avoids conflict with d-flex). Show row only when plan_price > 0 (i.e. new buyer). */
        const planPriceEl = document.querySelector('#summary_plan_price');
        const planPriceRow = document.querySelector('#summary_plan_price_row');
        if (plan_price > 0) {
            if (planPriceEl) planPriceEl.innerHTML = nr(plan_price, 2);
            if (planPriceRow) planPriceRow.classList.remove('d-none');
        } else {
            /* Upgrade-only: user already has the plan, no plan charge — hide the row */
            if (planPriceRow) planPriceRow.classList.add('d-none');
        }

        /* Display taxes by default */
        document.querySelector('#summary_taxes').classList.remove('d-none');

        /* Check for potential discounts */
        if(altum.code) {
            altum.code.discount = parseInt(altum.code.discount);
            let discount_value = parseFloat((price * altum.code.discount / 100).toFixed(2));

            price = price - discount_value;

            /* Show it on the summary */
            document.querySelector('#summary_discount').classList.remove('d-none');
            document.querySelector('#summary_discount .discount-value').innerHTML = nr(-discount_value, 2);

            /* Check for redeemable code */
            if(altum.code.type == 'redeemable') {
                document.querySelector('#summary_taxes').classList.add('d-none');
            }
        } else {
            document.querySelector('#summary_discount').classList.add('d-none');
        }

        /* Calculate with taxes, if any */
        if(altum.taxes && altum.code?.type != 'redeemable') {

            /* Check for the inclusives */
            let inclusive_taxes = altum.taxes.filter(row => row.type == 'inclusive');
            let exclusive_taxes_rows = altum.taxes.filter(row => row.type == 'exclusive');

            let inclusive_taxes_total_percentage = inclusive_taxes.reduce((total, row) => {
                return total + parseInt(row.value);
            }, 0);

            let total_inclusive_tax = parseFloat((price - (price / (1 + inclusive_taxes_total_percentage / 100))).toFixed(2));

            inclusive_taxes.forEach(row => {
                let percentage_of_total_inclusive_tax = parseInt(row.value) * 100 / inclusive_taxes_total_percentage;

                let inclusive_tax = parseFloat(total_inclusive_tax * percentage_of_total_inclusive_tax / 100).toFixed(2)

                /* Display the value of the tax */
                $(`#summary_tax_id_${row.tax_id} .tax-value`).html(nr(inclusive_tax, 2));
            });

            price_without_inclusive_taxes = price - total_inclusive_tax;

            /* Check for the exclusives */
            let exclusive_taxes_array = [];

            exclusive_taxes_rows.forEach(row => {
                let exclusive_tax = parseFloat((row.value_type == 'percentage' ? price_without_inclusive_taxes * (parseFloat(row.value) / 100) : parseFloat(row.value)).toFixed(2));

                exclusive_taxes_array.push(exclusive_tax);

                /* Display the value of the tax */
                if(row.value_type == 'percentage') {
                    $(`#summary_tax_id_${row.tax_id} .tax-value`).html(`+${nr(exclusive_tax, 2)}`);
                }
            });

            exclusive_taxes = exclusive_taxes_array.reduce((total, number) => total + number, 0);

            /* Price with all the taxes */
            price_with_taxes = price + exclusive_taxes;

            price = price_with_taxes;
        }

        /* Display the total: use plan_price + logo_addon_price so the total is always correct
         * even when is_upgrade_only = true (where plan_price = 0) or when altum.lifetime_price
         * hasn't been updated yet by handle_logo_addon_checkout_change on first load. */
        let total_price = plan_price + logo_addon_price;
        if (altum.taxes && altum.code?.type != 'redeemable') {
            total_price = price; /* price already includes taxes computed above */
        }
        $('#summary_total').html(nr(total_price, 2));
    };

    /* Named event handlers */
    let handle_payment_frequency_change = event => {
        check_payment_frequency();
        check_payment_processor();
        check_payment_submit_text();
        calculate_prices();
    };

    let handle_payment_processor_change = event => {
        check_payment_frequency();
        check_payment_processor();
        check_payment_submit_text();
        calculate_prices();
    };

    let handle_payment_type_change = event => {
        let payment_type = document.querySelector('[name="payment_type"]:checked')?.value;

        switch(payment_type) {
            case 'one_time':

                $('#summary_payment_type_one_time').show();
                $('#summary_payment_type_recurring').hide();

                break;

            case 'recurring':

                $('#summary_payment_type_one_time').hide();
                $('#summary_payment_type_recurring').show();

                break;
        }

        check_payment_submit_text();
    };

    let handle_logo_addon_checkout_change = event => {
        const checkbox = document.getElementById('logo_addon_checkout');

        /* Only run addon-specific logic when the checkbox is present (plan_id == 1) */
        if (checkbox) {
            const isChecked = checkbox.checked;
            const urlParams = new URLSearchParams(window.location.search);

            if (isChecked) {
                urlParams.set('logo_addon', '1');
            } else {
                urlParams.delete('logo_addon');
            }
            window.history.replaceState({}, '', window.location.pathname + '?' + urlParams.toString());

            /* Update card border color for visual feedback */
            const addonCard = document.getElementById('logo_addon_card');
            if (addonCard) {
                addonCard.style.borderColor = isChecked ? '#28a745' : '#dee2e6';
            }

            const row = document.getElementById('wl_addon_feature_row');
            const icon = document.getElementById('wl_addon_feature_icon');
            const text = document.getElementById('wl_addon_feature_text');

            if (row && icon && text) {
                if (isChecked) {
                    row.style.display = '';
                    row.classList.remove('text-muted');
                    icon.setAttribute('class', 'fas fa-fw fa-sm mr-3 fa-check text-success');
                    text.setAttribute('class', 'text-dark font-weight-bold');
                } else {
                    row.style.display = 'none';
                    row.classList.add('text-muted');
                    icon.setAttribute('class', 'fas fa-fw fa-sm mr-3 fa-times text-muted');
                    text.setAttribute('class', '');
                }
            }

            const is_upgrade_only = <?= $is_upgrade_only_addon ? 'true' : 'false' ?>;
            const is_inr = <?= (currency() === 'INR') ? 'true' : 'false' ?>;
            const addon_amount = is_inr ? 170.0 : 2.0;

            /*
             * Use the actual base plan price from the hidden input (set by PHP).
             * Do NOT hardcode base_plan_price to 0 for upgrade-only users — that wiped out
             * the real plan price and made the plan price display show blank/0.
             * Instead, read the current altum.lifetime_price (which PHP already set correctly:
             *   - upgrade-only without addon: lifetime_price = addon_amount only (or actual price)
             *   - new buyer: lifetime_price = plan_price + addon_amount)
             * and recompute by adding or removing the addon amount.
             */
            const base_plan_price_input = document.querySelector('input[name="lifetime_price"]');
            const original_lifetime_price = base_plan_price_input ? parseFloat(base_plan_price_input.getAttribute('data-base-price') || base_plan_price_input.value) : 0;

            altum.lifetime_price = is_upgrade_only
                ? (isChecked ? addon_amount : 0.0)                             /* upgrade: only pay addon price */
                : (original_lifetime_price + (isChecked ? addon_amount : 0.0)); /* new buyer: base + optional addon */

            const lifetime_input = document.querySelector('input[name="lifetime_price"]');
            if (lifetime_input) {
                lifetime_input.value = altum.lifetime_price;
            }

            /* Prevent checkout of $0 when they already own the plan and unselect the addon upgrade */
            const submitBtn = document.querySelector('button[name="submit"]');
            if (submitBtn) {
                if (is_upgrade_only && !isChecked) {
                    submitBtn.disabled = true;
                    $('#submit_default_text, #submit_text').html("Select Addon to Upgrade");
                } else {
                    submitBtn.disabled = false;
                    $('#submit_default_text, #submit_text').html("<?= l('pay.custom_plan.pay') ?>");
                }
            }
        }

        /* Always recalculate prices regardless of addon checkbox presence */
        calculate_prices();
    };

    $payment_frequency_inputs.on('change', handle_payment_frequency_change);
    $payment_processor_inputs.on('change', handle_payment_processor_change);
    $payment_type_inputs.on('change', handle_payment_type_change);
    $('#logo_addon_checkout').on('change', handle_logo_addon_checkout_change);

    /* Init */
    let init_checkout = () => {
        /* Select default values */
        if(!document.querySelector('[name="payment_processor"]:checked')) {
            $payment_processor_inputs.first().click();
        }

        apply_default_payment_type();

        if(!document.querySelector('[name="payment_frequency"]:checked')) {
            $payment_frequency_inputs.first().click();
        }

        check_payment_frequency();
        check_payment_processor();
        check_payment_submit_text();

        /* Initial setup for logo addon check */
        handle_logo_addon_checkout_change();

        /*
         * Explicitly recalculate prices here because:
         * - jQuery .click() on an already-checked radio does NOT fire the change event
         * - handle_logo_addon_checkout_change early-exits for plans without the addon checkbox
         * Both cases leave #summary_plan_price and #summary_total blank without this call.
         */
        calculate_prices();
    };

    init_checkout();
</script>

<?php if($data->payment_extra_data && $data->payment_extra_data['payment_processor'] == 'paddle'): ?>
    <script src="https:/*cdn.paddle.com/paddle/paddle.js"></script> */

    <script>
        'use strict';

        Paddle.Setup({ vendor: <?= settings()->paddle->vendor_id ?> });

        <?php if(settings()->paddle->mode == 'sandbox'): ?>
        Paddle.Environment.set('<?= settings()->paddle->mode ?>');
        <?php endif ?>

        Paddle.Checkout.open({
            override: "<?= $data->payment_extra_data['url'] ?>"
        });
    </script>
<?php endif ?>


<?php if($data->payment_extra_data && $data->payment_extra_data['payment_processor'] == 'paddle_billing'): ?>
    <script src="https:/*cdn.paddle.com/paddle/v2/paddle.js"></script> */

    <script>
        'use strict';

        /* Set Paddle environment */
        <?php if(settings()->paddle_billing->mode == 'sandbox'): ?>
        Paddle.Environment.set('sandbox');
        <?php endif ?>

        /* Initialize Paddle Billing SDK */
        Paddle.Initialize({
            token: '<?= settings()->paddle_billing->client_side_token ?>'
        });

        /* Open Paddle Billing Checkout */
        Paddle.Checkout.open({
            settings: {
                variant: 'one-page',
                displayMode: 'overlay',
                theme: '<?= (\Altum\ThemeStyle::get() == 'dark' ? 'dark' : 'light') ?>',
                allowLogout: false,
                showAddTaxId: false,
                showAddDiscounts: false,
                successUrl: encodeURI('<?= $data->payment_extra_data['success_url'] ?>'),
                frameStyle: 'width:100%;min-width:312px;background-color:transparent;border:none;'
            },
            items: [{
                priceId: '<?= $data->payment_extra_data['price_id'] ?? '' ?>',
                quantity: 1
            }],
            customer: {
                email: <?= json_encode(user()->email) ?>,
            }
        });
    </script>
<?php endif ?>

<?php if($data->payment_extra_data && $data->payment_extra_data['payment_processor'] == 'razorpay'): ?>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <script>
        'use strict';

        var options = {
            "key": "<?= $data->payment_extra_data['key_id'] ?>",
            "subscription_id": "<?= $data->payment_extra_data['subscription_id'] ?>",
            "name": <?= json_encode($data->payment_extra_data['brand_name']) ?>,
            "description": <?= json_encode($data->payment_extra_data['description']) ?>,
            "prefill": {
                "name": <?= json_encode($data->payment_extra_data['name']) ?>,
                "email": <?= json_encode($data->payment_extra_data['email']) ?>,
                "contact": <?= json_encode($data->payment_extra_data['contact']) ?>
            },
            "handler": function (response) {
                var redirectUrl = "<?= $data->payment_extra_data['callback_url'] ?>" 
                    + "&razorpay_payment_id=" + response.razorpay_payment_id 
                    + "&razorpay_subscription_id=" + (response.razorpay_subscription_id || "") 
                    + "&razorpay_signature=" + response.razorpay_signature;
                window.location.href = redirectUrl;
            },
            "theme": {
                "color": "#2b84ea"
            }
        };
        var rzp = new Razorpay(options);
        rzp.open();
    </script>
<?php endif ?>

<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php if(settings()->plisio_whitelabel->is_enabled): ?>
    <?= include_view(THEME_PATH . 'views/pay/plisio_whitelabel_modal.php', ['payment_extra_data' => $data->payment_extra_data]) ?>
<?php endif ?>

