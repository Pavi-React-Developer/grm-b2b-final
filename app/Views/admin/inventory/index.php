<!-- Inventory Management View -->
<div class="w-full">
    
    <!-- Header Section -->
    <div class="mb-8 relative z-10">
        <!-- Breadcrumbs -->
        <div class="text-sm font-medium text-gray-400 mb-2 font-serif tracking-wide">
            Dashboard <span class="mx-1 text-gray-300">›</span> Inventory Management <span class="mx-1 text-gray-300">›</span> <span class="text-brand-700">Inventory Stock</span>
        </div>
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 w-full">
            <div>
                <h1 class="text-4xl sm:text-5xl font-display font-extrabold text-gray-900 tracking-tight">Inventory Management</h1>
                <p class="text-sm text-gray-500 mt-1">Real-time stock tracking, inventory alerts, and warehouse SKU levels.</p>
            </div>
            <div class="flex items-center space-x-3 ml-auto">
                <button onclick="window.location.reload()" class="bg-white hover:bg-gray-50 text-slate-700 text-xs font-bold uppercase tracking-wider px-5 py-2.5 rounded-full border border-slate-200 shadow-xs flex items-center gap-2 transition active:scale-95 group">
                    <svg class="w-3.5 h-3.5 text-slate-500 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span>REFRESH</span>
                </button>
                <button onclick="exportInventoryCSV()" class="bg-[#F25996] hover:bg-[#d94480] text-white text-xs font-bold uppercase tracking-wider px-5 py-2.5 rounded-full shadow-xs flex items-center gap-2 transition active:scale-95 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <span>EXPORT EXCEL</span>
                </button>
            </div>
        </div>

        <!-- Dashboard KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 mb-8">
            <!-- Total Products -->
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 font-semibold mb-1">Total Products</p>
                    <p class="text-3xl font-extrabold text-gray-900"><?= number_format($summary['total_products'] ?? 0) ?></p>
                </div>
                <div class="w-11 h-11 flex items-center justify-center text-blue-500 bg-blue-50 rounded-xl">
                    <svg class="w-6 h-6 icon-24" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
            </div>

            <!-- Total Stock (Admin Only View) -->
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-1.5 mb-1">
                        <p class="text-xs text-gray-400 font-semibold">Total Stock</p>
                        <span class="text-[9px] bg-purple-100 text-purple-700 font-bold px-1.5 py-0.5 rounded">Admin</span>
                    </div>
                    <p class="text-3xl font-extrabold text-gray-900"><?= number_format(max(0, (int)($summary['total_stock'] ?? 0))) ?></p>
                </div>
                <div class="w-11 h-11 flex items-center justify-center text-purple-600 bg-purple-50 rounded-xl">
                    <svg class="w-6 h-6 icon-24" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
            </div>

            <!-- Current Stock (Live Available Stock) -->
            <div class="bg-white rounded-2xl p-5 border border-emerald-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs text-emerald-700 font-semibold mb-1">Current Stock</p>
                    <p class="text-3xl font-extrabold text-emerald-600"><?= number_format(max(0, (int)($summary['current_stock'] ?? 0))) ?></p>
                </div>
                <div class="w-11 h-11 flex items-center justify-center text-emerald-600 bg-emerald-50 rounded-xl">
                    <svg class="w-6 h-6 icon-24" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <!-- Low Stock -->
            <div class="bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl p-5 shadow-sm flex items-center justify-between text-white">
                <div>
                    <p class="text-xs text-amber-100 font-semibold mb-1">Low Stock</p>
                    <p class="text-3xl font-extrabold"><?= number_format($summary['low_stock_count'] ?? 0) ?></p>
                </div>
                <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 icon-24" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>

            <!-- Out of Stock -->
            <div class="bg-gradient-to-br from-rose-500 to-red-600 rounded-2xl p-5 shadow-sm flex items-center justify-between text-white">
                <div>
                    <p class="text-xs text-rose-100 font-semibold mb-1">Out of Stock</p>
                    <p class="text-3xl font-extrabold"><?= number_format($summary['out_of_stock_count'] ?? 0) ?></p>
                </div>
                <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 icon-24" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
            </div>
        </div>

        <!-- Low Stock Alerts -->
        <div class="bg-white rounded-2xl p-6 mb-8 border border-gray-100 shadow-sm">
            <h3 class="text-orange-600 font-bold mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 icon-20" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Low Stock Alerts
            </h3>
            
            <div class="flex overflow-x-auto space-x-4 pb-2 scrollbar-thin">
                <?php if(empty($lowStockAlerts)): ?>
                    <p class="text-sm text-gray-500 italic">No low stock items currently.</p>
                <?php else: ?>
                    <?php foreach($lowStockAlerts as $alert): ?>
                        <div class="border-l-4 border-amber-500 bg-amber-50/50 rounded-r-xl p-4 shadow-2xs min-w-[280px] border-y border-r border-amber-100">
                            <div class="flex justify-between items-start mb-1">
                                <h4 class="font-bold text-gray-800 text-sm truncate mr-4"><?= htmlspecialchars($alert['product_name']) ?></h4>
                                <div class="text-right">
                                    <span class="text-[10px] text-gray-400 font-bold tracking-wider">CURRENT</span>
                                    <p class="font-bold text-xl text-amber-600 leading-none"><?= max(0, (int)$alert['remaining_stock']) ?></p>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 font-medium">SKU: <?= htmlspecialchars($alert['sku']) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Inventory List Table -->
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden mb-8">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-200">
                        <th class="py-4 px-6 text-xs font-bold tracking-wider text-gray-600 uppercase">Product</th>
                        <th class="py-4 px-6 text-xs font-bold tracking-wider text-gray-600 uppercase">Category</th>
                        <th class="py-4 px-6 text-xs font-bold tracking-wider text-gray-600 uppercase">SKU</th>
                        <th class="py-4 px-6 text-xs font-bold tracking-wider text-gray-600 uppercase text-center">Current Stock</th>
                        <th class="py-4 px-6 text-xs font-bold tracking-wider text-gray-600 uppercase text-center">Status</th>
                        <th class="py-4 px-6 text-xs font-bold tracking-wider text-gray-600 uppercase text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if(empty($inventoryList)): ?>
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500">No inventory found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($inventoryList as $item): ?>
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg overflow-hidden border border-gray-200 mr-3 flex-shrink-0 bg-white">
                                            <?php if($item['image_path']): ?>
                                                <img src="<?= htmlspecialchars(get_image_url($item['image_path'])) ?>" alt="" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400"><svg class="w-5 h-5 icon-20" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <span class="font-bold text-gray-900 text-sm block"><?= htmlspecialchars($item['product_name']) ?></span>
                                            <?php if (!empty($item['vendor_store_name'])): ?>
                                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold bg-indigo-50 text-indigo-700 px-1.5 py-0.5 rounded border border-indigo-100 mt-0.5" title="Vendor: <?= htmlspecialchars($item['vendor_store_name']) ?>">
                                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                                    <?= htmlspecialchars($item['vendor_store_name']) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-sm font-medium text-gray-600">
                                    <?= htmlspecialchars($item['category_name'] ?? 'Uncategorized') ?>
                                </td>
                                <td class="py-4 px-6 text-sm font-semibold text-gray-800">
                                    <?= $item['variant_count'] > 1 ? '<span class="text-xs text-gray-500 italic">Multiple SKUs (' . $item['variant_count'] . ')</span>' : htmlspecialchars($item['sku']) ?>
                                </td>
                                <td class="py-4 px-6 text-center text-sm font-bold text-gray-800">
                                    <span class="text-base font-extrabold text-gray-900"><?= max(0, (int)$item['current_stock']) ?></span>
                                    <span class="block text-[11px] font-normal text-gray-400">Total: <?= max(0, (int)$item['total_stock']) ?></span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <?php 
                                        $stock = $item['current_stock'];
                                        $alert = $item['low_stock_alert'];
                                        if ($stock <= 0) {
                                            echo '<span class="inline-flex bg-red-50 text-red-700 border border-red-200 px-3 py-1 rounded-full text-xs font-bold">Out of Stock</span>';
                                        } elseif ($stock <= $alert) {
                                            echo '<span class="inline-flex bg-orange-50 text-orange-700 border border-orange-200 px-3 py-1 rounded-full text-xs font-bold">Low Stock</span>';
                                        } else {
                                            echo '<span class="inline-flex bg-emerald-50 text-emerald-700 border border-emerald-200 px-3 py-1 rounded-full text-xs font-bold">In Stock</span>';
                                        }
                                    ?>
                                </td>
                                <td class="py-4 px-6 text-right space-x-2">
                                    <a href="<?= BASE_URL ?>/admin/inventory/view?id=<?= $item['product_id'] ?>" class="text-emerald-600 hover:text-emerald-800 transition-colors focus:outline-none inline-block p-1 hover:bg-emerald-50 rounded-lg" title="View Details">
                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- Edit Stock Modal -->
