# LOGIN FIX - Troubleshooting & Solutions

## ✅ Perbaikan Yang Sudah Dilakukan:

### 1. LoginController - SIMPLIFIED ✅

```php
// Simplified login logic tanpa activity logging yang bermasalah
public function store(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();

        $user = Auth::user();

        // Check if user is admin
        if ($user->role === 'super_admin') {
            return redirect()->intended('/admin/dashboard');
        }

        return redirect()->intended('/dashboard');
    }

    throw ValidationException::withMessages([
        'email' => 'Email atau password salah.',
    ]);
}
```

### 2. User Model - UPDATED ✅

-   Added missing fields in `$fillable`: `phone`, `profile_photo_path`, `banned_at`
-   Password casting as 'hashed'
-   isAdmin() method checks for 'super_admin' role

## 🔧 LANGKAH PERBAIKAN LOGIN:

### Step 1: Reset Database & Seed Fresh Data

```bash
cd mance
php artisan migrate:fresh --seed
```

### Step 2: Verifikasi User di Database

```bash
php artisan tinker
```

Kemudian jalankan:

```php
// Check admin user
App\Models\User::where('email', 'admin@mance.go.id')->first();

// Check regular user
App\Models\User::where('email', 'budi.santoso@email.com')->first();

// Test password hash
Hash::check('password123', App\Models\User::where('email', 'admin@mance.go.id')->first()->password);
// Should return true

exit
```

### Step 3: Clear All Cache

```bash
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Step 4: Restart Server

```bash
# Stop server (Ctrl+C) then:
php artisan serve
```

### Step 5: Test Login

1. Go to: `http://localhost:8000/login`
2. Try these credentials:

**Admin Account:**

```
Email: admin@mance.go.id
Password: password123
```

**User Account:**

```
Email: budi.santoso@email.com
Password: password
```

## 🐛 DEBUGGING CHECKLIST:

### If Login Still Fails:

#### 1. Check Database Connection

```bash
php artisan tinker
DB::connection()->getPdo();
// Should not throw error
```

#### 2. Manual Password Reset

```bash
php artisan tinker
```

```php
$user = App\Models\User::where('email', 'admin@mance.go.id')->first();
$user->password = Hash::make('password123');
$user->save();
exit
```

#### 3. Check Session Configuration

In `.env` file, make sure:

```env
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

#### 4. Check Permissions

```bash
# Windows (Run as Administrator)
# No action needed

# Linux/Mac
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

#### 5. Enable Debug Mode

In `.env`:

```env
APP_DEBUG=true
```

This will show detailed error messages.

## 📝 TEST SCENARIOS:

### Scenario 1: Correct Login

-   Email: `admin@mance.go.id`
-   Password: `password123`
-   Expected: Redirect to `/admin/dashboard`

### Scenario 2: Wrong Password

-   Email: `admin@mance.go.id`
-   Password: `wrongpassword`
-   Expected: Error message "Email atau password salah."

### Scenario 3: Non-existent Email

-   Email: `notexist@email.com`
-   Password: `password`
-   Expected: Error message "Email atau password salah."

## 🚀 QUICK FIX SCRIPT:

Run all fixes in one command:

```bash
cd mance
php artisan migrate:fresh --seed
php artisan optimize:clear
php artisan serve
```

## ✅ VERIFICATION:

After applying fixes, you should be able to:

1. ✅ See login form at `/login`
2. ✅ Login with seeded credentials
3. ✅ Get redirected to dashboard after successful login
4. ✅ See error message for wrong credentials
5. ✅ Logout functionality works

## 🆘 LAST RESORT:

If still not working, create a test user manually:

```bash
php artisan tinker
```

```php
App\Models\User::create([
    'name' => 'Test Admin',
    'email' => 'test@admin.com',
    'password' => Hash::make('test123'),
    'role' => 'super_admin',
    'category' => 'umum'
]);
exit
```

Then login with:

-   Email: `test@admin.com`
-   Password: `test123`
