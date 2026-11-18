<?php defined('ALTUMCODE') || die() ?>

<div id="content">
    <button class="btn btn-block btn-gray-200 font-size-little-small font-weight-450 mb-4" type="button" data-toggle="collapse" data-target="#blog_container" aria-expanded="false" aria-controls="blog_container">
        <i class="fas fa-fw fa-blog fa-sm mr-1"></i> <?= l('admin_settings.content.blog') ?>
    </button>

    <div class="collapse" data-parent="#content" id="blog_container">
        <div class="form-group custom-control custom-switch">
            <input id="blog_is_enabled" name="blog_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->content->blog_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="blog_is_enabled"><i class="fas fa-fw fa-sm fa-blog text-muted mr-1"></i> <?= l('admin_settings.content.blog_is_enabled') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="blog_share_is_enabled" name="blog_share_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->content->blog_share_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="blog_share_is_enabled"><i class="fas fa-fw fa-sm fa-share-alt text-muted mr-1"></i> <?= l('admin_settings.content.blog_share_is_enabled') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="blog_search_widget_is_enabled" name="blog_search_widget_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->content->blog_search_widget_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="blog_search_widget_is_enabled"><i class="fas fa-fw fa-sm fa-search text-muted mr-1"></i> <?= l('admin_settings.content.blog_search_widget_is_enabled') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="blog_categories_widget_is_enabled" name="blog_categories_widget_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->content->blog_categories_widget_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="blog_categories_widget_is_enabled"><i class="fas fa-fw fa-sm fa-map text-muted mr-1"></i> <?= l('admin_settings.content.blog_categories_widget_is_enabled') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="blog_popular_widget_is_enabled" name="blog_popular_widget_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->content->blog_popular_widget_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="blog_popular_widget_is_enabled"><i class="fas fa-fw fa-sm fa-fire text-muted mr-1"></i> <?= l('admin_settings.content.blog_popular_widget_is_enabled') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="blog_views_is_enabled" name="blog_views_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->content->blog_views_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="blog_views_is_enabled"><i class="fas fa-fw fa-sm fa-eye text-muted mr-1"></i> <?= l('admin_settings.content.blog_views_is_enabled') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="blog_ratings_is_enabled" name="blog_ratings_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->content->blog_ratings_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="blog_ratings_is_enabled"><i class="fas fa-fw fa-sm fa-star text-muted mr-1"></i> <?= l('admin_settings.content.blog_ratings_is_enabled') ?></label>
        </div>

        <div class="form-group">
            <label for="blog_columns"><i class="fas fa-fw fa-sm fa-columns text-muted mr-1"></i> <?= l('admin_settings.content.blog_columns') ?></label>
            <select id="blog_columns" name="blog_columns" class="custom-select">
                <option value="1" <?= settings()->content->blog_columns == '1' ? 'selected="selected"' : null ?>>1</option>
                <option value="2" <?= settings()->content->blog_columns == '2' ? 'selected="selected"' : null ?>>2</option>
            </select>
        <div class="form-group">
            <label for="blog_bottom_header">Blog Index Bottom Header</label>
            <input id="blog_bottom_header" type="text" name="blog_bottom_header" class="form-control" value="<?= settings()->content->blog_bottom_header ?? '' ?>" />
        </div>

        <div class="form-group">
            <label for="blog_bottom_content">Blog Index Bottom Content</label>
            <div id="blog_quill_container">
                <div id="blog_quill"></div>
            </div>
            <textarea id="blog_bottom_content" name="blog_bottom_content" class="form-control d-none"><?= settings()->content->blog_bottom_content ?? '' ?></textarea>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 font-size-little-small font-weight-450 mb-4" type="button" data-toggle="collapse" data-target="#pages_container" aria-expanded="false" aria-controls="pages_container">
        <i class="fas fa-fw fa-info-circle fa-sm mr-1"></i> <?= l('admin_settings.content.pages') ?>
    </button>

    <div class="collapse" data-parent="#content" id="pages_container">
        <div class="form-group custom-control custom-switch">
            <input id="pages_is_enabled" name="pages_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->content->pages_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="pages_is_enabled"><i class="fas fa-fw fa-sm fa-info-circle text-muted mr-1"></i> <?= l('admin_settings.content.pages_is_enabled') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="pages_share_is_enabled" name="pages_share_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->content->pages_share_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="pages_share_is_enabled"><i class="fas fa-fw fa-sm fa-share-alt text-muted mr-1"></i> <?= l('admin_settings.content.pages_share_is_enabled') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="pages_popular_widget_is_enabled" name="pages_popular_widget_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->content->pages_popular_widget_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="pages_popular_widget_is_enabled"><i class="fas fa-fw fa-sm fa-fire text-muted mr-1"></i> <?= l('admin_settings.content.pages_popular_widget_is_enabled') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="pages_views_is_enabled" name="pages_views_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->content->pages_views_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="pages_views_is_enabled"><i class="fas fa-fw fa-sm fa-eye text-muted mr-1"></i> <?= l('admin_settings.content.pages_views_is_enabled') ?></label>
        </div>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>

<?php ob_start() ?>
    <link href="<?= ASSETS_FULL_URL . 'css/libraries/quill.snow.css?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/quill.min.js?v=' . PRODUCT_CODE ?>"></script>
<script>
    'use strict';
    let blog_quill = new Quill('#blog_quill', {
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
    blog_quill.root.innerHTML = document.querySelector('#blog_bottom_content').value;
    document.querySelector('form').addEventListener('submit', () => {
        document.querySelector('#blog_bottom_content').value = blog_quill.root.innerHTML;
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
