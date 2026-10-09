

<div class="w-full">
    <!-- Header Section -->
    <div class="mb-8 relative z-10">
        <!-- Breadcrumbs -->
        <div class="text-sm font-medium text-gray-400 mb-2 font-serif tracking-wide">
            <?php if (\Core\Session::get('user_role') === 'vendor'): ?>
                Dashboard <span class="mx-1 text-gray-300">›</span> Vendor Portal <span class="mx-1 text-gray-300">›</span> <span class="text-brand-700">Reviews</span>
            <?php else: ?>
                Dashboard <span class="mx-1 text-gray-300">›</span> Catalog Management <span class="mx-1 text-gray-300">›</span> <span class="text-brand-700">Reviews</span>
            <?php endif; ?>
        </div>
        
        <div class="flex justify-between items-center mb-6 w-full">
            <h2 class="text-5xl font-display font-extrabold text-gray-900 tracking-tight">
                <?= \Core\Session::get('user_role') === 'vendor' ? 'Customer Reviews' : 'Reviews Management' ?>
            </h2>
            
            <div class="flex items-center space-x-3 ml-auto">
                <button onclick="window.location.reload()" class="bg-white hover:bg-gray-50 text-gray-700 px-6 py-3 rounded-full font-bold text-sm shadow-sm border border-gray-200 transition-all flex items-center uppercase tracking-widest">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Refresh
                </button>
                <button onclick="exportReviewsCSV()" class="bg-[#F25996] hover:bg-[#e04481] text-white px-6 py-3 rounded-full font-bold text-sm shadow-sm transition-all flex items-center uppercase tracking-widest">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export Excel
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Overview (Optional, could add later) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center">
            <div class="p-3 bg-brand-50 rounded-xl mr-4 text-brand-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Reviews</p>
                <p class="text-2xl font-black text-gray-900"><?= count($reviews) ?></p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center">
            <div class="p-3 bg-green-50 rounded-xl mr-4 text-green-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Approved</p>
                <p class="text-2xl font-black text-gray-900"><?= count(array_filter($reviews, fn($r) => $r['is_approved'])) ?></p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center">
            <div class="p-3 bg-amber-50 rounded-xl mr-4 text-amber-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Pending</p>
                <p class="text-2xl font-black text-gray-900"><?= count(array_filter($reviews, fn($r) => !$r['is_approved'])) ?></p>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Reviewer</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Product</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Rating</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Photos</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($reviews)): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500 font-medium">No reviews found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($reviews as $review): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('M d, Y', strtotime($review['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900"><?= htmlspecialchars($review['reviewer_name']) ?></div>
                                    <div class="text-xs text-gray-500"><?= htmlspecialchars($review['reviewer_email']) ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 line-clamp-2" title="<?= htmlspecialchars($review['product_name']) ?>">
                                        <?= htmlspecialchars($review['product_name']) ?>
                                    </div>
                                    <?php if ($review['review_text']): ?>
                                        <div class="text-xs text-gray-500 mt-1 line-clamp-1" title="<?= htmlspecialchars($review['review_text']) ?>"><?= htmlspecialchars($review['review_text']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex text-yellow-400">
                                        <?php for($i=1; $i<=5; $i++): ?>
                                            <svg class="w-4 h-4 <?= $i <= $review['rating'] ? 'fill-current' : 'text-gray-300' ?>" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <?php endfor; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                    $photos = [];
                                    if (!empty($review['photos'])) {
                                        $decoded = json_decode($review['photos'], true);
                                        if (is_array($decoded)) $photos = $decoded;
                                    }
                                    ?>
                                    <?php if (!empty($photos)): ?>
                                        <div class="flex items-center gap-1">
                                            <?php foreach (array_slice($photos, 0, 3) as $photo): ?>
                                                <a href="<?= get_image_url($photo) ?>" target="_blank">
                                                    <img src="<?= get_image_url($photo) ?>" class="w-10 h-10 object-cover rounded-lg border border-gray-200 hover:opacity-80 transition-opacity" alt="Review photo">
                                                </a>
                                            <?php endforeach; ?>
                                            <?php if (count($photos) > 3): ?>
                                                <span class="text-xs text-gray-500 font-medium ml-1">+<?= count($photos) - 3 ?> more</span>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($review['is_approved']): ?>
                                        <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-md">Approved</span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-md">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <?php if ($this->hasPermission('reviews', 'edit')): ?>
                                        <?php if (!$review['is_approved']): ?>
                                            <button onclick="updateReviewStatus(<?= $review['id'] ?>, 'approve')" class="text-green-600 hover:text-green-900 mr-3">Approve</button>
                                        <?php endif; ?>
                                        <?php if ($review['is_approved']): ?>
                                            <button onclick="updateReviewStatus(<?= $review['id'] ?>, 'reject')" class="text-amber-600 hover:text-amber-900 mr-3">Revoke</button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <button onclick="viewReview(<?= htmlspecialchars(json_encode([
                                        'id' => $review['id'],
                                        'name' => $review['reviewer_name'],
                                        'product' => $review['product_name'],
                                        'rating' => $review['rating'],
                                        'text' => $review['review_text'],
                                        'photos' => $review['photos'] ? json_decode($review['photos']) : [],
                                        'date' => date('M d, Y', strtotime($review['created_at']))
                                    ])) ?>)" class="inline-flex items-center justify-center p-2 text-gray-500 hover:text-brand-600 hover:bg-gray-100 rounded-lg transition-colors align-middle" title="View Review">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- View Review Modal -->
