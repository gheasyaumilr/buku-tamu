<?php
include "koneksi.php";

// Pagination settings
$pageSize = 10; // jumlah baris per halaman
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $pageSize;

// Fitur pencarian yang aman dengan prepared statements
$keyword = "";
$params = [];
$where = "";
if (isset($_GET['cari']) && trim($_GET['cari']) !== '') {
    $keyword = trim($_GET['cari']);
    $where = "WHERE nama LIKE ? OR instansi LIKE ?";
}

// Hitung total data untuk badge dan pagination
if ($where === '') {
    $countSql = "SELECT COUNT(*) as cnt FROM buku_tamu";
    $countStmt = mysqli_prepare($koneksi, $countSql);
    mysqli_stmt_execute($countStmt);
    mysqli_stmt_bind_result($countStmt, $totalCount);
    mysqli_stmt_fetch($countStmt);
    mysqli_stmt_close($countStmt);
} else {
    $countSql = "SELECT COUNT(*) as cnt FROM buku_tamu $where";
    $likeParam = '%' . $keyword . '%';
    $countStmt = mysqli_prepare($koneksi, $countSql);
    mysqli_stmt_bind_param($countStmt, 'ss', $likeParam, $likeParam);
    mysqli_stmt_execute($countStmt);
    mysqli_stmt_bind_result($countStmt, $totalCount);
    mysqli_stmt_fetch($countStmt);
    mysqli_stmt_close($countStmt);
}

