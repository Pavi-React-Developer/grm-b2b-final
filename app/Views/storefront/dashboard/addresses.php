<style>
    /* ── Custom Pink Select (Address Forms) ── */
    .reg-cs-wrapper {
        position: relative;
        width: 100%;
    }
    .reg-cs-trigger {
        width: 100%;
        border-radius: 0.75rem;
        border: 1px solid #e5e7eb;
        background-color: #ffffff;
        padding: 0.625rem 0.875rem;
        font-size: 0.875rem;
        color: #111827;
        cursor: pointer;
        text-align: left;
        transition: all 0.2s ease-in-out;
        outline: none;
        display: flex;
        align-items: center;
        justify-content: space-between;
        user-select: none;
        font-weight: 500;
    }
    .reg-cs-trigger.placeholder { color: #9ca3af; font-weight: 400; }
    .reg-cs-trigger:focus,
    .reg-cs-trigger.open {
        border-color: #F25996 !important;
        box-shadow: 0 0 0 3px rgba(242, 89, 150, 0.2) !important;
    }
    .reg-cs-trigger.error {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2) !important;
    }
    .reg-cs-arrow {
        flex-shrink: 0;
        width: 16px;
        height: 16px;
        color: #9ca3af;
        transition: transform 0.2s ease;
        pointer-events: none;
    }
    .reg-cs-trigger.open .reg-cs-arrow { transform: rotate(180deg); color: #F25996; }
    .reg-cs-list {
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        right: 0;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        z-index: 50;
        max-height: 200px;
        overflow-y: auto;
        display: none;
        padding: 4px;
    }
    .reg-cs-list.open { display: block; }
    .reg-cs-list::-webkit-scrollbar { width: 4px; }
    .reg-cs-list::-webkit-scrollbar-track { background: transparent; }
    .reg-cs-list::-webkit-scrollbar-thumb { background: #F25996; border-radius: 4px; }
    .reg-cs-option {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 0.5rem;
        cursor: pointer;
        color: #374151;
        transition: background 0.15s, color 0.15s;
    }
    .reg-cs-option:hover {
        background: #fce7f3;
        color: #F25996;
    }
    .reg-cs-option.selected {
        background: #F25996;
        color: #ffffff;
        font-weight: 600;
    }
    .reg-cs-option.disabled {
        color: #9ca3af;
        cursor: default;
        pointer-events: none;
    }
</style>

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
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg my-4 flex flex-col max-h-[85vh] overflow-hidden">
            <!-- Modal Header (Fixed) -->
            <div class="flex items-center justify-between px-4 pt-4 pb-3 sm:px-7 sm:pt-6 sm:pb-4 border-b border-gray-100 shrink-0 bg-white">
                <h2 class="text-base sm:text-xl font-black text-[#1d1d1f]">Add New Address</h2>
                <button type="button" onclick="closeAddModal()" class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors text-gray-400 hover:text-gray-600 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            
            <form action="<?= BASE_URL ?>/dashboard/addresses/add" method="POST" class="flex flex-col flex-1 min-h-0">
                <!-- Scrollable Form Body -->
                <div class="px-4 py-4 sm:px-7 sm:py-6 space-y-4 sm:space-y-5 overflow-y-auto flex-1 overscroll-contain">
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">Label <span class="text-gray-400 font-normal">(e.g. Home, Office, Shop)</span></label>
                        <input type="text" name="label" maxlength="50" placeholder="Shop Address" class="w-full px-3.5 py-2.5 sm:px-4 sm:py-2.5 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">Address Line 1 <span class="text-red-500">*</span></label>
                        <input type="text" name="line1" required maxlength="255" placeholder="House/Flat no., Building, Street" class="w-full px-3.5 py-2.5 sm:px-4 sm:py-2.5 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">Address Line 2 <span class="text-gray-400 font-normal">(optional)</span></label>
                        <input type="text" name="line2" maxlength="255" placeholder="Area, Locality, Landmark" class="w-full px-3.5 py-2.5 sm:px-4 sm:py-2.5 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm">
                    </div>
                    
                    <!-- State & District / City (Registration Form Style) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">State <span class="text-red-500">*</span></label>
                            <select id="add_state" name="state" required onchange="toggleAddDistrictFields()" class="w-full px-3.5 py-2.5 sm:px-4 sm:py-2.5 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm bg-white font-medium text-gray-900">
                                <option value="">Select State</option>
                                <option value="Tamil Nadu">Tamil Nadu</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                        <div id="add_district_container">
                            <label id="add_district_label" class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">District / City <span class="text-red-500">*</span></label>
                            
                            <!-- Dropdown for Tamil Nadu -->
                            <select id="add_district_select" name="city" required class="w-full px-3.5 py-2.5 sm:px-4 sm:py-2.5 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm bg-white font-medium text-gray-900 hidden">
                                <option value="">Select District</option>
                                <option value="Ariyalur">Ariyalur</option>
                                <option value="Chengalpattu">Chengalpattu</option>
                                <option value="Chennai">Chennai</option>
                                <option value="Coimbatore">Coimbatore</option>
                                <option value="Cuddalore">Cuddalore</option>
                                <option value="Dharmapuri">Dharmapuri</option>
                                <option value="Dindigul">Dindigul</option>
                                <option value="Erode">Erode</option>
                                <option value="Kallakurichi">Kallakurichi</option>
                                <option value="Kanchipuram">Kanchipuram</option>
                                <option value="Kanyakumari">Kanyakumari</option>
                                <option value="Karur">Karur</option>
                                <option value="Krishnagiri">Krishnagiri</option>
                                <option value="Madurai">Madurai</option>
                                <option value="Mayiladuthurai">Mayiladuthurai</option>
                                <option value="Nagapattinam">Nagapattinam</option>
                                <option value="Namakkal">Namakkal</option>
                                <option value="Nilgiris">Nilgiris</option>
                                <option value="Perambalur">Perambalur</option>
                                <option value="Pudukkottai">Pudukkottai</option>
                                <option value="Ramanathapuram">Ramanathapuram</option>
                                <option value="Ranipet">Ranipet</option>
                                <option value="Salem">Salem</option>
                                <option value="Sivaganga">Sivaganga</option>
                                <option value="Tenkasi">Tenkasi</option>
                                <option value="Thanjavur">Thanjavur</option>
                                <option value="Theni">Theni</option>
                                <option value="Thoothukudi">Thoothukudi</option>
                                <option value="Tiruchirappalli">Tiruchirappalli (Trichy)</option>
                                <option value="Tirunelveli">Tirunelveli</option>
                                <option value="Tirupathur">Tirupathur</option>
                                <option value="Tiruppur">Tiruppur</option>
                                <option value="Tiruvallur">Tiruvallur</option>
                                <option value="Tiruvannamalai">Tiruvannamalai</option>
                                <option value="Tiruvarur">Tiruvarur</option>
                                <option value="Vellore">Vellore</option>
                                <option value="Viluppuram">Viluppuram</option>
                                <option value="Virudhunagar">Virudhunagar</option>
                            </select>
                            
                            <!-- Text Input for Others -->
                            <input id="add_district_text" name="city" type="text" pattern="[A-Za-z\s]+" oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'')" placeholder="Enter District / City Name" autocomplete="off" class="w-full px-3.5 py-2.5 sm:px-4 sm:py-2.5 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm bg-white font-medium text-gray-900 hidden" disabled>
                        </div>
                        <div id="add_other_state_container" class="hidden sm:col-span-2">
                            <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">State Name <span class="text-red-500">*</span></label>
                            <input id="add_other_state_name" name="other_state_name" type="text" pattern="[A-Za-z\s]+" oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'')" placeholder="Enter state name (e.g. Kerala, Karnataka)" autocomplete="off" class="w-full px-3.5 py-2.5 sm:px-4 sm:py-2.5 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm bg-white font-medium text-gray-900" disabled>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">Pincode <span class="text-red-500">*</span></label>
                            <input type="text" name="postal_code" required maxlength="10" pattern="\d{6}" title="Enter a valid 6-digit pincode" placeholder="600001" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,6)" class="w-full px-3.5 py-2.5 sm:px-4 sm:py-2.5 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">Country</label>
                            <input type="text" name="country" value="India" maxlength="50" class="w-full px-3.5 py-2.5 sm:px-4 sm:py-2.5 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm">
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5 pt-1">
                        <input type="checkbox" name="is_default" id="add_is_default" value="1" class="w-4 h-4 rounded border-gray-300 accent-[#F25996]">
                        <label for="add_is_default" class="text-xs sm:text-sm font-medium text-gray-700 cursor-pointer">Set as default address</label>
                    </div>
                </div>

                <!-- Modal Footer (Fixed at bottom) -->
                <div class="px-4 py-3 sm:px-7 sm:py-4 border-t border-gray-100 flex flex-col sm:flex-row justify-end gap-2.5 sm:gap-3 shrink-0 bg-gray-50/50 rounded-b-2xl">
                    <button type="button" onclick="closeAddModal()" class="w-full sm:w-auto px-5 py-2.5 rounded-xl font-bold text-gray-500 hover:bg-gray-100 transition-colors text-xs sm:text-sm order-2 sm:order-1 cursor-pointer">Cancel</button>
                    <button type="submit" class="w-full sm:w-auto bg-[#F25996] hover:bg-[#d8407d] text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-pink-900/20 transition-all text-xs sm:text-sm order-1 sm:order-2 cursor-pointer">Save Address</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======= EDIT ADDRESS MODAL ======= -->
