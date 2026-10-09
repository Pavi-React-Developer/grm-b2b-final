<?php
// Extracted from $contentData which is passed in via home.php
$id = uniqid('carousel_');
$title = $contentData['title'] ?? '';
$sort_order = $contentData['sort_order'] ?? 0;
$mobile_count = max(1, (int)($contentData['mobile_count'] ?? 2));
$desktop_count = (int)($contentData['desktop_count'] ?? 4);
if ($desktop_count < 2) {
    $desktop_count = 4;
}
$cta_text = $contentData['cta_text'] ?? '';
$cta_url = $contentData['cta_url'] ?? '';
$cta_position = $contentData['cta_position'] ?? 'Right';
$show_arrows = $contentData['show_arrows'] ?? true;
$show_dots = $contentData['show_dots'] ?? true;
$product_ids = $contentData['product_ids'] ?? [];
$theme = $contentData['theme'] ?? ['type' => 'solid', 'value' => '#ffffff'];

$title_color = $contentData['title_color'] ?? '#111827';
$text_color = $contentData['text_color'] ?? '#111827';
$price_color = $contentData['price_color'] ?? '#111827';
$button_bg_color = $contentData['button_bg_color'] ?? '#4a3628';
$button_text_color = $contentData['button_text_color'] ?? '#ffffff';
$arrow_color = !empty($contentData['arrow_color']) && $contentData['arrow_color'] !== '#ffffff' && $contentData['arrow_color'] !== '#111827' && $contentData['arrow_color'] !== '#1f2937' ? $contentData['arrow_color'] : '#F25996';
$arrow_bg_color = $contentData['arrow_bg_color'] ?? '#ffffff';
$dot_color = $contentData['dot_color'] ?? '#8c6a4f';
$wishlist_color = $contentData['wishlist_color'] ?? 'rgba(17, 24, 39, 0.8)';
$image_fit = $contentData['image_fit'] ?? 'cover';

$objectFitClass = 'object-cover';
if ($image_fit === 'contain') $objectFitClass = 'object-contain';
elseif ($image_fit === 'stretch') $objectFitClass = 'object-fill';

// Background styling
$bgStyle = '';
if (!empty($theme['value'])) {
    $bgStyle = 'background: ' . $theme['value'] . ';';
}

// Find actual product data from the globally injected $products array
$carouselVendorId = $contentData['vendor_id'] ?? 'all';
$product_ids_int = array_map('intval', (array)($product_ids ?? []));

$carouselProducts = [];
if (isset($products) && is_array($products)) {
    if (!empty($product_ids_int)) {
        // If specific products were checked/selected, map them in exact selected order
        $productMap = [];
        foreach ($products as $p) {
            $productMap[(int)$p['id']] = $p;
        }
        foreach ($product_ids_int as $pId) {
            if (isset($productMap[$pId])) {
                $carouselProducts[] = $productMap[$pId];
            }
        }
    } elseif ($carouselVendorId !== 'all' && $carouselVendorId !== '') {
        // If vendor filter was chosen, show all vendor products
        foreach ($products as $p) {
            $pVendorId = (int)($p['vendor_id'] ?? $p['user_id'] ?? 0);
            if ($carouselVendorId === 'in_house' && $pVendorId === 0) {
                $carouselProducts[] = $p;
            } elseif ((int)$carouselVendorId === $pVendorId) {
                $carouselProducts[] = $p;
            }
        }
    }
}

$mobileWidth = 100 / max(1, $mobile_count);
$desktopWidth = 100 / max(1, $desktop_count);
$tablet_count = min(3, max(2, (int)floor($desktop_count * 0.75)));
$tabletWidth = 100 / max(1, $tablet_count);
?>

