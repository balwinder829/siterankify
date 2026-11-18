<?php defined('ALTUMCODE') || die() ?>
<!DOCTYPE html>
<html lang="<?= \Altum\Language::$code ?>" dir="<?= l('direction') ?>" class="h-100">
    <head>
        <title><?= \Altum\Title::get() ?></title>
        <base href="<?= SITE_URL; ?>">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <?php if(\Altum\Plugin::is_active('pwa') && settings()->pwa->is_enabled): ?>
            <meta name="theme-color" content="<?= settings()->pwa->theme_color ?>"/>

            <?php if(settings()->pwa->is_fullscreen ?? true): ?>
                <meta name="apple-mobile-web-app-capable" content="yes">
                <meta name="mobile-web-app-capable" content="yes">
                <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
            <?php endif ?>

			<?= pwa_generate_dynamic_splash_screen_links() ?>

            <link rel="manifest" href="<?= SITE_URL . UPLOADS_URL_PATH . \Altum\Uploads::get_path('pwa') . 'manifest.json' ?>" />
        <?php endif ?>

        <?php if(\Altum\Meta::$canonical): ?>
            <link rel="canonical" href="<?= \Altum\Meta::$canonical ?>" />
        <?php endif ?>

        <?php if(\Altum\Meta::$robots): ?>
            <meta name="robots" content="<?= \Altum\Meta::$robots ?>">
        <?php endif ?>

        <?php \Altum\Meta::output() ?>

        <?php if(\Altum\Meta::$link_alternate): ?>
            <link rel="alternate" href="<?= SITE_URL . \Altum\Router::$original_request ?>" hreflang="x-default" />
            <?php if(count(\Altum\Language::$active_languages) > 1): ?>
                <?php foreach(\Altum\Language::$active_languages as $language_name => $language_code): ?>
                    <?php if(settings()->main->default_language != $language_name): ?>
                        <link rel="alternate" href="<?= SITE_URL . $language_code . '/' . \Altum\Router::$original_request ?>" hreflang="<?= $language_code ?>" />
                    <?php endif ?>
                <?php endforeach ?>
            <?php endif ?>
        <?php endif ?>

        <?php if(!empty(settings()->main->favicon)): ?>
            <link href="<?= settings()->main->favicon_full_url ?>" rel="icon" />
        <?php endif ?>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,600&family=Raleway:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,600&display=swap" rel="stylesheet">

        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        colors: {
                            brand: {
                                50:  '#f0f9ff',
                                100: '#e0f2fe',
                                200: '#bae6fd',
                                300: '#7dd3fc',
                                400: '#38bdf8',
                                500: '#1084cd',
                                600: '#0c6fae',
                                700: '#085384',
                                800: '#0b2b48',
                                900: '#081d32',
                            },
                            navy: {
                                800: '#0e2e4e',
                                900: '#0b2239',
                                950: '#051321',
                            }
                        },
                        fontFamily: {
                            sans: ['Montserrat', 'Raleway', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
                            heading: ['Montserrat', 'Raleway', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
                        }
                    }
                }
            }
        </script>

        <!-- SiteRankify Core Design System (Tailwind & Modern CSS) -->
        <link href="<?= ASSETS_FULL_URL ?>css/siterankify.css?v=<?= PRODUCT_CODE ?>" rel="stylesheet" media="screen">

        <?= \Altum\Event::get_content('head') ?>

        <?php if(!empty(settings()->custom->head_js)): ?>
            <?= get_settings_custom_head_js() ?>
        <?php endif ?>

        <?php if(!empty(settings()->custom->head_css)): ?>
            <style><?= settings()->custom->head_css ?></style>
        <?php endif ?>
    </head>

    <body class="<?= l('direction') == 'rtl' ? 'rtl' : null ?> bg-gray-50 font-sans text-gray-900 antialiased min-h-screen flex flex-col <?= \Altum\ThemeStyle::get() == 'dark' ? 'cc--darkmode' : null ?>" data-theme-style="<?= \Altum\ThemeStyle::get() ?>">
        <?php if(!empty(settings()->custom->body_content)): ?>
            <?= settings()->custom->body_content ?>
        <?php endif ?>

        <?php require THEME_PATH . 'views/partials/announcements.php' ?>
        <?php require THEME_PATH . 'views/partials/cookie_consent.php' ?>

        <main class="flex-1 flex items-center justify-center px-4 py-10 sm:py-16">
            <div class="w-full max-w-[440px]">
                <div class="text-center mb-8">
                    <a href="<?= url() ?>" class="inline-flex items-center no-underline focus:outline-none" data-logo>
                        <?= include_view(THEME_PATH . 'views/partials/logo.php', ['size' => 'lg']) ?>
                    </a>
                </div>
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-9">
                    <?= $this->views['content'] ?>
                </div>
                <div class="mt-6 text-center text-xs text-slate-500 font-medium">
                    <span>&copy; <?= date('Y') ?> <?= settings()->main->title ?>. Powered by Sortiq Solutions.</span>
                </div>
            </div>
        </main>


        <?= \Altum\Event::get_content('modals') ?>

        <?php require THEME_PATH . 'views/partials/js_global_variables.php' ?>

        <?php foreach(['libraries/jquery.min.js', 'libraries/popper.min.js', 'libraries/bootstrap.min.js', 'custom.js', 'libraries/fontawesome.min.js', 'libraries/fontawesome-solid.min.js', 'libraries/fontawesome-brands.modified.js'] as $file): ?>
            <script src="<?= ASSETS_FULL_URL ?>js/<?= $file ?>?v=<?= PRODUCT_CODE ?>"></script>
        <?php endforeach ?>

        <?= \Altum\Event::get_content('javascript') ?>
    </body>
</html>