<div id="edit-address-modal" class="fixed inset-0 z-[100] hidden bg-black/50 backdrop-blur-sm overflow-y-auto" role="dialog" aria-modal="true">
    <div class="min-h-full flex items-center justify-center p-3 sm:p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg my-4 flex flex-col max-h-[85vh] overflow-hidden">
            <!-- Modal Header (Fixed) -->
            <div class="flex items-center justify-between px-4 pt-4 pb-3 sm:px-7 sm:pt-6 sm:pb-4 border-b border-gray-100 shrink-0 bg-white">
                <h2 class="text-base sm:text-xl font-black text-[#1d1d1f]">Edit Address</h2>
                <button type="button" onclick="closeEditModal()" class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors text-gray-400 hover:text-gray-600 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            
            <form action="<?= BASE_URL ?>/dashboard/addresses/edit" method="POST" class="flex flex-col flex-1 min-h-0">
                <input type="hidden" name="address_id" id="edit_address_id">
                
                <!-- Scrollable Form Body -->
                <div class="px-4 py-4 sm:px-7 sm:py-6 space-y-4 sm:space-y-5 overflow-y-auto flex-1 overscroll-contain">
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">Label <span class="text-gray-400 font-normal">(e.g. Home, Office, Shop)</span></label>
                        <input type="text" name="label" id="edit_label" maxlength="50" placeholder="Shop Address" class="w-full px-3.5 py-2.5 sm:px-4 sm:py-2.5 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">Address Line 1 <span class="text-red-500">*</span></label>
                        <input type="text" name="line1" id="edit_line1" required maxlength="255" placeholder="House/Flat no., Building, Street" class="w-full px-3.5 py-2.5 sm:px-4 sm:py-2.5 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">Address Line 2 <span class="text-gray-400 font-normal">(optional)</span></label>
                        <input type="text" name="line2" id="edit_line2" maxlength="255" placeholder="Area, Locality, Landmark" class="w-full px-3.5 py-2.5 sm:px-4 sm:py-2.5 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm">
                    </div>
                    
                    <!-- State & District / City (Registration Form Style) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">State <span class="text-red-500">*</span></label>
                            <select id="edit_state" name="state" required onchange="toggleEditDistrictFields()" class="w-full px-3.5 py-2.5 sm:px-4 sm:py-2.5 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm bg-white font-medium text-gray-900">
                                <option value="">Select State</option>
                                <option value="Tamil Nadu">Tamil Nadu</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                        <div id="edit_district_container">
                            <label id="edit_district_label" class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">District / City <span class="text-red-500">*</span></label>
                            
                            <!-- Dropdown for Tamil Nadu -->
                            <select id="edit_district_select" name="city" required class="w-full px-3.5 py-2.5 sm:px-4 sm:py-2.5 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm bg-white font-medium text-gray-900 hidden">
                                <option value="">Select District</option>
                                <option value="Ariyalur">Ariyalur</option>
                                <option value="Chengalpattu">Chengalpattu</option>
                                <option value="Chennai">Chennai</option>
                                <option value="Coimbatore">Coimbatore</option>
                                <option value="Cuddalore">Cuddalore</option>
                                <option value="Dharmapuri">Dharmapuri</option>
                                <option value="Dindigul">Dindigul</option>
                                <option value="Erode">Erode</option>
                                <option value="Kallakurichi">Kallakurichi</option>
                                <option value="Kanchipuram">Kanchipuram</option>
                                <option value="Kanyakumari">Kanyakumari</option>
                                <option value="Karur">Karur</option>
                                <option value="Krishnagiri">Krishnagiri</option>
                                <option value="Madurai">Madurai</option>
                                <option value="Mayiladuthurai">Mayiladuthurai</option>
                                <option value="Nagapattinam">Nagapattinam</option>
                                <option value="Namakkal">Namakkal</option>
                                <option value="Nilgiris">Nilgiris</option>
                                <option value="Perambalur">Perambalur</option>
                                <option value="Pudukkottai">Pudukkottai</option>
                                <option value="Ramanathapuram">Ramanathapuram</option>
                                <option value="Ranipet">Ranipet</option>
                                <option value="Salem">Salem</option>
                                <option value="Sivaganga">Sivaganga</option>
                                <option value="Tenkasi">Tenkasi</option>
                                <option value="Thanjavur">Thanjavur</option>
                                <option value="Theni">Theni</option>
                                <option value="Thoothukudi">Thoothukudi</option>
                                <option value="Tiruchirappalli">Tiruchirappalli (Trichy)</option>
                                <option value="Tirunelveli">Tirunelveli</option>
                                <option value="Tirupathur">Tirupathur</option>
                                <option value="Tiruppur">Tiruppur</option>
                                <option value="Tiruvallur">Tiruvallur</option>
                                <option value="Tiruvannamalai">Tiruvannamalai</option>
                                <option value="Tiruvarur">Tiruvarur</option>
                                <option value="Vellore">Vellore</option>
                                <option value="Viluppuram">Viluppuram</option>
                                <option value="Virudhunagar">Virudhunagar</option>
                            </select>
                            
                            <!-- Text Input for Others -->
                            <input id="edit_district_text" name="city" type="text" pattern="[A-Za-z\s]+" oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'')" placeholder="Enter District / City Name" autocomplete="off" class="w-full px-3.5 py-2.5 sm:px-4 sm:py-2.5 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm bg-white font-medium text-gray-900 hidden" disabled>
                        </div>
                        <div id="edit_other_state_container" class="hidden sm:col-span-2">
                            <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">State Name <span class="text-red-500">*</span></label>
                            <input id="edit_other_state_name" name="other_state_name" type="text" pattern="[A-Za-z\s]+" oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'')" placeholder="Enter state name (e.g. Kerala, Karnataka)" autocomplete="off" class="w-full px-3.5 py-2.5 sm:px-4 sm:py-2.5 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm bg-white font-medium text-gray-900" disabled>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">Pincode <span class="text-red-500">*</span></label>
                            <input type="text" name="postal_code" id="edit_postal_code" required maxlength="10" pattern="\d{6}" title="Enter a valid 6-digit pincode" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,6)" class="w-full px-3.5 py-2.5 sm:px-4 sm:py-2.5 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">Country</label>
                            <input type="text" name="country" id="edit_country" maxlength="50" class="w-full px-3.5 py-2.5 sm:px-4 sm:py-2.5 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] outline-none transition-colors text-xs sm:text-sm">
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5 pt-1">
                        <input type="checkbox" name="is_default" id="edit_is_default" value="1" class="w-4 h-4 rounded border-gray-300 accent-[#F25996]">
                        <label for="edit_is_default" class="text-xs sm:text-sm font-medium text-gray-700 cursor-pointer">Set as default address</label>
                    </div>
                </div>

                <!-- Modal Footer (Fixed at bottom) -->
                <div class="px-4 py-3 sm:px-7 sm:py-4 border-t border-gray-100 flex flex-col sm:flex-row justify-end gap-2.5 sm:gap-3 shrink-0 bg-gray-50/50 rounded-b-2xl">
                    <button type="button" onclick="closeEditModal()" class="w-full sm:w-auto px-5 py-2.5 rounded-xl font-bold text-gray-500 hover:bg-gray-100 transition-colors text-xs sm:text-sm order-2 sm:order-1 cursor-pointer">Cancel</button>
                    <button type="submit" class="w-full sm:w-auto bg-[#F25996] hover:bg-[#d8407d] text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-pink-900/20 transition-all text-xs sm:text-sm order-1 sm:order-2 cursor-pointer">Update Address</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const TN_DISTRICTS = [
    "Ariyalur", "Chengalpattu", "Chennai", "Coimbatore", "Cuddalore", "Dharmapuri",
    "Dindigul", "Erode", "Kallakurichi", "Kanchipuram", "Kanyakumari", "Karur",
    "Krishnagiri", "Madurai", "Mayiladuthurai", "Nagapattinam", "Namakkal", "Nilgiris",
    "Perambalur", "Pudukkottai", "Ramanathapuram", "Ranipet", "Salem", "Sivaganga",
    "Tenkasi", "Thanjavur", "Theni", "Thoothukudi", "Tiruchirappalli", "Tirunelveli",
    "Tirupathur", "Tiruppur", "Tiruvallur", "Tiruvannamalai", "Tiruvarur", "Vellore",
    "Viluppuram", "Virudhunagar"
];

