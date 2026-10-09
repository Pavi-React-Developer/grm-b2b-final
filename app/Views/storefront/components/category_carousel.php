<?php
// Extracted from $contentData which is passed in via home.php
$id = uniqid('cat_carousel_');
$title = $contentData['title'] ?? '';
$sort_order = $contentData['sort_order'] ?? 0;
$mobile_count = max(1, (int)($contentData['mobile_count'] ?? 2));
$desktop_count = (int)($contentData['desktop_count'] ?? 6);
if ($desktop_count < 2) {
    $desktop_count = 6;
}
$cta_text = $contentData['cta_text'] ?? '';
$cta_url = $contentData['cta_url'] ?? '';
$cta_url = trim((string)$cta_url) !== '' ? $cta_url : (BASE_URL . '/catalog');
$cta_position = $contentData['cta_position'] ?? 'Right';
$show_arrows = $contentData['show_arrows'] ?? true;
$show_dots = $contentData['show_dots'] ?? true;
$category_ids = $contentData['category_ids'] ?? [];
$theme = $contentData['theme'] ?? ['type' => 'solid', 'value' => '#ffffff'];
$title_color = $contentData['title_color'] ?? '#111827';
$subtitle_color = $contentData['subtitle_color'] ?? '#4b5563';
$text_color = $contentData['text_color'] ?? '#111827';
$button_bg_color = $contentData['button_bg_color'] ?? '#4a3628';
$button_text_color = $contentData['button_text_color'] ?? '#ffffff';
$arrow_color = !empty($contentData['arrow_color']) && $contentData['arrow_color'] !== '#ffffff' && $contentData['arrow_color'] !== '#111827' && $contentData['arrow_color'] !== '#1f2937' ? $contentData['arrow_color'] : '#F25996';
$arrow_bg_color = $contentData['arrow_bg_color'] ?? '#ffffff';
$dot_color = $contentData['dot_color'] ?? '#8c6a4f';

// Layout & Sizing
$container_height = $contentData['container_height'] ?? '';
$mobile_container_height = $contentData['mobile_container_height'] ?? '';
$image_fit = $contentData['image_fit'] ?? 'cover';

$objectFitClass = 'object-cover';
if ($image_fit === 'contain') $objectFitClass = 'object-contain bg-gray-50';
if ($image_fit === 'stretch') $objectFitClass = 'object-fill';

// Height styles
$heightStyle = '';
if (!empty($container_height) || !empty($mobile_container_height)) {
    $mHeight = !empty($mobile_container_height) ? "min-height: {$mobile_container_height}vh;" : '';
    $dHeight = !empty($container_height) ? "min-height: {$container_height}vh;" : '';
    $heightStyle = "style=\"$mHeight\" data-desktop-height=\"$dHeight\"";
}

// Background styling
$bgStyle = '';
if (!empty($theme['value'])) {
    $bgStyle = 'background: ' . $theme['value'] . ';';
}

// Find actual category data from the globally injected $categories array in home.php
$carouselCategories = [];
if (isset($categories) && is_array($categories)) {
    foreach ($categories as $c) {
        if (in_array($c['id'], $category_ids)) {
            $carouselCategories[] = $c;
        }
    }
}

$actualCount = count($carouselCategories);

// Calculate adaptive items per view so fewer items are displayed prominently instead of tiny thumbnails
if ($actualCount > 0 && $actualCount < $desktop_count) {
    $effectiveDesktopCount = max(2, min($desktop_count, $actualCount));
} else {
    $effectiveDesktopCount = max(2, $desktop_count);
}

$effectiveMobileCount = max(1, min($mobile_count, $actualCount));

$mobileWidth = 100 / $effectiveMobileCount;
$desktopWidth = 100 / $effectiveDesktopCount;
?>

