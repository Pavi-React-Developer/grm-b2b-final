<div class="bg-[#fafafa] min-h-screen flex flex-col md:flex-row relative">

    <?php include __DIR__ . '/_sidebar.php'; ?>

    <div class="flex-1 p-3 xs:p-4 sm:p-5 md:p-6 lg:p-12 relative overflow-y-auto w-full min-w-0">
        <div class="max-w-3xl mx-auto w-full min-w-0">

            <!-- Page Header -->
            <div class="mb-5 sm:mb-8 border-b border-gray-100 pb-3 sm:pb-5 flex flex-row items-center justify-between gap-2">
                <div class="min-w-0">
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-black text-[#1d1d1f] tracking-tight font-display truncate">My Addresses</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5 truncate">Manage your delivery addresses</p>
                </div>
                <button type="button" onclick="openAddModal()" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-white bg-[#F25996] hover:bg-[#d8407d] px-3 py-2 sm:px-4 sm:py-2.5 rounded-xl transition-all shadow-md shadow-pink-900/20 shrink-0 whitespace-nowrap">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Add Address</span>
                </button>
            </div>

            <!-- Flash Messages -->
            <?php if ($flash = \Core\Session::getFlash('success')): ?>
                <div class="mb-5 flex items-center gap-2.5 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-xs sm:text-sm font-medium">
                    <svg class="w-4 h-4 flex-shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    <?= htmlspecialchars($flash) ?>
                </div>
            <?php endif; ?>
            <?php if ($flash = \Core\Session::getFlash('error')): ?>
                <div class="mb-5 flex items-center gap-2.5 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-xs sm:text-sm font-medium">
                    <svg class="w-4 h-4 flex-shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    <?= htmlspecialchars($flash) ?>
                </div>
            <?php endif; ?>

            <!-- Address Cards -->
            <?php if (empty($addresses)): ?>
                <div class="text-center py-16 px-4 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <p class="text-gray-700 font-bold text-base sm:text-lg">No addresses saved yet</p>
                    <p class="text-gray-400 text-xs sm:text-sm mt-0.5 mb-5">Add a delivery address to speed up your checkout</p>
                    <button onclick="openAddModal()" class="inline-flex items-center gap-2 bg-[#F25996] text-white px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm hover:bg-[#d8407d] transition-colors shadow-md">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Add your first address
                    </button>
                </div>
            <?php else: ?>
                <div class="space-y-3.5 sm:space-y-4">
                    <?php foreach ($addresses as $addr): ?>
                        <div class="relative bg-white border-2 <?= $addr['is_default'] ? 'border-[#F25996]' : 'border-gray-100' ?> rounded-2xl p-4 sm:p-6 shadow-sm hover:shadow-md transition-shadow">
                            
                            <!-- Top Header: Label & Default Badge -->
                            <div class="flex items-center justify-between gap-2 mb-2.5">
                                <div class="flex items-center gap-1.5 min-w-0">
                                    <svg class="w-4 h-4 text-[#F25996] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    <span class="text-xs sm:text-sm font-bold text-[#F25996] uppercase tracking-wide truncate">
                                        <?= htmlspecialchars(!empty($addr['label']) ? $addr['label'] : 'Saved Address') ?>
                                    </span>
                                </div>
                                <?php if ($addr['is_default']): ?>
                                    <span class="bg-[#F25996] text-white text-[11px] sm:text-xs font-bold px-2.5 py-0.5 rounded-full shrink-0">Default</span>
                                <?php endif; ?>
                            </div>

                            <!-- Address Details -->
                            <p class="text-gray-900 font-bold text-sm sm:text-base break-words"><?= htmlspecialchars($addr['line1']) ?></p>
                            <?php if (!empty($addr['line2'])): ?>
                                <p class="text-gray-600 text-xs sm:text-sm mt-0.5 break-words"><?= htmlspecialchars($addr['line2']) ?></p>
                            <?php endif; ?>
                            <p class="text-gray-600 text-xs sm:text-sm mt-1">
                                <?= htmlspecialchars($addr['city']) ?>, <?= htmlspecialchars($addr['state']) ?> &ndash; <?= htmlspecialchars($addr['postal_code']) ?>
                            </p>
                            <p class="text-gray-500 text-xs sm:text-sm mt-0.5"><?= htmlspecialchars($addr['country'] ?? 'India') ?></p>

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap items-center justify-between gap-2.5 mt-4 pt-3.5 border-t border-gray-100 text-xs sm:text-sm">
                                <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                                    <button
                                        onclick='openEditModal(<?= htmlspecialchars(json_encode($addr), ENT_QUOTES, 'UTF-8') ?>)'
                                        class="inline-flex items-center gap-1.5 font-bold text-[#F25996] hover:text-[#d8407d] transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                        <span>Edit</span>
                                    </button>

                                    <?php if (!$addr['is_default']): ?>
                                        <form action="<?= BASE_URL ?>/dashboard/addresses/set-default" method="POST" class="inline m-0">
                                            <input type="hidden" name="address_id" value="<?= $addr['id'] ?>">
                                            <button type="submit" class="inline-flex items-center gap-1.5 font-bold text-gray-500 hover:text-gray-800 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                <span>Set as Default</span>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>

                                <div class="flex items-center">
                                    <?php if (!$addr['is_default']): ?>
                                        <form action="<?= BASE_URL ?>/dashboard/addresses/delete" method="POST" class="inline m-0" onsubmit="return confirm('Delete this address?')">
                                            <input type="hidden" name="address_id" value="<?= $addr['id'] ?>">
                                            <button type="submit" class="inline-flex items-center gap-1.5 font-bold text-red-500 hover:text-red-700 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                <span>Delete</span>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="inline-flex items-center text-[11px] sm:text-xs text-gray-500 font-semibold bg-gray-100 px-2.5 py-1 rounded-lg border border-gray-200">
                                            Default
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
</div>

<!-- ======= ADD ADDRESS MODAL ======= -->
<div id="add-address-modal" class="fixed inset-0 z-[100] hidden bg-black/50 backdrop-blur-sm overflow-y-auto" role="dialog" aria-modal="true">
    <div class="min-h-full flex items-center justify-center p-3 sm:p-4">
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg my-4 flex flex-col max-h-[90vh]">
        <div class="flex items-center justify-between px-4 pt-4 pb-3 sm:px-7 sm:pt-7 sm:pb-4 border-b border-gray-100 shrink-0">
            <h2 class="text-base sm:text-xl font-black text-[#1d1d1f]">Add New Address</h2>
            <button onclick="closeAddModal()" class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <form action="<?= BASE_URL ?>/dashboard/addresses/add" method="POST" class="px-4 py-4 sm:px-7 sm:py-6 space-y-4 sm:space-y-5 overflow-y-auto flex-1">
            <div>
                <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">Label <span class="text-gray-400 font-normal">(e.g. Home, Office)</span></label>
                <input type="text" name="label" maxlength="50" placeholder="Home" class="w-full px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm">
            </div>
            <div>
                <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">Address Line 1 <span class="text-red-500">*</span></label>
                <input type="text" name="line1" required maxlength="255" placeholder="House/Flat no., Building, Street" class="w-full px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm">
            </div>
            <div>
                <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">Address Line 2 <span class="text-gray-400 font-normal">(optional)</span></label>
                <input type="text" name="line2" maxlength="255" placeholder="Area, Locality, Landmark" class="w-full px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm">
            </div>
            <div class="grid grid-cols-2 gap-3 sm:gap-4">
                <div>
                    <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">City <span class="text-red-500">*</span></label>
                    <input type="text" name="city" required maxlength="100" placeholder="Mumbai" class="w-full px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm">
                </div>
                <div>
                    <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">State <span class="text-red-500">*</span></label>
                    <input type="text" name="state" required maxlength="100" placeholder="Maharashtra" class="w-full px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3 sm:gap-4">
                <div>
                    <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">Pincode <span class="text-red-500">*</span></label>
                    <input type="text" name="postal_code" required maxlength="10" pattern="\d{6}" title="Enter a valid 6-digit pincode" placeholder="400001" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,6)" class="w-full px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm">
                </div>
                <div>
                    <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">Country</label>
                    <input type="text" name="country" value="India" maxlength="50" class="w-full px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm">
                </div>
            </div>
            <div class="flex items-center gap-2.5 pt-1">
                <input type="checkbox" name="is_default" id="add_is_default" value="1" class="w-4 h-4 rounded border-gray-300 accent-[#F25996]">
                <label for="add_is_default" class="text-xs sm:text-sm font-medium text-gray-700 cursor-pointer">Set as default address</label>
            </div>
            <div class="pt-2 flex flex-col sm:flex-row justify-end gap-2.5 sm:gap-3">
                <button type="button" onclick="closeAddModal()" class="w-full sm:w-auto px-5 py-2.5 rounded-xl font-bold text-gray-500 hover:bg-gray-100 transition-colors text-xs sm:text-sm order-2 sm:order-1">Cancel</button>
                <button type="submit" class="w-full sm:w-auto bg-[#F25996] hover:bg-[#d8407d] text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-pink-900/20 transition-all text-xs sm:text-sm order-1 sm:order-2">Save Address</button>
            </div>
        </form>
    </div>
    </div>
</div>

<!-- ======= EDIT ADDRESS MODAL ======= -->
<div id="edit-address-modal" class="fixed inset-0 z-[100] hidden bg-black/50 backdrop-blur-sm overflow-y-auto" role="dialog" aria-modal="true">
    <div class="min-h-full flex items-center justify-center p-3 sm:p-4">
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg my-4 flex flex-col max-h-[90vh]">
        <div class="flex items-center justify-between px-4 pt-4 pb-3 sm:px-7 sm:pt-7 sm:pb-4 border-b border-gray-100 shrink-0">
            <h2 class="text-base sm:text-xl font-black text-[#1d1d1f]">Edit Address</h2>
            <button onclick="closeEditModal()" class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <form action="<?= BASE_URL ?>/dashboard/addresses/edit" method="POST" class="px-4 py-4 sm:px-7 sm:py-6 space-y-4 sm:space-y-5">
            <input type="hidden" name="address_id" id="edit_address_id">
            <div>
                <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">Label <span class="text-gray-400 font-normal">(e.g. Home, Office)</span></label>
                <input type="text" name="label" id="edit_label" maxlength="50" placeholder="Home" class="w-full px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm">
            </div>
            <div>
                <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">Address Line 1 <span class="text-red-500">*</span></label>
                <input type="text" name="line1" id="edit_line1" required maxlength="255" placeholder="House/Flat no., Building, Street" class="w-full px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm">
            </div>
            <div>
                <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">Address Line 2 <span class="text-gray-400 font-normal">(optional)</span></label>
                <input type="text" name="line2" id="edit_line2" maxlength="255" placeholder="Area, Locality, Landmark" class="w-full px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm">
            </div>
            <div class="grid grid-cols-2 gap-3 sm:gap-4">
                <div>
                    <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">City <span class="text-red-500">*</span></label>
                    <input type="text" name="city" id="edit_city" required maxlength="100" placeholder="Mumbai" class="w-full px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm">
                </div>
                <div>
                    <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">State <span class="text-red-500">*</span></label>
                    <input type="text" name="state" id="edit_state" required maxlength="100" placeholder="Maharashtra" class="w-full px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3 sm:gap-4">
                <div>
                    <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">Pincode <span class="text-red-500">*</span></label>
                    <input type="text" name="postal_code" id="edit_postal_code" required maxlength="10" pattern="\d{6}" title="Enter a valid 6-digit pincode" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,6)" class="w-full px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm">
                </div>
                <div>
                    <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">Country</label>
                    <input type="text" name="country" id="edit_country" maxlength="50" class="w-full px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm">
                </div>
            </div>
            <div class="flex items-center gap-2.5 pt-1">
                <input type="checkbox" name="is_default" id="edit_is_default" value="1" class="w-4 h-4 rounded border-gray-300 accent-[#F25996]">
                <label for="edit_is_default" class="text-xs sm:text-sm font-medium text-gray-700 cursor-pointer">Set as default address</label>
            </div>
            <div class="pt-2 flex flex-col sm:flex-row justify-end gap-2.5 sm:gap-3">
                <button type="button" onclick="closeEditModal()" class="w-full sm:w-auto px-5 py-2.5 rounded-xl font-bold text-gray-500 hover:bg-gray-100 transition-colors text-xs sm:text-sm order-2 sm:order-1">Cancel</button>
                <button type="submit" class="w-full sm:w-auto bg-[#F25996] hover:bg-[#d8407d] text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-pink-900/20 transition-all text-xs sm:text-sm order-1 sm:order-2">Update Address</button>
            </div>
        </form>
    </div>
    </div>
</div>

<script>
function openAddModal() {
    const modal = document.getElementById('add-address-modal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeAddModal() {
    const modal = document.getElementById('add-address-modal');
    modal.classList.add('hidden');
    document.body.style.overflow = '';
}
function openEditModal(addr) {
    document.getElementById('edit_address_id').value = addr.id;
    document.getElementById('edit_label').value = addr.label || '';
    document.getElementById('edit_line1').value = addr.line1 || '';
    document.getElementById('edit_line2').value = addr.line2 || '';
    document.getElementById('edit_city').value = addr.city || '';
    document.getElementById('edit_state').value = addr.state || '';
    document.getElementById('edit_postal_code').value = addr.postal_code || '';
    document.getElementById('edit_country').value = addr.country || 'India';
    document.getElementById('edit_is_default').checked = addr.is_default == 1;

    const modal = document.getElementById('edit-address-modal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeEditModal() {
    const modal = document.getElementById('edit-address-modal');
    modal.classList.add('hidden');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') { closeAddModal(); closeEditModal(); }
});
</script>
