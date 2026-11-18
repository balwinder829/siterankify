<?php defined('ALTUMCODE') || die() ?>

<div class="space-y-6 font-sans">
    <?= \Altum\Alerts::output_alerts() ?>

    <!-- Top Header Bar -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg border border-amber-100">
                <i class="fas fa-bell"></i>
            </div>
            <div>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight m-0 flex items-center gap-2">
                    <span><?= l('notification_handlers.header') ?></span>
                    <span title="<?= l('notification_handlers.subheader') ?>"><i class="fas fa-info-circle text-xs text-slate-400"></i></span>
                </h1>
                <p class="text-xs text-slate-500 font-medium m-0">Webhook, Email, Slack, & Telegram alerts for automated audit checks</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5 w-full lg:w-auto justify-end">
            <a href="<?= url('notification-handler-create') ?>" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition-all text-decoration-none inline-flex items-center gap-2">
                <i class="fas fa-plus-circle"></i> <?= l('notification_handlers.create') ?>
            </a>
        </div>
    </div>

    <!-- Notification Handlers Table -->
    <?php if (!empty($data->notification_handlers)): ?>
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <table class="sr-table">
                <thead>
                    <tr>
                        <th>Channel Name</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Created Date</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data->notification_handlers as $row): ?>
                        <tr>
                            <td>
                                <a href="<?= url('notification-handler-update/' . $row->notification_handler_id) ?>" class="font-bold text-slate-900 hover:text-blue-600 text-decoration-none block">
                                    <?= $row->name ?>
                                </a>
                            </td>
                            <td>
                                <span class="sr-badge sr-badge-info uppercase tracking-wider text-[10px]">
                                    <?= l('notification_handlers.type_' . $row->type) ?>
                                </span>
                            </td>
                            <td>
                                <?php if($row->is_enabled): ?>
                                    <span class="sr-badge sr-badge-success"><i class="fas fa-check-circle mr-1"></i> Active</span>
                                <?php else: ?>
                                    <span class="sr-badge sr-badge-warning"><i class="fas fa-pause-circle mr-1"></i> Paused</span>
                                <?php endif ?>
                            </td>
                            <td>
                                <span class="text-xs text-slate-500 font-medium" title="<?= \Altum\Date::get($row->datetime, 1) ?>">
                                    <?= \Altum\Date::get_timeago($row->datetime) ?>
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center justify-end gap-2">
                                    <a href="<?= url('notification-handler-update/' . $row->notification_handler_id) ?>" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all text-decoration-none">
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
            <i class="fas fa-bell text-3xl text-slate-300"></i>
            <h3 class="text-sm font-bold text-slate-700">No Notification Channels Configured</h3>
            <p class="text-xs text-slate-400">Set up Slack, Telegram, or Webhooks to receive instant SEO alert notifications.</p>
        </div>
    <?php endif ?>
</div>
