<div class="w-full">
    <!-- Header Section -->
    <div class="mb-8 relative z-10">
        <!-- Breadcrumbs -->
        <div class="text-sm font-medium text-gray-400 mb-2 font-serif tracking-wide">
            Dashboard <span class="mx-1 text-gray-300">›</span> Catalog Management <span class="mx-1 text-gray-300">›</span> <span class="text-brand-700">Sub-Categories</span>
        </div>
        
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-5xl font-display font-extrabold text-gray-900 tracking-tight">Sub-Categories</h2>
            
            <div class="flex items-center space-x-3">
                <button onclick="window.location.reload()" class="bg-white hover:bg-gray-50 text-gray-700 px-6 py-3 rounded-full font-bold text-sm shadow-sm border border-gray-200 transition-all flex items-center uppercase tracking-widest">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Refresh
                </button>
                <button class="bg-brand-600/90 hover:bg-brand-700 text-white px-6 py-3 rounded-full font-bold text-sm shadow-sm transition-all flex items-center uppercase tracking-widest" onclick="exportSubCategories()">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export Excel
                </button>
                <?php if ($this->hasPermission('subcategories', 'create')): ?>
                <button onclick="document.getElementById('add-modal').classList.remove('hidden')" class="bg-brand-600 hover:bg-brand-700 text-white px-6 py-3 rounded-full font-bold text-sm shadow-md transition-all flex items-center uppercase tracking-widest">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Add Sub-Category
                </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="bg-white rounded-full shadow-sm border border-gray-100 p-1.5 flex items-center">
            <div class="flex-grow flex items-center pl-5">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" id="subSearchInput" onkeyup="filterSubCategories()" placeholder="Search subcategories..." class="w-full pl-3 pr-4 py-2.5 bg-transparent border-none focus:ring-0 text-gray-700 placeholder-gray-400 outline-none font-medium">
            </div>
            <div class="border-l border-gray-200 pl-2 pr-2">
                <select id="subCategoryFilter" onchange="filterSubCategories()" class="bg-transparent border-none text-gray-600 focus:ring-0 py-2.5 pr-8 pl-4 font-semibold outline-none cursor-pointer max-w-[200px] truncate">
                    <option value="">All Categories</option>
                    <?php foreach($categories as $cat): ?>
                        <option value="<?= htmlspecialchars(strtolower($cat['name'])) ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <!-- Background Decoration -->
    <div class="absolute top-0 left-0 w-full h-96 bg-gradient-to-b from-brand-50/40 to-transparent pointer-events-none -z-10"></div>

    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl shadow-brand-900/5 border border-white/60 overflow-hidden ring-1 ring-gray-900/5">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-50/90 to-white/90 backdrop-blur text-gray-500 text-xs uppercase tracking-widest border-b border-gray-100">
                        <th class="px-6 py-5 font-semibold text-gray-500 w-16">ID</th>
                        <th class="px-6 py-5 font-semibold text-gray-500">Parent Category</th>
                        <th class="px-6 py-5 font-semibold text-gray-500">Name</th>
                        <th class="px-6 py-5 font-semibold text-gray-500">Slug</th>
                        <th class="px-6 py-5 font-semibold text-gray-500">Status</th>
                        <th class="px-6 py-5 font-semibold text-gray-500 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    <?php if(empty($subCategories)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-gray-400 font-medium">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                No subcategories found. Let's create your first one.
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($subCategories as $index => $sub): ?>
                        <tr class="group hover:bg-brand-50/40 transition-colors duration-300 data-row" data-parent="<?= htmlspecialchars(strtolower($sub['category_name'])) ?>">
                            <td class="px-6 py-5 text-gray-400 font-medium">
                                <span class="group-hover:text-brand-600 transition-colors duration-300">#<?= $index + 1 ?></span>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-gray-50 border border-gray-100 text-[11px] font-bold text-gray-700 uppercase tracking-wider">
                                    <?= htmlspecialchars($sub['category_name']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="font-semibold text-gray-900 text-base group-hover:text-brand-700 transition-colors"><?= htmlspecialchars($sub['name']) ?></div>
                            </td>
                            <td class="px-6 py-5 text-gray-500 font-mono text-[11px] bg-gray-50 px-2 py-1 rounded border border-gray-100 inline-block mt-2">
                                <?= htmlspecialchars($sub['slug']) ?>
                            </td>
                            <td class="px-6 py-5">
                                <?php if ($sub['status'] === 'active'): ?>
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50/80 text-emerald-600 border border-emerald-100 shadow-sm"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>Active</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-50/80 text-gray-600 border border-gray-200 shadow-sm"><span class="w-1.5 h-1.5 rounded-full bg-gray-400 mr-1.5"></span>Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-5 text-right space-x-2">
                                <?php if ($this->hasPermission('subcategories', 'edit')): ?>
                                <button onclick="openEditModal(<?= htmlspecialchars(json_encode($sub)) ?>)" class="p-2 text-gray-400 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition-all duration-200 group/btn" title="Edit">
                                    <svg class="w-5 h-5 group-hover/btn:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <?php endif; ?>
                                <?php if ($this->hasPermission('subcategories', 'delete')): ?>
                                <button onclick="openDeleteModal(<?= $sub['id'] ?>)" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200 group/btn" title="Delete">
                                    <svg class="w-5 h-5 group-hover/btn:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="add-modal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 transition-opacity">
    <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-white transform transition-all">
        <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-xl font-display font-bold text-gray-900">Add Subcategory</h3>
            <button onclick="document.getElementById('add-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition-colors bg-white p-2 rounded-full shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="<?= BASE_URL ?>/admin/catalog/subcategories/store" method="POST" class="p-8 space-y-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Parent Category *</label>
                <select name="category_id" required class="w-full border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 px-4 py-2.5 transition-all bg-white font-medium">
                    <option value="">Select a Category</option>
                    <?php foreach($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Name *</label>
                <input type="text" name="name" required class="w-full border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 px-4 py-2.5 transition-all" oninput="this.form.slug.value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '')">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Slug *</label>
                <input type="text" name="slug" required class="w-full border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 px-4 py-2.5 transition-all bg-gray-50/50 text-gray-600 font-mono text-sm">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 px-4 py-2.5 transition-all bg-white font-medium">
                    <option value="active">🟢 Active</option>
                    <option value="inactive">⚪ Inactive</option>
                </select>
            </div>
            <div class="pt-6 mt-6 border-t border-gray-100 flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('add-modal').classList.add('hidden')" class="px-5 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-brand-600 to-brand-700 hover:from-brand-500 hover:to-brand-600 text-white rounded-xl text-sm font-semibold shadow-lg shadow-brand-600/30 transform hover:-translate-y-0.5 transition-all">Save Subcategory</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="edit-modal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 transition-opacity">
    <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-white transform transition-all">
        <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-xl font-display font-bold text-gray-900">Edit Subcategory</h3>
            <button onclick="document.getElementById('edit-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition-colors bg-white p-2 rounded-full shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="<?= BASE_URL ?>/admin/catalog/subcategories/update" method="POST" class="p-8 space-y-5">
            <input type="hidden" name="id" id="edit-id">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Parent Category *</label>
                <select name="category_id" id="edit-category_id" required class="w-full border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 px-4 py-2.5 transition-all bg-white font-medium">
                    <?php foreach($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Name *</label>
                <input type="text" name="name" id="edit-name" required class="w-full border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 px-4 py-2.5 transition-all">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Slug *</label>
                <input type="text" name="slug" id="edit-slug" required class="w-full border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 px-4 py-2.5 transition-all bg-gray-50/50 text-gray-600 font-mono text-sm">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Status</label>
                <select name="status" id="edit-status" class="w-full border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 px-4 py-2.5 transition-all bg-white font-medium">
                    <option value="active">🟢 Active</option>
                    <option value="inactive">⚪ Inactive</option>
                </select>
            </div>
            <div class="pt-6 mt-6 border-t border-gray-100 flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('edit-modal').classList.add('hidden')" class="px-5 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">Cancel</button>
                <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-brand-600 to-brand-700 hover:from-brand-500 hover:to-brand-600 text-white rounded-xl text-sm font-semibold shadow-lg shadow-brand-600/30 transform hover:-translate-y-0.5 transition-all">Update Subcategory</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div id="delete-modal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 transition-opacity">
    <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden text-center p-8 border border-white transform transition-all">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-50 border-4 border-red-100/50 mb-5 relative">
            <svg class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <div class="absolute -right-1 -top-1 w-4 h-4 bg-red-500 rounded-full animate-ping opacity-75"></div>
        </div>
        <h3 class="text-xl font-display font-bold text-gray-900 mb-2">Delete Subcategory?</h3>
        <p class="text-sm text-gray-500 mb-8 font-medium">Are you sure you want to delete this subcategory? This action <strong class="text-gray-900">cannot be undone</strong>.</p>
        <form action="<?= BASE_URL ?>/admin/catalog/subcategories/delete" method="POST" class="flex space-x-3">
            <input type="hidden" name="id" id="delete-id">
            <button type="button" onclick="document.getElementById('delete-modal').classList.add('hidden')" class="flex-1 px-5 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">Cancel</button>
            <button type="submit" class="flex-1 px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-500 hover:to-red-400 text-white rounded-xl text-sm font-semibold shadow-lg shadow-red-500/30 transform hover:-translate-y-0.5 transition-all">Yes, Delete</button>
        </form>
    </div>
</div>

<script>
const allSubCategories = <?= json_encode($subCategories ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

function exportSubCategories() {
    if (allSubCategories.length === 0) {
        alert('No subcategories to export.');
        return;
    }
    const headers = ['ID', 'Parent Category', 'Name', 'Slug', 'Status'];
    let csvContent = "data:text/csv;charset=utf-8," + headers.join(",") + "\n";
    allSubCategories.forEach(s => {
        const row = [
            s.id,
            `"${(s.category_name || '').replace(/"/g, '""')}"`,
            `"${(s.name || '').replace(/"/g, '""')}"`,
            s.slug,
            s.status
        ];
        csvContent += row.join(",") + "\n";
    });
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "subcategories_export.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function filterSubCategories() {
    const searchVal = document.getElementById('subSearchInput').value.toLowerCase();
    const catVal = document.getElementById('subCategoryFilter').value.toLowerCase();
    
    const rows = document.querySelectorAll('tbody tr.data-row');
    
    rows.forEach(row => {
        const textContent = row.innerText.toLowerCase();
        const parentCat = row.getAttribute('data-parent') || '';
        
        const matchesSearch = textContent.includes(searchVal);
        const matchesCat = catVal ? parentCat === catVal : true;
        
        if (matchesSearch && matchesCat) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function openEditModal(sub) {
    document.getElementById('edit-id').value = sub.id;
    document.getElementById('edit-category_id').value = sub.category_id;
    document.getElementById('edit-name').value = sub.name;
    document.getElementById('edit-slug').value = sub.slug;
    document.getElementById('edit-status').value = sub.status;
    document.getElementById('edit-modal').classList.remove('hidden');
}

function openDeleteModal(id) {
    document.getElementById('delete-id').value = id;
    document.getElementById('delete-modal').classList.remove('hidden');
}
</script>
