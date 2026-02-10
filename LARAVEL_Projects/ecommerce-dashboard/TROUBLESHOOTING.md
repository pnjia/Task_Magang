# Troubleshooting Guide - SPA Conversion

## ✅ Masalah yang Telah Diperbaiki

### 1. **Error: "@vitejs/plugin-react can't detect preamble"**

**Error yang Muncul di Console:**
```
Uncaught (in promise) Error: @vitejs/plugin-react can't detect preamble. Something is wrong.
Uncaught TypeError: v[y] is not a function
```

**Penyebab:**
- File JSX tidak mengimport React di awal file
- Vite Fast Refresh memerlukan React import untuk mendeteksi komponen React
- Tanpa import React, Vite tidak bisa mengenali file sebagai React component

**Solusi:**
Tambahkan `import React` di **SEMUA file .jsx**:

```jsx
// ✅ BENAR - Semua file JSX harus import React
import React from 'react';
import { Link } from '@inertiajs/react';

export default function MyComponent() {
    return <div>Hello</div>;
}
```

```jsx
// ✅ BENAR - Untuk file dengan hooks
import React, { useState, useEffect } from 'react';
import { usePage } from '@inertiajs/react';

export default function MyComponent() {
    const [value, setValue] = useState('');
    return <div>{value}</div>;
}
```

```jsx
// ❌ SALAH - Missing React import
import { Link } from '@inertiajs/react';

export default function MyComponent() {
    return <div>Hello</div>;
}
```

**File yang harus diupdate:**
- ✅ `resources/js/Layouts/AppLayout.jsx`
- ✅ `resources/js/Layouts/GuestLayout.jsx`
- ✅ `resources/js/Pages/Dashboard.jsx`
- ✅ `resources/js/Pages/Auth/Login.jsx`
- ✅ `resources/js/Pages/Products/Index.jsx`
- ✅ `resources/js/Pages/Products/Create.jsx`
- ✅ `resources/js/Pages/Products/Edit.jsx`
- ✅ `resources/js/Pages/Categories/Index.jsx`
- ✅ `resources/js/Pages/Categories/Create.jsx`
- ✅ `resources/js/Pages/Categories/Edit.jsx`

### 2. **Halaman Dashboard Putih/Blank + Auth User Null**

**Penyebab:**
- Component mencoba akses `auth.user.role` sebelum data loaded
- Redirect dilakukan langsung di render (bukan di useEffect)
- Multiple `usePage()` calls menyebabkan inconsistency

**Solusi di `AppLayout.jsx`:**

```jsx
import React, { useState, useEffect } from 'react';
import { Link, usePage } from '@inertiajs/react';

export default function AppLayout({ title, children }) {
    // ✅ Panggil usePage() sekali saja
    const { auth, component } = usePage().props;
    const [sidebarOpen, setSidebarOpen] = useState(false);
    const [dropdownOpen, setDropdownOpen] = useState(false);

    // ✅ Helper menggunakan variable component, bukan usePage() lagi
    const isActive = (page) => {
        return component.startsWith(page);
    };

    // ✅ Redirect di useEffect, bukan langsung di render
    useEffect(() => {
        if (!auth || !auth.user) {
            window.location.href = '/login';
        }
    }, [auth]);

    // ✅ Early return jika user belum ada
    if (!auth || !auth.user) {
        return null;
    }

    return (
        <div>
            {/* ✅ Gunakan optional chaining untuk semua akses auth.user */}
            <div>{auth?.user?.name || 'User'}</div>
            
            {/* ✅ Check role dengan optional chaining */}
            {auth?.user?.role === 'owner' && (
                <div>Owner only content</div>
            )}
        </div>
    );
}
```

### 3. **Halaman Dashboard Putih (Masalah Sebelumnya)**

**Penyebab:**
- Missing CSS import di vite directive
- Penggunaan `route().current()` helper sebelum Ziggy fully loaded
- Component navigation menggunakan Laravel route helper yang tidak tersedia di client-side

**Solusi:**
1. **Update `resources/views/app.blade.php`**:
   ```blade
   @vite(['resources/css/app.css', 'resources/js/app.js'])
   ```
   (Bukan: `@vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.jsx"])`)

