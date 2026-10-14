
<div class="max-w-[95rem] mx-auto pb-12 px-4 sm:px-6">
    <form action="<?= BASE_URL ?>/admin/cms/navbars/store" method="POST" id="navbarForm">
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-8">
            <div class="mb-2 flex justify-between items-center">
                <h2 class="text-2xl font-serif text-[#0b3c7c]">Navbar Configuration</h2>
            </div>

            <!-- Global Settings -->
            <div>
                <h3 class="text-xl font-serif font-bold text-[#0b3c7c] mb-6 border-b border-gray-100 pb-3">Global Settings</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left side -->
                <div class="flex flex-wrap gap-8">
                    <div>
                        <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">NAVBAR LOGO</label>
                        <div class="w-32 h-32 border-2 border-dashed border-gray-200 rounded-xl flex items-center justify-center relative bg-gray-50 hover:bg-gray-100 transition-colors cursor-pointer overflow-hidden group" id="logoUploadArea">
                            <input type="hidden" name="logo" id="logoInput" value="">
                            <div id="logoPlaceholder" class="text-center text-gray-400">
                                <svg class="w-8 h-8 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                <span class="text-xs font-medium">Upload</span>
                            </div>
                            <img id="logoPreview" src="" class="absolute inset-0 w-full h-full object-contain hidden bg-white p-2" alt="Logo Preview">
                            <button type="button" id="logoRemoveBtn" class="absolute top-2 right-2 bg-white rounded-full p-1 shadow-sm text-red-500 font-bold hidden z-10 hover:text-red-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">FAVICON (TAB ICON)</label>
                        <div class="w-32 h-32 border-2 border-dashed border-gray-200 rounded-xl flex items-center justify-center relative bg-gray-50 hover:bg-gray-100 transition-colors cursor-pointer overflow-hidden group" id="faviconUploadArea">
                            <input type="hidden" name="favicon" id="faviconInput" value="">
                            <div id="faviconPlaceholder" class="text-center text-gray-400">
                                <svg class="w-8 h-8 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                <span class="text-xs font-medium">Upload</span>
                            </div>
                            <img id="faviconPreview" src="" class="absolute inset-0 w-full h-full object-contain hidden bg-white p-2" alt="Favicon Preview">
                            <button type="button" id="faviconRemoveBtn" class="absolute top-2 right-2 bg-white rounded-full p-1 shadow-sm text-red-500 font-bold hidden z-10 hover:text-red-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right side -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">LOGO CTA URL</label>
                        <input type="text" name="logo_cta_url" placeholder="/" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors">
                        <p class="text-[10px] text-gray-400 mt-1">Where the logo redirects when clicked.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">LOGO POSITION</label>
                        <select name="logo_position" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown focus:ring-1 focus:ring-brown outline-none transition-colors bg-white">
                            <option value="Left">Left</option>
                            <option value="Center">Center</option>
                            <option value="Right">Right</option>
                        </select>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">NAVBAR COLOR THEME</label>
                        </div>
                        
                        <input type="hidden" name="bg_color_type" id="theme_type" value="solid">
                        <input type="hidden" name="bg_color_value" id="theme_value" value="#ffffff">

                        <div class="border border-gray-200 rounded-xl p-4 space-y-4 bg-gray-50">
                            <!-- Type Tabs -->
                            <div class="flex flex-wrap gap-2">
                                <button type="button" data-type="solid" class="color-type-btn active bg-gray-900 text-white border-gray-300 px-4 py-1.5 rounded-full text-xs font-bold border transition-all">Solid</button>
                                <button type="button" data-type="linear" class="color-type-btn bg-white text-gray-600 border-gray-200 px-4 py-1.5 rounded-full text-xs font-bold border transition-all hover:border-gray-400">Linear Gradient</button>
                                <button type="button" data-type="radial" class="color-type-btn bg-white text-gray-600 border-gray-200 px-4 py-1.5 rounded-full text-xs font-bold border transition-all hover:border-gray-400">Radial Gradient</button>
                                <button type="button" data-type="link" class="color-type-btn bg-white text-gray-600 border-gray-200 px-4 py-1.5 rounded-full text-xs font-bold border transition-all hover:border-gray-400">Color Link</button>
                            </div>

                            <!-- Solid Panel -->
                            <div class="panel-solid flex items-center gap-4">
                                <label class="text-xs text-gray-500 font-medium w-12">Color</label>
                                <input type="color" class="color-solid-picker w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5 bg-white" value="#ffffff">
                                <input type="text" class="color-solid-input w-28 border border-gray-200 rounded-lg px-3 py-1.5 outline-none focus:border-brown font-mono" value="#ffffff">
                            </div>

                            <!-- Linear Gradient Panel -->
                            <div class="panel-linear hidden space-y-3">
                                <div class="flex items-center gap-4">
                                    <label class="text-xs text-gray-500 font-medium w-16">From</label>
                                    <input type="color" class="color-linear-picker-1 w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5 bg-white" value="#8c6a4f">
                                    <input type="text" class="color-linear-1 w-28 border border-gray-200 rounded-lg px-3 py-1.5 outline-none focus:border-brown font-mono" value="#8c6a4f">
                                </div>
                                <div class="flex items-center gap-4">
                                    <label class="text-xs text-gray-500 font-medium w-16">To</label>
                                    <input type="color" class="color-linear-picker-2 w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5 bg-white" value="#c4a882">
                                    <input type="text" class="color-linear-2 w-28 border border-gray-200 rounded-lg px-3 py-1.5 outline-none focus:border-brown font-mono" value="#c4a882">
                                </div>
                                <div class="flex items-center gap-4">
                                    <label class="text-xs text-gray-500 font-medium w-16">Direction</label>
                                    <select class="color-linear-dir border border-gray-200 rounded-lg px-3 py-1.5 bg-white outline-none focus:border-brown w-40">
                                        <option value="to right">Left &rarr; Right</option>
                                        <option value="to left">Right &rarr; Left</option>
                                        <option value="to bottom">Top &rarr; Bottom</option>
                                        <option value="to top">Bottom &rarr; Top</option>
                                        <option value="to bottom right">Top-Left &rarr; Bottom-Right</option>
                                        <option value="to bottom left">Top-Right &rarr; Bottom-Left</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Radial Gradient Panel -->
                            <div class="panel-radial hidden space-y-3">
                                <div class="flex items-center gap-4">
                                    <label class="text-xs text-gray-500 font-medium w-16">Center</label>
                                    <input type="color" class="color-radial-picker-1 w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5 bg-white" value="#ffffff">
                                    <input type="text" class="color-radial-1 w-28 border border-gray-200 rounded-lg px-3 py-1.5 outline-none focus:border-brown font-mono" value="#ffffff">
                                </div>
                                <div class="flex items-center gap-4">
                                    <label class="text-xs text-gray-500 font-medium w-16">Edge</label>
                                    <input type="color" class="color-radial-picker-2 w-10 h-10 rounded-lg border-2 border-gray-200 cursor-pointer p-0.5 bg-white" value="#8c6a4f">
                                    <input type="text" class="color-radial-2 w-28 border border-gray-200 rounded-lg px-3 py-1.5 outline-none focus:border-brown font-mono" value="#8c6a4f">
                                </div>
                            </div>

                            <!-- Color Link Panel -->
                            <div class="panel-link hidden space-y-2">
                                <label class="text-xs text-gray-500 font-medium">Enter a CSS color value, variable, or any valid background</label>
                                <input type="text" class="color-link-input w-full border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-brown font-mono" value="" placeholder="e.g. var(--primary-color) or #FF5500 or any CSS value">
                            </div>

                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">NAVBAR TEXT COLOR</label>
                                <label class="flex items-center gap-2 font-medium text-gray-700 cursor-pointer w-max mb-3">
                                    <input type="checkbox" id="use_custom_text_link" class="w-4 h-4 rounded border-gray-300">
                                    Use Custom Color Link / CSS
                                </label>
                                <div id="standard-text-panel" class="flex items-center gap-3 border border-gray-200 rounded-[10px] p-2 focus-within:border-brown focus-within:ring-1 focus-within:ring-brown transition-colors bg-white max-w-[220px] shadow-sm">
                                    <div class="relative w-8 h-8 flex-shrink-0 rounded-md overflow-hidden border border-gray-200 shadow-inner">
                                        <input type="color" id="text_color_picker" value="#8C6A4F" class="absolute -top-2 -left-2 w-12 h-12 cursor-pointer border-0 p-0 bg-transparent">
                                    </div>
                                    <input type="text" id="text_color_text" value="#8C6A4F" class="flex-1 outline-none border-none focus:ring-0 p-0 font-mono text-gray-700 bg-transparent">
                                </div>
                                <div id="custom-text-panel" class="hidden">
                                    <input type="hidden" name="text_color" id="final_text_color" value="#8C6A4F" disabled>
                                    <input type="text" id="custom_text_link_input" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 outline-none focus:border-brown font-mono" value="" placeholder="e.g. var(--text-color) or #FF0000">
                                </div>
                                <input type="hidden" name="text_color" id="final_text_color_standard" value="#8C6A4F">
                            </div>

                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <div class="relative">
                                        <input type="checkbox" name="show_system_icons" id="show_system_icons" class="sr-only peer" checked>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all" style="accent-color: #8c6a4f;"></div>
                                    </div>
                                    <span class="text-xs font-serif font-bold text-gray-500 uppercase tracking-wider">Show System Icons</span>
                                </label>
                            </div>

                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-3">NAVBAR CONTAINER HEIGHT</label>
                                <div class="flex items-center gap-3">
                                    <input type="text" name="container_height" value="10" class="w-24 border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-brown font-mono">
                                    <span class="text-xs text-gray-500">vh (default: 10)</span>
                                </div>
                            </div>

                            
                            
                            <div class="pt-4">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Preview</label>
                            <?php
                                $themeValue = '#ffffff'; // Default for create
                                $previewStyle = 'background: #ffffff';
                            ?>
                            <div class="h-12 rounded-lg border border-gray-200 theme-preview-box w-full" style="<?= (strpos($previewStyle, ':') !== false) ? $previewStyle : 'background: ' . $previewStyle ?>"></div>
                            <div class="text-[10px] text-gray-400 font-mono mt-1 theme-preview-text">#ffffff</div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- Menu Items Section -->
        <div class="flex justify-between items-center mb-4 mt-8 border-t border-gray-100 pt-8">
            <div>
                <h3 class="text-xl font-serif font-bold text-gray-800">Menu Items</h3>
                <p class="text-xs text-gray-500 mt-0.5">Drag items or use the arrows to rearrange navigation order on your website</p>
            </div>
            <button type="button" id="addMenuItemBtn" class="text-gray-700 bg-white border border-gray-200 px-4 py-2 rounded-full font-bold text-xs uppercase tracking-wider shadow-sm hover:bg-gray-50 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                ADD ITEM
            </button>
        </div>

        <div id="menuItemsContainer" class="space-y-6">
            <!-- Items will be appended here -->
        </div>

        <!-- Bottom Action Row -->
        <div class="mt-8 px-8 pt-6 pb-8 -mx-8 -mb-8 flex justify-between items-center border-t border-gray-100 rounded-b-2xl">
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" class="w-5 h-5 text-[#0056b3] bg-gray-100 border-gray-300 rounded focus:ring-[#0056b3] focus:ring-2 cursor-pointer" style="accent-color: #0056b3;" checked>
                <label for="is_active" class="font-bold text-[#0056b3]">Active (Visible on Site)</label>
            </div>
            
            <div class="flex items-center gap-4">
                <a href="<?= BASE_URL ?>/admin/cms/navbars" class="px-6 py-2.5 rounded-full border border-red-200 text-red-500 font-bold hover:bg-red-50 transition-colors bg-white">CANCEL</a>
                <button type="submit" form="navbarForm" class="px-8 py-2.5 bg-gray-900 text-white font-bold text-sm rounded-full shadow-sm hover:bg-black transition-all">
                    Save Navbar
                </button>
            </div>
        </div>

        </div> <!-- End of main white container -->
    </form>
</div>

<!-- Template for Menu Item -->
<template id="menuItemTemplate">
    <div class="bg-white rounded-[1.5rem] shadow-sm border border-gray-100 p-8 relative menu-item-block section-form-wrapper">
        <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-3">
            <div class="flex items-center gap-3">
                <div class="menu-item-drag-handle cursor-grab active:cursor-grabbing text-gray-400 hover:text-gray-700 bg-gray-50 hover:bg-gray-100 px-3 py-1.5 rounded-xl border border-gray-200 transition-all flex items-center gap-1.5 shadow-2xs select-none group" title="Click and drag to reorder this menu item">
                    <svg class="w-4 h-4 text-gray-500 group-hover:text-blue-600 transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M8 6a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm0 6a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm0 6a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm6-12a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm0 6a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm0 6a2 2 0 1 1-4 0 2 2 0 0 1 4 0z"/></svg>
                    <span class="text-[11px] font-sans font-bold text-gray-600 group-hover:text-blue-600 tracking-wider uppercase">Drag</span>
                </div>
                <div class="flex items-center gap-0.5 bg-gray-50 border border-gray-200 rounded-lg p-0.5">
                    <button type="button" class="move-item-up-btn p-1 text-gray-400 hover:text-blue-600 hover:bg-white rounded transition-colors" title="Move Up">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>
                    </button>
                    <button type="button" class="move-item-down-btn p-1 text-gray-400 hover:text-blue-600 hover:bg-white rounded transition-colors" title="Move Down">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                </div>
                <h4 class="text-lg font-serif font-bold text-[#0b3c7c] menu-item-card-title flex items-center gap-2">
                    <span class="item-title-display">New Menu Item</span>
                    <span class="text-xs font-sans font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-100 order-badge">#</span>
                </h4>
            </div>
            <button type="button" class="text-gray-400 hover:text-red-500 transition-colors remove-item-btn p-1.5 rounded-lg hover:bg-red-50" title="Remove menu item">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">NAV TITLE *</label>
                <input type="text" name="menu_item_title[__INDEX__]" placeholder="e.g. Shop" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown outline-none transition-colors">
            </div>
            <div>
                <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">CTA URL *</label>
                <input type="text" name="menu_item_cta_url[__INDEX__]" placeholder="e.g. /shop" required class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown outline-none transition-colors">
            </div>
            <div>
                <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">POSITION</label>
                <select name="menu_item_position[__INDEX__]" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:border-brown outline-none transition-colors bg-white">
                    <option value="Left">Left</option>
                    <option value="Center">Center</option>
                    <option value="Right">Right</option>
                </select>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">AUTO-FILL FROM CATALOG</label>
                <div class="relative custom-multiselect">
                    <div class="w-full border border-gray-200 rounded-lg px-4 py-2.5 bg-white cursor-pointer flex justify-between items-center transition-colors hover:border-brown" onclick="this.nextElementSibling.classList.toggle('hidden')">
                        <span class="text-gray-700 text-sm select-text-label">Select Categories...</span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                    <div class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg hidden max-h-48 overflow-y-auto p-2 space-y-1">
                        <?php foreach($categories as $cat): ?>
                            <label class="flex items-center gap-2 p-2 hover:bg-gray-50 cursor-pointer rounded transition-colors">
                                <input type="checkbox" name="menu_item_auto_fill[__INDEX__][]" value="<?= htmlspecialchars($cat['id']) ?>" class="rounded border-gray-300 text-brown focus:ring-brown cat-checkbox" onchange="updateMultiSelectText(this)">
                                <span class="text-sm text-gray-700"><?= htmlspecialchars($cat['name']) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-xs font-serif font-bold text-gray-500 uppercase tracking-wider mb-2">TEXT COLOR (OPTIONAL)</label>
                <div class="flex items-center gap-2 border border-gray-200 rounded-lg p-1.5 focus-within:border-brown transition-colors">
                    <input type="color" value="#000000" class="w-8 h-8 rounded cursor-pointer border-0 p-0 item_text_color_picker">
                    <input type="text" name="menu_item_text_color[__INDEX__]" placeholder="#000000" class="flex-1 outline-none border-none focus:ring-0 px-2 font-mono item_text_color_text">
                </div>
            </div>
        </div>

        <!-- Dropdown Options & Color Theme Section -->
        <div class="mt-4 p-4 sm:p-5 bg-gray-50/90 border border-gray-200 rounded-2xl dropdown-config-section transition-all opacity-70">
            <div class="flex items-center justify-between pb-3 mb-3 border-b border-gray-200">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    <span class="text-xs font-serif font-bold text-gray-700 uppercase tracking-wider">Dropdown Menu Options & Theme</span>
                </div>
                <button type="button" class="add-dropdown-option-btn text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 active:scale-95 px-3 py-1.5 rounded-lg transition-all shadow-xs flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    <span>Add Dropdown Option</span>
                </button>
            </div>

            <!-- Dropdown Panel Color Theme -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-[10px] font-serif font-bold text-gray-400 uppercase tracking-wider mb-1.5">PANEL BACKGROUND</label>
                    <div class="flex items-center gap-2 border border-gray-200 rounded-lg p-1.5 bg-white focus-within:border-brown transition-colors">
                        <input type="color" value="#ffffff" class="w-8 h-8 rounded cursor-pointer border-0 p-0 dropdown_bg_picker">
                        <input type="text" name="menu_item_dropdown_bg_color[__INDEX__]" value="#ffffff" placeholder="#ffffff" class="flex-1 outline-none border-none focus:ring-0 px-2 font-mono text-sm dropdown_bg_text">
                        <span class="w-6 h-6 rounded border border-gray-200 flex-shrink-0 dropdown_bg_preview" style="background:#ffffff"></span>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-serif font-bold text-gray-400 uppercase tracking-wider mb-1.5">PANEL TEXT COLOR</label>
                    <div class="flex items-center gap-2 border border-gray-200 rounded-lg p-1.5 bg-white focus-within:border-brown transition-colors">
                        <input type="color" value="#111827" class="w-8 h-8 rounded cursor-pointer border-0 p-0 dropdown_text_picker">
                        <input type="text" name="menu_item_dropdown_text_color[__INDEX__]" value="#111827" placeholder="#111827" class="flex-1 outline-none border-none focus:ring-0 px-2 font-mono text-sm dropdown_text_text">
                        <span class="w-6 h-6 rounded border border-gray-200 flex-shrink-0 dropdown_text_preview" style="background:#111827"></span>
                    </div>
                </div>
            </div>

            <!-- Dropdown Sub-Items List -->
            <div class="mt-3">
                <label class="block text-[11px] font-serif font-bold text-gray-600 uppercase tracking-wider mb-2">
                    Custom Dropdown Links / Options
                    <span class="text-[10px] font-sans font-normal text-gray-400 normal-case ml-1">(Adds clickable options to this dropdown menu)</span>
                </label>
                
                <div class="dropdown-options-container space-y-2.5"></div>

                <div class="no-options-placeholder text-center py-3 text-xs text-gray-400 bg-white/70 border border-dashed border-gray-200 rounded-xl mt-2">
                    No custom dropdown options yet. Click <span class="font-bold text-blue-600">Add Dropdown Option</span> to add custom links.
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between pt-6 mt-6 border-t border-gray-100">
            <div class="flex items-center gap-8">
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="menu_item_is_dropdown[__INDEX__]" value="1" class="w-4 h-4 text-brown rounded border-gray-300 focus:ring-brown">
                    <span class="font-bold text-gray-700">Is Dropdown Menu?</span>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="menu_item_active[__INDEX__]" value="1" checked class="w-4 h-4 text-blue-500 rounded border-gray-300 focus:ring-blue-500">
                    <span class="text-gray-600">Active</span>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" class="insert-item-below-btn text-xs font-bold text-blue-600 hover:text-blue-800 bg-blue-50/70 hover:bg-blue-100/70 px-3 py-1.5 rounded-lg border border-blue-200 transition-colors flex items-center gap-1.5" title="Insert a new menu item directly below this one">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Insert Below</span>
                </button>
            </div>
        </div>
    </div>
</template>

<?php require_once __DIR__ . '/../../media/_selector_modal.php'; ?>

<!-- Include SortableJS for Drag and Drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<style>
.sortable-ghost {
    opacity: 0.35 !important;
    background-color: #eff6ff !important;
    border: 2px dashed #3b82f6 !important;
}
.sortable-drag {
    opacity: 0.98 !important;
    background-color: #ffffff !important;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
    transform: scale(1.01) !important;
}
.sortable-chosen {
    cursor: grabbing !important;
}
.menu-item-drag-handle {
    cursor: grab;
    user-select: none;
    -webkit-user-select: none;
}
.menu-item-drag-handle:active {
    cursor: grabbing;
}
</style>

<script>
function updateMultiSelectText(checkbox) {
    const container = checkbox.closest('.custom-multiselect');
    const checkedCount = container.querySelectorAll('.cat-checkbox:checked').length;
    const label = container.querySelector('.select-text-label');
    label.innerText = checkedCount > 0 ? checkedCount + ' Selected' : 'Select Categories...';
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.custom-multiselect')) {
        document.querySelectorAll('.custom-multiselect .absolute').forEach(el => el.classList.add('hidden'));
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Media Upload Logic
    const logoUploadArea = document.getElementById('logoUploadArea');
    const logoInput = document.getElementById('logoInput');
    const logoPreview = document.getElementById('logoPreview');
    const logoPlaceholder = document.getElementById('logoPlaceholder');
    const logoRemoveBtn = document.getElementById('logoRemoveBtn');

    logoUploadArea.addEventListener('click', function(e) {
        if(e.target === logoRemoveBtn || logoRemoveBtn.contains(e.target)) return;
        
        if (typeof openMediaSelector === 'function') {
            openMediaSelector(url => {
                if (url) {
                    logoInput.value = url;
                    logoPreview.src = url;
                    logoPreview.classList.remove('hidden');
                    logoPlaceholder.classList.add('hidden');
                    logoRemoveBtn.classList.remove('hidden');
                }
            });
        }
    });

    logoRemoveBtn.addEventListener('click', function() {
        logoInput.value = '';
        logoPreview.src = '';
        logoPreview.classList.add('hidden');
        logoPlaceholder.classList.remove('hidden');
        logoRemoveBtn.classList.add('hidden');
    });

    // Favicon Upload Logic
    const faviconUploadArea = document.getElementById('faviconUploadArea');
    const faviconInput = document.getElementById('faviconInput');
    const faviconPreview = document.getElementById('faviconPreview');
    const faviconPlaceholder = document.getElementById('faviconPlaceholder');
    const faviconRemoveBtn = document.getElementById('faviconRemoveBtn');

    if (faviconUploadArea) {
        faviconUploadArea.addEventListener('click', function(e) {
            if(faviconRemoveBtn && (e.target === faviconRemoveBtn || faviconRemoveBtn.contains(e.target))) return;
            if (typeof openMediaSelector === 'function') {
                openMediaSelector(url => {
                    if (url) {
                        faviconInput.value = url;
                        faviconPreview.src = url;
                        faviconPreview.classList.remove('hidden');
                        faviconPlaceholder.classList.add('hidden');
                        faviconRemoveBtn.classList.remove('hidden');
                    }
                });
            }
        });
        faviconRemoveBtn.addEventListener('click', function() {
            faviconInput.value = '';
            faviconPreview.src = '';
            faviconPreview.classList.add('hidden');
            faviconPlaceholder.classList.remove('hidden');
            faviconRemoveBtn.classList.add('hidden');
        });
    }

    // Color Theme Builder
    const hiddenType = document.getElementById('theme_type');
    const hiddenVal = document.getElementById('theme_value');
    const btns = document.querySelectorAll('.color-type-btn');
    const pSolid = document.querySelector('.panel-solid');
    const pLinear = document.querySelector('.panel-linear');
    const pRadial = document.querySelector('.panel-radial');
    const pLink = document.querySelector('.panel-link');
    
    function updateTheme(syncFromText = false) {
        const type = hiddenType.value;
        let val = '';
        
        if (type === 'solid') {
            const picker = document.querySelector('.color-solid-picker');
            const text = document.querySelector('.color-solid-input');
            if (syncFromText && /^#[0-9A-Fa-f]{6}$/.test(text.value)) picker.value = text.value;
            val = picker.value;
            if (!syncFromText) text.value = val;
        } else if (type === 'linear') {
            const picker1 = document.querySelector('.color-linear-picker-1');
            const text1 = document.querySelector('.color-linear-1');
            const picker2 = document.querySelector('.color-linear-picker-2');
            const text2 = document.querySelector('.color-linear-2');
            const dir = document.querySelector('.color-linear-dir').value;
            
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
            const picker1 = document.querySelector('.color-radial-picker-1');
            const text1 = document.querySelector('.color-radial-1');
            const picker2 = document.querySelector('.color-radial-picker-2');
            const text2 = document.querySelector('.color-radial-2');
            
            if (syncFromText) {
                if (/^#[0-9A-Fa-f]{6}$/.test(text1.value)) picker1.value = text1.value;
                if (/^#[0-9A-Fa-f]{6}$/.test(text2.value)) picker2.value = text2.value;
            }
            
            val = `radial-gradient(circle, ${picker1.value}, ${picker2.value})`;
            if (!syncFromText) {
                text1.value = picker1.value;
                text2.value = picker2.value;
            }
        } else if (type === 'link') {
            const linkInput = document.querySelector('.color-link-input');
            val = linkInput ? linkInput.value.trim() : '';
        }
        
        hiddenVal.value = val;
        
        const previewBox = document.querySelector('.theme-preview-box');
        if (previewBox) {
            if (val.includes(':')) {
                previewBox.setAttribute('style', val);
            } else {
                previewBox.setAttribute('style', 'background: ' + val);
            }
        }
        
        const previewText = document.querySelector('.theme-preview-text');
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
            if (pLink) pLink.classList.add('hidden');
            
            if(hiddenType.value === 'solid') pSolid.classList.remove('hidden');
            if(hiddenType.value === 'linear') pLinear.classList.remove('hidden');
            if(hiddenType.value === 'radial') pRadial.classList.remove('hidden');
            if(hiddenType.value === 'link' && pLink) pLink.classList.remove('hidden');
            
            updateTheme();
        });
    });

    document.querySelectorAll('.bg-gray-50 input[type="color"], .bg-gray-50 select').forEach(el => {
        el.addEventListener('input', () => updateTheme(false));
        el.addEventListener('change', () => updateTheme(false));
    });

    document.querySelectorAll('.bg-gray-50 input[type="text"]').forEach(el => {
        if (el.className.includes('color-')) {
            el.addEventListener('input', () => updateTheme(true));
        }
    });

    // Color Link input fires updateTheme directly
    const colorLinkInput = document.querySelector('.color-link-input');
    if (colorLinkInput) {
        colorLinkInput.addEventListener('input', () => {
            const rawVal = colorLinkInput.value.trim();
            hiddenVal.value = rawVal;
            const previewBox = document.querySelector('.theme-preview-box');
            if (previewBox) {
                if (rawVal.includes(':')) {
                    previewBox.setAttribute('style', rawVal);
                } else {
                    previewBox.setAttribute('style', 'background: ' + rawVal);
                }
            }
            const previewText = document.querySelector('.theme-preview-text');
            if (previewText) previewText.innerText = rawVal;
            // updateTheme(false); // Don't call this because it resets to linkInput.value anyway
        });
    }

    // Global Color Sync for Text Color with Custom Link toggle
    const useCustomTextLink = document.getElementById('use_custom_text_link');
    const standardTextPanel = document.getElementById('standard-text-panel');
    const customTextPanel = document.getElementById('custom-text-panel');
    const finalTextColorStd = document.getElementById('final_text_color_standard');
    const finalTextColorCustom = document.getElementById('final_text_color');
    const textColorPicker = document.getElementById('text_color_picker');
    const textColorText = document.getElementById('text_color_text');
    const customTextInput = document.getElementById('custom_text_link_input');

    function updateTextColorHidden() {
        if (useCustomTextLink && useCustomTextLink.checked) {
            if (finalTextColorCustom) finalTextColorCustom.disabled = false;
            if (finalTextColorStd) finalTextColorStd.disabled = true;
            if (customTextInput && finalTextColorCustom) finalTextColorCustom.value = customTextInput.value;
        } else {
            if (finalTextColorCustom) finalTextColorCustom.disabled = true;
            if (finalTextColorStd) finalTextColorStd.disabled = false;
            if (textColorPicker && finalTextColorStd) finalTextColorStd.value = textColorPicker.value;
        }
    }

    if (useCustomTextLink) {
        useCustomTextLink.addEventListener('change', function() {
            if (this.checked) {
                standardTextPanel.classList.add('hidden');
                customTextPanel.classList.remove('hidden');
            } else {
                standardTextPanel.classList.remove('hidden');
                customTextPanel.classList.add('hidden');
            }
            updateTextColorHidden();
        });
    }

    if (textColorPicker && textColorText) {
        textColorPicker.addEventListener('input', () => { textColorText.value = textColorPicker.value; updateTextColorHidden(); });
        textColorText.addEventListener('input', () => { if(/^#[0-9A-Fa-f]{6}$/.test(textColorText.value)) textColorPicker.value = textColorText.value; updateTextColorHidden(); });
    }
    if (customTextInput) {
        customTextInput.addEventListener('input', () => { updateTextColorHidden(); });
    }
    updateTextColorHidden();

    // Menu Items Logic
    const menuItemsContainer = document.getElementById('menuItemsContainer');
    const addMenuItemBtn = document.getElementById('addMenuItemBtn');
    const template = document.getElementById('menuItemTemplate');
    const navbarForm = document.getElementById('navbarForm');

    function reindexMenuItems() {
        const blocks = menuItemsContainer.querySelectorAll('.menu-item-block');
        blocks.forEach((block, idx) => {
            // Update names of all inputs, selects, textareas
            block.querySelectorAll('input, select, textarea').forEach(input => {
                if (!input.name) return;
                input.name = input.name.replace(/\[\d+\]|\[__INDEX__\]/, `[${idx}]`);
            });

            // Update title text and order badge
            const titleInput = block.querySelector('input[name^="menu_item_title"]');
            const titleDisplay = block.querySelector('.item-title-display');
            const orderBadge = block.querySelector('.order-badge');
            if (titleDisplay) {
                const val = titleInput && titleInput.value.trim() ? titleInput.value.trim() : 'Menu Item';
                titleDisplay.textContent = val;
            }
            if (orderBadge) {
                orderBadge.textContent = `#${idx + 1}`;
            }

            // Up/Down button states
            const upBtn = block.querySelector('.move-item-up-btn');
            const downBtn = block.querySelector('.move-item-down-btn');
            if (upBtn) {
                upBtn.disabled = (idx === 0);
                upBtn.classList.toggle('opacity-30', idx === 0);
                upBtn.classList.toggle('cursor-not-allowed', idx === 0);
            }
            if (downBtn) {
                downBtn.disabled = (idx === blocks.length - 1);
                downBtn.classList.toggle('opacity-30', idx === blocks.length - 1);
                downBtn.classList.toggle('cursor-not-allowed', idx === blocks.length - 1);
            }
        });
    }

    function moveItemUp(block) {
        const prev = block.previousElementSibling;
        if (prev && prev.classList.contains('menu-item-block')) {
            menuItemsContainer.insertBefore(block, prev);
            reindexMenuItems();
            block.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }

    function moveItemDown(block) {
        const next = block.nextElementSibling;
        if (next && next.classList.contains('menu-item-block')) {
            menuItemsContainer.insertBefore(next, block);
            reindexMenuItems();
            block.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }

    function insertMenuItemAfter(referenceBlock) {
        let html = template.innerHTML;
        html = html.replace(/__INDEX__/g, itemIndex++);
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        const newBlock = tempDiv.firstElementChild;
        if (referenceBlock && referenceBlock.nextElementSibling) {
            menuItemsContainer.insertBefore(newBlock, referenceBlock.nextElementSibling);
        } else {
            menuItemsContainer.appendChild(newBlock);
        }
        bindItemEvents(newBlock);
        reindexMenuItems();
        newBlock.scrollIntoView({ behavior: 'smooth', block: 'center' });
        const titleInput = newBlock.querySelector('input[name^="menu_item_title"]');
        if (titleInput) titleInput.focus();
    }

    function bindItemEvents(block) {
        // Sync title input with card title in real time
        const titleInput = block.querySelector('input[name^="menu_item_title"]');
        const titleDisplay = block.querySelector('.item-title-display');
        if (titleInput && titleDisplay) {
            titleInput.addEventListener('input', () => {
                titleDisplay.textContent = titleInput.value.trim() || 'Menu Item';
            });
        }

        // Up & Down buttons
        const upBtn = block.querySelector('.move-item-up-btn');
        if (upBtn) upBtn.addEventListener('click', (e) => { e.stopPropagation(); moveItemUp(block); });

        const downBtn = block.querySelector('.move-item-down-btn');
        if (downBtn) downBtn.addEventListener('click', (e) => { e.stopPropagation(); moveItemDown(block); });

        // Insert below button
        const insertBtn = block.querySelector('.insert-item-below-btn');
        if (insertBtn) insertBtn.addEventListener('click', (e) => { e.stopPropagation(); insertMenuItemAfter(block); });

        // Color pickers inside item
        const tPicker = block.querySelector('.item_text_color_picker');
        const tText = block.querySelector('.item_text_color_text');
        if (tPicker && tText) {
            tPicker.addEventListener('input', () => { tText.value = tPicker.value; });
            tText.addEventListener('input', () => { tPicker.value = tText.value; });
        }

        const bPicker = block.querySelector('.item_bg_color_picker');
        const bText = block.querySelector('.item_bg_color_text');
        if (bPicker && bText) {
            bPicker.addEventListener('input', () => { bText.value = bPicker.value; });
            bText.addEventListener('input', () => { bPicker.value = bText.value; });
        }

        // Remove buttons
        const topRemove = block.querySelector('.remove-item-btn');
        if (topRemove) topRemove.addEventListener('click', () => {
            block.remove();
            reindexMenuItems();
        });
        
        const bottomRemove = block.querySelector('.remove-item-btn-bottom');
        if (bottomRemove) bottomRemove.addEventListener('click', () => {
            block.remove();
            reindexMenuItems();
        });

        // Dropdown BG color pickers
        const dbPicker = block.querySelector('.dropdown_bg_picker');
        const dbText   = block.querySelector('.dropdown_bg_text');
        const dbPrev   = block.querySelector('.dropdown_bg_preview');
        if (dbPicker && dbText) {
            dbPicker.addEventListener('input', () => {
                dbText.value = dbPicker.value;
                if (dbPrev) dbPrev.style.background = dbPicker.value;
            });
            dbText.addEventListener('input', () => {
                if (/^#[0-9A-Fa-f]{3,6}$/.test(dbText.value)) {
                    dbPicker.value = dbText.value;
                    if (dbPrev) dbPrev.style.background = dbText.value;
                }
            });
        }

        // Dropdown text color pickers
        const dtPicker = block.querySelector('.dropdown_text_picker');
        const dtText   = block.querySelector('.dropdown_text_text');
        const dtPrev   = block.querySelector('.dropdown_text_preview');
        if (dtPicker && dtText) {
            dtPicker.addEventListener('input', () => {
                dtText.value = dtPicker.value;
                if (dtPrev) dtPrev.style.background = dtPicker.value;
            });
            dtText.addEventListener('input', () => {
                if (/^#[0-9A-Fa-f]{3,6}$/.test(dtText.value)) {
                    dtPicker.value = dtText.value;
                    if (dtPrev) dtPrev.style.background = dtText.value;
                }
            });
        }

        // Dropdown options management
        const addOptBtn = block.querySelector('.add-dropdown-option-btn');
        const optContainer = block.querySelector('.dropdown-options-container');
        const noOptPlaceholder = block.querySelector('.no-options-placeholder');
        const isDropdownCb = block.querySelector('input[name^="menu_item_is_dropdown"]');
        const dropdownSection = block.querySelector('.dropdown-config-section');

        function updateOptionNumbers() {
            if (!optContainer) return;
            const rows = optContainer.querySelectorAll('.dropdown-option-row');
            rows.forEach((row, rIdx) => {
                const numEl = row.querySelector('.opt-number');
                if (numEl) numEl.textContent = (rIdx + 1);
            });
            if (noOptPlaceholder) {
                noOptPlaceholder.classList.toggle('hidden', rows.length > 0);
            }
        }

        if (addOptBtn && optContainer) {
            addOptBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (isDropdownCb) isDropdownCb.checked = true;
                if (dropdownSection) dropdownSection.classList.remove('opacity-70');

                // Determine parent index
                const anyInput = block.querySelector('input[name^="menu_item_title"]');
                let parentIdx = '0';
                if (anyInput) {
                    const match = anyInput.name.match(/\[(\d+|__INDEX__)\]/);
                    if (match) parentIdx = match[1];
                }

                const rowDiv = document.createElement('div');
                rowDiv.className = 'dropdown-option-row flex items-center gap-2.5 p-2 bg-white rounded-xl border border-gray-200 shadow-2xs group hover:border-blue-300 transition-colors';
                rowDiv.innerHTML = `
                    <span class="w-6 h-6 rounded-lg bg-gray-100 text-gray-500 font-mono text-xs flex items-center justify-center shrink-0 font-bold opt-number">#</span>
                    <div class="flex-1 min-w-0">
                        <input type="text" name="menu_item_sub_title[${parentIdx}][]" placeholder="Option Name (e.g. Feeding Dresses)" class="w-full text-xs font-medium border border-gray-200 rounded-lg px-3 py-2 focus:border-blue-500 outline-none transition-colors" required>
                    </div>
                    <div class="flex-1 min-w-0">
                        <input type="text" name="menu_item_sub_url[${parentIdx}][]" placeholder="Option URL (e.g. /catalog?cat=dresses)" class="w-full text-xs font-mono border border-gray-200 rounded-lg px-3 py-2 focus:border-blue-500 outline-none transition-colors" required>
                    </div>
                    <button type="button" class="remove-dropdown-option-btn p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors shrink-0" title="Remove option">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                `;
                optContainer.appendChild(rowDiv);
                updateOptionNumbers();
                const newInput = rowDiv.querySelector('input[type="text"]');
                if (newInput) newInput.focus();
            });

            optContainer.addEventListener('click', (e) => {
                const removeBtn = e.target.closest('.remove-dropdown-option-btn');
                if (removeBtn) {
                    e.stopPropagation();
                    const row = removeBtn.closest('.dropdown-option-row');
                    if (row) {
                        row.remove();
                        updateOptionNumbers();
                    }
                }
            });
        }

        if (isDropdownCb && dropdownSection) {
            isDropdownCb.addEventListener('change', () => {
                if (isDropdownCb.checked) {
                    dropdownSection.classList.remove('opacity-70');
                } else {
                    const rowCount = optContainer ? optContainer.querySelectorAll('.dropdown-option-row').length : 0;
                    if (rowCount === 0) {
                        dropdownSection.classList.add('opacity-70');
                    }
                }
            });
        }
    }

    let itemIndex = menuItemsContainer.querySelectorAll('.menu-item-block').length;
    addMenuItemBtn.addEventListener('click', function() {
        let html = template.innerHTML;
        html = html.replace(/__INDEX__/g, itemIndex++);
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        const block = tempDiv.firstElementChild;
        menuItemsContainer.appendChild(block);
        bindItemEvents(block);
        reindexMenuItems();
        block.scrollIntoView({ behavior: 'smooth', block: 'center' });
        const titleInput = block.querySelector('input[name^="menu_item_title"]');
        if (titleInput) titleInput.focus();
    });

    // Initialize SortableJS
    if (typeof Sortable !== 'undefined') {
        new Sortable(menuItemsContainer, {
            animation: 180,
            handle: '.menu-item-drag-handle',
            draggable: '.menu-item-block',
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            chosenClass: 'sortable-chosen',
            onEnd: function() {
                reindexMenuItems();
            }
        });
    }

    if (navbarForm) {
        navbarForm.addEventListener('submit', function() {
            reindexMenuItems();
        });
    }

    // Add one empty item by default
    addMenuItemBtn.click();
});
</script>
