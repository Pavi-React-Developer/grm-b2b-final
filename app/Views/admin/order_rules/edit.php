<div class="max-w-7xl mx-auto pb-12">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-4">
            <a href="<?= BASE_URL ?>/admin/order-rules" class="flex items-center justify-center w-10 h-10 bg-white border border-gray-200 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900 font-display">Edit Order Rule #<?= $rule['id'] ?></h2>
                <p class="text-sm text-gray-500 mt-1">Update rule details and category minimum amounts.</p>
            </div>
        </div>
    </div>

    <form action="<?= BASE_URL ?>/admin/order-rules/update" method="POST" id="ruleForm">
        <input type="hidden" name="id" value="<?= $rule['id'] ?>">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Form -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Section 1: Main Category -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center space-x-3">
                        <div class="flex items-center justify-center w-6 h-6 rounded-full bg-brand-600 text-white text-xs font-bold">1</div>
                        <h3 class="text-lg font-semibold text-gray-900">Main Category</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Main Category Dropdown -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Main Category <span class="text-red-500">*</span></label>
                                <select name="category_id" id="category_id" required class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-brand-500 focus:border-brand-500 text-sm px-4 py-2.5">
                                    <option value="">-- Select Category --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $rule['category_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <p class="text-xs text-gray-500 mt-2">This rule will apply to all items in this category.</p>
                            </div>
                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                                <select name="status" id="status" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-brand-500 focus:border-brand-500 text-sm px-4 py-2.5">
                                    <option value="active" <?= $rule['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="inactive" <?= $rule['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                </select>
                                <p class="text-xs text-gray-500 mt-2">Active rules will be applied to orders.</p>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Main Category Minimum Amount (₹) <span class="text-red-500">*</span></label>
                            <div class="relative max-w-md">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₹</span>
                                </div>
                                <input type="text" step="0.01" min="0.01" name="min_amount" id="min_amount" required class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-brand-500 focus:border-brand-500 text-sm" placeholder="e.g. 2000.00" value="<?= htmlspecialchars($rule['min_amount']) ?>">
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Users cannot checkout unless they have at least this amount from the main category.</p>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Secondary Categories -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center space-x-3">
                        <div class="flex items-center justify-center w-6 h-6 rounded-full bg-brand-600 text-white text-xs font-bold">2</div>
                        <h3 class="text-lg font-semibold text-gray-900">Secondary Category Minimum Amounts</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-600 mb-6" id="secondary_desc">Set minimum order amount for each secondary category</p>
                        
                        <?php $secondRules = json_decode($rule['second_category_rules'], true) ?? []; ?>

                        <!-- Table Layout for Added Categories -->
                        <div class="border border-gray-200 rounded-lg overflow-hidden" id="secondary_table_container">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">Secondary Category</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">Minimum Amount (₹)</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th scope="col" class="relative px-4 py-3 w-10"><span class="sr-only">Remove</span></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="secondary_tbody">
                                    <?php if (!empty($secondRules)): ?>
                                        <?php foreach ($secondRules as $scatId => $minAmount): ?>
                                            <?php 
                                                // Find name
                                                $catName = 'Category';
                                                foreach ($secondCategories as $sc) {
                                                    if ($sc['id'] == $scatId) {
                                                        $catName = $sc['name'];
                                                        break;
                                                    }
                                                }
                                            ?>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-8 w-8 rounded bg-brand-50 flex items-center justify-center text-brand-600 mr-3">
                                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                            </svg>
                                                        </div>
                                                        <select class="category-select block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-brand-500 focus:border-brand-500 sm:text-sm rounded-md" required>
                                                            <option value="">-- Select --</option>
                                                            <?php foreach ($secondCategories as $sub): ?>
                                                                <option value="<?= $sub['id'] ?>" <?= $sub['id'] == $scatId ? 'selected' : '' ?>><?= htmlspecialchars($sub['name']) ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="relative rounded-md shadow-sm">
                                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                            <span class="text-gray-500 sm:text-sm">₹</span>
                                                        </div>
                                                        <input type="text" step="0.01" min="0.01" name="second_category_amounts[<?= $scatId ?>]" value="<?= htmlspecialchars($minAmount) ?>" class="amount-input w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:ring-brand-500 focus:border-brand-500 sm:text-sm" placeholder="Amount" required>
                                                        <input type="hidden" name="second_category_ids[]" value="<?= $scatId ?>">
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="text-sm text-gray-500 row-desc">Minimum order amount for <?= htmlspecialchars($catName) ?> products</span>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <button type="button" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-1.5 rounded-md transition-colors remove-btn">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            
                            <!-- Add Button Row -->
                            <div class="p-4 bg-gray-50 border-t border-gray-200 text-center">
                                <button type="button" id="add_category_btn" class="inline-flex items-center px-4 py-2 text-sm font-medium text-brand-600 hover:text-brand-700 bg-white hover:bg-brand-50 rounded-md transition-colors border border-dashed border-brand-300 w-full justify-center">
                                    <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Add Secondary Category
                                </button>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-4">
                    <a href="<?= BASE_URL ?>/admin/order-rules" class="px-6 py-2.5 border border-gray-300 text-gray-700 bg-white rounded-lg hover:bg-gray-50 font-medium transition-colors text-sm">Cancel</a>
                    <button type="submit" class="px-6 py-2.5 bg-brand-600 text-white rounded-lg hover:bg-brand-700 font-medium transition-colors shadow-sm text-sm inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Update Rule
                    </button>
                </div>
                
            </div>
            
            <!-- Right Column: Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Summary Card -->
                <div class="bg-brand-50/50 rounded-xl border border-brand-100 p-6 sticky top-6">
                    <div class="flex items-center space-x-2 mb-4">
                        <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <h3 class="font-semibold text-gray-900 text-lg">Rule Summary</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Main Category</p>
                            <p class="text-sm font-semibold text-gray-900" id="summary_main_cat">Not Selected</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Status</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800" id="summary_status">Active</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Secondary Categories</p>
                            <p class="text-sm font-semibold text-gray-900" id="summary_sec_count">0 categories</p>
                        </div>
                        <div class="pt-3 border-t border-brand-200">
                            <p class="text-xs text-gray-500 mb-1">Total Minimum Amount Range</p>
                            <p class="text-lg font-bold text-brand-600" id="summary_total_range">₹0.00</p>
                        </div>
                    </div>
                </div>
                
                <!-- How it works -->
                <div class="bg-blue-50 rounded-xl border border-blue-100 p-6">
                    <div class="flex items-center space-x-2 mb-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h3 class="font-semibold text-blue-900 text-sm">How it works</h3>
                    </div>
                    <p class="text-sm text-blue-800 leading-relaxed">
                        Each secondary category has its own minimum order amount. Orders containing items from the main category must meet or exceed the minimum amount for the main category, AND meet the specified minimums for any added secondary categories.
                    </p>
                </div>
            </div>
            
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category_id');
    const minAmountInput = document.getElementById('min_amount');
    const statusSelect = document.getElementById('status');
    const container = document.getElementById('secondary_table_container');
    const tbody = document.getElementById('secondary_tbody');
    const addBtn = document.getElementById('add_category_btn');
    const secondaryDesc = document.getElementById('secondary_desc');
    
    // Summary elements
    const sumMainCat = document.getElementById('summary_main_cat');
    const sumStatus = document.getElementById('summary_status');
    const sumSecCount = document.getElementById('summary_sec_count');
    const sumTotalRange = document.getElementById('summary_total_range');

    let availableSubcategories = <?= json_encode($secondCategories) ?>;

    // Update Summary Sidebar
    function updateSummary() {
        // Main Cat
        const selectedMain = categorySelect.options[categorySelect.selectedIndex];
        sumMainCat.textContent = selectedMain && selectedMain.value ? selectedMain.textContent : 'Not Selected';
        
        // Status
        sumStatus.textContent = statusSelect.options[statusSelect.selectedIndex].text;
        if(statusSelect.value === 'active') {
            sumStatus.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800';
        } else {
            sumStatus.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800';
        }

        // Secondary Categories Count
        const rows = tbody.querySelectorAll('tr');
        sumSecCount.textContent = `${rows.length} categories`;

        // Total Range Calculation
        const mainMin = parseFloat(minAmountInput.value) || 0;
        let minVal = 0, maxVal = 0;
        if(rows.length > 0) {
            let secValues = Array.from(rows).map(r => parseFloat(r.querySelector('input[type="text"]').value) || 0);
            minVal = Math.min(...secValues);
            maxVal = Math.max(...secValues);
        }

        if(rows.length === 0) {
            sumTotalRange.textContent = `₹${mainMin.toFixed(2)}`;
        } else if (minVal === maxVal) {
            sumTotalRange.textContent = `₹${minVal.toFixed(2)}`;
        } else {
            sumTotalRange.textContent = `₹${minVal.toFixed(2)} - ₹${maxVal.toFixed(2)}`;
        }
    }

    // Bind Summary Updates
    categorySelect.addEventListener('change', updateSummary);
    statusSelect.addEventListener('change', updateSummary);
    minAmountInput.addEventListener('input', updateSummary);
    tbody.addEventListener('input', updateSummary);
    tbody.addEventListener('change', updateSummary);

    // Initial load update
    updateSummary();

    // Fetch Subcategories
    categorySelect.addEventListener('change', function() {
        const catId = this.value;
        const catName = this.options[this.selectedIndex].text;
        
        tbody.innerHTML = '';
        availableSubcategories = [];
        updateSummary();

        if (catId) {
            secondaryDesc.textContent = `Loading secondary categories for ${catName}...`;
            container.classList.add('hidden');

            fetch(`<?= BASE_URL ?>/api/subcategories?category_id=${catId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success' && data.data.length > 0) {
                        availableSubcategories = data.data;
                        secondaryDesc.textContent = `Set minimum order amount for each secondary category under ${catName}`;
                        container.classList.remove('hidden');
                    } else {
                        secondaryDesc.textContent = `No secondary categories found under ${catName}.`;
                    }
                })
                .catch(error => {
                    console.error('Error fetching second categories:', error);
                    secondaryDesc.textContent = 'Error loading secondary categories.';
                });
        } else {
            secondaryDesc.textContent = 'Select a main category first to see available secondary categories.';
            container.classList.add('hidden');
        }
    });

    // Add Row
    addBtn.addEventListener('click', function() {
        if(availableSubcategories.length === 0) return;

        const tr = document.createElement('tr');
        
        // Generate options
        let optionsHtml = '<option value="">-- Select --</option>';
        availableSubcategories.forEach(sub => {
            optionsHtml += `<option value="${sub.id}">${sub.name}</option>`;
        });

        tr.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8 rounded bg-brand-50 flex items-center justify-center text-brand-600 mr-3">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <select class="category-select block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-brand-500 focus:border-brand-500 sm:text-sm rounded-md" required>
                        ${optionsHtml}
                    </select>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">₹</span>
                    </div>
                    <input type="text" step="0.01" min="0.01" class="amount-input w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:ring-brand-500 focus:border-brand-500 sm:text-sm" placeholder="Amount" disabled required>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="text-sm text-gray-500 row-desc">Select a category...</span>
            </td>
            <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button type="button" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-1.5 rounded-md transition-colors remove-btn">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
        updateSummary();
    });

    // Handle Row Interactions
    tbody.addEventListener('change', function(e) {
        if(e.target.classList.contains('category-select')) {
            const tr = e.target.closest('tr');
            const select = e.target;
            const input = tr.querySelector('.amount-input');
            const desc = tr.querySelector('.row-desc');
            const catId = select.value;
            const catName = select.options[select.selectedIndex].text;

            // Remove old hidden input if exists
            const oldHidden = tr.querySelector('input[type="hidden"]');
            if(oldHidden) oldHidden.remove();

            if(catId) {
                input.name = `second_category_amounts[${catId}]`;
                input.disabled = false;
                desc.textContent = `Minimum order amount for ${catName} products`;
                
                // Add hidden input for second_category_ids[]
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'second_category_ids[]';
                hiddenInput.value = catId;
                tr.appendChild(hiddenInput);
            } else {
                input.name = '';
                input.disabled = true;
                desc.textContent = 'Select a category...';
            }
        }
    });

    tbody.addEventListener('click', function(e) {
        const removeBtn = e.target.closest('.remove-btn');
        if(removeBtn) {
            removeBtn.closest('tr').remove();
            updateSummary();
        }
    });

});
</script>
