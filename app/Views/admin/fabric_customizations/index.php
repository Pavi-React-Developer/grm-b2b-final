<div class="p-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-amber-600 mb-1">
                <span>Customize Module</span>
                <span>•</span>
                <span>Garment Engineering</span>
            </div>
            <h1 class="text-2xl font-black text-gray-900 font-display flex items-center gap-3">
                <span>✂️ Fabric Customization Rules</span>
                <span class="text-xs font-semibold px-2.5 py-1 bg-amber-100 text-amber-800 rounded-full border border-amber-200">
                    <?= $totalRules ?> Programs
                </span>
            </h1>
            <p class="text-sm text-gray-500 mt-1">Configure size consumption rates, minimum stitch pieces, and wastage factors for wholesale fabrics.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?= BASE_URL ?>/admin/customize/orders" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-bold rounded-xl shadow-xs transition-colors">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <span>Custom Orders</span>
            </a>
            <a href="<?= BASE_URL ?>/admin/fabric-customizations/create" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white text-sm font-bold rounded-xl shadow-sm hover:shadow transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Add Fabric Rule</span>
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Total Rules</p>
                <p class="text-2xl font-black text-gray-900 mt-1"><?= $totalRules ?></p>
                <p class="text-xs text-gray-500 mt-0.5">Across all garment types</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xl">
                📐
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Active Programs</p>
                <p class="text-2xl font-black text-emerald-600 mt-1"><?= $activeRules ?></p>
                <p class="text-xs text-gray-500 mt-0.5">Live on storefront /customize</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xl">
                ⚡
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Customizable Fabrics</p>
                <p class="text-2xl font-black text-indigo-600 mt-1"><?= $totalConfiguredFabrics ?></p>
                <p class="text-xs text-gray-500 mt-0.5">Assigned fabric inventory</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xl">
                🧵
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-xs mb-6">
        <form method="GET" action="<?= BASE_URL ?>/admin/fabric-customizations" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[240px]">
                <div class="relative">
                    <input type="text" name="search" value="<?= htmlspecialchars($filters['search'] ?? '') ?>" placeholder="Search by fabric, SKU, or garment program..." class="w-full pl-10 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <select name="status" class="px-3.5 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                <option value="">All Statuses</option>
                <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active Only</option>
                <option value="inactive" <?= ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive Only</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-gray-900 hover:bg-black text-white text-sm font-semibold rounded-xl transition-colors">
                Filter
            </button>
            <?php if (!empty($filters['search']) || !empty($filters['status'])): ?>
                <a href="<?= BASE_URL ?>/admin/fabric-customizations" class="px-3 py-2 text-xs font-semibold text-gray-500 hover:text-gray-700">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Rules List -->
    <?php if (empty($rules)): ?>
        <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
            <div class="w-16 h-16 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl">
                ✂️
            </div>
            <h3 class="text-lg font-bold text-gray-900">No Fabric Customization Rules Found</h3>
            <p class="text-sm text-gray-500 max-w-md mx-auto mt-1 mb-6">Create rules mapping fabric products to garment types, allowed size distributions, and consumption formulas.</p>
            <a href="<?= BASE_URL ?>/admin/fabric-customizations/create" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-600 text-white font-bold text-sm rounded-xl shadow-sm hover:shadow transition-all">
                + Create First Fabric Rule
            </a>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($rules as $r): ?>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-xs hover:border-amber-200 transition-all p-5">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-gray-100">
                        <!-- Left Info -->
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-100 to-orange-100 border border-amber-200 text-amber-800 flex items-center justify-center font-black text-lg flex-shrink-0">
                                🧵
                            </div>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-base font-bold text-gray-900">
                                        <?= htmlspecialchars($r['program_name']) ?>
                                    </h3>
                                    <span class="text-xs px-2.5 py-0.5 rounded-full font-bold bg-amber-50 text-amber-700 border border-amber-200">
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
                                    <span class="text-xs px-2 py-0.5 rounded-full font-bold <?= $r['status'] === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' ?>">
                                        <?= ucfirst($r['status']) ?>
                                    </span>
                                </div>
                                <div class="flex items-center gap-4 text-xs text-gray-500 mt-1">
                                    <span>Fabric: <strong class="text-gray-800"><?= htmlspecialchars($r['fabric_name']) ?></strong> (SKU: <?= htmlspecialchars($r['fabric_sku'] ?? 'N/A') ?>)</span>
                                    <span>•</span>
                                    <span>Stock: <strong class="text-gray-800"><?= (int)$r['fabric_stock'] ?> <?= htmlspecialchars($r['unit']) ?></strong></span>
                                    <span>•</span>
                                    <span>Wholesale Rate: <strong class="text-gray-800">₹<?= number_format($r['fabric_price'], 2) ?>/<?= htmlspecialchars($r['unit']) ?></strong></span>
                                </div>
                            </div>
                        </div>

                        <!-- Right Actions -->
                        <div class="flex items-center gap-2 flex-wrap">
                            <a href="<?= BASE_URL ?>/fabrics/customize?fabric_id=<?= $r['fabric_id'] ?>" target="_blank" class="px-3 py-1.5 bg-gray-50 hover:bg-gray-100 text-gray-700 text-xs font-bold rounded-lg border border-gray-200 transition-colors flex items-center gap-1">
                                <span>Preview Workshop</span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                            <a href="<?= BASE_URL ?>/admin/fabric-customizations/edit?id=<?= $r['id'] ?>" class="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-bold rounded-lg border border-amber-200 transition-colors">
                                Edit Rule
                            </a>
                            <form action="<?= BASE_URL ?>/admin/fabric-customizations/delete" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this fabric customization rule?');">
                                <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Rule Breakdown & Size Variants -->
                    <div class="mt-4 pt-1">
                        <div class="bg-gray-50/70 p-3.5 rounded-xl border border-gray-100">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-gray-500">
                                    Configured Size Variants & Allowed Limits:
                                </span>
                                <span class="text-[11px] font-semibold text-gray-400">
                                    Total Base: <?= array_sum(array_map(fn($s) => (int)($s['base_quantity'] ?? 2), array_filter($r['sizes'] ?? [], fn($s) => !empty($s['enabled'])))) ?> pcs
                                </span>
                            </div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <?php if (!empty($r['sizes'])): ?>
                                    <?php foreach ($r['sizes'] as $sz): ?>
                                        <span class="inline-flex items-center gap-1.5 text-xs font-bold bg-white text-gray-800 px-3 py-1.5 rounded-lg border border-gray-200 shadow-2xs">
                                            <span class="text-amber-800 font-black"><?= htmlspecialchars($sz['size_name']) ?></span>
                                            <span class="text-gray-400">•</span>
                                            <span class="text-gray-600">Base: <?= (int)($sz['base_quantity'] ?? 2) ?></span>
                                            <span class="text-gray-400">|</span>
                                            <span class="text-amber-700">Max: <?= (int)($sz['max_quantity'] ?? 4) ?> pcs</span>
                                        </span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="text-xs text-gray-400 italic">No size variants configured</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
