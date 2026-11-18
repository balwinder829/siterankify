<?php defined('ALTUMCODE') || die();
$has_full_access = is_logged_in() && (user()->plan_id != 'free' || \Altum\Authentication::is_admin());

/* Compute download limit reached server-side (mirrors the JS logic) */
$download_limit = 1;
if(isset($this->user->plan_settings->pdf_downloads_limit)) {
    $download_limit = $this->user->plan_settings->pdf_downloads_limit;
} else if(is_logged_in()) {
    if(user()->plan_id == 'free') {
        $download_limit = 1;
    } else if(user()->plan_id == 1) {
        $download_limit = 1;
    } else if(user()->plan_id == 2) {
        $download_limit = 10;
    } else if(user()->plan_id == 3) {
        $download_limit = 120;
    } else {
        $download_limit = 10;
    }
}
if(is_logged_in() && $download_limit != -1) {
    $download_limit += (int) (user()->extra_pdf_downloads ?? 0);
}

$php_download_limit_reached = !\Altum\Authentication::is_admin() && (
    !is_logged_in() || (
        $download_limit != -1 && $data->download_count >= $download_limit
    )
);

/* Check if user has logo/custom branding permission by plan */
$php_has_logo_permission = is_logged_in() && (
    \Altum\Authentication::is_admin() ||
    user()->plan_id == 'free' ||
    in_array(user()->plan_id, [2, 3]) ||
    (user()->plan_id == 1 && !empty(user()->preferences->has_logo_addon))
);
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 font-sans">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(!$this->user->plan_settings->removable_branding_is_enabled): ?>
        <div class="d-none d-print-block mb-4">
            <div class="card">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-0 h5 font-weight-bold"><?= settings()->main->title ?></p>
                        <a href="<?= url() ?>" class="small"><?= remove_url_protocol_from_url(url()) ?></a>
                    </div>

                    <?php if(isset(settings()->main->report_logo_full_url)): ?>
                        <img src="<?= settings()->main->report_logo_full_url ?>" class="audit-logo ml-3" alt="<?= l('global.accessibility.logo_alt') ?>" />
                    <?php elseif(settings()->main->{'logo_' . \Altum\ThemeStyle::get()} != ''): ?>
                        <img src="<?= settings()->main->{'logo_' . \Altum\ThemeStyle::get() . '_full_url'} ?>" class="audit-logo ml-3" alt="<?= l('global.accessibility.logo_alt') ?>" />
                    <?php endif ?>
                </div>
            </div>
        </div>
    <?php endif ?>

    <div class="d-print-none">
        <?php if(settings()->main->breadcrumbs_is_enabled): ?>
            <?php if(is_logged_in()): ?>
                <nav aria-label="breadcrumb">
                    <ol class="custom-breadcrumbs small">
                        <li>
                            <a href="<?= url('audits') ?>"><?= l('archived_audits.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
                        </li>
                        <li class="active" aria-current="page"><?= l('archived_audit.breadcrumb') ?></li>
                    </ol>
                </nav>
            <?php endif ?>
        <?php endif ?>
    </div>

    <?php
    $audit_score_class_name = match (true) {
        $data->archived_audit->score >= 80 => 'success',
        $data->archived_audit->score >= 50 => 'warning',
        $data->archived_audit->score >= 0 => 'danger',
    };
    ?>

    <div class="position-relative mb-3 d-print-none">
        <div class="audit-archived-audits-wrapper-left"></div>
        <div class="audit-archived-audits-wrapper-right"></div>

        <div class="d-flex align-items-center audit-archived-audits-wrapper">
            <?php foreach($data->archived_audits_left as $archived_audit): ?>
                <?php
                $archived_audit_score_class_name = match (true) {
                    $archived_audit->score >= 80 => 'text-success',
                    $archived_audit->score >= 50 => 'text-warning',
                    $archived_audit->score >= 0 => 'text-danger',
                };
                ?>
                <div class="card p-3 text-center position-relative white-space-normal min-width-fit-content" data-toggle="tooltip" data-html="true" title="<?= \Altum\Date::get($archived_audit->datetime, 2) . '<br /><small>' . \Altum\Date::get($archived_audit->datetime, 3) . '</small>' ?>">
                    <a href="<?= url('archived-audit/' . $archived_audit->archived_audit_id) ?>" class="stretched-link font-weight-bold text-decoration-none <?= $archived_audit_score_class_name ?>">
                        <?= $archived_audit->score ?>
                    </a>
                    <div class="small text-muted"><?= \Altum\Date::get_timeago($archived_audit->datetime) ?></div>
                </div>

                <div>
                    <i class="fas fa-fw fa-sm fa-arrow-right text-gray-300 mx-3"></i>
                </div>
            <?php endforeach ?>

            <div id="current_audit" class="card p-3 text-center position-relative white-space-normal min-width-fit-content border-primary" data-toggle="tooltip" data-html="true" title="<?= \Altum\Date::get($data->archived_audit->datetime, 2) . '<br /><small>' . \Altum\Date::get($data->archived_audit->datetime, 3) . '</small>' ?>">
                <span href="<?= url('archived-audit/' . $data->archived_audit->audit_id) ?>" class="stretched-link font-weight-bold text-decoration-none <?= 'text-' . $audit_score_class_name ?>">
                    <?= $data->archived_audit->score ?>
                </span>
                <div class="small text-muted"><?= \Altum\Date::get_timeago($data->archived_audit->datetime) ?></div>
            </div>

            <?php $total_archived_audits_right = count($data->archived_audits_right); ?>

            <?php if($total_archived_audits_right): ?>
                <div>
                    <i class="fas fa-fw fa-sm fa-arrow-right text-gray-300 mx-3"></i>
                </div>
            <?php endif ?>

            <?php $i = 1; ?>
            <?php foreach($data->archived_audits_right as $archived_audit): ?>
                <?php
                $archived_audit_score_class_name = match (true) {
                    $archived_audit->score >= 80 => 'text-success',
                    $archived_audit->score >= 50 => 'text-warning',
                    $archived_audit->score >= 0 => 'text-danger',
                };
                ?>
                <div class="card p-3 text-center position-relative white-space-normal min-width-fit-content" data-toggle="tooltip" data-html="true" title="<?= \Altum\Date::get($archived_audit->datetime, 2) . '<br /><small>' . \Altum\Date::get($archived_audit->datetime, 3) . '</small>' ?>">
                    <a href="<?= url('archived-audit/' . $archived_audit->archived_audit_id) ?>" class="stretched-link font-weight-bold text-decoration-none <?= $archived_audit_score_class_name ?>">
                        <?= $archived_audit->score ?>
                    </a>
                    <div class="small text-muted"><?= \Altum\Date::get_timeago($archived_audit->datetime) ?></div>
                </div>

                <?php if($i++ != $total_archived_audits_right): ?>
                    <div>
                        <i class="fas fa-fw fa-sm fa-arrow-right text-gray-300 mx-3"></i>
                    </div>
                <?php endif ?>
            <?php endforeach ?>

            <div>
                <i class="fas fa-fw fa-sm fa-bolt text-gray-300 mx-3"></i>
            </div>

            <div class="card p-3 text-center position-relative white-space-normal min-width-fit-content border-primary-100 border0" data-toggle="tooltip" data-html="true" title="<?= \Altum\Date::get($data->audit->last_refresh_datetime, 2) . '<br /><small>' . \Altum\Date::get($data->audit->last_refresh_datetime, 3) . '</small>' ?>">
                <a href="<?= url('audit/' . $data->audit->audit_id) ?>" class="stretched-link font-weight-bold text-decoration-none <?= 'text-' . $audit_score_class_name ?>">
                    <?= $data->audit->score ?>
                </a>
                <div class="small text-muted"><?= \Altum\Date::get_timeago($data->audit->last_refresh_datetime) ?></div>
            </div>
        </div>
    </div>

    <?php ob_start() ?>
    <script>
    'use strict';

        const container = document.querySelector('.audit-archived-audits-wrapper');
        if(container) {
            const fade_left = document.querySelector('.audit-archived-audits-wrapper-left');
            const fade_right = document.querySelector('.audit-archived-audits-wrapper-right');

            const update_fades = () => {
                fade_left.style.opacity = container.scrollLeft ? 1 : 0;
                fade_right.style.opacity = (container.scrollLeft + container.clientWidth + 1 >= container.scrollWidth) ? 0 : 1;
            };

            container.addEventListener('scroll', update_fades);
            window.addEventListener('resize', update_fades);
            update_fades();

            document.querySelector('#current_audit').scrollIntoView({behavior: 'smooth', block: 'center', inline: 'start'});
        }
    </script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

    <?php
    $audit_score_circle_attributes = [
        'progress' => $data->archived_audit->score,
        'size' => 125,
        'circleColor' => 'var(--gray-200)',
        'progressColor' => 'var(--' . $audit_score_class_name . ')',
        'circleWidth' => '12px',
        'progressWidth' => '12px',
        'progressShape' => 'round',
        'textColor' => 'var(--' . $audit_score_class_name . ')',
        'textSize' => [
            'fontSize' => 30
        ],
        'valueToggle' => true,
        'percentageToggle' => false
    ];
    ?>

    <?php
    $major_issues_percentage = number_format($data->archived_audit->major_issues * 100 / $data->archived_audit->total_tests, '2', '.', '');
    $moderate_issues_percentage = number_format($data->archived_audit->moderate_issues * 100 / $data->archived_audit->total_tests, '2', '.', '');
    $minor_issues_percentage = number_format($data->archived_audit->minor_issues * 100 / $data->archived_audit->total_tests, '2', '.', '');
    $passed_tests = $data->archived_audit->total_tests - $data->archived_audit->major_issues - $data->archived_audit->moderate_issues - $data->archived_audit->minor_issues;
    $passed_tests_percentage = number_format($passed_tests * 100 / $data->archived_audit->total_tests, '2', '.', '');
    ?>

    <div class="card mb-4">
        <div class="d-flex flex-column flex-md-row">
            <div class="d-flex align-items-center justify-content-center">
                <div class="audit-score-circle">
                    <?= get_audit_score_circle($audit_score_circle_attributes) ?>
                </div>
            </div>

            <div class="card-body text-truncate d-flex justify-content-between align-items-center">
                <div class="text-truncate">
                    <div class="d-flex align-items-center">
                        <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain($data->archived_audit->host) ?>" class="img-fluid icon-favicon-small mr-1" loading="lazy" />

                        <a href="<?= url('website/' . $data->archived_audit->website_id) ?>" class="small text-muted text-truncate" data-toggle="tooltip" title="<?= $data->archived_audit->host ?>">
                            <?= string_truncate($data->archived_audit->host, 32) ?>
                        </a>
                    </div>

                    <h1 class="h4 text-truncate mb-2">
                        <span title="<?= $data->archived_audit->url ?>"><?= sprintf(l('audit.header'), remove_url_protocol_from_url($data->archived_audit->url)) ?></span>

                        <a href="<?= $data->archived_audit->url ?>" class="small" target="_blank" rel="noreferrer">
                            <i class="fas fa-fw fa-xs fa-external-link-alt text-muted ml-1"></i>
                        </a>
                    </h1>

                    <p class="small text-muted white-space-normal m-0">
                        <?= sprintf(l('audits.dynamic_description'), $data->archived_audit->score, $data->archived_audit->total_issues, $passed_tests, $data->archived_audit->total_tests) ?>
                    </p>

                    <p class="small text-muted white-space-normal m-0">
                        <?php
                        if($data->audits_stats->average_score > 0) {
                            $percentage_difference = round((($data->archived_audit->score - $data->audits_stats->average_score) / $data->audits_stats->average_score) * 100, 2);
                        } else {
                            $percentage_difference = 0;
                        }
                        ?>

                        <?php if($data->archived_audit->score >= $data->audits_stats->average_score): ?>
                            <?= sprintf(l('audits.dynamic_description2.above_average'),
                                $data->audits_stats->average_score .
                                ' (' . $percentage_difference . '%) ' .
                                '<i class="fas fa-fw fa-sm fa-arrow-up text-success"></i>',
                                $data->audits_stats->total_audits,
                                $data->audits_stats->total_tests)
                            ?>
                        <?php else: ?>
                            <?= sprintf(l('audits.dynamic_description2.below_average'),
                                $data->audits_stats->average_score .
                                ' (' . abs($percentage_difference) . '%) ' .
                                '<i class="fas fa-fw fa-sm fa-arrow-down text-danger"></i>',
                                $data->audits_stats->total_audits,
                                $data->audits_stats->total_tests)
                            ?>
                        <?php endif ?>
                    </p>
                </div>

                <div class="d-flex">
                    <button type="button" id="download-report-btn" class="btn btn-link text-secondary d-print-none <?= $this->user->plan_settings->export->pdf ? null : 'disabled' ?>" onclick="downloadReportTrigger();return false;" data-toggle="tooltip" title="<?= sprintf(l('global.export_to'), 'PDF') ?>" data-tooltip-hide-on-click>
                        <i class="fas fa-fw fa-file-pdf"></i>
                    </button>



                    <?php if((is_logged_in() && $this->user->user_id == $data->audit->user_id) || (md5(get_ip()) == $data->audit->uploader_id)): ?>
                    <?= include_view(THEME_PATH . 'views/audits/audit_dropdown_button.php', ['id' => $data->archived_audit->audit_id, 'resource_name' => remove_url_protocol_from_url($data->archived_audit->url), 'url' => $data->archived_audit->url]) ?>
                    <?php endif ?>
                </div>
            </div>
        </div>

        <div class="card-footer bg-white">
            <div class="row">
                <div class="col-6 col-md-3 col-lg-6 col-xl-3 mb-2 mb-md-0 mb-lg-2 mb-xl-0">
                    <div data-html="true" data-toggle="tooltip" title="<?= l('audits.ttfb') . ' (' . l('audits.ttfb_help') . ')<br /><small>' . l('audits.ttfb_help2') . '</small>' ?>">
                    <span class="badge badge-light text-body mr-1">
                        <i class="fas fa-fw fa-sm fa-server"></i>
                    </span>

                        <span class="small font-weight-bold"><?= display_response_time($data->archived_audit->ttfb) ?></span>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-6 col-xl-3 mb-2 mb-md-0 mb-lg-2 mb-xl-0">
                    <div data-html="true" data-toggle="tooltip" title="<?= l('audits.response_time') . '<br /><small>' . l('audits.response_time_help') . '</small>' ?>">
                        <span class="badge badge-light text-body mr-1">
                            <i class="fas fa-fw fa-sm fa-tachometer-alt"></i>
                        </span>

                        <span class="small font-weight-bold"><?= display_response_time($data->archived_audit->response_time) ?></span>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-6 col-xl-3">
                    <div data-html="true" data-toggle="tooltip" title="<?= l('audits.page_size') . '<br /><small>' . l('audits.page_size_help') . '</small>' ?>">
                        <span class="badge badge-light text-body mr-1">
                            <i class="fas fa-fw fa-sm fa-file"></i>
                        </span>

                        <span class="small font-weight-bold"><?= get_formatted_bytes($data->archived_audit->page_size) ?></span>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-6 col-xl-3">
                    <div data-html="true" data-toggle="tooltip" title="<?= l('audits.http_requests') . '<br /><small>' . l('audits.http_requests_help') . '</small>' ?>">
                        <span class="badge badge-light text-body mr-1">
                            <i class="fas fa-fw fa-sm fa-sitemap"></i>
                        </span>

                        <span class="small font-weight-bold"><?= sprintf(l('audits.http_requests_x'), nr($data->archived_audit->http_requests)) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer bg-white">
            <?php
            $score_bar_tooltip = '<div class=\'text-left\'>';
            $score_bar_tooltip .= '<div><i class=\'fas fa-fw fa-sm fa-exclamation-circle text-danger mr-1\'></i> ' . sprintf(l('audits.major_issues_x'), nr($data->archived_audit->major_issues)) . '</div>';
            $score_bar_tooltip .= '<div><i class=\'fas fa-fw fa-sm fa-exclamation-triangle text-warning mr-1\'></i> ' . sprintf(l('audits.moderate_issues_x'), nr($data->archived_audit->moderate_issues)) . '</div>';
            $score_bar_tooltip .= '<div><i class=\'fas fa-fw fa-sm fa-circle text-muted mr-1\'></i> ' . sprintf(l('audits.minor_issues_x'), nr($data->archived_audit->minor_issues)) . '</div>';
            $score_bar_tooltip .= '<div><i class=\'fas fa-fw fa-sm fa-check-circle text-success mr-1\'></i> ' . sprintf(l('audits.passed_tests_x'), nr($data->archived_audit->passed_tests)) . '</div>';
            $score_bar_tooltip .= '</div>';
            ?>

            <div class="d-flex flex-column flex-md-row flex-lg-column flex-xl-row audit-checks-bar-wrapper" data-toggle="tooltip" data-html="true" title="<?= $score_bar_tooltip ?>">
                <?php if($data->archived_audit->major_issues): ?>
                    <div class="audit-checks-bar-item bg-danger my-1 my-md-0 my-lg-1 my-xl-0" style="width: <?= $major_issues_percentage ?>%;"></div>
                <?php endif ?>

                <?php if($data->archived_audit->moderate_issues): ?>
                    <div class="audit-checks-bar-item bg-warning my-1 my-md-0 my-lg-1 my-xl-0" style="width: <?= $moderate_issues_percentage ?>%;"></div>
                <?php endif ?>

                <?php if($data->archived_audit->minor_issues): ?>
                    <div class="audit-checks-bar-item bg-gray-600 my-1 my-md-0 my-lg-1 my-xl-0" style="width: <?= $minor_issues_percentage ?>%;"></div>
                <?php endif ?>

                <?php if($data->archived_audit->passed_tests): ?>
                    <div class="audit-checks-bar-item bg-success my-1 my-md-0 my-lg-1 my-xl-0" style="width: <?= $passed_tests_percentage ?>%;"></div>
                <?php endif ?>
            </div>
        </div>
    </div>

    <?php if(settings()->audits->ai_is_enabled && $data->archived_audit->ai_summary): ?>
        <div class="card mb-4">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between">
                    <span class="small font-weight-bold"><?= l('audits.ai_summary') ?></span>

                    <span class="badge bg-primary-50 text-primary-700">
                        <i class="fas fa-fw fa-sm fa-wand-magic"></i>
                    </span>
                </div>
            </div>

            <div class="card-body">
                <?= $data->archived_audit->ai_summary ?>
            </div>
        </div>
    <?php endif ?>

    <div class="card mb-2">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-12 col-md-auto mb-3 mb-md-0 d-flex justify-content-center">
                    <img src="<?= $data->archived_audit->data->opengraph->{'og:image'} ?? ASSETS_FULL_URL . 'images/audit/opengraph-not-found.svg' ?>" class="audit-opengraph-image img-fluid rounded" data-toggle="tooltip" title="<?= l('audits.opengraph_image') ?>" loading="lazy" />
                </div>

                <div class="col text-center text-md-left">
                    <p class="h6"><?= $data->archived_audit->data->title ? e($data->archived_audit->data->title) : l('audits.title_missing') ?></p>

                    <p class="small m-0 text-muted"><?= $data->archived_audit->data->meta_description ? e($data->archived_audit->data->meta_description) : l('audits.meta_description_missing') ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-4 p-3 text-truncate">
            <div class="card d-flex flex-row h-100 overflow-hidden" data-toggle="tooltip" data-html="true" title="<?= l('audits.last_refresh_datetime') . ($data->audit->last_refresh_datetime ? '<br />' . \Altum\Date::get($data->audit->last_refresh_datetime, 2) . '<br /><small>' . \Altum\Date::get($data->audit->last_refresh_datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($data->audit->last_refresh_datetime) . ')</small>' : '<br />' . l('global.na')) ?>">
                <div class="px-3 d-flex flex-column justify-content-center">
                    <div class="p-2 rounded-2x card-widget-icon d-flex align-items-center justify-content-center bg-gray-50">
                        <i class="fas fa-fw fa-sm fa-calendar-check text-muted"></i>
                    </div>
                </div>

                <div class="card-body text-truncate">
                    <?= $data->audit->last_refresh_datetime ? \Altum\Date::get_timeago($data->audit->last_refresh_datetime) : l('global.na') ?>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4 p-3 text-truncate">
            <div class="card d-flex flex-row h-100 overflow-hidden" data-toggle="tooltip" data-html="true" title="<?= l('audits.next_refresh_datetime') . ($data->audit->next_refresh_datetime ? '<br />' . \Altum\Date::get($data->audit->next_refresh_datetime, 2) . '<br /><small>' . \Altum\Date::get($data->audit->next_refresh_datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($data->audit->next_refresh_datetime) . ')</small>' : '<br />') ?>">
                <div class="px-3 d-flex flex-column justify-content-center">
                    <div class="p-2 rounded-2x card-widget-icon d-flex align-items-center justify-content-center bg-gray-50">
                        <i class="fas fa-fw fa-sm fa-retweet text-muted"></i>
                    </div>
                </div>

                <div class="card-body text-truncate">
                    <?= $data->audit->next_refresh_datetime ? \Altum\Date::get_timeago($data->audit->next_refresh_datetime) : l('global.none') ?>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4 p-3 text-truncate">
            <div class="card d-flex flex-row h-100 overflow-hidden" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), '<br />' . \Altum\Date::get($data->archived_audit->datetime, 2) . '<br /><small>' . \Altum\Date::get($data->archived_audit->datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($data->archived_audit->datetime) . ')</small>') ?>">
                <div class="px-3 d-flex flex-column justify-content-center">
                    <div class="p-2 rounded-2x card-widget-icon d-flex align-items-center justify-content-center bg-gray-50">
                        <i class="fas fa-fw fa-sm fa-calendar text-muted"></i>
                    </div>
                </div>

                <div class="card-body text-truncate">
                    <?= $data->archived_audit->datetime ? \Altum\Date::get_timeago($data->archived_audit->datetime) : l('global.na') ?>
                </div>
            </div>
        </div>
    </div>


    <div class="card mt-4 mb-5">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between">
                <span class="small font-weight-bold"><?= l('audits.basic') ?></span>

                <span class="badge bg-primary-50 text-primary-700">
                    <i class="fas fa-fw fa-sm fa-file-code"></i>
                </span>
            </div>
        </div>

        <div class="card-body">

            <?php if(settings()->audits->available_tests->doctype): ?>
                <div id="doctype" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('doctype', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.doctype') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->doctype): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <div class="mb-3">
                                <p class="small m-0">
                                    <?= $data->archived_audit->data->doctype ? '<code>' . e($data->archived_audit->data->doctype) . '</code>' : l('global.none') ?>
                                </p>
                                <small class="text-muted"><?= l('audits.test.doctype_help') ?></small>
                            </div>

                            <?php if(in_array('missing', $data->archived_audit->issues->moderate->doctype ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-moderate">
                                    <p class="m-0 font-size-small"><?= l('audits.test.doctype.missing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.doctype.missing_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->language): ?>
                <div id="language" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('language', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.language') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->language): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <?php if(!empty($data->archived_audit->data->language)): ?>
                                <div class="mb-3">
                                    <p class="small font-weight-bold m-0"><?= e($data->archived_audit->data->language) ?></p>
                                    <small class="text-muted"><?= l('audits.test.language_help') ?></small>
                                </div>
                            <?php endif ?>

                            <?php if(in_array('missing', $data->archived_audit->issues->minor->language ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                    <p class="m-0 font-size-small"><?= l('audits.test.language.missing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.language.missing_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->meta_charset): ?>
                <div id="meta_charset" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('meta_charset', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.meta_charset') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->meta_charset): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <div class="mb-3">
                                <?php if($data->archived_audit->data->meta_charset): ?>
                                    <p class="small m-0">
                                        <code><?= $data->archived_audit->data->meta_charset ?></code>
                                    </p>
                                <?php endif ?>
                                <small class="text-muted"><?= l('audits.test.meta_charset_help') ?></small>
                            </div>

                            <?php if(in_array('missing', $data->archived_audit->issues->moderate->meta_charset ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-moderate">
                                    <p class="m-0 font-size-small"><?= l('audits.test.meta_charset.missing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.meta_charset.missing_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->meta_viewport): ?>
                <div id="meta_viewport" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('meta_viewport', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.meta_viewport') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->meta_viewport): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <div class="mb-3">
                                <p class="small m-0">
                                    <?php if($data->archived_audit->data->meta_viewport): ?>
                                        <code><?= $data->archived_audit->data->meta_viewport ?></code>
                                    <?php endif ?>
                                </p>
                                <small class="text-muted"><?= l('audits.test.meta_viewport_help') ?></small>
                            </div>

                            <?php if(in_array('missing', $data->archived_audit->issues->moderate->meta_viewport ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-moderate">
                                    <p class="m-0 font-size-small"><?= l('audits.test.meta_viewport.missing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.meta_viewport.missing_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->favicon): ?>
                <div id="favicon" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('favicon', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.favicon') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->favicon): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <?php if(!empty($data->archived_audit->data->favicon)): ?>
                                <div class="mb-3">
                                    <p class="small m-0">
                                        <img referrerpolicy="no-referrer" src="<?= $data->archived_audit->data->favicon ?>" class="img-fluid icon-favicon-small mr-1" loading="lazy" />
                                        <a href="<?= $data->archived_audit->data->favicon ?>" target="_blank" rel="nofollow noreferrer"><?= $data->archived_audit->data->favicon ?></a>
                                    </p>
                                    <small class="text-muted"><?= l('audits.test.favicon_help') ?></small>
                                </div>
                            <?php endif ?>

                            <?php if(in_array('missing', $data->archived_audit->issues->moderate->favicon ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-moderate">
                                    <p class="m-0 font-size-small"><?= l('audits.test.favicon.missing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.favicon.missing_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>
            <?php endif ?>

        </div>
    </div>

    <div class="card mb-5">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between">
                <span class="small font-weight-bold"><?= l('audits.seo') ?></span>

                <span class="badge bg-primary-50 text-primary-700">
                    <i class="fas fa-fw fa-sm fa-search-plus"></i>
                </span>
            </div>
        </div>

        <div class="card-body">

            <?php if(settings()->audits->available_tests->title): ?>
                <div id="title" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('title', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.title') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->title): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <?php if(!empty($data->archived_audit->data->title)): ?>
                                <div class="mb-3">
                                    <p class="small font-weight-bold m-0"><?= e($data->archived_audit->data->title) ?></p>
                                    <small class="text-muted"><?= sprintf(l('audits.characters'), mb_strlen($data->archived_audit->data->title ?? '')) ?></small>
                                </div>
                            <?php endif ?>

                            <?php if(in_array('missing', $data->archived_audit->issues->major->title ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-major">
                                    <p class="m-0 font-size-small"><?= l('audits.test.title.missing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.title.missing_help') ?></small>
                                </div>
                            <?php endif ?>

                            <?php if(in_array('too_long', $data->archived_audit->issues->major->title ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-major">
                                    <p class="m-0 font-size-small"><?= l('audits.test.title.too_long') ?></p>
                                    <small class="text-muted"><?= l('audits.test.title.too_long_help') ?></small>
                                </div>
                            <?php endif ?>

                            <?php if(in_array('too_short', $data->archived_audit->issues->major->title ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-major">
                                    <p class="m-0 font-size-small"><?= l('audits.test.title.too_short') ?></p>
                                    <small class="text-muted"><?= l('audits.test.title.too_short_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->meta_description): ?>
                <div id="meta_description" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('meta_description', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.meta_description') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->meta_description): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <?php if(!empty($data->archived_audit->data->meta_description)): ?>
                                <div class="mb-3">
                                    <p class="small m-0"><?= e($data->archived_audit->data->meta_description) ?></p>
                                    <small class="text-muted"><?= sprintf(l('audits.characters'), mb_strlen($data->archived_audit->data->meta_description)) ?></small>
                                </div>
                            <?php endif ?>

                            <?php if(in_array('missing', $data->archived_audit->issues->major->meta_description ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-major">
                                    <p class="m-0 font-size-small"><?= l('audits.test.meta_description.missing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.meta_description.missing_help') ?></small>
                                </div>
                            <?php endif ?>

                            <?php if(in_array('too_long', $data->archived_audit->issues->moderate->meta_description ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-moderate">
                                    <p class="m-0 font-size-small"><?= l('audits.test.meta_description.too_long') ?></p>
                                    <small class="text-muted"><?= l('audits.test.meta_description.too_long_help') ?></small>
                                </div>
                            <?php endif ?>

                            <?php if(in_array('too_short', $data->archived_audit->issues->moderate->meta_description  ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-moderate">
                                    <p class="m-0 font-size-small"><?= l('audits.test.meta_description.too_short') ?></p>
                                    <small class="text-muted"><?= l('audits.test.meta_description.too_short_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->h1): ?>
                <div id="h1" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('h1', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.h1') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->h1): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <?php if(count($data->archived_audit->data->headings->h1) > 1): ?>
                                <ol class="mb-0 pl-3 audit-ol">
                                    <?php foreach($data->archived_audit->data->headings->h1 ?? [] as $h1): ?>
                                        <li class="mb-3">
                                            <p class="small font-weight-bold m-0"><?= e($h1) ?></p>
                                            <small class="text-muted"><?= sprintf(l('audits.characters'), mb_strlen($h1)) ?></small>
                                        </li>
                                    <?php endforeach ?>
                                </ol>
                            <?php else: ?>
                                <?php foreach($data->archived_audit->data->headings->h1 ?? [] as $h1): ?>
                                    <div class="mb-3">
                                        <p class="small font-weight-bold m-0"><?= e($h1) ?></p>
                                        <small class="text-muted"><?= sprintf(l('audits.characters'), mb_strlen($h1)) ?></small>
                                    </div>
                                <?php endforeach ?>
                            <?php endif ?>

                            <?php if(in_array('missing', $data->archived_audit->issues->major->h1 ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-major">
                                    <p class="m-0 font-size-small"><?= l('audits.test.h1.missing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.h1.missing_help') ?></small>
                                </div>
                            <?php else: ?>

                                <?php if(in_array('too_many', $data->archived_audit->issues->moderate->h1 ?? [])): ?>
                                    <div class="mb-3 audit-issue-wrapper audit-issue-moderate">
                                        <p class="m-0 font-size-small"><?= l('audits.test.h1.too_many') ?></p>
                                        <small class="text-muted"><?= l('audits.test.h1.too_many_help') ?></small>
                                    </div>
                                <?php endif ?>

                                <?php if(in_array('too_long', $data->archived_audit->issues->minor->h1 ?? [])): ?>
                                    <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                        <p class="m-0 font-size-small"><?= l('audits.test.h1.too_long') ?></p>
                                        <small class="text-muted"><?= l('audits.test.h1.too_long_help') ?></small>
                                    </div>
                                <?php endif ?>

                                <?php if(in_array('too_short', $data->archived_audit->issues->minor->h1  ?? [])): ?>
                                    <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                        <p class="m-0 font-size-small"><?= l('audits.test.h1.too_short') ?></p>
                                        <small class="text-muted"><?= l('audits.test.h1.too_short_help') ?></small>
                                    </div>
                                <?php endif ?>

                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->meta_robots): ?>
                <div id="meta_robots" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('meta_robots', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.meta_robots') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->meta_robots): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <div class="mb-3">
                                <?php if (!empty($data->archived_audit->data->meta_robots)): ?>
                                    <p class="small font-weight-bold m-0"><?= e(implode(', ', $data->archived_audit->data->meta_robots)) ?></p>
                                <?php else: ?>
                                    <p class="small font-weight-bold m-0"><?= l('audits.test.meta_robots_missing') ?></p>
                                <?php endif ?>

                                <small class="text-muted"><?= l('audits.test.meta_robots_help') ?></small>
                            </div>

                            <?php if(in_array('excluded', $data->archived_audit->issues->major->meta_robots ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-major">
                                    <p class="m-0 font-size-small"><?= l('audits.test.meta_robots.excluded') ?></p>
                                    <small class="text-muted"><?= l('audits.test.meta_robots.excluded_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->header_robots): ?>
                <div id="header_robots" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('header_robots', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.header_robots') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->header_robots): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <div class="mb-3">
                                <p class="small font-weight-bold m-0"><?= $data->archived_audit->data->response_headers->x_robots_tag ? e($data->archived_audit->data->response_headers->x_robots_tag) : l('audits.test.header_robots_missing') ?></p>

                                <small class="text-muted"><?= l('audits.test.header_robots_help') ?></small>
                            </div>

                            <?php if(in_array('excluded', $data->archived_audit->issues->major->header_robots ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-major">
                                    <p class="m-0 font-size-small"><?= l('audits.test.header_robots.excluded') ?></p>
                                    <small class="text-muted"><?= l('audits.test.header_robots.excluded_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->canonical): ?>
                <div id="canonical" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('canonical', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.canonical') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->canonical): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <div class="mb-3">
                                <p class="small m-0"><strong><?= $data->archived_audit->data->canonical ? e($data->archived_audit->data->canonical) : l('global.none') ?></strong></p>
                                <small class="text-muted"><?= l('audits.test.canonical_help') ?></small>
                            </div>

                            <?php if(in_array('missing', $data->archived_audit->issues->minor->canonical ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                    <p class="m-0 font-size-small"><?= l('audits.test.canonical.missing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.canonical.missing_help') ?></small>
                                    <small class="text-muted"><?= sprintf(l('audits.test.canonical.missing_help2'), '<br /><code>&lt;link rel="canonical" href="' . $data->archived_audit->url . '" /&gt;</code>') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->is_seo_friendly_url): ?>
                <div id="is_seo_friendly_url" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('is_seo_friendly_url', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.is_seo_friendly_url') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->is_seo_friendly_url): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <div class="mb-3">
                                <p class="small m-0"><strong><?= $data->archived_audit->data->url ?></strong></p>
                                <small class="text-muted"><?= l('audits.test.is_seo_friendly_url_help') ?></small>
                            </div>

                            <?php if(in_array('false', $data->archived_audit->issues->minor->is_seo_friendly_url ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                    <p class="m-0 font-size-small"><?= l('audits.test.is_seo_friendly_url.false') ?></p>
                                    <small class="text-muted"><?= l('audits.test.is_seo_friendly_url.false_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->opengraph): ?>
                <div id="opengraph" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('opengraph', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.opengraph') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->opengraph): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <div class="mb-3">
                                <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#opengraph_container" aria-expanded="false" aria-controls="opengraph_container" <?= count((array) $data->archived_audit->data->opengraph) ? null : 'disabled="disabled"' ?>>
                                    <?= sprintf(l('audits.test.opengraph_count'), '<strong>' . count((array) $data->archived_audit->data->opengraph) . '</strong>') ?>
                                </button>

                                <div class="collapse" id="opengraph_container">
                                    <div class="card card-body">
                                        <ol class="mb-0 pl-3 audit-ol">
                                            <?php foreach ($data->archived_audit->data->opengraph as $key => $value): ?>
                                                <li class="text-truncate mb-3">
                                                    <p class="m-0 font-size-small font-weight-bold">
                                                        <?= e($key) ?>
                                                    </p>

                                                    <small class="text-muted">
                                                        <?php if($key == 'og:image'): ?>
                                                            <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain(parse_url($value, PHP_URL_HOST)) ?>" class="img-fluid icon-favicon-small mr-1" loading="lazy" />
                                                            <a href="<?= e($value) ?>" target="_blank" rel="nofollow noreferrer" class="text-truncate"><?= e($value) ?></a>
                                                        <?php elseif($key == 'og:url'): ?>
                                                            <a href="<?= e($value) ?>" target="_blank" rel="nofollow noreferrer" class="text-truncate"><?= e($value) ?></a>
                                                        <?php else: ?>
                                                            <?= e($value) ?>
                                                        <?php endif ?>
                                                    </small>
                                                </li>
                                            <?php endforeach ?>
                                        </ol>
                                    </div>
                                </div>
                                <small class="text-muted"><?= l('audits.test.opengraph_help') ?></small>
                            </div>

                            <?php if(in_array('missing', $data->archived_audit->issues->minor->opengraph ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                    <p class="m-0 font-size-small"><?= l('audits.test.opengraph.missing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.opengraph.missing_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->schemas): ?>
                <div id="schemas" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('schemas', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.schemas') ?></span>
                        </div>
                    </div>

                    <?php

                    function render_list($data) {
                        if(is_object($data)) {
                            $data = (array) $data;
                        }

                        $output = '<ul class="pl-4 my-3">';
                        foreach ($data as $key => $value) {
                            $output .= '<li><span class="m-0 font-size-small font-weight-bold">' . e($key) . ':</span> ';
                            if(is_array($value) || is_object($value)) {
                                $output .= render_list($value);
                            } else {
                                $output .= '<span class="text-muted small">' . e($value) . '</span>';
                            }
                            $output .= '</li>';
                        }
                        $output .= '</ul>';

                        return $output;
                    }
                    ?>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->schemas): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <div class="mb-3">
                                <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#schemas_container" aria-expanded="false" aria-controls="schemas_container" <?= count((array) $data->archived_audit->data->schemas) ? null : 'disabled="disabled"' ?>>
                                    <?= sprintf(l('audits.test.schemas_count'), '<strong>' . count((array) $data->archived_audit->data->schemas) . '</strong>') ?>
                                </button>

                                <div class="collapse" id="schemas_container">
                                    <div class="card card-body">
                                        <ol class="mb-0 pl-3 audit-ol">
                                            <?php $i = 1; ?>
                                            <?php foreach ($data->archived_audit->data->schemas as $key => $value): ?>
                                                <li class="mb-3">

                                                    <div>
                                                        <?= render_list($value) ?>
                                                    </div>
                                                </li>
                                            <?php endforeach ?>
                                        </ol>
                                    </div>
                                </div>
                                <small class="text-muted"><?= l('audits.test.schemas_help') ?></small>
                            </div>

                            <?php if(in_array('missing', $data->archived_audit->issues->minor->schemas ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                    <p class="m-0 font-size-small"><?= l('audits.test.schemas.missing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.schemas.missing_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->other_headings): ?>
                <div id="other_headings" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <span data-toggle="tooltip" title="<?= l('audits.informational_test') ?>"><i class="fas fa-fw fa-sm fa-info-circle text-info mr-1"></i></span>
                            <span class="font-weight-bold"><?= l('audits.test.other_headings') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->other_headings): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <div class="mb-3">
                                <?php foreach(['h2', 'h3', 'h4', 'h5', 'h6'] as $heading_type): ?>
                                    <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#other_headings_container_<?= $heading_type ?>" aria-expanded="false" aria-controls="other_headings_container_<?= $heading_type ?>" <?= count($data->archived_audit->data->headings->{$heading_type}) ? null : 'disabled="disabled"' ?>>
                                        <?= sprintf(l('audits.test.other_headings_count'), '<strong>' . count($data->archived_audit->data->headings->{$heading_type}) . '</strong>', $heading_type) ?>
                                    </button>

                                    <div class="collapse" id="other_headings_container_<?= $heading_type ?>">
                                        <div class="card card-body">
                                            <ol class="mb-0 pl-3 audit-ol">
                                                <?php foreach ($data->archived_audit->data->headings->{$heading_type} as $heading_text): ?>
                                                    <li class="font-size-small mb-2">
                                                        <?= e($heading_text) ?>
                                                    </li>
                                                <?php endforeach ?>
                                            </ol>
                                        </div>
                                    </div>
                                <?php endforeach ?>

                                <small class="text-muted"><?= l('audits.test.other_headings_help') ?></small>
                            </div>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->meta_keywords): ?>
                <div id="meta_keywords" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <span data-toggle="tooltip" title="<?= l('audits.informational_test') ?>"><i class="fas fa-fw fa-sm fa-info-circle text-info mr-1"></i></span>
                            <span class="font-weight-bold"><?= l('audits.test.meta_keywords') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->meta_keywords): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <?php $meta_keywords = explode(',', $data->archived_audit->data->meta_keywords ?? '') ?>

                            <?php if(!empty($data->archived_audit->data->meta_keywords)): ?>
                                <div class="mb-3">
                                    <p class="m-0 font-size-small">
                                        <?php foreach ($meta_keywords as $keyword): ?>
                                            <code class="badge badge-light mr-2 mb-1"><?= $keyword ?></code>
                                        <?php endforeach ?>
                                    </p>

                                    <small class="text-muted"><?= sprintf(l('audits.characters'), mb_strlen($data->archived_audit->data->meta_keywords ?? '')) ?></small>
                                    <small class="text-muted"><?= sprintf(l('audits.test.meta_keywords_count'), count($meta_keywords)) ?></small>
                                </div>
                            <?php else: ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-info">
                                    <p class="m-0 font-size-small"><?= l('audits.test.meta_keywords.missing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.meta_keywords.missing_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>
            <?php endif ?>

        </div>
    </div>

    <div class="card mb-5">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between">
                <span class="small font-weight-bold"><?= l('audits.content') ?></span>

                <span class="badge bg-primary-50 text-primary-700">
                    <i class="fas fa-fw fa-sm fa-paragraph"></i>
                </span>
            </div>
        </div>

        <div class="card-body">

            <?php if(settings()->audits->available_tests->words_count): ?>
                <div id="words_count" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('words_count', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.words_count') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->words_count): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <div class="mb-3">
                                <p class="small m-0"><?= sprintf(l('audits.test.words_count_count'), '<strong>' . nr($data->archived_audit->data->words_count) . '</strong>') ?></p>
                                <small class="text-muted"><?= l('audits.test.words_count_help') ?></small>
                            </div>

                            <?php if(in_array('too_few', $data->archived_audit->issues->moderate->words_count ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-moderate">
                                    <p class="m-0 font-size-small"><?= l('audits.test.words_count.too_few') ?></p>
                                    <small class="text-muted"><?= l('audits.test.words_count.too_few_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->words_used): ?>
                <div id="words_used" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <span data-toggle="tooltip" title="<?= l('audits.informational_test') ?>"><i class="fas fa-fw fa-sm fa-info-circle text-info mr-1"></i></span>
                            <span class="font-weight-bold"><?= l('audits.test.words_used') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->words_used): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <?php if(!empty($data->archived_audit->data->top_words)): ?>
                                <div class="mb-3">
                                    <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#words_used_container" aria-expanded="false" aria-controls="words_used_container">
                                        <?= sprintf(l('audits.test.words_used_count'), '<strong>' . count((array) $data->archived_audit->data->top_words) . '</strong>') ?>
                                    </button>

                                    <div class="collapse" id="words_used_container">
                                        <div class="card card-body">
                                            <div class="row">
                                                <?php foreach ($data->archived_audit->data->top_words as $word => $count): ?>
                                                    <div class="col-md-6">
                                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                                            <div class="text-truncate">
                                                                <p class="m-0 font-size-small text-truncate" title="<?= e($word) ?>"><?= e($word) ?></p>
                                                            </div>

                                                            <span class="badge badge-light">
                                                        <?= nr($count) ?>
                                                    </span>
                                                        </div>
                                                    </div>
                                                <?php endforeach ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-info">
                                    <p class="m-0 font-size-small"><?= l('audits.test.words_used.missing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.words_used.missing_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->deprecated_html_tags): ?>
                <div id="deprecated_html_tags" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('deprecated_html_tags', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.deprecated_html_tags') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->deprecated_html_tags): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <?php if(count((array) $data->archived_audit->data->deprecated_html_tags)): ?>
                                <div class="mb-3">
                                    <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#deprecated_html_tags_container" aria-expanded="false" aria-controls="deprecated_html_tags_container">
                                        <?= sprintf(l('audits.test.deprecated_html_tags_count'), '<strong>' . count((array) $data->archived_audit->data->deprecated_html_tags) . '</strong>') ?>
                                    </button>

                                    <div class="collapse" id="deprecated_html_tags_container">
                                        <div class="card card-body">
                                            <?php foreach ($data->archived_audit->data->deprecated_html_tags as $tag => $count): ?>
                                                <div class="d-flex justify-content-between small mb-2">
                                                    <code>&lt;<?= $tag ?>&gt;</code>

                                                    <span class="font-weight-bold"><?= nr($count) ?></span>
                                                </div>
                                            <?php endforeach ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                    <p class="m-0 font-size-small"><?= l('audits.test.deprecated_html_tags.existing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.deprecated_html_tags.existing_help') ?></small>
                                </div>
                            <?php else: ?>
                                <div class="mb-3">
                                    <p class="small font-weight-bold m-0"><?= l('audits.test.deprecated_html_tags_missing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.deprecated_html_tags_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->inline_css): ?>
                <div id="inline_css" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('inline_css', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.inline_css') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->inline_css): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <div class="mb-3">
                                <?php if(count((array) $data->archived_audit->data->inline_css)): ?>
                                    <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#inline_css_container" aria-expanded="false" aria-controls="inline_css_container">
                                        <?= sprintf(l('audits.test.inline_css_count'), '<strong>' . count((array) $data->archived_audit->data->inline_css) . '</strong>') ?>
                                    </button>

                                    <div class="collapse" id="inline_css_container">
                                        <div class="card card-body">
                                            <ol class="mb-0 pl-3 audit-ol">
                                                <?php foreach ($data->archived_audit->data->inline_css as $inline_css): ?>
                                                    <li class="mb-3">
                                                        <p class="m-0 font-size-small">&lt;<?= e($inline_css->tag) ?>&gt;</p>
                                                        <small class="text-muted"><code><?= string_truncate(e($inline_css->style), 64) ?></code></small>
                                                    </li>
                                                <?php endforeach ?>
                                            </ol>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <p class="small font-weight-bold m-0"><?= l('audits.test.inline_css_missing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.inline_css_help') ?></small>
                                <?php endif ?>
                            </div>

                            <?php if(in_array('existing', $data->archived_audit->issues->minor->inline_css ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                    <p class="m-0 font-size-small"><?= l('audits.test.inline_css.existing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.inline_css.existing_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->emails): ?>
                <div id="emails" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('emails', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.emails') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->emails): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <div class="mb-3">
                                <?php if(count((array) $data->archived_audit->data->emails)): ?>
                                    <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#emails_container" aria-expanded="false" aria-controls="emails_container">
                                        <?= sprintf(l('audits.test.emails_count'), '<strong>' . count((array) $data->archived_audit->data->emails) . '</strong>') ?>
                                    </button>

                                    <div class="collapse" id="emails_container">
                                        <div class="card card-body">
                                            <ol class="mb-0">
                                                <?php foreach ($data->archived_audit->data->emails as $email): ?>
                                                    <li class="font-size-small mb-2">
                                                        <?= e($email) ?>
                                                    </li>
                                                <?php endforeach ?>
                                            </ol>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <p class="small font-weight-bold m-0"><?= l('audits.test.emails_missing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.emails_help') ?></small>
                                <?php endif ?>
                            </div>

                            <?php if(in_array('existing', $data->archived_audit->issues->minor->emails ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                    <p class="m-0 font-size-small"><?= l('audits.test.emails.existing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.emails.existing_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->text_to_html_ratio): ?>
                <div id="text_to_html_ratio" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('text_to_html_ratio', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.text_to_html_ratio') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->text_to_html_ratio): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <div class="mb-3">
                                <p class="small m-0"><strong><?= nr($data->archived_audit->data->text_to_html_ratio) ?>%</strong></p>
                                <small class="text-muted"><?= l('audits.test.text_to_html_ratio_help') ?></small>
                            </div>

                            <?php if(in_array('too_low', $data->archived_audit->issues->minor->text_to_html_ratio ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                    <p class="m-0 font-size-small"><?= l('audits.test.text_to_html_ratio.too_low') ?></p>
                                    <small class="text-muted"><?= l('audits.test.text_to_html_ratio.too_low_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>
            <?php endif ?>

        </div>
    </div>

    <div class="card mb-5">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between">
                <span class="small font-weight-bold"><?= l('audits.media') ?></span>

                <span class="badge bg-primary-50 text-primary-700">
                    <i class="fas fa-fw fa-sm fa-image"></i>
                </span>
            </div>
        </div>

        <div class="card-body">

            <?php if(settings()->audits->available_tests->image_formats): ?>
                <div id="image_formats" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('image_formats', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.image_formats') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->image_formats): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <?php
                            $failed_images = [];
                            foreach ($data->archived_audit->data->images as $image) {
                                if($image->extension && !in_array($image->extension, ['webp', 'avif', 'svg'])) {
                                    $failed_images[] = $image;
                                }
                            }
                            ?>
                            <?php if (!empty($failed_images)): ?>
                                <div class="mb-3">
                                    <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#image_formats_container" aria-expanded="false" aria-controls="image_formats_container">
                                        <?= sprintf(l('audits.test.image_formats_count'), '<strong>' . count($failed_images) . '</strong>') ?>
                                    </button>

                                    <div class="collapse" id="image_formats_container">
                                        <div class="card card-body">
                                            <?php foreach ($failed_images as $image): ?>
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <div class="text-truncate">
                                                        <p class="m-0 font-size-small">
                                                            <span class="badge badge-light"><?= $image->extension ?></span>
                                                            <?= $image->title ? e($image->title) : l('audits.test.image_formats_title_missing') ?>
                                                        </p>
                                                        <small class="text-muted">
                                                            <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain(parse_url($image->src, PHP_URL_HOST)) ?>" class="img-fluid icon-favicon-small mr-1" loading="lazy" />
                                                            <a href="<?= e($image->src) ?>" target="_blank" rel="nofollow noreferrer" class="text-truncate"><?= e($image->src) ?></a>
                                                        </small>
                                                    </div>
                                                </div>
                                            <?php endforeach ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                    <p class="m-0 font-size-small"><?= l('audits.test.image_formats.existing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.image_formats.existing_help') ?></small>
                                </div>
                            <?php else: ?>
                                <div class="mb-3">
                                    <p class="small font-weight-bold m-0"><?= l('audits.test.image_formats_missing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.image_formats_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->image_alt): ?>
                <div id="image_alt" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('image_alt', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.image_alt') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->image_alt): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <?php
                            $failed_images = [];
                            foreach($data->archived_audit->data->images as $image) {
                                if(empty($image->alt)) {
                                    $failed_images[] = $image;
                                }
                            }
                            ?>
                            <?php if (!empty($failed_images)): ?>
                                <div class="mb-3">
                                    <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#image_alt_container" aria-expanded="false" aria-controls="image_alt_container">
                                        <?= sprintf(l('audits.test.image_alt_count'), count($failed_images)) ?>
                                    </button>

                                    <div class="collapse" id="image_alt_container">
                                        <div class="card card-body">
                                            <?php foreach ($failed_images as $image): ?>
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <div class="text-truncate">
                                                        <p class="m-0 font-size-small">
                                                            <span class="badge badge-light"><?= $image->extension ?></span>
                                                            <?= $image->title ? e($image->title) : l('audits.test.image_alt_title_missing') ?>
                                                        </p>
                                                        <small class="text-muted">
                                                            <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain(parse_url($image->src, PHP_URL_HOST)) ?>" class="img-fluid icon-favicon-small mr-1" loading="lazy" />
                                                            <a href="<?= e($image->src) ?>" target="_blank" rel="nofollow noreferrer" class="text-truncate"><?= e($image->src) ?></a>
                                                        </small>
                                                    </div>
                                                </div>
                                            <?php endforeach ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                    <p class="m-0 font-size-small"><?= l('audits.test.image_alt.missing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.image_alt.missing_help') ?></small>
                                </div>
                            <?php else: ?>
                                <div class="mb-3">
                                    <p class="small font-weight-bold m-0"><?= l('audits.test.image_alt_existing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.image_alt_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->image_lazy_loading): ?>
                <div id="image_lazy_loading" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <span data-toggle="tooltip" title="<?= l('audits.informational_test') ?>"><i class="fas fa-fw fa-sm fa-info-circle text-info mr-1"></i></span>
                            <span class="font-weight-bold"><?= l('audits.test.image_lazy_loading') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->image_lazy_loading): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <?php
                            $failed_images = [];
                            foreach($data->archived_audit->data->images as $image) {
                                if(empty($image->loading) || $image->loading != 'lazy') {
                                    $failed_images[] = $image;
                                }
                            }
                            ?>
                            <?php if (!empty($failed_images)): ?>
                                <div class="mb-3">
                                    <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#image_lazy_loading_container" aria-expanded="false" aria-controls="image_lazy_loading_container">
                                        <?= sprintf(l('audits.test.image_lazy_loading_count'), '<strong>' . count($failed_images) . '</strong>') ?>
                                    </button>

                                    <div class="collapse" id="image_lazy_loading_container">
                                        <div class="card card-body">
                                            <?php foreach ($failed_images as $image): ?>
                                                <div class="text-truncate mb-3">
                                                    <p class="m-0 font-size-small">
                                                        <span class="badge badge-light"><?= $image->extension ?></span>
                                                        <?= $image->title ? e($image->title) : l('audits.test.image_lazy_loading_title_missing') ?>
                                                    </p>
                                                    <small class="text-muted">
                                                        <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain(parse_url($image->src, PHP_URL_HOST)) ?>" class="img-fluid icon-favicon-small mr-1" loading="lazy" />
                                                        <a href="<?= e($image->src) ?>" target="_blank" rel="nofollow noreferrer" class="text-truncate"><?= e($image->src) ?></a>
                                                    </small>
                                                </div>
                                            <?php endforeach ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                    <p class="m-0 font-size-small"><?= l('audits.test.image_lazy_loading.missing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.image_lazy_loading.missing_help') ?></small>
                                </div>
                            <?php else: ?>
                                <div class="mb-3">
                                    <p class="small font-weight-bold m-0"><?= l('audits.test.image_lazy_loading_existing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.image_lazy_loading_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>
            <?php endif ?>

        </div>
    </div>

    <div class="card mb-5">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between">
                <span class="small font-weight-bold"><?= l('audits.technical_performance') ?></span>

                <span class="badge bg-primary-50 text-primary-700">
                    <i class="fas fa-fw fa-sm fa-tachometer-alt"></i>
                </span>
            </div>
        </div>

        <div class="card-body">

            <?php if(settings()->audits->available_tests->robots): ?>
                <div id="robots" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('robots', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.robots') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->robots): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <div class="mb-3">
                                <p class="small m-0"><a href="<?= $data->archived_audit->data->robots_url ?>" target="_blank" rel="nofollow noreferrer"><?= $data->archived_audit->data->robots_url ?></a></p>
                                <small class="text-muted"><?= l('audits.test.robots_help') ?></small>
                            </div>

                            <?php if(in_array('missing', $data->archived_audit->issues->minor->robots ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                    <p class="m-0 font-size-small"><?= l('audits.test.robots.missing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.robots.missing_help') ?></small>
                                </div>
                            <?php endif ?>

                            <?php if(in_array('excluded', $data->archived_audit->issues->major->robots ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-major">
                                    <p class="m-0 font-size-small"><?= l('audits.test.robots.excluded') ?></p>
                                    <small class="text-muted"><?= l('audits.test.robots.excluded_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->not_found): ?>
                <div id="not_found" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('not_found', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.not_found') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->not_found): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <div class="mb-3">
                                <p class="small m-0"><?= remove_url_protocol_from_url($data->archived_audit->data->not_found_url) ?> - <?= $data->archived_audit->data->not_found_status_code ?></p>
                                <small class="text-muted"><?= l('audits.test.not_found_help') ?></small>
                            </div>

                            <?php if(in_array('missing', $data->archived_audit->issues->moderate->not_found ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-moderate">
                                    <p class="m-0 font-size-small"><?= l('audits.test.not_found.missing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.not_found.missing_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->header_server): ?>
                <div id="header_server" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('header_server', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.header_server') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->header_server): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <div class="mb-3">
                                <p class="small font-weight-bold m-0"><?= $data->archived_audit->data->response_headers->server ? e($data->archived_audit->data->response_headers->server) : l('audits.test.header_server_missing') ?></p>

                                <small class="text-muted"><?= l('audits.test.header_server_help') ?></small>
                            </div>

                            <?php if(in_array('existing', $data->archived_audit->issues->minor->header_server ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                    <p class="m-0 font-size-small"><?= l('audits.test.header_server.existing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.header_server.existing_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->server_compression): ?>
                <div id="server_compression" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('server_compression', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.server_compression') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->server_compression): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <div class="mb-3">
                                <p class="small m-0">
                                    <?php
                                    $compression_algorithm = match ($data->archived_audit->data->response_headers->content_encoding) {
                                        'br' => 'Brotli',
                                        'gzip' => 'GNU zip',
                                        'compress' => 'Unix compression',
                                        'deflate' => 'Deflate compression',
                                        'zstd' => 'Zstandard compression',
                                        default => null
                                    };
                                    ?>

                                    <span class="font-weight-bold">
                                <?= $data->archived_audit->data->response_headers->content_encoding ? e($data->archived_audit->data->response_headers->content_encoding) : l('global.none') ?>
                            </span>

                                    <span>(<?= $compression_algorithm ?>)</span>
                                </p>

                                <?php if($data->archived_audit->data->response_headers->content_encoding): ?>
                                    <p class="small m-0">
                                        <?= sprintf(l('audits.test.server_compression_comparison'), '<strong>' . get_formatted_bytes($data->archived_audit->data->page_size) . '</strong>', '<strong>' . get_formatted_bytes($data->archived_audit->data->download_size) . '</strong>', nr(get_percentage_change($data->archived_audit->data->page_size, $data->archived_audit->data->download_size))) ?>
                                    </p>
                                <?php endif ?>

                                <small class="text-muted"><?= l('audits.test.server_compression_help') ?></small>
                            </div>

                            <?php if(in_array('missing', $data->archived_audit->issues->moderate->server_compression ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-moderate">
                                    <p class="m-0 font-size-small"><?= l('audits.test.server_compression.missing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.server_compression.missing_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->response_time): ?>
                <div id="response_time" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('response_time', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.response_time') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->response_time): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <div class="mb-3">
                                <p class="small font-weight-bold m-0"><?= display_response_time($data->archived_audit->data->response_time) ?></p>
                                <small class="text-muted"><?= l('audits.test.response_time_help') ?></small>
                            </div>

                            <?php if(in_array('too_slow', $data->archived_audit->issues->major->response_time ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-major">
                                    <p class="m-0 font-size-small"><?= l('audits.test.response_time.too_slow') ?></p>
                                    <small class="text-muted"><?= l('audits.test.response_time.too_slow_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->page_size): ?>
                <div id="page_size" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('page_size', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.page_size') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->page_size): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <div class="mb-3">
                                <p class="small font-weight-bold m-0"><?= get_formatted_bytes($data->archived_audit->data->page_size) ?></p>
                                <small class="text-muted"><?= l('audits.test.page_size_help') ?></small>
                            </div>

                            <?php if(in_array('too_big', $data->archived_audit->issues->moderate->page_size ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-moderate">
                                    <p class="m-0 font-size-small"><?= l('audits.test.page_size.too_big') ?></p>
                                    <small class="text-muted"><?= l('audits.test.page_size.too_big_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->dom_size): ?>
                <div id="dom_size" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('dom_size', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.dom_size') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->dom_size): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <div class="mb-3">
                                <p class="small font-weight-bold m-0"><?= sprintf(l('audits.test.dom_size_nodes'), nr($data->archived_audit->data->dom_size)) ?></p>
                                <small class="text-muted"><?= l('audits.test.dom_size_help') ?></small>
                            </div>

                            <?php if(in_array('too_big', $data->archived_audit->issues->minor->dom_size ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                    <p class="m-0 font-size-small"><?= l('audits.test.dom_size.too_big') ?></p>
                                    <small class="text-muted"><?= l('audits.test.dom_size.too_big_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->non_deferred_scripts): ?>
                <div id="non_deferred_scripts" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('non_deferred_scripts', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.non_deferred_scripts') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->non_deferred_scripts): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <?php if($data->archived_audit->data->non_deferred_scripts_count): ?>
                                <div class="mb-3">
                                    <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#non_deferred_scripts_container" aria-expanded="false" aria-controls="non_deferred_scripts_container">
                                        <?= sprintf(l('audits.test.non_deferred_scripts_count'), '<strong>' . $data->archived_audit->data->non_deferred_scripts_count . '</strong>') ?>
                                    </button>

                                    <div class="collapse" id="non_deferred_scripts_container">
                                        <div class="card card-body">
                                            <ol class="mb-0 pl-3 audit-ol">
                                                <?php foreach ($data->archived_audit->data->scripts as $script): ?>
                                                    <?php if($script->is_deferred) continue ?>
                                                    <li class="font-size-small mb-2">
                                                        <?php if($script->src): ?>
                                                            <a href="<?= e($script->src) ?>" target="_blank" rel="nofollow noreferrer">
                                                                <?= e($script->src) ?>
                                                            </a>
                                                        <?php else: ?>
                                                            <?= l('audits.test.non_deferred_scripts_src_missing') ?>
                                                        <?php endif ?>
                                                    </li>
                                                <?php endforeach ?>
                                            </ol>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                    <p class="m-0 font-size-small"><?= l('audits.test.non_deferred_scripts.existing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.non_deferred_scripts.existing_help') ?></small>
                                    <small class="text-muted"><?= l('audits.test.non_deferred_scripts.existing_help2') ?></small>
                                </div>
                            <?php else: ?>
                                <div class="mb-3">
                                    <p class="small font-weight-bold m-0"><?= l('audits.test.non_deferred_scripts_missing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.non_deferred_scripts_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->http_requests): ?>
                <div id="http_requests" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('http_requests', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.http_requests') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->http_requests): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <p class="small mb-2"><?= sprintf(l('audits.test.http_requests_count'), '<strong>' . $data->archived_audit->data->http_requests . '</strong>') ?></p>

                            <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#http_requests_container_css" aria-expanded="false" aria-controls="http_requests_container_css" <?= $data->archived_audit->data->http_requests_data->css ? null : 'disabled="disabled"' ?>>
                                <?= sprintf(l('audits.test.http_requests_css'), '<strong>' . $data->archived_audit->data->http_requests_data->css . '</strong>') ?>
                            </button>

                            <div class="collapse" id="http_requests_container_css">
                                <div class="card card-body">
                                    <ol class="mb-0 pl-3 audit-ol">
                                        <?php foreach($data->archived_audit->data->stylesheets as $stylesheet): ?>
                                            <li class="font-size-small mb-2">
                                                <a href="<?= e($stylesheet->href) ?>" rel="nofollow noreferrer" target="_blank">
                                                    <?= e($stylesheet->href) ?>
                                                </a>
                                            </li>
                                        <?php endforeach ?>
                                    </ol>
                                </div>
                            </div>

                            <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#http_requests_container_js" aria-expanded="false" aria-controls="http_requests_container_js" <?= $data->archived_audit->data->http_requests_data->js ? null : 'disabled="disabled"' ?>>
                                <?= sprintf(l('audits.test.http_requests_js'), '<strong>' . $data->archived_audit->data->http_requests_data->js . '</strong>') ?>
                            </button>

                            <div class="collapse" id="http_requests_container_js">
                                <div class="card card-body">
                                    <ol class="mb-0 pl-3 audit-ol">
                                        <?php foreach($data->archived_audit->data->scripts as $script): ?>
                                            <?php if($script->type != 'url') continue ?>
                                            <li class="font-size-small mb-2">
                                                <a href="<?= e($script->src) ?>" rel="nofollow noreferrer" target="_blank">
                                                    <?= e($script->src) ?>
                                                </a>
                                            </li>
                                        <?php endforeach ?>
                                    </ol>
                                </div>
                            </div>

                            <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#http_requests_container_images" aria-expanded="false" aria-controls="http_requests_container_images" <?= $data->archived_audit->data->http_requests_data->images ? null : 'disabled="disabled"' ?>>
                                <?= sprintf(l('audits.test.http_requests_images'), '<strong>' . $data->archived_audit->data->http_requests_data->images . '</strong>') ?>
                            </button>

                            <div class="collapse" id="http_requests_container_images">
                                <div class="card card-body">
                                    <ol class="mb-0 pl-3 audit-ol">
                                        <?php foreach($data->archived_audit->data->images as $image): ?>
                                            <?php if($image->type != 'url') continue ?>
                                            <li class="font-size-small mb-2">
                                                <a href="<?= e($image->src) ?>" rel="nofollow noreferrer" target="_blank">
                                                    <?= e($image->src) ?>
                                                </a>
                                            </li>
                                        <?php endforeach ?>

                                        <?php if(!empty($data->archived_audit->data->favicon)): ?>
                                            <li class="font-size-small mb-2">
                                                <a href="<?= $data->archived_audit->data->favicon ?>" rel="nofollow noreferrer" target="_blank">
                                                    <?= e($data->archived_audit->data->favicon) ?>
                                                </a>
                                            </li>
                                        <?php endif ?>
                                    </ol>
                                </div>
                            </div>

                            <?php foreach(['audios', 'videos', 'iframes'] as $http_request_type): ?>
                                <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#<?= 'http_requests_container_' . $http_request_type ?>" aria-expanded="false" aria-controls="<?= 'http_requests_container_' . $http_request_type ?>" <?= $data->archived_audit->data->http_requests_data->{$http_request_type} ? null : 'disabled="disabled"' ?>>
                                    <?= sprintf(l('audits.test.http_requests_' . $http_request_type), '<strong>' . $data->archived_audit->data->http_requests_data->{$http_request_type} . '</strong>') ?>
                                </button>

                                <div class="collapse" id="<?= 'http_requests_container_' . $http_request_type ?>">
                                    <div class="card card-body">
                                        <ol class="mb-0 pl-3 audit-ol">
                                            <?php foreach($data->archived_audit->data->{$http_request_type} as $http_request): ?>
                                                <li class="font-size-small mb-2">
                                                    <a href="<?= e($http_request->src) ?>" rel="nofollow noreferrer" target="_blank">
                                                        <?= e($http_request->src) ?>
                                                    </a>
                                                </li>
                                            <?php endforeach ?>
                                        </ol>
                                    </div>
                                </div>
                            <?php endforeach ?>

                            <small class="text-muted"><?= l('audits.test.http_requests_help') ?></small>

                            <div class="mb-3"></div>

                            <?php if(in_array('too_many', $data->archived_audit->issues->moderate->http_requests ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-moderate">
                                    <p class="m-0 font-size-small"><?= l('audits.test.http_requests.too_many') ?></p>
                                    <small class="text-muted"><?= l('audits.test.http_requests.too_many_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->is_http2): ?>
                <div id="is_http2" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('is_http2', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.is_http2') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->is_http2): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <div class="mb-3">
                                <p class="small m-0"><strong><?= $data->archived_audit->data->http_version == 3 ? l('global.yes') : l('global.none') ?></strong></p>
                                <small class="text-muted"><?= l('audits.test.is_http2_help') ?></small>
                            </div>

                            <?php if(in_array('missing', $data->archived_audit->issues->moderate->is_http2 ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-moderate">
                                    <p class="m-0 font-size-small"><?= l('audits.test.is_http2.missing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.is_http2.missing_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->is_https): ?>
                <div id="is_https" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('is_https', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.is_https') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->is_https): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <div class="mb-3">
                                <p class="small m-0"><strong><?= $data->archived_audit->data->is_https ? l('global.yes') : l('global.none') ?></strong></p>
                                <small class="text-muted"><?= l('audits.test.is_https_help') ?></small>
                            </div>

                            <?php if(in_array('missing', $data->archived_audit->issues->major->is_https ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-major">
                                    <p class="m-0 font-size-small"><?= l('audits.test.is_https.missing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.is_https.missing_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->is_ssl_valid): ?>
                <div id="is_ssl_valid" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('is_ssl_valid', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.is_ssl_valid') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->is_ssl_valid): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <div class="mb-3">
                                <p class="small m-0"><strong><?= $data->archived_audit->data->is_ssl_valid ? l('global.yes') : l('global.none') ?></strong></p>
                                <small class="text-muted"><?= l('audits.test.is_ssl_valid_help') ?></small>
                            </div>

                            <?php if(in_array('invalid', $data->archived_audit->issues->major->is_ssl_valid ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-major">
                                    <p class="m-0 font-size-small"><?= l('audits.test.is_ssl_valid.missing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.is_ssl_valid.missing_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->safe_browsing && !empty(settings()->audits->google_safe_browsing_api_key)): ?>
                <div id="safe_browsing" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('safe_browsing', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.safe_browsing') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->safe_browsing): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <div class="mb-3">
                                <p class="small m-0"><strong><?= empty($data->archived_audit->data->threats) ? l('audits.test.safe_browsing.true') : sprintf(l('audits.test.safe_browsing.false.threats'), implode(',', $data->archived_audit->data->threats))  ?></strong></p>
                                <small class="text-muted"><?= l('audits.test.safe_browsing_help') ?></small>
                            </div>

                            <?php if(in_array('false', $data->archived_audit->issues->major->safe_browsing ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-major">
                                    <p class="m-0 font-size-small"><?= l('audits.test.safe_browsing.false') ?></p>
                                    <small class="text-muted"><?= l('audits.test.safe_browsing.false_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->hsts): ?>
                <div id="hsts" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('hsts', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.hsts') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->hsts): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <div class="mb-3">
                                <p class="small m-0"><strong><?= $data->archived_audit->data->hsts ?: l('global.none') ?></strong></p>
                                <small class="text-muted"><?= l('audits.test.hsts_help') ?></small>
                            </div>

                            <?php if(in_array('missing', $data->archived_audit->issues->moderate->hsts ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-moderate">
                                    <p class="m-0 font-size-small"><?= l('audits.test.hsts.missing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.hsts.missing_help') ?></small>
                                </div>
                            <?php endif ?>

                            <?php if(in_array('invalid', $data->archived_audit->issues->moderate->hsts ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-moderate">
                                    <p class="m-0 font-size-small"><?= l('audits.test.hsts.invalid') ?></p>
                                    <small class="text-muted"><?= l('audits.test.hsts.invalid_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between">
                <span class="small font-weight-bold"><?= l('audits.links') ?></span>

                <span class="badge bg-primary-50 text-primary-700">
                    <i class="fas fa-fw fa-sm fa-link"></i>
                </span>
            </div>
        </div>

        <div class="card-body">

            <?php if(settings()->audits->available_tests->unsafe_external_links): ?>
                <div id="unsafe_external_links" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('unsafe_external_links', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.unsafe_external_links') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->unsafe_external_links): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <?php if($data->archived_audit->data->unsafe_external_links_count): ?>
                                <div class="mb-3">
                                    <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#unsafe_external_links_container" aria-expanded="false" aria-controls="unsafe_external_links_container">
                                        <?= sprintf(l('audits.test.unsafe_external_links_count'), '<strong>' . $data->archived_audit->data->unsafe_external_links_count . '</strong>') ?>
                                    </button>

                                    <div class="collapse" id="unsafe_external_links_container">
                                        <div class="card card-body">
                                            <?php foreach ($data->archived_audit->data->links as $link): ?>
                                                <?php if(!$link->is_unsafe) continue ?>

                                                <div class="text-truncate mb-3">
                                                    <p class="m-0 font-size-small">
                                                        <?= $link->text ? e($link->text) : l('audits.test.unsafe_external_links_text_missing') ?>
                                                    </p>

                                                    <small class="text-muted">
                                                        <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain(parse_url($link->href, PHP_URL_HOST)) ?>" class="img-fluid icon-favicon-small mr-1" loading="lazy" />
                                                        <a href="<?= e($link->href) ?>" target="_blank" rel="nofollow noreferrer" class="text-truncate"><?= e($link->href) ?></a>
                                                    </small>
                                                </div>
                                            <?php endforeach ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                    <p class="m-0 font-size-small"><?= l('audits.test.unsafe_external_links.existing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.unsafe_external_links.existing_help') ?></small>
                                </div>
                            <?php else: ?>
                                <div class="mb-3">
                                    <p class="small font-weight-bold m-0"><?= l('audits.test.unsafe_external_links_missing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.unsafe_external_links_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->external_links): ?>
                <div id="external_links" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('external_links', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.external_links') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->external_links): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <div class="mb-3">
                                <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#external_links_container" aria-expanded="false" aria-controls="external_links_container" <?= $data->archived_audit->data->external_links_count ? null : 'disabled="disabled"' ?>>
                                    <?= sprintf(l('audits.test.external_links_count'), '<strong>' . $data->archived_audit->data->external_links_count . '</strong>') ?>
                                </button>

                                <div class="collapse" id="external_links_container">
                                    <div class="card card-body">
                                        <?php foreach ($data->archived_audit->data->links as $link): ?>
                                            <?php if($link->is_internal) continue ?>

                                            <div class="text-truncate mb-3">
                                                <p class="m-0 font-size-small">
                                                    <?= $link->text ? e($link->text) : l('audits.test.external_links_text_missing') ?>
                                                </p>

                                                <small class="text-muted">
                                                    <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain(parse_url($link->href, PHP_URL_HOST)) ?>" class="img-fluid icon-favicon-small mr-1" loading="lazy" />
                                                    <a href="<?= e($link->href) ?>" target="_blank" rel="nofollow noreferrer" class="text-truncate"><?= e($link->href) ?></a>
                                                </small>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                            </div>

                            <?php if(in_array('too_many', $data->archived_audit->issues->moderate->external_links ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-moderate">
                                    <p class="m-0 font-size-small"><?= l('audits.test.external_links.too_many') ?></p>
                                    <small class="text-muted"><?= l('audits.test.external_links.too_many_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->internal_links): ?>
                <div id="internal_links" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <?= get_audit_test_icon('internal_links', $data->archived_audit->issues) ?>
                            <span class="font-weight-bold"><?= l('audits.test.internal_links') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->internal_links): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <div class="mb-3">
                                <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#internal_links_container" aria-expanded="false" aria-controls="internal_links_container" <?= $data->archived_audit->data->internal_links_count ? null : 'disabled="disabled"' ?>>
                                    <?= sprintf(l('audits.test.internal_links_count'), '<strong>' . $data->archived_audit->data->internal_links_count . '</strong>') ?>
                                </button>

                                <div class="collapse" id="internal_links_container">
                                    <div class="card card-body">
                                        <?php foreach ($data->archived_audit->data->links as $link): ?>
                                            <?php if(!$link->is_internal) continue ?>

                                            <div class="text-truncate mb-3">
                                                <p class="m-0 font-size-small">
                                                    <?= $link->text ? e($link->text) : l('audits.test.internal_links_text_missing') ?>
                                                </p>

                                                <small class="text-muted">
                                                    <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain(parse_url($link->href, PHP_URL_HOST)) ?>" class="img-fluid icon-favicon-small mr-1" loading="lazy" />
                                                    <a href="<?= e($link->href) ?>" target="_blank" rel="nofollow noreferrer" class="text-truncate"><?= e($link->href) ?></a>
                                                </small>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                            </div>

                            <?php if(in_array('too_many', $data->archived_audit->issues->minor->internal_links ?? [])): ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-minor">
                                    <p class="m-0 font-size-small"><?= l('audits.test.internal_links.too_many') ?></p>
                                    <small class="text-muted"><?= l('audits.test.internal_links.too_many_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>

                <div class="flex-fill my-4">
                    <hr class="border-gray-100" />
                </div>
            <?php endif ?>

            <?php if(settings()->audits->available_tests->social_links): ?>
                <div id="social_links" class="row">
                    <div class="col-12 col-xl-4 mb-2 mb-xl-0">
                        <div>
                            <span data-toggle="tooltip" title="<?= l('audits.informational_test') ?>"><i class="fas fa-fw fa-sm fa-info-circle text-info mr-1"></i></span>
                            <span class="font-weight-bold"><?= l('audits.test.social_links') ?></span>
                        </div>
                    </div>

                    <div class="col-12 col-xl-8">
                        <?php if(!$this->user->plan_settings->audits_enabled_tests->social_links): ?>
                            <p class="small m-0">
                                <i class="fas fa-fw fa-question-circle mr-1"></i> <?=  l('global.info_message.plan_feature_no_access') ?>
                            </p>
                        <?php else: ?>
                            <?php if(!empty($data->archived_audit->data->social_links)): ?>
                                <div class="mb-3">
                                    <button class="btn btn-sm btn-block btn-gray-200 mb-2" type="button" data-toggle="collapse" data-target="#social_links_container" aria-expanded="false" aria-controls="social_links_container">
                                        <?= sprintf(l('audits.test.social_links_count'), '<strong>' . count((array) $data->archived_audit->data->social_links) . '</strong>') ?>
                                    </button>

                                    <div class="collapse" id="social_links_container">
                                        <div class="card card-body">
                                            <ol class="mb-0 pl-3 audit-ol">
                                                <?php foreach ($data->archived_audit->data->social_links as $social_link): ?>
                                                    <?php
                                                    $social_link_icon = match ($social_link->type) {
                                                        'facebook' => 'fab fa-facebook',
                                                        'twitter' => 'fab fa-x-twitter',
                                                        'x' => 'fab fa-x-twitter',
                                                        'instagram' => 'fab fa-instagram',
                                                        'linkedin' => 'fab fa-linkedin',
                                                        'youtube' => 'fab fa-youtube',
                                                        'pinterest' => 'fab fa-pinterest',
                                                        'tiktok' => 'fab fa-tiktok',
                                                        'threads' => 'fas fa-at',
                                                        'whatsapp' => 'fab fa-whatsapp',
                                                        'telegram' => 'fab fa-telegram',
                                                        'facebook_messenger' => 'fab fa-facebook-messenger',
                                                        'snapchat' => 'fab fa-snapchat',
                                                        'twitch' => 'fab fa-twitch',
                                                        'discord' => 'fab fa-discord',
                                                        'reddit' => 'fab fa-reddit',
                                                        'vk' => 'fab fa-vk',
                                                        'onlyfans' => 'fas fa-lock',
                                                        'bluesky' => 'fas fa-cloud',
                                                    };
                                                    ?>
                                                    ?>
                                                    <li class="mb-3">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <p class="m-0 font-size-small"><?= $social_link->text ? e($social_link->text) : l('audits.test.social_links_text_missing') ?></p>
                                                                <small class="text-muted"><a href="<?= e($social_link->href) ?>" target="_blank" rel="nofollow noreferrer"><?= e($social_link->href) ?></a></small>
                                                            </div>

                                                            <span class="font-weight-bold">
                                                                <i class="<?= $social_link_icon ?> fa-fw fa-lg"></i>
                                                            </span>
                                                        </div>
                                                    </li>
                                                <?php endforeach ?>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="mb-3 audit-issue-wrapper audit-issue-info">
                                    <p class="m-0 font-size-small"><?= l('audits.test.social_links.missing') ?></p>
                                    <small class="text-muted"><?= l('audits.test.social_links.missing_help') ?></small>
                                </div>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>
            <?php endif ?>

        </div>
    </div>

    <style>
        /* Audit search previews */
        .audit-search-preview-google-wrapper {
            background: #ffffff;
        }

        [data-theme-style="dark"] .audit-search-preview-google-wrapper {
            background: #101218;
        }

        .audit-search-preview-google-img-wrapper {
            background-color: #f1f3f4;
            border: 1px solid #dadce0;
            border-radius: 50%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            height: 26px;
            width: 26px;
            margin-right: 12px;
            flex-shrink: 0;
            vertical-align: middle;
        }

        .audit-search-preview-google-img {
            width: 18px;
            height: 18px;
        }

        .audit-search-preview-google-host {
            color: #202124;
            font-size: 14px;
        }

        [data-theme-style="dark"] .audit-search-preview-google-host {
            color: #dadce0;
        }

        .audit-search-preview-google-url {
            color: #4d5156;
            font-size: 12px;
        }

        [data-theme-style="dark"] .audit-search-preview-google-url {
            color: #bdc1c6;
        }

        .audit-search-preview-google-title {
            color: #1800ab;
            font-family: Arial, sans-serif;
            font-size: 20px;
            font-weight: 400;
            display: inline-block;
            line-height: 1.3;
            margin-bottom: 3px;
            padding-top: 5px;
        }

        [data-theme-style="dark"] .audit-search-preview-google-title {
            color: #7fa7ff;
        }

        .audit-search-preview-google-description {
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-weight: 400;
            line-height: 22px;
            color: #474747;
        }

        [data-theme-style="dark"] .audit-search-preview-google-description {
            color: #cdcdcd;
        }
    </style>

    <div class="card mb-4">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between">
                <span class="small font-weight-bold"><?= sprintf(l('audits.search_preview_x'), 'Google') ?></span>

                <span class="badge bg-primary-50 text-primary-700">
                    <i class="fab fa-fw fa-sm fa-google"></i>
                </span>
            </div>
        </div>

        <div class="card-body audit-search-preview-google-wrapper">
            <div class="d-flex flex-column" style="max-width: 650px;">
                <div class="d-flex align-items-center">
                    <div class="audit-search-preview-google-img-wrapper">
                        <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain($data->archived_audit->host) ?>" class="audit-search-preview-google-img" loading="lazy" />
                    </div>

                    <div class="d-flex flex-column">
                        <div class="audit-search-preview-google-host"><?= $data->archived_audit->opengraph->site_name ?? $data->archived_audit->host ?></div>
                        <div class="audit-search-preview-google-url"><?= $data->archived_audit->url ?></div>
                    </div>
                </div>

                <h3 class="audit-search-preview-google-title"><?= $data->archived_audit->data->title ? e($data->archived_audit->data->title) : l('audits.title_missing') ?></h3>

                <div class="audit-search-preview-google-description">
                    <?= $data->archived_audit->data->meta_description ? e($data->archived_audit->data->meta_description) : l('audits.meta_description_missing') ?>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Audit search previews */
        .audit-search-preview-bing-wrapper {
            background: #ffffff;
        }

        [data-theme-style="dark"] .audit-search-preview-bing-wrapper {
            background: #1b1a19;
        }

        .audit-search-preview-bing-img-wrapper {
            background-color: #f1f3f4;
            border: 1px solid #ddd;
            overflow: hidden;
            width: 26px;
            height: 26px;
            text-align: center;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
        }

        .audit-search-preview-bing-img {
            border-radius: 4px;
            width: 16px;
            height: 16px;
        }

        .audit-search-preview-bing-host {
            line-height: 18px;
            font-size: 14px;
            color: #444;
        }

        [data-theme-style="dark"] .audit-search-preview-bing-host {
            color: #edebe9;
        }

        .audit-search-preview-bing-url {
            line-height: 20px;
            font-size: 13px;
            color: #444;
        }

        [data-theme-style="dark"] .audit-search-preview-bing-url {
            color: #edebe9;
        }

        .audit-search-preview-bing-title {
            color: #4007a2;
            font-family: Arial, sans-serif;
            font-size: 20px;
            line-height: 28px;
            font-weight: 400;
            padding-top: 4px;
            margin: 0;
        }

        [data-theme-style="dark"] .audit-search-preview-bing-title {
            color: #82c7ff;
        }

        .audit-search-preview-bing-description {
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-weight: 400;
            line-height: 22px;
            color: #71777d;
        }

        [data-theme-style="dark"] .audit-search-preview-bing-description {
            color: #d2d0ce;
        }
    </style>

    <div class="card mb-4">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between">
                <span class="small font-weight-bold"><?= sprintf(l('audits.search_preview_x'), 'Bing') ?></span>

                <span class="badge bg-primary-50 text-primary-700">
                    <i class="fab fa-fw fa-sm fa-microsoft"></i>
                </span>
            </div>
        </div>

        <div class="card-body audit-search-preview-bing-wrapper">
            <div class="d-flex flex-column" style="max-width: 600px;">
                <div class="d-flex align-items-center">
                    <div class="audit-search-preview-bing-img-wrapper">
                        <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain($data->archived_audit->host) ?>" class="audit-search-preview-bing-img" loading="lazy" />
                    </div>

                    <div class="d-flex flex-column">
                        <div class="audit-search-preview-bing-host"><?= $data->archived_audit->opengraph->site_name ?? $data->archived_audit->host ?></div>
                        <div class="audit-search-preview-bing-url"><?= $data->archived_audit->url ?></div>
                    </div>
                </div>

                <h3 class="audit-search-preview-bing-title"><?= $data->archived_audit->data->title ? e($data->archived_audit->data->title) : l('audits.title_missing') ?></h3>

                <div class="audit-search-preview-bing-description">
                    <?= $data->archived_audit->data->meta_description ? e($data->archived_audit->data->meta_description) : l('audits.meta_description_missing') ?>
                </div>
            </div>
        </div>
    </div>


    <style>
        /* Audit search previews */
        .audit-search-preview-yandex-wrapper {
            background: #fbfbfb;
        }

        [data-theme-style="dark"] .audit-search-preview-yandex-wrapper {
            background: #111112;
        }

        .audit-search-preview-yandex-img {
            width: 16px;
            height: 16px;
            margin-right: 10px;
            margin-top: 15px;
        }

        .audit-search-preview-yandex-result-wrapper {
            background: #ffffff;
            padding: 12px 16px;
            border-radius: 16px;
            box-shadow: 0 4px 12px 0 rgba(0, 0, 0, .03);
        }

        [data-theme-style="dark"] .audit-search-preview-yandex-result-wrapper {
            background: #18181a;
        }

        .audit-search-preview-yandex-url {
            font-family: 'YS Text', -apple-system, BlinkMacSystemFont, Arial, Helvetica, sans-serif;
            font-size: 14px;
            font-weight: bold;
            line-height: 17px;
            color: #0b6301;
        }

        [data-theme-style="dark"] .audit-search-preview-yandex-url {
            color: rgba(255, 255, 255, .87);
        }

        .audit-search-preview-yandex-title {
            color: #000080;
            font-family: 'YS Text', -apple-system, BlinkMacSystemFont, Arial, Helvetica, sans-serif;
            font-size: 18px;
            line-height: 24px;
            font-weight: 400;
            margin: 0;
        }

        [data-theme-style="dark"] .audit-search-preview-yandex-title {
            color: #8cb3dd;
        }

        .audit-search-preview-yandex-description {
            margin-top: 2px;
            font-family: 'YS Text', -apple-system, BlinkMacSystemFont, Arial, Helvetica, sans-serif;
            font-size: 14px;
            line-height: 18px;
            color: #333;
        }

        [data-theme-style="dark"] .audit-search-preview-yandex-description {
            color: #c5c5c6;
        }
    </style>

    <div class="card">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between">
                <span class="small font-weight-bold"><?= sprintf(l('audits.search_preview_x'), 'Yandex') ?></span>

                <span class="badge bg-primary-50 text-primary-700">
                    <i class="fab fa-fw fa-sm fa-yandex"></i>
                </span>
            </div>
        </div>

        <div class="card-body audit-search-preview-yandex-wrapper">
            <div class="d-flex" style="max-width: 600px;">
                <img referrerpolicy="no-referrer" src="<?= get_favicon_url_from_domain($data->archived_audit->host) ?>" class="audit-search-preview-yandex-img" loading="lazy" />

                <div class="d-flex flex-column audit-search-preview-yandex-result-wrapper">
                    <h3 class="audit-search-preview-yandex-title"><?= $data->archived_audit->data->title ? e($data->archived_audit->data->title) : l('audits.title_missing') ?></h3>
                    <div class="audit-search-preview-yandex-url"><?= remove_url_protocol_from_url($data->archived_audit->url) ?></div>
                    <div class="audit-search-preview-yandex-description">
                        <?= $data->archived_audit->data->meta_description ? e($data->archived_audit->data->meta_description) : l('audits.meta_description_missing') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(is_logged_in()): ?>
<div class="modal fade" id="report_logo_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-file-pdf mr-2 text-primary"></i> Customize PDF Report Branding</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                <div class="row">
                    <!-- Form side -->
                    <div class="col-12 col-md-6 border-right">
                        <h6 class="font-weight-bold text-muted text-uppercase mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">Branding Details</h6>
                        
                        <div class="form-group">
                            <label class="small font-weight-bold text-muted" for="wl_logo_file">Upload Logo</label>
                            <input type="file" id="wl_logo_file" accept="image/*" class="form-control-file">
                            <small class="form-text text-muted">Recommended size: 200x50px</small>
                        </div>
                        
                        <div class="form-group">
                            <label class="small font-weight-bold text-muted" for="wl_company_input">Company Name</label>
                            <input type="text" id="wl_company_input" class="form-control form-control-sm" placeholder="e.g. My Agency">
                        </div>

                        <div class="form-group">
                            <label class="small font-weight-bold text-muted" for="wl_domain_input">Domain Name</label>
                            <input type="text" id="wl_domain_input" class="form-control form-control-sm" placeholder="e.g. agency.com">
                        </div>

                        <div class="form-group">
                            <label class="small font-weight-bold text-muted" for="wl_email_input">Email ID</label>
                            <input type="email" id="wl_email_input" class="form-control form-control-sm" placeholder="e.g. contact@agency.com">
                        </div>

                        <div class="form-group">
                            <label class="small font-weight-bold text-muted" for="wl_phone_input">Mobile Number</label>
                            <input type="text" id="wl_phone_input" class="form-control form-control-sm" placeholder="e.g. +1 234 567 890">
                        </div>
                    </div>

                    <!-- Live Preview side -->
                    <div class="col-12 col-md-6 d-flex flex-column justify-content-between">
                        <div>
                            <h6 class="font-weight-bold text-muted text-uppercase mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">Live PDF Report Page Preview</h6>
                            
                            <div class="border rounded shadow-sm bg-white p-3 d-flex flex-column justify-content-between" id="wl_page_preview_box" style="height: 380px; font-family: sans-serif; overflow: hidden; position: relative;">
                                <!-- Header -->
                                <div class="d-flex justify-content-between align-items-center pb-2 border-bottom mb-2" style="font-size: 10px;">
                                    <div id="wl_preview_logo_container" style="max-height: 45px;">
                                        <span id="wl_preview_company_text" class="font-weight-bold" style="font-size: 12px;">SiteRankify</span>
                                        <img id="wl_preview_logo_img" src="" style="max-height: 45px; display: none;" />
                                    </div>
                                    <div class="text-right text-muted" style="font-size: 8px; line-height: 1.2;">
                                        <span id="wl_preview_email_block"><i class="fas fa-envelope mr-1"></i> <span id="wl_preview_email"><?= get_support_email() ?></span></span><br>
                                        <span id="wl_preview_phone_block"><i class="fas fa-phone mr-1"></i> <span id="wl_preview_phone"></span></span>
                                    </div>
                                </div>
                                
                                <!-- Scaled Report Content Preview -->
                                <div id="wl_content_preview_area" class="flex-grow-1 my-2 border rounded p-2 bg-light" style="overflow-y: auto; overflow-x: hidden; position: relative; height: 180px; max-height: 180px;">
                                    <!-- We will inject a scaled-down clone of the main card here using JS -->
                                </div>
                                
                                <!-- Footer -->
                                <div class="pt-2 border-top" style="font-size: 9px; color: #718096;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div id="wl_preview_footer_logo_container" style="max-height: 45px;" class="mb-1">
                                                <span id="wl_preview_footer_company_text" class="font-weight-bold text-dark" style="font-size: 10px;">SiteRankify</span>
                                                <img id="wl_preview_footer_logo_img" src="" style="max-height: 45px; display: none;" />
                                            </div>
                                            &copy; <span id="wl_preview_year">2026</span> <span id="wl_preview_footer_company">SiteRankify</span>. All rights reserved.
                                        </div>
                                        <div id="wl_preview_domain_text" class="font-weight-bold text-primary" style="font-size: 9px;">siteanalysistool.com</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer bg-light d-flex justify-content-between">
                <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Cancel</button>
                <div>
                    <button type="button" id="wl_reset_btn" class="btn btn-light font-weight-bold mr-2">Reset</button>
                    <button type="button" id="wl_save_download_btn" class="btn btn-primary font-weight-bold"><i class="fas fa-file-download mr-1"></i> Save & Download PDF</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="pdf_full_preview_modal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content border-0 shadow-lg" style="background: #f8f9fa;">
            <div class="modal-header bg-white border-bottom">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-file-pdf mr-2 text-primary"></i> PDF Report Live Preview</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body overflow-auto" style="max-height: 75vh; padding: 30px;">
                <div class="shadow-sm bg-white mx-auto p-4 border rounded" id="pdf_full_preview_document" style="max-width: 800px; min-height: 600px; font-family: sans-serif; position: relative; color: #333; overflow-x: hidden;">
                    <!-- Dynamically populated white-labeled header, report content clone, and footer -->
                </div>
            </div>
            <div class="modal-footer bg-white border-top d-flex justify-content-between">
                <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Close Preview</button>
                <button type="button" id="pdf_full_preview_download_btn" class="btn btn-primary font-weight-bold"><i class="fas fa-file-download mr-1"></i> Save & Download PDF</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="download_logo_ask_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-file-pdf mr-2 text-danger"></i> Custom PDF Branding</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                $has_logo_permission = $php_has_logo_permission;
                ?>
                <p class="text-muted small mb-4">Would you like to download this PDF report with your custom white-label branding, or download the standard report immediately?</p>
                
                <?php if($has_logo_permission): ?>
                <div class="p-3 bg-light rounded mb-4 border">
                    <button type="button" id="modal_download_custom_btn" class="btn btn-info btn-block font-weight-bold py-2"><i class="fas fa-magic mr-1"></i> Download with Custom Branding</button>
                </div>
                <?php else: ?>
                <div class="p-3 bg-light rounded mb-4 border text-center">
                    <i class="fas fa-lock fa-2x text-warning mb-3"></i>
                    <h6 class="font-weight-bold small text-uppercase text-muted mb-1">Custom Logo Locked</h6>
                    <p class="small text-muted mb-3">Custom logo branding is included in Monthly/Yearly plans, or as a one-time addon for Premier Plan.</p>
                    <a href="<?= url('plan') ?>" class="btn btn-sm btn-primary btn-block font-weight-bold text-white">
                        View Plans
                    </a>
                </div>
                <?php endif ?>
            </div>
            <div class="modal-footer bg-light justify-content-between">
                <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Cancel</button>
                <button type="button" id="modal_download_standard_btn" class="btn btn-primary font-weight-bold">Download Standard PDF</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    let audit_download_limit_reached = false; // Limits only enforced on generation, not on archived views

    function trackDownload(withLogo = false) {
        $.ajax({
            type: 'POST',
            url: '<?= url('audit/download_track') ?>',
            data: {
                token: '<?= \Altum\Csrf::get() ?>',
                audit_id: '<?= $data->audit->audit_id ?>',
                audit_url: '<?= $data->audit->url ?>',
                with_logo: withLogo ? 1 : 0
            },
            dataType: 'json'
        });
    }

    function downloadReportTrigger() {
        <?php if(!is_logged_in()): ?>
            $('#login_modal').modal('show');
            return;
        <?php endif; ?>

        if(audit_download_limit_reached) {
            $('#limit_modal').modal('show');
            return;
        }

        <?php if($php_has_logo_permission): ?>
            $('#download_logo_ask_modal').modal('show');
        <?php else: ?>
            downloadReport(false);
        <?php endif; ?>
    }

    function downloadReport(withLogo = true) {
        const currentScrollX = window.pageXOffset || document.documentElement.scrollLeft;
        const currentScrollY = window.pageYOffset || document.documentElement.scrollTop;
        
        // Force instant scroll to top-left to avoid smooth scroll coordinate lag in html2canvas
        const scrollResetStyle = document.createElement('style');
        scrollResetStyle.id = 'pdf-scroll-reset-style';
        scrollResetStyle.innerHTML = 'html, body { scroll-behavior: auto !important; }';
        document.head.appendChild(scrollResetStyle);
        
        window.scrollTo(0, 0);

        const siteName = '<?= addslashes(remove_url_protocol_from_url($data->archived_audit->url)) ?>';
        const filename = 'seo-report-' + siteName.replace(/[^a-z0-9]/gi, '-').toLowerCase() + '.pdf';

        // Create a professional loading overlay
        const overlay = document.createElement('div');
        overlay.id = 'pdf-loading-overlay';
        overlay.innerHTML = `
            <div style='position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(12px) saturate(180%); z-index: 9999; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;'>
                <div style='width: 100%; max-width: 450px; padding: 30px; background: white; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1);'>
                    <div class='mb-4'>
                        <div class='spinner-border text-primary' role='status' style='width: 3.5rem; height: 3.5rem; border-width: 0.25rem;'></div>
                    </div>
                    <div id='pdf-progress-text' class='display-4 font-weight-bold text-primary mb-2'>0%</div>
                    <div class='progress mb-4' style='height: 12px; border-radius: 6px; background: #f0f2f5; overflow: hidden; box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);'>
                        <div id='pdf-progress-bar' class='progress-bar bg-gradient-primary' role='progressbar' style='width: 0%; transition: width 0.3s ease;'></div>
                    </div>
                    <h3 class='h4 font-weight-bold text-dark mb-2'>Generating Premium Report</h3>
                    <p id='pdf-loading-status' class='text-muted small px-3 font-italic'>Initializing PDF engine...</p>
                </div>
            </div>
        `;
        document.body.appendChild(overlay);

        const element = document.querySelector('.container');
        const clone = element.cloneNode(true);

        const custom_logo_url = '<?= is_logged_in() && !empty($this->user->preferences->white_label_report_logo) ? \Altum\Uploads::get_full_url('users') . $this->user->preferences->white_label_report_logo : '' ?>';
        const global_logo_url = '<?= isset(settings()->main->original_report_logo_full_url) ? settings()->main->original_report_logo_full_url : (isset(settings()->main->report_logo_full_url) ? settings()->main->report_logo_full_url : (settings()->main->{'logo_' . \Altum\ThemeStyle::get()} != '' ? settings()->main->{'logo_' . \Altum\ThemeStyle::get() . '_full_url'} : '')) ?>';

        const selectorsToRemove = [
            '.sticky-sidebar', '.d-print-none', '.scroll-to-top', '#download-report-btn', '.app-menu', 
            '.solution-lock-cta', '.solution-lock-overlay', '.headings-toggle', '.show-more-links', 
            '.alert', '.audit-archived-audits-wrapper', 
            '.audit-archived-audits-wrapper-left', '.audit-archived-audits-wrapper-right', '.d-print-block'
        ];
        
        <?php if(!$has_full_access): ?>
        selectorsToRemove.push('.solution-lock-content');
        <?php endif ?>
        
        selectorsToRemove.forEach(sel => {
            clone.querySelectorAll(sel).forEach(el => el.remove());
        });

        clone.classList.remove('container');
        clone.style.width = '100%';
        clone.style.maxWidth = '100%';
        clone.style.margin = '0';
        clone.style.padding = '0';

        clone.querySelectorAll('.container').forEach(el => {
            el.style.width = '100%';
            el.style.maxWidth = '100%';
            el.style.margin = '0';
            el.style.padding = '0';
        });

        clone.querySelectorAll('.row').forEach(el => {
            el.style.display = 'block';
            el.style.marginLeft = '0';
            el.style.marginRight = '0';
            el.style.width = '100%';
        });

        clone.querySelectorAll('.col-12.col-lg-3').forEach(el => {
            if(!el.querySelector(':scope > *')) {
                el.remove();
            }
        });

        const overview = clone.querySelector('#overview');
        if(overview) {
            overview.style.display = 'flex';
            overview.style.width = '100%';
            overview.style.marginLeft = '0';
            overview.style.marginRight = '0';
            overview.style.alignItems = 'center';
            overview.style.pageBreakAfter = 'auto';
            overview.style.breakAfter = 'auto';
        }

        clone.querySelectorAll('.col-12.col-lg-9').forEach(el => {
            el.style.flex = '0 0 100%';
            el.style.maxWidth = '100%';
            el.style.width = '100%';
        });

        clone.querySelectorAll('.pdf-monitor-col').forEach(el => {
            el.style.flex = '0 0 34%';
            el.style.maxWidth = '34%';
            el.style.textAlign = 'center';

            const monitor = el.querySelector('.monitor-mockup');
            if(monitor) {
                monitor.style.maxWidth = '320px';
                monitor.style.margin = '0 auto';
            }

            el.querySelectorAll('img').forEach(img => {
                img.crossOrigin = 'anonymous';
                img.style.maxWidth = '100%';
            });
        });

        clone.querySelectorAll('.pdf-stats-col').forEach(el => {
            el.style.flex = '0 0 41%';
            el.style.maxWidth = '41%';
            el.style.paddingLeft = '1.5rem';
            el.style.paddingRight = '1.5rem';
        });

        clone.querySelectorAll('.pdf-gauge-col').forEach(el => {
            el.style.flex = '0 0 25%';
            el.style.maxWidth = '25%';
            el.style.textAlign = 'center';
        });

        clone.querySelectorAll('.collapse').forEach(el => {
            el.classList.add('show');
            el.style.display = 'block';
        });

        clone.querySelectorAll('.test-row').forEach(el => {
            el.style.position = 'relative';
        });

        const logo_to_use = withLogo 
            ? (activeWhiteLabelBranding && activeWhiteLabelBranding.logo ? activeWhiteLabelBranding.logo : '<?= is_logged_in() && !empty($this->user->preferences->white_label_report_logo) ? \Altum\Uploads::get_full_url('users') . $this->user->preferences->white_label_report_logo : '' ?>') 
            : global_logo_url;

        const company_to_use = withLogo 
            ? (activeWhiteLabelBranding && activeWhiteLabelBranding.company ? activeWhiteLabelBranding.company : '<?php echo addslashes($php_has_logo_permission && is_logged_in() && !empty($this->user->preferences->white_label_company_name) ? $this->user->preferences->white_label_company_name : settings()->main->title); ?>') 
            : '<?php echo addslashes(settings()->main->title); ?>';

        const domain_to_use = withLogo 
            ? (activeWhiteLabelBranding && activeWhiteLabelBranding.domain ? activeWhiteLabelBranding.domain : '<?php echo $php_has_logo_permission && is_logged_in() && !empty($this->user->preferences->white_label_domain_name) ? addslashes($this->user->preferences->white_label_domain_name) : remove_url_protocol_from_url(url()); ?>') 
            : '<?php echo remove_url_protocol_from_url(url()); ?>';

        const email_to_use = withLogo 
            ? (activeWhiteLabelBranding && activeWhiteLabelBranding.email ? activeWhiteLabelBranding.email : '<?php echo $php_has_logo_permission && is_logged_in() && !empty($this->user->preferences->white_label_email) ? addslashes($this->user->preferences->white_label_email) : (!empty(settings()->business->email) ? settings()->business->email : (!empty(settings()->main->email) ? settings()->main->email : get_support_email())); ?>') 
            : '<?php echo get_support_email(); ?>';

        const phone_to_use = withLogo 
            ? (activeWhiteLabelBranding && activeWhiteLabelBranding.phone ? activeWhiteLabelBranding.phone : '<?php echo $php_has_logo_permission && is_logged_in() && !empty($this->user->preferences->white_label_phone) ? addslashes($this->user->preferences->white_label_phone) : (!empty(settings()->business->phone) ? settings()->business->phone : (!empty(settings()->main->phone) ? settings()->main->phone : "")); ?>') 
            : '<?php echo !empty(settings()->business->phone) ? addslashes(settings()->business->phone) : (!empty(settings()->main->phone) ? addslashes(settings()->main->phone) : ""); ?>';

        const headerHtml = `
            <table style="width: 100%; table-layout: fixed; border-bottom: 2px solid #edf2f7; padding-bottom: 15px; margin-bottom: 25px; font-family: sans-serif; border-collapse: collapse;">
                <tr>
                    <td style="vertical-align: middle; text-align: left; width: 45%;">
                        <a href="<?= url() ?>" style="text-decoration: none; color: #000;">
                            ${logo_to_use ? `
                                <img src="${logo_to_use}" style="max-height: 55px; display: inline-block; vertical-align: middle;" />
                            ` : `
                                <span style="font-size: 20px; font-weight: 800; display: inline-block; vertical-align: middle; font-family: sans-serif; max-width: 100%; overflow-wrap: anywhere;">${company_to_use}</span>
                            `}
                        </a>
                    </td>
                    <td style="vertical-align: middle; text-align: right; width: 55%; font-size: 10.5px; line-height: 1.45; font-weight: 600; color: #495057; font-family: sans-serif;">
                        ${email_to_use ? `
                            <span style="display: block; margin-bottom: 2px; overflow-wrap: anywhere;"><svg width="11" height="11" fill="#495057" style="display: inline-block; vertical-align: middle; margin-right: 4px;" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg> ${email_to_use}</span>
                        ` : ''}
                        ${phone_to_use ? `
                            <span style="display: block; white-space: nowrap;"><svg width="11" height="11" fill="#495057" style="display: inline-block; vertical-align: middle; margin-right: 4px;" viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg> ${phone_to_use}</span>
                        ` : ''}
                    </td>
                </tr>
            </table>
        `;

        const footerHtml = `
            <table style="width: 100%; border-top: 2px solid #edf2f7; padding-top: 20px; margin-top: 40px; font-family: sans-serif; color: #718096; font-size: 12px; border-collapse: collapse;">
                <tr>
                    <td style="text-align: left; vertical-align: middle; width: 50%;">
                        ${logo_to_use ? `
                            <img src="${logo_to_use}" style="max-height: 50px; margin-bottom: 8px; display: block;" />
                        ` : `
                            <span style="font-size: 14px; font-weight: 800; margin-bottom: 8px; display: block; color: #000;">${company_to_use}</span>
                        `}
                        &copy; ${new Date().getFullYear()} ${company_to_use}. All rights reserved.
                    </td>
                    <td style="text-align: right; vertical-align: middle; width: 50%;">
                        ${domain_to_use ? `
                            <a href="https://${domain_to_use}" target="_blank" style="text-decoration: none; color: #0056b3; font-weight: bold;">${domain_to_use}</a>
                        ` : `
                            <?php
                            $social_svgs = [
                                'facebook' => '<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>',
                                'twitter' => '<path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>',
                                'x' => '<path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>',
                                'instagram' => '<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>',
                                'linkedin' => '<path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>',
                                'youtube' => '<path d="M23.498 6.163a3.003 3.003 0 00-2.11-2.11C19.518 3.545 12 3.545 12 3.545s-7.518 0-9.388.508a3.003 3.003 0 00-2.11 2.11C0 8.033 0 12 0 12s0 3.967.502 5.837a3.003 3.003 0 002.11 2.11c1.87.508 9.388.508 9.388.508s7.518 0 9.388-.508a3.003 3.003 0 002.11-2.11C24 15.967 24 12 24 12s0-3.967-.502-5.837zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>',
                                'pinterest' => '<path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.946-.199-2.399.041-3.43.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.27 1.042-1.002 2.35-1.492 3.146 1.124.347 2.317.535 3.554.535 6.621 0 11.987-5.366 11.987-11.987C23.999 5.368 18.636 0 12.017 0z"/>',
                                'tiktok' => '<path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.03 1.62 4.15.93.98 2.19 1.6 3.55 1.78.01 1.25.01 2.51.01 3.76-.94-.03-1.87-.27-2.75-.63-.99-.41-1.85-1.04-2.53-1.82-.04 2.22-.01 4.45-.04 6.67-.06 1.48-.46 2.96-1.2 4.23-1.02 1.78-2.87 3.06-4.89 3.48-1.54.33-3.17.21-4.64-.34-1.92-.72-3.48-2.27-4.14-4.18-.73-2.11-.47-4.52.73-6.4 1.23-1.9 3.39-3.08 5.67-3.08.02 1.4.01 2.81.02 4.22-.72-.02-1.46.16-2.09.52-.77.44-1.31 1.2-1.5 2.07-.26 1.22.18 2.53 1.11 3.28.84.67 1.95.89 2.99.6 1.13-.31 2.04-1.24 2.27-2.39.14-.68.12-1.38.12-2.07-.01-4.43 0-8.87-.02-13.31z"/>',
                                'whatsapp' => '<path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.16 5.348 5.511 0 12.073 0c3.179.001 6.167 1.238 8.414 3.486 2.247 2.248 3.481 5.239 3.48 8.42-.004 6.557-5.355 11.907-11.914 11.907-2.012-.001-3.993-.513-5.753-1.486L.057 24zm6.611-3.83c1.696.993 3.385 1.517 5.39 1.519 5.513 0 10.016-4.497 10.02-10.011.002-2.673-1.037-5.184-2.93-7.078-1.892-1.893-4.405-2.934-7.087-2.936-5.523 0-10.024 4.5-10.028 10.015-.002 2.053.538 4.053 1.565 5.795L2.613 21.4l4.055-1.23z"/>',
                                'telegram' => '<path d="M23.977 2.585a.948.948 0 00-.733-.284L.79 10.887a.956.956 0 00-.095 1.777l5.244 1.637 2.022 6.471a.957.957 0 001.62.383l2.871-2.775 5.568 4.108a.952.952 0 001.5-.54L23.993 3.479a.952.952 0 00-.016-.894zm-16.14 10.2L19.26 6.32a.318.318 0 01.442.456L8.435 15.011l-.253 2.766-.345-4.992z"/>'
                            ];
                            foreach(require APP_PATH . 'includes/admin_socials.php' as $key => $value): ?>
                                <?php if(isset(settings()->socials->{$key}) && !empty(settings()->socials->{$key})): ?>
                                    <a href="<?= sprintf($value['format'], settings()->socials->{$key}) ?>" style="text-decoration: none; color: #718096; margin-left: 12px; display: inline-block; vertical-align: middle;">
                                        <?php if(isset($social_svgs[$key])): ?>
                                            <svg width="14" height="14" fill="#718096" style="display: inline-block; vertical-align: middle;" viewBox="0 0 24 24">
                                                <?= $social_svgs[$key] ?>
                                            </svg>
                                        <?php else: ?>
                                            <i class="<?= $value['icon'] ?> fa-fw" style="font-size: 14px;"></i>
                                        <?php endif ?>
                                    </a>
                                <?php endif ?>
                            <?php endforeach ?>
                        `}
                    </td>
                </tr>
            </table>
        `;

        const headerContainer = document.createElement('div');
        headerContainer.innerHTML = headerHtml;

        const footerContainer = document.createElement('div');
        footerContainer.innerHTML = footerHtml;

        const spacer = document.createElement('div');
        spacer.style.height = '40px';

        const wrapper = document.createElement('div');
        wrapper.id = 'pdf-report-wrapper';
        wrapper.style.padding = '0';
        wrapper.style.background = 'white';
        
        // Capture at the report's desktop width, then let jsPDF scale it to A4.
        wrapper.style.width = '1100px';
        
        // Append the real report clone so typography, cards and spacing match the screen.
        wrapper.appendChild(clone);
        wrapper.appendChild(spacer);

        const printOverrideStyle = document.createElement('style');
        printOverrideStyle.innerHTML = `
            html, body {
                display: block !important;
                height: auto !important;
                overflow: visible !important;
                max-height: none !important;
                margin: 0 !important;
                padding: 0 !important;
                background: #ffffff !important;
            }
            #pdf-report-wrapper *, #pdf-report-wrapper *::before, #pdf-report-wrapper *::after,
            .pdf-report-clone *, .pdf-report-clone *::before, .pdf-report-clone *::after {
                transition: none !important;
                animation: none !important;
                box-shadow: none !important;
                text-shadow: none !important;
                -webkit-box-shadow: none !important;
            }
            #pdf-report-wrapper *:not(svg):not(circle):not(path):not(rect):not(g),
            .pdf-report-clone *:not(svg):not(circle):not(path):not(rect):not(g) {
                transform: none !important;
            }
            #pdf-report-wrapper svg, #pdf-report-wrapper circle, #pdf-report-wrapper path, #pdf-report-wrapper rect, #pdf-report-wrapper g,
            .pdf-report-clone svg, .pdf-report-clone circle, .pdf-report-clone path, .pdf-report-clone rect, .pdf-report-clone g {
                filter: none !important;
            }
            /* Crucial rule: Force browsers to print all backgrounds and colors exactly */
            #pdf-report-wrapper, #pdf-report-wrapper *, .pdf-report-clone, .pdf-report-clone * {
                box-sizing: border-box !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            #pdf-report-wrapper, .pdf-report-clone {
                overflow: hidden !important;
                background: #ffffff !important;
            }
            /* Ensure cards have clean borders and pure white background without gray shadows */
            .card, .border, .shadow-sm, .rounded, [class*="shadow"], [class*="card"] {
                border: 1px solid #e2e8f0 !important;
                background: #ffffff !important;
                background-color: #ffffff !important;
                box-shadow: none !important;
            }
            #pdf-report-wrapper p,
            #pdf-report-wrapper div,
            #pdf-report-wrapper span,
            #pdf-report-wrapper td,
            #pdf-report-wrapper th,
            #pdf-report-wrapper a,
            .pdf-report-clone p,
            .pdf-report-clone div,
            .pdf-report-clone span,
            .pdf-report-clone td,
            .pdf-report-clone th,
            .pdf-report-clone a {
                overflow-wrap: anywhere !important;
                word-wrap: break-word !important;
            }
            .container { max-width: 100% !important; width: 100% !important; padding-left: 15px !important; padding-right: 15px !important; margin: 0 auto !important; }
            .row { display: flex !important; flex-wrap: wrap !important; }
            .col-lg-9 { flex: 0 0 100% !important; max-width: 100% !important; }
            .test-row { display: flex !important; padding: 1.5rem !important; }
            .test-row > .flex-grow-1 { min-width: 0 !important; max-width: 100% !important; }
            .table-responsive { overflow: visible !important; max-width: 100% !important; }
            .html2pdf__page-break { height: 0 !important; display: block !important; page-break-before: always !important; break-before: page !important; }
            .pdf-keep-together { page-break-inside: avoid !important; break-inside: avoid !important; }
            #keyword_consistency, #in_page_links { page-break-inside: auto !important; break-inside: auto !important; }
            h1, h2, h3, h4, h5, h6, .p-3.bg-white.border.rounded, .p-4.bg-white.border.rounded, .audit-module { page-break-inside: avoid !important; break-inside: avoid !important; }
            
            .section-header { 
                page-break-after: avoid !important; 
                break-after: avoid !important; 
                margin-top: 18px !important;
                margin-bottom: 12px !important;
            }
            #overview { 
                display: flex !important; 
                align-items: center !important;
                clear: both !important; 
                margin-left: 0 !important; 
                margin-right: 0 !important; 
                width: 100% !important; 
                page-break-after: auto !important; 
                break-after: auto !important;
                padding-bottom: 1.5rem !important;
            }
            .pdf-monitor-col { flex: 0 0 34% !important; max-width: 34% !important; box-sizing: border-box !important; text-align: center !important; }
            .pdf-monitor-col .monitor-mockup { max-width: 320px !important; margin: 0 auto !important; }
            .pdf-stats-col { flex: 0 0 41% !important; max-width: 41% !important; box-sizing: border-box !important; padding-left: 1.5rem !important; padding-right: 1.5rem !important; }
            .pdf-gauge-col { flex: 0 0 25% !important; max-width: 25% !important; box-sizing: border-box !important; text-align: center !important; }
            
            table { width: 100% !important; table-layout: fixed !important; }
            th, td { white-space: normal !important; vertical-align: middle !important; }
            tr { page-break-inside: avoid !important; break-inside: avoid !important; }
            #keyword_consistency table { font-size: 12px !important; line-height: 1.35 !important; }
            #keyword_consistency th:first-child,
            #keyword_consistency td:first-child { width: 52% !important; }
            #keyword_consistency th:not(:first-child),
            #keyword_consistency td:not(:first-child) { width: 16% !important; text-align: center !important; }
            .bg-light, .alert, [style*="background-color"] { overflow: hidden !important; }
 
            .mobile-mockup { max-width: 160px !important; margin: 0 auto !important; page-break-inside: avoid !important; break-inside: avoid !important; }
 
            .mb-5 { margin-bottom: 15px !important; }
            .mt-5 { margin-top: 15px !important; }
            .pb-4 { padding-bottom: 10px !important; }
            .navbar { display: flex !important; }
            .navbar-collapse { display: flex !important; }
            .navbar-nav { display: flex !important; flex-direction: row !important; }
            .nav-item { display: block !important; margin-left: 15px !important; }
            .app-footer { display: block !important; }
            @media print {
                .navbar { display: flex !important; }
                .navbar-collapse { display: flex !important; }
                .navbar-nav { display: flex !important; flex-direction: row !important; }
                .nav-item { display: block !important; margin-left: 15px !important; }
                .app-footer { display: block !important; }
            }
        `;
        document.head.appendChild(printOverrideStyle);
 
        wrapper.style.position = 'absolute';
        wrapper.style.left = '0';
        wrapper.style.top = '0';
        wrapper.style.zIndex = '500'; // Positioned behind the high-priority loading overlay (z-index 9999)
        wrapper.style.height = 'auto';
        wrapper.style.overflow = 'visible';
        wrapper.style.display = 'block';
        document.body.prepend(wrapper);

        const calculatedWidth = Math.ceil(wrapper.scrollWidth || wrapper.offsetWidth || 1100);
        wrapper.style.width = calculatedWidth + 'px';

        wrapper.querySelectorAll('.test-row, .audit-module, .p-3.bg-white.border.rounded, .p-4.bg-white.border.rounded, .mobile-mockup').forEach(el => {
            if((el.scrollHeight || el.offsetHeight) < 1200) {
                el.classList.add('pdf-keep-together');
            }
        });

        const margin = 10;
        const usableWidth = 190; // A4 width (210) minus 20 margin
        const usableHeight = 277; // A4 height (297) minus 20 margin
        const chunkHeight = Math.floor((calculatedWidth * usableHeight) / usableWidth);
        const headerHeight = 85;
        const footerHeight = 105;
        const pageContentHeight = chunkHeight - headerHeight - footerHeight;

        const printablePageHeight = pageContentHeight;
        const getOffsetTop = (el) => {
            let top = 0;
            let current = el;
            while(current && current !== wrapper) {
                top += current.offsetTop || 0;
                current = current.offsetParent;
            }
            return top;
        };

        wrapper.querySelectorAll('#overview, .section-header, .test-row, tr, .p-3.bg-white.border.rounded, .p-4.bg-white.border.rounded, .bg-light.border.rounded, .solution-lock').forEach(el => {
            const elementHeight = el.offsetHeight || el.scrollHeight || 0;
            if(!elementHeight) return;

            const currentTop = getOffsetTop(el);
            const currentPageIndex = Math.floor(currentTop / printablePageHeight);
            const pageEnd = (currentPageIndex + 1) * printablePageHeight;

            // If the element's bottom overflows the current page boundary:
            if(currentTop + elementHeight > pageEnd) {
                // If it can fit on a single page, push it to the next page using a spacer
                if(elementHeight <= printablePageHeight) {
                    const spacerHeight = pageEnd - currentTop;
                    if(spacerHeight > 0 && spacerHeight < printablePageHeight) {
                        if(el.tagName.toLowerCase() === 'tr') {
                            const spacerTr = document.createElement('tr');
                            spacerTr.className = 'pdf-page-spacer-tr';
                            const colSpan = el.cells.length || 10;
                            spacerTr.innerHTML = `<td colspan="${colSpan}" style="height: ${spacerHeight}px; border: none !important; padding: 0 !important; margin: 0 !important; background: #ffffff !important; background-color: #ffffff !important;"></td>`;
                            el.parentNode.insertBefore(spacerTr, el);
                        } else {
                            const spacer = document.createElement('div');
                            spacer.className = 'pdf-page-spacer';
                            spacer.style.height = spacerHeight + 'px';
                            spacer.style.width = '100%';
                            spacer.style.display = 'block';
                            el.parentNode.insertBefore(spacer, el);
                        }
                    }
                }
            }
        });

        // Measure actual rendered size after appending manual page breaks.
        const calculatedHeight = wrapper.scrollHeight || wrapper.offsetHeight || 6000;

        // Dynamically compute the maximum safe scale to prevent exceeding browser canvas limits (32767px height)
        const maxCanvasDim = 32767;
        let scale = 2; // Default scale is 2
        if (calculatedHeight * scale > maxCanvasDim) {
            scale = Math.floor((maxCanvasDim / calculatedHeight) * 10) / 10;
            if (scale < 1.0) {
                scale = 1.0;
            }
        }

        const opt = {
            margin: [10, 10, 10, 10],
            filename: filename,
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { 
                scale: scale, 
                useCORS: true, 
                letterRendering: true,
                scrollX: 0,
                scrollY: 0,
                windowWidth: calculatedWidth,
                windowHeight: calculatedHeight,
                height: calculatedHeight,
                width: calculatedWidth
            },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
            pagebreak: { mode: ['css', 'legacy'], avoid: ['.pdf-keep-together', '.audit-module', 'tr'] }
        };

        const addHeaderFooter = (pdf) => {
            return;
            const totalPages = pdf.internal.getNumberOfPages();
            const pageWidth = pdf.internal.pageSize.getWidth();
            const pageHeight = pdf.internal.pageSize.getHeight();
            
            for (let i = 1; i <= totalPages; i++) {
                pdf.setPage(i);
                
                // Professional design: Do not print headers or footers on the cover page (Page 1)
                if (i === 1) {
                    continue;
                }
                
                // --- HEADER ---
                pdf.setFontSize(8);
                pdf.setFont('helvetica', 'normal');
                pdf.setTextColor(100, 110, 120);
                pdf.text('<?= settings()->main->title ?>', 12, 8);
                pdf.text('SEO Audit for ' + siteName, pageWidth - 12, 8, {align: 'right'});
                
                // Header Line
                pdf.setDrawColor(220, 225, 230);
                pdf.setLineWidth(0.2);
                pdf.line(10, 10, pageWidth - 10, 10);
                
                // --- FOOTER ---
                pdf.line(10, pageHeight - 10, pageWidth - 10, pageHeight - 10);
                
                const dateStr = new Date().toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' });
                pdf.text(dateStr, 12, pageHeight - 6);
                pdf.text('<?= remove_url_protocol_from_url(url()) ?>', pageWidth - 12, pageHeight - 6, {align: 'right'});
                pdf.text('Page ' + i + ' of ' + totalPages, pageWidth / 2, pageHeight - 6, {align: 'center'});
            }
        };

        const cleanupPdfDom = () => {
            if(document.body.contains(wrapper)) document.body.removeChild(wrapper);
            if(document.getElementById('pdf-loading-overlay')) {
                document.body.removeChild(document.getElementById('pdf-loading-overlay'));
            }
            if (printOverrideStyle.parentNode) printOverrideStyle.parentNode.removeChild(printOverrideStyle);
            const scrollStyle = document.getElementById('pdf-scroll-reset-style');
            if (scrollStyle) scrollStyle.remove();
            window.scrollTo(currentScrollX, currentScrollY);
        };

        const updatePdfProgress = (progress, status) => {
            const progressText = document.getElementById('pdf-progress-text');
            const progressBar = document.getElementById('pdf-progress-bar');
            const statusEl = document.getElementById('pdf-loading-status');
            if(progressText) progressText.innerText = progress + '%';
            if(progressBar) progressBar.style.width = progress + '%';
            if(statusEl) statusEl.innerText = status;
        };

        const loadPdfScript = (src, globalCheck) => new Promise((resolve, reject) => {
            if(globalCheck()) {
                resolve();
                return;
            }

            const existing = Array.from(document.scripts).find(script => script.src === src);
            if(existing) {
                existing.addEventListener('load', () => resolve(), { once: true });
                existing.addEventListener('error', () => reject(new Error('Failed to load ' + src)), { once: true });
                setTimeout(() => globalCheck() ? resolve() : reject(new Error('Timed out loading ' + src)), 8000);
                return;
            }

            const script = document.createElement('script');
            script.src = src;
            script.onload = () => resolve();
            script.onerror = () => reject(new Error('Failed to load ' + src));
            document.head.appendChild(script);
        });

        const ensurePdfLibraries = () => Promise.all([
            loadPdfScript('https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js', () => !!window.html2canvas),
            loadPdfScript('https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js', () => !!(window.jspdf && window.jspdf.jsPDF) || !!window.jsPDF)
        ]);

        const waitForPdfImages = () => Promise.all(Array.from(wrapper.querySelectorAll('img')).map(img => {
            if(img.complete) return Promise.resolve();
            return new Promise(resolve => {
                img.onload = resolve;
                img.onerror = resolve;
                setTimeout(resolve, 1000);
            });
        }));

        const addCanvasToPdf = (pdf, canvas, pageState) => {
            const pageWidth = pdf.internal.pageSize.getWidth();
            const pageHeight = pdf.internal.pageSize.getHeight();
            const margin = 10;
            const sectionGap = 3;
            const usableWidth = pageWidth - (margin * 2);
            const usableHeight = pageHeight - (margin * 2);
            const imageHeight = canvas.height * usableWidth / canvas.width;

            if(imageHeight <= usableHeight) {
                if(!pageState.isFirstPage && pageState.y + imageHeight > pageHeight - margin) {
                    pdf.addPage();
                    pageState.y = margin;
                }
                pdf.addImage(canvas.toDataURL('image/jpeg', 0.96), 'JPEG', margin, pageState.y, usableWidth, imageHeight);
                pageState.y += imageHeight + sectionGap;
                pageState.isFirstPage = false;
                return;
            }

            const sliceHeight = Math.floor(canvas.width * usableHeight / usableWidth);
            let rendered = 0;
            while(rendered < canvas.height) {
                if(!pageState.isFirstPage || pageState.y > margin) {
                    pdf.addPage();
                    pageState.y = margin;
                }

                const partHeight = Math.min(sliceHeight, canvas.height - rendered);
                const sliceCanvas = document.createElement('canvas');
                sliceCanvas.width = canvas.width;
                sliceCanvas.height = partHeight;
                const ctx = sliceCanvas.getContext('2d');
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0, 0, sliceCanvas.width, sliceCanvas.height);
                ctx.drawImage(canvas, 0, rendered, canvas.width, partHeight, 0, 0, canvas.width, partHeight);

                const slicePdfHeight = partHeight * usableWidth / canvas.width;
                pdf.addImage(sliceCanvas.toDataURL('image/jpeg', 0.96), 'JPEG', margin, margin, usableWidth, slicePdfHeight);
                pageState.isFirstPage = false;
                pageState.y = margin + slicePdfHeight + sectionGap;
                rendered += partHeight;
            }
        };

        const renderPdfInSections = async () => {
            await ensurePdfLibraries();
            await waitForPdfImages();
            const JsPdfClass = window.jspdf && window.jspdf.jsPDF ? window.jspdf.jsPDF : window.jsPDF;
            if(!JsPdfClass || !window.html2canvas) throw new Error('PDF libraries are not loaded.');

            const pdf = new JsPdfClass('p', 'mm', 'a4');
            const pageWidth = pdf.internal.pageSize.getWidth();
            const pageHeight = pdf.internal.pageSize.getHeight();
            const margin = 10;
            const usableWidth = pageWidth - (margin * 2);
            const usableHeight = pageHeight - (margin * 2);
            const renderScale = 1.05;
            const chunkHeight = Math.floor((calculatedWidth * usableHeight) / usableWidth);
            const headerHeight = 85;
            const footerHeight = 105;
            const contentHeight = chunkHeight - headerHeight - footerHeight;
            const totalHeight = wrapper.scrollHeight || wrapper.offsetHeight || calculatedHeight;
            const totalChunks = Math.max(1, Math.ceil(totalHeight / contentHeight));

            for(let index = 0; index < totalChunks; index++) {
                const offsetY = index * contentHeight;
                const currentChunkHeight = Math.min(contentHeight, totalHeight - offsetY);
                updatePdfProgress(Math.min(95, Math.max(5, Math.round((index / totalChunks) * 95))), 'Capturing page ' + (index + 1) + ' of ' + totalChunks + '...');

                const pageViewport = document.createElement('div');
                pageViewport.className = 'pdf-page-viewport';
                pageViewport.style.position = 'absolute';
                pageViewport.style.left = '0';
                pageViewport.style.top = '0';
                pageViewport.style.width = calculatedWidth + 'px';
                pageViewport.style.height = chunkHeight + 'px';
                pageViewport.style.overflow = 'hidden';
                pageViewport.style.overflowClipMargin = '0';
                pageViewport.style.clipPath = 'inset(0)';
                pageViewport.style.contain = 'paint';
                pageViewport.style.isolation = 'isolate';
                pageViewport.style.background = '#ffffff';
                pageViewport.style.zIndex = '501';

                const pageHeader = document.createElement('div');
                pageHeader.style.width = '100%';
                pageHeader.style.height = headerHeight + 'px';
                pageHeader.style.overflow = 'hidden';
                pageHeader.style.boxSizing = 'border-box';
                pageHeader.innerHTML = headerHtml;
                pageViewport.appendChild(pageHeader);

                const pageContent = document.createElement('div');
                pageContent.style.width = '100%';
                pageContent.style.height = contentHeight + 'px';
                pageContent.style.position = 'relative';
                pageContent.style.overflow = 'hidden';
                pageContent.style.boxSizing = 'border-box';

                const pageClone = wrapper.cloneNode(true);
                pageClone.removeAttribute('id');
                pageClone.classList.add('pdf-report-clone');
                pageClone.style.position = 'absolute';
                pageClone.style.left = '0';
                pageClone.style.top = '-' + offsetY + 'px';
                pageClone.style.width = calculatedWidth + 'px';
                pageClone.style.height = 'auto';
                pageClone.style.overflow = 'visible';
                pageClone.style.background = '#ffffff';
                if(index > 0) {
                    pageClone.querySelectorAll('.pdf-monitor-col, .monitor-mockup').forEach(el => {
                        el.style.visibility = 'hidden';
                    });
                }
                pageContent.appendChild(pageClone);
                pageViewport.appendChild(pageContent);

                const pageFooter = document.createElement('div');
                pageFooter.style.width = '100%';
                pageFooter.style.height = footerHeight + 'px';
                pageFooter.style.overflow = 'hidden';
                pageFooter.style.boxSizing = 'border-box';
                pageFooter.style.position = 'absolute';
                pageFooter.style.bottom = '0';
                pageFooter.style.left = '0';
                pageFooter.innerHTML = footerHtml;
                pageViewport.appendChild(pageFooter);

                document.body.appendChild(pageViewport);

                let pageCanvas;
                try {
                    pageCanvas = await html2canvas(pageViewport, {
                        scale: renderScale,
                        useCORS: true,
                        allowTaint: false,
                        backgroundColor: '#ffffff',
                        scrollX: 0,
                        scrollY: 0,
                        width: calculatedWidth,
                        height: chunkHeight,
                        windowWidth: calculatedWidth,
                        windowHeight: chunkHeight
                    });
                } finally {
                    if(document.body.contains(pageViewport)) {
                        document.body.removeChild(pageViewport);
                    }
                }

                if(pageCanvas.width > 0 && pageCanvas.height > 0) {
                    const imageHeight = pageCanvas.height * usableWidth / pageCanvas.width;
                    if(index > 0) pdf.addPage();
                    pdf.addImage(pageCanvas.toDataURL('image/jpeg', 0.92), 'JPEG', margin, margin, usableWidth, Math.min(imageHeight, usableHeight));
                }
            }

            updatePdfProgress(100, 'Ready for download!');
            pdf.save(filename);
            trackDownload(withLogo);
        };

        setTimeout(() => {
            renderPdfInSections()
                .then(() => {
                    setTimeout(() => {
                        cleanupPdfDom();
                        window.location.reload();
                    }, 500);
                })
                .catch(error => {
                    console.error(error);
                    cleanupPdfDom();
                    alert('PDF generation failed. Please refresh and try again.');
                });
        }, 250);
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        let logoUploadedSuccessfully = false;

        const modalLogoFile = document.getElementById('modal_logo_file');
        const modalLogoPreviewContainer = document.getElementById('modal_logo_preview_container');
        const modalLogoPreviewImg = document.getElementById('modal_logo_preview_img');

        if (modalLogoFile) {
            modalLogoFile.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        if (modalLogoPreviewImg) {
                            modalLogoPreviewImg.src = e.target.result;
                        }
                        if (modalLogoPreviewContainer) {
                            modalLogoPreviewContainer.style.setProperty('display', 'flex', 'important');
                        }
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        $('#download_logo_ask_modal').on('show.bs.modal', function () {
            if (logoUploadedSuccessfully) {
                const btn = document.getElementById('modal_upload_logo_btn');
                if (btn) {
                    btn.disabled = true;
                    btn.classList.remove('btn-info');
                    btn.classList.add('btn-success');
                    btn.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Logo Updated Successfully!';
                }
            }
        });

        const modalDownloadStandardBtn = document.getElementById('modal_download_standard_btn');
        if(modalDownloadStandardBtn) {
            modalDownloadStandardBtn.addEventListener('click', () => {
                window.pdfCustomBranding = null;
                $('#download_logo_ask_modal').modal('hide');
                downloadReport(false);
            });
        }

        const modalDownloadCustomBtn = document.getElementById('modal_download_custom_btn');
        if(modalDownloadCustomBtn) {
            modalDownloadCustomBtn.addEventListener('click', () => {
                $('#download_logo_ask_modal').modal('hide');
                $('#report_logo_modal').modal('show');
            });
        }

        const modalUploadLogoBtn = document.getElementById('modal_upload_logo_btn');
        if(modalUploadLogoBtn) {
            modalUploadLogoBtn.addEventListener('click', () => {
                const fileInput = document.getElementById('modal_logo_file');
                const errorDiv = document.getElementById('modal_logo_upload_error');
                
                if (!fileInput) return;

                if (fileInput.files.length === 0) {
                    errorDiv.innerText = 'Please select a logo image file to upload first.';
                    errorDiv.style.display = 'block';
                    return;
                }

                const file = fileInput.files[0];
                const formData = new FormData();
                formData.append('white_label_report_logo', file);
                formData.append('token', '<?= \Altum\Csrf::get() ?>');

                const originalHTML = modalUploadLogoBtn.innerHTML;
                modalUploadLogoBtn.disabled = true;
                modalUploadLogoBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Uploading...';
                errorDiv.style.display = 'none';

                fetch('<?= url('audit/upload_report_logo') ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        logoUploadedSuccessfully = true;

                        const logoImg = document.querySelector('.d-print-block img.audit-logo');
                        const logoContainer = document.querySelector('.d-print-block .card-body');
                        if (logoImg) {
                            logoImg.src = data.logo_url;
                        } else if (logoContainer) {
                            const newImg = document.createElement('img');
                            newImg.src = data.logo_url;
                            newImg.className = 'audit-logo ml-3';
                            newImg.alt = 'Logo';
                            logoContainer.appendChild(newImg);
                        }
                        
                        modalUploadLogoBtn.disabled = true;
                        modalUploadLogoBtn.classList.remove('btn-info');
                        modalUploadLogoBtn.classList.add('btn-success');
                        modalUploadLogoBtn.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Logo Updated Successfully!';
                        
                        setTimeout(() => {
                            $('#download_logo_ask_modal').modal('hide');
                            downloadReport(true);
                        }, 1000);
                    } else {
                        errorDiv.innerText = data.message || 'An error occurred during upload.';
                        errorDiv.style.display = 'block';
                        modalUploadLogoBtn.disabled = false;
                        modalUploadLogoBtn.innerHTML = originalHTML;
                    }
                })
                .catch(() => {
                    errorDiv.innerText = 'Failed to connect to server. Please try again.';
                    errorDiv.style.display = 'block';
                    modalUploadLogoBtn.disabled = false;
                    modalUploadLogoBtn.innerHTML = originalHTML;
                });
            });
        }

        const wlLogoFile = document.getElementById('wl_logo_file');
        const wlCompanyInput = document.getElementById('wl_company_input');
        const wlDomainInput = document.getElementById('wl_domain_input');
        const wlEmailInput = document.getElementById('wl_email_input');
        const wlPhoneInput = document.getElementById('wl_phone_input');

        const wlPreviewCompanyText = document.getElementById('wl_preview_company_text');
        const wlPreviewLogoImg = document.getElementById('wl_preview_logo_img');
        const wlPreviewEmail = document.getElementById('wl_preview_email');
        const wlPreviewEmailBlock = document.getElementById('wl_preview_email_block');
        const wlPreviewPhone = document.getElementById('wl_preview_phone');
        const wlPreviewPhoneBlock = document.getElementById('wl_preview_phone_block');

        const wlPreviewFooterCompanyText = document.getElementById('wl_preview_footer_company_text');
        const wlPreviewFooterLogoImg = document.getElementById('wl_preview_footer_logo_img');
        const wlPreviewFooterCompany = document.getElementById('wl_preview_footer_company');
        const wlPreviewDomainText = document.getElementById('wl_preview_domain_text');
        const wlPreviewYear = document.getElementById('wl_preview_year');

        const support_email = '<?= get_support_email() ?>';

        const updateWLPreview = (logo, company, domain, email, phone) => {
            if (wlPreviewEmail) wlPreviewEmail.innerText = email || support_email;
            if (wlPreviewPhone) wlPreviewPhone.innerText = phone;
            if (wlPreviewFooterCompany) wlPreviewFooterCompany.innerText = company || 'SiteRankify';
            if (wlPreviewDomainText) wlPreviewDomainText.innerText = domain || 'siteanalysistool.com';
            if (wlPreviewYear) wlPreviewYear.innerText = new Date().getFullYear();

            if (wlPreviewEmailBlock) {
                wlPreviewEmailBlock.style.display = email ? 'inline' : 'none';
            }
            if (wlPreviewPhoneBlock) {
                wlPreviewPhoneBlock.style.display = phone ? 'inline' : 'none';
            }

            if (logo) {
                if (wlPreviewLogoImg) {
                    wlPreviewLogoImg.src = logo;
                    wlPreviewLogoImg.style.display = 'inline-block';
                }
                if (wlPreviewCompanyText) wlPreviewCompanyText.style.display = 'none';

                if (wlPreviewFooterLogoImg) {
                    wlPreviewFooterLogoImg.src = logo;
                    wlPreviewFooterLogoImg.style.display = 'inline-block';
                }
                if (wlPreviewFooterCompanyText) wlPreviewFooterCompanyText.style.display = 'none';
            } else {
                if (wlPreviewLogoImg) wlPreviewLogoImg.style.display = 'none';
                if (wlPreviewCompanyText) {
                    wlPreviewCompanyText.style.display = 'inline-block';
                    wlPreviewCompanyText.innerText = company || 'SiteRankify';
                }

                if (wlPreviewFooterLogoImg) wlPreviewFooterLogoImg.style.display = 'none';
                if (wlPreviewFooterCompanyText) {
                    wlPreviewFooterCompanyText.style.display = 'inline-block';
                    wlPreviewFooterCompanyText.innerText = company || 'SiteRankify';
                }
            }
        };

        const getWLValues = () => {
            return {
                logo: wlPreviewLogoImg && wlPreviewLogoImg.src && wlPreviewLogoImg.style.display !== 'none' ? wlPreviewLogoImg.src : '',
                company: wlCompanyInput ? wlCompanyInput.value : '',
                domain: wlDomainInput ? wlDomainInput.value : '',
                email: wlEmailInput ? wlEmailInput.value : '',
                phone: wlPhoneInput ? wlPhoneInput.value : ''
            };
        };

        const onInputChange = () => {
            const vals = getWLValues();
            updateWLPreview(vals.logo, vals.company, vals.domain, vals.email, vals.phone);
        };

        if (wlCompanyInput) wlCompanyInput.addEventListener('input', onInputChange);
        if (wlDomainInput) wlDomainInput.addEventListener('input', onInputChange);
        if (wlEmailInput) wlEmailInput.addEventListener('input', onInputChange);
        if (wlPhoneInput) wlPhoneInput.addEventListener('input', onInputChange);

        if (wlLogoFile) {
            wlLogoFile.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        updateWLPreview(e.target.result, wlCompanyInput.value, wlDomainInput.value, wlEmailInput.value, wlPhoneInput.value);
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        const loadWLBranding = () => {
            const logo = '<?= is_logged_in() && !empty($this->user->preferences->white_label_report_logo) ? \Altum\Uploads::get_full_url("users") . $this->user->preferences->white_label_report_logo : "" ?>';
            const company = '<?= addslashes($php_has_logo_permission && is_logged_in() && !empty($this->user->preferences->white_label_company_name) ? $this->user->preferences->white_label_company_name : settings()->main->title) ?>';
            const domain = '<?= $php_has_logo_permission && is_logged_in() && !empty($this->user->preferences->white_label_domain_name) ? addslashes($this->user->preferences->white_label_domain_name) : remove_url_protocol_from_url(url()) ?>';
            const email = '<?= $php_has_logo_permission && is_logged_in() && !empty($this->user->preferences->white_label_email) ? addslashes($this->user->preferences->white_label_email) : (!empty(settings()->business->email) ? settings()->business->email : (!empty(settings()->main->email) ? settings()->main->email : get_support_email())) ?>';
            const phone = '<?= $php_has_logo_permission && is_logged_in() && !empty($this->user->preferences->white_label_phone) ? addslashes($this->user->preferences->white_label_phone) : (!empty(settings()->business->phone) ? settings()->business->phone : (!empty(settings()->main->phone) ? settings()->main->phone : "")) ?>';

            if (wlCompanyInput) wlCompanyInput.value = company;
            if (wlDomainInput) wlDomainInput.value = domain;
            if (wlEmailInput) wlEmailInput.value = email;
            if (wlPhoneInput) wlPhoneInput.value = phone;

            updateWLPreview(logo, company, domain, email, phone);

            const wlContentPreviewArea = document.getElementById('wl_content_preview_area');
            if (wlContentPreviewArea) {
                wlContentPreviewArea.className = "flex-grow-1 my-2 border rounded p-2 bg-light d-flex align-items-center justify-content-center";
                wlContentPreviewArea.innerHTML = `
                    <button type="button" id="wl_generate_preview_btn" class="btn btn-sm btn-outline-primary font-weight-bold">
                        <i class="fas fa-eye mr-1"></i> Preview PDF Content
                    </button>
                `;
                
                const btn = document.getElementById('wl_generate_preview_btn');
                if (btn) {
                    btn.addEventListener('click', () => {
                        const wl_logo_url = wlPreviewLogoImg && wlPreviewLogoImg.src && wlPreviewLogoImg.style.display !== 'none' ? wlPreviewLogoImg.src : '';
                        const wl_company = wlCompanyInput ? wlCompanyInput.value : '';
                        const wl_domain = wlDomainInput ? wlDomainInput.value : '';
                        const wl_email = wlEmailInput ? wlEmailInput.value : '';
                        const wl_phone = wlPhoneInput ? wlPhoneInput.value : '';

                        const headerHtml = `
                            <div style="border-bottom: 2px solid #edf2f7; padding-bottom: 15px; margin-bottom: 25px; font-family: sans-serif; display: flex; justify-content: space-between; align-items: center; width: 100%;">
                                <div>
                                    ${wl_logo_url ? `
                                        <img src="${wl_logo_url}" style="max-height: 55px; display: inline-block; vertical-align: middle;" />
                                    ` : `
                                        <span style="font-size: 20px; font-weight: 800; display: inline-block; vertical-align: middle;">${wl_company || 'SiteRankify'}</span>
                                    `}
                                </div>
                                <div style="text-align: right; font-size: 11px; font-weight: 600; color: #495057;">
                                    ${wl_email ? `<span style="margin-left: 15px; display: inline-block; vertical-align: middle;"><i class="fas fa-envelope mr-1"></i> ${wl_email}</span>` : ''}
                                    ${wl_phone ? `<span style="margin-left: 15px; display: inline-block; vertical-align: middle;"><i class="fas fa-phone mr-1"></i> ${wl_phone}</span>` : ''}
                                </div>
                            </div>
                        `;

                        const footerHtml = `
                            <div style="border-top: 2px solid #edf2f7; padding-top: 20px; margin-top: 40px; font-family: sans-serif; color: #718096; font-size: 11px; display: flex; justify-content: space-between; align-items: center; width: 100%;">
                                <div>
                                    ${wl_logo_url ? `
                                        <img src="${wl_logo_url}" style="max-height: 50px; margin-bottom: 8px; display: block;" />
                                    ` : `
                                        <span style="font-size: 12px; font-weight: 800; margin-bottom: 8px; display: block; color: #000;">${wl_company || 'SiteRankify'}</span>
                                    `}
                                    &copy; ${new Date().getFullYear()} ${wl_company || 'SiteRankify'}. All rights reserved.
                                </div>
                                <div>
                                    ${wl_domain ? `<a href="https://${wl_domain}" target="_blank" style="text-decoration: none; color: #0056b3; font-weight: bold;">${wl_domain}</a>` : ''}
                                </div>
                            </div>
                        `;

                        const mainCard = document.getElementById('overview') || document.querySelector('.container > .card.mb-4') || document.querySelector('.card');
                        if (mainCard) {
                            const clonedCard = mainCard.cloneNode(true);
                            clonedCard.querySelectorAll('.d-print-none, button, .btn, .card-footer, script').forEach(el => el.remove());
                            
                            const docContainer = document.getElementById('pdf_full_preview_document');
                            if (docContainer) {
                                docContainer.innerHTML = '';
                                
                                const headerDiv = document.createElement('div');
                                headerDiv.innerHTML = headerHtml;
                                
                                const footerDiv = document.createElement('div');
                                footerDiv.innerHTML = footerHtml;
                                
                                docContainer.appendChild(headerDiv);
                                docContainer.appendChild(clonedCard);
                                docContainer.appendChild(footerDiv);
                            }
                        }

                        $('#pdf_full_preview_modal').modal('show');
                    });
                }
            }
        };

        const resetBtn = document.getElementById('wl_reset_btn');
        if (resetBtn) {
            resetBtn.addEventListener('click', () => {
                if (wlLogoFile) wlLogoFile.value = '';
                loadWLBranding();
            });
        }

        const saveBrandingAndDownload = (btn, originalHtml, closeModalsCallback) => {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Saving...';
            
            const formData = new FormData();
            formData.append('token', '<?= \Altum\Csrf::get() ?>');
            
            if (wlLogoFile && wlLogoFile.files.length > 0) {
                formData.append('white_label_report_logo', wlLogoFile.files[0]);
            }
            if (wlCompanyInput) formData.append('company_name', wlCompanyInput.value);
            if (wlDomainInput) formData.append('domain_name', wlDomainInput.value);
            if (wlEmailInput) formData.append('email', wlEmailInput.value);
            if (wlPhoneInput) formData.append('phone', wlPhoneInput.value);

            fetch('<?= url("audit/upload_report_logo") ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success" && data.logo_url) {
                    updateWLPreview(data.logo_url, wlCompanyInput.value, wlDomainInput.value, wlEmailInput.value, wlPhoneInput.value);
                }
                btn.disabled = false;
                btn.innerHTML = originalHtml;
                
                window.pdfCustomBranding = getWLValues();
                closeModalsCallback();
                downloadReport(true);
            })
            .catch(() => {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
                alert('Failed to save branding settings. Downloading report anyway.');
                window.pdfCustomBranding = getWLValues();
                closeModalsCallback();
                downloadReport(true);
            });
        };

        const saveDownloadBtn = document.getElementById('wl_save_download_btn');
        if (saveDownloadBtn) {
            saveDownloadBtn.addEventListener('click', function() {
                const originalHtml = this.innerHTML;
                saveBrandingAndDownload(this, originalHtml, () => {
                    $('#report_logo_modal').modal('hide');
                });
            });
        }

        const pdfFullPreviewDownloadBtn = document.getElementById('pdf_full_preview_download_btn');
        if (pdfFullPreviewDownloadBtn) {
            pdfFullPreviewDownloadBtn.addEventListener('click', function() {
                const originalHtml = this.innerHTML;
                saveBrandingAndDownload(this, originalHtml, () => {
                    $('#pdf_full_preview_modal').modal('hide');
                    $('#report_logo_modal').modal('hide');
                });
            });
        }

        $('#report_logo_modal').on('show.bs.modal', loadWLBranding);
});
</script>
<?php endif ?>
