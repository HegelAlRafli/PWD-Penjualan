<?php
include 'koneksi.php';

$page_title = "Edit Supplier";

// 1. Ambil ID dari URL
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
} else {
    header("Location: index.php?page=data_supplier");
    exit();
}

// 2. Ambil data supplier berdasarkan id_supplier
$query = "SELECT * FROM suppliers WHERE id_supplier = '$id'";
$result = mysqli_query($koneksi, $query);
$supplier = mysqli_fetch_assoc($result);

if (!$supplier) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php?page=data_supplier';</script>";
    exit();
}

// 3. Proses Update Data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fungsi clean_input (pastikan fungsi ini ada di koneksi.php atau definisikan di sini)
    function clean($data)
    {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    $nama_perusahaan = clean($_POST['nama_perusahaan']);
    $nama_kontak     = clean($_POST['nama_kontak']);
    $email           = clean($_POST['email']);
    $telepon          = clean($_POST['telepon']);
    $alamat          = clean($_POST['alamat']);
    $kota            = clean($_POST['kota']);
    $provinsi        = clean($_POST['provinsi']);
    $kode_pos        = clean($_POST['kode_pos']);
    $jenis_produk    = clean($_POST['jenis_produk']);
    $model_bisnis    = clean($_POST['model_bisnis']);
    $keterangan      = clean($_POST['keterangan']);

    $query_update = "UPDATE suppliers SET 
        nama_perusahaan = '$nama_perusahaan',
        nama_kontak = '$nama_kontak',
        email = '$email',
        telepon = '$telepon',
        alamat = '$alamat',
        kota = '$kota',
        provinsi = '$provinsi',
        kode_pos = '$kode_pos',
        jenis_produk = '$jenis_produk',
        model_bisnis = '$model_bisnis',
        keterangan = '$keterangan'
        WHERE id_supplier = '$id'";

    if (mysqli_query($koneksi, $query_update)) {
        echo "<script>alert('Data supplier berhasil diperbarui!'); window.location='index.php?page=data_supplier';</script>";
    } else {
        $error = "Gagal memperbarui data: " . mysqli_error($koneksi);
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="content-wrapper">
    <?php include 'includes/menu.php'; ?>

    <main class="main-content">
        <div class="page-header">
            <h2>Edit Supplier</h2>
            <div class="breadcrumb">
                <a href="index.php">Home</a>
                <i class="fas fa-chevron-right"></i>
                <a href="index.php?page=data_supplier">Data Supplier</a>
                <i class="fas fa-chevron-right"></i>
                <span>Edit Supplier</span>
            </div>
        </div>

        <div class="content">
            <?php if (isset($error)): ?>
                <div style="color: red; background: #fee; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h3>Form Edit Data Perusahaan</h3>
                    <a href="index.php?page=data_supplier" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    <form method="POST" class="form-vertical">

                        <div class="form-row">
                            <div class="form-group" style="flex: 1; margin-right: 15px;">
                                <label><i class="fas fa-building"></i> Nama Perusahaan *</label>
                                <input type="text" name="nama_perusahaan"
                                    value="<?php echo htmlspecialchars($supplier['nama_perusahaan']); ?>" required>
                            </div>
                            <div class="form-group" style="flex: 1;">
                                <label><i class="fas fa-user-tie"></i> Nama Kontak Person *</label>
                                <input type="text" name="nama_kontak"
                                    value="<?php echo htmlspecialchars($supplier['nama_kontak']); ?>" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group" style="flex: 1; margin-right: 15px;">
                                <label><i class="fas fa-envelope"></i> Email</label>
                                <input type="email" name="email"
                                    value="<?php echo htmlspecialchars($supplier['email']); ?>">
                            </div>
                            <div class="form-group" style="flex: 1;">
                                <label><i class="fas fa-phone"></i> No. Telepon *</label>
                                <input type="text" name="telepon"
                                    value="<?php echo htmlspecialchars($supplier['telepon']); ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-map-marker-alt"></i> Alamat Lengkap *</label>
                            <textarea name="alamat" rows="3" required
                                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;"><?php echo htmlspecialchars($supplier['alamat']); ?></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group" style="flex: 1; margin-right: 10px;">
                                <label>Kota</label>
                                <input type="text" name="kota"
                                    value="<?php echo htmlspecialchars($supplier['kota']); ?>">
                            </div>
                            <div class="form-group" style="flex: 1; margin-right: 10px;">
                                <label>Provinsi</label>
                                <input type="text" name="provinsi"
                                    value="<?php echo htmlspecialchars($supplier['provinsi']); ?>">
                            </div>
                            <div class="form-group" style="flex: 0.5;">
                                <label>Kode Pos</label>
                                <input type="text" name="kode_pos"
                                    value="<?php echo htmlspecialchars($supplier['kode_pos']); ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group" style="flex: 1; margin-right: 15px;">
                                <label><i class="fas fa-boxes"></i> Jenis Produk *</label>
                                <select name="jenis_produk" required>
                                    <?php
                                    $jenis = ['barang', 'jasa', 'bahan baku', 'bahan jadi'];
                                    foreach ($jenis as $j) {
                                        $sel = ($supplier['jenis_produk'] == $j) ? 'selected' : '';
                                        echo "<option value='$j' $sel>" . ucwords($j) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group" style="flex: 1;">
                                <label><i class="fas fa-store"></i> Model Bisnis *</label>
                                <select name="model_bisnis" required>
                                    <?php
                                    $model = ['produsen', 'grosir', 'importir'];
                                    foreach ($model as $m) {
                                        $sel = ($supplier['model_bisnis'] == $m) ? 'selected' : '';
                                        echo "<option value='$m' $sel>" . ucwords($m) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-info-circle"></i> Keterangan Tambahan</label>
                            <input type="text" name="keterangan"
                                value="<?php echo htmlspecialchars($supplier['keterangan']); ?>">
                        </div>

                        <div class="form-actions"
                            style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
                            <button type="reset" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary"
                                style="background: #4e73df; color: white; border: none; cursor: pointer;">
                                <i class="fas fa-save"></i> Update Data Supplier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<style>
    .form-row {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 15px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 5px;
        font-size: 14px;
        color: #555;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        outline: none;
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: #4e73df;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        display: inline-block;
    }

    .btn-primary {
        background: #4e73df;
        color: white;
    }

    .btn-secondary {
        background: #858796;
        color: white;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
</style>

<?php include 'includes/footer.php'; ?>