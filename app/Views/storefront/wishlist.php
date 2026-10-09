<div class="bg-[#fafafa] min-h-screen flex flex-col md:flex-row relative">
    
    <?php include __DIR__ . '/dashboard/_sidebar.php'; ?>
    
    <div class="flex-1 p-3 sm:p-5 md:p-6 relative w-full min-w-0">
        <div class="max-w-6xl mx-auto w-full min-w-0">
        
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 mb-4 border-b border-gray-100 pb-3">
                <div>
                    <h1 class="text-lg sm:text-xl md:text-2xl font-black text-gray-900 font-display tracking-tight flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#F25996] fill-[#F25996]" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                        <span>My Wishlist</span>
                    </h1>
                    <p class="text-[11px] sm:text-xs text-gray-500 mt-0.5">Products and styles you have saved for later.</p>
                </div>
                <?php if (!empty($items)): ?>
                <div class="flex items-center justify-between sm:justify-start gap-2.5 sm:gap-3 bg-white px-3 py-1.5 rounded-xl border border-pink-100/70 shadow-xs text-xs">
                    <label class="flex items-center gap-1.5 cursor-pointer shrink-0 select-none">
                        <input type="checkbox" id="page-wishlist-select-all" onchange="toggleAllPageWishlistItems(this)" class="w-3.5 h-3.5 text-[#F25996] rounded-[4px] border-gray-300 focus:ring-0 cursor-pointer accent-[#F25996]">
                        <span class="font-bold text-gray-700 whitespace-nowrap text-xs">Select All</span>
                    </label>
                    <div class="h-3.5 w-px bg-gray-200 shrink-0"></div>
                    <button onclick="deleteSelectedPageWishlistItems()" class="font-bold text-red-500 hover:text-red-700 disabled:opacity-40 disabled:cursor-not-allowed transition-colors flex items-center gap-1 shrink-0 whitespace-nowrap text-xs" id="page-wishlist-del-selected" disabled>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        Remove
                    </button>
                    <button onclick="addSelectedPageWishlistToCart()" class="font-bold text-[#F25996] hover:text-[#d8407d] disabled:opacity-40 disabled:cursor-not-allowed transition-colors flex items-center gap-1 shrink-0 whitespace-nowrap text-xs" id="page-wishlist-add-selected" disabled>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        Add to Cart
                    </button>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-3.5" id="wishlist-container">
                <?php if (empty($items)): ?>
                    <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-gray-100 shadow-sm">
                        <svg class="w-16 h-16 mx-auto text-[#F25996] mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                        <p class="text-base sm:text-lg font-bold text-gray-800">Your wishlist is empty.</p>
                        <p class="text-xs text-gray-400 mt-1 mb-4">Explore our catalog and save items you want to buy later.</p>
                        <a href="<?= BASE_URL ?>/catalog" class="inline-flex items-center gap-1.5 text-xs font-bold text-white bg-text-gray hover:bg-[#F25996] px-5 py-2.5 rounded-xl transition-all shadow-sm shadow-pink-500/20">Browse Catalog</a>
                    </div>
                <?php else: ?>
                    <?php foreach($items as $product): ?>
                        <?php 
                            $catGst = (float)($product['category_sgst'] ?? 0) + (float)($product['category_cgst'] ?? 0);
                            $prodGst = $catGst > 0 ? $catGst : ((float)($product['total_gst'] ?? 0));

                            $rawBase = (isset($product['base_price']) && (float)$product['base_price'] > 0) ? (float)$product['base_price'] : (float)($product['wholesale_price'] ?? 0);
                            $rawDiscount = !empty($product['discount_price']) && (float)$product['discount_price'] > 0 ? (float)$product['discount_price'] : null;

                            $sellingPrice = $rawDiscount ?? $rawBase;
                            $originalPrice = ($rawDiscount && $rawDiscount < $rawBase) ? $rawBase : null;
                            $discPct = ($originalPrice && $originalPrice > $sellingPrice) ? round((($originalPrice - $sellingPrice) / $originalPrice) * 100) : 0;

                            $moq = (int)($product['moq_override'] ?? $product['category_moq'] ?? 1);
                            $totalStock = (int)($product['total_variant_stock'] ?? $product['stock_quantity'] ?? 0);
                            $inStock = $totalStock >= $moq;

                            $variantText = !empty($product['first_variant_attrs']) 
                                ? $product['first_variant_attrs'] 
                                : (!empty($product['first_variant_name']) ? $product['first_variant_name'] : (!empty($product['category_name']) ? $product['category_name'] : 'Standard'));
                            
                            $imgSrc = !empty($product['primary_image']) ? get_image_url($product['primary_image']) : 'https://placehold.co/400x400/f3f4f6/a1a1aa?text=No+Image';
                        ?>
                        <div class="bg-white border border-gray-100/90 rounded-[22px] p-2.5 sm:p-3 hover:shadow-md transition-all duration-300 group flex items-start gap-2.5 sm:gap-3 relative shadow-xs" id="wishlist-item-<?= $product['id'] ?>">
                            <!-- Left Selection Checkbox (Small) -->
                            <div class="pt-1 flex-shrink-0">
                                <input type="checkbox" class="page-wishlist-item-checkbox w-3.5 h-3.5 text-[#0c2340] rounded-[4px] border border-gray-300 focus:ring-0 cursor-pointer accent-[#0c2340]" value="<?= $product['id'] ?>" onchange="updatePageWishlistSelection()" data-moq="<?= $moq ?>">
                            </div>

                            <!-- Product Thumbnail Image Container (w-22 h-22 / 88px x 88px) -->
                            <a href="<?= BASE_URL ?>/product?id=<?= $product['id'] ?>" class="rounded-[16px] overflow-hidden flex-shrink-0 relative group/img block" style="width: 88px; height: 88px; min-width: 88px; min-height: 88px;">
                                <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="rounded-[16px] group-hover/img:scale-105 transition-transform duration-300 block" style="width: 100%; height: 100%; object-fit: cover;" loading="lazy" decoding="async">
                            </a>

                            <!-- Center Details & Right Actions -->
                            <div class="flex-1 min-w-0 flex items-start justify-between gap-2 self-stretch">
                                <!-- Center Info Column -->
                                <div class="flex-1 min-w-0 flex flex-col justify-between self-stretch">
                                    <div>
                                        <!-- Title -->
                                        <a href="<?= BASE_URL ?>/product?id=<?= $product['id'] ?>" class="text-xs sm:text-[13px] font-bold text-gray-900 leading-tight hover:text-[#0c2340] transition-colors line-clamp-1" title="<?= htmlspecialchars($product['name']) ?>">
                                            <?= htmlspecialchars($product['name']) ?>
                                        </a>

                                        <!-- Variant Attributes -->
                                        <div class="text-[10px] sm:text-[11px] text-gray-400 font-normal mt-0.5 truncate">
                                            <?= htmlspecialchars($variantText) ?>
                                        </div>

                                        <!-- Price Row -->
                                        <div class="flex items-baseline gap-1 mt-0.5 flex-wrap">
                                            <span class="text-xs sm:text-sm font-bold text-[#0c2340]">₹<?= format_price($sellingPrice) ?></span>
                                            <?php if ($originalPrice): ?>
                                                <span class="text-[10px] sm:text-[11px] text-gray-400 line-through font-normal">₹<?= format_price($originalPrice) ?></span>
                                            <?php endif; ?>
                                            <span class="text-[10px] sm:text-[11px] text-gray-400 font-normal">/ Unit</span>
                                            <?php if ($discPct > 0): ?>
                                                <span class="text-[8px] sm:text-[9px] font-bold text-[#f43f5e] bg-rose-50 border border-rose-100 px-1 py-0.2 rounded"><?= $discPct ?>% OFF</span>
                                            <?php endif; ?>
                                        </div>

                                        <!-- MOQ Line -->
                                        <div class="text-[10px] sm:text-[11px] text-gray-400 font-normal mt-0.5">
                                            MOQ: <?= $moq ?> Units
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Action Column: Heart Icon on Top, Cart Icon Below -->
                                <div class="flex flex-col justify-between items-end self-stretch shrink-0 pl-1">
                                    <!-- Top: Heart Remove Button -->
                                    <button onclick="removeWishlist(<?= $product['id'] ?>)" class="text-[#f43f5e] hover:scale-110 active:scale-95 transition-transform p-0.5 -mr-1" title="Remove from Wishlist">
                                        <svg class="w-4 h-4 text-[#f43f5e] fill-[#f43f5e]" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                        </svg>
                                    </button>

                                    <!-- Bottom (Below Heart): Pink Cart Icon Button -->
                                    <button type="button" onclick="addWishlistItemToCart(<?= $product['id'] ?>, <?= $moq ?>, this, event)" class="w-7 h-7 sm:w-8 sm:h-8 bg-[#F25996] hover:bg-[#d8407d] text-white rounded-full transition-all shadow-xs shadow-pink-500/25 flex items-center justify-center active:scale-90 mt-auto shrink-0" title="Add to Cart">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
        </div>
    </div>
