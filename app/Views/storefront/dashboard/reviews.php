<div class="bg-[#fafafa] min-h-screen flex flex-col md:flex-row relative">
    
    <?php include __DIR__ . '/_sidebar.php'; ?>

    <div class="flex-1 p-3 xs:p-4 sm:p-5 md:p-6 lg:p-12 relative w-full min-w-0 overflow-y-auto">
        <div class="max-w-5xl mx-auto w-full min-w-0">
            <div class="mb-4 sm:mb-6 border-b border-gray-100 pb-3 sm:pb-5">
                <h1 class="text-xl sm:text-2xl md:text-3xl font-black text-gray-900 font-display tracking-tight">My Reviews</h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5 sm:mt-1">Review your purchased items and view your past feedback.</p>
            </div>

            <div x-data="{ 
                tab: '<?= !empty($pendingReviews) ? 'pending' : 'past' ?>',
                pendingPage: 1,
                pendingPerPage: 5,
                pastPage: 1,
                pastPerPage: 4,
                totalPending: <?= count($pendingReviews) ?>,
                totalPast: <?= count($myReviews) ?>
            }" class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 p-3.5 sm:p-6 md:p-8">
                
                <!-- Tabs -->
                <div class="flex border-b border-gray-100 mb-4 sm:mb-6 gap-3 sm:gap-6">
                    <button @click="tab = 'pending'" :class="{'border-[#F25996] text-[#F25996] font-black': tab === 'pending', 'border-transparent text-gray-500 hover:text-gray-700 font-semibold': tab !== 'pending'}" class="pb-3 text-xs sm:text-sm border-b-2 transition-all flex items-center gap-1.5 shrink-0">
                        <span>Pending Reviews</span>
                        <span class="px-2 py-0.5 text-[10px] sm:text-xs rounded-full font-bold" :class="{'bg-[#F25996]/10 text-[#F25996]': tab === 'pending', 'bg-gray-100 text-gray-600': tab !== 'pending'}"><?= count($pendingReviews) ?></span>
                    </button>
                    <button @click="tab = 'past'" :class="{'border-[#F25996] text-[#F25996] font-black': tab === 'past', 'border-transparent text-gray-500 hover:text-gray-700 font-semibold': tab !== 'past'}" class="pb-3 text-xs sm:text-sm border-b-2 transition-all flex items-center gap-1.5 shrink-0">
                        <span>Past Reviews</span>
                        <span class="px-2 py-0.5 text-[10px] sm:text-xs rounded-full font-bold" :class="{'bg-[#F25996]/10 text-[#F25996]': tab === 'past', 'bg-gray-100 text-gray-600': tab !== 'past'}"><?= count($myReviews) ?></span>
                    </button>
                </div>

                <!-- Pending Reviews Tab -->
                <div x-show="tab === 'pending'" class="space-y-3 sm:space-y-4">
                    <?php if (empty($pendingReviews)): ?>
                        <div class="text-center py-12 sm:py-16 bg-[#fafafa] rounded-2xl border border-gray-100">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            <p class="text-sm sm:text-base font-semibold text-gray-700">No pending reviews</p>
                            <p class="text-xs text-gray-400 mt-1">You don't have any products waiting for review.</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-3 sm:space-y-4">
                            <?php foreach ($pendingReviews as $idx => $item): ?>
                                <div x-show="Math.floor(<?= $idx ?> / pendingPerPage) + 1 === pendingPage" class="flex items-center gap-3 sm:gap-4 p-3 sm:p-4 border border-gray-100 rounded-2xl hover:border-gray-200 transition-all shadow-sm bg-white">
                                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl overflow-hidden bg-gray-50 border border-gray-100 shrink-0 flex items-center justify-center relative">
                                        <?php if (!empty($item['primary_image'])): ?>
                                            <img src="<?= get_image_url($item['primary_image']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>" class="w-full h-full object-contain p-1" onerror="this.style.display='none'; this.parentElement.querySelector('.fallback-svg').classList.remove('hidden');">
                                            <svg class="w-6 h-6 text-gray-300 fallback-svg hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <?php else: ?>
                                            <svg class="w-6 h-6 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-bold text-gray-900 text-xs sm:text-sm md:text-base leading-snug line-clamp-2" title="<?= htmlspecialchars($item['product_name']) ?>">
                                            <?= htmlspecialchars($item['product_name']) ?>
                                        </h3>
                                        <p class="text-[11px] sm:text-xs text-gray-500 mt-0.5">Order #<?= htmlspecialchars($item['order_number']) ?></p>
                                        <button type="button" onclick="openReviewModal(<?= $item['product_id'] ?>, <?= $item['order_id'] ?>)" class="mt-2 px-3 sm:px-4 py-1.5 sm:py-2 bg-[#F25996] text-white hover:bg-[#d8407d] text-xs sm:text-sm font-bold rounded-xl transition-all shadow-sm inline-flex items-center gap-1.5 whitespace-nowrap">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                            <span>Write a Review</span>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Pending Reviews Pagination -->
                        <div x-show="totalPending > pendingPerPage" class="pt-4 border-t border-gray-100 flex flex-col xs:flex-row items-center justify-between gap-3">
                            <p class="text-xs text-gray-500 font-medium">
                                Showing <span class="font-bold text-gray-800" x-text="(pendingPage - 1) * pendingPerPage + 1"></span> to <span class="font-bold text-gray-800" x-text="Math.min(pendingPage * pendingPerPage, totalPending)"></span> of <span class="font-bold text-gray-800" x-text="totalPending"></span> items
                            </p>
                            <div class="flex items-center gap-1.5">
                                <button 
                                    type="button" 
                                    @click="if(pendingPage > 1) { pendingPage--; $el.closest('.bg-white').scrollIntoView({behavior: 'smooth'}); }" 
                                    :disabled="pendingPage === 1" 
                                    :class="pendingPage === 1 ? 'opacity-40 cursor-not-allowed text-gray-400 bg-gray-50 border-gray-200' : 'text-gray-700 bg-white hover:bg-gray-50 border-gray-200 shadow-sm'" 
                                    class="px-3 py-1.5 rounded-xl border text-xs font-bold transition-all flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                    <span>Prev</span>
                                </button>
                                <div class="flex items-center gap-1">
                                    <template x-for="p in Math.ceil(totalPending / pendingPerPage)" :key="p">
                                        <button 
                                            type="button" 
                                            @click="pendingPage = p; $el.closest('.bg-white').scrollIntoView({behavior: 'smooth'});" 
                                            :class="pendingPage === p ? 'bg-[#F25996] text-white font-black shadow-sm' : 'bg-white hover:bg-gray-100 text-gray-700 font-semibold border border-gray-200'" 
                                            class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl text-xs flex items-center justify-center transition-all"
                                            x-text="p">
                                        </button>
                                    </template>
                                </div>
                                <button 
                                    type="button" 
                                    @click="if(pendingPage < Math.ceil(totalPending / pendingPerPage)) { pendingPage++; $el.closest('.bg-white').scrollIntoView({behavior: 'smooth'}); }" 
                                    :disabled="pendingPage >= Math.ceil(totalPending / pendingPerPage)" 
                                    :class="pendingPage >= Math.ceil(totalPending / pendingPerPage) ? 'opacity-40 cursor-not-allowed text-gray-400 bg-gray-50 border-gray-200' : 'text-gray-700 bg-white hover:bg-gray-50 border-gray-200 shadow-sm'" 
                                    class="px-3 py-1.5 rounded-xl border text-xs font-bold transition-all flex items-center gap-1">
                                    <span>Next</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Past Reviews Tab -->
                <div x-show="tab === 'past'" style="display: none;" class="space-y-3 sm:space-y-4">
                    <?php if (empty($myReviews)): ?>
                        <div class="text-center py-12 sm:py-16 bg-[#fafafa] rounded-2xl border border-gray-100">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <p class="text-sm sm:text-base font-semibold text-gray-700">No reviews yet</p>
                            <p class="text-xs text-gray-400 mt-1">You haven't written any reviews yet.</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-3 sm:space-y-4">
                            <?php foreach ($myReviews as $idx => $review): ?>
                                <div x-show="Math.floor(<?= $idx ?> / pastPerPage) + 1 === pastPage" class="p-3.5 sm:p-5 border border-gray-100 rounded-2xl bg-white shadow-sm space-y-2.5 sm:space-y-3">
                                    <div class="flex items-start justify-between gap-2.5">
                                        <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
                                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl overflow-hidden bg-gray-50 border border-gray-100 shrink-0 flex items-center justify-center relative">
                                                <?php if (!empty($review['primary_image'])): ?>
                                                    <img src="<?= get_image_url($review['primary_image']) ?>" class="w-full h-full object-contain p-1" onerror="this.style.display='none'; this.parentElement.querySelector('.fallback-svg').classList.remove('hidden');">
                                                    <svg class="w-5 h-5 text-gray-300 fallback-svg hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                <?php else: ?>
                                                    <svg class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                <?php endif; ?>
                                            </div>
                                            <div class="min-w-0">
                                                <h4 class="font-bold text-gray-900 text-xs sm:text-sm md:text-base leading-snug line-clamp-1" title="<?= htmlspecialchars($review['product_name']) ?>">
                                                    <?= htmlspecialchars($review['product_name']) ?>
                                                </h4>
                                                <div class="flex items-center text-yellow-400 mt-0.5 text-xs">
                                                    <?php for($i=1; $i<=5; $i++): ?>
                                                        <svg class="w-3.5 h-3.5 <?= $i <= $review['rating'] ? 'fill-current' : 'text-gray-300' ?>" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.363 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.363-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if($review['is_approved']): ?>
                                            <span class="px-2 sm:px-2.5 py-0.5 bg-green-50 text-green-700 border border-green-200 text-[10px] sm:text-xs font-bold rounded-full shrink-0">Approved</span>
                                        <?php else: ?>
                                            <span class="px-2 sm:px-2.5 py-0.5 bg-amber-50 text-amber-700 border border-amber-200 text-[10px] sm:text-xs font-bold rounded-full shrink-0">Pending Approval</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if($review['review_text']): ?>
                                        <p class="text-xs sm:text-sm text-gray-700 leading-relaxed bg-gray-50/80 p-2.5 sm:p-3 rounded-xl border border-gray-100"><?= nl2br(htmlspecialchars($review['review_text'])) ?></p>
                                    <?php endif; ?>
                                    <?php if(!empty($review['photos'])): ?>
                                        <div class="flex flex-wrap gap-2 pt-1">
                                            <?php foreach(json_decode($review['photos']) as $photo): ?>
                                                <a href="<?= get_image_url($photo) ?>" target="_blank" class="block w-14 h-14 sm:w-16 sm:h-16 rounded-xl overflow-hidden border border-gray-200 shadow-sm hover:opacity-90 transition-opacity">
                                                    <img src="<?= get_image_url($photo) ?>" class="w-full h-full object-cover">
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    <p class="text-[10px] sm:text-xs text-gray-400 font-medium"><?= date('M d, Y', strtotime($review['created_at'])) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Past Reviews Pagination -->
                        <div x-show="totalPast > pastPerPage" class="pt-4 border-t border-gray-100 flex flex-col xs:flex-row items-center justify-between gap-3">
                            <p class="text-xs text-gray-500 font-medium">
                                Showing <span class="font-bold text-gray-800" x-text="(pastPage - 1) * pastPerPage + 1"></span> to <span class="font-bold text-gray-800" x-text="Math.min(pastPage * pastPerPage, totalPast)"></span> of <span class="font-bold text-gray-800" x-text="totalPast"></span> reviews
                            </p>
                            <div class="flex items-center gap-1.5">
                                <button 
                                    type="button" 
                                    @click="if(pastPage > 1) { pastPage--; $el.closest('.bg-white').scrollIntoView({behavior: 'smooth'}); }" 
                                    :disabled="pastPage === 1" 
                                    :class="pastPage === 1 ? 'opacity-40 cursor-not-allowed text-gray-400 bg-gray-50 border-gray-200' : 'text-gray-700 bg-white hover:bg-gray-50 border-gray-200 shadow-sm'" 
                                    class="px-3 py-1.5 rounded-xl border text-xs font-bold transition-all flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                    <span>Prev</span>
                                </button>
                                <div class="flex items-center gap-1">
                                    <template x-for="p in Math.ceil(totalPast / pastPerPage)" :key="p">
                                        <button 
                                            type="button" 
                                            @click="pastPage = p; $el.closest('.bg-white').scrollIntoView({behavior: 'smooth'});" 
                                            :class="pastPage === p ? 'bg-[#F25996] text-white font-black shadow-sm' : 'bg-white hover:bg-gray-100 text-gray-700 font-semibold border border-gray-200'" 
                                            class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl text-xs flex items-center justify-center transition-all"
                                            x-text="p">
                                        </button>
                                    </template>
                                </div>
                                <button 
                                    type="button" 
                                    @click="if(pastPage < Math.ceil(totalPast / pastPerPage)) { pastPage++; $el.closest('.bg-white').scrollIntoView({behavior: 'smooth'}); }" 
                                    :disabled="pastPage >= Math.ceil(totalPast / pastPerPage)" 
                                    :class="pastPage >= Math.ceil(totalPast / pastPerPage) ? 'opacity-40 cursor-not-allowed text-gray-400 bg-gray-50 border-gray-200' : 'text-gray-700 bg-white hover:bg-gray-50 border-gray-200 shadow-sm'" 
                                    class="px-3 py-1.5 rounded-xl border text-xs font-bold transition-all flex items-center gap-1">
                                    <span>Next</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Review Modal -->
