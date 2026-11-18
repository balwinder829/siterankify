<?php defined('ALTUMCODE') || die();
$plans = (new \Altum\Models\Plan())->get_plans();
$premier_plan = $plans[1] ?? null;
$monthly_plan = $plans[2] ?? null;
$yearly_plan = $plans[3] ?? null;
?>

<?php
$plan_id = $data->plan_id ?? null;

if($plan_id == 'free'): ?>
    <ul class="space-y-2.5 p-0 m-0 list-none text-xs font-medium text-slate-700">
        <li class="flex items-start gap-3">
            <i class="fas fa-check text-emerald-500 text-xs shrink-0 mt-0.5"></i>
            <span>Full Technical SEO Report</span>
        </li>
        <li class="flex items-start gap-3">
            <i class="fas fa-check text-emerald-500 text-xs shrink-0 mt-0.5"></i>
            <span><?= settings()->plan_free->settings->pdf_downloads_limit ?? 1 ?> PDF Download</span>
        </li>
        <li class="flex items-start gap-3">
            <i class="fas fa-check text-emerald-500 text-xs shrink-0 mt-0.5"></i>
            <span><?= settings()->plan_free->settings->pdf_downloads_limit ?? 1 ?> White-Label Report</span>
        </li>
    </ul>
<?php elseif($plan_id == '1'): ?>
    <ul class="space-y-2.5 p-0 m-0 list-none text-xs font-medium text-slate-700">
        <li class="flex items-start gap-3">
            <i class="fas fa-check text-emerald-500 text-xs shrink-0 mt-0.5"></i>
            <span>Full Technical SEO Report</span>
        </li>
        <li class="flex items-start gap-3">
            <i class="fas fa-check text-emerald-500 text-xs shrink-0 mt-0.5"></i>
            <span><?= $premier_plan ? ($premier_plan->settings->pdf_downloads_limit ?? 1) : 1 ?> PDF Download</span>
        </li>
        <li class="flex items-start gap-3">
            <i class="fas fa-check text-emerald-500 text-xs shrink-0 mt-0.5"></i>
            <span><?= $premier_plan ? ($premier_plan->settings->pdf_downloads_limit ?? 1) : 1 ?> White-Label Report</span>
        </li>
        <li class="flex items-start gap-3">
            <i class="fas fa-check text-emerald-500 text-xs shrink-0 mt-0.5"></i>
            <span><?= $premier_plan ? (($premier_plan->settings->audits_per_month_limit == -1) ? 'Unlimited' : ($premier_plan->settings->audits_per_month_limit ?? 50)) : 50 ?> Website Audits / Month</span>
        </li>
        <li class="flex items-start gap-3 pt-2 border-t border-slate-100<?= (isset($_GET['logo_addon']) && $_GET['logo_addon'] == 1) ? '' : ' text-slate-400' ?>" id="wl_addon_feature_row" style="<?= (isset($_GET['logo_addon']) && $_GET['logo_addon'] == 1) ? '' : 'display:none' ?>">
            <i class="fas <?= (isset($_GET['logo_addon']) && $_GET['logo_addon'] == 1) ? 'fa-check text-emerald-500' : 'fa-times text-slate-300' ?> text-xs shrink-0 mt-0.5" id="wl_addon_feature_icon"></i>
            <span class="<?= (isset($_GET['logo_addon']) && $_GET['logo_addon'] == 1) ? 'text-slate-900 font-bold' : '' ?>" id="wl_addon_feature_text"><?= currency() === 'INR' ? '₹170' : '$2' ?> Extra PDF Download +</span>
        </li>
    </ul>
