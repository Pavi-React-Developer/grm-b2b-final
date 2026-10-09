<div class="mb-8 relative z-10">
    <!-- Breadcrumbs -->
    <div class="text-sm font-medium text-gray-400 mb-2 font-serif tracking-wide">
        Dashboard <span class="mx-1 text-gray-300">›</span> <?= $isVendor ? 'Vendor Portal' : 'Media Management' ?> <span class="mx-1 text-gray-300">›</span> <span class="text-brand-700">Media Assets</span>
    </div>
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-5xl font-display font-extrabold text-gray-900 tracking-tight">
                <?= $isVendor ? 'My Store Media Library' : 'Platform Media Manager' ?>
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                <?= $isVendor ? 'Upload and manage product photos, catalogs, and media assets for your store.' : 'Central media library with Cloudinary asset management, automatic deduplication, and vendor tracking.' ?>
            </p>
        </div>
        
        <div class="flex items-center space-x-3">
            <?php if ($this->hasPermission('media_manager', 'create')): ?>
            <button onclick="document.getElementById('upload-modal').classList.remove('hidden')" class="bg-[#F25996] hover:bg-[#e04481] text-white px-6 py-3 rounded-full font-bold text-xs shadow-sm transition-all flex items-center gap-2 uppercase tracking-wider">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Upload Media
            </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (!$isVendor): ?>
