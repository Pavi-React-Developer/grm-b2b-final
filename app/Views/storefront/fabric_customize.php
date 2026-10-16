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

        <?php 
            $rawImages = !empty($customization['images']) && is_array($customization['images']) ? array_values(array_filter($customization['images'])) : [];
            $fallbackImg = 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=800&auto=format&fit=crop&q=80';
            $galleryList = !empty($rawImages) ? array_slice($rawImages, 0, 6) : [$fallbackImg];
            $mainImgUrl = get_image_url($galleryList[0]);

            $baseP = (float)($customization['fabric_base_price'] ?? 0);
            $discP = (float)($customization['fabric_discount_price'] ?? 0);
            if ($discP <= 0 && !empty($customization['fabric_price'])) {
                $discP = (float)$customization['fabric_price'];
            }
            $discPercent = ($baseP > 0 && $discP > 0 && $discP < $baseP) 
                ? round((($baseP - $discP) / $baseP) * 100) 
                : 0;

            $isWishlisted = !empty($wishlistIds) && in_array((int)$customization['fabric_id'], $wishlistIds);
        ?>

        <!-- ── Top Fabric Product Showcase Hero (Matches Screenshot Layout) ── -->
        <div class="bg-white rounded-3xl border border-gray-100 p-5 sm:p-7 lg:p-8 shadow-xs mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
                
                <!-- Left Column: Image Gallery (Vertical Thumbnails on Left + 3:4 Main Image on Right) -->
                <div class="lg:col-span-6 xl:col-span-6 w-full space-y-6">
                    <div class="flex flex-col-reverse md:flex-row gap-3 sm:gap-4 items-start w-full">
                        
                        <!-- Left Vertical Thumbnails Strip -->
                        <?php if (count($galleryList) > 1): ?>
                        <div id="fabricThumbnails" class="flex md:flex-col gap-2.5 sm:gap-3 overflow-x-auto md:overflow-y-auto max-h-[660px] xl:max-h-[720px] w-full md:w-20 shrink-0 no-scrollbar order-2 md:order-1 pb-1 md:pb-0">
                            <?php foreach ($galleryList as $idx => $gImg): 
                                $tUrl = get_image_url($gImg, 200, 200);
                            ?>
                            <button type="button" 
                                    onclick="setFabricActiveImage(<?= $idx ?>)"
                                    onmouseenter="setFabricActiveImage(<?= $idx ?>)"
                                    id="fabric_thumb_btn_<?= $idx ?>"
                                    class="fabric-thumb-btn w-16 h-16 sm:w-18 sm:h-18 md:w-20 md:h-20 aspect-square rounded-2xl bg-white overflow-hidden border-2 transition-all cursor-pointer shrink-0 <?= $idx === 0 ? 'border-[#F25996] ring-2 ring-[#F25996]/30 shadow-xs' : 'border-gray-200 hover:border-[#F25996]/50 opacity-80 hover:opacity-100' ?>">
                                <img src="<?= htmlspecialchars($tUrl) ?>" 
                                     alt="" 
                                     onerror="this.src='<?= $fallbackImg ?>';" 
                                     class="w-full h-full object-cover">
                            </button>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                        <!-- Main Showcase Image Frame (3:4 aspect ratio) -->
                        <div class="relative flex-1 w-full bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm group cursor-pointer order-1 md:order-2" onclick="openFabricModal(fabricActiveImgIndex)" title="Click to view full image">
                            
                            <!-- Top-Left Scallop Starburst Discount Rosette Badge (Aqua Blue) -->
                            <?php if ($discPercent > 0 && function_exists('render_discount_starburst')): ?>
                            <div class="absolute top-3.5 left-3.5 z-20 w-16 h-16 sm:w-20 sm:h-20 drop-shadow-md pointer-events-none transition-transform duration-300 group-hover:scale-105">
                                <?= render_discount_starburst($discPercent, 'w-full h-full') ?>
                            </div>
                            <?php else: ?>
                            <span class="absolute top-3.5 left-3.5 z-20 px-3 py-1 bg-[#F25996] text-white font-black text-[10px] uppercase tracking-wider rounded-lg shadow-sm">
                                ✂️ Custom Ready
                            </span>
                            <?php endif; ?>

                            <!-- Bottom-Right Click to Zoom Pill -->
                            <div class="absolute bottom-3.5 right-3.5 z-20 bg-black/60 text-white text-xs px-3.5 py-1.5 rounded-full backdrop-blur-sm flex items-center gap-1.5 shadow-md pointer-events-none select-none transition-all duration-200 group-hover:bg-black/80">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                <span>Click to zoom</span>
                            </div>

                            <!-- 3:4 Aspect Ratio Portrait Container -->
                            <div class="w-full overflow-hidden flex items-center justify-center bg-gray-50/40 rounded-3xl" style="aspect-ratio: 3 / 4; min-height: 540px; max-height: 720px; width: 100%;">
                                <img id="fabricMainImage" 
                                     src="<?= htmlspecialchars($mainImgUrl) ?>" 
                                     alt="<?= htmlspecialchars($customization['fabric_name']) ?>" 
                                     onerror="this.onerror=null; this.src='<?= $fallbackImg ?>';"
                                     class="w-full h-full object-cover transition-all duration-300 group-hover:scale-105"
                                     style="width: 100%; height: 100%; object-fit: cover; display: block;">
                            </div>
                        </div>

                    </div>

                    <!-- Fabric Description & Details Card (Admin Added Description) -->
                    <div class="bg-gradient-to-br from-pink-50/30 via-white to-gray-50/50 rounded-3xl p-5 sm:p-6 border border-gray-200/90 shadow-2xs space-y-4">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <h3 class="text-xs sm:text-sm font-black text-gray-900 uppercase tracking-wider flex items-center gap-2">
                                <span>📄 Fabric Description &amp; Details</span>
                            </h3>
                            <span class="text-[10px] font-black px-2.5 py-0.5 rounded-full bg-pink-50 text-[#F25996] border border-pink-200">
                                Fabric Details
                            </span>
                        </div>

                        <!-- Admin Added Description -->
                        <div class="text-xs sm:text-sm text-gray-700 leading-relaxed">
                            <?php if (!empty($customization['fabric_description'])): ?>
                                <div class="text-gray-700 leading-relaxed whitespace-pre-line bg-white p-4 rounded-2xl border border-gray-100 shadow-2xs text-xs sm:text-sm">
                                    <?= nl2br(htmlspecialchars(trim($customization['fabric_description']))) ?>
                                </div>
                            <?php else: ?>
                                <p class="text-xs text-gray-400 italic bg-white p-4 rounded-2xl border border-gray-100">No description provided for this fabric.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Product Details & Step 1 Sizing Matrix -->
                <div class="lg:col-span-6 xl:col-span-6 flex flex-col justify-start space-y-4">
                    
                    <!-- Category / Program Pill Badge -->
                    <div class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-pink-50 text-[#F25996] text-xs font-bold border border-pink-100 self-start">
                        <span><?= htmlspecialchars($customization['fabric_name']) ?> | Customization</span>
                        <span>•</span>
                        <span><?= htmlspecialchars($customization['garment_type'] ?? 'Custom Garment') ?></span>
                    </div>

                    <!-- Title -->
                    <h1 class="text-2xl sm:text-3xl font-black text-gray-900 font-display tracking-tight leading-tight">
                        <?= htmlspecialchars($customization['fabric_name']) ?>
                    </h1>

                    <!-- Meta: SKU -->
                    <div class="flex items-center gap-3 text-xs text-gray-500 flex-wrap">
                        <span>SKU: <strong class="text-gray-800"><?= htmlspecialchars($customization['fabric_sku'] ?? 'N/A') ?></strong></span>
                    </div>

                    <!-- Star Rating Row -->
                    <?php
                        $avgCustomRating = !empty($customizationStats['avg_rating']) ? (float)$customizationStats['avg_rating'] : 0.0;
                        $totalCustomRevs = !empty($customizationStats['total_reviews']) ? (int)$customizationStats['total_reviews'] : 0;
                    ?>
                    <div class="flex items-center gap-2">
                        <div class="flex">
                            <?php for($i=1; $i<=5; $i++): ?>
                            <svg class="w-4 h-4 <?= ($totalCustomRevs > 0 && $i <= round($avgCustomRating)) ? 'text-amber-400 fill-current' : 'text-gray-200 fill-current' ?>" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <?php endfor; ?>
                        </div>
                        <?php if ($totalCustomRevs > 0): ?>
                            <span class="text-xs font-bold text-gray-800"><?= number_format($avgCustomRating, 1) ?></span>
                            <a href="#customReviewsSection" class="text-xs text-[#F25996] hover:underline font-semibold">
                                (<?= $totalCustomRevs ?> <?= $totalCustomRevs === 1 ? 'Review' : 'Reviews' ?>)
                            </a>
                        <?php else: ?>
                            <span class="text-xs font-semibold text-gray-400">Not rated yet</span>
                            <a href="#customReviewsSection" class="text-xs text-[#F25996] hover:underline font-semibold">
                                (No reviews yet)
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Available Stock (in Emerald Green bold) -->
                    <div class="text-sm font-bold text-emerald-600 flex items-center gap-1.5">
                        <span>Available Stock:</span>
                        <span class="text-emerald-700 font-extrabold"><?= number_format((int)$customization['fabric_stock']) ?> Qty</span>
                    </div>

                    <!-- Price Block -->
                    <div class="bg-gradient-to-r from-pink-50/70 via-rose-50/30 to-white p-4 sm:p-5 rounded-2xl border border-pink-100 flex items-baseline gap-3 flex-wrap">
                        <span class="text-3xl sm:text-4xl font-black text-gray-900">
                            ₹<?= number_format($discP, 2) ?>
                        </span>
                        <?php if ($discPercent > 0): ?>
                        <span class="text-base font-semibold text-gray-400 line-through">
                            ₹<?= number_format($baseP, 2) ?>
                        </span>
                        <span class="text-xs font-bold px-2.5 py-0.5 rounded-md bg-rose-50 text-rose-600 border border-rose-200">
                            <?= $discPercent ?>% OFF
                        </span>
                        <?php endif; ?>
                        <span class="text-xs font-semibold text-gray-500">per <?= htmlspecialchars($customization['unit'] ?? 'Quantity') ?> (Wholesale Rate)</span>
                    </div>

                    <!-- Step 1: Custom Stitching & Sizing Workshop -->
                    <div class="pt-4 border-t border-gray-100 space-y-5">
                        <div>
                            <div class="flex items-center justify-between">
                                <h3 class="text-base sm:text-lg font-bold text-gray-900 font-display flex items-center gap-2">
                                    <span>📏 Select Sizes & Stitch Pieces</span>
                                </h3>
                                <span class="text-xs font-semibold px-2.5 py-1 bg-pink-50 text-[#F25996] rounded-full border border-pink-200">
                                    Step 1 of 2
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                Choose how many pieces you would like to stitch for each available size. Fabric consumption is automatically computed per piece based on admin engineering rules.
                            </p>
                        </div>

                        <!-- Fabric Quantity (Multiplier) Section -->
                        <div class="bg-gradient-to-r from-pink-50/80 via-rose-50/50 to-pink-50/80 p-3.5 sm:p-4 rounded-2xl border border-pink-200 flex flex-col sm:flex-row items-center justify-between gap-3 sm:gap-4 shadow-2xs">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-[#F25996] text-white font-black text-lg flex items-center justify-center shadow-xs shrink-0">
                                    📦
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm flex items-center gap-2 flex-wrap">
                                        <span>Fabric Quantity / Sets</span>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-pink-100 text-[#db2777] border border-pink-300/70" id="fabricQtyBadge">
                                            1 Set (1x)
                                        </span>
                                        <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-md bg-white text-[#F25996] border border-pink-300 shadow-2xs" id="fabricRequiredBadge">
                                            Required: <strong id="fabricRequiredPcs"><?= (int)$customization['minimum_pieces'] ?> Pcs</strong> (Max: <strong id="fabricMaxPcs"><?= (int)($customization['maximum_pieces'] ?? ($customization['minimum_pieces'] * 20)) ?> Pcs</strong>)
                                        </span>
                                    </h4>
                                    <p class="text-[11px] text-gray-500">Limits scale automatically for the chosen quantity.</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 shrink-0">
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

                        <!-- Interactive Size Variant Buttons Grid -->
                        <div>
                            <div class="flex items-center justify-between mb-2.5">
                                <label class="text-xs font-bold uppercase tracking-wider text-gray-700 flex items-center gap-1.5">
                                    <span>1. Choose Size Variants:</span>
                                    <span class="text-[10px] px-2 py-0.5 rounded-md bg-pink-50 text-[#F25996] font-bold border border-pink-200">Click to Select</span>
                                </label>
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="selectAllVariants()" class="text-xs font-bold text-[#F25996] hover:underline cursor-pointer">Select All</button>
                                    <span class="text-gray-300">•</span>
                                    <button type="button" onclick="clearAllVariants()" class="text-xs font-bold text-gray-500 hover:text-red-500 cursor-pointer">Clear</button>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-3 gap-3" id="variantButtonsGrid">
                                <?php if (!empty($customization['sizes'])): ?>
                                    <?php foreach ($customization['sizes'] as $sz): 
                                        $baseQty = isset($sz['base_quantity']) ? (int)$sz['base_quantity'] : 2;
                                        $maxQty = isset($sz['max_quantity']) && (int)$sz['max_quantity'] > 0 ? (int)$sz['max_quantity'] : 4;
                                        $fabricPricePerMeter = (float)($customization['fabric_price'] ?? 0);
                                        $consumptionRate = (float)($sz['fabric_consumption'] ?? 1);
                                        $pricePerPiece = round($fabricPricePerMeter * $consumptionRate, 2);
                                    ?>
                                        <button type="button" 
                                                id="btn_size_<?= htmlspecialchars($sz['size_name']) ?>"
                                                onclick="toggleSizeVariant('<?= htmlspecialchars($sz['size_name']) ?>')"
                                                data-size="<?= htmlspecialchars($sz['size_name']) ?>"
                                                data-base-qty="<?= $baseQty ?>"
                                                data-base-max="<?= $maxQty ?>"
                                                data-price-per-pc="<?= $pricePerPiece ?>"
                                                data-consumption="<?= $consumptionRate ?>"
                                                class="variant-btn relative p-3 rounded-2xl border-2 transition-all text-left flex flex-col justify-between group cursor-pointer border-gray-200 bg-white hover:border-pink-300 hover:shadow-xs">
                                            
                                            <!-- Top: Size Badge & Check Icon -->
                                            <div class="flex items-center justify-between mb-1.5">
                                                <span class="w-7 h-7 rounded-xl bg-pink-50 text-[#F25996] font-black text-xs flex items-center justify-center border border-pink-200 group-hover:scale-105 transition-transform">
                                                    <?= htmlspecialchars($sz['size_name']) ?>
                                                </span>
                                                <span class="check-icon w-4.5 h-4.5 rounded-full border border-gray-300 flex items-center justify-center text-[9px] text-transparent group-hover:border-pink-400 font-bold transition-all">
                                                    ✓
                                                </span>
                                            </div>

                                            <!-- Price Per Piece -->
                                            <div class="text-xs font-bold text-gray-900 mb-0.5">
                                                ₹<?= number_format($pricePerPiece, 2) ?><span class="text-[10px] text-gray-500 font-normal">/pc</span>
                                            </div>

                                            <!-- Base & Max Info -->
                                            <div class="text-[10px] text-gray-500 space-y-0.5 border-t border-gray-100 pt-1 mt-1">
                                                <div class="flex items-center justify-between">
                                                    <span>Required:</span>
                                                    <strong class="text-[#F25996] font-bold" id="btn_range_label_<?= htmlspecialchars($sz['size_name']) ?>"><?= $baseQty ?> – <?= $maxQty ?> pcs</strong>
                                                </div>
                                            </div>
                                        </button>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Active Size Quantity Adjusters Section -->
                        <div id="activeSizesSection" class="space-y-2.5 pt-2 border-t border-gray-100">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 text-xs font-bold text-gray-700">
                                <span class="uppercase tracking-wider">2. Selected Size Quantities:</span>
                                <span class="text-[#F25996] font-medium text-[11px]" id="activeVariantNotice"></span>
                            </div>

                            <div class="divide-y divide-gray-100 border border-gray-100 rounded-2xl overflow-hidden bg-gray-50/50" id="selectedSizesSteppersList">
                                <!-- Empty State Placeholder when no variant selected -->
                                <div id="emptyVariantsPlaceholder" class="p-5 sm:p-6 text-center bg-white flex flex-col items-center justify-center gap-2">
                                    <div class="w-9 h-9 rounded-full bg-pink-50 text-[#F25996] flex items-center justify-center text-base">
                                        👆
                                    </div>
                                    <p class="text-xs font-bold text-gray-700">No Size Variants Selected</p>
                                    <p class="text-[11px] text-gray-500 max-w-sm">Click on any size variant buttons above to choose the sizes and pieces you want to customize.</p>
                                </div>

                                <?php if (!empty($customization['sizes'])): ?>
                                    <?php foreach ($customization['sizes'] as $sz): 
                                        $baseQty = isset($sz['base_quantity']) ? (int)$sz['base_quantity'] : 2;
                                        $maxQty = isset($sz['max_quantity']) && (int)$sz['max_quantity'] > 0 ? (int)$sz['max_quantity'] : 4;
                                        $fabricPricePerMeter = (float)($customization['fabric_price'] ?? 0);
                                        $consumptionRate = (float)($sz['fabric_consumption'] ?? 1);
                                        $pricePerPiece = round($fabricPricePerMeter * $consumptionRate, 2);
                                    ?>
                                        <div id="row_size_<?= htmlspecialchars($sz['size_name']) ?>" class="size-row p-3.5 bg-white flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-pink-50/30 transition-colors hidden">
                                            
                                            <!-- Size Info -->
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-xl bg-pink-100 text-[#F25996] font-black text-xs flex items-center justify-center border border-pink-200 shrink-0">
                                                    <?= htmlspecialchars($sz['size_name']) ?>
                                                </div>
                                                <div>
                                                    <div class="font-bold text-xs sm:text-sm text-gray-900 flex items-center gap-2 flex-wrap">
                                                        <span>Size <?= htmlspecialchars($sz['size_name']) ?></span>
                                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-pink-100 text-[#db2777] border border-pink-200" id="range_label_<?= htmlspecialchars($sz['size_name']) ?>">
                                                            Required: <?= $baseQty ?> pcs (Max: <?= $maxQty ?> pcs)
                                                        </span>
                                                    </div>
                                                    <div class="text-[11px] text-gray-500 mt-0.5">
                                                        Rate: <strong class="text-green-700">₹<?= number_format($pricePerPiece, 2) ?>/pc</strong>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Stepper & Subtotal & Delete Action -->
                                            <div class="flex items-center gap-2.5 sm:gap-3">
                                                <div class="inline-flex items-center border-2 border-[#F25996] rounded-full bg-white px-1.5 py-0.5 shadow-xs">
                                                    <button type="button" onclick="adjustQty('<?= htmlspecialchars($sz['size_name']) ?>', -1)" class="w-6 h-6 sm:w-7 sm:h-7 rounded-full flex items-center justify-center font-black text-base text-[#F25996] hover:bg-pink-50 transition-colors select-none cursor-pointer">
                                                        -
                                                    </button>
                                                    <div class="h-3.5 w-px bg-pink-200 mx-1"></div>
                                                    <input type="number" id="qty_<?= htmlspecialchars($sz['size_name']) ?>" data-size="<?= htmlspecialchars($sz['size_name']) ?>" data-rate="<?= (float)$sz['fabric_consumption'] ?>" data-price-per-pc="<?= $pricePerPiece ?>" data-base-qty="<?= $baseQty ?>" data-base-max="<?= $maxQty ?>" data-min="<?= $baseQty ?>" data-max="<?= $maxQty ?>" value="0" min="<?= $baseQty ?>" max="<?= $maxQty ?>" oninput="validateAndRecalc(this)" class="size-qty-input w-10 sm:w-11 h-6 sm:h-7 text-center font-black text-xs sm:text-sm text-[#F25996] bg-transparent focus:outline-none">
                                                    <div class="h-3.5 w-px bg-pink-200 mx-1"></div>
                                                    <button type="button" onclick="adjustQty('<?= htmlspecialchars($sz['size_name']) ?>', 1)" class="w-6 h-6 sm:w-7 sm:h-7 rounded-full flex items-center justify-center font-black text-base text-[#F25996] hover:bg-pink-50 transition-colors select-none cursor-pointer">
                                                        +
                                                    </button>
                                                </div>

                                                <!-- Size Subtotal Gauge -->
                                                <div class="text-right min-w-[65px]">
                                                    <span class="text-[9px] font-bold uppercase tracking-wider text-gray-400 block">Subtotal</span>
                                                    <span class="text-xs font-bold text-gray-800" id="sub_<?= htmlspecialchars($sz['size_name']) ?>">
                                                         0 Pcs
                                                    </span>
                                                    <span class="text-[10px] font-bold text-green-700 block" id="price_sub_<?= htmlspecialchars($sz['size_name']) ?>">
                                                        ₹0.00
                                                    </span>
                                                </div>

                                                <!-- Delete / Remove Size Button -->
                                                <button type="button" 
                                                        onclick="removeSizeVariant('<?= htmlspecialchars($sz['size_name']) ?>')" 
                                                        title="Remove size <?= htmlspecialchars($sz['size_name']) ?>"
                                                        class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-gray-50 hover:bg-rose-50 text-gray-400 hover:text-rose-600 flex items-center justify-center transition-all cursor-pointer border border-gray-200 hover:border-rose-200 group/del shrink-0">
                                                    <svg class="w-3.5 h-3.5 transition-transform group-hover/del:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>

                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="p-6 text-center text-sm text-gray-500 italic">No sizes configured for this fabric.</div>
                                <?php endif; ?>
                            </div>
                        </div>

                    <!-- Packaging Cover Option Selection -->
                    <div class="mt-5 bg-gradient-to-r from-pink-50/60 via-purple-50/30 to-pink-50/60 p-4 rounded-2xl border border-pink-100 space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold uppercase tracking-wider text-gray-800 flex items-center gap-1.5">
                                <span>Packaging Type:</span>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-pink-100 text-[#F25996]">Choose Cover</span>
                            </label>
                            <?php if ((float)($customization['without_cover_discount_percentage'] ?? 0) > 0): ?>
                                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                    Save <?= number_format($customization['without_cover_discount_percentage'], 1) ?>% without cover
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="grid grid-cols-2 gap-2.5">
                            <!-- With Cover Option -->
                            <button type="button" 
                                    id="optWithCover" 
                                    onclick="selectPackagingOption('with_cover')"
                                    class="packaging-opt-btn p-3 rounded-xl border-2 text-left transition-all cursor-pointer border-[#F25996] bg-white ring-2 ring-[#F25996]/20 shadow-xs">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-black text-gray-900 flex items-center gap-1">
                                        <span>🛍️ With Cover</span>
                                    </span>
                                    <span class="pkg-check w-4 h-4 rounded-full bg-[#F25996] text-white flex items-center justify-center text-[10px] font-bold">✓</span>
                                </div>
                                <p class="text-[10px] text-gray-500 leading-tight">Individual protective garment poly cover packing.</p>
                                <span class="inline-block mt-1 text-[9px] font-bold text-gray-700 bg-gray-100 px-1.5 py-0.5 rounded">Standard Rate</span>
                            </button>

                            <!-- Without Cover Option -->
                            <button type="button" 
                                    id="optWithoutCover" 
                                    onclick="selectPackagingOption('without_cover')"
                                    class="packaging-opt-btn p-3 rounded-xl border-2 text-left transition-all cursor-pointer border-gray-200 bg-white hover:border-[#F25996]/50">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-black text-gray-900 flex items-center gap-1">
                                        <span>📦 Without Cover</span>
                                    </span>
                                    <span class="pkg-check w-4 h-4 rounded-full border border-gray-300 text-transparent flex items-center justify-center text-[10px] font-bold">✓</span>
                                </div>
                                <p class="text-[10px] text-gray-500 leading-tight">Bulk loose packing directly in carton.</p>
                                <?php if ((float)($customization['without_cover_discount_percentage'] ?? 0) > 0): ?>
                                    <span class="inline-block mt-1 text-[9px] font-black text-emerald-700 bg-emerald-100 px-1.5 py-0.5 rounded border border-emerald-200">
                                        -<?= number_format($customization['without_cover_discount_percentage'], 1) ?>% Off
                                    </span>
                                <?php else: ?>
                                    <span class="inline-block mt-1 text-[9px] font-bold text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded">Standard</span>
                                <?php endif; ?>
                            </button>
                        </div>
                    </div>

                    <!-- Piece Count & Constraint Tracker -->
                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-gray-600">Total Stitch Pieces:</span>
                            <span class="font-black text-sm text-gray-900" id="summaryTotalPieces">0 Pcs</span>
                        </div>
                        
                        <!-- Visual Progress Meter -->
                        <div class="w-full bg-gray-200 h-2.5 rounded-full overflow-hidden">
                            <div id="pieceProgressBar" class="h-full bg-[#F25996] transition-all duration-300" style="width: 0%;"></div>
                        </div>

                        <div class="flex items-center justify-between text-[11px] text-gray-500" id="pieceConstraintText">
                            <span>Min to Enable Checkout: <?= (int)$customization['minimum_pieces'] ?> Pcs</span>
                            <span>Max Allowed: <?= (int)($customization['maximum_pieces'] ?? ($customization['minimum_pieces'] * 20)) ?> Pcs</span>
                        </div>
                    </div>

                    <!-- Live Summary Highlights (Compact Card) -->
                    <div class="bg-gradient-to-br from-pink-50/60 to-purple-50/30 p-4 rounded-2xl border border-pink-100 space-y-2.5">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-600 font-medium">Calculation Status:</span>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 shrink-0 whitespace-nowrap" id="liveStatusBadge">
                                Live Ready
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-xs text-gray-600">
                            <span>Total Required Fabric:</span>
                            <span class="font-bold text-gray-900" id="summaryRequiredFabric">0.00 <?= htmlspecialchars($customization['unit'] ?? 'Meters') ?></span>
                        </div>
                        <div class="flex items-center justify-between text-xs text-gray-600">
                            <span>Fabric Stock Available:</span>
                            <span class="font-bold text-emerald-700" id="summaryStockDisplay"><?= (int)$customization['fabric_stock'] ?> Qty</span>
                        </div>
                        
                        <!-- Hidden elements to preserve ID bindings for JS calculations -->
                        <span id="summaryBaseConsumption" class="hidden">0.00</span>
                        <span id="summaryWastageAmount" class="hidden">+0.00</span>
                        <span id="summaryFabricCost" class="hidden">₹0.00</span>
                        <div id="packagingDiscountRow" class="hidden"><span id="summaryCoverDiscount"></span></div>
                        <span id="summaryGstAmount" class="hidden">₹0.00</span>
                        <span id="deliveryDaysBadge" class="hidden"></span>
                        <span id="deliveryExpectedDate" class="hidden"></span>
                        <span id="deliveryScaleNote" class="hidden"></span>
                        <input type="hidden" id="shippingAddressInput" value="">
                        <input type="hidden" id="orderNotesInput" value="">

                        <div class="flex items-center justify-between pt-2.5 border-t border-pink-200/60">
                            <div>
                                <span class="text-xs font-bold text-gray-800 block">Estimated Subtotal:</span>
                                <span class="text-[10px] text-gray-400">Includes 5% GST &amp; discounts</span>
                            </div>
                            <span class="text-2xl font-black text-[#F25996]" id="summaryGrandTotal">₹0.00</span>
                        </div>
                    </div>

                    <!-- Error Alert Box (if validation fails) -->
                    <div id="errorAlertBox" class="hidden p-3.5 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs font-semibold"></div>

                    <!-- Step 1 to Step 2 Proceed to Checkout CTA -->
                    <div class="space-y-2.5 pt-2">
                        <button type="button" 
                                id="btnProceedCheckout" 
                                onclick="proceedToCheckout()" 
                                class="w-full py-4 px-6 bg-gradient-to-r from-[#F25996] via-[#ea3c85] to-[#db2777] hover:opacity-95 text-white font-black text-base rounded-2xl shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2.5 cursor-pointer group">
                            <span>Proceed to Checkout</span>
                            <svg class="w-5 h-5 text-white transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                        <!-- Keep a hidden button for any legacy bindings -->
                        <button type="button" id="btnPayRazorpay" class="hidden"></button>

                        <div class="flex items-center justify-center gap-2 text-[11px] text-gray-400 font-medium">
                            <svg class="w-3.5 h-3.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd"/>
                            </svg>
                            <span>Next: Review sizes, delivery address, subtotals &amp; secure Razorpay payment</span>
                        </div>
                    </div>

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
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
const FABRIC_GALLERY_IMAGES = <?= json_encode(array_values(array_map(fn($p) => get_image_url($p), $galleryList))) ?>;
let fabricActiveImgIndex = 0;

function setFabricActiveImage(idx) {
    if (!FABRIC_GALLERY_IMAGES || !FABRIC_GALLERY_IMAGES[idx]) return;
    fabricActiveImgIndex = idx;
    const mainImg = document.getElementById('fabricMainImage');
    if (mainImg) {
        mainImg.style.opacity = '0.6';
        mainImg.src = FABRIC_GALLERY_IMAGES[idx];
        setTimeout(() => { mainImg.style.opacity = '1'; }, 100);
    }
    document.querySelectorAll('.fabric-thumb-btn').forEach((btn, i) => {
        if (i === idx) {
            btn.className = 'fabric-thumb-btn w-16 h-16 sm:w-18 sm:h-18 md:w-20 md:h-20 aspect-square rounded-2xl bg-white overflow-hidden border-2 transition-all cursor-pointer shrink-0 border-[#F25996] ring-2 ring-[#F25996]/30 shadow-xs';
        } else {
            btn.className = 'fabric-thumb-btn w-16 h-16 sm:w-18 sm:h-18 md:w-20 md:h-20 aspect-square rounded-2xl bg-white overflow-hidden border-2 transition-all cursor-pointer shrink-0 border-gray-200 hover:border-[#F25996]/50 opacity-80 hover:opacity-100';
        }
    });
}

function shareFabricProduct() {
    const url = window.location.href;
    const title = document.title;
    if (navigator.share) {
        navigator.share({ title: title, url: url }).catch(() => {});
    } else {
        navigator.clipboard.writeText(url).then(() => {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Link copied to clipboard!',
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                alert('Product link copied to clipboard!');
            }
        });
    }
}

function handleAddToCartClick() {
    proceedToCheckout();
}

function onTopFabricMultiplierChange(inp) {
    let mult = parseInt(inp.value) || 1;
    if (mult < 1) mult = 1;
    const bottomInp = document.getElementById('fabric_multiplier');
    if (bottomInp) bottomInp.value = mult;
    onFabricMultiplierChange(inp);
}

function openFabricModal(idx) {
    if (idx !== undefined) fabricActiveImgIndex = idx;
    const modal = document.getElementById('fabricLightboxModal');
    const modalImg = document.getElementById('fabricModalImg');
    const counter = document.getElementById('fabricModalCounter');
    if (!modal || !modalImg) return;

    modalImg.src = FABRIC_GALLERY_IMAGES[fabricActiveImgIndex] || '';
    if (counter) counter.innerText = `${fabricActiveImgIndex + 1} / ${FABRIC_GALLERY_IMAGES.length}`;
    modal.classList.remove('hidden');
    renderModalThumbs();
}

function closeFabricModal() {
    const modal = document.getElementById('fabricLightboxModal');
    if (modal) modal.classList.add('hidden');
}

function navFabricModal(direction) {
    if (!FABRIC_GALLERY_IMAGES || FABRIC_GALLERY_IMAGES.length <= 1) return;
    fabricActiveImgIndex = (fabricActiveImgIndex + direction + FABRIC_GALLERY_IMAGES.length) % FABRIC_GALLERY_IMAGES.length;
    const modalImg = document.getElementById('fabricModalImg');
    const counter = document.getElementById('fabricModalCounter');
    if (modalImg) modalImg.src = FABRIC_GALLERY_IMAGES[fabricActiveImgIndex];
    if (counter) counter.innerText = `${fabricActiveImgIndex + 1} / ${FABRIC_GALLERY_IMAGES.length}`;
    setFabricActiveImage(fabricActiveImgIndex);
    renderModalThumbs();
}

function renderModalThumbs() {
    const wrap = document.getElementById('fabricModalThumbs');
    if (!wrap) return;
    if (FABRIC_GALLERY_IMAGES.length <= 1) {
        wrap.innerHTML = '';
        return;
    }
    wrap.innerHTML = FABRIC_GALLERY_IMAGES.map((url, i) => `
        <button type="button" onclick="setFabricActiveImage(${i}); document.getElementById('fabricModalImg').src='${url}'; document.getElementById('fabricModalCounter').innerText='${i + 1} / ${FABRIC_GALLERY_IMAGES.length}'; renderModalThumbs();" class="w-12 h-12 rounded-xl overflow-hidden border-2 transition-all flex-shrink-0 ${i === fabricActiveImgIndex ? 'border-[#F25996] ring-2 ring-[#F25996]/50' : 'border-white/40 opacity-70 hover:opacity-100'}">
            <img src="${url}" class="w-full h-full object-cover">
        </button>
    `).join('');
}

document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('fabricLightboxModal');
    if (!modal || modal.classList.contains('hidden')) return;
    if (e.key === 'Escape') closeFabricModal();
    if (e.key === 'ArrowLeft') navFabricModal(-1);
    if (e.key === 'ArrowRight') navFabricModal(1);
});

const FABRIC_ID = <?= (int)$customization['fabric_id'] ?>;
const CUSTOMIZATION_ID = <?= (int)$customization['id'] ?>;
const MIN_PIECES = <?= (int)$customization['minimum_pieces'] ?>;
const MAX_PIECES = <?= (int)($customization['maximum_pieces'] ?? ($customization['minimum_pieces'] * 20)) ?>;
const FABRIC_STOCK = <?= (float)$customization['fabric_stock'] ?>;
const UNIT = <?= json_encode($customization['unit'] ?? 'Qty') ?>;
const BASE_URL = <?= json_encode(BASE_URL) ?>;
const IS_LOGGED_IN = <?= \Core\Session::get('user_id') ? 'true' : 'false' ?>;
const FABRIC_PRICE_PER_UNIT = <?= (float)($customization['fabric_price'] ?? 0) ?>;
const SIZES_CONFIG = <?= json_encode($customization['sizes'] ?? []) ?>;
const WITHOUT_COVER_DISCOUNT_PCT = <?= (float)($customization['without_cover_discount_percentage'] ?? 0) ?>;
const DELIVERY_DAYS = <?= (int)($customization['delivery_days'] ?? 7) ?>;
const DELIVERY_BASE_PIECES = <?= (int)($customization['delivery_base_pieces'] ?? 100) ?>;

let selectedPackagingOption = 'with_cover';
let selectedSizeNames = []; // Preserves selection sequence
let currentCalc = null;

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
    triggerRecalc();
}

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

function getSizeLimits(sizeName, mult) {
    if (!mult) mult = getFabricQuantity();
    const sObj = SIZES_CONFIG.find(s => s.size_name === sizeName);
    const baseMin = sObj && parseInt(sObj.base_quantity) > 0 ? parseInt(sObj.base_quantity) : 2;
    const baseMax = sObj && parseInt(sObj.max_quantity) > 0 ? parseInt(sObj.max_quantity) : 35;
    return {
        min: baseMin * mult,
        max: baseMax * mult,
        baseMin: baseMin,
        baseMax: baseMax
    };
}

function onFabricMultiplierChange(inp) {
    let mult = parseInt(inp.value) || 1;
    if (mult < 1) {
        mult = 1;
        inp.value = 1;
    }

    const topInp = document.getElementById('fabric_multiplier_top');
    if (topInp && topInp !== inp) topInp.value = mult;
    const bottomInp = document.getElementById('fabric_multiplier');
    if (bottomInp && bottomInp !== inp) bottomInp.value = mult;

    // Update multiplier badge
    const badge = document.getElementById('fabricQtyBadge');
    if (badge) {
        badge.textContent = mult === 1 ? '1 Set (1x)' : `${mult} Sets (${mult}x)`;
    }

    // Update required pieces Min & Max labels dynamically
    const reqMinPcs = MIN_PIECES * mult;
    const reqMaxPcs = MAX_PIECES * mult;
    const reqBadge = document.getElementById('fabricRequiredPcs');
    if (reqBadge) reqBadge.textContent = `${reqMinPcs} Pcs`;
    const maxBadge = document.getElementById('fabricMaxPcs');
    if (maxBadge) maxBadge.textContent = `${reqMaxPcs} Pcs`;

    // Update button & row labels and clamp input values
    SIZES_CONFIG.forEach(sz => {
        const sizeName = sz.size_name;
        const limits = getSizeLimits(sizeName, mult);

        const btnRange = document.getElementById('btn_range_label_' + sizeName);
        if (btnRange) btnRange.textContent = `${limits.min} – ${limits.max} pcs`;

        const rangeBadge = document.getElementById('range_label_' + sizeName);
        if (rangeBadge) rangeBadge.textContent = `Required: ${limits.min} pcs (Max: ${limits.max} pcs)`;

        const input = document.getElementById('qty_' + sizeName);
        if (input) {
            input.setAttribute('data-min', limits.min);
            input.setAttribute('data-max', limits.max);
            input.min = limits.min;
            input.max = limits.max;
            if (selectedSizeNames.includes(sizeName)) {
                let val = parseInt(input.value) || 0;
                if (val < limits.min) input.value = limits.min;
                else if (val > limits.max) input.value = limits.max;
            } else {
                input.value = 0;
            }
        }
    });

    // Reapply flow with scaled multiplier
    applyVariantSelectionFlow();
}

function toggleSizeVariant(sizeName) {
    const idx = selectedSizeNames.indexOf(sizeName);
    const mult = getFabricQuantity();
    const overallMax = MAX_PIECES * mult;
    const limits = getSizeLimits(sizeName, mult);
    const input = document.getElementById('qty_' + sizeName);

    if (idx >= 0) {
        selectedSizeNames.splice(idx, 1);
        if (input) input.value = 0;
    } else {
        selectedSizeNames.push(sizeName);
        if (input) {
            let otherPieces = getTotalSelectedPieces(sizeName);
            let remainingOverall = Math.max(0, overallMax - otherPieces);
            let initialVal = Math.min(limits.min, remainingOverall);
            input.value = initialVal;
        }
    }
    applyVariantSelectionFlow();
}

function removeSizeVariant(sizeName) {
    const idx = selectedSizeNames.indexOf(sizeName);
    if (idx >= 0) {
        selectedSizeNames.splice(idx, 1);
    }
    const input = document.getElementById('qty_' + sizeName);
    if (input) {
        input.value = 0;
    }
    applyVariantSelectionFlow();
}

function selectAllVariants() {
    selectedSizeNames = SIZES_CONFIG.map(s => s.size_name);
    const mult = getFabricQuantity();
    const overallMax = MAX_PIECES * mult;
    let accumulated = 0;

    SIZES_CONFIG.forEach(sz => {
        const limits = getSizeLimits(sz.size_name, mult);
        const input = document.getElementById('qty_' + sz.size_name);
        if (input) {
            let remaining = Math.max(0, overallMax - accumulated);
            let val = Math.min(limits.min, remaining);
            input.value = val;
            accumulated += val;
        }
    });
    applyVariantSelectionFlow();
}

function clearAllVariants() {
    selectedSizeNames = [];
    SIZES_CONFIG.forEach(sz => {
        const input = document.getElementById('qty_' + sz.size_name);
        if (input) input.value = 0;
    });
    applyVariantSelectionFlow();
}

function applyVariantSelectionFlow() {
    const mult = getFabricQuantity();

    // Update UI elements for each configured size independently
    SIZES_CONFIG.forEach(sz => {
        const sizeName = sz.size_name;
        const btn = document.getElementById('btn_size_' + sizeName);
        const row = document.getElementById('row_size_' + sizeName);
        const input = document.getElementById('qty_' + sizeName);
        const rangeBadge = document.getElementById('range_label_' + sizeName);
        const btnRange = document.getElementById('btn_range_label_' + sizeName);
        const isSelected = selectedSizeNames.includes(sizeName);
        const limits = getSizeLimits(sizeName, mult);

        // Update button visual state
        if (btn) {
            const checkIcon = btn.querySelector('.check-icon');
            if (isSelected) {
                btn.classList.add('border-[#F25996]', 'bg-pink-50/40', 'shadow-xs', 'ring-2', 'ring-[#F25996]/20');
                btn.classList.remove('border-gray-200', 'bg-white');
                if (checkIcon) {
                    checkIcon.classList.add('bg-[#F25996]', 'border-[#F25996]', 'text-white');
                    checkIcon.classList.remove('border-gray-300', 'text-transparent');
                }
            } else {
                btn.classList.remove('border-[#F25996]', 'bg-pink-50/40', 'shadow-xs', 'ring-2', 'ring-[#F25996]/20');
                btn.classList.add('border-gray-200', 'bg-white');
                if (checkIcon) {
                    checkIcon.classList.remove('bg-[#F25996]', 'border-[#F25996]', 'text-white');
                    checkIcon.classList.add('border-gray-300', 'text-transparent');
                }
            }
        }

        // Show/hide row in the stepper list
        if (row) {
            if (isSelected) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        }

        // Update bounds and values independently for this size
        if (input) {
            input.setAttribute('data-min', limits.min);
            input.setAttribute('data-max', limits.max);
            input.min = limits.min;
            input.max = limits.max;

            if (isSelected) {
                let val = parseInt(input.value) || 0;
                if (val < limits.min) val = limits.min;
                if (val > limits.max) val = limits.max;
                input.value = val;
            } else {
                input.value = 0;
            }
        }

        if (rangeBadge) {
            rangeBadge.textContent = `Required: ${limits.min} pcs (Max: ${limits.max} pcs)`;
        }
        if (btnRange) {
            btnRange.textContent = `${limits.min} – ${limits.max} pcs`;
        }

        // Update top size pill button
        const topPill = document.getElementById('top_size_pill_' + sizeName);
        if (topPill) {
            if (isSelected) {
                topPill.className = 'top-size-pill min-w-[44px] h-10 px-3.5 rounded-xl border-2 font-bold text-sm transition-all cursor-pointer flex items-center justify-center border-[#F25996] bg-pink-50 text-[#F25996] shadow-xs ring-2 ring-[#F25996]/20';
            } else {
                topPill.className = 'top-size-pill min-w-[44px] h-10 px-3.5 rounded-xl border-2 font-bold text-sm transition-all cursor-pointer flex items-center justify-center border-gray-200 bg-white text-gray-700 hover:border-[#F25996]';
            }
        }
    });

    // Empty placeholder toggle
    const placeholder = document.getElementById('emptyVariantsPlaceholder');
    if (placeholder) {
        if (selectedSizeNames.length === 0) {
            placeholder.classList.remove('hidden');
        } else {
            placeholder.classList.add('hidden');
        }
    }

    // Update active notice banner
    const notice = document.getElementById('activeVariantNotice');
    if (notice) {
        if (selectedSizeNames.length > 0) {
            notice.textContent = `• ${selectedSizeNames.length} size(s) selected with independent allowed limits (${mult}x Sets)`;
        } else {
            notice.textContent = '• Please click one or more size buttons above to select variants';
        }
    }

    triggerRecalc();
}

function getTotalSelectedPieces(excludeSizeName = null) {
    const inputs = document.querySelectorAll('.size-qty-input');
    let total = 0;
    inputs.forEach(input => {
        const sz = input.getAttribute('data-size');
        if (sz !== excludeSizeName && selectedSizeNames.includes(sz)) {
            total += parseInt(input.value) || 0;
        }
    });
    return total;
}

function adjustQty(sizeName, delta) {
    const input = document.getElementById('qty_' + sizeName);
    if (!input) return;
    const mult = getFabricQuantity();
    const overallMax = MAX_PIECES * mult;
    const limits = getSizeLimits(sizeName, mult);
    const min = limits.min;
    const sizeMax = limits.max;

    let currentVal = parseInt(input.value) || 0;
    let otherPieces = getTotalSelectedPieces(sizeName);
    let remainingOverall = Math.max(0, overallMax - otherPieces);
    let effectiveMaxForThisSize = Math.min(sizeMax, remainingOverall);

    if (delta > 0) {
        if (currentVal >= effectiveMaxForThisSize) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'info',
                    title: 'Max Limit Reached',
                    text: currentVal >= sizeMax 
                        ? `Maximum allowed pieces for size ${sizeName} is ${sizeMax} pcs.`
                        : `Maximum total pieces allowed for ${mult} Set(s) is ${overallMax} pcs.`,
                    confirmButtonColor: '#F25996',
                    timer: 2500
                });
            }
            return;
        }
        input.value = Math.min(effectiveMaxForThisSize, currentVal + delta);
    } else {
        let val = currentVal + delta;
        if (val < min) val = min;
        input.value = val;
    }
    triggerRecalc();
}

