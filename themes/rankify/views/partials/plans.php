<?php defined('ALTUMCODE') || die();
$plans = (new \Altum\Models\Plan())->get_plans();
$premier_plan = $plans[1] ?? null;
$monthly_plan = $plans[2] ?? null;
$yearly_plan = $plans[3] ?? null;

$currency_code = currency();

$is_testing = isset($_GET['testing']) && $_GET['testing'] == '91a7c5b632ef4801';
$testing_query = $is_testing ? '&testing=91a7c5b632ef4801' : '';

$usd_premier_price = $is_testing ? '$0.10' : '$5';
$usd_premier_orig = $is_testing ? '$0.10' : '$10';
$inr_premier_price = $is_testing ? '₹1' : '₹450';
$inr_premier_orig = $is_testing ? '₹1' : '₹900';

$usd_monthly_price = $is_testing ? '$0.10' : '$25';
$usd_monthly_orig = $is_testing ? '$0.10' : '$50';
$inr_monthly_price = $is_testing ? '₹1' : '₹2,100';
$inr_monthly_orig = $is_testing ? '₹1' : '₹4,200';

$usd_yearly_price = $is_testing ? '$0.10' : '$250';
$usd_yearly_orig = $is_testing ? '$0.10' : '$600';
$inr_yearly_price = $is_testing ? '₹1' : '₹21,000';
$inr_yearly_orig = $is_testing ? '₹1' : '₹50,000';

$cur_premier_price = $currency_code === 'INR' ? $inr_premier_price : $usd_premier_price;
$cur_premier_orig = $currency_code === 'INR' ? $inr_premier_orig : $usd_premier_orig;
$cur_monthly_price = $currency_code === 'INR' ? $inr_monthly_price : $usd_monthly_price;
$cur_monthly_orig = $currency_code === 'INR' ? $inr_monthly_orig : $usd_monthly_orig;
$cur_yearly_price = $currency_code === 'INR' ? $inr_yearly_price : $usd_yearly_price;
$cur_yearly_orig = $currency_code === 'INR' ? $inr_yearly_orig : $usd_yearly_orig;
?>