<?php elseif($plan_id == '2'): ?>
    <ul class="space-y-2.5 p-0 m-0 list-none text-xs font-medium text-slate-700">
        <li class="flex items-start gap-3">
            <i class="fas fa-check text-emerald-500 text-xs shrink-0 mt-0.5"></i>
            <span>Full Technical SEO Report</span>
        </li>
        <li class="flex items-start gap-3">
            <i class="fas fa-check text-emerald-500 text-xs shrink-0 mt-0.5"></i>
            <span><?= $monthly_plan ? ($monthly_plan->settings->pdf_downloads_limit ?? 10) : 10 ?> PDF Downloads</span>
        </li>
        <li class="flex items-start gap-3">
            <i class="fas fa-check text-emerald-500 text-xs shrink-0 mt-0.5"></i>
            <span><?= $monthly_plan ? ($monthly_plan->settings->pdf_downloads_limit ?? 10) : 10 ?> White-Label Reports</span>
        </li>
        <li class="flex items-start gap-3">
            <i class="fas fa-check text-emerald-500 text-xs shrink-0 mt-0.5"></i>
            <span><?= $monthly_plan ? (($monthly_plan->settings->audits_per_month_limit == -1) ? 'Unlimited' : ($monthly_plan->settings->audits_per_month_limit ?? 100)) : 100 ?> Website Audits / Month</span>
        </li>
        <li class="flex items-start gap-2 pt-2 border-t border-slate-100 text-[11px] text-slate-500 italic">
            <i class="fas fa-info-circle text-slate-400 text-xs shrink-0 mt-0.5"></i>
            <span>Reverts to lifetime tier after subscription completes</span>
        </li>
    </ul>
<?php elseif($plan_id == '3'): ?>
    <ul class="space-y-2.5 p-0 m-0 list-none text-xs font-medium text-slate-700">
        <li class="flex items-start gap-3">
            <i class="fas fa-check text-emerald-500 text-xs shrink-0 mt-0.5"></i>
            <span>Full Technical SEO Report</span>
        </li>
        <li class="flex items-start gap-3">
            <i class="fas fa-check text-emerald-500 text-xs shrink-0 mt-0.5"></i>
            <span><?= $yearly_plan ? ($yearly_plan->settings->pdf_downloads_limit ?? 150) : 150 ?> PDF Downloads</span>
        </li>
        <li class="flex items-start gap-3">
            <i class="fas fa-check text-emerald-500 text-xs shrink-0 mt-0.5"></i>
            <span><?= $yearly_plan ? ($yearly_plan->settings->pdf_downloads_limit ?? 150) : 150 ?> White-Label Reports</span>
        </li>
        <li class="flex items-start gap-3">
            <i class="fas fa-check text-emerald-500 text-xs shrink-0 mt-0.5"></i>
            <span><?= $yearly_plan ? (($yearly_plan->settings->audits_per_month_limit == -1) ? 'Unlimited' : ($yearly_plan->settings->audits_per_month_limit ?? 120)) : 120 ?> Website Audits / Month</span>
        </li>
        <li class="flex items-start gap-2 pt-2 border-t border-slate-100 text-[11px] text-slate-500 italic">
            <i class="fas fa-info-circle text-slate-400 text-xs shrink-0 mt-0.5"></i>
            <span>Reverts to lifetime tier after subscription completes</span>
        </li>
    </ul>
