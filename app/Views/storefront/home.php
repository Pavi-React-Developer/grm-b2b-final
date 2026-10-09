<?php
// Dynamic Headless CMS Renderer for Storefront
$canAddToCart = \Core\Session::get('user_id') && \Core\Session::get('user_status') === 'active';

// $layoutSections is now injected by HomeController to avoid PHP built-in server deadlock
?>
<div id="cms-sections-container" class="bg-white min-h-screen">
    <?php if (empty($layoutSections) || isset($layoutSections['error'])): ?>
        <div class="text-center py-20">
            <p class="text-red-500">Failed to load storefront layout. Please check CMS Configuration.</p>
        </div>
    <?php else: ?>
        <?php foreach ($layoutSections as $section): ?>
            <?php 
                if ($section['section_type'] === 'navbar') continue;
                if ($section['section_type'] === 'top_bar') continue;
                if ($section['section_type'] === 'footer') continue;
                if ($section['section_type'] === 'about_us') continue;
                
                $componentFile = __DIR__ . "/components/{$section['section_type']}.php";
                if (file_exists($componentFile)) {
                    echo '<div class="cms-section transition-all duration-300" data-cms-id="' . htmlspecialchars($section['id']) . '" style="display: ' . ($section['is_active'] ? 'block' : 'none') . '; margin-bottom: 0px;">';
                    // Inject specific data if needed
                    $contentData = $section['content_data'] ?? [];
                    include $componentFile;
                    echo '</div>';
                }
            ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
// Listen for live preview messages from the CMS Layout Builder
window.addEventListener('message', function(event) {
    if (event.data.action === 'toggle') {
        const section = document.querySelector(`.cms-section[data-cms-id="${event.data.id}"]`);
        if (section) {
            section.style.display = event.data.active ? 'block' : 'none';
        }
    } else if (event.data.action === 'reorder') {
        const container = document.getElementById('cms-sections-container');
        if (!container) return;
        const sections = Array.from(document.querySelectorAll('.cms-section'));
        
        event.data.order.forEach(id => {
            const section = sections.find(s => s.getAttribute('data-cms-id') === id);
            if (section) {
                container.appendChild(section);
            }
        });
    }
});



function updateQty(btn, change, minVal, maxVal = null) {
    if (event) event.stopPropagation();
    const input = btn.parentElement.querySelector('input[name="quantity"]');
    let val = parseInt(input.value) || minVal;
    val += change;
    if (val < minVal) val = minVal;
    if (maxVal !== null && val > maxVal) {
        val = maxVal;
        alert('You cannot order more than the available stock (' + maxVal + ').');
    }
    input.value = val;
}
</script>
