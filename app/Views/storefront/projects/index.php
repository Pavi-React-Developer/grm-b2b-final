<?php
/**
 * Storefront Our Projects Showcase Page (/project & /projects)
 */
?>
<main class="min-h-screen bg-slate-100/70 pb-28">
    <!-- ── Breadcrumb Section (Home > Project) ── -->
    <div class="bg-white border-b border-gray-200/80">
        <div class="max-w-[1536px] mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
            <nav class="grm-breadcrumb-nav hide-scrollbar no-scrollbar scrollbar-none flex flex-wrap items-center gap-1 sm:gap-1.5 text-xs sm:text-sm font-medium" aria-label="Breadcrumb">
                <a href="<?= BASE_URL ?>/" class="inline-flex items-center gap-1.5 text-[#F25996] hover:text-[#d8407d] font-semibold transition-colors shrink-0">
                    <svg class="w-4 h-4 shrink-0 text-[#F25996]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                    </svg>
                    <span>Home</span>
                </a>
                <svg class="w-4 h-4 text-[#F25996] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-[#F25996] font-bold shrink-0">Project</span>
            </nav>
        </div>
    </div>

    <!-- Hero Header Banner -->
    <div class="pt-8 pb-3 text-center">
        <div class="max-w-[1536px] mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-display font-extrabold text-gray-900 tracking-tight">
                Our <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#F25996] to-[#e04481]">Projects</span>
            </h1>
            <p class="text-sm sm:text-base text-gray-600 mt-2 sm:mt-2.5 max-w-2xl mx-auto font-normal leading-relaxed">
                Discover our curated portfolio of bespoke garment manufacturing, high-fashion wholesale collections, and artisanal textile craftsmanship.
            </p>
        </div>
    </div>

    <!-- Projects Container -->
    <div class="max-w-[1536px] mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        <?php if (empty($projects)): ?>
            <!-- Empty State -->
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-12 sm:p-20 text-center max-w-2xl mx-auto">
                <div class="w-20 h-20 mx-auto mb-6 bg-pink-50 text-[#F25996] rounded-3xl flex items-center justify-center">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 font-display">New Showcases Coming Soon</h2>
                <p class="text-gray-500 text-sm mt-3 leading-relaxed">
                    We are currently cataloging our latest completed client projects and seasonal collections. Please check back shortly to explore our portfolio!
                </p>
                <a href="<?= BASE_URL ?>/" class="inline-flex items-center gap-2 mt-8 bg-[#F25996] hover:bg-[#e04481] text-white px-8 py-3 rounded-full font-bold text-xs uppercase tracking-wider shadow-md hover:shadow-lg transition-all">
                    Back to Home
                </a>
            </div>
        <?php else: ?>
            <div class="space-y-16 sm:space-y-24 lg:space-y-28">
                <?php foreach ($projects as $index => $project): 
                    $images = $project['images'] ?? [];
                    $videos = $project['videos'] ?? [];
                    $projId = $project['id'];
                    $isReversed = ($index % 2 !== 0);
                ?>
                <article class="bg-white rounded-[2.5rem] border border-gray-200/90 shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden p-6 sm:p-10 lg:p-12" id="project-<?= $projId ?>">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                        
                        <!-- Left / Media Column: Multi-Image Carousel / Gallery -->
                        <div class="lg:col-span-7 <?= $isReversed ? 'lg:order-2' : 'lg:order-1' ?>">
                            <?php if (!empty($images)): ?>
                                <div class="space-y-4" id="gallery-<?= $projId ?>">
                                    <!-- Main Featured Image (Fully Visible, object-contain, No Crop) -->
                                    <div class="relative aspect-[4/3] sm:aspect-[16/10] rounded-2xl overflow-hidden bg-slate-900/5 border border-gray-100 flex items-center justify-center p-2 shadow-xs cursor-pointer group" onclick="openLightbox(document.getElementById('main-img-<?= $projId ?>').src)">
                                        <!-- Ambient background blur -->
                                        <div class="absolute inset-0 bg-cover bg-center blur-2xl opacity-15 scale-110 pointer-events-none transition-all duration-700" 
                                             id="main-img-bg-<?= $projId ?>" 
                                             style="background-image: url('<?= htmlspecialchars($images[0]) ?>');"></div>

                                        <!-- Full Image (object-contain so 100% of image is shown) -->
                                        <img id="main-img-<?= $projId ?>" 
                                             src="<?= htmlspecialchars($images[0]) ?>" 
                                             alt="<?= htmlspecialchars($project['title']) ?>" 
                                             class="relative z-10 max-w-full max-h-full w-auto h-auto object-contain rounded-xl transition-all duration-300 group-hover:scale-[1.02] drop-shadow-xs">
                                        
                                        <div class="absolute inset-0 z-20 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                                            <span class="bg-white/95 text-gray-800 text-xs font-bold px-4 py-2 rounded-full shadow-lg backdrop-blur-xs flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-[#F25996]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                                Click to enlarge
                                            </span>
                                        </div>

                                        <div class="absolute top-4 left-4 z-20 bg-black/60 backdrop-blur-md text-white text-[11px] font-bold px-3 py-1 rounded-full uppercase tracking-wider flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-pink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span><?= count($images) ?> Photo<?= count($images) > 1 ? 's' : '' ?></span>
                                        </div>
                                    </div>

                                    <!-- Thumbnails Navigation (if multiple images) -->
                                    <?php if (count($images) > 1): ?>
                                        <div class="flex items-center gap-2.5 overflow-x-auto py-2 no-scrollbar">
                                            <?php foreach ($images as $imgIdx => $imgUrl): ?>
                                                <button type="button" 
                                                        onclick="switchMainImage('<?= $projId ?>', '<?= htmlspecialchars($imgUrl) ?>', this)"
                                                        class="thumbnail-btn flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 rounded-xl overflow-hidden border-2 bg-slate-50 transition-all <?= $imgIdx === 0 ? 'border-[#F25996] scale-105 shadow-sm' : 'border-gray-200 opacity-70 hover:opacity-100' ?>">
                                                    <img src="<?= htmlspecialchars($imgUrl) ?>" alt="Thumbnail <?= $imgIdx + 1 ?>" class="w-full h-full object-cover">
                                                </button>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <!-- Fallback if project only has videos or no images -->
                                <div class="aspect-[16/10] rounded-2xl bg-gradient-to-br from-pink-50 to-gray-50 flex items-center justify-center text-gray-400 border border-gray-100">
                                    <div class="text-center p-6">
                                        <svg class="w-12 h-12 mx-auto mb-2 text-[#F25996] opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-xs font-semibold">Featured Project Showcase</span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Right / Text Column: Details & Embedded Video in Old Style -->
                        <div class="lg:col-span-5 <?= $isReversed ? 'lg:order-1' : 'lg:order-2' ?> flex flex-col justify-between space-y-5">
                            <div>
                                <!-- Subtitle / Category Badge (Multi-line safe, clean wrap) -->
                                <?php if (!empty($project['subtitle'])): ?>
                                    <div class="mb-3">
                                        <span class="inline-block px-3.5 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-pink-50 text-[#F25996] leading-relaxed max-w-full break-words">
                                            <?= htmlspecialchars($project['subtitle']) ?>
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <!-- Title -->
                                <h2 class="text-2xl sm:text-3xl font-display font-bold text-gray-900 tracking-tight leading-snug">
                                    <?= htmlspecialchars($project['title']) ?>
                                </h2>

                                <!-- Description -->
                                <?php if (!empty($project['description'])): ?>
                                    <div class="mt-3 text-sm sm:text-base text-gray-600 leading-relaxed font-normal space-y-2 prose prose-sm max-w-none">
                                        <?= nl2br(htmlspecialchars($project['description'])) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Video Section: Kept compact inside the right column (Old Style) -->
                            <?php if (!empty($videos)): 
                                $firstVideo = $videos[0];
                                $firstVidUrl = is_array($firstVideo) ? ($firstVideo['url'] ?? '') : $firstVideo;
                                $firstYoutubeId = null;
                                if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $firstVidUrl, $match)) {
                                    $firstYoutubeId = $match[1];
                                }
                            ?>
                                <div id="video-gallery-<?= $projId ?>" class="pt-5 border-t border-gray-100">
                                    <div class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2.5 flex items-center justify-between">
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-[#F25996]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span>Project Video</span>
                                        </div>
                                        <span id="active-video-index-<?= $projId ?>" class="text-[11px] font-semibold text-[#F25996] bg-pink-50 px-2.5 py-0.5 rounded-full">
                                            Video 1 of <?= count($videos) ?>
                                        </span>
                                    </div>

                                    <!-- Main Video Player -->
                                    <div class="relative rounded-2xl overflow-hidden bg-black aspect-video shadow-sm">
                                        <div id="main-video-wrapper-<?= $projId ?>" class="w-full h-full transition-opacity duration-200">
                                            <?php if ($firstYoutubeId): ?>
                                                <iframe src="https://www.youtube.com/embed/<?= htmlspecialchars($firstYoutubeId) ?>?rel=0" 
                                                        id="main-video-frame-<?= $projId ?>"
                                                        class="w-full h-full border-0" 
                                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                        allowfullscreen></iframe>
                                            <?php else: ?>
                                                <video src="<?= htmlspecialchars($firstVidUrl) ?>" 
                                                       id="main-video-player-<?= $projId ?>"
                                                       controls 
                                                       preload="metadata" 
                                                       class="w-full h-full object-contain bg-black">
                                                    Your browser does not support the video tag.
                                                </video>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Video Thumbnails Navigation Below Video (if multiple videos) -->
                                    <?php if (count($videos) > 1): ?>
                                        <div class="flex items-center gap-2 overflow-x-auto py-2 mt-1 no-scrollbar">
                                            <?php foreach ($videos as $vidIdx => $vItem): 
                                                $vUrl = is_array($vItem) ? ($vItem['url'] ?? '') : $vItem;
                                                $vYtId = null;
                                                if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $vUrl, $vMatch)) {
                                                    $vYtId = $vMatch[1];
                                                }
                                                $isFirstVid = ($vidIdx === 0);
                                            ?>
                                                <button type="button" 
                                                        onclick="switchMainVideo('<?= $projId ?>', '<?= htmlspecialchars($vUrl, ENT_QUOTES) ?>', '<?= $vYtId ? 'youtube' : 'html5' ?>', '<?= $vYtId ?? '' ?>', <?= $vidIdx + 1 ?>, <?= count($videos) ?>, this)"
                                                        class="video-thumbnail-btn relative flex-shrink-0 w-20 h-14 rounded-xl overflow-hidden border-2 bg-black transition-all group <?= $isFirstVid ? 'border-[#F25996] scale-105 shadow-sm ring-2 ring-pink-100' : 'border-gray-200 opacity-70 hover:opacity-100' ?>"
                                                        title="Play Video <?= $vidIdx + 1 ?>">
                                                    <?php if ($vYtId): ?>
                                                        <img src="https://img.youtube.com/vi/<?= htmlspecialchars($vYtId) ?>/hqdefault.jpg" alt="Video <?= $vidIdx + 1 ?>" class="w-full h-full object-cover">
                                                    <?php else: ?>
                                                        <video src="<?= htmlspecialchars($vUrl) ?>#t=0.5" preload="metadata" class="w-full h-full object-cover pointer-events-none"></video>
                                                    <?php endif; ?>
                                                    
                                                    <div class="absolute inset-0 bg-black/40 group-hover:bg-black/20 flex items-center justify-center transition-colors">
                                                        <div class="w-5 h-5 rounded-full bg-white/90 text-[#F25996] flex items-center justify-center shadow-xs">
                                                            <svg class="w-2.5 h-2.5 translate-x-0.5 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="absolute bottom-1 right-1 bg-black/75 text-white text-[9px] font-bold px-1.5 py-0.2 rounded">
                                                        #<?= $vidIdx + 1 ?>
                                                    </div>
                                                </button>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- Lightbox Modal for Fullscreen Image View -->
