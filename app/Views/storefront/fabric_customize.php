<div class="bg-gray-50 min-h-screen pb-12 md:pb-16">
<?php
    $totalCustomRevs = !empty($customizationStats['total_reviews']) ? (int)$customizationStats['total_reviews'] : 0;
    $avgCustomRating = !empty($customizationStats['avg_rating']) ? (float)$customizationStats['avg_rating'] : 0.0;
    $withoutCoverDiscountPct = (float)($customization['without_cover_discount_percentage'] ?? 0.0);
    $deliveryDaysBase = (int)($customization['delivery_days'] ?? 7);
    $deliveryPiecesBase = (int)($customization['delivery_base_pieces'] ?? 100);
?>


    <!-- ── Breadcrumb Section (Matches Product Details & Catalog Bar Style) ── -->
    <div class="bg-white border-b border-gray-200/80 mb-5 md:mb-6">
        <div class="w-full max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
            <nav class="grm-breadcrumb-nav hide-scrollbar no-scrollbar scrollbar-none flex flex-wrap items-center gap-1 sm:gap-1.5 text-xs sm:text-sm font-medium" aria-label="Breadcrumb">
                <a href="<?= BASE_URL ?>/" class="inline-flex items-center gap-1 text-[#F25996] hover:text-[#d8407d] font-semibold transition-colors shrink-0">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 shrink-0 text-[#F25996]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                    </svg>
                    <span>Home</span>
                </a>
                <svg class="w-3.5 h-3.5 text-[#F25996] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
                <a href="<?= BASE_URL ?>/customize" class="text-[#F25996] hover:text-[#d8407d] font-semibold transition-colors shrink-0">Customize</a>
                <svg class="w-3.5 h-3.5 text-[#F25996] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-[#F25996] font-bold shrink-0 truncate" aria-current="page"><?= htmlspecialchars($customization['fabric_name']) ?></span>
            </nav>
        </div>
    </div>

    <div class="w-full max-w-full mx-auto px-4 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left 7 Cols: Fabric Overview & Size Matrix -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- Fabric Info Card -->
                <div class="bg-white rounded-3xl border border-gray-100 p-6 md:p-8 shadow-xs">
                    <div class="flex flex-col sm:flex-row gap-6 items-start">
                        <!-- Product Image -->
                        <div class="w-full sm:w-44 h-44 rounded-2xl bg-gradient-to-br from-pink-50 to-rose-50 overflow-hidden flex-shrink-0 border border-gray-100 relative group shadow-sm">
                            <?php 
                                $rawImg = !empty($customization['primary_image']) ? get_image_url($customization['primary_image']) : (!empty($customization['images'][0]) ? get_image_url($customization['images'][0]) : '');
                            ?>
                            <?php if (!empty($rawImg)): ?>
                            <img src="<?= htmlspecialchars($rawImg) ?>" 
                                 alt="<?= htmlspecialchars($customization['fabric_name']) ?>" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <?php else: ?>
                            <div class="w-full h-full flex flex-col items-center justify-center p-3 text-center">
                                <span class="text-3xl mb-1">🧵</span>
                                <span class="text-xs font-bold text-gray-700 line-clamp-2"><?= htmlspecialchars($customization['fabric_name']) ?></span>
                            </div>
                            <?php endif; ?>
                            <span class="absolute top-2 left-2 px-2.5 py-1 bg-[#F25996] text-white font-black text-[10px] uppercase tracking-wider rounded-md shadow-sm">
                                ✂️ Custom Ready
                            </span>
                        </div>

                        <!-- Product Meta -->
                        <div class="flex-1 min-w-0">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-pink-50 text-[#F25996] text-xs font-bold border border-pink-200 mb-2">
                                <span><?= htmlspecialchars($customization['program_name']) ?></span>
                                <span>•</span>
                                <span><?= htmlspecialchars($customization['garment_type']) ?></span>
                            </div>

                            <h1 class="text-2xl font-black text-gray-900 font-display leading-tight mb-2">
                                <?= htmlspecialchars($customization['fabric_name']) ?>
                            </h1>

                            <div class="flex items-center gap-4 text-xs text-gray-500 mb-4 flex-wrap">
                                <span>SKU: <strong class="text-gray-800"><?= htmlspecialchars($customization['fabric_sku'] ?? 'N/A') ?></strong></span>
                                <span>•</span>
                                <span>Feature Types: <strong class="text-gray-800"><?= htmlspecialchars($customization['feeding_type']) ?></strong></span>
                                <span>•</span>
                                <span>Available Stock: <strong class="text-emerald-700 font-bold"><?= (int)$customization['fabric_stock'] ?> <?= htmlspecialchars($customization['unit']) ?></strong></span>
                            </div>

                            <div class="flex items-baseline gap-2 flex-wrap">
                                <?php if (!empty($customization['fabric_discount_price']) && (float)$customization['fabric_discount_price'] > 0 && (float)$customization['fabric_discount_price'] < (float)$customization['fabric_base_price']): ?>
                                    <span class="text-2xl font-black text-gray-900">
                                        ₹<?= number_format($customization['fabric_discount_price'], 2) ?>
                                    </span>
                                    <span class="text-sm font-semibold text-gray-400 line-through">
                                        ₹<?= number_format($customization['fabric_base_price'], 2) ?>
                                    </span>
                                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-md bg-rose-50 text-rose-600 border border-rose-200">
                                        <?= round((((float)$customization['fabric_base_price'] - (float)$customization['fabric_discount_price']) / (float)$customization['fabric_base_price']) * 100) ?>% OFF
                                    </span>
                                <?php else: ?>
                                    <span class="text-2xl font-black text-gray-900">
                                        ₹<?= number_format($customization['fabric_price'], 2) ?>
                                    </span>
                                <?php endif; ?>
                                <span class="text-xs font-semibold text-gray-500">per <?= htmlspecialchars($customization['unit'] ?? 'Qty') ?> (Wholesale Rate)</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Size & Piece Distribution Matrix (Fabric Rules Matrix) -->
                <div class="bg-white rounded-3xl border border-gray-100 p-6 md:p-8 shadow-xs space-y-6">
                    <div>
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-bold text-gray-900 font-display flex items-center gap-2">
                                <span>📏 Select Sizes & Stitch Pieces</span>
                            </h2>
                            <span class="text-xs font-semibold px-2.5 py-1 bg-pink-50 text-[#F25996] rounded-full border border-pink-200">
                                Step 1 of 2
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            Choose how many pieces you would like to stitch for each available size. Fabric consumption is automatically computed per piece based on admin engineering rules.
                        </p>
                    </div>

                    <!-- Fabric Quantity (Multiplier) Section -->
                    <div class="bg-gradient-to-r from-pink-50/80 via-rose-50/50 to-pink-50/80 p-4 rounded-2xl border border-pink-200 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-2xs">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-[#F25996] text-white font-black text-lg flex items-center justify-center shadow-xs flex-shrink-0">
                                📦
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                                    <span>Fabric Quantity / Sets</span>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-pink-100 text-[#db2777] border border-pink-300/70" id="fabricQtyBadge">
                                        1 Set (1x)
                                    </span>
                                </h4>
                                <p class="text-xs text-gray-500">Default quantities, min requirements, and maximum limits scale automatically for the chosen fabric quantity.</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 flex-shrink-0">
                            <div class="inline-flex items-center border-2 border-[#F25996] rounded-full bg-white px-2 py-0.5 shadow-xs">
                                <button type="button" onclick="adjustFabricMultiplier(-1)" class="w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center font-black text-lg text-[#F25996] hover:bg-pink-50 transition-colors select-none cursor-pointer">
                                    -
                                </button>
                                <div class="h-4 w-px bg-pink-200 mx-1"></div>
                                <input type="number" id="fabric_multiplier" value="1" min="1" max="999" oninput="onFabricMultiplierChange(this)" class="w-12 h-7 sm:h-8 text-center font-black text-sm sm:text-base text-[#F25996] bg-transparent focus:outline-none">
                                <div class="h-4 w-px bg-pink-200 mx-1"></div>
                                <button type="button" onclick="adjustFabricMultiplier(1)" class="w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center font-black text-lg text-[#F25996] hover:bg-pink-50 transition-colors select-none cursor-pointer">
                                    +
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Fabric Set Containers List -->
                    <div id="fabricSetsContainer" class="space-y-6">
                        <!-- Containers are dynamically rendered by JavaScript based on Fabric Quantity / Sets -->
                    </div>

                </div>

                <!-- Customization Style Labels Quantity Allocation (Step 2) -->
                <?php if (!empty($customization['labels'])): ?>
                    <div class="bg-white rounded-3xl border border-gray-100 p-6 md:p-8 shadow-xs space-y-5">
                        <div class="border-b border-gray-100 pb-3">
                            <div>
                                <h2 class="text-base font-bold text-gray-900 font-display flex items-center gap-2">
                                    <span>✂️ Customization Style & Label Allocation</span>
                                </h2>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    Select the style label directly in each Fabric Set container above. Total pieces from each set are allocated to its chosen style.
                                </p>
                            </div>
                        </div>

                        <!-- Pieces Allocation Tracker Banner -->
                        <div class="bg-gradient-to-r from-pink-50/70 via-rose-50/40 to-pink-50/70 p-3.5 rounded-2xl border border-pink-200/80 flex flex-wrap items-center justify-between gap-3 text-xs">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-gray-700">Total Stitched Pieces:</span>
                                <span class="font-black text-sm text-gray-900" id="labelTrackerTotalRequired">0 Pcs</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-gray-700">Allocated to Styles:</span>
                                <span class="font-black text-sm text-[#F25996]" id="labelTrackerTotalAllocated">0 Pcs</span>
                            </div>
                            <div id="labelTrackerUnassignedContainer" class="hidden items-center gap-2">
                                <span class="font-bold text-amber-800">Unassigned:</span>
                                <span class="font-black text-sm text-amber-600" id="labelTrackerTotalUnassigned">0 Pcs</span>
                            </div>
                            <div id="labelTrackerBadge" class="px-2.5 py-1 rounded-lg font-bold text-[11px] bg-gray-100 text-gray-600 border border-gray-200">
                                Select sizes above
                            </div>
                        </div>

                        <!-- Live Label Allocation Cards List -->
                        <div class="space-y-3" id="labelsAllocationOverviewList">
                            <!-- Populated dynamically by updateLabelAllocationOverview() -->
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Delivery Address & Special Instructions removed as requested -->

            </div>

            <!-- Right 5 Cols: Live Summary & 2 Action Paths -->
            <div class="lg:col-span-5 lg:sticky lg:top-24 space-y-6">
                
                <div class="bg-white rounded-3xl border border-gray-100 p-6 md:p-8 shadow-lg relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-[#F25996]/10 rounded-full blur-2xl pointer-events-none"></div>

                    <h3 class="text-xs sm:text-sm font-bold text-gray-900 font-display uppercase tracking-wider border-b border-gray-100 pb-3 mb-4 flex items-center justify-between gap-2">
                        <span class="truncate">Fabric Consumption & Bill</span>
                        <span class="text-[10px] sm:text-[11px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 shrink-0 whitespace-nowrap" id="liveStatusBadge">
                            Live Calc
                        </span>
                    </h3>

                    <!-- Piece Count & Constraint Tracker -->
                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 mb-5 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-gray-600">Total Stitch Pieces:</span>
                            <span class="font-black text-sm text-gray-900" id="summaryTotalPieces">0 Pcs</span>
                        </div>
                        
                        <!-- Visual Progress Meter -->
                        <div class="w-full bg-gray-200 h-2.5 rounded-full overflow-hidden">
                            <div id="pieceProgressBar" class="h-full bg-[#F25996] transition-all duration-300" style="width: 0%;"></div>
                        </div>

                        <div class="flex items-center justify-between text-[11px] text-gray-500" id="pieceConstraintText">
                            <span>Required to Enable Payment: <strong class="text-gray-900"><?= (int)$customization['minimum_pieces'] ?> Pcs</strong> <span class="text-gray-400 font-normal">(<?= (int)$customization['minimum_pieces'] ?>/set)</span></span>
                            <span>Max Allowed: <strong class="text-gray-900"><?= (int)$customization['minimum_pieces'] ?> Pcs</strong></span>
                        </div>
                    </div>

                    <!-- Fabric Calculations Metric breakdown -->
                    <div class="space-y-3 text-sm pb-5 border-b border-gray-100">
                        <div class="flex items-center justify-between text-gray-600">
                            <span>Base Fabric Needed:</span>
                            <span class="font-bold text-gray-900" id="summaryBaseConsumption">0.00 <?= htmlspecialchars($customization['unit']) ?></span>
                        </div>
                        <div class="flex items-center justify-between text-gray-600">
                            <span>Wastage Buffer (<?= number_format($customization['wastage_percentage'], 1) ?>%):</span>
                            <span class="font-bold text-[#F25996]" id="summaryWastageAmount">+0.00 <?= htmlspecialchars($customization['unit']) ?></span>
                        </div>
                        <div class="flex items-center justify-between text-gray-800 font-bold bg-pink-50/60 p-2.5 rounded-xl border border-pink-100">
                            <span class="text-[#db2777]">Total Required Fabric:</span>
                            <span class="text-[#db2777] font-black text-base" id="summaryRequiredFabric">0.00 <?= htmlspecialchars($customization['unit']) ?></span>
                        </div>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>Fabric Stock Available:</span>
                            <span class="font-bold text-gray-700" id="summaryStockDisplay"><?= (int)$customization['fabric_stock'] ?> <?= htmlspecialchars($customization['unit']) ?></span>
                        </div>
                    </div>

                    
                    <!-- Packaging Selection Option (With Cover vs Without Cover) -->
                    <div class="space-y-2 mb-4">
                        <label class="text-xs font-bold uppercase tracking-wider text-gray-700 flex items-center justify-between">
                            <span>Packaging Option</span>
                            <?php if ($withoutCoverDiscountPct > 0): ?>
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold border border-emerald-200">
                                Save <?= number_format($withoutCoverDiscountPct, 1) ?>% without cover
                            </span>
                            <?php endif; ?>
                        </label>
                        <div class="grid grid-cols-2 gap-2.5">
                            <button type="button" 
                                    id="optWithCover"
                                    onclick="selectPackagingOption('with_cover')"
                                    class="packaging-opt-btn p-3 rounded-xl border-2 text-left transition-all cursor-pointer border-[#F25996] bg-white ring-2 ring-[#F25996]/20 shadow-xs">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-bold text-gray-900">With Cover</span>
                                    <span class="pkg-check w-4 h-4 rounded-full bg-[#F25996] text-white flex items-center justify-center text-[10px] font-bold">✓</span>
                                </div>
                                <p class="text-[10px] text-gray-500">Standard packaging</p>
                            </button>
                            <button type="button" 
                                    id="optWithoutCover"
                                    onclick="selectPackagingOption('without_cover')"
                                    class="packaging-opt-btn p-3 rounded-xl border-2 text-left transition-all cursor-pointer border-gray-200 bg-white hover:border-[#F25996]/50">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-bold text-gray-900">Without Cover</span>
                                    <span class="pkg-check w-4 h-4 rounded-full border border-gray-300 text-transparent flex items-center justify-center text-[10px] font-bold">✓</span>
                                </div>
                                <?php if ($withoutCoverDiscountPct > 0): ?>
                                    <p class="text-[10px] font-bold text-emerald-700">-<?= number_format($withoutCoverDiscountPct, 1) ?>% Off</p>
                                <?php else: ?>
                                    <p class="text-[10px] text-gray-500">Eco packaging</p>
                                <?php endif; ?>
                            </button>
                        </div>
                    </div>

                    <!-- Dynamic Delivery Schedule Pill -->
                    <div id="deliverySchedulePill" class="p-3 bg-gradient-to-r from-amber-50/60 to-orange-50/40 border border-amber-200/80 rounded-2xl flex items-center justify-between text-xs mb-3 shadow-2xs">
                        <div class="flex items-center gap-2">
                            <span class="text-base">🚚</span>
                            <div>
                                <strong class="font-bold text-gray-800 block text-[11px]">Dynamic Delivery Schedule</strong>
                                <span class="text-[10px] text-gray-500" id="deliveryExpectedDate">Est: <?= $deliveryDaysBase ?> Days for <?= $deliveryPiecesBase ?> pcs</span>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-amber-100 text-amber-900 border border-amber-200" id="deliveryDaysBadge">
                            <?= $deliveryDaysBase ?> Days
                        </span>
                    </div>
<!-- Pricing Breakdown -->
                    <div class="space-y-3 pt-5 pb-6 border-b border-gray-100 text-sm">
                        <div class="flex items-center justify-between text-gray-600">
                            <span>Fabric Cost (@ ₹<?= number_format($customization['fabric_price'], 2) ?>/<?= htmlspecialchars($customization['unit']) ?>):</span>
                            <span class="font-bold text-gray-900" id="summaryFabricCost">₹0.00</span>
                        </div>
                        
                        <!-- Without Cover Discount Row -->
                        <div id="packagingDiscountRow" class="hidden items-center justify-between text-emerald-700 font-semibold text-xs">
                            <span class="flex items-center gap-1">
                                <span>🛍️ Without Cover Discount:</span>
                                <span class="text-[10px] px-1.5 py-0.2 rounded bg-emerald-100 text-emerald-800 font-bold">-<?= number_format($withoutCoverDiscountPct, 1) ?>%</span>
                            </span>
                            <span class="font-bold" id="summaryCoverDiscount">-₹0.00</span>
                        </div>
<div class="flex items-center justify-between text-gray-600">
                            <span class="flex items-center gap-1.5">
                                <span>GST (5% Included):</span>
                                <span class="text-[10px] bg-emerald-50 text-emerald-700 font-bold px-1.5 py-0.5 rounded border border-emerald-200">Included in Price</span>
                            </span>
                            <span class="font-bold text-gray-700" id="summaryGstAmount">₹0.00</span>
                        </div>
                        <div class="flex items-center justify-between text-base font-black text-gray-900 pt-2">
                            <span>Estimated Grand Total:</span>
                            <span class="text-2xl font-black text-[#F25996]" id="summaryGrandTotal">₹0.00</span>
                        </div>
                    </div>

                    <!-- Error Alert Box (if validation fails) -->
                    <div id="errorAlertBox" class="hidden p-3.5 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs font-semibold my-4"></div>

                    <!-- Action Buttons: Proceed to Checkout (Step 2) & Direct Razorpay -->
                    <div class="space-y-2 pt-2">
                        <button type="button" 
                                id="btnProceedCheckout" 
                                onclick="proceedToCheckout()" 
                                class="w-full py-3.5 px-5 bg-gradient-to-r from-[#F25996] via-[#ea3c85] to-[#db2777] hover:opacity-95 text-white font-black text-sm rounded-2xl shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 cursor-pointer group">
                            <span>Proceed to Checkout</span>
                            <svg class="w-4 h-4 text-white transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                        <button type="button" 
                                id="btnPayRazorpay" 
                                onclick="payViaRazorpay()" 
                                class="w-full py-2.5 px-4 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold text-xs rounded-xl transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4 text-[#F25996] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            <span>Quick Pay Online (Razorpay)</span>
                        </button>
                        <div class="flex items-center justify-center gap-1.5 text-[11px] text-gray-400 font-medium">
                            <svg class="w-3.5 h-3.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd"/></svg>
                            <span>Secured by 256-Bit Razorpay Payments</span>
                        </div>
                    </div></div>

                </div>

            </div>

        </div>

    

<!-- ── Customer Reviews & Stitching Feedback Section ── -->
        <div id="customReviewsSection" class="bg-white rounded-3xl border border-gray-100 p-6 sm:p-8 lg:p-10 shadow-xs mb-10">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-6 mb-6">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-pink-50 text-[#F25996] border border-pink-100">Verified Reviews</span>
                        <span class="text-xs text-gray-400">•</span>
                        <span class="text-xs font-medium text-gray-500">Custom Stitching &amp; Fabric Feel</span>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight">Customer Reviews for this Customized Garment</h3>
                </div>

                <div class="flex items-center gap-3 bg-gray-50 px-4 py-2.5 rounded-2xl border border-gray-100 self-start sm:self-auto">
                    <div class="text-2xl font-black text-gray-900"><?= $totalCustomRevs > 0 ? number_format($avgCustomRating, 1) : '—' ?></div>
                    <div>
                        <div class="flex">
                            <?php for($i=1; $i<=5; $i++): ?>
                                <svg class="w-3.5 h-3.5 <?= ($totalCustomRevs > 0 && $i <= round($avgCustomRating)) ? 'text-amber-400 fill-current' : 'text-gray-200 fill-current' ?>" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <?php endfor; ?>
                        </div>
                        <p class="text-[10px] text-gray-500 font-semibold mt-0.5"><?= $totalCustomRevs ?> Customer Rating<?= $totalCustomRevs === 1 ? '' : 's' ?></p>
                    </div>
                </div>
            </div>

            <?php if (empty($customizationReviews)): ?>
                <div class="text-center py-12 px-4 bg-gray-50/60 rounded-2xl border border-dashed border-gray-200">
                    <span class="w-12 h-12 rounded-full bg-pink-50 text-[#F25996] inline-flex items-center justify-center text-xl mb-3 shadow-2xs">✂️</span>
                    <h4 class="text-sm sm:text-base font-bold text-gray-800">No reviews yet for this custom program</h4>
                    <p class="text-xs text-gray-500 max-w-md mx-auto mt-1 leading-relaxed">
                        Be the first to order and customize this fabric! Once your order status is marked as delivered, you can rate and review your finished garments directly from your order details dashboard.
                    </p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <?php foreach ($customizationReviews as $rev): 
                        $revPhotos = !empty($rev['photos']) ? (json_decode($rev['photos'], true) ?: []) : [];
                    ?>
                        <div class="p-5 rounded-2xl border border-gray-100 bg-[#fafafa]/60 hover:bg-white hover:shadow-md transition-all space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-[#F25996]/10 text-[#F25996] flex items-center justify-center font-bold text-xs uppercase">
                                            <?= htmlspecialchars(substr($rev['reviewer_name'] ?? 'U', 0, 1)) ?>
                                        </div>
                                        <div>
                                            <h5 class="text-xs sm:text-sm font-bold text-gray-900"><?= htmlspecialchars($rev['reviewer_name']) ?></h5>
                                            <p class="text-[10px] text-gray-400 font-medium"><?= date('M d, Y', strtotime($rev['created_at'])) ?></p>
                                        </div>
                                    </div>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-50 text-green-700 border border-green-200 flex items-center gap-1">
                                    <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    Verified Buyer
                                </span>
                            </div>

                            <!-- Rating Stars -->
                            <div class="flex text-amber-400">
                                <?php for($i=1; $i<=5; $i++): ?>
                                    <svg class="w-4 h-4 <?= $i <= $rev['rating'] ? 'fill-current' : 'text-gray-200 fill-current' ?>" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <?php endfor; ?>
                            </div>

                            <!-- Comment -->
                            <?php if (!empty($rev['review_text'])): ?>
                                <p class="text-xs sm:text-sm text-gray-700 leading-relaxed italic">
                                    “<?= nl2br(htmlspecialchars($rev['review_text'])) ?>”
                                </p>
                            <?php endif; ?>

                            <!-- Photos -->
                            <?php if (!empty($revPhotos)): ?>
                                <div class="flex flex-wrap gap-2 pt-1">
                                    <?php foreach ($revPhotos as $p): ?>
                                        <a href="<?= get_image_url($p) ?>" target="_blank" class="block w-14 h-14 rounded-xl overflow-hidden border border-gray-200 shadow-2xs hover:scale-105 transition-transform">
                                            <img src="<?= get_image_url($p) ?>" class="w-full h-full object-cover" alt="Buyer custom photo">
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<!-- Fabric Gallery Lightbox Modal -->
<div id="fabricLightboxModal" class="fixed inset-0 z-50 bg-black/85 backdrop-blur-sm hidden flex items-center justify-center p-3 sm:p-6" onclick="if(event.target === this) closeFabricModal()">
    <div class="relative max-w-4xl w-full flex flex-col items-center">
        <!-- Close Button -->
        <button type="button" onclick="closeFabricModal()" class="absolute -top-12 right-0 text-white hover:text-pink-300 p-2 text-sm font-bold flex items-center gap-1.5 transition-colors">
            <span>Close (Esc)</span>
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <!-- Main Lightbox Image Frame -->
        <div class="relative max-h-[75vh] w-auto max-w-full overflow-hidden rounded-2xl bg-white shadow-2xl flex items-center justify-center">
            <img id="fabricModalImg" src="" alt="Fabric Full Preview" class="max-h-[75vh] max-w-full object-contain">
            
            <!-- Prev / Next Controls -->
            <button type="button" onclick="navFabricModal(-1)" class="absolute left-3 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-black/55 hover:bg-black/75 text-white flex items-center justify-center shadow-lg transition-transform hover:scale-110">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button type="button" onclick="navFabricModal(1)" class="absolute right-3 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-black/55 hover:bg-black/75 text-white flex items-center justify-center shadow-lg transition-transform hover:scale-110">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </button>

            <!-- Counter Badge -->
            <div id="fabricModalCounter" class="absolute bottom-3 left-1/2 -translate-x-1/2 bg-black/70 text-white text-xs font-bold px-3 py-1 rounded-full backdrop-blur-xs">
                1 / 1
            </div>
        </div>

        <!-- Lightbox Thumbnails -->
        <div id="fabricModalThumbs" class="flex items-center gap-2 mt-4 overflow-x-auto max-w-full pb-1 scrollbar-none justify-center"></div>
    </div>
</div>

<!-- Razorpay Official Checkout Script -->


</div>
</div>

<!-- Razorpay Official Checkout Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
const FABRIC_ID = <?= (int)$customization['fabric_id'] ?>;
const CUSTOMIZATION_ID = <?= (int)$customization['id'] ?>;
const MIN_PIECES_PER_SET = <?= (int)$customization['minimum_pieces'] ?>;
const MAX_PIECES_PER_SET = <?= (int)$customization['minimum_pieces'] ?>;
const MIN_PIECES = MIN_PIECES_PER_SET;
const MAX_PIECES = MAX_PIECES_PER_SET;
const FABRIC_STOCK = <?= (float)$customization['fabric_stock'] ?>;
const UNIT = <?= json_encode($customization['unit'] ?? 'Qty') ?>;
const BASE_URL = <?= json_encode(BASE_URL) ?>;
const IS_LOGGED_IN = <?= \Core\Session::get('user_id') ? 'true' : 'false' ?>;
const FABRIC_PRICE_PER_UNIT = <?= (float)($customization['fabric_price'] ?? 0) ?>;
const SIZES_CONFIG = <?= json_encode($customization['sizes'] ?? []) ?>;
const LABELS_CONFIG = <?= json_encode($customization['labels'] ?? []) ?>;
const WITHOUT_COVER_DISCOUNT_PCT = <?= (float)($customization['without_cover_discount_percentage'] ?? 0) ?>;
const DELIVERY_DAYS = <?= (int)($customization['delivery_days'] ?? 7) ?>;
const DELIVERY_BASE_PIECES = <?= (int)($customization['delivery_base_pieces'] ?? 100) ?>;
let selectedPackagingOption = 'with_cover';

function selectPackagingOption(option) {
    selectedPackagingOption = option;
    const btnWith = document.getElementById('optWithCover');
    const btnWithout = document.getElementById('optWithoutCover');
    
    if (btnWith && btnWithout) {
        const checkWith = btnWith.querySelector('.pkg-check');
        const checkWithout = btnWithout.querySelector('.pkg-check');
        
        if (option === 'with_cover') {
            btnWith.className = 'packaging-opt-btn p-3 rounded-xl border-2 text-left transition-all cursor-pointer border-[#F25996] bg-white ring-2 ring-[#F25996]/20 shadow-xs';
            btnWithout.className = 'packaging-opt-btn p-3 rounded-xl border-2 text-left transition-all cursor-pointer border-gray-200 bg-white hover:border-[#F25996]/50';
            if (checkWith) checkWith.className = 'pkg-check w-4 h-4 rounded-full bg-[#F25996] text-white flex items-center justify-center text-[10px] font-bold';
            if (checkWithout) checkWithout.className = 'pkg-check w-4 h-4 rounded-full border border-gray-300 text-transparent flex items-center justify-center text-[10px] font-bold';
        } else {
            btnWithout.className = 'packaging-opt-btn p-3 rounded-xl border-2 text-left transition-all cursor-pointer border-emerald-500 bg-emerald-50/20 ring-2 ring-emerald-500/20 shadow-xs';
            btnWith.className = 'packaging-opt-btn p-3 rounded-xl border-2 text-left transition-all cursor-pointer border-gray-200 bg-white hover:border-[#F25996]/50';
            if (checkWithout) checkWithout.className = 'pkg-check w-4 h-4 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-bold';
            if (checkWith) checkWith.className = 'pkg-check w-4 h-4 rounded-full border border-gray-300 text-transparent flex items-center justify-center text-[10px] font-bold';
        }
    }
    triggerLiveRecalc();
}

// fabricSets state: array of { setId: 1, labelId: 1, selectedSizes: { 'M': 2, 'L': 2 } }
let fabricSets = [];
let currentCalc = null;

function getSizeLimits(sz) {
    if (!sz) return { baseMin: 1, baseMax: 35 };
    const rawMax = parseInt(sz.max_quantity);
    const baseMax = rawMax > 0 ? rawMax : 35;
    const rawMin = parseInt(sz.base_quantity);
    const baseMin = (rawMin > 0) ? Math.min(rawMin, baseMax) : Math.min(1, baseMax);
    return { baseMin, baseMax };
}

function escapeHtmlStr(str) {
    if (str === null || str === undefined) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function getFabricQuantity() {
    const inp = document.getElementById('fabric_multiplier');
    return inp ? Math.max(1, parseInt(inp.value) || 1) : 1;
}

function getSetTotalPieces(setIdx) {
    if (!fabricSets[setIdx] || !fabricSets[setIdx].selectedSizes) return 0;
    return Object.values(fabricSets[setIdx].selectedSizes).reduce((sum, q) => sum + (parseInt(q) || 0), 0);
}

function adjustFabricMultiplier(delta) {
    const inp = document.getElementById('fabric_multiplier');
    if (!inp) return;
    let val = (parseInt(inp.value) || 1) + delta;
    if (val < 1) val = 1;
    inp.value = val;
    onFabricMultiplierChange(inp);
}

function onFabricMultiplierChange(inp) {
    let mult = parseInt(inp.value) || 1;
    if (mult < 1) {
        mult = 1;
        inp.value = 1;
    }

    // Update multiplier badge
    const badge = document.getElementById('fabricQtyBadge');
    if (badge) {
        badge.textContent = mult === 1 ? '1 Set (1x)' : `${mult} Sets (${mult}x)`;
    }

    // Adjust fabricSets array length
    while (fabricSets.length < mult) {
        const newIdx = fabricSets.length;
        fabricSets.push({
            setId: newIdx + 1,
            labelId: null,
            selectedSizes: {}
        });
    }
    if (fabricSets.length > mult) {
        fabricSets = fabricSets.slice(0, mult);
    }

    renderFabricSetContainers();
}

function initFabricSets() {
    const mult = getFabricQuantity();
    fabricSets = [];
    for (let i = 0; i < mult; i++) {
        fabricSets.push({
            setId: i + 1,
            labelId: null,
            selectedSizes: {}
        });
    }
    renderFabricSetContainers();
}

function renderFabricSetContainers() {
    const container = document.getElementById('fabricSetsContainer');
    if (!container) return;

    if (fabricSets.length === 0) {
        initFabricSets();
        return;
    }

    let html = '';

    fabricSets.forEach((set, setIdx) => {
        const activeSizes = Object.keys(set.selectedSizes);
        // Auto-clamp active sizes to their allowed baseMax
        activeSizes.forEach(sName => {
            const szObj = SIZES_CONFIG.find(s => s.size_name === sName);
            if (szObj) {
                const { baseMin, baseMax } = getSizeLimits(szObj);
                if (set.selectedSizes[sName] > baseMax) {
                    set.selectedSizes[sName] = baseMax;
                }
            }
        });
        const setPcs = getSetTotalPieces(setIdx);
        const isSetComplete = (setPcs === MIN_PIECES_PER_SET);
        const isSetOver = (setPcs > MIN_PIECES_PER_SET);
        const setPct = MIN_PIECES_PER_SET > 0 ? Math.min(100, Math.round((setPcs / MIN_PIECES_PER_SET) * 100)) : 100;

        let badgeHtml = '';
        if (setPcs === 0) {
            badgeHtml = `<span class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-gray-100 text-gray-600 border border-gray-200">0 / ${MIN_PIECES_PER_SET} pcs (Empty)</span>`;
        } else if (setPcs < MIN_PIECES_PER_SET) {
            badgeHtml = `<span class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-amber-50 text-amber-800 border border-amber-200 flex items-center gap-1">⚠️ ${setPcs} / ${MIN_PIECES_PER_SET} pcs (Need ${MIN_PIECES_PER_SET - setPcs} more)</span>`;
        } else if (isSetComplete) {
            badgeHtml = `<span class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center gap-1">✓ Complete (${setPcs}/${MIN_PIECES_PER_SET} pcs)</span>`;
        } else {
            badgeHtml = `<span class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 border border-rose-200 flex items-center gap-1">❌ ${setPcs} / ${MIN_PIECES_PER_SET} pcs (Exceeds Limit)</span>`;
        }

        html += `
        <div class="bg-white rounded-3xl border border-gray-100 p-5 md:p-6 shadow-xs space-y-5" id="set_card_${setIdx}">
            <!-- Set Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-3.5">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl ${isSetComplete ? 'bg-emerald-600' : 'bg-[#F25996]'} text-white font-black text-sm flex items-center justify-center shadow-xs shrink-0 transition-colors">
                        #${set.setId}
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="font-bold text-gray-900 text-sm">Fabric Set #${set.setId}</h3>
                            ${badgeHtml}
                        </div>
                        <p class="text-[11px] text-gray-400 mt-0.5">Required for this fabric: <strong class="text-gray-800 font-bold">${MIN_PIECES_PER_SET} pcs</strong> (Max: ${MIN_PIECES_PER_SET} pcs)</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <!-- Progress Bar per set -->
                    <div class="w-24 sm:w-28 bg-gray-100 h-2 rounded-full overflow-hidden border border-gray-200 hidden sm:block" title="Set progress: ${setPcs}/${MIN_PIECES_PER_SET} pcs">
                        <div class="h-full ${isSetComplete ? 'bg-emerald-500' : (isSetOver ? 'bg-rose-500' : 'bg-[#F25996]')} transition-all duration-300" style="width: ${setPct}%;"></div>
                    </div>

                    <!-- Custom Style / Label Dropdown for this Set -->
                    ${LABELS_CONFIG.length > 0 ? `
                    <div class="flex items-center gap-1.5">
                        <span class="text-[11px] font-bold text-[#F25996] hidden md:inline">Style:</span>
                        <select onchange="onSetLabelChange(${setIdx}, this.value, this)" class="text-xs font-bold py-1.5 px-3 rounded-xl border-2 ${set.labelId ? 'border-pink-400 bg-pink-50/70 text-[#db2777]' : 'border-pink-300 bg-pink-50/30 text-gray-800'} focus:outline-none focus:ring-2 focus:ring-[#F25996]/20 focus:border-[#F25996] cursor-pointer transition-all shadow-2xs">
                            <option value="" ${!set.labelId ? 'selected' : ''}>🏷️ Select Style / Label...</option>
                            ${LABELS_CONFIG.map(lbl => `
                                <option value="${lbl.id}" ${parseInt(set.labelId) === parseInt(lbl.id) ? 'selected' : ''}>
                                    🏷️ ${escapeHtmlStr(lbl.display_label)}
                                </option>
                            `).join('')}
                        </select>
                    </div>
                    ` : ''}
                </div>
            </div>

            <!-- 1. Choose Size Variants for this Set -->
            <div>
                <div class="flex items-center justify-between mb-2.5">
                    <label class="text-xs font-bold uppercase tracking-wider text-gray-700 flex items-center gap-1.5">
                        <span>1. Choose Size Variants:</span>
                        <span class="text-[10px] px-2 py-0.5 rounded-md bg-pink-50 text-[#F25996] font-bold border border-pink-200">Click to Select</span>
                    </label>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="selectAllSetVariants(${setIdx})" class="text-xs font-bold text-[#F25996] hover:underline cursor-pointer">Select All</button>
                        <span class="text-gray-300">•</span>
                        <button type="button" onclick="clearSetVariants(${setIdx})" class="text-xs font-bold text-gray-500 hover:text-red-500 cursor-pointer">Clear</button>
                    </div>
                </div>

                <!-- Variant Buttons Grid for this Set -->
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2.5">
                    ${SIZES_CONFIG.map(sz => {
                        const isSelected = !!set.selectedSizes[sz.size_name];
                        const { baseMin, baseMax } = getSizeLimits(sz);
                        const consumption = parseFloat(sz.fabric_consumption) || 1;
                        const pricePerPc = Math.round(FABRIC_PRICE_PER_UNIT * consumption * 100) / 100;

                        return `
                            <button type="button" 
                                    onclick="toggleSetSizeVariant(${setIdx}, '${escapeHtmlStr(sz.size_name)}')"
                                    class="variant-btn relative p-3 rounded-2xl border-2 transition-all text-left flex flex-col justify-between group cursor-pointer ${isSelected ? 'border-[#F25996] bg-pink-50/40 shadow-xs ring-2 ring-[#F25996]/20' : 'border-gray-200 bg-white hover:border-pink-300 hover:shadow-sm'}">
                                
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="w-7 h-7 rounded-xl bg-pink-50 text-[#F25996] font-black text-xs flex items-center justify-center border border-pink-200 group-hover:scale-105 transition-transform">
                                        ${escapeHtmlStr(sz.size_name)}
                                    </span>
                                    <span class="w-4 h-4 rounded-full border flex items-center justify-center text-[10px] font-bold transition-all ${isSelected ? 'bg-[#F25996] border-[#F25996] text-white' : 'border-gray-300 text-transparent group-hover:border-pink-400'}">
                                        ✓
                                    </span>
                                </div>

                                <div class="text-xs font-bold text-gray-900 mb-0.5">
                                    ₹${pricePerPc.toFixed(2)}<span class="text-[10px] text-gray-500 font-normal">/pc</span>
                                </div>

                                <div class="text-[10px] text-gray-500 border-t border-gray-100 pt-1 mt-0.5">
                                    <span>Allowed: <strong class="text-[#F25996] font-bold">${baseMin} – ${baseMax} pcs</strong></span>
                                </div>
                            </button>
                        `;
                    }).join('')}
                </div>
            </div>

            <!-- 2. Selected Size Quantities for this Set -->
            <div class="space-y-2 pt-2 border-t border-gray-100">
                <div class="flex items-center justify-between text-xs font-bold text-gray-700">
                    <span class="uppercase tracking-wider">2. Selected Size Quantities (Set #${set.setId}):</span>
                    <span class="text-[#F25996] font-medium text-[11px]">${activeSizes.length} variant(s) active (${setPcs}/${MIN_PIECES_PER_SET} Pcs)</span>
                </div>

                <div class="divide-y divide-gray-100 border border-gray-100 rounded-2xl overflow-hidden bg-gray-50/50">
                    ${activeSizes.length === 0 ? `
                        <div class="p-5 text-center bg-white flex flex-col items-center justify-center gap-1.5">
                            <div class="w-8 h-8 rounded-full bg-pink-50 text-[#F25996] flex items-center justify-center text-sm">
                                👆
                            </div>
                            <p class="text-xs font-bold text-gray-700">No Size Variants Selected for Set #${set.setId}</p>
                            <p class="text-[11px] text-gray-400">Click size buttons above to add variants for this fabric set (${MIN_PIECES_PER_SET} pcs required).</p>
                        </div>
                    ` : activeSizes.map(sName => {
                        const szObj = SIZES_CONFIG.find(s => s.size_name === sName) || {};
                        const { baseMin, baseMax } = getSizeLimits(szObj);
                        const qty = parseInt(set.selectedSizes[sName]) || baseMin;
                        const consumption = parseFloat(szObj.fabric_consumption) || 1;
                        const pricePerPc = Math.round(FABRIC_PRICE_PER_UNIT * consumption * 100) / 100;
                        const totalPcPrice = (pricePerPc * qty).toFixed(2);

                        return `
                            <div class="p-3.5 bg-white flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-pink-50/30 transition-colors">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-9 h-9 rounded-xl bg-pink-100 text-[#F25996] font-black text-xs flex items-center justify-center border border-pink-200 shrink-0">
                                        ${escapeHtmlStr(sName)}
                                    </div>
                                    <div>
                                        <div class="font-bold text-xs text-gray-900 flex items-center gap-1.5">
                                            <span>Size ${escapeHtmlStr(sName)}</span>
                                            <span class="text-[9.5px] font-bold px-1.5 py-0.5 rounded bg-pink-50 text-[#F25996] border border-pink-200">
                                                Allowed: ${baseMin} – ${baseMax} pcs
                                            </span>
                                        </div>
                                        <div class="text-[10.5px] text-gray-500 mt-0.5">
                                            Rate: <strong class="text-emerald-700">₹${pricePerPc.toFixed(2)}/pc</strong>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="inline-flex items-center border-2 border-[#F25996] rounded-full bg-white px-2 py-0.5 shadow-xs">
                                        <button type="button" onclick="adjustSetSizeQty(${setIdx}, '${escapeHtmlStr(sName)}', -1)" class="w-6 h-6 sm:w-7 sm:h-7 rounded-full flex items-center justify-center font-black text-base text-[#F25996] hover:bg-pink-50 transition-colors cursor-pointer select-none">
                                            -
                                        </button>
                                        <div class="h-3 w-px bg-pink-200 mx-1"></div>
                                        <input type="number" 
                                               value="${qty}" 
                                               min="${baseMin}" 
                                               max="${baseMax}" 
                                               oninput="onSetSizeQtyInput(${setIdx}, '${escapeHtmlStr(sName)}', this)" 
                                               class="w-10 h-6 sm:h-7 text-center font-black text-xs sm:text-sm text-[#F25996] bg-transparent focus:outline-none">
                                        <div class="h-3 w-px bg-pink-200 mx-1"></div>
                                        <button type="button" onclick="adjustSetSizeQty(${setIdx}, '${escapeHtmlStr(sName)}', 1)" class="w-6 h-6 sm:w-7 sm:h-7 rounded-full flex items-center justify-center font-black text-base text-[#F25996] hover:bg-pink-50 transition-colors cursor-pointer select-none">
                                            +
                                        </button>
                                    </div>

                                    <div class="text-right min-w-[65px]">
                                        <span class="text-[9.5px] font-bold uppercase tracking-wider text-gray-400 block">Subtotal</span>
                                        <span class="text-xs font-bold text-gray-800">${qty} Pcs</span>
                                        <span class="text-[10.5px] font-bold text-emerald-700 block">₹${totalPcPrice}</span>
                                    </div>

                                    <button type="button" 
                                             onclick="removeSetSizeVariant(${setIdx}, '${escapeHtmlStr(sName)}')" 
                                             title="Remove size ${escapeHtmlStr(sName)}"
                                             class="w-7 h-7 rounded-lg bg-gray-50 hover:bg-rose-50 text-gray-400 hover:text-rose-600 flex items-center justify-center transition-all cursor-pointer border border-gray-200 hover:border-rose-200 shrink-0">
                                         <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                     </button>
                                </div>
                            </div>
                        `;
                    }).join('')}
                </div>
            </div>
        </div>
        `;
    });

    container.innerHTML = html;

    updateLabelAllocationOverview();
    triggerLiveRecalc();
}

function toggleSetSizeVariant(setIdx, sizeName) {
    if (!fabricSets[setIdx]) return;
    const set = fabricSets[setIdx];
    const szObj = SIZES_CONFIG.find(s => s.size_name === sizeName) || {};
    const { baseMin, baseMax } = getSizeLimits(szObj);

    if (set.selectedSizes[sizeName]) {
        delete set.selectedSizes[sizeName];
    } else {
        const currentPcs = getSetTotalPieces(setIdx);
        if (currentPcs + baseMin > MAX_PIECES_PER_SET) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Fabric Set Limit Reached',
                    text: `Fabric Set #${set.setId} is set for ${MAX_PIECES_PER_SET} pcs maximum. Currently: ${currentPcs} pcs (Adding size ${sizeName} with min ${baseMin} pcs would exceed ${MAX_PIECES_PER_SET} pcs).`,
                    confirmButtonColor: '#F25996'
                });
            }
            return;
        }
        set.selectedSizes[sizeName] = baseMin;
    }
    renderFabricSetContainers();
}

function removeSetSizeVariant(setIdx, sizeName) {
    if (!fabricSets[setIdx]) return;
    delete fabricSets[setIdx].selectedSizes[sizeName];
    renderFabricSetContainers();
}

function selectAllSetVariants(setIdx) {
    if (!fabricSets[setIdx]) return;
    const set = fabricSets[setIdx];
    let runningTotal = 0;
    SIZES_CONFIG.forEach(sz => {
        const { baseMin } = getSizeLimits(sz);
        if (runningTotal + baseMin <= MAX_PIECES_PER_SET) {
            set.selectedSizes[sz.size_name] = baseMin;
            runningTotal += baseMin;
        } else if (runningTotal < MAX_PIECES_PER_SET) {
            const rem = MAX_PIECES_PER_SET - runningTotal;
            if (rem >= 1) {
                set.selectedSizes[sz.size_name] = rem;
                runningTotal += rem;
            }
        }
    });
    renderFabricSetContainers();
}

function clearSetVariants(setIdx) {
    if (!fabricSets[setIdx]) return;
    fabricSets[setIdx].selectedSizes = {};
    renderFabricSetContainers();
}

function adjustSetSizeQty(setIdx, sizeName, delta) {
    if (!fabricSets[setIdx]) return;
    const set = fabricSets[setIdx];
    const szObj = SIZES_CONFIG.find(s => s.size_name === sizeName) || {};
    const { baseMin, baseMax } = getSizeLimits(szObj);

    let currentVal = parseInt(set.selectedSizes[sizeName]) || baseMin;
    let nextVal = currentVal + delta;

    if (delta > 0) {
        const currentSetTotal = getSetTotalPieces(setIdx);
        if (currentSetTotal + delta > MAX_PIECES_PER_SET) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Fabric Set Limit Reached',
                    text: `Fabric Set #${set.setId} cannot exceed ${MAX_PIECES_PER_SET} total pieces. Currently: ${currentSetTotal} pcs.`,
                    confirmButtonColor: '#F25996',
                    timer: 2500
                });
            }
            return;
        }
        if (nextVal > baseMax) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'info',
                    title: 'Variant Limit Reached',
                    text: `Maximum allowed pieces for size ${sizeName} in Fabric Set #${set.setId} is ${baseMax} pcs.`,
                    confirmButtonColor: '#F25996',
                    timer: 2000
                });
            }
            return;
        }
    }
    if (nextVal < baseMin) {
        nextVal = baseMin;
    }
    set.selectedSizes[sizeName] = nextVal;
    renderFabricSetContainers();
}

