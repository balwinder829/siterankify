<?php defined('ALTUMCODE') || die() ?>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-2 font-sans space-y-8">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-4">
        <a href="<?= url() ?>" class="hover:text-blue-600 transition-colors text-decoration-none text-slate-500"><?= l('index.breadcrumb') ?></a>
        <i class="fas fa-chevron-right text-[10px] text-slate-400"></i>
        <a href="<?= url('tools') ?>" class="hover:text-blue-600 transition-colors text-decoration-none text-slate-500"><?= l('tools.breadcrumb') ?></a>
        <i class="fas fa-chevron-right text-[10px] text-slate-400"></i>
        <span class="text-slate-900 font-bold truncate"><?= l('tools.' . \Altum\Router::$method . '.name') ?></span></div>
<?php endif ?>

    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-2">
        <div class="space-y-1"><h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight m-0"><?= l('tools.js_minifier.name') ?></h1><p class="text-xs text-slate-500 font-medium m-0 leading-relaxed"><?= l('tools.js_minifier.description') ?></p></div>

        <?= $this->views['ratings'] ?>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
        <div>

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

                <div class="space-y-1.5"><label for="js" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fab fa-fw fa-js fa-sm text-muted mr-1"></i> <?= l('tools.js_minifier.js') ?></label>
                    <textarea id="js" name="js" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium <?= \Altum\Alerts::has_field_errors('js') ? 'is-invalid' : null ?>" required="required"><?= e($data->values['js']) ?></textarea>
                    <?= \Altum\Alerts::output_field_error('js') ?>
                </div>

                <button type="submit" name="submit" class="w-full py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-500/20 transition-all cursor-pointer mt-4"><?= l('global.submit') ?></button>
            </form>

        </div>
    </div>

    <?php if(isset($data->result)): ?>
        <div class="mt-8">
            <div class="flex items-center gap-2 mb-4">
                <div class="h-8 w-8 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                    <i class="fas fa-poll-h text-blue-600 text-xs"></i>
                </div>
                <h2 class="text-base font-extrabold text-slate-900 m-0">Results</h2>
            </div><div class="overflow-x-auto rounded-2xl border border-slate-200/80 bg-white shadow-sm mt-4">
                <table class="w-full text-left border-collapse text-sm">
                    <tbody>
                        <tr>
                            <td class="font-weight-bold">
                                <?= l('tools.js_minifier.result.js_characters') ?>
                            </td>
                            <td class="text-nowrap <?= $data->js_characters > $data->minified_js_characters ? 'text-danger' : 'text-success'; ?>">
                                <?= nr($data->js_characters) ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">
                                <?= l('tools.js_minifier.result.minified_js_characters') ?>
                            </td>
                            <td class="text-nowrap <?= $data->js_characters >= $data->minified_js_characters ? 'text-success' : 'text-danger'; ?>">
                                <?= nr($data->minified_js_characters) ?> <?= '(' . nr(get_percentage_change($data->js_characters, $data->minified_js_characters), 2) . '%)' ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-4">
            <div>
                <div>

                    <div class="space-y-1.5">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="result" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><?= l('tools.js_minifier.result') ?></label>
                            <div>
                                <button
                                        type="button"
                                        class="btn btn-link text-secondary"
                                        data-toggle="tooltip"
                                        title="<?= l('global.clipboard_copy') ?>"
                                        aria-label="<?= l('global.clipboard_copy') ?>"
                                        data-copy="<?= l('global.clipboard_copy') ?>"
                                        data-copied="<?= l('global.clipboard_copied') ?>"
                                        data-clipboard-target="#result"
                                        data-clipboard-text
                                >
                                    <i class="fas fa-fw fa-sm fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        <textarea id="result" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium" rows="10"><?= htmlspecialchars($data->result, ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>

                </div>
            </div>
        </div>
    <?php endif ?>

<?= $this->views['extra_content'] ?>

    <?= $this->views['similar_tools'] ?>

    <?= $this->views['popular_tools'] ?>
</div>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>
