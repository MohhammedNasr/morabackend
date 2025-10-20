# Fix CORS for Frontend Login

## Issue
Getting "419 Page Expired" when logging in from Next.js frontend at `localhost:3000`.

## Solution

### Step 1: Update .env on Server

SSH into FastComet and edit:
```bash
cd /home/xcgulfco/morabackend
nano .env
```

Update these values:
```env
# Remove or empty SESSION_DOMAIN
SESSION_DOMAIN=

# Add frontend URL to Sanctum stateful domains
SANCTUM_STATEFUL_DOMAINS=localhost:3000,127.0.0.1:3000,morabackend.xcgulf.net
```

### Step 2: Update config/cors.php

Edit the CORS configuration:
```bash
nano config/cors.php
```

Ensure it has:
```php
'paths' => ['api/*', 'sanctum/csrf-cookie', 'admin/*', 'web/*'],

'allowed_origins' => [
    'http://localhost:3000',
    'http://127.0.0.1:3000',
    'https://morabackend.xcgulf.net',
],

'allowed_origins_patterns' => [],

'allowed_headers' => ['*'],

'exposed_headers' => [],

'max_age' => 0,

'supports_credentials' => true,
```

### Step 3: Update config/sanctum.php

Edit:
```bash
nano config/sanctum.php
```

Make sure:
```php
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
    '%s%s',
    'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
    Sanctum::currentApplicationUrlWithPort()
))),
```

### Step 4: Clear All Caches

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:cache
php artisan config:cache
```

### Step 5: Restart Frontend

On your local machine:
```bash
cd frontend
# Stop the dev server (Ctrl + C)
npm run dev
```

## Test

Visit `http://localhost:3000/login` and try logging in.

Should work now! ✅
