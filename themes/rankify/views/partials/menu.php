<?php defined('ALTUMCODE') || die() ?>

<header class="sticky top-0 z-50 w-full bg-white/95 backdrop-blur-md border-b border-slate-200/90 font-sans transition-all duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 gap-4">

            <!-- Brand Logo -->
            <a href="<?= url() ?>" class="inline-flex items-center gap-2.5 text-decoration-none shrink-0 group focus:outline-none" data-logo>
                <?= include_view(THEME_PATH . 'views/partials/logo.php', ['size' => 'md']) ?>
            </a>

            <!-- Desktop Navigation Links -->
            <nav class="hidden lg:flex items-center gap-1.5" aria-label="Main Navigation">
                <?php foreach($data->pages as $page): ?>
                    <?php if(in_array(mb_strtolower($page->title), ['pricing plans']) || in_array(mb_strtolower($page->url), ['pricing-plans'])): ?>
                        <?php continue; ?>
                    <?php endif ?>
                    <a class="px-3.5 py-2 rounded-lg text-[13px] font-semibold transition-all duration-150 text-decoration-none whitespace-nowrap text-slate-600 hover:text-slate-900 hover:bg-slate-100/80 active:bg-slate-200/60" href="<?= $page->url ?>" target="<?= $page->target ?>">
                        <?php if($page->icon): ?>
                            <i class="<?= $page->icon ?> text-slate-400 text-xs mr-1.5"></i>
                        <?php endif ?>
                        <?= $page->title ?>
                    </a>
                <?php endforeach ?>

                <?php if(settings()->tools->is_enabled && (settings()->tools->access == 'everyone' || (settings()->tools->access == 'users' && is_logged_in()))): ?>
                    <a class="px-3.5 py-2 rounded-lg text-[13px] font-semibold transition-all duration-150 text-decoration-none whitespace-nowrap inline-flex items-center gap-1.5 <?= \Altum\Router::$controller_key == 'tools' ? 'text-blue-600 bg-blue-50/80 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' ?>" href="<?= url('tools') ?>">
                        <i class="fas fa-layer-group text-xs <?= \Altum\Router::$controller_key == 'tools' ? 'text-blue-600' : 'text-slate-400' ?>"></i>
                        <?= l('tools.menu') ?>
                    </a>
                <?php endif ?>

                <?php if(settings()->payment->is_enabled): ?>
                    <a class="px-3.5 py-2 rounded-lg text-[13px] font-semibold transition-all duration-150 text-decoration-none whitespace-nowrap inline-flex items-center gap-1.5 <?= \Altum\Router::$controller_key == 'plan' ? 'text-blue-600 bg-blue-50/80 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' ?>" href="<?= url('plan') ?>">
                        <i class="fas fa-gem text-xs <?= \Altum\Router::$controller_key == 'plan' ? 'text-blue-600' : 'text-slate-400' ?>"></i>
                        <?= l('plan.menu') ?>
                    </a>
                <?php endif ?>

                <a class="px-3.5 py-2 rounded-lg text-[13px] font-semibold transition-all duration-150 text-decoration-none whitespace-nowrap inline-flex items-center gap-1.5 <?= \Altum\Router::$controller_key == 'blog' ? 'text-blue-600 bg-blue-50/80 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' ?>" href="<?= url('blog') ?>">
                    <i class="fas fa-book-open text-xs <?= \Altum\Router::$controller_key == 'blog' ? 'text-blue-600' : 'text-slate-400' ?>"></i>
                    <?= l('blog.menu') ?>
                </a>

                <a class="px-3.5 py-2 rounded-lg text-[13px] font-semibold transition-all duration-150 text-decoration-none whitespace-nowrap inline-flex items-center gap-1.5 <?= \Altum\Router::$controller_key == 'contact' ? 'text-blue-600 bg-blue-50/80 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' ?>" href="<?= url('contact') ?>">
                    <i class="fas fa-headset text-xs <?= \Altum\Router::$controller_key == 'contact' ? 'text-blue-600' : 'text-slate-400' ?>"></i>
                    <?= l('contact.menu') ?>
                </a>
            </nav>

            <!-- Desktop Action Buttons / User Menu -->
            <div class="hidden lg:flex items-center gap-3">
                <?php if(is_logged_in()): ?>
                    <a class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold text-slate-700 hover:text-slate-900 rounded-lg hover:bg-slate-100 text-decoration-none transition-colors" href="<?= url('dashboard') ?>">
                        <i class="fas fa-chart-line text-blue-600"></i>
                        <?= l('dashboard.menu') ?>
                    </a>

                    <div class="relative dropdown">
                        <a class="flex items-center gap-2.5 pl-1.5 pr-3 py-1 rounded-xl bg-slate-50 hover:bg-slate-100 transition-all cursor-pointer text-decoration-none border border-slate-200/90 shadow-2xs" data-toggle="dropdown" href="#" aria-haspopup="true" aria-expanded="false">
                            <img src="<?= get_user_avatar($this->user->avatar, $this->user->email) ?>" class="h-7 w-7 rounded-lg object-cover ring-1 ring-slate-200 shrink-0" loading="lazy" alt="Avatar" />
                            <div class="flex flex-col text-left leading-none">
                                <span class="text-xs font-bold text-slate-800 truncate max-w-[90px]"><?= $this->user->name ?></span>
                                <span class="text-[10px] text-slate-500 font-medium truncate max-w-[90px]"><?= $this->user->is_admin ? 'Admin' : 'Member' ?></span>
                            </div>
                            <i class="fas fa-chevron-down text-[9px] text-slate-400 ml-1"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right shadow-xl border border-slate-200 rounded-2xl p-1.5 w-60 space-y-1 bg-white mt-1">
                            <div class="px-3.5 py-2.5 bg-slate-50 rounded-xl border border-slate-100 mb-1">
                                <p class="text-xs font-bold text-slate-900 truncate m-0"><?= $this->user->name ?></p>
                                <p class="text-[11px] text-slate-500 truncate m-0 mt-0.5"><?= $this->user->email ?></p>
                            </div>
                            <?php if($this->user->is_admin): ?>
                                <a href="<?= url('admin') ?>" class="dropdown-item rounded-xl py-2 px-3 flex items-center gap-2.5 text-xs font-semibold text-purple-700 bg-purple-50/60 hover:bg-purple-100/70 text-decoration-none transition-colors">
                                    <i class="fas fa-shield-halved w-4 text-center text-purple-600"></i> Admin Panel
                                </a>
                            <?php endif ?>
                            <a href="<?= url('audits') ?>" class="dropdown-item rounded-xl py-2 px-3 flex items-center gap-2.5 text-xs font-medium text-slate-700 hover:bg-slate-50 text-decoration-none transition-colors">
                                <i class="fas fa-bolt text-amber-500 w-4 text-center"></i> <?= l('audits.menu') ?>
                            </a>
                            <a href="<?= url('websites') ?>" class="dropdown-item rounded-xl py-2 px-3 flex items-center gap-2.5 text-xs font-medium text-slate-700 hover:bg-slate-50 text-decoration-none transition-colors">
                                <i class="fas fa-globe text-blue-500 w-4 text-center"></i> <?= l('websites.menu') ?>
                            </a>
                            <a href="<?= url('account') ?>" class="dropdown-item rounded-xl py-2 px-3 flex items-center gap-2.5 text-xs font-medium text-slate-700 hover:bg-slate-50 text-decoration-none transition-colors">
                                <i class="fas fa-user-gear text-slate-400 w-4 text-center"></i> <?= l('account.menu') ?>
                            </a>
                            <div class="my-1 border-t border-slate-100"></div>
                            <a href="<?= url('logout') ?>" class="dropdown-item rounded-xl py-2 px-3 flex items-center gap-2.5 text-xs font-semibold text-rose-600 hover:bg-rose-50 text-decoration-none transition-colors">
                                <i class="fas fa-arrow-right-from-bracket w-4 text-center"></i> <?= l('global.menu.logout') ?>
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a class="px-4 py-2 text-[13px] font-bold text-slate-700 hover:text-slate-900 rounded-xl hover:bg-slate-100 transition-colors text-decoration-none" href="<?= url('login') ?>">
                        <?= l('login.menu') ?>
                    </a>

                    <?php if(settings()->users->register_is_enabled): ?>
                        <a class="inline-flex items-center gap-2 px-4.5 py-2 rounded-xl bg-blue-600 text-white text-[13px] font-bold hover:bg-blue-700 shadow-xs hover:shadow transition-all text-decoration-none" href="<?= url('register') ?>">
                            <span>Get Started</span>
                            <i class="fas fa-arrow-right text-[11px]"></i>
                        </a>
                    <?php endif ?>
                <?php endif ?>
            </div>

            <!-- Mobile Hamburger Toggle -->
            <button id="navbar_toggler" class="lg:hidden h-10 w-10 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 flex items-center justify-center transition-colors focus:outline-none cursor-pointer border border-slate-200/90 shadow-2xs" type="button" aria-label="<?= l('global.accessibility.toggle_navigation') ?>">
                <i class="fas fa-bars text-sm"></i>
            </button>
        </div>

        <!-- Mobile Nav Drawer -->
        <div id="mobile_nav" class="hidden lg:hidden border-t border-slate-200/80 py-4 bg-white">
            <ul class="flex flex-col gap-1 m-0 p-0 list-none">
                <?php foreach($data->pages as $page): ?>
                    <?php if(in_array(mb_strtolower($page->title), ['pricing plans']) || in_array(mb_strtolower($page->url), ['pricing-plans'])): ?>
                        <?php continue; ?>
                    <?php endif ?>
                    <li>
                        <a class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-700 text-sm font-semibold hover:bg-slate-100 text-decoration-none transition-colors" href="<?= $page->url ?>" target="<?= $page->target ?>">
                            <?php if($page->icon): ?>
                                <i class="<?= $page->icon ?> text-slate-400 text-xs w-4 text-center"></i>
                            <?php else: ?>
                                <i class="fas fa-circle text-[6px] text-slate-300 w-4 text-center"></i>
                            <?php endif ?>
                            <?= $page->title ?>
                        </a>
                    </li>
                <?php endforeach ?>

                <?php if(settings()->tools->is_enabled && (settings()->tools->access == 'everyone' || (settings()->tools->access == 'users' && is_logged_in()))): ?>
                    <li>
                        <a class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-700 text-sm font-semibold hover:bg-slate-100 text-decoration-none transition-colors <?= \Altum\Router::$controller_key == 'tools' ? 'bg-blue-50 text-blue-600' : '' ?>" href="<?= url('tools') ?>">
                            <i class="fas fa-layer-group text-slate-400 text-xs w-4 text-center"></i>
                            <?= l('tools.menu') ?>
                        </a>
                    </li>
                <?php endif ?>
                <?php if(settings()->payment->is_enabled): ?>
                    <li>
                        <a class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-700 text-sm font-semibold hover:bg-slate-100 text-decoration-none transition-colors <?= \Altum\Router::$controller_key == 'plan' ? 'bg-blue-50 text-blue-600' : '' ?>" href="<?= url('plan') ?>">
                            <i class="fas fa-gem text-slate-400 text-xs w-4 text-center"></i>
                            <?= l('plan.menu') ?>
                        </a>
                    </li>
                <?php endif ?>
                <li>
                    <a class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-700 text-sm font-semibold hover:bg-slate-100 text-decoration-none transition-colors <?= \Altum\Router::$controller_key == 'blog' ? 'bg-blue-50 text-blue-600' : '' ?>" href="<?= url('blog') ?>">
                        <i class="fas fa-book-open text-slate-400 text-xs w-4 text-center"></i>
                        <?= l('blog.menu') ?>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-slate-700 text-sm font-semibold hover:bg-slate-100 text-decoration-none transition-colors <?= \Altum\Router::$controller_key == 'contact' ? 'bg-blue-50 text-blue-600' : '' ?>" href="<?= url('contact') ?>">
                        <i class="fas fa-headset text-slate-400 text-xs w-4 text-center"></i>
                        <?= l('contact.menu') ?>
                    </a>
                </li>

                <?php if(is_logged_in()): ?>
                    <li class="mt-3 pt-3 border-t border-slate-100">
                        <a class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl bg-blue-50 text-blue-600 text-sm font-bold text-decoration-none" href="<?= url('dashboard') ?>">
                            <i class="fas fa-chart-line text-xs w-4 text-center"></i>
                            <?= l('dashboard.menu') ?>
                        </a>
                    </li>
                    <?php if($this->user->is_admin): ?>
                        <li>
                            <a class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl bg-purple-50 text-purple-700 text-sm font-bold text-decoration-none" href="<?= url('admin') ?>">
                                <i class="fas fa-shield-halved text-xs w-4 text-center"></i>
                                Admin Panel
                            </a>
                        </li>
                    <?php endif ?>
                    <li>
                        <a class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-rose-600 text-sm font-bold hover:bg-rose-50 text-decoration-none transition-colors" href="<?= url('logout') ?>">
                            <i class="fas fa-arrow-right-from-bracket text-xs w-4 text-center"></i>
                            <?= l('global.menu.logout') ?>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="mt-3 pt-3 border-t border-slate-100 flex flex-col gap-2">
                        <a class="flex items-center justify-center px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 text-sm font-bold text-decoration-none hover:bg-slate-100 transition-colors" href="<?= url('login') ?>">
                            <?= l('login.menu') ?>
                        </a>
                        <?php if(settings()->users->register_is_enabled): ?>
                            <a class="flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-bold text-decoration-none hover:bg-blue-700 shadow-xs transition-colors" href="<?= url('register') ?>">
                                <span>Get Started Free</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        <?php endif ?>
                    </li>
                <?php endif ?>
            </ul>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggler = document.getElementById('navbar_toggler');
        const mobileNav = document.getElementById('mobile_nav');
        if(toggler && mobileNav) {
            toggler.addEventListener('click', () => {
                mobileNav.classList.toggle('hidden');
                const icon = toggler.querySelector('i');
                if(icon) {
                    if(mobileNav.classList.contains('hidden')) {
                        icon.classList.remove('fa-xmark');
                        icon.classList.add('fa-bars');
                    } else {
                        icon.classList.remove('fa-bars');
                        icon.classList.add('fa-xmark');
                    }
                }
            });
        }
    });
</script>
