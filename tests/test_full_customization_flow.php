<?php

require_once __DIR__ . '/../config/constants.php';
spl_autoload_register(function ($className) {
    $classPath = str_replace('\\', '/', $className);
    if (strpos($className, 'Core\\') === 0) {
        $classPath = 'core/' . substr($classPath, 5);
    } elseif (strpos($className, 'App\\') === 0) {
        $classPath = 'app/' . substr($classPath, 4);
    }
    $file = BASE_PATH . '/' . $classPath . '.php';
    if (file_exists($file)) require_once $file;
});

echo "=== STARTING FULL CUSTOMIZATION & FABRIC CONSUMPTION TEST SUITE ===\n\n";

$passed = 0;
$failed = 0;

function assertTest($condition, $testName) {
    global $passed, $failed;
    if ($condition) {
        echo " [PASS] " . $testName . "\n";
        $passed++;
    } else {
        echo " [FAIL] " . $testName . "\n";
        $failed++;
    }
}

$db = \Core\Database::getInstance();
$model = new \App\Models\FabricCustomization();
$service = new \App\Services\FabricCustomizationService();

// 1. Test Model: Get all rules
$allRules = $model->getAllWithProductInfo();
assertTest(is_array($allRules) && count($allRules) > 0, "Model: getAllWithProductInfo returns array of rules");

// 2. Test Model: Get by Fabric ID
$testFabricId = !empty($allRules) ? (int)$allRules[0]['fabric_id'] : 60001;
$rule = $model->getByFabricId($testFabricId);
assertTest($rule !== null && $rule['fabric_id'] == $testFabricId, "Model: getByFabricId retrieves rule for fabric #{$testFabricId}");
assertTest(!empty($rule['sizes']) && count($rule['sizes']) >= 1, "Model: rule #{$testFabricId} has sizes attached");

// 3. Test Service: Calculate Consumption (Normal Valid)
$calc = $service->calculateConsumption($rule, ['M' => 10, 'L' => 5], (float)$rule['fabric_stock']);
assertTest($calc['total_pieces'] === 15, "Service: Total pieces calculated correctly (10 + 5 = 15)");
// M: 10 * 2.0 = 20m, L: 5 * 2.2 = 11m -> Total base = 31m
assertTest($calc['base_consumption'] == 31.0, "Service: Base consumption is 31.0 meters (10*2.0 + 5*2.2)");
assertTest($calc['required_fabric'] == 31.0, "Service: Required fabric with 0% wastage is 31.0 meters");
assertTest($calc['is_valid'] === true, "Service: Calculation is valid when min pieces (10) and stock (100) are met");

// 4. Test Service: Calculate Consumption with Wastage Buffer
$ruleWithWastage = array_merge($rule, ['wastage_percentage' => 5.0]);
$calcWastage = $service->calculateConsumption($ruleWithWastage, ['M' => 10, 'L' => 5], 100.0);
// 31m + 5% of 31m (1.55m) = 32.55m
assertTest($calcWastage['wastage_amount'] == 1.55, "Service: Wastage buffer (5%) is 1.55 meters");
assertTest($calcWastage['required_fabric'] == 32.55, "Service: Total required fabric with wastage is 32.55 meters");

// 5. Test Service: Validation Fails on Low Pieces (< Minimum)
$calcLow = $service->calculateConsumption($rule, ['M' => 2], 100.0);
assertTest($calcLow['is_valid'] === false, "Service: Fails when total pieces (2) < minimum pieces (10)");
assertTest(count($calcLow['errors']) > 0, "Service: Returns human-friendly error message for low piece count");

// 6. Test Service: Validation Fails on Insufficient Inventory Stock (e.g. 3XL/4XL heavy consumption)
$calcStock = $service->calculateConsumption($rule, ['3XL' => 15, 'XXL' => 10], 35.0); // High consumption exceeding 35m stock
assertTest($calcStock['is_valid'] === false, "Service: Fails when larger sizes (3XL/XXL) require more fabric than 35m stock");
assertTest(strpos($calcStock['errors'][0], 'Fabric Stock Exceeded') !== false || strpos($calcStock['errors'][0], 'inventory') !== false, "Service: Returns explicit fabric stock shortage error message");
assertTest($calcStock['is_stock_sufficient'] === false, "Service: is_stock_sufficient flag is false when required > stock");

// 7. Test Service: Pricing Calculation
$pricing = $service->calculatePricing((float)$rule['fabric_price'], 31.0, 0.0, 15, 5.0);
$expectedFabricCost = round(31.0 * (float)$rule['fabric_price'], 2);
assertTest($pricing['fabric_cost'] == $expectedFabricCost, "Service: Fabric cost calculation is accurate");
assertTest($pricing['gst_rate'] == 5.0, "Service: GST rate applied is 5%");
$expectedGst = round($expectedFabricCost * 0.05, 2);
assertTest($pricing['gst_amount'] == $expectedGst, "Service: GST amount is calculated accurately");
assertTest($pricing['total'] == ($expectedFabricCost + $expectedGst), "Service: Grand total matches subtotal + GST");

