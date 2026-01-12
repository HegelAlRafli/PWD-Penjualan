<?php
include 'koneksi.php';

$page_title = "Detail Supplier";

// Ambil ID dari URL (INTEGER/STRING)
$id = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : '';

$query = "SELECT * FROM suppliers WHERE id_supplier = '$id'";
$result = mysqli_query($koneksi, $query);
$supplier = mysqli_fetch_assoc($result);

if (!$supplier) {
    echo "<script>alert('Data supplier tidak ditemukan!'); window.location='index.php?page=data_supplier';</script>";
    exit();
}
?>

<?php include 'includes/header.php'; ?>

<div class="content-wrapper">
    <?php include 'includes/menu.php'; ?>

    <main class="main-content">
        <div class="page-header">
            <h2>Detail Supplier</h2>
            <div class="breadcrumb">
                <a href="index.php">Home</a>
                <i class="fas fa-chevron-right"></i>
                <a href="index.php?page=data_supplier">Data Supplier</a>
                <i class="fas fa-chevron-right"></i>
                <span>Detail Supplier</span>
            </div>
        </div>

        <div class="content">
            <div class="card">
                <div class="card-body">
                    <form class="form-vertical">

                        <div class="form-group" style="text-align: center; margin-bottom: 30px;">
                            <div
                                style="width: 120px; height: 120px; background: #f4f7fe; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; border: 2px solid #4e73df; color: #4e73df;">
                                <i class="fas fa-building fa-4x"></i>
                            </div>
                            <h3 style="margin-top: 15px; color: #333;">
                                <?php echo htmlspecialchars($supplier['nama_perusahaan']); ?></h3>
                            <span class="badge"
                                style="background: #4e73df; color:white; padding: 5px 15px; border-radius: 20px;">
                                <?php echo strtoupper($supplier['model_bisnis']); ?>
                            </span>
                        </div>

                        <div class="form-row">
                            <div class="form-group" style="flex: 1; margin-right: 10px;">
                                <label><i class="fas fa-id-badge"></i> ID Supplier</label>
                                <input type="text" disabled value="<?php echo $supplier['id_supplier']; ?>">
                            </div>
                            <div class="form-group" style="flex: 1;">
                                <label><i class="fas fa-user-tie"></i> Nama Kontak Person</label>
                                <input type="text" disabled
                                    value="<?php echo htmlspecialchars($supplier['nama_kontak']); ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group" style="flex: 1; margin-right: 10px;">
                                <label><i class="fas fa-envelope"></i> Email</label>
                                <input type="email" disabled
                                    value="<?php echo htmlspecialchars($supplier['email']); ?>">
                            </div>
                            <div class="form-group" style="flex: 1;">
                                <label><i class="fas fa-phone"></i> No Telepon</label>
                                <input type="text" disabled
                                    value="<?php echo htmlspecialchars($supplier['telepon']); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-map-marker-alt"></i> Alamat Lengkap</label>
                            <textarea disabled
                                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; background: #f9f9f9; height: 80px; resize: none;"><?php echo htmlspecialchars($supplier['alamat']); ?></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group" style="flex: 1; margin-right: 10px;">
                                <label>Kota</label>
                                <input type="text" disabled value="<?php echo htmlspecialchars($supplier['kota']); ?>">
                            </div>
                            <div class="form-group" style="flex: 1; margin-right: 10px;">
                                <label>Provinsi</label>
                                <input type="text" disabled
                                    value="<?php echo htmlspecialchars($supplier['provinsi']); ?>">
                            </div>
                            <div class="form-group" style="flex: 0.5;">
                                <label>Kode Pos</label>
                                <input type="text" disabled
                                    value="<?php echo htmlspecialchars($supplier['kode_pos']); ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group" style="flex: 1; margin-right: 10px;">
                                <label><i class="fas fa-boxes"></i> Jenis Produk</label>
                                <?php
                                $jenis = $supplier['jenis_produk'];
                                $color = ($jenis == 'bahan baku') ? '#e74a3b' : (($jenis == 'bahan jadi') ? '#1cc88a' : '#36b9cc');
                                ?>
                                <input type="text" disabled value="<?php echo ucwords($jenis); ?>"
                                    style="font-weight:bold; color: <?php echo $color; ?>;">
                            </div>
                            <div class="form-group" style="flex: 1;">
                                <label><i class="fas fa-store"></i> Model Bisnis</label>
                                <input type="text" disabled value="<?php echo ucwords($supplier['model_bisnis']); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-info-circle"></i> Keterangan</label>
                            <input type="text" disabled
                                value="<?php echo htmlspecialchars($supplier['keterangan'] ?? '-'); ?>">
                        </div>

                        <div class="card-footer"
                            style="background-color: #f8f9fa; border-top: 1px solid #ddd; padding: 20px; margin-top: 20px; border-radius: 0 0 10px 10px;">
                            <a href="index.php?page=data_supplier" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <a href="edit_supplier.php?id=<?php echo $supplier['id_supplier']; ?>"
                                class="btn btn-primary" style="margin-left: 10px;">
                                <i class="fas fa-edit"></i> Edit Data
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<style>
    /* Styling tambahan agar input disabled terlihat bersih */
    input[disabled],
    textarea[disabled] {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #eee;
        border-radius: 8px;
        background-color: #fcfcfc !important;
        color: #555;
        cursor: not-allowed;
        margin-top: 5px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        font-weight: 600;
        font-size: 14px;
        color: #444;
        display: block;
    }

    .form-row {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
        font-weight: 600;
    }

    .btn-primary {
        background: #4e73df;
        color: white;
    }

    .btn-secondary {
        background: #858796;
        color: white;
    }

    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
        }

        .form-group {
            margin-right: 0 !important;
        }
    }
</style>

<?php include 'includes/footer.php'; ?>