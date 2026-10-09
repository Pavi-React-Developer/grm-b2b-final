<?php
namespace App\Controllers\Storefront;

use Core\Controller;
use Core\Session;
use App\Models\User;
use App\Models\VendorProfile;
use App\Models\VendorType;
use App\Services\EmailService;

class VendorAuthController extends Controller
{
    public function showRegister()
    {
        if (!is_vendor_module_enabled()) {
            Session::setFlash('error', 'Vendor registration and onboarding is currently disabled by platform administration.');
            $this->redirect('/register');
            return;
        }

        if (Session::get('user_id')) {
            $this->redirect('/');
        }
        $oldInput = Session::getFlash('old_input') ?? [];
        $initialStep = Session::getFlash('vendor_register_step') ?? 1;

        $vendorTypeModel = new VendorType();
        $vendorTypes = $vendorTypeModel->getAllActive();

        $this->render('storefront/vendor_register', [
            'title' => 'Register as Vendor',
            'old' => $oldInput,
            'initialStep' => (int)$initialStep,
            'vendorTypes' => $vendorTypes
        ]);
    }

    private function redirectWithError(string $msg, int $step = 1)
    {
        $oldInput = $_POST;
        unset($oldInput['password']); // Safety: don't persist plaintext password
        Session::setFlash('old_input', $oldInput);
        Session::setFlash('vendor_register_step', $step);
        Session::setFlash('error', $msg);
        $this->redirect('/vendor-register');
    }

