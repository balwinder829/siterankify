<?php defined('ALTUMCODE') || die() ?>

<div class="max-w-4xl mx-auto space-y-6 font-sans">
    <?= \Altum\Alerts::output_alerts() ?>

    <nav aria-label="breadcrumb">
        <ol class="flex flex-wrap items-center gap-2 text-xs text-slate-400 font-bold mb-2 p-0 list-none">
            <li>
                <a href="<?= url('tickets') ?>" class="text-slate-500 hover:text-blue-600 text-decoration-none transition-colors"><?= l('tickets.breadcrumb') ?></a>
            </li>
            <li><i class="fas fa-fw fa-angle-right text-slate-300 text-[10px]"></i></li>
            <li class="text-slate-800" aria-current="page"><?= l('ticket_create.breadcrumb') ?></li>
        </ol>
    </nav>

    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight m-0"><?= l('ticket_create.header') ?></h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1 m-0">Submit a support request to our engineering and SEO diagnostic team.</p>
        </div>
        <a href="<?= url('tickets') ?>" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all text-decoration-none inline-flex items-center gap-1.5 shrink-0">
            <i class="fas fa-arrow-left text-[11px]"></i>
            <span class="hidden sm:inline">Back to Tickets</span>
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs">
        <form action="" method="post" role="form" class="space-y-5">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="space-y-1.5">
                <label for="subject" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><?= l('ticket_create.subject') ?> <span class="text-rose-500">*</span></label>
                <input type="text" id="subject" name="subject" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium <?= \Altum\Alerts::has_field_errors('subject') ? 'border-rose-400' : null ?>" placeholder="e.g. Need assistance with white-label PDF audit export" value="<?= $data->values['subject'] ?>" required="required" />
                <?= \Altum\Alerts::output_field_error('subject') ?>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label for="ticket_category_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><?= l('ticket_create.category') ?></label>
                    <select id="ticket_category_id" name="ticket_category_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium">
                        <option value=""><?= l('global.none') ?> (General Inquiry)</option>
                        <?php foreach($data->categories as $row): ?>
                            <option value="<?= $row->ticket_category_id ?>"><?= $row->name ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label for="priority" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><?= l('ticket_create.priority') ?></label>
                    <select id="priority" name="priority" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium">
                        <option value="low"><?= l('tickets.priority.low') ?></option>
                        <option value="medium" selected><?= l('tickets.priority.medium') ?></option>
                        <option value="high"><?= l('tickets.priority.high') ?></option>
                    </select>
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="message" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><?= l('ticket_create.message') ?> <span class="text-rose-500">*</span></label>
                <textarea id="message" name="message" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium <?= \Altum\Alerts::has_field_errors('message') ? 'border-rose-400' : null ?>" rows="6" placeholder="Please describe your issue, questions, or diagnostic request in detail..." required="required"><?= $data->values['message'] ?></textarea>
                <?= \Altum\Alerts::output_field_error('message') ?>
            </div>

            <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-slate-100">
                <span class="text-xs text-slate-400 order-2 sm:order-1">Our support team responds promptly within 24 hours.</span>
                <button type="submit" name="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition-all cursor-pointer border-0 order-1 sm:order-2 inline-flex items-center justify-center gap-2">
                    <i class="fas fa-paper-plane text-[11px]"></i>
                    <span><?= l('global.create') ?></span>
                </button>
            </div>
        </form>
    </div>
</div>