</div>

<script>
function toggleAllPageWishlistItems(checkbox) {
    const checkboxes = document.querySelectorAll('.page-wishlist-item-checkbox');
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
    updatePageWishlistSelection();
}

function updatePageWishlistSelection() {
    const checkboxes = document.querySelectorAll('.page-wishlist-item-checkbox');
    const checked = Array.from(checkboxes).filter(cb => cb.checked);
    
    const selectAllBtn = document.getElementById('page-wishlist-select-all');
    if (selectAllBtn) {
        selectAllBtn.checked = checked.length > 0 && checked.length === checkboxes.length;
    }

    const delBtn = document.getElementById('page-wishlist-del-selected');
    const addBtn = document.getElementById('page-wishlist-add-selected');
    if (delBtn) delBtn.disabled = checked.length === 0;
    if (addBtn) addBtn.disabled = checked.length === 0;
}

async function deleteSelectedPageWishlistItems() {
    const checked = Array.from(document.querySelectorAll('.page-wishlist-item-checkbox:checked')).map(cb => cb.value);
    if (checked.length === 0) return;
    
    for (const productId of checked) {
        await fetch('<?= BASE_URL ?>/wishlist/toggle', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ product_id: productId })
        });
        const item = document.getElementById('wishlist-item-' + productId);
        if (item) item.remove();
    }
    
    const container = document.getElementById('wishlist-container');
    if (container.children.length === 0) {
        window.location.reload();
    }
    updatePageWishlistSelection();
    
    if (typeof fetchWishlistCount === 'function') fetchWishlistCount();
}

