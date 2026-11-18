<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3 m-0"><i class="fas fa-fw fa-xs fa-book text-primary-900 mr-2"></i> <?= l('admin_tickets_categories.header') ?></h1>

    <div class="col-auto p-0">
        <a href="<?= url('admin/ticket-category-create') ?>" class="btn btn-primary"><i class="fas fa-fw fa-plus-circle"></i> <?= l('admin_tickets_categories.create') ?></a>
    </div>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="table-responsive table-custom-container">
    <table class="table table-custom">
        <thead>
        <tr>
            <th><?= l('admin_tickets_categories.table.name') ?></th>
            <th><?= l('admin_global.datetime') ?></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php $results = db()->get('tickets_categories'); ?>
        <?php if(count($results)): ?>
            <?php foreach($results as $row): ?>
                <tr>
                    <td class="text-nowrap">
                        <a href="<?= url('admin/ticket-category-update/' . $row->ticket_category_id) ?>"><?= $row->name ?></a>
                    </td>
                    <td class="text-nowrap text-muted">
                        <?= \Altum\Date::get($row->datetime, 1) ?>
                    </td>
                    <td>
                        <div class="d-flex justify-content-end">
                            <?= include_view(THEME_PATH . 'views/admin/tickets-categories/admin_ticket_category_dropdown_button.php', ['id' => $row->ticket_category_id, 'resource_name' => $row->name]) ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach ?>
        <?php else: ?>
            <tr>
                <td colspan="3" class="text-muted"><?= l('global.no_data') ?></td>
            </tr>
        <?php endif ?>
        </tbody>
    </table>
</div>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_url.php', [
    'name' => 'ticket_category',
    'resource_id' => 'ticket_category_id',
    'has_dynamic_resource_name' => true,
    'path' => 'admin/tickets-categories/delete'
]), 'modals'); ?>
