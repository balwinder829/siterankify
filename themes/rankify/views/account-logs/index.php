<?php defined('ALTUMCODE') || die() ?>

<div class="max-w-6xl mx-auto space-y-6 font-sans">
    <?= \Altum\Alerts::output_alerts() ?>

    <?= $this->views['account_header_menu'] ?>

    <!-- Top Action & Header Card -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-3.5">
            <div class="h-11 w-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg border border-blue-100/80 shrink-0">
                <i class="fas fa-scroll"></i>
            </div>
            <div>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight m-0 flex items-center gap-2">
                    <span><?= l('account_logs.header') ?></span>
                    <span data-toggle="tooltip" title="<?= l('account_logs.subheader') ?>">
                        <i class="fas fa-info-circle text-slate-400 text-xs cursor-pointer"></i>
                    </span>
                </h1>
                <p class="text-xs text-slate-500 font-medium m-0 mt-0.5"><?= l('account_logs.subheader') ?></p>
            </div>
        </div>

        <div class="flex items-center gap-2.5 w-full sm:w-auto justify-end">
            <!-- Export Dropdown -->
            <div class="relative">
                <button id="export_dropdown_btn" type="button" class="h-10 px-3.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center gap-2 text-xs font-bold transition-all focus:outline-none <?= count($data->logs) ? 'cursor-pointer' : 'opacity-50 cursor-not-allowed' ?>" title="<?= l('global.export') ?>">
                    <i class="fas fa-download text-xs"></i>
                    <span class="hidden sm:inline"><?= l('global.export') ?></span>
                </button>

                <div id="export_dropdown_menu" class="hidden absolute right-0 top-full mt-2 bg-white z-[100] shadow-2xl border border-slate-200/90 rounded-2xl p-2 w-52 space-y-1 font-sans">
                    <a href="<?= url('account-logs?' . $data->filters->get_get() . '&export=csv') ?>" target="_blank" class="dropdown-item rounded-xl py-2 px-3 flex items-center gap-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition-colors <?= $this->user->plan_settings->export->csv ? null : 'opacity-50 cursor-not-allowed' ?>" <?= $this->user->plan_settings->export->csv ? null : get_plan_feature_disabled_info() ?>>
                        <i class="fas fa-file-csv text-emerald-600 w-4"></i>
                        <span><?= sprintf(l('global.export_to'), 'CSV') ?></span>
                    </a>
                    <a href="<?= url('account-logs?' . $data->filters->get_get() . '&export=json') ?>" target="_blank" class="dropdown-item rounded-xl py-2 px-3 flex items-center gap-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition-colors <?= $this->user->plan_settings->export->json ? null : 'opacity-50 cursor-not-allowed' ?>" <?= $this->user->plan_settings->export->json ? null : get_plan_feature_disabled_info() ?>>
                        <i class="fas fa-file-code text-blue-600 w-4"></i>
                        <span><?= sprintf(l('global.export_to'), 'JSON') ?></span>
                    </a>
                    <a href="#" class="dropdown-item rounded-xl py-2 px-3 flex items-center gap-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition-colors <?= $this->user->plan_settings->export->pdf ? null : 'opacity-50 cursor-not-allowed' ?>" onclick="event.preventDefault(); window.print();">
                        <i class="fas fa-file-pdf text-rose-600 w-4"></i>
                        <span><?= sprintf(l('global.export_to'), 'PDF') ?></span>
                    </a>
                </div>
            </div>

            <!-- Filter Dropdown Button -->
            <div class="relative">
                <button id="filters_dropdown_btn" type="button" class="h-10 px-3.5 rounded-xl <?= $data->filters->has_applied_filters ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' ?> flex items-center gap-2 text-xs font-bold transition-all focus:outline-none cursor-pointer" title="<?= l('global.filters.tooltip') ?>">
                    <i class="fas fa-filter text-xs"></i>
                    <span><?= l('global.filters.header') ?></span>
                    <?php if($data->filters->has_applied_filters): ?>
                        <span class="h-2 w-2 rounded-full bg-white animate-pulse"></span>
                    <?php endif ?>
                </button>

                <!-- Scrollable Filter Dropdown Panel -->
                <div id="filters_dropdown_menu" class="hidden absolute right-0 top-full mt-2 bg-white z-[100] shadow-2xl border border-slate-200/90 rounded-2xl p-5 w-80 sm:w-96 max-h-[75vh] overflow-y-auto font-sans">
                    <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-sliders-h text-blue-600 text-xs"></i>
                            <span class="text-xs font-bold text-slate-900 uppercase tracking-wider"><?= l('global.filters.header') ?></span>
                        </div>
                        <?php if($data->filters->has_applied_filters): ?>
                            <a href="<?= url(\Altum\Router::$original_request) ?>" class="text-xs text-blue-600 hover:text-blue-700 font-bold hover:underline"><?= l('global.filters.reset') ?></a>
                        <?php endif ?>
                    </div>

                    <form action="" method="get" role="form" class="space-y-3.5">
                        <div class="space-y-1">
                            <label for="filters_search" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider"><?= l('global.filters.search') ?></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                    <i class="fas fa-search text-xs"></i>
                                </span>
                                <input type="search" name="search" id="filters_search" class="w-full pl-8 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-blue-600 focus:bg-white transition-all" placeholder="<?= l('global.filters.search') ?>" value="<?= $data->filters->search ?>" />
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label for="filters_search_by" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider"><?= l('global.filters.search_by') ?></label>
                            <select name="search_by" id="filters_search_by" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-blue-600 focus:bg-white transition-all font-medium">
                                <option value="ip" <?= $data->filters->search_by == 'ip' ? 'selected="selected"' : null ?>><?= l('global.ip') ?></option>
                                <option value="city_name" <?= $data->filters->search_by == 'city_name' ? 'selected="selected"' : null ?>><?= l('global.city') ?></option>
                                <option value="os_name" <?= $data->filters->search_by == 'os_name' ? 'selected="selected"' : null ?>><?= l('global.os_name') ?></option>
                                <option value="browser_name" <?= $data->filters->search_by == 'browser_name' ? 'selected="selected"' : null ?>><?= l('global.browser_name') ?></option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label for="filters_device_type" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider"><?= l('global.device') ?></label>
                            <select name="device_type" id="filters_device_type" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-blue-600 focus:bg-white transition-all font-medium">
                                <option value=""><?= l('global.all') ?></option>
                                <?php foreach(['desktop', 'tablet', 'mobile'] as $device_type): ?>
                                    <option value="<?= $device_type ?>" <?= isset($data->filters->filters['device_type']) && $data->filters->filters['device_type'] == $device_type ? 'selected="selected"' : null ?>><?= l('global.device.' . $device_type) ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label for="filters_continent_code" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider"><?= l('global.continent') ?></label>
                            <select name="continent_code" id="filters_continent_code" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-blue-600 focus:bg-white transition-all font-medium">
                                <option value=""><?= l('global.all') ?></option>
                                <?php foreach(get_continents_array() as $continent_code => $continent_name): ?>
                                    <option value="<?= $continent_code ?>" <?= isset($data->filters->filters['continent_code']) && $data->filters->filters['continent_code'] == $continent_code ? 'selected="selected"' : null ?>><?= $continent_name ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label for="filters_country_code" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider"><?= l('global.country') ?></label>
                            <select name="country_code" id="filters_country_code" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-blue-600 focus:bg-white transition-all font-medium">
                                <option value=""><?= l('global.all') ?></option>
                                <?php foreach(get_countries_array() as $country_code => $country_name): ?>
                                    <option value="<?= $country_code ?>" <?= isset($data->filters->filters['country_code']) && $data->filters->filters['country_code'] == $country_code ? 'selected="selected"' : null ?>><?= $country_name ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-2.5">
                            <div class="space-y-1">
                                <label for="filters_order_by" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider"><?= l('global.filters.order_by') ?></label>
                                <select name="order_by" id="filters_order_by" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-blue-600 focus:bg-white transition-all font-medium">
                                    <option value="id" <?= $data->filters->order_by == 'id' ? 'selected="selected"' : null ?>><?= l('global.id') ?></option>
                                    <option value="datetime" <?= $data->filters->order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                                </select>
                            </div>

                            <div class="space-y-1">
                                <label for="filters_order_type" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider"><?= l('global.filters.order_type') ?></label>
                                <select name="order_type" id="filters_order_type" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-blue-600 focus:bg-white transition-all font-medium">
                                    <option value="ASC" <?= $data->filters->order_type == 'ASC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_asc') ?></option>
                                    <option value="DESC" <?= $data->filters->order_type == 'DESC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_desc') ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label for="filters_results_per_page" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider"><?= l('global.filters.results_per_page') ?></label>
                            <select name="results_per_page" id="filters_results_per_page" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-blue-600 focus:bg-white transition-all font-medium">
                                <?php foreach($data->filters->allowed_results_per_page as $key): ?>
                                    <option value="<?= $key ?>" <?= $data->filters->results_per_page == $key ? 'selected="selected"' : null ?>><?= $key ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div class="pt-2">
                            <button type="submit" name="submit" class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition-all cursor-pointer flex items-center justify-center gap-2">
                                <i class="fas fa-check text-xs"></i>
                                <span><?= l('global.submit') ?></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Filters Notice (if applied) -->
    <?php if($data->filters->has_applied_filters): ?>
        <div class="flex items-center justify-between gap-3 px-4 py-3 bg-blue-50/70 border border-blue-100 rounded-2xl text-xs font-semibold text-blue-900">
            <div class="flex items-center gap-2">
                <i class="fas fa-filter text-blue-600"></i>
                <span>Active filters applied to activity logs list</span>
            </div>
            <a href="<?= url(\Altum\Router::$original_request) ?>" class="text-blue-700 hover:text-blue-900 font-bold hover:underline inline-flex items-center gap-1">
                <i class="fas fa-times-circle text-xs"></i>
                <span><?= l('global.filters.reset') ?></span>
            </a>
        </div>
    <?php endif ?>

    <!-- Logs Table Card -->
    <?php if (!empty($data->logs)): ?>
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200/80">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider"><?= l('global.type') ?></th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider"><?= l('global.ip') ?></th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider"><?= l('global.details') ?></th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right sm:text-left">Date & Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach($data->logs as $row): ?>
                            <?php //ALTUMCODE:DEMO if(DEMO) {$row->ip = 'hidden on demo';} ?>
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <!-- Type Badge -->
                                <td class="px-6 py-4.5 whitespace-nowrap align-middle">
                                    <?php 
                                        $type_lower = mb_strtolower($row->type ?? '');
                                        $badge_class = 'bg-blue-50 text-blue-700 border-blue-200/80';
                                        $icon_class = 'fa-fingerprint text-blue-500';

                                        if(str_contains($type_lower, 'delete') || str_contains($type_lower, 'destroy')) {
                                            $badge_class = 'bg-rose-50 text-rose-700 border-rose-200/80';
                                            $icon_class = 'fa-trash text-rose-500';
                                        } elseif(str_contains($type_lower, 'create') || str_contains($type_lower, 'insert') || str_contains($type_lower, 'register')) {
                                            $badge_class = 'bg-emerald-50 text-emerald-700 border-emerald-200/80';
                                            $icon_class = 'fa-plus-circle text-emerald-500';
                                        } elseif(str_contains($type_lower, 'update') || str_contains($type_lower, 'edit')) {
                                            $badge_class = 'bg-amber-50 text-amber-700 border-amber-200/80';
                                            $icon_class = 'fa-pen text-amber-500';
                                        } elseif(str_contains($type_lower, 'login') || str_contains($type_lower, 'auth')) {
                                            $badge_class = 'bg-blue-50 text-blue-700 border-blue-200/80';
                                            $icon_class = 'fa-sign-in-alt text-blue-500';
                                        } else {
                                            $badge_class = 'bg-slate-100 text-slate-700 border-slate-200/80';
                                            $icon_class = 'fa-history text-slate-500';
                                        }
                                    ?>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border <?= $badge_class ?>">
                                        <i class="fas <?= $icon_class ?> text-[11px]"></i>
                                        <span><?= htmlspecialchars($row->type) ?></span>
                                    </span>
                                </td>

                                <!-- IP & Location Info -->
                                <td class="px-6 py-4.5 whitespace-nowrap align-middle">
                                    <div class="flex flex-col gap-1.5">
                                        <span class="inline-flex items-center gap-1.5 font-mono text-xs font-bold text-slate-800 bg-slate-100/90 px-2.5 py-1 rounded-lg border border-slate-200/70 w-fit">
                                            <i class="fas fa-network-wired text-[10px] text-slate-400"></i>
                                            <span><?= htmlspecialchars($row->ip) ?></span>
                                        </span>

                                        <div class="flex items-center gap-1.5 text-xs text-slate-500 font-medium">
                                            <?php if($row->country_code): ?>
                                                <img src="<?= ASSETS_FULL_URL . 'images/countries/' . mb_strtolower($row->country_code) . '.svg' ?>" class="w-4 h-3 object-cover rounded-2xs inline-block shadow-2xs" data-toggle="tooltip" title="<?= get_country_from_country_code($row->country_code) ?>" alt="" />
                                            <?php else: ?>
                                                <i class="fas fa-flag text-[11px] text-slate-400" data-toggle="tooltip" title="<?= l('global.unknown') ?>"></i>
                                            <?php endif ?>

                                            <span data-toggle="tooltip" title="<?= htmlspecialchars(($row->city_name ? $row->city_name . ', ' : '') . ($row->country_code ? get_country_from_country_code($row->country_code) . ' (' . get_continent_from_continent_code($row->continent_code) . ')' : l('global.unknown'))) ?>">
                                                <?= htmlspecialchars($row->city_name ?: ($row->country_code ? get_country_from_country_code($row->country_code) : l('global.unknown'))) ?>
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Environment & Device Badges -->
                                <td class="px-6 py-4.5 whitespace-nowrap align-middle">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <!-- Device Type -->
                                        <?php if($row->device_type): ?>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100/90 border border-slate-200/70 rounded-lg text-xs font-semibold text-slate-700" data-toggle="tooltip" title="<?= l('global.device.' . $row->device_type) ?>">
                                                <i class="fas fa-<?= $row->device_type ?> text-[11px] text-slate-500"></i>
                                                <span class="capitalize"><?= htmlspecialchars(l('global.device.' . $row->device_type)) ?></span>
                                            </span>
                                        <?php endif ?>

                                        <!-- Operating System -->
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100/90 border border-slate-200/70 rounded-lg text-xs font-semibold text-slate-700" data-toggle="tooltip" title="<?= htmlspecialchars($row->os_name ?: l('global.unknown')) ?>">
                                            <img src="<?= ASSETS_FULL_URL . 'images/os/' . os_name_to_os_key($row->os_name) . '.svg' ?>" class="w-3.5 h-3.5 object-contain" alt="" />
                                            <span><?= htmlspecialchars($row->os_name ?: l('global.unknown')) ?></span>
                                        </span>

                                        <!-- Browser -->
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100/90 border border-slate-200/70 rounded-lg text-xs font-semibold text-slate-700" data-toggle="tooltip" title="<?= htmlspecialchars($row->browser_name ?: l('global.unknown')) ?>">
                                            <img src="<?= ASSETS_FULL_URL . 'images/browsers/' . browser_name_to_browser_key($row->browser_name) . '.svg' ?>" class="w-3.5 h-3.5 object-contain" alt="" />
                                            <span><?= htmlspecialchars($row->browser_name ?: l('global.unknown')) ?></span>
                                        </span>
                                    </div>
                                </td>

                                <!-- Timestamp -->
                                <td class="px-6 py-4.5 whitespace-nowrap align-middle text-right sm:text-left">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-slate-900 inline-flex items-center gap-1.5 justify-end sm:justify-start" data-toggle="tooltip" title="<?= \Altum\Date::get($row->datetime, 1) ?>">
                                            <i class="far fa-clock text-slate-400 text-[11px]"></i>
                                            <span><?= \Altum\Date::get_timeago($row->datetime) ?></span>
                                        </span>
                                        <span class="text-[11px] text-slate-400 font-medium mt-0.5">
                                            <?= \Altum\Date::get($row->datetime, 2) ?>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pt-2"><?= $data->pagination ?></div>
    <?php else: ?>
        <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center shadow-xs">
            <div class="h-16 w-16 bg-slate-100 text-slate-400 rounded-3xl flex items-center justify-center mx-auto mb-4 text-2xl">
                <i class="fas fa-scroll"></i>
            </div>
            <h3 class="text-base font-extrabold text-slate-900 mb-1"><?= l('account_logs.logs.no_data') ?></h3>
            <p class="text-xs text-slate-500 font-medium max-w-sm mx-auto mb-5"><?= l('account_logs.logs.no_data_help') ?></p>
            <?php if($data->filters->has_applied_filters): ?>
                <a href="<?= url(\Altum\Router::$original_request) ?>" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition-all text-decoration-none">
                    <i class="fas fa-sync-alt text-xs"></i>
                    <span><?= l('global.filters.reset') ?></span>
                </a>
            <?php endif ?>
        </div>
    <?php endif ?>
</div>

<?php ob_start() ?>
<script>
    'use strict';
    document.addEventListener('DOMContentLoaded', () => {
        const exportBtn = document.getElementById('export_dropdown_btn');
        const exportMenu = document.getElementById('export_dropdown_menu');
        const filtersBtn = document.getElementById('filters_dropdown_btn');
        const filtersMenu = document.getElementById('filters_dropdown_menu');

        // Toggle Export Dropdown
        if (exportBtn && exportMenu) {
            exportBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                exportMenu.classList.toggle('hidden');
                if (filtersMenu) filtersMenu.classList.add('hidden');
            });
            exportMenu.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        }

        // Toggle Filters Dropdown
        if (filtersBtn && filtersMenu) {
            filtersBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                filtersMenu.classList.toggle('hidden');
                if (exportMenu) exportMenu.classList.add('hidden');
            });
            filtersMenu.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        }

        // Close on Click Outside
        document.addEventListener('click', () => {
            if (exportMenu) exportMenu.classList.add('hidden');
            if (filtersMenu) filtersMenu.classList.add('hidden');
        });
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
