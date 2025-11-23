# Pamulihan App V2 - Admin System Documentation

## Overview

This is a comprehensive admin system built with Laravel 12 and Filament 3, featuring user management, role-based permissions, wallet system, media library integration, and WhatsApp messaging capabilities.

## Tech Stack

- **Framework**: Laravel 12
- **Admin Panel**: Filament 3.3
- **Role & Permissions**: FilamentShield (BezhanSalleh/FilamentShield)
- **Wallet System**: bavix/laravel-wallet
- **Media Library**: Filament Spatie Media Library Plugin
- **Database**: MySQL

## Features

### 1. User Management
- Complete CRUD operations for users
- Avatar upload with image editor and circle cropper
- Multiple document uploads per user
- Role assignment with checkbox list
- Searchable and sortable user table
- User count badge on navigation

### 2. Role & Permission Management
- Pre-configured roles: super_admin, admin, user
- Granular permissions for different operations
- FilamentShield integration for UI management
- Role-based access control throughout the system

### 3. Wallet System
- Individual wallet for each user
- Balance display in user table (IDR currency)
- **Add Balance** action - deposit money to user wallet
- **Deduct Balance** action - withdraw money from user wallet with balance validation
- **Transfer Balance** action - transfer money between users
- Complete transaction history via RelationManager
- Transaction details modal with metadata support

### 4. WhatsApp Integration
- Integration with external WhatsApp microservice
- **Send WhatsApp** action on each user
- Phone number validation (Indonesian format)
- Pre-filled phone number from user record
- Custom message composer
- Success/error notifications

### 5. Media Management
- Avatar collection (single file, circular)
- Documents collection (multiple files, up to 10)
- File size limits and validation
- Image editor for avatars
- Default avatar using UI Avatars API

## Installation & Setup

### Prerequisites
- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js & NPM (for asset compilation)

### Database Setup

The database has already been created and migrated. If you need to reset:

```bash
# Drop all tables and re-migrate
php artisan migrate:fresh

# Run seeders to create roles, permissions, and users
php artisan db:seed
```

### Environment Configuration

The `.env` file is configured with:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pamulihan_app
DB_USERNAME=root
DB_PASSWORD=Jarjit251089

WHATSAPP_API_URL=http://localhost:8081/api/send
WHATSAPP_API_KEY=your_api_key_here
```

**Important**: Update `WHATSAPP_API_KEY` with your actual WhatsApp microservice API key.

### Running the Application

```bash
# Start the development server
php artisan serve

# Access the admin panel at:
# http://localhost:8000/admin
```

## Default Credentials

Two test users are created automatically:

### Super Admin
- Email: `admin@pamulihan.com`
- Password: `password`
- Initial Balance: IDR 1,000,000

### Test User
- Email: `user@test.com`
- Password: `password`
- Initial Balance: IDR 500,000

## File Structure

```
app/
├── Filament/
│   ├── Resources/
│   │   ├── UserResource.php
│   │   └── UserResource/
│   │       ├── Pages/
│   │       │   ├── ListUsers.php
│   │       │   ├── CreateUser.php
│   │       │   └── EditUser.php
│   │       └── RelationManagers/
│   │           └── TransactionsRelationManager.php
│   └── Providers/
│       └── Filament/
│           └── AdminPanelProvider.php
├── Models/
│   └── User.php (with HasWallet, InteractsWithMedia, HasRoles traits)
├── Services/
│   └── WhatsAppService.php
database/
├── migrations/
│   ├── 2025_11_18_080756_create_permission_tables.php
│   ├── 2025_11_18_081558_add_phone_to_users_table.php
│   └── [wallet migrations]
└── seeders/
    ├── DatabaseSeeder.php
    └── RoleAndPermissionSeeder.php
config/
└── services.php (WhatsApp configuration)
resources/
└── views/
    └── filament/
        └── resources/
            └── user-resource/
                └── modals/
                    └── transaction-details.blade.php
```

## API Integration

### WhatsApp Microservice

The system integrates with an external WhatsApp microservice:

**Endpoint**: `http://localhost:8081/api/send`

**Headers**:
```
x-api-key: {API_KEY}
Content-Type: application/json
```

**Request Body**:
```json
{
  "to": "6283821348593",
  "message": "Hello from WhatsApp bot!"
}
```

