<?php defined('ALTUMCODE') || die() ?>

<div class="max-w-4xl mx-auto space-y-6 font-sans">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php $url = parse_url(SITE_URL); $host = $url['host'] . (mb_strlen($url['path']) > 1 ? $url['path'] : null); ?>

    <!-- Top Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="<?= url('domains') ?>" class="h-10 w-10 rounded-2xl bg-white border border-slate-200 text-slate-600 flex items-center justify-center hover:bg-slate-50 transition-all text-decoration-none">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>
            <div>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight m-0"><?= l('domain_update.header') ?></h1>
                <p class="text-xs text-slate-500 font-medium m-0">Update custom domain details and redirect URLs</p>
            </div>
        </div>

        <div>
            <?= include_view(THEME_PATH . 'views/domains/domain_dropdown_button.php', ['id' => $data->domain->domain_id]) ?>
        </div>
    </div>

    <!-- DNS Setup Guidance Box -->
    <div class="p-5 rounded-3xl bg-blue-50/70 border border-blue-100 text-blue-900 text-xs font-medium space-y-1">
        <div class="font-bold flex items-center gap-2 text-blue-700">
            <i class="fas fa-info-circle"></i>
            <span>DNS Configuration</span>
        </div>
        <div><?= sprintf(l('domains.help'), '<strong>' . (settings()->audits->domains_custom_main_ip ?: $_SERVER['SERVER_ADDR']) . '</strong>', '<strong>' . $host . '</strong>') ?></div>
    </div>

    <!-- Domain Form Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-6">
        <form action="" method="post" class="space-y-5" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="space-y-1">
                <label for="host" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><?= l('domains.host') ?></label>
                <input type="text" id="host" name="host" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium <?= \Altum\Alerts::has_field_errors('host') ? 'border-rose-500' : null ?>" value="<?= $data->domain->host ?>" placeholder="<?= l('global.host_placeholder') ?>" required="required" />
                <?= \Altum\Alerts::output_field_error('host') ?>
            </div>

            <div class="space-y-1">
                <label for="custom_index_url" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><?= l('domains.custom_index_url') ?></label>
                <input type="url" id="custom_index_url" name="custom_index_url" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium <?= \Altum\Alerts::has_field_errors('custom_index_url') ? 'border-rose-500' : null ?>" value="<?= $data->domain->custom_index_url ?>" placeholder="<?= l('global.url_placeholder') ?>" />
                <?= \Altum\Alerts::output_field_error('custom_index_url') ?>
                <span class="text-xs text-slate-500 mt-1 block font-medium"><?= l('domains.custom_index_url_help') ?></span>
            </div>

            <div class="space-y-1">
                <label for="custom_not_found_url" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><?= l('domains.custom_not_found_url') ?></label>
                <input type="url" id="custom_not_found_url" name="custom_not_found_url" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium <?= \Altum\Alerts::has_field_errors('custom_not_found_url') ? 'border-rose-500' : null ?>" value="<?= $data->domain->custom_not_found_url ?>" placeholder="<?= l('global.url_placeholder') ?>" />
                <?= \Altum\Alerts::output_field_error('custom_not_found_url') ?>
                <span class="text-xs text-slate-500 mt-1 block font-medium"><?= l('domains.custom_not_found_url_help') ?></span>
            </div>

            <button type="submit" name="submit" class="w-full py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-500/20 transition-all cursor-pointer mt-4">
                <?= l('global.update') ?>
            </button>
        </form>
    </div>
</div>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/domains/domain_delete_modal.php'), 'modals'); ?>