function onSetSizeQtyInput(setIdx, sizeName, inp) {
    if (!fabricSets[setIdx]) return;
    const set = fabricSets[setIdx];
    const szObj = SIZES_CONFIG.find(s => s.size_name === sizeName) || {};
    const { baseMin, baseMax } = getSizeLimits(szObj);

    let val = parseInt(inp.value);
    if (isNaN(val) || val < baseMin) val = baseMin;
    
    const currentVal = parseInt(set.selectedSizes[sizeName]) || baseMin;
    const otherSizesTotal = getSetTotalPieces(setIdx) - currentVal;

    if (otherSizesTotal + val > MAX_PIECES_PER_SET) {
        val = Math.max(baseMin, MAX_PIECES_PER_SET - otherSizesTotal);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'warning',
                title: 'Set Piece Limit Reached',
                text: `Quantity adjusted to ${val} pcs so Fabric Set #${set.setId} does not exceed ${MAX_PIECES_PER_SET} total pieces.`,
                confirmButtonColor: '#F25996',
                timer: 2500
            });
        }
    }

    if (val > baseMax) {
        val = baseMax;
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'info',
                title: 'Variant Limit Reached',
                text: `Maximum allowed pieces for size ${sizeName} in Fabric Set #${set.setId} is ${baseMax} pcs.`,
                confirmButtonColor: '#F25996',
                timer: 2000
            });
        }
    }
    inp.value = val;
    set.selectedSizes[sizeName] = val;
    renderFabricSetContainers();
}

