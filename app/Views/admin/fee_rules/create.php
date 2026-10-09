<?php
/**
 * Admin - Create Fee Rule (Premium Beige Design)
 */
$editMode = $editMode ?? false;
$rule = $rule ?? [];
$formAction = $formAction ?? (BASE_URL . '/admin/fee-rules/store');
$selectedStates = $rule['application_states'] ?? [];
$weightSlabs    = $rule['weight_slabs'] ?? [];
$categoriesList = $categories ?? [];
?>
<div class="w-full">
    <!-- Header Section -->
    <div class="mb-8 relative z-10">
        <!-- Breadcrumbs -->
        <div class="text-sm font-medium text-gray-400 mb-2 font-serif tracking-wide">
            Dashboard <span class="mx-1 text-gray-300">›</span> Settings <span class="mx-1 text-gray-300">›</span> Fee Rules <span class="mx-1 text-gray-300">›</span> <span class="text-brand-700"><?= $editMode ? 'Edit Fee' : 'Add New Fee' ?></span>
        </div>
        
        <div class="flex justify-between items-center mb-6 w-full">
            <h2 class="text-4xl font-display font-extrabold text-gray-900 tracking-tight"><?= $editMode ? 'Edit Fee' : 'Add New Fee' ?></h2>
            <a href="<?= BASE_URL ?>/admin/fee-rules" class="bg-white hover:bg-gray-50 text-gray-700 px-6 py-3 rounded-full font-bold text-sm shadow-sm border border-gray-200 transition-all flex items-center uppercase tracking-widest">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Rules
            </a>
        </div>
    </div>

    <?php if ($flash = \Core\Session::getFlash('error')): ?>
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm font-medium shadow-sm"><?= htmlspecialchars($flash) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= $formAction ?>" id="fee-rule-form">
        <?php if ($editMode): ?><input type="hidden" name="id" value="<?= $rule['id'] ?>"><?php endif; ?>

        <div class="bg-white rounded-2xl p-8 md:p-10 border border-gray-200 shadow-sm">
            
            <div class="grid grid-cols-1 md:grid-cols-2 mb-8" style="column-gap: 3rem;">
                
                <!-- Row 1 -->
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Fee Name <span class="text-red-500">*</span></label>
                    <input type="text" name="fee_name" minlength="3" required
                           value="<?= htmlspecialchars($rule['fee_name'] ?? '') ?>"
                           placeholder="e.g. Delivery Fee"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-gray-800 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm transition-colors placeholder:text-gray-400">
                </div>
                
                <div class="mb-6">
                    <div class="mb-2">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Application State <span class="text-red-500">*</span></label>
                    </div>
                    <div class="flex items-center gap-8 h-[46px]">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="application_states[]" value="Tamil Nadu"
                                   <?= in_array('Tamil Nadu', $selectedStates) ? 'checked' : '' ?>
                                   class="w-5 h-5 rounded text-[#F25996] focus:ring-[#F25996] border-gray-300">
                            <span class="text-sm font-medium text-gray-700 group-hover:text-black">Tamil Nadu</span>
                        </label>
                        
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="application_states[]" value="Other State"
                                   <?= in_array('Other State', $selectedStates) ? 'checked' : '' ?>
                                   class="w-5 h-5 rounded text-[#F25996] focus:ring-[#F25996] border-gray-300">
                            <span class="text-sm font-medium text-gray-700 group-hover:text-black">Other State</span>
                        </label>
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Fee Category <span class="text-red-500">*</span></label>
                        <button type="button" onclick="openCatModal()" class="text-xs uppercase tracking-wider font-bold text-brand-600 hover:text-brand-700 hover:underline">+ Add Category</button>
                    </div>
                    
                    <!-- Custom Select for Categories -->
                    <div class="relative text-sm">
                        <input type="hidden" name="fee_category_id" id="fee_category_id" value="<?= htmlspecialchars($rule['fee_category_id'] ?? '') ?>" required>
                        <input type="hidden" id="fee_category_name" value="">
                        
                        <button type="button" id="catSelectBtn" onclick="toggleCatDropdown()" class="w-full flex justify-between items-center border border-gray-200 rounded-xl px-4 py-3 text-gray-800 bg-white transition-colors">
                            <span id="catSelectText" class="text-gray-400"> — Select Category — </span>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        
                        <!-- Dropdown options -->
                        <div id="catDropdown" class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg hidden max-h-60 overflow-y-auto">
                            <?php foreach ($categoriesList as $cat): ?>
                                <div class="cat-option flex justify-between items-center px-4 py-2.5 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-0" data-id="<?= $cat['id'] ?>" data-name="<?= htmlspecialchars($cat['name']) ?>" data-is-weight="<?= isset($cat['is_weight_based']) ? $cat['is_weight_based'] : 0 ?>" onclick="selectCat(this)">
                                    <span class="text-gray-800 font-medium"><?= htmlspecialchars($cat['name']) ?></span>
                                    <button type="button" onclick="deleteCategory(event, <?= $cat['id'] ?>)" class="text-red-400 hover:text-red-600 p-1" title="Delete Category">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Payment Method (Optional)</label>
                    <select name="payment_method" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-gray-800 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm transition-colors bg-white">
                        <option value="Online" <?= ($rule['payment_method'] ?? 'Online') === 'Online' ? 'selected' : '' ?>>Online</option>
                    </select>
                </div>

                <div id="flat-fee-container" class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2" style="column-gap: 3rem;">
                    <!-- Fee Type (left) | empty (right) -->
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Fee Type <span class="text-red-500">*</span></label>
                        <select name="fee_type" id="fee_type" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-gray-800 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm transition-colors bg-white" onchange="toggleFeeType()">
                            <option value="Fixed Amount" <?= ($rule['fee_type'] ?? '') === 'Fixed Amount' ? 'selected' : '' ?>>Fixed Amount</option>
                            <option value="Percentage" <?= ($rule['fee_type'] ?? '') === 'Percentage' ? 'selected' : '' ?>>Percentage</option>
                        </select>
                    </div>
                    <div class="hidden md:block mb-6"></div><!-- right column placeholder -->

                    <!-- Minimum Order Amount (left) | Maximum Order Amount (right) -->
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Minimum Order Amount (₹)</label>
                        <input type="text" name="minimum_order_amount"
                               value="<?= htmlspecialchars($rule['minimum_order_amount'] ?? '') ?>"
                               placeholder="e.g. 500"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-gray-800 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm transition-colors placeholder:text-gray-400">
                    </div>
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Maximum Order Amount (₹)</label>
                        <input type="text" name="maximum_order_amount"
                               value="<?= htmlspecialchars($rule['maximum_order_amount'] ?? '') ?>"
                               placeholder="Leave blank for unlimited"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-gray-800 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm transition-colors placeholder:text-gray-400">
                    </div>

                    <!-- Fee Value (left) | empty (right) -->
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2" id="flat-fee-label">Fee Value (₹) <span class="text-red-500">*</span></label>
                        <input type="text" name="flat_fee_value"
                               value="<?= htmlspecialchars($rule['flat_fee_value'] ?? '0') ?>"
                               placeholder="e.g. 50"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-gray-800 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm transition-colors placeholder:text-gray-400">
                    </div>
                    <div class="hidden md:block mb-6"></div><!-- right column placeholder -->
                </div>
                
            </div>
            
            <!-- Fixed grid placement for Active Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 mb-8" style="column-gap: 3rem;">                  
                <div class="flex items-center pt-2">
                    <input type="hidden" name="active" value="0">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="active" value="1" id="active_toggle"
                               <?= ($rule['active'] ?? 1) ? 'checked' : '' ?>
                               class="w-5 h-5 rounded text-[#F25996] border-gray-300 focus:ring-[#F25996] transition-colors">
                        <span class="text-xs font-bold text-gray-900 uppercase tracking-wider">Active Status</span>
                    </label>
                </div>
            </div>

            <!-- Divider -->
            <div class="w-full h-px bg-gray-200 mb-8"></div>

            <!-- Weight Slabs Container (Conditionally shown) -->
            <div id="weight-slabs-container" class="mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Weight-Based Fee Configuration</h2>
                    <button type="button" onclick="addSlab()" class="border border-gray-300 text-gray-700 hover:bg-gray-100 px-5 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition-colors">
                        + Add Slab
                    </button>
                </div>

                <div id="slab-error" class="hidden mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm font-sans"></div>

                <div id="slabs-container" class="space-y-4">
                    <?php foreach ($weightSlabs as $i => $slab): ?>
                        <div class="slab-row bg-gray-50 p-6 rounded-2xl flex items-end gap-5 border border-gray-200">
                            <input type="hidden" name="slab_order[<?= $i ?>]" value="<?= $i ?>" class="slab-order-input">
                            
                            <div class="flex-1">
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Min Weight (kg) <span class="text-red-500">* (Min 0.1)</span></label>
                                <input type="number" name="slab_min_weight[<?= $i ?>]" step="0.001" min="0.1" required
                                       value="<?= htmlspecialchars(isset($slab['minWeight']) ? (float)$slab['minWeight'] : '0.1') ?>"
                                       class="slab-min w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-gray-800 focus:border-brand-500 focus:ring-0 text-sm font-medium"
                                       onchange="validateSlabs()">
                            </div>
                            
                            <span class="text-gray-400 font-light text-2xl mb-2">-</span>
                            
                            <div class="flex-1">
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Max Weight (kg) <span class="text-red-500">*</span></label>
                                <input type="number" name="slab_max_weight[<?= $i ?>]" step="0.001" min="0.1" required
                                       value="<?= htmlspecialchars($slab['maxWeight'] ?? '1') ?>"
                                       class="slab-max w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-gray-800 focus:border-brand-500 focus:ring-0 text-sm font-medium"
                                       onchange="validateSlabs()">
                            </div>

                            <div class="flex-1">
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 slab-charge-label">Charge (₹)</label>
                                <input type="text" name="slab_charge[<?= $i ?>]" step="0.01" min="0" required
                                       value="<?= htmlspecialchars($slab['charge']) ?>"
                                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-gray-800 focus:border-brand-500 focus:ring-0 text-sm">
                            </div>
                            
                            <div class="flex flex-col gap-2 pb-1">
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="hidden" name="slab_status[<?= $i ?>]" value="0">
                                    <input type="checkbox" name="slab_status[<?= $i ?>]" value="1" <?= !empty($slab['status']) ? 'checked' : '' ?> class="w-4 h-4 rounded text-[#F25996] border-gray-300 focus:ring-[#F25996]">
                                    <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Active</span>
                                </label>
                                <div class="flex gap-1 border border-gray-300 rounded-lg overflow-hidden bg-white">
                                    <button type="button" onclick="moveSlab(this, -1)" class="px-2.5 py-1 text-[10px] uppercase font-bold text-gray-600 hover:bg-gray-100 hover:text-black transition-colors border-r border-gray-300">Up</button>
                                    <button type="button" onclick="moveSlab(this, 1)" class="px-2.5 py-1 text-[10px] uppercase font-bold text-gray-600 hover:bg-gray-100 hover:text-black transition-colors">Down</button>
                                </div>
                            </div>
                            
                            <button type="button" onclick="this.closest('.slab-row').remove(); validateSlabs();" class="pb-3 text-red-400 hover:text-red-600 transition-colors ml-2" title="Remove Slab">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Submit Area -->
            <div class="flex justify-end items-center gap-4 pt-6">
                <a href="<?= BASE_URL ?>/admin/fee-rules" class="px-6 py-3 rounded-full border border-gray-200 text-gray-700 font-bold uppercase tracking-wider text-xs hover:bg-gray-50 transition-all cursor-pointer">Cancel</a>
                <button type="submit" id="submit-btn" class="px-6 py-3 rounded-full bg-[#F25996] hover:bg-[#e04481] text-white font-bold text-xs uppercase tracking-wider transition-all shadow-md cursor-pointer flex items-center">
                    <?= $editMode ? 'Save Fee Configuration' : 'Save Fee Configuration' ?>
                </button>
            </div>
            
        </div>
    </form>
