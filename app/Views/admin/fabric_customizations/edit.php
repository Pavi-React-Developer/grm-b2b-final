<div class="p-6 max-w-5xl mx-auto">
    <!-- Breadcrumb & Header -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-amber-600 mb-1">
            <a href="<?= BASE_URL ?>/admin/fabric-customizations" class="hover:underline">Fabric Rules</a>
            <span>/</span>
            <span>Edit Rule #<?= $rule['id'] ?></span>
        </div>
        <h1 class="text-2xl font-black text-gray-900 font-display">
            ✏️ Edit Fabric Customization Rule
        </h1>
        <p class="text-sm text-gray-500 mt-1">Configure order piece limits, without cover discount, and dynamic delivery schedules for wholesale fabrics.</p>
    </div>

    <form action="<?= BASE_URL ?>/admin/fabric-customizations/update" method="POST" id="ruleForm" class="space-y-6">
        <input type="hidden" name="id" value="<?= $rule['id'] ?>">
        <input type="hidden" name="fabric_id" value="<?= $rule['fabric_id'] ?>">
        <input type="hidden" name="program_name" value="<?= htmlspecialchars($rule['program_name'] ?? 'Fabric Customization Rule') ?>">
        <input type="hidden" name="garment_type" value="<?= htmlspecialchars($rule['garment_type'] ?? 'Custom Garment') ?>">
        <input type="hidden" name="category_id" value="<?= $rule['category_id'] ?? '' ?>">
        <input type="hidden" name="sub_category_id" value="<?= $rule['sub_category_id'] ?? '' ?>">
        <input type="hidden" name="feeding_type" value="<?= htmlspecialchars($rule['feeding_type'] ?? 'Non-Feeding') ?>">

        <!-- Customization Rule Settings -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-xs space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <!-- Required Pieces to Enable Payment (Min & Max Limits) -->
                <div class="sm:col-span-2 bg-gradient-to-r from-amber-50/50 via-white to-amber-50/30 p-4 rounded-xl border border-amber-200/80">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold text-gray-800 uppercase tracking-wider flex items-center gap-1.5">
                            <span>Required Order Pieces (Min / Max Limits)</span>
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-amber-100 text-amber-800 font-bold border border-amber-200">Dynamic Scaling</span>
                        </label>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 mb-1">
                                Minimum Required Pieces <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="minimum_pieces" value="<?= isset($rule['minimum_pieces']) ? (int)$rule['minimum_pieces'] : 10 ?>" min="1" required placeholder="e.g. 10" class="w-full px-4 py-2.5 text-sm bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 font-bold text-gray-900 pr-14">
                                <span class="absolute right-3.5 top-2.5 text-xs text-gray-400 font-semibold">Pcs (Min)</span>
                            </div>
                            <p class="text-[10px] text-gray-500 mt-1">
                                Base pieces required before checkout is unlocked (scales with Sets).
                            </p>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 mb-1">
                                Maximum Allowed Pieces <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="maximum_pieces" value="<?= isset($rule['maximum_pieces']) ? (int)$rule['maximum_pieces'] : 100 ?>" min="1" required placeholder="e.g. 100" class="w-full px-4 py-2.5 text-sm bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 font-bold text-gray-900 pr-14">
                                <span class="absolute right-3.5 top-2.5 text-xs text-gray-400 font-semibold">Pcs (Max)</span>
                            </div>
                            <p class="text-[10px] text-gray-500 mt-1">
                                Maximum total pieces buyer can order per set multiplier.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Without Cover Discount Percentage -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2 flex items-center justify-between">
                        <span>Without Cover Discount (%)</span>
                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-700 font-bold border border-emerald-200">Price Reduction</span>
                    </label>
                    <div class="relative">
                        <input type="number" name="without_cover_discount_percentage" value="<?= isset($rule['without_cover_discount_percentage']) ? number_format((float)$rule['without_cover_discount_percentage'], 2, '.', '') : '0.00' ?>" step="0.01" min="0" max="100" placeholder="e.g. 5.00" class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 font-bold text-emerald-700 pr-14">
                        <span class="absolute right-3.5 top-2.5 text-xs text-gray-400 font-semibold">% OFF</span>
                    </div>
                    <p class="text-[11px] text-gray-500 mt-1">
                        When buyer selects <strong>Without Cover</strong> in UI, the base product price is reduced by this percentage.
                    </p>
                </div>

                <!-- Dynamic Delivery Dates Setting -->
                <div class="bg-gray-50/80 p-3.5 rounded-xl border border-gray-200">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <span>🚚 Dynamic Delivery Schedule</span>
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-600 mb-1">Days</label>
                            <div class="relative">
                                <input type="number" name="delivery_days" value="<?= isset($rule['delivery_days']) ? (int)$rule['delivery_days'] : 7 ?>" min="1" required class="w-full px-3 py-2 text-xs bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 font-bold text-gray-900 pr-12">
                                <span class="absolute right-2.5 top-2 text-[10px] text-gray-400 font-medium">Days</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-600 mb-1">Per Pieces</label>
                            <div class="relative">
                                <input type="number" name="delivery_base_pieces" value="<?= isset($rule['delivery_base_pieces']) ? (int)$rule['delivery_base_pieces'] : 100 ?>" min="1" required class="w-full px-3 py-2 text-xs bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 font-bold text-gray-900 pr-12">
                                <span class="absolute right-2.5 top-2 text-[10px] text-gray-400 font-medium">Pcs</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-[10px] text-gray-500 mt-1">
                        e.g. 7 days for 100 pcs; scales dynamically as buyer qty increases.
                    </p>
                </div>

                <!-- Status -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Rule Status
                    </label>
                    <select name="status" class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 font-medium">
                        <option value="active" <?= $rule['status'] === 'active' ? 'selected' : '' ?>>Active (Visible on Storefront)</option>
                        <option value="inactive" <?= $rule['status'] === 'inactive' ? 'selected' : '' ?>>Inactive (Draft / Hidden)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="<?= BASE_URL ?>/admin/fabric-customizations" class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-bold rounded-xl transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white text-sm font-bold rounded-xl shadow-sm hover:shadow transition-all">
                Update Fabric Rule
            </button>
        </div>
    </form>
</div>
