<?php defined('ALTUMCODE') || die() ?>

<div class="space-y-6 font-sans">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="text-center space-y-2">
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
            <?= l('lost_password.header') ?>
        </h1>
        <p class="text-xs text-slate-500 font-medium leading-relaxed">
            <?= l('lost_password.subheader') ?>
        </p>
    </div>

    <form action="" method="post" class="space-y-4" role="form">
        <div class="space-y-1">
            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><?= l('global.email') ?></label>
            <input id="email" type="email" name="email" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-4 focus:ring-blue-500/15 transition-all font-medium <?= \Altum\Alerts::has_field_errors('email') ? 'border-rose-500' : null ?>" value="<?= $data->values['email'] ?>" required="required" autofocus="autofocus" />
            <?= \Altum\Alerts::output_field_error('email') ?>
        </div>

        <?php if(settings()->captcha->lost_password_is_enabled): ?>
            <div class="pt-2">
                <?php $data->captcha->display() ?>
            </div>
        <?php endif ?>

        <button type="submit" name="submit" class="w-full py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-500/20 transition-all cursor-pointer mt-4" <?= isset($_COOKIE['lost_password_lockout']) ? 'disabled="disabled"' : null ?>>
            Send Password Reset Link
        </button>
    </form>

    <div class="pt-4 border-t border-slate-100 text-center text-xs text-slate-500 font-medium">
        <a href="<?= url('login' . $data->redirect_append) ?>" class="text-blue-600 hover:underline font-bold"><?= l('lost_password.return') ?></a>
    </div>
</div>

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
            "name": "<?= l('lost_password.title') ?>",
            "item": "<?= url('lost-password') ?>"
        }
    ]
}
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
