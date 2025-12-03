# Quick Start Guide - Restaurant Management System

## 🚀 Getting Started

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & npm
- Laravel Herd (or any Laravel development environment)

### Installation Steps

1. **Install Dependencies:**
```bash
composer install
npm install
```

2. **Environment Setup:**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Database Setup:**
```bash
php artisan migrate
php artisan db:seed --class=RolePermissionSeeder
```

4. **Build Frontend:**
```bash
npm run dev
# Or for production: npm run build
```

5. **Start Development Server:**
```bash
php artisan serve
# Frontend should be running via Vite automatically
```

---

## 👤 Initial Admin Setup

1. **Create Admin User** (if not exists):
```bash
php artisan tinker
>>> App\Models\User::create([
...     'name' => 'Admin',
...     'email' => 'admin@restaurant.com',
...     'password' => Hash::make('password'),
...     'is_admin' => true,
... ]);
```

2. **Assign Admin Role:**
```bash
php artisan tinker
>>> $adminRole = App\Models\Role::where('slug', 'admin')->first();
>>> $user = App\Models\User::where('email', 'admin@restaurant.com')->first();
>>> $user->update(['role_id' => $adminRole->id]);
```

---

## 📍 Key Routes

### Admin Routes (require authentication):
- `/admin/dashboard` - Main dashboard
- `/admin/orders` - Order management
- `/admin/kitchen` - Kitchen display
- `/admin/food` - Menu items
- `/admin/menu-categories` - Menu categories
- `/admin/tables` - Table management
- `/admin/roles` - Role management
- `/admin/permissions` - Permission management

### Public Routes:
- `/login` - Admin login
- `/` - Home page

---

## 🎯 Quick Usage Guide

### 1. Create Menu Categories
1. Go to `/admin/menu-categories`
2. Click "Create Category"
3. Add name, icon, description, and image
4. Set display order

### 2. Add Menu Items
1. Go to `/admin/food`
2. Click "Create Menu Item"
3. Fill in details:
   - Name, category, price
   - Add variants (sizes)
   - Add add-ons (extras)
   - Set availability times
   - Add tags and allergens
   - Upload image

### 3. Create Tables
1. Go to `/admin/tables`
2. Click "Create Table"
3. Set table number, capacity, location
4. Configure zone, shape, floor

### 4. Manage Orders
1. View orders at `/admin/orders`
2. Filter by status, date, or search
3. Use kitchen display at `/admin/kitchen` for real-time updates
4. Update order status as needed

### 5. Manage Roles & Permissions
1. Go to `/admin/roles` to create/edit roles
2. Assign permissions to roles
3. Go to `/admin/permissions` to manage permissions
4. Users inherit permissions from their role

---

## 🔐 Default Roles

1. **Admin** - Full access to everything
2. **Manager** - Can manage operations, view reports (no role management)
3. **Waiter** - Can take orders, manage tables, view reservations
4. **Chef** - Can view orders and update order status

---

## 🎨 UI Features

- **Glassomorphic Design** - Beautiful transparent cards with backdrop blur
- **Responsive** - Works on desktop, tablet, and mobile
- **Real-time Updates** - Kitchen display auto-refreshes
- **Dark Theme** - Easy on the eyes
- **Smooth Animations** - Polished user experience

---

## 📊 Dashboard Features

- Revenue statistics (today/week/month)
- Order statistics
- Active tables count
- Recent orders feed
- Revenue trend chart
- Order status distribution
- Popular menu items

---

## 🛠️ Troubleshooting

### Vite Build Errors:
```bash
npm run dev
# Make sure Vite server is running
```

### Permission Issues:
- Make sure you've run the RolePermissionSeeder
- Check that your user has a role assigned
- Verify middleware is working

### Database Errors:
```bash
php artisan migrate:fresh --seed
# ⚠️ This will delete all data!
```

---

## 📝 Notes

- All migrations have been cleaned up and merged
- Flash messages appear automatically
- Kitchen display auto-refreshes every 30 seconds
- Order status changes update in real-time

---

**Happy Managing! 🍽️**

