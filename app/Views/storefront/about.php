<?php if (!empty($aboutUs)):
    $c = $aboutUs['content_data'];
    $theme = $c['theme'];
?>
<style>
    .about-cta-btn {
        border-top-left-radius: 2rem;
        border-bottom-right-radius: 2rem;
        padding: 0.75rem 1.25rem 0.75rem 1.5rem;
    }
    .about-cta-text { font-size: 0.75rem; margin-right: 0.75rem; }
    .about-cta-icon { width: 1.5rem; height: 1.5rem; }
    .about-cta-svg { width: 0.875rem; height: 0.875rem; }
    @media (min-width: 768px) {
        .about-hero {
            min-height: 34rem;
        }
        .about-hero-media {
            height: 100%;
            min-height: 34rem;
        }
        .about-cta-btn {
            border-top-left-radius: 3rem;
            border-bottom-right-radius: 3rem;
            padding: 1rem 1.75rem 1rem 2rem;
        }
        .about-cta-text { font-size: 1rem; margin-right: 1rem; }
        .about-cta-icon { width: 2rem; height: 2rem; }
        .about-cta-svg { width: 1rem; height: 1rem; }
    }
    @media (max-width: 767px) {
        .about-hero-media {
            height: 24rem;
        }
        .about-cta-btn {
            margin-top: 0.75rem;
            margin-bottom: 1rem;
        }
    }
    .about-features {
        background-color: <?= htmlspecialchars($theme['feature_bg_color'] ?? '#ffffff') ?> !important;
    }
    .about-features .about-feature-card {
        border-color: #e5e7eb !important;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.08);
    }
    .custom-features-scrollbar::-webkit-scrollbar {
        height: 6px;
    }
    .custom-features-scrollbar::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.05);
        border-radius: 9999px;
    }
    .custom-features-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.2);
        border-radius: 9999px;
    }
    .custom-features-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 0, 0, 0.35);
    }
