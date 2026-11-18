<?php defined('ALTUMCODE') || die() ?>

<?php
$page_url_lower = mb_strtolower($data->page->url);
$is_about_page = in_array($page_url_lower, ['about', 'about-us', 'about-page']);
$is_faq_page = ($page_url_lower === 'faq');
$is_privacy_page = in_array($page_url_lower, ['privacy-policy', 'privacy']);
$is_terms_page = in_array($page_url_lower, ['terms', 'terms-and-conditions', 'terms-of-service']);
$is_disclaimer_page = ($page_url_lower === 'disclaimer');
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-sans space-y-10">
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
        <a href="<?= url() ?>" class="hover:text-blue-600 transition-colors text-decoration-none text-slate-500"><?= l('index.breadcrumb') ?></a>
        <?php if($data->pages_category): ?>
            <i class="fas fa-chevron-right text-[10px] text-slate-400"></i>
            <a href="<?= url('pages/' . $data->pages_category->url) ?>" class="hover:text-blue-600 transition-colors text-decoration-none text-slate-500"><?= $data->pages_category->title ?></a>
        <?php endif ?>
        <i class="fas fa-chevron-right text-[10px] text-slate-400"></i>
        <span class="text-slate-900 font-bold truncate max-w-xs"><?= $data->page->title ?></span>
    </div>

    <?php if($is_disclaimer_page): ?>
        <div class="relative overflow-hidden rounded-3xl bg-slate-900 border border-slate-800 p-8 sm:p-12 lg:p-14 text-white shadow-lg space-y-4">
            <div class="space-y-4 max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-300 text-xs font-bold uppercase tracking-wider">
                    <i class="fas fa-scale-balanced text-amber-400 text-xs"></i>
                    <span>Official Legal &amp; Audit Advisory</span>
                </div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-tight m-0">
                    Website &amp; SEO Audit Disclaimer
                </h1>
                <p class="text-sm sm:text-base text-slate-300 font-normal m-0 leading-relaxed max-w-2xl">
                    Important terms and limitations concerning automated algorithmic diagnostic scores, search engine independence, and technical recommendations.
                </p>
                <div class="text-xs text-slate-400 font-medium pt-3 border-t border-slate-800 flex flex-wrap items-center gap-4">
                    <span><i class="far fa-calendar-check text-slate-400 mr-1.5"></i> Effective: <?= \Altum\Date::get($data->page->last_datetime ?? $data->page->datetime, 1) ?></span>
                    <span><i class="fas fa-shield text-slate-400 mr-1.5"></i> Binding Terms for All Audits</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-white rounded-3xl border border-slate-200 p-7 sm:p-9 shadow-xs space-y-4">
                    <div class="flex items-center gap-3.5 pb-4 border-b border-slate-100">
                        <span class="h-9 w-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-extrabold border border-blue-100 shrink-0">01</span>
                        <h2 class="text-xl font-bold text-slate-900 m-0">Automated Algorithmic Scoring &amp; Heuristics</h2>
                    </div>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed m-0">
                        SiteRankify provides an automated website crawler engineered to scan publicly accessible web pages across a series of standardized technical checkpoints (such as HTML header integrity, meta tags, schema markup, image attributes, SSL validity, and mobile viewport responsiveness). All calculated scores, letter grades, and percentages (0 to 100) are generated strictly through programmed rule-based heuristics.
                    </p>
                    <div class="p-5 rounded-2xl bg-amber-50/60 border border-amber-200/80 text-sm text-slate-700 space-y-2">
                        <div class="font-bold text-amber-900 flex items-center gap-2">
                            <i class="fas fa-circle-exclamation text-amber-600"></i>
                            <span>No Guarantee of Organic Rankings:</span>
                        </div>
                        <p class="m-0 leading-relaxed text-slate-600 text-xs sm:text-sm">
                            Attaining a 100/100 diagnostic score on SiteRankify does not guarantee #1 rankings or specific keyword placement on Google, Bing, Yahoo, or other search engines. Real-world search engines employ thousands of proprietary, machine-learned, and personalized ranking algorithms that operate completely outside third-party control.
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200 p-7 sm:p-9 shadow-xs space-y-4">
                    <div class="flex items-center gap-3.5 pb-4 border-b border-slate-100">
                        <span class="h-9 w-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-extrabold border border-blue-100 shrink-0">02</span>
                        <h2 class="text-xl font-bold text-slate-900 m-0">Search Engine Independence &amp; Affiliation</h2>
                    </div>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed m-0">
                        SiteRankify is an independent SaaS analysis engine and is in no way affiliated, associated, authorized, endorsed by, or in any way officially connected with Google LLC, Alphabet Inc., Microsoft Corporation, or any search engine entity. Search providers frequently roll out unannounced algorithmic changes (Core Updates, Helpful Content systems, Spam updates) that may alter search ranking positions regardless of on-page audit results.
                    </p>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200 p-7 sm:p-9 shadow-xs space-y-4">
                    <div class="flex items-center gap-3.5 pb-4 border-b border-slate-100">
                        <span class="h-9 w-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-extrabold border border-blue-100 shrink-0">03</span>
                        <h2 class="text-xl font-bold text-slate-900 m-0">Educational &amp; Diagnostic Use (No Legal / Financial Advice)</h2>
                    </div>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed m-0">
                        Audit reports, technical checklists, Core Web Vitals assessments, and PDF exports are provided strictly for educational and technical troubleshooting purposes. They do not constitute formal legal, commercial, or financial counsel. Implementation of diagnostic suggestions (such as robots.txt exclusions, 301 redirection rules, or canonical modifications) should always be verified in testing environments by qualified web administrators.
                    </p>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200 p-7 sm:p-9 shadow-xs space-y-4">
                    <div class="flex items-center gap-3.5 pb-4 border-b border-slate-100">
                        <span class="h-9 w-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-extrabold border border-blue-100 shrink-0">04</span>
                        <h2 class="text-xl font-bold text-slate-900 m-0">External APIs &amp; Third-Party Services</h2>
                    </div>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed m-0">
                        Our platform interfaces with public DNS authorities, SSL registries, PageSpeed API endpoints, and third-party network probes. While we strive for continuous diagnostic precision, SiteRankify makes no guarantees regarding the continuous uptime, latency, or uninhibited availability of external third-party infrastructure.
                    </p>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200 p-7 sm:p-9 shadow-xs space-y-4">
                    <div class="flex items-center gap-3.5 pb-4 border-b border-slate-100">
                        <span class="h-9 w-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-extrabold border border-blue-100 shrink-0">05</span>
                        <h2 class="text-xl font-bold text-slate-900 m-0">Limitation of Platform Liability</h2>
                    </div>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed m-0">
                        To the maximum extent permitted by applicable law, SiteRankify, its directors, employees, and developers shall not be held liable for any loss of revenue, decrease in organic impressions, traffic changes, site downtime, or incidental damages resulting from the use or inability to use our platform or diagnostic PDF reports.
                    </p>
                </div>
            </div>

            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <i class="fas fa-file-shield text-blue-600"></i>
                        <span>Legal Index</span>
                    </div>
                    <div class="space-y-2 text-sm font-semibold">
                        <a href="<?= url('page/privacy-policy') ?>" class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 text-slate-700 hover:text-blue-600 transition-colors text-decoration-none">
                            <span>Privacy &amp; Data Policy</span>
                            <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                        </a>
                        <a href="<?= url('page/terms') ?>" class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 text-slate-700 hover:text-blue-600 transition-colors text-decoration-none">
                            <span>Terms &amp; Conditions</span>
                            <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                        </a>
                        <a href="<?= url('page/faq') ?>" class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 text-slate-700 hover:text-blue-600 transition-colors text-decoration-none">
                            <span>Platform FAQ Desk</span>
                            <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                        </a>
                    </div>
                </div>

                <div class="rounded-3xl bg-slate-50 border border-slate-200 p-6 space-y-3">
                    <div class="text-sm font-bold text-slate-900">Questions Regarding Terms?</div>
                    <p class="text-xs text-slate-500 m-0 leading-relaxed">
                        If you require contractual clarification or agency compliance documentation, reach out to our legal department directly.
                    </p>
                    <a href="<?= url('contact') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition-colors text-decoration-none">
                        <span>Contact Legal Desk</span>
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>
        </div>

    <?php elseif($is_privacy_page): ?>
        <div class="relative overflow-hidden rounded-3xl bg-slate-900 border border-slate-800 p-8 sm:p-12 lg:p-14 text-white shadow-lg space-y-4">
            <div class="space-y-4 max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-300 text-xs font-bold uppercase tracking-wider">
                    <i class="fas fa-user-shield text-blue-400 text-xs"></i>
                    <span>Privacy &amp; Data Security Protocol</span>
                </div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-tight m-0">
                    Privacy &amp; Data Protection Policy
                </h1>
                <p class="text-sm sm:text-base text-slate-300 font-normal m-0 leading-relaxed max-w-2xl">
                    Your diagnostic data and personal information are protected by strict encryption standards. Learn how SiteRankify collects, stores, and respects your privacy.
                </p>
                <div class="text-xs text-slate-400 font-medium pt-3 border-t border-slate-800 flex flex-wrap items-center gap-4">
                    <span><i class="far fa-calendar-check text-slate-400 mr-1.5"></i> Effective: <?= \Altum\Date::get($data->page->last_datetime ?? $data->page->datetime, 1) ?></span>
                    <span><i class="fas fa-check-double text-emerald-400 mr-1.5"></i> GDPR &amp; CCPA Aligned</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-white rounded-3xl border border-slate-200 p-7 sm:p-9 shadow-xs space-y-4">
                    <div class="flex items-center gap-3.5 pb-4 border-b border-slate-100">
                        <span class="h-9 w-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-extrabold border border-blue-100 shrink-0">01</span>
                        <h2 class="text-xl font-bold text-slate-900 m-0">Information We Collect</h2>
                    </div>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed m-0">
                        SiteRankify collects only the necessary data needed to execute high-speed technical SEO audits, manage authenticated subscriptions, and generate white-label PDF reports:
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1.5">
                            <div class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                <i class="fas fa-user-circle text-blue-600"></i>
                                <span>Account Credentials</span>
                            </div>
                            <p class="text-xs text-slate-600 m-0 leading-relaxed">Name, verified email, and bcrypt-hashed passwords. We never store raw plain-text passwords.</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1.5">
                            <div class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                <i class="fas fa-magnifying-glass-chart text-emerald-600"></i>
                                <span>Audit Records</span>
                            </div>
                            <p class="text-xs text-slate-600 m-0 leading-relaxed">Submitted URLs, PageSpeed telemetry, crawl logs, and generated PDF diagnostic snapshots.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200 p-7 sm:p-9 shadow-xs space-y-4">
                    <div class="flex items-center gap-3.5 pb-4 border-b border-slate-100">
                        <span class="h-9 w-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-extrabold border border-blue-100 shrink-0">02</span>
                        <h2 class="text-xl font-bold text-slate-900 m-0">How We Utilize Your Information</h2>
                    </div>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed m-0">
                        All collected parameters serve legitimate operational purposes:
                    </p>
                    <ul class="space-y-2.5 p-0 m-0 list-none text-xs sm:text-sm text-slate-700 font-medium">
                        <li class="flex items-start gap-2.5"><i class="fas fa-check-circle text-blue-600 text-sm mt-0.5 shrink-0"></i> <span>Conducting and rendering automated SEO audit reports and scores in real time.</span></li>
                        <li class="flex items-start gap-2.5"><i class="fas fa-check-circle text-blue-600 text-sm mt-0.5 shrink-0"></i> <span>Processing subscription renewals, plan limits, and generating valid tax invoices.</span></li>
                        <li class="flex items-start gap-2.5"><i class="fas fa-check-circle text-blue-600 text-sm mt-0.5 shrink-0"></i> <span>Sending critical transactional alerts (password recovery, 2FA verification, audit completion notices).</span></li>
                        <li class="flex items-start gap-2.5"><i class="fas fa-check-circle text-blue-600 text-sm mt-0.5 shrink-0"></i> <span>Securing platform infrastructure against automated denial-of-service, brute-force, or malicious scraping.</span></li>
                    </ul>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200 p-7 sm:p-9 shadow-xs space-y-4">
                    <div class="flex items-center gap-3.5 pb-4 border-b border-slate-100">
                        <span class="h-9 w-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-extrabold border border-blue-100 shrink-0">03</span>
                        <h2 class="text-xl font-bold text-slate-900 m-0">Payment Processing &amp; Financial Security</h2>
                    </div>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed m-0">
                        Payment transactions are processed entirely through certified PCI-DSS Level 1 payment processors (including Stripe, PayPal, and Razorpay). SiteRankify servers <strong>never store, inspect, or log</strong> credit card details, security codes (CVV), or bank credentials.
                    </p>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200 p-7 sm:p-9 shadow-xs space-y-4">
                    <div class="flex items-center gap-3.5 pb-4 border-b border-slate-100">
                        <span class="h-9 w-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-extrabold border border-blue-100 shrink-0">04</span>
                        <h2 class="text-xl font-bold text-slate-900 m-0">Your Data Rights (GDPR &amp; CCPA)</h2>
                    </div>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed m-0">
                        You maintain full sovereignty over your information:
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1">
                            <div class="text-sm font-bold text-slate-900">Right to Portability</div>
                            <p class="text-xs text-slate-500 m-0 leading-relaxed">Download and export your audits, reports, and diagnostic data from the user dashboard anytime.</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1">
                            <div class="text-sm font-bold text-slate-900">Right to Erasure</div>
                            <p class="text-xs text-slate-500 m-0 leading-relaxed">Permanently delete your account and purge all associated audit logs via Account Deletion settings.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <i class="fas fa-file-shield text-blue-600"></i>
                        <span>Legal Index</span>
                    </div>
                    <div class="space-y-2 text-sm font-semibold">
                        <a href="<?= url('page/terms') ?>" class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 text-slate-700 hover:text-blue-600 transition-colors text-decoration-none">
                            <span>Terms &amp; Conditions</span>
                            <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                        </a>
                        <a href="<?= url('page/disclaimer') ?>" class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 text-slate-700 hover:text-blue-600 transition-colors text-decoration-none">
                            <span>Legal Disclaimer</span>
                            <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                        </a>
                        <a href="<?= url('page/faq') ?>" class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 text-slate-700 hover:text-blue-600 transition-colors text-decoration-none">
                            <span>Platform FAQ Desk</span>
                            <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                        </a>
                    </div>
                </div>

                <div class="rounded-3xl bg-slate-50 border border-slate-200 p-6 space-y-3">
                    <div class="text-sm font-bold text-slate-900">Privacy Inquiries</div>
                    <p class="text-xs text-slate-500 m-0 leading-relaxed">
                        To submit a formal data inquiry or request immediate record deletion, contact our Data Protection Officer.
                    </p>
                    <a href="<?= url('contact') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition-colors text-decoration-none">
                        <span>Message Data Officer</span>
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>
        </div>

    <?php elseif($is_terms_page): ?>
        <div class="relative overflow-hidden rounded-3xl bg-slate-900 border border-slate-800 p-8 sm:p-12 lg:p-14 text-white shadow-lg space-y-4">
            <div class="space-y-4 max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-300 text-xs font-bold uppercase tracking-wider">
                    <i class="fas fa-file-contract text-blue-400 text-xs"></i>
                    <span>Service Agreement &amp; Platform License</span>
                </div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-tight m-0">
                    Terms &amp; Conditions of Usage
                </h1>
                <p class="text-sm sm:text-base text-slate-300 font-normal m-0 leading-relaxed max-w-2xl">
                    Please read these terms carefully before accessing SiteRankify, launching automated audit crawlers, or purchasing subscription passes.
                </p>
                <div class="text-xs text-slate-400 font-medium pt-3 border-t border-slate-800 flex flex-wrap items-center gap-4">
                    <span><i class="far fa-calendar-check text-slate-400 mr-1.5"></i> Effective: <?= \Altum\Date::get($data->page->last_datetime ?? $data->page->datetime, 1) ?></span>
                    <span><i class="fas fa-gavel text-slate-400 mr-1.5"></i> Legally Binding Agreement</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-white rounded-3xl border border-slate-200 p-7 sm:p-9 shadow-xs space-y-4">
                    <div class="flex items-center gap-3.5 pb-4 border-b border-slate-100">
                        <span class="h-9 w-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-extrabold border border-blue-100 shrink-0">01</span>
                        <h2 class="text-xl font-bold text-slate-900 m-0">Service Grant &amp; License Scope</h2>
                    </div>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed m-0">
                        SiteRankify grants you a revocable, non-exclusive, non-transferable license to access our platform, execute automated technical SEO audits on domains you own or have explicit authorization to audit, and download diagnostic reports according to your purchased plan quotas.
                    </p>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200 p-7 sm:p-9 shadow-xs space-y-4">
                    <div class="flex items-center gap-3.5 pb-4 border-b border-slate-100">
                        <span class="h-9 w-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-extrabold border border-blue-100 shrink-0">02</span>
                        <h2 class="text-xl font-bold text-slate-900 m-0">Account Security &amp; Credentials</h2>
                    </div>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed m-0">
                        You are responsible for preserving the confidentiality of your credentials and for all activities that take place under your account. You agree to notify SiteRankify immediately upon suspecting any unauthorized access. We strongly advise enabling Two-Factor Authentication (2FA) in Account Security.
                    </p>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200 p-7 sm:p-9 shadow-xs space-y-4">
                    <div class="flex items-center gap-3.5 pb-4 border-b border-slate-100">
                        <span class="h-9 w-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-extrabold border border-blue-100 shrink-0">03</span>
                        <h2 class="text-xl font-bold text-slate-900 m-0">Acceptable Use &amp; Infrastructure Protection</h2>
                    </div>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed m-0">
                        Users agree strictly to avoid:
                    </p>
                    <ul class="space-y-2.5 p-0 m-0 list-none text-xs sm:text-sm text-slate-700 font-medium">
                        <li class="flex items-start gap-2.5"><i class="fas fa-circle-xmark text-rose-500 text-sm mt-0.5 shrink-0"></i> <span>Using our crawler infrastructure to trigger denial-of-service (DDoS) requests against third-party web servers.</span></li>
                        <li class="flex items-start gap-2.5"><i class="fas fa-circle-xmark text-rose-500 text-sm mt-0.5 shrink-0"></i> <span>Attempting to reverse-engineer, decompile, or bypass the proprietary SiteRankify heuristic algorithm.</span></li>
                        <li class="flex items-start gap-2.5"><i class="fas fa-circle-xmark text-rose-500 text-sm mt-0.5 shrink-0"></i> <span>Reselling raw software access without an authorized agency license or white-label tier.</span></li>
                    </ul>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200 p-7 sm:p-9 shadow-xs space-y-4">
                    <div class="flex items-center gap-3.5 pb-4 border-b border-slate-100">
                        <span class="h-9 w-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-extrabold border border-blue-100 shrink-0">04</span>
                        <h2 class="text-xl font-bold text-slate-900 m-0">Subscription Tiers, Billing &amp; Cancellations</h2>
                    </div>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed m-0">
                        Subscriptions are billed in advance on a recurring monthly or annual basis, or as a one-time payment for Lifetime Access passes. You can cancel your subscription anytime via your Account Plan portal. Cancellations take effect at the conclusion of your current billing period without sudden loss of access.
                    </p>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200 p-7 sm:p-9 shadow-xs space-y-4">
                    <div class="flex items-center gap-3.5 pb-4 border-b border-slate-100">
                        <span class="h-9 w-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-extrabold border border-blue-100 shrink-0">05</span>
                        <h2 class="text-xl font-bold text-slate-900 m-0">White-Label &amp; Commercial Client Rights</h2>
                    </div>
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed m-0">
                        Users on plans with White-Label permissions are explicitly granted commercial rights to embed their agency branding, custom logos, metadata, and color themes onto exported PDF audit reports and provide those reports directly to paying clients.
                    </p>
                </div>
            </div>

            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <i class="fas fa-file-shield text-blue-600"></i>
                        <span>Legal Index</span>
                    </div>
                    <div class="space-y-2 text-sm font-semibold">
                        <a href="<?= url('page/privacy-policy') ?>" class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 text-slate-700 hover:text-blue-600 transition-colors text-decoration-none">
                            <span>Privacy &amp; Data Policy</span>
                            <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                        </a>
                        <a href="<?= url('page/disclaimer') ?>" class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 text-slate-700 hover:text-blue-600 transition-colors text-decoration-none">
                            <span>Legal Disclaimer</span>
                            <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                        </a>
                        <a href="<?= url('page/faq') ?>" class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 text-slate-700 hover:text-blue-600 transition-colors text-decoration-none">
                            <span>Platform FAQ Desk</span>
                            <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                        </a>
                    </div>
                </div>

                <div class="rounded-3xl bg-slate-50 border border-slate-200 p-6 space-y-3">
                    <div class="text-sm font-bold text-slate-900">Commercial Questions?</div>
                    <p class="text-xs text-slate-500 m-0 leading-relaxed">
                        For volume audits, enterprise agreements, or API integration contracts, contact our sales and licensing division.
                    </p>
                    <a href="<?= url('contact') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition-colors text-decoration-none">
                        <span>Contact Enterprise Sales</span>
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>
        </div>

    <?php elseif($is_faq_page): ?>
        <div class="relative overflow-hidden rounded-3xl bg-slate-900 border border-slate-800 p-8 sm:p-12 lg:p-14 text-white shadow-lg space-y-4">
            <div class="space-y-4 max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-300 text-xs font-bold uppercase tracking-wider">
                    <i class="fas fa-circle-question text-blue-400 text-xs"></i>
                    <span>Knowledge Base &amp; Answers</span>
                </div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-tight m-0">
                    Frequently Asked Questions
                </h1>
                <p class="text-sm sm:text-base text-slate-300 font-normal m-0 leading-relaxed max-w-2xl">
                    Everything you need to know about SiteRankify crawler algorithms, scoring formulas, white-label exports, and account subscriptions.
                </p>
                <div class="text-xs text-slate-400 font-medium pt-3 border-t border-slate-800 flex flex-wrap items-center gap-4">
                    <span><i class="far fa-clock text-slate-400 mr-1.5"></i> Verified for Platform 2.0</span>
                    <span><i class="fas fa-headset text-slate-400 mr-1.5"></i> 24/7 Support Available</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-8 space-y-8">
                <!-- FAQ Section 1 -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 pb-2 border-b border-slate-200">
                        <i class="fas fa-magnifying-glass-chart text-blue-600 text-sm"></i>
                        <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider m-0">Crawler Diagnostics &amp; Scoring</h2>
                    </div>

                    <div class="space-y-3">
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden transition-all">
                            <button type="button" class="w-full px-6 py-4 flex items-center justify-between text-left font-bold text-slate-900 hover:text-blue-600 transition-colors focus:outline-none cursor-pointer border-0 bg-transparent text-sm sm:text-base" onclick="document.getElementById('faq_1').classList.toggle('hidden'); this.querySelector('i').classList.toggle('rotate-180');">
                                <span>How does SiteRankify calculate the SEO audit score?</span>
                                <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform"></i>
                            </button>
                            <div id="faq_1" class="hidden px-6 pb-5 pt-2 text-slate-600 text-xs sm:text-sm leading-relaxed border-t border-slate-100 bg-slate-50/50">
                                Our crawler checks over 50 technical factors across 5 main categories: Meta (title, descriptions, canonical tags), Media &amp; Content (headings, image alt text, word counts), Core Web Vitals (FCP, LCP, CLS performance), Security (SSL certificate validity, HTTPS redirection, security headers), and Mobile Responsiveness (viewport parameters). The weighted score is displayed from 0 to 100.
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden transition-all">
                            <button type="button" class="w-full px-6 py-4 flex items-center justify-between text-left font-bold text-slate-900 hover:text-blue-600 transition-colors focus:outline-none cursor-pointer border-0 bg-transparent text-sm sm:text-base" onclick="document.getElementById('faq_2').classList.toggle('hidden'); this.querySelector('i').classList.toggle('rotate-180');">
                                <span>How fast does a website audit complete?</span>
                                <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform"></i>
                            </button>
                            <div id="faq_2" class="hidden px-6 pb-5 pt-2 text-slate-600 text-xs sm:text-sm leading-relaxed border-t border-slate-100 bg-slate-50/50">
                                Single-page technical audits typically finish in under 10 seconds. Distributed server nodes process HTTP responses, check DNS records, and test security protocols in parallel.
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden transition-all">
                            <button type="button" class="w-full px-6 py-4 flex items-center justify-between text-left font-bold text-slate-900 hover:text-blue-600 transition-colors focus:outline-none cursor-pointer border-0 bg-transparent text-sm sm:text-base" onclick="document.getElementById('faq_3').classList.toggle('hidden'); this.querySelector('i').classList.toggle('rotate-180');">
                                <span>Can I audit password-protected or staging websites?</span>
                                <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform"></i>
                            </button>
                            <div id="faq_3" class="hidden px-6 pb-5 pt-2 text-slate-600 text-xs sm:text-sm leading-relaxed border-t border-slate-100 bg-slate-50/50">
                                Target URLs must be publicly accessible via standard HTTP/HTTPS. Localhost or private staging environments behind authentication walls cannot be audited unless made publicly reachable through tunnels (such as ngrok or Cloudflare Tunnels).
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ Section 2 -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 pb-2 border-b border-slate-200">
                        <i class="fas fa-file-pdf text-blue-600 text-sm"></i>
                        <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider m-0">PDF Reports &amp; White-Labeling</h2>
                    </div>

                    <div class="space-y-3">
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden transition-all">
                            <button type="button" class="w-full px-6 py-4 flex items-center justify-between text-left font-bold text-slate-900 hover:text-blue-600 transition-colors focus:outline-none cursor-pointer border-0 bg-transparent text-sm sm:text-base" onclick="document.getElementById('faq_4').classList.toggle('hidden'); this.querySelector('i').classList.toggle('rotate-180');">
                                <span>How does White-Label report customization work?</span>
                                <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform"></i>
                            </button>
                            <div id="faq_4" class="hidden px-6 pb-5 pt-2 text-slate-600 text-xs sm:text-sm leading-relaxed border-t border-slate-100 bg-slate-50/50">
                                Subscribers with white-label privileges can go to Account Preferences and upload their own logo, specify custom agency titles, and define custom report footer text. Exported PDF reports reflect their custom branding automatically.
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden transition-all">
                            <button type="button" class="w-full px-6 py-4 flex items-center justify-between text-left font-bold text-slate-900 hover:text-blue-600 transition-colors focus:outline-none cursor-pointer border-0 bg-transparent text-sm sm:text-base" onclick="document.getElementById('faq_5').classList.toggle('hidden'); this.querySelector('i').classList.toggle('rotate-180');">
                                <span>Are PDF exports available on the free plan?</span>
                                <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform"></i>
                            </button>
                            <div id="faq_5" class="hidden px-6 pb-5 pt-2 text-slate-600 text-xs sm:text-sm leading-relaxed border-t border-slate-100 bg-slate-50/50">
                                The free tier provides 10 online web audits each month. PDF export generation and white-label customization are included in the Premier Lifetime plan and subscription tiers.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ Section 3 -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 pb-2 border-b border-slate-200">
                        <i class="fas fa-credit-card text-blue-600 text-sm"></i>
                        <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider m-0">Plans, Billing &amp; Subscriptions</h2>
                    </div>

                    <div class="space-y-3">
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden transition-all">
                            <button type="button" class="w-full px-6 py-4 flex items-center justify-between text-left font-bold text-slate-900 hover:text-blue-600 transition-colors focus:outline-none cursor-pointer border-0 bg-transparent text-sm sm:text-base" onclick="document.getElementById('faq_6').classList.toggle('hidden'); this.querySelector('i').classList.toggle('rotate-180');">
                                <span>What is the Premier Lifetime plan?</span>
                                <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform"></i>
                            </button>
                            <div id="faq_6" class="hidden px-6 pb-5 pt-2 text-slate-600 text-xs sm:text-sm leading-relaxed border-t border-slate-100 bg-slate-50/50">
                                The Premier Lifetime plan is a one-time purchase granting ongoing monthly audit replenishments without recurring subscription invoices. It unlocks full diagnostic breakdown, white-label branding, and PDF exports.
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden transition-all">
                            <button type="button" class="w-full px-6 py-4 flex items-center justify-between text-left font-bold text-slate-900 hover:text-blue-600 transition-colors focus:outline-none cursor-pointer border-0 bg-transparent text-sm sm:text-base" onclick="document.getElementById('faq_7').classList.toggle('hidden'); this.querySelector('i').classList.toggle('rotate-180');">
                                <span>Can I toggle between USD and INR currencies?</span>
                                <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform"></i>
                            </button>
                            <div id="faq_7" class="hidden px-6 pb-5 pt-2 text-slate-600 text-xs sm:text-sm leading-relaxed border-t border-slate-100 bg-slate-50/50">
                                Yes, you can toggle between USD and INR pricing on our plans page instantly without reloading the page. Recurring subscriptions can also be cancelled anytime directly from the Account Plan page.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dynamic DB FAQs if assigned -->
                <?php if(!empty($data->faqs)): ?>
                    <div class="space-y-4 pt-4 border-t border-slate-200">
                        <div class="flex items-center gap-2 pb-2 border-b border-slate-200">
                            <i class="fas fa-book text-blue-600 text-sm"></i>
                            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider m-0">Additional FAQs</h2>
                        </div>

                        <div class="space-y-3">
                            <?php foreach($data->faqs as $index => $faq): ?>
                                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden transition-all">
                                    <button type="button" class="w-full px-6 py-4 flex items-center justify-between text-left font-bold text-slate-900 hover:text-blue-600 transition-colors focus:outline-none cursor-pointer border-0 bg-transparent text-sm sm:text-base" onclick="document.getElementById('faq_db_<?= $faq->faq_id ?>').classList.toggle('hidden'); this.querySelector('i').classList.toggle('rotate-180');">
                                        <span><?= $faq->title ?></span>
                                        <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform"></i>
                                    </button>
                                    <div id="faq_db_<?= $faq->faq_id ?>" class="hidden px-6 pb-5 pt-2 text-slate-600 text-xs sm:text-sm leading-relaxed border-t border-slate-100 bg-slate-50/50">
                                        <?= $faq->description ?>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                <?php endif ?>
            </div>

            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <i class="fas fa-headset text-blue-600"></i>
                        <span>Still Need Assistance?</span>
                    </div>
                    <p class="text-xs text-slate-500 m-0 leading-relaxed">
                        Have a specific question not addressed in this FAQ desk? Our customer care specialists are available 24/7 to help resolve your inquiry.
                    </p>
                    <a href="<?= url('contact') ?>" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition-colors text-decoration-none w-full justify-center">
                        <i class="fas fa-envelope text-xs"></i>
                        <span>Contact Customer Support</span>
                    </a>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <i class="fas fa-file-shield text-blue-600"></i>
                        <span>Legal Documentation</span>
                    </div>
                    <div class="space-y-2 text-sm font-semibold">
                        <a href="<?= url('page/privacy-policy') ?>" class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 text-slate-700 hover:text-blue-600 transition-colors text-decoration-none">
                            <span>Privacy &amp; Data Policy</span>
                            <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                        </a>
                        <a href="<?= url('page/terms') ?>" class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 text-slate-700 hover:text-blue-600 transition-colors text-decoration-none">
                            <span>Terms &amp; Conditions</span>
                            <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                        </a>
                        <a href="<?= url('page/disclaimer') ?>" class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 text-slate-700 hover:text-blue-600 transition-colors text-decoration-none">
                            <span>Legal Disclaimer</span>
                            <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    <?php elseif($is_about_page): ?>
        <!-- ========================================================
             ABOUT US PAGE - FULLY DYNAMIC & BEAUTIFULLY STRUCTURED
             ======================================================== -->

        <!-- 1. Hero Banner Block -->
        <div class="relative overflow-hidden rounded-3xl bg-slate-900 border border-slate-800 p-8 sm:p-12 lg:p-16 text-white shadow-xl space-y-10">
            <div class="space-y-6 max-w-4xl">
                <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-300 text-xs font-bold uppercase tracking-wider">
                    <span class="flex h-2 w-2 rounded-full bg-blue-400 animate-pulse"></span>
                    <span><?= settings()->main->title ?> Intelligence &amp; Diagnostics</span>
                </div>
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-[1.15] m-0">
                    Empowering Modern Creators With Instant, Deep SEO Diagnostics
                </h1>
                <p class="text-base sm:text-lg lg:text-xl text-slate-300 font-normal m-0 leading-relaxed max-w-3xl">
                    SiteRankify was created on a single core conviction: website audits should be lightning-fast, transparently actionable, and accessible without expensive agency retainer fees. We evaluate 50+ technical parameters in seconds to help developers, agencies, and webmasters master search visibility.
                </p>
                
                <!-- Live KPI Metrics Strip -->
                <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-5 gap-6 pt-8 border-t border-slate-800">
                    <div class="space-y-1.5">
                        <div class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">50+</div>
                        <div class="text-xs sm:text-sm text-slate-400 font-semibold uppercase tracking-wider">Audit Checks</div>
                    </div>
                    <div class="space-y-1.5">
                        <div class="text-3xl sm:text-4xl font-extrabold text-blue-400 tracking-tight">&lt; 10s</div>
                        <div class="text-xs sm:text-sm text-slate-400 font-semibold uppercase tracking-wider">Scan Velocity</div>
                    </div>
                    <div class="space-y-1.5">
                        <div class="text-3xl sm:text-4xl font-extrabold text-emerald-400 tracking-tight">99.9%</div>
                        <div class="text-xs sm:text-sm text-slate-400 font-semibold uppercase tracking-wider">Crawler Uptime</div>
                    </div>
                    <div class="space-y-1.5">
                        <div class="text-3xl sm:text-4xl font-extrabold text-amber-400 tracking-tight">100%</div>
                        <div class="text-xs sm:text-sm text-slate-400 font-semibold uppercase tracking-wider">White-Label Ready</div>
                    </div>
                    <div class="space-y-1.5 col-span-2 sm:col-span-1">
                        <div class="text-3xl sm:text-4xl font-extrabold text-indigo-400 tracking-tight"><?= nr($data->page->total_views) ?>+</div>
                        <div class="text-xs sm:text-sm text-slate-400 font-semibold uppercase tracking-wider">Audits &amp; Reads</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Origin, Mission & Architecture (2-Column Grid) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
            <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-12 shadow-xs flex flex-col justify-between space-y-8">
                <div class="space-y-5">
                    <div class="inline-flex items-center gap-2 text-xs font-extrabold text-blue-600 uppercase tracking-wider">
                        <i class="fas fa-bullseye text-sm"></i>
                        <span>The SiteRankify Mission</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight m-0 leading-snug">
                        Bridging Technical Crawl Audits With Tangible Organic Growth
                    </h2>
                    <p class="text-base text-slate-600 leading-relaxed m-0 font-normal">
                        In today's algorithmic search ecosystem, search engines demand far more than basic keyword placement. Modern crawlers evaluate web pages using neural ranking networks, strict Core Web Vitals speed thresholds, structured JSON-LD schemas, and HTTPS cryptographic security.
                    </p>
                    <p class="text-base text-slate-600 leading-relaxed m-0 font-normal">
                        Legacy enterprise SEO suites take hours to index, bombard non-technical teams with cryptic errors, and charge hundreds of dollars every month. SiteRankify was engineered to replace that friction with precision diagnostics: run an audit in 10 seconds, immediately see what's broken with step-by-step code guidance, and export professional white-label PDF dossiers directly to clients.
                    </p>
                </div>

                <div class="p-6 sm:p-7 rounded-2xl bg-slate-50 border border-slate-200/80 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
                    <div class="space-y-1">
                        <div class="text-base font-bold text-slate-900">Run an instant audit today</div>
                        <div class="text-sm text-slate-500">Test any public URL without signing contracts or entering credit cards.</div>
                    </div>
                    <a href="<?= url() ?>" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-xs transition-all text-decoration-none shrink-0 inline-flex items-center gap-2">
                        <span>Launch Free Audit</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>

            <div class="lg:col-span-5 bg-slate-900 rounded-3xl border border-slate-800 p-8 sm:p-12 text-white shadow-lg flex flex-col justify-between space-y-8">
                <div class="space-y-4">
                    <div class="inline-flex items-center gap-2 text-xs font-bold text-blue-400 uppercase tracking-wider">
                        <i class="fas fa-microchip"></i>
                        <span>Crawler Architecture</span>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-bold text-white tracking-tight m-0 leading-tight">
                        Engineered on Distributed Parallel Execution
                    </h3>
                    <p class="text-sm sm:text-base text-slate-300 leading-relaxed m-0 font-normal">
                        Behind every SiteRankify scan is an asynchronous crawling engine built to evaluate live HTTP response payloads, parse deep HTML DOM trees, simulate mobile viewports, and test TLS cryptographic handshakes simultaneously.
                    </p>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-white/5 border border-white/10">
                        <div class="h-11 w-11 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center text-lg font-bold shrink-0">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <div>
                            <div class="text-sm sm:text-base font-bold text-white">Parallel HTTP/2 Pipeline</div>
                            <div class="text-xs sm:text-sm text-slate-400">Low-latency distributed network requests</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-white/5 border border-white/10">
                        <div class="h-11 w-11 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-lg font-bold shrink-0">
                            <i class="fas fa-shield-halved"></i>
                        </div>
                        <div>
                            <div class="text-sm sm:text-base font-bold text-white">Zero Third-Party Data Leakage</div>
                            <div class="text-xs sm:text-sm text-slate-400">End-to-end encrypted query telemetry</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-white/5 border border-white/10">
                        <div class="h-11 w-11 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-lg font-bold shrink-0">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div>
                            <div class="text-sm sm:text-base font-bold text-white">High-DPI Vector PDF Render</div>
                            <div class="text-xs sm:text-sm text-slate-400">Custom branded client deliverables</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Four Core Pillars Grid -->
        <div class="space-y-8">
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <span class="text-xs font-extrabold text-blue-600 uppercase tracking-wider">Platform Capabilities</span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight m-0">The Four Pillars of SiteRankify</h2>
                <p class="text-sm sm:text-base text-slate-600 m-0 leading-relaxed font-normal">Every feature is designed to deliver clarity, eliminate ambiguity, and boost organic search performance.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-xs hover:border-blue-300 hover:shadow-lg transition-all flex flex-col justify-between space-y-6 group">
                    <div class="space-y-4">
                        <div class="h-14 w-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl border border-blue-100 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-xs">
                            <i class="fas fa-magnifying-glass-chart"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 m-0 group-hover:text-blue-600 transition-colors">50+ Technical Audits</h3>
                        <p class="text-sm sm:text-[15px] text-slate-600 m-0 leading-relaxed font-normal">
                            Comprehensive validation of meta tags, headings hierarchy, Open Graph, canonical URLs, image alt coverage, broken links, and keyword density.
                        </p>
                    </div>
                    <div class="text-xs font-bold text-blue-600 flex items-center gap-1.5 pt-2">
                        <span>Deep Code Inspection</span>
                        <i class="fas fa-arrow-right text-[11px]"></i>
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-xs hover:border-indigo-300 hover:shadow-lg transition-all flex flex-col justify-between space-y-6 group">
                    <div class="space-y-4">
                        <div class="h-14 w-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl border border-indigo-100 group-hover:bg-indigo-600 group-hover:text-white transition-all shadow-xs">
                            <i class="fas fa-gauge-high"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 m-0 group-hover:text-indigo-600 transition-colors">Core Web Vitals</h3>
                        <p class="text-sm sm:text-[15px] text-slate-600 m-0 leading-relaxed font-normal">
                            Real-time telemetry analysis for page load speeds, First Contentful Paint (FCP), Largest Contentful Paint (LCP), and mobile viewport rendering.
                        </p>
                    </div>
                    <div class="text-xs font-bold text-indigo-600 flex items-center gap-1.5 pt-2">
                        <span>Speed &amp; UX Benchmarking</span>
                        <i class="fas fa-arrow-right text-[11px]"></i>
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-xs hover:border-slate-400 hover:shadow-lg transition-all flex flex-col justify-between space-y-6 group">
                    <div class="space-y-4">
                        <div class="h-14 w-14 rounded-2xl bg-slate-100 text-slate-900 flex items-center justify-center text-2xl border border-slate-200 group-hover:bg-slate-900 group-hover:text-white transition-all shadow-xs">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 m-0 group-hover:text-slate-900 transition-colors">White-Label Studio</h3>
                        <p class="text-sm sm:text-[15px] text-slate-600 m-0 leading-relaxed font-normal">
                            Upload your agency logo, define custom brand color palettes, and export executive-ready PDF audit reports that win and retain high-value clients.
                        </p>
                    </div>
                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5 pt-2">
                        <span>Commercial Deliverables</span>
                        <i class="fas fa-arrow-right text-[11px]"></i>
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-xs hover:border-emerald-300 hover:shadow-lg transition-all flex flex-col justify-between space-y-6 group">
                    <div class="space-y-4">
                        <div class="h-14 w-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl border border-emerald-100 group-hover:bg-emerald-600 group-hover:text-white transition-all shadow-xs">
                            <i class="fas fa-lock"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 m-0 group-hover:text-emerald-600 transition-colors">Security &amp; SSL Analysis</h3>
                        <p class="text-sm sm:text-[15px] text-slate-600 m-0 leading-relaxed font-normal">
                            Inspect SSL/TLS certificate validity, HTTPS redirection integrity, HTTP Strict Transport Security (HSTS), and modern security header defenses.
                        </p>
                    </div>
                    <div class="text-xs font-bold text-emerald-600 flex items-center gap-1.5 pt-2">
                        <span>Infrastructure Integrity</span>
                        <i class="fas fa-arrow-right text-[11px]"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. How SiteRankify Operates - 4-Step Process (REDESIGNED FOR EXCELLENT SPACING, FONT SIZE & LINE-HEIGHT) -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-12 lg:p-16 shadow-xs space-y-12">
            <div class="text-center max-w-3xl mx-auto space-y-3">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50 text-blue-700 text-xs font-bold uppercase tracking-wider border border-blue-100">
                    <i class="fas fa-route text-blue-600"></i>
                    <span>Step-by-Step Execution</span>
                </div>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight m-0 leading-tight">
                    How SiteRankify Operates
                </h2>
                <p class="text-base sm:text-lg text-slate-600 m-0 leading-relaxed font-normal">
                    From raw URL entry to complete actionable diagnostic reports in seconds.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                <!-- Step 01 -->
                <div class="p-8 rounded-3xl bg-slate-50/80 border border-slate-200/90 hover:bg-white hover:border-blue-300 hover:shadow-xl transition-all duration-300 flex flex-col justify-between space-y-6 group">
                    <div class="space-y-5">
                        <div class="flex items-center justify-between">
                            <span class="h-12 w-12 rounded-2xl bg-blue-600 text-white font-black text-lg flex items-center justify-center shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform">
                                01
                            </span>
                            <span class="h-12 w-12 rounded-2xl bg-white border border-slate-200/80 text-slate-600 group-hover:text-blue-600 group-hover:border-blue-200 flex items-center justify-center text-xl shadow-2xs transition-all">
                                <i class="fas fa-globe"></i>
                            </span>
                        </div>
                        <div class="space-y-2">
                            <h4 class="text-xl font-bold text-slate-900 tracking-tight leading-snug group-hover:text-blue-600 transition-colors m-0">
                                Input Target URL
                            </h4>
                            <p class="text-sm sm:text-[15px] text-slate-600 leading-relaxed font-normal m-0">
                                Enter any public website URL or landing page. Our distributed network dispatches asynchronous HTTP crawlers immediately.
                            </p>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-slate-200/60 flex items-center gap-2 text-xs font-bold text-slate-500 group-hover:text-blue-600 transition-colors">
                        <i class="fas fa-bolt text-blue-500 text-[11px]"></i>
                        <span>Instant Crawler Dispatch</span>
                    </div>
                </div>

                <!-- Step 02 -->
                <div class="p-8 rounded-3xl bg-slate-50/80 border border-slate-200/90 hover:bg-white hover:border-blue-300 hover:shadow-xl transition-all duration-300 flex flex-col justify-between space-y-6 group">
                    <div class="space-y-5">
                        <div class="flex items-center justify-between">
                            <span class="h-12 w-12 rounded-2xl bg-blue-600 text-white font-black text-lg flex items-center justify-center shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform">
                                02
                            </span>
                            <span class="h-12 w-12 rounded-2xl bg-white border border-slate-200/80 text-slate-600 group-hover:text-blue-600 group-hover:border-blue-200 flex items-center justify-center text-xl shadow-2xs transition-all">
                                <i class="fas fa-microchip"></i>
                            </span>
                        </div>
                        <div class="space-y-2">
                            <h4 class="text-xl font-bold text-slate-900 tracking-tight leading-snug group-hover:text-blue-600 transition-colors m-0">
                                50+ Parallel Tests
                            </h4>
                            <p class="text-sm sm:text-[15px] text-slate-600 leading-relaxed font-normal m-0">
                                Our engine tests HTML meta tags, heading hierarchies, Core Web Vitals, mobile viewports, SSL certificates, and security headers.
                            </p>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-slate-200/60 flex items-center gap-2 text-xs font-bold text-slate-500 group-hover:text-blue-600 transition-colors">
                        <i class="fas fa-check-double text-emerald-500 text-[11px]"></i>
                        <span>Comprehensive Multi-Scan</span>
                    </div>
                </div>

                <!-- Step 03 -->
                <div class="p-8 rounded-3xl bg-slate-50/80 border border-slate-200/90 hover:bg-white hover:border-blue-300 hover:shadow-xl transition-all duration-300 flex flex-col justify-between space-y-6 group">
                    <div class="space-y-5">
                        <div class="flex items-center justify-between">
                            <span class="h-12 w-12 rounded-2xl bg-blue-600 text-white font-black text-lg flex items-center justify-center shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform">
                                03
                            </span>
                            <span class="h-12 w-12 rounded-2xl bg-white border border-slate-200/80 text-slate-600 group-hover:text-blue-600 group-hover:border-blue-200 flex items-center justify-center text-xl shadow-2xs transition-all">
                                <i class="fas fa-code"></i>
                            </span>
                        </div>
                        <div class="space-y-2">
                            <h4 class="text-xl font-bold text-slate-900 tracking-tight leading-snug group-hover:text-blue-600 transition-colors m-0">
                                Prioritized Code Fixes
                            </h4>
                            <p class="text-sm sm:text-[15px] text-slate-600 leading-relaxed font-normal m-0">
                                Review overall weighted scores (0 to 100) and step-by-step code guidance to fix critical issues, warnings, and performance bottlenecks.
                            </p>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-slate-200/60 flex items-center gap-2 text-xs font-bold text-slate-500 group-hover:text-blue-600 transition-colors">
                        <i class="fas fa-wrench text-amber-500 text-[11px]"></i>
                        <span>Actionable Resolution</span>
                    </div>
                </div>

                <!-- Step 04 -->
                <div class="p-8 rounded-3xl bg-slate-50/80 border border-slate-200/90 hover:bg-white hover:border-blue-300 hover:shadow-xl transition-all duration-300 flex flex-col justify-between space-y-6 group">
                    <div class="space-y-5">
                        <div class="flex items-center justify-between">
                            <span class="h-12 w-12 rounded-2xl bg-blue-600 text-white font-black text-lg flex items-center justify-center shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform">
                                04
                            </span>
                            <span class="h-12 w-12 rounded-2xl bg-white border border-slate-200/80 text-slate-600 group-hover:text-blue-600 group-hover:border-blue-200 flex items-center justify-center text-xl shadow-2xs transition-all">
                                <i class="fas fa-file-pdf"></i>
                            </span>
                        </div>
                        <div class="space-y-2">
                            <h4 class="text-xl font-bold text-slate-900 tracking-tight leading-snug group-hover:text-blue-600 transition-colors m-0">
                                Export Branded PDF
                            </h4>
                            <p class="text-sm sm:text-[15px] text-slate-600 leading-relaxed font-normal m-0">
                                Generate high-resolution vector PDF reports with your agency's logo, brand colors, and contact info to deliver to paying clients.
                            </p>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-slate-200/60 flex items-center gap-2 text-xs font-bold text-slate-500 group-hover:text-blue-600 transition-colors">
                        <i class="fas fa-award text-indigo-500 text-[11px]"></i>
                        <span>Client Deliverables</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. The Diagnostic Spectrum Matrix -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-12 lg:p-16 shadow-xs space-y-10">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 pb-6 border-b border-slate-100">
                <div class="space-y-2">
                    <span class="text-xs font-extrabold text-blue-600 uppercase tracking-wider">The Diagnostic Spectrum</span>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight m-0">What Our Heuristic Engine Inspects</h2>
                    <p class="text-base text-slate-600 m-0 font-normal">Every single audit cycle verifies technical checkpoints across 6 core web disciplines.</p>
                </div>
                <div class="text-sm font-bold text-slate-600">
                    <i class="fas fa-check-double text-emerald-500 mr-1.5"></i> Continually updated with search engine standards
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Category 1 -->
                <div class="p-7 rounded-3xl bg-slate-50 border border-slate-200/80 space-y-4">
                    <div class="flex items-center gap-3 text-base sm:text-lg font-bold text-slate-900">
                        <span class="h-9 w-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-sm">
                            <i class="fas fa-tags"></i>
                        </span>
                        <span>Meta &amp; Content Quality</span>
                    </div>
                    <ul class="text-sm sm:text-[15px] text-slate-600 space-y-3 p-0 m-0 list-none font-normal">
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs shrink-0"></i> <span>Title Tag character length &amp; relevance</span></li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs shrink-0"></i> <span>Meta Description synthesis</span></li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs shrink-0"></i> <span>Strict Heading hierarchy (H1 - H6)</span></li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs shrink-0"></i> <span>Text-to-HTML code density ratio</span></li>
                    </ul>
                </div>

                <!-- Category 2 -->
                <div class="p-7 rounded-3xl bg-slate-50 border border-slate-200/80 space-y-4">
                    <div class="flex items-center gap-3 text-base sm:text-lg font-bold text-slate-900">
                        <span class="h-9 w-9 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm">
                            <i class="fas fa-network-wired"></i>
                        </span>
                        <span>Crawling &amp; Indexability</span>
                    </div>
                    <ul class="text-sm sm:text-[15px] text-slate-600 space-y-3 p-0 m-0 list-none font-normal">
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs shrink-0"></i> <span>Robots.txt directive validation</span></li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs shrink-0"></i> <span>XML Sitemap accessibility</span></li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs shrink-0"></i> <span>Self-referential Canonical tags</span></li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs shrink-0"></i> <span>Internal and external hyperlink tracking</span></li>
                    </ul>
                </div>

                <!-- Category 3 -->
                <div class="p-7 rounded-3xl bg-slate-50 border border-slate-200/80 space-y-4">
                    <div class="flex items-center gap-3 text-base sm:text-lg font-bold text-slate-900">
                        <span class="h-9 w-9 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm">
                            <i class="fas fa-mobile-screen-button"></i>
                        </span>
                        <span>Mobile &amp; Performance</span>
                    </div>
                    <ul class="text-sm sm:text-[15px] text-slate-600 space-y-3 p-0 m-0 list-none font-normal">
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs shrink-0"></i> <span>Responsive Viewport definitions</span></li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs shrink-0"></i> <span>Image Alt tag completeness</span></li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs shrink-0"></i> <span>HTML, CSS &amp; JavaScript compression</span></li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs shrink-0"></i> <span>Server response time (TTFB)</span></li>
                    </ul>
                </div>

                <!-- Category 4 -->
                <div class="p-7 rounded-3xl bg-slate-50 border border-slate-200/80 space-y-4">
                    <div class="flex items-center gap-3 text-base sm:text-lg font-bold text-slate-900">
                        <span class="h-9 w-9 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-sm">
                            <i class="fas fa-shield-halved"></i>
                        </span>
                        <span>Security &amp; Encryption</span>
                    </div>
                    <ul class="text-sm sm:text-[15px] text-slate-600 space-y-3 p-0 m-0 list-none font-normal">
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs shrink-0"></i> <span>SSL / TLS Certificate expiration check</span></li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs shrink-0"></i> <span>HTTPS Automatic Redirection test</span></li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs shrink-0"></i> <span>HTTP Strict Transport Security (HSTS)</span></li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs shrink-0"></i> <span>Mixed-content insecure resource audit</span></li>
                    </ul>
                </div>

                <!-- Category 5 -->
                <div class="p-7 rounded-3xl bg-slate-50 border border-slate-200/80 space-y-4">
                    <div class="flex items-center gap-3 text-base sm:text-lg font-bold text-slate-900">
                        <span class="h-9 w-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-sm">
                            <i class="fas fa-share-nodes"></i>
                        </span>
                        <span>Social &amp; Schema Graphs</span>
                    </div>
                    <ul class="text-sm sm:text-[15px] text-slate-600 space-y-3 p-0 m-0 list-none font-normal">
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs shrink-0"></i> <span>OpenGraph (OG) Title, Image &amp; Description</span></li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs shrink-0"></i> <span>Twitter Card metadata format</span></li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs shrink-0"></i> <span>Structured Schema.org microdata</span></li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs shrink-0"></i> <span>Favicon &amp; Apple Touch Icons presence</span></li>
                    </ul>
                </div>

                <!-- Category 6 -->
                <div class="p-7 rounded-3xl bg-slate-50 border border-slate-200/80 space-y-4">
                    <div class="flex items-center gap-3 text-base sm:text-lg font-bold text-slate-900">
                        <span class="h-9 w-9 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm">
                            <i class="fas fa-link"></i>
                        </span>
                        <span>Link Health &amp; Architecture</span>
                    </div>
                    <ul class="text-sm sm:text-[15px] text-slate-600 space-y-3 p-0 m-0 list-none font-normal">
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs shrink-0"></i> <span>Internal vs. External link ratio</span></li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs shrink-0"></i> <span>Broken hyperlink (404) detection</span></li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs shrink-0"></i> <span>NoFollow attribute usage audit</span></li>
                        <li class="flex items-center gap-2.5"><i class="fas fa-check text-emerald-500 text-xs shrink-0"></i> <span>URL slug cleanliness and length</span></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- 6. Operating Principles (3 Cards) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
            <div class="bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-9 shadow-xs space-y-4">
                <div class="h-12 w-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl border border-blue-100">
                    <i class="fas fa-scale-balanced"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 m-0">Zero Vanity Metrics</h3>
                <p class="text-sm sm:text-[15px] text-slate-600 m-0 leading-relaxed font-normal">
                    We ignore arbitrary proprietary scores that have no basis in reality. Our recommendations stem strictly from documented search engine protocols and W3C web standards.
                </p>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-9 shadow-xs space-y-4">
                <div class="h-12 w-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl border border-indigo-100">
                    <i class="fas fa-shield-heart"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 m-0">Privacy-First Architecture</h3>
                <p class="text-sm sm:text-[15px] text-slate-600 m-0 leading-relaxed font-normal">
                    Your queries and reports belong strictly to you. We do not sell your client lists, harvest submitted domains, or track sensitive traffic telemetry.
                </p>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-9 shadow-xs space-y-4">
                <div class="h-12 w-12 rounded-2xl bg-slate-100 text-slate-900 flex items-center justify-center text-xl border border-slate-200">
                    <i class="fas fa-code-commit"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 m-0">Built for Direct Actionability</h3>
                <p class="text-sm sm:text-[15px] text-slate-600 m-0 leading-relaxed font-normal">
                    Every diagnostic alert comes accompanied by the precise code snippet or configuration change needed to resolve it quickly on your server or CMS.
                </p>
            </div>
        </div>

        <!-- 7. Dynamic DB Content Block (if provided in CMS) -->
        <?php if(!empty(trim(strip_tags($data->page->content ?? '')))): ?>
            <div class="bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-12 shadow-xs space-y-6">
                <div class="pb-4 border-b border-slate-100">
                    <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Company Story &amp; Details</span>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight m-0 mt-1"><?= $data->page->title ?></h3>
                </div>

                <div class="prose max-w-none text-slate-700 leading-relaxed text-base space-y-4">
                    <div id="page_content" class="<?= $data->page->editor == 'wysiwyg' ? 'ql-content' : null ?>">
                        <?= $data->page->content ?>
                    </div>
                </div>
            </div>
        <?php endif ?>

        <!-- 8. Conversion Call-To-Action Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-blue-600 p-8 sm:p-12 lg:p-14 text-white shadow-xl flex flex-col sm:flex-row items-center justify-between gap-8">
            <div class="space-y-3 max-w-xl text-center sm:text-left">
                <h3 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight m-0">
                    Ready to Transform Your Website's Search Performance?
                </h3>
                <p class="text-sm sm:text-base text-blue-100 m-0 leading-relaxed font-normal">
                    Join thousands of web professionals and agencies using SiteRankify to spot critical errors before they impact traffic.
                </p>
            </div>
            <div class="flex flex-wrap items-center justify-center sm:justify-end gap-4 shrink-0">
                <a href="<?= url() ?>" class="px-7 py-3.5 rounded-xl bg-white text-blue-700 hover:bg-blue-50 font-bold text-sm shadow-sm transition-all text-decoration-none">
                    Run Free Audit Now
                </a>
                <a href="<?= url('plan') ?>" class="px-7 py-3.5 rounded-xl bg-blue-700 hover:bg-blue-800 text-white font-bold text-sm border border-blue-500 transition-all text-decoration-none">
                    View Upgrade Plans
                </a>
            </div>
        </div>

    <?php else: ?>
        <!-- Generic / Dynamic Custom Page Template -->
        <div class="bg-white rounded-3xl border border-slate-200 p-8 sm:p-12 shadow-xs space-y-6">
            <div class="pb-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Site Information</span>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight m-0 mt-1"><?= $data->page->title ?></h1>
                </div>
                <div class="text-xs text-slate-400 font-medium">
                    Last updated <?= \Altum\Date::get($data->page->last_datetime ?? $data->page->datetime, 1) ?>
                </div>
            </div>

            <?php if(!empty($data->page->image)): ?>
                <div class="rounded-2xl overflow-hidden shadow-xs border border-slate-100">
                    <img src="<?= \Altum\Uploads::get_full_url('pages') . $data->page->image ?>" class="w-full h-auto max-h-[400px] object-cover" alt="<?= e($data->page->title) ?>" />
                </div>
            <?php endif ?>

            <div class="prose max-w-none text-slate-700 leading-relaxed text-sm sm:text-base space-y-4">
                <div id="page_content" class="<?= $data->page->editor == 'wysiwyg' ? 'ql-content' : null ?>">
                    <?= $data->page->content ?>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>
