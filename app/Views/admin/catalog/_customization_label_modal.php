<!-- Quick-Add Customization Label Modal -->
<div id="customizationLabelModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="label-modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900/60 transition-opacity backdrop-blur-sm" onclick="closeCustomizationLabelModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal Dialog -->
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
            <!-- Header -->
            <div class="bg-gradient-to-r from-pink-600 via-rose-600 to-amber-600 px-6 py-4 text-white flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-white/10 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold font-display" id="label-modal-title">🏷️ Quick Add Customization Label</h3>
                        <p class="text-xs text-pink-100">Add a new style option or customization label template.</p>
                    </div>
                </div>
                <button type="button" onclick="closeCustomizationLabelModal()" class="text-white/80 hover:text-white bg-white/10 hover:bg-white/20 p-2 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Form -->
            <form id="formQuickAddLabel" onsubmit="submitCustomizationLabelModal(event)" class="p-6 space-y-4">
                <!-- Group / Category -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                        Group / Category <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="group_name" id="modal_label_group" list="existing-label-groups" required placeholder="e.g. Maternity, Necklines, Sleeves, Bottoms" class="w-full border border-gray-300 rounded-xl focus:ring-pink-500 focus:border-pink-500 px-3.5 py-2.5 text-sm bg-white font-medium">
                    <datalist id="existing-label-groups">
                        <option value="Maternity"></option>
                        <option value="Necklines"></option>
                        <option value="Sleeves"></option>
                        <option value="Bottoms"></option>
                        <option value="Dupatta"></option>
                        <option value="Fit & Flare"></option>
                        <option value="Pockets"></option>
                    </datalist>
                    <p class="text-[11px] text-gray-400 mt-1">Select from suggestions or type a new group name.</p>
                </div>

                <!-- Sub-Group (Optional) -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                        Sub-Group <span class="text-gray-400 font-normal">(Optional)</span>
                    </label>
                    <input type="text" name="sub_group" id="modal_label_subgroup" placeholder="e.g. Feeding, Non Feeding, Short, Long" class="w-full border border-gray-300 rounded-xl focus:ring-pink-500 focus:border-pink-500 px-3.5 py-2.5 text-sm bg-white">
                </div>

                <!-- Label / Style Name -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                        Style / Label Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="modal_label_name" required placeholder="e.g. Puff Sleeve, Anarkali Cut, Mandarin Collar" class="w-full border border-gray-300 rounded-xl focus:ring-pink-500 focus:border-pink-500 px-3.5 py-2.5 text-sm bg-white font-bold text-gray-900">
                </div>

                <!-- Description (Optional) -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                        Description / Notes <span class="text-gray-400 font-normal">(Optional)</span>
                    </label>
                    <textarea name="description" id="modal_label_desc" rows="2" placeholder="Brief notes about this customization style..." class="w-full border border-gray-300 rounded-xl focus:ring-pink-500 focus:border-pink-500 px-3.5 py-2 text-sm text-gray-700"></textarea>
                </div>

                <!-- Live Preview Badge -->
                <div class="bg-gray-50 p-3 rounded-xl border border-gray-200 text-xs flex items-center justify-between">
                    <span class="text-gray-500 font-medium">Display Preview:</span>
                    <span id="modal_label_preview" class="font-bold text-gray-800 bg-white px-2.5 py-1 rounded-lg border border-gray-200">
                        Group -> Style Name
                    </span>
                </div>

                <!-- Modal Actions -->
                <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeCustomizationLabelModal()" class="px-4 py-2 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="btnSubmitQuickAddLabel" class="px-5 py-2 bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-700 hover:to-rose-700 text-white text-sm font-bold rounded-xl shadow-xs hover:shadow transition-all flex items-center gap-2">
                        <span id="btnSubmitQuickAddLabelText">Create & Select Label</span>
                        <svg id="btnSubmitQuickAddLabelSpinner" class="w-4 h-4 animate-spin hidden" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openCustomizationLabelModal() {
    const modal = document.getElementById('customizationLabelModal');
    if (!modal) return;
    document.getElementById('formQuickAddLabel').reset();
    updateLabelModalPreview();
    modal.classList.remove('hidden');
    setTimeout(() => {
        const input = document.getElementById('modal_label_group');
        if (input) input.focus();
    }, 100);
}

function closeCustomizationLabelModal() {
    const modal = document.getElementById('customizationLabelModal');
    if (modal) modal.classList.add('hidden');
}

function updateLabelModalPreview() {
    const group = (document.getElementById('modal_label_group')?.value || '').trim();
    const subgroup = (document.getElementById('modal_label_subgroup')?.value || '').trim();
    const name = (document.getElementById('modal_label_name')?.value || '').trim();
    const previewEl = document.getElementById('modal_label_preview');
    if (!previewEl) return;

    const parts = [group, subgroup, name].filter(Boolean);
    previewEl.textContent = parts.length > 0 ? parts.join(' ➔ ') : 'Group ➔ Style Name';
}

