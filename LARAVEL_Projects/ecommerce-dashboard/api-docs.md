# API Documentation - E-Commerce Dashboard

Dokumentasi API untuk proyek Laravel multi-tenant e-commerce dashboard. Semua endpoint menggunakan prefix `/api` dan sebagian besar memerlukan autentikasi via Sanctum token.

## Autentikasi

- **Sanctum Token**: Sertakan header `Authorization: Bearer {token}` untuk endpoint yang dilindungi.
- **CSRF**: Tidak diperlukan untuk endpoint API (kecuali web routes).

## Endpoint API

### User

#### GET /api/user
Mengembalikan data user yang sedang login.

**Auth**: Required (Sanctum)  
**Response**: JSON dengan data user.

### Autentikasi

#### GET /api/login
Menampilkan form login (untuk API, mungkin tidak digunakan langsung).

**Auth**: None  
**Response**: HTML form atau JSON tergantung request.

#### POST /api/login
Proses login user.

**Auth**: None  
**Body**:
```json
{
  "email": "user@example.com",
  "password": "password"
}
```
**Response**: JSON dengan token atau error.

#### GET /api/register
Menampilkan form register.

**Auth**: None  
**Response**: HTML form atau JSON.

#### POST /api/register
Proses register user baru.

**Auth**: None  
**Body**:
```json
{
  "name": "Nama User",
  "email": "user@example.com",
  "password": "password",
  "password_confirmation": "password"
}
```
**Response**: JSON dengan data user atau error.

#### POST /api/logout
Logout user.

**Auth**: Required (Sanctum)  
**Response**: JSON konfirmasi logout.

### Dashboard

#### GET /api/dashboard
Menampilkan data dashboard (ringkasan penjualan, dll.).

**Auth**: Required (Sanctum)  
**Response**: JSON dengan data dashboard.

### Produk

#### GET /api/products
Daftar produk (dengan pagination).

**Auth**: Required (Sanctum)  
**Query Params**: page, search, dll.  
**Response**: JSON dengan list produk.

#### POST /api/products
Buat produk baru.

**Auth**: Required (Sanctum)  
**Body**:
```json
{
  "name": "Nama Produk",
  "price": 10000,
  "stock": 50,
  "category_id": "uuid-category"
}
```
**Response**: JSON dengan data produk baru.

#### GET /api/products/{id}
Detail produk spesifik.

**Auth**: Required (Sanctum)  
**Response**: JSON dengan data produk.

#### PUT /api/products/{id}
Update produk.

**Auth**: Required (Sanctum)  
**Body**: Sama seperti POST, field opsional.  
**Response**: JSON dengan data produk terupdate.

#### DELETE /api/products/{id}
Hapus produk.

**Auth**: Required (Sanctum)  
**Response**: JSON konfirmasi.

### Kategori

#### GET /api/categories
Daftar kategori (dengan pagination).

**Auth**: Required (Sanctum)  
**Response**: JSON dengan list kategori.

#### POST /api/categories
Buat kategori baru.

**Auth**: Required (Sanctum)  
**Body**:
```json
{
  "name": "Nama Kategori"
}
```
**Response**: JSON dengan data kategori baru.

#### GET /api/categories/{id}
Detail kategori spesifik.

**Auth**: Required (Sanctum)  
**Response**: JSON dengan data kategori.

#### PUT /api/categories/{id}
Update kategori.

**Auth**: Required (Sanctum)  
**Body**:
```json
{
  "name": "Nama Kategori Baru"
}
```
**Response**: JSON dengan data kategori terupdate.

#### DELETE /api/categories/{id}
Hapus kategori.

**Auth**: Required (Sanctum)  
**Response**: JSON konfirmasi.

### Transaksi

#### GET /api/transactions
Daftar transaksi aktif (unpaid, paid, processing, shipped).

**Auth**: Required (Sanctum)  
**Query Params**: search, date_from, date_to, filter_status.  
**Response**: JSON dengan list transaksi.

#### GET /api/transactions/history
Riwayat transaksi (completed, cancelled).

**Auth**: Required (Sanctum)  
**Query Params**: search, date_from, date_to.  
**Response**: JSON dengan list transaksi.

#### GET /api/transactions/{id}
Detail transaksi spesifik.

**Auth**: Required (Sanctum)  
**Response**: JSON dengan data transaksi dan detail.

#### POST /api/transactions
Buat transaksi baru (POS/checkout).

**Auth**: Required (Sanctum)  
**Body**:
```json
{
  "payment_amount": 50000,
  "cart": [
    {
      "id": "uuid-produk",
      "qty": 2
    },
    {
      "id": "uuid-produk-lain",
      "qty": 1
    }
  ]
}
```
**Response**: JSON dengan data transaksi dan pesan sukses.

#### PUT /api/transactions/{id}/status
Update status transaksi.

**Auth**: Required (Sanctum)  
**Body**:
```json
{
  "status": "paid"
}
```
**Response**: JSON dengan data transaksi terupdate.

#### PUT /api/transactions/{id}/confirm-payment
Konfirmasi pembayaran (set payment_amount = total_amount).

**Auth**: Required (Sanctum)  
**Response**: JSON dengan data transaksi.

#### PUT /api/transactions/{id}
Update transaksi (status).

**Auth**: Required (Sanctum)  
**Body**:
```json
{
  "status": "completed"
}
```
**Response**: JSON dengan data transaksi.

### User Management

#### GET /api/users
Daftar user (dengan pagination).

**Auth**: Required (Sanctum)  
**Response**: JSON dengan list user.

#### POST /api/users
Buat user baru.

**Auth**: Required (Sanctum)  
**Body**:
```json
{
  "name": "Nama User",
  "email": "user@example.com",
  "password": "password",
  "role": "staff"
}
```
**Response**: JSON dengan data user baru.

#### GET /api/users/{id}
Detail user spesifik.

**Auth**: Required (Sanctum)  
**Response**: JSON dengan data user.

#### PUT /api/users/{id}
Update user.

**Auth**: Required (Sanctum)  
**Body**:
```json
{
  "name": "Nama Baru",
  "email": "email@baru.com",
  "password": "password_baru",
  "password_confirmation": "password_baru"
}
```
**Response**: JSON dengan data user terupdate dan pesan sukses.

#### DELETE /api/users/{id}
Hapus user.

**Auth**: Required (Sanctum)  
**Response**: JSON konfirmasi.

#### PUT /api/users/{id}/role
Update role user.

**Auth**: Required (Sanctum)  
**Body**:
```json
{
  "role": "owner"
}
```
**Response**: JSON dengan data user.

### Profile

#### GET /api/profile
Data profile user login.

**Auth**: Required (Sanctum)  
**Response**: JSON dengan data profile.

#### PUT /api/profile
Update profile.

**Auth**: Required (Sanctum)  
**Body**:
```json
{
  "name": "Nama Baru",
  "email": "email@baru.com"
}
```
**Response**: JSON dengan data profile terupdate.

#### PUT /api/profile/password
Update password.

**Auth**: Required (Sanctum)  
**Body**:
```json
{
  "current_password": "password_lama",
  "password": "password_baru",
  "password_confirmation": "password_baru"
}
```
**Response**: JSON konfirmasi.

#### DELETE /api/profile
Hapus akun user.

**Auth**: Required (Sanctum)  
**Response**: JSON konfirmasi.