<?php defined('ALTUMCODE') || die() ?>

<nav aria-label="breadcrumb">
    <ol class="custom-breadcrumbs small">
        <li>
            <a href="<?= url('admin/tickets-categories') ?>"><?= l('admin_tickets_categories.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
        </li>
        <li class="active" aria-current="page"><?= l('admin_ticket_category_update.breadcrumb') ?></li>
    </ol>
</nav>

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3 m-0"><i class="fas fa-fw fa-xs fa-book text-primary-900 mr-2"></i> <?= l('admin_ticket_category_update.header') ?></h1>

    <?= include_view(THEME_PATH . 'views/admin/tickets-categories/admin_ticket_category_dropdown_button.php', ['id' => $data->ticket_category->ticket_category_id, 'resource_name' => $data->ticket_category->name]) ?>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="card <?= \Altum\Alerts::has_field_errors() ? 'border-danger' : null ?>">
    <div class="card-body">

        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="form-group">
                <label for="name"><?= l('admin_tickets_categories.table.name') ?></label>
                <input type="text" id="name" name="name" class="form-control" value="<?= $data->ticket_category->name ?>" required="required" />
            </div>

            <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
        </form>

    </div>
</div>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_url.php', [
    'name' => 'ticket_category',
    'resource_id' => 'ticket_category_id',
    'has_dynamic_resource_name' => true,
    'path' => 'admin/tickets-categories/delete'
]), 'modals'); ?>
