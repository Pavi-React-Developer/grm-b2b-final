<?php
namespace App\Controllers\Storefront;

use Core\Controller;
use Core\Session;
use Core\Database;
use App\Models\User;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ForgotPasswordController extends Controller
{
    public function showEmailForm()
    {
        if (Session::get('user_id')) {
            $this->redirect('/');
        }
        $this->render('storefront/forgot_password', ['title' => 'Forgot Password']);
    }

    public function sendOtp()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/forgot-password');
        }

        if (!\Core\RateLimiter::attempt('forgot_password_otp', 5, 60)) {
            Session::setFlash('error', 'Too many requests. Please wait a minute and try again.');
            $this->redirect('/forgot-password');
        }

        $email = trim($_POST['email'] ?? '');
        if (empty($email)) {
            Session::setFlash('error', 'Please enter your email address.');
            $this->redirect('/forgot-password');
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user) {
            Session::setFlash('error', 'This email address is not registered in our system.');
            $this->redirect('/forgot-password');
        }

        // Generate 6-digit OTP
        $otp = sprintf("%06d", mt_rand(100000, 999999));

        $db = Database::getInstance();
        
        // Delete any existing OTPs for this email to prevent spam
        $db->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$email]);

        // Insert new OTP
        $db->prepare("INSERT INTO password_resets (email, otp, created_at) VALUES (?, ?, NOW())")->execute([$email, $otp]);

        // Send Email via PHPMailer
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USER;
            $mail->Password   = SMTP_PASS;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = SMTP_PORT;

            $mail->setFrom(SMTP_FROM, APP_NAME);
            $mail->addAddress($email, $user['name']);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset OTP - ' . APP_NAME;
            $mail->Body    = "
                <div style='font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif; max-width: 540px; margin: 0 auto; padding: 28px; background: #ffffff; border: 1px solid #FDBFDD; border-radius: 16px; box-shadow: 0 4px 12px rgba(242, 89, 150, 0.08);'>
                    <div style='text-align: center; margin-bottom: 20px;'>
                        <span style='display: inline-block; background: #fdf2f7; color: #F25996; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; padding: 4px 12px; border-radius: 9999px; border: 1px solid #FDBFDD;'>Security Verification</span>
                        <h2 style='color: #111827; font-size: 22px; font-weight: 800; margin: 12px 0 6px;'>Password Reset Request</h2>
                    </div>
                    <p style='color: #374151; font-size: 14px; line-height: 1.6; margin: 0 0 12px;'>Hello <strong>" . htmlspecialchars($user['name'] ?? 'User') . "</strong>,</p>
                    <p style='color: #4b5563; font-size: 14px; line-height: 1.6; margin: 0 0 20px;'>You have requested to reset your password. Use the following 6-digit OTP to proceed:</p>
                    <div style='background: linear-gradient(135deg, #fdf2f7 0%, #fff 100%); border: 2px dashed #FDBFDD; padding: 20px; text-align: center; border-radius: 12px; margin: 20px 0;'>
                        <span style='font-size: 34px; font-weight: 800; letter-spacing: 8px; color: #F25996; font-family: monospace;'>$otp</span>
                    </div>
                    <p style='color: #F25996; font-size: 13px; font-weight: bold; text-align: center; margin: 12px 0 0;'>This OTP will expire in 60 seconds.</p>
                    <p style='color: #6b7280; font-size: 12px; text-align: center; margin: 6px 0 0;'>If you did not request this, please ignore this email.</p>
                    <hr style='border: none; border-top: 1px solid #f3f4f6; margin: 24px 0;' />
                    <p style='color: #9ca3af; font-size: 11px; text-align: center; margin: 0;'>&copy; " . date('Y') . " GRM B2B. All rights reserved.</p>
                </div>
            ";

            $mail->send();
        } catch (Exception $e) {
            // Log error in real app
        }

        Session::set('reset_email', $email);
        Session::setFlash('success', 'OTP has been sent to your email.');
        $this->redirect('/verify-otp');
    }

    public function showOtpForm()
    {
        $email = Session::get('reset_email');
        if (!$email) {
            $this->redirect('/forgot-password');
        }

        $db = \Core\Database::getInstance();
        $stmt = $db->prepare("SELECT TIMESTAMPDIFF(SECOND, created_at, NOW()) as seconds_passed FROM password_resets WHERE email = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$email]);
        $resetRecord = $stmt->fetch();
        
        $otpExpirationRemaining = 0;
        if ($resetRecord && $resetRecord['seconds_passed'] < 60) {
            $otpExpirationRemaining = 60 - $resetRecord['seconds_passed'];
        }

        $devOtp = '';
        if (defined('APP_ENV') && APP_ENV === 'development') {
            $stmtOtp = $db->prepare("SELECT otp FROM password_resets WHERE email = ? ORDER BY created_at DESC LIMIT 1");
            $stmtOtp->execute([$email]);
            $devOtp = $stmtOtp->fetchColumn() ?: '';
        }

        $this->render('storefront/verify_otp', [
            'title' => 'Verify OTP',
            'formAction' => '/verify-otp',
            'resendAction' => '/forgot-password',
            'isRegistration' => false,
            'otpExpirationRemaining' => $otpExpirationRemaining,
            'devOtp' => $devOtp
        ]);
    }

    public function verifyOtp()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/verify-otp');
        }

        $email = Session::get('reset_email');
        $otp = trim($_POST['otp'] ?? '');

        if (!$email || empty($otp)) {
            Session::setFlash('error', 'Invalid request.');
            $this->redirect('/forgot-password');
        }

        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT *, TIMESTAMPDIFF(SECOND, created_at, NOW()) as seconds_passed FROM password_resets WHERE email = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$email]);
        $resetRecord = $stmt->fetch();

        if (!$resetRecord) {
            Session::setFlash('error', 'Invalid OTP or session expired.');
            $this->redirect('/verify-otp');
        }

        // Check if 60 seconds have passed using MySQL's internal clock to prevent timezone bugs
        if ($resetRecord['seconds_passed'] > 60) {
            Session::setFlash('error', 'This OTP has expired. Please request a new one.');
            $this->redirect('/forgot-password');
        }

        if ($resetRecord['otp'] !== $otp) {
            Session::setFlash('error', 'Invalid OTP. Please try again.');
            $this->redirect('/verify-otp');
        }

        // Valid OTP
        Session::set('reset_verified', true);
        $this->redirect('/reset-password');
    }

    public function showResetForm()
    {
        if (!Session::get('reset_verified') || !Session::get('reset_email')) {
            $this->redirect('/forgot-password');
        }
        $this->render('storefront/reset_password', ['title' => 'Reset Password']);
    }

    public function resetPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/reset-password');
        }

        if (!Session::get('reset_verified') || !Session::get('reset_email')) {
            $this->redirect('/forgot-password');
        }

        $email = Session::get('reset_email');
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($newPassword) || empty($confirmPassword)) {
            Session::setFlash('error', 'All fields are required.');
            $this->redirect('/reset-password');
        }

        if ($newPassword !== $confirmPassword) {
            Session::setFlash('error', 'Passwords do not match.');
            $this->redirect('/reset-password');
        }

        if (strlen($newPassword) < 8) {
            Session::setFlash('error', 'Password must be at least 8 characters long.');
            $this->redirect('/reset-password');
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user) {
            Session::setFlash('error', 'User not found.');
            $this->redirect('/forgot-password');
        }

        // Check if new password is the same as the old password
        if (password_verify($newPassword, $user['password_hash'])) {
            Session::setFlash('error', 'Your new password cannot be the same as your old password. Please choose a different one.');
            $this->redirect('/reset-password');
        }

        // Hash and update
        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $db = Database::getInstance();
        $db->prepare("UPDATE users SET password_hash = ? WHERE id = ?")->execute([$newHash, $user['id']]);
        
        // Clean up reset tokens
        $db->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$email]);

        // Clear session variables
        Session::remove('reset_email');
        Session::remove('reset_verified');

        Session::setFlash('success', 'Your password has been successfully reset! You can now login.');
        $this->redirect('/login');
    }
}
