<?php
include "koneksi.php";

$success = false;
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: daftar_tamu.php');
    exit;
}
$id = (int)$_GET['id'];

// Ambil data tamu
$stmt = mysqli_prepare($koneksi, "SELECT id, nama, instansi, tujuan FROM buku_tamu WHERE id = ?");
if (!$stmt) {
    die('Query error: ' . mysqli_error($koneksi));
}
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $rid, $rnama, $rinstansi, $rtujuan);
if (!mysqli_stmt_fetch($stmt)) {
    mysqli_stmt_close($stmt);
    header('Location: daftar_tamu.php');
    exit;
}
mysqli_stmt_close($stmt);

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
    $instansi = isset($_POST['instansi']) ? trim($_POST['instansi']) : '';
    $tujuan = isset($_POST['tujuan']) ? trim($_POST['tujuan']) : '';

    if ($nama === '' || $instansi === '' || $tujuan === '') {
        $error = 'Semua field harus diisi.';
    } else {
        $upd = mysqli_prepare($koneksi, "UPDATE buku_tamu SET nama = ?, instansi = ?, tujuan = ? WHERE id = ?");
        if ($upd) {
            mysqli_stmt_bind_param($upd, 'sssi', $nama, $instansi, $tujuan, $id);
            if (mysqli_stmt_execute($upd)) {
                mysqli_stmt_close($upd);
                // Redirect dengan pesan sukses
                header('Location: daftar_tamu.php?msg=updated');
                exit;
            } else {
                $error = 'Gagal menyimpan perubahan: ' . mysqli_stmt_error($upd);
                mysqli_stmt_close($upd);
            }
        } else {
            $error = 'Gagal menyiapkan query update: ' . mysqli_error($koneksi);
        }
    }
}

