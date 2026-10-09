<?php
$contentData = $contentData ?? [];
$global = $contentData['global_settings'] ?? [];
$menuItems = $contentData['menu_items'] ?? [];

$logo = $global['logo'] ?? '';
$logoCta = $global['logo_cta_url'] ?? '/';
$logoPos = $global['logo_position'] ?? 'Left';
$showSystemIcons = isset($global['show_system_icons']) ? (bool)$global['show_system_icons'] : true;
$containerHeight = isset($global['container_height']) ? (int)$global['container_height'] : 10;
$desktopMinHeight = ($containerHeight > 30) ? $containerHeight : max(64, $containerHeight * 6);

$globalFontSize = !empty($global['font_size']) ? (int)$global['font_size'] : 14;
$globalIconSize = !empty($global['icon_size']) ? (int)$global['icon_size'] : 20;

// Backward compatibility for bg_color
$bgColorVal = '#ffffff';
if (isset($global['bg_color'])) {
    if (is_array($global['bg_color'])) {
        $bgColorVal = $global['bg_color']['value'] ?? '#ffffff';
    } else {
        $bgColorVal = $global['bg_color'];
    }
}

$textColor = $global['text_color'] ?? '#8C6A4F';

function getInlineStyleStr($property, $value) {
    if (strpos($value, ':') !== false) {
        $val = trim($value);
        return htmlspecialchars($val) . (substr($val, -1) === ';' ? '' : ';');
    }
    return htmlspecialchars($property) . ': ' . htmlspecialchars($value) . ';';
}
$bgInline = getInlineStyleStr('background', $bgColorVal);
$textInline = getInlineStyleStr('color', $textColor);

// Ensure Customize is available in navbar
$hasCustomizeInNav = false;
foreach ($menuItems as $m) {
    if (isset($m['cta_url']) && strpos($m['cta_url'], 'customize') !== false) {
        $hasCustomizeInNav = true;
        break;
    }
}
if (!$hasCustomizeInNav) {
    $menuItems[] = [
        'title' => 'Customize',
        'cta_url' => BASE_URL . '/customize',
        'position' => 'Left',
        'active' => 1,
        'is_dropdown' => 0
    ];
}

$leftItems = [];
$centerItems = [];
$rightItems = [];
foreach ($menuItems as $item) {
    if (!isset($item['active']) || $item['active']) {
        $pos = $item['position'] ?? 'Left';
        if ($pos === 'Right') {
            $rightItems[] = $item;
        } elseif ($pos === 'Center') {
            $centerItems[] = $item;
        } else {
            $leftItems[] = $item;
        }
    }
}

