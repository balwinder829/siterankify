<?php defined('ALTUMCODE') || die() ?>

<div class="max-w-4xl mx-auto space-y-6 font-sans">
    <nav aria-label="breadcrumb">
        <ol class="flex flex-wrap items-center gap-2 text-xs text-slate-400 font-bold mb-2 p-0 list-none">
            <li>
                <a href="<?= url('tickets') ?>" class="text-slate-500 hover:text-blue-600 text-decoration-none transition-colors"><?= l('tickets.breadcrumb') ?></a>
            </li>
            <li><i class="fas fa-fw fa-angle-right text-slate-300 text-[10px]"></i></li>
            <li class="text-slate-800" aria-current="page"><?= l('ticket.breadcrumb') ?> #<?= $data->ticket->ticket_id ?></li>
        </ol>
    </nav>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/80 shadow-xs">
        <div class="min-w-0 space-y-1">
            <h1 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight m-0 truncate"><?= e($data->ticket->subject) ?></h1>
            <div class="flex items-center gap-2 text-xs text-slate-400">
                <span>Created <?= \Altum\Date::get_timeago($data->ticket->datetime) ?></span>
                <?php if($data->ticket->category_name): ?>
                    <span>•</span>
                    <span class="text-slate-600 font-medium"><?= e($data->ticket->category_name) ?></span>
                <?php endif ?>
            </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <?php if($data->ticket->status == 'open'): ?>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                    <span><?= l('tickets.status.open') ?></span>
                </span>
            <?php elseif($data->ticket->status == 'pending'): ?>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200/80">
                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                    <span><?= l('tickets.status.pending') ?></span>
                </span>
            <?php elseif($data->ticket->status == 'resolved'): ?>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200/80">
                    <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                    <span><?= l('tickets.status.resolved') ?></span>
                </span>
            <?php else: ?>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                    <span><?= l('tickets.status.closed') ?></span>
                </span>
            <?php endif ?>

            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200">
                <?= l('tickets.priority.' . $data->ticket->priority) ?>
            </span>
        </div>
    </div>

    <?= \Altum\Alerts::output_alerts() ?>

    <div class="space-y-4">
        <?php foreach($data->ticket_replies as $reply): ?>
            <?php $is_admin = $reply->user_id && db()->where('user_id', $reply->user_id)->getOne('users', ['type'])->type == 'admin'; ?>

            <div class="flex gap-3 sm:gap-4 <?= $is_admin ? 'flex-row-reverse' : '' ?>">
                <div class="shrink-0">
                    <?php if($reply->avatar): ?>
                        <img src="<?= get_user_avatar($reply->avatar, $reply->email) ?>" class="h-10 w-10 rounded-2xl object-cover border border-slate-200" alt="" />
                    <?php else: ?>
                        <div class="h-10 w-10 rounded-2xl <?= $is_admin ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-700' ?> flex items-center justify-center font-bold text-xs border border-slate-200/80">
                            <?= strtoupper(mb_substr($reply->name, 0, 1)) ?>
                        </div>
                    <?php endif ?>
                </div>

                <div class="flex-1 max-w-2xl">
                    <div class="p-4 sm:p-5 rounded-2xl border <?= $is_admin ? 'bg-blue-50/40 border-blue-200/70' : 'bg-white border-slate-200/80' ?> shadow-xs space-y-2">
                        <div class="flex items-center justify-between gap-2 border-b <?= $is_admin ? 'border-blue-100' : 'border-slate-100' ?> pb-2">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-xs text-slate-900"><?= e($reply->name) ?></span>
                                <?php if($is_admin): ?>
                                    <span class="px-2 py-0.5 rounded-md bg-blue-600 text-white text-[10px] font-extrabold uppercase tracking-wider">Support</span>
                                <?php endif ?>
                            </div>
                            <span class="text-[11px] text-slate-400 font-medium" title="<?= \Altum\Date::get($reply->datetime, 1) ?>">
                                <?= \Altum\Date::get_timeago($reply->datetime) ?>
                            </span>
                        </div>
                        <div class="text-sm text-slate-700 leading-relaxed break-words">
                            <?= nl2br(e($reply->message)) ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>

    <?php if($data->ticket->status != 'closed'): ?>
        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 sm:p-7 shadow-xs">
            <form action="" method="post" role="form" class="space-y-4">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

                <div class="space-y-1.5">
                    <label for="message" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        <i class="fas fa-reply text-blue-500 mr-1"></i> Send Reply
                    </label>
                    <textarea id="message" name="message" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium" rows="4" placeholder="Type your reply or follow-up response here..." required="required"></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" name="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition-all cursor-pointer border-0 inline-flex items-center gap-2">
                        <i class="fas fa-paper-plane text-[11px]"></i>
                        <span><?= l('ticket.submit') ?></span>
                    </button>
                </div>
            </form>
        </div>
    <?php else: ?>
        <div class="p-4 rounded-2xl bg-slate-100 border border-slate-200 text-slate-600 text-xs font-semibold flex items-center gap-2.5">
            <i class="fas fa-lock text-slate-400"></i>
            <span><?= l('ticket.info_message.closed') ?></span>
        </div>
    <?php endif ?>
</div>
