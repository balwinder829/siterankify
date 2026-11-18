<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex flex-column flex-md-row justify-content-between mb-4">
    <h1 class="h3 mb-3 mb-md-0 text-truncate"><i class="fas fa-fw fa-xs fa-ticket-alt text-primary-900 mr-2"></i> <?= l('admin_tickets.header') ?></h1>

    <div class="d-flex position-relative d-print-none">
        <div class="ml-3">
            <div class="dropdown">
                <button type="button" class="btn <?= $data->filters->has_applied_filters ? 'btn-secondary' : 'btn-gray-300' ?> filters-button dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport" data-tooltip data-html="true" title="<?= l('global.filters.tooltip') ?>" data-tooltip-hide-on-click>
                    <i class="fas fa-fw fa-sm fa-filter"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-right filters-dropdown">
                    <div class="dropdown-header d-flex justify-content-between">
                        <span class="h6 m-0"><?= l('global.filters.header') ?></span>

                        <?php if($data->filters->has_applied_filters): ?>
                            <a href="<?= url(\Altum\Router::$original_request) ?>" class="text-muted"><?= l('global.filters.reset') ?></a>
                        <?php endif ?>
                    </div>

                    <div class="dropdown-divider"></div>

                    <form action="" method="get" role="form">
                        <div class="form-group px-4">
                            <label for="filters_search" class="small"><?= l('global.filters.search') ?></label>
                            <input type="search" name="search" id="filters_search" class="form-control form-control-sm" value="<?= $data->filters->search ?>" />
                        </div>

                        <div class="form-group px-4">
                            <label for="filters_search_by" class="small"><?= l('global.filters.search_by') ?></label>
                            <select name="search_by" id="filters_search_by" class="custom-select custom-select-sm">
                                <option value="subject" <?= $data->filters->search_by == 'subject' ? 'selected="selected"' : null ?>><?= l('tickets.table.subject') ?></option>
                            </select>
                        </div>

                        <div class="form-group px-4">
                            <label for="filters_status" class="small"><?= l('global.status') ?></label>
                            <select name="status" id="filters_status" class="custom-select custom-select-sm">
                                <option value=""><?= l('global.all') ?></option>
                                <option value="open" <?= isset($data->filters->filters['status']) && $data->filters->filters['status'] == 'open' ? 'selected="selected"' : null ?>><?= l('tickets.status.open') ?></option>
                                <option value="pending" <?= isset($data->filters->filters['status']) && $data->filters->filters['status'] == 'pending' ? 'selected="selected"' : null ?>><?= l('tickets.status.pending') ?></option>
                                <option value="resolved" <?= isset($data->filters->filters['status']) && $data->filters->filters['status'] == 'resolved' ? 'selected="selected"' : null ?>><?= l('tickets.status.resolved') ?></option>
                                <option value="closed" <?= isset($data->filters->filters['status']) && $data->filters->filters['status'] == 'closed' ? 'selected="selected"' : null ?>><?= l('tickets.status.closed') ?></option>
                            </select>
                        </div>

                        <div class="form-group px-4">
                            <label for="filters_priority" class="small"><?= l('tickets.table.priority') ?></label>
                            <select name="priority" id="filters_priority" class="custom-select custom-select-sm">
                                <option value=""><?= l('global.all') ?></option>
                                <option value="low" <?= isset($data->filters->filters['priority']) && $data->filters->filters['priority'] == 'low' ? 'selected="selected"' : null ?>><?= l('tickets.priority.low') ?></option>
                                <option value="medium" <?= isset($data->filters->filters['priority']) && $data->filters->filters['priority'] == 'medium' ? 'selected="selected"' : null ?>><?= l('tickets.priority.medium') ?></option>
                                <option value="high" <?= isset($data->filters->filters['priority']) && $data->filters->filters['priority'] == 'high' ? 'selected="selected"' : null ?>><?= l('tickets.priority.high') ?></option>
                            </select>
                        </div>

                        <div class="form-group px-4">
                            <label for="filters_ticket_category_id" class="small"><?= l('tickets.table.category') ?></label>
                            <select name="ticket_category_id" id="filters_ticket_category_id" class="custom-select custom-select-sm">
                                <option value=""><?= l('global.all') ?></option>
                                <?php foreach($data->categories as $row): ?>
                                    <option value="<?= $row->ticket_category_id ?>" <?= isset($data->filters->filters['ticket_category_id']) && $data->filters->filters['ticket_category_id'] == $row->ticket_category_id ? 'selected="selected"' : null ?>><?= $row->name ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div class="form-group px-4 mt-4">
                            <button type="submit" name="submit" class="btn btn-sm btn-primary btn-block"><?= l('global.submit') ?></button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="table-responsive table-custom-container">
    <table class="table table-custom">
        <thead>
        <tr>
            <th><?= l('global.user') ?></th>
            <th><?= l('tickets.table.subject') ?></th>
            <th><?= l('global.status') ?></th>
            <th><?= l('tickets.table.priority') ?></th>
            <th><?= l('tickets.table.datetime') ?></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($data->tickets as $row): ?>
            <tr>
                <td class="text-nowrap">
                    <div class="d-flex">
                        <a href="<?= url('admin/user-view/' . $row->user_id) ?>">
                            <img src="<?= get_gravatar($row->user_email) ?>" class="user-avatar rounded-circle mr-3" alt="" />
                        </a>

                        <div class="d-flex flex-column">
                            <div>
                                <a href="<?= url('admin/user-view/' . $row->user_id) ?>"><?= $row->user_name ?></a>
                            </div>

                            <span class="small text-muted"><?= $row->user_email ?></span>
                        </div>
                    </div>
                </td>

                <td class="text-nowrap">
                    <div>
                        <a href="<?= url('admin/ticket/' . $row->ticket_id) ?>"><?= $row->subject ?></a>
                    </div>

                    <div class="small">
                        <span class="text-muted"><?= $row->category_name ?? l('tickets.no_category') ?></span>
                    </div>
                </td>

                <td class="text-nowrap">
                    <?php if($row->status == 'open'): ?>
                        <span class="badge badge-success"><i class="fas fa-fw fa-sm fa-check mr-1"></i> <?= l('tickets.status.open') ?></span>
                    <?php elseif($row->status == 'pending'): ?>
                        <span class="badge badge-warning"><i class="fas fa-fw fa-sm fa-clock mr-1"></i> <?= l('tickets.status.pending') ?></span>
                    <?php elseif($row->status == 'resolved'): ?>
                        <span class="badge badge-info"><i class="fas fa-fw fa-sm fa-check-double mr-1"></i> <?= l('tickets.status.resolved') ?></span>
                    <?php elseif($row->status == 'closed'): ?>
                        <span class="badge badge-secondary"><i class="fas fa-fw fa-sm fa-times mr-1"></i> <?= l('tickets.status.closed') ?></span>
                    <?php endif ?>
                </td>

                <td class="text-nowrap">
                    <?php if($row->priority == 'low'): ?>
                        <span class="badge badge-light"><?= l('tickets.priority.low') ?></span>
                    <?php elseif($row->priority == 'medium'): ?>
                        <span class="badge badge-warning"><?= l('tickets.priority.medium') ?></span>
                    <?php elseif($row->priority == 'high'): ?>
                        <span class="badge badge-danger"><?= l('tickets.priority.high') ?></span>
                    <?php endif ?>
                </td>

                <td class="text-nowrap text-muted">
                    <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), '<br />' . \Altum\Date::get($row->datetime, 2) . '<br /><small>' . \Altum\Date::get($row->datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($row->datetime) . ')</small>') ?>">
                        <i class="fas fa-fw fa-calendar text-muted"></i>
                    </span>

                    <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.last_datetime_tooltip'), ($row->last_datetime ? '<br />' . \Altum\Date::get($row->last_datetime, 2) . '<br /><small>' . \Altum\Date::get($row->last_datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($row->last_datetime) . ')</small>' : '<br />' . l('global.na'))) ?>">
                        <i class="fas fa-fw fa-history text-muted"></i>
                    </span>
                </td>

                <td>
                    <div class="d-flex justify-content-end">
                        <div class="dropdown">
                            <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
                                <i class="fas fa-fw fa-ellipsis-v"></i>
                            </button>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="<?= url('admin/ticket/' . $row->ticket_id) ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-eye mr-2"></i> <?= l('global.view') ?></a>
                                <a href="#" data-toggle="modal" data-target="#ticket_delete_modal" data-ticket-id="<?= $row->ticket_id ?>" data-resource-name="<?= $row->subject ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?></a>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>

<div class="mt-3"><?= $data->pagination ?></div>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_url.php', [
    'name' => 'ticket',
    'resource_id' => 'ticket_id',
    'has_data_resource_name' => true,
    'path' => 'admin/tickets/delete'
]), 'modals'); ?>
