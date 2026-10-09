<?php
namespace App\Models;

use Core\Database;

class OrderRule
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll()
    {
        $sql = "
            SELECT r.*, c.name as category_name 
            FROM order_rules r
            JOIN categories c ON r.category_id = c.id
            ORDER BY c.name ASC
        ";
        return $this->db->query($sql)->fetchAll();
    }
    
    public function getActiveRules()
    {
        $sql = "
            SELECT r.*, c.name as category_name 
            FROM order_rules r
            JOIN categories c ON r.category_id = c.id
            WHERE r.status = 'active'
        ";
        return $this->db->query($sql)->fetchAll();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM order_rules WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create(array $data)
    {
        // Check for duplicates
        if ($this->ruleExists($data['category_id'])) {
            throw new \Exception("An order rule already exists for this main category.");
        }

        $sql = "INSERT INTO order_rules (category_id, second_category_rules, min_amount, status) 
                VALUES (:category_id, :second_category_rules, :min_amount, :status)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'category_id' => $data['category_id'],
            'second_category_rules' => $data['second_category_rules'] ?: null,
            'min_amount' => $data['min_amount'],
            'status' => $data['status'] ?? 'active'
        ]);
        
        return $this->db->lastInsertId();
    }

    public function update($id, array $data)
    {
        // Check for duplicates if category changed
        if ($this->ruleExists($data['category_id'], $id)) {
            throw new \Exception("An order rule already exists for this main category.");
        }

        $sql = "UPDATE order_rules 
                SET category_id = :category_id, 
                    second_category_rules = :second_category_rules, 
                    min_amount = :min_amount, 
                    status = :status 
                WHERE id = :id";
                
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'category_id' => $data['category_id'],
            'second_category_rules' => $data['second_category_rules'] ?: null,
            'min_amount' => $data['min_amount'],
            'status' => $data['status'] ?? 'active',
            'id' => $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM order_rules WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    private function ruleExists($categoryId, $excludeId = null)
    {
        $sql = "SELECT id FROM order_rules WHERE category_id = :category_id";
        $params = ['category_id' => $categoryId];
        
        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch() !== false;
    }

    public static function validateCartRules(array $categoryTotals, float $cartSubtotal = 0): array
    {
        $db = Database::getInstance();
        $errors = [];
        $failingCategories = [];

        try {
            $ruleModel = new self();
            $rules = $ruleModel->getActiveRules();

            // Calculate overall cart total
            $overallCartSubtotal = $cartSubtotal > 0 ? $cartSubtotal : 0;
            if ($overallCartSubtotal <= 0) {
                foreach ($categoryTotals as $catData) {
                    $overallCartSubtotal += (float)($catData['total'] ?? 0);
                }
            }

            // Fetch category details map (name, min_order_value, min_cart_value)
            $catStmt = $db->query("SELECT id, name, min_order_value, min_cart_value FROM categories");
            $categories = $catStmt ? $catStmt->fetchAll(\PDO::FETCH_ASSOC) : [];
            $catMap = [];
            foreach ($categories as $c) {
                $catMap[(int)$c['id']] = $c;
            }

            // Group active order rules by main category id
            $mainRulesByCat = [];
            foreach ($rules as $rule) {
                $mainRulesByCat[(int)$rule['category_id']] = $rule;
            }

            // 1. Validate each category present in cart
            foreach ($categoryTotals as $catId => $data) {
                $catId = (int)$catId;
                $catTotal = (float)($data['total'] ?? 0);
                if ($catTotal <= 0) continue;

                $catInfo = $catMap[$catId] ?? null;
                $catName = $data['name'] ?? ($catInfo['name'] ?? "Category #$catId");
                $catMov = !empty($catInfo['min_order_value']) ? (float)$catInfo['min_order_value'] : 0;
                $catCartMin = !empty($catInfo['min_cart_value']) ? (float)$catInfo['min_cart_value'] : 0;

                // Priority 1: If an active Order Rule exists for this main category
                if (isset($mainRulesByCat[$catId])) {
                    $rule = $mainRulesByCat[$catId];
                    $minAmount = (float)$rule['min_amount'];

                    // Enforce main category minimum amount
                    if ($minAmount > 0 && $catTotal < $minAmount) {
                        $shortfall = $minAmount - $catTotal;
                        $errors[] = "Minimum order amount for <strong>" . htmlspecialchars($catName) . "</strong> is <strong>₹" . number_format($minAmount) . "</strong>. (Currently: ₹" . number_format($catTotal) . ", add ₹" . number_format($shortfall) . " more).";
                        if (!in_array($catId, $failingCategories)) {
                            $failingCategories[] = $catId;
                        }
                    }

                    // Check linked secondary category rules
                    $secondRules = json_decode($rule['second_category_rules'] ?? '[]', true) ?? [];
                    foreach ($secondRules as $secCatId => $specialMin) {
                        $secCatId = (int)$secCatId;
                        $specialMin = (float)$specialMin;
                        $secCatName = $catMap[$secCatId]['name'] ?? "Category #$secCatId";
                        $secCatTotal = isset($categoryTotals[$secCatId]) ? (float)$categoryTotals[$secCatId]['total'] : 0;

                        if ($secCatTotal > 0) {
                            if ($catTotal < $minAmount) {
                                $shortfall = $minAmount - $catTotal;
                                $errors[] = "To buy items from <strong>" . htmlspecialchars($secCatName) . "</strong>, your order must contain at least <strong>₹" . number_format($minAmount) . "</strong> from <strong>" . htmlspecialchars($catName) . "</strong>. (Currently: ₹" . number_format($catTotal) . ", add ₹" . number_format($shortfall) . " more).";
                                if (!in_array($catId, $failingCategories)) {
                                    $failingCategories[] = $catId;
                                }
                            } elseif ($specialMin > 0 && $secCatTotal < $specialMin) {
                                $secShortfall = $specialMin - $secCatTotal;
                                $errors[] = "Minimum order for <strong>" . htmlspecialchars($secCatName) . "</strong> under this offer is <strong>₹" . number_format($specialMin) . "</strong>. (Currently: ₹" . number_format($secCatTotal) . ", add ₹" . number_format($secShortfall) . " more).";
                                if (!in_array($secCatId, $failingCategories)) {
                                    $failingCategories[] = $secCatId;
                                }
                            }
                        }
                    }
                } else {
                    // Priority 2: No Order Rule set -> Fallback to Category MOV (Category specific minimum)
                    if ($catMov > 0 && $catTotal < $catMov) {
                        $shortfall = $catMov - $catTotal;
                        $errors[] = "Minimum order value for <strong>" . htmlspecialchars($catName) . "</strong> is <strong>₹" . number_format($catMov) . "</strong>. (Currently: ₹" . number_format($catTotal) . ", add ₹" . number_format($shortfall) . " more).";
                        if (!in_array($catId, $failingCategories)) {
                            $failingCategories[] = $catId;
                        }
                    }
                }

                // Overall Cart Limit Amount Check: If Category requires a minimum total order cart value
                if ($catCartMin > 0 && $overallCartSubtotal < $catCartMin) {
                    $cartShortfall = $catCartMin - $overallCartSubtotal;
                    $errors[] = "Overall order cart value for orders containing <strong>" . htmlspecialchars($catName) . "</strong> must be at least <strong>₹" . number_format($catCartMin) . "</strong>. (Current Cart Total: ₹" . number_format($overallCartSubtotal) . ", add ₹" . number_format($cartShortfall) . " more).";
                    if (!in_array($catId, $failingCategories)) {
                        $failingCategories[] = $catId;
                    }
                }
            }

            // 2. Also check if user added secondary items belonging to a rule whose main category is NOT in the cart
            foreach ($rules as $rule) {
                $mainCatId = (int)$rule['category_id'];
                $minAmount = (float)$rule['min_amount'];
                $mainCatTotal = isset($categoryTotals[$mainCatId]) ? (float)$categoryTotals[$mainCatId]['total'] : 0;
                $mainCatName = $rule['category_name'] ?? ($catMap[$mainCatId]['name'] ?? "Category #$mainCatId");

                if ($mainCatTotal <= 0) {
                    $secondRules = json_decode($rule['second_category_rules'] ?? '[]', true) ?? [];
                    foreach ($secondRules as $secCatId => $specialMin) {
                        $secCatId = (int)$secCatId;
                        $secCatTotal = isset($categoryTotals[$secCatId]) ? (float)$categoryTotals[$secCatId]['total'] : 0;
                        if ($secCatTotal > 0) {
                            $secCatName = $catMap[$secCatId]['name'] ?? "Category #$secCatId";
                            $errors[] = "To buy items from <strong>" . htmlspecialchars($secCatName) . "</strong>, your order must contain at least <strong>₹" . number_format($minAmount) . "</strong> from <strong>" . htmlspecialchars($mainCatName) . "</strong>.";
                            if (!in_array($mainCatId, $failingCategories)) {
                                $failingCategories[] = $mainCatId;
                            }
                        }
                    }
                }
            }

            // 3. Overall Global Cart Minimum Limit across all categories
            $globalCartMin = 0.0;
            $settStmt = $db->query("SELECT setting_value FROM settings WHERE setting_key = 'min_order_cart_value' LIMIT 1");
            if ($settStmt && ($settRow = $settStmt->fetch(\PDO::FETCH_ASSOC))) {
                $globalCartMin = (float)($settRow['setting_value'] ?? 0);
            }

            if ($globalCartMin > 0 && $overallCartSubtotal > 0 && $overallCartSubtotal < $globalCartMin) {
                $globalShortfall = $globalCartMin - $overallCartSubtotal;
                $errors[] = "Overall order cart value across all categories must be at least <strong>₹" . number_format($globalCartMin, 2) . "</strong>. (Current Cart Total: ₹" . number_format($overallCartSubtotal, 2) . ", add ₹" . number_format($globalShortfall, 2) . " more).";
            }

        } catch (\Throwable $e) {
            // Silently fallback on any exception
        }

        return [
            'errors' => array_values(array_unique($errors)),
            'failing_categories' => array_values(array_unique($failingCategories))
        ];
    }
}
