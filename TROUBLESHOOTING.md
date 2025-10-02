# TROUBLESHOOTING - CSS & Alpine.js Loading Issues

## ✅ Issue Fixed: CSS and Alpine.js Not Loading

### Problem:

CSS (TailwindCSS) dan Alpine.js tidak ter-load di browser, menampilkan plain HTML tanpa styling.

### Solution Steps Applied:

1. **Build Assets** ✅

```bash
npm run build
```

Output: Build successful, files created in `public/build/`

2. **Clear Laravel Cache** ✅

```bash
php artisan optimize:clear
```

3. **Verified Build Files** ✅
   Files exist in:

-   `public/build/manifest.json`
-   `public/build/assets/app-*.css`
-   `public/build/assets/app-*.js`

### To Complete the Fix:

**IMPORTANT: You need to restart the Laravel server!**

```bash
# Stop current server (Ctrl+C) then:
php artisan serve
```

### Alternative Solutions if Still Not Working:

1. **Development Mode with Hot Reload:**

```bash
# Terminal 1 - Laravel Server
php artisan serve

# Terminal 2 - Vite Dev Server (for hot reload)
npm run dev
```

2. **Force Rebuild:**

```bash
rm -rf public/build
npm run build
php artisan optimize:clear
```

3. **Check Browser Console:**

-   Open browser DevTools (F12)
-   Check Console tab for errors
-   Check Network tab to see if CSS/JS files are loading

4. **Verify @vite Directive:**
   All Blade templates should have:

```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

5. **Manual Test URL:**
   Try accessing directly:

-   `http://localhost:8000/build/manifest.json`
-   Should return JSON with asset mappings

### Current Status:

-   ✅ TailwindCSS installed and configured
-   ✅ Alpine.js installed and configured
-   ✅ Build files generated
-   ✅ Cache cleared
-   ⚠️ **Need to restart Laravel server**

### Testing After Fix:

1. Restart Laravel server: `php artisan serve`
2. Open browser: `http://localhost:8000`
3. Hard refresh: `Ctrl+Shift+R` (Windows) or `Cmd+Shift+R` (Mac)
4. Login page should show with proper styling:
    - Blue gradient background
    - Styled form inputs
    - Primary color buttons
    - Proper typography

### Note:

The build system is working correctly. The issue was that:

1. Assets weren't built initially
2. Laravel server needs restart after build
3. Browser might be caching old version
