<?php
namespace App\Controllers\Storefront;

use Core\Controller;
use Core\Session;
use App\Models\Cart;
use App\Models\FeeRule;

class FeeApiController extends Controller
{
    public function calculate()
    {
        header('Content-Type: application/json');

        $userId = Session::get('user_id');
        if (!$userId || Session::get('user_status') !== 'active') {
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $state = trim($_POST['state'] ?? $_GET['state'] ?? '');
        $paymentMethod = trim($_POST['payment_method'] ?? $_GET['payment_method'] ?? 'Online');
        // Normalize payment method: map COD-related terms
        if (strtolower($paymentMethod) === 'cod') {
            $paymentMethod = 'COD';
        } else {
            $paymentMethod = 'Online';
        }

        $cartModel = new Cart();
        $cartItems = $cartModel->getItems($userId);

        if (empty($cartItems)) {
            echo json_encode(['error' => 'Cart is empty']);
            return;
        }

        // 1. Calculate subtotal (Incl. GST)
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $gst = (float)($item['variant_total_gst'] ?? 0);
            if ($gst <= 0) $gst = (float)($item['product_total_gst'] ?? 0);
            if ($gst <= 0) $gst = (float)($item['category_sgst'] ?? 0) + (float)($item['category_cgst'] ?? 0);

            if (!empty($item['variant_discount_price']) && (float)$item['variant_discount_price'] > 0) {
                $rawEffective = (float)$item['variant_discount_price'];
            } else {
                $rawEffective = (!empty($item['variant_price']) && (float)$item['variant_price'] > 0) 
                    ? (float)$item['variant_price'] 
                    : (float)($item['wholesale_price'] ?? 0);
            }

            $unitPrice = $rawEffective;

            $subtotal += $unitPrice * $item['quantity'];
        }

        // 2. Aggregate total cart weight in KG
        $totalWeightKg = 0.0;
        foreach ($cartItems as $item) {
            $weight = (float)($item['weight'] ?? 0);
            $unit   = strtolower(trim($item['weight_unit'] ?? 'kg'));
            // If unit contains 'g' but not 'kg', treat as grams
            if (strpos($unit, 'g') !== false && strpos($unit, 'kg') === false) {
                $weight = $weight / 1000.0;
            }
            $totalWeightKg += $weight * $item['quantity'];
        }

        // 3. Aggregate total cart amount (for packing/fee calculation)
        $totalAmount = $subtotal;

        // 4. Resolve fee rules by state & payment method
        $feeRuleModel = new FeeRule();
        $matchedRules = $feeRuleModel->findMatchingRules($state, $paymentMethod);

        $fees = [
            'shipping_fee'  => 0.0,
            'weight_fee'    => 0.0,
            'platform_fee'  => 0.0,
            'packaging_fee' => 0.0,
            'cod_advance'   => 0.0,
        ];

        $codAvailable = true;
        $codMessage = '';
        $feeBreakdown = [];

        foreach ($matchedRules as $rule) {
            $maxOrderAmt = !empty($rule['maximum_order_amount']) ? (float)$rule['maximum_order_amount'] : null;
            $minOrderAmt = !empty($rule['minimum_order_amount']) ? (float)$rule['minimum_order_amount'] : null;
            $scalingMultiplier = 1; // default: no scaling

            // Minimum order check — skip if below minimum
            if ($minOrderAmt !== null && $subtotal < $minOrderAmt) {
                if ($rule['payment_method'] === 'COD') {
                    $codAvailable = false;
                    $codMessage = "COD is not available for orders below ₹" . number_format($minOrderAmt, 2) . ".";
                }
                continue;
            }

            // Maximum order check — if exceeded, scale proportionally instead of skipping
            if ($maxOrderAmt !== null && $subtotal > $maxOrderAmt && $maxOrderAmt > 0) {
                $scalingMultiplier = (int)ceil($subtotal / $maxOrderAmt);
            }

            $categoryName = strtolower($rule['category_name'] ?? '');
            $slabs = json_decode($rule['weight_slabs'] ?? '[]', true) ?: [];

            if (!empty($slabs)) {
                // Weight-slab based fee
                $charge = $this->resolveWeightSlabCharge($slabs, $totalWeightKg);
                $key = $this->resolveFeeKey($categoryName);
                $fees[$key] += $charge;
                $feeBreakdown[] = [
                    'name'   => $rule['fee_name'],
                    'amount' => $charge,
                    'note'   => "Weight: " . round($totalWeightKg, 3) . " kg"
                ];
            } else {
                // Flat or percentage fee — apply scaling if order exceeds max
                $baseCharge = $this->resolveFlat($rule, $subtotal);
                $charge = $baseCharge * $scalingMultiplier;
                $key = $this->resolveFeeKey($categoryName);
                $fees[$key] += $charge;
                if ($charge > 0) {
                    $note = $scalingMultiplier > 1
                        ? "Scaled ×{$scalingMultiplier} (order ₹" . number_format($subtotal, 0) . " ÷ ₹" . number_format($maxOrderAmt, 0) . " cap)"
                        : null;
                    $breakdown = ['name' => $rule['fee_name'], 'amount' => $charge];
                    if ($note) $breakdown['note'] = $note;
                    $feeBreakdown[] = $breakdown;
                }
            }
        }


        // 6. Grand Total formula
        // Grand Total = Subtotal + Billable Fees (shipping + weight + platform + packaging)
        // Round off calculated by next whole integer (round)
        $billableFees = (float)$fees['shipping_fee'] + (float)$fees['weight_fee'] + (float)$fees['platform_fee'] + (float)$fees['packaging_fee'];
        $unroundedGrandTotal = (float)$subtotal + $billableFees;
        $grandTotal   = (float)round($unroundedGrandTotal);
        $roundOff     = round($grandTotal - $unroundedGrandTotal, 2);
        if (abs($roundOff) < 0.001) {
            $roundOff = 0.0;
        }

        // 7. Paid/Balance amounts
        $codAdvance   = (float)$fees['cod_advance'];
        if ($paymentMethod === 'COD') {
            $paidAmount    = $codAdvance; // buyer pays this advance online
            $balanceAmount = $grandTotal - $paidAmount; // rest in cash on delivery
        } else {
            $paidAmount    = $grandTotal; // buyer pays full online
            $balanceAmount = 0.0;
        }

        echo json_encode([
            'success'         => true,
            'subtotal'        => round($subtotal, 2),
            'total_weight_kg' => round($totalWeightKg, 3),
            'total_amount'    => round($totalAmount, 2),
            'fees'            => [
                'shipping_fee'  => round($fees['shipping_fee'], 2),
                'weight_fee'    => round($fees['weight_fee'], 2),
                'platform_fee'  => round($fees['platform_fee'], 2),
                'packaging_fee' => round($fees['packaging_fee'], 2),
                'cod_advance'   => round($codAdvance, 2),
            ],
            'fee_breakdown'   => $feeBreakdown,
            'billable_fees'   => round($billableFees, 2),
            'unrounded_total' => round($unroundedGrandTotal, 2),
            'round_off'       => round($roundOff, 2),
            'grand_total'     => round($grandTotal, 2),
            'paid_amount'     => round($paidAmount, 2),
            'balance_amount'  => round($balanceAmount, 2),
            'cod_available'   => $codAvailable,
            'cod_message'     => $codMessage,
        ]);
    }