<div id="editStockModal" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/40 backdrop-blur-sm transition-opacity" onclick="closeEditStockModal()"></div>
    
    <!-- Modal Content -->
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transform transition-all border border-gray-100">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/80">
                <h3 class="text-xl font-bold text-gray-900 font-display">Edit Stock</h3>
                <button onclick="closeEditStockModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <form action="<?= BASE_URL ?>/admin/inventory/update" method="POST" class="p-6">
                <input type="hidden" name="variant_id" id="edit_variant_id">
                <input type="hidden" name="current_stock" id="edit_current_stock_hidden">
                
                <p class="text-sm text-gray-600 mb-6">Updating stock for: <span id="edit_product_name" class="font-bold text-gray-900 text-base ml-1"></span></p>
                
                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold tracking-wider text-gray-600 uppercase mb-2">Total Stock (Inventory)</label>
                        <input type="text" name="total_stock" id="edit_total_stock" required min="0" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-brand-500 focus:border-brand-500 bg-white text-gray-900 font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-bold tracking-wider text-gray-600 uppercase mb-2">Add Stock Qty (+/-)</label>
                        <input type="text" id="edit_add_stock" placeholder="e.g. 5" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-brand-500 focus:border-brand-500 bg-gray-50 text-gray-900 font-bold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold tracking-wider text-gray-600 uppercase mb-2">Current Stock (Available)</label>
                        <input type="text" id="edit_current_stock" readonly class="w-full border border-gray-200 rounded-xl px-4 py-2.5 bg-gray-50 text-gray-500 font-medium cursor-not-allowed">
                        <p class="text-[10px] text-gray-400 mt-1">Calculated based on Total Stock - Ordered Quantity</p>
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-center space-x-4">
                    <button type="button" onclick="closeEditStockModal()" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-bold text-sm rounded-full hover:bg-gray-50 transition-colors w-1/2 uppercase tracking-wider">
                        CANCEL
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-[#F25996] hover:bg-[#d94480] text-white font-bold text-sm rounded-full transition-colors shadow-sm w-1/2 uppercase tracking-wider">
                        SAVE CHANGES
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function exportInventoryCSV() {
    window.location.href = '<?= BASE_URL ?>/admin/inventory/export';
}

function openEditStockModal(variantId) {
    document.getElementById('edit_variant_id').value = variantId;
    document.getElementById('edit_product_name').textContent = 'Loading...';
    document.getElementById('edit_total_stock').value = '';
    document.getElementById('edit_add_stock').value = '';
    document.getElementById('edit_current_stock').value = '';
    document.getElementById('edit_current_stock_hidden').value = '';
    
    fetch('<?= BASE_URL ?>/admin/inventory/variant-details?id=' + variantId)
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }
            
            document.getElementById('edit_product_name').textContent = data.product_name + ' (' + data.sku + ')';
            document.getElementById('edit_total_stock').value = data.inventory;
            document.getElementById('edit_current_stock').value = data.current_stock;
            document.getElementById('edit_current_stock_hidden').value = data.current_stock;
            
            initialTotalStock = parseInt(data.inventory) || 0;
            initialCurrentStock = parseInt(data.current_stock) || 0;
            
            document.getElementById('editStockModal').classList.remove('hidden');
        })
        .catch(err => {
            console.error('Error fetching variant details:', err);
            alert('Failed to load stock details.');
        });
}

function closeEditStockModal() {
    document.getElementById('editStockModal').classList.add('hidden');
}

let initialTotalStock = 0;
let initialCurrentStock = 0;

document.getElementById('edit_total_stock').addEventListener('input', function() {
    const newTotal = parseInt(this.value) || 0;
    const diff = newTotal - initialTotalStock;
    const newCurrent = Math.max(0, initialCurrentStock + diff);
    document.getElementById('edit_current_stock').value = newCurrent;
    document.getElementById('edit_current_stock_hidden').value = newCurrent;
    if(diff !== 0) document.getElementById('edit_add_stock').value = diff;
    else document.getElementById('edit_add_stock').value = '';
});

document.getElementById('edit_add_stock').addEventListener('input', function() {
    const addQty = parseInt(this.value) || 0;
    const newCurrent = Math.max(0, initialCurrentStock + addQty);
    document.getElementById('edit_total_stock').value = Math.max(0, initialTotalStock + addQty);
    document.getElementById('edit_current_stock').value = newCurrent;
    document.getElementById('edit_current_stock_hidden').value = newCurrent;
});
</script>
