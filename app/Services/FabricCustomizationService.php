<?php

namespace App\Services;

class FabricCustomizationService
{
    /**
     * Calculate live fabric consumption and validation metrics
     *
     * @param array $customization Customization rule data with sizes
     * @param array $sizeQuantities Key-value pair of size_name/size_id => quantity
     * @param float|null $availableStock Current product stock quantity
     * @param int $fabricQuantity Fabric quantity / set multiplier
     * @return array
     */
    public function calculateConsumption(array $customization, array $sizeQuantities, ?float $availableStock = null, int $fabricQuantity = 1)
    {
        $fabricQuantity = max(1, $fabricQuantity);
        $minPieces = (int)($customization['minimum_pieces'] ?? 1) * $fabricQuantity;
        $maxPieces = !empty($customization['maximum_pieces']) ? (int)$customization['maximum_pieces'] * $fabricQuantity : null;
        $wastagePct = (float)($customization['wastage_percentage'] ?? 0.00);
        $unit = $customization['unit'] ?? 'Meter';
        $stock = $availableStock !== null ? (float)$availableStock : (float)($customization['fabric_stock'] ?? 0);

        // Map configured sizes
        $configuredSizes = [];
        if (!empty($customization['sizes'])) {
            foreach ($customization['sizes'] as $sz) {
                $configuredSizes[$sz['size_name']] = (float)$sz['fabric_consumption'];
                if (!empty($sz['size_id'])) {
                    $configuredSizes['id_' . $sz['size_id']] = (float)$sz['fabric_consumption'];
                }
            }
        }

        $totalPieces = 0;
        $baseConsumption = 0.00;
        $breakdown = [];

        foreach ($sizeQuantities as $sizeKey => $qty) {
            $qty = (int)$qty;
            if ($qty <= 0) continue;

            $consumptionPerPiece = 0.00;
            $sizeName = (string)$sizeKey;

            if (isset($configuredSizes[$sizeKey])) {
                $consumptionPerPiece = $configuredSizes[$sizeKey];
            } elseif (isset($configuredSizes['id_' . $sizeKey])) {
                $consumptionPerPiece = $configuredSizes['id_' . $sizeKey];
            } else {
                // Fallback search in sizes array
                foreach ($customization['sizes'] as $sz) {
                    if ((string)$sz['id'] === (string)$sizeKey || (string)$sz['size_id'] === (string)$sizeKey || strcasecmp($sz['size_name'], (string)$sizeKey) === 0) {
                        $consumptionPerPiece = (float)$sz['fabric_consumption'];
                        $sizeName = $sz['size_name'];
                        break;
                    }
                }
            }

            $sizeTotalConsumption = $qty * $consumptionPerPiece;
            $totalPieces += $qty;
            $baseConsumption += $sizeTotalConsumption;

            $breakdown[] = [
                'size_name'             => $sizeName,
                'quantity'              => $qty,
                'consumption_per_piece' => $consumptionPerPiece,
                'total_consumption'     => round($sizeTotalConsumption, 3),
                'unit'                  => $unit
            ];
        }

        $wastageAmount = $baseConsumption * ($wastagePct / 100.0);
        $requiredFabric = $baseConsumption + $wastageAmount;

        $isMinMet = ($totalPieces >= $minPieces);
        $isMaxMet = ($maxPieces === null || $totalPieces <= $maxPieces);
        $isStockSufficient = ($stock >= $fabricQuantity);

        $errors = [];
        if ($totalPieces === 0) {
            $errors[] = "Please select size variants. A minimum of {$minPieces} pieces is required to enable payment.";
        } elseif (!$isMinMet) {
            $remaining = $minPieces - $totalPieces;
            $errors[] = "Minimum {$minPieces} pieces required to enable payment. You currently have {$totalPieces} pieces (Please select {$remaining} more pcs).";
        }
        if (!$isMaxMet && $maxPieces !== null) {
            $errors[] = "Maximum order quantity is {$maxPieces} pieces. You currently have {$totalPieces} pieces.";
        }
        if (!$isStockSufficient) {
            $shortage = (int)($fabricQuantity - $stock);
            $errors[] = "Fabric Stock Exceeded: You selected {$fabricQuantity} Sets, but only {$stock} {$unit} are available in inventory.";
        }

        $isValid = empty($errors) && $totalPieces > 0;

        return [
            'fabric_quantity'       => $fabricQuantity,
            'total_pieces'          => $totalPieces,
            'base_consumption'      => round($baseConsumption, 3),
            'wastage_percentage'    => $wastagePct,
            'wastage_amount'        => round($wastageAmount, 3),
            'required_fabric'       => round($requiredFabric, 2),
            'available_stock'       => $stock,
            'unit'                  => $unit,
            'minimum_pieces'        => $minPieces,
            'maximum_pieces'        => $maxPieces,
            'is_min_met'            => $isMinMet,
            'is_max_met'            => $isMaxMet,
            'is_stock_sufficient'   => $isStockSufficient,
            'is_valid'              => $isValid,
            'errors'                => $errors,
            'breakdown'             => $breakdown
        ];
    }

    /**
     * Compute total pricing breakdown
     */
    public function calculatePricing(float $pricePerUnit, float $requiredFabric, float $stitchingPerPiece = 0.00, int $totalPieces = 0, float $gstRate = 5.0)
    {
        $fabricCost = round($pricePerUnit * $totalPieces, 2);
        $stitchingCost = round($stitchingPerPiece * $totalPieces, 2);
        $subtotal = $fabricCost + $stitchingCost;
        $gstAmount = round($subtotal * ($gstRate / 100.0), 2);
        $total = $subtotal + $gstAmount;

        return [
            'price_per_unit'    => $pricePerUnit,
            'required_fabric'   => $requiredFabric,
            'fabric_cost'       => $fabricCost,
            'stitching_per_pc'  => $stitchingPerPiece,
            'total_pieces'      => $totalPieces,
            'stitching_cost'    => $stitchingCost,
            'subtotal'          => $subtotal,
            'gst_rate'          => $gstRate,
            'gst_amount'        => $gstAmount,
            'total'             => $total
        ];
    }
}
