<?php
/**
 * @var string $title
 * @var array $order
 * @var array $items
 */
?>

<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <h1 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($title) ?></h1>
    <div class="flex items-center gap-3">
        <a href="<?= BASE_URL ?>/admin/orders/invoice?order=<?= htmlspecialchars($order['order_number']) ?>" target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 text-white text-sm font-semibold rounded-lg hover:bg-slate-800 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Download Invoice PDF
        </a>
        <?php if (empty($isVendor)): ?>
        <a href="<?= BASE_URL ?>/admin/order-modifications/create?order=<?= htmlspecialchars($order['order_number']) ?>"
           class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 text-white text-sm font-semibold rounded-lg hover:bg-amber-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Modify Bill
        </a>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>/admin/orders" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 font-medium">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Orders
        </a>
    </div>
</div>

<?php
    $calcTotalBase = 0;
    $calcTotalSgst = 0;
    $calcTotalCgst = 0;
    $calcItems = [];
    foreach ($items as $item) {
        $qty = (int)($item['quantity'] ?? 0);
        $unitPrice = (float)($item['unit_price'] ?? 0);

        $rawBase = 0.0;
        if (!empty($item['variant_discount_price']) && (float)$item['variant_discount_price'] > 0) {
            $rawBase = (float)$item['variant_discount_price'];
        } elseif (!empty($item['variant_base_price']) && (float)$item['variant_base_price'] > 0) {
            $rawBase = (float)$item['variant_base_price'];
        } elseif (!empty($item['wholesale_price']) && (float)$item['wholesale_price'] > 0) {
            $rawBase = (float)$item['wholesale_price'];
        }

        $sgstRate = 0.0;
        $cgstRate = 0.0;

        $vSgst = (float)($item['variant_sgst'] ?? 0);
        $vCgst = (float)($item['variant_cgst'] ?? 0);
        $vTot  = (float)($item['variant_total_gst'] ?? 0);

        $pSgst = (float)($item['product_sgst'] ?? 0);
        $pCgst = (float)($item['product_cgst'] ?? 0);
        $pTot  = (float)($item['product_total_gst'] ?? 0);

        $cSgst = (float)($item['category_sgst'] ?? 0);
        $cCgst = (float)($item['category_cgst'] ?? 0);

        if ($vSgst > 0 || $vCgst > 0) {
            $sgstRate = $vSgst;
            $cgstRate = $vCgst;
        } elseif ($vTot > 0) {
            $sgstRate = round($vTot / 2, 2);
            $cgstRate = round($vTot / 2, 2);
        } elseif ($pSgst > 0 || $pCgst > 0) {
            $sgstRate = $pSgst;
            $cgstRate = $pCgst;
        } elseif ($pTot > 0) {
            $sgstRate = round($pTot / 2, 2);
            $cgstRate = round($pTot / 2, 2);
        } elseif ($cSgst > 0 || $cCgst > 0) {
            $sgstRate = $cSgst;
            $cgstRate = $cCgst;
        }

        $totGstRate = $sgstRate + $cgstRate;

        if ($rawBase > 0 && $unitPrice > $rawBase) {
            $unitTax = round($unitPrice - $rawBase, 2);
            $unitBase = $rawBase;
            $lineBase = round($unitBase * $qty, 2);
            $lineTax = round($unitTax * $qty, 2);
            $lineSgst = ($totGstRate > 0 && $sgstRate > 0) ? round($lineTax * ($sgstRate / $totGstRate), 2) : round($lineTax / 2, 2);
            $lineCgst = round($lineTax - $lineSgst, 2);
        } elseif ($rawBase > 0 && abs($unitPrice - $rawBase) < 0.01) {
            $unitBase = $unitPrice;
            $lineBase = round($unitBase * $qty, 2);
            $lineTax = 0.0;
            $lineSgst = 0.0;
            $lineCgst = 0.0;
        } elseif ($totGstRate > 0) {
            $unitBase = round($unitPrice / (1 + ($totGstRate / 100)), 2);
            $unitTax = round($unitPrice - $unitBase, 2);
            $lineBase = round($unitBase * $qty, 2);
            $lineTax = round($unitTax * $qty, 2);
            $lineSgst = ($totGstRate > 0 && $sgstRate > 0) ? round($lineTax * ($sgstRate / $totGstRate), 2) : round($lineTax / 2, 2);
            $lineCgst = round($lineTax - $lineSgst, 2);
        } else {
            $unitBase = $unitPrice;
            $lineBase = round($unitBase * $qty, 2);
            $lineTax = 0.0;
            $lineSgst = 0.0;
            $lineCgst = 0.0;
        }

        $lineTotal = round($unitPrice * $qty, 2);

        $calcTotalBase += $lineBase;
        $calcTotalSgst += $lineSgst;
        $calcTotalCgst += $lineCgst;

        $calcItems[] = array_merge($item, [
            'unit_base' => $unitBase,
            'line_base' => $lineBase,
            'sgst_rate' => $sgstRate,
            'cgst_rate' => $cgstRate,
            'tot_gst_rate' => $totGstRate,
            'line_sgst' => $lineSgst,
            'line_cgst' => $lineCgst,
            'line_total' => $lineTotal
        ]);
    }
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Order Items & Pricing -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-900">Order Items</h2>
                <span class="px-3 py-1 bg-<?= $order['payment_status'] === 'paid' ? 'green' : 'amber' ?>-100 text-<?= $order['payment_status'] === 'paid' ? 'green' : 'amber' ?>-800 rounded-full text-xs font-bold uppercase tracking-wide">
                    <?= htmlspecialchars($order['payment_status']) ?>
                </span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50/50 text-xs uppercase text-gray-500 font-semibold border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4">Product</th>
                            <th class="px-4 py-4 text-right">Base Price</th>
                            <th class="px-4 py-4 text-center">Tax (GST)</th>
                            <th class="px-4 py-4 text-center">Qty</th>
                            <th class="px-6 py-4 text-right">Total (Incl. Tax)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($calcItems as $item): ?>
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="h-10 w-10 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                        <?php if ($item['image']): ?>
                                            <img src="<?= htmlspecialchars(get_image_url($item['image'])) ?>" alt="" class="h-full w-full object-cover" loading="lazy" decoding="async">
                                        <?php else: ?>
                                            <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900"><?= htmlspecialchars($item['name']) ?></p>
                                        <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                                            <?php if (!empty($item['variant_name'])): ?>
                                                <p class="text-xs text-gray-500 font-medium">Variant: <?= htmlspecialchars($item['variant_name']) ?></p>
                                            <?php endif; ?>
                                            <?php if (!empty($item['category_hsn_code'])): ?>
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10.5px] font-mono font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                                    HSN: <?= htmlspecialchars($item['category_hsn_code']) ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if (!empty($item['vendor_store_name'])): ?>
                                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10.5px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                                    Vendor: <?= htmlspecialchars($item['vendor_store_name']) ?>
                                                </span>
                                            <?php endif; ?>
                                            <p class="text-xs text-gray-500">Weight: <?= floatval($item['weight']) ?> kg</p>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-right font-medium text-gray-700">₹<?= number_format($item['unit_base'], 2) ?></td>
                            <td class="px-4 py-4 text-center">
                                <?php if ($item['tot_gst_rate'] > 0): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                        <?= $item['tot_gst_rate'] ?>% (S:<?= $item['sgst_rate'] ?>% + C:<?= $item['cgst_rate'] ?>%)
                                    </span>
                                <?php else: ?>
                                    <span class="text-gray-400 text-xs">0%</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-4 text-center font-medium"><?= (int)$item['quantity'] ?></td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900">₹<?= number_format($item['line_total'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="p-6 bg-gray-50 border-t border-gray-100">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Payment Summary</h3>
                <div class="space-y-2.5 text-sm">
                    <div class="flex justify-between items-center text-gray-600">
                        <span>Taxable Base Subtotal</span>
                        <span class="font-medium text-gray-800">₹<?= number_format($calcTotalBase, 2) ?></span>
                    </div>
                    <?php if ($calcTotalSgst > 0 || $calcTotalCgst > 0): ?>
                    <div class="flex justify-between items-center text-blue-700 bg-blue-50/50 px-2 py-1 rounded">
                        <span>Total SGST</span>
                        <span class="font-semibold">+ ₹<?= number_format($calcTotalSgst, 2) ?></span>
                    </div>
                    <div class="flex justify-between items-center text-blue-700 bg-blue-50/50 px-2 py-1 rounded">
                        <span>Total CGST</span>
                        <span class="font-semibold">+ ₹<?= number_format($calcTotalCgst, 2) ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="flex justify-between items-center text-gray-600 border-t border-gray-200/60 pt-2">
                        <span><?= !empty($isVendor) ? 'Your Items Subtotal (Incl. GST)' : 'Items Total (Incl. GST)' ?></span>
                        <span class="font-bold text-gray-900">₹<?= number_format(!empty($isVendor) ? ($calcTotalBase + $calcTotalSgst + $calcTotalCgst) : $order['total_amount'], 2) ?></span>
                    </div>
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
                    <?php
                        $unroundedOrderTotal = (float)($order['total_amount'] ?? 0) + (float)($order['shipping_fee'] ?? 0) + (float)($order['weight_fee'] ?? 0) + (float)($order['platform_fee'] ?? 0) + (float)($order['packaging_fee'] ?? 0);
                        $grandTotal = ($order['grand_total'] ?? 0) > 0 ? (float)$order['grand_total'] : (float)round($unroundedOrderTotal);
                        $orderRoundOff = round($grandTotal - $unroundedOrderTotal, 2);
                    ?>
                    <?php if ($orderRoundOff > 0 || abs($orderRoundOff) > 0.001): ?>
                    <div class="flex justify-between items-center text-gray-600">
                        <span>Round Off</span>
                        <span class="font-medium text-gray-800"><?= $orderRoundOff > 0 ? '+ ₹' . number_format($orderRoundOff, 2) : '₹0.00' ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="border-t border-gray-200 pt-3 mt-1">
                        <div class="flex justify-between items-center">
                            <span class="text-base font-bold text-gray-900">Grand Total</span>
                            <span class="text-xl font-black text-gray-900">₹<?= number_format($grandTotal, 2) ?></span>
                        </div>
                        <div class="text-xs text-blue-700 bg-blue-50/60 rounded px-2 py-1 mt-1.5 font-medium">
                            <span class="text-[10px] uppercase font-bold text-gray-400 block">In Words:</span>
                            <?= htmlspecialchars(\App\Services\InvoiceService::numberToWordsIndian($grandTotal)) ?>
                        </div>
                    </div>
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

    <!-- Right Column: Customer & Shipping Details -->
    <div class="space-y-6">
        <!-- Status Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Current Status</h3>
            <div class="flex items-center space-x-3">
                <?php
                    $isTerminal = in_array($order['status'], ['delivered', 'cancelled']);
                ?>
                <?php if ($this->hasPermission('all_orders', 'edit') && !$isTerminal): ?>
                <div class="relative inline-block w-full">
                    <select class="status-dropdown text-sm font-bold rounded-xl pl-4 pr-10 py-2.5 border shadow-2xs transition-all duration-200 cursor-pointer appearance-none outline-none focus:ring-2 w-full" data-id="<?= htmlspecialchars($order['order_number']) ?>" data-original="<?= htmlspecialchars($order['status']) ?>">
                        <?php
                            $statuses = [
                                'placed'           => 'Placed',
                                'packed'           => 'Packed',
                                'shipped'          => 'Shipped',
                                'out_for_delivery' => 'Out for Delivery',
                                'delivered'        => 'Delivered'
                            ];
                            
                            $allowed_next = [
                                'placed'           => ['placed', 'packed'],
                                'packed'           => ['packed', 'shipped'],
                                'shipped'          => ['shipped', 'out_for_delivery'],
                                'out_for_delivery' => ['out_for_delivery', 'delivered'],
                                'delivered'        => ['delivered']
                            ];
                            
                            $currentStatus = $order['status'];
                            $allowedForCurrent = $allowed_next[$currentStatus] ?? [$currentStatus];
                            
                            foreach($statuses as $val => $label) {
                                if (!in_array($val, $allowedForCurrent)) continue;
                                $sel = ($currentStatus === $val) ? 'selected' : '';
                                echo "<option value=\"$val\" $sel class=\"bg-white text-slate-900 font-semibold py-2\">$label</option>";
                            }
                        ?>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-current opacity-70">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
                <?php else: ?>
                    <?php
                        $statusClass = 'bg-gray-100 text-gray-800 border-gray-200';
                        if ($order['status'] === 'placed') $statusClass = 'bg-amber-50 text-amber-800 border-amber-200';
                        elseif ($order['status'] === 'packed') $statusClass = 'bg-purple-50 text-purple-800 border-purple-200';
                        elseif ($order['status'] === 'shipped') $statusClass = 'bg-blue-50 text-blue-800 border-blue-200';
                        elseif ($order['status'] === 'out_for_delivery') $statusClass = 'bg-indigo-50 text-indigo-800 border-indigo-200';
                        elseif ($order['status'] === 'delivered') $statusClass = 'bg-emerald-50 text-emerald-800 border-emerald-200';
                        elseif ($order['status'] === 'cancelled') $statusClass = 'bg-rose-50 text-rose-800 border-rose-200';
                        
                        $formattedStatus = ucwords(str_replace('_', ' ', $order['status']));
                    ?>
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold border shadow-2xs <?= $statusClass ?>">
                        <?php if ($order['status'] === 'delivered'): ?>
                            <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <?php elseif ($order['status'] === 'cancelled'): ?>
                            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        <?php endif; ?>
                        <?= $formattedStatus ?>
                    </span>
                <?php endif; ?>
            </div>
            
            <?php if ($order['status'] === 'shipped' || $order['status'] === 'out_for_delivery' || $order['status'] === 'delivered'): ?>
            <div class="mt-6 pt-4 border-t border-gray-100">
                <h4 class="text-xs font-semibold text-gray-500 mb-1">Courier Information</h4>
                <p class="text-sm font-bold text-gray-900"><?= htmlspecialchars($order['courier_name'] ?: 'Not Specified') ?></p>
                <?php if ($order['tracking_number']): ?>
                    <p class="text-xs text-gray-500 mt-1">Tracking: <span class="font-medium text-gray-700"><?= htmlspecialchars($order['tracking_number']) ?></span></p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Packing & Verification Videos Card -->
        <?php if (!empty($order['packing_video_1']) || !empty($order['packing_video_2']) || !empty($order['packed_at']) || $order['status'] === 'packed'): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Packing Verification</h3>
                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-purple-100 text-purple-700">📦 Packed</span>
            </div>
            
            <?php if (!empty($order['packed_at'])): ?>
                <p class="text-xs text-gray-500 mb-3">Packed on: <span class="font-semibold text-gray-800"><?= date('d M Y, h:i A', strtotime($order['packed_at'])) ?></span></p>
            <?php endif; ?>

            <div class="space-y-2.5">
                <?php if (!empty($order['packing_video_1'])): 
                    $v1Url = (strpos($order['packing_video_1'], 'http') === 0) ? $order['packing_video_1'] : (BASE_URL . '/' . ltrim($order['packing_video_1'], '/'));
                ?>
                <a href="<?= htmlspecialchars($v1Url) ?>" target="_blank" class="flex items-center justify-between p-3 rounded-xl bg-purple-50 hover:bg-purple-100 border border-purple-200 transition-colors group">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-purple-600 text-white flex items-center justify-center font-bold text-xs">1</div>
                        <span class="text-xs font-bold text-purple-900">Packing Video 1</span>
                    </div>
                    <span class="text-xs font-semibold text-purple-700 group-hover:underline flex items-center gap-1">
                        Watch / Download
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </span>
                </a>
                <?php endif; ?>

                <?php if (!empty($order['packing_video_2'])): 
                    $v2Url = (strpos($order['packing_video_2'], 'http') === 0) ? $order['packing_video_2'] : (BASE_URL . '/' . ltrim($order['packing_video_2'], '/'));
                ?>
                <a href="<?= htmlspecialchars($v2Url) ?>" target="_blank" class="flex items-center justify-between p-3 rounded-xl bg-purple-50 hover:bg-purple-100 border border-purple-200 transition-colors group">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-purple-600 text-white flex items-center justify-center font-bold text-xs">2</div>
                        <span class="text-xs font-bold text-purple-900">Packing Video 2</span>
                    </div>
                    <span class="text-xs font-semibold text-purple-700 group-hover:underline flex items-center gap-1">
                        Watch / Download
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </span>
                </a>
                <?php endif; ?>

                <?php if (empty($order['packing_video_1']) && empty($order['packing_video_2'])): ?>
                <p class="text-xs text-gray-500 italic">No packing videos uploaded yet.</p>
                <?php endif; ?>
            </div>

            <?php if ($order['status'] === 'delivered' || !empty($order['delivered_at'])): ?>
                <?php
                    $delTime = !empty($order['delivered_at']) ? strtotime($order['delivered_at']) : strtotime($order['updated_at'] ?? $order['created_at']);
                    $expiryTime = !empty($order['packing_video_expires_at']) ? strtotime($order['packing_video_expires_at']) : strtotime('+30 days', $delTime);
                    $daysRemaining = max(0, ceil(($expiryTime - time()) / 86400));
                ?>
                <div class="mt-3 pt-3 border-t border-gray-100 text-[11.5px] text-gray-500">
                    <div class="flex items-center justify-between">
                        <span>30-Day Video Retention:</span>
                        <span class="font-bold <?= $daysRemaining > 5 ? 'text-green-600' : 'text-amber-600' ?>"><?= $daysRemaining ?> days left</span>
                    </div>
                    <p class="text-[10.5px] text-gray-400 mt-0.5">Expires on: <?= date('d M Y', $expiryTime) ?></p>
                </div>
            <?php else: ?>
                <p class="mt-3 pt-2 border-t border-gray-100 text-[11px] text-gray-400">
                    ℹ️ 30-day customer retention timer begins once marked Delivered.
                </p>
            <?php endif; ?>

            <?php if ($this->hasPermission('all_orders', 'edit') && in_array($order['status'], ['placed', 'packed'])): ?>
            <div class="mt-4 pt-3 border-t border-gray-100">
                <button type="button" onclick="openPackingModal('<?= htmlspecialchars($order['order_number']) ?>', document.querySelector('.status-dropdown'))" class="w-full py-2 px-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-xs font-bold transition-colors flex items-center justify-center gap-1.5 shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <?= (!empty($order['packing_video_1']) || !empty($order['packing_video_2'])) ? 'Update Videos & Send Email' : 'Upload Packing Videos & Email' ?>
                </button>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Customer Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Customer Details</h3>
            <div class="flex items-center space-x-3 mb-4">
                <div class="h-10 w-10 rounded-full bg-brand-100 flex items-center justify-center text-brand-700 font-bold">
                    <?= strtoupper(substr($order['customer_name'], 0, 1)) ?>
                </div>
                <div>
                    <p class="font-bold text-gray-900"><?= htmlspecialchars($order['customer_name']) ?></p>
                    <a href="mailto:<?= htmlspecialchars($order['customer_email']) ?>" class="text-sm text-brand-600 hover:underline"><?= htmlspecialchars($order['customer_email']) ?></a>
                </div>
            </div>
            <p class="text-sm text-gray-600 flex items-center mt-2">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                <?= htmlspecialchars($order['customer_phone'] ?: 'N/A') ?>
            </p>
        </div>

        <!-- Shipping Address Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Shipping Address</h3>
            <div class="text-sm text-gray-600 whitespace-pre-line leading-relaxed">
                <?= htmlspecialchars($order['shipping_address']) ?>
            </div>
        </div>
    </div>
</div>

<!-- Packing Videos Modal -->
<div id="packingModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm" onclick="closePackingModal()"></div>
    
    <!-- Modal Content -->
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 transform transition-all max-h-[92vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Upload Order Packing Videos</h3>
                    <p class="text-xs text-gray-500">Order: <span id="packOrderNumberBadge" class="font-mono font-bold text-purple-700"></span></p>
                </div>
            </div>
            <button type="button" onclick="closePackingModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="bg-purple-50 border border-purple-200 rounded-xl p-3.5 mb-4 text-xs text-purple-900 flex items-start gap-2.5">
            <svg class="w-4 h-4 text-purple-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <strong>Automated Invoice & Video Workflow:</strong>
                <p class="text-[11.5px] text-purple-800 mt-0.5">Uploading packing videos marks this order as <strong>Packed</strong>, attaches the generated <strong>Tax Invoice PDF</strong>, and emails it directly to the customer. Packing videos remain available to the customer for <strong>30 days after delivery</strong>.</p>
            </div>
        </div>
        
        <form id="packingForm" onsubmit="submitPackingDetails(event)" enctype="multipart/form-data">
            <input type="hidden" id="packOrderId" name="order_id">
            
            <!-- Video 1 -->
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                    Packing Video 1 <span class="normal-case font-normal text-gray-500">(Item Verification &amp; Box Packing - Max 3MB)</span>
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-3 hover:border-purple-500 transition-colors bg-gray-50">
                    <input type="file" id="packingVideo1" name="packing_video_1" accept="video/*,.mp4,.webm,.mov" onchange="validateVideoSize(this, 'Packing Video 1')" class="w-full text-xs text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-purple-100 file:text-purple-700 hover:file:bg-purple-200 cursor-pointer">
                    <p class="text-[11px] text-gray-400 mt-1">MP4, WebM, MOV (Max 3MB limit)</p>
                </div>
            </div>
            
            <!-- Video 2 -->
            <div class="mb-5">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                    Packing Video 2 <span class="normal-case font-normal text-gray-500">(Box Sealing &amp; Label Verification - Max 3MB)</span>
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-3 hover:border-purple-500 transition-colors bg-gray-50">
                    <input type="file" id="packingVideo2" name="packing_video_2" accept="video/*,.mp4,.webm,.mov" onchange="validateVideoSize(this, 'Packing Video 2')" class="w-full text-xs text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-purple-100 file:text-purple-700 hover:file:bg-purple-200 cursor-pointer">
                    <p class="text-[11px] text-gray-400 mt-1">MP4, WebM, MOV (Max 3MB limit)</p>
                </div>
            </div>
            
            <div id="packingUploadStatus" class="hidden mb-4 p-3 bg-purple-100 border border-purple-300 rounded-xl text-center text-xs font-semibold text-purple-900">
                <div class="inline-block animate-spin rounded-full h-4 w-4 border-2 border-purple-700 border-t-transparent mr-2 align-middle"></div>
                Uploading packing videos &amp; emailing dynamic Tax Invoice PDF...
            </div>

            <div class="flex space-x-4" style="display: flex; gap: 1rem; width: 100%;">
                <button type="button" onclick="closePackingModal()" id="packingCancelBtn" class="font-bold transition-colors" style="flex: 1; padding: 0.625rem 0; background-color: #f3f4f6; color: #111827; border-radius: 0.75rem; text-align: center; border: 1px solid #e5e7eb;">Cancel</button>
                <button type="submit" id="packingSubmitBtn" class="font-bold transition-colors shadow-sm" style="flex: 1; padding: 0.625rem 0; background-color: #7c3aed; color: #ffffff; border-radius: 0.75rem; text-align: center; border: 1px solid #7c3aed;">Confirm &amp; Send Invoice</button>
            </div>
        </form>
    </div>
</div>

<!-- Tracking Details Modal -->
<div id="trackingModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm" onclick="closeTrackingModal()"></div>
    
    <!-- Modal Content -->
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6 transform transition-all">
        <div class="flex items-center space-x-2 mb-6">
            <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            <h3 class="text-xl font-bold text-gray-900">Enter Tracking Details</h3>
        </div>
        
        <form id="trackingForm" onsubmit="submitTrackingDetails(event)">
            <input type="hidden" id="trackOrderId">
            
            <div class="mb-4">
                <div class="flex justify-between items-end mb-1">
                    <label class="block text-sm font-bold text-gray-700">Courier Service</label>
                    <a href="#" class="text-xs font-bold text-green-700 hover:underline">+ Add / Manage</a>
                </div>
                <select id="trackCourier" required class="w-full px-3 py-2 border border-green-700 rounded-lg text-sm text-green-900 font-medium focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="" disabled selected>Select Courier Service</option>
                    <option value="ST Courier">stcourier</option>
                    <option value="A1">A1</option>
                    <option value="MSS">MSS</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-1">Tracking ID</label>
                <input type="text" id="trackId" placeholder="e.g. TRK123456789" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-gray-300">
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-1">Tracking URL</label>
                <input type="url" id="trackUrl" placeholder="https://tracker.com/TRK123" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-gray-300">
            </div>
            
            <div class="flex space-x-4" style="display: flex; gap: 1rem; width: 100%;">
                <button type="button" onclick="closeTrackingModal()" class="font-bold transition-colors" style="flex: 1; padding: 0.625rem 0; background-color: #f3f4f6; color: #111827; border-radius: 0.75rem; text-align: center; border: 1px solid #e5e7eb;">Cancel</button>
                <button type="submit" class="font-bold transition-colors shadow-sm" style="flex: 1; padding: 0.625rem 0; background-color: #22c55e; color: #ffffff; border-radius: 0.75rem; text-align: center; border: 1px solid #22c55e;">Save</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusDropdowns = document.querySelectorAll('.status-dropdown');
    
    // Style the dropdowns based on their current value with vibrant, high-contrast pills
    const updateDropdownStyle = (select) => {
        const val = select.value;
        let colorClasses = "bg-amber-50 hover:bg-amber-100/70 border-amber-300 text-amber-900 focus:ring-amber-400";
        if (val === 'packed') {
            colorClasses = "bg-purple-50 hover:bg-purple-100/70 border-purple-300 text-purple-900 focus:ring-purple-400";
        } else if (val === 'shipped') {
            colorClasses = "bg-blue-50 hover:bg-blue-100/70 border-blue-300 text-blue-900 focus:ring-blue-400";
        } else if (val === 'out_for_delivery') {
            colorClasses = "bg-indigo-50 hover:bg-indigo-100/70 border-indigo-300 text-indigo-900 focus:ring-indigo-400";
        } else if (val === 'delivered') {
            colorClasses = "bg-emerald-50 hover:bg-emerald-100/70 border-emerald-300 text-emerald-900 focus:ring-emerald-400";
        }
        
        select.className = `status-dropdown text-sm font-bold rounded-xl pl-4 pr-10 py-2.5 border shadow-2xs transition-all duration-200 cursor-pointer appearance-none outline-none focus:ring-2 w-full ${colorClasses}`;
    };

    const updateDropdownOptions = (select, currentStatus) => {
        // Strict forward-only progression (Cancelled removed from operational status update dropdown)
        const allowedNext = {
            'placed':           ['placed', 'packed'],
            'packed':           ['packed', 'shipped'],
            'shipped':          ['shipped', 'out_for_delivery'],
            'out_for_delivery': ['out_for_delivery', 'delivered'],
            'delivered':        ['delivered']
        };
        const allowed = allowedNext[currentStatus] || [currentStatus];
        
        Array.from(select.options).forEach(opt => {
            if (allowed.includes(opt.value)) {
                opt.disabled = false;
                opt.hidden = false;
            } else {
                opt.disabled = true;
                opt.hidden = true;
            }
        });
        select.value = currentStatus;
    };

    statusDropdowns.forEach(select => {
        updateDropdownStyle(select);
        updateDropdownOptions(select, select.getAttribute('data-original'));
        
        select.addEventListener('change', function() {
            const orderId = this.getAttribute('data-id');
            const originalStatus = this.getAttribute('data-original');
            const newStatus = this.value;

            if (newStatus === originalStatus) return;

            const formatCurrent = originalStatus.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
            const formatTarget = newStatus.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());

            // If moving to Packed, open packing modal directly
            if (newStatus === 'packed') {
                openPackingModal(orderId, select);
                return;
            }

            // If moving to Shipped, open tracking modal directly
            if (newStatus === 'shipped') {
                openTrackingModal(orderId, select);
                return;
            }
            
            Swal.fire({
                title: 'Update Order Status?',
                html: `Are you sure you want to transition Order <b>#${orderId}</b> from <span class="text-gray-600 font-semibold">${formatCurrent}</span> to <span class="text-brand-600 font-bold">${formatTarget}</span>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#F25996',
                cancelButtonColor: '#f3f4f6',
                confirmButtonText: 'Yes, Update Status',
                cancelButtonText: '<span style="color: #F25996; font-weight: bold;">Cancel</span>',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-3xl',
                    confirmButton: 'font-bold rounded-xl px-6 py-3',
                    cancelButton: 'font-bold rounded-xl px-6 py-3'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    updateStatusAjax(orderId, newStatus, select);
                } else {
                    select.value = originalStatus;
                    updateDropdownStyle(select);
                }
            });
        });
    });

    let currentTrackingSelect = null;
    let currentTrackingOrderId = null;

    window.openTrackingModal = function(orderId, selectEl) {
        currentTrackingOrderId = orderId;
        currentTrackingSelect = selectEl;
        
        document.getElementById('trackOrderId').value = orderId;
        document.getElementById('trackCourier').value = '';
        document.getElementById('trackId').value = '';
        document.getElementById('trackUrl').value = '';
        
        const modal = document.getElementById('trackingModal');
        modal.classList.remove('hidden');
    };

    window.closeTrackingModal = function() {
        const modal = document.getElementById('trackingModal');
        modal.classList.add('hidden');
        if (currentTrackingSelect) {
            currentTrackingSelect.value = currentTrackingSelect.getAttribute('data-original');
            updateDropdownStyle(currentTrackingSelect);
        }
        currentTrackingSelect = null;
        currentTrackingOrderId = null;
    };

    window.submitTrackingDetails = function(e) {
        e.preventDefault();
        
        const orderId = currentTrackingOrderId;
        const selectEl = currentTrackingSelect;
        const courier = document.getElementById('trackCourier').value;
        const trackingNum = document.getElementById('trackId').value;
        
        if (!courier) {
            alert("Please select a Courier Service.");
            return;
        }

        fetch('<?= BASE_URL ?>/admin/orders/update-courier', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ order_id: orderId, courier_name: courier, tracking_number: trackingNum })
        }).then(r => r.json()).then(res => {
            if(res.success) {
                document.getElementById('trackingModal').classList.add('hidden');
                updateStatusAjax(orderId, 'shipped', selectEl);
            } else {
                alert(res.message);
                closeTrackingModal();
            }
        }).catch(err => {
            console.error(err);
            alert("Failed to save tracking details.");
            closeTrackingModal();
        });
    };

    // --- Packing Videos Modal Handlers ---
    let currentPackingSelect = null;
    let currentPackingOrderId = null;

    window.openPackingModal = function(orderId, selectEl) {
        currentPackingOrderId = orderId;
        currentPackingSelect = selectEl;

        document.getElementById('packOrderId').value = orderId;
        document.getElementById('packOrderNumberBadge').textContent = '#' + orderId;
        document.getElementById('packingVideo1').value = '';
        document.getElementById('packingVideo2').value = '';
        document.getElementById('packingUploadStatus').classList.add('hidden');
        document.getElementById('packingSubmitBtn').disabled = false;
        document.getElementById('packingCancelBtn').disabled = false;

        const modal = document.getElementById('packingModal');
        modal.classList.remove('hidden');
    };

    window.closePackingModal = function() {
        const modal = document.getElementById('packingModal');
        modal.classList.add('hidden');
        if (currentPackingSelect) {
            currentPackingSelect.value = currentPackingSelect.getAttribute('data-original');
            updateDropdownStyle(currentPackingSelect);
        }
        currentPackingSelect = null;
        currentPackingOrderId = null;
    };

    window.validateVideoSize = function(input, labelName) {
        const maxBytes = 3 * 1024 * 1024;
        if (input.files && input.files[0]) {
            if (input.files[0].size > maxBytes) {
                const mb = (input.files[0].size / (1024 * 1024)).toFixed(2);
                Swal.fire({
                    title: 'File Too Large (Max 3MB)',
                    text: `${labelName} is ${mb}MB. Maximum allowed file size is 3MB. Please select a smaller video file.`,
                    icon: 'warning',
                    confirmButtonColor: '#7c3aed'
                });
                input.value = '';
                return false;
            }
        }
        return true;
    };

    window.submitPackingDetails = function(e) {
        e.preventDefault();

        const maxBytes = 3 * 1024 * 1024;
        const v1Input = document.getElementById('packingVideo1');
        const v2Input = document.getElementById('packingVideo2');

        if (v1Input && v1Input.files && v1Input.files[0] && v1Input.files[0].size > maxBytes) {
            const mb = (v1Input.files[0].size / (1024 * 1024)).toFixed(2);
            Swal.fire({
                title: 'File Too Large (Max 3MB)',
                text: `Packing Video 1 is ${mb}MB. Maximum allowed file size is 3MB. Please select a smaller video file.`,
                icon: 'warning',
                confirmButtonColor: '#7c3aed'
            });
            return false;
        }
        if (v2Input && v2Input.files && v2Input.files[0] && v2Input.files[0].size > maxBytes) {
            const mb = (v2Input.files[0].size / (1024 * 1024)).toFixed(2);
            Swal.fire({
                title: 'File Too Large (Max 3MB)',
                text: `Packing Video 2 is ${mb}MB. Maximum allowed file size is 3MB. Please select a smaller video file.`,
                icon: 'warning',
                confirmButtonColor: '#7c3aed'
            });
            return false;
        }

        const form = document.getElementById('packingForm');
        const formData = new FormData(form);
        const orderId = currentPackingOrderId;
        const selectEl = currentPackingSelect;

        document.getElementById('packingUploadStatus').classList.remove('hidden');
        document.getElementById('packingSubmitBtn').disabled = true;
        document.getElementById('packingCancelBtn').disabled = true;

        fetch('<?= BASE_URL ?>/admin/orders/update-packed', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(res => {
            document.getElementById('packingUploadStatus').classList.add('hidden');
            document.getElementById('packingSubmitBtn').disabled = false;
            document.getElementById('packingCancelBtn').disabled = false;

            if (res.success) {
                document.getElementById('packingModal').classList.add('hidden');
                if (selectEl) {
                    selectEl.setAttribute('data-original', 'packed');
                    selectEl.value = 'packed';
                    updateDropdownStyle(selectEl);
                    updateDropdownOptions(selectEl, 'packed');
                }
                Swal.fire({
                    title: 'Packed & Invoice Dispatched!',
                    text: res.message,
                    icon: 'success',
                    confirmButtonColor: '#7c3aed'
                }).then(() => {
                    location.reload();
                });
            } else {
                alert('Error: ' + res.message);
                closePackingModal();
            }
        })
        .catch(err => {
            console.error(err);
            document.getElementById('packingUploadStatus').classList.add('hidden');
            document.getElementById('packingSubmitBtn').disabled = false;
            document.getElementById('packingCancelBtn').disabled = false;
            alert('Failed to upload packing videos or update status.');
            closePackingModal();
        });
    };

    function updateStatusAjax(orderId, status, selectEl) {
        fetch('<?= BASE_URL ?>/admin/orders/update-status', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ order_id: orderId, status: status })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                selectEl.setAttribute('data-original', status);
                updateDropdownStyle(selectEl);
                updateDropdownOptions(selectEl, status);
                if (status === 'shipped' || status === 'delivered' || status === 'cancelled') {
                    location.reload(); // Reload to show tracking details / update cards
                }
            } else {
                alert('Error: ' + data.message);
                selectEl.value = selectEl.getAttribute('data-original');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Failed to update status.');
            selectEl.value = selectEl.getAttribute('data-original');
        });
    }
});
</script>
