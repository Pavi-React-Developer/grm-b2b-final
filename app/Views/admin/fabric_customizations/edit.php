<div class="p-6 max-w-5xl mx-auto">
    <!-- Breadcrumb & Header -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-amber-600 mb-1">
            <a href="<?= BASE_URL ?>/admin/fabric-customizations" class="hover:underline">Fabric Rules</a>
            <span>/</span>
            <span>Edit Rule #<?= $rule['id'] ?></span>
        </div>
        <h1 class="text-2xl font-black text-gray-900 font-display">
            ✏️ Edit Fabric Rule: <?= htmlspecialchars($rule['program_name']) ?>
        </h1>
        <p class="text-sm text-gray-500 mt-1">Update size consumption rates, minimum stitch pieces, and wastage factors.</p>
    </div>

    <form action="<?= BASE_URL ?>/admin/fabric-customizations/update" method="POST" id="ruleForm" class="space-y-6">
        <input type="hidden" name="id" value="<?= $rule['id'] ?>">

        <!-- Section 1: Fabric Selection & Identity -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-xs space-y-5">
            <h2 class="text-base font-bold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                <span class="w-7 h-7 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center text-sm font-black">1</span>
                <span>Fabric & Program Details</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Fabric Dropdown -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Wholesale Fabric Product <span class="text-red-500">*</span>
                    </label>
                    <select name="fabric_id" id="fabricSelect" required class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 font-medium">
                        <?php foreach ($products as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= $p['id'] == $rule['fabric_id'] ? 'selected' : '' ?> data-stock="<?= $p['stock_quantity'] ?>" data-price="<?= $p['wholesale_price'] ?>" data-sku="<?= htmlspecialchars($p['base_sku']) ?>" data-cat="<?= $p['category_id'] ?>" data-sub="<?= $p['sub_category_id'] ?>">
                                <?= htmlspecialchars($p['name']) ?> (SKU: <?= htmlspecialchars($p['base_sku']) ?> | Stock: <?= (int)$p['stock_quantity'] ?> Units | ₹<?= number_format($p['wholesale_price'], 2) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Program Name -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Program Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="program_name" value="<?= htmlspecialchars($rule['program_name']) ?>" required class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 font-medium">
                </div>

                <!-- Garment Type -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Garment Type <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="garment_type" value="<?= htmlspecialchars($rule['garment_type']) ?>" required class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 font-medium">
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Category
                    </label>
                    <select name="category_id" id="categorySelect" class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                        <option value="">-- Optional Category --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $rule['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Sub Category -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Sub Category
                    </label>
                    <select name="sub_category_id" id="subCategorySelect" class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                        <option value="">-- Optional Sub Category --</option>
                        <?php foreach ($subCategories as $sc): ?>
                            <option value="<?= $sc['id'] ?>" <?= $sc['id'] == $rule['sub_category_id'] ? 'selected' : '' ?> data-cat="<?= $sc['category_id'] ?>"><?= htmlspecialchars($sc['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Feature Type (Multi-Select) -->
                <?php
                $currentFeatures = [];
                if (!empty($rule['feeding_type'])) {
                    if (strpos($rule['feeding_type'], '[') === 0) {
                        $decoded = json_decode($rule['feeding_type'], true);
                        $currentFeatures = is_array($decoded) ? $decoded : [$rule['feeding_type']];
                    } else {
                        $currentFeatures = array_filter(array_map('trim', explode(',', $rule['feeding_type'])));
                    }
                }
                if (empty($currentFeatures)) {
                    $currentFeatures = ['Non-Feeding'];
                }
                ?>
                <div class="relative">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider flex items-center gap-1.5">
                            <span>Feature Type</span>
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-amber-50 text-amber-800 font-bold border border-amber-200">Multi-Select</span>
                        </label>
                        <button type="button" onclick="openAddFeedingOptionModal()" class="text-xs font-bold text-amber-700 hover:text-amber-800 bg-amber-50 hover:bg-amber-100 px-2 py-0.5 rounded-md border border-amber-200 transition-colors flex items-center gap-1 shadow-2xs cursor-pointer">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            + Add / Manage Options
                        </button>
                    </div>

                    <!-- Multi-Select Trigger Box -->
                    <div id="featureMultiSelectBox" onclick="toggleFeatureDropdown(event)" class="min-h-[42px] w-full px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-xl hover:border-amber-400 focus-within:bg-white focus-within:ring-2 focus-within:ring-amber-500/20 focus-within:border-amber-500 cursor-pointer flex flex-wrap items-center gap-1.5 transition-all">
                        <div id="selectedFeatureBadges" class="flex flex-wrap items-center gap-1.5">
                            <!-- Selected pills will be rendered here -->
                        </div>
                        <span id="featurePlaceholderText" class="text-xs text-gray-400 font-medium py-1">Select feature types...</span>
                        <div class="ml-auto text-gray-400 pl-1">
                            <svg class="w-4 h-4 transform transition-transform" id="featureDropdownArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>

                    <!-- Hidden inputs container for form submission -->
                    <div id="featureHiddenInputs">
                        <?php foreach ($currentFeatures as $cf): ?>
                            <input type="hidden" name="feeding_type[]" value="<?= htmlspecialchars($cf) ?>">
                        <?php endforeach; ?>
                    </div>

                    <!-- Dropdown Menu -->
                    <div id="featureDropdownMenu" class="hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-gray-200 rounded-xl shadow-xl z-50 p-3 max-h-72 overflow-y-auto space-y-3">
                        <!-- Standard Types -->
                        <div>
                            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Standard Types</div>
                            <div class="space-y-1">
                                <label class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg hover:bg-amber-50 cursor-pointer text-xs font-semibold text-gray-800 select-none">
                                    <input type="checkbox" value="Non-Feeding" onchange="toggleFeatureOption(this.value, this.checked)" class="feature-checkbox rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                                    <span>Non-Feeding (Standard)</span>
                                </label>
                                <label class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg hover:bg-amber-50 cursor-pointer text-xs font-semibold text-gray-800 select-none">
                                    <input type="checkbox" value="Feeding" onchange="toggleFeatureOption(this.value, this.checked)" class="feature-checkbox rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                                    <span>Feeding (Maternity / Zips)</span>
                                </label>
                            </div>
                        </div>

                        <!-- Sleeve & Cut Types -->
                        <div class="border-t border-gray-100 pt-2">
                            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Sleeve & Cut Types</div>
                            <div class="space-y-1" id="sleeveOptionsContainer">
                                <label class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg hover:bg-amber-50 cursor-pointer text-xs font-semibold text-gray-800 select-none">
                                    <input type="checkbox" value="Sleeve: Short Sleeve" onchange="toggleFeatureOption(this.value, this.checked)" class="feature-checkbox rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                                    <span>Sleeve: Short Sleeve</span>
                                </label>
                                <label class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg hover:bg-amber-50 cursor-pointer text-xs font-semibold text-gray-800 select-none">
                                    <input type="checkbox" value="Sleeve: 3/4th Sleeve" onchange="toggleFeatureOption(this.value, this.checked)" class="feature-checkbox rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                                    <span>Sleeve: 3/4th Sleeve</span>
                                </label>
                                <label class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg hover:bg-amber-50 cursor-pointer text-xs font-semibold text-gray-800 select-none">
                                    <input type="checkbox" value="Sleeve: Full Sleeve" onchange="toggleFeatureOption(this.value, this.checked)" class="feature-checkbox rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                                    <span>Sleeve: Full Sleeve</span>
                                </label>
                                <label class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg hover:bg-amber-50 cursor-pointer text-xs font-semibold text-gray-800 select-none">
                                    <input type="checkbox" value="Sleeve: Sleeveless" onchange="toggleFeatureOption(this.value, this.checked)" class="feature-checkbox rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                                    <span>Sleeve: Sleeveless</span>
                                </label>
                            </div>
                        </div>

                        <!-- Custom Added Options -->
                        <div class="border-t border-gray-100 pt-2" id="customOptionsSection">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Custom Added Options</span>
                            </div>
                            <div class="space-y-1" id="customCheckboxesContainer">
                                <!-- Populated dynamically with checkboxes and delete icon -->
                            </div>
                        </div>

                        <div class="border-t border-gray-100 pt-2">
                            <button type="button" onclick="openAddFeedingOptionModal()" class="w-full py-1.5 text-center text-xs font-bold text-amber-700 hover:bg-amber-50 rounded-lg border border-dashed border-amber-200 transition-colors cursor-pointer">
                                + Add Custom Feature Type...
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Required Pieces to Enable Payment -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Required Pieces to Enable Payment <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" name="minimum_pieces" value="<?= isset($rule['minimum_pieces']) ? (int)$rule['minimum_pieces'] : 10 ?>" min="1" required placeholder="e.g. 10" class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 font-bold text-gray-900 pr-14">
                        <span class="absolute right-3.5 top-2.5 text-xs text-gray-400 font-semibold">Pcs</span>
                    </div>
                    <p class="text-[11px] text-gray-500 mt-1">
                        Minimum total stitch pieces buyer must select before the online payment button is enabled on storefront.
                    </p>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Rule Status
                    </label>
                    <select name="status" class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 font-medium">
                        <option value="active" <?= $rule['status'] === 'active' ? 'selected' : '' ?>>Active (Visible on Storefront)</option>
                        <option value="inactive" <?= $rule['status'] === 'inactive' ? 'selected' : '' ?>>Inactive (Draft / Hidden)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Section 2: Size Variants & Quantity Allocation -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-xs space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-3">
                <div>
                    <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center text-sm font-black">2</span>
                        <span>Fabric Size Variants & Quantity Limits</span>
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Define sizes, default buy quantities (e.g. 2 pcs), and maximum allowed pieces per size (e.g. max 4 pcs for 4XL).
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="addCustomSizeVariant()" class="px-3.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-bold rounded-lg border border-amber-200 transition-colors flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Add Size Variant</span>
                    </button>
                </div>
            </div>

            <!-- Quick Text Parser & Presets -->
            <div class="bg-amber-50/50 p-4 rounded-xl border border-amber-100 space-y-3">
                <!-- Text input parser -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                    <div class="relative flex-1">
                        <input type="text" id="quickShorthandInput" placeholder="Quick paste e.g. M-2, L-2, XL-2, XXL-2, 3XL-2, 4XL-4 (or M:2/4, L:2/4)" class="w-full px-3.5 py-2 text-xs bg-white border border-amber-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 font-mono text-gray-800">
                    </div>
                    <button type="button" onclick="applyShorthandInput()" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-lg transition-colors shadow-2xs cursor-pointer flex items-center justify-center gap-1.5 shrink-0">
                        <span>⚡ Parse & Generate</span>
                    </button>
                </div>

                <!-- Presets Buttons -->
                <div class="flex flex-wrap items-center gap-2 pt-1 border-t border-amber-200/60">
                    <span class="text-[11px] font-bold text-amber-900 uppercase tracking-wider mr-1">Quick Presets:</span>
                    <button type="button" onclick="applySizeVariantPreset('standard_m_4xl')" class="px-2.5 py-1 bg-white hover:bg-amber-100 text-amber-900 text-xs font-bold rounded-lg border border-amber-200 transition-all shadow-2xs cursor-pointer">
                        + Standard (M to 4XL: Base 2 / Max 4)
                    </button>
                    <button type="button" onclick="applySizeVariantPreset('all_s_xxl')" class="px-2.5 py-1 bg-white hover:bg-amber-100 text-amber-900 text-xs font-bold rounded-lg border border-amber-200 transition-all shadow-2xs cursor-pointer">
                        + S to XXL (Base 2 / Max 4)
                    </button>
                    <button type="button" onclick="applySizeVariantPreset('plus_3xl_6xl')" class="px-2.5 py-1 bg-white hover:bg-amber-100 text-amber-900 text-xs font-bold rounded-lg border border-amber-200 transition-all shadow-2xs cursor-pointer">
                        + Plus (3XL to 6XL: Base 2 / Max 4)
                    </button>
                    <button type="button" onclick="applySizeVariantPreset('freesize')" class="px-2.5 py-1 bg-white hover:bg-amber-100 text-amber-900 text-xs font-bold rounded-lg border border-amber-200 transition-all shadow-2xs cursor-pointer">
                        + Free Size (Base 2 / Max 10)
                    </button>
                    <button type="button" onclick="clearAllSizeVariants()" class="px-2.5 py-1 bg-white hover:bg-red-50 text-red-600 text-xs font-bold rounded-lg border border-red-200 transition-all shadow-2xs ml-auto cursor-pointer">
                        Clear All
                    </button>
                </div>
            </div>

            <!-- Size Variant Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm" id="sizesTable">
                    <thead>
                        <tr class="text-[11px] uppercase font-bold text-gray-400 border-b border-gray-100">
                            <th class="py-2.5 px-3">Size Variant</th>
                            <th class="py-2.5 px-3">Default / Base Qty</th>
                            <th class="py-2.5 px-3">Max Allowed Qty (Buyer Limit)</th>
                            <th class="py-2.5 px-3 text-center">Status</th>
                            <th class="py-2.5 px-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50" id="sizesTbody">
                        <?php 
                        $sizesList = !empty($rule['sizes']) ? $rule['sizes'] : [];
                        foreach ($sizesList as $idx => $ds): 
                            $baseQty = isset($ds['base_quantity']) ? (int)$ds['base_quantity'] : 2;
                            $maxQty = isset($ds['max_quantity']) ? (int)$ds['max_quantity'] : 4;
                        ?>
                            <tr class="size-row group">
                                <td class="py-2.5 px-3">
                                    <input type="text" name="sizes[<?= $idx ?>][size_name]" value="<?= htmlspecialchars($ds['size_name']) ?>" required placeholder="e.g. M" class="size-name-input w-28 px-3 py-1.5 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:bg-white font-bold text-gray-800">
                                </td>
                                <td class="py-2.5 px-3">
                                    <div class="flex items-center gap-2">
                                        <input type="number" name="sizes[<?= $idx ?>][base_quantity]" value="<?= $baseQty ?>" min="0" required class="size-base-qty-input w-28 px-3 py-1.5 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:bg-white font-bold text-gray-800" oninput="updateVariantSummary()">
                                        <span class="text-xs text-gray-400 font-semibold">pcs</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3">
                                    <div class="flex items-center gap-2">
                                        <input type="number" name="sizes[<?= $idx ?>][max_quantity]" value="<?= $maxQty ?>" min="1" required class="size-max-qty-input w-28 px-3 py-1.5 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:bg-white font-bold text-amber-700" oninput="updateVariantSummary()">
                                        <span class="text-xs text-gray-400 font-semibold">max pcs</span>
                                    </div>
                                </td>
                                <td class="py-2.5 px-3 text-center">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="sizes[<?= $idx ?>][enabled]" value="1" <?= !empty($ds['enabled']) ? 'checked' : '' ?> class="sr-only peer" onchange="updateVariantSummary()">
                                        <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-amber-600"></div>
                                    </label>
                                </td>
                                <td class="py-2.5 px-3 text-right">
                                    <button type="button" onclick="removeSizeVariantRow(this)" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors cursor-pointer" title="Delete variant">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Summary Bar -->
            <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-200/80 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-4">
                    <span>Active Size Variants: <strong class="text-gray-900" id="adminActiveVariantsCount"><?= count(array_filter($sizesList, fn($s) => !empty($s['enabled']))) ?></strong></span>
                    <span>•</span>
                    <span>Total Base Pieces: <strong class="text-amber-800" id="adminTotalBasePieces"><?= array_sum(array_map(fn($s) => (int)($s['base_quantity'] ?? 2), array_filter($sizesList, fn($s) => !empty($s['enabled'])))) ?></strong> pcs</span>
                </div>
                <div class="text-gray-500 italic">
                    Buyers can adjust quantities up to the configured Max Allowed Limit for each size.
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-3 pt-4">
            <a href="<?= BASE_URL ?>/admin/fabric-customizations" class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-bold rounded-xl transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white text-sm font-bold rounded-xl shadow-sm hover:shadow transition-all">
                Update Fabric Rule
            </button>
        </div>
    </form>
</div>

<script>
let sizeIndex = <?= count($sizesList) ?>;

function addCustomSizeVariant(sizeName = '', baseQty = 2, maxQty = 4, enabled = true) {
    const tbody = document.getElementById('sizesTbody');
    const tr = document.createElement('tr');
    tr.className = 'size-row group';
    tr.innerHTML = `
        <td class="py-2.5 px-3">
            <input type="text" name="sizes[${sizeIndex}][size_name]" value="${escapeHtmlStr(sizeName)}" required placeholder="e.g. M" class="size-name-input w-28 px-3 py-1.5 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:bg-white font-bold text-gray-800">
        </td>
        <td class="py-2.5 px-3">
            <div class="flex items-center gap-2">
                <input type="number" name="sizes[${sizeIndex}][base_quantity]" value="${baseQty}" min="0" required class="size-base-qty-input w-28 px-3 py-1.5 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:bg-white font-bold text-gray-800" oninput="updateVariantSummary()">
                <span class="text-xs text-gray-400 font-semibold">pcs</span>
            </div>
        </td>
        <td class="py-2.5 px-3">
            <div class="flex items-center gap-2">
                <input type="number" name="sizes[${sizeIndex}][max_quantity]" value="${maxQty}" min="1" required class="size-max-qty-input w-28 px-3 py-1.5 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:bg-white font-bold text-amber-700" oninput="updateVariantSummary()">
                <span class="text-xs text-gray-400 font-semibold">max pcs</span>
            </div>
        </td>
        <td class="py-2.5 px-3 text-center">
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="sizes[${sizeIndex}][enabled]" value="1" ${enabled ? 'checked' : ''} class="sr-only peer" onchange="updateVariantSummary()">
                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-amber-600"></div>
            </label>
        </td>
        <td class="py-2.5 px-3 text-right">
            <button type="button" onclick="removeSizeVariantRow(this)" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors cursor-pointer" title="Delete variant">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
    sizeIndex++;
    updateVariantSummary();
}

function removeSizeVariantRow(btn) {
    const rows = document.querySelectorAll('.size-row');
    if (rows.length <= 1) {
        alert('You must have at least one size variant configured.');
        return;
    }
    btn.closest('tr').remove();
    updateVariantSummary();
}

function clearAllSizeVariants() {
    if (confirm('Clear all size variant rows?')) {
        document.getElementById('sizesTbody').innerHTML = '';
        addCustomSizeVariant('M', 2, 4);
    }
}

function applySizeVariantPreset(type) {
    const presets = {
        'standard_m_4xl': [
            { name: 'M', base: 2, max: 4 },
            { name: 'L', base: 2, max: 4 },
            { name: 'XL', base: 2, max: 4 },
            { name: 'XXL', base: 2, max: 4 },
            { name: '3XL', base: 2, max: 4 },
            { name: '4XL', base: 2, max: 4 }
        ],
        'all_s_xxl': [
            { name: 'S', base: 2, max: 4 },
            { name: 'M', base: 2, max: 4 },
            { name: 'L', base: 2, max: 4 },
            { name: 'XL', base: 2, max: 4 },
            { name: 'XXL', base: 2, max: 4 }
        ],
        'plus_3xl_6xl': [
            { name: '3XL', base: 2, max: 4 },
            { name: '4XL', base: 2, max: 4 },
            { name: '5XL', base: 2, max: 4 },
            { name: '6XL', base: 2, max: 4 }
        ],
        'freesize': [
            { name: 'Free Size', base: 2, max: 10 }
        ]
    };

    const items = presets[type];
    if (!items) return;

    document.getElementById('sizesTbody').innerHTML = '';
    sizeIndex = 0;
    items.forEach(it => {
        addCustomSizeVariant(it.name, it.base, it.max, true);
    });
}

function applyShorthandInput() {
    const input = document.getElementById('quickShorthandInput');
    const val = input.value.trim();
    if (!val) {
        alert('Please enter shorthand sizes (e.g. M-2, L-2, XL-2, XXL-2, 3XL-2, 4XL-4)');
        return;
    }

    // Split by comma or space
    const tokens = val.split(/[,;\s]+/).map(t => t.trim()).filter(Boolean);
    if (tokens.length === 0) return;

    document.getElementById('sizesTbody').innerHTML = '';
    sizeIndex = 0;

    tokens.forEach(tok => {
        let name = tok;
        let base = 2;
        let max = 4;

        if (tok.includes(':')) {
            const parts = tok.split(':');
            name = parts[0].trim();
            if (parts[1]) {
                if (parts[1].includes('/')) {
                    const qParts = parts[1].split('/');
                    base = parseInt(qParts[0]) || 2;
                    max = parseInt(qParts[1]) || (base * 2);
                } else {
                    base = parseInt(parts[1]) || 2;
                    max = base * 2;
                }
            }
        } else if (tok.includes('-')) {
            const parts = tok.split('-');
            if (parts.length >= 3) {
                name = parts.slice(0, -2).join('-');
                base = parseInt(parts[parts.length - 2]) || 2;
                max = parseInt(parts[parts.length - 1]) || (base * 2);
            } else if (parts.length === 2) {
                name = parts[0].trim();
                base = parseInt(parts[1]) || 2;
                max = Math.max(4, base * 2);
            }
        }

        if (name) {
            addCustomSizeVariant(name.toUpperCase(), base, max, true);
        }
    });

    input.value = '';
}

function updateVariantSummary() {
    const rows = document.querySelectorAll('.size-row');
    let active = 0;
    let totalBase = 0;

    rows.forEach(r => {
        const chk = r.querySelector('input[type="checkbox"]');
        const baseNum = parseInt(r.querySelector('.size-base-qty-input')?.value) || 0;
        if (chk && chk.checked) {
            active++;
            totalBase += baseNum;
        }
    });

    const activeEl = document.getElementById('adminActiveVariantsCount');
    const totalEl = document.getElementById('adminTotalBasePieces');
    if (activeEl) activeEl.textContent = active;
    if (totalEl) totalEl.textContent = totalBase;
}

// Auto select category/sub if fabric changes
document.getElementById('fabricSelect').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    if (opt) {
        const catId = opt.getAttribute('data-cat');
        const subId = opt.getAttribute('data-sub');
        if (catId) {
            document.getElementById('categorySelect').value = catId;
        }
        if (subId) {
            document.getElementById('subCategorySelect').value = subId;
        }
    }
});

// --- FEATURE TYPE MULTI-SELECT SYSTEM ---
let selectedFeatures = <?= json_encode(array_values($currentFeatures)) ?>;

function toggleFeatureDropdown(e) {
    if (e && e.target && e.target.closest('.feature-badge-remove')) {
        return;
    }
    const menu = document.getElementById('featureDropdownMenu');
    const arrow = document.getElementById('featureDropdownArrow');
    const isHidden = menu.classList.contains('hidden');
    if (isHidden) {
        menu.classList.remove('hidden');
        if (arrow) arrow.classList.add('rotate-180');
    } else {
        menu.classList.add('hidden');
        if (arrow) arrow.classList.remove('rotate-180');
    }
}

// Close dropdown on outside click
document.addEventListener('click', function(e) {
    const box = document.getElementById('featureMultiSelectBox');
    const menu = document.getElementById('featureDropdownMenu');
    const modal = document.getElementById('addFeedingOptionModal');
    if (!box || !menu) return;
    if (!box.contains(e.target) && !menu.contains(e.target) && (!modal || !modal.contains(e.target))) {
        menu.classList.add('hidden');
        const arrow = document.getElementById('featureDropdownArrow');
        if (arrow) arrow.classList.remove('rotate-180');
    }
});

function toggleFeatureOption(value, isChecked) {
    if (!value) return;
    if (isChecked) {
        if (!selectedFeatures.includes(value)) {
            selectedFeatures.push(value);
        }
    } else {
        selectedFeatures = selectedFeatures.filter(v => v !== value);
    }
    renderSelectedFeatureBadges();
    renderFeatureHiddenInputs();
    syncFeatureCheckboxes();
}

function removeSelectedFeature(value, e) {
    if (e) e.stopPropagation();
    toggleFeatureOption(value, false);
}

function renderSelectedFeatureBadges() {
    const container = document.getElementById('selectedFeatureBadges');
    const placeholder = document.getElementById('featurePlaceholderText');
    if (!container) return;

    if (selectedFeatures.length === 0) {
        container.innerHTML = '';
        if (placeholder) placeholder.classList.remove('hidden');
        return;
    }

    if (placeholder) placeholder.classList.add('hidden');

    let html = '';
    selectedFeatures.forEach(feat => {
        const escaped = escapeHtmlStr(feat);
        const encoded = encodeURIComponent(feat);
        html += `
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-100 text-amber-900 text-xs font-bold border border-amber-200 shadow-2xs">
            <span>${escaped}</span>
            <button type="button" onclick="removeSelectedFeature(decodeURIComponent('${encoded}'), event)" class="feature-badge-remove text-amber-700 hover:text-red-700 hover:bg-amber-200/60 rounded-full w-4 h-4 flex items-center justify-center transition-colors cursor-pointer" title="Remove ${escaped}">
                &times;
            </button>
        </span>`;
    });
    container.innerHTML = html;
}

function renderFeatureHiddenInputs() {
    const container = document.getElementById('featureHiddenInputs');
    if (!container) return;

    if (selectedFeatures.length === 0) {
        container.innerHTML = `<input type="hidden" name="feeding_type[]" value="Non-Feeding">`;
        return;
    }

    let html = '';
    selectedFeatures.forEach(feat => {
        html += `<input type="hidden" name="feeding_type[]" value="${escapeHtmlStr(feat)}">`;
    });
    container.innerHTML = html;
}

function syncFeatureCheckboxes() {
    document.querySelectorAll('.feature-checkbox').forEach(cb => {
        cb.checked = selectedFeatures.includes(cb.value);
    });
}

function openAddFeedingOptionModal() {
    const modal = document.getElementById('addFeedingOptionModal');
    const input = document.getElementById('newFeedingOptionInput');
    input.value = '';
    renderModalSavedOptionsList();
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => input.focus(), 50);
}

function closeAddFeedingOptionModal() {
    const modal = document.getElementById('addFeedingOptionModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function deleteCustomOption(optionValue) {
    if (!confirm(`Are you sure you want to delete "${optionValue}"?`)) {
        return;
    }

    try {
        let saved = JSON.parse(localStorage.getItem('custom_feeding_options') || '[]');
        saved = saved.filter(v => v !== optionValue);
        localStorage.setItem('custom_feeding_options', JSON.stringify(saved));
    } catch (e) {}

    // Unselect if selected
    selectedFeatures = selectedFeatures.filter(v => v !== optionValue);
    
    renderCustomCheckboxes();
    renderSelectedFeatureBadges();
    renderFeatureHiddenInputs();
    renderModalSavedOptionsList();
}

function escapeHtmlStr(str) {
    if (!str) return '';
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
}

function renderCustomCheckboxes() {
    const container = document.getElementById('customCheckboxesContainer');
    if (!container) return;

    let saved = [];
    try {
        saved = JSON.parse(localStorage.getItem('custom_feeding_options') || '[]');
    } catch (e) {}

    // Also include custom features already in selectedFeatures that aren't standard
    const standardOptions = ['Non-Feeding', 'Feeding', 'Sleeve: Short Sleeve', 'Sleeve: 3/4th Sleeve', 'Sleeve: Full Sleeve', 'Sleeve: Sleeveless'];
    selectedFeatures.forEach(feat => {
        if (!standardOptions.includes(feat) && !saved.includes(feat)) {
            saved.push(feat);
        }
    });

    if (saved.length === 0) {
        container.innerHTML = `<p class="text-[11px] text-gray-400 italic px-2 py-1">No custom types added yet</p>`;
        return;
    }

    let html = '';
    saved.forEach(val => {
        const escaped = escapeHtmlStr(val);
        const encoded = encodeURIComponent(val);
        const isChecked = selectedFeatures.includes(val) ? 'checked' : '';
        html += `
        <div class="flex items-center justify-between px-2.5 py-1.5 rounded-lg hover:bg-amber-50 group">
            <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-gray-800 flex-1 select-none">
                <input type="checkbox" value="${escaped}" ${isChecked} onchange="toggleFeatureOption(this.value, this.checked)" class="feature-checkbox rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                <span>${escaped}</span>
            </label>
            <button type="button" onclick="deleteCustomOption(decodeURIComponent('${encoded}'))" class="p-1 text-gray-300 hover:text-red-600 hover:bg-red-50 rounded opacity-0 group-hover:opacity-100 transition-all cursor-pointer" title="Delete ${escaped}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        </div>`;
    });
    container.innerHTML = html;
}

function renderModalSavedOptionsList() {
    const container = document.getElementById('modalSavedOptionsList');
    if (!container) return;

    let saved = [];
    try {
        saved = JSON.parse(localStorage.getItem('custom_feeding_options') || '[]');
    } catch (e) {}

    const standardOptions = ['Non-Feeding', 'Feeding', 'Sleeve: Short Sleeve', 'Sleeve: 3/4th Sleeve', 'Sleeve: Full Sleeve', 'Sleeve: Sleeveless'];
    selectedFeatures.forEach(feat => {
        if (!standardOptions.includes(feat) && !saved.includes(feat)) {
            saved.push(feat);
        }
    });

    if (saved.length === 0) {
        container.innerHTML = `<p class="text-xs text-gray-400 italic py-2 text-center">No custom options added yet.</p>`;
        return;
    }

    let html = '';
    saved.forEach(val => {
        const escaped = escapeHtmlStr(val);
        const encodedVal = encodeURIComponent(val);
        html += `
        <div class="flex items-center justify-between p-2 rounded-lg bg-gray-50 hover:bg-gray-100 border border-gray-200/60 transition-colors group">
            <span class="text-xs font-semibold text-gray-800">${escaped}</span>
            <button type="button" onclick="deleteCustomOption(decodeURIComponent('${encodedVal}'))" class="p-1 text-red-500 hover:text-red-700 hover:bg-red-50 rounded transition-colors cursor-pointer" title="Delete ${escaped}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        </div>`;
    });
    container.innerHTML = html;
}

function submitAddFeedingOption() {
    const input = document.getElementById('newFeedingOptionInput');
    const val = input.value.trim();
    if (!val) {
        alert('Please enter an option name.');
        input.focus();
        return;
    }

    try {
        let saved = JSON.parse(localStorage.getItem('custom_feeding_options') || '[]');
        if (!saved.includes(val)) {
            saved.push(val);
            localStorage.setItem('custom_feeding_options', JSON.stringify(saved));
        }
    } catch (e) {}

    // Automatically check / select this new option
    if (!selectedFeatures.includes(val)) {
        selectedFeatures.push(val);
    }

    renderCustomCheckboxes();
    renderSelectedFeatureBadges();
    renderFeatureHiddenInputs();
    syncFeatureCheckboxes();
    renderModalSavedOptionsList();
    closeAddFeedingOptionModal();
}

function loadSavedFeedingOptions() {
    renderCustomCheckboxes();
    renderSelectedFeatureBadges();
    renderFeatureHiddenInputs();
    syncFeatureCheckboxes();
}

document.addEventListener('DOMContentLoaded', function() {
    updateVariantSummary();
    loadSavedFeedingOptions();
});
</script>

<!-- Modal for Adding / Managing Custom Feeding/Feature Option -->
<div id="addFeedingOptionModal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 border border-gray-100 transform transition-all">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
            <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center text-sm">✨</span>
                <span>Custom Types & Features</span>
            </h3>
            <button type="button" onclick="closeAddFeedingOptionModal()" class="text-gray-400 hover:text-gray-600 rounded-lg p-1 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <!-- Add Form -->
        <div class="space-y-3 mb-5">
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Add New Option / Field Name <span class="text-red-500">*</span></label>
                <div class="flex gap-2">
                    <input type="text" id="newFeedingOptionInput" placeholder="e.g. normal sleeve, puff sleeve" class="flex-1 px-3.5 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 font-medium" onkeydown="if(event.key==='Enter'){event.preventDefault();submitAddFeedingOption();}">
                    <button type="button" onclick="submitAddFeedingOption()" class="px-4 py-2 text-xs font-bold text-white bg-amber-600 hover:bg-amber-700 rounded-xl transition-colors shadow-sm shrink-0">Add & Select</button>
                </div>
            </div>
        </div>

        <!-- Manage / Delete List -->
        <div class="border-t border-gray-100 pt-4">
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Manage / Delete Options</h4>
                <span class="text-[11px] text-gray-400">Click 🗑️ to remove</span>
            </div>
            <div id="modalSavedOptionsList" class="max-h-48 overflow-y-auto space-y-1.5 pr-1">
                <!-- Populated dynamically with delete buttons -->
            </div>
        </div>

        <div class="flex justify-end pt-4 mt-2 border-t border-gray-100">
            <button type="button" onclick="closeAddFeedingOptionModal()" class="px-4 py-2 text-xs font-bold text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">Close</button>
        </div>
    </div>
</div>
