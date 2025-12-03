# How to Create an Admin User

There are several ways to create an admin user in the Restaurant Management System. Choose the method that works best for you.

---

## 🚀 **Method 1: Using Seeder (Recommended)**

This is the easiest and most consistent method.

### Step 1: Make sure roles are seeded
```bash
php artisan db:seed --class=RolePermissionSeeder
```

### Step 2: Run the Admin User Seeder
```bash
php artisan db:seed --class=AdminUserSeeder
```

This will create an admin user with:
- **Email**: `admin@restaurant.com`
- **Password**: `password` (⚠️ **Change this immediately!**)
- **Role**: Admin (with all permissions)
- **is_admin**: true

---

## 💻 **Method 2: Using Tinker (Interactive)**

Open Laravel Tinker and create the admin manually:

```bash
php artisan tinker
```

Then run these commands:

```php
// First, get the admin role
$adminRole = App\Models\Role::where('slug', 'admin')->first();

// Create the admin user
$admin = App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@restaurant.com',
    'password' => Hash::make('your-secure-password'),
    'role_id' => $adminRole->id,
    'is_admin' => true,
]);

// Verify it was created
echo "Admin created: " . $admin->email;
```

---

## 🔧 **Method 3: Using Artisan Command (One-liner)**

Run this command in your terminal:

```bash
php artisan tinker --execute="
\$adminRole = App\Models\Role::where('slug', 'admin')->first();
\$admin = App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@restaurant.com',
    'password' => Hash::make('password'),
    'role_id' => \$adminRole->id,
    'is_admin' => true,
]);
echo 'Admin created: ' . \$admin->email;
"
```

---

## 📝 **Method 4: Update Existing User to Admin**

If you already have a user and want to make them admin:

```bash
php artisan tinker
```

```php
// Get the admin role
$adminRole = App\Models\Role::where('slug', 'admin')->first();

// Get your user
$user = App\Models\User::where('email', 'your-email@example.com')->first();

// Make them admin
$user->update([
    'role_id' => $adminRole->id,
    'is_admin' => true,
]);

echo "User updated to admin!";
```

---

## ✅ **Verify Admin Creation**

After creating the admin, verify it works:

1. **Login at**: `/admin/login`
2. **Use credentials**: 
   - Email: `admin@restaurant.com`
   - Password: `password` (or whatever you set)

3. **You should be able to access**:
   - `/admin/dashboard`
   - `/admin/roles`
   - `/admin/permissions`
   - All admin features

---

## 🔒 **Security Notes**

⚠️ **IMPORTANT**: 
- Always change the default password after first login!
- Use strong passwords in production
- Consider using environment variables for initial admin credentials

---

## 🎯 **Quick Start (Complete Setup)**

If you're setting up the system for the first time:

```bash
# 1. Run migrations
php artisan migrate

# 2. Seed roles and permissions
php artisan db:seed --class=RolePermissionSeeder

# 3. Create admin user
php artisan db:seed --class=AdminUserSeeder

# 4. Build frontend
npm run dev

# 5. Visit /admin/login and login with:
#    Email: admin@restaurant.com
#    Password: password
```

---

## 🆘 **Troubleshooting**

### "Admin role not found"
- Make sure you've run: `php artisan db:seed --class=RolePermissionSeeder`

### "User already exists"
- The seeder will update existing users automatically
- Or delete the user first: `User::where('email', 'admin@restaurant.com')->delete();`

### "Can't login"
- Verify the password hash is correct
- Check that `is_admin` is true AND `role_id` is set
- Make sure you're using `/admin/login` (not `/login`)

---

**That's it! You now have an admin user ready to use.** 🎉