function onSetLabelChange(setIdx, labelId, selElem) {
    if (!fabricSets[setIdx]) return;
    fabricSets[setIdx].labelId = parseInt(labelId) || null;
    if (selElem) {
        if (fabricSets[setIdx].labelId) {
            selElem.className = 'text-xs font-bold py-1.5 px-3 rounded-xl border-2 border-pink-400 bg-pink-50/70 text-[#db2777] focus:outline-none focus:ring-2 focus:ring-[#F25996]/20 focus:border-[#F25996] cursor-pointer transition-all shadow-2xs';
        } else {
            selElem.className = 'text-xs font-bold py-1.5 px-3 rounded-xl border-2 border-pink-300 bg-pink-50/30 text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#F25996]/20 focus:border-[#F25996] cursor-pointer transition-all shadow-2xs';
        }
    }
    updateLabelAllocationOverview();
    triggerLiveRecalc();
}

function autoDistributeLabelsAcrossSets() {
    if (LABELS_CONFIG.length === 0) return;
    fabricSets.forEach((set, idx) => {
        const lbl = LABELS_CONFIG[idx % LABELS_CONFIG.length];
        set.labelId = lbl ? lbl.id : null;
    });
    renderFabricSetContainers();
}

function resetAllSetLabels() {
    if (LABELS_CONFIG.length === 0) return;
    const firstLabelId = LABELS_CONFIG[0].id;
    fabricSets.forEach(set => {
        set.labelId = firstLabelId;
    });
    renderFabricSetContainers();
}

