<div class="w-full">
    <!-- Header Section -->
    <div class="mb-8 relative z-10">
        <!-- Breadcrumbs -->
        <div class="text-sm font-medium text-gray-400 mb-2 font-serif tracking-wide">
            Dashboard <span class="mx-1 text-gray-300">›</span> Settings <span class="mx-1 text-gray-300">›</span> <span class="text-brand-700">Order Rules</span>
        </div>
        
        <div class="flex justify-between items-center mb-6 w-full">
            <h2 class="text-5xl font-display font-extrabold text-gray-900 tracking-tight">Order Rules</h2>
            
            <div class="flex items-center space-x-3 ml-auto">
                <button onclick="window.location.reload()" class="bg-white hover:bg-gray-50 text-gray-700 px-6 py-3 rounded-full font-bold text-sm shadow-sm border border-gray-200 transition-all flex items-center uppercase tracking-widest">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Refresh
                </button>
                <button onclick="exportOrderRulesCSV()" class="bg-[#F25996] hover:bg-[#e04481] text-white px-6 py-3 rounded-full font-bold text-sm shadow-sm transition-all flex items-center uppercase tracking-widest cursor-pointer">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export Excel
                </button>
                <?php if ($this->hasPermission('order_rules', 'create')): ?>
                <a href="<?= BASE_URL ?>/admin/order-rules/create" class="bg-[#F25996] hover:bg-[#e04481] text-white px-6 py-3 rounded-full font-bold text-sm shadow-md transition-all flex items-center uppercase tracking-widest">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Add Rule
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">Main Category</th>
                    <th class="px-6 py-4">Second Categories</th>
                    <th class="px-6 py-4">Min Amount (₹)</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                <?php if (empty($rules)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            No order rules found. <a href="<?= BASE_URL ?>/admin/order-rules/create" class="text-brand-600 hover:underline">Create one</a>.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($rules as $rule): ?>
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-6 py-4 text-gray-500">#<?= $rule['id'] ?></td>
                            <td class="px-6 py-4 font-medium text-gray-900"><?= htmlspecialchars($rule['category_name']) ?></td>
                            <td class="px-6 py-4 text-gray-600">
                                <?php 
                                $secondRules = json_decode($rule['second_category_rules'], true);
                                if (!empty($secondRules)): 
                                ?>
                                    <span class="text-xs bg-brand-100 text-brand-800 px-2 py-1 rounded"><?= count($secondRules) ?> Rules</span>
                                <?php else: ?>
                                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded">None</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-900"> ₹<?= number_format($rule['min_amount']) ?></td>
                            <td class="px-6 py-4">
                                <?php if ($rule['status'] === 'active'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-3 opacity-100  transition-opacity">
                                    <?php if ($this->hasPermission('order_rules', 'edit')): ?>
                                    <a href="<?= BASE_URL ?>/admin/order-rules/edit?id=<?= $rule['id'] ?>" class="text-blue-600 hover:text-blue-800" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <?php endif; ?>
                                    <?php if ($this->hasPermission('order_rules', 'delete')): ?>
                                    <form action="<?= BASE_URL ?>/admin/order-rules/delete" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this rule?');">
                                        <input type="hidden" name="id" value="<?= $rule['id'] ?>">
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
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

<script>
function exportOrderRulesCSV() {
    let table = document.querySelector("table");
    if (!table) return;
    
    let rows = Array.from(table.querySelectorAll("tr"));
    if (rows.length === 0) {
        alert("No order rules to export.");
        return;
    }
    
    let csvRows = [];
    
    rows.forEach(row => {
        let cols = Array.from(row.querySelectorAll("th, td"));
        if (cols.length === 0) return;
        
        // If it's the "No order rules found" empty message row
        if (cols.length === 1 && cols[0].getAttribute("colspan")) {
            return;
        }
        
        // Exclude the Actions column (last column)
        let exportCols = cols.length > 1 ? cols.slice(0, -1) : cols;
        
        let rowData = exportCols.map(col => {
            let text = col.innerText.replace(/\r?\n|\r/g, " ").replace(/"/g, '""').trim();
            return '"' + text + '"';
        });
        
        csvRows.push(rowData.join(","));
    });
    
    if (csvRows.length <= 1) {
        alert("No order rules data available to export.");
        return;
    }
    
    let csvString = "\uFEFF" + csvRows.join("\r\n");
    let blob = new Blob([csvString], { type: "text/csv;charset=utf-8;" });
    let url = URL.createObjectURL(blob);
    
    let link = document.createElement("a");
    link.setAttribute("href", url);
    link.setAttribute("download", "order_rules_export_<?= date('Y-m-d') ?>.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
}
</script>
</div>