<div id="reviewModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-[100] hidden opacity-0 transition-opacity duration-300 flex items-center justify-center p-3 sm:p-4">
    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl w-full max-w-lg transform scale-95 transition-transform duration-300 max-h-[90vh] overflow-hidden flex flex-col border border-gray-100">
        <div class="flex justify-between items-center p-4 sm:p-5 border-b border-gray-100 bg-[#fafafa]">
            <h2 class="text-base sm:text-lg font-black text-gray-900">Write a Review</h2>
            <button onclick="closeReviewModal()" class="text-gray-400 hover:text-gray-600 bg-white hover:bg-gray-100 rounded-full p-1.5 transition-colors border border-gray-200 shadow-sm">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form id="reviewForm" onsubmit="submitReview(event)" class="p-4 sm:p-6 overflow-y-auto space-y-4">
            <input type="hidden" name="product_id" id="reviewProductId">
            <input type="hidden" name="order_id" id="reviewOrderId">
            
            <!-- Star Rating -->
            <div class="flex flex-col items-center py-2 bg-gray-50/80 rounded-2xl border border-gray-100">
                <p class="text-xs sm:text-sm font-bold text-gray-700 mb-2">How would you rate this product?</p>
                <div class="flex items-center gap-1.5 sm:gap-2" id="starRating">
                    <?php for($i=1; $i<=5; $i++): ?>
                        <button type="button" data-rating="<?= $i ?>" onclick="setRating(<?= $i ?>)" onmouseover="hoverRating(<?= $i ?>)" onmouseout="resetRatingHover()" class="star-btn text-gray-300 hover:text-yellow-400 focus:outline-none transition-colors p-1">
                            <svg class="w-7 h-7 sm:w-8 sm:h-8 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </button>
                    <?php endfor; ?>
                </div>
                <input type="hidden" name="rating" id="reviewRatingInput" value="0">
            </div>

            <!-- Review Text -->
            <div>
                <label class="block text-xs sm:text-sm font-bold text-gray-700 mb-1.5">Share your experience <span class="text-red-500">*</span></label>
                <textarea name="review_text" rows="3" required class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#F25996] focus:border-transparent text-xs sm:text-sm resize-none" placeholder="What did you like or dislike about the product quality, fabric, fit, etc.?"></textarea>
            </div>

            <!-- Photos -->
            <div>
                <label class="block text-xs sm:text-sm font-bold text-gray-700 mb-1.5">Add photos (Optional)</label>
                <input type="file" id="reviewPhotosInput" name="photos[]" multiple accept="image/*" onchange="previewReviewPhotos(this)" class="block w-full text-xs sm:text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#F25996]/10 file:text-[#F25996] hover:file:bg-[#F25996]/20 transition cursor-pointer">
                <div id="reviewPhotosPreview" class="mt-3 flex flex-wrap gap-2 hidden"></div>
            </div>

            <button type="submit" id="reviewSubmitBtn" class="w-full py-2.5 sm:py-3 bg-[#F25996] hover:bg-[#d8407d] text-white font-bold rounded-xl transition-colors shadow-md text-xs sm:text-sm">
                Submit Review
            </button>
        </form>
    </div>
