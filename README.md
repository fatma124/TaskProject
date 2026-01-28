# Order & Payment Management API

## Setup
- composer install
- php artisan migrate
- php artisan jwt:secret
- php artisan serve

## Authentication
- JWT based authentication
- All protected endpoints require Bearer Token

## Orders
- CRUD operations
- Orders have statuses: pending, confirmed, cancelled
- Orders cannot be deleted if payments exist

## Payments
- Payments processed only for confirmed orders
- Supports multiple gateways

## Payment Gateway Extensibility
- Uses Strategy Pattern
- Each gateway implements PaymentGatewayInterface
- To add new gateway:
  1. Create new class in app/Payments
  2. Implement pay() method
  3. Register in PaymentGatewayFactory
  4. Add env config

## Testing
- Feature & unit tests included
