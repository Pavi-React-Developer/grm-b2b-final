<?php
// Extracted from $contentData which is passed in via home.php
$id = uniqid('cat_grid_');
$title = $contentData['title'] ?? '';
$sort_order = $contentData['sort_order'] ?? 0;
$mobile_count = $contentData['mobile_count'] ?? 3;
$desktop_count = $contentData['desktop_count'] ?? 6;
$cta_text = $contentData['cta_text'] ?? '';
$cta_url = $contentData['cta_url'] ?? '';
$cta_position = $contentData['cta_position'] ?? 'Right';
$category_ids = $contentData['category_ids'] ?? [];
$theme = $contentData['theme'] ?? ['type' => 'solid', 'value' => '#ffffff'];

$title_color = $contentData['title_color'] ?? '#111827';
$text_color = $contentData['text_color'] ?? '#111827';
$arrow_color = $contentData['arrow_color'] ?? '#ffffff';
$arrow_bg_color = $contentData['arrow_bg_color'] ?? '#ffffff';
$dot_color = $contentData['dot_color'] ?? '#8c6a4f';
$button_bg_color = $contentData['button_bg_color'] ?? '#8c6a4f';
$button_text_color = $contentData['button_text_color'] ?? '#ffffff';
$cta_bg_color = $contentData['cta_bg_color'] ?? '#8c6a4f';
$cta_text_color = $contentData['cta_text_color'] ?? '#ffffff';
$wishlist_color = $contentData['wishlist_color'] ?? '#2563eb';
$subtitle_color = $contentData['subtitle_color'] ?? '#6b7280';
$price_color = $contentData['price_color'] ?? '#1d4ed8';
// Background styling
$bgStyle = '';
if (!empty($theme['value'])) {
    if (($theme['type'] ?? '') === 'css') {
        $bgStyle = strpos($theme['value'], 'background') !== false ? $theme['value'] : 'background: ' . $theme['value'] . ';';
    } else {
        $bgStyle = 'background: ' . $theme['value'] . ';';
    }
}

$image_fit = $contentData['image_fit'] ?? 'cover';
$desktop_height = $contentData['desktop_height'] ?? '80';
$mobile_height = $contentData['mobile_height'] ?? '50';

// Fetch grid categories
$gridCategories = [];
if (isset($categories) && is_array($categories)) {
    foreach ($categories as $c) {
        if (in_array($c['id'], $category_ids)) {
            $gridCategories[] = $c;
        }
    }
}

