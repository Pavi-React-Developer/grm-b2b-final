<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-[1.5rem] shadow-custom border border-gray-100">
        <div>
            <div class="flex items-center gap-3">
                <a href="<?= BASE_URL ?>/admin/cms/size-charts" class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-colors" title="Back to Size Charts">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight">Edit Size Chart: <?= htmlspecialchars($chart['title']) ?></h1>
                    <p class="text-sm text-gray-500 font-medium">Update measurements, columns, rows, or category assignment dynamically.</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?= BASE_URL ?>/admin/cms/size-charts" class="px-5 py-2.5 border border-gray-200 text-gray-600 hover:bg-gray-50 font-bold text-sm rounded-full transition-colors">
                Cancel
            </a>
            <button type="button" onclick="document.getElementById('sizeChartForm').submit()" class="bg-brand-600 hover:bg-brand-700 text-white px-6 py-2.5 rounded-full font-bold text-sm shadow-sm transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Save Changes</span>
            </button>
        </div>
    </div>

    <!-- Main Form -->
    <form action="<?= BASE_URL ?>/admin/cms/size-charts/update" method="POST" id="sizeChartForm" class="space-y-6">
        <input type="hidden" name="id" value="<?= $chart['id'] ?>">
        <!-- Hidden JSON Payloads -->
        <input type="hidden" name="columns_json_payload" id="columnsJsonPayload">
        <input type="hidden" name="rows_json_payload" id="rowsJsonPayload">

        <!-- Chart Settings Card -->
        <div class="bg-white p-6 sm:p-8 rounded-[1.5rem] shadow-custom border border-gray-100 space-y-6">
            <h2 class="text-base font-black text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-3 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-brand-600"></span>
                Basic Information & Category
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <!-- Size Chart Title -->
                <div class="lg:col-span-2">
                    <label class="block text-xs font-black text-gray-700 uppercase tracking-wider mb-2">Size Chart Title / Dress Name <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="chartTitleInput" oninput="updateLivePreview()" value="<?= htmlspecialchars($chart['title']) ?>" placeholder="e.g. CURVY PEPLUM MAXI" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm font-bold text-gray-900 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all">
                </div>

                <!-- Applicable Category with Dynamic Add Category Action -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-black text-gray-700 uppercase tracking-wider">Target Category</label>
                        <button type="button" onclick="openAddCategoryModal()" class="text-xs text-brand-600 hover:text-brand-800 font-black flex items-center gap-1 hover:underline transition-colors" title="Create a new category dynamically">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            <span>+ Add Category</span>
                        </button>
                    </div>
                    <select name="category_id" id="categoryIdSelect" onchange="updateCategoryName()" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none bg-white">
                        <option value="0" data-name="All Categories" <?= (int)$chart['category_id'] === 0 ? 'selected' : '' ?>>All Categories / General</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" data-name="<?= htmlspecialchars($cat['name']) ?>" <?= (int)$chart['category_id'] === (int)$cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" name="category_name" id="categoryNameHidden" value="<?= htmlspecialchars($chart['category_name'] ?? 'All Categories') ?>">
                </div>

                <!-- Dress Type -->
                <div>
                    <label class="block text-xs font-black text-gray-700 uppercase tracking-wider mb-2">Dress / Garment Type</label>
                    <input type="text" name="dress_type" id="dressTypeInput" value="<?= htmlspecialchars($chart['dress_type'] ?? '') ?>" placeholder="e.g. Maxi Dress, Coords Set, Kurti, Frock, Nightwear" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none">
                </div>

                <!-- Tolerance Note -->
                <div>
                    <label class="block text-xs font-black text-gray-700 uppercase tracking-wider mb-2">Tolerance / Measurement Note</label>
                    <input type="text" name="tolerance_note" id="toleranceNoteInput" oninput="updateLivePreview()" value="<?= htmlspecialchars($chart['tolerance_note'] ?? 'Size in inches (+ or - 0.5")') ?>" placeholder='e.g. Size in inches (+ or - 0.5")' class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none">
                </div>

                <!-- Status & Sort -->
                <div class="flex items-center gap-4 pt-4">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" <?= $chart['is_active'] ? 'checked' : '' ?> class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        <span class="ml-3 text-xs font-black text-gray-700 uppercase tracking-wider">Active</span>
                    </label>
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-bold text-gray-500">Order:</label>
                        <input type="text" name="sort_order" value="<?= (int)($chart['sort_order'] ?? 0) ?>" class="w-16 px-2 py-1.5 border border-gray-200 rounded-lg text-xs font-bold text-center">
                    </div>
                </div>
            </div>
        </div>

        <!-- Interactive Columns & Rows Table Builder -->
        <div class="bg-white p-6 sm:p-8 rounded-[1.5rem] shadow-custom border border-gray-100 space-y-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-gray-100 pb-4">
                <div>
                    <h2 class="text-base font-black text-gray-900 uppercase tracking-wider flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-brand-600"></span>
                        Measurement Table Columns & Rows
                    </h2>
                    <p class="text-xs text-gray-400 font-medium mt-0.5">Customize columns and size measurements dynamically.</p>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <button type="button" onclick="addNewColumnModal()" class="px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-full text-xs font-bold transition-colors flex items-center gap-1.5 border border-blue-200">
                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>Add Column</span>
                    </button>
                    <button type="button" onclick="addNewRow()" class="px-4 py-2 bg-brand-50 hover:bg-brand-100 text-brand-700 rounded-full text-xs font-bold transition-colors flex items-center gap-1.5 border border-brand-200">
                        <svg class="w-3.5 h-3.5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>Add Size Row</span>
                    </button>
                </div>
            </div>

            <!-- Interactive Table Grid Input -->
            <div class="overflow-x-auto rounded-2xl border border-gray-200 bg-gray-50/50 p-3">
                <table class="w-full text-left border-collapse" id="builderTable">
                    <thead id="builderTableHead">
                        <!-- Head built dynamically by JS -->
                    </thead>
                    <tbody id="builderTableBody" class="divide-y divide-gray-200">
                        <!-- Rows built dynamically by JS -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Live Visual Preview Container -->
        <div class="bg-white p-6 sm:p-8 rounded-[1.5rem] shadow-custom border border-gray-100 space-y-6">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <h2 class="text-base font-black text-gray-900 uppercase tracking-wider">Live Storefront Preview</h2>
                </div>
                <span class="text-xs font-bold text-gray-400 bg-gray-100 px-3 py-1 rounded-full">Realtime UI Render</span>
            </div>

            <!-- Preview Card Box -->
            <div class="bg-[#faf9f8] p-6 sm:p-10 rounded-[1.75rem] border border-gray-200/80 max-w-3xl mx-auto shadow-sm">
                <!-- Preview Title & Tolerance Pill -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6 pb-2">
                    <div>
                        <h3 id="previewTitle" class="text-2xl font-black text-gray-900 uppercase tracking-tight font-serif inline-block pb-1" style="border-bottom: 4px solid #5a3e2b;">
                            <?= htmlspecialchars($chart['title']) ?>
                        </h3>
                    </div>
                    <div>
                        <span id="previewTolerance" class="inline-block px-4 py-1.5 rounded-full border border-gray-300 text-gray-700 text-xs font-bold tracking-tight bg-white shadow-2xs">
                            <?= htmlspecialchars($chart['tolerance_note'] ?? 'Size in inches (+ or - 0.5")') ?>
                        </span>
                    </div>
                </div>

                <!-- Preview Table -->
                <div class="overflow-x-auto rounded-xl bg-white shadow-2xs border border-gray-100">
                    <table class="w-full text-center border-collapse">
                        <thead id="previewTableHead" class="bg-white text-[#5a3e2b] font-bold text-xs sm:text-sm border-b-2 border-gray-200">
                            <!-- Preview columns -->
                        </thead>
                        <tbody id="previewTableBody" class="divide-y divide-gray-100 text-xs sm:text-sm font-semibold text-gray-800">
                            <!-- Preview rows -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Form Actions Bottom -->
        <div class="flex justify-end gap-3 pt-2">
            <a href="<?= BASE_URL ?>/admin/cms/size-charts" class="px-6 py-3 border border-gray-200 text-gray-600 hover:bg-gray-50 font-bold text-sm rounded-full transition-colors">
                Cancel
            </a>
            <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white px-8 py-3 rounded-full font-bold text-sm shadow-md transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Save Changes</span>
            </button>
        </div>
    </form>
</div>

<script>
// State Initialized with Existing Data from Database
let columns = <?= json_encode($chart['columns'] ?: ['Size', 'Bust', 'Full Length', 'Sleeve length']) ?>;
let rows = <?= json_encode($chart['rows'] ?: [['Size' => 'S', 'Bust' => '', 'Full Length' => '', 'Sleeve length' => '']]) ?>;

function renderBuilder() {
    // 1. Render Header
    const head = document.getElementById('builderTableHead');
    let headHtml = `<tr class="bg-white text-xs font-black text-gray-700 uppercase tracking-wider border-b border-gray-200">`;
    columns.forEach((c, idx) => {
        headHtml += `<th class="py-3 px-3 min-w-[130px]">
            <div class="flex items-center justify-between gap-1 bg-gray-100 px-2.5 py-1.5 rounded-lg border border-gray-200">
                <input type="text" value="${c}" onchange="renameColumn(${idx}, this.value)" class="bg-transparent font-black text-xs text-gray-800 outline-none w-full" title="Click to rename column">
                ${idx > 0 ? `<button type="button" onclick="deleteColumn(${idx})" class="text-red-400 hover:text-red-600 font-bold ml-1 text-sm p-0.5" title="Delete Column">×</button>` : ''}
            </div>
        </th>`;
    });
    headHtml += `<th class="py-3 px-3 w-28 text-center">
        <button type="button" onclick="addNewColumnModal()" class="inline-flex items-center gap-1 text-[11px] font-bold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-2 py-1 rounded-md border border-blue-200 transition-colors">
            + Column
        </button>
    </th></tr>`;
    head.innerHTML = headHtml;

    // 2. Render Body
    const body = document.getElementById('builderTableBody');
    let bodyHtml = '';
    rows.forEach((r, rIdx) => {
        bodyHtml += `<tr class="bg-white hover:bg-gray-50/50 transition-colors">`;
        columns.forEach(c => {
            const isSize = (c.toLowerCase() === 'size');
            const val = r[c] ?? '';
            bodyHtml += `<td class="py-2.5 px-3">
                <input type="text" value="${val}" oninput="updateCellValue(${rIdx}, '${c}', this.value)" placeholder="${isSize ? 'e.g. S, M, XL, 28' : 'e.g. 36&quot;'}" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs ${isSize ? 'font-black text-gray-900 bg-amber-50/40' : 'font-semibold text-gray-700'} focus:ring-2 focus:ring-brand-500 outline-none">
            </td>`;
        });
        bodyHtml += `<td class="py-2.5 px-3 text-center">
            <button type="button" onclick="deleteRow(${rIdx})" class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors" title="Delete Row">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </td></tr>`;
    });
    body.innerHTML = bodyHtml;

    updateLivePreview();
}

function updateCellValue(rIdx, colName, val) {
    if (rows[rIdx]) {
        rows[rIdx][colName] = val;
    }
    updateLivePreview();
}

function renameColumn(idx, newName) {
    newName = newName.trim();
    if (!newName) return;
    const oldName = columns[idx];
    columns[idx] = newName;
    rows.forEach(r => {
        r[newName] = r[oldName] ?? '';
        delete r[oldName];
    });
    renderBuilder();
}

function deleteColumn(idx) {
    if (columns.length <= 1) {
        alert('At least one column is required.');
        return;
    }
    const colName = columns[idx];
    columns.splice(idx, 1);
    rows.forEach(r => delete r[colName]);
    renderBuilder();
}

function addColumn(colName) {
    colName = colName.trim();
    if (!colName) return;
    if (!columns.some(c => c.toLowerCase() === colName.toLowerCase())) {
        columns.push(colName);
        rows.forEach(r => {
            if (!(colName in r)) r[colName] = '';
        });
        renderBuilder();
    }
}

function addNewColumnModal() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Add Measurement Column',
            html: `
                <div class="text-left space-y-3 pt-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase">Column Name</label>
                    <input id="swalNewColInput" type="text" placeholder="e.g. Bust, Waist, Length, Chest, Hip, Sleeve" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-bold text-gray-900 focus:ring-2 focus:ring-brand-500 outline-none">
                    <div>
                        <span class="text-xs text-gray-400 font-bold block mb-1.5">Quick Suggestions:</span>
                        <div class="flex flex-wrap gap-1.5">
                            <button type="button" onclick="document.getElementById('swalNewColInput').value='Bust'" class="px-2.5 py-1 bg-gray-100 hover:bg-gray-200 rounded-md text-xs font-semibold text-gray-700">Bust</button>
                            <button type="button" onclick="document.getElementById('swalNewColInput').value='Waist'" class="px-2.5 py-1 bg-gray-100 hover:bg-gray-200 rounded-md text-xs font-semibold text-gray-700">Waist</button>
                            <button type="button" onclick="document.getElementById('swalNewColInput').value='Hip'" class="px-2.5 py-1 bg-gray-100 hover:bg-gray-200 rounded-md text-xs font-semibold text-gray-700">Hip</button>
                            <button type="button" onclick="document.getElementById('swalNewColInput').value='Full Length'" class="px-2.5 py-1 bg-gray-100 hover:bg-gray-200 rounded-md text-xs font-semibold text-gray-700">Full Length</button>
                            <button type="button" onclick="document.getElementById('swalNewColInput').value='Chest'" class="px-2.5 py-1 bg-gray-100 hover:bg-gray-200 rounded-md text-xs font-semibold text-gray-700">Chest</button>
                            <button type="button" onclick="document.getElementById('swalNewColInput').value='Top Length'" class="px-2.5 py-1 bg-gray-100 hover:bg-gray-200 rounded-md text-xs font-semibold text-gray-700">Top Length</button>
                            <button type="button" onclick="document.getElementById('swalNewColInput').value='Pant Length'" class="px-2.5 py-1 bg-gray-100 hover:bg-gray-200 rounded-md text-xs font-semibold text-gray-700">Pant Length</button>
                            <button type="button" onclick="document.getElementById('swalNewColInput').value='Shoulder'" class="px-2.5 py-1 bg-gray-100 hover:bg-gray-200 rounded-md text-xs font-semibold text-gray-700">Shoulder</button>
                            <button type="button" onclick="document.getElementById('swalNewColInput').value='Sleeve length'" class="px-2.5 py-1 bg-gray-100 hover:bg-gray-200 rounded-md text-xs font-semibold text-gray-700">Sleeve length</button>
                            <button type="button" onclick="document.getElementById('swalNewColInput').value='Age Group'" class="px-2.5 py-1 bg-gray-100 hover:bg-gray-200 rounded-md text-xs font-semibold text-gray-700">Age Group</button>
                        </div>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Add Column',
            cancelButtonText: 'Cancel',
            customClass: {
                popup: 'rounded-3xl p-6 max-w-md',
                confirmButton: 'bg-brand-600 hover:bg-brand-700 text-white rounded-full px-6 py-2.5 font-bold text-sm',
                cancelButton: 'border border-gray-300 text-gray-600 rounded-full px-6 py-2.5 font-bold text-sm'
            },
            buttonsStyling: false,
            preConfirm: () => {
                const input = document.getElementById('swalNewColInput');
                const val = input ? input.value.trim() : '';
                if (!val) {
                    Swal.showValidationMessage('Please enter a column name');
                    return false;
                }
                if (columns.some(c => c.toLowerCase() === val.toLowerCase())) {
                    Swal.showValidationMessage('Column already exists!');
                    return false;
                }
                return val;
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                addColumn(result.value);
            }
        });
        setTimeout(() => {
            const el = document.getElementById('swalNewColInput');
            if (el) el.focus();
        }, 100);
    } else {
        const name = prompt('Enter new column name (e.g. Bust, Waist, Length, Chest):');
        if (name && name.trim()) {
            addColumn(name.trim());
        }
    }
}

function addNewRow() {
    const newRow = {};
    columns.forEach(c => newRow[c] = '');
    rows.push(newRow);
    renderBuilder();
}

function deleteRow(idx) {
    if (rows.length <= 1) {
        alert('At least one size row is required.');
        return;
    }
    rows.splice(idx, 1);
    renderBuilder();
}

function updateCategoryName() {
    const sel = document.getElementById('categoryIdSelect');
    const opt = sel.options[sel.selectedIndex];
    document.getElementById('categoryNameHidden').value = opt.getAttribute('data-name') || 'All Categories';
}

function openAddCategoryModal() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Create New Category',
            html: `
                <div class="text-left space-y-3 pt-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase">Category Name <span class="text-red-500">*</span></label>
                    <input id="swalNewCatInput" type="text" placeholder="e.g. Western Wear, Kurtis, Nightwear, Kids Frocks, Tops" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-bold text-gray-900 focus:ring-2 focus:ring-brand-500 outline-none">
                    <p class="text-xs text-gray-500">This category will be saved to the database and automatically selected for this size chart.</p>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Save Category',
            cancelButtonText: 'Cancel',
            customClass: {
                popup: 'rounded-3xl p-6 max-w-md',
                confirmButton: 'bg-brand-600 hover:bg-brand-700 text-white rounded-full px-6 py-2.5 font-bold text-sm',
                cancelButton: 'border border-gray-300 text-gray-600 rounded-full px-6 py-2.5 font-bold text-sm'
            },
            buttonsStyling: false,
            preConfirm: () => {
                const input = document.getElementById('swalNewCatInput');
                const val = input ? input.value.trim() : '';
                if (!val) {
                    Swal.showValidationMessage('Category name is required');
                    return false;
                }
                return val;
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                createCategoryAjax(result.value);
            }
        });
        setTimeout(() => {
            const el = document.getElementById('swalNewCatInput');
            if (el) el.focus();
        }, 100);
    } else {
        const name = prompt('Enter new category name:');
        if (name && name.trim()) {
            createCategoryAjax(name.trim());
        }
    }
}

