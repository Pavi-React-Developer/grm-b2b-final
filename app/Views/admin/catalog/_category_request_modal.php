<!-- Category / Subcategory Request Modal -->
<div id="categoryRequestModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-60 transition-opacity backdrop-blur-sm" onclick="closeCategoryRequestModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal Dialog -->
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-brand-600 to-indigo-700 px-6 py-5 text-white flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-white/10 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold font-display" id="modal-title">Request New Category / Subcategory</h3>
                        <p class="text-xs text-brand-100">Proposed categories will be submitted for Super Admin approval.</p>
                    </div>
                </div>
                <button type="button" onclick="closeCategoryRequestModal()" class="text-white/80 hover:text-white bg-white/10 hover:bg-white/20 p-2 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Tab Navigation -->
            <div class="flex border-b border-gray-200 bg-gray-50/80 px-6 pt-3">
                <button type="button" id="tabBtnCategory" onclick="switchCategoryModalTab('category')" class="px-4 py-2.5 text-sm font-bold border-b-2 border-brand-600 text-brand-600 transition-all flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    New Category
                </button>
                <button type="button" id="tabBtnSubcategory" onclick="switchCategoryModalTab('subcategory')" class="px-4 py-2.5 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700 transition-all flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    New Subcategory
                </button>
                <button type="button" id="tabBtnMyRequests" onclick="switchCategoryModalTab('myRequests')" class="px-4 py-2.5 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700 transition-all flex items-center gap-1.5 ml-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    My Requests
                </button>
            </div>

            <!-- Tab 1: Request Main Category -->
            <form id="formRequestCategory" onsubmit="submitCategoryRequest(event, 'category')" class="p-6 space-y-4">
                <input type="hidden" name="request_type" value="category">
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Category Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="req_cat_name" required placeholder="e.g. Footwear & Shoes" class="w-full border border-gray-300 rounded-xl focus:ring-brand-500 focus:border-brand-500 px-3.5 py-2.5 text-sm">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">HSN Code <span class="text-[10px] text-gray-400 font-normal">(digits only)</span></label>
                        <input type="text" name="hsn_code" id="req_cat_hsn" maxlength="8" pattern="[0-9]*" oninput="this.value=this.value.replace(/\D/g,'')" placeholder="e.g. 6403" class="w-full border border-gray-300 rounded-xl focus:ring-brand-500 focus:border-brand-500 px-3.5 py-2 text-sm font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">SGST (%)</label>
                        <input type="text" step="0.01" min="0" max="50" name="sgst" id="req_cat_sgst" value="0.00" class="w-full border border-gray-300 rounded-xl focus:ring-brand-500 focus:border-brand-500 px-3.5 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">CGST (%)</label>
                        <input type="text" step="0.01" min="0" max="50" name="cgst" id="req_cat_cgst" value="0.00" class="w-full border border-gray-300 rounded-xl focus:ring-brand-500 focus:border-brand-500 px-3.5 py-2 text-sm">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-bold text-gray-700">Proposed Specification Attributes (Optional)</label>
                        <span class="text-[11px] text-gray-400">e.g. Size: 6,7,8,9,10 | Color: Black, Brown</span>
                    </div>
                    <textarea name="proposed_attributes" id="req_cat_attrs" rows="2" placeholder="e.g. Size: 6, 7, 8, 9, 10 | Color: Black, Brown, White | Material: Leather, Synthetic" class="w-full border border-gray-300 rounded-xl focus:ring-brand-500 focus:border-brand-500 px-3.5 py-2 text-sm font-mono"></textarea>
                    <div class="mt-1.5 flex flex-wrap items-center gap-1.5 text-[11px]">
                        <span class="text-gray-400 font-medium">Quick Presets:</span>
                        <button type="button" onclick="appendProposedAttr('req_cat_attrs', 'Shoe Sizes: 6, 7, 8, 9, 10, 11, 12')" class="px-2 py-0.5 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 rounded-md font-semibold transition-colors">
                            👟 Shoe Sizes
                        </button>
                        <button type="button" onclick="appendProposedAttr('req_cat_attrs', 'Size: XS, S, M, L, XL, XXL')" class="px-2 py-0.5 bg-blue-50 hover:bg-blue-100 text-blue-800 border border-blue-200 rounded-md font-semibold transition-colors">
                            👕 Apparel Sizes
                        </button>
                        <button type="button" onclick="appendProposedAttr('req_cat_attrs', 'Color: Black, Brown, Tan, White, Navy')" class="px-2 py-0.5 bg-purple-50 hover:bg-purple-100 text-purple-800 border border-purple-200 rounded-md font-semibold transition-colors">
                            🎨 Colors
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Description / Notes for Admin</label>
                    <textarea name="description" id="req_cat_desc" rows="2" placeholder="Briefly explain what products will be sold under this category..." class="w-full border border-gray-300 rounded-xl focus:ring-brand-500 focus:border-brand-500 px-3.5 py-2 text-sm"></textarea>
                </div>

                <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeCategoryRequestModal()" class="px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="btnSubmitCat" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                        <span>Submit for Approval</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>

            <!-- Tab 2: Request Subcategory -->
            <form id="formRequestSubcategory" onsubmit="submitCategoryRequest(event, 'subcategory')" class="p-6 space-y-4 hidden">
                <input type="hidden" name="request_type" value="subcategory">

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Parent Category <span class="text-red-500">*</span></label>
                    <select name="parent_category_id" id="req_sub_parent_id" required class="w-full border border-gray-300 rounded-xl focus:ring-brand-500 focus:border-brand-500 px-3.5 py-2.5 text-sm bg-white">
                        <option value="">Select Parent Category...</option>
                        <?php if (!empty($categories)): ?>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Subcategory Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="req_sub_name" required placeholder="e.g. Leather Boots" class="w-full border border-gray-300 rounded-xl focus:ring-brand-500 focus:border-brand-500 px-3.5 py-2.5 text-sm">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-bold text-gray-700">Proposed Specification Attributes (Optional)</label>
                        <span class="text-[11px] text-gray-400">e.g. Size: 6,7,8,9,10 | Color: Black, Brown</span>
                    </div>
                    <textarea name="proposed_attributes" id="req_sub_attrs" rows="2" placeholder="e.g. Shoe Size: 6, 7, 8, 9, 10, 11 | Color: Black, Brown, Tan" class="w-full border border-gray-300 rounded-xl focus:ring-brand-500 focus:border-brand-500 px-3.5 py-2 text-sm font-mono"></textarea>
                    <div class="mt-1.5 flex flex-wrap items-center gap-1.5 text-[11px]">
                        <span class="text-gray-400 font-medium">Quick Presets:</span>
                        <button type="button" onclick="appendProposedAttr('req_sub_attrs', 'Shoe Sizes: 6, 7, 8, 9, 10, 11, 12')" class="px-2 py-0.5 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 rounded-md font-semibold transition-colors">
                            👟 Shoe Sizes
                        </button>
                        <button type="button" onclick="appendProposedAttr('req_sub_attrs', 'Size: XS, S, M, L, XL, XXL')" class="px-2 py-0.5 bg-blue-50 hover:bg-blue-100 text-blue-800 border border-blue-200 rounded-md font-semibold transition-colors">
                            👕 Apparel Sizes
                        </button>
                        <button type="button" onclick="appendProposedAttr('req_sub_attrs', 'Color: Black, Brown, Tan, White, Navy')" class="px-2 py-0.5 bg-purple-50 hover:bg-purple-100 text-purple-800 border border-purple-200 rounded-md font-semibold transition-colors">
                            🎨 Colors
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Description / Notes for Admin</label>
                    <textarea name="description" id="req_sub_desc" rows="2" placeholder="Briefly describe this subcategory..." class="w-full border border-gray-300 rounded-xl focus:ring-brand-500 focus:border-brand-500 px-3.5 py-2 text-sm"></textarea>
                </div>

                <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeCategoryRequestModal()" class="px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="btnSubmitSub" class="px-5 py-2.5 bg-[#F25996] hover:bg-[#e04481] text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                        <span>Submit Subcategory</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>

            <!-- Tab 3: My Requests List -->
            <div id="panelMyRequests" class="p-6 hidden">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-sm font-bold text-gray-900">Your Submitted Category Requests</h4>
                    <button type="button" onclick="loadMyCategoryRequests()" class="text-xs text-brand-600 hover:text-brand-700 font-semibold flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Refresh
                    </button>
                </div>

                <div class="overflow-x-auto max-h-64 border border-gray-200 rounded-xl">
                    <table class="w-full text-left text-xs text-gray-600">
                        <thead class="bg-gray-50 text-gray-700 font-bold border-b border-gray-200 uppercase tracking-wider text-[11px]">
                            <tr>
                                <th class="px-4 py-3">Type</th>
                                <th class="px-4 py-3">Proposed Name</th>
                                <th class="px-4 py-3">Parent</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Submitted</th>
                            </tr>
                        </thead>
                        <tbody id="myRequestsTableBody" class="divide-y divide-gray-100">
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-400 italic">Loading requests...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 pt-3 border-t border-gray-100 flex justify-end">
                    <button type="button" onclick="closeCategoryRequestModal()" class="px-4 py-2 border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openCategoryRequestModal(initialType = 'category') {
    const modal = document.getElementById('categoryRequestModal');
    if (!modal) return;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // Auto-select parent category in subcategory form if currently selected in product form
    const currentCatSelect = document.getElementById('category_id');
    const modalParentSelect = document.getElementById('req_sub_parent_id');
    if (currentCatSelect && modalParentSelect && currentCatSelect.value) {
        modalParentSelect.value = currentCatSelect.value;
    }

    switchCategoryModalTab(initialType);
}

