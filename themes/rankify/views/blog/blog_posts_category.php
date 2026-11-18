<?php defined('ALTUMCODE') || die() ?>

<style>
.pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    list-style: none;
    padding: 0;
    margin: 0;
}
.pagination .page-item .page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 2.5rem;
    width: 2.5rem;
    border-radius: 0.75rem;
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
    color: #334155;
    font-weight: 700;
    font-size: 0.75rem;
    text-decoration: none;
    transition: all 0.2s ease;
}
.pagination .page-item.active .page-link {
    background-color: #2563eb;
    border-color: #2563eb;
    color: #ffffff;
}
.pagination .page-item .page-link:hover:not(.active) {
    background-color: #f1f5f9;
    border-color: #cbd5e1;
    color: #0f172a;
}
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-sans space-y-8">
    <div class="flex items-center gap-2 text-xs font-medium text-slate-500">
        <a href="<?= url() ?>" class="hover:text-blue-600 transition-colors text-decoration-none text-slate-500"><?= l('index.breadcrumb') ?></a>
        <i class="fas fa-chevron-right text-[10px] text-slate-400"></i>
        <a href="<?= url('blog') ?>" class="hover:text-blue-600 transition-colors text-decoration-none text-slate-500"><?= l('blog.breadcrumb') ?></a>
        <i class="fas fa-chevron-right text-[10px] text-slate-400"></i>
        <span class="text-slate-900 font-semibold truncate max-w-xs"><?= $data->blog_posts_category->title ?></span>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-8 sm:p-10 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-8">
        <div class="space-y-3.5 max-w-xl">
            <span class="inline-block text-xs font-bold text-blue-600 tracking-wider uppercase">Category Archive</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight m-0 text-slate-900 leading-tight">
                <?= $data->blog_posts_category->title ?>
            </h1>
            <?php if(!empty($data->blog_posts_category->description)): ?>
                <p class="text-sm text-slate-500 font-normal m-0 leading-relaxed pt-1"><?= $data->blog_posts_category->description ?></p>
            <?php endif ?>
        </div>

        <form action="<?= url('blog') ?>" method="get" class="w-full md:w-80">
            <div class="relative">
                <input type="search" name="search" class="w-full py-3 pr-4 pl-11 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-500/10 transition-all text-left" placeholder="Search in all articles..." />
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <div class="lg:col-span-2 space-y-6">
            <?php if (!empty($data->blog_posts)): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <?php foreach($data->blog_posts as $blog_post): ?>
                        <article class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs hover:border-slate-300 hover:shadow-sm transition-all flex flex-col justify-between group">
                            <div>
                                <?php if($blog_post->image): ?>
                                    <a href="<?= SITE_URL . ($blog_post->language ? \Altum\Language::$active_languages[$blog_post->language] . '/' : null) . 'blog/' . $blog_post->url ?>" class="block overflow-hidden bg-slate-100 border-b border-slate-100 aspect-video">
                                        <img src="<?= \Altum\Uploads::get_full_url('blog') . $blog_post->image ?>" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-200" alt="<?= $blog_post->image_description ?>" loading="lazy" />
                                    </a>
                                <?php endif ?>

                                <div class="p-5 space-y-2.5">
                                    <div class="flex items-center gap-2 text-[11px] text-slate-400 font-medium">
                                        <span class="inline-flex items-center gap-1.5"><i class="far fa-calendar-alt"></i> <?= \Altum\Date::get($blog_post->datetime, 2) ?></span>
                                    </div>

                                    <h2 class="text-base font-semibold text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-2 m-0 leading-snug">
                                        <a href="<?= SITE_URL . ($blog_post->language ? \Altum\Language::$active_languages[$blog_post->language] . '/' : null) . 'blog/' . $blog_post->url ?>" class="text-decoration-none text-slate-900 hover:text-blue-600">
                                            <?= $blog_post->title ?>
                                        </a>
                                    </h2>

                                    <p class="text-xs text-slate-500 font-normal line-clamp-2 m-0 leading-relaxed">
                                        <?= $blog_post->description ?>
                                    </p>
                                </div>
                            </div>

                            <div class="px-5 py-3 border-t border-slate-100 flex items-center justify-between">
                                <a href="<?= SITE_URL . ($blog_post->language ? \Altum\Language::$active_languages[$blog_post->language] . '/' : null) . 'blog/' . $blog_post->url ?>" class="inline-flex items-center gap-1 text-xs font-medium text-blue-600 hover:text-blue-700 transition-colors text-decoration-none group-hover:translate-x-0.5">
                                    <span>Read article</span>
                                    <i class="fas fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>
                        </article>
                    <?php endforeach ?>
                </div>

                <div class="pt-4 flex justify-center"><?= $data->pagination ?></div>
            <?php else: ?>
                <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center space-y-3 shadow-xs">
                    <div class="h-12 w-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center text-lg mx-auto">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-900 m-0">No Articles in this Category</h3>
                    <p class="text-xs text-slate-500 font-normal m-0 max-w-sm mx-auto leading-relaxed">There are currently no published articles in this category. Check back soon or explore other topics.</p>
                </div>
            <?php endif ?>
        </div>

        <div class="space-y-6 lg:sticky lg:top-24">
            <?php if (!empty($data->blog_posts_categories)): ?>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-3">
                    <div class="pb-2.5 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-xs font-semibold text-slate-900 uppercase tracking-wider m-0 flex items-center gap-2">
                            <i class="fas fa-folder text-blue-600"></i>
                            <span>Categories</span>
                        </h3>
                        <span class="text-[11px] font-medium text-slate-400"><?= count($data->blog_posts_categories) ?></span>
                    </div>

                    <div class="space-y-1">
                        <?php foreach($data->blog_posts_categories as $blog_posts_category): ?>
                            <a href="<?= SITE_URL . ($blog_posts_category->language ? \Altum\Language::$active_languages[$blog_posts_category->language] . '/' : null) . 'blog/category/' . $blog_posts_category->url ?>" class="flex items-center justify-between px-3 py-2 rounded-xl text-decoration-none text-xs font-medium transition-colors <?= $data->blog_posts_category->blog_posts_category_id == $blog_posts_category->blog_posts_category_id ? 'bg-blue-50 text-blue-600' : 'text-slate-700 hover:bg-slate-50' ?>">
                                <span><?= $blog_posts_category->title ?></span>
                                <span class="px-2 py-0.5 rounded-md <?= $data->blog_posts_category->blog_posts_category_id == $blog_posts_category->blog_posts_category_id ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-500' ?> text-[10px]"><?= nr($blog_posts_category->blog_posts_count ?? $blog_posts_category->posts_count ?? 0) ?></span>
                            </a>
                        <?php endforeach ?>
                    </div>
                </div>
            <?php endif ?>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-3">
                <div class="h-9 w-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm border border-blue-100">
                    <i class="fas fa-bolt"></i>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-slate-900 m-0">Instant SEO Health Audit</h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-normal m-0 mt-1">Scan technical crawl factors, PageSpeed metrics, and security headers in seconds.</p>
                </div>
                <a href="<?= url() ?>" class="block w-full py-2.5 text-center rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-medium text-xs shadow-xs transition-colors text-decoration-none">
                    Start Free Audit
                </a>
            </div>
        </div>
    </div>
</div>
