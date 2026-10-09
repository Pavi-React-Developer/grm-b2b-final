<style>
.main-banner-form .media-row,
.main-banner-form .media-row > div,
.main-banner-form .media-row input {
    min-width: 0;
    max-width: 100%;
}

.main-banner-form .dynamic-info-section > div {
    background: #fff !important;
    border: 1px solid #e5e7eb !important;
    border-radius: 0.75rem !important;
    padding: 0.75rem !important;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04) !important;
}

.dynamic-info-section .dynamic-preview-grid,
.main-banner-form .dynamic-info-section .dynamic-preview-grid {
    display: flex !important;
    flex-direction: column !important;
    gap: 0.5rem !important;
    max-height: 260px !important;
    overflow-y: auto !important;
    padding-right: 0.25rem !important;
}

.dynamic-info-section .dynamic-preview-grid > div,
.main-banner-form .dynamic-info-section .dynamic-preview-grid > div {
    display: flex !important;
    flex-direction: row !important;
    align-items: center !important;
    gap: 0.75rem !important;
    width: 100% !important;
    min-height: auto !important;
    background: #fff !important;
    border: 1px solid #e5e7eb !important;
    border-radius: 0.75rem !important;
    padding: 0.5rem 0.75rem !important;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04) !important;
}

/* Custom Dropdown Styling */
select.content-type-select,
.main-banner-form select {
    background-color: #ffffff !important;
    color: #1f2937 !important;
    border-color: #fbcfe8 !important;
    accent-color: #F25996 !important;
}

select.content-type-select:focus,
.main-banner-form select:focus {
    border-color: #F25996 !important;
    box-shadow: 0 0 0 3px rgba(242, 89, 150, 0.15) !important;
}

select.content-type-select option,
.main-banner-form select option {
    background-color: #ffffff !important;
    color: #1f2937 !important;
    padding: 10px 14px !important;
}

select.content-type-select option:hover,
select.content-type-select option:focus,
select.content-type-select option:checked,
.main-banner-form select option:hover,
.main-banner-form select option:focus,
.main-banner-form select option:checked {
    background-color: #F25996 !important;
    background: #F25996 !important;
    color: #ffffff !important;
}
</style>

<div class="max-w-[95rem] mx-auto pb-12 px-4 sm:px-6">
    <?php
    $renderColorField = function($label, $name, $value) {
        $isCustom = !empty($value) && !preg_match('/^#[0-9A-Fa-f]{3,6}$/', $value);
        $displayHex = $isCustom ? '#000000' : ($value ?: '#000000');
        ?>
        <div class="flex flex-col gap-2 color-field-wrapper" data-custom="<?= $isCustom ? 'true' : 'false' ?>">
            <label class="text-[10px] text-gray-500 font-bold uppercase tracking-wider"><?= $label ?></label>
            
            <div class="color-standard-mode border border-gray-200 rounded-lg p-2 flex items-center gap-3 bg-white <?= $isCustom ? 'hidden' : '' ?>">
                <input type="color" value="<?= htmlspecialchars($displayHex) ?>" class="color-picker-input w-8 h-8 rounded cursor-pointer border-0 p-0 shadow-sm flex-shrink-0">
                <input type="text" class="color-picker-text w-full font-mono text-xs border-0 p-0 focus:ring-0 text-gray-600 bg-transparent outline-none" value="<?= htmlspecialchars($displayHex) ?>">
            </div>
            
            <div class="color-custom-mode <?= !$isCustom ? 'hidden' : '' ?>">
                <input type="text" class="color-custom-input w-full border border-gray-200 rounded-lg px-3 h-12 text-xs font-mono outline-none focus:border-brown" value="<?= htmlspecialchars($value) ?>" placeholder="var(--color-name)">
            </div>
            
            <input type="hidden" name="<?= $name ?>" class="color-final-input" value="<?= htmlspecialchars($value) ?>">
        </div>
        <?php
    };
    ?>
    <form action="<?= BASE_URL ?>/admin/cms/main-banners/store" method="POST" id="mainBannerForm">
        
        <!-- Single Main Container -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-8">
            
            <div class="mb-2 flex justify-between items-center">
                <h2 class="text-2xl font-serif text-[#0b3c7c]">Create Main Banner</h2>
            </div>

            <!-- Row 1: Title & Main Theme -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Title (Internal)</label>
                    <input type="text" name="title" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Main Banner Background Theme</label>
                    <input type="hidden" name="main_color_theme_type" id="mainThemeType" value="solid">
                    <input type="hidden" name="main_color_theme_value" id="mainThemeValue" value="#ffffff">
                    <div class="flex flex-wrap gap-2 mb-3">
                        <button type="button" data-main-theme="solid" class="main-theme-btn px-3 py-1.5 rounded-full text-xs font-bold border bg-gray-900 text-white">Solid</button>
                        <button type="button" data-main-theme="linear" class="main-theme-btn px-3 py-1.5 rounded-full text-xs font-bold border bg-white text-gray-600">Linear</button>
                        <button type="button" data-main-theme="radial" class="main-theme-btn px-3 py-1.5 rounded-full text-xs font-bold border bg-white text-gray-600">Radial</button>
                        <button type="button" data-main-theme="css" class="main-theme-btn px-3 py-1.5 rounded-full text-xs font-bold border bg-white text-gray-600">Color Link</button>
                    </div>
                    <div id="mainThemePanel" class="flex items-center gap-2">
                        <input type="color" id="mainThemeColor1" value="#ffffff" class="w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5 bg-white">
                        <input type="color" id="mainThemeColor2" value="#c4a882" class="hidden w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5 bg-white">
                        <input type="text" id="mainThemeText" value="#ffffff" class="flex-1 min-w-0 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none focus:border-brown font-mono">
                        <div class="w-10 h-10 rounded-lg border border-gray-200 flex-shrink-0" id="main_bg_preview" style="background:#ffffff;"></div>
                    </div>
                </div>
            </div>
            <script>
            (function() {
                var type = document.getElementById('mainThemeType');
                var value = document.getElementById('mainThemeValue');
                var picker = document.getElementById('mainThemeColor1');
                var picker2 = document.getElementById('mainThemeColor2');
                var text = document.getElementById('mainThemeText');
                var preview = document.getElementById('main_bg_preview');
                var current = 'solid';
                function sync() {
                    var v = current === 'solid' ? picker.value : current === 'linear' ? 'linear-gradient(to right, ' + picker.value + ', ' + picker2.value + ')' : current === 'radial' ? 'radial-gradient(circle, ' + picker.value + ', ' + picker2.value + ')' : text.value;
                    value.value = v; type.value = current; text.value = v; preview.style.background = v;
                }
                document.querySelectorAll('.main-theme-btn').forEach(function(btn){ btn.addEventListener('click', function(){ current=btn.dataset.mainTheme; document.querySelectorAll('.main-theme-btn').forEach(function(b){b.classList.remove('bg-gray-900','text-white');b.classList.add('bg-white','text-gray-600');}); btn.classList.add('bg-gray-900','text-white');btn.classList.remove('bg-white','text-gray-600'); picker2.classList.toggle('hidden', current==='solid'||current==='css'); text.placeholder=current==='css'?'var(--brand-color)':'#ffffff'; sync(); }); });
                picker.addEventListener('input', sync); picker2.addEventListener('input', sync); text.addEventListener('input', function(){ if(current==='css') sync(); });
            })();
            </script>

            <!-- Row 1.5: Layout & Sizing -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Desktop Container Height</label>
                    <div class="flex items-center gap-3">
                        <input type="text" name="container_height" value="" class="w-full border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-brown text-sm font-mono" placeholder="e.g. 80">
                        <span class="text-xs text-gray-500 whitespace-nowrap">vh (default: 80)</span>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Mobile Container Height</label>
                    <div class="flex items-center gap-3">
                        <input type="text" name="mobile_container_height" value="" class="w-full border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-brown text-sm font-mono" placeholder="e.g. 100">
                        <span class="text-xs text-gray-500 whitespace-nowrap">vh (default: 100)</span>
                    </div>
                </div>
            </div>

            <!-- Row 2: Banner Layout -->
            <div id="banner_layout_preview" class="grid grid-cols-1 lg:grid-cols-3 gap-8 p-6 rounded-xl transition-colors duration-300">
                <!-- Left Container -->
                <div class="flex flex-col h-full">
                    <?php 
                    $prefix = 'left';
                    $sectionTitle = 'Left Container';
                    $isRight = false;
                    // Expected variables:
// $prefix - e.g. 'left', 'right_top', 'right_bottom'
// $sectionTitle - e.g. 'Left Container'
// $isRight - boolean
// $data - array (for edit)
// $theme - array (for edit)
// $media - array (for edit)

