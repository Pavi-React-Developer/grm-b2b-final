<div class="bg-[#faf9f8] min-h-screen pb-12 sm:pb-16">

    <!-- ── Breadcrumb Section ── -->
    <div class="bg-white border-b border-gray-200/80 mb-6 sm:mb-8">
        <div class="w-full max-w-full mx-auto px-2 sm:px-6 lg:px-8 py-3.5">
            <nav class="grm-breadcrumb-nav hide-scrollbar no-scrollbar scrollbar-none flex flex-wrap items-center gap-1 sm:gap-1.5 text-xs sm:text-sm font-medium" aria-label="Breadcrumb">
                <a href="<?= BASE_URL ?>/" class="inline-flex items-center gap-1 text-[#F25996] hover:text-[#d8407d] font-semibold transition-colors shrink-0">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 shrink-0 text-[#F25996]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                    </svg>
                    <span>Home</span>
                </a>
                <svg class="w-3.5 h-3.5 text-[#F25996] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-[#F25996] font-bold">Size Charts</span>
            </nav>
        </div>
    </div>

    <div class="w-full max-w-full mx-auto px-2 sm:px-6 lg:px-8 space-y-8">
        


        <!-- Banner Header -->
        <div class="bg-white rounded-[2rem] p-6 sm:p-10 border border-gray-100 shadow-sm flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            <div class="max-w-xl">
                <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-[#f25996] bg-[#fdf2f7] px-3 py-1 rounded-full border border-[#fbaed2] mb-3">
                    📏 Official Measurement Guide
                </span>
                <h1 class="text-2xl sm:text-4xl font-black text-gray-900 tracking-tight font-serif">
                    Garment Size & Measurement Charts
                </h1>
                <p class="text-sm text-gray-600 mt-2 leading-relaxed">
                    Accurate size specifications for all women's dresses, coords sets, kurtas, and baby & kids apparel. Measurements are standard B2B specifications in inches.
                </p>
            </div>
            <!-- Search & Quick Navigation -->
            <div class="w-full lg:w-80 shrink-0">
                <label class="block text-xs font-bold text-black uppercase tracking-wider mb-1.5">Find Your Style</label>
                <div class="relative">
                    <input type="text" id="sfSearchInput" oninput="sfFilterCharts()" placeholder="Search by dress or style..." class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-full text-xs font-medium focus:ring-2 focus:ring-[#f25996] focus:border-[#f25996] outline-none bg-gray-50/50">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
        </div>

        <!-- Category Filter Pills -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
            <button onclick="sfSelectCategory('all', this)" class="sf-cat-btn px-5 py-2 rounded-full text-xs font-bold transition-all whitespace-nowrap" style="background-color: #F25996; color: #ffffff; border: 2px solid #FDBFDD; outline: none; box-shadow: none;">
                All Size Charts (<?= count($charts) ?>)
            </button>
            <?php 
            $uniqueCats = [];
            foreach ($charts as $c) {
                if (!empty($c['category_name'])) {
                    $uniqueCats[$c['category_name']] = ($uniqueCats[$c['category_name']] ?? 0) + 1;
                }
            }
            foreach ($uniqueCats as $cName => $count): 
            ?>
                <button onclick="sfSelectCategory('<?= htmlspecialchars(strtolower($cName)) ?>', this)" class="sf-cat-btn px-5 py-2 rounded-full text-xs font-bold transition-all whitespace-nowrap hover:bg-pink-50" style="background-color: #ffffff; color: #374151; border: 1px solid #e5e7eb; outline: none;">
                    <?= htmlspecialchars($cName) ?> (<?= $count ?>)
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Size Charts Display Grid -->
        <div class="space-y-10" id="sizeChartsContainer">
            <?php if (empty($charts)): ?>
                <div class="bg-white rounded-[2rem] p-12 text-center border border-gray-100 shadow-sm">
                    <p class="text-gray-500 font-medium">No size charts currently published. Please check back later.</p>
                </div>
            <?php else: ?>
                <?php foreach ($charts as $chart): 
                    $catAttr = strtolower($chart['category_name'] ?: 'general');
                    $cols = $chart['columns'];
                    $rows = $chart['rows'];
                ?>
                    <div class="sf-chart-card bg-white rounded-[2rem] p-6 sm:p-10 border border-gray-200/90 shadow-sm transition-all hover:shadow-md" data-category="<?= htmlspecialchars($catAttr) ?>" data-title="<?= htmlspecialchars(strtolower($chart['title'])) ?>" id="chart-<?= $chart['id'] ?>">
                        <!-- Card Header -->
                        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 mb-6 pb-2">
                            <div>
                                <h2 class="text-xl sm:text-2xl font-black text-gray-900 uppercase tracking-tight font-serif inline-block pb-1 leading-tight" style="border-bottom: 4px solid #f25996;">
                                    <?= htmlspecialchars($chart['title']) ?>
                                </h2>
                                <?php if (!empty($chart['dress_type'])): ?>
                                    <span class="block text-xs font-bold text-gray-400 mt-1"><?= htmlspecialchars($chart['dress_type']) ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <?php if (!empty($chart['category_name'])): ?>
                                    <span class="inline-block px-3 py-1 rounded-full bg-[#fdf2f7] border border-[#fbaed2] text-[#f25996] text-[11px] font-bold">
                                        <?= htmlspecialchars($chart['category_name']) ?>
                                    </span>
                                <?php endif; ?>
                                <span class="inline-block px-4 py-1.5 rounded-full border border-gray-300 text-gray-700 text-xs font-bold tracking-tight bg-gray-50 whitespace-nowrap shadow-2xs">
                                    <?= htmlspecialchars($chart['tolerance_note'] ?: 'Size in inches (+ or - 0.5")') ?>
                                </span>
                            </div>
                        </div>

                        <!-- Content: Table + Optional Illustration Diagram -->
                        <div class="grid grid-cols-1 <?= !empty($chart['image_url']) ? 'lg:grid-cols-12 gap-6' : '' ?> items-start">
                            <div class="<?= !empty($chart['image_url']) ? 'lg:col-span-8' : 'w-full' ?>">
                                <div class="overflow-x-auto rounded-xl border border-gray-100 bg-white shadow-2xs">
                                    <table class="w-full text-center border-collapse min-w-[480px]">
                                        <thead class="bg-white text-gray-900 font-bold text-xs sm:text-sm border-b-2 border-gray-200">
                                            <tr>
                                                <?php foreach ($cols as $c): ?>
                                                    <th class="py-3.5 px-4 font-bold text-gray-900 whitespace-nowrap"><?= htmlspecialchars($c) ?></th>
                                                <?php endforeach; ?>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 text-xs sm:text-sm font-semibold text-gray-800">
                                            <?php foreach ($rows as $r): ?>
                                                <tr class="hover:bg-pink-50/30 transition-colors">
                                                    <?php foreach ($cols as $cIndex => $c): 
                                                        $val = $r[$c] ?? ($r[strtolower($c)] ?? '-');
                                                        $isSize = ($cIndex === 0 || strtolower($c) === 'size');
                                                    ?>
                                                        <td class="py-3.5 px-4 <?= $isSize ? 'font-black text-gray-900 bg-gray-50/40' : 'font-medium text-gray-700' ?>">
                                                            <?= htmlspecialchars((string)$val) ?>
                                                        </td>
                                                    <?php endforeach; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <?php if (!empty($chart['image_url'])): ?>
                                <div class="lg:col-span-4 mt-4 lg:mt-0">
                                    <div class="bg-gray-50/80 p-4 rounded-2xl border border-gray-200/80 shadow-2xs flex flex-col items-center">
                                        <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-[#F25996]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            Measurement Guide Diagram
                                        </span>
                                        <a href="<?= htmlspecialchars($chart['image_url']) ?>" target="_blank" rel="noopener noreferrer" class="block w-full text-center group" title="Click to view full size diagram">
                                            <img src="<?= htmlspecialchars($chart['image_url']) ?>" alt="<?= htmlspecialchars($chart['title']) ?> Measurement Diagram" class="w-full h-auto max-h-64 object-contain rounded-xl border border-gray-200 bg-white p-2 group-hover:scale-[1.02] transition-transform duration-200 shadow-2xs">
                                            <span class="inline-flex items-center gap-1 text-[11px] text-[#F25996] font-bold mt-2 group-hover:underline">
                                                <span>🔍 Click to Enlarge Diagram</span>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- How to Measure Tips Card -->
        <div class="bg-white rounded-[2rem] p-6 sm:p-8 border border-gray-100 shadow-sm">
            <h3 class="text-base font-black text-gray-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                <span>💡</span> Helpful Sizing Tips
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-xs text-gray-600">
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <strong class="block font-bold text-gray-900 text-sm mb-1">1. Bust / Chest</strong>
                    Measure under arms around the fullest part of the bust, keeping the tape level across shoulder blades.
                </div>
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <strong class="block font-bold text-gray-900 text-sm mb-1">2. Top / Full Length</strong>
                    Measure from the highest point of shoulder seam down to the bottom hem of the garment.
                </div>
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <strong class="block font-bold text-gray-900 text-sm mb-1">3. Pant Hip & Length</strong>
                    Measure hip around the fullest portion. Measure pant length from waistband down to ankle hem.
                </div>
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <strong class="block font-bold text-gray-900 text-sm mb-1">4. Tolerance Margin</strong>
                    All dimensions are standard apparel measurements with a allowable manufacturing tolerance of ±0.5" to 1".
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function sfSelectCategory(cat, btn) {
    document.querySelectorAll('.sf-cat-btn').forEach(b => {
        b.style.backgroundColor = '#ffffff';
        b.style.color = '#374151';
        b.style.border = '1px solid #e5e7eb';
        b.style.outline = 'none';
        b.style.boxShadow = 'none';
    });
    btn.style.backgroundColor = '#F25996';
    btn.style.color = '#ffffff';
    btn.style.border = '2px solid #FDBFDD';
    btn.style.outline = 'none';
    btn.style.boxShadow = 'none';

    const cards = document.querySelectorAll('.sf-chart-card');
    cards.forEach(card => {
        const cCat = card.getAttribute('data-category') || '';
        if (cat === 'all' || cCat === cat) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}

function sfFilterCharts() {
    const q = document.getElementById('sfSearchInput').value.toLowerCase().trim();
    const cards = document.querySelectorAll('.sf-chart-card');
    cards.forEach(card => {
        const title = card.getAttribute('data-title') || '';
        const cat = card.getAttribute('data-category') || '';
        if (title.includes(q) || cat.includes(q)) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
