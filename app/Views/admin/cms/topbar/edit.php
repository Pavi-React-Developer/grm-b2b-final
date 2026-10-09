<?php
$content = $topbar['content'] ?? [];
$settings = $content['global_settings'] ?? [];
$bgColor = $settings['bg_color'] ?? '#3f4c38';
$textColor = $settings['text_color'] ?? '#ffffff';
$mode = $settings['mode'] ?? 'slider';
$speed = $settings['speed'] ?? 10;
$height = $settings['height'] ?? 38;
$fontSize = $settings['font_size'] ?? 13;
$showSocial = isset($settings['show_social_icon']) ? (bool)$settings['show_social_icon'] : true;
$socialPlatform = $settings['social_platform'] ?? 'instagram';
$socialUrl = $settings['social_url'] ?? '';
$announcements = $content['announcements'] ?? [];

if (empty($announcements)) {
    $announcements[] = [
        'text' => '',
        'cta_url' => '',
        'icon' => 'truck',
        'active' => 1
    ];
}
?>
<style>
@keyframes grmPreviewMarquee {
    0% { transform: translateX(50%); }
    100% { transform: translateX(-50%); }
}
.grm-marquee-anim {
    display: inline-block !important;
    white-space: nowrap !important;
    animation: grmPreviewMarquee 10s linear infinite !important;
}
@keyframes grmTopBarFade {
    0%, 100% { opacity: 0.2; transform: translateY(2px); }
    50% { opacity: 1; transform: translateY(0); }
}
.grm-fade-anim {
    display: inline-block !important;
    animation: grmTopBarFade 2.5s ease-in-out infinite !important;
}
</style>

