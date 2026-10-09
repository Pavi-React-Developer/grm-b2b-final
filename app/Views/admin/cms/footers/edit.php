<?php
// Footer Edit View
$c    = $footer['content'];
$th   = $c['theme']   ?? [];
$co   = $c['contact'] ?? [];
$mp   = $c['maps']    ?? [];
$so   = $c['social']  ?? [];
$cols = $c['columns'] ?? [];

$bgType  = $th['type']  ?? 'solid';
$bgValue = $th['value'] ?? '#1f2937';
$textCol    = $th['text_color']    ?? '#d1d5db';
$headingCol = $th['heading_color'] ?? '#ffffff';
$linkCol    = $th['link_color']    ?? '#9ca3af';

$logoVal = $c['logo'] ?? '';
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600;700&display=swap');
.font-serif { font-family: 'Playfair Display', serif; }
.font-sans  { font-family: 'Inter', sans-serif; }
.bg-brown   { background-color: #8C6A4F; }
.border-brown { border-color: #8C6A4F; }
</style>

<div class="w-full font-sans">
<form action="<?= BASE_URL ?>/admin/cms/footers/update" method="POST" id="footerForm" class="bg-white rounded-[1.5rem] shadow-custom border border-gray-100 p-8 space-y-0">
<input type="hidden" name="id" value="<?= htmlspecialchars($footer['id']) ?>">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-serif text-[#0b3c7c]">Edit Footer</h2>
    </div>

    <!-- ── Row 1: Title + Logo ── -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            <label class="block text-[10px] font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Internal Title *</label>
            <input type="text" name="title" required value="<?= htmlspecialchars($c['title'] ?? 'Footer') ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm">
        </div>
        <div>
            <label class="block text-[10px] font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Footer Logo</label>
            <div class="flex items-center gap-4">
                <div class="w-24 h-24 border-2 border-dashed border-gray-200 rounded-xl flex items-center justify-center relative bg-gray-50 hover:bg-gray-100 cursor-pointer overflow-hidden group" id="logoUploadArea">
                    <input type="hidden" name="logo" id="logoInput" value="<?= htmlspecialchars($logoVal) ?>">
                    <div id="logoPlaceholder" class="text-center text-gray-400 <?= $logoVal ? 'hidden' : '' ?>">
                        <svg class="w-7 h-7 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span class="text-xs font-medium">Upload</span>
                    </div>
                    <img id="logoPreview" src="<?= htmlspecialchars($logoVal) ?>" class="absolute inset-0 w-full h-full object-contain bg-white p-2 <?= $logoVal ? '' : 'hidden' ?>" alt="Logo">
                    <button type="button" id="logoRemoveBtn" class="absolute bottom-1 left-1 text-red-500 z-10 <?= $logoVal ? '' : 'hidden' ?>">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <p class="text-xs text-gray-400">Recommended: PNG with transparent background, 200×60px</p>
            </div>
        </div>
    </div>

    <!-- ── Description & Copyright ── -->
    <div class="mt-12 pt-4 grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            <label class="block text-[10px] font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Description</label>
            <textarea name="description" rows="3" placeholder="Short description about your store…" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm resize-none"><?= htmlspecialchars($c['description'] ?? '') ?></textarea>
        </div>
        <div>
            <label class="block text-[10px] font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Copyright Text</label>
            <input type="text" name="copyright" value="<?= htmlspecialchars($c['copyright'] ?? '') ?>" placeholder="© 2025 GRMmaternitystore. All rights reserved." class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm">
        </div>
    </div>

    <!-- ── Color Theme ── -->
    <div class="mt-12 pt-4">
        <h3 class="text-xl font-serif font-bold text-[#0b3c7c] mb-6 border-b border-gray-100 pb-3">Color Theme</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-3">Background Color</label>
                <input type="hidden" name="bg_color_type"  id="theme_type"  value="<?= htmlspecialchars($bgType) ?>">
                <input type="hidden" name="bg_color_value" id="theme_value" value="<?= htmlspecialchars($bgValue) ?>">
                <div class="border border-gray-200 rounded-xl p-4 space-y-4 bg-gray-50">
                    <div class="flex gap-2">
                        <?php foreach (['solid'=>'Solid','linear'=>'Linear Gradient','radial'=>'Radial Gradient'] as $t=>$tl): ?>
                        <button type="button" data-type="<?= $t ?>" class="color-type-btn <?= $bgType===$t ? 'active bg-gray-900 text-white border-gray-300' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-400' ?> px-4 py-1.5 rounded-full text-xs font-bold border transition-all"><?= $tl ?></button>
                        <?php endforeach; ?>
                        <button type="button" data-type="css" class="color-type-btn <?= $bgType==='css' ? 'active bg-gray-900 text-white border-gray-300' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-400' ?> px-4 py-1.5 rounded-full text-xs font-bold border transition-all">Color Link (CSS)</button>
                    </div>
                    <div class="panel-solid flex items-center gap-4 <?= $bgType!=='solid'?'hidden':'' ?>">
                        <label class="text-xs text-gray-500 font-medium w-12">Color</label>
                        <input type="color" class="color-solid-picker w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5 bg-white" value="<?= $bgType==='solid'?htmlspecialchars($bgValue):'#1f2937' ?>">
                        <input type="text"  class="color-solid-input w-28 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none font-mono" value="<?= $bgType==='solid'?htmlspecialchars($bgValue):'#1f2937' ?>">
                    </div>
                    <div class="panel-linear space-y-3 <?= $bgType!=='linear'?'hidden':'' ?>">
                        <div class="flex items-center gap-4">
                            <label class="text-xs text-gray-500 font-medium w-16">From</label>
                            <input type="color" class="color-linear-picker-1 w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5 bg-white" value="#1f2937">
                            <input type="text"  class="color-linear-1 w-28 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none font-mono" value="#1f2937">
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="text-xs text-gray-500 font-medium w-16">To</label>
                            <input type="color" class="color-linear-picker-2 w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5 bg-white" value="#374151">
                            <input type="text"  class="color-linear-2 w-28 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none font-mono" value="#374151">
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="text-xs text-gray-500 font-medium w-16">Direction</label>
                            <select class="color-linear-dir border border-gray-200 rounded-lg px-3 py-1.5 text-sm bg-white outline-none w-40">
                                <option value="to right">Left → Right</option>
                                <option value="to left">Right → Left</option>
                                <option value="to bottom" selected>Top → Bottom</option>
                                <option value="to top">Bottom → Top</option>
                            </select>
                        </div>
                    </div>
                    <div class="panel-radial space-y-3 <?= $bgType!=='radial'?'hidden':'' ?>">
                        <div class="flex items-center gap-4">
                            <label class="text-xs text-gray-500 font-medium w-16">Center</label>
                            <input type="color" class="color-radial-picker-1 w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5 bg-white" value="#374151">
                            <input type="text"  class="color-radial-1 w-28 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none font-mono" value="#374151">
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="text-xs text-gray-500 font-medium w-16">Edge</label>
                            <input type="color" class="color-radial-picker-2 w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5 bg-white" value="#1f2937">
                            <input type="text"  class="color-radial-2 w-28 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none font-mono" value="#1f2937">
                        </div>
                    </div>
                    <div class="panel-css <?= $bgType!=='css'?'hidden':'' ?>">
                        <div class="flex items-center gap-3">
                            <label class="text-xs text-gray-500 font-medium w-12">Value</label>
                            <input type="text" class="color-css-input w-full border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none focus:border-brown font-mono" value="<?= htmlspecialchars($bgType === 'css' ? $bgValue : '') ?>" placeholder="var(--primary-color) or rgba(0,0,0,0.5) or linear-gradient(...)">
                        </div>
                    </div>
                    <div class="border-t border-gray-200 pt-3">
                        <div class="h-10 rounded-lg border border-gray-200 theme-preview-box" style="background:<?= htmlspecialchars($bgValue) ?>;"></div>
                        <div class="text-[10px] text-gray-400 font-mono mt-1 theme-preview-text"><?= htmlspecialchars($bgValue) ?></div>
                    </div>
                </div>
            </div>
            <div class="space-y-4">
                <?php foreach (['text_color'=>['Text / Description Color',$textCol],'heading_color'=>['Heading Color',$headingCol],'link_color'=>['Link Color',$linkCol]] as $id=>[$lbl,$val]): ?>
                <div>
                    <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2"><?= $lbl ?></label>
                    <div class="flex items-center gap-4">
                        <input type="color" name="<?= $id ?>" id="<?= $id ?>_picker" value="<?= htmlspecialchars($val) ?>" class="w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5 bg-white">
                        <input type="text" id="<?= $id ?>_text" value="<?= htmlspecialchars($val) ?>" class="w-28 border border-gray-200 rounded-lg px-3 py-1.5 text-sm outline-none font-mono">
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- ── Contact Info ── -->
    <div class="mt-12 pt-4">
        <h3 class="text-xl font-serif font-bold text-[#0b3c7c] mb-6 border-b border-gray-100 pb-3">Contact Info</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-[10px] font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Email</label>
                <input type="email" name="contact_email" value="<?= htmlspecialchars($co['email']??'') ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm">
            </div>
            <div>
                <label class="block text-[10px] font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Phone</label>
                <input type="text" name="contact_phone" value="<?= htmlspecialchars($co['phone']??'') ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm">
            </div>
        </div>
    </div>

    <!-- ── Google Maps ── -->
    <div class="mt-12 pt-4">
        <h3 class="text-xl font-serif font-bold text-[#0b3c7c] mb-6 border-b border-gray-100 pb-3">Google Maps Integration</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-[10px] font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Google Maps Iframe URL</label>
                <input type="text" name="map_address" id="map_address" value="<?= htmlspecialchars($mp['address']??'') ?>" placeholder="https://maps.google.com/maps?q=..." class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm">
                <p class="text-xs text-gray-400 mt-1">Paste the Google Maps embed URL (Share → Embed a map → copy src)</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Latitude</label>
                    <input type="text" name="map_lat" value="<?= htmlspecialchars($mp['lat']??'') ?>" placeholder="12.9716" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Longitude</label>
                    <input type="text" name="map_lng" value="<?= htmlspecialchars($mp['lng']??'') ?>" placeholder="77.5946" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm">
                </div>
                <div class="col-span-2">
                    <label class="block text-[10px] font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Google Maps Link (Clickable)</label>
                    <input type="text" name="map_link" value="<?= htmlspecialchars($mp['map_link']??'') ?>" placeholder="https://goo.gl/maps/..." class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm">
                </div>
            </div>
        </div>
        <div id="mapPreviewBox" class="<?= !empty($mp['address']) ? '' : 'hidden' ?>">
            <label class="block text-[10px] font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">Map Preview</label>
            <div class="rounded-xl overflow-hidden border border-gray-200 h-48 bg-gray-100">
                <iframe id="mapIframe" src="<?= htmlspecialchars($mp['address']??'') ?>" class="w-full h-full border-0" loading="lazy" allowfullscreen></iframe>
            </div>
        </div>
    </div>

    <!-- ── Social Media ── -->
    <div class="mt-12 pt-4">
        <h3 class="text-xl font-serif font-bold text-[#0b3c7c] mb-6 border-b border-gray-100 pb-3">Social Media Links</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php
            $socials = ['facebook'=>'Facebook URL','instagram'=>'Instagram URL','youtube'=>'YouTube URL','twitter'=>'Twitter / X URL','pinterest'=>'Pinterest URL'];
            foreach ($socials as $key => $lbl): ?>
            <div>
                <label class="block text-[10px] font-serif font-bold text-gray-600 uppercase tracking-wider mb-1"><?= $lbl ?></label>
                <input type="url" name="social_<?= $key ?>" value="<?= htmlspecialchars($so[$key]??'') ?>" placeholder="https://..." class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm">
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ── Footer Columns ── -->
    <div class="mt-12 pt-4">
        <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-3">
            <h3 class="text-xl font-serif font-bold text-[#0b3c7c]">Footer Columns</h3>
            <button type="button" id="addColumnBtn" class="inline-flex items-center gap-2 text-gray-700 bg-white border border-gray-200 px-4 py-2 rounded-full font-bold text-xs shadow-sm hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Column
            </button>
        </div>
        <div id="columnsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($cols as $ci => $col): ?>
            <div class="column-block bg-white rounded-xl border border-gray-200 p-5 relative shadow-sm" data-ci="<?= $ci ?>">
                <button type="button" class="remove-column-btn absolute top-3 right-3 text-gray-300 hover:text-red-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div class="mb-4">
                    <label class="block text-[10px] font-serif font-bold text-gray-600 uppercase tracking-wider mb-1">Column Title</label>
                    <input type="text" name="col_title[]" value="<?= htmlspecialchars($col['title']??'') ?>" placeholder="e.g. About" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors bg-white">
                </div>
                <div class="links-container space-y-2 mb-3">
                    <?php foreach ($col['links']??[] as $li => $link): ?>
                    <div class="link-row flex items-center gap-2">
                        <input type="text" name="link_label[<?= $ci ?>][]" value="<?= htmlspecialchars($link['label']??'') ?>" placeholder="Label" class="flex-1 min-w-0 border border-gray-200 rounded-lg px-3 py-2 text-xs focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors bg-white">
                        <input type="text" name="link_url[<?= $ci ?>][]"   value="<?= htmlspecialchars($link['url']??'') ?>" placeholder="URL"   class="flex-1 min-w-0 border border-gray-200 rounded-lg px-3 py-2 text-xs focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors bg-white">
                        <button type="button" class="remove-link-btn text-gray-300 hover:text-red-500 transition-colors flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="add-link-btn text-[10px] font-bold text-gray-600 hover:text-gray-900 uppercase tracking-wider flex items-center gap-1 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Link
                </button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ── Bottom Actions ── -->
    <div class="flex items-center justify-between pt-6 border-t border-gray-100">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" class="w-5 h-5 text-[#0056b3] bg-gray-100 border-gray-300 rounded focus:ring-[#0056b3] focus:ring-2 cursor-pointer" style="accent-color:#0056b3;" <?= $footer['is_active'] ? 'checked' : '' ?>>
            <span class="text-sm font-bold text-[#0056b3]">Active (Visible on Site)</span>
        </label>
        <div class="flex items-center gap-3">
            <a href="<?= BASE_URL ?>/admin/cms/footers" class="px-6 py-2.5 border border-red-200 text-red-500 hover:bg-red-50 font-bold text-sm rounded-full transition-colors">CANCEL</a>
            <button type="submit" class="px-8 py-2.5 bg-gray-900 text-white font-bold text-sm rounded-full shadow-sm hover:bg-black transition-all">Save Footer</button>
        </div>
    </div>

</div>
</form>
</div>

<template id="columnTemplate">
    <div class="column-block bg-white rounded-xl border border-gray-200 p-5 relative shadow-sm">
        <button type="button" class="remove-column-btn absolute top-3 right-3 text-gray-300 hover:text-red-500 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div class="mb-4">
            <label class="block text-[10px] font-serif font-bold text-gray-600 uppercase tracking-wider mb-1">Column Title</label>
            <input type="text" name="col_title[]" placeholder="e.g. About" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors bg-white">
        </div>
        <div class="links-container space-y-2 mb-3"></div>
        <button type="button" class="add-link-btn text-[10px] font-bold text-gray-600 hover:text-gray-900 uppercase tracking-wider flex items-center gap-1 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Link
        </button>
    </div>
</template>

<?php require_once __DIR__ . '/../../media/_selector_modal.php'; ?>

<script>
(function(){
// ── existing columns: wire up remove buttons ──
document.querySelectorAll('.column-block').forEach(block => {
    block.querySelector('.remove-column-btn').addEventListener('click', () => block.remove());
    block.querySelectorAll('.remove-link-btn').forEach(btn => btn.addEventListener('click', () => btn.closest('.link-row').remove()));
    const ci = parseInt(block.dataset.ci);
    block.querySelector('.add-link-btn').addEventListener('click', () => addLinkToBlock(block, ci));
});

// ── Color Theme ──
const typeHidden  = document.getElementById('theme_type');
const valueHidden = document.getElementById('theme_value');
function updatePreview(bg) {
    const preview = document.querySelector('.theme-preview-box');
    if (/\bbackground(?:-color|-image)?\s*:/i.test(bg)) {
        preview.setAttribute('style', bg + (bg.endsWith(';') ? '' : ';') + 'border-radius:0.5rem;border:1px solid #e5e7eb;');
    } else {
        preview.style.background = bg;
    }
    document.querySelector('.theme-preview-text').textContent = bg;
    valueHidden.value = bg;
}

function syncCssValue() {
    const cssInput = document.querySelector('.color-css-input');
    if (!cssInput) return;
    updatePreview(cssInput.value.trim() || '#1f2937');
}

document.querySelectorAll('.color-type-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.color-type-btn').forEach(b => { b.classList.remove('active','bg-gray-900','text-white'); b.classList.add('bg-white','text-gray-600'); });
        this.classList.add('active','bg-gray-900','text-white'); this.classList.remove('bg-white','text-gray-600');
        const t = this.dataset.type; typeHidden.value = t;
        document.querySelector('.panel-solid').classList.toggle('hidden', t !== 'solid');
        document.querySelector('.panel-linear').classList.toggle('hidden', t !== 'linear');
        document.querySelector('.panel-radial').classList.toggle('hidden', t !== 'radial');
        document.querySelector('.panel-css').classList.toggle('hidden', t !== 'css');
        syncAll();
    });
});

function syncSolid(from) { const p=document.querySelector('.color-solid-picker'),i=document.querySelector('.color-solid-input'); if(from==='picker')i.value=p.value; else p.value=i.value; updatePreview(i.value); }
document.querySelector('.color-solid-picker').addEventListener('input',()=>syncSolid('picker'));
document.querySelector('.color-solid-input').addEventListener('input',()=>syncSolid('input'));

function syncLinear(){const c1=document.querySelector('.color-linear-1').value,c2=document.querySelector('.color-linear-2').value,d=document.querySelector('.color-linear-dir').value; updatePreview(`linear-gradient(${d},${c1},${c2})`);}
document.querySelector('.color-linear-picker-1').addEventListener('input',e=>{document.querySelector('.color-linear-1').value=e.target.value;syncLinear();});
document.querySelector('.color-linear-1').addEventListener('input',e=>{document.querySelector('.color-linear-picker-1').value=e.target.value;syncLinear();});
document.querySelector('.color-linear-picker-2').addEventListener('input',e=>{document.querySelector('.color-linear-2').value=e.target.value;syncLinear();});
document.querySelector('.color-linear-2').addEventListener('input',e=>{document.querySelector('.color-linear-picker-2').value=e.target.value;syncLinear();});
document.querySelector('.color-linear-dir').addEventListener('change',syncLinear);
function syncRadial(){const c1=document.querySelector('.color-radial-1').value,c2=document.querySelector('.color-radial-2').value; updatePreview(`radial-gradient(circle,${c1},${c2})`);}
document.querySelector('.color-radial-picker-1').addEventListener('input',e=>{document.querySelector('.color-radial-1').value=e.target.value;syncRadial();});
document.querySelector('.color-radial-1').addEventListener('input',e=>{document.querySelector('.color-radial-picker-1').value=e.target.value;syncRadial();});
document.querySelector('.color-radial-picker-2').addEventListener('input',e=>{document.querySelector('.color-radial-2').value=e.target.value;syncRadial();});
document.querySelector('.color-radial-2').addEventListener('input',e=>{document.querySelector('.color-radial-picker-2').value=e.target.value;syncRadial();});
function syncAll(){const t=typeHidden.value; if(t==='solid')syncSolid('input'); else if(t==='linear')syncLinear(); else syncRadial();}

document.querySelector('.color-css-input')?.addEventListener('input', syncCssValue);

syncAll();

['text_color','heading_color','link_color'].forEach(id=>{
    const picker=document.getElementById(id+'_picker'),text=document.getElementById(id+'_text');
    if(!picker||!text)return;
    picker.addEventListener('input',()=>{picker.name=id;text.value=picker.value;});
    text.addEventListener('input',()=>{picker.value=text.value;});
});

// ── Logo ──
const logoArea=document.getElementById('logoUploadArea'),logoInput=document.getElementById('logoInput'),logoPreview=document.getElementById('logoPreview'),logoPlaceholder=document.getElementById('logoPlaceholder'),logoRemoveBtn=document.getElementById('logoRemoveBtn');
logoArea.addEventListener('click',function(e){
    if(e.target===logoRemoveBtn||logoRemoveBtn.contains(e.target))return;
    if (typeof openMediaSelector === 'function') {
        openMediaSelector(url => {
            if (url) {
                logoInput.value=url;logoPreview.src=url;
                logoPreview.classList.remove('hidden');
                logoPlaceholder.classList.add('hidden');
                logoRemoveBtn.classList.remove('hidden');
            }
        });
    }
});
logoRemoveBtn.addEventListener('click',e=>{e.stopPropagation();logoInput.value='';logoPreview.src='';logoPreview.classList.add('hidden');logoPlaceholder.classList.remove('hidden');logoRemoveBtn.classList.add('hidden');});

// ── Map Preview ──
const mapInput=document.getElementById('map_address'),mapBox=document.getElementById('mapPreviewBox'),mapIf=document.getElementById('mapIframe');
const mapLat=document.querySelector('input[name="map_lat"]'), mapLng=document.querySelector('input[name="map_lng"]');
let mapTimer;
function updateMapPreview(){
    clearTimeout(mapTimer);
    mapTimer=setTimeout(()=>{
        let url=mapInput.value.trim();
        const lat=mapLat?mapLat.value.trim():'';
        const lng=mapLng?mapLng.value.trim():'';
        
        if(!url && lat && lng) {
            url=`https://maps.google.com/maps?q=${lat},${lng}&t=&z=15&ie=UTF8&iwloc=&output=embed`;
            mapInput.value=url;
        }
        
        if(url.startsWith('http')){
            if(!url.includes('output=embed') && url.includes('/maps?q=')) url += '&output=embed';
            mapIf.src=url;
            mapBox.classList.remove('hidden');
        }else{
            mapBox.classList.add('hidden');
        }
    },800);
}
mapInput.addEventListener('input',updateMapPreview);
if(mapLat) mapLat.addEventListener('input',updateMapPreview);
if(mapLng) mapLng.addEventListener('input',updateMapPreview);


// ── Columns ──
const columnsContainer=document.getElementById('columnsContainer');
const colTmpl=document.getElementById('columnTemplate');
let colIndex = <?= count($cols) ?>;

function addLinkToBlock(block, ci) {
    const row = document.createElement('div'); row.className='link-row flex items-center gap-2';
    row.innerHTML = `<input type="text" name="link_label[${ci}][]" placeholder="Label" class="flex-1 min-w-0 border border-gray-200 rounded-lg px-3 py-2 text-xs focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors bg-white"><input type="text" name="link_url[${ci}][]" placeholder="URL" class="flex-1 min-w-0 border border-gray-200 rounded-lg px-3 py-2 text-xs focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors bg-white"><button type="button" class="remove-link-btn text-gray-300 hover:text-red-500 transition-colors flex-shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>`;
    row.querySelector('.remove-link-btn').addEventListener('click',()=>row.remove());
    block.querySelector('.links-container').appendChild(row);
}

document.getElementById('addColumnBtn').addEventListener('click',function(){
    const ci=colIndex++;
    const clone=colTmpl.content.cloneNode(true);
    columnsContainer.appendChild(clone);
    const block=columnsContainer.lastElementChild;
    block.querySelector('.remove-column-btn').addEventListener('click',()=>block.remove());
    block.querySelector('.add-link-btn').addEventListener('click',()=>addLinkToBlock(block,ci));
    addLinkToBlock(block,ci);
});
})();
</script>
