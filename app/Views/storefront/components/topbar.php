<?php
$contentData = $contentData ?? [];
$settings = $contentData['global_settings'] ?? [];
$announcements = $contentData['announcements'] ?? [];

// Filter active announcements
$activeAnnouncements = array_filter($announcements, function($item) {
    return !isset($item['active']) || (bool)$item['active'];
});
$activeAnnouncements = array_values($activeAnnouncements);

if (empty($activeAnnouncements)) {
    return;
}

$id = uniqid('topbar_');
$bgColor = !empty($settings['bg_color']) ? $settings['bg_color'] : '#3f4c38';
$textColor = !empty($settings['text_color']) ? $settings['text_color'] : '#ffffff';
$mode = $settings['mode'] ?? 'slider'; // slider, sticky, fade
$speed = max(1, (int)($settings['speed'] ?? 5));
$height = max(28, (int)($settings['height'] ?? 38));
$fontSize = max(10, (int)($settings['font_size'] ?? 13));
$showSocial = isset($settings['show_social_icon']) ? (bool)$settings['show_social_icon'] : true;
$socialPlatform = $settings['social_platform'] ?? 'instagram';
$socialUrl = !empty($settings['social_url']) ? $settings['social_url'] : '#';

// Social SVGs
$socialIcons = [
    'instagram' => '<svg class="w-4 h-4 transition-transform hover:scale-110" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>',
    'facebook' => '<svg class="w-4 h-4 transition-transform hover:scale-110" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
    'whatsapp' => '<svg class="w-4 h-4 transition-transform hover:scale-110" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>',
    'twitter' => '<svg class="w-4 h-4 transition-transform hover:scale-110" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
    'phone' => '<svg class="w-4 h-4 transition-transform hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>'
];

// Helper for prefix icon
if (!function_exists('getAnnouncementIcon')) {
    function getAnnouncementIcon($iconName) {
        switch ($iconName) {
            case 'truck':
                return '<svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 inline-block opacity-90 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            case 'tag':
                return '<svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 inline-block opacity-90 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>';
            case 'fire':
                return '<svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 inline-block opacity-90 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>';
            case 'star':
                return '<svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 inline-block opacity-90 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>';
            default:
                return '';
        }
    }
}

// Single announcement pass duration across the screen (ultra-slow, graceful drift ~50s-65s)
$passDuration = max(50, (int)$speed * 15);
?>

<style>
/* Total Scrollbar suppression scoped only to topbar wrapper */
#grm-topbar-wrapper-<?= $id ?>,
#grm-topbar-wrapper-<?= $id ?> * {
    scrollbar-width: none !important;
    -ms-overflow-style: none !important;
}
#grm-topbar-wrapper-<?= $id ?> *::-webkit-scrollbar {
    display: none !important;
    width: 0 !important;
    height: 0 !important;
}

@keyframes grmSingleMarqueePass_<?= $id ?> {
    0% {
        left: 100%;
        transform: translate3d(0, -50%, 0);
    }
    100% {
        left: 0;
        transform: translate3d(-100%, -50%, 0);
    }
}

.grm-single-marquee-item-<?= $id ?> {
    position: absolute;
    white-space: nowrap;
    top: 50%;
    left: 100%;
    transform: translate3d(0, -50%, 0);
    will-change: left, transform;
    font-size: <?= (int)$fontSize ?>px;
    font-weight: 500;
    display: none;
}

.grm-single-marquee-item-<?= $id ?>.running {
    display: inline-flex !important;
    align-items: center;
    animation: grmSingleMarqueePass_<?= $id ?> <?= (int)$passDuration ?>s linear 1 forwards;
}

.grm-single-marquee-item-<?= $id ?>.hidden {
    display: none !important;
}

.grm-single-marquee-item-<?= $id ?>:hover {
    animation-play-state: paused !important;
}

.topbar-fade-item-<?= $id ?> {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1), transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    opacity: 0;
    transform: translateY(4px);
    pointer-events: none;
}
.topbar-fade-item-<?= $id ?>.active {
    opacity: 1;
    transform: translateY(0);
    pointer-events: auto;
}

