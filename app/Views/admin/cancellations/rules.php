<?php
/**
 * Admin — Cancellation Rules (Financial & SLA)
 *
 * @var array $rules
 */

$isCod = false; // Always false, site only supports online payment

// Filter rules to only show prepaid
$filteredRules = array_filter($rules, function($r) {
    return $r['payment_method'] === 'prepaid';
});
?>

<div class="w-full">
    <!-- Header Section -->
    <div class="mb-8 relative z-10">
        <!-- Breadcrumbs -->
        <div class="text-sm font-medium text-gray-400 mb-2 font-serif tracking-wide">
            Dashboard <span class="mx-1 text-gray-300">›</span> <a href="<?= BASE_URL ?>/admin/cancellations" class="hover:text-brand-600">Cancellation Management</a> <span class="mx-1 text-gray-300">›</span> <span class="text-brand-700">Cancellation Rules</span>
        </div>
        
        <div class="flex justify-between items-center mb-6 w-full">
            <h2 class="text-5xl font-display font-extrabold text-gray-900 tracking-tight">Cancellation Rules</h2>
            
            <div class="flex items-center space-x-3 ml-auto">
                <button onclick="window.location.reload()" class="bg-white hover:bg-gray-50 text-gray-700 px-6 py-3 rounded-full font-bold text-sm shadow-sm border border-gray-200 transition-all flex items-center uppercase tracking-widest">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Refresh
                </button>
                <button onclick="exportCancellationRulesCSV()" class="bg-[#F25996] hover:bg-[#e04481] text-white px-6 py-3 rounded-full font-bold text-sm shadow-sm transition-all flex items-center uppercase tracking-widest">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export Excel
                </button>
            </div>
        </div>
    </div>

<!-- Global Cancellations Toggle Banner -->
<div id="global-toggle-banner" class="mb-6 flex items-center justify-between px-5 py-4 rounded-2xl border <?= $cancellationsEnabled ? 'bg-emerald-50 border-emerald-200' : 'bg-red-50 border-red-200' ?> shadow-sm transition-all" id="global-cancel-banner">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full flex items-center justify-center <?= $cancellationsEnabled ? 'bg-emerald-100' : 'bg-red-100' ?>">
            <svg class="w-5 h-5 <?= $cancellationsEnabled ? 'text-emerald-600' : 'text-red-500' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="font-bold text-sm <?= $cancellationsEnabled ? 'text-emerald-800' : 'text-red-800' ?>">
                Order Cancellations: <span id="global-toggle-label"><?= $cancellationsEnabled ? 'Enabled' : 'Disabled' ?></span>
            </p>
            <p class="text-xs <?= $cancellationsEnabled ? 'text-emerald-700' : 'text-red-700' ?> mt-0.5">
                When disabled, buyers will not see the "Cancel Order" button in their dashboard.
            </p>
        </div>
    </div>
    <!-- Toggle Switch -->
    <label class="relative inline-flex items-center cursor-pointer" title="Toggle cancellations globally">
        <input type="checkbox" id="global-cancel-toggle" class="sr-only peer" <?= $cancellationsEnabled ? 'checked' : '' ?> onchange="toggleGlobalCancellations(this.checked)">
        <div class="w-14 h-7 bg-red-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-emerald-400 rounded-full peer peer-checked:after:translate-x-7 peer-checked:bg-emerald-500 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all shadow-inner"></div>
    </label>
</div>

<!-- Page header -->
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-gray-900 mb-1 font-serif">Cancellation Rules</h1>
        <p class="text-sm text-gray-500">Define cancellation fees, refund percentages, and SLAs based on order status.</p>
    </div>
    <?php if ($this->hasPermission('cancellation_rules', 'create')): ?>
    <button onclick="openAddModal()"
        class="flex items-center gap-2 px-6 py-2.5 bg-[#F25996] hover:bg-[#d94480] text-white font-bold rounded-full shadow-sm transition-all text-sm uppercase tracking-wider active:scale-95">
        + Add New Rule
    </button>
    <?php endif; ?>
</div>



