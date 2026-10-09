<div class="mb-8 relative z-10">
    <!-- Breadcrumbs -->
    <div class="text-sm font-medium text-gray-400 mb-2 font-serif tracking-wide">
        Dashboard <span class="mx-1 text-gray-300">›</span> <a href="<?= BASE_URL ?>/admin/finance/payments" class="hover:text-brand-600"><?= $isVendor ? 'Vendor Portal' : 'Finance Management' ?></a> <span class="mx-1 text-gray-300">›</span> <span class="text-brand-700">Withdrawals & Payouts</span>
    </div>
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-5xl font-display font-extrabold text-gray-900 tracking-tight">
                <?= $isVendor ? 'Payouts & Withdrawal History' : 'Vendor Withdrawal Requests & Disbursals' ?>
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                <?= $isVendor ? 'Track your withdrawal requests, processing statuses, and bank transfer UTR references.' : 'Review, approve with UTR reference, or reject vendor withdrawal requests.' ?>
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <?php if ($isVendor): ?>
                <?php if ($summary['can_withdraw']): ?>
                    <button onclick="document.getElementById('vendor-withdraw-modal').classList.remove('hidden')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-full font-bold text-xs shadow-sm transition-all flex items-center gap-2 uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        New Withdrawal Request
                    </button>
                <?php else: ?>
                    <button disabled class="bg-gray-200 text-gray-400 cursor-not-allowed px-6 py-3 rounded-full font-bold text-xs">
                        Min. ₹<?= number_format($summary['min_withdrawal_limit'], 2) ?> to Withdraw
                    </button>
                <?php endif; ?>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/admin/finance/payments" class="bg-white hover:bg-gray-50 text-gray-700 px-6 py-3 rounded-full font-bold text-xs border border-gray-200 shadow-sm transition uppercase tracking-wider">
                    &larr; Back to Payments Overview
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if ($isVendor): ?>
<!-- Vendor Wallet Balance Banner -->
<div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-5 rounded-2xl text-white shadow-md mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <span class="text-xs text-indigo-300 font-bold uppercase tracking-wider block">Available Withdrawable Balance</span>
        <h3 class="text-3xl font-black text-emerald-400 mt-0.5">₹<?= number_format($summary['available_balance'], 2) ?></h3>
        <span class="text-xs text-slate-400">Delivered orders earnings ready for bank payout</span>
    </div>
    <div class="flex items-center space-x-6 text-xs">
        <div>
            <span class="text-slate-400 block">Pending Requests:</span>
            <span class="font-bold text-amber-400">₹<?= number_format($summary['pending_withdrawals'], 2) ?> (<?= (int)$summary['pending_requests_count'] ?> requests)</span>
        </div>
        <div>
            <span class="text-slate-400 block">Total Lifetime Paid:</span>
            <span class="font-bold text-white">₹<?= number_format($summary['total_withdrawn'], 2) ?></span>
        </div>
        <div>
            <span class="text-slate-400 block">Min. Withdrawal Threshold:</span>
            <span class="font-bold text-white">₹<?= number_format($summary['min_withdrawal_limit'], 2) ?></span>
        </div>
    </div>
</div>
<?php else: ?>
<!-- Admin Stats Banner -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center">
        <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Pending Payout Requests</p>
            <h3 class="text-xl font-extrabold text-amber-600">₹<?= number_format($platformSummary['pending_payouts_amount'] ?? 0, 2) ?></h3>
            <span class="text-[10px] text-gray-400"><?= (int)($platformSummary['pending_payouts_count'] ?? 0) ?> requests waiting</span>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center">
        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Disbursed to Vendors</p>
            <h3 class="text-xl font-extrabold text-emerald-600">₹<?= number_format($platformSummary['total_disbursed'] ?? 0, 2) ?></h3>
            <span class="text-[10px] text-gray-400">Completed bank transfers</span>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center">
        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Available In Wallets</p>
            <h3 class="text-xl font-extrabold text-gray-900">₹<?= number_format($platformSummary['total_vendor_available'] ?? 0, 2) ?></h3>
            <span class="text-[10px] text-indigo-600 font-semibold">Eligible for future payouts</span>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Filter Bar -->