function updateLabelAllocationOverview() {
    const listElem = document.getElementById('labelsAllocationOverviewList');
    if (!listElem) return;

    let totalStitchedPieces = 0;
    let totalAllocatedPieces = 0;
    const labelBreakdown = {};
    const unassignedSets = [];

    LABELS_CONFIG.forEach(lbl => {
        labelBreakdown[lbl.id] = {
            label: lbl,
            assignedSets: [],
            totalPieces: 0
        };
    });

    fabricSets.forEach(set => {
        let setPcs = 0;
        for (const sz in set.selectedSizes) {
            setPcs += (parseInt(set.selectedSizes[sz]) || 0);
        }
        totalStitchedPieces += setPcs;

        if (set.labelId && labelBreakdown[set.labelId]) {
            labelBreakdown[set.labelId].assignedSets.push({
                setId: set.setId,
                pieces: setPcs
            });
            labelBreakdown[set.labelId].totalPieces += setPcs;
            totalAllocatedPieces += setPcs;
        } else {
            unassignedSets.push({
                setId: set.setId,
                pieces: setPcs
            });
        }
    });

    const unassignedPieces = totalStitchedPieces - totalAllocatedPieces;
    const unassignedSetsWithPcs = unassignedSets.filter(s => s.pieces > 0);

    let html = '';

    // If there are unassigned sets that have pieces selected, show pink guided notification box
    if (unassignedSetsWithPcs.length > 0) {
        html += `
            <div class="p-4 rounded-2xl border border-pink-200 bg-pink-50/60 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs shadow-2xs">
                <div class="flex items-start gap-2.5 text-gray-800">
                    <span class="text-base">🏷️</span>
                    <div>
                        <strong class="font-bold text-[#db2777] block">Unassigned Fabric Set(s)</strong>
                        <div class="flex items-center gap-1.5 mt-1.5 flex-wrap">
                            ${unassignedSetsWithPcs.map(s => `<span class="px-2.5 py-1 rounded-lg bg-white border border-pink-200 font-bold text-[#F25996] text-[11px] shadow-2xs">Fabric Set #${s.setId}: ${s.pieces} pcs (Choose Style)</span>`).join(' ')}
                        </div>
                    </div>
                </div>
                <span class="text-[11px] font-bold text-[#F25996] bg-white px-2.5 py-1 rounded-lg border border-pink-200 shrink-0 shadow-2xs">
                    Please choose a style in each fabric container above
                </span>
            </div>
        `;
    }

    // Only display style labels that the user has chosen in at least one fabric set
    const activeAllocatedLabels = LABELS_CONFIG.filter(lbl => {
        const data = labelBreakdown[lbl.id];
        return data && data.assignedSets.length > 0;
    });

    if (activeAllocatedLabels.length === 0) {
        html += `
            <div class="p-6 rounded-2xl border border-dashed border-pink-200 bg-pink-50/20 text-center">
                <span class="text-2xl mb-1.5 block">🏷️</span>
                <p class="text-xs font-bold text-gray-800">No Customization Style Selected Yet</p>
                <p class="text-[11px] text-gray-500 mt-1 max-w-sm mx-auto">Choose a style label in Fabric Set #1 above to dynamically allocate quantities and view the breakdown here.</p>
            </div>
        `;
    } else {
        activeAllocatedLabels.forEach(lbl => {
            const data = labelBreakdown[lbl.id];
            const assignedSetsHtml = data.assignedSets.map(s => `<span class="inline-flex items-center gap-1 text-[10.5px] font-bold px-2 py-0.5 rounded-md bg-pink-100 text-[#db2777]">Set #${s.setId}: ${s.pieces} pcs</span>`).join(' ');

            html += `
                <div class="p-4 rounded-2xl border border-pink-200 bg-pink-50/20 transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-2xs">
                    <div class="flex-1 min-w-0">
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white text-gray-900 text-xs font-bold border border-gray-200 shadow-2xs">
                            <span>🏷️</span>
                            <span>${escapeHtmlStr(lbl.display_label)}</span>
                        </div>
                        ${lbl.description ? `<p class="text-[11px] text-gray-500 mt-1">${escapeHtmlStr(lbl.description)}</p>` : ''}
                        <div class="flex items-center gap-1.5 mt-2 flex-wrap">
                            ${assignedSetsHtml}
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <span class="text-xs font-bold text-gray-500">Allocated:</span>
                        <span class="font-black text-sm text-[#F25996] px-3.5 py-1 bg-white rounded-xl border border-pink-200 shadow-2xs">
                            ${data.totalPieces} Pcs
                        </span>
                    </div>
                </div>
            `;
        });
    }

    listElem.innerHTML = html;

    const reqElem = document.getElementById('labelTrackerTotalRequired');
    if (reqElem) reqElem.textContent = `${totalStitchedPieces} Pcs`;

    const allocElem = document.getElementById('labelTrackerTotalAllocated');
    if (allocElem) allocElem.textContent = `${totalAllocatedPieces} Pcs`;

    const unassignedContainer = document.getElementById('labelTrackerUnassignedContainer');
    const unassignedElem = document.getElementById('labelTrackerTotalUnassigned');
    if (unassignedContainer && unassignedElem) {
        if (unassignedPieces > 0) {
            unassignedElem.textContent = `${unassignedPieces} Pcs`;
            unassignedContainer.classList.remove('hidden');
            unassignedContainer.classList.add('flex');
        } else {
            unassignedContainer.classList.add('hidden');
            unassignedContainer.classList.remove('flex');
        }
    }

    const badgeElem = document.getElementById('labelTrackerBadge');
    if (badgeElem) {
        if (totalStitchedPieces === 0) {
            badgeElem.className = 'px-2.5 py-1 rounded-lg font-bold text-[11px] bg-gray-100 text-gray-600 border border-gray-200';
            badgeElem.textContent = 'Select sizes above';
        } else if (unassignedPieces > 0 || unassignedSetsWithPcs.length > 0) {
            badgeElem.className = 'px-2.5 py-1 rounded-lg font-bold text-[11px] bg-amber-100 text-amber-900 border border-amber-300';
            badgeElem.textContent = `⚠️ ${unassignedSetsWithPcs.length} Set(s) Unassigned`;
        } else {
            badgeElem.className = 'px-2.5 py-1 rounded-lg font-bold text-[11px] bg-emerald-50 text-emerald-800 border border-emerald-200';
            badgeElem.textContent = '✓ Perfectly Allocated';
        }
    }
}

