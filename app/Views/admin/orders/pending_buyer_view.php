<?php
/**
 * @var string $title
 * @var array $buyer
 * @var array $orders
 */

$totalPendingOrders = count($orders);
$totalPendingAmount = array_sum(array_column($orders, 'grand_total'));
$initial = strtoupper(substr($buyer['name'] ?? 'B', 0, 1));
?>

<style>
    .icon-16 { width: 16px !important; height: 16px !important; max-width: 16px !important; max-height: 16px !important; flex-shrink: 0; }
    .icon-20 { width: 20px !important; height: 20px !important; max-width: 20px !important; max-height: 20px !important; flex-shrink: 0; }
    .icon-24 { width: 24px !important; height: 24px !important; max-width: 24px !important; max-height: 24px !important; flex-shrink: 0; }
    .icon-48 { width: 48px !important; height: 48px !important; max-width: 48px !important; max-height: 48px !important; flex-shrink: 0; }
</style>

<div class="w-full">
    <!-- Header Section -->
    <div class="mb-8 relative z-10">
        <!-- Breadcrumbs -->
        <div class="text-sm font-medium text-gray-400 mb-2 font-serif tracking-wide">
            Dashboard <span class="mx-1 text-gray-300">›</span> Order Management <span class="mx-1 text-gray-300">›</span> <a href="<?= BASE_URL ?>/admin/orders/pending" class="text-gray-500 hover:text-brand-700">Pending Orders (By Buyer)</a> <span class="mx-1 text-gray-300">›</span> <span class="text-brand-700"><?= htmlspecialchars($buyer['name']) ?></span>
        </div>
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 w-full">
            <div>
                <h2 class="text-3xl sm:text-4xl font-display font-extrabold text-gray-900 tracking-tight flex items-center gap-3">
                    <?= htmlspecialchars($buyer['name']) ?>
                    <?php if (!empty($buyer['unique_buyer_id'])): ?>
                        <span class="text-xs px-2.5 py-1 rounded-full bg-gray-100 text-gray-700 font-mono font-medium">#<?= htmlspecialchars($buyer['unique_buyer_id']) ?></span>
                    <?php endif; ?>
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">All pending / uncompleted checkout sessions and products for this buyer.</p>
            </div>
            
            <div class="flex items-center space-x-3">
                <a href="<?= BASE_URL ?>/admin/orders/pending" class="bg-white hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-full font-bold text-xs uppercase tracking-wider shadow-sm border border-gray-200 transition-all flex items-center">
                    <svg class="w-4 h-4 mr-1.5 icon-16" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Back to Buyers List
                </a>
            </div>
        </div>
    </div>

    <!-- Buyer Profile Card -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
            <!-- Col 1: Avatar + Name -->
            <div class="flex items-center space-x-4 border-b md:border-b-0 md:border-r border-gray-100 pb-4 md:pb-0 pr-0 md:pr-4">
                <div class="w-14 h-14 rounded-2xl bg-[#fdf2f7] text-[#F25996] border border-[#fbaed2] flex items-center justify-center font-extrabold text-2xl shadow-sm flex-shrink-0">
                    <?= $initial ?>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-lg leading-tight"><?= htmlspecialchars($buyer['name']) ?></h3>
                    <?php if (!empty($buyer['business_name'])): ?>
                        <p class="text-xs font-semibold text-[#F25996] mt-0.5"><?= htmlspecialchars($buyer['business_name']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($buyer['shop_location'])): ?>
                        <p class="text-xs text-gray-400 mt-0.5"><?= htmlspecialchars($buyer['shop_location']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Col 2: Contact -->
            <div class="space-y-1.5 border-b md:border-b-0 md:border-r border-gray-100 pb-4 md:pb-0 pr-0 md:pr-4">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Contact Details</p>
                <div class="flex items-center text-sm font-semibold text-gray-800">
                    <svg class="w-4 h-4 mr-2 text-gray-400 icon-16" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    <?= htmlspecialchars($buyer['phone'] ?: 'N/A') ?>
                </div>
                <div class="flex items-center text-xs text-gray-600 truncate">
                    <svg class="w-4 h-4 mr-2 text-gray-400 icon-16" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <?= htmlspecialchars($buyer['email'] ?: 'N/A') ?>
                </div>
            </div>

            <!-- Col 3: Pending Orders Count -->
            <div class="border-b md:border-b-0 md:border-r border-gray-100 pb-4 md:pb-0 pr-0 md:pr-4">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Pending Checkout Sessions</p>
                <div class="flex items-center gap-2">
                    <span class="text-2xl font-black text-amber-600"><?= $totalPendingOrders ?></span>
                    <span class="text-xs font-medium text-gray-500"><?= $totalPendingOrders === 1 ? 'Unpaid Session' : 'Unpaid Sessions' ?></span>
                </div>
            </div>

            <!-- Col 4: Total Pending Value -->
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Pending Amount</p>
                <div class="text-2xl font-black text-gray-900">
                    ₹<?= number_format($totalPendingAmount, 2) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Title -->
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
            <span>Pending Checkout Sessions</span>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800"><?= $totalPendingOrders ?></span>
        </h3>
    </div>

    <!-- List of Pending Orders & Items -->
    <?php if (empty($orders)): ?>
        <div class="bg-white rounded-2xl p-12 text-center border border-gray-100 shadow-sm">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3 icon-48" width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <h4 class="text-base font-bold text-gray-800">No Pending Orders</h4>
            <p class="text-sm text-gray-500 mt-1">This buyer has no pending or cancelled checkout attempts.</p>
        </div>
    <?php else: ?>
        <div class="space-y-6">
            <?php foreach ($orders as $index => $order): 
                $items = $order['items'] ?? [];
                $feeBreakdown = !empty($order['fee_breakdown']) ? json_decode($order['fee_breakdown'], true) : null;
            ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Order Header Bar -->
                <div class="p-5 bg-gradient-to-r from-gray-50 to-amber-50/30 border-b border-gray-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="px-3 py-1 bg-amber-100 text-amber-900 font-mono font-bold text-sm rounded-lg border border-amber-200 shadow-xs">
                            <?= htmlspecialchars($order['order_number']) ?>
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            Payment Pending
                        </span>
                        <span class="text-xs text-gray-500 font-medium">
                            <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?>
                        </span>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <span class="text-xs text-gray-500 block">Session Total</span>
                            <span class="text-lg font-black text-gray-900">₹<?= number_format((float)($order['grand_total'] ?? $order['total_amount']), 2) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Products Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3.5">Product & Variant</th>
                                <th class="px-6 py-3.5 text-center">Unit Price</th>
                                <th class="px-6 py-3.5 text-center">Quantity</th>
                                <th class="px-6 py-3.5 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if (empty($items)): ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-6 text-center text-gray-400 italic">No products recorded for this checkout session.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($items as $item): ?>
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3.5">
                                            <div class="h-12 w-12 flex-shrink-0 bg-gray-100 rounded-xl overflow-hidden border border-gray-200">
                                                <?php if (!empty($item['image'])): ?>
                                                    <img src="<?= htmlspecialchars(get_image_url($item['image'])) ?>" alt="" class="h-full w-full object-cover" loading="lazy">
                                                <?php else: ?>
                                                    <div class="h-full w-full flex items-center justify-center text-gray-300">
                                                        <svg class="w-6 h-6 icon-24" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900"><?= htmlspecialchars($item['name']) ?></p>
                                                <div class="flex flex-wrap items-center gap-2 mt-0.5">
                                                    <?php if (!empty($item['variant_name'])): ?>
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                            <?= htmlspecialchars($item['variant_name']) ?>
                                                        </span>
                                                    <?php endif; ?>
                                                    <?php if (!empty($item['weight'])): ?>
                                                        <span class="text-xs text-gray-400"><?= floatval($item['weight']) ?> kg</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center font-medium text-gray-900">
                                        ₹<?= number_format((float)$item['unit_price'], 2) ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-block px-2.5 py-1 bg-gray-100 text-gray-800 rounded-md font-bold text-xs">
                                            <?= (int)$item['quantity'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-gray-900">
                                        ₹<?= number_format((float)($item['unit_price'] * $item['quantity']), 2) ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Footer Summary / Address for this attempt -->
                <div class="p-5 bg-gray-50/60 border-t border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <!-- Shipping Address -->
                    <div>
                        <span class="font-bold text-gray-500 uppercase tracking-wider block mb-1">Delivery Address</span>
                        <p class="text-gray-700 font-medium whitespace-pre-line leading-relaxed"><?= htmlspecialchars($order['shipping_address'] ?: 'Not provided') ?></p>
                    </div>

                    <!-- Pricing & Fees Breakdown -->
                    <div class="space-y-1.5 md:text-right">
                        <div class="flex justify-between md:justify-end md:gap-8 text-gray-600">
                            <span>Subtotal:</span>
                            <span class="font-semibold text-gray-800">₹<?= number_format((float)$order['total_amount'], 2) ?></span>
                        </div>
                        <?php if (!empty($feeBreakdown)): ?>
                            <?php foreach ($feeBreakdown as $fee): ?>
                                <div class="flex justify-between md:justify-end md:gap-8 text-gray-600">
                                    <span><?= htmlspecialchars($fee['name']) ?>:</span>
                                    <span class="font-semibold text-gray-800">₹<?= number_format((float)$fee['amount'], 2) ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <?php if (($order['shipping_fee'] ?? 0) > 0): ?>
                                <div class="flex justify-between md:justify-end md:gap-8 text-gray-600">
                                    <span>Shipping Fee:</span>
                                    <span class="font-semibold text-gray-800">₹<?= number_format((float)$order['shipping_fee'], 2) ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (($order['platform_fee'] ?? 0) > 0): ?>
                                <div class="flex justify-between md:justify-end md:gap-8 text-gray-600">
                                    <span>Platform Fee:</span>
                                    <span class="font-semibold text-gray-800">₹<?= number_format((float)$order['platform_fee'], 2) ?></span>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <div class="flex justify-between md:justify-end md:gap-8 text-sm font-black text-gray-900 pt-1 border-t border-gray-200">
                            <span>Grand Total:</span>
                            <span>₹<?= number_format((float)($order['grand_total'] ?? $order['total_amount']), 2) ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
