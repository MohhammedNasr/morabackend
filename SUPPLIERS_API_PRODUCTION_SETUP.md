# Suppliers API - Production Setup Guide

## Problem Fixed
The 419 CSRF error on `/admin/suppliers/datatable` endpoint has been resolved by:

1. **Rewritten Datatable API** - The datatable method now properly handles DataTables requests with search, pagination, and sorting
2. **CSRF Token Exclusion** - Admin routes are excluded from CSRF verification for Bearer token authentication
3. **CORS Configuration** - Updated to allow cross-origin requests from localhost frontend to production backend
4. **Sanctum Configuration** - Configured to support both local and production domains

## Changes Made

### 1. Controller Updates (`app/Http/Controllers/Admin/SupplierController.php`)

#### Updated Methods:
- **`datatable()`** - Completely rewritten to handle DataTables requests properly with:
  - Search functionality across multiple fields
  - Server-side pagination
  - Column sorting
  - Error handling
  - JSON response format compatible with DataTables

- **`index()`** - Added JSON support for API requests
- **`show()`** - Added JSON support for API requests
- **`toggleStatus()`** - NEW method to toggle supplier status
- **`verify()`** - NEW method to verify suppliers

### 2. Middleware Configuration (`app/Http/Middleware/VerifyCsrfToken.php`)

```php
protected $except = [
    'api/*',
    'admin/*',
    'suppliers/datatable',
    'stores/datatable',
    'users/datatable',
    'representatives/datatable',
];
```

### 3. CORS Configuration (`config/cors.php`)

- Added `admin/*` to paths
- Added environment-based frontend URL support
- Explicitly allowed required headers

### 4. Sanctum Configuration (`config/sanctum.php`)

- Added production domain to stateful domains
- Uses custom VerifyCsrfToken middleware

## Deployment Steps

### For Production Server (morabackend.xcgulf.net)

1. **Upload the changed files to production:**
   - `app/Http/Controllers/Admin/SupplierController.php`
   - `app/Http/Middleware/VerifyCsrfToken.php`
   - `config/cors.php`
   - `config/sanctum.php`
   - `.env` (update with production values)

2. **Update Production `.env` file with these values:**
   ```env
   APP_URL=https://morabackend.xcgulf.net
   SESSION_DOMAIN=.xcgulf.net
   SANCTUM_STATEFUL_DOMAINS=localhost:3000,127.0.0.1:3000,morabackend.xcgulf.net
   FRONTEND_URL=http://localhost:3000
   ```

3. **Clear cache on production:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   php artisan view:clear
   ```

4. **Restart PHP-FPM/Web Server:**
   ```bash
   # For Apache
   sudo systemctl restart apache2
   
   # For Nginx + PHP-FPM
   sudo systemctl restart php8.2-fpm
   sudo systemctl restart nginx
   ```

### For Local Development

The local `.env` is already configured. Just ensure your Laravel server is running:
```bash
php artisan serve
```

## API Endpoints Available

### Suppliers Datatable
- **URL:** `POST /api/auth/admin/suppliers/datatable` OR `POST /admin/suppliers/datatable`
- **Auth:** Bearer Token (Sanctum)
- **Request Parameters:**
  - `draw` - Draw counter (DataTables)
  - `start` - Pagination start
  - `length` - Page size
  - `search[value]` - Search query
  - `order[0][column]` - Sort column index
  - `order[0][dir]` - Sort direction (asc/desc)

- **Response:**
  ```json
  {
    "draw": 1,
    "recordsTotal": 100,
    "recordsFiltered": 50,
    "data": [...]
  }
  ```

### Other Supplier Endpoints
- `GET /api/auth/admin/suppliers` - List all suppliers
- `GET /api/auth/admin/suppliers/list` - Get suppliers dropdown list
- `GET /api/auth/admin/suppliers/{id}/json` - Get supplier details
- `POST /api/auth/admin/suppliers/{id}/toggle-status` - Toggle active/verified status
- `POST /api/auth/admin/suppliers/{id}/verify` - Verify supplier

## Testing

### Test the API with curl:
```bash
# Replace YOUR_TOKEN with actual bearer token
curl -X POST https://morabackend.xcgulf.net/admin/suppliers/datatable \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"draw":1,"start":0,"length":10}'
```

### Expected Response:
```json
{
  "draw": 1,
  "recordsTotal": 10,
  "recordsFiltered": 10,
  "data": [
    {
      "id": 1,
      "name": "Supplier Name",
      "contact_name": "Contact Person",
      "email": "email@example.com",
      "phone": "0512345678",
      "address": "Address",
      "commercial_record": "CR123456",
      "payment_term_days": 30,
      "tax_id": "TAX123",
      "bank_account": "BANK123",
      "is_active": 1,
      "is_verified": 1,
      "created_at": "2025-01-01 12:00",
      "updated_at": "2025-01-01 12:00"
    }
  ]
}
```

## Troubleshooting

### If 419 error persists:
1. Ensure the production `.env` has the correct SANCTUM_STATEFUL_DOMAINS
2. Clear all caches on production server
3. Check that the Authorization header is being sent with Bearer token
4. Verify CORS headers in browser network tab

### If CORS error occurs:
1. Check that FRONTEND_URL is set in production `.env`
2. Verify the request origin matches allowed origins
3. Ensure credentials are being sent (withCredentials: true in frontend)

### If 401 Unauthorized:
1. Verify the Bearer token is valid and not expired
2. Check that the user is authenticated as admin
3. Ensure the token is in the Authorization header format: `Bearer {token}`

## Notes

- The API now works with both `/admin/suppliers/datatable` and `/api/auth/admin/suppliers/datatable` routes
- CSRF protection is disabled for admin routes when using Bearer token authentication
- All admin routes require valid Sanctum authentication
- The datatable endpoint supports server-side processing for better performance with large datasets
