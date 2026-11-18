<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex flex-column flex-md-row justify-content-between mb-4">
    <h1 class="h3 mb-3 mb-md-0 text-truncate"><i class="fas fa-fw fa-xs fa-coins text-primary-900 mr-2"></i> User Credits</h1>
</div>

<?= \Altum\Alerts::output_alerts() ?>



    <div class="table-responsive table-custom-container">
        <table class="table table-custom">
            <thead>
            <tr>
                <th><?= l('admin_users.table.user') ?></th>
                <th>Plan Details</th>
                <th>Audits Credits</th>
                <th>Report Views</th>
                <th>PDF Downloads</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($data->users as $row): ?>
                <?php
                    // Audits
                    $audits_limit = $row->plan_settings->audits_per_month_limit ?? 0;
                    $audits_used = $row->audit_audits_current_month ?? 0;
                    $audits_remaining = $audits_limit == -1 ? -1 : max(0, $audits_limit - $audits_used);
                    $audits_display = $audits_limit == -1 ? 'Unlimited' : $audits_remaining . ' / ' . $audits_limit;

                    // Views
                    $base_views_limit = $row->plan_settings->report_views_limit ?? null;
                    if($base_views_limit === null) {
                        if ($row->plan_id == 'free') $base_views_limit = 15;
                        else if ($row->plan_id == 1) $base_views_limit = 50;
                        else if ($row->plan_id == 2) $base_views_limit = 100;
                        else if ($row->plan_id == 3) $base_views_limit = 120;
                        else $base_views_limit = 15;
                    }
                    $views_limit = $base_views_limit;
                    $extra_views = $row->extra_report_views ?? 0;
                    if($views_limit != -1) $views_limit += $extra_views;
                    $views_used = db()->where('ip_md5', (string)$row->user_id)->where('type', 'report_view_account')->getValue('ip_tracking', 'count') ?? 0;
                    $views_remaining = $views_limit == -1 ? -1 : max(0, $views_limit - $views_used);
                    
                    if ($views_limit == -1) {
                        $views_display = 'Unlimited';
                    } else {
                        if ($extra_views > 0) {
                            $remaining_base = max(0, $base_views_limit - $views_used);
                            $remaining_extra = max(0, $extra_views - max(0, $views_used - $base_views_limit));
                            $views_display = $remaining_base . ' + ' . $remaining_extra . '(extra limit) / ' . $base_views_limit;
                        } else {
                            $views_display = $views_remaining . ' / ' . $base_views_limit;
                        }
                    }

                    // PDF
                    $base_pdf_limit = $row->plan_settings->pdf_downloads_limit ?? null;
                    if($base_pdf_limit === null) {
                        if ($row->plan_id == 'free') $base_pdf_limit = 1;
                        else if ($row->plan_id == 1) $base_pdf_limit = 1;
                        else if ($row->plan_id == 2) $base_pdf_limit = 10;
                        else if ($row->plan_id == 3) $base_pdf_limit = 120;
                        else $base_pdf_limit = 10;
                    }
                    $pdf_limit = $base_pdf_limit;
                    $extra_pdf = $row->extra_pdf_downloads ?? 0;
                    if($pdf_limit != -1) $pdf_limit += $extra_pdf;
                    $pdf_used = db()->where('ip_md5', (string)$row->user_id)->where('type', 'report_download_account')->getValue('ip_tracking', 'count') ?? 0;
                    $pdf_remaining = $pdf_limit == -1 ? -1 : max(0, $pdf_limit - $pdf_used);
                    
                    if ($pdf_limit == -1) {
                        $pdf_display = 'Unlimited';
                    } else {
                        if ($extra_pdf > 0) {
                            $remaining_pdf_base = max(0, $base_pdf_limit - $pdf_used);
                            $remaining_pdf_extra = max(0, $extra_pdf - max(0, $pdf_used - $base_pdf_limit));
                            $pdf_display = $remaining_pdf_base . ' + ' . $remaining_pdf_extra . '(extra limit) / ' . $base_pdf_limit;
                        } else {
                            $pdf_display = $pdf_remaining . ' / ' . $base_pdf_limit;
                        }
                    }


                ?>
                <tr>
                    <td class="text-nowrap">
                        <div class="d-flex align-items-center">
                            <a href="<?= url('admin/user-view/' . $row->user_id) ?>">
                                <img src="<?= get_user_avatar($row->avatar, $row->email) ?>" class="user-avatar rounded-circle mr-3" alt="" />
                            </a>

                            <div class="d-flex flex-column">
                                <div>
                                    <a href="<?= url('admin/user-view/' . $row->user_id) ?>"><?= $row->name ?></a>
                                </div>
                                <span class="text-muted small"><?= $row->email ?></span>
                            </div>
                        </div>
                    </td>
                    
                    <td class="text-nowrap">
                        <div class="d-flex flex-column">
                            <span><?= $data->plans[$row->plan_id]->name ?></span>
                        </div>
                    </td>

                    <td class="text-nowrap">
                        <span class="badge badge-warning"><i class="fas fa-bolt mr-1"></i> <?= $audits_display ?></span>
                    </td>

                    <td class="text-nowrap">
                        <span class="badge badge-info"><i class="fas fa-eye mr-1"></i> <?= $views_display ?></span>
                    </td>

                    <td class="text-nowrap">
                        <span class="badge badge-success"><i class="fas fa-file-pdf mr-1"></i> <?= $pdf_display ?></span>
                    </td>

                    <td>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#edit_credits_modal_<?= $row->user_id ?>">
                                <i class="fas fa-fw fa-pencil-alt"></i> Edit Credits
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Edit Credits Modal -->
                <div class="modal fade" id="edit_credits_modal_<?= $row->user_id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Credits: <?= $row->name ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="<?= url('admin/credits/update') ?>" method="post" role="form">
                                <div class="modal-body">
                                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />
                                    <input type="hidden" name="user_id" value="<?= $row->user_id ?>" />
                                    
                                    <h6 class="mt-3 text-muted">Add Extra Credits</h6>
                                    <p class="small text-muted mb-3">Add bonus credits on top of the user's limits.</p>
                                    
                                    <div class="form-group">
                                        <label for="extra_report_views_<?= $row->user_id ?>"><i class="fas fa-fw fa-sm fa-plus-circle text-muted mr-1"></i> Extra Report Views</label>
                                        <input type="number" class="form-control" id="extra_report_views_<?= $row->user_id ?>" name="extra_report_views" value="<?= $row->extra_report_views ?? 0 ?>" required="required" />
                                        <small class="form-text text-muted">Add additional views on top of the plan limit.</small>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="extra_pdf_downloads_<?= $row->user_id ?>"><i class="fas fa-fw fa-sm fa-plus-circle text-muted mr-1"></i> Extra PDF Downloads</label>
                                        <input type="number" class="form-control" id="extra_pdf_downloads_<?= $row->user_id ?>" name="extra_pdf_downloads" value="<?= $row->extra_pdf_downloads ?? 0 ?>" required="required" />
                                        <small class="form-text text-muted">Add additional PDF downloads on top of the plan limit.</small>
                                    </div>

                                    <div class="form-group custom-control custom-switch">
                                        <input id="reset_usage_<?= $row->user_id ?>" name="reset_usage" type="checkbox" class="custom-control-input" value="1">
                                        <label class="custom-control-label" for="reset_usage_<?= $row->user_id ?>"><i class="fas fa-fw fa-sm fa-undo text-muted mr-1"></i> Reset Current Usage</label>
                                        <small class="form-text text-muted">If checked, the user's current usage for this month will be reset to 0, giving them full access to the limits defined above.</small>
                                    </div>

                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle mr-1"></i> An email will be automatically sent to the user notifying them of these changes.
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>

<div class="mt-3"><?= $data->pagination ?></div>