<!-- Rules list -->
<?php if (empty($filteredRules)): ?>
    <div class="bg-white border border-dashed border-gray-300 rounded-2xl p-16 text-center">
        <svg class="w-12 h-12 mx-auto text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        <h3 class="text-lg font-bold text-gray-700 mb-2">No rules defined</h3>
        <p class="text-gray-400 text-sm mb-6">All cancellations will be allowed with 0 fee and 100% refund by default.</p>
        <?php if ($this->hasPermission('cancellation_rules', 'create')): ?>
        <button onclick="openAddModal()" class="px-6 py-2.5 bg-[#F25996] text-white font-bold rounded-full hover:bg-[#d94480] transition-all text-sm uppercase tracking-wider shadow-sm active:scale-95">
            + Add Your First Rule
        </button>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="rules-list">
        <?php foreach ($filteredRules as $rule): ?>
        <div id="rule-card-<?= $rule['id'] ?>" class="bg-white border <?= $rule['is_active'] ? 'border-gray-200' : 'border-gray-100 opacity-60' ?> rounded-2xl p-5 shadow-sm transition-all">
            
            <div class="flex items-center justify-between mb-4">
                <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-bold rounded-full border border-gray-200 uppercase tracking-wider">
                    <?= htmlspecialchars($rule['rule_name']) ?>
                </span>
                
                <div class="flex items-center gap-2">
                    <?php if ($this->hasPermission('cancellation_rules', 'edit')): ?>
                    <button onclick='openEditModal(<?= json_encode($rule) ?>)' class="p-1.5 text-gray-400 hover:text-[#F25996] transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </button>
                    <?php endif; ?>
                    <?php if ($this->hasPermission('cancellation_rules', 'delete')): ?>
                    <button onclick="deleteRule(<?= $rule['id'] ?>, '<?= htmlspecialchars(addslashes($rule['rule_name'])) ?>')" class="p-1.5 text-gray-400 hover:text-rose-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                    <?php endif; ?>
                </div>
            </div>

            <div class="space-y-3 mb-5">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500">Cancellation Fee</span>
                    <span class="font-bold text-gray-900">₹<?= number_format($rule['cancellation_fee'], 2) ?></span>
                </div>
                <!-- Refund is calculated dynamically -->
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500">SLA</span>
                    <span class="font-bold text-gray-900"><?= $rule['sla_days'] ?> Days</span>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                <span class="text-xs font-bold <?= $rule['is_active'] ? 'text-emerald-600' : 'text-gray-400' ?>">
                    <?= $rule['is_active'] ? 'Active' : 'Inactive' ?>
                </span>
                
                <button onclick="toggleRule(<?= $rule['id'] ?>, this)"
                    id="toggle-<?= $rule['id'] ?>"
                    <?= !$this->hasPermission('cancellation_rules', 'edit') ? 'disabled' : '' ?>
                    class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors focus:outline-none <?= $rule['is_active'] ? 'bg-[#F25996]' : 'bg-gray-200' ?> <?= !$this->hasPermission('cancellation_rules', 'edit') ? 'opacity-50 cursor-not-allowed' : '' ?>">
                    <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white shadow transition-transform <?= $rule['is_active'] ? 'translate-x-4' : 'translate-x-1' ?>"></span>
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- =============================================================================
     Add / Edit Rule Modal