The `WhatsAppService` class handles:
- Phone number validation and formatting
- Indonesian number format conversion (0xxx to 62xxx)
- Error handling and logging
- Bulk message sending support

## User Model Capabilities

The User model implements several interfaces and traits:

```php
class User extends Authenticatable implements FilamentUser, Wallet, HasMedia
{
    use HasFactory, Notifiable, HasWallet, InteractsWithMedia, HasRoles;
}
```

### Wallet Operations

```php
// Deposit money
$user->deposit(1000000, ['description' => 'Top up']);

// Withdraw money
$user->withdraw(50000, ['description' => 'Purchase']);

// Transfer to another user
$user->transfer($recipient, 100000, ['description' => 'Payment']);

// Check balance
$balance = $user->balance;

// Get transactions
$transactions = $user->transactions;
```

### Media Operations

```php
// Add avatar
$user->addMedia($file)->toMediaCollection('avatar');

// Add document
$user->addMedia($file)->toMediaCollection('documents');

// Get avatar URL
$avatarUrl = $user->getFirstMediaUrl('avatar');

// Get all documents
$documents = $user->getMedia('documents');
```

### Role Operations

```php
// Assign role
$user->assignRole('admin');

// Check role
$user->hasRole('super_admin');

// Check permission
$user->can('manage_wallet');
```

## Permissions List

### User Management
- `view_user` - View single user
- `view_any_user` - View user list
- `create_user` - Create new user
- `update_user` - Update user details
- `delete_user` - Delete user
- `delete_any_user` - Bulk delete users

### Wallet Management
- `manage_wallet` - Access to wallet features
- `add_balance` - Add balance to user wallet
- `deduct_balance` - Deduct balance from user wallet
- `transfer_balance` - Transfer balance between users

### WhatsApp
- `send_whatsapp` - Send WhatsApp messages to users

### Role Management
- `view_role` - View single role
- `view_any_role` - View role list
- `create_role` - Create new role
- `update_role` - Update role details
- `delete_role` - Delete role
- `delete_any_role` - Bulk delete roles

## Customization

### Adding New Permissions

1. Add permission to `RoleAndPermissionSeeder.php`
2. Run seeder: `php artisan db:seed --class=RoleAndPermissionSeeder`

### Modifying Wallet Currency

Edit `UserResource.php`:
```php
Tables\Columns\TextColumn::make('balance')
    ->money('USD') // Change from IDR
```

### Customizing WhatsApp Message Templates

Create reusable templates in `WhatsAppService.php`:
```php
public function sendWelcomeMessage(string $phoneNumber, string $name): array
{
    $message = "Hello {$name}! Welcome to Pamulihan App.";
    return $this->send($phoneNumber, $message);
}
```

## Troubleshooting

### WhatsApp Not Sending
1. Verify WhatsApp microservice is running on port 8081
2. Check API key in `.env` file
3. Verify phone number format (must start with 62)
4. Check Laravel logs at `storage/logs/laravel.log`

### Wallet Balance Not Updating
1. Check if wallet was created: `$user->wallet`
2. Verify transactions table exists
3. Clear cache: `php artisan cache:clear`

### Permission Denied Errors
1. Verify user has correct role assigned
2. Check role has required permissions
3. Clear permission cache: `php artisan permission:cache-reset`

### File Upload Issues
1. Ensure storage is linked: `php artisan storage:link`
2. Check file permissions on `storage/` directory
3. Verify `public` disk is configured in `config/filesystems.php`

## Security Notes

1. Change default passwords immediately in production
2. Update `WHATSAPP_API_KEY` with secure key
3. Configure proper file upload validation
4. Enable HTTPS in production
5. Review and customize permissions based on your needs

## Future Enhancements

Potential features to add:
- Activity logs for user actions
- Email notifications for wallet transactions
- Export user data to CSV/Excel
- Advanced filtering and search
- Dashboard widgets for statistics
- Multi-currency wallet support
- SMS integration alongside WhatsApp
- Two-factor authentication

## Support

For issues or questions:
1. Check Laravel documentation: https://laravel.com/docs
2. Check Filament documentation: https://filamentphp.com/docs
3. Review package documentation for specific features

## License

This project is proprietary software for Pamulihan App.
