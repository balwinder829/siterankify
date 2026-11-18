<?php defined('ALTUMCODE') || die() ?>

<div class="space-y-6 font-sans">
    <?= \Altum\Alerts::output_alerts() ?>

    <!-- Top Header Bar -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg border border-amber-100">
                <i class="fas fa-bolt"></i>
            </div>
            <div>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight m-0 flex items-center gap-2">
                    <span><?= l('audits.header') ?></span>
                    <span title="<?= l('audits.subheader') ?>"><i class="fas fa-info-circle text-xs text-slate-400"></i></span>
                </h1>
                <p class="text-xs text-slate-500 font-medium m-0">Manage, export, and monitor all website audit reports</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5 w-full lg:w-auto justify-end">
            <?php if(($this->user->plan_settings->audits_per_month_limit != -1 && $data->audits_current_month >= $this->user->plan_settings->audits_per_month_limit)): ?>
                <button type="button" class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-400 font-bold text-xs cursor-not-allowed" <?= get_plan_feature_limit_reached_info() ?>>
                    <i class="fas fa-plus-circle mr-1"></i> <?= l('audits.create') ?>
                </button>
            <?php else: ?>
                <a href="<?= url('dashboard') ?>" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition-all text-decoration-none inline-flex items-center gap-2">
                    <i class="fas fa-plus-circle"></i> <?= l('audits.create') ?>
                </a>
            <?php endif ?>

            <!-- Export Dropdown -->
            <div class="relative">
                <button onclick="document.getElementById('export_dropdown').classList.toggle('hidden'); document.getElementById('filters_dropdown').classList.add('hidden'); event.stopPropagation();" type="button" class="h-10 w-10 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition-all focus:outline-none <?= count($data->audits) ? null : 'opacity-50 cursor-not-allowed' ?>" title="<?= l('global.export') ?>">
                    <i class="fas fa-download text-xs"></i>
                </button>

                <div id="export_dropdown" class="hidden absolute right-0 top-full mt-2 bg-white z-[100] shadow-2xl border border-slate-200/90 rounded-2xl p-2 w-48 space-y-1">
                    <a href="<?= url('audits?' . $data->filters->get_get() . '&export=csv')  ?>" target="_blank" class="dropdown-item rounded-xl py-2 px-3 flex items-center gap-2 text-xs font-semibold text-slate-700 hover:bg-slate-100 <?= $this->user->plan_settings->export->csv ? null : 'opacity-50 cursor-not-allowed' ?>">
                        <i class="fas fa-file-csv text-blue-500"></i> Export CSV
                    </a>
                    <a href="<?= url('audits?' . $data->filters->get_get() . '&export=json') ?>" target="_blank" class="dropdown-item rounded-xl py-2 px-3 flex items-center gap-2 text-xs font-semibold text-slate-700 hover:bg-slate-100 <?= $this->user->plan_settings->export->json ? null : 'opacity-50 cursor-not-allowed' ?>">
                        <i class="fas fa-file-code text-indigo-500"></i> Export JSON
                    </a>
                    <a href="#" class="dropdown-item rounded-xl py-2 px-3 flex items-center gap-2 text-xs font-semibold text-slate-700 hover:bg-slate-100 <?= $this->user->plan_settings->export->pdf ? null : 'opacity-50 cursor-not-allowed' ?>" onclick="event.preventDefault(); window.print();">
                        <i class="fas fa-file-pdf text-rose-500"></i> Export PDF
                    </a>
                </div>
            </div>

            <!-- Filters Button -->
            <div class="relative">
                <button onclick="document.getElementById('filters_dropdown').classList.toggle('hidden'); document.getElementById('export_dropdown').classList.add('hidden'); event.stopPropagation();" type="button" class="h-10 w-10 rounded-xl <?= $data->filters->has_applied_filters ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' ?> flex items-center justify-center transition-all focus:outline-none" title="<?= l('global.filters.tooltip') ?>">
                    <i class="fas fa-filter text-xs"></i>
                </button>

                <div id="filters_dropdown" class="hidden absolute right-0 top-full mt-2 bg-white z-[100] shadow-2xl border border-slate-200/90 rounded-2xl p-4 w-72 space-y-3 font-sans">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <span class="text-xs font-bold text-slate-900 uppercase tracking-wider"><?= l('global.filters.header') ?></span>
                        <?php if($data->filters->has_applied_filters): ?>
                            <a href="<?= url(\Altum\Router::$original_request) ?>" class="text-[11px] text-blue-600 hover:underline font-bold"><?= l('global.filters.reset') ?></a>
                        <?php endif ?>
                    </div>

                    <form action="" method="get" class="space-y-3">
                        <div class="space-y-1">
                            <label for="filters_search" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider"><?= l('global.filters.search') ?></label>
                            <input type="search" name="search" id="filters_search" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-blue-600" value="<?= $data->filters->search ?>" />
                        </div>

                        <div class="space-y-1">
                            <label for="filters_search_by" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider"><?= l('global.filters.search_by') ?></label>
                            <select name="search_by" id="filters_search_by" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-blue-600">
                                <option value="url" <?= $data->filters->search_by == 'url' ? 'selected="selected"' : null ?>><?= l('global.url') ?></option>
                                <option value="host" <?= $data->filters->search_by == 'host' ? 'selected="selected"' : null ?>><?= l('audits.host') ?></option>
                                <option value="title" <?= $data->filters->search_by == 'title' ? 'selected="selected"' : null ?>><?= l('audits.page_title') ?></option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label for="filters_order_by" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider"><?= l('global.filters.order_by') ?></label>
                            <select name="order_by" id="filters_order_by" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-blue-600">
                                <option value="audit_id" <?= $data->filters->order_by == 'audit_id' ? 'selected="selected"' : null ?>><?= l('global.id') ?></option>
                                <option value="datetime" <?= $data->filters->order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                                <option value="score" <?= $data->filters->order_by == 'score' ? 'selected="selected"' : null ?>><?= l('audits.score') ?></option>
                                <option value="total_issues" <?= $data->filters->order_by == 'total_issues' ? 'selected="selected"' : null ?>><?= l('audits.total_issues') ?></option>
                            </select>
                        </div>

                        <button type="submit" name="submit" class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition-all cursor-pointer">
                            <?= l('global.submit') ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Audits List Table -->
    <?php if (!empty($data->audits)): ?>
        <form id="table" action="<?= SITE_URL . 'audits/bulk' ?>" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />
            <input type="hidden" name="type" value="" data-bulk-type />
            <input type="hidden" name="original_request" value="<?= base64_encode(\Altum\Router::$original_request) ?>" />
            <input type="hidden" name="original_request_query" value="<?= base64_encode(\Altum\Router::$original_request_query) ?>" />

            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-x-auto">
                <table class="sr-table">
                    <thead>
                        <tr>
                            <th data-bulk-table class="hidden w-10">
                                <input id="bulk_select_all" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-blue-600" />
                            </th>
                            <th>Website</th>
                            <th>Score</th>
                            <th>Issues</th>
                            <th>Date</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data->audits as $row): ?>
                            <tr>
                                <td data-bulk-table class="hidden">
                                    <input id="selected_audit_id_<?= $row->audit_id ?>" type="checkbox" name="selected[]" value="<?= $row->audit_id ?>" class="h-4 w-4 rounded border-slate-300 text-blue-600" />
                                </td>
                                <td>
                                    <a href="<?= url('audit/' . $row->audit_id) ?>" class="font-bold text-slate-900 hover:text-blue-600 text-decoration-none block">
                                        <?= string_truncate(remove_url_protocol_from_url($row->url), 36) ?>
                                    </a>
                                    <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium">
                                        <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain($row->host) ?>" class="h-3.5 w-3.5 rounded" loading="lazy" />
                                        <span><?= $row->host ?></span>
                                        <a href="<?= $row->url ?>" target="_blank" rel="noreferrer" class="text-slate-400 hover:text-blue-600"><i class="fas fa-external-link-alt text-[10px]"></i></a>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <span class="font-extrabold text-xs text-slate-800"><?= nr($row->score) ?>%</span>
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
                                    <span class="text-xs text-slate-500 font-medium" title="<?= \Altum\Date::get($row->datetime, 1) ?>">
                                        <?= \Altum\Date::get_timeago($row->datetime) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="<?= url('audit/' . $row->audit_id) ?>" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all text-decoration-none">
                                            View Report
                                        </a>
                                        <?= include_view(THEME_PATH . 'views/audits/audit_dropdown_button.php', ['id' => $row->audit_id, 'resource_name' => remove_url_protocol_from_url($row->url), 'url' => $row->url]) ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </form>

        <div class="pt-4"><?= $data->pagination ?></div>
    <?php else: ?>
        <div class="bg-white rounded-3xl border border-slate-200/80 p-8 text-center space-y-2">
            <i class="fas fa-bolt text-3xl text-slate-300"></i>
            <h3 class="text-sm font-bold text-slate-700">No Audits Found</h3>
            <p class="text-xs text-slate-400">Run a website audit from your dashboard to view results here.</p>
        </div>
    <?php endif ?>
</div>

<?php require THEME_PATH . 'views/partials/js_bulk.php' ?>
<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/bulk_delete_modal.php'), 'modals'); ?>

<?php ob_start() ?>
<script>
    document.addEventListener('click', () => {
        document.getElementById('export_dropdown')?.classList.add('hidden');
        document.getElementById('filters_dropdown')?.classList.add('hidden');
    });
    document.getElementById('export_dropdown')?.addEventListener('click', e => e.stopPropagation());
    document.getElementById('filters_dropdown')?.addEventListener('click', e => e.stopPropagation());
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
