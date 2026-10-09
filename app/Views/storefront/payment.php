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
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <h2 class="mt-4 text-2xl sm:text-3xl font-extrabold text-gray-900 font-display">
            Processing Payment
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Order <strong class="text-gray-900 font-mono">#<?= htmlspecialchars($orderNumber ?? '') ?></strong> • Total: <strong class="text-[#F25996]">₹<?= number_format((float)($amount ?? 0), 2) ?></strong>
        </p>
        <p class="mt-1 text-xs text-gray-400">
            Connecting to Razorpay secure gateway...
        </p>
    </div>

    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md text-center px-4">
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-100 shadow-sm">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 mx-auto mb-5" style="border-color: #F25996;"></div>
            
            <div id="payment-status-notice" class="hidden mb-4 p-3 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-xl font-medium leading-relaxed"></div>
            
            <button id="pay-btn" class="w-full text-white px-6 py-3 rounded-xl transition-all duration-200 font-bold shadow-md hover:shadow-lg inline-flex items-center justify-center gap-2 transform active:scale-98 cursor-pointer" style="background-color: #F25996;" onmouseover="this.style.backgroundColor='#d8407d'" onmouseout="this.style.backgroundColor='#F25996'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <span>Click here to Proceed to Payment</span>
            </button>
            
            <p class="mt-3 text-[11px] text-gray-400">If the payment window does not open automatically, click the button above.</p>
            
            <div class="mt-5 pt-4 border-t border-gray-100">
                <a href="<?= BASE_URL ?>/cart" class="text-xs font-semibold text-gray-500 hover:text-[#F25996] transition-colors inline-flex items-center gap-1">
                    <span>&larr; Return to Shopping Cart</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================================
     [ARCHIVED] Cashfree JS SDK — Preserved for future use
=========================================================================
<script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cashfree = Cashfree({
            mode: "<?= ($env ?? 'sandbox') === 'sandbox' ? 'sandbox' : 'production' ?>"
        });
        
        const checkoutOptions = {
            paymentSessionId: "<?= htmlspecialchars($paymentSessionId ?? '') ?>",
            redirectTarget: "_self"
        };
        
        const payBtn = document.getElementById('pay-btn');
        if (payBtn) {
            payBtn.addEventListener('click', function() {
                cashfree.checkout(checkoutOptions);
            });
        }

        setTimeout(() => {
            cashfree.checkout(checkoutOptions);
        }, 500);
    });
</script>
========================================================================= -->

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
                    const noticeEl = document.getElementById('payment-status-notice');
                    if (noticeEl) {
                        noticeEl.innerText = 'Payment window closed. Click the button above to retry payment or return to your cart.';
                        noticeEl.classList.remove('hidden');
                    }
                }
            }
        };

        const rzp = new Razorpay(options);

        rzp.on('payment.failed', function (response) {
            console.warn("Razorpay payment attempt failed:", response.error);
            const noticeEl = document.getElementById('payment-status-notice');
            if (noticeEl) {
                const desc = (response.error && response.error.description) ? response.error.description : 'Payment could not be completed. Please choose another method in Razorpay or retry.';
                noticeEl.innerText = desc;
                noticeEl.classList.remove('hidden');
            }
            // Do NOT call alert() or redirect away so the user can use Razorpay's in-modal retry methods (Cards, Netbanking, UPI, Wallet)
        });

        const payBtn = document.getElementById('pay-btn');
        if (payBtn) {
            payBtn.addEventListener('click', function() {
                rzp.open();
            });
        }

        // Automatically trigger Razorpay checkout modal
        setTimeout(function() {
            rzp.open();
        }, 500);
    });
</script>
