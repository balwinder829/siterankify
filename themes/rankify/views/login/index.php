<?php defined('ALTUMCODE') || die() ?>

<div class="space-y-5 font-sans">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="space-y-1">
        <h1 class="text-xl font-bold text-gray-900 tracking-tight m-0">Sign In</h1>
        <p class="text-sm text-gray-500 m-0">Enter your credentials to access your account</p>
    </div>

    <form action="<?= url('login') ?>" method="post" class="space-y-4" role="form">
        <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

        <?php if(session_has('twofa_required') && $data->user && $data->user->twofa_secret && $data->user->status == 1): ?>
            <input id="email" type="hidden" name="email" value="<?= $data->user ? $data->values['email'] : null ?>" required="required" />
            <input id="password" type="hidden" name="password" value="<?= $data->user ? $data->values['password'] : null ?>" required="required" />
            <input id="rememberme" type="hidden" name="rememberme" value="<?= $data->values['rememberme'] ? '1' : null ?>">

            <div class="space-y-1.5">
                <label for="twofa_token" class="block text-xs font-semibold text-gray-700">Two-Factor Authentication Code</label>
                <input id="twofa_token" type="text" name="twofa_token"
                    class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-900 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/15 transition-all placeholder:text-gray-400 <?= \Altum\Alerts::has_field_errors('twofa_token') ? 'border-red-500' : null ?>"
                    required="required" autocomplete="off" autofocus="autofocus" placeholder="123456" maxlength="6" />
                <?= \Altum\Alerts::output_field_error('twofa_token') ?>
            </div>

            <button type="submit" name="submit" class="w-full py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm transition-colors cursor-pointer border-0">
                Verify Code
            </button>
        <?php else: ?>

            <div class="space-y-1.5">
                <label for="email" class="block text-xs font-semibold text-gray-700">Email Address</label>
                <input id="email" type="email" name="email"
                    class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-900 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/15 transition-all placeholder:text-gray-400 <?= \Altum\Alerts::has_field_errors('email') ? 'border-red-500' : null ?>"
                    value="<?= $data->values['email'] ?>" required="required" autofocus="autofocus" placeholder="name@company.com" />
                <?= \Altum\Alerts::output_field_error('email') ?>
            </div>

            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-xs font-semibold text-gray-700">Password</label>
                    <a href="<?= url('lost-password' . $data->redirect_append) ?>" class="text-xs text-blue-600 hover:underline">Forgot password?</a>
                </div>
                <div class="relative">
                    <input id="password" type="password" name="password"
                        class="w-full px-3.5 py-2.5 pr-10 bg-white border border-gray-300 rounded-lg text-gray-900 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/15 transition-all placeholder:text-gray-400 <?= \Altum\Alerts::has_field_errors('password') ? 'border-red-500' : null ?>"
                        value="<?= $data->user ? $data->values['password'] : null ?>" required="required" placeholder="••••••••" />
                    <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer" onclick="const p = document.getElementById('password'); const icon = this.querySelector('i'); if(p.type==='password'){ p.type='text'; icon.classList.replace('fa-eye', 'fa-eye-slash'); } else { p.type='password'; icon.classList.replace('fa-eye-slash', 'fa-eye'); }">
                        <i class="fas fa-eye text-xs"></i>
                    </button>
                </div>
                <?= \Altum\Alerts::output_field_error('password') ?>
            </div>

            <?php if(settings()->captcha->login_is_enabled): ?>
                <div class="pt-1">
                    <?php $data->captcha->display() ?>
                </div>
            <?php endif ?>

            <div class="flex items-center justify-between">
                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="rememberme" id="rememberme" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer" <?= $data->values['rememberme'] ? 'checked="checked"' : null ?>>
                    <span class="text-xs text-gray-600 font-medium">Remember me</span>
                </label>
            </div>

            <button type="submit" name="submit" class="w-full py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm transition-colors cursor-pointer border-0">
                Sign In
            </button>
        <?php endif ?>
    </form>

    <?php if(settings()->users->register_is_enabled): ?>
        <div class="text-center pt-3 border-t border-gray-100 text-sm text-gray-500">
            Don't have an account? <a href="<?= url('register' . $data->redirect_append) ?>" class="text-blue-600 font-semibold hover:underline">Sign up free</a>
        </div>
    <?php endif ?>
</div>
