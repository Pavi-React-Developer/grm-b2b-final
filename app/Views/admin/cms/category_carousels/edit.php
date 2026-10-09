
<div class="w-full">
    <form action="<?= BASE_URL ?>/admin/cms/category-carousels/update" method="POST" id="carouselForm" class="bg-white rounded-[1.5rem] shadow-custom border border-gray-100 p-8">
        <div class="mb-6">
            <h2 class="text-2xl font-serif text-[#0b3c7c]">Edit Category Carousel</h2>
        </div>
        
        <input type="hidden" name="id" value="<?= htmlspecialchars($carousel['id']) ?>">

<?php
$content = $carousel['content'] ?? [];
$selectedCategories = $content['category_ids'] ?? [];
$theme = $content['theme'] ?? ['type' => 'solid', 'value' => '#ffffff'];

// If categories var is not passed directly but we need it (the controller does pass it though)
if (!isset($categories)) {
    $db = \Core\Database::getInstance();
    $categories = $db->query("SELECT id, name, slug, image_path FROM categories WHERE status = 'active' ORDER BY name ASC")->fetchAll();
}
?>

<div class="p-6 md:p-8 space-y-8 w-full">
    
    <!-- Title & Sort Order -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Section Title *</label>
            <input type="text" name="title" value="<?= htmlspecialchars($content['title'] ?? '') ?>" required 
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-shadow text-gray-900"
                placeholder="e.g. Shop by Category">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Sort Order</label>
            <input type="text" name="sort_order" value="<?= htmlspecialchars($content['sort_order'] ?? '0') ?>" 
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-shadow text-gray-900">
        </div>
    </div>

    <!-- Carousel Item Counts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Mobile View Category Count</label>
            <input type="text" name="mobile_count" value="<?= htmlspecialchars($content['mobile_count'] ?? '3') ?>" min="1" max="4"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-shadow text-gray-900">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">Desktop View Category Count</label>
            <input type="text" name="desktop_count" value="<?= htmlspecialchars($content['desktop_count'] ?? '6') ?>" min="1" max="10"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-shadow text-gray-900">
        </div>
    </div>

    <!-- CTA & Position -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">CTA Button Text (Optional)</label>
            <input type="text" name="cta_text" value="<?= htmlspecialchars($content['cta_text'] ?? '') ?>" 
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-shadow text-gray-900"
                placeholder="View All Categories">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">CTA URL (Optional)</label>
            <input type="text" name="cta_url" value="<?= htmlspecialchars($content['cta_url'] ?? '') ?>" 
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-shadow text-gray-900"
                placeholder="/categories">
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
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    <?php
                    if (!isset($renderColorField)) {
                        $renderColorField = function($label, $name, $default) use ($content) {
                            $val = $content[$name] ?? $default;
                            $isCustom = str_starts_with($val, 'var(');
                            $hexVal = $isCustom ? '#ffffff' : $val;
                            $customVal = $isCustom ? substr($val, 4, -1) : '';
                            ?>
                            <div class="flex flex-col gap-2 color-field-wrapper" data-custom="<?= $isCustom ? 'true' : 'false' ?>">
                                <label class="text-[10px] text-gray-500 font-bold uppercase"><?= $label ?></label>
                                <div class="color-standard-mode <?= $isCustom ? 'hidden' : 'flex items-center gap-2' ?>">
                                    <input type="color" value="<?= htmlspecialchars($hexVal) ?>" class="color-picker-input w-8 h-8 rounded border border-gray-200 cursor-pointer p-0.5">
                                    <input type="text" value="<?= htmlspecialchars($hexVal) ?>" class="color-picker-text w-full border border-gray-200 rounded px-2 py-1 text-xs outline-none focus:border-brand-500 font-mono">
                                </div>
                                <div class="color-custom-mode <?= $isCustom ? '' : 'hidden' ?>">
                                    <div class="flex items-center gap-1 border border-gray-200 rounded px-2 py-1 focus-within:border-brand-500 bg-gray-50">
                                        <span class="text-xs text-gray-400 font-mono">var(--</span>
                                        <input type="text" value="<?= htmlspecialchars($customVal) ?>" class="color-custom-input w-full bg-transparent text-xs outline-none font-mono" placeholder="color-name">
                                        <span class="text-xs text-gray-400 font-mono">)</span>
                                    </div>
                                </div>
                                <input type="hidden" name="<?= $name ?>" value="<?= htmlspecialchars($val) ?>" class="color-final-value">
                            </div>
                            <?php
                        };
                    }
                    $renderColorField('Title Color', 'title_color', $content['title_color'] ?? '#111827');
                    $renderColorField('Text Color', 'text_color', $content['text_color'] ?? '#111827');
                    $renderColorField('Arrow BG', 'arrow_bg_color', $content['arrow_bg_color'] ?? '#ffffff');
                    $renderColorField('Arrow Icon', 'arrow_color', $content['arrow_color'] ?? '#111827');
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

    <!-- Category Selection (Custom UI) -->
    <div class="p-6 border border-gray-200 rounded-xl space-y-4">
        <h3 class="text-base font-bold text-gray-900 mb-4 uppercase tracking-wide">Select Categories *</h3>
        
        <div class="relative mb-4">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" id="catSearch" class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-shadow text-gray-900" placeholder="Search categories...">
        </div>

        <div class="border border-gray-200 rounded-lg max-h-80 overflow-y-auto" id="categoryList">
            <?php foreach ($categories as $cat): 
                $img = !empty($cat['image_path']) ? (str_starts_with($cat['image_path'], 'http') ? $cat['image_path'] : (BASE_URL . '/' . ltrim($cat['image_path'], '/'))) : 'https://placehold.co/100?text=' . urlencode($cat['name']);
                $isChecked = in_array($cat['id'], $selectedCategories ?? []) ? 'checked' : '';
            ?>
            <label class="flex items-center px-4 py-3 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors category-item" data-name="<?= htmlspecialchars(strtolower($cat['name'])) ?>">
                <input type="checkbox" name="category_ids[]" value="<?= $cat['id'] ?>" <?= $isChecked ?> class="w-4 h-4 text-brand-600 border-gray-300 rounded focus:ring-brand-500 mr-4">
                <img src="<?= htmlspecialchars($img) ?>" class="w-10 h-10 object-contain rounded border border-gray-200 bg-white mr-4">
                <div class="flex-1">
                    <div class="font-medium text-sm text-gray-900"><?= htmlspecialchars($cat['name']) ?></div>
                </div>
            </label>
            <?php endforeach; ?>
            <?php if(empty($categories)): ?>
                <div class="px-4 py-6 text-center text-gray-500 text-sm">No categories available.</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Status & Actions -->
    <div class="px-6 py-4 flex items-center justify-between border-t border-gray-200 bg-white">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" <?= (!isset($carousel['is_active']) || $carousel['is_active']) ? 'checked' : '' ?> class="w-5 h-5 text-[#0056b3] bg-gray-100 border-gray-300 rounded focus:ring-[#0056b3] focus:ring-2 cursor-pointer" style="accent-color: #0056b3;">
            <span class="text-sm font-bold text-[#0056b3]">Active (Visible on Site)</span>
        </label>
        
        <div class="flex items-center gap-3">
            <a href="<?= BASE_URL ?>/admin/cms/category-carousels" class="px-6 py-2.5 border border-red-200 text-red-500 hover:bg-red-50 font-bold text-sm rounded-full transition-colors">CANCEL</a>
            <button type="submit" class="px-8 py-2.5 bg-gray-900 text-white font-bold text-sm rounded-full shadow-sm hover:bg-black transition-all">Save Carousel</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ---- Color Picker ----
    let currentType = document.getElementById('colorThemeType').value;
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

    // Category Filter Logic
    const searchInput = document.getElementById('catSearch');
    const categoryItems = document.querySelectorAll('.category-item');

    function filterCategories() {
        const searchTerm = searchInput.value.toLowerCase();

        categoryItems.forEach(item => {
            const name = item.getAttribute('data-name');
            const matchesSearch = name.includes(searchTerm);

            if (matchesSearch) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterCategories);
});
</script>

    </form>
</div>

<style>
.font-display {
    font-family: 'Outfit', sans-serif;
}
</style>
