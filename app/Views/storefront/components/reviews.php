<?php
/**
 * Dynamic Customer Reviews Component for Storefront Landing Page
 * Injected with $contentData from cms_components content_data JSON
 */

$title = isset($contentData['title']) ? trim($contentData['title']) : '';
$subtitle = isset($contentData['subtitle']) ? trim($contentData['subtitle']) : '';
$animation = $contentData['animation'] ?? 'fade';
$validAnimations = ['fade', 'slide_right', 'slide_left', 'zoom'];
if (!in_array($animation, $validAnimations)) {
    $animation = 'fade';
}

$autoplay = !empty($contentData['autoplay']);
$autoplaySpeed = max(2, min(15, (int)($contentData['autoplay_speed'] ?? 4)));
$cardsDesktop = max(1, min(4, (int)($contentData['cards_desktop'] ?? 3)));
$cardsTablet = max(1, min(3, (int)($contentData['cards_tablet'] ?? 2)));

$bgColor = !empty($contentData['bg_color']) ? $contentData['bg_color'] : '#ffffff';
$cardBgColor = !empty($contentData['card_bg_color']) ? $contentData['card_bg_color'] : '#ffffff';
$starColor = !empty($contentData['star_color']) ? $contentData['star_color'] : '#f59e0b';
$accentColor = !empty($contentData['accent_color']) ? $contentData['accent_color'] : '#F25996';
$arrowBgColor = !empty($contentData['arrow_bg_color']) ? $contentData['arrow_bg_color'] : '#ffffff';
$arrowIconColor = !empty($contentData['arrow_icon_color']) ? $contentData['arrow_icon_color'] : '#1f2937';
$dotColor = !empty($contentData['dot_color']) ? $contentData['dot_color'] : '#d1d5db';
$dotActiveColor = !empty($contentData['dot_active_color']) ? $contentData['dot_active_color'] : (!empty($contentData['accent_color']) ? $contentData['accent_color'] : '#F25996');
$showProduct = isset($contentData['show_product']) ? (bool)$contentData['show_product'] : true;
$showVerified = isset($contentData['show_verified']) ? (bool)$contentData['show_verified'] : true;
$showArrows = !isset($contentData['show_arrows']) || !empty($contentData['show_arrows']);
$showDots = !isset($contentData['show_dots']) || !empty($contentData['show_dots']);
$containerHeight = !empty($contentData['container_height']) && trim($contentData['container_height']) !== '' ? trim($contentData['container_height']) : 'auto';
$mobileContainerHeight = !empty($contentData['mobile_container_height']) && trim($contentData['mobile_container_height']) !== '' ? trim($contentData['mobile_container_height']) : 'auto';

$selectedIds = !empty($contentData['selected_review_ids']) && is_array($contentData['selected_review_ids'])
    ? array_map('intval', $contentData['selected_review_ids'])
    : [];

$displayReviews = [];

try {
    $db = \Core\Database::getInstance();
    if (!empty($selectedIds)) {
        $placeholders = implode(',', array_fill(0, count($selectedIds), '?'));
        $sql = "
            SELECT 
                r.*,
                p.name AS product_name,
                p.id AS product_id,
                (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) AS product_image,
                u.name AS reviewer_name
            FROM reviews r
            JOIN products p ON r.product_id = p.id
            JOIN users u ON r.user_id = u.id
            WHERE r.id IN ($placeholders)
            ORDER BY FIELD(r.id, $placeholders)
        ";
        $params = array_merge($selectedIds, $selectedIds);
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $displayReviews = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    } else {
        // Fallback: fetch top 6 approved reviews if none specifically selected
        $stmt = $db->query("
            SELECT 
                r.*,
                p.name AS product_name,
                p.id AS product_id,
                (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) AS product_image,
                u.name AS reviewer_name
            FROM reviews r
            JOIN products p ON r.product_id = p.id
            JOIN users u ON r.user_id = u.id
            WHERE r.is_approved = 1
            ORDER BY r.created_at DESC
            LIMIT 6
        ");
        $displayReviews = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
} catch (\Exception $e) {
    $displayReviews = [];
}

if (empty($displayReviews)) {
    return; // Don't render empty section on storefront
}

$sectionUid = 'rev_sec_' . substr(md5(uniqid((string)mt_rand(), true)), 0, 8);
?>

<?php 
$hasCustomHeight = ($containerHeight !== 'auto' || $mobileContainerHeight !== 'auto');
?>

<?php if ($hasCustomHeight): ?>
<style>
<?php if ($mobileContainerHeight !== 'auto'): ?>
#<?= $sectionUid ?> {
    min-height: <?= htmlspecialchars($mobileContainerHeight) ?>;
}
<?php endif; ?>
<?php if ($containerHeight !== 'auto'): ?>
@media (min-width: 768px) {
    #<?= $sectionUid ?> {
        min-height: <?= htmlspecialchars($containerHeight) ?>;
    }
}
<?php endif; ?>
</style>
<?php endif; ?>