async function addSelectedPageWishlistToCart() {
    const checked = Array.from(document.querySelectorAll('.page-wishlist-item-checkbox:checked'));
    if (checked.length === 0) return;
    
    for (const cb of checked) {
        const productId = cb.value;
        const moq = cb.dataset.moq || 1;
        
        await fetch('<?= BASE_URL ?>/cart/ajax-add', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: productId, quantity: moq, from_wishlist: 1 })
        });
        const item = document.getElementById('wishlist-item-' + productId);
        if (item) item.remove();
        const drawerItem = document.getElementById('wishlist-drawer-item-' + productId);
        if (drawerItem) drawerItem.remove();
    }
    
    if (typeof fetchCart === 'function') fetchCart();
    if (typeof openCartDrawer === 'function') openCartDrawer(true);
}

function removeWishlist(productId) {
    fetch('<?= BASE_URL ?>/wishlist/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.action === 'removed') {
            const item = document.getElementById('wishlist-item-' + productId);
            if (item) {
                item.remove();
            }
            
            // Check if empty
            const container = document.getElementById('wishlist-container');
            if (container.children.length === 0) {
                container.innerHTML = `
                <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <svg class="w-12 h-12 mx-auto text-[#F25996] mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <p class="text-base sm:text-lg font-medium text-gray-700">Your wishlist is empty.</p>
                    <a href="<?= BASE_URL ?>/catalog" class="text-[#F25996] font-bold hover:underline mt-2 inline-block text-sm">Browse Catalog</a>
                </div>`;
            }
        } else {
            alert(data.message || 'Error updating wishlist');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred.');
    });
}
</script>
