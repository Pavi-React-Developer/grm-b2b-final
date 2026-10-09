<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    header {
        display: none !important;
    }
    .v-card {
        background-color: #FFFFFF !important;
        border: 1px solid #E2E8F0 !important;
        border-radius: 16px !important;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04), 0 1px 2px -1px rgba(0, 0, 0, 0.04) !important;
    }
    .v-stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<div class="w-full max-w-full font-sans text-slate-800 pb-12">

    <!-- Store Profile & Welcome Banner -->
    <div class="v-card p-6 mb-6 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white border-0 shadow-lg relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 opacity-10 text-white pointer-events-none">
            <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-7-3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3zm7 17H5V8h14v12zm-7-8c-1.66 0-3-1.34-3-3H7c0 2.76 2.24 5 5 5s5-2.24 5-5h-2c0 1.66-1.34 3-3 3z"/></svg>
        </div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="bg-emerald-500/20 text-emerald-300 text-xs font-semibold px-2.5 py-1 rounded-full border border-emerald-400/30 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Vendor Store Portal
                    </span>
                    <span class="bg-white/10 text-white/90 text-xs font-mono font-medium px-2.5 py-1 rounded-full border border-white/10">
                        ID: <?= htmlspecialchars($profile['unique_vendor_id'] ?? (!empty($user['id']) ? 'VN' . str_pad($user['id'], 5, '0', STR_PAD_LEFT) : 'VN00001')) ?>
                    </span>
                    <span class="bg-white/10 text-white/80 text-xs font-medium px-2.5 py-1 rounded-full capitalize">
                        <?= htmlspecialchars($profile['vendor_type'] ?? 'Wholesaler') ?>
                    </span>
                </div>
                <h1 class="text-2xl lg:text-3xl font-bold tracking-tight text-white mb-1">
                    <?= htmlspecialchars($profile['store_name'] ?? 'Vendor Dashboard') ?>
                </h1>
                <p class="text-xs text-slate-300 max-w-xl">
                    <?= htmlspecialchars($profile['company_name'] ?? '') ?> 
                    <?php if (!empty($profile['gst_number'])): ?>
                        • GST: <span class="font-mono text-emerald-300"><?= htmlspecialchars($profile['gst_number']) ?></span>
                    <?php elseif (!empty($profile['pan_number'])): ?>
                        • PAN: <span class="font-mono text-emerald-300"><?= htmlspecialchars($profile['pan_number']) ?></span>
                    <?php endif; ?>
                    <?php if (!empty($profile['city']) && !empty($profile['state'])): ?>
                        • <?= htmlspecialchars($profile['city']) ?>, <?= htmlspecialchars($profile['state']) ?>
                    <?php endif; ?>
                </p>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex flex-wrap items-center gap-3">
                <a href="<?= BASE_URL ?>/admin/catalog/products/create" class="inline-flex items-center gap-2 bg-[#F25996] hover:bg-[#e04481] text-white font-semibold text-xs px-4 py-2.5 rounded-xl shadow-sm transition transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    + Add New Product
                </a>
                <a href="<?= BASE_URL ?>/admin/catalog/products" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white font-semibold text-xs px-4 py-2.5 rounded-xl border border-white/20 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Catalog (<?= count($vendorProducts ?? []) ?>)
                </a>
                <a href="<?= BASE_URL ?>/admin/orders" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white font-semibold text-xs px-4 py-2.5 rounded-xl border border-white/20 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    Orders
                </a>
            </div>
        </div>
    </div>

    <!-- 4 Core Performance KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        
        <!-- Total Products -->
        <div class="v-card p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Products</span>
                <div class="v-stat-icon bg-purple-50 text-purple-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-slate-900">
                <?= count($vendorProducts ?? []) ?> Products
            </div>
            <div class="text-[11px] text-purple-600 font-semibold mt-1">
                Listed in store catalog
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="v-card p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Gross Revenue</span>
                <div class="v-stat-icon bg-emerald-50 text-emerald-600 font-bold text-lg">
                    ₹
                </div>
            </div>
            <div class="text-2xl font-extrabold text-emerald-600">
                ₹<?= number_format((float)($stats['total_revenue'] ?? 0), 2) ?>
            </div>
            <div class="text-[11px] text-emerald-600/90 font-medium mt-1 flex flex-col gap-1">
                <span>From paid matured orders</span>
                <?php if (!empty($stats['holding_revenue']) && (float)$stats['holding_revenue'] > 0): ?>
                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-700 bg-amber-50 border border-amber-200/80 px-2 py-0.5 rounded-full w-fit">
                        ⏳ +₹<?= number_format((float)$stats['holding_revenue'], 2) ?> in <?= (int)($stats['holding_days'] ?? 7) ?>-day holding
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Total Units Sold -->
        <div class="v-card p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Units Sold</span>
                <div class="v-stat-icon bg-indigo-50 text-indigo-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-slate-900">
                <?= number_format((int)($stats['total_units_sold'] ?? 0)) ?> pcs
            </div>
            <div class="text-[11px] text-indigo-600 font-medium mt-1">
                Total item volume ordered
            </div>
        </div>

        <!-- Total Paid Orders -->
        <div class="v-card p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Paid Orders</span>
                <div class="v-stat-icon bg-blue-50 text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-slate-900">
                <?= number_format((int)($stats['total_orders'] ?? 0)) ?> Orders
            </div>
            <div class="text-[11px] text-amber-600 font-medium mt-1 flex flex-col gap-0.5">
                <span><?= (int)($stats['pending_orders'] ?? 0) ?> pending fulfillment</span>
                <?php if (!empty($stats['holding_orders_count']) && (int)$stats['holding_orders_count'] > 0): ?>
                    <span class="text-[10px] text-amber-700 font-semibold">(<?= (int)$stats['holding_orders_count'] ?> orders maturing soon)</span>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <!-- MAIN SECTION: ALL VENDOR PRODUCTS & INDIVIDUAL PRODUCT REVENUE BREAKDOWN -->
    <div class="v-card p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-4 mb-6">
            <div>
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    My Products & Revenue Breakdown
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Complete list of all products in your store and the revenue generated by each</p>
            </div>
            <div class="flex items-center gap-3">
                <input type="text" id="productSearchInput" onkeyup="filterVendorProducts()" placeholder="Search product name or SKU..." class="px-3.5 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none w-64 bg-slate-50 focus:bg-white transition">
                <a href="<?= BASE_URL ?>/admin/catalog/products/create" class="inline-flex items-center gap-1.5 text-xs font-semibold bg-emerald-600 hover:bg-emerald-500 text-white px-3.5 py-2 rounded-xl transition shadow-xs">
                    + Add Product
                </a>
            </div>
        </div>

        <?php if (empty($vendorProducts)): ?>
            <div class="py-16 text-center text-slate-400">
                <div class="w-16 h-16 mx-auto bg-slate-100 rounded-2xl flex items-center justify-center text-slate-300 mb-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-700 mb-1">No Products in Your Catalog</h3>
                <p class="text-xs text-slate-400 mb-4">Start by adding your first product to generate sales and revenue.</p>
                <a href="<?= BASE_URL ?>/admin/catalog/products/create" class="inline-flex items-center gap-2 text-xs font-bold bg-[#F25996] text-white px-4 py-2.5 rounded-xl hover:bg-[#e04481] transition">
                    + Add Product Now
                </a>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs" id="vendorProductsTable">
                    <thead>
                        <tr class="text-slate-400 uppercase text-[10px] tracking-wider border-b border-slate-100 bg-slate-50/50">
                            <th class="py-3 px-4 rounded-l-xl">Product</th>
                            <th class="py-3 px-3">SKU</th>
                            <th class="py-3 px-3 text-right">Wholesale Price</th>
                            <th class="py-3 px-3 text-center">Stock</th>
                            <th class="py-3 px-3 text-center">Units Sold</th>
                            <th class="py-3 px-3 text-center">Paid Orders</th>
                            <th class="py-3 px-3 text-right font-bold text-slate-700">Gross Revenue</th>
                            <th class="py-3 px-3 text-center">Status</th>
                            <th class="py-3 px-4 text-right rounded-r-xl">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($vendorProducts as $prod): ?>
                            <tr class="hover:bg-slate-50/80 transition group prod-row">
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl bg-slate-100 overflow-hidden border border-slate-200 flex-shrink-0 flex items-center justify-center">
                                            <?php 
                                                $imgSrc = $prod['primary_image'] ?? '';
                                                $finalImgUrl = '';
                                                if (!empty($imgSrc)) {
                                                    $finalImgUrl = (strpos($imgSrc, 'http://') === 0 || strpos($imgSrc, 'https://') === 0) 
                                                        ? $imgSrc 
                                                        : BASE_URL . '/' . ltrim($imgSrc, '/');
                                                }
                                            ?>
                                            <?php if ($finalImgUrl): ?>
                                                <img src="<?= htmlspecialchars($finalImgUrl) ?>" alt="" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <span class="text-lg">📦</span>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <a href="<?= BASE_URL ?>/admin/catalog/products/edit?id=<?= $prod['id'] ?>" class="font-bold text-slate-900 group-hover:text-indigo-600 transition text-sm block">
                                                <?= htmlspecialchars($prod['name']) ?>
                                            </a>
                                            <span class="inline-block text-[11px] text-slate-400 font-medium">
                                                <?= htmlspecialchars($prod['category_name'] ?? 'Uncategorized') ?>
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-3 font-mono text-[11px] text-slate-500">
                                    <?= htmlspecialchars($prod['base_sku'] ?: ('#' . $prod['id'])) ?>
                                </td>
                                <td class="py-3 px-3 text-right font-semibold text-slate-800">
                                    ₹<?= number_format((float)($prod['wholesale_price'] ?? 0), 2) ?>
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <?php $stock = (int)($prod['stock_quantity'] ?? 0); ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-semibold rounded-full <?= $stock <= 5 ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' ?>">
                                        <span class="w-1.5 h-1.5 rounded-full <?= $stock <= 5 ? 'bg-rose-500' : 'bg-emerald-500' ?>"></span>
                                        <?= $stock ?> in stock
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-center font-bold text-indigo-600 text-sm">
                                    <?= (int)$prod['total_sold_qty'] ?> pcs
                                </td>
                                <td class="py-3 px-3 text-center font-semibold text-slate-600">
                                    <?= (int)$prod['paid_orders_count'] ?>
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <span class="font-extrabold text-base <?= ((float)$prod['total_revenue'] > 0) ? 'text-emerald-600' : 'text-slate-400' ?>">
                                        ₹<?= number_format((float)$prod['total_revenue'], 2) ?>
                                    </span>
                                    <?php if (!empty($prod['holding_revenue']) && (float)$prod['holding_revenue'] > 0): ?>
                                        <div class="text-[10px] font-bold text-amber-600 flex items-center justify-end gap-1 mt-0.5" title="Revenue currently in maturation holding window">
                                            <span>+₹<?= number_format((float)$prod['holding_revenue'], 2) ?></span>
                                            <span class="bg-amber-100 text-amber-800 px-1 py-0.2 rounded text-[9px]">holding</span>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <?php if ($prod['status'] === 'active' && ($prod['approval_status'] === 'approved' || empty($prod['approval_status']))): ?>
                                        <span class="inline-block px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-100 text-emerald-700">
                                            Active
                                        </span>
                                    <?php elseif ($prod['approval_status'] === 'pending'): ?>
                                        <span class="inline-block px-2 py-0.5 text-[10px] font-bold rounded-full bg-amber-100 text-amber-700">
                                            Pending Review
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-block px-2 py-0.5 text-[10px] font-bold rounded-full bg-slate-100 text-slate-600">
                                            <?= htmlspecialchars($prod['status']) ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <a href="<?= BASE_URL ?>/admin/catalog/products/edit?id=<?= $prod['id'] ?>" class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition">
                                        Edit &rarr;
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-50 font-bold text-slate-900 border-t-2 border-slate-200">
                            <td class="py-3.5 px-4" colspan="4">
                                Total Performance (<?= count($vendorProducts) ?> Products)
                            </td>
                            <td class="py-3.5 px-3 text-center text-indigo-600">
                                <?= number_format((int)($stats['total_units_sold'] ?? 0)) ?> pcs
                            </td>
                            <td class="py-3.5 px-3 text-center">
                                <?= (int)($stats['total_orders'] ?? 0) ?> orders
                            </td>
                            <td class="py-3.5 px-3 text-right text-emerald-600 text-base">
                                ₹<?= number_format((float)($stats['total_revenue'] ?? 0), 2) ?>
                            </td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Product Revenue Comparison Visual Graph -->
    <?php if (!empty($vendorProducts) && count($vendorProducts) > 0): ?>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        
        <!-- Revenue per Product Bar Chart (2 cols) -->
        <div class="v-card p-6 lg:col-span-2">
            <h3 class="text-base font-bold text-slate-900 mb-1">Product Revenue Comparison</h3>
            <p class="text-xs text-slate-400 mb-4">Gross sales revenue generated per product</p>
            <div class="relative h-64">
                <canvas id="productRevenueBarChart"></canvas>
            </div>
        </div>

        <!-- Units Sold per Product Doughnut (1 col) -->
        <div class="v-card p-6">
            <h3 class="text-base font-bold text-slate-900 mb-1">Units Sold Share</h3>
            <p class="text-xs text-slate-400 mb-4">Share of volume sold across products</p>
            <div class="relative h-64 flex items-center justify-center">
                <canvas id="productUnitsDoughnutChart"></canvas>
            </div>
        </div>

    </div>
    <?php endif; ?>

    <!-- Recent Orders for Vendor -->
    <div class="v-card p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-base font-bold text-slate-900">Recent Customer Orders</h2>
                <p class="text-xs text-slate-400">Latest orders placed for your products</p>
            </div>
            <a href="<?= BASE_URL ?>/admin/orders" class="text-xs font-semibold text-[#F25996] hover:text-[#e04481]">
                View All Orders &rarr;
            </a>
        </div>

        <?php if (empty($recentOrders)): ?>
            <div class="py-8 text-center text-slate-400 text-xs">
                No recent customer orders found for your products.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 uppercase text-[10px] tracking-wider bg-slate-50/50">
                            <th class="py-2.5 px-3 rounded-l-lg">Order #</th>
                            <th class="py-2.5 px-3">Customer</th>
                            <th class="py-2.5 px-3">Date</th>
                            <th class="py-2.5 px-3 text-center">Your Items</th>
                            <th class="py-2.5 px-3 text-right">Vendor Subtotal</th>
                            <th class="py-2.5 px-3 text-center">Status</th>
                            <th class="py-2.5 px-3 text-right rounded-r-lg">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php foreach ($recentOrders as $ro): ?>
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="py-3 px-3 font-mono font-bold text-[#F25996]">
                                    <a href="<?= BASE_URL ?>/admin/orders/view?id=<?= urlencode($ro['order_number']) ?>" class="hover:underline">
                                        #<?= htmlspecialchars($ro['order_number']) ?>
                                    </a>
                                </td>
                                <td class="py-3 px-3">
                                    <div class="font-medium text-slate-900"><?= htmlspecialchars($ro['user_name'] ?? 'Guest Buyer') ?></div>
                                    <div class="text-[10px] text-slate-400"><?= htmlspecialchars($ro['user_phone'] ?? '') ?></div>
                                </td>
                                <td class="py-3 px-3 text-slate-500 whitespace-nowrap">
                                    <?= date('d M Y, h:i A', strtotime($ro['created_at'])) ?>
                                </td>
                                <td class="py-3 px-3 text-center font-semibold text-slate-700">
                                    <?= (int)($ro['vendor_item_qty'] ?? 1) ?> pcs
                                </td>
                                <td class="py-3 px-3 text-right font-bold text-emerald-600">
                                    ₹<?= number_format((float)($ro['vendor_subtotal'] ?? $ro['total_amount']), 2) ?>
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <?php
                                    $statusBadge = match($ro['status']) {
                                        'placed' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'packed' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'shipped' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'out_for_delivery' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'delivered' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        default => 'bg-slate-50 text-slate-700 border-slate-200'
                                    };
                                    ?>
                                    <span class="inline-block px-2.5 py-1 text-[10px] font-semibold rounded-full border <?= $statusBadge ?> capitalize">
                                        <?= str_replace('_', ' ', $ro['status']) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <a href="<?= BASE_URL ?>/admin/orders/view?id=<?= urlencode($ro['order_number']) ?>" class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1 rounded-lg transition">
                                        Details &rarr;
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

