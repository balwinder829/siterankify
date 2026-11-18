<?php defined('ALTUMCODE') || die() ?>

<!-- ============================================================
     HERO SECTION (Clean Pure White Surface with Ambient Dust Glow)
     ============================================================ -->
<section class="hero-main-section relative bg-white border-b border-slate-200 overflow-hidden flex items-center">
    <div class="w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 py-12 sm:py-16 lg:py-20">
        
        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-[1.12] mb-4 max-w-4xl mx-auto">
            SEO Audits &amp; Tools
        </h1>

        <p class="text-base sm:text-lg lg:text-xl text-slate-600 font-normal max-w-2xl mx-auto mb-8 sm:mb-10 leading-relaxed">
            Get your webpages audited professionally and improve your search rankings, speed, and technical health in seconds.
        </p>

        <!-- Main Audit Form Card with enhanced width, height, and premium elevation -->
        <div class="max-w-3xl mx-auto relative">
            
            <div class="absolute -inset-2 rounded-3xl opacity-25 blur-2xl pointer-events-none -z-10"
                 style="background: radial-gradient(circle at 20% 30%, #1084cd 0%, transparent 60%), radial-gradient(circle at 80% 70%, #14b8a6 0%, transparent 60%);">
            </div>

            <div class="bg-white p-2.5 sm:p-3 rounded-2xl border border-slate-200 shadow-xl shadow-slate-200/60 relative z-10">
                <form action="<?= is_logged_in() ? url('dashboard') : url('register') ?>" method="get" id="index_audit_form" class="relative">
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center bg-slate-50 hover:bg-slate-100/70 focus-within:bg-white border border-slate-200 rounded-xl p-2 sm:p-2.5 gap-2.5 transition-all">
                        
                        <!-- Input Box with clean globe icon and larger text -->
                        <div class="flex items-center flex-1 min-w-0 px-3 py-1.5">
                            <div class="flex items-center gap-2 text-slate-400 shrink-0 select-none mr-2.5">
                                <i class="fas fa-globe text-slate-400 text-base sm:text-lg"></i>
                                <span class="text-sm font-semibold text-slate-400 hidden xs:inline">https://</span>
                            </div>
                            <input type="url" name="url" id="index_url_input"
                                class="w-full bg-transparent text-slate-900 text-base sm:text-lg font-medium border-0 focus:outline-none focus:ring-0 p-0 m-0 placeholder:text-slate-400"
                                placeholder="example.com/" required="required" autocomplete="off" />
                        </div>

                        <!-- Submit Button with prominent size -->
                        <button type="submit"
                            class="h-12 sm:h-13 px-8 rounded-lg bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold text-sm sm:text-base transition-all whitespace-nowrap cursor-pointer inline-flex items-center justify-center gap-2.5 border-0 shrink-0 shadow-md shadow-blue-500/25 group">
                            <span>Start audit</span>
                            <i class="fas fa-arrow-right text-sm transition-transform group-hover:translate-x-1"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</section>

<!-- ============================================================
     3-STEP WORKFLOW & 40+ SEO TESTS (Unified Diagnostic Section)
     Seamless flow from 3-step audit process directly into live test coverage
     ============================================================ -->
