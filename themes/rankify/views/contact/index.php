<?php defined('ALTUMCODE') || die() ?>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 font-sans space-y-8">
    <?= \Altum\Alerts::output_alerts() ?>

    <!-- Page Header -->
    <div class="space-y-3">
        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 border border-blue-200 text-blue-700 text-xs font-semibold">
            <i class="fas fa-headset text-blue-500 text-[10px]"></i>
            Official Support Desk
        </span>
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 tracking-tight m-0">Let's Connect &amp; Solve Together</h1>
        <p class="text-base text-gray-500 leading-relaxed m-0 max-w-xl">
            Whether you need help with crawler diagnostics, white-label PDF customization, or account queries — our team replies within 24 business hours.
        </p>
        <div class="flex flex-wrap items-center gap-4 pt-1">
            <div class="flex items-center gap-2">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                <span class="text-xs font-semibold text-gray-600">All Diagnostic Crawlers Live</span>
            </div>
            <div class="flex items-center gap-2">
                <i class="fas fa-bolt text-amber-500 text-xs"></i>
                <span class="text-xs font-semibold text-gray-600">&lt; 24h SLA Guarantee</span>
            </div>
        </div>
    </div>

    <!-- Contact Info Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white border border-gray-200 rounded-xl p-5 hover:border-blue-300 hover:shadow-sm transition-all flex flex-col gap-3 group">
            <div class="h-10 w-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-base border border-blue-100 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-900 m-0 mb-1">Email Correspondence</h3>
                <p class="text-xs text-gray-500 m-0 leading-relaxed">Direct inbox for invoice, technical questions, and account verification.</p>
            </div>
            <div class="pt-3 border-t border-gray-100 mt-auto">
                <a href="mailto:<?= settings()->business->email ?>" class="text-sm font-semibold text-blue-600 hover:underline block truncate text-decoration-none">
                    <?= settings()->business->email ?> &rarr;
                </a>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-5 hover:border-green-300 hover:shadow-sm transition-all flex flex-col gap-3 group">
            <div class="h-10 w-10 rounded-lg bg-green-50 text-green-600 flex items-center justify-center text-base border border-green-100 group-hover:bg-green-600 group-hover:text-white transition-colors">
                <i class="fas fa-phone-volume"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-900 m-0 mb-1">Direct Telephone Desk</h3>
                <p class="text-xs text-gray-500 m-0 leading-relaxed">Representatives available Mon–Fri (09:00–18:00 IST).</p>
            </div>
            <div class="pt-3 border-t border-gray-100 mt-auto">
                <a href="tel:<?= settings()->business->phone ?>" class="text-sm font-semibold text-green-600 hover:underline block truncate text-decoration-none">
                    <?= settings()->business->phone ?> &rarr;
                </a>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-5 hover:border-gray-300 hover:shadow-sm transition-all flex flex-col gap-3 group">
            <div class="h-10 w-10 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center text-base border border-gray-200 group-hover:bg-gray-700 group-hover:text-white transition-colors">
                <i class="fas fa-shield-halved"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-900 m-0 mb-1">Security &amp; Agency Licensing</h3>
                <p class="text-xs text-gray-500 m-0 leading-relaxed">White-label partnerships, API enterprise keys, and data compliance inquiries.</p>
            </div>
            <div class="pt-3 border-t border-gray-100 mt-auto">
                <span class="text-xs font-semibold text-gray-600 flex items-center gap-1.5">
                    <i class="fas fa-check-circle text-green-500"></i> Certified Enterprise Platform
                </span>
            </div>
        </div>
    </div>

    <!-- Contact Form + Sidebar -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- Contact Form -->
        <div class="lg:col-span-8 bg-white border border-gray-200 rounded-xl p-6 sm:p-8 space-y-5">
            <div class="pb-4 border-b border-gray-100 space-y-1">
                <h2 class="text-lg font-bold text-gray-900 m-0">Send Us a Message</h2>
                <p class="text-sm text-gray-500 m-0">All inquiries receive timestamped ticketing confirmation.</p>
            </div>

            <div id="contact_response_container"></div>

            <form id="contact_form" action="" method="post" class="space-y-4" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />
                <input type="hidden" name="source" value="contact_page" />
                <input type="hidden" name="ajax" value="true" />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label for="name" class="block text-xs font-semibold text-gray-700"><?= l('contact.name') ?> <span class="text-red-500">*</span></label>
                        <input id="name" type="text" name="name"
                            class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/15 transition-all"
                            value="<?= $data->values['name'] ?>" maxlength="320" placeholder="Your full name" required />
                    </div>
                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-semibold text-gray-700"><?= l('contact.email') ?> <span class="text-red-500">*</span></label>
                        <input id="email" type="email" name="email"
                            class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/15 transition-all"
                            value="<?= $data->values['email'] ?>" maxlength="64" placeholder="your@email.com" required />
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label for="subject" class="block text-xs font-semibold text-gray-700"><?= l('contact.subject') ?> <span class="text-red-500">*</span></label>
                    <input id="subject" type="text" name="subject"
                        class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/15 transition-all"
                        value="<?= $data->values['subject'] ?>" maxlength="128" placeholder="Brief description of your inquiry" required />
                </div>

                <div class="space-y-1.5">
                    <label for="message" class="block text-xs font-semibold text-gray-700"><?= l('contact.message') ?> <span class="text-red-500">*</span></label>
                    <textarea id="message" name="message"
                        class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 text-sm focus:outline-none focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-500/15 transition-all"
                        rows="5" placeholder="Please describe your requirements, site URLs, or technical questions..." required><?= $data->values['message'] ?></textarea>
                </div>

                <div class="pt-3 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-2 text-xs text-gray-400">
                        <i class="fas fa-lock text-gray-400 text-xs"></i>
                        <span>Encrypted &amp; confidential</span>
                    </div>
                    <button type="submit" name="submit"
                        class="w-full sm:w-auto px-7 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm transition-colors cursor-pointer border-0 flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane text-xs"></i>
                        Send Message
                    </button>
                </div>
            </form>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-4 space-y-4">
            <!-- Address -->
            <div class="bg-white border border-gray-200 rounded-xl p-5 space-y-3">
                <div class="flex items-center gap-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <i class="fas fa-building text-blue-600 text-xs"></i>
                    Headquarters
                </div>
                <div class="space-y-2.5 text-sm text-gray-700 leading-relaxed">
                    <div class="flex gap-3">
                        <i class="fas fa-map-pin text-blue-600 text-sm mt-0.5 shrink-0"></i>
                        <span><?= implode(', ', array_filter([settings()->business->address, settings()->business->city, settings()->business->county, settings()->business->zip, settings()->business->country])) ?></span>
                    </div>
                    <div class="flex gap-3">
                        <i class="far fa-clock text-blue-600 text-sm mt-0.5 shrink-0"></i>
                        <span>Mon – Fri, 09:00 – 18:00 IST</span>
                    </div>
                </div>
            </div>

            <!-- Socials -->
            <div class="bg-white border border-gray-200 rounded-xl p-5 space-y-3">
                <div class="flex items-center gap-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <i class="fas fa-share-nodes text-blue-600 text-xs"></i>
                    Follow Us
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <?php if(!empty(settings()->socials->facebook)): ?>
                        <a href="https://www.facebook.com/<?= settings()->socials->facebook ?>" class="h-9 w-9 rounded-lg bg-gray-100 hover:bg-blue-50 hover:text-blue-600 text-gray-600 flex items-center justify-center transition-colors text-decoration-none" target="_blank" rel="noreferrer">
                            <i class="fab fa-facebook text-sm"></i>
                        </a>
                    <?php endif ?>
                    <?php if(!empty(settings()->socials->x)): ?>
                        <a href="https://x.com/<?= settings()->socials->x ?>" class="h-9 w-9 rounded-lg bg-gray-100 hover:bg-blue-50 hover:text-blue-600 text-gray-600 flex items-center justify-center transition-colors text-decoration-none" target="_blank" rel="noreferrer">
                            <i class="fab fa-twitter text-sm"></i>
                        </a>
                    <?php endif ?>
                    <?php if(!empty(settings()->socials->linkedin)): ?>
                        <a href="https://www.linkedin.com/<?= settings()->socials->linkedin ?>" class="h-9 w-9 rounded-lg bg-gray-100 hover:bg-blue-50 hover:text-blue-600 text-gray-600 flex items-center justify-center transition-colors text-decoration-none" target="_blank" rel="noreferrer">
                            <i class="fab fa-linkedin-in text-sm"></i>
                        </a>
                    <?php endif ?>
                    <a href="<?= url() ?>" class="h-9 w-9 rounded-lg bg-gray-100 hover:bg-blue-50 hover:text-blue-600 text-gray-600 flex items-center justify-center transition-colors text-decoration-none" target="_blank" rel="noreferrer">
                        <i class="fas fa-globe text-sm"></i>
                    </a>
                </div>
            </div>

            <!-- Ticket CTA -->
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 space-y-3">
                <div class="flex items-center gap-2">
                    <i class="fas fa-ticket text-blue-600 text-sm"></i>
                    <span class="text-sm font-semibold text-gray-900">Registered Users</span>
                </div>
                <p class="text-xs text-gray-500 m-0 leading-relaxed">
                    Get faster support by submitting a private ticket from your dashboard.
                </p>
                <?php if(is_logged_in()): ?>
                    <a href="<?= url('ticket-create') ?>" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs transition-colors text-decoration-none">
                        Open Private Ticket <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                <?php else: ?>
                    <a href="<?= url('login') ?>" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs transition-colors text-decoration-none">
                        Log In for Priority Support <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                <?php endif ?>
            </div>
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
    form.find('.border-red-400').removeClass('border-red-400');

    $.ajax({
        type: 'POST',
        url: form.attr('action'),
        data: form.serialize(),
        dataType: 'json',
        success: (data) => {
            submit_button.removeClass('is-loading').prop('disabled', false);

            if(data.status == 'success') {
                container.html(`<div class="p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm font-semibold mb-4">${data.message}</div>`);
                form.find('#subject, #message').val('');
            } else {
                let errors = '';
                if(typeof data.message === 'object') {
                    Object.values(data.message).forEach(error => { errors += `<div>${error}</div>`; });
                } else {
                    errors = data.message;
                }
                container.html(`<div class="p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm font-semibold mb-4">${errors}</div>`);

                if(data.field_errors && typeof data.field_errors === 'object') {
                    let first = Object.keys(data.field_errors)[0];
                    if(first) { let f = form.find(`[name="${first}"]`); f.addClass('border-red-400'); f.trigger('focus'); }
                    Object.keys(data.field_errors).forEach(fn => { form.find(`[name="${fn}"]`).addClass('border-red-400'); });
                }
            }
        },
        error: () => {
            submit_button.removeClass('is-loading').prop('disabled', false);
            container.html(`<div class="p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm font-semibold mb-4"><?= l('global.error_message.basic') ?></div>`);
        }
    });

    event.preventDefault();
});
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>