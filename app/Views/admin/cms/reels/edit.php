<?php
$content = $container['content'] ?? [];
$title = $content['title'] ?? '';
$subtitle = $content['subtitle'] ?? '';
$deskHeight = $content['desktop_height_vh'] ?? '70';
$mobHeight = $content['mobile_height_vh'] ?? '55';
$deskCols = $content['desktop_view_count'] ?? 4;
$mobCols = $content['mobile_view_count'] ?? 2;
$autoplay = !empty($content['autoplay']);
$muted = !empty($content['muted']);
$loop = !empty($content['loop']);
$isActive = !empty($container['is_active']);
$items = !empty($content['items']) && is_array($content['items']) ? $content['items'] : [];
?>

<div class="mb-8 relative z-10">
    <!-- Breadcrumbs -->
    <div class="text-sm font-medium text-gray-400 mb-2 font-serif tracking-wide">
        Dashboard <span class="mx-1 text-gray-300">›</span> CMS Management <span class="mx-1 text-gray-300">›</span> <a href="<?= BASE_URL ?>/admin/cms/reels" class="text-gray-500 hover:text-gray-700">Reels Container</a> <span class="mx-1 text-gray-300">›</span> <span class="text-[#F25996] font-bold">Edit Container</span>
    </div>
    
    <div>
        <h2 class="text-3xl sm:text-4xl font-display font-extrabold text-gray-900 tracking-tight flex items-center gap-3">
            <span>Edit Reels Container</span>
            <span class="text-xs bg-rose-50 text-rose-600 border border-rose-100 px-3 py-1 rounded-full uppercase tracking-widest font-black"><?= htmlspecialchars($container['id']) ?></span>
        </h2>
        <p class="text-sm text-gray-500 mt-1">Modify viewport heights in VH, display counts, and update Cloudinary video reels.</p>
    </div>
</div>

