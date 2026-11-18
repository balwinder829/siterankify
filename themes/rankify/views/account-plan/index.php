<?php defined('ALTUMCODE') || die() ?>

<div class="max-w-4xl mx-auto space-y-6 font-sans">
    <?= \Altum\Alerts::output_alerts() ?>

    <?= $this->views['account_header_menu'] ?>

    <div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-4">
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
                    <div class="space-y-4">

                        <h1 class="h4"><?= sprintf(l('account_plan.header'), $this->user->plan->translations->{\Altum\Language::$name}->name ?? $this->user->plan->name) ?></h1>

                        <?php if($this->user->plan_id != 'free'): ?>
                            <p class="text-slate-500 ">
                                <?=
                                (new \DateTime($this->user->plan_expiration_date)) < (new \DateTime())->modify('+10 years') ?
                                        ($this->user->payment_subscription_id ?
                                                '<i class="fas fa-fw fa-sm fa-rotate mr-1"></i>' . sprintf(l('account_plan.plan.renews'), '<strong>' . \Altum\Date::get($this->user->plan_expiration_date, 2) . '</strong>', l('pay.custom_plan.' . $this->user->payment_processor), nr($this->user->payment_total_amount), $this->user->payment_currency)
                                                : '<i class="fas fa-fw fa-sm fa-hourglass-end mr-1"></i>' .sprintf(l('account_plan.plan.expires'), '<strong>' . \Altum\Date::get($this->user->plan_expiration_date, 2) . '</strong>'))
                                        : '<i class="fas fa-fw fa-sm fa-infinity mr-1"></i>' . l('account_plan.plan.lifetime')
                                ?>
                            </p>
                        <?php endif ?>

                        <?php if(settings()->payment->is_enabled): ?>
                            <?php if($this->user->plan_id == 'free'): ?>
                                <a href="<?= url('plan/upgrade') ?>" class="w-full py-3.5 rounded-xl bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-100 font-bold text-sm transition-all cursor-pointer mt-4 block text-center"><i class="fas fa-fw fa-sm fa-arrow-up"></i> <?= l('account.plan.upgrade_plan') ?></a>
                            <?php else: ?>
                                <a href="<?= url('plan/renew') ?>" class="w-full py-3.5 rounded-xl bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-100 font-bold text-sm transition-all cursor-pointer mt-4 block text-center"><i class="fas fa-fw fa-sm fa-sync-alt"></i> <?= l('account.plan.renew_plan') ?></a>
                            <?php endif ?>
                        <?php endif ?>


                        <?= (new \Altum\View('partials/plan_features'))->run(['plan_settings' => $this->user->plan_settings]) ?>

                    </div>
                </div>
            </div>

            <?php if(settings()->payment->is_enabled && $data->suggested_plan): ?>
                <div class="space-y-4">
                    <div class="bg-white rounded-3xl border-2 border-blue-600 p-6 sm:p-8 shadow-md" style="border-width: 2px;">
                        <div class="space-y-4">

                            <h2 class="h5">
                                <?php if($data->suggested_plan_code): ?>
                                    <?= sprintf(l('account_plan.upgrade.header_discount'), $data->suggested_plan_code->discount . '%') ?>
                                <?php else: ?>
                                    <?= l('account_plan.upgrade.header') ?>
                                <?php endif ?>
                            </h2>
                            <p class="text-slate-500"><?= sprintf(l('account_plan.upgrade.subheader'), '<strong class="text-primary">' . $data->suggested_plan->name . '</strong>') ?></p>

                            <?php if($data->suggested_plan_code): ?>
                                <a href="<?= url('pay/' . $data->suggested_plan->plan_id . '?code=' . $data->suggested_plan_code->code) ?>" class="btn btn-block btn-primary rounded-2x my-4">
                                    <?= sprintf(l('account_plan.upgrade.discount_button'), $data->suggested_plan_code->discount . '%') ?> <i class="fas fa-fw fa-sm fa-arrow-right"></i>
                                </a>
                            <?php else: ?>
                                <a href="<?= url('pay/' . $data->suggested_plan->plan_id) ?>" class="btn btn-block btn-primary rounded-2x my-4">
                                    <?= l('plans.choose') ?> <i class="fas fa-fw fa-sm fa-arrow-right"></i>
                                </a>
                            <?php endif ?>

                            <?= (new \Altum\View('partials/plan_features'))->run(['plan_settings' => $data->suggested_plan->settings]) ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>
        </div>

    </div>

    <?php if($this->user->plan_id != 'free' && $this->user->payment_subscription_id): ?>
        <div class="card mt-5">
            <div class="space-y-4">
                <h2 class="h5"><?= l('account_plan.cancel.header') ?></h2>
                <p class="text-slate-500"><?= l('account_plan.cancel.subheader') ?></p>

                <a href="<?= url('account-plan/cancel_subscription' . \Altum\Csrf::get_url_query()) ?>" class="w-full py-3.5 rounded-xl bg-slate-50 text-slate-700 border border-slate-200 hover:bg-slate-100 font-bold text-sm transition-all cursor-pointer mt-4 block text-center" onclick='return confirm(<?= json_encode(l('account_plan.cancel.confirm_message')) ?>)'><?= l('account_plan.cancel.cancel') ?></a>
            </div>
        </div>
    <?php endif ?>
</div>