</div>

<!-- Add Category Modal -->
<div id="addCatModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl p-8 max-w-sm w-full shadow-2xl border border-gray-100">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Add Category</h3>
        
        <input type="text" id="newCatName" placeholder="Category Name" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-gray-800 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-colors mb-4 text-sm">
        
        <label class="flex items-center gap-3 cursor-pointer group mb-6">
            <input type="checkbox" id="newCatIsWeight" value="1" class="w-5 h-5 rounded text-[#F25996] border-gray-300 focus:ring-[#F25996] transition-colors">
            <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Has Weight Slabs?</span>
        </label>
        
        <div class="flex justify-end gap-3">
            <button type="button" onclick="closeCatModal()" class="px-5 py-2.5 rounded-full border border-gray-200 text-gray-700 font-bold uppercase tracking-wider text-xs hover:bg-gray-50 transition-all cursor-pointer">Cancel</button>
            <button type="button" onclick="submitNewCategory()" class="px-5 py-2.5 rounded-full bg-[#F25996] hover:bg-[#e04481] text-white font-bold uppercase tracking-wider text-xs shadow-md transition-all cursor-pointer">Add</button>
        </div>
    </div>
</div>

<script>
// ---------- Category Dropdown & UI Logic ----------
function toggleCatDropdown() {
    const d = document.getElementById('catDropdown');
    d.classList.toggle('hidden');
}

