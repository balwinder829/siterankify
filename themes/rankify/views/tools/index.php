<?php defined('ALTUMCODE') || die() ?>

<?php
$tools_list = [];
if(isset($data->tools)) {
    foreach($data->tools as $key => $value) {
        if(settings()->tools->available_tools->{$key}) {
            $cat = 'utilities';
            if(in_array($key, ['dns_lookup', 'ip_lookup', 'whois_lookup', 'ping', 'reverse_ip_lookup'])) {
                $cat = 'network';
            } elseif(in_array($key, ['ssl_lookup', 'hsts_checker', 'safe_url_checker', 'server_signature_checker'])) {
                $cat = 'security';
            } elseif(in_array($key, ['ttfb_checker', 'brotli_checker', 'http2_checker', 'website_page_size_checker', 'lazy_loading_images_checker'])) {
                $cat = 'performance';
            } elseif(in_array($key, ['meta_tags_checker', 'google_search_preview', 'bing_search_preview', 'yandex_search_preview', 'noindex_checker', 'opengraph_checker', 'image_alt_tags_checker', 'text_to_html_ratio_checker', 'google_cache_checker', 'deprecated_html_tags_checker'])) {
                $cat = 'seo';
            }
            $tools_list[$key] = array_merge($value, [
                'name' => l('tools.' . $key . '.name'),
                'description' => l('tools.' . $key . '.description'),
                'category' => $cat,
                'views' => $data->tools_usage[$key]->total_views ?? 0,
            ]);
        }
    }
}
$total_available_tools = count($tools_list) + 1;
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 font-sans space-y-5">
    <?= \Altum\Alerts::output_alerts() ?>

    <!-- Page Header -->
    <div class="bg-white border border-gray-200 rounded-xl p-5 sm:p-6 space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-5">
            <div class="space-y-2 max-w-xl">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 border border-blue-200 text-blue-700 text-xs font-semibold">
                    <i class="fas fa-tools text-blue-500 text-[10px]"></i>
                    Free Developer &amp; Technical SEO Suite
                </span>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight m-0">
                    Developer &amp; <span class="text-blue-600">SEO Utilities</span>
                </h1>
                <p class="text-sm text-gray-500 leading-relaxed m-0">
                    Diagnose DNS configurations, verify SSL certificates, inspect HTTP headers, preview SERP snippets, and minify web code instantly.
                </p>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <div class="px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 text-center">
                    <span class="text-2xl font-bold text-gray-900 block leading-none"><?= $total_available_tools ?></span>
                    <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wide block mt-1">Available Tools</span>
                </div>
                <div class="px-4 py-3 rounded-lg bg-blue-50 border border-blue-200 text-center">
                    <span class="text-2xl font-bold text-blue-600 block leading-none">100%</span>
                    <span class="text-[10px] font-semibold text-blue-700 uppercase tracking-wide block mt-1">Free Access</span>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row items-center gap-2">
            <div class="relative flex-1 w-full">
                <input type="search" id="tool_search_input" name="search"
                    class="w-full py-2.5 pr-4 pl-10 bg-gray-50 text-gray-900 placeholder-gray-400 rounded-lg text-sm border border-gray-200 focus:border-blue-600 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/15 transition-all"
                    placeholder="Search tools... (DNS, SSL, Ping, Minifier)" />
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
            </div>
            <button type="button" id="clear_search_btn"
                class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold text-xs transition-colors border-0 cursor-pointer">
                <i class="fas fa-times text-[10px]"></i> Clear
            </button>
        </div>

        <!-- Category Filters -->
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 no-scrollbar text-xs font-semibold" id="tool_category_filters">
            <button type="button" data-category="all" class="tool-filter-btn px-3.5 py-2 rounded-lg bg-blue-600 text-white border-0 cursor-pointer transition-colors whitespace-nowrap">
                All (<?= $total_available_tools ?>)
            </button>
            <button type="button" data-category="seo" class="tool-filter-btn px-3.5 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 border-0 cursor-pointer transition-colors whitespace-nowrap">
                <i class="fas fa-search-plus text-xs mr-1 text-gray-500"></i> SEO &amp; SERP
            </button>
            <button type="button" data-category="network" class="tool-filter-btn px-3.5 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 border-0 cursor-pointer transition-colors whitespace-nowrap">
                <i class="fas fa-network-wired text-xs mr-1 text-gray-500"></i> Network &amp; DNS
            </button>
            <button type="button" data-category="security" class="tool-filter-btn px-3.5 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 border-0 cursor-pointer transition-colors whitespace-nowrap">
                <i class="fas fa-shield-alt text-xs mr-1 text-gray-500"></i> Security &amp; SSL
            </button>
            <button type="button" data-category="performance" class="tool-filter-btn px-3.5 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 border-0 cursor-pointer transition-colors whitespace-nowrap">
                <i class="fas fa-tachometer-alt text-xs mr-1 text-gray-500"></i> Performance
            </button>
            <button type="button" data-category="utilities" class="tool-filter-btn px-3.5 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 border-0 cursor-pointer transition-colors whitespace-nowrap">
                <i class="fas fa-code text-xs mr-1 text-gray-500"></i> Utilities
            </button>
        </div>
    </div>

    <!-- No Results State -->
    <div id="tools_no_data" class="hidden text-center py-14 bg-white border border-gray-200 rounded-xl space-y-3">
        <div class="h-12 w-12 rounded-xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto text-xl">
            <i class="fas fa-search"></i>
        </div>
        <h3 class="text-base font-semibold text-gray-900 m-0">No tools matched your query</h3>
        <p class="text-sm text-gray-500 m-0 max-w-sm mx-auto">
            Try searching for DNS, SSL, Minifier, IP, or Ping.
        </p>
    </div>

    <!-- Tools Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" id="tools_grid">

        <!-- Featured SEO Audit Tool -->
        <div class="tool-card bg-white border border-gray-200 hover:border-blue-500 rounded-xl p-5 hover:shadow-sm transition-all flex flex-col justify-between group space-y-4"
            data-tool-id="seo_audit" data-tool-name="seo audit website scan technical check" data-tool-category="seo">
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="h-10 w-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-lg group-hover:bg-blue-600 group-hover:text-white transition-colors border border-blue-100">
                        <i class="fas fa-magnifying-glass-chart"></i>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 text-[10px] font-semibold border border-blue-200">Featured</span>
                        <span class="px-2 py-0.5 rounded-full bg-green-50 text-green-700 text-[10px] font-semibold border border-green-200">Free</span>
                    </div>
                </div>
                <div class="space-y-1">
                    <h3 class="text-sm font-semibold text-gray-900 group-hover:text-blue-600 transition-colors m-0">
                        <a href="<?= url() ?>" class="text-decoration-none text-gray-900 hover:text-blue-600">SEO Audit</a>
                    </h3>
                    <p class="text-xs text-gray-500 line-clamp-2 m-0 leading-relaxed">
                        Scan any public webpage for over 50 technical SEO signals, Core Web Vitals, and meta tags.
                    </p>
                </div>
            </div>
            <div class="pt-3 border-t border-gray-100 flex items-center justify-between">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">SEO Engine</span>
                <a href="<?= url() ?>" class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-700 text-decoration-none">
                    Run Audit <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- Dynamic Tools -->
        <?php foreach($tools_list as $key => $tool): ?>
            <div class="tool-card bg-white border border-gray-200 hover:border-blue-500 rounded-xl p-5 hover:shadow-sm transition-all flex flex-col justify-between group space-y-4"
                data-tool-id="<?= $key ?>" data-tool-name="<?= mb_strtolower($tool['name'] . ' ' . $tool['description']) ?>" data-tool-category="<?= $tool['category'] ?>">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="h-10 w-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-lg group-hover:bg-blue-600 group-hover:text-white transition-colors border border-blue-100">
                            <i class="<?= $tool['icon'] ?>"></i>
                        </div>
                        <?php if(settings()->tools->views_is_enabled && $tool['views'] > 0): ?>
                            <span class="px-2 py-0.5 rounded-full bg-gray-50 border border-gray-200 text-gray-500 text-[10px] font-semibold inline-flex items-center gap-1">
                                <i class="fas fa-eye text-[9px] text-gray-400"></i> <?= nr($tool['views']) ?>
                            </span>
                        <?php else: ?>
                            <span class="px-2 py-0.5 rounded-full bg-gray-100 text-gray-500 text-[10px] font-semibold capitalize"><?= $tool['category'] ?></span>
                        <?php endif ?>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-sm font-semibold text-gray-900 group-hover:text-blue-600 transition-colors m-0">
                            <a href="<?= url('tools/' . str_replace('_', '-', $key)) ?>" class="text-decoration-none text-gray-900 hover:text-blue-600">
                                <?= $tool['name'] ?>
                            </a>
                        </h3>
                        <p class="text-xs text-gray-500 line-clamp-2 m-0 leading-relaxed"><?= $tool['description'] ?></p>
                    </div>
                </div>
                <div class="pt-3 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wide capitalize"><?= $tool['category'] ?></span>
                    <a href="<?= url('tools/' . str_replace('_', '-', $key)) ?>" class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-700 text-decoration-none">
                        Use Tool <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput  = document.getElementById('tool_search_input');
    const clearBtn     = document.getElementById('clear_search_btn');
    const cards        = document.querySelectorAll('.tool-card');
    const noData       = document.getElementById('tools_no_data');
    const filterBtns   = document.querySelectorAll('.tool-filter-btn');

    let activeCategory = 'all';

    function filterTools() {
        const query = (searchInput ? searchInput.value : '').toLowerCase().trim();
        let count = 0;
        cards.forEach(card => {
            const name     = card.getAttribute('data-tool-name') || '';
            const category = card.getAttribute('data-tool-category') || '';
            const showQ    = query === '' || name.includes(query);
            const showC    = activeCategory === 'all' || category === activeCategory;
            if(showQ && showC) { card.classList.remove('hidden'); count++; }
            else               { card.classList.add('hidden'); }
        });
        if(noData) noData.classList.toggle('hidden', count > 0);
    }

    if(searchInput) searchInput.addEventListener('input', filterTools);

    if(clearBtn) {
        clearBtn.addEventListener('click', () => {
            if(searchInput) { searchInput.value = ''; filterTools(); searchInput.focus(); }
        });
    }

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            activeCategory = btn.getAttribute('data-category') || 'all';
            filterBtns.forEach(b => {
                b.classList.remove('bg-blue-600', 'text-white');
                b.classList.add('bg-gray-100', 'text-gray-700');
            });
            btn.classList.remove('bg-gray-100', 'text-gray-700');
            btn.classList.add('bg-blue-600', 'text-white');
            filterTools();
        });
    });
});
</script>
