<div class="w-full">
    <!-- Header Section -->
    <div class="mb-8 relative z-10">
        <div class="text-sm font-medium text-gray-400 mb-2 font-serif tracking-wide">
            Dashboard <span class="mx-1 text-gray-300">›</span> <?= (\Core\Session::get('user_role') === 'vendor') ? 'Vendor Portal' : 'Administration' ?> <span class="mx-1 text-gray-300">›</span> <span class="text-brand-700">Settings</span>
        </div>
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-5xl font-display font-extrabold text-gray-900 tracking-tight">Account & Store Settings</h2>
                <p class="text-sm text-gray-500 mt-1">Manage your multiple bank accounts, primary payout destination, and store profile details.</p>
            </div>
            
            <div class="flex items-center space-x-3">
                <button onclick="openAddBankModal()" class="bg-[#F25996] hover:bg-[#e04481] text-white px-5 py-2.5 rounded-full font-bold text-xs shadow-sm transition-all flex items-center gap-2 uppercase tracking-wider">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Bank Account
                </button>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="flex border-b border-gray-200 mb-8 space-x-8">
        <button onclick="switchTab('bank_accounts')" id="tab-btn-bank_accounts" class="tab-btn py-3 text-sm font-bold border-b-2 border-brand-600 text-brand-700 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            Bank Accounts & Payouts
            <span class="bg-brand-100 text-brand-800 text-xs px-2 py-0.5 rounded-full font-extrabold"><?= count($bankAccounts) ?></span>
        </button>
        <button onclick="switchTab('profile')" id="tab-btn-profile" class="tab-btn py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            Store & Tax Profile
        </button>
    </div>

    <!-- TAB 1: BANK ACCOUNTS -->
    <div id="tab-content-bank_accounts" class="tab-content space-y-6">
        <!-- Active / Primary Account Notice -->
        <div class="bg-gradient-to-r from-emerald-900 via-teal-900 to-slate-900 p-6 rounded-2xl text-white shadow-md flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/20 border border-emerald-400/30 flex items-center justify-center text-emerald-400 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs uppercase font-extrabold tracking-wider text-emerald-400">Current Primary Payout Destination</span>
                        <span class="bg-emerald-500/30 text-emerald-300 text-[10px] font-bold px-2 py-0.5 rounded-full border border-emerald-400/30">Active in Finance</span>
                    </div>
                    <?php if ($primaryAccount): ?>
                        <h3 class="text-xl font-bold text-white mt-1"><?= htmlspecialchars($primaryAccount['bank_name']) ?> — <span class="font-mono text-emerald-300"><?= htmlspecialchars($primaryAccount['account_number']) ?></span></h3>
                        <p class="text-xs text-slate-300 mt-1">Beneficiary: <strong><?= htmlspecialchars($primaryAccount['account_holder_name']) ?></strong> | IFSC: <span class="font-mono uppercase text-emerald-200"><?= htmlspecialchars($primaryAccount['ifsc_code']) ?></span> <?= !empty($primaryAccount['branch_name']) ? ('| Branch: ' . htmlspecialchars($primaryAccount['branch_name'])) : '' ?></p>
                    <?php else: ?>
                        <p class="text-sm text-amber-300 mt-1">No primary bank account configured yet. Please add a bank account below to enable automated payouts.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="shrink-0 flex items-center gap-3">
                <button onclick="openAddBankModal()" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 px-4 py-2 rounded-xl text-xs font-bold transition shadow">
                    + Add Another Bank
                </button>
            </div>
        </div>

        <!-- Bank Accounts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (empty($bankAccounts)): ?>
                <div class="col-span-full bg-white rounded-2xl p-12 text-center border border-gray-100 shadow-sm">
                    <div class="w-16 h-16 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">No Bank Accounts Added</h3>
                    <p class="text-sm text-gray-500 max-w-md mx-auto mb-6">Add your bank account details to receive sales payouts, wallet withdrawals, and settlement transfers directly into your bank.</p>
                    <button onclick="openAddBankModal()" class="bg-brand-600 hover:bg-brand-700 text-white px-6 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider shadow">
                        Add First Bank Account
                    </button>
                </div>
            <?php else: ?>
                <?php foreach ($bankAccounts as $acc): ?>
                    <div class="bg-white rounded-2xl border <?= $acc['is_primary'] ? 'border-emerald-500 ring-2 ring-emerald-500/20 shadow-md' : 'border-gray-200 hover:border-gray-300 shadow-sm' ?> p-6 relative flex flex-col justify-between transition-all">
                        <!-- Top status & tags -->
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-9 h-9 rounded-lg <?= $acc['is_primary'] ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-600' ?> flex items-center justify-center font-black text-sm">
                                        🏦
                                    </div>
                                    <div>
                                        <h4 class="text-base font-bold text-gray-900 leading-tight"><?= htmlspecialchars($acc['bank_name']) ?></h4>
                                        <span class="text-[10px] uppercase font-semibold text-gray-400"><?= htmlspecialchars($acc['account_type']) ?> Account</span>
                                    </div>
                                </div>

                                <?php if ($acc['is_primary']): ?>
                                    <span class="bg-emerald-100 text-emerald-800 text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> Primary Active
                                    </span>
                                <?php else: ?>
                                    <button onclick="setPrimaryAccount(<?= $acc['id'] ?>)" class="text-xs text-brand-600 hover:text-brand-800 font-bold hover:underline">
                                        Set as Active &rarr;
                                    </button>
                                <?php endif; ?>
                            </div>

                            <!-- Account Details Box -->
                            <div class="bg-gray-50 rounded-xl p-4 space-y-2 border border-gray-100 text-xs mb-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-500 font-medium">Account Number:</span>
                                    <span class="font-mono font-bold text-gray-900 tracking-wider text-sm"><?= htmlspecialchars($acc['account_number']) ?></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-500 font-medium">Beneficiary Name:</span>
                                    <span class="font-semibold text-gray-900"><?= htmlspecialchars($acc['account_holder_name']) ?></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-500 font-medium">IFSC Code:</span>
                                    <span class="font-mono font-bold text-indigo-700 uppercase"><?= htmlspecialchars($acc['ifsc_code']) ?></span>
                                </div>
                                <?php if (!empty($acc['branch_name'])): ?>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-500 font-medium">Branch:</span>
                                    <span class="text-gray-700"><?= htmlspecialchars($acc['branch_name']) ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if (!empty($acc['upi_id'])): ?>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-500 font-medium">UPI VPA:</span>
                                    <span class="font-mono text-gray-900 font-semibold"><?= htmlspecialchars($acc['upi_id']) ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Card Action Buttons -->
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100 text-xs">
                            <div>
                                <?php if (!$acc['is_primary']): ?>
                                    <button onclick="setPrimaryAccount(<?= $acc['id'] ?>)" class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold rounded-lg transition">
                                        Make Default
                                    </button>
                                <?php else: ?>
                                    <span class="text-[11px] text-emerald-600 font-semibold flex items-center gap-1">
                                        ✓ Used dynamically for payouts
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="flex items-center space-x-2">
                                <button onclick="editBankAccount(<?= htmlspecialchars(json_encode($acc)) ?>)" class="text-gray-500 hover:text-gray-700 font-bold p-1.5 hover:bg-gray-100 rounded-lg transition" title="Edit details">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <button onclick="deleteBankAccount(<?= $acc['id'] ?>, '<?= htmlspecialchars($acc['bank_name']) ?>')" class="text-rose-500 hover:text-rose-700 font-bold p-1.5 hover:bg-rose-50 rounded-lg transition" title="Remove account">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- TAB 2: STORE PROFILE -->
    <div id="tab-content-profile" class="tab-content hidden space-y-6">
        <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-sm max-w-4xl">
            <h3 class="text-lg font-bold text-gray-900 mb-6 font-display">Registered Vendor Details</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Store / Brand Name</label>
                    <div class="p-3 bg-gray-50 rounded-xl font-bold text-gray-900 border border-gray-100">
                        <?= htmlspecialchars($vendorProfile['store_name'] ?? 'N/A') ?>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Company / Legal Entity</label>
                    <div class="p-3 bg-gray-50 rounded-xl font-bold text-gray-900 border border-gray-100">
                        <?= htmlspecialchars($vendorProfile['company_name'] ?? 'N/A') ?>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Unique Vendor ID</label>
                    <div class="p-3 bg-gray-50 rounded-xl font-mono font-bold text-brand-700 border border-gray-100">
                        <?= htmlspecialchars($vendorProfile['unique_vendor_id'] ?? 'N/A') ?>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Vendor Type</label>
                    <div class="p-3 bg-gray-50 rounded-xl font-bold text-gray-900 border border-gray-100 capitalize">
                        <?= htmlspecialchars($vendorProfile['vendor_type'] ?? 'Wholesaler') ?>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">GST Number</label>
                    <div class="p-3 bg-gray-50 rounded-xl font-mono font-bold text-gray-900 border border-gray-100">
                        <?= htmlspecialchars($vendorProfile['gst_number'] ?? 'Not Provided / Exempted') ?>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">PAN Number</label>
                    <div class="p-3 bg-gray-50 rounded-xl font-mono font-bold text-gray-900 border border-gray-100">
                        <?= htmlspecialchars($vendorProfile['pan_number'] ?? 'N/A') ?>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Registered Business Address</label>
                    <div class="p-3 bg-gray-50 rounded-xl text-gray-900 font-medium border border-gray-100">
                        <?= htmlspecialchars($vendorProfile['address'] ?? '') ?>, <?= htmlspecialchars($vendorProfile['city'] ?? '') ?>, <?= htmlspecialchars($vendorProfile['state'] ?? '') ?> - <?= htmlspecialchars($vendorProfile['pincode'] ?? '') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Add / Edit Bank Account -->