<div id="imageLightbox" class="fixed inset-0 z-50 bg-black/90 backdrop-blur-md hidden items-center justify-center p-4" onclick="closeLightbox(event)">
    <button type="button" onclick="closeLightboxNow()" class="absolute top-6 right-6 text-white/80 hover:text-white p-2 rounded-full bg-white/10 hover:bg-white/20 transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>
    <img id="lightboxImg" src="" alt="Enlarged project preview" class="max-w-full max-h-[90vh] rounded-2xl shadow-2xl object-contain" onclick="event.stopPropagation()">
</div>

<script>
function switchMainImage(projectId, newUrl, clickedBtn) {
    const mainImg = document.getElementById('main-img-' + projectId);
    const mainBg = document.getElementById('main-img-bg-' + projectId);
    if (mainImg) {
        mainImg.style.opacity = '0.4';
        setTimeout(() => {
            mainImg.src = newUrl;
            mainImg.style.opacity = '1';
            if (mainBg) {
                mainBg.style.backgroundImage = `url('${newUrl}')`;
            }
        }, 150);
    }
    
    // Update thumbnail border styling
    const parent = clickedBtn.closest('#gallery-' + projectId);
    if (parent) {
        parent.querySelectorAll('.thumbnail-btn').forEach(b => {
            b.classList.remove('border-[#F25996]', 'scale-105', 'shadow-sm');
            b.classList.add('border-gray-200', 'opacity-70');
        });
        clickedBtn.classList.remove('border-gray-200', 'opacity-70');
        clickedBtn.classList.add('border-[#F25996]', 'scale-105', 'shadow-sm');
    }
}