/* ─────────────────────────────────────────────────
   Custom Pink Select for Address Modals
───────────────────────────────────────────────── */
function initAddrCustomSelect(sel) {
    if (!sel || sel.dataset.addrCsInit) return;
    sel.dataset.addrCsInit = '1';

    sel.style.display = 'none';

    var wrapper = document.createElement('div');
    wrapper.className = 'reg-cs-wrapper';
    if (sel.classList.contains('hidden')) wrapper.classList.add('hidden');
    sel.parentNode.insertBefore(wrapper, sel);
    wrapper.appendChild(sel);

    var trigger = document.createElement('button');
    trigger.type = 'button';
    trigger.className = 'reg-cs-trigger placeholder';

    var label = document.createElement('span');
    label.className = 'reg-cs-label truncate';
    label.textContent = sel.options[0] ? sel.options[0].text : 'Select';
    trigger.appendChild(label);

    var arrow = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    arrow.setAttribute('class', 'reg-cs-arrow');
    arrow.setAttribute('viewBox', '0 0 20 20');
    arrow.setAttribute('fill', 'none');
    arrow.setAttribute('stroke', 'currentColor');
    arrow.setAttribute('stroke-width', '2');
    var path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    path.setAttribute('stroke-linecap', 'round');
    path.setAttribute('stroke-linejoin', 'round');
    path.setAttribute('d', 'M5 7l5 5 5-5');
    arrow.appendChild(path);
    trigger.appendChild(arrow);
    wrapper.appendChild(trigger);

    var list = document.createElement('div');
    list.className = 'reg-cs-list';
    wrapper.appendChild(list);

    function buildOptions() {
        list.innerHTML = '';
        Array.from(sel.options).forEach(function(opt) {
            var item = document.createElement('div');
            item.className = 'reg-cs-option' +
                (opt.value === '' ? ' disabled' : '') +
                (opt.selected || sel.value === opt.value ? ' selected' : '');
            item.textContent = opt.text;
            if (opt.value !== '') {
                item.addEventListener('click', function(e) {
                    e.stopPropagation();
                    sel.value = opt.value;
                    label.textContent = opt.text;
                    trigger.classList.remove('placeholder');
                    list.querySelectorAll('.reg-cs-option').forEach(function(o) { o.classList.remove('selected'); });
                    item.classList.add('selected');
                    closeList();
                    sel.dispatchEvent(new Event('change', { bubbles: true }));
                });
            }
            list.appendChild(item);
        });
    }

    function syncTrigger() {
        var cur = sel.options[sel.selectedIndex];
        if (cur && cur.value !== '') {
            label.textContent = cur.text;
            trigger.classList.remove('placeholder');
        } else {
            label.textContent = sel.options[0] ? sel.options[0].text : 'Select';
            trigger.classList.add('placeholder');
        }
        list.querySelectorAll('.reg-cs-option').forEach(function(o) {
            if (cur && o.textContent.trim() === cur.text.trim() && cur.value !== '') {
                o.classList.add('selected');
            } else {
                o.classList.remove('selected');
            }
        });
    }

    sel.refreshCustomSelect = function() {
        buildOptions();
        syncTrigger();
        if (sel.classList.contains('hidden')) {
            wrapper.classList.add('hidden');
        } else {
            wrapper.classList.remove('hidden');
        }
    };

    syncTrigger();
    buildOptions();

    function openList() {
        document.querySelectorAll('.reg-cs-trigger.open').forEach(function(t) {
            if (t !== trigger) {
                t.classList.remove('open');
                var sib = t.parentElement && t.parentElement.querySelector('.reg-cs-list');
                if (sib) sib.classList.remove('open');
            }
        });
        trigger.classList.add('open');
        list.classList.add('open');
    }
    function closeList() {
        trigger.classList.remove('open');
        list.classList.remove('open');
    }

    trigger.addEventListener('click', function(e) {
        e.stopPropagation();
        list.classList.contains('open') ? closeList() : openList();
    });

    new MutationObserver(function() {
        if (sel.classList.contains('hidden')) {
            wrapper.classList.add('hidden');
        } else {
            wrapper.classList.remove('hidden');
        }
        buildOptions();
        syncTrigger();
    }).observe(sel, { attributes: true, attributeFilter: ['class'], childList: true, subtree: true });

    sel.addEventListener('change', syncTrigger);
}