function getAggregatedSizes() {
    const aggregated = {};
    fabricSets.forEach(set => {
        for (const [sz, qty] of Object.entries(set.selectedSizes)) {
            const num = parseInt(qty) || 0;
            if (num > 0) {
                aggregated[sz] = (aggregated[sz] || 0) + num;
            }
        }
    });
    return aggregated;
}

function getSelectedLabelQuantities() {
    const labelBreakdown = {};
    LABELS_CONFIG.forEach(lbl => {
        labelBreakdown[lbl.id] = {
            label_id: lbl.id,
            label_name: lbl.display_label,
            quantity: 0
        };
    });

    fabricSets.forEach(set => {
        if (!set.labelId) return;
        let setPcs = 0;
        for (const sz in set.selectedSizes) {
            setPcs += (parseInt(set.selectedSizes[sz]) || 0);
        }
        if (labelBreakdown[set.labelId]) {
            labelBreakdown[set.labelId].quantity += setPcs;
        }
    });

    return Object.values(labelBreakdown).filter(l => l.quantity > 0);
}

function triggerLiveRecalc() {
    const aggregatedSizes = getAggregatedSizes();
    const fabricQuantity = getFabricQuantity();
    const setsPayload = fabricSets.map(s => ({
        set_id: s.setId,
        sizes: s.selectedSizes
    }));

    fetch(`${BASE_URL}/fabrics/customization/calculate`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            fabric_id: FABRIC_ID,
            customization_id: CUSTOMIZATION_ID,
            fabric_quantity: fabricQuantity,
            sizes: aggregatedSizes,
            packaging_option: selectedPackagingOption,
            without_cover: (selectedPackagingOption === "without_cover"),
            sets: setsPayload
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            currentCalc = data;
            renderCalculation(data.consumption, data.pricing);
        }
    })
    .catch(err => {
        console.error("Calc error", err);
    });
}