<div id="bankAccountModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-100 flex flex-col my-auto">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-[#F25996] to-[#e04481] text-white">
            <h3 class="font-bold text-base" id="bankModalTitle">Add Bank Account</h3>
            <button onclick="closeBankModal()" class="text-white/80 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form id="bankAccountForm" onsubmit="handleSaveBankAccount(event)" class="p-6 space-y-4">
            <input type="hidden" id="bank_acc_id" name="id" value="">

            <div>
                <label for="f_bank_name" class="block text-xs font-semibold text-gray-700 mb-1">Bank Name *</label>
                <input type="text" id="f_bank_name" name="bank_name" required placeholder="e.g. HDFC Bank, State Bank of India" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-brand-500 focus:border-brand-500 text-sm font-semibold text-gray-900">
            </div>

            <div>
                <label for="f_holder_name" class="block text-xs font-semibold text-gray-700 mb-1">Account Holder / Beneficiary Name *</label>
                <input type="text" id="f_holder_name" name="account_holder_name" required placeholder="Name as registered with bank" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-brand-500 focus:border-brand-500 text-sm font-semibold text-gray-900">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="f_acc_no" class="block text-xs font-semibold text-gray-700 mb-1">Account Number *</label>
                    <input type="text" id="f_acc_no" name="account_number" inputmode="numeric" maxlength="18" required placeholder="Digits only (9-18)" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-brand-500 focus:border-brand-500 text-sm font-mono font-bold text-gray-900">
                </div>
                <div>
                    <label for="f_ifsc" class="block text-xs font-semibold text-gray-700 mb-1">IFSC Code *</label>
                    <input type="text" id="f_ifsc" name="ifsc_code" maxlength="11" required placeholder="e.g. HDFC0001234" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-brand-500 focus:border-brand-500 text-sm font-mono font-bold uppercase text-gray-900">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="f_branch" class="block text-xs font-semibold text-gray-700 mb-1">Branch Name</label>
                    <input type="text" id="f_branch" name="branch_name" placeholder="e.g. Coimbatore Main" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-brand-500 focus:border-brand-500 text-sm text-gray-900">
                </div>
                <div>
                    <label for="f_acc_type" class="block text-xs font-semibold text-gray-700 mb-1">Account Type</label>
                    <select id="f_acc_type" name="account_type" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-brand-500 focus:border-brand-500 text-sm font-medium text-gray-900 bg-white">
                        <option value="current">Current Account</option>
                        <option value="savings">Savings Account</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="f_upi" class="block text-xs font-semibold text-gray-700 mb-1">UPI ID / VPA (Optional)</label>
                <input type="text" id="f_upi" name="upi_id" placeholder="e.g. yourstore@okaxis" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-brand-500 focus:border-brand-500 text-sm font-mono text-gray-900">
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" id="f_is_primary" name="is_primary" value="1" class="w-4 h-4 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                <label for="f_is_primary" class="text-xs font-semibold text-gray-700 cursor-pointer">Set as Primary / Active Bank for dynamic payout settlements</label>
            </div>

            <div class="pt-4 flex justify-end space-x-3 border-t border-gray-100">
                <button type="button" onclick="closeBankModal()" class="px-4 py-2 border border-gray-200 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-50 transition">Cancel</button>
                <button type="submit" id="saveBankBtn" class="px-5 py-2 bg-[#F25996] hover:bg-[#e04481] text-white rounded-xl text-xs font-bold transition shadow">Save Bank Details</button>
            </div>
        </form>
    </div>
</div>

<script>
function switchTab(tab) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(el => {
        el.classList.remove('border-brand-600', 'text-brand-700', 'font-bold');
        el.classList.add('border-transparent', 'text-gray-500', 'font-medium');
    });

    const activeContent = document.getElementById('tab-content-' + tab);
    const activeBtn = document.getElementById('tab-btn-' + tab);
    if (activeContent) activeContent.classList.remove('hidden');
    if (activeBtn) {
        activeBtn.classList.remove('border-transparent', 'text-gray-500', 'font-medium');
        activeBtn.classList.add('border-brand-600', 'text-brand-700', 'font-bold');
    }
}

