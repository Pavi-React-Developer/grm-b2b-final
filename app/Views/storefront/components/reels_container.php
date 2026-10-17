<?php
/**
 * Dynamic Reels Container Component for Storefront Landing Page
 * 100% dynamic - Zero hardcoding
 */
$reelsData = $contentData ?? [];
$items = !empty($reelsData['items']) && is_array($reelsData['items']) ? $reelsData['items'] : [];

if (empty($items)) {
    return; // Don't render empty container
}

$title = $reelsData['title'] ?? '';
$subtitle = $reelsData['subtitle'] ?? '';
$deskHeight = !empty($reelsData['desktop_height_vh']) ? (float)$reelsData['desktop_height_vh'] : 70;
$mobHeight = !empty($reelsData['mobile_height_vh']) ? (float)$reelsData['mobile_height_vh'] : 55;
$deskCols = !empty($reelsData['desktop_view_count']) ? (float)$reelsData['desktop_view_count'] : 4;
$mobCols = !empty($reelsData['mobile_view_count']) ? (float)$reelsData['mobile_view_count'] : 2;
$autoplay = !empty($reelsData['autoplay']);
$isMuted = !empty($reelsData['muted']);
$isLoop = !empty($reelsData['loop']);
$bgColor = !empty($reelsData['bg_color']) ? $reelsData['bg_color'] : '#ffffff';
$uniqueId = 'reels_' . substr(md5(uniqid()), 0, 8);
?>

<style>
/* Dynamic Responsive Viewport Heights & Column Counts */
#<?= $uniqueId ?> {
    --reel-gap: 1rem;
    --mob-cols: <?= $mobCols ?>;
    --desk-cols: <?= $deskCols ?>;
}
#<?= $uniqueId ?> .reel-card {
    height: <?= $mobHeight ?>vh;
    min-height: 280px;
    max-height: 600px;
    flex: 0 0 calc((100% - ((var(--mob-cols) - 1) * var(--reel-gap))) / var(--mob-cols));
    scroll-snap-align: start;
}
@media (min-width: 768px) {
    #<?= $uniqueId ?> {
        --reel-gap: 1.25rem;
    }
    #<?= $uniqueId ?> .reel-card {
        height: <?= $deskHeight ?>vh;
        min-height: 400px;
        max-height: 750px;
        flex: 0 0 calc((100% - ((var(--desk-cols) - 1) * var(--reel-gap))) / var(--desk-cols));
        <?= count($items) === 1 ? 'max-width: 380px; width: 100%;' : '' ?>
    }
}
#<?= $uniqueId ?> .reels-track::-webkit-scrollbar {
    display: none;
}
#<?= $uniqueId ?> .reels-track {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>

