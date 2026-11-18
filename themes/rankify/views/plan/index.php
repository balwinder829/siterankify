<?php defined('ALTUMCODE') || die() ?>

<div class="bg-white border-b border-gray-200 font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 space-y-10">
        <?= \Altum\Alerts::output_alerts() ?>

        <?php if(is_logged_in() && $this->user->plan_is_expired && $this->user->plan_id != 'free'): ?>
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-amber-800 text-sm font-medium flex items-center gap-2">
                <i class="fas fa-exclamation-triangle text-amber-500 shrink-0"></i>
                <span><?= l('global.info_message.user_plan_is_expired') ?></span>
            </div>
        <?php endif ?>

        <!-- Page Header -->
        <div class="text-center space-y-3 max-w-2xl mx-auto">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-50 border border-blue-200 text-blue-700 text-xs font-semibold">
                Flexible Lifetime &amp; Subscription Tiers
            </span>
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight m-0">
                Simple, Transparent <span class="text-blue-600">Pricing</span>
            </h1>
            <p class="text-sm sm:text-base text-gray-500 leading-relaxed m-0">
                Choose a one-time Premier Lifetime pass or a high-volume Monthly or Annual plan. Unlock deep technical crawl diagnostics, white-label branding, and PDF exports.
            </p>
        </div>

        <!-- Plans (dynamic from controller) -->
        <?= $this->views['plans'] ?>

        <!-- Why Choose Section -->
        <div class="pt-8 border-t border-gray-100 space-y-8">
            <div class="text-center space-y-1">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 m-0">Why Choose SiteRankify Plans?</h2>
                <p class="text-sm text-gray-500 m-0">Built for individual site owners, growth teams, and enterprise agencies.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="bg-gray-50 border border-gray-200 p-6 rounded-xl space-y-3 hover:border-gray-300 hover:bg-white transition-all">
                    <div class="h-10 w-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-lg border border-blue-100">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 m-0">Dedicated Support</h3>
                    <p class="text-xs text-gray-500 leading-relaxed m-0">Direct email support for technical setup, PDF customization, and API integration assistance.</p>
                </div>

                <div class="bg-gray-50 border border-gray-200 p-6 rounded-xl space-y-3 hover:border-gray-300 hover:bg-white transition-all">
                    <div class="h-10 w-10 rounded-lg bg-green-50 text-green-600 flex items-center justify-center text-lg border border-green-100">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 m-0">No Hidden Costs</h3>
                    <p class="text-xs text-gray-500 leading-relaxed m-0">Clear pricing structure with zero hidden fees. Upgrade or revert plans anytime with full transparency.</p>
                </div>

                <div class="bg-gray-50 border border-gray-200 p-6 rounded-xl space-y-3 hover:border-gray-300 hover:bg-white transition-all">
                    <div class="h-10 w-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-lg border border-amber-100">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900 m-0">Instant Activation</h3>
                    <p class="text-xs text-gray-500 leading-relaxed m-0">All plan limits, PDF export capabilities, and white-label features activate instantly upon payment.</p>
                </div>
            </div>
        </div>
    </div>
</div>