function renderCalculation(c, p) {
    const effectiveMin = c.minimum_pieces || (MIN_PIECES_PER_SET * getFabricQuantity());
    const effectiveMax = c.maximum_pieces || effectiveMin;
    const totalPieces = c.total_pieces || 0;

    // Piece Tracker Text & Progress
    document.getElementById('summaryTotalPieces').textContent = `${totalPieces} / ${effectiveMax} Pcs`;
    
    const constraintText = document.getElementById('pieceConstraintText');
    if (constraintText) {
        constraintText.innerHTML = `
            <span>Min to Enable Payment: <strong class="text-gray-900">${effectiveMin} Pcs</strong> <span class="text-gray-400 font-normal">(${MIN_PIECES_PER_SET}/set)</span></span>
            <span>Max Allowed: <strong class="text-gray-900">${effectiveMax} Pcs</strong></span>
        `;
    }

    document.getElementById('summaryBaseConsumption').textContent = `${c.base_consumption} ${c.unit}`;
    const wastageElem = document.getElementById('summaryWastageAmount');
    if (wastageElem) wastageElem.textContent = `+${c.wastage_amount} ${c.unit}`;

    document.getElementById('summaryRequiredFabric').textContent = `${c.required_fabric} ${c.unit}`;
    document.getElementById('summaryFabricCost').textContent = `₹${p.fabric_cost.toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
    document.getElementById('summaryGstAmount').textContent = `₹${p.gst_amount.toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
    document.getElementById('summaryGrandTotal').textContent = `₹${p.total.toLocaleString('en-IN', {minimumFractionDigits: 2})}`;

    const isOverStock = (getFabricQuantity() > FABRIC_STOCK) || !c.is_stock_sufficient;
    const reqFabricContainer = document.getElementById('summaryRequiredFabric').parentElement;
    const statusBadge = document.getElementById('liveStatusBadge');
    const btnPay = document.getElementById('btnPayRazorpay');
    const btnProceed = document.getElementById('btnProceedCheckout');
    const setBtnState = (enabled) => {
        [btnPay, btnProceed].forEach(btn => {
            if (!btn) return;
            btn.disabled = !enabled;
            if (enabled) {
                btn.classList.remove('opacity-40', 'cursor-not-allowed');
                btn.classList.add('hover:opacity-95', 'cursor-pointer');
            } else {
                btn.classList.add('opacity-40', 'cursor-not-allowed');
                btn.classList.remove('hover:opacity-95', 'cursor-pointer');
            }
        });
    };
    const errorBox = document.getElementById('errorAlertBox');

    // Check separate set compliance
    const incompleteSets = [];
    const overLimitSets = [];
    const unassignedStyleSets = [];

    fabricSets.forEach(s => {
        const setPcs = getSetTotalPieces(s.setId - 1);
        if (setPcs < MIN_PIECES_PER_SET) {
            incompleteSets.push({ id: s.setId, pcs: setPcs, needed: MIN_PIECES_PER_SET - setPcs });
        } else if (setPcs > MAX_PIECES_PER_SET) {
            overLimitSets.push({ id: s.setId, pcs: setPcs, excess: setPcs - MAX_PIECES_PER_SET });
        }
        if (setPcs > 0 && !s.labelId) {
            unassignedStyleSets.push({ id: s.setId, pcs: setPcs });
        }
    });

    const hasSetErrors = (incompleteSets.length > 0) || (overLimitSets.length > 0) || (unassignedStyleSets.length > 0 && LABELS_CONFIG.length > 0);

    // Progress bar for total pieces
    const bar = document.getElementById('pieceProgressBar');
    const pct = effectiveMax > 0 ? Math.min(100, Math.round((totalPieces / effectiveMax) * 100)) : 100;
    bar.style.width = pct + '%';

    if (isOverStock) {
        // Exceeded Fabric stock
        bar.className = 'h-full bg-rose-600 transition-all duration-300';
        reqFabricContainer.className = 'flex items-center justify-between font-bold bg-rose-50 p-2.5 rounded-xl border border-rose-200 text-rose-900';
        statusBadge.className = 'text-[10px] sm:text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-800 border border-rose-300 shrink-0 whitespace-nowrap';
        statusBadge.textContent = '❌ Exceeds Fabric Stock';

        errorBox.innerHTML = `
            <div class="flex items-start gap-2.5">
                <span class="text-base">⚠️</span>
                <div>
                    <strong class="font-bold block mb-1">Fabric Stock Exceeded!</strong>
                    <span>You selected <strong>${getFabricQuantity()} Sets</strong>, but only <strong>${FABRIC_STOCK} ${UNIT}</strong> are available in inventory. Please reduce the fabric set quantity.</span>
                </div>
            </div>
        `;
        errorBox.classList.remove('hidden');

        btnPay.disabled = true;
        btnPay.classList.add('opacity-40', 'cursor-not-allowed');
        btnPay.classList.remove('hover:opacity-95', 'cursor-pointer');

    } else if (totalPieces > effectiveMax || overLimitSets.length > 0) {
        bar.className = 'h-full bg-rose-600 transition-all duration-300';
        reqFabricContainer.className = 'flex items-center justify-between font-bold bg-rose-50 p-2.5 rounded-xl border border-rose-200 text-rose-900';
        statusBadge.className = 'text-[10px] sm:text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-800 border border-rose-300 shrink-0 whitespace-nowrap';
        statusBadge.textContent = `❌ Exceeds Limit (${totalPieces}/${effectiveMax} Pcs)`;

        let overMsg = '';
        if (overLimitSets.length > 0) {
            overMsg = overLimitSets.map(ov => `<p>• <strong>Fabric Set #${ov.id}</strong> has <strong>${ov.pcs} pcs</strong> (${ov.excess} pcs over the ${MAX_PIECES_PER_SET} pcs limit).</p>`).join('');
        } else {
            overMsg = `<p>Maximum allowed is <strong>${effectiveMax} pcs</strong> for ${getFabricQuantity()} Set(s).</p>`;
        }

        errorBox.innerHTML = `
            <div class="flex items-start gap-2.5">
                <span class="text-base">⚠️</span>
                <div>
                    <strong class="font-bold block mb-0.5 text-rose-900">Set Piece Limit Exceeded</strong>
                    <div class="text-rose-800 text-xs leading-relaxed space-y-1">
                        ${overMsg}
                        <p class="font-bold pt-0.5 text-[11px]">Please reduce variant quantities to enable payment.</p>
                    </div>
                </div>
            </div>
        `;
        errorBox.classList.remove('hidden');

        btnPay.disabled = true;
        btnPay.classList.add('opacity-40', 'cursor-not-allowed');
        btnPay.classList.remove('hover:opacity-95', 'cursor-pointer');

    } else if (incompleteSets.length > 0 && totalPieces > 0) {
        const firstInc = incompleteSets[0];
        const multNotice = incompleteSets.length > 1 ? ` (${incompleteSets.length} sets need pieces)` : '';
        bar.className = 'h-full bg-[#F25996] transition-all duration-300';
        reqFabricContainer.className = 'flex items-center justify-between font-bold bg-pink-50/60 p-2.5 rounded-xl border border-pink-100 text-[#db2777]';
        statusBadge.className = 'text-[10px] sm:text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-900 border border-amber-300 shrink-0 whitespace-nowrap';
        statusBadge.textContent = `⏳ Set #${firstInc.id} Needs ${firstInc.needed} Pcs${multNotice}`;

        errorBox.innerHTML = `
            <div class="flex items-start gap-2.5">
                <span class="text-base">⏳</span>
                <div>
                    <strong class="font-bold block mb-0.5 text-pink-900">Separate Fabric Set Requirement (${MIN_PIECES_PER_SET} Pcs / Set)</strong>
                    <div class="text-pink-800 text-xs leading-relaxed space-y-1">
                        ${incompleteSets.map(inc => `
                            <p>• <strong>Fabric Set #${inc.id}</strong> currently has <strong>${inc.pcs} pcs</strong> (Please add <strong>${inc.needed} more pcs</strong>).</p>
                        `).join('')}
                        <p class="font-bold pt-1 text-[11px]">Each separate fabric set container must have at least ${MIN_PIECES_PER_SET} pieces to enable online payment.</p>
                    </div>
                </div>
            </div>
        `;
        errorBox.classList.remove('hidden');

        btnPay.disabled = true;
        btnPay.classList.add('opacity-40', 'cursor-not-allowed');
        btnPay.classList.remove('hover:opacity-95', 'cursor-pointer');

    } else if (unassignedStyleSets.length > 0 && LABELS_CONFIG.length > 0 && totalPieces > 0) {
        const firstUn = unassignedStyleSets[0];
        bar.className = 'h-full bg-[#F25996] transition-all duration-300';
        reqFabricContainer.className = 'flex items-center justify-between font-bold bg-pink-50/60 p-2.5 rounded-xl border border-pink-100 text-[#db2777]';
        statusBadge.className = 'text-[10px] sm:text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-900 border border-amber-300 shrink-0 whitespace-nowrap';
        statusBadge.textContent = `⏳ Set #${firstUn.id} Style Needed`;

        errorBox.innerHTML = `
            <div class="flex items-start gap-2.5">
                <span class="text-base">🏷️</span>
                <div>
                    <strong class="font-bold block mb-0.5 text-pink-900">Custom Style Selection Required</strong>
                    <div class="text-pink-800 text-xs leading-relaxed space-y-1">
                        ${unassignedStyleSets.map(s => `
                            <p>• <strong>Fabric Set #${s.id}</strong> has <strong>${s.pcs} pcs</strong> but no custom style is assigned.</p>
                        `).join('')}
                        <p class="font-bold pt-1 text-[11px]">Please assign a style label for each fabric set above to enable online payment.</p>
                    </div>
                </div>
            </div>
        `;
        errorBox.classList.remove('hidden');

        btnPay.disabled = true;
        btnPay.classList.add('opacity-40', 'cursor-not-allowed');
        btnPay.classList.remove('hover:opacity-95', 'cursor-pointer');

    } else if (c.is_valid && !hasSetErrors && totalPieces >= effectiveMin && totalPieces <= effectiveMax) {
        bar.className = 'h-full bg-emerald-500 transition-all duration-300';
        reqFabricContainer.className = 'flex items-center justify-between font-bold bg-emerald-50 p-2.5 rounded-xl border border-emerald-200 text-emerald-950';
        statusBadge.className = 'text-[10px] sm:text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-300 shrink-0 whitespace-nowrap';
        statusBadge.textContent = `✅ Payment Enabled (${totalPieces} Pcs)`;

        errorBox.classList.add('hidden');

        btnPay.disabled = false;
        btnPay.classList.remove('opacity-40', 'cursor-not-allowed');
        btnPay.classList.add('hover:opacity-95', 'cursor-pointer');

    } else if (c.errors && c.errors.length > 0) {
        bar.className = 'h-full bg-rose-600 transition-all duration-300';
        reqFabricContainer.className = 'flex items-center justify-between font-bold bg-rose-50 p-2.5 rounded-xl border border-rose-200 text-rose-900';
        statusBadge.className = 'text-[10px] sm:text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-800 border border-rose-300 shrink-0 whitespace-nowrap';
        statusBadge.textContent = '⚠️ Check Variant Limits';

        errorBox.innerHTML = `
            <div class="flex items-start gap-2.5">
                <span class="text-base">⚠️</span>
                <div>
                    <strong class="font-bold block mb-0.5 text-rose-900">Size Variant Limit Issue</strong>
                    <div class="text-rose-800 text-xs leading-relaxed space-y-1">
                        ${c.errors.map(err => `<p>• ${err}</p>`).join('')}
                    </div>
                </div>
            </div>
        `;
        errorBox.classList.remove('hidden');

        btnPay.disabled = true;
        btnPay.classList.add('opacity-40', 'cursor-not-allowed');
        btnPay.classList.remove('hover:opacity-95', 'cursor-pointer');

    } else {
        bar.className = 'h-full bg-gray-300 transition-all duration-300';
        reqFabricContainer.className = 'flex items-center justify-between font-bold bg-gray-50 p-2.5 rounded-xl border border-gray-100 text-gray-800';
        statusBadge.className = 'text-[10px] sm:text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-600 border border-gray-200 shrink-0 whitespace-nowrap';
        statusBadge.textContent = `Min ${effectiveMin} Pcs Required (${MIN_PIECES_PER_SET}/set)`;

        errorBox.classList.add('hidden');
        btnPay.disabled = true;
        btnPay.classList.add('opacity-40', 'cursor-not-allowed');
        btnPay.classList.remove('hover:opacity-95', 'cursor-pointer');
    }
}

function onAddressSelectChange(sel) {
    const input = document.getElementById('shippingAddressInput');
    const previewCard = document.getElementById('savedAddressPreviewCard');
    const customContainer = document.getElementById('customAddressContainer');
    const labelElem = document.getElementById('addressCardLabel');
    const textElem = document.getElementById('addressCardText');

    if (sel.value === '__CUSTOM__' || !sel.value) {
        enableCustomAddressMode();
    } else {
        if (customContainer) customContainer.classList.add('hidden');
        if (previewCard) previewCard.classList.remove('hidden');
        if (input) input.value = sel.value;

        const opt = sel.options[sel.selectedIndex];
        const label = opt ? opt.getAttribute('data-label') || 'Saved Address' : 'Saved Address';
        if (labelElem) labelElem.textContent = label;
        if (textElem) textElem.textContent = sel.value;
    }
}

function enableCustomAddressMode() {
    const previewCard = document.getElementById('savedAddressPreviewCard');
    const customContainer = document.getElementById('customAddressContainer');
    const selectElem = document.getElementById('shippingAddressSelect');
    const input = document.getElementById('shippingAddressInput');

    if (previewCard) previewCard.classList.add('hidden');
    if (customContainer) customContainer.classList.remove('hidden');
    if (selectElem) selectElem.value = '__CUSTOM__';
    if (input) {
        input.focus();
    }
}

function cancelCustomAddressMode() {
    const selectElem = document.getElementById('shippingAddressSelect');
    if (selectElem && selectElem.options.length > 1) {
        selectElem.selectedIndex = 0;
        onAddressSelectChange(selectElem);
    }
}

function appendTailoringSpec(specText) {
    const textarea = document.getElementById('orderNotesInput');
    if (!textarea) return;

    let current = textarea.value.trim();
    if (current.includes(specText)) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'info',
                title: 'Specification Added',
                text: 'This specification is already included in your tailoring notes.',
                confirmButtonColor: '#F25996',
                timer: 1500
            });
        }
        return;
    }

    if (current.length > 0) {
        textarea.value = current + '\n• ' + specText;
    } else {
        textarea.value = '• ' + specText;
    }
    textarea.focus();
}

