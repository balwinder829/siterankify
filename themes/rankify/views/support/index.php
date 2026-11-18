<?php defined('ALTUMCODE') || die() ?>

<div class="max-w-7xl mx-auto space-y-10 font-sans px-4 sm:px-6 lg:px-8 py-8">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
        <nav aria-label="breadcrumb">
            <ol class="flex flex-wrap items-center gap-2 text-xs text-slate-400 font-semibold mb-2 p-0 list-none">
                <li><a href="<?= url() ?>" class="text-slate-500 hover:text-blue-600 text-decoration-none transition-colors">Home</a></li>
                <li><i class="fas fa-chevron-right text-[10px] text-slate-300"></i></li>
                <li class="text-slate-900 font-bold" aria-current="page">Support Desk</li>
            </ol>
        </nav>
    <?php endif ?>

    <div class="relative overflow-hidden rounded-3xl bg-slate-900 border border-slate-800 p-8 sm:p-12 lg:p-14 text-white shadow-lg">

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
            <div class="space-y-4 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-500/10 border border-blue-400/20 text-blue-300 text-xs font-bold uppercase tracking-wider">
                    <i class="fas fa-life-ring text-blue-400 text-xs"></i>
                    <span>Customer Care &amp; Assistance</span>
                </div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-tight m-0">
                    SiteRankify Support Center
                </h1>
                <p class="text-sm sm:text-base text-slate-300 font-normal m-0 leading-relaxed max-w-xl">
                    Need guidance with technical crawler diagnostics, custom DNS report interpretations, billing questions, or white-label PDF setup? Our engineering specialists are here for you.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <?php if(is_logged_in()): ?>
                    <a href="<?= url('ticket-create') ?>" class="px-6 py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-500/20 transition-all text-decoration-none inline-flex items-center gap-2">
                        <i class="fas fa-plus text-xs"></i>
                        <span>Open Private Ticket</span>
                    </a>
                    <a href="<?= url('tickets') ?>" class="px-5 py-3.5 rounded-xl bg-white/10 hover:bg-white/15 text-white font-bold text-sm backdrop-blur-md border border-white/20 transition-all text-decoration-none inline-flex items-center gap-2">
                        <i class="fas fa-list-check text-xs"></i>
                        <span>View My Tickets</span>
                    </a>
                <?php else: ?>
                    <a href="<?= url('page/faq') ?>" class="px-6 py-3.5 rounded-xl bg-white/10 hover:bg-white/15 text-white font-bold text-sm backdrop-blur-md border border-white/20 transition-all text-decoration-none inline-flex items-center gap-2">
                        <i class="fas fa-circle-question text-xs"></i>
                        <span>Explore FAQ Desk</span>
                    </a>
                <?php endif ?>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-3">
            <div class="h-11 w-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg border border-blue-100">
                <i class="fas fa-bolt"></i>
            </div>
            <h3 class="text-base font-bold text-slate-900 m-0">Rapid Turnaround</h3>
            <p class="text-xs text-slate-500 m-0 leading-relaxed">Our support engineering staff reviews incoming tickets within 24 business hours guaranteed.</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-3">
            <div class="h-11 w-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg border border-indigo-100">
                <i class="fas fa-book-open"></i>
            </div>
            <h3 class="text-base font-bold text-slate-900 m-0">Knowledge Base</h3>
            <p class="text-xs text-slate-500 m-0 leading-relaxed">Instantly resolve standard questions about Core Web Vitals, quotas, and crawling by checking documentation.</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-3">
            <div class="h-11 w-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg border border-emerald-100">
                <i class="fas fa-shield-check"></i>
            </div>
            <h3 class="text-base font-bold text-slate-900 m-0">Direct Verification</h3>
            <p class="text-xs text-slate-500 m-0 leading-relaxed">Private ticket threads are strictly authenticated to prevent credential theft or diagnostic leaks.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-6">
                <div class="space-y-1 pb-4 border-b border-slate-100">
                    <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Direct Assistance</span>
                    <h3 class="text-lg font-bold text-slate-900 m-0">Operational Information</h3>
                </div>

                <div class="space-y-4 text-xs font-semibold text-slate-700">
                    <div class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50 border border-slate-200/80">
                        <span class="text-slate-500">Contact Email:</span>
                        <a href="mailto:<?= get_support_email() ?>" class="text-blue-600 font-bold hover:underline truncate max-w-[180px]"><?= get_support_email() ?></a>
                    </div>
                    <div class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50 border border-slate-200/80">
                        <span class="text-slate-500">Operating Schedule:</span>
                        <span class="text-slate-900 font-bold">Mon – Fri, 09:00 – 18:00</span>
                    </div>
                </div>

                <div class="space-y-3 pt-3 border-t border-slate-100">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Helpful Resources</span>
                    <ul class="space-y-2 p-0 m-0 list-none text-sm font-semibold text-slate-700">
                        <li>
                            <a href="<?= url('page/faq') ?>" class="flex items-center justify-between py-2.5 px-3.5 rounded-xl hover:bg-slate-50 hover:text-blue-600 transition-colors text-decoration-none">
                                <span>Frequently Asked Questions</span>
                                <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                            </a>
                        </li>
                        <li>
                            <a href="<?= url('page/terms') ?>" class="flex items-center justify-between py-2.5 px-3.5 rounded-xl hover:bg-slate-50 hover:text-blue-600 transition-colors text-decoration-none">
                                <span>Terms of Service &amp; Usage</span>
                                <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                            </a>
                        </li>
                        <li>
                            <a href="<?= url('page/privacy-policy') ?>" class="flex items-center justify-between py-2.5 px-3.5 rounded-xl hover:bg-slate-50 hover:text-blue-600 transition-colors text-decoration-none">
                                <span>Privacy &amp; Data Security</span>
                                <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <?php if(settings()->content->pages_share_is_enabled): ?>
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between gap-4">
                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Share Support Link:</span>
                    <div class="flex items-center gap-2">
                        <?= include_view(THEME_PATH . 'views/partials/share_buttons.php', ['url' => url(\Altum\Router::$original_request), 'class' => 'h-9 px-3.5 rounded-xl bg-slate-100 hover:bg-blue-50 text-slate-700 text-xs font-bold inline-flex items-center gap-1.5 border-0 transition-colors', 'copy_to_clipboard' => true]) ?>
                    </div>
                </div>
            <?php endif ?>
        </div>

        <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200 p-7 sm:p-10 shadow-xs space-y-6">
            <div class="space-y-1.5 pb-5 border-b border-slate-100">
                <div class="text-xs font-bold text-blue-600 uppercase tracking-wider">Direct Message</div>
                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight m-0">Submit Support Request</h2>
                <div class="text-sm text-slate-500 leading-relaxed pt-1">
                    <?php if($data->system_page_seo->content): ?>
                        <?= $data->system_page_seo->content ?>
                    <?php else: ?>
                        Before reaching out, check our FAQ section for instant resolutions. For direct assistance, submit the form below and our engineering team will respond directly.
                    <?php endif ?>
                </div>
            </div>

            <div id="contact_response_container"></div>

            <form id="contact_form" action="" method="post" role="form" class="space-y-5">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />
                <input type="hidden" name="source" value="support_page" />
                <input type="hidden" name="ajax" value="true" />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="space-y-2">
                        <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Full Name <span class="text-rose-500">*</span></label>
                        <input id="name" type="text" name="name" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium text-left <?= \Altum\Alerts::has_field_errors('name') ? 'border-rose-400' : null ?>" value="<?= $data->values['name'] ?>" maxlength="320" placeholder="Your Full Name" required />
                        <?= \Altum\Alerts::output_field_error('name') ?>
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Email Address <span class="text-rose-500">*</span></label>
                        <input id="email" type="email" name="email" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium text-left <?= \Altum\Alerts::has_field_errors('email') ? 'border-rose-400' : null ?>" value="<?= $data->values['email'] ?>" maxlength="64" placeholder="your@email.com" required />
                        <?= \Altum\Alerts::output_field_error('email') ?>
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="subject" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Subject <span class="text-rose-500">*</span></label>
                    <input id="subject" type="text" name="subject" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium text-left <?= \Altum\Alerts::has_field_errors('subject') ? 'border-rose-400' : null ?>" value="<?= $data->values['subject'] ?>" maxlength="128" placeholder="Summary of your question or issue..." required />
                    <?= \Altum\Alerts::output_field_error('subject') ?>
                </div>

                <div class="space-y-2" data-character-counter="textarea">
                    <div class="flex items-center justify-between">
                        <label for="message" class="block text-xs font-bold text-slate-700 uppercase tracking-wider m-0">Message <span class="text-rose-500">*</span></label>
                        <span class="text-xs text-slate-400 font-medium" data-character-counter-wrapper></span>
                    </div>
                    <textarea id="message" name="message" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition-all font-medium text-left <?= \Altum\Alerts::has_field_errors('message') ? 'border-rose-400' : null ?>" rows="5" minlength="10" maxlength="2048" placeholder="Please describe your issue or question in detail..." required><?= $data->values['message'] ?></textarea>
                    <?= \Altum\Alerts::output_field_error('message') ?>
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="submit" name="submit" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-500/20 hover:shadow-lg hover:shadow-blue-500/30 transition-all cursor-pointer border-0 flex items-center justify-center gap-2" data-is-ajax>
                        <i class="fas fa-paper-plane text-xs"></i>
                        <span>Send Request</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';

    $('body').on('submit', '#contact_form', event => {
        let form = $(event.currentTarget);
        let submit_button = form.find('button[type="submit"]');
        let container = $('#contact_response_container');

        submit_button.addClass('is-loading').prop('disabled', true);
        container.html('');
        form.find('.border-rose-400').removeClass('border-rose-400');

        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: form.serialize(),
            dataType: 'json',
            success: (data) => {
                submit_button.removeClass('is-loading').prop('disabled', false);

                if (data.status == 'success') {
                    container.html(`<div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold mb-4">${data.message}</div>`);
                    form.find('#subject, #message').val('');
                } else {
                    let errors = '';
                    if (typeof data.message === 'object') {
                        Object.values(data.message).forEach(error => {
                            errors += `<div>${error}</div>`;
                        });
                    } else {
                        errors = data.message;
                    }
                    container.html(`<div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-semibold mb-4">${errors}</div>`);

                    if (data.field_errors && typeof data.field_errors === 'object') {
                        let first_field_name = Object.keys(data.field_errors)[0];
                        if (first_field_name) {
                            let field = form.find(`[name="${first_field_name}"]`);
                            field.addClass('border-rose-400');
                            field.trigger('focus');
                        }

                        Object.keys(data.field_errors).forEach(field_name => {
                            form.find(`[name="${field_name}"]`).addClass('border-rose-400');
                        });
                    }
                }
            },
            error: () => {
                submit_button.removeClass('is-loading').prop('disabled', false);
                container.html(`<div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-semibold mb-4"><?= l('global.error_message.basic') ?></div>`);
            }
        });

        event.preventDefault();
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
