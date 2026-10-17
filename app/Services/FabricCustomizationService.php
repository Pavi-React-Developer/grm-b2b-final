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
    public function calculateConsumption(array $customization, array $sizeQuantities, ?float $availableStock = null, int $fabricQuantity = 1, array $sets = [])
    {
        $fabricQuantity = max(1, $fabricQuantity);
        $baseMin = !empty($customization['required_pieces']) ? (int)$customization['required_pieces'] : (int)($customization['minimum_pieces'] ?? 1);
        $baseMax = !empty($customization['maximum_pieces']) ? (int)$customization['maximum_pieces'] : ($baseMin * 20);
        $perSetMin = $baseMin;
        $perSetMax = $baseMax;
        $minPieces = $baseMin * $fabricQuantity;
        $maxPieces = $baseMax * $fabricQuantity;
        $wastagePct = (float)($customization['wastage_percentage'] ?? 0.00);
        $unit = $customization['unit'] ?? 'Meter';
        $stock = $availableStock !== null ? (float)$availableStock : (float)($customization['fabric_stock'] ?? 0);

        // Map configured sizes and limits
        $configuredSizes = [];
        $sizeLimits = [];
        if (!empty($customization['sizes'])) {
            foreach ($customization['sizes'] as $sz) {
                $sName = $sz['size_name'];
                $configuredSizes[$sName] = (float)$sz['fabric_consumption'];
                $sizeLimits[$sName] = [
                    'base_min' => isset($sz['base_quantity']) ? (int)$sz['base_quantity'] : 2,
                    'base_max' => isset($sz['max_quantity']) && (int)$sz['max_quantity'] > 0 ? (int)$sz['max_quantity'] : 35
                ];
                if (!empty($sz['size_id'])) {
                    $configuredSizes['id_' . $sz['size_id']] = (float)$sz['fabric_consumption'];
                }
            }
        }

        $totalPieces = 0;
        $baseConsumption = 0.00;
        $breakdown = [];
        $errors = [];

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

            // Validate individual size min & max limits
            $limits = $sizeLimits[$sizeName] ?? ['base_min' => 1, 'base_max' => 9999];
            $effectiveSizeMin = min($limits['base_min'], $baseMin);
            if ($effectiveSizeMin < 1) $effectiveSizeMin = 1;
            $effectiveSizeMax = $limits['base_max'] * $fabricQuantity;

            if ($qty < $effectiveSizeMin) {
                $errors[] = "Size {$sizeName}: Quantity {$qty} pcs is below the minimum required ({$effectiveSizeMin} pcs).";
            }
            if ($qty > $effectiveSizeMax) {
                $errors[] = "Size {$sizeName}: Quantity {$qty} pcs exceeds the maximum allowed ({$effectiveSizeMax} pcs for {$fabricQuantity} Set(s)).";
            }

            $sizeTotalConsumption = $qty * $consumptionPerPiece;
            $totalPieces += $qty;
            $baseConsumption += $sizeTotalConsumption;

            $breakdown[] = [
                'size_name'             => $sizeName,
                'quantity'              => $qty,
                'min_allowed'           => $effectiveSizeMin,
                'max_allowed'           => $effectiveSizeMax,
                'consumption_per_piece' => $consumptionPerPiece,
                'total_consumption'     => round($sizeTotalConsumption, 3),
                'unit'                  => $unit
            ];
        }

        // Validate each individual fabric set container
        if (!empty($sets)) {
            foreach ($sets as $sIdx => $setData) {
                $setNum = $setData['set_id'] ?? ($sIdx + 1);
                $setPcs = 0;
                if (!empty($setData['sizes']) && is_array($setData['sizes'])) {
                    foreach ($setData['sizes'] as $szQ) {
                        $setPcs += (int)$szQ;
                    }
                } elseif (isset($setData['total_pieces'])) {
                    $setPcs = (int)$setData['total_pieces'];
                }
                if ($setPcs < $perSetMin) {
                    $needed = $perSetMin - $setPcs;
                    $errors[] = "Fabric Set #{$setNum}: Selected {$setPcs} pcs, which is below the required {$perSetMin} pieces (Please add {$needed} more pcs).";
                } elseif ($setPcs > $perSetMax) {
                    $excess = $setPcs - $perSetMax;
                    $errors[] = "Fabric Set #{$setNum}: Selected {$setPcs} pcs, which exceeds the allowed {$perSetMax} pieces ({$excess} pcs in excess).";
                }
            }
        }

        $wastageAmount = $baseConsumption * ($wastagePct / 100.0);
        $requiredFabric = $baseConsumption + $wastageAmount;

        $isMinMet = ($totalPieces >= $minPieces);
        $isMaxMet = ($maxPieces === null || $totalPieces <= $maxPieces);
        $isStockSufficient = ($stock >= $fabricQuantity);

        if ($totalPieces === 0) {
            $errors[] = "Please select size variants. A minimum of {$minPieces} pieces ({$perSetMin} pcs per fabric set) is required to enable payment.";
        } elseif (!$isMinMet) {
            $remaining = $minPieces - $totalPieces;
            $errors[] = "Minimum {$minPieces} pieces required to enable payment ({$perSetMin} pcs per fabric set). You currently have {$totalPieces} pieces (Please select {$remaining} more pcs).";
        } elseif ($totalPieces > $maxPieces) {
            $excess = $totalPieces - $maxPieces;
            $errors[] = "Maximum {$maxPieces} pieces allowed for {$fabricQuantity} Set(s) ({$perSetMax} pcs per fabric set). You currently have {$totalPieces} pieces ({$excess} pcs in excess). Please reduce variant quantities.";
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
            'per_set_minimum'       => $perSetMin,
            'per_set_maximum'       => $perSetMax,
            'is_min_met'            => $isMinMet,
            'is_max_met'            => $isMaxMet,
            'is_stock_sufficient'   => $isStockSufficient,
            'is_valid'              => $isValid,
            'errors'                => $errors,
            'breakdown'             => $breakdown
        ];
    }

    /**
     * Compute total pricing breakdown with optional Without Cover percentage discount
     */
    public function calculatePricing(float $pricePerUnit, float $requiredFabric, float $stitchingPerPiece = 0.00, int $totalPieces = 0, float $gstRate = 5.0, bool $withoutCover = false, float $discountPercentage = 0.00)
    {
        $effectivePricePerUnit = $pricePerUnit;
        if ($withoutCover && $discountPercentage > 0) {
            $effectivePricePerUnit = max(0, round($pricePerUnit * (1.0 - ($discountPercentage / 100.0)), 2));
        }

        $originalFabricCost = round($pricePerUnit * $requiredFabric, 2);
        $fabricCost = round($effectivePricePerUnit * $requiredFabric, 2);
        $stitchingCost = round($stitchingPerPiece * $totalPieces, 2);
        $subtotal = $fabricCost + $stitchingCost;

        // GST is inclusive in the price (customer pays only the subtotal/fabric cost, GST is not added extra)
        // GST component = subtotal - (subtotal / (1 + gstRate/100))
        $gstAmount = ($gstRate > 0) ? round($subtotal - ($subtotal / (1.0 + ($gstRate / 100.0))), 2) : 0.00;
        $total = $subtotal;
        $totalSaved = max(0, round($originalFabricCost - $fabricCost, 2));

        return [
            'original_price_per_unit' => $pricePerUnit,
            'price_per_unit'          => $effectivePricePerUnit,
            'fabric_price_original'   => $pricePerUnit,
            'fabric_price_effective'  => $effectivePricePerUnit,
            'without_cover'           => $withoutCover,
            'discount_percentage'     => $discountPercentage,
            'without_cover_discount'  => $totalSaved,
            'total_saved'             => $totalSaved,
            'required_fabric'         => $requiredFabric,
            'fabric_cost_original'    => $originalFabricCost,
            'fabric_cost'             => $fabricCost,
            'stitching_per_pc'        => $stitchingPerPiece,
            'total_pieces'            => $totalPieces,
            'stitching_cost'          => $stitchingCost,
            'subtotal'                => $subtotal,
            'gst_rate'                => $gstRate,
            'gst_amount'              => $gstAmount,
            'total'                   => $total
        ];
    }

    /**
     * Calculate dynamic delivery schedule based on total pieces ordered
     */
    public function calculateDeliveryTimeline(int $totalPieces, int $baseDays = 7, int $basePieces = 100)
    {
        $totalPieces = max(1, $totalPieces);
        $baseDays = max(1, $baseDays);
        $basePieces = max(1, $basePieces);
        
        $multiplier = (int)ceil($totalPieces / $basePieces);
        $totalDays = $multiplier * $baseDays;
        $formattedDate = date('M d, Y', strtotime("+{$totalDays} days"));
        $rawDate = date('Y-m-d', strtotime("+{$totalDays} days"));
        
        return [
            'total_days'               => $totalDays,
            'base_days'                => $baseDays,
            'base_pieces'              => $basePieces,
            'multiplier'               => $multiplier,
            'total_pieces'             => $totalPieces,
            'estimated_date'           => $rawDate,
            'formatted_date'           => $formattedDate,
            'estimated_date_formatted' => $formattedDate
        ];
    }
}