<section class="bg-slate-50 border-b border-slate-200 !py-10 sm:!py-14">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- 3 Process Steps with spacious cards, bold step badges, and clear typography -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-6 text-left mb-10 sm:mb-12">
            <!-- Step 1 -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-7 flex flex-col justify-between hover:border-slate-300 hover:shadow-md transition-all shadow-xs">
                <div>
                    <div class="h-9 w-9 rounded-xl bg-blue-600 text-white font-extrabold text-sm flex items-center justify-center mb-4 shadow-sm shadow-blue-500/20">
                        1
                    </div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 mb-2 leading-snug">Paste your URL.</h2>
                    <p class="text-xs sm:text-sm text-slate-600 m-0 leading-relaxed font-normal">Single page, domain, sitemap, or raw HTML code.</p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-7 flex flex-col justify-between hover:border-slate-300 hover:shadow-md transition-all shadow-xs">
                <div>
                    <div class="h-9 w-9 rounded-xl bg-blue-600 text-white font-extrabold text-sm flex items-center justify-center mb-4 shadow-sm shadow-blue-500/20">
                        2
                    </div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 mb-2 leading-snug">Run the audit.</h2>
                    <p class="text-xs sm:text-sm text-slate-600 m-0 leading-relaxed font-normal">Get effective actionable insights within seconds.</p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-7 flex flex-col justify-between hover:border-slate-300 hover:shadow-md transition-all shadow-xs">
                <div>
                    <div class="h-9 w-9 rounded-xl bg-blue-600 text-white font-extrabold text-sm flex items-center justify-center mb-4 shadow-sm shadow-blue-500/20">
                        3
                    </div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 mb-2 leading-snug">Fix issues &amp; improve.</h2>
                    <p class="text-xs sm:text-sm text-slate-600 m-0 leading-relaxed font-normal">Get clear tips to improve your rankings and speed.</p>
                </div>
            </div>
        </div>

        <!-- 40+ SEO Tests Integrated Strip with Generous Spacing and Larger Badges -->
        <div class="pt-8 border-t border-slate-200 text-center">
            <div class="max-w-3xl mx-auto mb-6">
                <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight leading-snug inline-flex items-center gap-2 justify-center">
                    <span>Over 40+ Parameters Tested Instantly</span>
                    <i class="fas fa-bolt text-blue-600 text-sm"></i>
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 mt-1.5 leading-relaxed">
                    Comprehensive on-page, speed, mobile, security, and technical checks run on every audit.
                </p>
            </div>

            <?php
            $seo_tests = [
                ['icon' => 'fas fa-heading', 'title' => 'Title tag'],
                ['icon' => 'fas fa-align-left', 'title' => 'Meta description'],
                ['icon' => 'fas fa-heading', 'title' => 'H1 tag'],
                ['icon' => 'fas fa-tags', 'title' => 'Meta keywords'],
                ['icon' => 'fas fa-robot', 'title' => 'robots.txt page'],
                ['icon' => 'fas fa-sitemap', 'title' => 'sitemap.xml'],
                ['icon' => 'fas fa-link', 'title' => 'Canonical URL'],
                ['icon' => 'fas fa-lock', 'title' => 'Valid SSL Certificate'],
                ['icon' => 'fas fa-shield-alt', 'title' => 'HSTS Security'],
                ['icon' => 'fas fa-stopwatch', 'title' => 'Response time (TTFB)'],
                ['icon' => 'fas fa-weight-hanging', 'title' => 'Page size'],
                ['icon' => 'fas fa-mobile-alt', 'title' => 'Meta viewport'],
                ['icon' => 'fas fa-images', 'title' => 'Image alt attributes'],
                ['icon' => 'fas fa-compress-alt', 'title' => 'GZIP / Brotli compression'],
                ['icon' => 'fas fa-network-wired', 'title' => 'HTTP/2 protocol'],
                ['icon' => 'fas fa-percentage', 'title' => 'Text to HTML ratio'],
                ['icon' => 'fas fa-eye-slash', 'title' => 'Noindex directive'],
                ['icon' => 'fas fa-share-nodes', 'title' => 'OpenGraph tags'],
            ];
            ?>

            <div class="flex flex-wrap items-center justify-center gap-2.5 sm:gap-3 max-w-5xl mx-auto">
                <?php foreach($seo_tests as $test): ?>
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white border border-slate-200 text-slate-800 text-xs sm:text-sm font-semibold shadow-xs hover:border-slate-300 hover:text-blue-600 transition-colors">
                        <i class="<?= $test['icon'] ?> text-blue-600 text-xs"></i>
                        <span><?= $test['title'] ?></span>
                    </div>
                <?php endforeach ?>
            </div>
        </div>

    </div>
