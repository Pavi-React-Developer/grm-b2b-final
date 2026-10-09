<!-- Attribute / Specification Creation Modal -->
<div id="attributeRequestModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="attr-modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-60 transition-opacity backdrop-blur-sm" onclick="closeAttributeModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal Dialog -->
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gray-100">
            <!-- Header -->
            <div class="bg-gradient-to-r from-amber-600 via-orange-600 to-amber-700 px-6 py-4 text-white flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-white/10 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold font-display" id="attr-modal-title">✨ Add Specification Attribute</h3>
                        <p class="text-xs text-amber-100">Create sizes, colors, materials or custom variant attributes.</p>
                    </div>
                </div>
                <button type="button" onclick="closeAttributeModal()" class="text-white/80 hover:text-white bg-white/10 hover:bg-white/20 p-2 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Target Category Badge Bar -->
            <div class="bg-amber-50/70 border-b border-amber-100 px-6 py-2.5 flex items-center justify-between text-xs">
                <span class="text-amber-900 font-medium">Adding attribute for:</span>
                <div class="flex items-center gap-1.5 font-bold">
                    <span id="attrModalCatBadge" class="bg-white text-amber-800 px-2 py-0.5 rounded-md border border-amber-200">Category</span>
                    <span id="attrModalSubBadge" class="bg-white text-indigo-800 px-2 py-0.5 rounded-md border border-indigo-200 hidden">Subcategory</span>
                </div>
            </div>

            <!-- Form -->
            <form id="formCreateAttributeModal" onsubmit="submitAttributeModal(event)" class="p-6 space-y-4">
                <input type="hidden" name="category_id" id="modal_attr_cat_id">
                <input type="hidden" name="sub_category_id" id="modal_attr_subcat_id">

                <!-- Attribute Name & Code -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Attribute Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="modal_attr_name" required placeholder="e.g. Size, Color, Shoe Size" oninput="autoGenAttrCode(this.value)" class="w-full border border-gray-300 rounded-xl focus:ring-amber-500 focus:border-amber-500 px-3.5 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Code / Identifier</label>
                        <input type="text" name="attribute_code" id="modal_attr_code" placeholder="e.g. shoe_size" class="w-full border border-gray-300 rounded-xl focus:ring-amber-500 focus:border-amber-500 px-3.5 py-2.5 text-sm font-mono bg-gray-50">
                    </div>
                </div>

                <!-- Input / Display Type -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Display / Input Type <span class="text-red-500">*</span></label>
                    <select name="input_type" id="modal_attr_input_type" class="w-full border border-gray-300 rounded-xl focus:ring-amber-500 focus:border-amber-500 px-3.5 py-2.5 text-sm bg-white font-medium">
                        <option value="checkbox">Multi-select Chips (Best for Sizes, Variants, Features)</option>
                        <option value="colorpicker">Color Swatches (Color circles with color codes)</option>
                        <option value="dropdown">Dropdown Selection (Single choice list)</option>
                        <option value="textbox">Text Field (Free text specifications)</option>
                    </select>
                </div>

                <!-- Values / Options -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-sm font-bold text-gray-700">Predefined Options / Values <span class="text-red-500">*</span></label>
                        <span class="text-[11px] text-gray-400">Separate by commas (e.g. 6, 7, 8, 9, 10)</span>
                    </div>
                    <textarea name="values" id="modal_attr_values" rows="3" required placeholder="e.g. 6, 7, 8, 9, 10, 11 (or Small, Medium, Large, XL)" class="w-full border border-gray-300 rounded-xl focus:ring-amber-500 focus:border-amber-500 px-3.5 py-2.5 text-sm"></textarea>

                    <!-- Quick Preset Badges -->
                    <div class="mt-2 flex flex-wrap items-center gap-1.5 text-[11px]">
                        <span class="text-gray-400 font-medium">Quick Presets:</span>
                        <button type="button" onclick="applyAttrPreset('Shoe Sizes', '6, 7, 8, 9, 10, 11, 12')" class="px-2 py-0.5 bg-amber-100/80 hover:bg-amber-200 text-amber-900 rounded-md font-semibold transition-colors">
                            👟 Shoe Sizes
                        </button>
                        <button type="button" onclick="applyAttrPreset('Size', 'XS, S, M, L, XL, XXL, 3XL')" class="px-2 py-0.5 bg-blue-100/80 hover:bg-blue-200 text-blue-900 rounded-md font-semibold transition-colors">
                            👕 Apparel Sizes
                        </button>
                        <button type="button" onclick="applyAttrPreset('Color', 'Black, Brown, White, Tan, Navy Blue, Grey', 'colorpicker')" class="px-2 py-0.5 bg-purple-100/80 hover:bg-purple-200 text-purple-900 rounded-md font-semibold transition-colors">
                            🎨 Colors
                        </button>
                    </div>
                </div>

                <!-- Flags -->
                <div class="pt-2 border-t border-gray-100 grid grid-cols-2 gap-3 text-xs">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="is_variant" id="modal_attr_is_variant" value="1" checked class="w-4 h-4 text-amber-600 border-gray-300 rounded focus:ring-amber-500">
                        <span class="font-bold text-gray-700">Use for Variant Generation</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="is_filterable" value="1" checked class="w-4 h-4 text-amber-600 border-gray-300 rounded focus:ring-amber-500">
                        <span class="font-semibold text-gray-600">Show in Storefront Filters</span>
                    </label>
                </div>

                <!-- Actions -->
                <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeAttributeModal()" class="px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="btnSubmitAttrModal" class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                        <span>Save & Add Attribute</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAttributeModal() {
    const catSelect = document.getElementById('category_id');
    const subcatSelect = document.getElementById('sub_category_id');

    if (!catSelect || !catSelect.value) {
        Swal.fire({
            icon: 'warning',
            title: 'Select Category First',
            text: 'Please choose a Category from the dropdown before adding specification attributes.',
            confirmButtonColor: '#d97706'
        });
        catSelect ? catSelect.focus() : null;
        return;
    }

    const catId = catSelect.value;
    const catName = catSelect.options[catSelect.selectedIndex].text.split('(')[0].trim();
    const subcatId = (subcatSelect && subcatSelect.value) ? subcatSelect.value : '';
    const subcatName = (subcatSelect && subcatSelect.selectedIndex > 0) ? subcatSelect.options[subcatSelect.selectedIndex].text.trim() : '';

    document.getElementById('modal_attr_cat_id').value = catId;
    document.getElementById('modal_attr_subcat_id').value = subcatId;

    document.getElementById('attrModalCatBadge').textContent = catName;
    const subBadge = document.getElementById('attrModalSubBadge');
    if (subcatName && subcatName !== 'None' && subcatName !== 'Select Category first...') {
        subBadge.textContent = subcatName;
        subBadge.classList.remove('hidden');
    } else {
        subBadge.classList.add('hidden');
    }

    const modal = document.getElementById('attributeRequestModal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
            const input = document.getElementById('modal_attr_name');
            if (input) input.focus();
        }, 100);
    }
}

