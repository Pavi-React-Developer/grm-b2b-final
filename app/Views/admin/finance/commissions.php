<div class="mb-8 relative z-10">
    <!-- Breadcrumbs -->
    <div class="text-sm font-medium text-gray-400 mb-2 font-serif tracking-wide">
        Dashboard <span class="mx-1 text-gray-300">›</span> <a href="<?= BASE_URL ?>/admin/finance/payments" class="hover:text-brand-600">Finance Management</a> <span class="mx-1 text-gray-300">›</span> <span class="text-brand-700">GST & Fee Configuration</span>
    </div>
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-5xl font-display font-extrabold text-gray-900 tracking-tight">
                GST & Fee Rate Configuration
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Configure GST rates, platform fee deductions, and vendor revenue splits across Global Defaults, Specific Vendors, Categories, and Products.
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="<?= BASE_URL ?>/admin/finance/payments" class="bg-white hover:bg-gray-50 text-gray-700 px-6 py-3 rounded-full font-bold text-xs border border-gray-200 shadow-sm transition uppercase tracking-wider">
                &larr; Back to Payments Overview
            </a>
        </div>
    </div>
</div>

<!-- Commission Hierarchy Explanation Banner -->
<div class="bg-indigo-50/60 border border-indigo-100 p-4 rounded-2xl mb-6 text-xs text-indigo-950 flex items-start gap-3">
    <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-sm shrink-0">
        ℹ️
    </div>
    <div>
        <h4 class="font-bold text-indigo-900 mb-1">GST & Rate Deduction Hierarchy (Precedence Order):</h4>
        <div class="flex flex-wrap items-center gap-2 font-medium">
            <span class="bg-white px-2.5 py-1 rounded-lg border border-indigo-200 font-bold text-indigo-700">1. Product GST / Rate Override</span>
            <span class="text-indigo-400">&rarr;</span>
            <span class="bg-white px-2.5 py-1 rounded-lg border border-indigo-200 font-bold text-indigo-700">2. Individual Vendor Override</span>
            <span class="text-indigo-400">&rarr;</span>
            <span class="bg-white px-2.5 py-1 rounded-lg border border-indigo-200 font-bold text-indigo-700">3. Category GST Rate</span>
            <span class="text-indigo-400">&rarr;</span>
            <span class="bg-white px-2.5 py-1 rounded-lg border border-indigo-200 font-bold text-indigo-700">4. Global Standard Rate</span>
        </div>
        <p class="text-[11px] text-indigo-700 mt-1.5">Calculates the Admin Amount (GST) deducted from order items, with the remainder credited to the Vendor Amount.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- TIER 1: Global Platform Default Commission & Withdrawal Threshold -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                <div class="flex items-center gap-2">
                    <span class="w-7 h-7 bg-indigo-100 text-indigo-700 rounded-lg flex items-center justify-center font-bold text-xs">01</span>
                    <h3 class="font-bold text-gray-900 text-base">Global Platform Defaults</h3>
                </div>
                <span class="text-[10px] font-bold bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-full">Base Rule</span>
            </div>

            <form action="<?= BASE_URL ?>/admin/finance/commissions/save" method="POST" class="space-y-4">
                <input type="hidden" name="setting_type" value="global">

                <div>
                    <label for="default_commission_rate" class="block text-xs font-bold text-gray-700 mb-1">
                        Default Platform Commission Rate (%) *
                    </label>
                    <div class="relative">
                        <input type="text" step="0.1" min="0" max="100" id="default_commission_rate" name="default_commission_rate" value="<?= htmlspecialchars($globalRate) ?>" required class="w-full px-3 py-2 pr-8 border border-gray-200 rounded-xl text-sm font-bold text-gray-900 focus:ring-indigo-500 focus:border-indigo-500">
                        <span class="absolute right-3 top-2.5 text-gray-400 font-bold text-xs">%</span>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">Applied to any order where no custom vendor or category rate is specified.</p>
                </div>

                <div>
                    <label for="min_withdrawal_amount" class="block text-xs font-bold text-gray-700 mb-1">
                        Admin Configured Vendor Withdrawal Limit (₹) *
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-gray-400 font-bold text-xs">₹</span>
                        <input type="text" step="1" min="1" id="min_withdrawal_amount" name="min_withdrawal_amount" value="<?= htmlspecialchars($minWithdrawal) ?>" required class="w-full pl-7 pr-3 py-2 border border-gray-200 rounded-xl text-sm font-bold text-gray-900 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">Vendors can request up to this maximum limit configured by Admin per payout request.</p>
                </div>

                <div>
                    <label for="vendor_revenue_holding_days" class="block text-xs font-bold text-gray-700 mb-1">
                        Vendor Revenue Maturation / Holding Period (Days) *
                    </label>
                    <div class="relative">
                        <input type="text" step="1" min="0" max="365" id="vendor_revenue_holding_days" name="vendor_revenue_holding_days" value="<?= htmlspecialchars($holdingDays ?? 7) ?>" required class="w-full px-3 py-2 pr-12 border border-gray-200 rounded-xl text-sm font-bold text-gray-900 focus:ring-indigo-500 focus:border-indigo-500">
                        <span class="absolute right-3 top-2.5 text-gray-400 font-bold text-xs">Days</span>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">When a buyer places an order, revenue matures and shows in the vendor's realized dashboard & wallet after this many days (e.g. 7 days). Set 0 for instant release.</p>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2 bg-[#F25996] hover:bg-[#e04481] text-white font-bold text-xs rounded-xl transition shadow">
                        Save Global Defaults
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TIER 2: Category Commission Rates -->
    <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                <div class="flex items-center gap-2">
                    <span class="w-7 h-7 bg-emerald-100 text-emerald-700 rounded-lg flex items-center justify-center font-bold text-xs">02</span>
                    <h3 class="font-bold text-gray-900 text-base">Category-Wise Commission Rates</h3>
                </div>
                <span class="text-[10px] font-bold bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full"><?= count($categories) ?> Categories</span>
            </div>

            <p class="text-xs text-gray-500 mb-3">Set category-specific commission rates (e.g. 5% on Kids Wear, 12% on Electronics). Leave blank to use Global Default (<?= number_format($globalRate, 1) ?>%).</p>

            <div class="overflow-y-auto max-h-64 border border-gray-100 rounded-xl">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 font-bold uppercase tracking-wider border-b border-gray-100">
                            <th class="py-2.5 px-3">Category Name</th>
                            <th class="py-2.5 px-3 text-center">Products</th>
                            <th class="py-2.5 px-3 text-right">Commission Rate (%)</th>
                            <th class="py-2.5 px-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($categories as $c): ?>
                            <tr class="hover:bg-gray-50/50">
                                <td class="py-2 px-3 font-bold text-gray-800">
                                    <?= htmlspecialchars($c['name']) ?>
                                </td>
                                <td class="py-2 px-3 text-center text-gray-400">
                                    <?= (int)$c['product_count'] ?>
                                </td>
                                <td class="py-2 px-3 text-right">
                                    <form action="<?= BASE_URL ?>/admin/finance/commissions/save" method="POST" class="inline-flex items-center gap-1.5 justify-end">
                                        <input type="hidden" name="setting_type" value="category">
                                        <input type="hidden" name="category_id" value="<?= $c['id'] ?>">
                                        <input type="text" step="0.1" min="0" max="100" name="commission_rate" value="<?= $c['commission_rate'] !== null ? htmlspecialchars($c['commission_rate']) : '' ?>" placeholder="<?= $globalRate ?>%" class="w-20 px-2 py-1 border border-gray-200 rounded-lg text-xs font-bold text-right focus:ring-emerald-500 focus:border-emerald-500">
                                        <button type="submit" class="px-2 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-[10px] transition">Save</button>
                                    </form>
                                </td>
                                <td class="py-2 px-3 text-center">
                                    <?php if ($c['commission_rate'] !== null && $c['commission_rate'] !== ''): ?>
                                        <span class="text-[10px] text-emerald-700 font-bold bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100">Active</span>
                                    <?php else: ?>
                                        <span class="text-[10px] text-gray-400 italic">Inherits Default</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- TIER 3: Individual Vendor Commission Overrides -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-gray-50/50">
        <div>
            <div class="flex items-center gap-2">
                <span class="w-7 h-7 bg-purple-100 text-purple-700 rounded-lg flex items-center justify-center font-bold text-xs">03</span>
                <h3 class="font-bold text-gray-900 text-base">Individual Vendor Commission Overrides</h3>
            </div>
            <p class="text-xs text-gray-500 mt-0.5">Assign negotiated custom commission rates for specific vendors. Overrides Category and Global default rates.</p>
        </div>
        <span class="text-xs font-semibold text-gray-400"><?= count($vendors) ?> Vendors</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-gray-50 text-gray-500 font-bold uppercase tracking-wider border-b border-gray-100">
                    <th class="py-3 px-4">Vendor / Store Name</th>
                    <th class="py-3 px-4">Owner & Contact</th>
                    <th class="py-3 px-4 text-center">Current Status</th>
                    <th class="py-3 px-4 text-center">Effective Rate Status</th>
                    <th class="py-3 px-4 text-right">Custom Commission Rate (%)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 font-medium">
                <?php foreach ($vendors as $v): ?>
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="py-3 px-4">
                            <div class="font-bold text-gray-900"><?= htmlspecialchars($v['store_name'] ?? $v['name']) ?></div>
                            <div class="text-[10px] text-gray-400 font-mono">
                                ID: <span class="text-indigo-600 font-bold"><?= htmlspecialchars($v['unique_vendor_id'] ?? 'VN'.$v['id']) ?></span>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="text-gray-800"><?= htmlspecialchars($v['name']) ?></div>
                            <div class="text-[10px] text-gray-400"><?= htmlspecialchars($v['email']) ?></div>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-100 text-emerald-700">
                                <?= htmlspecialchars($v['status'] ?? 'active') ?>
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <?php 
                            $vRate = $v['commission_rate'] ?? null;
                            if ($vRate !== null && $vRate !== ''): 
                            ?>
                                <span class="bg-purple-50 text-purple-700 px-2.5 py-0.5 rounded-full font-bold text-[10px] border border-purple-200">
                                    ⭐ Custom (<?= number_format((float)$vRate, 1) ?>%)
                                </span>
                            <?php else: ?>
                                <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full font-bold text-[10px]">
                                    Global Default (<?= number_format($globalRate, 1) ?>%)
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <form action="<?= BASE_URL ?>/admin/finance/commissions/save" method="POST" class="inline-flex items-center gap-2 justify-end">
                                <input type="hidden" name="setting_type" value="vendor">
                                <input type="hidden" name="vendor_id" value="<?= $v['id'] ?>">
                                <div class="relative">
                                    <input type="text" step="0.1" min="0" max="100" name="commission_rate" value="<?= ($v['commission_rate'] ?? null) !== null ? htmlspecialchars((string)$v['commission_rate']) : '' ?>" placeholder="<?= $globalRate ?>%" class="w-24 px-3 py-1.5 border border-gray-200 rounded-xl text-xs font-bold text-right focus:ring-purple-500 focus:border-purple-500">
                                    <span class="absolute right-2.5 top-1.5 text-gray-400 font-bold text-xs">%</span>
                                </div>
                                <button type="submit" class="px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl font-bold text-xs transition shadow-sm">
                                    Update
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- TIER 4: Product Commission Overrides -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
        <div class="flex items-center gap-2">
            <span class="w-7 h-7 bg-amber-100 text-amber-700 rounded-lg flex items-center justify-center font-bold text-xs">04</span>
            <div>
                <h3 class="font-bold text-gray-900 text-base">Product-Specific Commission Overrides (Optional)</h3>
                <p class="text-xs text-gray-500">Specific product rate takes highest priority over all rules.</p>
            </div>
        </div>
        <span class="text-xs font-semibold text-gray-400"><?= count($products) ?> Products</span>
    </div>

    <div class="overflow-x-auto max-h-80">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-gray-50 text-gray-500 font-bold uppercase tracking-wider border-b border-gray-100">
                    <th class="py-3 px-4">Product Name</th>
                    <th class="py-3 px-4">Vendor</th>
                    <th class="py-3 px-4">Category</th>
                    <th class="py-3 px-4 text-right">Wholesale Price</th>
                    <th class="py-3 px-4 text-right">Custom Product Rate (%)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 font-medium">
                <?php foreach ($products as $p): ?>
                    <tr class="hover:bg-gray-50/50">
                        <td class="py-2.5 px-4 font-bold text-gray-900">
                            <?= htmlspecialchars($p['name']) ?>
                        </td>
                        <td class="py-2.5 px-4 text-gray-600">
                            <?= htmlspecialchars($p['store_name'] ?? 'In-House') ?>
                        </td>
                        <td class="py-2.5 px-4 text-gray-500">
                            <?= htmlspecialchars($p['category_name'] ?? 'General') ?>
                        </td>
                        <td class="py-2.5 px-4 text-right font-bold text-gray-900">
                            ₹<?= number_format($p['wholesale_price'] ?? 0, 2) ?>
                        </td>
                        <td class="py-2.5 px-4 text-right">
                            <form action="<?= BASE_URL ?>/admin/finance/commissions/save" method="POST" class="inline-flex items-center gap-2 justify-end">
                                <input type="hidden" name="setting_type" value="product">
                                <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                                <input type="text" step="0.1" min="0" max="100" name="commission_rate" value="<?= $p['product_comm'] !== null ? htmlspecialchars($p['product_comm']) : '' ?>" placeholder="Default" class="w-20 px-2 py-1 border border-gray-200 rounded-lg text-xs font-bold text-right focus:ring-amber-500 focus:border-amber-500">
                                <button type="submit" class="px-2.5 py-1 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-bold text-[10px] transition">Save</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
