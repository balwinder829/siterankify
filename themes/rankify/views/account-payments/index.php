<?php defined('ALTUMCODE') || die() ?>

<div class="max-w-6xl mx-auto space-y-6 font-sans">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <div class="flex items-center gap-3">
            <h1 class="text-xl font-extrabold text-slate-900 tracking-tight m-0">Invoice details</h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('account_payments.subheader') ?>">
                    <i class="fas fa-fw fa-info-circle text-slate-500"></i>
                </span>
            </div>
        </div>

        <div class="flex items-center gap-2.5 w-full sm:w-auto justify-end">
                <div>
                    <div class="dropdown">
                        <button type="button" class="btn btn-light dropdown-toggle-simple <?= count($data->payments) ? null : 'disabled' ?>" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.export') ?>" data-tooltip-hide-on-click>
                            <i class="fas fa-fw fa-sm fa-download"></i>
                        </button>

                        <div class="dropdown-menu dropdown-menu-right d-print-none">
                            <a href="<?= url('account-payments?' . $data->filters->get_get() . '&export=csv') ?>" target="_blank" class="dropdown-item <?= $this->user->plan_settings->export->csv ? null : 'disabled pointer-events-all' ?>" <?= $this->user->plan_settings->export->csv ? null : get_plan_feature_disabled_info() ?>>
                                <i class="fas fa-fw fa-sm fa-file-csv mr-2"></i> <?= sprintf(l('global.export_to'), 'CSV') ?>
                            </a>
                            <a href="<?= url('account-payments?' . $data->filters->get_get() . '&export=json') ?>" target="_blank" class="dropdown-item <?= $this->user->plan_settings->export->json ? null : 'disabled pointer-events-all' ?>" <?= $this->user->plan_settings->export->json ? null : get_plan_feature_disabled_info() ?>>
                                <i class="fas fa-fw fa-sm fa-file-code mr-2"></i> <?= sprintf(l('global.export_to'), 'JSON') ?>
                    </a>
                    <a href="#" class="dropdown-item <?= $this->user->plan_settings->export->pdf ? null : 'disabled pointer-events-all' ?>" <?= $this->user->plan_settings->export->pdf ? $this->user->plan_settings->export->pdf ? 'onclick="event.preventDefault(); downloadPaymentsPDF();"' : 'disabled pointer-events-all' : get_plan_feature_disabled_info() ?>>
                        <i class="fas fa-fw fa-sm fa-file-pdf mr-2"></i> <?= sprintf(l('global.export_to'), 'PDF') ?>
                    </a>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="dropdown">
                        <button type="button" class="btn <?= $data->filters->has_applied_filters ? 'btn-dark' : 'btn-light' ?> filters-button dropdown-toggle-simple <?= count($data->payments) || $data->filters->has_applied_filters ? null : 'disabled' ?>" data-toggle="dropdown" data-boundary="viewport" data-tooltip data-html="true" title="<?= l('global.filters.tooltip') ?>" data-tooltip-hide-on-click>
                        <i class="fas fa-fw fa-sm fa-filter"></i>
                    </button>

                        <div class="dropdown-menu dropdown-menu-right filters-dropdown">
                            <div class="dropdown-header d-flex justify-content-between">
                                <span class="h6 m-0"><?= l('global.filters.header') ?></span>

                                <?php if($data->filters->has_applied_filters): ?>
                                    <a href="<?= url(\Altum\Router::$original_request) ?>" class="text-slate-500"><?= l('global.filters.reset') ?></a>
                                <?php endif ?>
                            </div>

                            <div class="dropdown-divider"></div>

                            <form action="" method="get" role="form">
                                <div class="form-group px-4">
                                    <label for="processor" class="small"><?= l('account_payments.processor') ?></label>
                                    <select name="processor" id="processor" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium custom-select-sm">
                                        <option value=""><?= l('global.all') ?></option>
                                        <?php foreach($data->payment_processors as $key => $value): ?>
                                            <option value="<?= $key ?>" <?= isset($data->filters->filters['processor']) && $data->filters->filters['processor'] == $key ? 'selected="selected"' : null ?>><?= l('pay.custom_plan.' . $key) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>

                                <div class="form-group px-4">
                                    <label for="type" class="small"><?= l('global.type') ?></label>
                                    <select name="type" id="type" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium custom-select-sm">
                                        <option value=""><?= l('global.all') ?></option>
                                        <option value="one_time" <?= isset($data->filters->filters['type']) && $data->filters->filters['type'] == 'one_time' ? 'selected="selected"' : null ?>><?= l('account_payments.type_one_time') ?></option>
                                        <option value="recurring" <?= isset($data->filters->filters['type']) && $data->filters->filters['type'] == 'recurring' ? 'selected="selected"' : null ?>><?= l('account_payments.type_recurring') ?></option>
                                    </select>
                                </div>

                                <div class="form-group px-4">
                                    <label for="frequency" class="small"><?= l('account_payments.frequency') ?></label>
                                    <select name="frequency" id="frequency" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium custom-select-sm">
                                        <option value=""><?= l('global.all') ?></option>
                                        <?php foreach(['monthly', 'quarterly', 'biannual', 'annual', 'lifetime'] as $payment_frequency): ?>
                                            <option value="monthly" <?= isset($data->filters->filters['frequency']) && $data->filters->filters['frequency'] == $payment_frequency ? 'selected="selected"' : null ?>><?= l('plan.custom_plan.' . $payment_frequency) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>

                                <div class="form-group px-4">
                                    <label for="filters_order_by" class="small"><?= l('global.filters.order_by') ?></label>
                                    <select name="order_by" id="filters_order_by" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium custom-select-sm">
                                        <option value="id" <?= $data->filters->order_by == 'id' ? 'selected="selected"' : null ?>><?= l('global.id') ?></option>
                                        <option value="datetime" <?= $data->filters->order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                                        <option value="total_amount" <?= $data->filters->order_by == 'total_amount' ? 'selected="selected"' : null ?>><?= l('account_payments.order_by_total_amount') ?></option>
                                    </select>
                                </div>

                                <div class="form-group px-4">
                                    <label for="filters_order_type" class="small"><?= l('global.filters.order_type') ?></label>
                                    <select name="order_type" id="filters_order_type" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium custom-select-sm">
                                        <option value="ASC" <?= $data->filters->order_type == 'ASC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_asc') ?></option>
                                        <option value="DESC" <?= $data->filters->order_type == 'DESC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_desc') ?></option>
                                    </select>
                                </div>

                                <div class="form-group px-4">
                                    <label for="filters_results_per_page" class="small"><?= l('global.filters.results_per_page') ?></label>
                                    <select name="results_per_page" id="filters_results_per_page" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium custom-select-sm">
                                        <?php foreach($data->filters->allowed_results_per_page as $key): ?>
                                            <option value="<?= $key ?>" <?= $data->filters->results_per_page == $key ? 'selected="selected"' : null ?>><?= $key ?></option>
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

    <?php if (!empty($data->payments)): ?>
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-x-auto">
            <table class="sr-table">
                <thead>
                <tr>
                    <th><?= l('account_payments.plan_id') ?></th>
                    <th><?= l('account_payments.total_amount') ?></th>
                    <th><?= l('account_payments.payment_processor') ?></th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                <?php foreach($data->payments as $row): ?>

                    <tr>
                        <td class="text-nowrap">
                            <div class="d-flex flex-column">
                                <span><?= $row->translations->{\Altum\Language::$name}->name ?? $row->plan_name ?></span>
                                <span class="text-slate-500"><?= l('pay.custom_plan.' . $row->type . '_type') ?></span>
                            </div>
                        </td>

                        <td class="text-nowrap">
                            <span class="sr-badge sr-badge-success"><?= $row->total_amount ?> <?= $row->currency ?></span>
                        </td>

                        <td class="text-nowrap">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-800 border border-slate-200">
                                <i class="<?= $data->payment_processors[$row->processor]['icon'] ?> fa-fw mr-1" style="color: <?= $data->payment_processors[$row->processor]['color'] ?>"></i>
                                <span><?= l('pay.custom_plan.' . $row->processor) ?></span>
                            </span>
                        </td>

                        <td class="text-nowrap">
                            <span class="" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), '<br />' . \Altum\Date::get($row->datetime, 2) . '<br /><small>' . \Altum\Date::get($row->datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($row->datetime) . ')</small>') ?>">
                                <i class="fas fa-fw fa-calendar text-slate-500"></i>
                            </span>
                        </td>

                        <td class="text-nowrap">
                            <div class="d-flex justify-content-end">
                                <?php if($row->status): ?>
                                    <?php if(settings()->payment->invoice_is_enabled): ?>
                                        <a href="<?= url('invoice/' . $row->id) ?>" class="btn btn-sm btn-primary" target="_blank">
                                            <i class="fas fa-fw fa-sm fa-file-invoice"></i> <?= l('account_payments.invoice') ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="sr-badge sr-badge-success"><?= l('account_payments.status_approved') ?></span>
                                    <?php endif ?>
                                <?php else: ?>
                                    <span class="sr-badge sr-badge-warning"><?= l('account_payments.status_pending') ?></span>
                                <?php endif ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach ?>

                </tbody>
            </table>
        </div>

        <div class="mt-3"><?= $data->pagination ?></div>
    <?php else: ?>
        <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
            'filters_get' => $data->filters->get ?? [],
            'name' => 'account_payments',
            'has_secondary_text' => false,
        ]); ?>
    <?php endif ?>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
function downloadPaymentsPDF() {
    const table = document.querySelector('.table-custom-container');
    if (!table) return;

    // Clone the element to render in isolation
    const element = table.cloneNode(true);
    
    // Set explicit colors to prevent white-on-white in dark mode or themes
    element.style.color = '#000000';
    element.style.backgroundColor = '#ffffff';
    element.style.padding = '20px';
    
    // Clean up inline styles for child elements to use black text
    element.querySelectorAll('*').forEach(el => {
        el.style.color = '#000000';
        el.style.borderColor = '#dddddd';
    });

    const options = {
        margin:       10,
        filename:     'payments-history.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2, useCORS: true, backgroundColor: '#ffffff' },
        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'landscape' }
    };

    html2pdf().set(options).from(element).save();
}
</script>

