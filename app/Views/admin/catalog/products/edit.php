<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap');
.font-serif { font-family: 'Playfair Display', serif; }
.font-sans { font-family: 'Inter', sans-serif; }
.bg-cream { background-color: #FDFBF7; }
.border-cream { border-color: #EFE9DF; }
.text-brown { color: #8C6B4D; }
.bg-brown { background-color: #8C6B4D; }
.ring-brown { --tw-ring-color: #8C6B4D; }
.focus\:border-brown:focus { border-color: #8C6B4D; }
</style>

<div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8 bg-cream min-h-screen font-sans -m-6">
    <div class="mb-8 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-[#1A1A1A] font-serif tracking-tight"><?= !empty($isCustomize) ? 'Edit Customizable Product / Fabric' : 'Edit Product Catalog Item' ?></h1>
        <a href="<?= BASE_URL ?>/admin/catalog/products<?= !empty($isCustomize) ? '?module=customize' : '' ?>" class="text-gray-400 hover:text-gray-600 transition-colors bg-white p-2 rounded-full border border-gray-200 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </a>
    </div>

    <form action="<?= BASE_URL ?>/admin/catalog/products/update" method="POST" enctype="multipart/form-data" class="space-y-6">
        <input type="hidden" name="id" value="<?= $product['id'] ?>">
        <?php if (!empty($isCustomize)): ?>
        <input type="hidden" name="module" value="customize">
        <?php endif; ?>
        
        <!-- Categorization and Base Image -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div class="sm:col-span-2 bg-white p-6 rounded-2xl border border-gray-100 flex flex-col justify-between shadow-2xs">
                <div>
                    <div class="border-b border-gray-200 pb-3 mb-5">
                        <h3 class="text-lg font-bold text-gray-900">Categorization</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Organize your product hierarchy and assign size charts.</p>
                    </div>

                    <!-- Row 1: Category & Subcategory (2 Columns with generous room) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-1.5">
                                <label class="block text-sm font-bold text-gray-800">Category <span class="text-red-500">*</span></label>
                                <button type="button" onclick="openCategoryRequestModal('category')" class="text-xs font-bold text-brand-600 hover:text-brand-700 bg-brand-50 hover:bg-brand-100 px-2.5 py-1 rounded-lg border border-brand-200 transition-colors shrink-0 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    <span>Request New</span>
                                </button>
                            </div>
                            <select name="category_id" id="category_id" required class="w-full border border-gray-300 rounded-xl focus:ring-brand-500 focus:border-brand-500 px-3.5 py-2.5 bg-white text-sm text-gray-800 shadow-2xs">
                                <option value="">Select Category...</option>
                                <?php foreach($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>" data-sgst="<?= htmlspecialchars($category['sgst'] ?? '0.00') ?>" data-cgst="<?= htmlspecialchars($category['cgst'] ?? '0.00') ?>" data-hsn="<?= htmlspecialchars($category['hsn_code'] ?? '') ?>" <?= $product['category_id'] == $category['id'] ? 'selected' : '' ?>><?= htmlspecialchars($category['name']) ?><?= !empty($category['hsn_code']) ? ' (HSN: ' . htmlspecialchars($category['hsn_code']) . ')' : '' ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-1.5">
                                <label class="block text-sm font-bold text-gray-800">Subcategory</label>
                                <button type="button" onclick="openCategoryRequestModal('subcategory')" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1 rounded-lg border border-indigo-200 transition-colors shrink-0 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    <span>Request New</span>
                                </button>
                            </div>
                            <select name="sub_category_id" id="sub_category_id" class="w-full border border-gray-300 rounded-xl focus:ring-brand-500 focus:border-brand-500 px-3.5 py-2.5 bg-white text-sm text-gray-800 shadow-2xs <?= empty($subCategories) ? 'opacity-50' : '' ?>" <?= empty($subCategories) ? 'disabled' : '' ?>>
                                <option value="">Select Category first...</option>
                                <?php if (!empty($subCategories)): ?>
                                    <?php foreach($subCategories as $subCategory): ?>
                                        <option value="<?= $subCategory['id'] ?>" <?= $product['sub_category_id'] == $subCategory['id'] ? 'selected' : '' ?>><?= htmlspecialchars($subCategory['name']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Row 2: Size Chart (Placed cleanly below with ample spacing) -->
                    <div class="mt-5 pt-4 border-t border-gray-200/80">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="sm:col-span-2">
                                <div class="flex items-center justify-between gap-2 mb-1.5">
                                    <div class="flex items-center gap-2">
                                        <label class="block text-sm font-bold text-gray-800">Size Chart</label>
                                        <span class="text-[11px] font-semibold text-gray-400 bg-gray-200/60 px-2 py-0.5 rounded-full">Optional</span>
                                    </div>
                                    <a href="<?= BASE_URL ?>/admin/cms/size-charts/create" target="_blank" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 px-2.5 py-1 rounded-lg border border-emerald-200 transition-colors shrink-0 flex items-center gap-1" title="Open Size Chart Creator in new tab">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        <span>Add New Chart</span>
                                    </a>
                                </div>
                                <select name="size_chart_id" id="size_chart_id" class="w-full border border-gray-300 rounded-xl focus:ring-brand-500 focus:border-brand-500 px-3.5 py-2.5 bg-white text-sm text-gray-800 shadow-2xs">
                                    <option value="">Default (Auto from Category / Subcategory / General)</option>
                                    <?php if (!empty($sizeCharts)): ?>
                                        <?php foreach($sizeCharts as $sc): ?>
                                            <option value="<?= $sc['id'] ?>" 
                                                    data-category-id="<?= (int)($sc['category_id'] ?? 0) ?>"
                                                    data-subcategory-id="<?= (int)($sc['sub_category_id'] ?? 0) ?>"
                                                    <?= (!empty($product['size_chart_id']) && (int)$product['size_chart_id'] === (int)$sc['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($sc['title']) ?><?= !empty($sc['sub_category_name']) ? ' [' . htmlspecialchars($sc['sub_category_name']) . ']' : (!empty($sc['category_name']) ? ' (' . htmlspecialchars($sc['category_name']) . ')' : '') ?><?= !empty($sc['dress_type']) ? ' - ' . htmlspecialchars($sc['dress_type']) : '' ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <p class="text-xs text-gray-500 mt-1.5" id="size_chart_hint">Auto-filters to match selected Category and Subcategory.</p>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($vendors)): ?>
                    <!-- Row 3: Assigned Vendor -->
                    <div class="mt-5 pt-4 border-t border-gray-200/80">
                        <label class="block text-sm font-bold text-gray-800 mb-1.5">Assigned Vendor (Optional)</label>
                        <select name="vendor_id" id="vendor_id" class="w-full border border-gray-300 rounded-xl focus:ring-brand-500 focus:border-brand-500 px-3.5 py-2.5 bg-white text-sm text-gray-800 shadow-2xs">
                            <option value="">In-House / Admin Product (Default)</option>
                            <?php foreach($vendors as $v): ?>
                                <option value="<?= $v['id'] ?>" <?= (isset($product['vendor_id']) && (int)$product['vendor_id'] === (int)$v['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($v['store_name'] ?? $v['name']) ?> (ID: <?= htmlspecialchars($v['unique_vendor_id'] ?? 'VN'.$v['id']) ?> - <?= htmlspecialchars($v['email']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Product Base Image -->
            <div class="bg-white p-6 rounded-2xl border border-cream shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-bold text-[#4A3C31] font-serif mb-4 flex items-center"><?= !empty($isCustomize) ? 'Fabric Base Image' : 'Product Base Image' ?></h3>
                    <label class="block text-sm font-bold text-[#4A3C31] mb-1">Update Primary Image</label>
                    <p class="text-xs text-gray-500 mb-3">Select a new image to replace the current thumbnail.</p>
                    
                    <input type="hidden" name="primary_image_path" id="primary_image_path" value="<?= htmlspecialchars($primaryImage['image_path'] ?? '') ?>">
                    <button type="button" onclick="openMediaSelector((rawUrl, fullUrl) => { 
                        const displayUrl = fullUrl || ((rawUrl.startsWith('http://') || rawUrl.startsWith('https://')) ? rawUrl : ('<?= BASE_URL ?>/' + rawUrl.replace(/^\/+/, '')));
                        document.getElementById('primary_image_path').value = rawUrl; 
                        document.getElementById('image_preview').src = displayUrl; 
                        document.getElementById('image_preview_container_outer').classList.remove('hidden'); 
                        document.getElementById('image_preview_container_outer').classList.add('flex'); 
                        document.getElementById('preview_text').innerText = 'Selected:';
                    })" class="px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200 mb-3 block">
                        Select Media
                    </button>
                    
                    <?php 
                    $primaryImage = null;
                    if (!empty($productImages)) {
                        foreach ($productImages as $img) {
                            if ($img['is_primary']) {
                                $primaryImage = $img;
                                break;
                            }
                        }
                    }
                    ?>
                    <div id="image_preview_container_outer" class="mt-2 <?= empty($primaryImage) ? 'hidden' : 'flex' ?> items-center">
                        <p class="text-xs text-gray-500 mr-3" id="preview_text"><?= empty($primaryImage) ? 'Preview:' : 'Current:' ?></p>
                                    <div class="relative inline-block group" id="image_preview_container">
                                        <img id="image_preview" src="<?= empty($primaryImage) ? '#' : htmlspecialchars(get_image_url($primaryImage['image_path'])) ?>" data-original-src="<?= empty($primaryImage) ? '#' : htmlspecialchars(get_image_url($primaryImage['image_path'])) ?>" class="w-16 h-16 object-cover rounded border border-gray-200">
                                        
                                        <button type="button" onclick="removePrimaryImage(this, <?= $product['id'] ?>, '<?= empty($primaryImage) ? '' : htmlspecialchars($primaryImage['image_path']) ?>')" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-md hover:bg-red-600 transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div></div>
                </div>
            </div>
        </div>
        
        <!-- Basic Info (Hidden in original mockup, but necessary for functionality) -->
        <div class="bg-white p-6 rounded-2xl border border-cream shadow-sm">
            <h3 class="text-lg font-bold text-[#4A3C31] font-serif mb-4 flex items-center">
                Basic Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-[#4A3C31] mb-1"><?= !empty($isCustomize) ? 'Fabric Name' : 'Product Name' ?> <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required class="w-full border border-gray-200 rounded-xl focus:ring-brown focus:border-brown px-4 py-2 text-sm text-gray-700 bg-gray-50/50">
                </div>
                <div>
                    <label class="block text-sm font-bold text-[#4A3C31] mb-1">Slug <span class="text-red-500">*</span></label>
                    <input type="text" name="slug" value="<?= htmlspecialchars($product['slug']) ?>" required class="w-full border border-gray-200 rounded-xl focus:ring-brown focus:border-brown px-4 py-2 text-sm text-gray-700 bg-gray-50/50">
                </div>
                <div>
                    <label class="block text-sm font-bold text-[#4A3C31] mb-1">Status</label>
                    <select name="status" class="w-full border border-gray-200 rounded-xl focus:ring-brown focus:border-brown px-4 py-2 text-sm text-gray-700 bg-gray-50/50">
                        <option value="active" <?= $product['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="draft" <?= $product['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="inactive" <?= $product['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
                <?php if (empty($isCustomize)): ?>
                <div>
                    <label class="block text-sm font-bold text-[#4A3C31] mb-1">MOQ <span class="text-red-500">*</span></label>
                    <input type="text" name="moq_override" required min="1" value="<?= htmlspecialchars($product['moq_override'] ?? '') ?>" placeholder="Minimum Order Quantity" class="w-full border border-gray-200 rounded-xl focus:ring-brown focus:border-brown px-4 py-2 text-sm text-gray-700 bg-gray-50/50">
                </div>
                <?php else: ?>
                <div>
                    <label class="block text-sm font-bold text-[#4A3C31] mb-1">Fabric Quantity (Stock) <span class="text-red-500">*</span></label>
                    <input type="number" name="stock_quantity" required min="0" value="<?= htmlspecialchars($product['stock_quantity'] ?? 0) ?>" placeholder="Enter total fabric quantity" class="w-full border border-gray-200 rounded-xl focus:ring-brown focus:border-brown px-4 py-2 text-sm text-gray-700 bg-gray-50/50">
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Description -->
        <?php 
        $rawCustomFields = $product['custom_fields'] ?? [];
        if (is_string($rawCustomFields)) {
            $existingCustomFields = json_decode($rawCustomFields, true) ?: [];
        } elseif (is_array($rawCustomFields)) {
            $existingCustomFields = $rawCustomFields;
        } else {
            $existingCustomFields = [];
        }
        ?>
        <div class="bg-white p-6 rounded-2xl border border-cream shadow-sm space-y-4">
            <div class="flex justify-between items-center">
                <label class="block text-sm font-bold text-[#4A3C31]"><?= !empty($isCustomize) ? 'Fabric Description' : 'Description' ?> <span class="text-red-500">*</span></label>
                <button type="button" onclick="addCustomField()" class="text-xs font-semibold text-brown bg-cream border border-cream px-3 py-1.5 rounded-lg flex items-center hover:bg-[#F5F0E6] transition-colors shadow-2xs">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    + Add Field
                </button>
            </div>
            <textarea name="description" rows="5" required placeholder="Detailed description of the product features, benefits..." class="w-full border border-gray-200 rounded-xl focus:ring-brown focus:border-brown px-4 py-3 text-sm text-gray-700 bg-gray-50/50"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>

            <!-- 2. How to Use / How to Play -->
            <div>
                <label class="block text-sm font-bold text-[#4A3C31] mb-1"><?= !empty($isCustomize) ? 'How to Customize / Use' : 'How to Use / How to Play' ?></label>
                <textarea name="how_to_use" rows="3" placeholder="Enter instructions on how to use or play with this product..." class="w-full border border-gray-200 rounded-xl focus:ring-brown focus:border-brown px-4 py-2.5 text-sm text-gray-700 bg-gray-50/50"><?= htmlspecialchars($product['how_to_use'] ?? '') ?></textarea>
            </div>

            <!-- 3. Why Choose -->
            <div>
                <label class="block text-sm font-bold text-[#4A3C31] mb-1">Why Choose</label>
                <textarea name="why_choose" rows="3" placeholder="Enter why customers should choose this product..." class="w-full border border-gray-200 rounded-xl focus:ring-brown focus:border-brown px-4 py-2.5 text-sm text-gray-700 bg-gray-50/50"><?= htmlspecialchars($product['why_choose'] ?? '') ?></textarea>
            </div>

            <!-- Dynamic Custom Fields Container -->
            <div id="custom-fields-container" class="space-y-3 pt-2">
                <?php if (!empty($existingCustomFields)): ?>
                    <?php foreach ($existingCustomFields as $cfIdx => $cf): ?>
                        <div class="custom-field-row flex items-start gap-3 p-3.5 bg-gray-50/80 border border-gray-200 rounded-xl transition-all duration-200 hover:border-gray-300 shadow-2xs" id="custom-field-<?= $cfIdx ?>">
                            <div class="w-1/3 min-w-[140px]">
                                <input type="text" name="custom_fields[<?= $cfIdx ?>][name]" value="<?= htmlspecialchars($cf['name'] ?? '') ?>" placeholder="Field Name" class="w-full border border-gray-300 rounded-lg focus:ring-brand-500 focus:border-brand-500 px-3 py-2 text-sm bg-white font-medium text-gray-800">
                            </div>
                            <div class="flex-1">
                                <textarea name="custom_fields[<?= $cfIdx ?>][value]" rows="2" placeholder="Field details / content..." class="w-full border border-gray-300 rounded-lg focus:ring-brand-500 focus:border-brand-500 px-3 py-2 text-sm bg-white text-gray-800 resize-y"><?= htmlspecialchars($cf['value'] ?? '') ?></textarea>
                            </div>
                            <button type="button" onclick="this.closest('.custom-field-row').remove()" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors mt-0.5" title="Delete Field">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Custom Specifications (Attributes) -->
        <div id="attributes-section" class="bg-white p-6 rounded-2xl border border-cream shadow-sm">
            <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-2">
                <div class="flex items-center space-x-3">
                    <h3 class="text-lg font-bold text-[#4A3C31] font-serif flex items-center">
                        <span class="mr-2 text-yellow-500">✨</span> Custom Specifications
                    </h3>
                    <button type="button" onclick="openAttributeModal()" class="inline-flex items-center px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 rounded-lg text-xs font-bold transition-colors shadow-sm">
                        <svg class="w-3.5 h-3.5 mr-1 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        + Add Attribute
                    </button>
                </div>
                <label class="flex items-center space-x-2 cursor-pointer bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200">
                    <input type="checkbox" id="no_attributes_checkbox" class="w-4 h-4 text-brown border-gray-300 rounded focus:ring-brown">
                    <span class="text-sm font-bold text-gray-700">No Attributes (Simple Product)</span>
                </label>
            </div>
            <div id="attributes-container" class="min-h-[100px]">
                <!-- Attributes will be loaded here via JS -->
            </div>
        </div>

        <!-- Variant Management -->
        <div id="variant-management-section" class="bg-white rounded-2xl border border-cream shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-[#4A3C31] font-serif mb-6 flex items-center">
                    <span class="mr-2 text-[#C4A484]"><svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg></span> Variant Management
                </h3>
                
                <div class="flex flex-wrap items-center justify-between gap-4 bg-gray-50/50 p-3 rounded-xl border border-gray-100">

                    <div class="flex items-center space-x-4">
                        <button type="button" id="btn-refresh-variants" class="text-xs font-bold text-white bg-brown border border-brown rounded-full px-6 py-2 hover:bg-[#7A5B3E] transition-colors tracking-wide shadow-sm">REFRESH VARIANTS</button>
                        
                        <div class="flex bg-white rounded-lg border border-gray-200 p-0.5 text-xs font-medium shadow-sm">
                            <button type="button" id="view-table" class="px-4 py-1.5 text-brown bg-cream rounded-md border border-cream">Table</button>
                            <button type="button" id="view-cards" class="px-4 py-1.5 text-gray-500 hover:text-gray-700 rounded-md">Cards</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="variant-table-view" class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-xs font-bold text-gray-400 uppercase tracking-wider bg-gray-50/30">
                            <th class="px-6 py-4 border-b border-gray-100">Variant</th>
                            <th class="px-6 py-4 border-b border-gray-100">SKU</th>
                            <th class="px-6 py-4 border-b border-gray-100">Price (₹)</th>
                            <th class="px-6 py-4 border-b border-gray-100 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="variant-table-body" class="divide-y divide-gray-100">
                        <?php if (empty($variants)): ?>
                        <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">No variants exist for this product.</td></tr>
                        <?php else: ?>
                            <?php foreach ($variants as $index => $variant): ?>
                            <tr class="hover:bg-gray-50/50 transition-colors variant-table-row">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        <span class="font-bold text-gray-900 font-serif text-base">Variant #<?= $variant['id'] ?></span>
                                        <?php if ($index === 0): ?>
                                        <span class="ml-3 px-2 py-0.5 text-[10px] font-bold text-[#A1885E] bg-[#F7F2E8] rounded-full">Primary</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <input type="text" data-sync="variants[<?= $variant['id'] ?>][sku]" value="<?= htmlspecialchars($variant['sku']) ?>" class="w-48 text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-600 focus:ring-brown focus:border-brown bg-white shadow-sm sync-input">
                                </td>
                                <td class="px-6 py-4">
                                    <input type="text" step="any" min="0.01" data-sync="variants[<?= $variant['id'] ?>][base_price]" value="<?= htmlspecialchars($variant['base_price']) ?>" class="w-32 text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-600 focus:ring-brown focus:border-brown bg-white shadow-sm sync-input">
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button type="button" onclick="if(confirm('Remove this variant? It will be deleted when you update the product.')){ this.closest('.variant-table-row').remove(); const card = document.getElementById('variant-card-<?= $variant['id'] ?>'); if(card) card.remove(); }" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete variant">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- CARDS VIEW -->
            <div id="variant-cards-view" class="p-6 bg-gray-50/20 border-t border-gray-100 hidden">
                <?php if (empty($variants)): ?>
                    <div class="text-center py-8 text-gray-500 text-sm border-2 border-dashed border-gray-200 rounded-xl">
                        No variants exist for this product yet.
                    </div>
                <?php else: ?>
                    <div class="space-y-6">
                        <?php foreach ($variants as $index => $variant): ?>
                        <div id="variant-card-<?= $variant['id'] ?>" class="bg-white rounded-2xl border border-cream shadow-sm overflow-hidden variant-card-item">
                            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/30">
                                <div class="flex items-center space-x-3">
                                    <span class="font-bold text-gray-900 font-serif text-lg">Variant #<?= $variant['id'] ?></span>
                                    <?php if ($index === 0): ?>
                                    <span class="px-2 py-0.5 text-[10px] font-bold text-[#A1885E] bg-[#F7F2E8] rounded-full uppercase tracking-wide">Primary</span>
                                    <?php endif; ?>
                                    <div class="text-xs text-gray-400 mt-1 font-mono hidden md:block ml-2"><?= htmlspecialchars($variant['sku']) ?></div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-3 py-1 text-xs font-bold text-green-700 bg-green-100 rounded-full"><?= ucfirst(htmlspecialchars($variant['status'] ?? 'active')) ?></span>
                                    <button type="button" onclick="if(confirm('Remove this variant? It will be deleted when you update the product.')){ this.closest('.variant-card-item').remove(); }" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete variant">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
                                <!-- Form Fields (2/3 width) -->
                                <?php
                                    $vSgst = isset($variant['sgst']) && $variant['sgst'] !== null ? (float)$variant['sgst'] : (float)($product['sgst'] ?? 0);
                                    $vCgst = isset($variant['cgst']) && $variant['cgst'] !== null ? (float)$variant['cgst'] : (float)($product['cgst'] ?? 0);
                                    $vTotalGst = isset($variant['total_gst']) && $variant['total_gst'] !== null ? (float)$variant['total_gst'] : ($vSgst + $vCgst);
                                    $effectivePrice = !empty($variant['discount_price']) && (float)$variant['discount_price'] > 0 ? (float)$variant['discount_price'] : (float)($variant['base_price'] ?? 0);
                                    $vMaxAmount = isset($variant['max_amount']) && $variant['max_amount'] !== null && (float)$variant['max_amount'] > 0 ? (float)$variant['max_amount'] : $effectivePrice;
                                ?>
                                <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 variant-card-container">
                                    <div>
                                        <input type="hidden" name="variants[<?= $variant['id'] ?>][name]" value="<?= htmlspecialchars($variant['name'] ?? '') ?>">
                                        <label class="block text-xs font-bold text-gray-500 mb-1">Base Price <span class="text-red-500">*</span></label>
                                        <input type="text" step="any" min="0.01" name="variants[<?= $variant['id'] ?>][base_price]" value="<?= htmlspecialchars($variant['base_price']) ?>" required oninput="calculateGst(this)" class="variant-base-price w-full border border-gray-200 rounded-lg focus:ring-brown focus:border-brown px-3 py-2 text-sm text-gray-700 shadow-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 mb-1">Discount Price</label>
                                        <input type="text" step="0.01" name="variants[<?= $variant['id'] ?>][discount_price]" value="<?= htmlspecialchars($variant['discount_price'] ?? '') ?>" oninput="calculateGst(this)" class="variant-discount-price w-full border border-gray-200 rounded-lg focus:ring-brown focus:border-brown px-3 py-2 text-sm text-gray-700 shadow-sm" placeholder="e.g. 99.99">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 mb-1">SGST (%)</label>
                                        <input type="text" step="0.01" name="variants[<?= $variant['id'] ?>][sgst]" value="<?= number_format($vSgst, 2, '.', '') ?>" readonly class="variant-sgst w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 cursor-not-allowed text-gray-600 shadow-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 mb-1">CGST (%)</label>
                                        <input type="text" step="0.01" name="variants[<?= $variant['id'] ?>][cgst]" value="<?= number_format($vCgst, 2, '.', '') ?>" readonly class="variant-cgst w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 cursor-not-allowed text-gray-600 shadow-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 mb-1">Total GST (%)</label>
                                        <input type="text" step="0.01" name="variants[<?= $variant['id'] ?>][total_gst]" value="<?= number_format($vTotalGst, 2, '.', '') ?>" readonly class="variant-total-gst w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 cursor-not-allowed font-semibold text-gray-700 shadow-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 mb-1">Total Amount (Incl. GST)</label>
                                        <input type="text" step="0.01" name="variants[<?= $variant['id'] ?>][max_amount]" value="<?= number_format($vMaxAmount, 2, '.', '') ?>" readonly class="variant-total-amount w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 cursor-not-allowed font-bold text-[#8C6B4D] shadow-sm">
                                    </div>
                                    <?php if (empty($isCustomize)): ?>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 mb-1">Inventory <span class="text-red-500">*</span></label>
                                        <input type="text" name="variants[<?= $variant['id'] ?>][inventory]" value="<?= htmlspecialchars($variant['inventory']) ?>" required class="w-full border border-gray-200 rounded-lg focus:ring-brown focus:border-brown px-3 py-2 text-sm text-gray-700 shadow-sm">
                                    </div>
                                    <?php endif; ?>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 mb-1">Low Stock Alert <span class="text-red-500">*</span></label>
                                        <input type="text" name="variants[<?= $variant['id'] ?>][low_stock_alert]" value="<?= htmlspecialchars($variant['low_stock_alert']) ?>" required class="w-full border border-gray-200 rounded-lg focus:ring-brown focus:border-brown px-3 py-2 text-sm text-gray-700 shadow-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 mb-1">SKU <span class="text-red-500">*</span></label>
                                        <input type="text" name="variants[<?= $variant['id'] ?>][sku]" value="<?= htmlspecialchars($variant['sku']) ?>" required class="w-full border border-gray-200 rounded-lg focus:ring-brown focus:border-brown px-3 py-2 text-sm text-gray-700 shadow-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 mb-1">Weight (kg) <span class="text-red-500">*</span></label>
                                        <input type="text" step="0.0001" min="0.0001" name="variants[<?= $variant['id'] ?>][weight]" value="<?= $variant['weight'] !== null ? (float)$variant['weight'] : '' ?>" required class="w-full border border-gray-200 rounded-lg focus:ring-brown focus:border-brown px-3 py-2 text-sm text-gray-700 shadow-sm">
                                    </div>
                                </div>
                                
                                <!-- Images Section (1/3 width) -->
                                <div class="lg:col-span-1 border-t lg:border-t-0 lg:border-l border-gray-100 pt-6 lg:pt-0 lg:pl-6">
                                    <h4 class="text-sm font-bold text-[#4A3C31] font-serif mb-4 border-b border-gray-200 pb-2">Variant Images</h4>
                                    
                                    <button type="button" onclick="openMediaSelector((rawUrl, fullUrl) => addVariantImage('<?= $variant['id'] ?>', rawUrl, '', fullUrl))" class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200 mb-4 flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        Add Media
                                    </button>
                                    
                                    <div id="variant-image-inputs-<?= $variant['id'] ?>"></div>
                                    <div id="variant-image-preview-<?= $variant['id'] ?>" class="grid grid-cols-4 gap-2 mb-4"></div>
                                    
                                    <div class="grid grid-cols-4 gap-2">
                                        <?php if (!empty($variant['images'])): ?>
                                            <?php foreach ($variant['images'] as $imgIndex => $imgUrl): ?>
                                                <?php $displayUrl = get_image_url($imgUrl); ?>
                                                <div class="relative group rounded-lg overflow-hidden border border-brown shadow-sm p-0.5 bg-[#D9A05B] existing-variant-image-tile">
                                                    <?php if ($imgIndex === 0): ?>
                                                    <span class="absolute top-0.5 left-0.5 bg-brown text-white text-[8px] px-1 rounded-sm uppercase tracking-wider z-10 flex items-center"><svg class="w-2 h-2 mr-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg> Main</span>
                                                    <?php endif; ?>
                                                    <div class="aspect-square bg-gray-100 rounded-md bg-cover bg-center" style="background-image: url('<?= htmlspecialchars($displayUrl) ?>')"></div>
                                                    <input type="hidden" name="variants[<?= $variant['id'] ?>][existing_image_paths][]" value="<?= htmlspecialchars($imgUrl) ?>">
                                                    <button type="button" onclick="removeExistingVariantImage(this)" class="absolute top-0.5 right-0.5 bg-red-500 text-white rounded-full p-1 z-20 shadow-md hover:bg-red-600 transition-colors"> 
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <!-- Mockup Placeholder -->
                                            <div class="relative group rounded-lg overflow-hidden border border-gray-200 shadow-sm p-0.5 bg-gray-50 col-span-4 text-center py-4">
                                                <span class="text-xs text-gray-400">No images uploaded</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="flex justify-end pt-4 pb-12">
            <button type="submit" class="px-8 py-3 bg-brown text-white font-bold rounded-full hover:bg-[#7A5B3E] transition-colors shadow-md"><?= !empty($isCustomize) ? 'Update Fabric' : 'Update Product' ?></button>
        </div>
    </form>
</div>

<script>
const BASE_URL = '<?= BASE_URL ?>';
document.addEventListener('DOMContentLoaded', function() {
    const categoryIdInput = document.getElementById('category_id');
    const subCategorySelect = document.getElementById('sub_category_id');
    const attributesContainer = document.getElementById('attributes-container');
    const noAttributesCheckbox = document.getElementById('no_attributes_checkbox');
    const variantManagementSection = document.getElementById('variant-management-section');
    
    // Check if product is already a simple product
    const existingVariantsCount = <?= count($variants ?? []) ?>;
    const existingAttributesCount = <?= count($selectedAttributes ?? []) ?>;
    
    // If it has NO attributes and NO explicit variants (or 1 variant with no attributes), we might check it.
    if (existingAttributesCount === 0 && (existingVariantsCount === 0 || existingVariantsCount === 1)) {
        noAttributesCheckbox.checked = true;
    }

    function toggleNoAttributes() {
        if (!noAttributesCheckbox) return;
        
        if (noAttributesCheckbox.checked) {
            attributesContainer.style.display = 'none';
            if (variantManagementSection) {
                variantManagementSection.style.display = 'block';
                const header = variantManagementSection.querySelector('h3');
                if(header) header.innerHTML = '📦 Product Details';
                
                const variantInputs = variantManagementSection.querySelectorAll('input, select, textarea, button');
                variantInputs.forEach(input => input.disabled = false);
                
                const refreshBtn = document.getElementById('btn-refresh-variants');
                if (refreshBtn) refreshBtn.style.display = 'none';
            }
            const attrInputs = attributesContainer.querySelectorAll('input, select, textarea');
            attrInputs.forEach(input => input.disabled = true);
        } else {
            attributesContainer.style.display = 'block';
            if (variantManagementSection) {
                variantManagementSection.style.display = 'block';
                const header = variantManagementSection.querySelector('h3');
                if(header) header.innerHTML = '<span class="mr-2 text-[#C4A484]"><svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg></span> Variant Management';
                
                const variantInputs = variantManagementSection.querySelectorAll('input, select, textarea, button');
                variantInputs.forEach(input => input.disabled = false);
                
                const refreshBtn = document.getElementById('btn-refresh-variants');
                if (refreshBtn) refreshBtn.style.display = 'block';
            }
            const attrInputs = attributesContainer.querySelectorAll('input, select, textarea');
            attrInputs.forEach(input => input.disabled = false);
            
            fetchAttributes();
        }
    }

    const sizeChartSelect = document.getElementById('size_chart_id');
    const sizeChartHint = document.getElementById('size_chart_hint');

    function filterSizeCharts(catId, subCatId) {
        if (!sizeChartSelect) return;

        const options = sizeChartSelect.querySelectorAll('option[data-category-id]');
        let matchCount = 0;
        let subMatchCount = 0;

        options.forEach(opt => {
            const chartCatId = opt.getAttribute('data-category-id');
            const chartSubCatId = opt.getAttribute('data-subcategory-id') || '0';

            const matchesSub = subCatId && (chartSubCatId === String(subCatId));
            const matchesCat = catId && (chartCatId === String(catId));
            const isGlobal = !chartCatId || chartCatId === '0';

            if (matchesSub) {
                opt.style.display = '';
                subMatchCount++;
                matchCount++;
            } else if (matchesCat || isGlobal) {
                opt.style.display = '';
                if (matchesCat) matchCount++;
            } else {
                opt.style.display = 'none';
            }
        });

        if (subCatId && subMatchCount > 0) {
            const currentSelected = sizeChartSelect.querySelector(`option[value="${sizeChartSelect.value}"]`);
            if (!sizeChartSelect.value || (currentSelected && currentSelected.style.display === 'none')) {
                const matchedSubOpt = sizeChartSelect.querySelector(`option[data-subcategory-id="${subCatId}"]`);
                if (matchedSubOpt) {
                    sizeChartSelect.value = matchedSubOpt.value;
                }
            }
        }

        if (sizeChartHint) {
            if (subCatId && subMatchCount > 0) {
                sizeChartHint.innerHTML = `<span class="text-emerald-600 font-semibold">✓ ${subMatchCount} size chart(s) specifically tailored for this subcategory.</span>`;
            } else if (catId && matchCount > 0) {
                sizeChartHint.innerHTML = `<span class="text-blue-600 font-semibold">✓ ${matchCount} size chart(s) available for this category.</span>`;
            } else {
                sizeChartHint.innerHTML = `<span class="text-gray-500">Auto-filters to match selected Category and Subcategory.</span>`;
            }
        }
    }

    if (noAttributesCheckbox) {
        noAttributesCheckbox.addEventListener('change', toggleNoAttributes);
    }
    
    toggleNoAttributes(); // Initial state

    // Initial filter for size charts based on currently selected values
    filterSizeCharts(categoryIdInput ? categoryIdInput.value : '', subCategorySelect ? subCategorySelect.value : '');

    if (categoryIdInput) {
        categoryIdInput.addEventListener('change', function() {
            const catId = this.value;
            filterSizeCharts(catId, subCategorySelect ? subCategorySelect.value : '');

            if (!catId) {
                if (subCategorySelect) {
                    subCategorySelect.innerHTML = '<option value="">Select Category first...</option>';
                    subCategorySelect.disabled = true;
                }
                fetchAttributes();
                return;
            }

            if (subCategorySelect) {
                subCategorySelect.disabled = true;
                subCategorySelect.innerHTML = '<option value="">Loading...</option>';

                fetch(BASE_URL + '/admin/catalog/subcategories/api-get-by-category?category_id=' + catId)
                    .then(res => res.json())
                    .then(data => {
                        subCategorySelect.innerHTML = '<option value="">None</option>';
                        data.forEach(sub => {
                            const option = document.createElement('option');
                            option.value = sub.id;
                            option.textContent = sub.name;
                            subCategorySelect.appendChild(option);
                        });
                        subCategorySelect.disabled = false;
                        fetchAttributes();
                        filterSizeCharts(catId, subCategorySelect.value);
                    })
                    .catch(err => {
                        console.error(err);
                        subCategorySelect.innerHTML = '<option value="">Error loading subcategories</option>';
                    });
            } else {
                fetchAttributes();
            }
        });
    }

    if (subCategorySelect) {
        subCategorySelect.addEventListener('change', function() {
            fetchAttributes();
            filterSizeCharts(categoryIdInput ? categoryIdInput.value : '', this.value);
        });
    }

    const selectedAttributes = <?= json_encode($selectedAttributes ?? []) ?>;
    const productImages = <?= json_encode($productImages ?? []) ?>;

    function escapeHtml(unsafe) {
        if (unsafe === 0 || unsafe === '0') return '0';
        return (unsafe || '').toString()
             .replace(/&/g, "&amp;")
             .replace(/</g, "&lt;")
             .replace(/>/g, "&gt;")
             .replace(/"/g, "&quot;")
             .replace(/'/g, "&#039;");
    }

    function getColorHex(colorName) {
        const colors = {
            'red': '#ef4444', 'blue': '#3b82f6', 'green': '#22c55e', 
            'yellow': '#eab308', 'orange': '#f97316', 'purple': '#a855f7',
            'pink': '#ec4899', 'black': '#000000', 'white': '#ffffff',
            'gray': '#6b7280', 'brown': '#D9A05B', 'nude': '#E5D3B3', 'wood': '#A06D41'
        };
        const key = colorName.toLowerCase().trim();
        return colors[key] || '#D9A05B'; 
    }

    function fetchAttributes() {
        const catId = categoryIdInput.value;
        const subCatId = document.getElementById('sub_category_id').value;
        if (!catId) return;

        attributesContainer.innerHTML = '<div class="text-sm text-gray-400">Loading specifications...</div>';

        let url = '/admin/catalog/attributes/api-get-by-category?category_id=' + catId;
        if (subCatId) url += '&sub_category_id=' + subCatId;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (noAttributesCheckbox && noAttributesCheckbox.checked) return;
                
                if (!data || data.length === 0) {
                    attributesContainer.innerHTML = `
                        <div class="bg-amber-50/60 border border-dashed border-amber-200 rounded-xl p-5 text-center my-2">
                            <div class="w-10 h-10 bg-amber-100 text-amber-700 rounded-full flex items-center justify-center mx-auto mb-2 font-bold text-lg">✨</div>
                            <p class="text-sm font-bold text-gray-800 mb-1">No custom specifications configured for this category yet.</p>
                            <p class="text-xs text-gray-500 mb-3">Add attributes like Size, Color, or Material to generate product variants, or check "No Attributes (Simple Product)".</p>
                            <button type="button" onclick="openAttributeModal()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-xs font-bold shadow-sm transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                + Add Specification Attribute
                            </button>
                        </div>
                    `;
                    return;
                }
                
                let html = '<div class="space-y-6">';
                
                data.forEach(attr => {
                    if (attr.show_on_product_form == 0) return;

                    const savedVals = selectedAttributes[attr.id] || [];
                    let req = attr.is_required ? 'required' : '';
                    let nameSingle = `attributes[${attr.id}]`;
                    let nameMultiple = `attributes[${attr.id}][]`;
                    let textVal = savedVals.length > 0 ? (savedVals[0].value_text || '') : '';
                    let escapedTextVal = escapeHtml(textVal);
                    let picklistIds = savedVals.map(v => parseInt(v.attribute_value_id)).filter(id => !isNaN(id));

                    html += `<div>`;
                    html += `<label class="block text-sm font-bold text-[#4A3C31] mb-3 capitalize">${attr.name} ${attr.is_required ? '<span class="text-red-500">*</span>' : ''}</label>`;
                    
                    const isColorAttr = attr.name.toLowerCase().includes('color');

                    if ((isColorAttr && (attr.input_type === 'checkbox' || attr.input_type === 'multiselect')) || attr.input_type === 'colorpicker') {
                        html += `<div class="flex flex-wrap gap-3">`;
                        if(attr.values) {
                            attr.values.forEach(v => {
                                let chk = picklistIds.includes(parseInt(v.id)) ? 'checked' : '';
                                let colorHex = v.color_code || getColorHex(v.value);
                                html += `
                                <label class="relative cursor-pointer flex items-center justify-center w-8 h-8" title="${v.value}">
                                    <input type="checkbox" name="${nameMultiple}" value="${v.id}" data-text="${escapeHtml(v.value)}" data-group="${escapeHtml(attr.name)}" data-attr-id="${attr.id}" ${chk} class="sr-only peer">
                                    <div class="absolute inset-0 rounded-full border-2 border-transparent peer-checked:border-[#c9080d] shadow-sm transition-all" style="background-color: ${colorHex}"></div>
                                    <svg class="relative w-4 h-4 text-white opacity-0 peer-checked:opacity-100 drop-shadow-md pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </label>`;
                            });
                        }
                        html += `</div>`;
                    } else if (attr.input_type === 'checkbox') {
                        html += `<div class="flex flex-wrap gap-4">`;
                        if(attr.values) {
                            attr.values.forEach(v => {
                                let chk = picklistIds.includes(parseInt(v.id)) ? 'checked' : '';
                                html += `
                                <label class="flex items-center space-x-2 cursor-pointer bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100 hover:bg-gray-100 transition-colors">
                                    <input type="checkbox" name="${nameMultiple}" value="${v.id}" data-text="${escapeHtml(v.value)}" data-group="${escapeHtml(attr.name)}" data-attr-id="${attr.id}" ${chk} class="w-4 h-4 text-brown border-gray-300 rounded focus:ring-brown">
                                    <span class="text-sm font-medium text-gray-700">${v.value}</span>
                                </label>`;
                            });
                        }
                        html += `</div>`;
                    } else {
                        let baseClass = "w-full border border-gray-200 rounded-xl focus:ring-brown focus:border-brown px-4 py-2 text-sm text-gray-700 bg-gray-50/50";
                        switch(attr.input_type) {
                            case 'dropdown':
                                html += `<select name="${nameSingle}" ${req} class="${baseClass} max-w-md"><option value="">Select...</option>`;
                                if(attr.values) {
                                    attr.values.forEach(v => {
                                        let sel = picklistIds.includes(parseInt(v.id)) ? 'selected' : '';
                                        html += `<option value="${v.id}" ${sel}>${v.value}</option>`;
                                    });
                                }
                                html += `</select>`;
                                break;
                            case 'textarea':
                                html += `<textarea name="${nameSingle}" ${req} class="${baseClass}" rows="3">${escapedTextVal}</textarea>`;
                                break;
                            default:
                                html += `<input type="text" name="${nameSingle}" value="${escapedTextVal}" ${req} class="${baseClass} max-w-md">`;
                        }
                    }
                    
                    html += `</div>`;
                });
                
                html += '</div>';
                attributesContainer.innerHTML = html;
                
                const existingVariantsForCheck = <?= json_encode($variants ?? []) ?>;
                if (existingVariantsForCheck && existingVariantsForCheck.length > 0) {
                    existingVariantsForCheck.forEach(v => {
                        if (v.attributes) {
                            Object.entries(v.attributes).forEach(([attrId, valId]) => {
                                const cb = document.querySelector(`#attributes-container input[type="checkbox"][data-attr-id="${attrId}"][value="${valId}"]`);
                                if (cb) {
                                    cb.checked = true;
                                }
                            });
                        }
                    });
                }

                generateVariants();

                // Auto-regenerate when admin checks/unchecks attribute values
                document.querySelectorAll('#attributes-container input[type="checkbox"]').forEach(cb => {
                    cb.addEventListener('change', function() {
                        clearTimeout(window._variantGenTimer);
                        window._variantGenTimer = setTimeout(generateVariants, 300);
                    });
                });
            })
            .catch(err => {
                console.error(err);
                attributesContainer.innerHTML = '<div class="text-sm text-red-500">Error loading specifications.</div>';
            });
    }

    function generateVariants() {
        const tableBody = document.getElementById('variant-table-body');
        const cardsView = document.getElementById('variant-cards-view');
        
        // Find all selected checkboxes inside attributes container
        const attributeGroups = {}; // key: attributeName, value: array of {id, val}
        const checkboxes = document.querySelectorAll('#attributes-container input[type="checkbox"]:checked');
        
        checkboxes.forEach(cb => {
            const text = cb.getAttribute('data-text') || cb.value;
            const groupName = cb.getAttribute('data-group') || 'Variant';
            const attrId = cb.getAttribute('data-attr-id');
            
            if (!attributeGroups[groupName]) attributeGroups[groupName] = [];
            attributeGroups[groupName].push({ id: cb.value, text: text, groupName: groupName, attrId: attrId });
        });
        
        const groupKeys = Object.keys(attributeGroups);
        if (groupKeys.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="3" class="px-6 py-4 text-center text-gray-500">No variant-defining attributes selected. Please select specifications above and click Refresh Variants.</td></tr>';
            cardsView.innerHTML = '<div class="text-center py-8 text-gray-500 text-sm border-2 border-dashed border-gray-200 rounded-xl">No variant-defining attributes selected. Please select specifications above and click Refresh Variants.</div>';
            return;
        }
        
        // Generate combinations
        const arrays = groupKeys.map(k => attributeGroups[k]);
        const combinations = arrays.reduce((a, b) => a.flatMap(d => b.map(e => [d, e].flat())), [[]]);
        
        let tableHtml = '';
        let cardsHtml = '<div class="space-y-6">';
        
        const existingVariants = <?= json_encode($variants ?? []) ?>;
        
        combinations.forEach((combo, index) => {
            const titleText = combo.map(c => c.text).join(' - ');
            const skuSuffix = combo.map(c => c.text.substring(0, 3).toUpperCase()).join('');
            const baseSku = document.querySelector('input[name="slug"]').value.toUpperCase().replace(/[^A-Z0-9]/g, '').substring(0, 5) || 'PROD';
            const sku = `${baseSku}-${skuSuffix}-${index + 1}`;
            
            // Try to match with an existing variant by exact attributes first
            let v = existingVariants.find(ev => {
                if (!ev.attributes) return false;
                return combo.every(c => String(ev.attributes[c.attrId]) === String(c.id));
            });
            
            // Fallback to SKU matching
            if (!v) {
                v = existingVariants.find(ev => ev.sku === sku);
            }

            // NOTE: No index-based fallback for v — that would assign wrong variant data/images.
            // For unmatched (new) variants, use null so fields default to empty/zero,
            // NOT existingVariants[0] which would bleed L/Orange data into L/Purple, etc.
            const templateV = v || null;

            const productBasePrice = document.querySelector('input[name="wholesale_price"]')?.value || 0;
            
            const basePrice = (templateV && templateV.base_price !== null && templateV.base_price !== undefined && templateV.base_price !== "") ? templateV.base_price : productBasePrice;
            const discountPrice = templateV ? (templateV.discount_price ?? '') : '';
            const discountPercent = templateV ? (templateV.discount_percent ?? '') : '';
            const inventory = (templateV && templateV.inventory !== null && templateV.inventory !== undefined) ? templateV.inventory : 0;
            const lowStockAlert = (templateV && templateV.low_stock_alert !== null && templateV.low_stock_alert !== undefined) ? templateV.low_stock_alert : 5;
            const actualSku = v ? (v.sku ?? sku) : sku;
            const barcode = v ? (v.barcode ?? '') : '';
            const weight = (templateV && templateV.weight !== null && templateV.weight !== undefined) ? parseFloat(templateV.weight) : '';

            const selectedCatOption = categoryIdInput ? categoryIdInput.options[categoryIdInput.selectedIndex] : null;
            const currentSgst = selectedCatOption ? (parseFloat(selectedCatOption.getAttribute('data-sgst')) || 0).toFixed(2) : '0.00';
            const currentCgst = selectedCatOption ? (parseFloat(selectedCatOption.getAttribute('data-cgst')) || 0).toFixed(2) : '0.00';

            const vSgst = templateV && templateV.sgst !== null && templateV.sgst !== undefined ? parseFloat(templateV.sgst).toFixed(2) : currentSgst;
            const vCgst = templateV && templateV.cgst !== null && templateV.cgst !== undefined ? parseFloat(templateV.cgst).toFixed(2) : currentCgst;
            const vTotalGst = templateV && templateV.total_gst !== null && templateV.total_gst !== undefined ? parseFloat(templateV.total_gst).toFixed(2) : (parseFloat(vSgst) + parseFloat(vCgst)).toFixed(2);

            const effPrice = (parseFloat(discountPrice) > 0) ? parseFloat(discountPrice) : (parseFloat(basePrice) || 0);
            const vMaxAmount = templateV && templateV.max_amount !== null && templateV.max_amount !== undefined && parseFloat(templateV.max_amount) > 0 ? parseFloat(templateV.max_amount).toFixed(2) : effPrice.toFixed(2);
            
            const inputPrefix = v && v.id ? `variants[${v.id}]` : `new_variants[${index}]`;
            const variantId = v && v.id ? v.id : 'new_' + index;
            
            let imgHtml = '';
            // Only show images when v is a confirmed exact match (attributes or SKU).
            // Never fall back to templateV images — that would show wrong variant's images.
            const variantImages = v && v.images ? v.images : [];
            if (variantImages.length > 0) {
                imgHtml += `<div class="grid grid-cols-4 gap-2">`;
                variantImages.slice(0, 4).forEach((imgPath, i) => {
                    const isAbsoluteUrl = imgPath.startsWith('http://') || imgPath.startsWith('https://');
                    const displayUrl = isAbsoluteUrl ? imgPath : '<?= BASE_URL ?>/' + imgPath.replace(/^\/+/, '');
                    imgHtml += `
                    <div class="relative rounded-lg overflow-hidden border border-brown shadow-sm p-0.5 bg-[#D9A05B]">
                        ${i === 0 ? '<span class="absolute top-0.5 left-0.5 bg-brown text-white text-[8px] px-1 rounded-sm uppercase tracking-wider z-10 flex items-center">Main</span>' : ''}
                        <div class="aspect-square bg-gray-100 rounded-md bg-cover bg-center" style="background-image: url('${escapeHtml(displayUrl)}')"></div>
                        <input type="hidden" name="${inputPrefix}[existing_image_paths][]" value="${escapeHtml(imgPath)}">
                        <button type="button" onclick="removeExistingVariantImage(this)" class="absolute top-0.5 right-0.5 bg-red-500 text-white rounded-full p-1 shadow-md hover:bg-red-600 transition-colors z-20">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>`;
                });
                imgHtml += `</div>`;
            }
            
            
            // Table Row
            tableHtml += `
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        <span class="font-bold text-gray-900 font-serif text-base">${titleText}</span>
                        ${index === 0 ? '<span class="ml-3 px-2 py-0.5 text-[10px] font-bold text-[#A1885E] bg-[#F7F2E8] rounded-full">Primary</span>' : ''}
                    </div>
                </td>
                <td class="px-6 py-4">
                    <input type="text" data-sync="${inputPrefix}[sku]" value="${escapeHtml(actualSku)}" class="w-48 text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-600 focus:ring-brown focus:border-brown bg-white shadow-sm sync-input">
                </td>
                <td class="px-6 py-4">
                    <input type="text" step="any" min="0.01" data-sync="${inputPrefix}[base_price]" value="${escapeHtml(basePrice)}" class="w-32 text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-600 focus:ring-brown focus:border-brown bg-white shadow-sm sync-input">
                </td>
                <td class="px-6 py-4">
                    <button type="button" onclick="this.closest('tr').remove(); document.getElementById('variant-card-${variantId}').remove();" class="text-red-500 hover:text-red-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </td>
            </tr>`;
            
            // Card HTML — NEW variants (no existing DB match) get blue accent, EXISTING get default style
            const isNew = !v;
            cardsHtml += `
            <div id="variant-card-${variantId}" class="bg-white rounded-2xl border ${isNew ? 'border-blue-300 ring-2 ring-blue-100' : 'border-cream'} shadow-sm overflow-hidden mb-6">
                <div class="p-5 border-b ${isNew ? 'border-blue-100 bg-blue-50/50' : 'border-gray-100 bg-gray-50/30'} flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <span class="font-bold text-gray-900 font-serif text-lg">${titleText}</span>
                        ${index === 0 && !isNew ? '<span class="px-2 py-0.5 text-[10px] font-bold text-[#A1885E] bg-[#F7F2E8] rounded-full uppercase tracking-wide">Primary</span>' : ''}
                        ${isNew ? '<span class="px-2 py-0.5 text-[10px] font-bold text-blue-700 bg-blue-100 rounded-full uppercase tracking-wide">+ New Variant</span>' : ''}
                        <div class="text-xs text-gray-400 mt-1 font-mono hidden md:block ml-2">${escapeHtml(actualSku)}</div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="px-3 py-1 text-xs font-bold ${isNew ? 'text-blue-700 bg-blue-100' : 'text-green-700 bg-green-100'} rounded-full">${isNew ? 'Will be Created' : 'Active'}</span>
                        <button type="button" onclick="this.closest('.bg-white').remove();" class="text-gray-400 hover:text-red-500 transition-colors" title="Remove this variant">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>
                
                <div class="p-6 flex flex-col gap-6 sm:flex-row">
                    <!-- Hidden combo data -->
                    <input type="hidden" name="${inputPrefix}[name]" value="${titleText}">
                    ${combo.map(c => `<input type="hidden" name="${inputPrefix}[attributes][${c.attrId}]" value="${c.id}">`).join('')}
                    <!-- Form Fields -->
                    <div class="flex-1 grid grid-cols-2 gap-x-4 gap-y-4 variant-card-container" style="min-width:0">
                        <div><label class="block text-xs font-bold text-gray-500 mb-1">Base Price <span class="text-red-500">*</span></label><input type="number" step="any" min="0.01" name="${inputPrefix}[base_price]" value="${escapeHtml(basePrice)}" required oninput="calculateGst(this)" class="variant-base-price w-full border border-gray-200 rounded-lg focus:ring-brown focus:border-brown px-3 py-2 text-sm text-gray-700 shadow-sm"></div>
                        <div><label class="block text-xs font-bold text-gray-500 mb-1">Discount Price</label><input type="number" step="0.01" name="${inputPrefix}[discount_price]" value="${escapeHtml(discountPrice)}" oninput="calculateGst(this)" class="variant-discount-price w-full border border-gray-200 rounded-lg focus:ring-brown focus:border-brown px-3 py-2 text-sm text-gray-700 shadow-sm" placeholder="e.g. 99.99"></div>
                        <div><label class="block text-xs font-bold text-gray-500 mb-1">SGST (%)</label><input type="number" step="0.01" name="${inputPrefix}[sgst]" value="${escapeHtml(vSgst)}" readonly class="variant-sgst w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 cursor-not-allowed text-gray-600 shadow-sm"></div>
                        <div><label class="block text-xs font-bold text-gray-500 mb-1">CGST (%)</label><input type="number" step="0.01" name="${inputPrefix}[cgst]" value="${escapeHtml(vCgst)}" readonly class="variant-cgst w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 cursor-not-allowed text-gray-600 shadow-sm"></div>
                        <div><label class="block text-xs font-bold text-gray-500 mb-1">Total GST (%)</label><input type="number" step="0.01" name="${inputPrefix}[total_gst]" value="${escapeHtml(vTotalGst)}" readonly class="variant-total-gst w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 cursor-not-allowed font-semibold text-gray-700 shadow-sm"></div>
                        <div><label class="block text-xs font-bold text-gray-500 mb-1">Total Amount (Incl. GST)</label><input type="number" step="0.01" name="${inputPrefix}[max_amount]" value="${escapeHtml(vMaxAmount)}" readonly class="variant-total-amount w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 cursor-not-allowed font-bold text-[#8C6B4D] shadow-sm"></div>
                        ${ <?= !empty($isCustomize) ? 'true' : 'false' ?> ? '' : `
                        <div><label class="block text-xs font-bold text-gray-500 mb-1">Inventory <span class="text-red-500">*</span></label><input type="number" name="${inputPrefix}[inventory]" value="${escapeHtml(inventory)}" required class="w-full border border-gray-200 rounded-lg focus:ring-brown focus:border-brown px-3 py-2 text-sm text-gray-700 shadow-sm"></div>
                        `}
                        <div><label class="block text-xs font-bold text-gray-500 mb-1">Low Stock Alert <span class="text-red-500">*</span></label><input type="number" name="${inputPrefix}[low_stock_alert]" value="${escapeHtml(lowStockAlert)}" required class="w-full border border-gray-200 rounded-lg focus:ring-brown focus:border-brown px-3 py-2 text-sm text-gray-700 shadow-sm"></div>
                        <div><label class="block text-xs font-bold text-gray-500 mb-1">SKU <span class="text-red-500">*</span></label><input type="text" name="${inputPrefix}[sku]" value="${escapeHtml(actualSku)}" required class="w-full border border-gray-200 rounded-lg focus:ring-brown focus:border-brown px-3 py-2 text-sm text-gray-700 shadow-sm"></div>
                        <div><label class="block text-xs font-bold text-gray-500 mb-1">Weight (kg) <span class="text-red-500">*</span></label><input type="text" step="0.0001" min="0.0001" name="${inputPrefix}[weight]" value="${escapeHtml(weight)}" required class="w-full border border-gray-200 rounded-lg focus:ring-brown focus:border-brown px-3 py-2 text-sm text-gray-700 shadow-sm"></div>
                    </div>
                    
                    <!-- Images -->
                    <div class="border-t sm:border-t-0 sm:border-l border-gray-100 pt-4 sm:pt-0 sm:pl-6" style="width:220px;flex-shrink:0">
                        <h4 class="text-sm font-bold text-[#4A3C31] font-serif mb-3 border-b border-gray-200 pb-2">Variant Images</h4>
                        <button type="button" onclick="openMediaSelector((rawUrl, fullUrl) => addVariantImage('${variantId}', rawUrl, '${inputPrefix}', fullUrl))" class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200 mb-3 flex items-center justify-center">
                            <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Add Media
                        </button>
                        <div id="variant-image-inputs-${variantId}"></div>
                        <div id="variant-image-preview-${variantId}" class="grid grid-cols-4 gap-2 mb-3"></div>
                        ${variantImages.length > 0 ? imgHtml : (isNew ? '<p class="text-xs text-gray-400 italic text-center py-2">No image selected yet</p>' : '')}
                    </div>
                </div>
            </div>`;
        });
        
        cardsHtml += '</div>';
        
        tableBody.innerHTML = tableHtml;
        cardsView.innerHTML = cardsHtml;
        
        bindSyncEvents();
        
        // Ensure state is correct if checkbox was checked
        if (noAttributesCheckbox && noAttributesCheckbox.checked) {
            toggleNoAttributes();
        }
    }

    function bindSyncEvents() {
        // Sync inputs between table and cards
        document.querySelectorAll('.sync-input').forEach(input => {
            // Remove existing listener to prevent duplicates
            const new_element = input.cloneNode(true);
            input.parentNode.replaceChild(new_element, input);
            
            new_element.addEventListener('input', function() {
                const targetName = this.getAttribute('data-sync');
                const target = document.querySelector(`input[name="${targetName}"]`);
                if (target) target.value = this.value;
            });
        });
        
        document.querySelectorAll('#variant-cards-view input').forEach(input => {
            const new_element = input.cloneNode(true);
            input.parentNode.replaceChild(new_element, input);
            
            new_element.addEventListener('input', function() {
                const name = this.getAttribute('name');
                const target = document.querySelector(`.sync-input[data-sync="${name}"]`);
                if (target) target.value = this.value;
            });
        });
    }

    function updateCategoryGstRates() {
        const selectedOption = categoryIdInput ? categoryIdInput.options[categoryIdInput.selectedIndex] : null;
        if (!selectedOption) return;
        const sgst = parseFloat(selectedOption.getAttribute('data-sgst')) || 0;
        const cgst = parseFloat(selectedOption.getAttribute('data-cgst')) || 0;
        const totalGst = sgst + cgst;

        document.querySelectorAll('.variant-sgst').forEach(el => el.value = sgst.toFixed(2));
        document.querySelectorAll('.variant-cgst').forEach(el => el.value = cgst.toFixed(2));
        document.querySelectorAll('.variant-total-gst').forEach(el => el.value = totalGst.toFixed(2));
        document.querySelectorAll('.variant-base-price').forEach(el => calculateGst(el));
    }

    window.calculateGst = function(inputEl) {
        const container = inputEl.closest('.variant-card-container') || inputEl.closest('.grid') || inputEl.closest('tr') || inputEl.closest('.p-6');
        if (!container) return;

        const basePriceEl = container.querySelector('.variant-base-price') || container.querySelector('input[name*="[base_price]"]');
        const discountPriceEl = container.querySelector('.variant-discount-price') || container.querySelector('input[name*="[discount_price]"]');
        const sgstEl = container.querySelector('.variant-sgst') || container.querySelector('input[name*="[sgst]"]');
        const cgstEl = container.querySelector('.variant-cgst') || container.querySelector('input[name*="[cgst]"]');
        const totalGstEl = container.querySelector('.variant-total-gst') || container.querySelector('input[name*="[total_gst]"]');
        const totalAmountEl = container.querySelector('.variant-total-amount') || container.querySelector('input[name*="[max_amount]"]');

        const basePrice = basePriceEl ? parseFloat(basePriceEl.value) || 0 : 0;
        const discountPrice = discountPriceEl ? parseFloat(discountPriceEl.value) || 0 : 0;
        const sgst = sgstEl ? parseFloat(sgstEl.value) || 0 : 0;
        const cgst = cgstEl ? parseFloat(cgstEl.value) || 0 : 0;
        const totalGst = sgst + cgst;

        if (totalGstEl) {
            totalGstEl.value = totalGst.toFixed(2);
        }

        const effectivePrice = discountPrice > 0 ? discountPrice : basePrice;
        const totalAmount = effectivePrice;

        if (totalAmountEl) {
            totalAmountEl.value = totalAmount > 0 ? totalAmount.toFixed(2) : '';
        }
    };

    categoryIdInput.addEventListener('change', function() {
        const catId = this.value;
        const subCategorySelect = document.getElementById('sub_category_id');
        
        updateCategoryGstRates();

        if (!catId) {
            subCategorySelect.innerHTML = '<option value="">Select Category first...</option>';
            subCategorySelect.disabled = true;
            subCategorySelect.classList.add('opacity-50');
            fetchAttributes();
            return;
        }

        subCategorySelect.disabled = true;
        subCategorySelect.classList.remove('opacity-50');
        subCategorySelect.innerHTML = '<option value="">Loading...</option>';

        fetch('/admin/catalog/subcategories/api-get-by-category?category_id=' + catId)
            .then(res => res.json())
            .then(data => {
                subCategorySelect.innerHTML = '<option value="">None</option>';
                data.forEach(sub => {
                    const option = document.createElement('option');
                    option.value = sub.id;
                    option.textContent = sub.name;
                    subCategorySelect.appendChild(option);
                });
                subCategorySelect.disabled = false;
                
                fetchAttributes();
            })
            .catch(err => {
                console.error(err);
                subCategorySelect.innerHTML = '<option value="">Error loading subcategories</option>';
            });
    });

    document.getElementById('sub_category_id').addEventListener('change', fetchAttributes);

    fetchAttributes();
    bindSyncEvents();

    // Initial GST recalculation for existing variants
    document.querySelectorAll('.variant-base-price').forEach(el => calculateGst(el));

    const btnRefresh = document.getElementById('btn-refresh-variants');
    if (btnRefresh) {
        btnRefresh.addEventListener('click', generateVariants);
    }

    // Toggle Variant Views
    const btnTable = document.getElementById('view-table');
    const btnCards = document.getElementById('view-cards');
    const viewTable = document.getElementById('variant-table-view');
    const viewCards = document.getElementById('variant-cards-view');

    if (btnTable && btnCards) {
        btnTable.addEventListener('click', function() {
            btnTable.classList.replace('text-gray-500', 'text-brown');
            btnTable.classList.add('bg-cream', 'border', 'border-cream');
            btnTable.classList.remove('hover:text-gray-700');
            
            btnCards.classList.replace('text-brown', 'text-gray-500');
            btnCards.classList.remove('bg-cream', 'border', 'border-cream');
            btnCards.classList.add('hover:text-gray-700');
            
            viewTable.classList.remove('hidden');
            viewCards.classList.add('hidden');
        });
        
        btnCards.addEventListener('click', function() {
            btnCards.classList.replace('text-gray-500', 'text-brown');
            btnCards.classList.add('bg-cream', 'border', 'border-cream');
            btnCards.classList.remove('hover:text-gray-700');
            
            btnTable.classList.replace('text-brown', 'text-gray-500');
            btnTable.classList.remove('bg-cream', 'border', 'border-cream');
            btnTable.classList.add('hover:text-gray-700');
            
            viewCards.classList.remove('hidden');
            viewTable.classList.add('hidden');
        });
    }

    // Form submit validation
    const productForm = document.querySelector('form[action*="/admin/catalog/products/update"]');
    if (productForm) {
        productForm.addEventListener('submit', function(e) {
            const variantCards = document.querySelectorAll('#variant-cards-view .variant-card-container, #variant-cards-view > .space-y-6 > div');
            
            // If cards are hidden or rendered, validate all active variant inputs
            const priceInputs = document.querySelectorAll('input[name*="[base_price]"]');
            const weightInputs = document.querySelectorAll('input[name*="[weight]"]');

            if (priceInputs.length === 0) {
                e.preventDefault();
                alert('At least one product variant with pricing and weight details is required.');
                return false;
            }

            let hasError = false;
            priceInputs.forEach((priceInput, idx) => {
                if (hasError) return;
                const price = parseFloat(priceInput.value);
                if (isNaN(price) || price <= 0) {
                    e.preventDefault();
                    alert(`Base Price is required and must be greater than 0 for variant #${idx + 1}.`);
                    priceInput.focus();
                    hasError = true;
                    return;
                }
            });

            if (hasError) return false;

            weightInputs.forEach((weightInput, idx) => {
                if (hasError) return;
                const weight = parseFloat(weightInput.value);
                if (isNaN(weight) || weight <= 0) {
                    e.preventDefault();
                    alert(`Weight is required and must be greater than 0 kg for variant #${idx + 1}.`);
                    weightInput.focus();
                    hasError = true;
                    return;
                }
            });

            if (hasError) return false;
        });
    }
});
</script>

<script>
    // Primary Image Preview functionality
    window.setPrimaryImage = function(url) {
        document.getElementById('primary_image_input_hidden').value = url;
        document.getElementById('image_preview').src = url;
        document.getElementById('image_preview_container_outer').classList.remove('hidden');
        document.getElementById('image_preview_container_outer').classList.add('flex');
    };

    window.addVariantImage = function(index, rawUrl, prefix, fullUrl) {
        const inputsContainer = document.getElementById('variant-image-inputs-' + index);
        const previewContainer = document.getElementById('variant-image-preview-' + index);
        const card = document.getElementById('variant-card-' + index);
        
        let existingCount = 0;
        if (card) {
            existingCount = card.querySelectorAll('.existing-variant-image-tile').length;
        } else {
            existingCount = document.querySelectorAll('.existing-variant-image-tile').length;
        }
        const newCount = inputsContainer ? inputsContainer.children.length : 0;
        
        if ((existingCount + newCount) >= 4) {
            alert('Maximum 4 images allowed per variant.');
            return;
        }

        const inputId = 'img_' + Math.random().toString(36).substr(2, 9);
        const actualPrefix = prefix || `variants[${index}]`;
        const displayUrl = fullUrl || ((rawUrl.startsWith('http://') || rawUrl.startsWith('https://')) ? rawUrl : ('<?= BASE_URL ?>/' + rawUrl.replace(/^\/+/, '')));

        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = `${actualPrefix}[image_paths][]`;
        hiddenInput.value = rawUrl;
        hiddenInput.id = 'input_' + inputId;
        if (inputsContainer) inputsContainer.appendChild(hiddenInput);

        const div = document.createElement('div');
        div.className = 'relative group aspect-square rounded-lg overflow-hidden border border-brown shadow-sm p-0.5 bg-[#D9A05B]';
        div.id = 'preview_' + inputId;
        div.innerHTML = `<div class="aspect-square bg-gray-100 rounded-md bg-cover bg-center" style="background-image: url('${displayUrl}')"></div>
        <button type="button" onclick="removeVariantImage('${inputId}')" class="absolute top-0.5 right-0.5 bg-red-500 text-white rounded-full p-1 shadow-md hover:bg-red-600 transition-colors z-20">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>`;
        if (previewContainer) previewContainer.appendChild(div);
    };

    window.removeVariantImage = function(inputId) {
        const input = document.getElementById('input_' + inputId);
        const preview = document.getElementById('preview_' + inputId);
        if (input) input.remove();
        if (preview) preview.remove();
    };

    // Custom Dynamic Description Fields
    let customFieldCount = <?= !empty($existingCustomFields) ? count($existingCustomFields) : 0 ?>;
    window.addCustomField = function(fieldName = '', fieldValue = '') {
        const container = document.getElementById('custom-fields-container');
        if (!container) return;
        const index = customFieldCount++;
        const row = document.createElement('div');
        row.className = 'custom-field-row flex items-start gap-3 p-3.5 bg-gray-50/80 border border-gray-200 rounded-xl transition-all duration-200 hover:border-gray-300 shadow-2xs';
        row.id = `custom-field-${index}`;
        row.innerHTML = `
            <div class="w-1/3 min-w-[140px]">
                <input type="text" name="custom_fields[${index}][name]" value="${fieldName.replace(/"/g, '&quot;')}" placeholder="Field Name" class="w-full border border-gray-300 rounded-lg focus:ring-brand-500 focus:border-brand-500 px-3 py-2 text-sm bg-white font-medium text-gray-800">
            </div>
            <div class="flex-1">
                <textarea name="custom_fields[${index}][value]" rows="2" placeholder="Field details / content..." class="w-full border border-gray-300 rounded-lg focus:ring-brand-500 focus:border-brand-500 px-3 py-2 text-sm bg-white text-gray-800 resize-y">${fieldValue}</textarea>
            </div>
            <button type="button" onclick="this.closest('.custom-field-row').remove()" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors mt-0.5" title="Delete Field">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        `;
        container.appendChild(row);
        const nameInput = row.querySelector('input');
        if (nameInput && !fieldName) {
            nameInput.focus();
        }
    };

    window.removeExistingVariantImage = function(btn) {
        const tile = btn.closest('.relative');
        if (tile) {
            const hidden = tile.querySelector('input[type="hidden"]');
            if (hidden) hidden.remove();
            tile.remove();
        }
    };

    window.removePrimaryImage = function(btn, productId, imagePath) {
        const hiddenInput = document.getElementById('primary_image_path');
        const outerContainer = document.getElementById('image_preview_container_outer');
        
        if (hiddenInput) hiddenInput.value = '';
        if (outerContainer) {
            outerContainer.classList.add('hidden');
            outerContainer.classList.remove('flex');
        }
        
        if (imagePath && productId) {
            deleteImage(btn, productId, imagePath);
        }
    };

    function deleteImage(btn, productId, imagePath) {
        if (!confirm('Are you sure you want to remove this image?')) return;
        fetch('<?= BASE_URL ?>/admin/catalog/products/delete-image', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: productId, image_path: imagePath })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                btn.closest('.relative').remove();
            } else {
                alert('Error removing image.');
            }
        });
    }
</script>

<?php include __DIR__ . '/../../media/_selector_modal.php'; ?>
<?php include __DIR__ . '/../_category_request_modal.php'; ?>
<?php include __DIR__ . '/../_attribute_request_modal.php'; ?>
