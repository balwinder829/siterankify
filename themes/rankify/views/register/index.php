<?php defined('ALTUMCODE') || die() ?>

<div class="space-y-5 font-sans">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="space-y-1">
        <h1 class="text-xl font-bold text-gray-900 tracking-tight m-0">Create an Account</h1>
        <p class="text-sm text-gray-500 m-0">Start auditing and monitoring your websites for free</p>
    </div>

    <form action="" method="post" class="space-y-4" role="form">
        <?php if(session_has('otp_register_required')): ?>
            <div class="space-y-1.5">
                <label for="otp_token" class="block text-xs font-semibold text-gray-700">Email Verification Code</label>
                <input id="otp_token" type="text" name="otp_token"
                    class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-900 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/15 transition-all placeholder:text-gray-400 <?= \Altum\Alerts::has_field_errors('otp_token') ? 'border-red-500' : null ?>"
                    required="required" autocomplete="off" autofocus="autofocus" placeholder="123456" maxlength="6" />
                <?= \Altum\Alerts::output_field_error('otp_token') ?>
            </div>

            <button type="submit" name="submit" class="w-full py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm transition-colors cursor-pointer border-0">
                Verify Code
            </button>

            <div class="text-center pt-1">
                <a href="<?= url('register' . $data->redirect_append . (str_contains($data->redirect_append ?? '', '?') ? '&' : '?') . 'restart=true') ?>" class="text-xs text-gray-500 hover:text-blue-600">Cancel and restart</a>
            </div>
        <?php elseif(!settings()->users->register_only_social_logins): ?>

            <div class="space-y-1.5">
                <label for="name" class="block text-xs font-semibold text-gray-700">Full Name</label>
                <input id="name" type="text" name="name"
                    class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-900 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/15 transition-all placeholder:text-gray-400 <?= \Altum\Alerts::has_field_errors('name') ? 'border-red-500' : null ?>"
                    value="<?= $data->values['name'] ?>" maxlength="32" required="required" autofocus="autofocus" placeholder="John Doe" />
                <?= \Altum\Alerts::output_field_error('name') ?>
            </div>

            <div class="space-y-1.5">
                <label for="email" class="block text-xs font-semibold text-gray-700">Email Address</label>
                <input id="email" type="email" name="email"
                    class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-900 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/15 transition-all placeholder:text-gray-400 <?= \Altum\Alerts::has_field_errors('email') ? 'border-red-500' : null ?>"
                    value="<?= $data->values['email'] ?>" maxlength="128" required="required" placeholder="name@company.com" />
                <?= \Altum\Alerts::output_field_error('email') ?>
            </div>

            <div class="space-y-1.5">
                <label for="password" class="block text-xs font-semibold text-gray-700">Password</label>
                <div class="relative">
                    <input id="password" type="password" name="password"
                        class="w-full px-3.5 py-2.5 pr-10 bg-white border border-gray-300 rounded-lg text-gray-900 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/15 transition-all placeholder:text-gray-400 <?= \Altum\Alerts::has_field_errors('password') ? 'border-red-500' : null ?>"
                        value="<?= $data->values['password'] ?>" required="required" placeholder="••••••••" />
                    <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer" onclick="const p = document.getElementById('password'); const icon = this.querySelector('i'); if(p.type==='password'){ p.type='text'; icon.classList.replace('fa-eye', 'fa-eye-slash'); } else { p.type='password'; icon.classList.replace('fa-eye-slash', 'fa-eye'); }">
                        <i class="fas fa-eye text-xs"></i>
                    </button>
                </div>
                <?= \Altum\Alerts::output_field_error('password') ?>
            </div>

            <?php if(settings()->captcha->register_is_enabled): ?>
                <div class="pt-1">
                    <?php $data->captcha->display() ?>
                </div>
            <?php endif ?>

            <div>
                <label class="inline-flex items-start gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="accept" id="accept" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 mt-0.5 cursor-pointer" required="required">
                    <span class="text-xs text-gray-500 leading-normal">
                        I agree to the <a href="<?= settings()->main->terms_and_conditions_url ?: '#' ?>" class="text-blue-600 hover:underline" target="_blank">Terms</a> and <a href="<?= settings()->main->privacy_policy_url ?: '#' ?>" class="text-blue-600 hover:underline" target="_blank">Privacy Policy</a>
                    </span>
                </label>
            </div>

            <button type="submit" name="submit" class="w-full py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm transition-colors cursor-pointer border-0">
                Create Account
            </button>
        <?php endif ?>
    </form>

    <div class="text-center pt-3 border-t border-gray-100 text-sm text-gray-500">
        Already have an account? <a href="<?= url('login' . $data->redirect_append) ?>" class="text-blue-600 font-semibold hover:underline">Sign in</a>
    </div>
</div>
