<?php
// Vendor & Admin Bulk CSV Import / Export View
?>
<div class="p-6 max-w-7xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-2xl border border-gray-200/80 shadow-2xs flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Bulk Catalog & Stock CSV Upload</h1>
            <p class="text-xs text-gray-500 mt-1">Onboard hundreds of B2B products or update stock quantities in bulk via CSV spreadsheets</p>
        </div>
        <a href="<?= BASE_URL ?>/assets/templates/grm_b2b_product_import_template.csv" download class="px-4 py-2.5 bg-stone-100 hover:bg-stone-200 text-stone-800 font-bold text-xs rounded-xl border border-stone-300 flex items-center gap-2 transition">
            <svg class="w-4 h-4 text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Download CSV Template
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Card 1: Bulk Create New Products -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200/80 shadow-2xs space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <div>
                    <h3 class="font-extrabold text-sm text-gray-900">Bulk Product Import</h3>
                    <p class="text-[11px] text-gray-500">Create new products, MOQs, base prices & taxes</p>
                </div>
            </div>

            <form action="<?= BASE_URL ?>/admin/catalog/import-products" method="POST" enctype="multipart/form-data" class="space-y-4 pt-2">
                <div class="border-2 border-dashed border-gray-200 rounded-2xl p-6 text-center hover:border-indigo-400 transition bg-gray-50/50">
                    <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <label class="cursor-pointer text-xs font-bold text-indigo-600 hover:underline">
                        <span>Choose CSV File</span>
                        <input type="file" name="csv_file" accept=".csv" required class="hidden">
                    </label>
                    <p class="text-[10px] text-gray-400 mt-1">Only .csv files up to 10MB supported</p>
                </div>
                <button type="submit" class="w-full py-3 bg-[#F25996] hover:bg-[#e04481] text-white font-bold text-xs rounded-xl shadow-sm transition">
                    Upload & Process Products CSV
                </button>
            </form>
        </div>

        <!-- Card 2: Bulk Inventory/Stock Update -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200/80 shadow-2xs space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div>
                    <h3 class="font-extrabold text-sm text-gray-900">Bulk Stock Quantity Sync</h3>
                    <p class="text-[11px] text-gray-500">Update warehouse stock levels by SKU ID</p>
                </div>
            </div>

            <form action="<?= BASE_URL ?>/admin/catalog/sync-stock" method="POST" enctype="multipart/form-data" class="space-y-4 pt-2">
                <div class="border-2 border-dashed border-gray-200 rounded-2xl p-6 text-center hover:border-emerald-400 transition bg-gray-50/50">
                    <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <label class="cursor-pointer text-xs font-bold text-emerald-600 hover:underline">
                        <span>Choose Inventory CSV</span>
                        <input type="file" name="stock_file" accept=".csv" required class="hidden">
                    </label>
                    <p class="text-[10px] text-gray-400 mt-1">Only .csv files up to 10MB supported</p>
                </div>
                <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                    Sync Warehouse Stock CSV
                </button>
            </form>
        </div>
    </div>
</div>