function checkLoginOrPrompt() {
    if (!IS_LOGGED_IN) {
        Swal.fire({
            title: 'Please Login',
            text: 'You need to be logged in to place custom wholesale orders.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#F25996',
            confirmButtonText: 'Go to Login',
            cancelButtonText: 'Cancel'
        }).then(result => {
            if (result.isConfirmed) {
                window.location.href = `${BASE_URL}/login`;
            }
        });
        return false;
    }
    return true;
}

function getPayload() {
    const aggregatedSizes = getAggregatedSizes();
    const fabricQuantity = getFabricQuantity();
    const addrElem = document.getElementById('shippingAddressInput');
    const address = addrElem ? addrElem.value.trim() : '';
    const notesElem = document.getElementById('orderNotesInput');
    const notes = notesElem ? notesElem.value.trim() : '';
    const labelQuantities = getSelectedLabelQuantities();

    // Prepare clean sets breakdown for backend record keeping
    const setsBreakdown = fabricSets.map(s => {
        let setPcs = 0;
        for (const sz in s.selectedSizes) {
            setPcs += (parseInt(s.selectedSizes[sz]) || 0);
        }
        const lblObj = LABELS_CONFIG.find(l => parseInt(l.id) === parseInt(s.labelId));
        return {
            set_id: s.setId,
            label_id: s.labelId,
            label_name: lblObj ? lblObj.display_label : 'Standard Style',
            sizes: s.selectedSizes,
            total_pieces: setPcs
        };
    });

    return {
        fabric_id: FABRIC_ID,
        customization_id: CUSTOMIZATION_ID,
        fabric_quantity: fabricQuantity,
        sizes: aggregatedSizes,
            packaging_option: selectedPackagingOption,
            without_cover: (selectedPackagingOption === "without_cover"),
        sets: setsBreakdown,
        shipping_address: address,
        notes: notes,
        label_quantities: labelQuantities
    };
}

// -------------------------------------------------------------
// Online Payment via Razorpay
// -------------------------------------------------------------

// -------------------------------------------------------------
// Proceed to Step 2: Dedicated Checkout & Razorpay Page
// -------------------------------------------------------------
function proceedToCheckout() {
    if (!checkLoginOrPrompt()) return;

    const sizes = getAggregatedSizes();
    const fabricQuantity = getFabricQuantity();
    const mult = fabricQuantity;
    const effectiveMin = MIN_PIECES_PER_SET * mult;
    const effectiveMax = MAX_PIECES_PER_SET * mult;
    const totalPcs = Object.values(sizes).reduce((a, b) => a + b, 0);

    if (totalPcs === 0) {
        Swal.fire({
            title: 'Select Size Quantities',
            text: 'Please select quantities for at least one size variant in each fabric set to proceed to checkout.',
            icon: 'info',
            confirmButtonColor: '#F25996'
        });
        return;
    }

    // Validate every separate set container
    for (let i = 0; i < fabricSets.length; i++) {
        const setPcs = getSetTotalPieces(i);
        if (setPcs < MIN_PIECES_PER_SET) {
            Swal.fire({
                title: 'Incomplete Fabric Set',
                text: `Fabric Set #${i + 1} has only ${setPcs} pieces. Each separate fabric set must have at least ${MIN_PIECES_PER_SET} pieces to enable checkout.`,
                icon: 'warning',
                confirmButtonColor: '#F25996'
            });
            return;
        }
        if (setPcs > MAX_PIECES_PER_SET) {
            Swal.fire({
                title: 'Fabric Set Limit Exceeded',
                text: `Fabric Set #${i + 1} has ${setPcs} pieces. Maximum allowed is ${MAX_PIECES_PER_SET} pieces per fabric set.`,
                icon: 'warning',
                confirmButtonColor: '#F25996'
            });
            return;
        }
        if (LABELS_CONFIG.length > 0 && !fabricSets[i].labelId) {
            Swal.fire({
                title: 'Select Custom Style',
                text: `Please assign a custom style/label for Fabric Set #${fabricSets[i].setId} before proceeding to checkout.`,
                icon: 'warning',
                confirmButtonColor: '#F25996'
            });
            return;
        }
    }

    if (fabricQuantity > FABRIC_STOCK) {
        Swal.fire({
            title: 'Fabric Stock Shortage',
            text: `You selected ${fabricQuantity} Sets, but only ${FABRIC_STOCK} Qty are available in stock.`,
            icon: 'warning',
            confirmButtonColor: '#F25996'
        });
        return;
    }

    // Submit via dynamic POST form to checkout page
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `${BASE_URL}/fabrics/customize/checkout`;

    const addField = (name, val) => {
        const inp = document.createElement('input');
        inp.type = 'hidden';
        inp.name = name;
        inp.value = val;
        form.appendChild(inp);
    };

    addField('fabric_id', FABRIC_ID);
    addField('customization_id', CUSTOMIZATION_ID);
    addField('fabric_quantity', fabricQuantity);
    addField('packaging_option', selectedPackagingOption);
    addField('without_cover', selectedPackagingOption === 'without_cover' ? '1' : '0');

    Object.keys(sizes).forEach(sz => {
        addField(`sizes[${sz}]`, sizes[sz]);
    });

    // Also pass clean sets JSON
    const setsBreakdown = fabricSets.map(s => {
        let setPcs = 0;
        for (const sz in s.selectedSizes) {
            setPcs += (parseInt(s.selectedSizes[sz]) || 0);
        }
        const lblObj = LABELS_CONFIG.find(l => parseInt(l.id) === parseInt(s.labelId));
        return {
            set_id: s.setId,
            label_id: s.labelId,
            label_name: lblObj ? lblObj.display_label : 'Standard Style',
            sizes: s.selectedSizes,
            total_pieces: setPcs
        };
    });
    addField('sets_json', JSON.stringify(setsBreakdown));

    document.body.appendChild(form);
    form.submit();
}

