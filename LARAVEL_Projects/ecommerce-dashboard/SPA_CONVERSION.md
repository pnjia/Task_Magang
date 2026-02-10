# Konversi ke SPA (Single Page Application)

Proyek ini telah dikonversi dari traditional server-rendered application menjadi SPA menggunakan **Inertia.js** dengan **React** sebagai frontend framework.

## 🚀 Teknologi yang Digunakan

- **Laravel 12** - Backend framework
- **Inertia.js v2.0** - SPA adapter yang menghubungkan Laravel dengan React
- **React 18** - Frontend JavaScript library
- **Vite** - Build tool dan dev server
- **Ziggy** - Laravel route helper untuk JavaScript
- **Tailwind CSS** - Utility-first CSS framework
- **Alpine.js** - Retained untuk simple interactivity

## 📦 Packages yang Diinstall

### PHP (Composer)
```bash
composer require inertiajs/inertia-laravel
composer require tightenco/ziggy
```

### JavaScript (NPM)
```bash
npm install @inertiajs/react react react-dom @vitejs/plugin-react ziggy-js
```

## 🔧 Perubahan Konfigurasi

### 1. **vite.config.js**
- Menambahkan plugin React
- Konfigurasi alias `@` untuk `/resources/js`
- Enable JSX di file .js

### 2. **resources/js/app.js**
- Menggunakan `createInertiaApp` dari `@inertiajs/react`
- Setup React root dengan `createRoot`
- Konfigurasi progress bar

### 3. **bootstrap/app.php**
- Register `HandleInertiaRequests` middleware

### 4. **resources/js/bootstrap.js**
- Menambahkan Ziggy route helper global

## 📁 Struktur File Baru

```
resources/js/
├── app.js                 # Entry point Inertia + React
├── bootstrap.js           # Axios & Ziggy setup
├── Layouts/
│   ├── AppLayout.jsx     # Layout untuk authenticated users
│   └── GuestLayout.jsx   # Layout untuk guest (login/register)
└── Pages/
    ├── Dashboard.jsx     # Halaman dashboard
    └── Auth/
        └── Login.jsx     # Halaman login

resources/views/
└── app.blade.php         # Root Inertia template dengan @inertia directive
```

## 🎯 Cara Kerja SPA

1. **No Page Reloads**: Navigasi antar halaman tidak reload browser, hanya update konten yang berubah
2. **Shared Data**: User authentication dan flash messages di-share otomatis ke semua page melalui `HandleInertiaRequests`
3. **Laravel Routes**: Tetap menggunakan routing Laravel, tidak perlu setup React Router
4. **Form Handling**: Menggunakan Inertia form helpers dengan CSRF token otomatis

## 💻 Development

### Jalankan Dev Server
```bash
npm run dev
```

### Build untuk Production
```bash
npm run build
```

### Watch Mode
```bash
npm run dev
```

## 🔄 Konversi View ke Inertia

### Sebelum (Blade):
```php
// Controller
return view('dashboard', compact('data'));
```

### Sesudah (Inertia):
```php
// Controller
use Inertia\Inertia;

return Inertia::render('Dashboard', [
    'data' => $data
]);
```

### Component React:
```jsx
// resources/js/Pages/Dashboard.jsx
import AppLayout from '@/Layouts/AppLayout';

export default function Dashboard({ data }) {
    return (
        <AppLayout title="Dashboard">
            <div>{/* Your content */}</div>
        </AppLayout>
    );
}
```

## 🔗 Navigasi di React

### Menggunakan Link Component:
```jsx
import { Link } from '@inertiajs/react';

<Link href="/products">Produk</Link>
```

### Menggunakan useForm Hook:
```jsx
import { useForm } from '@inertiajs/react';

const { data, setData, post, processing, errors } = useForm({
    name: '',
    email: '',
});

const submit = (e) => {
    e.preventDefault();
    post('/users');
};
```

## 📊 Props yang Di-Share Otomatis

Tersedia di semua page melalui `usePage().props`:

```jsx
import { usePage } from '@inertiajs/react';

const { auth, flash } = usePage().props;

// auth.user - Current authenticated user
// flash.success - Success message
// flash.error - Error message
```

## ✅ Status Konversi

### Sudah Dikonversi:
- ✅ Dashboard (owner view)
- ✅ Login page
- ✅ Layout components (AppLayout & GuestLayout)

### Perlu Dikonversi:
- ⏳ Products (index, create, edit)
- ⏳ Categories (index, create, edit)
- ⏳ Transactions (index, create, show, history)
- ⏳ Users (index, create, edit)
- ⏳ Profile page
- ⏳ Register page
- ⏳ Password reset pages

## 🎨 Alpine.js Compatibility

Alpine.js masih tersedia untuk simple interactivity seperti:
- Dropdown menus
- Sidebar toggle
- Modal dialogs

Gunakan `x-data`, `x-show`, `@click` seperti biasa di JSX:

```jsx
<div className="..." x-data="{ open: false }">
    <button onClick={() => {}} x-on:click="open = !open">Toggle</button>
</div>
```

## 🚀 Next Steps

1. **Konversi halaman satu per satu**: Mulai dari halaman yang paling sering diakses
2. **Test navigasi**: Pastikan tidak ada page reload saat berpindah halaman
3. **Update controllers**: Ubah `return view()` menjadi `return Inertia::render()`
4. **Test forms**: Pastikan form submission dan validation bekerja dengan baik

## 📚 Resources

- [Inertia.js Documentation](https://inertiajs.com/)
- [React Documentation](https://react.dev/)
- [Ziggy Documentation](https://github.com/tighten/ziggy)
- [Vite Documentation](https://vitejs.dev/)

---

**Happy coding! 🎉** Proyek Anda sekarang adalah Single Page Application yang modern dan responsif!