<div class="max-w-7xl mx-auto space-y-8 font-sans">
    <div class="flex justify-center items-center">
        <div class="inline-flex items-center p-1 rounded-full bg-slate-100 border border-slate-200/90 shadow-2xs">
            <button type="button" onclick="setCurrency('USD')" id="curr_btn_usd" class="px-5 py-2 rounded-full text-xs font-bold transition-all border-0 cursor-pointer <?= $currency_code !== 'INR' ? 'bg-white text-slate-900 shadow-sm' : 'bg-transparent text-slate-500 hover:text-slate-900' ?>">
                USD ($)
            </button>
            <button type="button" onclick="setCurrency('INR')" id="curr_btn_inr" class="px-5 py-2 rounded-full text-xs font-bold transition-all border-0 cursor-pointer <?= $currency_code === 'INR' ? 'bg-white text-slate-900 shadow-sm' : 'bg-transparent text-slate-500 hover:text-slate-900' ?>">
                INR (₹)
            </button>
            <input type="checkbox" id="currency_toggle_checkbox" class="sr-only" onchange="toggleCurrencySwitch(this.checked)" <?= $currency_code === 'INR' ? 'checked' : '' ?>>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 items-stretch">
        <!-- Free Starter -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 flex flex-col justify-between shadow-xs hover:border-slate-300 hover:shadow-md transition-all text-left">
            <div class="space-y-4">
                <!-- Card Head (Centered) -->
                <div class="text-center pb-4 border-b border-slate-100 flex flex-col items-center">
                    <span class="inline-block px-3 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[11px] font-extrabold uppercase tracking-wider mb-2">Free Starter</span>
                    <h3 class="text-lg font-black text-slate-900 tracking-tight m-0">Starter Scan</h3>
                    <p class="text-xs text-slate-500 m-0 mt-1 font-medium">Basic crawl metrics &amp; test</p>
                    <div class="mt-3 pt-3 border-t border-slate-100 w-full flex flex-col items-center justify-center">
                        <div class="flex items-baseline gap-1 justify-center">
                            <span class="text-3xl font-black text-slate-900 tracking-tight leading-none">$0</span>
                            <span class="text-xs text-slate-400 font-medium">/forever</span>
                        </div>
                        <span class="text-[11px] text-slate-400 mt-1 font-medium">No credit card required</span>
                    </div>
                </div>

                <div class="space-y-2">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block text-center">Included Features</span>
                    <ul class="space-y-1.5 p-0 m-0 list-none text-xs text-slate-600 font-medium">
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-blue-600 text-xs shrink-0"></i>
                            <span>Basic Technical SEO Audit</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-blue-600 text-xs shrink-0"></i>
                            <span>10 Audits per Month</span>
                        </li>
                        <li class="flex items-center gap-2 text-slate-400">
                            <i class="fas fa-minus text-slate-300 text-xs shrink-0"></i>
                            <span>PDF Downloads Disabled</span>
                        </li>
                        <li class="flex items-center gap-2 text-slate-400">
                            <i class="fas fa-minus text-slate-300 text-xs shrink-0"></i>
                            <span>White-Label Reports Disabled</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="pt-3.5 mt-3.5 border-t border-slate-100">
                <?php if(is_logged_in() && $this->user->plan_id == 'free'): ?>
                    <button type="button" class="w-full py-2.5 px-4 rounded-xl bg-slate-100 text-slate-400 font-bold text-xs cursor-not-allowed border-0 text-center" disabled>Active Plan</button>
                <?php elseif(is_logged_in()): ?>
                    <button type="button" class="w-full py-2.5 px-4 rounded-xl bg-slate-100 text-slate-400 font-bold text-xs cursor-not-allowed border-0 text-center" disabled>Included</button>
                <?php else: ?>
                    <a href="<?= url('register') ?>" class="w-full py-2.5 px-4 rounded-xl bg-slate-900 hover:bg-black text-white font-bold text-xs text-center transition-all text-decoration-none block border-0 shadow-xs">Get Started</a>
                <?php endif ?>
            </div>
        </div>

        <!-- Premier Lifetime -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 flex flex-col justify-between shadow-xs hover:border-slate-300 hover:shadow-md transition-all text-left">
            <div class="space-y-4">
                <!-- Card Head (Centered) -->
                <div class="text-center pb-4 border-b border-slate-100 flex flex-col items-center">
                    <span class="inline-block px-3 py-0.5 rounded-full bg-blue-50 text-blue-600 border border-blue-100 text-[11px] font-extrabold uppercase tracking-wider mb-2">Lifetime Pass</span>
                    <h3 class="text-lg font-black text-slate-900 tracking-tight m-0">Premier Lifetime</h3>
                    <p class="text-xs text-slate-500 m-0 mt-1 font-medium">Pay once, keep forever</p>
                    <div class="mt-3 pt-3 border-t border-slate-100 w-full flex flex-col items-center justify-center">
                        <div class="flex items-baseline gap-2 justify-center">
                            <div class="text-3xl font-black text-slate-900 tracking-tight leading-none" data-currency-val data-usd="<?= $usd_premier_price ?>" data-inr="<?= $inr_premier_price ?>">
                                <?= $cur_premier_price ?>
                            </div>
                            <div class="text-xs text-slate-400 line-through font-semibold" data-currency-val data-usd="<?= $usd_premier_orig ?>" data-inr="<?= $inr_premier_orig ?>">
                                <?= $cur_premier_orig ?>
                            </div>
                        </div>
                        <span class="text-[11px] text-blue-600 font-semibold mt-1">One-time payment • No recurring fee</span>
                    </div>
                </div>

                <div class="space-y-2">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block text-center">Included Features</span>
                    <ul class="space-y-1.5 p-0 m-0 list-none text-xs text-slate-700 font-medium">
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-blue-600 text-xs shrink-0"></i>
                            <span>Full Detailed SEO Audit Report</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-blue-600 text-xs shrink-0"></i>
                            <span><span id="premier_pdf_limit" data-base="1">1</span> Download PDF Report</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-blue-600 text-xs shrink-0"></i>
                            <span><span id="premier_white_label_limit" data-base="1">1</span> White-Label Client Report</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-blue-600 text-xs shrink-0"></i>
                            <span>50 Website Audits per Month</span>
                        </li>
                    </ul>

                    <div class="pt-0.5">
                        <label class="inline-flex items-center gap-2 cursor-pointer text-slate-800 text-[11px] font-semibold select-none m-0 bg-slate-50 hover:bg-slate-100 p-1.5 rounded-lg transition-all border border-slate-200 w-full">
                            <input type="checkbox" class="h-3.5 w-3.5 rounded text-blue-600 focus:ring-blue-500 border-slate-300 shrink-0 cursor-pointer" onchange="toggleLogoAddon(this)" />
                            <span data-currency-val data-usd="Add Extra PDF (+$2)" data-inr="Add Extra PDF (+₹180)">Add Extra PDF (<?= $currency_code === 'INR' ? '+₹180' : '+$2' ?>)</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="pt-2.5 mt-2.5 border-t border-slate-100">
                <?php if(is_logged_in() && $this->user->plan_id == '1'): ?>
                    <a href="<?= url('pay/1?trial_skip=true' . $testing_query) ?>" class="w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs text-center shadow-xs transition-all text-decoration-none block border-0" data-currency-url data-usd-url="<?= url('pay/1?trial_skip=true&currency=USD' . $testing_query) ?>" data-inr-url="<?= url('pay/1?trial_skip=true&currency=INR' . $testing_query) ?>">Buy Again</a>
                <?php elseif(is_logged_in() && in_array($this->user->plan_id, ['2', '3'])): ?>
                    <button type="button" class="w-full py-2.5 px-4 rounded-xl bg-slate-100 text-slate-400 font-bold text-xs cursor-not-allowed border-0 text-center" disabled>Active in Plan</button>
                <?php else: ?>
                    <a href="<?= is_logged_in() ? url('pay/1?trial_skip=true' . $testing_query) : (url('register') . ($is_testing ? '?redirect=plan&testing=91a7c5b632ef4801' : '')) ?>" class="w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs text-center shadow-xs transition-all text-decoration-none block border-0" data-currency-url data-usd-url="<?= is_logged_in() ? url('pay/1?trial_skip=true&currency=USD' . $testing_query) : url('register') ?>" data-inr-url="<?= is_logged_in() ? url('pay/1?trial_skip=true&currency=INR' . $testing_query) : url('register') ?>">Get Premier Lifetime</a>
                <?php endif ?>
            </div>
        </div>

        <!-- Monthly Pro -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 flex flex-col justify-between shadow-xs hover:border-slate-300 hover:shadow-md transition-all text-left">
            <div class="space-y-4">
                <!-- Card Head (Centered) -->
                <div class="text-center pb-4 border-b border-slate-100 flex flex-col items-center">
                    <span class="inline-block px-3 py-0.5 rounded-full bg-blue-50 text-blue-600 border border-blue-100 text-[11px] font-extrabold uppercase tracking-wider mb-2">Monthly Tier</span>
                    <h3 class="text-lg font-black text-slate-900 tracking-tight m-0">Monthly Pro</h3>
                    <p class="text-xs text-slate-500 m-0 mt-1 font-medium">Scale with high volume</p>
                    <div class="mt-3 pt-3 border-t border-slate-100 w-full flex flex-col items-center justify-center">
                        <div class="flex items-baseline gap-2 justify-center">
                            <div class="text-3xl font-black text-slate-900 tracking-tight leading-none" data-currency-val data-usd="<?= $usd_monthly_price ?>" data-inr="<?= $inr_monthly_price ?>">
                                <?= $cur_monthly_price ?>
                            </div>
                            <div class="text-xs text-slate-400 line-through font-semibold" data-currency-val data-usd="<?= $usd_monthly_orig ?>" data-inr="<?= $inr_monthly_orig ?>">
                                <?= $cur_monthly_orig ?>
                            </div>
                            <span class="text-xs text-slate-400 font-medium">/mo</span>
                        </div>
                        <span class="text-[11px] text-slate-500 mt-1 font-medium">Billed monthly • Cancel anytime</span>
                    </div>
                </div>

                <div class="space-y-2">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block text-center">Included Features</span>
                    <ul class="space-y-1.5 p-0 m-0 list-none text-xs text-slate-600 font-medium">
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-blue-600 text-xs shrink-0"></i>
                            <span>Full Detailed SEO Audit Report</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-blue-600 text-xs shrink-0"></i>
                            <span>10 Download PDF Reports</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-blue-600 text-xs shrink-0"></i>
                            <span>10 White-Label Branding Reports</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-blue-600 text-xs shrink-0"></i>
                            <span>100 Website Audits per Month</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="pt-2.5 mt-2.5 border-t border-slate-100">
                <?php if(is_logged_in() && $this->user->plan_id == '2'): ?>
                    <button type="button" class="w-full py-2.5 px-4 rounded-xl bg-slate-100 text-slate-400 font-bold text-xs cursor-not-allowed border-0 text-center" disabled>Active Plan</button>
                <?php elseif(is_logged_in() && $this->user->plan_id == '3'): ?>
                    <button type="button" class="w-full py-2.5 px-4 rounded-xl bg-slate-100 text-slate-400 font-bold text-xs cursor-not-allowed border-0 text-center" disabled>Included in Yearly</button>
                <?php else: ?>
                    <a href="<?= is_logged_in() ? url('pay/2?trial_skip=true' . $testing_query) : (url('register') . ($is_testing ? '?redirect=plan&testing=91a7c5b632ef4801' : '')) ?>" class="w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs text-center shadow-xs transition-all text-decoration-none block border-0">Choose Monthly</a>
                <?php endif ?>
            </div>
        </div>

        <!-- Annual Team -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-5 flex flex-col justify-between shadow-xs hover:border-slate-300 hover:shadow-md transition-all text-left">
            <div class="space-y-4">
                <!-- Card Head (Centered) -->
                <div class="text-center pb-4 border-b border-slate-100 flex flex-col items-center">
                    <span class="inline-block px-3 py-0.5 rounded-full bg-blue-50 text-blue-600 border border-blue-100 text-[11px] font-extrabold uppercase tracking-wider mb-2">Annual Savings</span>
                    <h3 class="text-lg font-black text-slate-900 tracking-tight m-0">Annual Team</h3>
                    <p class="text-xs text-slate-500 m-0 mt-1 font-medium">Full power &amp; max volume</p>
                    <div class="mt-3 pt-3 border-t border-slate-100 w-full flex flex-col items-center justify-center">
                        <div class="flex items-baseline gap-2 justify-center">
                            <div class="text-3xl font-black text-slate-900 tracking-tight leading-none" data-currency-val data-usd="<?= $usd_yearly_price ?>" data-inr="<?= $inr_yearly_price ?>">
                                <?= $cur_yearly_price ?>
                            </div>
                            <div class="text-xs text-slate-400 line-through font-semibold" data-currency-val data-usd="<?= $usd_yearly_orig ?>" data-inr="<?= $inr_yearly_orig ?>">
                                <?= $cur_yearly_orig ?>
                            </div>
                            <span class="text-xs text-slate-400 font-medium">/yr</span>
                        </div>
                        <span class="text-[11px] text-blue-600 font-semibold mt-1">Billed annually • Save over 50%</span>
                    </div>
                </div>

                <div class="space-y-2">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block text-center">Included Features</span>
                    <ul class="space-y-1.5 p-0 m-0 list-none text-xs text-slate-600 font-medium">
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-blue-600 text-xs shrink-0"></i>
                            <span>Full Detailed SEO Audit Report</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-blue-600 text-xs shrink-0"></i>
                            <span>150 Download PDF Reports</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-blue-600 text-xs shrink-0"></i>
                            <span>150 White-Label Client Reports</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-check text-blue-600 text-xs shrink-0"></i>
                            <span>Unlimited Website Audits</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="pt-2.5 mt-2.5 border-t border-slate-100">
                <?php if(is_logged_in() && $this->user->plan_id == '3'): ?>
                    <button type="button" class="w-full py-2.5 px-4 rounded-xl bg-slate-100 text-slate-400 font-bold text-xs cursor-not-allowed border-0 text-center" disabled>Active Plan</button>
                <?php else: ?>
                    <a href="<?= is_logged_in() ? url('pay/3?trial_skip=true' . $testing_query) : (url('register') . ($is_testing ? '?redirect=plan&testing=91a7c5b632ef4801' : '')) ?>" class="w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs text-center shadow-xs transition-all text-decoration-none block border-0">Choose Annual</a>
                <?php endif ?>
            </div>
        </div>
    </div>

    <?php if(\Altum\Router::$controller_key != 'index'): ?>
        <div class="text-center pt-2">
            <button onclick="let el = document.getElementById('featureBreakdownCollapse'); if(el) { el.classList.toggle('hidden'); let icon = this.querySelector('i'); if(icon) { icon.classList.toggle('fa-chevron-down'); icon.classList.toggle('fa-chevron-up'); } }" class="px-5 py-2.5 rounded-full bg-white border border-slate-200 hover:bg-slate-50 text-slate-800 font-bold text-xs shadow-xs transition-all inline-flex items-center gap-2 cursor-pointer" type="button">
                <span>Detailed Feature Comparison</span>
                <i class="fas fa-chevron-down text-[10px]"></i>
            </button>
        </div>

        <div class="hidden mt-4" id="featureBreakdownCollapse">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th class="p-3 font-bold text-slate-900">Features</th>
                            <th class="p-3 font-bold text-slate-900 text-center">Free Starter</th>
                            <th class="p-3 font-bold text-fuchsia-600 text-center">Premier (Lifetime)</th>
                            <th class="p-3 font-bold text-blue-600 text-center">Monthly Pro</th>
                            <th class="p-3 font-bold text-slate-900 text-center">Annual Team</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr>
                            <td class="p-3 font-semibold text-slate-800"><i class="fas fa-search text-blue-600 mr-2"></i> Full SEO Audit</td>
                            <td class="p-3 text-center"><i class="fas fa-check text-blue-600"></i></td>
                            <td class="p-3 text-center"><i class="fas fa-check text-fuchsia-600"></i></td>
                            <td class="p-3 text-center"><i class="fas fa-check text-blue-600"></i></td>
                            <td class="p-3 text-center"><i class="fas fa-check text-blue-600"></i></td>
                        </tr>
                        <tr>
                            <td class="p-3 font-semibold text-slate-800"><i class="fas fa-download text-blue-600 mr-2"></i> PDF Downloads</td>
                            <td class="p-3 text-center text-slate-400">0</td>
                            <td class="p-3 text-center font-bold text-fuchsia-600">1</td>
                            <td class="p-3 text-center font-semibold text-slate-700">10</td>
                            <td class="p-3 text-center font-semibold text-slate-700">150</td>
                        </tr>
                        <tr>
                            <td class="p-3 font-semibold text-slate-800"><i class="fas fa-file-pdf text-blue-600 mr-2"></i> White-Label Reports</td>
                            <td class="p-3 text-center text-slate-400">0</td>
                            <td class="p-3 text-center font-bold text-fuchsia-600">1</td>
                            <td class="p-3 text-center font-semibold text-slate-700">10</td>
                            <td class="p-3 text-center font-semibold text-slate-700">150</td>
                        </tr>
                        <tr>
                            <td class="p-3 font-semibold text-slate-800"><i class="fas fa-globe text-blue-600 mr-2"></i> Monthly Website Scans</td>
                            <td class="p-3 text-center font-semibold text-slate-700">10</td>
                            <td class="p-3 text-center font-bold text-fuchsia-600">50</td>
                            <td class="p-3 text-center font-semibold text-slate-700">100</td>
                            <td class="p-3 text-center font-semibold text-slate-700">Unlimited</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif ?>
