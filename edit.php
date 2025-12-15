<?php
    include 'koneksi.php';

    $page_title = "Edit Barang";

    // Ambil ID dari URL
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Ambil data barang saat ini
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
        
        // Cek kode unik (kecuali untuk barang ini sendiri)
        $check_query = "SELECT id FROM barang WHERE kode_barang = '$kode_barang' AND id != $id";
        $check_result = mysqli_query($koneksi, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            $_SESSION['pesan'] = "Kode barang sudah digunakan!";
            $_SESSION['tipe'] = "error";
        } else {
            
            // --- LOGIKA UPLOAD FOTO ---
            $foto_nama = $_FILES['foto']['name'];
            $foto_tmp = $_FILES['foto']['tmp_name'];
            
            // Jika ada foto baru yang diupload
            if (!empty($foto_nama)) {
                $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg', 'gif');
                $x = explode('.', $foto_nama);
                $ekstensi = strtolower(end($x));
                
                // Cek ekstensi file
                if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
                    // Buat nama file baru yang unik (menggunakan time() agar lebih unik)
                    $nama_file_baru = time() . '_' . rand(100, 999) . '.' . $ekstensi;
                    
                    // Upload file ke folder uploads
                    move_uploaded_file($foto_tmp, 'uploads/' . $nama_file_baru);
                    
                    // Hapus foto lama jika ada dan file-nya benar-benar ada di server
                    if (!empty($barang['foto']) && file_exists('uploads/' . $barang['foto'])) {
                        unlink('uploads/' . $barang['foto']);
                    }

                    // Query UPDATE dengan mengganti foto
                    $query = "UPDATE barang SET 
                              kode_barang = '$kode_barang',
                              nama_barang = '$nama_barang',
                              kategori = '$kategori',
                              stok = '$stok',
                              harga = '$harga',
                              deskripsi = '$deskripsi',
                              status = '$status',
                              foto = '$nama_file_baru' 
                              WHERE id = $id";
                } else {
                    $_SESSION['pesan'] = "Ekstensi gambar tidak diperbolehkan (hanya jpg, jpeg, png, gif)!";
                    $_SESSION['tipe'] = "error";
                    header("Location: edit.php?id=$id");
                    exit();
                }
            } else {
                // Jika TIDAK ada foto baru (foto tidak berubah)
                $query = "UPDATE barang SET 
                          kode_barang = '$kode_barang',
                          nama_barang = '$nama_barang',
                          kategori = '$kategori',
                          stok = '$stok',
                          harga = '$harga',
                          deskripsi = '$deskripsi',
                          status = '$status'
                          WHERE id = $id";
            }
            // --- AKHIR LOGIKA UPLOAD ---

            if (mysqli_query($koneksi, $query)) {
                $_SESSION['pesan'] = "Data barang dan gambar berhasil diperbarui!";
                $_SESSION['tipe'] = "success";
                // PERUBAHAN 1: Redirect kembali ke halaman edit ini agar melihat perubahan
header("Location: index.php?page=data_barang");                exit();
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
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                    </a>
                </div>

                <div class="card-body">
                    <?php if (isset($_SESSION['pesan'])): ?>
                        <div class="alert alert-<?php echo $_SESSION['tipe']; ?>" role="alert" style="margin-bottom: 20px; padding: 10px; border-radius: 5px; <?php echo $_SESSION['tipe'] == 'success' ? 'background-color: #d4edda; color: #155724;' : 'background-color: #f8d7da; color: #721c24;'; ?>">
                            <?php echo $_SESSION['pesan']; ?>
                        </div>
                        <?php unset($_SESSION['pesan']); unset($_SESSION['tipe']); ?>
                    <?php endif; ?>

                    <form method="POST" class="form-vertical" enctype="multipart/form-data">
                        
                        <div class="form-group">
                            <label>Foto Produk (Saat Ini)</label>
                            <div style="margin-bottom: 10px;">
                                <?php 
                                    $fotoPath = !empty($barang['foto']) ? 'uploads/' . $barang['foto'] : 'https://via.placeholder.com/150?text=No+Image';
                                    // PERUBAHAN 2: Tambahkan parameter waktu agar browser tidak menggunakan cache (memaksa ambil gambar baru)
                                    $cacheBuster = !empty($barang['foto']) ? '?t=' . time() : '';
                                ?>
                                <img src="<?php echo $fotoPath . $cacheBuster; ?>" alt="Preview Foto" 
                                     style="width: 150px; height: 150px; object-fit: cover; border: 1px solid #ddd; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                            </div>
                            <label for="foto" class="btn btn-outline-primary btn-sm" style="cursor: pointer; margin-top: 5px;">
                                <i class="fas fa-camera"></i> Pilih Foto Baru
                            </label>
                            <input type="file" id="foto" name="foto" class="form-control" accept="image/*" style="display: none;" onchange="previewImage(this);">
                            <span class="text-muted" style="font-size: 0.85em; margin-left: 10px;">*Biarkan kosong jika tidak ingin mengubah gambar.</span>
                             <script>
                                function previewImage(input) {
                                    if (input.files && input.files[0]) {
                                        var reader = new FileReader();
                                        reader.onload = function (e) {
                                            // Ganti sumber gambar preview dengan gambar yang dipilih
                                            document.querySelector('img[alt="Preview Foto"]').src = e.target.result;
                                        }
                                        reader.readAsDataURL(input.files[0]);
                                    }
                                }
                            </script>
                        </div>
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
                                    <?php 
                                    $kategori_list = ['Elektronik', 'Pakaian', 'Makanan', 'Minuman', 'Alat Tulis', 'Olahraga','Alustista', 'Alat Tempur', 'Alat Tempur 😘','Lainnya'];
                                    foreach($kategori_list as $kat) {
                                        $selected = ($barang['kategori'] == $kat) ? 'selected' : '';
                                        echo "<option value='$kat' $selected>$kat</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="stok">
                                    <i class="fas fa-cubes"></i> Stok *
                                </label>
                                <input type="number" id="stok" name="stok" value="<?php echo $barang['stok']; ?>"
                                    min="0" required>
                            </div>

                            <div class="form-group">
                                <label for="harga">
                                    <i class="fas fa-money-bill-wave"></i> Harga (Rp) *
                                </label>
                                <input type="number" id="harga" name="harga" value="<?php echo $barang['harga']; ?>"
                                    min="0" required>
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
                            <a href="edit.php?id=<?php echo $id; ?>" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Reset Form
                            </a>
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