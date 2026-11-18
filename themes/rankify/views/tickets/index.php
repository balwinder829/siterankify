<?php defined('ALTUMCODE') || die() ?>

<div class="space-y-6 font-sans">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/80 shadow-xs">
        <div class="space-y-1">
            <h1 class="text-xl font-extrabold text-slate-900 tracking-tight m-0 flex items-center gap-2">
                <i class="fas fa-ticket-alt text-blue-600 text-base"></i>
                <span><?= l('tickets.header') ?></span>
            </h1>
            <p class="text-xs text-slate-500 m-0"><?= l('tickets.subheader') ?></p>
        </div>

        <div class="w-full sm:w-auto flex justify-end">
            <a href="<?= url('ticket-create') ?>" class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition-all cursor-pointer border-0 inline-flex items-center justify-center gap-1.5 text-decoration-none">
                <i class="fas fa-plus text-[10px]"></i>
                <span><?= l('tickets.create') ?></span>
            </a>
        </div>
    </div>

    <?php if(count($data->tickets)): ?>
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200/80 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
                            <th class="p-4 pl-6"><?= l('tickets.table.subject') ?></th>
                            <th class="p-4"><?= l('tickets.table.status') ?></th>
                            <th class="p-4"><?= l('tickets.table.priority') ?></th>
                            <th class="p-4"><?= l('tickets.table.datetime') ?></th>
                            <th class="p-4 pr-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach($data->tickets as $row): ?>
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="p-4 pl-6 whitespace-nowrap">
                                    <a href="<?= url('ticket/' . $row->ticket_id) ?>" class="font-bold text-slate-900 hover:text-blue-600 text-decoration-none block">
                                        <?= e($row->subject) ?>
                                    </a>
                                    <span class="text-[11px] text-slate-400 font-normal">
                                        <?= $row->category_name ?? l('tickets.no_category') ?>
                                    </span>
                                </td>

                                <td class="p-4 whitespace-nowrap">
                                    <?php if($row->status == 'open'): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <i class="fas fa-check-circle text-[10px]"></i> <?= l('tickets.status.open') ?>
                                        </span>
                                    <?php elseif($row->status == 'pending'): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <i class="fas fa-clock text-[10px]"></i> <?= l('tickets.status.pending') ?>
                                        </span>
                                    <?php elseif($row->status == 'resolved'): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                            <i class="fas fa-check-double text-[10px]"></i> <?= l('tickets.status.resolved') ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                            <i class="fas fa-times text-[10px]"></i> <?= l('tickets.status.closed') ?>
                                        </span>
                                    <?php endif ?>
                                </td>

                                <td class="p-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200">
                                        <?= l('tickets.priority.' . $row->priority) ?>
                                    </span>
                                </td>

                                <td class="p-4 whitespace-nowrap text-slate-500 text-[11px]">
                                    <span title="<?= \Altum\Date::get($row->datetime, 1) ?>">
                                        <?= \Altum\Date::get_timeago($row->datetime) ?>
                                    </span>
                                </td>

                                <td class="p-4 pr-6 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="<?= url('ticket/' . $row->ticket_id) ?>" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-blue-50 hover:text-blue-600 text-slate-600 font-semibold text-[11px] transition-all text-decoration-none">
                                            <?= l('global.view') ?>
                                        </a>
                                        <button type="button" data-toggle="modal" data-target="#ticket_delete_modal" data-ticket-id="<?= $row->ticket_id ?>" data-resource-name="<?= $row->subject ?>" class="p-1 rounded-lg text-slate-400 hover:text-rose-600 transition-colors border-0 bg-transparent cursor-pointer" title="<?= l('global.delete') ?>">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4"><?= $data->pagination ?></div>
    <?php else: ?>
        <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
            'filters_get' => $data->filters->get ?? [],
            'name' => 'tickets',
            'has_secondary_text' => true,
        ]); ?>
    <?php endif ?>
</div>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_url.php', [
    'name' => 'ticket',
    'resource_id' => 'ticket_id',
    'has_data_resource_name' => true,
    'path' => 'tickets/delete'
]), 'modals'); ?>
