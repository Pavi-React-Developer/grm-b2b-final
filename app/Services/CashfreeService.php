<?php
namespace App\Services;

/**
 * CashfreeService
 *
 * Wraps the Cashfree Refund REST API (v2022-09-01) using PHP cURL.
 * Does NOT use the cashfree-pg PHP SDK (which requires Composer) —
 * instead uses plain cURL so it works in this project's architecture.
 *
 * Credentials are read from .env via the constants set in config/constants.php.
 * Add these to your .env if not already present:
 *   CASHFREE_APP_ID=...
 *   CASHFREE_SECRET_KEY=...
 *   CASHFREE_ENV=sandbox   (or "production")
 */
class CashfreeService
{
    private string $appId;
    private string $secretKey;
    private string $baseUrl;
    private string $apiVersion = '2022-09-01';

    public function __construct()
    {
        $envPath = dirname(dirname(__DIR__)) . '/.env';
        $env     = file_exists($envPath) ? parse_ini_file($envPath) : [];

        $this->appId     = $env['CASHFREE_APP_ID']     ?? '';
        $this->secretKey = $env['CASHFREE_SECRET_KEY'] ?? '';
        $cfEnv           = strtolower($env['CASHFREE_ENV'] ?? 'sandbox');

        $this->baseUrl = ($cfEnv === 'production')
            ? 'https://api.cashfree.com/pg'
            : 'https://sandbox.cashfree.com/pg';
    }

    // ---------------------------------------------------------------------------
    // Public API
    // ---------------------------------------------------------------------------

    /**
     * Initiate a refund via Cashfree.
     *
     * @param string $cashfreeOrderId  The Cashfree order_id (NOT our internal order number)
     * @param string $refundId         Unique refund ID we generate (e.g. "REFUND-42")
     * @param float  $amount           Amount to refund in INR
     * @param string $note             Short note visible in Cashfree dashboard
     *
     * @return array {
     *   success: bool,
     *   refund_id: string|null,
     *   status: string|null,
     *   message: string,
     *   raw: array|null
     * }
     */
    public function initiateRefund(
        string $cashfreeOrderId,
        string $refundId,
        float  $amount,
        string $note = 'Order cancellation refund'
    ): array {
        if (empty($this->appId) || empty($this->secretKey)) {
            return $this->errorResponse('Cashfree credentials not configured.');
        }

        $endpoint = "{$this->baseUrl}/orders/{$cashfreeOrderId}/refunds";

        $payload = [
            'refund_amount' => round($amount, 2),
            'refund_id'     => $refundId,
            'refund_note'   => substr($note, 0, 255),
        ];

        $response = $this->request('POST', $endpoint, $payload);

        if (!$response['success']) {
            return $response;
        }

        $data = $response['data'];

        // Cashfree returns refund_status: SUCCESS | PENDING | CANCELLED | ONHOLD
        $cfStatus = strtoupper($data['refund_status'] ?? 'PENDING');

        return [
            'success'   => true,
            'refund_id' => $data['refund_id']     ?? $refundId,
            'status'    => $cfStatus,
            'message'   => 'Refund initiated successfully.',
            'raw'       => $data,
        ];
    }

    /**
     * Poll the status of an existing refund.
     *
     * @param string $cashfreeOrderId Cashfree order ID
     * @param string $refundId        Our refund ID
     * @return array Same shape as initiateRefund()
     */
    public function getRefundStatus(string $cashfreeOrderId, string $refundId): array
    {
        if (empty($this->appId) || empty($this->secretKey)) {
            return $this->errorResponse('Cashfree credentials not configured.');
        }

        $endpoint = "{$this->baseUrl}/orders/{$cashfreeOrderId}/refunds/{$refundId}";
        $response = $this->request('GET', $endpoint);

        if (!$response['success']) {
            return $response;
        }

        $data     = $response['data'];
        $cfStatus = strtoupper($data['refund_status'] ?? 'PENDING');

        return [
            'success'   => true,
            'refund_id' => $data['refund_id'] ?? $refundId,
            'status'    => $cfStatus,
            'message'   => 'Status fetched.',
            'raw'       => $data,
        ];
    }

    // ---------------------------------------------------------------------------
    // Internal helpers
    // ---------------------------------------------------------------------------

    /**
     * Execute a cURL request to the Cashfree API.
     *
     * @return array {success: bool, data: array|null, message: string}
     */
    private function request(string $method, string $url, array $body = []): array
    {
        $ch = curl_init();

        $headers = [
            'Content-Type: application/json',
            'x-client-id: '     . $this->appId,
            'x-client-secret: ' . $this->secretKey,
            'x-api-version: '   . $this->apiVersion,
        ];

        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_CAINFO         => dirname(dirname(__DIR__)) . '/cacert.pem',
        ]);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST,       true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }

        $rawBody  = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr  = curl_error($ch);
        curl_close($ch);

        if ($curlErr) {
            error_log("[CashfreeService] cURL error: {$curlErr}");
            return $this->errorResponse("Network error: {$curlErr}");
        }

        $decoded = json_decode($rawBody, true);

        if ($httpCode >= 200 && $httpCode < 300) {
            return ['success' => true, 'data' => $decoded, 'message' => 'OK'];
        }

        $cfMessage = $decoded['message'] ?? $decoded['error'] ?? "HTTP {$httpCode}";
        error_log("[CashfreeService] API error {$httpCode}: {$cfMessage} | URL: {$url}");
        return $this->errorResponse("Cashfree API error: {$cfMessage}");
    }

    private function errorResponse(string $message): array
    {
        return [
            'success'   => false,
            'refund_id' => null,
            'status'    => null,
            'message'   => $message,
            'raw'       => null,
        ];
    }
}
