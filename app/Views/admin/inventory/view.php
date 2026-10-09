<!-- Inventory View Details -->
<div class="w-full">
    <!-- Breadcrumbs & Header -->
    <div class="mb-8 relative z-10">
        <div class="text-sm font-medium text-gray-400 mb-2 font-serif tracking-wide">
            Dashboard <span class="mx-1 text-gray-300">›</span> <a href="<?= BASE_URL ?>/admin/inventory" class="hover:text-brand-600 transition-colors">Inventory Management</a> <span class="mx-1 text-gray-300">›</span> <span class="text-brand-700">Product Details</span>
        </div>
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 w-full">
            <div>
                <h1 class="text-4xl sm:text-5xl font-display font-extrabold text-gray-900 tracking-tight">Product Details</h1>
                <p class="text-sm text-gray-500 mt-1">Detailed inventory levels and SKU variant breakdown.</p>
            </div>
            <a href="<?= BASE_URL ?>/admin/inventory" class="bg-white hover:bg-gray-50 text-slate-700 px-5 py-2.5 rounded-full font-bold text-xs border border-slate-200 shadow-xs flex items-center gap-2 transition active:scale-95 uppercase tracking-wider">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>BACK TO LIST</span>
            </a>
        </div>
    </div>

    <!-- Main Product Card -->
    <div class="bg-white rounded-2xl p-8 border border-gray-200/80 shadow-sm mb-8">
        <div class="flex flex-col md:flex-row gap-8 items-start">
            
            <!-- Product Image -->
            <div class="w-full md:w-1/3 flex-shrink-0">
                <div class="rounded-2xl overflow-hidden aspect-square border border-gray-100 bg-gray-50 shadow-2xs flex items-center justify-center">
                    <?php if($product['image_path']): ?>
                        <img src="<?= htmlspecialchars(get_image_url($product['image_path'])) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Product Info -->
            <div class="w-full md:w-2/3">
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-1"><?= htmlspecialchars($product['name']) ?></h2>
                <p class="text-gray-400 font-mono text-xs mb-6"><?= htmlspecialchars($product['slug']) ?></p>

                <div class="bg-gray-50/80 rounded-2xl p-6 border border-gray-100">
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
                        <div>
                            <p class="text-xs font-bold tracking-wider text-gray-400 uppercase mb-1">Category</p>
                            <p class="font-bold text-gray-900 text-base"><?= htmlspecialchars($product['category_name'] ?? 'N/A') ?></p>
                        </div>
                        <div>
                            <p class="text-xs font-bold tracking-wider text-gray-400 uppercase mb-1">Price</p>
                            <?php 
                                if (!empty($product['variant_discount_price']) && $product['variant_discount_price'] > 0) {
                                    $displayInvPrice = $product['variant_discount_price'];
                                } elseif (!empty($product['variant_base_price']) && $product['variant_base_price'] > 0) {
                                    $displayInvPrice = $product['variant_base_price'];
                                } else {
                                    $displayInvPrice = $product['wholesale_price'];
                                }
                            ?>
                            <p class="font-bold text-gray-900 text-base">₹<?= number_format($displayInvPrice, 2) ?></p>
                        </div>
                        <div>
                            <div class="flex items-center gap-1 mb-1">
                                <p class="text-xs font-bold tracking-wider text-gray-400 uppercase">Total Stock</p>
                                <span class="text-[9px] bg-purple-100 text-purple-700 font-bold px-1 rounded">Admin</span>
                            </div>
                            <p class="font-bold text-gray-900 text-base"><?= max(0, (int)($product['total_stock_quantity'] ?? $product['stock_quantity'])) ?></p>
                        </div>
                        <div>
                            <p class="text-xs font-bold tracking-wider text-emerald-700 uppercase mb-1">Current Stock</p>
                            <p class="font-bold text-emerald-600 text-base"><?= max(0, (int)($product['current_stock_quantity'] ?? 0)) ?></p>
                        </div>
                        <div>
                            <p class="text-xs font-bold tracking-wider text-gray-400 uppercase mb-1">SKU</p>
                            <div class="flex flex-wrap gap-1">
                                <?php 
                                    $baseSku = $product['base_sku'];
                                    if (!empty($baseSku)): 
                                ?>
                                    <div class="bg-white px-2 py-0.5 rounded border border-gray-200 inline-block font-mono text-xs font-semibold text-gray-700">
                                        <?= htmlspecialchars($baseSku) ?>
                                    </div>
                                <?php elseif (!empty($variants)): ?>
                                    <?php foreach ($variants as $v): ?>
                                        <?php if (!empty($v['sku'])): ?>
                                        <div class="bg-white px-2 py-0.5 rounded border border-gray-200 inline-block font-mono text-xs font-semibold text-gray-700 whitespace-nowrap" title="<?= htmlspecialchars($v['variant_name'] ?? 'Variant') ?>">
                                            <?= htmlspecialchars($v['sku']) ?>
                                        </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="bg-white px-2 py-0.5 rounded border border-gray-200 inline-block font-mono text-xs font-semibold text-gray-700">N/A</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Variants Section -->
        <div class="mt-12 border-t border-gray-100 pt-8">
            <h3 class="text-xl font-bold text-gray-900 tracking-tight uppercase mb-6">Product Variants</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php if (empty($variants)): ?>
                    <!-- Simple product stock -->
                    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm relative">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-bold text-gray-900 text-base capitalize">Default</h4>
                            <?php 
                                $stock = max(0, (int)($product['current_stock_quantity'] ?? $product['stock_quantity']));
                                if ($stock <= 0) echo '<span class="bg-red-50 text-red-700 border border-red-200 px-2.5 py-0.5 rounded-full text-xs font-bold">Out of Stock</span>';
                                elseif ($stock <= 5) echo '<span class="bg-orange-50 text-orange-700 border border-orange-200 px-2.5 py-0.5 rounded-full text-xs font-bold">Low Stock</span>';
                                else echo '<span class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-0.5 rounded-full text-xs font-bold">In Stock</span>';
                            ?>
                        </div>
                        <p class="text-xs text-gray-400 font-mono mb-4 bg-gray-50 inline-block px-2 py-1 rounded border border-gray-100"><?= htmlspecialchars($product['base_sku'] ?? 'N/A') ?></p>
                        
                        <div class="grid grid-cols-2 gap-4 border-t border-b border-gray-100 py-4 mb-4">
                            <div class="text-center border-r border-gray-100">
                                <p class="text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-1">Total Stock</p>
                                <p class="font-extrabold text-xl text-gray-900"><?= max(0, (int)($product['total_stock_quantity'] ?? $product['stock_quantity'])) ?></p>
                            </div>
                            <div class="text-center">
                                <p class="text-[10px] font-bold tracking-widest text-emerald-600 uppercase mb-1">Current Stock</p>
                                <p class="font-extrabold text-xl text-emerald-700"><?= max(0, (int)($product['current_stock_quantity'] ?? $product['stock_quantity'])) ?></p>
                            </div>
                        </div>
                        <?php if ($this->hasPermission('inventory', 'edit')): ?>
                        <button onclick="openEditStockModal('p_<?= $product['id'] ?>')" class="text-indigo-600 hover:text-indigo-800 transition-colors text-xs font-bold flex items-center gap-1">
                            <svg class="w-4 h-4 icon-16" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit Stock
                        </button>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <?php foreach($variants as $variant): ?>
                        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm relative">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-bold text-gray-900 text-base capitalize"><?= htmlspecialchars(str_replace($product['base_sku'] . '-', '', $variant['sku'])) ?></h4>
                                <?php 
                                    $stock = max(0, (int)$variant['current_stock']);
                                    $alert = (int)$variant['low_stock_alert'];
                                    if ($stock <= 0) echo '<span class="bg-red-50 text-red-700 border border-red-200 px-2.5 py-0.5 rounded-full text-xs font-bold">Out of Stock</span>';
                                    elseif ($stock <= $alert) echo '<span class="bg-orange-50 text-orange-700 border border-orange-200 px-2.5 py-0.5 rounded-full text-xs font-bold">Low Stock</span>';
                                    else echo '<span class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-0.5 rounded-full text-xs font-bold">In Stock</span>';
                                ?>
                            </div>
                            <p class="text-xs text-gray-400 font-mono mb-4 bg-gray-50 inline-block px-2 py-1 rounded border border-gray-100"><?= htmlspecialchars($variant['sku']) ?></p>
                            
                            <div class="grid grid-cols-2 gap-4 border-t border-b border-gray-100 py-4 mb-4">
                                <div class="text-center border-r border-gray-100">
                                    <p class="text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-1">Total Stock</p>
                                    <p class="font-extrabold text-xl text-gray-900"><?= max(0, (int)$variant['inventory']) ?></p>
                                </div>
                                <div class="text-center">
                                    <p class="text-[10px] font-bold tracking-widest text-emerald-600 uppercase mb-1">Current Stock</p>
                                    <p class="font-extrabold text-xl text-emerald-700"><?= max(0, (int)$variant['current_stock']) ?></p>
                                </div>
                            </div>
                            <?php if ($this->hasPermission('inventory', 'edit')): ?>
                            <button onclick="openEditStockModal('<?= $variant['id'] ?>')" class="text-indigo-600 hover:text-indigo-800 transition-colors text-xs font-bold flex items-center gap-1">
                                <svg class="w-4 h-4 icon-16" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Edit Stock
                            </button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
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
                <input type="hidden" name="redirect_to" value="/admin/inventory/view?id=<?= $product['id'] ?>">
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
