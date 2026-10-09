<?php
$c = $aboutUs['content'];
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600;700&display=swap');
.font-serif { font-family: 'Playfair Display', serif; }
.font-sans  { font-family: 'Inter', sans-serif; }
.bg-brown   { background-color: #8C6A4F; }
.border-brown { border-color: #8C6A4F; }
.text-brown { color: #8C6A4F; }
.focus\:border-brown:focus { border-color: #8C6A4F; }
.focus\:ring-brown:focus { --tw-ring-color: #8C6A4F; }
</style>

<style>
/* Color Link UI */
.color-field-item { display:flex; flex-direction:column; gap:6px; min-width:180px; }
.color-field-item label { font-size:12px; font-family:'Playfair Display',serif; font-weight:700; color:#4b5563; letter-spacing:.08em; text-transform:uppercase; margin-bottom:2px; }
.color-link-group { display:flex; align-items:center; gap:6px; border:1px solid #e5e7eb; border-radius:10px; padding:6px 8px; background:#fff; }
.color-link-group input[type=color] { width:32px; height:32px; border-radius:6px; border:2px solid #e5e7eb; cursor:pointer; padding:2px; background:#fff; flex-shrink:0; }
.color-link-hex { flex:1; min-width:0; border:none; font-size:12px; font-family:monospace; text-transform:uppercase; outline:none; background:transparent; color:#374151; }
.color-link-preview { width:28px; height:28px; border-radius:6px; border:1px solid #e5e7eb; flex-shrink:0; }
.theme-colors-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:20px; }
</style>

<div class="max-w-[95rem] mx-auto py-8 px-4 sm:px-6 font-sans">
    <form action="<?= BASE_URL ?>/admin/cms/about-us/update" method="POST" id="aboutUsForm">
        <input type="hidden" name="id" value="<?= htmlspecialchars($aboutUs['id']) ?>">
        
        <!-- Main Container -->
        <div class="bg-white rounded-[1.5rem] shadow-custom border border-gray-100 p-8 space-y-10">
            
            <div class="border-b border-gray-100 pb-4 mb-8">
                <h2 class="text-2xl font-serif text-[#0b3c7c]">Edit About Us Component</h2>
            </div>

            <!-- General Settings -->
            <div class="mt-8">
                <h3 class="text-xl font-serif font-bold text-gray-800 mb-6 border-b border-gray-100 pb-3">General Settings</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Internal Name *</label>
                        <input type="text" name="internal_name" required value="<?= htmlspecialchars($c['internal_name'] ?? '') ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors" placeholder="e.g., Default About Us">
                    </div>
                </div>
            </div>

            <!-- Row 1: Hero Section -->
            <div class="mt-12 pt-4">
                <h3 class="text-xl font-serif font-bold text-gray-800 mb-6 border-b border-gray-100 pb-3">Row 1: Hero Section</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Title</label>
                            <input type="text" name="hero_title" value="<?= htmlspecialchars($c['hero']['title'] ?? '') ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">SubTitle</label>
                            <input type="text" name="hero_subtitle" value="<?= htmlspecialchars($c['hero']['subtitle'] ?? '') ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Description</label>
                            <textarea name="hero_description" rows="3" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm"><?= htmlspecialchars($c['hero']['description'] ?? '') ?></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">CTA Text</label>
                                <input type="text" name="hero_cta_text" value="<?= htmlspecialchars($c['hero']['cta_text'] ?? '') ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">CTA URL</label>
                                <input type="text" name="hero_cta_url" value="<?= htmlspecialchars($c['hero']['cta_url'] ?? '') ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm">
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Hero Image</label>
                            <div class="flex items-center gap-4">
                                <div class="w-24 h-24 border-2 border-dashed border-gray-200 rounded-xl flex items-center justify-center relative bg-gray-50 hover:bg-gray-100 cursor-pointer overflow-hidden group" onclick="openMediaManager('hero_image')" id="heroUploadArea">
                                    <input type="hidden" name="hero_image" id="hero_image" value="<?= htmlspecialchars($c['hero']['image'] ?? '') ?>">
                                    <div id="heroPlaceholder" class="text-center text-gray-400 <?= !empty($c['hero']['image']) ? 'hidden' : '' ?>">
                                        <svg class="w-7 h-7 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        <span class="text-xs font-medium">Select</span>
                                    </div>
                                    <img id="heroPreview" src="<?= htmlspecialchars($c['hero']['image'] ?? '') ?>" class="absolute inset-0 w-full h-full object-cover <?= empty($c['hero']['image']) ? 'hidden' : '' ?>">
                                </div>
                                <p class="text-xs text-gray-400">Click to select from Media Manager</p>
                            </div>
                        </div>
                        
                        <div class="border border-gray-200 rounded-xl p-4 bg-gray-50 space-y-4">
                            <h4 class="font-bold text-gray-700 text-sm mb-3">Theme Colors (Hero)</h4>
                            <div class="theme-colors-grid">
                                <div class="color-field-item">
                                    <label>Background</label>
                                    <div class="color-link-group">
                                        <input type="color" name="theme_bg_color" id="clr_theme_bg_color" value="<?= htmlspecialchars($c['theme']['bg_color'] ?? '#f9fafb') ?>" oninput="syncColorLink(this)">
                                        <input type="text" class="color-link-hex" value="<?= htmlspecialchars($c['theme']['bg_color'] ?? '#f9fafb') ?>" oninput="syncColorHex(this,'clr_theme_bg_color')" placeholder="#f9fafb">
                                        <span class="color-link-preview" id="prev_theme_bg_color" style="background:<?= htmlspecialchars($c['theme']['bg_color'] ?? '#f9fafb') ?>;"></span>
                                    </div>
                                </div>
                                <div class="color-field-item">
                                    <label>Title Text</label>
                                    <div class="color-link-group">
                                        <input type="color" name="theme_hero_text_color" id="clr_theme_hero_text_color" value="<?= htmlspecialchars($c['theme']['hero_text_color'] ?? '#111827') ?>" oninput="syncColorLink(this)">
                                        <input type="text" class="color-link-hex" value="<?= htmlspecialchars($c['theme']['hero_text_color'] ?? '#111827') ?>" oninput="syncColorHex(this,'clr_theme_hero_text_color')" placeholder="#111827">
                                        <span class="color-link-preview" id="prev_theme_hero_text_color" style="background:<?= htmlspecialchars($c['theme']['hero_text_color'] ?? '#111827') ?>;"></span>
                                    </div>
                                </div>
                                <div class="color-field-item">
                                    <label>Description</label>
                                    <div class="color-link-group">
                                        <input type="color" name="theme_hero_desc_color" id="clr_theme_hero_desc_color" value="<?= htmlspecialchars($c['theme']['hero_desc_color'] ?? '#4b5563') ?>" oninput="syncColorLink(this)">
                                        <input type="text" class="color-link-hex" value="<?= htmlspecialchars($c['theme']['hero_desc_color'] ?? '#4b5563') ?>" oninput="syncColorHex(this,'clr_theme_hero_desc_color')" placeholder="#4b5563">
                                        <span class="color-link-preview" id="prev_theme_hero_desc_color" style="background:<?= htmlspecialchars($c['theme']['hero_desc_color'] ?? '#4b5563') ?>;"></span>
                                    </div>
                                </div>
                                <div class="color-field-item">
                                    <label>Button BG</label>
                                    <div class="color-link-group">
                                        <input type="color" name="theme_btn_bg_color" id="clr_theme_btn_bg_color" value="<?= htmlspecialchars($c['theme']['btn_bg_color'] ?? '#059669') ?>" oninput="syncColorLink(this)">
                                        <input type="text" class="color-link-hex" value="<?= htmlspecialchars($c['theme']['btn_bg_color'] ?? '#059669') ?>" oninput="syncColorHex(this,'clr_theme_btn_bg_color')" placeholder="#059669">
                                        <span class="color-link-preview" id="prev_theme_btn_bg_color" style="background:<?= htmlspecialchars($c['theme']['btn_bg_color'] ?? '#059669') ?>;"></span>
                                    </div>
                                </div>
                                <div class="color-field-item">
                                    <label>Button Text</label>
                                    <div class="color-link-group">
                                        <input type="color" name="theme_btn_text_color" id="clr_theme_btn_text_color" value="<?= htmlspecialchars($c['theme']['btn_text_color'] ?? '#ffffff') ?>" oninput="syncColorLink(this)">
                                        <input type="text" class="color-link-hex" value="<?= htmlspecialchars($c['theme']['btn_text_color'] ?? '#ffffff') ?>" oninput="syncColorHex(this,'clr_theme_btn_text_color')" placeholder="#ffffff">
                                        <span class="color-link-preview" id="prev_theme_btn_text_color" style="background:<?= htmlspecialchars($c['theme']['btn_text_color'] ?? '#ffffff') ?>;"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 2: Features Section -->
            <div class="mt-12 pt-4">
                <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-3">
                    <h3 class="text-xl font-serif font-bold text-gray-800">Row 2: Features</h3>
                    <button type="button" onclick="addFeatureColumn()" class="inline-flex items-center gap-2 text-gray-700 bg-white border border-gray-200 px-4 py-2 rounded-full font-bold text-xs shadow-sm hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Column
                    </button>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Section Title (Optional)</label>
                    <input type="text" name="features_heading" value="<?= htmlspecialchars($c['features_heading'] ?? $c['features_title'] ?? '') ?>" placeholder="e.g. Our Core Features" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm">
                </div>
                
                <div class="border border-gray-200 rounded-xl p-4 bg-gray-50 mb-6">
                    <h4 class="font-bold text-gray-700 text-sm mb-3">Theme Colors (Features)</h4>
                    <div class="theme-colors-grid">
                        <div class="color-field-item">
                            <label>Background</label>
                            <div class="color-link-group">
                                <input type="color" name="theme_feature_bg_color" id="clr_theme_feature_bg_color" value="<?= htmlspecialchars($c['theme']['feature_bg_color'] ?? '#ffffff') ?>" oninput="syncColorLink(this)">
                                <input type="text" class="color-link-hex" value="<?= htmlspecialchars($c['theme']['feature_bg_color'] ?? '#ffffff') ?>" oninput="syncColorHex(this,'clr_theme_feature_bg_color')" placeholder="#ffffff">
                                <span class="color-link-preview" id="prev_theme_feature_bg_color" style="background:<?= htmlspecialchars($c['theme']['feature_bg_color'] ?? '#ffffff') ?>;"></span>
                            </div>
                        </div>
                        <div class="color-field-item">
                            <label>Icon Color</label>
                            <div class="color-link-group">
                                <input type="color" name="theme_feature_icon_color" id="clr_theme_feature_icon_color" value="<?= htmlspecialchars($c['theme']['feature_icon_color'] ?? '#059669') ?>" oninput="syncColorLink(this)">
                                <input type="text" class="color-link-hex" value="<?= htmlspecialchars($c['theme']['feature_icon_color'] ?? '#059669') ?>" oninput="syncColorHex(this,'clr_theme_feature_icon_color')" placeholder="#059669">
                                <span class="color-link-preview" id="prev_theme_feature_icon_color" style="background:<?= htmlspecialchars($c['theme']['feature_icon_color'] ?? '#059669') ?>;"></span>
                            </div>
                        </div>
                        <div class="color-field-item">
                            <label>Text Color</label>
                            <div class="color-link-group">
                                <input type="color" name="theme_feature_text_color" id="clr_theme_feature_text_color" value="<?= htmlspecialchars($c['theme']['feature_text_color'] ?? '#111827') ?>" oninput="syncColorLink(this)">
                                <input type="text" class="color-link-hex" value="<?= htmlspecialchars($c['theme']['feature_text_color'] ?? '#111827') ?>" oninput="syncColorHex(this,'clr_theme_feature_text_color')" placeholder="#111827">
                                <span class="color-link-preview" id="prev_theme_feature_text_color" style="background:<?= htmlspecialchars($c['theme']['feature_text_color'] ?? '#111827') ?>;"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="featureContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php 
                    $features = $c['features'] ?? [];
                    if (empty($features)) {
                        $features = [
                            ['title' => '', 'icon' => '', 'description' => '', 'bg_color' => '#ffffff', 'text_color' => '#111827'],
                            ['title' => '', 'icon' => '', 'description' => '', 'bg_color' => '#ffffff', 'text_color' => '#111827'],
                            ['title' => '', 'icon' => '', 'description' => '', 'bg_color' => '#ffffff', 'text_color' => '#111827']
                        ];
                    }
                    foreach ($features as $idx => $f): 
                    ?>
                    <div class="feature-col border border-gray-200 rounded-xl p-5 bg-white shadow-sm relative">
                        <button type="button" onclick="this.closest('.feature-col').remove()" class="absolute top-3 right-3 text-gray-300 hover:text-red-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        <div class="space-y-4 mt-2">
                            <div>
                                <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">SVG Icon Code</label>
                                <textarea name="feature_icon[]" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-xs focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm font-mono text-gray-600"><?= htmlspecialchars($f['icon'] ?? '') ?></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Title</label>
                                <input type="text" name="feature_title[]" value="<?= htmlspecialchars($f['title'] ?? '') ?>" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Description</label>
                                <textarea name="feature_description[]" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm"><?= htmlspecialchars($f['description'] ?? '') ?></textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mt-2 border-t border-gray-100 pt-3">
                                <div>
                                    <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Card BG Color</label>
                                    <div class="color-link-group">
                                        <input type="color" name="feature_bg_color[]" value="<?= htmlspecialchars($f['bg_color'] ?? '#ffffff') ?>" oninput="syncColorLink(this)">
                                        <input type="text" class="color-link-hex" value="<?= htmlspecialchars($f['bg_color'] ?? '#ffffff') ?>" oninput="syncColorHexAnon(this)" placeholder="#ffffff">
                                        <span class="color-link-preview" style="background:<?= htmlspecialchars($f['bg_color'] ?? '#ffffff') ?>;"></span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Card Text Color</label>
                                    <div class="color-link-group">
                                        <input type="color" name="feature_text_color[]" value="<?= htmlspecialchars($f['text_color'] ?? '#111827') ?>" oninput="syncColorLink(this)">
                                        <input type="text" class="color-link-hex" value="<?= htmlspecialchars($f['text_color'] ?? '#111827') ?>" oninput="syncColorHexAnon(this)" placeholder="#111827">
                                        <span class="color-link-preview" style="background:<?= htmlspecialchars($f['text_color'] ?? '#111827') ?>;"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Row 3: Why Choose Section -->
            <div class="mt-12 pt-4">
                <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-3">
                    <h3 class="text-xl font-serif font-bold text-gray-800">Row 3: Why Choose Us</h3>
                    <button type="button" onclick="addWhyChooseColumn()" class="inline-flex items-center gap-2 text-gray-700 bg-white border border-gray-200 px-4 py-2 rounded-full font-bold text-xs shadow-sm hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Column
                    </button>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Section Title</label>
                    <input type="text" name="why_choose_heading" value="<?= htmlspecialchars($c['why_choose_heading'] ?? $c['why_choose_title'] ?? 'Why Choose Us') ?>" placeholder="Why Choose Us" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm">
                </div>

                <div class="border border-gray-200 rounded-xl p-4 bg-gray-50 mb-6">
                    <h4 class="font-bold text-gray-700 text-sm mb-3">Theme Colors (Why Choose)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="flex items-center gap-3">
                            <label class="text-xs font-serif font-bold text-gray-500 uppercase tracking-wider w-24">Background</label>
                            <div class="color-link-group flex-1">
                                <input type="color" name="theme_why_choose_bg_color" id="clr_theme_why_choose_bg_color" value="<?= htmlspecialchars($c['theme']['why_choose_bg_color'] ?? '#ffffff') ?>" oninput="syncColorLink(this)">
                                <input type="text" class="color-link-hex" value="<?= htmlspecialchars($c['theme']['why_choose_bg_color'] ?? '#ffffff') ?>" oninput="syncColorHex(this,'clr_theme_why_choose_bg_color')" placeholder="#ffffff">
                                <span class="color-link-preview" id="prev_theme_why_choose_bg_color" style="background:<?= htmlspecialchars($c['theme']['why_choose_bg_color'] ?? '#ffffff') ?>;"></span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <label class="text-xs font-serif font-bold text-gray-500 uppercase tracking-wider w-24">Icon Color</label>
                            <div class="color-link-group flex-1">
                                <input type="color" name="theme_why_choose_icon_color" id="clr_theme_why_choose_icon_color" value="<?= htmlspecialchars($c['theme']['why_choose_icon_color'] ?? '#059669') ?>" oninput="syncColorLink(this)">
                                <input type="text" class="color-link-hex" value="<?= htmlspecialchars($c['theme']['why_choose_icon_color'] ?? '#059669') ?>" oninput="syncColorHex(this,'clr_theme_why_choose_icon_color')" placeholder="#059669">
                                <span class="color-link-preview" id="prev_theme_why_choose_icon_color" style="background:<?= htmlspecialchars($c['theme']['why_choose_icon_color'] ?? '#059669') ?>;"></span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <label class="text-xs font-serif font-bold text-gray-500 uppercase tracking-wider w-24">Text Color</label>
                            <div class="color-link-group flex-1">
                                <input type="color" name="theme_why_choose_text_color" id="clr_theme_why_choose_text_color" value="<?= htmlspecialchars($c['theme']['why_choose_text_color'] ?? '#111827') ?>" oninput="syncColorLink(this)">
                                <input type="text" class="color-link-hex" value="<?= htmlspecialchars($c['theme']['why_choose_text_color'] ?? '#111827') ?>" oninput="syncColorHex(this,'clr_theme_why_choose_text_color')" placeholder="#111827">
                                <span class="color-link-preview" id="prev_theme_why_choose_text_color" style="background:<?= htmlspecialchars($c['theme']['why_choose_text_color'] ?? '#111827') ?>;"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="whyChooseContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php 
                    $why_choose = $c['why_choose'] ?? [];
                    if(empty($why_choose)) {
                        $why_choose = [
                            ['title' => '', 'icon' => '', 'description' => '', 'bg_color' => '#ffffff', 'text_color' => '#111827'],
                            ['title' => '', 'icon' => '', 'description' => '', 'bg_color' => '#ffffff', 'text_color' => '#111827'],
                            ['title' => '', 'icon' => '', 'description' => '', 'bg_color' => '#ffffff', 'text_color' => '#111827']
                        ];
                    }
                    foreach ($why_choose as $idx => $w):
                    ?>
                    <div class="why-choose-col border border-gray-200 rounded-xl p-5 bg-white shadow-sm relative">
                        <button type="button" onclick="this.closest('.why-choose-col').remove()" class="absolute top-3 right-3 text-gray-300 hover:text-red-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        <div class="space-y-4 mt-2">
                            <div>
                                <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">SVG Icon Code</label>
                                <textarea name="why_choose_icon[]" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-xs focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm font-mono text-gray-600"><?= htmlspecialchars($w['icon'] ?? '') ?></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Title</label>
                                <input type="text" name="why_choose_title[]" value="<?= htmlspecialchars($w['title'] ?? '') ?>" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Description</label>
                                <textarea name="why_choose_description[]" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm"><?= htmlspecialchars($w['description'] ?? '') ?></textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mt-2 border-t border-gray-100 pt-3">
                                <div>
                                    <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Card BG Color</label>
                                    <div class="color-link-group">
                                        <input type="color" name="why_choose_bg_color[]" value="<?= htmlspecialchars($w['bg_color'] ?? '#ffffff') ?>" oninput="syncColorLink(this)">
                                        <input type="text" class="color-link-hex" value="<?= htmlspecialchars($w['bg_color'] ?? '#ffffff') ?>" oninput="syncColorHexAnon(this)" placeholder="#ffffff">
                                        <span class="color-link-preview" style="background:<?= htmlspecialchars($w['bg_color'] ?? '#ffffff') ?>;"></span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Card Text Color</label>
                                    <div class="color-link-group">
                                        <input type="color" name="why_choose_text_color[]" value="<?= htmlspecialchars($w['text_color'] ?? '#111827') ?>" oninput="syncColorLink(this)">
                                        <input type="text" class="color-link-hex" value="<?= htmlspecialchars($w['text_color'] ?? '#111827') ?>" oninput="syncColorHexAnon(this)" placeholder="#111827">
                                        <span class="color-link-preview" style="background:<?= htmlspecialchars($w['text_color'] ?? '#111827') ?>;"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Row 4: FAQ Section -->
            <div class="mt-12 pt-4">
                <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-3">
                    <h3 class="text-xl font-serif font-bold text-gray-800">Row 4: FAQs</h3>
                    <button type="button" onclick="addFaqRow()" class="inline-flex items-center gap-2 text-gray-700 bg-white border border-gray-200 px-4 py-2 rounded-full font-bold text-xs shadow-sm hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add FAQ
                    </button>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Section Title</label>
                    <input type="text" name="faq_heading" value="<?= htmlspecialchars($c['faq_heading'] ?? $c['faq_title'] ?? 'Frequently Asked Questions') ?>" placeholder="Frequently Asked Questions" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm">
                </div>

                <div class="border border-gray-200 rounded-xl p-4 bg-gray-50 mb-6">
                    <h4 class="font-bold text-gray-700 text-sm mb-3">Theme Colors (FAQs)</h4>
                    <div class="theme-colors-grid">
                        <div class="color-field-item">
                            <label>Background</label>
                            <div class="color-link-group">
                                <input type="color" name="theme_faq_bg_color" id="clr_theme_faq_bg_color" value="<?= htmlspecialchars($c['theme']['faq_bg_color'] ?? '#ffffff') ?>" oninput="syncColorLink(this)">
                                <input type="text" class="color-link-hex" value="<?= htmlspecialchars($c['theme']['faq_bg_color'] ?? '#ffffff') ?>" oninput="syncColorHex(this,'clr_theme_faq_bg_color')" placeholder="#ffffff">
                                <span class="color-link-preview" id="prev_theme_faq_bg_color" style="background:<?= htmlspecialchars($c['theme']['faq_bg_color'] ?? '#ffffff') ?>;"></span>
                            </div>
                        </div>
                        <div class="color-field-item">
                            <label>Section Title Color</label>
                            <div class="color-link-group">
                                <input type="color" name="theme_faq_text_color" id="clr_theme_faq_text_color" value="<?= htmlspecialchars($c['theme']['faq_text_color'] ?? '#111827') ?>" oninput="syncColorLink(this)">
                                <input type="text" class="color-link-hex" value="<?= htmlspecialchars($c['theme']['faq_text_color'] ?? '#111827') ?>" oninput="syncColorHex(this,'clr_theme_faq_text_color')" placeholder="#111827">
                                <span class="color-link-preview" id="prev_theme_faq_text_color" style="background:<?= htmlspecialchars($c['theme']['faq_text_color'] ?? '#111827') ?>;"></span>
                            </div>
                        </div>
                        <div class="color-field-item">
                            <label>Question Color</label>
                            <div class="color-link-group">
                                <input type="color" name="theme_faq_question_color" id="clr_theme_faq_question_color" value="<?= htmlspecialchars($c['theme']['faq_question_color'] ?? $c['theme']['faq_text_color'] ?? '#111827') ?>" oninput="syncColorLink(this)">
                                <input type="text" class="color-link-hex" value="<?= htmlspecialchars($c['theme']['faq_question_color'] ?? $c['theme']['faq_text_color'] ?? '#111827') ?>" oninput="syncColorHex(this,'clr_theme_faq_question_color')" placeholder="#111827">
                                <span class="color-link-preview" id="prev_theme_faq_question_color" style="background:<?= htmlspecialchars($c['theme']['faq_question_color'] ?? $c['theme']['faq_text_color'] ?? '#111827') ?>;"></span>
                            </div>
                        </div>
                        <div class="color-field-item">
                            <label>Answer Color</label>
                            <div class="color-link-group">
                                <input type="color" name="theme_faq_answer_color" id="clr_theme_faq_answer_color" value="<?= htmlspecialchars($c['theme']['faq_answer_color'] ?? '#4b5563') ?>" oninput="syncColorLink(this)">
                                <input type="text" class="color-link-hex" value="<?= htmlspecialchars($c['theme']['faq_answer_color'] ?? '#4b5563') ?>" oninput="syncColorHex(this,'clr_theme_faq_answer_color')" placeholder="#4b5563">
                                <span class="color-link-preview" id="prev_theme_faq_answer_color" style="background:<?= htmlspecialchars($c['theme']['faq_answer_color'] ?? '#4b5563') ?>;"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="faqContainer" class="space-y-4">
                    <?php 
                    $faqs = $c['faqs'] ?? [];
                    if(empty($faqs)) {
                        $faqs = [['question' => '', 'answer' => '']];
                    }
                    foreach ($faqs as $faq):
                    ?>
                    <div class="flex gap-4 items-start faq-row bg-white border border-gray-200 p-4 rounded-xl shadow-sm relative">
                        <button type="button" onclick="this.closest('.faq-row').remove()" class="absolute top-3 right-3 text-gray-300 hover:text-red-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        <div class="flex-1 space-y-3 pr-8">
                            <div>
                                <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Question</label>
                                <input type="text" name="faq_question[]" value="<?= htmlspecialchars($faq['question'] ?? '') ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm font-medium">
                            </div>
                            <div>
                                <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Answer</label>
                                <textarea name="faq_answer[]" rows="2" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm"><?= htmlspecialchars($faq['answer'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <!-- Bottom Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="w-5 h-5 text-[#0056b3] bg-gray-100 border-gray-300 rounded focus:ring-[#0056b3] focus:ring-2 cursor-pointer" style="accent-color:#0056b3;" <?= $aboutUs['is_active'] ? 'checked' : '' ?>>
                    <span class="text-sm font-bold text-[#0056b3]">Active (Visible on Site)</span>
                </label>
                <div class="flex items-center gap-3">
                    <a href="<?= BASE_URL ?>/admin/cms/about-us" class="px-6 py-2.5 border border-red-200 text-red-500 hover:bg-red-50 font-bold text-sm rounded-full transition-colors">CANCEL</a>
                    <button type="submit" class="px-8 py-2.5 bg-gray-900 text-white font-bold text-sm rounded-full shadow-sm hover:bg-black transition-all">Update About Us Component</button>
                </div>
            </div>

        </div>
    </form>
</div>

<?php require_once BASE_PATH . '/app/Views/admin/media/_selector_modal.php'; ?>

<script>
function openMediaManager(inputId) {
    if (typeof openMediaSelector === 'function') {
        openMediaSelector(url => {
            if (url) {
                document.getElementById(inputId).value = url;
                const preview = document.getElementById(inputId.replace('_image', 'Preview'));
                const placeholder = document.getElementById(inputId.replace('_image', 'Placeholder'));
                if (preview && placeholder) {
                    preview.src = url;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
            }
        });
    }
}

function addFaqRow() {
    const container = document.getElementById('faqContainer');
    const row = document.createElement('div');
    row.className = 'flex gap-4 items-start faq-row bg-white border border-gray-200 p-4 rounded-xl shadow-sm relative mt-4';
    row.innerHTML = `
        <button type="button" onclick="this.closest('.faq-row').remove()" class="absolute top-3 right-3 text-gray-300 hover:text-red-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div class="flex-1 space-y-3 pr-8">
            <div>
                <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Question</label>
                <input type="text" name="faq_question[]" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm font-medium">
            </div>
            <div>
                <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Answer</label>
                <textarea name="faq_answer[]" rows="2" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm"></textarea>
            </div>
        </div>
    `;
    container.appendChild(row);
}

function addFeatureColumn() {
    const container = document.getElementById('featureContainer');
    const col = document.createElement('div');
    col.className = 'feature-col border border-gray-200 rounded-xl p-5 bg-white shadow-sm relative';
    col.innerHTML = `
        <button type="button" onclick="this.closest('.feature-col').remove()" class="absolute top-3 right-3 text-gray-300 hover:text-red-500 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div class="space-y-4 mt-2">
            <div>
                <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">SVG Icon Code</label>
                <textarea name="feature_icon[]" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-xs focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm font-mono text-gray-600"></textarea>
            </div>
            <div>
                <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Title</label>
                <input type="text" name="feature_title[]" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm">
            </div>
            <div>
                <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Description</label>
                <textarea name="feature_description[]" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4 mt-2 border-t border-gray-100 pt-3">
                <div>
                    <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Card BG Color</label>
                    <div class="color-link-group">
                        <input type="color" name="feature_bg_color[]" value="#ffffff" oninput="syncColorLink(this)">
                        <input type="text" class="color-link-hex" value="#ffffff" oninput="syncColorHexAnon(this)" placeholder="#ffffff">
                        <span class="color-link-preview" style="background:#ffffff;"></span>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Card Text Color</label>
                    <div class="color-link-group">
                        <input type="color" name="feature_text_color[]" value="#111827" oninput="syncColorLink(this)">
                        <input type="text" class="color-link-hex" value="#111827" oninput="syncColorHexAnon(this)" placeholder="#111827">
                        <span class="color-link-preview" style="background:#111827;"></span>
                    </div>
                </div>
            </div>
        </div>
    `;
    container.appendChild(col);
}

function addWhyChooseColumn() {
    const container = document.getElementById('whyChooseContainer');
    const col = document.createElement('div');
    col.className = 'why-choose-col border border-gray-200 rounded-xl p-5 bg-white shadow-sm relative';
    col.innerHTML = `
        <button type="button" onclick="this.closest('.why-choose-col').remove()" class="absolute top-3 right-3 text-gray-300 hover:text-red-500 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div class="space-y-4 mt-2">
            <div>
                <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">SVG Icon Code</label>
                <textarea name="why_choose_icon[]" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-xs focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm font-mono text-gray-600"></textarea>
            </div>
            <div>
                <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Title</label>
                <input type="text" name="why_choose_title[]" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm">
            </div>
            <div>
                <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Description</label>
                <textarea name="why_choose_description[]" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors text-sm"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4 mt-2 border-t border-gray-100 pt-3">
                <div>
                    <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Card BG Color</label>
                    <div class="color-link-group">
                        <input type="color" name="why_choose_bg_color[]" value="#ffffff" oninput="syncColorLink(this)">
                        <input type="text" class="color-link-hex" value="#ffffff" oninput="syncColorHexAnon(this)" placeholder="#ffffff">
                        <span class="color-link-preview" style="background:#ffffff;"></span>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">Card Text Color</label>
                    <div class="color-link-group">
                        <input type="color" name="why_choose_text_color[]" value="#111827" oninput="syncColorLink(this)">
                        <input type="text" class="color-link-hex" value="#111827" oninput="syncColorHexAnon(this)" placeholder="#111827">
                        <span class="color-link-preview" style="background:#111827;"></span>
                    </div>
                </div>
            </div>
        </div>
    `;
    container.appendChild(col);
}

// Sync color picker -> hex text + preview
function syncColorLink(colorInput) {
    const group = colorInput.closest('.color-link-group');
    const hex = colorInput.value;
    const hexInput = group.querySelector('.color-link-hex');
    const preview = group.querySelector('.color-link-preview');
    if (hexInput) hexInput.value = hex.toUpperCase();
    if (preview) preview.style.background = hex;
    // Also update named preview if exists
    if (colorInput.id) {
        const namedPreview = document.getElementById('prev_' + colorInput.id.replace('clr_',''));
        if (namedPreview) namedPreview.style.background = hex;
    }
}

// Sync hex text -> color picker + preview (named)
function syncColorHex(hexInput, colorPickerId) {
    let val = hexInput.value.trim();
    if (!val.startsWith('#')) val = '#' + val;
    const group = hexInput.closest('.color-link-group');
    const preview = group ? group.querySelector('.color-link-preview') : null;
    const picker = document.getElementById(colorPickerId);
    if (/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/.test(val)) {
        if (picker) picker.value = val;
        if (preview) preview.style.background = val;
        const namedPreview = document.getElementById('prev_' + colorPickerId.replace('clr_',''));
        if (namedPreview) namedPreview.style.background = val;
    }
}

// Sync hex text -> color picker + preview (anonymous, sibling-based)
function syncColorHexAnon(hexInput) {
    let val = hexInput.value.trim();
    if (!val.startsWith('#')) val = '#' + val;
    const group = hexInput.closest('.color-link-group');
    const picker = group ? group.querySelector('input[type=color]') : null;
    const preview = group ? group.querySelector('.color-link-preview') : null;
    if (/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/.test(val)) {
        if (picker) picker.value = val;
        if (preview) preview.style.background = val;
    }
}
</script>