    public function register()
    {
        if (!is_vendor_module_enabled()) {
            Session::setFlash('error', 'Vendor registration and onboarding is currently disabled by platform administration.');
            $this->redirect('/register');
            return;
        }

        // 1. Gather Data
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        
        $storeName = trim($_POST['store_name'] ?? '');
        $companyName = trim($_POST['company_name'] ?? '');
        $vendorType = $_POST['vendor_type'] ?? 'wholesaler';
        
        $hasGst = isset($_POST['has_gst']) ? ($_POST['has_gst'] === '1' || $_POST['has_gst'] === 'true') : (!empty($_POST['gst_number']));
        
        $gstNumber = $hasGst ? strtoupper(trim($_POST['gst_number'] ?? '')) : null;
        $panNumber = strtoupper(trim($_POST['pan_number'] ?? ''));
        
        $noGstReason = null;
        if (!$hasGst) {
            $selectedReason = trim($_POST['no_gst_reason'] ?? '');
            if ($selectedReason === 'Other') {
                $noGstReason = trim($_POST['other_gst_reason'] ?? 'Other reason');
            } else {
                $noGstReason = $selectedReason;
            }
        }
        
        $address = trim($_POST['address'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $state = trim($_POST['state'] ?? '');
        $pincode = trim($_POST['pincode'] ?? '');
        
        $bankName = trim($_POST['bank_name'] ?? '');
        $accountNumber = trim($_POST['account_number'] ?? '');
        $ifscCode = strtoupper(trim($_POST['ifsc_code'] ?? ''));
        $accountHolderName = trim($_POST['account_holder_name'] ?? '');
        $upiId = trim($_POST['upi_id'] ?? '');
        $upiName = trim($_POST['upi_name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        // 2. Strict Validation with Step Memory & Input Retention
        if (empty($name) || empty($email) || empty($phone) || empty($password)) {
            $this->redirectWithError('Please fill in all required fields in Account Information.', 1);
            return;
        }

        if (empty($storeName) || empty($address) || empty($city) || empty($state) || empty($pincode)) {
            $this->redirectWithError('Please fill in all required fields in Store & Business Details.', 2);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->redirectWithError('Invalid email format.', 1);
            return;
        }

        if (!preg_match('/^[0-9]{10}$/', $phone)) {
            $this->redirectWithError('Phone number must be exactly 10 digits.', 1);
            return;
        }

        if (!preg_match('/^[0-9]{6}$/', $pincode)) {
            $this->redirectWithError('Pincode must be exactly 6 digits.', 2);
            return;
        }

        // Tax / Business Registration Validation
        if ($hasGst) {
            if (empty($gstNumber)) {
                $this->redirectWithError('Please enter your 15-digit GST Number or select Non-GST Vendor.', 2);
                return;
            }
            if (!preg_match('/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/', $gstNumber)) {
                $this->redirectWithError('Invalid GST number format (e.g. 27AAAAA0000A1Z5).', 2);
                return;
            }
            if (empty($panNumber) && strlen($gstNumber) === 15) {
                $panNumber = substr($gstNumber, 2, 10);
            }
            if (!empty($panNumber) && !preg_match('/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/', $panNumber)) {
                $this->redirectWithError('Invalid PAN number format (e.g. ABCDE1234F).', 2);
                return;
            }
        } else {
            // Non-GST Vendor
            if (empty($panNumber)) {
                $this->redirectWithError('PAN number is required for Non-GST vendor registration.', 2);
                return;
            }
            if (!preg_match('/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/', $panNumber)) {
                $this->redirectWithError('Invalid PAN number format (e.g. ABCDE1234F). Must be 5 letters, 4 digits, 1 letter.', 2);
                return;
            }
            if (empty($noGstReason)) {
                $this->redirectWithError('Please select or specify a reason for Non-GST vendor registration.', 2);
                return;
            }
            $gstNumber = null;
        }

        if (!empty($accountNumber) && !preg_match('/^[0-9]{9,18}$/', $accountNumber)) {
            $this->redirectWithError('Bank account number must be between 9 and 18 digits (numbers only).', 3);
            return;
        }

        if (!empty($ifscCode) && !preg_match('/^[A-Z]{4}0[A-Z0-9]{6}$/', $ifscCode)) {
            $this->redirectWithError('Invalid IFSC code format (e.g. HDFC0001234). Standard format is 4 letters, 0, followed by 6 alphanumeric characters.', 3);
            return;
        }

        if (empty($upiId)) {
            $this->redirectWithError('Please enter your UPI ID / VPA for vendor settlements.', 3);
            return;
        }

        if (!preg_match('/^[\w.\-_]{2,256}@[a-zA-Z]{2,64}$/', $upiId)) {
            $this->redirectWithError('Invalid UPI ID format (e.g. yourname@okaxis or 9876543210@upi).', 3);
            return;
        }

        if (empty($upiName)) {
            $this->redirectWithError('Please enter the UPI Account Holder / Beneficiary Name.', 3);
            return;
        }

        $userModel = new User();
        if ($userModel->findByEmail($email)) {
            $this->redirectWithError('An account with this email address already exists.', 1);
            return;
        }

        if ($userModel->findByPhone($phone)) {
            $this->redirectWithError('An account with this phone number already exists.', 1);
            return;
        }

        try {
            // 3. Create User record
            $userId = $userModel->create([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'password' => $password,
                'role' => 'vendor',
                'status' => 'pending'
            ]);

            // 4. Handle Media / Document Uploads
            $shopImagePaths = [];
            $gstDocPath = null;
            $uploadDir = BASE_PATH . '/uploads/vendors/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $db = \Core\Database::getInstance();

            // A. Multiple Shop / Showroom Images
            if (!empty($_FILES['shop_images']['name'])) {
                $names = is_array($_FILES['shop_images']['name']) ? $_FILES['shop_images']['name'] : [$_FILES['shop_images']['name']];
                $tmpNames = is_array($_FILES['shop_images']['tmp_name']) ? $_FILES['shop_images']['tmp_name'] : [$_FILES['shop_images']['tmp_name']];
                $errors = is_array($_FILES['shop_images']['error']) ? $_FILES['shop_images']['error'] : [$_FILES['shop_images']['error']];

                foreach ($names as $idx => $fname) {
                    if (!empty($fname) && ($errors[$idx] ?? 0) === UPLOAD_ERR_OK) {
                        $ext = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
                        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                            $filename = 'shop_img_' . $userId . '_' . time() . '_' . $idx . '.' . $ext;
                            $targetPath = $uploadDir . $filename;
                            if (move_uploaded_file($tmpNames[$idx], $targetPath)) {
                                $relPath = 'uploads/vendors/' . $filename;
                                $shopImagePaths[] = $relPath;

                                $stmt = $db->prepare("INSERT INTO media (user_id, file_path, file_type, purpose) VALUES (:uid, :path, 'image', 'shop_image')");
                                $stmt->execute(['uid' => $userId, 'path' => $relPath]);
                            }
                        }
                    }
                }
            }

            // Single shop_photo fallback
            if (!empty($_FILES['shop_photo']['name']) && empty($shopImagePaths)) {
                $file = $_FILES['shop_photo'];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp']) && $file['error'] === UPLOAD_ERR_OK) {
                    $filename = 'shop_img_' . $userId . '_' . time() . '.' . $ext;
                    if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
                        $relPath = 'uploads/vendors/' . $filename;
                        $shopImagePaths[] = $relPath;
                        $stmt = $db->prepare("INSERT INTO media (user_id, file_path, file_type, purpose) VALUES (:uid, :path, 'image', 'shop_image')");
                        $stmt->execute(['uid' => $userId, 'path' => $relPath]);
                    }
                }
            }

            // B. GST / Verification Document (Max 2MB limit & Cloudinary Storage)
            if (!empty($_FILES['gst_certificate']['name'])) {
                $file = $_FILES['gst_certificate'];
                
                // Enforce 2MB maximum file size limit
                if ($file['size'] > 2 * 1024 * 1024) {
                    $this->redirectWithError('GST certificate file size exceeds the 2MB limit. Please upload a smaller file.', 2);
                    return;
                }

                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];
                if (in_array($ext, $allowed) && $file['error'] === UPLOAD_ERR_OK) {
                    // Try uploading to Cloudinary
                    $cloudinary = new \App\Core\CloudinaryUploader();
                    $publicId = 'gst_certificate_' . $userId . '_' . time();
                    $cloudRes = $cloudinary->uploadMedia($file['tmp_name'], $publicId, $file['name']);

                    if ($cloudRes && !empty($cloudRes['secure_url'])) {
                        $gstDocPath = $cloudRes['secure_url'];
                        $stmt = $db->prepare("INSERT INTO media (user_id, file_path, file_type, purpose) VALUES (:uid, :path, :type, 'vendor_verification')");
                        $stmt->execute([
                            'uid' => $userId,
                            'path' => $gstDocPath,
                            'type' => $ext === 'pdf' ? 'pdf' : 'image'
                        ]);
                    } else {
                        // Fallback to local storage if Cloudinary is offline or credentials missing
                        $filename = 'vendor_doc_' . $userId . '_' . time() . '.' . $ext;
                        if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
                            $gstDocPath = 'uploads/vendors/' . $filename;
                            $stmt = $db->prepare("INSERT INTO media (user_id, file_path, file_type, purpose) VALUES (:uid, :path, :type, 'vendor_verification')");
                            $stmt->execute([
                                'uid' => $userId,
                                'path' => $gstDocPath,
                                'type' => $ext === 'pdf' ? 'pdf' : 'image'
                            ]);
                        }
                    }
                }
            }

            // 5. Create Vendor Profile
            $vendorModel = new VendorProfile();
            $vendorModel->create([
                'user_id' => $userId,
                'store_name' => $storeName,
                'company_name' => $companyName ?: $storeName,
                'vendor_type' => $vendorType,
                'gst_number' => $gstNumber ?: null,
                'pan_number' => $panNumber ?: null,
                'no_gst_reason' => $noGstReason ?: null,
                'address' => $address,
                'city' => $city,
                'state' => $state,
                'pincode' => $pincode,
                'bank_name' => $bankName ?: null,
                'account_number' => $accountNumber ?: null,
                'ifsc_code' => $ifscCode ?: null,
                'account_holder_name' => $accountHolderName ?: null,
                'upi_id' => $upiId ?: null,
                'upi_name' => $upiName ?: null,
                'description' => $description ?: null,
                'shop_images' => !empty($shopImagePaths) ? json_encode($shopImagePaths) : null,
                'gst_document' => $gstDocPath
            ]);

            // 6. Create Registration Request
            $vendorModel->createRegistrationRequest($userId);

            // 7. Generate & Send OTP
            $otp = (string)rand(100000, 999999);
            Session::set('vendor_reg_user_id', $userId);
            Session::set('vendor_reg_otp', $otp);
            Session::set('vendor_reg_email', $email);

            try {
                $emailService = new EmailService();
                $emailService->sendOtpEmail($email, $otp, $name);
            } catch (\Throwable $mailEx) {
                error_log("[Vendor OTP Email Warning] " . $mailEx->getMessage());
            }

            Session::setFlash('success', 'Registration submitted! Please enter the OTP sent to your email.');
            $this->redirect('/vendor-register/verify');

        } catch (\Exception $e) {
            Session::setFlash('error', 'Registration failed: ' . $e->getMessage());
            $this->redirect('/vendor-register');
        }
    }

    public function showVerifyOtp()
    {
        if (!is_vendor_module_enabled()) {
            Session::setFlash('error', 'Vendor registration is currently disabled.');
            $this->redirect('/register');
            return;
        }

        if (!Session::get('vendor_reg_user_id')) {
            $this->redirect('/vendor-register');
        }
        $this->render('storefront/vendor_verify_otp', ['title' => 'Verify Vendor OTP']);
    }

    public function verifyOtp()
    {
        if (!is_vendor_module_enabled()) {
            Session::setFlash('error', 'Vendor registration is currently disabled.');
            $this->redirect('/register');
            return;
        }

        $submittedOtp = trim($_POST['otp'] ?? '');
        $storedOtp = Session::get('vendor_reg_otp');
        $userId = Session::get('vendor_reg_user_id');

        if (!$userId || !$storedOtp) {
            Session::setFlash('error', 'Session expired. Please register again.');
            $this->redirect('/vendor-register');
            return;
        }

        if ($submittedOtp === $storedOtp) {
            // OTP verified! User status remains 'pending' for admin review
            Session::remove('vendor_reg_otp');
            Session::remove('vendor_reg_user_id');
            Session::remove('vendor_reg_email');

            Session::setFlash('success', 'Vendor account registration submitted successfully! Your application is under review by the administrator.');
            $this->redirect('/login');
        } else {
            Session::setFlash('error', 'Invalid OTP. Please try again.');
            $this->redirect('/vendor-register/verify');
        }
    }

    public function resendOtp()
    {
        $userId = Session::get('vendor_reg_user_id');
        $email = Session::get('vendor_reg_email');

        if (!$userId || !$email) {
            echo json_encode(['success' => false, 'message' => 'Session expired.']);
            exit;
        }

        $otp = (string)rand(100000, 999999);
        Session::set('vendor_reg_otp', $otp);

        $userModel = new User();
        $user = $userModel->findById($userId);

        $emailService = new EmailService();
        $sent = $emailService->sendOtpEmail($email, $otp, $user['name'] ?? 'Vendor');

        echo json_encode(['success' => $sent, 'message' => $sent ? 'OTP resent successfully.' : 'Failed to send OTP.']);
        exit;
    }

    public function checkVendorExists()
    {
        header('Content-Type: application/json');
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true) ?? [];

        $field = $data['field'] ?? '';
        $value = trim($data['value'] ?? '');

        $db = \Core\Database::getInstance();

        // 1. Single field check
        if (!empty($field) && !empty($value)) {
            if ($field === 'email') {
                $stmt = $db->prepare("SELECT id FROM users WHERE LOWER(email) = LOWER(?) LIMIT 1");
                $stmt->execute([$value]);
                if ($stmt->fetch()) {
                    echo json_encode([
                        'exists' => true,
                        'field' => 'email',
                        'message' => 'This email address is already registered. Please log in or use another email.'
                    ]);
                    return;
                }
            } elseif ($field === 'phone') {
                $stmt = $db->prepare("SELECT id FROM users WHERE phone = ? LIMIT 1");
                $stmt->execute([$value]);
                if ($stmt->fetch()) {
                    echo json_encode([
                        'exists' => true,
                        'field' => 'phone',
                        'message' => 'This mobile number is already registered. Please use another number.'
                    ]);
                    return;
                }
            } elseif ($field === 'account_number') {
                $stmt = $db->prepare("SELECT id FROM vendor_profiles WHERE account_number = ? LIMIT 1");
                $stmt->execute([$value]);
                $exists = $stmt->fetch();

                if (!$exists) {
                    $stmt = $db->prepare("SELECT id FROM vendor_bank_accounts WHERE account_number = ? LIMIT 1");
                    $stmt->execute([$value]);
                    $exists = $stmt->fetch();
                }

                if ($exists) {
                    echo json_encode([
                        'exists' => true,
                        'field' => 'account_number',
                        'message' => 'This bank account number is already linked to an existing vendor account.'
                    ]);
                    return;
                }
            } elseif ($field === 'upi_id') {
                $stmt = $db->prepare("SELECT id FROM vendor_profiles WHERE LOWER(upi_id) = LOWER(?) LIMIT 1");
                $stmt->execute([$value]);
                $exists = $stmt->fetch();

                if (!$exists) {
                    $stmt = $db->prepare("SELECT id FROM vendor_bank_accounts WHERE LOWER(upi_id) = LOWER(?) LIMIT 1");
                    $stmt->execute([$value]);
                    $exists = $stmt->fetch();
                }

                if ($exists) {
                    echo json_encode([
                        'exists' => true,
                        'field' => 'upi_id',
                        'message' => 'This UPI ID / VPA is already linked to an existing vendor account.'
                    ]);
                    return;
                }
            } elseif ($field === 'gst_number') {
                $stmt = $db->prepare("SELECT id FROM vendor_profiles WHERE UPPER(gst_number) = UPPER(?) LIMIT 1");
                $stmt->execute([$value]);
                if ($stmt->fetch()) {
                    echo json_encode([
                        'exists' => true,
                        'field' => 'gst_number',
                        'message' => 'This GST number is already registered with another vendor profile.'
                    ]);
                    return;
                }
            }
        }

        // 2. Batch check support
        $errors = [];
        if (!empty($data['email'])) {
            $stmt = $db->prepare("SELECT id FROM users WHERE LOWER(email) = LOWER(?) LIMIT 1");
            $stmt->execute([trim($data['email'])]);
            if ($stmt->fetch()) {
                $errors['email'] = 'This email address is already registered. Please log in or use another email.';
            }
        }

        if (!empty($data['phone'])) {
            $stmt = $db->prepare("SELECT id FROM users WHERE phone = ? LIMIT 1");
            $stmt->execute([trim($data['phone'])]);
            if ($stmt->fetch()) {
                $errors['phone'] = 'This mobile number is already registered. Please use another number.';
            }
        }

        if (!empty($data['account_number'])) {
            $stmt = $db->prepare("SELECT id FROM vendor_profiles WHERE account_number = ? LIMIT 1");
            $stmt->execute([trim($data['account_number'])]);
            $exists = $stmt->fetch();
            if (!$exists) {
                $stmt = $db->prepare("SELECT id FROM vendor_bank_accounts WHERE account_number = ? LIMIT 1");
                $stmt->execute([trim($data['account_number'])]);
                $exists = $stmt->fetch();
            }
            if ($exists) {
                $errors['account_number'] = 'This bank account number is already linked to an existing vendor account.';
            }
        }

        if (!empty($data['upi_id'])) {
            $stmt = $db->prepare("SELECT id FROM vendor_profiles WHERE LOWER(upi_id) = LOWER(?) LIMIT 1");
            $stmt->execute([trim($data['upi_id'])]);
            $exists = $stmt->fetch();
            if (!$exists) {
                $stmt = $db->prepare("SELECT id FROM vendor_bank_accounts WHERE LOWER(upi_id) = LOWER(?) LIMIT 1");
                $stmt->execute([trim($data['upi_id'])]);
                $exists = $stmt->fetch();
            }
            if ($exists) {
                $errors['upi_id'] = 'This UPI ID / VPA is already linked to an existing vendor account.';
            }
        }

        if (!empty($errors)) {
            echo json_encode(['exists' => true, 'errors' => $errors]);
            return;
        }

        echo json_encode(['exists' => false]);
        return;
    }
}
