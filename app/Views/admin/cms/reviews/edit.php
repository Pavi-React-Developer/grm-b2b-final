<?php
$content = $section['content'] ?? [];
$selectedIds = $content['selected_review_ids'] ?? [];
$curAnimation = $content['animation'] ?? 'fade';
?>
<div class="mb-6 relative z-10">
    <!-- Breadcrumbs -->
    <div class="text-sm font-medium text-gray-400 mb-2 font-serif tracking-wide">
        Dashboard <span class="mx-1 text-gray-300">›</span> CMS Management <span class="mx-1 text-gray-300">›</span> <a href="<?= BASE_URL ?>/admin/cms/reviews" class="hover:text-brand-700">Reviews</a> <span class="mx-1 text-gray-300">›</span> <span class="text-brand-700">Edit Review Section</span>
    </div>
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-3xl sm:text-4xl font-display font-extrabold text-gray-900 tracking-tight">Edit Customer Review Section</h2>
            <p class="text-sm text-gray-500 mt-1">Section ID: <code class="bg-gray-100 text-gray-800 px-2 py-0.5 rounded text-xs font-mono"><?= htmlspecialchars($section['id']) ?></code></p>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?= BASE_URL ?>/admin/cms/reviews" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-full font-bold text-xs shadow-sm transition-all flex items-center gap-2 uppercase tracking-wider">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to List
            </a>
        </div>
    </div>
</div>

