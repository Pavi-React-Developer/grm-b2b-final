<style>
@media (max-width: 640px) {
    .order-item-row { flex-direction: column !important; align-items: flex-start !important; gap: 0.75rem; }
    .order-item-price { align-self: flex-start !important; text-align: left !important; margin-top: 0.25rem; }
    .item-details-container { padding-right: 0 !important; width: 100%; }
}
</style>
<div style="min-height: 80vh; background: linear-gradient(135deg, #f0fdf4 0%, #f9fafb 100%); display: flex; align-items: center; justify-content: center; padding: 1.5rem 1rem;">
    <div style="max-width: 32rem; width: 100%; background: #ffffff; border-radius: 1rem; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04); overflow: hidden; position: relative; border: 1px solid #e5e7eb;">
        
        <!-- Header Section -->
        <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 1.75rem 1.25rem; text-align: center; position: relative; overflow: hidden;">
            <!-- Decorative elements -->
            <div style="position: absolute; top: -2rem; left: -2rem; width: 8rem; height: 8rem; background: rgba(255,255,255,0.08); border-radius: 50%;"></div>
            <div style="position: absolute; bottom: -2.5rem; right: -2.5rem; width: 10rem; height: 10rem; background: rgba(255,255,255,0.08); border-radius: 50%;"></div>
            
            <div style="position: relative; z-index: 10;">
                <div style="width: 3.25rem; height: 3.25rem; background: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem auto; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                    <svg style="width: 1.75rem; height: 1.75rem; color: #10b981;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h1 style="font-size: 1.35rem; font-weight: 800; color: #ffffff; margin-bottom: 0.25rem; letter-spacing: -0.015em; font-family: 'Inter', sans-serif;">Order Confirmed!</h1>
                <p style="color: #d1fae5; font-size: 0.85rem; font-weight: 400; margin: 0;">Thank you! Your transaction was successfully completed.</p>
            </div>
        </div>

        <!-- Order Info Section -->
        <div style="padding: 1.25rem 1.25rem 1.5rem 1.25rem;">
            <div class="order-top-row" style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.875rem; border-bottom: 1px solid #f3f4f6; margin-bottom: 1.25rem; gap: 0.75rem;">
                <div style="flex: 1; min-width: 0;">
                    <p style="font-size: 0.68rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; margin-bottom: 0.15rem;">Order Number</p>
                    <p style="font-size: 1.05rem; font-weight: 800; color: #111827; word-break: break-all; margin: 0;">#<?= htmlspecialchars($order['order_number']) ?></p>
                </div>
                <div style="text-align: right; flex-shrink: 0;">
                    <p style="font-size: 0.68rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; margin-bottom: 0.15rem;">Total Paid</p>
                    <?php $paidAmount = (!empty($order['cod_advance_paid']) && $order['cod_advance_paid'] > 0) ? $order['cod_advance_paid'] : $order['grand_total']; ?>
                    <p style="font-size: 1.25rem; font-weight: 800; color: #059669; margin: 0; white-space: nowrap;">₹<?= number_format($paidAmount) ?></p>
                </div>
            </div>

            <!-- Products Summary -->
            <div style="margin-bottom: 1.25rem;">
                <h3 style="font-size: 0.95rem; font-weight: 700; color: #111827; margin-bottom: 0.75rem; display: flex; align-items: center;">
                    <svg style="width: 1.15rem; height: 1.15rem; margin-right: 0.375rem; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Order Summary
                </h3>
                
                <div style="background: #f9fafb; border-radius: 0.75rem; padding: 0.375rem; border: 1px solid #f3f4f6;">
                    <?php foreach($items as $item): ?>
                        <div class="order-item-row" style="padding: 0.75rem; display: flex; justify-content: space-between; align-items: center; background: #ffffff; border-radius: 0.5rem; margin-bottom: 0.375rem; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
                            <div style="display: flex; align-items: center; width: 100%;">
                                <?php if (!empty($item['primary_image'])): ?>
                                    <img src="<?= htmlspecialchars(get_image_url($item['primary_image'])) ?>" alt="<?= htmlspecialchars($item['name']) ?>" style="width: 3rem; height: 3rem; object-fit: cover; border-radius: 0.375rem; margin-right: 0.75rem; border: 1px solid #f3f4f6; flex-shrink: 0;">
                                <?php else: ?>
                                    <div style="width: 3rem; height: 3rem; background: #f3f4f6; border-radius: 0.375rem; margin-right: 0.75rem; display: flex; align-items: center; justify-content: center; color: #9ca3af; flex-shrink: 0;">
                                        <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                <?php endif; ?>
                                <div class="item-details-container" style="flex: 1; padding-right: 0.75rem; min-width: 0;">
                                    <h4 style="font-weight: 600; color: #111827; font-size: 0.875rem; margin-bottom: 0.15rem; white-space: normal; word-break: break-word; line-height: 1.3;">
                                        <?= htmlspecialchars($item['name']) ?>
                                    </h4>
                                    <?php if (!empty($item['variant_name'])): ?>
                                        <div style="font-size: 0.7rem; color: #6b7280; font-weight: 500; margin-bottom: 0.15rem; white-space: normal; word-break: break-word;">
                                            Variant: <?= htmlspecialchars($item['variant_name']) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div style="display: flex; align-items: center; font-size: 0.75rem; color: #6b7280; flex-wrap: wrap; gap: 0.375rem;">
                                        <?php if (!empty($item['category_hsn_code'])): ?>
                                            <span style="background: #fdf2f8; color: #db2777; border: 1px solid #fbcfe8; padding: 0.1rem 0.35rem; border-radius: 0.2rem; font-size: 0.65rem; font-weight: 700; font-family: monospace;">HSN: <?= htmlspecialchars($item['category_hsn_code']) ?></span>
                                        <?php endif; ?>
                                        <span style="background: #f3f4f6; color: #374151; padding: 0.1rem 0.35rem; border-radius: 0.2rem; font-size: 0.7rem; font-weight: 600;">Qty: <?= $item['quantity'] ?></span>
                                        <span style="font-size: 0.75rem;">Rate: ₹<?= number_format($item['price'] ?? ($item['total_price'] / $item['quantity'])) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="order-item-price" style="text-align: right; flex-shrink: 0;">
                                <span style="font-weight: 700; color: #111827; font-size: 0.95rem;">₹<?= number_format($item['total_price']) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Payment Summary -->
            <div style="margin-bottom: 1.25rem;">
                <h3 style="font-size: 0.95rem; font-weight: 700; color: #111827; margin-bottom: 0.75rem; display: flex; align-items: center;">
                    <svg style="width: 1.1rem; height: 1.1rem; margin-right: 0.375rem; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Payment Summary
                </h3>
                
                <div style="background: #ffffff; border-radius: 0.75rem; padding: 1rem; border: 1px solid #f3f4f6; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.375rem; font-size: 0.8rem; color: #4b5563;">
                        <span>Subtotal</span>
                        <span style="font-weight: 600; color: #111827;">₹<?= number_format($order['total_amount']) ?></span>
                    </div>
                    <?php
                    $feeBreakdown = !empty($order['fee_breakdown']) ? json_decode($order['fee_breakdown'], true) : null;
                    if (!empty($feeBreakdown)): 
                        foreach ($feeBreakdown as $fee):
                    ?>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.375rem; font-size: 0.8rem; color: #4b5563;">
                            <span>
                                <?= htmlspecialchars($fee['name']) ?>
                                <?php if (!empty($fee['note'])): ?>
                                    <span style="font-size: 0.7rem; color: #9ca3af; margin-left: 0.2rem;">(<?= htmlspecialchars($fee['note']) ?>)</span>
                                <?php endif; ?>
                            </span>
                            <span style="font-weight: 600; color: #111827;">₹<?= number_format($fee['amount']) ?></span>
                        </div>
                    <?php 
                        endforeach; 
                    else: 
                    ?>
                        <?php if (!empty($order['shipping_fee']) && $order['shipping_fee'] > 0): ?>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.375rem; font-size: 0.8rem; color: #4b5563;">
                            <span>Shipping Fee</span>
                            <span style="font-weight: 600; color: #111827;">₹<?= number_format($order['shipping_fee']) ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($order['weight_fee']) && $order['weight_fee'] > 0): ?>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.375rem; font-size: 0.8rem; color: #4b5563;">
                            <span>Weight Fee</span>
                            <span style="font-weight: 600; color: #111827;">₹<?= number_format($order['weight_fee']) ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($order['platform_fee']) && $order['platform_fee'] > 0): ?>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.375rem; font-size: 0.8rem; color: #4b5563;">
                            <span>Platform Fee</span>
                            <span style="font-weight: 600; color: #111827;">₹<?= number_format($order['platform_fee']) ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($order['packaging_fee']) && $order['packaging_fee'] > 0): ?>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.375rem; font-size: 0.8rem; color: #4b5563;">
                            <span>Packing Fee</span>
                            <span style="font-weight: 600; color: #111827;">₹<?= number_format($order['packaging_fee']) ?></span>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <div style="border-top: 1px solid #f3f4f6; margin-top: 0.5rem; padding-top: 0.5rem; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-weight: 700; color: #111827; font-size: 0.875rem;">Grand Total</span>
                        <span style="font-weight: 800; color: #059669; font-size: 1rem;">₹<?= number_format($order['grand_total']) ?></span>
                    </div>
                    <div style="font-size: 0.7rem; color: #db2777; background: #fdf2f8; border: 1px solid #fce7f3; border-radius: 0.25rem; padding: 0.25rem 0.4rem; margin-top: 0.35rem; font-weight: 600;">
                        <span style="font-size: 0.6rem; text-transform: uppercase; color: #9d174d; display: block;">In Words:</span>
                        <?= htmlspecialchars(\App\Services\InvoiceService::numberToWordsIndian((float)$order['grand_total'])) ?>
                    </div>
                    <?php if (!empty($order['balance_amount']) && $order['balance_amount'] > 0): ?>
                    <div style="border-top: 1px dashed #e5e7eb; margin-top: 0.5rem; padding-top: 0.5rem; display: flex; justify-content: space-between; font-size: 0.8rem;">
                        <span style="color: #6b7280;">COD Balance (To pay on delivery)</span>
                        <span style="font-weight: 700; color: #dc2626;">₹<?= number_format($order['balance_amount']) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 0.5rem; padding-top: 0.5rem; flex-wrap: wrap;">
                <a href="<?= BASE_URL ?>/dashboard/orders" style="flex: 1; text-align: center; padding: 0.45rem 0.75rem; background: #F25996; color: #ffffff; font-weight: 600; font-size: 0.75rem; border-radius: 0.375rem; text-decoration: none; box-shadow: 0 2px 4px rgba(242, 89, 150, 0.25); border: 1px solid #F25996; transition: all 0.2s; min-width: 120px;" onmouseover="this.style.backgroundColor='#d8407d'; this.style.borderColor='#d8407d';" onmouseout="this.style.backgroundColor='#F25996'; this.style.borderColor='#F25996';">
                    View Order Details
                </a>
                <a href="<?= BASE_URL ?>/catalog" style="flex: 1; text-align: center; padding: 0.45rem 0.75rem; background: #fdf2f8; color: #db2777; font-weight: 600; font-size: 0.75rem; border-radius: 0.375rem; border: 1px solid #fbcfe8; text-decoration: none; box-shadow: 0 1px 2px rgba(219, 39, 119, 0.05); transition: all 0.2s; min-width: 120px;" onmouseover="this.style.backgroundColor='#fce7f3';" onmouseout="this.style.backgroundColor='#fdf2f8';">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>
