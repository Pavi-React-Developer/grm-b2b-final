<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Add Forms Column -->
    <div class="space-y-6">
        <?php if ($this->hasPermission('categories', 'create')): ?>
        <!-- Add Category Form -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Add Category</h3>
            <form action="<?= BASE_URL ?>/admin/catalog/category" method="POST">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Category Name</label>
                    <input type="text" id="name" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm px-3 py-2 border">
                </div>
                <div class="mb-4">
                    <label for="moq" class="block text-sm font-medium text-gray-700">Default MOQ (Pieces)</label>
                    <input type="text" id="moq" name="moq" value="10" required min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm px-3 py-2 border">
                </div>
                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-slate-900 border border-transparent rounded-lg font-medium text-white hover:bg-slate-800 transition-colors">
                    Save Category
                </button>
            </form>
        </div>
        <?php endif; ?>

        <?php if ($this->hasPermission('products', 'create')): ?>
        <!-- Add Product Form -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Add Product</h3>
            <form action="<?= BASE_URL ?>/admin/catalog/product" method="POST">
                <div class="mb-4">
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                    <select id="category_id" name="category_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm px-3 py-2 border">
                        <option value="">Select a category</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?> (MOQ: <?= $cat['moq'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="product_name" class="block text-sm font-medium text-gray-700">Product Name</label>
                    <input type="text" id="product_name" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm px-3 py-2 border">
                </div>
                <div class="mb-4">
                    <label for="wholesale_price" class="block text-sm font-medium text-gray-700">Wholesale Price (₹)</label>
                    <input type="text" step="0.01" id="wholesale_price" name="wholesale_price" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm px-3 py-2 border">
                </div>
                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-brand-600 border border-transparent rounded-lg font-medium text-white hover:bg-brand-700 transition-colors" <?= empty($categories) ? 'disabled title="Create a category first"' : '' ?>>
                    Save Product
                </button>
            </form>
        </div>
        <?php endif; ?>
    </div>

    <!-- Lists Column -->
    <div class="lg:col-span-2 space-y-6">
        
        <?php if ($this->hasPermission('products', 'view')): ?>
        <!-- Products List -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Products</h3>
                <span class="text-sm text-gray-500"><?= count($products) ?> Total</span>
            </div>
            
            <?php if (empty($products)): ?>
                <div class="p-8 text-center text-gray-500">No products added yet.</div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white border-b border-gray-200 text-xs uppercase text-gray-500 font-semibold">
                                <th class="px-6 py-3">Product Name</th>
                                <th class="px-6 py-3">Category</th>
                                <th class="px-6 py-3">B2B Price</th>
                                <th class="px-6 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach($products as $product): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 text-sm font-medium text-gray-900"><?= htmlspecialchars($product['name']) ?></td>
                                    <td class="px-6 py-3 text-sm text-gray-500"><?= htmlspecialchars($product['category_name']) ?></td>
                                    <td class="px-6 py-3 text-sm text-gray-900 font-semibold">₹<?= number_format($product['wholesale_price']) ?></td>
                                    <td class="px-6 py-3 text-sm text-gray-500">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium <?= $product['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                                            <?= ucfirst($product['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if ($this->hasPermission('categories', 'view')): ?>
        <!-- Categories List -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Categories</h3>
                <span class="text-sm text-gray-500"><?= count($categories) ?> Total</span>
            </div>
            
            <?php if (empty($categories)): ?>
                <div class="p-8 text-center text-gray-500">No categories added yet.</div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white border-b border-gray-200 text-xs uppercase text-gray-500 font-semibold">
                                <th class="px-6 py-3">Category Name</th>
                                <th class="px-6 py-3">Base MOQ</th>
                                <th class="px-6 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach($categories as $category): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 text-sm font-medium text-gray-900"><?= htmlspecialchars($category['name']) ?></td>
                                    <td class="px-6 py-3 text-sm text-gray-500"><?= $category['moq'] ?> pieces</td>
                                    <td class="px-6 py-3 text-sm text-gray-500">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium <?= $category['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                                            <?= ucfirst($category['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </div>
</div>
