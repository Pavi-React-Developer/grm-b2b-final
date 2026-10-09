<?php
// Define basic constants for paths and application config

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('CORE_PATH', BASE_PATH . '/core');
define('PUBLIC_PATH', BASE_PATH);
define('CONFIG_PATH', BASE_PATH . '/config');

// Load Environment Variables
$envPath = BASE_PATH . '/.env';
if (!file_exists($envPath) && file_exists(BASE_PATH . '/env')) {
    $envPath = BASE_PATH . '/env';
}
$envVars = [];
if (file_exists($envPath)) {
    $parsed = @parse_ini_file($envPath);
    if ($parsed !== false && is_array($parsed) && !empty($parsed)) {
        $envVars = $parsed;
    } else {
        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines) {
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '' || str_starts_with($line, '#') || str_starts_with($line, ';') || str_starts_with($line, '//')) {
                    continue;
                }
                if (strpos($line, '=') !== false) {
                    list($k, $v) = explode('=', $line, 2);
                    $k = trim($k);
                    $v = trim(trim($v), "'\"");
                    $envVars[$k] = $v;
                }
            }
        }
    }
}

// Base URL for the application (useful for linking CSS, JS, email links, etc.)
if (!empty($envVars['APP_URL'])) {
    define('BASE_URL', rtrim($envVars['APP_URL'], '/'));
} else {
    $isHttps = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || 
               (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
    $protocol = $isHttps ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
    define('BASE_URL', $protocol . '://' . $host);
}

// Application Configuration
define('APP_NAME', $envVars['APP_NAME'] ?? 'GRM B2B');
define('APP_VERSION', $envVars['APP_VERSION'] ?? '1.0.0');
define('APP_ENV', $envVars['APP_ENV'] ?? 'development');
define('APP_TIMEZONE', $envVars['APP_TIMEZONE'] ?? 'Asia/Kolkata');
date_default_timezone_set(APP_TIMEZONE);


// SMTP Configuration
define('SMTP_HOST', $envVars['SMTP_HOST'] ?? 'smtp.gmail.com');
define('SMTP_PORT', $envVars['SMTP_PORT'] ?? 587);
define('SMTP_USER', $envVars['SMTP_USER'] ?? '');
define('SMTP_PASS', $envVars['SMTP_PASS'] ?? '');
define('SMTP_FROM', $envVars['SMTP_FROM'] ?? '');

// Cloudinary Configuration
define('CLOUDINARY_CLOUD_NAME', $envVars['CLOUDINARY_CLOUD_NAME'] ?? '');
define('CLOUDINARY_API_KEY', $envVars['CLOUDINARY_API_KEY'] ?? '');
define('CLOUDINARY_API_SECRET', $envVars['CLOUDINARY_API_SECRET'] ?? '');

/*
// Cashfree Configuration (Archived / Preserved for future use)
define('CASHFREE_APP_ID', $envVars['CASHFREE_APP_ID'] ?? 'YOUR_DEFAULT_APP_ID');
define('CASHFREE_SECRET_KEY', $envVars['CASHFREE_SECRET_KEY'] ?? 'YOUR_DEFAULT_SECRET_KEY');
define('CASHFREE_ENV', $envVars['CASHFREE_ENV'] ?? 'sandbox'); // sandbox or production
*/

// Razorpay Configuration (Active Payment Gateway)
define('RAZORPAY_KEY_ID', $envVars['RAZORPAY_KEY_ID'] ?? 'rzp_test_YourKeyIdHere');
define('RAZORPAY_KEY_SECRET', $envVars['RAZORPAY_KEY_SECRET'] ?? 'YourKeySecretHere');
define('RAZORPAY_CURRENCY', $envVars['RAZORPAY_CURRENCY'] ?? 'INR');


// ─── Image Helpers ───────────────────────────────────────────────────────────

if (!function_exists('get_image_url')) {
    /**
     * Returns an optimised image URL.
     * For Cloudinary URLs it injects q_auto,f_auto (WebP delivery) and optional resize.
     */
    function get_image_url($path, $width = null, $height = null) {
        if (empty($path)) return '';

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            // Cloudinary: auto quality + format, optional resize
            if (strpos($path, 'res.cloudinary.com') !== false && strpos($path, 'q_auto') === false) {
                $transform = [];
                if ($width)  $transform[] = 'w_' . (int)$width;
                if ($height) $transform[] = 'h_' . (int)$height;
                $transform[] = 'c_limit,q_auto,f_auto';
                $transformStr = implode(',', $transform);

                // Insert right after /upload/
                $path = preg_replace('#(/upload/)#', '$1' . $transformStr . '/', $path, 1);
            }
            return $path;
        }

        $cleanPath = ltrim($path, '/');
        if (strpos($cleanPath, 'uploads/') === 0 && !file_exists(BASE_PATH . '/' . $cleanPath) && file_exists(BASE_PATH . '/public/' . $cleanPath)) {
            $cleanPath = 'public/' . $cleanPath;
        }

        return rtrim(BASE_URL, '/') . '/' . $cleanPath;
    }
}

