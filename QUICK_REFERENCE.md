# Quick Reference Guide - Pamulihan App V2

## Login & Access

**Admin Panel URL**: http://localhost:8000/admin

**Super Admin Credentials**:
- Email: `admin@pamulihan.com`
- Password: `password`

**Test User Credentials**:
- Email: `user@test.com`
- Password: `password`

## Main Features at a Glance

### User Management
Navigate to **User Management → Users**

**Actions available per user**:
1. View (eye icon)
2. Edit (pencil icon)
3. Add Balance (green plus circle)
4. Deduct Balance (red minus circle)
5. Transfer Balance (yellow arrow circle)
6. Send WhatsApp (blue chat bubble)
7. Delete (trash icon)

### Create New User
Click **"New User"** button → Fill form:
- Name (required)
- Email (required, unique)
- Phone (optional, for WhatsApp)
- Password (required on create)
- Avatar (optional, image)
- Documents (optional, multiple files)
- Roles (select from checkboxes)

### Wallet Operations

**Add Balance**:
1. Click Actions menu on user row
2. Select "Add Balance"
3. Enter amount (min 1,000 IDR)
4. Add description (optional)
5. Confirm

**Deduct Balance**:
1. Click Actions menu on user row
2. Select "Deduct Balance"
3. Enter amount
4. Add description (optional)
5. System checks balance availability
6. Confirm

**Transfer Balance**:
1. Click Actions menu on user row
2. Select "Transfer Balance"
3. Choose recipient from dropdown
4. Enter amount
5. Add description (optional)
6. System checks balance availability
7. Confirm

### View Transaction History
1. Edit any user
2. Scroll to bottom
3. Click "Wallet Transactions" tab
4. View all deposits/withdrawals
5. Click "Details" to see full transaction info

### Send WhatsApp Message
1. Ensure user has phone number
2. Click Actions menu
3. Select "Send WhatsApp"
4. Review/edit phone number (auto-filled)
5. Type message
6. Send

## File Uploads

### Avatar
- Single file only
- Max size: 2MB
- Formats: jpg, png, gif
- Circular cropper available
- Displayed in user table

### Documents
- Multiple files allowed
- Max 10 files per user
- Max size per file: 5MB
- All common formats supported
- Drag to reorder

## Roles & Permissions

### Built-in Roles

**Super Admin**:
- Full system access
- All permissions
- Can manage roles

**Admin**:
- User management
- Wallet operations
- Send WhatsApp
- Cannot manage roles

**User**:
- View only
- Cannot edit anything

### Manage Roles
Navigate to **User Management → Shield → Roles**
- Create new roles
- Edit permissions per role
- Assign to users

## WhatsApp Setup

### Prerequisites
1. WhatsApp microservice running on port 8081
2. API key configured in `.env`

### Configuration
Edit `.env` file:
```env
WHATSAPP_API_URL=http://localhost:8081/api/send
WHATSAPP_API_KEY=your_actual_api_key_here
```

### Phone Number Format
Accepted formats:
- `6283821348593` (international)
- `083821348593` (local, will convert to 62)
- `+62838213485` (with plus, will clean)

## Database Management

### Reset Everything
```bash
php artisan migrate:fresh --seed
```
This will:
- Drop all tables
- Run all migrations
- Create roles and permissions
- Create 2 default users
- Add initial wallet balances

### Just Reseed Users
```bash
php artisan db:seed --class=RoleAndPermissionSeeder
```

### Manual Database Access
```bash
mysql -u root -pJarjit251089 pamulihan_app
```

## Troubleshooting

### Can't Login?
1. Verify database connection
2. Check if users exist: `php artisan tinker` → `User::count()`
3. Reset password: Run seeder again

### WhatsApp Not Working?
1. Check microservice is running: `curl http://localhost:8081`
2. Verify API key in `.env`
3. Check logs: `tail -f storage/logs/laravel.log`
4. Test phone format: Use 6283821348593

### Balance Not Updating?
1. Check transactions table exists
2. Clear cache: `php artisan cache:clear`
3. Verify wallet created: User → Transactions tab

### File Upload Fails?
1. Link storage: `php artisan storage:link`
2. Check permissions: `chmod -R 775 storage`
3. Verify file size is within limits

### Permission Denied?
1. Login as super admin
2. Check user has correct role
3. Reset permissions: `php artisan permission:cache-reset`

## Key Artisan Commands

