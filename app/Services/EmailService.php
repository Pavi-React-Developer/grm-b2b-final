<?php
namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * EmailService
 *
 * Sends transactional HTML emails with attachment support (e.g., invoices).
 */
class EmailService
{
    private string $fromEmail;
    private string $fromName;
    private array $envVars;

    public function __construct()
    {
        $envPath = dirname(dirname(__DIR__)) . '/.env';
        $this->envVars = file_exists($envPath) ? parse_ini_file($envPath) : [];

        $this->fromEmail = $this->envVars['MAIL_FROM_ADDRESS'] ?? $this->envVars['SMTP_FROM'] ?? 'noreply@grmb2b.com';
        $this->fromName  = $this->envVars['MAIL_FROM_NAME']    ?? 'GRM B2B';
    }

    // ---------------------------------------------------------------------------
    // Public methods
    // ---------------------------------------------------------------------------

    /**
     * Send generic HTML email.
     */
    public function sendEmail(string $toEmail, string $subject, string $htmlBody, string $toName = ''): bool
    {
        return $this->send($toEmail, $toName ?: $toEmail, $subject, $htmlBody);
    }

    /**
     * Send OTP Verification Email.
     */
    public function sendOtpEmail(string $toEmail, string $otp, string $name = 'User'): bool
    {
        $subject = "Your OTP for Registration - GRM B2B";
        $body = "
            <div style='font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif; max-width: 540px; margin: 0 auto; padding: 28px; background: #ffffff; border: 1px solid #FDBFDD; border-radius: 16px; box-shadow: 0 4px 12px rgba(242, 89, 150, 0.08);'>
                <div style='text-align: center; margin-bottom: 20px;'>
                    <span style='display: inline-block; background: #fdf2f7; color: #F25996; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; padding: 4px 12px; border-radius: 9999px; border: 1px solid #FDBFDD;'>GRM B2B Verification</span>
                    <h2 style='color: #111827; font-size: 22px; font-weight: 800; margin: 12px 0 6px;'>Email Verification Code</h2>
                </div>
                <p style='color: #374151; font-size: 14px; line-height: 1.6; margin: 0 0 12px;'>Hello <strong>" . htmlspecialchars($name) . "</strong>,</p>
                <p style='color: #4b5563; font-size: 14px; line-height: 1.6; margin: 0 0 20px;'>Thank you for registering with <strong>GRM B2B</strong>. Please use the following One-Time Password (OTP) to complete your account verification:</p>
                <div style='background: linear-gradient(135deg, #fdf2f7 0%, #fff 100%); border: 2px dashed #FDBFDD; padding: 20px; text-align: center; border-radius: 12px; margin: 20px 0;'>
                    <span style='font-size: 34px; font-weight: 800; letter-spacing: 8px; color: #F25996; font-family: monospace;'>" . htmlspecialchars($otp) . "</span>
                </div>
                <p style='color: #6b7280; font-size: 13px; text-align: center; margin: 12px 0 0;'>This OTP is valid for your registration. Please do not share this code with anyone.</p>
                <hr style='border: none; border-top: 1px solid #f3f4f6; margin: 24px 0;' />
                <p style='color: #9ca3af; font-size: 11px; text-align: center; margin: 0;'>&copy; " . date('Y') . " GRM B2B. All rights reserved.</p>
            </div>
        ";
        return $this->send($toEmail, $name, $subject, $body);
    }

    /**
     * Email #1 — Cancellation requested by user.
     */
    public function sendCancellationRequested(array $user, array $order): bool
    {
        $subject = "Cancellation Request Received — Order #{$order['order_number']}";
        $body    = $this->buildCancellationRequestedHtml($user, $order);
        return $this->send($user['email'], $user['name'], $subject, $body);
    }

    /**
     * Email #2 — Refund successfully processed by Cashfree.
     */
    public function sendRefundProcessed(array $user, array $order, float $amount): bool
    {
        $subject = "Refund of ₹" . number_format($amount, 2) . " Processed — Order #{$order['order_number']}";
        $body    = $this->buildRefundProcessedHtml($user, $order, $amount);
        return $this->send($user['email'], $user['name'], $subject, $body);
    }

    /**
     * Email #3 — Refund request rejected by admin.
     */
    public function sendRefundRejected(array $user, array $order, string $reason): bool
    {
        $subject = "Update on Your Cancellation Request — Order #{$order['order_number']}";
        $body    = $this->buildRefundRejectedHtml($user, $order, $reason);
        return $this->send($user['email'], $user['name'], $subject, $body);
    }

