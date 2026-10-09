<?php
// Storefront Footer Component
// Available variable: $contentData (array from JSON)

$theme        = $contentData['theme'] ?? [];
$bgValue      = $theme['value'] ?? '#3d2b1f';
$bgStyle      = ($theme['type'] ?? 'solid') === 'css' && preg_match('/\bbackground(?:-color|-image)?\s*:/i', (string)$bgValue)
    ? (string)$bgValue
    : 'background: ' . (string)$bgValue . ';';
$textColor    = $theme['text_color'] ?? '#c4a882';
$headingColor = $theme['heading_color'] ?? '#ffffff';
$linkColor    = $theme['link_color'] ?? '#d4b896';

$logo         = $contentData['logo'] ?? '';
$description  = $contentData['description'] ?? '';
$copyright    = $contentData['copyright'] ?? '';
$contact      = $contentData['contact'] ?? [];
$maps         = $contentData['maps'] ?? [];
$social       = $contentData['social'] ?? [];
$columns      = $contentData['columns'] ?? [];

$socialIcons = [
    'facebook'  => 'M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z',
    'instagram' => 'M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01M6.5 2h11A4.5 4.5 0 0122 6.5v11a4.5 4.5 0 01-4.5 4.5h-11A4.5 4.5 0 012 17.5v-11A4.5 4.5 0 016.5 2z',
    'youtube'   => 'M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 00-1.95 1.96A29 29 0 001 12a29 29 0 00.46 5.58A2.78 2.78 0 003.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 001.95-1.95A29 29 0 0023 12a29 29 0 00-.46-5.58zM9.75 15.02V8.98L15.5 12l-5.75 3.02z',
    'twitter'   => 'M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z',
    'pinterest' => 'M12 2C6.48 2 2 6.48 2 12c0 4.24 2.65 7.86 6.39 9.29-.09-.78-.17-1.98.04-2.83.18-.77 1.22-5.16 1.22-5.16s-.31-.63-.31-1.56c0-1.46.85-2.55 1.9-2.55.9 0 1.33.67 1.33 1.48 0 .9-.58 2.26-.87 3.51-.25 1.05.52 1.9 1.54 1.9 1.85 0 3.09-2.37 3.09-5.17 0-2.14-1.44-3.64-3.5-3.64-2.38 0-3.78 1.79-3.78 3.64 0 .72.28 1.49.62 1.91.07.08.08.16.06.25-.06.26-.21.87-.24.99-.04.16-.13.19-.29.12-1.08-.5-1.75-2.09-1.75-3.36 0-2.73 1.98-5.23 5.7-5.23 2.99 0 5.32 2.13 5.32 4.98 0 2.97-1.87 5.35-4.46 5.35-.87 0-1.69-.45-1.97-.98l-.54 2c-.19.74-.72 1.66-1.07 2.22.81.25 1.66.38 2.55.38 5.52 0 10-4.48 10-10S17.52 2 12 2z',
];

$activeSocials = array_filter($social, fn($url) => !empty($url));

// Build dynamic grid columns: brand (1.6fr) | ...link cols (1fr each) | contact (1fr) | map (1.4fr)
$hasContact = !empty($contact['phone']) || !empty($contact['email']);
$hasMap     = !empty($maps['address']);

$gridParts = ['1.6fr'];
foreach ($columns as $_) { $gridParts[] = '1fr'; }
if ($hasContact) $gridParts[] = '1fr';
if ($hasMap)     $gridParts[] = '1.4fr';

$gridTemplate = implode(' ', $gridParts);
?>

<style>
    /* Mobile: full single column */
    @media (max-width: 767px) {
        .footer-grid-container {
            grid-template-columns: 1fr !important;
            gap: 2rem !important;
        }
        .footer-bottom-bar {
            flex-direction: column !important;
            align-items: center !important;
            text-align: center !important;
            gap: 0.75rem !important;
        }
    }
    /* Tablet: 2-column grid */
    @media (min-width: 768px) and (max-width: 1023px) {
        .footer-grid-container {
            grid-template-columns: 1fr 1fr !important;
            gap: 2rem !important;
        }
        .footer-map-embed {
            max-width: 100% !important;
            overflow: hidden !important;
        }
        .footer-map-embed iframe {
            width: 100% !important;
        }
    }
</style>

