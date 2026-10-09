<div class="mb-8 relative z-10">
    <!-- Breadcrumbs -->
    <div class="text-sm font-medium text-gray-400 mb-2 font-serif tracking-wide">
        Dashboard <span class="mx-1 text-gray-300">›</span> <a href="<?= BASE_URL ?>/admin/finance/payments" class="hover:text-brand-600"><?= $isVendor ? 'Vendor Portal' : 'Finance Management' ?></a> <span class="mx-1 text-gray-300">›</span> <span class="text-brand-700">Ledger Statement</span>
    </div>
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-5xl font-display font-extrabold text-gray-900 tracking-tight">
                <?= $isVendor ? 'Earnings & Payout Ledger Statement' : 'Platform Financial Transactions Ledger' ?>
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                <?= $isVendor ? 'Complete audit trail of all order credits, platform fee deductions, and withdrawal payouts.' : 'Chronological audit ledger of all vendor credits, commissions, and withdrawal payouts.' ?>
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="<?= BASE_URL ?>/admin/finance/payments" class="bg-white hover:bg-gray-50 text-gray-700 px-6 py-3 rounded-full font-bold text-xs border border-gray-200 shadow-sm transition uppercase tracking-wider">
                &larr; Back to Payments
            </a>
        </div>
    </div>
</div>

