<?php defined('ALTUMCODE') || die() ?>

<div class="mb-6 font-sans">
    <div class="lg:hidden">
        <select name="account_header_menu" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-slate-900 text-xs font-bold focus:outline-none focus:border-blue-600 shadow-xs">
            <option value="<?= url('account') ?>" <?= \Altum\Router::$controller_key == 'account' ? 'selected="selected"' : null ?>><?= l('account.menu') ?></option>
            <option value="<?= url('account-preferences') ?>" <?= \Altum\Router::$controller_key == 'account-preferences' ? 'selected="selected"' : null ?>><?= l('account_preferences.menu') ?></option>
            <option value="<?= url('account-plan') ?>" <?= \Altum\Router::$controller_key == 'account-plan' ? 'selected="selected"' : null ?>><?= l('account_plan.menu') ?></option>

            <?php if(settings()->payment->is_enabled): ?>
                <option value="<?= url('account-payments') ?>" <?= \Altum\Router::$controller_key == 'account-payments' ? 'selected="selected"' : null ?>><?= l('account_payments.menu') ?></option>
                <?php if(\Altum\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled): ?>
                    <option value="<?= url('referrals') ?>" <?= \Altum\Router::$controller_key == 'referrals' ? 'selected="selected"' : null ?>><?= l('referrals.menu') ?></option>
                <?php endif ?>
            <?php endif ?>

            <option value="<?= url('account-logs') ?>" <?= \Altum\Router::$controller_key == 'account-logs' ? 'selected="selected"' : null ?>><?= l('account_logs.menu') ?></option>
            <option value="<?= url('account-delete') ?>" <?= \Altum\Router::$controller_key == 'account-delete' ? 'selected="selected"' : null ?>><?= l('account_delete.menu') ?></option>
        </select>
    </div>

    <div class="hidden lg:flex items-center gap-1.5 p-1 bg-slate-100/90 border border-slate-200/80 rounded-xl shadow-xs overflow-x-auto">
        <a href="<?= url('account') ?>" class="px-3.5 py-2 rounded-lg font-bold text-xs transition-all text-decoration-none inline-flex items-center gap-2 shrink-0 <?= \Altum\Router::$controller_key == 'account' ? 'bg-white text-slate-900 shadow-xs border border-slate-200/60' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60' ?>">
            <i class="fas fa-user-cog text-xs <?= \Altum\Router::$controller_key == 'account' ? 'text-blue-600' : 'text-slate-400' ?>"></i> <?= l('account.menu') ?>
        </a>

        <a href="<?= url('account-preferences') ?>" class="px-3.5 py-2 rounded-lg font-bold text-xs transition-all text-decoration-none inline-flex items-center gap-2 shrink-0 <?= \Altum\Router::$controller_key == 'account-preferences' ? 'bg-white text-slate-900 shadow-xs border border-slate-200/60' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60' ?>">
            <i class="fas fa-sliders-h text-xs <?= \Altum\Router::$controller_key == 'account-preferences' ? 'text-blue-600' : 'text-slate-400' ?>"></i> <?= l('account_preferences.menu') ?>
        </a>

        <a href="<?= url('account-plan') ?>" class="px-3.5 py-2 rounded-lg font-bold text-xs transition-all text-decoration-none inline-flex items-center gap-2 shrink-0 <?= \Altum\Router::$controller_key == 'account-plan' ? 'bg-white text-slate-900 shadow-xs border border-slate-200/60' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60' ?>">
            <i class="fas fa-box-open text-xs <?= \Altum\Router::$controller_key == 'account-plan' ? 'text-blue-600' : 'text-slate-400' ?>"></i> <?= l('account_plan.menu') ?>
        </a>

        <?php if(settings()->payment->is_enabled): ?>
            <a href="<?= url('account-payments') ?>" class="px-3.5 py-2 rounded-lg font-bold text-xs transition-all text-decoration-none inline-flex items-center gap-2 shrink-0 <?= \Altum\Router::$controller_key == 'account-payments' ? 'bg-white text-slate-900 shadow-xs border border-slate-200/60' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60' ?>">
                <i class="fas fa-credit-card text-xs <?= \Altum\Router::$controller_key == 'account-payments' ? 'text-blue-600' : 'text-slate-400' ?>"></i> <?= l('account_payments.menu') ?>
            </a>
            <?php if(\Altum\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled): ?>
                <a href="<?= url('referrals') ?>" class="px-3.5 py-2 rounded-lg font-bold text-xs transition-all text-decoration-none inline-flex items-center gap-2 shrink-0 <?= \Altum\Router::$controller_key == 'referrals' ? 'bg-white text-slate-900 shadow-xs border border-slate-200/60' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60' ?>">
                    <i class="fas fa-wallet text-xs <?= \Altum\Router::$controller_key == 'referrals' ? 'text-blue-600' : 'text-slate-400' ?>"></i> <?= l('referrals.menu') ?>
                </a>
            <?php endif ?>
        <?php endif ?>

        <a href="<?= url('account-logs') ?>" class="px-3.5 py-2 rounded-lg font-bold text-xs transition-all text-decoration-none inline-flex items-center gap-2 shrink-0 <?= \Altum\Router::$controller_key == 'account-logs' ? 'bg-white text-slate-900 shadow-xs border border-slate-200/60' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60' ?>">
            <i class="fas fa-scroll text-xs <?= \Altum\Router::$controller_key == 'account-logs' ? 'text-blue-600' : 'text-slate-400' ?>"></i> <?= l('account_logs.menu') ?>
        </a>

        <a href="<?= url('account-delete') ?>" class="px-3.5 py-2 rounded-lg font-bold text-xs transition-all text-decoration-none inline-flex items-center gap-2 shrink-0 ml-auto <?= \Altum\Router::$controller_key == 'account-delete' ? 'bg-rose-600 text-white shadow-xs' : 'text-rose-600 hover:bg-rose-50' ?>">
            <i class="fas fa-trash-alt text-xs"></i> <?= l('account_delete.menu') ?>
        </a>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';
    document.querySelector('select[name="account_header_menu"]')?.addEventListener('change', event => {
        window.location = event.currentTarget.value;
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