function selectCat(el) {
    const id = el.getAttribute('data-id');
    const name = el.getAttribute('data-name');
    const isWeight = parseInt(el.getAttribute('data-is-weight') || '0', 10);
    
    document.getElementById('fee_category_id').value = id;
    document.getElementById('fee_category_name').value = name;
    
    const textEl = document.getElementById('catSelectText');
    textEl.textContent = name;
    textEl.classList.remove('text-gray-400');
    textEl.classList.add('text-gray-900', 'font-medium');
    
    document.getElementById('catDropdown').classList.add('hidden');
    
    updateCategoryVisibility(isWeight);
}

function updateCategoryVisibility(isWeight) {
    const flatCont = document.getElementById('flat-fee-container');
    const weightCont = document.getElementById('weight-slabs-container');
    
    if (isWeight === 1) {
        flatCont.classList.add('hidden');
        weightCont.classList.remove('hidden');
        // Auto-add one default slab if container is empty
        const slabsContainer = document.getElementById('slabs-container');
        if (slabsContainer && slabsContainer.querySelectorAll('.slab-row').length === 0) {
            addSlab();
        }
    } else {
        flatCont.classList.remove('hidden');
        weightCont.classList.add('hidden');
    }
}

// Initial category selection if edit mode
(function() {
    const selectedCatId = document.getElementById('fee_category_id').value;
    if (selectedCatId) {
        const option = document.querySelector(`.cat-option[data-id="${selectedCatId}"]`);
        if (option) {
            selectCat(option);
        }
    }
})();

