<div class="w-full">
    <form action="<?= BASE_URL ?>/admin/cms/product-carousels/update" method="POST" id="carouselForm" class="bg-white rounded-[1.5rem] shadow-custom border border-gray-100 p-8">
        <div class="mb-6">
            <h2 class="text-2xl font-serif text-[#0b3c7c]">Edit Product Carousel</h2>
        </div>
        
        <input type="hidden" name="id" value="<?= htmlspecialchars($carousel['id']) ?>">
<?php
        $content = $carousel['content'] ?? [];
$selectedProducts = $content['product_ids'] ?? [];
$theme = $content['theme'] ?? ['type' => 'solid', 'value' => '#ffffff'];

$renderColorField = function($label, $name, $default) use ($content) {
    $val = $content[$name] ?? $default;
    $isVar = str_starts_with($val, 'var(');
    $hex = $isVar ? $default : $val;
    $varName = $isVar ? substr($val, 4, -1) : '';

    echo '
    <div class="flex flex-col gap-2 p-2 rounded-lg hover:bg-gray-50 border border-transparent hover:border-gray-100 transition-colors color-field-wrapper" data-custom="' . ($isVar ? 'true' : 'false') . '">
        <label class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">' . $label . '</label>
        
        <!-- Standard Picker -->
        <div class="flex items-center gap-2 color-standard-mode ' . ($isVar ? 'hidden' : '') . '">
            <input type="color" value="' . htmlspecialchars($hex) . '" class="w-8 h-8 rounded border border-gray-200 cursor-pointer p-0 color-picker-input bg-white">
            <input type="text" value="' . htmlspecialchars($hex) . '" class="w-20 border border-gray-200 rounded px-2 py-1 text-xs outline-none font-mono color-picker-text">
        </div>

        <!-- Custom Link/Var -->
        <div class="color-custom-mode ' . ($isVar ? '' : 'hidden') . '">
            <div class="flex flex-1 items-center border border-gray-200 rounded px-2 bg-white">
                <span class="text-xs text-gray-400 font-mono pr-1">var(</span>
                <input type="text" value="' . htmlspecialchars($varName) . '" class="w-full py-1.5 text-[10px] outline-none font-mono color-custom-input" placeholder="--my-var">
                <span class="text-xs text-gray-400 font-mono pl-1">)</span>
            </div>
        </div>
        
        <input type="hidden" name="' . $name . '" class="color-final-value" value="' . htmlspecialchars($val) . '">
    </div>';
};
?>

