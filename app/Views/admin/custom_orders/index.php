<?php
/**
 * Admin Customized Garments & Orders View
 * 
 * @var string $title
 * @var array $orders
 * @var string $currentStatus
 * @var array $statusCounts
 */
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 uppercase tracking-wider">
                    ✨ Customization Studio
                </span>
                <span class="text-xs text-gray-500 font-medium">B2B Wholesale Module</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">Customized Garment Orders & Requests</h1>
            <p class="text-sm text-gray-500 mt-1">Manage wholesale fabric customization configurations, stitching orders, and quote requests.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?= BASE_URL ?>/admin/fabric-customizations" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-bold rounded-xl shadow-xs transition-colors">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879a3 3 0 11-4.242-4.242L11.758 4.758a3 3 0 114.242 4.242L12 12z"/></svg>
                <span>Fabric Rules (<?= count($orders) ?> Active)</span>
            </a>
            <a href="<?= BASE_URL ?>/admin/fabric-customizations/create" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white text-sm font-bold rounded-xl shadow-sm hover:shadow transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>+ New Fabric Rule</span>
            </a>
        </div>
    </div>

    <!-- Status Filter Tabs -->
    <div class="bg-white rounded-2xl p-2 border border-gray-200/80 shadow-xs mb-6 overflow-x-auto">
        <div class="flex gap-2 min-w-max">
            <?php
            $tabs = [
                'all' => ['label' => 'All Custom Orders', 'count' => $statusCounts['all'] ?? 0],
                'request_pending' => ['label' => 'Quote Requests', 'count' => $statusCounts['request_pending'] ?? 0, 'color' => 'amber'],
                'paid' => ['label' => 'Razorpay Paid', 'count' => $statusCounts['paid'] ?? 0, 'color' => 'emerald'],
                'placed' => ['label' => 'New Placed', 'count' => $statusCounts['placed'] ?? 0],
                'stitching' => ['label' => 'In Stitching', 'count' => $statusCounts['stitching'] ?? 0, 'color' => 'indigo'],
                'in_production' => ['label' => 'In Production', 'count' => $statusCounts['in_production'] ?? 0, 'color' => 'blue'],
                'packed' => ['label' => 'Packed', 'count' => $statusCounts['packed'] ?? 0],
                'shipped' => ['label' => 'Shipped', 'count' => $statusCounts['shipped'] ?? 0],
                'delivered' => ['label' => 'Delivered', 'count' => $statusCounts['delivered'] ?? 0, 'color' => 'green'],
                'cancelled' => ['label' => 'Cancelled', 'count' => $statusCounts['cancelled'] ?? 0, 'color' => 'rose'],
            ];
            ?>
            <?php foreach ($tabs as $key => $tab): ?>
                <?php $isActive = $currentStatus === $key; ?>
                <a href="<?= BASE_URL ?>/admin/customize/orders?status=<?= $key ?>" class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 <?= $isActive ? 'bg-slate-900 text-white shadow-xs' : 'text-gray-600 hover:bg-gray-100/70 hover:text-gray-900' ?>">
                    <span><?= htmlspecialchars($tab['label']) ?></span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black <?= $isActive ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700' ?>">
                        <?= $tab['count'] ?>
                    </span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Orders Feed -->
    <?php if (empty($orders)): ?>
        <div class="bg-white rounded-3xl p-16 text-center border border-gray-200 shadow-sm">
            <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-4 border border-amber-100 shadow-xs">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879a3 3 0 11-4.242-4.242L11.758 4.758a3 3 0 114.242 4.242L12 12z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">No Custom Orders Found</h3>
            <p class="text-sm text-gray-500 max-w-md mx-auto mb-6">There are no customized garment orders or requests matching the selected filter status.</p>
            <a href="<?= BASE_URL ?>/admin/customize/orders" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 text-white text-xs font-bold rounded-xl hover:bg-gray-800 transition-colors">
                View All Custom Orders
            </a>
        </div>
    <?php else: ?>
        <div class="space-y-6">
            <?php foreach ($orders as $order): ?>
                <div class="bg-white rounded-3xl border border-gray-200/90 shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                    <!-- Top Order Info Header -->
                    <div class="p-6 bg-gradient-to-r from-gray-50/80 via-white to-gray-50/80 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex items-center gap-3 flex-wrap">
                            <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center font-black shadow-xs">
                                ✂️
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-base font-extrabold text-gray-900 font-mono">#<?= htmlspecialchars($order['order_number']) ?></h3>
                                    <?php if ($order['payment_status'] === 'paid'): ?>
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black bg-emerald-100 text-emerald-800 border border-emerald-200 flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> Razorpay Paid
                                        </span>
                                    <?php elseif ($order['payment_status'] === 'request_pending'): ?>
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black bg-amber-100 text-amber-800 border border-amber-200 flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span> Quote Request
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-gray-100 text-gray-700">
                                            <?= ucfirst($order['payment_status'] ?? 'pending') ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-xs text-gray-500 mt-0.5">Placed on <?= date('M d, Y \a\t h:i A', strtotime($order['created_at'])) ?></p>
                            </div>
                        </div>

                        <!-- Buyer Info & Status Changer -->
                        <div class="flex items-center gap-4 flex-wrap">
                            <div class="text-right hidden sm:block">
                                <div class="text-xs font-bold text-gray-900"><?= htmlspecialchars($order['user_name'] ?? 'Buyer') ?></div>
                                <div class="text-[11px] text-gray-500"><?= htmlspecialchars($order['user_phone'] ?? $order['user_email'] ?? '') ?></div>
                            </div>
                            
                            <!-- Status Selector -->
                            <div class="flex items-center gap-2">
                                <label class="text-xs font-bold text-gray-500">Status:</label>
                                <select onchange="updateOrderStatus('<?= $order['id'] ?>', this.value)" class="bg-gray-50 border border-gray-200 rounded-xl px-3 py-1.5 text-xs font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                                    <option value="placed" <?= $order['status'] === 'placed' ? 'selected' : '' ?>>Placed / New</option>
                                    <option value="stitching" <?= $order['status'] === 'stitching' ? 'selected' : '' ?>>🧵 Stitching</option>
                                    <option value="in_production" <?= $order['status'] === 'in_production' ? 'selected' : '' ?>>🏭 In Production</option>
                                    <option value="quality_check" <?= $order['status'] === 'quality_check' ? 'selected' : '' ?>>🔍 Quality Check</option>
                                    <option value="packed" <?= $order['status'] === 'packed' ? 'selected' : '' ?>>📦 Packed</option>
                                    <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>🚚 Shipped</option>
                                    <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : '' ?>>✅ Delivered</option>
                                    <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>❌ Cancelled</option>
                                </select>
                            </div>

                            <a href="<?= BASE_URL ?>/admin/orders/view?id=<?= $order['order_number'] ?>" class="p-2 text-gray-400 hover:text-gray-900 hover:bg-gray-100 rounded-xl transition-colors" title="View Full Order">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Custom Items Breakdown -->
                    <div class="p-6 space-y-4">
                        <?php foreach ($order['items'] as $item): ?>
                            <?php 
                            $cData = $item['customization_parsed'];
                            $rawImg = !empty($item['primary_image']) ? get_image_url($item['primary_image']) : null;
                            ?>
                            <div class="p-4 rounded-2xl bg-amber-50/40 border border-amber-200/80">
                                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                                    
                                    <!-- Left: Fabric Info -->
                                    <div class="flex items-start gap-4">
                                        <div class="w-16 h-16 rounded-xl bg-gray-100 border border-gray-200 overflow-hidden flex-shrink-0">
                                            <?php if ($rawImg): ?>
                                                <img src="<?= htmlspecialchars($rawImg) ?>" alt="" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <div class="w-full h-full flex items-center justify-center text-gray-400 font-bold text-xs">Fabric</div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-amber-800 bg-amber-100 px-2 py-0.5 rounded-md">
                                                ✂️ <?= htmlspecialchars($cData['program_name'] ?? 'Custom Program') ?>
                                            </span>
                                            <h4 class="text-base font-extrabold text-gray-900 mt-1"><?= htmlspecialchars($item['product_name']) ?></h4>
                                            <div class="flex items-center gap-3 text-xs text-gray-500 mt-0.5 flex-wrap">
                                                <?php if (!empty($item['fabric_sku'])): ?>
                                                    <span>SKU: <?= htmlspecialchars($item['fabric_sku']) ?></span>
                                                    <span>•</span>
                                                <?php endif; ?>
                                                <span>Garment Type: <strong class="text-gray-800"><?= htmlspecialchars($cData['garment_type'] ?? 'Garment') ?></strong></span>
                                                <span>•</span>
                                                <span>Total Pieces: <strong class="text-gray-900"><?= (int)$item['quantity'] ?> pcs</strong></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right: Fabric Consumption Metric -->
                                    <div class="bg-white p-3 rounded-xl border border-amber-200 shadow-2xs text-right min-w-[180px]">
                                        <div class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Total Fabric Consumed</div>
                                        <div class="text-lg font-black text-amber-900 mt-0.5">
                                            <?= number_format($cData['required_fabric'] ?? 0, 2) ?> <?= htmlspecialchars($cData['fabric_unit'] ?? $cData['unit'] ?? 'Meter') ?>
                                        </div>
                                        <div class="text-xs font-bold text-gray-700 mt-1">
                                            Value: ₹<?= number_format($item['total_price'], 2) ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Stitched Sizes Breakdown Grid -->
                                <?php if (!empty($cData['selected_sizes'])): ?>
                                    <div class="mt-4 pt-3 border-t border-amber-200/60">
                                        <div class="text-xs font-extrabold uppercase tracking-wider text-amber-900 mb-2 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                            Configured Sizes & Piece Distribution (Fabric Rule Breakdown):
                                        </div>
                                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-2">
                                            <?php foreach ($cData['selected_sizes'] as $sz): ?>
                                                <div class="bg-white p-2.5 rounded-xl border border-amber-200 text-center shadow-2xs">
                                                    <div class="text-xs font-black text-amber-800 uppercase"><?= htmlspecialchars($sz['size_name']) ?></div>
                                                    <div class="text-sm font-extrabold text-gray-900 my-0.5"><?= (int)$sz['quantity'] ?> <span class="text-[11px] font-medium text-gray-400">pcs</span></div>
                                                    <div class="text-[10.5px] font-semibold text-amber-700">
                                                        <?= number_format($sz['total_consumption'] ?? ($sz['quantity'] * ($sz['consumption_per_piece'] ?? 0)), 2) ?> <?= htmlspecialchars($cData['fabric_unit'] ?? $cData['unit'] ?? 'm') ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
function updateOrderStatus(orderId, status) {
    Swal.fire({
        title: 'Update Custom Order Status?',
        text: `Change order status to ${status.replace('_', ' ').toUpperCase()}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ea580c',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, update status'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('<?= BASE_URL ?>/admin/customize/orders/update-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    order_id: orderId,
                    status: status
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: data.message || 'Could not update status.'
                    });
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected network error occurred.'
                });
            });
        }
    });
}
</script>
