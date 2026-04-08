# SPGDT API - Report Management System

Sebuah RESTful API yang dibangun dengan Laravel untuk mengelola data laporan dengan fitur filtering, pagination, dan validasi data yang komprehensif.

## 📋 Daftar Isi

- [Tentang Project](#tentang-project)
- [Persyaratan](#persyaratan)
- [Instalasi](#instalasi)
- [Setup Database](#setup-database)
- [Menjalankan Server](#menjalankan-server)
- [API Endpoints](#api-endpoints)
- [Query Parameters](#query-parameters)
- [Contoh Penggunaan](#contoh-penggunaan)
- [Struktur Project](#struktur-project)
- [Clean Code Principles](#clean-code-principles)

---

## Tentang Project

**SPGDT API** adalah sistem backend untuk mengelola laporan berbasis REST API. Project ini dibangun mengikuti best practices Laravel dengan fokus pada:

- ✅ Clean Code & Maintainability
- ✅ Advanced Filtering & Pagination
- ✅ Form Request Validation
- ✅ API Resource Formatting
- ✅ Database Optimization dengan Index

### Fitur Utama

1. **GET API Endpoint** - Mengambil data laporan
2. **Advanced Filtering** - Filter multi-parameter (type, status, city, date range)
3. **Pagination** - Performa optimal dengan limit data
4. **Data Validation** - Validasi lengkap menggunakan Form Request
5. **Consistent Response** - Format JSON yang terstandar
6. **Database Index** - Index pada kolom yang sering di-filter

---

## Persyaratan

- PHP >= 8.1
- Composer
- Laravel 13.x
- MySQL/SQLite
- Git

---

## Instalasi

### 1. Clone Repository

```bash
git clone <repository-url>
cd spgdt-api
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure Database

Edit file `.env` dan sesuaikan database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=spgdt_api
DB_USERNAME=root
DB_PASSWORD=
```

---

## Setup Database

### 1. Jalankan Migration

```bash
php artisan migrate
```

Perintah ini akan membuat tabel `reports` dengan struktur:

```sql
CREATE TABLE reports (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  type VARCHAR(255) NOT NULL,
  status VARCHAR(255) NOT NULL,
  city VARCHAR(255) NOT NULL,
  date DATE NOT NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  INDEX idx_type (type),
  INDEX idx_status (status),
  INDEX idx_city (city),
  INDEX idx_date (date)
);
```

### 2. Generate Data Dummy

```bash
php artisan db:seed
```

Atau spesifik:

```bash
php artisan db:seed --class=ReportSeeder
```

Seeder akan membuat 20 data laporan dummy dengan variasi:

- **Types**: ambulance, emergency, fire, police, medical
- **Statuses**: pending, in-progress, completed, cancelled
- **Cities**: Jakarta, Surabaya, Bandung, Medan, Semarang, Makassar, Palembang, Yogyakarta
- **Dates**: Dalam 30 hari terakhir dari sekarang

---

## Menjalankan Server

```bash
php artisan serve
```

Server akan berjalan di: `http://127.0.0.1:8000`

---

## API Endpoints

### GET /api/reports

Mengambil data laporan dengan dukungan filter dan pagination.

**Base URL:**

```
http://127.0.0.1:8000/api/reports
```

**Method:** `GET`

**Response Format:**

```json
{
    "message": "success",
    "data": [
        {
            "id": 1,
            "type": "ambulance",
            "status": "pending",
            "city": "Jakarta",
            "date": "2026-04-05",
            "created_at": "2026-04-08 12:00:00",
            "updated_at": "2026-04-08 12:00:00"
        }
    ],
    "meta": {
        "page": 1,
        "limit": 10,
        "total": 20
    }
}
```

---

## Query Parameters

| Parameter    | Tipe    | Default | Keterangan                                       |
| ------------ | ------- | ------- | ------------------------------------------------ |
| `page`       | Integer | 1       | Nomor halaman pagination                         |
| `limit`      | Integer | 10      | Jumlah data per halaman (max: 50)                |
| `type`       | String  | -       | Filter jenis laporan (ambulance, emergency, dll) |
| `status`     | String  | -       | Filter status laporan (pending, completed, dll)  |
| `city`       | String  | -       | Filter kota                                      |
| `start_date` | Date    | -       | Filter tanggal mulai (format: Y-m-d)             |
| `end_date`   | Date    | -       | Filter tanggal akhir (format: Y-m-d)             |

### Validasi Parameter

- ✅ `page` - Integer positif, minimum 1
- ✅ `limit` - Integer positif, max 50 records
- ✅ `start_date` & `end_date` - Format date Y-m-d
- ✅ Semua parameter optional (bersifat opsional)

---

## Contoh Penggunaan

### 1. Get All Reports (Dasar)

```bash
curl "http://127.0.0.1:8000/api/reports"
```

### 2. Filter Berdasarkan Type

```bash
curl "http://127.0.0.1:8000/api/reports?type=ambulance"
```

### 3. Filter Berdasarkan Status

```bash
curl "http://127.0.0.1:8000/api/reports?status=completed"
```

### 4. Filter Berdasarkan Kota

```bash
curl "http://127.0.0.1:8000/api/reports?city=Jakarta"
```

### 5. Filter Berdasarkan Date Range

```bash
curl "http://127.0.0.1:8000/api/reports?start_date=2026-03-01&end_date=2026-04-08"
```

### 6. Kombinasi Filter + Pagination

```bash
curl "http://127.0.0.1:8000/api/reports?type=ambulance&city=Jakarta&page=1&limit=5"
```

### 7. Custom Pagination

```bash
curl "http://127.0.0.1:8000/api/reports?page=2&limit=20"
```

### 8. Multiple Filters

```bash
curl "http://127.0.0.1:8000/api/reports?type=emergency&status=pending&city=Surabaya&start_date=2026-04-01&limit=15"
```

---

## Struktur Project

```
spgdt-api/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/
│   │   │       └── ReportController.php       # API Controller
│   │   ├── Requests/
│   │   │   └── GetReportsRequest.php          # Form Request Validation
│   │   └── Resources/
│   │       └── ReportResource.php             # API Resource Formatting
│   └── Models/
│       └── Report.php                         # Report Model dengan Filter Scope
├── database/
│   ├── migrations/
│   │   └── 2024_04_08_000001_create_reports_table.php
│   └── seeders/
│       ├── ReportSeeder.php                   # Report Data Seeder
│       └── DatabaseSeeder.php
├── routes/
│   └── api.php                                # API Routes Definition
├── bootstrap/
│   └── app.php                                # Application Configuration
├── .env                                       # Environment Configuration
├── composer.json
└── README.md
```

---

## Clean Code Principles

### 1. Thin Controller

Controller hanya bertanggung jawab untuk:

- Menerima request
- Memanggil business logic
- Return response

**File:** `app/Http/Controllers/Api/ReportController.php`

```php
public function index(GetReportsRequest $request): JsonResponse
{
    $filters = $request->validated();
    $limit = $filters['limit'] ?? 10;
    $page = $filters['page'] ?? 1;

    $query = Report::filter($filters);
    $total = $query->count();
    $reports = $query->orderByDesc('created_at')->paginate($limit, ['*'], 'page', $page);

    return response()->json([
        'message' => 'success',
        'data' => ReportResource::collection($reports->items()),
        'meta' => [
            'page' => $reports->currentPage(),
            'limit' => $reports->perPage(),
            'total' => $total,
        ],
    ]);
}
```

### 2. Query Scope

Filtering logic dipindahkan ke Model menggunakan Query Scope

**File:** `app/Models/Report.php`

```php
public function scopeFilter(Builder $query, array $filters): Builder
{
    return $query
        ->when($filters['type'] ?? null, fn($q, $type) => $q->where('type', $type))
        ->when($filters['status'] ?? null, fn($q, $status) => $q->where('status', $status))
        ->when($filters['city'] ?? null, fn($q, $city) => $q->where('city', $city))
        ->when($filters['start_date'] ?? null, fn($q, $startDate) => $q->whereDate('date', '>=', $startDate))
        ->when($filters['end_date'] ?? null, fn($q, $endDate) => $q->whereDate('date', '<=', $endDate));
}
```

### 3. Form Request Validation

Validasi terpusat menggunakan Form Request bukan di Controller

**File:** `app/Http/Requests/GetReportsRequest.php`

```php
public function rules(): array
{
    return [
        'page' => 'sometimes|integer|min:1',
        'limit' => 'sometimes|integer|min:1|max:50',
        'type' => 'sometimes|string|max:50',
        'status' => 'sometimes|string|max:50',
        'city' => 'sometimes|string|max:100',
        'start_date' => 'sometimes|date_format:Y-m-d',
        'end_date' => 'sometimes|date_format:Y-m-d',
    ];
}
```

### 4. API Resource

Data formatting terpusat menggunakan Resource bukan di Controller

**File:** `app/Http/Resources/ReportResource.php`

```php
public function toArray(Request $request): array
{
    return [
        'id' => $this->id,
        'type' => $this->type,
        'status' => $this->status,
        'city' => $this->city,
        'date' => $this->date->format('Y-m-d'),
        'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
    ];
}
```

### 5. Database Optimization

Menambahkan index pada kolom yang sering di-filter

```php
$table->index('type');
$table->index('status');
$table->index('city');
$table->index('date');
```

---

## Contoh Testing dengan Postman

### Setup Postman Collection

**1. Create New Request**

- Method: `GET`
- URL: `http://127.0.0.1:8000/api/reports`

**2. Add Query Parameters**

- Click "Params" tab
- Tambahkan parameter sesuai kebutuhan

**3. Send Request**

- Click "Send"
- Lihat response di "Pretty" tab

### Contoh Request di Postman

```
GET /api/reports?type=ambulance&status=pending&limit=10&page=1
Host: 127.0.0.1:8000
```

---
