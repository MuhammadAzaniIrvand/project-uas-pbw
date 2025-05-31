# Sistem Manajemen Laboratorium FMIPA (LabSys)

Sistem manajemen laboratorium berbasis web untuk Fakultas MIPA yang memungkinkan pengelolaan inventaris, peminjaman alat, dan penjadwalan laboratorium secara terintegrasi.

## Demo Aplikasi

Lihat demo aplikasi ini di: https://youtu.be/7tqEpRYq7Pw

Gunakan akun berikut untuk mencoba:
- Admin: admin@labsys.test / admin_password_123
- Aslab: ASLAB001 / aslab_password_123

## Fitur Utama

- Manajemen Inventaris Laboratorium
- Sistem Peminjaman Alat Lab
- Penjadwalan Penggunaan Lab
- Manajemen User (Admin, Aslab, Mahasiswa)
- Notifikasi Status Peminjaman
- Laporan dan Riwayat Penggunaan

## Prasyarat

Sebelum menginstall sistem ini, pastikan komputer Anda memiliki:

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL/MariaDB
- Git

## Cara Instalasi

1. Clone repository ini:
```bash
git clone [URL_REPOSITORY]
cd LabSys
```

2. Install dependencies PHP:
```bash
composer install
```

3. Install dependencies JavaScript:
```bash
npm install
```

4. Salin file .env.example menjadi .env:
```bash
copy .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Konfigurasi database di file .env:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=labsys
DB_USERNAME=root
DB_PASSWORD=
```

7. Jalankan migrasi dan seeder:
```bash
php artisan migrate:fresh --seed
```

## Menjalankan Aplikasi

1. Jalankan server Laravel:
```bash
php artisan serve
```

2. Di terminal terpisah, jalankan Vite untuk asset compilation:
```bash
npm run dev
```

3. Akses aplikasi di browser:
```
http://localhost:8000
```

## Akun Default

### Admin
- Email: admin@labsys.test
- Password: admin_password_123

### Aslab
- Email: aslab.informatika@labsys.test
- Password: aslab_password_123
- NPM: ASLAB001

## Struktur Sistem

- `/app` - Core aplikasi
- `/database` - Migrasi dan seeder
- `/resources` - Views dan assets
- `/routes` - File routing
- `/public` - File publik
- `/tests` - Unit dan feature tests

## Kontribusi

Jika ingin berkontribusi:
1. Fork repository
2. Buat branch baru
3. Commit perubahan
4. Push ke branch
5. Buat Pull Request

