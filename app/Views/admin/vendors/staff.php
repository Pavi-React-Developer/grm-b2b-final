<?php
// Vendor Staff & Multi-User RBAC Management View
?>
<div class="w-full space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-gray-200/80 shadow-2xs">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Vendor Staff & Role Management</h1>
            <p class="text-xs text-gray-500 mt-1">Configure multi-user access for vendor operations (Warehouse Staff, Billing/Accounts, Catalog Manager)</p>
        </div>
        <button type="button" onclick="document.getElementById('add-staff-modal').classList.remove('hidden')" class="px-5 py-2.5 bg-[#F25996] hover:bg-[#e04481] text-white font-bold text-xs rounded-xl shadow-sm flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Add New Staff Account
        </button>
    </div>

    <!-- Staff List Table -->
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-2xs overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-sm text-gray-900">Active Vendor Staff Accounts</h3>
            <span class="text-xs text-indigo-600 font-semibold bg-indigo-50 px-2.5 py-1 rounded-full">Multi-User RBAC Enabled</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200/80 text-gray-500 font-bold uppercase tracking-wider">
                        <th class="py-3.5 px-4">Staff Name</th>
                        <th class="py-3.5 px-4">Email / Login</th>
                        <th class="py-3.5 px-4">Role & Permissions</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Created Date</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700 font-medium">
                    <?php if (empty($staffList)): ?>
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-400">
                                <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                No staff accounts created yet. Click "Add New Staff Account" above to invite team members.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($staffList as $st): ?>
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="py-3.5 px-4 font-bold text-gray-900"><?= htmlspecialchars($st['name']) ?></td>
                                <td class="py-3.5 px-4 font-mono text-gray-600"><?= htmlspecialchars($st['email']) ?></td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-800 font-bold rounded-lg border border-slate-200 uppercase text-[10px]">
                                        <?= str_replace('vendor_', '', htmlspecialchars($st['role'])) ?>
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full <?= $st['status'] === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-600 border border-red-200' ?>">
                                        <?= ucfirst($st['status']) ?>
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-gray-400"><?= date('d M Y', strtotime($st['created_at'])) ?></td>
                                <td class="py-3.5 px-4 text-right">
                                    <button class="text-indigo-600 hover:text-indigo-900 font-bold text-xs">Edit Role</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Add Staff -->
<div id="add-staff-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-xs p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl border border-gray-200">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <h3 class="font-extrabold text-base text-gray-900">Create Staff Sub-Account</h3>
            <button type="button" onclick="document.getElementById('add-staff-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 font-bold">&times;</button>
        </div>
        <form action="<?= BASE_URL ?>/admin/vendors/staff/create" method="POST" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Staff Member Name</label>
                <input type="text" name="name" required class="w-full text-xs p-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Email Address (Login)</label>
                <input type="email" name="email" required class="w-full text-xs p-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Role / Permissions</label>
                <select name="role" class="w-full text-xs p-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    <option value="vendor_warehouse">Warehouse Staff (Dispatches & Tracking Only)</option>
                    <option value="vendor_billing">Billing & Accounts (Payouts & Invoices Only)</option>
                    <option value="vendor_catalog">Catalog Manager (Product & Inventory Only)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Temporary Password</label>
                <input type="password" name="password" required class="w-full text-xs p-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>
            <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('add-staff-modal').classList.add('hidden')" class="px-4 py-2 bg-gray-100 text-gray-700 font-bold text-xs rounded-xl hover:bg-gray-200">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-[#F25996] hover:bg-[#e04481] text-white font-bold text-xs rounded-xl shadow-sm transition">Create Account</button>
            </div>
        </form>
    </div>
</div>
