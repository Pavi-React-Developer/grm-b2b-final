<?php
// Retrieve CMS Logo from Navbar or Database
$cmsLogo = '';
if (!empty($components['navbar']['content_data'])) {
    $navData = json_decode($components['navbar']['content_data'], true) ?? [];
    $cmsLogo = $navData['global_settings']['logo'] ?? $navData['logo'] ?? '';
}
if (empty($cmsLogo)) {
    try {
        $db = \Core\Database::getInstance();
        $navStmt = $db->query("SELECT content_data FROM cms_components WHERE section_type = 'navbar' AND is_active = 1 ORDER BY id DESC LIMIT 1");
        $navComp = $navStmt ? $navStmt->fetch(\PDO::FETCH_ASSOC) : null;
        if ($navComp && !empty($navComp['content_data'])) {
            $navData = json_decode($navComp['content_data'], true);
            $cmsLogo = $navData['global_settings']['logo'] ?? $navData['logo'] ?? '';
        }
    } catch (\Throwable $e) {
        // Fallback gracefully
    }
}
if (empty($cmsLogo) && defined('BASE_URL')) {
    $cmsLogo = BASE_URL . '/favicon.svg';
}
?>
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100/60 flex flex-col justify-center py-10 px-4 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-lg text-center">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-50 border border-amber-200/80 text-amber-800 text-xs font-bold tracking-wide mb-3 shadow-xs">
            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
            <span>Order #<?= htmlspecialchars($orderNumber ?? '') ?></span>
        </div>
        <h2 id="page-main-heading" class="text-2xl sm:text-3xl font-black text-gray-900 font-display tracking-tight">
            Processing Payment
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Total Payable: <strong class="text-[#F25996] text-base font-extrabold">₹<?= number_format((float)($amount ?? 0), 2) ?></strong>
        </p>
    </div>

    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-lg text-center">
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-100/80 shadow-xl shadow-gray-200/50 relative overflow-hidden backdrop-blur-sm">
            
            <!-- Default Loading State -->
            <div id="payment-loading-state" class="py-2">
                <div class="relative w-14 h-14 mx-auto mb-4">
                    <div class="animate-spin rounded-full h-14 w-14 border-3 border-pink-100 border-t-[#F25996]"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#F25996]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                </div>
                <p class="text-xs font-semibold text-gray-500 animate-pulse">Connecting to Razorpay secure gateway...</p>
            </div>

            <!-- Modern Pending Alert Box (Activated upon window close or error) -->
            <div id="payment-pending-card" class="hidden text-left mb-6 rounded-2xl bg-gradient-to-br from-amber-50/90 via-orange-50/50 to-pink-50/40 border border-amber-200/80 p-5 shadow-xs transition-all duration-300">
                <div class="flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 shadow-xs border border-amber-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <h3 class="text-sm font-bold text-gray-900 flex items-center gap-1.5">
                                <span>Payment Pending</span>
                                <span class="px-2 py-0.5 rounded-md bg-amber-100 text-amber-800 text-[10px] font-extrabold uppercase">Unpaid</span>
                            </h3>
                            <span class="text-xs font-mono font-bold text-gray-500">#<?= htmlspecialchars($orderNumber ?? '') ?></span>
                        </div>
                        <p id="pending-alert-message" class="mt-1 text-xs text-gray-600 leading-relaxed">
                            The payment window was closed before completion. Your items are safe and saved under your account as a pending order.
                        </p>
                    </div>
                </div>

                <!-- Action Button Matrix -->
                <div class="mt-4 pt-4 border-t border-amber-200/60 grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <button type="button" id="pay-btn" class="w-full text-white px-4 py-2.5 rounded-xl font-bold text-xs shadow-sm hover:shadow-md transition-all duration-200 inline-flex items-center justify-center gap-2 cursor-pointer transform active:scale-98" style="background-color: #F25996;" onmouseover="this.style.backgroundColor='#d8407d'" onmouseout="this.style.backgroundColor='#F25996'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Retry Payment Now</span>
                    </button>
                    <a href="<?= BASE_URL ?>/dashboard/orders" class="w-full bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 px-4 py-2.5 rounded-xl font-bold text-xs shadow-xs hover:border-gray-300 transition-all inline-flex items-center justify-center gap-1.5 text-center">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <span>View in My Orders &rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Standalone Fallback Pay Button (Visible before pending card triggers or if modal blocked) -->
            <div id="fallback-pay-btn-container" class="mb-4">
                <button type="button" id="pay-btn-initial" class="w-full text-white px-6 py-3.5 rounded-2xl transition-all duration-200 font-bold text-sm shadow-md hover:shadow-lg inline-flex items-center justify-center gap-2 transform active:scale-98 cursor-pointer" style="background-color: #F25996;" onmouseover="this.style.backgroundColor='#d8407d'" onmouseout="this.style.backgroundColor='#F25996'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span>Click here to Proceed to Payment</span>
                </button>
                <p class="mt-2.5 text-[11px] text-gray-400">If the payment window does not open automatically, click the button above.</p>
            </div>

            <?php if (str_starts_with(defined('RAZORPAY_KEY_ID') ? RAZORPAY_KEY_ID : '', 'rzp_test_')): ?>
            <!-- Test Mode Credentials helper card -->
            <div class="mt-4 p-3.5 bg-gray-50 border border-gray-200/80 rounded-2xl text-left text-xs text-gray-700">
                <div class="flex items-center gap-1.5 font-bold text-gray-800 mb-1.5">
                    <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Razorpay Test Mode (Domestic Indian Cards Only)</span>
                </div>
                <div class="space-y-1.5 font-mono text-[11px]">
                    <div class="flex items-center justify-between bg-white px-2.5 py-1.5 rounded-lg border border-gray-200">
                        <span><strong>RuPay:</strong> 6070 0000 0000 0014</span>
                        <span class="text-[10px] text-gray-400">Exp: 12/28 | CVV: 123</span>
                    </div>
                    <div class="flex items-center justify-between bg-white px-2.5 py-1.5 rounded-lg border border-gray-200">
                        <span><strong>Visa:</strong> 4315 8100 0000 0000</span>
                        <span class="text-[10px] text-gray-400">Exp: 12/28 | CVV: 123</span>
                    </div>
                    <div class="flex items-center justify-between bg-white px-2.5 py-1.5 rounded-lg border border-gray-200">
                        <span><strong>Test UPI:</strong> success@razorpay</span>
                        <span class="text-[10px] text-emerald-600 font-bold">Instant Pass</span>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Bottom Navigation -->
            <div class="mt-5 pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500 font-semibold">
                <a href="<?= BASE_URL ?>/cart" class="hover:text-[#F25996] transition-colors inline-flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Return to Cart</span>
                </a>
                <a href="<?= BASE_URL ?>/dashboard/orders" class="hover:text-[#F25996] transition-colors inline-flex items-center gap-1">
                    <span>My Order History</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- [ACTIVE] Razorpay Checkout SDK -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const options = {
            "key": "<?= htmlspecialchars($razorpayKeyId ?? (defined('RAZORPAY_KEY_ID') ? RAZORPAY_KEY_ID : '')) ?>",
            "amount": <?= json_encode((int)($amountInPaise ?? (round(($amount ?? 0) * 100)))) ?>,
            "currency": "<?= htmlspecialchars($currency ?? 'INR') ?>",
            "name": "<?= htmlspecialchars(defined('APP_NAME') ? APP_NAME : 'GRM B2B') ?>",
            "description": "Order #<?= htmlspecialchars($orderNumber ?? '') ?>",
            "callback_url": "<?= BASE_URL ?>/checkout/verify?order_id=<?= urlencode($orderNumber ?? '') ?>",
            <?php if (!empty($cmsLogo)): ?>
            "image": "<?= htmlspecialchars($cmsLogo) ?>",
            <?php endif; ?>
            "order_id": "<?= htmlspecialchars($razorpayOrderId ?? '') ?>",
            "handler": function (response) {
                // Submit payment verification details via POST
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?= BASE_URL ?>/checkout/verify';

                const fields = {
                    'order_id': '<?= htmlspecialchars($orderNumber ?? '') ?>',
                    'razorpay_payment_id': response.razorpay_payment_id,
                    'razorpay_order_id': response.razorpay_order_id || '<?= htmlspecialchars($razorpayOrderId ?? '') ?>',
                    'razorpay_signature': response.razorpay_signature
                };

                for (const key in fields) {
                    const hiddenField = document.createElement('input');
                    hiddenField.type = 'hidden';
                    hiddenField.name = key;
                    hiddenField.value = fields[key];
                    form.appendChild(hiddenField);
                }

                document.body.appendChild(form);
                form.submit();
            },
            "prefill": {
                "name": "<?= htmlspecialchars($customerName ?? '') ?>",
                "email": "<?= htmlspecialchars($customerEmail ?? '') ?>",
                "contact": "<?= htmlspecialchars($customerPhone ?? '') ?>"
            },
            "theme": {
                "color": "#F25996"
            },
            "modal": {
                "ondismiss": function() {
                    console.log('Payment modal dismissed');
                    showPendingUI('Payment window was closed before completing transaction. Your order has been saved as Payment Pending.');
                }
            }
        };

        const rzp = new Razorpay(options);

        function showPendingUI(msg) {
            const loadingEl = document.getElementById('payment-loading-state');
            const pendingCard = document.getElementById('payment-pending-card');
            const fallbackBtnContainer = document.getElementById('fallback-pay-btn-container');
            const msgEl = document.getElementById('pending-alert-message');
            const headingEl = document.getElementById('page-main-heading');

            if (loadingEl) loadingEl.classList.add('hidden');
            if (fallbackBtnContainer) fallbackBtnContainer.classList.add('hidden');
            if (headingEl) headingEl.innerText = 'Payment Pending';
            if (msgEl && msg) msgEl.innerText = msg;
            if (pendingCard) pendingCard.classList.remove('hidden');
        }

        rzp.on('payment.failed', function (response) {
            console.warn("Razorpay payment attempt failed:", response.error);
            const desc = (response.error && response.error.description) 
                ? response.error.description 
                : 'Payment could not be completed. You can retry with another method.';
            showPendingUI(desc + ' Your order is saved as Payment Pending.');
        });

        function triggerPayment() {
            rzp.open();
        }

        const payBtn = document.getElementById('pay-btn');
        if (payBtn) payBtn.addEventListener('click', triggerPayment);

        const payBtnInitial = document.getElementById('pay-btn-initial');
        if (payBtnInitial) payBtnInitial.addEventListener('click', triggerPayment);

        // Automatically trigger Razorpay checkout modal after brief load
        setTimeout(function() {
            rzp.open();
        }, 400);
    });
</script>