```bash
# Development
php artisan serve                    # Start server
php artisan tinker                   # Interactive shell

# Database
php artisan migrate                  # Run migrations
php artisan migrate:fresh --seed     # Fresh install
php artisan db:seed                  # Run seeders only

# Cache
php artisan cache:clear              # Clear app cache
php artisan config:clear             # Clear config cache
php artisan route:clear              # Clear route cache
php artisan view:clear               # Clear view cache

# Permissions
php artisan permission:cache-reset   # Clear permission cache
php artisan shield:generate --all    # Generate Shield resources

# Storage
php artisan storage:link             # Link storage folder
```

## Testing Features

### Test Wallet System
1. Login as admin@pamulihan.com
2. Create new user or use existing
3. Add 100,000 IDR balance
4. Deduct 50,000 IDR
5. Create another user
6. Transfer 25,000 between them
7. View transaction history

### Test WhatsApp (Requires Microservice)
1. Ensure microservice is running
2. Edit user, add phone: 6283821348593
3. Click "Send WhatsApp"
4. Type: "Test message"
5. Send and check logs

### Test Media Upload
1. Create/edit user
2. Upload avatar (test with large file to see limit)
3. Upload multiple documents
4. Try reordering documents
5. View in table

### Test Permissions
1. Create new user
2. Assign "user" role
3. Logout
4. Login as that user
5. Try to edit something (should fail)

## API Examples

### WhatsApp API Direct Call
```bash
curl -X POST http://localhost:8081/api/send \
  -H "x-api-key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "to": "6283821348593",
    "message": "Hello from API"
  }'
```

## File Locations

### Important Files
```
app/Models/User.php                          # User model
app/Services/WhatsAppService.php             # WhatsApp service
app/Filament/Resources/UserResource.php      # Main resource
database/seeders/RoleAndPermissionSeeder.php # Seeder
config/services.php                          # WhatsApp config
.env                                         # Environment vars
```

### Views & Templates
```
resources/views/filament/resources/
  user-resource/modals/
    transaction-details.blade.php            # Transaction modal
```

### Storage
```
storage/app/public/              # Uploaded files
storage/logs/laravel.log         # Application logs
```

## Common User Scenarios

### Scenario 1: Add New Staff Member
1. Navigate to Users
2. Click "New User"
3. Fill in name, email, phone
4. Upload avatar
5. Set password
6. Assign "admin" role
7. Save
8. Add initial balance if needed

### Scenario 2: Customer Top-Up
1. Find customer in Users list
2. Click Actions → Add Balance
3. Enter amount
4. Description: "Manual top-up via admin"
5. Confirm
6. Customer can see new balance

### Scenario 3: Refund Customer
1. Find customer
2. Click Actions → Add Balance
3. Enter refund amount
4. Description: "Refund for order #123"
5. Confirm

### Scenario 4: Send Bulk WhatsApp
Currently: Manual one by one
Future: Can add bulk action
For now: Use WhatsAppService bulk method programmatically

### Scenario 5: Monthly Report
1. Open each user
2. Check Transactions tab
3. Filter by date
4. Export feature can be added

## Production Deployment

### Pre-Deploy Checklist
- [ ] Update `.env` with production values
- [ ] Change APP_ENV=production
- [ ] Set APP_DEBUG=false
- [ ] Update database credentials
- [ ] Add real WhatsApp API key
- [ ] Change all default passwords
- [ ] Test all features
- [ ] Set up backups
- [ ] Configure queue workers
- [ ] Enable HTTPS
- [ ] Set up monitoring

### Deploy Commands
```bash
# On server
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan db:seed --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
chmod -R 775 storage bootstrap/cache
```

## Support & Resources

### Documentation Links
- Laravel: https://laravel.com/docs/12.x
- Filament: https://filamentphp.com/docs/3.x
- FilamentShield: https://github.com/bezhanSalleh/filament-shield
- Laravel Wallet: https://bavix.github.io/laravel-wallet/
- Spatie Media Library: https://spatie.be/docs/laravel-medialibrary

### Project Info
- **Location**: /Users/macmini/SantosWork/Apps/PamulihanAppV2
- **Database**: pamulihan_app
- **Admin Path**: /admin
- **Laravel Version**: 12.x
- **Filament Version**: 3.3.x
- **PHP Version**: 8.2+

---

**Last Updated**: 2025-11-18
**Status**: Development Ready