</div>

<!-- AlpineJS -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
let currentRating = 0;

function openReviewModal(productId, orderId) {
    document.getElementById('reviewProductId').value = productId;
    document.getElementById('reviewOrderId').value = orderId;
    
    // Reset form
    document.getElementById('reviewForm').reset();
    const preview = document.getElementById('reviewPhotosPreview');
    if (preview) {
        preview.innerHTML = '';
        preview.classList.add('hidden');
    }
    setRating(0);
    
    const modal = document.getElementById('reviewModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        modal.firstElementChild.classList.remove('scale-95');
    }, 10);
}

function closeReviewModal() {
    const modal = document.getElementById('reviewModal');
    modal.classList.add('opacity-0');
    modal.firstElementChild.classList.add('scale-95');
    document.body.style.overflow = '';
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function setRating(rating) {
    currentRating = rating;
    document.getElementById('reviewRatingInput').value = rating;
    updateStars(rating);
}

function hoverRating(rating) {
    updateStars(rating);
}

function resetRatingHover() {
    updateStars(currentRating);
}

function previewReviewPhotos(input) {
    const previewContainer = document.getElementById('reviewPhotosPreview');
    previewContainer.innerHTML = ''; // Clear existing
    if (!input.files || input.files.length === 0) {
        previewContainer.classList.add('hidden');
        return;
    }
    
    previewContainer.classList.remove('hidden');
    
    Array.from(input.files).forEach(file => {
        if (!file.type.startsWith('image/')) return;
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const imgWrap = document.createElement('div');
            imgWrap.className = 'relative w-16 h-16 rounded-xl overflow-hidden border border-gray-200 shadow-sm';
            
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'w-full h-full object-cover';
            
            imgWrap.appendChild(img);
            previewContainer.appendChild(imgWrap);
        }
        reader.readAsDataURL(file);
    });
}

