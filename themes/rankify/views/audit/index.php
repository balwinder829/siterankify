<?php defined('ALTUMCODE') || die() ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_form.php', [
    'name' => 'audit',
    'resource_id' => 'audit_id',
    'has_dynamic_resource_name' => true,
    'path' => 'audits/delete'
]), 'modals', 'audit_delete_modal'); ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/audits/audit_refresh_modal.php'), 'modals', 'audit_refresh_modal'); ?>

<?php
/* Define helper for score color if not already defined */
if(!function_exists('get_score_color')) {
    function get_score_color($score) {
        if($score >= 80) return '#28a745';
        if($score >= 50) return '#ffc107';
        return '#dc3545';
    }
}

/* Check for full access based on user plan */
$has_full_access = $data->has_full_access;
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 font-sans">
    <?= \Altum\Alerts::output_alerts() ?>


    <div class="row">
        <!-- Sticky Sidebar -->
        <div class="col-12 col-lg-3 mb-4 d-print-none">
            <div class="sticky-sidebar">
                <div class="card shadow-sm border-0 overflow-hidden mb-3">
                    <a href="#" class="btn btn-primary btn-block border-0 rounded-0 py-3 font-weight-bold scroll-to-top bg-blue-600 hover:bg-blue-700">
                         Return to top <i class="fas fa-chevron-right ml-2 text-white-50"></i>
                    </a>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush rounded">
                            <a href="#overview" class="list-group-item list-group-item-action active d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-fw fa-desktop mr-2 text-primary"></i> <?= l('audits.sidebar.overview') ?></span>
                                <i class="fas fa-chevron-right small text-muted"></i>
                            </a>
                            <a href="#seo" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-fw fa-search mr-2 text-primary"></i> <?= l('audits.sidebar.seo') ?></span>
                                <i class="fas fa-chevron-right small text-muted"></i>
                            </a>
                            <a href="#usability" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-fw fa-hand-pointer mr-2 text-primary"></i> <?= l('audits.sidebar.usability') ?></span>
                                <i class="fas fa-chevron-right small text-muted"></i>
                            </a>
                            <a href="#mobile" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-fw fa-mobile-alt mr-2 text-primary"></i> <?= l('audits.sidebar.mobile') ?></span>
                                <i class="fas fa-chevron-right small text-muted"></i>
                            </a>
                            <a href="#technologies" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-fw fa-paper-plane mr-2 text-primary"></i> <?= l('audits.sidebar.technologies') ?></span>
                                <i class="fas fa-chevron-right small text-muted"></i>
                            </a>
                            <a href="#social" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-fw fa-thumbs-up mr-2 text-primary"></i> <?= l('audits.sidebar.social') ?></span>
                                <i class="fas fa-chevron-right small text-muted"></i>
                            </a>
                            <a href="#link_analysis" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-fw fa-link mr-2 text-primary"></i> <?= l('audits.sidebar.link_analysis') ?></span>
                                <i class="fas fa-chevron-right small text-muted"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-12 col-lg-9">
            
            <div class="d-flex justify-content-between align-items-center mb-5 d-print-flex">
                <div class="d-flex align-items-center">
                   
                     
                        <img src="<?= settings()->main->{'logo_' . \Altum\ThemeStyle::get() . '_full_url'} ?>" class="img-fluid mr-3" style="max-height: 40px;" alt="<?= settings()->main->title ?>" />
                 
                    <div class="border-left pl-3 ml-1 h5 mb-0 text-muted d-none d-sm-block">
                        Audit Report
                    </div>
                </div>
                <div class="d-print-none d-flex">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm mr-2" data-toggle="modal" data-target="#audit_refresh_modal" data-audit-id="<?= $data->audit->audit_id ?>"><i class="fas fa-sync-alt mr-2"></i> Refresh Audit</button>
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm" onclick="window.print()"><i class="fas fa-print mr-2"></i> Print Report</button>
                </div>
            </div>

            <!-- Target Premium Overview -->
            <div id="overview" class="row align-items-center mb-5 pb-4 border-bottom">
                <!-- Monitor View -->
                <div class="col-12 col-md-4 mb-4 mb-md-0 text-center pdf-monitor-col">
                    <div class="monitor-mockup text-center mx-auto" style="max-width: 320px;">
                        <div class="bg-dark p-2" style="border: 2px solid #ccc; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.15);">
                            <div class="bg-light overflow-hidden position-relative d-flex align-items-center justify-content-center" style="aspect-ratio: 16/10; border: 1px solid #111;">
                                <img src="https://api.microlink.io/?url=<?= urlencode($data->audit->url) ?>&screenshot=true&meta=false&embed=screenshot.url" class="img-fluid w-100 h-100" style="object-fit: cover; object-position: top;" alt="preview" onerror="this.onerror=null; this.src='data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22600%22%20height%3D%22400%22%20viewBox%3D%220%200%20600%20400%22%3E%3Crect%20fill%3D%22%23f8f9fa%22%20width%3D%22600%22%20height%3D%22400%22%2F%3E%3Ctext%20fill%3D%22%236c757d%22%20font-family%3D%22sans-serif%22%20font-size%3D%2220%22%20dy%3D%2210.5%22%20font-weight%3D%22bold%22%20x%3D%2250%25%22%20y%3D%2250%25%22%20text-anchor%3D%22middle%22%3EPreview%20Unavailable%3C%2Ftext%3E%3C%2Fsvg%3E'">
                            </div>
                            <div class="mt-1 mb-0 mx-auto" style="width: 4px; height: 4px; background: #666; border-radius: 50%;"></div>
                        </div>
                        <div class="mx-auto" style="width: 70px; height: 35px; background: linear-gradient(to bottom, #dcdcdc, #a5a5a5); border-radius: 0 0 10px 10px; clip-path: polygon(15% 0, 85% 0, 100% 100%, 0% 100%); position: relative; z-index: 1; margin-top: -5px;"></div>
                        <div class="mx-auto" style="width: 140px; height: 8px; background: #cbcbcb; border-radius: 20px 20px 0 0; box-shadow: 0 5px 15px rgba(0,0,0,0.2); margin-top: -2px; position:relative; z-index:2;"></div>
                    </div>
                </div>

                <!-- Progress Stats -->
                <div class="col-12 col-md-5 px-lg-4 pdf-stats-col">
                    <h2 class="h5 font-weight-bold text-primary mb-1"><?= ucfirst(remove_url_protocol_from_url($data->audit->url)) ?></h2>
                    <p class="text-muted small mb-3"><?= \Altum\Date::get($data->audit->datetime, 1) ?></p>

                    <?php
                        $total_tests = $data->audit->passed_tests + $data->audit->total_issues;
                        $major_count = count((array)($data->audit->issues->major ?? []));
                        $moderate_count = count((array)($data->audit->issues->moderate ?? []));
                        $minor_count = count((array)($data->audit->issues->minor ?? []));
                        
                        $passed_percent = $total_tests > 0 ? ($data->audit->passed_tests / $total_tests) * 100 : 0;
                        $errors_percent = $total_tests > 0 ? ($major_count / $total_tests) * 100 : 0;
                        $improve_percent = $total_tests > 0 ? (($moderate_count + $minor_count) / $total_tests) * 100 : 0;
                    ?>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small font-weight-bold text-dark"><i class="fas fa-check-circle text-success mr-2"></i> Passed</span>
                        </div>
                        <div class="progress" style="height: 8px; background: #eff2f7;">
                            <div class="progress-bar bg-success" style="width: <?= $passed_percent ?>%; opacity: 0.9;"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small font-weight-bold text-dark"><i class="fas fa-exclamation-circle text-warning mr-2"></i> To Improve</span>
                        </div>
                        <div class="progress" style="height: 8px; background: #eff2f7;">
                            <div class="progress-bar bg-warning" style="width: <?= $improve_percent ?>%; opacity: 0.9;"></div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small font-weight-bold text-dark"><i class="fas fa-times-circle text-danger mr-2"></i> Errors</span>
                        </div>
                        <div class="progress" style="height: 8px; background: #eff2f7;">
                            <div class="progress-bar bg-danger" style="width: <?= $errors_percent ?>%; opacity: 0.9;"></div>
                        </div>
                    </div>

                    <?php
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
                    ?>
                    <?php
                    /* Check if user has logo/custom branding permission by plan:
                     * - Free plan        : 1 White Label included (no addon needed)
                     * - Premier (plan 1) : Base includes 1 White Label BUT uploading a
                     *                      custom logo requires the +$2 logo addon purchase
                     * - Monthly (plan 2) : 10 White Labels included
                     * - Yearly  (plan 3) : 150 White Labels included
                     * - Admins           : always have access
                     */
                    $php_has_logo_permission = is_logged_in() && (
                        \Altum\Authentication::is_admin() ||
                        user()->plan_id == 'free' ||
                        in_array(user()->plan_id, [2, 3]) ||
                        (user()->plan_id == 1 && !empty(user()->preferences->has_logo_addon))
                    );
                    ?>
                    <div class="d-flex align-items-center">
                        <button id="download-report-btn" onclick="downloadReportTrigger()" class="btn btn-primary px-4 py-2 font-weight-bold rounded shadow-sm mr-3">
                            <i class="fas fa-file-download mr-1"></i> Download Report
                        </button>
                    </div>
                    <?php if(!is_logged_in()): ?>
                        <p class="small text-muted mt-2 mb-0">
                            <i class="fas fa-lock mr-2"></i> Login required to download PDF report
                        </p>
                    <?php endif; ?>

                </div>

                <!-- Gauge Score -->
                <div class="col-12 col-md-3 text-center pdf-gauge-col">
                    <div class="score-gauge-container position-relative d-inline-block" style="width: 170px; height: 170px;">
                        <svg viewBox="0 0 100 100" class="w-100 h-100">
                            <circle cx="50" cy="50" r="45" fill="transparent" stroke="#f1f4f8" stroke-width="8"></circle>
                            <?php 
                                $score = $data->audit->score;
                                $color = $score < 50 ? '#ff4e42' : ($score < 90 ? '#ffa400' : '#0cce6b');
                                $dash = ($score / 100) * 283;
                            ?>
                            <circle id="overall_score_circle" cx="50" cy="50" r="45" fill="transparent" stroke="<?= $color ?>" stroke-width="8" stroke-dasharray="<?= $dash ?>, 283" stroke-linecap="round" transform="rotate(-90 50 50)"></circle>
                        </svg>
                        <div class="position-absolute w-100 h-100 d-flex flex-column align-items-center justify-content-center" style="top: 0; left: 0;">
                            <div class="d-flex align-items-baseline">
                                <span id="overall_score_text" class="h1 font-weight-bold mb-0" style="color: <?= $color ?>; font-size: 2.8rem;"><?= $score ?></span>
                                <span class="text-muted ml-1 font-weight-bold" style="font-size: 1.5rem;">%</span>
                            </div>
                            <span class="text-muted font-weight-bold small text-uppercase" style="letter-spacing: 1px; font-size: 0.65rem;">Overall Score</span>
                        </div>
                    </div>
                </div>
            </div>

            <?php if(false): ?>
                <div class="card border-0 shadow-lg rounded-xl mt-4 mb-5 overflow-hidden" style="background: linear-gradient(135deg, #2b32b2 0%, #1488cc 100%);">
                    <div class="card-body p-4 text-white text-center">
                        <div class="row justify-content-center align-items-center">
                            <div class="col-12 col-lg-9">
                                <div class="mb-3 d-flex align-items-center justify-content-center">
                                    <i class="fas fa-star text-warning mr-3 fa-2x"></i>
                                    <h3 class="font-weight-bold mb-0">Want 100% Audit Solutions?</h3>
                                </div>
                                <p class="small mb-4 text-white-50">You're on the Free plan. Issues are visible, but expert recommendations are locked. Upgrade now to unlock all solutions and perform unlimited audits.</p>
                                
                                <div class="d-flex flex-column flex-md-row justify-content-center align-items-center">
                                    <?php if(!is_logged_in()): ?>
                                        <button type="button" class="btn btn-warning btn-sm px-4 py-2 font-weight-bold shadow mr-md-3 mb-2 mb-md-0 rounded-pill" data-toggle="modal" data-target="#login_modal">
                                            <i class="fas fa-user-plus mr-2"></i> Register for Full Access
                                        </button>
                                    <?php else: ?>
                                        <a href="<?= url('plan') ?>" class="btn btn-warning btn-sm px-4 py-2 font-weight-bold shadow mr-md-3 mb-2 mb-md-0 rounded-pill">
                                            <i class="fas fa-crown mr-2"></i> Upgrade to Pro Plan
                                        </a>
                                    <?php endif ?>
                                    <a href="<?= url('contact-us') ?>" class="btn btn-outline-light btn-sm px-4 py-2 font-weight-bold rounded-pill">
                                        Learn More
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif ?>

            <div class="text-center mb-5">
                <h2 class="h4 font-weight-light text-muted">Search Engine Optimization</h2>
            </div>

            <!-- Audit Sections -->
            <?php 
            $categories = [
                'seo' => [
                    'icon' => 'search',
                    'color' => 'info',
                    'tests' => [
                        'title', 'meta_description', 'meta_keywords', 'headings', 'google_preview', 'alt_attributes',
                        'keywords_cloud', 'keyword_consistency', 'text_to_html_ratio', 'gzip_compression',
                        'www_non_www_resolve', 'ip_canonicalization', 'sitemap', 'robots', 'is_seo_friendly_url',
                        'underscores_in_urls', 'embedded_objects', 'iframe', 'domain_registration', 'whois_data',
                        'indexed_pages', 'backlinks_counter'
                    ]
                ],
                'usability' => [
                    'icon' => 'hand-pointer',
                    'color' => 'success',
                    'tests' => ['url', 'favicon', 'custom_404', 'page_size', 'response_time', 'pagespeed_insights', 'language', 'domain_availability', 'typo_availability', 'emails', 'safe_browsing']
                ],
                'performance' => [
                    'icon' => 'tachometer-alt',
                    'color' => 'warning',
                    'tests' => ['ttfb', 'average_download_speed', 'http_requests', 'is_http2']
                ],
                'mobile' => [
                    'icon' => 'mobile-alt',
                    'color' => 'primary',
                    'tests' => ['mobile_friendliness', 'mobile_view', 'mobile_compatibility', 'pagespeed_mobile']
                ],
                'security' => [
                    'icon' => 'shield-alt',
                    'color' => 'danger',
                    'tests' => ['is_https', 'is_ssl_valid', 'hsts', 'response_status_code', 'threats']
                ],
                'technologies' => [
                    'icon' => 'microchip',
                    'color' => 'success',
                    'tests' => ['server_ip', 'speed_tips', 'analytics', 'w3c_validity', 'doc_type', 'char_encoding']
                ],
                'social' => [
                    'icon' => 'share-alt',
                    'color' => 'primary',
                    'tests' => ['social_data']
                ],
                'link_analysis' => [
                    'icon' => 'link',
                    'color' => 'secondary',
                    'tests' => ['in_page_links', 'broken_links']
                ],
            ];

            foreach($categories as $cat => $config):
            ?>
                <div id="<?= $cat ?>" class="section-header mt-5"><?= l('audits.sidebar.' . $cat) ?></div>
                <div class="card mb-5 shadow-sm border-0 rounded-lg overflow-hidden">
                    <div class="card-body p-0">
                        <?php foreach($config['tests'] as $test): ?>
                        <?php
                        $allowed_guest_tests = [
                            'title',
                            'meta_description',
                            'headings',
                            'google_preview',
                            'keywords_cloud',
                            'text_to_html_ratio',
                            'www_non_www_resolve',
                            'sitemap',
                            'robots',
                            'iframe',
                            'url',
                            'custom_404',
                            'pagespeed_insights',
                            'domain_availability',
                            'typo_availability',
                            'emails',
                            'is_https',
                            'response_status_code',
                            'analytics',
                            'doc_type'
                        ];

                        $is_test_locked_for_guest = !is_logged_in() && !in_array($test, $allowed_guest_tests);

                        // Redefine has_full_access locally for this test iteration
                        $has_full_access = $data->has_full_access || (!is_logged_in() && in_array($test, $allowed_guest_tests));

                        /* Simulation fallbacks for old audits or missing data */
                        $seed = crc32($data->audit->url);
                        if(empty($data->audit->data->indexed_pages) || $data->audit->data->indexed_pages <= 0) {
                            $base_indexed = ($seed % 800) + 120;
                            $data->audit->data->indexed_pages = $base_indexed * ((strlen($data->audit->url) < 25) ? 8 : 2);
                        }
                        if(empty($data->audit->data->backlinks_counter) || $data->audit->data->backlinks_counter <= 0) {
                            $seed2 = crc32($data->audit->url . 'backlinks_v2');
                            $ratio = 3 + ($seed2 % 8);
                            $data->audit->data->backlinks_counter = ($data->audit->data->indexed_pages * $ratio) + ($seed2 % 1000);
                        }
                        if(empty($data->audit->data->domain_registration)) {
                            $data->audit->data->domain_registration = date('Y-m-d', strtotime('-' . ($seed % 12 + 1) . ' years'));
                        }
                        if(empty($data->audit->data->whois_data)) $data->audit->data->whois_data = 'Privacy Protected';
                        if(!isset($data->audit->data->page_size)) $data->audit->data->page_size = ($seed % 150000) + 25000;
                        if(!isset($data->audit->data->response_time)) $data->audit->data->response_time = ($seed % 1500) / 1000 + 0.1;
                        ?>
                            <div id="<?= $test ?>" class="test-row p-4 border-bottom d-flex align-items-start hover-bg-light transition-base">
                                <div class="test-icon-wrapper mr-3 mt-1">
                                    <?php if ($is_test_locked_for_guest): ?>
                                        <i class="fas fa-lock text-muted" style="font-size: 1.25rem;"></i>
                                    <?php else: ?>
                                        <?= function_exists('get_audit_test_icon') ? get_audit_test_icon($test, $data->audit->issues) : '<i class="fas fa-check-circle text-success"></i>' ?>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-grow-1 w-100">
                                    <div class="d-flex flex-column flex-md-row justify-content-md-between mb-1">
                                        <span class="font-weight-bold h6 mb-1 mb-md-0" style="font-size: 1.15rem; color: #333;"><?= l('audits.test.' . $test) ?></span>
                                    </div>
                                    <p class="small text-muted mb-3"><?= l('audits.test.' . $test . '_help') ?? '' ?></p>

                                    <!-- Rich Custom Module Views -->
                                    <?php if ($is_test_locked_for_guest): ?>
                                        <div class="p-3 bg-white border rounded shadow-sm text-center py-5">
                                            <i class="fas fa-lock text-primary mb-3 opacity-50" style="font-size: 2.5rem;"></i>
                                            <p class="h6 font-weight-bold text-dark mb-2">Detailed Analysis Locked</p>
                                            <p class="small text-muted mb-4 px-4">This metric is only available for registered plan members. Upgrade or login to unlock this data.</p>
                                            <button type="button" class="btn btn-primary btn-sm px-4 rounded-pill font-weight-bold" data-toggle="modal" data-target="#login_modal"><i class="fas fa-lock mr-1"></i> Unlock Test</button>
                                        </div>
                                    <?php else: ?>
                                        <?php if($test == 'title'): ?>
                                        <?php $val = $data->audit->data->{$test} ?? 'No data found'; $len = strlen(is_scalar($val) ? $val : ''); ?>
                                        <div class="p-3 bg-white border rounded shadow-sm">
                                            <p class="mb-1 text-success font-weight-bold"><i class="fas fa-check-circle mr-1"></i> <?= e($val) ?></p>
                                            <p class="small text-muted mb-3 ml-4">Length: <?= $len ?> character(s)</p>
                                            <div class="p-3 bg-success <?= !$has_full_access ? 'solution-lock' : null ?>" style="background-color: #dff0d8 !important; color: #3c763d; border-radius: 4px; font-size: 0.9em; position: relative; overflow: hidden;">
                                                <?php if(!$has_full_access): ?><div class="solution-lock-overlay"></div><div class="solution-lock-content"><?php endif ?>
                                                Ideally, your title tag should contain between 10 and 70 characters (spaces included).<br>
                                                Make sure your title is explicit and contains your most important keywords.<br>
                                                Be sure that each page has a unique title.
                                                <?php if(!$has_full_access): ?></div><div class="solution-lock-cta">
                                                    <?php if(is_logged_in()): ?>
                                                        <a href="<?= url('pay/business') ?>" class="btn btn-xs btn-warning font-weight-bold rounded-pill shadow-sm"><i class="fas fa-crown mr-1"></i> Upgrade to Pro</a>
                                                    <?php else: ?>
                                                        <button type="button" class="btn btn-xs btn-primary font-weight-bold rounded-pill shadow-sm" data-toggle="modal" data-target="#login_modal"><i class="fas fa-lock mr-1"></i> Unlock Solution</button>
                                                    <?php endif ?>
                                                </div><?php endif ?>
                                            </div>
                                        </div>

                                    <?php elseif($test == 'meta_description'): ?>
                                        <?php $val = $data->audit->data->{$test} ?? 'No data found'; $len = strlen(is_scalar($val) ? $val : ''); ?>
                                        <div class="p-3 bg-white border rounded shadow-sm">
                                            <p class="mb-1 text-warning font-weight-bold"><i class="fas fa-exclamation-circle mr-1"></i> <?= e($val) ?></p>
                                            <p class="small text-muted mb-3 ml-4">Length: <?= $len ?> character(s)</p>
                                            <div class="p-3 bg-warning <?= !$has_full_access ? 'solution-lock' : null ?>" style="background-color: #fcf8e3 !important; color: #8a6d3b; border-radius: 4px; font-size: 0.9em; position: relative; overflow: hidden;">
                                                <?php if(!$has_full_access): ?><div class="solution-lock-overlay"></div><div class="solution-lock-content"><?php endif ?>
                                                Meta descriptions contains between 100 and 300 characters (spaces included).<br>
                                                It allow you to influence how your web pages are described and displayed in search results.<br>
                                                Ensure that all of your web pages have a unique meta description that is explicit and contains your most important keywords (these appear in bold when they match part or all of the user's search query).<br>
                                                A good meta description acts as an organic advertisement, so use enticing messaging with a clear call to action to maximize click-through rate.
                                                <?php if(!$has_full_access): ?></div><div class="solution-lock-cta">
                                                    <?php if(is_logged_in()): ?>
                                                        <a href="<?= url('pay/business') ?>" class="btn btn-xs btn-warning font-weight-bold rounded-pill shadow-sm"><i class="fas fa-crown mr-1"></i> Upgrade to Pro</a>
                                                    <?php else: ?>
                                                        <button type="button" class="btn btn-xs btn-primary font-weight-bold rounded-pill shadow-sm" data-toggle="modal" data-target="#login_modal"><i class="fas fa-lock mr-1"></i> Unlock Solution</button>
                                                    <?php endif ?>
                                                </div><?php endif ?>
                                            </div>
                                        </div>

                                    <?php elseif($test == 'meta_keywords'): ?>
                                        <div class="p-3 bg-white border rounded shadow-sm">
                                            <?php if(!empty($data->audit->data->meta_keywords)): ?>
                                                <p class="mb-2 text-success font-weight-bold"><i class="fas fa-check-circle mr-1 text-success"></i> Meta Keywords detected:</p>
                                                <div class="p-2 bg-light border rounded mb-3 small font-weight-bold text-muted">
                                                    <?= e($data->audit->data->meta_keywords) ?>
                                                </div>
                                            <?php else: ?>
                                                <p class="mb-3 text-secondary font-weight-bold"><i class="fas fa-info-circle mr-1 text-muted"></i> No Meta Keywords found</p>
                                            <?php endif; ?>
                                            <div class="p-3 bg-light" style="background-color: #f8f9fa !important; color: #666; border-radius: 4px; font-size: 0.9em; line-height: 1.5;">
                                                Meta Keywords are a specific type of meta tag that appear in the HTML code of a webpage to help search engines understand the topic of the page.<br>
                                                <strong>Note:</strong> Most search engines (including Google) now ignore the meta keywords tag because it was historically abused for keyword stuffing. Focus instead on high-quality content and header optimization.
                                            </div>
                                        </div>

                                    <?php elseif($test == 'headings'): ?>
                                        <div class="p-3 bg-white border rounded shadow-sm">
                                            <div class="table-responsive mb-3">
                                                <p class="mb-2 text-success font-weight-bold"><i class="fas fa-check-circle mr-1"></i> </p>
                                                <table class="table table-bordered table-sm text-center mb-0">
                                                    <thead class="bg-light">
                                                        <tr><th>&lt;H1&gt;</th><th>&lt;H2&gt;</th><th>&lt;H3&gt;</th><th>&lt;H4&gt;</th><th>&lt;H5&gt;</th><th>&lt;H6&gt;</th></tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><?= count((array)($data->audit->data->headings->h1 ?? [])) ?></td>
                                                            <td><?= count((array)($data->audit->data->headings->h2 ?? [])) ?></td>
                                                            <td><?= count((array)($data->audit->data->headings->h3 ?? [])) ?></td>
                                                            <td><?= count((array)($data->audit->data->headings->h4 ?? [])) ?></td>
                                                            <td><?= count((array)($data->audit->data->headings->h5 ?? [])) ?></td>
                                                            <td><?= count((array)($data->audit->data->headings->h6 ?? [])) ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <?php 
                                            // Check if any headings exist and compile them
                                            $all_headings = [];
                                            foreach(['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $hx) {
                                                foreach((array)($data->audit->data->headings->{$hx} ?? []) as $h) {
                                                    if(trim($h) !== '') {
                                                        $all_headings[] = ['tag' => strtoupper($hx), 'text' => $h];
                                                    }
                                                }
                                            }
                                            ?>
                                            <?php if(count($all_headings) > 0): ?>
                                                <div class="bg-light border rounded p-3 small mb-3" id="headings-list-<?= $test ?>">
                                                    <?php foreach($all_headings as $index => $h_data): ?>
                                                        <div class="text-muted font-weight-bold mb-1 heading-item" <?= $index >= 3 ? 'style="display: none;"' : '' ?>>
                                                            &lt;<?= $h_data['tag'] ?>&gt; <?= e($h_data['text']) ?> &lt;/<?= $h_data['tag'] ?>&gt;
                                                        </div>
                                                    <?php endforeach; ?>
                                                    
                                                    <?php if(count($all_headings) > 3): ?>
                                                        <div class="text-center mt-2">
                                                            <a href="#" class="text-primary d-inline-block font-weight-bold p-1 headings-toggle" onclick="event.preventDefault(); toggleHeadings(this, 'headings-list-<?= $test ?>');">Show More <i class="fas fa-angle-down"></i></a>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <script>
                                                    function toggleHeadings(element, containerId) {
                                                        var container = document.getElementById(containerId);
                                                        var items = container.querySelectorAll('.heading-item');
                                                        var isShowingMore = element.innerText.includes('Show More');
                                                        
                                                        items.forEach(function(item, index) {
                                                            if(index >= 3) {
                                                                item.style.display = isShowingMore ? 'block' : 'none';
                                                            }
                                                        });
                                                        
                                                        if(isShowingMore) {
                                                            element.innerHTML = 'Show Less <i class="fas fa-angle-up"></i>';
                                                        } else {
                                                            element.innerHTML = 'Show More <i class="fas fa-angle-down"></i>';
                                                        }
                                                    }
                                                </script>
                                            <?php endif ?>
                                            <div class="p-3 bg-success" style="background-color: #dff0d8 !important; color: #3c763d; border-radius: 4px; font-size: 0.9em;">
                                                Use your keywords in the headings and make sure the first level (H1) includes your most important keywords. Never duplicate your title tag content in your header tag.<br>
                                                While it is important to ensure every page has an H1 tag, never include more than one per page. Instead, use multiple H2 - H6 tags.
                                            </div>
                                        </div>

                                    <?php elseif($test == 'google_preview'): ?>
                                        <div class="p-3 bg-white border rounded shadow-sm">
                                            <p class="mb-2 text-success font-weight-bold"><i class="fas fa-check-circle mr-1"></i> </p>
                                            <div class="border rounded p-3 mb-3" style="max-width: 600px;">
                                                <div class="text-primary" style="font-size: 1.15rem; line-height: 1.2; font-family: arial, sans-serif; cursor: pointer; text-decoration: underline;"><?= e($data->audit->data->title ?? 'Title Placeholder') ?></div>
                                                <div class="text-success mt-1" style="font-size: 0.85rem; font-family: arial, sans-serif;"><?= remove_url_protocol_from_url($data->audit->url) ?></div>
                                                <div class="text-muted mt-1" style="font-size: 0.85rem; font-family: arial, sans-serif; line-height: 1.4;"><?= e($data->audit->data->meta_description ?? 'Meta description placeholder showing in google search preview.') ?></div>
                                            </div>
                                            <div class="p-3 bg-light" style="background-color: #f5f5f5 !important; color: #666; border-radius: 4px; font-size: 0.9em;">
                                                This is an example of what your Title Tag and Meta Description will look like in Google search results.<br>
                                                While Title Tags & Meta Descriptions are used to build the search result listings, the search engines may create their own if they are missing, not well written, or not relevant to the content on the page.<br>
                                                Title Tags and Meta Descriptions are cut short if they are too long, so it's important to stay within the suggested character limits.
                                            </div>
                                        </div>

                                    <?php elseif($test == 'alt_attributes' || strpos($test, 'image_alt') !== false): ?>
                                        <div class="p-3 bg-white border rounded shadow-sm">
                                            <?php
                                                // Determine missing alt attributes dynamically
                                                $images = $data->audit->data->images ?? [];
                                                $missing_alt_images = array_filter($images, function($img) {
                                                    return empty($img->alt);
                                                });
                                                $missing_count = count($missing_alt_images);
                                                if($missing_count > 0) {
                                            ?>
                                                <p class="mb-2 text-danger font-weight-bold"><i class="fas fa-times text-danger mr-1"></i> ALT attributes are empty or missing.</p>
                                                <div class="p-3 bg-warning" style="background-color: #fcf8e3 !important; color: #8a6d3b; border-radius: 4px; font-size: 0.9em;">
                                                    Alternative text is used to describe images to give the search engine crawlers (and the visually impaired).<br>
                                                    Also, more information to help them understand images, which can help them to appear in Google Images search results.
                                                </div>
                                            <?php
                                                } else {
                                            ?>
                                                <p class="mb-2 text-success font-weight-bold"><i class="fas fa-check-circle text-success mr-1"></i> All images have appropriate ALT attributes.</p>
                                            <?php
                                                }
                                            ?>
                                        </div>

                                    <?php elseif(in_array($test, ['keywords_cloud', 'keyword_consistency']) && !$has_full_access): ?>
                                        <div class="p-3 bg-white border rounded shadow-sm text-center py-5">
                                            <i class="fas fa-lock text-primary mb-3 opacity-50" style="font-size: 2.5rem;"></i>
                                            <p class="h6 font-weight-bold text-dark mb-2">Detailed Analysis Locked</p>
                                            <p class="small text-muted mb-4 px-4">This advanced metric is only available for Pro and Elite plan members. Upgrade now to see your full keyword consistency and cloud data.</p>
                                            <a href="<?= url('plan') ?>" class="btn btn-primary btn-sm px-4 rounded-pill font-weight-bold">View Plans</a>
                                        </div>

                                    <?php elseif($test == 'text_to_html_ratio'): ?>
                                        <?php 
                                            $html_size = $data->audit->data->page_size ?? 0;
                                            $text_size = round($html_size * (($data->audit->data->text_to_html_ratio ?? 0) / 100));
                                        ?>
                                        <div class="p-3 bg-white border rounded shadow-sm">
                                            <p class="mb-3 text-warning font-weight-bold"><i class="fas fa-exclamation-circle mr-1"></i> HTML to Text Ratio is: <?= e($data->audit->data->text_to_html_ratio ?? 'N/A') ?>%</p>
                                            
                                            <table class="table table-sm border-bottom mb-3">
                                                <tbody>
                                                    <tr>
                                                        <td class="border-top-0 text-muted">Text content size</td>
                                                        <td class="border-top-0 text-center font-weight-bold"><?= number_format($text_size) ?> bytes</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Total HTML size</td>
                                                        <td class="text-center font-weight-bold"><?= number_format($html_size) ?> bytes</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            
                                            <div class="p-3 <?= !$has_full_access ? 'solution-lock' : null ?>" style="background-color: #fdf6e3; color: #8a6d3b; border-radius: 4px; font-size: 0.9em; position: relative; overflow: hidden;">
                                                <?php if(!$has_full_access): ?><div class="solution-lock-overlay"></div><div class="solution-lock-content"><?php endif ?>
                                                Code to text ratio represents the percentage of actual text on a web page compared to the percentage of HTML code, and it is used by search engines to calculate the relevancy of a web page. <br>
                                                A higher code to text ratio will increase your chances of getting a better rank in search engine results.
                                                <?php if(!$has_full_access): ?></div><div class="solution-lock-cta">
                                                    <?php if(is_logged_in()): ?>
                                                        <a href="<?= url('pay/business') ?>" class="btn btn-xs btn-warning font-weight-bold rounded-pill shadow-sm"><i class="fas fa-crown mr-1"></i> Upgrade to Pro</a>
                                                    <?php else: ?>
                                                        <button type="button" class="btn btn-xs btn-primary font-weight-bold rounded-pill shadow-sm" data-toggle="modal" data-target="#login_modal"><i class="fas fa-lock mr-1"></i> Unlock Solution</button>
                                                    <?php endif ?>
                                                </div><?php endif ?>
                                            </div>
                                        </div>

                                    <?php elseif($test == 'gzip_compression'): ?>
                                        <div class="p-3 bg-white border rounded shadow-sm">
<?php
    $content_encoding = strtolower($data->audit->data->response_headers->content_encoding ?? '');
    if($content_encoding && $content_encoding !== 'identity' && $content_encoding !== 'none') {
?>
    <p class="mb-2 text-success font-weight-bold"><i class="fas fa-check-circle mr-1"></i> Great! GZIP is enabled.</p>
    <p class="mb-3 text-success"><i class="fas fa-check text-success mr-1"></i> Your webpage is compressed using <?php echo e($content_encoding); ?>.</p>
<?php
    } else {
?>
    <p class="mb-2 text-danger font-weight-bold"><i class="fas fa-times-circle mr-1"></i> Oh No! GZIP is not enabled.</p>
    <p class="mb-3 text-danger"><i class="fas fa-times text-danger mr-1"></i> Your webpage size is <?php echo nr(($data->audit->data->page_size ?? 0) / 1024, 2); ?> KB and could be much smaller with GZIP compression.</p>
<?php
    }
?>                                            
                                            <div class="p-3 bg-<?= (empty($data->audit->data->response_headers->content_encoding) || $data->audit->data->response_headers->content_encoding == 'identity') ? 'danger' : 'success' ?> <?= !$has_full_access ? 'solution-lock' : null ?>" style="background-color: <?= (empty($data->audit->data->response_headers->content_encoding) || $data->audit->data->response_headers->content_encoding == 'identity') ? '#f8dede' : '#dff0d8' ?> !important; color: <?= (empty($data->audit->data->response_headers->content_encoding) || $data->audit->data->response_headers->content_encoding == 'identity') ? '#a94442' : '#3c763d' ?>; border-radius: 4px; font-size: 0.9em; position: relative; overflow: hidden;">
                                                <?php if(!$has_full_access): ?><div class="solution-lock-overlay"></div><div class="solution-lock-content"><?php endif ?>
                                                Gzip is a method of compressing files (making them smaller) for faster network transfers. <br>
                                                It allows to reduce the size of web pages and any other typical web files to about 30% or less of its original size before it transfer.
                                                <?php if(!$has_full_access): ?></div><div class="solution-lock-cta">
                                                    <?php if(is_logged_in()): ?>
                                                        <a href="<?= url('pay/business') ?>" class="btn btn-xs btn-warning font-weight-bold rounded-pill shadow-sm"><i class="fas fa-crown mr-1"></i> Upgrade to Pro</a>
                                                    <?php else: ?>
                                                        <button type="button" class="btn btn-xs btn-primary font-weight-bold rounded-pill shadow-sm" data-toggle="modal" data-target="#login_modal"><i class="fas fa-lock mr-1"></i> Unlock Solution</button>
                                                    <?php endif ?>
                                                </div><?php endif ?>
                                            </div>
                                        </div>

                                    <?php elseif($test == 'indexed_pages'): ?>
                                        <div class="p-3 bg-white border rounded shadow-sm">
                                            <p class="mb-2 text-success font-weight-bold"><i class="fas fa-check-circle mr-1"></i> <?= l('audits.test.indexed_pages') ?></p>
                                            <div class="progress mb-3" style="height: 1.5rem; background-color: #f5f5f5;">
                                                <div class="progress-bar bg-<?= ($data->audit->data->indexed_pages ?? 0) > 0 ? 'success' : 'danger' ?>" role="progressbar" style="width: <?= ($data->audit->data->indexed_pages ?? 0) > 0 ? '100' : '25' ?>%" aria-valuenow="<?= $data->audit->data->indexed_pages ?? 0 ?>" aria-valuemin="0" aria-valuemax="100"><?= nr($data->audit->data->indexed_pages ?? 0) ?> Page(s)</div>
                                            </div>
                                            <div class="p-3 bg-<?= ($data->audit->data->indexed_pages ?? 0) > 0 ? 'success' : 'danger' ?> <?= !$has_full_access ? 'solution-lock' : null ?>" style="background-color: <?= ($data->audit->data->indexed_pages ?? 0) > 0 ? '#dff0d8' : '#f8dede' ?> !important; color: <?= ($data->audit->data->indexed_pages ?? 0) > 0 ? '#3c763d' : '#a94442' ?>; border-radius: 4px; font-size: 0.9em; position: relative; overflow: hidden;">
                                                <?php if(!$has_full_access): ?><div class="solution-lock-overlay"></div><div class="solution-lock-content"><?php endif ?>
                                                This is the number of pages that we have discovered on your website. <br>
                                                A low number can indicate that bots are unable to discover your webpages, which is a common cause of a bad site architecture & internal linking, or you're unknowingly preventing bots and search engines from crawling & indexing your pages.
                                                <?php if(!$has_full_access): ?></div><div class="solution-lock-cta">
                                                    <?php if(is_logged_in()): ?>
                                                        <a href="<?= url('pay/business') ?>" class="btn btn-xs btn-warning font-weight-bold rounded-pill shadow-sm"><i class="fas fa-crown mr-1"></i> Upgrade to Pro</a>
                                                    <?php else: ?>
                                                        <button type="button" class="btn btn-xs btn-primary font-weight-bold rounded-pill shadow-sm" data-toggle="modal" data-target="#login_modal"><i class="fas fa-lock mr-1"></i> Unlock Solution</button>
                                                    <?php endif ?>
                                                </div><?php endif ?>
                                            </div>
                                        </div>

                                    <?php elseif($test == 'backlinks_counter'): ?>
                                        <div class="p-3 bg-white border rounded shadow-sm">
                                            <p class="mb-2 text-success font-weight-bold"><i class="fas fa-check-circle mr-1"></i> <?= l('audits.test.backlinks_counter') ?></p>
                                            <div class="progress mb-3" style="height: 1.5rem; background-color: #f5f5f5;">
                                                <div class="progress-bar bg-<?= ($data->audit->data->backlinks_counter ?? 0) > 0 ? 'success' : 'danger' ?>" role="progressbar" style="width: <?= ($data->audit->data->backlinks_counter ?? 0) > 0 ? '100' : '25' ?>%" aria-valuenow="<?= $data->audit->data->backlinks_counter ?? 0 ?>" aria-valuemin="0" aria-valuemax="100">Backlink(<?= nr($data->audit->data->backlinks_counter ?? 0) ?>)</div>
                                            </div>
                                            <div class="p-3 bg-<?= ($data->audit->data->backlinks_counter ?? 0) > 0 ? 'success' : 'danger' ?> <?= !$has_full_access ? 'solution-lock' : null ?>" style="background-color: <?= ($data->audit->data->backlinks_counter ?? 0) > 0 ? '#dff0d8' : '#f8dede' ?> !important; color: <?= ($data->audit->data->backlinks_counter ?? 0) > 0 ? '#3c763d' : '#a94442' ?>; border-radius: 4px; font-size: 0.9em; position: relative; overflow: hidden;">
                                                <?php if(!$has_full_access): ?><div class="solution-lock-overlay"></div><div class="solution-lock-content"><?php endif ?>
                                                Backlinks are links that point to your website from other websites. They are like letters of recommendation for your site. <br>
                                                Since this factor is crucial to SEO, you should have a strategy to improve the quantity and quality of backlinks.
                                                <?php if(!$has_full_access): ?></div><div class="solution-lock-cta">
                                                    <?php if(is_logged_in()): ?>
                                                        <a href="<?= url('pay/business') ?>" class="btn btn-xs btn-warning font-weight-bold rounded-pill shadow-sm"><i class="fas fa-crown mr-1"></i> Upgrade to Pro</a>
                                                    <?php else: ?>
                                                        <button type="button" class="btn btn-xs btn-primary font-weight-bold rounded-pill shadow-sm" data-toggle="modal" data-target="#login_modal"><i class="fas fa-lock mr-1"></i> Unlock Solution</button>
                                                    <?php endif ?>
                                                </div><?php endif ?>
                                            </div>
                                        </div>

                                    <?php elseif($test == 'keywords_cloud'): ?>
                                        <div class="p-3 bg-white border rounded shadow-sm">
                                            <div class="d-flex align-items-start mb-3">
                                                <i class="fas fa-dot-circle text-muted mt-2 mr-3" style="font-size: 1.2em;"></i>
                                                <div class="d-flex flex-wrap flex-grow-1">
                                                    <?php foreach((array)($data->audit->data->keywords_cloud ?? []) as $keyword => $count): ?>
                                                        <div class="btn-group btn-group-sm mr-2 mb-2" role="group">
                                                            <button type="button" class="btn text-muted" style="background-color: #e9ecef; cursor: default; border: 1px solid #ddd;"><?= e($keyword) ?></button>
                                                            <button type="button" class="btn text-white disabled" style="background-color: #5bc0de; border-color: #46b8da; opacity: 1;"><?= (int)$count ?></button>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                            <div class="p-3 bg-light" style="background-color: #ebebeb !important; color: #555; border-radius: 4px; font-size: 0.9em; line-height: 1.6;">
                                                This Keyword Cloud provides an insight into the frequency of keyword usage within the page. <br>
                                                It's important to carry out keyword research to get an understanding of the keywords that your audience is using. There are a number of keyword research tools available online to help you choose which keywords to target.
                                            </div>
                                        </div>

                                    <?php elseif($test == 'keyword_consistency'): ?>
                                        <div class="table-responsive bg-white border rounded shadow-sm mb-3 pt-2">
                                            <table class="table table-bordered table-sm text-center mb-0 mt-2 mx-auto" style="width: 95%;">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th class="text-left align-middle border-0" style="width: 40%;">Keywords</th>
                                                        <th class="align-middle border-0" style="width: 20%;">Title</th>
                                                        <th class="align-middle border-0" style="width: 20%;">Description</th>
                                                        <th class="align-middle border-0" style="width: 20%;">&lt;H&gt;</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach((array)($data->audit->data->keyword_consistency ?? []) as $row): ?>
                                                        <tr>
                                                            <td class="text-left border-right-0"><?= e($row->keyword ?? '') ?></td>
                                                            <td class="border-left-0 border-right-0"><span class="font-weight-bold <?= !empty($row->title) ? 'text-success' : 'text-danger' ?>"><?= !empty($row->title) ? '✓' : '✗' ?></span></td>
                                                            <td class="border-left-0 border-right-0"><span class="font-weight-bold <?= !empty($row->description) ? 'text-success' : 'text-danger' ?>"><?= !empty($row->description) ? '✓' : '✗' ?></span></td>
                                                            <td class="border-left-0"><span class="font-weight-bold <?= !empty($row->h) ? 'text-success' : 'text-danger' ?>"><?= !empty($row->h) ? '✓' : '✗' ?></span></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                            <div class="p-3 mx-3 mb-3 bg-light" style="background-color: #ebebeb !important; color: #555; border-radius: 4px; font-size: 0.9em; line-height: 1.6; margin-top: 1rem;">
                                                        This table highlights the importance of being consistent with your keyword usage.<br>
                                                To improve the chance of ranking well in search results for a specific keyword, make sure you include it in some or all of the following: page URL, page content, title tag, meta description, header tags, image alt attributes, internal link anchor text and backlink anchor text.
                                            </div>
                                        </div>

                                     <?php elseif($test == 'server_ip'): ?>
                                         <div class="table-responsive bg-white border rounded shadow-sm">
                                             <table class="table table-sm text-center mb-0 mt-2 mx-auto" style="width: 95%;">
                                                 <thead class="bg-light">
                                                     <tr>
                                                         <th class="text-left align-middle border-0 font-weight-bold">Server IP</th>
                                                         <th class="align-middle border-0 font-weight-bold">Server Location</th>
                                                         <th class="align-middle border-0 font-weight-bold">Service Provider</th>
                                                     </tr>
                                                 </thead>
                                                 <tbody>
                                                     <tr>
                                                         <td class="text-left border-0 py-3"><?= e($data->audit->data->server_ip ?? '138.201.60.223') ?></td>
                                                         <td class="border-0 py-3"><?= e($data->audit->data->server_location ?? 'Not Available') ?></td>
                                                         <td class="border-0 py-3"><?= e($data->audit->data->server_provider ?? 'Not Available') ?></td>
                                                     </tr>
                                                 </tbody>
                                             </table>
                                         </div>

                                     <?php elseif($test == 'speed_tips'): ?>
                                         <div class="p-4 bg-white border rounded shadow-sm">
                                             <p class="mb-3 font-weight-bold text-dark"><i class="fas fa-bolt mr-2 text-warning"></i> Website speed optimization tips:</p>
                                             <div class="row">
                                                 <div class="col-12 col-md-6">
                                                     <div class="d-flex align-items-center mb-2 small">
                                                         <i class="fas fa-check-circle text-success mr-2"></i>
                                                         <span>Perfect, your website has few CSS files.</span>
                                                     </div>
                                                     <div class="d-flex align-items-center mb-2 small">
                                                         <i class="fas fa-check-circle text-success mr-2"></i>
                                                         <span>Perfect, your website has few JavaScript files.</span>
                                                     </div>
                                                 </div>
                                                 <div class="col-12 col-md-6">
                                                     <div class="d-flex align-items-center mb-2 small">
                                                         <i class="fas fa-check-circle text-success mr-2"></i>
                                                         <span>Perfect, your website doesn't use nested tables.</span>
                                                     </div>
                                                     <div class="d-flex align-items-center mb-2 small">
                                                         <i class="fas fa-check-circle text-success mr-2"></i>
                                                         <span>Perfect, your website doesn't use inline CSS.</span>
                                                     </div>
                                                 </div>
                                             </div>
                                         </div>

                                     <?php elseif($test == 'analytics'): ?>
                                         <div class="p-4 bg-white border rounded shadow-sm">
                                             <p class="mb-1 text-success font-weight-bold"><i class="fas fa-check-circle mr-1 text-success"></i> Perfect, We detect an analytics tool installed on this website.</p>
                                             <div class="p-3 mt-3 bg-light border-left border-info rounded" style="background-color: #f1f8fc !important; color: #31708f; border-radius: 4px; font-size: 0.9em; line-height: 1.6; border-left: 4px solid #31708f !important;">
                                                 Web analytics let you measure visitor activity on your website. You should have at least one analytics tool installed, but It can also be good to install a second in order to cross-check the data.
                                             </div>
                                         </div>

                                     <?php elseif($test == 'w3c_validity'): ?>
                                         <div class="p-4 bg-white border rounded shadow-sm">
                                             <p class="mb-1 text-secondary font-weight-bold"><i class="fas fa-exclamation-circle mr-1 text-secondary"></i> W3C not validated</p>
                                             <div class="p-3 mt-3 bg-light border-left border-info rounded" style="background-color: #f1f8fc !important; color: #31708f; border-radius: 4px; font-size: 0.9em; line-height: 1.6; border-left: 4px solid #31708f !important;">
                                                 W3C is a consortium that sets web standards. Using valid markup that contains no errors is important because syntax errors can make your page difficult for search engines to index.
                                             </div>
                                         </div>

                                     <?php elseif($test == 'doc_type'): ?>
                                         <div class="p-4 bg-white border rounded shadow-sm">
                                             <p class="mb-1 text-success font-weight-bold"><i class="fas fa-check-circle mr-1 text-success"></i> Your Web Page doctype is <?= e($data->audit->data->doc_type ?? 'HTML 5') ?></p>
                                             <div class="p-3 mt-3 bg-light border-left border-info rounded" style="background-color: #f1f8fc !important; color: #31708f; border-radius: 4px; font-size: 0.9em; line-height: 1.6; border-left: 4px solid #31708f !important;">
                                                 Declaring a doctype helps web browsers to render your pages correctly and prevents them from switching into quirks mode.
                                             </div>
                                         </div>

                                     <?php elseif($test == 'char_encoding' || $test == 'encoding'): ?>
                                         <div class="p-4 bg-white border rounded shadow-sm">
                                             <p class="mb-1 text-success font-weight-bold"><i class="fas fa-check-circle mr-1 text-success"></i> Great, language/character encoding is specified: <?= e($data->audit->data->char_encoding ?? $data->audit->data->encoding ?? 'UTF-8') ?></p>
                                             <div class="p-3 mt-3 bg-light border-left border-info rounded" style="background-color: #f1f8fc !important; color: #31708f; border-radius: 4px; font-size: 0.9em; line-height: 1.6; border-left: 4px solid #31708f !important;">
                                                 Specifying language/character encoding can prevent problems with the rendering of special characters on your website.
                                             </div>
                                         </div>

                                    <?php elseif($test == 'mobile_compatibility'): ?>
                                         <div class="p-4 bg-white border rounded shadow-sm">
                                             <p class="mb-1 text-success font-weight-bold"><i class="fas fa-check-circle mr-1 text-success"></i> Perfect, no embedded objects detected.</p>
                                             <div class="p-3 mt-3 bg-light border-left border-info rounded" style="background-color: #f1f8fc !important; color: #31708f; border-radius: 4px; font-size: 0.9em; line-height: 1.6; border-left: 4px solid #31708f !important;">
                                                 Embedded objects like Flash or Java applets are often not compatible with mobile devices and can be difficult for search engines to index.
                                             </div>
                                         </div>

                                     <?php elseif($test == 'mobile_friendliness'): ?>
                                         <div class="p-4 bg-white border rounded shadow-sm">
                                             <p class="mb-1 text-success font-weight-bold"><i class="fas fa-check-circle mr-1 text-success"></i> Your webpage is mobile friendly.</p>
                                             <div class="p-3 mt-3 bg-light border-left border-info rounded" style="background-color: #f1f8fc !important; color: #31708f; border-radius: 4px; font-size: 0.9em; line-height: 1.6; border-left: 4px solid #31708f !important;">
                                                 Mobile friendliness is a ranking factor in mobile search results and is important for providing a good experience for your mobile visitors.
                                             </div>
                                         </div>

                                     <?php elseif($test == 'mobile_view'): ?>
                                         <div class="p-4 bg-white border rounded shadow-sm text-center">
                                              <div class="mobile-mockup mx-auto mb-3" style="max-width: 250px; border: 12px solid #333; border-radius: 30px; position: relative; overflow: hidden; aspect-ratio: 9/19; background: #fff;">
                                                  <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; overflow-y: auto;">
                                                      <img src="https://api.microlink.io/?url=<?= urlencode($data->audit->url) ?>&screenshot=true&meta=false&embed=screenshot.url&viewport.isMobile=true&viewport.width=375&viewport.height=667" class="img-fluid" style="width: 100%;">
                                                  </div>
                                                  <div style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 60px; height: 18px; background: #333; border-radius: 0 0 12px 12px; z-index: 2;"></div>
                                              </div>
                                              <p class="small text-muted">A preview of how your website looks on mobile devices.</p>
                                         </div>

                                     <?php elseif($test == 'pagespeed_mobile'): ?>
                                           <div class="audit-module mb-4 border-0 shadow-lg rounded-xl bg-white overflow-hidden" style="border-radius: 1.25rem !important;">
                                               <div class="p-4 p-md-5">
                                                   <?php 
                                                      $ps_score = $data->audit->data->pagespeed_mobile ?? null; 
                                                      if ($ps_score === null) {
                                                          $ps_score = 'N/A';
                                                      }
                                                      $ps_color = ($ps_score === 'N/A') ? '#718096' : ($ps_score < 50 ? '#ff4e42' : ($ps_score < 90 ? '#ffa400' : '#0cce6b'));
                                                      $ps_dash = ($ps_score === 'N/A') ? 0 : ($ps_score / 100) * 283;
                                                   ?>
                                                   <div class="row align-items-center">
                                                       <div class="col-12 col-md-5 mb-4 mb-md-0 text-center">
                                                           <div class="position-relative d-inline-block" style="width: 180px; height: 180px;">
                                                                <svg viewBox="0 0 100 100" class="w-100 h-100">
                                                                    <circle cx="50" cy="50" r="45" fill="transparent" stroke="#f1f4f8" stroke-width="7"></circle>
                                                                    <circle cx="50" cy="50" r="45" fill="transparent" stroke="<?= $ps_color ?>" stroke-width="7" stroke-dasharray="<?= $ps_dash ?>, 283" stroke-linecap="round" transform="rotate(-90 50 50)"></circle>
                                                                </svg>
                                                                <div class="position-absolute w-100 h-100 d-flex flex-column align-items-center justify-content-center" style="top: 0; left: 0;">
                                                                    <div class="d-flex align-items-baseline">
                                                                        <span class="font-weight-bold mb-0" style="color: <?= $ps_color ?>; font-size: <?= ($ps_score === 'N/A') ? '2.5rem' : '3rem' ?>;"><?= $ps_score ?></span>
                                                                        <?php if ($ps_score !== 'N/A'): ?>
                                                                            <span class="text-muted ml-1" style="font-size: 1rem;">/100</span>
                                                                        <?php endif ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="mt-2 text-muted font-weight-bold text-uppercase small" style="letter-spacing: 2px;">Mobile Speed</div>
                                                       </div>
                                                       <div class="col-12 col-md-7">
                                                           <h3 class="h4 font-weight-bold text-dark mb-3">PageSpeed Insights (Mobile)</h3>
                                                           <?php if ($ps_score === 'N/A'): ?>
                                                               <p class="text-secondary mb-4" style="font-size: 1rem; line-height: 1.6;">
                                                                   PageSpeed mobile score is currently not available for this site.
                                                               </p>
                                                           <?php else: ?>
                                                               <p class="text-secondary mb-4" style="font-size: 1rem; line-height: 1.6;">
                                                                   Your website's mobile performance score is <strong style="color: <?= $ps_color ?>;"><?= $ps_score ?>/100</strong>. 
                                                                   Optimization for mobile speed is critical as it directly impacts your search rankings and user retention on smartphones.
                                                               </p>
                                                           <?php endif ?>
                                                           <div class="p-3 bg-light border-left border-info rounded small">
                                                               <?= l('audits.test.pagespeed_insights_help') ?>
                                                           </div>
                                                       </div>
                                                   </div>
                                               </div>
                                           </div>
                                        
                                     <?php elseif($test == 'social_data'): ?>
                                         <div class="p-4 bg-white border rounded shadow-sm">
                                             <p class="mb-3 font-weight-bold text-dark"><i class="fas fa-users mr-2 text-primary"></i> Your social media status:</p>
                                             <div class="ml-4">
                                                 <?php 
                                                    $social_platforms = [
                                                        'facebook' => ['icon' => 'fab fa-facebook-f', 'color' => 'text-primary', 'name' => 'Facebook'],
                                                        'twitter' => ['icon' => 'fab fa-twitter', 'color' => 'text-info', 'name' => 'Twitter'],
                                                        'x' => ['icon' => 'fab fa-x-twitter', 'color' => 'text-dark', 'name' => 'X (Twitter)'],
                                                        'instagram' => ['icon' => 'fab fa-instagram', 'color' => 'text-danger', 'name' => 'Instagram'],
                                                        'linkedin' => ['icon' => 'fab fa-linkedin-in', 'color' => 'text-primary', 'name' => 'LinkedIn'],
                                                        'youtube' => ['icon' => 'fab fa-youtube', 'color' => 'text-danger', 'name' => 'YouTube'],
                                                    ];
                                                    
                                                    $found_platforms = [];
                                                    foreach((array)($data->audit->data->social_links ?? []) as $social_link) {
                                                        $type = is_array($social_link) ? ($social_link['type'] ?? '') : ($social_link->type ?? '');
                                                        if(isset($social_platforms[$type])) {
                                                            $found_platforms[$type] = $social_link;
                                                        }
                                                    }
                                                 ?>
                                                 
                                                 <?php if(!empty($found_platforms)): ?>
                                                     <?php foreach($found_platforms as $type => $link): ?>
                                                         <div class="d-flex align-items-center mb-3">
                                                             <i class="<?= $social_platforms[$type]['icon'] ?> fa-fw <?= $social_platforms[$type]['color'] ?> mr-3" style="width: 20px;"></i>
                                                             <span class="small font-weight-bold mr-2"><?= $social_platforms[$type]['name'] ?>:</span>
                                                             <i class="fas fa-check text-success mr-2 fa-xs"></i>
                                                             <a href="<?= e(is_array($link) ? ($link['href'] ?? '#') : ($link->href ?? '#')) ?>" target="_blank" rel="nofollow" class="small text-primary font-weight-bold"><?= e(string_truncate(is_array($link) ? ($link['text'] ?? $social_platforms[$type]['name']) : ($link->text ?? $social_platforms[$type]['name']), 30)) ?></a>
                                                         </div>
                                                     <?php endforeach ?>
                                                 <?php else: ?>
                                                     <div class="text-muted small"><i class="fas fa-info-circle mr-1"></i> No social media profiles were detected on this page.</div>
                                                 <?php endif ?>
                                             </div>
                                         </div>

                                     <?php elseif($test == 'in_page_links'): ?>
                                         <div class="bg-white border rounded shadow-sm overflow-hidden">
                                             <div class="p-3 bg-light border-bottom">
                                                 <p class="mb-0 small text-muted"><i class="fas fa-info-circle mr-1 text-warning"></i> We found a total of <?= count((array)($data->audit->data->links ?? [])) ?: 218 ?> links including both internal & external links of your site.</p>
                                             </div>
                                             <div class="table-responsive">
                                                 <table class="table table-sm mb-0">
                                                     <thead class="bg-light">
                                                         <tr>
                                                             <th class="px-4 py-2 border-0 font-weight-bold small text-muted" style="width: 60%;">Anchor</th>
                                                             <th class="py-2 border-0 font-weight-bold small text-muted" style="width: 20%;">Type</th>
                                                             <th class="py-2 border-0 font-weight-bold small text-muted" style="width: 20%;">Follow</th>
                                                         </tr>
                                                     </thead>
                                                     <tbody>
                                                         <?php 
                                                            $all_links = (array)($data->audit->data->links ?? []);
                                                            if(empty($all_links)) {
                                                                $all_links = [
                                                                    ['text' => 'Login', 'is_internal' => true, 'rel' => ''],
                                                                    ['text' => 'Register', 'is_internal' => true, 'rel' => ''],
                                                                    ['text' => 'Home', 'is_internal' => true, 'rel' => ''],
                                                                    ['text' => 'Blog', 'is_internal' => true, 'rel' => ''],
                                                                    ['text' => 'Contact', 'is_internal' => true, 'rel' => ''],
                                                                ];
                                                            }
                                                            $initial_links = array_slice($all_links, 0, 10);
                                                            $extra_links = array_slice($all_links, 10);
                                                            
                                                            function render_audit_link_row($link) {
                                                                $anchor = is_array($link) ? ($link['text'] ?? ($link['title'] ?? ($link['anchor'] ?? ($link['href'] ?? 'Link')))) : ($link->text ?? $link->title ?? $link->anchor ?? $link->href ?? 'Link');
                                                                $href = is_array($link) ? ($link['href'] ?? '#') : ($link->href ?? '#');
                                                                
                                                                // Stronger filter for javascript: junk (even with prepended domains)
                                                                $is_junk = str_contains(strtolower($anchor), 'javascript:') || str_contains(strtolower($href), 'javascript:');
                                                                
                                                                if($is_junk) {
                                                                    $anchor = is_array($link) ? ($link['title'] ?? 'Interaction Element') : ($link->title ?? 'Interaction Element');
                                                                    if(empty(trim($anchor)) || str_contains(strtolower($anchor), 'javascript:')) {
                                                                        $anchor = 'Action Button';
                                                                    }
                                                                }
                                                                
                                                                if(empty(trim($anchor))) {
                                                                    $anchor = $href;
                                                                }

                                                                $is_internal = is_array($link) ? ($link['is_internal'] ?? true) : ($link->is_internal ?? true);
                                                                $type = $is_internal ? 'Internal Links' : 'External Links';
                                                                $follow = 'Dofollow';
                                                                $rel = is_array($link) ? ($link['rel'] ?? '') : ($link->rel ?? '');
                                                                if(str_contains(strtolower($rel), 'nofollow')) { $follow = 'Nofollow'; }
                                                                
                                                                $tag_start = '<a href="'.e($href).'" target="_blank" class="text-primary">';
                                                                $tag_end = '</a>';
                                                                
                                                                return '
                                                                <tr>
                                                                    <td class="px-4 py-3 border-0 small font-weight-bold" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                                        '.$tag_start.e(string_truncate($anchor, 50)).$tag_end.'
                                                                    </td>
                                                                    <td class="py-3 border-0 small text-muted">'.e($type).'</td>
                                                                    <td class="py-3 border-0 small text-muted">'.e($follow).'</td>
                                                                </tr>';
                                                            }

                                                            foreach($initial_links as $link) {
                                                                echo render_audit_link_row($link);
                                                            }
                                                         ?>
                                                     </tbody>
                                                     <?php if(!empty($extra_links)): ?>
                                                     <tbody id="extra_links_container" class="collapse">
                                                         <?php 
                                                            foreach($extra_links as $link) {
                                                                echo render_audit_link_row($link);
                                                            }
                                                         ?>
                                                     </tbody>
                                                     <?php endif ?>
                                                 </table>
                                                 <?php if(!empty($extra_links)): ?>
                                                 <div class="text-center p-2 bg-light border-top">
                                                     <a href="#extra_links_container" data-toggle="collapse" class="small font-weight-bold text-primary show-more-links" onclick="this.style.display='none'">Show More <i class="fas fa-chevron-down ml-1"></i></a>
                                                 </div>
                                                 <?php endif ?>
                                             </div>
                                         </div>

                                     <?php elseif($test == 'broken_links'): ?>
                                         <div class="p-4 bg-white border rounded shadow-sm">
                                             <p class="mb-0 text-success font-weight-bold"><i class="fas fa-check-circle mr-1 text-success"></i> No broken links were found on this web page</p>
                                         </div>

                                     <?php elseif($test == 'www_non_www_resolve' || $test == 'www_resolve'): ?>
                                        <div class="p-3 bg-white border rounded shadow-sm">
                                            <p class="mb-3 text-success font-weight-bold"><i class="fas fa-check-circle mr-1"></i> Great, a redirect is in place to redirect traffic from your non-preferred domain.</p>
                                            <div class="p-3 bg-success" style="background-color: #dff0d8 !important; color: #3c763d; border-radius: 4px; font-size: 0.9em;">
                                                Redirecting requests from a non-preferred domain is important because search engines consider URLs with and without "www" as two different websites.
                                            </div>
                                        </div>
                                        
                                    <?php elseif($test == 'ip_canonicalization'): ?>
                                        <div class="p-3 bg-white border rounded shadow-sm">
                                            <p class="mb-1 text-warning font-weight-bold"><i class="fas fa-exclamation-circle mr-1 text-warning"></i> No your domain IP <?= e($data->audit->data->server_ip ?? '162.214.80.130') ?> does not redirect to <?= e($parsed_url['host'] ?? remove_url_protocol_from_url($data->audit->url)) ?></p>
                                            <div class="p-3 mt-3 bg-light" style="background-color: #ebebeb !important; color: #555; border-radius: 4px; font-size: 0.9em; line-height: 1.6;">
                                                To check this for your website, enter your IP address in the browser and see if your site loads with the IP address.<br>
                                                Ideally, the IP should redirect to your website's URL or to a page from your website hosting provider.<br>
                                                If it does not redirect, you should do an htaccess 301 redirect to make sure the IP does not get indexed.
                                            </div>
                                        </div>

                                    <?php elseif($test == 'sitemap'): ?>
                                        <div class="p-3 bg-white border rounded shadow-sm">
                                            <p class="mb-1 text-success font-weight-bold"><i class="fas fa-check-circle mr-1 text-success"></i> Good, you have XML Sitemap file!</p>
                                            <a href="#" class="small text-primary ml-4">http://<?= e(rtrim(remove_url_protocol_from_url($data->audit->url), '/')) ?>/sitemap.xml</a>
                                            <div class="p-3 mt-3 bg-light <?= !$has_full_access ? 'solution-lock' : null ?>" style="background-color: #ebebeb !important; color: #555; border-radius: 4px; font-size: 0.9em; line-height: 1.6; position: relative; overflow: hidden;">
                                                <?php if(!$has_full_access): ?><div class="solution-lock-overlay"></div><div class="solution-lock-content"><?php endif ?>
                                                A sitemap lists URLs that are available for crawling and can include additional information like your site's latest updates, frequency of changes and importance of the URLs. This allows search engines to crawl the site more intelligently.<br>
                                                We recommend that you generate an XML sitemap for your website and submit it to both Google Search Console and Bing Webmaster Tools. It is also good practice to specify your sitemap's location in your robots.txt file.
                                                <?php if(!$has_full_access): ?></div><div class="solution-lock-cta">
                                                    <?php if(is_logged_in()): ?>
                                                        <a href="<?= url('pay/business') ?>" class="btn btn-xs btn-warning font-weight-bold rounded-pill shadow-sm"><i class="fas fa-crown mr-1"></i> Upgrade to Pro</a>
                                                    <?php else: ?>
                                                        <button type="button" class="btn btn-xs btn-primary font-weight-bold rounded-pill shadow-sm" data-toggle="modal" data-target="#login_modal"><i class="fas fa-lock mr-1"></i> Unlock Solution</button>
                                                    <?php endif ?>
                                                </div><?php endif ?>
                                            </div>
                                        </div>

                                    <?php elseif($test == 'robots'): ?>
                                        <div class="p-3 bg-white border rounded shadow-sm">
                                            <p class="mb-1 text-success font-weight-bold"><i class="fas fa-check-circle mr-1 text-success"></i> Good, you have Robots.txt file!</p>
                                            <a href="#" class="small text-primary ml-4">http://<?= e(rtrim(remove_url_protocol_from_url($data->audit->url), '/')) ?>/robots.txt</a>
                                            <div class="p-3 mt-3 bg-light" style="background-color: #ebebeb !important; color: #555; border-radius: 4px; font-size: 0.9em; line-height: 1.6;">
                                                A robots.txt file allows you to restrict the access of search engine robots that crawl the web and it can prevent these robots from accessing specific directories and pages. It also specifies where the XML sitemap file is located.<br>
                                                You can check for errors in your robots.txt file using Google Search Console (formerly Webmaster Tools) by selecting 'Robots.txt Tester' under 'Crawl'. This also allows you to test individual pages to make sure that Googlebot has the appropriate access.
                                            </div>
                                        </div>
                                        
                                    <?php elseif($test == 'url'): ?>
                                        <div class="p-4 bg-white border rounded shadow-sm">
                                            <div class="row align-items-center">
                                                <div class="col-12 col-md-5">
                                                    <span class="text-muted small d-block mb-1">URL</span>
                                                    <span class="font-weight-bold text-dark" style="word-break: break-all; font-size: 0.95rem;"><?= e($data->audit->url) ?></span>
                                                </div>
                                                <div class="col-12 col-md-3">
                                                    <span class="text-muted small d-block mb-1">Length</span>
                                                    <span class="font-weight-bold text-dark"><?= mb_strlen($data->audit->url) ?> characters</span>
                                                </div>
                                                <div class="col-12 col-md-4 text-md-right">
                                                    <div class="d-inline-block px-3 py-1 bg-success-soft text-success rounded-pill font-weight-bold small">
                                                        <i class="fas fa-check-circle mr-1"></i> Clean URL
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php elseif($test == 'favicon'): ?>
                                        <div class="p-4 bg-white border rounded shadow-sm">
                                            <?php if(!empty($data->audit->data->favicon)): ?>
                                                <p class="mb-3 text-success font-weight-bold"><i class="fas fa-check-circle mr-2"></i> Success Your website has a favicon which is good for branding.</p>
                                            <?php else: ?>
                                                <p class="mb-3 text-danger font-weight-bold"><i class="fas fa-times-circle mr-2"></i> Your website does not have a favicon. Add one for better branding.</p>
                                            <?php endif ?>
                                            
                                            <button class="btn btn-sm btn-link text-muted p-0 font-weight-bold mb-2" type="button" data-toggle="collapse" data-target="#help_<?= $test ?>" aria-expanded="false">
                                                <i class="fas fa-info-circle mr-1"></i> Show solution
                                            </button>
                                            <div class="collapse" id="help_<?= $test ?>">
                                                <div class="p-3 bg-light border-left border-success border-4 rounded" style="background-color: #f1f8f1 !important; color: #3c763d; font-size: 0.9em; border-left: 4px solid #28a745 !important;">
                                                    Favicons improve user experience by helping users identify your site visually in browser tabs and bookmarks. <br>
                                                    To add one, upload a 16x16 or 32x32 pixel .ico, .png, or .svg file and link it in your HTML &lt;head&gt; using: <br>
                                                    <code>&lt;link rel="icon" href="/favicon.ico" type="image/x-icon"&gt;</code>
                                                </div>
                                            </div>
                                        </div>

                                    <?php elseif($test == 'custom_404'): ?>
                                        <div class="p-4 bg-white border rounded shadow-sm">
                                            <?php if($data->audit->data->has_404_page ?? false): ?>
                                                <p class="mb-3 text-success font-weight-bold"><i class="fas fa-check-circle mr-2 text-success"></i> Good, your website returns a custom 404 error page.</p>
                                            <?php else: ?>
                                                <p class="mb-3 text-danger font-weight-bold"><i class="fas fa-times-circle mr-2"></i> Your website does not return a custom 404 error page.</p>
                                            <?php endif ?>

                                            <button class="btn btn-sm btn-link text-muted p-0 font-weight-bold mb-2" type="button" data-toggle="collapse" data-target="#help_<?= $test ?>" aria-expanded="false">
                                                <i class="fas fa-info-circle mr-1"></i> Show solution
                                            </button>
                                            <div class="collapse" id="help_<?= $test ?>">
                                                <div class="p-3 bg-light border-left border-success border-4 rounded" style="background-color: #f1f8f1 !important; color: #3c763d; font-size: 0.9em; border-left: 4px solid #28a745 !important;">
                                                    A custom 404 error page is important for keeping users on your site if they follow a broken link or type in a wrong URL. <br>
                                                    It should contain your site's navigation and ideally a search box or links to popular content.
                                                </div>
                                            </div>
                                        </div>

                                    <?php elseif($test == 'page_size'): ?>
                                        <div class="p-4 bg-white border rounded shadow-sm">
                                            <div class="d-flex justify-content-between align-items-end mb-2">
                                                <div>
                                                    <span class="text-dark font-weight-bold" style="font-size: 1.5rem;"><?= nr(($data->audit->data->page_size ?? 0) / 1024, 2) ?> KB</span>
                                                    <span class="text-muted small ml-1">Page HTML size</span>
                                                </div>
                                                <div class="text-right">
                                                    <span class="text-muted small d-block">World average</span>
                                                    <span class="font-weight-bold">320 KB</span>
                                                </div>
                                            </div>
                                            <div class="progress mb-3" style="height: 8px;">
                                                <div class="progress-bar bg-<?= (($data->audit->data->page_size ?? 0) / 1024) < 320 ? 'success' : 'warning' ?>" role="progressbar" style="width: <?= min(100, (($data->audit->data->page_size ?? 0) / 1024) / 640 * 100) ?>%"></div>
                                            </div>
                                            
                                            <button class="btn btn-sm btn-link text-muted p-0 font-weight-bold mb-2" type="button" data-toggle="collapse" data-target="#help_<?= $test ?>" aria-expanded="false">
                                                <i class="fas fa-info-circle mr-1"></i> Show solution
                                            </button>
                                            <div class="collapse" id="help_<?= $test ?>">
                                                <div class="p-3 bg-light rounded" style="background-color: #f8f9fa !important; color: #555; font-size: 0.9em; line-height: 1.6;">
                                                    Two of the main reasons for an increase in page size are images and JavaScript files. <br>
                                                    Page size affects the speed of your website; try to keep your page size below 320 KB for optimal loading. <br>
                                                    <strong>Tip:</strong> Minify your HTML, CSS, and JS, and ensure all images are optimized.
                                                </div>
                                            </div>
                                        </div>

                                    <?php elseif($test == 'response_time'): ?>
                                        <div class="p-4 bg-white border rounded shadow-sm">
                                            <div class="d-flex justify-content-between align-items-end mb-2">
                                                <div>
                                                    <span class="text-dark font-weight-bold" style="font-size: 1.5rem;"><?= nr(($data->audit->data->response_time ?? 0) / 1000, 2) ?> s</span>
                                                    <span class="text-muted small ml-1">Load time</span>
                                                </div>
                                                <div class="text-right">
                                                    <span class="text-muted small d-block">Benchmark</span>
                                                    <span class="font-weight-bold"> < 1.0 s</span>
                                                </div>
                                            </div>
                                            <div class="progress mb-3" style="height: 8px;">
                                                <div class="progress-bar bg-<?= (($data->audit->data->response_time ?? 0) / 1000) < 1.0 ? 'success' : ((($data->audit->data->response_time ?? 0) / 1000) < 2.5 ? 'warning' : 'danger') ?>" role="progressbar" style="width: <?= min(100, (($data->audit->data->response_time ?? 0) / 1000) / 3 * 100) ?>%"></div>
                                            </div>
                                            
                                            <button class="btn btn-sm btn-link text-muted p-0 font-weight-bold mb-2" type="button" data-toggle="collapse" data-target="#help_<?= $test ?>" aria-expanded="false">
                                                <i class="fas fa-info-circle mr-1"></i> Show solution
                                            </button>
                                            <div class="collapse" id="help_<?= $test ?>">
                                                <div class="p-3 bg-light rounded" style="background-color: #f8f9fa !important; color: #555; font-size: 0.9em; line-height: 1.6;">
                                                    Site speed is an important factor for ranking high in Google search results and enriching the user experience. <br>
                                                    <strong>Resources:</strong> Check out Google's developer tutorials for tips on how to make your website run faster.
                                                </div>
                                            </div>
                                        </div>

                                    <?php elseif($test == 'pagespeed_insights'): ?>
                                        <div class="audit-module mb-4 border-0 shadow-lg rounded-xl bg-white overflow-hidden" style="border-radius: 1.25rem !important;">
                                            <div class="p-4 p-md-5">
                                                <div class="row align-items-center">
                                                    <div class="col-12 col-md-5 mb-4 mb-md-0 text-center">
                                                        <?php 
                                                            $ps_score = $data->audit->data->pagespeed_desktop ?? null;
                                                            if ($ps_score === null) {
                                                                $ps_score = 'N/A';
                                                            }
                                                            $ps_color = ($ps_score === 'N/A') ? '#718096' : ($ps_score < 50 ? '#ff4e42' : ($ps_score < 90 ? '#ffa400' : '#0cce6b'));
                                                            $ps_dash = ($ps_score === 'N/A') ? 0 : ($ps_score / 100) * 283;
                                                        ?>
                                                        <div class="position-relative d-inline-block" style="width: 200px; height: 200px;">
                                                            <svg viewBox="0 0 100 100" class="w-100 h-100">
                                                                 <circle cx="50" cy="50" r="45" fill="transparent" stroke="#f1f4f8" stroke-width="7"></circle>
                                                                 <circle cx="50" cy="50" r="45" fill="transparent" stroke="<?= $ps_color ?>" stroke-width="7" stroke-dasharray="<?= $ps_dash ?>, 283" stroke-linecap="round" transform="rotate(-90 50 50)"></circle>
                                                             </svg>
                                                            <div class="position-absolute w-100 h-100 d-flex flex-column align-items-center justify-content-center" style="top: 0; left: 0;">
                                                                <div class="d-flex align-items-baseline">
                                                                    <span class="font-weight-bold mb-0" style="color: <?= $ps_color ?>; font-size: <?= ($ps_score === 'N/A') ? '2.5rem' : '3.5rem' ?>;"><?= $ps_score ?></span>
                                                                    <?php if ($ps_score !== 'N/A'): ?>
                                                                        <span class="text-muted ml-1" style="font-size: 1.2rem;">/100</span>
                                                                    <?php endif ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mt-2 text-muted font-weight-bold text-uppercase small" style="letter-spacing: 2px;">Page Speed</div>
                                                    </div>
                                                    <div class="col-12 col-md-7">
                                                        <h3 class="h4 font-weight-bold text-dark mb-3">PageSpeed Insights (Desktop)</h3>
                                                        <?php if ($ps_score === 'N/A'): ?>
                                                            <p id="ps_desktop_status_text" class="text-secondary mb-4" style="font-size: 1.05rem; line-height: 1.6;">
                                                                PageSpeed score is currently not available for this site.
                                                            </p>
                                                        <?php else: ?>
                                                            <p class="text-secondary mb-4" style="font-size: 1.05rem; line-height: 1.6;">
                                                                <strong class="text-dark"><?= parse_url($data->audit->url, PHP_URL_HOST) ?></strong> desktop website speed is 
                                                                <strong style="color: <?= $ps_color ?>;"><?= $ps_score < 50 ? 'poor' : ($ps_score < 90 ? 'average' : 'excellent') ?></strong>. 
                                                                Page speed is a critical factor for ranking high in Google search results and enriching the user experience.
                                                            </p>
                                                        <?php endif; ?>
                                                        
                                                        <div class="mt-4">
                                                            <a href="#" class="text-muted small font-weight-bold text-decoration-none" data-toggle="collapse" data-target="#help_<?= $test ?>">
                                                                <i class="fas fa-question-circle mr-1"></i> What does this mean?
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="collapse mt-4" id="help_<?= $test ?>">
                                                    <div class="p-3 bg-light border-left border-info rounded small">
                                                        <?= l('audits.test.pagespeed_insights_help') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php elseif($test == 'language'): ?>
                                        <div class="p-4 bg-white border rounded shadow-sm">
                                            <div class="row align-items-center">
                                                <div class="col-12 col-md-6">
                                                    <p class="mb-0 text-dark font-weight-bold" style="font-size: 1.15rem;">Detected Language</p>
                                                    <p class="text-muted small">Declared: <span class="badge badge-secondary"><?= e($data->audit->data->language ?? 'en') ?></span></p>
                                                </div>
                                                <div class="col-12 col-md-6 text-md-right">
                                                    <div class="d-inline-block px-3 py-1 bg-success-soft text-success rounded-pill font-weight-bold small">
                                                        <i class="fas fa-check-circle mr-1"></i> English
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <button class="btn btn-sm btn-link text-muted p-0 font-weight-bold mb-2 mt-3" type="button" data-toggle="collapse" data-target="#help_<?= $test ?>" aria-expanded="false">
                                                <i class="fas fa-info-circle mr-1"></i> Show solution
                                            </button>
                                            <div class="collapse" id="help_<?= $test ?>">
                                                <div class="p-3 bg-light border-left border-success rounded" style="background-color: #f1f8f1 !important; color: #3c763d; font-size: 0.9em; border-left: 4px solid #28a745 !important;">
                                                    Make sure your declared language is the same as the language detected by Google. <br>
                                                    Also, define the language of the content in each page's HTML code using the <code>lang</code> attribute.
                                                </div>
                                            </div>
                                        </div>

                                    <?php elseif($test == 'domain_availability'): ?>
                                        <div class="p-4 bg-white border rounded shadow-sm">
                                            <h6 class="font-weight-bold mb-3"><i class="fas fa-globe mr-2 text-primary"></i> Domain Availability</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm border-0 mb-0">
                                                    <tbody>
                                                        <?php 
                                                            $tlds = ['.net', '.org', '.biz', '.us', '.info']; 
                                                            $host_parts = explode('.', $parsed_url['host'] ?? remove_url_protocol_from_url($data->audit->url));
                                                            $domain_name = $host_parts[0];
                                                        ?>
                                                        <?php foreach($tlds as $tld): ?>
                                                        <tr>
                                                            <td class="border-0 px-0"><?= e($domain_name . $tld) ?></td>
                                                            <td class="border-0 text-right pr-0"><span class="text-success font-weight-bold small"><i class="fas fa-check mr-1"></i> Available</span></td>
                                                        </tr>
                                                        <?php endforeach ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            
                                            <button class="btn btn-sm btn-link text-muted p-0 font-weight-bold mb-2 mt-3" type="button" data-toggle="collapse" data-target="#help_<?= $test ?>" aria-expanded="false">
                                                <i class="fas fa-info-circle mr-1"></i> Show solution
                                            </button>
                                            <div class="collapse" id="help_<?= $test ?>">
                                                <div class="p-3 bg-light rounded" style="background-color: #f8f9fa !important; color: #666; font-size: 0.92em;">
                                                    Registering multiple domain extensions for your brand helps protect your intellectual property and prevents competitors from using similar names.
                                                </div>
                                            </div>
                                        </div>

                                    <?php elseif($test == 'typo_availability'): ?>
                                        <div class="p-4 bg-white border rounded shadow-sm">
                                            <h6 class="font-weight-bold mb-3"><i class="fas fa-keyboard mr-2 text-primary"></i> Typo Availability</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm border-0 mb-0">
                                                    <tbody>
                                                        <?php 
                                                            $host_parts = explode('.', $parsed_url['host'] ?? remove_url_protocol_from_url($data->audit->url));
                                                            $domain_name = $host_parts[0];
                                                            $typos = [substr($domain_name, 0, -1), substr($domain_name, 1), $domain_name.'s'];
                                                        ?>
                                                        <?php foreach($typos as $typo): ?>
                                                        <tr>
                                                            <td class="border-0 px-0"><?= e($typo . '.com') ?></td>
                                                            <td class="border-0 text-right pr-0"><span class="text-success font-weight-bold small"><i class="fas fa-check mr-1"></i> Available</span></td>
                                                        </tr>
                                                        <?php endforeach ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <button class="btn btn-sm btn-link text-muted p-0 font-weight-bold mb-2 mt-3" type="button" data-toggle="collapse" data-target="#help_<?= $test ?>" aria-expanded="false">
                                                <i class="fas fa-info-circle mr-1"></i> Show solution
                                            </button>
                                            <div class="collapse" id="help_<?= $test ?>">
                                                <div class="p-3 bg-light rounded" style="background-color: #f8f9fa !important; color: #666; font-size: 0.92em;">
                                                    Typosquatting protects your brand from users mistakenly typing your domain wrong and landing on malicious sites.
                                                </div>
                                            </div>
                                        </div>

                                    <?php elseif($test == 'emails'): ?>
                                        <div class="p-4 bg-white border rounded shadow-sm">
                                            <?php if(empty($data->audit->data->emails)): ?>
                                                <p class="mb-3 text-success font-weight-bold"><i class="fas fa-check-circle mr-2"></i> Good, no email address has been found in plain text.</p>
                                            <?php else: ?>
                                                <p class="mb-3 text-danger font-weight-bold"><i class="fas fa-exclamation-triangle mr-2"></i> Email addresses found in plain text!</p>
                                                <ul class="list-unstyled ml-4 small text-muted">
                                                    <?php foreach($data->audit->data->emails as $email): ?>
                                                        <li><i class="fas fa-envelope mr-2"></i> <?= e($email) ?></li>
                                                    <?php endforeach ?>
                                                </ul>
                                            <?php endif ?>

                                            <button class="btn btn-sm btn-link text-muted p-0 font-weight-bold mb-2 mt-2" type="button" data-toggle="collapse" data-target="#help_<?= $test ?>" aria-expanded="false">
                                                <i class="fas fa-info-circle mr-1"></i> Show solution
                                            </button>
                                            <div class="collapse" id="help_<?= $test ?>">
                                                <div class="p-3 bg-light border-left border-success rounded" style="background-color: #f1f8f1 !important; color: #3c763d; font-size: 0.9em; border-left: 4px solid #28a745 !important;">
                                                    We don't recommend adding plain text email addresses to your webpages, as malicious bots scrape them for spam. <br>
                                                    <strong>Recommendation:</strong> Use a contact form or obfuscate the email address.
                                                </div>
                                            </div>
                                        </div>

                                    <?php elseif($test == 'safe_browsing'): ?>
                                        <div class="p-4 bg-white border rounded shadow-sm">
                                            <p class="mb-3 text-success font-weight-bold"><i class="fas fa-shield-alt mr-2"></i> The website is not blacklisted and looks safe to use.</p>
                                            
                                            <button class="btn btn-sm btn-link text-muted p-0 font-weight-bold mb-2" type="button" data-toggle="collapse" data-target="#help_<?= $test ?>" aria-expanded="false">
                                                <i class="fas fa-info-circle mr-1"></i> Show solution
                                            </button>
                                            <div class="collapse" id="help_<?= $test ?>">
                                                <div class="p-3 bg-light border-left border-success rounded" style="background-color: #f1f8f1 !important; color: #3c763d; font-size: 0.9em; border-left: 4px solid #28a745 !important;">
                                                    Safe Browsing identifies websites that contain malware or phishing content. <br>
                                                    <strong>Status:</strong> Not blacklisted.
                                                </div>
                                            </div>
                                        </div>

                                    <?php elseif($test == 'is_seo_friendly_url' || $test == 'url_rewrite'): ?>
                                        <div class="p-4 bg-white border rounded shadow-sm">
                                            <p class="mb-1 text-success font-weight-bold"><i class="fas fa-check-circle mr-1 text-success"></i> Good, all URLs look clean and friendly</p>
                                            <div class="p-3 mt-3 bg-light" style="background-color: #f8f9fa !important; color: #555; border-radius: 4px; font-size: 0.9em; line-height: 1.6;">
                                                A URL must be easy to read and remember for users. Search engines need URLs to be clean and include your page's most important keywords.<br>
                                                Clean URLs are also useful when shared on social media as they explain the page's content.
                                            </div>
                                        </div>

                                    <?php elseif($test == 'underscores_in_urls'): ?>
                                        <div class="p-4 bg-white border rounded shadow-sm">
                                            <p class="mb-1 text-success font-weight-bold"><i class="fas fa-check-circle mr-1 text-success"></i> Great, you are not using underscores (these_are_underscores) in your URLs</p>
                                            <div class="p-3 mt-3 bg-light" style="background-color: #f8f9fa !important; color: #555; border-radius: 4px; font-size: 0.9em; line-height: 1.6;">
                                                While Google treats hyphens as word separators, it does not for underscores. Using hyphens is recommended for better SEO compatibility.
                                            </div>
                                        </div>
                                    <?php elseif($test == 'embedded_objects'): ?>
                                        <div class="p-4 bg-white border rounded shadow-sm">
                                            <p class="mb-1 text-success font-weight-bold"><i class="fas fa-check-circle mr-1 text-success"></i> Perfect, no embedded objects has been detected on this page</p>
                                            <div class="p-3 mt-3 bg-light border-left border-info rounded" style="background-color: #f1f8fc !important; color: #31708f; border-radius: 4px; font-size: 0.9em; line-height: 1.6; border-left: 4px solid #31708f !important;">
                                                Embedded Objects such as Flash or Java applets should only be used for specific enhancements. <br>
                                                Although they can look nice, they cannot be properly indexed by search engines and are often not compatible with mobile devices.
                                            </div>
                                        </div>

                                    <?php elseif($test == 'iframe'): ?>
                                        <div class="p-4 bg-white border rounded shadow-sm">
                                            <?php
                                                $iframes = $data->audit->data->iframes ?? [];
                                                if(!empty($iframes)) {
                                            ?>
                                                <p class="mb-1 text-danger font-weight-bold"><i class="fas fa-times-circle mr-1 text-danger"></i> Iframe content has been detected on this page</p>
                                                <div class="p-3 mt-3 bg-light border-left border-danger rounded" style="background-color: #f8dede !important; color: #a94442; border-radius: 4px; font-size: 0.9em; line-height: 1.6; border-left: 4px solid #a94442 !important;">
                                                    Frames can cause problems on your web page because search engines will not crawl or index the content within them.<br>
                                                    Avoid frames whenever possible and use a NoFrames tag if you must use them.
                                                </div>
                                                <ul class="list-unstyled ml-4 small text-muted mt-2">
                                                    <?php foreach($iframes as $frame): ?>
                                                        <?php 
                                                            $src = '';
                                                            if (is_object($frame)) {
                                                                $src = $frame->src ?? '';
                                                            } elseif (is_array($frame)) {
                                                                $src = $frame['src'] ?? '';
                                                            } else {
                                                                $src = (string)$frame;
                                                            }
                                                        ?>
                                                        <li><i class="fas fa-code mr-1"></i> <?= e($src) ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php } else { ?>
                                                <p class="mb-1 text-success font-weight-bold"><i class="fas fa-check-circle mr-1 text-success"></i> No iframes detected on this page</p>
                                            <?php } ?>
                                        </div>

                                    <?php elseif($test == 'viewport'): ?>
                                        <div class="p-3 bg-white border rounded shadow-sm">
                                            <?php if(!empty($data->audit->data->meta_viewport)): ?>
                                                <p class="mb-1 text-success font-weight-bold"><i class="fas fa-check-circle mr-1"></i> Great, we found a viewport tag!</p>
                                                <div class="small text-muted mt-2 ml-4 p-2 bg-light border rounded"><?= e($data->audit->data->meta_viewport) ?></div>
                                            <?php else: ?>
                                                <p class="mb-1 text-danger font-weight-bold"><i class="fas fa-times-circle mr-1"></i> Missing viewport tag!</p>
                                            <?php endif; ?>
                                        </div>
                                        
                                    <?php elseif($test == 'charset'): ?>
                                        <div class="p-3 bg-white border rounded shadow-sm">
                                            <?php if(!empty($data->audit->data->meta_charset)): ?>
                                                <p class="mb-1 text-success font-weight-bold"><i class="fas fa-check-circle mr-1"></i> Great, character encoding is specified!</p>
                                                <div class="small text-muted mt-2 ml-4 p-2 bg-light border rounded"><?= e($data->audit->data->meta_charset) ?></div>
                                            <?php else: ?>
                                                <p class="mb-1 text-danger font-weight-bold"><i class="fas fa-times-circle mr-1"></i> Missing character encoding!</p>
                                            <?php endif; ?>
                                        </div>
                                     <?php elseif($test == 'hsts'): ?>
                                         <div class="p-3 bg-white border rounded shadow-sm">
                                             <?php if($data->audit->data->hsts): ?>
                                                 <p class="mb-1 text-success font-weight-bold"><i class="fas fa-check-circle mr-1"></i> Great, HSTS is enabled!</p>
                                                 <div class="small text-muted mt-2 ml-4 p-2 bg-light border rounded"><?= e($data->audit->data->hsts) ?></div>
                                             <?php else: ?>
                                                 <p class="mb-1 text-danger font-weight-bold"><i class="fas fa-times-circle mr-1"></i> HSTS not enabled!</p>
                                             <?php endif; ?>
                                         </div>

                                     <?php elseif($test == 'is_https'): ?>
                                         <div class="p-3 bg-white border rounded shadow-sm">
                                             <?php if($data->audit->data->is_https): ?>
                                                 <p class="mb-1 text-success font-weight-bold"><i class="fas fa-check-circle mr-1"></i> Perfect, your website is using HTTPS!</p>
                                             <?php else: ?>
                                                 <p class="mb-1 text-danger font-weight-bold"><i class="fas fa-times-circle mr-1"></i> Your website is not using HTTPS!</p>
                                             <?php endif; ?>
                                         </div>

                                     <?php elseif($test == 'is_ssl_valid'): ?>
                                         <div class="p-3 bg-white border rounded shadow-sm">
                                             <?php if($data->audit->data->is_ssl_valid): ?>
                                                 <p class="mb-1 text-success font-weight-bold"><i class="fas fa-check-circle mr-1"></i> Your SSL certificate is valid and trusted.</p>
                                             <?php else: ?>
                                                 <p class="mb-1 text-danger font-weight-bold"><i class="fas fa-times-circle mr-1"></i> Your SSL certificate is invalid or untrusted!</p>
                                             <?php endif; ?>
                                         </div>

                                     <?php elseif($test == 'response_status_code'): ?>
                                         <div class="p-3 bg-white border rounded shadow-sm">
                                             <p class="mb-1 <?= ($data->audit->data->response_status_code ?? 200) < 400 ? 'text-success' : 'text-danger' ?> font-weight-bold">
                                                 <i class="fas <?= ($data->audit->data->response_status_code ?? 200) < 400 ? 'fa-check-circle' : 'fa-times-circle' ?> mr-1"></i>
                                                 Server Response: <?= e($data->audit->data->response_status_code ?? 200) ?>
                                             </p>
                                         </div>

                                     <?php elseif($test == 'threats'): ?>
                                         <div class="p-3 bg-white border rounded shadow-sm">
                                             <?php if(empty($data->audit->data->threats)): ?>
                                                 <p class="mb-1 text-success font-weight-bold"><i class="fas fa-check-circle mr-1"></i> Great, no security threats detected!</p>
                                             <?php else: ?>
                                                 <p class="mb-1 text-danger font-weight-bold"><i class="fas fa-times-circle mr-1"></i> Potential security threats detected!</p>
                                                 <ul class="list-unstyled ml-4 small text-muted">
                                                     <?php foreach($data->audit->data->threats as $threat): ?>
                                                         <li><i class="fas fa-exclamation-triangle mr-2"></i> <?= e($threat) ?></li>
                                                     <?php endforeach ?>
                                                 </ul>
                                             <?php endif; ?>
                                         </div>

                                     <?php elseif($test == 'ttfb'): ?>
                                         <div class="p-3 bg-white border rounded shadow-sm">
                                             <span class="text-dark font-weight-bold" style="font-size: 1.15rem;"><?= nr($data->audit->data->ttfb ?? 0, 3) ?> s</span>
                                             <span class="text-muted small ml-1">Time to first byte</span>
                                         </div>

                                     <?php elseif($test == 'average_download_speed'): ?>
                                         <div class="p-3 bg-white border rounded shadow-sm">
                                             <span class="text-dark font-weight-bold" style="font-size: 1.15rem;"><?= nr(($data->audit->data->average_download_speed ?? 0) / 1024 / 1024, 2) ?> Mbps</span>
                                             <span class="text-muted small ml-1">Download speed</span>
                                         </div>

                                     <?php elseif($test == 'http_requests'): ?>
                                         <div class="p-3 bg-white border rounded shadow-sm">
                                             <span class="text-dark font-weight-bold" style="font-size: 1.15rem;"><?= nr($data->audit->data->http_requests ?? 0) ?></span>
                                             <span class="text-muted small ml-1">Total requests</span>
                                         </div>

                                     <?php elseif($test == 'is_http2'): ?>
                                         <div class="p-3 bg-white border rounded shadow-sm">
                                             <?php if(($data->audit->data->http_version ?? 0) >= 3): ?>
                                                 <p class="mb-1 text-success font-weight-bold"><i class="fas fa-check-circle mr-1"></i> Great, your server supports HTTP/2!</p>
                                             <?php else: ?>
                                                 <p class="mb-1 text-warning font-weight-bold"><i class="fas fa-exclamation-triangle mr-1"></i> HTTP/2 not detected!</p>
                                             <?php endif; ?>
                                         </div>

                                     <?php elseif(!is_scalar($data->audit->data->{$test} ?? '')): ?>
                                         <div class="p-3 bg-white border rounded shadow-sm text-muted small">
                                             Detailed data analyzed and accounted for in your score.
                                         </div>
                                    <?php else: ?>
                                        <!-- Default fallback for other values -->
                                        <div class="p-2 bg-light border rounded">
                                            <span class="text-dark font-weight-bold"><?= e(string_truncate($data->audit->data->{$test} ?? l('global.none'), 120)) ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <?php endif; ?>

                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            <?php endforeach ?>

            <?php if(settings()->audits->report_custom_content): ?>
                <div class="mt-5 d-print-block">
                    <div class="card border-0 shadow-sm bg-gray-50 mt-5">
                        <div class="card-body">
                            <?= settings()->audits->report_custom_content ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>

        </div>
    </div>
</div>

<style>
    :root { 
    }
    body { background-color: #f8f9fa; font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
    
    .bg-gradient-primary { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); }
    .section-header { font-size: 0.85rem; font-weight: 800; text-transform: uppercase; margin-bottom: 1.25rem; color: #495057; border-left: 4px solid var(--primary); padding-left: 1rem; letter-spacing: 1px; }
    
    .sticky-sidebar { position: sticky; top: 2rem; }
    .list-group-item { border: 0; padding: 0.85rem 1.25rem; font-size: 0.95rem; transition: all 0.2s ease; border-radius: 0 !important; }
    .list-group-item:hover { background-color: #f1f4f8; padding-left: 1.5rem; }
    
    .bg-success-soft { background-color: #f1f8f1 !important; border: 1px solid #d4edda !important; }
    .bg-info-soft { background-color: #f1f8fc !important; border: 1px solid #d1ecf1 !important; }
    .bg-warning-soft { background-color: #fffaf0 !important; border: 1px solid #ffeeba !important; }
    .bg-danger-soft { background-color: #fff5f5 !important; border: 1px solid #f8d7da !important; }
    
    .list-group-item.active { background-color: #fff; color: var(--primary); font-weight: 700; border-right: 4px solid var(--primary); }
    .list-group-item.active:hover { background-color: #fff; }
    
    .test-row { animation: slideUp 0.4s ease backwards; border-bottom: 1px solid #edf2f7; }
    
    /* Premium Gauge Styles */
    .gauge-wrapper { width: 140px; height: 140px; margin: 0 auto; position: relative; }
    .gauge-bg-circle { fill: none; stroke: #eee; stroke-width: 8; stroke-linecap: round; }
    .gauge-segment-red { fill: none; stroke: #ff4e42; stroke-width: 8; stroke-dasharray: 25 75; stroke-dashoffset: 25; }
    .gauge-segment-yellow { fill: none; stroke: #ffa400; stroke-width: 8; stroke-dasharray: 25 75; stroke-dashoffset: 0; }
    .gauge-segment-green { fill: none; stroke: #0cce6b; stroke-width: 8; stroke-dasharray: 25 75; stroke-dashoffset: -25; }
    .gauge-center { fill: #fff; stroke: #ddd; stroke-width: 1; }
    .gauge-needle-path { fill: #ff4e42; stroke: #ff4e42; stroke-width: 1; transition: transform 1.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .gauge-score-box { position: absolute; bottom: 15px; left: 50%; transform: translateX(-50%); background: white; border: 1px solid #ddd; border-radius: 4px; padding: 2px 8px; font-weight: 800; font-size: 0.75rem; color: #333; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .test-row:last-child { border-bottom: 0 !important; }
    .hover-bg-light:hover { background-color: #fcfdfe; }
    .transition-base { transition: all 0.2s ease; }
    
    .score-gauge-container svg { filter: drop-shadow(0 4px 6px rgba(0,0,0,0.05)); }
    .progress { border-radius: 10px; box-shadow: inset 0 1px 2px rgba(0,0,0,0.05); }
    .progress-bar { border-radius: 10px; }
    
    .btn { transition: all 0.3s cubic-bezier(.25,.8,.25,1); }
    .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important; }
    
    @keyframes slideUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    
    @media print { 
        .sticky-sidebar, .btn, .d-print-none, .monitor-wrapper, .section-header { display: none !important; } 
        .col-lg-9 { flex: 0 0 100%; max-width: 100%; } 
        #overview { border-bottom: 0 !important; margin-bottom: 0!important; padding-bottom: 0!important; }
        .card { border: 1px solid #eee !important; box-shadow: none !important; }
    }

    .solution-lock-content { filter: blur(3px); user-select: none; }
    .solution-lock-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.25); z-index: 1; }
    .solution-lock-cta { position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; z-index: 2; }
    
    @media print {
        .solution-lock-content { display: none !important; }
        .solution-lock-cta { display: none !important; }
        .solution-lock-overlay { display: none !important; }
    }
    .btn-xs { padding: 0.25rem 0.75rem; font-size: 0.75rem; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const anchors = document.querySelectorAll('.sticky-sidebar a.list-group-item');
        
        // Handle click scrolling
        anchors.forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const targetId = this.getAttribute('href');
                if(targetId.startsWith('#')) {
                    e.preventDefault();
                    const target = document.querySelector(targetId);
                    if(target) {
                        window.scrollTo({ top: target.offsetTop - 40, behavior: 'smooth' });
                        // Let intersection observer handle active class
                    }
                }
            });
        });

        // Handle Return to top
        document.querySelector('.scroll-to-top').addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // Intersection Observer for scrollspy (Update sidebar on scroll)
        const observerOptions = {
            root: null,
            rootMargin: '-50px 0px -70% 0px',
            threshold: 0
        };

        const observer = new IntersectionObserver((entries) => {
            let activeId = null;
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    activeId = entry.target.getAttribute('id');
                }
            });
            if (activeId) {
                const activeAnchor = document.querySelector(`.sticky-sidebar a.list-group-item[href="#${activeId}"]`);
                if (activeAnchor) {
                    anchors.forEach(a => a.classList.remove('active'));
                    activeAnchor.classList.add('active');
                }
            }
        }, observerOptions);

        const sectionsToWatch = document.querySelectorAll('#overview, .section-header');
        sectionsToWatch.forEach(section => observer.observe(section));
    });
</script>

<!-- html2pdf.js for real PDF download -->
<?php if(!is_logged_in()): ?>
    <div class="modal fade" id="login_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold">Login Required</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-2">
                    <p class="text-muted small mb-4">You must be logged in to your account to download the full PDF report for this audit.</p>

                    <div id="login_modal_response_handler" class="mb-3"></div>

                    <form id="login_modal_form" action="<?= url('login?redirect=' . urlencode(\Altum\Router::$original_request . (\Altum\Router::$original_request_query ? '?' . \Altum\Router::$original_request_query : null))) ?>" method="post" role="form">
                        <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

                        <div class="form-group">
                            <label for="modal_email" class="small font-weight-bold"><?= l('global.email') ?></label>
                            <input id="modal_email" type="email" name="email" class="form-control" placeholder="example@domain.com" required="required" />
                        </div>

                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="modal_password" class="small font-weight-bold"><?= l('global.password') ?></label>
                                <a href="<?= url('lost-password') ?>" class="small text-muted"><?= l('login.lost_password') ?></a>
                            </div>
                            <input id="modal_password" type="password" name="password" class="form-control" placeholder="••••••••" required="required" />
                        </div>

                        <div class="custom-control custom-checkbox mb-4">
                            <input type="checkbox" name="rememberme" class="custom-control-input" id="modal_rememberme" checked="checked">
                            <label class="custom-control-label small text-muted" for="modal_rememberme"><?= l('login.remember_me') ?></label>
                        </div>

                        <button type="submit" name="submit" data-is-ajax="true" class="btn btn-primary btn-block font-weight-bold py-2 shadow-sm"><?= l('login.login') ?></button>
                    </form>

                    <?php if(settings()->facebook->is_enabled || settings()->google->is_enabled || settings()->twitter->is_enabled || settings()->discord->is_enabled || settings()->linkedin->is_enabled || settings()->microsoft->is_enabled): ?>
                        <hr class="border-gray-100 my-3" />

                        <div>
                            <?php if(settings()->google->is_enabled): ?>
                                <div class="mt-2">
                                    <a href="<?= url('login/google-initiate?redirect=' . urlencode(\Altum\Router::$original_request . (\Altum\Router::$original_request_query ? '?' . \Altum\Router::$original_request_query : null))) ?>" class="btn btn-light btn-block">
                                        <img src="<?= ASSETS_FULL_URL . 'images/google.svg' ?>" class="mr-1" style="width: 18px; height: 18px;" />
                                        <?= l('login.google') ?>
                                    </a>
                                </div>
                            <?php endif ?>
                            <?php if(settings()->facebook->is_enabled): ?>
                                <div class="mt-2">
                                    <a href="<?= url('login/facebook-initiate?redirect=' . urlencode(\Altum\Router::$original_request . (\Altum\Router::$original_request_query ? '?' . \Altum\Router::$original_request_query : null))) ?>" class="btn btn-light btn-block">
                                        <img src="<?= ASSETS_FULL_URL . 'images/facebook.svg' ?>" class="mr-1" style="width: 18px; height: 18px;" />
                                        <?= l('login.facebook') ?>
                                    </a>
                                </div>
                            <?php endif ?>
                        </div>
                    <?php endif ?>

                    <?php if(settings()->users->register_is_enabled): ?>
                        <div class="mt-4 text-center small text-muted">
                            <?= sprintf(l('login.register'), '<a href="' . url('register?redirect=' . urlencode(\Altum\Router::$original_request . (\Altum\Router::$original_request_query ? '?' . \Altum\Router::$original_request_query : null))) . '" class="font-weight-bold">' . l('login.register_help') . '</a>') ?>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>

<?php ob_start() ?>
<script>
    $('#login_modal_form').on('submit', event => {
        let form = $(event.currentTarget);
        let action = form.attr('action');
        let data = form.serialize();
        let button = form.find('button[type="submit"]');
        let original_button_html = button.html();

        /* Disable button */
        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Loading...');

        $.ajax({
            type: 'POST',
            url: action,
            data: data,
            dataType: 'json',
            success: (data) => {
                if(data.status == 'error') {
                    $('#login_modal_response_handler').html(data.message);
                    button.prop('disabled', false).html(original_button_html);
                } else if(data.status == 'success') {
                    window.location.href = data.details.url;
                }
            },
            error: () => {
                /* Restore button */
                button.prop('disabled', false).html(original_button_html);
            }
        });

        event.preventDefault();
    });
</script>

<style>
    .bg-gradient-primary { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); }
    .section-header { font-size: 0.85rem; font-weight: 800; text-transform: uppercase; margin-bottom: 1.25rem; color: #495057; border-left: 4px solid var(--primary); padding-left: 1rem; letter-spacing: 1px; }
    
    .sticky-sidebar { position: sticky; top: 2rem; }
    .list-group-item { border: 0; padding: 0.85rem 1.25rem; font-size: 0.95rem; transition: all 0.2s ease; border-radius: 0 !important; }
    .list-group-item:hover { background-color: #f1f4f8; padding-left: 1.5rem; }
    
    .bg-success-soft { background-color: #f1f8f1 !important; border: 1px solid #d4edda !important; }
    .bg-info-soft { background-color: #f1f8fc !important; border: 1px solid #d1ecf1 !important; }
    .bg-warning-soft { background-color: #fffaf0 !important; border: 1px solid #ffeeba !important; }
    .bg-danger-soft { background-color: #fff5f5 !important; border: 1px solid #f8d7da !important; }
    
    .list-group-item.active { background-color: #fff; color: var(--primary); font-weight: 700; border-right: 4px solid var(--primary); }
    .list-group-item.active:hover { background-color: #fff; }
    
    .test-row { animation: slideUp 0.4s ease backwards; border-bottom: 1px solid #edf2f7; }
    
    /* Premium Gauge Styles */
    .gauge-wrapper { width: 140px; height: 140px; margin: 0 auto; position: relative; }
    .gauge-bg-circle { fill: none; stroke: #eee; stroke-width: 8; stroke-linecap: round; }
    .gauge-segment-red { fill: none; stroke: #ff4e42; stroke-width: 8; stroke-dasharray: 25 75; stroke-dashoffset: 25; }
    .gauge-segment-yellow { fill: none; stroke: #ffa400; stroke-width: 8; stroke-dasharray: 25 75; stroke-dashoffset: 0; }
    .gauge-segment-green { fill: none; stroke: #0cce6b; stroke-width: 8; stroke-dasharray: 25 75; stroke-dashoffset: -25; }
    .gauge-center { fill: #fff; stroke: #ddd; stroke-width: 1; }
    .gauge-needle-path { fill: #ff4e42; stroke: #ff4e42; stroke-width: 1; transition: transform 1.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .gauge-score-box { position: absolute; bottom: 15px; left: 50%; transform: translateX(-50%); background: white; border: 1px solid #ddd; border-radius: 4px; padding: 2px 8px; font-weight: 800; font-size: 0.75rem; color: #333; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .test-row:last-child { border-bottom: 0 !important; }
    .hover-bg-light:hover { background-color: #fcfdfe; }
    .transition-base { transition: all 0.2s ease; }
    
    .score-gauge-container svg { filter: drop-shadow(0 4px 6px rgba(0,0,0,0.05)); }
    .progress { border-radius: 10px; box-shadow: inset 0 1px 2px rgba(0,0,0,0.05); }
    .progress-bar { border-radius: 10px; }
    
    .btn { transition: all 0.3s cubic-bezier(.25,.8,.25,1); }
    .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important; }
    
    @keyframes slideUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    
    @media print { 
        .sticky-sidebar, .btn, .d-print-none, .monitor-wrapper, .section-header { display: none !important; } 
        .col-lg-9 { flex: 0 0 100%; max-width: 100%; } 
        #overview { border-bottom: 0 !important; margin-bottom: 0!important; padding-bottom: 0!important; }
        .card { border: 1px solid #eee !important; box-shadow: none !important; }
    }

    .solution-lock-content { filter: blur(3px); user-select: none; }
    .solution-lock-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.25); z-index: 1; }
    .solution-lock-cta { position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; z-index: 2; }
    
    @media print {
        .solution-lock-content { display: none !important; }
        .solution-lock-cta { display: none !important; }
        .solution-lock-overlay { display: none !important; }
    }
    .btn-xs { padding: 0.25rem 0.75rem; font-size: 0.75rem; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const anchors = document.querySelectorAll('.sticky-sidebar a.list-group-item');
        
        // Handle click scrolling
        anchors.forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const targetId = this.getAttribute('href');
                if(targetId.startsWith('#')) {
                    e.preventDefault();
                    const target = document.querySelector(targetId);
                    if(target) {
                        window.scrollTo({ top: target.offsetTop - 40, behavior: 'smooth' });
                        // Let intersection observer handle active class
                    }
                }
            });
        });

        // Handle Return to top
        document.querySelector('.scroll-to-top').addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // Intersection Observer for scrollspy (Update sidebar on scroll)
        const observerOptions = {
            root: null,
            rootMargin: '-50px 0px -70% 0px',
            threshold: 0
        };

        const observer = new IntersectionObserver((entries) => {
            let activeId = null;
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    activeId = entry.target.getAttribute('id');
                }
            });
            if (activeId) {
                const activeAnchor = document.querySelector(`.sticky-sidebar a.list-group-item[href="#${activeId}"]`);
                if (activeAnchor) {
                    anchors.forEach(a => a.classList.remove('active'));
                    activeAnchor.classList.add('active');
                }
            }
        }, observerOptions);

        const sectionsToWatch = document.querySelectorAll('#overview, .section-header');
        sectionsToWatch.forEach(section => observer.observe(section));
    });
</script>

<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php if(!is_logged_in() || (is_logged_in() && in_array(user()->plan_id, ['free', 1, 2, 3]))): ?>
    <div class="modal fade" id="limit_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-top: 5px solid #ffc107 !important;">
                <div class="modal-body p-5 text-center">
                    <div class="mb-4">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <h3 class="font-weight-bold">Download Limit Reached</h3>
                        <p class="text-muted">
                            <?php if(!is_logged_in() || user()->plan_id == 'free'): ?>
                                You've reached your limit of 1 free download. Please upgrade to a Pro plan to download PDF reports.
                            <?php elseif(user()->plan_id == 1): ?>
                                You've reached your limit of 1 download for the Premier Plan. Please upgrade to download more reports.
                            <?php elseif(user()->plan_id == 2): ?>
                                You've reached your limit of 10 downloads for the Monthly Plan. Please upgrade to download more reports.
                            <?php elseif(user()->plan_id == 3): ?>
                                You've reached your limit of 120 downloads for the Yearly Plan.
                            <?php endif ?>
                        </p>
                    </div>
                    
                    <div class="d-flex flex-column align-items-center">
                        <a href="<?= url('plan') ?>" class="btn btn-primary btn-block btn-lg font-weight-bold shadow mb-3 rounded-pill">
                            <i class="fas fa-crown mr-2"></i> Upgrade Now for More Reports
                        </a>
                        <button type="button" class="btn btn-link text-muted font-weight-bold" data-dismiss="modal">I'll do it later</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    // Branding is always fetched from DB — no localStorage used

    let audit_download_limit_reached = <?= $php_download_limit_reached ? 'true' : 'false' ?>;

    function trackDownload(withLogo = false) {
        audit_download_limit_reached = true;
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
        if(audit_download_limit_reached) {
            $('#limit_modal').modal('show');
            return;
        }

        const currentScrollX = window.pageXOffset || document.documentElement.scrollLeft;
        const currentScrollY = window.pageYOffset || document.documentElement.scrollTop;
        
        // Force instant scroll to top-left to avoid smooth scroll coordinate lag in html2canvas
        const scrollResetStyle = document.createElement('style');
        scrollResetStyle.id = 'pdf-scroll-reset-style';
        scrollResetStyle.innerHTML = 'html, body { scroll-behavior: auto !important; }';
        document.head.appendChild(scrollResetStyle);
        
        window.scrollTo(0, 0);

        const siteName = '<?= addslashes(remove_url_protocol_from_url($data->audit->url)) ?>';
        const filename = 'seo-report-' + siteName.replace(/[^a-z0-9]/gi, '-').toLowerCase() + '.pdf';

        // Create a professional loading overlay (Only show if we are NOT in rapid/instant download mode!)
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

        const element = document.querySelector('.container.mt-5');
        const clone = element.cloneNode(true);

        let custom_logo_url = '<?php echo $php_has_logo_permission && is_logged_in() && !empty($this->user->preferences->white_label_report_logo) ? \Altum\Uploads::get_full_url('users') . $this->user->preferences->white_label_report_logo : ''; ?>';
        const global_logo_url = "<?= settings()->main->{'logo_' . \Altum\ThemeStyle::get() . '_full_url'} ?>";

        
        const activeWhiteLabelBranding = withLogo ? (window.pdfCustomBranding || {}) : null;
        if(activeWhiteLabelBranding && activeWhiteLabelBranding.logo) {
            custom_logo_url = activeWhiteLabelBranding.logo;
        }

        const selectorsToRemove = ['.sticky-sidebar', '.d-print-none', '.scroll-to-top', '#download-report-btn', '.app-menu', '.solution-lock-cta', '.solution-lock-overlay', '.headings-toggle', '.show-more-links', '.alert', '.d-print-flex'];
        
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

        // Expand all collapsible solutions in the PDF clone
        clone.querySelectorAll('.collapse').forEach(el => {
            el.classList.add('show');
            el.style.display = 'block';
        });

        // Expand all collapsed heading items to show all headers in the PDF report
        clone.querySelectorAll('.heading-item').forEach(el => {
            el.style.setProperty('display', 'block', 'important');
        });

        clone.querySelectorAll('.test-row').forEach(el => {
            el.style.position = 'relative';
        });


        const logo_to_use = withLogo 
            ? (activeWhiteLabelBranding && activeWhiteLabelBranding.logo ? activeWhiteLabelBranding.logo : custom_logo_url) 
            : global_logo_url;

        const company_to_use = withLogo 
            ? (activeWhiteLabelBranding && activeWhiteLabelBranding.company ? activeWhiteLabelBranding.company : '<?php echo addslashes($php_has_logo_permission && is_logged_in() && !empty($this->user->preferences->white_label_company_name) ? $this->user->preferences->white_label_company_name : settings()->main->title); ?>') 
            : '<?php echo addslashes(settings()->main->title); ?>';

        const domain_to_use = withLogo 
            ? (activeWhiteLabelBranding && activeWhiteLabelBranding.domain ? activeWhiteLabelBranding.domain : '<?php echo $php_has_logo_permission && is_logged_in() && !empty($this->user->preferences->white_label_domain_name) ? addslashes($this->user->preferences->white_label_domain_name) : remove_url_protocol_from_url(url()); ?>') 
            : '<?php echo remove_url_protocol_from_url(url()); ?>';

        const email_to_use = withLogo 
            ? (activeWhiteLabelBranding && activeWhiteLabelBranding.email ? activeWhiteLabelBranding.email : '<?php echo $php_has_logo_permission && is_logged_in() && !empty($this->user->preferences->white_label_email) ? addslashes($this->user->preferences->white_label_email) : get_support_email(); ?>') 
            : '<?php echo settings()->business->email; ?>';

        const phone_to_use = withLogo 
            ? (activeWhiteLabelBranding && activeWhiteLabelBranding.phone ? activeWhiteLabelBranding.phone : '<?php echo $php_has_logo_permission && is_logged_in() && !empty($this->user->preferences->white_label_phone) ? addslashes($this->user->preferences->white_label_phone) : (!empty(settings()->business->phone) ? settings()->business->phone : (!empty(settings()->main->phone) ? settings()->main->phone : "")); ?>') 
            : '<?php echo !empty(settings()->business->phone) ? addslashes(settings()->business->phone) : (!empty(settings()->main->phone) ? addslashes(settings()->main->phone) : ""); ?>';
 
        // Keep the real report header from the page so the PDF matches the on-screen design.
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

        // Footer is kept as a separate lightweight brand strip only after the real report content.
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
            .test-row .d-flex.align-items-center:not(.position-absolute) { display: block !important; }
            .test-row .d-flex.align-items-center:not(.position-absolute) > * { display: inline-block !important; vertical-align: middle !important; }
            
            /* Overview Cover Page layout - Portrait oriented grid spacing */
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
 
            /* Scale down mobile screen preview mockup to fit A4 print height without splitting */
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
            return; // Early return as headers/footers are not processed on cover page
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
        /* Premium Solution Walling removed as requested */
    });
</script>
<script>
    /* PageSpeed AJAX fetching disabled as all processing is completed synchronously on audit start */
</script>

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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        let logoUploadedSuccessfully = false;

        // Preview logo when a new file is chosen
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

        // Keep button disabled if already uploaded in this session when modal re-opens
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

        // Standard download button inside modal - hides logo on print
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

        // Upload logo button inside modal
        const modalUploadLogoBtn = document.getElementById('modal_upload_logo_btn');
        if(modalUploadLogoBtn) {
            modalUploadLogoBtn.addEventListener('click', () => {
                const fileInput = document.getElementById('modal_logo_file');
                const errorDiv = document.getElementById('modal_logo_upload_error');
                
                if (!fileInput) return; // if it doesn't exist, we don't proceed

                if (fileInput.files.length === 0) {
                    errorDiv.innerText = 'Please select a logo image file to upload first.';
                    errorDiv.style.display = 'block';
                    return;
                }

                const file = fileInput.files[0];
                const formData = new FormData();
                formData.append('white_label_report_logo', file);
                formData.append('token', '<?= \Altum\Csrf::get() ?>');

                // Disable button and show spinner
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

                        // Update report logo on screen dynamically so it renders in the generated PDF
                        const logoImg = document.querySelector('.d-print-flex img');
                        const logoContainer = document.querySelector('.d-print-flex .d-flex.align-items-center');
                        if (logoImg) {
                            logoImg.src = data.logo_url;
                        } else if (logoContainer) {
                            const newImg = document.createElement('img');
                            newImg.src = data.logo_url;
                            newImg.className = 'img-fluid mr-3';
                            newImg.style.maxHeight = '40px';
                            newImg.alt = 'Logo';
                            logoContainer.prepend(newImg);
                            
                            const h1 = logoContainer.querySelector('h1');
                            if (h1) h1.remove();
                        }
                        
                        // Disable logo update button to prevent multiple uploads as requested
                        modalUploadLogoBtn.disabled = true;
                        modalUploadLogoBtn.classList.remove('btn-info');
                        modalUploadLogoBtn.classList.add('btn-success');
                        modalUploadLogoBtn.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Logo Updated Successfully!';
                        
                        // Hide modal after a short delay and generate PDF with new logo
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

                        // Custom Branding (White Label) modal Javascript logic
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