</div>

<!-- Interactive Client-side Script & Charts -->
<script>
function filterVendorProducts() {
    const input = document.getElementById('productSearchInput').value.toLowerCase();
    const rows = document.querySelectorAll('.prod-row');
    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(input) ? '' : 'none';
    });
}

document.addEventListener('DOMContentLoaded', function() {
    <?php
    $prodLabels = [];
    $prodRevenues = [];
    $prodUnits = [];
    $palette = ['#10B981', '#6366F1', '#3B82F6', '#F59E0B', '#EC4899', '#8B5CF6'];
    
    if (!empty($vendorProducts)) {
        foreach ($vendorProducts as $vp) {
            $prodLabels[] = htmlspecialchars($vp['name']);
            $prodRevenues[] = (float)$vp['total_revenue'];
            $prodUnits[] = (int)$vp['total_sold_qty'];
        }
    }
    ?>
    const labels = <?= json_encode($prodLabels) ?>;
    const revenues = <?= json_encode($prodRevenues) ?>;
    const units = <?= json_encode($prodUnits) ?>;

    // 1. Revenue Bar Chart
    const barCtx = document.getElementById('productRevenueBarChart');
    if (barCtx && labels.length > 0) {
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Gross Revenue (₹)',
                    data: revenues,
                    backgroundColor: '#10B981',
                    borderRadius: 8,
                    barThickness: 32
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Revenue: ₹' + context.parsed.y.toLocaleString('en-IN', {minimumFractionDigits: 2});
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₹' + value.toLocaleString('en-IN');
                            }
                        },
                        grid: { color: '#F1F5F9' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // 2. Units Sold Doughnut Chart
    const doughnutCtx = document.getElementById('productUnitsDoughnutChart');
    if (doughnutCtx && labels.length > 0) {
        const hasSoldUnits = units.some(u => u > 0);
        new Chart(doughnutCtx, {
            type: 'doughnut',
            data: {
                labels: hasSoldUnits ? labels : ['No sales yet'],
                datasets: [{
                    data: hasSoldUnits ? units : [1],
                    backgroundColor: hasSoldUnits ? ['#10B981', '#F25996', '#FDBFDD', '#F59E0B', '#EC4899', '#db2777'] : ['#E2E8F0'],
                    borderWidth: 2,
                    borderColor: '#FFFFFF'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (!hasSoldUnits) return 'No sales recorded yet';
                                return context.label + ': ' + context.parsed + ' units sold';
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    }
});
</script>
