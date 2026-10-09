const { chromium } = require('playwright');
const fs = require('fs');

(async () => {
    console.log("Starting Playwright E2E Test Suite for GRM B2B...");
    let browser;
    try {
        browser = await chromium.launch({ channel: 'msedge', headless: true });
    } catch (e) {
        browser = await chromium.launch({ channel: 'chrome', headless: true });
    }
    const context = await browser.newContext();
    const page = await context.newPage();

    const results = {
        admin: {},
        staff: {},
        errors: []
    };

    try {
        // --- 1. SUPER ADMIN FLOW ---
        console.log("\n[1] Testing Super Admin Login...");
        await page.goto('http://localhost:8000/login');
        await page.fill('input[name="email"], input[type="email"]', 'admin@grmb2b.com');
        await page.fill('input[name="password"], input[type="password"]', 'SuperAdmin@2026');
        await page.click('button[type="submit"]');
        await page.waitForURL('**/admin/dashboard');
        console.log(" Super Admin successfully logged in to Dashboard.");
        results.admin['dashboard'] = 'OK';

        // Check key admin modules
        const adminPages = [
            { name: 'Orders', url: 'http://localhost:8000/admin/orders' },
            { name: 'Pending Payments', url: 'http://localhost:8000/admin/orders/pending' },
            { name: 'Bill Modifications', url: 'http://localhost:8000/admin/order-modifications' },
            { name: 'Catalog Products', url: 'http://localhost:8000/admin/catalog/products' },
            { name: 'Categories', url: 'http://localhost:8000/admin/catalog/categories' },
            { name: 'Subcategories', url: 'http://localhost:8000/admin/catalog/subcategories' },
            { name: 'Attributes', url: 'http://localhost:8000/admin/catalog/attributes' },
            { name: 'Inventory', url: 'http://localhost:8000/admin/inventory' },
            { name: 'Buyers', url: 'http://localhost:8000/admin/buyers' },
            { name: 'Cancellations', url: 'http://localhost:8000/admin/cancellations' },
            { name: 'Refunds', url: 'http://localhost:8000/admin/cancellations/refunds' },
            { name: 'Cancellation Rules', url: 'http://localhost:8000/admin/cancellations/rules' },
            { name: 'Reviews', url: 'http://localhost:8000/admin/reviews' },
            { name: 'Vendors', url: 'http://localhost:8000/admin/vendors' },
            { name: 'Vendor Analytics', url: 'http://localhost:8000/admin/vendors/dashboard' },
            { name: 'Finance Payments', url: 'http://localhost:8000/admin/finance/payments' },
            { name: 'Finance Withdrawals', url: 'http://localhost:8000/admin/finance/withdrawals' },
            { name: 'Finance Commissions', url: 'http://localhost:8000/admin/finance/commissions' },
            { name: 'Finance Transactions', url: 'http://localhost:8000/admin/finance/transactions' },
            { name: 'Order Rules', url: 'http://localhost:8000/admin/order-rules' },
            { name: 'Fee Rules', url: 'http://localhost:8000/admin/fee-rules' },
            { name: 'Fabric Customizations', url: 'http://localhost:8000/admin/fabric-customizations' },
            { name: 'Customized Orders', url: 'http://localhost:8000/admin/customize/orders' },
            { name: 'Staff Management', url: 'http://localhost:8000/admin/staff' },
            { name: 'Roles & Permissions', url: 'http://localhost:8000/admin/staff/roles' },
            { name: 'Media Manager', url: 'http://localhost:8000/admin/media' },
            { name: 'Support Helpdesk', url: 'http://localhost:8000/admin/support' },
            { name: 'System Settings', url: 'http://localhost:8000/admin/settings' },
            { name: 'CMS Builder', url: 'http://localhost:8000/admin/cms/builder' },
            { name: 'CMS Hero Banners', url: 'http://localhost:8000/admin/cms/hero-banners' },
            { name: 'CMS Size Charts', url: 'http://localhost:8000/admin/cms/size-charts' }
        ];

        for (const p of adminPages) {
            try {
                await page.goto(p.url, { waitUntil: 'domcontentloaded' });
                const title = await page.title();
                const content = await page.content();
                const isError = content.includes('Fatal error') || content.includes('SQLSTATE') || content.includes('Access Denied');
                results.admin[p.name] = isError ? 'ERROR: ' + title : 'OK';
                console.log(` Admin page [${p.name}]: ${results.admin[p.name]}`);
            } catch (err) {
                results.admin[p.name] = 'FAILED: ' + err.message;
                console.log(`❌ Admin page [${p.name}]: FAILED (${err.message})`);
            }
        }

        // Logout Admin
        await page.goto('http://localhost:8000/logout');

        // --- 2. STAFF FLOW (Suguna) ---
        console.log("\n[2] Testing Staff Login (suguna@gmail.com)...");
        await page.goto('http://localhost:8000/login');
        await page.fill('input[name="email"], input[type="email"]', 'suguna@gmail.com');
        await page.fill('input[name="password"], input[type="password"]', 'suguna123');
        await page.click('button[type="submit"]');
        await page.waitForURL('**/admin/dashboard');
        console.log(" Staff successfully logged in to Dashboard.");
        results.staff['dashboard'] = 'OK';

        // Test staff permissions on modules
        for (const p of adminPages) {
            try {
                await page.goto(p.url, { waitUntil: 'domcontentloaded' });
                const currentUrl = page.url();
                const content = await page.content();
                const hasAccessDenied = content.includes('Access Denied') || content.includes('do not have permission');
                const isCms = p.name.startsWith('CMS');
                
                if (isCms) {
                    // Staff should NOT have access to CMS (should be redirected or denied)
                    if (currentUrl.includes('cms')) {
                        results.staff[p.name] = 'SECURITY_LEAK: CMS accessible to staff';
                        console.log(`⚠️ Staff CMS [${p.name}]: SECURITY LEAK - Accessible`);
                    } else {
                        results.staff[p.name] = 'BLOCKED_CORRECTLY';
                        console.log(` Staff CMS [${p.name}]: BLOCKED CORRECTLY (redirected)`);
                    }
                } else {
                    // For standard modules, Suguna has view permissions
                    if (hasAccessDenied) {
                        results.staff[p.name] = 'DENIED_PERMISSION';
                        console.log(`❌ Staff page [${p.name}]: DENIED PERMISSION`);
                    } else if (currentUrl.includes(p.url.replace('http://localhost:8000', ''))) {
                        results.staff[p.name] = 'VIEW_ALLOWED';
                        console.log(` Staff page [${p.name}]: VIEW ALLOWED`);
                    } else {
                        results.staff[p.name] = 'REDIRECTED: ' + currentUrl;
                        console.log(` Staff page [${p.name}]: REDIRECTED to ` + currentUrl);
                    }
                }
            } catch (err) {
                results.staff[p.name] = 'FAILED: ' + err.message;
                console.log(`❌ Staff page [${p.name}]: FAILED (${err.message})`);
            }
        }

    } catch (e) {
        console.error("Test Suite Error: ", e);
        results.errors.push(e.message);
    } finally {
        await browser.close();
        fs.writeFileSync('scratch/playwright_summary.json', JSON.stringify(results, null, 2));
        console.log("\nPlaywright testing complete. Summary written to scratch/playwright_summary.json");
    }
})();
