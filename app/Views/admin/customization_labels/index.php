<?php
/**
 * Admin Customization Labels / Templates Index View
 * 
 * @var string $title
 * @var array $labels
 * @var array $groups
 * @var array $filters
 * @var int $totalLabels
 * @var int $activeLabels
 */
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- Top Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-pink-600 mb-1">
                <a href="<?= BASE_URL ?>/admin/fabric-customizations" class="hover:underline">Customize Studio</a>
                <span>/</span>
                <span>Customization Labels</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight flex items-center gap-3">
                <span>🏷️ Customization Labels & Templates</span>
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Define hierarchical style tags (e.g. <code>Maternity -&gt; Feeding -&gt; Full Sleeve</code>) that can be dynamically assigned to fabrics and customized with user quantities.
            </p>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            <a href="<?= BASE_URL ?>/admin/catalog/products?module=customize" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 text-xs font-bold rounded-xl shadow-xs transition-all">
                <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <span>Customizable Fabrics</span>
            </a>
            <?php if ($this->hasPermission('customization_labels', 'create')): ?>
            <a href="<?= BASE_URL ?>/admin/customization-labels/create" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-pink-500 to-rose-600 hover:from-pink-600 hover:to-rose-700 text-white text-xs font-bold rounded-xl shadow-sm hover:shadow transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>+ Add New Label</span>
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Labels</span>
                <div class="text-2xl font-black text-gray-900 mt-1"><?= $totalLabels ?></div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center font-bold text-lg">
                🏷️
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Active Templates</span>
                <div class="text-2xl font-black text-emerald-600 mt-1"><?= $activeLabels ?></div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">
                ✓
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Style Categories</span>
                <div class="text-2xl font-black text-indigo-600 mt-1"><?= count($groups) ?></div>
            </div>
            <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg">
                📁
            </div>
        </div>
    </div>

    <!-- Filters & Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-xs">
        <form action="<?= BASE_URL ?>/admin/customization-labels" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <input type="text" name="search" value="<?= htmlspecialchars($filters['search'] ?? '') ?>" placeholder="Search by label name, group, or keyword..." class="w-full pl-10 pr-4 py-2 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 font-medium">
                <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <div class="sm:w-48">
                <select name="group_name" onchange="this.form.submit()" class="w-full px-3 py-2 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 font-medium">
                    <option value="">All Groups</option>
                    <?php foreach ($groups as $grp): ?>
                        <option value="<?= htmlspecialchars($grp) ?>" <?= ($filters['group_name'] ?? '') === $grp ? 'selected' : '' ?>>
                            <?= htmlspecialchars($grp) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="sm:w-36">
                <select name="status" onchange="this.form.submit()" class="w-full px-3 py-2 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 font-medium">
                    <option value="">All Statuses</option>
                    <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active Only</option>
                    <option value="inactive" <?= ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive Only</option>
                </select>
            </div>

            <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-xs font-bold rounded-xl hover:bg-gray-800 transition-colors">
                Filter
            </button>
            <?php if (!empty($filters['search']) || !empty($filters['group_name']) || !empty($filters['status'])): ?>
                <a href="<?= BASE_URL ?>/admin/customization-labels" class="px-4 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 text-xs font-bold rounded-xl transition-colors inline-flex items-center justify-center">
                    Clear
                </a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Labels Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-xs overflow-hidden">
        <?php if (empty($labels)): ?>
            <div class="p-12 text-center">
                <div class="w-14 h-14 bg-pink-50 text-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-3 text-2xl font-bold">
                    🏷️
                </div>
                <h3 class="text-base font-bold text-gray-900 mb-1">No Customization Labels Found</h3>
                <p class="text-xs text-gray-500 max-w-sm mx-auto mb-4">
                    Create templates like <code>Maternity -&gt; Feeding -&gt; Full Sleeve</code> to enable granular quantity tailoring.
                </p>
                <?php if ($this->hasPermission('customization_labels', 'create')): ?>
                <a href="<?= BASE_URL ?>/admin/customization-labels/create" class="inline-flex items-center gap-1.5 px-4 py-2 bg-pink-600 text-white text-xs font-bold rounded-xl hover:bg-pink-700 transition-colors">
                    + Create First Label
                </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-gray-50/80 border-b border-gray-100 text-gray-400 font-extrabold uppercase tracking-wider text-[10px]">
                            <th class="px-5 py-3.5">Template / Label Path</th>
                            <th class="px-4 py-3.5">Group & Sub-Group</th>
                            <th class="px-4 py-3.5">Style Name</th>
                            <th class="px-4 py-3.5 text-center">Fabrics Attached</th>
                            <th class="px-4 py-3.5 text-center">Sort Order</th>
                            <th class="px-4 py-3.5 text-center">Status</th>
                            <th class="px-5 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-medium">
                        <?php foreach ($labels as $lbl): ?>
                            <tr class="hover:bg-pink-50/30 transition-colors">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-pink-50 text-pink-800 text-xs font-bold border border-pink-200">
                                            🏷️ <?= htmlspecialchars($lbl['display_label']) ?>
                                        </span>
                                    </div>
                                    <?php if (!empty($lbl['description'])): ?>
                                        <p class="text-[11px] text-gray-500 mt-1 max-w-xs truncate"><?= htmlspecialchars($lbl['description']) ?></p>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="text-gray-900 font-bold"><?= htmlspecialchars($lbl['group_name'] ?: 'General') ?></div>
                                    <?php if (!empty($lbl['sub_group'])): ?>
                                        <div class="text-[11px] text-gray-500">↳ <?= htmlspecialchars($lbl['sub_group']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="text-gray-800 font-bold"><?= htmlspecialchars($lbl['name']) ?></span>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[11px] font-bold bg-gray-100 text-gray-700">
                                        <?= (int)($lbl['used_in_fabrics_count'] ?? 0) ?> fabrics
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-center text-gray-500">
                                    <?= (int)$lbl['sort_order'] ?>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <?php if ($this->hasPermission('customization_labels', 'edit')): ?>
                                    <form action="<?= BASE_URL ?>/admin/customization-labels/toggle-status" method="POST" class="inline">
                                        <input type="hidden" name="id" value="<?= $lbl['id'] ?>">
                                        <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold transition-colors cursor-pointer <?= $lbl['status'] === 'active' ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">
                                            <?= $lbl['status'] === 'active' ? '● Active' : '○ Inactive' ?>
                                        </button>
                                    </form>
                                    <?php else: ?>
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold <?= $lbl['status'] === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' ?>">
                                        <?= $lbl['status'] === 'active' ? '● Active' : '○ Inactive' ?>
                                    </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-5 py-3.5 text-right space-x-2">
                                    <?php if ($this->hasPermission('customization_labels', 'edit')): ?>
                                    <a href="<?= BASE_URL ?>/admin/customization-labels/edit?id=<?= $lbl['id'] ?>" class="inline-flex items-center px-2.5 py-1 text-xs font-bold text-gray-700 bg-gray-100 hover:bg-pink-100 hover:text-pink-700 rounded-lg transition-colors">
                                        Edit
                                    </a>
                                    <?php endif; ?>
                                    <?php if ($this->hasPermission('customization_labels', 'delete')): ?>
                                    <form action="<?= BASE_URL ?>/admin/customization-labels/delete" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this customization label?');">
                                        <input type="hidden" name="id" value="<?= $lbl['id'] ?>">
                                        <button type="submit" class="inline-flex items-center px-2.5 py-1 text-xs font-bold text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer">
                                            Delete
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

</div>
