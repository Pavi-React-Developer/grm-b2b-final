<?php
// Global Product Card Settings - CSS Variable Injector
// Include this in the <head> of main.php to apply product card styles globally.
$db = \Core\Database::getInstance();
$stmt = $db->prepare("SELECT setting_value FROM settings WHERE setting_key = 'product_card_settings'");
$stmt->execute();
$row = $stmt->fetch();
$pcs = $row ? (json_decode($row['setting_value'], true) ?? []) : [];

$d = $pcs['desktop'] ?? [];
$m = $pcs['mobile'] ?? [];

// Helper: convert value to CSS - if numeric treat as vh, if 'auto' use auto
function pcVh($val, $default = 'auto') {
    $v = trim($val ?? '');
    if ($v === '' || strtolower($v) === 'auto') return 'auto';
    if (is_numeric($v)) return $v . 'vh';
    return $v; // e.g. already has unit
}

function pcWidth($val, $default = 'auto') {
    $v = trim($val ?? '');
    if ($v === '' || strtolower($v) === 'auto') return 'auto';
    if (is_numeric($v)) return $v . 'vw';
    return $v;
}

$d_card_h    = pcVh($d['card_height'] ?? '');
$d_img_h     = pcVh($d['image_height'] ?? '');
$d_cont_h    = pcVh($d['container_height'] ?? '');
$d_cont_w    = pcWidth($d['container_width'] ?? '');
$d_bg        = htmlspecialchars(!empty($d['card_bg_color'])     ? $d['card_bg_color']     : '#ffffff');
$d_name      = htmlspecialchars(!empty($d['name_color'])        ? $d['name_color']        : '#111827');
$d_price     = htmlspecialchars(!empty($d['price_color'])       ? $d['price_color']       : '#111827');
$d_star      = htmlspecialchars(!empty($d['star_color'])        ? $d['star_color']        : '#f59e0b');
$d_btn_bg        = htmlspecialchars(!empty($d['button_bg_color'])   ? $d['button_bg_color']   : '#F25996');
$d_btn_txt       = htmlspecialchars(!empty($d['button_text_color']) ? $d['button_text_color'] : '#ffffff');
$d_wishlist_bg   = htmlspecialchars(!empty($d['wishlist_bg_color']) ? $d['wishlist_bg_color'] : '#ffffff');
$d_wishlist_icon = htmlspecialchars(!empty($d['wishlist_icon_color']) ? $d['wishlist_icon_color'] : '#111827');

$m_card_h        = pcVh($m['card_height'] ?? '');
$m_img_h         = pcVh($m['image_height'] ?? '');
$m_cont_h        = pcVh($m['container_height'] ?? '');
$m_cont_w        = pcWidth($m['container_width'] ?? '');
$m_bg            = htmlspecialchars(!empty($m['card_bg_color'])     ? $m['card_bg_color']     : '#ffffff');
$m_name          = htmlspecialchars(!empty($m['name_color'])        ? $m['name_color']        : '#111827');
$m_price         = htmlspecialchars(!empty($m['price_color'])       ? $m['price_color']       : '#111827');
$m_star          = htmlspecialchars(!empty($m['star_color'])        ? $m['star_color']        : '#f59e0b');
$m_btn_bg        = htmlspecialchars(!empty($m['button_bg_color'])   ? $m['button_bg_color']   : '#F25996');
$m_btn_txt       = htmlspecialchars(!empty($m['button_text_color']) ? $m['button_text_color'] : '#ffffff');
$m_wishlist_bg   = htmlspecialchars(!empty($m['wishlist_bg_color']) ? $m['wishlist_bg_color'] : '#ffffff');
$m_wishlist_icon = htmlspecialchars(!empty($m['wishlist_icon_color']) ? $m['wishlist_icon_color'] : '#111827');
?>
<style>
/* ===== Global Product Card CSS Variables ===== */
:root {
    /* Desktop defaults */
    --pc-card-height:    <?= $d_card_h ?>;
    --pc-image-height:   <?= $d_img_h ?>;
    --pc-cont-height:    <?= $d_cont_h ?>;
    --pc-cont-width:     <?= $d_cont_w ?>;
    --pc-bg:             <?= $d_bg ?>;
    --pc-name:           <?= $d_name ?>;
    --pc-price:          <?= $d_price ?>;
    --pc-star:           <?= $d_star ?>;
    --pc-btn-bg:         <?= $d_btn_bg ?>;
    --pc-btn-txt:        <?= $d_btn_txt ?>;
    --pc-wishlist-bg:   <?= $d_wishlist_bg ?>;
    --pc-wishlist-icon: <?= $d_wishlist_icon ?>;
}