    // ---- Private helpers ----

    private function resolveWeightSlabCharge(array $slabs, float $totalWeight): float
    {
        // Filter active slabs only and sort by minWeight
        $activeSlabs = array_filter($slabs, fn($s) => !empty($s['status']));
        usort($activeSlabs, fn($a, $b) => $a['minWeight'] <=> $b['minWeight']);
        $activeSlabs = array_values($activeSlabs);

        if (empty($activeSlabs)) return 0.0;

        $lowestSlab  = $activeSlabs[0];
        $highestSlab = end($activeSlabs);

        // Below the lowest slab — use lowest charge
        if ($totalWeight < $lowestSlab['minWeight']) {
            return (float)$lowestSlab['charge'];
        }

        // Within a slab
        foreach ($activeSlabs as $slab) {
            if ($totalWeight >= $slab['minWeight'] && $totalWeight <= $slab['maxWeight']) {
                return (float)$slab['charge'];
            }
        }

        // In an unconfigured gap — find the next higher slab
        foreach ($activeSlabs as $slab) {
            if ($totalWeight < $slab['minWeight']) {
                return (float)$slab['charge'];
            }
        }

        // Exceeds all slabs — proportional scaling
        // Charge = ceil(totalWeight / maxSlabLimit) × maxSlabCharge
        $maxLimit  = (float)$highestSlab['maxWeight'];
        $maxCharge = (float)$highestSlab['charge'];
        if ($maxLimit <= 0) return $maxCharge;

        return ceil($totalWeight / $maxLimit) * $maxCharge;
    }

    private function resolveFlat(array $rule, float $subtotal): float
    {
        if ($rule['fee_type'] === 'Percentage') {
            return ($subtotal * (float)$rule['flat_fee_value']) / 100.0;
        }
        return (float)$rule['flat_fee_value'];
    }

    private function resolveFeeKey(string $categoryName): string
    {
        if (strpos($categoryName, 'weight') !== false) return 'weight_fee';
        if (strpos($categoryName, 'platform') !== false) return 'platform_fee';
        if (strpos($categoryName, 'packing') !== false) return 'packaging_fee';
        if (strpos($categoryName, 'cod') !== false) return 'cod_advance';
        return 'shipping_fee';
    }
}
