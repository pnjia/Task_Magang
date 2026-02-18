# TESTING PLAN — API (PHPUnit / Pest) for E-Commerce Dashboard

Tujuan: menyediakan panduan langkah-demi-langkah agar agen AI (atau developer) dapat menyiapkan dan menjalankan test otomatis untuk seluruh API proyek menggunakan MySQL (phpMyAdmin) sebagai DB.

Catatan penting sebelum mulai
- Jangan jalankan test pada database produksi. Buat database terpisah mis. `ecommerce_test`.
- Project ini memakai Sanctum untuk API auth — test harus membuat user dan melakukan `actingAs($user, 'sanctum')` atau membuat token.
- Kita memakai MySQL (bukan SQLite). Pastikan test environment dikonfigurasi agar Laravel menjalankan migrasi pada DB test.

Ringkasan langkah
1. Persiapan environment
2. Konfigurasi `phpunit.xml` / `.env.testing`
3. Instalasi Pest / PHPUnit dan dependencies dev
4. Membuat test database & user (MySQL)
5. Menjalankan migrasi & seed khusus test
6. Menulis test feature untuk tiap endpoint API
7. Menambahkan test untuk error handling (500 simplifikasi)
8. Menjalankan test dan debugging
9. (Opsional) Menambahkan CI (GitHub Actions)

---

1) Persiapan environment (lokal)
- Pastikan `composer install` sudah dijalankan.
- Pastikan `php` CLI, `pdo_mysql` extension terpasang.
- Pastikan MySQL server berjalan (akses via phpMyAdmin / CLI).

Command contoh:
```bash
composer install
php -v
php -m | grep pdo_mysql
```

2) Buat database MySQL untuk testing
- Buat database bernama mis. `ecommerce_test` dan user khusus test (atau gunakan user lokal yang sama).
- Contoh SQL (jalankan di phpMyAdmin atau `mysql` CLI):
```sql
CREATE DATABASE ecommerce_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'ecommerce_test_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON ecommerce_test.* TO 'ecommerce_test_user'@'localhost';
FLUSH PRIVILEGES;
```

3) Konfigurasi `phpunit.xml` atau `.env.testing`
- Edit `phpunit.xml` (root) atau buat `.env.testing` untuk men-set DB credentials saat test dijalankan.
- Contoh bagian env di `phpunit.xml`:
```xml
<php>
    <env name="APP_ENV" value="testing"/>
    <env name="DB_CONNECTION" value="mysql"/>
    <env name="DB_HOST" value="127.0.0.1"/>
    <env name="DB_PORT" value="3306"/>
    <env name="DB_DATABASE" value="ecommerce_test"/>
    <env name="DB_USERNAME" value="ecommerce_test_user"/>
    <env name="DB_PASSWORD" value="strong_password"/>
</php>
```
- Alternatif: buat `.env.testing` dengan variabel di atas dan pastikan `phpunit.xml` menggunakan `ENV` loader (default Laravel/Pest mendukung `.env.testing`).

4) Install Pest (disarankan) atau gunakan PHPUnit bawaan
- Pasang Pest (lebih nyaman dan modern) jika belum terpasang:
```bash
composer require pestphp/pest --dev
composer require pestphp/pest-plugin-laravel --dev
php artisan test --clear-cache
```
- Atau gunakan PHPUnit:
```bash
./vendor/bin/phpunit
```

5) Migrasi & Seed untuk test
- Pastikan test dapat menjalankan migrasi di DB test. `RefreshDatabase` trait biasanya akan menggunakan connection `DB_CONNECTION` dari `phpunit.xml`.
- Jalankan migrasi manual pertama kali (opsional):
```bash
php artisan migrate --env=testing --database=mysql
```
- Jika menggunakan `RefreshDatabase` di test, Laravel akan menjalankan migration di awal test.
- Siapkan seeders / factories untuk data yang sering dipakai (tenant, user owner/staff, categories, products).

6) Struktur tests dan contoh test skeleton
- Lokasi: `tests/Feature/Api` untuk feature tests API.
- Buat test-file per resource: `AuthTest`, `UsersTest`, `ProfileTest`, `ProductsTest`, `CategoriesTest`, `TransactionsTest`.
- Gunakan factories untuk membuat data.

