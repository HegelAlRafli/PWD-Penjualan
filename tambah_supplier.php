<?php
include 'koneksi.php';

$page_title = "Tambah Supplier";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data input (Menggunakan fungsi clean_input yang diasumsikan ada di koneksi.php)
    $nama_perusahaan = clean_input($_POST['nama_perusahaan']);
    $nama_kontak     = clean_input($_POST['nama_kontak']);
    $email           = clean_input($_POST['email']);
    $telepon          = clean_input($_POST['telepon']);
    $alamat          = clean_input($_POST['alamat']);
    $kota            = clean_input($_POST['kota']);
    $provinsi        = clean_input($_POST['provinsi']);
    $kode_pos        = clean_input($_POST['kode_pos']);
    $jenis_produk    = clean_input($_POST['jenis_produk']);
    $model_bisnis    = clean_input($_POST['model_bisnis']);
    $keterangan      = clean_input($_POST['keterangan']);

    // Query Insert ke tabel suppliers
    $query = "INSERT INTO suppliers (
        nama_perusahaan, nama_kontak, email, telepon, alamat, 
        kota, provinsi, kode_pos, jenis_produk, model_bisnis, keterangan
    ) VALUES (
        '$nama_perusahaan', '$nama_kontak', '$email', '$telepon', '$alamat', 
        '$kota', '$provinsi', '$kode_pos', '$jenis_produk', '$model_bisnis', '$keterangan'
    )";

    if (mysqli_query($koneksi, $query)) {
        $_SESSION['pesan'] = "Supplier berhasil ditambahkan!";
        $_SESSION['tipe'] = "success";
        header("Location: index.php?page=data_supplier");
        exit();
    } else {
        $_SESSION['pesan'] = "Gagal menambahkan supplier: " . mysqli_error($koneksi);
        $_SESSION['tipe'] = "error";
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="content-wrapper">
    <?php include 'includes/menu.php'; ?>

    <main class="main-content">
        <div class="page-header">
            <h2>Tambah Supplier Baru</h2>
            <div class="breadcrumb">
                <a href="index.php">Home</a>
                <i class="fas fa-chevron-right"></i>
                <a href="index.php?page=data_supplier">Data Supplier</a>
                <i class="fas fa-chevron-right"></i>
                <span>Tambah Supplier</span>
            </div>
        </div>

        <div class="content">
            <div class="card">
                <div class="card-header">
                    <h3>Form Registrasi Supplier</h3>
                    <a href="index.php?page=data_supplier" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    <form method="POST" class="form-vertical">

                        <div class="form-row">
                            <div class="form-group" style="flex: 1; margin-right: 15px;">
                                <label for="nama_perusahaan">
                                    <i class="fas fa-building"></i> Nama Perusahaan *
                                </label>
                                <input type="text" name="nama_perusahaan" required placeholder="Masukkan nama PT/CV">
                            </div>

                            <div class="form-group" style="flex: 1;">
                                <label for="nama_kontak">
                                    <i class="fas fa-user-tie"></i> Nama Kontak Person *
                                </label>
                                <input type="text" name="nama_kontak" required placeholder="Nama PIC/Kontak">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group" style="flex: 1; margin-right: 15px;">
                                <label for="email">
                                    <i class="fas fa-envelope"></i> Email Perusahaan
                                </label>
                                <input type="email" name="email" placeholder="contoh@perusahaan.com">
                            </div>

                            <div class="form-group" style="flex: 1;">
                                <label for="telepon">
                                    <i class="fas fa-phone"></i> No. Telepon *
                                </label>
                                <input type="text" name="telepon" required placeholder="Contoh: 021-xxxxxx atau 08xxx">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="alamat">
                                <i class="fas fa-map-marker-alt"></i> Alamat Kantor Lengkap *
                            </label>
                            <textarea name="alamat" rows="3" required
                                placeholder="Jl. Nama Jalan No. XX, Kecamatan..."></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group" style="flex: 1; margin-right: 10px;">
                                <label>Kota</label>
                                <input type="text" name="kota" placeholder="Contoh: Jakarta Selatan">
                            </div>
                            <div class="form-group" style="flex: 1; margin-right: 10px;">
                                <label>Provinsi</label>
                                <input type="text" name="provinsi" placeholder="Contoh: DKI Jakarta">
                            </div>
                            <div class="form-group" style="flex: 0.5;">
                                <label>Kode Pos</label>
                                <input type="text" name="kode_pos" placeholder="12345">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group" style="flex: 1; margin-right: 15px;">
                                <label for="jenis_produk">
                                    <i class="fas fa-boxes"></i> Jenis Produk *
                                </label>
                                <select name="jenis_produk" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="barang">Barang</option>
                                    <option value="jasa">Jasa</option>
                                    <option value="bahan baku">Bahan Baku</option>
                                    <option value="bahan jadi">Bahan Jadi</option>
                                </select>
                            </div>

                            <div class="form-group" style="flex: 1;">
                                <label for="model_bisnis">
                                    <i class="fas fa-store"></i> Model Bisnis *
                                </label>
                                <select name="model_bisnis" required>
                                    <option value="">-- Pilih Model --</option>
                                    <option value="produsen">Produsen</option>
                                    <option value="grosir">Grosir</option>
                                    <option value="importir">Importir</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="keterangan">
                                <i class="fas fa-info-circle"></i> Keterangan Tambahan
                            </label>
                            <input type="text" name="keterangan" placeholder="Contoh: Supplier spesialis kain katun">
                        </div>

                        <div class="form-actions"
                            style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
                            <button type="reset" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary"
                                style="background-color: #4e73df; border:none; color:white; cursor:pointer;">
                                <i class="fas fa-save"></i> Simpan Supplier
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
        margin-bottom: 10px;
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
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        outline: none;
        font-family: inherit;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #4e73df;
        box-shadow: 0 0 5px rgba(78, 115, 223, 0.1);
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
</style>

<?php include 'includes/footer.php'; ?>