function payViaRazorpay() {
    if (!checkLoginOrPrompt()) return;

    const payload = getPayload();
    if (Object.keys(payload.sizes).length === 0) {
        Swal.fire({ title: 'Select Sizes', text: 'Please add quantities for at least one size.', icon: 'warning', confirmButtonColor: '#F25996' });
        return;
    }

    // Validate every separate set container
    for (let i = 0; i < fabricSets.length; i++) {
        const setPcs = getSetTotalPieces(i);
        if (setPcs < MIN_PIECES_PER_SET) {
            Swal.fire({
                title: 'Incomplete Fabric Set',
                text: `Fabric Set #${i + 1} has only ${setPcs} pieces. Each separate fabric set must have at least ${MIN_PIECES_PER_SET} pieces to enable payment.`,
                icon: 'warning',
                confirmButtonColor: '#F25996'
            });
            return;
        }
        if (setPcs > MAX_PIECES_PER_SET) {
            Swal.fire({
                title: 'Fabric Set Limit Exceeded',
                text: `Fabric Set #${i + 1} has ${setPcs} pieces. Maximum allowed is ${MAX_PIECES_PER_SET} pieces per fabric set.`,
                icon: 'warning',
                confirmButtonColor: '#F25996'
            });
            return;
        }
        if (LABELS_CONFIG.length > 0 && !fabricSets[i].labelId) {
            Swal.fire({
                title: 'Select Custom Style',
                text: `Please assign a custom style/label for Fabric Set #${fabricSets[i].setId} before proceeding to payment.`,
                icon: 'warning',
                confirmButtonColor: '#F25996'
            });
            return;
        }
    }

    if (!currentCalc || !currentCalc.consumption.is_valid || (getFabricQuantity() > FABRIC_STOCK)) {
        let msg = 'Please satisfy the required piece requirements.';
        if (getFabricQuantity() > FABRIC_STOCK || (currentCalc && !currentCalc.consumption.is_stock_sufficient)) {
            msg = `<strong>Fabric Inventory Shortage:</strong> You selected <strong>${getFabricQuantity()} Sets</strong>, but only <strong>${FABRIC_STOCK} ${UNIT}</strong> are available in stock.`;
        } else if (currentCalc && currentCalc.consumption.errors) {
            msg = currentCalc.consumption.errors.join('<br>');
        }
        Swal.fire({ title: 'Validation Notice', html: msg, icon: 'warning', confirmButtonColor: '#F25996' });
        return;
    }

    Swal.fire({ title: 'Connecting to Razorpay...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

    // Step 1: Initialize Razorpay Order
    fetch(`${BASE_URL}/fabrics/customization/razorpay-init`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(data => {
        Swal.close();
        if (!data.success) {
            Swal.fire({ title: 'Payment Init Failed', text: data.message || 'Unable to start online payment', icon: 'error', confirmButtonColor: '#F25996' });
            return;
        }

        // Razorpay Options
        const options = {
            "key": data.razorpay_key,
            "amount": data.amount_in_paise,
            "currency": data.currency || "INR",
            "name": "GRM B2B Garment Customizer",
            "description": `Custom Order: ${data.program_name}`,
            "order_id": data.razorpay_order_id,
            "prefill": {
                "name": data.user.name,
                "email": data.user.email,
                "contact": data.user.phone
            },
            "theme": {
                "color": "#F25996"
            },
            "handler": function (response) {
                // Step 2: Verify Razorpay Payment Signature
                Swal.fire({ title: 'Verifying Payment & Confirming Order...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                const verifyPayload = Object.assign({}, payload, {
                    razorpay_payment_id: response.razorpay_payment_id,
                    razorpay_order_id: response.razorpay_order_id || data.razorpay_order_id,
                    razorpay_signature: response.razorpay_signature || ''
                });

                fetch(`${BASE_URL}/fabrics/customization/razorpay-verify`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(verifyPayload)
                })
                .then(r => r.json())
                .then(verifyRes => {
                    if (verifyRes.success) {
                        Swal.fire({
                            title: 'Order Confirmed! 🧵',
                            text: verifyRes.message,
                            icon: 'success',
                            confirmButtonColor: '#F25996',
                            confirmButtonText: 'View Invoice & Order'
                        }).then(() => {
                            window.location.href = verifyRes.redirect_url || `${BASE_URL}/dashboard/orders`;
                        });
                    } else {
                        Swal.fire({ title: 'Verification Error', text: verifyRes.message || 'Payment verification failed', icon: 'error', confirmButtonColor: '#F25996' });
                    }
                })
                .catch(err => {
                    Swal.fire({ title: 'Network Error', text: 'Error verifying payment with server.', icon: 'error', confirmButtonColor: '#F25996' });
                });
            },
            "modal": {
                "ondismiss": function() {
                    console.log('Razorpay modal closed by user');
                }
            }
        };

        try {
            const rzp = new Razorpay(options);
            rzp.on('payment.failed', function (resp){
                Swal.fire({ title: 'Payment Failed', text: resp.error.description || 'Payment was declined or cancelled.', icon: 'error', confirmButtonColor: '#F25996' });
            });
            rzp.open();
        } catch (e) {
            console.error("Razorpay error", e);
            Swal.fire({ title: 'Razorpay Error', text: 'Unable to open Razorpay modal: ' + e.message, icon: 'error', confirmButtonColor: '#F25996' });
        }
    })
    .catch(err => {
        Swal.fire({ title: 'Connection Error', text: 'Unable to connect to payment gateway.', icon: 'error', confirmButtonColor: '#F25996' });
    });
}

// Custom Pink Dropdown Component Handler
function initCustomPinkSelects() {
    const selects = document.querySelectorAll('select.custom-select-pink');
    
    selects.forEach(function (sel) {
        if (sel.dataset.customInit) return;
        sel.dataset.customInit = '1';

        // Hide the native select
        sel.style.display = 'none';

        // Create Wrapper
        const wrapper = document.createElement('div');
        wrapper.className = 'custom-select-pink-wrapper mb-2.5';
        sel.parentNode.insertBefore(wrapper, sel);
        wrapper.appendChild(sel);

        // Create Trigger Button
        const trigger = document.createElement('button');
        trigger.type = 'button';
        trigger.className = 'custom-select-pink-trigger';

        const label = document.createElement('span');
        label.className = 'custom-select-pink-label';
        const selectedOption = sel.options[sel.selectedIndex] || sel.options[0];
        label.textContent = selectedOption ? selectedOption.text : 'Select Address';
        trigger.appendChild(label);

        // SVG Chevron Arrow (matches Image 2 style)
        const arrow = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        arrow.setAttribute('class', 'custom-select-pink-arrow');
        arrow.setAttribute('viewBox', '0 0 20 20');
        arrow.setAttribute('fill', 'none');
        arrow.setAttribute('stroke', 'currentColor');
        arrow.setAttribute('stroke-width', '2.2');
        arrow.setAttribute('stroke-linecap', 'round');
        arrow.setAttribute('stroke-linejoin', 'round');
        const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
        path.setAttribute('d', 'M5 7.5l5 5 5-5');
        arrow.appendChild(path);
        trigger.appendChild(arrow);

        wrapper.appendChild(trigger);

        // Create Menu List
        const menu = document.createElement('div');
        menu.className = 'custom-select-pink-menu';

        Array.from(sel.options).forEach(function (opt) {
            const item = document.createElement('div');
            item.className = 'custom-select-pink-item' + (opt.selected ? ' selected' : '');
            item.textContent = opt.text;

            item.addEventListener('click', function (e) {
                e.stopPropagation();
                sel.value = opt.value;
                label.textContent = opt.text;

                menu.querySelectorAll('.custom-select-pink-item').forEach(function (el) {
                    el.classList.remove('selected');
                });
                item.classList.add('selected');

                trigger.classList.remove('open');
                menu.classList.remove('open');

                // Trigger change event & handler
                sel.dispatchEvent(new Event('change'));
                if (typeof onAddressSelectChange === 'function') {
                    onAddressSelectChange(sel);
                }
            });

            menu.appendChild(item);
        });

        wrapper.appendChild(menu);

        // Toggle dropdown on click
        trigger.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const isOpen = trigger.classList.contains('open');

            // Close all other open dropdowns
            document.querySelectorAll('.custom-select-pink-trigger.open').forEach(function (t) {
                t.classList.remove('open');
                const m = t.parentElement ? t.parentElement.querySelector('.custom-select-pink-menu') : null;
                if (m) m.classList.remove('open');
            });

            if (!isOpen) {
                trigger.classList.add('open');
                menu.classList.add('open');
            }
        });
    });

    // Close on click outside
    document.addEventListener('click', function () {
        document.querySelectorAll('.custom-select-pink-trigger.open').forEach(function (t) {
            t.classList.remove('open');
            const m = t.parentElement ? t.parentElement.querySelector('.custom-select-pink-menu') : null;
            if (m) m.classList.remove('open');
        });
    });

    // Close on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.custom-select-pink-trigger.open').forEach(function (t) {
                t.classList.remove('open');
                const m = t.parentElement ? t.parentElement.querySelector('.custom-select-pink-menu') : null;
                if (m) m.classList.remove('open');
            });
        }
    });
}

// Initial calculation & dropdown initialization on load
document.addEventListener('DOMContentLoaded', () => {
    initFabricSets();
    initCustomPinkSelects();
});
</script>

<!-- Custom Pink Dropdown Component Styles (Matches Image 2 Style) -->
<style>
    .custom-select-pink-wrapper {
        position: relative;
        width: 100%;
    }
    .custom-select-pink-trigger {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        padding: 0.625rem 1.125rem;
        background-color: #ffffff;
        border: 1.5px solid #FDBFDD;
        border-radius: 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        cursor: pointer;
        outline: none;
        user-select: none;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    }
    .custom-select-pink-trigger:hover {
        border-color: #F25996;
    }
    .custom-select-pink-trigger:focus,
    .custom-select-pink-trigger.open {
        border-color: #F25996 !important;
        box-shadow: 0 0 0 3px rgba(242, 89, 150, 0.2) !important;
    }
    .custom-select-pink-label {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        text-align: left;
        flex: 1;
    }
    .custom-select-pink-arrow {
        width: 1.05rem;
        height: 1.05rem;
        color: #F25996;
        transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        flex-shrink: 0;
        pointer-events: none;
    }
    .custom-select-pink-trigger.open .custom-select-pink-arrow {
        transform: rotate(180deg);
    }
    .custom-select-pink-menu {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        min-width: 100%;
        background: #ffffff;
        border: 1.5px solid #FDBFDD;
        border-radius: 1.125rem;
        box-shadow: 0 12px 30px rgba(242, 89, 150, 0.15), 0 4px 10px rgba(0,0,0,0.05);
        padding: 6px;
        z-index: 60;
        max-height: 240px;
        overflow-y: auto;
        display: none;
        animation: cfFadeIn 0.15s ease-out;
    }
    .custom-select-pink-menu.open {
        display: block;
    }
    .custom-select-pink-menu::-webkit-scrollbar {
        width: 4px;
    }
    .custom-select-pink-menu::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-select-pink-menu::-webkit-scrollbar-thumb {
        background: #F25996;
        border-radius: 4px;
    }
    .custom-select-pink-item {
        padding: 0.625rem 0.875rem;
        font-size: 0.875rem;
        border-radius: 0.75rem;
        cursor: pointer;
        color: #374151;
        font-weight: 500;
        transition: all 0.15s ease;
        margin-bottom: 2px;
        line-height: 1.4;
    }
    .custom-select-pink-item:last-child {
        margin-bottom: 0;
    }
    .custom-select-pink-item:hover {
        background-color: #fdf2f7;
        color: #F25996;
        font-weight: 600;
    }
    .custom-select-pink-item.selected {
        background-color: #F25996 !important;
        color: #ffffff !important;
        font-weight: 600;
    }
    @keyframes cfFadeIn {
        from { opacity: 0; transform: translateY(-4px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ── SweetAlert Pink & Round Pill Modal Customization ── */
    .swal2-popup {
        border-radius: 1.5rem !important;
        padding: 2rem 1.5rem !important;
        font-family: inherit !important;
    }
    .swal2-title {
        font-weight: 800 !important;
        color: #111827 !important;
        letter-spacing: -0.02em !important;
    }
    .swal2-html-container {
        color: #4b5563 !important;
        font-size: 0.9375rem !important;
        line-height: 1.6 !important;
    }
    .swal2-icon {
        width: 3.25rem !important;
        height: 3.25rem !important;
        margin: 0.5rem auto 1rem !important;
        border-width: 2.5px !important;
    }
    .swal2-icon .swal2-icon-content {
        font-size: 1.75rem !important;
        line-height: 3.25rem !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    .swal2-icon.swal2-question {
        border-color: #F25996 !important;
        color: #F25996 !important;
    }
    .swal2-icon.swal2-warning {
        border-color: #F25996 !important;
        color: #F25996 !important;
    }
    .swal2-icon.swal2-info {
        border-color: #F25996 !important;
        color: #F25996 !important;
    }
    .swal2-actions {
        gap: 0.75rem !important;
        margin-top: 1.5rem !important;
    }
    .swal2-styled.swal2-confirm {
        background: linear-gradient(135deg, #F25996 0%, #ea3c85 100%) !important;
        border-radius: 9999px !important;
        padding: 0.75rem 2rem !important;
        font-weight: 800 !important;
        font-size: 0.875rem !important;
        letter-spacing: 0.01em !important;
        box-shadow: 0 4px 14px rgba(242, 89, 150, 0.35) !important;
        transition: all 0.2s ease !important;
        border: none !important;
    }
    .swal2-styled.swal2-confirm:hover {
        background: linear-gradient(135deg, #e04885 0%, #db2777 100%) !important;
        box-shadow: 0 6px 20px rgba(242, 89, 150, 0.5) !important;
        transform: translateY(-1px) !important;
    }
    .swal2-styled.swal2-cancel {
        background-color: #f3f4f6 !important;
        color: #4b5563 !important;
        border-radius: 9999px !important;
        padding: 0.75rem 1.75rem !important;
        font-weight: 700 !important;
        font-size: 0.875rem !important;
        transition: all 0.2s ease !important;
        border: 1px solid #e5e7eb !important;
    }
    .swal2-styled.swal2-cancel:hover {
        background-color: #e5e7eb !important;
        color: #111827 !important;
        transform: translateY(-1px) !important;
    }
</style>
