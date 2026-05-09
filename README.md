# Sistem Pendaftaran Peserta Sukan Rakyat Kampung Budiman

Sistem ini ialah aplikasi pendaftaran peserta untuk Sukan Rakyat / Sukan SULAM Kampung Budiman. Peserta awam boleh mendaftar melalui pautan atau QR code tanpa log masuk, menerima kod pendaftaran unik, dan menyemak status pendaftaran. Sebarang pembetulan maklumat dibuat oleh admin melalui panel pengurusan.

## Stack

- Laravel 12
- PHP 8.2+
- MySQL/MariaDB untuk produksi
- SQLite boleh digunakan untuk pembangunan tempatan
- Blade, Tailwind CSS, Flowbite, Vite
- Laravel Breeze authentication

## Pemasangan

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

## Tetapan `.env`

Untuk MySQL/MariaDB:

```env
APP_NAME="Sukan Budiman"
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sukan_budiman
DB_USERNAME=root
DB_PASSWORD=
```

Untuk SQLite tempatan:

```env
DB_CONNECTION=sqlite
```

Pastikan fail `database/database.sqlite` wujud jika menggunakan SQLite.

## Database

```bash
php artisan migrate:fresh --seed
```

Seeder akan mencipta:

- Admin default
- Contoh rumah sukan
- Data acara Sukan SULAM

## Login Admin Default

- Emel: `admin@budiman.test`
- Kata laluan: `password`

Tukar kata laluan ini sebelum digunakan secara sebenar.

## Run Local

```bash
npm run dev
php artisan serve
```

Buka `http://127.0.0.1:8000`.

Untuk build aset produksi:

```bash
npm run build
```

## Route Awam

- `/` - Landing page
- `/daftar` - Borang pendaftaran peserta
- `/berjaya/{registration_code}` - Paparan berjaya
- `/semak` - Semak pendaftaran
- `/status/{registration_code}` - Status pendaftaran

## Route Admin

- `/login` - Log masuk admin
- `/admin/dashboard` - Dashboard
- `/admin/participants` - Pengurusan peserta
- `/admin/houses` - Pengurusan rumah sukan
- `/admin/sports` - Pengurusan acara sukan
- `/admin/reports` - Laporan dan eksport CSV

## Ciri Utama

- Pendaftaran peserta awam tanpa log masuk
- Kod pendaftaran unik
- Peserta awam tidak boleh edit selepas hantar
- Maklumat penjaga wajib untuk peserta kanak-kanak
- Admin boleh tambah, lihat, edit dan padam peserta
- Admin boleh urus rumah sukan dan acara
- Assign peserta kepada acara
- Eksport CSV
- Link WhatsApp peringatan ringkas
- Audit log untuk tindakan create, update dan delete admin

## Ujian

```bash
php artisan test
```

## Had Semasa

- Tiada integrasi SMS atau WhatsApp API berbayar.
- Audit log direkodkan dalam database tetapi belum ada halaman paparan khusus.
- Role management penuh dengan Spatie belum dipasang kerana hanya admin asas diperlukan pada fasa ini.
- Tarikh, masa dan lokasi acara belum dijadikan tetapan sistem.

## Cadangan Penambahbaikan

- Halaman audit log admin.
- Tetapan tarikh, masa dan lokasi untuk mesej peringatan.
- Cetakan senarai peserta mengikut rumah sukan/acara.
- QR code generator untuk pautan `/daftar`.
- Role tambahan seperti petugas pendaftaran atau penyelaras acara.
