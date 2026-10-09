<div class="w-full space-y-6">
    <!-- Header Section -->
    <div class="mb-8 relative z-10">
        <!-- Breadcrumbs -->
        <div class="text-sm font-medium text-gray-400 mb-2 font-serif tracking-wide">
            Dashboard <span class="mx-1 text-gray-300">›</span> Vendor Management <span class="mx-1 text-gray-300">›</span> <span class="text-brand-700">Pending Approvals</span>
        </div>
        
        <div class="flex justify-between items-center mb-6 w-full">
            <div>
                <h2 class="text-5xl font-display font-extrabold text-gray-900 tracking-tight">Pending Vendor Approvals</h2>
                <p class="text-sm text-gray-500 mt-1">Review business registration applications, store details, and verification proof before granting seller access.</p>
            </div>
            
            <div class="flex items-center space-x-3 ml-auto">
                <a href="<?= BASE_URL ?>/admin/vendors" class="bg-white hover:bg-gray-50 text-gray-700 px-6 py-3 rounded-full font-bold text-sm shadow-sm border border-gray-200 transition-all flex items-center uppercase tracking-widest">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to Vendors
                </a>
            </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex items-center gap-2 mb-8 border-b border-gray-200 pb-3">
        <a href="<?= BASE_URL ?>/admin/vendors/dashboard" class="px-4 py-2 text-gray-500 hover:text-gray-900 font-medium text-sm rounded-t-lg transition flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            Revenue &amp; Analytics
        </a>
        <a href="<?= BASE_URL ?>/admin/vendors" class="px-4 py-2 text-gray-500 hover:text-gray-900 font-medium text-sm rounded-t-lg transition flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            Active Vendors
        </a>
        <a href="<?= BASE_URL ?>/admin/vendors/pending" class="px-4 py-2 bg-brand-50 text-brand-700 border-b-2 border-brand-700 font-bold text-sm rounded-t-lg transition flex items-center gap-2">
            <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Pending Approvals (<?= count($vendors) ?>)
        </a>
        <a href="<?= BASE_URL ?>/admin/vendors/types" class="px-4 py-2 text-gray-500 hover:text-gray-900 font-medium text-sm rounded-t-lg transition flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            Vendor Types
        </a>
    </div>

    <!-- Pending Vendors Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-800 text-base">Pending Applications Queue</h3>
            <span class="px-2.5 py-1 bg-amber-50 text-amber-700 text-xs font-bold rounded-full">
                <?= count($vendors) ?> Applications
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-gray-100">
                        <th class="py-3 px-4">Applied Date</th>
                        <th class="py-3 px-4">Store & Company</th>
                        <th class="py-3 px-4">Vendor Type</th>
                        <th class="py-3 px-4">Applicant Contact</th>
                        <th class="py-3 px-4">Location</th>
                        <th class="py-3 px-4">GST / PAN</th>
                        <th class="py-3 px-4 text-right">Review Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    <?php if (empty($vendors)): ?>
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                No pending vendor applications at this time.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($vendors as $v): ?>
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="py-3 px-4 text-xs text-gray-500 font-mono">
                                    <?= date('d M Y, h:i A', strtotime($v['created_at'])) ?>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-semibold text-gray-900"><?= htmlspecialchars($v['store_name']) ?></div>
                                    <div class="text-xs text-gray-500"><?= htmlspecialchars($v['company_name'] ?? '-') ?></div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="inline-block px-2.5 py-1 text-xs font-semibold bg-indigo-50 text-indigo-700 rounded-lg capitalize">
                                        <?= htmlspecialchars($v['vendor_type'] ?? 'wholesaler') ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="text-gray-900 font-medium"><?= htmlspecialchars($v['name']) ?></div>
                                    <div class="text-xs text-gray-500"><?= htmlspecialchars($v['email']) ?></div>
                                    <div class="text-xs text-gray-500"><?= htmlspecialchars($v['phone']) ?></div>
                                </td>
                                <td class="py-3 px-4 text-gray-600">
                                    <?= htmlspecialchars($v['city']) ?>, <?= htmlspecialchars($v['state']) ?> (<?= htmlspecialchars($v['pincode']) ?>)
                                </td>
                                <td class="py-3 px-4 font-mono text-xs">
                                    <?php if (!empty($v['gst_number'])): ?>
                                        <div class="font-bold text-gray-800">GST: <?= htmlspecialchars($v['gst_number']) ?></div>
                                    <?php else: ?>
                                        <div class="mb-1"><span class="px-2 py-0.5 bg-amber-50 text-amber-700 font-sans font-bold text-[10px] rounded-full border border-amber-200">Non-GST Vendor</span></div>
                                    <?php endif; ?>
                                    <div class="text-gray-600">PAN: <?= htmlspecialchars($v['pan_number'] ?? 'N/A') ?></div>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <a href="<?= BASE_URL ?>/admin/vendors/view?id=<?= $v['id'] ?>" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition inline-flex items-center shadow-sm">
                                        Review Application &rarr;
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
