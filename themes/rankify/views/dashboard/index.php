<?php defined('ALTUMCODE') || die() ?>

<div class="space-y-5 font-sans">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div>
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-base border border-blue-100">
                    <i class="fas fa-th-large"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-slate-900 tracking-tight m-0">
                        <?= l('dashboard.header') ?>
                    </h1>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider <?= $this->user->plan_id == 'free' ? 'bg-slate-100 text-slate-600 border border-slate-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' ?>">
                            <i class="fas fa-box text-[9px]"></i> <?= $this->user->plan->name ?>
                        </span>
                        <?php if($this->user->plan_id != 'free'): ?>
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200">
                                <i class="fas fa-star text-amber-500 text-[9px]"></i> Lifetime Access
                            </span>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <a href="<?= url('plan') ?>" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition-all inline-flex items-center gap-2 text-decoration-none">
                <i class="fas fa-crown text-amber-300"></i> <?= l('plan.menu') ?>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs">
        <div id="audit-container">
            <form id="audit_create_form" action="<?= url('audit-create') ?>" method="post" role="form" data-show-progress>
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get('token') ?>" />
                <div class="notification-container"></div>

                <div class="flex flex-col lg:flex-row items-stretch gap-2.5">
                    <div class="flex-1 space-y-2">
                        <div data-type="single" <?= (session_get('audit_form')['type'] ?? 'single') != 'single' ? 'class="hidden"' : null ?>>
                            <input id="url" type="text" name="url" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/10 transition-all font-medium <?= \Altum\Alerts::has_field_errors('url') ? 'border-rose-500' : null ?>" value="<?= session_get('audit_form')['url'] ?? null ?>" placeholder="<?= l('global.url_placeholder') ?>" aria-label="<?= l('global.url') ?>" maxlength="2048" required="required" data-url-autofocus />
                        </div>

                        <div data-type="sitemap" <?= (session_get('audit_form')['type'] ?? 'single') != 'sitemap' ? 'class="hidden"' : null ?>>
                            <input id="url" type="text" name="url" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/10 transition-all font-medium <?= \Altum\Alerts::has_field_errors('url') ? 'border-rose-500' : null ?>" value="<?= session_get('audit_form')['url'] ?? null ?>" placeholder="<?= l('audits.input.sitemap_url_placeholder') ?>" aria-label="<?= l('global.url') ?>" maxlength="2048" required="required" />
                        </div>

                        <div data-type="bulk" class="hidden" <?= (session_get('audit_form')['type'] ?? 'single') != 'bulk' ? 'class="hidden"' : null ?>>
                            <textarea id="urls" type="urls" name="urls" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/10 transition-all font-medium <?= \Altum\Alerts::has_field_errors('url') ? 'border-rose-500' : null ?>" placeholder="<?= l('audits.input.bulk_url_placeholder1') . "\n" . l('audits.input.bulk_url_placeholder2') ?>" aria-label="<?= l('global.url') ?>" required="required"><?= session_get('audit_form')['urls'] ?? null ?></textarea>
                            <span class="text-xs text-slate-500 mt-1 block font-medium"><?= l('audits.input.bulk_url_help') ?></span>
                        </div>

                        <div data-type="html" class="hidden" <?= (session_get('audit_form')['type'] ?? 'single') != 'html' ? 'class="hidden"' : null ?>>
                            <input id="url" type="text" name="url" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/10 transition-all font-medium <?= \Altum\Alerts::has_field_errors('url') ? 'border-rose-500' : null ?>" value="<?= session_get('audit_form')['url'] ?? null ?>" placeholder="<?= l('global.url_placeholder') ?>" aria-label="<?= l('global.url') ?>" maxlength="2048" required="required" />
                            <span class="text-xs text-slate-500 mt-1 block font-medium"><?= l('audits.input.html_url_help') ?></span>
                        </div>

                        <div data-type="html" class="hidden" <?= (session_get('audit_form')['type'] ?? 'html') != 'sitemap' ? 'class="hidden"' : null ?>>
                            <textarea id="html" type="html" name="html" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/10 transition-all font-medium <?= \Altum\Alerts::has_field_errors('url') ? 'border-rose-500' : null ?>" placeholder="<?= l('audits.input.html_placeholder') ?>" aria-label="<?= l('audits.input.html') ?>" required="required"><?= session_get('audit_form')['html'] ?? null ?></textarea>
                            <span class="text-xs text-slate-500 mt-1 block font-medium"><?= l('audits.input.html_help') ?></span>
                        </div>
                        <?= \Altum\Alerts::output_field_error('url') ?>
                    </div>

                    <button type="submit" name="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition-all flex items-center justify-center gap-2 whitespace-nowrap cursor-pointer" data-is-ajax>
                        <i class="fas fa-bolt text-amber-300"></i>
                        <span><?= l('audits.submit') ?></span>
                    </button>
                </div>

                <!-- Settings Collapsible Panel -->
                <div class="mt-4 pt-4 border-t border-slate-100" id="audit_settings">
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Audit Mode Type</label>
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-2">
                                <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 bg-slate-50 hover:bg-white cursor-pointer transition-all">
                                    <input type="radio" name="type" value="single" class="h-4 w-4 text-blue-600 focus:ring-blue-500" <?= (session_get('audit_form')['type'] ?? 'single') == 'single' ? 'checked="checked"' : null?> required="required" />
                                    <span class="text-xs font-bold text-slate-800"><i class="fas fa-link text-blue-500 mr-1"></i> Single Page</span>
                                </label>

                                <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 bg-slate-50 hover:bg-white cursor-pointer transition-all <?= $this->user->plan_id == 'free' ? 'opacity-50 cursor-not-allowed' : null ?>">
                                    <input type="radio" name="type" value="sitemap" class="h-4 w-4 text-blue-600 focus:ring-blue-500" <?= (session_get('audit_form')['type'] ?? 'single') == 'sitemap' ? 'checked="checked"' : null?> <?= $this->user->plan_id == 'free' ? 'disabled' : null ?> required="required" />
                                    <span class="text-xs font-bold text-slate-800"><i class="fas fa-sitemap text-indigo-500 mr-1"></i> Sitemap Audit</span>
                                </label>

                                <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 bg-slate-50 hover:bg-white cursor-pointer transition-all <?= $this->user->plan_id == 'free' ? 'opacity-50 cursor-not-allowed' : null ?>">
                                    <input type="radio" name="type" value="bulk" class="h-4 w-4 text-blue-600 focus:ring-blue-500" <?= (session_get('audit_form')['type'] ?? 'single') == 'bulk' ? 'checked="checked"' : null?> <?= $this->user->plan_id == 'free' ? 'disabled' : null ?> required="required" />
                                    <span class="text-xs font-bold text-slate-800"><i class="fas fa-layer-group text-amber-500 mr-1"></i> Bulk URLs</span>
                                </label>

                                <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 bg-slate-50 hover:bg-white cursor-pointer transition-all <?= $this->user->plan_id == 'free' ? 'opacity-50 cursor-not-allowed' : null ?>">
                                    <input type="radio" name="type" value="html" class="h-4 w-4 text-blue-600 focus:ring-blue-500" <?= (session_get('audit_form')['type'] ?? 'single') == 'html' ? 'checked="checked"' : null?> <?= $this->user->plan_id == 'free' ? 'disabled' : null ?> required="required" />
                                    <span class="text-xs font-bold text-slate-800"><i class="fas fa-code text-rose-500 mr-1"></i> Direct HTML</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 pt-2">
                            <input type="checkbox" name="is_public" id="is_public" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500" <?= (session_get('audit_form')['is_public'] ?? false) ? 'checked="checked"' : null ?>>
                            <label for="is_public" class="text-xs font-bold text-slate-700 cursor-pointer m-0">Make Audit Publicly Accessible</label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Overview Usage Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Audits Performed</span>
                <div class="text-3xl font-extrabold text-slate-900"><?= nr($data->usage->audit_audits_current_month) ?></div>
                <div class="h-1.5 w-48 bg-slate-100 rounded-full overflow-hidden mt-2">
                    <div class="h-full bg-blue-600 rounded-full" style="width: <?= $this->user->plan_settings->audits_per_month_limit == 0 || $this->user->plan_settings->audits_per_month_limit == -1 ? 0 : ($data->usage->audit_audits_current_month / $this->user->plan_settings->audits_per_month_limit * 100) ?>%"></div>
                </div>
            </div>
            <a href="<?= url('audits') ?>" class="h-12 w-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl hover:scale-105 transition-transform">
                <i class="fas fa-bolt"></i>
            </a>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Tracked Websites</span>
                <div class="text-3xl font-extrabold text-slate-900"><?= nr($data->total_websites) ?></div>
                <div class="h-1.5 w-48 bg-slate-100 rounded-full overflow-hidden mt-2">
                    <div class="h-full bg-indigo-600 rounded-full" style="width: <?= $this->user->plan_settings->websites_limit == 0 || $this->user->plan_settings->websites_limit == -1 ? 0 : ($data->total_websites / $this->user->plan_settings->websites_limit * 100) ?>%"></div>
                </div>
            </div>
            <a href="<?= url('websites') ?>" class="h-12 w-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl hover:scale-105 transition-transform">
                <i class="fas fa-pager"></i>
            </a>
        </div>
    </div>

    <!-- Recent Audits Section -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-900 font-sans tracking-tight">Recent Audits</h2>
            <a href="<?= url('audits') ?>" class="text-xs font-bold text-blue-600 hover:underline">View All Audits &rarr;</a>
        </div>

        <?php if (!empty($data->audits)): ?>
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="overflow-x-auto w-full">
                    <table class="sr-table min-w-[550px] w-full">
                        <thead>
                            <tr>
                                <th>Website</th>
                                <th>SEO Health</th>
                                <th>Total Issues</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data->audits as $row): ?>
                                <tr>
                                    <td>
                                        <a href="<?= url('audit/' . $row->audit_id) ?>" class="font-bold text-slate-900 hover:text-blue-600 text-decoration-none block">
                                            <?= string_truncate(remove_url_protocol_from_url($row->url), 32) ?>
                                        </a>
                                        <span class="text-xs text-slate-400 font-medium"><?= $row->host ?></span>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <span class="font-extrabold text-xs text-slate-800"><?= nr($row->score) ?>%</span>
                                            <div class="h-2 w-20 sm:w-24 bg-slate-100 rounded-full overflow-hidden">
                                                <div class="h-full rounded-full <?= $row->score >= 80 ? 'bg-emerald-500' : ($row->score >= 50 ? 'bg-amber-500' : 'bg-rose-500') ?>" style="width: <?= $row->score ?>%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="sr-badge <?= $row->major_issues > 0 ? 'sr-badge-danger' : ($row->moderate_issues > 0 ? 'sr-badge-warning' : 'sr-badge-success') ?>">
                                            <?= sprintf(l('audits.total_issues_x'), nr($row->total_issues)) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= url('audit/' . $row->audit_id) ?>" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all text-decoration-none inline-flex items-center gap-1">
                                            <span>View</span>
                                            <i class="fas fa-arrow-right text-[10px]"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php else: ?>
            <div class="bg-white rounded-3xl border border-slate-200/80 p-8 text-center space-y-2">
                <i class="fas fa-bolt text-3xl text-slate-300"></i>
                <h3 class="text-sm font-bold text-slate-700">No Audits Run Yet</h3>
                <p class="text-xs text-slate-400">Enter a website URL above to start your first SEO audit.</p>
            </div>
        <?php endif ?>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';
    document.querySelector('[data-url-autofocus]') && document.querySelector('[data-url-autofocus]').focus();

    document.querySelector('#audit_create_form').addEventListener('submit', event => {
        event.currentTarget.querySelectorAll('input[name="url"]').forEach(url_input => {
            const lowercase_input = url_input.value.toLowerCase();
            url_input.value = lowercase_input.startsWith('http://') || lowercase_input.startsWith('https://')
                ? url_input.value
                : `https://${url_input.value}`;
        });

        if(document.querySelector('#audit_create_form').checkValidity()) {
            event.preventDefault();
            pause_submit_button(event.currentTarget);
            
            const overlay = document.createElement('div');
            overlay.id = 'audit-progress-overlay';
            overlay.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(15, 23, 42, 0.6);
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                animation: fadeIn 0.3s ease-out;
            `;

            overlay.innerHTML = `
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-2xl p-6 sm:p-8 text-center max-w-md w-[90%] font-sans">
                    <div class="h-16 w-16 mx-auto mb-4 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-inner">
                        <i class="fas fa-bolt text-2xl animate-bounce text-amber-500"></i>
                    </div>
                    <h2 class="text-xl font-bold text-slate-900 mb-1">Deep Website Audit</h2>
                    <p class="text-xs text-slate-500 mb-6">Analyzing PageSpeed, meta metrics, security, & technical health...</p>
                    
                    <div class="relative mb-4">
                        <div class="h-4 w-full bg-slate-100 rounded-full overflow-hidden border border-slate-200/80">
                            <div class="h-full bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full transition-all duration-300" data-progress-bar style="width: 0%"></div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-xs font-bold text-slate-700">
                        <span data-status-text class="flex items-center gap-1.5 text-blue-600">
                            <i class="fas fa-spinner fa-spin"></i> Initializing Analysis...
                        </span>
                        <span data-progress-percentage class="text-slate-900 font-mono">0%</span>
                    </div>
                </div>
            `;
            document.body.appendChild(overlay);

            let percentage = 0;
            const percentage_element = overlay.querySelector('[data-progress-percentage]');
            const progress_bar = overlay.querySelector('[data-progress-bar]');
            const status_text = overlay.querySelector('[data-status-text]');

            const statuses = [
                { p: 10, t: "Fetching page source..." },
                { p: 25, t: "Analyzing DOM structure..." },
                { p: 45, t: "Evaluating Meta tags & SEO content..." },
                { p: 65, t: "Checking accessibility & performance..." },
                { p: 85, t: "Performing Google PageSpeed handshake..." },
                { p: 95, t: "Finalizing comprehensive report..." }
            ];

            const interval = setInterval(() => {
                let increment = 0;
                if(percentage < 40) increment = Math.random() * 5 + 2;
                else if(percentage < 75) increment = Math.random() * 2 + 0.5;
                else if(percentage < 95) increment = Math.random() * 0.5 + 0.1;
                else increment = 0.1;

                percentage += increment;
                if(percentage > 99) {
                    percentage = 99;
                    clearInterval(interval);
                }

                const rounded = Math.floor(percentage);
                percentage_element.innerText = `${rounded}%`;
                progress_bar.style.width = `${rounded}%`;

                const currentStatus = statuses.filter(s => rounded >= s.p).pop();
                if(currentStatus) status_text.innerText = currentStatus.t;
            }, 600);

            const formData = new FormData(event.currentTarget);
            formData.append('ajax', 1);

            fetch(event.currentTarget.action, {
                method: 'POST',
                body: formData
            })
            .then(async response => {
                const text = await response.text();
                let data = {};
                try {
                    data = JSON.parse(text);
                } catch(e) {
                    if(response.redirected) {
                        window.location.href = response.url;
                        return;
                    }
                    if (response.ok && response.url && !response.url.endsWith('/audit-create')) {
                        window.location.href = response.url;
                        return;
                    }
                    throw new Error('Analysis completed but server response was invalid.');
                }

                if (!response.ok || data.status === 'error') {
                    throw new Error(data.message || 'Server error');
                }

                if(data.url) {
                    window.location.href = data.url;
                }
            })
            .catch(error => {
                clearInterval(interval);
                if(document.body.contains(overlay)) {
                    document.body.removeChild(overlay);
                }
                alert('Audit Failed: ' + error.message);
            });
        }
    });

    type_handler('input[name="type"]', 'data-type');
    document.querySelector('input[name="type"]') && document.querySelectorAll('input[name="type"]').forEach(element => element.addEventListener('change', () => { type_handler('input[name="type"]', 'data-type'); }));

    type_handler('[name="is_public"]', 'data-is-public');
    document.querySelector('[name="is_public"]') && document.querySelectorAll('[name="is_public"]').forEach(element => element.addEventListener('change', () => { type_handler('[name="is_public"]', 'data-is-public'); }));
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
