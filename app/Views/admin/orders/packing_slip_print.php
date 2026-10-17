<?php
/**
 * 100mm × 100mm Thermal Packing Slip (Single-Page Strict Format)
 *
 * @var array $orders
 * @var array $itemsByOrder
 * @var array $config
 * @var string $cmsLogo
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packing Slips (100mm × 100mm) — <?= count($orders) ?> Orders</title>
    
    <script>
        (function() {
            var _w = console.warn;
            console.warn = function() {
                if (arguments[0] && typeof arguments[0] === 'string' && arguments[0].indexOf('cdn.tailwindcss.com') !== -1) return;
                _w.apply(console, arguments);
            };
        })();
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <style>
        @page {
            size: 100mm 100mm;
            margin: 0;
        }

        *, *::before, *::after {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background: #e5e7eb;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #000000;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* 100mm x 100mm Strict Thermal Page */
        .slip-page {
            width: 100mm;
            height: 100mm;
            max-width: 100mm;
            max-height: 100mm;
            background: #ffffff;
            padding: 4mm 4.5mm;
            box-sizing: border-box;
            position: relative;
            overflow: hidden;
            page-break-after: always;
            break-after: page;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            font-size: 8px;
            line-height: 1.25;
            margin: 0 auto 16px auto;
            color: #000000;
        }
        .slip-page * {
            -webkit-font-smoothing: antialiased;
        }

        @media print {
            body {
                background: #ffffff !important;
            }
            .slip-page {
                box-shadow: none !important;
                margin: 0 !important;
                border: none !important;
                page-break-after: always !important;
                break-after: page !important;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Non-Print Floating Toolbar -->
    <div class="no-print sticky top-0 z-50 bg-gray-900 text-white px-6 py-3.5 shadow-xl flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="w-8 h-8 rounded-lg bg-pink-600 flex items-center justify-center font-bold text-sm">🖨️</span>
            <div>
                <h1 class="text-sm font-bold tracking-tight">Packing Slips Ready to Print</h1>
                <p class="text-[11px] text-gray-400">Total <?= count($orders) ?> order slips • Thermal Size: <strong>100mm × 100mm (Single Page)</strong></p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <!-- 1-Click Direct USB Print Button (Uses Saved Configuration) -->
            <button type="button" id="directUsbPrintBtn" onclick="startDirectPrint()" class="px-3.5 py-1.5 text-xs font-bold bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg shadow-sm transition-all flex items-center gap-1.5 ring-1 ring-emerald-400/40 cursor-pointer active:scale-95" title="Direct 1-click raw TSPL print to <?= htmlspecialchars($config['tspl_printer'] ?? 'TSC TE244') ?> via USB">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span id="directUsbBtnText">⚡ Direct USB (<?= htmlspecialchars($config['tspl_printer'] ?? 'TSC TE244') ?>)</span>
            </button>
            <a href="<?= BASE_URL ?>/admin/orders/packing-slip-config#printer-settings" target="_blank" class="px-2 py-1.5 text-xs text-gray-400 hover:text-white bg-gray-800 hover:bg-gray-700 rounded-lg transition-colors border border-gray-700 flex items-center gap-1 shrink-0" title="Configure Printer Darkness, Speed & Modes">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </a>
            <button type="button" id="toggleEditModeBtn" onclick="toggleEditMode()" class="px-3.5 py-1.5 text-xs font-semibold bg-gray-800 hover:bg-gray-700 text-amber-300 border border-amber-500/30 rounded-lg transition-colors flex items-center gap-1.5" title="Click to directly edit text on packing slips before printing">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                <span id="editBtnText">✏️ Enable Edit Mode</span>
            </button>
            <button onclick="window.close()" class="px-3.5 py-1.5 text-xs font-semibold bg-gray-800 hover:bg-gray-700 text-gray-300 rounded-lg transition-colors">
                Close
            </button>
            <button onclick="window.print()" class="px-5 py-2 text-xs font-bold bg-[#F25996] hover:bg-[#e04481] text-white rounded-lg shadow-sm transition-all flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Print All Slips</span>
            </button>
        </div>
    </div>

    <!-- Direct Print Status Notification Banner (shown during/after printing) -->
    <div id="directPrintStatusBanner" class="no-print hidden sticky top-14 z-40 bg-emerald-950/95 text-emerald-200 border-b border-emerald-500/40 px-6 py-2.5 backdrop-blur-md flex items-center justify-between text-xs font-mono shadow-md">
        <div class="flex items-center gap-2.5">
            <span id="bannerSpinner" class="w-3.5 h-3.5 border-2 border-emerald-400 border-t-transparent rounded-full animate-spin"></span>
            <span id="bannerStatusText">Printing directly to <?= htmlspecialchars($config['tspl_printer'] ?? 'TSC TE244') ?>...</span>
        </div>
        <div class="flex items-center gap-3">
            <span id="bannerPercent" class="font-bold text-emerald-400">0%</span>
            <button type="button" onclick="document.getElementById('directPrintStatusBanner').classList.add('hidden')" class="text-emerald-400 hover:text-white text-sm font-bold">✕</button>
        </div>
    </div>

    <!-- Slips Container -->
    <div class="p-6 print:p-0">
        <?php foreach ($orders as $order): ?>
            <?php
                $orderId = $order['id'];
                $items = $itemsByOrder[$orderId] ?? [];
                $orderNumber = $order['order_number'] ?? ('#' . $orderId);
                $orderDate = !empty($order['created_at']) ? date('d M Y, H:i', strtotime($order['created_at'])) : date('d M Y');
                $customerName = !empty($order['user_name']) ? $order['user_name'] : (!empty($order['customer_name']) ? $order['customer_name'] : 'Customer');
                $rawShippingAddress = !empty($order['shipping_address']) ? $order['shipping_address'] : 'No shipping address specified';
                // Remove [Shop Address] or any [Address Label] bracketed tags from address
                $shippingAddress = trim(preg_replace('/^\s*\[.*?\]\s*[\r\n]*/m', '', $rawShippingAddress));
                $shippingAddress = trim(preg_replace('/\s*\[.*?Address\]\s*/i', '', $shippingAddress));
                $shippingAddress = trim(preg_replace('/\s*\[.*?\]\s*/', '', $shippingAddress));
                $grandTotal = (float)($order['grand_total'] ?? $order['total_amount'] ?? 0);
                $isPaid = ($order['payment_status'] ?? '') === 'paid';
                $isCod = (strpos(strtolower($order['payment_status'] ?? ''), 'cod') !== false) || (!empty($order['balance_amount']) && (float)$order['balance_amount'] > 0);

                $sectionsOrder = $config['section_order'] ?? ['header', 'meta', 'addresses', 'notes'];
                $logoUrl = !empty($config['show_logo']) ? ($config['logo_url'] ?: $cmsLogo) : '';
                $showGstin = !empty($config['show_gstin']) && !empty($config['gstin']);
                $showNotes = !empty($config['show_notes']) && !empty($config['notes']);
            ?>

            <div class="slip-page shadow-md border border-gray-300 print:shadow-none print:border-none relative overflow-hidden bg-white"
                 data-order-number="<?= htmlspecialchars($orderNumber) ?>"
                 data-order-date="<?= htmlspecialchars($orderDate) ?>"
                 data-customer-name="<?= htmlspecialchars($customerName) ?>"
                 data-customer-address="<?= htmlspecialchars(str_replace(["\r\n", "\r", "\n"], ", ", $shippingAddress)) ?>"
                 data-customer-phone="<?= htmlspecialchars($customerPhone) ?>"
                 data-payment-status="<?= $isPaid ? 'PAID' : 'PENDING' ?>"
                 data-company-name="<?= htmlspecialchars($config['company_name']) ?>"
                 data-company-address="<?= htmlspecialchars(str_replace(["\r\n", "\r", "\n"], ", ", $config['address'])) ?>"
                 data-company-phone="<?= htmlspecialchars($config['phone']) ?>"
                 data-company-gstin="<?= htmlspecialchars($config['gstin']) ?>"
                 data-subtitle="<?= htmlspecialchars($config['subtitle'] ?? 'FORWARDING WITH CARE') ?>"
                 data-notes="<?= htmlspecialchars($config['notes'] ?? '') ?>">
                
                <!-- Top-Right Geometric Corner Accents -->
                <svg class="absolute top-0 right-0 w-20 h-14 pointer-events-none z-0" viewBox="0 0 100 65" preserveAspectRatio="none">
                    <polygon points="50,0 78,0 100,22 100,0" fill="#4b5563" />
                    <polygon points="72,0 100,0 100,48 40,0" fill="#000000" />
                </svg>

                <!-- Bottom-Right Geometric Corner Accents -->
                <svg class="absolute bottom-0 right-0 w-28 h-18 pointer-events-none z-0" viewBox="0 0 140 85" preserveAspectRatio="none">
                    <polygon points="140,42 140,85 70,85" fill="#4b5563" />
                    <polygon points="140,20 140,85 35,85" fill="#1f2937" />
                    <polygon points="140,0 140,85 0,85" fill="#000000" />
                </svg>

                <!-- Background Subtle Watermark -->
                <?php if (!empty($logoUrl)): ?>
                    <img src="<?= htmlspecialchars($logoUrl) ?>" class="absolute bottom-4 right-4 w-44 h-44 object-contain opacity-[0.06] pointer-events-none select-none z-0" alt="Watermark">
                <?php else: ?>
                    <div class="absolute bottom-6 right-6 font-serif font-black text-5xl tracking-widest text-black opacity-[0.04] pointer-events-none select-none z-0">
                        <?= htmlspecialchars($config['company_name']) ?>
                    </div>
                <?php endif; ?>

                <!-- Relative Foreground Content Wrapper -->
                <div class="relative z-10 flex flex-col justify-between h-full">

                    <?php foreach ($sectionsOrder as $secKey): ?>
                        
                        <?php if ($secKey === 'header'): ?>
                        <!-- Section: Header (Big CMS Logo on Top, Company Name & GSTIN Below Logo, Order Details on Right) -->
                        <div class="flex items-center justify-between gap-3 pb-2 border-b-2 border-black">
                            <!-- Left: Big Logo with Company Name & GSTIN below -->
                            <div class="flex flex-col items-start gap-0.5 min-w-0">
                                <?php if (!empty($logoUrl)): ?>
                                    <img src="<?= htmlspecialchars($logoUrl) ?>" alt="Logo" class="h-14 w-auto max-w-[150px] object-contain mb-1">
                                <?php else: ?>
                                    <div class="h-12 w-20 rounded-lg bg-black text-white flex items-center justify-center font-black text-sm tracking-wider mb-1">
                                        <?= strtoupper(substr($config['company_name'], 0, 3)) ?>
                                    </div>
                                <?php endif; ?>
                                <h2 class="font-serif font-black text-[14px] uppercase tracking-wide text-black leading-tight">
                                    <?= htmlspecialchars($config['company_name']) ?>
                                </h2>
                                <?php if ($showGstin): ?>
                                    <div class="text-[9.5px] font-mono font-bold text-black mt-0.5 tracking-wider">
                                        GSTIN: <span class="font-black"><?= htmlspecialchars($config['gstin']) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Right: Order ID, Date, Payment Status (Replaces PACKING SLIP & FORWARDING WITH CARE) -->
                            <div class="border-l-2 border-black pl-3 shrink-0 flex flex-col justify-center gap-1.5 min-w-[155px]">
                                <!-- Order No -->
                                <div class="flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-1 text-[8.5px] font-bold text-gray-700 uppercase tracking-wider">
                                        <svg class="w-3.5 h-3.5 text-black shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>ORDER NO:</span>
                                    </div>
                                    <span class="text-[12px] font-black font-mono text-black leading-none"><?= htmlspecialchars($orderNumber) ?></span>
                                </div>

                                <!-- Order Date -->
                                <div class="flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-1 text-[8.5px] font-bold text-gray-700 uppercase tracking-wider">
                                        <svg class="w-3.5 h-3.5 text-black shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>DATE:</span>
                                    </div>
                                    <span class="text-[10px] font-black text-black leading-none"><?= date('d M Y', strtotime($order['created_at'] ?? 'now')) ?></span>
                                </div>

                                <!-- Payment Status -->
                                <div class="flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-1 text-[8.5px] font-bold text-gray-700 uppercase tracking-wider">
                                        <svg class="w-3.5 h-3.5 text-black shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                        <span>PAYMENT:</span>
                                    </div>
                                    <div class="text-[10.5px] font-black text-black leading-none">
                                        <?php if ($isPaid): ?>
                                            PAID <span class="text-[9px] font-bold text-gray-700">(Online)</span>
                                        <?php elseif ($isCod): ?>
                                            COD
                                        <?php else: ?>
                                            PENDING
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php elseif ($secKey === 'meta'): ?>
                        <!-- Separate Order Meta Bar removed (integrated into Header above per user request) -->
                        <?php continue; ?>

                        <?php elseif ($secKey === 'addresses'): ?>
                        <!-- Section: Addresses (From & To Cards, Bigger Fonts) -->
                        <div class="grid grid-cols-2 gap-2.5 my-2">
                            <!-- SHIP FROM Card (Styled to match SHIP TO) -->
                            <div class="border-2 border-black rounded-xl overflow-hidden bg-white shadow-none">
                                <div class="bg-gray-100 px-3 py-1.5 border-b-2 border-black flex items-center gap-1.5">
                                    <span class="w-4 h-4 rounded-md bg-black text-white flex items-center justify-center shrink-0">
                                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                                    </span>
                                    <span class="text-[10px] font-black uppercase text-black tracking-wider">SHIP FROM</span>
                                </div>
                                <div class="p-2.5 flex flex-col gap-0.5">
                                    <div class="font-black text-[11px] text-black leading-tight tracking-tight"><?= htmlspecialchars($config['company_name']) ?></div>
                                    <div class="text-[8px] text-black font-medium leading-tight"><?= nl2br(htmlspecialchars($config['address'])) ?></div>
                                    <?php if (!empty($config['phone'])): ?>
                                        <div class="text-[8px] text-black font-bold mt-0.5">Ph: <?= htmlspecialchars($config['phone']) ?></div>
                                    <?php endif; ?>
                                    <?php if (!empty($config['email'])): ?>
                                        <div class="text-[8px] text-black font-bold">Email: <?= htmlspecialchars($config['email']) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- SHIP TO Card (Big font size) -->
                            <div class="border-2 border-black rounded-xl overflow-hidden bg-white shadow-none">
                                <div class="bg-gray-100 px-3 py-1.5 border-b-2 border-black flex items-center gap-1.5">
                                    <span class="w-4 h-4 rounded-md bg-black text-white flex items-center justify-center shrink-0">
                                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                                    </span>
                                    <span class="text-[10px] font-black uppercase text-black tracking-wider">SHIP TO</span>
                                </div>
                                <div class="p-2.5 flex flex-col gap-1">
                                    <div class="font-black text-[15px] text-black leading-tight tracking-tight"><?= htmlspecialchars($customerName) ?></div>
                                    <div class="text-[11px] text-black font-bold leading-snug"><?= nl2br(htmlspecialchars($shippingAddress)) ?></div>
                                    <?php if (!empty($customerPhone)): ?>
                                        <div class="text-[12px] text-black font-black mt-1">Ph: <?= htmlspecialchars($customerPhone) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <?php elseif ($secKey === 'notes'): ?>
                        <!-- Section: Declaration / Notes (Bigger Fonts) -->
                        <?php if ($showNotes): ?>
                        <div class="border border-gray-400 rounded-xl p-2 bg-white flex items-start gap-2 mt-auto relative z-10">
                            <div class="w-4 h-4 rounded-full bg-black text-white flex items-center justify-center font-bold text-[9px] shrink-0 mt-0.5">
                                i
                            </div>
                            <div class="text-[8.5px] text-black font-medium leading-tight">
                                <?= htmlspecialchars($config['notes']) ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php endif; ?>

                    <?php endforeach; ?>

                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        // Direct USB Hardware Configuration (Configured under /admin/orders/packing-slip-config)
        const savedPrinterConfig = {
            printerName: <?= json_encode($config['tspl_printer'] ?? 'TSC TE244') ?>,
            mode: <?= json_encode($config['tspl_mode'] ?? 'bitmap') ?>,
            speed: <?= json_encode((string)($config['tspl_speed'] ?? 4)) ?>,
            density: <?= json_encode((string)($config['tspl_density'] ?? 7)) ?>,
            threshold: <?= json_encode((int)($config['tspl_threshold'] ?? 165)) ?>,
            invertPolarity: <?= !empty($config['tspl_invert']) ? 'true' : 'false' ?>
        };

        // Edit Mode Toggle
        let isEditMode = false;
        function toggleEditMode() {
            isEditMode = !isEditMode;
            const btn = document.getElementById('toggleEditModeBtn');
            const txt = document.getElementById('editBtnText');
            const slips = document.querySelectorAll('.slip-page');

            if (isEditMode) {
                btn.className = 'px-3.5 py-1.5 text-xs font-bold bg-amber-500 text-gray-950 rounded-lg shadow-sm transition-colors flex items-center gap-1.5 ring-2 ring-amber-300';
                txt.textContent = '✏️ Edit Mode Active (Click any text to edit)';
                slips.forEach(slip => {
                    slip.contentEditable = "true";
                    slip.style.outline = "2px dashed #f59e0b";
                    slip.style.cursor = "text";
                });
            } else {
                btn.className = 'px-3.5 py-1.5 text-xs font-semibold bg-gray-800 hover:bg-gray-700 text-amber-300 border border-amber-500/30 rounded-lg transition-colors flex items-center gap-1.5';
                txt.textContent = '✏️ Enable Edit Mode';
                slips.forEach(slip => {
                    slip.contentEditable = "false";
                    slip.style.outline = "none";
                    slip.style.cursor = "default";
                });
            }
        }

        function updateProgress(msg, pct, isError = false) {
            const banner = document.getElementById('directPrintStatusBanner');
            const statusTxt = document.getElementById('bannerStatusText');
            const pctTxt = document.getElementById('bannerPercent');
            const spinner = document.getElementById('bannerSpinner');
            
            if (banner) {
                banner.classList.remove('hidden');
                if (isError) {
                    banner.className = 'no-print sticky top-14 z-40 bg-rose-950/95 text-rose-200 border-b border-rose-500/50 px-6 py-2.5 backdrop-blur-md flex items-center justify-between text-xs font-mono shadow-md';
                    if (spinner) spinner.className = 'hidden';
                } else if (pct >= 100) {
                    banner.className = 'no-print sticky top-14 z-40 bg-emerald-950/95 text-emerald-200 border-b border-emerald-500/50 px-6 py-2.5 backdrop-blur-md flex items-center justify-between text-xs font-mono shadow-md';
                    if (spinner) {
                        spinner.className = 'text-emerald-400 font-bold';
                        spinner.textContent = '✓';
                    }
                } else {
                    banner.className = 'no-print sticky top-14 z-40 bg-gray-900/95 text-emerald-300 border-b border-emerald-500/40 px-6 py-2.5 backdrop-blur-md flex items-center justify-between text-xs font-mono shadow-md';
                    if (spinner) spinner.className = 'w-3.5 h-3.5 border-2 border-emerald-400 border-t-transparent rounded-full animate-spin';
                }
            }
            if (statusTxt) statusTxt.textContent = msg;
            if (pctTxt) pctTxt.textContent = Math.round(pct) + '%';
        }

        function escapeHtmlSafe(str) {
            if (!str) return '';
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }

        function uint8ToBase64(bytes) {
            let binary = '';
            const len = bytes.byteLength;
            for (let i = 0; i < len; i++) {
                binary += String.fromCharCode(bytes[i]);
            }
            return window.btoa(binary);
        }

        async function sendRawToPrinter(dataBuffer, targetPrinter = null) {
            const printerName = targetPrinter || savedPrinterConfig.printerName || 'TSC TE244';
            const formData = new FormData();
            formData.append('printer_name', printerName);
            formData.append('raw_base64', uint8ToBase64(dataBuffer));

            const res = await fetch('<?= BASE_URL ?>/admin/orders/packing-slips/direct-print', {
                method: 'POST',
                body: formData
            });

            if (!res.ok) {
                const text = await res.text();
                throw new Error('Print server error (' + res.status + '): ' + text);
            }

            const data = await res.json();
            if (!data.success) {
                throw new Error(data.message || 'Printer communication error');
            }
            return data;
        }

        async function sendDiagnosticCommand(cmdString) {
            try {
                updateProgress('Sending command to printer: ' + cmdString.trim() + '...', 50);
                const encoder = new TextEncoder();
                await sendRawToPrinter(encoder.encode(cmdString));
                updateProgress('Command sent successfully to TSC TE244!', 100);
            } catch (err) {
                updateProgress('Diagnostic failed: ' + err.message, 0, true);
                alert('Printer error: ' + err.message);
            }
        }

        // Build Native Pure Vector TSPL for a slip page
        function buildVectorTspl(slipEl, speed, density) {
            const d = slipEl.dataset;
            const orderNo    = d.orderNumber || 'GB00001';
            const orderDate  = d.orderDate || '';
            const status     = d.paymentStatus || 'PAID';
            const compName   = (d.companyName || 'GRM GARMENTS').toUpperCase();
            const compAddr   = (d.companyAddress || '').replace(/[\r\n]+/g, ' ');
            const compPhone  = d.companyPhone ? ('Ph: ' + d.companyPhone) : '';
            const gstin      = d.companyGstin ? ('GSTIN: ' + d.companyGstin) : '';
            const subtitle   = d.subtitle || 'FORWARDING WITH CARE';
            const custName   = d.customerName || 'Customer';
            const custAddr   = (d.customerAddress || '').replace(/[\r\n]+/g, ' ');
            const custPhone  = d.customerPhone ? ('Ph: ' + d.customerPhone) : '';
            const notes      = (d.notes || 'Declaration: Actual items described').replace(/[\r\n]+/g, ' ');

            let tspl = `SIZE 100 mm, 100 mm\r\n`;
            tspl += `GAP 3 mm, 0 mm\r\n`;
            tspl += `SPEED ${speed}\r\n`;
            tspl += `DENSITY ${density}\r\n`;
            tspl += `DIRECTION 1\r\n`;
            tspl += `REFERENCE 0,0\r\n`;
            tspl += `CLS\r\n`;

            // Top Header: Company Name & GSTIN on Left, Order Details on Right (Replacing PACKING SLIP & FORWARDING WITH CARE)
            tspl += `TEXT 30,30,"3",0,2,2,"${compName.substring(0, 20)}"\r\n`;
            if (gstin) {
                tspl += `TEXT 30,80,"2",0,1,1,"${gstin}"\r\n`;
            }
            // Vertical divider bar
            tspl += `BAR 470,25,2,90\r\n`;
            // Order No, Date, Status on Right
            tspl += `TEXT 485,25,"2",0,1,1,"ORDER: ${orderNo}"\r\n`;
            tspl += `TEXT 485,55,"2",0,1,1,"DATE:  ${orderDate.substring(0, 11)}"\r\n`;
            tspl += `TEXT 485,85,"2",0,1,1,"PAY:   ${status}"\r\n`;
            tspl += `BAR 30,125,740,2\r\n`;

            // Addresses (Shifted up since separate meta bar is removed)
            // SHIP FROM Box (Small font size)
            tspl += `BOX 30,140,385,420,2\r\n`;
            tspl += `TEXT 45,150,"1",0,1,1,"SHIP FROM:"\r\n`;
            tspl += `BAR 45,168,320,1\r\n`;
            tspl += `TEXT 45,178,"2",0,1,1,"${compName.substring(0, 25)}"\r\n`;
            tspl += `TEXT 45,206,"1",0,1,1,"${compAddr.substring(0, 38)}"\r\n`;
            if (compAddr.length > 38) {
                tspl += `TEXT 45,226,"1",0,1,1,"${compAddr.substring(38, 76)}"\r\n`;
            }
            if (compPhone) {
                tspl += `TEXT 45,254,"1",0,1,1,"${compPhone}"\r\n`;
            }

            // Clean customer address to remove [Shop Address] or bracketed labels
            const cleanCustAddr = custAddr.replace(/\[.*?\]/g, '').replace(/Shop Address/gi, '').trim();

            // SHIP TO Box (Big font size)
            tspl += `BOX 405,140,770,420,2\r\n`;
            tspl += `TEXT 420,150,"2",0,1,1,"SHIP TO:"\r\n`;
            tspl += `BAR 420,170,330,1\r\n`;
            tspl += `TEXT 420,180,"3",0,2,2,"${custName.substring(0, 16)}"\r\n`;
            tspl += `TEXT 420,225,"2",0,1,1,"${cleanCustAddr.substring(0, 30)}"\r\n`;
            if (cleanCustAddr.length > 30) {
                tspl += `TEXT 420,250,"2",0,1,1,"${cleanCustAddr.substring(30, 60)}"\r\n`;
            }
            if (custPhone) {
                tspl += `TEXT 420,285,"3",0,1,1,"${custPhone}"\r\n`;
            }

            // Barcode (Code 128)
            tspl += `BARCODE 260,440,"128",85,1,0,2,4,"${orderNo}"\r\n`;

            // Footer Declaration
            tspl += `BOX 30,550,770,625,1\r\n`;
            tspl += `TEXT 45,570,"2",0,1,1,"${notes.substring(0, 75)}"\r\n`;

            tspl += `PRINT 1,1\r\n`;
            return new TextEncoder().encode(tspl);
        }

        // Build High-Resolution 203 DPI Raster TSPL Bitmap
        async function buildBitmapTspl(slipEl, speed, density, threshold = null, invertPolarity = null) {
            if (typeof html2canvas === 'undefined') {
                throw new Error('html2canvas library is loading... please wait a few seconds and try again.');
            }

            // High-resolution capture at 3x scale for ultra-sharp font definition
            const renderedCanvas = await html2canvas(slipEl, {
                scale: 3,
                backgroundColor: '#ffffff',
                logging: false,
                useCORS: true
            });

            // Rescale cleanly to exact 800 dots x 800 dots with 2mm safety margins so no content is cut off
            const targetCanvas = document.createElement('canvas');
            targetCanvas.width = 800;
            targetCanvas.height = 800;
            const ctx = targetCanvas.getContext('2d', { willReadFrequently: true });
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, 800, 800);
            ctx.imageSmoothingEnabled = true;
            ctx.imageSmoothingQuality = 'high';
            // Printable area: 16 dots margin left/right, 14 dots margin top/bottom
            ctx.drawImage(renderedCanvas, 16, 14, 768, 770);

            const imgData = ctx.getImageData(0, 0, 800, 800);
            const pixels = imgData.data;
            const widthBytes = 100; // 800 dots / 8 bits
            const heightDots = 800;
            const rawBytes = new Uint8Array(widthBytes * heightDots);

            // Dynamic threshold: read from argument or saved configuration (default: 165 for solid text strokes)
            const th = (threshold !== null && threshold !== undefined) ? threshold : (savedPrinterConfig.threshold || 165);
            const inv = (invertPolarity !== null && invertPolarity !== undefined) ? invertPolarity : (!!savedPrinterConfig.invertPolarity);

            for (let y = 0; y < heightDots; y++) {
                for (let byteX = 0; byteX < widthBytes; byteX++) {
                    // For TSC TE244 BITMAP command:
                    // 1 bit = White paper (Heater OFF)
                    // 0 bit = Black ink (Heater ON)
                    // Default byte is 0xFF (all white paper)
                    let byteVal = 0xFF;
                    for (let bit = 0; bit < 8; bit++) {
                        const x = byteX * 8 + bit;
                        const idx = (y * 800 + x) * 4;
                        const r = pixels[idx];
                        const g = pixels[idx + 1];
                        const b = pixels[idx + 2];
                        const a = pixels[idx + 3];

                        // If not transparent and darker than threshold: burn black dot (clear bit to 0)
                        if (a > 60) {
                            const lum = 0.299 * r + 0.587 * g + 0.114 * b;
                            if (lum < th) {
                                byteVal &= ~(1 << (7 - bit));
                            }
                        }
                    }

                    // If user requested inverted polarity
                    if (inv) {
                        byteVal = (~byteVal) & 0xFF;
                    }

                    rawBytes[y * widthBytes + byteX] = byteVal;
                }
            }

            const headerStr = `SIZE 100 mm, 100 mm\r\nGAP 3 mm, 0 mm\r\nSPEED ${speed}\r\nDENSITY ${density}\r\nDIRECTION 1\r\nREFERENCE 0,0\r\nCLS\r\nBITMAP 0,0,100,800,0,`;
            const footerStr = `\r\nPRINT 1,1\r\n`;

            const encoder = new TextEncoder();
            const headerBytes = encoder.encode(headerStr);
            const footerBytes = encoder.encode(footerStr);

            const combined = new Uint8Array(headerBytes.length + rawBytes.length + footerBytes.length);
            combined.set(headerBytes, 0);
            combined.set(rawBytes, headerBytes.length);
            combined.set(footerBytes, headerBytes.length + rawBytes.length);

            return combined;
        }

        // Main Execution: Print All Slips Directly (1-Click Instant)
        async function startDirectPrint() {
            const slips = document.querySelectorAll('.slip-page');
            if (!slips.length) {
                alert('No packing slips found to print.');
                return;
            }

            const btn = document.getElementById('directUsbPrintBtn');
            const btnText = document.getElementById('directUsbBtnText');
            if (btn) btn.disabled = true;
            if (btnText) btnText.textContent = 'Printing...';

            const printerName = savedPrinterConfig.printerName || 'TSC TE244';
            const mode = savedPrinterConfig.mode || 'bitmap';
            const speed = savedPrinterConfig.speed || '4';
            const density = savedPrinterConfig.density || '7';
            const threshold = savedPrinterConfig.threshold || 165;
            const invertPolarity = !!savedPrinterConfig.invertPolarity;

            try {
                updateProgress(`Connecting directly to ${printerName}...`, 5);

                const total = slips.length;
                for (let i = 0; i < total; i++) {
                    const slipEl = slips[i];
                    const orderNo = slipEl.dataset.orderNumber || ('#' + (i + 1));
                    const basePct = (i / total) * 100;
                    updateProgress(`Rendering slip ${i + 1} of ${total} (${orderNo})...`, basePct + 5);

                    let packetBuffer;
                    if (mode === 'vector') {
                        packetBuffer = buildVectorTspl(slipEl, speed, density);
                    } else {
                        packetBuffer = await buildBitmapTspl(slipEl, speed, density, threshold, invertPolarity);
                    }

                    updateProgress(`Printing slip ${i + 1} on ${printerName}...`, basePct + 15);
                    await sendRawToPrinter(packetBuffer, printerName);

                    // pause between labels for thermal print cycle
                    await new Promise(r => setTimeout(r, 100));
                }

                updateProgress(`Done! Successfully printed all ${total} packing slips on ${printerName}.`, 100);

            } catch (err) {
                console.error(err);
                updateProgress('Error: ' + err.message, 0, true);
                alert('Direct print error: ' + err.message + '\n\nMake sure your ' + printerName + ' is turned ON and USB cable is connected.');
            } finally {
                if (btn) btn.disabled = false;
                if (btnText) btnText.textContent = '⚡ Direct USB (' + printerName + ')';
            }
        }

        // Download raw .PRN TSPL file
        async function downloadTsplFile() {
            const slips = document.querySelectorAll('.slip-page');
            if (!slips.length) return;

            const mode = savedPrinterConfig.mode || 'bitmap';
            const speed = savedPrinterConfig.speed || '4';
            const density = savedPrinterConfig.density || '7';
            const threshold = savedPrinterConfig.threshold || 165;
            const invertPolarity = !!savedPrinterConfig.invertPolarity;

            updateProgress('Generating .PRN file...', 20);
            const packets = [];
            for (let i = 0; i < slips.length; i++) {
                if (mode === 'vector') {
                    packets.push(buildVectorTspl(slips[i], speed, density));
                } else {
                    packets.push(await buildBitmapTspl(slips[i], speed, density, threshold, invertPolarity));
                }
            }

            const totalLen = packets.reduce((acc, p) => acc + p.length, 0);
            const merged = new Uint8Array(totalLen);
            let offset = 0;
            for (const p of packets) {
                merged.set(p, offset);
                offset += p.length;
            }

            const blob = new Blob([merged], { type: 'application/octet-stream' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `TSC_TE244_PackingSlips_${Date.now()}.prn`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            updateProgress('.PRN file downloaded successfully!', 100);
        }
    </script>

    <?php if (!empty($_GET['auto_print'])): ?>
    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 350);
        });
    </script>
    <?php endif; ?>

</body>
</html>
