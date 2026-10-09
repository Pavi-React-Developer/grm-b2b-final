<div class="mb-8 relative z-10">
    <!-- Breadcrumbs -->
    <div class="text-sm font-medium text-gray-400 mb-2 font-serif tracking-wide">
        Dashboard <span class="mx-1 text-gray-300">›</span> <?= $isVendor ? 'Vendor Portal' : 'Finance Management' ?> <span class="mx-1 text-gray-300">›</span> <span class="text-brand-700">Payments & Settlements</span>
    </div>
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-5xl font-display font-extrabold text-gray-900 tracking-tight">
                <?= $isVendor ? 'My Earnings & Payment Wallet' : 'Vendor Payments & Settlement Overview' ?>
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                <?= $isVendor ? 'Track your order earnings, GST deductions, pending escrow, and withdrawable wallet funds.' : 'Monitor platform GMV, GST / Admin revenue, vendor wallet balances, and escrow settlements.' ?>
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <?php if ($isVendor): ?>
                <?php if ($summary['can_withdraw']): ?>
                    <button onclick="document.getElementById('withdraw-modal').classList.remove('hidden')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-full font-bold text-xs shadow-sm transition-all flex items-center gap-2 uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Withdraw Funds (₹<?= number_format($summary['available_balance'], 2) ?>)
                    </button>
                <?php else: ?>
                    <button disabled class="bg-gray-200 text-gray-400 cursor-not-allowed px-6 py-3 rounded-full font-bold text-xs flex items-center gap-2 uppercase tracking-wider" title="Minimum withdrawal amount is ₹<?= number_format($summary['min_withdrawal_limit'], 2) ?>">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Min. ₹<?= number_format($summary['min_withdrawal_limit'], 2) ?> to Withdraw
                    </button>
                <?php endif; ?>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/admin/finance/withdrawals" class="bg-[#F25996] hover:bg-[#e04481] text-white px-6 py-3 rounded-full font-bold text-xs shadow-sm transition-all flex items-center gap-2 uppercase tracking-wider">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    Payout Requests (<?= (int)($platformSummary['pending_payouts_count'] ?? 0) ?>)
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (!$isVendor): ?>
<!-- Admin Global Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center">
        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mr-4 text-xl font-bold">
            ₹
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Platform Gross GMV</p>
            <h3 class="text-xl font-extrabold text-gray-900">₹<?= number_format($platformSummary['total_gross_gmv'] ?? 0, 2) ?></h3>
            <span class="text-[10px] text-gray-400 font-medium">Total customer sales</span>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center">
        <div class="w-12 h-12 bg-indigo-50 text-indigo-700 rounded-xl flex items-center justify-center mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Admin Amount (GST)</p>
            <h3 class="text-xl font-extrabold text-indigo-600">₹<?= number_format($platformSummary['total_admin_amount'] ?? $platformSummary['total_commission_earned'] ?? 0, 2) ?></h3>
            <span class="text-[10px] text-indigo-600 font-semibold">Total to Admin</span>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center">
        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Vendor Amount (Net)</p>
            <h3 class="text-xl font-extrabold text-emerald-600">₹<?= number_format($platformSummary['total_vendor_amount'] ?? $platformSummary['total_vendor_delivered_net'] ?? 0, 2) ?></h3>
            <span class="text-[10px] text-emerald-600 font-semibold">Total to Vendors</span>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center">
        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Pending Escrow</p>
            <h3 class="text-xl font-extrabold text-amber-600">₹<?= number_format($platformSummary['total_pending_escrow'] ?? 0, 2) ?></h3>
            <span class="text-[10px] text-gray-400">In-transit orders</span>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center">
        <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Disbursed</p>
            <h3 class="text-xl font-extrabold text-purple-600">₹<?= number_format($platformSummary['total_disbursed'] ?? 0, 2) ?></h3>
            <span class="text-[10px] text-gray-400">Total payouts paid</span>
        </div>
    </div>
</div>

<!-- Admin Filter & Vendor Select Bar -->
<div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex items-center space-x-2">
        <span class="text-xs font-bold text-gray-700">Filter Vendor:</span>
        <select onchange="window.location.href='<?= BASE_URL ?>/admin/finance/payments' + (this.value ? '?vendor_id=' + this.value : '')" class="px-3.5 py-1.5 rounded-xl text-xs font-bold border border-gray-200 bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">🏢 All Vendors (Summary View)</option>
            <?php foreach ($activeVendors as $v): ?>
                <option value="<?= $v['id'] ?>" <?= (!empty($selectedVendorId) && (int)$selectedVendorId === (int)$v['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($v['store_name'] ?? $v['name']) ?> (ID: <?= htmlspecialchars($v['unique_vendor_id'] ?? 'VN'.$v['id']) ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="flex items-center space-x-2">
        <a href="<?= BASE_URL ?>/admin/finance/commissions" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl transition">
            ⚙️ GST & Fee Settings
        </a>
        <a href="<?= BASE_URL ?>/admin/finance/withdrawals" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold text-xs rounded-xl transition">
            💸 Payout Requests
        </a>
        <a href="<?= BASE_URL ?>/admin/finance/transactions" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl transition">
            📑 Financial Statements
        </a>
    </div>
</div>
<?php endif; ?>

<?php if ($isVendor || !empty($selectedVendorSummary)): 
    $s = $isVendor ? $summary : $selectedVendorSummary;
?>
<!-- Vendor Specific Wallet Metrics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <!-- Card 1: Available Wallet Balance -->
    <div class="bg-gradient-to-br from-emerald-500 to-teal-700 p-5 rounded-2xl text-white shadow-lg relative overflow-hidden flex flex-col justify-between">
        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
        <div>
            <div class="flex items-center justify-between mb-1">
                <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-100">Withdrawable Balance</span>
                <span class="px-2 py-0.5 bg-white/20 text-white rounded-full text-[10px] font-bold">Ready</span>
            </div>
            <h3 class="text-2xl font-black tracking-tight">₹<?= number_format($s['available_balance'], 2) ?></h3>
        </div>
        <div class="mt-3 pt-2 border-t border-white/20 text-[11px] text-emerald-100 flex items-center justify-between">
            <span>Vendor Share (Delivered)</span>
            <span>Min. ₹<?= number_format($s['min_withdrawal_limit'], 2) ?></span>
        </div>
    </div>

    <!-- Card 2: Pending Orders Escrow -->
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-1">
                <span class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Pending Escrow</span>
                <span class="px-2 py-0.5 bg-amber-50 text-amber-700 rounded-full text-[10px] font-bold">In-Transit</span>
            </div>
            <h3 class="text-2xl font-black text-amber-600">₹<?= number_format($s['pending_orders_amount'], 2) ?></h3>
        </div>
        <div class="mt-3 pt-2 border-t border-gray-50 text-[11px] text-gray-400">
            Vendor Share (In-Transit)
        </div>
    </div>

    <!-- Card 3: Total Gross Sales -->
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-1">
                <span class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Matured Gross Sales</span>
                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded-full text-[10px] font-bold">Released</span>
            </div>
            <h3 class="text-2xl font-black text-gray-900">₹<?= number_format($s['total_gross_sales'], 2) ?></h3>
        </div>
        <div class="mt-3 pt-2 border-t border-gray-50 text-[11px] text-gray-400 flex items-center justify-between">
            <span>Orders matured</span>
            <?php if (($s['total_holding_gross'] ?? 0) > 0): ?>
                <span class="text-amber-600 font-bold" title="<?= (int)($s['holding_days'] ?? 7) ?>-day holding period">+₹<?= number_format($s['total_holding_gross'], 2) ?> holding</span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Card 4: Admin Amount (GST) -->
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-1">
                <span class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Admin Amount (GST)</span>
                <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded-full text-[10px] font-bold">To Admin</span>
            </div>
            <h3 class="text-2xl font-black text-indigo-600">₹<?= number_format($s['total_admin_amount'] ?? $s['total_commission_fee'], 2) ?></h3>
        </div>
        <div class="mt-3 pt-2 border-t border-gray-50 text-[11px] text-gray-400">
            GST & Platform Deductions
        </div>
    </div>

    <!-- Card 5: Total Withdrawn -->
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-1">
                <span class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Total Withdrawn</span>
                <span class="px-2 py-0.5 bg-purple-50 text-purple-700 rounded-full text-[10px] font-bold">Paid</span>
            </div>
            <h3 class="text-2xl font-black text-purple-600">₹<?= number_format($s['total_withdrawn'], 2) ?></h3>
        </div>
        <div class="mt-3 pt-2 border-t border-gray-50 text-[11px] text-gray-400 flex items-center justify-between">
            <span>Disbursed Payouts</span>
            <?php if (($s['pending_withdrawals'] ?? 0) > 0): ?>
                <span class="text-amber-600 font-bold" title="Pending approval">₹<?= number_format($s['pending_withdrawals'], 2) ?> pending</span>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if (!$isVendor && empty($selectedVendorId)): ?>
<!-- All Vendors Balances Table for Admin -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-base font-bold text-gray-900 font-display flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            All Registered Vendors - Financial Balances
        </h3>
        <span class="text-xs font-semibold text-gray-400"><?= count($vendorsList) ?> Active Vendors</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-gray-50/80 text-gray-500 font-bold uppercase tracking-wider border-b border-gray-100">
                    <th class="py-3.5 px-4">Vendor & Store</th>
                    <th class="py-3.5 px-4 text-right">Gross GMV</th>
                    <th class="py-3.5 px-4 text-center">GST Rate</th>
                    <th class="py-3.5 px-4 text-right">Admin Amount (GST)</th>
                    <th class="py-3.5 px-4 text-right">Vendor Amount (Net)</th>
                    <th class="py-3.5 px-4 text-right">Available Wallet</th>
                    <th class="py-3.5 px-4 text-right">Pending Escrow</th>
                    <th class="py-3.5 px-4 text-right">Total Withdrawn</th>
                    <th class="py-3.5 px-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 font-medium">
                <?php foreach ($vendorsList as $v): ?>
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="py-3.5 px-4">
                            <div class="font-bold text-gray-900"><?= htmlspecialchars($v['store_name'] ?? $v['name']) ?></div>
                            <div class="text-[10px] text-gray-400 font-mono">
                                ID: <span class="text-indigo-600 font-bold"><?= htmlspecialchars($v['unique_vendor_id'] ?? 'VN'.$v['id']) ?></span> • <?= htmlspecialchars($v['email']) ?>
                            </div>
                        </td>
                        <td class="py-3.5 px-4 text-right font-bold text-gray-900">
                            ₹<?= number_format($v['total_gross_sales'], 2) ?>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <?php if ($v['commission_rate'] !== null && $v['commission_rate'] !== ''): ?>
                                <span class="bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-full font-bold text-[10px] border border-indigo-100">
                                    <?= number_format($v['commission_rate'], 1) ?>% (Custom)
                                </span>
                            <?php else: ?>
                                <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full font-bold text-[10px]">
                                    <?= number_format($platformSummary['default_commission_rate'], 1) ?>% (Standard)
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3.5 px-4 text-right font-bold text-indigo-600">
                            ₹<?= number_format($v['total_admin_amount'] ?? $v['total_commission_fee'], 2) ?>
                            <div class="text-[9px] text-gray-400 font-normal">to Admin</div>
                        </td>
                        <td class="py-3.5 px-4 text-right font-bold text-emerald-600">
                            ₹<?= number_format($v['total_vendor_amount'] ?? $v['total_net_delivered'], 2) ?>
                            <div class="text-[9px] text-emerald-600/80 font-normal">to Vendor</div>
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <span class="font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg border border-emerald-100">
                                ₹<?= number_format($v['available_balance'], 2) ?>
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-right font-semibold text-amber-600">
                            ₹<?= number_format($v['pending_orders_amount'], 2) ?>
                        </td>
                        <td class="py-3.5 px-4 text-right font-bold text-purple-600">
                            ₹<?= number_format($v['total_withdrawn'], 2) ?>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <a href="<?= BASE_URL ?>/admin/finance/payments?vendor_id=<?= $v['id'] ?>" class="px-2.5 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold rounded-lg transition" title="View detailed order breakdown">
                                View Details &rarr;
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- Detailed Orders & Earnings Breakdown Table -->
<?php if ($isVendor || !empty($selectedVendorId)): ?>
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-gray-50/50">
        <div>
            <h3 class="text-base font-bold text-gray-900 font-display flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Order-by-Order Earnings & GST Breakdown
            </h3>
            <p class="text-xs text-gray-500 mt-0.5">Itemized calculations showing gross total, GST / Admin amount, and Vendor amount per product.</p>
        </div>

        <!-- Filter tabs -->
        <div class="flex items-center space-x-2">
            <?php 
                $baseUrlParams = $isVendor ? (BASE_URL . '/admin/finance/payments') : (BASE_URL . '/admin/finance/payments?vendor_id=' . $selectedVendorId);
                $sep = $isVendor ? '?' : '&';
            ?>
            <a href="<?= $baseUrlParams ?>" class="px-3 py-1 rounded-xl text-xs font-bold transition <?= empty($currentStatus) || $currentStatus === 'all' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">
                All (<?= count($orders) ?>)
            </a>
            <a href="<?= $baseUrlParams . $sep ?>status=delivered" class="px-3 py-1 rounded-xl text-xs font-bold transition <?= $currentStatus === 'delivered' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' ?>">
                Delivered (Wallet Settled)
            </a>
            <a href="<?= $baseUrlParams . $sep ?>status=shipped" class="px-3 py-1 rounded-xl text-xs font-bold transition <?= $currentStatus === 'shipped' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' ?>">
                In-Transit (Escrow)
            </a>
        </div>
    </div>

    <?php if (empty($orders)): ?>
        <div class="p-12 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="font-semibold text-gray-600">No order earnings found for this filter.</p>
            <p class="text-xs text-gray-400 mt-1">When wholesale orders are placed and paid, earnings will populate here.</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 font-bold uppercase tracking-wider border-b border-gray-100">
                        <th class="py-3.5 px-4">Order # & Date</th>
                        <th class="py-3.5 px-4">Product Item</th>
                        <th class="py-3.5 px-4 text-center">Qty</th>
                        <th class="py-3.5 px-4 text-right">Gross Total</th>
                        <th class="py-3.5 px-4 text-center">GST %</th>
                        <th class="py-3.5 px-4 text-right">Admin Amount (GST)</th>
                        <th class="py-3.5 px-4 text-right">Vendor Amount</th>
                        <th class="py-3.5 px-4 text-center">Order Status</th>
                        <th class="py-3.5 px-4 text-center">Settlement Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    <?php foreach ($orders as $item): ?>
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="py-3.5 px-4">
                                <span class="font-bold font-mono text-indigo-600 text-xs">
                                    #<?= htmlspecialchars($item['order_number']) ?>
                                </span>
                                <div class="text-[10px] text-gray-400">
                                    <?= date('d M Y, h:i A', strtotime($item['order_date'])) ?>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-gray-900"><?= htmlspecialchars($item['product_name']) ?></div>
                                <div class="text-[10px] text-gray-400">
                                    Unit Price: ₹<?= number_format($item['unit_price'], 2) ?> • <?= htmlspecialchars($item['category_name'] ?? 'General') ?>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold text-gray-700">
                                <?= (int)$item['quantity'] ?>
                            </td>
                            <td class="py-3.5 px-4 text-right font-bold text-gray-900">
                                ₹<?= number_format($item['item_gross'], 2) ?>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100" title="<?= htmlspecialchars($item['commission_tier'] ?? 'GST Rate') ?>">
                                    <?= number_format($item['gst_rate'] ?? $item['commission_rate'], 1) ?>%
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right font-bold text-indigo-600">
                                ₹<?= number_format($item['admin_amount'] ?? $item['commission_fee'], 2) ?>
                                <div class="text-[9px] text-gray-400 font-normal">to Admin (GST)</div>
                            </td>
                            <td class="py-3.5 px-4 text-right font-black text-emerald-600 text-sm">
                                ₹<?= number_format($item['vendor_amount'] ?? $item['net_amount'], 2) ?>
                                <div class="text-[9px] text-emerald-600/80 font-normal">to Vendor</div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    <?php if ($item['order_status'] === 'delivered'): ?>
                                        bg-emerald-100 text-emerald-700
                                    <?php elseif ($item['order_status'] === 'shipped' || $item['order_status'] === 'out_for_delivery'): ?>
                                        bg-blue-100 text-blue-700
                                    <?php elseif ($item['order_status'] === 'packed'): ?>
                                        bg-indigo-100 text-indigo-700
                                    <?php else: ?>
                                        bg-amber-100 text-amber-700
                                    <?php endif; ?>">
                                    <?= htmlspecialchars($item['order_status']) ?>
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <?php if ($item['settlement_status'] === 'settled_wallet'): ?>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full border border-emerald-200">
                                        <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        Ready in Wallet
                                    </span>
                                <?php elseif ($item['settlement_status'] === 'holding_period'): ?>
                                    <div class="inline-flex flex-col items-center">
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold bg-amber-50 text-amber-700 px-2 py-0.5 rounded-full border border-amber-200" title="Order date: <?= htmlspecialchars($item['order_date']) ?>. Holding period: <?= (int)$item['holding_days'] ?> days">
                                            <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Holding (<?= (int)$item['remaining_holding_days'] ?>d left)
                                        </span>
                                        <span class="text-[9px] text-gray-400 mt-0.5">Matures <?= htmlspecialchars($item['release_date']) ?></span>
                                    </div>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full border border-blue-200">
                                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        In Escrow (Delivering)
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- Vendor Withdrawal Request Modal -->
<?php if ($isVendor): ?>
<div id="withdraw-modal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-3 sm:p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-100 flex flex-col max-h-[85vh] sm:max-h-[90vh] my-auto relative">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-emerald-600 to-teal-700 text-white shrink-0 sticky top-0 z-20">
            <div>
                <h3 class="font-bold text-base">Request Fund Withdrawal</h3>
                <p class="text-xs text-emerald-100">Transfer funds to your registered bank account or UPI</p>
            </div>
            <button onclick="document.getElementById('withdraw-modal').classList.add('hidden')" class="text-white/80 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form action="<?= BASE_URL ?>/admin/finance/withdrawals/request" method="POST" class="p-6 space-y-4 overflow-y-auto flex-1 min-h-0">
            <!-- Available balance banner -->
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
                <label for="amount" class="block text-sm font-semibold text-gray-700 mb-1">Withdrawal Amount (₹) *</label>
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-gray-400 font-bold">₹</span>
                    <input type="text" step="0.01" min="1" max="<?= $summary['min_withdrawal_limit'] ?>" id="amount" name="amount" value="<?= min($summary['min_withdrawal_limit'], $summary['available_balance']) ?>" required class="w-full pl-8 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-base font-bold text-gray-900">
                </div>
                <p class="text-[11px] text-rose-600 mt-1 font-semibold">
                    🛡️ Admin Configured Withdrawal Limit: <strong>₹<?= number_format($summary['min_withdrawal_limit'], 2) ?></strong> max per request.
                </p>
            </div>

            <!-- Payout Method -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Payout Method *</label>
                <select name="payout_method" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm font-medium bg-white focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="bank_transfer" selected>🏦 Direct Bank Transfer (NEFT / RTGS / IMPS)</option>
                    <option value="upi">📱 UPI Transfer</option>
                </select>
            </div>

            <!-- Bank Details (Pre-filled from primary account / saved accounts) -->
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-700 block">Registered Payout Account Details:</span>
                    <a href="<?= BASE_URL ?>/admin/vendor/settings" class="text-[11px] text-emerald-700 hover:text-emerald-900 font-bold hover:underline flex items-center gap-1">
                        ⚙️ Manage Accounts
                    </a>
                </div>

                <?php if (!empty($savedBankAccounts) && count($savedBankAccounts) > 1): ?>
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-500 mb-1">Select Bank Account:</label>
                        <select onchange="handlePayWithdrawBankSelect(this)" class="w-full px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-semibold bg-white text-gray-800">
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
                        <input type="text" id="pw_bank_name" name="bank_name" value="<?= htmlspecialchars($selectedBank['bank_name'] ?? '') ?>" required placeholder="e.g. HDFC Bank" class="w-full px-2.5 py-1.5 border border-gray-200 rounded-lg text-xs font-semibold bg-white">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-500 mb-0.5">Account Number *</label>
                        <input type="text" id="pw_acc_no" name="account_number" value="<?= htmlspecialchars($selectedBank['account_number'] ?? '') ?>" required placeholder="Account number" class="w-full px-2.5 py-1.5 border border-gray-200 rounded-lg text-xs font-mono font-semibold bg-white">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-500 mb-0.5">IFSC Code *</label>
                        <input type="text" id="pw_ifsc" name="ifsc_code" value="<?= htmlspecialchars($selectedBank['ifsc_code'] ?? '') ?>" required placeholder="IFSC Code" class="w-full px-2.5 py-1.5 border border-gray-200 rounded-lg text-xs font-mono font-semibold uppercase bg-white">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-500 mb-0.5">Account Holder Name *</label>
                        <input type="text" id="pw_holder_name" name="account_holder_name" value="<?= htmlspecialchars($selectedBank['account_holder_name'] ?? '') ?>" required placeholder="Beneficiary name" class="w-full px-2.5 py-1.5 border border-gray-200 rounded-lg text-xs font-semibold bg-white">
                    </div>
                </div>
            </div>

            <script>
            function handlePayWithdrawBankSelect(selectEl) {
                try {
                    const b = JSON.parse(selectEl.value);
                    document.getElementById('pw_bank_name').value = b.bank_name || '';
                    document.getElementById('pw_acc_no').value = b.account_number || '';
                    document.getElementById('pw_ifsc').value = b.ifsc_code || '';
                    document.getElementById('pw_holder_name').value = b.account_holder_name || '';
                } catch(e) {}
            }
            </script>

            <div>
                <label for="vendor_notes" class="block text-xs font-semibold text-gray-600 mb-1">Notes / Instructions (Optional)</label>
                <textarea id="vendor_notes" name="vendor_notes" rows="2" placeholder="e.g. Please transfer to default HDFC current account" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-emerald-500 focus:border-emerald-500"></textarea>
            </div>

            <div class="pt-3 pb-1 flex justify-end space-x-3 border-t border-gray-100 sticky bottom-0 bg-white z-10 -mx-6 px-6 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                <button type="button" onclick="document.getElementById('withdraw-modal').classList.add('hidden')" class="px-4 py-2 border border-gray-200 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-50 transition">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow">Submit Withdrawal Request &rarr;</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>