function closeAttributeModal() {
    const modal = document.getElementById('attributeRequestModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

function autoGenAttrCode(val) {
    const codeInput = document.getElementById('modal_attr_code');
    if (!codeInput) return;
    const code = val.toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/^_+|_+$/g, '');
    codeInput.value = code;
}

function applyAttrPreset(name, values, inputType = 'checkbox') {
    document.getElementById('modal_attr_name').value = name;
    autoGenAttrCode(name);
    document.getElementById('modal_attr_values').value = values;
    document.getElementById('modal_attr_input_type').value = inputType;
}

async function submitAttributeModal(e) {
    e.preventDefault();
    const form = document.getElementById('formCreateAttributeModal');
    const btn = document.getElementById('btnSubmitAttrModal');
    const origText = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = '<span class="inline-block animate-spin mr-2">↻</span> Saving...';

    const formData = new FormData(form);

    try {
        const response = await fetch('<?= BASE_URL ?>/admin/catalog/attributes/store', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            closeAttributeModal();
            form.reset();

            Swal.fire({
                icon: 'success',
                title: 'Attribute Added!',
                text: data.message || 'Specification attribute created successfully.',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });

            // Reload specifications in the product form
            if (typeof window.fetchAttributes === 'function') {
                window.fetchAttributes();
            } else if (typeof fetchAttributes === 'function') {
                fetchAttributes();
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Failed to Add Attribute',
                text: data.message || 'Could not save attribute. Please try again.',
                confirmButtonColor: '#ef4444'
            });
        }
    } catch (err) {
        console.error(err);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An unexpected error occurred while saving the attribute.',
            confirmButtonColor: '#ef4444'
        });
    } finally {
        btn.disabled = false;
        btn.innerHTML = origText;
    }
}
</script>
