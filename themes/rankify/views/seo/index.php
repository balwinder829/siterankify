<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="text-center">
        <h1 class="index-header mb-2"><?= l('seo.header') ?></h1>
        <p class="index-subheader mb-5"><?= l('seo.subheader') ?></p>
    </div>

    <div class="row">
        <div class="col col-lg-8 offset-lg-2" id="audit-container">
            <form id="audit_create_form" action="<?= url('audit-create') ?>" method="post" role="form" data-show-progress>
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get('token') ?>" />

                <div class="notification-container"></div>

                <div class="row">
                    <div class="col-12 col-lg mb-3 mb-lg-0">
                        <input id="url" type="text" name="url" class="form-control <?= \Altum\Alerts::has_field_errors('url') ? 'is-invalid' : null ?>" placeholder="<?= l('global.url_placeholder') ?>" aria-label="<?= l('global.url') ?>" maxlength="2048" required="required" data-url-autofocus>
                        <?= \Altum\Alerts::output_field_error('url') ?>
                    </div>

                    <div class="col-12 col-lg-auto">
                        <div class="row no-gutters">
                            <div class="col-auto mr-1">
                                <button type="button" class="btn btn-block btn-outline-primary" data-tooltip title="<?= l('audits.settings') ?>" data-toggle="collapse" data-target="#audit_settings" aria-expanded="false" aria-controls="audit_settings">
                                    <i class="fas fa-fw fa-sm fa-wrench"></i>
                                </button>
                            </div>

                            <div class="col">
                                <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax>
                                    <i class="fas fa-fw fa-sm fa-bolt mr-1"></i>
                                    <?= l('audits.submit') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="collapse mt-4" id="audit_settings">
                    <div>
                        <div class="form-group">
                            <label for="type"><i class="fas fa-fw fa-sm fa-fingerprint text-muted mr-1"></i> <?= l('global.type') ?></label>
                            <div class="row btn-group-toggle m-n2" data-toggle="buttons">
                                <div class="p-2 col-12 col-lg-6">
                                    <label class="btn btn-light btn-block font-size-small mb-0 text-truncate active">
                                        <input type="radio" name="type" value="single" class="custom-control-input" checked="checked" required="required" />
                                        <i class="fas fa-link fa-fw fa-sm mr-1"></i> <?= l('audits.input.type.single') ?>
                                    </label>
                                </div>

                                <div class="p-2 col-12 col-lg-6">
                                    <label class="btn btn-light btn-block font-size-small mb-0 text-truncate">
                                        <input type="radio" name="type" value="sitemap" class="custom-control-input" required="required" />
                                        <i class="fas fa-network-wired fa-fw fa-sm mr-1"></i> <?= l('audits.input.type.sitemap') ?>
                                    </label>
                                </div>

                                <div class="p-2 col-12 col-lg-6">
                                    <label class="btn btn-light btn-block font-size-small mb-0 text-truncate">
                                        <input type="radio" name="type" value="bulk" class="custom-control-input" required="required" />
                                        <i class="fas fa-layer-group fa-fw fa-sm mr-1"></i> <?= l('audits.input.type.bulk') ?>
                                    </label>
                                </div>

                                <div class="p-2 col-12 col-lg-6">
                                    <label class="btn btn-light btn-block font-size-small mb-0 text-truncate">
                                        <input type="radio" name="type" value="html" class="custom-control-input" required="required" />
                                        <i class="fas fa-code fa-fw fa-sm mr-1"></i> <?= l('audits.input.type.html') ?>
                                    </label>
                                </div>
                            </div>
                            <small id="type_single_help" data-type="single" class="form-text text-muted"><?= l('audits.input.type.single_help') ?></small>
                            <small id="type_sitemap_help" data-type="sitemap" class="form-text text-muted"><?= l('audits.input.type.sitemap_help') ?></small>
                            <small id="type_bulk_help" data-type="bulk" class="form-text text-muted"><?= l('audits.input.type.bulk_help') ?></small>
                        </div>

                        <div class="form-group custom-control custom-switch" data-tracking-type="normal">
                            <input
                                    type="checkbox"
                                    class="custom-control-input"
                                    name="is_public"
                                    id="is_public"
                                    checked="checked"
                            >
                            <label class="custom-control-label" for="is_public"><i class="fas fa-fw fa-sm fa-eye text-muted mr-1"></i> <?= l('audits.input.is_public') ?></label>
                            <small class="form-text text-muted"><?= l('audits.input.is_public_help') ?></small>
                        </div>

                        <div class="form-group" data-password-toggle-view data-password-toggle-view-show="<?= l('global.show') ?>" data-password-toggle-view-hide="<?= l('global.hide') ?>" data-is-public="on">
                            <label for="password"><i class="fas fa-fw fa-sm fa-lock text-muted mr-1"></i> <?= l('global.password') ?></label>
                            <input id="password" type="password" class="form-control" name="password" value="" />
                            <small class="form-text text-muted"><?= l('audits.input.password_help') ?></small>
                        </div>
                    </div>
                </div>

                        <?php if(count($data->domains ?? []) && settings()->audits->domains_is_enabled): ?>
                            <div class="form-group mt-4">
                                <label for="domain_id"><i class="fas fa-fw fa-sm fa-globe text-muted mr-1"></i> <?= l('audits.input.domain_id') ?></label>
                                <select id="domain_id" name="domain_id" class="custom-select">
                                    <option value="" selected="selected"><?= parse_url(SITE_URL, PHP_URL_HOST) ?></option>
                                    <?php foreach($data->domains ?? [] as $row): ?>
                                        <option value="<?= $row->domain_id ?>"><?= $row->host ?></option>
                                    <?php endforeach ?>
                                </select>
                                <small class="form-text text-muted"><?= l('audits.input.domain_id_help') ?></small>
                            </div>
                        <?php endif ?>

                        <div class="form-group">
                            <label for="audit_check_interval"><i class="fas fa-fw fa-sm fa-sync text-muted mr-1"></i> <?= l('audits.input.audit_check_interval') ?></label>
                            <select id="audit_check_interval" name="audit_check_interval" class="custom-select">
                                <option value="" selected="selected"><?= l('global.none') ?></option>
                                <?php foreach(require APP_PATH . 'includes/audits_check_intervals.php' as $key => $value): ?>
                                    <option value="<?= $key ?>" <?= !in_array($key, $this->user->plan_settings->audits_check_intervals ?? []) ? 'disabled="disabled"' : null ?>><?= $value ?></option>
                                <?php endforeach ?>
                            </select>
                            <small class="form-text text-muted"><?= l('audits.input.audit_check_interval_help') ?></small>
                        </div>

                        <div data-audit-check-interval>
                            <div class="form-group">
                                <div class="d-flex flex-wrap flex-row justify-content-between">
                                    <label><i class="fas fa-fw fa-sm fa-bell text-muted mr-1"></i> <?= l('audits.input.notification_handlers') ?></label>
                                    <a href="<?= url('notification-handler-create') ?>" target="_blank" class="small mb-2"><i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('notification_handlers.create') ?></a>
                                </div>
                                <div class="mb-2"><small class="form-text text-muted"><?= l('audits.input.notification_handlers_help') ?></small></div>

                                <div class="row">
                                    <?php foreach($data->notification_handlers ?? [] as $notification_handler): ?>
                                        <div class="col-12">
                                            <div class="custom-control custom-checkbox my-2">
                                                <input id="notifications_<?= $notification_handler->notification_handler_id ?>" name="notifications[]" value="<?= $notification_handler->notification_handler_id ?>" type="checkbox" class="custom-control-input">
                                                <label class="custom-control-label" for="notifications_<?= $notification_handler->notification_handler_id ?>">
                                                    <span class="mr-1"><?= $notification_handler->name ?></span>
                                                    <small class="badge badge-light badge-pill"><?= l('notification_handlers.type_' . $notification_handler->type) ?></small>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <?php if(l('seo.extra_content')): ?>
                <div class="mt-5">
                    <div class="card">
                        <div class="card-body">
                            <?= l('seo.extra_content') ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';