    /**
     * Email #4 — Order Packed with Dynamic Invoice Attachment & Packing Video attachments/links.
     */
    public function sendOrderPackedWithInvoice(array $user, array $order, string $pdfBinary, string $pdfFilename, ?string $video1Url = null, ?string $video2Url = null): bool
    {
        $subject = "Your Order #{$order['order_number']} is Packed & Tax Invoice Attached 📦";
        $body    = $this->buildOrderPackedHtml($user, $order, $video1Url, $video2Url);

        $attachments = [];
        if (!empty($pdfBinary)) {
            $attachments[] = [
                'type'     => 'string',
                'binary'   => $pdfBinary,
                'filename' => $pdfFilename,
                'mime'     => 'application/pdf'
            ];
        }

        $basePath = defined('BASE_PATH') ? BASE_PATH : dirname(dirname(__DIR__));

        // Attach Video 1 file directly if present on server (up to 25MB)
        if (!empty($video1Url)) {
            $filePath1 = $basePath . '/' . ltrim($video1Url, '/');
            if (!file_exists($filePath1) && file_exists($basePath . '/public/' . ltrim($video1Url, '/'))) {
                $filePath1 = $basePath . '/public/' . ltrim($video1Url, '/');
            }
            if (file_exists($filePath1) && filesize($filePath1) <= 25 * 1024 * 1024) {
                $ext1 = strtolower(pathinfo($filePath1, PATHINFO_EXTENSION));
                $mime1 = ($ext1 === 'mp4') ? 'video/mp4' : (($ext1 === 'webm') ? 'video/webm' : 'video/quicktime');
                $attachments[] = [
                    'type'     => 'path',
                    'path'     => $filePath1,
                    'filename' => "Packing_Video_1_{$order['order_number']}." . $ext1,
                    'mime'     => $mime1
                ];
            }
        }

        // Attach Video 2 file directly if present on server (up to 25MB)
        if (!empty($video2Url)) {
            $filePath2 = $basePath . '/' . ltrim($video2Url, '/');
            if (!file_exists($filePath2) && file_exists($basePath . '/public/' . ltrim($video2Url, '/'))) {
                $filePath2 = $basePath . '/public/' . ltrim($video2Url, '/');
            }
            if (file_exists($filePath2) && filesize($filePath2) <= 25 * 1024 * 1024) {
                $ext2 = strtolower(pathinfo($filePath2, PATHINFO_EXTENSION));
                $mime2 = ($ext2 === 'mp4') ? 'video/mp4' : (($ext2 === 'webm') ? 'video/webm' : 'video/quicktime');
                $attachments[] = [
                    'type'     => 'path',
                    'path'     => $filePath2,
                    'filename' => "Packing_Video_2_{$order['order_number']}." . $ext2,
                    'mime'     => $mime2
                ];
            }
        }

        return $this->send($user['email'], $user['name'], $subject, $body, $attachments);
    }

    // ---------------------------------------------------------------------------
    // Email HTML templates
    // ---------------------------------------------------------------------------

