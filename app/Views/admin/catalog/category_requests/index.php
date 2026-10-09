<div class="w-full space-y-6">
    <!-- Header Section -->
    <div class="mb-8 relative z-10">
        <!-- Breadcrumbs -->
        <div class="text-sm font-medium text-gray-400 mb-2 font-serif tracking-wide">
            Dashboard <span class="mx-1 text-gray-300">›</span> Catalog Management <span class="mx-1 text-gray-300">›</span> <span class="text-brand-700">Category Requests</span>
        </div>
        
        <div class="flex justify-between items-center mb-6 w-full">
            <div>
                <h2 class="text-5xl font-display font-extrabold text-gray-900 tracking-tight">Category Approval Requests</h2>
                <p class="text-sm text-gray-500 mt-1">Review, approve, or reject new categories and subcategories proposed by vendors.</p>
            </div>
            
            <div class="flex items-center space-x-3 ml-auto">
                <a href="<?= BASE_URL ?>/admin/catalog/categories" class="bg-white hover:bg-gray-50 text-gray-700 px-6 py-3 rounded-full font-bold text-sm shadow-sm border border-gray-200 transition-all flex items-center uppercase tracking-widest">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    Manage Categories
                </a>
            </div>
        </div>
    </div>

    <!-- Stats & Tabs Bar -->
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Tabs -->
        <div class="flex items-center space-x-2 w-full md:w-auto overflow-x-auto">
            <a href="<?= BASE_URL ?>/admin/catalog/category-requests?status=pending" class="px-4 py-2 rounded-xl text-sm font-bold transition-colors flex items-center gap-2 whitespace-nowrap <?= ($currentStatus === 'pending') ? 'bg-[#F25996] text-white shadow-sm shadow-[#F25996]/30' : 'text-gray-600 hover:bg-gray-100' ?>">
                <span>⏳ Pending Review</span>
                <?php if ($pendingCount > 0): ?>
                    <span class="px-2 py-0.5 rounded-full text-xs font-black <?= ($currentStatus === 'pending') ? 'bg-white text-[#F25996]' : 'bg-pink-100 text-[#F25996]' ?>">
                        <?= $pendingCount ?>
                    </span>
                <?php endif; ?>
            </a>
            <a href="<?= BASE_URL ?>/admin/catalog/category-requests?status=approved" class="px-4 py-2 rounded-xl text-sm font-bold transition-colors flex items-center gap-2 whitespace-nowrap <?= ($currentStatus === 'approved') ? 'bg-[#F25996] text-white shadow-sm shadow-[#F25996]/30' : 'text-gray-600 hover:bg-gray-100' ?>">
                <span>✅ Approved</span>
            </a>
            <a href="<?= BASE_URL ?>/admin/catalog/category-requests?status=rejected" class="px-4 py-2 rounded-xl text-sm font-bold transition-colors flex items-center gap-2 whitespace-nowrap <?= ($currentStatus === 'rejected') ? 'bg-[#F25996] text-white shadow-sm shadow-[#F25996]/30' : 'text-gray-600 hover:bg-gray-100' ?>">
                <span>❌ Rejected</span>
            </a>
            <a href="<?= BASE_URL ?>/admin/catalog/category-requests?status=all" class="px-4 py-2 rounded-xl text-sm font-bold transition-colors flex items-center gap-2 whitespace-nowrap <?= ($currentStatus === 'all') ? 'bg-[#F25996] text-white shadow-sm shadow-[#F25996]/30' : 'text-gray-600 hover:bg-gray-100' ?>">
                <span>🌐 All Requests</span>
            </a>
        </div>

        <!-- Search Form -->
        <form method="GET" action="<?= BASE_URL ?>/admin/catalog/category-requests" class="w-full md:w-72 flex items-center">
            <input type="hidden" name="status" value="<?= htmlspecialchars($currentStatus) ?>">
            <div class="relative w-full">
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search category, vendor..." class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-brand-500 focus:border-brand-500 bg-gray-50/50">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </form>
    </div>

    <!-- Requests Table -->
    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Proposed Item</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Type / Parent</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Requested By (Vendor)</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tax & Details</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <?php if (!empty($requests)): ?>
                        <?php foreach($requests as $req): ?>
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-9 h-9 rounded-xl <?= $req['request_type'] === 'subcategory' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' ?> flex items-center justify-center font-bold text-sm mr-3">
                                            <?= strtoupper(substr($req['name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900"><?= htmlspecialchars($req['name']) ?></div>
                                            <div class="text-xs text-gray-400 font-mono">slug: <?= htmlspecialchars($req['slug']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($req['request_type'] === 'subcategory'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800">
                                            Subcategory
                                        </span>
                                        <div class="text-xs text-gray-500 mt-1">Parent: <strong class="text-gray-700"><?= htmlspecialchars($req['parent_category_name'] ?? 'N/A') ?></strong></div>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                            Main Category
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900"><?= htmlspecialchars($req['store_name'] ?? $req['vendor_name'] ?? 'Vendor') ?></div>
                                    <div class="text-xs text-gray-500 flex items-center gap-1.5 mt-0.5">
                                        <span class="bg-gray-100 text-gray-700 font-mono px-1.5 py-0.5 rounded font-bold"><?= htmlspecialchars($req['unique_vendor_id'] ?? 'VN'.$req['vendor_id']) ?></span>
                                        <span><?= htmlspecialchars($req['vendor_email']) ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs text-gray-700 space-y-1">
                                        <?php if (!empty($req['hsn_code'])): ?>
                                            <div>HSN: <strong class="font-mono"><?= htmlspecialchars($req['hsn_code']) ?></strong></div>
                                        <?php endif; ?>
                                        <?php if ((float)$req['sgst'] > 0 || (float)$req['cgst'] > 0): ?>
                                            <div>GST: <?= (float)$req['sgst'] ?>% SGST + <?= (float)$req['cgst'] ?>% CGST</div>
                                        <?php endif; ?>
                                        <?php if (!empty($req['attributes_json'])): 
                                            $parsedAttrs = json_decode($req['attributes_json'], true);
                                        ?>
                                            <?php if (is_array($parsedAttrs) && !empty($parsedAttrs)): ?>
                                                <div class="mt-1">
                                                    <span class="text-[11px] font-bold text-amber-800 bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200">
                                                        ✨ Proposed Attributes:
                                                    </span>
                                                    <div class="text-[11px] text-gray-600 mt-0.5 space-y-0.5">
                                                        <?php foreach($parsedAttrs as $pa): ?>
                                                            <div>
                                                                <strong class="text-gray-800"><?= htmlspecialchars($pa['name'] ?? '') ?>:</strong> 
                                                                <span class="text-gray-500"><?= htmlspecialchars(is_array($pa['values'] ?? null) ? implode(', ', $pa['values']) : ($pa['values'] ?? '')) ?></span>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if (!empty($req['description'])): ?>
                                            <div class="text-gray-500 truncate max-w-xs" title="<?= htmlspecialchars($req['description']) ?>">
                                                <?= htmlspecialchars($req['description']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($req['status'] === 'approved'): ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                            ✅ Approved
                                        </span>
                                        <?php if (!empty($req['reviewer_name'])): ?>
                                            <div class="text-[11px] text-gray-400 mt-0.5">by <?= htmlspecialchars($req['reviewer_name']) ?></div>
                                        <?php endif; ?>
                                    <?php elseif ($req['status'] === 'rejected'): ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200" title="<?= htmlspecialchars($req['rejection_reason'] ?? '') ?>">
                                            ❌ Rejected
                                        </span>
                                        <?php if (!empty($req['rejection_reason'])): ?>
                                            <div class="text-[11px] text-red-500 mt-0.5 max-w-[140px] truncate" title="<?= htmlspecialchars($req['rejection_reason']) ?>">
                                                <?= htmlspecialchars($req['rejection_reason']) ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                            ⏳ Pending
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                    <?= date('M d, Y', strtotime($req['created_at'])) ?>
                                    <div class="text-[11px] text-gray-400"><?= date('h:i A', strtotime($req['created_at'])) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <?php if ($req['status'] === 'pending'): ?>
                                        <div class="flex items-center justify-end gap-2">
                                            <!-- Approve Form -->
                                            <form action="<?= BASE_URL ?>/admin/catalog/category-requests/approve" method="POST" class="inline m-0" onsubmit="return confirmApprove(event, '<?= htmlspecialchars(addslashes($req['name'])) ?>')">
                                                <input type="hidden" name="id" value="<?= $req['id'] ?>">
                                                <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    Approve
                                                </button>
                                            </form>

                                            <!-- Reject Button -->
                                            <button type="button" onclick="promptReject(<?= $req['id'] ?>, '<?= htmlspecialchars(addslashes($req['name'])) ?>')" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 rounded-lg text-xs font-bold transition-colors flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                Reject
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400 italic">Processed</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <div class="max-w-sm mx-auto">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    <p class="font-bold text-gray-700">No category requests found</p>
                                    <p class="text-xs text-gray-400 mt-1">There are currently no <?= htmlspecialchars($currentStatus) ?> category requests matching your query.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Hidden Form for Rejecting -->
<form id="formRejectCategory" action="<?= BASE_URL ?>/admin/catalog/category-requests/reject" method="POST" class="hidden">
    <input type="hidden" name="id" id="reject_req_id">
    <input type="hidden" name="rejection_reason" id="reject_reason_input">
</form>

<script>
function confirmApprove(e, name) {
    e.preventDefault();
    const form = e.target;
    Swal.fire({
        title: 'Approve Category?',
        text: `Are you sure you want to approve "${name}"? It will be immediately activated and available in the catalog.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#059669',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Approve & Activate'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
    return false;
}

function promptReject(id, name) {
    Swal.fire({
        title: `Reject "${name}"?`,
        text: 'Please provide a brief reason for rejecting this category request:',
        input: 'textarea',
        inputPlaceholder: 'e.g. Category already covered under Footwear / Incorrect tax info...',
        inputAttributes: {
            'aria-label': 'Type your reason here'
        },
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Confirm Reject',
        preConfirm: (reason) => {
            if (!reason || !reason.trim()) {
                Swal.showValidationMessage('Please enter a rejection reason for the vendor.');
            }
            return reason;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('reject_req_id').value = id;
            document.getElementById('reject_reason_input').value = result.value.trim();
            document.getElementById('formRejectCategory').submit();
        }
    });
}
</script>
