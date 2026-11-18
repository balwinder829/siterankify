<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->tools->similar_widget_is_enabled && isset($data->tools[$data->tool]['similar'])): ?>
    <?php $count = settings()->tools->style == 'frankfurt' ? 4 : 3; ?>

    <div class="mt-6 font-sans space-y-3">
        <h2 class="text-sm font-bold text-slate-900 tracking-tight m-0 flex items-center gap-2">
            <i class="fas fa-tools text-blue-600 text-xs"></i>
            <span><?= l('tools.similar_tools') ?></span>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="similar_tools">
            <?php $i = 0; ?>
            <?php foreach($data->tools[$data->tool]['similar'] as $key): ?>
                <?php if(settings()->tools->available_tools->{$key}): ?>
                    <?php if($i++ >= $count) break ?>

                    <?= include_view(THEME_PATH . 'views/tools/tool_widget_' . (settings()->tools->style ?? 'frankfurt') . '.php', [
                        'tool_id' => $key,
                        'tool_icon' => $data->tools[$key]['icon'],
                        'tools_usage' => $data->tools_usage
                    ]); ?>

                <?php endif ?>
            <?php endforeach ?>
        </div>
    </div>
<?php endif ?>

