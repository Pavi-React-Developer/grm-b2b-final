<div class="p-6 max-w-5xl mx-auto">
    <!-- Breadcrumb & Header -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-pink-600 mb-1">
            <a href="<?= BASE_URL ?>/admin/fabric-customizations" class="hover:underline">Fabric Rules</a>
            <span>/</span>
            <span>New Customization Rule</span>
        </div>
        <h1 class="text-2xl font-black text-gray-900 font-display">
            ➕ Add Fabric Customization Rule
        </h1>
        <p class="text-sm text-gray-500 mt-1">Configure size consumption rates, minimum stitch pieces, and wastage factors for wholesale fabrics.</p>
    </div>

    <form action="<?= BASE_URL ?>/admin/fabric-customizations/store" method="POST" id="ruleForm" class="space-y-6">
        
        <!-- Section 1: Fabric Selection & Identity -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-xs space-y-5">
            <h2 class="text-base font-bold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                <span class="w-7 h-7 rounded-lg bg-pink-50 text-pink-700 flex items-center justify-center text-sm font-black">1</span>
                <span>Select Fabric & Program Details</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Fabric Dropdown -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Wholesale Fabric Product <span class="text-red-500">*</span>
                    </label>
                    <select name="fabric_id" id="fabricSelect" required class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 font-medium">
                        <option value="">-- Choose Fabric Product from Inventory --</option>
                        <?php foreach ($products as $p): ?>
                            <option value="<?= $p['id'] ?>" data-stock="<?= $p['stock_quantity'] ?>" data-price="<?= $p['wholesale_price'] ?>" data-sku="<?= htmlspecialchars($p['base_sku']) ?>" data-cat="<?= $p['category_id'] ?>" data-sub="<?= $p['sub_category_id'] ?>">
                                <?= htmlspecialchars($p['name']) ?> (SKU: <?= htmlspecialchars($p['base_sku']) ?> | Stock: <?= (int)$p['stock_quantity'] ?> Units | ₹<?= number_format($p['wholesale_price'], 2) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Top Row: Program Name, Garment Type -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Program Name -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Program Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="program_name" required placeholder="e.g. Women's Cotton Maxi Dress" class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 font-medium">
                </div>

                <!-- Garment Type -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Garment Type <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="garment_type" required placeholder="e.g. Maxi Dress, Kurti, Shirt, Trousers" class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 font-medium">
                </div>
            </div>

            <!-- Second Row: Required Pieces to Enable Payment, Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Required Pieces to Enable Payment -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Required Pieces to Enable Payment <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" name="minimum_pieces" value="10" min="1" required placeholder="e.g. 10" class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 font-bold text-gray-900 pr-14">
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
                        <option value="active">Active (Visible on Storefront)</option>
                        <option value="inactive">Inactive (Draft / Hidden)</option>
                    </select>
                </div>
            </div>
            </div>
        </div>

        <!-- Section 2: Assign Customization Style Labels / Templates -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-xs space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-3">
                <div>
                    <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-pink-50 text-pink-700 flex items-center justify-center text-sm font-black">2</span>
                        <span>Assign Customization Style Labels / Templates</span>
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Select which template labels (e.g. <code>Maternity -&gt; Feeding -&gt; Full Sleeve</code>) apply to this fabric. Users will see input fields to allocate piece quantities to these styles on the storefront.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="selectAllLabels(true)" class="px-2.5 py-1 text-xs font-bold text-pink-700 bg-pink-50 hover:bg-pink-100 rounded-lg border border-pink-200 transition-colors cursor-pointer">Select All</button>
                    <button type="button" onclick="selectAllLabels(false)" class="px-2.5 py-1 text-xs font-bold text-gray-600 bg-gray-50 hover:bg-gray-100 rounded-lg border border-gray-200 transition-colors cursor-pointer">Clear</button>
                    <a href="<?= BASE_URL ?>/admin/customization-labels/create" target="_blank" class="px-3 py-1 bg-gray-900 hover:bg-gray-800 text-white text-xs font-bold rounded-lg transition-colors flex items-center gap-1">
                        + New Label
                    </a>
                </div>
            </div>

            <?php if (empty($allLabels)): ?>
                <div class="p-6 bg-pink-50/50 rounded-xl border border-pink-100 text-center">
                    <p class="text-xs text-gray-600 mb-2">No customization labels created yet.</p>
                    <a href="<?= BASE_URL ?>/admin/customization-labels/create" target="_blank" class="inline-flex items-center gap-1 text-xs font-bold text-pink-600 hover:underline">
                        + Create your first template label (e.g. Maternity -&gt; Feeding -&gt; Full Sleeve)
                    </a>
                </div>
            <?php else: ?>
                <?php
                    // Group labels by group_name
                    $groupedLabels = [];
                    foreach ($allLabels as $lbl) {
                        $grp = $lbl['group_name'] ?: 'General / Standard';
                        $groupedLabels[$grp][] = $lbl;
                    }
                ?>
                <div class="space-y-4">
                    <?php foreach ($groupedLabels as $grpName => $lblGroup): ?>
                        <div class="bg-gray-50/80 p-4 rounded-xl border border-gray-200/70">
                            <div class="text-xs font-extrabold text-gray-900 uppercase tracking-wider mb-2.5 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-pink-500"></span>
                                <span><?= htmlspecialchars($grpName) ?></span>
                                <span class="text-[10px] font-bold text-gray-400 bg-white px-1.5 py-0.5 rounded border border-gray-200"><?= count($lblGroup) ?> styles</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                                <?php foreach ($lblGroup as $lbl): ?>
                                    <label class="flex items-start gap-2.5 p-2.5 bg-white rounded-xl border border-gray-200 hover:border-pink-300 hover:bg-pink-50/20 transition-all cursor-pointer select-none">
                                        <input type="checkbox" name="label_ids[]" value="<?= $lbl['id'] ?>" class="label-checkbox rounded border-gray-300 text-pink-600 focus:ring-pink-500 mt-0.5">
                                        <div class="flex-1 min-w-0">
                                            <div class="text-xs font-bold text-gray-900 flex items-center gap-1 truncate">
                                                <span>🏷️</span>
                                                <span class="truncate"><?= htmlspecialchars($lbl['display_label']) ?></span>
                                            </div>
                                            <?php if (!empty($lbl['description'])): ?>
                                                <p class="text-[10.5px] text-gray-400 truncate mt-0.5"><?= htmlspecialchars($lbl['description']) ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Section 3: Size Variants & Quantity Allocation -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-xs space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-3">
                <div>
                    <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-pink-50 text-pink-700 flex items-center justify-center text-sm font-black">3</span>
                        <span>Fabric Size Variants & Quantity Limits</span>
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Define sizes, default buy quantities (e.g. 2 pcs), and maximum allowed pieces per size (e.g. max 4 pcs for 4XL).
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="addCustomSizeVariant()" class="px-3.5 py-1.5 bg-pink-50 hover:bg-pink-100 text-[#F25996] text-xs font-bold rounded-lg border border-pink-200 transition-colors flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-3.5 h-3.5 text-[#F25996]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Add Size Variant</span>
                    </button>
                </div>
            </div>

            <!-- Quick Text Parser & Presets -->
            <div class="bg-pink-50/50 p-4 rounded-xl border border-pink-100 space-y-3">
                <!-- Text input parser -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                    <div class="relative flex-1">
                        <input type="text" id="quickShorthandInput" placeholder="Quick paste e.g. M-2, L-2, XL-2, XXL-2, 3XL-2, 4XL-4 (or M:2/4, L:2/4)" class="w-full px-3.5 py-2 text-xs bg-white border border-pink-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F25996] font-mono text-gray-800">
                    </div>
                    <button type="button" onclick="applyShorthandInput()" class="px-4 py-2 bg-[#F25996] hover:bg-[#d8407d] text-white text-xs font-bold rounded-lg transition-colors shadow-2xs cursor-pointer flex items-center justify-center gap-1.5 shrink-0">
                        <span>⚡ Parse & Generate</span>
                    </button>
                </div>

                <!-- Presets Buttons -->
                <div class="flex flex-wrap items-center gap-2 pt-1 border-t border-pink-200/60">
                    <span class="text-[11px] font-bold text-pink-900 uppercase tracking-wider mr-1">Quick Presets:</span>
                    <button type="button" onclick="applySizeVariantPreset('standard_m_4xl')" class="px-2.5 py-1 bg-white hover:bg-pink-50 text-pink-800 text-xs font-bold rounded-lg border border-pink-200 transition-all shadow-2xs cursor-pointer">
                        + Standard (M to 4XL: Base 2 / Max 4)
                    </button>
                    <button type="button" onclick="applySizeVariantPreset('all_s_xxl')" class="px-2.5 py-1 bg-white hover:bg-pink-50 text-pink-800 text-xs font-bold rounded-lg border border-pink-200 transition-all shadow-2xs cursor-pointer">
                        + S to XXL (Base 2 / Max 4)
                    </button>
                    <button type="button" onclick="applySizeVariantPreset('plus_3xl_6xl')" class="px-2.5 py-1 bg-white hover:bg-pink-50 text-pink-800 text-xs font-bold rounded-lg border border-pink-200 transition-all shadow-2xs cursor-pointer">
                        + Plus (3XL to 6XL: Base 2 / Max 4)
                    </button>
                    <button type="button" onclick="applySizeVariantPreset('freesize')" class="px-2.5 py-1 bg-white hover:bg-pink-50 text-pink-800 text-xs font-bold rounded-lg border border-pink-200 transition-all shadow-2xs cursor-pointer">
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
                        <!-- Populated by JS -->
                    </tbody>
                </table>
            </div>

            <!-- Summary Bar -->
            <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-200/80 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-4">
                    <span>Active Size Variants: <strong class="text-gray-900" id="adminActiveVariantsCount">0</strong></span>
                    <span>•</span>
                    <span>Total Base Pieces: <strong class="text-[#F25996]" id="adminTotalBasePieces">0</strong> pcs</span>
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
            <button type="submit" class="px-6 py-2.5 bg-[#F25996] hover:bg-[#d8407d] text-white text-sm font-bold rounded-xl shadow-sm hover:shadow transition-all">
                Save Fabric Rule
            </button>
        </div>
    </form>
</div>

<script>
let sizeIndex = 0;

function escapeHtmlStr(str) {
    if (!str) return '';
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
}

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
                <input type="number" name="sizes[${sizeIndex}][max_quantity]" value="${maxQty}" min="1" required class="size-max-qty-input w-28 px-3 py-1.5 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] font-bold text-[#F25996]" oninput="updateVariantSummary()">
                <span class="text-xs text-gray-400 font-semibold">max pcs</span>
            </div>
        </td>
        <td class="py-2.5 px-3 text-center">
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="sizes[${sizeIndex}][enabled]" value="1" ${enabled ? 'checked' : ''} class="sr-only peer" onchange="updateVariantSummary()">
                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#F25996]"></div>
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
        // Supports formats: M-2, M-2-4, M:2, M:2/4, 4XL-2
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
                // e.g. 4XL-2-4 -> name: 4XL, base: 2, max: 4
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

function selectAllLabels(check) {
    document.querySelectorAll('.label-checkbox').forEach(cb => {
        cb.checked = check;
    });
}

document.addEventListener('DOMContentLoaded', function() {
    applySizeVariantPreset('standard_m_4xl');
});
</script>