function createCategoryAjax(categoryName) {
    const fd = new FormData();
    fd.append('name', categoryName);

    fetch('<?= BASE_URL ?>/admin/cms/size-charts/create-category', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: fd
    })
    .then(r => r.json())
    .then(data => {
        if (data.success && data.category) {
            const select = document.getElementById('categoryIdSelect');
            const cat = data.category;
            
            // Check if option already exists
            let existingOpt = select.querySelector(`option[value="${cat.id}"]`);
            if (!existingOpt) {
                const newOpt = document.createElement('option');
                newOpt.value = cat.id;
                newOpt.setAttribute('data-name', cat.name);
                newOpt.textContent = cat.name;
                select.appendChild(newOpt);
                existingOpt = newOpt;
            }
            
            // Select the new category
            select.value = cat.id;
            updateCategoryName();

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: `Category "${cat.name}" added & selected!`,
                    showConfirmButton: false,
                    timer: 3000
                });
            } else {
                alert(`Category "${cat.name}" added & selected!`);
            }
        } else {
            alert(data.error || 'Failed to create category.');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Network error while saving category.');
    });
}

function updateLivePreview() {
    const titleVal = document.getElementById('chartTitleInput').value.trim() || 'SIZE CHART TITLE';
    const toleranceVal = document.getElementById('toleranceNoteInput').value.trim() || 'Size in inches (+ or - 0.5")';

    document.getElementById('previewTitle').textContent = titleVal;
    document.getElementById('previewTolerance').textContent = toleranceVal;

    // Head
    let headHtml = '<tr>';
    columns.forEach(c => {
        headHtml += `<th class="py-3 px-3 sm:px-4 font-bold text-gray-900">${c}</th>`;
    });
    headHtml += '</tr>';
    document.getElementById('previewTableHead').innerHTML = headHtml;

    // Body
    let bodyHtml = '';
    rows.forEach(r => {
        bodyHtml += `<tr class="hover:bg-gray-50/60 transition-colors">`;
        columns.forEach(c => {
            const val = r[c] || '-';
            const isBold = (c.toLowerCase() === 'size');
            bodyHtml += `<td class="py-3 px-3 sm:px-4 ${isBold ? 'font-black text-gray-900' : 'font-medium text-gray-700'}">${val}</td>`;
        });
        bodyHtml += `</tr>`;
    });
    document.getElementById('previewTableBody').innerHTML = bodyHtml;

    // Sync Hidden Inputs for form submit
    document.getElementById('columnsJsonPayload').value = JSON.stringify(columns);
    document.getElementById('rowsJsonPayload').value = JSON.stringify(rows);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    renderBuilder();
});
</script>
