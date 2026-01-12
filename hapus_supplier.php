<?php
// Mulai session jika belum dimulai (opsional, tergantung apakah sudah ada di koneksi.php)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'koneksi.php';

// Ambil ID dari URL dan pastikan nilainya adalah angka (integer)
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // 1. Query Hapus Data Supplier
    $query = "DELETE FROM suppliers WHERE id_supplier = $id";

    if (mysqli_query($koneksi, $query)) {
        // Jika berhasil
        $_SESSION['pesan'] = "Data supplier berhasil dihapus!";
        $_SESSION['tipe'] = "success";
    } else {
        // Jika gagal (misal: ID sedang digunakan sebagai relasi di tabel lain)
        $_SESSION['pesan'] = "Gagal menghapus supplier: " . mysqli_error($koneksi);
        $_SESSION['tipe'] = "error";
    }
} else {
    $_SESSION['pesan'] = "ID tidak valid!";
    $_SESSION['tipe'] = "error";
}

// Redirect kembali ke halaman utama data supplier
header("Location: index.php?page=data_supplier");
exit();