<section id="<?= $uniqueId ?>" class="py-6 sm:py-10 relative overflow-hidden" style="background-color: <?= htmlspecialchars($bgColor) ?>;">
    <div class="w-full px-3 sm:px-5 lg:px-6">
        
        <!-- Header (without top arrows) -->
        <?php if (!empty($title) || !empty($subtitle)): ?>
            <div class="mb-4 sm:mb-6">
                <?php if (!empty($title)): ?>
                    <div class="flex items-center gap-2.5 mb-1">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#F25996] animate-pulse"></span>
                        <h2 class="text-2xl sm:text-3xl font-display font-extrabold text-gray-900 tracking-tight">
                            <?= htmlspecialchars($title) ?>
                        </h2>
                    </div>
                <?php endif; ?>
                <?php if (!empty($subtitle)): ?>
                    <p class="text-xs sm:text-sm text-gray-500 font-medium max-w-2xl">
                        <?= htmlspecialchars($subtitle) ?>
                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Track Wrapper with Navigation Arrows Positioned OUTSIDE the Reels -->
        <div class="relative px-9 sm:px-14">
            
            <?php if (count($items) > 1): ?>
                <!-- Left Nav Arrow Button (Outside Reels) -->
                <button type="button" 
                        onclick="scrollReelsTrack('<?= $uniqueId ?>', -1)" 
                        class="absolute left-0 top-1/2 -translate-y-1/2 z-30 w-9 h-9 sm:w-11 sm:h-11 rounded-full bg-white text-gray-800 shadow-xl border border-gray-200 flex items-center justify-center transition-all duration-300 hover:bg-gray-50 hover:scale-110 active:scale-95 focus:outline-none"
                        aria-label="Previous Reels">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                </button>
            <?php endif; ?>

            <!-- Horizontal Scrollable Snap Track -->
            <div class="reels-track flex items-stretch gap-4 sm:gap-5 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-4 pt-1 <?= count($items) < $deskCols ? 'justify-center' : 'justify-start' ?>" id="track-<?= $uniqueId ?>">
                <?php foreach ($items as $idx => $item): 
                    $reelTitle = !empty($item['title']) ? $item['title'] : 'Explore Style';
                    $ctaUrl = !empty($item['cta_url']) ? $item['cta_url'] : '#';
                    $ctaText = !empty($item['cta_text']) ? $item['cta_text'] : 'Shop Now';
                    $deskImg = !empty($item['desktop_image']) ? $item['desktop_image'] : '';
                    $mobImg = !empty($item['mobile_image']) ? $item['mobile_image'] : $deskImg;
                    $videoUrl = !empty($item['video_url']) ? $item['video_url'] : '';
                ?>
                    <div class="reel-card relative rounded-2xl sm:rounded-3xl overflow-hidden bg-gray-950 shadow-md group cursor-pointer transition-all duration-300 hover:shadow-2xl hover:-translate-y-1"
                         onclick="openReelModal('<?= $uniqueId ?>', <?= (int)$idx ?>)"
                         data-video="<?= htmlspecialchars($videoUrl) ?>"
                         data-title="<?= htmlspecialchars($reelTitle) ?>"
                         data-cta-url="<?= htmlspecialchars($ctaUrl) ?>"
                         data-cta-text="<?= htmlspecialchars($ctaText) ?>">
                        
                        <!-- 24-Hour Continuous Looping Video Player -->
                        <?php if (!empty($videoUrl)): ?>
                            <video class="reel-bg-video w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                   src="<?= htmlspecialchars($videoUrl) ?>"
                                   autoplay
                                   loop
                                   muted
                                   playsinline
                                   preload="auto"
                                   poster="<?= htmlspecialchars($deskImg ?: $mobImg) ?>">
                            </video>
                        <?php elseif (!empty($deskImg) || !empty($mobImg)): ?>
                            <picture class="w-full h-full block">
                                <?php if (!empty($deskImg)): ?>
                                    <source media="(min-width: 768px)" srcset="<?= htmlspecialchars($deskImg) ?>">
                                <?php endif; ?>
                                <img src="<?= htmlspecialchars($mobImg ?: $deskImg) ?>" alt="<?= htmlspecialchars($reelTitle) ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            </picture>
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-gray-900 text-gray-600">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            </div>
                        <?php endif; ?>

                        <!-- Overlay Gradients -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/20 to-black/30 pointer-events-none transition-opacity group-hover:opacity-90"></div>

                        <!-- Top Badge (Reel Icon & Play indicator) -->
                        <div class="absolute top-3 left-3 right-3 flex items-center justify-between pointer-events-none">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-black/50 backdrop-blur-md text-white border border-white/20">
                                <svg class="w-3 h-3 text-[#F25996]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/></svg>
                                Reel
                            </span>
                            <?php if (!empty($videoUrl)): ?>
                                <button type="button" onclick="toggleReelCardSound(event, this)" class="pointer-events-auto w-7 h-7 rounded-full bg-black/50 backdrop-blur-md text-white flex items-center justify-center border border-white/20 hover:bg-black/70 transition-all" title="Toggle Sound">
                                    <svg class="w-3.5 h-3.5 sound-icon-muted <?= $isMuted ? '' : 'hidden' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"></path></svg>
                                    <svg class="w-3.5 h-3.5 sound-icon-unmuted <?= $isMuted ? 'hidden' : '' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path></svg>
                                </button>
                            <?php endif; ?>
                        </div>

                        <!-- Center Play Prompt (animated on hover) -->
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="w-12 h-12 rounded-full bg-white/30 backdrop-blur-md border border-white/40 flex items-center justify-center text-white shadow-xl transform scale-90 group-hover:scale-100 transition-transform">
                                <svg class="w-6 h-6 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        </div>

                        <!-- Bottom Content & CTA -->
                        <div class="absolute bottom-0 inset-x-0 p-4 sm:p-5 flex flex-col justify-end text-white pointer-events-none z-20">
                            <h3 class="font-display font-bold text-sm sm:text-base leading-snug line-clamp-2 drop-shadow-md mb-2">
                                <?= htmlspecialchars($reelTitle) ?>
                            </h3>
                            
                            <?php if (!empty($ctaUrl) && $ctaUrl !== '#'): 
                                $finalCtaUrl = (str_starts_with($ctaUrl, 'http://') || str_starts_with($ctaUrl, 'https://')) ? $ctaUrl : (rtrim(BASE_URL, '/') . '/' . ltrim($ctaUrl, '/'));
                            ?>
                                <a href="<?= htmlspecialchars($finalCtaUrl) ?>" 
                                   onclick="event.stopPropagation(); window.location.href=this.href;" 
                                   class="pointer-events-auto relative z-30 inline-flex items-center justify-center gap-1.5 w-full py-2.5 px-3 rounded-xl bg-white/95 hover:bg-white text-gray-900 font-bold text-xs shadow-lg backdrop-blur-sm transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                                    <span><?= htmlspecialchars($ctaText) ?></span>
                                    <svg class="w-3.5 h-3.5 text-[#F25996]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (count($items) > 1): ?>
                <!-- Right Nav Arrow Button (Outside Reels) -->
                <button type="button" 
                        onclick="scrollReelsTrack('<?= $uniqueId ?>', 1)" 
                        class="absolute right-0 top-1/2 -translate-y-1/2 z-30 w-9 h-9 sm:w-11 sm:h-11 rounded-full bg-white text-gray-800 shadow-xl border border-gray-200 flex items-center justify-center transition-all duration-300 hover:bg-gray-50 hover:scale-110 active:scale-95 focus:outline-none"
                        aria-label="Next Reels">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                </button>
            <?php endif; ?>

        </div>
    </div>