</section>

<!-- ============================================================
     ADDITIONAL TOOLS (Pure White Surface: #ffffff)
     ============================================================ -->
<section class="bg-white border-b border-slate-200 !py-12 sm:!py-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-8 sm:mb-10">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-snug inline-flex items-center gap-2.5 justify-center">
                <span>Free Web &amp; SEO Tools</span>
                <i class="fas fa-screwdriver-wrench text-blue-600 text-lg"></i>
            </h2>
            <p class="text-sm sm:text-base text-slate-600 mt-2 leading-relaxed">
                Fast, reliable, and easy-to-use lookup and network diagnostic utilities.
            </p>
        </div>

        <?php
        $tools_sample = [
            ['icon' => 'fas fa-network-wired', 'name' => 'DNS Lookup', 'desc' => 'Find A, AAAA, CNAME, MX, NS, TXT, and SOA DNS records.'],
            ['icon' => 'fas fa-search-location', 'name' => 'IP Lookup', 'desc' => 'Get approximate geographical IP details and ASN info.'],
            ['icon' => 'fas fa-lock', 'name' => 'SSL Certificate Lookup', 'desc' => 'Get all details and expiration dates of any SSL certificate.'],
            ['icon' => 'fas fa-fingerprint', 'name' => 'Whois Domain Lookup', 'desc' => 'Check domain registrar, owner, and nameserver details.'],
            ['icon' => 'fas fa-server', 'name' => 'Ping Status Checker', 'desc' => 'Verify website uptime, latency, and online response status.'],
            ['icon' => 'fas fa-stopwatch', 'name' => 'TTFB Checker', 'desc' => 'Verify time to first byte and backend server responsiveness.'],
            ['icon' => 'fas fa-weight-hanging', 'name' => 'Page Size Checker', 'desc' => 'Measure total page payload weight including all resources.'],
            ['icon' => 'fas fa-tags', 'name' => 'Meta Tags Checker', 'desc' => 'Inspect and verify title, description, and social meta tags.'],
            ['icon' => 'fas fa-satellite', 'name' => 'HTTP/2 Checker', 'desc' => 'Check whether a web server supports the modern HTTP/2 protocol.'],
        ];
        ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 text-left">
            <?php foreach($tools_sample as $tool): ?>
                <a href="<?= url('tools') ?>" class="sr-tool-card bg-slate-50 hover:bg-white border border-slate-200/90 hover:border-blue-400 rounded-2xl p-5 sm:p-5 flex items-start gap-4 transition-all duration-300 text-decoration-none group shadow-xs hover:shadow-md hover:-translate-y-1">
                    <div class="h-11 w-11 rounded-xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white border border-blue-100 flex items-center justify-center text-base shrink-0 transition-all duration-300">
                        <i class="<?= $tool['icon'] ?>"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between gap-1 mb-1">
                            <h3 class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-blue-600 transition-colors leading-snug m-0"><?= $tool['name'] ?></h3>
                            <i class="fas fa-arrow-right text-[10px] text-slate-300 group-hover:text-blue-600 group-hover:translate-x-1 transition-all duration-300"></i>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-600 m-0 leading-relaxed font-normal line-clamp-2"><?= $tool['desc'] ?></p>
                    </div>
                </a>
            <?php endforeach ?>
        </div>

        <div class="text-center mt-8 sm:mt-10">
            <a href="<?= url('tools') ?>" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-slate-100 hover:bg-blue-600 text-slate-800 hover:text-white font-bold text-xs sm:text-sm text-decoration-none transition-all shadow-xs hover:shadow-md hover:-translate-y-0.5">
                <span>View all available tools</span>
                <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>

    </div>
</section>

<!-- ============================================================
     PRICING PLANS (Soft Slate Gray Surface: #f8fafc)
     ============================================================ -->
<?php if(settings()->main->display_index_plans): ?>
<section id="plans" class="bg-slate-50 border-b border-slate-200 !py-6 sm:!py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-4">
            <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight leading-snug">Transparent Plans &amp; Pricing</h2>
            <p class="text-xs text-slate-500 mt-0.5 leading-normal">
                Choose the plan that suits your web analysis and auditing needs.
            </p>
        </div>
        <?= $this->views['plans'] ?>
    </div>
</section>
<?php endif ?>

<!-- ============================================================
     TESTIMONIALS (Subtle Warm Tint Surface: #fafaf9)
     ============================================================ -->
<?php if(settings()->main->display_index_testimonials && !empty($data->testimonials)): ?>
<?php
$testimonials_list = $data->testimonials;
$double_testimonials = array_merge($testimonials_list, $testimonials_list);
?>
<style>
@keyframes marqueeScroll {
    0%   { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
.marquee-track {
    display: flex;
    animation: marqueeScroll 35s linear infinite;
    width: max-content;
}
.marquee-track:hover { animation-play-state: paused; }
</style>
<section class="bg-stone-50 border-b border-stone-200 !py-12 sm:!py-16 overflow-hidden">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-8 sm:mb-12">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-snug">Loved by Webmasters &amp; Marketers</h2>
            <p class="text-sm sm:text-base text-slate-600 mt-2 leading-relaxed">
                See what users say about auditing and maintaining their search performance with SiteRankify.
            </p>
        </div>
    </div>

    <div class="overflow-hidden relative w-full px-0 py-3">
        <div class="marquee-track gap-5 sm:gap-6 px-4">
            <?php foreach($double_testimonials as $testimonial): ?>
                <div class="sr-tilt-card w-[310px] sm:w-[360px] min-h-[220px] shrink-0 bg-white border border-stone-200/90 rounded-2xl p-6 text-left flex flex-col justify-between shadow-xs hover:shadow-xl transition-all duration-300">
                    <div>
                        <div class="flex items-center gap-1 text-amber-400 text-sm mb-3">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-700 leading-relaxed font-medium m-0 line-clamp-4">
                            "<?= e($testimonial['text']) ?>"
                        </p>
                    </div>
                    <div class="flex items-center gap-3 pt-4 mt-4 border-t border-stone-100">
                        <img src="<?= e($testimonial['image']) ?>" class="h-10 w-10 rounded-full object-cover ring-2 ring-blue-100 shrink-0" alt="<?= e($testimonial['name']) ?>" loading="lazy" />
                        <div class="min-w-0 flex-1">
                            <h4 class="text-xs sm:text-sm font-bold text-slate-900 m-0 truncate"><?= e($testimonial['name']) ?></h4>
                            <span class="text-[11px] sm:text-xs text-slate-500 block truncate font-medium mt-0.5"><?= e($testimonial['attribute']) ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</section>
<?php endif ?>

<!-- ============================================================
     FREQUENTLY ASKED QUESTIONS (Pure White Surface: #ffffff)
     ============================================================ -->
<?php if(settings()->main->display_index_faq): ?>
<section class="bg-white border-b border-slate-200 !py-6 sm:!py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-xl mx-auto mb-4">
            <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight leading-snug">Answers to Common Questions</h2>
            <p class="text-xs text-slate-500 mt-0.5 leading-normal">
                Everything you need to know about website analysis, technical SEO, and performance health.
            </p>
        </div>

        <?php
        $faqs = [
            [
                'question' => '1. What is website analysis?',
                'answer' => 'Website analysis helps you understand how well your website is performing in terms of SEO, speed, technical health, and user experience. SiteRankify makes website analysis simple by identifying technical obstacles and prioritizing actionable fixes.'
            ],
            [
                'question' => '2. How can I check website performance online?',
                'answer' => 'Simply enter your domain into our audit bar above. In seconds, our crawler runs over 40+ diagnostics to inspect response times, Core Web Vitals, HTML errors, SSL status, and mobile friendliness without needing advanced technical knowledge.'
            ],
            [
                'question' => '3. Why is website performance important?',
                'answer' => 'A fast and technically optimized website improves user experience, lowers bounce rates, and boosts search engine visibility. Google directly uses page experience signals and Core Web Vitals (LCP, CLS, INP) as organic ranking criteria.'
            ],
            [
                'question' => '4. What is a site checkup?',
                'answer' => 'A site checkup is a complete 360-degree review of your website health. It checks meta title lengths, header hierarchies, image alt tags, canonical URLs, robots.txt, sitemaps, server compression, and SSL certificate validity.'
            ],
            [
                'question' => '5. How often should I perform a website analysis?',
                'answer' => 'We recommend checking your website regularly, especially after publishing major content, updating plugins, or deploying code changes. Regular audits help spot regressions before they hurt search rankings.'
            ],
            [
                'question' => '6. Can website analysis improve SEO rankings?',
                'answer' => 'Yes. By fixing critical technical SEO issues like broken internal links, slow server response times, duplicate meta tags, and missing canonical tags, search bots can crawl and index your pages much more efficiently.'
            ],
            [
                'question' => '7. What problems can this tool detect?',
                'answer' => 'Our engine detects missing title tags, excessive DOM depth, uncompressed assets, missing HSTS headers, mixed HTTP/HTTPS content, noindex tags, invalid sitemaps, slow TTFB, and lack of OpenGraph social tags.'
            ],
            [
                'question' => '8. What is technical SEO analysis?',
                'answer' => 'Technical SEO analysis audits your web pages for crawlability, indexation readiness, page structure, and security. It ensures search engine crawlers can access and index your content without encountering errors or delays.'
            ],
            [
                'question' => '9. Can I export White-Label PDF reports for clients?',
                'answer' => 'Yes! You can download comprehensive white-label PDF audit reports branded with your agency name and logo, ready to present directly to clients and stakeholders.'
            ],
            [
                'question' => '10. Can I analyze competitor websites?',
                'answer' => 'Yes. You can audit any publicly accessible website URL to benchmark competitor loading times, SEO structures, meta tag implementations, and technical setups.'
            ],
        ];
        ?>

        <div class="space-y-2.5" id="faq_accordion">
            <?php foreach($faqs as $i => $faq): ?>
                <div class="border border-slate-200 rounded-xl bg-slate-50/70 overflow-hidden transition-all duration-150 faq-item">
                    <button class="w-full py-3.5 px-4 sm:px-5 text-left flex items-center justify-between gap-4 focus:outline-none cursor-pointer faq-toggle-btn bg-slate-50/70 hover:bg-slate-100/80 border-0 transition-colors"
                        type="button" data-index="<?= $i ?>" aria-expanded="<?= $i === 0 ? 'true' : 'false' ?>">
                        <span class="text-sm font-bold text-slate-900 leading-snug"><?= $faq['question'] ?></span>
                        <i class="fas fa-chevron-down text-xs text-slate-500 transition-transform duration-200 shrink-0 <?= $i === 0 ? 'rotate-180 text-blue-600' : '' ?>" id="faq_icon_<?= $i ?>"></i>
                    </button>
                    <div id="faq_answer_<?= $i ?>" class="<?= $i === 0 ? '' : 'hidden' ?> px-4 sm:px-5 pb-4 pt-1 border-t border-slate-200 bg-white">
                        <p class="text-sm text-slate-600 leading-relaxed m-0 font-normal"><?= $faq['answer'] ?></p>
                    </div>
                </div>
            <?php endforeach ?>
        </div>

    </div>
</section>
<?php endif ?>

<!-- ============================================================
     BLOG / GUIDES (Cool Indigo Tint Surface: #f0f7ff)
     ============================================================ -->
<?php if(!empty($data->blog_posts)): ?>
<section class="bg-blue-50/40 border-b border-blue-100/80 !py-12 sm:!py-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-8 sm:mb-10">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-snug">Latest SEO Guides &amp; Insights</h2>
            <p class="text-sm sm:text-base text-slate-600 mt-2 leading-relaxed">
                Actionable optimization tutorials and technical tips.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 text-left">
            <?php foreach($data->blog_posts as $idx => $blog_post): ?>
                <?php 
                $post_url = SITE_URL . ($blog_post->language ? \Altum\Language::$active_languages[$blog_post->language] . '/' : null) . 'blog/' . $blog_post->url;
                $shadow_classes = [
                    'hover:shadow-blue-500/20 hover:border-blue-400',
                    'hover:shadow-teal-500/20 hover:border-teal-400',
                    'hover:shadow-indigo-500/20 hover:border-indigo-400',
                ];
                $hover_shadow = $shadow_classes[$idx % count($shadow_classes)];
                ?>
                <article class="sr-blog-card bg-white border border-slate-200/90 rounded-2xl overflow-hidden <?= $hover_shadow ?> transition-all duration-300 flex flex-col justify-between shadow-xs hover:shadow-xl hover:-translate-y-1 group">
                    <div>
                        <?php if($blog_post->image): ?>
                            <a href="<?= $post_url ?>" class="block overflow-hidden bg-slate-100 aspect-16/9 relative text-decoration-none group">
                                <img src="<?= \Altum\Uploads::get_full_url('blog') . $blog_post->image ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="<?= $blog_post->image_description ?>" loading="lazy" />
                            </a>
                        <?php else: ?>
                            <div class="h-1.5 bg-blue-600 w-full"></div>
                        <?php endif ?>
                        <div class="p-5 sm:p-6 pb-4">
                            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-2.5">
                                <i class="fas fa-calendar-day text-blue-600 text-xs"></i>
                                <span><?= \Altum\Date::get($blog_post->datetime, 2) ?></span>
                            </div>
                            <h3 class="text-base sm:text-lg font-bold text-slate-900 line-clamp-2 leading-snug mb-2">
                                <a href="<?= $post_url ?>" class="text-decoration-none text-slate-900 group-hover:text-blue-600 transition-colors">
                                    <?= $blog_post->title ?>
                                </a>
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed line-clamp-3 font-normal m-0">
                                <?= $blog_post->description ?>
                            </p>
                        </div>
                    </div>
                    <div class="px-5 sm:px-6 pb-5 pt-0">
                        <a href="<?= $post_url ?>" class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors text-decoration-none">
                            <span>Read Article</span>
                            <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </article>
            <?php endforeach ?>
        </div>

    </div>
</section>
<?php endif ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.faq-toggle-btn').forEach(button => {
        button.addEventListener('click', () => {
            const index = button.getAttribute('data-index');
            const targetAnswer = document.getElementById(`faq_answer_${index}`);
            const targetIcon   = document.getElementById(`faq_icon_${index}`);
            if(!targetAnswer || !targetIcon) return;

            const isCurrentlyOpen = !targetAnswer.classList.contains('hidden');

            // Close all items first (mutual exclusion)
            document.querySelectorAll('[id^="faq_answer_"]').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('[id^="faq_icon_"]').forEach(el => {
                el.classList.remove('rotate-180', 'text-blue-600');
                el.classList.add('text-slate-500');
            });
            document.querySelectorAll('.faq-toggle-btn').forEach(el => {
                el.setAttribute('aria-expanded', 'false');
            });

            // If it was closed, open it now
            if(!isCurrentlyOpen) {
                targetAnswer.classList.remove('hidden');
                targetIcon.classList.add('rotate-180', 'text-blue-600');
                targetIcon.classList.remove('text-slate-500');
                button.setAttribute('aria-expanded', 'true');
            }
        });
    });
});
</script>