<?php else: ?>
    <?php $features = ((array) (settings()->payment->plan_features ?? [])) + array_fill_keys(require APP_PATH . 'includes/available_plan_features.php', true) ?>

    <ul class="list-style-none m-0">
        <?php foreach($features as $feature => $is_enabled): ?>

            <?php if($is_enabled && $feature == 'audits_per_month_limit'): ?>
                <li class="d-flex align-items-baseline mb-2">
                    <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->audits_per_month_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
                    <div class="<?= $data->plan_settings->audits_per_month_limit ? null : 'text-muted' ?>">
                        <?= sprintf(l('global.plan_settings.audits_per_month_limit'), '<strong>' . ($data->plan_settings->audits_per_month_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->audits_per_month_limit)) . '</strong>') ?>
                    </div>
                </li>
            <?php endif ?>

            <?php if($is_enabled && $feature == 'websites_limit'): ?>
                <li class="d-flex align-items-baseline mb-2">
                    <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->websites_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
                    <div class="<?= $data->plan_settings->websites_limit ? null : 'text-muted' ?>">
                        <?= sprintf(l('global.plan_settings.websites_limit'), '<strong>' . ($data->plan_settings->websites_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->websites_limit)) . '</strong>') ?>
                    </div>
                </li>
            <?php endif ?>

            <?php if($is_enabled && $feature == 'audits_enabled_tests'): ?>
                <?php $audits_enabled_tests = count(array_filter((array) $data->plan_settings->audits_enabled_tests)) ?>
                <li class="d-flex align-items-baseline mb-2">
                    <i class="fas fa-fw fa-sm mr-3 <?= $audits_enabled_tests ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
                    <div class="<?= $audits_enabled_tests ? null : 'text-muted' ?>">
                        <?= sprintf(l('global.plan_settings.audits_enabled_tests'), '<strong>' . ($audits_enabled_tests == -1 ? l('global.unlimited') : nr($audits_enabled_tests)) . '</strong>') ?>
                    </div>
                </li>
            <?php endif ?>

            <?php if($is_enabled && $feature == 'audits_bulk_limit'): ?>
                <li class="d-flex align-items-baseline mb-2">
                    <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->audits_bulk_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
                    <div class="<?= $data->plan_settings->audits_bulk_limit ? null : 'text-muted' ?>">
                        <?= sprintf(l('global.plan_settings.audits_bulk_limit'), '<strong>' . ($data->plan_settings->audits_bulk_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->audits_bulk_limit)) . '</strong>') ?>
                    </div>
                </li>
            <?php endif ?>

            <?php if($is_enabled && $feature == 'audits_retention'): ?>
                <li class="d-flex align-items-baseline mb-2">
                    <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->audits_retention ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
                    <div class="<?= $data->plan_settings->audits_retention ? null : 'text-muted' ?>" data-toggle="tooltip" title="<?= ($data->plan_settings->audits_retention == -1 ? '' : $data->plan_settings->audits_retention . ' ' . l('global.date.days')) ?>">
                        <?= sprintf(l('global.plan_settings.audits_retention'), '<strong>' . ($data->plan_settings->audits_retention == -1 ? l('global.unlimited') : \Altum\Date::days_format($data->plan_settings->audits_retention)) . '</strong>') ?>
                    </div>
                </li>
            <?php endif ?>

            <?php if($is_enabled && $feature == 'audits_check_intervals'): ?>
                <?php ob_start() ?>
                <div class='d-flex flex-column'>
                    <?php if(!empty($data->plan_settings->audits_check_intervals)): ?>
                        <?php foreach(require APP_PATH . 'includes/audits_check_intervals.php' as $key => $value): ?>
                            <?php if(in_array($key, $data->plan_settings->audits_check_intervals)): ?>
                                <span class='my-1'><?= $value ?></span>
                            <?php endif ?>
                        <?php endforeach ?>
                    <?php else: ?>
                        <?= l('global.none') ?>
                    <?php endif ?>
                </div>
                <?php $html = ob_get_clean() ?>

                <li class="d-flex align-items-baseline mb-2">
                    <i class="fas fa-fw fa-sm mr-3 <?= count((array) ($data->plan_settings->audits_check_intervals ?? [])) ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
                    <div class="<?= count((array) ($data->plan_settings->audits_check_intervals ?? [])) ? null : 'text-muted' ?>">
                        <?= l('global.plan_settings.audits_check_intervals') ?>
                        <span class="ml-1" data-toggle="tooltip" data-html="true" title="<?= $html ?>"><i class="fas fa-fw fa-xs fa-circle-question text-gray-500"></i></span>
                    </div>
                </li>
            <?php endif ?>

            <?php if($is_enabled && $feature == 'notification_handlers_limit'): ?>
                <?php ob_start() ?>
                <?php $notification_handlers_icon = 'fa-times text-muted'; ?>
                <div class='d-flex flex-column'>
                    <?php foreach(array_keys(require APP_PATH . 'includes/notification_handlers.php') as $notification_handler): ?>
                        <?php if($data->plan_settings->{'notification_handlers_' . $notification_handler . '_limit'} != 0) $notification_handlers_icon = 'fa-check text-success' ?>
                        <span class='my-1'><?= sprintf(l('global.plan_settings.notification_handlers_' . $notification_handler . '_limit'), '<strong>' . ($data->plan_settings->{'notification_handlers_' . $notification_handler . '_limit'} == -1 ? l('global.unlimited') : nr($data->plan_settings->{'notification_handlers_' . $notification_handler . '_limit'})) . '</strong>') ?></span>
                    <?php endforeach ?>
                </div>
                <?php $html = ob_get_clean() ?>

                <li class="d-flex align-items-baseline mb-2">
                    <i class="fas fa-fw fa-sm mr-3 <?= $notification_handlers_icon ?>"></i>
                    <div>
                        <?= l('global.plan_settings.notification_handlers_limit') ?>
                        <span class="ml-1" data-toggle="tooltip" data-html="true" title="<?= $html ?>"><i class="fas fa-fw fa-xs fa-circle-question text-gray-500"></i></span>
                    </div>
                </li>
            <?php endif ?>

            <?php if($is_enabled && $feature == 'domains_limit' && settings()->audits->domains_is_enabled): ?>
                <li class="d-flex align-items-baseline mb-2">
                    <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->domains_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
                    <div class="<?= $data->plan_settings->domains_limit ? null : 'text-muted' ?>">
                        <?= sprintf(l('global.plan_settings.domains_limit'), '<strong>' . ($data->plan_settings->domains_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->domains_limit)) . '</strong>') ?>
                    </div>
                </li>
            <?php endif ?>

            <?php if($is_enabled && $feature == 'teams_limit' && \Altum\Plugin::is_active('teams')): ?>
                <li class="d-flex align-items-baseline mb-2">
                    <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->teams_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
                    <div class="<?= $data->plan_settings->teams_limit ? null : 'text-muted' ?>">
                        <?= sprintf(l('global.plan_settings.teams_limit'), '<strong>' . ($data->plan_settings->teams_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->teams_limit)) . '</strong>') ?>
                        <span class="ml-1" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.plan_settings.team_members_limit'), '<strong>' . ($data->plan_settings->team_members_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->team_members_limit)) . '</strong>') ?>"><i class="fas fa-fw fa-xs fa-circle-question text-gray-500"></i></span>
                    </div>
                </li>
            <?php endif ?>

            <?php if($is_enabled && $feature == 'affiliate_commission_percentage' && \Altum\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled): ?>
                <li class="d-flex align-items-baseline mb-2">
                    <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->affiliate_commission_percentage ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
                    <div class="<?= $data->plan_settings->affiliate_commission_percentage ? null : 'text-muted' ?>">
                        <?= sprintf(l('global.plan_settings.affiliate_commission_percentage'), '<strong>' . nr($data->plan_settings->affiliate_commission_percentage) . '%</strong>') ?>
                    </div>
                </li>
            <?php endif ?>

            <?php if($is_enabled && $feature == 'password_protection_is_enabled'): ?>
                <li class="d-flex align-items-baseline mb-2">
                    <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->password_protection_is_enabled ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
                    <div class="<?= $data->plan_settings->password_protection_is_enabled ? null : 'text-muted' ?>">
                        <?= l('global.plan_settings.password_protection_is_enabled') ?>
                        <span class="ml-1" data-toggle="tooltip" title="<?= l('global.plan_settings.password_protection_is_enabled_help') ?>"><i class="fas fa-fw fa-xs fa-circle-question text-gray-500"></i></span>
                    </div>
                </li>
            <?php endif ?>

            <?php if($is_enabled && $feature == 'api_is_enabled' && settings()->main->api_is_enabled): ?>
                <li class="d-flex align-items-baseline mb-2">
                    <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->api_is_enabled ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
                    <div class="<?= $data->plan_settings->api_is_enabled ? null : 'text-muted' ?>">
                        <?= l('global.plan_settings.api_is_enabled') ?>
                        <span class="ml-1" data-toggle="tooltip" title="<?= l('global.plan_settings.api_is_enabled_help') ?>"><i class="fas fa-fw fa-xs fa-circle-question text-gray-500"></i></span>
                    </div>
                </li>
            <?php endif ?>

            <?php if($is_enabled && $feature == 'white_labeling_is_enabled' && settings()->main->white_labeling_is_enabled): ?>
                <li class="d-flex align-items-baseline mb-2">
                    <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->white_labeling_is_enabled ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
                    <div class="<?= $data->plan_settings->white_labeling_is_enabled ? null : 'text-muted' ?>">
                        <?= l('global.plan_settings.white_labeling_is_enabled') ?>
                        <span class="ml-1" data-toggle="tooltip" title="<?= l('global.plan_settings.white_labeling_is_enabled_help') ?>"><i class="fas fa-fw fa-xs fa-circle-question text-gray-500"></i></span>
                    </div>
                </li>
            <?php endif ?>

            <?php if($is_enabled && $feature == sprintf(l('global.plan_settings.export'), '')): ?>
                <?php $enabled_exports_count = count(array_filter((array) $data->plan_settings->export)) ?>

                <?php ob_start() ?>
                <div class='d-flex flex-column'>
                    <?php foreach(['csv', 'json', 'pdf'] as $key): ?>
                        <?php if($data->plan_settings->export->{$key}): ?>
                            <span class='my-1'><?= sprintf(l('global.export_to'), mb_strtoupper($key)) ?></span>
                        <?php else: ?>
                            <s class='my-1'><?= sprintf(l('global.export_to'), mb_strtoupper($key)) ?></s>
                        <?php endif ?>
                    <?php endforeach ?>
                </div>
                <?php $html = ob_get_clean() ?>

                <li class="d-flex align-items-baseline mb-2">
                    <i class="fas fa-fw fa-sm mr-3 <?= $enabled_exports_count ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
                    <div class="<?= $enabled_exports_count ? null : 'text-muted' ?>">
                        <?= sprintf(l('global.plan_settings.export'), $enabled_exports_count) ?>
                        <span class="mr-1" data-html="true" data-toggle="tooltip" title="<?= $html ?>"><i class="fas fa-fw fa-xs fa-circle-question text-gray-500"></i></span>
                    </div>
                </li>
            <?php endif ?>

            <?php if($is_enabled && $feature == 'ai_is_enabled' && settings()->audits->ai_is_enabled): ?>
                <li class="d-flex align-items-baseline mb-2">
                    <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->ai_is_enabled ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
                    <div class="<?= $data->plan_settings->ai_is_enabled ? null : 'text-muted' ?>">
                        <?= l('global.plan_settings.ai_is_enabled') ?>
                        <span class="ml-1" data-toggle="tooltip" title="<?= l('global.plan_settings.ai_is_enabled_help') ?>"><i class="fas fa-fw fa-xs fa-circle-question text-gray-500"></i></span>
                    </div>
                </li>
            <?php endif ?>

            <?php if($is_enabled && $feature == 'no_ads'): ?>
                <li class="d-flex align-items-baseline mb-2">
                    <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->no_ads ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
                    <div class="<?= $data->plan_settings->no_ads ? null : 'text-muted' ?>">
                        <?= l('global.plan_settings.no_ads') ?>
                        <span class="ml-1" data-toggle="tooltip" title="<?= l('global.plan_settings.no_ads_help') ?>"><i class="fas fa-fw fa-xs fa-circle-question text-gray-500"></i></span>
                    </div>
                </li>
            <?php endif ?>

            <?php if($is_enabled && $feature == 'removable_branding_is_enabled'): ?>
                <li class="d-flex align-items-baseline mb-2">
                    <i class="fas fa-fw fa-sm mr-3 <?= $data->plan_settings->removable_branding_is_enabled ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
                    <div class="<?= $data->plan_settings->removable_branding_is_enabled ? null : 'text-muted' ?>">
                        <?= l('global.plan_settings.removable_branding_is_enabled') ?>
                        <span class="ml-1" data-toggle="tooltip" title="<?= l('global.plan_settings.removable_branding_is_enabled_help') ?>"><i class="fas fa-fw fa-xs fa-circle-question text-gray-500"></i></span>
                    </div>
                </li>
            <?php endif ?>

        <?php endforeach ?>
    </ul>
<?php endif ?>