function openAddBankModal() {
    document.getElementById('bankModalTitle').innerText = 'Add New Bank Account';
    document.getElementById('bank_acc_id').value = '';
    document.getElementById('bankAccountForm').reset();
    document.getElementById('bankAccountModal').classList.remove('hidden');
}

function editBankAccount(acc) {
    document.getElementById('bankModalTitle').innerText = 'Edit Bank Account';
    document.getElementById('bank_acc_id').value = acc.id;
    document.getElementById('f_bank_name').value = acc.bank_name || '';
    document.getElementById('f_holder_name').value = acc.account_holder_name || '';
    document.getElementById('f_acc_no').value = acc.account_number || '';
    document.getElementById('f_ifsc').value = acc.ifsc_code || '';
    document.getElementById('f_branch').value = acc.branch_name || '';
    document.getElementById('f_acc_type').value = acc.account_type || 'current';
    document.getElementById('f_upi').value = acc.upi_id || '';
    document.getElementById('f_is_primary').checked = (parseInt(acc.is_primary) === 1);
    document.getElementById('bankAccountModal').classList.remove('hidden');
}

function closeBankModal() {
    document.getElementById('bankAccountModal').classList.add('hidden');
}

function handleSaveBankAccount(e) {
    e.preventDefault();
    const btn = document.getElementById('saveBankBtn');
    btn.disabled = true;
    btn.innerText = 'Saving...';

    const data = {
        id: document.getElementById('bank_acc_id').value,
        bank_name: document.getElementById('f_bank_name').value,
        account_holder_name: document.getElementById('f_holder_name').value,
        account_number: document.getElementById('f_acc_no').value,
        ifsc_code: document.getElementById('f_ifsc').value,
        branch_name: document.getElementById('f_branch').value,
        account_type: document.getElementById('f_acc_type').value,
        upi_id: document.getElementById('f_upi').value,
        is_primary: document.getElementById('f_is_primary').checked ? 1 : 0
    };

    fetch('<?= BASE_URL ?>/admin/vendor/settings/bank-accounts/save', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        btn.disabled = false;
        btn.innerText = 'Save Bank Details';
        if (res.success) {
            window.location.reload();
        } else {
            alert(res.message);
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerText = 'Save Bank Details';
        alert('An error occurred while saving.');
    });
}

function setPrimaryAccount(id) {
    if (!confirm('Are you sure you want to set this bank account as the active primary payout account? Future payouts and admin finance transfers will automatically use this account.')) return;

    fetch('<?= BASE_URL ?>/admin/vendor/settings/bank-accounts/set-primary', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id })
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            window.location.reload();
        } else {
            alert(res.message);
        }
    })
    .catch(() => alert('Failed to update primary bank account.'));
}

function deleteBankAccount(id, name) {
    if (!confirm(`Are you sure you want to remove bank account "${name}"?`)) return;

    fetch('<?= BASE_URL ?>/admin/vendor/settings/bank-accounts/delete', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id })
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            window.location.reload();
        } else {
            alert(res.message);
        }
    })
    .catch(() => alert('Failed to delete bank account.'));
}
</script>
