<?php
namespace App\Models;

use Core\Model;

class FeeRule extends Model
{
    public static function normalizeState($state)
    {
        if (empty($state)) return '';
        $normalized = trim(strtolower($state));
        if ($normalized === 'all') return 'All';
        return mb_convert_case($normalized, MB_CASE_TITLE, "UTF-8");
    }

    public function getAll()
    {
        $stmt = $this->db->query("
            SELECT r.*, c.name as category_name 
            FROM fee_rules r
            JOIN fee_categories c ON r.fee_category_id = c.id
            ORDER BY r.fee_name ASC
        ");
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM fee_rules WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function findMatchingRules($state, $paymentMethod)
    {
        // Collapse spaces + lowercase for fuzzy matching:
        // "tamilnadu" == "Tamil Nadu" == "tamil nadu"
        $normalizedState = strtolower(str_replace([' ', '-', '_'], '', trim($state)));

        $stmt = $this->db->prepare("
            SELECT r.*, c.name as category_name 
            FROM fee_rules r
            JOIN fee_categories c ON r.fee_category_id = c.id
            WHERE r.active = 1 AND c.is_active = 1
        ");
        $stmt->execute();
        $allActiveRules = $stmt->fetchAll();

        $matchedRules = [];

        foreach ($allActiveRules as $rule) {
            // 1. Payment method check
            if ($rule['payment_method'] !== 'Both' && $rule['payment_method'] !== $paymentMethod) {
                continue;
            }

            // 2. Geography check — space-stripped, lowercased comparison
            $states = json_decode($rule['application_states'], true) ?: [];
            $stateMatch = false;

            foreach ($states as $s) {
                $ns = strtolower(str_replace([' ', '-', '_'], '', trim($s)));
                if ($ns === 'all' || $ns === $normalizedState) {
                    $stateMatch = true;
                    break;
                }
                if ($ns === 'otherstate' && $normalizedState !== 'tamilnadu') {
                    $stateMatch = true;
                    break;
                }
            }

            if ($stateMatch) {
                $matchedRules[] = $rule;
            }
        }

        return $matchedRules;
    }


    public function create(array $data)
    {
        $states = array_map([self::class, 'normalizeState'], $data['application_states'] ?? []);
        $weightSlabs = $data['weight_slabs'] ?? [];
        
        // Validate weight slabs overlap
        $this->validateWeightSlabs($weightSlabs);

        $stmt = $this->db->prepare("
            INSERT INTO fee_rules (
                fee_name, fee_category_id, fee_type, flat_fee_value, 
                application_states, payment_method, minimum_order_amount, 
                maximum_order_amount, active, weight_slabs
            ) VALUES (
                :fee_name, :fee_category_id, :fee_type, :flat_fee_value, 
                :application_states, :payment_method, :minimum_order_amount, 
                :maximum_order_amount, :active, :weight_slabs
            )
        ");
        
        $stmt->execute([
            'fee_name' => trim($data['fee_name']),
            'fee_category_id' => $data['fee_category_id'],
            'fee_type' => $data['fee_type'],
            'flat_fee_value' => $data['flat_fee_value'] ?? 0.00,
            'application_states' => json_encode($states),
            'payment_method' => $data['payment_method'] ?? 'Both',
            'minimum_order_amount' => !empty($data['minimum_order_amount']) ? $data['minimum_order_amount'] : null,
            'maximum_order_amount' => !empty($data['maximum_order_amount']) ? $data['maximum_order_amount'] : null,
            'active' => isset($data['active']) ? (int)$data['active'] : 1,
            'weight_slabs' => json_encode($weightSlabs)
        ]);

        return $this->db->lastInsertId();
    }

    public function update($id, array $data)
    {
        $states = array_map([self::class, 'normalizeState'], $data['application_states'] ?? []);
        $weightSlabs = $data['weight_slabs'] ?? [];
        
        // Validate weight slabs overlap
        $this->validateWeightSlabs($weightSlabs);

        $stmt = $this->db->prepare("
            UPDATE fee_rules SET
                fee_name = :fee_name,
                fee_category_id = :fee_category_id,
                fee_type = :fee_type,
                flat_fee_value = :flat_fee_value,
                application_states = :application_states,
                payment_method = :payment_method,
                minimum_order_amount = :minimum_order_amount,
                maximum_order_amount = :maximum_order_amount,
                active = :active,
                weight_slabs = :weight_slabs
            WHERE id = :id
        ");
        
        return $stmt->execute([
            'fee_name' => trim($data['fee_name']),
            'fee_category_id' => $data['fee_category_id'],
            'fee_type' => $data['fee_type'],
            'flat_fee_value' => $data['flat_fee_value'] ?? 0.00,
            'application_states' => json_encode($states),
            'payment_method' => $data['payment_method'] ?? 'Both',
            'minimum_order_amount' => !empty($data['minimum_order_amount']) ? $data['minimum_order_amount'] : null,
            'maximum_order_amount' => !empty($data['maximum_order_amount']) ? $data['maximum_order_amount'] : null,
            'active' => isset($data['active']) ? (int)$data['active'] : 1,
            'weight_slabs' => json_encode($weightSlabs),
            'id' => $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM fee_rules WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function validateWeightSlabs(array $slabs)
    {
        if (empty($slabs)) return;

        // Ensure numeric fields and check active slabs only
        $activeSlabs = [];
        foreach ($slabs as $slab) {
            $status = isset($slab['status']) ? (bool)$slab['status'] : true;
            if (!$status) continue;

            $min = (float)($slab['minWeight'] ?? 0);
            $max = (float)($slab['maxWeight'] ?? 0);
            $charge = (float)($slab['charge'] ?? 0);

            if ($min < 0 || $max < 0 || $charge < 0) {
                throw new \Exception("Negative values are not allowed in weight slabs.");
            }
            if ($max <= $min) {
                throw new \Exception("Max weight must be greater than min weight (Current: Min {$min} - Max {$max}).");
            }

            $activeSlabs[] = [
                'min' => $min,
                'max' => $max,
                'charge' => $charge
            ];
        }

        // Sort by min weight
        usort($activeSlabs, function ($a, $b) {
            return $a['min'] <=> $b['min'];
        });

        // Check for overlaps
        for ($i = 1; $i < count($activeSlabs); $i++) {
            if ($activeSlabs[$i]['min'] < $activeSlabs[$i - 1]['max']) {
                throw new \Exception("Weight slabs overlap detected: Slab range [{$activeSlabs[$i]['min']} - {$activeSlabs[$i]['max']}] overlaps with [{$activeSlabs[$i - 1]['min']} - {$activeSlabs[$i - 1]['max']}].");
            }
        }
    }
}
