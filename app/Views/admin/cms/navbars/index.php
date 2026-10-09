<div class="mb-8 relative z-10">
    <!-- Breadcrumbs -->
    <div class="text-sm font-medium text-gray-400 mb-2 font-serif tracking-wide">
        Dashboard <span class="mx-1 text-gray-300">›</span> CMS Management <span class="mx-1 text-gray-300">›</span> <span class="text-brand-700">Navbars</span>
    </div>
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-5xl font-display font-extrabold text-gray-900 tracking-tight">Navbars</h2>
            <p class="text-sm text-gray-500 mt-1">Manage your storefront navbar configurations.</p>
        </div>
        <a href="<?= BASE_URL ?>/admin/cms/navbars/create" class="bg-[#F25996] hover:bg-[#e04481] text-white px-6 py-3 rounded-full font-bold text-xs shadow-sm transition-all flex items-center gap-2 uppercase tracking-wider">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add Navbar
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    <?php if(empty($navbars)): ?>
        <div class="col-span-full py-12 text-center text-gray-400 bg-white rounded-[2rem] border border-gray-100 shadow-sm">
            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            <p class="text-lg font-medium text-gray-500">No Navbars Found</p>
            <p class="text-sm mt-1">Click "Add Navbar" to create your first configuration.</p>
        </div>
    <?php else: ?>
        <?php foreach($navbars as $navbar): 
            $content = $navbar['content'];
            $title = $content['title'] ?? 'Untitled Navbar';
            $isActive = $navbar['is_active'];
        ?>
        <div class="bg-white rounded-[1.5rem] overflow-hidden shadow-custom border border-gray-100 flex flex-col transition-transform hover:-translate-y-1 duration-300">
            <!-- Details Area -->
            <div class="p-5 flex-1 flex flex-col">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h3 class="font-serif font-bold text-gray-900 text-lg line-clamp-1"><?= htmlspecialchars($title) ?></h3>
                    </div>
                    <?php if($isActive): ?>
                        <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Active</span>
                    <?php else: ?>
                        <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Inactive</span>
                    <?php endif; ?>
                </div>
                
                <div class="mt-5 pt-4 border-t border-gray-100 flex items-center justify-between">
                    <!-- Actions -->
                    <div class="flex items-center gap-3">
                        <a href="<?= BASE_URL ?>/admin/cms/navbars/edit?id=<?= $navbar['id'] ?>" class="text-blue-500 hover:text-blue-700 transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        <form action="<?= BASE_URL ?>/admin/cms/navbars/delete" method="POST" class="inline delete-form">
                            <input type="hidden" name="id" value="<?= $navbar['id'] ?>">
                            <button type="submit" class="text-red-500 hover:text-red-700 transition-colors" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
.font-serif {
    font-family: 'Playfair Display', serif;
}
.shadow-custom { box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
</style>
