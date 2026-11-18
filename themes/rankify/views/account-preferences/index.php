<?php defined('ALTUMCODE') || die() ?>

<div class="max-w-4xl mx-auto space-y-6 font-sans">
    <?= \Altum\Alerts::output_alerts() ?>

    <?= $this->views['account_header_menu'] ?>

    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight m-0"><?= l('account_preferences.header') ?></h1>
            <p class="text-xs text-slate-500 m-0 mt-0.5"><?= l('account_preferences.subheader') ?></p>
        </div>
    </div>

    <form id="account_preferences" action="" method="post" role="form" enctype="multipart/form-data" class="space-y-6">
        <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

        <?php if(settings()->main->white_labeling_is_enabled): ?>
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider m-0 flex items-center gap-2">
                        <i class="fas fa-cube text-blue-600"></i>
                        <span><?= l('account_preferences.white_labeling') ?></span>
                    </h2>
                    <?php if(!$this->user->plan_settings->white_labeling_is_enabled): ?>
                        <span class="px-2.5 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-[11px] font-bold">Requires Plan Upgrade</span>
                    <?php endif ?>
                </div>

                <div <?= $this->user->plan_settings->white_labeling_is_enabled ? null : get_plan_feature_disabled_info() ?>>
                    <div class="<?= $this->user->plan_settings->white_labeling_is_enabled ? null : 'opacity-60 pointer-events-none' ?> space-y-4">
                        <div class="space-y-1.5">
                            <label for="white_label_title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><?= l('account_preferences.white_label_title') ?></label>
                            <input type="text" id="white_label_title" name="white_label_title" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium <?= \Altum\Alerts::has_field_errors('white_label_title') ? 'border-rose-400' : null ?>" value="<?= $this->user->preferences->white_label_title ?>" maxlength="32" placeholder="Custom Brand Title" />
                            <?= \Altum\Alerts::output_field_error('white_label_title') ?>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                            <div class="space-y-1.5" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= get_max_upload() ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), get_max_upload()) ?>">
                                <label for="white_label_logo_light" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><?= l('account_preferences.white_label_logo_light') ?></label>
                                <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'users', 'file_key' => 'white_label_logo_light', 'already_existing_image' => $this->user->preferences->white_label_logo_light]) ?>
                            </div>

                            <div class="space-y-1.5" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= get_max_upload() ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), get_max_upload()) ?>">
                                <label for="white_label_logo_dark" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><?= l('account_preferences.white_label_logo_dark') ?></label>
                                <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'users', 'file_key' => 'white_label_logo_dark', 'already_existing_image' => $this->user->preferences->white_label_logo_dark]) ?>
                            </div>

                            <div class="space-y-1.5" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= get_max_upload() ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), get_max_upload()) ?>">
                                <label for="white_label_favicon" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><?= l('account_preferences.white_label_favicon') ?></label>
                                <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'users', 'file_key' => 'white_label_favicon', 'already_existing_image' => $this->user->preferences->white_label_favicon]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif ?>

        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-5">
            <div class="pb-3 border-b border-slate-100">
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider m-0 flex items-center gap-2">
                    <i class="fas fa-sliders-h text-blue-600"></i>
                    <span><?= l('account_preferences.default_settings') ?></span>
                </h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label for="default_results_per_page" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><?= l('account_preferences.default_results_per_page') ?></label>
                    <select id="default_results_per_page" name="default_results_per_page" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium">
                        <?php foreach([10, 25, 50, 100, 250, 500, 1000] as $key): ?>
                            <option value="<?= $key ?>" <?= ($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page) == $key ? 'selected="selected"' : null ?>><?= $key ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label for="default_order_type" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><?= l('account_preferences.default_order_type') ?></label>
                    <select id="default_order_type" name="default_order_type" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium">
                        <option value="ASC" <?= ($this->user->preferences->default_order_type ?? settings()->main->default_order_type) == 'ASC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_asc') ?></option>
                        <option value="DESC" <?= ($this->user->preferences->default_order_type ?? settings()->main->default_order_type) == 'DESC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_desc') ?></option>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label for="websites_default_order_by" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><?= sprintf(l('account_preferences.default_order_by_x'), l('websites.title')) ?></label>
                    <select id="websites_default_order_by" name="websites_default_order_by" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium">
                        <option value="website_id" <?= $this->user->preferences->websites_default_order_by == 'website_id' ? 'selected="selected"' : null ?>><?= l('global.id') ?></option>
                        <option value="datetime" <?= $this->user->preferences->websites_default_order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                        <option value="last_datetime" <?= $this->user->preferences->websites_default_order_by == 'last_datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_last_datetime') ?></option>
                        <option value="last_audit_datetime" <?= $this->user->preferences->websites_default_order_by == 'last_audit_datetime' ? 'selected="selected"' : null ?>><?= l('websites.last_audit_datetime') ?></option>
                        <option value="host" <?= $this->user->preferences->websites_default_order_by == 'host' ? 'selected="selected"' : null ?>><?= l('audits.host') ?></option>
                        <option value="score" <?= $this->user->preferences->websites_default_order_by == 'score' ? 'selected="selected"' : null ?>><?= l('audits.score') ?></option>
                        <option value="total_audits" <?= $this->user->preferences->websites_default_order_by == 'total_audits' ? 'selected="selected"' : null ?>><?= l('websites.total_audits') ?></option>
                        <option value="total_archived_audits" <?= $this->user->preferences->websites_default_order_by == 'total_archived_audits' ? 'selected="selected"' : null ?>><?= l('websites.total_archived_audits') ?></option>
                        <option value="total_issues" <?= $this->user->preferences->websites_default_order_by == 'total_issues' ? 'selected="selected"' : null ?>><?= l('audits.total_issues') ?></option>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label for="audits_default_order_by" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><?= sprintf(l('account_preferences.default_order_by_x'), l('audits.title')) ?></label>
                    <select id="audits_default_order_by" name="audits_default_order_by" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium">
                        <option value="audit_id" <?= $this->user->preferences->audits_default_order_by == 'audit_id' ? 'selected="selected"' : null ?>><?= l('global.id') ?></option>
                        <option value="datetime" <?= $this->user->preferences->audits_default_order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                        <option value="last_datetime" <?= $this->user->preferences->audits_default_order_by == 'last_datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_last_datetime') ?></option>
                        <option value="next_refresh_datetime" <?= $this->user->preferences->audits_default_order_by == 'next_refresh_datetime' ? 'selected="selected"' : null ?>><?= l('audits.next_refresh_datetime') ?></option>
                        <option value="last_refresh_datetime" <?= $this->user->preferences->audits_default_order_by == 'last_refresh_datetime' ? 'selected="selected"' : null ?>><?= l('audits.last_refresh_datetime') ?></option>
                        <option value="url" <?= $this->user->preferences->audits_default_order_by == 'url' ? 'selected="selected"' : null ?>><?= l('global.url') ?></option>
                        <option value="host" <?= $this->user->preferences->audits_default_order_by == 'host' ? 'selected="selected"' : null ?>><?= l('audits.host') ?></option>
                        <option value="title" <?= $this->user->preferences->audits_default_order_by == 'title' ? 'selected="selected"' : null ?>><?= l('audits.page_title') ?></option>
                        <option value="score" <?= $this->user->preferences->audits_default_order_by == 'score' ? 'selected="selected"' : null ?>><?= l('audits.score') ?></option>
                        <option value="total_issues" <?= $this->user->preferences->audits_default_order_by == 'total_issues' ? 'selected="selected"' : null ?>><?= l('audits.total_issues') ?></option>
                        <option value="total_refreshes" <?= $this->user->preferences->audits_default_order_by == 'total_refreshes' ? 'selected="selected"' : null ?>><?= l('audits.total_refreshes') ?></option>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label for="archived_audits_default_order_by" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><?= sprintf(l('account_preferences.default_order_by_x'), l('archived_audits.title')) ?></label>
                    <select id="archived_audits_default_order_by" name="archived_audits_default_order_by" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium">
                        <option value="archived_audit_id" <?= $this->user->preferences->archived_audits_default_order_by == 'archived_audit_id' ? 'selected="selected"' : null ?>><?= l('global.id') ?></option>
                        <option value="datetime" <?= $this->user->preferences->archived_audits_default_order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                        <option value="url" <?= $this->user->preferences->archived_audits_default_order_by == 'url' ? 'selected="selected"' : null ?>><?= l('global.url') ?></option>
                        <option value="host" <?= $this->user->preferences->archived_audits_default_order_by == 'host' ? 'selected="selected"' : null ?>><?= l('audits.host') ?></option>
                        <option value="title" <?= $this->user->preferences->archived_audits_default_order_by == 'title' ? 'selected="selected"' : null ?>><?= l('audits.page_title') ?></option>
                        <option value="score" <?= $this->user->preferences->archived_audits_default_order_by == 'score' ? 'selected="selected"' : null ?>><?= l('audits.score') ?></option>
                        <option value="total_issues" <?= $this->user->preferences->archived_audits_default_order_by == 'total_issues' ? 'selected="selected"' : null ?>><?= l('audits.total_issues') ?></option>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label for="notification_handlers_default_order_by" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><?= sprintf(l('account_preferences.default_order_by_x'), l('notification_handlers.title')) ?></label>
                    <select id="notification_handlers_default_order_by" name="notification_handlers_default_order_by" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium">
                        <option value="notification_handler_id" <?= $this->user->preferences->notification_handlers_default_order_by == 'notification_handler_id' ? 'selected="selected"' : null ?>><?= l('global.id') ?></option>
                        <option value="datetime" <?= $this->user->preferences->notification_handlers_default_order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                        <option value="last_datetime" <?= $this->user->preferences->notification_handlers_default_order_by == 'last_datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_last_datetime') ?></option>
                        <option value="name" <?= $this->user->preferences->notification_handlers_default_order_by == 'name' ? 'selected="selected"' : null ?>><?= l('global.name') ?></option>
                    </select>
                </div>

                <?php if(settings()->audits->domains_is_enabled): ?>
                    <div class="space-y-1.5 sm:col-span-2">
                        <label for="domains_default_order_by" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><?= sprintf(l('account_preferences.default_order_by_x'), l('domains.title')) ?></label>
                        <select id="domains_default_order_by" name="domains_default_order_by" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium">
                            <option value="domain_id" <?= $this->user->preferences->domains_default_order_by == 'domain_id' ? 'selected="selected"' : null ?>><?= l('global.id') ?></option>
                            <option value="datetime" <?= $this->user->preferences->domains_default_order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                            <option value="last_datetime" <?= $this->user->preferences->domains_default_order_by == 'last_datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_last_datetime') ?></option>
                            <option value="host" <?= $this->user->preferences->domains_default_order_by == 'host' ? 'selected="selected"' : null ?>><?= l('domains.table.host') ?></option>
                        </select>
                    </div>
                <?php endif ?>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-4">
            <div class="pb-3 border-b border-slate-100">
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider m-0 flex items-center gap-2">
                    <i class="fas fa-table-cells text-blue-600"></i>
                    <span><?= l('account_preferences.dashboard_features') ?></span>
                </h2>
                <p class="text-xs text-slate-500 m-0 mt-0.5">Toggle and drag items to configure widgets on your dashboard.</p>
            </div>

            <div id="dashboard_features" class="space-y-2">
                <?php $dashboard_features = ((array) $this->user->preferences->dashboard) + array_fill_keys(['websites', 'audits'], true) ?>
                <?php $index = 0; ?>
                <?php foreach($dashboard_features as $feature => $is_enabled): ?>
                    <div class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-200 rounded-xl hover:bg-slate-100/80 transition-colors">
                        <span class="text-slate-400 cursor-grab drag">
                            <i class="fas fa-grip-vertical text-xs"></i>
                        </span>

                        <label class="inline-flex items-center gap-2.5 text-xs font-bold text-slate-700 cursor-pointer select-none m-0 flex-1">
                            <input id="<?= 'dashboard_' . $feature ?>" name="dashboard[<?= $index++ ?>]" value="<?= $feature ?>" type="checkbox" class="h-4 w-4 rounded text-blue-600 focus:ring-blue-500 border-slate-300 cursor-pointer" <?= $is_enabled ? 'checked="checked"' : null ?>>
                            <span><?= l('dashboard.' . $feature . '.header') ?></span>
                        </label>
                    </div>
                <?php endforeach ?>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" name="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition-all cursor-pointer border-0 inline-flex items-center gap-2">
                <i class="fas fa-check text-[11px]"></i>
                <span><?= l('global.update') ?></span>
            </button>
        </div>
    </form>
</div>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/sortable.js?v=' . PRODUCT_CODE ?>"></script>
<script>
    'use strict';
    
    if(document.getElementById('dashboard_features')) {
        Sortable.create(document.getElementById('dashboard_features'), {
            animation: 150,
            handle: '.drag',
            onUpdate: event => {
                document.querySelectorAll('#dashboard_features > div').forEach((elm, i) => {
                    let input = elm.querySelector('input[type="checkbox"]');
                    if(input) {
                        input.setAttribute('name', `dashboard[${i}]`);
                    }
                });
            }
        });
    }
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