Contoh `tests/Feature/Api/ProfileTest.php` (Pest or PHPUnit compatible):
```php
<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns json for profile show', function () {
    $user = User::factory()->create();

    $this->actingAs($user, 'sanctum')
         ->getJson('/api/profile')
         ->assertStatus(200)
         ->assertJsonStructure(['user']);
});

it('can update profile and returns json', function () {
    $user = User::factory()->create();

    $payload = ['name' => 'New Name', 'email' => 'new@example.com'];

    $this->actingAs($user, 'sanctum')
         ->putJson('/api/profile', $payload)
         ->assertStatus(200)
         ->assertJsonFragment(['message' => 'Profile updated successfully.']);
});

it('delete profile returns json message', function () {
    $user = User::factory()->create(['password' => bcrypt('secret')]);

    $this->actingAs($user, 'sanctum')
         ->deleteJson('/api/profile', ['password' => 'secret'])
         ->assertStatus(200)
         ->assertJson(['message' => 'Account deleted successfully.']);
});
```

Contoh `tests/Feature/Api/UsersTest.php` update user role:
```php
it('updates user role via api', function () {
    $owner = User::factory()->create(['role' => 'owner']);
    $staff = User::factory()->create(['role' => 'cashier']);

    $this->actingAs($owner, 'sanctum')
         ->putJson('/api/users/' . $staff->id . '/role', ['role' => 'owner'])
         ->assertStatus(200)
         ->assertJson(['message' => 'Peran pengguna berhasil diperbarui.']);
});
```

7) Testing server error handling (500 simplifikasi)
- Tambahkan test yang memicu exception internal (contoh: force duplicate or throw exception from controller via mocking) dan assert response is generic 500 JSON.

Example (pseudo):
```php
it('returns generic 500 json on server error', function () {
    $user = User::factory()->create();

    // This assumes you can hit a route that will throw
    $this->actingAs($user, 'sanctum')
         ->postJson('/api/transactions', [/* bad payload that triggers DB exception */])
         ->assertStatus(500)
         ->assertJson(['message' => 'Internal Server Error']);
});
```
- Middleware `ApiExceptionMiddleware` yang sudah ditambahkan akan menangkap Throwable dan mengembalikan message/500.

8) Menjalankan test
- Jalankan test seluruhnya:
```bash
./vendor/bin/pest
# atau
./vendor/bin/phpunit --configuration phpunit.xml
```
- Jika menggunakan Pest:
```bash
./vendor/bin/pest --coverage
```

9) Debugging umum / tips
- Jika test gagal karena autentikasi: pastikan `actingAs($user, 'sanctum')` dan Sanctum dipasang pada middleware.
- Jika test gagal karena `RequestGuard::logout` (seperti kasus sebelum), pastikan controller tidak memanggil `Auth::logout()` untuk API requests.
- Cek `storage/logs/laravel.log` untuk stack trace.
- Pastikan `phpunit.xml` memuat env testing DB yang benar dan `RefreshDatabase` trait bekerja dengan MySQL (Laravel migrates in transaction only for sqlite in-memory; with MySQL `RefreshDatabase` will run migrations normally so it is ok).

10) CI (GitHub Actions) contoh (ringkas)
- Buat workflow yang menjalankan service `mysql` di actions, lalu:
```yaml
services:
  mysql:
    image: mysql:8
    env:
      MYSQL_DATABASE: ecommerce_test
      MYSQL_ROOT_PASSWORD: root
    ports:
      - 3306:3306
    options: >-
      --health-cmd "mysqladmin ping --silent"

steps:
- uses: actions/checkout@v3
- name: Set up PHP
  uses: shivammathur/setup-php@v2
  with:
    php-version: '8.2'
    extensions: mbstring, intl, pdo_mysql
- run: composer install --prefer-dist --no-progress --no-suggest
- name: Run migrations
  run: php artisan migrate --env=testing
- name: Run tests
  run: ./vendor/bin/pest --colors
```

11) Dokumentasi & best practices
- Simpan `TESTING_PLAN.md` (file ini) di repo.
- Tambahkan contoh test templates ke `tests/Feature/Api`.
- Perbarui factories agar mudah membuat tenant + owner + staff.
- Pastikan seeders untuk tenants/users ada dan digunakan pada test yang memerlukan multi-tenant setup.

---

Checklist minimal yang harus Anda lakukan sekarang
- [ ] Buat DB `ecommerce_test` dan user; tambah cred ke `phpunit.xml` atau `.env.testing`.
- [ ] Install Pest (opsional) dan jalankan `./vendor/bin/pest`.
- [ ] Buat beberapa test skeleton seperti contoh di atas.
- [ ] Jalankan tests dan perbaiki error yang muncul. Lihat `storage/logs` bila perlu.

Jika mau, saya bisa:
- Men-generate contoh test files (`tests/Feature/Api/*.php`) langsung di repo berdasarkan controller yang ada.
- Menyiapkan GitHub Actions workflow file untuk menjalankan test dengan MySQL.
- Membuat seeders/factories spesifik yang dipakai di test.

Tolong konfirmasi langkah mana yang Anda ingin saya kerjakan berikutnya (generate tests, setup CI, atau hanya buatkan checklist/README seperti ini).