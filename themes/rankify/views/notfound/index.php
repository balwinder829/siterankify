<?php defined('ALTUMCODE') || die(); ?>

<div class="max-w-2xl mx-auto py-3 px-4 font-sans">
    <div class="bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-12 text-center shadow-xl space-y-6">
        <?= \Altum\Alerts::output_alerts() ?>

        <div class="h-20 w-20 mx-auto rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center text-4xl border border-blue-100">
            <i class="fas fa-ghost"></i>
        </div>

        <div class="space-y-2">
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">404 - Page Not Found</h1>
            <p class="text-xs text-slate-500 font-medium leading-relaxed max-w-md mx-auto"><?= l('not_found.subheader') ?></p>
        </div>

        <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-3">
            <?php if(is_logged_in()): ?>
                <a href="<?= url('dashboard') ?>" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition-all text-decoration-none inline-flex items-center justify-center gap-2">
                    <i class="fas fa-th-large"></i> <?= l('dashboard.menu') ?>
                </a>
            <?php endif ?>

            <a href="<?= url() ?>" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all text-decoration-none inline-flex items-center justify-center gap-2">
                <i class="fas fa-arrow-left text-xs"></i> <?= l('not_found.button') ?>
            </a>
        </div>
    </div>
</div>
