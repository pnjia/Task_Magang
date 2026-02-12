# GitHub Copilot Instructions - Laravel E-Commerce Dashboard

## Project Overview

Multi-tenant e-commerce dashboard with Laravel 12 + Inertia.js + React. UUID-based tenant isolation, role-based access (owner/staff), and SPA architecture with server-side routing.

## Critical Architecture Patterns

### Multi-Tenant Model Pattern

ALL models handling tenant data MUST use both traits and include tenant_id in fillable:

```php
use HasFactory, HasUuids, BelongsToTenant;
protected $fillable = ['tenant_id', 'name', ...]; // tenant_id first
```

### SPA Response Pattern (CRITICAL)

Controllers MUST return `Inertia::render()`, NEVER `view()` or JSON:

```php
return Inertia::render('Products/Index', ['products' => $products]);
```

### React Import Pattern (SWC Plugin)

ALL .jsx files MUST include explicit React import due to `@vitejs/plugin-react-swc`:

```jsx
import React from "react";
import { useState } from "react"; // hooks imported separately
```

**Why**: Using `@vitejs/plugin-react-swc` (not standard `@vitejs/plugin-react`) to avoid "can't detect preamble" errors. This requires explicit React imports.

## Development Workflow

### Start Development Server

```bash
composer dev  # Runs: Laravel serve + Queue + Logs + Vite (concurrently)
```

### Build for Production

```bash
npm run build  # MUST run after JSX changes before testing
rm -rf node_modules/.vite  # Clear cache if white screen issues
```

### Common Issues & Solutions

**White Screen / "can't detect preamble" Error:**

1. Verify ALL .jsx files have `import React from 'react';`
2. Check vite.config.js uses `@vitejs/plugin-react-swc`
3. Clear cache: `rm -rf node_modules/.vite && npm run build`
4. Hard refresh browser: Ctrl+Shift+R (clear browser cache)

**Status Update Not Refreshing UI:**
Use `router.reload({ only: ['transactions'] })` in onSuccess callback, NOT `preserveState: true` alone.

## Key Conventions

### Database Migrations

```php
$table->uuid('id')->primary();  // Always UUID, never auto-increment
$table->foreignUuid('tenant_id')->constrained()->onDelete('cascade');
```

### Frontend Navigation

```jsx
import { Link, router } from '@inertiajs/react';
<Link href="/products">Products</Link>  // NOT <a> tags
router.put('/transactions/{id}/status', {...})  // Form submissions
```

### Transaction Status Workflow

- **Active statuses**: unpaid, paid, processing, shipped (shown in "Pesanan Masuk")
- **Archive statuses**: completed, cancelled (moved to "Riwayat Transaksi")
- Index controller filters: `whereIn('status', ['unpaid', 'paid', 'processing', 'shipped'])`
- History controller filters: `whereIn('status', ['completed', 'cancelled'])`

### Role-Based Access

```php
Route::middleware(['role:owner'])->group(function() { /* owner only */ });
```

Frontend check: `usePage().props.auth.user.role === 'owner'`

## File Organization

### Backend

- `app/Traits/BelongsToTenant.php` - Auto tenant_id scoping
- `app/Traits/HasUuid.php` - UUID primary keys
- `app/Services/TenantContext.php` - Tenant context management

### Frontend

- `resources/js/Pages/` - Page components (route-based)
- `resources/js/Layouts/` - Layout wrappers
- `resources/js/app.jsx` - SPA entry with NProgress + Alpine.js

### Styling

Tailwind CSS only - NO custom CSS modules. Use utility classes with responsive modifiers:

```jsx
<div className="px-6 py-4 whitespace-nowrap text-sm font-medium">
```

## Testing Commands

```bash
composer test  # Clears config, runs PHPUnit
php artisan test --filter ProductTest
./vendor/bin/pint  # Format PHP (PSR-12)
```

## Debugging Tips

- **Backend**: Check `storage/logs/laravel.log`, use `php artisan tinker`
- **Routes**: `php artisan route:list | grep transactions`
- **Frontend**: Browser DevTools Console, check Network tab for Inertia requests
- **Tenant isolation**: Verify `tenant_id` in queries with `DB::enableQueryLog()`

## Environment

- PHP 8.2+, Laravel 12.49, Node.js
- Vite 5.4 with SWC plugin (faster than Babel)
- React 18 with Inertia.js v2
- Tailwind CSS 3.x

## Important Files to Check Before Editing

- `vite.config.js` - React plugin config (SWC-based)
- `app/Http/Middleware/HandleInertiaRequests.php` - Shared Inertia props
- `routes/web.php` - All routes with middleware groups
- `app/Http/Controllers/TransactionController.php` - Complex status update logic

When in doubt about multi-tenant patterns, refer to `app/Models/Product.php` and `app/Traits/BelongsToTenant.php` as canonical examples.