// Pastikan variabel non-null untuk HTML
$rnama = isset($rnama) ? $rnama : '';
$rinstansi = isset($rinstansi) ? $rinstansi : '';
$rtujuan = isset($rtujuan) ? $rtujuan : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tamu - Buku Tamu Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Caveat:wght@600;700&family=EB+Garamond:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        :root{
            --paper:#F4ECD8;
            --paper-line:#D8C9A8;
            --ink:#3A2E1E;
            --wood:#6B4423;
            --wood-dark:#4A2E18;
            --gold:#C9A227;
        }
        body{
            background: var(--wood-dark) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 80 80'%3E%3Crect width='80' height='80' fill='%234A2E18'/%3E%3Cpath d='M0 0L80 80M80 0L0 80' stroke='%236B4423' stroke-width='1' opacity='0.3'/%3E%3C/svg%3E");
            font-family:'EB Garamond', Georgia, serif;
            color: var(--ink);
            min-height:100vh;
            display:flex;
            flex-direction:column;
        }
        .navbar{
            background: var(--wood) !important;
            border-bottom: 4px solid var(--gold);
        }
        .navbar-brand, .nav-link{
            font-family:'Playfair Display', serif !important;
            letter-spacing: 0.5px;
        }
        .navbar-brand{
            font-size: 1.4rem;
        }

        /* Buku terbuka */
        .book{
            background: var(--paper);
            border-radius: 6px;
            box-shadow:
                0 0 0 1px rgba(0,0,0,0.05),
                0 25px 50px -10px rgba(0,0,0,0.6),
                inset 0 0 60px rgba(107,68,35,0.15);
            position: relative;
        }
        .book-page{
            position:relative;
            padding: 2rem 1.75rem;
            background-image: repeating-linear-gradient(
                to bottom,
                transparent 0,
                transparent 1.85rem,
                var(--paper-line) 1.85rem,
                var(--paper-line) calc(1.85rem + 1px)
            );
            background-position: 0 4.2rem;
        }

        .title-script{
            font-family:'Playfair Display', serif;
            font-weight:700;
            color: var(--wood-dark);
            border-bottom: 2px solid var(--gold);
            display:inline-block;
            padding-bottom:0.15rem;
        }

        .form-label{
            font-family:'Playfair Display', serif;
            color: var(--wood-dark);
            font-size:0.95rem;
        }
        .form-control, .form-control:focus{
            background: transparent;
            border: none;
            border-bottom: 1px solid var(--wood);
            border-radius:0;
            font-family:'EB Garamond', Georgia, serif;
            font-size:1.05rem;
            color: var(--ink);
            padding-left:0.25rem;
            box-shadow:none;
        }
        .form-control:focus{
            border-bottom: 2px solid var(--gold);
            background: rgba(201,162,39,0.06);
        }
        textarea.form-control{
            resize:none;
        }
        .form-control::placeholder{
            color: #A89A7E;
            font-style: italic;
        }

        .btn-quill{
            font-family:'Playfair Display', serif;
            background: var(--wood);
            border: 1px solid var(--wood-dark);
            color: var(--paper);
            letter-spacing: 0.5px;
            border-radius: 3px;
        }
        .btn-quill:hover{
            background: var(--wood-dark);
            color: var(--gold);
        }
        .btn-outline-stamp{
            font-family:'Playfair Display', serif;
            border: 2px solid var(--wood);
            color: var(--wood-dark);
            border-radius: 3px;
            background: transparent;
        }
        .btn-outline-stamp:hover{
            background: var(--wood);
            color: var(--paper);
        }

        /* Stempel / cap */
        .stamp{
            font-family:'Caveat', cursive;
            font-weight:700;
            color: #8B1E1E;
            border: 3px solid #8B1E1E;
            border-radius: 50%;
            width: 90px;
            height: 90px;
            display:flex;
            align-items:center;
            justify-content:center;
            text-align:center;
            font-size:0.95rem;
            line-height:1.1;
            transform: rotate(-12deg);
            opacity:0.75;
            position:absolute;
            top: 1.25rem;
            right: 1.25rem;
            pointer-events:none;
        }

        .alert{
            border-radius:3px;
            font-family:'EB Garamond', Georgia, serif;
        }

        footer{
            background: var(--wood-dark) !important;
            border-top: 4px solid var(--gold);
            font-family:'Caveat', cursive;
            font-size:1.05rem;
        }
        footer a{
            color: var(--gold);
            text-decoration:none;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-journal-bookmark"></i> Buku Tamu Digital</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="index.php">Form Tamu</a></li>
                <li class="nav-item"><a class="nav-link" href="daftar_tamu.php">Daftar Tamu</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container my-4 flex-grow-1">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <?php if (isset($error)) { ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-sm py-2" role="alert">
                    <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php } ?>

            <div class="book">
                <div class="book-page">
                    <div class="stamp">Ubah<br>Catatan</div>

                    <h4 class="title-script mb-3 fs-5">Edit Tamu</h4>

                    <form method="POST">
                        <div class="mb-2">
                            <label class="form-label fw-semibold mb-0">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control form-control-sm"
                                   value="<?php echo htmlspecialchars($rnama); ?>"
                                   placeholder="Tulis nama lengkap" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-semibold mb-0">Instansi / Asal</label>
                            <input type="text" name="instansi" class="form-control form-control-sm"
                                   value="<?php echo htmlspecialchars($rinstansi); ?>"
                                   placeholder="Asal instansi/sekolah" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-0">Tujuan Kedatangan</label>
                            <textarea name="tujuan" class="form-control form-control-sm" rows="2"
                                      placeholder="Apa tujuan kunjungan Anda?" required><?php echo htmlspecialchars($rtujuan); ?></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="daftar_tamu.php" class="btn btn-outline-stamp btn-sm">
                                <i class="bi bi-x-lg"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-quill btn-sm">
                                <i class="bi bi-feather"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Footer -->
<footer class="text-white text-center py-3 mt-auto">
    <div class="container">
        <small>&copy; 2026 Buku Tamu Digital Sekolah - UTS Pemrograman Web II</small><br>
        <small>Dirancang dengan &hearts; oleh <strong>Ghea Syaumil Rakhmah</strong></small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>