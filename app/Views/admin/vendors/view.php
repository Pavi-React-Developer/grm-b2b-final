<div class="w-full">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <div class="flex items-center space-x-2">
                <a href="<?= BASE_URL ?>/admin/vendors" class="text-xs text-gray-500 hover:text-indigo-600 font-semibold">&larr; Vendors</a>
                <span class="text-gray-300">/</span>
                <span class="text-xs text-gray-500">Details</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 font-display mt-1">
                <?= htmlspecialchars($vendor['store_name'] ?? $vendor['name']) ?>
            </h1>
            <p class="text-sm text-gray-500">Applied on <?= date('d M Y, h:i A', strtotime($vendor['created_at'])) ?></p>
        </div>
        
        <div class="mt-4 md:mt-0 flex items-center space-x-3">
            <span class="px-3 py-1 text-xs font-bold rounded-full capitalize 
                <?php if ($vendor['status'] === 'active' || $vendor['status'] === 'approved'): ?>
                    bg-emerald-100 text-emerald-700
                <?php elseif ($vendor['status'] === 'pending'): ?>
                    bg-amber-100 text-amber-700
                <?php else: ?>
                    bg-rose-100 text-rose-700
                <?php endif; ?>">
                Status: <?= htmlspecialchars($vendor['status']) ?>
            </span>
        </div>
    </div>

    <!-- Vendor Sales & Revenue Overview -->
    <?php if (!empty($salesStats)): ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mr-4 font-bold text-lg">
                ₹
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Gross Sales</p>
                <h3 class="text-xl font-extrabold text-gray-900">₹<?= number_format($salesStats['total_revenue'] ?? 0, 2) ?></h3>
                <span class="text-[10px] text-emerald-600 font-semibold">Completed Orders</span>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center">
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Units Sold</p>
                <h3 class="text-xl font-extrabold text-gray-900"><?= number_format($salesStats['total_units_sold'] ?? 0) ?></h3>
                <span class="text-[10px] text-gray-400">Total items</span>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Orders</p>
                <h3 class="text-xl font-extrabold text-gray-900"><?= number_format($salesStats['total_orders'] ?? 0) ?></h3>
                <span class="text-[10px] text-amber-600 font-semibold"><?= (int)($salesStats['pending_orders'] ?? 0) ?> pending</span>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center">
            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Catalog Products</p>
                <h3 class="text-xl font-extrabold text-gray-900"><?= number_format($salesStats['total_products'] ?? 0) ?></h3>
                <span class="text-[10px] <?= ($salesStats['low_stock_products'] ?? 0) > 0 ? 'text-rose-500 font-bold' : 'text-gray-400' ?>">
                    <?= ($salesStats['low_stock_products'] ?? 0) > 0 ? ($salesStats['low_stock_products'] . ' low stock') : 'In stock' ?>
                </span>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Business Info Card -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Business Profile & Location
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500 block text-xs font-semibold">Vendor ID</span>
                        <span class="font-mono font-bold text-indigo-600">
                            <?= htmlspecialchars($vendor['unique_vendor_id'] ?? 'Not Assigned Yet') ?>
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-500 block text-xs font-semibold">Vendor Type</span>
                        <span class="font-semibold text-gray-800 capitalize"><?= htmlspecialchars($vendor['vendor_type'] ?? 'wholesaler') ?></span>
                    </div>
                    <div>
                        <span class="text-gray-500 block text-xs font-semibold">Store / Brand Name</span>
                        <span class="font-semibold text-gray-800"><?= htmlspecialchars($vendor['store_name']) ?></span>
                    </div>
                    <div>
                        <span class="text-gray-500 block text-xs font-semibold">Registered Company Name</span>
                        <span class="font-semibold text-gray-800"><?= htmlspecialchars($vendor['company_name'] ?? 'N/A') ?></span>
                    </div>
                    <div>
                        <span class="text-gray-500 block text-xs font-semibold">GST Registration</span>
                        <?php if (!empty($vendor['gst_number'])): ?>
                            <span class="font-mono font-bold text-gray-800"><?= htmlspecialchars($vendor['gst_number']) ?></span>
                        <?php else: ?>
                            <div class="mt-0.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                    Non-GST Vendor
                                </span>
                                <?php if (!empty($vendor['no_gst_reason'])): ?>
                                    <div class="text-xs text-gray-600 mt-1"><span class="font-semibold text-gray-500">Reason:</span> <?= htmlspecialchars($vendor['no_gst_reason']) ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <span class="text-gray-500 block text-xs font-semibold">PAN Number</span>
                        <span class="font-mono font-bold text-gray-800"><?= htmlspecialchars($vendor['pan_number'] ?? 'N/A') ?></span>
                    </div>
                    <div class="md:col-span-2">
                        <span class="text-gray-500 block text-xs font-semibold">Warehouse / Shop Address</span>
                        <span class="text-gray-800"><?= htmlspecialchars($vendor['address']) ?>, <?= htmlspecialchars($vendor['city']) ?>, <?= htmlspecialchars($vendor['state']) ?> - <?= htmlspecialchars($vendor['pincode']) ?></span>
                    </div>
                    <?php if (!empty($vendor['description'])): ?>
                        <div class="md:col-span-2 pt-2 border-t border-gray-50">
                            <span class="text-gray-500 block text-xs font-semibold">Business Description</span>
                            <p class="text-gray-700 italic"><?= nl2br(htmlspecialchars($vendor['description'])) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Bank & Payout Card -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    Bank & Payout Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500 block text-xs font-semibold">Bank Name</span>
                        <span class="font-semibold text-gray-800"><?= htmlspecialchars($vendor['bank_name'] ?? 'Not provided') ?></span>
                    </div>
                    <div>
                        <span class="text-gray-500 block text-xs font-semibold">Account Number</span>
                        <span class="font-mono font-semibold text-gray-800"><?= htmlspecialchars($vendor['account_number'] ?? 'Not provided') ?></span>
                    </div>
                    <div>
                        <span class="text-gray-500 block text-xs font-semibold">IFSC Code</span>
                        <span class="font-mono font-semibold text-gray-800"><?= htmlspecialchars($vendor['ifsc_code'] ?? 'Not provided') ?></span>
                    </div>
                    <div>
                        <span class="text-gray-500 block text-xs font-semibold">Account Holder Name</span>
                        <span class="font-semibold text-gray-800"><?= htmlspecialchars($vendor['account_holder_name'] ?? 'Not provided') ?></span>
                    </div>
                    <div>
                        <span class="text-gray-500 block text-xs font-semibold">UPI ID / VPA</span>
                        <span class="font-mono font-semibold text-indigo-600"><?= htmlspecialchars($vendor['upi_id'] ?? 'Not provided') ?></span>
                    </div>
                    <div>
                        <span class="text-gray-500 block text-xs font-semibold">UPI Beneficiary Name</span>
                        <span class="font-semibold text-gray-800"><?= htmlspecialchars($vendor['upi_name'] ?? 'Not provided') ?></span>
                    </div>
                </div>
            </div>

            <!-- Uploaded Document Proofs & Shop Images -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-6">
                <!-- A. Shop / Showroom Photos -->
                <div>
                    <h3 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4 flex items-center justify-between">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            Shop & Warehouse Photos
                        </span>
                    </h3>
                    <?php 
                        $shopPhotos = [];
                        if (!empty($vendor['shop_images'])) {
                            $decoded = json_decode($vendor['shop_images'], true);
                            if (is_array($decoded)) {
                                $shopPhotos = $decoded;
                            }
                        }
                        if (empty($shopPhotos) && !empty($vendor['media'])) {
                            foreach ($vendor['media'] as $m) {
                                if (($m['purpose'] ?? '') === 'shop_image' || $m['file_type'] === 'image') {
                                    $shopPhotos[] = $m['file_path'];
                                }
                            }
                        }
                    ?>
                    <?php if (empty($shopPhotos)): ?>
                        <p class="text-sm text-gray-400">No shop photos uploaded by this vendor.</p>
                    <?php else: ?>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                            <?php foreach ($shopPhotos as $imgPath): 
                                $src = (strpos($imgPath, 'http://') === 0 || strpos($imgPath, 'https://') === 0) ? $imgPath : (BASE_URL . '/' . ltrim($imgPath, '/'));
                            ?>
                                <a href="<?= $src ?>" target="_blank" class="group block relative rounded-xl overflow-hidden border border-gray-200 bg-gray-50 hover:shadow-md transition">
                                    <img src="<?= $src ?>" alt="Shop Photo" class="w-full h-28 object-cover group-hover:scale-105 transition-transform duration-300">
                                    <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-bold">
                                        View Full
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- B. Verification Proofs / GST Document -->
                <div class="border-t border-gray-100 pt-4">
                    <h3 class="text-base font-bold text-gray-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        GST Certificate & Business Verification Documents
                    </h3>
                    <?php 
                        $docs = [];
                        if (!empty($vendor['gst_document'])) {
                            $docs[] = ['file_path' => $vendor['gst_document'], 'file_type' => (pathinfo($vendor['gst_document'], PATHINFO_EXTENSION) === 'pdf' ? 'pdf' : 'image')];
                        }
                        if (!empty($vendor['media'])) {
                            foreach ($vendor['media'] as $m) {
                                if (($m['purpose'] ?? '') === 'vendor_verification' || $m['file_type'] === 'pdf') {
                                    $docs[] = $m;
                                }
                            }
                        }
                    ?>
                    <?php if (empty($docs)): ?>
                        <p class="text-sm text-gray-400">No GST certificate or proof document attached.</p>
                    <?php else: ?>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <?php foreach ($docs as $d): 
                                $src = (strpos($d['file_path'], 'http://') === 0 || strpos($d['file_path'], 'https://') === 0) ? $d['file_path'] : (BASE_URL . '/' . ltrim($d['file_path'], '/'));
                            ?>
                                <div class="border border-gray-200 rounded-xl p-3 bg-gray-50 flex items-center justify-between">
                                    <div class="flex items-center space-x-2 truncate">
                                        <span class="text-lg"><?= $d['file_type'] === 'pdf' ? '📄' : '🖼️' ?></span>
                                        <span class="text-xs font-semibold text-gray-700 truncate"><?= basename($d['file_path']) ?></span>
                                    </div>
                                    <a href="<?= $src ?>" target="_blank" class="px-3 py-1 bg-white border border-gray-200 hover:bg-gray-100 text-indigo-600 text-xs font-bold rounded-lg transition shadow-sm shrink-0">
                                        Open &rarr;
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Top Performing Products -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4 flex items-center justify-between">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                        Top Performing Products
                    </span>
                    <a href="<?= BASE_URL ?>/admin/catalog/products?vendor_id=<?= $vendor['id'] ?>" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold">View Catalog &rarr;</a>
                </h3>

                <?php if (empty($topProducts)): ?>
                    <p class="text-sm text-gray-400 py-2">No product sales recorded yet for this vendor.</p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="text-gray-400 font-bold uppercase tracking-wider border-b border-gray-100">
                                    <th class="pb-2">Product</th>
                                    <th class="pb-2">SKU</th>
                                    <th class="pb-2 text-right">Price</th>
                                    <th class="pb-2 text-center">Units Sold</th>
                                    <th class="pb-2 text-right">Revenue</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 font-medium text-gray-700">
                                <?php foreach ($topProducts as $prod): ?>
                                    <tr>
                                        <td class="py-2.5 flex items-center space-x-2">
                                            <?php 
                                                $firstImg = $prod['primary_image'] ?? null;
                                            ?>
                                            <?php if ($firstImg): ?>
                                                <img src="<?= BASE_URL ?>/<?= ltrim($firstImg, '/') ?>" alt="" class="w-8 h-8 rounded-lg object-cover border">
                                            <?php else: ?>
                                                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-[10px] text-gray-400">📦</div>
                                            <?php endif; ?>
                                            <span class="font-bold text-gray-900 truncate max-w-[200px]" title="<?= htmlspecialchars($prod['name']) ?>"><?= htmlspecialchars($prod['name']) ?></span>
                                        </td>
                                        <td class="py-2.5 font-mono text-[11px] text-gray-500"><?= htmlspecialchars($prod['sku'] ?? 'N/A') ?></td>
                                        <td class="py-2.5 text-right font-semibold">₹<?= number_format((float)($prod['price'] ?? 0), 2) ?></td>
                                        <td class="py-2.5 text-center font-bold text-indigo-600"><?= (int)$prod['total_qty_sold'] ?></td>
                                        <td class="py-2.5 text-right font-black text-emerald-600">₹<?= number_format((float)$prod['total_revenue_generated'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Recent Customer Orders -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4 flex items-center justify-between">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        Recent Orders Involving This Vendor
                    </span>
                    <a href="<?= BASE_URL ?>/admin/orders" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold">All Orders &rarr;</a>
                </h3>

                <?php if (empty($recentOrders)): ?>
                    <p class="text-sm text-gray-400 py-2">No orders placed for this vendor's products yet.</p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="text-gray-400 font-bold uppercase tracking-wider border-b border-gray-100">
                                    <th class="pb-2">Order #</th>
                                    <th class="pb-2">Buyer</th>
                                    <th class="pb-2">Items</th>
                                    <th class="pb-2 text-right">Vendor Subtotal</th>
                                    <th class="pb-2 text-center">Status</th>
                                    <th class="pb-2 text-right">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 font-medium text-gray-700">
                                <?php foreach ($recentOrders as $ro): ?>
                                    <tr>
                                        <td class="py-2.5 font-bold font-mono text-indigo-600">
                                            <a href="<?= BASE_URL ?>/admin/orders/view?id=<?= $ro['id'] ?>" class="hover:underline">
                                                #<?= htmlspecialchars($ro['order_number']) ?>
                                            </a>
                                        </td>
                                        <td class="py-2.5">
                                            <span class="font-bold text-gray-900 block"><?= htmlspecialchars($ro['buyer_name'] ?? 'Guest Buyer') ?></span>
                                            <span class="text-[10px] text-gray-400"><?= htmlspecialchars($ro['buyer_phone'] ?? '') ?></span>
                                        </td>
                                        <td class="py-2.5 text-gray-600 truncate max-w-[150px]" title="<?= htmlspecialchars($ro['items_summary'] ?? '') ?>">
                                            <?= htmlspecialchars($ro['items_summary'] ?? 'Items') ?>
                                        </td>
                                        <td class="py-2.5 text-right font-black text-emerald-600">
                                            ₹<?= number_format((float)($ro['vendor_subtotal'] ?? 0), 2) ?>
                                        </td>
                                        <td class="py-2.5 text-center">
                                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full capitalize
                                                <?php if ($ro['status'] === 'delivered'): ?>
                                                    bg-emerald-100 text-emerald-700
                                                <?php elseif ($ro['status'] === 'cancelled'): ?>
                                                    bg-rose-100 text-rose-700
                                                <?php else: ?>
                                                    bg-amber-100 text-amber-700
                                                <?php endif; ?>">
                                                <?= htmlspecialchars(str_replace('_', ' ', $ro['status'])) ?>
                                            </span>
                                        </td>
                                        <td class="py-2.5 text-right text-gray-400 text-[11px] whitespace-nowrap">
                                            <?= date('d M Y', strtotime($ro['created_at'])) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar Actions Column -->
        <div class="space-y-6">
            <!-- Applicant Info -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4">Applicant Contact</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-gray-500 block text-xs font-semibold">Contact Person</span>
                        <span class="font-bold text-gray-800"><?= htmlspecialchars($vendor['name']) ?></span>
                    </div>
                    <div>
                        <span class="text-gray-500 block text-xs font-semibold">Email</span>
                        <a href="mailto:<?= htmlspecialchars($vendor['email']) ?>" class="text-indigo-600 font-medium hover:underline"><?= htmlspecialchars($vendor['email']) ?></a>
                    </div>
                    <div>
                        <span class="text-gray-500 block text-xs font-semibold">Phone Number</span>
                        <a href="tel:<?= htmlspecialchars($vendor['phone']) ?>" class="text-indigo-600 font-medium hover:underline"><?= htmlspecialchars($vendor['phone']) ?></a>
                    </div>
                </div>
            </div>

            <!-- Approval / Rejection Box -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4">Application Decision</h3>
                
                <?php if ($vendor['status'] === 'pending'): ?>
                    <form action="<?= BASE_URL ?>/admin/vendors/approve" method="POST" class="mb-4">
                        <input type="hidden" name="id" value="<?= $vendor['id'] ?>">
                        <button type="submit" data-confirm-type="approve" data-confirm-title="Approve Vendor Application" data-confirm-message="Are you sure you want to APPROVE this vendor application?" onclick="return confirm('Are you sure you want to APPROVE this vendor application?')" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition shadow-md flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Approve Vendor Application
                        </button>
                    </form>

                    <form action="<?= BASE_URL ?>/admin/vendors/reject" method="POST" class="border-t border-gray-100 pt-4">
                        <input type="hidden" name="id" value="<?= $vendor['id'] ?>">
                        <label for="rejection_reason" class="block text-xs font-semibold text-gray-700 mb-1">Rejection Reason (Sent to Vendor)</label>
                        <textarea id="rejection_reason" name="rejection_reason" rows="2" placeholder="State why application was rejected..." required class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs mb-3"></textarea>
                        <button type="submit" data-confirm-type="reject" data-confirm-title="Reject Vendor Application" data-confirm-message="Are you sure you want to reject this vendor application?" onclick="return confirm('Reject this vendor application?')" class="w-full py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl transition shadow">
                            Reject Application
                        </button>
                    </form>
                <?php elseif ($vendor['status'] === 'active' || $vendor['status'] === 'approved'): ?>
                    <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-800 text-xs font-semibold text-center">
                        This vendor has been approved and is active.
                    </div>
                <?php else: ?>
                    <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl text-rose-800 text-xs text-center">
                        <strong>Rejected Application</strong>
                        <?php if (!empty($vendor['rejection_reason'])): ?>
                            <p class="mt-1 italic">Reason: <?= htmlspecialchars($vendor['rejection_reason']) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
