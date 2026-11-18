<?php defined('ALTUMCODE') || die() ?>

<style>
body {
    background-color: #f8f9fa;
}
.invoice-wrapper {
    background: #ffffff;
    padding: 40px;
    font-family: Arial, sans-serif;
    color: #000;
    max-width: 850px;
    margin: 0 auto;
    border: 1px solid #ddd;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}
.invoice-header-logo {
    max-height: 80px;
    margin-bottom: 15px;
}
.invoice-title {
    color: #4f81bd;
    font-weight: normal;
    font-size: 1.6rem;
    text-transform: uppercase;
    text-align: right;
    margin-bottom: 10px;
}
.invoice-meta-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}
.invoice-meta-table th, .invoice-meta-table td {
    border: 1px solid #000;
    padding: 6px 12px;
    text-align: center;
    font-size: 0.9rem;
}
.invoice-meta-table th {
    background-color: #f2f2f2;
    font-weight: bold;
}
.section-header-bar {
    background-color: #1b365d;
    color: #ffffff;
    padding: 6px 12px;
    font-weight: bold;
    font-size: 0.95rem;
    text-transform: uppercase;
    margin-bottom: 10px;
}
.bill-to-section {
    font-size: 0.95rem;
    line-height: 1.5;
    margin-bottom: 30px;
    color: #000;
}
.invoice-items-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    border: 1px solid #000;
}
.invoice-items-table th {
    background-color: #1b365d;
    color: #ffffff;
    padding: 10px;
    font-weight: bold;
    text-transform: uppercase;
    font-size: 0.95rem;
    border: 1px solid #000;
}
.invoice-items-table td {
    padding: 10px;
    font-size: 0.95rem;
    border-left: 1px solid #000;
    border-right: 1px solid #000;
    height: 35px;
}
.invoice-items-table tr.empty-row td {
    height: 30px;
}
.invoice-items-table tr.bottom-border td {
    border-bottom: 1px solid #000;
}
.totals-section-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}
.totals-section-table td {
    padding: 6px 12px;
    font-size: 0.95rem;
    color: #000;
}
.totals-section-table tr.total-row {
    background-color: #b4c6e7;
    font-weight: bold;
}
.totals-section-table tr.total-row td {
    border-top: 1px solid #000;
    border-bottom: 1px solid #000;
}
.tax-rate-box {
    border: 1px solid #000;
    padding: 2px 8px;
    background-color: #ffffff;
    font-weight: bold;
    display: inline-block;
    min-width: 80px;
    text-align: right;
}
.other-comments-box {
    border: 1px solid #000;
    min-height: 120px;
    padding: 12px;
    font-size: 0.9rem;
    color: #000;
}
.payment-details-block {
    font-size: 0.9rem;
    line-height: 1.5;
    text-align: left;
    margin-top: 25px;
    color: #000;
}
.invoice-footer {
    margin-top: 40px;
    border-top: 1px dashed #000;
    padding-top: 20px;
    text-align: center;
    font-size: 0.9rem;
    line-height: 1.6;
    color: #000;
}
.thank-you-text {
    font-style: italic;
    font-weight: bold;
    font-size: 1.1rem;
    color: #000;
    margin-top: 10px;
}
@media print {
    body {
        background-color: #fff;
    }
    .invoice-wrapper {
        box-shadow: none;
        border: none;
        padding: 0;
        max-width: 100%;
    }
    .d-print-none {
        display: none !important;
    }
}
</style>