function closeCategoryRequestModal() {
    const modal = document.getElementById('categoryRequestModal');
    if (!modal) return;
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function switchCategoryModalTab(tab) {
    const btnCat = document.getElementById('tabBtnCategory');
    const btnSub = document.getElementById('tabBtnSubcategory');
    const btnMy = document.getElementById('tabBtnMyRequests');
    
    const formCat = document.getElementById('formRequestCategory');
    const formSub = document.getElementById('formRequestSubcategory');
    const panelMy = document.getElementById('panelMyRequests');

    // Reset styles
    [btnCat, btnSub, btnMy].forEach(b => {
        if (!b) return;
        b.className = 'px-4 py-2.5 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700 transition-all flex items-center gap-1.5';
    });
    [formCat, formSub, panelMy].forEach(p => p && p.classList.add('hidden'));

    if (tab === 'subcategory') {
        btnSub.className = 'px-4 py-2.5 text-sm font-bold border-b-2 border-indigo-600 text-indigo-600 transition-all flex items-center gap-1.5';
        formSub.classList.remove('hidden');
    } else if (tab === 'myRequests') {
        btnMy.className = 'px-4 py-2.5 text-sm font-bold border-b-2 border-brand-600 text-brand-600 transition-all flex items-center gap-1.5 ml-auto';
        panelMy.classList.remove('hidden');
        loadMyCategoryRequests();
    } else {
        btnCat.className = 'px-4 py-2.5 text-sm font-bold border-b-2 border-brand-600 text-brand-600 transition-all flex items-center gap-1.5';
        formCat.classList.remove('hidden');
    }
}

async function submitCategoryRequest(e, type) {
    e.preventDefault();
    const form = type === 'subcategory' ? document.getElementById('formRequestSubcategory') : document.getElementById('formRequestCategory');
    const submitBtn = type === 'subcategory' ? document.getElementById('btnSubmitSub') : document.getElementById('btnSubmitCat');
    
    const formData = new FormData(form);
    const origText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="inline-block animate-spin mr-2">↻</span> Submitting...';

    try {
        const response = await fetch('<?= BASE_URL ?>/admin/catalog/category-requests/store', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            form.reset();
            Swal.fire({
                icon: 'success',
                title: 'Request Submitted!',
                text: data.message || 'Your category request has been submitted to Super Admin for approval.',
                confirmButtonColor: '#059669',
                timer: 4000
            });
            switchCategoryModalTab('myRequests');
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Submission Failed',
                text: data.message || 'Could not submit category request. Please try again.',
                confirmButtonColor: '#ef4444'
            });
        }
    } catch (err) {
        console.error(err);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An unexpected error occurred while communicating with the server.',
            confirmButtonColor: '#ef4444'
        });
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = origText;
    }
}