// Close dropdown if clicked outside
document.addEventListener('click', function(e) {
    const btn = document.getElementById('catSelectBtn');
    const d = document.getElementById('catDropdown');
    if (btn && d && !btn.contains(e.target) && !d.contains(e.target)) {
        d.classList.add('hidden');
    }
});

function openCatModal() {
    document.getElementById('addCatModal').classList.remove('hidden');
    document.getElementById('newCatName').value = '';
    document.getElementById('newCatName').focus();
}

function closeCatModal() {
    document.getElementById('addCatModal').classList.add('hidden');
}

function submitNewCategory() {
    const name = document.getElementById('newCatName').value.trim();
    if (!name) return;
    
    const isWeightBased = document.getElementById('newCatIsWeight').checked ? 1 : 0;
    
    fetch('<?= BASE_URL ?>/admin/fee-rules/add-category', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'name=' + encodeURIComponent(name) + '&is_weight_based=' + isWeightBased
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Add to dropdown
            const dropdown = document.getElementById('catDropdown');
            const newOpt = document.createElement('div');
            newOpt.className = 'cat-option flex justify-between items-center px-4 py-2 hover:bg-orange-50 cursor-pointer border-b border-gray-100 last:border-0';
            newOpt.setAttribute('data-id', data.id);
            newOpt.setAttribute('data-name', data.name);
            newOpt.setAttribute('data-is-weight', data.is_weight_based);
            newOpt.onclick = function() { selectCat(newOpt); };
            newOpt.innerHTML = `
                <span class="text-gray-800 font-medium">${data.name}</span>
                <button type="button" onclick="deleteCategory(event, ${data.id})" class="text-red-400 hover:text-red-600 p-1" title="Delete Category">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            `;
            dropdown.appendChild(newOpt);
            closeCatModal();
            selectCat(newOpt);
        } else {
            alert(data.error || 'Failed to add category.');
        }
    });
}