<div class="w-full">
    <form id="reviewForm" action="<?= BASE_URL ?>/admin/cms/reviews/update" method="POST" class="bg-white rounded-[1.5rem] shadow-custom border border-gray-100 p-6 sm:p-8 space-y-8">
        <input type="hidden" name="id" value="<?= htmlspecialchars($section['id']) ?>">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left & Center: Configuration & Review Selection (2 Cols) -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Section 1: General Information -->
                <div class="border border-gray-200/80 rounded-2xl p-6 bg-gray-50/40">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200/70">
                        <div class="w-10 h-10 rounded-xl bg-pink-50 text-[#F25996] flex items-center justify-center font-bold">
                            1
                        </div>
                        <div>
                            <h3 class="font-serif font-bold text-gray-900 text-lg">Section Header & Content</h3>
                            <p class="text-xs text-gray-500">Define the heading and subheading for this review section.</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">Section Title</label>
                                <span class="text-[11px] font-medium text-gray-400 lowercase italic">(optional)</span>
                            </div>
                            <input type="text" name="title" value="<?= htmlspecialchars($content['title'] ?? '') ?>" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#F25996] focus:border-transparent text-sm text-gray-800" placeholder="e.g. Loved by 10,000+ Happy Customers (Optional)">
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">Section Subtitle</label>
                                <span class="text-[11px] font-medium text-gray-400 lowercase italic">(optional)</span>
                            </div>
                            <input type="text" name="subtitle" value="<?= htmlspecialchars($content['subtitle'] ?? '') ?>" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#F25996] focus:border-transparent text-sm text-gray-800" placeholder="Brief supporting description (Optional)">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Animation & Transition Effects -->
                <div class="border border-gray-200/80 rounded-2xl p-6 bg-gray-50/40">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200/70">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                            2
                        </div>
                        <div>
                            <h3 class="font-serif font-bold text-gray-900 text-lg">Animation Style</h3>
                            <p class="text-xs text-gray-500">Choose the slide transition effect for the customer reviews carousel.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Option: Fade -->
                        <label class="relative flex flex-col p-4 border-2 rounded-2xl cursor-pointer transition-all hover:border-[#F25996] has-[:checked]:border-[#F25996] has-[:checked]:bg-white bg-white/70 border-gray-200">
                            <input type="radio" name="animation" value="fade" <?= $curAnimation === 'fade' ? 'checked' : '' ?> class="sr-only">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-bold text-sm text-gray-900 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#F25996]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Fade
                                </span>
                                <span class="w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center radio-dot"></span>
                            </div>
                            <p class="text-xs text-gray-500">Smooth opacity fade in and out as cards transition.</p>
                        </label>

                        <!-- Option: Slide Right -->
                        <label class="relative flex flex-col p-4 border-2 rounded-2xl cursor-pointer transition-all hover:border-[#F25996] has-[:checked]:border-[#F25996] has-[:checked]:bg-white bg-white/70 border-gray-200">
                            <input type="radio" name="animation" value="slide_right" <?= $curAnimation === 'slide_right' ? 'checked' : '' ?> class="sr-only">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-bold text-sm text-gray-900 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    Slide Right
                                </span>
                                <span class="w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center radio-dot"></span>
                            </div>
                            <p class="text-xs text-gray-500">Glides dynamically towards the right on slide change.</p>
                        </label>

                        <!-- Option: Slide Left -->
                        <label class="relative flex flex-col p-4 border-2 rounded-2xl cursor-pointer transition-all hover:border-[#F25996] has-[:checked]:border-[#F25996] has-[:checked]:bg-white bg-white/70 border-gray-200">
                            <input type="radio" name="animation" value="slide_left" <?= $curAnimation === 'slide_left' ? 'checked' : '' ?> class="sr-only">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-bold text-sm text-gray-900 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                                    Slide Left
                                </span>
                                <span class="w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center radio-dot"></span>
                            </div>
                            <p class="text-xs text-gray-500">Glides dynamically towards the left on slide change.</p>
                        </label>

                        <!-- Option: Zoom -->
                        <label class="relative flex flex-col p-4 border-2 rounded-2xl cursor-pointer transition-all hover:border-[#F25996] has-[:checked]:border-[#F25996] has-[:checked]:bg-white bg-white/70 border-gray-200">
                            <input type="radio" name="animation" value="zoom" <?= $curAnimation === 'zoom' ? 'checked' : '' ?> class="sr-only">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-bold text-sm text-gray-900 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"></path></svg>
                                    Zoom
                                </span>
                                <span class="w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center radio-dot"></span>
                            </div>
                            <p class="text-xs text-gray-500">Expands smoothly into focus with a gentle scale pop effect.</p>
                        </label>
                    </div>
                </div>

                <!-- Section 3: Review Selection Table -->
                <div class="border border-gray-200/80 rounded-2xl p-6 bg-gray-50/40">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 pb-4 border-b border-gray-200/70">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                                3
                            </div>
                            <div>
                                <h3 class="font-serif font-bold text-gray-900 text-lg">Select Customer Reviews</h3>
                                <p class="text-xs text-gray-500">Only checked reviews will be published and rendered on the landing page carousel.</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" id="selectAllReviews" class="px-3 py-1.5 rounded-lg border border-gray-200 bg-white text-xs font-bold text-gray-700 hover:bg-gray-100 transition-colors">Select All</button>
                            <button type="button" id="deselectAllReviews" class="px-3 py-1.5 rounded-lg border border-gray-200 bg-white text-xs font-bold text-gray-500 hover:bg-gray-100 transition-colors">Clear</button>
                        </div>
                    </div>

                    <!-- Search Reviews input -->
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" id="reviewSearchInput" placeholder="Search by reviewer name, product, or review text..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-white text-xs focus:outline-none focus:ring-2 focus:ring-[#F25996] focus:border-transparent">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>

                    <!-- Reviews list / table -->
                    <?php if(empty($reviews)): ?>
                        <div class="py-10 text-center text-gray-400 bg-white rounded-2xl border border-dashed border-gray-200">
                            <p class="text-sm font-medium text-gray-500">No customer reviews found in database.</p>
                            <p class="text-xs text-gray-400 mt-1">Customers submit reviews after receiving their orders.</p>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm">
                            <table class="w-full text-left border-collapse" id="reviewsTable">
                                <thead>
                                    <tr class="border-b border-gray-200 text-[11px] font-bold text-gray-400 uppercase tracking-wider bg-gray-50/70">
                                        <th class="py-3 px-3 w-10 text-center">
                                            <input type="checkbox" id="masterCheckbox" class="rounded text-[#F25996] focus:ring-[#F25996]">
                                        </th>
                                        <th class="py-3 px-3">Reviewer</th>
                                        <th class="py-3 px-3">Product</th>
                                        <th class="py-3 px-3">Rating & Feedback</th>
                                        <th class="py-3 px-3 text-right">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-xs">
                                    <?php foreach($reviews as $rev): 
                                        $rating = (int)$rev['rating'];
                                        $img = !empty($rev['product_image']) ? $rev['product_image'] : 'https://placehold.co/100x100?text=Product';
                                        if (!str_starts_with($img, 'http') && !str_starts_with($img, '/')) {
                                            $img = '/' . ltrim($img, '/');
                                        }
                                        $isChecked = in_array((int)$rev['id'], array_map('intval', $selectedIds));
                                    ?>
                                    <tr class="review-row hover:bg-pink-50/30 transition-colors">
                                        <td class="py-3 px-3 text-center">
                                            <input type="checkbox" name="selected_review_ids[]" value="<?= $rev['id'] ?>" <?= $isChecked ? 'checked' : '' ?> class="review-chk rounded text-[#F25996] focus:ring-[#F25996] w-4 h-4">
                                        </td>
                                        <td class="py-3 px-3 font-medium text-gray-900 whitespace-nowrap">
                                            <div class="font-bold text-gray-800 reviewer-name"><?= htmlspecialchars($rev['reviewer_name'] ?: 'Verified Customer') ?></div>
                                            <div class="text-[10px] text-gray-400"><?= htmlspecialchars($rev['reviewer_email'] ?? '') ?></div>
                                        </td>
                                        <td class="py-3 px-3">
                                            <div class="flex items-center gap-2 max-w-[200px]">
                                                <img src="<?= htmlspecialchars($img) ?>" class="w-8 h-8 rounded-lg object-cover border border-gray-100 shrink-0" alt="Product">
                                                <span class="truncate text-[11px] text-gray-700 font-medium product-name"><?= htmlspecialchars($rev['product_name'] ?? 'Product #' . $rev['product_id']) ?></span>
                                            </div>
                                        </td>
                                        <td class="py-3 px-3">
                                            <div class="flex items-center gap-1 text-amber-400 mb-0.5">
                                                <?php for($i=1; $i<=5; $i++): ?>
                                                    <svg class="w-3.5 h-3.5 <?= $i <= $rating ? 'fill-current' : 'text-gray-200 fill-current' ?>" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                <?php endfor; ?>
                                                <span class="text-[11px] font-bold text-gray-600 ml-1"><?= number_format($rating, 1) ?></span>
                                            </div>
                                            <p class="text-gray-600 italic line-clamp-1 review-text text-[11px]">"<?= htmlspecialchars($rev['comment'] ?? 'No comment') ?>"</p>
                                        </td>
                                        <td class="py-3 px-3 text-right">
                                            <?php if(!empty($rev['is_approved'])): ?>
                                                <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold px-2 py-0.5 rounded-full">Approved</span>
                                            <?php else: ?>
                                                <span class="bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold px-2 py-0.5 rounded-full">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

            <!-- Right Column: Carousel Settings & Colors (1 Col) -->
            <div class="space-y-6">
                
                <!-- Publish / Active Toggle -->
                <div class="border border-gray-200/80 rounded-2xl p-6 bg-gray-50/40">
                    <h4 class="font-serif font-bold text-gray-900 text-base mb-4">Publish Status</h4>
                    
                    <div class="flex items-center justify-between p-3.5 bg-white rounded-xl border border-gray-200/80 shadow-sm">
                        <div>
                            <span class="text-xs font-bold text-gray-800 block">Active Status</span>
                            <span class="text-[11px] text-gray-500">Enable on storefront landing page</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" <?= !empty($section['is_active']) ? 'checked' : '' ?> class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#F25996]"></div>
                        </label>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200/70">
                        <p class="text-[11px] text-gray-400">
                            Once saved, this review component will be visible in the <a href="<?= BASE_URL ?>/admin/cms/builder" class="text-blue-600 underline font-semibold">Layout Builder</a> where you can adjust its position on the homepage.
                        </p>
                    </div>
                </div>

                <!-- Carousel Display Settings -->
                <div class="border border-gray-200/80 rounded-2xl p-6 bg-gray-50/40 space-y-5">
                    <h4 class="font-serif font-bold text-gray-900 text-base pb-3 border-b border-gray-200/70">Carousel Controls</h4>
                    
                    <!-- Autoplay Toggle -->
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-gray-700 block">Auto-scroll Carousel</span>
                            <span class="text-[11px] text-gray-400">Slides automatically advance</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="autoplay" value="1" <?= !empty($content['autoplay']) ? 'checked' : '' ?> class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#F25996]"></div>
                        </label>
                    </div>

                    <!-- Autoplay Speed -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Slide Interval (Seconds)</label>
                        <input type="number" name="autoplay_speed" value="<?= (int)($content['autoplay_speed'] ?? 4) ?>" min="2" max="15" class="w-full px-3 py-2 rounded-xl border border-gray-200 bg-white text-xs focus:ring-2 focus:ring-[#F25996] focus:border-transparent">
                    </div>

                    <!-- Desktop Cards Count -->
                    <?php $cardsD = (int)($content['cards_desktop'] ?? 3); ?>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Cards Visible on Desktop</label>
                        <select name="cards_desktop" class="w-full px-3 py-2 rounded-xl border border-gray-200 bg-white text-xs focus:ring-2 focus:ring-[#F25996] focus:border-transparent">
                            <option value="1" <?= $cardsD === 1 ? 'selected' : '' ?>>1 Card (Full Focus)</option>
                            <option value="2" <?= $cardsD === 2 ? 'selected' : '' ?>>2 Cards (Side by side)</option>
                            <option value="3" <?= $cardsD === 3 ? 'selected' : '' ?>>3 Cards (Recommended)</option>
                            <option value="4" <?= $cardsD === 4 ? 'selected' : '' ?>>4 Cards (Compact)</option>
                        </select>
                    </div>

                    <!-- Tablet Cards Count -->
                    <?php $cardsT = (int)($content['cards_tablet'] ?? 2); ?>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Cards Visible on Tablet</label>
                        <select name="cards_tablet" class="w-full px-3 py-2 rounded-xl border border-gray-200 bg-white text-xs focus:ring-2 focus:ring-[#F25996] focus:border-transparent">
                            <option value="1" <?= $cardsT === 1 ? 'selected' : '' ?>>1 Card</option>
                            <option value="2" <?= $cardsT === 2 ? 'selected' : '' ?>>2 Cards</option>
                        </select>
                    </div>

                    <!-- Show Product Info -->
                    <div class="flex items-center justify-between pt-2">
                        <div>
                            <span class="text-xs font-bold text-gray-700 block">Show Product Badge</span>
                            <span class="text-[11px] text-gray-400">Display item name & image</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="show_product" value="1" <?= !empty($content['show_product']) ? 'checked' : '' ?> class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#F25996]"></div>
                        </label>
                    </div>

                    <!-- Show Verified Badge -->
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-gray-700 block">Verified Buyer Badge</span>
                            <span class="text-[11px] text-gray-400">Green checkmark badge</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="show_verified" value="1" <?= !empty($content['show_verified']) ? 'checked' : '' ?> class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#F25996]"></div>
                        </label>
                    </div>

                    <!-- Show Navigation Arrows (Left & Right) -->
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-gray-700 block">Show Navigation Arrows</span>
                            <span class="text-[11px] text-gray-400">Left & right carousel arrow buttons</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="show_arrows" value="1" <?= !isset($content['show_arrows']) || !empty($content['show_arrows']) ? 'checked' : '' ?> class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#F25996]"></div>
                        </label>
                    </div>

                    <!-- Show Pagination Dots -->
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-gray-700 block">Show Pagination Dots</span>
                            <span class="text-[11px] text-gray-400">Bottom carousel indicator dots</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="show_dots" value="1" <?= !isset($content['show_dots']) || !empty($content['show_dots']) ? 'checked' : '' ?> class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#F25996]"></div>
                        </label>
                    </div>

                    <!-- Container Height (Desktop & Mobile) -->
                    <div class="pt-3 border-t border-gray-100">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">Container Height (vh / auto)</label>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Desktop Height</label>
                                <input type="text" name="container_height" value="<?= htmlspecialchars($content['container_height'] ?? 'auto') ?>" placeholder="e.g. auto, 70vh" class="w-full px-3 py-2 rounded-xl border border-gray-200 bg-white text-xs font-mono focus:ring-2 focus:ring-[#F25996] focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Mobile Height</label>
                                <input type="text" name="mobile_container_height" value="<?= htmlspecialchars($content['mobile_container_height'] ?? 'auto') ?>" placeholder="e.g. auto, 85vh" class="w-full px-3 py-2 rounded-xl border border-gray-200 bg-white text-xs font-mono focus:ring-2 focus:ring-[#F25996] focus:border-transparent">
                            </div>
                        </div>
                        <span class="text-[10px] text-gray-400 block mt-1">Set to <b>auto</b> for natural height, or use <b>vh</b> (e.g. 70vh) like Hero Banner.</span>
                    </div>
                </div>

                <!-- Theme & Colors -->
                <div class="border border-gray-200/80 rounded-2xl p-6 bg-gray-50/40 space-y-4">
                    <h4 class="font-serif font-bold text-gray-900 text-base pb-3 border-b border-gray-200/70">Styling & Colors</h4>

                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-700">Section Background</span>
                        <div class="flex items-center gap-2 color-sync-group">
                            <input type="color" value="<?= htmlspecialchars($content['bg_color'] ?? '#ffffff') ?>" class="color-picker w-8 h-8 rounded-lg cursor-pointer border border-gray-200 p-0 shadow-xs">
                            <input type="text" name="bg_color" value="<?= htmlspecialchars($content['bg_color'] ?? '#ffffff') ?>" placeholder="#ffffff" class="color-hex w-24 px-2 py-1 text-xs font-mono text-gray-700 bg-white border border-gray-200 rounded-lg hover:border-gray-300 focus:border-[#F25996] focus:ring-1 focus:ring-pink-200 outline-none uppercase transition-colors">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-700">Card Background</span>
                        <div class="flex items-center gap-2 color-sync-group">
                            <input type="color" value="<?= htmlspecialchars($content['card_bg_color'] ?? '#ffffff') ?>" class="color-picker w-8 h-8 rounded-lg cursor-pointer border border-gray-200 p-0 shadow-xs">
                            <input type="text" name="card_bg_color" value="<?= htmlspecialchars($content['card_bg_color'] ?? '#ffffff') ?>" placeholder="#ffffff" class="color-hex w-24 px-2 py-1 text-xs font-mono text-gray-700 bg-white border border-gray-200 rounded-lg hover:border-gray-300 focus:border-[#F25996] focus:ring-1 focus:ring-pink-200 outline-none uppercase transition-colors">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-700">Star Rating Color</span>
                        <div class="flex items-center gap-2 color-sync-group">
                            <input type="color" value="<?= htmlspecialchars($content['star_color'] ?? '#f59e0b') ?>" class="color-picker w-8 h-8 rounded-lg cursor-pointer border border-gray-200 p-0 shadow-xs">
                            <input type="text" name="star_color" value="<?= htmlspecialchars($content['star_color'] ?? '#f59e0b') ?>" placeholder="#f59e0b" class="color-hex w-24 px-2 py-1 text-xs font-mono text-gray-700 bg-white border border-gray-200 rounded-lg hover:border-gray-300 focus:border-[#F25996] focus:ring-1 focus:ring-pink-200 outline-none uppercase transition-colors">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-700">Accent / Quotes Color</span>
                        <div class="flex items-center gap-2 color-sync-group">
                            <input type="color" value="<?= htmlspecialchars($content['accent_color'] ?? '#F25996') ?>" class="color-picker w-8 h-8 rounded-lg cursor-pointer border border-gray-200 p-0 shadow-xs">
                            <input type="text" name="accent_color" value="<?= htmlspecialchars($content['accent_color'] ?? '#F25996') ?>" placeholder="#F25996" class="color-hex w-24 px-2 py-1 text-xs font-mono text-gray-700 bg-white border border-gray-200 rounded-lg hover:border-gray-300 focus:border-[#F25996] focus:ring-1 focus:ring-pink-200 outline-none uppercase transition-colors">
                        </div>
                    </div>

                    <div class="pt-2 border-t border-gray-200/60">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-gray-500 block mb-2">Arrow & Dot Styling</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-700">Arrow Background Color</span>
                        <div class="flex items-center gap-2 color-sync-group">
                            <input type="color" value="<?= htmlspecialchars($content['arrow_bg_color'] ?? '#ffffff') ?>" class="color-picker w-8 h-8 rounded-lg cursor-pointer border border-gray-200 p-0 shadow-xs">
                            <input type="text" name="arrow_bg_color" value="<?= htmlspecialchars($content['arrow_bg_color'] ?? '#ffffff') ?>" placeholder="#ffffff" class="color-hex w-24 px-2 py-1 text-xs font-mono text-gray-700 bg-white border border-gray-200 rounded-lg hover:border-gray-300 focus:border-[#F25996] focus:ring-1 focus:ring-pink-200 outline-none uppercase transition-colors">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-700">Arrow Icon Color</span>
                        <div class="flex items-center gap-2 color-sync-group">
                            <input type="color" value="<?= htmlspecialchars($content['arrow_icon_color'] ?? '#1f2937') ?>" class="color-picker w-8 h-8 rounded-lg cursor-pointer border border-gray-200 p-0 shadow-xs">
                            <input type="text" name="arrow_icon_color" value="<?= htmlspecialchars($content['arrow_icon_color'] ?? '#1f2937') ?>" placeholder="#1f2937" class="color-hex w-24 px-2 py-1 text-xs font-mono text-gray-700 bg-white border border-gray-200 rounded-lg hover:border-gray-300 focus:border-[#F25996] focus:ring-1 focus:ring-pink-200 outline-none uppercase transition-colors">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-700">Dot Base / Inactive Color</span>
                        <div class="flex items-center gap-2 color-sync-group">
                            <input type="color" value="<?= htmlspecialchars($content['dot_color'] ?? '#d1d5db') ?>" class="color-picker w-8 h-8 rounded-lg cursor-pointer border border-gray-200 p-0 shadow-xs">
                            <input type="text" name="dot_color" value="<?= htmlspecialchars($content['dot_color'] ?? '#d1d5db') ?>" placeholder="#d1d5db" class="color-hex w-24 px-2 py-1 text-xs font-mono text-gray-700 bg-white border border-gray-200 rounded-lg hover:border-gray-300 focus:border-[#F25996] focus:ring-1 focus:ring-pink-200 outline-none uppercase transition-colors">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-700">Dot Active Color</span>
                        <div class="flex items-center gap-2 color-sync-group">
                            <input type="color" value="<?= htmlspecialchars($content['dot_active_color'] ?? '#F25996') ?>" class="color-picker w-8 h-8 rounded-lg cursor-pointer border border-gray-200 p-0 shadow-xs">
                            <input type="text" name="dot_active_color" value="<?= htmlspecialchars($content['dot_active_color'] ?? '#F25996') ?>" placeholder="#F25996" class="color-hex w-24 px-2 py-1 text-xs font-mono text-gray-700 bg-white border border-gray-200 rounded-lg hover:border-gray-300 focus:border-[#F25996] focus:ring-1 focus:ring-pink-200 outline-none uppercase transition-colors">
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- Bottom Action Bar (Inside the single form container) -->
        <div class="pt-6 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-xs text-gray-500 flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Changes will be updated immediately across the storefront landing page.</span>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                <a href="<?= BASE_URL ?>/admin/cms/reviews" class="px-6 py-2.5 border border-red-200 text-red-500 hover:bg-red-50 font-bold text-sm rounded-full transition-colors uppercase">
                    Cancel
                </a>
                <button type="submit" class="bg-[#F25996] hover:bg-[#e04481] text-white px-8 py-2.5 rounded-full font-bold text-sm shadow-sm hover:shadow-md transition-all flex items-center gap-2 uppercase tracking-wider">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Update Review Section
                </button>
            </div>
        </div>
    </form>
</div>

<style>
.font-serif { font-family: 'Playfair Display', serif; }
.shadow-custom { box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
has-[:checked] .radio-dot {
    background-color: #F25996;
    border-color: #F25996;
}
input[type="radio"]:checked + div span .radio-dot,
input[type="radio"]:checked ~ div .radio-dot {
    background-color: #F25996;
    border-color: #F25996;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const masterCheckbox = document.getElementById('masterCheckbox');
    const selectAllBtn = document.getElementById('selectAllReviews');
    const deselectAllBtn = document.getElementById('deselectAllReviews');
    const checkBoxes = document.querySelectorAll('.review-chk');
    const searchInput = document.getElementById('reviewSearchInput');
    const rows = document.querySelectorAll('.review-row');

    if (masterCheckbox) {
        masterCheckbox.addEventListener('change', function() {
            checkBoxes.forEach(cb => {
                if (cb.closest('tr').style.display !== 'none') {
                    cb.checked = masterCheckbox.checked;
                }
            });
        });
    }

    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function() {
            checkBoxes.forEach(cb => cb.checked = true);
            if (masterCheckbox) masterCheckbox.checked = true;
        });
    }

    if (deselectAllBtn) {
        deselectAllBtn.addEventListener('click', function() {
            checkBoxes.forEach(cb => cb.checked = false);
            if (masterCheckbox) masterCheckbox.checked = false;
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            rows.forEach(row => {
                const reviewer = row.querySelector('.reviewer-name')?.textContent.toLowerCase() || '';
                const product = row.querySelector('.product-name')?.textContent.toLowerCase() || '';
                const text = row.querySelector('.review-text')?.textContent.toLowerCase() || '';
                
                if (reviewer.includes(query) || product.includes(query) || text.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // Color Pickers & Hex Inputs Two-Way Synchronization (with paste support)
    document.querySelectorAll('.color-sync-group').forEach(group => {
        const picker = group.querySelector('.color-picker');
        const hexInput = group.querySelector('.color-hex');
        if (!picker || !hexInput) return;

        function updateFromText() {
            let val = hexInput.value.trim();
            if (/^[0-9a-fA-F]{3}$/i.test(val)) {
                val = '#' + val[0] + val[0] + val[1] + val[1] + val[2] + val[2];
            } else if (/^[0-9a-fA-F]{6}$/i.test(val)) {
                val = '#' + val;
            } else if (/^#[0-9a-fA-F]{3}$/i.test(val)) {
                val = '#' + val[1] + val[1] + val[2] + val[2] + val[3] + val[3];
            }
            if (/^#[0-9a-fA-F]{6}$/i.test(val)) {
                picker.value = val;
            }
        }

        // Live typing
        hexInput.addEventListener('input', updateFromText);

        // Immediate paste handling
        hexInput.addEventListener('paste', () => {
            setTimeout(() => {
                let val = hexInput.value.trim();
                if (/^[0-9a-fA-F]{6}$/i.test(val)) {
                    hexInput.value = '#' + val;
                }
                updateFromText();
            }, 10);
        });

        // Format on blur
        hexInput.addEventListener('blur', () => {
            let val = hexInput.value.trim();
            if (/^[0-9a-fA-F]{6}$/i.test(val)) {
                hexInput.value = '#' + val;
            } else if (/^[0-9a-fA-F]{3}$/i.test(val)) {
                hexInput.value = '#' + val[0] + val[0] + val[1] + val[1] + val[2] + val[2];
            }
        });

        // Color picker box update
        picker.addEventListener('input', () => {
            hexInput.value = picker.value;
        });
    });
});
</script>