async function loadMyCategoryRequests() {
    const tbody = document.getElementById('myRequestsTableBody');
    if (!tbody) return;
    tbody.innerHTML = '<tr><td colspan="5" class="px-4 py-6 text-center text-gray-400 italic">Loading requests...</td></tr>';

    try {
        const res = await fetch('<?= BASE_URL ?>/admin/catalog/category-requests/my-requests');
        const data = await res.json();

        if (data.success && data.requests && data.requests.length > 0) {
            tbody.innerHTML = data.requests.map(req => {
                let statusBadge = '';
                if (req.status === 'approved') {
                    statusBadge = '<span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-800 border border-green-200">Approved</span>';
                } else if (req.status === 'rejected') {
                    const reason = req.rejection_reason ? ` title="${escapeHtml(req.rejection_reason)}"` : '';
                    statusBadge = `<span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800 border border-red-200 cursor-help"${reason}>Rejected</span>`;
                } else {
                    statusBadge = '<span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">Pending Review</span>';
                }

                const typeBadge = req.request_type === 'subcategory' 
                    ? '<span class="px-1.5 py-0.5 rounded bg-indigo-50 text-indigo-700 font-semibold text-[10px]">Subcategory</span>' 
                    : '<span class="px-1.5 py-0.5 rounded bg-blue-50 text-blue-700 font-semibold text-[10px]">Category</span>';

                const dateStr = new Date(req.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

                return `
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="px-4 py-2.5">${typeBadge}</td>
                        <td class="px-4 py-2.5 font-bold text-gray-900">${escapeHtml(req.name)}</td>
                        <td class="px-4 py-2.5 text-gray-500">${escapeHtml(req.parent_category_name || '—')}</td>
                        <td class="px-4 py-2.5">${statusBadge}</td>
                        <td class="px-4 py-2.5 text-gray-400 text-[11px]">${dateStr}</td>
                    </tr>
                `;
            }).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="5" class="px-4 py-6 text-center text-gray-400 italic">No category requests submitted yet.</td></tr>';
        }
    } catch (err) {
        console.error(err);
        tbody.innerHTML = '<tr><td colspan="5" class="px-4 py-4 text-center text-red-500 italic">Failed to load requests.</td></tr>';
    }
}

function appendProposedAttr(textareaId, text) {
    const el = document.getElementById(textareaId);
    if (!el) return;
    if (!el.value || el.value.trim() === '') {
        el.value = text;
    } else {
        el.value = el.value.trim() + ' | ' + text;
    }
}

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}
</script>
