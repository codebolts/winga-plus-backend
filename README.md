# WingA Plus Backend

An online marketplace built with Laravel, featuring a comprehensive e-commerce platform with user authentication, product management, and more.

## Features

- **User Authentication**: Registration, login, and profile management with Sanctum tokens
- **Role-based Access**: Separate controllers for buyers and sellers
- **Product Management**: Sellers can create and manage products with multiple images
- **Categories & Subcategories**: Organized product categorization
- **Order Management**: Complete order lifecycle for buyers and sellers
- **Reviews System**: Product reviews and ratings
- **Promotions**: Special offers and discounts
- **Advertisements**: Featured ads management

## Product Images Feature

Products now support up to 5 additional images separate from the main product image:

- **Main Image**: Single primary product image (existing functionality)
- **Additional Images**: Up to 5 extra images stored in a separate `product_images` table
- **API Endpoints**:
  - `POST /api/seller/products`: Create product with `images[]` array (max 5 files)
  - `PUT /api/seller/products/{id}`: Update product images (replaces existing additional images)
- **Storage**: Images are stored in `storage/app/public/products/` with proper cleanup on updates/deletes
- **Response**: Product responses include `images` array ordered by position

## Installation

1. Clone the repository
2. Run `composer install`
3. Copy `.env.example` to `.env` and configure your database
4. Run `php artisan key:generate`
5. Run `php artisan migrate`
6. Run `php artisan serve`

## API Documentation

For detailed API documentation including all endpoints, request/response examples, and data structures, see [API.md](API.md).

### Quick Overview

The API uses standard REST conventions with JSON responses.

**Base URL:** `http://localhost:8000/api`

**Authentication:** Bearer token required for protected endpoints

### Key Endpoints

- **Authentication**: `POST /register`, `POST /login`, `GET /profile`, `PUT /profile/update`
- **Products**: `GET /products`, `GET /seller/products`, `POST /seller/products`
- **Orders**: `GET /buyer/orders`, `GET /seller/orders`
- **Reports**: `GET /reports/sales`, `GET /reports/products`, `GET /reports/customers`, `GET /reports/dashboard`
- **Categories**: `GET /categories`, `GET /subcategories`

### Authentication

All protected routes require Bearer token authentication:
```
Authorization: Bearer {token}
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## License

This project is licensed under the MIT License.
