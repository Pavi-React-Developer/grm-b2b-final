<?php
/**
 * @var string $title
 * @var string $range
 * @var array $metrics
 * @var array $vendorSummaries
 * @var string $topVendors (JSON)
 * @var string $revenueTrend (JSON)
 */
?>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="w-full">
    
    <!-- Top Breadcrumbs & Header -->
    <div class="mb-8 relative z-10">
        <div class="text-sm font-medium text-gray-400 mb-2 font-serif tracking-wide">
            Dashboard <span class="mx-1 text-gray-300">›</span> Vendor Management <span class="mx-1 text-gray-300">›</span> <span class="text-brand-700">Vendor Revenues &amp; Analytics</span>
        </div>

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h2 class="text-5xl font-display font-extrabold text-gray-900 tracking-tight flex items-center gap-3">
                    <span>Vendor Analytics</span>
                    <span class="bg-indigo-100 text-indigo-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                        Marketplace Hub
                    </span>
                </h2>
                <p class="text-sm text-gray-500 mt-1">Real-time marketplace revenue, vendor gross sales, and seller fulfillment performance.</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <!-- Date Filter Pills -->
                <div class="bg-white border border-gray-200 rounded-full p-1 shadow-sm flex items-center text-xs font-semibold">
                    <a href="<?= BASE_URL ?>/admin/vendors/dashboard?range=all" 
                       class="px-3.5 py-1.5 rounded-full transition <?= ($range === 'all') ? 'bg-[#F25996] text-white shadow-sm' : 'text-gray-600 hover:text-gray-900' ?>">
                        All Time
                    </a>
                    <a href="<?= BASE_URL ?>/admin/vendors/dashboard?range=this_month" 
                       class="px-3.5 py-1.5 rounded-full transition <?= ($range === 'this_month') ? 'bg-[#F25996] text-white shadow-sm' : 'text-gray-600 hover:text-gray-900' ?>">
                        This Month
                    </a>
                    <a href="<?= BASE_URL ?>/admin/vendors/dashboard?range=30" 
                       class="px-3.5 py-1.5 rounded-full transition <?= ($range === '30') ? 'bg-[#F25996] text-white shadow-sm' : 'text-gray-600 hover:text-gray-900' ?>">
                        Last 30 Days
                    </a>
                    <a href="<?= BASE_URL ?>/admin/vendors/dashboard?range=7" 
                       class="px-3.5 py-1.5 rounded-full transition <?= ($range === '7') ? 'bg-[#F25996] text-white shadow-sm' : 'text-gray-600 hover:text-gray-900' ?>">
                        7 Days
                    </a>
                </div>

                <!-- Export Button -->
                <a href="<?= BASE_URL ?>/admin/vendors/export-revenue?range=<?= htmlspecialchars($range) ?>" 
                   class="bg-white hover:bg-gray-50 text-gray-700 px-4 py-2.5 rounded-full font-bold text-xs shadow-sm border border-gray-200 transition flex items-center gap-1.5 uppercase tracking-wider">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export CSV
                </a>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex items-center gap-2 mb-8 border-b border-gray-200 pb-3">
        <a href="<?= BASE_URL ?>/admin/vendors/dashboard" class="px-4 py-2 bg-brand-50 text-brand-700 border-b-2 border-brand-700 font-bold text-sm rounded-t-lg transition flex items-center gap-2">
            <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            Revenue &amp; Analytics
        </a>
        <a href="<?= BASE_URL ?>/admin/vendors" class="px-4 py-2 text-gray-500 hover:text-gray-900 font-medium text-sm rounded-t-lg transition flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            Active Vendors (<?= $metrics['active_vendors'] ?? 0 ?>)
        </a>
        <a href="<?= BASE_URL ?>/admin/vendors/pending" class="px-4 py-2 text-gray-500 hover:text-gray-900 font-medium text-sm rounded-t-lg transition flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Pending Approvals
            <?php if (($metrics['pending_vendors'] ?? 0) > 0): ?>
                <span class="bg-amber-500 text-white text-[10px] font-black px-1.5 py-0.5 rounded-full ml-1"><?= $metrics['pending_vendors'] ?></span>
            <?php endif; ?>
        </a>
        <a href="<?= BASE_URL ?>/admin/vendors/types" class="px-4 py-2 text-gray-500 hover:text-gray-900 font-medium text-sm rounded-t-lg transition flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            Vendor Types
        </a>
    </div>

    <!-- 5 Hero KPI Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 mb-8">
        
        <!-- Total Vendor Revenue -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Gross Vendor Sales</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">
                    ₹
                </div>
            </div>
            <div class="text-2xl font-extrabold text-gray-900">
                ₹<?= number_format($metrics['total_revenue'], 2) ?>
            </div>
            <p class="text-[11.5px] text-emerald-600 font-semibold mt-1 flex items-center gap-1">
                <span>✓</span> Completed &amp; Paid Orders
            </p>
        </div>

        <!-- Total Units Sold -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Units Sold</span>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-gray-900">
                <?= number_format($metrics['total_units_sold']) ?>
            </div>
            <p class="text-[11.5px] text-gray-400 mt-1">Across all vendor products</p>
        </div>

        <!-- Vendor Orders -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Orders</span>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-gray-900">
                <?= number_format($metrics['total_orders']) ?>
            </div>
            <p class="text-[11.5px] text-amber-600 font-medium mt-1">
                <?= $metrics['pending_fulfillment'] ?> awaiting packing / dispatch
            </p>
        </div>

        <!-- Active Marketplace Vendors -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Active Vendors</span>
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-gray-900">
                <?= number_format($metrics['active_vendors']) ?>
            </div>
            <p class="text-[11.5px] text-gray-400 mt-1">
                <?= $metrics['pending_vendors'] ?> pending applications
            </p>
        </div>

        <!-- Vendor Catalog Products -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Vendor Catalog</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-gray-900">
                <?= number_format($metrics['total_products']) ?>
            </div>
            <p class="text-[11.5px] <?= $metrics['low_stock_products'] > 0 ? 'text-rose-500 font-semibold' : 'text-gray-400' ?> mt-1">
                <?= $metrics['low_stock_products'] > 0 ? ($metrics['low_stock_products'] . ' low stock items') : 'Stock levels healthy' ?>
            </p>
        </div>

    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- Top Revenue Generating Vendors Chart (2 Cols) -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Top Revenue Generating Vendors</h3>
                    <p class="text-xs text-gray-500">Ranked by total gross sales volume</p>
                </div>
            </div>
            <div class="relative h-64 w-full">
                <canvas id="topVendorsChart"></canvas>
            </div>
        </div>

        <!-- Monthly Vendor Revenue Trend (1 Col) -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Monthly Revenue Trend</h3>
                    <p class="text-xs text-gray-500">Marketplace growth over 6 months</p>
                </div>
            </div>
            <div class="relative h-64 w-full flex items-center justify-center">
                <canvas id="revenueTrendChart"></canvas>
            </div>
        </div>

    </div>

    <!-- Vendor Revenue & Performance Breakdown Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-gray-900">All Vendor Revenues &amp; Performance</h3>
                <p class="text-xs text-gray-500">Detailed financial performance per registered vendor store</p>
            </div>
            
            <div class="w-full md:w-72">
                <div class="relative">
                    <input type="text" id="vendorSearchInput" oninput="filterVendorTable()" placeholder="Search vendor name, ID, city..." 
                           class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#1a50a8] font-medium">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="vendorRevenueTable">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-[11px] font-bold uppercase tracking-wider border-b border-gray-100">
                        <th class="py-3.5 px-6">Vendor / Store</th>
                        <th class="py-3.5 px-4">Contact Info</th>
                        <th class="py-3.5 px-4 text-center">Catalog</th>
                        <th class="py-3.5 px-4 text-center">Orders</th>
                        <th class="py-3.5 px-4 text-center">Units Sold</th>
                        <th class="py-3.5 px-4 text-right">Gross Revenue</th>
                        <th class="py-3.5 px-4 text-center">Fulfillment</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs">
                    <?php if (empty($vendorSummaries)): ?>
                        <tr>
                            <td colspan="8" class="py-12 text-center text-gray-400">
                                No registered vendors found.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($vendorSummaries as $idx => $vs): 
                            $initial = strtoupper(substr($vs['store_name'], 0, 1));
                            $colors = [
                                'bg-[#fdf2f7] text-[#F25996] border border-[#fbaed2]',
                                'bg-pink-100 text-[#e04481] border border-pink-200',
                                'bg-rose-100 text-[#F25996] border border-rose-200',
                                'bg-pink-50 text-[#c72e6b] border border-pink-200',
                                'bg-fuchsia-50 text-[#F25996] border border-fuchsia-200'
                            ];
                            $avatarColor = $colors[ord($initial) % count($colors)];
                        ?>
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="py-4 px-6">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-sm <?= $avatarColor ?>">
                                            <?= $initial ?>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 text-sm flex items-center gap-2">
                                                <span><?= htmlspecialchars($vs['store_name']) ?></span>
                                                <span class="font-mono text-[10px] font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 px-1.5 py-0.2 rounded">
                                                    <?= htmlspecialchars($vs['unique_vendor_id'] ?? ('VN' . str_pad($vs['user_id'], 5, '0', STR_PAD_LEFT))) ?>
                                                </span>
                                            </div>
                                            <div class="text-gray-400 text-[11px]">
                                                <?= htmlspecialchars($vs['company_name'] ?: $vs['owner_name']) ?>
                                                <?php if (!empty($vs['city'])): ?>
                                                    • <?= htmlspecialchars($vs['city']) ?>, <?= htmlspecialchars($vs['state']) ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="font-medium text-gray-800"><?= htmlspecialchars($vs['owner_name']) ?></div>
                                    <div class="text-gray-400 text-[11px]"><?= htmlspecialchars($vs['email']) ?></div>
                                    <div class="text-gray-400 text-[11px]"><?= htmlspecialchars($vs['phone']) ?></div>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="font-bold text-gray-800"><?= (int)$vs['total_products'] ?></span>
                                    <span class="text-gray-400 text-[11px] block">products</span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="font-bold text-gray-800"><?= (int)$vs['total_paid_orders'] ?></span>
                                    <span class="text-gray-400 text-[11px] block">orders</span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded text-xs">
                                        <?= (int)$vs['total_units_sold'] ?> pcs
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <div class="text-sm font-black text-gray-900">
                                        ₹<?= number_format((float)$vs['gross_revenue'], 2) ?>
                                    </div>
                                    <div class="text-[10px] text-gray-400">Total Sales</div>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <?php if ($vs['pending_fulfillment_orders'] > 0): ?>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-amber-100 text-amber-800">
                                            ⏳ <?= $vs['pending_fulfillment_orders'] ?> pending
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-100 text-emerald-800">
                                            ✓ All Fulfilled
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <a href="<?= BASE_URL ?>/admin/vendors/view?id=<?= $vs['user_id'] ?>" 
                                       class="px-3 py-1.5 bg-[#1a50a8] hover:bg-[#153e83] text-white text-xs font-bold rounded-lg transition inline-flex items-center gap-1 shadow-sm">
                                        <span>View Store</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
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

