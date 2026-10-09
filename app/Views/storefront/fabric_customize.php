<div class="bg-gray-50 min-h-screen pb-12 md:pb-16">

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
                                $rawImg = !empty($customization['images'][0]) ? get_image_url($customization['images'][0]) : '';
                                $fallbackImg = 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=800&auto=format&fit=crop&q=80';
                                $mainImg = !empty($rawImg) ? $rawImg : $fallbackImg;
                            ?>
                            <img src="<?= htmlspecialchars($mainImg) ?>" 
                                 alt="<?= htmlspecialchars($customization['fabric_name']) ?>" 
                                 onerror="this.onerror=null; this.src='<?= $fallbackImg ?>';"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
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
                                <span class="text-xs font-semibold text-gray-500">per <?= htmlspecialchars($customization['unit'] ?? 'Meter') ?> (Wholesale Rate)</span>
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

                    <!-- Size Steppers List -->
                    <div class="divide-y divide-gray-100 border border-gray-100 rounded-2xl overflow-hidden bg-gray-50/50">
                        <?php if (!empty($customization['sizes'])): ?>
                            <?php foreach ($customization['sizes'] as $sz): 
                                $baseQty = isset($sz['base_quantity']) ? (int)$sz['base_quantity'] : 2;
                                $maxQty = isset($sz['max_quantity']) && (int)$sz['max_quantity'] > 0 ? (int)$sz['max_quantity'] : 4;
                                $fabricPricePerMeter = (float)($customization['fabric_price'] ?? 0);
                                $consumptionRate = (float)($sz['fabric_consumption'] ?? 1);
                                $pricePerPiece = round($fabricPricePerMeter * $consumptionRate, 2);
                                $initialSubtotalPrice = round($pricePerPiece * $baseQty, 2);
                            ?>
                                <div class="p-4 bg-white flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-pink-50/30 transition-colors">
                                    
                                    <!-- Size Label & Quantity Limits -->
                                    <div class="flex items-center gap-3">
                                        <div class="w-11 h-11 rounded-xl bg-pink-100 text-[#F25996] font-black text-sm flex items-center justify-center border border-pink-200 flex-shrink-0">
                                            <?= htmlspecialchars($sz['size_name']) ?>
                                        </div>
                                        <div>
                                            <div class="font-bold text-sm text-gray-900 flex items-center gap-2">
                                                <span>Size <?= htmlspecialchars($sz['size_name']) ?></span>
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-pink-50 text-[#F25996] border border-pink-200" id="max_label_<?= htmlspecialchars($sz['size_name']) ?>">
                                                    Max: <?= $maxQty ?> pcs
                                                </span>
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                Default: <strong id="default_qty_val_<?= htmlspecialchars($sz['size_name']) ?>"><?= $baseQty ?> pcs</strong> • Max Allowed: <strong id="max_qty_val_<?= htmlspecialchars($sz['size_name']) ?>" class="text-[#F25996]"><?= $maxQty ?> pcs</strong>
                                            </div>
                                            <div class="text-[11px] text-green-700 font-bold mt-0.5">
                                                ₹<?= number_format($pricePerPiece, 2) ?>/pc
                                                <?php if ((float)($customization['fabric_discount_price'] ?? 0) > 0): ?>
                                                    <span class="line-through text-gray-400 font-normal ml-1">₹<?= number_format(round((float)($customization['fabric_base_price'] ?? $fabricPricePerMeter) * $consumptionRate, 2), 2) ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Interactive Counter Stepper (Pill Capsule Style Matches Image 2) -->
                                    <div class="flex items-center gap-4">
                                        <div class="inline-flex items-center border-2 border-[#F25996] rounded-full bg-white px-2 py-0.5 shadow-xs">
                                            <button type="button" onclick="adjustQty('<?= htmlspecialchars($sz['size_name']) ?>', -1)" class="w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center font-black text-lg text-[#F25996] hover:bg-pink-50 transition-colors select-none cursor-pointer">
                                                -
                                            </button>
                                            <div class="h-4 w-px bg-pink-200 mx-1"></div>
                                            <input type="number" id="qty_<?= htmlspecialchars($sz['size_name']) ?>" data-size="<?= htmlspecialchars($sz['size_name']) ?>" data-rate="<?= (float)$sz['fabric_consumption'] ?>" data-price-per-pc="<?= $pricePerPiece ?>" data-base-qty="<?= $baseQty ?>" data-base-max="<?= $maxQty ?>" data-max="<?= $maxQty ?>" value="<?= $baseQty ?>" min="0" max="<?= $maxQty ?>" oninput="validateAndRecalc(this)" class="size-qty-input w-12 h-7 sm:h-8 text-center font-black text-sm sm:text-base text-[#F25996] bg-transparent focus:outline-none">
                                            <div class="h-4 w-px bg-pink-200 mx-1"></div>
                                            <button type="button" onclick="adjustQty('<?= htmlspecialchars($sz['size_name']) ?>', 1)" class="w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center font-black text-lg text-[#F25996] hover:bg-pink-50 transition-colors select-none cursor-pointer">
                                                +
                                            </button>
                                        </div>

                                        <!-- Size Subtotal Gauge -->
                                        <div class="text-right">
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block">Pieces</span>
                                            <span class="text-xs font-bold text-gray-800" id="sub_<?= htmlspecialchars($sz['size_name']) ?>">
                                                <?= $baseQty ?> Pcs
                                            </span>
                                            <span class="text-[11px] font-bold text-green-700 block" id="price_sub_<?= htmlspecialchars($sz['size_name']) ?>">
                                                ₹<?= number_format($initialSubtotalPrice, 2) ?>
                                            </span>
                                        </div>
                                    </div>

                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="p-6 text-center text-sm text-gray-500 italic">No sizes configured for this fabric.</div>
                        <?php endif; ?>
                    </div>

                    <!-- Quick Preset Helper Buttons -->
                    <div class="flex items-center justify-between text-xs text-gray-500 pt-2 flex-wrap gap-2">
                        <span>Quick Presets:</span>
                        <div class="flex gap-2">
                            <button type="button" id="btnPresetMin" onclick="applyPreset()" class="px-3 py-1.5 bg-gray-100 hover:bg-pink-100 text-gray-700 hover:text-[#F25996] font-bold rounded-lg transition-colors cursor-pointer">
                                Minimum Order (<?= (int)$customization['minimum_pieces'] ?> Pcs)
                            </button>
                            <button type="button" onclick="resetAllQty()" class="px-3 py-1.5 bg-gray-100 hover:bg-red-50 text-gray-500 hover:text-red-600 font-bold rounded-lg transition-colors cursor-pointer">
                                Reset
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address & Custom Notes -->
                <div class="bg-white rounded-3xl border border-gray-100 p-6 md:p-8 shadow-xs space-y-5">
                    <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <span>📍 Delivery Address & Special Instructions</span>
                    </h2>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            Shipping / Delivery Destination
                        </label>
                        <?php 
                            $formatAddress = function($addr) {
                                if (empty($addr) || !is_array($addr)) return '';
                                $line1 = $addr['line1'] ?? $addr['address_line1'] ?? $addr['address'] ?? '';
                                $line2 = $addr['line2'] ?? $addr['address_line2'] ?? '';
                                $city = $addr['city'] ?? '';
                                $state = $addr['state'] ?? '';
                                $pin = $addr['postal_code'] ?? $addr['pincode'] ?? $addr['zip'] ?? '';
                                $country = $addr['country'] ?? '';
                                
                                $parts = array_filter([
                                    $line1,
                                    $line2,
                                    $city,
                                    $state ? ($pin ? "$state - $pin" : $state) : $pin,
                                    $country
                                ]);
                                return implode(', ', $parts);
                            };

                            $defaultAddressStr = !empty($userAddresses[0]) ? $formatAddress($userAddresses[0]) : '';
                        ?>
                        <?php if (!empty($userAddresses)): ?>
                            <select id="shippingAddressSelect" onchange="onAddressSelectChange(this)" class="custom-select-pink w-full">
                                <?php foreach ($userAddresses as $addr): ?>
                                    <?php 
                                        $formatted = $formatAddress($addr);
                                        $label = !empty($addr['label']) ? $addr['label'] : (!empty($addr['name']) ? $addr['name'] : 'Saved Address');
                                    ?>
                                    <option value="<?= htmlspecialchars($formatted) ?>">
                                        <?= htmlspecialchars($label . ' — ' . $formatted) ?>
                                    </option>
                                <?php endforeach; ?>
                                <option value="">-- Enter Custom / Other Delivery Address --</option>
                            </select>
                        <?php endif; ?>
                        <textarea id="shippingAddressInput" rows="2" placeholder="Enter complete factory / boutique delivery address with PIN code..." class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#F25996]/20 focus:border-[#F25996] font-medium"><?= htmlspecialchars($defaultAddressStr) ?></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            Tailoring Notes & Custom Specifications
                        </label>
                        <textarea id="orderNotesInput" rows="2" placeholder="e.g. Please add custom brand label tags, specific thread contrast, or special packing requirements..." class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#F25996]/20 focus:border-[#F25996] font-medium"></textarea>
                    </div>
                </div>

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
                            <span>Min Required: <?= (int)$customization['minimum_pieces'] ?> Pcs</span>
                            <span>Max: <?= !empty($customization['maximum_pieces']) ? (int)$customization['maximum_pieces'] . ' Pcs' : 'Unlimited' ?></span>
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

                    <!-- Pricing Breakdown -->
                    <div class="space-y-3 pt-5 pb-6 border-b border-gray-100 text-sm">
                        <div class="flex items-center justify-between text-gray-600">
                            <span>Fabric Cost (@ ₹<?= number_format($customization['fabric_price'], 2) ?>/<?= htmlspecialchars($customization['unit']) ?>):</span>
                            <span class="font-bold text-gray-900" id="summaryFabricCost">₹0.00</span>
                        </div>
                        <div class="flex items-center justify-between text-gray-600">
                            <span>Applicable GST (5%):</span>
                            <span class="font-bold text-gray-900" id="summaryGstAmount">₹0.00</span>
                        </div>
                        <div class="flex items-center justify-between text-base font-black text-gray-900 pt-2">
                            <span>Estimated Grand Total:</span>
                            <span class="text-2xl font-black text-[#F25996]" id="summaryGrandTotal">₹0.00</span>
                        </div>
                    </div>

                    <!-- Error Alert Box (if validation fails) -->
                    <div id="errorAlertBox" class="hidden p-3.5 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs font-semibold my-4"></div>

                    <!-- THE 2 ACTION PATHS AS REQUESTED -->
                    <div class="space-y-2.5 pt-1">
                        
                        <!-- OPTION 1: Request for Quote / Custom Order Request -->
                        <button type="button" id="btnSubmitRequest" onclick="submitCustomRequest()" class="w-full py-2.5 sm:py-3 px-4 bg-white hover:bg-gray-50 text-gray-800 border-1.5 border-gray-900 font-bold text-xs sm:text-sm rounded-xl shadow-2xs transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                            <span>📝 Option 1: Submit Order Request</span>
                        </button>
                        <p class="text-[10.5px] sm:text-[11px] text-gray-400 text-center">Place request for review without immediate online payment.</p>

                        <div class="relative flex py-0.5 items-center">
                            <div class="flex-grow border-t border-gray-200"></div>
                            <span class="flex-shrink mx-3 text-gray-400 text-[10px] uppercase font-bold tracking-wider">or pay directly</span>
                            <div class="flex-grow border-t border-gray-200"></div>
                        </div>

                        <!-- OPTION 2: Online Payment via Razorpay -->
                        <button type="button" id="btnPayRazorpay" onclick="payViaRazorpay()" class="w-full py-2.5 sm:py-3 px-4 bg-gradient-to-r from-[#F25996] via-[#ea3c85] to-[#db2777] hover:opacity-95 text-white font-bold text-xs sm:text-sm rounded-xl shadow-sm hover:shadow-md transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                            <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            <span>Option 2: Pay Online (Razorpay)</span>
                        </button>
                        <div class="flex items-center justify-center gap-1.5 text-[10.5px] sm:text-[11px] text-gray-400">
                            <span>🔒 Secured by 256-Bit Razorpay Payments</span>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

<!-- Razorpay Official Checkout Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
const FABRIC_ID = <?= (int)$customization['fabric_id'] ?>;
const CUSTOMIZATION_ID = <?= (int)$customization['id'] ?>;
const MIN_PIECES = <?= (int)$customization['minimum_pieces'] ?>;
const MAX_PIECES = <?= !empty($customization['maximum_pieces']) ? (int)$customization['maximum_pieces'] : 'null' ?>;
const FABRIC_STOCK = <?= (float)$customization['fabric_stock'] ?>;
const UNIT = <?= json_encode($customization['unit']) ?>;
const BASE_URL = <?= json_encode(BASE_URL) ?>;
const IS_LOGGED_IN = <?= \Core\Session::get('user_id') ? 'true' : 'false' ?>;
const FABRIC_PRICE_PER_UNIT = <?= (float)($customization['fabric_price'] ?? 0) ?>;

let currentCalc = null;

function getFabricQuantity() {
    const inp = document.getElementById('fabric_multiplier');
    return inp ? Math.max(1, parseInt(inp.value) || 1) : 1;
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

    // Update all sizes default and max limits
    document.querySelectorAll('.size-qty-input').forEach(input => {
        const baseQty = parseInt(input.getAttribute('data-base-qty')) || 0;
        const baseMax = parseInt(input.getAttribute('data-base-max')) || 9999;
        const sz = input.getAttribute('data-size');
        
        const newMax = baseMax * mult;
        const newDefault = baseQty * mult;
        
        input.setAttribute('data-max', newMax);
        input.max = newMax;
        input.value = newDefault;

        // Update labels
        const maxBadge = document.getElementById('max_label_' + sz);
        if (maxBadge) maxBadge.textContent = `Max: ${newMax} pcs`;

        const defVal = document.getElementById('default_qty_val_' + sz);
        if (defVal) defVal.textContent = `${newDefault} pcs`;

        const maxVal = document.getElementById('max_qty_val_' + sz);
        if (maxVal) maxVal.textContent = `${newMax} pcs`;
    });

    // Update preset button label & min required display
    const effectiveMin = MIN_PIECES * mult;
    const presetBtn = document.getElementById('btnPresetMin');
    if (presetBtn) {
        presetBtn.textContent = `Minimum Order (${effectiveMin} Pcs)`;
    }
    const constraintMin = document.getElementById('pieceConstraintMin');
    if (constraintMin) {
        constraintMin.textContent = `Min Required: ${effectiveMin} Pcs`;
    }
    const constraintMax = document.getElementById('pieceConstraintMax');
    if (constraintMax && MAX_PIECES !== null) {
        constraintMax.textContent = `Max: ${MAX_PIECES * mult} Pcs`;
    }

    triggerRecalc();
}

function getSelectedSizes() {
    const inputs = document.querySelectorAll('.size-qty-input');
    const sizes = {};
    inputs.forEach(input => {
        const sz = input.getAttribute('data-size');
        const qty = parseInt(input.value) || 0;
        if (qty > 0) {
            sizes[sz] = qty;
        }
    });
    return sizes;
}

function onAddressSelectChange(sel) {
    const input = document.getElementById('shippingAddressInput');
    if (input) {
        input.value = sel.value;
        if (!sel.value) {
            input.focus();
        }
    }
}

function adjustQty(sizeName, delta) {
    const input = document.getElementById('qty_' + sizeName);
    if (!input) return;
    const max = parseInt(input.getAttribute('data-max')) || 9999;
    let val = (parseInt(input.value) || 0) + delta;
    if (val < 0) val = 0;
    if (val > max) {
        val = max;
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'info',
                title: 'Max Limit Reached',
                text: `Maximum allowed pieces for size ${sizeName} is ${max} pcs.`,
                confirmButtonColor: '#F25996',
                timer: 2000
            });
        }
    }
    input.value = val;
    triggerRecalc();
}