function deleteCategory(e, id) {
    e.stopPropagation(); // prevent dropdown select
    if (!confirm('Are you sure you want to delete this category?')) return;
    
    fetch('<?= BASE_URL ?>/admin/fee-rules/delete-category', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + id
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const opt = document.querySelector(`.cat-option[data-id="${id}"]`);
            if (opt) opt.remove();
            
            // If it was the currently selected category, clear the selection
            if (document.getElementById('fee_category_id').value == id) {
                document.getElementById('fee_category_id').value = '';
                document.getElementById('fee_category_name').value = '';
                document.getElementById('catSelectText').textContent = '— Select Category —';
                document.getElementById('catSelectText').classList.add('text-gray-400');
                document.getElementById('catSelectText').classList.remove('text-gray-900', 'font-medium');
                updateCategoryVisibility('');
            }
        } else {
            alert(data.error || 'Failed to delete category.');
        }
    });
}

// ---------- Fee type toggle ----------
function toggleFeeType() {
    const type = document.getElementById('fee_type').value;
    const label = document.getElementById('flat-fee-label');
    if (label) label.innerHTML = type === 'Percentage' ? 'Fee Value (%) <span class="text-red-500">*</span>' : 'Fee Value (₹) <span class="text-red-500">*</span>';
    
    document.querySelectorAll('.slab-charge-label').forEach(lbl => {
        lbl.innerText = type === 'Percentage' ? 'Charge (%)' : 'Charge (₹)';
    });
}
toggleFeeType();

// ---------- Slab management ----------
let slabIndex = <?= count($weightSlabs) ?>;

function addSlab() {
    const container = document.getElementById('slabs-container');
    const div = document.createElement('div');
    div.className = 'slab-row bg-gray-50 p-6 rounded-2xl flex items-end gap-5 border border-gray-200';
    div.innerHTML = `
        <input type="hidden" name="slab_order[${slabIndex}]" value="${slabIndex}" class="slab-order-input">
        
        <div class="flex-1">
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Min Weight (kg) <span class="text-red-500">* (Min 0.1)</span></label>
            <input type="number" name="slab_min_weight[${slabIndex}]" step="0.001" min="0.1" value="0.1" required
                   class="slab-min w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-gray-800 focus:border-brand-500 focus:ring-0 text-sm font-medium" onchange="validateSlabs()">
        </div>
        
        <span class="text-gray-400 font-light text-2xl mb-2">-</span>
        
        <div class="flex-1">
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Max Weight (kg) <span class="text-red-500">*</span></label>
            <input type="number" name="slab_max_weight[${slabIndex}]" step="0.001" min="0.1" value="1" required
                   class="slab-max w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-gray-800 focus:border-brand-500 focus:ring-0 text-sm font-medium" onchange="validateSlabs()">
        </div>

        <div class="flex-1">
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 slab-charge-label">${document.getElementById('fee_type')?.value === 'Percentage' ? 'Charge (%)' : 'Charge (₹)'}</label>
            <input type="text" name="slab_charge[${slabIndex}]" step="0.01" min="0" value="0"
                   class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-gray-800 focus:border-brand-500 focus:ring-0 text-sm">
        </div>
        
        <div class="flex flex-col gap-2 pb-1">
            <label class="flex items-center gap-2 cursor-pointer group">
                <input type="hidden" name="slab_status[${slabIndex}]" value="0">
                <input type="checkbox" name="slab_status[${slabIndex}]" value="1" checked class="w-4 h-4 rounded text-[#F25996] border-gray-300 focus:ring-[#F25996]">
                <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Active</span>
            </label>
            <div class="flex gap-1 border border-gray-300 rounded-lg overflow-hidden bg-white">
                <button type="button" onclick="moveSlab(this, -1)" class="px-2.5 py-1 text-[10px] uppercase font-bold text-gray-600 hover:bg-gray-100 hover:text-black transition-colors border-r border-gray-300">Up</button>
                <button type="button" onclick="moveSlab(this, 1)" class="px-2.5 py-1 text-[10px] uppercase font-bold text-gray-600 hover:bg-gray-100 hover:text-black transition-colors">Down</button>
            </div>
        </div>
        
        <button type="button" onclick="this.closest('.slab-row').remove(); validateSlabs();" class="pb-3 text-red-400 hover:text-red-600 transition-colors ml-2" title="Remove Slab">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    `;
    container.appendChild(div);
    slabIndex++;
    validateSlabs();
    toggleFeeType();
}

