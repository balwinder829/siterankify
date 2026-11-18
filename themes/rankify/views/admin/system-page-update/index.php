<?php defined('ALTUMCODE') || die() ?>

<nav aria-label="breadcrumb">
    <ol class="custom-breadcrumbs small">
        <li>
            <a href="<?= url('admin/pages') ?>"><?= l('admin_pages.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
        </li>
        <li class="active" aria-current="page">System Page SEO Update</li>
    </ol>
</nav>

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3 mb-0 text-truncate"><i class="fas fa-fw fa-xs fa-cogs text-primary-900 mr-2"></i> <?= ucfirst($data->slug) ?> SEO Settings</h1>

    <div>
        <a href="<?= SITE_URL . ($data->slug == 'home' ? '' : $data->slug) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-fw fa-sm fa-external-link-alt mr-1"></i> View Page
        </a>
    </div>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="card">
    <div class="card-body">

        <form action="" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="form-group">
                <label for="title"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> Meta Title</label>
                <input id="title" type="text" name="title" class="form-control" value="<?= $data->page_seo->title ?>" maxlength="256" />
                <small class="form-text text-muted">Leave empty to use the system default.</small>
            </div>

            <div class="form-group">
                <label for="description"><i class="fas fa-fw fa-sm fa-pen text-muted mr-1"></i> Meta Description</label>
                <textarea id="description" name="description" class="form-control" maxlength="256"><?= $data->page_seo->description ?></textarea>
            </div>

            <div class="form-group">
                <label for="keywords"><i class="fas fa-fw fa-sm fa-file-word text-muted mr-1"></i> Meta Keywords</label>
                <textarea id="keywords" name="keywords" class="form-control" maxlength="256"><?= $data->page_seo->keywords ?></textarea>
            </div>

            <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= get_max_upload() ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), get_max_upload()) ?>">
                <label for="image"><i class="fas fa-fw fa-sm fa-image text-muted mr-1"></i> <?= l('admin_resources.image') ?></label>
                <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'pages', 'file_key' => 'image', 'already_existing_image' => $data->page_seo->image ?? null]) ?>
                <?= \Altum\Alerts::output_field_error('image') ?>
                <small class="form-text text-muted"><?= l('admin_resources.image_help') . ' ' . sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('pages')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), get_max_upload()) ?></small>
            </div>

            <div class="form-group">
                <label for="location">Location / Address (for Map)</label>
                <input id="location" type="text" name="location" class="form-control" value="<?= settings()->system_pages_seo->{$data->system_page_id}->location ?? '' ?>" placeholder="e.g. Dubai, UAE" />
                <small class="form-text text-muted">Enter your business address to show it on the Google Map (Contact Page only).</small>
            </div>

            <div class="form-group">
                <label for="content"><i class="fas fa-fw fa-sm fa-paragraph text-muted mr-1"></i> Content</label>
                <div id="quill_container">
                    <div id="quill"></div>
                </div>
                <textarea name="content" id="content" class="form-control d-none" style="height: 15rem;"><?= e($data->page_seo->content) ?></textarea>
            </div>

            <div class="form-group custom-control custom-switch">
                <input id="is_indexable" name="is_indexable" type="checkbox" class="custom-control-input" <?= $data->page_seo->is_indexable ? 'checked="checked"' : null ?>>
                <label class="custom-control-label" for="is_indexable"><i class="fas fa-fw fa-sm fa-search text-muted mr-1"></i> <?= l('admin_resources.is_indexable') ?></label>
                <small class="form-text text-muted"><?= l('admin_resources.is_indexable_help') ?></small>
            </div>

            <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
        </form>
    </div>
</div>

<?php ob_start() ?>
<link href="<?= ASSETS_FULL_URL . 'css/libraries/quill.snow.css?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/quill.min.js?v=' . PRODUCT_CODE ?>"></script>

<script>
    'use strict';

    /* Initiate QuillJs */
    let quill = new Quill('#quill', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ "font": [] }, { "size": ["small", false, "large", "huge"] }],
                ["bold", "italic", "underline", "strike"],
                [{ "color": [] }, { "background": [] }],
                [{ "script": "sub" }, { "script": "super" }],
                [{ "header": 1 }, { "header": 2 }, "blockquote", "code-block"],
                [{ "list": "ordered" }, { "list": "bullet" }, { "indent": "-1" }, { "indent": "+1" }],
                [{ "direction": "rtl" }, { "align": [] }],
                ["link", "image", "video", "formula"],
                ["clean"]
            ]
        },
    });
    quill.root.innerHTML = document.querySelector('#content').value;

    /* Handle form submission with the editor */
    document.querySelector('form').addEventListener('submit', async event => {
        document.querySelector('#content').value = quill.root.innerHTML;
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