<div class="bg-white p-3 rounded-2xl border border-gray-100 shadow-sm mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex items-center space-x-2">
        <?php 
            $baseUrl = $isVendor ? (BASE_URL . '/admin/finance/withdrawals') : (BASE_URL . '/admin/finance/withdrawals' . (!empty($selectedVendorId) ? '?vendor_id=' . $selectedVendorId : ''));
            $sep = (strpos($baseUrl, '?') !== false) ? '&' : '?';
        ?>
        <a href="<?= $baseUrl ?>" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition <?= empty($currentStatus) || $currentStatus === 'all' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">
            All (<?= count($withdrawals) ?>)
        </a>
        <a href="<?= $baseUrl . $sep ?>status=pending" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition <?= $currentStatus === 'pending' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' ?>">
            Pending Approval
        </a>
        <a href="<?= $baseUrl . $sep ?>status=completed" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition <?= $currentStatus === 'completed' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' ?>">
            Completed / Paid
        </a>
        <a href="<?= $baseUrl . $sep ?>status=rejected" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition <?= $currentStatus === 'rejected' ? 'bg-rose-600 text-white' : 'bg-rose-50 text-rose-700 hover:bg-rose-100' ?>">
            Rejected
        </a>
    </div>

    <?php if (!$isVendor && !empty($activeVendors)): ?>
    <div class="flex items-center space-x-2">
        <label for="vendor_filter" class="text-xs font-semibold text-gray-500">Filter Vendor:</label>
        <select id="vendor_filter" onchange="window.location.href='<?= BASE_URL ?>/admin/finance/withdrawals' + (this.value ? '?vendor_id=' + this.value : '')" class="px-3 py-1.5 text-xs font-semibold border border-gray-200 rounded-xl bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">All Vendors...</option>
            <?php foreach ($activeVendors as $v): ?>
                <option value="<?= $v['id'] ?>" <?= (!empty($selectedVendorId) && (int)$selectedVendorId === (int)$v['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($v['store_name'] ?? $v['name']) ?> (ID: <?= htmlspecialchars($v['unique_vendor_id'] ?? 'VN'.$v['id']) ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php endif; ?>
</div>

<!-- Withdrawals Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <?php if (empty($withdrawals)): ?>
        <div class="p-12 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <p class="font-semibold text-gray-600">No withdrawal requests found.</p>
            <p class="text-xs text-gray-400 mt-1"><?= $isVendor ? 'You can submit a withdrawal request whenever your available balance reaches ₹' . number_format($summary['min_withdrawal_limit'], 2) : 'No payout requests have been submitted by vendors yet.' ?></p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 font-bold uppercase tracking-wider border-b border-gray-100">
                        <th class="py-3.5 px-4">Request # & Date</th>
                        <?php if (!$isVendor): ?>
                            <th class="py-3.5 px-4">Vendor / Store</th>
                        <?php endif; ?>
                        <th class="py-3.5 px-4 text-right">Amount</th>
                        <th class="py-3.5 px-4">Payout Account Details</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4">Reference / Notes</th>
                        <?php if (!$isVendor): ?>
                            <th class="py-3.5 px-4 text-center">Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    <?php foreach ($withdrawals as $w): ?>
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="py-3.5 px-4">
                                <span class="font-bold font-mono text-indigo-600 text-xs">
                                    <?= htmlspecialchars($w['payout_number']) ?>
                                </span>
                                <div class="text-[10px] text-gray-400">
                                    <?= date('d M Y, h:i A', strtotime($w['created_at'])) ?>
                                </div>
                            </td>

                            <?php if (!$isVendor): ?>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-gray-900 text-xs flex items-center gap-1.5">
                                        <span>🏢 <?= htmlspecialchars($w['store_name'] ?? $w['vendor_name']) ?></span>
                                    </div>
                                    <div class="text-[11px] font-semibold text-gray-700 mt-0.5">
                                        👤 <?= htmlspecialchars($w['vendor_name']) ?>
                                    </div>
                                    <div class="text-[10px] text-gray-400 font-mono mt-0.5 flex items-center gap-2">
                                        <span>ID: <strong class="text-indigo-600 font-bold"><?= htmlspecialchars($w['unique_vendor_id'] ?? 'VN'.$w['vendor_id']) ?></strong></span>
                                        <?php if (!empty($w['vendor_phone'])): ?>
                                            <span>• 📞 <?= htmlspecialchars($w['vendor_phone']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            <?php endif; ?>

                            <td class="py-3.5 px-4 text-right font-black text-gray-900 text-sm">
                                ₹<?= number_format($w['amount'], 2) ?>
                            </td>

                            <td class="py-3.5 px-4">
                                <?php if ($w['payout_method'] === 'upi'): ?>
                                    <div class="font-bold text-gray-800">📱 UPI: <span class="font-mono text-indigo-600"><?= htmlspecialchars($w['upi_id'] ?? 'N/A') ?></span></div>
                                    <div class="text-[10px] text-gray-500">Holder: <?= htmlspecialchars($w['account_holder_name'] ?? $w['vendor_name']) ?></div>
                                <?php else: ?>
                                    <div class="font-bold text-gray-800">🏦 <?= htmlspecialchars($w['bank_name'] ?? 'Bank Transfer') ?></div>
                                    <div class="text-[11px] text-gray-700 font-mono mt-0.5">
                                        Acc: <strong class="bg-gray-100 px-1.5 py-0.5 rounded text-gray-900"><?= htmlspecialchars($w['account_number'] ?? 'N/A') ?></strong>
                                    </div>
                                    <div class="text-[10px] text-gray-500 font-mono mt-0.5">
                                        IFSC: <strong class="text-indigo-700 uppercase"><?= htmlspecialchars($w['ifsc_code'] ?? 'N/A') ?></strong> • Holder: <strong class="text-gray-800"><?= htmlspecialchars($w['account_holder_name'] ?? $w['vendor_name']) ?></strong>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    <?php if ($w['status'] === 'completed'): ?>
                                        bg-emerald-100 text-emerald-700
                                    <?php elseif ($w['status'] === 'rejected'): ?>
                                        bg-rose-100 text-rose-700
                                    <?php else: ?>
                                        bg-amber-100 text-amber-700
                                    <?php endif; ?>">
                                    <?= htmlspecialchars($w['status']) ?>
                                </span>
                            </td>

                            <td class="py-3.5 px-4">
                                <?php if (!empty($w['transaction_reference'])): ?>
                                    <div class="text-[11px] font-bold text-emerald-700 font-mono">
                                        UTR: <?= htmlspecialchars($w['transaction_reference']) ?>
                                    </div>
                                    <div class="text-[10px] text-gray-400">
                                        Processed: <?= date('d M Y', strtotime($w['processed_at'])) ?>
                                    </div>
                                <?php elseif (!empty($w['admin_notes'])): ?>
                                    <div class="text-[11px] text-rose-600 italic">
                                        <?= htmlspecialchars($w['admin_notes']) ?>
                                    </div>
                                <?php elseif (!empty($w['vendor_notes'])): ?>
                                    <div class="text-[11px] text-gray-500 italic">
                                        <?= htmlspecialchars($w['vendor_notes']) ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-[10px] text-gray-400 italic">Awaiting review</span>
                                <?php endif; ?>
                            </td>

                            <?php if (!$isVendor): ?>
                                <td class="py-3.5 px-4 text-center">
                                    <?php if ($w['status'] === 'pending'): ?>
                                        <div class="flex items-center justify-center space-x-1.5">
                                            <button onclick="openApproveModal(<?= htmlspecialchars(json_encode($w)) ?>)" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[10px] rounded-lg transition shadow-sm flex items-center gap-1" title="Approve and enter bank UTR">
                                                <span>Approve & Pay</span>
                                            </button>
                                            <button onclick="openRejectModal(<?= htmlspecialchars(json_encode($w)) ?>)" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-[10px] rounded-lg border border-rose-200 transition" title="Reject request">
                                                Reject
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-[10px] text-gray-400">Settled</span>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Admin Approve Payout Modal -->
<?php if (!$isVendor): ?>
<div id="approve-payout-modal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-3 sm:p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-100 flex flex-col max-h-[85vh] sm:max-h-[90vh] my-auto relative">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-emerald-600 to-teal-700 text-white shrink-0 sticky top-0 z-20">
            <div class="flex items-center gap-2">
                <span class="text-xl">💳</span>
                <h3 class="font-bold text-base">Approve & Disburse Vendor Payout</h3>
            </div>
            <button onclick="document.getElementById('approve-payout-modal').classList.add('hidden')" class="text-white/80 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="<?= BASE_URL ?>/admin/finance/withdrawals/process" method="POST" class="p-6 space-y-4 overflow-y-auto flex-1 min-h-0">
            <input type="hidden" name="payout_id" id="approve_payout_id">
            <input type="hidden" name="action" value="approve">

            <!-- Request & Vendor Card -->
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-3.5 text-xs text-gray-800 space-y-2">
                <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-gray-400 block">Payout Request #</span>
                        <span id="approve_payout_num" class="font-mono font-bold text-indigo-700 text-sm"></span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] uppercase font-bold text-gray-400 block">Vendor ID</span>
                        <span id="approve_vendor_id" class="font-mono font-bold text-gray-900 text-xs"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 text-xs pt-1">
                    <div>
                        <span class="text-[10px] font-semibold text-gray-500 block">Store Name</span>
                        <span id="approve_store_name" class="font-bold text-gray-900"></span>
                    </div>
                    <div>
                        <span class="text-[10px] font-semibold text-gray-500 block">Owner / Contact Name</span>
                        <span id="approve_owner_name" class="font-bold text-gray-900"></span>
                    </div>
                    <div class="col-span-2">
                        <span class="text-[10px] font-semibold text-gray-500 block">Contact Info</span>
                        <span id="approve_contact_info" class="text-gray-700"></span>
                    </div>
                </div>

                <div class="flex justify-between items-center bg-emerald-100/80 border border-emerald-200 rounded-lg p-2.5 text-emerald-950 font-bold mt-2">
                    <span class="text-xs uppercase tracking-wider">Amount to Transfer:</span>
                    <span id="approve_amount" class="text-lg font-black text-emerald-700"></span>
                </div>
            </div>

            <!-- Beneficiary Bank Details Card -->
            <div class="bg-emerald-50/70 border border-emerald-200 rounded-xl p-3.5 space-y-2">
                <div class="flex items-center justify-between border-b border-emerald-200/80 pb-1.5">
                    <span class="text-xs font-extrabold text-emerald-900 uppercase tracking-wider flex items-center gap-1.5">
                        <span>🏦 Target Beneficiary Bank Details</span>
                    </span>
                    <span class="text-[10px] font-bold text-emerald-700 bg-white px-2 py-0.5 rounded border border-emerald-200" id="approve_payout_method_badge">Bank Transfer</span>
                </div>

                <div id="approve_bank_card_body" class="space-y-2 text-xs">
                    <!-- Dynamic content injected by JS -->
                </div>
            </div>

            <div>
                <label for="transaction_reference" class="block text-xs font-bold text-gray-800 mb-1">
                    Bank Transaction Reference / UTR Number <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="transaction_reference" name="transaction_reference" required placeholder="e.g. UTR1234567890 / IMPS20260923" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-xs font-mono font-bold uppercase focus:ring-emerald-500 focus:border-emerald-500 bg-white">
                <p class="text-[10px] text-gray-400 mt-1">Enter official UTR or transaction ref code generated from your corporate netbanking portal after executing transfer.</p>
            </div>

            <div>
                <label for="approve_admin_notes" class="block text-xs font-semibold text-gray-600 mb-1">Admin Notes (Optional)</label>
                <textarea id="approve_admin_notes" name="admin_notes" rows="2" placeholder="e.g. Disbursed via ICICI Bank Corporate Portal" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-emerald-500 focus:border-emerald-500"></textarea>
            </div>

            <div class="pt-3 pb-1 flex justify-end space-x-3 border-t border-gray-100 sticky bottom-0 bg-white z-10 -mx-6 px-6 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                <button type="button" onclick="document.getElementById('approve-payout-modal').classList.add('hidden')" class="px-4 py-2 border border-gray-200 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-50 transition">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-md flex items-center gap-1.5">
                    <span>Confirm & Complete Payout</span>
                    <span>&rarr;</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Admin Reject Payout Modal -->
<div id="reject-payout-modal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-3 sm:p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border border-gray-100 flex flex-col max-h-[85vh] sm:max-h-[90vh] my-auto relative">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-rose-600 text-white shrink-0 sticky top-0 z-20">
            <h3 class="font-bold text-base">Reject Withdrawal Request</h3>
            <button onclick="document.getElementById('reject-payout-modal').classList.add('hidden')" class="text-white/80 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="<?= BASE_URL ?>/admin/finance/withdrawals/process" method="POST" class="p-6 space-y-4 overflow-y-auto flex-1 min-h-0">
            <input type="hidden" name="payout_id" id="reject_payout_id">
            <input type="hidden" name="action" value="reject">

            <p class="text-xs text-gray-600">
                Rejecting this request will immediately restore the funds back into the vendor's available wallet balance.
            </p>

            <div>
                <label for="reject_admin_notes" class="block text-xs font-bold text-gray-700 mb-1">Reason for Rejection *</label>
                <textarea id="reject_admin_notes" name="admin_notes" rows="3" required placeholder="e.g. Invalid bank account number or IFSC code provided. Please update profile." class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-rose-500 focus:border-rose-500"></textarea>
            </div>

            <div class="pt-3 pb-1 flex justify-end space-x-3 border-t border-gray-100 sticky bottom-0 bg-white z-10 -mx-6 px-6 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                <button type="button" onclick="document.getElementById('reject-payout-modal').classList.add('hidden')" class="px-4 py-2 border border-gray-200 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-50 transition">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition shadow">Reject & Refund to Wallet</button>
            </div>
        </form>
    </div>
</div>

<script>
function openApproveModal(payout) {
    document.getElementById('approve_payout_id').value = payout.id;
    document.getElementById('approve_payout_num').innerText = payout.payout_number;
    
    document.getElementById('approve_vendor_id').innerText = payout.unique_vendor_id || ('VN' + payout.vendor_id);
    document.getElementById('approve_store_name').innerText = payout.store_name || payout.vendor_name || 'N/A';
    document.getElementById('approve_owner_name').innerText = payout.vendor_name || 'N/A';
    
    let contactParts = [];
    if (payout.vendor_email) contactParts.push('📧 ' + payout.vendor_email);
    if (payout.vendor_phone) contactParts.push('📞 ' + payout.vendor_phone);
    document.getElementById('approve_contact_info').innerText = contactParts.join(' • ') || 'N/A';

    document.getElementById('approve_amount').innerText = '₹' + parseFloat(payout.amount).toLocaleString('en-IN', {minimumFractionDigits: 2});
    
    let isUpi = (payout.payout_method === 'upi');
    document.getElementById('approve_payout_method_badge').innerText = isUpi ? 'UPI Transfer' : 'Bank Transfer';
    
    let cardHtml = '';
    if (isUpi) {
        cardHtml = `
            <div class="grid grid-cols-1 gap-1.5">
                <div>
                    <span class="text-[10px] font-semibold text-gray-500 block">Beneficiary Name</span>
                    <span class="font-bold text-gray-900">${payout.account_holder_name || payout.vendor_name || 'N/A'}</span>
                </div>
                <div>
                    <span class="text-[10px] font-semibold text-gray-500 block">UPI ID / VPA</span>
                    <span class="font-mono font-bold text-indigo-700 bg-white px-2 py-1 rounded border border-indigo-200 inline-block text-xs">${payout.upi_id || 'N/A'}</span>
                </div>
            </div>
        `;
    } else {
        cardHtml = `
            <div>
                <span class="text-[10px] font-semibold text-gray-500 block">Account Holder / Beneficiary Name</span>
                <span class="font-extrabold text-gray-900 text-xs">${payout.account_holder_name || payout.vendor_name || 'N/A'}</span>
            </div>
            <div class="grid grid-cols-2 gap-2 pt-1">
                <div>
                    <span class="text-[10px] font-semibold text-gray-500 block">Bank Name</span>
                    <span class="font-bold text-gray-800">${payout.bank_name || 'Bank Transfer'}</span>
                </div>
                <div>
                    <span class="text-[10px] font-semibold text-gray-500 block">IFSC Code</span>
                    <span class="font-mono font-extrabold text-indigo-800 bg-white px-2 py-0.5 rounded border border-indigo-200 uppercase inline-block text-xs">${payout.ifsc_code || 'N/A'}</span>
                </div>
                <div class="col-span-2">
                    <span class="text-[10px] font-semibold text-gray-500 block">Account Number</span>
                    <span class="font-mono font-black text-gray-900 bg-white px-2.5 py-1 rounded border border-gray-300 inline-block text-xs tracking-wider">${payout.account_number || 'N/A'}</span>
                </div>
            </div>
        `;
    }
    document.getElementById('approve_bank_card_body').innerHTML = cardHtml;
    
    document.getElementById('approve-payout-modal').classList.remove('hidden');
}

function openRejectModal(payout) {
    document.getElementById('reject_payout_id').value = payout.id;
    document.getElementById('reject-payout-modal').classList.remove('hidden');
}
</script>
<?php endif; ?>

<!-- Vendor Withdrawal Modal for direct launch on this page -->
<?php if ($isVendor): ?>
<div id="vendor-withdraw-modal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-3 sm:p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-100 flex flex-col max-h-[85vh] sm:max-h-[90vh] my-auto relative">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-emerald-600 to-teal-700 text-white shrink-0 sticky top-0 z-20">
            <h3 class="font-bold text-base">Request Fund Withdrawal</h3>
            <button onclick="document.getElementById('vendor-withdraw-modal').classList.add('hidden')" class="text-white/80 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="<?= BASE_URL ?>/admin/finance/withdrawals/request" method="POST" class="p-6 space-y-4 overflow-y-auto flex-1 min-h-0">
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3.5 flex items-center justify-between">
                <div>
                    <span class="text-xs text-emerald-700 font-semibold block">Available Withdrawable Balance:</span>
                    <span class="text-xl font-extrabold text-emerald-800">₹<?= number_format($summary['available_balance'], 2) ?></span>
                </div>
                <div class="text-right">
                    <span class="text-[10px] text-gray-500 block">Admin Withdrawal Limit:</span>
                    <span class="text-xs font-bold text-emerald-700">₹<?= number_format($summary['min_withdrawal_limit'], 2) ?></span>
                </div>
            </div>

            <div>
                <label for="v_amount" class="block text-sm font-semibold text-gray-700 mb-1">Withdrawal Amount (₹) *</label>
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-gray-400 font-bold">₹</span>
                    <input type="text" step="0.01" min="1" max="<?= $summary['min_withdrawal_limit'] ?>" id="v_amount" name="amount" value="<?= min($summary['min_withdrawal_limit'], $summary['available_balance']) ?>" required class="w-full pl-8 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-base font-bold text-gray-900">
                </div>
                <p class="text-[11px] text-rose-600 mt-1 font-semibold">
                    🛡️ Admin Configured Withdrawal Limit: <strong>₹<?= number_format($summary['min_withdrawal_limit'], 2) ?></strong> max per request.
                </p>
            </div>

            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-700 block">Beneficiary Bank Account:</span>
                    <a href="<?= BASE_URL ?>/admin/vendor/settings" class="text-[11px] text-emerald-700 hover:text-emerald-900 font-bold hover:underline flex items-center gap-1">
                        ⚙️ Manage Accounts
                    </a>
                </div>

                <?php if (!empty($savedBankAccounts) && count($savedBankAccounts) > 1): ?>
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-500 mb-1">Select Bank Account:</label>
                        <select onchange="handleWithdrawBankSelect(this)" class="w-full px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-semibold bg-white text-gray-800">
                            <?php foreach ($savedBankAccounts as $b): ?>
                                <option value='<?= htmlspecialchars(json_encode($b), ENT_QUOTES) ?>' <?= !empty($b['is_primary']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($b['bank_name']) ?> (<?= htmlspecialchars($b['account_number']) ?>) <?= !empty($b['is_primary']) ? '— [Active Primary]' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <?php 
                    $selectedBank = $primaryAccount ?: ($savedBankAccounts[0] ?? $vendorProfile);
                ?>
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-500 mb-0.5">Bank Name *</label>
                        <input type="text" id="w_bank_name" name="bank_name" value="<?= htmlspecialchars($selectedBank['bank_name'] ?? '') ?>" required placeholder="e.g. HDFC Bank" class="w-full px-2.5 py-1.5 border border-gray-200 rounded-lg text-xs font-semibold bg-white">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-500 mb-0.5">Account Number *</label>
                        <input type="text" id="w_acc_no" name="account_number" value="<?= htmlspecialchars($selectedBank['account_number'] ?? '') ?>" required placeholder="Account number" class="w-full px-2.5 py-1.5 border border-gray-200 rounded-lg text-xs font-mono font-semibold bg-white">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-500 mb-0.5">IFSC Code *</label>
                        <input type="text" id="w_ifsc" name="ifsc_code" value="<?= htmlspecialchars($selectedBank['ifsc_code'] ?? '') ?>" required placeholder="IFSC Code" class="w-full px-2.5 py-1.5 border border-gray-200 rounded-lg text-xs font-mono font-semibold uppercase bg-white">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-500 mb-0.5">Account Holder Name *</label>
                        <input type="text" id="w_holder_name" name="account_holder_name" value="<?= htmlspecialchars($selectedBank['account_holder_name'] ?? '') ?>" required placeholder="Beneficiary name" class="w-full px-2.5 py-1.5 border border-gray-200 rounded-lg text-xs font-semibold bg-white">
                    </div>
                </div>
            </div>

            <script>
            function handleWithdrawBankSelect(selectEl) {
                try {
                    const b = JSON.parse(selectEl.value);
                    document.getElementById('w_bank_name').value = b.bank_name || '';
                    document.getElementById('w_acc_no').value = b.account_number || '';
                    document.getElementById('w_ifsc').value = b.ifsc_code || '';
                    document.getElementById('w_holder_name').value = b.account_holder_name || '';
                } catch(e) {}
            }
            </script>

            <div class="pt-3 pb-1 flex justify-end space-x-3 border-t border-gray-100 sticky bottom-0 bg-white z-10 -mx-6 px-6 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                <button type="button" onclick="document.getElementById('vendor-withdraw-modal').classList.add('hidden')" class="px-4 py-2 border border-gray-200 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-50 transition">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow">Submit Withdrawal Request &rarr;</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>