if (!function_exists('lazy_img')) {
    /**
     * Outputs an optimised <img> tag with lazy loading and Cloudinary transforms.
     * Use $eager = true for above-the-fold / LCP images (hero banners, etc.).
     */
    function lazy_img($path, $alt = '', $class = '', $width = null, $height = null, $eager = false) {
        $src     = get_image_url($path, $width, $height);
        $loading = $eager ? 'eager' : 'lazy';
        $altE    = htmlspecialchars($alt,   ENT_QUOTES, 'UTF-8');
        $classE  = htmlspecialchars($class, ENT_QUOTES, 'UTF-8');
        $srcE    = htmlspecialchars($src,   ENT_QUOTES, 'UTF-8');
        $wAttr   = $width  ? " width=\"{$width}\""  : '';
        $hAttr   = $height ? " height=\"{$height}\"" : '';
        return "<img src=\"{$srcE}\" alt=\"{$altE}\" class=\"{$classE}\" loading=\"{$loading}\" decoding=\"async\"{$wAttr}{$hAttr}>";
    }
}

if (!function_exists('human_time_ago')) {
    /**
     * Converts a datetime string to human readable time ago (e.g. 5 mins ago, 2 hours ago)
     */
    function human_time_ago($datetime) {
        if (empty($datetime)) return '—';
        $time = strtotime($datetime);
        if (!$time) return '—';
        $diff = time() - $time;
        if ($diff < 0) return 'Just now';
        if ($diff < 60) return 'Just now';
        if ($diff < 3600) return floor($diff / 60) . ' mins ago';
        if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
        if ($diff < 604800) return floor($diff / 86400) . ' days ago';
        return date('d M Y', $time);
    }
}

if (!function_exists('format_price')) {
    /**
     * Formats price without trailing .00 (e.g. 100.00 -> 100, 399.50 -> 399.50, 1000.00 -> 1,000)
     */
    function format_price($price) {
        if ($price === null || $price === '') return '0';
        $floatVal = (float)$price;
        if (floor($floatVal) == $floatVal) {
            return number_format($floatVal, 0);
        }
        return rtrim(rtrim(number_format($floatVal, 2, '.', ','), '0'), '.');
    }
}