</section>

<!-- Fullscreen / Modal Reel Viewer (Instagram / TikTok Style) -->
<div id="reel-modal-<?= $uniqueId ?>" class="fixed inset-0 z-50 bg-black/95 backdrop-blur-xl hidden flex items-center justify-center p-0 sm:p-4 transition-opacity">
    <!-- Close Button -->
    <button type="button" onclick="closeReelModal('<?= $uniqueId ?>')" class="absolute top-4 right-4 z-50 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center border border-white/20 transition-all">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>

    <!-- Prev & Next Arrows (Desktop) -->
    <button type="button" onclick="navigateModalReel('<?= $uniqueId ?>', -1)" class="hidden md:flex absolute left-6 top-1/2 -translate-y-1/2 z-50 w-12 h-12 rounded-full bg-white/10 hover:bg-white/25 text-white items-center justify-center border border-white/20 transition-all hover:scale-110">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
    </button>
    <button type="button" onclick="navigateModalReel('<?= $uniqueId ?>', 1)" class="hidden md:flex absolute right-6 top-1/2 -translate-y-1/2 z-50 w-12 h-12 rounded-full bg-white/10 hover:bg-white/25 text-white items-center justify-center border border-white/20 transition-all hover:scale-110">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
    </button>

    <!-- Reel Screen Card -->
    <div class="relative w-full h-full sm:h-[88vh] sm:max-w-md sm:rounded-3xl overflow-hidden bg-black shadow-2xl flex flex-col justify-between border sm:border-white/20">
        <!-- Progress bar -->
        <div class="absolute top-0 inset-x-0 h-1 bg-white/20 z-30">
            <div id="modal-progress-<?= $uniqueId ?>" class="h-full bg-[#F25996] transition-all duration-100" style="width: 0%;"></div>
        </div>

        <!-- Video Display -->
        <div class="absolute inset-0 z-10 flex items-center justify-center">
            <video id="modal-video-<?= $uniqueId ?>" class="w-full h-full object-cover" playsinline loop></video>
        </div>

        <!-- Top Controls in Modal -->
        <div class="relative z-20 p-5 flex items-center justify-between text-white">
            <span class="text-xs font-bold bg-black/40 backdrop-blur-md px-3 py-1 rounded-full border border-white/20 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-[#F25996] animate-pulse"></span>
                Reel Player
            </span>
            <button type="button" id="modal-sound-btn-<?= $uniqueId ?>" onclick="toggleModalSound('<?= $uniqueId ?>')" class="w-9 h-9 rounded-full bg-black/40 backdrop-blur-md border border-white/20 flex items-center justify-center text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path></svg>
            </button>
        </div>

        <!-- Bottom Controls in Modal -->
        <div class="relative z-20 p-6 bg-gradient-to-t from-black/95 via-black/60 to-transparent text-white flex flex-col gap-3">
            <h4 id="modal-title-<?= $uniqueId ?>" class="font-display font-bold text-lg leading-snug drop-shadow-md"></h4>
            <a id="modal-cta-<?= $uniqueId ?>" href="#" class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-[#F25996] to-rose-600 hover:from-[#e04481] hover:to-rose-700 text-white font-bold text-sm shadow-xl flex items-center justify-center gap-2 transition-all">
                <span id="modal-cta-text-<?= $uniqueId ?>">Shop Now</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
    </div>
</div>

<script>
window.reelsComponentsData = window.reelsComponentsData || {};
window.reelsComponentsData['<?= $uniqueId ?>'] = {
    items: <?= json_encode($items) ?>,
    currentIndex: 0,
    autoplay: <?= $autoplay ? 'true' : 'false' ?>
};

function scrollReelsTrack(containerId, direction) {
    const track = document.getElementById(`track-${containerId}`);
    if (track) {
        const scrollAmount = track.clientWidth * 0.75 * direction;
        track.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    }
}

function toggleReelCardSound(e, btn) {
    e.stopPropagation();
    const card = btn.closest('.reel-card');
    const video = card ? card.querySelector('video') : null;
    if (video) {
        video.muted = !video.muted;
        const muteIcon = btn.querySelector('.sound-icon-muted');
        const unmuteIcon = btn.querySelector('.sound-icon-unmuted');
        if (muteIcon && unmuteIcon) {
            muteIcon.classList.toggle('hidden', !video.muted);
            unmuteIcon.classList.toggle('hidden', video.muted);
        }
        if (video.paused) {
            video.play().catch(() => {});
        }
    }
}

function openReelModal(containerId, idx) {
    const data = window.reelsComponentsData[containerId];
    if (!data || !data.items || !data.items[idx]) return;

    data.currentIndex = idx;
    const item = data.items[idx];

    const modal = document.getElementById(`reel-modal-${containerId}`);
    const video = document.getElementById(`modal-video-${containerId}`);
    const titleEl = document.getElementById(`modal-title-${containerId}`);
    const ctaEl = document.getElementById(`modal-cta-${containerId}`);
    const ctaTextEl = document.getElementById(`modal-cta-text-${containerId}`);

    if (modal && video) {
        titleEl.innerText = item.title || 'Reel Style';
        ctaEl.href = item.cta_url || '#';
        ctaTextEl.innerText = item.cta_text || 'Shop Now';
        
        if (item.video_url) {
            video.src = item.video_url;
            video.currentTime = 0;
            video.muted = false;
            video.play().catch(() => {
                video.muted = true;
                video.play();
            });
        }

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        video.ontimeupdate = () => {
            if (video.duration) {
                const prog = (video.currentTime / video.duration) * 100;
                const pBar = document.getElementById(`modal-progress-${containerId}`);
                if (pBar) pBar.style.width = prog + '%';
            }
        };
    }
}

function closeReelModal(containerId) {
    const modal = document.getElementById(`reel-modal-${containerId}`);
    const video = document.getElementById(`modal-video-${containerId}`);
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
    if (video) {
        video.pause();
        video.src = '';
    }
}

function navigateModalReel(containerId, dir) {
    const data = window.reelsComponentsData[containerId];
    if (!data || !data.items) return;
    let nextIdx = data.currentIndex + dir;
    if (nextIdx < 0) nextIdx = data.items.length - 1;
    if (nextIdx >= data.items.length) nextIdx = 0;
    openReelModal(containerId, nextIdx);
}

function toggleModalSound(containerId) {
    const video = document.getElementById(`modal-video-${containerId}`);
    if (video) {
        video.muted = !video.muted;
    }
}

// 24-Hour Continuous Non-Stop Looping Video Engine
function init24HrVideoLoop_<?= $uniqueId ?>() {
    const track = document.getElementById('track-<?= $uniqueId ?>');
    if (!track) return;
    const cards = track.querySelectorAll('.reel-card');
    cards.forEach(card => {
        const video = card.querySelector('video.reel-bg-video');
        if (!video) return;

        video.muted = true;
        video.loop = true;
        video.playsInline = true;
        video.setAttribute('muted', '');
        video.setAttribute('loop', '');
        video.setAttribute('playsinline', '');
        video.setAttribute('autoplay', '');

        const startPlayback = () => {
            const playPromise = video.play();
            if (playPromise !== undefined) {
                playPromise.catch(() => {
                    video.muted = true;
                    video.play().catch(() => {});
                });
            }
        };

        // Start playing immediately on page load
        startPlayback();

        // 24-hour continuous loop guarantee: immediately restart if video ends
        video.addEventListener('ended', () => {
            video.currentTime = 0;
            video.play().catch(() => {});
        });

        // If paused by browser battery saver or OS, resume looping automatically
        video.addEventListener('pause', () => {
            const modal = document.getElementById('reel-modal-<?= $uniqueId ?>');
            if (!modal || modal.classList.contains('hidden')) {
                video.play().catch(() => {});
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', init24HrVideoLoop_<?= $uniqueId ?>);
window.addEventListener('load', init24HrVideoLoop_<?= $uniqueId ?>);
document.addEventListener('visibilitychange', () => {
    if (!document.hidden) {
        init24HrVideoLoop_<?= $uniqueId ?>();
    }
});
</script>