// Ambil data halaman saat ini
$sql = "SELECT * FROM buku_tamu $where ORDER BY id DESC LIMIT ? OFFSET ?";
$stmt = mysqli_prepare($koneksi, $sql);
if ($where === '') {
    mysqli_stmt_bind_param($stmt, 'ii', $pageSize, $offset);
} else {
    // two strings + two ints -> build dynamic bind
    mysqli_stmt_bind_param($stmt, 'ssii', $likeParam, $likeParam, $pageSize, $offset);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$total = (int)$totalCount;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tamu - Buku Tamu Digital</title>
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

        .hero-section{
            background: linear-gradient(180deg, var(--wood) 0%, var(--wood-dark) 100%);
            border-bottom: 4px solid var(--gold);
        }
        .hero-section h1{
            font-family:'Playfair Display', serif;
            letter-spacing: 1px;
        }
        .hero-section .lead{
            font-family:'Caveat', cursive;
            font-size:1.4rem;
            color: var(--paper);
        }

        /* Buku terbuka (single page, lebar) */
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
            padding: 1.75rem;
        }

        .title-script{
            font-family:'Playfair Display', serif;
            font-weight:700;
            color: var(--wood-dark);
            border-bottom: 2px solid var(--gold);
            display:inline-block;
            padding-bottom:0.15rem;
        }

        /* Search bar */
        .search-quill .form-control{
            background: transparent;
            border: none;
            border-bottom: 1px solid var(--wood);
            border-radius:0;
            font-family:'EB Garamond', Georgia, serif;
            font-size:1.05rem;
            color: var(--ink);
            box-shadow:none;
        }
        .search-quill .form-control:focus{
            border-bottom: 2px solid var(--gold);
            background: rgba(201,162,39,0.06);
        }
        .search-quill .form-control::placeholder{
            color:#A89A7E;
            font-style:italic;
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

        /* Badge total tamu seperti label kertas */
        .badge-ledger{
            font-family:'Playfair Display', serif;
            background: var(--wood);
            color: var(--paper);
            border: 1px solid var(--gold);
            border-radius: 3px;
            padding: 0.5rem 1rem;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
        }

        /* Tabel ala buku besar / ledger */
        .table-ledger{
            font-family:'EB Garamond', Georgia, serif;
            font-size: 1.02rem;
            background: transparent;
        }
        .table-ledger thead th{
            font-family:'Playfair Display', serif;
            background: var(--wood);
            color: var(--paper);
            border-bottom: 2px solid var(--gold);
            font-weight:600;
            letter-spacing:0.5px;
        }
        .table-ledger tbody tr{
            border-bottom: 1px solid var(--paper-line);
        }
        .table-ledger tbody tr:nth-of-type(odd){
            background-color: rgba(107,68,35,0.05);
        }
        .table-ledger tbody tr:hover{
            background-color: rgba(201,162,39,0.12);
        }
        .table-ledger td, .table-ledger th{
            vertical-align: middle;
        }

        .btn-outline-primary, a.btn-outline-primary{
            font-family:'EB Garamond', Georgia, serif;
            color: var(--wood-dark);
            border-color: var(--wood);
            border-radius: 3px;
        }
        .btn-outline-primary:hover{
            background: var(--wood);
            color: var(--paper);
        }
        .btn-outline-danger{
            font-family:'EB Garamond', Georgia, serif;
            border-radius: 3px;
        }

        /* Pagination ala buku */
        .pagination .page-link{
            font-family:'Playfair Display', serif;
            color: var(--wood-dark);
            border-color: var(--paper-line);
            background: var(--paper);
        }
        .pagination .page-item.active .page-link{
            background: var(--wood);
            border-color: var(--wood-dark);
            color: var(--paper);
        }
        .pagination .page-item.disabled .page-link{
            background: var(--paper);
            color: #A89A7E;
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
        <a class="navbar-brand fw-bold" href="index.php">
            <i class="bi bi-journal-bookmark"></i> Buku Tamu Digital
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Form Tamu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="daftar_tamu.php">Daftar Tamu</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<div class="hero-section text-center text-white py-5">
    <div class="container">
        <h1 class="fw-bold">Daftar Tamu</h1>
        <p class="lead mb-0">~ Catatan setiap jejak yang singgah ~</p>
    </div>
</div>

<div class="container my-4 flex-grow-1">

    <div class="book">
        <div class="book-page">

            <!-- Search Bar -->
            <div class="row mb-4">
                <div class="col-md-6 mx-auto">
                    <form action="daftar_tamu.php" method="GET" class="d-flex search-quill">
                        <input type="text" name="cari" class="form-control me-2"
                               placeholder="Cari berdasarkan nama atau instansi..."
                               value="<?php echo htmlspecialchars($keyword); ?>">
                        <button type="submit" class="btn btn-quill px-4"><i class="bi bi-search"></i> Cari</button>
                        <?php if (!empty($keyword)) { ?>
                            <a href="daftar_tamu.php" class="btn btn-outline-stamp px-3 ms-2">Reset</a>
                        <?php } ?>
                    </form>
                </div>
            </div>

            <!-- Info jumlah data -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge-ledger">
                    <i class="bi bi-bookmark-fill"></i> Total Tamu: <?php echo $total; ?>
                </span>
                <a href="index.php" class="btn btn-quill px-4">
                    <i class="bi bi-feather"></i> Tambah Tamu
                </a>
            </div>

            <!-- Tabel Daftar Tamu -->
            <div class="table-responsive">
                <table class="table table-ledger align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Lengkap</th>
                            <th>Instansi</th>
                            <th>Tujuan Kedatangan</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($total > 0) {
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $no++ . "</td>";
                                echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['instansi']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['tujuan']) . "</td>";
                                echo "<td>" . date("d-m-Y", strtotime($row['tanggal'])) . "</td>";
                                echo "<td>" . date("H:i:s", strtotime($row['waktu'])) . "</td>";
                                // Actions: Edit & Delete
                                $id = (int)$row['id'];
                                echo "<td>";
                                echo "<a href='edit_tamu.php?id=$id' class='btn btn-sm btn-outline-primary me-2'>Edit</a>";
                                echo "<form action='delete_tamu.php' method='POST' class='d-inline' onsubmit=\"return confirm('Hapus data ini?');\">";
                                echo "<input type='hidden' name='id' value='$id'>";
                                echo "<button type='submit' class='btn btn-sm btn-outline-danger'>Hapus</button>";
                                echo "</form>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center py-4' style='font-family:\"Caveat\",cursive;font-size:1.3rem;color:var(--wood);'>
                                    ~ Belum ada jejak tamu yang tercatat ~
                                  </td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

<?php
// Flash messages (msg)
if (isset($_GET['msg'])) {
    $m = $_GET['msg'];
    if ($m === 'deleted') {
        echo '<div class="container"><div class="alert alert-success mt-3">Data berhasil dihapus.</div></div>';
    } elseif ($m === 'updated') {
        echo '<div class="container"><div class="alert alert-success mt-3">Data berhasil diperbarui.</div></div>';
    } elseif ($m === 'error') {
        echo '<div class="container"><div class="alert alert-danger mt-3">Terjadi kesalahan.</div></div>';
    }
}


    // Pagination links
    $totalPages = (int) ceil($total / $pageSize);
    if ($totalPages > 1) {
        $baseUrl = 'daftar_tamu.php';
        $queryParams = [];
        if ($keyword !== '') $queryParams['cari'] = $keyword;
        echo '<div class="container my-3">';
        echo '<nav aria-label="Page navigation">';
        echo '<ul class="pagination justify-content-center">';

        // Previous
        $prevPage = max(1, $page - 1);
        $qp = array_merge($queryParams, ['page' => $prevPage]);
        echo '<li class="page-item ' . ($page == 1 ? 'disabled' : '') . '">';
        echo '<a class="page-link" href="' . $baseUrl . '?' . http_build_query($qp) . '" aria-label="Previous">&laquo;</a>';
        echo '</li>';

        // Pages (simple range)
        for ($p = 1; $p <= $totalPages; $p++) {
            $qp = array_merge($queryParams, ['page' => $p]);
            echo '<li class="page-item ' . ($p == $page ? 'active' : '') . '">';
            echo '<a class="page-link" href="' . $baseUrl . '?' . http_build_query($qp) . '">' . $p . '</a>';
            echo '</li>';
        }

        // Next
        $nextPage = min($totalPages, $page + 1);
        $qp = array_merge($queryParams, ['page' => $nextPage]);
        echo '<li class="page-item ' . ($page == $totalPages ? 'disabled' : '') . '">';
        echo '<a class="page-link" href="' . $baseUrl . '?' . http_build_query($qp) . '" aria-label="Next">&raquo;</a>';
        echo '</li>';

        echo '</ul>';
        echo '</nav>';
        echo '</div>';
    }
    ?>

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