if (!function_exists('render_discount_starburst')) {
    function render_discount_starburst($pct, $extraClass = '') {
        $pct = (int)$pct;
        if ($pct <= 0) return '';
        $uid = uniqid('lux_');
        $fontSize = ($pct >= 100) ? '28' : '34';
        return '<div class="luxury-discount-badge ' . htmlspecialchars($extraClass) . ' pointer-events-none select-none" style="filter: drop-shadow(0 3px 6px rgba(0,0,0,0.3)) drop-shadow(0 2px 8px rgba(0,168,204,0.35)); aspect-ratio: 160/210;">
            <svg viewBox="0 0 160 210" class="w-full h-full block" xmlns="http://www.w3.org/2000/svg" style="overflow: visible;">
                <defs>
                    <!-- Main Body Gradient: Deep Rich Royal Teal -->
                    <linearGradient id="bodyGrad_' . $uid . '" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#026d88" />
                        <stop offset="35%" stop-color="#035d75" />
                        <stop offset="70%" stop-color="#02475a" />
                        <stop offset="100%" stop-color="#01313f" />
                    </linearGradient>

                    <!-- Top Rod Cylindrical Gradient -->
                    <linearGradient id="rodGrad_' . $uid . '" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#22d3ee" />
                        <stop offset="30%" stop-color="#00a8cc" />
                        <stop offset="70%" stop-color="#026982" />
                        <stop offset="100%" stop-color="#013b4a" />
                    </linearGradient>

                    <!-- Front Ribbon Gradient -->
                    <linearGradient id="ribbonGrad_' . $uid . '" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#00b4d8" />
                        <stop offset="30%" stop-color="#0096c7" />
                        <stop offset="75%" stop-color="#0077b6" />
                        <stop offset="100%" stop-color="#023e8a" />
                    </linearGradient>

                    <!-- Back Ribbon Tails Gradient -->
                    <linearGradient id="tailGrad_' . $uid . '" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#025368" />
                        <stop offset="50%" stop-color="#013d4d" />
                        <stop offset="100%" stop-color="#01242e" />
                    </linearGradient>

                    <!-- Shadow for Under-Folds -->
                    <linearGradient id="shadowGrad_' . $uid . '" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#01202a" />
                        <stop offset="100%" stop-color="#000e13" />
                    </linearGradient>

                    <!-- Pure Brilliant White Accent Gradient -->
                    <linearGradient id="whiteGrad_' . $uid . '" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#ffffff" />
                        <stop offset="50%" stop-color="#f0fdff" />
                        <stop offset="100%" stop-color="#ffffff" />
                    </linearGradient>
                </defs>

                <!-- ================= 1. TOP HORIZONTAL ROD & SIDE FINIALS ================= -->
                <g>
                    <!-- Outer Halo / Border for Top Rod -->
                    <rect x="22" y="11" width="116" height="15" rx="7.5" fill="none" stroke="url(#whiteGrad_' . $uid . ')" stroke-width="2.2" />
                    <!-- Rod Cylinder Body -->
                    <rect x="23" y="12" width="114" height="13" rx="6.5" fill="url(#rodGrad_' . $uid . ')" />
                    <!-- Specular Top Highlight Reflection -->
                    <path d="M 32,14.5 L 128,14.5" stroke="#ffffff" stroke-width="1.6" stroke-linecap="round" stroke-opacity="0.85" />
                    <!-- Left Finial Notch (White Chevron) -->
                    <polygon points="17,18.5 24,14 22,18.5 24,23" fill="url(#whiteGrad_' . $uid . ')" />
                    <circle cx="15" cy="18.5" r="1.2" fill="url(#whiteGrad_' . $uid . ')" />
                    <!-- Right Finial Notch (White Chevron) -->
                    <polygon points="143,18.5 136,14 138,18.5 136,23" fill="url(#whiteGrad_' . $uid . ')" />
                    <circle cx="145" cy="18.5" r="1.2" fill="url(#whiteGrad_' . $uid . ')" />
                    <!-- Inner Rolled Sleeve Cavity Openings -->
                    <ellipse cx="26" cy="18.5" rx="2.5" ry="4.8" fill="url(#shadowGrad_' . $uid . ')" stroke="url(#whiteGrad_' . $uid . ')" stroke-width="0.8" />
                    <ellipse cx="134" cy="18.5" rx="2.5" ry="4.8" fill="url(#shadowGrad_' . $uid . ')" stroke="url(#whiteGrad_' . $uid . ')" stroke-width="0.8" />
                </g>

                <!-- ================= 2. SWOOPING BACK RIBBON TAILS (LEFT & RIGHT) ================= -->
                <g>
                    <!-- Left Tail -->
                    <path d="M 32,106 C 20,107 13,114 9,132 L 20,123 L 11,111 C 16,102 24,98 34,98 Z" fill="url(#tailGrad_' . $uid . ')" stroke="url(#whiteGrad_' . $uid . ')" stroke-width="1.3" stroke-linejoin="round" />
                    <path d="M 30,103 C 21,104 15,108 12,116 L 17,121 L 12,126" fill="none" stroke="url(#whiteGrad_' . $uid . ')" stroke-width="0.7" stroke-opacity="0.8" />

                    <!-- Right Tail -->
                    <path d="M 128,106 C 140,107 147,114 151,132 L 140,123 L 149,111 C 144,102 136,98 126,98 Z" fill="url(#tailGrad_' . $uid . ')" stroke="url(#whiteGrad_' . $uid . ')" stroke-width="1.3" stroke-linejoin="round" />
                    <path d="M 130,103 C 139,104 145,108 148,116 L 143,121 L 148,126" fill="none" stroke="url(#whiteGrad_' . $uid . ')" stroke-width="0.7" stroke-opacity="0.8" />
                </g>

                <!-- ================= 3. MAIN VERTICAL BANNER BODY ================= -->
                <!-- Outer Pennant Shape with White Outline -->
                <path d="
                    M 34,22 
                    L 126,22 
                    L 126,146 
                    L 80,195 
                    L 34,146 
                    Z
                " fill="url(#bodyGrad_' . $uid . ')" stroke="url(#whiteGrad_' . $uid . ')" stroke-width="2.2" stroke-linejoin="round" />

                <!-- Inner Inset Parallel White Border Line -->
                <path d="
                    M 40,28 
                    L 120,28 
                    L 120,143 
                    L 80,186 
                    L 40,143 
                    Z
                " fill="none" stroke="url(#whiteGrad_' . $uid . ')" stroke-width="1.2" stroke-opacity="0.95" />

                <!-- ================= 4. TOP ROYAL FILIGREE SCROLLWORK ================= -->
                <g fill="none" stroke="url(#whiteGrad_' . $uid . ')" stroke-width="1.2" stroke-linecap="round">
                    <!-- Left Filigree Spiral -->
                    <path d="M 68,42 C 60,37 50,38 52,44 C 54,49 63,48 69,42 C 73,38 77,40 80,43" />
                    <!-- Right Filigree Spiral -->
                    <path d="M 92,42 C 100,37 110,38 108,44 C 106,49 97,48 91,42 C 87,38 83,40 80,43" />
                    <!-- Center Top Diamond -->
                    <polygon points="80,37 83,41 80,45 77,41" fill="url(#whiteGrad_' . $uid . ')" stroke="none" />
                    <!-- Subtle Arch Linking -->
                    <path d="M 72,40 Q 80,36 88,40" stroke-width="0.9" />
                </g>

                <!-- ================= 5. MAIN PERCENTAGE TEXT ================= -->
                <text x="80" y="70" font-family="Georgia, \'Times New Roman\', serif" font-weight="bold" font-size="' . $fontSize . '" fill="url(#whiteGrad_' . $uid . ')" text-anchor="middle" dominant-baseline="central" letter-spacing="0.5">' . $pct . '%</text>

                <!-- ================= 6. MIDDLE 3D ARCHED RIBBON BANNER ("O F F") ================= -->
                <g>
                    <!-- Dark Under-Fold Shadow Triangles -->
                    <path d="M 22,122 L 34,122 L 34,132 Z" fill="url(#shadowGrad_' . $uid . ')" />
                    <path d="M 138,122 L 126,122 L 126,132 Z" fill="url(#shadowGrad_' . $uid . ')" />

                    <!-- Front Ribbon Banner Body (Arched) -->
                    <path d="M 22,96 Q 80,105 138,96 L 138,122 Q 80,131 22,122 Z" fill="url(#ribbonGrad_' . $uid . ')" stroke="url(#whiteGrad_' . $uid . ')" stroke-width="1.8" stroke-linejoin="round" />

                    <!-- Ribbon White Inset Trim Pinstripes -->
                    <path d="M 24,100 Q 80,109 136,100" fill="none" stroke="url(#whiteGrad_' . $uid . ')" stroke-width="0.9" stroke-opacity="0.95" />
                    <path d="M 24,118 Q 80,127 136,118" fill="none" stroke="url(#whiteGrad_' . $uid . ')" stroke-width="0.9" stroke-opacity="0.95" />

                    <!-- Ribbon Spaced Text: "O F F" -->
                    <text x="80" y="114" font-family="Georgia, \'Times New Roman\', serif" font-weight="bold" font-size="13" fill="url(#whiteGrad_' . $uid . ')" text-anchor="middle" dominant-baseline="central" letter-spacing="4">O F F</text>
                </g>

                <!-- ================= 7. LOWER SECTION: 5 DOTS DIVIDER ================= -->
                <g fill="url(#whiteGrad_' . $uid . ')">
                    <circle cx="68" cy="144" r="1.3" />
                    <circle cx="74" cy="144" r="1.6" />
                    <circle cx="80" cy="144" r="2.2" />
                    <circle cx="86" cy="144" r="1.6" />
                    <circle cx="92" cy="144" r="1.3" />
                </g>

                <!-- ================= 8. LOWER SECTION: LAUREL SCALLOPED BEADS & STAR ================= -->
                <!-- Left Scalloped Laurel Garland -->
                <g fill="url(#whiteGrad_' . $uid . ')" stroke="url(#whiteGrad_' . $uid . ')">
                    <path d="M 46,151 Q 54,164 68,175" fill="none" stroke-width="0.8" stroke-opacity="0.6" />
                    <circle cx="47" cy="151" r="1.6" />
                    <circle cx="51" cy="157" r="1.8" />
                    <circle cx="56" cy="163" r="2.0" />
                    <circle cx="62" cy="169" r="2.2" />
                    <circle cx="69" cy="175" r="2.3" />
                </g>

                <!-- Right Scalloped Laurel Garland -->
                <g fill="url(#whiteGrad_' . $uid . ')" stroke="url(#whiteGrad_' . $uid . ')">
                    <path d="M 114,151 Q 106,164 92,175" fill="none" stroke-width="0.8" stroke-opacity="0.6" />
                    <circle cx="113" cy="151" r="1.6" />
                    <circle cx="109" cy="157" r="1.8" />
                    <circle cx="104" cy="163" r="2.0" />
                    <circle cx="98" cy="169" r="2.2" />
                    <circle cx="91" cy="175" r="2.3" />
                </g>

                <!-- Bottom 5-Point Crisp White Star -->
                <polygon points="80,175 82.2,180.2 87.5,180.5 83.4,184 84.8,189.2 80,186.2 75.2,189.2 76.6,184 72.5,180.5 77.8,180.2" fill="url(#whiteGrad_' . $uid . ')" stroke="url(#whiteGrad_' . $uid . ')" stroke-width="0.5" stroke-linejoin="round" />
            </svg>
        </div>';
    }
}

if (!function_exists('is_vendor_module_enabled')) {
    /**
     * Checks if the dynamic Multi-Vendor Module is enabled by Super Admin.
     * Default: true ('1')
     */
    function is_vendor_module_enabled(): bool {
        try {
            if (!class_exists('Core\Cache')) {
                $cacheFile = defined('CORE_PATH') ? (CORE_PATH . '/Cache.php') : (__DIR__ . '/../core/Cache.php');
                if (file_exists($cacheFile)) {
                    require_once $cacheFile;
                }
            }
            if (!class_exists('Core\Database')) {
                $dbFile = defined('CORE_PATH') ? (CORE_PATH . '/Database.php') : (__DIR__ . '/../core/Database.php');
                if (file_exists($dbFile)) {
                    require_once $dbFile;
                }
            }

            $val = class_exists('Core\Cache') ? \Core\Cache::get('setting_vendor_module_enabled') : null;
            if ($val === null) {
                $db = \Core\Database::getInstance();
                $stmt = $db->prepare("SELECT setting_value FROM settings WHERE setting_key = 'vendor_module_enabled' LIMIT 1");
                $stmt->execute();
                $res = $stmt->fetchColumn();
                $val = ($res !== false && $res !== null) ? (string)$res : '1';
                if (class_exists('Core\Cache')) {
                    \Core\Cache::set('setting_vendor_module_enabled', $val, 3600);
                }
            }
            return ($val === '1' || $val === 1 || $val === true || $val === 'true');
        } catch (\Throwable $e) {
            return true;
        }
    }
}