function moveSlab(btn, direction) {
    const row = btn.closest('.slab-row');
    const container = row.parentElement;
    
    if (direction === -1 && row.previousElementSibling) {
        container.insertBefore(row, row.previousElementSibling);
    } else if (direction === 1 && row.nextElementSibling) {
        container.insertBefore(row.nextElementSibling, row);
    }
    updateSlabOrders();
}

function updateSlabOrders() {
    document.querySelectorAll('.slab-row').forEach((row, i) => {
        const orderInput = row.querySelector('.slab-order-input');
        if (orderInput) orderInput.value = i;
    });
}

// ---------- Overlap & Minimum Weight Validator ----------
function validateSlabs() {
    const rows = document.querySelectorAll('.slab-row');
    const errEl = document.getElementById('slab-error');
    const btn = document.getElementById('submit-btn');
    const slabs = [];

    for (let row of rows) {
        const minEl = row.querySelector('.slab-min');
        const maxEl = row.querySelector('.slab-max');
        const statusEl = row.querySelector('input[name*="slab_status"][type="checkbox"]');
        if (!minEl || !maxEl) continue;

        const min = parseFloat(minEl.value);
        const max = parseFloat(maxEl.value);

        if (isNaN(min) || min < 0.1) {
            showError('Minimum weight must be at least 0.1 kg.');
            if (btn) btn.disabled = true;
            return false;
        }

        if (isNaN(max) || max <= min) {
            showError(`Max weight (${max || 0} kg) must be greater than min weight (${min} kg).`);
            if (btn) btn.disabled = true;
            return false;
        }

        if (statusEl && statusEl.checked) {
            slabs.push({ min, max });
        }
    }

    // Sort and check overlaps
    slabs.sort((a, b) => a.min - b.min);
    for (let i = 1; i < slabs.length; i++) {
        if (slabs[i].min < slabs[i-1].max) {
            showError(`Slab overlap detected: [${slabs[i].min} - ${slabs[i].max}] overlaps with [${slabs[i-1].min} - ${slabs[i-1].max}].`);
            if (btn) btn.disabled = true;
            return false;
        }
    }

    if (errEl) errEl.classList.add('hidden');
    if (btn) btn.disabled = false;
    return true;
}

function showError(msg) {
    const errEl = document.getElementById('slab-error');
    if (errEl) {
        errEl.textContent = msg;
        errEl.classList.remove('hidden');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('fee-rule-form')?.addEventListener('submit', function(e) {
        const weightCont = document.getElementById('weight-slabs-container');
        if (weightCont && !weightCont.classList.contains('hidden')) {
            if (!validateSlabs()) {
                e.preventDefault();
                return false;
            }
        }
    });
});
</script>

<script>
// On load, update visibility and text based on current category
window.addEventListener('DOMContentLoaded', () => {
    const selectedId = document.getElementById('fee_category_id').value;
    if (selectedId) {
        const option = document.querySelector(`.cat-option[data-id="${selectedId}"]`);
        if (option) {
            const isWeight = parseInt(option.getAttribute('data-is-weight') || '0', 10);
            updateCategoryVisibility(isWeight);
            
            const name = option.getAttribute('data-name');
            const textEl = document.getElementById('catSelectText');
            textEl.textContent = name;
            textEl.classList.remove('text-gray-400');
            textEl.classList.add('text-gray-900', 'font-medium');
        }
    } else {
        // Default state if no category selected
        updateCategoryVisibility(0);
    }
});
</script>
