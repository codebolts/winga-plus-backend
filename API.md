# WingA Plus API Documentation

## Overview

WingA Plus is an e-commerce platform API built with Laravel. This documentation covers all available endpoints, authentication, and data structures.

## Base URL
```
http://localhost:8000/api
```

## Authentication

All protected endpoints require Bearer token authentication.

### Headers
```
Authorization: Bearer {token}
Content-Type: application/json
```

### Authentication Endpoints

#### Register User
```http
POST /register
```

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "role": "buyer",
  "phone_number": "0712345678"
}
```

**Response (200):**
```json
{
  "status": "success",
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "buyer",
      "phone_number": "0712345678",
      "photo": null,
      "created_at": "2025-09-20T08:00:00.000000Z",
      "updated_at": "2025-09-20T08:00:00.000000Z"
    },
    "token": "1|abc123..."
  }
}
```

#### Login User
```http
POST /login
```

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response (200):**
```json
{
  "status": "success",
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "buyer"
    },
    "token": "1|abc123..."
  }
}
```

#### Get User Profile
```http
GET /profile
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "status": "success",
  "message": "User profile fetched successfully",
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "buyer",
    "phone_number": "0712345678",
    "photo": "/storage/photos/photo.jpg"
  }
}
```

#### Update User Profile
```http
PUT /profile/update
Authorization: Bearer {token}
```

**Request Body (multipart/form-data):**
```
name: John Doe
email: john@example.com
phone_number: 0712345678
photo: [file]
```

**Response (200):**
```json
{
  "status": "success",
  "message": "Profile updated successfully",
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "phone_number": "0712345678",
    "photo": "/storage/photos/photo.jpg"
  }
}
```

## Business Profile Management

### Get Business Profile (Seller Only)
```http
GET /seller/business-profile
Authorization: Bearer {token} (seller only)
```

**Response (200):**
```json
{
  "status": "success",
  "message": "Business profile retrieved successfully",
  "data": {
    "id": 1,
    "seller_id": 1,
    "business_name": "My Store",
    "description": "Best electronics store",
    "logo": "/storage/business-logos/logo_1_1234567890.jpg",
    "website": "https://mystore.com",
    "free_delivery": true,
    "delivery_cost": 0,
    "delivery_locations": "[\"Nairobi\", \"Mombasa\"]",
    "payment_on_delivery": true,
    "payment_before_delivery": false,
    "business_address": "123 Main St, Nairobi",
    "business_phone": "+254712345678",
    "created_at": "2025-09-20T08:00:00.000000Z",
    "updated_at": "2025-09-20T08:00:00.000000Z"
  }
}
```

### Create Business Profile (Seller Only)
```http
POST /seller/business-profile
Authorization: Bearer {token} (seller only)
```

**Request Body (multipart/form-data):**
```
business_name: My Store
description: Best electronics store
website: https://mystore.com
logo: [logo_image_file]
free_delivery: true
delivery_cost: 0
delivery_locations: ["Nairobi", "Mombasa"]
payment_on_delivery: true
payment_before_delivery: false
business_address: 123 Main St, Nairobi
business_phone: +254712345678
```

**Response (201):**
```json
{
  "status": "success",
  "message": "Business profile created successfully",
  "data": {
    "id": 1,
    "seller_id": 1,
    "business_name": "My Store",
    "description": "Best electronics store",
    "logo": "/storage/business-logos/logo_1_1234567890.jpg",
    "website": "https://mystore.com",
    "free_delivery": true,
    "delivery_cost": 0,
    "delivery_locations": "[\"Nairobi\", \"Mombasa\"]",
    "payment_on_delivery": true,
    "payment_before_delivery": false,
    "business_address": "123 Main St, Nairobi",
    "business_phone": "+254712345678"
  }
}
```

### Update Business Profile (Seller Only)
```http
PUT /seller/business-profile
Authorization: Bearer {token} (seller only)
```

**Request Body (multipart/form-data):**
```
business_name: My Store
description: Best electronics store
website: https://mystore.com
logo: [logo_image_file]
free_delivery: true
delivery_cost: 0
delivery_locations: ["Nairobi", "Mombasa"]
payment_on_delivery: true
payment_before_delivery: false
business_address: 123 Main St, Nairobi
business_phone: +254712345678
```

**Response (200):**
```json
{
  "status": "success",
  "message": "Business profile updated successfully",
  "data": {
    "id": 1,
    "seller_id": 1,
    "business_name": "My Store",
    "description": "Best electronics store",
    "logo": "/storage/business-logos/logo_1_1234567890.jpg",
    "website": "https://mystore.com",
    "free_delivery": true,
    "delivery_cost": 0,
    "delivery_locations": "[\"Nairobi\", \"Mombasa\"]",
    "payment_on_delivery": true,
    "payment_before_delivery": false,
    "business_address": "123 Main St, Nairobi",
    "business_phone": "+254712345678"
  }
}
```

### Delete Business Profile (Seller Only)
```http
DELETE /seller/business-profile
Authorization: Bearer {token} (seller only)
```

### Get Delivery Settings (Public)
```http
GET /sellers/{sellerId}/delivery-settings
```

**Response (200):**
```json
{
  "status": "success",
  "message": "Delivery settings retrieved",
  "data": {
    "free_delivery": true,
    "delivery_cost": 0,
    "payment_on_delivery": true,
    "payment_before_delivery": false,
    "delivery_locations": "[\"Nairobi\", \"Mombasa\"]",
    "business_address": "123 Main St, Nairobi",
    "business_phone": "+254712345678"
  }
}
```

### Get Business Profile
```http
GET /seller/business-profile
Authorization: Bearer {token} (seller only)
```

## Categories & Subcategories

### Get All Categories
```http
GET /categories
```

**Response (200):**
```json
{
  "status": "success",
  "message": "Categories retrieved successfully",
  "data": [
    {
      "id": 1,
      "name": "Electronics",
      "created_at": "2025-09-20T08:00:00.000000Z",
      "updated_at": "2025-09-20T08:00:00.000000Z"
    }
  ]
}
```

### Get Single Category
```http
GET /categories/{id}
```

### Get Subcategories for Category
```http
GET /categories/{category}/subcategories
Authorization: Bearer {token}
```

### Get All Subcategories
```http
GET /subcategories
```

### Get Single Subcategory
```http
GET /subcategories/{id}
```

## Products

### Browse Products (Buyer)
```http
GET /products?search=laptop&min_price=100&max_price=1000
```

**Query Parameters:**
- `search`: Search term
- `min_price`: Minimum price
- `max_price`: Maximum price

**Response (200):**
```json
{
  "status": "success",
  "message": "Products retrieved successfully",
  "data": [
    {
      "id": 1,
      "name": "Gaming Laptop",
      "description": "High-performance gaming laptop",
      "price": 1500.00,
      "stock": 10,
      "image": "/storage/products/laptop.jpg",
      "seller": {
        "id": 1,
        "name": "Tech Store"
      },
      "images": [
        {
          "id": 1,
          "image_path": "/storage/products/laptop_1.jpg",
          "position": 1
        }
      ]
    }
  ]
}
```

### Get Single Product
```http
GET /products/{id}
```

### Get Home Page Data
```http
GET /home
```

**Response (200):**
```json
{
  "status": "success",
  "message": "Home data retrieved successfully",
  "data": {
    "popular_products": [...],
    "recently_added": [...]
  }
}
```

### Get Product Reviews
```http
GET /products/{product}/reviews
```

## Seller Product Management

### Create Product
```http
POST /seller/products
Authorization: Bearer {token} (seller only)
```

**Request Body (multipart/form-data):**
```
name: Gaming Laptop
price: 1500.00
stock: 10
category_id: 1
subcategory_id: 2
description: High-performance gaming laptop
image: [main_image_file]
images[]: [additional_image_1]
images[]: [additional_image_2]
custom_attributes: {"color": "black", "ram": "16GB"}
```

**Response (200):**
```json
{
  "status": "success",
  "message": "Product created successfully",
  "data": {
    "id": 1,
    "name": "Gaming Laptop",
    "price": 1500.00,
    "stock": 10,
    "image": "/storage/products/laptop.jpg",
    "images": [
      {
        "id": 1,
        "image_path": "/storage/products/laptop_1.jpg",
        "position": 1
      }
    ]
  }
}
```

### Update Product
```http
PUT /seller/products/{id}
Authorization: Bearer {token} (seller only)
```

**Request Body:** Same as create, all fields optional for update.

### Get Seller's Products
```http
GET /seller/products?page=1&per_page=15
Authorization: Bearer {token} (seller only)
```

### Get Single Product
```http
GET /seller/products/{id}
Authorization: Bearer {token} (seller only)
```

### Delete Product
```http
DELETE /seller/products/{id}
Authorization: Bearer {token} (seller only)
```

## Orders

### Buyer Order Management

#### Create Order
```http
POST /buyer/orders
Authorization: Bearer {token} (buyer only)
```

**Request Body:**
```json
{
  "items": [
    {
      "product_id": 1,
      "quantity": 2
    }
  ],
  "delivery_address": "123 Main Street, Nairobi, Kenya",
  "delivery_location": "Nairobi CBD",
  "payment_method": "on_delivery",
  "special_instructions": "Please call before delivery"
}
```

**Request Parameters:**
- `delivery_address`: Required delivery address (max 500 chars)
- `delivery_location`: Optional specific location/area
- `payment_method`: Required - "on_delivery" or "before_delivery"
- `special_instructions`: Optional delivery instructions (max 500 chars)

**Delivery Cost Calculation:**
- If seller offers free delivery: $0
- If seller has custom delivery cost: Use seller's rate
- Default delivery cost: $5.00
- Multi-seller orders: $5.00 (default)

**Payment Validation:**
- Validates payment method against seller's accepted payment options
- Single seller orders respect seller's payment preferences
- Multi-seller orders allow both payment methods

#### Get Buyer's Orders
```http
GET /buyer/orders
Authorization: Bearer {token} (buyer only)
```

#### Get Single Order
```http
GET /buyer/orders/{id}
Authorization: Bearer {token} (buyer only)
```

#### Cancel Order
```http
POST /buyer/orders/{id}/cancel
Authorization: Bearer {token} (buyer only)
```

### Seller Order Management

#### Get Seller's Orders
```http
GET /seller/orders
Authorization: Bearer {token} (seller only)
```

#### Get Single Order
```http
GET /seller/orders/{id}
Authorization: Bearer {token} (seller only)
```

#### Update Order Status
```http
PUT /seller/orders/{id}/status
Authorization: Bearer {token} (seller only)
```

**Request Body:**
```json
{
  "status": "shipped",
  "special_instructions": "Package will arrive tomorrow"
}
```

**Request Parameters:**
- `status`: Required - "pending", "completed", "cancelled", "shipped", "delivered"
- `special_instructions`: Optional - Update delivery instructions (max 500 chars)

**Status Flow:**
- `pending` → `shipped` → `delivered` → `completed`
- `pending` → `cancelled`
- When status changes to "delivered", `delivered_at` timestamp is automatically set

#### Mark Order as Completed (Legacy)
```http
POST /seller/orders/{id}/complete
Authorization: Bearer {token} (seller only)
```

## Wishlist Management (Buyer Only)

### Get User's Wishlist
```http
GET /wishlist
Authorization: Bearer {token} (buyer only)
```

**Response (200):**
```json
{
  "status": "success",
  "message": "Wishlist retrieved successfully",
  "data": [
    {
      "id": 1,
      "buyer_id": 1,
      "product_id": 1,
      "created_at": "2025-09-20T08:00:00.000000Z",
      "product": {
        "id": 1,
        "name": "Gaming Laptop",
        "price": 1500.00,
        "image": "/storage/products/laptop.jpg",
        "seller": {
          "id": 1,
          "name": "Tech Store"
        },
        "images": [...]
      }
    }
  ]
}
```

### Add Product to Wishlist
```http
POST /wishlist
Authorization: Bearer {token} (buyer only)
```

**Request Body:**
```json
{
  "product_id": 1
}
```

**Response (201):**
```json
{
  "status": "success",
  "message": "Product added to wishlist",
  "data": {
    "id": 1,
    "buyer_id": 1,
    "product_id": 1,
    "product": {...}
  }
}
```

### Remove Product from Wishlist
```http
DELETE /wishlist/{productId}
Authorization: Bearer {token} (buyer only)
```

### Check if Product is in Wishlist
```http
GET /wishlist/check/{productId}
Authorization: Bearer {token} (buyer only)
```

**Response (200):**
```json
{
  "status": "success",
  "message": "Wishlist check completed",
  "data": {
    "in_wishlist": true
  }
}
```

### Move Product from Wishlist to Cart
```http
POST /wishlist/move-to-cart/{productId}
Authorization: Bearer {token} (buyer only)
```

**Request Body:**
```json
{
  "quantity": 2
}
```

## Cart Management (Buyer Only)

### Get User's Cart
```http
GET /cart
Authorization: Bearer {token} (buyer only)
```

**Response (200):**
```json
{
  "status": "success",
  "message": "Cart retrieved successfully",
  "data": {
    "id": 1,
    "items": [
      {
        "id": 1,
        "cart_id": 1,
        "product_id": 1,
        "quantity": 2,
        "product": {
          "id": 1,
          "name": "Gaming Laptop",
          "price": 1500.00,
          "image": "/storage/products/laptop.jpg",
          "seller": {...},
          "images": [...]
        },
        "subtotal": 3000.00
      }
    ],
    "total_items": 2,
    "total_price": 3000.00
  }
}
```

### Add Item to Cart
```http
POST /cart
Authorization: Bearer {token} (buyer only)
```

**Request Body:**
```json
{
  "product_id": 1,
  "quantity": 2
}
```

### Update Cart Item Quantity
```http
PUT /cart/items/{itemId}
Authorization: Bearer {token} (buyer only)
```

**Request Body:**
```json
{
  "quantity": 3
}
```

### Remove Item from Cart
```http
DELETE /cart/items/{itemId}
Authorization: Bearer {token} (buyer only)
```

### Clear Entire Cart
```http
DELETE /cart
Authorization: Bearer {token} (buyer only)
```

### Get Cart Summary
```http
GET /cart/summary
Authorization: Bearer {token} (buyer only)
```

**Response (200):**
```json
{
  "status": "success",
  "message": "Cart summary retrieved",
  "data": {
    "total_items": 2,
    "subtotal": 3000.00,
    "estimated_delivery": 5.00,
    "total": 3005.00
  }
}
```

### Move Item from Cart to Wishlist
```http
POST /cart/move-to-wishlist/{itemId}
Authorization: Bearer {token} (buyer only)
```

## Legal Documents

### Get Terms and Conditions
```http
GET /terms-and-conditions
```

**Response (200):**
```json
{
  "status": "success",
  "message": "Document retrieved successfully",
  "data": {
    "id": 1,
    "type": "terms_and_conditions",
    "title": "Terms and Conditions",
    "content": "# Terms and Conditions\n\n[Full content here...]",
    "version": "1.0",
    "is_active": true,
    "effective_date": "2025-09-20T00:00:00.000000Z",
    "created_at": "2025-09-20T00:00:00.000000Z",
    "updated_at": "2025-09-20T00:00:00.000000Z"
  }
}
```

### Get Privacy Policy
```http
GET /privacy-policy
```

**Response (200):**
```json
{
  "status": "success",
  "message": "Document retrieved successfully",
  "data": {
    "id": 2,
    "type": "privacy_policy",
    "title": "Privacy Policy",
    "content": "# Privacy Policy\n\n[Full content here...]",
    "version": "1.0",
    "is_active": true,
    "effective_date": "2025-09-20T00:00:00.000000Z",
    "created_at": "2025-09-20T00:00:00.000000Z",
    "updated_at": "2025-09-20T00:00:00.000000Z"
  }
}
```

### Get Legal Document by Type
```http
GET /legal-documents/{type}
```

**Parameters:**
- `type`: `terms_and_conditions` or `privacy_policy`

### Get All Active Legal Documents
```http
GET /legal-documents/active
```

**Response (200):**
```json
{
  "status": "success",
  "message": "Active legal documents retrieved successfully",
  "data": [
    {
      "id": 1,
      "type": "terms_and_conditions",
      "title": "Terms and Conditions",
      "version": "1.0",
      "is_active": true,
      "effective_date": "2025-09-20T00:00:00.000000Z"
    },
    {
      "id": 2,
      "type": "privacy_policy",
      "title": "Privacy Policy",
      "version": "1.0",
      "is_active": true,
      "effective_date": "2025-09-20T00:00:00.000000Z"
    }
  ]
}
```

## Reviews

### Create Review
```http
POST /products/{product}/reviews
Authorization: Bearer {token} (buyer only)
```

**Request Body:**
```json
{
  "rating": 5,
  "comment": "Excellent product!"
}
```

### Update Review
```http
PUT /reviews/{id}
Authorization: Bearer {token} (review owner only)
```

### Delete Review
```http
DELETE /reviews/{id}
Authorization: Bearer {token} (review owner only)
```

## Reports (Seller Only)

### Sales Report
```http
GET /reports/sales?period=month&start_date=2025-09-01&end_date=2025-09-30
Authorization: Bearer {token} (seller only)
```

**Query Parameters:**
- `period`: `week`, `month`, `year` (default: `month`)
- `start_date`: Custom start date (YYYY-MM-DD)
- `end_date`: Custom end date (YYYY-MM-DD)

**Response (200):**
```json
{
  "status": "success",
  "message": "Sales report retrieved successfully",
  "data": {
    "period": "month",
    "start_date": "2025-09-01",
    "end_date": "2025-09-30",
    "total_revenue": 15000.00,
    "total_orders": 25,
    "total_quantity_sold": 45,
    "daily_sales": [
      {
        "date": "2025-09-01",
        "revenue": 500.00,
        "total_quantity": 3,
        "total_orders": 2
      }
    ]
  }
}
```

### Product Reports
```http
GET /reports/products?limit=20&sort_by=revenue&sort_order=desc
Authorization: Bearer {token} (seller only)
```

**Query Parameters:**
- `limit`: Number of products to return (default: 20)
- `sort_by`: `revenue`, `quantity`, `orders_count` (default: `revenue`)
- `sort_order`: `asc`, `desc` (default: `desc`)

**Response (200):**
```json
{
  "status": "success",
  "message": "Product reports retrieved successfully",
  "data": [
    {
      "id": 1,
      "name": "Gaming Laptop",
      "price": 1500.00,
      "image": "/storage/products/laptop.jpg",
      "revenue": 7500.00,
      "total_quantity_sold": 5,
      "orders_count": 3,
      "average_price": 1500.00
    }
  ]
}
```

### Customer Report
```http
GET /reports/customers?period=month
Authorization: Bearer {token} (seller only)
```

**Query Parameters:**
- `period`: `week`, `month`, `year` (default: `month`)

**Response (200):**
```json
{
  "status": "success",
  "message": "Customer report retrieved successfully",
  "data": {
    "period": "month",
    "start_date": "2025-09-01",
    "end_date": "2025-09-30",
    "total_customers": 15,
    "new_customers": 3,
    "returning_customers": 12,
    "top_customers": [
      {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "total_orders": 5,
        "total_spent": 2500.00,
        "last_order_date": "2025-09-15"
      }
    ]
  }
}
```

### Dashboard Statistics
```http
GET /reports/dashboard
Authorization: Bearer {token} (seller only)
```

**Response (200):**
```json
{
  "status": "success",
  "message": "Dashboard stats retrieved successfully",
  "data": {
    "total_products": 25,
    "total_orders": 150,
    "total_revenue": 45000.00,
    "monthly_revenue": 8500.00,
    "recent_orders": [
      {
        "id": 101,
        "total_price": 1200.00,
        "buyer_name": "Jane Smith"
      }
    ],
    "top_products": [
      {
        "id": 1,
        "name": "Gaming Laptop",
        "total_sold": 8
      }
    ]
  }
}
```

## Categories Management (Seller Only)

### Create Category
```http
POST /seller/categories
Authorization: Bearer {token} (seller only)
```

**Request Body:**
```json
{
  "name": "New Category"
}
```

### Create Subcategory
```http
POST /seller/subcategories
Authorization: Bearer {token} (seller only)
```

**Request Body:**
```json
{
  "name": "New Subcategory",
  "category_id": 1
}
```

## Promotions

### Get All Promotions
```http
GET /promotions
Authorization: Bearer {token}
```

### Create Promotion
```http
POST /promotions
Authorization: Bearer {token}
```

### Get Single Promotion
```http
GET /promotions/{id}
Authorization: Bearer {token}
```

### Update Promotion
```http
PUT /promotions/{id}
Authorization: Bearer {token}
```

### Delete Promotion
```http
DELETE /promotions/{id}
Authorization: Bearer {token}
```

### Get Active Promotions
```http
GET /promotions/active
```

## Advertisements

### Get All Ads
```http
GET /ads
Authorization: Bearer {token}
```

### Create Ad
```http
POST /ads
Authorization: Bearer {token}
```

### Get Single Ad
```http
GET /ads/{id}
Authorization: Bearer {token}
```

### Update Ad
```http
PUT /ads/{id}
Authorization: Bearer {token}
```

### Delete Ad
```http
DELETE /ads/{id}
Authorization: Bearer {token}
```

### Get Active Ads
```http
GET /ads/active
```

## Messages

### Send Message
```http
POST /messages/send
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "receiver_id": 2,
  "message": "Hello, I'm interested in your product"
}
```

### Get Conversation
```http
GET /messages/conversation/{userId}
Authorization: Bearer {token}
```

### Mark Messages as Read
```http
POST /messages/read/{userId}
Authorization: Bearer {token}
```

### Get Conversations List
```http
GET /messages/conversations
Authorization: Bearer {token}
```

## Error Responses

### Validation Error (422)
```json
{
  "status": "error",
  "message": "Validation failed",
  "data": {
    "name": ["The name field is required."],
    "email": ["The email has already been taken."]
  }
}
```

### Unauthorized (401/403)
```json
{
  "status": "error",
  "message": "Unauthorized",
  "data": null
}
```

### Not Found (404)
```json
{
  "status": "error",
  "message": "Resource not found",
  "data": null
}
```

### Server Error (500)
```json
{
  "status": "error",
  "message": "Internal server error",
  "data": null
}
```

## Data Types

### User
```json
{
  "id": "integer",
  "name": "string",
  "email": "string",
  "role": "buyer|seller",
  "phone_number": "string|null",
  "photo": "string|null",
  "created_at": "datetime",
  "updated_at": "datetime"
}
```

### Product
```json
{
  "id": "integer",
  "name": "string",
  "description": "string|null",
  "price": "decimal",
  "stock": "integer",
  "image": "string|null",
  "seller_id": "integer",
  "category_id": "integer|null",
  "subcategory_id": "integer|null",
  "custom_attributes": "object|null",
  "is_popular": "boolean",
  "is_favourite": "boolean",
  "created_at": "datetime",
  "updated_at": "datetime",
  "seller": "User",
  "category": "Category|null",
  "subcategory": "Subcategory|null",
  "images": "ProductImage[]"
}
```

### Order
```json
{
  "id": "integer",
  "buyer_id": "integer",
  "total_price": "decimal",
  "status": "pending|completed|cancelled|shipped|delivered",
  "delivery_cost": "decimal",
  "delivery_address": "string|null",
  "delivery_location": "string|null",
  "payment_method": "on_delivery|before_delivery",
  "payment_status": "pending|paid|failed",
  "special_instructions": "string|null",
  "delivered_at": "datetime|null",
  "created_at": "datetime",
  "updated_at": "datetime",
  "buyer": "User",
  "items": "OrderItem[]"
}
```

### BusinessProfile
```json
{
  "id": "integer",
  "seller_id": "integer",
  "business_name": "string",
  "description": "string|null",
  "logo": "string|null",
  "website": "string|null",
  "free_delivery": "boolean",
  "delivery_cost": "decimal|null",
  "delivery_locations": "array|null",
  "payment_on_delivery": "boolean",
  "payment_before_delivery": "boolean",
  "business_address": "string|null",
  "business_phone": "string|null",
  "created_at": "datetime",
  "updated_at": "datetime",
  "seller": "User"
}
```

### OrderItem
```json
{
  "id": "integer",
  "order_id": "integer",
  "product_id": "integer",
  "quantity": "integer",
  "price": "decimal",
  "created_at": "datetime",
  "updated_at": "datetime",
  "order": "Order",
  "product": "Product"
}
```

### ProductImage
```json
{
  "id": "integer",
  "product_id": "integer",
  "image_path": "string",
  "position": "integer",
  "created_at": "datetime",
  "updated_at": "datetime",
  "product": "Product"
}
```

### Wishlist
```json
{
  "id": "integer",
  "buyer_id": "integer",
  "product_id": "integer",
  "created_at": "datetime",
  "updated_at": "datetime",
  "buyer": "User",
  "product": "Product"
}
```

### Cart
```json
{
  "id": "integer",
  "buyer_id": "integer",
  "created_at": "datetime",
  "updated_at": "datetime",
  "buyer": "User",
  "items": "CartItem[]"
}
```

### CartItem
```json
{
  "id": "integer",
  "cart_id": "integer",
  "product_id": "integer",
  "quantity": "integer",
  "created_at": "datetime",
  "updated_at": "datetime",
  "cart": "Cart",
  "product": "Product",
  "subtotal": "decimal"
}
```

### LegalDocument
```json
{
  "id": "integer",
  "type": "terms_and_conditions|privacy_policy",
  "title": "string",
  "content": "string",
  "version": "string",
  "is_active": "boolean",
  "effective_date": "datetime|null",
  "created_at": "datetime",
  "updated_at": "datetime"
}
```

## Rate Limiting

- API endpoints are rate limited to prevent abuse
- Authentication endpoints: 10 requests per minute
- General endpoints: 60 requests per minute
- File upload endpoints: 10 requests per minute

## File Upload Limits

- Maximum file size: 2MB per file
- Supported formats: JPEG, PNG, JPG, GIF
- Product images: Up to 5 additional images per product

## Status Codes

- `200`: Success
- `201`: Created
- `400`: Bad Request
- `401`: Unauthorized
- `403`: Forbidden
- `404`: Not Found
- `422`: Validation Failed
- `500`: Internal Server Error