<style>
    #<?= $id ?> .slide-item {
        width: calc(<?= $mobileWidth ?>% - <?= (($mobile_count - 1) * 0.5) / $mobile_count ?>rem);
    }
    #<?= $id ?> .carousel-btn {
        top: 30%; /* Center of product image on mobile so it never covers product titles or prices */
        width: 1.5rem;
        height: 1.5rem;
        color: #F25996 !important;
    }
    #<?= $id ?> .carousel-btn svg {
        width: 0.75rem;
        height: 0.75rem;
        stroke: #F25996 !important;
    }
    #<?= $id ?> .carousel-btn:hover {
        color: #e04481 !important;
    }
    #<?= $id ?> .carousel-btn:hover svg {
        stroke: #e04481 !important;
    }
    #prev_<?= $id ?> { left: -0.25rem; }
    #next_<?= $id ?> { right: -0.25rem; }
    /* Mobile view alignment: minimal gutters so product cards maximize display width */
    #track_container_<?= $id ?> { padding-left: 0.15rem; padding-right: 0.15rem; }
    #inner_<?= $id ?> { gap: 0.5rem !important; }
    
    #<?= $id ?> .slider-dot {
        width: 6px;
        height: 6px;
        background-color: <?= htmlspecialchars($dot_color) ?> !important;
        border-radius: 9999px;
        border: none;
        cursor: pointer;
        padding: 0;
        transition: all 0.3s ease;
        opacity: 0.5;
        display: inline-block;
        vertical-align: middle;
    }
    #<?= $id ?> .slider-dot.active {
        width: 8px;
        height: 8px;
        background-color: <?= htmlspecialchars($dot_color) ?> !important;
        opacity: 1;
        outline: 2px solid <?= htmlspecialchars($dot_color) ?> !important;
        outline-offset: 2px;
    }

    /* Tablet layout (768px to 1023px) */
    @media (min-width: 768px) and (max-width: 1023px) {
        #<?= $id ?> .slide-item {
            width: calc(<?= $tabletWidth ?>% - <?= (($tablet_count - 1) * 0.85) / $tablet_count ?>rem) !important;
            display: flex !important;
            flex-direction: column !important;
        }
        #inner_<?= $id ?> {
            gap: 0.85rem !important;
        }
        #track_container_<?= $id ?> {
            padding-left: 1.25rem !important;
            padding-right: 1.25rem !important;
        }
        #<?= $id ?> .carousel-btn {
            top: 35% !important;
            width: 1.75rem !important;
            height: 1.75rem !important;
        }
        #<?= $id ?> .carousel-btn svg {
            width: 0.85rem !important;
            height: 0.85rem !important;
        }
        #prev_<?= $id ?> { left: -0.25rem !important; }
        #next_<?= $id ?> { right: -0.25rem !important; }
        #<?= $id ?> .product-card {
            border-radius: 1.25rem !important;
            height: 100% !important;
        }
        #<?= $id ?> .product-image-wrap {
            aspect-ratio: 1 / 1 !important;
        }
        #<?= $id ?> .product-card-body {
            padding: 0.75rem 0.65rem 0.85rem 0.65rem !important;
            margin-top: -1.25rem !important;
            border-radius: 1.25rem 1.25rem 0 0 !important;
        }
        #<?= $id ?> .product-name {
            font-size: 0.9375rem !important;
            font-weight: 700 !important;
            line-height: 1.25 !important;
            margin-bottom: 0.35rem !important;
        }
        #<?= $id ?> .product-price {
            font-size: 1.15rem !important;
            font-weight: 800 !important;
        }
        #<?= $id ?> .product-orig-price {
            font-size: 0.75rem !important;
        }
        #<?= $id ?> .product-badge-discount {
            font-size: 0.625rem !important;
            padding: 0.15rem 0.35rem !important;
        }
        #<?= $id ?> .product-moq-row {
            font-size: 0.725rem !important;
            font-weight: 600 !important;
            margin-bottom: 0.5rem !important;
            display: flex !important;
            align-items: center !important;
            gap: 0.25rem !important;
        }
        #<?= $id ?> .product-moq-row svg {
            width: 0.85rem !important;
            height: 0.85rem !important;
            flex-shrink: 0 !important;
        }
        #<?= $id ?> .product-btn {
            padding: 0.55rem 0.5rem !important;
            font-size: 0.8125rem !important;
            font-weight: 700 !important;
            border-radius: 0.65rem !important;
            width: 100% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0.35rem !important;
            white-space: nowrap !important;
            line-height: 1.2 !important;
        }
        #<?= $id ?> .product-btn svg {
            width: 1rem !important;
            height: 1rem !important;
            flex-shrink: 0 !important;
        }
        #<?= $id ?> .product-btn span {
            font-size: inherit !important;
            white-space: nowrap !important;
        }
        #<?= $id ?> .carousel-header-cta {
            min-height: 2.25rem !important;
            padding: 0.4rem 1.25rem !important;
            font-size: 0.8125rem !important;
            border-width: 1.5px !important;
        }
        #<?= $id ?> .slider-dot {
            width: 8px;
            height: 8px;
        }
        #<?= $id ?> .slider-dot.active {
            width: 10px;
            height: 10px;
            outline: 2px solid <?= htmlspecialchars($dot_color) ?> !important;
            outline-offset: 2px;
        }
    }
    
    /* Desktop layout (1024px and up) */
    @media (min-width: 1024px) {
        #<?= $id ?> .slide-item {
            width: calc(<?= $desktopWidth ?>% - <?= (($desktop_count - 1) * 1.5) / $desktop_count ?>rem);
        }
        #<?= $id ?> .carousel-btn {
            top: 50%; /* center of entire card on desktop */
            width: 1.85rem;
            height: 1.85rem;
        }
        #<?= $id ?> .carousel-btn svg {
            width: 0.95rem;
            height: 0.95rem;
        }
        #prev_<?= $id ?> { left: 0.25rem; }
        #next_<?= $id ?> { right: 0.25rem; }
        #track_container_<?= $id ?> { padding-left: 2rem; padding-right: 2rem; }
        #inner_<?= $id ?> { gap: 1.5rem !important; }
        #<?= $id ?> .slider-dot {
            width: 10px;
            height: 10px;
        }
        #<?= $id ?> .slider-dot.active {
            width: 12px;
            height: 12px;
            outline: 2px solid <?= htmlspecialchars($dot_color) ?> !important;
            outline-offset: 2px;
        }
    }
    @media (max-width: 767px) {
        #<?= $id ?> {
            min-height: 0 !important;
            height: auto !important;
            padding-top: 0.35rem !important;
            padding-bottom: 0.35rem !important;
        }
        #<?= $id ?> .carousel-outer-wrap {
            padding-left: 0.25rem !important;
            padding-right: 0.25rem !important;
        }
        #<?= $id ?> .carousel-bg-wrap {
            padding: 0.65rem 0.35rem 0.5rem 0.35rem !important;
            border-radius: 1.25rem !important;
        }
        #track_container_<?= $id ?> {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        #<?= $id ?> .carousel-header-wrap {
            margin-bottom: 0.5rem !important;
            gap: 0.5rem !important;
        }
        #<?= $id ?> .slide-item {
            display: flex !important;
            flex-direction: column !important;
            height: auto !important;
        }
        #<?= $id ?> .product-card {
            height: 100% !important;
            flex: 1 1 auto !important;
            display: flex !important;
            flex-direction: column !important;
            border-radius: 1.25rem !important;
            overflow: hidden !important;
        }
        #<?= $id ?> .product-image-wrap {
            aspect-ratio: 4 / 5 !important;
            width: 100% !important;
            height: auto !important;
            overflow: hidden !important;
            background: #f3f4f6 !important;
            display: block !important;
            line-height: 0 !important;
            flex-shrink: 0 !important;
        }
        #<?= $id ?> .product-image-wrap img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            display: block !important;
        }
        #<?= $id ?> .product-card-body {
            padding: 0.55rem 0.45rem 0.65rem 0.45rem !important;
            margin-top: -1rem !important;
            border-radius: 1.25rem 1.25rem 0 0 !important;
            flex: 1 1 auto !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: flex-start !important;
        }
        #<?= $id ?> .product-card-body .mt-auto {
            margin-top: 0 !important;
            display: flex !important;
            flex-direction: column !important;
            flex: 1 1 auto !important;
        }
        #<?= $id ?> .product-card-body form {
            margin-top: auto !important;
        }
        #<?= $id ?> .product-name {
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            line-height: 1.2 !important;
            margin-bottom: 0.2rem !important;
            min-height: auto !important;
        }
        #<?= $id ?> .product-price {
            font-size: 0.85rem !important;
            font-weight: 800 !important;
        }
        #<?= $id ?> .product-moq-row {
            font-size: 0.525rem !important;
            font-weight: 600 !important;
            margin-bottom: 0.25rem !important;
            display: flex !important;
            align-items: center !important;
            gap: 0.15rem !important;
            letter-spacing: -0.01em !important;
        }
        #<?= $id ?> .product-moq-row svg {
            width: 0.5875rem !important;
            height: 0.5875rem !important;
            flex-shrink: 0 !important;
        }
        #<?= $id ?> .product-btn {
            white-space: nowrap !important;
            flex-wrap: nowrap !important;
            padding: 0.35rem 0.2rem !important;
            font-size: 0.625rem !important;
            border-radius: 0.5rem !important;
            width: 100% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0.15rem !important;
            line-height: 1.2 !important;
        }
        #<?= $id ?> .product-btn svg {
            width: 0.6875rem !important;
            height: 0.6875rem !important;
            flex-shrink: 0 !important;
        }
        #<?= $id ?> .product-btn span {
            font-size: inherit !important;
            white-space: nowrap !important;
        }
        #<?= $id ?> .carousel-header-cta {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-height: 1.5rem !important;
            height: auto !important;
            padding: 0.25rem 0.65rem !important;
            font-size: 0.6875rem !important;
            font-weight: 700 !important;
            letter-spacing: 0.05em !important;
            border-radius: 9999px !important;
            border: 1px solid <?= htmlspecialchars($title_color ?? '#ffffff') ?> !important;
            background: transparent !important;
            color: <?= htmlspecialchars($title_color ?? '#ffffff') ?> !important;
            white-space: nowrap !important;
            line-height: 1 !important;
        }
    }
    @media (min-width: 1024px) {
        #<?= $id ?> .carousel-header-cta {
            min-height: 2.5rem !important;
            padding: 0.5rem 1.5rem !important;
            font-size: 0.875rem !important;
            border-width: 1.5px !important;
        }
    }
