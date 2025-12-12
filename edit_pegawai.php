<?php
include 'koneksi.php';

$page_title = "Edit Pegawai";

// Ambil ID dari URL (Gunakan real_escape_string karena ID formatnya String 'PEGxxx')
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
} else {
    header("Location: index.php?page=data_pegawai");
    exit();
}

// Ambil data pegawai berdasarkan id_pegawai
$query = "SELECT * FROM pegawai WHERE id_pegawai = '$id'";
$result = mysqli_query($koneksi, $query);
$pegawai = mysqli_fetch_assoc($result);

if (!$pegawai) {
    $_SESSION['pesan'] = "Data pegawai tidak ditemukan!";
    $_SESSION['tipe'] = "error";
    header("Location: index.php?page=data_pegawai");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil input form
    $id_pegawai_baru = clean_input($_POST['id_pegawai']); // Jika ID boleh diedit
    $nama           = clean_input($_POST['nama']);
    $email          = clean_input($_POST['email']);
    $no_telepon     = clean_input($_POST['no_telepon']);
    $jabatan        = clean_input($_POST['jabatan']);
    $gaji           = clean_input($_POST['gaji']);
    $tanggal_masuk  = clean_input($_POST['tanggal_masuk']);
    $status_aktif   = clean_input($_POST['status_aktif']);

    // Cek duplikasi ID jika ID diubah (Opsional, jika ID boleh diedit)
    $check_query = "SELECT id_pegawai FROM pegawai WHERE id_pegawai = '$id_pegawai_baru' AND id_pegawai != '$id'";
    $check_result = mysqli_query($koneksi, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $_SESSION['pesan'] = "ID Pegawai sudah digunakan oleh pegawai lain!";
        $_SESSION['tipe'] = "error";
    } else {
        // --- LOGIC UPLOAD FOTO ---
        $foto_nama = $pegawai['foto_profil']; // Default: pakai foto lama

        // Jika ada file foto baru yang diupload
        if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] == 0) {
            $target_dir = "uploads/";
            $file_extension = pathinfo($_FILES["foto_profil"]["name"], PATHINFO_EXTENSION);
            $file_name_new = $id_pegawai_baru . "_" . time() . "." . $file_extension;
            $target_file = $target_dir . $file_name_new;

            // Upload file baru
            if (move_uploaded_file($_FILES["foto_profil"]["tmp_name"], $target_file)) {
                // Hapus foto lama jika ada dan bukan default
                if ($pegawai['foto_profil'] != 'default.png' && file_exists($target_dir . $pegawai['foto_profil'])) {
                    unlink($target_dir . $pegawai['foto_profil']);
                }
                $foto_nama = $file_name_new; // Update nama foto untuk database
            }
        }
        // -------------------------

        $query = "UPDATE pegawai SET 
                  id_pegawai = '$id_pegawai_baru',
                  nama = '$nama',
                  email = '$email',
                  no_telepon = '$no_telepon',
                  foto_profil = '$foto_nama',
                  jabatan = '$jabatan',
                  gaji = '$gaji',
                  tanggal_masuk = '$tanggal_masuk',
                  status_aktif = '$status_aktif'
                  WHERE id_pegawai = '$id'";

        if (mysqli_query($koneksi, $query)) {
            $_SESSION['pesan'] = "Data pegawai berhasil diperbarui!";
            $_SESSION['tipe'] = "success";
            header("Location: index.php?page=data_pegawai");
            exit();
        } else {
            $_SESSION['pesan'] = "Gagal memperbarui pegawai: " . mysqli_error($koneksi);
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
            <h2>Edit Pegawai</h2>
            <div class="breadcrumb">
                <a href="index.php">Home</a>
                <i class="fas fa-chevron-right"></i>
                <a href="index.php?page=data_pegawai">Data Pegawai</a>
                <i class="fas fa-chevron-right"></i>
                <span>Edit Pegawai</span>
            </div>
        </div>

        <div class="content">
            <div class="card">
                <div class="card-header">
                    <h3>Edit Data Pegawai</h3>
                    <a href="index.php?page=data_pegawai" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    <form method="POST" class="form-vertical" enctype="multipart/form-data">

                        <div class="form-row">
                            <div class="form-group">
                                <label for="id_pegawai">
                                    <i class="fas fa-id-card"></i> ID Pegawai *
                                </label>
                                <input type="text" id="id_pegawai" name="id_pegawai"
                                    value="<?php echo htmlspecialchars($pegawai['id_pegawai']); ?>" required>
                                <small class="form-hint">Hati-hati mengubah ID Pegawai.</small>
                            </div>

                            <div class="form-group">
                                <label for="nama">
                                    <i class="fas fa-user"></i> Nama Lengkap *
                                </label>
                                <input type="text" id="nama" name="nama"
                                    value="<?php echo htmlspecialchars($pegawai['nama']); ?>" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">
                                    <i class="fas fa-envelope"></i> Email *
                                </label>
                                <input type="email" id="email" name="email"
                                    value="<?php echo htmlspecialchars($pegawai['email']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="no_telepon">
                                    <i class="fas fa-phone"></i> No. Telepon *
                                </label>
                                <input type="text" id="no_telepon" name="no_telepon"
                                    value="<?php echo htmlspecialchars($pegawai['no_telepon']); ?>" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="jabatan">
                                    <i class="fas fa-briefcase"></i> Jabatan *
                                </label>
                                <select id="jabatan" name="jabatan" required>
                                    <option value="">Pilih Jabatan</option>
                                    <?php
                                    $jabatan_list = ["Manager", "Supervisor", "Staff Admin", "Staff Gudang", "Marketing", "IT Support"];
                                    foreach ($jabatan_list as $jab) {
                                        $selected = ($pegawai['jabatan'] == $jab) ? 'selected' : '';
                                        echo "<option value='$jab' $selected>$jab</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="gaji">
                                    <i class="fas fa-money-bill-wave"></i> Gaji (Rp) *
                                </label>
                                <input type="number" id="gaji" name="gaji" value="<?php echo $pegawai['gaji']; ?>"
                                    min="0" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="tanggal_masuk">
                                    <i class="fas fa-calendar-alt"></i> Tanggal Masuk *
                                </label>
                                <input type="date" id="tanggal_masuk" name="tanggal_masuk"
                                    value="<?php echo $pegawai['tanggal_masuk']; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="status_aktif">
                                    <i class="fas fa-toggle-on"></i> Status Aktif *
                                </label>
                                <select id="status_aktif" name="status_aktif" required>
                                    <option value="1" <?php echo ($pegawai['status_aktif'] == 1) ? 'selected' : ''; ?>>
                                        Aktif</option>
                                    <option value="0" <?php echo ($pegawai['status_aktif'] == 0) ? 'selected' : ''; ?>>
                                        Non-Aktif</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="foto_profil">
                                <i class="fas fa-camera"></i> Foto Profil
                            </label>
                            <div style="margin-bottom: 10px;">
                                <?php if (!empty($pegawai['foto_profil'])): ?>
                                <img src="uploads/<?php echo $pegawai['foto_profil']; ?>" alt="Foto Lama" width="100"
                                    class="img-thumbnail">
                                <br><small>Foto saat ini</small>
                                <?php endif; ?>
                            </div>
                            <input type="file" id="foto_profil" name="foto_profil" accept="image/*">
                            <small class="form-hint">Biarkan kosong jika tidak ingin mengganti foto.</small>
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