function toggleAddDistrictFields() {
    const stateVal = document.getElementById('add_state').value;
    const selectEl = document.getElementById('add_district_select');
    const textEl   = document.getElementById('add_district_text');
    const otherContainer = document.getElementById('add_other_state_container');
    const otherInput = document.getElementById('add_other_state_name');

    if (stateVal === 'Tamil Nadu') {
        selectEl.classList.remove('hidden');
        selectEl.disabled = false;
        selectEl.required = true;

        textEl.classList.add('hidden');
        textEl.disabled = true;
        textEl.required = false;

        if (otherContainer) otherContainer.classList.add('hidden');
        if (otherInput) { otherInput.disabled = true; otherInput.required = false; }
    } else if (stateVal === 'Others') {
        textEl.classList.remove('hidden');
        textEl.disabled = false;
        textEl.required = true;
        textEl.placeholder = "Enter District / City Name";

        selectEl.classList.add('hidden');
        selectEl.disabled = true;
        selectEl.required = false;

        if (otherContainer) otherContainer.classList.remove('hidden');
        if (otherInput) { otherInput.disabled = false; otherInput.required = true; }
    } else {
        selectEl.classList.add('hidden');
        selectEl.disabled = true;
        selectEl.required = false;

        textEl.classList.remove('hidden');
        textEl.disabled = true;
        textEl.required = false;
        textEl.placeholder = "Select State first";

        if (otherContainer) otherContainer.classList.add('hidden');
        if (otherInput) { otherInput.disabled = true; otherInput.required = false; }
    }

    if (selectEl.refreshCustomSelect) selectEl.refreshCustomSelect();
}

