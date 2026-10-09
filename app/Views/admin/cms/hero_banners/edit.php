<div class="max-w-4xl mx-auto">
   
    <?php 
        $content = $banner['content'] ?? [];
        $title = $content['title'] ?? '';
        $subtitle = $content['subtitle'] ?? '';
        $button_text = $content['button_text'] ?? '';
        $cta_url = $content['cta_url'] ?? '';
        $animation = $content['animation'] ?? 'fade';
        $sort_order = $content['sort_order'] ?? 0;
        $start_date = $content['start_date'] ?? '';
        $end_date = $content['end_date'] ?? '';
        $description = $content['description'] ?? '';
        $theme_type = $content['theme']['type'] ?? 'solid';
        $theme_value = $content['theme']['value'] ?? '';
        $media_items = $content['media'] ?? [];
        $is_active = $banner['is_active'] ?? 0;
    ?>

    <form action="<?= BASE_URL ?>/admin/cms/hero-banners/update" method="POST" class="bg-white rounded-[1.5rem] shadow-custom border border-gray-100 p-8 h-full flex flex-col">
        
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-serif text-[#0b3c7c]">Edit Hero Banner</h2>
        </div>
    
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-auto">
            <input type="hidden" name="id" value="<?= htmlspecialchars($banner['id']) ?>">
            
            <div>
                <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($title) ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors">
            </div>
            <div>
                <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Subtitle</label>
                <input type="text" name="subtitle" value="<?= htmlspecialchars($subtitle) ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors">
            </div>
            
            <div>
                <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Button Text</label>
                <input type="text" name="button_text" value="<?= htmlspecialchars($button_text) ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors">
            </div>
            <div>
                <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">CTA URL</label>
                <input type="text" name="cta_url" value="<?= htmlspecialchars($cta_url) ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors">
            </div>

            <!-- Settings -->
            <div>
                <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Image Fit</label>
                <select name="image_fit" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 font-bold focus:border-[#F25996] outline-none bg-white">
                    <?php $fit = $content['image_fit'] ?? 'cover'; ?>
                    <option value="cover" <?= $fit === 'cover' ? 'selected' : '' ?>>Cover (Fills, Crops)</option>
                    <option value="contain" <?= $fit === 'contain' ? 'selected' : '' ?>>Contain (Full Image, Empty Space)</option>
                    <option value="fill" <?= $fit === 'fill' ? 'selected' : '' ?>>Stretch (Fills, Distorts)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Animation</label>
                <select name="animation" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown focus:ring-1 focus:ring-brown outline-none appearance-none bg-white">
                    <option value="fade" <?= $animation === 'fade' ? 'selected' : '' ?>>Fade</option>
                    <option value="slide_left" <?= $animation === 'slide_left' ? 'selected' : '' ?>>Slide Left</option>
                    <option value="slide_right" <?= $animation === 'slide_right' ? 'selected' : '' ?>>Slide Right</option>
                    <option value="zoom" <?= $animation === 'zoom' ? 'selected' : '' ?>>Zoom</option>
                </select>
            </div>
            <div class="col-span-full grid grid-cols-2 gap-6">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="checkbox" name="show_arrows" value="1" class="w-4 h-4 text-brown rounded border-gray-300 focus:ring-brown" <?= ($banner['content']['show_arrows'] ?? true) ? 'checked' : '' ?>>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900 transition-colors">Show Arrows</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="checkbox" name="show_dots" value="1" class="w-4 h-4 text-brown rounded border-gray-300 focus:ring-brown" <?= ($banner['content']['show_dots'] ?? true) ? 'checked' : '' ?>>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900 transition-colors">Show Dots</span>
                </label>
            </div>

            <div class="col-span-full">
                <div>
                <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Sort Order</label>
                <input type="text" name="sort_order" value="<?= (int)$sort_order ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors">
                </div>
            </div>

            <div class="col-span-full grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Start Date</label>
                    <input type="date" name="start_date" value="<?= htmlspecialchars($start_date) ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">End Date</label>
                    <input type="date" name="end_date" value="<?= htmlspecialchars($end_date) ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors">
                </div>
            </div>
            
            <div class="col-span-full">
                <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Color Theme</label>
                <input type="hidden" name="color_theme_type" id="colorThemeType" value="<?= htmlspecialchars($theme_type) ?>">
                <input type="hidden" name="color_theme_value" id="colorThemeValue" value="<?= htmlspecialchars($theme_value) ?>">

                <?php
                    $savedSolid = '#ffffff';
                    $savedLinear1 = '#8c6a4f'; $savedLinear2 = '#c4a882'; $savedLinearDir = 'to right';
                    $savedRadial1 = '#ffffff'; $savedRadial2 = '#8c6a4f';

                    if ($theme_type === 'solid' && !empty($theme_value)) {
                        $savedSolid = $theme_value;
                    } elseif ($theme_type === 'linear' && !empty($theme_value)) {
                        if (preg_match('/linear-gradient\(([^,]+),\s*(#[0-9a-fA-F]{6}),\s*(#[0-9a-fA-F]{6})\)/', $theme_value, $m)) {
                            $savedLinearDir = trim($m[1]);
                            $savedLinear1 = trim($m[2]);
                            $savedLinear2 = trim($m[3]);
                        }
                    } elseif ($theme_type === 'radial' && !empty($theme_value)) {
                        if (preg_match('/radial-gradient\([^,]+,\s*(#[0-9a-fA-F]{6}),\s*(#[0-9a-fA-F]{6})\)/', $theme_value, $m)) {
                            $savedRadial1 = trim($m[1]);
                            $savedRadial2 = trim($m[2]);
                        }
                    }

                    $arrow_color = $banner['content']['theme']['arrow_color'] ?? '#000000';
                    $arrow_bg_color = $banner['content']['theme']['arrow_bg_color'] ?? '#ffffff';
                    $dot_color = $banner['content']['theme']['dot_color'] ?? '#8c6a4f';
                    $title_color = $banner['content']['theme']['title_color'] ?? '#ffffff';
                    $subtitle_color = $banner['content']['theme']['subtitle_color'] ?? '#ffffff';
                    $description_color = $banner['content']['theme']['description_color'] ?? '#ffffff';
                    $button_bg_color = $banner['content']['theme']['button_bg_color'] ?? '#111827';
                    $button_text_color = $banner['content']['theme']['button_text_color'] ?? '#ffffff';
                ?>

                <div class="border border-gray-200 rounded-xl p-4 space-y-4">
                    <div class="flex gap-2">
                        <button type="button" data-type="solid" class="color-type-btn <?= $theme_type === 'solid' ? 'active-type bg-gray-900 text-white border-gray-300' : 'text-gray-600 border-gray-200' ?> px-4 py-1.5 rounded-full text-xs font-bold border transition-all">Solid</button>
                        <button type="button" data-type="linear" class="color-type-btn <?= $theme_type === 'linear' ? 'active-type bg-gray-900 text-white border-gray-300' : 'text-gray-600 border-gray-200' ?> px-4 py-1.5 rounded-full text-xs font-bold border transition-all">Linear Gradient</button>
                        <button type="button" data-type="radial" class="color-type-btn <?= $theme_type === 'radial' ? 'active-type bg-gray-900 text-white border-gray-300' : 'text-gray-600 border-gray-200' ?> px-4 py-1.5 rounded-full text-xs font-bold border transition-all">Radial Gradient</button>
                        <button type="button" data-type="link" class="color-type-btn <?= $theme_type === 'link' ? 'active-type bg-gray-900 text-white border-gray-300' : 'text-gray-600 border-gray-200' ?> px-4 py-1.5 rounded-full text-xs font-bold border transition-all">Color Link (CSS)</button>
                    </div>

                    <div id="panel-solid" class="color-panel <?= $theme_type !== 'solid' ? 'hidden' : '' ?> flex items-center gap-4">
                        <label class="text-xs text-gray-500 font-medium w-12">Color</label>
                        <input type="color" id="solidColor" value="<?= htmlspecialchars($savedSolid) ?>" class="w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5">
                        <input type="text" id="solidHex" value="<?= htmlspecialchars($savedSolid) ?>" class="w-28 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none font-mono">
                    </div>

                    <div id="panel-linear" class="color-panel <?= $theme_type !== 'linear' ? 'hidden' : '' ?> space-y-3">
                        <div class="flex items-center gap-4">
                            <label class="text-xs text-gray-500 font-medium w-12">From</label>
                            <input type="color" id="linearColor1" value="<?= htmlspecialchars($savedLinear1) ?>" class="w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5">
                            <input type="text" id="linearHex1" value="<?= htmlspecialchars($savedLinear1) ?>" class="w-28 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none font-mono">
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="text-xs text-gray-500 font-medium w-12">To</label>
                            <input type="color" id="linearColor2" value="<?= htmlspecialchars($savedLinear2) ?>" class="w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5">
                            <input type="text" id="linearHex2" value="<?= htmlspecialchars($savedLinear2) ?>" class="w-28 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none font-mono">
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="text-xs text-gray-500 font-medium w-12">Direction</label>
                            <select id="linearDir" class="border border-gray-200 rounded-lg px-3 py-1.5 text-sm bg-white outline-none">
                                <option value="to right" <?= $savedLinearDir === 'to right' ? 'selected' : '' ?>>Left → Right</option>
                                <option value="to left" <?= $savedLinearDir === 'to left' ? 'selected' : '' ?>>Right → Left</option>
                                <option value="to bottom" <?= $savedLinearDir === 'to bottom' ? 'selected' : '' ?>>Top → Bottom</option>
                                <option value="to top" <?= $savedLinearDir === 'to top' ? 'selected' : '' ?>>Bottom → Top</option>
                                <option value="to bottom right" <?= $savedLinearDir === 'to bottom right' ? 'selected' : '' ?>>Top-Left → Bottom-Right</option>
                                <option value="to bottom left" <?= $savedLinearDir === 'to bottom left' ? 'selected' : '' ?>>Top-Right → Bottom-Left</option>
                            </select>
                        </div>
                    </div>

                    <div id="panel-radial" class="color-panel <?= $theme_type !== 'radial' ? 'hidden' : '' ?> space-y-3">
                        <div class="flex items-center gap-4">
                            <label class="text-xs text-gray-500 font-medium w-16">Center</label>
                            <input type="color" id="radialColor1" value="<?= htmlspecialchars($savedRadial1) ?>" class="w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5">
                            <input type="text" id="radialHex1" value="<?= htmlspecialchars($savedRadial1) ?>" class="w-28 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none font-mono">
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="text-xs text-gray-500 font-medium w-16">Edge</label>
                            <input type="color" id="radialColor2" value="<?= htmlspecialchars($savedRadial2) ?>" class="w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5">
                            <input type="text" id="radialHex2" value="<?= htmlspecialchars($savedRadial2) ?>" class="w-28 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none font-mono">
                        </div>
                    </div>

                    <div id="panel-link" class="color-panel <?= $theme_type !== 'link' ? 'hidden' : '' ?> space-y-2">
                        <label class="text-xs text-gray-500 font-medium">Enter a CSS color value, variable, or any valid background</label>
                        <input type="text" id="colorLinkInput" class="w-full border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-brown font-mono" value="<?= htmlspecialchars($theme_type === 'link' ? $theme_value : '') ?>" placeholder="e.g. var(--primary-color) or #FF5500 or any CSS value">
                    </div>

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
                            <?php $currentFit = $content['image_fit'] ?? 'cover'; ?>
                            <select name="image_fit" class="w-full border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-brown text-sm">
                                <option value="cover" <?= $currentFit === 'cover' ? 'selected' : '' ?>>Cover (Fills, Crops)</option>
                                <option value="contain" <?= $currentFit === 'contain' ? 'selected' : '' ?>>Contain (Full Image, Empty Space)</option>
                                <option value="fill" <?= $currentFit === 'fill' ? 'selected' : '' ?>>Stretch (Fills, Distorts)</option>
                            </select>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-6 mt-6">
                        <div class="flex items-center justify-between mb-4">
                            <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider">Component Colors</label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="masterColorLinkToggle" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                <span class="text-xs font-bold text-gray-700">Use Custom Color Link / CSS</span>
                            </label>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
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

                            $renderColorField('Title Color', 'title_color', $title_color);
                            $renderColorField('Subtitle', 'subtitle_color', $subtitle_color);
                            $renderColorField('Description', 'description_color', $description_color);
                            $renderColorField('Button BG', 'button_bg_color', $button_bg_color);
                            $renderColorField('Button Text', 'button_text_color', $button_text_color);
                            $renderColorField('Arrow BG', 'arrow_bg_color', $arrow_bg_color ?? '#ffffff');
                            $renderColorField('Arrow Icon', 'arrow_color', $arrow_color);
                            $renderColorField('Dot Color', 'dot_color', $dot_color);
                            ?>
                        </div>
                    </div> <!-- Added missing closing div for Component Colors -->

                    <div>
                        <label class="text-xs text-gray-400 font-medium mb-1 block">Preview</label>
                        <div id="colorPreviewBar" class="w-full h-12 rounded-lg border border-gray-200" style="background:<?= htmlspecialchars($theme_value ?: '#ffffff') ?>;"></div>
                        <p id="colorPreviewText" class="text-[10px] text-gray-400 mt-1 font-mono"><?= htmlspecialchars($theme_value ?: '#ffffff') ?></p>
                    </div>
                </div>
            </div>

            <div class="col-span-full">
                <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors"><?= htmlspecialchars($description) ?></textarea>
            </div>
        </div>

        <!-- Media Items Section -->
        <div class="border-t border-gray-100 pt-6 mt-auto">
            <div class="flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-center mb-4">
                <label class="block text-xs font-serif font-bold text-gray-600 uppercase tracking-wider leading-relaxed">Media Items (Multi-Image / Multi-Video)</label>
                <button type="button" id="addMediaBtn" class="btn-add-media shrink-0 whitespace-nowrap text-xs font-bold text-gray-700 bg-white border border-gray-200 px-4 py-2 rounded-full hover:bg-gray-50 transition-colors shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Item
                </button>
            </div>

            <div id="mediaContainer" class="space-y-4">
                <?php if(empty($media_items)): ?>
                    <div class="bg-gray-50 text-gray-400 text-sm text-center py-6 rounded-lg border border-dashed border-gray-200" id="emptyMediaMsg">
                        No media items added yet. Click 'Add Item' above.
                    </div>
                <?php else: ?>
                    <?php foreach($media_items as $idx => $media): 
                        $dUrl = htmlspecialchars($media['desktop'] ?? '');
                        $mUrl = htmlspecialchars($media['mobile'] ?? '');
                        $mType = $media['type'] ?? 'image';
                        $isVideo = $mType === 'video' || preg_match('/\.(mp4|webm|ogg)/i', $dUrl);
                    ?>
                        <div class="bg-white border border-gray-200 rounded-xl p-5 relative group media-item-row">
                            <button type="button" class="absolute top-4 right-4 text-red-400 hover:text-red-600 remove-media-btn" title="Remove">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                            
                            <div class="mb-4 w-full sm:w-1/3">
                                <label class="block text-[10px] font-serif font-bold text-gray-500 uppercase tracking-wider mb-1">Type</label>
                                <select name="media_type[]" class="media-type-select w-full border border-gray-200 rounded-md px-3 py-1.5 text-sm bg-white outline-none">
                                    <option value="image" <?= $mType === 'image' ? 'selected' : '' ?>>Image</option>
                                    <option value="video" <?= $mType === 'video' ? 'selected' : '' ?>>Video</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-[10px] font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Desktop</label>
                                    <div class="upload-drop-zone border-2 border-dashed border-gray-200 rounded-lg p-3 text-center cursor-pointer hover:border-brown hover:bg-stone-50 transition-colors relative" data-target="desktop">
                                        <div class="upload-placeholder <?= $dUrl ? 'hidden' : '' ?>">
                                            <svg class="w-7 h-7 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                            <p class="text-xs text-gray-400">Click or drag to upload</p>
                                        </div>
                                        <div class="upload-preview <?= $dUrl ? '' : 'hidden' ?> mt-1">
                                            <?php if($dUrl): ?>
                                                <?php if($isVideo): ?>
                                                    <video src="<?= $dUrl ?>" class="w-full h-32 rounded-md object-cover" controls muted></video>
                                                <?php else: ?>
                                                    <img src="<?= $dUrl ?>" class="w-full h-32 rounded-md object-cover" alt="Desktop preview">
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                        <input type="file" class="upload-file-input hidden" accept="image/*,video/*">
                                    </div>
                                    <input type="text" name="media_desktop[]" value="<?= $dUrl ?>" class="desktop-url-input mt-2 w-full border border-gray-200 rounded-md px-3 py-1.5 text-xs outline-none font-mono" placeholder="Paste URL here...">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Mobile</label>
                                    <div class="upload-drop-zone border-2 border-dashed border-gray-200 rounded-lg p-3 text-center cursor-pointer hover:border-brown hover:bg-stone-50 transition-colors relative" data-target="mobile">
                                        <div class="upload-placeholder <?= $mUrl ? 'hidden' : '' ?>">
                                            <svg class="w-7 h-7 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                            <p class="text-xs text-gray-400">Click or drag to upload</p>
                                        </div>
                                        <div class="upload-preview <?= $mUrl ? '' : 'hidden' ?> mt-1">
                                            <?php if($mUrl): ?>
                                                <?php if($isVideo): ?>
                                                    <video src="<?= $mUrl ?>" class="w-full h-32 rounded-md object-cover" controls muted></video>
                                                <?php else: ?>
                                                    <img src="<?= $mUrl ?>" class="w-full h-32 rounded-md object-cover" alt="Mobile preview">
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                        <input type="file" class="upload-file-input hidden" accept="image/*,video/*">
                                    </div>
                                    <input type="text" name="media_mobile[]" value="<?= $mUrl ?>" class="mobile-url-input mt-2 w-full border border-gray-200 rounded-md px-3 py-1.5 text-xs outline-none font-mono" placeholder="Paste URL here...">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="border-t border-gray-100 pt-6 mt-6 flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" <?= $is_active ? 'checked' : '' ?> class="w-5 h-5 text-gray-900 bg-gray-100 border-gray-300 rounded focus:ring-gray-900 focus:ring-2 cursor-pointer">
                <span class="text-sm font-bold text-gray-900">Active (Visible on Site)</span>
            </label>
            <div class="flex items-center gap-4">
                <a href="<?= BASE_URL ?>/admin/cms/hero-banners" class="px-6 py-2.5 border border-red-200 text-red-500 hover:bg-red-50 font-bold text-sm rounded-full transition-colors">CANCEL</a>
                <button type="submit" class="px-8 py-2.5 bg-gray-900 text-white font-bold text-sm rounded-full shadow-sm hover:bg-black transition-all">Update Banner</button>
            </div>
        </div>
    </form>
</div>

<!-- Template for new media item -->
<template id="mediaTemplate">
    <div class="bg-white border border-gray-200 rounded-xl p-5 relative group media-item-row">
        <button type="button" class="absolute top-4 right-4 text-red-400 hover:text-red-600 remove-media-btn" title="Remove">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </button>
        
        <div class="mb-4 w-1/3">
            <label class="block text-[10px] font-serif font-bold text-gray-500 uppercase tracking-wider mb-1">Type</label>
            <select name="media_type[]" class="media-type-select w-full border border-gray-200 rounded-md px-3 py-1.5 text-sm bg-white outline-none">
                <option value="image">Image</option>
                <option value="video">Video</option>
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Desktop -->
            <div>
                <label class="block text-[10px] font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Desktop</label>
                <div class="upload-drop-zone border-2 border-dashed border-gray-200 rounded-lg p-3 text-center cursor-pointer hover:border-brown hover:bg-stone-50 transition-colors relative" data-target="desktop">
                    <div class="upload-placeholder">
                        <svg class="w-7 h-7 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        <p class="text-xs text-gray-400">Click or drag to upload</p>
                    </div>
                    <div class="upload-preview hidden mt-1"></div>
                    <div class="upload-spinner hidden mt-3">
                        <svg class="animate-spin w-6 h-6 mx-auto" style="color:#8c6a4f" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <p class="text-[10px] text-gray-400 mt-1">Uploading...</p>
                    </div>
                    <input type="file" class="upload-file-input hidden" accept="image/*,video/*">
                </div>
                <div class="mt-2 flex gap-2">
                    <input type="text" name="media_desktop[]" class="desktop-url-input flex-1 border border-gray-200 rounded-md px-3 py-1.5 text-xs outline-none font-mono" placeholder="Paste URL here...">
                </div>
            </div>

            <!-- Mobile -->
            <div>
                <label class="block text-[10px] font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Mobile</label>
                <div class="upload-drop-zone border-2 border-dashed border-gray-200 rounded-lg p-3 text-center cursor-pointer hover:border-brown hover:bg-stone-50 transition-colors relative" data-target="mobile">
                    <div class="upload-placeholder">
                        <svg class="w-7 h-7 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        <p class="text-xs text-gray-400">Click or drag to upload</p>
                    </div>
                    <div class="upload-preview hidden mt-1"></div>
                    <div class="upload-spinner hidden mt-3">
                        <svg class="animate-spin w-6 h-6 mx-auto" style="color:#8c6a4f" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <p class="text-[10px] text-gray-400 mt-1">Uploading...</p>
                    </div>
                    <input type="file" class="upload-file-input hidden" accept="image/*,video/*">
                </div>
                <div class="mt-2 flex gap-2">
                    <input type="text" name="media_mobile[]" class="mobile-url-input flex-1 border border-gray-200 rounded-md px-3 py-1.5 text-xs outline-none font-mono" placeholder="Paste URL here...">
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Media Picker Modal -->
<div id="mediaPickerModal" class="fixed inset-0 bg-gray-900/50 hidden z-[100] flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-5xl rounded-xl shadow-2xl flex flex-col max-h-[90vh] overflow-hidden">
        <div class="flex-shrink-0 flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-serif font-bold text-gray-800 text-lg">Select Media</h3>
            <button type="button" id="closeMediaPickerBtn" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto flex-1 min-h-0">
            <div id="mediaPickerGrid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <div class="col-span-full text-center text-gray-400 py-12">Loading media...</div>
            </div>
        </div>
    </div>
</div>

<style>
.font-serif {
    font-family: 'Playfair Display', serif;
}
.shadow-custom { box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
.bg-brown { background-color: #f8d3b5ff; }
.hover\:bg-brown-dark:hover { background-color: #f8d3b5ff}
.text-brown { color: #f8d3b5ff}
.border-brown { border-color: #f8d3b5ff}
.focus\:border-brown:focus { border-color: #f8d3b5ff}
.focus\:ring-brown:focus { --tw-ring-color: #f8d3b5ff}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addBtn = document.getElementById('addMediaBtn');
    const container = document.getElementById('mediaContainer');
    const template = document.getElementById('mediaTemplate');
    const emptyMsg = document.getElementById('emptyMediaMsg');

    addBtn.addEventListener('click', function() {
        if(emptyMsg) emptyMsg.style.display = 'none';
        const clone = template.content.cloneNode(true);
        container.appendChild(clone);
        const lastRow = container.querySelector('.media-item-row:last-child') || container.lastElementChild;
        if (lastRow) initUploadZones(lastRow);
    });

    container.addEventListener('click', function(e) {
        if(e.target.closest('.remove-media-btn')) {
            e.target.closest('.media-item-row, .group').remove();
            if(container.querySelectorAll('.media-item-row').length === 0) {
                if(emptyMsg) emptyMsg.style.display = 'block';
            }
        }
    });

    // Init upload zones for all pre-rendered rows
    container.querySelectorAll('.media-item-row').forEach(row => initUploadZones(row));

    // ---- Upload Zones ----
    function renderPreview(zone, url, type) {
        const placeholder = zone.querySelector('.upload-placeholder');
        const preview = zone.querySelector('.upload-preview');
        const spinner = zone.querySelector('.upload-spinner');
        placeholder.classList.add('hidden');
        spinner.classList.add('hidden');
        preview.classList.remove('hidden');
        if (type === 'video' || /\.(mp4|webm|ogg)$/i.test(url)) {
            preview.innerHTML = `<video src="${url}" class="w-full h-32 rounded-md object-cover" controls muted></video><p class="text-[10px] text-gray-400 mt-1 truncate">${url.split('/').pop()}</p>`;
        } else {
            preview.innerHTML = `<img src="${url}" class="w-full h-32 rounded-md object-cover" alt="preview"><p class="text-[10px] text-gray-400 mt-1 truncate">${url.split('/').pop()}</p>`;
        }
    }

    function initUploadZones(row) {
        row.querySelectorAll('.upload-drop-zone').forEach(zone => {
            const fileInput = zone.querySelector('.upload-file-input');
            const target = zone.getAttribute('data-target');
            const urlInput = row.querySelector(target === 'desktop' ? '.desktop-url-input' : '.mobile-url-input');
            const typeSelect = row.querySelector('.media-type-select');

            // Guard: skip if critical elements are missing
            if (!urlInput) return;

            zone.addEventListener('click', e => {
                if (!e.target.closest('video, img, input, button')) {
                    openMediaPicker((selectedUrl, isVideo) => {
                        urlInput.value = selectedUrl;
                        if (typeSelect) typeSelect.value = isVideo ? 'video' : 'image';
                        renderPreview(zone, selectedUrl, isVideo ? 'video' : 'image');
                    });
                }
            });

            zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('border-brown'); });
            zone.addEventListener('dragleave', () => zone.classList.remove('border-brown'));
            zone.addEventListener('drop', e => {
                e.preventDefault(); zone.classList.remove('border-brown');
                const file = e.dataTransfer.files[0];
                if (file) handleFile(file, zone, urlInput, typeSelect);
            });

            if (fileInput) {
                fileInput.addEventListener('change', () => {
                    if (fileInput.files[0]) handleFile(fileInput.files[0], zone, urlInput, typeSelect);
                });
            }

            // URL paste — show preview immediately
            urlInput.addEventListener('input', () => {
                const url = urlInput.value.trim();
                if (url.length > 10) renderPreview(zone, url, typeSelect ? typeSelect.value : 'image');
            });
        });
    }

    // Media Picker Logic
    const pickerModal = document.getElementById('mediaPickerModal');
    const pickerGrid = document.getElementById('mediaPickerGrid');
    const closePickerBtn = document.getElementById('closeMediaPickerBtn');
    let pickerCallback = null;

    closePickerBtn.addEventListener('click', () => pickerModal.classList.add('hidden'));

    function openMediaPicker(callback) {
        pickerCallback = callback;
        pickerModal.classList.remove('hidden');
        pickerGrid.innerHTML = '<div class="col-span-full text-center text-gray-400 py-12">Loading media...</div>';
        
        fetch('<?= BASE_URL ?>/admin/media/ajax-get')
            .then(r => r.json())
            .then(res => {
                if(res.success && res.data.length > 0) {
                    pickerGrid.innerHTML = '';
                    res.data.forEach(media => {
                        const isVideo = media.original_filename.match(/\.(mp4|webm|ogg)$/i) || media.cloudinary_url.includes('/video/upload/');
                        const div = document.createElement('div');
                        div.className = 'border border-gray-200 rounded-lg overflow-hidden cursor-pointer hover:border-brown transition-colors group relative';
                        div.innerHTML = `
                            <div class="aspect-square bg-gray-50 flex items-center justify-center overflow-hidden">
                                ${isVideo 
                                    ? `<video src="${media.cloudinary_url}" class="w-full h-full object-cover" preload="none"></video>` 
                                    : `<img src="${media.cloudinary_url}" class="w-full h-full object-cover">`
                                }
                            </div>
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-xs font-bold bg-brown px-3 py-1 rounded-full shadow">Select</span>
                            </div>
                        `;
                        div.addEventListener('click', () => {
                            if(pickerCallback) pickerCallback(media.cloudinary_url, isVideo);
                            pickerModal.classList.add('hidden');
                        });
                        pickerGrid.appendChild(div);
                    });
                } else {
                    pickerGrid.innerHTML = '<div class="col-span-full text-center text-gray-400 py-12">No media found in Media Manager.</div>';
                }
            })
            .catch(() => {
                pickerGrid.innerHTML = '<div class="col-span-full text-center text-red-400 py-12">Error loading media.</div>';
            });
    }

    function handleFile(file, zone, urlInput, typeSelect) {
        const localUrl = URL.createObjectURL(file);
        const fileType = file.type.startsWith('video') ? 'video' : 'image';
        if (typeSelect) typeSelect.value = fileType;
        renderPreview(zone, localUrl, fileType);
        
        // Disable submit button while uploading
        const submitBtn = document.querySelector('button[type="submit"]');
        if(submitBtn) { submitBtn.disabled = true; submitBtn.innerText = 'Uploading...'; }

        urlInput.value = '';

        const fd = new FormData();
        fd.append('image', file);
        const spinner = zone.querySelector('.upload-spinner');
        const preview = zone.querySelector('.upload-preview');
        spinner.classList.remove('hidden');
        preview.classList.add('hidden');

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '<?= BASE_URL ?>/admin/media/upload');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        
        function reenableSubmit() {
            const btn = document.querySelector('button[type="submit"]');
            if(btn) { btn.disabled = false; btn.innerText = 'Update Banner'; }
        }

        xhr.onload = () => {
            spinner.classList.add('hidden');
            reenableSubmit();
            try {
                const data = JSON.parse(xhr.responseText);
                if (data.success && data.url) {
                    urlInput.value = data.url;
                    renderPreview(zone, data.url, fileType);
                    showUploadToast('Uploaded successfully!', 'success');
                } else {
                    renderPreview(zone, localUrl, fileType);
                    showUploadToast(data.error || 'Upload failed.', 'error');
                }
            } catch(e) {
                renderPreview(zone, localUrl, fileType);
                showUploadToast('Upload error. Check network tab.', 'error');
            }
        };
        xhr.onerror = () => { 
            spinner.classList.add('hidden'); 
            reenableSubmit();
            renderPreview(zone, localUrl, fileType); 
            showUploadToast('Network error. Upload failed.', 'error');
        };
        xhr.send(fd);
    }

    function showUploadToast(msg, type = 'info') {
        const t = document.createElement('div');
        const colors = { success: 'bg-green-500', error: 'bg-red-500', info: 'bg-blue-500' };
        t.className = `fixed bottom-4 right-4 px-5 py-2.5 rounded-lg shadow-lg text-white text-sm font-medium z-50 ${colors[type]}`;
        t.textContent = msg;
        document.body.appendChild(t);
        setTimeout(() => t.remove(), 4000);
    }


    let currentType = document.getElementById('colorThemeType').value || 'solid';
    const themeTypeInput = document.getElementById('colorThemeType');
    const themeValueInput = document.getElementById('colorThemeValue');
    const previewBar = document.getElementById('colorPreviewBar');
    const previewText = document.getElementById('colorPreviewText');

    function syncColorInput(picker, hexInput) {
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
    document.getElementById('linearDir').addEventListener('change', updatePreview);

    function updatePreview() {
        let cssValue = '';
        if (currentType === 'solid') {
            cssValue = document.getElementById('solidColor').value;
        } else if (currentType === 'linear') {
            const dir = document.getElementById('linearDir').value;
            const c1 = document.getElementById('linearColor1').value;
            const c2 = document.getElementById('linearColor2').value;
            cssValue = `linear-gradient(${dir}, ${c1}, ${c2})`;
        } else if (currentType === 'radial') {
            const c1 = document.getElementById('radialColor1').value;
            const c2 = document.getElementById('radialColor2').value;
            cssValue = `radial-gradient(circle, ${c1}, ${c2})`;
        } else if (currentType === 'link') {
            cssValue = document.getElementById('colorLinkInput').value;
        }
        
        if (currentType === 'link' && cssValue.includes('background')) {
            previewBar.style.cssText = cssValue + (cssValue.endsWith(';') ? '' : ';');
            // Re-apply border/rounded classes if cssText overwrites them
            previewBar.style.borderRadius = '0.5rem';
            previewBar.style.border = '1px solid #e5e7eb';
        } else {
            previewBar.style.background = cssValue;
        }
        
        previewText.textContent = cssValue;
        themeValueInput.value = cssValue;
        themeTypeInput.value = currentType;
    }

    document.getElementById('colorLinkInput').addEventListener('input', updatePreview);

    // Tab switching
    document.querySelectorAll('.color-type-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            currentType = btn.getAttribute('data-type');
            document.querySelectorAll('.color-type-btn').forEach(b => {
                b.classList.remove('bg-gray-900', 'text-white', 'border-gray-300', 'active-type');
                b.classList.add('text-gray-600', 'border-gray-200');
            });
            btn.classList.add('bg-gray-900', 'text-white', 'border-gray-300', 'active-type');
            btn.classList.remove('text-gray-600', 'border-gray-200');
            document.querySelectorAll('.color-panel').forEach(p => p.classList.add('hidden'));
            document.getElementById('panel-' + currentType).classList.remove('hidden');
            updatePreview();
        });
    });

    // Don't overwrite the preview on edit — it's already set by PHP
    // Only update hidden fields so they're ready on submit
    themeTypeInput.value = currentType;

    // Hybrid Color Inputs (Color Link) - Master Toggle
    const masterToggle = document.getElementById('masterColorLinkToggle');
    const colorFields = document.querySelectorAll('.color-field-wrapper');
    
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
            const finalInput = wrapper.querySelector('.color-final-input');

            if (isCustomMode) {
                standardMode.classList.add('hidden');
                customMode.classList.remove('hidden');
                customInput.value = finalInput.value;
            } else {
                standardMode.classList.remove('hidden');
                customMode.classList.add('hidden');
                // Ensure valid hex before switching back
                if (/^#[0-9A-Fa-f]{3,6}$/.test(finalInput.value)) {
                    pickerInput.value = finalInput.value;
                }
            }
            updateFinal(wrapper);
        });
    });

    function updateFinal(wrapper) {
        const isCustomMode = masterToggle.checked;
        const pickerInput = wrapper.querySelector('.color-picker-input');
        const pickerText = wrapper.querySelector('.color-picker-text');
        const customInput = wrapper.querySelector('.color-custom-input');
        const finalInput = wrapper.querySelector('.color-final-input');
        
        if (isCustomMode) {
            finalInput.value = customInput.value;
        } else {
            finalInput.value = pickerText.value;
        }
        updatePreview();
    }

    colorFields.forEach(wrapper => {
        const pickerInput = wrapper.querySelector('.color-picker-input');
        const pickerText = wrapper.querySelector('.color-picker-text');
        const customInput = wrapper.querySelector('.color-custom-input');

        // Sync visual picker and its text input
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
        
        customInput.addEventListener('input', () => updateFinal(wrapper));
    });
});
</script>