function validateAndRecalc(input) {
    const sizeName = input.getAttribute('data-size');
    const mult = getFabricQuantity();
    const overallMax = MAX_PIECES * mult;
    const limits = getSizeLimits(sizeName, mult);
    const min = limits.min;
    const sizeMax = limits.max;

    let val = parseInt(input.value);
    if (isNaN(val) || val < min) {
        val = min;
        input.value = min;
    }

    let otherPieces = getTotalSelectedPieces(sizeName);
    let remainingOverall = Math.max(0, overallMax - otherPieces);
    let effectiveMaxForThisSize = Math.min(sizeMax, remainingOverall);

    if (val > effectiveMaxForThisSize) {
        val = effectiveMaxForThisSize;
        input.value = effectiveMaxForThisSize;
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'info',
                title: 'Max Limit Reached',
                text: val >= sizeMax 
                    ? `Maximum allowed pieces for size ${sizeName} is ${sizeMax} pcs.`
                    : `Maximum total pieces allowed for ${mult} Set(s) is ${overallMax} pcs.`,
                confirmButtonColor: '#F25996',
                timer: 2500
            });
        }
    }
    triggerRecalc();
}

function getSelectedSizes() {
    const inputs = document.querySelectorAll('.size-qty-input');
    const sizes = {};
    inputs.forEach(input => {
        const sz = input.getAttribute('data-size');
        const qty = parseInt(input.value) || 0;
        if (qty > 0 && selectedSizeNames.includes(sz)) {
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
            sizes: sizes,
            packaging_option: selectedPackagingOption,
            without_cover: (selectedPackagingOption === 'without_cover')
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            currentCalc = data;
            renderCalculation(data.consumption, data.pricing, data.delivery);
        }
    })
    .catch(err => {
        console.error("Calc error", err);
    });
}