// Fetch products for these categories
$db = \Core\Database::getInstance();
$catProducts = [];
$catProductCounts = [];
if (!empty($category_ids)) {
    // We fetch products for these categories. Note that there might be no category_id in products table directly if it's many-to-many, but in this schema it seems to be category_id.
    // Let's check if category_id exists. It usually does.
    $ids = implode(',', array_map('intval', $category_ids));
    try {
        $stmt = $db->query("
            SELECT p.*, 
                   c.sgst as category_sgst, c.cgst as category_cgst, c.moq as category_moq,
                   (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as main_image,
                   pv_agg.base_price,
                   pv_agg.discount_price
            FROM products p 
            LEFT JOIN categories c ON c.id = p.category_id
            LEFT JOIN (
                SELECT product_id,
                       MIN(base_price) AS base_price,
                       MIN(CASE WHEN discount_price > 0 THEN discount_price ELSE NULL END) AS discount_price
                FROM product_variants
                GROUP BY product_id
            ) pv_agg ON pv_agg.product_id = p.id
            WHERE p.category_id IN ($ids) AND p.status = 'active' 
            ORDER BY p.id DESC
        ");
        $productsRaw = $stmt->fetchAll();
        
        foreach ($productsRaw as $p) {
            $cId = $p['category_id'];
            if (!isset($catProducts[$cId])) {
                $catProducts[$cId] = [];
                $catProductCounts[$cId] = 0;
            }
            if (count($catProducts[$cId]) < 12) { // keep up to 12 for horizontal slider track
                $catProducts[$cId][] = $p;
            }
            $catProductCounts[$cId]++;
        }
    } catch (\Exception $e) {
        // Fallback or ignore if query fails (e.g. wrong schema)
    }
}
?>

<style>
    /* ---- Mobile-only overrides (max-width: 767px) ---- */
    @media (max-width: 767px) {
        #<?= $id ?> {
            padding-top: 0.35rem !important;
            padding-bottom: 0.35rem !important;
        }
        #<?= $id ?> .catgrid-outer-wrap {
            display: none !important;
        }
        /* Hide desktop sidebar on mobile */
        #<?= $id ?> .catgrid-sidebar {
            display: none !important;
        }
        /* Hide desktop product panes on mobile */
        #<?= $id ?> .catgrid-right {
            display: none !important;
        }
        #<?= $id ?> .catgrid-outer-container {
            padding-left: 0.25rem !important;
            padding-right: 0.25rem !important;
        }
        #<?= $id ?> .catgrid-bg-wrap {
            padding: 0.65rem 0.35rem 0.5rem 0.35rem !important;
            border-radius: 1.25rem !important;
        }
        #<?= $id ?> .catgrid-header-wrap {
            margin-bottom: 0.5rem !important;
            gap: 0.5rem !important;
        }

        #<?= $id ?> .catgrid-header-cta {
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
            border-width: 1px !important;
            white-space: nowrap !important;
            line-height: 1 !important;
        }

        /* Show mobile section */
        #<?= $id ?> .catgrid-mobile {
            display: block !important;
            width: 100%;
            overflow: hidden;
        }

        /* Mobile horizontal tab bar */
        #<?= $id ?> .mobile-tab-bar {
            display: flex;
            flex-direction: row;
            overflow-x: auto;
            gap: 0.3rem;
            padding: 0.2rem 0.1rem 0.35rem 0.1rem;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        #<?= $id ?> .mobile-tab-bar::-webkit-scrollbar { display: none; }

        #<?= $id ?> .mobile-cat-btn {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 0.35rem;
            padding: 0.25rem 0.6rem 0.25rem 0.3rem;
            border-radius: 9999px;
            border: 1px solid #e5e7eb;
            background: #fff;
            white-space: nowrap;
            cursor: pointer;
            flex-shrink: 0;
            transition: background 0.2s, color 0.2s, border-color 0.2s;
        }
        #<?= $id ?> .mobile-cat-btn img {
            width: 1.35rem;
            height: 1.35rem;
            border-radius: 50%;
            object-fit: cover;
            border: 1.5px solid rgba(255,255,255,0.8);
        }
        #<?= $id ?> .mobile-cat-name {
            font-size: 0.6875rem;
            font-weight: 700;
            line-height: 1.1;
        }
        #<?= $id ?> .mobile-cat-count {
            font-size: 0.525rem;
            font-weight: 500;
            opacity: 0.8;
            line-height: 1;
        }

        /* Mobile horizontal scroll product track - show 2 cards */
        #<?= $id ?> .mobile-product-grid {
            display: flex !important;
            flex-direction: row !important;
            overflow-x: auto !important;
            gap: 0.5rem !important;
            padding: 0.35rem 0 0.5rem 0 !important;
            width: 100% !important;
            scroll-snap-type: x mandatory !important;
            -webkit-overflow-scrolling: touch !important;
            scrollbar-width: none !important;
            -ms-overflow-style: none !important;
        }
        #<?= $id ?> .mobile-product-grid::-webkit-scrollbar { display: none !important; }

        /* Mobile product card - show 2 cards */
        #<?= $id ?> .mobile-product-card {
            background: var(--pc-bg, #fff);
            border-radius: 1.25rem;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            display: flex;
            flex-direction: column;
            gap: 0;
            position: relative;
            flex-shrink: 0 !important;
            width: calc(50% - 0.25rem) !important;
            min-width: calc(50% - 0.25rem) !important;
            max-width: calc(50% - 0.25rem) !important;
            scroll-snap-align: start !important;
        }
        #<?= $id ?> .mobile-product-card .mobile-img-wrap {
            width: 100%;
            aspect-ratio: 4 / 5;
            overflow: hidden;
            background: #f3f4f6;
            flex-shrink: 0;
            display: block;
            line-height: 0;
            font-size: 0;
        }
        #<?= $id ?> .mobile-product-card .mobile-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: <?= htmlspecialchars($image_fit) ?>;
            display: block;
            max-width: 100%;
        }
        #<?= $id ?> .mobile-product-card .mobile-card-body {
            padding: 0.55rem 0.45rem 0.65rem 0.45rem !important;
            margin-top: -1rem !important;
            border-radius: 1.25rem 1.25rem 0 0 !important;
            display: flex !important;
            flex-direction: column !important;
            flex: 1 1 auto !important;
            background: var(--pc-bg, #fff) !important;
            justify-content: flex-start !important;
        }
        #<?= $id ?> .mobile-product-card .mobile-card-body .mt-auto {
            margin-top: 0 !important;
            display: flex !important;
            flex-direction: column !important;
            flex: 1 1 auto !important;
        }
        #<?= $id ?> .mobile-product-card .mobile-card-body form {
            margin-top: auto !important;
        }
        #<?= $id ?> .mobile-product-card .mobile-card-name {
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            line-height: 1.2 !important;
            display: -webkit-box !important;
            -webkit-line-clamp: 2 !important;
            -webkit-box-orient: vertical !important;
            overflow: hidden !important;
            margin-bottom: 0.2rem !important;
            color: var(--pc-name, #1a50a8) !important;
            min-height: auto !important;
        }
        #<?= $id ?> .mobile-product-card .mobile-card-price {
            font-size: 0.85rem !important;
            font-weight: 800 !important;
            color: var(--pc-price, #1a50a8) !important;
        }
        #<?= $id ?> .mobile-product-card .mobile-card-btn {
            width: 100% !important;
            padding: 0.35rem 0.2rem !important;
            font-size: 0.625rem !important;
            font-weight: 700 !important;
            border-radius: 0.5rem !important;
            border: none !important;
            cursor: pointer !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0.15rem !important;
            background-color: <?= htmlspecialchars($button_bg_color) ?> !important;
            color: <?= htmlspecialchars($button_text_color) ?> !important;
            transition: opacity 0.2s !important;
            line-height: 1.2 !important;
            white-space: nowrap !important;
        }
        #<?= $id ?> .mobile-product-card .mobile-card-btn svg {
            width: 0.6875rem !important;
            height: 0.6875rem !important;
            flex-shrink: 0 !important;
        }
        #<?= $id ?> .mobile-product-card .mobile-card-btn span {
            font-size: inherit !important;
            white-space: nowrap !important;
        }
        #<?= $id ?> .mobile-badge {
            position: absolute;
            top: 0.5rem;
            left: 0.5rem;
            background: #2d6a4f;
            color: #fff;
            font-size: 0.6rem;
            font-weight: 800;
            padding: 0.2rem 0.35rem;
            border-radius: 0.4rem;
            line-height: 1.2;
            z-index: 5;
        }
    }

    /* Desktop layout & horizontal slider track */
    @media (min-width: 768px) {
        #<?= $id ?> .product-img-container {
            height: 275px;
            max-height: 295px;
        }
        #<?= $id ?> .catgrid-mobile {
            display: none !important;
        }
        #<?= $id ?> .catgrid-header-cta {
            min-height: 2.5rem !important;
            padding: 0.5rem 1.5rem !important;
            font-size: 0.875rem !important;
            border-width: 1.5px !important;
        }
        #<?= $id ?> {
            min-height: 0 !important;
            height: auto !important;
        }
        #<?= $id ?> .catgrid-desktop-track {
            display: flex !important;
            flex-direction: row !important;
            overflow-x: auto !important;
            gap: 1.25rem !important;
            scroll-snap-type: x mandatory !important;
            -webkit-overflow-scrolling: touch !important;
            scrollbar-width: none !important;
            -ms-overflow-style: none !important;
            padding: 0.25rem 0.25rem 0.5rem 0.25rem !important;
            width: 100% !important;
        }
        #<?= $id ?> .catgrid-desktop-track::-webkit-scrollbar {
            display: none !important;
        }
        #<?= $id ?> .catgrid-slide-card {
            flex-shrink: 0 !important;
            width: 250px !important;
            min-width: 250px !important;
            max-width: 250px !important;
            scroll-snap-align: start !important;
        }
        #<?= $id ?> .catgrid-sidebar-scroll {
            max-height: 435px !important;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }
        #<?= $id ?> .catgrid-sidebar-scroll::-webkit-scrollbar {
            width: 5px;
        }
        #<?= $id ?> .catgrid-sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        #<?= $id ?> .catgrid-sidebar-scroll::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 9999px;
        }
        #<?= $id ?> .catgrid-sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background-color: #94a3b8;
        }
    }
