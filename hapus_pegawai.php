<?php
include 'koneksi.php';

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $query = "DELETE FROM pegawai WHERE id_pegawai = $id";

    if (mysqli_query($koneksi, query: $query)) {
        $_SESSION['pesan'] = "Barang berhasil dihapus!";
        $_SESSION['tipe'] = "success";
    } else {
        $_SESSION['pesan'] = "Gagal menghapus barang: " . mysqli_error($koneksi);
        $_SESSION['tipe'] = "error";
    }
}

header("Location: index.php?page=data_pegawai");
exit();