<!-- Client-side filter & Chart rendering scripts -->
<script>
function filterVendorTable() {
    let input = document.getElementById('vendorSearchInput');
    let filter = input.value.toLowerCase();
    let table = document.querySelector("#vendorRevenueTable tbody");
    if (!table) return;
    let tr = table.getElementsByTagName('tr');

    for (let i = 0; i < tr.length; i++) {
        let text = tr[i].textContent || tr[i].innerText;
        if (text.toLowerCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const topVendorsData = <?= $topVendors ?? '[]' ?>;
    const revenueTrendData = <?= $revenueTrend ?? '[]' ?>;

    // Top Vendors Bar Chart
    if (topVendorsData && topVendorsData.length > 0) {
        const vendorLabels = topVendorsData.map(v => v.store_name);
        const vendorRevenues = topVendorsData.map(v => parseFloat(v.total_revenue));

        const ctxV = document.getElementById('topVendorsChart').getContext('2d');
        new Chart(ctxV, {
            type: 'bar',
            data: {
                labels: vendorLabels,
                datasets: [{
                    label: 'Gross Revenue (₹)',
                    data: vendorRevenues,
                    backgroundColor: '#10b981',
                    borderRadius: 8,
                    barThickness: 36
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
                                return 'Revenue: ₹' + Number(context.raw).toLocaleString('en-IN', {minimumFractionDigits: 2});
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [4, 4], color: '#f1f5f9' },
                        ticks: {
                            callback: function(val) {
                                return '₹' + Number(val).toLocaleString('en-IN');
                            }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // Revenue Trend Chart
    if (revenueTrendData && revenueTrendData.length > 0) {
        const trendLabels = revenueTrendData.map(t => t.month_label);
        const trendRevenues = revenueTrendData.map(t => parseFloat(t.revenue));

        const ctxT = document.getElementById('revenueTrendChart').getContext('2d');
        new Chart(ctxT, {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Monthly Revenue (₹)',
                    data: trendRevenues,
                    borderColor: '#F25996',
                    backgroundColor: 'rgba(242, 89, 150, 0.12)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 3,
                    pointBackgroundColor: '#F25996',
                    pointRadius: 5
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
                                return 'Revenue: ₹' + Number(context.raw).toLocaleString('en-IN', {minimumFractionDigits: 2});
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [4, 4], color: '#f1f5f9' },
                        ticks: {
                            callback: function(val) {
                                return '₹' + Number(val).toLocaleString('en-IN');
                            }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }
});
</script>