<form id="reels-form" action="<?= BASE_URL ?>/admin/cms/reels/update" method="POST" enctype="multipart/form-data" class="space-y-8">
    <input type="hidden" name="id" value="<?= htmlspecialchars($container['id']) ?>">

    <!-- Section 1: Container Viewport & Display Settings -->
    <div class="bg-white rounded-[1.5rem] shadow-custom border border-gray-100 p-6 sm:p-8">
        <div class="flex items-center justify-between pb-6 mb-6 border-b border-gray-100">
            <div>
                <h3 class="text-xl font-display font-bold text-gray-900">Container Dimensions & Display Rules</h3>
                <p class="text-xs text-gray-500 mt-1">Configure responsive heights in VH and visible item counts for desktop and mobile viewports.</p>
            </div>
            <div class="flex items-center gap-2">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" <?= $isActive ? 'checked' : '' ?> class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#F25996]"></div>
                    <span class="ml-3 text-xs font-bold text-gray-700">Publish to Storefront</span>
                </label>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Section Title -->
            <div class="lg:col-span-2">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Section Headline / Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($title) ?>" placeholder="e.g. Trending Reels & Live Styles" class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-[#F25996]/30 focus:border-[#F25996] outline-none transition-all">
            </div>

            <!-- Section Subtitle -->
            <div class="lg:col-span-2">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Section Subtitle / Tagline</label>
                <input type="text" name="subtitle" value="<?= htmlspecialchars($subtitle) ?>" placeholder="e.g. Tap to watch video and order direct" class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-[#F25996]/30 focus:border-[#F25996] outline-none transition-all">
            </div>

            <!-- Desktop Height in VH -->
            <div class="bg-gray-50/70 p-4 rounded-2xl border border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Desktop Height (vh)</label>
                    <span class="text-[10px] bg-blue-100 text-blue-700 px-2 py-0.5 rounded font-black">Desktop View</span>
                </div>
                <div class="flex items-center gap-2">
                    <input type="number" step="1" min="30" max="100" name="desktop_height_vh" value="<?= htmlspecialchars((string)$deskHeight) ?>" required class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-800 focus:ring-2 focus:ring-[#F25996]/30 outline-none">
                    <span class="text-xs font-bold text-gray-500">vh</span>
                </div>
                <p class="text-[11px] text-gray-400 mt-1.5">Recommended: 65vh – 75vh</p>
            </div>

            <!-- Mobile Height in VH -->
            <div class="bg-gray-50/70 p-4 rounded-2xl border border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Mobile Height (vh)</label>
                    <span class="text-[10px] bg-pink-100 text-pink-700 px-2 py-0.5 rounded font-black">Mobile View</span>
                </div>
                <div class="flex items-center gap-2">
                    <input type="number" step="1" min="30" max="100" name="mobile_height_vh" value="<?= htmlspecialchars((string)$mobHeight) ?>" required class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-800 focus:ring-2 focus:ring-[#F25996]/30 outline-none">
                    <span class="text-xs font-bold text-gray-500">vh</span>
                </div>
                <p class="text-[11px] text-gray-400 mt-1.5">Recommended: 50vh – 60vh</p>
            </div>

            <!-- Desktop View Count -->
            <div class="bg-gray-50/70 p-4 rounded-2xl border border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Desktop View Count</label>
                    <span class="text-[10px] bg-blue-100 text-blue-700 px-2 py-0.5 rounded font-black">Columns</span>
                </div>
                <input type="number" step="0.5" min="2" max="8" name="desktop_view_count" value="<?= htmlspecialchars((string)$deskCols) ?>" required class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-800 focus:ring-2 focus:ring-[#F25996]/30 outline-none">
                <p class="text-[11px] text-gray-400 mt-1.5">Visible reels on desktop (e.g. 4 or 5)</p>
            </div>

            <!-- Mobile View Count -->
            <div class="bg-gray-50/70 p-4 rounded-2xl border border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Mobile View Count</label>
                    <span class="text-[10px] bg-pink-100 text-pink-700 px-2 py-0.5 rounded font-black">Columns</span>
                </div>
                <input type="number" step="0.5" min="1" max="4" name="mobile_view_count" value="<?= htmlspecialchars((string)$mobCols) ?>" required class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-800 focus:ring-2 focus:ring-[#F25996]/30 outline-none">
                <p class="text-[11px] text-gray-400 mt-1.5">Visible reels on mobile (e.g. 1.5 or 2)</p>
            </div>

            <!-- Video Player Controls -->
            <div class="lg:col-span-4 grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 border-t border-gray-100">
                <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100/70 transition-colors">
                    <input type="checkbox" name="autoplay" value="1" <?= $autoplay ? 'checked' : '' ?> class="w-4 h-4 text-[#F25996] rounded border-gray-300 focus:ring-[#F25996]">
                    <span class="text-xs font-semibold text-gray-700">Autoplay on Scroll / Hover</span>
                </label>
                <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100/70 transition-colors">
                    <input type="checkbox" name="muted" value="1" <?= $muted ? 'checked' : '' ?> class="w-4 h-4 text-[#F25996] rounded border-gray-300 focus:ring-[#F25996]">
                    <span class="text-xs font-semibold text-gray-700">Muted by Default</span>
                </label>
                <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100/70 transition-colors">
                    <input type="checkbox" name="loop" value="1" <?= $loop ? 'checked' : '' ?> class="w-4 h-4 text-[#F25996] rounded border-gray-300 focus:ring-[#F25996]">
                    <span class="text-xs font-semibold text-gray-700">Continuous Loop Playback</span>
                </label>
            </div>
        </div>
    </div>

    <!-- Section 2: Dynamic Reels Repeater Items -->
    <div class="bg-white rounded-[1.5rem] shadow-custom border border-gray-100 p-6 sm:p-8">
        <div class="flex items-center justify-between pb-6 mb-6 border-b border-gray-100">
            <div>
                <h3 class="text-xl font-display font-bold text-gray-900 flex items-center gap-2">
                    <span>Reel Videos & Images</span>
                    <span id="reels-count-badge" class="px-2.5 py-0.5 rounded-full text-xs font-black bg-pink-100 text-[#F25996]"><?= count($items) ?> Reels</span>
                </h3>
                <p class="text-xs text-gray-500 mt-1">Upload video files & poster images directly to Cloudinary or select from existing media library.</p>
            </div>
            <button type="button" onclick="addReelRow()" class="bg-[#F25996] hover:bg-[#e04481] text-white px-5 py-2.5 rounded-full font-bold text-xs uppercase tracking-wider shadow-sm flex items-center gap-2 transition-all hover:scale-105 active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                + Add Another Reel
            </button>
        </div>

        <!-- Reel Items Container -->
        <div id="reels-container" class="space-y-6">
            <!-- Dynamically populated via JS -->
        </div>

        <div class="mt-6 pt-6 border-t border-gray-100 flex justify-center">
            <button type="button" onclick="addReelRow()" class="w-full sm:w-auto px-8 py-3 bg-gray-50 hover:bg-gray-100 text-gray-700 border-2 border-dashed border-gray-300 hover:border-[#F25996] rounded-2xl font-bold text-xs uppercase tracking-widest flex items-center justify-center gap-2 transition-all hover:text-[#F25996]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Another Video Reel
            </button>
        </div>

        <!-- Bottom Action Bar (Hero Banner Style) -->
        <div class="border-t border-gray-100 pt-6 mt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-xs text-gray-400 font-medium">Ready to deploy changes to the storefront reels section.</p>
            <div class="flex items-center gap-4">
                <a href="<?= BASE_URL ?>/admin/cms/reels" class="px-6 py-2.5 border border-red-200 text-red-500 hover:bg-red-50 font-bold text-sm rounded-full transition-colors uppercase tracking-wider">
                    CANCEL
                </a>
                <button type="submit" id="submit-btn" class="px-8 py-2.5 bg-gray-900 hover:bg-black text-white font-bold text-sm rounded-full shadow-sm transition-all flex items-center gap-2 uppercase tracking-wider">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span id="submit-btn-text">Update Reels Container</span>
                </button>
            </div>
        </div>
    </div>
</form>

<style>
.shadow-custom { box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
</style>

<!-- Media Picker Modal (Cloudinary Assets) -->
<div id="mediaPickerModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm hidden z-[100] flex items-center justify-center p-4 transition-all">
    <div class="bg-white w-full max-w-5xl rounded-3xl shadow-2xl flex flex-col max-h-[90vh] overflow-hidden border border-gray-100">
        <!-- Header -->
        <div class="flex-shrink-0 flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50/80">
            <div class="flex items-center gap-4">
                <h3 class="font-display font-bold text-gray-800 text-lg flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#F25996]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span>Cloudinary Media Library</span>
                </h3>
                <div class="flex items-center gap-1 bg-white p-1 rounded-xl border border-gray-200 text-xs">
                    <button type="button" onclick="filterMediaPicker('all')" id="tab-all" class="px-3 py-1 rounded-lg font-bold bg-[#F25996] text-white transition-all">All</button>
                    <button type="button" onclick="filterMediaPicker('video')" id="tab-video" class="px-3 py-1 rounded-lg font-bold text-gray-600 hover:text-gray-900 transition-all">Videos</button>
                    <button type="button" onclick="filterMediaPicker('image')" id="tab-image" class="px-3 py-1 rounded-lg font-bold text-gray-600 hover:text-gray-900 transition-all">Images</button>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <label class="cursor-pointer inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-[#F25996] hover:bg-[#d63f7d] text-white text-xs font-bold rounded-xl shadow-sm transition-all active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    <span id="modal-upload-btn-text">Upload to Cloudinary</span>
                    <input type="file" id="modalPickerFileInput" class="hidden" accept="image/*,video/*" onchange="uploadFromModal(this)">
                </label>
                <button type="button" onclick="closeMediaPicker()" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-xl hover:bg-gray-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>
        <!-- Grid -->
        <div class="p-6 overflow-y-auto flex-1 min-h-0 bg-gray-50/30">
            <div id="mediaPickerGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <div class="col-span-full text-center text-gray-400 py-12 flex flex-col items-center gap-2">
                    <svg class="animate-spin w-8 h-8 text-[#F25996]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span class="text-xs font-semibold">Loading Cloudinary media...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let reelIndex = 0;
let activeMediaPickerCallback = null;
let cachedMediaList = [];
let currentMediaFilter = 'all';

function addReelRow(data = {}) {
    const container = document.getElementById('reels-container');
    const idx = reelIndex++;

    const title = data.title || '';
    const ctaUrl = data.cta_url || '';
    const ctaText = data.cta_text || 'Shop Now';
    const deskImg = data.desktop_image || '';
    const mobImg = data.mobile_image || '';
    const videoUrl = data.video_url || '';
    const sort = data.sort_order || (idx + 1);

    const card = document.createElement('div');
    card.id = `reel-card-${idx}`;
    card.className = 'p-6 sm:p-7 rounded-3xl border border-gray-200/90 bg-gray-50/50 relative group hover:border-[#F25996]/50 hover:bg-white transition-all shadow-sm';
    card.innerHTML = `
        <!-- Card Header -->
        <div class="flex items-center justify-between pb-4 mb-5 border-b border-gray-200/80">
            <div class="flex items-center gap-2.5">
                <span class="w-8 h-8 rounded-xl bg-[#F25996] text-white flex items-center justify-center text-xs font-black shadow-sm shadow-[#F25996]/30">#${idx + 1}</span>
                <span class="font-display font-bold text-gray-800 text-sm">Reel Item Configuration</span>
            </div>
            <button type="button" onclick="removeReelRow(${idx})" class="text-gray-400 hover:text-red-500 p-1.5 rounded-xl hover:bg-red-50 transition-colors" title="Remove Reel">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        </div>

        <!-- Top Row: Title, Target Link, Button Text, Sort Order in a SINGLE ROW -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-4 sm:p-5 mb-5 bg-white rounded-2xl border border-gray-200/90 shadow-xs">
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Reel Title / Product Tag *</label>
                <input type="text" name="reel_title[]" value="${escapeHtml(title)}" placeholder="e.g. Pure Silk Banarasi Saree" required class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-[#F25996]/30 focus:border-[#F25996] outline-none transition-all">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Target Link / CTA URL</label>
                <input type="text" name="reel_cta_url[]" value="${escapeHtml(ctaUrl)}" placeholder="e.g. /catalog or /products" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-[#F25996]/30 focus:border-[#F25996] outline-none transition-all">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Button Text</label>
                <input type="text" name="reel_cta_text[]" value="${escapeHtml(ctaText)}" placeholder="Shop Now" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-[#F25996]/30 outline-none transition-all">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Sort Order</label>
                <input type="number" name="reel_sort_order[]" value="${sort}" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-[#F25996]/30 outline-none transition-all">
            </div>
        </div>

        <!-- Bottom Row: Media Uploaders (Video on Left, Desktop & Mobile Posters on Right) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
            
            <!-- 1. VIDEO ASSET (6 cols) -->
            <div class="lg:col-span-6 space-y-4">
                <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-200/90 shadow-xs h-full flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-xs font-bold text-gray-800 uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-[#F25996]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                <span>Video Asset (Direct Cloudinary Upload / Library)</span>
                            </label>
                            <span class="text-[10px] font-black text-rose-500 bg-rose-50 px-2 py-0.5 rounded-md uppercase">MP4 / WebM / Cloudinary</span>
                        </div>

                        <!-- Action Buttons for Video -->
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <label class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-xl text-xs font-bold transition-all hover:scale-105 active:scale-95 border border-indigo-200/60">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                <span id="btn-video-text-${idx}">Choose Video File</span>
                                <input type="file" accept="video/mp4,video/webm,video/ogg,video/quicktime" class="hidden" onchange="uploadMediaFile(this, 'video', ${idx})">
                            </label>

                            <button type="button" onclick="openMediaPickerFor('video', ${idx})" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition-all border border-gray-200">
                                <svg class="w-3.5 h-3.5 text-[#F25996]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span>Browse Library</span>
                            </button>

                            <span id="status-video-${idx}" class="text-[11px] font-semibold text-gray-400 hidden"></span>
                        </div>

                        <!-- Direct URL input -->
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Cloudinary URL:</span>
                            <input type="url" name="reel_video_url[]" id="input-video-url-${idx}" value="${escapeHtml(videoUrl)}" placeholder="https://res.cloudinary.com/.../video.mp4" oninput="syncVideoPreview(this.value, ${idx})" class="flex-1 px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg text-xs font-mono text-gray-700 outline-none focus:bg-white focus:border-[#F25996]">
                        </div>
                    </div>

                    <!-- Video Preview Container -->
                    <div id="video-wrap-${idx}" class="mt-3 ${videoUrl ? '' : 'hidden'}">
                        <div class="relative rounded-xl overflow-hidden bg-black/90 border border-gray-200">
                            <video id="video-preview-${idx}" src="${escapeHtml(videoUrl)}" class="w-full h-36 object-contain" controls playsinline preload="metadata"></video>
                            <button type="button" onclick="clearVideoAsset(${idx})" class="absolute top-2 right-2 bg-black/60 hover:bg-black/90 text-white p-1.5 rounded-lg text-xs font-bold backdrop-blur-md transition-all flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                <span>Clear Video</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. DESKTOP & MOBILE POSTER IMAGES (6 cols) -->
            <div class="lg:col-span-6 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 h-full">
                    
                    <!-- Desktop Poster Card -->
                    <div class="bg-white p-4 rounded-2xl border border-gray-200/90 shadow-xs flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-xs font-bold text-gray-800 uppercase tracking-wider">Desktop Poster</label>
                                <span class="text-[9px] font-black text-blue-600 bg-blue-50 px-2 py-0.5 rounded">Desktop View</span>
                            </div>

                            <div class="flex flex-wrap items-center gap-1.5 mb-2.5">
                                <label class="cursor-pointer inline-flex items-center gap-1.5 px-3 py-1.5 bg-pink-50 hover:bg-pink-100 text-[#F25996] rounded-lg text-xs font-bold transition-all border border-pink-200/60">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    <span id="btn-desk-text-${idx}">Choose Image</span>
                                    <input type="file" accept="image/*" class="hidden" onchange="uploadMediaFile(this, 'desktop_image', ${idx})">
                                </label>
                                <button type="button" onclick="openMediaPickerFor('desktop_image', ${idx})" class="px-2.5 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-bold transition-all border border-gray-200">
                                    Library
                                </button>
                            </div>

                            <input type="url" name="reel_desktop_image_url[]" id="input-desk-url-${idx}" value="${escapeHtml(deskImg)}" placeholder="Or paste Cloudinary image URL..." oninput="syncImagePreview(this.value, 'desk-img-prev-${idx}', ${idx})" class="w-full px-2.5 py-1.5 bg-gray-50 border border-gray-200 rounded-lg text-xs font-mono text-gray-700 outline-none focus:bg-white focus:border-[#F25996]">
                        </div>

                        <!-- Desktop Poster Thumbnail Preview -->
                        <div class="mt-3 relative h-28 bg-gray-100 rounded-xl overflow-hidden border border-gray-200 flex items-center justify-center group/desk">
                            <img id="desk-img-prev-${idx}" src="${deskImg || 'https://placehold.co/400x600?text=Desktop+Poster'}" class="w-full h-full object-cover">
                            ${deskImg ? `
                                <button type="button" onclick="clearImageAsset('desktop_image', ${idx})" class="absolute top-1.5 right-1.5 bg-black/60 hover:bg-black/80 text-white p-1 rounded-md text-[10px] backdrop-blur-sm transition-all" title="Remove image">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            ` : ''}
                        </div>
                    </div>

                    <!-- Mobile Poster Card -->
                    <div class="bg-white p-4 rounded-2xl border border-gray-200/90 shadow-xs flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-xs font-bold text-gray-800 uppercase tracking-wider">Mobile Poster</label>
                                <button type="button" onclick="copyDesktopToMobile(${idx})" class="text-[10px] text-[#F25996] hover:underline font-bold" title="Copy desktop poster image">
                                    Copy Desktop
                                </button>
                            </div>

                            <div class="flex flex-wrap items-center gap-1.5 mb-2.5">
                                <label class="cursor-pointer inline-flex items-center gap-1.5 px-3 py-1.5 bg-pink-50 hover:bg-pink-100 text-[#F25996] rounded-lg text-xs font-bold transition-all border border-pink-200/60">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    <span id="btn-mob-text-${idx}">Choose Image</span>
                                    <input type="file" accept="image/*" class="hidden" onchange="uploadMediaFile(this, 'mobile_image', ${idx})">
                                </label>
                                <button type="button" onclick="openMediaPickerFor('mobile_image', ${idx})" class="px-2.5 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-bold transition-all border border-gray-200">
                                    Library
                                </button>
                            </div>

                            <input type="url" name="reel_mobile_image_url[]" id="input-mob-url-${idx}" value="${escapeHtml(mobImg)}" placeholder="Or paste Cloudinary image URL..." oninput="syncImagePreview(this.value, 'mob-img-prev-${idx}', ${idx})" class="w-full px-2.5 py-1.5 bg-gray-50 border border-gray-200 rounded-lg text-xs font-mono text-gray-700 outline-none focus:bg-white focus:border-[#F25996]">
                        </div>

                        <!-- Mobile Poster Thumbnail Preview -->
                        <div class="mt-3 relative h-28 bg-gray-100 rounded-xl overflow-hidden border border-gray-200 flex items-center justify-center group/mob">
                            <img id="mob-img-prev-${idx}" src="${mobImg || deskImg || 'https://placehold.co/400x600?text=Mobile+Poster'}" class="w-full h-full object-cover">
                            ${mobImg ? `
                                <button type="button" onclick="clearImageAsset('mobile_image', ${idx})" class="absolute top-1.5 right-1.5 bg-black/60 hover:bg-black/80 text-white p-1 rounded-md text-[10px] backdrop-blur-sm transition-all" title="Remove image">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            ` : ''}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    `;

    container.appendChild(card);
    updateCountBadge();
}

function removeReelRow(idx) {
    const card = document.getElementById(`reel-card-${idx}`);
    if (card) {
        card.remove();
        updateCountBadge();
    }
}

function updateCountBadge() {
    const cards = document.querySelectorAll('[id^="reel-card-"]');
    const badge = document.getElementById('reels-count-badge');
    if (badge) {
        badge.innerText = `${cards.length} ${cards.length === 1 ? 'Reel' : 'Reels'}`;
    }
}

/**
 * Upload single file directly to Cloudinary via AJAX (/admin/media/upload)
 */
function uploadMediaFile(input, targetType, idx) {
    if (!input.files || input.files.length === 0) return;
    const file = input.files[0];

    const submitBtn = document.getElementById('submit-btn');
    const submitBtnText = document.getElementById('submit-btn-text');
    let btnLabelEl = null;

    if (targetType === 'video') {
        btnLabelEl = document.getElementById(`btn-video-text-${idx}`);
    } else if (targetType === 'desktop_image') {
        btnLabelEl = document.getElementById(`btn-desk-text-${idx}`);
    } else if (targetType === 'mobile_image') {
        btnLabelEl = document.getElementById(`btn-mob-text-${idx}`);
    }

    const origLabel = btnLabelEl ? btnLabelEl.innerText : 'Upload';
    if (btnLabelEl) btnLabelEl.innerText = 'Uploading to Cloudinary...';

    if (submitBtn) {
        submitBtn.disabled = true;
        if (submitBtnText) submitBtnText.innerText = 'Uploading Media...';
    }

    const fd = new FormData();
    fd.append('image', file); // MediaController uses 'image'

    fetch('<?= BASE_URL ?>/admin/media/upload', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: fd
    })
    .then(r => r.json())
    .then(res => {
        if (res.success && res.url) {
            showUploadToast('Uploaded to Cloudinary successfully!', 'success');
            if (targetType === 'video') {
                const urlInput = document.getElementById(`input-video-url-${idx}`);
                if (urlInput) urlInput.value = res.url;
                syncVideoPreview(res.url, idx);
            } else if (targetType === 'desktop_image') {
                const urlInput = document.getElementById(`input-desk-url-${idx}`);
                if (urlInput) urlInput.value = res.url;
                syncImagePreview(res.url, `desk-img-prev-${idx}`, idx);
                // If mobile poster is blank, also set it
                const mobInput = document.getElementById(`input-mob-url-${idx}`);
                if (mobInput && !mobInput.value) {
                    mobInput.value = res.url;
                    syncImagePreview(res.url, `mob-img-prev-${idx}`, idx);
                }
            } else if (targetType === 'mobile_image') {
                const urlInput = document.getElementById(`input-mob-url-${idx}`);
                if (urlInput) urlInput.value = res.url;
                syncImagePreview(res.url, `mob-img-prev-${idx}`, idx);
            }
        } else {
            showUploadToast(res.error || 'Upload failed. Please check format/size.', 'error');
        }
    })
    .catch(err => {
        console.error('Cloudinary upload error:', err);
        showUploadToast('Network error during upload.', 'error');
    })
    .finally(() => {
        if (btnLabelEl) btnLabelEl.innerText = origLabel;
        if (submitBtn) {
            submitBtn.disabled = false;
            if (submitBtnText) submitBtnText.innerText = 'Update Reels Container';
        }
        input.value = ''; // Reset input to allow selecting same file again
    });
}

function syncVideoPreview(url, idx) {
    const video = document.getElementById(`video-preview-${idx}`);
    const wrap = document.getElementById(`video-wrap-${idx}`);
    if (!video || !wrap) return;

    if (url && url.trim() !== '') {
        video.src = url.trim();
        wrap.classList.remove('hidden');
    } else {
        video.src = '';
        wrap.classList.add('hidden');
    }
}

function clearVideoAsset(idx) {
    const input = document.getElementById(`input-video-url-${idx}`);
    if (input) input.value = '';
    syncVideoPreview('', idx);
}

function syncImagePreview(url, imgId, idx) {
    const img = document.getElementById(imgId);
    if (!img) return;

    if (url && url.trim() !== '') {
        img.src = url.trim();
    } else {
        img.src = 'https://placehold.co/400x600?text=' + (imgId.includes('desk') ? 'Desktop+Poster' : 'Mobile+Poster');
    }
}

function clearImageAsset(type, idx) {
    if (type === 'desktop_image') {
        const input = document.getElementById(`input-desk-url-${idx}`);
        if (input) input.value = '';
        syncImagePreview('', `desk-img-prev-${idx}`, idx);
    } else {
        const input = document.getElementById(`input-mob-url-${idx}`);
        if (input) input.value = '';
        syncImagePreview('', `mob-img-prev-${idx}`, idx);
    }
}

function copyDesktopToMobile(idx) {
    const deskInput = document.getElementById(`input-desk-url-${idx}`);
    const mobInput = document.getElementById(`input-mob-url-${idx}`);
    if (deskInput && mobInput && deskInput.value) {
        mobInput.value = deskInput.value;
        syncImagePreview(deskInput.value, `mob-img-prev-${idx}`, idx);
        showUploadToast('Copied desktop image to mobile poster', 'info');
    }
}

// Media Picker Modal Integration
function openMediaPickerFor(targetType, idx) {
    activeMediaPickerCallback = (selectedUrl, isVideo) => {
        if (targetType === 'video') {
            const input = document.getElementById(`input-video-url-${idx}`);
            if (input) input.value = selectedUrl;
            syncVideoPreview(selectedUrl, idx);
        } else if (targetType === 'desktop_image') {
            const input = document.getElementById(`input-desk-url-${idx}`);
            if (input) input.value = selectedUrl;
            syncImagePreview(selectedUrl, `desk-img-prev-${idx}`, idx);
        } else if (targetType === 'mobile_image') {
            const input = document.getElementById(`input-mob-url-${idx}`);
            if (input) input.value = selectedUrl;
            syncImagePreview(selectedUrl, `mob-img-prev-${idx}`, idx);
        }
    };

    const modal = document.getElementById('mediaPickerModal');
    if (modal) modal.classList.remove('hidden');
    loadMediaPickerAssets();
}

function closeMediaPicker() {
    const modal = document.getElementById('mediaPickerModal');
    if (modal) modal.classList.add('hidden');
    activeMediaPickerCallback = null;
}

function filterMediaPicker(filter) {
    currentMediaFilter = filter;
    ['all', 'video', 'image'].forEach(t => {
        const btn = document.getElementById(`tab-${t}`);
        if (btn) {
            if (t === filter) {
                btn.className = 'px-3 py-1 rounded-lg font-bold bg-[#F25996] text-white transition-all';
            } else {
                btn.className = 'px-3 py-1 rounded-lg font-bold text-gray-600 hover:text-gray-900 transition-all';
            }
        }
    });
    renderMediaGrid();
}

function loadMediaPickerAssets() {
    const grid = document.getElementById('mediaPickerGrid');
    if (!grid) return;

    if (cachedMediaList.length > 0) {
        renderMediaGrid();
        return;
    }

    grid.innerHTML = '<div class="col-span-full text-center text-gray-400 py-12">Loading Cloudinary media...</div>';

    fetch('<?= BASE_URL ?>/admin/media/ajax-get')
        .then(r => r.json())
        .then(res => {
            if (res.success && Array.isArray(res.data)) {
                cachedMediaList = res.data;
                renderMediaGrid();
            } else {
                grid.innerHTML = '<div class="col-span-full text-center text-gray-400 py-12">No media found in library.</div>';
            }
        })
        .catch(err => {
            console.error('Error fetching media:', err);
            grid.innerHTML = '<div class="col-span-full text-center text-red-400 py-12">Error loading media.</div>';
        });
}

function renderMediaGrid() {
    const grid = document.getElementById('mediaPickerGrid');
    if (!grid) return;

    let items = cachedMediaList;
    if (currentMediaFilter === 'video') {
        items = items.filter(m => isVideoMedia(m));
    } else if (currentMediaFilter === 'image') {
        items = items.filter(m => !isVideoMedia(m));
    }

    if (items.length === 0) {
        grid.innerHTML = '<div class="col-span-full text-center text-gray-400 py-12">No matching media files.</div>';
        return;
    }

    grid.innerHTML = '';
    items.forEach(media => {
        const isVideo = isVideoMedia(media);
        const card = document.createElement('div');
        card.className = 'border border-gray-200 rounded-2xl overflow-hidden cursor-pointer hover:border-[#F25996] hover:shadow-md transition-all group relative bg-white';
        card.innerHTML = `
            <div class="aspect-square bg-gray-50 flex items-center justify-center overflow-hidden relative">
                ${isVideo 
                    ? `<video src="${media.cloudinary_url}" class="w-full h-full object-cover" muted></video>
                       <span class="absolute top-2 left-2 px-1.5 py-0.5 rounded-md bg-black/70 text-[9px] font-bold text-white uppercase tracking-wider">Video</span>` 
                    : `<img src="${media.cloudinary_url}" class="w-full h-full object-cover">`
                }
            </div>
            <div class="p-2 bg-white text-[11px] font-medium text-gray-600 truncate border-t border-gray-100">
                ${escapeHtml(media.original_filename || 'Media Asset')}
            </div>
            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                <span class="text-white text-xs font-bold bg-[#F25996] px-4 py-1.5 rounded-full shadow-lg transform scale-95 group-hover:scale-100 transition-transform">
                    Select
                </span>
            </div>
        `;
        card.onclick = () => {
            if (activeMediaPickerCallback) {
                activeMediaPickerCallback(media.cloudinary_url, isVideo);
                showUploadToast('Selected from Cloudinary library!', 'info');
            }
            closeMediaPicker();
        };
        grid.appendChild(card);
    });
}

function isVideoMedia(m) {
    const fn = (m.original_filename || '').toLowerCase();
    const url = (m.cloudinary_url || '').toLowerCase();
    return fn.endsWith('.mp4') || fn.endsWith('.webm') || fn.endsWith('.mov') || url.includes('/video/upload/');
}

function uploadFromModal(input) {
    if (!input.files || input.files.length === 0) return;
    const file = input.files[0];
    const textEl = document.getElementById('modal-upload-btn-text');
    if (textEl) textEl.innerText = 'Uploading...';

    const fd = new FormData();
    fd.append('image', file);

    fetch('<?= BASE_URL ?>/admin/media/upload', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: fd
    })
    .then(r => r.json())
    .then(res => {
        if (res.success && res.url) {
            showUploadToast('Uploaded successfully!', 'success');
            // Refresh list
            cachedMediaList = [];
            loadMediaPickerAssets();
        } else {
            showUploadToast(res.error || 'Upload failed', 'error');
        }
    })
    .catch(() => showUploadToast('Upload failed', 'error'))
    .finally(() => {
        if (textEl) textEl.innerText = 'Upload to Cloudinary';
        input.value = '';
    });
}

function showUploadToast(msg, type = 'info') {
    const t = document.createElement('div');
    const colors = {
        success: 'bg-emerald-600 text-white',
        error: 'bg-rose-600 text-white',
        info: 'bg-gray-900 text-white'
    };
    t.className = `fixed bottom-6 right-6 px-4 py-3 rounded-2xl shadow-2xl text-xs font-bold z-[110] flex items-center gap-2 transition-all transform translate-y-2 ${colors[type] || colors.info}`;
    t.innerHTML = `
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span>${escapeHtml(msg)}</span>
    `;
    document.body.appendChild(t);
    setTimeout(() => {
        t.style.opacity = '0';
        setTimeout(() => t.remove(), 300);
    }, 3500);
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

document.addEventListener('DOMContentLoaded', () => {
    const initialItems = <?= json_encode($items) ?>;
    if (initialItems && initialItems.length > 0) {
        initialItems.forEach(item => addReelRow(item));
    } else {
        addReelRow({ title: 'New Arrival Fabric Drape', cta_text: 'Order Fabric' });
    }
});
</script>