/* Mobile override */
@media (max-width: 767px) {
    :root {
        --pc-card-height:    <?= $m_card_h ?>;
        --pc-image-height:   <?= $m_img_h ?>;
        --pc-cont-height:    <?= $m_cont_h ?>;
        --pc-cont-width:     <?= $m_cont_w ?>;
        --pc-bg:             <?= $m_bg ?>;
        --pc-name:           <?= $m_name ?>;
        --pc-price:          <?= $m_price ?>;
        --pc-star:           <?= $m_star ?>;
        --pc-btn-bg:         <?= $m_btn_bg ?>;
        --pc-btn-txt:        <?= $m_btn_txt ?>;
        --pc-wishlist-bg:   <?= $m_wishlist_bg ?>;
        --pc-wishlist-icon: <?= $m_wishlist_icon ?>;
    }
}

/* ===== Site-wide Product Card Styles from CMS Settings ===== */

/* 1. Card Container Background */
html body .product-card,
html body div[id] .product-card,
html body .mobile-product-card,
html body div[id] .mobile-product-card {
    background-color: var(--pc-bg) !important;
    border-radius: 1.5rem !important;
    box-sizing: border-box !important;
    max-width: 100% !important;
    display: flex !important;
    flex-direction: column !important;
    height: auto !important;
    min-height: 0 !important;
}

/* Default aspect ratio for product image when no explicit image height is set */
<?php if ($d_img_h === 'auto' || empty($d_img_h)): ?>
html body .product-card .product-image-wrap,
html body div[id] .product-card .product-image-wrap,
html body .product-card .product-img-container,
html body div[id] .product-card .product-img-container,
html body .mobile-product-card .mobile-img-wrap,
html body div[id] .mobile-product-card .mobile-img-wrap {
    aspect-ratio: 1 / 1 !important;
    width: 100% !important;
    flex-shrink: 0 !important;
}
<?php endif; ?>