<div class="container my-5">
    <div class="d-print-none d-flex justify-content-between mb-4 max-width-850 mx-auto" style="max-width: 850px;">
        <a href="<?= url('dashboard') ?>" class="btn btn-outline-secondary"><i class="fas fa-arrow-left mr-1"></i> Dashboard</a>
        <div>
            <button type="button" class="btn btn-primary mr-2" onclick="window.print(); return false;"><i class="fas fa-print mr-1"></i> <?= l('invoice.print') ?></button>
            <button type="button" class="btn btn-success" onclick="downloadPDF(); return false;"><i class="fas fa-download mr-1"></i> Download Invoice</button>
        </div>
    </div>

    <div class="invoice-wrapper">
        <!-- Top Header Row -->
        <div class="row align-items-start">
            <!-- Left Header: Brand & Address -->
            <div class="col-7">
                <?php if(settings()->main->logo_light): ?>
                    <img src="<?= settings()->main->logo_light_full_url ?>" class="invoice-header-logo" alt="logo" />
                <?php else: ?>
                    <h2 class="font-weight-bold text-primary mb-2"><?= $data->payment->business->brand_name ?? settings()->business->brand_name ?></h2>
                <?php endif ?>

                <div class="mt-3 mb-3" style="line-height: 1.4; font-size: 0.95rem; color: #000;">
                    <?php if($data->payment->billing): ?>
                        <div class="font-weight-bold"><?= $data->payment->billing->name ?></div>
                        <div><?= $data->payment->billing->address ?></div>
                        <div><?= $data->payment->billing->city ?><?= $data->payment->billing->county ? ', ' . $data->payment->billing->county : '' ?><?= $data->payment->billing->zip ? ' - ' . $data->payment->billing->zip : '' ?></div>
                        <?php if($data->payment->billing->type == 'business' && !empty($data->payment->billing->tax_id)): ?>
                            <div class="font-weight-bold mt-1">GST - <?= $data->payment->billing->tax_id ?></div>
                        <?php endif ?>
                    <?php else: ?>
                        <div class="font-weight-bold"><?= $data->payment->name ?? 'Customer' ?></div>
                        <div><?= $data->payment->email ?></div>
                    <?php endif ?>
                </div>

                <div style="line-height: 1.4; font-size: 0.9rem;">
                    <div><?= $data->payment->business->address ?? '504, Navniti Apartment' ?></div>
                    <div><?= ($data->payment->business->city ?? 'Patparganj Delhi') . ' - ' . ($data->payment->business->zip ?? '92') ?></div>
                    <div>Phone: <i class="fab fa-whatsapp mr-1" style="color: #25d366; font-weight: bold;"></i>+91 7838025290</div>
                    <?php if(!empty($data->payment->business->email)): ?>
                        <div>Email: <?= $data->payment->business->email ?></div>
                    <?php endif ?>
                    <div>Website: <?= $_SERVER['HTTP_HOST'] ?></div>
                </div>
            </div>

            <!-- Right Header: Title & Meta Table -->
            <div class="col-5">
                <div class="invoice-title">TAX Invoice</div>
                <table class="invoice-meta-table">
                    <thead>
                        <tr>
                            <th>DATE</th>
                            <th>INVOICE</th>
                            <th>GST</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= \Altum\Date::get($data->payment->datetime, 1) ?></td>
                            <td><?= $data->payment->business->invoice_nr_prefix . str_pad($data->payment->id, 5, '0', STR_PAD_LEFT) ?></td>
                            <td><?= $data->payment->business->tax_id ?? '07AAFFI2969B1ZH' ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>



        <!-- BILL TO Section -->
        <div class="mt-4">
            <div class="section-header-bar">BILL TO</div>
            <div class="bill-to-section d-flex align-items-stretch">
                <div class="flex-fill pr-4">
                    <?php if($data->payment->billing): ?>
                        <div class="font-weight-bold"><?= $data->payment->billing->name ?></div>
                        <div><?= $data->payment->billing->address ?></div>
                        <div><?= $data->payment->billing->city ?><?= $data->payment->billing->county ? ', ' . $data->payment->billing->county : '' ?><?= $data->payment->billing->zip ? ' - ' . $data->payment->billing->zip : '' ?></div>
                        <?php if($data->payment->billing->type == 'business' && !empty($data->payment->billing->tax_id)): ?>
                            <div class="font-weight-bold mt-1">GST - <?= $data->payment->billing->tax_id ?></div>
                        <?php endif ?>
                    <?php else: ?>
                        <div class="font-weight-bold"><?= $data->payment->name ?? 'Customer' ?></div>
                        <div><?= $data->payment->email ?></div>
                    <?php endif ?>
                </div>
                <div class="pl-4" style="border-left: 2px solid #000; min-width: 300px;">
                    <div class="font-weight-bold">Infotrench Technologies LLP</div>
                    <div>GST: 09AAFFI2969B1ZD</div>
                    <div>Address: Greater Noida WEST</div>
                </div>
            </div>
        </div>

        <!-- Particulars Table -->
        <div class="mt-4">
            <table class="invoice-items-table">
                <thead>
                    <tr>
                        <th style="width: 70%; text-align: left;">PARTICULAR</th>
                        <th style="width: 15%; text-align: center;">TAXED</th>
                        <th style="width: 15%; text-align: right;">AMOUNT</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Item Row -->
                    <tr>
                        <td style="text-align: left; font-weight: bold; vertical-align: top;">
                            <?= sprintf(l('invoice.plan'), $data->payment->plan->name ?? $data->payment->plan_db->name) ?> 
                            (<?= l('invoice.frequency.' . $data->payment->frequency) ?>)
                        </td>
                        <td style="text-align: center; vertical-align: top;">
                            <?php if(!empty($data->payment_taxes)): ?>
                                <i class="fas fa-check text-muted"></i>
                            <?php endif ?>
                        </td>
                        <td style="text-align: right; font-weight: bold; vertical-align: top;">
                            <?= nr($data->payment->base_amount ? $data->payment->base_amount : $data->payment->total_amount, 2) ?>
                        </td>
                    </tr>

                    <!-- Discount Row if exists -->
                    <?php if($data->payment->discount_amount): ?>
                        <tr>
                            <td style="text-align: left; vertical-align: top;">
                                <?= l('invoice.code') ?>: <?= $data->payment->code ?>
                            </td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: right; color: red;">
                                -<?= nr($data->payment->discount_amount, 2) ?>
                            </td>
                        </tr>
                    <?php endif ?>

                    <!-- Empty filler rows for look and feel -->
                    <?php 
                    $filler_count = $data->payment->discount_amount ? 6 : 7;
                    for($i = 0; $i < $filler_count; $i++): 
                    ?>
                    <tr class="empty-row <?= $i == ($filler_count - 1) ? 'bottom-border' : '' ?>">
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php endfor ?>
                </tbody>
            </table>
        </div>

        <!-- Totals & Payments Section -->
        <div class="row mt-4">
            <!-- Left: Other Comments Box -->
            <div class="col-7">
                <div class="section-header-bar">OTHER COMMENTS</div>
                <div class="other-comments-box">
                    <?php if(!empty($this->user) && !empty($this->user->billing->notes)): ?>
                        <?= nl2br($this->user->billing->notes) ?>
                    <?php else: ?>
                        Thank you for your purchase. Please keep this invoice for your records.
                    <?php endif ?>
                </div>
            </div>

            <!-- Right: Calculation details -->
            <div class="col-5">
                <table class="totals-section-table">
                    <tbody>
                        <tr>
                            <td style="text-align: left; color: #000;">Subtotal</td>
                            <td style="text-align: right; font-weight: bold;"><?= nr($data->payment->price, 2) ?></td>
                        </tr>
                        <?php 
                        $tax_rate = 0;
                        $tax_amount = 0;
                        if(!empty($data->payment_taxes)) {
                            foreach($data->payment_taxes as $tax) {
                                $tax_rate += $tax->value;
                                $tax_amount += $tax->amount;
                            }
                        }
                        ?>
                        <tr>
                            <td style="text-align: left; color: #000;">Tax amount</td>
                            <td style="text-align: right; font-weight: bold;"><?= nr($tax_amount, 2) ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: left; color: #000; vertical-align: middle;">Tax rate</td>
                            <td style="text-align: right; padding: 4px 12px;">
                                <div class="tax-rate-box"><?= number_format($tax_rate, 3) ?>%</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: left; color: #000;">Other</td>
                            <td style="text-align: right; font-weight: bold;">-</td>
                        </tr>
                        <tr class="total-row">
                            <td style="text-align: left; text-transform: uppercase;">TOTAL</td>
                            <td style="text-align: right; font-size: 1.1rem;"><?= nr($data->payment->total_amount, 2) ?> <span class="small font-weight-normal"><?= $data->payment->currency ?></span></td>
                        </tr>
                    </tbody>
                </table>

                <div class="payment-details-block">
                    <div class="font-weight-bold" style="color: #000; font-size: 0.95rem;">Make all cheques payable to</div>
                    <div class="font-weight-bold" style="color: #1b365d; font-size: 1rem;"><?= $data->payment->business->name ?? 'Infotrench Technologies LLP' ?></div>
                    <div style="margin-top: 5px;">Account no - 081605009654</div>
                    <div>ifsc code : ICIC0000816</div>
                </div>
            </div>
        </div>

        <!-- Footer Section -->
        <div class="invoice-footer">
            <div>If you have any questions about this invoice, please contact</div>
            <div class="font-weight-bold">[Name: Rahul, Phone #9540041787, info@infotrench.com]</div>
            <div class="thank-you-text">Thank You For Your Business!</div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
function downloadPDF() {
    const element = document.querySelector('.invoice-wrapper');
    const options = {
        margin:       10,
        filename:     'invoice-<?= $data->payment->business->invoice_nr_prefix . str_pad($data->payment->id, 5, '0', STR_PAD_LEFT) ?>.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2, useCORS: true },
        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };
    html2pdf().set(options).from(element).save();
}

<?php if(isset($_GET['download'])): ?>
window.addEventListener('DOMContentLoaded', () => {
    downloadPDF();
});
<?php endif ?>
</script>
