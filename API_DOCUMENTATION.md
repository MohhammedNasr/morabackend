# Mora API Documentation

**Base URL**: `http://localhost:8000/api` (Development)  
**Authentication**: Laravel Sanctum (Bearer Token)  
**API Version**: v1

## 📋 Table of Contents
- [Authentication](#authentication)
- [Store Management](#store-management)
- [Order Management](#order-management)
- [Product Management](#product-management)
- [Payment](#payment)
- [Wallet](#wallet)
- [Common Responses](#common-responses)

---

## 🔐 Authentication
sadsadasd
All authenticated endpoints require Bearer token in header:
```
Authorization: Bearer {token}
```

### Register Store
**POST** `/api/auth/register`

**Request Body**:
```json
{
  "store_name": "My Store",
  "owner_name": "John Doe",
  "phone": "966512345678",
  "email": "owner@store.com",
  "password": "password123",
  "password_confirmation": "password123",
  "store_type_id": 1,
  "city_id": 1
}
```

**Response** (200):
```json
{
  "success": true,
  "message": "Registration successful. Please verify your phone.",
  "data": {
    "user_id": 123,
    "phone": "966512345678"
  }
}
```

### Login
**POST** `/api/auth/login`

**Request Body**:
```json
{
  "phone": "966512345678",
  "password": "password123"
}
```

**Response** (200):
```json
{
  "success": true,
  "data": {
    "token": "1|abc123xyz...",
    "user": {
      "id": 123,
      "name": "John Doe",
      "phone": "966512345678",
      "email": "owner@store.com",
      "role": "store_owner"
    }
  }
}
```

### Verify Phone
**POST** `/api/auth/verify`

**Request Body**:
```json
{
  "phone": "966512345678",
  "code": "1234"
}
```

**Response** (200):
```json
{
  "success": true,
  "message": "Phone verified successfully",
  "data": {
    "token": "1|abc123xyz..."
  }
}
```

### Resend Verification Code
**POST** `/api/auth/resend-verification`

**Request Body**:
```json
{
  "phone": "966512345678"
}
```

### Forgot Password
**POST** `/api/auth/forget-password`

**Request Body**:
```json
{
  "phone": "966512345678"
}
```

### Verify Reset Code
**POST** `/api/auth/verify-reset-code`

**Request Body**:
```json
{
  "phone": "966512345678",
  "code": "1234"
}
```

### Reset Password
**POST** `/api/auth/reset-password`

**Request Body**:
```json
{
  "phone": "966512345678",
  "code": "1234",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

---

## 🏪 Store Management

### Get Store Profile
**GET** `/api/store/profile`  
**Auth**: Required

**Response** (200):
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "My Store",
    "owner_name": "John Doe",
    "phone": "966512345678",
    "email": "owner@store.com",
    "logo": "https://...",
    "branches_count": 3,
    "status": "active"
  }
}
```

### Update Store Profile
**PUT** `/api/store/profile`  
**Auth**: Required

**Request Body**:
```json
{
  "name": "Updated Store Name",
  "email": "newemail@store.com",
  "logo": "base64_encoded_image"
}
```

### Get Store Branches
**GET** `/api/branches`  
**Auth**: Required

**Response** (200):
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Main Branch",
      "address": "123 Main St",
      "city": "Riyadh",
      "balance_limit": 50000.00,
      "current_balance": 25000.00,
      "status": "active"
    }
  ]
}
```

---

## 📦 Order Management

### Get Orders
**GET** `/api/orders`  
**Auth**: Required

**Query Parameters**:
- `status` (optional): pending, confirmed, processing, delivered, cancelled
- `page` (optional): Page number
- `per_page` (optional): Items per page (default: 15)

**Response** (200):
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "order_number": "ORD-20251019-001",
        "total": 1500.00,
        "status": "pending",
        "created_at": "2025-10-19T10:00:00Z",
        "items_count": 5
      }
    ],
    "total": 50,
    "per_page": 15,
    "last_page": 4
  }
}
```

### Get Order Details
**GET** `/api/orders/{id}`  
**Auth**: Required

**Response** (200):
```json
{
  "success": true,
  "data": {
    "id": 1,
    "order_number": "ORD-20251019-001",
    "status": "pending",
    "total": 1500.00,
    "subtotal": 1300.00,
    "tax": 195.00,
    "delivery_fee": 5.00,
    "items": [
      {
        "id": 1,
        "product_name": "Product A",
        "quantity": 2,
        "price": 100.00,
        "total": 200.00
      }
    ],
    "delivery_address": {
      "address": "123 Main St",
      "city": "Riyadh"
    }
  }
}
```

### Create Order
**POST** `/api/orders`  
**Auth**: Required

**Request Body**:
```json
{
  "branch_id": 1,
  "supplier_id": 5,
  "items": [
    {
      "product_id": 10,
      "quantity": 2,
      "price": 100.00
    }
  ],
  "notes": "Please deliver before 5 PM"
}
```

### Update Order Status
**PATCH** `/api/orders/{id}/status`  
**Auth**: Required (Admin/Representative)

**Request Body**:
```json
{
  "status": "confirmed",
  "notes": "Order confirmed and processing"
}
```

---

## 💰 Payment

### Initialize Payment
**POST** `/api/payment/initialize`  
**Auth**: Required

**Request Body**:
```json
{
  "order_id": 123,
  "payment_method": "hyperpay",
  "amount": 1500.00
}
```

**Response** (200):
```json
{
  "success": true,
  "data": {
    "checkout_id": "abc123xyz",
    "payment_url": "https://payment-gateway.com/checkout/abc123xyz"
  }
}
```

### Payment Webhook
**POST** `/api/payment/webhook`  
**Auth**: Not required (webhook)

This endpoint receives payment gateway callbacks.

---

## 👛 Wallet

### Get Wallet Balance
**GET** `/api/wallet/balance`  
**Auth**: Required

**Response** (200):
```json
{
  "success": true,
  "data": {
    "balance": 5000.00,
    "currency": "SAR"
  }
}
```

### Get Wallet Transactions
**GET** `/api/wallet/transactions`  
**Auth**: Required

**Query Parameters**:
- `type` (optional): credit, debit
- `page` (optional): Page number

**Response** (200):
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "type": "credit",
        "amount": 1000.00,
        "description": "Payment for Order #123",
        "created_at": "2025-10-19T10:00:00Z"
      }
    ]
  }
}
```

---

## 📋 Common Responses

### Success Response
```json
{
  "success": true,
  "data": { /* response data */ },
  "message": "Operation successful"
}
```

### Error Response
```json
{
  "success": false,
  "error": "Error message",
  "errors": {
    "field_name": ["Validation error message"]
  }
}
```

### HTTP Status Codes
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

---

## 📝 Notes

### Localization
API supports multiple languages via `Accept-Language` header:
```
Accept-Language: ar
```
Supported: `en`, `ar`

### Pagination
Most list endpoints support pagination:
- `page`: Current page number
- `per_page`: Items per page (default: 15, max: 100)

### Date Format
All dates in ISO 8601 format: `2025-10-19T10:00:00Z`

### File Uploads
Files should be base64 encoded or multipart/form-data

---

## 🔗 Postman Collections

Import these Postman collections for testing:
- `backend/mora-api.postman_collection.json`
- `backend/market-owner-api.postman_collection.json`

---

**Last Updated**: October 2025  
**Maintained by**: Backend Team