<section class="cms-reviews-section w-full relative py-4 sm:py-6 md:py-8 <?= $hasCustomHeight ? 'flex flex-col justify-center' : '' ?>" id="<?= $sectionUid ?>" style="background-color: <?= htmlspecialchars($bgColor) ?>;">
    <div class="carousel-outer-wrap w-full max-w-full mx-auto px-1 sm:px-6 lg:px-8">
        <div class="w-full relative">
            
            <!-- Section Header -->
            <div class="mb-4 sm:mb-6 px-1 sm:px-2">
                <?php if (!empty($title) || !empty($subtitle)): ?>
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] sm:text-[11px] font-bold uppercase tracking-wider mb-1.5 shadow-xs" style="background-color: <?= htmlspecialchars($accentColor) ?>15; color: <?= htmlspecialchars($accentColor) ?>;">
                            <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            Customer Feedback
                        </div>
                        <?php if (!empty($title)): ?>
                            <h2 class="text-xl sm:text-2xl md:text-3xl font-serif font-bold text-gray-900 tracking-tight leading-tight">
                                <?= htmlspecialchars($title) ?>
                            </h2>
                        <?php endif; ?>
                        <?php if (!empty($subtitle)): ?>
                            <p class="mt-1 text-xs sm:text-sm text-gray-500 font-normal leading-normal">
                                <?= htmlspecialchars($subtitle) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Carousel Outer Container with Left & Right Side Arrows -->
            <div class="relative px-0 sm:px-1">
                <?php if ($showArrows): ?>
                    <button type="button" class="rev-prev-btn absolute left-0.5 sm:left-1 top-1/2 -translate-y-1/2 z-30 w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 rounded-full border border-gray-200/90 shadow-md flex items-center justify-center transition-all duration-200 focus:outline-none hover:scale-105 active:scale-95 cursor-pointer" style="background-color: <?= htmlspecialchars($arrowBgColor) ?>; color: <?= htmlspecialchars($arrowIconColor) ?>;" aria-label="Previous Reviews">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <button type="button" class="rev-next-btn absolute right-0.5 sm:right-1 top-1/2 -translate-y-1/2 z-30 w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 rounded-full border border-gray-200/90 shadow-md flex items-center justify-center transition-all duration-200 focus:outline-none hover:scale-105 active:scale-95 cursor-pointer" style="background-color: <?= htmlspecialchars($arrowBgColor) ?>; color: <?= htmlspecialchars($arrowIconColor) ?>;" aria-label="Next Reviews">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                <?php endif; ?>

                <!-- Carousel Viewport -->
                <div class="rev-carousel-viewport overflow-hidden relative mx-6 sm:mx-8 md:mx-10">
                    <div class="rev-track flex transition-transform duration-500 ease-out" data-animation="<?= htmlspecialchars($animation) ?>">
                    <?php foreach ($displayReviews as $idx => $rev): 
                        $rating = max(1, min(5, (int)$rev['rating']));
                        $reviewerName = !empty($rev['reviewer_name']) ? $rev['reviewer_name'] : 'Verified Buyer';
                        $initials = strtoupper(substr($reviewerName, 0, 1));
                        
                        $productImg = !empty($rev['product_image']) ? $rev['product_image'] : 'https://placehold.co/100x100?text=Product';
                        if (!str_starts_with($productImg, 'http') && !str_starts_with($productImg, '/')) {
                            $productImg = '/' . ltrim($productImg, '/');
                        }
                        $dateStr = !empty($rev['created_at']) ? date('M j, Y', strtotime($rev['created_at'])) : '';
                    ?>
                    <div class="rev-slide flex-shrink-0 px-2 sm:px-2.5 flex flex-col justify-stretch" style="width: <?= 100 / $cardsDesktop ?>%;">
                        <div class="rev-card h-full rounded-2xl p-4 sm:p-5 shadow-xs border border-gray-100 flex flex-col justify-between gap-3 transition-all duration-300 hover:shadow-md hover:-translate-y-0.5 relative" style="background-color: <?= htmlspecialchars($cardBgColor) ?>;">
                            
                            <!-- Top Row: Rating & Quote Icon -->
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <!-- Star Rating -->
                                    <div class="flex items-center gap-0.5">
                                        <?php for ($s = 1; $s <= 5; $s++): ?>
                                            <svg class="w-3.5 h-3.5 <?= $s <= $rating ? 'fill-current' : 'text-gray-200 fill-current' ?>" style="<?= $s <= $rating ? 'color: ' . htmlspecialchars($starColor) . ';' : '' ?>" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        <?php endfor; ?>
                                        <span class="text-[11px] font-bold text-gray-500 ml-1"><?= $rating ?>.0</span>
                                    </div>

                                    <!-- Decorative Quote Mark -->
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center opacity-70" style="background-color: <?= htmlspecialchars($accentColor) ?>15; color: <?= htmlspecialchars($accentColor) ?>;">
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Review Text -->
                                <p class="text-gray-700 text-xs sm:text-[13px] leading-relaxed line-clamp-3 font-normal mb-1">
                                    "<?= htmlspecialchars($rev['review_text'] ?: 'Excellent experience and great quality!') ?>"
                                </p>
                            </div>

                            <!-- Bottom Section: Customer Info + Product Link -->
                            <div class="pt-2.5 border-t border-gray-100 flex flex-col gap-2 mt-auto">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs text-white shadow-xs shrink-0" style="background-color: <?= htmlspecialchars($accentColor) ?>;">
                                            <?= htmlspecialchars($initials) ?>
                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="font-bold text-gray-900 text-xs sm:text-[13px] leading-tight flex items-center gap-1 truncate">
                                                <span class="truncate"><?= htmlspecialchars($reviewerName) ?></span>
                                                <?php if ($showVerified): ?>
                                                    <span class="inline-flex items-center text-emerald-600 shrink-0" title="Verified Buyer">
                                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                    </span>
                                                <?php endif; ?>
                                            </h4>
                                            <?php if (!empty($dateStr)): ?>
                                                <span class="text-[10px] text-gray-400 block mt-0.5"><?= htmlspecialchars($dateStr) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Optional Product Pill -->
                                <?php if ($showProduct && !empty($rev['product_name'])): ?>
                                    <a href="<?= BASE_URL ?>/product?id=<?= $rev['product_id'] ?>" class="flex items-center gap-2 p-1.5 px-2 rounded-lg bg-gray-50/80 hover:bg-gray-100/90 transition-colors group/item">
                                        <img src="<?= htmlspecialchars($productImg) ?>" class="w-6 h-6 rounded-md object-cover border border-gray-100 flex-shrink-0" alt="<?= htmlspecialchars($rev['product_name']) ?>">
                                        <span class="text-[11px] text-gray-600 font-medium truncate group-hover/item:text-gray-900 transition-colors" title="<?= htmlspecialchars($rev['product_name']) ?>">
                                            <?= htmlspecialchars($rev['product_name']) ?>
                                        </span>
                                    </a>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Pagination Dots -->
        <?php if ($showDots): ?>
            <div class="rev-dots flex items-center justify-center gap-1.5 mt-4 sm:mt-5"></div>
        <?php endif; ?>

        </div>
    </div>