$renderItems = function($items) use ($textColor, $globalFontSize, $globalIconSize) {
    if (empty($items)) return;
    foreach ($items as $item):
        $itemBg = !empty($item['bg_color']) && $item['bg_color'] !== '#ffffff' && $item['bg_color'] !== 'transparent' ? $item['bg_color'] : 'transparent';
        $itemText = !empty($item['text_color']) ? $item['text_color'] : $textColor;
        $fontSize = !empty($item['font_size']) ? (int)$item['font_size'] : $globalFontSize;
        $fontSizeCss = $fontSize > 0 ? "font-size: {$fontSize}px;" : "";
        $hasDropdown = !empty($item['is_dropdown']);
?>
        <div class="relative group shrink-0">
            <a href="<?= htmlspecialchars($item['cta_url'] ?? '#') ?>" 
               class="px-3 xl:px-4 py-2 rounded-lg font-bold tracking-normal transition-all duration-150 flex items-center gap-1.5 whitespace-nowrap shrink-0 hover:bg-black/5 active:bg-black/10 hover:opacity-90 active:scale-95" 
               style="background-color: <?= htmlspecialchars($itemBg) ?>; color: <?= htmlspecialchars($itemText) ?>; <?= $fontSizeCss ?>">
                <span><?= htmlspecialchars($item['title'] ?? '') ?></span>
                <?php if ($hasDropdown): ?>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4 xl:w-4.5 xl:h-4.5 shrink-0 opacity-80 group-hover:rotate-180 transition-transform duration-200" style="<?= $fontSize ? 'width: ' . max(12, (int)round($fontSize * 0.9)) . 'px; height: ' . max(12, (int)round($fontSize * 0.9)) . 'px;' : '' ?>"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                <?php endif; ?>
            </a>
            
            <?php if ($hasDropdown): ?>
            <?php 
                $hasAutoFill = !empty($item['auto_fill_catalog']);
                $catIds = [];
                if ($hasAutoFill) {
                    $catIds = is_string($item['auto_fill_catalog']) ? explode(',', $item['auto_fill_catalog']) : (array)$item['auto_fill_catalog'];
                    $catIds = array_filter(array_map('intval', $catIds));
                }
                $isMega = count($catIds) > 1;
                $dropdownBg = $item['dropdown_bg_color'] ?? '#ffffff';
                $dropdownText = $item['dropdown_text_color'] ?? '#111827';
            ?>
            <div class="absolute top-full <?= $isMega ? 'left-1/2 -translate-x-1/2 origin-top' : 'left-0 origin-top-left' ?> mt-2 shadow-2xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 transform <?= $isMega ? '' : 'w-64 rounded-xl' ?>" style="background-color: <?= htmlspecialchars($dropdownBg) ?>; <?= $isMega ? 'border-radius: 12px; width: 750px; max-width: 90vw;' : '' ?>">
                <?php if (!empty($catIds)): 
                    $db = \Core\Database::getInstance();
                    $inClause = implode(',', $catIds);
                    $subCats = $db->query("SELECT id, name, slug FROM categories WHERE id IN ($inClause) AND status = 'active' ORDER BY name ASC")->fetchAll();
                    if (count($subCats) > 0):
                ?>
                    <div class="p-8 flex flex-wrap gap-x-12 gap-y-10 overflow-y-auto" style="max-height: 75vh;">
                        <?php foreach ($subCats as $subCat): 
                            $stmt = $db->prepare("SELECT id, name, slug FROM products WHERE category_id = ? AND status = 'active' ORDER BY name ASC LIMIT 10");
                            $stmt->execute([$subCat['id']]);
                            $products = $stmt->fetchAll();
                        ?>
                        <div class="flex flex-col flex-1" style="min-width: 200px; max-width: 280px;">
                            <a href="<?= BASE_URL ?>/catalog?category_id=<?= htmlspecialchars($subCat['id']) ?>" class="block font-bold uppercase mb-4 hover:opacity-80 transition-opacity border-b border-gray-200 pb-3" style="color: <?= htmlspecialchars($dropdownText) ?>; letter-spacing: 1px;">
                                <?= htmlspecialchars($subCat['name']) ?>
                            </a>
                            <div class="flex flex-col space-y-3.5">
                                <?php if (count($products) > 0): ?>
                                    <?php foreach ($products as $prod): ?>
                                        <a href="<?= BASE_URL ?>/product?id=<?= htmlspecialchars($prod['id']) ?>" class="hover:opacity-80 transition-opacity flex items-center gap-3 group/link font-medium text-sm" style="color: <?= htmlspecialchars($dropdownText) ?>; opacity: 0.85;">
                                            <span class="w-1.5 h-1.5 rounded-full flex-shrink-0" style="background-color: <?= htmlspecialchars($dropdownText) ?>; opacity: 0.6;"></span>
                                            <span class="leading-snug break-words"><?= htmlspecialchars($prod['name']) ?></span>
                                        </a>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="italic text-xs" style="color: <?= htmlspecialchars($dropdownText) ?>; opacity: 0.5;">No products</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="px-6 py-4 text-sm text-gray-500 italic">No categories found</div>
                <?php endif; ?>
                <?php else: ?>
                    <div class="px-6 py-4 text-sm text-gray-500 italic">No sub-items defined</div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
<?php
    endforeach;
};

