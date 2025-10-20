# Backend API Routes Analysis

## 🔹 API Routes (`/api/*`) - For Mobile App
**Base URL**: `https://morabackend.xcgulf.net/api`
**Authentication**: Bearer Token (Sanctum)

### Public Routes (No Auth Required)
- `GET /api/banks` - Get list of banks
- `POST /api/payment/webhook` - Payment webhook handler
- `POST /api/webhooks/tap` - Tap payment webhook

### Authentication Routes (`/api/auth/*`)
- `POST /api/auth/register` - Store owner registration
- `POST /api/auth/login` - Login (phone + password)
- `POST /api/auth/verify` - Verify OTP
- `POST /api/auth/resend-verification` - Resend OTP
- `POST /api/auth/forget-password` - Send password reset OTP
- `POST /api/auth/verify-reset-code` - Verify reset code
- `POST /api/auth/reset-password` - Reset password

#### Admin Auth (For Dashboard)
- `POST /api/auth/admin/login` - Admin login with email/password ✅
- `GET /api/auth/admin/me` - Get current admin user ✅
- `POST /api/auth/admin/logout` - Admin logout ✅

#### Supplier Auth
- `POST /api/auth/suppliers/register` - Supplier registration
- `POST /api/auth/suppliers/forget-password`
- `POST /api/auth/suppliers/verify-reset-code`
- `POST /api/auth/suppliers/reset-password`
- `POST /api/auth/suppliers/verify`
- `POST /api/auth/suppliers/resend-verification`

### Protected Routes (Require Bearer Token)
- `POST /api/auth/logout` - Logout
- `GET /api/auth/user` - Get current user
- `DELETE /api/auth/account` - Delete account

### Public Data
- `GET /api/store-types` - Get store types
- `GET /api/cities` - Get cities
- `GET /api/stores` - Get stores list
- `GET /api/stores/{store}/branches` - Get store branches
- `GET /api/settings` - Get app settings
- `PUT /api/settings` - Update settings

### Categories & Products
- `GET /api/categories` - List categories
- `POST /api/categories/products` - Get products by category
- `GET /api/categories/{category}/suppliers` - Get suppliers for category

### Orders
- `GET /api/orders` - List orders
- `POST /api/orders` - Create order
- `GET /api/orders/{order}` - Get order details
- `POST /api/orders/{order}/verify` - Verify order
- `POST /api/orders/{order}/resend-verification`
- `POST /api/orders/{order}/cancel`
- `POST /api/orders/{order}/approve`
- `POST /api/orders/{order}/process`
- `POST /api/orders/{order}/complete`
- `POST /api/orders/{order}/modify`

### Sub-Orders
- `POST /api/sub-orders/{subOrder}/change-status`
- `PUT /api/sub-orders/{subOrder}/modify`

### Suppliers
- `GET /api/suppliers/home` - Supplier dashboard
- `GET /api/suppliers/list-orders` - List sub-orders
- `PUT /api/suppliers/profile` - Update supplier profile
- `GET /api/suppliers` - List suppliers
- `POST /api/suppliers` - Create supplier
- `POST /api/suppliers/by-categories` - Get suppliers by categories
- `GET /api/suppliers/{supplier}` - Get supplier details
- `PUT /api/suppliers/{supplier}` - Update supplier
- `GET /api/suppliers/{supplier}/products` - Get supplier products
- `POST /api/suppliers/{supplier}/products` - Attach product to supplier
- `PUT /api/suppliers/{supplier}/products/{product}` - Update product
- `DELETE /api/suppliers/{supplier}/products/{product}` - Detach product

### Representatives
- `GET /api/representative/home` - Representative dashboard
- `GET /api/representative/list-orders` - List assigned orders
- `GET /api/representative/sub-orders/{subOrder}` - Get sub-order details
- `PUT /api/representative/profile` - Update profile

### Store Owner Routes
- `POST /api/wallet/charge` - Charge wallet
- `GET /api/wallet/balance` - Get wallet balance
- `GET /api/wallet/transactions` - Get transactions
- `POST /api/order-payments/{orderPayment}/pay` - Pay for order
- `GET /api/home` - Store owner dashboard
- `PUT /api/store-profile` - Update store profile
- `GET /api/store/branches` - Get branches
- `POST /api/store/branches` - Create branch
- `GET /api/store/balance-requests` - Get balance requests
- `POST /api/store/balance-requests` - Create balance request

### Promotions
- `POST /api/promotions/validate` - Validate promotion code
- `POST /api/promotions/apply` - Apply promotion

### Notifications
- `GET /api/notifications` - List notifications
- `GET /api/notifications/{id}` - Get notification
- `POST /api/notifications/mark-as-read` - Mark as read
- `DELETE /api/notifications/{id}` - Delete notification
- `POST /api/notifications/register-device-token` - Register FCM token

---

## 🔹 Web Routes (`/admin/*`) - For Dashboard
**Base URL**: `https://morabackend.xcgulf.net`
**Authentication**: Session cookies OR Bearer Token (Both work now)
**CSRF**: Disabled for `/admin/*` routes ✅

