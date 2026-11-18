<?php defined('ALTUMCODE') || die() ?>

<div id="audits">
    <button class="btn btn-block btn-gray-200 font-size-little-small font-weight-450 mb-4" type="button" data-toggle="collapse" data-target="#requests_container" aria-expanded="false" aria-controls="requests_container">
        <i class="fas fa-fw fa-circle-nodes fa-sm mr-1"></i> <?= l('admin_settings.audits.requests') ?>
    </button>

    <div class="collapse" data-parent="#audits" id="requests_container">
        <div class="form-group">
            <label for="accept_encoding"><?= l('admin_settings.audits.accept_encoding') ?></label>
            <input id="accept_encoding" type="text" name="accept_encoding" class="form-control" value="<?= settings()->audits->accept_encoding ?>" />
            <small class="form-text text-muted"><?= l('admin_settings.audits.accept_encoding_help') ?></small>
        </div>

        <div class="form-group">
            <label for="user_agent"><?= l('admin_settings.audits.user_agent') ?></label>
            <input id="user_agent" type="text" name="user_agent" class="form-control" value="<?= settings()->audits->user_agent ?>" />
            <small class="form-text text-muted"><?= l('admin_settings.audits.user_agent_help') ?></small>
        </div>

        <div class="form-group">
            <label for="request_timeout"><?= l('admin_settings.audits.request_timeout') ?></label>
            <div class="input-group">
                <input id="request_timeout" type="number" min="1" name="request_timeout" class="form-control" value="<?= settings()->audits->request_timeout ?>" />
                <div class="input-group-append">
                    <span class="input-group-text"><?= l('global.date.seconds') ?></span>
                </div>
            </div>
            <small class="form-text text-muted"><?= l('admin_settings.audits.request_timeout_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="double_check_is_enabled" name="double_check_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->audits->double_check_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="double_check_is_enabled"><?= l('admin_settings.audits.double_check_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.audits.double_check_is_enabled_help') ?></small>
        </div>

        <div class="form-group">
            <label for="double_check_wait"><?= l('admin_settings.audits.double_check_wait') ?></label>
            <div class="input-group">
                <input id="double_check_wait" type="number" min="0" max="5" name="double_check_wait" class="form-control" value="<?= settings()->audits->double_check_wait ?>" />
                <div class="input-group-append">
                    <span class="input-group-text"><?= l('global.date.seconds') ?></span>
                </div>
            </div>
            <small class="form-text text-muted"><?= l('admin_settings.audits.double_check_wait_help') ?></small>
        </div>

        <div class="form-group">
            <label for="blacklisted_domains"><?= l('admin_settings.audits.blacklisted_domains') ?></label>
            <textarea id="blacklisted_domains" class="form-control" name="blacklisted_domains"><?= implode(',', settings()->audits->blacklisted_domains) ?></textarea>
            <small class="form-text text-muted"><?= l('admin_settings.audits.blacklisted_domains_help') ?></small>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 font-size-little-small font-weight-450 mb-4" type="button" data-toggle="collapse" data-target="#domains_container" aria-expanded="false" aria-controls="domains_container">
        <i class="fas fa-fw fa-globe fa-sm mr-1"></i> <?= l('admin_settings.audits.domains') ?>
    </button>

    <div class="collapse" data-parent="#audits" id="domains_container">
        <div class="form-group custom-control custom-switch">
            <input id="domains_is_enabled" name="domains_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->audits->domains_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="domains_is_enabled"><?= l('admin_settings.audits.domains_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.audits.domains_is_enabled_help') ?></small>
        </div>

        <div class="form-group">
            <label for="domains_custom_main_ip"><?= l('admin_settings.audits.domains_custom_main_ip') ?></label>
            <input id="domains_custom_main_ip" name="domains_custom_main_ip" type="text" class="form-control" value="<?= settings()->audits->domains_custom_main_ip ?>" placeholder="<?= $_SERVER['SERVER_ADDR'] ?>">
            <small class="form-text text-muted"><?= l('admin_settings.audits.domains_custom_main_ip_help') ?></small>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 font-size-little-small font-weight-450 mb-4" type="button" data-toggle="collapse" data-target="#audits_container" aria-expanded="false" aria-controls="audits_container">
        <i class="fas fa-fw fa-bolt fa-sm mr-1"></i> <?= l('admin_settings.audits.audits') ?>
    </button>

    <div class="collapse" data-parent="#audits" id="audits_container">
        <div class="form-group custom-control custom-switch">
            <input id="ip_limit_is_enabled" name="ip_limit_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->audits->ip_limit_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="ip_limit_is_enabled">IP Limit restriction</label>
            <small class="form-text text-muted">Enable this to restrict guests to a specific number of audits per IP address.</small>
        </div>

        <div class="form-group">
            <label for="excluded_words"><?= l('admin_settings.audits.excluded_words') ?></label>
            <textarea id="excluded_words" class="form-control" name="excluded_words"><?= implode(',', settings()->audits->excluded_words ?? []) ?></textarea>
            <small class="form-text text-muted"><?= l('admin_settings.audits.excluded_words_help') ?></small>
        </div>

        <div class="form-group mt-5">
            <?php $available_tests = require APP_PATH . 'includes/available_audit_tests.php'; ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="h5"><?= l('admin_settings.audits.available_tests') . ' (' . count($available_tests) . ')' ?></h3>

                <div>
                    <button type="button" class="btn btn-sm btn-light" data-toggle="tooltip" title="<?= l('global.select_all') ?>" data-tooltip-hide-on-click onclick="document.querySelectorAll(`[name='available_tests[]']`).forEach(element => element.checked ? null : element.checked = true)"><i class="fas fa-fw fa-check-square"></i></button>
                    <button type="button" class="btn btn-sm btn-light" data-toggle="tooltip" title="<?= l('global.deselect_all') ?>" data-tooltip-hide-on-click onclick="document.querySelectorAll(`[name='available_tests[]']`).forEach(element => element.checked ? element.checked = false : null)"><i class="fas fa-fw fa-minus-square"></i></button>
                </div>
            </div>

            <div class="row">
                <?php foreach($available_tests as $key => $value): ?>
                    <div class="col-12 col-lg-6">
                        <div class="custom-control custom-checkbox my-2">
                            <input id="<?= 'test_' . $key ?>" name="available_tests[]" value="<?= $key ?>" type="checkbox" class="custom-control-input" <?= settings()->audits->available_tests->{$key} ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label d-flex align-items-center" for="<?= 'test_' . $key ?>">
                                <?= l('audits.test.' . $key) ?>
                            </label>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>

        <div class="form-group">
            <label for="example_url"><?= l('admin_settings.audits.example_url') ?></label>
            <input id="example_url" type="url" name="example_url" class="form-control" placeholder="<?= l('global.url_placeholder') ?>" value="<?= settings()->audits->example_url ?>" />
            <small class="form-text text-muted"><?= l('admin_settings.audits.example_url_help') ?></small>
        </div>

        <div class="form-group">
            <label for="google_safe_browsing_api_key"><?= l('admin_settings.audits.google_safe_browsing_api_key') ?></label>
            <input id="google_safe_browsing_api_key" type="text" name="google_safe_browsing_api_key" class="form-control" value="<?= settings()->audits->google_safe_browsing_api_key ?>" />
            <small class="form-text text-muted"><?= l('admin_settings.audits.google_safe_browsing_api_key_help') ?></small>
        </div>

        <div class="form-group">
            <label for="google_pagespeed_api_key"><?= l('admin_settings.audits.google_pagespeed_api_key') ?></label>
            <input id="google_pagespeed_api_key" type="text" name="google_pagespeed_api_key" class="form-control" value="<?= settings()->audits->google_pagespeed_api_key ?>" />
            <small class="form-text text-muted"><?= l('admin_settings.audits.google_pagespeed_api_key_help') ?></small>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 font-size-little-small font-weight-450 mb-4" type="button" data-toggle="collapse" data-target="#ai_container" aria-expanded="false" aria-controls="ai_container">
        <i class="fas fa-fw fa-robot fa-sm mr-1"></i> <?= l('admin_settings.audits.ai') ?>
    </button>

    <div class="collapse" data-parent="#audits" id="ai_container">
        <div class="form-group custom-control custom-switch">
            <input id="ai_is_enabled" name="ai_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->audits->ai_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="ai_is_enabled"><?= l('admin_settings.audits.ai_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.audits.ai_is_enabled_help') ?></small>
        </div>

        <div class="form-group">
            <label for="openai_api_key"><i class="fas fa-fw fa-sm fa-robot text-muted mr-1"></i> <?= l('admin_settings.audits.openai_api_key') ?></label>
            <input id="openai_api_key" type="text" name="openai_api_key" class="form-control" value="<?= settings()->audits->openai_api_key ?>" />
            <small class="form-text text-muted"><?= l('admin_settings.audits.openai_api_key_help') ?></small>
        </div>

        <div class="form-group">
            <label for="openai_model"><i class="fas fa-fw fa-sm fa-robot text-muted mr-1"></i> <?= l('admin_settings.audits.openai_model') ?></label>
            <select id="openai_model" name="openai_model" class="custom-select">
                <?php foreach(['gpt-5','gpt-5-mini','gpt-5-nano','gpt-4o','gpt-4','gpt-4.1','gpt-4.1-mini','gpt-3.5-turbo'] as $model): ?>
                    <option value="<?= $model ?>" <?= settings()->audits->openai_model == $model ? 'selected="selected"' : null ?>><?= $model ?></option>
                <?php endforeach ?>
            </select>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 font-size-little-small font-weight-450 mb-4" type="button" data-toggle="collapse" data-target="#report_content_container" aria-expanded="false" aria-controls="report_content_container">
        <i class="fas fa-fw fa-file-lines fa-sm mr-1"></i> Report Custom Content
    </button>

    <div class="collapse" data-parent="#audits" id="report_content_container">
        <div class="form-group">
            <label for="report_custom_content">Report Bottom Content</label>
            <textarea id="report_custom_content" name="report_custom_content" class="form-control"><?= settings()->audits->report_custom_content ?></textarea>
            <small class="form-text text-muted">This content will be displayed at the bottom of every audit report page. Useful for disclaimers, support info, or upsells.</small>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 font-size-little-small font-weight-450 mb-4" type="button" data-toggle="collapse" data-target="#cron_settings_container" aria-expanded="false" aria-controls="cron_settings_container">
        <i class="fas fa-fw fa-arrows-rotate fa-sm mr-1"></i> <?= l('admin_settings.cron.cron_settings') ?>
    </button>

    <div class="collapse" data-parent="#audits" id="cron_settings_container">
        <div class="alert alert-danger mb-3"><?= l('admin_settings.cron.cron_settings_help') ?></div>

        <div class="form-group">
            <label for="audits_per_cron"><?= l('admin_settings.audits.audits_per_cron') ?></label>
            <input id="audits_per_cron" type="number" min="0" name="audits_per_cron" class="form-control" value="<?= settings()->audits->audits_per_cron ?? 30 ?>" />
        </div>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