<!-- Admin Filter Bar -->
<div class="bg-white p-3 rounded-2xl border border-gray-100 shadow-sm mb-6 flex flex-wrap items-center justify-between gap-3">
    <div class="flex items-center space-x-2">
        <a href="<?= BASE_URL ?>/admin/media?scope=admin" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition <?= empty($currentVendorId) && ($currentScope === 'admin' || empty($currentScope)) ? 'bg-gray-900 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">
            🏢 In-House / Admin Direct (<?= ($currentScope === 'admin' || empty($currentScope)) && empty($currentVendorId) ? count($mediaFiles) : '' ?>)
        </a>
        <?php if (is_vendor_module_enabled()): ?>
        <a href="<?= BASE_URL ?>/admin/media?scope=vendors" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition <?= $currentScope === 'vendors' ? 'bg-[#F25996] text-white shadow-sm' : 'bg-pink-50 text-[#F25996] hover:bg-pink-100' ?>">
            🏬 Vendor Media
        </a>
        <a href="<?= BASE_URL ?>/admin/media?scope=all" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition <?= $currentScope === 'all' ? 'bg-gray-900 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">
            🌐 All Media
        </a>
        <?php endif; ?>
    </div>

    <?php if (is_vendor_module_enabled() && !empty($vendors)): ?>
    <div class="flex items-center space-x-2">
        <label for="vendor_filter" class="text-xs font-semibold text-gray-500">Filter by Vendor:</label>
        <select id="vendor_filter" onchange="if(this.value){ window.location.href='<?= BASE_URL ?>/admin/media?vendor_id=' + this.value; } else { window.location.href='<?= BASE_URL ?>/admin/media?scope=admin'; }" class="px-3 py-1.5 text-xs font-semibold border border-gray-200 rounded-xl bg-gray-50 focus:ring-brand-500 focus:border-brand-500">
            <option value="">Select Vendor...</option>
            <?php foreach ($vendors as $v): ?>
                <option value="<?= $v['id'] ?>" <?= (!empty($currentVendorId) && (int)$currentVendorId === (int)$v['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($v['store_name'] ?? $v['name']) ?> (ID: <?= htmlspecialchars($v['unique_vendor_id'] ?? 'VN'.$v['id']) ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- Upload Modal -->
<div id="upload-modal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-gray-900 text-base">Upload Media</h3>
            <button onclick="document.getElementById('upload-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="<?= BASE_URL ?>/admin/media/upload" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Select Image(s) / Video</label>
                <input type="file" name="images[]" multiple accept="image/*,video/*" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-pink-50 file:text-[#F25996] hover:file:bg-pink-100 border border-gray-200 rounded-xl p-2 bg-gray-50">
                <p class="text-[11px] text-gray-400 mt-1">Files are saved and organized automatically.</p>
            </div>
            <div class="pt-4 flex justify-end space-x-3 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('upload-modal').classList.add('hidden')" class="px-4 py-2 border border-gray-200 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-50 transition">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-[#F25996] text-white rounded-xl text-xs font-bold hover:bg-[#e04481] transition shadow">Upload</button>
            </div>
        </form>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <?php if (empty($mediaFiles)): ?>
        <div class="p-12 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <p class="font-semibold text-gray-600">No media files found.</p>
            <p class="text-xs text-gray-400 mt-1"><?= $isVendor ? 'Upload product photos and shop images using the button above.' : 'No in-house media files uploaded yet.' ?></p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 p-6">
            <?php foreach ($mediaFiles as $media): 
                $rawUrl = $media['cloudinary_url'] ?? '';
                $mediaUrl = (strpos($rawUrl, 'http://') === 0 || strpos($rawUrl, 'https://') === 0) ? $rawUrl : (BASE_URL . '/' . ltrim($rawUrl, '/'));
                $isVideo = preg_match('/\.(mp4|webm|ogg)$/i', $media['original_filename']) || strpos($rawUrl, '/video/upload/') !== false;
                $isVendorUpload = !empty($media['store_name']) || ($media['uploader_role'] === 'vendor');
            ?>
                <div class="border border-gray-200 rounded-2xl overflow-hidden bg-gray-50 relative group transition-all duration-200 hover:shadow-md hover:border-indigo-300 flex flex-col justify-between">
                    <div class="relative bg-white w-full h-40 overflow-hidden flex items-center justify-center">
                        <?php if($isVideo): ?>
                            <video src="<?= htmlspecialchars($mediaUrl) ?>" class="w-full h-40 object-cover bg-black" autoplay muted loop playsinline></video>
                        <?php else: ?>
                            <img src="<?= htmlspecialchars($mediaUrl) ?>" alt="<?= htmlspecialchars($media['original_filename']) ?>" class="w-full h-40 object-cover transition-transform duration-300 group-hover:scale-105" onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\' fill=\'%23cbd5e1\' viewBox=\'0 0 24 24\'><path d=\'M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z\'/></svg>';">
                        <?php endif; ?>

                        <!-- Uploader Badge for Admin -->
                        <?php if (!$isVendor): ?>
                            <div class="absolute bottom-2 left-2">
                                <?php if ($isVendorUpload): ?>
                                    <span class="inline-flex items-center gap-1 text-[9px] font-bold bg-indigo-600/90 text-white px-2 py-0.5 rounded-md backdrop-blur shadow-sm">
                                        🏪 <?= htmlspecialchars($media['store_name'] ?? $media['uploader_name'] ?? 'Vendor') ?>
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 text-[9px] font-bold bg-gray-900/80 text-white px-2 py-0.5 rounded-md backdrop-blur shadow-sm">
                                        🏢 In-House
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="p-3 bg-white border-t border-gray-100 flex-grow flex flex-col justify-between">
                        <div>
                            <p class="text-xs font-bold text-gray-900 truncate" title="<?= htmlspecialchars($media['original_filename']) ?>">
                                <?= htmlspecialchars($media['original_filename']) ?>
                            </p>
                            <p class="text-[10px] text-gray-400 mt-1 font-mono truncate" title="<?= htmlspecialchars($media['file_hash']) ?>">
                                <?= !empty($media['file_size']) ? number_format($media['file_size'] / 1024, 1) . ' KB' : 'Media Asset' ?>
                            </p>
                        </div>
                        <div class="flex items-center justify-between mt-2 pt-2 border-t border-gray-50 text-[10px] text-gray-400">
                            <span><?= date('M d, Y', strtotime($media['created_at'])) ?></span>
                            <a href="<?= htmlspecialchars($mediaUrl) ?>" target="_blank" class="text-indigo-600 hover:text-indigo-800 font-bold" title="Open Full View">View &nearr;</a>
                        </div>
                    </div>
                    
                    <?php if ($this->hasPermission('media_manager', 'delete')): ?>
                    <form action="<?= BASE_URL ?>/admin/media/delete" method="POST" class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <input type="hidden" name="id" value="<?= $media['id'] ?>">
                        <button type="submit" class="bg-red-500/90 hover:bg-red-600 text-white rounded-full p-1.5 shadow-md backdrop-blur transition-transform hover:scale-110" onclick="return confirm('Delete this media file?');" title="Delete media">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</div>