/* Handle URL input */
    document.querySelector('[data-url-autofocus]').focus();

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
            
            // Create Full-Screen Overlay
            const overlay = document.createElement('div');
            overlay.id = 'audit-progress-overlay';
            overlay.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(15, 23, 42, 0.4);
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
                backdrop-filter: blur(12px) saturate(180%);
                -webkit-backdrop-filter: blur(12px) saturate(180%);
                animation: fadeIn 0.4s ease-out;
            `;

            overlay.innerHTML = `
                <div class="card border-0 shadow-2xl p-4 p-md-5 text-center altum-animate altum-animate-zoom-in" style="width: 90%; max-width: 500px; border-radius: 2rem; background: #ffffff;">
                    <div class="mb-4">
                        <div class="spinner-grow text-primary" role="status" style="width: 4rem; height: 4rem;"></div>
                    </div>
                    <h2 class="h3 font-weight-bold text-dark mb-2">Deep Website Audit</h2>
                    <p class="text-muted mb-4">Please wait while we perform a comprehensive SEO analysis of your site.</p>
                    
                    <div class="position-relative mb-4">
                        <div class="progress shadow-sm" style="height: 35px; border-radius: 20px; background: #f0f2f5; overflow: hidden;">
                            <div class="progress-bar progress-bar-animated progress-bar-striped bg-primary" role="progressbar" style="width: 0%; transition: width 0.4s cubic-bezier(0.1, 0.7, 0.1, 1);" data-progress-bar></div>
                        </div>
                        <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center" style="top: 0; left: 0;">
                            <span class="font-weight-bold text-dark" style="font-size: 1.1rem;" data-progress-percentage>0%</span>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center justify-content-center text-primary font-weight-bold mb-0">
                        <i class="fas fa-search-location mr-2 fa-spin"></i>
                        <span data-status-text>Initializing Lighthouse Analysis...</span>
                    </div>
                </div>
                <style>
                    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
                    .shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
                </style>
            `;
            document.body.appendChild(overlay);

            // Simulation logic
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

                // Update status text based on percentage
                const currentStatus = statuses.reverse().find(s => rounded >= s.p);
                // Reverse is required because find returns first match (so we check largest percentage first)
                // Let's reset the array state
                statuses.reverse(); 
                const correctStatus = statuses.slice().reverse().find(s => rounded >= s.p);
                if (correctStatus) {
                    status_text.innerHTML = `<i class="fas fa-search-location mr-2 fa-spin"></i> ${correctStatus.t}`;
                }
            }, 600);

            // AJAX Submission
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
                    throw new Error('Analysis completed but server result was malformed. Server said: ' + text.substring(0, 100));
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

    let audit_check_interval_process = () => {
        let audit_check_interval = document.querySelector('select[name="audit_check_interval"]').value;
        if(audit_check_interval) {
            document.querySelector('[data-audit-check-interval]').classList.remove('d-none');
        } else {
            document.querySelector('[data-audit-check-interval]').classList.add('d-none');
        }
    }
    document.querySelector('select[name="audit_check_interval"]').addEventListener('change', audit_check_interval_process);
    audit_check_interval_process();
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php ob_start() ?>
<script>
    'use strict';

let active_notification_handlers_per_resource_limit = <?= (int) $this->user->plan_settings->active_notification_handlers_per_resource_limit ?>;

    if(active_notification_handlers_per_resource_limit != -1) {
        let process_notification_handlers = () => {
            let selected = document.querySelectorAll('[name="notifications[]"]:checked').length;

            if(selected >= active_notification_handlers_per_resource_limit) {
                document.querySelectorAll('[name="notifications[]"]:not(:checked)').forEach(element => element.setAttribute('disabled', 'disabled'));
            } else {
                document.querySelectorAll('[name="notifications[]"]:not(:checked)').forEach(element => element.removeAttribute('disabled'));
            }
        }

        document.querySelectorAll('[name="notifications[]"]').forEach(element => element.addEventListener('change', process_notification_handlers));

        process_notification_handlers();
    }
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>