document.getElementById('modal_label_group')?.addEventListener('input', updateLabelModalPreview);
document.getElementById('modal_label_subgroup')?.addEventListener('input', updateLabelModalPreview);
document.getElementById('modal_label_name')?.addEventListener('input', updateLabelModalPreview);

function submitCustomizationLabelModal(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSubmitQuickAddLabel');
    const btnText = document.getElementById('btnSubmitQuickAddLabelText');
    const spinner = document.getElementById('btnSubmitQuickAddLabelSpinner');

    const form = document.getElementById('formQuickAddLabel');
    const formData = new FormData(form);

    btn.disabled = true;
    btnText.textContent = 'Creating...';
    spinner.classList.remove('hidden');

    fetch(BASE_URL + '/admin/customization-labels/quick-add', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btnText.textContent = 'Create & Select Label';
        spinner.classList.add('hidden');

        if (data.success && data.label) {
            appendCustomizationLabelToGrid(data.label);
            closeCustomizationLabelModal();
            showCustomizationToast('Label "' + data.label.name + '" created and selected!');
        } else {
            alert(data.message || 'Error creating customization label.');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btnText.textContent = 'Create & Select Label';
        spinner.classList.add('hidden');
        console.error(err);
        alert('Network error while creating label. Please check your connection.');
    });
}

function appendCustomizationLabelToGrid(label) {
    const grid = document.getElementById('customization-labels-grid');
    const emptyState = document.getElementById('customization-labels-empty');
    if (emptyState) {
        emptyState.classList.add('hidden');
    }
    if (!grid) return;

    // Check if item already exists
    const existing = document.querySelector(`input[name="customization_labels[]"][value="${label.id}"]`);
    if (existing) {
        existing.checked = true;
        const parentLabel = existing.closest('label');
        if (parentLabel) {
            parentLabel.scrollIntoView({ behavior: 'smooth', block: 'center' });
            parentLabel.classList.add('ring-2', 'ring-pink-500', 'bg-pink-50');
            setTimeout(() => parentLabel.classList.remove('ring-2', 'ring-pink-500', 'bg-pink-50'), 2500);
        }
        return;
    }

    const card = document.createElement('label');
    card.className = 'relative cursor-pointer flex items-start gap-3 p-3.5 rounded-xl border border-pink-300 bg-pink-50/60 has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50/40 has-[:checked]:ring-1 has-[:checked]:ring-brand-500 transition-all shadow-2xs select-none hover:border-gray-300 animate-pulse';
    
    let groupBadge = label.group_name ? `<span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-gray-100 text-gray-700">${escapeLabelHtml(label.group_name)}</span>` : '';
    let subGroupBadge = label.sub_group ? `<span class="text-[10px] font-semibold px-2 py-0.5 rounded-md bg-amber-50 text-amber-800 border border-amber-200">${escapeLabelHtml(label.sub_group)}</span>` : '';
    let descHtml = label.description ? `<p class="text-[11px] text-gray-500 mt-1 line-clamp-2">${escapeLabelHtml(label.description)}</p>` : '';

    card.innerHTML = `
        <input type="checkbox" name="customization_labels[]" value="${label.id}" checked class="mt-0.5 w-4 h-4 text-brand-600 border-gray-300 rounded focus:ring-brand-500 customization-label-checkbox">
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-1.5 flex-wrap">
                ${groupBadge}
                ${subGroupBadge}
                <span class="text-xs font-bold text-gray-900">${escapeLabelHtml(label.name)}</span>
                <span class="text-[9px] font-black uppercase px-1.5 py-0.2 rounded bg-pink-200 text-pink-800 ml-auto">New</span>
            </div>
            ${descHtml}
        </div>
    `;

    grid.insertBefore(card, grid.firstChild);
    setTimeout(() => {
        card.classList.remove('animate-pulse', 'border-pink-300', 'bg-pink-50/60');
    }, 2000);
    card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function escapeLabelHtml(str) {
    if (!str) return '';
    return str.toString()
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function showCustomizationToast(msg) {
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-5 right-5 z-50 bg-gray-900 text-white px-4 py-3 rounded-xl shadow-xl text-xs font-bold flex items-center gap-2 border border-gray-700 transition-all transform translate-y-2 opacity-0';
    toast.innerHTML = `<svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> <span>${msg}</span>`;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.classList.remove('translate-y-2', 'opacity-0');
    }, 50);
    setTimeout(() => {
        toast.classList.add('translate-y-2', 'opacity-0');
        setTimeout(() => toast.remove(), 400);
    }, 3000);
}
</script>
