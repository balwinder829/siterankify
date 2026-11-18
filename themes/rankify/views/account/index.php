<?php defined('ALTUMCODE') || die() ?>

<div class="max-w-4xl mx-auto space-y-6 font-sans">
    <?= \Altum\Alerts::output_alerts() ?>

    <?= $this->views['account_header_menu'] ?>

    <form action="" method="post" role="form" enctype="multipart/form-data" class="space-y-6">
        <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

        <div>
            <div class="flex items-center gap-2 mb-3">
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight m-0"><?= l('account.settings.header') ?></h1>
                <span data-toggle="tooltip" title="<?= l('account.settings.subheader') ?>">
                    <i class="fas fa-info-circle text-slate-400 text-xs"></i>
                </span>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-4">
                <div class="space-y-1.5" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->main->avatar_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->main->avatar_size_limit) ?>">
                    <label for="avatar" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fas fa-image text-slate-400 mr-1"></i> <?= l('account.settings.avatar') ?></label>
                    <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'users', 'file_key' => 'avatar', 'already_existing_image' => $this->user->avatar, 'input_data' => 'data-crop data-aspect-ratio="1"']) ?>
                    <small class="text-xs text-slate-400 font-medium mt-1 block"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('users')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->main->avatar_size_limit) ?></small>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fas fa-signature text-slate-400 mr-1"></i> <?= l('global.name') ?></label>
                        <input type="text" id="name" name="name" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium <?= \Altum\Alerts::has_field_errors('name') ? 'border-rose-400' : null ?>" value="<?= $this->user->name ?>" maxlength="32" />
                        <?= \Altum\Alerts::output_field_error('name') ?>
                    </div>

                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fas fa-envelope text-slate-400 mr-1"></i> <?= l('global.email') ?></label>
                        <input type="text" id="email" name="email" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium <?= \Altum\Alerts::has_field_errors('email') ? 'border-rose-400' : null ?>" value="<?= $this->user->email ?>" maxlength="128" />
                        <?= \Altum\Alerts::output_field_error('email') ?>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label for="timezone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fas fa-user-clock text-slate-400 mr-1"></i> <?= l('account.settings.timezone') ?></label>
                        <select id="timezone" name="timezone" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium">
                            <?php foreach(DateTimeZone::listIdentifiers() as $timezone) echo '<option value="' . $timezone . '" ' . ($this->user->timezone == $timezone ? 'selected="selected"' : null) . '>' . $timezone . '</option>' ?>
                        </select>
                        <small class="text-xs text-slate-400 font-medium mt-1 block"><?= l('account.settings.timezone_help') ?></small>
                    </div>

                    <div class="space-y-1.5">
                        <label for="anti_phishing_code" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fas fa-user-secret text-slate-400 mr-1"></i> <?= l('account.settings.anti_phishing_code') ?></label>
                        <input type="text" id="anti_phishing_code" name="anti_phishing_code" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium <?= \Altum\Alerts::has_field_errors('anti_phishing_code') ? 'border-rose-400' : null ?>" value="<?= $this->user->anti_phishing_code ?>" maxlength="8" />
                        <?= \Altum\Alerts::output_field_error('anti_phishing_code') ?>
                        <small class="text-xs text-slate-400 font-medium mt-1 block"><?= l('account.settings.anti_phishing_code_help') ?></small>
                    </div>
                </div>

                <?php if(\Altum\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled): ?>
                    <div class="space-y-1.5">
                        <label for="referral_key" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fas fa-wallet text-slate-400 mr-1"></i> <?= l('account.settings.referral_key') ?></label>
                        <div class="flex rounded-xl overflow-hidden border border-slate-200 bg-slate-50">
                            <span class="px-3.5 py-2.5 bg-slate-100 border-r border-slate-200 text-xs text-slate-500 font-semibold select-none"><?= remove_url_protocol_from_url(SITE_URL) . '?ref=' ?></span>
                            <input type="text" id="referral_key" name="referral_key" class="w-full px-3.5 py-2.5 bg-slate-50 text-slate-900 text-sm focus:outline-none focus:bg-white transition-all font-medium border-0" value="<?= $this->user->referral_key ?>" maxlength="32" />
                        </div>
                        <?= \Altum\Alerts::output_field_error('referral_key') ?>
                    </div>
                <?php endif ?>

                <?php if(settings()->users->account_display_newsletter_checkbox): ?>
                    <div class="pt-2">
                        <label class="inline-flex items-center gap-2.5 cursor-pointer text-xs font-bold text-slate-700 select-none m-0">
                            <input id="is_newsletter_subscribed" name="is_newsletter_subscribed" type="checkbox" class="h-4 w-4 rounded text-blue-600 focus:ring-blue-500 border-slate-300 cursor-pointer" <?= $this->user->is_newsletter_subscribed ? 'checked="checked"' : null ?>>
                            <span><i class="fas fa-newspaper text-slate-400 mr-1"></i> <?= l('account.settings.is_newsletter_subscribed') ?></span>
                        </label>
                        <small class="text-xs text-slate-400 font-medium mt-1 block pl-6"><?= l('account.settings.is_newsletter_subscribed_help') ?></small>
                    </div>
                <?php endif ?>
            </div>
        </div>

        <div id="billing" style="<?= !settings()->payment->is_enabled || !settings()->payment->taxes_and_billing_is_enabled ? 'display: none;' : null ?>">
            <div class="flex items-center gap-2 mb-3">
                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight m-0"><?= l('account.billing.header') ?></h2>
                <span data-toggle="tooltip" title="<?= l('account.billing.subheader') ?>">
                    <i class="fas fa-info-circle text-slate-400 text-xs"></i>
                </span>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-4">
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fas fa-briefcase text-slate-400 mr-1"></i> <?= l('account.billing.type') ?></label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl border <?= $this->user->billing->type == 'personal' ? 'border-blue-600 bg-blue-50/40 text-blue-700' : 'border-slate-200 bg-slate-50 text-slate-700' ?> cursor-pointer font-bold text-xs select-none">
                            <input type="radio" name="billing_type" value="personal" class="sr-only" <?= $this->user->billing->type == 'personal' ? 'checked="checked"' : null ?> />
                            <i class="fas fa-user text-xs"></i>
                            <span><?= l('account.billing.type_personal') ?></span>
                        </label>
                        <label class="flex items-center justify-center gap-2 p-2.5 rounded-xl border <?= $this->user->billing->type == 'business' ? 'border-blue-600 bg-blue-50/40 text-blue-700' : 'border-slate-200 bg-slate-50 text-slate-700' ?> cursor-pointer font-bold text-xs select-none">
                            <input type="radio" name="billing_type" value="business" class="sr-only" <?= $this->user->billing->type == 'business' ? 'checked="checked"' : null ?> />
                            <i class="fas fa-building text-xs"></i>
                            <span><?= l('account.billing.type_business') ?></span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label for="billing_name" id="billing_name_label" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fas fa-signature text-slate-400 mr-1"></i> <?= l('account.billing.name') ?></label>
                        <input id="billing_name" type="text" name="billing_name" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium" value="<?= $this->user->billing->name ?>" maxlength="64" />
                    </div>

                    <div class="space-y-1.5" data-billing-type="business">
                        <label for="billing_tax_id" id="billing_tax_id_label" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fas fa-tag text-slate-400 mr-1"></i> <?= !empty(settings()->business->tax_type) ? settings()->business->tax_type : l('account.billing.tax_id') ?></label>
                        <input id="billing_tax_id" type="text" name="billing_tax_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium" value="<?= $this->user->billing->tax_id ?>" maxlength="64" />
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label for="billing_address" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fas fa-map-marker-alt text-slate-400 mr-1"></i> <?= l('account.billing.address') ?></label>
                    <input id="billing_address" type="text" name="billing_address" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium" value="<?= $this->user->billing->address ?>" maxlength="128" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label for="billing_city" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fas fa-city text-slate-400 mr-1"></i> <?= l('global.city') ?></label>
                        <input id="billing_city" type="text" name="billing_city" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium" value="<?= $this->user->billing->city ?>" maxlength="64" />
                    </div>

                    <div class="space-y-1.5" id="billing_state_container" style="display: none;">
                        <label for="billing_state" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fas fa-map text-slate-400 mr-1"></i> <?= l('account.billing.state') ?></label>
                        <select id="billing_state" name="billing_state" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium">
                            <option value=" "><?= l('global.none') ?></option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label for="billing_zip" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fas fa-sort-numeric-up-alt text-slate-400 mr-1"></i> <?= l('account.billing.zip') ?></label>
                        <input id="billing_zip" type="text" name="billing_zip" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium" value="<?= $this->user->billing->zip ?>" maxlength="32" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label for="billing_country" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fas fa-flag text-slate-400 mr-1"></i> <?= l('global.country') ?></label>
                        <select id="billing_country" name="billing_country" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium">
                            <?php foreach(get_countries_array() as $key => $value): ?>
                                <option value="<?= $key ?>" <?= $this->user->billing->country == $key ? 'selected="selected"' : null ?>><?= $value ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label for="billing_phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fas fa-phone text-slate-400 mr-1"></i> <?= l('account.billing.phone') ?></label>
                        <input id="billing_phone" type="text" name="billing_phone" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium" value="<?= $this->user->billing->phone ?>" maxlength="32" />
                    </div>
                </div>
            </div>
        </div>

        <?php ob_start() ?>
        <script>
            'use strict';
            let update_labels = () => {
                let typeEl = document.querySelector('[name="billing_type"]:checked');
                let type = typeEl ? typeEl.value : 'personal';
                let nameLabel = document.querySelector('#billing_name_label');
                let taxLabel = document.querySelector('#billing_tax_id_label');
                if (type == 'personal') {
                    if (nameLabel) nameLabel.innerHTML = '<i class="fas fa-signature text-slate-400 mr-1"></i> <?= l('account.billing.name') ?>';
                } else {
                    if (nameLabel) nameLabel.innerHTML = '<i class="fas fa-signature text-slate-400 mr-1"></i> Company Name';
                    if (taxLabel) taxLabel.innerHTML = '<i class="fas fa-tag text-slate-400 mr-1"></i> GST / Tax ID';
                }
            };

            type_handler('[name="billing_type"]', 'data-billing-type');
            update_labels();
            document.querySelectorAll('[name="billing_type"]').forEach(element => {
                element.addEventListener('change', () => {
                    type_handler('[name="billing_type"]', 'data-billing-type');
                    update_labels();
                });
            });

            <?php if(!empty($this->user->payment_subscription_id)): ?>
            document.querySelectorAll('[name^="billing_"]').forEach(element => {
                element.setAttribute('disabled', 'disabled');
            });
            <?php endif ?>

            const states_data = {
                'US': ['Alabama','Alaska','Arizona','Arkansas','California','Colorado','Connecticut','Delaware','Florida','Georgia','Hawaii','Idaho','Illinois','Indiana','Iowa','Kansas','Kentucky','Louisiana','Maine','Maryland','Massachusetts','Michigan','Minnesota','Mississippi','Missouri','Montana','Nebraska','Nevada','New Hampshire','New Jersey','New Mexico','New York','North Carolina','North Dakota','Ohio','Oklahoma','Oregon','Pennsylvania','Rhode Island','South Carolina','South Dakota','Tennessee','Texas','Utah','Vermont','Virginia','Washington','West Virginia','Wisconsin','Wyoming'],
                'CA': ['Alberta','British Columbia','Manitoba','New Brunswick','Newfoundland and Labrador','Northwest Territories','Nova Scotia','Nunavut','Ontario','Prince Edward Island','Quebec','Saskatchewan','Yukon'],
                'IN': ['Andhra Pradesh','Arunachal Pradesh','Assam','Bihar','Chhattisgarh','Delhi','Goa','Gujarat','Haryana','Himachal Pradesh','Jharkhand','Karnataka','Kerala','Madhya Pradesh','Maharashtra','Manipur','Meghalaya','Mizoram','Nagaland','Odisha','Punjab','Rajasthan','Sikkim','Tamil Nadu','Telangana','Tripura','Uttar Pradesh','Uttarakhand','West Bengal'],
                'BR': ['Acre','Alagoas','Amapá','Amazonas','Bahia','Ceará','Distrito Federal','Espírito Santo','Goiás','Maranhão','Mato Grosso','Mato Grosso do Sul','Minas Gerais','Pará','Paraíba','Paraná','Pernambuco','Piauí','Rio de Janeiro','Rio Grande do Norte','Rio Grande do Sul','Rondônia','Roraima','Santa Catarina','São Paulo','Sergipe','Tocantins']
            };

            const country_select = document.querySelector('#billing_country');
            const state_container = document.querySelector('#billing_state_container');
            const state_select = document.querySelector('#billing_state');
            const saved_state = '<?= $this->user->billing->state ?? '' ?>';

            let state_handler = (selected_country) => {
                if(!state_select) return;
                state_select.innerHTML = '<option value=" "><?= l('global.none') ?></option>';
                if(states_data[selected_country]) {
                    states_data[selected_country].forEach(state => {
                        const is_selected = (state === saved_state) ? 'selected' : '';
                        state_select.innerHTML += `<option value="${state}" ${is_selected}>${state}</option>`;
                    });
                    state_container.style.display = 'block';
                } else {
                    state_container.style.display = 'none';
                }
            };

            if(country_select) {
                state_handler(country_select.value);
                country_select.addEventListener('change', function() {
                    state_handler(this.value);
                });
            }
        </script>
        <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

        <div>
            <div class="flex items-center gap-2 mb-3">
                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight m-0"><?= l('account.change_password.header') ?></h2>
                <span data-toggle="tooltip" title="<?= l('account.change_password.subheader') ?>">
                    <i class="fas fa-info-circle text-slate-400 text-xs"></i>
                </span>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xs space-y-4">
                <div class="space-y-1.5" data-password-toggle-view data-password-toggle-view-show="<?= l('global.show') ?>" data-password-toggle-view-hide="<?= l('global.hide') ?>">
                    <label for="old_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fas fa-unlock text-slate-400 mr-1"></i> <?= l('account.change_password.current_password') ?></label>
                    <input type="password" id="old_password" name="old_password" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium <?= \Altum\Alerts::has_field_errors('old_password') ? 'border-rose-400' : null ?>" />
                    <?= \Altum\Alerts::output_field_error('old_password') ?>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5" data-password-toggle-view data-password-toggle-view-show="<?= l('global.show') ?>" data-password-toggle-view-hide="<?= l('global.hide') ?>">
                        <label for="new_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fas fa-lock text-slate-400 mr-1"></i> <?= l('account.change_password.new_password') ?></label>
                        <input type="password" id="new_password" name="new_password" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium <?= \Altum\Alerts::has_field_errors('new_password') ? 'border-rose-400' : null ?>" />
                        <?= \Altum\Alerts::output_field_error('new_password') ?>
                    </div>

                    <div class="space-y-1.5" data-password-toggle-view data-password-toggle-view-show="<?= l('global.show') ?>" data-password-toggle-view-hide="<?= l('global.hide') ?>">
                        <label for="repeat_password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider"><i class="fas fa-lock text-slate-400 mr-1"></i> <?= l('account.change_password.repeat_password') ?></label>
                        <input type="password" id="repeat_password" name="repeat_password" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium <?= \Altum\Alerts::has_field_errors('repeat_password') ? 'border-rose-400' : null ?>" />
                        <?= \Altum\Alerts::output_field_error('repeat_password') ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" name="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition-all cursor-pointer border-0 inline-flex items-center gap-2">
                <i class="fas fa-check text-[11px]"></i>
                <span><?= l('global.update') ?></span>
            </button>
        </div>
    </form>
</div>

<?php include_view(THEME_PATH . 'views/partials/js_cropper.php') ?>

<?php if(!$this->user->twofa_secret): ?>
    <?php ob_start() ?>
    <script>
        'use strict';
        type_handler('select[name="twofa_is_enabled"]', 'data-twofa-is-enabled');
        document.querySelector('select[name="twofa_is_enabled"]') && document.querySelectorAll('select[name="twofa_is_enabled"]').forEach(element => element.addEventListener('change', () => { type_handler('select[name="twofa_is_enabled"]', 'data-twofa-is-enabled'); }));
    </script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
<?php endif ?>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>
