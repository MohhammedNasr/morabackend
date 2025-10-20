# FastComet Deployment Configuration

## 🌐 Production Server Details

**URL**: https://morabackend.xcgulf.net  
**Server Path**: `/home/xhqmpsycgulf/morabackend`  
**Document Root**: `/home/xhqmpsycgulf/morabackend/public`

---

## 📋 Database Configuration

**Database Name**: `xcgulfco_mora_dev`  
**Database User**: `xcgulfco_mora_user`  
**Database Password**: `*Y6Y{5vK%Zp9Tm=x`  
**Database Host**: `localhost`

---
sd

## 🔧 Production .env Configuration

Copy this configuration to your server at: `/home/xhqmpsycgulf/morabackend/.env`

```env
APP_NAME=Mora
APP_ENV=production
APP_KEY=base64:p1aE1dWP3aKfbWxZ5maaR0qoBlcR5du/l32yJuu1qG0=
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=https://morabackend.xcgulf.net

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file

PHP_CLI_SERVER_WORKERS=4
BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=xcgulfco_mora_dev
DB_USERNAME=xcgulfco_mora_user
DB_PASSWORD=*Y6Y{5vK%Zp9Tm=x

# Session & Cache (use database on shared hosting)
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=morabackend.xcgulf.net

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
CACHE_PREFIX=

# Mail Configuration
MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

VITE_APP_NAME="${APP_NAME}"

# Sanctum SPA Auth
SANCTUM_STATEFUL_DOMAINS=morabackend.xcgulf.net

# Firebase Configuration
FIREBASE_CREDENTIALS="storage/mora-27ae6-firebase-adminsdk-fbsvc-751f63e260.json"
FIREBASE_DATABASE_URL="https://mora-27ae6-default-rtdb.firebaseio.com"
FIREBASE_STORAGE_BUCKET="mora-27ae6.appspot.com"
FIREBASE_PROJECT_ID="mora-27ae6"
FCM_SERVER_KEY="AAAA3e26087:APA91bH6y4cf2c4e3b3edf79280bd78380aa1"

# HyperPay Configuration (Sandbox Mode)
SANDBOX_MODE=true
ENTITY_ID_MADA=
ENTITY_ID_APPLE_PAY=
ENTITY_ID=8ac7a4c79483092601948366b9d1011b
ACCESS_TOKEN=OGFjN2E0Yzc5NDgzMDkyNjAxOTQ4MzY2MzY1ZDAxMTZ8NnpwP1Q9Y3dGTiUyYWN6NmFmQWo=
CURRENCY=SAR

# Tap Payment Configuration
TAP_SECRET_KEY=
TAP_PUBLIC_KEY=
TAP_BASE_URL=https://api.tap.company/v2/
```

---

## 🚀 Deployment Steps

### 1. Update .env on Server

**SSH into your server**:
```bash
cd /home/xhqmpsycgulf/morabackend
nano .env
```

Copy the configuration above, paste it, then save:
- Press `Ctrl + O` (save)
- Press `Enter` (confirm)
- Press `Ctrl + X` (exit)

---

### 2. Run Migrations

```bash
cd /home/xhqmpsycgulf/morabackend

# Generate application key (if not already done)
php artisan key:generate

# Run migrations
php artisan migrate --force

# Optional: Seed database with test data
php artisan db:seed --force

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

### 3. Set Permissions

```bash
cd /home/xhqmpsycgulf/morabackend
chmod -R 755 storage bootstrap/cache
```

---

### 4. Test API

Visit in browser:
```
https://morabackend.xcgulf.net/api/banks
```

Expected: JSON response with bank data

---

## 🔄 Auto-Deploy Configuration

### Deploy Script Location
`/home/xhqmpsycgulf/deploy.php`

### GitHub Webhook URL
`https://morabackend.xcgulf.net/deploy.php`

### Webhook Secret
`mora-xcgulf-webhook-xyz-12345`

---

## 📱 For Flutter Developers

**API Base URL**:
```dart
const String API_BASE_URL = 'https://morabackend.xcgulf.net/api';
```

---

## 🔐 Important Security Notes

1. **Never commit .env file** to Git
2. **Firebase credentials** must be uploaded separately to `storage/` folder
3. **Change SANDBOX_MODE** to `false` when going to production
4. **Set APP_DEBUG** to `false` in production
5. **Use strong passwords** for database

---

## 📊 Key Differences: Local vs Production

| Setting | Local | Production |
|---------|-------|------------|
| APP_ENV | local | production |
| APP_DEBUG | true | **false** |
| APP_URL | localhost:8000 | morabackend.xcgulf.net |
| DB_HOST | 127.0.0.1 | localhost |
| DB_DATABASE | mora | xcgulfco_mora_dev |
| SESSION_DRIVER | file | **database** |
| SESSION_DOMAIN | localhost | morabackend.xcgulf.net |
| LOG_LEVEL | debug | **error** |

---

## 🛠️ Troubleshooting

### Database Connection Failed
```bash
# Test database connection
php artisan tinker
> DB::connection()->getPdo();
```

If error:
- Check database name, username, password
- Verify database user has permissions
- Ensure DB_HOST is `localhost` not `127.0.0.1`

### Permission Errors
```bash
chmod -R 755 storage bootstrap/cache
chown -R xhqmpsycgulf:xhqmpsycgulf storage bootstrap/cache
```

### Clear All Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### View Deployment Logs
```bash
cat /home/xhqmpsycgulf/deploy.log
```

---

## 📞 Support

**Repository**: https://github.com/MohhammedNasr/morabackend  
**Server**: FastComet - xcgulf.net

---

**Last Updated**: October 20, 2025
