<?php defined('ALTUMCODE') || die() ?>

<div class="max-w-xl mx-auto space-y-6 font-sans">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="<?= url('teams') ?>" class="h-10 w-10 rounded-2xl bg-white border border-slate-200 text-slate-600 flex items-center justify-center hover:bg-slate-50 transition-all text-decoration-none">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>
            <div>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight m-0"><?= l('team_update.header') ?></h1>
                <p class="text-xs text-slate-500 font-medium m-0">Update team configuration and settings</p>
            </div>
        </div>

        <div>
            <?= include_view(THEME_PATH . 'views/team/team_dropdown_button.php', ['id' => $data->team->team_id, 'resource_name' => $data->team->name]) ?>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
        <form action="" method="post" class="space-y-4" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="space-y-1">
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><?= l('global.name') ?></label>
                <input type="text" id="name" name="name" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium <?= \Altum\Alerts::has_field_errors('name') ? 'border-rose-500' : null ?>" value="<?= $data->team->name ?>" required="required" />
                <?= \Altum\Alerts::output_field_error('name') ?>
            </div>

            <button type="submit" name="submit" class="w-full py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-500/20 transition-all cursor-pointer mt-4">
                <?= l('global.update') ?>
            </button>
        </form>
    </div>
</div>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_form.php', [
    'name' => 'team',
    'resource_id' => 'team_id',
    'has_dynamic_resource_name' => true,
    'path' => 'teams/delete'
]), 'modals'); ?>
