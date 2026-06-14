# 📒 Buku Tamu Digital — Sekolah

> UTS Pemrograman Web II (IF 405) · Universitas Siber Asia (UNSIA)

| | |
|---|---|
| **Nama** | Ghea Syaumil Rakhmah |
| **NIM** | 240401010081 |
| **Repo** | [github.com/gheasyaumilr/buku-tamu](https://github.com/gheasyaumilr/buku-tamu) |

---

## Deskripsi

Aplikasi web sederhana untuk mencatat buku tamu sekolah. Tamu mengisi form (nama, instansi, tujuan kedatangan), data tersimpan ke MySQL, lalu ditampilkan dalam tabel responsif dengan Bootstrap 5 dan fitur pencarian.

**Fitur utama:**
- Form input tamu (Nama, Instansi, Tujuan) dengan tanggal & waktu otomatis
- Halaman daftar tamu dengan tabel Bootstrap dan kolom pencarian
- Tampilan responsif — Bootstrap 5 via CDN

---

## Screenshots

### Form Input Tamu (`index.php`)

<p align="center">
  <img src="screenshot/form-desktop.png" alt="Form Input Tamu — tampilan desktop" width="720">
</p>

<p align="center">
  <img src="screenshot/form-mobile.png" alt="Form Input Tamu — tampilan mobile" width="360">
</p>

### Daftar Tamu (`daftar_tamu.php`)

<p align="center">
  <img src="screenshot/daftar-tamu-desktop.png" alt="Daftar Tamu — tampilan desktop" width="720">
</p>

> Simpan file screenshot ke folder `screenshot/` di root proyek. Nama file yang disarankan: `form-desktop.png`, `form-mobile.png`, `daftar-tamu-desktop.png`.

---

## Struktur Folder

```
buku-tamu/
├── index.php            → Halaman form input tamu
├── daftar_tamu.php      → Halaman daftar tamu + pencarian
├── koneksi.php          → Konfigurasi koneksi database (mysqli) — tidak di-commit
├── koneksi.php.example  → Template koneksi (salin & isi kredensial lokal)
├── db_bukutamu.sql      → Struktur database & 3 baris data contoh
├── assets/
│   └── style.css        → Custom CSS tambahan
├── screenshot/          → Screenshot antarmuka (opsional di .gitignore)
└── README.md
```

---

## Struktur Tabel `buku_tamu`

| Kolom    | Tipe Data                        |
|----------|----------------------------------|
| id       | INT, AUTO_INCREMENT, PRIMARY KEY |
| nama     | VARCHAR(100)                     |
| instansi | VARCHAR(100)                     |
| tujuan   | TEXT                             |
| tanggal  | DATE                             |
| waktu    | TIME                             |

---

## Cara Instalasi (XAMPP / Laragon)

1. **Pastikan PHP & MySQL aktif** (XAMPP/Laragon).

2. **Copy folder** `buku-tamu` ke:
   - XAMPP → `htdocs/`
   - Laragon → `www/`

3. **Import database** via phpMyAdmin (`http://localhost/phpmyadmin`):
   - Klik **Import** → pilih `db_bukutamu.sql` → klik **Go**
   - Database `db_bukutamu` dan tabel `buku_tamu` otomatis terbentuk beserta 3 data contoh.

4. **Buat file koneksi** dari template:
   ```bash
   cp koneksi.php.example koneksi.php
   ```
   Lalu edit sesuai kredensial lokal (lihat bagian [Konfigurasi Database](#konfigurasi-database)).

5. **Buka browser:**
   ```
   http://localhost/buku-tamu/index.php
   ```

---

## Konfigurasi Database

Edit file `koneksi.php` jika konfigurasi MySQL berbeda:

```php
<?php
$host   = "localhost";
$user   = "root";
$pass   = "";          // isi password jika ada
$dbname = "db_bukutamu";

$conn = mysqli_connect($host, $user, $pass, $dbname);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
```

> ⚠️ File `koneksi.php` **tidak disertakan di repo** karena berisi kredensial. Gunakan `koneksi.php.example` sebagai template.

---

## Upload ke GitHub

Jika belum punya repo lokal, jalankan dari direktori induk (mis. `htdocs`):

```bash
cd /path/to/htdocs
git init
git add buku-tamu
git commit -m "Initial commit: Buku Tamu Digital"

# Ganti URL sesuai repo Anda
git remote add origin https://github.com/gheasyaumilr/buku-tamu.git
git branch -M main
git push -u origin main
```

Untuk menambahkan screenshot setelah commit pertama:

```bash
git add screenshot/form-desktop.png screenshot/daftar-tamu-desktop.png
git commit -m "Add screenshots"
git push
```

---

## `.gitignore` yang Disarankan

```gitignore
# Sistem / IDE
.DS_Store
node_modules/
.vscode/

# Kredensial — WAJIB diabaikan
koneksi.php

# Opsional: abaikan jika database dump bersifat privat
# db_bukutamu.sql

# Opsional: abaikan screenshot jika tidak ingin di-commit
# screenshot/
```

> 💡 Jika ingin screenshot tampil di README GitHub, pastikan baris `screenshot/` **tidak ada** (atau dikomentari) di `.gitignore`.

---

## Tips Screenshot untuk README

| Ukuran | Keterangan |
|--------|-----------|
| 800–1200 px lebar | Desktop |
| 360–480 px lebar | Mobile |
| Kompres ke < 500 KB | Agar repo tidak membengkak |

Akses screenshot lokal via browser saat server berjalan:
```
http://localhost/buku-tamu/screenshot/<nama-file>.png
```
