<?php defined('ALTUMCODE') || die() ?>

<div class="space-y-6 font-sans">
    <?= \Altum\Alerts::output_alerts() ?>

    <!-- Top Header Bar -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-lg border border-slate-200">
                <i class="fas fa-archive"></i>
            </div>
            <div>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight m-0 flex items-center gap-2">
                    <span><?= l('archived_audits.header') ?></span>
                    <span title="<?= l('archived_audits.subheader') ?>"><i class="fas fa-info-circle text-xs text-slate-400"></i></span>
                </h1>
                <p class="text-xs text-slate-500 font-medium m-0">Historical log of previous website audit runs</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5 w-full lg:w-auto justify-end">
            <!-- Export Dropdown -->
            <div class="relative dropdown">
                <button type="button" class="h-10 w-10 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition-all focus:outline-none <?= count($data->archived_audits) ? null : 'opacity-50 cursor-not-allowed' ?>" data-toggle="dropdown" title="<?= l('global.export') ?>">
                    <i class="fas fa-download text-xs"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-right shadow-2xl border border-slate-200/90 rounded-2xl p-2 w-48 space-y-1">
                    <a href="<?= url('archived-audits?' . $data->filters->get_get() . '&export=csv')  ?>" target="_blank" class="dropdown-item rounded-xl py-2 px-3 flex items-center gap-2 text-xs font-semibold text-slate-700 hover:bg-slate-100 <?= $this->user->plan_settings->export->csv ? null : 'opacity-50 cursor-not-allowed' ?>">
                        <i class="fas fa-file-csv text-blue-500"></i> Export CSV
                    </a>
                    <a href="<?= url('archived-audits?' . $data->filters->get_get() . '&export=json') ?>" target="_blank" class="dropdown-item rounded-xl py-2 px-3 flex items-center gap-2 text-xs font-semibold text-slate-700 hover:bg-slate-100 <?= $this->user->plan_settings->export->json ? null : 'opacity-50 cursor-not-allowed' ?>">
                        <i class="fas fa-file-code text-indigo-500"></i> Export JSON
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Archived Audits Table -->
    <?php if (!empty($data->archived_audits)): ?>
        <form id="table" action="<?= SITE_URL . 'archived-audits/bulk' ?>" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />
            <input type="hidden" name="type" value="" data-bulk-type />

            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                <table class="sr-table">
                    <thead>
                        <tr>
                            <th>Website</th>
                            <th>Score</th>
                            <th>Issues</th>
                            <th>Archived Date</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data->archived_audits as $row): ?>
                            <tr>
                                <td>
                                    <a href="<?= url('archived-audit/' . $row->archived_audit_id) ?>" class="font-bold text-slate-900 hover:text-blue-600 text-decoration-none block">
                                        <?= string_truncate(remove_url_protocol_from_url($row->url), 36) ?>
                                    </a>
                                    <span class="text-xs text-slate-400 font-medium"><?= $row->host ?></span>
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
                                        <a href="<?= url('archived-audit/' . $row->archived_audit_id) ?>" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all text-decoration-none">
                                            View Report
                                        </a>
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
            <i class="fas fa-archive text-3xl text-slate-300"></i>
            <h3 class="text-sm font-bold text-slate-700">No Archived Audits</h3>
            <p class="text-xs text-slate-400">Previous audit history will appear here.</p>
        </div>
    <?php endif ?>
</div>
