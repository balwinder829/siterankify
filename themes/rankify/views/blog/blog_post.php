<?php defined('ALTUMCODE') || die() ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-sans space-y-8">
    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
        <a href="<?= url() ?>" class="hover:text-blue-600 transition-colors text-decoration-none text-slate-500"><?= l('index.breadcrumb') ?></a>
        <i class="fas fa-chevron-right text-[10px] text-slate-400"></i>
        <a href="<?= url('blog') ?>" class="hover:text-blue-600 transition-colors text-decoration-none text-slate-500"><?= l('blog.breadcrumb') ?></a>
        <?php if($data->blog_posts_category): ?>
            <i class="fas fa-chevron-right text-[10px] text-slate-400"></i>
            <a href="<?= url('blog/category/' . $data->blog_posts_category->url) ?>" class="hover:text-blue-600 transition-colors text-decoration-none text-slate-500"><?= $data->blog_posts_category->title ?></a>
        <?php endif ?>
        <i class="fas fa-chevron-right text-[10px] text-slate-400"></i>
        <span class="text-slate-900 font-bold truncate max-w-xs"><?= $data->blog_post->title ?></span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        <div class="lg:col-span-2 space-y-6">
            <article class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-10 shadow-xs space-y-6">
                <div class="space-y-3 pb-4 border-b border-slate-100">
                    <?php if($data->blog_posts_category): ?>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 border border-blue-200 text-blue-700 text-xs font-bold">
                            <?= $data->blog_posts_category->title ?>
                        </span>
                    <?php endif ?>

                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight m-0">
                        <?= $data->blog_post->title ?>
                    </h1>

                    <div class="flex flex-wrap items-center gap-4 text-xs font-medium text-slate-400">
                        <span class="flex items-center gap-1.5"><i class="far fa-calendar text-slate-400"></i> <?= \Altum\Date::get($data->blog_post->datetime, 2) ?></span>
                        <?php if(settings()->content->blog_views_is_enabled): ?>
                            <span class="flex items-center gap-1.5"><i class="far fa-eye text-slate-400"></i> <?= nr($data->blog_post->total_views) ?> Views</span>
                        <?php endif ?>
                        <?php $estimated_reading_time = string_estimate_reading_time($data->blog_post->content) ?>
                        <?php if($estimated_reading_time->minutes > 0): ?>
                            <span class="flex items-center gap-1.5"><i class="far fa-clock text-slate-400"></i> <?= $estimated_reading_time->minutes ?> min read</span>
                        <?php endif ?>
                    </div>
                </div>

                <?php if($data->blog_post->image): ?>
                    <div class="rounded-2xl overflow-hidden border border-slate-100 shadow-xs">
                        <img src="<?= \Altum\Uploads::get_full_url('blog') . $data->blog_post->image ?>" class="w-full h-auto max-h-[420px] object-cover" alt="<?= $data->blog_post->image_description ?>" />
                    </div>
                <?php endif ?>

                <?php if(!empty($data->blog_post->description)): ?>
                    <div class="p-4 rounded-xl bg-slate-50 border-l-4 border-blue-600 text-slate-700 text-xs sm:text-sm font-medium italic leading-relaxed">
                        <?= $data->blog_post->description ?>
                    </div>
                <?php endif ?>

                <div class="prose max-w-none text-slate-700 leading-relaxed font-sans text-sm sm:text-base space-y-4">
                    <div class="<?= $data->blog_post->editor == 'wysiwyg' ? 'ql-content' : null ?>">
                        <?= $data->blog_post->content ?>
                    </div>
                </div>

                <?php if(settings()->content->blog_share_is_enabled): ?>
                    <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Share this article:</span>
                        <div class="flex items-center gap-2">
                            <?= include_view(THEME_PATH . 'views/partials/share_buttons.php', ['url' => url(\Altum\Router::$original_request), 'class' => 'h-8 px-3 rounded-lg bg-slate-100 hover:bg-blue-50 text-slate-700 text-xs font-semibold inline-flex items-center gap-1.5 border-0 transition-colors', 'copy_to_clipboard' => true]) ?>
                        </div>
                    </div>
                <?php endif ?>
            </article>

            <?php if(!empty($data->blog_posts)): ?>
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-slate-900 tracking-tight m-0">Related Articles</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <?php foreach($data->blog_posts as $related_post): ?>
                            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs space-y-2 hover:border-blue-300 transition-colors">
                                <?php if($related_post->image): ?>
                                    <a href="<?= SITE_URL . ($related_post->language ? \Altum\Language::$active_languages[$related_post->language] . '/' : null) . 'blog/' . $related_post->url ?>" class="block rounded-lg overflow-hidden border border-slate-100">
                                        <img src="<?= \Altum\Uploads::get_full_url('blog') . $related_post->image ?>" class="w-full h-32 object-cover hover:scale-105 transition-transform" alt="<?= $related_post->title ?>" />
                                    </a>
                                <?php endif ?>
                                <h4 class="text-xs font-bold text-slate-900 m-0 line-clamp-2 leading-snug">
                                    <a href="<?= SITE_URL . ($related_post->language ? \Altum\Language::$active_languages[$related_post->language] . '/' : null) . 'blog/' . $related_post->url ?>" class="text-decoration-none text-slate-900 hover:text-blue-600">
                                        <?= $related_post->title ?>
                                    </a>
                                </h4>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            <?php endif ?>
        </div>

        <div class="space-y-5">
            <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs space-y-3 text-center">
                <div class="h-12 w-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg mx-auto border border-blue-100">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900 m-0">SiteRankify Editorial Team</h3>
                <p class="text-xs text-slate-500 font-normal leading-relaxed m-0">Curated and reviewed by technical search specialists, performance architects, and webmasters.</p>
            </div>

            <?php if(!empty($data->blog_posts_categories)): ?>
                <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs space-y-3">
                    <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider m-0 flex items-center gap-2">
                        <i class="fas fa-folder text-blue-600"></i>
                        <span>Topics &amp; Categories</span>
                    </h3>
                    <div class="space-y-1">
                        <?php foreach($data->blog_posts_categories as $blog_posts_category): ?>
                            <a href="<?= SITE_URL . ($blog_posts_category->language ? \Altum\Language::$active_languages[$blog_posts_category->language] . '/' : null) . 'blog/category/' . $blog_posts_category->url ?>" class="flex items-center justify-between p-2 rounded-xl hover:bg-slate-50 hover:text-blue-600 transition-colors text-decoration-none text-slate-700 text-xs font-semibold">
                                <span><?= $blog_posts_category->title ?></span>
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-500 text-[10px] font-bold"><?= nr($blog_posts_category->blog_posts_count) ?></span>
                            </a>
                        <?php endforeach ?>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>