2. **Update `AppLayout.jsx`** - Gunakan `usePage().component` untuk check active page:
   ```jsx
   const isActive = (page) => {
       return usePage().component.startsWith(page);
   };
   
   // Kemudian gunakan:
   isActive('Dashboard')
   isActive('Products/')
   isActive('Categories/')
   ```

3. **Jangan gunakan `route().current()`** di React components sebelum Ziggy fully initialized

## 🔧 Langkah-Langkah Setelah Perbaikan

### 1. Rebuild Assets
```bash
npm run build
```

### 2. Clear Cache Laravel
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### 3. Jalankan Development Server
```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

### 4. Test di Browser
- Buka `http://localhost:8000/dashboard`
- Pastikan tidak ada error di console (F12)
- Test navigasi ke Products, Categories
- Pastikan tidak ada page reload saat navigasi

## 🐛 Debugging Tips

### Check Console Errors
1. Buka Developer Tools (F12)
2. Tab Console - lihat error JavaScript
3. Tab Network - pastikan assets loaded (app.js, app.css)

### Check Inertia Response
Di Network tab, klik request ke `/dashboard`:
- Headers harus ada `X-Inertia: true`
- Response harus JSON dengan structure:
  ```json
  {
    "component": "Dashboard",
    "props": { ...data },
    "url": "/dashboard",
    "version": "..."
  }
  ```

### Common Errors & Solutions

#### Error: `route is not defined`
**Solusi:** Gunakan `usePage().component` untuk navigation checking, bukan `route().current()`

#### Error: `Cannot read property 'user' of undefined`
**Solusi:** Check `HandleInertiaRequests.php` - pastikan auth data di-share:
```php
'auth' => [
    'user' => $request->user(),
],
```

#### Halaman Blank Tanpa Error
**Check:**
1. Apakah `@vite` directive include CSS?
2. Apakah component exists di `resources/js/Pages/`?
3. Apakah controller return `Inertia::render()` bukan `view()`?

#### CSS Not Loading
**Solusi:**
```blade
<!-- app.blade.php -->
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

## ✨ Verifikasi SPA Berfungsi

### Test Checklist:
- [ ] Dashboard loads tanpa error
- [ ] Sidebar navigation visible
- [ ] Click "Produk" - page berubah tanpa reload (check icon spinner di tab)
- [ ] Click "Kategori" - page berubah tanpa reload
- [ ] Back button browser works
- [ ] Active menu item highlighted correctly
- [ ] Data tampil dengan benar

### Performance Check:
- Initial page load: ~500ms - 2s (tergantung koneksi)
- Subsequent navigation: ~100-300ms (no full reload!)
- Network tab: Hanya fetch JSON, bukan full HTML

## 🚀 Next Steps Jika Masih Error

1. **Check Laravel Logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Check Vite Errors:**
   ```bash
   npm run dev
   # Watch for compilation errors
   ```

3. **Verify File Structure:**
   ```
   resources/js/
   ├── app.js
   ├── bootstrap.js
   ├── Layouts/
   │   ├── AppLayout.jsx
   │   └── GuestLayout.jsx
   └── Pages/
       ├── Dashboard.jsx
       ├── Products/
       ├── Categories/
       └── Auth/
   ```

4. **Check Controller:**
   ```php
   // BENAR ✅
   use Inertia\Inertia;
   return Inertia::render('Dashboard', $data);
   
   // SALAH ❌
   return view('dashboard', $data);
   ```

## 📝 Status Konversi

### ✅ Sudah Dikonversi (SPA Ready):
- Dashboard
- Products (Index, Create, Edit)
- Categories (Index, Create, Edit)
- Login
- AppLayout & GuestLayout

### ⏳ Belum Dikonversi (Masih Blade):
- Transactions
- Users
- Profile
- Register & Password Reset

**Note:** Halaman yang belum dikonversi masih akan full page reload. Ini normal sampai semua halaman dikonversi.

## 🎯 Performance Optimization (Optional)

### Enable SSR (Server-Side Rendering)
Untuk initial load lebih cepat:
```bash
npm install @inertiajs/inertia-react-ssr
php artisan inertia:start-ssr
```

### Code Splitting
Sudah otomatis dengan Vite - setiap page component di-split ke file terpisah.

### Cache Busting
Sudah otomatis - Vite generate hash di filename assets.

---

**Updated:** February 10, 2026
**Status:** Dashboard & navigation FIXED ✅