<footer style="<?= htmlspecialchars($bgStyle) ?> color: <?= htmlspecialchars($textColor) ?>; font-family: 'Inter', 'Segoe UI', sans-serif; font-size: 15px; line-height: 1.6;" class="w-full">

    <!-- ── Main Footer Body ── -->
    <div class="w-full mx-auto px-6 sm:px-8 lg:px-12" style="padding-top:4rem; padding-bottom:4rem;">

        <div class="footer-grid-container" style="display:grid; grid-template-columns:<?= htmlspecialchars($gridTemplate) ?>; gap:3rem; align-items:start;">

            <!-- Col 1: Brand + Description + Social -->
            <div style="display:flex; flex-direction:column; gap:1.25rem; padding-right:1rem;">
                <?php if ($logo): ?>
                    <img src="<?= htmlspecialchars($logo) ?>" alt="Store Logo" style="height:3rem; width:auto; object-fit:contain;">
                <?php else: ?>
                    <div style="font-size:1.25rem; font-weight:700; line-height:1.2; color:<?= htmlspecialchars($headingColor) ?>; font-family: 'Inter', 'Segoe UI', sans-serif;">GRM B2B</div>
                <?php endif; ?>

                <?php if ($description): ?>
                    <p style="font-size:0.875rem; line-height:1.7; color:<?= htmlspecialchars($textColor) ?>; font-family: 'Inter', 'Segoe UI', sans-serif;"><?= nl2br(htmlspecialchars($description)) ?></p>
                <?php endif; ?>

                <?php if (!empty($activeSocials)): ?>
                <div style="display:flex; align-items:center; gap:1rem; padding-top:0.25rem;">
                    <?php foreach ($activeSocials as $network => $url): ?>
                        <a href="<?= htmlspecialchars($url) ?>" target="_blank" rel="noopener noreferrer"
                           title="<?= ucfirst($network) ?>"
                           style="color:<?= htmlspecialchars($linkColor) ?>; opacity:0.75; transition:opacity 0.2s;"
                           onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.75'">
                            <svg style="width:1.25rem; height:1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="<?= $socialIcons[$network] ?? '' ?>"/>
                            </svg>
                        </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Dynamic Link Columns -->
            <?php foreach ($columns as $col): ?>
            <div style="display:flex; flex-direction:column; gap:1rem;">
                <h3 style="font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.12em; color:<?= htmlspecialchars($headingColor) ?>; margin:0; font-family: 'Inter', 'Segoe UI', sans-serif;">
                    <?= htmlspecialchars($col['title'] ?? '') ?>
                </h3>
                <ul style="list-style:none; margin:0; padding:0; display:flex; flex-direction:column; gap:0.625rem;">
                    <?php foreach ($col['links'] ?? [] as $link): ?>
                    <li>
                                <a href="<?= htmlspecialchars($link['url'] ?? '#') ?>"
                                    style="font-size:0.875rem; color:<?= htmlspecialchars($linkColor) ?>; opacity:0.8; text-decoration:none; transition:opacity 0.2s; font-family: 'Inter', 'Segoe UI', sans-serif;"
                           onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.8'">
                            <?= htmlspecialchars($link['label'] ?? '') ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endforeach; ?>

            <!-- Contact Column -->
            <?php if ($hasContact): ?>
            <div style="display:flex; flex-direction:column; gap:1rem;">
                <h3 style="font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.12em; color:<?= htmlspecialchars($headingColor) ?>; margin:0; font-family: 'Inter', 'Segoe UI', sans-serif;">Contact</h3>
                <ul style="list-style:none; margin:0; padding:0; display:flex; flex-direction:column; gap:0.75rem;">
                    <?php if (!empty($contact['phone'])): ?>
                    <li style="display:flex; align-items:flex-start; gap:0.625rem; font-size:0.875rem;">
                        <svg style="width:1rem; height:1rem; flex-shrink:0; margin-top:0.1rem; opacity:0.7; color:<?= htmlspecialchars($textColor) ?>;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span style="color:<?= htmlspecialchars($textColor) ?>; font-family: 'Inter', 'Segoe UI', sans-serif;"><?= htmlspecialchars($contact['phone']) ?></span>
                    </li>
                    <?php endif; ?>
                    <?php if (!empty($contact['email'])): ?>
                    <li style="display:flex; align-items:flex-start; gap:0.625rem; font-size:0.875rem;">
                        <svg style="width:1rem; height:1rem; flex-shrink:0; margin-top:0.1rem; opacity:0.7; color:<?= htmlspecialchars($textColor) ?>;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <a href="mailto:<?= htmlspecialchars($contact['email']) ?>"
                           style="color:<?= htmlspecialchars($linkColor) ?>; opacity:0.8; text-decoration:none; font-family: 'Inter', 'Segoe UI', sans-serif;"
                           onmouseover="this.style.opacity='1';this.style.textDecoration='underline'" onmouseout="this.style.opacity='0.8';this.style.textDecoration='none'">
                           <?= htmlspecialchars($contact['email']) ?>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            <?php endif; ?>

            <!-- Find Us (Map) Column -->
            <?php if ($hasMap): ?>
            <div style="display:flex; flex-direction:column; gap:1rem;">
                <div class="footer-map-embed" style="border-radius:0.75rem; overflow:hidden; border:1px solid rgba(255,255,255,0.15); width:100%; height:9rem;">
                    <iframe src="<?= htmlspecialchars($maps['address']) ?>"
                        title="Store Location Map"
                        style="width:100%; height:100%; border:none;" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                <?php if (!empty($maps['map_link'])): ?>
                <a href="<?= htmlspecialchars($maps['map_link']) ?>" target="_blank" rel="noopener noreferrer"
                   style="display:inline-flex; align-items:center; gap:0.375rem; font-size:0.75rem; color:<?= htmlspecialchars($linkColor) ?>; opacity:0.75; text-decoration:none; font-family: 'Inter', 'Segoe UI', sans-serif;"
                   onmouseover="this.style.opacity='1';this.style.textDecoration='underline'" onmouseout="this.style.opacity='0.75';this.style.textDecoration='none'">
                    <svg style="width:0.875rem; height:0.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Open in Google Maps
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- ── Copyright Bottom Bar ── -->
    <div style="border-top: 1px solid rgba(255,255,255,0.12);">
        <div class="footer-bottom-bar w-full mx-auto px-6 sm:px-8 lg:px-12"
             style="padding-top:1rem; padding-bottom:1rem; display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:0.5rem;">
            <p style="font-size:0.75rem; color:<?= htmlspecialchars($textColor) ?>; opacity:0.6; margin:0; font-family: 'Inter', 'Segoe UI', sans-serif;">
                <?= htmlspecialchars($copyright ?: ('© ' . date('Y') . ' GRM Garments B2B. All rights reserved.')) ?>
            </p>
            <p style="font-size:0.75rem; color:<?= htmlspecialchars($textColor) ?>; opacity:0.6; margin:0; display:flex; align-items:center; gap:0.375rem; font-family: 'Inter', 'Segoe UI', sans-serif;">
                <svg style="width:0.875rem; height:0.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
                </svg>
                Global Shipping Available
            </p>
        </div>
    </div>

</footer>
