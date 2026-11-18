<?php defined('ALTUMCODE') || die() ?>

<div class="dropdown">
    <button type="button" class="h-8 w-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-900 flex items-center justify-center transition-colors focus:outline-none border-0 cursor-pointer" data-toggle="dropdown" data-boundary="viewport" aria-label="Website Actions">
        <i class="fas fa-ellipsis-v text-xs"></i>
    </button>

    <div class="dropdown-menu dropdown-menu-right shadow-xl border border-slate-200/90 rounded-2xl p-1.5 min-w-[160px] text-xs font-semibold space-y-0.5">
        <a class="dropdown-item rounded-xl py-2 px-3 flex items-center gap-2 text-slate-700 hover:bg-slate-50 hover:text-blue-600 transition-colors" href="<?= url('website/' . $data->id) ?>">
            <i class="fas fa-eye text-slate-400 text-xs w-4"></i> <?= l('global.view') ?>
        </a>

        <a class="dropdown-item rounded-xl py-2 px-3 flex items-center gap-2 text-slate-700 hover:bg-slate-50 hover:text-blue-600 transition-colors" href="<?= url('website-update/' . $data->id) ?>">
            <i class="fas fa-pencil-alt text-slate-400 text-xs w-4"></i> <?= l('global.edit') ?>
        </a>

        <div class="border-t border-slate-100 my-1"></div>

        <a
            href="#"
            data-toggle="modal"
            data-target="#website_delete_modal"
            data-website-id="<?= $data->id ?>"
            data-resource-name="<?= $data->resource_name ?>"
            class="dropdown-item rounded-xl py-2 px-3 flex items-center gap-2 text-rose-600 hover:bg-rose-50 transition-colors"
        ><i class="fas fa-trash-alt text-rose-400 text-xs w-4"></i> <?= l('global.delete') ?></a>
    </div>
</div>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_form.php', [
    'name' => 'website',
    'resource_id' => 'website_id',
    'has_dynamic_resource_name' => true,
    'path' => 'websites/delete'
]), 'modals', 'website_delete_modal'); ?>