/* 2. Card Body Background & Overlapping Curved Design (Desktop Only - Compact & Fully Fitted) */
@media (min-width: 768px) {
    html body .product-card,
    html body div[id] .product-card {
        border-radius: 1.25rem !important;
        overflow: hidden !important;
        height: 100% !important;
        min-height: 0 !important;
        display: flex !important;
        flex-direction: column !important;
        box-sizing: border-box !important;
        background-color: var(--pc-bg) !important;
    }
    html body .product-card .product-image-wrap,
    html body div[id] .product-card .product-image-wrap,
    html body .product-card .product-img-container,
    html body div[id] .product-card .product-img-container {
        aspect-ratio: 1 / 1 !important;
        width: 100% !important;
        height: auto !important;
        overflow: hidden !important;
        display: block !important;
        position: relative !important;
        flex-shrink: 0 !important;
    }
    html body .product-card .product-image-wrap img,
    html body div[id] .product-card .product-image-wrap img,
    html body .product-card .product-img-container img,
    html body div[id] .product-card .product-img-container img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        display: block !important;
    }
    html body .product-card .product-card-body,
    html body div[id] .product-card .product-card-body {
        background-color: var(--pc-bg) !important;
        margin-top: -1.25rem !important;
        border-radius: 1.25rem 1.25rem 0 0 !important;
        position: relative !important;
        z-index: 10 !important;
        flex: 1 1 auto !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: flex-start !important;
        gap: 0.2rem !important;
        padding: 0.65rem 0.75rem 0.75rem 0.75rem !important;
    }
    html body .product-card .product-card-body .mt-auto,
    html body div[id] .product-card .product-card-body .mt-auto,
    html body .product-card .product-card-body > .mt-auto,
    html body div[id] .product-card .product-card-body > .mt-auto {
        margin-top: 0.25rem !important;
        display: flex !important;
        flex-direction: column !important;
        width: 100% !important;
        gap: 0.15rem !important;
    }
    html body .product-card .product-card-body form,
    html body div[id] .product-card .product-card-body form {
        margin-top: 0.35rem !important;
        width: 100% !important;
    }
    /* Compact Desktop Typography & Spacing */
    html body .product-card .product-name,
    html body div[id] .product-card .product-name,
    html body .product-card .product-link h3,
    html body div[id] .product-card .product-link h3,
    html body .product-card h3 {
        font-size: 0.95rem !important;
        font-weight: 700 !important;
        line-height: 1.25 !important;
        margin-bottom: 0.15rem !important;
        min-height: auto !important;
        display: -webkit-box !important;
        -webkit-line-clamp: 2 !important;
        -webkit-box-orient: vertical !important;
        overflow: hidden !important;
    }
    html body .product-card .product-price-row,
    html body div[id] .product-card .product-price-row {
        display: flex !important;
        align-items: baseline !important;
        gap: 0.35rem !important;
        margin-bottom: 0.2rem !important;
        flex-wrap: wrap !important;
    }
    html body .product-card .product-price,
    html body div[id] .product-card .product-price {
        font-size: 1.15rem !important;
        font-weight: 800 !important;
        line-height: 1.2 !important;
    }
    html body .product-card .product-orig-price,
    html body div[id] .product-card .product-orig-price {
        font-size: 0.85rem !important;
        font-weight: 600 !important;
        color: #9ca3af !important;
        text-decoration: line-through !important;
    }
    html body .product-card .product-badge-discount,
    html body div[id] .product-card .product-badge-discount {
        font-size: 0.6875rem !important;
        font-weight: 800 !important;
        padding: 0.08rem 0.45rem !important;
        border-radius: 9999px !important;
        background-color: #F25996 !important;
        color: #ffffff !important;
        line-height: 1.2 !important;
        display: inline-block !important;
    }
    html body .product-card .product-moq-row,
    html body div[id] .product-card .product-moq-row {
        font-size: 0.75rem !important;
        font-weight: 600 !important;
        margin-bottom: 0.35rem !important;
        display: flex !important;
        align-items: center !important;
        gap: 0.25rem !important;
        line-height: 1.2 !important;
    }
    html body .product-card .product-moq-row svg,
    html body div[id] .product-card .product-moq-row svg {
        width: 0.875rem !important;
        height: 0.875rem !important;
        flex-shrink: 0 !important;
    }
    html body .product-card .product-btn,
    html body div[id] .product-card .product-btn,
    html body .product-card form button[type="submit"],
    html body div[id] .product-card form button[type="submit"] {
        padding: 0.45rem 0.65rem !important;
        font-size: 0.8rem !important;
        font-weight: 700 !important;
        border-radius: 0.625rem !important;
        width: 100% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0.35rem !important;
        line-height: 1.2 !important;
    }
    html body .product-card .product-btn svg,
    html body div[id] .product-card .product-btn svg,
    html body .product-card form button[type="submit"] svg,
    html body div[id] .product-card form button[type="submit"] svg {
        width: 1rem !important;
        height: 1rem !important;
        flex-shrink: 0 !important;
    }
    /* Compact Desktop Wishlist Button */
    html body .product-card button[onclick*="toggleWishlist"],
    html body div[id] button[onclick*="toggleWishlist"],
    html body .product-card .wishlist-btn,
    html body div[id] .wishlist-btn {
        width: 1.625rem !important;
        height: 1.625rem !important;
        min-width: 0 !important;
        min-height: 0 !important;
        top: 0.5rem !important;
        right: 0.5rem !important;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 !important;
    }
    html body .product-card button[onclick*="toggleWishlist"] svg,
    html body div[id] button[onclick*="toggleWishlist"] svg,
    html body .product-card .wishlist-btn svg,
    html body div[id] .wishlist-btn svg {
        width: 0.8rem !important;
        height: 0.8rem !important;
        stroke-width: 2 !important;
    }
}