function switchMainVideo(projectId, vidUrl, vidType, youtubeId, vidIndex, totalVideos, clickedBtn) {
    const wrapper = document.getElementById('main-video-wrapper-' + projectId);
    const counter = document.getElementById('active-video-index-' + projectId);
    if (counter) {
        counter.textContent = 'Video ' + vidIndex + ' of ' + totalVideos;
    }
    
    if (wrapper) {
        wrapper.style.opacity = '0.3';
        setTimeout(() => {
            if (vidType === 'youtube' && youtubeId) {
                wrapper.innerHTML = `
                    <iframe src="https://www.youtube.com/embed/${youtubeId}?autoplay=1&rel=0" 
                            id="main-video-frame-${projectId}"
                            class="w-full h-full border-0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen></iframe>`;
            } else {
                wrapper.innerHTML = `
                    <video src="${vidUrl}" 
                           id="main-video-player-${projectId}"
                           controls 
                           autoplay 
                           class="w-full h-full object-contain bg-black">
                        Your browser does not support the video tag.
                    </video>`;
            }
            wrapper.style.opacity = '1';
        }, 150);
    }
    
    // Update video thumbnail border styling
    const parent = clickedBtn.closest('#video-gallery-' + projectId);
    if (parent) {
        parent.querySelectorAll('.video-thumbnail-btn').forEach(b => {
            b.classList.remove('border-[#F25996]', 'scale-105', 'shadow-sm', 'ring-2', 'ring-pink-100');
            b.classList.add('border-gray-200', 'opacity-70');
        });
        clickedBtn.classList.remove('border-gray-200', 'opacity-70');
        clickedBtn.classList.add('border-[#F25996]', 'scale-105', 'shadow-sm', 'ring-2', 'ring-pink-100');
    }
}

function openLightbox(url) {
    const lightbox = document.getElementById('imageLightbox');
    const lightboxImg = document.getElementById('lightboxImg');
    if (lightbox && lightboxImg) {
        lightboxImg.src = url;
        lightbox.classList.remove('hidden');
        lightbox.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

function closeLightboxNow() {
    const lightbox = document.getElementById('imageLightbox');
    if (lightbox) {
        lightbox.classList.remove('flex');
        lightbox.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

function closeLightbox(e) {
    if (e.target.id === 'imageLightbox') {
        closeLightboxNow();
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeLightboxNow();
    }
});
</script>