</style>

<div class="w-full relative py-2 sm:py-4 md:py-6" id="<?= $id ?>">
    <div class="carousel-outer-wrap w-full max-w-full mx-auto px-1 sm:px-6 lg:px-8">
        <div class="carousel-bg-wrap rounded-2xl md:rounded-[2rem] p-2 sm:p-4 md:p-6 lg:p-8 relative" style="<?= $bgStyle ?>">
        <div class="w-full relative">
        
        <!-- Header -->
        <div class="carousel-header-wrap flex flex-col md:flex-row items-start md:items-center justify-between gap-2 md:gap-3 mb-3 md:mb-10">
            <?php if ($cta_position === 'Center'): ?>
                <div class="flex-1"></div>
            <?php endif; ?>
            
            <h2 class="text-lg sm:text-2xl md:text-3xl font-serif font-bold leading-tight min-w-0 <?= $cta_position === 'Center' ? 'text-center flex-1' : 'flex-1' ?>" style="color: <?= htmlspecialchars($title_color) ?>;">
                <?= htmlspecialchars($title) ?>
            </h2>
            
            <?php if ($cta_text && $cta_url): ?>
                <div class="self-end md:self-auto <?= $cta_position === 'Center' ? 'flex-1 text-right' : 'shrink-0' ?>">
                    <a href="<?= htmlspecialchars($cta_url) ?>" class="carousel-header-cta text-xs sm:text-sm font-bold tracking-wider uppercase hover:opacity-80 px-3 sm:px-6 py-1.5 sm:py-2 rounded-full transition-all whitespace-nowrap inline-flex items-center justify-center" style="border: 1.5px solid <?= htmlspecialchars($title_color ?? '#ffffff') ?>; color: <?= htmlspecialchars($title_color ?? '#ffffff') ?>; background-color: transparent;">
                        <?= htmlspecialchars($cta_text) ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Slider Container -->
        <div class="relative group/carousel" id="track_container_<?= $id ?>">
            
            <!-- Track -->
            <div class="overflow-hidden touch-pan-y" id="track_<?= $id ?>">
                <div class="flex transition-transform duration-500 ease-out items-stretch" id="inner_<?= $id ?>" style="transform: translateX(0%);">
                    <?php if (empty($carouselProducts)): ?>
                        <div class="w-full py-20 text-center text-gray-500 bg-white/50 rounded-2xl">
                            No products selected for this carousel.
                        </div>
                    <?php else: ?>
                        <?php foreach ($carouselProducts as $prod): 
                            $img = !empty($prod['primary_image']) ? (str_starts_with($prod['primary_image'], 'http') ? $prod['primary_image'] : (BASE_URL . '/' . ltrim($prod['primary_image'], '/'))) : 'https://placehold.co/400?text=' . urlencode($prod['name']);
                            $catGst = (float)($prod['category_sgst'] ?? 0) + (float)($prod['category_cgst'] ?? 0);
                            $prodGst = $catGst > 0 ? $catGst : ((float)($prod['variant_total_gst'] ?? 0) ?: (float)($prod['total_gst'] ?? 0));

                            $basePrice = (isset($prod['base_price']) && (float)$prod['base_price'] > 0) ? (float)$prod['base_price'] : (float)($prod['wholesale_price'] ?? 0);
                            $discountPrice = !empty($prod['discount_price']) && (float)$prod['discount_price'] > 0 ? (float)$prod['discount_price'] : null;
                            $effPrice = $discountPrice ?? $basePrice;

                            $displayPrice = $effPrice;

                            $displayOriginalPrice = null;
                            if ($discountPrice && $basePrice > $discountPrice) {
                                $displayOriginalPrice = $basePrice;
                            }

                            $moq = !empty($prod['moq_override']) ? $prod['moq_override'] : ($prod['category_moq'] ?? 1);
                            $isLoggedIn = \Core\Session::get('user_id') !== null;
                        ?>
                            <!-- Slide Item -->
                            <div class="slide-item flex-none">
                                <div class="product-card bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow border border-gray-200 flex flex-col h-full relative group">
                                    
                                    <!-- Wishlist Icon -->
                                    <?php if ($isLoggedIn): ?>
                                    <?php 
                                        $inWishlist = false;
                                        if (!empty($wishlistIds) && is_array($wishlistIds)) {
                                            $inWishlist = in_array($prod['id'], $wishlistIds);
                                        } elseif (!empty($wishlistItems) && is_array($wishlistItems)) {
                                            $inWishlist = in_array($prod['id'], array_column($wishlistItems, 'product_id')) || in_array($prod['id'], array_column($wishlistItems, 'id'));
                                        }
                                    ?>
                                    <button onclick="toggleWishlist(<?= $prod['id'] ?>, this)" data-wishlist-btn-id="<?= $prod['id'] ?>" class="wishlist-btn <?= $inWishlist ? 'is-active' : '' ?> absolute top-2 right-2 w-6 h-6 sm:w-7 sm:h-7 hover:opacity-80 transition-opacity rounded-full flex items-center justify-center z-10 shadow-sm" style="background-color: <?= htmlspecialchars($wishlist_color) ?>;">
                                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 <?= $inWishlist ? 'text-[#F25996] fill-[#F25996] is-active' : 'text-gray-400 fill-none' ?>" fill="<?= $inWishlist ? '#F25996' : 'none' ?>" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                    </button>
                                    <?php endif; ?>

                                    <!-- Product Image -->
                                    <a href="<?= BASE_URL ?>/product?id=<?= $prod['id'] ?>" class="product-image-wrap block w-full bg-gray-100 overflow-hidden shrink-0 relative">
                                        <?php 
                                        $discPercent = ($displayOriginalPrice && $displayOriginalPrice > $displayPrice) ? round((($displayOriginalPrice - $displayPrice) / $displayOriginalPrice) * 100) : 0;
                                        if ($isLoggedIn && $discPercent > 0) {
                                            echo render_discount_starburst($discPercent, 'absolute top-1.5 left-1.5 z-10 w-16 sm:w-18 md:w-20 transition-transform duration-300 group-hover:scale-110');
                                        }
                                        ?>
                                        <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($prod['name']) ?>" class="w-full h-full <?= $objectFitClass ?> group-hover:scale-105 transition-transform duration-500" loading="lazy" decoding="async" style="object-fit: <?= htmlspecialchars($image_fit ?: 'cover') ?>; width: 100%; height: 100%; display: block;">
                                    </a>

                                    <!-- Product Details -->
                                    <div class="product-card-body p-3 md:p-4 flex flex-col bg-white relative z-10">
                                        <?php 
                                        $cardBrand = !empty($prod['vendor_store_name']) ? $prod['vendor_store_name'] : (!empty($prod['vendor_company_name']) ? $prod['vendor_company_name'] : '');
                                        ?>
                                        <?php if (!empty($cardBrand)): ?>
                                            <div class="mb-1">
                                                <span class="inline-flex items-center gap-1 text-[10px] md:text-[11px] font-bold text-indigo-700 bg-indigo-50/80 px-2 py-0.5 rounded border border-indigo-100/70 capitalize">
                                                    <svg class="w-2.5 h-2.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                                    <?= htmlspecialchars($cardBrand) ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>

                                        <a href="<?= BASE_URL ?>/product?id=<?= $prod['id'] ?>" class="product-link block mb-1">
                                            <h3 class="product-name font-bold leading-snug line-clamp-2" style="font-size: clamp(0.875rem, 3.5vw, 1.25rem);"><?= htmlspecialchars($prod['name']) ?></h3>
                                        </a>
                                        
                                        <?php if (!empty($prod['sku'])): ?>
                                            <p class="font-medium mb-2 hidden md:block" style="font-size: 0.9375rem; color: <?= htmlspecialchars($subtitle_color) ?>;">SKU: <?= htmlspecialchars($prod['sku']) ?></p>
                                        <?php endif; ?>

                                        <?php $isLoggedIn = \Core\Session::get('user_id') !== null; ?>
                                        <?php if ($isLoggedIn): ?>
                                            <div class="mt-auto flex flex-col justify-end">
                                                <!-- Selling Price, Strikethrough & Discount Badge (Same Row) -->
                                                <div class="product-price-row flex items-baseline gap-1.5 flex-wrap mb-1">
                                                    <span class="product-price font-extrabold tracking-tight" style="font-size: clamp(0.95rem, 3.8vw, 1.5rem);">₹<?= format_price($displayPrice) ?></span>
                                                    <?php if ($displayOriginalPrice && $displayOriginalPrice > $displayPrice): ?>
                                                        <span class="product-orig-price text-[0.6875rem] md:text-sm font-bold text-gray-400 line-through">₹<?= format_price($displayOriginalPrice) ?></span>
                                                    <?php endif; ?>
                                                </div>

                                                <!-- Row 4: Minimum Order Count -->
                                                <p class="product-moq-row text-[0.525rem] md:text-[11px] lg:text-xs font-semibold mb-2 md:mb-3 lg:mb-4 flex items-center gap-1 whitespace-nowrap overflow-hidden text-ellipsis" style="color: var(--pc-price);">
                                                    <svg class="w-3 h-3 md:w-3.5 md:h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                                    <span>Min. Order: <?= htmlspecialchars($moq) ?> Pcs</span>
                                                </p>

                                                <!-- Row 6: CTA Button -->
                                                <form action="<?= BASE_URL ?>/cart/add" method="POST" onsubmit="ajaxAddToCart(event, this)">
                                                    <input type="hidden" name="product_id" value="<?= $prod['id'] ?>">
                                                    <input type="hidden" name="quantity" value="<?= htmlspecialchars($moq) ?>">
                                                    <button type="submit" class="product-btn w-full font-bold transition-colors flex items-center justify-center gap-1.5 md:gap-1.5 lg:gap-2 hover:opacity-90 shadow-sm whitespace-nowrap" style="padding: clamp(0.55rem, 1.8vw, 0.9rem); font-size: clamp(0.8125rem, 2.5vw, 1rem); border-radius: 0.75rem;">
                                                        <svg class="w-3.5 h-3.5 md:w-4 md:h-4 lg:w-5 lg:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                                        <span class="whitespace-nowrap">Add to Cart</span>
                                                    </button>
                                                </form>
                                            </div>
                                        <?php else: ?>
                                            <div class="mt-auto pt-2 md:pt-3 lg:pt-4 md:border-t md:border-gray-100">
                                                <a href="<?= BASE_URL ?>/login" class="flex items-center gap-1.5 text-[#b94a3e] font-semibold hover:underline whitespace-nowrap overflow-hidden text-ellipsis" style="font-size: clamp(0.65rem, 2.2vw, 0.8125rem);">
                                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                    <span class="truncate">Register to view price</span>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Arrows -->
            <?php if ($show_arrows && !empty($carouselProducts)): ?>
                <button id="prev_<?= $id ?>" class="carousel-btn absolute -translate-y-1/2 rounded-full flex items-center justify-center shadow-md border border-gray-200 hover:shadow-lg hover:scale-105 active:scale-95 transition-all disabled:opacity-0 disabled:cursor-not-allowed z-20" style="color: <?= htmlspecialchars($arrow_color) ?>; background-color: <?= htmlspecialchars($arrow_bg_color) ?>;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button id="next_<?= $id ?>" class="carousel-btn absolute -translate-y-1/2 rounded-full flex items-center justify-center shadow-md border border-gray-200 hover:shadow-lg hover:scale-105 active:scale-95 transition-all disabled:opacity-0 disabled:cursor-not-allowed z-20" style="color: <?= htmlspecialchars($arrow_color) ?>; background-color: <?= htmlspecialchars($arrow_bg_color) ?>;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                </button>
            <?php endif; ?>
        </div>

        <!-- Dots -->
        <?php if ($show_dots && !empty($carouselProducts)): ?>
            <div class="mt-8 flex justify-center items-center gap-2" id="dots_<?= $id ?>">
                <!-- Dots generated by JS -->
            </div>
        <?php endif; ?>

        </div>
    </div>
