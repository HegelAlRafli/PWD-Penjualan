<?php
    include 'koneksi.php';

    $page_title = "Detail Barang";

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
?>

<?php include 'includes/header.php'; ?>

<div class="content-wrapper">
    <?php include 'includes/menu.php'; ?>

    <main class="main-content">
        <div class="page-header">
            <h2>Detail Barang</h2>
            <div class="breadcrumb">
                <a href="index.php">Home</a>
                <i class="fas fa-chevron-right"></i>
                <a href="index.php?page=data_barang">Data Barang</a>
                <i class="fas fa-chevron-right"></i>
                <span>Detail Barang</span>
            </div>
        </div>

        <div class="content">
            <div class="card">
                <div class="card-body">
                    <form class="form-vertical">
                        
                        <div class="form-group" style="text-align: center; margin-bottom: 30px;">
                            <label style="display:block; font-weight:bold;">Foto Barang</label>
                            <?php 
                                $fotoPath = !empty($barang['foto']) ? 'uploads/' . $barang['foto'] : '';
                                if (!file_exists($fotoPath) || empty($barang['foto'])) {
                                     // Gunakan placeholder jika foto tidak ada
                                     $src = 'https://via.placeholder.com/200?text=No+Image';
                                } else {
                                     $src = $fotoPath;
                                }
                            ?>
                            <img src="<?php echo $src; ?>" alt="Foto Barang" 
                                 style="max-width: 200px; height: auto; border: 1px solid #ddd; padding: 5px; border-radius: 5px;">
                        </div>

                        <div class="form-group">
                            <label for="id">ID Database</label>
                            <input type="text" class="form-control" disabled value="<?php echo $barang['id']; ?>">
                        </div>

                        <div class="form-row">
                            <div class="form-group" style="flex: 1; margin-right: 10px;">
                                <label><i class="fas fa-barcode"></i> Kode Barang</label>
                                <input type="text" disabled value="<?php echo htmlspecialchars($barang['kode_barang']); ?>">
                            </div>

                            <div class="form-group" style="flex: 1;">
                                <label><i class="fas fa-tag"></i> Kategori</label>
                                <input type="text" disabled value="<?php echo htmlspecialchars($barang['kategori']); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-box"></i> Nama Barang</label>
                            <input type="text" disabled value="<?php echo htmlspecialchars($barang['nama_barang']); ?>">
                        </div>

                        <div class="form-row">
                            <div class="form-group" style="flex: 1; margin-right: 10px;">
                                <label><i class="fas fa-cubes"></i> Stok</label>
                                <input type="number" disabled value="<?php echo $barang['stok']; ?>">
                            </div>

                            <div class="form-group" style="flex: 1;">
                                <label><i class="fas fa-money-bill-wave"></i> Harga (Rp)</label>
                                <input type="text" disabled value="Rp <?php echo number_format($barang['harga'], 0, ',', '.'); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-info-circle"></i> Status</label>
                            <input type="text" disabled 
                                value="<?php echo ucfirst($barang['status']); ?>"
                                style="font-weight: bold; color: <?php echo ($barang['status'] == 'aktif') ? 'green' : 'red'; ?>;">
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-align-left"></i> Deskripsi</label>
                            <textarea rows="4" disabled><?php echo htmlspecialchars($barang['deskripsi']); ?></textarea>
                        </div>

                        <div class="card-header" style="background-color: #f8f9fa; border-top: 1px solid #ddd; padding: 15px;">
                            <a href="index.php?page=data_barang" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>

                            <a href="edit.php?id=<?php echo $barang['id']; ?>" class="btn btn-primary" style="margin-left: 10px;">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include 'includes/footer.php'; ?>