// 8. Test Model: Get Customizable Fabrics for Storefront
$storefrontFabrics = $model->getCustomizableFabrics();
assertTest(is_array($storefrontFabrics) && count($storefrontFabrics) > 0, "Model: getCustomizableFabrics returns list of active customizable fabrics");
assertTest(isset($storefrontFabrics[0]['fabric_name']) && isset($storefrontFabrics[0]['sizes']), "Model: Storefront fabric item has name and sizes breakdown");

// 9. Test Order Flow: Option 1 - Submit Request Order
$userId = 1; // Super admin / test user
$reqOrderNumber = 'GRM-REQ-TEST-' . rand(1000, 9999);
$customizationData = [
    'customization_id'   => $rule['id'],
    'program_name'       => $rule['program_name'],
    'garment_type'       => $rule['garment_type'],
    'fabric_name'        => $rule['fabric_name'],
    'total_pieces'       => 15,
    'required_fabric'    => 31.0,
    'order_mode'         => 'request_quote',
    'sizes'              => $calc['breakdown']
];

$db->beginTransaction();
$stmt = $db->prepare("
    INSERT INTO orders (user_id, order_number, total_amount, grand_total, shipping_address, status, payment_status, notes, created_at)
    VALUES (?, ?, ?, ?, ?, 'placed', 'request_pending', 'Test Custom Request', NOW())
");
$stmt->execute([$userId, $reqOrderNumber, $pricing['subtotal'], $pricing['total'], '123 Textile Mill Road']);
$orderId = (int)$db->lastInsertId();

$itemStmt = $db->prepare("
    INSERT INTO order_items (order_id, product_id, customization_id, customization_data, quantity, unit_price, total_price)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");
$itemStmt->execute([$orderId, $rule['fabric_id'], $rule['id'], json_encode($customizationData), 15, $rule['fabric_price'], $pricing['total']]);
$db->commit();

assertTest($orderId > 0, "Order: Option 1 - Request Order inserted successfully with ID #{$orderId}");

// 10. Test Order Model: Retrieve Custom Orders for Admin Dashboard
$orderModel = new \App\Models\Order();
$customOrders = $orderModel->getCustomOrders();
assertTest(is_array($customOrders) && count($customOrders) > 0, "OrderModel: getCustomOrders returns custom orders list");
$foundTestOrder = false;
foreach ($customOrders as $co) {
    if ($co['order_number'] === $reqOrderNumber) {
        $foundTestOrder = true;
        $customData = $co['items'][0]['customization_parsed'] ?? null;
        assertTest(!empty($customData['sizes']), "OrderModel: Custom order contains deserialized sizes distribution");
        assertTest(($customData['total_pieces'] ?? 0) == 15, "OrderModel: Custom order total pieces matches 15");
        assertTest($co['payment_status'] === 'request_pending', "OrderModel: Request order has payment_status = 'request_pending'");
        break;
    }
}
assertTest($foundTestOrder, "OrderModel: Newly created custom request order #{$reqOrderNumber} found in admin list");

// 11. Test Custom Order Status Counts
$counts = $orderModel->getCustomOrderStatusCounts();
assertTest(isset($counts['all']) && isset($counts['placed']), "OrderModel: getCustomOrderStatusCounts returns status tallies");

// 12. Test Razorpay Order Signature Verification
$razorpayService = new \App\Services\RazorpayService('sample_key_id', 'sample_secret_key');
$testOrderId = 'order_mock_12345';
$testPaymentId = 'pay_mock_98765';
$expectedSig = hash_hmac('sha256', $testOrderId . '|' . $testPaymentId, 'sample_secret_key');
$sigVerified = $razorpayService->verifySignature($testOrderId, $testPaymentId, $expectedSig);
assertTest($sigVerified === true, "RazorpayService: HMAC SHA256 signature verification passes for valid signature");

$badSigVerified = $razorpayService->verifySignature($testOrderId, $testPaymentId, 'invalid_signature_xyz');
assertTest($badSigVerified === false, "RazorpayService: Signature verification rejects tampered signature");

// Clean up test order
$db->prepare("DELETE FROM order_items WHERE order_id = ?")->execute([$orderId]);
$db->prepare("DELETE FROM orders WHERE id = ?")->execute([$orderId]);
echo "\nTest database records cleaned up.\n";

echo "\n=======================================================\n";
echo "TEST RESULTS: {$passed} PASSED, {$failed} FAILED\n";
echo "=======================================================\n";