/* 2. Mobile Clean Card Body Flow with Overlapping Curved Container */
@media (max-width: 767px) {
    html body .product-card,
    html body div[id] .product-card,
    html body .mobile-product-card,
    html body div[id] .mobile-product-card {
        border-radius: 1.25rem !important;
        overflow: hidden !important;
        height: 100% !important;
        min-height: 0 !important;
        display: flex !important;
        flex-direction: column !important;
    }
    html body .product-card .product-image-wrap,
    html body div[id] .product-card .product-image-wrap,
    html body .mobile-product-card .mobile-img-wrap,
    html body div[id] .mobile-product-card .mobile-img-wrap,
    html body .product-card .product-img-container,
    html body div[id] .product-card .product-img-container {
        aspect-ratio: 4 / 5 !important;
        height: auto !important;
        width: 100% !important;
        flex-shrink: 0 !important;
        overflow: hidden !important;
    }
    html body .product-card .product-image-wrap img,
    html body div[id] .product-card .product-image-wrap img,
    html body .mobile-product-card .mobile-img-wrap img,
    html body div[id] .mobile-product-card .mobile-img-wrap img,
    html body .product-card .product-img-container img,
    html body div[id] .product-card .product-img-container img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        display: block !important;
    }
    html body .product-card .product-card-body,
    html body div[id] .product-card .product-card-body,
    html body .mobile-product-card .mobile-card-body,
    html body div[id] .mobile-product-card .mobile-card-body {
        background-color: var(--pc-bg) !important;
        margin-top: -1rem !important;
        border-radius: 1.25rem 1.25rem 0 0 !important;
        position: relative !important;
        z-index: 10 !important;
        flex: 1 1 auto !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: flex-start !important;
        padding: 0.55rem 0.45rem 0.65rem 0.45rem !important;
    }
    html body .product-card .product-card-body > .mt-auto,
    html body div[id] .product-card .product-card-body > .mt-auto,
    html body .mobile-product-card .mobile-card-body > .mt-auto,
    html body div[id] .mobile-product-card .mobile-card-body > .mt-auto {
        margin-top: 0 !important;
        display: flex !important;
        flex-direction: column !important;
        flex: 1 1 auto !important;
    }
    html body .product-card .product-card-body form,
    html body div[id] .product-card .product-card-body form,
    html body .mobile-product-card .mobile-card-body form,
    html body div[id] .mobile-product-card .mobile-card-body form {
        margin-top: auto !important;
    }
    /* 6-Row Structured Mobile Product Card Styles */
    /* Row 1: Product Name */
    html body .product-card .product-name,
    html body div[id] .product-card .product-name,
    html body .mobile-product-card .mobile-card-name,
    html body div[id] .mobile-product-card .mobile-card-name,
    html body .product-card .product-link h3,
    html body div[id] .product-card .product-link h3,
    html body .product-card h3,
    html body .mobile-product-card h3 {
        font-size: 0.75rem !important; /* 12px */
        font-weight: 700 !important;
        line-height: 1.2 !important;
        margin-bottom: 0.2rem !important;
        color: var(--pc-name) !important;
        min-height: auto !important;
        display: -webkit-box !important;
        -webkit-line-clamp: 2 !important;
        -webkit-box-orient: vertical !important;
        overflow: hidden !important;
    }
    /* Row 2: Selling Price, Strikethrough & Discount Badge (Same Row) */
    html body .product-card .product-price-row,
    html body div[id] .product-card .product-price-row {
        display: flex !important;
        align-items: baseline !important;
        gap: 0.25rem !important;
        flex-wrap: wrap !important;
        margin-bottom: 0.15rem !important;
    }
    html body .product-card .product-price,
    html body div[id] .product-card .product-price,
    html body .mobile-product-card .mobile-card-price,
    html body div[id] .mobile-product-card .mobile-card-price {
        font-size: 0.85rem !important; /* ~13.5px */
        font-weight: 800 !important;
        color: var(--pc-price) !important;
        line-height: 1.2 !important;
    }
    html body .product-card .product-unit,
    html body .mobile-product-card .product-unit {
        display: none !important;
    }
    /* Discount Price & Badge Styling */
    html body .product-card .product-orig-price,
    html body div[id] .product-card .product-orig-price,
    html body .mobile-product-card .product-orig-price,
    html body div[id] .mobile-product-card .product-orig-price {
        font-size: 0.625rem !important; /* 10px */
        font-weight: 700 !important;
        color: #9ca3af !important;
        text-decoration: line-through !important;
        line-height: 1 !important;
    }
    html body .product-card .product-badge-discount,
    html body div[id] .product-card .product-badge-discount,
    html body .mobile-product-card .product-badge-discount,
    html body div[id] .mobile-product-card .product-badge-discount {
        font-size: 0.525rem !important; /* ~8.5px */
        font-weight: 800 !important;
        padding: 0.05rem 0.35rem !important;
        border-radius: 9999px !important;
        background-color: #F25996 !important;
        color: #ffffff !important;
        line-height: 1.2 !important;
        display: inline-block !important;
    }
    html body .product-card .product-orig-price-placeholder,
    html body div[id] .product-card .product-orig-price-placeholder,
    html body .mobile-product-card .product-orig-price-placeholder,
    html body div[id] .mobile-product-card .product-orig-price-placeholder {
        font-size: 0.625rem !important;
        line-height: 1 !important;
        visibility: hidden !important;
        display: inline-block !important;
    }
    /* Row 5: Minimum Order Count */
    html body .product-card .product-moq-row,
    html body div[id] .product-card .product-moq-row,
    html body .mobile-product-card .product-moq-row,
    html body div[id] .mobile-product-card .product-moq-row {
        font-size: 0.525rem !important; /* ~8.4px */
        font-weight: 600 !important;
        margin-bottom: 0.25rem !important;
        display: flex !important;
        align-items: center !important;
        gap: 0.15rem !important;
        line-height: 1.2 !important;
        white-space: nowrap !important;
        color: var(--pc-price) !important;
        letter-spacing: -0.01em !important;
    }
    html body .product-card .product-moq-row svg,
    html body div[id] .product-card .product-moq-row svg,
    html body .mobile-product-card .product-moq-row svg,
    html body div[id] .mobile-product-card .product-moq-row svg {
        width: 0.5875rem !important;
        height: 0.5875rem !important;
        flex-shrink: 0 !important;
    }
    html body .product-card .product-moq-row span,
    html body div[id] .product-card .product-moq-row span {
        white-space: nowrap !important;
        font-size: inherit !important;
    }
    /* Row 6: CTA Button */
    html body .product-card .product-btn,
    html body div[id] .product-card .product-btn,
    html body .mobile-product-card .mobile-card-btn,
    html body div[id] .mobile-product-card .mobile-card-btn,
    html body .product-card form button[type="submit"],
    html body div[id] .product-card form button[type="submit"],
    html body .product-card form button,
    html body div[id] .product-card form button {
        font-size: 0.625rem !important; /* 10px */
        font-weight: 700 !important;
        white-space: nowrap !important;
        flex-wrap: nowrap !important;
        padding: 0.35rem 0.2rem !important;
        border-radius: 0.5rem !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0.15rem !important;
        width: 100% !important;
        line-height: 1.2 !important;
    }
    html body .product-card .product-btn svg,
    html body div[id] .product-card .product-btn svg,
    html body .mobile-product-card .mobile-card-btn svg,
    html body div[id] .mobile-product-card .mobile-card-btn svg {
        width: 0.6875rem !important;
        height: 0.6875rem !important;
        flex-shrink: 0 !important;
    }
    html body .product-card .product-btn span,
    html body div[id] .product-card .product-btn span {
        font-size: inherit !important;
        white-space: nowrap !important;
    }
    /* Mobile compact wishlist button */
    html body .product-card button[onclick*="toggleWishlist"],
    html body div[id] button[onclick*="toggleWishlist"],
    html body .product-card .wishlist-btn,
    html body div[id] .wishlist-btn,
    html body .mobile-product-card button[onclick*="toggleWishlist"],
    html body div[id] .mobile-product-card button[onclick*="toggleWishlist"],
    html body .mobile-product-card .wishlist-btn {
        width: 1.375rem !important;
        height: 1.375rem !important;
        min-width: 0 !important;
        min-height: 0 !important;
        top: 0.35rem !important;
        right: 0.35rem !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 !important;
    }
    html body .product-card button[onclick*="toggleWishlist"] svg,
    html body div[id] button[onclick*="toggleWishlist"] svg,
    html body .product-card .wishlist-btn svg,
    html body div[id] .wishlist-btn svg,
    html body .mobile-product-card button[onclick*="toggleWishlist"] svg,
    html body div[id] .mobile-product-card button[onclick*="toggleWishlist"] svg,
    html body .mobile-product-card .wishlist-btn svg {
        width: 0.7rem !important;
        height: 0.7rem !important;
        stroke-width: 2 !important;
    }
}