function updateStars(rating) {
    const stars = document.querySelectorAll('.star-btn');
    stars.forEach(star => {
        const val = parseInt(star.getAttribute('data-rating'));
        if (val <= rating) {
            star.classList.remove('text-gray-300');
            star.classList.add('text-yellow-400');
        } else {
            star.classList.remove('text-yellow-400');
            star.classList.add('text-gray-300');
        }
    });
}

function submitReview(e) {
    e.preventDefault();
    
    if (currentRating === 0) {
        alert('Please select a star rating.');
        return;
    }
    
    const form = e.target;
    const formData = new FormData(form);
    const btn = document.getElementById('reviewSubmitBtn');
    
    btn.disabled = true;
    btn.innerText = 'Submitting...';
    
    fetch('<?= BASE_URL ?>/dashboard/reviews/submit', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'Something went wrong.');
            btn.disabled = false;
            btn.innerText = 'Submit Review';
        }
    })
    .catch(err => {
        console.error(err);
        alert('An error occurred while submitting your review.');
        btn.disabled = false;
        btn.innerText = 'Submit Review';
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const orderId = urlParams.get('order_id');
    const productId = urlParams.get('product_id');
    if (orderId && productId) {
        setTimeout(() => {
            openReviewModal(parseInt(productId), parseInt(orderId));
        }, 150);
    }
});
</script>
