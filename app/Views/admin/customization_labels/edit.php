<?php
/**
 * Admin Edit Customization Label / Template View
 * 
 * @var string $title
 * @var array $label
 * @var array $groups
 */
?>

<div class="max-w-3xl mx-auto px-4 sm:px-6 py-8 space-y-6">

    <!-- Breadcrumb & Header -->
    <div>
        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-pink-600 mb-1">
            <a href="<?= BASE_URL ?>/admin/customization-labels" class="hover:underline">Customization Labels</a>
            <span>/</span>
            <span>Edit Template</span>
        </div>
        <h1 class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
            <span>✏️ Edit Customization Label</span>
        </h1>
        <p class="text-xs text-gray-500 mt-1">
            Update label structure, groupings, or descriptions for <code><?= htmlspecialchars($label['display_label']) ?></code>.
        </p>
    </div>

    <!-- Live Preview Pill Card -->
    <div class="bg-gradient-to-r from-pink-50 via-rose-50 to-pink-50 p-4 rounded-2xl border border-pink-200/80 shadow-2xs">
        <div class="text-[11px] font-bold uppercase tracking-wider text-pink-700 mb-1.5 flex items-center gap-1.5">
            <span>✨ Live Template Preview</span>
            <span class="text-[10px] px-1.5 py-0.2 rounded bg-white text-pink-600 font-bold border border-pink-200">Storefront View</span>
        </div>
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white text-gray-900 font-bold text-sm shadow-xs border border-pink-200" id="livePreviewContainer">
            <span class="text-base">🏷️</span>
            <span id="previewDisplayText" class="text-pink-900 font-extrabold"><?= htmlspecialchars($label['display_label']) ?></span>
        </div>
    </div>

    <!-- Form -->
    <form action="<?= BASE_URL ?>/admin/customization-labels/update" method="POST" class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-100 shadow-xs space-y-5">
        <input type="hidden" name="id" value="<?= $label['id'] ?>">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- Group Name -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    Primary Group / Category <span class="text-pink-500">*</span>
                </label>
                <input type="text" name="group_name" id="groupInput" list="existingGroupsList" value="<?= htmlspecialchars($label['group_name'] ?? '') ?>" required placeholder="e.g. Maternity, Nightwear, Formal" oninput="updateLivePreview()" class="w-full px-4 py-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 font-medium">
                <datalist id="existingGroupsList">
                    <?php foreach ($groups as $g): ?>
                        <option value="<?= htmlspecialchars($g) ?>">
                    <?php endforeach; ?>
                </datalist>
                <p class="text-[10px] text-gray-400 mt-1">Level 1 grouping (e.g. Maternity, Shirts)</p>
            </div>

            <!-- Sub Group -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    Sub-Group / Feature Type
                </label>
                <input type="text" name="sub_group" id="subGroupInput" value="<?= htmlspecialchars($label['sub_group'] ?? '') ?>" placeholder="e.g. Feeding, Non-Feeding, Front Open" oninput="updateLivePreview()" class="w-full px-4 py-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 font-medium">
                <p class="text-[10px] text-gray-400 mt-1">Level 2 grouping (e.g. Feeding, Regular Fit)</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- Style / Label Name -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    Specific Style / Label Name <span class="text-pink-500">*</span>
                </label>
                <input type="text" name="name" id="styleNameInput" value="<?= htmlspecialchars($label['name'] ?? '') ?>" required placeholder="e.g. Full Sleeve, 3/4th Sleeve, V-Neck" oninput="updateLivePreview()" class="w-full px-4 py-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 font-medium">
                <p class="text-[10px] text-gray-400 mt-1">Level 3 variant / style tag name</p>
            </div>

            <!-- Custom Full Display Name (Optional override) -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    Display Label (Override if needed)
                </label>
                <input type="text" name="display_label" id="displayLabelInput" value="<?= htmlspecialchars($label['display_label'] ?? '') ?>" placeholder="Auto: Group -> Sub-Group -> Style" class="w-full px-4 py-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 font-medium">
            </div>
        </div>

        <!-- Description -->
        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                Description / Tailoring Specifications (Optional)
            </label>
            <textarea name="description" rows="2" placeholder="e.g. Includes concealed 12-inch nursing zips on both sides with full sleeve coverage..." class="w-full px-4 py-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 font-medium"><?= htmlspecialchars($label['description'] ?? '') ?></textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2 border-t border-gray-100">
            <!-- Sort Order -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    Sort Order
                </label>
                <input type="number" name="sort_order" value="<?= (int)$label['sort_order'] ?>" min="0" class="w-full px-4 py-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 font-medium">
            </div>

            <!-- Status -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    Status
                </label>
                <select name="status" class="w-full px-4 py-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 font-medium">
                    <option value="active" <?= $label['status'] === 'active' ? 'selected' : '' ?>>Active (Available for fabric assignment)</option>
                    <option value="inactive" <?= $label['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
        </div>

        <!-- Submit Actions -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
            <a href="<?= BASE_URL ?>/admin/customization-labels" class="px-5 py-2.5 bg-gray-100 text-gray-700 hover:bg-gray-200 text-xs font-bold rounded-xl transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-pink-500 to-rose-600 hover:from-pink-600 hover:to-rose-700 text-white text-xs font-bold rounded-xl shadow-sm hover:shadow transition-all">
                Update Label Template
            </button>
        </div>

    </form>

</div>

<script>
function updateLivePreview() {
    const grp = document.getElementById('groupInput').value.trim();
    const sub = document.getElementById('subGroupInput').value.trim();
    const name = document.getElementById('styleNameInput').value.trim();
    
    const parts = [grp, sub, name].filter(Boolean);
    const result = parts.length > 0 ? parts.join(' -> ') : 'Group -> Sub-Group -> Style Name';
    
    document.getElementById('previewDisplayText').innerText = result;
}
</script>