/* 3. Product Card Height (only if explicitly set in vh/px, not auto) */
<?php if ($d_card_h !== 'auto' && !empty($d_card_h)): ?>
@media (min-width: 768px) {
    html body .product-card,
    html body div[id] .product-card {
        height: var(--pc-card-height) !important;
    }
}
<?php endif; ?>

/* 4. Image Container Height */
@media (min-width: 768px) {
    <?php if ($d_card_h !== 'auto' && !empty($d_card_h)): ?>
    html body .product-card .product-image-wrap,
    html body div[id] .product-card .product-image-wrap,
    html body .product-card .product-img-container,
    html body div[id] .product-card .product-img-container {
        flex: 1 1 auto !important;
        min-height: 0 !important;
        height: auto !important;
    }
    <?php elseif ($d_img_h !== 'auto' && !empty($d_img_h)): ?>
    html body .product-card .product-image-wrap,
    html body div[id] .product-card .product-image-wrap,
    html body .product-card .product-img-container,
    html body div[id] .product-card .product-img-container {
        height: var(--pc-image-height) !important;
    }
    <?php else: ?>
    html body .product-card .product-image-wrap,
    html body div[id] .product-card .product-image-wrap,
    html body .product-card .product-img-container,
    html body div[id] .product-card .product-img-container {
        aspect-ratio: 1 / 1 !important;
        height: auto !important;
    }
    <?php endif; ?>
}

