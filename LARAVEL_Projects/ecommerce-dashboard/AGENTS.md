# AGENTS.md - Laravel E-Commerce Dashboard

This file contains essential information for agentic coding agents working in this Laravel multi-tenant e-commerce dashboard repository.

## Quick Start Commands

### Development Setup
```bash
composer setup          # Complete project setup (install, migrate, build)
composer dev            # Start development server with hot reload (all services)
npm run dev            # Vite frontend dev server only
php artisan serve       # Laravel backend server only
```

### Testing
```bash
composer test          # Run all tests (clears config first)
php artisan test       # Run PHPUnit tests
php artisan test --filter TestName          # Run specific test class
php artisan test --filter test_method_name  # Run specific test method
./vendor/bin/phpunit tests/Feature          # Run feature tests only
./vendor/bin/phpunit tests/Unit            # Run unit tests only
php artisan test --coverage                 # Run with coverage report
```

### Code Quality
```bash
./vendor/bin/pint     # Format PHP code (Laravel Pint - PSR-12)
npm run build         # Production build
```

### Database
```bash
php artisan migrate                 # Run migrations
php artisan migrate:fresh --seed    # Fresh database with seeders
php artisan db:seed                 # Run seeders
php artisan tinker                  # Laravel REPL
```

## Architecture Overview

This is a **multi-tenant e-commerce dashboard** with the following key characteristics:

- **Multi-tenancy**: UUID-based tenant isolation for all data
- **SPA**: React + Inertia.js (no API endpoints, server-side routing)
- **Role-based access**: `owner` and `staff` roles with different permissions
- **Marketplace**: Public store fronts with admin dashboards

### Core Architecture Patterns

1. **Multi-tenant Models**: All tenant-specific models use:
   ```php
   use HasUuids, BelongsToTenant;
   // UUID primary keys + automatic tenant_id filtering
   ```

2. **SPA Response Pattern**: Controllers return `Inertia::render()` not `view()`

3. **Role-based Authorization**: Route middleware groups protect features
   ```php
   Route::middleware(['role:owner'])->group(function () {
       // Owner-only routes
   });
   ```

## Code Style Guidelines

### PHP/Laravel Conventions

**Naming:**
- Models: PascalCase (`Product`, `Category`, `Transaction`)
- Controllers: PascalCase + "Controller" suffix (`ProductController`)
- Methods: camelCase
- Database: snake_case
- Routes: kebab-case URLs

**Structure:**
- PSR-4 autoloading (`App\` namespace)
- Mass assignment via `$fillable` properties
- UUID primary keys everywhere (no auto-increment)
- Factory classes for all models
- Form request validation

**Imports:**
```php
// Order: Laravel imports, then third-party, then App namespace
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
```

### Frontend (React/Inertia)

**Components:**
- Pages in `resources/js/Pages/` organized by feature
- Layouts in `resources/js/Layouts/`
- Use React hooks for state management
- Conditional rendering based on `usePage().props.auth.user.role`

**Styling:**
- Tailwind CSS utility classes
- No CSS modules, use inline Tailwind
- Responsive design with mobile-first approach

### Database Patterns

**All tables must have:**
```php
// migrations
$table->uuid('id')->primary();
$table->foreignUuid('tenant_id')->constrained()->onDelete('cascade');
// ... other columns
$table->timestamps();
```

**Model traits:**
```php
class Product extends Model
{
    use HasFactory, HasUuids, BelongsToTenant;
    
    protected $fillable = ['tenant_id', 'name', 'price', 'stock', 'category_id'];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
```

## Testing Guidelines

### Test Structure
- **Feature tests**: Full request/response testing
- **Unit tests**: Individual method/class testing  
- **Database**: SQLite in-memory, `RefreshDatabase` trait

### Test Patterns
```php
class ProductTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_owner_can_create_product(): void
    {
        // Arrange: Create tenant and owner user
        // Act: Make request to create product
        // Assert: Product was created and response is correct
    }
}
```

### Running Tests
Always run `composer test` before committing to ensure all tests pass.

## Error Handling

### Backend
- Use form request validation (`StoreProductRequest`)
- Return proper Inertia responses with errors
- Handle tenant isolation automatically via middleware

### Frontend
- Display validation errors from Laravel
- Use Inertia's shared error handling
- Show loading states with `<progress>` component

## Key Development Rules

### Multi-tenant Development
1. **Always** include `tenant_id` in multi-tenant migrations
2. **Never** query across tenant boundaries (use model scopes)
3. **Always** use UUID primary keys, never auto-increment
4. **Test** tenant isolation thoroughly

### SPA Development
1. **Never** return JSON responses from controllers (use Inertia)
2. **Always** share common data via `HandleInertiaRequests` middleware
3. **Always** use `<Link>` for navigation, not `<a>` tags
4. **Never** mix Vue and React components (choose one)

### Security
1. **Always** validate user tenant ownership
2. **Never** expose sequential IDs (use UUIDs)
3. **Always** use role-based middleware protection
4. **Never** trust client-side authorization alone

## File Organization

### Backend Structure
```
app/
├── Http/Controllers/     # MVC controllers
├── Models/              # Eloquent models with UUID + tenant traits
├── Services/            # Business logic (TenantContext service)
├── Middleware/          # Custom middleware (CheckRole, SetupTenantContext)
└── Requests/            # Form request validation
```

### Frontend Structure
```
resources/js/
├── Pages/               # Route-based React components
│   ├── Dashboard.jsx
│   ├── Products/        # Product CRUD
│   ├── Categories/     # Category CRUD  
│   ├── Transactions/    # POS and history
│   ├── Auth/           # Authentication
│   └── Users/          # User management
├── Layouts/            # Reusable layouts
└── app.jsx             # SPA entry point
```

## Environment Requirements

- PHP 8.2+
- Node.js (for Vite)
- SQLite for development/testing
- Composer dependencies
- NPM dependencies

## Common Tasks

### Adding New Model
1. Create migration with UUID primary key + tenant_id
2. Create model with `HasUuids, BelongsToTenant` traits
3. Create factory class
4. Create controller with CRUD operations
5. Create React pages for frontend
6. Add routes with appropriate middleware
7. Write tests for all functionality

### Adding New Role
1. Update `users.role` enum in migration
2. Update `CheckRole` middleware if needed
3. Add role-based route protection
4. Update frontend role checks
5. Test authorization thoroughly

### Debugging
- Use `php artisan tinker` for backend debugging
- Check Laravel logs in `storage/logs/laravel.log`
- Use browser dev tools for React debugging
- Run `php artisan route:list` to verify routes
- Check `php artisan config:cache` if config issues

This codebase follows modern Laravel and React best practices with comprehensive testing, proper multi-tenant isolation, and clean SPA architecture.