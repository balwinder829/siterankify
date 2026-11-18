<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex flex-column flex-md-row justify-content-between mb-4">
    <h1 class="h3 mb-3 mb-md-0 text-truncate"><i class="fas fa-fw fa-xs fa-box-open text-primary-900 mr-2"></i> Plan Credits</h1>
    <p class="text-muted">Manage the default credit limits for each plan.</p>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="table-responsive table-custom-container">
    <table class="table table-custom">
        <thead>
        <tr>
            <th>Plan Name</th>
            <th>Audits Limit</th>
            <th>Report Views Limit</th>
            <th>PDF Downloads Limit</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($data->plans as $row): ?>
            <?php
                // Audits
                $audits_limit = $row->settings->audits_per_month_limit ?? 0;
                $audits_display = $audits_limit == -1 ? 'Unlimited' : $audits_limit;

                // Views
                $views_limit = $row->settings->report_views_limit ?? null;
                if($views_limit === null) {
                    if ($row->plan_id == 'free') $views_limit = 15;
                    else if ($row->plan_id == 1) $views_limit = 50;
                    else if ($row->plan_id == 2) $views_limit = 100;
                    else if ($row->plan_id == 3) $views_limit = 120;
                    else $views_limit = 15;
                }
                $views_display = $views_limit == -1 ? 'Unlimited' : $views_limit;

                // PDF
                $pdf_limit = $row->settings->pdf_downloads_limit ?? null;
                if($pdf_limit === null) {
                    if ($row->plan_id == 'free') $pdf_limit = 1;
                    else if ($row->plan_id == 1) $pdf_limit = 1;
                    else if ($row->plan_id == 2) $pdf_limit = 10;
                    else if ($row->plan_id == 3) $pdf_limit = 120;
                    else $pdf_limit = 10;
                }
                $pdf_display = $pdf_limit == -1 ? 'Unlimited' : $pdf_limit;
            ?>
            <tr>
                <td class="text-nowrap">
                    <div class="d-flex flex-column">
                        <span class="font-weight-bold"><?= $row->name ?></span>
                        <span class="text-muted small">Plan ID: <?= $row->plan_id ?></span>
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
                        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#edit_plan_credits_modal_<?= $row->plan_id ?>">
                            <i class="fas fa-fw fa-pencil-alt"></i> Edit Plan Credits
                        </button>
                    </div>
                </td>
            </tr>

            <!-- Edit Plan Credits Modal -->
            <div class="modal fade" id="edit_plan_credits_modal_<?= $row->plan_id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Plan Credits: <?= $row->name ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="<?= url('admin/plan-credits/update') ?>" method="post" role="form">
                            <div class="modal-body">
                                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />
                                <input type="hidden" name="plan_id" value="<?= $row->plan_id ?>" />
                                
                                <p class="small text-muted mb-3">Update the default limits for all users assigned to this plan.</p>
                                
                                <div class="form-group">
                                    <label for="audits_per_month_limit_<?= $row->plan_id ?>"><i class="fas fa-fw fa-sm fa-bolt text-muted mr-1"></i> Audits Per <?= (stripos($row->name, 'yearly') !== false || stripos($row->name, 'year') !== false) ? 'Year' : 'Month' ?> Limit</label>
                                    <input type="number" class="form-control" id="audits_per_month_limit_<?= $row->plan_id ?>" name="audits_per_month_limit" value="<?= $audits_limit ?>" required="required" />
                                    <small class="form-text text-muted">Set to -1 for unlimited.</small>
                                </div>

                                <div class="form-group">
                                    <label for="report_views_limit_<?= $row->plan_id ?>"><i class="fas fa-fw fa-sm fa-eye text-muted mr-1"></i> Report Views Limit</label>
                                    <input type="number" class="form-control" id="report_views_limit_<?= $row->plan_id ?>" name="report_views_limit" value="<?= $views_limit ?>" required="required" />
                                    <small class="form-text text-muted">Set to -1 for unlimited.</small>
                                </div>

                                <div class="form-group">
                                    <label for="pdf_downloads_limit_<?= $row->plan_id ?>"><i class="fas fa-fw fa-sm fa-file-pdf text-muted mr-1"></i> PDF Downloads Limit</label>
                                    <input type="number" class="form-control" id="pdf_downloads_limit_<?= $row->plan_id ?>" name="pdf_downloads_limit" value="<?= $pdf_limit ?>" required="required" />
                                    <small class="form-text text-muted">Set to -1 for unlimited.</small>
                                </div>
                                
                                <hr>

                                <div class="form-group custom-control custom-switch">
                                    <input id="update_users_plan_settings_<?= $row->plan_id ?>" name="update_users_plan_settings" type="checkbox" class="custom-control-input" value="1" checked="checked">
                                    <label class="custom-control-label" for="update_users_plan_settings_<?= $row->plan_id ?>">Update existing users' plan settings</label>
                                    <small class="form-text text-muted">If checked, all current users on this plan will instantly get these new limits.</small>
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