@keyframes grmTopBarPulse {
    0%, 100% { opacity: 0.85; transform: scale(0.995); }
    50% { opacity: 1; transform: scale(1); }
}
.grm-topbar-pulse {
    animation: grmTopBarPulse 3s ease-in-out infinite;
}
</style>

<div id="grm-topbar-wrapper-<?= $id ?>" class="w-full relative z-30 select-none transition-colors duration-300 overflow-hidden" 
     style="background-color: <?= htmlspecialchars($bgColor) ?>; color: <?= htmlspecialchars($textColor) ?>; height: <?= (int)$height ?>px; min-height: <?= (int)$height ?>px; overflow: hidden !important;">
    
    <div class="max-w-[95rem] mx-auto px-3 sm:px-6 lg:px-8 flex items-center justify-between h-full relative overflow-hidden" style="height: <?= (int)$height ?>px;">
        
        <!-- Left Spacer for symmetry on desktop -->
        <div class="hidden sm:block w-8 flex-shrink-0"></div>

        <!-- Center Announcements Area -->
        <div class="flex-1 w-full relative flex items-center justify-center overflow-hidden h-full">
            
            <?php if ($mode === 'slider'): ?>
                <!-- Single Announcement Full-Width Pass Marquee (One Announcement at a Time) -->
                <div class="w-full overflow-hidden relative h-full flex items-center" id="singleMarqueeContainer_<?= $id ?>">
                    <?php foreach ($activeAnnouncements as $idx => $item): ?>
                        <div class="grm-single-marquee-item-<?= $id ?> <?= $idx === 0 ? 'running' : 'hidden' ?>" data-index="<?= $idx ?>">
                            <?php if (!empty($item['cta_url'])): ?>
                                <a href="<?= htmlspecialchars($item['cta_url']) ?>" class="hover:underline inline-flex items-center gap-2">
                                    <?= getAnnouncementIcon($item['icon'] ?? 'none') ?>
                                    <span><?= htmlspecialchars($item['text']) ?></span>
                                </a>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-2">
                                    <?= getAnnouncementIcon($item['icon'] ?? 'none') ?>
                                    <span><?= htmlspecialchars($item['text']) ?></span>
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <script>
                (function() {
                    var container = document.getElementById('singleMarqueeContainer_<?= $id ?>');
                    if (!container) return;
                    var items = container.querySelectorAll('.grm-single-marquee-item-<?= $id ?>');
                    if (!items.length) return;
                    
                    var currentIndex = 0;
                    var totalItems = items.length;

                    function runItem(index) {
                        for (var i = 0; i < items.length; i++) {
                            items[i].classList.remove('running');
                            items[i].classList.add('hidden');
                        }
                        
                        var currentEl = items[index];
                        if (!currentEl) return;
                        currentEl.classList.remove('hidden');
                        // Force DOM reflow to cleanly restart the CSS animation
                        void currentEl.offsetWidth;
                        currentEl.classList.add('running');
                    }

                    container.addEventListener('animationend', function(e) {
                        if (e.target.classList.contains('grm-single-marquee-item-<?= $id ?>')) {
                            currentIndex = (currentIndex + 1) % totalItems;
                            runItem(currentIndex);
                        }
                    });

                    runItem(0);
                })();
                </script>

            <?php elseif ($mode === 'fade'): ?>
                <!-- Smooth Fade Transition -->
                <div class="w-full overflow-hidden flex items-center justify-center relative h-full" id="fadeContainer_<?= $id ?>">
                    <?php if (count($activeAnnouncements) === 1): ?>
                        <div class="grm-topbar-pulse flex items-center justify-center font-medium tracking-wide text-center px-4" style="font-size: <?= (int)$fontSize ?>px;">
                            <?php $item = $activeAnnouncements[0]; ?>
                            <?php if (!empty($item['cta_url'])): ?>
                                <a href="<?= htmlspecialchars($item['cta_url']) ?>" class="hover:underline inline-flex items-center gap-2">
                                    <?= getAnnouncementIcon($item['icon'] ?? 'none') ?>
                                    <span><?= htmlspecialchars($item['text']) ?></span>
                                </a>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-2">
                                    <?= getAnnouncementIcon($item['icon'] ?? 'none') ?>
                                    <span><?= htmlspecialchars($item['text']) ?></span>
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="relative w-full flex items-center justify-center overflow-hidden" style="height: <?= (int)$height ?>px;">
                            <?php foreach ($activeAnnouncements as $idx => $item): ?>
                                <div class="topbar-fade-item-<?= $id ?> <?= $idx === 0 ? 'active' : '' ?>" data-fade="<?= $idx ?>">
                                    <?php if (!empty($item['cta_url'])): ?>
                                        <a href="<?= htmlspecialchars($item['cta_url']) ?>" class="hover:underline inline-flex items-center gap-2 font-medium tracking-wide text-center px-4" style="font-size: <?= (int)$fontSize ?>px;">
                                            <?= getAnnouncementIcon($item['icon'] ?? 'none') ?>
                                            <span><?= htmlspecialchars($item['text']) ?></span>
                                        </a>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-2 font-medium tracking-wide text-center px-4" style="font-size: <?= (int)$fontSize ?>px;">
                                            <?= getAnnouncementIcon($item['icon'] ?? 'none') ?>
                                            <span><?= htmlspecialchars($item['text']) ?></span>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <script>
                        (function() {
                            var container = document.getElementById('fadeContainer_<?= $id ?>');
                            if (!container) return;
                            var fadeItems = container.querySelectorAll('.topbar-fade-item-<?= $id ?>');
                            var totalFade = fadeItems.length;
                            if (totalFade <= 1) return;
                            var currentFade = 0;
                            var intervalTime = <?= max(2, (int)$speed) * 1000 ?>;
                            var fadeTimer = null;

                            function startFadeTimer() {
                                if (fadeTimer) clearInterval(fadeTimer);
                                fadeTimer = setInterval(function() {
                                    fadeItems[currentFade].classList.remove('active');
                                    currentFade = (currentFade + 1) % totalFade;
                                    fadeItems[currentFade].classList.add('active');
                                }, intervalTime);
                            }

                            container.addEventListener('mouseenter', function() {
                                if (fadeTimer) clearInterval(fadeTimer);
                            });
                            container.addEventListener('mouseleave', function() {
                                startFadeTimer();
                            });

                            startFadeTimer();
                        })();
                        </script>
                    <?php endif; ?>
                </div>

            <?php else: ?>
                <!-- Sticky / Static Single or Main Announcement Text -->
                <div class="flex items-center justify-center w-full h-full px-4 overflow-hidden">
                    <?php $singleItem = $activeAnnouncements[0]; ?>
                    <?php if (!empty($singleItem['cta_url'])): ?>
                        <a href="<?= htmlspecialchars($singleItem['cta_url']) ?>" class="hover:underline inline-flex items-center justify-center gap-2 text-center font-medium tracking-wide truncate max-w-full" style="font-size: <?= (int)$fontSize ?>px;">
                            <?= getAnnouncementIcon($singleItem['icon'] ?? 'none') ?>
                            <span class="truncate"><?= htmlspecialchars($singleItem['text']) ?></span>
                        </a>
                    <?php else: ?>
                        <span class="inline-flex items-center justify-center gap-2 text-center font-medium tracking-wide truncate max-w-full" style="font-size: <?= (int)$fontSize ?>px;">
                            <?= getAnnouncementIcon($singleItem['icon'] ?? 'none') ?>
                            <span class="truncate"><?= htmlspecialchars($singleItem['text']) ?></span>
                        </span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right End Social / Contact Icon (Desktop/Tablet only) -->
        <?php if ($showSocial): ?>
            <div class="hidden sm:flex flex-shrink-0 items-center ml-4 z-10">
                <a href="<?= htmlspecialchars($socialUrl) ?>" target="_blank" rel="noopener noreferrer" class="opacity-90 hover:opacity-100 transition-opacity p-1" title="<?= ucfirst($socialPlatform) ?>">
                    <?= $socialIcons[$socialPlatform] ?? $socialIcons['phone'] ?>
                </a>
            </div>
        <?php else: ?>
            <div class="hidden sm:block w-8 flex-shrink-0"></div>
        <?php endif; ?>

    </div>
</div>
