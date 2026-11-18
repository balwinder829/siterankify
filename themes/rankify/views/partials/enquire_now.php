<?php defined('ALTUMCODE') || die() ?>

<?php if(isset(settings()->socials->whatsapp) && !empty(settings()->socials->whatsapp)): ?>
    <a href="https://wa.me/<?= settings()->socials->whatsapp ?>" target="_blank" class="btn btn-success whatsapp-fab shadow-lg zoom-animation">
        <i class="fab fa-fw fa-whatsapp tst"></i>
    </a>
<?php endif ?>

<?php if(false): ?>
<button type="button" class="btn btn-primary enquire-now-fab shadow-lg zoom-animation" data-toggle="modal" data-target="#enquire_now_modal">
    <i class="fas fa-fw fa-envelope mr-2"></i> <?= l('contact.enquiry') ?>
</button>

<div class="modal fade" id="enquire_now_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= l('contact.enquiry') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div id="enquire_now_response_container"></div>

                <form id="enquire_now_form" action="<?= url('contact') ?>" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />
                    <input type="hidden" name="ajax" value="true" />
                    <input type="hidden" name="source" value="enquire_now" />

                    <div class="form-group">
                        <label for="enquire_name"><?= l('global.name') ?></label>
                        <input id="enquire_name" type="text" name="name" class="form-control" value="<?= is_logged_in() ? $this->user->name : null ?>" minlength="2" maxlength="64" />
                    </div>

                    <div class="form-group">
                        <label for="enquire_email"><?= l('global.email') ?></label>
                        <input id="enquire_email" type="email" name="email" class="form-control" value="<?= is_logged_in() ? $this->user->email : null ?>" maxlength="320" />
                    </div>

                    <div class="form-group">
                        <label for="enquire_subject"><?= l('contact.subject') ?></label>
                        <input id="enquire_subject" type="text" name="subject" class="form-control" minlength="2" maxlength="128" />
                    </div>

                    <div class="form-group">
                        <label for="enquire_message"><?= l('contact.message') ?></label>
                        <textarea id="enquire_message" name="message" class="form-control" rows="4" minlength="10" maxlength="2048"></textarea>
                    </div>

                    <div class="mt-4">
                        <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary" data-is-ajax><?= l('global.submit') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif ?>

<style>
    .enquire-now-fab {
        position: fixed !important;
        bottom: 2rem !important;
        right: 2rem !important;
        z-index: 9999 !important;
        border-radius: 5rem !important;
        padding: 0.75rem 1.5rem !important;
        font-weight: bold !important;
        display: flex !important;
        align-items: center !important;
    }

    .whatsapp-fab {
        position: fixed !important;
        bottom: 5.5rem !important;
        right: 2rem !important;
        z-index: 9999 !important;
        border-radius: 50% !important;
        width: 3.5rem !important;
        height: 3.5rem !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        background-color: #25d366 !important;
        border-color: #25d366 !important;
        color: white !important;
        padding: 0 !important;
        font-size: 1.5rem !important;
    }

    .whatsapp-fab:hover {
        background-color: #128c7e !important;
        border-color: #128c7e !important;
        color: white !important;
    }

    [dir="rtl"] .enquire-now-fab, [dir="rtl"] .whatsapp-fab {
        right: auto !important;
        left: 2rem !important;
    }

    @media (max-width: 767.98px) {
        .whatsapp-fab {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            pointer-events: none !important;
        }
    }
</style>

<?php ob_start() ?>
<script>
    'use strict';

    $('body').on('submit', '#enquire_now_form', event => {
        let form = $(event.currentTarget);
        let submit_button = form.find('button[type="submit"]');
        let container = $('#enquire_now_response_container');

        /* Disable button */
        submit_button.addClass('is-loading').prop('disabled', true);
        container.html('');
        form.find('.is-invalid').removeClass('is-invalid');

        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: form.serialize(),
            dataType: 'json',
            success: (data) => {
                submit_button.removeClass('is-loading').prop('disabled', false);

                if (data.status == 'success') {
                    container.html(`<div class="alert alert-success">${data.message}</div>`);
                    form.find('input[type="text"], input[type="email"], textarea').val('');
                    
                    /* Auto-close modal after 3 seconds */
                    setTimeout(() => {
                        $('#enquire_now_modal').modal('hide');
                        container.html('');
                    }, 3000);
                } else {
                    let errors = '';
                    if (typeof data.message === 'object') {
                        Object.values(data.message).forEach(error => {
                            errors += `<div>${error}</div>`;
                        });
                    } else {
                        errors = data.message;
                    }
                    container.html(`<div class="alert alert-danger">${errors}</div>`);

                    if (data.field_errors && typeof data.field_errors === 'object') {
                        let first_field_name = Object.keys(data.field_errors)[0];
                        if (first_field_name) {
                            let field = form.find(`[name="${first_field_name}"]`);
                            field.addClass('is-invalid');
                            field.trigger('focus');
                        }

                        Object.keys(data.field_errors).forEach(field_name => {
                            form.find(`[name="${field_name}"]`).addClass('is-invalid');
                        });
                    }
                }
            },
            error: () => {
                submit_button.removeClass('is-loading').prop('disabled', false);
                container.html(`<div class="alert alert-danger"><?= l('global.error_message.basic') ?></div>`);
            }
        });

        event.preventDefault();
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