<div class="max-w-[95rem] mx-auto pb-12 px-4 sm:px-6">
    <form id="topBarEditForm" action="<?= BASE_URL ?>/admin/cms/topbars/update" method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($topbar['id']) ?>">

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-8">
            
            <!-- Form Top Header -->
            <div class="mb-2 flex justify-between items-center">
                <h2 class="text-2xl font-serif text-[#0b3c7c]">Edit Top Announcement Bar</h2>
            </div>

            <!-- Live Preview Banner Component -->
            <div>
                <div class="flex justify-between items-center mb-3">
                    <span class="text-xs font-serif font-bold text-gray-500 uppercase tracking-wider">LIVE PREVIEW</span>
                    <span class="text-xs text-gray-400">Updates automatically as you edit</span>
                </div>
                <div id="livePreviewContainer" class="w-full rounded-xl overflow-hidden shadow-inner flex items-center justify-between px-6 transition-all duration-300 relative" style="background-color: <?= htmlspecialchars($bgColor) ?>; color: <?= htmlspecialchars($textColor) ?>; min-height: <?= (int)$height ?>px;">
                    <div class="flex-1 overflow-hidden relative text-center">
                        <div id="livePreviewText" class="font-medium text-center px-4 transition-all" style="font-size: <?= (int)$fontSize ?>px;">
                            <?= htmlspecialchars($announcements[0]['text'] ?? '') ?>
                        </div>
                    </div>
                    <div id="livePreviewSocial" class="flex-shrink-0 flex items-center ml-3" style="display: <?= $showSocial ? 'flex' : 'none' ?>;">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </div>
                </div>
            </div>

            <!-- Global Settings Section -->
            <div>
                <div class="pb-3 border-b border-gray-100 mb-6">
                    <h3 class="text-xl font-serif font-bold text-[#0b3c7c]">Global Settings</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">BAR CONFIGURATION TITLE *</label>
                        <input type="text" name="title" value="<?= htmlspecialchars($content['title'] ?? 'Top Bar Configuration') ?>" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-[#0b3c7c] outline-none transition-colors text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">BAR MODE / ANIMATION STYLE *</label>
                        <select name="mode" id="barMode" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-[#0b3c7c] outline-none transition-colors bg-white text-sm">
                            <option value="slider" <?= $mode === 'slider' ? 'selected' : '' ?>>Slider / Marquee Animation (Auto-scroll multiple announcements)</option>
                            <option value="sticky" <?= $mode === 'sticky' ? 'selected' : '' ?>>Sticky Static Text (Fixed single or static announcements)</option>
                            <option value="fade" <?= $mode === 'fade' ? 'selected' : '' ?>>Smooth Fade Animation (Transitions between announcements)</option>
                        </select>
                    </div>
                </div>

                <!-- Colors & Dimensions -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 pt-4 border-t border-gray-100">
                    <div>
                        <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">BACKGROUND COLOR</label>
                        <div class="flex items-center gap-3 border border-gray-200 rounded-lg p-2 bg-white">
                            <input type="color" id="bgColorPicker" name="bg_color" value="<?= htmlspecialchars($bgColor) ?>" class="w-8 h-8 rounded border-0 cursor-pointer p-0 bg-transparent">
                            <input type="text" id="bgColorText" value="<?= htmlspecialchars($bgColor) ?>" class="w-full outline-none border-none p-0 font-mono text-sm uppercase text-gray-700 bg-transparent">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">TEXT COLOR</label>
                        <div class="flex items-center gap-3 border border-gray-200 rounded-lg p-2 bg-white">
                            <input type="color" id="textColorPicker" name="text_color" value="<?= htmlspecialchars($textColor) ?>" class="w-8 h-8 rounded border-0 cursor-pointer p-0 bg-transparent">
                            <input type="text" id="textColorText" value="<?= htmlspecialchars($textColor) ?>" class="w-full outline-none border-none p-0 font-mono text-sm uppercase text-gray-700 bg-transparent">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">BAR HEIGHT (PX)</label>
                        <input type="number" id="barHeightInput" name="height" value="<?= (int)$height ?>" min="28" max="70" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-[#0b3c7c] outline-none transition-colors text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">FONT SIZE (PX)</label>
                        <input type="number" id="fontSizeInput" name="font_size" value="<?= (int)$fontSize ?>" min="10" max="20" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-[#0b3c7c] outline-none transition-colors text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">SPEED (SECONDS)</label>
                        <input type="number" id="speedInput" name="speed" value="<?= (int)$speed ?>" min="2" max="30" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-[#0b3c7c] outline-none transition-colors text-sm" title="Duration for marquee loop or slide interval">
                    </div>
                </div>

                <!-- Right End Icon Section -->
                <div class="pt-6 border-t border-gray-100 mt-6 grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" id="showSocialIconToggle" name="show_social_icon" value="1" <?= $showSocial ? 'checked' : '' ?> class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 cursor-pointer" style="accent-color: #0066cc;">
                            <span class="text-xs font-serif font-bold text-gray-500 uppercase tracking-wider">SHOW RIGHT END ICON</span>
                        </label>
                    </div>

                    <div>
                        <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">SOCIAL PLATFORM / ICON</label>
                        <select name="social_platform" id="socialPlatform" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-[#0b3c7c] outline-none transition-colors bg-white text-sm">
                            <option value="instagram" <?= $socialPlatform === 'instagram' ? 'selected' : '' ?>>Instagram Icon</option>
                            <option value="facebook" <?= $socialPlatform === 'facebook' ? 'selected' : '' ?>>Facebook Icon</option>
                            <option value="whatsapp" <?= $socialPlatform === 'whatsapp' ? 'selected' : '' ?>>WhatsApp Icon</option>
                            <option value="twitter" <?= $socialPlatform === 'twitter' ? 'selected' : '' ?>>Twitter / X Icon</option>
                            <option value="phone" <?= $socialPlatform === 'phone' ? 'selected' : '' ?>>Phone Icon</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">ICON LINK URL (OPTIONAL)</label>
                        <input type="url" name="social_url" value="<?= htmlspecialchars($socialUrl) ?>" placeholder="https://instagram.com/yourstore" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-[#0b3c7c] outline-none transition-colors text-sm">
                    </div>
                </div>
            </div>

            <!-- Announcements Messages Section -->
            <div class="pt-8 border-t border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-serif font-bold text-[#0b3c7c]">Announcement Messages</h3>
                    <button type="button" id="addAnnouncementBtn" class="text-gray-700 bg-white border border-gray-200 px-4 py-2 rounded-full font-bold text-xs uppercase tracking-wider shadow-sm hover:bg-gray-50 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        ADD ANNOUNCEMENT
                    </button>
                </div>

                <div id="announcementList" class="space-y-6">
                    <?php foreach ($announcements as $idx => $item): ?>
                    <!-- Announcement Item Block -->
                    <div class="bg-white rounded-[1.5rem] shadow-sm border border-gray-100 p-8 relative announcement-item-block">
                        <button type="button" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors remove-announcement-btn" title="Remove">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                        
                        <h4 class="text-lg font-serif font-bold text-[#0b3c7c] mb-6 border-b border-gray-100 pb-3">Announcement Item</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                            <div class="md:col-span-6">
                                <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">ANNOUNCEMENT TEXT *</label>
                                <input type="text" name="announcement_text[]" value="<?= htmlspecialchars($item['text'] ?? '') ?>" placeholder="e.g. Free Shipping on orders over $50" required class="announcement-input w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-[#0b3c7c] outline-none transition-colors text-sm">
                            </div>
                            <div class="md:col-span-3">
                                <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">CLICKABLE LINK URL (OPTIONAL)</label>
                                <input type="text" name="announcement_cta_url[]" value="<?= htmlspecialchars($item['cta_url'] ?? '') ?>" placeholder="/catalog or https://..." class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-[#0b3c7c] outline-none transition-colors text-sm">
                            </div>
                            <div class="md:col-span-3">
                                <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">PREFIX ICON</label>
                                <?php $iconVal = $item['icon'] ?? 'none'; ?>
                                <select name="announcement_icon[]" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-[#0b3c7c] outline-none transition-colors bg-white text-sm">
                                    <option value="none" <?= $iconVal === 'none' ? 'selected' : '' ?>>None</option>
                                    <option value="truck" <?= $iconVal === 'truck' ? 'selected' : '' ?>>Free Shipping Truck</option>
                                    <option value="tag" <?= $iconVal === 'tag' ? 'selected' : '' ?>>Discount Tag</option>
                                    <option value="fire" <?= $iconVal === 'fire' ? 'selected' : '' ?>>Hot Deal / Fire</option>
                                    <option value="star" <?= $iconVal === 'star' ? 'selected' : '' ?>>Star / Special</option>
                                </select>
                                <input type="hidden" name="announcement_active[]" value="1">
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Bottom Action Row matching Fourth Image -->
            <div class="mt-8 px-8 pt-6 pb-8 -mx-8 -mb-8 flex justify-between items-center border-t border-gray-100 rounded-b-2xl">
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="is_active" class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 cursor-pointer" style="accent-color: #0066cc;" value="1" <?= !isset($topbar['is_active']) || $topbar['is_active'] ? 'checked' : '' ?>>
                    <label for="is_active" class="font-bold text-black text-sm cursor-pointer">Active (Visible on Site)</label>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="<?= BASE_URL ?>/admin/cms/topbars" class="px-6 py-2.5 rounded-full border border-red-200 text-red-500 font-bold hover:bg-red-50 transition-colors bg-white text-xs uppercase tracking-wider">CANCEL</a>
                    <button type="submit" class="px-8 py-2.5 bg-gray-900 text-white font-bold text-sm rounded-full shadow-sm hover:bg-black transition-all">
                        Update Top Bar
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bgColorPicker = document.getElementById('bgColorPicker');
    const bgColorText = document.getElementById('bgColorText');
    const textColorPicker = document.getElementById('textColorPicker');
    const textColorText = document.getElementById('textColorText');
    const barHeightInput = document.getElementById('barHeightInput');
    const fontSizeInput = document.getElementById('fontSizeInput');
    const barMode = document.getElementById('barMode');
    const livePreviewContainer = document.getElementById('livePreviewContainer');
    const livePreviewText = document.getElementById('livePreviewText');
    const livePreviewSocial = document.getElementById('livePreviewSocial');
    const showSocialIconToggle = document.getElementById('showSocialIconToggle');

    function updateLivePreview() {
        if (livePreviewContainer) {
            livePreviewContainer.style.backgroundColor = bgColorPicker.value;
            livePreviewContainer.style.color = textColorPicker.value;
            livePreviewContainer.style.minHeight = barHeightInput.value + 'px';
        }
        if (livePreviewText) {
            livePreviewText.style.fontSize = fontSizeInput.value + 'px';
            if (barMode.value === 'slider') {
                livePreviewText.classList.add('grm-marquee-anim');
                livePreviewText.classList.remove('grm-fade-anim', 'truncate');
            } else if (barMode.value === 'fade') {
                livePreviewText.classList.add('grm-fade-anim');
                livePreviewText.classList.remove('grm-marquee-anim', 'truncate');
            } else {
                livePreviewText.classList.remove('grm-marquee-anim', 'grm-fade-anim');
                livePreviewText.classList.add('truncate');
            }
        }
        if (livePreviewSocial) {
            livePreviewSocial.style.display = showSocialIconToggle.checked ? 'flex' : 'none';
        }
        
        const firstInput = document.querySelector('.announcement-input');
        if (firstInput && livePreviewText) {
            livePreviewText.textContent = firstInput.value || 'Announcement text preview...';
        }
    }

    bgColorPicker.addEventListener('input', function() {
        bgColorText.value = bgColorPicker.value;
        updateLivePreview();
    });
    bgColorText.addEventListener('input', function() {
        if (/^#[0-9A-F]{6}$/i.test(bgColorText.value)) {
            bgColorPicker.value = bgColorText.value;
            updateLivePreview();
        }
    });

    textColorPicker.addEventListener('input', function() {
        textColorText.value = textColorPicker.value;
        updateLivePreview();
    });
    textColorText.addEventListener('input', function() {
        if (/^#[0-9A-F]{6}$/i.test(textColorText.value)) {
            textColorPicker.value = textColorText.value;
            updateLivePreview();
        }
    });

    barHeightInput.addEventListener('input', updateLivePreview);
    fontSizeInput.addEventListener('input', updateLivePreview);
    barMode.addEventListener('change', updateLivePreview);
    showSocialIconToggle.addEventListener('change', updateLivePreview);

    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('announcement-input')) {
            updateLivePreview();
        }
    });

    const addBtn = document.getElementById('addAnnouncementBtn');
    const list = document.getElementById('announcementList');

    if (addBtn && list) {
        addBtn.addEventListener('click', function() {
            const newItem = document.createElement('div');
            newItem.className = 'bg-white rounded-[1.5rem] shadow-sm border border-gray-100 p-8 relative announcement-item-block animate-fadeIn';
            newItem.innerHTML = `
                <button type="button" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors remove-announcement-btn" title="Remove">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                
                <h4 class="text-lg font-serif font-bold text-[#0b3c7c] mb-6 border-b border-gray-100 pb-3">Announcement Item</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                    <div class="md:col-span-6">
                        <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">ANNOUNCEMENT TEXT *</label>
                        <input type="text" name="announcement_text[]" value="" placeholder="e.g. Extra 10% Off on Bulk Orders" required class="announcement-input w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-[#0b3c7c] outline-none transition-colors text-sm">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">CLICKABLE LINK URL (OPTIONAL)</label>
                        <input type="text" name="announcement_cta_url[]" value="" placeholder="/catalog" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-[#0b3c7c] outline-none transition-colors text-sm">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">PREFIX ICON</label>
                        <select name="announcement_icon[]" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-[#0b3c7c] outline-none transition-colors bg-white text-sm">
                            <option value="none">None</option>
                            <option value="truck">Free Shipping Truck</option>
                            <option value="tag" selected>Discount Tag</option>
                            <option value="fire">Hot Deal / Fire</option>
                            <option value="star">Star / Special</option>
                        </select>
                        <input type="hidden" name="announcement_active[]" value="1">
                    </div>
                </div>
            `;
            list.appendChild(newItem);
        });

        list.addEventListener('click', function(e) {
            const removeBtn = e.target.closest('.remove-announcement-btn');
            if (removeBtn) {
                const items = list.querySelectorAll('.announcement-item-block');
                if (items.length > 1) {
                    removeBtn.closest('.announcement-item-block').remove();
                    updateLivePreview();
                } else {
                    alert('You must have at least one announcement item.');
                }
            }
        });
    }

    updateLivePreview();
});
</script>