$renderLogo = function() use ($logo, $logoCta, $textColor) {
?>
    <a href="<?= htmlspecialchars($logoCta) ?>" class="flex items-center shrink-0">
        <?php if ($logo): ?>
            <img src="<?= htmlspecialchars($logo) ?>" alt="Navbar Logo" class="h-9 lg:h-10 max-h-11 max-w-[160px] lg:max-w-[220px] object-contain shrink-0">
        <?php else: ?>
            <span class="text-xl font-black tracking-tight" style="color: <?= htmlspecialchars($textColor) ?>;">Brand Logo</span>
        <?php endif; ?>
    </a>
<?php }; ?>

<?php
$renderSystemIcons = function() use ($textColor, $bgColorVal, $globalFontSize, $globalIconSize) {
    $iconSvgStyle = $globalIconSize > 0 ? "width: {$globalIconSize}px; height: {$globalIconSize}px;" : "";
    $loginFontSizeCss = $globalFontSize > 0 ? "font-size: {$globalFontSize}px;" : "";
?>
    <div class="flex items-center space-x-2 lg:space-x-3 xl:space-x-4 pl-2 lg:pl-4 shrink-0" style="color: <?= htmlspecialchars($textColor) ?>;">
        <!-- Search Icon -->
        <a href="javascript:void(0)" onclick="openSearchModal(event)" class="w-9 h-9 rounded-full flex items-center justify-center transition-all duration-150 hover:bg-black/5 active:bg-black/10 cursor-pointer shrink-0" style="color: <?= htmlspecialchars($textColor) ?>;" title="Search">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5 shrink-0" style="<?= $iconSvgStyle ?>"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </a>

        <?php if (\Core\Session::get('user_id')): ?>
        <div class="flex items-center space-x-2 lg:space-x-3 xl:space-x-4 shrink-0">
            <!-- Wishlist Icon -->
            <a href="javascript:void(0)" onclick="openWishlistDrawer()" class="w-9 h-9 rounded-full flex items-center justify-center transition-all duration-150 hover:bg-black/5 active:bg-black/10 relative shrink-0" style="color: <?= htmlspecialchars($textColor) ?>;" title="Wishlist">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5 shrink-0" style="<?= $iconSvgStyle ?>"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                <span id="wishlist-badge" class="absolute top-0.5 right-0.5 bg-rose-600 text-white text-[10px] font-bold h-4 w-4 rounded-full flex items-center justify-center hidden shadow-xs">0</span>
            </a>

            <!-- Cart Icon -->
            <a href="javascript:void(0)" onclick="openCartDrawer()" class="w-9 h-9 rounded-full flex items-center justify-center transition-all duration-150 hover:bg-black/5 active:bg-black/10 relative shrink-0" style="color: <?= htmlspecialchars($textColor) ?>;" title="Cart">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="w-5 h-5 shrink-0" style="<?= $iconSvgStyle ?>"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span id="cart-badge" class="absolute top-0.5 right-0.5 bg-rose-600 text-white text-[10px] font-bold h-4 w-4 rounded-full flex items-center justify-center hidden shadow-xs">0</span>
            </a>
        </div>
        <?php endif; ?>

        <!-- User Account / Login -->
        <?php if (\Core\Session::get('user_id')): ?>
            <?php 
                $userId = \Core\Session::get('user_id');
                $userName = \Core\Session::get('user_name');
                if (empty($userName) && $userId) {
                    $db = \Core\Database::getInstance();
                    $stmt = $db->prepare("SELECT name FROM users WHERE id = ?");
                    $stmt->execute([$userId]);
                    $userRow = $stmt->fetch();
                    if ($userRow && !empty($userRow['name'])) {
                        $userName = $userRow['name'];
                        \Core\Session::set('user_name', $userName);
                    } else {
                        $userName = 'User';
                    }
                }
                $initial = strtoupper(substr($userName, 0, 1));
            ?>
            <div class="relative group shrink-0 user-profile-dropdown-wrap">
                <?php 
                    $role = \Core\Session::get('user_role');
                    $isAdminRole = in_array($role, ['super_admin', 'manager', 'staff', 'vendor']);
                    $dashLink = $isAdminRole ? BASE_URL . '/admin/dashboard' : BASE_URL . '/dashboard'; 
                ?>
                <a href="<?= $dashLink ?>" class="flex items-center hover:opacity-90 transition-opacity focus:outline-none shrink-0 cursor-pointer" style="color: <?= htmlspecialchars($textColor) ?>;" title="Account Profile">
                    <div class="w-8 h-8 lg:w-9 lg:h-9 rounded-full flex items-center justify-center font-bold text-xs lg:text-sm shadow-sm ring-2 ring-white/40 transition-transform group-hover:scale-105" style="background-color: <?= htmlspecialchars($textColor) ?>; color: <?= htmlspecialchars($bgColorVal ?? '#ffffff') ?>;">
                        <?= $initial ?>
                    </div>
                </a>
                <!-- Dropdown Menu -->
                <div class="absolute right-0 top-full pt-2 w-56 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-[9999] origin-top-right">
                    <div class="bg-white border border-gray-100 rounded-2xl shadow-2xl p-2 text-gray-800 divide-y divide-gray-100">
                        <!-- User Info Header -->
                        <div class="px-3 py-2.5">
                            <p class="text-xs font-bold text-gray-900 truncate"><?= htmlspecialchars($userName) ?></p>
                            <p class="text-[10px] text-gray-400 font-medium capitalize mt-0.5">
                                <?= $role === 'super_admin' ? 'Super Admin' : ucfirst($role ?? 'Customer') ?>
                            </p>
                        </div>
                        
                        <!-- Menu Links -->
                        <div class="py-1 space-y-0.5">
                            <?php if ($role === 'vendor'): ?>
                                <a href="<?= BASE_URL ?>/admin/dashboard" class="flex items-center justify-between px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-pink-50 hover:text-[#F25996] rounded-lg transition-colors group/item">
                                    <span class="flex items-center gap-2.5">
                                        <svg class="w-4 h-4 text-[#F25996]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        <span>Vendor Portal</span>
                                    </span>
                                    <span class="bg-pink-100 text-[#F25996] text-[10px] px-2 py-0.5 rounded-full font-bold">Vendor</span>
                                </a>
                            <?php elseif ($isAdminRole): ?>
                                <a href="<?= BASE_URL ?>/admin/dashboard" class="flex items-center gap-2.5 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-pink-50 hover:text-[#F25996] rounded-lg transition-colors">
                                    <svg class="w-4 h-4 text-[#F25996]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                    <span>Admin Dashboard</span>
                                </a>
                            <?php else: ?>
                                <a href="<?= BASE_URL ?>/dashboard" class="flex items-center gap-2.5 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-pink-50 hover:text-[#F25996] rounded-lg transition-colors">
                                    <svg class="w-4 h-4 text-[#F25996]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    <span>My Account</span>
                                </a>
                                <a href="<?= BASE_URL ?>/dashboard/orders" class="flex items-center gap-2.5 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-pink-50 hover:text-[#F25996] rounded-lg transition-colors">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="w-4 h-4 text-[#F25996] shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    <span>My Orders</span>
                                </a>
                            <?php endif; ?>
                        </div>

                        <!-- Logout -->
                        <div class="pt-1">
                            <form action="<?= BASE_URL ?>/logout" method="POST" class="w-full">
                                <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-semibold text-[#F25996] hover:bg-pink-50 rounded-lg transition-colors cursor-pointer text-left">
                                    <svg class="w-4 h-4 text-[#F25996]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    <span>Sign Out</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="flex items-center space-x-2 ml-1 shrink-0">
                <a href="<?= BASE_URL ?>/login" class="inline-flex items-center px-3 py-1.5 rounded-lg transition-all duration-150 hover:bg-black/5 active:bg-black/10 shrink-0 font-bold" title="Log In" style="color: <?= htmlspecialchars($textColor) ?>; <?= $loginFontSizeCss ?>">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5 shrink-0" style="<?= $iconSvgStyle ?>">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="ml-1.5 font-bold uppercase tracking-wider whitespace-nowrap">LOG IN</span>
                </a>
            </div>
        <?php endif; ?>
    </div>
<?php
};
?>

<div style="<?= $bgInline ?> <?= $textInline ?>" class="w-full shadow-xs border-b border-black/5 z-40 relative">
    
    <!-- Desktop Layout (>= 1024px) -->
    <div class="hidden lg:flex w-full items-center justify-between px-6 xl:px-12" style="min-height: <?= $desktopMinHeight ?>px;">
        <?php if ($logoPos === 'Center'): ?>
            <div class="flex-1 flex justify-start items-center gap-4 xl:gap-6 min-w-0">
                <?php $renderItems($leftItems); ?>
            </div>
            
            <div class="shrink-0 flex justify-center items-center px-6 gap-4 xl:gap-6">
                <?php $renderLogo(); ?>
                <?php $renderItems($centerItems); ?>
            </div>
            
            <div class="flex-1 flex justify-end items-center gap-4 xl:gap-6 min-w-0">
                <?php $renderItems($rightItems); ?>
                <?php if ($showSystemIcons) $renderSystemIcons(); ?>
            </div>
        <?php elseif ($logoPos === 'Right'): ?>
            <div class="flex-1 flex justify-start items-center gap-4 xl:gap-6 min-w-0">
                <?php $renderItems($leftItems); ?>
            </div>
            
            <?php if (!empty($centerItems)): ?>
            <div class="shrink-0 flex justify-center items-center gap-3 xl:gap-6 px-4">
                <?php $renderItems($centerItems); ?>
            </div>
            <?php endif; ?>
            
            <div class="flex-1 flex justify-end items-center gap-4 xl:gap-6 min-w-0">
                <?php $renderItems($rightItems); ?>
                <?php if ($showSystemIcons) $renderSystemIcons(); ?>
                <div class="shrink-0 pl-4 xl:pr-2">
                    <?php $renderLogo(); ?>
                </div>
            </div>
        <?php else: ?>
            <!-- Default (Logo Left) -->
            <?php if (!empty($centerItems)): ?>
                <!-- When Center Items exist: 3-column balanced layout with perfect visual center -->
                <div class="flex-1 flex items-center justify-start gap-4 xl:gap-8 min-w-0">
                    <div class="shrink-0 pr-2 xl:pr-4">
                        <?php $renderLogo(); ?>
                    </div>
                    <?php if (!empty($leftItems)): ?>
                    <div class="flex items-center gap-2 xl:gap-4 flex-wrap">
                        <?php $renderItems($leftItems); ?>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="shrink-0 flex items-center justify-center gap-3 xl:gap-6 px-4">
                    <?php $renderItems($centerItems); ?>
                </div>
                
                <div class="flex-1 flex items-center justify-end gap-3 xl:gap-6 min-w-0">
                    <?php if (!empty($rightItems)): ?>
                    <div class="flex items-center gap-2 xl:gap-4 flex-wrap">
                        <?php $renderItems($rightItems); ?>
                    </div>
                    <?php endif; ?>
                    <?php if ($showSystemIcons) $renderSystemIcons(); ?>
                </div>
            <?php else: ?>
                <!-- When No Center Items: Natural Left-to-Right layout -->
                <div class="flex items-center gap-4 xl:gap-8 flex-1 min-w-0">
                    <div class="shrink-0 pr-4 xl:pr-6">
                        <?php $renderLogo(); ?>
                    </div>
                    <div class="flex items-center gap-2 xl:gap-4 flex-wrap">
                        <?php $renderItems($leftItems); ?>
                    </div>
                </div>
                
                <div class="flex items-center justify-end gap-3 xl:gap-6 shrink-0">
                    <div class="flex items-center gap-2 xl:gap-4">
                        <?php $renderItems($rightItems); ?>
                    </div>
                    <?php if ($showSystemIcons) $renderSystemIcons(); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    
    <!-- Mobile & Tablet Layout (< 1024px) -->
    <div class="flex lg:hidden w-full items-center justify-between h-14 sm:h-16 px-4 sm:px-6 md:px-8">
        <!-- Brand Logo -->
        <div class="shrink-0 flex items-center">
            <a href="<?= htmlspecialchars($logoCta) ?>" class="flex items-center">
                <?php if ($logo): ?>
                    <img src="<?= htmlspecialchars($logo) ?>" alt="Navbar Logo" class="h-8 sm:h-9 max-h-9 max-w-[140px] sm:max-w-[190px] md:max-w-[220px] object-contain shrink-0">
                <?php else: ?>
                    <span class="text-lg sm:text-xl font-black tracking-tight" style="color: <?= htmlspecialchars($textColor) ?>;">Brand Logo</span>
                <?php endif; ?>
            </a>
        </div>

        <!-- Tablet / Mobile Action Cluster -->
        <div class="flex items-center gap-1 sm:gap-2 md:gap-3 shrink-0">
            <?php if ($showSystemIcons): ?>
                <!-- Search Button -->
                <a href="javascript:void(0)" onclick="openSearchModal(event)" class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-xl hover:bg-black/5 active:bg-black/10 transition-colors cursor-pointer" style="color: <?= htmlspecialchars($textColor) ?>;" title="Search">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </a>

                <?php if (\Core\Session::get('user_id')): ?>
                    <!-- Wishlist Icon (Tablet & Mobile) -->
                    <a href="javascript:void(0)" onclick="openWishlistDrawer()" class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-xl hover:bg-black/5 active:bg-black/10 transition-colors relative" style="color: <?= htmlspecialchars($textColor) ?>;" title="Wishlist">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        <span id="tablet-wishlist-badge" class="absolute top-1 right-1 bg-rose-600 text-white text-[9px] font-bold h-4 w-4 rounded-full flex items-center justify-center hidden shadow-xs">0</span>
                    </a>

                    <!-- Cart Icon (Tablet & Mobile) -->
                    <a href="javascript:void(0)" onclick="openCartDrawer()" class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-xl hover:bg-black/5 active:bg-black/10 transition-colors relative" style="color: <?= htmlspecialchars($textColor) ?>;" title="Cart">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="w-5 h-5 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span id="tablet-cart-badge" class="absolute top-1 right-1 bg-rose-600 text-white text-[9px] font-bold h-4 w-4 rounded-full flex items-center justify-center hidden shadow-xs">0</span>
                    </a>

                    <!-- User Avatar -->
                    <?php 
                        $userId = \Core\Session::get('user_id');
                        $userName = \Core\Session::get('user_name') ?? 'User';
                        $initial = strtoupper(substr($userName, 0, 1));
                        $dashLink = in_array(\Core\Session::get('user_role'), ['super_admin', 'manager', 'staff', 'vendor']) ? BASE_URL . '/admin/dashboard' : BASE_URL . '/dashboard'; 
                    ?>
                    <a href="<?= $dashLink ?>" class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-xl hover:bg-black/5 active:bg-black/10 transition-colors" title="Account">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center font-bold text-xs shadow-sm ring-2 ring-white/30" style="background-color: <?= htmlspecialchars($textColor) ?>; color: <?= htmlspecialchars($bgColorVal ?? '#ffffff') ?>;">
                            <?= $initial ?>
                        </div>
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/login" class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-xl hover:bg-black/5 active:bg-black/10 transition-colors" style="color: <?= htmlspecialchars($textColor) ?>;" title="Log In">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </a>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Hamburger Toggle Button -->
            <button type="button" class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-xl hover:bg-black/5 active:bg-black/10 transition-colors shrink-0" id="mobileNavbarToggleBtn" aria-label="Toggle navigation menu" style="color: <?= htmlspecialchars($textColor) ?>;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </div>
    
    <!-- Mobile & Tablet Menu Dropdown -->
    <div class="hidden lg:hidden absolute top-full left-0 right-0 shadow-2xl border-t border-black/10 z-50 flex flex-col overflow-hidden rounded-b-2xl max-h-[80vh] overflow-y-auto" id="mobileNavbarMenu" style="<?= $bgInline ?>">
        <div class="py-2">
            <?php foreach ($menuItems as $item): ?>
                <?php if (!isset($item['active']) || $item['active']): ?>
                    <?php
                        $itemBg = !empty($item['bg_color']) && $item['bg_color'] !== '#ffffff' ? $item['bg_color'] : 'transparent';
                        $itemText = !empty($item['text_color']) ? $item['text_color'] : $textColor;
                        $hasDropdown = !empty($item['is_dropdown']);
                        $borderColor = strlen($textColor) === 7 ? $textColor . '25' : $textColor;
                    ?>
                    <a href="<?= htmlspecialchars($item['cta_url'] ?? '#') ?>" class="block px-6 py-3.5 font-bold text-sm transition-colors border-b last:border-b-0 hover:opacity-80" style="color: <?= htmlspecialchars($itemText) ?>; background-color: <?= htmlspecialchars($itemBg) ?>; border-color: <?= htmlspecialchars($borderColor) ?>;">
                        <div class="flex items-center justify-between">
                            <span><?= htmlspecialchars($item['title'] ?? '') ?></span>
                            <?php if ($hasDropdown): ?>
                                <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
            
            <?php if (\Core\Session::get('user_id')): ?>
                <div class="flex flex-col border-t" style="border-color: <?= htmlspecialchars($borderColor ?? ($textColor.'25')) ?>;">
                    <a href="javascript:void(0)" onclick="openWishlistDrawer(); document.getElementById('mobileNavbarMenu').classList.add('hidden');" class="flex items-center justify-between px-6 py-3.5 font-bold text-sm transition-colors border-b" style="color: <?= htmlspecialchars($textColor) ?>; border-color: <?= htmlspecialchars($borderColor ?? ($textColor.'25')) ?>;">
                        <span class="flex items-center gap-3">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            My Wishlist
                        </span>
                        <span id="drawer-wishlist-badge" class="bg-rose-600 text-white text-xs font-bold px-2 py-0.5 rounded-full hidden">0</span>
                    </a>
                    <a href="javascript:void(0)" onclick="openCartDrawer(); document.getElementById('mobileNavbarMenu').classList.add('hidden');" class="flex items-center justify-between px-6 py-3.5 font-bold text-sm transition-colors border-b" style="color: <?= htmlspecialchars($textColor) ?>; border-color: <?= htmlspecialchars($borderColor ?? ($textColor.'25')) ?>;">
                        <span class="flex items-center gap-3">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Shopping Cart
                        </span>
                        <span id="drawer-cart-badge" class="bg-rose-600 text-white text-xs font-bold px-2 py-0.5 rounded-full hidden">0</span>
                    </a>
                    <!-- Logout -->
                    <form action="<?= BASE_URL ?>/logout" method="POST">
                        <button type="submit" class="flex w-full items-center gap-3 px-6 py-3.5 font-bold text-sm transition-colors hover:bg-black/5" style="color: <?= htmlspecialchars($textColor) ?>;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="w-5 h-5" style="color: <?= htmlspecialchars($textColor) ?>;"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Logout
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileBtn = document.getElementById('mobileNavbarToggleBtn');
    const mobileMenu = document.getElementById('mobileNavbarMenu');
    if (mobileBtn && mobileMenu) {
        mobileBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            mobileMenu.classList.toggle('hidden');
        });
        
        document.addEventListener('click', function(e) {
            if (!mobileMenu.classList.contains('hidden') && !mobileMenu.contains(e.target) && !mobileBtn.contains(e.target)) {
                mobileMenu.classList.add('hidden');
            }
        });
    }

    // Sync all badge elements
    const syncBadges = (sourceId, targetIds) => {
        const source = document.getElementById(sourceId);
        if (!source) return;

        const update = () => {
            const val = source.textContent;
            const isHidden = source.classList.contains('hidden') || val === '0' || !val;
            targetIds.forEach(id => {
                const target = document.getElementById(id);
                if (target) {
                    target.textContent = val;
                    if (isHidden) {
                        target.classList.add('hidden');
                    } else {
                        target.classList.remove('hidden');
                    }
                }
            });
        };

        const observer = new MutationObserver(update);
        observer.observe(source, { childList: true, attributes: true, characterData: true, subtree: true });
        update();
    };

    syncBadges('wishlist-badge', ['tablet-wishlist-badge', 'drawer-wishlist-badge']);
    syncBadges('cart-badge', ['tablet-cart-badge', 'drawer-cart-badge']);
});
</script>