$data = $data ?? [];
$theme = $theme ?? [];
$media = $media ?? [];
?>
<div class="bg-white rounded-[1.5rem] shadow-sm border <?= $isRight ? 'border-pink-100' : 'border-gray-100' ?> p-8 flex flex-col h-full section-form-wrapper main-banner-form">
    <h3 class="text-xl font-serif font-bold text-gray-800 mb-6 border-b border-gray-100 pb-3"><?= htmlspecialchars($sectionTitle) ?></h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Text fields -->
        <div>
            <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Button Text</label>
            <input type="text" name="<?= $prefix ?>_button_text" value="<?= htmlspecialchars($data['button_text'] ?? 'Shop Now') ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown outline-none">
        </div>
        <div>
            <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">CTA URL</label>
            <input type="text" name="<?= $prefix ?>_cta_url" value="<?= htmlspecialchars($data['cta_url'] ?? '') ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown outline-none">
        </div>

        <!-- Settings -->
        <div>
            <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2 text-[#F25996]">Image Fit</label>
            <select name="<?= $prefix ?>_image_fit" class="w-full w-full border-2 border-pink-200 rounded-xl px-4 py-2.5 font-bold focus:border-[#F25996] focus:ring-2 focus:ring-[#F25996]/20 outline-none bg-white text-gray-800 cursor-pointer">
                <?php $fit = $data['image_fit'] ?? 'cover'; ?>
                <option value="cover" <?= $fit === 'cover' ? 'selected' : '' ?>>Cover (Fills, Crops)</option>
                <option value="contain" <?= $fit === 'contain' ? 'selected' : '' ?>>Contain (Full Image, Empty Space)</option>
                <option value="fill" <?= $fit === 'fill' ? 'selected' : '' ?>>Stretch (Fills, Distorts)</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Animation</label>
            <select name="<?= $prefix ?>_animation" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown outline-none bg-white">
                <?php $anim = $data['animation'] ?? 'fade'; ?>
                <option value="fade" <?= $anim === 'fade' ? 'selected' : '' ?>>Fade</option>
                <option value="slide_left" <?= $anim === 'slide_left' ? 'selected' : '' ?>>Slide Left</option>
                <option value="slide_right" <?= $anim === 'slide_right' ? 'selected' : '' ?>>Slide Right</option>
                <option value="zoom" <?= $anim === 'zoom' ? 'selected' : '' ?>>Zoom</option>
            </select>
        </div>
        <div class="col-span-full grid grid-cols-2 gap-6">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="<?= $prefix ?>_show_arrows" value="1" class="w-4 h-4 text-brown rounded" <?= !isset($data['show_arrows']) || $data['show_arrows'] ? 'checked' : '' ?>>
                <span class="text-sm font-medium text-gray-700">Show Arrows</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="<?= $prefix ?>_show_dots" value="1" class="w-4 h-4 text-brown rounded" <?= !isset($data['show_dots']) || $data['show_dots'] ? 'checked' : '' ?>>
                <span class="text-sm font-medium text-gray-700">Show Dots</span>
            </label>
        </div>

        <!-- Dates -->
        <div class="col-span-full grid grid-cols-2 gap-6">
        <div>
            <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Start Date (Optional)</label>
            <input type="date" name="<?= $prefix ?>_start_date" value="<?= htmlspecialchars($data['start_date'] ?? '') ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown outline-none">
        </div>
        <div>
            <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">End Date (Optional)</label>
            <input type="date" name="<?= $prefix ?>_end_date" value="<?= htmlspecialchars($data['end_date'] ?? '') ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown outline-none">
        </div>
        </div>

        <?php if ($isRight): ?>
        <div class="col-span-full">
            <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2 text-[#F25996]">Dynamic Content Type</label>
            <select name="<?= $prefix ?>_content_type" class="content-type-select w-full w-full border-2 border-pink-200 rounded-xl px-4 py-3 font-bold focus:border-[#F25996] focus:ring-2 focus:ring-[#F25996]/20 outline-none bg-white text-gray-800 cursor-pointer" data-prefix="<?= $prefix ?>">
                <?php $ct = $data['content_type'] ?? 'custom_image'; ?>
                <option value="custom_image" <?= $ct === 'custom_image' ? 'selected' : '' ?>>Custom Images / Slider (Upload Media Below)</option>
                <option value="product_carousel" <?= $ct === 'product_carousel' ? 'selected' : '' ?>>All Products</option>
                <option value="selected_products" <?= $ct === 'selected_products' ? 'selected' : '' ?>>Selected Products (Manual Pick)</option>
                <option value="category_grid" <?= $ct === 'category_grid' ? 'selected' : '' ?>>All Categories</option>
                <option value="selected_categories" <?= $ct === 'selected_categories' ? 'selected' : '' ?>>Selected Categories (Manual Pick)</option>
            </select>
        </div>
        <?php endif; ?>
        
        <?php if (!$isRight): ?>
        <!-- Left Container fields -->
        <div class="col-span-full grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Title</label>
                <input type="text" name="<?= $prefix ?>_title" value="<?= htmlspecialchars($data['title'] ?? '') ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown outline-none">
            </div>
            <div>
                <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Subtitle</label>
                <input type="text" name="<?= $prefix ?>_subtitle" value="<?= htmlspecialchars($data['subtitle'] ?? '') ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown outline-none">
            </div>
            <div class="col-span-full">
                <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Description</label>
                <textarea name="<?= $prefix ?>_description" rows="2" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown outline-none"><?= htmlspecialchars($data['description'] ?? '') ?></textarea>
            </div>
            <div class="col-span-full">
                <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Text Alignment</label>
                <select name="<?= $prefix ?>_alignment" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown outline-none bg-white">
                    <?php $align = $data['alignment'] ?? 'center'; ?>
                    <option value="left" <?= $align === 'left' ? 'selected' : '' ?>>Left Align</option>
                    <option value="center" <?= $align === 'center' ? 'selected' : '' ?>>Center Align</option>
                    <option value="right" <?= $align === 'right' ? 'selected' : '' ?>>Right Align</option>
                    <option value="bottom" <?= $align === 'bottom' ? 'selected' : '' ?>>Bottom Align</option>
                </select>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Color Theme -->
        <div class="col-span-full">
            <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Color Theme</label>
            
                <?php 
                $themeType = $theme['type'] ?? 'solid';
                $themeValue = $theme['value'] ?? '#ffffff';
                
                $solidColor = '#ffffff';
                $linear1 = '#8c6a4f'; $linear2 = '#c4a882'; $linearDir = 'to right';
                $radial1 = '#ffffff'; $radial2 = '#8c6a4f';
                $cssValue = '';
                
                if ($themeType === 'solid') {
                    $solidColor = $themeValue;
                } elseif ($themeType === 'linear' && preg_match('/linear-gradient\(([^,]+),\s*([^,]+),\s*([^)]+)\)/', $themeValue, $m)) {
                    $linearDir = trim($m[1]);
                    $linear1 = trim($m[2]);
                    $linear2 = trim($m[3]);
                } elseif ($themeType === 'radial' && preg_match('/radial-gradient\(circle,\s*([^,]+),\s*([^)]+)\)/', $themeValue, $m)) {
                    $radial1 = trim($m[1]);
                    $radial2 = trim($m[2]);
                } elseif ($themeType === 'css') {
                    $cssValue = $themeValue;
                }
                ?>
                
                <input type="hidden" name="<?= $prefix ?>_color_theme_type" class="theme-type" value="<?= $themeType ?>">
                <input type="hidden" name="<?= $prefix ?>_color_theme_value" class="theme-value" value="<?= htmlspecialchars($themeValue) ?>">

                <div class="border border-gray-200 rounded-xl p-4 space-y-4 mb-6">
                    <!-- Type Tabs -->
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" data-type="solid" class="color-type-btn <?= $themeType === 'solid' ? 'active bg-gray-900 text-white border-gray-300' : 'bg-white text-gray-600 border-gray-200' ?> px-4 py-1.5 rounded-full text-xs font-bold border transition-all">Solid</button>
                        <button type="button" data-type="linear" class="color-type-btn <?= $themeType === 'linear' ? 'active bg-gray-900 text-white border-gray-300' : 'bg-white text-gray-600 border-gray-200' ?> px-4 py-1.5 rounded-full text-xs font-bold border transition-all hover:border-gray-400">Linear Gradient</button>
                        <button type="button" data-type="radial" class="color-type-btn <?= $themeType === 'radial' ? 'active bg-gray-900 text-white border-gray-300' : 'bg-white text-gray-600 border-gray-200' ?> px-4 py-1.5 rounded-full text-xs font-bold border transition-all hover:border-gray-400">Radial Gradient</button>
                        <button type="button" data-type="css" class="color-type-btn <?= $themeType === 'css' ? 'active bg-gray-900 text-white border-gray-300' : 'bg-white text-gray-600 border-gray-200' ?> px-4 py-1.5 rounded-full text-xs font-bold border transition-all hover:border-gray-400">Color Link (CSS)</button>
                    </div>

                    <!-- Solid Panel -->
                    <div class="panel-solid flex items-center gap-4 <?= $themeType !== 'solid' ? 'hidden' : '' ?>">
                        <label class="text-xs text-gray-500 font-medium w-12">Color</label>
                        <input type="color" class="color-solid-picker w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5 bg-white" value="<?= $solidColor ?>">
                        <input type="text" class="color-solid-input w-28 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none focus:border-brown font-mono" value="<?= $solidColor ?>">
                    </div>

                    <!-- Linear Gradient Panel -->
                    <div class="panel-linear <?= $themeType !== 'linear' ? 'hidden' : '' ?> space-y-3">
                        <div class="flex items-center gap-4">
                            <label class="text-xs text-gray-500 font-medium w-16">From</label>
                            <input type="color" class="color-linear-picker-1 w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5 bg-white" value="<?= $linear1 ?>">
                            <input type="text" class="color-linear-1 w-28 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none focus:border-brown font-mono" value="<?= $linear1 ?>">
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="text-xs text-gray-500 font-medium w-16">To</label>
                            <input type="color" class="color-linear-picker-2 w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5 bg-white" value="<?= $linear2 ?>">
                            <input type="text" class="color-linear-2 w-28 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none focus:border-brown font-mono" value="<?= $linear2 ?>">
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="text-xs text-gray-500 font-medium w-16">Direction</label>
                            <select class="color-linear-dir border border-gray-200 rounded-lg px-3 py-1.5 text-sm bg-white outline-none focus:border-brown w-40">
                                <option value="to right" <?= $linearDir === 'to right' ? 'selected' : '' ?>>Left &rarr; Right</option>
                                <option value="to left" <?= $linearDir === 'to left' ? 'selected' : '' ?>>Right &rarr; Left</option>
                                <option value="to bottom" <?= $linearDir === 'to bottom' ? 'selected' : '' ?>>Top &rarr; Bottom</option>
                                <option value="to top" <?= $linearDir === 'to top' ? 'selected' : '' ?>>Bottom &rarr; Top</option>
                                <option value="to bottom right" <?= $linearDir === 'to bottom right' ? 'selected' : '' ?>>Top-Left &rarr; Bottom-Right</option>
                                <option value="to bottom left" <?= $linearDir === 'to bottom left' ? 'selected' : '' ?>>Top-Right &rarr; Bottom-Left</option>
                            </select>
                        </div>
                    </div>

                    <!-- Radial Gradient Panel -->
                    <div class="panel-radial <?= $themeType !== 'radial' ? 'hidden' : '' ?> space-y-3">
                        <div class="flex items-center gap-4">
                            <label class="text-xs text-gray-500 font-medium w-16">Center</label>
                            <input type="color" class="color-radial-picker-1 w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5 bg-white" value="<?= $radial1 ?>">
                            <input type="text" class="color-radial-1 w-28 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none focus:border-brown font-mono" value="<?= $radial1 ?>">
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="text-xs text-gray-500 font-medium w-16">Edge</label>
                            <input type="color" class="color-radial-picker-2 w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5 bg-white" value="<?= $radial2 ?>">
                            <input type="text" class="color-radial-2 w-28 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none focus:border-brown font-mono" value="<?= $radial2 ?>">
                        </div>
                    </div>

                    <!-- CSS Panel -->
                    <div class="panel-css flex items-center gap-4 <?= $themeType !== 'css' ? 'hidden' : '' ?>">
                        <label class="text-xs text-gray-500 font-medium w-12">Value</label>
                        <input type="text" class="color-css-input w-full border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none focus:border-brown font-mono" value="<?= htmlspecialchars($cssValue ?? '') ?>" placeholder="var(--color-name) or rgba(0,0,0,0.5)">
                    </div>

                    <!-- Component Colors -->
                    <div class="border-t border-gray-100 pt-4 mt-4">
                        <div class="flex items-center justify-between mb-4">
                            <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider">Component Colors</label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="masterColorLinkToggle w-4 h-4 text-[#F25996] rounded border-gray-300 focus:ring-[#F25996]">
                                <span class="text-xs font-bold text-gray-700">Use Custom Color Link / CSS</span>
                            </label>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <?php if (!$isRight): ?>
                            <?php
                            $renderColorField('Title Color', $prefix.'_title_color', $theme['title_color'] ?? '#ffffff');
                            $renderColorField('Subtitle Color', $prefix.'_subtitle_color', $theme['subtitle_color'] ?? '#ffffff');
                            $renderColorField('Description Color', $prefix.'_description_color', $theme['description_color'] ?? '#ffffff');
                            ?>
                            <?php endif; ?>
                            


                            <?php
                            $renderColorField('Button BG', $prefix.'_button_bg_color', $theme['button_bg_color'] ?? '#ffffff');
                            $renderColorField('Button Text', $prefix.'_button_text_color', $theme['button_text_color'] ?? '#000000');
                            $renderColorField('Arrow BG', $prefix.'_arrow_bg_color', $theme['arrow_bg_color'] ?? '#ffffff');
                            $renderColorField('Arrow Icon', $prefix.'_arrow_color', $theme['arrow_color'] ?? '#000000');
                            $renderColorField('Dot Color', $prefix.'_dot_color', $theme['dot_color'] ?? '#ffffff');
                            ?>
                        </div>
                    </div>
                    
                    <div class="pt-2">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Preview</label>
                        <div class="h-12 rounded-lg border border-gray-200 theme-preview-box w-full" style="background: <?= $themeValue ?>"></div>
                        <div class="text-[10px] text-gray-400 font-mono mt-1 theme-preview-text" id="<?= $prefix ?>-theme-text-preview"><?= htmlspecialchars($themeValue) ?></div>
                    </div>
                </div>
        </div>
    </div>
    
    <div class="media-section mt-auto pt-6 border-t border-gray-100" data-prefix="<?= $prefix ?>">
        <div class="flex items-center justify-between mb-4">
            <label class="text-xs font-serif font-bold text-gray-600 uppercase tracking-wider">Media Items <span class="lowercase text-gray-400 font-normal">(Multi-image / Multi-video)</span></label>
            <button type="button" class="btn-add-media text-xs font-bold text-gray-700 bg-white border border-gray-200 px-4 py-2 rounded-full hover:bg-gray-50 transition-colors shadow-sm flex items-center gap-2" data-prefix="<?= $prefix ?>">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Item
            </button>
        </div>

        <div class="media-container space-y-4" id="media-<?= $prefix ?>">
            <?php if (empty($media)): ?>
            <div class="empty-msg bg-gray-50 text-gray-400 text-sm text-center py-6 rounded-lg border border-dashed border-gray-200">
                No media items added yet. Click 'Add Item'.
            </div>
            <?php else: ?>
                <div class="empty-msg hidden bg-gray-50 text-gray-400 text-sm text-center py-6 rounded-lg border border-dashed border-gray-200">
                    No media items added yet. Click 'Add Item'.
                </div>
                <?php foreach($media as $m): ?>
                <div class="media-row relative group bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                    <button type="button" class="btn-remove-media absolute top-4 right-4 text-red-400 hover:text-red-600 transition-colors z-10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                    
                    <div class="mb-4 w-1/3">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Type</label>
                        <select name="<?= $prefix ?>_media_type[]" class="media-type-input w-full border border-gray-200 rounded-md px-3 py-2 text-sm bg-white outline-none focus:border-brown">
                            <option value="image" <?= $m['type'] === 'image' ? 'selected' : '' ?>>Image</option>
                            <option value="video" <?= $m['type'] === 'video' ? 'selected' : '' ?>>Video</option>
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Desktop -->
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Desktop</label>
                            
                            <div class="desktop-preview h-32 mb-2 bg-gray-50 border-2 border-dashed border-gray-200 rounded-lg flex flex-col items-center justify-center overflow-hidden hover:bg-gray-100 transition-colors cursor-pointer">
                                <?php if(empty($m['desktop'])): ?>
                                    <svg class="w-6 h-6 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    <span class="text-xs text-gray-400 font-medium">Click or drag to upload</span>
                                <?php elseif($m['type'] === 'video'): ?>
                                    <video src="<?= htmlspecialchars($m['desktop']) ?>" class="w-full h-full object-cover" muted></video>
                                <?php else: ?>
                                    <img src="<?= htmlspecialchars($m['desktop']) ?>" class="w-full h-full object-cover">
                                <?php endif; ?>
                            </div>
                            
                            <div class="flex gap-2">
                                <input type="text" name="<?= $prefix ?>_media_desktop[]" value="<?= htmlspecialchars($m['desktop'] ?? '') ?>" class="desktop-url flex-1 border border-gray-200 rounded-md px-3 py-2 text-xs outline-none focus:border-brown" placeholder="Paste URL here...">
                            </div>
                        </div>

                        <!-- Mobile -->
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Mobile</label>
                            
                            <div class="mobile-preview h-32 mb-2 bg-gray-50 border-2 border-dashed border-gray-200 rounded-lg flex flex-col items-center justify-center overflow-hidden hover:bg-gray-100 transition-colors cursor-pointer">
                                <?php if(empty($m['mobile'])): ?>
                                    <svg class="w-6 h-6 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    <span class="text-xs text-gray-400 font-medium">Click or drag to upload</span>
                                <?php elseif($m['type'] === 'video'): ?>
                                    <video src="<?= htmlspecialchars($m['mobile']) ?>" class="w-full h-full object-cover" muted></video>
                                <?php else: ?>
                                    <img src="<?= htmlspecialchars($m['mobile']) ?>" class="w-full h-full object-cover">
                                <?php endif; ?>
                            </div>
                            
                            <div class="flex gap-2">
                                <input type="text" name="<?= $prefix ?>_media_mobile[]" value="<?= htmlspecialchars($m['mobile'] ?? '') ?>" class="mobile-url flex-1 border border-gray-200 rounded-md px-3 py-2 text-xs outline-none focus:border-brown" placeholder="Paste URL here...">
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Dynamic Fetch Info Message -->
    <div class="dynamic-info-section hidden border-t border-gray-100 pt-6" data-prefix="<?= $prefix ?>">
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
            <h4 class="text-gray-800 font-bold text-sm mb-4 dynamic-info-title">Automatic Fetching Preview</h4>
            <div class="dynamic-preview-grid flex flex-col gap-2 max-h-72 overflow-y-auto pr-1">
                <!-- JS will populate preview items here -->
            </div>
        </div>
    </div>
    
    <!-- Manual Selection UI Block -->
    <div class="manual-selection-section hidden border-t border-gray-100 pt-6" data-prefix="<?= $prefix ?>">
        <div class="bg-pink-50/60 border border-pink-200 rounded-xl p-4">
            <h4 class="text-pink-900 font-bold text-sm mb-4 manual-selection-title">Select Items</h4>
            
            <!-- Filter Input -->
            <div class="mb-4 relative">
                <input type="text" class="manual-selection-search w-full border border-pink-300 rounded-lg pl-10 pr-4 py-2 text-sm outline-none focus:border-[#F25996] bg-white" placeholder="Search to filter..." data-prefix="<?= $prefix ?>">
                <svg class="w-4 h-4 text-pink-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            
            <!-- Scrollable Checkbox List -->
            <div class="manual-selection-list h-64 overflow-y-auto bg-white border border-pink-200 rounded-lg p-2 space-y-1">
                <!-- JS will populate checklists here -->
            </div>
            
            <?php 
                $selProds = isset($data['selected_products']) ? json_encode($data['selected_products']) : '[]';
                $selCats = isset($data['selected_categories']) ? json_encode($data['selected_categories']) : '[]';
            ?>
            <!-- Hidden inputs to hold the selected items on edit -->
            <input type="hidden" class="pre-selected-products" value='<?= htmlspecialchars($selProds) ?>'>
            <input type="hidden" class="pre-selected-categories" value='<?= htmlspecialchars($selCats) ?>'>
        </div>
    </div>
</div>
 

                </div>

                <!-- Right Container (Top) -->
                <div class="flex flex-col h-full">
                    <?php 
                    $prefix = 'right_top';
                    $sectionTitle = 'Right Container (Top)';
                    $isRight = true;
                    // Expected variables:
// $prefix - e.g. 'left', 'right_top', 'right_bottom'
// $sectionTitle - e.g. 'Left Container'
// $isRight - boolean
// $data - array (for edit)
// $theme - array (for edit)
// $media - array (for edit)

$data = $data ?? [];
$theme = $theme ?? [];
$media = $media ?? [];
?>
<div class="bg-white rounded-[1.5rem] shadow-sm border <?= $isRight ? 'border-pink-100' : 'border-gray-100' ?> p-8 flex flex-col h-full section-form-wrapper">
    <h3 class="text-xl font-serif font-bold text-gray-800 mb-6 border-b border-gray-100 pb-3"><?= htmlspecialchars($sectionTitle) ?></h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Text fields -->
        <div>
            <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Button Text</label>
            <input type="text" name="<?= $prefix ?>_button_text" value="<?= htmlspecialchars($data['button_text'] ?? 'Shop Now') ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown outline-none">
        </div>
        <div>
            <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">CTA URL</label>
            <input type="text" name="<?= $prefix ?>_cta_url" value="<?= htmlspecialchars($data['cta_url'] ?? '') ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown outline-none">
        </div>

        <!-- Settings -->
        <div>
            <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2 text-[#F25996]">Image Fit</label>
            <select name="<?= $prefix ?>_image_fit" class="w-full w-full border-2 border-pink-200 rounded-xl px-4 py-2.5 font-bold focus:border-[#F25996] focus:ring-2 focus:ring-[#F25996]/20 outline-none bg-white text-gray-800 cursor-pointer">
                <?php $fit = $data['image_fit'] ?? 'cover'; ?>
                <option value="cover" <?= $fit === 'cover' ? 'selected' : '' ?>>Cover (Fills, Crops)</option>
                <option value="contain" <?= $fit === 'contain' ? 'selected' : '' ?>>Contain (Full Image, Empty Space)</option>
                <option value="fill" <?= $fit === 'fill' ? 'selected' : '' ?>>Stretch (Fills, Distorts)</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Animation</label>
            <select name="<?= $prefix ?>_animation" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown outline-none bg-white">
                <?php $anim = $data['animation'] ?? 'fade'; ?>
                <option value="fade" <?= $anim === 'fade' ? 'selected' : '' ?>>Fade</option>
                <option value="slide_left" <?= $anim === 'slide_left' ? 'selected' : '' ?>>Slide Left</option>
                <option value="slide_right" <?= $anim === 'slide_right' ? 'selected' : '' ?>>Slide Right</option>
                <option value="zoom" <?= $anim === 'zoom' ? 'selected' : '' ?>>Zoom</option>
            </select>
        </div>
        <div class="col-span-full grid grid-cols-2 gap-6">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="<?= $prefix ?>_show_arrows" value="1" class="w-4 h-4 text-brown rounded" <?= !isset($data['show_arrows']) || $data['show_arrows'] ? 'checked' : '' ?>>
                <span class="text-sm font-medium text-gray-700">Show Arrows</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="<?= $prefix ?>_show_dots" value="1" class="w-4 h-4 text-brown rounded" <?= !isset($data['show_dots']) || $data['show_dots'] ? 'checked' : '' ?>>
                <span class="text-sm font-medium text-gray-700">Show Dots</span>
            </label>
        </div>

        <!-- Dates -->
        <div class="col-span-full grid grid-cols-2 gap-6">
        <div>
            <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Start Date (Optional)</label>
            <input type="date" name="<?= $prefix ?>_start_date" value="<?= htmlspecialchars($data['start_date'] ?? '') ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown outline-none">
        </div>
        <div>
            <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">End Date (Optional)</label>
            <input type="date" name="<?= $prefix ?>_end_date" value="<?= htmlspecialchars($data['end_date'] ?? '') ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown outline-none">
        </div>
        </div>

        <?php if ($isRight): ?>
        <div class="col-span-full">
            <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2 text-[#F25996]">Dynamic Content Type</label>
            <select name="<?= $prefix ?>_content_type" class="content-type-select w-full w-full border-2 border-pink-200 rounded-xl px-4 py-3 font-bold focus:border-[#F25996] focus:ring-2 focus:ring-[#F25996]/20 outline-none bg-white text-gray-800 cursor-pointer" data-prefix="<?= $prefix ?>">
                <?php $ct = $data['content_type'] ?? 'custom_image'; ?>
                <option value="custom_image" <?= $ct === 'custom_image' ? 'selected' : '' ?>>Custom Images / Slider (Upload Media Below)</option>
                <option value="product_carousel" <?= $ct === 'product_carousel' ? 'selected' : '' ?>>All Products</option>
                <option value="selected_products" <?= $ct === 'selected_products' ? 'selected' : '' ?>>Selected Products (Manual Pick)</option>
                <option value="category_grid" <?= $ct === 'category_grid' ? 'selected' : '' ?>>All Categories</option>
                <option value="selected_categories" <?= $ct === 'selected_categories' ? 'selected' : '' ?>>Selected Categories (Manual Pick)</option>
            </select>
        </div>
        <?php endif; ?>
        
        <?php if (!$isRight): ?>
        <!-- Left Container fields -->
        <div class="col-span-full grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Title</label>
                <input type="text" name="<?= $prefix ?>_title" value="<?= htmlspecialchars($data['title'] ?? '') ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown outline-none">
            </div>
            <div>
                <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Subtitle</label>
                <input type="text" name="<?= $prefix ?>_subtitle" value="<?= htmlspecialchars($data['subtitle'] ?? '') ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown outline-none">
            </div>
            <div class="col-span-full">
                <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Description</label>
                <textarea name="<?= $prefix ?>_description" rows="2" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown outline-none"><?= htmlspecialchars($data['description'] ?? '') ?></textarea>
            </div>
            <div class="col-span-full">
                <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Text Alignment</label>
                <select name="<?= $prefix ?>_alignment" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown outline-none bg-white">
                    <?php $align = $data['alignment'] ?? 'center'; ?>
                    <option value="left" <?= $align === 'left' ? 'selected' : '' ?>>Left Align</option>
                    <option value="center" <?= $align === 'center' ? 'selected' : '' ?>>Center Align</option>
                    <option value="right" <?= $align === 'right' ? 'selected' : '' ?>>Right Align</option>
                    <option value="bottom" <?= $align === 'bottom' ? 'selected' : '' ?>>Bottom Align</option>
                </select>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Color Theme -->
        <div class="col-span-full">
            <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Color Theme</label>
            
                <?php 
                $themeType = $theme['type'] ?? 'solid';
                $themeValue = $theme['value'] ?? '#ffffff';
                
                $solidColor = '#ffffff';
                $linear1 = '#8c6a4f'; $linear2 = '#c4a882'; $linearDir = 'to right';
                $radial1 = '#ffffff'; $radial2 = '#8c6a4f';
                $cssValue = '';
                
                if ($themeType === 'solid') {
                    $solidColor = $themeValue;
                } elseif ($themeType === 'linear' && preg_match('/linear-gradient\(([^,]+),\s*([^,]+),\s*([^)]+)\)/', $themeValue, $m)) {
                    $linearDir = trim($m[1]);
                    $linear1 = trim($m[2]);
                    $linear2 = trim($m[3]);
                } elseif ($themeType === 'radial' && preg_match('/radial-gradient\(circle,\s*([^,]+),\s*([^)]+)\)/', $themeValue, $m)) {
                    $radial1 = trim($m[1]);
                    $radial2 = trim($m[2]);
                } elseif ($themeType === 'css') {
                    $cssValue = $themeValue;
                }
                ?>
                
                <input type="hidden" name="<?= $prefix ?>_color_theme_type" class="theme-type" value="<?= $themeType ?>">
                <input type="hidden" name="<?= $prefix ?>_color_theme_value" class="theme-value" value="<?= htmlspecialchars($themeValue) ?>">

                <div class="border border-gray-200 rounded-xl p-4 space-y-4 mb-6">
                    <!-- Type Tabs -->
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" data-type="solid" class="color-type-btn <?= $themeType === 'solid' ? 'active bg-gray-900 text-white border-gray-300' : 'bg-white text-gray-600 border-gray-200' ?> px-4 py-1.5 rounded-full text-xs font-bold border transition-all">Solid</button>
                        <button type="button" data-type="linear" class="color-type-btn <?= $themeType === 'linear' ? 'active bg-gray-900 text-white border-gray-300' : 'bg-white text-gray-600 border-gray-200' ?> px-4 py-1.5 rounded-full text-xs font-bold border transition-all hover:border-gray-400">Linear Gradient</button>
                        <button type="button" data-type="radial" class="color-type-btn <?= $themeType === 'radial' ? 'active bg-gray-900 text-white border-gray-300' : 'bg-white text-gray-600 border-gray-200' ?> px-4 py-1.5 rounded-full text-xs font-bold border transition-all hover:border-gray-400">Radial Gradient</button>
                        <button type="button" data-type="css" class="color-type-btn <?= $themeType === 'css' ? 'active bg-gray-900 text-white border-gray-300' : 'bg-white text-gray-600 border-gray-200' ?> px-4 py-1.5 rounded-full text-xs font-bold border transition-all hover:border-gray-400">Color Link (CSS)</button>
                    </div>

                    <!-- Solid Panel -->
                    <div class="panel-solid flex items-center gap-4 <?= $themeType !== 'solid' ? 'hidden' : '' ?>">
                        <label class="text-xs text-gray-500 font-medium w-12">Color</label>
                        <input type="color" class="color-solid-picker w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5 bg-white" value="<?= $solidColor ?>">
                        <input type="text" class="color-solid-input w-28 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none focus:border-brown font-mono" value="<?= $solidColor ?>">
                    </div>

                    <!-- Linear Gradient Panel -->
                    <div class="panel-linear <?= $themeType !== 'linear' ? 'hidden' : '' ?> space-y-3">
                        <div class="flex items-center gap-4">
                            <label class="text-xs text-gray-500 font-medium w-16">From</label>
                            <input type="color" class="color-linear-picker-1 w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5 bg-white" value="<?= $linear1 ?>">
                            <input type="text" class="color-linear-1 w-28 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none focus:border-brown font-mono" value="<?= $linear1 ?>">
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="text-xs text-gray-500 font-medium w-16">To</label>
                            <input type="color" class="color-linear-picker-2 w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5 bg-white" value="<?= $linear2 ?>">
                            <input type="text" class="color-linear-2 w-28 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none focus:border-brown font-mono" value="<?= $linear2 ?>">
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="text-xs text-gray-500 font-medium w-16">Direction</label>
                            <select class="color-linear-dir border border-gray-200 rounded-lg px-3 py-1.5 text-sm bg-white outline-none focus:border-brown w-40">
                                <option value="to right" <?= $linearDir === 'to right' ? 'selected' : '' ?>>Left &rarr; Right</option>
                                <option value="to left" <?= $linearDir === 'to left' ? 'selected' : '' ?>>Right &rarr; Left</option>
                                <option value="to bottom" <?= $linearDir === 'to bottom' ? 'selected' : '' ?>>Top &rarr; Bottom</option>
                                <option value="to top" <?= $linearDir === 'to top' ? 'selected' : '' ?>>Bottom &rarr; Top</option>
                                <option value="to bottom right" <?= $linearDir === 'to bottom right' ? 'selected' : '' ?>>Top-Left &rarr; Bottom-Right</option>
                                <option value="to bottom left" <?= $linearDir === 'to bottom left' ? 'selected' : '' ?>>Top-Right &rarr; Bottom-Left</option>
                            </select>
                        </div>
                    </div>

                    <!-- Radial Gradient Panel -->
                    <div class="panel-radial <?= $themeType !== 'radial' ? 'hidden' : '' ?> space-y-3">
                        <div class="flex items-center gap-4">
                            <label class="text-xs text-gray-500 font-medium w-16">Center</label>
                            <input type="color" class="color-radial-picker-1 w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5 bg-white" value="<?= $radial1 ?>">
                            <input type="text" class="color-radial-1 w-28 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none focus:border-brown font-mono" value="<?= $radial1 ?>">
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="text-xs text-gray-500 font-medium w-16">Edge</label>
                            <input type="color" class="color-radial-picker-2 w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5 bg-white" value="<?= $radial2 ?>">
                            <input type="text" class="color-radial-2 w-28 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none focus:border-brown font-mono" value="<?= $radial2 ?>">
                        </div>
                    </div>

                    <!-- CSS Panel -->
                    <div class="panel-css flex items-center gap-4 <?= $themeType !== 'css' ? 'hidden' : '' ?>">
                        <label class="text-xs text-gray-500 font-medium w-12">Value</label>
                        <input type="text" class="color-css-input w-full border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none focus:border-brown font-mono" value="<?= htmlspecialchars($cssValue ?? '') ?>" placeholder="var(--color-name) or rgba(0,0,0,0.5)">
                    </div>

                    <!-- Component Colors -->
                    <div class="border-t border-gray-100 pt-4 mt-4">
                        <div class="flex items-center justify-between mb-4">
                            <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider">Component Colors</label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="masterColorLinkToggle w-4 h-4 text-[#F25996] rounded border-gray-300 focus:ring-[#F25996]">
                                <span class="text-xs font-bold text-gray-700">Use Custom Color Link / CSS</span>
                            </label>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <?php if (!$isRight): ?>
                            <?php
                            $renderColorField('Title Color', $prefix.'_title_color', $theme['title_color'] ?? '#ffffff');
                            $renderColorField('Subtitle Color', $prefix.'_subtitle_color', $theme['subtitle_color'] ?? '#ffffff');
                            $renderColorField('Description Color', $prefix.'_description_color', $theme['description_color'] ?? '#ffffff');
                            ?>
                            <?php endif; ?>
                            


                            <?php
                            $renderColorField('Button BG', $prefix.'_button_bg_color', $theme['button_bg_color'] ?? '#ffffff');
                            $renderColorField('Button Text', $prefix.'_button_text_color', $theme['button_text_color'] ?? '#000000');
                            $renderColorField('Arrow BG', $prefix.'_arrow_bg_color', $theme['arrow_bg_color'] ?? '#ffffff');
                            $renderColorField('Arrow Icon', $prefix.'_arrow_color', $theme['arrow_color'] ?? '#000000');
                            $renderColorField('Dot Color', $prefix.'_dot_color', $theme['dot_color'] ?? '#ffffff');
                            ?>
                        </div>
                    </div>
                    
                    <div class="pt-2">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Preview</label>
                        <div class="h-12 rounded-lg border border-gray-200 theme-preview-box w-full" style="background: <?= $themeValue ?>"></div>
                        <div class="text-[10px] text-gray-400 font-mono mt-1 theme-preview-text" id="<?= $prefix ?>-theme-text-preview"><?= htmlspecialchars($themeValue) ?></div>
                    </div>
                </div>
        </div>
    </div>
    
    <div class="media-section mt-auto pt-6 border-t border-gray-100" data-prefix="<?= $prefix ?>">
        <div class="flex items-center justify-between mb-4">
            <label class="text-xs font-serif font-bold text-gray-600 uppercase tracking-wider">Media Items <span class="lowercase text-gray-400 font-normal">(Multi-image / Multi-video)</span></label>
            <button type="button" class="btn-add-media text-xs font-bold text-gray-700 bg-white border border-gray-200 px-4 py-2 rounded-full hover:bg-gray-50 transition-colors shadow-sm flex items-center gap-2" data-prefix="<?= $prefix ?>">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Item
            </button>
        </div>

        <div class="media-container space-y-4" id="media-<?= $prefix ?>">
            <?php if (empty($media)): ?>
            <div class="empty-msg bg-gray-50 text-gray-400 text-sm text-center py-6 rounded-lg border border-dashed border-gray-200">
                No media items added yet. Click 'Add Item'.
            </div>
            <?php else: ?>
                <div class="empty-msg hidden bg-gray-50 text-gray-400 text-sm text-center py-6 rounded-lg border border-dashed border-gray-200">
                    No media items added yet. Click 'Add Item'.
                </div>
                <?php foreach($media as $m): ?>
                <div class="media-row relative group bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                    <button type="button" class="btn-remove-media absolute top-4 right-4 text-red-400 hover:text-red-600 transition-colors z-10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                    
                    <div class="mb-4 w-1/3">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Type</label>
                        <select name="<?= $prefix ?>_media_type[]" class="media-type-input w-full border border-gray-200 rounded-md px-3 py-2 text-sm bg-white outline-none focus:border-brown">
                            <option value="image" <?= $m['type'] === 'image' ? 'selected' : '' ?>>Image</option>
                            <option value="video" <?= $m['type'] === 'video' ? 'selected' : '' ?>>Video</option>
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Desktop -->
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Desktop</label>
                            
                            <div class="desktop-preview h-32 mb-2 bg-gray-50 border-2 border-dashed border-gray-200 rounded-lg flex flex-col items-center justify-center overflow-hidden hover:bg-gray-100 transition-colors cursor-pointer">
                                <?php if(empty($m['desktop'])): ?>
                                    <svg class="w-6 h-6 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    <span class="text-xs text-gray-400 font-medium">Click or drag to upload</span>
                                <?php elseif($m['type'] === 'video'): ?>
                                    <video src="<?= htmlspecialchars($m['desktop']) ?>" class="w-full h-full object-cover" muted></video>
                                <?php else: ?>
                                    <img src="<?= htmlspecialchars($m['desktop']) ?>" class="w-full h-full object-cover">
                                <?php endif; ?>
                            </div>
                            
                            <div class="flex gap-2">
                                <input type="text" name="<?= $prefix ?>_media_desktop[]" value="<?= htmlspecialchars($m['desktop'] ?? '') ?>" class="desktop-url flex-1 border border-gray-200 rounded-md px-3 py-2 text-xs outline-none focus:border-brown" placeholder="Paste URL here...">
                            </div>
                        </div>

                        <!-- Mobile -->
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Mobile</label>
                            
                            <div class="mobile-preview h-32 mb-2 bg-gray-50 border-2 border-dashed border-gray-200 rounded-lg flex flex-col items-center justify-center overflow-hidden hover:bg-gray-100 transition-colors cursor-pointer">
                                <?php if(empty($m['mobile'])): ?>
                                    <svg class="w-6 h-6 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    <span class="text-xs text-gray-400 font-medium">Click or drag to upload</span>
                                <?php elseif($m['type'] === 'video'): ?>
                                    <video src="<?= htmlspecialchars($m['mobile']) ?>" class="w-full h-full object-cover" muted></video>
                                <?php else: ?>
                                    <img src="<?= htmlspecialchars($m['mobile']) ?>" class="w-full h-full object-cover">
                                <?php endif; ?>
                            </div>
                            
                            <div class="flex gap-2">
                                <input type="text" name="<?= $prefix ?>_media_mobile[]" value="<?= htmlspecialchars($m['mobile'] ?? '') ?>" class="mobile-url flex-1 border border-gray-200 rounded-md px-3 py-2 text-xs outline-none focus:border-brown" placeholder="Paste URL here...">
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Dynamic Fetch Info Message -->
    <div class="dynamic-info-section hidden border-t border-gray-100 pt-6" data-prefix="<?= $prefix ?>">
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
            <h4 class="text-gray-800 font-bold text-sm mb-4 dynamic-info-title">Automatic Fetching Preview</h4>
            <div class="dynamic-preview-grid flex flex-col gap-2 max-h-72 overflow-y-auto pr-1">
                <!-- JS will populate preview items here -->
            </div>
        </div>
    </div>
    
    <!-- Manual Selection UI Block -->
    <div class="manual-selection-section hidden border-t border-gray-100 pt-6" data-prefix="<?= $prefix ?>">
        <div class="bg-pink-50/60 border border-pink-200 rounded-xl p-4">
            <h4 class="text-pink-900 font-bold text-sm mb-4 manual-selection-title">Select Items</h4>
            
            <!-- Filter Input -->
            <div class="mb-4 relative">
                <input type="text" class="manual-selection-search w-full border border-pink-300 rounded-lg pl-10 pr-4 py-2 text-sm outline-none focus:border-[#F25996] bg-white" placeholder="Search to filter..." data-prefix="<?= $prefix ?>">
                <svg class="w-4 h-4 text-pink-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            
            <!-- Scrollable Checkbox List -->
            <div class="manual-selection-list h-64 overflow-y-auto bg-white border border-pink-200 rounded-lg p-2 space-y-1">
                <!-- JS will populate checklists here -->
            </div>
            
            <?php 
                $selProds = isset($data['selected_products']) ? json_encode($data['selected_products']) : '[]';
                $selCats = isset($data['selected_categories']) ? json_encode($data['selected_categories']) : '[]';
            ?>
            <!-- Hidden inputs to hold the selected items on edit -->
            <input type="hidden" class="pre-selected-products" value='<?= htmlspecialchars($selProds) ?>'>
            <input type="hidden" class="pre-selected-categories" value='<?= htmlspecialchars($selCats) ?>'>
        </div>
    </div>
</div>
 

                </div>

                <!-- Right Container (Bottom) -->
                <div class="flex flex-col h-full">
                    <?php 
                    $prefix = 'right_bottom';
                    $sectionTitle = 'Right Container (Bottom)';
                    $isRight = true;
                    // Expected variables:
// $prefix - e.g. 'left', 'right_top', 'right_bottom'
// $sectionTitle - e.g. 'Left Container'
// $isRight - boolean
// $data - array (for edit)
// $theme - array (for edit)
// $media - array (for edit)

$data = $data ?? [];
$theme = $theme ?? [];
$media = $media ?? [];
?>
<div class="bg-white rounded-[1.5rem] shadow-sm border <?= $isRight ? 'border-pink-100' : 'border-gray-100' ?> p-8 flex flex-col h-full section-form-wrapper">
    <h3 class="text-xl font-serif font-bold text-gray-800 mb-6 border-b border-gray-100 pb-3"><?= htmlspecialchars($sectionTitle) ?></h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Text fields -->
        <div>
            <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Button Text</label>
            <input type="text" name="<?= $prefix ?>_button_text" value="<?= htmlspecialchars($data['button_text'] ?? 'Shop Now') ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown outline-none">
        </div>
        <div>
            <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">CTA URL</label>
            <input type="text" name="<?= $prefix ?>_cta_url" value="<?= htmlspecialchars($data['cta_url'] ?? '') ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown outline-none">
        </div>

        <!-- Settings -->
        <div>
            <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2 text-[#F25996]">Image Fit</label>
            <select name="<?= $prefix ?>_image_fit" class="w-full w-full border-2 border-pink-200 rounded-xl px-4 py-2.5 font-bold focus:border-[#F25996] focus:ring-2 focus:ring-[#F25996]/20 outline-none bg-white text-gray-800 cursor-pointer">
                <?php $fit = $data['image_fit'] ?? 'cover'; ?>
                <option value="cover" <?= $fit === 'cover' ? 'selected' : '' ?>>Cover (Fills, Crops)</option>
                <option value="contain" <?= $fit === 'contain' ? 'selected' : '' ?>>Contain (Full Image, Empty Space)</option>
                <option value="fill" <?= $fit === 'fill' ? 'selected' : '' ?>>Stretch (Fills, Distorts)</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Animation</label>
            <select name="<?= $prefix ?>_animation" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown outline-none bg-white">
                <?php $anim = $data['animation'] ?? 'fade'; ?>
                <option value="fade" <?= $anim === 'fade' ? 'selected' : '' ?>>Fade</option>
                <option value="slide_left" <?= $anim === 'slide_left' ? 'selected' : '' ?>>Slide Left</option>
                <option value="slide_right" <?= $anim === 'slide_right' ? 'selected' : '' ?>>Slide Right</option>
                <option value="zoom" <?= $anim === 'zoom' ? 'selected' : '' ?>>Zoom</option>
            </select>
        </div>
        <div class="col-span-full grid grid-cols-2 gap-6">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="<?= $prefix ?>_show_arrows" value="1" class="w-4 h-4 text-brown rounded" <?= !isset($data['show_arrows']) || $data['show_arrows'] ? 'checked' : '' ?>>
                <span class="text-sm font-medium text-gray-700">Show Arrows</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="<?= $prefix ?>_show_dots" value="1" class="w-4 h-4 text-brown rounded" <?= !isset($data['show_dots']) || $data['show_dots'] ? 'checked' : '' ?>>
                <span class="text-sm font-medium text-gray-700">Show Dots</span>
            </label>
        </div>

        <!-- Dates -->
        <div class="col-span-full grid grid-cols-2 gap-6">
        <div>
            <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Start Date (Optional)</label>
            <input type="date" name="<?= $prefix ?>_start_date" value="<?= htmlspecialchars($data['start_date'] ?? '') ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown outline-none">
        </div>
        <div>
            <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">End Date (Optional)</label>
            <input type="date" name="<?= $prefix ?>_end_date" value="<?= htmlspecialchars($data['end_date'] ?? '') ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown outline-none">
        </div>
        </div>

        <?php if ($isRight): ?>
        <div class="col-span-full">
            <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2 text-[#F25996]">Dynamic Content Type</label>
            <select name="<?= $prefix ?>_content_type" class="content-type-select w-full w-full border-2 border-pink-200 rounded-xl px-4 py-3 font-bold focus:border-[#F25996] focus:ring-2 focus:ring-[#F25996]/20 outline-none bg-white text-gray-800 cursor-pointer" data-prefix="<?= $prefix ?>">
                <?php $ct = $data['content_type'] ?? 'custom_image'; ?>
                <option value="custom_image" <?= $ct === 'custom_image' ? 'selected' : '' ?>>Custom Images / Slider (Upload Media Below)</option>
                <option value="product_carousel" <?= $ct === 'product_carousel' ? 'selected' : '' ?>>All Products</option>
                <option value="selected_products" <?= $ct === 'selected_products' ? 'selected' : '' ?>>Selected Products (Manual Pick)</option>
                <option value="category_grid" <?= $ct === 'category_grid' ? 'selected' : '' ?>>All Categories</option>
                <option value="selected_categories" <?= $ct === 'selected_categories' ? 'selected' : '' ?>>Selected Categories (Manual Pick)</option>
            </select>
        </div>
        <?php endif; ?>
        
        <?php if (!$isRight): ?>
        <!-- Left Container fields -->
        <div class="col-span-full grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Title</label>
                <input type="text" name="<?= $prefix ?>_title" value="<?= htmlspecialchars($data['title'] ?? '') ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown outline-none">
            </div>
            <div>
                <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Subtitle</label>
                <input type="text" name="<?= $prefix ?>_subtitle" value="<?= htmlspecialchars($data['subtitle'] ?? '') ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown outline-none">
            </div>
            <div class="col-span-full">
                <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Description</label>
                <textarea name="<?= $prefix ?>_description" rows="2" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown outline-none"><?= htmlspecialchars($data['description'] ?? '') ?></textarea>
            </div>
            <div class="col-span-full">
                <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Text Alignment</label>
                <select name="<?= $prefix ?>_alignment" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown outline-none bg-white">
                    <?php $align = $data['alignment'] ?? 'center'; ?>
                    <option value="left" <?= $align === 'left' ? 'selected' : '' ?>>Left Align</option>
                    <option value="center" <?= $align === 'center' ? 'selected' : '' ?>>Center Align</option>
                    <option value="right" <?= $align === 'right' ? 'selected' : '' ?>>Right Align</option>
                    <option value="bottom" <?= $align === 'bottom' ? 'selected' : '' ?>>Bottom Align</option>
                </select>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Color Theme -->
        <div class="col-span-full">
            <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Color Theme</label>
            
                <?php 
                $themeType = $theme['type'] ?? 'solid';
                $themeValue = $theme['value'] ?? '#ffffff';
                
                $solidColor = '#ffffff';
                $linear1 = '#8c6a4f'; $linear2 = '#c4a882'; $linearDir = 'to right';
                $radial1 = '#ffffff'; $radial2 = '#8c6a4f';
                $cssValue = '';
                
                if ($themeType === 'solid') {
                    $solidColor = $themeValue;
                } elseif ($themeType === 'linear' && preg_match('/linear-gradient\(([^,]+),\s*([^,]+),\s*([^)]+)\)/', $themeValue, $m)) {
                    $linearDir = trim($m[1]);
                    $linear1 = trim($m[2]);
                    $linear2 = trim($m[3]);
                } elseif ($themeType === 'radial' && preg_match('/radial-gradient\(circle,\s*([^,]+),\s*([^)]+)\)/', $themeValue, $m)) {
                    $radial1 = trim($m[1]);
                    $radial2 = trim($m[2]);
                } elseif ($themeType === 'css') {
                    $cssValue = $themeValue;
                }
                ?>
                
                <input type="hidden" name="<?= $prefix ?>_color_theme_type" class="theme-type" value="<?= $themeType ?>">
                <input type="hidden" name="<?= $prefix ?>_color_theme_value" class="theme-value" value="<?= htmlspecialchars($themeValue) ?>">

                <div class="border border-gray-200 rounded-xl p-4 space-y-4 mb-6">
                    <!-- Type Tabs -->
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" data-type="solid" class="color-type-btn <?= $themeType === 'solid' ? 'active bg-gray-900 text-white border-gray-300' : 'bg-white text-gray-600 border-gray-200' ?> px-4 py-1.5 rounded-full text-xs font-bold border transition-all">Solid</button>
                        <button type="button" data-type="linear" class="color-type-btn <?= $themeType === 'linear' ? 'active bg-gray-900 text-white border-gray-300' : 'bg-white text-gray-600 border-gray-200' ?> px-4 py-1.5 rounded-full text-xs font-bold border transition-all hover:border-gray-400">Linear Gradient</button>
                        <button type="button" data-type="radial" class="color-type-btn <?= $themeType === 'radial' ? 'active bg-gray-900 text-white border-gray-300' : 'bg-white text-gray-600 border-gray-200' ?> px-4 py-1.5 rounded-full text-xs font-bold border transition-all hover:border-gray-400">Radial Gradient</button>
                        <button type="button" data-type="css" class="color-type-btn <?= $themeType === 'css' ? 'active bg-gray-900 text-white border-gray-300' : 'bg-white text-gray-600 border-gray-200' ?> px-4 py-1.5 rounded-full text-xs font-bold border transition-all hover:border-gray-400">Color Link (CSS)</button>
                    </div>

                    <!-- Solid Panel -->
                    <div class="panel-solid flex items-center gap-4 <?= $themeType !== 'solid' ? 'hidden' : '' ?>">
                        <label class="text-xs text-gray-500 font-medium w-12">Color</label>
                        <input type="color" class="color-solid-picker w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5 bg-white" value="<?= $solidColor ?>">
                        <input type="text" class="color-solid-input w-28 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none focus:border-brown font-mono" value="<?= $solidColor ?>">
                    </div>

                    <!-- Linear Gradient Panel -->
                    <div class="panel-linear <?= $themeType !== 'linear' ? 'hidden' : '' ?> space-y-3">
                        <div class="flex items-center gap-4">
                            <label class="text-xs text-gray-500 font-medium w-16">From</label>
                            <input type="color" class="color-linear-picker-1 w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5 bg-white" value="<?= $linear1 ?>">
                            <input type="text" class="color-linear-1 w-28 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none focus:border-brown font-mono" value="<?= $linear1 ?>">
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="text-xs text-gray-500 font-medium w-16">To</label>
                            <input type="color" class="color-linear-picker-2 w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5 bg-white" value="<?= $linear2 ?>">
                            <input type="text" class="color-linear-2 w-28 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none focus:border-brown font-mono" value="<?= $linear2 ?>">
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="text-xs text-gray-500 font-medium w-16">Direction</label>
                            <select class="color-linear-dir border border-gray-200 rounded-lg px-3 py-1.5 text-sm bg-white outline-none focus:border-brown w-40">
                                <option value="to right" <?= $linearDir === 'to right' ? 'selected' : '' ?>>Left &rarr; Right</option>
                                <option value="to left" <?= $linearDir === 'to left' ? 'selected' : '' ?>>Right &rarr; Left</option>
                                <option value="to bottom" <?= $linearDir === 'to bottom' ? 'selected' : '' ?>>Top &rarr; Bottom</option>
                                <option value="to top" <?= $linearDir === 'to top' ? 'selected' : '' ?>>Bottom &rarr; Top</option>
                                <option value="to bottom right" <?= $linearDir === 'to bottom right' ? 'selected' : '' ?>>Top-Left &rarr; Bottom-Right</option>
                                <option value="to bottom left" <?= $linearDir === 'to bottom left' ? 'selected' : '' ?>>Top-Right &rarr; Bottom-Left</option>
                            </select>
                        </div>
                    </div>

                    <!-- Radial Gradient Panel -->
                    <div class="panel-radial <?= $themeType !== 'radial' ? 'hidden' : '' ?> space-y-3">
                        <div class="flex items-center gap-4">
                            <label class="text-xs text-gray-500 font-medium w-16">Center</label>
                            <input type="color" class="color-radial-picker-1 w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5 bg-white" value="<?= $radial1 ?>">
                            <input type="text" class="color-radial-1 w-28 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none focus:border-brown font-mono" value="<?= $radial1 ?>">
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="text-xs text-gray-500 font-medium w-16">Edge</label>
                            <input type="color" class="color-radial-picker-2 w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5 bg-white" value="<?= $radial2 ?>">
                            <input type="text" class="color-radial-2 w-28 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none focus:border-brown font-mono" value="<?= $radial2 ?>">
                        </div>
                    </div>

                    <!-- CSS Panel -->
                    <div class="panel-css flex items-center gap-4 <?= $themeType !== 'css' ? 'hidden' : '' ?>">
                        <label class="text-xs text-gray-500 font-medium w-12">Value</label>
                        <input type="text" class="color-css-input w-full border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none focus:border-brown font-mono" value="<?= htmlspecialchars($cssValue ?? '') ?>" placeholder="var(--color-name) or rgba(0,0,0,0.5)">
                    </div>

                    <!-- Component Colors -->
                    <div class="border-t border-gray-100 pt-4 mt-4">
                        <div class="flex items-center justify-between mb-4">
                            <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider">Component Colors</label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="masterColorLinkToggle w-4 h-4 text-[#F25996] rounded border-gray-300 focus:ring-[#F25996]">
                                <span class="text-xs font-bold text-gray-700">Use Custom Color Link / CSS</span>
                            </label>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <?php if (!$isRight): ?>
                            <?php
                            $renderColorField('Title Color', $prefix.'_title_color', $theme['title_color'] ?? '#ffffff');
                            $renderColorField('Subtitle Color', $prefix.'_subtitle_color', $theme['subtitle_color'] ?? '#ffffff');
                            $renderColorField('Description Color', $prefix.'_description_color', $theme['description_color'] ?? '#ffffff');
                            ?>
                            <?php endif; ?>
                            


                            <?php
                            $renderColorField('Button BG', $prefix.'_button_bg_color', $theme['button_bg_color'] ?? '#ffffff');
                            $renderColorField('Button Text', $prefix.'_button_text_color', $theme['button_text_color'] ?? '#000000');
                            $renderColorField('Arrow BG', $prefix.'_arrow_bg_color', $theme['arrow_bg_color'] ?? '#ffffff');
                            $renderColorField('Arrow Icon', $prefix.'_arrow_color', $theme['arrow_color'] ?? '#000000');
                            $renderColorField('Dot Color', $prefix.'_dot_color', $theme['dot_color'] ?? '#ffffff');
                            ?>
                        </div>
                    </div>
                    
                    <div class="pt-2">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Preview</label>
                        <div class="h-12 rounded-lg border border-gray-200 theme-preview-box w-full" style="background: <?= $themeValue ?>"></div>
                        <div class="text-[10px] text-gray-400 font-mono mt-1 theme-preview-text" id="<?= $prefix ?>-theme-text-preview"><?= htmlspecialchars($themeValue) ?></div>
                    </div>
                </div>
        </div>
    </div>
    
    <div class="media-section mt-auto pt-6 border-t border-gray-100" data-prefix="<?= $prefix ?>">
        <div class="flex items-center justify-between mb-4">
            <label class="text-xs font-serif font-bold text-gray-600 uppercase tracking-wider">Media Items <span class="lowercase text-gray-400 font-normal">(Multi-image / Multi-video)</span></label>
            <button type="button" class="btn-add-media text-xs font-bold text-gray-700 bg-white border border-gray-200 px-4 py-2 rounded-full hover:bg-gray-50 transition-colors shadow-sm flex items-center gap-2" data-prefix="<?= $prefix ?>">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Item
            </button>
        </div>

        <div class="media-container space-y-4" id="media-<?= $prefix ?>">
            <?php if (empty($media)): ?>
            <div class="empty-msg bg-gray-50 text-gray-400 text-sm text-center py-6 rounded-lg border border-dashed border-gray-200">
                No media items added yet. Click 'Add Item'.
            </div>
            <?php else: ?>
                <div class="empty-msg hidden bg-gray-50 text-gray-400 text-sm text-center py-6 rounded-lg border border-dashed border-gray-200">
                    No media items added yet. Click 'Add Item'.
                </div>
                <?php foreach($media as $m): ?>
                <div class="media-row relative group bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                    <button type="button" class="btn-remove-media absolute top-4 right-4 text-red-400 hover:text-red-600 transition-colors z-10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                    
                    <div class="mb-4 w-1/3">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Type</label>
                        <select name="<?= $prefix ?>_media_type[]" class="media-type-input w-full border border-gray-200 rounded-md px-3 py-2 text-sm bg-white outline-none focus:border-brown">
                            <option value="image" <?= $m['type'] === 'image' ? 'selected' : '' ?>>Image</option>
                            <option value="video" <?= $m['type'] === 'video' ? 'selected' : '' ?>>Video</option>
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Desktop -->
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Desktop</label>
                            
                            <div class="desktop-preview h-32 mb-2 bg-gray-50 border-2 border-dashed border-gray-200 rounded-lg flex flex-col items-center justify-center overflow-hidden hover:bg-gray-100 transition-colors cursor-pointer">
                                <?php if(empty($m['desktop'])): ?>
                                    <svg class="w-6 h-6 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    <span class="text-xs text-gray-400 font-medium">Click or drag to upload</span>
                                <?php elseif($m['type'] === 'video'): ?>
                                    <video src="<?= htmlspecialchars($m['desktop']) ?>" class="w-full h-full object-cover" muted></video>
                                <?php else: ?>
                                    <img src="<?= htmlspecialchars($m['desktop']) ?>" class="w-full h-full object-cover">
                                <?php endif; ?>
                            </div>
                            
                            <div class="flex gap-2">
                                <input type="text" name="<?= $prefix ?>_media_desktop[]" value="<?= htmlspecialchars($m['desktop'] ?? '') ?>" class="desktop-url flex-1 border border-gray-200 rounded-md px-3 py-2 text-xs outline-none focus:border-brown" placeholder="Paste URL here...">
                            </div>
                        </div>

                        <!-- Mobile -->
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Mobile</label>
                            
                            <div class="mobile-preview h-32 mb-2 bg-gray-50 border-2 border-dashed border-gray-200 rounded-lg flex flex-col items-center justify-center overflow-hidden hover:bg-gray-100 transition-colors cursor-pointer">
                                <?php if(empty($m['mobile'])): ?>
                                    <svg class="w-6 h-6 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    <span class="text-xs text-gray-400 font-medium">Click or drag to upload</span>
                                <?php elseif($m['type'] === 'video'): ?>
                                    <video src="<?= htmlspecialchars($m['mobile']) ?>" class="w-full h-full object-cover" muted></video>
                                <?php else: ?>
                                    <img src="<?= htmlspecialchars($m['mobile']) ?>" class="w-full h-full object-cover">
                                <?php endif; ?>
                            </div>
                            
                            <div class="flex gap-2">
                                <input type="text" name="<?= $prefix ?>_media_mobile[]" value="<?= htmlspecialchars($m['mobile'] ?? '') ?>" class="mobile-url flex-1 border border-gray-200 rounded-md px-3 py-2 text-xs outline-none focus:border-brown" placeholder="Paste URL here...">
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Dynamic Fetch Info Message -->
    <div class="dynamic-info-section hidden border-t border-gray-100 pt-6" data-prefix="<?= $prefix ?>">
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
            <h4 class="text-gray-800 font-bold text-sm mb-4 dynamic-info-title">Automatic Fetching Preview</h4>
            <div class="dynamic-preview-grid flex flex-col gap-2 max-h-72 overflow-y-auto pr-1">
                <!-- JS will populate preview items here -->
            </div>
        </div>
    </div>
    
    <!-- Manual Selection UI Block -->
    <div class="manual-selection-section hidden border-t border-gray-100 pt-6" data-prefix="<?= $prefix ?>">
        <div class="bg-pink-50/60 border border-pink-200 rounded-xl p-4">
            <h4 class="text-pink-900 font-bold text-sm mb-4 manual-selection-title">Select Items</h4>
            
            <!-- Filter Input -->
            <div class="mb-4 relative">
                <input type="text" class="manual-selection-search w-full border border-pink-300 rounded-lg pl-10 pr-4 py-2 text-sm outline-none focus:border-[#F25996] bg-white" placeholder="Search to filter..." data-prefix="<?= $prefix ?>">
                <svg class="w-4 h-4 text-pink-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            
            <!-- Scrollable Checkbox List -->
            <div class="manual-selection-list h-64 overflow-y-auto bg-white border border-pink-200 rounded-lg p-2 space-y-1">
                <!-- JS will populate checklists here -->
            </div>
            
            <?php 
                $selProds = isset($data['selected_products']) ? json_encode($data['selected_products']) : '[]';
                $selCats = isset($data['selected_categories']) ? json_encode($data['selected_categories']) : '[]';
            ?>
            <!-- Hidden inputs to hold the selected items on edit -->
            <input type="hidden" class="pre-selected-products" value='<?= htmlspecialchars($selProds) ?>'>
            <input type="hidden" class="pre-selected-categories" value='<?= htmlspecialchars($selCats) ?>'>
        </div>
    </div>
</div>
                    
                </div>
            </div>

            <!-- Row 3: Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="w-5 h-5 text-[#0056b3] bg-gray-100 border-gray-300 rounded focus:ring-[#0056b3] focus:ring-2 cursor-pointer" style="accent-color: #0056b3;" checked>
                    <span class="text-sm font-bold text-[#0056b3]">Active (Visible on Site)</span>
                </label>
                <div class="flex items-center gap-3">
                    <a href="<?= BASE_URL ?>/admin/cms/main-banners" class="px-6 py-2.5 border border-red-200 text-red-500 hover:bg-red-50 font-bold text-sm rounded-full transition-colors">CANCEL</a>
                    <button type="submit" class="px-8 py-2.5 bg-gray-900 text-white font-bold text-sm rounded-full shadow-sm hover:bg-black transition-all">Save Banner</button>
                </div>
            </div>
            
        </div>
    </form>
</div>

<!-- Template for Media Row -->
<template id="mediaTemplate">
    <div class="media-row relative group bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
        <button type="button" class="btn-remove-media absolute top-4 right-4 text-red-400 hover:text-red-600 transition-colors z-10">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </button>
        
        <div class="mb-4 w-1/3">
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Type</label>
            <select class="media-type-input w-full border border-gray-200 rounded-md px-3 py-2 text-sm bg-white outline-none focus:border-brown">
                <option value="image">Image</option>
                <option value="video">Video</option>
            </select>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Desktop -->
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Desktop</label>
                
                <div class="desktop-preview h-32 mb-2 bg-gray-50 border-2 border-dashed border-gray-200 rounded-lg flex flex-col items-center justify-center overflow-hidden hover:bg-gray-100 transition-colors cursor-pointer">
                    <svg class="w-6 h-6 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    <span class="text-xs text-gray-400 font-medium">Click or drag to upload</span>
                </div>
                
                <div class="flex gap-2">
                    <input type="text" class="desktop-url flex-1 border border-gray-200 rounded-md px-3 py-2 text-xs outline-none focus:border-brown" placeholder="Paste URL here...">
                </div>
            </div>

            <!-- Mobile -->
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Mobile</label>
                
                <div class="mobile-preview h-32 mb-2 bg-gray-50 border-2 border-dashed border-gray-200 rounded-lg flex flex-col items-center justify-center overflow-hidden hover:bg-gray-100 transition-colors cursor-pointer">
                    <svg class="w-6 h-6 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    <span class="text-xs text-gray-400 font-medium">Click or drag to upload</span>
                </div>
                
                <div class="flex gap-2">
                    <input type="text" class="mobile-url flex-1 border border-gray-200 rounded-md px-3 py-2 text-xs outline-none focus:border-brown" placeholder="Paste URL here...">
                </div>
            </div>
        </div>
    </div>
</template>

<?php require_once __DIR__ . '/../../media/_selector_modal.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    const previewProducts = <?= json_encode($previewProducts ?? []) ?>;
    const previewCategories = <?= json_encode($previewCategories ?? []) ?>;

    // --- Dynamic Content Type Logic ---
    document.querySelectorAll('.content-type-select').forEach(select => {
        const check = () => {
            const prefix = select.dataset.prefix;
            const mediaSection = document.querySelector(`.media-section[data-prefix="${prefix}"]`);
            const infoSection = document.querySelector(`.dynamic-info-section[data-prefix="${prefix}"]`);
            const manualSection = document.querySelector(`.manual-selection-section[data-prefix="${prefix}"]`);
            
            if (mediaSection && infoSection && manualSection) {
                // Hide all sections first
                mediaSection.style.display = 'none';
                infoSection.style.display = 'none';
                manualSection.style.display = 'none';

                if (select.value === 'custom_image') {
                    mediaSection.style.display = 'block';
                } else if (select.value === 'product_carousel' || select.value === 'category_grid') {
                    infoSection.style.display = 'block';
                    
                    const title = infoSection.querySelector('.dynamic-info-title');
                    const grid = infoSection.querySelector('.dynamic-preview-grid');
                    grid.innerHTML = '';
                    
                    if (select.value === 'product_carousel') {
                        title.innerText = 'Preview: All Products (Auto-Fetched)';
                        previewProducts.forEach(p => {
                            const img = p.main_image ? p.main_image : 'https://placehold.co/400x400/f3f4f6/a1a1aa?text=No+Image';
                            grid.innerHTML += `
                                <div class="flex items-center gap-3 p-2 bg-white border border-gray-200 rounded-xl shadow-2xs hover:bg-gray-50 transition-colors">
                                    <img src="${img}" class="w-12 h-12 rounded-lg object-cover bg-gray-100 border border-gray-200 shrink-0" onerror="this.src='https://placehold.co/400x400/f3f4f6/a1a1aa?text=No+Image'">
                                    <span class="text-xs sm:text-sm font-semibold text-gray-800 flex-1 truncate" title="${p.name}">${p.name}</span>
                                </div>
                            `;
                        });
                    } else if (select.value === 'category_grid') {
                        title.innerText = 'Preview: All Categories (Auto-Fetched)';
                        previewCategories.forEach(c => {
                            const img = c.image ? c.image : 'https://placehold.co/400x400/f3f4f6/a1a1aa?text=No+Image';
                            grid.innerHTML += `
                                <div class="flex items-center gap-3 p-2 bg-white border border-gray-200 rounded-xl shadow-2xs hover:bg-gray-50 transition-colors">
                                    <img src="${img}" class="w-12 h-12 rounded-full object-cover bg-gray-100 border border-gray-200 shrink-0" onerror="this.src='https://placehold.co/400x400/f3f4f6/a1a1aa?text=No+Image'">
                                    <span class="text-xs sm:text-sm font-semibold text-gray-800 flex-1 truncate" title="${c.name}">${c.name}</span>
                                </div>
                            `;
                        });
                    }
                } else if (select.value === 'selected_products' || select.value === 'selected_categories') {
                    manualSection.style.display = 'block';
                    
                    const title = manualSection.querySelector('.manual-selection-title');
                    const listContainer = manualSection.querySelector('.manual-selection-list');
                    listContainer.innerHTML = ''; // Clear out previous items

                    if (select.value === 'selected_products') {
                        title.innerText = 'Select Products to Display';
                        const preSelected = JSON.parse(manualSection.querySelector('.pre-selected-products').value || '[]');
                        
                        previewProducts.forEach(p => {
                            const img = p.main_image ? p.main_image : 'https://placehold.co/400x400/f3f4f6/a1a1aa?text=No+Image';
                            const checked = preSelected.includes(p.id.toString()) || preSelected.includes(p.id) ? 'checked' : '';
                            listContainer.innerHTML += `
                                <label class="flex items-center gap-3 p-2 hover:bg-pink-50/60 rounded-lg cursor-pointer transition-colors border border-transparent hover:border-pink-100 manual-list-item" data-search="${p.name.toLowerCase()}">
                                    <input type="checkbox" name="${prefix}_selected_products[]" value="${p.id}" class="w-4 h-4 text-[#F25996] rounded border-gray-300 focus:ring-[#F25996]" ${checked}>
                                    <img src="${img}" class="w-10 h-10 object-cover rounded-md shadow-2xs bg-gray-100 shrink-0">
                                    <span class="text-xs font-medium text-gray-700 flex-1 truncate" title="${p.name}">${p.name}</span>
                                </label>
                            `;
                        });
                    } else if (select.value === 'selected_categories') {
                        title.innerText = 'Select Categories to Display';
                        const preSelected = JSON.parse(manualSection.querySelector('.pre-selected-categories').value || '[]');
                        
                        previewCategories.forEach(c => {
                            const img = c.image ? c.image : 'https://placehold.co/400x400/f3f4f6/a1a1aa?text=No+Image';
                            const checked = preSelected.includes(c.id.toString()) || preSelected.includes(c.id) ? 'checked' : '';
                            listContainer.innerHTML += `
                                <label class="flex items-center gap-3 p-2 hover:bg-pink-50/60 rounded-lg cursor-pointer transition-colors border border-transparent hover:border-pink-100 manual-list-item" data-search="${c.name.toLowerCase()}">
                                    <input type="checkbox" name="${prefix}_selected_categories[]" value="${c.id}" class="w-4 h-4 text-[#F25996] rounded border-gray-300 focus:ring-[#F25996]" ${checked}>
                                    <img src="${img}" class="w-10 h-10 object-cover rounded-full shadow-2xs bg-gray-100 shrink-0">
                                    <span class="text-xs font-medium text-gray-700 flex-1 truncate" title="${c.name}">${c.name}</span>
                                </label>
                            `;
                        });
                    }
                }
            }
        };
        select.addEventListener('change', check);
        check(); // run on load
    });

    // --- Manual Selection Filter Logic ---
    document.querySelectorAll('.manual-selection-search').forEach(input => {
        input.addEventListener('input', function() {
            const term = this.value.toLowerCase();
            const prefix = this.dataset.prefix;
            const container = document.querySelector(`.manual-selection-section[data-prefix="${prefix}"]`);
            if (container) {
                container.querySelectorAll('.manual-list-item').forEach(item => {
                    const searchableText = item.dataset.search;
                    if (searchableText.includes(term)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }
        });
    });

    // --- Color Theme Builder ---
    document.querySelectorAll('.theme-type').forEach(hiddenType => {
        const container = hiddenType.closest('.section-form-wrapper');
        const hiddenVal = container.querySelector('.theme-value');
        const btns = container.querySelectorAll('.color-type-btn');
        const pSolid = container.querySelector('.panel-solid');
        const pLinear = container.querySelector('.panel-linear');
        const pRadial = container.querySelector('.panel-radial');
        const pCss = container.querySelector('.panel-css');
        
        function updateTheme(syncFromText = false) {
            const type = hiddenType.value;
            let val = '';
            
            if (type === 'solid') {
                const picker = container.querySelector('.color-solid-picker');
                const text = container.querySelector('.color-solid-input');
                if (syncFromText && /^#[0-9A-Fa-f]{6}$/.test(text.value)) picker.value = text.value;
                val = picker.value;
                if (!syncFromText) text.value = val;
            } else if (type === 'linear') {
                const picker1 = container.querySelector('.color-linear-picker-1');
                const text1 = container.querySelector('.color-linear-1');
                const picker2 = container.querySelector('.color-linear-picker-2');
                const text2 = container.querySelector('.color-linear-2');
                const dir = container.querySelector('.color-linear-dir').value;
                
                if (syncFromText) {
                    if (/^#[0-9A-Fa-f]{6}$/.test(text1.value)) picker1.value = text1.value;
                    if (/^#[0-9A-Fa-f]{6}$/.test(text2.value)) picker2.value = text2.value;
                }
                
                val = `linear-gradient(${dir}, ${picker1.value}, ${picker2.value})`;
                if (!syncFromText) {
                    text1.value = picker1.value;
                    text2.value = picker2.value;
                }
            } else if (type === 'radial') {
                const picker1 = container.querySelector('.color-radial-picker-1');
                const text1 = container.querySelector('.color-radial-1');
                const picker2 = container.querySelector('.color-radial-picker-2');
                const text2 = container.querySelector('.color-radial-2');
                
                if (syncFromText) {
                    if (/^#[0-9A-Fa-f]{6}$/.test(text1.value)) picker1.value = text1.value;
                    if (/^#[0-9A-Fa-f]{6}$/.test(text2.value)) picker2.value = text2.value;
                }
                
                val = `radial-gradient(circle, ${picker1.value}, ${picker2.value})`;
                if (!syncFromText) {
                    text1.value = picker1.value;
                    text2.value = picker2.value;
                }
            } else if (type === 'css') {
                const text = container.querySelector('.color-css-input');
                if (text) val = text.value;
            }
            
            hiddenVal.value = val;
            const previewBox = container.querySelector('.theme-preview-box');
            if (previewBox) {
                if (type === 'css') {
                    if (val.includes('background:') || val.includes('background-color:')) {
                        previewBox.style.cssText = 'background: #f3f4f6; ' + val;
                    } else {
                        previewBox.style.cssText = '';
                        previewBox.style.background = val;
                    }
                } else {
                    previewBox.style.cssText = '';
                    previewBox.style.background = val;
                }
            }
            
            const previewText = container.querySelector('.theme-preview-text');
            if (previewText) previewText.innerText = val;
        }

        btns.forEach(btn => {
            btn.addEventListener('click', () => {
                btns.forEach(b => {
                    b.classList.remove('bg-gray-900', 'text-white', 'border-gray-300', 'active');
                    b.classList.add('bg-white', 'text-gray-600', 'border-gray-200');
                });
                btn.classList.remove('bg-white', 'text-gray-600', 'border-gray-200');
                btn.classList.add('bg-gray-900', 'text-white', 'border-gray-300', 'active');
                
                hiddenType.value = btn.dataset.type;
                
                pSolid.classList.add('hidden');
                pLinear.classList.add('hidden');
                pRadial.classList.add('hidden');
                if (pCss) pCss.classList.add('hidden');
                
                if(hiddenType.value === 'solid') pSolid.classList.remove('hidden');
                if(hiddenType.value === 'linear') pLinear.classList.remove('hidden');
                if(hiddenType.value === 'radial') pRadial.classList.remove('hidden');
                if(hiddenType.value === 'css' && pCss) pCss.classList.remove('hidden');
                
                updateTheme();
            });
        });

        // Event listeners for inputs
        const solidPicker = container.querySelector('.color-solid-picker');
        const solidInput = container.querySelector('.color-solid-input');
        if(solidPicker) solidPicker.addEventListener('input', () => updateTheme(false));
        if(solidInput) solidInput.addEventListener('input', () => updateTheme(true));
        
        ['1','2'].forEach(idx => {
            const lp = container.querySelector('.color-linear-picker-' + idx);
            const lt = container.querySelector('.color-linear-' + idx);
            if(lp) lp.addEventListener('input', () => updateTheme(false));
            if(lt) lt.addEventListener('input', () => updateTheme(true));
            
            const rp = container.querySelector('.color-radial-picker-' + idx);
            const rt = container.querySelector('.color-radial-' + idx);
            if(rp) rp.addEventListener('input', () => updateTheme(false));
            if(rt) rt.addEventListener('input', () => updateTheme(true));
        });
        
        const lDir = container.querySelector('.color-linear-dir');
        if(lDir) lDir.addEventListener('change', () => updateTheme(false));

        const cssInput = container.querySelector('.color-css-input');
        if(cssInput) cssInput.addEventListener('input', () => updateTheme(true));
        
        container.querySelectorAll('input[type="text"]').forEach(el => {
            if (el.className.includes('color-')) {
                el.addEventListener('input', () => updateTheme(true));
            }
        });
    });

    // --- Media Manager ---
    const template = document.getElementById('mediaTemplate');
    
    document.querySelectorAll('.btn-add-media').forEach(btn => {
        btn.addEventListener('click', () => {
            const prefix = btn.dataset.prefix;
            const container = document.getElementById('media-' + prefix);
            const empty = container.querySelector('.empty-msg');
            if (empty) empty.style.display = 'none';
            
            const clone = template.content.cloneNode(true);
            const row = clone.querySelector('.media-row');
            
            // Set names dynamically based on prefix
            row.querySelector('.media-type-input').name = `${prefix}_media_type[]`;
            row.querySelector('.desktop-url').name = `${prefix}_media_desktop[]`;
            row.querySelector('.mobile-url').name = `${prefix}_media_mobile[]`;
            
            initMediaRow(row);
            container.appendChild(clone);
        });
    });

    document.addEventListener('click', e => {
        const removeBtn = e.target.closest('.btn-remove-media');
        if (removeBtn) {
            const container = removeBtn.closest('.media-container');
            removeBtn.closest('.media-row').remove();
            if (container.querySelectorAll('.media-row').length === 0) {
                const empty = container.querySelector('.empty-msg');
                if (empty) empty.style.display = 'block';
            }
        }
    });

    function renderPreview(url, previewEl, type) {
        if (!url) {
            previewEl.innerHTML = '<span class="text-xs text-gray-400">Preview</span>';
            return;
        }
        if (type === 'video' || url.match(/\.(mp4|webm|ogg)$/i) || url.includes('/video/upload/')) {
            previewEl.innerHTML = `<video src="${url}" class="w-full h-full object-cover" controls muted></video>`;
        } else {
            previewEl.innerHTML = `<img src="${url}" class="w-full h-full object-cover">`;
        }
    }

    function initMediaRow(row) {
        const typeSelect = row.querySelector('.media-type-input');
        
        row.querySelectorAll('.desktop-preview, .mobile-preview').forEach(preview => {
            preview.addEventListener('click', () => {
                const target = preview.classList.contains('desktop-preview') ? 'desktop' : 'mobile';
                const input = row.querySelector(`.${target}-url`);
                
                openMediaSelector(url => {
                    if (url) {
                        input.value = url;
                        if(url.match(/\.(mp4|webm|ogg)$/i) || url.includes('/video/upload/')) {
                            typeSelect.value = 'video';
                        }
                        renderPreview(url, preview, typeSelect.value);
                    }
                });
            });
        });
        
        row.querySelectorAll('input[type="text"]').forEach(input => {
            input.addEventListener('input', () => {
                const target = input.classList.contains('desktop-url') ? 'desktop' : 'mobile';
                const preview = row.querySelector(`.${target}-preview`);
                renderPreview(input.value.trim(), preview, typeSelect.value);
            });
        });
        
        typeSelect.addEventListener('change', () => {
            const dUrl = row.querySelector('.desktop-url').value.trim();
            const mUrl = row.querySelector('.mobile-url').value.trim();
            renderPreview(dUrl, row.querySelector('.desktop-preview'), typeSelect.value);
            renderPreview(mUrl, row.querySelector('.mobile-preview'), typeSelect.value);
        });
    }

    // Hybrid Color Inputs (Color Link) - Master Toggles
    const masterToggles = document.querySelectorAll('.masterColorLinkToggle');
    masterToggles.forEach(masterToggle => {
        const container = masterToggle.closest('.border-t');
        if(!container) return;
        const colorFields = container.querySelectorAll('.color-field-wrapper');
        
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
                const finalInput = wrapper.querySelector('.color-final-input');

                if (isCustomMode) {
                    standardMode.classList.add('hidden');
                    customMode.classList.remove('hidden');
                    customInput.value = finalInput.value;
                } else {
                    standardMode.classList.remove('hidden');
                    customMode.classList.add('hidden');
                    if (/^#[0-9A-Fa-f]{3,6}$/.test(finalInput.value)) {
                        pickerInput.value = finalInput.value;
                    }
                }
                updateFinalColors(wrapper, masterToggle.checked);
            });
        });

        colorFields.forEach(wrapper => {
            const pickerInput = wrapper.querySelector('.color-picker-input');
            const pickerText = wrapper.querySelector('.color-picker-text');
            const customInput = wrapper.querySelector('.color-custom-input');

            pickerInput.addEventListener('input', () => {
                pickerText.value = pickerInput.value;
                updateFinalColors(wrapper, masterToggle.checked);
            });
            pickerText.addEventListener('input', () => {
                if (/^#[0-9A-Fa-f]{3,6}$/.test(pickerText.value)) {
                    pickerInput.value = pickerText.value;
                }
                updateFinalColors(wrapper, masterToggle.checked);
            });
            
            customInput.addEventListener('input', () => updateFinalColors(wrapper, masterToggle.checked));
        });
    });

    function updateFinalColors(wrapper, isCustomMode) {
        const pickerText = wrapper.querySelector('.color-picker-text');
        const customInput = wrapper.querySelector('.color-custom-input');
        const finalInput = wrapper.querySelector('.color-final-input');
        
        if (isCustomMode) {
            finalInput.value = customInput.value;
        } else {
            finalInput.value = pickerText.value;
        }
    }

});
</script>