</section>

<!-- Scoped CSS for Animations & Hover Styles -->
<style>
#<?= $sectionUid ?> .font-serif {
    font-family: 'Playfair Display', Georgia, serif;
}
#<?= $sectionUid ?> .rev-prev-btn:hover,
#<?= $sectionUid ?> .rev-next-btn:hover {
    filter: brightness(0.95);
    transform: translateY(-50%) scale(1.05);
}
#<?= $sectionUid ?> .rev-dot-btn {
    background-color: <?= htmlspecialchars($dotColor) ?> !important;
}
#<?= $sectionUid ?> .rev-dot-btn.active {
    background-color: <?= htmlspecialchars($dotActiveColor) ?> !important;
}

/* Animations Definitions */
@keyframes revFadeAnim {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes revSlideRightAnim {
    from { opacity: 0; transform: translateX(-35px); }
    to { opacity: 1; transform: translateX(0); }
}

@keyframes revSlideLeftAnim {
    from { opacity: 0; transform: translateX(35px); }
    to { opacity: 1; transform: translateX(0); }
}

@keyframes revZoomAnim {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}

/* Active animation classes applied dynamically */
#<?= $sectionUid ?> .anim-fade {
    animation: revFadeAnim 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
#<?= $sectionUid ?> .anim-slide_right {
    animation: revSlideRightAnim 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
#<?= $sectionUid ?> .anim-slide_left {
    animation: revSlideLeftAnim 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
#<?= $sectionUid ?> .anim-zoom {
    animation: revZoomAnim 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

/* Responsive adjustments for desktop/tablet/mobile */
@media (max-width: 1024px) {
    #<?= $sectionUid ?> .rev-slide {
        width: <?= 100 / $cardsTablet ?>% !important;
    }
}
@media (max-width: 640px) {
    #<?= $sectionUid ?> .rev-slide {
        width: 100% !important;
    }
}
</style>

