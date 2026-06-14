<?php
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: daftar_tamu.php');
    exit;
}

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    header('Location: daftar_tamu.php');
    exit;
}

$id = (int)$_POST['id'];

$stmt = mysqli_prepare($koneksi, "DELETE FROM buku_tamu WHERE id = ?");
if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'i', $id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header('Location: daftar_tamu.php?msg=deleted');
        exit;
    } else {
        mysqli_stmt_close($stmt);
        header('Location: daftar_tamu.php?msg=error');
        exit;
    }
} else {
    header('Location: daftar_tamu.php?msg=error');
    exit;
}
?>
