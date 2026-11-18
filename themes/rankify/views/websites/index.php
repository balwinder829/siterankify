<?php defined('ALTUMCODE') || die() ?>

<div class="space-y-4 font-sans">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if($this->user->plan_settings->websites_limit != -1 && $data->total_websites > $this->user->plan_settings->websites_limit): ?>
        <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium flex items-center gap-2.5">
            <i class="fas fa-exclamation-circle text-rose-500 shrink-0 text-sm"></i>
            <div class="leading-normal"><?= sprintf(settings()->payment->is_enabled ? l('global.info_message.plan_feature_limit_removal_with_upgrade') : l('global.info_message.plan_feature_limit_removal'), '<strong>' . $data->total_websites - $this->user->plan_settings->websites_limit, mb_strtolower(l('websites.title')) . '</strong>', '<a href="' . url('plan') . '" class="font-bold text-rose-900 underline">' . l('global.info_message.plan_upgrade') . '</a>') ?></div>
        </div>
    <?php endif ?>

    <!-- Header Banner -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/90 shadow-2xs">
        <div class="flex items-center gap-3 min-w-0">
            <div class="h-9 w-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-base border border-blue-100 shrink-0">
                <i class="fas fa-pager"></i>
            </div>
            <div class="min-w-0">
                <div class="flex items-center gap-2">
                    <h1 class="text-lg font-bold text-slate-900 tracking-tight m-0">
                        <?= l('websites.header') ?>
                    </h1>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                        <?= nr($data->total_websites) ?>
                    </span>
                </div>
                <p class="text-xs text-slate-500 font-medium m-0 truncate">Tracked domains &amp; aggregate SEO health</p>
            </div>
        </div>

        <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
            <!-- Export Dropdown -->
            <div class="relative">
                <button type="button" onclick="document.getElementById('websites_export_menu')?.classList.toggle('hidden'); event.stopPropagation();" class="h-9 px-3 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 text-xs font-bold inline-flex items-center gap-1.5 transition-colors cursor-pointer <?= count($data->websites) ? null : 'opacity-50 cursor-not-allowed' ?>">
                    <i class="fas fa-download text-xs text-slate-500"></i>
                    <span class="hidden xs:inline">Export</span>
                </button>

                <div id="websites_export_menu" class="hidden absolute right-0 top-full mt-2 bg-white z-50 shadow-xl border border-slate-200/90 rounded-2xl p-1.5 w-44 space-y-1">
                    <a href="<?= url('websites?' . $data->filters->get_get() . '&export=csv')  ?>" target="_blank" class="rounded-xl py-2 px-3 flex items-center gap-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-blue-600 text-decoration-none transition-colors <?= $this->user->plan_settings->export->csv ? null : 'opacity-50 cursor-not-allowed' ?>">
                        <i class="fas fa-file-csv text-emerald-500"></i> Export CSV
                    </a>
                    <a href="<?= url('websites?' . $data->filters->get_get() . '&export=json') ?>" target="_blank" class="rounded-xl py-2 px-3 flex items-center gap-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-blue-600 text-decoration-none transition-colors <?= $this->user->plan_settings->export->json ? null : 'opacity-50 cursor-not-allowed' ?>">
                        <i class="fas fa-file-code text-indigo-500"></i> Export JSON
                    </a>
                </div>
            </div>

            <!-- New Audit Action -->
            <a href="<?= url('dashboard') ?>" class="px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition-colors text-decoration-none inline-flex items-center gap-1.5 whitespace-nowrap">
                <i class="fas fa-plus-circle text-xs"></i>
                <span>Audit Website</span>
            </a>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200/90 shadow-2xs">
        <form action="" method="get" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
            <div class="relative flex-1 min-w-0">
                <i class="fas fa-search text-slate-400 text-xs absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                <input type="search" name="search" value="<?= $data->filters->search ?>" placeholder="Search domain (e.g. example.com)..." class="w-full h-10 pl-9 pr-3 bg-slate-50 hover:bg-slate-100/70 focus:bg-white rounded-xl text-slate-900 text-xs sm:text-sm font-medium border border-slate-200 focus:border-blue-600 focus:outline-none transition-colors" />
            </div>

            <div class="flex items-center gap-2">
                <select name="order_by" class="h-10 px-3 bg-slate-50 hover:bg-slate-100/70 focus:bg-white rounded-xl text-slate-900 text-xs font-medium border border-slate-200 focus:border-blue-600 focus:outline-none transition-colors cursor-pointer">
                    <option value="score" <?= $data->filters->order_by == 'score' ? 'selected' : '' ?>>Sort: Health Score</option>
                    <option value="total_audits" <?= $data->filters->order_by == 'total_audits' ? 'selected' : '' ?>>Sort: Total Audits</option>
                    <option value="total_issues" <?= $data->filters->order_by == 'total_issues' ? 'selected' : '' ?>>Sort: Total Issues</option>
                    <option value="last_datetime" <?= $data->filters->order_by == 'last_datetime' ? 'selected' : '' ?>>Sort: Last Activity</option>
                    <option value="host" <?= $data->filters->order_by == 'host' ? 'selected' : '' ?>>Sort: Domain Name</option>
                </select>

                <button type="submit" class="h-10 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition-colors cursor-pointer border-0 shrink-0">
                    Filter
                </button>

                <?php if($data->filters->has_applied_filters): ?>
                    <a href="<?= url('websites') ?>" class="h-10 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs inline-flex items-center justify-center transition-colors text-decoration-none shrink-0" title="Reset Filters">
                        <i class="fas fa-undo text-xs"></i>
                    </a>
                <?php endif ?>
            </div>
        </form>
    </div>

    <!-- Content: Responsive Websites List -->
    <?php if (!empty($data->websites)): ?>
        <form id="table" action="<?= SITE_URL . 'websites/bulk' ?>" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />
            <input type="hidden" name="type" value="" data-bulk-type />

            <!-- MOBILE CARDS VIEW (For screens < 768px, specifically 320px to 640px) -->
            <div class="block md:hidden space-y-2.5">
                <?php foreach($data->websites as $row): ?>
                    <div class="bg-white p-3.5 rounded-2xl border border-slate-200/90 shadow-2xs space-y-3">
                        <!-- Top Row: Domain & Action Menu -->
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain($row->host) ?>" class="h-6 w-6 rounded-md bg-slate-50 p-0.5 border border-slate-200 shrink-0" loading="lazy" />
                                <div class="min-w-0">
                                    <a href="<?= url('website/' . $row->website_id) ?>" class="font-bold text-sm text-slate-900 hover:text-blue-600 text-decoration-none block truncate">
                                        <?= $row->host ?>
                                    </a>
                                    <a href="<?= 'https://' . $row->host ?>" target="_blank" rel="noreferrer" class="text-[11px] text-slate-400 hover:text-blue-600 inline-flex items-center gap-1 font-medium text-decoration-none">
                                        <span class="truncate"><?= $row->host ?></span>
                                        <i class="fas fa-external-link-alt text-[8px]"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="shrink-0">
                                <?= include_view(THEME_PATH . 'views/websites/website_dropdown_button.php', ['id' => $row->website_id, 'resource_name' => $row->host]) ?>
                            </div>
                        </div>

                        <!-- Middle Stats Row -->
                        <div class="grid grid-cols-2 gap-2 p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Health</span>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <span class="font-black text-sm text-slate-900"><?= nr($row->score) ?>%</span>
                                    <div class="h-1.5 w-12 bg-slate-200 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full <?= $row->score >= 80 ? 'bg-emerald-500' : ($row->score >= 50 ? 'bg-amber-500' : 'bg-rose-500') ?>" style="width: <?= $row->score ?>%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Issues</span>
                                <span class="sr-badge mt-0.5 <?= $row->major_issues > 0 ? 'sr-badge-danger' : ($row->moderate_issues > 0 ? 'sr-badge-warning' : 'sr-badge-success') ?>">
                                    <?= sprintf(l('audits.total_issues_x'), nr($row->total_issues)) ?>
                                </span>
                            </div>
                        </div>

                        <!-- Bottom Controls -->
                        <div class="flex items-center justify-between gap-2 pt-0.5">
                            <div class="flex items-center gap-1.5 min-w-0">
                                <a href="<?= url('audits?website_id=' . $row->website_id) ?>" class="px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 font-bold text-[10px] inline-flex items-center gap-1 text-decoration-none border border-amber-200">
                                    <i class="fas fa-bolt text-[8px]"></i> <?= nr($row->total_audits) ?>
                                </a>
                                <?php if($row->total_archived_audits > 0): ?>
                                    <a href="<?= url('archived-audits?website_id=' . $row->website_id) ?>" class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 font-semibold text-[10px] inline-flex items-center gap-1 text-decoration-none border border-slate-200">
                                        <i class="fas fa-archive text-[8px]"></i> <?= nr($row->total_archived_audits) ?>
                                    </a>
                                <?php endif ?>
                            </div>

                            <a href="<?= url('website/' . $row->website_id) ?>" class="px-3 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs transition-colors text-decoration-none inline-flex items-center gap-1 shrink-0">
                                <span>Overview</span>
                                <i class="fas fa-chevron-right text-[9px]"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>

            <!-- DESKTOP TABLE VIEW (For screens >= 768px) -->
            <div class="hidden md:block bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
                <div class="overflow-x-auto w-full">
                    <table class="sr-table w-full">
                        <thead>
                            <tr>
                                <th>Domain Host</th>
                                <th>Audits Run</th>
                                <th>SEO Health</th>
                                <th>Total Issues</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data->websites as $row): ?>
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain($row->host) ?>" class="h-6 w-6 rounded-lg bg-slate-50 p-1 border border-slate-200 shrink-0" loading="lazy" />
                                            <div class="min-w-0">
                                                <a href="<?= url('website/' . $row->website_id) ?>" class="font-bold text-slate-900 hover:text-blue-600 text-decoration-none block truncate max-w-xs">
                                                    <?= $row->host ?>
                                                </a>
                                                <a href="<?= 'https://' . $row->host ?>" target="_blank" rel="noreferrer" class="text-xs text-slate-400 hover:text-blue-600 inline-flex items-center gap-1 font-medium text-decoration-none">
                                                    <span>Visit site</span>
                                                    <i class="fas fa-external-link-alt text-[9px]"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-1.5">
                                            <a href="<?= url('audits?website_id=' . $row->website_id) ?>" class="px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 font-bold text-xs inline-flex items-center gap-1 text-decoration-none border border-amber-200 hover:bg-amber-100 transition-colors">
                                                <i class="fas fa-bolt text-[10px]"></i> <?= nr($row->total_audits) ?> Active
                                            </a>
                                            <a href="<?= url('archived-audits?website_id=' . $row->website_id) ?>" class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 font-semibold text-xs inline-flex items-center gap-1 text-decoration-none border border-slate-200 hover:bg-slate-200 transition-colors">
                                                <i class="fas fa-archive text-[10px]"></i> <?= nr($row->total_archived_audits) ?>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <span class="font-extrabold text-xs text-slate-800 w-9"><?= nr($row->score) ?>%</span>
                                            <div class="h-2 w-20 bg-slate-100 rounded-full overflow-hidden">
                                                <div class="h-full rounded-full <?= $row->score >= 80 ? 'bg-emerald-500' : ($row->score >= 50 ? 'bg-amber-500' : 'bg-rose-500') ?>" style="width: <?= $row->score ?>%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="sr-badge <?= $row->major_issues > 0 ? 'sr-badge-danger' : ($row->moderate_issues > 0 ? 'sr-badge-warning' : 'sr-badge-success') ?>">
                                            <?= sprintf(l('audits.total_issues_x'), nr($row->total_issues)) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a href="<?= url('website/' . $row->website_id) ?>" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all text-decoration-none">
                                                Overview
                                            </a>
                                            <?= include_view(THEME_PATH . 'views/websites/website_dropdown_button.php', ['id' => $row->website_id, 'resource_name' => $row->host]) ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>

        <div class="pt-2"><?= $data->pagination ?></div>
    <?php else: ?>
        <div class="bg-white rounded-2xl border border-slate-200/90 p-8 sm:p-12 text-center space-y-3 shadow-2xs">
            <div class="h-12 w-12 rounded-2xl bg-blue-50 text-blue-600 mx-auto flex items-center justify-center text-xl border border-blue-100">
                <i class="fas fa-pager"></i>
            </div>
            <div>
                <h3 class="text-sm sm:text-base font-bold text-slate-800 m-0">No Tracked Websites</h3>
                <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Websites will be automatically registered and tracked here as you perform SEO diagnostics and audits.</p>
            </div>
            <a href="<?= url('dashboard') ?>" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition-colors text-decoration-none shadow-sm shadow-blue-500/20">
                <i class="fas fa-plus"></i>
                <span>Run Your First Audit</span>
            </a>
        </div>
    <?php endif ?>
</div>

<?php require THEME_PATH . 'views/partials/js_bulk.php' ?>
<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/bulk_delete_modal.php'), 'modals'); ?>

<script>
    document.addEventListener('click', () => {
        document.getElementById('websites_export_menu')?.classList.add('hidden');
    });
</script>
