<?php
include 'koneksi.php';

$page_title = "Tambah Barang";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_barang = clean_input($_POST['kode_barang']);
    $nama_barang = clean_input($_POST['nama_barang']);
    $kategori    = clean_input($_POST['kategori']);
    $stok        = clean_input($_POST['stok']);
    $harga       = clean_input($_POST['harga']);
    $deskripsi   = clean_input($_POST['deskripsi']);
    $status = isset($_POST['status']) ? clean_input($_POST['status']) : 'aktif';


    // --- LOGIKA UPLOAD FOTO (YANG DIPERBAIKI) ---
    $foto = 'default_barang.jpg'; // Nama default jika tidak ada upload

    // Cek apakah ada file foto yang dikirim dan tidak error
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $allowed    = array('jpg', 'jpeg', 'png', 'gif');
        $filename   = $_FILES['foto']['name'];
        $ext        = pathinfo($filename, PATHINFO_EXTENSION);

        // Validasi Ekstensi
        if (in_array(strtolower($ext), $allowed)) {
            // Tentukan folder tujuan (Naik 2 level dari pages/data_barang/ ke root/uploads/)
            $target_dir = "uploads/";

            // Cek apakah folder uploads ada, jika tidak buat dulu
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            // Buat nama baru yang unik
            $new_filename = "barang_" . time() . "." . $ext;
            $target_file  = $target_dir . $new_filename;

            // PINDAHKAN FILE
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
                $foto = $new_filename; // Berhasil! Update variabel $foto
            } else {
                // Jika gagal pindah (misal permission error), kita biarkan default tapi beri alert
                echo "<script>alert('Gagal memindahkan file ke folder uploads!');</script>";
            }
        } else {
            echo "<script>alert('Format file harus JPG, PNG, atau GIF');</script>";
        }
    }
    // --- AKHIR LOGIKA UPLOAD ---

    // Generate kode otomatis jika kosong
    if (empty($kode_barang)) {
        $prefix = "BRG";
        $query = "SELECT MAX(SUBSTRING(kode_barang, 4)) as max_code FROM barang WHERE kode_barang LIKE '$prefix%'";
        $result = mysqli_query($koneksi, $query);
        $row = mysqli_fetch_assoc($result);
        $next_num = ($row['max_code'] ?? 0) + 1;
        $kode_barang = $prefix . str_pad($next_num, 3, '0', STR_PAD_LEFT);
    }

    // Cek kode sudah ada
    $check_query = "SELECT id FROM barang WHERE kode_barang = '$kode_barang'";
    $check_result = mysqli_query($koneksi, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $_SESSION['pesan'] = "Kode barang sudah digunakan!";
        $_SESSION['tipe'] = "error";
    } else {
        // Query Insert (Pastikan variabel $foto masuk ke sini)
        $query = "INSERT INTO barang (kode_barang, foto, nama_barang, kategori, stok, harga, deskripsi, status) 
VALUES ('$kode_barang', '$foto', '$nama_barang', '$kategori', '$stok', '$harga', '$deskripsi', '$status')
";

        if (mysqli_query($koneksi, $query)) {
            $_SESSION['pesan'] = "Barang berhasil ditambahkan!";
            $_SESSION['tipe'] = "success";
            header("Location: index.php?page=data_barang");
            exit();
        } else {
            $_SESSION['pesan'] = "Gagal menambahkan barang: " . mysqli_error($koneksi);
            $_SESSION['tipe'] = "error";
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="content-wrapper">
    <?php include 'includes/menu.php'; ?>

    <main class="main-content">
        <div class="page-header">
            <h2>Tambah Barang Baru</h2>
            <div class="breadcrumb">
                <a href="index.php">Home</a>
                <i class="fas fa-chevron-right"></i>
                <a href="index.php?page=data_barang">Data Barang</a>
                <i class="fas fa-chevron-right"></i>
                <span>Tambah Barang</span>
            </div>
        </div>

        <div class="content">
            <div class="card">
                <div class="card-header">
                    <h3>Form Tambah Barang</h3>
                    <a href="index.php?page=data_barang" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    <form method="POST" class="form-vertical" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="kode_barang">
                                    <i class="fas fa-barcode"></i> Kode Barang
                                </label>
                                <input type="text" id="kode_barang" name="kode_barang"
                                    placeholder="Kosongkan untuk generate otomatis">
                                <small class="form-hint">Contoh: BRG001</small>
                            </div>

                            <div class="form-group">
                                <label for="nama_barang">
                                    <i class="fas fa-box"></i> Nama Barang *
                                </label>
                                <input type="text" id="nama_barang" name="nama_barang" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="kategori">
                                    <i class="fas fa-tags"></i> Kategori *
                                </label>
                                <select id="kategori" name="kategori" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="Elektronik">Elektronik</option>
                                    <option value="Pakaian">Pakaian</option>
                                    <option value="Makanan">Makanan</option>
                                    <option value="Minuman">Minuman</option>
                                    <option value="Alat Tulis">Alat Tulis</option>
                                    <option value="Olahraga">Olahraga</option>
                                    <option value="Alustista">Alustista</option>
                                    <option value="Alat Tempur">Alat Tempur 😘</option>

                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="stok">
                                    <i class="fas fa-cubes"></i> Stok *
                                </label>
                                <input type="number" id="stok" name="stok" min="0" required>
                            </div>

                            <div class="form-group">
                                <label for="harga">
                                    <i class="fas fa-money-bill-wave"></i> Harga (Rp) *
                                </label>
                                <input type="number" id="harga" name="harga" min="0" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status">
                                <i class="fas fa-toggle-on"></i> Status Barang *
                            </label>
                            <select id="status" name="status" required>
                                <option value="aktif" selected>Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                            <small class="form-hint">Barang nonaktif tidak akan tampil di penjualan</small>
                        </div>

                        <div class="form-group">
                            <label for="foto"><i class="fas fa-image"></i> Foto Barang</label>
                            <input type="file" id="foto" name="foto" class="form-control-file" accept="image/*">
                            <small class="form-hint">Format: JPG, PNG, GIF. Max: 2MB</small>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">
                                <i class="fas fa-align-left"></i> Deskripsi
                            </label>
                            <textarea id="deskripsi" name="deskripsi" rows="4"
                                placeholder="Deskripsi barang..."></textarea>
                        </div>

                        <div class="form-actions">
                            <button type="reset" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Barang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include 'includes/footer.php'; ?>