<?php defined('ALTUMCODE') || die() ?>

<div class="space-y-6 font-sans">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="text-center space-y-2">
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
            <?= l('reset_password.header') ?>
        </h1>
        <p class="text-xs text-slate-500 font-medium leading-relaxed">
            <?= l('reset_password.subheader') ?>
        </p>
    </div>

    <form action="" method="post" class="space-y-4" role="form">
        <div class="space-y-1" data-password-toggle-view data-password-toggle-view-show="<?= l('global.show') ?>" data-password-toggle-view-hide="<?= l('global.hide') ?>">
            <label for="new_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><?= l('reset_password.new_password') ?></label>
            <input id="new_password" type="password" name="new_password" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium <?= \Altum\Alerts::has_field_errors('new_password') ? 'border-rose-500' : null ?>" required="required" autofocus="autofocus" />
            <?= \Altum\Alerts::output_field_error('new_password') ?>
        </div>

        <div class="space-y-1" data-password-toggle-view data-password-toggle-view-show="<?= l('global.show') ?>" data-password-toggle-view-hide="<?= l('global.hide') ?>">
            <label for="repeat_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><?= l('reset_password.repeat_password') ?></label>
            <input id="repeat_password" type="password" name="repeat_password" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium <?= \Altum\Alerts::has_field_errors('repeat_password') ? 'border-rose-500' : null ?>" required="required" />
            <?= \Altum\Alerts::output_field_error('repeat_password') ?>
        </div>

        <button type="submit" name="submit" class="w-full py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-500/20 transition-all cursor-pointer mt-4">
            Reset Password
        </button>
    </form>

    <div class="pt-4 border-t border-slate-100 text-center text-xs text-slate-500 font-medium">
        <a href="login" class="text-blue-600 hover:underline font-bold"><?= l('reset_password.return') ?></a>
    </div>
</div>
