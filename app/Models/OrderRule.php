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

            // Group active order rules by main category id and map linked secondary categories
            $mainRulesByCat = [];
            $allLinkedSecondaryCatIds = [];
            foreach ($rules as $rule) {
                $mainRulesByCat[(int)$rule['category_id']] = $rule;
                $secondRules = json_decode($rule['second_category_rules'] ?? '[]', true) ?? [];
                foreach ($secondRules as $secId => $secMin) {
                    $allLinkedSecondaryCatIds[(int)$secId] = (int)$rule['category_id'];
                }
            }

            // Determine if any category in the cart is governed by an Order Rule
            $hasOrderRuleInCart = false;
            foreach ($categoryTotals as $catId => $data) {
                $catId = (int)$catId;
                $catTotal = (float)($data['total'] ?? 0);
                if ($catTotal <= 0) continue;
                if (isset($mainRulesByCat[$catId]) || isset($allLinkedSecondaryCatIds[$catId])) {
                    $hasOrderRuleInCart = true;
                    break;
                }
            }

            // =============================================================
            // PRIORITY 1: ORDER RULES (Admin Order Rules Table)
            // If an Order Rule is configured for the cart, it takes full precedence.
            // When reached/satisfied, checkout is unlocked (no lower rules apply).
            // =============================================================
            if ($hasOrderRuleInCart) {
                $orderRuleErrors = [];
                $orderRuleFailingCats = [];

                // A. Check order rules for main categories in the cart
                foreach ($categoryTotals as $catId => $data) {
                    $catId = (int)$catId;
                    $catTotal = (float)($data['total'] ?? 0);
                    if ($catTotal <= 0) continue;

                    if (isset($mainRulesByCat[$catId])) {
                        $rule = $mainRulesByCat[$catId];
                        $minAmount = (float)$rule['min_amount'];
                        $catInfo = $catMap[$catId] ?? null;
                        $catName = $data['name'] ?? ($catInfo['name'] ?? "Category #$catId");

                        $secondRules = json_decode($rule['second_category_rules'] ?? '[]', true) ?? [];
                        $hasSecondaryItemsInCart = false;

                        // Check linked secondary category rules
                        foreach ($secondRules as $secCatId => $specialMin) {
                            $secCatId = (int)$secCatId;
                            $specialMin = (float)$specialMin;
                            $secCatName = $catMap[$secCatId]['name'] ?? "Category #$secCatId";
                            $secCatTotal = isset($categoryTotals[$secCatId]) ? (float)$categoryTotals[$secCatId]['total'] : 0;

                            if ($secCatTotal > 0) {
                                $hasSecondaryItemsInCart = true;
                                if ($catTotal < $minAmount) {
                                    $shortfall = $minAmount - $catTotal;
                                    $orderRuleErrors[] = "To buy items from <strong>" . htmlspecialchars($secCatName) . "</strong>, your order must contain at least <strong>₹" . number_format($minAmount) . "</strong> from <strong>" . htmlspecialchars($catName) . "</strong>. (Currently: ₹" . number_format($catTotal) . ", add ₹" . number_format($shortfall) . " more).";
                                    if (!in_array($catId, $orderRuleFailingCats)) {
                                        $orderRuleFailingCats[] = $catId;
                                    }
                                } elseif ($specialMin > 0 && $secCatTotal < $specialMin) {
                                    $secShortfall = $specialMin - $secCatTotal;
                                    $orderRuleErrors[] = "Minimum order for <strong>" . htmlspecialchars($secCatName) . "</strong> under this offer is <strong>₹" . number_format($specialMin) . "</strong>. (Currently: ₹" . number_format($secCatTotal) . ", add ₹" . number_format($secShortfall) . " more).";
                                    if (!in_array($secCatId, $orderRuleFailingCats)) {
                                        $orderRuleFailingCats[] = $secCatId;
                                    }
                                }
                            }
                        }

                        // If no secondary combo items in cart, check main category order rule minimum amount
                        if (!$hasSecondaryItemsInCart && $minAmount > 0 && $catTotal < $minAmount) {
                            $shortfall = $minAmount - $catTotal;
                            $orderRuleErrors[] = "Order rule for <strong>" . htmlspecialchars($catName) . "</strong> requires a minimum order amount of <strong>₹" . number_format($minAmount) . "</strong>. (Currently: ₹" . number_format($catTotal) . ", add ₹" . number_format($shortfall) . " more).";
                            if (!in_array($catId, $orderRuleFailingCats)) {
                                $orderRuleFailingCats[] = $catId;
                            }
                        }
                    }
                }

                // B. Check if secondary items exist in cart when main category is NOT in the cart
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
                                $orderRuleErrors[] = "To buy items from <strong>" . htmlspecialchars($secCatName) . "</strong>, your order must contain at least <strong>₹" . number_format($minAmount) . "</strong> from <strong>" . htmlspecialchars($mainCatName) . "</strong>.";
                                if (!in_array($mainCatId, $orderRuleFailingCats)) {
                                    $orderRuleFailingCats[] = $mainCatId;
                                }
                            }
                        }
                    }
                }

                // If Order Rule is failing, return errors. If reached/satisfied, unlock checkout immediately!
                return [
                    'errors' => array_values(array_unique($orderRuleErrors)),
                    'failing_categories' => array_values(array_unique($orderRuleFailingCats))
                ];
            }

            // =============================================================
            // PRIORITY 2: CATEGORY RULES (Category Minimum Order Value MOV)
            // Evaluated ONLY if NO Order Rule is set (or 0) for the cart categories.
            // When reached/satisfied, checkout is unlocked (no cart rule applies).
            // =============================================================
            $hasCategoryRuleInCart = false;
            foreach ($categoryTotals as $catId => $data) {
                $catId = (int)$catId;
                $catInfo = $catMap[$catId] ?? null;
                $catMov = !empty($catInfo['min_order_value']) ? (float)$catInfo['min_order_value'] : 0;
                if ($catMov > 0) {
                    $hasCategoryRuleInCart = true;
                    break;
                }
            }

            if ($hasCategoryRuleInCart) {
                $categoryRuleErrors = [];
                $categoryRuleFailingCats = [];

                foreach ($categoryTotals as $catId => $data) {
                    $catId = (int)$catId;
                    $catTotal = (float)($data['total'] ?? 0);
                    if ($catTotal <= 0) continue;

                    $catInfo = $catMap[$catId] ?? null;
                    $catName = $data['name'] ?? ($catInfo['name'] ?? "Category #$catId");
                    $catMov = !empty($catInfo['min_order_value']) ? (float)$catInfo['min_order_value'] : 0;

                    if ($catMov > 0 && $catTotal < $catMov) {
                        $shortfall = $catMov - $catTotal;
                        $categoryRuleErrors[] = "Minimum order value for <strong>" . htmlspecialchars($catName) . "</strong> is <strong>₹" . number_format($catMov) . "</strong>. (Currently: ₹" . number_format($catTotal) . ", add ₹" . number_format($shortfall) . " more).";
                        if (!in_array($catId, $categoryRuleFailingCats)) {
                            $categoryRuleFailingCats[] = $catId;
                        }
                    }
                }

                // If Category Rule is failing, return errors. If reached/satisfied, unlock checkout immediately!
                return [
                    'errors' => array_values(array_unique($categoryRuleErrors)),
                    'failing_categories' => array_values(array_unique($categoryRuleFailingCats))
                ];
            }

            // =============================================================
            // PRIORITY 3: CART RULES (Minimum Overall Cart Value)
            // Evaluated ONLY if NO Order Rule AND NO Category Rule are set (both 0 / unset).
            // =============================================================
            $cartRuleErrors = [];
            $cartRuleFailingCats = [];

            // A. Category-level overall cart minimum (min_cart_value)
            foreach ($categoryTotals as $catId => $data) {
                $catId = (int)$catId;
                $catTotal = (float)($data['total'] ?? 0);
                if ($catTotal <= 0) continue;

                $catInfo = $catMap[$catId] ?? null;
                $catName = $data['name'] ?? ($catInfo['name'] ?? "Category #$catId");
                $catCartMin = !empty($catInfo['min_cart_value']) ? (float)$catInfo['min_cart_value'] : 0;

                if ($catCartMin > 0 && $overallCartSubtotal < $catCartMin) {
                    $cartShortfall = $catCartMin - $overallCartSubtotal;
                    $cartRuleErrors[] = "Overall order cart value for orders containing <strong>" . htmlspecialchars($catName) . "</strong> must be at least <strong>₹" . number_format($catCartMin) . "</strong>. (Current Cart Total: ₹" . number_format($overallCartSubtotal) . ", add ₹" . number_format($cartShortfall) . " more).";
                    if (!in_array($catId, $cartRuleFailingCats)) {
                        $cartRuleFailingCats[] = $catId;
                    }
                }
            }

            // B. Global site-wide cart minimum limit (settings.min_order_cart_value)
            $globalCartMin = 0.0;
            $settStmt = $db->query("SELECT setting_value FROM settings WHERE setting_key = 'min_order_cart_value' LIMIT 1");
            if ($settStmt && ($settRow = $settStmt->fetch(\PDO::FETCH_ASSOC))) {
                $globalCartMin = (float)($settRow['setting_value'] ?? 0);
            }

            if ($globalCartMin > 0 && $overallCartSubtotal > 0 && $overallCartSubtotal < $globalCartMin) {
                $globalShortfall = $globalCartMin - $overallCartSubtotal;
                $cartRuleErrors[] = "Overall order cart value across all categories must be at least <strong>₹" . number_format($globalCartMin, 2) . "</strong>. (Current Cart Total: ₹" . number_format($overallCartSubtotal, 2) . ", add ₹" . number_format($globalShortfall, 2) . " more).";
            }

            return [
                'errors' => array_values(array_unique($cartRuleErrors)),
                'failing_categories' => array_values(array_unique($cartRuleFailingCats))
            ];

        } catch (\Throwable $e) {
            // Silently fallback on any exception
        }

        return [
            'errors' => [],
            'failing_categories' => []
        ];
    }
}
