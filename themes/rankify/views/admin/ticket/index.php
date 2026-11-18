<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li>
                <a href="<?= url('admin/tickets') ?>"><?= l('admin_tickets.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
            </li>
            <li class="active" aria-current="page"><?= l('admin_ticket.breadcrumb') ?></li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 m-0 text-truncate"><?= sprintf(l('admin_ticket.title'), $data->ticket->subject) ?></h1>

        <div class="d-flex align-items-center">
            <span class="badge badge-pill <?= $data->ticket->status == 'open' ? 'badge-success' : ($data->ticket->status == 'resolved' ? 'badge-primary' : 'badge-light') ?> mr-3" style="font-size: 0.9rem; padding: 0.5rem 1rem;">
                <?= l('tickets.status.' . $data->ticket->status) ?>
            </span>
            
            <div class="dropdown">
                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-fw fa-cog mr-1"></i> <?= l('global.edit') ?>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <h6 class="dropdown-header">Update Status</h6>
                    <a class="dropdown-item" href="<?= url('admin/ticket/' . $data->ticket->ticket_id . '/status/open') ?>"><?= l('tickets.status.open') ?></a>
                    <a class="dropdown-item" href="<?= url('admin/ticket/' . $data->ticket->ticket_id . '/status/pending') ?>"><?= l('tickets.status.pending') ?></a>
                    <a class="dropdown-item" href="<?= url('admin/ticket/' . $data->ticket->ticket_id . '/status/resolved') ?>"><?= l('tickets.status.resolved') ?></a>
                    <a class="dropdown-item" href="<?= url('admin/ticket/' . $data->ticket->ticket_id . '/status/closed') ?>"><?= l('tickets.status.closed') ?></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12 col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small text-uppercase font-weight-bold mb-1">User</div>
                    <div class="d-flex align-items-center">
                        <img src="<?= get_user_avatar($data->ticket->user_avatar, $data->ticket->user_email) ?>" class="rounded-circle mr-2" width="25" height="25" />
                        <a href="<?= url('admin/user-view/' . $data->ticket->user_id) ?>"><?= $data->ticket->user_name ?></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small text-uppercase font-weight-bold mb-1">Category</div>
                    <div><?= $data->ticket->category_name ?? l('tickets.no_category') ?></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small text-uppercase font-weight-bold mb-1">Priority</div>
                    <div>
                        <span class="badge <?= $data->ticket->priority == 'high' ? 'badge-danger' : ($data->ticket->priority == 'medium' ? 'badge-warning' : 'badge-info') ?>">
                            <?= l('tickets.priority.' . $data->ticket->priority) ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?= \Altum\Alerts::output_alerts() ?>

    <div class="ticket-chat-container mb-5">
        <?php foreach($data->ticket_replies as $reply): ?>
            <?php $is_admin_reply = $reply->user_id && db()->where('user_id', $reply->user_id)->getOne('users', ['type'])->type == 'admin'; ?>
            
            <div class="ticket-message <?= $is_admin_reply ? 'ticket-message-admin' : 'ticket-message-user' ?> d-flex mb-4">
                <div class="ticket-message-avatar mr-3">
                    <img src="<?= get_user_avatar($reply->avatar, $reply->email) ?>" class="rounded-circle shadow-sm" width="45" height="45" alt="" />
                </div>
                
                <div class="ticket-message-content flex-grow-1">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="font-weight-bold">
                                    <?= $reply->name ?>
                                    <?php if($is_admin_reply): ?>
                                        <span class="badge badge-primary ml-1" style="font-size: 0.65rem; text-transform: uppercase;">Staff</span>
                                    <?php endif ?>
                                </span>
                                <small class="text-muted">
                                    <?= \Altum\Date::get($reply->datetime, 1) ?> (<?= \Altum\Date::get_timeago($reply->datetime) ?>)
                                </small>
                            </div>
                            <div class="ticket-message-text">
                                <?= nl2br(e($reply->message)) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>

    <div class="card shadow-sm border-0 mb-5">
        <div class="card-body">
            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

                <div class="form-group">
                    <label for="message" class="font-weight-bold"><i class="fas fa-fw fa-sm fa-reply mr-1"></i> Send Reply</label>
                    <textarea id="message" name="message" class="form-control form-control-lg" rows="5" placeholder="Type your response here..." required="required"></textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" name="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                        <i class="fas fa-fw fa-paper-plane mr-1"></i> Reply
                    </button>
                    
                    <div class="custom-control custom-checkbox align-self-center">
                        <input type="checkbox" name="close_ticket" id="close_ticket" class="custom-control-input">
                        <label class="custom-control-label text-muted" for="close_ticket">Close ticket after reply</label>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .ticket-chat-container {
        display: flex;
        flex-direction: column;
    }
    .ticket-message-admin .card {
        background: #f0f7ff;
        border-left: 4px solid #007bff !important;
    }
    .ticket-message-user .card {
        background: #fdfdfd;
        border-left: 4px solid #6c757d !important;
    }
    .ticket-message-text {
        font-size: 0.95rem;
        line-height: 1.6;
        color: #333;
        word-break: break-all;
        overflow-wrap: break-word;
    }
    .custom-breadcrumbs {
        padding: 0;
        margin-bottom: 1rem;
        list-style: none;
        display: flex;
        flex-wrap: wrap;
    }
    .custom-breadcrumbs li {
        display: flex;
        align-items: center;
    }
    .custom-breadcrumbs li a {
        color: #6c757d;
        text-decoration: none;
    }
    .custom-breadcrumbs li i {
        margin: 0 0.5rem;
        color: #dee2e6;
        font-size: 0.75rem;
    }
    .custom-breadcrumbs li.active {
        color: #343a40;
    }
</style>
