<?php
/**
 * @var string $title
 * @var array  $order
 * @var array  $items
 */

$statusColors = [
    'placed'           => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
    'packed'           => 'bg-orange-100 text-orange-800 border border-orange-200',
    'shipped'          => 'bg-blue-100 text-blue-800 border border-blue-200',
    'out_for_delivery' => 'bg-indigo-100 text-indigo-800 border border-indigo-200',
    'delivered'        => 'bg-green-100 text-green-800 border border-green-200',
    'cancelled'        => 'bg-red-100 text-red-800 border border-red-200',
    'pending'          => 'bg-gray-100 text-gray-700 border border-gray-200',
];
$statusColor  = $statusColors[$order['status']] ?? 'bg-gray-100 text-gray-700 border border-gray-200';
$statusLabels = [
    'placed'           => 'Order Placed',
    'packed'           => 'Packed',
    'shipped'          => 'Shipped',
    'out_for_delivery' => 'Out for Delivery',
    'delivered'        => 'Delivered',
    'cancelled'        => 'Cancelled',
    'pending'          => 'Pending',
];
$statusLabel = $statusLabels[$order['status']] ?? ucfirst($order['status']);
$grandTotal = ($order['grand_total'] ?? 0) > 0 ? $order['grand_total'] : $order['total_amount'];
?>

