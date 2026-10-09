<div class="w-full">
    <!-- Top Breadcrumbs & Header -->
    <div class="mb-8 relative z-10">
        <div class="text-sm font-medium text-gray-400 mb-2 font-serif tracking-wide">
            Dashboard <span class="mx-1 text-gray-300">›</span> Vendor Management <span class="mx-1 text-gray-300">›</span> <span class="text-brand-700">Active Vendors</span>
        </div>

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h2 class="text-5xl font-display font-extrabold text-gray-900 tracking-tight">Active Vendors</h2>
                <p class="text-sm text-gray-500 mt-1">Manage approved vendors, store profiles, and seller merchant IDs.</p>
            </div>

            <div class="flex items-center space-x-3">
                <a href="<?= BASE_URL ?>/admin/vendors/dashboard" class="px-4 py-2.5 bg-[#F25996] hover:bg-[#e04481] text-white text-xs font-bold rounded-full shadow transition flex items-center gap-2 uppercase tracking-wider">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Revenue Dashboard
                </a>
                <a href="<?= BASE_URL ?>/admin/vendors/pending" class="px-4 py-2.5 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 text-xs font-bold rounded-full shadow-sm transition flex items-center gap-2 uppercase tracking-wider">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Pending (<?= $stats['pending_vendors'] ?? 0 ?>)
                </a>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex items-center gap-2 mb-8 border-b border-gray-200 pb-3">
        <a href="<?= BASE_URL ?>/admin/vendors/dashboard" class="px-4 py-2 text-gray-500 hover:text-gray-900 font-medium text-sm rounded-t-lg transition flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            Revenue &amp; Analytics
        </a>
        <a href="<?= BASE_URL ?>/admin/vendors" class="px-4 py-2 bg-brand-50 text-brand-700 border-b-2 border-brand-700 font-bold text-sm rounded-t-lg transition flex items-center gap-2">
            <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            Active Vendors (<?= $stats['active_vendors'] ?? count($vendors) ?>)
        </a>
        <a href="<?= BASE_URL ?>/admin/vendors/pending" class="px-4 py-2 text-gray-500 hover:text-gray-900 font-medium text-sm rounded-t-lg transition flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Pending Approvals
            <?php if (($stats['pending_vendors'] ?? 0) > 0): ?>
                <span class="bg-amber-500 text-white text-[10px] font-black px-1.5 py-0.5 rounded-full ml-1"><?= $stats['pending_vendors'] ?></span>
            <?php endif; ?>
        </a>
        <a href="<?= BASE_URL ?>/admin/vendors/types" class="px-4 py-2 text-gray-500 hover:text-gray-900 font-medium text-sm rounded-t-lg transition flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            Vendor Types
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mr-4 font-bold text-lg">
                ₹
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Gross Vendor Revenue</p>
                <h3 class="text-xl font-extrabold text-gray-900">₹<?= number_format($metrics['total_revenue'] ?? 0, 2) ?></h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center">
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Vendors</p>
                <h3 class="text-xl font-extrabold text-gray-900"><?= $stats['total_vendors'] ?? 0 ?></h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Active Sellers</p>
                <h3 class="text-xl font-extrabold text-gray-900"><?= $stats['active_vendors'] ?? 0 ?></h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center">
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Pending Review</p>
                <h3 class="text-xl font-extrabold text-gray-900"><?= $stats['pending_vendors'] ?? 0 ?></h3>
            </div>
        </div>
    </div>

    <!-- Active Vendors Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-gray-900 text-lg">Active Vendor Directory</h3>
                <p class="text-xs text-gray-500">Overview of all active vendors and their marketplace sales</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-[11px] font-bold uppercase tracking-wider border-b border-gray-100">
                        <th class="py-3.5 px-6">Vendor ID</th>
                        <th class="py-3.5 px-4">Store &amp; Brand</th>
                        <th class="py-3.5 px-4">Contact Info</th>
                        <th class="py-3.5 px-4">Location</th>
                        <th class="py-3.5 px-4">GST / PAN</th>
                        <th class="py-3.5 px-4 text-right">Gross Sales</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs">
                    <?php if (empty($vendors)): ?>
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-400">No active vendors found.</td>
                        </tr>
                    <?php else: ?>
                        <?php 
                        // Map vendor revenue from $vendorSummaries
                        $revMap = [];
                        if (!empty($vendorSummaries)) {
                            foreach ($vendorSummaries as $vs) {
                                $revMap[$vs['user_id']] = (float)$vs['gross_revenue'];
                            }
                        }
                        ?>
                        <?php foreach ($vendors as $v): 
                            $vRev = $revMap[$v['id']] ?? 0.0;
                        ?>
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="py-4 px-6 font-mono font-bold text-indigo-600">
                                    <?= htmlspecialchars($v['unique_vendor_id'] ?? ('VN' . str_pad($v['id'], 5, '0', STR_PAD_LEFT))) ?>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="font-bold text-gray-900 text-sm"><?= htmlspecialchars($v['store_name']) ?></div>
                                    <div class="text-xs text-gray-500"><?= htmlspecialchars($v['company_name'] ?? '-') ?></div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="font-medium text-gray-900"><?= htmlspecialchars($v['name']) ?></div>
                                    <div class="text-[11px] text-gray-500"><?= htmlspecialchars($v['email']) ?></div>
                                    <div class="text-[11px] text-gray-500"><?= htmlspecialchars($v['phone']) ?></div>
                                </td>
                                <td class="py-4 px-4 text-gray-600">
                                    <?= htmlspecialchars($v['city']) ?>, <?= htmlspecialchars($v['state']) ?>
                                </td>
                                <td class="py-4 px-4 font-mono text-[11px] text-gray-600">
                                    <?php if (!empty($v['gst_number'])): ?>
                                        <div class="font-bold text-gray-800">GST: <?= htmlspecialchars($v['gst_number']) ?></div>
                                    <?php else: ?>
                                        <div class="mb-0.5"><span class="px-1.5 py-0.5 bg-amber-50 text-amber-700 font-sans font-semibold text-[10px] rounded border border-amber-200">Non-GST</span></div>
                                    <?php endif; ?>
                                    <div>PAN: <?= htmlspecialchars($v['pan_number'] ?? 'N/A') ?></div>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <span class="font-black text-gray-900 text-sm">₹<?= number_format($vRev, 2) ?></span>
                                    <span class="text-[10px] text-emerald-600 block font-semibold">Revenue</span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <a href="<?= BASE_URL ?>/admin/vendors/view?id=<?= $v['id'] ?>" 
                                       class="px-3.5 py-1.5 bg-gray-100 hover:bg-[#1a50a8] hover:text-white text-gray-700 text-xs font-bold rounded-lg transition inline-flex items-center gap-1 shadow-sm">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
