<?php
$diff      = $mod['revised_total'] - $mod['original_total'];
$isSaving  = $diff < 0;
$diffText  = ($diff < 0 ? '−' : '+') . '₹' . number_format(abs($diff), 2);
$diffClass = $diff < 0 ? 'text-green-600' : ($diff > 0 ? 'text-red-500' : 'text-gray-500');

$changeTypeLabels = [
    'removed'       => ['label' => 'Removed',     'badge' => 'bg-red-100 text-red-700'],
    'qty_changed'   => ['label' => 'Qty Changed', 'badge' => 'bg-amber-100 text-amber-700'],
    'price_changed' => ['label' => 'Price Change','badge' => 'bg-purple-100 text-purple-700'],
    'replaced'      => ['label' => 'Replaced',    'badge' => 'bg-blue-100 text-blue-700'],
    'added'         => ['label' => 'New Added',   'badge' => 'bg-green-100 text-green-700'],
    'unchanged'     => ['label' => 'Unchanged',   'badge' => 'bg-gray-100 text-gray-500'],
];
?>

<section class="min-h-screen bg-gray-50 py-10 px-4">
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Header -->
    <div class="text-center">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-amber-100 mb-4">
            <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Your Order Has Been Modified</h1>
        <p class="text-gray-500 mt-2 text-sm">Order <span class="font-mono font-semibold text-gray-800">#<?= htmlspecialchars($mod['order_number']) ?></span></p>
    </div>

    <!-- Reason box -->
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-5">
        <p class="text-sm font-semibold text-amber-800 mb-1">Message from our team:</p>
        <p class="text-sm text-amber-900"><?= nl2br(htmlspecialchars($mod['reason'])) ?></p>
    </div>

    <!-- Expiry warning -->
    <?php if ($mod['expires_at'] && strtotime($mod['expires_at']) > time()): ?>
    <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-600 shadow-sm">
        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Please respond by <strong class="text-gray-800"><?= date('M j, Y H:i', strtotime($mod['expires_at'])) ?></strong>. After this, the order will be auto-cancelled.
    </div>
    <?php endif; ?>

    <!-- Items comparison -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">What Changed in Your Order</h2>
        </div>
        <div class="divide-y divide-gray-100">
            <?php foreach ($mod['items'] as $item):
                $ct        = $changeTypeLabels[$item['change_type']] ?? ['label' => $item['change_type'], 'badge' => 'bg-gray-100 text-gray-500'];
                $isRemoved = $item['change_type'] === 'removed';
                $isUnchanged = $item['change_type'] === 'unchanged';
            ?>
            <div class="flex items-start gap-4 px-6 py-5 <?= $isRemoved ? 'bg-red-50/50' : '' ?>">
                <!-- Image -->
                <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0 mt-0.5">
                    <?php if (!empty($item['product_image'])): ?>
                        <img src="<?= htmlspecialchars($item['product_image']) ?>" class="w-full h-full object-cover <?= $isRemoved ? 'grayscale opacity-50' : '' ?>">
                    <?php endif; ?>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <p class="font-semibold text-gray-900 <?= $isRemoved ? 'line-through text-gray-400' : '' ?>">
                            <?= htmlspecialchars($item['product_name']) ?>
                        </p>
                        <?php if ($item['variant_name']): ?>
                            <span class="text-xs text-gray-400">(<?= htmlspecialchars($item['variant_name']) ?>)</span>
                        <?php endif; ?>
                        <?php if (!$isUnchanged): ?>
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold <?= $ct['badge'] ?>">
                            <?= $ct['label'] ?>
                        </span>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($item['note'])): ?>
                        <p class="text-xs text-gray-500 italic mt-0.5"><?= htmlspecialchars($item['note']) ?></p>
                    <?php endif; ?>
                    <div class="mt-2 text-sm flex items-center gap-3 flex-wrap">
                        <?php if ($isRemoved): ?>
                            <span class="text-gray-400 line-through"><?= $item['old_qty'] ?> × ₹<?= number_format($item['old_price'], 2) ?></span>
                            <span class="font-semibold text-red-500">Removed</span>
                        <?php elseif ($item['change_type'] === 'added'): ?>
                            <span class="font-semibold text-green-500 italic">New</span>
                            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            <span class="text-gray-900 font-semibold"><?= $item['new_qty'] ?> × ₹<?= number_format($item['new_price'], 2) ?> = <strong class="text-brand-700">₹<?= number_format($item['new_qty'] * $item['new_price'], 2) ?></strong></span>
                        <?php elseif ($isUnchanged): ?>
                            <span class="text-gray-600"><?= $item['new_qty'] ?> × ₹<?= number_format($item['new_price'], 2) ?> = <strong>₹<?= number_format($item['new_qty'] * $item['new_price'], 2) ?></strong></span>
                        <?php else: ?>
                            <span class="text-gray-400 line-through"><?= $item['old_qty'] ?> × ₹<?= number_format($item['old_price'], 2) ?></span>
                            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            <span class="text-gray-900 font-semibold"><?= $item['new_qty'] ?> × ₹<?= number_format($item['new_price'], 2) ?> = <strong class="text-brand-700">₹<?= number_format($item['new_qty'] * $item['new_price'], 2) ?></strong></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Bill Summary -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Bill Summary</h3>
        <div class="space-y-2 text-sm text-gray-600">
            <div class="flex justify-between">
                <span>Original Total</span>
                <span class="line-through text-gray-400">₹<?= number_format($mod['original_total'], 2) ?></span>
            </div>
            <div class="flex justify-between font-semibold text-gray-900 pt-2 border-t border-gray-100">
                <span>Revised Total</span>
                <span class="text-xl text-brand-700">₹<?= number_format($mod['revised_total'], 2) ?></span>
            </div>
            <div class="flex justify-between text-sm <?= $diffClass ?> font-medium">
                <span><?= $isSaving ? 'You Save' : 'Additional Amount' ?></span>
                <span><?= $diffText ?></span>
            </div>
        </div>
    </div>

    <!-- Decision Buttons -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <p class="text-sm text-gray-600 mb-5 text-center">
            <strong>Accept</strong> to confirm the modified order, or <strong>Reject</strong> to cancel and get a full refund.
        </p>
        <div class="flex gap-4">
            <!-- Reject -->
            <form action="<?= BASE_URL ?>/order-modification/respond" method="POST" class="flex-1" onsubmit="return confirm('Are you sure you want to reject this modification? Your order will be cancelled and a refund will be initiated.')">
                <input type="hidden" name="modification_id" value="<?= $mod['id'] ?>">
                <input type="hidden" name="decision" value="reject">
                <button type="submit" class="w-full py-3.5 border-2 border-red-200 text-red-600 font-semibold rounded-xl hover:bg-red-50 transition-colors text-sm">
                    ✕ Reject & Cancel Order
                </button>
            </form>
            <!-- Accept -->
            <form action="<?= BASE_URL ?>/order-modification/respond" method="POST" class="flex-1" onsubmit="return confirm('Accept this modification? Your order will be updated and processed at the revised total.')">
                <input type="hidden" name="modification_id" value="<?= $mod['id'] ?>">
                <input type="hidden" name="decision" value="accept">
                <button type="submit" class="w-full py-3.5 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition-colors text-sm">
                    ✓ Accept Modified Order
                </button>
            </form>
        </div>
    </div>

    <a href="<?= BASE_URL ?>/dashboard/orders" class="block text-center text-sm text-gray-400 hover:text-gray-600">← Back to My Orders</a>

</div>
</section>