<!-- Carousel Interaction & Autoplay Script -->
<script>
(function() {
    const sec = document.getElementById('<?= $sectionUid ?>');
    if (!sec) return;

    const track = sec.querySelector('.rev-track');
    const slides = sec.querySelectorAll('.rev-slide');
    const prevBtn = sec.querySelector('.rev-prev-btn');
    const nextBtn = sec.querySelector('.rev-next-btn');
    const dotsContainer = sec.querySelector('.rev-dots');

    const totalSlides = slides.length;
    let currentIndex = 0;
    let autoplayTimer = null;

    const animationType = '<?= $animation ?>';
    const isAutoplay = <?= $autoplay ? 'true' : 'false' ?>;
    const autoplaySpeed = <?= $autoplaySpeed * 1000 ?>;

    function getVisibleCount() {
        if (window.innerWidth <= 640) return 1;
        if (window.innerWidth <= 1024) return <?= $cardsTablet ?>;
        return <?= $cardsDesktop ?>;
    }

    function getMaxIndex() {
        return Math.max(0, totalSlides - getVisibleCount());
    }

    function buildDots() {
        if (!dotsContainer) return;
        dotsContainer.innerHTML = '';
        const max = getMaxIndex();
        if (max <= 0) return;

        for (let i = 0; i <= max; i++) {
            const dot = document.createElement('button');
            dot.type = 'button';
            dot.className = 'rev-dot-btn rounded-full transition-all duration-300 ' + (i === currentIndex ? 'w-8 h-2.5 active' : 'w-2.5 h-2.5 opacity-60 hover:opacity-100');
            dot.setAttribute('aria-label', 'Go to slide ' + (i + 1));
            dot.addEventListener('click', () => {
                goToSlide(i);
                restartAutoplay();
            });
            dotsContainer.appendChild(dot);
        }
    }

    function updateDots() {
        if (!dotsContainer) return;
        const dots = dotsContainer.querySelectorAll('button');
        dots.forEach((dot, idx) => {
            if (idx === currentIndex) {
                dot.className = 'rev-dot-btn w-8 h-2.5 rounded-full transition-all duration-300 active';
            } else {
                dot.className = 'rev-dot-btn w-2.5 h-2.5 rounded-full transition-all duration-300 opacity-60 hover:opacity-100';
            }
        });
    }

    function applyAnimation() {
        slides.forEach((slide) => {
            const card = slide.querySelector('.rev-card');
            if (card) {
                card.classList.remove('anim-fade', 'anim-slide_right', 'anim-slide_left', 'anim-zoom');
                // Trigger reflow to restart keyframe animation
                void card.offsetWidth;
                card.classList.add('anim-' + animationType);
            }
        });
    }

    function goToSlide(index) {
        const max = getMaxIndex();
        if (index < 0) {
            currentIndex = max;
        } else if (index > max) {
            currentIndex = 0;
        } else {
            currentIndex = index;
        }

        const visibleCount = getVisibleCount();
        const slidePercent = 100 / visibleCount;
        const translateX = -(currentIndex * slidePercent);
        track.style.transform = `translateX(${translateX}%)`;

        applyAnimation();
        updateDots();
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            goToSlide(currentIndex - 1);
            restartAutoplay();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            goToSlide(currentIndex + 1);
            restartAutoplay();
        });
    }

    function startAutoplay() {
        if (!isAutoplay || totalSlides <= getVisibleCount()) return;
        stopAutoplay();
        autoplayTimer = setInterval(() => {
            goToSlide(currentIndex + 1);
        }, autoplaySpeed);
    }

    function stopAutoplay() {
        if (autoplayTimer) {
            clearInterval(autoplayTimer);
            autoplayTimer = null;
        }
    }

    function restartAutoplay() {
        stopAutoplay();
        startAutoplay();
    }

    // Pause on hover
    sec.addEventListener('mouseenter', stopAutoplay);
    sec.addEventListener('mouseleave', startAutoplay);

    // Responsive resize handling
    window.addEventListener('resize', () => {
        buildDots();
        goToSlide(Math.min(currentIndex, getMaxIndex()));
    });

    // Initial setup
    buildDots();
    applyAnimation();
    startAutoplay();
})();
</script>
