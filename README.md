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
  <img src="https://raw.githubusercontent.com/gheasyaumilr/buku-tamu/refs/heads/main/screenshot/formulir.png" alt="Form Input Tamu" width="720">
</p>

### Edit Data Tamu

<p align="center">
  <img src="https://raw.githubusercontent.com/gheasyaumilr/buku-tamu/refs/heads/main/screenshot/edit.png" alt="Edit Data Tamu" width="720">
</p>

### Daftar Tamu (`daftar_tamu.php`)

<p align="center">
  <img src="https://raw.githubusercontent.com/gheasyaumilr/buku-tamu/refs/heads/main/screenshot/daftar-tamu.png" alt="Daftar Tamu" width="720">
</p>

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
