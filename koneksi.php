<?php
// Koneksi server
$host     = "localhost";
$username = "root";   // user database
$password = "";            // password database
$database = "a122407296_db_barangg"; // nama database


function clean_input($data) {
    global $koneksi;
    $data = trim($data);
    $data = mysqli_real_escape_string($koneksi, $data);
    $data = htmlspecialchars($data);
    return $data;
}

// Membuat koneksi
$koneksi = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}else{
    // die("aman syg");
}
?>
