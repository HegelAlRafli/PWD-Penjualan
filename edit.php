<?php
include 'koneksi.php';

$page_title = "Edit Barang";

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data barang
$query = "SELECT * FROM barang WHERE id = $id";
$result = mysqli_query($koneksi, $query);
$barang = mysqli_fetch_assoc($result);

if (!$barang) {
    $_SESSION['pesan'] = "Barang tidak ditemukan!";
    $_SESSION['tipe'] = "error";
    header("Location: index.php?page=data_barang");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_barang = clean_input($_POST['kode_barang']);
    $nama_barang = clean_input($_POST['nama_barang']);
    $kategori = clean_input($_POST['kategori']);
    $stok = clean_input($_POST['stok']);
    $harga = clean_input($_POST['harga']);
    $deskripsi = clean_input($_POST['deskripsi']);
    $status = clean_input($_POST['status']);
    
    // Cek kode unik (kecuali untuk barang ini)
    $check_query = "SELECT id FROM barang WHERE kode_barang = '$kode_barang' AND id != $id";
    $check_result = mysqli_query($koneksi, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        $_SESSION['pesan'] = "Kode barang sudah digunakan!";
        $_SESSION['tipe'] = "error";
    } else {
        $query = "UPDATE barang SET 
                  kode_barang = '$kode_barang',
                  nama_barang = '$nama_barang',
                  kategori = '$kategori',
                  stok = '$stok',
                  harga = '$harga',
                  deskripsi = '$deskripsi',
                  status = '$status'
                  WHERE id = $id";
        
        if (mysqli_query($koneksi, $query)) {
            $_SESSION['pesan'] = "Barang berhasil diperbarui!";
            $_SESSION['tipe'] = "success";
            header("Location: index.php?page=data_barang");
            exit();
        } else {
            $_SESSION['pesan'] = "Gagal memperbarui barang: " . mysqli_error($koneksi);
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
            <h2>Edit Barang</h2>
            <div class="breadcrumb">
                <a href="index.php">Home</a>
                <i class="fas fa-chevron-right"></i>
                <a href="index.php?page=data_barang">Data Barang</a>
                <i class="fas fa-chevron-right"></i>
                <span>Edit Barang</span>
            </div>
        </div>
        
        <div class="content">
            <div class="card">
                <div class="card-header">
                    <h3>Edit Data Barang</h3>
                    <a href="index.php?page=data_barang" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                
                <div class="card-body">
                    <form method="POST" class="form-vertical">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="kode_barang">
                                    <i class="fas fa-barcode"></i> Kode Barang *
                                </label>
                                <input type="text" id="kode_barang" name="kode_barang" 
                                       value="<?php echo htmlspecialchars($barang['kode_barang']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="nama_barang">
                                    <i class="fas fa-box"></i> Nama Barang *
                                </label>
                                <input type="text" id="nama_barang" name="nama_barang" 
                                       value="<?php echo htmlspecialchars($barang['nama_barang']); ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="kategori">
                                    <i class="fas fa-tags"></i> Kategori *
                                </label>
                                <select id="kategori" name="kategori" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="Elektronik" <?php echo $barang['kategori'] == 'Elektronik' ? 'selected' : ''; ?>>Elektronik</option>
                                    <option value="Pakaian" <?php echo $barang['kategori'] == 'Pakaian' ? 'selected' : ''; ?>>Pakaian</option>
                                    <option value="Makanan" <?php echo $barang['kategori'] == 'Makanan' ? 'selected' : ''; ?>>Makanan</option>
                                    <option value="Minuman" <?php echo $barang['kategori'] == 'Minuman' ? 'selected' : ''; ?>>Minuman</option>
                                    <option value="Alat Tulis" <?php echo $barang['kategori'] == 'Alat Tulis' ? 'selected' : ''; ?>>Alat Tulis</option>
                                    <option value="Olahraga" <?php echo $barang['kategori'] == 'Olahraga' ? 'selected' : ''; ?>>Olahraga</option>
                                    <option value="Lainnya" <?php echo $barang['kategori'] == 'Lainnya' ? 'selected' : ''; ?>>Lainnya</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="stok">
                                    <i class="fas fa-cubes"></i> Stok *
                                </label>
                                <input type="number" id="stok" name="stok" 
                                       value="<?php echo $barang['stok']; ?>" min="0" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="harga">
                                    <i class="fas fa-money-bill-wave"></i> Harga (Rp) *
                                </label>
                                <input type="number" id="harga" name="harga" 
                                       value="<?php echo $barang['harga']; ?>" min="0" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="status">
                                    <i class="fas fa-toggle-on"></i> Status
                                </label>
                                <select id="status" name="status" required>
                                    <option value="aktif" <?php echo $barang['status'] == 'aktif' ? 'selected' : ''; ?>>Aktif</option>
                                    <option value="nonaktif" <?php echo $barang['status'] == 'nonaktif' ? 'selected' : ''; ?>>Nonaktif</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="deskripsi">
                                <i class="fas fa-align-left"></i> Deskripsi
                            </label>
                            <textarea id="deskripsi" name="deskripsi" rows="4"><?php echo htmlspecialchars($barang['deskripsi']); ?></textarea>
                        </div>
                        
                        <div class="form-actions">
                            <button type="reset" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include 'includes/footer.php'; ?>