</div>

<script>
function setCurrency(curr) {
    const isINR = curr === 'INR';
    const chk = document.getElementById('currency_toggle_checkbox');
    if (chk) chk.checked = isINR;

    const btnUsd = document.getElementById('curr_btn_usd');
    const btnInr = document.getElementById('curr_btn_inr');
    if (btnUsd && btnInr) {
        if (isINR) {
            btnInr.className = "px-4 py-1.5 rounded-full text-xs font-bold transition-all border-0 cursor-pointer bg-white text-slate-900 shadow-sm";
            btnUsd.className = "px-4 py-1.5 rounded-full text-xs font-bold transition-all border-0 cursor-pointer bg-transparent text-slate-500 hover:text-slate-900";
        } else {
            btnUsd.className = "px-4 py-1.5 rounded-full text-xs font-bold transition-all border-0 cursor-pointer bg-white text-slate-900 shadow-sm";
            btnInr.className = "px-4 py-1.5 rounded-full text-xs font-bold transition-all border-0 cursor-pointer bg-transparent text-slate-500 hover:text-slate-900";
        }
    }

    toggleCurrencySwitch(isINR);
}

function toggleCurrencySwitch(isINR) {
    const currency = isINR ? 'INR' : 'USD';
    document.cookie = "set_currency=" + currency + ";path=/;max-age=2592000";

    document.querySelectorAll('[data-currency-val]').forEach(el => {
        const val = isINR ? el.getAttribute('data-inr') : el.getAttribute('data-usd');
        if (val) el.textContent = val;
    });

    document.querySelectorAll('[data-currency-url]').forEach(el => {
        const url = isINR ? el.getAttribute('data-inr-url') : el.getAttribute('data-usd-url');
        if (url) el.href = url;
    });
}