function validateAndRecalc(input) {
    const sizeName = input.getAttribute('data-size');
    const max = parseInt(input.getAttribute('data-max')) || 9999;
    let val = parseInt(input.value) || 0;
    if (val < 0) {
        val = 0;
        input.value = 0;
    }
    if (val > max) {
        val = max;
        input.value = max;
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'info',
                title: 'Max Limit Reached',
                text: `Maximum allowed pieces for size ${sizeName} is ${max} pcs.`,
                confirmButtonColor: '#F25996',
                timer: 2000
            });
        }
    }
    triggerRecalc();
}

function applyPreset(minQty) {
    const inputs = document.querySelectorAll('.size-qty-input');
    if (inputs.length === 0) return;
    
    const mult = getFabricQuantity();
    const effectiveMin = (minQty !== undefined ? minQty : MIN_PIECES) * mult;
    const perSize = Math.max(1, Math.floor(effectiveMin / inputs.length));
    
    inputs.forEach((inp) => {
        const max = parseInt(inp.getAttribute('data-max')) || 9999;
        inp.value = Math.min(max, perSize);
    });
    triggerRecalc();
}

function resetAllQty() {
    document.querySelectorAll('.size-qty-input').forEach(inp => inp.value = 0);
    triggerRecalc();
}

function triggerRecalc() {
    const inputs = document.querySelectorAll('.size-qty-input');
    const sizes = {};
    const fabricQuantity = getFabricQuantity();
    
    inputs.forEach(input => {
        const sz = input.getAttribute('data-size');
        const rate = parseFloat(input.getAttribute('data-rate')) || 0;
        const qty = parseInt(input.value) || 0;
        sizes[sz] = qty;
        
        // Update pieces count
        const subElem = document.getElementById('sub_' + sz);
        if (subElem) {
            subElem.textContent = qty > 0 ? `${qty} Pcs` : '0 Pcs';
        }
        // Update ₹ price subtotal per size
        const pricePerPc = parseFloat(input.getAttribute('data-price-per-pc')) || (rate * FABRIC_PRICE_PER_UNIT);
        const priceSub = document.getElementById('price_sub_' + sz);
        if (priceSub) {
            const total = pricePerPc * qty;
            priceSub.textContent = total > 0 ? `₹${total.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}` : '₹0.00';
        }
    });

    // Call calculation API
    fetch(`${BASE_URL}/fabrics/customization/calculate`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            fabric_id: FABRIC_ID,
            customization_id: CUSTOMIZATION_ID,
            fabric_quantity: fabricQuantity,
            sizes: sizes
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
    document.getElementById('summaryTotalPieces').textContent = `${c.total_pieces} Pcs`;
    document.getElementById('summaryBaseConsumption').textContent = `${c.base_consumption} ${c.unit}`;
    document.getElementById('summaryWastageAmount').textContent = `+${c.wastage_amount} ${c.unit}`;
    document.getElementById('summaryRequiredFabric').textContent = `${c.required_fabric} ${c.unit}`;
    document.getElementById('summaryFabricCost').textContent = `₹${p.fabric_cost.toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
    document.getElementById('summaryGstAmount').textContent = `₹${p.gst_amount.toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
    document.getElementById('summaryGrandTotal').textContent = `₹${p.total.toLocaleString('en-IN', {minimumFractionDigits: 2})}`;

    const isOverStock = (c.required_fabric > FABRIC_STOCK) || !c.is_stock_sufficient;
    const reqFabricContainer = document.getElementById('summaryRequiredFabric').parentElement;
    const statusBadge = document.getElementById('liveStatusBadge');
    const btnRequest = document.getElementById('btnSubmitRequest');
    const btnPay = document.getElementById('btnPayRazorpay');
    const errorBox = document.getElementById('errorAlertBox');

    const effectiveMin = c.minimum_pieces || (MIN_PIECES * getFabricQuantity());

    // Progress bar for minimum pieces
    const bar = document.getElementById('pieceProgressBar');
    const pct = effectiveMin > 0 ? Math.min(100, Math.round((c.total_pieces / effectiveMin) * 100)) : 100;
    bar.style.width = pct + '%';

    if (isOverStock) {
        // Exceeded Fabric stock
        bar.className = 'h-full bg-rose-600 transition-all duration-300';
        reqFabricContainer.className = 'flex items-center justify-between font-bold bg-rose-50 p-2.5 rounded-xl border border-rose-200 text-rose-900';
        statusBadge.className = 'text-[10px] sm:text-[11px] font-bold px-2 py-0.5 rounded-full bg-rose-100 text-rose-800 border border-rose-300 shrink-0 whitespace-nowrap';
        statusBadge.textContent = '❌ Exceeds Stock';

        const shortage = (c.required_fabric - FABRIC_STOCK).toFixed(2);
        errorBox.innerHTML = `
            <div class="flex items-start gap-2">
                <span class="text-base">⚠️</span>
                <div>
                    <strong class="font-bold block mb-1">Fabric Limit Exceeded by ${shortage} ${c.unit}!</strong>
                    <span>Your selected size distribution requires <strong>${c.required_fabric} ${c.unit}</strong>, but only <strong>${FABRIC_STOCK} ${c.unit}</strong> are available in stock. Larger sizes (e.g. 3XL, 4XL) consume more fabric per piece. Please reduce quantities to continue.</span>
                </div>
            </div>
        `;
        errorBox.classList.remove('hidden');

        // Disable Action Buttons
        btnRequest.disabled = true;
        btnRequest.classList.add('opacity-40', 'cursor-not-allowed');
        btnRequest.classList.remove('hover:bg-gray-50', 'cursor-pointer');

        btnPay.disabled = true;
        btnPay.classList.add('opacity-40', 'cursor-not-allowed');
        btnPay.classList.remove('hover:shadow-xl', 'cursor-pointer');

    } else if (c.total_pieces < effectiveMin && c.total_pieces > 0) {
        // Under minimum pieces
        bar.className = 'h-full bg-[#F25996] transition-all duration-300';
        reqFabricContainer.className = 'flex items-center justify-between font-bold bg-pink-50/60 p-2.5 rounded-xl border border-pink-100 text-[#db2777]';
        statusBadge.className = 'text-[10px] sm:text-[11px] font-bold px-2 py-0.5 rounded-full bg-pink-100 text-[#F25996] border border-pink-300 shrink-0 whitespace-nowrap';
        statusBadge.textContent = '⚠️ Min Not Met';

        errorBox.innerHTML = `⚠️ Minimum order is <strong>${effectiveMin} pieces</strong>. You currently have ${c.total_pieces} pieces.`;
        errorBox.classList.remove('hidden');

        btnRequest.disabled = true;
        btnRequest.classList.add('opacity-40', 'cursor-not-allowed');
        btnPay.disabled = true;
        btnPay.classList.add('opacity-40', 'cursor-not-allowed');

    } else if (c.is_valid && c.total_pieces > 0) {
        // Valid & Ready
        bar.className = 'h-full bg-emerald-500 transition-all duration-300';
        reqFabricContainer.className = 'flex items-center justify-between font-bold bg-emerald-50 p-2.5 rounded-xl border border-emerald-200 text-emerald-950';
        statusBadge.className = 'text-[10px] sm:text-[11px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-300 shrink-0 whitespace-nowrap';
        statusBadge.textContent = '✅ Ready to Order';

        errorBox.classList.add('hidden');

        // Enable Action Buttons
        btnRequest.disabled = false;
        btnRequest.classList.remove('opacity-40', 'cursor-not-allowed');
        btnRequest.classList.add('hover:bg-gray-50', 'cursor-pointer');

        btnPay.disabled = false;
        btnPay.classList.remove('opacity-40', 'cursor-not-allowed');
        btnPay.classList.add('hover:shadow-xl', 'cursor-pointer');

    } else {
        // Default / Empty
        bar.className = 'h-full bg-gray-300 transition-all duration-300';
        reqFabricContainer.className = 'flex items-center justify-between font-bold bg-gray-50 p-2.5 rounded-xl border border-gray-100 text-gray-800';
        statusBadge.className = 'text-[10px] sm:text-[11px] font-bold px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 border border-gray-200 shrink-0 whitespace-nowrap';
        statusBadge.textContent = 'Select Sizes';

        errorBox.classList.add('hidden');
        btnRequest.disabled = true;
        btnRequest.classList.add('opacity-40', 'cursor-not-allowed');
        btnPay.disabled = true;
        btnPay.classList.add('opacity-40', 'cursor-not-allowed');
    }
}

function checkLoginOrPrompt() {
    if (!IS_LOGGED_IN) {
        Swal.fire({
            title: 'Please Login',
            text: 'You need to be logged in to place wholesale custom orders and requests.',
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
    const sizes = getSelectedSizes();
    const fabricQuantity = getFabricQuantity();
    const address = document.getElementById('shippingAddressInput').value.trim();
    const notes = document.getElementById('orderNotesInput').value.trim();

    return {
        fabric_id: FABRIC_ID,
        customization_id: CUSTOMIZATION_ID,
        fabric_quantity: fabricQuantity,
        sizes: sizes,
        shipping_address: address,
        notes: notes
    };
}

// -------------------------------------------------------------
// OPTION 1: Submit Custom Order Request (Quote)
// -------------------------------------------------------------
function submitCustomRequest() {
    if (!checkLoginOrPrompt()) return;

    const payload = getPayload();
    if (Object.keys(payload.sizes).length === 0) {
        Swal.fire({ title: 'Select Sizes', text: 'Please add quantities for at least one size.', icon: 'warning', confirmButtonColor: '#F25996' });
        return;
    }

    if (!currentCalc || !currentCalc.consumption.is_valid || currentCalc.consumption.required_fabric > FABRIC_STOCK) {
        let msg = 'Please satisfy minimum piece requirements.';
        if (currentCalc && (currentCalc.consumption.required_fabric > FABRIC_STOCK || !currentCalc.consumption.is_stock_sufficient)) {
            const shortage = (currentCalc.consumption.required_fabric - FABRIC_STOCK).toFixed(2);
            msg = `<strong>Fabric Inventory Shortage:</strong> Your selection requires <strong>${currentCalc.consumption.required_fabric} ${UNIT}</strong>, but only <strong>${FABRIC_STOCK} ${UNIT}</strong> are in stock (Shortage: ${shortage} ${UNIT}).<br><br><span class="text-[#F25996]">Larger sizes (e.g. 3XL, 4XL) take more fabric per piece. Please reduce your piece quantities to proceed.</span>`;
        } else if (currentCalc && currentCalc.consumption.errors) {
            msg = currentCalc.consumption.errors.join('<br>');
        }
        Swal.fire({ title: 'Validation Warning', html: msg, icon: 'warning', confirmButtonColor: '#F25996' });
        return;
    }

    Swal.fire({
        title: 'Submit Custom Order Request?',
        html: `You are submitting a request for <strong>${currentCalc.consumption.total_pieces} pieces</strong> (${currentCalc.consumption.required_fabric} ${UNIT} of fabric).<br><br>Estimated Value: <strong>₹${currentCalc.pricing.total.toLocaleString('en-IN', {minimumFractionDigits: 2})}</strong>.<br><br>Our tailoring team will review and approve your request.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#F25996',
        cancelButtonColor: '#9CA3AF',
        confirmButtonText: 'Yes, Submit Request',
        cancelButtonText: 'Back'
    }).then(res => {
        if (res.isConfirmed) {
            Swal.fire({ title: 'Submitting Request...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            fetch(`${BASE_URL}/fabrics/customization/request-order`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Request Submitted! 🎉',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#F25996',
                        confirmButtonText: 'View My Orders'
                    }).then(() => {
                        window.location.href = data.redirect_url || `${BASE_URL}/dashboard/orders`;
                    });
                } else {
                    Swal.fire({ title: 'Submission Failed', text: data.message || 'Error submitting request', icon: 'error', confirmButtonColor: '#F25996' });
                }
            })
            .catch(err => {
                Swal.fire({ title: 'Network Error', text: 'Unable to communicate with server.', icon: 'error', confirmButtonColor: '#F25996' });
            });
        }
    });
}

// -------------------------------------------------------------
// OPTION 2: Online Payment via Razorpay
// -------------------------------------------------------------
function payViaRazorpay() {
    if (!checkLoginOrPrompt()) return;

    const payload = getPayload();
    if (Object.keys(payload.sizes).length === 0) {
        Swal.fire({ title: 'Select Sizes', text: 'Please add quantities for at least one size.', icon: 'warning', confirmButtonColor: '#F25996' });
        return;
    }

    if (!currentCalc || !currentCalc.consumption.is_valid || currentCalc.consumption.required_fabric > FABRIC_STOCK) {
        let msg = 'Please satisfy minimum piece requirements.';
        if (currentCalc && (currentCalc.consumption.required_fabric > FABRIC_STOCK || !currentCalc.consumption.is_stock_sufficient)) {
            const shortage = (currentCalc.consumption.required_fabric - FABRIC_STOCK).toFixed(2);
            msg = `<strong>Fabric Inventory Shortage:</strong> Your selection requires <strong>${currentCalc.consumption.required_fabric} ${UNIT}</strong>, but only <strong>${FABRIC_STOCK} ${UNIT}</strong> are in stock (Shortage: ${shortage} ${UNIT}).<br><br><span class="text-[#F25996]">Larger sizes (e.g. 3XL, 4XL) take more fabric per piece. Please reduce your piece quantities to proceed.</span>`;
        } else if (currentCalc && currentCalc.consumption.errors) {
            msg = currentCalc.consumption.errors.join('<br>');
        }
        Swal.fire({ title: 'Validation Warning', html: msg, icon: 'warning', confirmButtonColor: '#F25996' });
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
    triggerRecalc();
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
