<?php defined('ALTUMCODE') || die() ?>

<div class="max-w-2xl mx-auto py-3 px-4 font-sans">
    <div class="bg-white rounded-3xl border border-slate-200/80 p-8 sm:p-12 text-center shadow-xl space-y-6">
        <?= \Altum\Alerts::output_alerts() ?>

        <div class="h-20 w-20 mx-auto rounded-3xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-4xl shadow-inner border border-emerald-100">
            <i class="fas fa-check-circle animate-pulse"></i>
        </div>

        <div class="space-y-2">
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight"><?= l('pay_thank_you.header') ?></h1>

            <?php if(isset($_GET['code_days'])): ?>
                <p class="text-sm text-slate-500 font-medium"><?= sprintf(l('pay_thank_you.plan_redeemed'), (int) $_GET['code_days']) ?></p>
            <?php elseif(isset($_GET['payment_processor']) && in_array($_GET['payment_processor'], ['paypal', 'stripe', 'coinbase', 'payu', 'paystack', 'razorpay', 'mollie', 'yookassa', 'crypto_com', 'paddle', 'paddle_billing', 'midtrans', 'flutterwave', 'lemonsqueezy', 'myfatoorah', 'klarna', 'plisio'])): ?>
                <p class="text-sm text-slate-500 font-medium flex items-center justify-center gap-2" id="status-message">
                    <span class="h-3 w-3 rounded-full bg-blue-600 animate-ping" id="status-spinner"></span>
                    <span id="status-text"><?= l('pay_thank_you.plan_custom_will_start') ?></span>
                </p>
            <?php elseif(isset($_GET['payment_processor']) && in_array($_GET['payment_processor'], ['offline_payment', 'plisio_whitelabel'])): ?>
                <p class="text-sm text-slate-500 font-medium"><?= l('pay_thank_you.plan_custom_pending') ?></p>
            <?php else: ?>
                <p class="text-sm text-slate-500 font-medium"><?= sprintf(l('pay_thank_you.plan_trial_start'), (int) $_GET['trial_days']) ?></p>
            <?php endif ?>
        </div>

        <div class="pt-4">
            <a id="dashboard" href="<?= url('dashboard') ?>" class="px-8 py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-500/20 transition-all inline-flex items-center gap-2 text-decoration-none">
                <span><?= l('pay_thank_you.button') ?></span>
                <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
    </div>
</div>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL ?>js/libraries/tsparticles.confetti.bundle.min.js?v=<?= PRODUCT_CODE ?>"></script>

<script>
    'use strict';

    let rect = document.querySelector('#dashboard').getBoundingClientRect();

    confetti({
        particleCount: 100,
        spread: 70,
        origin: {
            x: (rect.x + (rect.width / 2)) / window.innerWidth,
            y: rect.y / window.innerHeight
        },
    });

    <?php if(isset($_GET['payment_processor']) && in_array($_GET['payment_processor'], ['paypal', 'stripe', 'coinbase', 'payu', 'paystack', 'razorpay', 'mollie', 'yookassa', 'crypto_com', 'paddle', 'paddle_billing', 'midtrans', 'flutterwave', 'lemonsqueezy', 'myfatoorah', 'klarna', 'plisio'])): ?>
    let attempts = 0;
    let max_attempts = 30;

    let check_payment_status = () => {
        attempts++;

        fetch('<?= url('pay-thank-you/status_ajax?payment_processor=' . $_GET['payment_processor'] . (isset($_GET['unique_id']) ? '&unique_id=' . $_GET['unique_id'] : null)) ?>')
            .then(response => response.json())
            .then(data => {
                if(data.status == 'success') {
                    document.querySelector('#status-spinner').classList.add('hidden');
                    document.querySelector('#status-text').innerHTML = data.message;
                    document.querySelector('#status-message').classList.remove('text-slate-500');
                    document.querySelector('#status-message').classList.add('text-emerald-600', 'font-bold');

                    confetti({
                        particleCount: 100,
                        spread: 70,
                        origin: {
                            x: (rect.x + (rect.width / 2)) / window.innerWidth,
                            y: rect.y / window.innerHeight
                        },
                    });
                } else if(attempts < max_attempts) {
                    setTimeout(check_payment_status, 2000);
                } else {
                    document.querySelector('#status-spinner').classList.add('hidden');
                    document.querySelector('#status-text').innerHTML = '<?= l('pay_thank_you.plan_custom_pending') ?>';
                }
            })
            .catch(error => {
                if(attempts < max_attempts) {
                    setTimeout(check_payment_status, 2000);
                }
            });
    };

    check_payment_status();
    <?php endif ?>
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
