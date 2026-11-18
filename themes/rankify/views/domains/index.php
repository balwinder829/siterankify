<?php defined('ALTUMCODE') || die() ?>

<div class="space-y-6 font-sans">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if($this->user->plan_settings->domains_limit != -1 && $data->total_domains > $this->user->plan_settings->domains_limit): ?>
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium flex items-center gap-2">
            <i class="fas fa-times-circle text-rose-500"></i>
            <div><?= sprintf(settings()->payment->is_enabled ? l('global.info_message.plan_feature_limit_removal_with_upgrade') : l('global.info_message.plan_feature_limit_removal'), '<strong>' . $data->total_domains - $this->user->plan_settings->domains_limit, mb_strtolower(l('domains.title')) . '</strong>', '<a href="' . url('plan') . '" class="font-bold text-rose-900 underline">' . l('global.info_message.plan_upgrade') . '</a>') ?></div>
        </div>
    <?php endif ?>

    <!-- Top Header Bar -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg border border-blue-100">
                <i class="fas fa-globe"></i>
            </div>
            <div>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight m-0 flex items-center gap-2">
                    <span><?= l('domains.header') ?></span>
                    <span title="<?= l('domains.subheader') ?>"><i class="fas fa-info-circle text-xs text-slate-400"></i></span>
                </h1>
                <p class="text-xs text-slate-500 font-medium m-0">Connect custom domains for white-label audit reports</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5 w-full lg:w-auto justify-end">
            <?php if($this->user->plan_settings->domains_limit != -1 && $data->total_domains >= $this->user->plan_settings->domains_limit): ?>
                <button type="button" class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-400 font-bold text-xs cursor-not-allowed" <?= get_plan_feature_limit_reached_info() ?>>
                    <i class="fas fa-plus-circle mr-1"></i> <?= l('domains.create') ?>
                </button>
            <?php else: ?>
                <a href="<?= url('domain-create') ?>" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition-all text-decoration-none inline-flex items-center gap-2">
                    <i class="fas fa-plus-circle"></i> <?= l('domains.create') ?>
                </a>
            <?php endif ?>
        </div>
    </div>

    <!-- Custom Domains Table -->
    <?php if (!empty($data->domains)): ?>
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <table class="sr-table">
                <thead>
                    <tr>
                        <th>Domain Name</th>
                        <th>Status</th>
                        <th>Created Date</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data->domains as $row): ?>
                        <tr>
                            <td>
                                <div class="font-bold text-slate-900"><?= $row->host ?></div>
                                <span class="text-xs text-slate-400 font-medium"><?= $row->scheme ?>://<?= $row->host ?></span>
                            </td>
                            <td>
                                <?php if($row->is_enabled == 1): ?>
                                    <span class="sr-badge sr-badge-success"><i class="fas fa-check-circle mr-1"></i> Active</span>
                                <?php elseif($row->is_enabled == 0): ?>
                                    <span class="sr-badge sr-badge-warning"><i class="fas fa-clock mr-1"></i> Pending Verification</span>
                                <?php else: ?>
                                    <span class="sr-badge sr-badge-danger"><i class="fas fa-times-circle mr-1"></i> Disabled</span>
                                <?php endif ?>
                            </td>
                            <td>
                                <span class="text-xs text-slate-500 font-medium" title="<?= \Altum\Date::get($row->datetime, 1) ?>">
                                    <?= \Altum\Date::get_timeago($row->datetime) ?>
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center justify-end gap-2">
                                    <a href="<?= url('domain-update/' . $row->domain_id) ?>" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all text-decoration-none">
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>

        <div class="pt-4"><?= $data->pagination ?></div>
    <?php else: ?>
        <div class="bg-white rounded-3xl border border-slate-200/80 p-8 text-center space-y-2">
            <i class="fas fa-globe text-3xl text-slate-300"></i>
            <h3 class="text-sm font-bold text-slate-700">No Custom Domains Connected</h3>
            <p class="text-xs text-slate-400">Click Add Custom Domain above to host audit reports on your own domain.</p>
        </div>
    <?php endif ?>
</div>
