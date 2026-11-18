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
        <div class="space-y-1"><h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight m-0"><?= l('tools.opengraph_checker.name') ?></h1><p class="text-xs text-slate-500 font-medium m-0 leading-relaxed"><?= l('tools.opengraph_checker.description') ?></p></div>

        <?= $this->views['ratings'] ?>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
        <div>

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

                <div class="form-group" data-type="website">
                    <label for="url" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fas fa-fw fa-sm fa-link text-muted mr-1"></i> <?= l('global.url') ?></label>
                    <input type="url" id="url" name="url" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium <?= \Altum\Alerts::has_field_errors('url') ? 'is-invalid' : null ?>" value="<?= $data->values['url'] ?>" placeholder="<?= l('global.url_placeholder') ?>" required="required" />
                    <?= \Altum\Alerts::output_field_error('url') ?>
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
            </div>

            <style>
                /* Audit search previews */
                .audit-preview-opengraph-no-image {
                    background-color: var(--gray-300);
                    border-radius: var(--border-radius);
                    width: 100%;
                    height: 240px;
                    object-fit: contain;
                }

                .audit-preview-opengraph-image {
                    background-color: var(--gray-300);
                    border-radius: var(--border-radius);
                    width: 100%;
                    max-height: 262px;
                    object-fit: contain;
                }

                .audit-preview-host {
                    color: #202124;
                    font-size: 14px;
                }

                [data-theme-style="dark"] .audit-preview-host {
                    color: #dadce0;
                }

                .audit-preview-title {
                    color: #1800ab;
                    font-family: Arial, sans-serif;
                    font-size: 20px;
                    font-weight: 400;
                    display: inline-block;
                    line-height: 1.3;
                    margin-bottom: 3px;
                    padding-top: 5px;
                }

                [data-theme-style="dark"] .audit-preview-title {
                    color: #7fa7ff;
                }

                .audit-preview-description {
                    font-family: Arial, sans-serif;
                    font-size: 14px;
                    font-weight: 400;
                    line-height: 22px;
                    color: #474747;
                }

                [data-theme-style="dark"] .audit-preview-description {
                    color: #cdcdcd;
                }
            </style>

            <div class="card mb-3">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between">
                        <span class="small font-weight-bold"><?= l('tools.opengraph_checker.preview') ?></span>

                        <span class="badge bg-primary-50 text-primary-700">
                            <i class="fa fa-fw fa-sm fa-image"></i>
                        </span>
                    </div>
                </div>

                <div class="card-body audit-preview-wrapper">
                    <div class="d-flex flex-column" style="max-width: 500px;">
                        <?php if(isset($data->result['opengraph']['og:image'])): ?>
                            <img src="<?= $data->result['opengraph']['og:image'] ?>" class="audit-preview-opengraph-image" referrerpolicy="no-referrer" loading="lazy" alt="" />
                        <?php else: ?>
                            <div class="audit-preview-opengraph-no-image"></div>
                        <?php endif ?>

                        <div class="d-flex align-items-center mt-2">
                            <div class="audit-preview-host"><?= $data->result['host'] ?></div>
                        </div>

                        <h3 class="audit-preview-title"><?= $data->result['title'] ? e($data->result['title']) : l('audits.title_missing') ?></h3>

                        <div class="audit-preview-description">
                            <?= $data->result['meta_description'] ? e($data->result['meta_description']) : l('audits.meta_description_missing') ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    <?php endif ?>

    <?php if(settings()->tools->last_submissions_is_enabled && isset($data->tools_usage[\Altum\Router::$method]) && !empty((array) $data->tools_usage[\Altum\Router::$method]->data)): ?>
        <div class="mt-8"><h2 class="text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-3 flex items-center gap-2"><i class="fas fa-history text-slate-400"></i><i class="fas fa-fw fa-sm fa-plus text-primary mr-1"></i> <?= l('tools.last_submissions') ?></h2><div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
                <div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <?php foreach((array) $data->tools_usage[\Altum\Router::$method]->data as $key => $value): ?>
                            <div>
                                <div class="truncate text-xs font-semibold text-slate-700 py-1.5">
                                    <a href="<?= url('tools/' . str_replace('_', '-', \Altum\Router::$method) . '?' . http_build_query((array) $value)) ?>" onclick="this.href += '&submit=1<?= \Altum\Csrf::get_url_query() ?>'">
                                        <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain(parse_url($value->url, PHP_URL_HOST)) ?>" class="img-fluid icon-favicon mr-1" loading="lazy" />

                                        <?= remove_url_protocol_from_url($value->url) ?>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>

                </div>
            </div>
        </div>

    <?php endif ?>

    <?php require_once THEME_PATH . 'views/tools/js_dynamic_url_processor.php' ?>

    <?= $this->views['extra_content'] ?>

    <?= $this->views['similar_tools'] ?>

    <?= $this->views['popular_tools'] ?>
</div>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>

