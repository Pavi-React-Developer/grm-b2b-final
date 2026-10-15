<div class="w-full">
    <!-- Header Section -->
    <div class="mb-6 relative z-10">
        <!-- Breadcrumbs -->
        <div class="text-sm font-medium text-gray-400 mb-2 font-serif tracking-wide">
            <?php if (\Core\Session::get('user_role') === 'vendor'): ?>
                Dashboard <span class="mx-1 text-gray-300">›</span> Vendor Portal <span class="mx-1 text-gray-300">›</span> <span class="text-brand-700">Reviews</span>
            <?php else: ?>
                Dashboard <span class="mx-1 text-gray-300">›</span> Catalog Management <span class="mx-1 text-gray-300">›</span> <span class="text-brand-700">Reviews</span>
            <?php endif; ?>
        </div>
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 w-full">
            <div>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold text-gray-900 tracking-tight">
                    <?= \Core\Session::get('user_role') === 'vendor' ? 'Customer Reviews' : 'Reviews Management' ?>
                </h2>
                <p class="text-sm text-gray-500 mt-1">Manage, moderate, and inspect customer feedback for standard &amp; customized products.</p>
            </div>
            
            <div class="flex items-center space-x-3 sm:ml-auto">
                <button onclick="window.location.reload()" class="bg-white hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-full font-bold text-xs sm:text-sm shadow-sm border border-gray-200 transition-all flex items-center uppercase tracking-wider">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Refresh
                </button>
                <button onclick="exportCurrentReviewsCSV()" class="bg-[#F25996] hover:bg-[#e04481] text-white px-5 py-2.5 rounded-full font-bold text-xs sm:text-sm shadow-sm transition-all flex items-center uppercase tracking-wider">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export CSV
                </button>
            </div>
        </div>
    </div>

    <?php
    $stdReviews = $standardReviews ?? array_values(array_filter($reviews ?? [], fn($r) => empty($r['customization_id'])));
    $cstReviews = $customizedReviews ?? array_values(array_filter($reviews ?? [], fn($r) => !empty($r['customization_id'])));

    $stdTotal = count($stdReviews);
    $stdApproved = count(array_filter($stdReviews, fn($r) => !empty($r['is_approved'])));
    $stdPending = $stdTotal - $stdApproved;

    $cstTotal = count($cstReviews);
    $cstApproved = count(array_filter($cstReviews, fn($r) => !empty($r['is_approved'])));
    $cstPending = $cstTotal - $cstApproved;
    ?>

    <!-- Main Section Tabs Navigation -->
    <div class="flex items-center gap-2 sm:gap-4 border-b border-gray-200 mb-6 pb-px overflow-x-auto no-scrollbar">
        <button type="button" 
                onclick="switchReviewsTab('standard')" 
                id="tabBtn-standard" 
                class="px-5 py-3 border-b-2 font-black text-sm transition-all flex items-center gap-2 shrink-0 border-[#F25996] text-[#F25996]">
            <span>🛍️ Standard Product Reviews</span>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#F25996]/10 text-[#F25996]" id="countBadge-standard"><?= $stdTotal ?></span>
        </button>

        <button type="button" 
                onclick="switchReviewsTab('customized')" 
                id="tabBtn-customized" 
                class="px-5 py-3 border-b-2 font-bold text-sm transition-all flex items-center gap-2 shrink-0 border-transparent text-gray-500 hover:text-gray-800">
            <span>✂️ Customized Product Reviews</span>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-pink-50 text-[#F25996] border border-pink-200" id="countBadge-customized"><?= $cstTotal ?></span>
            <?php if ($cstPending > 0): ?>
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse" title="<?= $cstPending ?> pending customized reviews"></span>
            <?php endif; ?>
        </button>
    </div>

    <!-- ===================================================================== -->
    <!-- SECTION 1: Standard Product Reviews                                   -->
    <!-- ===================================================================== -->
    <div id="section-standard" class="space-y-6">
        <!-- Stats Overview for Standard -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center">
                <div class="p-3 bg-pink-50 rounded-xl mr-4 text-[#F25996]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wider font-bold text-gray-400">Total Standard Reviews</p>
                    <p class="text-2xl font-black text-gray-900"><?= $stdTotal ?></p>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center">
                <div class="p-3 bg-green-50 rounded-xl mr-4 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wider font-bold text-gray-400">Approved</p>
                    <p class="text-2xl font-black text-green-700"><?= $stdApproved ?></p>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center">
                <div class="p-3 bg-amber-50 rounded-xl mr-4 text-amber-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wider font-bold text-gray-400">Pending Review</p>
                    <p class="text-2xl font-black text-amber-600"><?= $stdPending ?></p>
                </div>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="relative w-full sm:w-80">
                <input type="text" id="stdSearchInput" onkeyup="filterTable('standard')" placeholder="Search product or reviewer..." class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs sm:text-sm focus:ring-2 focus:ring-[#F25996] focus:border-transparent">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <label class="text-xs font-bold text-gray-500 whitespace-nowrap">Filter Status:</label>
                <select id="stdStatusFilter" onchange="filterTable('standard')" class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs sm:text-sm font-semibold text-gray-700">
                    <option value="all">All Reviews</option>
                    <option value="approved">Approved</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
        </div>

        <!-- Standard Data Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="standardReviewsTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Reviewer</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Standard Product</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Rating</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Photos</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        <?php if (empty($stdReviews)): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-400 font-medium">No standard product reviews found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($stdReviews as $review): 
                                $statusSlug = $review['is_approved'] ? 'approved' : 'pending';
                                $photos = !empty($review['photos']) ? (json_decode($review['photos'], true) ?: []) : [];
                            ?>
                                <tr class="hover:bg-gray-50/80 transition-colors review-row" data-status="<?= $statusSlug ?>">
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 font-medium">
                                        <?= date('M d, Y', strtotime($review['created_at'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900"><?= htmlspecialchars($review['reviewer_name']) ?></div>
                                        <div class="text-xs text-gray-500"><?= htmlspecialchars($review['reviewer_email']) ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-900 line-clamp-2" title="<?= htmlspecialchars($review['product_name']) ?>">
                                            <?= htmlspecialchars($review['product_name']) ?>
                                        </div>
                                        <?php if ($review['review_text']): ?>
                                            <div class="text-xs text-gray-500 mt-1 line-clamp-1 italic" title="<?= htmlspecialchars($review['review_text']) ?>">“<?= htmlspecialchars($review['review_text']) ?>”</div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex text-yellow-400">
                                            <?php for($i=1; $i<=5; $i++): ?>
                                                <svg class="w-4 h-4 <?= $i <= $review['rating'] ? 'fill-current' : 'text-gray-200 fill-current' ?>" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            <?php endfor; ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if (!empty($photos)): ?>
                                            <div class="flex items-center gap-1.5">
                                                <?php foreach (array_slice($photos, 0, 3) as $photo): ?>
                                                    <a href="<?= get_image_url($photo) ?>" target="_blank" class="block w-9 h-9 rounded-lg overflow-hidden border border-gray-200 hover:scale-105 transition-transform shadow-2xs">
                                                        <img src="<?= get_image_url($photo) ?>" class="w-full h-full object-cover" alt="Photo">
                                                    </a>
                                                <?php endforeach; ?>
                                                <?php if (count($photos) > 3): ?>
                                                    <span class="text-[11px] text-gray-500 font-bold ml-0.5">+<?= count($photos) - 3 ?></span>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-300 font-medium">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($review['is_approved']): ?>
                                            <span class="px-2.5 py-1 bg-green-50 text-green-700 border border-green-200 text-xs font-bold rounded-lg inline-flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> Approved
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold rounded-lg inline-flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-bold">
                                        <div class="inline-flex items-center gap-2">
                                            <?php if ($this->hasPermission('reviews', 'edit')): ?>
                                                <?php if (!$review['is_approved']): ?>
                                                    <button onclick="updateReviewStatus(<?= $review['id'] ?>, 'approve')" class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors shadow-2xs">Approve</button>
                                                <?php else: ?>
                                                    <button onclick="updateReviewStatus(<?= $review['id'] ?>, 'reject')" class="px-3 py-1 bg-amber-100 hover:bg-amber-200 text-amber-800 rounded-lg transition-colors">Revoke</button>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <button onclick="viewReview(<?= htmlspecialchars(json_encode([
                                                'id' => $review['id'],
                                                'name' => $review['reviewer_name'],
                                                'email' => $review['reviewer_email'],
                                                'product' => $review['product_name'],
                                                'isCustom' => false,
                                                'rating' => $review['rating'],
                                                'text' => $review['review_text'],
                                                'photos' => $photos,
                                                'date' => date('M d, Y', strtotime($review['created_at']))
                                            ])) ?>)" class="p-1.5 text-gray-500 hover:text-[#F25996] hover:bg-pink-50 rounded-lg transition-colors border border-gray-200" title="View Review Details">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ===================================================================== -->
    <!-- SECTION 2: Customized Product Reviews                                 -->
    <!-- ===================================================================== -->
    <div id="section-customized" class="space-y-6 hidden">
        <!-- Stats Overview for Customized -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center">
                <div class="p-3 bg-pink-50 rounded-xl mr-4 text-[#F25996]">
                    <span class="text-2xl">✂️</span>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wider font-bold text-gray-400">Total Custom Reviews</p>
                    <p class="text-2xl font-black text-gray-900"><?= $cstTotal ?></p>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center">
                <div class="p-3 bg-green-50 rounded-xl mr-4 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wider font-bold text-gray-400">Approved Custom</p>
                    <p class="text-2xl font-black text-green-700"><?= $cstApproved ?></p>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center">
                <div class="p-3 bg-amber-50 rounded-xl mr-4 text-amber-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wider font-bold text-gray-400">Pending Verification</p>
                    <p class="text-2xl font-black text-amber-600"><?= $cstPending ?></p>
                </div>
            </div>
        </div>

        <!-- Filter & Search Bar for Customized -->
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="relative w-full sm:w-80">
                <input type="text" id="cstSearchInput" onkeyup="filterTable('customized')" placeholder="Search fabric, garment or reviewer..." class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs sm:text-sm focus:ring-2 focus:ring-[#F25996] focus:border-transparent">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <label class="text-xs font-bold text-gray-500 whitespace-nowrap">Filter Status:</label>
                <select id="cstStatusFilter" onchange="filterTable('customized')" class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs sm:text-sm font-semibold text-gray-700">
                    <option value="all">All Reviews</option>
                    <option value="approved">Approved</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
        </div>

        <!-- Customized Data Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="customizedReviewsTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Reviewer</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Customized Garment &amp; Fabric</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Rating</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Photos</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        <?php if (empty($cstReviews)): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-400 font-medium">No customized product reviews found yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($cstReviews as $review): 
                                $statusSlug = $review['is_approved'] ? 'approved' : 'pending';
                                $photos = !empty($review['photos']) ? (json_decode($review['photos'], true) ?: []) : [];
                            ?>
                                <tr class="hover:bg-gray-50/80 transition-colors review-row" data-status="<?= $statusSlug ?>">
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 font-medium">
                                        <?= date('M d, Y', strtotime($review['created_at'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900"><?= htmlspecialchars($review['reviewer_name']) ?></div>
                                        <div class="text-xs text-gray-500"><?= htmlspecialchars($review['reviewer_email']) ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2 flex-wrap mb-1">
                                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-pink-50 text-[#F25996] border border-pink-200">
                                                ✂️ Custom <?= htmlspecialchars($review['garment_type'] ?? 'Garment') ?>
                                            </span>
                                            <?php if (!empty($review['program_name'])): ?>
                                                <span class="inline-block text-[11px] font-bold text-gray-600">
                                                    <?= htmlspecialchars($review['program_name']) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-xs font-medium text-gray-700 line-clamp-1" title="<?= htmlspecialchars($review['product_name']) ?>">
                                            Fabric: <strong class="text-gray-900"><?= htmlspecialchars($review['product_name']) ?></strong>
                                        </div>
                                        <?php if ($review['review_text']): ?>
                                            <div class="text-xs text-gray-500 mt-1 line-clamp-1 italic" title="<?= htmlspecialchars($review['review_text']) ?>">“<?= htmlspecialchars($review['review_text']) ?>”</div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex text-yellow-400">
                                            <?php for($i=1; $i<=5; $i++): ?>
                                                <svg class="w-4 h-4 <?= $i <= $review['rating'] ? 'fill-current' : 'text-gray-200 fill-current' ?>" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            <?php endfor; ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if (!empty($photos)): ?>
                                            <div class="flex items-center gap-1.5">
                                                <?php foreach (array_slice($photos, 0, 3) as $photo): ?>
                                                    <a href="<?= get_image_url($photo) ?>" target="_blank" class="block w-9 h-9 rounded-lg overflow-hidden border border-gray-200 hover:scale-105 transition-transform shadow-2xs">
                                                        <img src="<?= get_image_url($photo) ?>" class="w-full h-full object-cover" alt="Photo">
                                                    </a>
                                                <?php endforeach; ?>
                                                <?php if (count($photos) > 3): ?>
                                                    <span class="text-[11px] text-gray-500 font-bold ml-0.5">+<?= count($photos) - 3 ?></span>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-300 font-medium">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($review['is_approved']): ?>
                                            <span class="px-2.5 py-1 bg-green-50 text-green-700 border border-green-200 text-xs font-bold rounded-lg inline-flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> Approved
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold rounded-lg inline-flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-bold">
                                        <div class="inline-flex items-center gap-2">
                                            <?php if ($this->hasPermission('reviews', 'edit')): ?>
                                                <?php if (!$review['is_approved']): ?>
                                                    <button onclick="updateReviewStatus(<?= $review['id'] ?>, 'approve')" class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors shadow-2xs">Approve</button>
                                                <?php else: ?>
                                                    <button onclick="updateReviewStatus(<?= $review['id'] ?>, 'reject')" class="px-3 py-1 bg-amber-100 hover:bg-amber-200 text-amber-800 rounded-lg transition-colors">Revoke</button>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <button onclick="viewReview(<?= htmlspecialchars(json_encode([
                                                'id' => $review['id'],
                                                'name' => $review['reviewer_name'],
                                                'email' => $review['reviewer_email'],
                                                'product' => $review['product_name'],
                                                'isCustom' => true,
                                                'garmentType' => $review['garment_type'] ?? 'Garment',
                                                'programName' => $review['program_name'] ?? '',
                                                'rating' => $review['rating'],
                                                'text' => $review['review_text'],
                                                'photos' => $photos,
                                                'date' => date('M d, Y', strtotime($review['created_at']))
                                            ])) ?>)" class="p-1.5 text-gray-500 hover:text-[#F25996] hover:bg-pink-50 rounded-lg transition-colors border border-gray-200" title="View Review Details">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- View Review Modal (Comprehensive Detail Lightbox)                          -->
<!-- ========================================================================= -->
<div id="viewReviewModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-[200] hidden opacity-0 transition-opacity duration-300 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg transform scale-95 transition-transform duration-300 max-h-[90vh] overflow-y-auto border border-gray-100">
        <div class="flex justify-between items-center p-6 border-b border-gray-100 bg-[#fafafa]">
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-full bg-pink-50 text-[#F25996] flex items-center justify-center font-bold text-sm">⭐</span>
                <h2 class="text-lg font-black text-gray-900">Review Details</h2>
            </div>
            <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600 bg-white hover:bg-gray-100 rounded-full p-1.5 transition-colors border border-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Item Information</p>
                    <span id="vrCustomBadge" class="hidden text-[10px] font-bold text-[#F25996] bg-pink-50 border border-pink-200 px-2.5 py-0.5 rounded-full"></span>
                </div>
                <p id="vrProduct" class="text-gray-900 font-extrabold text-base"></p>
                <p id="vrProgram" class="text-xs text-gray-500 font-semibold mt-0.5 hidden"></p>
            </div>

            <div class="flex justify-between items-center border-t border-gray-100 pt-3 text-sm">
                <div>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Reviewer</p>
                    <p id="vrName" class="text-gray-900 font-bold"></p>
                    <p id="vrEmail" class="text-xs text-gray-500"></p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Date</p>
                    <p id="vrDate" class="text-gray-900 font-medium"></p>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-3">
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1.5">Rating</p>
                <div id="vrRating" class="flex text-yellow-400"></div>
            </div>

            <div class="border-t border-gray-100 pt-3">
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1.5">Review Comment</p>
                <p id="vrText" class="text-gray-800 text-sm leading-relaxed bg-gray-50 p-3.5 rounded-xl border border-gray-100 italic"></p>
            </div>

            <div id="vrPhotosContainer" class="border-t border-gray-100 pt-3 hidden">
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-2">Attached Photos</p>
                <div id="vrPhotos" class="flex flex-wrap gap-2.5"></div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- Confirm Action Modal                                                      -->
<!-- ========================================================================= -->
<div id="confirm-modal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-[200] hidden opacity-0 transition-opacity duration-300 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm transform scale-95 transition-transform duration-300 overflow-hidden text-center p-6 border border-gray-100">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-amber-100 mb-4" id="confirm-icon-bg">
            <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="confirm-icon"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <h3 class="text-lg font-black text-gray-900 mb-2" id="confirm-title">Confirm Action</h3>
        <p class="text-xs text-gray-500 mb-6 font-medium" id="confirm-message">Are you sure you want to proceed?</p>
        <div class="flex space-x-3">
            <button type="button" onclick="closeConfirmModal()" class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-50 transition-colors">Cancel</button>
            <button type="button" id="confirm-action-btn" class="flex-1 px-4 py-2.5 bg-[#F25996] text-white rounded-xl text-xs font-bold hover:bg-[#d8407d] transition-colors shadow-sm">Confirm</button>
        </div>
    </div>
</div>

<script>
let activeTab = 'standard';
let pendingReviewId = null;
let pendingStatus = null;

function switchReviewsTab(tab) {
    activeTab = tab;
    const btnStd = document.getElementById('tabBtn-standard');
    const btnCst = document.getElementById('tabBtn-customized');
    const secStd = document.getElementById('section-standard');
    const secCst = document.getElementById('section-customized');

    if (tab === 'standard') {
        btnStd.className = 'px-5 py-3 border-b-2 font-black text-sm transition-all flex items-center gap-2 shrink-0 border-[#F25996] text-[#F25996]';
        btnCst.className = 'px-5 py-3 border-b-2 font-bold text-sm transition-all flex items-center gap-2 shrink-0 border-transparent text-gray-500 hover:text-gray-800';
        secStd.classList.remove('hidden');
        secCst.classList.add('hidden');
    } else {
        btnCst.className = 'px-5 py-3 border-b-2 font-black text-sm transition-all flex items-center gap-2 shrink-0 border-[#F25996] text-[#F25996]';
        btnStd.className = 'px-5 py-3 border-b-2 font-bold text-sm transition-all flex items-center gap-2 shrink-0 border-transparent text-gray-500 hover:text-gray-800';
        secCst.classList.remove('hidden');
        secStd.classList.add('hidden');
    }
}

function filterTable(tab) {
    const searchVal = (tab === 'standard' ? document.getElementById('stdSearchInput') : document.getElementById('cstSearchInput')).value.toLowerCase();
    const statusVal = (tab === 'standard' ? document.getElementById('stdStatusFilter') : document.getElementById('cstStatusFilter')).value;
    const tableId = (tab === 'standard') ? 'standardReviewsTable' : 'customizedReviewsTable';
    const rows = document.querySelectorAll('#' + tableId + ' tbody tr.review-row');

    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        const rowStatus = row.getAttribute('data-status');
        const matchesSearch = text.includes(searchVal);
        const matchesStatus = (statusVal === 'all') || (rowStatus === statusVal);

        if (matchesSearch && matchesStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function updateReviewStatus(reviewId, status) {
    pendingReviewId = reviewId;
    pendingStatus = status;
    
    const modal = document.getElementById('confirm-modal');
    const title = document.getElementById('confirm-title');
    const msg = document.getElementById('confirm-message');
    const btn = document.getElementById('confirm-action-btn');
    const iconBg = document.getElementById('confirm-icon-bg');
    const icon = document.getElementById('confirm-icon');
    
    if (status === 'approve') {
        title.innerText = 'Approve Review';
        msg.innerText = 'Are you sure you want to approve this review and make it visible on the storefront?';
        btn.innerText = 'Approve Review';
        btn.className = 'flex-1 px-4 py-2.5 bg-green-600 text-white rounded-xl text-xs font-bold hover:bg-green-700 transition-colors shadow-sm';
        iconBg.className = 'mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4';
        icon.className = 'h-6 w-6 text-green-600';
    } else {
        title.innerText = 'Revoke/Reject Review';
        msg.innerText = 'Are you sure you want to revoke this review and hide it from the public storefront?';
        btn.innerText = 'Revoke Review';
        btn.className = 'flex-1 px-4 py-2.5 bg-red-600 text-white rounded-xl text-xs font-bold hover:bg-red-700 transition-colors shadow-sm';
        iconBg.className = 'mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4';
        icon.className = 'h-6 w-6 text-red-600';
    }
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        modal.firstElementChild.classList.remove('scale-95');
    }, 10);
}

function closeConfirmModal() {
    const modal = document.getElementById('confirm-modal');
    modal.classList.add('opacity-0');
    modal.firstElementChild.classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

document.getElementById('confirm-action-btn').addEventListener('click', function() {
    if (!pendingReviewId) return;
    
    const originalText = this.innerText;
    this.innerText = 'Processing...';
    this.disabled = true;
    
    fetch('<?= BASE_URL ?>/admin/reviews/update-status', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ review_id: pendingReviewId, status: pendingStatus })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'Operation failed.');
            closeConfirmModal();
            this.innerText = originalText;
            this.disabled = false;
        }
    })
    .catch(err => {
        console.error(err);
        alert('An unexpected network error occurred.');
        closeConfirmModal();
        this.innerText = originalText;
        this.disabled = false;
    });
});

function viewReview(review) {
    document.getElementById('vrProduct').innerText = review.product;
    document.getElementById('vrName').innerText = review.name;
    document.getElementById('vrEmail').innerText = review.email || '';
    document.getElementById('vrDate').innerText = review.date;
    document.getElementById('vrText').innerText = review.text || 'No comment provided.';
    
    // Custom Badge & Program
    const badgeEl = document.getElementById('vrCustomBadge');
    const progEl = document.getElementById('vrProgram');
    if (review.isCustom) {
        badgeEl.classList.remove('hidden');
        badgeEl.innerText = '✂️ Custom ' + (review.garmentType || 'Garment');
        if (review.programName) {
            progEl.classList.remove('hidden');
            progEl.innerText = 'Program: ' + review.programName;
        } else {
            progEl.classList.add('hidden');
        }
    } else {
        badgeEl.classList.add('hidden');
        progEl.classList.add('hidden');
    }
    
    // Set stars
    let starsHtml = '';
    for(let i=1; i<=5; i++) {
        const colorClass = i <= review.rating ? 'fill-current' : 'text-gray-200 fill-current';
        starsHtml += `<svg class="w-5 h-5 ${colorClass}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>`;
    }
    document.getElementById('vrRating').innerHTML = starsHtml;

    // Set photos
    const photosContainer = document.getElementById('vrPhotosContainer');
    const photosDiv = document.getElementById('vrPhotos');
    if (review.photos && review.photos.length > 0) {
        photosContainer.classList.remove('hidden');
        let phHtml = '';
        review.photos.forEach(p => {
            let src = (p.startsWith('http://') || p.startsWith('https://') || p.startsWith('/')) ? p : ('<?= BASE_URL ?>/' + p);
            phHtml += `<a href="${src}" target="_blank" class="block w-20 h-20 rounded-xl overflow-hidden border border-gray-200 shadow-xs hover:scale-105 transition-transform"><img src="${src}" class="w-full h-full object-cover"></a>`;
        });
        photosDiv.innerHTML = phHtml;
    } else {
        photosContainer.classList.add('hidden');
        photosDiv.innerHTML = '';
    }

    const modal = document.getElementById('viewReviewModal');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        modal.firstElementChild.classList.remove('scale-95');
    }, 10);
}

function closeViewModal() {
    const modal = document.getElementById('viewReviewModal');
    modal.classList.add('opacity-0');
    modal.firstElementChild.classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function exportCurrentReviewsCSV() {
    const tableId = (activeTab === 'standard') ? 'standardReviewsTable' : 'customizedReviewsTable';
    let table = document.getElementById(tableId);
    if (!table) return;
    
    let rows = Array.from(table.querySelectorAll("tr"));
    let csvContent = "data:text/csv;charset=utf-8,";
    
    rows.forEach(row => {
        if (row.style.display === 'none') return;
        let cols = Array.from(row.querySelectorAll("th, td"));
        // Exclude the last action column
        cols = cols.slice(0, cols.length - 1);
        let rowData = cols.map(col => '"' + col.innerText.replace(/"/g, '""').trim() + '"');
        csvContent += rowData.join(",") + "\r\n";
    });
    
    let encodedUri = encodeURI(csvContent);
    let link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", activeTab + "_reviews_export_<?= date('Y-m-d') ?>.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