<div class="bg-[#fafafa] min-h-screen flex flex-col md:flex-row relative">
    <?php include __DIR__ . '/_sidebar.php'; ?>
    <div class="flex-1 p-4 md:p-10 relative overflow-hidden">
        <div class="max-w-7xl mx-auto w-full">

            <div class="mb-5 flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-900 tracking-tight">Order Details &mdash; <?= htmlspecialchars($order['order_number']) ?></h1>
                <a href="<?= BASE_URL ?>/dashboard/orders"
                   class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 font-medium group">
                    <svg class="w-4 h-4 mr-1 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to My Orders
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

                <div class="lg:col-span-3 space-y-6">

                    <?php if (!empty($modification)): ?>
                    <div class="p-5 rounded-2xl border bg-blue-50 border-blue-200 text-blue-900 shadow-sm">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="p-2 bg-blue-100 text-blue-700 rounded-xl">⚡</span>
                            <div>
                                <h3 class="font-bold text-base">Order Bill Modified by Admin</h3>
                                <p class="text-xs text-blue-700">Modified on <?= date('M j, Y, h:i A', strtotime($modification['created_at'])) ?></p>
                            </div>
                        </div>
                        <?php if (!empty($modification['reason'])): ?>
                            <p class="text-xs text-blue-800 bg-blue-100/70 p-2.5 rounded-lg border border-blue-200 mb-2 font-medium">
                                <strong>Reason:</strong> <?= htmlspecialchars($modification['reason']) ?>
                            </p>
                        <?php endif; ?>
                        <div class="flex flex-wrap gap-4 text-xs font-semibold border-t border-blue-200/80 pt-2.5 mt-2 text-blue-900">
                            <div>Original Total: <span class="line-through text-blue-600">₹<?= number_format($modification['original_total'], 2) ?></span></div>
                            <div>&rarr; Revised Total: <span class="font-bold text-blue-950">₹<?= number_format($modification['revised_total'], 2) ?></span></div>
                            <?php $diff = $modification['revised_total'] - $modification['original_total']; ?>
                            <?php if ($diff > 0): ?>
                                <div class="text-red-600 font-bold">Balance Due: +₹<?= number_format($diff, 2) ?></div>
                            <?php elseif ($diff < 0): ?>
                                <div class="text-green-700 font-bold">Refund Amount: -₹<?= number_format(abs($diff), 2) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                            <h2 class="text-lg font-bold text-gray-900">Order Items</h2>
                            <span class="px-3 py-1 bg-<?= $order['payment_status'] === 'paid' ? 'green' : 'amber' ?>-100 text-<?= $order['payment_status'] === 'paid' ? 'green' : 'amber' ?>-800 rounded-full text-xs font-bold uppercase tracking-wide">
                                <?= htmlspecialchars(strtoupper($order['payment_status'])) ?>
                            </span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-600">
                                <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold border-b border-gray-100">
                                    <tr>
                                        <th class="px-6 py-4">Product</th>
                                        <th class="px-6 py-4">Price</th>
                                        <th class="px-6 py-4 text-center">Qty</th>
                                        <th class="px-6 py-4 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    <?php 
                                    $calculatedItemsSubtotal = 0;
                                    foreach ($items as $item):
                                        $itemTotal = (float)$item['unit_price'] * (int)$item['quantity'];
                                        $calculatedItemsSubtotal += $itemTotal;
                                        $rawImg = $item['image'] ?? '';
                                        if (empty($rawImg)) { $imgUrl = null; }
                                        elseif (str_starts_with($rawImg, 'http://') || str_starts_with($rawImg, 'https://')) { $imgUrl = $rawImg; }
                                        else { $imgUrl = BASE_URL . '/' . ltrim($rawImg, '/'); }
                                    ?>
                                    <tr class="hover:bg-gray-50/70 transition-colors">
                                        <td class="px-6 py-5">
                                            <div class="flex items-center gap-4">
                                                <div class="w-14 h-14 flex-shrink-0 rounded-xl border border-gray-100 overflow-hidden bg-gray-50">
                                                    <?php if ($imgUrl): ?>
                                                        <img src="<?= htmlspecialchars($imgUrl) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-full h-full object-cover">
                                                    <?php else: ?>
                                                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-gray-900 leading-tight"><?= htmlspecialchars($item['name']) ?></p>
                                                    <div class="flex items-center gap-2 mt-1 flex-wrap">
                                                        <?php if (!empty($item['variant_name'])): ?>
                                                            <span class="inline-block text-xs font-medium text-[#F25996] bg-pink-50 border border-pink-200 px-2 py-0.5 rounded-full">
                                                                Variant: <?= htmlspecialchars($item['variant_name']) ?>
                                                            </span>
                                                        <?php endif; ?>
                                                        <?php if (!empty($item['category_hsn_code'])): ?>
                                                            <span class="inline-block text-xs font-mono font-bold text-[#F25996] bg-pink-50 border border-pink-200 px-2 py-0.5 rounded-full">
                                                                HSN: <?= htmlspecialchars($item['category_hsn_code']) ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <?php if (!empty($item['weight'])): ?>
                                                        <p class="text-xs text-gray-400 mt-0.5"><?= floatval($item['weight']) ?> <?= htmlspecialchars($item['weight_unit'] ?? 'kg') ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 font-medium text-gray-900 whitespace-nowrap">₹<?= number_format($item['unit_price'], 2) ?></td>
                                        <td class="px-6 py-5 text-center font-semibold text-gray-800"><?= (int)$item['quantity'] ?></td>
                                        <td class="px-6 py-5 text-right font-bold text-gray-900 whitespace-nowrap">₹<?= number_format($itemTotal, 2) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="p-6 bg-gradient-to-br from-gray-50 to-white border-t border-gray-100">
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Payment Summary</h3>
                            <div class="space-y-2.5 text-sm">
                                <?php
                                $revisedTotal = (float)($order['grand_total'] ?? $order['total_amount']);
                                $otherFees = max(0, $revisedTotal - $calculatedItemsSubtotal);
                                ?>
                                <div class="flex justify-between items-center text-gray-600">
                                    <span><?= !empty($modification) ? 'Revised Items Subtotal' : 'Subtotal' ?></span>
                                    <span class="font-medium text-gray-800">₹<?= number_format($calculatedItemsSubtotal, 2) ?></span>
                                </div>
                                <?php if (!empty($modification)): ?>
                                    <?php if ($otherFees > 0): ?>
                                    <div class="flex justify-between items-center text-gray-600">
                                        <span>Other Fees (Shipping, Platform, Extra Charges, etc.)</span>
                                        <span class="font-medium text-gray-800">+₹<?= number_format($otherFees, 2) ?></span>
                                    </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php
                                    $feeBreakdown = !empty($order['fee_breakdown']) ? json_decode($order['fee_breakdown'], true) : null;
                                    if (!empty($feeBreakdown)): 
                                        foreach ($feeBreakdown as $fee):
                                    ?>
                                        <div class="flex justify-between items-center text-gray-600">
                                            <span>
                                                <?= htmlspecialchars($fee['name']) ?>
                                                <?php if (!empty($fee['note'])): ?>
                                                    <span class="text-xs text-gray-400 ml-1">(<?= htmlspecialchars($fee['note']) ?>)</span>
                                                <?php endif; ?>
                                            </span>
                                            <span class="font-medium text-gray-800">₹<?= number_format($fee['amount'], 2) ?></span>
                                        </div>
                                    <?php 
                                        endforeach; 
                                    else: 
                                    ?>
                                        <?php if (($order['shipping_fee'] ?? 0) > 0): ?>
                                        <div class="flex justify-between items-center text-gray-600">
                                            <span>Shipping Fee</span>
                                            <span class="font-medium text-gray-800">₹<?= number_format($order['shipping_fee'], 2) ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <?php if (($order['weight_fee'] ?? 0) > 0): ?>
                                        <div class="flex justify-between items-center text-gray-600">
                                            <span>Weight Fee</span>
                                            <span class="font-medium text-gray-800">₹<?= number_format($order['weight_fee'], 2) ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <?php if (($order['platform_fee'] ?? 0) > 0): ?>
                                        <div class="flex justify-between items-center text-gray-600">
                                            <span>Platform Fee</span>
                                            <span class="font-medium text-gray-800">₹<?= number_format($order['platform_fee'], 2) ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <?php if (($order['packaging_fee'] ?? 0) > 0): ?>
                                        <div class="flex justify-between items-center text-gray-600">
                                            <span>Packing Fee</span>
                                            <span class="font-medium text-gray-800">₹<?= number_format($order['packaging_fee'], 2) ?></span>
                                        </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php
                                    $unroundedDetailTotal = (float)$calculatedItemsSubtotal + (float)($order['shipping_fee'] ?? 0) + (float)($order['weight_fee'] ?? 0) + (float)($order['platform_fee'] ?? 0) + (float)($order['packaging_fee'] ?? 0);
                                    $finalDisplayTotal = (float)($revisedTotal > 0 ? $revisedTotal : round($unroundedDetailTotal));
                                    $detailRoundOff = round($finalDisplayTotal - $unroundedDetailTotal, 2);
                                ?>
                                <?php if ($detailRoundOff > 0 || abs($detailRoundOff) > 0.001): ?>
                                <div class="flex justify-between items-center text-gray-600">
                                    <span>Round Off</span>
                                    <span class="font-medium text-gray-800"><?= $detailRoundOff > 0 ? '+ ₹' . number_format($detailRoundOff, 2) : '₹0.00' ?></span>
                                </div>
                                <?php endif; ?>

                                <div class="border-t border-gray-200 pt-3 mt-1">
                                    <div class="flex justify-between items-center">
                                        <span class="text-base font-bold text-gray-900"><?= !empty($modification) ? 'Revised Grand Total' : 'Grand Total' ?></span>
                                        <span class="text-xl font-black text-[#1e1b4b]">₹<?= number_format($finalDisplayTotal, 2) ?></span>
                                    </div>
                                    <div class="text-xs text-blue-700 bg-blue-50/60 rounded px-2.5 py-1 mt-1.5 font-medium">
                                        <span class="text-[10px] uppercase font-bold text-gray-400 block">In Words:</span>
                                        <?= htmlspecialchars(\App\Services\InvoiceService::numberToWordsIndian($finalDisplayTotal)) ?>
                                    </div>
                                </div>

                                <?php if (!empty($modification)): 
                                    $origPaid = (float)$modification['original_total'];
                                    $diff = $revisedTotal - $origPaid;
                                ?>
                                <div class="bg-pink-50/70 p-3.5 rounded-xl border border-pink-100 mt-3 space-y-2 text-xs">
                                    <div class="flex justify-between text-gray-700">
                                        <span>Amount Already Paid (Original Total):</span>
                                        <span class="font-semibold text-gray-900">₹<?= number_format($origPaid, 2) ?></span>
                                    </div>
                                    <?php if ($diff > 0): ?>
                                        <div class="flex justify-between font-bold text-red-600 pt-1.5 border-t border-pink-100 text-sm">
                                            <span>Balance Amount (Due):</span>
                                            <span>+₹<?= number_format($diff, 2) ?></span>
                                        </div>
                                    <?php elseif ($diff < 0): ?>
                                        <div class="flex justify-between font-bold text-green-700 pt-1.5 border-t border-pink-100 text-sm">
                                            <span>Refund Amount:</span>
                                            <span>-₹<?= number_format(abs($diff), 2) ?></span>
                                        </div>
                                    <?php else: ?>
                                        <div class="flex justify-between font-bold text-gray-600 pt-1.5 border-t border-pink-100 text-sm">
                                            <span>Balance Amount:</span>
                                            <span>₹0.00</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>

                                <?php if (($order['cod_advance_paid'] ?? 0) > 0): ?>
                                <div class="border-t border-gray-100 pt-2 mt-1 space-y-1.5 text-xs text-gray-500">
                                    <div class="flex justify-between"><span>COD Advance Paid</span><span>₹<?= number_format($order['cod_advance_paid'], 2) ?></span></div>
                                    <div class="flex justify-between"><span>Balance (Cash on Delivery)</span><span>₹<?= number_format($order['balance_amount'], 2) ?></span></div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-5">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Order Info</h3>
                        <div class="space-y-3 text-sm">
                            <div>
                                <p class="text-gray-400 text-xs font-semibold uppercase tracking-wide mb-0.5">Order Number</p>
                                <p class="font-bold text-gray-900 font-mono text-sm">#<?= htmlspecialchars($order['order_number']) ?></p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs font-semibold uppercase tracking-wide mb-0.5">Placed On</p>
                                <p class="font-medium text-gray-700"><?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs font-semibold uppercase tracking-wide mb-0.5">Total Paid</p>
                                <p class="text-xl font-black text-[#F25996]">₹<?= number_format($grandTotal, 2) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Order Status</h3>
                        <span class="inline-block px-4 py-2 rounded-xl text-sm font-bold uppercase tracking-wide <?= $statusColor ?>">
                            <?= $statusLabel ?>
                        </span>
                        <?php if (in_array($order['status'], ['shipped', 'out_for_delivery', 'delivered'])): ?>
                        <div class="mt-5 pt-4 border-t border-gray-100">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Courier</h4>
                            <p class="text-sm font-bold text-gray-900"><?= htmlspecialchars($order['courier_name'] ?: 'Not Specified') ?></p>
                            <?php if (!empty($order['tracking_number'])): ?>
                                <p class="text-xs text-gray-500 mt-1">Tracking: <span class="font-medium text-gray-700"><?= htmlspecialchars($order['tracking_number']) ?></span></p>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        <?php
                        $steps = [
                            'placed'           => ['label' => 'Order Placed',     'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                            'packed'           => ['label' => 'Packed',           'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10'],
                            'shipped'          => ['label' => 'Shipped',          'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1 12a2 2 0 002 2h8a2 2 0 002-2L19 8'],
                            'out_for_delivery' => ['label' => 'Out for Delivery', 'icon' => 'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0'],
                            'delivered'        => ['label' => 'Delivered',        'icon' => 'M5 13l4 4L19 7'],
                        ];
                        $stepKeys   = array_keys($steps);
                        $currentIdx = array_search($order['status'], $stepKeys);
                        if ($order['status'] !== 'cancelled' && $currentIdx !== false):
                        ?>
                        <div class="mt-5 pt-4 border-t border-gray-100">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Progress</h4>
                            <ol class="space-y-3">
                                <?php foreach ($steps as $key => $step):
                                    $idx    = array_search($key, $stepKeys);
                                    $done   = $idx <= $currentIdx;
                                    $active = ($key === $order['status']);
                                ?>
                                <li class="flex items-center gap-3">
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0
                                        <?= $done ? ($active ? 'bg-[#F25996] text-white' : 'bg-green-500 text-white') : 'bg-gray-100 text-gray-400' ?>">
                                        <?php if ($done && !$active): ?>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        <?php else: ?>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $step['icon'] ?>"/></svg>
                                        <?php endif; ?>
                                    </div>
                                    <span class="text-sm <?= $active ? 'font-bold text-[#F25996]' : ($done ? 'font-medium text-gray-600' : 'text-gray-400') ?>">
                                        <?= $step['label'] ?>
                                    </span>
                                </li>
                                <?php endforeach; ?>
                            </ol>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Shipping Address</h3>
                        <div class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <div class="text-sm text-gray-600 whitespace-pre-line leading-relaxed">
                                <?= nl2br(htmlspecialchars($order['shipping_address'])) ?>
                            </div>
                        </div>
                    </div>

                    <?php if ($order['payment_status'] === 'pending'): ?>
                    <a href="<?= BASE_URL ?>/checkout/retry?order_id=<?= urlencode($order['order_number']) ?>"
                       class="flex items-center justify-center gap-2 w-full py-3 bg-[#F25996] hover:bg-[#d8407d]
                              text-white font-bold rounded-xl shadow-md transition-all text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Complete Payment
                    </a>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>
