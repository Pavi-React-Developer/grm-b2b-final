<div class="w-full">
    <!-- Header Section -->
    <div class="mb-8 relative z-10">
        <!-- Breadcrumbs -->
        <div class="text-sm font-medium text-gray-400 mb-2 font-serif tracking-wide">
            Dashboard <span class="mx-1 text-gray-300">›</span> Settings <span class="mx-1 text-gray-300">›</span> <span class="text-brand-700">Staff Management</span>
        </div>
        
        <div class="flex justify-between items-center mb-6 w-full">
            <h2 class="text-5xl font-display font-extrabold text-gray-900 tracking-tight">Staff Management</h2>
            
            <div class="flex items-center space-x-3 ml-auto">
                <button onclick="window.location.reload()" class="bg-white hover:bg-gray-50 text-gray-700 px-6 py-3 rounded-full font-bold text-sm shadow-sm border border-gray-200 transition-all flex items-center uppercase tracking-widest">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Refresh
                </button>
                <button onclick="exportStaffCSV()" class="bg-[#F25996] hover:bg-[#e04481] text-white px-6 py-3 rounded-full font-bold text-sm shadow-sm transition-all flex items-center uppercase tracking-widest cursor-pointer">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export Excel
                </button>
                <?php if ($this->hasPermission('staff_management', 'create')): ?>
                <a href="<?= BASE_URL ?>/admin/staff/create" class="bg-[#F25996] hover:bg-[#e04481] text-white px-6 py-3 rounded-full font-bold text-sm shadow-md transition-all flex items-center uppercase tracking-widest">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Add Staff
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] overflow-hidden">
    <!-- Action Bar -->
    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="relative w-full sm:max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-[#F25996] focus:border-[#F25996] sm:text-sm" placeholder="Search staff...">
        </div>
        <div class="flex items-center space-x-3 w-full sm:w-auto">
            <?php if ($this->hasPermission('staff_management', 'create')): ?>
            <a href="<?= BASE_URL ?>/admin/staff/create" class="flex-1 sm:flex-none bg-[#F25996] hover:bg-[#e04481] text-white font-medium py-2 px-4 rounded-lg shadow-sm flex items-center justify-center transition-colors text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add New Staff
            </a>
            <?php endif; ?>
            <button class="bg-[#1b262c] hover:bg-black text-white font-medium py-2 px-4 rounded-lg shadow-sm flex items-center transition-colors text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export
            </button>
            <button class="p-2 border border-gray-200 rounded-lg text-gray-500 hover:bg-gray-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50/50 border-b border-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Staff</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Email</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Mobile</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Created</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-50">
                <?php if (!empty($staff)): ?>
                    <?php foreach ($staff as $user): ?>
                    <tr class="hover:bg-gray-50/30 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0 rounded-full bg-[#f4fbf4] border border-[#e7f5e8] flex items-center justify-center text-[#2d5939] font-bold">
                                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-gray-900 leading-tight"><?= htmlspecialchars($user['name']) ?></div>
                                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5"><?= htmlspecialchars($user['custom_role_name'] ?? 'STAFF') ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= htmlspecialchars($user['email']) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= !empty($user['phone']) ? htmlspecialchars($user['phone']) : '-' ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">
                            <?= date('n/j/Y', strtotime($user['created_at'])) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <?php if ($user['status'] === 'active'): ?>
                                    <button type="button" onclick="toggleStaffStatus(<?= $user['id'] ?>, 'deactivated')" title="Click to Deactivate"
                                        class="w-8 h-5 bg-[#3f654b] rounded-full flex items-center p-1 cursor-pointer hover:bg-[#2d4d38] transition-colors">
                                        <div class="w-3 h-3 bg-white rounded-full ml-auto"></div>
                                    </button>
                                    <span class="text-xs font-bold text-[#3f654b] bg-[#e7f5e8] px-2 py-0.5 rounded-md">Active</span>
                                <?php else: ?>
                                    <button type="button" onclick="toggleStaffStatus(<?= $user['id'] ?>, 'active')" title="Click to Activate"
                                        class="w-8 h-5 bg-gray-300 rounded-full flex items-center p-1 cursor-pointer hover:bg-gray-400 transition-colors">
                                        <div class="w-3 h-3 bg-white rounded-full"></div>
                                    </button>
                                    <span class="text-xs font-bold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-md"><?= ucfirst(htmlspecialchars($user['status'])) ?></span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-3 items-center">
                                <!-- History Icon -->
                                <button class="text-blue-500 hover:text-blue-700 transition-colors" title="History">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </button>
                                <!-- Edit Icon -->
                                <?php if ($this->hasPermission('staff_management', 'edit')): ?>
                                <a href="<?= BASE_URL ?>/admin/staff/edit?id=<?= $user['id'] ?>" class="text-emerald-500 hover:text-emerald-700 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <?php endif; ?>
                                <!-- Delete Icon -->
                                <?php if ($user['id'] != \Core\Session::get('user_id') && $this->hasPermission('staff_management', 'delete')): ?>
                                <form action="<?= BASE_URL ?>/admin/staff/delete" method="POST" class="inline m-0 p-0" onsubmit="return confirm('Delete this staff member?');">
                                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                    <button type="submit" class="text-red-400 hover:text-red-600 transition-colors" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">
                            No staff members found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function exportStaffCSV() {
    let table = document.querySelector("table");
    if(!table) return;
    
    let rows = Array.from(table.querySelectorAll("tr"));
    let csvContent = "data:text/csv;charset=utf-8,";
    
    rows.forEach(row => {
        let cols = Array.from(row.querySelectorAll("th, td"));
        let rowData = cols.map(col => '"' + col.innerText.replace(/"/g, '""').trim() + '"');
        csvContent += rowData.join(",") + "\r\n";
    });
    
    let encodedUri = encodeURI(csvContent);
    let link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "staff_export_<?= date('Y-m-d') ?>.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function toggleStaffStatus(staffId, newStatus) {
    const label = newStatus === 'active' ? 'activate' : 'deactivate';
    
    // Modern UI Confirmation (Assuming SweetAlert2 or standard DOM modal, we'll use a clean custom styled confirm or simple alert replacement)
    // Since we want modern UI, let's create a custom styled confirm dialog using basic DOM if SweetAlert isn't loaded, or just styled div
    if(typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Are you sure?',
            text: `Do you really want to ${label} this staff member?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3f654b',
            cancelButtonColor: '#d33',
            confirmButtonText: `Yes, ${label} it!`
        }).then((result) => {
            if (result.isConfirmed) {
                executeToggleStatus(staffId, newStatus);
            }
        });
    } else {
        if (confirm(`Are you sure you want to ${label} this staff member?`)) {
            executeToggleStatus(staffId, newStatus);
        }
    }
}

function executeToggleStatus(staffId, newStatus) {
    fetch('<?= BASE_URL ?>/admin/staff/toggle-status', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ id: staffId, status: newStatus })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            showModernAlert('Error', data.message || 'Failed to update status.', 'error');
        }
    })
    .catch((err) => {
        showModernAlert('Error', 'Network error. Please try again.', 'error');
    });
}

function showModernAlert(title, message, type) {
    if(typeof Swal !== 'undefined') {
        Swal.fire(title, message, type);
    } else {
        alert(title + ": " + message);
    }
}
</script>
</div>