<div id="viewReviewModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden opacity-0 transition-opacity duration-300 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg transform scale-95 transition-transform duration-300 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center p-6 border-b border-gray-100">
            <h2 class="text-xl font-bold text-gray-900">Review Details</h2>
            <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6">
            <div class="mb-4">
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Product</p>
                <p id="vrProduct" class="text-gray-900 font-medium"></p>
            </div>
            <div class="flex justify-between items-center mb-4 border-t border-gray-100 pt-4">
                <div>
                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Reviewer</p>
                    <p id="vrName" class="text-gray-900 font-medium"></p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Date</p>
                    <p id="vrDate" class="text-gray-900 font-medium"></p>
                </div>
            </div>
            <div class="mb-4 border-t border-gray-100 pt-4">
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-2">Rating</p>
                <div id="vrRating" class="flex text-yellow-400"></div>
            </div>
            <div class="mb-4 border-t border-gray-100 pt-4">
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-2">Review Text</p>
                <p id="vrText" class="text-gray-700 text-sm italic"></p>
            </div>
            <div id="vrPhotosContainer" class="mb-4 border-t border-gray-100 pt-4 hidden">
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-2">Photos</p>
                <div id="vrPhotos" class="flex flex-wrap gap-2"></div>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Modal -->
<div id="confirm-modal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden opacity-0 transition-opacity duration-300 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm transform scale-95 transition-transform duration-300 overflow-hidden text-center p-6">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-amber-100 mb-4" id="confirm-icon-bg">
            <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="confirm-icon"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2" id="confirm-title">Confirm Action</h3>
        <p class="text-sm text-gray-500 mb-6" id="confirm-message">Are you sure you want to proceed?</p>
        <div class="flex space-x-3">
            <button type="button" onclick="closeConfirmModal()" class="flex-1 px-4 py-2.5 border border-gray-200 rounded-lg text-sm font-bold text-[#F25996] hover:bg-pink-50 transition-colors">Cancel</button>
            <button type="button" id="confirm-action-btn" class="flex-1 px-4 py-2.5 bg-brand-600 text-white rounded-lg text-sm font-medium hover:bg-brand-700 transition-colors">Confirm</button>
        </div>
    </div>
</div>

<script>
let pendingReviewId = null;
let pendingStatus = null;

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
        msg.innerText = 'Are you sure you want to approve this review?';
        btn.innerText = 'Approve';
        btn.className = 'flex-1 px-4 py-2.5 bg-[#F25996] text-white rounded-lg text-sm font-medium hover:bg-[#d8407d] transition-colors';
        iconBg.className = 'mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-pink-100 mb-4';
        icon.className = 'h-6 w-6 text-[#F25996]';
    } else {
        title.innerText = 'Revoke/Reject Review';
        msg.innerText = 'Are you sure you want to reject this review?';
        btn.innerText = 'Reject';
        btn.className = 'flex-1 px-4 py-2.5 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-colors';
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
            alert(data.message);
            closeConfirmModal();
            this.innerText = originalText;
            this.disabled = false;
        }
    })
    .catch(err => {
        console.error(err);
        alert('An error occurred');
        closeConfirmModal();
        this.innerText = originalText;
        this.disabled = false;
    });
});

function viewReview(review) {
    document.getElementById('vrProduct').innerText = review.product;
    document.getElementById('vrName').innerText = review.name;
    document.getElementById('vrDate').innerText = review.date;
    document.getElementById('vrText').innerText = review.text || 'No text provided.';
    
    // Set stars
    let starsHtml = '';
    for(let i=1; i<=5; i++) {
        const colorClass = i <= review.rating ? 'fill-current' : 'text-gray-300';
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
            phHtml += `<a href="${src}" target="_blank"><img src="${src}" class="w-20 h-20 object-cover rounded border border-gray-200 hover:opacity-80 transition-opacity"></a>`;
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

function exportReviewsCSV() {
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
    link.setAttribute("download", "reviews_export_<?= date('Y-m-d') ?>.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
</div>


