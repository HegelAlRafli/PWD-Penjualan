<?php
include 'koneksi.php';

$page_title = "Tambah Pegawai";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data input
    $id_pegawai     = clean_input($_POST['id_pegawai']);
    $nama           = clean_input($_POST['nama']);
    $email          = clean_input($_POST['email']);
    $no_telepon     = clean_input($_POST['no_telepon']);
    $jabatan        = clean_input($_POST['jabatan']);
    $gaji           = clean_input($_POST['gaji']);
    $tanggal_masuk  = clean_input($_POST['tanggal_masuk']);
    $status_aktif   = clean_input($_POST['status_aktif']);

    // Generate ID Pegawai otomatis jika kosong (Format: PEG001)
    if (empty($id_pegawai)) {
        $prefix = "PEG";
        $query = "SELECT MAX(SUBSTRING(id_pegawai, 4)) as max_code FROM pegawai WHERE id_pegawai LIKE '$prefix%'";
        $result = mysqli_query($koneksi, $query);
        $row = mysqli_fetch_assoc($result);
        $next_num = ($row['max_code'] ?? 0) + 1;
        $id_pegawai = $prefix . str_pad($next_num, 3, '0', STR_PAD_LEFT);
    }

    // Logic Upload Foto Profil
    $foto_nama = 'default.png'; // Foto default jika tidak ada upload

    if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] == 0) {
        $target_dir = "uploads/";

        // Pastikan folder uploads ada
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_extension = pathinfo($_FILES["foto_profil"]["name"], PATHINFO_EXTENSION);
        $file_name_new = $id_pegawai . "_" . time() . "." . $file_extension; // Nama file unik
        $target_file = $target_dir . $file_name_new;

        // Validasi tipe file (opsional, bisa ditambah)
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($file_extension), $allowed_types)) {
            if (move_uploaded_file($_FILES["foto_profil"]["tmp_name"], $target_file)) {
                $foto_nama = $file_name_new;
            }
        }
    }

    // Cek ID sudah ada
    $check_query = "SELECT id_pegawai FROM pegawai WHERE id_pegawai = '$id_pegawai'";
    $check_result = mysqli_query($koneksi, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $_SESSION['pesan'] = "ID Pegawai sudah digunakan!";
        $_SESSION['tipe'] = "error";
    } else {
        // Query Insert
        $query = "INSERT INTO pegawai (id_pegawai, nama, email, no_telepon, foto_profil, jabatan, gaji, tanggal_masuk, status_aktif) 
                  VALUES ('$id_pegawai', '$nama', '$email', '$no_telepon', '$foto_nama', '$jabatan', '$gaji', '$tanggal_masuk', '$status_aktif')";

        if (mysqli_query($koneksi, $query)) {
            $_SESSION['pesan'] = "Pegawai berhasil ditambahkan!";
            $_SESSION['tipe'] = "success";
            header("Location: index.php?page=data_pegawai");
            exit();
        } else {
            $_SESSION['pesan'] = "Gagal menambahkan pegawai: " . mysqli_error($koneksi);
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
            <h2>Tambah Pegawai Baru</h2>
            <div class="breadcrumb">
                <a href="index.php">Home</a>
                <i class="fas fa-chevron-right"></i>
                <a href="index.php?page=data_pegawai">Data Pegawai</a>
                <i class="fas fa-chevron-right"></i>
                <span>Tambah Pegawai</span>
            </div>
        </div>

        <div class="content">
            <div class="card">
                <div class="card-header">
                    <h3>Form Tambah Pegawai</h3>
                    <a href="index.php?page=data_pegawai" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    <form method="POST" class="form-vertical" enctype="multipart/form-data">

                        <div class="form-row">
                            <div class="form-group">
                                <label for="id_pegawai">
                                    <i class="fas fa-id-card"></i> ID Pegawai
                                </label>
                                <input type="text" id="id_pegawai" name="id_pegawai"
                                    placeholder="Kosongkan untuk auto-generate (PEGxxx)">
                            </div>

                            <div class="form-group">
                                <label for="nama">
                                    <i class="fas fa-user"></i> Nama Lengkap *
                                </label>
                                <input type="text" id="nama" name="nama" required placeholder="Nama lengkap pegawai">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">
                                    <i class="fas fa-envelope"></i> Email *
                                </label>
                                <input type="email" id="email" name="email" required placeholder="email@contoh.com">
                            </div>

                            <div class="form-group">
                                <label for="no_telepon">
                                    <i class="fas fa-phone"></i> No. Telepon *
                                </label>
                                <input type="text" id="no_telepon" name="no_telepon" required
                                    placeholder="08xxxxxxxxxx">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="jabatan">
                                    <i class="fas fa-briefcase"></i> Jabatan *
                                </label>
                                <select id="jabatan" name="jabatan" required>
                                    <option value="">Pilih Jabatan</option>
                                    <option value="Manager">Manager</option>
                                    <option value="Supervisor">Supervisor</option>
                                    <option value="Staff Admin">Staff Admin</option>
                                    <option value="Staff Gudang">Staff Gudang</option>
                                    <option value="Marketing">Marketing</option>
                                    <option value="IT Support">IT Support</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="gaji">
                                    <i class="fas fa-money-bill-wave"></i> Gaji (Rp) *
                                </label>
                                <input type="number" id="gaji" name="gaji" min="0" required
                                    placeholder="Contoh: 5000000">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="tanggal_masuk">
                                    <i class="fas fa-calendar-alt"></i> Tanggal Masuk *
                                </label>
                                <input type="date" id="tanggal_masuk" name="tanggal_masuk" required>
                            </div>

                            <div class="form-group">
                                <label for="status_aktif">
                                    <i class="fas fa-toggle-on"></i> Status Aktif *
                                </label>
                                <select id="status_aktif" name="status_aktif" required>
                                    <option value="1">Aktif</option>
                                    <option value="0">Non-Aktif</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="foto_profil">
                                <i class="fas fa-camera"></i> Foto Profil
                            </label>
                            <input type="file" id="foto_profil" name="foto_profil" accept="image/*">
                            <small class="form-hint">Format: JPG, PNG, GIF. Max: 2MB.</small>
                        </div>

                        <div class="form-actions">
                            <button type="reset" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Pegawai
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include 'includes/footer.php'; ?>