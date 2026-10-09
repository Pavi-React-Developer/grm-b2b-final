<div class="bg-[#fafafa] min-h-screen flex flex-col md:flex-row relative">
    
    <?php include __DIR__ . '/_sidebar.php'; ?>
    
    <div class="flex-1 p-3 xs:p-4 sm:p-5 md:p-6 lg:p-12 relative overflow-y-auto w-full min-w-0">
        <div class="max-w-3xl mx-auto w-full min-w-0">
            
            <!-- Page Header -->
            <div class="mb-5 sm:mb-8 flex flex-row items-center justify-between gap-2">
                <div class="min-w-0">
                    <h1 class="text-xl sm:text-2xl md:text-2xl lg:text-3xl font-black text-[#1d1d1f] tracking-tight font-display truncate">Public profile</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5 truncate">Manage your buyer details & credentials</p>
                </div>
                <button type="button" onclick="toggleEditMode()" id="edit-profile-btn" class="flex items-center gap-1.5 text-xs sm:text-sm font-bold text-[#F25996] bg-pink-50 hover:bg-pink-100 px-3 py-2 sm:px-4 sm:py-2.5 rounded-xl transition-all shadow-sm shrink-0">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    <span id="edit-btn-text">Edit Profile</span>
                </button>
            </div>
            
            <form action="<?= BASE_URL ?>/dashboard/profile/update" method="POST" enctype="multipart/form-data" class="w-full min-w-0">
                
                <!-- Profile Picture & Quick Info Card -->
                <div class="bg-white rounded-2xl p-4 sm:p-5 md:p-3.5 lg:p-6 border border-gray-100 shadow-sm mb-5 sm:mb-8 flex flex-col sm:flex-row items-center sm:items-center gap-4 sm:gap-6 md:gap-3.5 lg:gap-6 text-center sm:text-left">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 md:w-16 md:h-16 lg:w-28 lg:h-28 rounded-full border-4 border-white shadow-md overflow-hidden bg-gray-100 flex-shrink-0 relative group">
                        <?php if (!empty($user['profile_picture'])): ?>
                            <img id="profile-pic-preview" src="<?= htmlspecialchars($user['profile_picture']) ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <img id="profile-pic-preview" class="w-full h-full object-cover hidden">
                            <svg id="profile-pic-placeholder" class="w-10 h-10 sm:w-12 sm:h-12 md:w-8 md:h-8 lg:w-14 lg:h-14 text-gray-300 absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h2 class="text-base sm:text-lg md:text-sm lg:text-lg font-bold text-gray-900 truncate"><?= htmlspecialchars($user['name'] ?? 'Buyer Profile') ?></h2>
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 md:gap-1.5 lg:gap-2 mt-1">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 md:px-2 md:py-0.5 lg:px-2.5 lg:py-0.5 rounded-full text-[11px] sm:text-xs md:text-[10px] lg:text-xs font-semibold bg-pink-50 text-[#F25996] border border-pink-200">
                                <svg class="w-3 h-3 md:w-2.5 md:h-2.5 lg:w-3 lg:h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                ID: <?= htmlspecialchars($user['unique_buyer_id'] ?? 'Pending') ?>
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 md:px-2 md:py-0.5 lg:px-2.5 lg:py-0.5 rounded-full text-[11px] sm:text-xs md:text-[10px] lg:text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                <span class="w-1.5 h-1.5 md:w-1 md:h-1 lg:w-1.5 lg:h-1.5 rounded-full bg-emerald-500"></span>
                                Verified Buyer
                            </span>
                        </div>
                        <div class="mt-3 hidden w-full sm:w-auto" id="change-picture-btn">
                            <label class="bg-[#F25996] hover:bg-[#d8407d] text-white px-4 py-2 rounded-xl font-bold text-xs sm:text-sm cursor-pointer transition-colors text-center shadow inline-flex items-center justify-center gap-1.5 w-full sm:w-auto">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Change Picture</span>
                                <input type="file" name="profile_picture" accept=".jpg,.jpeg,.png,.gif" class="hidden" onchange="previewProfilePic(this)">
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Form Fields Card -->
                <div class="bg-white rounded-2xl p-4 sm:p-5 md:p-6 border border-gray-100 shadow-sm space-y-4 sm:space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 sm:gap-4 md:gap-4 lg:gap-5">
                        <div class="min-w-0">
                            <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">Business ID</label>
                            <input type="text" value="<?= htmlspecialchars($user['unique_buyer_id'] ?? 'Pending') ?>" disabled class="w-full px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-500 font-mono text-xs sm:text-sm tracking-wider cursor-not-allowed">
                        </div>
                        <div class="min-w-0">
                            <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required disabled class="profile-input w-full px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-xl border border-gray-200 text-xs sm:text-sm focus:border-[#F25996] focus:ring-2 focus:ring-[#F25996]/10 transition-colors bg-white disabled:bg-gray-50 disabled:border-gray-200 disabled:text-gray-700">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 sm:gap-4 md:gap-4 lg:gap-5">
                        <div class="min-w-0">
                            <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">Email Address</label>
                            <input type="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" disabled class="w-full px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-500 text-xs sm:text-sm cursor-not-allowed truncate">
                        </div>
                        <div class="min-w-0">
                            <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">Phone Number <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required pattern="\d{10}" title="Phone number must be exactly 10 digits" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" disabled class="profile-input w-full px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-xl border border-gray-200 text-xs sm:text-sm focus:border-[#F25996] focus:ring-2 focus:ring-[#F25996]/10 transition-colors bg-white disabled:bg-gray-50 disabled:border-gray-200 disabled:text-gray-700">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 sm:gap-4 md:gap-4 lg:gap-5">
                        <div class="sm:col-span-2 min-w-0">
                            <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">Company / Store Name</label>
                            <input type="text" name="business_name" value="<?= htmlspecialchars($profile['business_name'] ?? '') ?>" disabled class="profile-input w-full px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-xl border border-gray-200 text-xs sm:text-sm focus:border-[#F25996] focus:ring-2 focus:ring-[#F25996]/10 transition-colors bg-white disabled:bg-gray-50 disabled:border-gray-200 disabled:text-gray-700">
                        </div>
                        <div class="min-w-0">
                            <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">GST Number</label>
                            <input type="text" name="gst_number" value="<?= htmlspecialchars($profile['gst_number'] ?? '') ?>" disabled class="w-full px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-500 font-mono text-xs sm:text-sm cursor-not-allowed">
                        </div>
                        <div class="min-w-0">
                            <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">PAN Number</label>
                            <input type="text" name="pan_number" value="<?= htmlspecialchars($profile['pan_number'] ?? '') ?>" disabled class="w-full px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-500 font-mono text-xs sm:text-sm cursor-not-allowed">
                        </div>
                        <div class="sm:col-span-2 min-w-0">
                            <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f] mb-1.5">Instagram Profile Link</label>
                            <input type="url" name="instagram_link" value="<?= htmlspecialchars($profile['instagram_link'] ?? '') ?>" disabled class="profile-input w-full px-3.5 py-2.5 sm:px-4 sm:py-3 rounded-xl border border-gray-200 text-xs sm:text-sm focus:border-[#F25996] focus:ring-2 focus:ring-[#F25996]/10 transition-colors bg-white disabled:bg-gray-50 disabled:border-gray-200 disabled:text-gray-700" placeholder="https://instagram.com/yourhandle">
                        </div>
                    </div>

                    <!-- Default Delivery Address -->
                    <div class="pt-2">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-2">
                            <label class="block text-xs sm:text-sm font-bold text-[#1d1d1f]">Default Delivery Address</label>
                            <a href="<?= BASE_URL ?>/dashboard/addresses" class="text-xs sm:text-sm font-bold text-[#F25996] hover:underline flex items-center gap-1">Manage Addresses &rarr;</a>
                        </div>
                        <div class="p-3.5 sm:p-5 rounded-xl border border-gray-200 bg-gray-50 text-gray-700 text-xs sm:text-sm">
                            <?php if (!empty($defaultAddress)): ?>
                                <?php if (!empty($defaultAddress['label'])): ?>
                                    <span class="inline-block px-2.5 py-0.5 bg-[#F25996] text-white text-[11px] font-bold rounded-full mb-2 uppercase tracking-wider"><?= htmlspecialchars($defaultAddress['label']) ?></span>
                                <?php endif; ?>
                                <p class="font-semibold text-sm sm:text-base text-gray-900 mb-1 break-words"><?= htmlspecialchars($defaultAddress['line1']) ?></p>
                                <?php if (!empty($defaultAddress['line2'])): ?>
                                    <p class="text-gray-600 mb-1 break-words"><?= htmlspecialchars($defaultAddress['line2']) ?></p>
                                <?php endif; ?>
                                <p class="text-gray-600 mb-1"><?= htmlspecialchars($defaultAddress['city']) ?>, <?= htmlspecialchars($defaultAddress['state']) ?> &ndash; <?= htmlspecialchars($defaultAddress['postal_code']) ?></p>
                                <p class="text-gray-500 text-xs sm:text-sm"><?= htmlspecialchars($defaultAddress['country'] ?? 'India') ?></p>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <p class="text-gray-500 mb-3 text-xs sm:text-sm">No default address set.</p>
                                    <a href="<?= BASE_URL ?>/dashboard/addresses" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white border border-gray-300 rounded-xl text-xs sm:text-sm font-bold text-gray-700 hover:bg-gray-50 shadow-sm transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Add an Address
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Save / Cancel Container -->
                    <div id="save-button-container" class="mt-6 pt-5 border-t border-gray-100 hidden flex-col sm:flex-row items-stretch sm:items-center justify-end gap-2.5 sm:gap-3">
                        <button type="button" onclick="toggleEditMode()" class="w-full sm:w-auto px-5 py-2.5 sm:py-3 rounded-xl font-bold text-xs sm:text-sm text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors text-center order-2 sm:order-1">
                            Cancel
                        </button>
                        <button type="submit" class="w-full sm:w-auto bg-[#F25996] hover:bg-[#d8407d] text-white px-6 py-2.5 sm:py-3 rounded-xl font-bold text-xs sm:text-sm shadow-md shadow-pink-500/20 transition-all text-center order-1 sm:order-2">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
            
            <!-- Shop Verification Images -->
            <?php if (!empty($shopImages)): ?>
            <div class="mt-8 sm:mt-12 pt-6 border-t border-gray-200">
                <h2 class="text-base sm:text-xl font-bold text-[#1d1d1f] mb-4">Shop Verification Images</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2.5 sm:gap-4">
                    <?php foreach ($shopImages as $image): ?>
                        <div class="aspect-square bg-gray-50 rounded-xl sm:rounded-2xl overflow-hidden border border-gray-200 shadow-sm relative group">
                            <img src="<?= htmlspecialchars(get_image_url($image['file_path'])) ?>" alt="Shop Image" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
</div>

<script>
function toggleEditMode() {
    const changePicBtn = document.getElementById('change-picture-btn');
    if (changePicBtn) {
        changePicBtn.classList.toggle('hidden');
    }
    
    const saveContainer = document.getElementById('save-button-container');
    const editBtnText = document.getElementById('edit-btn-text');
    
    if (saveContainer) {
        if (saveContainer.classList.contains('hidden')) {
            saveContainer.classList.remove('hidden');
            saveContainer.classList.add('flex');
            if (editBtnText) editBtnText.textContent = 'Editing...';
        } else {
            saveContainer.classList.add('hidden');
            saveContainer.classList.remove('flex');
            if (editBtnText) editBtnText.textContent = 'Edit Profile';
        }
    }
    
    const inputs = document.querySelectorAll('.profile-input');
    inputs.forEach(input => {
        input.disabled = !input.disabled;
    });
}

function previewProfilePic(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('profile-pic-preview');
            const placeholder = document.getElementById('profile-pic-placeholder');
            if (preview) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }
            if (placeholder) {
                placeholder.classList.add('hidden');
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