<style>
    #<?= $id ?> .slide-item {
        width: calc(<?= $mobileWidth ?>% - <?= (($effectiveMobileCount - 1) * 0.75) / $effectiveMobileCount ?>rem);
    }
    #<?= $id ?> .carousel-btn {
        top: 38%; /* Center vertically with category image */
        width: 2.25rem;
        height: 2.25rem;
        color: #F25996 !important;
    }
    #<?= $id ?> .carousel-btn svg {
        width: 1rem;
        height: 1rem;
        stroke: #F25996 !important;
    }
    #<?= $id ?> .carousel-btn:hover {
        color: #e04481 !important;
    }
    #<?= $id ?> .carousel-btn:hover svg {
        stroke: #e04481 !important;
    }
    #prev_<?= $id ?> { left: -0.25rem; }
    #next_<?= $id ?> { right: -0.25rem; }
    #track_container_<?= $id ?> { padding-left: 0.25rem; padding-right: 0.25rem; }
    #inner_<?= $id ?> { gap: 1rem !important; }
    
    @media (max-width: 767px) {
        #<?= $id ?> .cat-outer-wrap {
            padding-left: 0.25rem !important;
            padding-right: 0.25rem !important;
        }
        #<?= $id ?> .cat-bg-wrap {
            padding: 1.25rem 0.85rem 1rem 0.85rem !important;
            border-radius: 1.5rem !important;
        }
        #track_container_<?= $id ?> {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
    }

    #<?= $id ?> .slider-dot {
        width: 8px;
        height: 8px;
        background-color: <?= htmlspecialchars($dot_color) ?> !important;
        border-radius: 9999px;
        border: none;
        cursor: pointer;
        padding: 0;
        transition: all 0.3s ease;
        opacity: 0.5;
        display: inline-block;
        vertical-align: middle;
    }
    #<?= $id ?> .slider-dot.active {
        width: 10px;
        height: 10px;
        background-color: <?= htmlspecialchars($dot_color) ?> !important;
        opacity: 1;
        outline: 2px solid <?= htmlspecialchars($dot_color) ?> !important;
        outline-offset: 2px;
    }

    @media (max-width: 767px) {
        #<?= $id ?> {
            min-height: 0 !important;
            height: auto !important;
        }
        #<?= $id ?> .slide-item {
            width: calc(<?= $mobileWidth ?>% - <?= (($effectiveMobileCount - 1) * 0.75) / $effectiveMobileCount ?>rem) !important;
            flex: 0 0 calc(<?= $mobileWidth ?>% - <?= (($effectiveMobileCount - 1) * 0.75) / $effectiveMobileCount ?>rem) !important;
            max-width: 140px !important;
        }
        #<?= $id ?> .category-card-img {
            width: 100% !important;
            max-width: 140px !important;
            aspect-ratio: 1 / 1 !important;
            border-radius: 1.25rem !important;
            margin-left: auto !important;
            margin-right: auto !important;
            margin-bottom: 0.4rem !important;
        }
        #<?= $id ?> .category-slide-link {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        #<?= $id ?> .category-name {
            font-size: 0.875rem !important;
            font-weight: 700 !important;
            word-break: break-word;
            font-family: inherit !important;
        }
        #<?= $id ?> .category-view-all {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-height: 1.75rem !important;
            height: auto !important;
            padding: 0.35rem 0.85rem !important;
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            letter-spacing: 0.05em !important;
            border-radius: 9999px !important;
            border: 1px solid <?= htmlspecialchars($button_text_color ?? '#ffffff') ?> !important;
            background: transparent !important;
            color: <?= htmlspecialchars($button_text_color ?? '#ffffff') ?> !important;
            white-space: nowrap !important;
            line-height: 1 !important;
        }
        #<?= $id ?> .category-carousel-header {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
        }
        #<?= $id ?> .category-carousel-header h2 {
            text-transform: uppercase !important;
            letter-spacing: 0.04em !important;
            font-size: 1.15rem !important;
            line-height: 1.25 !important;
            white-space: normal !important;
        }
        #<?= $id ?> .category-carousel-header .category-cta-wrap {
            align-self: flex-end !important;
        }
    }

    @media (min-width: 768px) {
        #<?= $id ?> .slide-item {
            width: calc(<?= $desktopWidth ?>% - <?= (($effectiveDesktopCount - 1) * 1.5) / $effectiveDesktopCount ?>rem) !important;
            flex: 0 0 calc(<?= $desktopWidth ?>% - <?= (($effectiveDesktopCount - 1) * 1.5) / $effectiveDesktopCount ?>rem) !important;
            max-width: 190px !important;
            min-width: 120px !important;
        }
        #<?= $id ?> .category-card-img {
            width: 100% !important;
            max-width: 190px !important;
            aspect-ratio: 1 / 1 !important;
            border-radius: 1.5rem !important;
            margin-left: auto !important;
            margin-right: auto !important;
            margin-bottom: 0.5rem !important;
            box-shadow: 0 4px 15px -2px rgba(0, 0, 0, 0.06) !important;
        }
        #<?= $id ?> .category-name {
            font-size: 1rem !important;
            font-weight: 700 !important;
            letter-spacing: 0.02em !important;
        }
        #<?= $id ?> .category-view-all {
            min-height: 2.25rem !important;
            padding: 0.4rem 1.25rem !important;
            font-size: 0.8rem !important;
            border-width: 1.5px !important;
        }
        #<?= $id ?> .carousel-btn {
            top: 40%;
            width: 2.25rem;
            height: 2.25rem;
        }
        #<?= $id ?> .carousel-btn svg {
            width: 1.1rem;
            height: 1.1rem;
        }
        #prev_<?= $id ?> { left: 0.25rem; }
        #next_<?= $id ?> { right: 0.25rem; }
        #track_container_<?= $id ?> { padding-left: 1.5rem; padding-right: 1.5rem; }
        #inner_<?= $id ?> { gap: 1.75rem !important; }
        #<?= $id ?> .slider-dot {
            width: 8px;
            height: 8px;
        }
        #<?= $id ?> .slider-dot.active {
            width: 10px;
            height: 10px;
            outline: 2px solid <?= htmlspecialchars($dot_color) ?> !important;
            outline-offset: 2px;
        }
    }
