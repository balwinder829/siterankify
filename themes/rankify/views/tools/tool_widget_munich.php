<?php defined('ALTUMCODE') || die() ?>

<div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-xs hover:shadow-sm hover:border-blue-300 transition-all flex flex-col justify-between h-full group space-y-3" data-tool-id="<?= $data->tool_id ?>" data-tool-name="<?= l('tools.' . $data->tool_id . '.name') ?>">
    <div class="space-y-2.5">
        <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-base group-hover:bg-blue-600 group-hover:text-white transition-all border border-blue-100">
            <i class="<?= $data->tool_icon ?>"></i>
        </div>

        <div class="space-y-1">
            <a href="<?= url('tools/' . str_replace('_', '-', $data->tool_id)) ?>" class="font-bold text-slate-900 hover:text-blue-600 text-sm tracking-tight text-decoration-none block truncate">
                <?= l('tools.' . $data->tool_id . '.name') ?>
            </a>
            <p class="text-xs text-slate-500 font-normal line-clamp-2 m-0"><?= l('tools.' . $data->tool_id . '.description') ?></p>
        </div>
    </div>

    <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
        <a href="<?= url('tools/' . str_replace('_', '-', $data->tool_id)) ?>" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-blue-600 hover:text-white text-slate-700 text-xs font-semibold inline-flex items-center gap-1.5 transition-all text-decoration-none">
            <span>Use Tool</span>
            <i class="fas fa-arrow-right text-[10px]"></i>
        </a>
    </div>
</div>