function renderCalculation(c, p, delivery) {
    const effectiveMin = c.minimum_pieces || (MIN_PIECES * getFabricQuantity());
    const effectiveMax = c.maximum_pieces || (MAX_PIECES * getFabricQuantity());
    const totalPieces = c.total_pieces || 0;

    // Piece Tracker Text & Progress
    document.getElementById('summaryTotalPieces').textContent = `${totalPieces} / ${effectiveMin}–${effectiveMax} Pcs`;
    
    const constraintText = document.getElementById('pieceConstraintText');
    if (constraintText) {
        constraintText.innerHTML = `
            <span>Min to Enable Payment: <strong class="text-gray-900">${effectiveMin} Pcs</strong></span>
            <span>Max Allowed: <strong class="text-gray-900">${effectiveMax} Pcs</strong></span>
        `;
    }

    document.getElementById('summaryBaseConsumption').textContent = `${c.base_consumption} ${c.unit}`;
    document.getElementById('summaryWastageAmount').textContent = `+${c.wastage_amount} ${c.unit}`;
    document.getElementById('summaryRequiredFabric').textContent = `${c.required_fabric} ${c.unit}`;

    // Fabric cost display with original cost line-through if without cover discount applied
    const fabricCostElem = document.getElementById('summaryFabricCost');
    if (p.without_cover && p.without_cover_discount > 0) {
        if (fabricCostElem) {
            fabricCostElem.innerHTML = `<span class="line-through text-xs text-gray-400 mr-1.5">₹${p.fabric_cost_original.toLocaleString('en-IN', {minimumFractionDigits: 2})}</span> ₹${p.fabric_cost.toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
        }
    } else {
        if (fabricCostElem) {
            fabricCostElem.textContent = `₹${p.fabric_cost.toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
        }
    }

    // Without Cover Discount row toggle in bill
    const pkgRow = document.getElementById('packagingDiscountRow');
    const pkgDiscElem = document.getElementById('summaryCoverDiscount');
    if (pkgRow && pkgDiscElem) {
        if (p.without_cover && p.without_cover_discount > 0) {
            pkgRow.classList.remove('hidden');
            pkgDiscElem.textContent = `-₹${p.without_cover_discount.toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
        } else {
            pkgRow.classList.add('hidden');
        }
    }

    // Dynamic Delivery Schedule update
    const delBadge = document.getElementById('deliveryDaysBadge');
    const delDateElem = document.getElementById('deliveryExpectedDate');
    const delNoteElem = document.getElementById('deliveryScaleNote');
    if (delivery && delBadge && delDateElem) {
        delBadge.textContent = `${delivery.total_days} Days`;
        delDateElem.textContent = delivery.estimated_date_formatted || delivery.estimated_date;
        if (delNoteElem) {
            delNoteElem.textContent = `Dynamically scales: ${delivery.total_days} days for ${delivery.total_pieces} pcs (${delivery.base_days} days per ${delivery.base_pieces} pcs batch)`;
        }
    } else if (delBadge && delDateElem) {
        const baseDays = DELIVERY_DAYS || 7;
        const basePcs = DELIVERY_BASE_PIECES || 100;
        const multBatch = Math.max(1, Math.ceil(totalPieces / basePcs));
        const calcDays = multBatch * baseDays;
        delBadge.textContent = `${calcDays} Days`;
        const est = new Date();
        est.setDate(est.getDate() + calcDays);
        delDateElem.textContent = est.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        if (delNoteElem) {
            delNoteElem.textContent = `Dynamically scales: ${calcDays} days for ${totalPieces} pcs (${baseDays} days per ${basePcs} pcs batch)`;
        }
    }

    document.getElementById('summaryGstAmount').textContent = `₹${p.gst_amount.toLocaleString('en-IN', {minimumFractionDigits: 2})}`;
    document.getElementById('summaryGrandTotal').textContent = `₹${p.total.toLocaleString('en-IN', {minimumFractionDigits: 2})}`;

    const isOverStock = (getFabricQuantity() > FABRIC_STOCK) || !c.is_stock_sufficient;
    const reqFabricContainer = document.getElementById('summaryRequiredFabric').parentElement;
    const statusBadge = document.getElementById('liveStatusBadge');
    const btnPay = document.getElementById('btnPayRazorpay');
    const btnProceed = document.getElementById('btnProceedCheckout');
    const errorBox = document.getElementById('errorAlertBox');

    // Progress bar for minimum pieces
    const bar = document.getElementById('pieceProgressBar');
    const pct = effectiveMax > 0 ? Math.min(100, Math.round((totalPieces / effectiveMax) * 100)) : 100;
    if (bar) bar.style.width = pct + '%';

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

    if (isOverStock) {
        // Exceeded Fabric stock
        if (bar) bar.className = 'h-full bg-rose-600 transition-all duration-300';
        if (reqFabricContainer) reqFabricContainer.className = 'flex items-center justify-between font-bold bg-rose-50 p-2.5 rounded-xl border border-rose-200 text-rose-900';
        if (statusBadge) {
            statusBadge.className = 'text-[10px] sm:text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-800 border border-rose-300 shrink-0 whitespace-nowrap';
            statusBadge.textContent = '❌ Exceeds Fabric Stock';
        }

        if (errorBox) {
            errorBox.innerHTML = `
                <div class="flex items-start gap-2.5">
                    <span class="text-base">⚠️</span>
                    <div>
                        <strong class="font-bold block mb-1">Fabric Stock Exceeded!</strong>
                        <span>You selected <strong>${getFabricQuantity()} Sets</strong>, but only <strong>${FABRIC_STOCK} Qty</strong> are available in inventory. Please reduce the fabric set quantity.</span>
                    </div>
                </div>
            `;
            errorBox.classList.remove('hidden');
        }

        setBtnState(false);

    } else if (totalPieces > effectiveMax) {
        // Exceeds Maximum allowed pieces for chosen sets
        const excess = totalPieces - effectiveMax;
        if (bar) bar.className = 'h-full bg-rose-600 transition-all duration-300';
        if (reqFabricContainer) reqFabricContainer.className = 'flex items-center justify-between font-bold bg-rose-50 p-2.5 rounded-xl border border-rose-200 text-rose-900';
        if (statusBadge) {
            statusBadge.className = 'text-[10px] sm:text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-800 border border-rose-300 shrink-0 whitespace-nowrap';
            statusBadge.textContent = `❌ Exceeds Max (${totalPieces}/${effectiveMax} Pcs)`;
        }

        if (errorBox) {
            errorBox.innerHTML = `
                <div class="flex items-start gap-2.5">
                    <span class="text-base">⚠️</span>
                    <div>
                        <strong class="font-bold block mb-0.5 text-rose-900">Maximum ${effectiveMax} Pieces Allowed</strong>
                        <span class="text-rose-800 text-xs leading-relaxed">
                            You currently have <strong>${totalPieces} pcs</strong> selected (${excess} pcs in excess). Maximum allowed is <strong>${effectiveMax} pcs</strong> for ${getFabricQuantity()} Set(s). Please reduce variant quantities to enable checkout.
                        </span>
                    </div>
                </div>
            `;
            errorBox.classList.remove('hidden');
        }

        setBtnState(false);

    } else if (totalPieces < effectiveMin && totalPieces > 0) {
        // Under minimum pieces - show clear message to take required pieces
        const remainingPcs = effectiveMin - totalPieces;
        if (bar) bar.className = 'h-full bg-[#F25996] transition-all duration-300';
        if (reqFabricContainer) reqFabricContainer.className = 'flex items-center justify-between font-bold bg-pink-50/60 p-2.5 rounded-xl border border-pink-100 text-[#db2777]';
        if (statusBadge) {
            statusBadge.className = 'text-[10px] sm:text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-900 border border-amber-300 shrink-0 whitespace-nowrap';
            statusBadge.textContent = `⏳ Need ${remainingPcs} More Pcs`;
        }

        if (errorBox) {
            errorBox.innerHTML = `
                <div class="flex items-start gap-2.5">
                    <span class="text-base">⏳</span>
                    <div>
                        <strong class="font-bold block mb-0.5 text-pink-900">Minimum ${effectiveMin} Pieces Required to Enable Checkout</strong>
                        <span class="text-pink-800 text-xs leading-relaxed">
                            You currently have <strong>${totalPieces} pcs</strong> selected. Please add <strong>${remainingPcs} more pcs</strong> from the sizes above to proceed to checkout.
                        </span>
                    </div>
                </div>
            `;
            errorBox.classList.remove('hidden');
        }

        setBtnState(false);

    } else if (c.is_valid && totalPieces >= effectiveMin && totalPieces <= effectiveMax) {
        // Valid & Ready
        if (bar) bar.className = 'h-full bg-emerald-500 transition-all duration-300';
        if (reqFabricContainer) reqFabricContainer.className = 'flex items-center justify-between font-bold bg-emerald-50 p-2.5 rounded-xl border border-emerald-200 text-emerald-950';
        if (statusBadge) {
            statusBadge.className = 'text-[10px] sm:text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-300 shrink-0 whitespace-nowrap';
            statusBadge.textContent = `✅ Ready for Checkout (${totalPieces} Pcs)`;
        }

        if (errorBox) errorBox.classList.add('hidden');
        setBtnState(true);

    } else {
        // Default / Empty
        if (bar) bar.className = 'h-full bg-gray-300 transition-all duration-300';
        if (reqFabricContainer) reqFabricContainer.className = 'flex items-center justify-between font-bold bg-gray-50 p-2.5 rounded-xl border border-gray-100 text-gray-800';
        if (statusBadge) {
            statusBadge.className = 'text-[10px] sm:text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-600 border border-gray-200 shrink-0 whitespace-nowrap';
            statusBadge.textContent = `Min ${effectiveMin} Pcs Required`;
        }

        if (errorBox) errorBox.classList.add('hidden');
        setBtnState(false);
    }
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
        notes: notes,
        packaging_option: selectedPackagingOption,
        without_cover: (selectedPackagingOption === 'without_cover')
    };
}

// -------------------------------------------------------------
// Proceed to Step 2: Dedicated Checkout & Razorpay Page
// -------------------------------------------------------------
function proceedToCheckout() {
    if (!checkLoginOrPrompt()) return;

    const sizes = getSelectedSizes();
    const fabricQuantity = getFabricQuantity();
    const mult = fabricQuantity;
    const effectiveMin = MIN_PIECES * mult;
    const effectiveMax = MAX_PIECES * mult;
    const totalPcs = Object.values(sizes).reduce((a, b) => a + b, 0);

    if (totalPcs === 0) {
        Swal.fire({
            title: 'Select Size Quantities',
            text: 'Please select quantities for at least one size variant to proceed to checkout.',
            icon: 'info',
            confirmButtonColor: '#F25996'
        });
        return;
    }

    if (totalPcs < effectiveMin) {
        Swal.fire({
            title: 'Minimum Pieces Required',
            text: `Please select at least ${effectiveMin} total pieces to continue (currently ${totalPcs} pcs).`,
            icon: 'warning',
            confirmButtonColor: '#F25996'
        });
        return;
    }

    if (totalPcs > effectiveMax) {
        Swal.fire({
            title: 'Maximum Limit Exceeded',
            text: `Maximum allowed pieces for ${mult} set(s) is ${effectiveMax} pcs (currently ${totalPcs} pcs).`,
            icon: 'warning',
            confirmButtonColor: '#F25996'
        });
        return;
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

    document.body.appendChild(form);
    form.submit();
}

// -------------------------------------------------------------
// Online Payment via Razorpay
// -------------------------------------------------------------
function payViaRazorpay() {
    if (!checkLoginOrPrompt()) return;

    const payload = getPayload();
    if (Object.keys(payload.sizes).length === 0) {
        Swal.fire({ title: 'Select Sizes', text: 'Please add quantities for at least one size.', icon: 'warning', confirmButtonColor: '#F25996' });
        return;
    }

    if (!currentCalc || !currentCalc.consumption.is_valid || (getFabricQuantity() > FABRIC_STOCK)) {
        let msg = 'Please satisfy the required piece requirements.';
        if (getFabricQuantity() > FABRIC_STOCK || (currentCalc && !currentCalc.consumption.is_stock_sufficient)) {
            msg = `<strong>Fabric Inventory Shortage:</strong> You selected <strong>${getFabricQuantity()} Sets</strong>, but only <strong>${FABRIC_STOCK} Qty</strong> are available in stock.`;
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
    clearAllVariants();
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
