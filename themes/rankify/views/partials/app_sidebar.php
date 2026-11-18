<?php defined('ALTUMCODE') || die() ?>

<div class="app-sidebar border-r border-slate-200/80 bg-white flex flex-col h-full font-sans">
    <div class="overflow-y-auto flex-1 p-3 space-y-4 scrollbar-thin">
        <div class="px-2 pt-1 pb-2 border-b border-slate-100 mb-2 flex items-center justify-between">
            <a href="<?= url() ?>" class="group inline-flex items-center text-decoration-none" data-logo>
                <?= include_view(THEME_PATH . 'views/partials/logo.php', ['size' => 'md']) ?>
            </a>
            <button type="button" id="app_sidebar_close" data-toggle-sidebar class="lg:hidden h-8 w-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center cursor-pointer border-0 transition-colors" onclick="if(window.toggle_app_sidebar) window.toggle_app_sidebar();" aria-label="Close sidebar">
                <i class="fas fa-times text-xs pointer-events-none"></i>
            </button>
        </div>
        <?php if(is_logged_in()): ?>
            <?php 
            $plan_name = \Altum\Authentication::is_admin() ? 'Administrator' : ($this->user->plan->translations->{\Altum\Language::$name}->name ?? $this->user->plan->name);
            $plan_type = '';
            if(\Altum\Authentication::is_admin()) {
                $plan_type = 'Owner';
            } else if($this->user->plan_id == 'free') {
                $plan_type = 'Free';
            } else {
                if($this->user->plan_id == 1) {
                    $plan_type = 'One Time';
                } else if($this->user->plan_id == 2) {
                    $plan_type = 'Monthly';
                } else if($this->user->plan_id == 3) {
                    $plan_type = 'Yearly';
                } else if(!empty($this->user->payment_frequency)) {
                    $plan_type = l('plan.custom_plan.' . $this->user->payment_frequency);
                } else {
                    $plan_type = 'Lifetime';
                }
            }

            $icon = $this->user->plan_id == 'free' ? 'fa-box-open' : 'fa-crown';
            ?>
            <!-- Plan & Subscription Status Card -->
            <div class="p-3.5 rounded-2xl bg-slate-900 text-white shadow-xs border border-slate-800 relative">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-xl bg-blue-900/40 text-blue-400 flex items-center justify-center shrink-0 font-bold border border-blue-700/50">
                        <i class="fas <?= $icon ?> text-xs"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between gap-1">

                            <span class="text-xs font-extrabold uppercase tracking-wider text-slate-100 truncate"><?= $plan_name ?></span>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-400 bg-blue-500/10 px-2 py-0.5 rounded-md border border-blue-500/20"><?= $plan_type ?></span>
                        </div>
                        <?php if($this->user->plan_id != 'free'): ?>
                            <?php 
                            $exp_date = new \DateTime($this->user->plan_expiration_date);
                            $now = new \DateTime();
                            $is_lifetime = \Altum\Authentication::is_admin() || $exp_date >= (new \DateTime())->modify('+10 years');
                            $days_left_sub = null;
                            if (!$is_lifetime) {
                                $now_for_diff = new \DateTime();
                                $interval = $now_for_diff->diff($exp_date);
                                $days_left_sub = $interval->days;
                            }
                            ?>
                            <?php $tz_abbr = (new \DateTime('now', new \DateTimeZone(\Altum\Date::$timezone)))->format('T'); ?>
                            <div class="text-[11px] font-medium text-slate-300 mt-1 flex items-center gap-1.5" title="<?= \Altum\Date::get($this->user->plan_expiration_date, 1) . ' (' . $tz_abbr . ')' ?>">
                                <i class="fas fa-calendar-alt text-blue-400 text-[10px]"></i>
                                <span><?= $is_lifetime ? 'Lifetime Access' : 'Expires: ' . \Altum\Date::get($this->user->plan_expiration_date, 6) ?></span>
                            </div>
                            <?php if (!$is_lifetime): ?>
                                <div id="subscription-countdown" class="text-[11px] font-bold text-amber-400 mt-0.5 flex items-center gap-1.5" data-expiration="<?= (new \DateTime($this->user->plan_expiration_date))->getTimestamp() ?>">
                                    <i class="fas fa-hourglass-half text-[10px]"></i>
                                    <span id="countdown-text">Calculating...</span>
                                </div>
                                <script>
                                    document.addEventListener('DOMContentLoaded', () => {
                                        const countdownEl = document.getElementById('subscription-countdown');
                                        if (countdownEl) {
                                            const expTimestamp = parseInt(countdownEl.getAttribute('data-expiration')) * 1000;
                                            const textEl = document.getElementById('countdown-text');
                                            
                                            function updateTimer() {
                                                const now = new Date().getTime();
                                                const diff = expTimestamp - now;
                                                
                                                if (diff <= 0) {
                                                    textEl.textContent = 'Expired';
                                                    return;
                                                }
                                                
                                                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                                                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                                                const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                                                
                                                let timeString = '';
                                                if (days > 0) {
                                                    timeString += days + 'd ' + hours + 'h ' + minutes + 'm ' + seconds + 's left';
                                                } else if (hours > 0) {
                                                    timeString += hours + 'h ' + minutes + 'm ' + seconds + 's left';
                                                } else {
                                                    timeString += minutes + 'm ' + seconds + 's left';
                                                }
                                                
                                                textEl.textContent = timeString;
                                            }
                                            
                                            updateTimer();
                                            setInterval(updateTimer, 1000);
                                        }
                                    });
                                </script>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>
                <?php if(settings()->payment->is_enabled && !\Altum\Authentication::is_admin()): ?>
                    <a href="<?= url('plan') ?>" class="mt-3 block text-center py-1.5 px-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-sm transition-all text-decoration-none">
                        <i class="fas fa-crown text-amber-300 mr-1"></i> Upgrade Plan
                    </a>
                <?php endif ?>
            </div>

            <?php
            // Calculate PDF Download Credits
            $download_limit = 1;
            $extra_pdf_downloads = 0;
            if(\Altum\Authentication::is_admin()) {
                $base_download_limit = -1;
            } else {
                if(isset($this->user->plan_settings->pdf_downloads_limit)) {
                    $base_download_limit = $this->user->plan_settings->pdf_downloads_limit;
                } else if ($this->user->plan_id == 'free') {
                    $base_download_limit = 1;
                } else if ($this->user->plan_id == 1) {
                    $base_download_limit = 1;
                } else if ($this->user->plan_id == 2) {
                    $base_download_limit = 10;
                } else if ($this->user->plan_id == 3) {
                    $base_download_limit = 120;
                } else {
                    $base_download_limit = 1;
                }

                if($base_download_limit != -1) {
                    $extra_pdf_downloads = (int) ($this->user->extra_pdf_downloads ?? 0);
                }
            }
            $download_limit = $base_download_limit == -1 ? -1 : $base_download_limit + $extra_pdf_downloads;

            $download_count = 0;
            if($download_limit != -1) {
                $cycle_start = get_user_cycle_start($this->user);

                $track = db()
                    ->where('ip_md5', (string)$this->user->user_id)
                    ->where('type', 'report_download_account')
                    ->getOne('ip_tracking');

                if ($track) {
                    if ($cycle_start && $track->last_datetime < $cycle_start) {
                        $download_count = 0;
                    } else {
                        $download_count = $track->count;
                    }
                }
            }

            $download_remaining = $download_limit == -1 ? -1 : max(0, $download_limit - $download_count);
            $download_pct = $download_limit > 0 ? min(100, ($download_remaining / $download_limit) * 100) : 100;
            
            if ($download_limit == -1) {
                $download_display = 'Unlimited';
            } else {
                $download_display = $download_remaining . ' / ' . $download_limit;
            }

            // Calculate Audit Credits
            $audits_limit = $this->user->plan_settings->audits_per_month_limit ?? 0;
            if(\Altum\Authentication::is_admin()) {
                $audits_limit = -1;
            }
            $audits_used = get_user_audits_used($this->user);
            $audits_remaining = $audits_limit == -1 ? -1 : max(0, $audits_limit - $audits_used);
            $audits_pct = $audits_limit > 0 ? min(100, ($audits_remaining / $audits_limit) * 100) : 100;

            // Calculate Report View Credits
            $extra_report_views = 0;
            if(\Altum\Authentication::is_admin()) {
                $base_view_limit = -1;
            } else {
                if(isset($this->user->plan_settings->report_views_limit)) {
                    $base_view_limit = $this->user->plan_settings->report_views_limit;
                } else if ($this->user->plan_id == 'free') {
                    $base_view_limit = 15;
                } else if ($this->user->plan_id == 1) {
                    $base_view_limit = 50;
                } else if ($this->user->plan_id == 2) {
                    $base_view_limit = 100;
                } else if ($this->user->plan_id == 3) {
                    $base_view_limit = 120;
                } else {
                    $base_view_limit = 15;
                }

                if($base_view_limit != -1) {
                    $extra_report_views = (int) ($this->user->extra_report_views ?? 0);
                }
            }
            $view_limit = $base_view_limit == -1 ? -1 : $base_view_limit + $extra_report_views;

            $view_count = 0;
            if($view_limit != -1) {
                $cycle_start = get_user_cycle_start($this->user);

                $track_view = db()
                    ->where('ip_md5', (string)$this->user->user_id)
                    ->where('type', 'report_view_account')
                    ->getOne('ip_tracking');

                if ($track_view) {
                    if ($cycle_start && $track_view->last_datetime < $cycle_start) {
                        $view_count = 0;
                    } else {
                        $view_count = $track_view->count;
                    }
                }
            }

            $view_remaining = $view_limit == -1 ? -1 : max(0, $view_limit - $view_count);
            $view_pct = $view_limit > 0 ? min(100, ($view_remaining / $view_limit) * 100) : 100;
            
            if ($view_limit == -1) {
                $view_display = 'Unlimited';
            } else {
                if ($extra_report_views > 0) {
                    $remaining_base = max(0, $base_view_limit - $view_count);
                    $remaining_extra = max(0, $extra_report_views - max(0, $view_count - $base_view_limit));
                    $view_display = $remaining_base . ' + ' . $remaining_extra . ' / ' . $base_view_limit;
                } else {
                    $view_display = $view_remaining . ' / ' . $base_view_limit;
                }
            }
            /* Calculate Days Left for Reset */
            $cycle_start = get_user_cycle_start($this->user);
            $next_reset = (new \DateTime($cycle_start))->modify('+1 month');
            $now = new \DateTime();
            $interval = $now->diff($next_reset);
            $days_left = $interval->days;
            if ($next_reset < $now) {
                $days_left = 0;
            }
            if ($this->user->plan_id == 1 || $plan_type == 'Lifetime' || $plan_type == 'One Time') {
                $days_left_text = 'Lifetime';
            } else {
                $days_left_text = $days_left == 0 ? 'Resets today' : ($days_left == 1 ? 'Resets in 1 day' : 'Resets in ' . $days_left . ' days');
            }
            ?>

            <!-- Usage Credits Card -->
            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3 font-sans text-xs">
                <div class="flex items-center justify-between text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                    <span>Usage Credits</span>
                    <?php if($this->user->plan_id == 'free'): ?>
                        <span class="text-blue-600 font-semibold flex items-center gap-1"><i class="fas fa-clock"></i> <?= $days_left_text ?></span>
                    <?php endif ?>
                </div>

                <!-- Audits Credit -->
                <div class="space-y-1">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-600 font-medium flex items-center gap-1.5"><i class="fas fa-bolt text-amber-500"></i> Audits</span>
                        <span class="font-bold text-slate-900"><?= $audits_remaining == -1 ? 'Unlimited' : $audits_remaining . ' / ' . $audits_limit ?></span>
                    </div>
                    <div class="h-1.5 w-full bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full bg-amber-500 rounded-full transition-all duration-500" style="width: <?= $audits_pct ?>%"></div>
                    </div>
                </div>

                <!-- Report Views Credit -->
                <div class="space-y-1">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-600 font-medium flex items-center gap-1.5"><i class="fas fa-eye text-blue-500"></i> Views</span>
                        <span class="font-bold text-slate-900"><?= $view_display ?></span>
                    </div>
                    <div class="h-1.5 w-full bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500 rounded-full transition-all duration-500" style="width: <?= $view_pct ?>%"></div>
                    </div>
                </div>

                <!-- PDF Downloads Credit -->
                <div class="space-y-1">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-600 font-medium flex items-center gap-1.5"><i class="fas fa-file-pdf text-rose-500"></i> PDFs</span>
                        <span class="font-bold text-slate-900"><?= $download_display ?></span>
                    </div>
                    <div class="h-1.5 w-full bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full bg-rose-500 rounded-full transition-all duration-500" style="width: <?= $download_pct ?>%"></div>
                    </div>
                </div>
            </div>
        <?php endif ?>

        <!-- Sidebar Links -->
        <nav class="space-y-4">
            <div class="space-y-1">
                <div class="px-3.5 py-1 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Overview</div>
                <a href="<?= url('dashboard') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-xs transition-all text-decoration-none <?= \Altum\Router::$controller == 'Dashboard' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' ?>">
                    <i class="fas fa-th text-sm w-4 <?= \Altum\Router::$controller == 'Dashboard' ? 'text-white' : 'text-slate-400' ?>"></i>
                    <span><?= l('dashboard.menu') ?></span>
                </a>

                <div class="flex items-center gap-1">
                    <a href="<?= url('audits') ?>" class="flex-1 flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-xs transition-all text-decoration-none <?= in_array(\Altum\Router::$controller, ['Audits', 'Audit', 'AuditUpdate']) ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' ?>">
                        <i class="fas fa-bolt text-sm w-4 <?= in_array(\Altum\Router::$controller, ['Audits', 'Audit', 'AuditUpdate']) ? 'text-white' : 'text-amber-500' ?>"></i>
                        <span><?= l('audits.menu') ?></span>
                    </a>
                    <a href="<?= url('archived-audits') ?>" class="p-2.5 rounded-xl text-slate-400 hover:bg-slate-100 hover:text-slate-900 transition-all text-decoration-none" title="<?= l('archived_audits.menu') ?>">
                        <i class="fas fa-archive text-xs"></i>
                    </a>
                </div>

                <a href="<?= url('websites') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-xs transition-all text-decoration-none <?= in_array(\Altum\Router::$controller, ['Websites', 'Website', 'WebsiteUpdate']) ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' ?>">
                    <i class="fas fa-pager text-sm w-4 <?= in_array(\Altum\Router::$controller, ['Websites', 'Website', 'WebsiteUpdate']) ? 'text-white' : 'text-blue-500' ?>"></i>
                    <span><?= l('websites.menu') ?></span>
                </a>
            </div>

            <div class="space-y-1">
                <div class="px-3.5 py-1 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Management</div>
                <a href="<?= url('notification-handlers') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-xs transition-all text-decoration-none <?= in_array(\Altum\Router::$controller, ['NotificationHandlers', 'NotificationHandlerUpdate', 'NotificationHandlerCreate']) ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' ?>">
                    <i class="fas fa-bell text-sm w-4 <?= in_array(\Altum\Router::$controller, ['NotificationHandlers', 'NotificationHandlerUpdate', 'NotificationHandlerCreate']) ? 'text-white' : 'text-slate-400' ?>"></i>
                    <span><?= l('notification_handlers.menu') ?></span>
                </a>

                <?php if(settings()->payment->is_enabled): ?>
                    <a href="<?= url('account-payments') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-xs transition-all text-decoration-none <?= in_array(\Altum\Router::$controller, ['AccountPayments']) ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' ?>">
                        <i class="fas fa-file-invoice text-sm w-4 <?= in_array(\Altum\Router::$controller, ['AccountPayments']) ? 'text-white' : 'text-slate-400' ?>"></i>
                        <span>Invoices</span>
                    </a>
                <?php endif ?>

                <?php if(settings()->tools->is_enabled && (settings()->tools->access == 'everyone' || (settings()->tools->access == 'users' && is_logged_in()))): ?>
                    <a href="<?= url('tools') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-xs transition-all text-decoration-none <?= \Altum\Router::$controller == 'Tools' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' ?>">
                        <i class="fas fa-tools text-sm w-4 <?= \Altum\Router::$controller == 'Tools' ? 'text-white' : 'text-slate-400' ?>"></i>
                        <span><?= l('tools.menu') ?></span>
                    </a>
                <?php endif ?>

                <a href="<?= url('tickets') ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-xs transition-all text-decoration-none <?= in_array(\Altum\Router::$controller, ['Tickets', 'Ticket', 'TicketCreate']) ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' ?>">
                    <i class="fas fa-ticket-alt text-sm w-4 <?= in_array(\Altum\Router::$controller, ['Tickets', 'Ticket', 'TicketCreate']) ? 'text-white' : 'text-slate-400' ?>"></i>
                    <span><?= l('tickets.menu') ?></span>
                </a>
            </div>


            <?php if(!empty($data->pages)): ?>
                <div class="space-y-1">
                    <div class="px-3.5 py-1 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Pages</div>
                    <?php foreach($data->pages as $page): ?>
                        <?php if(in_array(mb_strtolower($page->title), ['about us', 'pricing plans']) || in_array(mb_strtolower($page->url), ['about', 'about-us', 'pricing-plans'])): ?>
                            <?php continue; ?>
                        <?php endif ?>
                        <a href="<?= $page->url ?>" target="<?= $page->target ?>" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-xs text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-all text-decoration-none">
                            <?php if($page->icon): ?>
                                <i class="<?= $page->icon ?> text-sm w-4 text-slate-400"></i>
                            <?php endif ?>
                            <span><?= $page->title ?></span>
                        </a>
                    <?php endforeach ?>
                </div>
            <?php endif ?>
        </nav>
    </div>

    <!-- Sidebar User Footer -->
    <?php if(is_logged_in()): ?>
        <div class="p-3 border-t border-slate-200/80">
            <!-- Collapsible User Submenu -->
            <div id="user_menu_collapse" class="hidden pb-2 space-y-1 border-b border-slate-100 mb-2">
                <?php if(!\Altum\Teams::is_delegated()): ?>
                    <?php if(\Altum\Authentication::is_admin()): ?>
                        <a href="<?= url('admin') ?>" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 text-decoration-none">
                            <i class="fas fa-shield-alt w-4"></i> <?= l('global.menu.admin') ?>
                        </a>
                    <?php endif ?>

                    <a href="<?= url('account') ?>" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50 text-decoration-none">
                        <i class="fas fa-user-cog text-slate-400 w-4"></i> <?= l('account.menu') ?>
                    </a>

                    <a href="<?= url('account-preferences') ?>" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50 text-decoration-none">
                        <i class="fas fa-sliders-h text-slate-400 w-4"></i> <?= l('account_preferences.menu') ?>
                    </a>

                    <a href="<?= url('account-plan') ?>" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50 text-decoration-none">
                        <i class="fas fa-box-open text-slate-400 w-4"></i> <?= l('account_plan.menu') ?>
                    </a>

                    <?php if(settings()->payment->is_enabled): ?>
                        <a href="<?= url('account-payments') ?>" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50 text-decoration-none">
                            <i class="fas fa-credit-card text-slate-400 w-4"></i> <?= l('account_payments.menu') ?>
                        </a>

                        <?php if(\Altum\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled): ?>
                            <a href="<?= url('referrals') ?>" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50 text-decoration-none">
                                <i class="fas fa-wallet text-slate-400 w-4"></i> <?= l('referrals.menu') ?>
                            </a>
                        <?php endif ?>
                    <?php endif ?>

                    <?php if(\Altum\Plugin::is_active('teams')): ?>
                        <a href="<?= url('teams-system') ?>" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50 text-decoration-none">
                            <i class="fas fa-users text-slate-400 w-4"></i> <?= l('teams_system.menu') ?>
                        </a>
                    <?php endif ?>
                <?php endif ?>

                <a href="<?= url('logout') ?>" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-rose-600 hover:bg-rose-50 text-decoration-none">
                    <i class="fas fa-sign-out-alt w-4"></i> <?= l('global.menu.logout') ?>
                </a>
            </div>

            <button onclick="document.getElementById('user_menu_collapse').classList.toggle('hidden'); this.querySelector('.fa-chevron-up').classList.toggle('rotate-180');" class="w-full flex items-center gap-3 p-2 rounded-xl hover:bg-slate-100 transition-all text-left focus:outline-none border-0 bg-transparent">
                <img src="<?= get_user_avatar($this->user->avatar, $this->user->email) ?>" class="h-9 w-9 rounded-xl object-cover ring-1 ring-slate-300" loading="lazy" />
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-bold text-slate-900 truncate m-0"><?= $this->user->name ?></p>
                    <p class="text-[11px] text-slate-500 truncate m-0"><?= $this->user->email ?></p>
                </div>
                <i class="fas fa-chevron-up text-xs text-slate-400 transition-transform duration-200"></i>
            </button>
        </div>
    <?php endif ?>
</div>
