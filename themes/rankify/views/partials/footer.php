<?php defined('ALTUMCODE') || die() ?>

<footer class="mt-0 border-t border-slate-200/80 bg-gradient-to-b from-white to-slate-50/70 font-sans text-slate-600">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <!-- Top Footer Grid -->
        <div class="pt-8 pb-6 sm:pt-9 sm:pb-7">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-12 lg:gap-6">

                <!-- Col 1: Brand, Tagline, Uptime & Socials -->
                <div class="sm:col-span-2 lg:col-span-4 flex flex-col justify-start pr-0 lg:pr-6">
                    <a href="<?= url() ?>" class="inline-flex items-center no-underline focus:outline-none mb-3 group" data-logo>
                        <?= include_view(THEME_PATH . 'views/partials/logo.php', ['size' => 'md']) ?>
                    </a>

                    <p class="text-xs leading-relaxed text-slate-500 font-normal m-0 mb-4 max-w-sm">
                        High-throughput automated technical SEO diagnostics, Core Web Vitals profiling, and white-label client PDF audit reporting.
                    </p>

                    <!-- Configured Social Icons -->
                    <?php
                    $social_items = [];
                    if(!empty(settings()->socials)) {
                        $admin_socials = require APP_PATH . 'includes/admin_socials.php';
                        foreach($admin_socials as $key => $social) {
                            if(!empty(settings()->socials->{$key})) {
                                $href = sprintf($social['format'], settings()->socials->{$key});
                                $social_items[] = [
                                    'name' => $social['name'],
                                    'icon' => $social['icon'],
                                    'url'  => $href
                                ];
                            }
                        }
                    }
                    ?>
                    <?php if(!empty($social_items)): ?>
                        <div class="flex items-center gap-2 pt-0.5">
                            <?php foreach($social_items as $social_btn): ?>
                                <a href="<?= $social_btn['url'] ?>" target="_blank" rel="noopener noreferrer" title="<?= $social_btn['name'] ?>" class="w-7 h-7 rounded-lg bg-slate-100/90 border border-slate-200/80 flex items-center justify-center text-slate-500 hover:text-blue-600 hover:bg-blue-50 hover:border-blue-200 transition-all text-xs no-underline">
                                    <i class="<?= $social_btn['icon'] ?>"></i>
                                </a>
                            <?php endforeach ?>
                        </div>
                    <?php endif ?>
                </div>

                <!-- Col 2: Platform Links -->
                <div class="lg:col-span-2 sm:col-span-1 flex flex-col">
                    <h3 class="text-[11px] font-extrabold uppercase tracking-wider text-slate-900 m-0 mb-3 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                        Platform
                    </h3>
                    <ul class="space-y-2 p-0 m-0 list-none text-xs">
                        <li>
                            <a href="<?= url() ?>" class="font-medium text-slate-500 no-underline transition-colors hover:text-blue-600 hover:translate-x-0.5 inline-block leading-snug">
                                SEO Audit Engine
                            </a>
                        </li>
                        <?php if(settings()->tools->is_enabled): ?>
                            <li>
                                <a href="<?= url('tools') ?>" class="inline-flex items-center gap-1.5 font-medium text-slate-500 no-underline transition-colors hover:text-blue-600 hover:translate-x-0.5 leading-snug">
                                    <span>Technical Utilities</span>
                                    <span class="rounded bg-blue-50 px-1 py-0.5 text-[8px] font-bold text-blue-600 border border-blue-200/60 leading-none">
                                        FREE
                                    </span>
                                </a>
                            </li>
                        <?php endif ?>
                        <?php if(settings()->payment->is_enabled): ?>
                            <li>
                                <a href="<?= url('plan') ?>" class="font-medium text-slate-500 no-underline transition-colors hover:text-blue-600 hover:translate-x-0.5 inline-block leading-snug">
                                    Pricing Plans
                                </a>
                            </li>
                        <?php endif ?>
                        <?php if(is_logged_in()): ?>
                            <li>
                                <a href="<?= url('dashboard') ?>" class="font-medium text-slate-500 no-underline transition-colors hover:text-blue-600 hover:translate-x-0.5 inline-block leading-snug">
                                    Dashboard
                                </a>
                            </li>
                        <?php else: ?>
                            <li>
                                <a href="<?= url('register') ?>" class="font-medium text-slate-500 no-underline transition-colors hover:text-blue-600 hover:translate-x-0.5 inline-block leading-snug">
                                    Create Free Account
                                </a>
                            </li>
                        <?php endif ?>
                    </ul>
                </div>

                <!-- Col 3: Diagnostic Tools -->
                <div class="lg:col-span-3 sm:col-span-1 flex flex-col">
                    <h3 class="text-[11px] font-extrabold uppercase tracking-wider text-slate-900 m-0 mb-3 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                        Diagnostic Tools
                    </h3>
                    <ul class="space-y-2 p-0 m-0 list-none text-xs">
                        <li>
                            <a href="<?= url('tools/dns-lookup') ?>" class="font-medium text-slate-500 no-underline transition-colors hover:text-blue-600 hover:translate-x-0.5 inline-block leading-snug">
                                DNS Records Lookup
                            </a>
                        </li>
                        <li>
                            <a href="<?= url('tools/ssl-lookup') ?>" class="font-medium text-slate-500 no-underline transition-colors hover:text-blue-600 hover:translate-x-0.5 inline-block leading-snug">
                                SSL Certificate Checker
                            </a>
                        </li>
                        <li>
                            <a href="<?= url('tools/ping') ?>" class="font-medium text-slate-500 no-underline transition-colors hover:text-blue-600 hover:translate-x-0.5 inline-block leading-snug">
                                Server Ping Test
                            </a>
                        </li>
                        <li>
                            <a href="<?= url('tools/whois-lookup') ?>" class="font-medium text-slate-500 no-underline transition-colors hover:text-blue-600 hover:translate-x-0.5 inline-block leading-snug">
                                WHOIS Domain Lookup
                            </a>
                        </li>
                        <li>
                            <a href="<?= url('tools/reverse-ip-lookup') ?>" class="font-medium text-slate-500 no-underline transition-colors hover:text-blue-600 hover:translate-x-0.5 inline-block leading-snug">
                                Reverse IP Lookup
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Col 4: Resources & Trust Box -->
                <div class="lg:col-span-3 sm:col-span-2 flex flex-col justify-between">
                    <div>
                        <h3 class="text-[11px] font-extrabold uppercase tracking-wider text-slate-900 m-0 mb-3 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                            Resources &amp; Support
                        </h3>
                        <ul class="space-y-2 p-0 m-0 list-none text-xs">
                            <?php if(settings()->content->blog_is_enabled): ?>
                                <li>
                                    <a href="<?= url('blog') ?>" class="font-medium text-slate-500 no-underline transition-colors hover:text-blue-600 hover:translate-x-0.5 inline-block leading-snug">
                                        Knowledge Base &amp; Blog
                                    </a>
                                </li>
                            <?php endif ?>
                            <?php if(settings()->email_notifications->contact && !empty(settings()->email_notifications->emails)): ?>
                                <li>
                                    <a href="<?= url('contact') ?>" class="font-medium text-slate-500 no-underline transition-colors hover:text-blue-600 hover:translate-x-0.5 inline-block leading-snug">
                                        Contact &amp; Support
                                    </a>
                                </li>
                            <?php endif ?>
                            <?php if (!empty($data->pages)): ?>
                                <?php foreach($data->pages as $row): ?>
                                    <li>
                                        <a href="<?= $row->url ?>" target="<?= $row->target ?>" class="font-medium text-slate-500 no-underline transition-colors hover:text-blue-600 hover:translate-x-0.5 inline-block leading-snug">
                                            <?= trim($row->title) ?>
                                        </a>
                                    </li>
                                <?php endforeach ?>
                            <?php endif ?>
                        </ul>
                    </div>

                </div>

            </div>
        </div>

        <!-- Bottom Bar: Clean, Balanced & Compact -->
        <div class="border-t border-slate-200/80 py-4 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
            
            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-x-2 gap-y-1">
                <span>&copy; <?= date('Y') ?> <span class="font-semibold text-slate-700"><?= settings()->main->title ?></span>. All rights reserved.</span>
                <span class="text-slate-300 hidden sm:inline">•</span>
                <span class="text-slate-400">Built by <strong class="font-semibold text-slate-700">Sortiq Solutions Pvt Ltd</strong></span>
            </div>

            <div class="flex items-center gap-3">
                <?php if(count(\Altum\Language::$active_languages) > 1): ?>
                    <div class="relative">
                        <button
                            type="button"
                            class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-slate-600 shadow-2xs transition-colors hover:bg-slate-50 hover:text-slate-900 focus:outline-none"
                            onclick="this.nextElementSibling.classList.toggle('hidden'); event.stopPropagation();"
                        >
                            <i class="fas fa-globe text-blue-600 text-[10px]"></i>
                            <span><?= \Altum\Language::$name ?></span>
                            <i class="fas fa-chevron-up text-[7px] text-slate-400"></i>
                        </button>

                        <div class="absolute bottom-full right-0 sm:right-auto sm:left-1/2 sm:-translate-x-1/2 z-50 mb-1.5 hidden w-36 overflow-hidden rounded-xl border border-slate-200 bg-white p-1 shadow-lg text-left">
                            <?php foreach(\Altum\Language::$languages_ordered as $language): ?>
                                <?php if($language['status']): ?>
                                    <?php
                                    $new_url = match(\Altum\Router::$controller_key) {
                                        'pages', 'page' => SITE_URL . $language['code'] . '/' . 'pages',
                                        'blog' => SITE_URL . $language['code'] . '/' . 'blog',
                                        default => SITE_URL . $language['code'] . '/' . \Altum\Router::$original_request . (\Altum\Router::$original_request_query ? '?' . \Altum\Router::$original_request_query : null)
                                    };
                                    ?>
                                    <a
                                        href="<?= $new_url ?>"
                                        class="block rounded-lg px-2.5 py-1.5 text-[11px] font-semibold text-slate-700 no-underline transition-colors hover:bg-slate-50 hover:text-blue-600"
                                        onclick="set_cookie('set_language', '<?= $language['name'] ?>', 90, '<?= COOKIE_PATH ?>');"
                                    >
                                        <?= $language['name'] ?>
                                    </a>
                                <?php endif ?>
                            <?php endforeach ?>
                        </div>
                    </div>
                <?php endif ?>
            </div>

        </div>

    </div>
</footer>

<script>
    document.addEventListener('click', function(event) {
        document.querySelectorAll('footer .absolute').forEach(function(menu) {
            if (!menu.parentElement.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });
    });
</script>
