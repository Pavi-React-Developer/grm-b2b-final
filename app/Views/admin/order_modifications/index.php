<?php
// Count badges
$pendingCount = 0;
foreach ($modifications as $m) {
    if ($m['status'] === 'pending_buyer') $pendingCount++;
}

$statusColors = [
    'pending_buyer' => 'bg-amber-100 text-amber-700',
    'accepted'      => 'bg-green-100 text-green-700',
    'rejected'      => 'bg-red-100 text-red-700',
    'cancelled'     => 'bg-gray-100 text-gray-600',
];
$statusLabels = [
    'pending_buyer' => 'Awaiting Buyer',
    'accepted'      => 'Accepted',
    'rejected'      => 'Rejected',
    'cancelled'     => 'Cancelled',
];
?>

<div class="w-full space-y-6">

    <!-- Header Section -->
    <div class="mb-8 relative z-10">
        <!-- Breadcrumbs -->
        <div class="text-sm font-medium text-gray-400 mb-2 font-serif tracking-wide">
            Dashboard <span class="mx-1 text-gray-300">›</span> Order Management <span class="mx-1 text-gray-300">›</span> <span class="text-brand-700">Bill Modifications</span>
        </div>
        
        <div class="flex justify-between items-center mb-6 w-full">
            <div class="flex items-center gap-3">
                <h2 class="text-5xl font-display font-extrabold text-gray-900 tracking-tight">Bill Modifications</h2>
                <?php if ($pendingCount > 0): ?>
                <span class="inline-flex items-center gap-2 bg-amber-100 text-amber-700 text-sm font-semibold px-4 py-1.5 rounded-full">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <?= $pendingCount ?> Awaiting Response
                </span>
                <?php endif; ?>
            </div>
            
            <div class="flex items-center space-x-3 ml-auto">
                <button onclick="window.location.reload()" class="bg-white hover:bg-gray-50 text-gray-700 px-6 py-3 rounded-full font-bold text-sm shadow-sm border border-gray-200 transition-all flex items-center uppercase tracking-widest">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Refresh
                </button>
                <button onclick="exportModificationsCSV()" class="bg-[#F25996] hover:bg-[#e04481] text-white px-6 py-3 rounded-full font-bold text-sm shadow-sm transition-all flex items-center uppercase tracking-widest">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export Excel
                </button>
            </div>
        </div>
    </div>

    <!-- How to Create: info box (Pink Brand Theme) -->
    <div class="bg-[#fdf2f7] border border-[#fbaed2] rounded-xl px-5 py-4 flex items-start gap-4 shadow-sm">
        <svg class="w-6 h-6 text-[#F25996] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>
            <p class="text-sm font-bold text-[#831e46]">How to create a modification?</p>
            <p class="text-sm text-[#a32457] mt-0.5">Go to <a href="<?= BASE_URL ?>/admin/orders" class="underline font-semibold text-[#F25996] hover:text-[#e04481]">All Orders</a>, open an order, then click the <strong>"Modify Bill"</strong> button. The modification will appear here after you send it to the buyer.</p>
        </div>
    </div>

    <!-- Table -->
    <?php if (empty($modifications)): ?>
        <div class="bg-white rounded-xl border border-gray-200 p-16 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <p class="text-base font-medium">No bill modifications yet.</p>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase text-gray-500 font-semibold">
                        <tr>
                            <th class="px-6 py-4">Order</th>
                            <th class="px-6 py-4">Buyer</th>
                            <th class="px-6 py-4">Original Total</th>
                            <th class="px-6 py-4">Revised Total</th>
                            <th class="px-6 py-4">Difference</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Modified</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($modifications as $m): 
                            $diff = $m['revised_total'] - $m['original_total'];
                            $diffClass = $diff < 0 ? 'text-green-600' : ($diff > 0 ? 'text-red-600' : 'text-gray-500');
                        ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <a href="<?= BASE_URL ?>/admin/orders/view?id=<?= htmlspecialchars($m['order_number']) ?>" class="font-mono font-semibold text-brand-700 hover:underline">
                                    #<?= htmlspecialchars($m['order_number']) ?>
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900"><?= htmlspecialchars($m['buyer_name']) ?></div>
                                <div class="text-xs text-gray-400"><?= htmlspecialchars($m['buyer_phone']) ?></div>
                            </td>
                            <td class="px-6 py-4 text-gray-700">₹<?= number_format($m['original_total'], 2) ?></td>
                            <td class="px-6 py-4 font-semibold text-gray-900">₹<?= number_format($m['revised_total'], 2) ?></td>
                            <td class="px-6 py-4 font-semibold <?= $diffClass ?>">
                                <?= $diff >= 0 ? '+' : '' ?>₹<?= number_format($diff, 2) ?>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold <?= $statusColors[$m['status']] ?? 'bg-gray-100 text-gray-600' ?>">
                                    <?= $statusLabels[$m['status']] ?? ucfirst($m['status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-xs"><?= date('M j, Y H:i', strtotime($m['created_at'])) ?></td>
                            <td class="px-6 py-4 text-right">
                                <a href="<?= BASE_URL ?>/admin/order-modifications/view?id=<?= $m['id'] ?>" class="inline-flex items-center justify-center p-2 text-gray-500 hover:text-brand-600 hover:bg-gray-100 rounded-lg transition-colors" title="View Details">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function exportModificationsCSV() {
    // Use direct navigation for file download
    const a = document.createElement('a');
    a.href = '<?= BASE_URL ?>/admin/order-modifications/export-detailed';
    a.click();
}
</script>