============================================================================= -->
<div id="rule-modal" class="fixed inset-0 hidden flex items-center justify-center p-4" style="z-index: 9999;" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="closeRuleModal()"></div>
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">

        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <h3 id="modal-title" class="font-display text-xl font-bold text-gray-900">+ Add New Cancellation Rule</h3>
            <button onclick="closeRuleModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6">
            <input type="hidden" id="rule-id" value="">
            <input type="hidden" id="rule-payment-method" value="prepaid">

            <div class="grid grid-cols-2 gap-5 mb-5">
                
                <!-- Rule Name -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-1.5 uppercase tracking-wider">Rule Name (Order Status) <span class="text-red-500">*</span></label>
                    <select id="rule-name" class="w-full bg-gray-50/50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] appearance-none">
                        <option value="placed">Placed</option>
                        <option value="packed">Packed</option>
                        <option value="shipping">Shipping</option>
                        <option value="out_for_delivery">Out of delivery</option>
                        <option value="delivered">Delivery</option>
                    </select>
                </div>

                <!-- Cancellation Fee -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-1.5 uppercase tracking-wider">Cancellation Fee (₹) <span class="text-red-500">*</span></label>
                    <input type="text" id="rule-fee" min="0" step="1" placeholder="e.g. 20" 
                           class="w-full bg-gray-50/50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] placeholder-gray-400">
                </div>

                <!-- Refund % (Hidden as we now auto-calculate based on flat fee) -->
                <div class="hidden">
                    <label class="block text-xs font-bold text-gray-800 mb-1.5 uppercase tracking-wider">Refund (%) <span class="text-red-500">*</span></label>
                    <input type="text" id="rule-refund" value="100" class="w-full bg-gray-50/50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] placeholder-gray-400">
                </div>

                <!-- SLA Days -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-1.5 uppercase tracking-wider">SLA (Days) <span class="text-red-500">*</span></label>
                    <input type="text" id="rule-sla" min="0" step="1" placeholder="Enter SLA in days" class="w-full bg-gray-50/50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#F25996] focus:ring-1 focus:ring-[#F25996] placeholder-gray-400">
                </div>
            </div>

            <!-- Status Radio -->
            <div class="mb-8">
                <label class="block text-xs font-bold text-gray-800 mb-2 uppercase tracking-wider">Status</label>
                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2 text-sm cursor-pointer text-gray-700">
                        <input type="radio" name="rule_status" value="1" class="accent-[#F25996]" checked> Active
                    </label>
                    <label class="flex items-center gap-2 text-sm cursor-pointer text-gray-700">
                        <input type="radio" name="rule_status" value="0" class="accent-[#F25996]"> Inactive
                    </label>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="flex items-center justify-center gap-4">
                <button onclick="closeRuleModal()" class="px-8 py-2.5 bg-white border border-gray-300 text-gray-700 font-bold text-xs uppercase tracking-wider rounded-full hover:bg-gray-50 transition-colors">
                    CANCEL
                </button>
                <button id="rule-submit-btn" onclick="submitRuleForm()" class="px-8 py-2.5 bg-[#F25996] text-white font-bold text-xs uppercase tracking-wider rounded-full hover:bg-[#d94480] transition-colors shadow-md flex items-center gap-2 disabled:opacity-60">
                    <span id="rule-submit-label">SAVE RULE</span>
                    <svg id="rule-spinner" class="w-4 h-4 animate-spin hidden" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg>
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Delete confirmation -->
<div id="delete-modal" class="fixed inset-0 hidden flex items-center justify-center p-4" style="z-index: 9999;">
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
    <div class="relative w-full max-w-sm bg-white rounded-2xl shadow-xl border border-gray-100 p-6 text-center">
        <h3 class="text-lg font-black text-gray-900 mb-2 font-serif">Delete Rule?</h3>
        <p class="text-sm text-gray-500 mb-6">Are you sure you want to delete the rule for <strong id="delete-rule-name"></strong>?</p>
        <div class="flex gap-3 justify-center">
            <button onclick="closeDeleteModal()" class="px-5 py-2 border border-gray-300 text-gray-700 font-bold rounded-full hover:bg-gray-50 transition text-sm">Cancel</button>
            <button id="delete-confirm-btn" onclick="confirmDelete()" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-full shadow-md transition text-sm">Delete</button>
        </div>
    </div>
</div>

<!-- Toast -->
<div id="toast-box" class="fixed bottom-6 right-6 flex flex-col gap-2 pointer-events-none" style="z-index: 10000;"></div>

<script>
// ── Append Modals to Body Immediately ───────────────────────────────────────
(function() {
    const rm = document.getElementById('rule-modal');
    if (rm) document.body.appendChild(rm);
    const dm = document.getElementById('delete-modal');
    if (dm) document.body.appendChild(dm);
    const tb = document.getElementById('toast-box');
    if (tb) document.body.appendChild(tb);
})();

const IS_COD = <?= $isCod ? 'true' : 'false' ?>;

