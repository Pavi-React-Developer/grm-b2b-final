<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Staff Management</h1>
        <p class="text-sm text-gray-500 mt-1">Manage staff members and their permissions.</p>
    </div>
    <div class="mt-4 md:mt-0 flex items-center space-x-3">
        <div class="bg-white border border-gray-200 rounded-lg px-4 py-2 flex items-center text-sm font-medium text-gray-700 shadow-sm cursor-pointer hover:bg-gray-50 transition">
            Last 30 Days
            <svg class="w-4 h-4 ml-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </div>
    </div>
</div>

<?php if ($this->hasPermission('roles', 'create') || $this->hasPermission('roles', 'edit')): ?>
<div class="bg-white rounded-2xl border border-gray-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
    <form action="<?= BASE_URL ?>/admin/staff/roles/store" method="POST" class="p-8">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end border-b border-gray-100 pb-6 mb-6">
            <div class="w-full max-w-md">
                <h2 class="text-lg font-bold text-gray-900" id="form-title">Create / Update Role</h2>
                <p class="text-sm text-gray-500 mb-4">Define a preset role with specific module access.</p>
                
                <input type="hidden" id="edit_role_id" name="role_id" value="">
                <label for="role_name" class="block text-sm font-bold text-gray-700 mb-1">Role Name</label>
                <input type="text" id="role_name" name="role_name" placeholder="e.g. Marketing Manager" required 
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white transition-colors">
            </div>
            
            <button type="submit" id="submit-btn" class="mt-4 md:mt-0 bg-black hover:bg-gray-800 text-white font-bold py-2.5 px-6 rounded-lg transition-colors text-sm">
                Save Role
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="py-4 text-left text-sm font-bold text-gray-900 w-1/3">Module</th>
                        <th class="py-4 text-center text-sm font-bold text-gray-900">
                            <div class="flex flex-col items-center">
                                <span>View</span>
                                <input type="checkbox" onclick="toggleColumn('view', this.checked)" class="mt-2 w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500 cursor-pointer" title="Select All View">
                            </div>
                        </th>
                        <th class="py-4 text-center text-sm font-bold text-gray-900">
                            <div class="flex flex-col items-center">
                                <span>Create</span>
                                <input type="checkbox" onclick="toggleColumn('create', this.checked)" class="mt-2 w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500 cursor-pointer" title="Select All Create">
                            </div>
                        </th>
                        <th class="py-4 text-center text-sm font-bold text-gray-900">
                            <div class="flex flex-col items-center">
                                <span>Edit</span>
                                <input type="checkbox" onclick="toggleColumn('edit', this.checked)" class="mt-2 w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500 cursor-pointer" title="Select All Edit">
                            </div>
                        </th>
                        <th class="py-4 text-center text-sm font-bold text-gray-900">
                            <div class="flex flex-col items-center">
                                <span>Delete</span>
                                <input type="checkbox" onclick="toggleColumn('delete', this.checked)" class="mt-2 w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500 cursor-pointer" title="Select All Delete">
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php foreach ($modules as $groupName => $subModules): ?>
                        <tr class="bg-gray-50 border-y border-gray-100">
                            <td colspan="5" class="py-2 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                <?= htmlspecialchars($groupName) ?>
                            </td>
                        </tr>
                        <?php foreach ($subModules as $key => $details): ?>
                        <tr class="hover:bg-gray-50/30 transition-colors border-b border-gray-50">
                            <td class="py-3 px-4 text-sm font-medium text-gray-700 pl-8">
                                <?= htmlspecialchars($details['label']) ?>
                            </td>
                            <td class="py-3 text-center">
                                <?php if (in_array('view', $details['actions'])): ?>
                                <input type="checkbox" name="permissions[<?= $key ?>][view]" value="1" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <?php else: ?>
                                <span class="text-gray-300 text-xs">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 text-center">
                                <?php if (in_array('create', $details['actions'])): ?>
                                <input type="checkbox" name="permissions[<?= $key ?>][create]" value="1" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <?php else: ?>
                                <span class="text-gray-300 text-xs">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 text-center">
                                <?php if (in_array('edit', $details['actions'])): ?>
                                <input type="checkbox" name="permissions[<?= $key ?>][edit]" value="1" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <?php else: ?>
                                <span class="text-gray-300 text-xs">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 text-center">
                                <?php if (in_array('delete', $details['actions'])): ?>
                                <input type="checkbox" name="permissions[<?= $key ?>][delete]" value="1" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <?php else: ?>
                                <span class="text-gray-300 text-xs">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </form>
</div>
<?php endif; ?>

<!-- Existing Roles List (Optional, but good for reference) -->
<?php if (!empty($roles)): ?>
<div class="mt-8 bg-white rounded-2xl border border-gray-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <h3 class="text-lg font-bold text-gray-900">Existing Roles</h3>
    </div>
    <div class="p-6">
        <div class="flex flex-wrap gap-3">
            <?php foreach ($roles as $r): ?>
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                    <?= htmlspecialchars($r['name']) ?>
                    <?php if ($this->hasPermission('roles', 'edit')): ?>
                    <button type="button" onclick="editRole(<?= $r['id'] ?>, '<?= htmlspecialchars(addslashes($r['name'])) ?>', '<?= htmlspecialchars(addslashes($r['permissions'])) ?>')" class="text-blue-500 hover:text-blue-700 ml-2 focus:outline-none transition-colors" title="Edit Role">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </button>
                    <?php endif; ?>
                    <?php if ($this->hasPermission('roles', 'delete')): ?>
                    <form action="<?= BASE_URL ?>/admin/staff/roles/delete" method="POST" class="inline-flex items-center ml-2 m-0 p-0" onsubmit="return confirm('Are you sure you want to delete this role?');">
                        <input type="hidden" name="id" value="<?= $r['id'] ?>">
                        <button type="submit" class="text-gray-400 hover:text-red-500 focus:outline-none transition-colors" title="Delete Role">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </form>
                    <?php endif; ?>
                </span>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
function editRole(id, name, permissionsJson) {
    document.getElementById('edit_role_id').value = id;
    document.getElementById('role_name').value = name;
    document.getElementById('form-title').textContent = 'Edit Role: ' + name;
    document.getElementById('submit-btn').textContent = 'Update Role';
    
    // Clear all checkboxes
    document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
    
    // Check relevant boxes
    if (permissionsJson) {
        try {
            const perms = JSON.parse(permissionsJson);
            Object.keys(perms).forEach(module => {
                Object.keys(perms[module]).forEach(action => {
                    if (perms[module][action] == 1) {
                        const cb = document.querySelector(`input[name="permissions[${module}][${action}]"]`);
                        if (cb) cb.checked = true;
                    }
                });
            });
        } catch(e) {}
    }
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function toggleColumn(action, isChecked) {
    document.querySelectorAll(`input[type="checkbox"][name$="[${action}]"]`).forEach(cb => {
        if (!cb.disabled) {
            cb.checked = isChecked;
        }
    });
}
</script>