<?php if (!$isVendor && !empty($vendors)): ?>
<!-- Admin Filter Bar -->
<div class="bg-white p-3 rounded-2xl border border-gray-100 shadow-sm mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex items-center space-x-2">
        <label for="vendor_filter" class="text-xs font-semibold text-gray-500">Filter Ledger by Vendor:</label>
        <select id="vendor_filter" onchange="window.location.href='<?= BASE_URL ?>/admin/finance/transactions' + (this.value ? '?vendor_id=' + this.value : '')" class="px-3 py-1.5 text-xs font-semibold border border-gray-200 rounded-xl bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">All Platform Transactions...</option>
            <?php foreach ($vendors as $v): ?>
                <option value="<?= $v['id'] ?>" <?= (!empty($selectedVendorId) && (int)$selectedVendorId === (int)$v['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($v['store_name'] ?? $v['name']) ?> (ID: <?= htmlspecialchars($v['unique_vendor_id'] ?? 'VN'.$v['id']) ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<?php endif; ?>

<!-- Transactions Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <?php if (empty($transactions)): ?>
        <div class="p-12 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <p class="font-semibold text-gray-600">No ledger transactions recorded yet.</p>
            <p class="text-xs text-gray-400 mt-1">Transactions will record automatically as orders settle and payouts disburse.</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto max-h-[75vh] overflow-y-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead class="sticky top-0 bg-gray-50 z-10 shadow-sm">
                    <tr class="text-gray-500 font-bold uppercase tracking-wider border-b border-gray-100">
                        <th class="py-3.5 px-4">Txn # & Date</th>
                        <?php if (!$isVendor): ?>
                            <th class="py-3.5 px-4">Vendor</th>
                        <?php endif; ?>
                        <th class="py-3.5 px-4">Type</th>
                        <th class="py-3.5 px-4">Description</th>
                        <th class="py-3.5 px-4 text-right">Gross Amount</th>
                        <th class="py-3.5 px-4 text-right">Net Amount</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    <?php foreach ($transactions as $t): ?>
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="py-3.5 px-4">
                                <span class="font-bold font-mono text-indigo-600 text-xs">
                                    <?= htmlspecialchars($t['transaction_number']) ?>
                                </span>
                                <div class="text-[10px] text-gray-400">
                                    <?= date('d M Y, h:i A', strtotime($t['created_at'])) ?>
                                </div>
                            </td>

                            <?php if (!$isVendor): ?>
                                <td class="py-3.5 px-4 font-bold text-gray-900">
                                    <?= htmlspecialchars($t['store_name'] ?? $t['vendor_name']) ?>
                                </td>
                            <?php endif; ?>

                            <td class="py-3.5 px-4">
                                <?php if ($t['type'] === 'payout_withdrawal'): ?>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold bg-purple-50 text-purple-700 px-2 py-0.5 rounded-full border border-purple-200">
                                        💸 Withdrawal Payout
                                    </span>
                                <?php elseif ($t['type'] === 'payout_refund'): ?>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold bg-amber-50 text-amber-700 px-2 py-0.5 rounded-full border border-amber-200">
                                        ↩️ Payout Refund
                                    </span>
                                <?php elseif ($t['type'] === 'commission_fee'): ?>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold bg-rose-50 text-rose-700 px-2 py-0.5 rounded-full border border-rose-200">
                                        🏷️ Commission Fee
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full border border-emerald-200">
                                        🛒 Order Earning
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="py-3.5 px-4 text-gray-700">
                                <?= htmlspecialchars($t['description'] ?? 'Transaction') ?>
                            </td>

                            <td class="py-3.5 px-4 text-right font-bold text-gray-700">
                                ₹<?= number_format($t['gross_amount'], 2) ?>
                            </td>

                            <td class="py-3.5 px-4 text-right font-black text-sm <?= $t['type'] === 'payout_withdrawal' ? 'text-purple-600' : 'text-emerald-600' ?>">
                                <?= $t['type'] === 'payout_withdrawal' ? '-' : '+' ?>₹<?= number_format($t['net_amount'], 2) ?>
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    <?= $t['status'] === 'cleared' ? 'bg-emerald-100 text-emerald-700' : ($t['status'] === 'cancelled' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') ?>">
                                    <?= htmlspecialchars($t['status']) ?>
                                </span>
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                <button onclick="openTxnDetailsModal(<?= htmlspecialchars(json_encode($t), ENT_QUOTES, 'UTF-8') ?>)" 
                                        class="px-2.5 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 hover:text-indigo-900 rounded-lg transition shadow-sm border border-indigo-200/70 inline-flex items-center gap-1.5 font-bold text-[11px]" 
                                        title="View transaction audit details & balance payment">
                                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span>Details</span>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Transaction Audit Details Modal -->
<div id="txn-details-modal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-3 sm:p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border border-gray-100 flex flex-col max-h-[85vh] sm:max-h-[90vh] my-auto relative">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-indigo-700 to-purple-800 text-white shrink-0 sticky top-0 z-20">
            <div class="flex items-center gap-2">
                <span class="text-xl">📊</span>
                <div>
                    <h3 class="font-bold text-base">Transaction Audit Details</h3>
                    <p class="text-[11px] text-indigo-200 font-mono" id="modal_txn_num">TXN-2026-00000</p>
                </div>
            </div>
            <button onclick="closeTxnModal()" class="text-white/80 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <div class="p-6 space-y-4 overflow-y-auto flex-1 min-h-0">
            <!-- Header Summary Card -->
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-3.5 space-y-2">
                <div class="flex items-center justify-between">
                    <span id="modal_txn_type_badge" class="px-2.5 py-1 rounded-full text-[11px] font-bold"></span>
                    <span id="modal_txn_status_badge" class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider"></span>
                </div>
                <div class="text-xs text-gray-500 flex justify-between items-center pt-1 border-t border-slate-200/60">
                    <span>Recorded Date:</span>
                    <span id="modal_txn_date" class="font-semibold text-gray-800"></span>
                </div>
            </div>

            <!-- Vendor Card (if applicable) -->
            <div class="bg-indigo-50/50 border border-indigo-100 rounded-xl p-3.5 text-xs space-y-1.5">
                <span class="text-[10px] uppercase font-bold text-indigo-600 tracking-wider block">Vendor Information</span>
                <div class="flex justify-between items-center">
                    <span class="font-bold text-gray-900 text-sm" id="modal_vendor_name"></span>
                    <span class="font-mono font-bold text-indigo-700 bg-white px-2 py-0.5 rounded border border-indigo-200 text-xs" id="modal_vendor_id"></span>
                </div>
            </div>

            <!-- Financial Impact & Wallet Balance Card -->
            <div class="bg-white border border-gray-200 rounded-xl p-4 space-y-3 shadow-sm">
                <span class="text-xs font-extrabold text-gray-900 uppercase tracking-wider block border-b border-gray-100 pb-1.5">
                    💳 Financial Ledger Breakdown
                </span>

                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div>
                        <span class="text-[10px] font-semibold text-gray-400 block">Gross Transaction Amount</span>
                        <span id="modal_gross_amount" class="font-bold text-gray-800 text-sm"></span>
                    </div>
                    <div>
                        <span class="text-[10px] font-semibold text-gray-400 block">Platform Fee / Comm.</span>
                        <span id="modal_comm_amount" class="font-bold text-rose-600 text-sm"></span>
                    </div>
                    <div class="col-span-2 pt-1 border-t border-gray-100 flex justify-between items-center">
                        <span class="text-xs font-bold text-gray-700">Net Ledger Entry:</span>
                        <span id="modal_net_amount" class="font-black text-base"></span>
                    </div>
                </div>

                <!-- Resulting Wallet Balance Card -->
                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3 flex justify-between items-center mt-2">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-emerald-800 tracking-wider block">Wallet Balance Payment After Txn</span>
                        <span class="text-[10px] text-emerald-600">Available Wallet Balance</span>
                    </div>
                    <span id="modal_balance_after" class="text-lg font-black text-emerald-800 font-mono"></span>
                </div>
            </div>

            <!-- Audit Description Card -->
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-3.5 text-xs space-y-1">
                <span class="text-[10px] uppercase font-bold text-gray-400 block">Audit Description / Reference</span>
                <p id="modal_description" class="text-gray-800 font-medium leading-relaxed"></p>
            </div>
        </div>

        <div class="pt-3 pb-3 flex justify-end px-6 border-t border-gray-100 bg-white shrink-0">
            <button type="button" onclick="closeTxnModal()" class="px-5 py-2 bg-gray-900 hover:bg-gray-800 text-white rounded-xl text-xs font-bold transition shadow-sm">
                Close Audit Details
            </button>
        </div>
    </div>
</div>

<script>
function openTxnDetailsModal(t) {
    document.getElementById('modal_txn_num').innerText = t.transaction_number || 'TXN-N/A';
    if (t.created_at) {
        let d = new Date(t.created_at);
        document.getElementById('modal_txn_date').innerText = d.toLocaleString('en-IN', {
            day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', hour12: true
        });
    } else {
        document.getElementById('modal_txn_date').innerText = 'N/A';
    }
    
    // Vendor info
    document.getElementById('modal_vendor_name').innerText = t.store_name || t.vendor_name || 'In-House';
    document.getElementById('modal_vendor_id').innerText = t.unique_vendor_id || ('VN' + (t.vendor_id || ''));

    // Status badge
    let statusClass = t.status === 'cleared' ? 'bg-emerald-100 text-emerald-700' : (t.status === 'cancelled' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700');
    let statusBadge = document.getElementById('modal_txn_status_badge');
    statusBadge.className = 'px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider ' + statusClass;
    statusBadge.innerText = (t.status || 'CLEARED').toUpperCase();

    // Type badge
    let typeBadge = document.getElementById('modal_txn_type_badge');
    if (t.type === 'payout_withdrawal') {
        typeBadge.className = 'px-2.5 py-1 rounded-full text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-200';
        typeBadge.innerText = '💸 Withdrawal Payout';
    } else if (t.type === 'payout_refund') {
        typeBadge.className = 'px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200';
        typeBadge.innerText = '↩️ Payout Refund';
    } else if (t.type === 'commission_fee') {
        typeBadge.className = 'px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200';
        typeBadge.innerText = '🏷️ Commission Fee';
    } else {
        typeBadge.className = 'px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200';
        typeBadge.innerText = '🛒 Order Earning';
    }

    // Amounts
    document.getElementById('modal_gross_amount').innerText = '₹' + parseFloat(t.gross_amount || 0).toLocaleString('en-IN', {minimumFractionDigits: 2});
    document.getElementById('modal_comm_amount').innerText = '₹' + parseFloat(t.commission_amount || 0).toLocaleString('en-IN', {minimumFractionDigits: 2});
    
    let isNegative = (t.type === 'payout_withdrawal');
    let netElem = document.getElementById('modal_net_amount');
    netElem.className = 'font-black text-base ' + (isNegative ? 'text-purple-600' : 'text-emerald-600');
    netElem.innerText = (isNegative ? '-' : '+') + '₹' + parseFloat(t.net_amount || 0).toLocaleString('en-IN', {minimumFractionDigits: 2});

    // Wallet Balance after transaction
    let balAfter = (t.balance_after !== null && t.balance_after !== undefined && t.balance_after !== '') ? parseFloat(t.balance_after) : null;
    document.getElementById('modal_balance_after').innerText = balAfter !== null ? ('₹' + balAfter.toLocaleString('en-IN', {minimumFractionDigits: 2})) : '₹0.00';

    // Description
    document.getElementById('modal_description').innerText = t.description || 'N/A';

    document.getElementById('txn-details-modal').classList.remove('hidden');
}

function closeTxnModal() {
    document.getElementById('txn-details-modal').classList.add('hidden');
}
</script>
