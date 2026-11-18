<?php defined('ALTUMCODE') || die() ?>

<header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-slate-200/80 px-3.5 sm:px-6 lg:px-8 py-2.5 font-sans">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2.5 sm:gap-4">
            <button class="lg:hidden h-9 w-9 rounded-xl bg-slate-100 hover:bg-slate-200/90 text-slate-700 hover:text-slate-900 border border-slate-200/90 flex items-center justify-center transition-all focus:outline-none cursor-pointer shadow-2xs shrink-0" type="button" id="app_menu_toggler" data-toggle-sidebar onclick="if(window.toggle_app_sidebar) window.toggle_app_sidebar();" aria-label="<?= l('global.accessibility.toggle_navigation') ?>">
                <i class="fas fa-bars text-sm pointer-events-none"></i>
            </button>

            <a href="<?= url() ?>" class="group inline-flex items-center text-decoration-none" data-logo>
                <?= include_view(THEME_PATH . 'views/partials/logo.php', ['size' => 'sm']) ?>
            </a>

            <div class="hidden md:flex items-center gap-1 pl-3 border-l border-slate-200 text-xs">
                <a href="<?= url('dashboard') ?>" class="px-2.5 py-1.5 rounded-lg font-semibold text-decoration-none <?= \Altum\Router::$controller_key == 'dashboard' ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' ?>">Dashboard</a>
                <a href="<?= url('audits') ?>" class="px-2.5 py-1.5 rounded-lg font-semibold text-decoration-none <?= \Altum\Router::$controller_key == 'audits' ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' ?>">Audits</a>
                <a href="<?= url('tools') ?>" class="px-2.5 py-1.5 rounded-lg font-semibold text-decoration-none <?= \Altum\Router::$controller_key == 'tools' ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' ?>">Tools</a>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="<?= url('plan') ?>" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold transition-all text-decoration-none border border-blue-200/60">
                <i class="fas fa-crown text-amber-500 text-[11px]"></i>
                <span><?= $this->user->plan->name ?></span>
            </a>

            <?php if(is_logged_in()): ?>
                <div class="relative" id="user_profile_dropdown">
                    <button type="button" class="inline-flex items-center gap-2 p-1 pl-2 pr-3 rounded-full bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-white hover:border-slate-300 transition-all cursor-pointer shadow-2xs" onclick="const m = document.getElementById('user_profile_menu'); if(m) m.classList.toggle('hidden'); event.stopPropagation();">
                        <?php if($this->user->avatar): ?>
                            <img src="<?= \Altum\Uploads::get_full_url('avatars') . $this->user->avatar ?>" class="h-6 w-6 rounded-full object-cover" alt="" />
                        <?php else: ?>
                            <div class="h-6 w-6 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-[10px]">
                                <?= strtoupper(mb_substr($this->user->name, 0, 1)) ?>
                            </div>
                        <?php endif ?>
                        <span class="max-w-[100px] truncate hidden sm:inline"><?= $this->user->name ?></span>
                        <i class="fas fa-chevron-down text-[9px] text-slate-400"></i>
                    </button>

                    <div id="user_profile_menu" class="hidden absolute right-0 top-full mt-2 w-48 bg-white border border-slate-200/90 rounded-2xl shadow-xl p-1.5 z-50 text-xs font-semibold">
                        <div class="px-3 py-2 border-b border-slate-100 mb-1">
                            <div class="text-slate-900 font-bold truncate"><?= $this->user->name ?></div>
                            <div class="text-[11px] text-slate-400 font-normal truncate"><?= $this->user->email ?></div>
                        </div>
                        <a href="<?= url('account') ?>" class="flex items-center gap-2 px-3 py-2 rounded-xl text-slate-700 hover:bg-slate-50 hover:text-blue-600 text-decoration-none">
                            <i class="fas fa-user-cog text-slate-400 text-xs"></i>
                            <span><?= l('account.menu') ?></span>
                        </a>
                        <a href="<?= url('account-plan') ?>" class="flex items-center gap-2 px-3 py-2 rounded-xl text-slate-700 hover:bg-slate-50 hover:text-blue-600 text-decoration-none">
                            <i class="fas fa-credit-card text-slate-400 text-xs"></i>
                            <span><?= l('account_plan.menu') ?></span>
                        </a>
                        <?php if(\Altum\Authentication::is_admin()): ?>
                            <a href="<?= url('admin') ?>" class="flex items-center gap-2 px-3 py-2 rounded-xl text-slate-700 hover:bg-slate-50 hover:text-blue-600 text-decoration-none">
                                <i class="fas fa-shield-alt text-slate-400 text-xs"></i>
                                <span><?= l('global.menu.admin') ?></span>
                            </a>
                        <?php endif ?>
                        <div class="border-t border-slate-100 my-1"></div>
                        <a href="<?= url('logout') ?>" class="flex items-center gap-2 px-3 py-2 rounded-xl text-rose-600 hover:bg-rose-50 text-decoration-none">
                            <i class="fas fa-sign-out-alt text-rose-500 text-xs"></i>
                            <span><?= l('global.menu.logout') ?></span>
                        </a>
                    </div>
                </div>

                <script>
                    document.addEventListener('click', (e) => {
                        const m = document.getElementById('user_profile_menu');
                        if(m && !m.classList.contains('hidden') && !e.target.closest('#user_profile_dropdown')) {
                            m.classList.add('hidden');
                        }
                    });
                </script>
            <?php endif ?>
        </div>
    </div>
</header>