</style>

<div class="w-full relative py-3 sm:py-4 md:py-6" id="<?= $id ?>" <?= $heightStyle ?>>
    <script>
        (function() {
            var el = document.getElementById('<?= $id ?>');
            if (el && el.getAttribute('data-desktop-height') && window.innerWidth >= 768) {
                el.style.cssText += el.getAttribute('data-desktop-height');
            }
        })();
    </script>
    <div class="cat-outer-wrap w-full max-w-full mx-auto px-1 sm:px-6 lg:px-8 h-full flex flex-col justify-center">
        <div class="cat-bg-wrap rounded-2xl md:rounded-[2rem] p-3.5 sm:p-5 md:p-6 lg:p-7 relative w-full" style="<?= $bgStyle ?>">
            <div class="w-full relative">
                
                <!-- Header -->
                <div class="category-carousel-header flex flex-col md:flex-row items-start md:items-center justify-between gap-2.5 md:gap-4 mb-3 md:mb-8">
                    <?php if ($cta_position === 'Center'): ?>
                        <div class="flex-1 hidden md:block"></div>
                    <?php endif; ?>
                    
                    <h2 class="text-base sm:text-2xl md:text-3xl font-serif font-bold uppercase tracking-wider leading-tight <?= $cta_position === 'Center' ? 'md:text-center flex-1' : '' ?>" style="color: <?= htmlspecialchars($title_color) ?>;">
                        <?= htmlspecialchars($title) ?>
                    </h2>
                    
                    <?php if ($cta_text): ?>
                        <div class="category-cta-wrap self-end md:self-auto shrink-0 <?= $cta_position === 'Center' ? 'flex-1 md:text-right' : '' ?>">
                            <a href="<?= htmlspecialchars($cta_url) ?>" class="category-view-all text-xs md:text-sm font-bold tracking-wider uppercase hover:opacity-80 px-3 sm:px-6 py-1.5 sm:py-2 rounded-full transition-all inline-flex items-center justify-center whitespace-nowrap" style="border: 1.5px solid <?= htmlspecialchars($button_text_color ?? '#ffffff') ?>; color: <?= htmlspecialchars($button_text_color ?? '#ffffff') ?>; background-color: transparent;">
                                <?= htmlspecialchars($cta_text) ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Slider Container -->
                <?php $isFewItems = count($carouselCategories) <= 4; ?>
                <div class="relative group/carousel" id="track_container_<?= $id ?>">
                    
                    <!-- Track -->
                    <div class="overflow-hidden touch-pan-y" id="track_<?= $id ?>">
                        <div class="flex transition-transform duration-500 ease-out <?= $isFewItems ? 'justify-center' : '' ?>" id="inner_<?= $id ?>" style="transform: translateX(0%);">
                            <?php if (empty($carouselCategories)): ?>
                                <div class="w-full py-20 text-center text-gray-500 bg-white/50 rounded-2xl">
                                    No categories selected for this carousel.
                                </div>
                            <?php else: ?>
                                <?php foreach ($carouselCategories as $cat): 
                                    $img = !empty($cat['image_path']) ? (str_starts_with($cat['image_path'], 'http') ? $cat['image_path'] : (BASE_URL . '/' . ltrim($cat['image_path'], '/'))) : 'https://placehold.co/400?text=' . urlencode($cat['name']);
                                ?>
                                    <!-- Slide Item -->
                                    <div class="slide-item flex-none">
                                        <a href="<?= BASE_URL ?>/category?id=<?= $cat['id'] ?>" class="category-slide-link block group text-center px-0">
                                            <div class="category-card-img w-full aspect-square rounded-2xl md:rounded-[1.5rem] bg-white shadow-sm hover:shadow-md transition-all border border-gray-100 flex flex-col relative overflow-hidden mb-3">
                                                <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($cat['name']) ?>" class="w-full h-full <?= $objectFitClass ?> group-hover:scale-105 transition-transform duration-500 rounded-2xl md:rounded-[1.5rem]" style="object-fit: <?= htmlspecialchars($image_fit ?: 'cover') ?>; width: 100%; height: 100%; display: block;">
                                            </div>
                                            <h3 class="category-name font-serif font-bold text-center leading-snug tracking-wide" style="font-size: 1rem; color: <?= htmlspecialchars($text_color) ?>;"><?= htmlspecialchars($cat['name']) ?></h3>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Arrows -->
                    <?php if ($show_arrows && !empty($carouselCategories) && !$isFewItems): ?>
                        <button id="prev_<?= $id ?>" class="carousel-btn absolute -translate-y-1/2 rounded-full flex items-center justify-center shadow-md border border-gray-200 hover:shadow-lg hover:scale-105 active:scale-95 transition-all disabled:opacity-0 disabled:cursor-not-allowed z-20" style="color: <?= htmlspecialchars($arrow_color) ?>; background-color: <?= htmlspecialchars($arrow_bg_color) ?>;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                        </button>
                        <button id="next_<?= $id ?>" class="carousel-btn absolute -translate-y-1/2 rounded-full flex items-center justify-center shadow-md border border-gray-200 hover:shadow-lg hover:scale-105 active:scale-95 transition-all disabled:opacity-0 disabled:cursor-not-allowed z-20" style="color: <?= htmlspecialchars($arrow_color) ?>; background-color: <?= htmlspecialchars($arrow_bg_color) ?>;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    <?php endif; ?>
                </div>

                <!-- Dots -->
                <?php if ($show_dots && !empty($carouselCategories)): ?>
                    <div class="mt-3 md:mt-6 flex justify-center items-center gap-2" id="dots_<?= $id ?>">
                        <!-- Dots generated by JS -->
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const track = document.getElementById('track_<?= $id ?>');
    const inner = document.getElementById('inner_<?= $id ?>');
    if (!inner || !track) return;

    const prevBtn = document.getElementById('prev_<?= $id ?>');
    const nextBtn = document.getElementById('next_<?= $id ?>');
    const dotsContainer = document.getElementById('dots_<?= $id ?>');
    const slides = inner.querySelectorAll('.slide-item');
    
    if (slides.length === 0) return;

    // Desktop/Mobile items count configuration
    const mobileCount = <?= (int)$effectiveMobileCount ?>;
    const desktopCount = <?= (int)$effectiveDesktopCount ?>;
    
    let currentIndex = 0;
    let itemsPerView = window.innerWidth >= 768 ? desktopCount : mobileCount;
    let maxIndex = Math.max(0, slides.length - itemsPerView);

    function updateSlider() {
        // Calculate exact slide width by measuring the distance between first and second slide
        let slideWidth = 0;
        if (slides.length > 1) {
            slideWidth = slides[1].getBoundingClientRect().left - slides[0].getBoundingClientRect().left;
        } else {
            slideWidth = slides[0].getBoundingClientRect().width;
        }
        
        inner.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
        
        // Update Buttons
        if (prevBtn) {
            prevBtn.disabled = currentIndex === 0;
            prevBtn.style.opacity = '1';
            prevBtn.style.visibility = 'visible';
            prevBtn.style.display = 'flex';
        }
        if (nextBtn) {
            nextBtn.disabled = currentIndex >= maxIndex;
            nextBtn.style.opacity = '1';
            nextBtn.style.visibility = 'visible';
            nextBtn.style.display = 'flex';
        }
        
        // Update Dots
        if (dotsContainer) {
            const dots = dotsContainer.querySelectorAll('.slider-dot');
            dots.forEach((dot, idx) => {
                if (idx === currentIndex) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
        }
    }

    // Initialize Dots
    if (dotsContainer) {
        if (maxIndex > 0) {
            dotsContainer.style.display = 'flex';
            for (let i = 0; i <= maxIndex; i++) {
                const dot = document.createElement('button');
                dot.className = 'slider-dot' + (i === 0 ? ' active' : '');
                dot.setAttribute('aria-label', `Slide ${i + 1}`);
                dot.onclick = () => {
                    currentIndex = i;
                    updateSlider();
                };
                dotsContainer.appendChild(dot);
            }
        } else {
            dotsContainer.style.display = 'none';
        }
    }

    if (prevBtn) {
        prevBtn.onclick = () => {
            if (currentIndex > 0) {
                currentIndex--;
                updateSlider();
            }
        };
    }

    if (nextBtn) {
        nextBtn.onclick = () => {
            if (currentIndex < maxIndex) {
                currentIndex++;
                updateSlider();
            }
        };
    }

    // Handle Resize
    window.addEventListener('resize', () => {
        const newItemsPerView = window.innerWidth >= 768 ? desktopCount : mobileCount;
        if (newItemsPerView !== itemsPerView) {
            itemsPerView = newItemsPerView;
            maxIndex = Math.max(0, slides.length - itemsPerView);
            currentIndex = Math.min(currentIndex, maxIndex);
            
            // Re-render dots
            if (dotsContainer) {
                dotsContainer.innerHTML = '';
                if (maxIndex > 0) {
                    dotsContainer.style.display = 'flex';
                    for (let i = 0; i <= maxIndex; i++) {
                        const dot = document.createElement('button');
                        dot.className = 'slider-dot' + (i === currentIndex ? ' active' : '');
                        dot.setAttribute('aria-label', `Slide ${i + 1}`);
                        dot.onclick = () => { currentIndex = i; updateSlider(); };
                        dotsContainer.appendChild(dot);
                    }
                } else {
                    dotsContainer.style.display = 'none';
                }
            }
            updateSlider();
        }
        manageAutoPlay();
    });

    updateSlider();

    // Auto-play logic for mobile only
    let autoPlayInterval;

    function manageAutoPlay() {
        if (autoPlayInterval) clearInterval(autoPlayInterval);
        
        // Only autoplay on mobile (width < 768px) and if there are slides to move
        if (window.innerWidth < 768 && maxIndex > 0) {
            autoPlayInterval = setInterval(() => {
                if (currentIndex < maxIndex) {
                    currentIndex++;
                } else {
                    currentIndex = 0;
                }
                updateSlider();
            }, 3000); // 3 seconds
        }
    }

    // Pause auto-play on user interaction
    track.addEventListener('touchstart', () => { if (autoPlayInterval) clearInterval(autoPlayInterval); }, {passive: true});
    track.addEventListener('touchend', manageAutoPlay);
    track.addEventListener('mouseenter', () => { if (autoPlayInterval) clearInterval(autoPlayInterval); });
    track.addEventListener('mouseleave', manageAutoPlay);

    // Initialize autoplay
    manageAutoPlay();
});
</script>
