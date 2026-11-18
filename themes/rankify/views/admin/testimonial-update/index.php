<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
    <ol class="custom-breadcrumbs small">
        <li>
            <a href="<?= url('admin/testimonials') ?>"><?= l('admin_testimonials.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
        </li>
        <li class="active" aria-current="page"><?= l('admin_testimonial_update.breadcrumb') ?></li>
    </ol>
</nav>
<?php endif ?>

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3 m-0"><i class="fas fa-fw fa-xs fa-comments text-primary-900 mr-2"></i> <?= l('admin_testimonial_update.header') ?></h1>

    <div class="d-flex align-items-center">
        <div class="dropdown">
            <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-fw fa-ellipsis-v"></i>
            </button>

            <div class="dropdown-menu dropdown-menu-right">
                <a href="#" class="dropdown-item" data-choose="<?= l('global.delete') ?>" data-id="<?= $data->testimonial->testimonial_id ?>" data-name="<?= $data->testimonial->name ?>" data-toggle="modal" data-target="#testimonial_delete_modal"><i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?></a>
            </div>
        </div>
    </div>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="card <?= \Altum\Alerts::has_field_errors() ? 'border-danger' : null ?>">
    <div class="card-body">
        <form action="" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="form-group">
                <label for="name"><?= l('admin_testimonials.input.name') ?></label>
                <input id="name" type="text" name="name" class="form-control <?= \Altum\Alerts::has_field_errors('name') ? 'is-invalid' : null ?>" value="<?= $data->testimonial->name ?>" maxlength="256" required="required" />
                <?= \Altum\Alerts::output_field_error('name') ?>
            </div>

            <div class="form-group">
                <label for="attribute"><?= l('admin_testimonials.input.attribute') ?></label>
                <input id="attribute" type="text" name="attribute" class="form-control" value="<?= $data->testimonial->attribute ?>" maxlength="256" />
            </div>

            <div class="form-group">
                <label for="text"><?= l('admin_testimonials.input.text') ?></label>
                <textarea id="text" name="text" class="form-control <?= \Altum\Alerts::has_field_errors('text') ? 'is-invalid' : null ?>" rows="6" required="required"><?= $data->testimonial->text ?></textarea>
                <?= \Altum\Alerts::output_field_error('text') ?>
            </div>

            <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= get_max_upload() ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), get_max_upload()) ?>">
                <label for="image"><?= l('admin_testimonials.input.image') ?></label>
                <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'testimonials', 'file_key' => 'image', 'already_existing_image' => $data->testimonial->image]) ?>
                <?= \Altum\Alerts::output_field_error('image') ?>
                <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('testimonials')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), get_max_upload()) ?></small>
            </div>

            <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
        </form>
    </div>
</div>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_url.php', [
    'name' => 'testimonial',
    'resource_id' => 'testimonial_id',
    'has_dynamic_resource_name' => true,
    'path' => 'admin/testimonials/delete/'
]), 'modals'); ?>
