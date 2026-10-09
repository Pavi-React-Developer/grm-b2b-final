<div class="-mt-4 mb-4 flex justify-end">
    <?php if ($buyer['status'] === 'pending'): ?>
        <a href="<?= BASE_URL ?>/admin/buyers/pending" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-900 font-bold transition-colors">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Pending Buyers
        </a>
    <?php else: ?>
        <a href="<?= BASE_URL ?>/admin/buyers" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-900 font-bold transition-colors">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Buyers
        </a>
    <?php endif; ?>
</div>

<!-- Header Card -->
<div class="bg-white border border-[#eae0d5] rounded-3xl p-6 md:p-8 mb-6 flex flex-col md:flex-row items-start md:items-center justify-between shadow-sm relative overflow-hidden">
    <!-- Decorative background element -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-bl from-[#f9f5f0] to-transparent rounded-full -translate-y-1/2 translate-x-1/4 opacity-50 pointer-events-none"></div>

    <div class="flex items-center gap-6 relative z-10">
        <!-- Avatar -->
        <div class="w-20 h-20 bg-amber-400 rounded-full flex items-center justify-center text-black text-4xl font-display font-black shadow-md border-4 border-white">
            <?= strtoupper(substr($buyer['name'], 0, 1)) ?>
        </div>
        <!-- Info -->
        <div>
            <h2 class="text-3xl font-display font-black text-gray-900 mb-1 flex items-center gap-3">
                <?= htmlspecialchars($buyer['name']) ?>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider <?= $buyer['status'] === 'active' || $buyer['status'] === 'approved' ? 'bg-green-100 text-green-800 border border-green-200' : ($buyer['status'] === 'rejected' ? 'bg-red-100 text-red-800 border border-red-200' : 'bg-yellow-100 text-yellow-800 border border-yellow-200') ?>">
                    <?= ucfirst($buyer['status']) ?>
                </span>
            </h2>
            <p class="text-gray-500 mb-3 text-sm font-medium">Customer since <?= date('d M Y', strtotime($buyer['created_at'])) ?></p>
            <div class="flex flex-wrap items-center gap-5 text-sm font-semibold text-gray-700">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#c49b76]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <?= htmlspecialchars($buyer['email']) ?>
                </span>
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#c49b76]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    <?= htmlspecialchars($buyer['phone']) ?>
                </span>
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#c49b76]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Joined <?= date('d M Y', strtotime($buyer['created_at'])) ?>
                </span>
                
                <?php if(!empty($buyer['instagram_link'])): ?>
                    <span class="flex items-center gap-2 text-pink-600 hover:text-pink-700 transition-colors">
                       <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg> 
                       <a href="<?= htmlspecialchars($buyer['instagram_link']) ?>" target="_blank" class="hover:underline font-bold">Instagram Profile</a>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="flex items-stretch gap-4 mt-6 md:mt-0 relative z-10 w-full md:w-auto">
        <!-- Total Orders Box -->
        <div class="border border-[#eae0d5] rounded-2xl p-5 flex-1 md:min-w-[130px] text-center bg-[#fdfbf9] shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Total Orders</p>
            <p class="text-4xl font-display font-black text-gray-900"><?= $orderStats['total_orders'] ?></p>
        </div>
        <!-- Delivered Spent Box -->
        <div class="border border-[#eae0d5] rounded-2xl p-5 flex-1 md:min-w-[150px] text-center bg-[#fdfbf9] shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Delivered Spent</p>
            <p class="text-3xl font-display font-black text-[#c49b76]">₹<?= number_format($orderStats['delivered_spent'], 2) ?></p>
            <p class="text-[9px] font-black text-emerald-600 mt-1.5 uppercase tracking-wider">Delivered Only</p>
        </div>
    </div>
</div>

<!-- Essential Business Information & Verification -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Business Details</h3>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <dt class="text-xs font-bold text-gray-400 uppercase tracking-wider">Business Name</dt>
                <dd class="mt-1 text-base text-gray-900 font-semibold"><?= htmlspecialchars($buyer['business_name'] ?? 'N/A') ?></dd>
            </div>
            <div>
                <dt class="text-xs font-bold text-gray-400 uppercase tracking-wider">Unique Buyer ID</dt>
                <dd class="mt-1 text-sm font-bold text-brand-700 tracking-wider bg-brand-50 inline-block px-3 py-1 rounded-md border border-brand-100"><?= htmlspecialchars($buyer['unique_buyer_id'] ?? 'N/A') ?></dd>
            </div>
            <div>
                <dt class="text-xs font-bold text-gray-400 uppercase tracking-wider">Shop Location / Address</dt>
                <dd class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($buyer['shop_location'] ?? 'Not provided') ?></dd>
            </div>
            
            <?php if ($buyer['gst_flag']): ?>
                <div>
                    <dt class="text-xs font-bold text-gray-400 uppercase tracking-wider">GST Number</dt>
                    <dd class="mt-1 flex items-center justify-between bg-gray-50 p-2.5 rounded-lg border border-gray-200">
                        <span class="text-base text-gray-900 font-mono font-bold tracking-widest"><?= htmlspecialchars($buyer['gst_number']) ?></span>
                        <button type="button" id="verifyBtn" onclick="verifyGstPan('gst', '<?= htmlspecialchars($buyer['gst_number']) ?>')" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-bold rounded shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none transition-colors">
                            Verify GST
                        </button>
                    </dd>
                </div>
            <?php else: ?>
                <div>
                    <dt class="text-xs font-bold text-gray-400 uppercase tracking-wider">PAN Number (Non-GST)</dt>
                    <dd class="mt-1 flex items-center justify-between bg-gray-50 p-2.5 rounded-lg border border-gray-200">
                        <span class="text-base text-gray-900 font-mono font-bold tracking-widest"><?= htmlspecialchars(!empty($buyer['pan_number']) ? $buyer['pan_number'] : 'N/A') ?></span>
                        <?php if (!empty($buyer['pan_number'])): ?>
                            <button type="button" id="verifyBtn" onclick="verifyGstPan('pan', '<?= htmlspecialchars($buyer['pan_number']) ?>')" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-bold rounded shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none transition-colors">
                                Verify PAN
                            </button>
                        <?php endif; ?>
                    </dd>
                </div>
                <div class="mt-3">
                    <dt class="text-xs font-bold text-gray-400 uppercase tracking-wider">Reason for no GST</dt>
                    <dd class="mt-1 text-sm text-gray-700 italic border-l-2 border-amber-400 pl-3"><?= htmlspecialchars(!empty($buyer['no_gst_reason']) ? $buyer['no_gst_reason'] : 'N/A') ?></dd>
                </div>
            <?php endif; ?>

            <!-- Verification Results Box -->
            <div id="verificationResult" class="hidden mt-4 bg-slate-50 border border-slate-200 rounded-xl p-5 shadow-sm transition-all duration-300">
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-200">
                    <h5 class="text-sm font-bold text-slate-800 flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        Verification Result
                    </h5>
                    <span class="px-2.5 py-0.5 rounded text-xs font-bold" id="verifyStatusBadge">Verified</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm" id="verifyResultContent">
                    <!-- Populated dynamically -->
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Verification Photos</h3>
            <span class="text-xs font-medium text-gray-500"><?= count($buyer['media'] ?? []) ?> photo(s)</span>
        </div>
        <div class="p-6 flex-1">
            <?php if (empty($buyer['media'])): ?>
                <div class="h-full min-h-[200px] flex items-center justify-center bg-gray-50 rounded-xl text-gray-500 border-2 border-gray-200 border-dashed">
                    <div class="text-center">
                        <svg class="w-8 h-8 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <p class="text-sm font-medium">No photos uploaded.</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-2 gap-4">
                    <?php foreach($buyer['media'] as $media): ?>
                        <div class="border rounded-xl overflow-hidden bg-gray-100 group relative aspect-[4/3] shadow-sm">
                            <img src="<?= htmlspecialchars(get_image_url($media['file_path'])) ?>" alt="Shop Photo" class="w-full h-full object-cover" loading="lazy" decoding="async">
                            <a href="<?= htmlspecialchars(get_image_url($media['file_path'])) ?>" target="_blank" class="absolute inset-0 bg-transparent hover:bg-black/40 transition-all flex items-center justify-center rounded-xl no-pill" style="background-color: transparent !important; border-radius: 0.75rem !important;">
                                <span class="text-white opacity-0 group-hover:opacity-100 font-bold tracking-wider text-xs uppercase bg-black/75 px-4 py-2 rounded-full transform translate-y-2 group-hover:translate-y-0 transition-all shadow-md pointer-events-none">View Full Size</span>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Metrics Row -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Avg Order Value -->
    <div class="bg-white border border-[#eae0d5] rounded-3xl p-6 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500 mb-4 border border-blue-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
        </div>
        <div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Avg Order Value</p>
            <p class="text-2xl font-display font-black text-gray-900">₹<?= number_format($orderStats['avg_order_value'], 2) ?></p>
        </div>
    </div>
    <!-- Delivered -->
    <div class="bg-white border border-[#eae0d5] rounded-3xl p-6 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-500 mb-4 border border-emerald-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
        </div>
        <div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Delivered</p>
            <p class="text-2xl font-display font-black text-gray-900"><?= $orderStats['delivered_count'] ?></p>
        </div>
    </div>
    <!-- Cancelled -->
    <div class="bg-white border border-[#eae0d5] rounded-3xl p-6 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
        <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center text-red-500 mb-4 border border-red-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </div>
        <div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Cancelled</p>
            <p class="text-2xl font-display font-black text-gray-900"><?= $orderStats['cancelled_count'] ?></p>
        </div>
    </div>
    <!-- Preferred Payment -->
    <div class="bg-white border border-[#eae0d5] rounded-3xl p-6 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
        <div class="w-10 h-10 bg-[#fdfbf9] border border-[#eae0d5] rounded-xl flex items-center justify-center text-[#c49b76] mb-4">
            <span class="font-bold text-lg">₹</span>
        </div>
        <div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Preferred Payment</p>
            <p class="text-2xl font-display font-black text-gray-900"><?= htmlspecialchars($orderStats['preferred_payment']) ?></p>
        </div>
    </div>
</div>

<!-- Order History Section -->
<div class="bg-white border border-[#eae0d5] rounded-3xl shadow-sm overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-[#eae0d5] flex justify-between items-center bg-[#fdfbf9]">
        <h3 class="text-lg font-display font-black text-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-[#c49b76]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            Order History
        </h3>
        <span class="text-sm font-bold text-gray-500 bg-white border border-gray-200 px-3 py-1 rounded-full"><?= count($orderHistory) ?> order(s)</span>
    </div>
    
    <?php if (empty($orderHistory)): ?>
        <div class="p-16 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            <p class="font-medium text-lg">No orders found for this customer.</p>
            <p class="text-sm mt-1">When this buyer places an order, it will appear here.</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Products</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Payment</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach($orderHistory as $order): ?>
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-gray-900">#<?= htmlspecialchars($order['order_number']) ?></td>
                            <td class="px-6 py-4 text-gray-500 text-sm font-medium"><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg overflow-hidden border border-gray-200 shrink-0 bg-white">
                                        <?php if (!empty($order['first_product_image'])): ?>
                                            <img src="<?= htmlspecialchars(get_image_url($order['first_product_image'])) ?>" class="w-full h-full object-cover" loading="lazy" decoding="async">
                                        <?php else: ?>
                                            <div class="w-full h-full bg-gray-50 flex items-center justify-center"><svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 text-sm line-clamp-1 max-w-[150px]" title="<?= htmlspecialchars($order['first_product_name'] ?? 'Product') ?>"><?= htmlspecialchars($order['first_product_name'] ?? 'Product') ?></p>
                                        <?php if (($order['total_items'] ?? 1) > 1): ?>
                                            <p class="text-[10px] font-bold text-brand-600 mt-0.5">+<?= ($order['total_items'] - 1) ?> other item(s)</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <?php
                                    $statusColors = [
                                        'placed' => 'bg-blue-100 text-blue-800',
                                        'packed' => 'bg-purple-100 text-purple-800',
                                        'shipped' => 'bg-indigo-100 text-indigo-800',
                                        'out_for_delivery' => 'bg-orange-100 text-orange-800',
                                        'delivered' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800'
                                    ];
                                    $color = $statusColors[$order['status']] ?? 'bg-gray-100 text-gray-800';
                                    $displayStatus = str_replace('_', ' ', $order['status']);
                                ?>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider <?= $color ?>">
                                    <?= $displayStatus ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <?php 
                                    $isCod = (($order['balance_amount'] ?? 0) > 0 || ($order['cod_advance_paid'] ?? 0) > 0);
                                    $paymentType = $isCod ? 'COD' : 'Online';
                                    $payColor = $isCod ? 'bg-orange-50 text-orange-700 border-orange-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                ?>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider border <?= $payColor ?>">
                                    <?= $paymentType ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 font-black text-gray-900 text-right">₹<?= number_format(($order['grand_total'] ?? 0) > 0 ? $order['grand_total'] : $order['total_amount'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination UI -->
        <div class="px-6 py-5 border-t border-[#a3514b] flex items-center justify-center bg-[#fdfbf9] rounded-b-3xl">
            <div class="flex items-center gap-2">
                <!-- Double Left (First Page) -->
                <a href="<?= $currentPage > 1 ? '?id=' . $buyer['id'] . '&page=1' : '#' ?>" 
                   class="w-10 h-10 flex items-center justify-center rounded-xl border border-[#a3514b] bg-[#a3514b] text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-colors shadow-sm <?= $currentPage <= 1 ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path></svg>
                </a>
                
                <!-- Single Left (Previous Page) -->
                <a href="<?= $currentPage > 1 ? '?id=' . $buyer['id'] . '&page=' . ($currentPage - 1) : '#' ?>" 
                   class="w-10 h-10 flex items-center justify-center rounded-xl border border-[#a3514b] bg-[#a3514b] text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-colors shadow-sm <?= $currentPage <= 1 ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                
                <!-- Page Numbers -->
                <?php
                    // Show a window of 3 pages around the current page
                    $startPage = max(1, $currentPage - 1);
                    $endPage = min($totalPages, $currentPage + 1);
                    
                    if ($currentPage == 1) {
                        $endPage = min($totalPages, 3);
                    }
                    if ($currentPage == $totalPages) {
                        $startPage = max(1, $totalPages - 2);
                    }

                    for ($i = $startPage; $i <= $endPage; $i++):
                        $isActive = ($i == $currentPage);
                ?>
                    <?php if ($isActive): ?>
                        <a href="?id=<?= $buyer['id'] ?>&page=<?= $i ?>" 
                           class="w-10 h-10 flex items-center justify-center rounded-xl font-black text-lg shadow-sm transition-colors text-white" style="background-color: #750e07;">
                            <?= $i ?>
                        </a>
                    <?php else: ?>
                        <a href="?id=<?= $buyer['id'] ?>&page=<?= $i ?>" 
                           class="w-10 h-10 flex items-center justify-center rounded-xl font-black text-lg shadow-sm transition-colors border border-[#eae0d5] bg-white text-gray-500 hover:bg-gray-50">
                            <?= $i ?>
                        </a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <!-- Single Right (Next Page) -->
                <a href="<?= $currentPage < $totalPages ? '?id=' . $buyer['id'] . '&page=' . ($currentPage + 1) : '#' ?>" 
                   class="w-10 h-10 flex items-center justify-center rounded-xl border border-[#eae0d5] bg-white text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-colors shadow-sm <?= $currentPage >= $totalPages ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
                
                <!-- Double Right (Last Page) -->
                <a href="<?= $currentPage < $totalPages ? '?id=' . $buyer['id'] . '&page=' . $totalPages : '#' ?>" 
                   class="w-10 h-10 flex items-center justify-center rounded-xl border border-[#eae0d5] bg-white text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-colors shadow-sm <?= $currentPage >= $totalPages ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php if ($buyer['status'] === 'pending'): ?>
    <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-4">
        <form id="reject-form" action="<?= BASE_URL ?>/admin/buyers/reject" method="POST" style="display: none;">
            <input type="hidden" name="user_id" id="reject-user-id" value="<?= $buyer['id'] ?>">
            <input type="hidden" name="rejection_reason" id="reject-reason-input">
        </form>
        <button type="button" onclick="promptReject(<?= $buyer['id'] ?>, '<?= addslashes(htmlspecialchars($buyer['business_name'], ENT_QUOTES)) ?>')" class="inline-flex items-center px-6 py-2.5 border border-red-300 text-red-700 bg-white hover:bg-red-50 font-bold rounded-lg shadow-sm transition-colors">
            Reject Registration
        </button>
        <form action="<?= BASE_URL ?>/admin/buyers/approve" method="POST">
            <input type="hidden" name="user_id" value="<?= $buyer['id'] ?>">
            <button type="submit" class="inline-flex items-center px-8 py-2.5 border border-transparent text-white bg-green-600 hover:bg-green-700 font-bold rounded-lg shadow-sm transition-colors">
                Approve Buyer
            </button>
        </form>
    </div>
<?php endif; ?>

<script>
function verifyGstPan(type, value) {
    const btn = document.getElementById('verifyBtn');
    const resultBox = document.getElementById('verificationResult');
    const resultContent = document.getElementById('verifyResultContent');
    const badge = document.getElementById('verifyStatusBadge');

    if (!value) {
        alert('No value provided to verify.');
        return;
    }

    btn.disabled = true;
    const originalText = btn.innerText;
    btn.innerHTML = `
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg> Verifying...
    `;

    fetch(`<?= BASE_URL ?>/admin/buyers/verify?type=${type}&value=${encodeURIComponent(value)}`)
        .then(res => res.json())
        .then(response => {
            btn.disabled = false;
            btn.innerText = originalText;

            if (!response.success) {
                alert(response.message || 'Verification failed.');
                return;
            }

            resultBox.classList.remove('hidden');
            const data = response.data;
            let html = '';

            if (type === 'gst') {
                badge.innerText = 'Active GSTIN';
                badge.className = 'px-2.5 py-0.5 rounded text-xs font-bold bg-green-100 text-green-800';
                
                html = `
                    <div class="col-span-1">
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Legal Name</p>
                        <p class="font-semibold text-gray-900 mt-0.5">${data.legal_name}</p>
                    </div>
                    <div class="col-span-1">
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Trade Name</p>
                        <p class="font-semibold text-gray-900 mt-0.5">${data.trade_name}</p>
                    </div>
                    <div class="col-span-1">
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Constitution of Business</p>
                        <p class="text-gray-900 mt-0.5">${data.constitution}</p>
                    </div>
                    <div class="col-span-1">
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Taxpayer Type</p>
                        <p class="text-gray-900 mt-0.5">${data.taxpayer_type}</p>
                    </div>
                    <div class="col-span-1">
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Registration Date</p>
                        <p class="text-gray-900 mt-0.5">${data.registration_date}</p>
                    </div>
                    <div class="col-span-1">
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">State Jurisdiction</p>
                        <p class="text-gray-900 mt-0.5">${data.state}</p>
                    </div>
                    <div class="col-span-2 border-t pt-3 mt-2">
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Principal Place of Business</p>
                        <p class="text-gray-900 mt-0.5">${data.address}</p>
                    </div>
                `;
            } else {
                badge.innerText = 'Valid PAN';
                badge.className = 'px-2.5 py-0.5 rounded text-xs font-bold bg-green-100 text-green-800';

                let gstinsHtml = data.gstins.map(g => `
                    <div class="flex items-center justify-between bg-white border rounded px-3 py-1.5 mt-1 text-xs shadow-sm">
                        <span class="font-mono font-bold text-gray-800">${g}</span>
                        <span class="text-emerald-600 font-bold flex items-center tracking-wide">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                            Registered GST
                        </span>
                    </div>
                `).join('');

                html = `
                    <div class="col-span-1">
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Legal Name (on PAN Card)</p>
                        <p class="font-semibold text-gray-900 mt-0.5">${data.legal_name}</p>
                    </div>
                    <div class="col-span-1">
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">PAN Type / Constitution</p>
                        <p class="font-semibold text-gray-900 mt-0.5">${data.pan_type}</p>
                    </div>
                    <div class="col-span-1">
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">PAN Status</p>
                        <p class="text-green-600 font-black tracking-wide mt-0.5">${data.status}</p>
                    </div>
                    <div class="col-span-2 border-t pt-3 mt-2">
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider mb-2">Associated GST Registrations</p>
                        <div class="space-y-1.5">
                            ${gstinsHtml || '<p class="text-xs text-gray-400 italic">No associated GST registrations found.</p>'}
                        </div>
                    </div>
                `;
            }

            resultContent.innerHTML = html;
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerText = originalText;
            console.error(err);
            alert('An error occurred during verification.');
        });
}

function promptReject(userId, businessName) {
    Swal.fire({
        title: 'Reject Registration',
        html: `Rejecting application from: <b>${businessName}</b><br><br>Please enter the reason for rejection:`,
        input: 'textarea',
        inputPlaceholder: 'e.g., Documents provided are unclear...',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Confirm Rejection',
        inputValidator: (value) => {
            if (!value || value.trim().length === 0) {
                return 'You need to write a rejection reason!';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('reject-user-id').value = userId;
            document.getElementById('reject-reason-input').value = result.value;
            document.getElementById('reject-form').submit();
        }
    });
}
</script>
