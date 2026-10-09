<?php
/**
 * @var string $title
 * @var array $buyers
 * @var bool $isPendingView
 */
$totalPendingBuyers = count($buyers);
$totalPendingOrders = array_sum(array_column($buyers, 'pending_orders_count'));
$totalPendingValue  = array_sum(array_column($buyers, 'total_pending_amount'));
$avgPendingValue    = $totalPendingBuyers > 0 ? ($totalPendingValue / $totalPendingBuyers) : 0;
?>

<!-- Chart.js CDN for Sparklines -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .saas-card {
        background-color: #FFFFFF !important;
        border: 1px solid #F1F5F9 !important;
        border-radius: 16px !important;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04), 0 1px 2px -1px rgba(0, 0, 0, 0.04) !important;
    }
    .icon-16 { width: 16px !important; height: 16px !important; max-width: 16px !important; max-height: 16px !important; flex-shrink: 0; }
    .icon-20 { width: 20px !important; height: 20px !important; max-width: 20px !important; max-height: 20px !important; flex-shrink: 0; }
    .icon-32 { width: 32px !important; height: 32px !important; max-width: 32px !important; max-height: 32px !important; flex-shrink: 0; }
</style>

<div class="w-full">
    <!-- Header Section -->
    <div class="mb-8 relative z-10">
        <!-- Breadcrumbs -->
        <div class="text-sm font-medium text-gray-400 mb-2 font-serif tracking-wide">
            Dashboard <span class="mx-1 text-gray-300">›</span> Order Management <span class="mx-1 text-gray-300">›</span> <span class="text-brand-700">Pending Orders (By Buyer)</span>
        </div>
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 w-full">
            <div>
                <h2 class="text-4xl sm:text-5xl font-display font-extrabold text-gray-900 tracking-tight">Pending Orders</h2>
                <p class="text-sm text-gray-500 mt-1">Showing buyers with uncompleted or pending checkout payments.</p>
            </div>
            
            <div class="flex items-center space-x-3">
                <a href="<?= BASE_URL ?>/admin/orders" class="bg-white hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-full font-bold text-sm shadow-sm border border-gray-200 transition-all flex items-center">
                    <svg class="w-4 h-4 mr-2 icon-16" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    View All Orders
                </a>
                <button onclick="location.reload()" class="bg-white hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-full font-bold text-sm shadow-sm border border-gray-200 transition-all flex items-center uppercase tracking-wider">
                    <svg class="w-4 h-4 mr-2 icon-16" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Refresh
                </button>
            </div>
        </div>

        <!-- Small Overview KPI Cards (Matching Dashboard SaaS Aesthetics) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <!-- Card 1: Total Pending Revenue (Purple) -->
            <div class="saas-card p-5 relative overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-sm">
                            ₹
                        </div>
                        <span class="text-xs font-medium text-slate-500">Pending Revenue</span>
                    </div>
                    <div class="text-2xl font-extrabold text-slate-900 mt-2">
                        ₹<?= number_format($totalPendingValue, 2) ?>
                    </div>
                    <div class="mt-1 flex items-center text-[11px] font-semibold text-purple-600">
                        <span>Uncollected checkout value</span>
                    </div>
                </div>
                <!-- Purple Sparkline -->
                <div class="w-full h-9 mt-3">
                    <canvas id="sparklinePendingRev"></canvas>
                </div>
            </div>

            <!-- Card 2: Total Pending Orders (Sky Blue) -->
            <div class="saas-card p-5 relative overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="w-9 h-9 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center">
                            <svg class="w-4 h-4 icon-16" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                        <span class="text-xs font-medium text-slate-500">Pending Orders</span>
                    </div>
                    <div class="text-2xl font-extrabold text-slate-900 mt-2">
                        <?= number_format($totalPendingOrders) ?>
                    </div>
                    <div class="mt-1 flex items-center text-[11px] font-semibold">
                        <span class="text-amber-500 bg-amber-50 px-2 py-0.5 rounded-full mr-2"><?= $totalPendingOrders ?> pending</span> 
                        <span class="text-slate-400 font-normal">needs attention</span>
                    </div>
                </div>
                <!-- Blue Sparkline -->
                <div class="w-full h-9 mt-3">
                    <canvas id="sparklinePendingOrders"></canvas>
                </div>
            </div>

            <!-- Card 3: Pending Buyers (Green/Emerald) -->
            <div class="saas-card p-5 relative overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg class="w-4 h-4 icon-16" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <span class="text-xs font-medium text-slate-500">Pending Buyers</span>
                    </div>
                    <div class="text-2xl font-extrabold text-slate-900 mt-2">
                        <?= number_format($totalPendingBuyers) ?>
                    </div>
                    <div class="mt-1 flex items-center text-[11px] font-semibold text-emerald-600">
                        <span>Unique buyer profiles</span>
                    </div>
                </div>
                <!-- Green Sparkline -->
                <div class="w-full h-9 mt-3">
                    <canvas id="sparklinePendingBuyers"></canvas>
                </div>
            </div>

            <!-- Card 4: Avg Pending Value (Orange) -->
            <div class="saas-card p-5 relative overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="w-9 h-9 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                            <svg class="w-4 h-4 icon-16" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <span class="text-xs font-medium text-slate-500">Avg Value / Buyer</span>
                    </div>
                    <div class="text-2xl font-extrabold text-slate-900 mt-2">
                        ₹<?= number_format($avgPendingValue, 2) ?>
                    </div>
                    <div class="mt-1 flex items-center text-[11px] font-semibold text-orange-600">
                        <span>Per pending customer</span>
                    </div>
                </div>
                <!-- Orange Sparkline -->
                <div class="w-full h-9 mt-3">
                    <canvas id="sparklineAvgValue"></canvas>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="bg-white rounded-full shadow-sm border border-gray-100 p-1.5 flex items-center">
            <div class="flex-grow flex items-center pl-5">
                <svg class="w-5 h-5 mr-1 text-gray-400 icon-20" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" id="buyerSearchInput" oninput="filterPendingBuyers()" placeholder="Search pending buyers by name, phone, email, business name..." class="w-full pl-3 pr-4 py-2.5 bg-transparent border-none focus:ring-0 text-gray-700 placeholder-gray-400 outline-none font-medium text-sm">
            </div>
        </div>
    </div>

    <!-- Buyers Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 mb-6 overflow-hidden">
        <div class="overflow-x-auto admin-table-scroll pb-2">
            <table class="w-full text-left border-collapse min-w-[1050px]" id="pendingBuyersTable">
                <thead>
                    <tr class="bg-gray-50/75 text-gray-500 text-xs uppercase tracking-wider font-semibold border-b border-gray-100">
                        <th class="px-6 py-4">Buyer Profile</th>
                        <th class="px-6 py-4">Contact Info</th>
                        <th class="px-6 py-4 text-center">Pending Orders</th>
                        <th class="px-6 py-4 text-right">Total Pending Value</th>
                        <th class="px-6 py-4">Last Attempted</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="pendingBuyersTbody">
                    <?php if (empty($buyers)): ?>
                        <tr id="emptyRow">
                            <td colspan="6" class="px-6 py-16 text-center text-gray-500">
                                <div class="max-w-sm mx-auto">
                                    <div class="w-16 h-16 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 icon-32" width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <h3 class="text-base font-bold text-gray-800 mb-1">No Pending Orders</h3>
                                    <p class="text-sm text-gray-500">All buyer checkout payments have either been paid or completed.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($buyers as $b): 
                            $initial = strtoupper(substr($b['buyer_name'] ?? 'B', 0, 1));
                            $colors = [
                                'bg-[#fdf2f7] text-[#F25996] border border-[#fbaed2]',
                                'bg-pink-100 text-[#e04481] border border-pink-200',
                                'bg-rose-100 text-[#F25996] border border-rose-200',
                                'bg-pink-50 text-[#c72e6b] border border-pink-200',
                                'bg-fuchsia-50 text-[#F25996] border border-fuchsia-200'
                            ];
                            $colorClass = $colors[ord($initial) % count($colors)];
                        ?>
                        <tr class="buyer-row hover:bg-gray-50/60 transition-colors" data-search="<?= htmlspecialchars(strtolower($b['buyer_name'] . ' ' . $b['buyer_email'] . ' ' . $b['buyer_phone'] . ' ' . ($b['business_name'] ?? '') . ' ' . ($b['unique_buyer_id'] ?? ''))) ?>">
                            <!-- Buyer Profile -->
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full <?= $colorClass ?> flex items-center justify-center font-bold text-sm flex-shrink-0 shadow-sm">
                                        <?= $initial ?>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 flex items-center gap-2">
                                            <?= htmlspecialchars($b['buyer_name']) ?>
                                            <?php if (!empty($b['unique_buyer_id'])): ?>
                                                <span class="text-[10px] px-1.5 py-0.5 rounded bg-gray-100 text-gray-600 font-mono font-medium">#<?= htmlspecialchars($b['unique_buyer_id']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if (!empty($b['business_name'])): ?>
                                            <p class="text-xs text-gray-500 font-medium"><?= htmlspecialchars($b['business_name']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>

                            <!-- Contact Info -->
                            <td class="px-6 py-4 text-sm">
                                <div class="text-gray-900 font-medium"><?= htmlspecialchars($b['buyer_phone'] ?: '—') ?></div>
                                <div class="text-xs text-gray-500 mt-0.5"><?= htmlspecialchars($b['buyer_email'] ?: '—') ?></div>
                            </td>

                            <!-- Pending Orders Count -->
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                    <?= (int)$b['pending_orders_count'] ?> <?= (int)$b['pending_orders_count'] === 1 ? 'Order' : 'Orders' ?>
                                </span>
                            </td>

                            <!-- Total Pending Amount -->
                            <td class="px-6 py-4 text-right font-black text-gray-900 text-base">
                                ₹<?= number_format((float)$b['total_pending_amount'], 2) ?>
                            </td>

                            <!-- Last Attempted Date -->
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <div class="font-medium text-gray-800"><?= date('d M Y, h:i A', strtotime($b['latest_order_date'])) ?></div>
                                <div class="text-xs text-gray-400 mt-0.5"><?= human_time_ago($b['latest_order_date']) ?></div>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right">
                                <a href="<?= BASE_URL ?>/admin/orders/pending?buyer_id=<?= $b['user_id'] ?>" 
                                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#F25996] hover:bg-[#e04481] text-white font-bold text-xs rounded-xl shadow-sm transition-all">
                                    <svg class="w-4 h-4 icon-16" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    View Products
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

<script>
function filterPendingBuyers() {
    const query = (document.getElementById('buyerSearchInput').value || '').toLowerCase().trim();
    const rows = document.querySelectorAll('.buyer-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const searchData = row.getAttribute('data-search') || '';
        if (!query || searchData.includes(query)) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    let noResultsEl = document.getElementById('noResultsRow');
    if (visibleCount === 0 && rows.length > 0) {
        if (!noResultsEl) {
            const tbody = document.getElementById('pendingBuyersTbody');
            const tr = document.createElement('tr');
            tr.id = 'noResultsRow';
            tr.innerHTML = `<td colspan="6" class="px-6 py-10 text-center text-gray-500 font-medium">No pending buyers match "${query}".</td>`;
            tbody.appendChild(tr);
        }
    } else if (noResultsEl) {
        noResultsEl.remove();
    }
}

// Sparklines initialization matching dashboard aesthetics
document.addEventListener('DOMContentLoaded', function() {
    function createSparkline(canvasId, data, color) {
        const el = document.getElementById(canvasId);
        if (!el) return;
        new Chart(el, {
            type: 'line',
            data: {
                labels: data.map((_, i) => i),
                datasets: [{
                    data: data,
                    borderColor: color,
                    borderWidth: 2,
                    tension: 0.4,
                    pointRadius: 0,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { enabled: false } },
                scales: {
                    x: { display: false },
                    y: { display: false }
                }
            }
        });
    }

    createSparkline('sparklinePendingRev', [20, 24, 22, 28, 25, 30, 27, 34, 31, 38, 35, 42], '#F25996');
    createSparkline('sparklinePendingOrders', [15, 18, 14, 22, 19, 25, 21, 28, 24, 30, 27, 32], '#F25996');
    createSparkline('sparklinePendingBuyers', [10, 12, 11, 15, 13, 17, 16, 20, 18, 22, 20, 25], '#22C55E');
    createSparkline('sparklineAvgValue', [30, 28, 29, 26, 28, 25, 27, 24, 25, 23, 24, 22], '#FB923C');
});
</script>