</style>
<div class="cms-section pb-20" data-cms-id="<?= htmlspecialchars($aboutUs['id'] ?? '') ?>" style="display: <?= ($aboutUs['is_active'] ?? 1) ? 'block' : 'none' ?>; background-color: <?= htmlspecialchars($theme['bg_color'] ?? '#ffffff') ?>;">
    
    <!-- Hero Section -->
    <div class="about-hero relative overflow-hidden mb-12 lg:mb-16">
        <div class="max-w-[90rem] mx-auto flex flex-col md:flex-row items-center">
            <div class="w-full md:w-1/2 px-4 sm:px-6 lg:px-8 py-10 md:py-24 space-y-6 relative z-20 order-2 md:order-1">
                <?php if(!empty($c['hero']['subtitle'])): ?>
                <h4 class="font-bold tracking-wider text-sm uppercase" style="color: <?= htmlspecialchars($theme['hero_text_color'] ?? '#111827') ?>; opacity: 0.8;">
                    <?= htmlspecialchars($c['hero']['subtitle']) ?>
                </h4>
                <?php endif; ?>
                
                <?php if(!empty($c['hero']['title'])): ?>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-display font-extrabold leading-tight" style="color: <?= htmlspecialchars($theme['hero_text_color'] ?? '#111827') ?>;">
                    <?= htmlspecialchars($c['hero']['title']) ?>
                </h1>
                <?php endif; ?>
                
                <?php if(!empty($c['hero']['description'])): ?>
                <p class="text-lg md:text-xl max-w-lg leading-relaxed" style="color: <?= htmlspecialchars($theme['hero_desc_color'] ?? '#4b5563') ?>;">
                    <?= nl2br(htmlspecialchars($c['hero']['description'])) ?>
                </p>
                <?php endif; ?>
                
                <?php if(!empty($c['hero']['cta_text']) && !empty($c['hero']['cta_url'])): ?>
                <div class="pt-4">
                    <a href="<?= htmlspecialchars($c['hero']['cta_url']) ?>" class="about-cta-btn inline-flex items-center border border-transparent font-bold tracking-wide shadow-xl transition-opacity hover:opacity-90 focus:outline-none" style="background-color: <?= htmlspecialchars($theme['btn_bg_color'] ?? '#059669') ?>; color: <?= htmlspecialchars($theme['btn_text_color'] ?? '#ffffff') ?>;">
                        <span class="about-cta-text"><?= htmlspecialchars($c['hero']['cta_text']) ?></span>
                        <span class="about-cta-icon flex-shrink-0 bg-white rounded-full flex items-center justify-center shadow-sm" style="color:#111827;">
                            <svg class="about-cta-svg" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"></path></svg>
                        </span>
                    </a>
                </div>
                <?php endif; ?>
            </div>
            
            <?php if(!empty($c['hero']['image'])): ?>
            <div class="about-hero-media w-full overflow-hidden md:mx-0 md:rounded-none md:overflow-visible md:absolute md:top-0 md:right-0 md:w-1/2 md:h-full h-96 relative z-10 order-1 md:order-2">
                <div class="absolute inset-y-0 left-0 transform -skew-x-12 -ml-16 w-32 z-10 hidden md:block" style="background-color: <?= htmlspecialchars($theme['bg_color'] ?? '#ffffff') ?>;"></div>
                <img src="<?= htmlspecialchars($c['hero']['image']) ?>" alt="<?= htmlspecialchars($c['hero']['title'] ?? 'About Us') ?>" class="absolute inset-0 w-full h-full object-cover md:rounded-none">
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Features Section -->
    <?php if(!empty($c['features'])): 
        $allFeatures = $c['features'];
        $firstFeature = $allFeatures[0] ?? null;
        $scrollFeatures = array_slice($allFeatures, 1);
    ?>
    <div class="about-features py-16 mb-12 mx-4 md:mx-8 lg:mx-12 rounded-3xl" style="background-color: <?= htmlspecialchars($theme['feature_bg_color'] ?? '#ffffff') ?>;">
        <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
            <?php 
            $featuresHeading = !empty($c['features_heading']) ? $c['features_heading'] : (!empty($c['features_title']) ? $c['features_title'] : '');
            if (!empty($featuresHeading)): 
            ?>
            <div class="text-center mb-12">
                <h2 class="text-3xl font-display font-extrabold" style="color: <?= htmlspecialchars($theme['feature_text_color'] ?? '#111827') ?>;">
                    <?= htmlspecialchars($featuresHeading) ?>
                </h2>
            </div>
            <?php endif; ?>

            <?php if(empty($scrollFeatures)): ?>
                <!-- Single Column View -->
                <div class="max-w-xl mx-auto">
                    <div class="about-feature-card rounded-2xl p-8 text-center shadow-sm border border-white hover:shadow-md transition-shadow" style="background-color: <?= htmlspecialchars($firstFeature['bg_color'] ?? '#ffffff') ?>;">
                        <?php if(!empty($firstFeature['icon'])): ?>
                        <div class="w-10 h-10 md:w-8 md:h-8 mx-auto mb-4" style="color: <?= htmlspecialchars($theme['feature_icon_color'] ?? '#059669') ?>;">
                            <?= str_replace('<svg ', '<svg fill="currentColor" ', $firstFeature['icon']) ?>
                        </div>
                        <?php endif; ?>
                        <h4 class="font-bold text-xl md:text-lg mb-2 uppercase" style="color: <?= htmlspecialchars($firstFeature['text_color'] ?? $theme['feature_text_color'] ?? '#111827') ?>;">
                            <?= htmlspecialchars($firstFeature['title'] ?? '') ?>
                        </h4>
                        <p class="text-base md:text-sm opacity-90 md:opacity-80 leading-relaxed" style="color: <?= htmlspecialchars($firstFeature['text_color'] ?? $theme['feature_text_color'] ?? '#111827') ?>;">
                            <?= nl2br(htmlspecialchars($firstFeature['description'] ?? '')) ?>
                        </p>
                    </div>
                </div>
            <?php else: ?>
                <!-- Split Layout: Column 1 Container + Column 2, 3+ Horizontal Scroller -->
                <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 items-stretch">
                    
                    <!-- Column 1 Container (Dedicated UI) -->
                    <div class="w-full lg:w-[380px] xl:w-[420px] flex-shrink-0 flex flex-col">
                        <div class="about-feature-card h-full rounded-2xl p-8 text-center shadow-sm border border-white hover:shadow-md transition-shadow flex flex-col justify-start" style="background-color: <?= htmlspecialchars($firstFeature['bg_color'] ?? '#ffffff') ?>;">
                            <?php if(!empty($firstFeature['icon'])): ?>
                            <div class="w-10 h-10 md:w-8 md:h-8 mx-auto mb-4" style="color: <?= htmlspecialchars($theme['feature_icon_color'] ?? '#059669') ?>;">
                                <?= str_replace('<svg ', '<svg fill="currentColor" ', $firstFeature['icon']) ?>
                            </div>
                            <?php endif; ?>
                            <h4 class="font-bold text-xl md:text-lg mb-3 uppercase" style="color: <?= htmlspecialchars($firstFeature['text_color'] ?? $theme['feature_text_color'] ?? '#111827') ?>;">
                                <?= htmlspecialchars($firstFeature['title'] ?? '') ?>
                            </h4>
                            <p class="text-base md:text-sm opacity-90 md:opacity-80 leading-relaxed" style="color: <?= htmlspecialchars($firstFeature['text_color'] ?? $theme['feature_text_color'] ?? '#111827') ?>;">
                                <?= nl2br(htmlspecialchars($firstFeature['description'] ?? '')) ?>
                            </p>
                        </div>
                    </div>

                    <!-- Column 2, Column 3+ Horizontal Scroller Container -->
                    <div class="flex-1 min-w-0 relative flex flex-col justify-between">
                        <!-- Scroll Track -->
                        <div id="featureScrollTrack" class="overflow-x-auto flex gap-6 pb-4 pt-1 snap-x scroll-smooth custom-features-scrollbar" style="scrollbar-width: thin; scrollbar-color: rgba(0,0,0,0.2) transparent;">
                            <?php foreach($scrollFeatures as $f): ?>
                            <div class="about-feature-card snap-start flex-shrink-0 w-[85vw] sm:w-[320px] md:w-[350px] lg:w-[360px] rounded-2xl p-8 text-center shadow-sm border border-white hover:shadow-md transition-shadow flex flex-col justify-start" style="background-color: <?= htmlspecialchars($f['bg_color'] ?? '#ffffff') ?>;">
                                <?php if(!empty($f['icon'])): ?>
                                <div class="w-10 h-10 md:w-8 md:h-8 mx-auto mb-4" style="color: <?= htmlspecialchars($theme['feature_icon_color'] ?? '#059669') ?>;">
                                    <?= str_replace('<svg ', '<svg fill="currentColor" ', $f['icon']) ?>
                                </div>
                                <?php endif; ?>
                                <h4 class="font-bold text-xl md:text-lg mb-3 uppercase" style="color: <?= htmlspecialchars($f['text_color'] ?? $theme['feature_text_color'] ?? '#111827') ?>;">
                                    <?= htmlspecialchars($f['title'] ?? '') ?>
                                </h4>
                                <p class="text-base md:text-sm opacity-90 md:opacity-80 leading-relaxed" style="color: <?= htmlspecialchars($f['text_color'] ?? $theme['feature_text_color'] ?? '#111827') ?>;">
                                    <?= nl2br(htmlspecialchars($f['description'] ?? '')) ?>
                                </p>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Left & Right Arrow Navigation Controls -->
                        <div class="flex justify-end items-center gap-2 mt-2 pr-1">
                            <span class="text-xs text-gray-500 font-medium mr-2 hidden sm:inline-block">Scroll &rarr;</span>
                            <button type="button" onclick="document.getElementById('featureScrollTrack').scrollBy({left: -360, behavior: 'smooth'})" class="w-8 h-8 rounded-full bg-white/90 hover:bg-white shadow-sm flex items-center justify-center text-gray-700 hover:text-black border border-gray-200 transition-all cursor-pointer" aria-label="Previous">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <button type="button" onclick="document.getElementById('featureScrollTrack').scrollBy({left: 360, behavior: 'smooth'})" class="w-8 h-8 rounded-full bg-white/90 hover:bg-white shadow-sm flex items-center justify-center text-gray-700 hover:text-black border border-gray-200 transition-all cursor-pointer" aria-label="Next">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>

                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Why Choose Section -->
    <?php if(!empty($c['why_choose'])): ?>
    <div class="py-16 mb-12 mx-4 md:mx-8 lg:mx-12 rounded-3xl" style="background-color: <?= htmlspecialchars($theme['why_choose_bg_color'] ?? '#ffffff') ?>;">
        <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-display font-extrabold" style="color: <?= htmlspecialchars($theme['why_choose_text_color'] ?? '#111827') ?>;">
                    <?= htmlspecialchars(!empty($c['why_choose_heading']) ? $c['why_choose_heading'] : (!empty($c['why_choose_title']) ? $c['why_choose_title'] : 'Why Choose Us')) ?>
                </h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach($c['why_choose'] as $w): ?>
                <div class="rounded-2xl p-8 text-center shadow-sm border border-white hover:shadow-md transition-shadow" style="background-color: <?= htmlspecialchars($w['bg_color'] ?? '#ffffff') ?>;">
                    <?php if(!empty($w['icon'])): ?>
                    <div class="w-12 h-12 mx-auto mb-4" style="color: <?= htmlspecialchars($theme['why_choose_icon_color'] ?? '#059669') ?>;">
                        <?= str_replace('<svg ', '<svg fill="currentColor" ', $w['icon']) ?>
                    </div>
                    <?php endif; ?>
                    <h4 class="font-bold text-lg mb-2 uppercase" style="color: <?= htmlspecialchars($w['text_color'] ?? $theme['why_choose_text_color'] ?? '#111827') ?>;">
                        <?= htmlspecialchars($w['title'] ?? '') ?>
                    </h4>
                    <p class="text-sm opacity-80" style="color: <?= htmlspecialchars($w['text_color'] ?? $theme['why_choose_text_color'] ?? '#111827') ?>;">
                        <?= nl2br(htmlspecialchars($w['description'] ?? '')) ?>
                    </p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- FAQ Section -->
    <?php if(!empty($c['faqs'])): ?>
    <div class="py-16 mb-12 mx-4 md:mx-8 lg:mx-12 rounded-3xl" style="background-color: <?= htmlspecialchars($theme['faq_bg_color'] ?? '#ffffff') ?>;">
        <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-display font-extrabold" style="color: <?= htmlspecialchars($theme['faq_text_color'] ?? '#111827') ?>;"><?= htmlspecialchars(!empty($c['faq_heading']) ? $c['faq_heading'] : (!empty($c['faq_title']) ? $c['faq_title'] : 'Frequently Asked Questions')) ?></h2>
            </div>
            <div class="max-w-3xl mx-auto space-y-6">
                <?php foreach($c['faqs'] as $faq): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <button class="w-full p-6 text-left flex justify-between items-center focus:outline-none" onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('svg').classList.toggle('rotate-180');">
                        <h3 class="font-bold text-lg pr-4 uppercase" style="color: <?= htmlspecialchars($theme['faq_text_color'] ?? '#111827') ?>;">
                            <?= htmlspecialchars($faq['question'] ?? '') ?>
                        </h3>
                        <svg class="w-5 h-5 text-gray-400 transition-transform duration-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div class="hidden px-6 pb-6 border-t border-gray-50 pt-4">
                        <p class="text-gray-600">
                            <?= nl2br(htmlspecialchars($faq['answer'] ?? '')) ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>