### Admin Dashboard Routes
- `GET /admin` - Admin dashboard
- `GET /admin/analytics` - Dashboard analytics (JSON)

### Admin - Representatives
- `POST /admin/representatives/datatable` - DataTables endpoint
- `GET /admin/representatives` - List representatives
- `POST /admin/representatives` - Create representative
- `GET /admin/representatives/{id}` - Get representative
- `PUT /admin/representatives/{id}` - Update representative
- `DELETE /admin/representatives/{id}` - Delete representative

### Admin - Categories
- `POST /admin/categories/datatable` - DataTables endpoint
- `POST /admin/categories/{category}/toggle-status` - Toggle status
- Resource routes for categories

### Admin - Products
- `POST /admin/products/datatable` - DataTables endpoint
- `GET /admin/products/import` - Show import form
- `POST /admin/products/import` - Import products
- `GET /admin/products/export` - Export products
- Resource routes for products

### Admin - Suppliers
- `GET /admin/suppliers/list` - Get suppliers list
- `POST /admin/suppliers/datatable` - DataTables endpoint
- `POST /admin/suppliers/{supplier}/import-products` - Import products
- `PUT /admin/suppliers/{supplier}/status` - Update status
- Resource routes for suppliers

### Admin - Stores
- `POST /admin/stores/datatable` - DataTables endpoint
- `PUT /admin/stores/{store}/status` - Update status
- `GET /admin/stores/{store}/import-products` - Show import form
- `POST /admin/stores/{store}/import-products` - Import products
- `POST /admin/stores/{store}/branches` - Create branch
- Resource routes for stores

### Admin - Users
- `POST /admin/users/datatable` - DataTables endpoint
- `GET /admin/users/{user}/json` - Get user JSON (for editing)
- `PUT /admin/users/{user}/status` - Update status
- Resource routes for users

### Admin - Orders
- `POST /admin/orders/datatable` - DataTables endpoint
- `POST /admin/orders/{order}/approve` - Approve order
- `POST /admin/orders/{order}/reject` - Reject order
- `GET /admin/orders/{order}/invoice` - View invoice
- `GET /admin/orders/{order}/download-invoice` - Download invoice
- `PUT /admin/orders/payments/update-status` - Update payment status
- Resource routes for orders

### Admin - Balance Requests
- `GET /admin/balance-requests` - List balance requests
- `GET /admin/balance-requests/{id}` - Get balance request details
- `POST /admin/balance-requests/{id}/approve` - Approve request
- `POST /admin/balance-requests/{id}/reject` - Reject request

### Admin - Wallets
- `GET /admin/wallets` - List wallets
- `POST /admin/wallets/datatable` - DataTables endpoint
- `PUT /admin/wallets/{wallet}/update-balance` - Update balance

### Admin - Branches
- `POST /admin/branches/datatable` - DataTables endpoint
- `PUT /admin/store-branches/{branch}/balance-limit` - Update balance limit
- `PUT /admin/store-branches/{branch}/toggle-status` - Toggle status

### Admin - Roles
- `POST /admin/roles/datatable` - DataTables endpoint
- Resource routes for roles

### Admin - Promotions
- `POST /admin/promotions/datatable` - DataTables endpoint
- `POST /admin/promotions/{promotion}/toggle-status` - Toggle status
- Resource routes for promotions

---

## 🔑 Authentication Methods

### For Mobile App (Flutter)
**Use**: `/api/auth/login`
**Method**: Bearer Token
**Example**:
```
POST /api/auth/login
{
  "type": "user",  // or "supplier", "representative"
  "phone": "966500000000",
  "password": "password"
}

Response:
{
  "token": "1|abcdefg...",
  "user": {...}
}

// Then use token in headers:
Authorization: Bearer 1|abcdefg...
```

### For Dashboard (Next.js)
**Use**: `/api/auth/admin/login`
**Method**: Bearer Token
**Example**:
```
POST /api/auth/admin/login
{
  "email": "admin@example.com",
  "password": "password"
}

Response:
{
  "success": true,
  "token": "2|xyz123...",
  "user": {
    "id": 1,
    "name": "Admin",
    "email": "admin@example.com",
    "role": {...}
  }
}

// Then use token for all /admin/* requests:
Authorization: Bearer 2|xyz123...
```

---

## ⚠️ Important Notes

1. **All `/api/*` routes**: Use Bearer Token (Sanctum)
2. **All `/admin/*` routes**: Now accept Bearer Token (CSRF disabled) ✅
3. **CSRF Protection**: Disabled for `/admin/*` routes
4. **AdminMiddleware**: Updated to accept both session cookies and Bearer tokens

---

## 🐛 Current Issue

The frontend is getting **419 CSRF** when calling `/admin/users/datatable`.

**Why?**:
- The `/admin/*` routes are defined in `web.php` (not `api.php`)
- Even though we added `admin/*` to CSRF exceptions, the server may not have the updated code yet

**Solution**:
1. Verify server has latest code: `git log -1` on production
2. Clear cache on server: `php artisan config:clear && php artisan route:clear`
3. Or manually pull: `git pull origin develop && php artisan optimize`
