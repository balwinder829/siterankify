<?php defined('ALTUMCODE') || die() ?>

<div id="index_content">
    <div class="form-group">
        <label for="title">Meta Title</label>
        <input id="title" type="text" name="title" class="form-control" value="<?= settings()->index->title ?? '' ?>" />
    </div>

    <div class="form-group">
        <label for="description">Meta Description</label>
        <textarea id="description" name="description" class="form-control"><?= settings()->index->description ?? '' ?></textarea>
    </div>

    <div class="form-group">
        <label for="keywords">Meta Keywords</label>
        <textarea id="keywords" name="keywords" class="form-control"><?= settings()->index->keywords ?? '' ?></textarea>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="is_indexable" name="is_indexable" type="checkbox" class="custom-control-input" <?= (settings()->index->is_indexable ?? true) ? 'checked="checked"' : null ?>>
        <label class="custom-control-label" for="is_indexable"><?= l('admin_resources.is_indexable') ?></label>
        <small class="form-text text-muted"><?= l('admin_resources.is_indexable_help') ?></small>
    </div>

    <hr class="my-4" />

    <div class="form-group">
        <label for="header"><i class="fas fa-fw fa-sm fa-heading text-muted mr-1"></i> <?= l('admin_settings.index_content.header') ?></label>
        <input id="header" type="text" name="header" class="form-control" value="<?= settings()->index->header ?? l('index.header') ?>" />
    </div>

    <div class="form-group">
        <label for="subheader"><i class="fas fa-fw fa-sm fa-paragraph text-muted mr-1"></i> <?= l('admin_settings.index_content.subheader') ?></label>
        <textarea id="subheader" name="subheader" class="form-control"><?= settings()->index->subheader ?? l('index.subheader') ?></textarea>
    </div>

    <hr class="my-4" />

    <h2 class="h5 mb-3">Tutorial Steps</h2>

    <div class="row">
        <div class="col-12 col-lg-4">
            <div class="form-group">
                <label for="tutorial_one"><?= l('admin_settings.index_content.tutorial_one') ?></label>
                <input id="tutorial_one" type="text" name="tutorial_one" class="form-control" value="<?= settings()->index->tutorial_one ?? l('index.tutorial.one') ?>" />
            </div>
            <div class="form-group">
                <label for="tutorial_one_text"><?= l('admin_settings.index_content.tutorial_one_text') ?></label>
                <textarea id="tutorial_one_text" name="tutorial_one_text" class="form-control"><?= settings()->index->tutorial_one_text ?? l('index.tutorial.one_text') ?></textarea>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="form-group">
                <label for="tutorial_two"><?= l('admin_settings.index_content.tutorial_two') ?></label>
                <input id="tutorial_two" type="text" name="tutorial_two" class="form-control" value="<?= settings()->index->tutorial_two ?? l('index.tutorial.two') ?>" />
            </div>
            <div class="form-group">
                <label for="tutorial_two_text"><?= l('admin_settings.index_content.tutorial_two_text') ?></label>
                <textarea id="tutorial_two_text" name="tutorial_two_text" class="form-control"><?= settings()->index->tutorial_two_text ?? l('index.tutorial.two_text') ?></textarea>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="form-group">
                <label for="tutorial_three"><?= l('admin_settings.index_content.tutorial_three') ?></label>
                <input id="tutorial_three" type="text" name="tutorial_three" class="form-control" value="<?= settings()->index->tutorial_three ?? l('index.tutorial.three') ?>" />
            </div>
            <div class="form-group">
                <label for="tutorial_three_text"><?= l('admin_settings.index_content.tutorial_three_text') ?></label>
                <textarea id="tutorial_three_text" name="tutorial_three_text" class="form-control"><?= settings()->index->tutorial_three_text ?? l('index.tutorial.three_text') ?></textarea>
            </div>
        </div>
    </div>

    <hr class="my-4" />

    <h2 class="h5 mb-3">Call to Action (CTA)</h2>

    <div class="form-group">
        <label for="cta_header"><?= l('admin_settings.index_content.cta_header') ?></label>
        <input id="cta_header" type="text" name="cta_header" class="form-control" value="<?= settings()->index->cta_header ?? l('index.cta.header') ?>" />
    </div>

    <div class="form-group">
        <label for="cta_subheader"><?= l('admin_settings.index_content.cta_subheader') ?></label>
        <textarea id="cta_subheader" name="cta_subheader" class="form-control"><?= settings()->index->cta_subheader ?? l('index.cta.subheader') ?></textarea>
    </div>

    <hr class="my-4" />

    <h2 class="h5 mb-3">Testimonials</h2>

    <div class="row">
        <!-- Testimonial 1 -->
        <div class="col-12 col-lg-4">
            <h3 class="h6 mb-2">Testimonial 1</h3>
            <div class="form-group">
                <label for="testimonial_one_name">Name</label>
                <input id="testimonial_one_name" type="text" name="testimonial_one_name" class="form-control" value="<?= settings()->index->testimonial_one_name ?? 'Andrea Wilson' ?>" />
            </div>
            <div class="form-group">
                <label for="testimonial_one_attribute">Job Title / Company</label>
                <input id="testimonial_one_attribute" type="text" name="testimonial_one_attribute" class="form-control" value="<?= settings()->index->testimonial_one_attribute ?? "Editor, Writer's Weekly" ?>" />
            </div>
            <div class="form-group">
                <label for="testimonial_one_text">Quote</label>
                <textarea id="testimonial_one_text" name="testimonial_one_text" class="form-control" rows="4"><?= settings()->index->testimonial_one_text ?? 'This platform completely transformed the way we manage our workflows...' ?></textarea>
            </div>
            <div class="form-group">
                <label class="font-weight-bold">Avatar / Photo</label>
                <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', [
                    'uploads_file_key' => 'custom_images',
                    'file_key' => 'testimonial_one_image',
                    'already_existing_image' => settings()->custom_images->{'index_testimonial-one_webp'} ?? null
                ]) ?>
            </div>
        </div>

        <!-- Testimonial 2 -->
        <div class="col-12 col-lg-4">
            <h3 class="h6 mb-2">Testimonial 2</h3>
            <div class="form-group">
                <label for="testimonial_two_name">Name</label>
                <input id="testimonial_two_name" type="text" name="testimonial_two_name" class="form-control" value="<?= settings()->index->testimonial_two_name ?? 'George Parker' ?>" />
            </div>
            <div class="form-group">
                <label for="testimonial_two_attribute">Job Title / Company</label>
                <input id="testimonial_two_attribute" type="text" name="testimonial_two_attribute" class="form-control" value="<?= settings()->index->testimonial_two_attribute ?? 'Founder, BrightPath Solutions' ?>" />
            </div>
            <div class="form-group">
                <label for="testimonial_two_text">Quote</label>
                <textarea id="testimonial_two_text" name="testimonial_two_text" class="form-control" rows="4"><?= settings()->index->testimonial_two_text ?? 'I was skeptical at first...' ?></textarea>
            </div>
            <div class="form-group">
                <label class="font-weight-bold">Avatar / Photo</label>
                <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', [
                    'uploads_file_key' => 'custom_images',
                    'file_key' => 'testimonial_two_image',
                    'already_existing_image' => settings()->custom_images->{'index_testimonial-two_webp'} ?? null
                ]) ?>
            </div>
        </div>

        <!-- Testimonial 3 -->
        <div class="col-12 col-lg-4">
            <h3 class="h6 mb-2">Testimonial 3</h3>
            <div class="form-group">
                <label for="testimonial_three_name">Name</label>
                <input id="testimonial_three_name" type="text" name="testimonial_three_name" class="form-control" value="<?= settings()->index->testimonial_three_name ?? 'Calvin Mitchell' ?>" />
            </div>
            <div class="form-group">
                <label for="testimonial_three_attribute">Job Title / Company</label>
                <input id="testimonial_three_attribute" type="text" name="testimonial_three_attribute" class="form-control" value="<?= settings()->index->testimonial_three_attribute ?? 'CEO, FlowWorks Inc.' ?>" />
            </div>
            <div class="form-group">
                <label for="testimonial_three_text">Quote</label>
                <textarea id="testimonial_three_text" name="testimonial_three_text" class="form-control" rows="4"><?= settings()->index->testimonial_three_text ?? "We've tried multiple tools before..." ?></textarea>
            </div>
            <div class="form-group">
                <label class="font-weight-bold">Avatar / Photo</label>
                <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', [
                    'uploads_file_key' => 'custom_images',
                    'file_key' => 'testimonial_three_image',
                    'already_existing_image' => settings()->custom_images->{'index_testimonial-three_webp'} ?? null
                ]) ?>
            </div>
        </div>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
