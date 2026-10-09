<div class="w-full">
    <!-- Top Breadcrumbs & Header -->
    <div class="mb-8 relative z-10">
        <div class="text-sm font-medium text-gray-400 mb-2 font-serif tracking-wide">
            Dashboard <span class="mx-1 text-gray-300">›</span> <a href="<?= BASE_URL ?>/admin/vendors" class="hover:text-brand-600">Vendor Management</a> <span class="mx-1 text-gray-300">›</span> <span class="text-brand-700">Vendor Types</span>
        </div>

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h2 class="text-5xl font-display font-extrabold text-gray-900 tracking-tight">Vendor Types</h2>
                <p class="text-sm text-gray-500 mt-1">Manage business classification types dynamically. Only active types appear in the vendor registration form.</p>
            </div>

            <div class="flex items-center space-x-3">
                <button onclick="window.location.reload()" class="bg-white hover:bg-gray-50 text-gray-700 px-6 py-3 rounded-full font-bold text-sm shadow-sm border border-gray-200 transition-all flex items-center uppercase tracking-widest">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Refresh
                </button>
                <button onclick="openAddTypeModal()" class="bg-[#F25996] hover:bg-[#e04481] text-white px-6 py-3 rounded-full font-bold text-sm shadow-sm transition-all flex items-center gap-2 uppercase tracking-widest">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Vendor Type
                </button>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex items-center gap-2 mb-8 border-b border-gray-200 pb-3">
        <a href="<?= BASE_URL ?>/admin/vendors/dashboard" class="px-4 py-2 text-gray-500 hover:text-gray-900 font-medium text-sm rounded-t-lg transition flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            Revenue &amp; Analytics
        </a>
        <a href="<?= BASE_URL ?>/admin/vendors" class="px-4 py-2 text-gray-500 hover:text-gray-900 font-medium text-sm rounded-t-lg transition flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            Active Vendors
        </a>
        <a href="<?= BASE_URL ?>/admin/vendors/pending" class="px-4 py-2 text-gray-500 hover:text-gray-900 font-medium text-sm rounded-t-lg transition flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Pending Approvals
        </a>
        <a href="<?= BASE_URL ?>/admin/vendors/types" class="px-4 py-2 bg-brand-50 text-brand-700 border-b-2 border-brand-700 font-bold text-sm rounded-t-lg transition flex items-center gap-2">
            <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            Vendor Types (<?= count($types) ?>)
        </a>
    </div>

    <!-- Alert / Notice Banner -->
    <div class="mb-8 p-4 bg-pink-50/70 border border-pink-100 rounded-2xl flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl bg-[#F25996] text-white flex items-center justify-center shrink-0 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="text-xs text-gray-700">
            <span class="font-bold text-gray-900">Registration Form Rule:</span> Only vendor types with <span class="font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">Active</span> status will appear in the storefront vendor registration dropdown. Disabled types are hidden from applicants.
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Defined Types</p>
                <h3 class="text-3xl font-extrabold text-gray-900 mt-1"><?= $totalTypes ?></h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Active in Registration</p>
                <h3 class="text-3xl font-extrabold text-emerald-600 mt-1"><?= $activeTypes ?></h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Hidden / Disabled</p>
                <h3 class="text-3xl font-extrabold text-gray-400 mt-1"><?= $hiddenTypes ?></h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gray-100 text-gray-500 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Associated Vendors</p>
                <h3 class="text-3xl font-extrabold text-indigo-600 mt-1"><?= $totalVendors ?></h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>
    </div>

    <!-- Vendor Types Table Card -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="font-bold text-gray-900 text-lg">Vendor Business Types List</h3>
                <p class="text-xs text-gray-500 mt-0.5">Toggle status to enable or disable options from appearing on the vendor registration page.</p>
            </div>
            <span class="px-3 py-1 bg-pink-50 text-[#F25996] text-xs font-bold rounded-full border border-pink-100 self-start sm:self-auto">
                <?= count($types) ?> Total Types
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-gray-100">
                        <th class="py-4 px-6">Order</th>
                        <th class="py-4 px-6">Vendor Type Name</th>
                        <th class="py-4 px-6">System Slug / Key</th>
                        <th class="py-4 px-6">Description</th>
                        <th class="py-4 px-6 text-center">Associated Vendors</th>
                        <th class="py-4 px-6 text-center">Registration Form Status</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    <?php if (empty($types)): ?>
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                No vendor types found. Click "Add Vendor Type" to create one.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($types as $type): ?>
                            <tr class="hover:bg-gray-50/60 transition group" id="type-row-<?= $type['id'] ?>">
                                <td class="py-4 px-6 font-mono text-xs text-gray-400">
                                    #<?= (int)$type['display_order'] ?>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl <?= $type['is_active'] ? 'bg-pink-50 text-[#F25996] border border-pink-100' : 'bg-gray-100 text-gray-400' ?> flex items-center justify-center font-bold text-sm shrink-0">
                                            <?= strtoupper(substr($type['name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <span class="font-bold text-gray-900 block"><?= htmlspecialchars($type['name']) ?></span>
                                            <?php if ($type['slug'] === 'wholesaler'): ?>
                                                <span class="text-[10px] text-pink-600 font-bold uppercase tracking-wider">Default Registration Option</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="font-mono text-xs bg-gray-100 text-gray-700 px-2.5 py-1 rounded-md border border-gray-200">
                                        <?= htmlspecialchars($type['slug']) ?>
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-gray-500 text-xs max-w-xs truncate">
                                    <?= htmlspecialchars($type['description'] ?: 'No description provided') ?>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold <?= $type['vendor_count'] > 0 ? 'bg-indigo-50 text-indigo-700 border border-indigo-100' : 'bg-gray-50 text-gray-400' ?>">
                                        <?= (int)$type['vendor_count'] ?> Vendors
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <form action="<?= BASE_URL ?>/admin/vendors/types/toggle-status" method="POST" class="inline-block">
                                        <input type="hidden" name="id" value="<?= $type['id'] ?>">
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition-all shadow-xs <?= $type['is_active'] ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-gray-100 text-gray-500 border border-gray-200 hover:bg-gray-200' ?>" title="Click to toggle visibility in registration form">
                                            <span class="w-2 h-2 rounded-full <?= $type['is_active'] ? 'bg-emerald-500 animate-pulse' : 'bg-gray-400' ?>"></span>
                                            <?= $type['is_active'] ? 'Active (Visible)' : 'Hidden (Disabled)' ?>
                                        </button>
                                    </form>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button onclick="openEditTypeModal(<?= htmlspecialchars(json_encode($type)) ?>)" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition" title="Edit Vendor Type">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </button>
                                        <?php if ($type['vendor_count'] == 0): ?>
                                            <form action="<?= BASE_URL ?>/admin/vendors/types/delete" method="POST" onsubmit="return confirm('Are you sure you want to delete this vendor type?');" class="inline-block">
                                                <input type="hidden" name="id" value="<?= $type['id'] ?>">
                                                <button type="submit" class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition" title="Delete Type">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="p-2 text-gray-300 cursor-not-allowed" title="Cannot delete: <?= $type['vendor_count'] ?> vendor(s) are assigned to this type">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Add Vendor Type -->
<div id="add-type-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-60 backdrop-blur-xs" onclick="closeAddTypeModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
            <div class="p-6 sm:p-8">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-pink-50 text-[#F25996] flex items-center justify-center font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Add New Vendor Type</h3>
                            <p class="text-xs text-gray-400">Define a new vendor classification option.</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeAddTypeModal()" class="text-gray-400 hover:text-gray-600 rounded-full p-1.5 hover:bg-gray-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form action="<?= BASE_URL ?>/admin/vendors/types/create" method="POST" class="space-y-5">
                    <div>
                        <label for="add_name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Type Name *</label>
                        <input type="text" id="add_name" name="name" required placeholder="e.g. Retail Stockist, Direct Importer" oninput="generateSlug(this.value, 'add_slug')" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-[#F25996] focus:border-transparent transition">
                    </div>

                    <div>
                        <label for="add_slug" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Slug / System Key *</label>
                        <input type="text" id="add_slug" name="slug" required placeholder="e.g. retail_stockist" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm font-mono text-gray-700 bg-gray-50 focus:ring-2 focus:ring-[#F25996] focus:border-transparent transition">
                        <p class="text-[11px] text-gray-400 mt-1">Unique identifier used internally and stored in database records.</p>
                    </div>

                    <div>
                        <label for="add_description" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Description</label>
                        <textarea id="add_description" name="description" rows="2" placeholder="Brief description of this business model" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-[#F25996] focus:border-transparent transition"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="add_display_order" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Display Order</label>
                            <input type="text" id="add_display_order" name="display_order" value="0" min="0" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-[#F25996] focus:border-transparent transition">
                        </div>

                        <div class="flex items-center pt-6">
                            <label class="relative flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" checked class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#F25996]"></div>
                                <span class="ml-3 text-xs font-bold text-gray-700">Active / Visible</span>
                            </label>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex items-center justify-end space-x-3">
                        <button type="button" onclick="closeAddTypeModal()" class="px-5 py-2.5 rounded-full text-xs font-bold text-gray-600 hover:bg-gray-100 transition uppercase tracking-wider">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2.5 rounded-full text-xs font-bold bg-[#F25996] hover:bg-[#e04481] text-white shadow-sm transition uppercase tracking-wider">
                            Create Type
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Edit Vendor Type -->
<div id="edit-type-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-60 backdrop-blur-xs" onclick="closeEditTypeModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
            <div class="p-6 sm:p-8">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Edit Vendor Type</h3>
                            <p class="text-xs text-gray-400">Update classification settings and registration visibility.</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeEditTypeModal()" class="text-gray-400 hover:text-gray-600 rounded-full p-1.5 hover:bg-gray-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form action="<?= BASE_URL ?>/admin/vendors/types/update" method="POST" class="space-y-5">
                    <input type="hidden" id="edit_id" name="id">

                    <div>
                        <label for="edit_name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Type Name *</label>
                        <input type="text" id="edit_name" name="name" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-[#F25996] focus:border-transparent transition">
                    </div>

                    <div>
                        <label for="edit_slug" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Slug / System Key *</label>
                        <input type="text" id="edit_slug" name="slug" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm font-mono text-gray-700 bg-gray-50 focus:ring-2 focus:ring-[#F25996] focus:border-transparent transition">
                    </div>

                    <div>
                        <label for="edit_description" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Description</label>
                        <textarea id="edit_description" name="description" rows="2" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-[#F25996] focus:border-transparent transition"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="edit_display_order" class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Display Order</label>
                            <input type="text" id="edit_display_order" name="display_order" min="0" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-[#F25996] focus:border-transparent transition">
                        </div>

                        <div class="flex items-center pt-6">
                            <label class="relative flex items-center cursor-pointer">
                                <input type="checkbox" id="edit_is_active" name="is_active" value="1" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#F25996]"></div>
                                <span class="ml-3 text-xs font-bold text-gray-700">Active / Visible</span>
                            </label>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex items-center justify-end space-x-3">
                        <button type="button" onclick="closeEditTypeModal()" class="px-5 py-2.5 rounded-full text-xs font-bold text-gray-600 hover:bg-gray-100 transition uppercase tracking-wider">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2.5 rounded-full text-xs font-bold bg-[#F25996] hover:bg-[#e04481] text-white shadow-sm transition uppercase tracking-wider">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function generateSlug(text, targetId) {
    const slug = text.toLowerCase()
        .replace(/[^\w\s-]/g, '')
        .trim()
        .replace(/[\s_-]+/g, '_')
        .replace(/^-+|-+$/g, '');
    document.getElementById(targetId).value = slug;
}

function openAddTypeModal() {
    document.getElementById('add-type-modal').classList.remove('hidden');
    document.getElementById('add_name').focus();
}

function closeAddTypeModal() {
    document.getElementById('add-type-modal').classList.add('hidden');
}

function openEditTypeModal(type) {
    document.getElementById('edit_id').value = type.id;
    document.getElementById('edit_name').value = type.name;
    document.getElementById('edit_slug').value = type.slug;
    document.getElementById('edit_description').value = type.description || '';
    document.getElementById('edit_display_order').value = type.display_order || 0;
    document.getElementById('edit_is_active').checked = parseInt(type.is_active) === 1;
    
    document.getElementById('edit-type-modal').classList.remove('hidden');
    document.getElementById('edit_name').focus();
}

function closeEditTypeModal() {
    document.getElementById('edit-type-modal').classList.add('hidden');
}
</script>