    private function buildOrderPackedHtml(array $user, array $order, ?string $video1Url, ?string $video2Url): string
    {
        $name     = htmlspecialchars($user['name']);
        $orderNum = htmlspecialchars($order['order_number']);
        $amount   = number_format((float)($order['grand_total'] ?? $order['total_amount']), 2);
        $date     = date('d M Y, h:i A');

        $baseUrl = defined('BASE_URL') ? BASE_URL : '';

        $videoSection = '';
        if ($video1Url || $video2Url) {
            $videoSection .= "
            <div style='background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:20px;margin:24px 0;'>
                <h3 style='margin:0 0 10px;color:#15803d;font-size:15px;font-weight:bold;'>📹 Packing &amp; Quality Check Videos</h3>
                <p style='margin:0 0 12px;color:#166534;font-size:13px;line-height:1.5;'>
                    Our warehouse team has recorded the packaging and quality seal of your order. 
                    <strong>The video files are attached directly to this email</strong> for instant playback on any smartphone or computer.
                </p>
                <table cellpadding='0' cellspacing='0' border='0' style='margin-top:14px;'>
                    <tr>";

            if ($video1Url) {
                $fullV1 = (strpos($video1Url, 'http') === 0) ? $video1Url : ($baseUrl . '/' . ltrim($video1Url, '/'));
                $videoSection .= "
                        <td style='padding-right:12px;padding-bottom:8px;' valign='top'>
                            <a href='{$fullV1}' target='_blank' style='display:inline-block;background:#15803d;color:#ffffff;text-decoration:none;padding:10px 16px;border-radius:6px;font-size:12.5px;font-weight:bold;white-space:nowrap;'>
                                ▶ View / Download Video 1
                            </a>
                        </td>";
            }
            if ($video2Url) {
                $fullV2 = (strpos($video2Url, 'http') === 0) ? $video2Url : ($baseUrl . '/' . ltrim($video2Url, '/'));
                $videoSection .= "
                        <td style='padding-right:12px;padding-bottom:8px;' valign='top'>
                            <a href='{$fullV2}' target='_blank' style='display:inline-block;background:#047857;color:#ffffff;text-decoration:none;padding:10px 16px;border-radius:6px;font-size:12.5px;font-weight:bold;white-space:nowrap;'>
                                ▶ View / Download Video 2
                            </a>
                        </td>";
            }

            $orderHistoryUrl = $baseUrl . '/dashboard/orders';
            $videoSection .= "
                        <td style='padding-bottom:8px;' valign='top'>
                            <a href='{$orderHistoryUrl}' target='_blank' style='display:inline-block;background:#0369a1;color:#ffffff;text-decoration:none;padding:10px 16px;border-radius:6px;font-size:12.5px;font-weight:bold;white-space:nowrap;'>
                                📦 View in Order History
                            </a>
                        </td>
                    </tr>
                </table>
                <p style='margin:10px 0 0;color:#65a30d;font-size:11.5px;font-weight:600;'>
                    ℹ️ Note: You can view/play the attached video files directly below in this email, or access them in your Order History for 30 days after delivery.
                </p>
            </div>";
        }
        return $this->wrapInLayout("Order Packed & Ready for Shipment", "
            <p style='margin:0 0 16px'>Hi <strong>{$name}</strong>,</p>
            <p style='margin:0 0 16px'>Great news! Your order has been <strong style='color:#16a34a'>carefully packed and verified</strong> by our warehouse team. It is now queued for courier dispatch.</p>
            
            <div style='background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:20px;margin:24px 0'>
                <table width='100%' cellpadding='0' cellspacing='0'>
                    <tr><td style='color:#64748b;font-size:13px;padding:4px 0'>Order Number</td><td style='font-weight:700;text-align:right'>#{$orderNum}</td></tr>
                    <tr><td style='color:#64748b;font-size:13px;padding:4px 0'>Total Amount</td><td style='font-weight:700;text-align:right'>₹{$amount}</td></tr>
                    <tr><td style='color:#64748b;font-size:13px;padding:4px 0'>Packed On</td><td style='font-weight:700;text-align:right'>{$date}</td></tr>
                    <tr><td style='color:#64748b;font-size:13px;padding:4px 0'>Status</td><td style='text-align:right'><span style='background:#dcfce7;color:#14532d;padding:2px 10px;border-radius:999px;font-size:12px;font-weight:700'>PACKED</span></td></tr>
                </table>
            </div>

            {$videoSection}

            <div style='background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:16px;margin:20px 0;'>
                <p style='margin:0;color:#1e40af;font-size:13px;'>
                    📄 <strong>Tax Invoice Attached:</strong> Your official GST Tax Invoice has been generated and attached to this email as a PDF.
                </p>
            </div>

            <p style='margin:0 0 16px;color:#64748b;font-size:14px'>You will receive another notification with tracking details as soon as the package is handed over to the courier.</p>
            <p style='margin:0;color:#64748b;font-size:14px'>Thank you for choosing GRM B2B!</p>
        ");
    }

    private function buildCancellationRequestedHtml(array $user, array $order): string
    {
        $name        = htmlspecialchars($user['name']);
        $orderNum    = htmlspecialchars($order['order_number']);
        $amount      = number_format((float)($order['grand_total'] ?? $order['total_amount']), 2);
        $date        = date('d M Y, h:i A');

        return $this->wrapInLayout("Cancellation Request Received", "
            <p style='margin:0 0 16px'>Hi <strong>{$name}</strong>,</p>
            <p style='margin:0 0 16px'>We have received your cancellation request for the order below. Our team will review it and process the refund within <strong>3–5 business days</strong>.</p>
            <div style='background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:20px;margin:24px 0'>
                <table width='100%' cellpadding='0' cellspacing='0'>
                    <tr><td style='color:#64748b;font-size:13px;padding:4px 0'>Order Number</td><td style='font-weight:700;text-align:right'>#{$orderNum}</td></tr>
                    <tr><td style='color:#64748b;font-size:13px;padding:4px 0'>Order Amount</td><td style='font-weight:700;text-align:right'>₹{$amount}</td></tr>
                    <tr><td style='color:#64748b;font-size:13px;padding:4px 0'>Requested At</td><td style='font-weight:700;text-align:right'>{$date}</td></tr>
                    <tr><td style='color:#64748b;font-size:13px;padding:4px 0'>Status</td><td style='text-align:right'><span style='background:#fef9c3;color:#854d0e;padding:2px 10px;border-radius:999px;font-size:12px;font-weight:700'>PENDING REVIEW</span></td></tr>
                </table>
            </div>
            <p style='margin:0 0 16px;color:#64748b;font-size:14px'>You will receive another email once the refund is approved or if we need more information.</p>
            <p style='margin:0;color:#64748b;font-size:14px'>If you have questions, please contact our support team.</p>
        ");
    }

    private function buildRefundProcessedHtml(array $user, array $order, float $amount): string
    {
        $name     = htmlspecialchars($user['name']);
        $orderNum = htmlspecialchars($order['order_number']);
        $amt      = number_format($amount, 2);
        $date     = date('d M Y, h:i A');

        return $this->wrapInLayout("Refund Processed ✓", "
            <p style='margin:0 0 16px'>Hi <strong>{$name}</strong>,</p>
            <p style='margin:0 0 16px'>Great news! Your refund has been <strong style='color:#16a34a'>successfully processed</strong> and will reflect in your original payment source within 5–7 business days.</p>
            <div style='background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:20px;margin:24px 0'>
                <table width='100%' cellpadding='0' cellspacing='0'>
                    <tr><td style='color:#64748b;font-size:13px;padding:4px 0'>Order Number</td><td style='font-weight:700;text-align:right'>#{$orderNum}</td></tr>
                    <tr><td style='color:#64748b;font-size:13px;padding:4px 0'>Refund Amount</td><td style='font-weight:700;font-size:20px;text-align:right;color:#15803d'>₹{$amt}</td></tr>
                    <tr><td style='color:#64748b;font-size:13px;padding:4px 0'>Processed At</td><td style='font-weight:700;text-align:right'>{$date}</td></tr>
                    <tr><td style='color:#64748b;font-size:13px;padding:4px 0'>Status</td><td style='text-align:right'><span style='background:#dcfce7;color:#14532d;padding:2px 10px;border-radius:999px;font-size:12px;font-weight:700'>REFUNDED</span></td></tr>
                </table>
            </div>
            <p style='margin:0;color:#64748b;font-size:14px'>Thank you for shopping with GRM B2B. We hope to see you again soon!</p>
        ");
    }

    private function buildRefundRejectedHtml(array $user, array $order, string $reason): string
    {
        $name     = htmlspecialchars($user['name']);
        $orderNum = htmlspecialchars($order['order_number']);
        $reason   = htmlspecialchars($reason);

        return $this->wrapInLayout("Update on Your Cancellation Request", "
            <p style='margin:0 0 16px'>Hi <strong>{$name}</strong>,</p>
            <p style='margin:0 0 16px'>We have reviewed your cancellation request for order <strong>#{$orderNum}</strong> and unfortunately we are unable to process it at this time.</p>
            <div style='background:#fff7f7;border:1px solid #fecaca;border-radius:8px;padding:20px;margin:24px 0'>
                <p style='margin:0 0 8px;font-weight:700;color:#991b1b'>Reason for Rejection:</p>
                <p style='margin:0;color:#7f1d1d'>{$reason}</p>
            </div>
            <p style='margin:0 0 16px;color:#64748b;font-size:14px'>If you believe this is an error or need further assistance, please reach out to our support team and we will be happy to help.</p>
            <p style='margin:0;color:#64748b;font-size:14px'>We apologise for any inconvenience caused.</p>
        ");
    }

    /**
     * Shared email layout wrapper.
     */
    private function wrapInLayout(string $heading, string $bodyContent): string
    {
        $year       = date('Y');
        $brandColor = '#F25996';

        return "<!DOCTYPE html>
<html lang='en'>
<head><meta charset='UTF-8'><meta name='viewport' content='width=device-width,initial-scale=1'></head>
<body style='margin:0;padding:0;background:#fdf9fb;font-family:-apple-system,BlinkMacSystemFont,\"Segoe UI\",Roboto,sans-serif'>
  <table width='100%' cellpadding='0' cellspacing='0' style='padding:40px 16px'>
    <tr><td align='center'>
      <table width='600' cellpadding='0' cellspacing='0' style='background:#ffffff;border-radius:16px;overflow:hidden;border:1px solid #FDBFDD;box-shadow:0 4px 12px rgba(242,89,150,0.08)'>
        <!-- Header -->
        <tr><td style='background:linear-gradient(135deg, #F25996 0%, #e04481 100%);padding:28px 32px;text-align:center'>
          <h1 style='margin:0;color:#ffffff;font-size:24px;font-weight:800;letter-spacing:-0.5px'>GRM B2B</h1>
          <p style='margin:4px 0 0;color:#fedbe9;font-size:13px;font-weight:600'>{$heading}</p>
        </td></tr>
        <!-- Body -->
        <tr><td style='padding:32px'>
          {$bodyContent}
        </td></tr>
        <!-- Footer -->
        <tr><td style='background:#fdf2f7;padding:20px 32px;text-align:center;border-top:1px solid #FDBFDD'>
          <p style='margin:0;color:#9ca3af;font-size:12px'>© {$year} GRM B2B. All rights reserved.</p>
          <p style='margin:4px 0 0;color:#9ca3af;font-size:12px'>This is an automated email — please do not reply.</p>
        </td></tr>
      </table>
    </td></tr>
  </table>
</body>
</html>";
    }

    // ---------------------------------------------------------------------------
    // Transport
    // ---------------------------------------------------------------------------

    private function send(string $toEmail, string $toName, string $subject, string $htmlBody, array $attachments = []): bool
    {
        if (class_exists(PHPMailer::class)) {
            $mail = new PHPMailer(true);
            try {
                $host = defined('SMTP_HOST') ? SMTP_HOST : ($this->envVars['SMTP_HOST'] ?? $this->envVars['MAIL_HOST'] ?? 'smtp.gmail.com');
                $user = defined('SMTP_USER') ? SMTP_USER : ($this->envVars['SMTP_USER'] ?? $this->envVars['MAIL_USERNAME'] ?? '');
                $pass = defined('SMTP_PASS') ? SMTP_PASS : ($this->envVars['SMTP_PASS'] ?? $this->envVars['MAIL_PASSWORD'] ?? '');
                $port = defined('SMTP_PORT') ? (int)SMTP_PORT : (int)($this->envVars['SMTP_PORT'] ?? 587);
                $from = defined('SMTP_FROM') ? SMTP_FROM : ($this->envVars['SMTP_FROM'] ?? $this->fromEmail);

                if (!empty($host) && !empty($user) && !empty($pass)) {
                    $mail->isSMTP();
                    $mail->Host       = $host;
                    $mail->SMTPAuth   = true;
                    $mail->Username   = $user;
                    $mail->Password   = $pass;
                    $mail->Port       = $port;
                    if ($port === 465) {
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    } else {
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    }

                    $mail->SMTPOptions = [
                        'ssl' => [
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        ]
                    ];
                }

                $mail->setFrom($from, $this->fromName);
                $mail->addAddress($toEmail, $toName);
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body    = $htmlBody;
                $mail->AltBody = strip_tags($htmlBody);

                // Attachments
                foreach ($attachments as $att) {
                    if (isset($att['type']) && $att['type'] === 'string') {
                        $mail->addStringAttachment($att['binary'], $att['filename'], 'base64', $att['mime'] ?? 'application/pdf');
                    } elseif (isset($att['path']) && file_exists($att['path'])) {
                        $mime = $att['mime'] ?? 'application/octet-stream';
                        $mail->addAttachment($att['path'], $att['filename'] ?? '', 'base64', $mime);
                    }
                }

                return $mail->send();
            } catch (\Throwable $e) {
                error_log("[EmailService PHPMailer Error] to {$toEmail}: " . $e->getMessage());
            }
        }

        // Fallback to standard mail()
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: {$this->fromName} <{$this->fromEmail}>\r\n";
        $headers .= "Reply-To: {$this->fromEmail}\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        $to = "{$toName} <{$toEmail}>";
        $result = @mail($to, $subject, $htmlBody, $headers);

        if (!$result) {
            error_log("[EmailService mail() Error] Failed to send email to {$toEmail} — Subject: {$subject}");
        }

        return $result;
    }
}