/* 5. Product Name Color */
html body .product-card .product-name,
html body div[id] .product-card .product-name,
html body .mobile-product-card .mobile-card-name,
html body div[id] .mobile-product-card .mobile-card-name,
html body .product-card .product-link h3,
html body div[id] .product-card .product-link h3,
html body .product-card h3,
html body .mobile-product-card h3 {
    color: var(--pc-name) !important;
}

/* 6. Product Price Color */
html body .product-card .product-price,
html body div[id] .product-card .product-price,
html body .mobile-product-card .mobile-card-price,
html body div[id] .mobile-product-card .mobile-card-price {
    color: var(--pc-price) !important;
}

/* 7. Product Star Rating Color */
html body .product-card .product-stars,
html body div[id] .product-card .product-stars,
html body .mobile-product-card .product-stars,
html body div[id] .mobile-product-card .product-stars {
    color: var(--pc-star) !important;
}

/* 8. Product Action Button Colors (Add to Cart / Buy in Bulk) */
html body .product-card .product-btn,
html body div[id] .product-card .product-btn,
html body .mobile-product-card .mobile-card-btn,
html body div[id] .mobile-product-card .mobile-card-btn,
html body .product-card form button[type="submit"],
html body div[id] .product-card form button[type="submit"],
html body .product-card form button,
html body div[id] .product-card form button {
    background-color: var(--pc-btn-bg) !important;
    color: var(--pc-btn-txt) !important;
}