function toggleEditDistrictFields() {
    const stateVal = document.getElementById('edit_state').value;
    const selectEl = document.getElementById('edit_district_select');
    const textEl   = document.getElementById('edit_district_text');
    const otherContainer = document.getElementById('edit_other_state_container');
    const otherInput = document.getElementById('edit_other_state_name');

    if (stateVal === 'Tamil Nadu') {
        selectEl.classList.remove('hidden');
        selectEl.disabled = false;
        selectEl.required = true;

        textEl.classList.add('hidden');
        textEl.disabled = true;
        textEl.required = false;

        if (otherContainer) otherContainer.classList.add('hidden');
        if (otherInput) { otherInput.disabled = true; otherInput.required = false; }
    } else if (stateVal === 'Others') {
        textEl.classList.remove('hidden');
        textEl.disabled = false;
        textEl.required = true;
        textEl.placeholder = "Enter District / City Name";

        selectEl.classList.add('hidden');
        selectEl.disabled = true;
        selectEl.required = false;

        if (otherContainer) otherContainer.classList.remove('hidden');
        if (otherInput) { otherInput.disabled = false; otherInput.required = true; }
    } else {
        selectEl.classList.add('hidden');
        selectEl.disabled = true;
        selectEl.required = false;

        textEl.classList.remove('hidden');
        textEl.disabled = true;
        textEl.required = false;
        textEl.placeholder = "Select State first";

        if (otherContainer) otherContainer.classList.add('hidden');
        if (otherInput) { otherInput.disabled = true; otherInput.required = false; }
    }

    if (selectEl.refreshCustomSelect) selectEl.refreshCustomSelect();
}

