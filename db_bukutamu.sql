-- Database: db_bukutamu
CREATE DATABASE IF NOT EXISTS db_bukutamu;
USE db_bukutamu;

-- Tabel buku_tamu
CREATE TABLE IF NOT EXISTS buku_tamu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    instansi VARCHAR(100) NOT NULL,
    tujuan TEXT NOT NULL,
    tanggal DATE NOT NULL,
    waktu TIME NOT NULL
);

-- Data contoh (opsional, bisa dihapus)
INSERT INTO buku_tamu (nama, instansi, tujuan, tanggal, waktu) VALUES
('Ahmad Fauzi', 'Dinas Pendidikan Kota Bandung', 'Kunjungan rutin pengawasan sekolah', '2026-06-01', '09:15:00'),
('Siti Nurhaliza', 'PT Maju Bersama', 'Sosialisasi program beasiswa', '2026-06-02', '10:30:00'),
('Budi Santoso', 'Orang Tua Murid', 'Mengambil rapor anak', '2026-06-03', '13:45:00');