/* 9. Wishlist Button Background & Icon Color */
html body .product-card button[onclick*="toggleWishlist"],
html body div[id] button[onclick*="toggleWishlist"],
html body .mobile-product-card button[onclick*="toggleWishlist"],
html body div[id] .mobile-product-card button[onclick*="toggleWishlist"],
html body .product-card .wishlist-btn,
html body div[id] .wishlist-btn,
html body .mobile-product-card .wishlist-btn {
    background-color: var(--pc-wishlist-bg) !important;
}

html body .product-card button[onclick*="toggleWishlist"] svg:not(.is-active):not(.text-\[\#F25996\]):not(.fill-\[\#F25996\]):not(.text-red-500),
html body div[id] button[onclick*="toggleWishlist"] svg:not(.is-active):not(.text-\[\#F25996\]):not(.fill-\[\#F25996\]):not(.text-red-500),
html body .mobile-product-card button[onclick*="toggleWishlist"] svg:not(.is-active):not(.text-\[\#F25996\]):not(.fill-\[\#F25996\]):not(.text-red-500),
html body div[id] .mobile-product-card button[onclick*="toggleWishlist"] svg:not(.is-active):not(.text-\[\#F25996\]):not(.fill-\[\#F25996\]):not(.text-red-500),
html body .product-card .wishlist-btn svg:not(.is-active):not(.text-\[\#F25996\]):not(.fill-\[\#F25996\]):not(.text-red-500),
html body div[id] .product-card .wishlist-btn svg:not(.is-active):not(.text-\[\#F25996\]):not(.fill-\[\#F25996\]):not(.text-red-500),
html body .product-card button[onclick*="toggleWishlist"] svg:not(.is-active):not(.text-\[\#F25996\]):not(.fill-\[\#F25996\]):not(.text-red-500) *,
html body div[id] button[onclick*="toggleWishlist"] svg:not(.is-active):not(.text-\[\#F25996\]):not(.fill-\[\#F25996\]):not(.text-red-500) * {
    color: var(--pc-wishlist-icon) !important;
    stroke: var(--pc-wishlist-icon) !important;
    fill: none;
}

/* Active / In-Wishlist Pink Filled Heart */
html body .product-card button[onclick*="toggleWishlist"].is-active svg,
html body .product-card button[onclick*="toggleWishlist"] svg.is-active,
html body .product-card button[onclick*="toggleWishlist"] svg.text-\[\#F25996\],
html body .product-card button[onclick*="toggleWishlist"] svg.fill-\[\#F25996\],
html body .product-card .wishlist-btn.is-active svg,
html body .product-card .wishlist-btn svg.is-active,
html body .product-card .wishlist-btn svg.text-\[\#F25996\],
html body .product-card .wishlist-btn svg.fill-\[\#F25996\],
html body .mobile-product-card .wishlist-btn.is-active svg,
html body .mobile-product-card .wishlist-btn svg.is-active,
html body .mobile-product-card .wishlist-btn svg.text-\[\#F25996\],
html body .mobile-product-card .wishlist-btn svg.fill-\[\#F25996\],
html body [data-wishlist-btn-id].is-active svg,
html body [data-wishlist-btn-id] svg.is-active {
    fill: #F25996 !important;
    stroke: #F25996 !important;
    color: #F25996 !important;
}
</style>
