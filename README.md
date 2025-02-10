# Maju Mundur API - Quickstart Testing Guide (Postman)

This guide provides a simplified walkthrough for testing the Maju Mundur API using Postman.

## 1. Setup

1.  **Install Postman:** Download and install Postman: [https://www.postman.com/downloads/](https://www.postman.com/downloads/)
2.  **Start API Server:** Make sure your Laravel API is running (usually `php artisan serve`).
3.  **Database:** Ensure your database is set up, migrated, and seeded (`php artisan migrate:fresh --seed`).

## 2. Postman Environment

Create a Postman environment (e.g., "Maju Mundur Dev") with these variables:

*   `base_url`: `http://localhost:8000/api` (or your API's base URL).
*   `merchant_token`: (Leave blank - you'll get this after registering/logging in).
*   `customer_token`: (Leave blank - you'll get this after registering/logging in).

Select this environment in Postman.

## 3. Key API Endpoints and Tests

This section outlines the main API calls and how to test them. Remember to set the `Content-Type: application/json` header for requests with a JSON body.

### 3.1 Authentication

*   **Register Merchant:**
    *   `POST {{base_url}}/register`
    *   Body:
        ```json
        {
            "name": "Merchant Test",
            "email": "merchant@example.com",
            "password": "password",
            "is_merchant": true
        }
        ```
    *   **Copy the `token` from the response and save it to `merchant_token`.**

*   **Register Customer:**
    *   `POST {{base_url}}/register`
    *   Body:
        ```json
        {
            "name": "Customer Test",
            "email": "customer@example.com",
            "password": "password",
            "is_merchant": false
        }
        ```
    *   **Copy the `token` from the response and save it to `customer_token`.**

*   **Login Merchant:**
    *   `POST {{base_url}}/login`
    *    **Headers:** `Content-Type: application/json`
    *   Body:
        ```json
        {
            "email": "merchant@example.com",  // Use registered email
            "password": "password",
            "is_merchant": true
        }
        ```
     *   **Copy the `token` from the response and save it to `merchant_token`.**

*   **Login Customer:**
    *   `POST {{base_url}}/login`
    *   **Headers:** `Content-Type: application/json`
    *   Body:
        ```json
        {
            "email": "customer@example.com",  // Use registered email
            "password": "password",
            "is_merchant": false
        }
        ```
    *   **Copy the `token` from the response and save it to `customer_token`.**

* **Get Current User**
  * `GET {{base_url}}/user`
  * Headers: `Authorization: Bearer {{merchant_token}}` or `Authorization: Bearer {{customer_token}}`
  * Get current user data

*   **Logout:**
    *   `POST {{base_url}}/logout`
    *   Headers: `Authorization: Bearer {{merchant_token}}` (or `{{customer_token}}`)
    *   After logging out, try accessing a protected route with the *same* token. You should get a `401 Unauthorized` error.

### 3.2 Merchant: Product Management

**Headers:** `Authorization: Bearer {{merchant_token}}` for *all* merchant product requests.

*   **Create Product:**
    *   `POST {{base_url}}/merchant/products`
      *   **Headers:** `Content-Type: application/json`
    *   Body:
        ```json
        {
            "name": "My Product",
            "description": "Product description",
            "price": 29.99,
            "stock": 50
        }
        ```

*   **List Products:**
    *   `GET {{base_url}}/merchant/products`

*   **Show Product:**
    *   `GET {{base_url}}/merchant/products/{id}` (Replace `{id}` with the product ID)

*   **Update Product:**
    *   `PUT {{base_url}}/merchant/products/{id}`
      *   **Headers:** `Content-Type: application/json`
    *   Body: (Send the fields you want to update)
        ```json
        {
            "name": "Updated Name",
            "price": 39.99
        }
        ```

*   **Delete Product:**
    *   `DELETE {{base_url}}/merchant/products/{id}`

### 3.3 Customer: Viewing Products

*   **List All Products:**
    *   `GET {{base_url}}/products`  (No authentication needed)

### 3.4 Customer: Orders

**Headers:** `Authorization: Bearer {{customer_token}}` for *all* customer order requests.
**Headers:** `Content-Type: application/json` for create order requests.

*   **Create Order:**
    *   `POST {{base_url}}/customer/orders`
    *   Body:
        ```json
        {
            "products": [
                { "product_id": 1, "quantity": 1 },
                { "product_id": 2, "quantity": 2 }
            ]
        }
        ```
        (Use valid product IDs)

* **Get Order List:**
    * `GET {{base_url}}/customer/orders`

### 3.5  Merchant: Viewing Orders
*   **List Orders:**
    *   `GET {{base_url}}/merchant/orders`
        *   **Headers:** `Authorization: Bearer {{merchant_token}}`
    *   Get list orders which contains merchant's product
*  **Show Orders:**
    *   `GET {{base_url}}/merchant/orders/1` (replace 1 with valid order id)
    *    **Headers:** `Authorization: Bearer {{merchant_token}}`
    * Get order detail which contains merchant's product

### 3.6 Customer: Rewards

**Headers:** `Authorization: Bearer {{customer_token}}` for *all* customer reward requests.

*   **List Rewards:**
    *   `GET {{base_url}}/customer/rewards`

*   **Redeem Reward:**
    *   `POST {{base_url}}/customer/rewards/{id}/redeem` (Replace `{id}` with the reward ID)

## 4. Common Errors

*   **400 Bad Request:** Client-side error (e.g., not enough points to redeem).
*   **401 Unauthorized:** Missing or invalid token.
*   **403 Forbidden:**  User is authenticated, but not authorized (e.g., customer accessing merchant routes).
*   **404 Not Found:** Resource not found.
*   **422 Unprocessable Entity:** Validation errors (check the response body for details).
*   **500 Internal Server Error:** Server-side error.

This simplified guide provides a clear and concise way to start testing your Maju Mundur API. Remember to replace placeholders like `{{base_url}}`, `{{merchant_token}}`, `{{customer_token}}`, and `{id}` with actual values.  This is suitable for a quick start section in your README, and you can always expand it with more details as needed.
