# Buku Tamu Digital - Sekolah
UTS Pemrograman Web II (IF 405)
Universitas Siber Asia (UNSIA)

## Author / Tugas

- Ghea Syaumil Rakhmah
- NIM: 240401010081

## Deskripsi
Aplikasi web sederhana untuk mencatat buku tamu sekolah. Tamu mengisi form
(nama, instansi, tujuan kedatangan), data tersimpan ke MySQL, lalu
ditampilkan dalam tabel dengan styling Bootstrap dan fitur pencarian.

[Repository GitHub](https://github.com/gheasyaumilr/buku-tamu)
## Struktur Folder
```

## Siap Upload ke GitHub

Jika Anda ingin mengunggah seluruh folder proyek (`buku-tamu/`) ke GitHub, berikut langkah cepat yang bisa dicopy-paste dari terminal di direktori induk (mis. `htdocs`):

```bash
# Buat repo lokal (jika belum)
cd /path/to/htdocs
git init
git add buku-tamu
git commit -m "Initial commit: Buku Tamu Digital"

# Buat repo di GitHub lalu tambahkan remote (ganti URL repo Anda)
git remote add origin https://github.com/gheasyaumilr/buku-tamu.git
git branch -M main
git push -u origin main
```

Rekomendasi `.gitignore` untuk proyek ini (agar tidak mengunggah file yang sensitif atau tidak perlu):

```
# Sistem / IDE
.DS_Store
node_modules/
.vscode/

# Database dump jika ingin privat
db_bukutamu.sql

# File konfigurasi lokal yang berisi kredensial
koneksi.php

# Folder screenshot (opsional: jika tidak ingin di-commit)
/screenshot/
```

Catatan keamanan dan praktis:

buku-tamu/
├── index.php          -> Halaman form input tamu
├── daftar_tamu.php     -> Halaman daftar tamu + pencarian
├── koneksi.php         -> Konfigurasi koneksi database (mysqli)
├── db_bukutamu.sql     -> File SQL struktur database & data contoh
├── assets/
│   └── style.css       -> Custom CSS tambahan
└── README.md
```

## Screenshots

Semua screenshot antarmuka disimpan di folder `screenshot/` di dalam root proyek.
   - Buka file gambar dengan image viewer di OS Anda, atau akses langsung melalui browser jika server lokal berjalan:

```
http://localhost/buku-tamu/screenshot/<nama-file>.png
```

Tambahkan file screenshot baru ke folder `screenshot/` jika Anda ingin menyimpan capture layar saat menguji tampilan mobile/desktop.

### Menampilkan screenshot di GitHub

Jika Anda meng-upload repo ke GitHub, Anda bisa menampilkan screenshot langsung di `README.md` dengan cara berikut:

- Pastikan folder `screenshot/` TIDAK ada di `.gitignore`. Jika ada, hapus atau komen baris `/screenshot/` di `.gitignore` sebelum commit.
- Tambahkan file gambar ke folder `screenshot/` dan commit file tersebut ke repo.

Contoh perintah (dijalankan dari root repo):

```bash
git add screenshot/form-mobile.png
git commit -m "Add screenshot: form mobile"
git push
```

Contoh cara memasukkan gambar ke `README.md` (Markdown relatif):

```markdown
![Form mobile view](screenshot/form-mobile.png)
```

Jika Anda ingin mengatur lebar tampilannya, gunakan tag HTML (GitHub mendukung HTML di Markdown):

```html
<p align="center">
   <img src="screenshot/form-mobile.png" alt="Form mobile" width="720">
</p>
```

Tips praktis:
- Gunakan nama file yang jelas (mis. `form-mobile.png`, `daftar-tamu-desktop.png`) agar mudah direferensikan.
- Resize atau kompres gambar sebelum commit (mis. 800–1200 px lebar untuk screenshot desktop; 360–480 px untuk mobile) agar repo tidak membengkak.
- Jika file gambar sangat besar atau banyak (>100 MB atau banyak file), pertimbangkan menggunakan Git LFS (Large File Storage) atau host gambar di layanan eksternal.

Setelah push, GitHub akan menampilkan gambar di halaman README secara otomatis saat Anda membuka repo di browser.



## Cara Instalasi (XAMPP/Laragon)

1. Pastikan PHP & MySQL aktif (XAMPP/Laragon).
2. Copy folder `buku-tamu` ke dalam folder `htdocs` (XAMPP) atau `www` (Laragon).
3. Buka phpMyAdmin (http://localhost/phpmyadmin).
4. Import file `db_bukutamu.sql`:
   - Klik "Import" -> pilih file `db_bukutamu.sql` -> klik "Go"
   - Database `db_bukutamu` dan tabel `buku_tamu` akan otomatis dibuat,
     beserta 3 baris data contoh.
5. Buka browser, akses:
```
http://localhost/buku-tamu/index.php
```

## Konfigurasi Database
Jika konfigurasi MySQL Anda berbeda (user/password), edit file `koneksi.php`:
```php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "db_bukutamu";
```

Catatan: Untuk keamanan, file sebenarnya `koneksi.php` tidak disertakan di repo.
Gunakan `koneksi.php.example` sebagai template: salin file tersebut ke `koneksi.php` dan isi kredensial lokal Anda.


- Form input tamu (Nama, Instansi, Tujuan) dengan tanggal & waktu otomatis
- Halaman daftar tamu menampilkan seluruh data dalam tabel Bootstrap
- Tampilan responsif menggunakan Bootstrap 5 (CDN).

## Struktur Tabel `buku_tamu`
| Kolom    | Tipe Data         |
|----------|-------------------|
| id       | INT, AUTO_INCREMENT, PRIMARY KEY |
| nama     | VARCHAR(100)      |
| instansi | VARCHAR(100)      |
| tujuan   | TEXT              |
| tanggal  | DATE              |
| waktu    | TIME              |
