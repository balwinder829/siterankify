<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
    <ol class="custom-breadcrumbs small">
        <li>
            <a href="<?= url('admin/faqs') ?>"><?= l('admin_faqs.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
        </li>
        <li class="active" aria-current="page"><?= l('admin_faq_create.breadcrumb') ?></li>
    </ol>
</nav>
<?php endif ?>

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3 m-0"><i class="fas fa-fw fa-xs fa-question-circle text-primary-900 mr-2"></i> <?= l('admin_faq_create.header') ?></h1>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="card <?= \Altum\Alerts::has_field_errors() ? 'border-danger' : null ?>">
    <div class="card-body">
        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="form-group">
                <label for="question"><?= l('admin_faqs.input.question') ?></label>
                <input id="question" type="text" name="question" class="form-control <?= \Altum\Alerts::has_field_errors('question') ? 'is-invalid' : null ?>" value="<?= $data->values['question'] ?>" maxlength="256" required="required" />
                <?= \Altum\Alerts::output_field_error('question') ?>
            </div>

            <div class="form-group">
                <label for="answer"><?= l('admin_faqs.input.answer') ?></label>
                <textarea id="answer" name="answer" class="form-control <?= \Altum\Alerts::has_field_errors('answer') ? 'is-invalid' : null ?>" rows="6" required="required"><?= $data->values['answer'] ?></textarea>
                <?= \Altum\Alerts::output_field_error('answer') ?>
            </div>

            <div class="form-group">
                <label><?= l('admin_faqs.input.pages') ?></label>
                <?php foreach($data->available_pages as $page_key => $page_name): ?>
                    <div class="custom-control custom-switch mb-2">
                        <input id="pages_<?= $page_key ?>" name="pages[]" type="checkbox" class="custom-control-input" value="<?= $page_key ?>" <?= in_array($page_key, $data->values['pages'] ?? []) ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="pages_<?= $page_key ?>"><?= $page_name ?></label>
                    </div>
                <?php endforeach ?>
            </div>

            <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.create') ?></button>
        </form>
    </div>
</div>