<div class="p-6 md:p-8 space-y-8 w-full">
    
    <!-- Title & Sort Order -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Section Title *</label>
            <input type="text" name="title" value="<?= htmlspecialchars($content['title'] ?? '') ?>" required 
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-shadow text-gray-900"
                placeholder="e.g. Featured Products">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Sort Order</label>
            <input type="text" name="sort_order" value="<?= htmlspecialchars($content['sort_order'] ?? '0') ?>" 
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-shadow text-gray-900">
        </div>
    </div>

    <!-- Product Counts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Mobile View Product Count</label>
            <input type="text" name="mobile_count" value="<?= htmlspecialchars($content['mobile_count'] ?? '2') ?>" min="1" max="4"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-shadow text-gray-900">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Desktop View Product Count</label>
            <input type="text" name="desktop_count" value="<?= htmlspecialchars($content['desktop_count'] ?? '4') ?>" min="1" max="8"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-shadow text-gray-900">
        </div>
    </div>

    <!-- CTA & Position -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">CTA Button Text (Optional)</label>
            <input type="text" name="cta_text" value="<?= htmlspecialchars($content['cta_text'] ?? '') ?>" 
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-shadow text-gray-900"
                placeholder="View All">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">CTA URL (Optional)</label>
            <input type="text" name="cta_url" value="<?= htmlspecialchars($content['cta_url'] ?? '') ?>" 
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-shadow text-gray-900"
                placeholder="/catalog">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">CTA Position</label>
            <select name="cta_position" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-shadow text-gray-900 bg-white">
                <option value="Right" <?= ($content['cta_position'] ?? '') === 'Right' ? 'selected' : '' ?>>Right</option>
                <option value="Center" <?= ($content['cta_position'] ?? '') === 'Center' ? 'selected' : '' ?>>Center</option>
            </select>
        </div>
    </div>

    <!-- Navigation Options -->
    <div class="flex gap-6 items-center">
        <label class="flex items-center space-x-2 cursor-pointer">
            <input type="checkbox" name="show_arrows" value="1" <?= !isset($content['show_arrows']) || $content['show_arrows'] ? 'checked' : '' ?> class="w-4 h-4 text-brand-600 border-gray-300 rounded focus:ring-brand-500">
            <span class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Show Arrows</span>
        </label>
        <label class="flex items-center space-x-2 cursor-pointer">
            <input type="checkbox" name="show_dots" value="1" <?= !isset($content['show_dots']) || $content['show_dots'] ? 'checked' : '' ?> class="w-4 h-4 text-brand-600 border-gray-300 rounded focus:ring-brand-500">
            <span class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Show Dots</span>
        </label>
    </div>

    <!-- Color Theme -->
    <div class="col-span-full">
        <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Color Theme</label>
        <?php
            $thType = $theme['type'] ?? 'solid';
            $thVal = $theme['value'] ?? '#ffffff';
            
            // Extract values safely
            $solidHex = $thType === 'solid' ? $thVal : '#ffffff';
            $lin1 = '#8c6a4f'; $lin2 = '#c4a882'; $linDir = 'to bottom right';
            if ($thType === 'linear' && preg_match('/linear-gradient\((.*?),\s*(#[a-fA-F0-9]{3,6}),\s*(#[a-fA-F0-9]{3,6})\)/', $thVal, $m)) {
                $linDir = $m[1]; $lin1 = $m[2]; $lin2 = $m[3];
            }
            $rad1 = '#ffffff'; $rad2 = '#8c6a4f';
            if ($thType === 'radial' && preg_match('/radial-gradient\(circle,\s*(#[a-fA-F0-9]{3,6}),\s*(#[a-fA-F0-9]{3,6})\)/', $thVal, $m)) {
                $rad1 = $m[1]; $rad2 = $m[2];
            }
        ?>
        <input type="hidden" name="color_theme_type" id="colorThemeType" value="<?= htmlspecialchars($thType) ?>">
        <input type="hidden" name="color_theme_value" id="colorThemeValue" value="<?= htmlspecialchars($thVal) ?>">

        <div class="border border-gray-200 rounded-xl p-4 space-y-4 bg-white shadow-sm">
            <!-- Type Tabs -->
            <div class="flex gap-2">
                <button type="button" data-type="solid" class="color-type-btn <?= $thType === 'solid' ? 'active-type bg-gray-900 text-white border-gray-300' : 'text-gray-600 border-gray-200 hover:border-gray-400' ?> px-4 py-1.5 rounded-full text-xs font-bold border transition-all">Solid</button>
                <button type="button" data-type="linear" class="color-type-btn <?= $thType === 'linear' ? 'active-type bg-gray-900 text-white border-gray-300' : 'text-gray-600 border-gray-200 hover:border-gray-400' ?> px-4 py-1.5 rounded-full text-xs font-bold border transition-all">Linear Gradient</button>
                <button type="button" data-type="radial" class="color-type-btn <?= $thType === 'radial' ? 'active-type bg-gray-900 text-white border-gray-300' : 'text-gray-600 border-gray-200 hover:border-gray-400' ?> px-4 py-1.5 rounded-full text-xs font-bold border transition-all">Radial Gradient</button>
                <button type="button" data-type="link" class="color-type-btn <?= $thType === 'link' ? 'active-type bg-gray-900 text-white border-gray-300' : 'text-gray-600 border-gray-200 hover:border-gray-400' ?> px-4 py-1.5 rounded-full text-xs font-bold border transition-all">Color Link (CSS)</button>
            </div>

            <!-- Solid Panel -->
            <div id="panel-solid" class="color-panel flex items-center gap-4 <?= $thType === 'solid' ? '' : 'hidden' ?>">
                <label class="text-xs text-gray-500 font-medium w-12">Color</label>
                <input type="color" id="solidColor" value="<?= htmlspecialchars($solidHex) ?>" class="w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5">
                <input type="text" id="solidHex" value="<?= htmlspecialchars($solidHex) ?>" class="w-28 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none font-mono">
            </div>

            <!-- Linear Gradient Panel -->
            <div id="panel-linear" class="color-panel <?= $thType === 'linear' ? '' : 'hidden' ?> space-y-3">
                <div class="flex items-center gap-4">
                    <label class="text-xs text-gray-500 font-medium w-12">From</label>
                    <input type="color" id="linearColor1" value="<?= htmlspecialchars($lin1) ?>" class="w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5">
                    <input type="text" id="linearHex1" value="<?= htmlspecialchars($lin1) ?>" class="w-28 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none font-mono">
                </div>
                <div class="flex items-center gap-4">
                    <label class="text-xs text-gray-500 font-medium w-12">To</label>
                    <input type="color" id="linearColor2" value="<?= htmlspecialchars($lin2) ?>" class="w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5">
                    <input type="text" id="linearHex2" value="<?= htmlspecialchars($lin2) ?>" class="w-28 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none font-mono">
                </div>
                <div class="flex items-center gap-4">
                    <label class="text-xs text-gray-500 font-medium w-12">Direction</label>
                    <select id="linearDir" class="border border-gray-200 rounded-lg px-3 py-1.5 text-sm bg-white outline-none">
                        <option value="to right" <?= $linDir === 'to right' ? 'selected' : '' ?>>Left → Right</option>
                        <option value="to left" <?= $linDir === 'to left' ? 'selected' : '' ?>>Right → Left</option>
                        <option value="to bottom" <?= $linDir === 'to bottom' ? 'selected' : '' ?>>Top → Bottom</option>
                        <option value="to top" <?= $linDir === 'to top' ? 'selected' : '' ?>>Bottom → Top</option>
                        <option value="to bottom right" <?= $linDir === 'to bottom right' ? 'selected' : '' ?>>Top-Left → Bottom-Right</option>
                        <option value="to bottom left" <?= $linDir === 'to bottom left' ? 'selected' : '' ?>>Top-Right → Bottom-Left</option>
                    </select>
                </div>
            </div>

            <!-- Radial Gradient Panel -->
            <div id="panel-radial" class="color-panel <?= $thType === 'radial' ? '' : 'hidden' ?> space-y-3">
                <div class="flex items-center gap-4">
                    <label class="text-xs text-gray-500 font-medium w-16">Center</label>
                    <input type="color" id="radialColor1" value="<?= htmlspecialchars($rad1) ?>" class="w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5">
                    <input type="text" id="radialHex1" value="<?= htmlspecialchars($rad1) ?>" class="w-28 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none font-mono">
                </div>
                <div class="flex items-center gap-4">
                    <label class="text-xs text-gray-500 font-medium w-16">Edge</label>
                    <input type="color" id="radialColor2" value="<?= htmlspecialchars($rad2) ?>" class="w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5">
                    <input type="text" id="radialHex2" value="<?= htmlspecialchars($rad2) ?>" class="w-28 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none font-mono">
                </div>
            </div>

            <!-- Color Link Panel -->
            <div id="panel-link" class="color-panel <?= $thType === 'link' ? '' : 'hidden' ?> space-y-2">
                <label class="text-xs text-gray-500 font-medium">Enter a CSS color value, variable, or any valid background</label>
                <input type="text" id="colorLinkInput" class="w-full border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-brown font-mono" value="<?= htmlspecialchars($thType === 'link' ? $thVal : '') ?>" placeholder="e.g. var(--primary-color) or #FF5500 or any CSS value">
            </div>

            <!-- Layout & Sizing -->
            <div class="border-t border-gray-100 pt-4 mt-4 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Desktop Container Height</label>
                    <div class="flex items-center gap-3">
                        <input type="text" name="container_height" value="<?= htmlspecialchars($content['container_height'] ?? '') ?>" class="w-full border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-brown text-sm font-mono" placeholder="e.g. 80">
                        <span class="text-xs text-gray-500 whitespace-nowrap">vh (default: 80)</span>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Mobile Container Height</label>
                    <div class="flex items-center gap-3">
                        <input type="text" name="mobile_container_height" value="<?= htmlspecialchars($content['mobile_container_height'] ?? '') ?>" class="w-full border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-brown text-sm font-mono" placeholder="e.g. 100">
                        <span class="text-xs text-gray-500 whitespace-nowrap">vh (default: 100)</span>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Image Fit</label>
                    <select name="image_fit" class="w-full border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-brown text-sm">
                        <option value="cover" <?= ($content['image_fit'] ?? '') === 'cover' ? 'selected' : '' ?>>Cover (Fills, Crops)</option>
                        <option value="contain" <?= ($content['image_fit'] ?? '') === 'contain' ? 'selected' : '' ?>>Contain (Full Image, Empty Space)</option>
                        <option value="stretch" <?= ($content['image_fit'] ?? '') === 'stretch' ? 'selected' : '' ?>>Stretch (Fills, Distorts)</option>
                    </select>
                </div>
            </div>

            <!-- Component Colors -->
            <div class="border-t border-gray-100 pt-6 mt-6">
                <div class="flex items-center justify-between mb-4">
                    <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider">Component Colors</label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="masterColorLinkToggle" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                        <span class="text-xs font-bold text-gray-700">Use Custom Color Link / CSS</span>
                    </label>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <?php
                    $renderColorField('Title Color', 'title_color', $content['title_color'] ?? '#111827');
                    $renderColorField('Arrow BG', 'arrow_bg_color', $content['arrow_bg_color'] ?? '#ffffff');
                    $renderColorField('Arrow Icon', 'arrow_color', $content['arrow_color'] ?? '#ffffff');
                    $renderColorField('Dot Color', 'dot_color', $content['dot_color'] ?? '#8c6a4f');
                    ?>
                </div>
            </div>

            <!-- Live Preview Bar -->
            <div>
                <label class="text-xs text-gray-400 font-medium mb-1 block">Preview</label>
                <div id="colorPreviewBar" class="w-full h-12 rounded-lg border border-gray-200" style="background:<?= htmlspecialchars($thVal) ?>;"></div>
                <p id="colorPreviewText" class="text-[10px] text-gray-400 mt-1 font-mono"><?= htmlspecialchars($thVal) ?></p>
            </div>
        </div>
    </div>

    <!-- Product Selection (Custom Vendor & Category UI) -->
    <div class="p-6 border border-gray-200 rounded-xl space-y-5 bg-gray-50/50">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-gray-200 pb-4">
            <div>
                <h3 class="text-base font-bold text-gray-900 uppercase tracking-wide flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    Select Products for Carousel *
                </h3>
                <p class="text-xs text-gray-500 mt-0.5"><?= is_vendor_module_enabled() ? 'Filter by Vendor/Seller to feature paid listings or select specific products.' : 'Filter by Category to select specific products.' ?></p>
            </div>
            <div class="flex items-center gap-2 text-xs font-semibold">
                <span class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full border border-emerald-200" id="selectedCounter">
                    Selected: 0 Products
                </span>
                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full border border-gray-200" id="visibleCounter">
                    Showing: 0 Products
                </span>
            </div>
        </div>
        
        <!-- Filters Row -->
        <div class="grid grid-cols-1 <?= is_vendor_module_enabled() ? 'md:grid-cols-3' : 'md:grid-cols-2' ?> gap-4">
            <?php if (is_vendor_module_enabled()): ?>
            <!-- Vendor Filter -->
            <div>
                <?php $savedVendorId = $carousel['content']['vendor_id'] ?? 'all'; ?>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider flex items-center">
                    <svg class="w-4 h-4 mr-1 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Filter by Vendor / Seller
                </label>
                <select id="vendorFilter" name="vendor_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-shadow text-gray-900 bg-white text-sm font-medium">
                    <option value="all" <?= ($savedVendorId === 'all') ? 'selected' : '' ?>>🏢 All Vendors & In-House Products</option>
                    <option value="in_house" <?= ($savedVendorId === 'in_house') ? 'selected' : '' ?>>🏷️ Admin / In-House Products Only</option>
                    <?php if (!empty($vendors)): ?>
                        <optgroup label="Registered Vendors">
                            <?php foreach ($vendors as $v): ?>
                                <option value="<?= $v['id'] ?>" <?= ((string)$savedVendorId === (string)$v['id']) ? 'selected' : '' ?>>
                                    🏪 <?= htmlspecialchars($v['store_name'] ?? $v['name']) ?> (<?= htmlspecialchars($v['unique_vendor_id'] ?? ('VN'.$v['id'])) ?>)
                                </option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endif; ?>
                </select>
            </div>
            <?php endif; ?>

            <!-- Category Filter -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider flex items-center">
                    <svg class="w-4 h-4 mr-1 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    Filter by Category
                </label>
                <select id="catFilter" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-shadow text-gray-900 bg-white text-sm font-medium">
                    <option value="all">📦 All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['name']) ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Search Field -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider flex items-center">
                    <svg class="w-4 h-4 mr-1 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Search Products
                </label>
                <div class="relative">
                    <input type="text" id="prodSearch" class="w-full pl-4 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-shadow text-gray-900 text-sm" placeholder="Search by name, SKU or brand...">
                </div>
            </div>
        </div>

        <!-- Quick Action Buttons -->
        <div class="flex flex-wrap items-center justify-between gap-3 bg-white p-3 rounded-lg border border-gray-200">
            <div class="flex items-center gap-2">
                <button type="button" id="btnSelectAllVisible" class="px-3 py-1.5 bg-brand-50 hover:bg-brand-100 text-brand-700 border border-brand-200 text-xs font-bold rounded-lg transition-colors flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Select All Shown
                </button>
                <button type="button" id="btnDeselectAllVisible" class="px-3 py-1.5 bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 text-xs font-bold rounded-lg transition-colors flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Deselect All Shown
                </button>
            </div>
            <div>
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="showOnlySelectedToggle" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                    <span class="ml-2 text-xs font-bold text-gray-700">Show Selected Only</span>
                </label>
            </div>
        </div>

        <!-- Product List -->
        <div class="border border-gray-200 rounded-xl bg-white max-h-96 overflow-y-auto divide-y divide-gray-100 shadow-inner" id="productList">
            <?php foreach ($products as $prod): 
                $img = !empty($prod['primary_image']) ? (str_starts_with($prod['primary_image'], 'http') ? $prod['primary_image'] : (BASE_URL . '/' . ltrim($prod['primary_image'], '/'))) : 'https://placehold.co/100?text=' . urlencode($prod['name']);
                $isChecked = in_array($prod['id'], $selectedProducts) ? 'checked' : '';
                $vendorId = $prod['vendor_id'] ?? $prod['user_id'] ?? 0;
                $vendorStoreName = $prod['vendor_store_name'] ?? $prod['vendor_company_name'] ?? $prod['vendor_name'] ?? null;
            ?>
            <label class="flex items-center px-4 py-3 hover:bg-blue-50/40 cursor-pointer transition-colors product-item group" 
                   data-cat="<?= htmlspecialchars(strtolower($prod['category_name'])) ?>" 
                   data-name="<?= htmlspecialchars(strtolower($prod['name'])) ?>"
                   data-vendor-id="<?= $vendorId ?>"
                   data-vendor-name="<?= htmlspecialchars(strtolower($vendorStoreName ?? 'in_house')) ?>">
                <input type="checkbox" name="product_ids[]" value="<?= $prod['id'] ?>" <?= $isChecked ?> class="w-4 h-4 text-brand-600 border-gray-300 rounded focus:ring-brand-500 mr-4 cursor-pointer product-checkbox">
                <img src="<?= htmlspecialchars($img) ?>" class="w-11 h-11 object-contain rounded-lg border border-gray-200 bg-white mr-4 p-0.5 group-hover:scale-105 transition-transform">
                <div class="flex-1 min-w-0 pr-4">
                    <div class="font-bold text-sm text-gray-900 truncate"><?= htmlspecialchars($prod['name']) ?></div>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="text-xs text-gray-500 font-medium"><?= htmlspecialchars($prod['category_name']) ?></span>
                        <span class="text-xs text-gray-300">•</span>
                        <span class="text-xs font-semibold text-emerald-700">₹<?= number_format((float)($prod['base_price'] ?? $prod['price'] ?? 0), 2) ?></span>
                    </div>
                </div>
                <div class="flex-shrink-0 text-right">
                    <?php if (!empty($vendorStoreName)): ?>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                            <svg class="w-3.5 h-3.5 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <?= htmlspecialchars($vendorStoreName) ?>
                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                            🛡️ Admin In-House
                        </span>
                    <?php endif; ?>
                </div>
            </label>
            <?php endforeach; ?>
            <?php if(empty($products)): ?>
                <div class="px-4 py-8 text-center text-gray-500 text-sm font-medium">No products available.</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Status & Actions -->
    <div class="px-6 py-4 flex items-center justify-between border-t border-gray-200 bg-white">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" <?= (!isset($content['is_active']) || $content['is_active']) ? 'checked' : '' ?> class="w-5 h-5 text-[#0056b3] bg-gray-100 border-gray-300 rounded focus:ring-[#0056b3] focus:ring-2 cursor-pointer" style="accent-color: #0056b3;">
            <span class="text-sm font-bold text-[#0056b3]">Active (Visible on Site)</span>
        </label>
        
        <div class="flex items-center gap-3">
            <a href="<?= BASE_URL ?>/admin/cms/product-carousels" class="px-6 py-2.5 border border-red-200 text-red-500 hover:bg-red-50 font-bold text-sm rounded-full transition-colors">CANCEL</a>
            <button type="submit" class="px-8 py-2.5 bg-gray-900 text-white font-bold text-sm rounded-full shadow-sm hover:bg-black transition-all">Save Grid</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ---- Color Picker ----
    let currentType = document.getElementById('colorThemeType').value || 'solid';
    const themeTypeInput = document.getElementById('colorThemeType');
    const themeValueInput = document.getElementById('colorThemeValue');
    const previewBar = document.getElementById('colorPreviewBar');
    const previewText = document.getElementById('colorPreviewText');

    function syncColorInput(picker, hexInput) {
        if (!picker || !hexInput) return;
        picker.addEventListener('input', () => {
            hexInput.value = picker.value;
            updatePreview();
        });
        hexInput.addEventListener('input', () => {
            const val = hexInput.value;
            if (/^#[0-9a-fA-F]{6}$/.test(val)) {
                picker.value = val;
                updatePreview();
            }
        });
    }

    syncColorInput(document.getElementById('solidColor'), document.getElementById('solidHex'));
    syncColorInput(document.getElementById('linearColor1'), document.getElementById('linearHex1'));
    syncColorInput(document.getElementById('linearColor2'), document.getElementById('linearHex2'));
    syncColorInput(document.getElementById('radialColor1'), document.getElementById('radialHex1'));
    syncColorInput(document.getElementById('radialColor2'), document.getElementById('radialHex2'));
    
    const linearDir = document.getElementById('linearDir');
    if (linearDir) linearDir.addEventListener('change', updatePreview);

    function updatePreview() {
        let cssValue = '';
        if (currentType === 'solid') {
            cssValue = document.getElementById('solidColor').value;
        } else if (currentType === 'linear') {
            const dir = document.getElementById('linearDir') ? document.getElementById('linearDir').value : 'to right';
            const c1 = document.getElementById('linearColor1').value;
            const c2 = document.getElementById('linearColor2').value;
            cssValue = `linear-gradient(${dir}, ${c1}, ${c2})`;
        } else if (currentType === 'radial') {
            const c1 = document.getElementById('radialColor1').value;
            const c2 = document.getElementById('radialColor2').value;
            cssValue = `radial-gradient(circle, ${c1}, ${c2})`;
        } else if (currentType === 'link') {
            const linkInput = document.getElementById('colorLinkInput');
            if (linkInput) cssValue = linkInput.value;
        }
        
        if (previewBar) {
            if (currentType === 'link' && cssValue.includes('background')) {
                previewBar.style.cssText = cssValue + (cssValue.endsWith(';') ? '' : ';');
                previewBar.style.borderRadius = '0.5rem';
                previewBar.style.border = '1px solid #e5e7eb';
            } else {
                previewBar.style.cssText = '';
                previewBar.style.background = cssValue;
                previewBar.style.borderRadius = '0.5rem';
                previewBar.style.border = '1px solid #e5e7eb';
            }
        }
        
        if (previewText) previewText.textContent = cssValue;
        if (themeValueInput) themeValueInput.value = cssValue;
        if (themeTypeInput) themeTypeInput.value = currentType;
    }

    const colorLinkInput = document.getElementById('colorLinkInput');
    if (colorLinkInput) colorLinkInput.addEventListener('input', updatePreview);

    // Tab switching
    document.querySelectorAll('.color-type-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            currentType = btn.getAttribute('data-type');
            // Update tab styles
            document.querySelectorAll('.color-type-btn').forEach(b => {
                b.classList.remove('bg-gray-900', 'text-white', 'border-gray-300', 'active-type');
                b.classList.add('text-gray-600', 'border-gray-200');
            });
            btn.classList.add('bg-gray-900', 'text-white', 'border-gray-300', 'active-type');
            btn.classList.remove('text-gray-600', 'border-gray-200');
            // Show/hide panels
            document.querySelectorAll('.color-panel').forEach(p => p.classList.add('hidden'));
            const panel = document.getElementById('panel-' + currentType);
            if (panel) panel.classList.remove('hidden');
            updatePreview();
        });
    });

    // Initialize with default
    updatePreview();

    // Hybrid Color Inputs (Color Link) - Master Toggle
    const masterToggle = document.getElementById('masterColorLinkToggle');
    const colorFields = document.querySelectorAll('.color-field-wrapper');
    
    if (masterToggle) {
        // Check if any field is already custom on load
        let anyCustom = false;
        colorFields.forEach(wrapper => {
            if (wrapper.getAttribute('data-custom') === 'true') {
                anyCustom = true;
            }
        });
        if (anyCustom) masterToggle.checked = true;

        masterToggle.addEventListener('change', () => {
            const isCustomMode = masterToggle.checked;
            colorFields.forEach(wrapper => {
                const standardMode = wrapper.querySelector('.color-standard-mode');
                const customMode = wrapper.querySelector('.color-custom-mode');
                const customInput = wrapper.querySelector('.color-custom-input');
                const pickerInput = wrapper.querySelector('.color-picker-input');
                const finalInput = wrapper.querySelector('.color-final-value');

                if (isCustomMode) {
                    standardMode.classList.add('hidden');
                    customMode.classList.remove('hidden');
                    customInput.value = finalInput.value.startsWith('var(') ? finalInput.value.slice(4, -1) : '';
                } else {
                    standardMode.classList.remove('hidden');
                    customMode.classList.add('hidden');
                    if (/^#[0-9A-Fa-f]{3,6}$/.test(finalInput.value)) {
                        pickerInput.value = finalInput.value;
                        const pickerText = wrapper.querySelector('.color-picker-text');
                        if(pickerText) pickerText.value = finalInput.value;
                    }
                }
                updateFinal(wrapper);
            });
        });

        function updateFinal(wrapper) {
            const isCustomMode = masterToggle.checked;
            const pickerText = wrapper.querySelector('.color-picker-text');
            const customInput = wrapper.querySelector('.color-custom-input');
            const finalInput = wrapper.querySelector('.color-final-value');
            
            if (isCustomMode) {
                finalInput.value = customInput.value ? `var(${customInput.value})` : '';
            } else {
                finalInput.value = pickerText.value;
            }
            updatePreview();
        }

        colorFields.forEach(wrapper => {
            const pickerInput = wrapper.querySelector('.color-picker-input');
            const pickerText = wrapper.querySelector('.color-picker-text');
            const customInput = wrapper.querySelector('.color-custom-input');

            if (pickerInput && pickerText) {
                pickerInput.addEventListener('input', () => {
                    pickerText.value = pickerInput.value;
                    updateFinal(wrapper);
                });
                pickerText.addEventListener('input', () => {
                    if (/^#[0-9A-Fa-f]{3,6}$/.test(pickerText.value)) {
                        pickerInput.value = pickerText.value;
                    }
                    updateFinal(wrapper);
                });
            }
            
            if (customInput) {
                customInput.addEventListener('input', () => updateFinal(wrapper));
            }
        });
    }

    // Product Filter & Dynamic Selection Logic
    const searchInput = document.getElementById('prodSearch');
    const catSelect = document.getElementById('catFilter');
    const vendorSelect = document.getElementById('vendorFilter');
    const showOnlySelectedToggle = document.getElementById('showOnlySelectedToggle');
    const productItems = document.querySelectorAll('.product-item');
    const selectedCounter = document.getElementById('selectedCounter');
    const visibleCounter = document.getElementById('visibleCounter');
    const btnSelectAllVisible = document.getElementById('btnSelectAllVisible');
    const btnDeselectAllVisible = document.getElementById('btnDeselectAllVisible');

    function updateCounters() {
        let totalChecked = 0;
        let totalVisible = 0;

        productItems.forEach(item => {
            const cb = item.querySelector('.product-checkbox');
            if (cb && cb.checked) totalChecked++;
            if (item.style.display !== 'none') totalVisible++;
        });

        if (selectedCounter) selectedCounter.textContent = `Selected: ${totalChecked} Products`;
        if (visibleCounter) visibleCounter.textContent = `Showing: ${totalVisible} Products`;
    }

    function filterProducts() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
        const selectedCat = catSelect ? catSelect.value.toLowerCase() : 'all';
        const selectedVendor = vendorSelect ? vendorSelect.value : 'all';
        const onlySelected = showOnlySelectedToggle ? showOnlySelectedToggle.checked : false;

        productItems.forEach(item => {
            const name = item.getAttribute('data-name') || '';
            const cat = item.getAttribute('data-cat') || '';
            const vendorId = item.getAttribute('data-vendor-id') || '0';
            const cb = item.querySelector('.product-checkbox');
            const isChecked = cb ? cb.checked : false;

            const matchesSearch = name.includes(searchTerm);
            const matchesCat = selectedCat === 'all' || cat === selectedCat;
            
            let matchesVendor = true;
            if (selectedVendor === 'in_house') {
                matchesVendor = (vendorId === '0' || vendorId === '');
            } else if (selectedVendor !== 'all') {
                matchesVendor = (vendorId === selectedVendor);
            }

            let matchesSelectedFilter = true;
            if (onlySelected) {
                matchesSelectedFilter = isChecked;
            }

            if (matchesSearch && matchesCat && matchesVendor && matchesSelectedFilter) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });

        updateCounters();
    }

    if (searchInput) searchInput.addEventListener('input', filterProducts);
    if (catSelect) catSelect.addEventListener('change', filterProducts);
    if (vendorSelect) vendorSelect.addEventListener('change', filterProducts);
    if (showOnlySelectedToggle) showOnlySelectedToggle.addEventListener('change', filterProducts);

    // Track checkbox changes
    productItems.forEach(item => {
        const cb = item.querySelector('.product-checkbox');
        if (cb) {
            cb.addEventListener('change', updateCounters);
        }
    });

    // Select All Shown Products
    if (btnSelectAllVisible) {
        btnSelectAllVisible.addEventListener('click', () => {
            productItems.forEach(item => {
                if (item.style.display !== 'none') {
                    const cb = item.querySelector('.product-checkbox');
                    if (cb) cb.checked = true;
                }
            });
            updateCounters();
        });
    }

    // Deselect All Shown Products
    if (btnDeselectAllVisible) {
        btnDeselectAllVisible.addEventListener('click', () => {
            productItems.forEach(item => {
                if (item.style.display !== 'none') {
                    const cb = item.querySelector('.product-checkbox');
                    if (cb) cb.checked = false;
                }
            });
            updateCounters();
        });
    }

    // Initial counter setup & filter
    filterProducts();
});
</script>

        </form>
    </div>
</div>