function openAddModal() {
    const modal = document.getElementById('add-address-modal');
    const stateSelect = document.getElementById('add_state');
    const distSelect = document.getElementById('add_district_select');
    stateSelect.value = '';
    distSelect.value = '';
    const otherInput = document.getElementById('add_other_state_name');
    if (otherInput) otherInput.value = '';
    toggleAddDistrictFields();
    if (stateSelect.refreshCustomSelect) stateSelect.refreshCustomSelect();
    if (distSelect.refreshCustomSelect) distSelect.refreshCustomSelect();
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAddModal() {
    const modal = document.getElementById('add-address-modal');
    modal.classList.add('hidden');
    document.body.style.overflow = '';
}

function openEditModal(addr) {
    document.getElementById('edit_address_id').value = addr.id || '';
    document.getElementById('edit_label').value = addr.label || '';
    document.getElementById('edit_line1').value = addr.line1 || '';
    document.getElementById('edit_line2').value = addr.line2 || '';
    document.getElementById('edit_postal_code').value = addr.postal_code || '';
    document.getElementById('edit_country').value = addr.country || 'India';
    document.getElementById('edit_is_default').checked = addr.is_default == 1;

    const rawCity = (addr.city || '').trim();
    const rawState = (addr.state || '').trim();

    // Check if district belongs to Tamil Nadu list
    const isTnDistrict = TN_DISTRICTS.some(d => d.toLowerCase() === rawCity.toLowerCase());
    const isTnState = (rawState.toLowerCase() === 'tamil nadu' || isTnDistrict);

    const stateSelect = document.getElementById('edit_state');
    const distSelect = document.getElementById('edit_district_select');
    const otherStateInput = document.getElementById('edit_other_state_name');

    if (isTnState) {
        stateSelect.value = 'Tamil Nadu';
        toggleEditDistrictFields();
        let matched = false;
        for (let opt of distSelect.options) {
            if (opt.value.toLowerCase() === rawCity.toLowerCase()) {
                distSelect.value = opt.value;
                matched = true;
                break;
            }
        }
        if (!matched && rawCity) {
            distSelect.value = rawCity;
        }
        if (otherStateInput) otherStateInput.value = '';
    } else {
        stateSelect.value = 'Others';
        toggleEditDistrictFields();
        document.getElementById('edit_district_text').value = rawCity;
        if (otherStateInput) {
            otherStateInput.value = (rawState && rawState.toLowerCase() !== 'others') ? rawState : '';
        }
    }

    if (stateSelect.refreshCustomSelect) stateSelect.refreshCustomSelect();
    if (distSelect.refreshCustomSelect) distSelect.refreshCustomSelect();

    const modal = document.getElementById('edit-address-modal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    const modal = document.getElementById('edit-address-modal');
    modal.classList.add('hidden');
    document.body.style.overflow = '';
}

document.addEventListener('DOMContentLoaded', function() {
    initAddrCustomSelect(document.getElementById('add_state'));
    initAddrCustomSelect(document.getElementById('add_district_select'));
    initAddrCustomSelect(document.getElementById('edit_state'));
    initAddrCustomSelect(document.getElementById('edit_district_select'));

    document.addEventListener('click', function() {
        document.querySelectorAll('.reg-cs-trigger.open').forEach(function(t) { t.classList.remove('open'); });
        document.querySelectorAll('.reg-cs-list.open').forEach(function(l) { l.classList.remove('open'); });
    });
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') { closeAddModal(); closeEditModal(); }
});
</script>