</style>

<div class="w-full relative py-3 sm:py-4 md:py-6" id="<?= $id ?>">
    <div class="catgrid-outer-container w-full max-w-full mx-auto px-1 sm:px-6 lg:px-8">
        <div class="catgrid-bg-wrap rounded-2xl md:rounded-[2rem] p-2 sm:p-4 md:p-6 lg:p-8 relative" style="<?= htmlspecialchars($bgStyle) ?>">

        <!-- Header -->
        <?php if (!empty(trim($title)) || (!empty(trim($cta_text)) && !empty(trim($cta_url)))): ?>
        <div class="catgrid-header-wrap flex flex-col md:flex-row items-start md:items-center justify-between gap-2 md:gap-3 mb-3 md:mb-6">
            <?php if (!empty(trim($title))): ?>
            <h2 class="text-2xl md:text-3xl font-serif font-bold" style="color: <?= htmlspecialchars($title_color) ?>;">
                <?= htmlspecialchars($title) ?>
            </h2>
            <?php endif; ?>
            <?php if (!empty(trim($cta_text)) && !empty(trim($cta_url))): ?>
            <?php 
                $ctaTextColor = (!empty($cta_text_color) && $cta_text_color !== 'transparent') ? $cta_text_color : '#ffffff';
                $ctaBgColor = (!empty($cta_bg_color) && $cta_bg_color !== '#ffffff' && $cta_bg_color !== '#8c6a4f' && $cta_bg_color !== $ctaTextColor) ? $cta_bg_color : 'transparent';
            ?>
            <a href="<?= htmlspecialchars($cta_url) ?>" class="catgrid-header-cta self-end md:self-auto text-xs md:text-sm font-bold tracking-wider uppercase px-6 py-2 rounded-full transition-all hover:opacity-80 inline-flex items-center justify-center" style="border: 1.5px solid <?= htmlspecialchars($ctaTextColor) ?>; background-color: <?= htmlspecialchars($ctaBgColor) ?>; color: <?= htmlspecialchars($ctaTextColor) ?>;">
                <?= htmlspecialchars($cta_text) ?>
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- ========== DESKTOP LAYOUT: Sidebar + Product Grid ========== -->
        <div class="catgrid-outer-wrap" style="display:flex; flex-direction:row; align-items:stretch; border-radius:1.25rem; overflow:hidden; background:#ffffff; box-shadow:0 4px 20px rgba(0,0,0,0.06); border:1px solid rgba(229,231,235,0.8);">
            
            <!-- Left Sidebar: Categories List (desktop only) -->
            <div class="catgrid-sidebar" style="width:240px; min-width:240px; background:#f8fafc; border-right:1px solid #e2e8f0; flex-shrink:0; display:flex; flex-direction:column; justify-content:flex-start;">
                <div class="catgrid-sidebar-scroll" style="display:flex; flex-direction:column; max-height:435px; overflow-y:auto; overflow-x:hidden;">
                    <?php foreach ($gridCategories as $index => $cat): 
                        $img = !empty($cat['image_path']) ? (str_starts_with($cat['image_path'], 'http') ? $cat['image_path'] : (BASE_URL . '/' . ltrim($cat['image_path'], '/'))) : 'https://placehold.co/100?text=' . urlencode($cat['name']);
                        $count = $catProductCounts[$cat['id']] ?? 0;
                        $isActive = $index === 0;
                    ?>
                    <button class="cat-tab-btn" 
                            data-target="cat_pane_<?= $id ?>_<?= $cat['id'] ?>"
                            style="display:flex; align-items:center; gap:0.85rem; padding:0.85rem 1.1rem; width:100%; height:72px; box-sizing:border-box; flex-shrink:0; text-align:left; transition:all 0.2s; border-bottom:1px solid #e2e8f0; cursor:pointer; background:none; border-left:none; border-right:none; border-top:none; <?= $isActive ? 'background-color: '.$button_bg_color.'; color: '.$button_text_color.';' : 'color:#334155;' ?>">
                        <div style="width:2.75rem; height:2.75rem; border-radius:0.5rem; overflow:hidden; flex-shrink:0; background:#fff; box-shadow:0 1px 2px rgba(0,0,0,0.06); border:1px solid #f1f5f9;">
                            <img src="<?= htmlspecialchars($img) ?>" style="width:100%; height:100%; object-fit:cover;" loading="lazy" decoding="async">
                        </div>
                        <div style="flex:1; min-width:0;">
                            <h4 class="tab-title" style="font-weight:700; font-size:0.875rem; color:inherit; margin:0 0 0.2rem 0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"><?= htmlspecialchars($cat['name']) ?></h4>
                            <p class="tab-count" style="font-size:0.75rem; margin:0; <?= $isActive ? 'color:rgba(255,255,255,0.8);' : 'color:#64748b;' ?>"><?= $count ?> products</p>
                        </div>
                        <div class="tab-dot" style="width:0.45rem; height:0.45rem; border-radius:9999px; background:#fff; flex-shrink:0; <?= $isActive ? 'opacity:1;' : 'opacity:0;' ?>"></div>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Right Area: Products Horizontal Slider Track (desktop only) -->
            <div class="catgrid-right relative" style="flex:1; padding:1.25rem 1.5rem; background:#ffffff; overflow:hidden; min-width:0; display:flex; flex-direction:column; justify-content:center;">
                <?php foreach ($gridCategories as $index => $cat): 
                    $isActive = $index === 0;
                    $gridPaneProducts = $catProducts[$cat['id']] ?? [];
                    $paneId = "cat_pane_{$id}_{$cat['id']}";
                ?>
                <div id="<?= $paneId ?>" class="cat-pane relative transition-opacity duration-300 <?= $isActive ? 'block opacity-100' : 'hidden opacity-0' ?>">
                    <?php if (empty($gridPaneProducts)): ?>
                        <div class="flex items-center justify-center h-full text-gray-400 font-medium py-16">
                            No products found in this category.
                        </div>
                    <?php else: ?>
                        <!-- Slider Controls (Left / Right Arrow buttons) -->
                        <?php if (count($gridPaneProducts) > 2): ?>
                        <button type="button" onclick="scrollCatGrid('<?= $paneId ?>_track', -1)" class="catgrid-nav-btn catgrid-prev-btn absolute -left-2 top-1/2 -translate-y-1/2 z-20 w-9 h-9 rounded-full bg-white/95 border border-gray-200 shadow-md flex items-center justify-center text-[#F25996] hover:text-[#e04481] hover:scale-105 transition-all focus:outline-none" title="Previous">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button type="button" onclick="scrollCatGrid('<?= $paneId ?>_track', 1)" class="catgrid-nav-btn catgrid-next-btn absolute -right-2 top-1/2 -translate-y-1/2 z-20 w-9 h-9 rounded-full bg-white/95 border border-gray-200 shadow-md flex items-center justify-center text-[#F25996] hover:text-[#e04481] hover:scale-105 transition-all focus:outline-none" title="Next">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <?php endif; ?>

                        <!-- Horizontal Slider Track -->
                        <div id="<?= $paneId ?>_track" class="catgrid-desktop-track flex flex-row gap-4 overflow-x-auto scroll-smooth py-1 px-1" style="scrollbar-width: none; -ms-overflow-style: none; scroll-snap-type: x mandatory;">
                            <?php foreach ($gridPaneProducts as $prod): 
                                $imgPath = $prod['main_image'] ?? '';
                                $img = !empty($imgPath) ? (str_starts_with($imgPath, 'http') ? $imgPath : (BASE_URL . '/' . ltrim($imgPath, '/'))) : 'https://placehold.co/400?text=No+Image';
                                $catGst = (float)($prod['category_sgst'] ?? 0) + (float)($prod['category_cgst'] ?? 0);
                                $prodGst = $catGst > 0 ? $catGst : ((float)($prod['variant_total_gst'] ?? 0) ?: (float)($prod['total_gst'] ?? 0));
                                $basePrice = (isset($prod['base_price']) && $prod['base_price'] > 0) ? (float)$prod['base_price'] : (float)($prod['wholesale_price'] ?? 0);
                                $discountPrice = !empty($prod['discount_price']) && (float)$prod['discount_price'] > 0 ? (float)$prod['discount_price'] : null;
                                $effPrice = $discountPrice ?? $basePrice;
                                $displayPrice = $effPrice;
                                $displayOriginalPrice = null;
                                if ($discountPrice && $basePrice > $discountPrice) {
                                    $displayOriginalPrice = $basePrice;
                                }
                                $moq = !empty($prod['moq_override']) ? $prod['moq_override'] : ($prod['category_moq'] ?? ($prod['moq'] ?? 1));
                                $isLoggedIn = \Core\Session::get('user_id') !== null;
                            ?>
                            <div class="product-card catgrid-slide-card bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow border border-gray-200 flex flex-col h-full relative group shrink-0" style="width: 250px; min-width: 250px; max-width: 250px; scroll-snap-align: start;">
                                
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
                                <a href="<?= BASE_URL ?>/product?id=<?= $prod['id'] ?>" class="product-image-wrap block w-full bg-gray-100 overflow-hidden shrink-0 product-img-container relative">
                                    <?php 
                                    $discPercent = ($displayOriginalPrice && $displayOriginalPrice > $displayPrice) ? round((($displayOriginalPrice - $displayPrice) / $displayOriginalPrice) * 100) : 0;
                                    if ($isLoggedIn && $discPercent > 0) {
                                        echo render_discount_starburst($discPercent, 'absolute top-2 left-2 z-10 w-16 h-16 md:w-20 md:h-20 transition-transform duration-300 group-hover:scale-110');
                                    }
                                    ?>
                                    <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($prod['name']) ?>" class="w-full h-full object-<?= htmlspecialchars($image_fit) ?> group-hover:scale-105 transition-transform duration-500" loading="lazy" decoding="async" style="object-fit: <?= htmlspecialchars($image_fit ?: 'cover') ?>; width: 100%; height: 100%; display: block;">
                                </a>

                                <!-- Product Details -->
                                <div class="product-card-body p-4 md:p-5 flex flex-col flex-1 bg-white" style="position: relative; z-index: 10;">
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

                                    <a href="<?= BASE_URL ?>/product?id=<?= $prod['id'] ?>" class="product-link block mb-1.5">
                                        <h3 class="product-name font-bold leading-snug line-clamp-2" style="font-size: 1.15rem; color: <?= htmlspecialchars($text_color) ?>;"><?= htmlspecialchars($prod['name']) ?></h3>
                                    </a>
                                    
                                    <?php if (!empty($prod['sku'])): ?>
                                        <p class="font-medium mb-3" style="font-size: 0.875rem; color: <?= htmlspecialchars($subtitle_color) ?>;">SKU: <?= htmlspecialchars($prod['sku']) ?></p>
                                    <?php endif; ?>

                                    <?php if ($isLoggedIn): ?>
                                        <div class="mt-auto">
                                            <div class="product-price-row flex items-baseline gap-1.5 flex-wrap mb-2">
                                                <span class="product-price font-black tracking-tight" style="font-size: clamp(1.15rem, 3.5vw, 1.4rem);">₹<?= format_price($displayPrice) ?></span>
                                                <?php if ($displayOriginalPrice && $displayOriginalPrice > $displayPrice): ?>
                                                    <span class="product-orig-price text-sm font-bold text-gray-400 line-through">₹<?= format_price($displayOriginalPrice) ?></span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <p class="text-xs font-semibold mb-3 flex items-center gap-1" style="color: var(--pc-price);">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                                Min. Order: <?= htmlspecialchars($moq) ?> Pcs
                                            </p>

                                            <form action="<?= BASE_URL ?>/cart/add" method="POST" onsubmit="ajaxAddToCart(event, this)">
                                                <input type="hidden" name="product_id" value="<?= $prod['id'] ?>">
                                                <input type="hidden" name="quantity" value="<?= htmlspecialchars($moq) ?>">
                                                <button type="submit" class="product-btn w-full font-bold transition-colors flex items-center justify-center gap-2 hover:opacity-90 shadow-sm" style="padding: 0.75rem 1rem; font-size: 0.9375rem; border-radius: 0.75rem; background-color: <?= htmlspecialchars($button_bg_color) ?>; color: <?= htmlspecialchars($button_text_color) ?>;">
                                                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                                    Add to Cart
                                                </button>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                        <div class="mt-auto pt-2 border-t border-gray-100">
                                            <a href="<?= BASE_URL ?>/login" class="flex items-center gap-1.5 text-[#b94a3e] font-semibold py-1 text-xs md:text-[11px] lg:text-xs hover:underline leading-tight whitespace-nowrap overflow-hidden text-ellipsis">
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                <span class="truncate">Register to view price</span>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <!-- ========== END DESKTOP LAYOUT ========== -->

        <!-- ========== MOBILE LAYOUT: Horizontal tabs + 2-col product grid ========== -->
        <div class="catgrid-mobile" style="display:none;">
            <!-- Horizontal Scrollable Tab Bar -->
            <div class="mobile-tab-bar">
                <?php foreach ($gridCategories as $index => $cat): 
                    $img = !empty($cat['image_path']) ? (str_starts_with($cat['image_path'], 'http') ? $cat['image_path'] : (BASE_URL . '/' . ltrim($cat['image_path'], '/'))) : 'https://placehold.co/100?text=' . urlencode($cat['name']);
                    $count = $catProductCounts[$cat['id']] ?? 0;
                    $isFirst = $index === 0;
                ?>
                <button class="mobile-cat-btn <?= $isFirst ? 'mobile-active-btn' : '' ?>"
                        data-mobile-target="mob_pane_<?= $id ?>_<?= $cat['id'] ?>"
                        style="<?= $isFirst ? 'background-color:'.$button_bg_color.'; color:'.$button_text_color.'; border-color:'.$button_bg_color.';' : '' ?>">
                    <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($cat['name']) ?>">
                    <div style="text-align:left; line-height:1.15;">
                        <div class="mobile-cat-name"><?= htmlspecialchars($cat['name']) ?></div>
                        <div class="mobile-cat-count"><?= $count ?> products</div>
                    </div>
                </button>
                <?php endforeach; ?>
            </div>

            <!-- Mobile Product Panes -->
            <?php foreach ($gridCategories as $index => $cat): 
                $isFirst = $index === 0;
                $gridMobProducts = $catProducts[$cat['id']] ?? [];
            ?>
            <div id="mob_pane_<?= $id ?>_<?= $cat['id'] ?>" class="mob-cat-pane" style="<?= $isFirst ? '' : 'display:none;' ?>">
                <?php if (empty($gridMobProducts)): ?>
                    <div style="text-align:center; padding:2rem 1rem; color:#9ca3af; font-weight:600;">No products in this category.</div>
                <?php else: ?>
                <div class="mobile-product-grid">
                    <?php foreach ($gridMobProducts as $prod): 
                        $imgPath = $prod['main_image'] ?? '';
                        $img = !empty($imgPath) ? (str_starts_with($imgPath, 'http') ? $imgPath : (BASE_URL . '/' . ltrim($imgPath, '/'))) : 'https://placehold.co/400?text=' . urlencode($prod['name']);
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
                    <div class="product-card mobile-product-card">
                        <!-- Wishlist -->
                        <?php if ($isLoggedIn): ?>
                        <?php 
                            $inWishlist = false;
                            if (!empty($wishlistIds) && is_array($wishlistIds)) {
                                $inWishlist = in_array($prod['id'], $wishlistIds);
                            } elseif (!empty($wishlistItems) && is_array($wishlistItems)) {
                                $inWishlist = in_array($prod['id'], array_column($wishlistItems, 'product_id')) || in_array($prod['id'], array_column($wishlistItems, 'id'));
                            }
                        ?>
                        <button onclick="toggleWishlist(<?= $prod['id'] ?>, this)" data-wishlist-btn-id="<?= $prod['id'] ?>" class="wishlist-btn <?= $inWishlist ? 'is-active' : '' ?> absolute top-2 right-2 w-7 h-7 hover:opacity-80 transition-opacity rounded-full flex items-center justify-center z-10 shadow-sm" style="background-color: <?= htmlspecialchars($wishlist_color) ?>;">
                            <svg class="w-3.5 h-3.5 <?= $inWishlist ? 'text-[#F25996] fill-[#F25996] is-active' : 'text-gray-400 fill-none' ?>" fill="<?= $inWishlist ? '#F25996' : 'none' ?>" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        </button>
                        <?php endif; ?>

                        <!-- Image -->
                        <a href="<?= BASE_URL ?>/product?id=<?= $prod['id'] ?>" class="mobile-img-wrap relative">
                                <?php 
                                $mobDiscPercent = ($displayOriginalPrice && $displayOriginalPrice > $displayPrice) ? round((($displayOriginalPrice - $displayPrice) / $displayOriginalPrice) * 100) : 0;
                                if ($isLoggedIn && $mobDiscPercent > 0) {
                                    echo render_discount_starburst($mobDiscPercent, 'absolute top-1.5 left-1.5 z-10 w-14 h-14');
                                }
                                ?>
                            <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($prod['name']) ?>" loading="lazy" decoding="async">
                        </a>

                        <!-- Body -->
                        <div class="product-card-body mobile-card-body">
                            <!-- Row 1: Product Name -->
                            <a href="<?= BASE_URL ?>/product?id=<?= $prod['id'] ?>" class="product-link block mb-1">
                                <h3 class="product-name mobile-card-name"><?= htmlspecialchars($prod['name']) ?></h3>
                            </a>
                            <?php if ($isLoggedIn): ?>
                            <div class="mt-auto flex flex-col justify-end">
                                <!-- Selling Price, Strikethrough & Discount Badge (Same Row) -->
                                <div class="product-price-row flex items-baseline gap-1.5 flex-wrap mb-1">
                                    <span class="product-price mobile-card-price">₹<?= format_price($displayPrice) ?></span>
                                    <?php if ($displayOriginalPrice && $displayOriginalPrice > $displayPrice): ?>
                                        <span class="product-orig-price text-[0.625rem] font-bold text-gray-400 line-through">₹<?= format_price($displayOriginalPrice) ?></span>
                                    <?php endif; ?>
                                </div>

                                <!-- Row 4: Minimum Order Count -->
                                <p class="product-moq-row text-[0.525rem] font-semibold mb-2 flex items-center gap-1 whitespace-nowrap overflow-hidden text-ellipsis" style="color: var(--pc-price);">
                                    <svg class="w-2.5 h-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    <span>Min. Order: <?= htmlspecialchars($moq) ?> Pcs</span>
                                </p>

                                <!-- Row 5: CTA Button -->
                                <form action="<?= BASE_URL ?>/cart/add" method="POST" onsubmit="ajaxAddToCart(event, this)">
                                    <input type="hidden" name="product_id" value="<?= $prod['id'] ?>">
                                    <input type="hidden" name="quantity" value="<?= htmlspecialchars($moq) ?>">
                                    <button type="submit" class="product-btn mobile-card-btn">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        <span class="whitespace-nowrap">Add to Cart</span>
                                    </button>
                                </form>
                            </div>
                            <?php else: ?>
                            <a href="<?= BASE_URL ?>/login" style="font-size:0.75rem; color:#b94a3e; font-weight:600; margin-top:auto; display:block; padding-top:0.3rem;">Login to view price</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <!-- ========== END MOBILE LAYOUT ========== -->

        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('<?= $id ?>');
    if (!container) return;

    const tabs = container.querySelectorAll('.cat-tab-btn');
    const panes = container.querySelectorAll('.cat-pane');
    
    // Theme colors passed from PHP
    const activeBgColor = '<?= $button_bg_color ?>';
    const activeTextColor = '<?= $button_text_color ?>';

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active states from all tabs
            tabs.forEach(t => {
                t.classList.remove('active-tab');
                t.classList.add('hover:bg-gray-50');
                t.style.backgroundColor = '';
                t.style.color = '';
                
                const title = t.querySelector('.tab-title');
                if (title) {
                    title.classList.remove('text-white');
                    title.classList.add('text-gray-800');
                    title.style.color = '';
                }
                
                const count = t.querySelector('.tab-count');
                if (count) {
                    count.classList.remove('opacity-80', 'text-white');
                    count.classList.add('text-gray-500');
                    count.style.color = '';
                }

                const dot = t.querySelector('.tab-dot');
                if (dot) dot.classList.add('opacity-0');
            });

            // Add active state to clicked tab
            this.classList.add('active-tab');
            this.classList.remove('hover:bg-gray-50');
            this.style.backgroundColor = activeBgColor;
            this.style.color = activeTextColor;

            const title = this.querySelector('.tab-title');
            if (title) {
                title.classList.remove('text-gray-800');
                title.style.color = activeTextColor;
            }

            const count = this.querySelector('.tab-count');
            if (count) {
                count.classList.remove('text-gray-500');
                count.classList.add('opacity-80');
                count.style.color = activeTextColor;
            }
            
            const dot = this.querySelector('.tab-dot');
            if (dot) dot.classList.remove('opacity-0');

            // Hide all panes
            panes.forEach(pane => {
                pane.classList.remove('block', 'opacity-100');
                pane.classList.add('hidden', 'opacity-0');
            });

            // Show target pane
            const targetId = this.getAttribute('data-target');
            const targetPane = document.getElementById(targetId);
            if (targetPane) {
                targetPane.classList.remove('hidden');
                setTimeout(() => {
                    targetPane.classList.remove('opacity-0');
                    targetPane.classList.add('opacity-100');
                }, 50);
            }
        });
    });

    // ---- Mobile Tab Switching ----
    const mobileBtns = container.querySelectorAll('.mobile-cat-btn');
    const mobilePanes = container.querySelectorAll('.mob-cat-pane');
    const activeBgColorMob = '<?= $button_bg_color ?>';
    const activeTextColorMob = '<?= $button_text_color ?>';

    mobileBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Reset all mobile buttons
            mobileBtns.forEach(b => {
                b.style.backgroundColor = '';
                b.style.color = '';
                b.style.borderColor = '#e5e7eb';
            });
            // Activate clicked
            this.style.backgroundColor = activeBgColorMob;
            this.style.color = activeTextColorMob;
            this.style.borderColor = activeBgColorMob;

            // Hide all mobile panes
            mobilePanes.forEach(p => p.style.display = 'none');

            // Show target mobile pane
            const targetId = this.getAttribute('data-mobile-target');
            const targetPane = document.getElementById(targetId);
            if (targetPane) targetPane.style.display = '';
        });
    });
});

function scrollCatGrid(trackId, direction) {
    const track = document.getElementById(trackId);
    if (!track) return;
    const scrollAmount = 266 * 2; // card width + gap
    track.scrollBy({
        left: direction * scrollAmount,
        behavior: 'smooth'
    });
}
</script>
