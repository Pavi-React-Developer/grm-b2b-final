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

    <!-- Breadcrumbs -->
    <div class="text-sm font-medium text-gray-400 font-serif tracking-wide">
        Dashboard <span class="mx-1 text-gray-300">›</span> Customization Management <span class="mx-1 text-gray-300">›</span> <span class="text-[#F25996]">Customization Labels & Templates</span>
    </div>

    <!-- Top Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl sm:text-4xl font-display font-extrabold text-gray-900 tracking-tight flex items-center gap-3">
                <span>🏷️ Customization Labels & Templates</span>
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Define hierarchical style tags (e.g. <code>Maternity -&gt; Feeding -&gt; Full Sleeve</code>) that can be dynamically assigned to fabrics and customized with user quantities.
            </p>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            <a href="<?= BASE_URL ?>/admin/catalog/products?module=customize" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 text-xs font-bold rounded-full shadow-xs uppercase tracking-wider transition-all">
                <svg class="w-4 h-4 text-[#F25996]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <span>Customizable Fabrics</span>
            </a>
            <?php if ($this->hasPermission('customization_labels', 'create')): ?>
            <a href="<?= BASE_URL ?>/admin/customization-labels/create" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#F25996] hover:bg-[#d8407d] text-white text-xs font-bold rounded-full shadow-md uppercase tracking-widest transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>+ Add New Label</span>
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Labels</span>
                <div class="text-2xl font-black text-gray-900 mt-1"><?= $totalLabels ?></div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-pink-50 text-[#F25996] flex items-center justify-center font-bold text-lg border border-pink-100">
                🏷️
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Active Templates</span>
                <div class="text-2xl font-black text-emerald-600 mt-1"><?= $activeLabels ?></div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg border border-emerald-100">
                ✓
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Style Categories</span>
                <div class="text-2xl font-black text-indigo-600 mt-1"><?= count($groups) ?></div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg border border-indigo-100">
                📁
            </div>
        </div>
    </div>

    <!-- Status Tabs (Image 5 style) -->
    <div class="flex items-center space-x-2 overflow-x-auto mb-2 bg-white/70 backdrop-blur p-1.5 rounded-full border border-gray-100 shadow-sm w-fit">
        <a href="<?= BASE_URL ?>/admin/customization-labels<?= !empty($filters['search']) ? '?search=' . urlencode($filters['search']) : '' ?>" class="px-4 py-2 rounded-full text-xs font-bold transition-all flex items-center gap-1.5 whitespace-nowrap <?= empty($filters['status']) ? 'bg-[#F25996] text-white shadow-sm shadow-[#F25996]/30' : 'text-gray-600 hover:bg-gray-100' ?>">
            <span>🌐 All Labels</span>
        </a>
        <a href="<?= BASE_URL ?>/admin/customization-labels?status=active<?= !empty($filters['search']) ? '&search=' . urlencode($filters['search']) : '' ?>" class="px-4 py-2 rounded-full text-xs font-bold transition-all flex items-center gap-1.5 whitespace-nowrap <?= ($filters['status'] ?? '') === 'active' ? 'bg-[#F25996] text-white shadow-sm shadow-[#F25996]/30' : 'text-gray-600 hover:bg-gray-100' ?>">
            <span>✅ Active Only</span>
        </a>
        <a href="<?= BASE_URL ?>/admin/customization-labels?status=inactive<?= !empty($filters['search']) ? '&search=' . urlencode($filters['search']) : '' ?>" class="px-4 py-2 rounded-full text-xs font-bold transition-all flex items-center gap-1.5 whitespace-nowrap <?= ($filters['status'] ?? '') === 'inactive' ? 'bg-[#F25996] text-white shadow-sm shadow-[#F25996]/30' : 'text-gray-600 hover:bg-gray-100' ?>">
            <span>⏸️ Inactive Only</span>
        </a>
    </div>

    <!-- Filters & Search Bar (Image 5 style) -->
    <div class="bg-white rounded-full shadow-sm border border-gray-100 p-1.5 flex items-center">
        <form action="<?= BASE_URL ?>/admin/customization-labels" method="GET" class="w-full flex flex-col sm:flex-row items-center gap-2 pl-4 pr-1">
            <?php if (!empty($filters['status'])): ?>
                <input type="hidden" name="status" value="<?= htmlspecialchars($filters['status']) ?>">
            <?php endif; ?>
            <div class="flex-1 flex items-center gap-2 w-full">
                <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="<?= htmlspecialchars($filters['search'] ?? '') ?>" placeholder="Search by label name, group, or keyword..." class="w-full text-xs border-0 focus:ring-0 text-gray-900 placeholder-gray-400 bg-transparent py-2 focus:outline-none font-medium">
            </div>

            <div class="sm:w-44 shrink-0">
                <select name="group_name" onchange="this.form.submit()" class="w-full px-3 py-1.5 text-xs bg-gray-50 border border-gray-200 rounded-full focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#F25996]/20 font-medium">
                    <option value="">All Groups</option>
                    <?php foreach ($groups as $grp): ?>
                        <option value="<?= htmlspecialchars($grp) ?>" <?= ($filters['group_name'] ?? '') === $grp ? 'selected' : '' ?>>
                            <?= htmlspecialchars($grp) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="bg-[#F25996] hover:bg-[#d8407d] text-white px-5 py-2 rounded-full font-bold text-xs uppercase tracking-wider transition-colors shrink-0">
                Filter
            </button>
            <?php if (!empty($filters['search']) || !empty($filters['group_name']) || !empty($filters['status'])): ?>
                <a href="<?= BASE_URL ?>/admin/customization-labels" class="px-4 py-2 text-xs font-bold text-gray-500 hover:text-gray-700 rounded-full hover:bg-gray-100 transition-colors shrink-0">
                    Clear
                </a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Labels Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <?php if (empty($labels)): ?>
            <div class="p-12 text-center">
                <div class="w-14 h-14 bg-pink-50 text-[#F25996] rounded-2xl flex items-center justify-center mx-auto mb-3 text-2xl font-bold border border-pink-100">
                    🏷️
                </div>
                <h3 class="text-base font-bold text-gray-900 mb-1">No Customization Labels Found</h3>
                <p class="text-xs text-gray-500 max-w-sm mx-auto mb-4">
                    Create templates like <code>Maternity -&gt; Feeding -&gt; Full Sleeve</code> to enable granular quantity tailoring.
                </p>
                <?php if ($this->hasPermission('customization_labels', 'create')): ?>
                <a href="<?= BASE_URL ?>/admin/customization-labels/create" class="inline-flex items-center gap-1.5 px-5 py-2 bg-[#F25996] text-white text-xs font-bold rounded-full hover:bg-[#d8407d] uppercase tracking-wider transition-colors shadow-sm">
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
                            <tr class="hover:bg-pink-50/20 transition-colors">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-pink-50 text-[#F25996] text-xs font-bold border border-pink-200">
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
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-gray-100 text-gray-700">
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
                                        <button type="submit" class="px-3 py-1 rounded-full text-[10px] font-bold transition-colors cursor-pointer <?= $lbl['status'] === 'active' ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">
                                            <?= $lbl['status'] === 'active' ? '● Active' : '○ Inactive' ?>
                                        </button>
                                    </form>
                                    <?php else: ?>
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold <?= $lbl['status'] === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' ?>">
                                        <?= $lbl['status'] === 'active' ? '● Active' : '○ Inactive' ?>
                                    </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-5 py-3.5 text-right space-x-2">
                                    <?php if ($this->hasPermission('customization_labels', 'edit')): ?>
                                    <a href="<?= BASE_URL ?>/admin/customization-labels/edit?id=<?= $lbl['id'] ?>" class="inline-flex items-center px-3 py-1 text-xs font-bold text-[#F25996] bg-pink-50 hover:bg-[#F25996] hover:text-white rounded-full border border-pink-200 transition-all">
                                        Edit
                                    </a>
                                    <?php endif; ?>
                                    <?php if ($this->hasPermission('customization_labels', 'delete')): ?>
                                    <form action="<?= BASE_URL ?>/admin/customization-labels/delete" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this customization label?');">
                                        <input type="hidden" name="id" value="<?= $lbl['id'] ?>">
                                        <button type="submit" class="inline-flex items-center px-3 py-1 text-xs font-bold text-rose-600 hover:bg-rose-50 rounded-full transition-colors cursor-pointer">
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
