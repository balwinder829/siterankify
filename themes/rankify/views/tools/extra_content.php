<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->tools->extra_content_is_enabled): ?>
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 sm:p-5 shadow-xs space-y-3 font-sans mt-5">
        <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed text-xs">
            <?= l('tools.' . \Altum\Router::$method . '.extra_content') ?: l('tools.extra_content') ?>
        </div>
    </div>
<?php endif ?>

<?php if(settings()->tools->share_is_enabled): ?>
<div class="mt-5 font-sans">
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-3">
        <span class="text-xs font-bold text-slate-900 uppercase tracking-wider"><i class="fas fa-share-alt text-blue-600 mr-2"></i> <?= l('tools.share') ?></span>
        <div class="flex items-center gap-2">
            <?= include_view(THEME_PATH . 'views/partials/share_buttons.php', ['url' => url(\Altum\Router::$original_request), 'class' => 'btn btn-gray-100 btn-sm', 'copy_to_clipboard' => true]) ?>
        </div>
    </div>
</div>
<?php endif ?>

<?php ob_start() ?>
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@type": "ListItem",
                "position": 1,
                "name": "<?= l('index.title') ?>",
                    "item": "<?= url() ?>"
                },
                {
                    "@type": "ListItem",
                    "position": 2,
                    "name": "<?= l('tools.title') ?>",
                    "item": "<?= url('tools') ?>"
                },
                {
                    "@type": "ListItem",
                    "position": 3,
                    "name": "<?= l('tools.' . \Altum\Router::$method . '.name') ?>",
                    "item": "<?= url(\Altum\Router::$original_request) ?>"
                }
            ]
        }
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
