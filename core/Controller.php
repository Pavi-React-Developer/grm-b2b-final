<?php
namespace Core;

class Controller
{
    protected $userPermissions = [];

    public function __construct()
    {
        $userId = \Core\Session::get('user_id');
        if ($userId) {
            // Always fetch latest to ensure instant UI updates when Super Admin changes roles/status
            $db   = \Core\Database::getInstance();
            $stmt = $db->prepare("
                SELECT u.status, u.role, u.permissions AS custom_permissions, r.permissions AS role_permissions
                FROM users u
                LEFT JOIN roles r ON u.role_id = r.id
                WHERE u.id = ?
            ");
            $stmt->execute([$userId]);
            $userData = $stmt->fetch();

            if ($userData) {
                \Core\Session::set('user_status', $userData['status']);
                \Core\Session::set('user_role', $userData['role']);
                $blockedStatuses = ['blocked', 'inactive', 'deactivated'];
                if (in_array($userData['status'], $blockedStatuses)) {
                    // Clear all session data to force logout
                    \Core\Session::remove('user_id');
                    \Core\Session::remove('user_role');
                    \Core\Session::remove('user_status');
                    $msg = ($userData['status'] === 'inactive' || $userData['status'] === 'deactivated')
                        ? 'Your account has been deactivated. Please contact the administrator.'
                        : 'Your account has been blocked by the administrator.';
                    \Core\Session::setFlash('error', $msg);
                    $this->redirect('/login');
                }

                if ($userData['role'] === 'vendor' && !is_vendor_module_enabled()) {
                    \Core\Session::remove('user_id');
                    \Core\Session::remove('user_role');
                    \Core\Session::remove('user_status');
                    \Core\Session::setFlash('error', 'Vendor operations and portal access are currently disabled by platform administration.');
                    $this->redirect('/login');
                }

                // Load RBAC permissions
                $permissionsJson = !empty($userData['role_permissions']) ? $userData['role_permissions'] : $userData['custom_permissions'];
                if (!empty($permissionsJson)) {
                    $this->userPermissions = json_decode($permissionsJson, true) ?? [];
                }
            }
        }
    }

    /**
     * Check if current user has permission for a specific module and action
     */
    public function hasPermission($module, $action)
    {
        $role = \Core\Session::get('user_role');
        
        // Super Admin has all permissions
        if ($role === 'super_admin') {
            return true;
        }

        // Vendors have permissions to their own portal modules
        if ($role === 'vendor') {
            if (!is_vendor_module_enabled()) {
                return false;
            }
            $vendorAllowed = [
                'dashboard' => ['view'],
                'products' => ['view', 'create', 'edit', 'delete'],
                'inventory' => ['view', 'edit'],
                'all_orders' => ['view', 'edit'],
                'pending_payments' => ['view'],
                'orders' => ['view', 'edit'],
                'vendor_portal' => ['view', 'edit'],
                'vendor_management' => ['view'],
                'category_requests' => ['view', 'create'],
                'categories' => ['view'],
                'subcategories' => ['view'],
                'attributes' => ['view', 'create', 'edit'],
                'media_manager' => ['view', 'create', 'delete'],
                'finance' => ['view'],
                'payments' => ['view'],
                'withdrawals' => ['view', 'create'],
                'transactions' => ['view'],
                'reviews' => ['view', 'edit'],
                'settings' => ['view', 'create', 'edit', 'delete'],
                'support' => ['view', 'create', 'edit', 'delete']
            ];
            if (isset($vendorAllowed[$module]) && in_array($action, $vendorAllowed[$module])) {
                return true;
            }
            return false;
        }

        // Check loaded permissions matrix
        if (isset($this->userPermissions[$module][$action]) && $this->userPermissions[$module][$action] == 1) {
            return true;
        }

        return false;
    }

    /**
     * Check if current user has ANY permission (view, create, edit, delete) for a module
     */
    public function hasAnyPermission($module)
    {
        $role = \Core\Session::get('user_role');
        if ($role === 'super_admin') {
            return true;
        }

        if ($role === 'vendor') {
            if (!is_vendor_module_enabled()) {
                return false;
            }
            $vendorAllowedModules = [
                'dashboard', 'products', 'inventory', 'all_orders', 
                'pending_payments', 'orders', 'vendor_portal', 
                'vendor_management', 'category_requests', 'categories', 
                'subcategories', 'attributes', 'media_manager',
                'finance', 'payments', 'withdrawals', 'transactions',
                'reviews', 'settings', 'support'
            ];
            return in_array($module, $vendorAllowedModules);
        }

        if (isset($this->userPermissions[$module])) {
            foreach (['view', 'create', 'edit', 'delete'] as $action) {
                if (isset($this->userPermissions[$module][$action]) && $this->userPermissions[$module][$action] == 1) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Require permission or redirect
     */
    protected function requirePermission($module, $action)
    {
        if (!$this->hasPermission($module, $action)) {
            $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
                   || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
            if ($isAjax) {
                if (ob_get_length()) ob_clean();
                header('Content-Type: application/json', true, 403);
                echo json_encode(['success' => false, 'error' => 'You do not have permission to perform this action.']);
                exit;
            }

            $role = \Core\Session::get('user_role');
            if ($role === 'buyer') {
                \Core\Session::setFlash('error', 'You are currently signed in as a Buyer. Management modules require an Admin or Vendor account.');
                $this->redirect('/dashboard');
                return;
            }

            \Core\Session::setFlash('error', 'You do not have permission to perform this action.');
            
            $currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            // If they are bouncing back to dashboard (or already on it) but don't have dashboard access
            if ($currentUri === '/admin/dashboard' || $module === 'dashboard') {
                
                // Try to find the first module they DO have view access to
                $fallbackRoutes = [
                    'dashboard'          => '/admin/dashboard',
                    'all_orders'         => '/admin/orders',
                    'pending_payments'   => '/admin/orders/pending',
                    'bill_modifications' => '/admin/order-modifications',
                    'products'           => '/admin/catalog/products',
                    'categories'         => '/admin/catalog/categories',
                    'subcategories'      => '/admin/catalog/subcategories',
                    'attributes'         => '/admin/catalog/attributes',
                    'inventory'          => '/admin/inventory',
                    'all_buyers'         => '/admin/buyers',
                    'pending_buyers'     => '/admin/buyers/pending',
                    'all_cancellations'  => '/admin/cancellations',
                    'refunds'            => '/admin/cancellations/refunds',
                    'cancellation_rules' => '/admin/cancellations/rules',
                    'reviews'            => '/admin/reviews',
                    'all_vendors'        => '/admin/vendors',
                    'pending_vendors'    => '/admin/vendors/pending',
                    'vendor_analytics'   => '/admin/vendors/dashboard',
                    'payments'           => '/admin/finance/payments',
                    'withdrawals'        => '/admin/finance/withdrawals',
                    'commissions'        => '/admin/finance/commissions',
                    'transactions'       => '/admin/finance/transactions',
                    'order_rules'        => '/admin/order-rules',
                    'fee_rules'          => '/admin/fee-rules',
                    'fabric_customizations' => '/admin/fabric-customizations',
                    'custom_orders'      => '/admin/customize/orders',
                    'staff_management'   => '/admin/staff',
                    'roles'              => '/admin/staff/roles',
                    'media_manager'      => '/admin/media',
                    'support'            => '/admin/support',
                    'settings'           => '/admin/settings'
                ];
                
                foreach ($fallbackRoutes as $mod => $route) {
                    if (($this->hasPermission($mod, 'view') || $this->hasAnyPermission($mod)) && $currentUri !== $route) {
                        $this->redirect($route);
                    }
                }
                
                // If they have NO view permissions anywhere
                echo "<div style='font-family:sans-serif;text-align:center;margin-top:100px;'>
                        <h2 style='color:#e53e3e;'>Access Denied</h2>
                        <p>Your account does not have any active permissions assigned.</p>
                        <form action='".BASE_URL."/logout' method='POST'>
                            <button type='submit' style='padding:10px 20px; background:#2d5939; color:white; border:none; border-radius:5px; cursor:pointer;'>Logout</button>
                        </form>
                      </div>";
                exit;
            } else {
                $this->redirect('/admin/dashboard');
            }
        }
    }
    /**
     * Render a view file within a layout.
     *
     * @param string $view The view path (e.g., 'storefront/home')
     * @param array $data Data to extract into the view
     * @param string $layout The layout to use (default: 'main')
     */
    protected function render(string $view, array $data = [], string $layout = 'main')
    {
        // Extract data to make variables available in the view
        extract($data);

        // Capture the view content
        ob_start();
        $viewFile = APP_PATH . '/Views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            throw new \Exception("View file not found: {$viewFile}");
        }
        $content = ob_get_clean();

        // Include the layout and pass the content to it
        $layoutFile = APP_PATH . '/Views/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            // Fallback if no layout exists, just output content
            echo $content;
        }
    }

    /**
     * Redirect to a specific URL
     */
    protected function redirect(string $url)
    {
        header("Location: " . BASE_URL . $url);
        exit;
    }

    /**
     * Send a JSON response and exit
     */
    protected function json($data, int $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
