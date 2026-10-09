<?php
/**
 * Admin — Print Order Slips
 *
 * @var array $orders
 * @var array $itemsByOrder
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Slips</title>
    <!-- Use Tailwind via CDN for quick styling in print view -->
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
    <style>
        @page {
            size: 100mm 100mm;
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            background: #f5f5f5;
            font-family: sans-serif;
        }
        .slip-page {
            width: 100mm;
            height: 100mm;
            background: #fff;
            padding: 8mm;
            box-sizing: border-box;
            page-break-after: always;
            position: relative;
            overflow: hidden;
        }
        @media print {
            body { background: #fff; }
            .slip-page {
                box-shadow: none;
                margin: 0;
            }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    <div class="no-print p-4 bg-gray-900 text-white flex justify-between items-center fixed top-0 w-full z-50">
        <div>
            <h1 class="text-xl font-bold">Print Order Slips</h1>
            <p class="text-xs text-gray-400">Total <?= count($orders) ?> slips. Format: 100x100mm</p>
        </div>
        <button onclick="window.print()" class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white rounded font-bold shadow transition-colors">
            Print Now
        </button>
    </div>

    <div class="pt-20 print:pt-0">
        <?php foreach ($orders as $order): ?>
            <?php 
                $items = $itemsByOrder[$order['id']] ?? []; 
                $hasMod = !empty($order['has_modification']);
            ?>
            <div class="slip-page border-b border-gray-300 print:border-none mx-auto mb-4 print:mb-0 shadow-lg flex flex-col justify-between">
                
                <!-- Header -->
                <div class="border-b-2 border-black pb-2 mb-2 flex justify-between items-start">
                    <div>
                        <h2 class="text-xl font-black mb-0 leading-none">ORDER <?= htmlspecialchars($order['order_number']) ?></h2>
                        <p class="text-[10px] text-gray-500 mt-1"><?= date('d M Y, H:i', strtotime($order['created_at'])) ?></p>
                    </div>
                    <?php if ($hasMod): ?>
                        <span class="bg-black text-white text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-widest">Modified</span>
                    <?php endif; ?>
                </div>

                <!-- Addresses -->
                <div class="mb-2 text-[10px] leading-tight flex flex-col gap-2 flex-1">
                    <!-- From Address -->
                    <div class="border-b border-gray-100 pb-2">
                        <p class="font-bold text-gray-500 uppercase tracking-wider text-[8px] mb-0.5">From:</p>
                        <p class="font-bold text-[11px]"><?= APP_NAME ?></p>
                        <p>123 Business Park, Industrial Estate</p>
                        <p>Chennai, Tamil Nadu 600001, India</p>
                    </div>

                    <!-- To Address -->
                    <div>
                        <p class="font-bold text-gray-500 uppercase tracking-wider text-[8px] mb-0.5">To:</p>
                        <p class="font-bold text-[13px] mb-0.5"><?= htmlspecialchars($order['user_name']) ?></p>
                        <p>Phone: <?= htmlspecialchars($order['user_phone']) ?></p>
                        <p class="mt-0.5 whitespace-pre-wrap"><?= htmlspecialchars($order['shipping_address']) ?></p>
                    </div>
                </div>

                <!-- Items Summary (Truncated if too long) -->
                <div class="border-t border-dashed border-gray-400 pt-2 text-[10px]">
                    <p class="font-bold mb-1">Items (<?= count($items) ?>):</p>
                    <ul class="list-disc pl-3 h-[25mm] overflow-hidden">
                        <?php 
                        $maxItems = 3;
                        $count = 0;
                        foreach ($items as $item): 
                            if ($count >= $maxItems) {
                                echo '<li class="italic text-gray-500">...and ' . (count($items) - $maxItems) . ' more items</li>';
                                break;
                            }
                        ?>
                            <li class="truncate flex justify-between gap-1">
                                <span>
                                    <?= htmlspecialchars($item['name']) ?> 
                                    <?= $item['variant_name'] ? '(' . htmlspecialchars($item['variant_name']) . ')' : '' ?> 
                                    <span class="font-bold">x<?= $item['quantity'] ?></span>
                                </span>
                                <span class="font-semibold shrink-0">Rs. <?= number_format($item['total_price']) ?></span>
                            </li>
                        <?php 
                            $count++;
                        endforeach; 
                        ?>
                    </ul>
                </div>

                <!-- Footer Total -->
                <div class="border-t-2 border-black mt-2 pt-2 flex justify-between items-center">
                    <span class="font-bold text-[12px] uppercase">Amount:</span>
                    <span class="font-black text-[16px]">Rs. <?= number_format($order['grand_total']) ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Auto trigger print on load if uncommented -->
    <!-- <script>window.onload = function() { window.print(); }</script> -->
</body>
</html>
