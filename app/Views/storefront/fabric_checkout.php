<div class="bg-gray-50/70 min-h-screen pb-16">

    <!-- ── Breadcrumbs Bar ── -->
    <div class="bg-white border-b border-gray-200/80 mb-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
            <nav class="flex flex-wrap items-center gap-1.5 text-xs sm:text-sm font-medium" aria-label="Breadcrumb">
                <a href="<?= BASE_URL ?>/" class="inline-flex items-center gap-1 text-[#F25996] hover:text-[#d8407d] font-semibold transition-colors">
                    <svg class="w-4 h-4 text-[#F25996]" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                    <span>Home</span>
                </a>
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                <a href="<?= BASE_URL ?>/customize" class="text-gray-600 hover:text-[#F25996] font-medium transition-colors">Customize</a>
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                <a href="<?= BASE_URL ?>/fabrics/customize?fabric_id=<?= (int)$customization['fabric_id'] ?>" class="text-gray-600 hover:text-[#F25996] font-medium transition-colors truncate max-w-[200px]"><?= htmlspecialchars($customization['fabric_name']) ?></a>
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                <span class="text-[#F25996] font-bold" aria-current="page">Checkout &amp; Payment</span>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- ── Page Stepper Header ── -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 border-b border-gray-200">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 font-display tracking-tight flex items-center gap-3">
                    <span>Order Review &amp; Secure Payment</span>
                    <span class="text-xs font-bold px-3 py-1 rounded-full bg-pink-50 text-[#F25996] border border-pink-200 uppercase tracking-wider">
                        Step 2 of 2
                    </span>
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Review your custom garment sizing breakdown, specify delivery details, and pay securely via Razorpay.</p>
            </div>

            <!-- Stepper Indicators -->
            <div class="flex items-center gap-2 self-start md:self-auto">
                <a href="<?= BASE_URL ?>/fabrics/customize?fabric_id=<?= (int)$customization['fabric_id'] ?>" class="flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold hover:bg-emerald-100 transition-colors">
                    <span class="w-5 h-5 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px]">✓</span>
                    <span>1. Sizes Configured</span>
                </a>
                <span class="text-gray-300">→</span>
                <div class="flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-pink-50 text-[#F25996] border border-pink-300 text-xs font-bold shadow-2xs">
                    <span class="w-5 h-5 rounded-full bg-[#F25996] text-white flex items-center justify-center text-[10px]">2</span>
                    <span>Review &amp; Pay</span>
                </div>
            </div>
        </div>

        <?php
            $rawImages = !empty($customization['images']) && is_array($customization['images']) ? array_values(array_filter($customization['images'])) : [];
            $fallbackImg = 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=800&auto=format&fit=crop&q=80';
            $mainImg = !empty($rawImages[0]) ? get_image_url($rawImages[0]) : $fallbackImg;
            
            $withoutCover = ($packagingOption === 'without_cover');
            $discountPct = (float)($customization['without_cover_discount_percentage'] ?? 0);
        ?>

        <!-- ── Main Checkout Grid ── -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- ── Left 7 Cols: Order Breakdown & Delivery ── -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- 1. Customized Garment Details Card -->
                <div class="bg-white rounded-3xl border border-gray-200/90 p-5 sm:p-7 shadow-xs">
                    <div class="flex items-start justify-between gap-4 border-b border-gray-100 pb-5 mb-5">
                        <div class="flex items-start gap-4">
                            <div class="w-20 h-24 sm:w-24 sm:h-28 rounded-2xl overflow-hidden bg-gray-50 border border-gray-200 shrink-0 shadow-2xs">
                                <img src="<?= htmlspecialchars($mainImg) ?>" alt="<?= htmlspecialchars($customization['fabric_name']) ?>" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md bg-pink-50 text-[#F25996] text-[11px] font-bold border border-pink-100 mb-1">
                                    <span><?= htmlspecialchars($customization['garment_type'] ?? 'Custom Garment') ?></span>
                                    <?php if (!empty($customization['feeding_type']) && $customization['feeding_type'] !== 'Non-Feeding'): ?>
                                    <span>•</span>
                                    <span><?= htmlspecialchars($customization['feeding_type']) ?></span>
                                    <?php endif; ?>
                                </div>
                                <h2 class="text-lg sm:text-xl font-black text-gray-900 leading-snug">
                                    <?= htmlspecialchars($customization['fabric_name']) ?>
                                </h2>
                                <div class="flex items-center gap-3 text-xs text-gray-500 mt-1 flex-wrap">
                                    <span>SKU: <strong class="text-gray-700 font-mono"><?= htmlspecialchars(!empty($customization['fabric_sku']) ? $customization['fabric_sku'] : ($customization['sizes'][0]['variant_sku'] ?? 'N/A')) ?></strong></span>
                                    <span>•</span>
                                    <span>Fabric Sets: <strong class="text-[#F25996]"><?= (int)$fabricQuantity ?> Set(s)</strong></span>
                                </div>
                            </div>
                        </div>

                        <a href="<?= BASE_URL ?>/fabrics/customize?fabric_id=<?= (int)$customization['fabric_id'] ?>" class="text-xs font-bold text-[#F25996] hover:text-[#d8407d] hover:underline shrink-0 flex items-center gap-1 pt-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            <span>Edit Sizes</span>
                        </a>
                    </div>

                    <!-- Packaging Selection Pill -->
                    <div class="mb-5 bg-gradient-to-r from-pink-50/70 via-white to-pink-50/70 p-4 rounded-2xl border border-pink-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl <?= $withoutCover ? 'bg-emerald-100 text-emerald-700' : 'bg-pink-100 text-[#F25996]' ?> flex items-center justify-center text-lg shrink-0">
                                <?= $withoutCover ? '📦' : '🛍️' ?>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-gray-900 block">
                                    Packaging Type: <?= $withoutCover ? 'Without Cover (Bulk Packing)' : 'With Cover (Individual Garment Poly Cover)' ?>
                                </span>
                                <span class="text-[11px] text-gray-500">
                                    <?= $withoutCover ? 'Garments packed bulk loose directly in carton.' : 'Every garment individually poly-wrapped with protection.' ?>
                                </span>
                            </div>
                        </div>
                        <?php if ($withoutCover && $discountPct > 0): ?>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-800 text-xs font-black border border-emerald-300 shrink-0">
                            -<?= number_format($discountPct, 1) ?>% Off Applied
                        </span>
                        <?php else: ?>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 text-xs font-bold border border-gray-200 shrink-0">
                            Standard Packing
                        </span>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($sets)): ?>
                    <!-- Fabric Sets & Style Allocation Breakdown -->
                    <div class="mb-5 space-y-2.5">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-700 flex items-center gap-1.5">
                                <span>Fabric Sets &amp; Style Allocation</span>
                                <span class="text-[10px] px-2 py-0.5 rounded-md bg-pink-100 text-[#F25996] font-bold"><?= count($sets) ?> Set(s)</span>
                            </h3>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <?php foreach ($sets as $sIdx => $sData): 
                                $sNum = $sData['set_id'] ?? ($sIdx + 1);
                                $lblTxt = $sData['label_name'] ?? 'Custom Style';
                                $sPcs = isset($sData['total_pieces']) ? (int)$sData['total_pieces'] : array_sum($sData['sizes'] ?? []);
                            ?>
                            <div class="p-3.5 rounded-2xl bg-white border border-pink-100/90 shadow-xs space-y-2">
                                <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-md bg-[#F25996] text-white font-black text-xs flex items-center justify-center">#<?= $sNum ?></span>
                                        <strong class="text-xs font-bold text-gray-900">Set #<?= $sNum ?></strong>
                                    </div>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-pink-50 text-[#F25996] border border-pink-200">
                                        <?= $sPcs ?> Pcs
                                    </span>
                                </div>
                                <div class="flex items-center gap-1.5 text-xs text-gray-700">
                                    <span class="text-gray-400 font-medium">🏷️ Label / Style:</span>
                                    <span class="font-bold text-gray-900"><?= htmlspecialchars($lblTxt) ?></span>
                                </div>
                                <div class="flex items-center gap-1.5 flex-wrap pt-0.5">
                                    <?php if (!empty($sData['sizes'])): ?>
                                        <?php foreach ($sData['sizes'] as $szKey => $szCnt): if ($szCnt <= 0) continue; ?>
                                            <span class="text-[11px] px-2 py-0.5 bg-gray-50 border border-gray-200 rounded-md font-semibold text-gray-700">
                                                <?= htmlspecialchars($szKey) ?>: <strong class="text-gray-900"><?= $szCnt ?></strong>
                                            </span>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Sizing Breakdown Table -->
                    <div>
                        <div class="flex items-center justify-between mb-2.5">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-700 flex items-center gap-1.5">
                                <span>Selected Size Quantities &amp; Rates</span>
                                <span class="text-[10px] px-2 py-0.5 rounded-md bg-pink-100 text-[#F25996] font-bold"><?= count($sizes) ?> Size(s)</span>
                            </h3>
                            <span class="text-xs font-black text-gray-900">
                                Total: <strong class="text-[#F25996]"><?= (int)($consumption['total_pieces'] ?? 0) ?> Pcs</strong>
                            </span>
                        </div>

                        <div class="overflow-x-auto rounded-2xl border border-gray-200 bg-white">
                            <table class="w-full text-left text-xs">
                                <thead>
                                    <tr class="bg-gray-50/80 text-gray-600 font-bold border-b border-gray-200 uppercase tracking-wider text-[10px]">
                                        <th class="py-3 px-4">Size</th>
                                        <th class="py-3 px-4 text-center">Pieces</th>
                                        <th class="py-3 px-4 text-center">Fabric / Pc</th>
                                        <th class="py-3 px-4 text-right">Rate / Pc</th>
                                        <th class="py-3 px-4 text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <?php 
                                        $fabricRate = (float)($customization['fabric_price'] ?? 0);
                                        foreach ($sizes as $szName => $pcs): 
                                            $pcs = (int)$pcs;
                                            if ($pcs <= 0) continue;
                                            
                                            // Find size config
                                            $szObj = null;
                                            if (!empty($customization['sizes'])) {
                                                foreach ($customization['sizes'] as $s) {
                                                    if ($s['size_name'] === $szName) {
                                                        $szObj = $s;
                                                        break;
                                                    }
                                                }
                                            }
                                            $rateMtr = (float)($szObj['fabric_consumption'] ?? 1.0);
                                            $ratePc = round($fabricRate * $rateMtr, 2);
                                            $sizeSubtotal = round($ratePc * $pcs, 2);
                                    ?>
                                    <tr class="hover:bg-pink-50/30 transition-colors">
                                        <td class="py-3.5 px-4 font-bold text-gray-900 flex items-center gap-2">
                                            <span class="w-7 h-7 rounded-lg bg-pink-100 text-[#F25996] font-black text-xs flex items-center justify-center border border-pink-200">
                                                <?= htmlspecialchars($szName) ?>
                                            </span>
                                            <span>Size <?= htmlspecialchars($szName) ?></span>
                                        </td>
                                        <td class="py-3.5 px-4 text-center font-bold text-gray-900">
                                            <span class="px-2.5 py-1 bg-gray-100 text-gray-800 rounded-lg font-mono">
                                                <?= $pcs ?> pcs
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 text-center text-gray-600 font-medium">
                                            <?= number_format($rateMtr, 2) ?> <?= htmlspecialchars($customization['unit'] ?? 'Mtr') ?>
                                        </td>
                                        <td class="py-3.5 px-4 text-right text-gray-700 font-semibold">
                                            ₹<?= number_format($ratePc, 2) ?>
                                        </td>
                                        <td class="py-3.5 px-4 text-right font-black text-gray-900">
                                            ₹<?= number_format($sizeSubtotal, 2) ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-pink-50/50 font-bold border-t border-gray-200 text-gray-900">
                                        <td class="py-3 px-4 font-black">Total Pieces &amp; Consumption</td>
                                        <td class="py-3 px-4 text-center font-black text-[#F25996] text-sm">
                                            <?= (int)($consumption['total_pieces'] ?? 0) ?> pcs
                                        </td>
                                        <td class="py-3 px-4 text-center font-bold text-gray-700">
                                            <?= number_format((float)($consumption['required_fabric'] ?? 0), 2) ?> <?= htmlspecialchars($customization['unit'] ?? 'Mtr') ?>
                                        </td>
                                        <td class="py-3 px-4 text-right text-gray-500 font-semibold">Base Cost:</td>
                                        <td class="py-3 px-4 text-right font-black text-[#F25996] text-sm">
                                            ₹<?= number_format((float)($pricing['fabric_cost'] ?? 0), 2) ?>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Hidden fields for API payload (Address handled on wholesale account) -->
                <input type="hidden" id="checkoutShippingAddress" value="Wholesale Delivery on File">
                <input type="hidden" id="checkoutOrderNotes" value="">

            </div>

            <!-- ── Right 5 Cols: Subtotals Summary & Razorpay Payment ── -->
            <div class="lg:col-span-5 lg:sticky lg:top-24 space-y-5">
                
                <div class="bg-white rounded-3xl border border-gray-200/90 p-6 sm:p-7 shadow-lg relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-36 h-36 bg-[#F25996]/10 rounded-full blur-2xl pointer-events-none"></div>

                    <!-- Header -->
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-5">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 flex items-center gap-2">
                            <span>🧾 Subtotals &amp; Bill Breakdown</span>
                        </h3>
                        <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd"/></svg>
                            <span>Verified Pricing</span>
                        </span>
                    </div>

                    <!-- Metric Breakdown -->
                    <div class="space-y-3 text-sm pb-5 border-b border-gray-100">
                        <div class="flex items-center justify-between text-gray-600">
                            <span>Total Stitch Pieces:</span>
                            <span class="font-black text-gray-900 text-sm"><?= (int)($consumption['total_pieces'] ?? 0) ?> Pcs</span>
                        </div>
                        <div class="flex items-center justify-between text-gray-600">
                            <span>Base Fabric Needed:</span>
                            <span class="font-bold text-gray-900"><?= number_format((float)($consumption['base_consumption'] ?? 0), 2) ?> <?= htmlspecialchars($customization['unit'] ?? 'Mtr') ?></span>
                        </div>
                        <div class="flex items-center justify-between text-gray-600">
                            <span>Wastage Buffer (<?= number_format((float)$customization['wastage_percentage'], 1) ?>%):</span>
                            <span class="font-bold text-[#F25996]">+<?= number_format((float)($consumption['wastage_amount'] ?? 0), 2) ?> <?= htmlspecialchars($customization['unit'] ?? 'Mtr') ?></span>
                        </div>
                        <div class="flex items-center justify-between font-bold bg-pink-50/70 p-2.5 rounded-xl border border-pink-100 text-[#db2777]">
                            <span>Total Required Fabric:</span>
                            <span class="font-black text-base"><?= number_format((float)($consumption['required_fabric'] ?? 0), 2) ?> <?= htmlspecialchars($customization['unit'] ?? 'Mtr') ?></span>
                        </div>
                    </div>

                    <!-- Financial Breakdown -->
                    <div class="space-y-3 pt-5 pb-5 border-b border-gray-100 text-sm">
                        <div class="flex items-center justify-between text-gray-600">
                            <span>Fabric Cost:</span>
                            <span class="font-bold text-gray-900">₹<?= number_format((float)($pricing['fabric_cost'] ?? 0), 2) ?></span>
                        </div>

                        <?php if ($withoutCover && !empty($pricing['without_cover_discount']) && (float)$pricing['without_cover_discount'] > 0): ?>
                        <div class="flex items-center justify-between text-emerald-700 font-bold bg-emerald-50/70 p-2 rounded-xl border border-emerald-200 text-xs">
                            <span>📦 Without Cover Discount (-<?= number_format($discountPct, 1) ?>%):</span>
                            <span class="font-black text-sm text-emerald-700">-₹<?= number_format((float)$pricing['without_cover_discount'], 2) ?></span>
                        </div>
                        <?php endif; ?>

                        <div class="flex items-center justify-between text-gray-600">
                            <span>Included GST (<?= (float)($pricing['gst_rate'] ?? 5) ?>%):</span>
                            <span class="font-bold text-gray-900">₹<?= number_format((float)($pricing['gst_amount'] ?? 0), 2) ?></span>
                        </div>

                        <div class="flex items-center justify-between text-base font-black text-gray-900 pt-2 bg-gradient-to-r from-pink-50/60 to-white p-3 rounded-2xl border border-pink-100">
                            <span>Grand Total Payable:</span>
                            <span class="text-2xl font-black text-[#F25996]">₹<?= number_format((float)($pricing['total'] ?? 0), 2) ?></span>
                        </div>
                    </div>

                    <!-- Dynamic Delivery Card -->
                    <div class="p-3.5 bg-gradient-to-br from-indigo-50/80 via-blue-50/50 to-pink-50/40 rounded-2xl border border-indigo-100 my-4 space-y-1.5 shadow-2xs">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-indigo-950 flex items-center gap-1.5">
                                <span>🚚 Estimated Delivery Schedule</span>
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black bg-indigo-100 text-indigo-800 border border-indigo-200">
                                <?= (int)($delivery['total_days'] ?? 7) ?> Days
                            </span>
                        </div>
                        <div class="text-sm font-black text-gray-900">
                            <?= htmlspecialchars($delivery['estimated_date_formatted'] ?? date('M d, Y', strtotime('+7 days'))) ?>
                        </div>
                        <p class="text-[10px] text-gray-500">
                            Dynamically calculated for <?= (int)($consumption['total_pieces'] ?? 0) ?> pcs production run.
                        </p>
                    </div>

                    <!-- Action: Pay with Razorpay -->
                    <div class="space-y-3 pt-2">
                        <button type="button" 
                                id="btnRazorpayCheckout" 
                                onclick="executeRazorpayPayment()" 
                                class="w-full py-4 px-6 bg-gradient-to-r from-[#F25996] via-[#ea3c85] to-[#db2777] hover:opacity-95 text-white font-black text-base rounded-2xl shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2 cursor-pointer group">
                            <svg class="w-5 h-5 text-white shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            <span>Pay ₹<?= number_format((float)($pricing['total'] ?? 0), 2) ?> via Razorpay</span>
                        </button>

                        <div class="flex items-center justify-center gap-1.5 text-[11px] text-gray-400 font-medium">
                            <svg class="w-3.5 h-3.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd"/></svg>
                            <span>Secured 256-Bit Payment Gateway (UPI, Cards, NetBanking)</span>
                        </div>

                        <!-- B2B Alternate Option: Submit Quote Request -->
                        <div class="pt-2 text-center">
                            <button type="button" onclick="submitCustomOrderQuote()" class="text-xs font-bold text-gray-500 hover:text-gray-900 hover:underline cursor-pointer">
                                Or submit as wholesale order request (Pay via Invoice later)
                            </button>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- ── Razorpay Integration Scripts ── -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const CHECKOUT_FABRIC_ID = <?= (int)$customization['fabric_id'] ?>;
const CHECKOUT_CUSTOMIZATION_ID = <?= (int)$customization['id'] ?>;
const CHECKOUT_SIZES = <?= json_encode($sizes) ?>;
const CHECKOUT_SETS = <?= json_encode($sets ?? []) ?>;
const CHECKOUT_FABRIC_QTY = <?= (int)$fabricQuantity ?>;
const CHECKOUT_PACKAGING_OPT = <?= json_encode($packagingOption) ?>;
const BASE_URL = <?= json_encode(BASE_URL) ?>;

function getCheckoutPayload() {
    const addrElem = document.getElementById('checkoutShippingAddress');
    const notesElem = document.getElementById('checkoutOrderNotes');
    const address = (addrElem && addrElem.value && addrElem.value.trim()) ? addrElem.value.trim() : 'Wholesale Delivery on File';
    const notes = (notesElem && notesElem.value && notesElem.value.trim()) ? notesElem.value.trim() : '';

    return {
        fabric_id: CHECKOUT_FABRIC_ID,
        customization_id: CHECKOUT_CUSTOMIZATION_ID,
        fabric_quantity: CHECKOUT_FABRIC_QTY,
        sizes: CHECKOUT_SIZES,
        sets: CHECKOUT_SETS,
        shipping_address: address,
        notes: notes,
        packaging_option: CHECKOUT_PACKAGING_OPT,
        without_cover: (CHECKOUT_PACKAGING_OPT === 'without_cover')
    };
}

function executeRazorpayPayment() {
    const payload = getCheckoutPayload();

    const btn = document.getElementById('btnRazorpayCheckout');
    btn.disabled = true;
    btn.innerHTML = '<span class="inline-block animate-spin mr-2">🔄</span> Connecting to Razorpay...';

    Swal.fire({
        title: 'Opening Razorpay Gateway...',
        text: 'Preparing secure checkout session',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    fetch(`${BASE_URL}/fabrics/customization/razorpay-init`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(data => {
        Swal.close();
        btn.disabled = false;
        btn.innerHTML = `<span>Pay ₹${data.amount || ''} via Razorpay</span>`;

        if (!data.success) {
            Swal.fire({
                title: 'Payment Init Failed',
                text: data.message || 'Unable to connect to Razorpay. Please try again.',
                icon: 'error',
                confirmButtonColor: '#F25996'
            });
            return;
        }

        const options = {
            "key": data.razorpay_key,
            "amount": data.amount_in_paise,
            "currency": data.currency || "INR",
            "name": "GRM B2B Garment Studio",
            "description": `Custom Order: ${data.program_name}`,
            "order_id": data.razorpay_order_id,
            "prefill": {
                "name": data.user.name || '',
                "email": data.user.email || '',
                "contact": (data.user.phone && data.user.phone.length >= 10) ? data.user.phone : ''
            },
            "theme": {
                "color": "#F25996"
            },
            "handler": function (response) {
                Swal.fire({
                    title: 'Verifying Payment...',
                    text: 'Confirming your custom order with our tailoring team',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                const verifyPayload = Object.assign({}, payload, {
                    razorpay_payment_id: response.razorpay_payment_id,
                    razorpay_order_id: response.razorpay_order_id,
                    razorpay_signature: response.razorpay_signature
                });

                fetch(`${BASE_URL}/fabrics/customization/razorpay-verify`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(verifyPayload)
                })
                .then(vr => vr.json())
                .then(res => {
                    if (res.success) {
                        Swal.fire({
                            title: 'Order Confirmed! 🎉',
                            html: `<p class="text-sm text-gray-700">${res.message}</p><p class="text-xs text-gray-500 mt-2 font-mono">Order: ${res.order_number}</p>`,
                            icon: 'success',
                            confirmButtonColor: '#F25996',
                            confirmButtonText: 'View My Orders'
                        }).then(() => {
                            window.location.href = res.redirect_url || `${BASE_URL}/dashboard/orders`;
                        });
                    } else {
                        Swal.fire({
                            title: 'Payment Recorded with Issue',
                            text: res.message || 'Payment received but order sync error occurred. Please contact customer support.',
                            icon: 'warning',
                            confirmButtonColor: '#F25996'
                        });
                    }
                })
                .catch(err => {
                    Swal.fire({
                        title: 'Verification Error',
                        text: 'An error occurred during payment verification. If amount was debited, your order will be created shortly.',
                        icon: 'error',
                        confirmButtonColor: '#F25996'
                    });
                });
            },
            "modal": {
                "ondismiss": function() {
                    btn.disabled = false;
                }
            }
        };

        const rzp = new Razorpay(options);
        rzp.on('payment.failed', function (resp) {
            Swal.fire({
                title: 'Payment Incomplete',
                text: resp.error.description || resp.error.reason || 'Payment was declined or cancelled.',
                icon: 'warning',
                confirmButtonColor: '#F25996'
            });
            btn.disabled = false;
        });
        rzp.open();
    })
    .catch(err => {
        Swal.close();
        btn.disabled = false;
        btn.innerHTML = '<span>Pay via Razorpay</span>';
        Swal.fire({
            title: 'Connection Error',
            text: 'Unable to connect to payment server. Please check your network.',
            icon: 'error',
            confirmButtonColor: '#F25996'
        });
    });
}

function submitCustomOrderQuote() {
    const payload = getCheckoutPayload();

    Swal.fire({
        title: 'Submit Order Request?',
        text: 'Your order will be queued for team review. Payment can be made later upon approval.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#F25996',
        cancelButtonColor: '#9ca3af',
        confirmButtonText: 'Yes, Submit Request'
    }).then(res => {
        if (!res.isConfirmed) return;

        Swal.fire({
            title: 'Submitting Order Request...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch(`${BASE_URL}/fabrics/customization/request-order`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Request Submitted! 📋',
                    text: data.message,
                    icon: 'success',
                    confirmButtonColor: '#F25996'
                }).then(() => {
                    window.location.href = data.redirect_url || `${BASE_URL}/dashboard/orders`;
                });
            } else {
                Swal.fire({
                    title: 'Submission Notice',
                    text: data.message || 'Unable to submit request.',
                    icon: 'error',
                    confirmButtonColor: '#F25996'
                });
            }
        })
        .catch(err => {
            Swal.fire({
                title: 'Error',
                text: 'Network error submitting request.',
                icon: 'error',
                confirmButtonColor: '#F25996'
            });
        });
    });
}
</script>