<?php else: ?>
<div class="bg-white pb-20">
    <!-- Hero Section -->
    <div class="bg-gray-50 relative overflow-hidden">
        <div class="max-w-[90rem] mx-auto flex flex-col md:flex-row items-center">
            <div class="w-full md:w-1/2 px-4 sm:px-6 lg:px-8 py-16 md:py-24 space-y-6 relative z-20">
                <h4 class="text-brand-600 font-bold tracking-wider text-sm uppercase">About GRM</h4>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-display font-extrabold text-gray-900 leading-tight">Your Trusted B2B Wholesale Partner</h1>
                <p class="text-gray-600 text-lg md:text-xl max-w-lg leading-relaxed">
                    We help businesses grow by providing quality products, competitive wholesale pricing and reliable supply, all in one place.
                </p>
                <div class="pt-4">
                    <a href="<?= BASE_URL ?>/catalog" class="about-cta-btn inline-flex items-center border border-transparent font-bold tracking-wide shadow-xl text-white bg-brand-600 hover:bg-brand-700 transition-opacity focus:outline-none">
                        <span class="about-cta-text">Explore Products</span>
                        <span class="about-cta-icon flex-shrink-0 bg-white rounded-full flex items-center justify-center shadow-sm" style="color:#111827;">
                            <svg class="about-cta-svg" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"></path></svg>
                        </span>
                    </a>
                </div>
            </div>
            
            <div class="w-full md:absolute md:top-0 md:right-0 md:w-1/2 md:h-full h-96 relative z-10">
                <!-- Diagonal cut effect -->
                <div class="absolute inset-y-0 left-0 bg-gray-50 transform -skew-x-12 -ml-16 w-32 z-10 hidden md:block"></div>
                <img src="<?= BASE_URL ?>/assets/images/warehouse_hero.jpg" alt="Warehouse" class="absolute inset-0 w-full h-full object-cover">
            </div>
        </div>
    </div>

    <!-- Who We Are Section -->
    <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
        <div class="flex flex-col lg:flex-row items-center gap-16">
            <div class="w-full lg:w-1/2 relative">
                <!-- Decorative dots -->
                <div class="absolute -top-6 -left-6 w-32 h-32 bg-[radial-gradient(#d1d5db_2px,transparent_2px)] [background-size:16px_16px] z-0"></div>
                <img src="<?= BASE_URL ?>/assets/images/warehouse_building.jpg" alt="GRM Building" class="relative z-10 w-full h-auto rounded-3xl shadow-2xl">
                <!-- Decorative block -->
                <div class="absolute -bottom-6 -right-6 w-32 h-24 bg-[#e8cd9c] rounded-2xl z-0"></div>
            </div>
            <div class="w-full lg:w-1/2 space-y-8">
                <div>
                    <h4 class="text-brand-600 font-bold tracking-wider text-sm uppercase mb-3">Who We Are</h4>
                    <h2 class="text-3xl md:text-4xl font-display font-bold text-gray-900 leading-tight">Building Better Wholesale Connections</h2>
                </div>
                <p class="text-gray-600 text-lg leading-relaxed">
                    GRM is a B2B wholesale platform designed to make bulk purchasing simple, efficient and reliable for businesses of all sizes. We connect retailers, resellers, distributors and organizations with a wide range of quality products at competitive prices.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 bg-gray-900 rounded-full p-3 text-white mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-gray-900 font-bold text-lg">Quality Products</h4>
                            <p class="text-gray-500 text-sm mt-1">Curated products that meet business expectations.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 bg-gray-900 rounded-full p-3 text-white mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-gray-900 font-bold text-lg">Reliable Supply</h4>
                            <p class="text-gray-500 text-sm mt-1">Consistent availability and on-time delivery.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 bg-gray-900 rounded-full p-3 text-white mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-gray-900 font-bold text-lg">Competitive Pricing</h4>
                            <p class="text-gray-500 text-sm mt-1">Wholesale prices that help you grow better margins.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 bg-gray-900 rounded-full p-3 text-white mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-gray-900 font-bold text-lg">Business Focused</h4>
                            <p class="text-gray-500 text-sm mt-1">Solutions and support built exclusively for businesses.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Why Choose GRM -->
    <div class="bg-white py-16 px-4 sm:px-6 lg:px-8 max-w-[90rem] mx-auto border-t border-gray-100">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-display font-extrabold text-gray-900">WHY CHOOSE GRM?</h2>
            <div class="w-20 h-1 bg-brand-600 mx-auto mt-4 rounded-full"></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            <div class="bg-white border border-gray-100 rounded-2xl p-6 text-center shadow-sm hover:shadow-md transition-shadow">
                <div class="w-16 h-16 mx-auto mb-4 text-brand-600">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                </div>
                <h4 class="text-gray-900 font-bold text-sm mb-2">Quality Assurance</h4>
                <p class="text-gray-500 text-xs">We ensure every product meets high quality standards.</p>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl p-6 text-center shadow-sm hover:shadow-md transition-shadow">
                <div class="w-16 h-16 mx-auto mb-4 text-brand-600">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                </div>
                <h4 class="text-gray-900 font-bold text-sm mb-2">Better Pricing</h4>
                <p class="text-gray-500 text-xs">Transparent and competitive pricing for your business.</p>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl p-6 text-center shadow-sm hover:shadow-md transition-shadow">
                <div class="w-16 h-16 mx-auto mb-4 text-brand-600">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <h4 class="text-gray-900 font-bold text-sm mb-2">Bulk Purchasing</h4>
                <p class="text-gray-500 text-xs">Order in bulk with flexible quantities and easy process.</p>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl p-6 text-center shadow-sm hover:shadow-md transition-shadow">
                <div class="w-16 h-16 mx-auto mb-4 text-brand-600">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                </div>
                <h4 class="text-gray-900 font-bold text-sm mb-2">On-Time Delivery</h4>
                <p class="text-gray-500 text-xs">Reliable logistics network for timely deliveries.</p>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl p-6 text-center shadow-sm hover:shadow-md transition-shadow">
                <div class="w-16 h-16 mx-auto mb-4 text-brand-600">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <h4 class="text-gray-900 font-bold text-sm mb-2">Dedicated Support</h4>
                <p class="text-gray-500 text-xs">Our team is always ready to support your business.</p>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl p-6 text-center shadow-sm hover:shadow-md transition-shadow">
                <div class="w-16 h-16 mx-auto mb-4 text-brand-600">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <h4 class="text-gray-900 font-bold text-sm mb-2">Secure Transactions</h4>
                <p class="text-gray-500 text-xs">Safe, secure and transparent ordering experience.</p>
            </div>
        </div>
    </div>

    <!-- Mission & Vision -->
    <div class="bg-gray-50 py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-[90rem] mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-display font-extrabold text-gray-900">OUR MISSION & VISION</h2>
                <div class="w-20 h-1 bg-brand-600 mx-auto mt-4 rounded-full"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Mission Card -->
                <div class="bg-[#faf4ec] rounded-3xl p-10 flex flex-col sm:flex-row items-start sm:items-center gap-8 relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-[#f3e4c9] rounded-full opacity-50 z-0"></div>
                    <div class="w-24 h-24 flex-shrink-0 bg-gray-900 rounded-full flex items-center justify-center text-white z-10">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </div>
                    <div class="z-10">
                        <h4 class="text-brand-600 font-bold tracking-wider text-xs uppercase mb-2">Our Mission</h4>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Empowering Businesses Through Better Wholesale</h3>
                        <p class="text-gray-600 leading-relaxed text-sm">
                            Our mission is to simplify the wholesale experience by providing quality products, competitive pricing and dependable service that help businesses grow sustainably.
                        </p>
                    </div>
                </div>
                <!-- Vision Card -->
                <div class="bg-[#f0f4f8] rounded-3xl p-10 flex flex-col sm:flex-row items-start sm:items-center gap-8 relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-[#e2eaf2] rounded-full opacity-50 z-0"></div>
                    <div class="w-24 h-24 flex-shrink-0 bg-gray-900 rounded-full flex items-center justify-center text-white z-10">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </div>
                    <div class="z-10">
                        <h4 class="text-brand-600 font-bold tracking-wider text-xs uppercase mb-2">Our Vision</h4>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">To Be a Trusted Global B2B Wholesale Partner</h3>
                        <p class="text-gray-600 leading-relaxed text-sm">
                            Our vision is to become a globally trusted wholesale partner, known for reliability, transparency and long-term relationships with businesses.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Who We Serve -->
    <div class="bg-white py-20 px-4 sm:px-6 lg:px-8 max-w-[90rem] mx-auto border-t border-gray-100">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-display font-extrabold text-gray-900">WHO WE SERVE</h2>
            <div class="w-20 h-1 bg-brand-600 mx-auto mt-4 rounded-full"></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            <div class="bg-white border border-gray-100 rounded-2xl p-6 text-center shadow-sm">
                <div class="w-12 h-12 mx-auto mb-3 text-brand-600">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <h4 class="text-gray-900 font-bold text-sm mb-2">Retailers</h4>
                <p class="text-gray-500 text-xs">Well-stocked products for retail stores.</p>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl p-6 text-center shadow-sm">
                <div class="w-12 h-12 mx-auto mb-3 text-brand-600">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h4 class="text-gray-900 font-bold text-sm mb-2">Resellers</h4>
                <p class="text-gray-500 text-xs">Great products to resell and grow.</p>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl p-6 text-center shadow-sm">
                <div class="w-12 h-12 mx-auto mb-3 text-brand-600">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h4 class="text-gray-900 font-bold text-sm mb-2">Distributors</h4>
                <p class="text-gray-500 text-xs">Reliable supply for distribution business.</p>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl p-6 text-center shadow-sm">
                <div class="w-12 h-12 mx-auto mb-3 text-brand-600">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                </div>
                <h4 class="text-gray-900 font-bold text-sm mb-2">Online Sellers</h4>
                <p class="text-gray-500 text-xs">Products for e-commerce and online stores.</p>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl p-6 text-center shadow-sm">
                <div class="w-12 h-12 mx-auto mb-3 text-brand-600">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h4 class="text-gray-900 font-bold text-sm mb-2">Dealers</h4>
                <p class="text-gray-500 text-xs">Trusted products for dealership business.</p>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl p-6 text-center shadow-sm">
                <div class="w-12 h-12 mx-auto mb-3 text-brand-600">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <h4 class="text-gray-900 font-bold text-sm mb-2">Bulk Buyers</h4>
                <p class="text-gray-500 text-xs">Solutions for large scale purchasing needs.</p>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="relative bg-gray-900 py-24">
        <div class="absolute inset-0 overflow-hidden">
            <img src="<?= BASE_URL ?>/assets/images/handshake_bg.jpg" alt="Partnership" class="w-full h-full object-cover object-center opacity-30">
            <div class="absolute inset-0 bg-gray-900 bg-opacity-70"></div>
        </div>
        <div class="relative max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-12">
            <div class="w-full md:w-1/2">
                <h4 class="text-brand-600 font-bold tracking-wider text-sm uppercase mb-3">Let's Grow Together</h4>
                <h2 class="text-3xl md:text-5xl font-display font-extrabold text-white mb-6">Ready to Grow Your Business?</h2>
                <p class="text-gray-300 text-lg mb-8 max-w-xl">
                    Partner with GRM and experience the advantage of quality products, better pricing and reliable wholesale solutions.
                </p>
                <a href="<?= BASE_URL ?>/catalog" class="inline-flex items-center px-8 py-3.5 border border-transparent text-base font-bold rounded-lg shadow-sm text-white bg-brand-600 hover:bg-brand-700 transition-colors">
                    Start Shopping Now
                    <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </a>
            </div>
            <div class="w-full md:w-1/2 grid grid-cols-1 sm:grid-cols-3 gap-6 text-center text-white">
                <div>
                    <svg class="w-10 h-10 mx-auto text-brand-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div class="font-bold text-lg">Thousands of</div>
                    <div class="text-xs text-gray-400">Happy Businesses</div>
                </div>
                <div>
                    <svg class="w-10 h-10 mx-auto text-brand-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <div class="font-bold text-lg">Wide Range of</div>
                    <div class="text-xs text-gray-400">Products</div>
                </div>
                <div>
                    <svg class="w-10 h-10 mx-auto text-brand-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    <div class="font-bold text-lg">Secure & Easy</div>
                    <div class="text-xs text-gray-400">Ordering</div>
                </div>
            </div>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 bg-gray-900 rounded-full p-3 text-white mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-gray-900 font-bold text-lg">Business Focused</h4>
                            <p class="text-gray-500 text-sm mt-1">Solutions and support built exclusively for businesses.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Why Choose GRM -->
    <div class="bg-white py-16 px-4 sm:px-6 lg:px-8 max-w-[90rem] mx-auto border-t border-gray-100">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-display font-extrabold text-gray-900">WHY CHOOSE GRM?</h2>
            <div class="w-20 h-1 bg-brand-600 mx-auto mt-4 rounded-full"></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            <div class="bg-white border border-gray-100 rounded-2xl p-6 text-center shadow-sm hover:shadow-md transition-shadow">
                <div class="w-16 h-16 mx-auto mb-4 text-brand-600">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                </div>
                <h4 class="text-gray-900 font-bold text-sm mb-2">Quality Assurance</h4>
                <p class="text-gray-500 text-xs">We ensure every product meets high quality standards.</p>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl p-6 text-center shadow-sm hover:shadow-md transition-shadow">
                <div class="w-16 h-16 mx-auto mb-4 text-brand-600">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                </div>
                <h4 class="text-gray-900 font-bold text-sm mb-2">Better Pricing</h4>
                <p class="text-gray-500 text-xs">Transparent and competitive pricing for your business.</p>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl p-6 text-center shadow-sm hover:shadow-md transition-shadow">
                <div class="w-16 h-16 mx-auto mb-4 text-brand-600">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <h4 class="text-gray-900 font-bold text-sm mb-2">Bulk Purchasing</h4>
                <p class="text-gray-500 text-xs">Order in bulk with flexible quantities and easy process.</p>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl p-6 text-center shadow-sm hover:shadow-md transition-shadow">
                <div class="w-16 h-16 mx-auto mb-4 text-brand-600">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                </div>
                <h4 class="text-gray-900 font-bold text-sm mb-2">On-Time Delivery</h4>
                <p class="text-gray-500 text-xs">Reliable logistics network for timely deliveries.</p>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl p-6 text-center shadow-sm hover:shadow-md transition-shadow">
                <div class="w-16 h-16 mx-auto mb-4 text-brand-600">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <h4 class="text-gray-900 font-bold text-sm mb-2">Dedicated Support</h4>
                <p class="text-gray-500 text-xs">Our team is always ready to support your business.</p>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl p-6 text-center shadow-sm hover:shadow-md transition-shadow">
                <div class="w-16 h-16 mx-auto mb-4 text-brand-600">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <h4 class="text-gray-900 font-bold text-sm mb-2">Secure Transactions</h4>
                <p class="text-gray-500 text-xs">Safe, secure and transparent ordering experience.</p>
            </div>
        </div>
    </div>

    <!-- Mission & Vision -->
    <div class="bg-gray-50 py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-[90rem] mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-display font-extrabold text-gray-900">OUR MISSION & VISION</h2>
                <div class="w-20 h-1 bg-brand-600 mx-auto mt-4 rounded-full"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Mission Card -->
                <div class="bg-[#faf4ec] rounded-3xl p-10 flex flex-col sm:flex-row items-start sm:items-center gap-8 relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-[#f3e4c9] rounded-full opacity-50 z-0"></div>
                    <div class="w-24 h-24 flex-shrink-0 bg-gray-900 rounded-full flex items-center justify-center text-white z-10">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </div>
                    <div class="z-10">
                        <h4 class="text-brand-600 font-bold tracking-wider text-xs uppercase mb-2">Our Mission</h4>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Empowering Businesses Through Better Wholesale</h3>
                        <p class="text-gray-600 leading-relaxed text-sm">
                            Our mission is to simplify the wholesale experience by providing quality products, competitive pricing and dependable service that help businesses grow sustainably.
                        </p>
                    </div>
                </div>
                <!-- Vision Card -->
                <div class="bg-[#f0f4f8] rounded-3xl p-10 flex flex-col sm:flex-row items-start sm:items-center gap-8 relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-[#e2eaf2] rounded-full opacity-50 z-0"></div>
                    <div class="w-24 h-24 flex-shrink-0 bg-gray-900 rounded-full flex items-center justify-center text-white z-10">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </div>
                    <div class="z-10">
                        <h4 class="text-brand-600 font-bold tracking-wider text-xs uppercase mb-2">Our Vision</h4>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">To Be a Trusted Global B2B Wholesale Partner</h3>
                        <p class="text-gray-600 leading-relaxed text-sm">
                            Our vision is to become a globally trusted wholesale partner, known for reliability, transparency and long-term relationships with businesses.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Who We Serve -->
    <div class="bg-white py-20 px-4 sm:px-6 lg:px-8 max-w-[90rem] mx-auto border-t border-gray-100">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-display font-extrabold text-gray-900">WHO WE SERVE</h2>
            <div class="w-20 h-1 bg-brand-600 mx-auto mt-4 rounded-full"></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            <div class="bg-white border border-gray-100 rounded-2xl p-6 text-center shadow-sm">
                <div class="w-12 h-12 mx-auto mb-3 text-brand-600">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <h4 class="text-gray-900 font-bold text-sm mb-2">Retailers</h4>
                <p class="text-gray-500 text-xs">Well-stocked products for retail stores.</p>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl p-6 text-center shadow-sm">
                <div class="w-12 h-12 mx-auto mb-3 text-brand-600">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h4 class="text-gray-900 font-bold text-sm mb-2">Resellers</h4>
                <p class="text-gray-500 text-xs">Great products to resell and grow.</p>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl p-6 text-center shadow-sm">
                <div class="w-12 h-12 mx-auto mb-3 text-brand-600">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h4 class="text-gray-900 font-bold text-sm mb-2">Distributors</h4>
                <p class="text-gray-500 text-xs">Reliable supply for distribution business.</p>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl p-6 text-center shadow-sm">
                <div class="w-12 h-12 mx-auto mb-3 text-brand-600">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                </div>
                <h4 class="text-gray-900 font-bold text-sm mb-2">Online Sellers</h4>
                <p class="text-gray-500 text-xs">Products for e-commerce and online stores.</p>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl p-6 text-center shadow-sm">
                <div class="w-12 h-12 mx-auto mb-3 text-brand-600">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h4 class="text-gray-900 font-bold text-sm mb-2">Dealers</h4>
                <p class="text-gray-500 text-xs">Trusted products for dealership business.</p>
            </div>
            <div class="bg-white border border-gray-100 rounded-2xl p-6 text-center shadow-sm">
                <div class="w-12 h-12 mx-auto mb-3 text-brand-600">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <h4 class="text-gray-900 font-bold text-sm mb-2">Bulk Buyers</h4>
                <p class="text-gray-500 text-xs">Solutions for large scale purchasing needs.</p>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="relative bg-gray-900 py-24">
        <div class="absolute inset-0 overflow-hidden">
            <img src="<?= BASE_URL ?>/assets/images/handshake_bg.jpg" alt="Partnership" class="w-full h-full object-cover object-center opacity-30">
            <div class="absolute inset-0 bg-gray-900 bg-opacity-70"></div>
        </div>
        <div class="relative max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-12">
            <div class="w-full md:w-1/2">
                <h4 class="text-brand-600 font-bold tracking-wider text-sm uppercase mb-3">Let's Grow Together</h4>
                <h2 class="text-3xl md:text-5xl font-display font-extrabold text-white mb-6">Ready to Grow Your Business?</h2>
                <p class="text-gray-300 text-lg mb-8 max-w-xl">
                    Partner with GRM and experience the advantage of quality products, better pricing and reliable wholesale solutions.
                </p>
                <a href="<?= BASE_URL ?>/catalog" class="inline-flex items-center px-8 py-3.5 border border-transparent text-base font-bold rounded-lg shadow-sm text-white bg-brand-600 hover:bg-brand-700 transition-colors">
                    Start Shopping Now
                    <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </a>
            </div>
            <div class="w-full md:w-1/2 grid grid-cols-1 sm:grid-cols-3 gap-6 text-center text-white">
                <div>
                    <svg class="w-10 h-10 mx-auto text-brand-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div class="font-bold text-lg">Thousands of</div>
                    <div class="text-xs text-gray-400">Happy Businesses</div>
                </div>
                <div>
                    <svg class="w-10 h-10 mx-auto text-brand-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <div class="font-bold text-lg">Wide Range of</div>
                    <div class="text-xs text-gray-400">Products</div>
                </div>
                <div>
                    <svg class="w-10 h-10 mx-auto text-brand-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    <div class="font-bold text-lg">Secure & Easy</div>
                    <div class="text-xs text-gray-400">Ordering</div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if (!isset($skipAboutJsListener)): ?>
<script>
// Listen for live preview messages from the CMS Layout Builder
window.addEventListener('message', function(event) {
    if (event.data.action === 'toggle') {
        const section = document.querySelector(`.cms-section[data-cms-id="${event.data.id}"]`);
        if (section) {
            section.style.display = event.data.active ? 'block' : 'none';
        }
    }
});
</script>
<?php endif; ?>
