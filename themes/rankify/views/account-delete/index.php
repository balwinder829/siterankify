<?php defined('ALTUMCODE') || die() ?>

<div class="max-w-xl mx-auto space-y-6 font-sans">
    <?= \Altum\Alerts::output_alerts() ?>

    <?= $this->views['account_header_menu'] ?>

    <div class="bg-rose-50 border border-rose-200 rounded-3xl p-6 space-y-4">
        <div class="flex items-center gap-3 text-rose-700">
            <div class="h-10 w-10 rounded-2xl bg-rose-100 flex items-center justify-center font-bold text-lg">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div>
                <h1 class="text-lg font-extrabold m-0 text-rose-900"><?= l('account_delete.header') ?></h1>
                <p class="text-xs font-medium text-rose-700 m-0"><?= l('account_delete.subheader') ?></p>
            </div>
        </div>

        <form action="" method="post" role="form" class="space-y-4">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="space-y-1" data-password-toggle-view data-password-toggle-view-show="<?= l('global.show') ?>" data-password-toggle-view-hide="<?= l('global.hide') ?>">
                <label for="current_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><?= l('account_delete.current_password') ?></label>
                <input type="password" id="current_password" name="current_password" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-rose-600 focus:ring-4 focus:ring-rose-500/15 transition-all font-medium <?= \Altum\Alerts::has_field_errors('current_password') ? 'border-rose-500' : null ?>" required="required" />
                <?= \Altum\Alerts::output_field_error('current_password') ?>
            </div>

            <button type="submit" name="submit" class="w-full py-3.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm shadow-md shadow-rose-500/20 transition-all cursor-pointer">
                <?= l('global.delete') ?>
            </button>
        </form>
    </div>
</div>