// ── Add modal ─────────────────────────────────────────────────────────────────
function openAddModal() {
    document.getElementById('rule-id').value = '';
    document.getElementById('rule-name').value = 'placed';
    document.getElementById('rule-fee').value = '';
    document.getElementById('rule-refund').value = '';
    document.getElementById('rule-sla').value = '';
    document.querySelector('input[name="rule_status"][value="1"]').checked = true;
    
    document.getElementById('modal-title').textContent = '+ Add New Cancellation Rule (' + (IS_COD ? 'COD' : 'Prepaid') + ')';
    document.getElementById('rule-submit-label').textContent = 'SAVE RULE';
    
    document.getElementById('rule-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

// ── Edit modal ────────────────────────────────────────────────────────────────
function openEditModal(rule) {
    document.getElementById('rule-id').value = rule.id;
    
    // Set rule name dropdown
    const select = document.getElementById('rule-name');
    if (Array.from(select.options).some(o => o.value === rule.rule_name)) {
        select.value = rule.rule_name;
    } else {
        const opt = new Option(rule.rule_name, rule.rule_name);
        select.add(opt);
        select.value = rule.rule_name;
    }

    document.getElementById('rule-fee').value = rule.cancellation_fee;
    document.getElementById('rule-refund').value = rule.refund_percentage;
    document.getElementById('rule-sla').value = rule.sla_days;
    
    document.querySelector('input[name="rule_status"][value="' + rule.is_active + '"]').checked = true;

    document.getElementById('modal-title').textContent = 'Edit Cancellation Rule (' + (IS_COD ? 'COD' : 'Prepaid') + ')';
    document.getElementById('rule-submit-label').textContent = 'UPDATE RULE';
    
    document.getElementById('rule-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeRuleModal() {
    document.getElementById('rule-modal').classList.add('hidden');
    document.body.style.overflow = '';
}

// ── Submit (add or update) ────────────────────────────────────────────────────
async function submitRuleForm() {
    const id = document.getElementById('rule-id').value;
    const ruleName = document.getElementById('rule-name').value;
    const fee = parseFloat(document.getElementById('rule-fee').value);
    const sla = parseInt(document.getElementById('rule-sla').value);
    const refund = 100; // Hardcoded fallback for database validation
    const pm = document.getElementById('rule-payment-method').value;
    const active = document.querySelector('input[name="rule_status"]:checked').value;

    if (!ruleName) { toast('Rule Name is required.', 'error'); return; }
    if (isNaN(fee) || isNaN(sla) || sla < 0 || fee < 0) {
        toast('Please fill all required fields correctly (Fee and SLA must be ≥ 0)', 'error');
        return;
    }

    const payload = {
        id: id,
        rule_name: ruleName,
        payment_method: pm,
        cancellation_fee: fee,
        refund_percentage: refund,
        sla_days: sla,
        is_active: parseInt(active)
    };

    const url = id
        ? '<?= BASE_URL ?>/admin/cancellations/rules/update'
        : '<?= BASE_URL ?>/admin/cancellations/rules/add';

    const btn   = document.getElementById('rule-submit-btn');
    const label = document.getElementById('rule-submit-label');
    const spin  = document.getElementById('rule-spinner');
    btn.disabled = true; spin.classList.remove('hidden');
    const origLabel = label.textContent;
    label.textContent = 'SAVING…';

    try {
        const res  = await fetch(url, {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest'},
            body: JSON.stringify(payload),
        });
        const data = await res.json();
        if (data.success) {
            toast(data.message, 'success');
            closeRuleModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            toast(data.message || 'Error saving rule.', 'error');
            btn.disabled = false; label.textContent = origLabel; spin.classList.add('hidden');
        }
    } catch(e) {
        toast('Network error.', 'error');
        btn.disabled = false; label.textContent = origLabel; spin.classList.add('hidden');
    }
}

// ── Toggle active ─────────────────────────────────────────────────────────────
async function toggleRule(id, btn) {
    try {
        const res  = await fetch('<?= BASE_URL ?>/admin/cancellations/rules/toggle', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest'},
            body: JSON.stringify({id}),
        });
        const data = await res.json();
        if (data.success) {
            const isActive = data.is_active === 1;
            btn.classList.toggle('bg-[#5c3d2e]', isActive);
            btn.classList.toggle('bg-gray-200', !isActive);
            btn.querySelector('span').classList.toggle('translate-x-4', isActive);
            btn.querySelector('span').classList.toggle('translate-x-1', !isActive);
            
            const card = document.getElementById('rule-card-' + id);
            card.classList.toggle('opacity-60', !isActive);
            card.classList.toggle('border-gray-100', !isActive);
            card.classList.toggle('border-gray-200', isActive);
            
            const txt = card.querySelector('.text-xs.font-bold');
            txt.textContent = isActive ? 'Active' : 'Inactive';
            txt.className = 'text-xs font-bold ' + (isActive ? 'text-emerald-600' : 'text-gray-400');
            
            toast(data.message, 'success');
        } else {
            toast(data.message || 'Toggle failed.', 'error');
        }
    } catch(e) { toast('Network error.', 'error'); }
}

// ── Delete ────────────────────────────────────────────────────────────────────
let _deleteId = null;
function deleteRule(id, name) {
    _deleteId = id;
    document.getElementById('delete-rule-name').textContent = '"' + name + '"';
    document.getElementById('delete-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
    document.body.style.overflow = '';
}
async function confirmDelete() {
    if (!_deleteId) return;
    document.getElementById('delete-confirm-btn').disabled = true;
    try {
        const res  = await fetch('<?= BASE_URL ?>/admin/cancellations/rules/delete', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest'},
            body: JSON.stringify({id: _deleteId}),
        });
        const data = await res.json();
        if (data.success) {
            toast('Rule deleted.', 'success');
            closeDeleteModal();
            const card = document.getElementById('rule-card-' + _deleteId);
            if (card) { card.style.opacity = '0'; card.style.transform = 'scale(0.95)'; setTimeout(() => card.remove(), 300); }
        } else {
            toast(data.message || 'Error.', 'error');
        }
    } catch(e) { toast('Network error.', 'error'); }
    document.getElementById('delete-confirm-btn').disabled = false;
}

// ── Toast ─────────────────────────────────────────────────────────────────────
function toast(msg, type='success') {
    const c  = document.getElementById('toast-box');
    const bg = type === 'success' ? 'bg-[#5c3d2e]' : 'bg-rose-600';
    const el = document.createElement('div');
    el.className = `pointer-events-auto px-5 py-3 rounded-lg shadow-lg text-sm font-semibold flex items-center gap-2 text-white ${bg} transform translate-y-4 opacity-0 transition-all duration-300`;
    el.innerHTML = `<span>${msg}</span>`;
    c.appendChild(el);
    requestAnimationFrame(() => el.classList.remove('translate-y-4','opacity-0'));
    setTimeout(() => { el.classList.add('opacity-0','translate-y-4'); setTimeout(() => el.remove(), 300); }, 3000);
}
// ── Global Cancellations Toggle ───────────────────────────────────────────────
async function toggleGlobalCancellations(enabled) {
    const toggle = document.getElementById('global-cancel-toggle');
    const label  = document.getElementById('global-toggle-label');
    const banner = document.getElementById('global-toggle-banner');
    toggle.disabled = true;
    try {
        const res  = await fetch('<?= BASE_URL ?>/admin/cancellations/rules/toggle-global', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ enabled }),
        });
        const data = await res.json();
        if (data.success) {
            label.textContent = enabled ? 'Enabled' : 'Disabled';
            if (enabled) {
                banner.className = banner.className.replace('bg-red-50 border-red-200','bg-emerald-50 border-emerald-200');
            } else {
                banner.className = banner.className.replace('bg-emerald-50 border-emerald-200','bg-red-50 border-red-200');
            }
            toast(data.message);
        } else {
            toggle.checked = !enabled; // revert
            toast(data.message || 'Error updating setting.', 'error');
        }
    } catch(e) {
        toggle.checked = !enabled;
        toast('Network error.', 'error');
    }
    toggle.disabled = false;
}

function exportCancellationRulesCSV() {
    let table = document.querySelector("table");
    if(!table) return;
    
    let rows = Array.from(table.querySelectorAll("tr"));
    let csvContent = "data:text/csv;charset=utf-8,";
    
    rows.forEach(row => {
        let cols = Array.from(row.querySelectorAll("th, td"));
        let rowData = cols.map(col => '"' + col.innerText.replace(/"/g, '""').trim() + '"');
        csvContent += rowData.join(",") + "\r\n";
    });
    
    let encodedUri = encodeURI(csvContent);
    let link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "cancellation_rules_export_<?= date('Y-m-d') ?>.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
</div>