function toggleLogoAddon(checkbox) {
    const card = checkbox.closest('.bg-slate-900, .bg-white');
    if(card) {
        const btn = card.querySelector('a[data-currency-url]');
        if(btn) {
            ['data-usd-url', 'data-inr-url', 'href'].forEach(attr => {
                let currentUrl = btn.getAttribute(attr);
                if(currentUrl) {
                    let urlObj = new URL(currentUrl, window.location.origin);
                    if(checkbox.checked) {
                        urlObj.searchParams.set('logo_addon', '1');
                    } else {
                        urlObj.searchParams.delete('logo_addon');
                    }
                    btn.setAttribute(attr, urlObj.pathname + urlObj.search);
                }
            });
        }
    }

    const pdfLimitEl = document.getElementById('premier_pdf_limit');
    const wlLimitEl = document.getElementById('premier_white_label_limit');
    if(pdfLimitEl && wlLimitEl) {
        const baseLimit = parseInt(pdfLimitEl.getAttribute('data-base') || '1');
        if(checkbox.checked) {
            pdfLimitEl.textContent = baseLimit + 1;
            wlLimitEl.textContent = baseLimit + 1;
        } else {
            pdfLimitEl.textContent = baseLimit;
            wlLimitEl.textContent = baseLimit;
        }
    }
}
</script>
