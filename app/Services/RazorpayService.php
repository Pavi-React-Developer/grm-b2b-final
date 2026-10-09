<?php
namespace App\Services;

/**
 * RazorpayService
 * 
 * Handles Razorpay API integrations (Order Creation, Payment Verification, Refunds)
 * using direct PHP cURL REST API and HMAC SHA256 signature verification.
 */
class RazorpayService
{
    private string $keyId;
    private string $keySecret;
    private string $baseUrl = 'https://api.razorpay.com/v1';

    public function __construct(?string $keyId = null, ?string $keySecret = null)
    {
        $this->keyId = $keyId ?? (defined('RAZORPAY_KEY_ID') ? RAZORPAY_KEY_ID : '');
        $this->keySecret = $keySecret ?? (defined('RAZORPAY_KEY_SECRET') ? RAZORPAY_KEY_SECRET : '');
    }

    /**
     * Create a Razorpay Order
     *
     * @param float  $amountInRupees  Order amount in INR (e.g. 500.50)
     * @param string $receipt         Internal order number / reference
     * @param array  $notes           Optional metadata notes
     * @param string $currency        Currency code (default INR)
     * @return array ['success' => bool, 'order' => array, 'error' => string]
     */
    public function createOrder(float $amountInRupees, string $receipt, array $notes = [], string $currency = 'INR'): array
    {
        if (empty($this->keyId) || empty($this->keySecret)) {
            return [
                'success' => false,
                'error' => 'Razorpay credentials not configured. Please set RAZORPAY_KEY_ID and RAZORPAY_KEY_SECRET.'
            ];
        }

        // Razorpay accepts amount in the smallest currency sub-unit (paise for INR)
        $amountInPaise = (int)round($amountInRupees * 100);

        $payload = [
            'amount'   => $amountInPaise,
            'currency' => $currency,
            'receipt'  => substr($receipt, 0, 40),
            'notes'    => $notes
        ];

        $response = $this->request('POST', '/orders', $payload);

        if (!empty($response['id'])) {
            return [
                'success' => true,
                'order'   => $response
            ];
        }

        $errorMsg = $response['error']['description'] ?? ($response['message'] ?? 'Unable to create Razorpay order.');
        return [
            'success' => false,
            'error'   => $errorMsg,
            'raw'     => $response
        ];
    }

    /**
     * Verify payment signature returned by Razorpay Checkout
     *
     * @param string $razorpayOrderId
     * @param string $razorpayPaymentId
     * @param string $razorpaySignature
     * @return bool
     */
    public function verifySignature(string $razorpayOrderId, string $razorpayPaymentId, string $razorpaySignature): bool
    {
        if (empty($this->keySecret) || empty($razorpayOrderId) || empty($razorpayPaymentId) || empty($razorpaySignature)) {
            return false;
        }

        $payload = $razorpayOrderId . '|' . $razorpayPaymentId;
        $expectedSignature = hash_hmac('sha256', $payload, $this->keySecret);

        return hash_equals($expectedSignature, $razorpaySignature);
    }

    /**
     * Fetch payment details from Razorpay
     *
     * @param string $paymentId
     * @return array
     */
    public function fetchPayment(string $paymentId): array
    {
        return $this->request('GET', '/payments/' . urlencode($paymentId));
    }

    /**
     * Fetch order details from Razorpay
     *
     * @param string $orderId
     * @return array
     */
    public function fetchOrder(string $orderId): array
    {
        return $this->request('GET', '/orders/' . urlencode($orderId));
    }

    /**
     * Create a refund for a payment
     *
     * @param string     $paymentId
     * @param float|null $amountInRupees Optional partial refund amount in INR
     * @param array      $notes
     * @return array
     */
    public function createRefund(string $paymentId, ?float $amountInRupees = null, array $notes = []): array
    {
        $payload = ['notes' => $notes];
        if ($amountInRupees !== null && $amountInRupees > 0) {
            $payload['amount'] = (int)round($amountInRupees * 100);
        }

        return $this->request('POST', '/payments/' . urlencode($paymentId) . '/refund', $payload);
    }

    /**
     * Execute cURL request to Razorpay REST API
     */
    private function request(string $method, string $path, array $data = []): array
    {
        $url = $this->baseUrl . $path;
        $ch = curl_init();

        $headers = [
            'Content-Type: application/json',
            'Accept: application/json'
        ];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->keyId . ':' . $this->keySecret);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        } elseif ($method !== 'GET') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            if (!empty($data)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        }

        $res = curl_exec($ch);
        $err = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($err) {
            error_log("[RazorpayService] cURL error: " . $err);
            return ['error' => ['description' => 'cURL Error: ' . $err]];
        }

        $decoded = json_decode($res, true);
        if (!is_array($decoded)) {
            error_log("[RazorpayService] Invalid JSON response (HTTP {$httpCode}): " . $res);
            return ['error' => ['description' => 'Invalid JSON response from Razorpay server (HTTP ' . $httpCode . ')']];
        }

        return $decoded;
    }
}
