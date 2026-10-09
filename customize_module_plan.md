# Customize Module — Analysis & Implementation Plan

## 1. Current State (What Exists)

### ✅ Backend — Fully Built

| File | Status | Notes |
|------|--------|-------|
| [`FabricCustomization.php`](file:///d:/grm-workspace/GRM_B2B/app/Models/FabricCustomization.php) | ✅ Complete | Full CRUD, sizes, product join, stock filtering, image fetching |
| [`FabricCustomizationService.php`](file:///d:/grm-workspace/GRM_B2B/app/Services/FabricCustomizationService.php) | ✅ Complete | `calculateConsumption()` + `calculatePricing()` |
| [`Admin/FabricCustomizationController.php`](file:///d:/grm-workspace/GRM_B2B/app/Controllers/Admin/FabricCustomizationController.php) | ✅ Complete | index, create, store, edit, update, delete, toggleStatus, ajaxProductInfo |
| [`Storefront/FabricCustomizationController.php`](file:///d:/grm-workspace/GRM_B2B/app/Controllers/Storefront/FabricCustomizationController.php) | ✅ Complete | customizeListing, customizeWorkshop, calculateAjax, requestOrderAjax, razorpayInitAjax, razorpayVerifyAjax |
| [`Admin/CustomOrderController.php`](file:///d:/grm-workspace/GRM_B2B/app/Controllers/Admin/CustomOrderController.php) | ✅ Complete | index, updateStatus |
| `Order.php` — `getCustomOrders()` / `getCustomOrderStatusCounts()` | ✅ Complete | Exists in Order model |
| `SubCategory.php` — `getAllActive()` | ✅ Fixed | Just added |

### ✅ Routes — Fully Wired (`public/index.php`)

| Route | Method | Purpose |
|-------|--------|---------|
| `/customize` | GET | Storefront fabric listing |
| `/fabrics/customize` | GET | Workshop detail page |
| `/fabrics/customization/calculate` | POST | AJAX live calc |
| `/fabrics/customization/request-order` | POST | Submit quote request |
| `/fabrics/customization/razorpay-init` | POST | Razorpay init |
| `/fabrics/customization/razorpay-verify` | POST | Razorpay verify |
| `/admin/fabric-customizations` | GET | Admin list |
| `/admin/fabric-customizations/create` | GET | Create form |
| `/admin/fabric-customizations/store` | POST | Save new rule |
| `/admin/fabric-customizations/edit` | GET | Edit form |
| `/admin/fabric-customizations/update` | POST | Save edit |
| `/admin/fabric-customizations/delete` | POST | Delete |
| `/admin/fabric-customizations/toggle-status` | POST | AJAX toggle |
| `/admin/fabric-customizations/ajax-product-info` | GET | AJAX product info |
| `/admin/customize/orders` | GET | Admin custom orders list |
| `/admin/customize/orders/update-status` | POST | Admin status update |

### ✅ Views — All Present

| View | Status |
|------|--------|
| `admin/fabric_customizations/index.php` | ✅ Exists |
| `admin/fabric_customizations/create.php` | ✅ Exists |
| `admin/fabric_customizations/edit.php` | ✅ Exists |
| `admin/custom_orders/index.php` | ✅ Exists |
| `storefront/customize_listing.php` | ✅ Exists |
| `storefront/fabric_customize.php` | ✅ Exists (639 lines, full workshop page) |

### ✅ Navigation

| Location | Status |
|----------|--------|
| Admin sidebar — "Customize" dropdown (Fabric Rules + Custom Orders) | ✅ Present |
| Storefront navbar — "Customize" link → `/customize` | ✅ Present |
| Storefront mobile nav — "Customize Workshop" | ✅ Present |

---

## 2. Identified Gaps & Issues

### 🔴 CRITICAL — Missing DB Columns
The `order_items` table INSERT in `requestOrderAjax` and `razorpayVerifyAjax` uses columns `customization_id` and `customization_data`. These columns **may not exist** in the current `order_items` schema.

### 🟡 Medium — Admin Custom Orders View
[`admin/custom_orders/index.php`](file:///d:/grm-workspace/GRM_B2B/app/Views/admin/custom_orders/index.php) needs a **View Detail** page (`show.php`) so admin can see the full breakdown of a custom order (sizes, consumption, payment mode).

### 🟡 Medium — No "View Detail" for Custom Orders
Admin can list orders and update status. But there is no `/admin/customize/orders/view?id=X` page to see the full `customization_data` JSON breakdown.

### 🟡 Medium — Storefront Login Gate
`fabric_customize.php` and `customize_listing.php` do not redirect guests to login. They load but the order submission AJAX correctly enforces login — this is acceptable but an on-page login prompt / redirect banner would improve UX.

### 🟡 Medium — Admin sidebar emoji icons are literal text
The sidebar shows `?? Products / Fabrics`, `?? Fabric Rules`, `?? Custom Orders` — these are placeholder emoji characters that may not render correctly. Need proper SVG icons.

### 🟢 Low — No "fabric_placeholder.png"
`FabricCustomization.php` line 213 falls back to `/assets/images/fabric-placeholder.png`. This file may not exist. Needs to be created or the fallback changed to an existing image.

### 🟢 Low — `getAvailableFabricsForDropdown()` has dead parameter
The `$excludeCustomizationId` parameter in `FabricCustomization.php::getAvailableFabricsForDropdown()` is accepted but never used in the query.

---

## 3. Implementation Plan

### Phase 1 — Database Safety Check (Do First)
> **Goal:** Ensure all required DB columns exist before any UI changes.

**Step 1.1** — Run SQL migration to add missing columns to `order_items`:
```sql
ALTER TABLE order_items 
  ADD COLUMN IF NOT EXISTS customization_id INT NULL DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS customization_data LONGTEXT NULL DEFAULT NULL;
```

**Step 1.2** — Verify `fabric_customizations` and `fabric_customization_sizes` tables exist with correct schema.

---

### Phase 2 — Fix Admin Views (High Impact)

**Step 2.1 — Fix Admin Sidebar Icons**
- File: [`admin.php`](file:///d:/grm-workspace/GRM_B2B/app/Views/layouts/admin.php) lines ~640–660
- Replace `?? Products / Fabrics`, `?? Fabric Rules`, `?? Custom Orders` text with proper SVG icons matching the existing admin sidebar icon style.

**Step 2.2 — Build Admin Custom Order Detail View**
- Create: `app/Views/admin/custom_orders/show.php`
- Add route: `GET /admin/customize/orders/view` → `CustomOrderController@show`
- Add `show()` method to `CustomOrderController.php`
- Shows: order header, customer info, size breakdown table (from `customization_data` JSON), payment mode badge, status update control

---

### Phase 3 — Fix Storefront UX

**Step 3.1 — Add Login Gate Banner to Workshop Page**
- File: [`fabric_customize.php`](file:///d:/grm-workspace/GRM_B2B/app/Views/storefront/fabric_customize.php)
- If `$userId` is null: show a sticky banner "Login to place your custom order" with a login button
- This is already partially handled in JS but needs a visible PHP-rendered fallback

**Step 3.2 — Add Fabric Placeholder Image**
- Create or copy a placeholder image to `public/assets/images/fabric-placeholder.png`
- Or update the fallback in `FabricCustomization.php` to use an existing image path

---

### Phase 4 — Polish & Verify End-to-End Flow

**Step 4.1** — Test admin creates Fabric Rule: `/admin/fabric-customizations/create` (fixed ✅ with `getAllActive()`)
**Step 4.2** — Test storefront listing: `/customize`
**Step 4.3** — Test workshop page: `/fabrics/customize?fabric_id=X`
**Step 4.4** — Test AJAX calculate (live price update)
**Step 4.5** — Test submit custom order request (without payment)
**Step 4.6** — Test admin custom orders list: `/admin/customize/orders`

---

## 4. Execution Order

```
Phase 1.1 → DB migration (SQL)
Phase 1.2 → Verify tables
Phase 2.1 → Fix sidebar icons
Phase 2.2 → Admin order detail view + route + controller method
Phase 3.1 → Storefront login gate banner
Phase 3.2 → Placeholder image
Phase 4   → End-to-end manual test
```

---

## 5. Files That Need Changes

| File | Action |
|------|--------|
| Database (SQL) | ALTER order_items — add customization_id, customization_data |
| [`admin.php`](file:///d:/grm-workspace/GRM_B2B/app/Views/layouts/admin.php) | Fix sidebar icon placeholders |
| `app/Views/admin/custom_orders/show.php` | CREATE new detail view |
| [`CustomOrderController.php`](file:///d:/grm-workspace/GRM_B2B/app/Controllers/Admin/CustomOrderController.php) | Add `show()` method |
| `public/index.php` | Add GET route for custom order view |
| [`fabric_customize.php`](file:///d:/grm-workspace/GRM_B2B/app/Views/storefront/fabric_customize.php) | Add login gate banner |
| [`FabricCustomization.php`](file:///d:/grm-workspace/GRM_B2B/app/Models/FabricCustomization.php) | Fix placeholder image path |