</div>
     </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const track = document.getElementById('track_<?= $id ?>');
    const inner = document.getElementById('inner_<?= $id ?>');
    if (!inner || !track) return;

    const prevBtn = document.getElementById('prev_<?= $id ?>');
    const nextBtn = document.getElementById('next_<?= $id ?>');
    const dotsContainer = document.getElementById('dots_<?= $id ?>');
    const slides = inner.querySelectorAll('.slide-item');
    
    if (slides.length === 0) return;

    // Desktop/Tablet/Mobile items count configuration
    const mobileCount = <?= (int)$mobile_count ?>;
    const tabletCount = <?= (int)$tablet_count ?>;
    const desktopCount = <?= (int)$desktop_count ?>;
    
    function getItemsPerView() {
        if (window.innerWidth < 768) {
            return mobileCount;
        } else if (window.innerWidth < 1024) {
            return tabletCount;
        } else {
            return desktopCount;
        }
    }

    let currentIndex = 0;
    let itemsPerView = getItemsPerView();
    let maxIndex = Math.max(0, slides.length - itemsPerView);

    function updateSlider() {
        let slideWidth = 0;
        if (slides.length > 1) {
            slideWidth = slides[1].getBoundingClientRect().left - slides[0].getBoundingClientRect().left;
        } else {
            slideWidth = slides[0].getBoundingClientRect().width;
        }
        
        inner.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
        
        // Update Buttons
        if (prevBtn) {
            prevBtn.disabled = currentIndex === 0;
            prevBtn.style.opacity = '1';
            prevBtn.style.visibility = 'visible';
            prevBtn.style.display = 'flex';
        }
        if (nextBtn) {
            nextBtn.disabled = currentIndex >= maxIndex;
            nextBtn.style.opacity = '1';
            nextBtn.style.visibility = 'visible';
            nextBtn.style.display = 'flex';
        }
        
        // Update Dots
        if (dotsContainer) {
            const dots = dotsContainer.querySelectorAll('.slider-dot');
            dots.forEach((dot, idx) => {
                if (idx === currentIndex) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
        }
    }

    function initDots() {
        if (!dotsContainer) return;
        dotsContainer.innerHTML = '';
        if (maxIndex > 0) {
            dotsContainer.style.display = 'flex';
            for (let i = 0; i <= maxIndex; i++) {
                const dot = document.createElement('button');
                dot.className = 'slider-dot' + (i === currentIndex ? ' active' : '');
                dot.setAttribute('aria-label', `Slide ${i + 1}`);
                dot.onclick = () => {
                    currentIndex = i;
                    updateSlider();
                };
                dotsContainer.appendChild(dot);
            }
        } else {
            dotsContainer.style.display = 'none';
        }
    }

    initDots();

    if (prevBtn) {
        prevBtn.onclick = () => {
            if (currentIndex > 0) {
                currentIndex--;
                updateSlider();
            }
        };
    }

    if (nextBtn) {
        nextBtn.onclick = () => {
            if (currentIndex < maxIndex) {
                currentIndex++;
                updateSlider();
            }
        };
    }

    // Handle Resize
    window.addEventListener('resize', () => {
        const newItemsPerView = getItemsPerView();
        if (newItemsPerView !== itemsPerView) {
            itemsPerView = newItemsPerView;
            maxIndex = Math.max(0, slides.length - itemsPerView);
            currentIndex = Math.min(currentIndex, maxIndex);
            initDots();
            updateSlider();
        } else {
            updateSlider();
        }
        manageAutoPlay();
    });

    updateSlider();

    // Auto-play logic for mobile only
    let autoPlayInterval;

    function manageAutoPlay() {
        if (autoPlayInterval) clearInterval(autoPlayInterval);
        
        // Only autoplay on mobile (width < 768px) and if there are slides to move
        if (window.innerWidth < 768 && maxIndex > 0) {
            autoPlayInterval = setInterval(() => {
                if (currentIndex < maxIndex) {
                    currentIndex++;
                } else {
                    currentIndex = 0;
                }
                updateSlider();
            }, 3000); // 3 seconds
        }
    }

    // Pause auto-play on user interaction
    track.addEventListener('touchstart', () => { if (autoPlayInterval) clearInterval(autoPlayInterval); }, {passive: true});
    track.addEventListener('touchend', manageAutoPlay);
    track.addEventListener('mouseenter', () => { if (autoPlayInterval) clearInterval(autoPlayInterval); });
    track.addEventListener('mouseleave', manageAutoPlay);

    // Initialize autoplay
    manageAutoPlay();
});
</script>
