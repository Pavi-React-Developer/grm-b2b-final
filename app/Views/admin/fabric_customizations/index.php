<div class="p-6 max-w-7xl mx-auto">
    <!-- Breadcrumbs -->
    <div class="text-sm font-medium text-gray-400 mb-2 font-serif tracking-wide">
        Dashboard <span class="mx-1 text-gray-300">›</span> Customization Management <span class="mx-1 text-gray-300">›</span> <span class="text-[#F25996]">Fabric Customization Rules</span>
    </div>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl sm:text-4xl font-display font-extrabold text-gray-900 tracking-tight flex items-center gap-3">
                <span>✂️ Fabric Customization Rules</span>
                <span class="text-xs font-bold px-3 py-1 bg-pink-50 text-[#F25996] rounded-full border border-pink-200">
                    <?= $totalRules ?> Programs
                </span>
            </h1>
            <p class="text-sm text-gray-500 mt-1">Configure size consumption rates, minimum stitch pieces, and wastage factors for wholesale fabrics.</p>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
            <a href="<?= BASE_URL ?>/admin/customize/orders" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 text-xs font-bold rounded-full shadow-xs uppercase tracking-wider transition-colors">
                <svg class="w-4 h-4 text-[#F25996]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <span>Custom Orders</span>
            </a>
            <a href="<?= BASE_URL ?>/admin/fabric-customizations/create" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#F25996] hover:bg-[#d8407d] text-white text-xs font-bold rounded-full shadow-md uppercase tracking-widest transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Add Fabric Rule</span>
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Total Rules</p>
                <p class="text-2xl font-black text-gray-900 mt-1"><?= $totalRules ?></p>
                <p class="text-xs text-gray-500 mt-0.5">Across all garment types</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-pink-50 text-[#F25996] flex items-center justify-center font-bold text-xl border border-pink-100">
                📐
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Active Programs</p>
                <p class="text-2xl font-black text-emerald-600 mt-1"><?= $activeRules ?></p>
                <p class="text-xs text-gray-500 mt-0.5">Live on storefront /customize</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xl border border-emerald-100">
                ⚡
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Customizable Fabrics</p>
                <p class="text-2xl font-black text-indigo-600 mt-1"><?= $totalConfiguredFabrics ?></p>
                <p class="text-xs text-gray-500 mt-0.5">Assigned fabric inventory</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xl border border-indigo-100">
                🧵
            </div>
        </div>
    </div>

    <!-- Status Tabs (Image 5 style) -->
    <div class="flex items-center space-x-2 overflow-x-auto mb-4 bg-white/70 backdrop-blur p-1.5 rounded-full border border-gray-100 shadow-sm w-fit">
        <a href="<?= BASE_URL ?>/admin/fabric-customizations<?= !empty($filters['search']) ? '?search=' . urlencode($filters['search']) : '' ?>" class="px-4 py-2 rounded-full text-xs font-bold transition-all flex items-center gap-1.5 whitespace-nowrap <?= empty($filters['status']) ? 'bg-[#F25996] text-white shadow-sm shadow-[#F25996]/30' : 'text-gray-600 hover:bg-gray-100' ?>">
            <span>🌐 All Statuses</span>
        </a>
        <a href="<?= BASE_URL ?>/admin/fabric-customizations?status=active<?= !empty($filters['search']) ? '&search=' . urlencode($filters['search']) : '' ?>" class="px-4 py-2 rounded-full text-xs font-bold transition-all flex items-center gap-1.5 whitespace-nowrap <?= ($filters['status'] ?? '') === 'active' ? 'bg-[#F25996] text-white shadow-sm shadow-[#F25996]/30' : 'text-gray-600 hover:bg-gray-100' ?>">
            <span>✅ Active Only</span>
        </a>
        <a href="<?= BASE_URL ?>/admin/fabric-customizations?status=inactive<?= !empty($filters['search']) ? '&search=' . urlencode($filters['search']) : '' ?>" class="px-4 py-2 rounded-full text-xs font-bold transition-all flex items-center gap-1.5 whitespace-nowrap <?= ($filters['status'] ?? '') === 'inactive' ? 'bg-[#F25996] text-white shadow-sm shadow-[#F25996]/30' : 'text-gray-600 hover:bg-gray-100' ?>">
            <span>⏸️ Inactive Only</span>
        </a>
    </div>

    <!-- Search Bar (Image 5 style) -->
    <div class="bg-white rounded-full shadow-sm border border-gray-100 p-1.5 flex items-center mb-6">
        <form method="GET" action="<?= BASE_URL ?>/admin/fabric-customizations" class="flex-grow flex items-center pl-4 pr-1 gap-2">
            <?php if (!empty($filters['status'])): ?>
                <input type="hidden" name="status" value="<?= htmlspecialchars($filters['status']) ?>">
            <?php endif; ?>
            <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" value="<?= htmlspecialchars($filters['search'] ?? '') ?>" placeholder="Search by fabric, SKU, or garment program..." class="w-full text-xs border-0 focus:ring-0 text-gray-900 placeholder-gray-400 bg-transparent py-2 px-2 focus:outline-none font-medium">
            <button type="submit" class="bg-[#F25996] hover:bg-[#d8407d] text-white px-5 py-2 rounded-full font-bold text-xs uppercase tracking-wider transition-colors shrink-0">
                Filter
            </button>
            <?php if (!empty($filters['search']) || !empty($filters['status'])): ?>
                <a href="<?= BASE_URL ?>/admin/fabric-customizations" class="px-4 py-2 text-xs font-bold text-gray-500 hover:text-gray-700 rounded-full hover:bg-gray-100 transition-colors shrink-0">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Rules List -->
    <?php if (empty($rules)): ?>
        <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center shadow-sm">
            <div class="w-16 h-16 bg-pink-50 text-[#F25996] rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl border border-pink-100">
                ✂️
            </div>
            <h3 class="text-lg font-bold text-gray-900">No Fabric Customization Rules Found</h3>
            <p class="text-sm text-gray-500 max-w-md mx-auto mt-1 mb-6">Create rules mapping fabric products to garment types, allowed size distributions, and consumption formulas.</p>
            <a href="<?= BASE_URL ?>/admin/fabric-customizations/create" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#F25996] hover:bg-[#d8407d] text-white font-bold text-xs rounded-full shadow-md uppercase tracking-widest transition-all">
                + Create First Fabric Rule
            </a>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($rules as $r): ?>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:border-pink-200 hover:shadow-md transition-all p-5">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-gray-100">
                        <!-- Left Info -->
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-pink-50 border border-pink-200 text-[#F25996] flex items-center justify-center font-black text-lg flex-shrink-0">
                                🧵
                            </div>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-base font-bold text-gray-900">
                                        <?= htmlspecialchars($r['program_name']) ?>
                                    </h3>
                                    <span class="text-xs px-2.5 py-0.5 rounded-full font-bold bg-pink-50 text-[#F25996] border border-pink-200">
                                        <?= htmlspecialchars($r['garment_type']) ?>
                                    </span>
                                    <?php 
                                    $featuresList = [];
                                    if (!empty($r['feeding_type'])) {
                                        if (strpos($r['feeding_type'], '[') === 0) {
                                            $dec = json_decode($r['feeding_type'], true);
                                            $featuresList = is_array($dec) ? $dec : [$r['feeding_type']];
                                        } else {
                                            $featuresList = array_filter(array_map('trim', explode(',', $r['feeding_type'])));
                                        }
                                    }
                                    foreach ($featuresList as $feat):
                                    ?>
                                        <span class="text-xs px-2.5 py-0.5 rounded-full font-semibold <?= strpos($feat, 'Feeding') !== false ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-blue-50 text-blue-700 border border-blue-200' ?>">
                                            <?= htmlspecialchars($feat) ?>
                                        </span>
                                    <?php endforeach; ?>
                                    <span class="text-xs px-2.5 py-0.5 rounded-full font-bold <?= $r['status'] === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' ?>">
                                        <?= ucfirst($r['status']) ?>
                                    </span>
                                </div>
                                <div class="flex items-center gap-3 text-xs text-gray-500 mt-1.5 flex-wrap">
                                    <span>Fabric: <strong class="text-gray-800"><?= htmlspecialchars($r['fabric_name']) ?></strong> (SKU: <?= htmlspecialchars($r['fabric_sku'] ?? 'N/A') ?>)</span>
                                    <span>•</span>
                                    <span>Stock: <strong class="text-gray-800"><?= (int)$r['fabric_stock'] ?> Qty</strong></span>
                                    <span>•</span>
                                    <span>Min: <strong class="text-[#F25996] font-bold"><?= (int)($r['minimum_pieces'] ?? 10) ?> Pcs</strong></span>
                                    <?php if (!empty($r['maximum_pieces'])): ?>
                                        <span>•</span>
                                        <span>Max: <strong class="text-gray-800"><?= (int)$r['maximum_pieces'] ?> Pcs</strong></span>
                                    <?php endif; ?>
                                    <?php if (!empty($r['without_cover_discount_percentage']) && (float)$r['without_cover_discount_percentage'] > 0): ?>
                                        <span>•</span>
                                        <span class="inline-flex items-center gap-1 font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                                            Cover Discount: <?= (float)$r['without_cover_discount_percentage'] ?>% OFF
                                        </span>
                                    <?php endif; ?>
                                    <span>•</span>
                                    <span class="inline-flex items-center gap-1 text-indigo-700 font-medium">
                                        🚚 <?= (int)($r['delivery_days'] ?? 7) ?> Days / <?= (int)($r['delivery_base_pieces'] ?? 100) ?> Pcs
                                    </span>
                                    <span>•</span>
                                    <span>Rate: <strong class="text-gray-800">₹<?= number_format($r['fabric_price'], 2) ?></strong></span>
                                </div>
                            </div>
                        </div>

                        <!-- Right Actions -->
                        <div class="flex items-center gap-2 flex-wrap">
                            <a href="<?= BASE_URL ?>/fabrics/customize?fabric_id=<?= $r['fabric_id'] ?>" target="_blank" class="px-4 py-1.5 bg-white hover:bg-gray-50 text-gray-700 text-xs font-bold rounded-full border border-gray-200 shadow-2xs transition-colors flex items-center gap-1">
                                <span>Preview Workshop</span>
                                <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                            <a href="<?= BASE_URL ?>/admin/fabric-customizations/edit?id=<?= $r['id'] ?>" class="px-4 py-1.5 bg-pink-50 hover:bg-[#F25996] hover:text-white text-[#F25996] text-xs font-bold rounded-full border border-pink-200 transition-all">
                                Edit Rule
                            </a>
                            <form action="<?= BASE_URL ?>/admin/fabric-customizations/delete" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this fabric customization rule?');">
                                <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-50 rounded-full transition-colors cursor-pointer">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
