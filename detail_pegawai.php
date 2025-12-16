<?php
include 'koneksi.php';

$page_title = "Detail Pegawai";

// Ambil ID dari URL (STRING)
$id = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : '';

$query = "SELECT * FROM pegawai WHERE id_pegawai = '$id'";
$result = mysqli_query($koneksi, $query);
$pegawai = mysqli_fetch_assoc($result);

if (!$pegawai) {
    $_SESSION['pesan'] = "Data pegawai tidak ditemukan!";
    $_SESSION['tipe'] = "error";
    header("Location: index.php?page=data_pegawai");
    exit();
}
?>

<?php include 'includes/header.php'; ?>

<div class="content-wrapper">
    <?php include 'includes/menu.php'; ?>

    <main class="main-content">
        <div class="page-header">
            <h2>Detail Pegawai</h2>
            <div class="breadcrumb">
                <a href="index.php">Home</a>
                <i class="fas fa-chevron-right"></i>
                <a href="index.php?page=pegawai">Data Pegawai</a>
                <i class="fas fa-chevron-right"></i>
                <span>Detail Pegawai</span>
            </div>
        </div>

        <div class="content">
            <div class="card">
                <div class="card-body">
                    <form class="form-vertical">

                        <div class="form-group" style="text-align: center; margin-bottom: 30px;">
                            <label style="display:block; font-weight:bold;">Foto Profil</label>
                            <?php
                            $fotoPath = !empty($pegawai['foto_profil']) ? 'uploads/' . $pegawai['foto_profil'] : '';

                            // Cek apakah file ada atau kosong
                            if (!file_exists($fotoPath) || empty($pegawai['foto_profil'])) {
                                // Placeholder user icon jika foto kosong
                                $src = 'https://via.placeholder.com/150?text=No+Profile';
                            } else {
                                $src = $fotoPath;
                            }
                            ?>
                            <img src="<?php echo $src; ?>" alt="Foto Profil"
                                style="width: 150px; height: 150px; object-fit: cover; border: 1px solid #ddd; padding: 5px; border-radius: 50%;">
                        </div>

                        <div class="form-group">
                            <label for="id_pegawai">ID Pegawai</label>
                            <input type="text" class="form-control" disabled value="<?php echo $pegawai['id_pegawai']; ?>">
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-user"></i> Nama Lengkap</label>
                            <input type="text" disabled value="<?php echo htmlspecialchars($pegawai['nama']); ?>">
                        </div>

                        <div class="form-row">
                            <div class="form-group" style="flex: 1; margin-right: 10px;">
                                <label><i class="fas fa-envelope"></i> Email</label>
                                <input type="email" disabled value="<?php echo htmlspecialchars($pegawai['email']); ?>">
                            </div>

                            <div class="form-group" style="flex: 1;">
                                <label><i class="fas fa-phone"></i> No Telepon</label>
                                <input type="text" disabled value="<?php echo htmlspecialchars($pegawai['no_telepon']); ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group" style="flex: 1; margin-right: 10px;">
                                <label><i class="fas fa-briefcase"></i> Jabatan</label>
                                <input type="text" disabled value="<?php echo htmlspecialchars($pegawai['jabatan']); ?>">
                            </div>

                            <div class="form-group" style="flex: 1;">
                                <label><i class="fas fa-money-bill-wave"></i> Gaji (Rp)</label>
                                <input type="text" disabled value="Rp <?php echo number_format($pegawai['gaji'], 0, ',', '.'); ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group" style="flex: 1; margin-right: 10px;">
                                <label><i class="fas fa-calendar-alt"></i> Tanggal Masuk</label>
                                <input type="text" disabled value="<?php echo date('d F Y', strtotime($pegawai['tanggal_masuk'])); ?>">
                            </div>

                            <div class="form-group" style="flex: 1;">
                                <label><i class="fas fa-info-circle"></i> Status Aktif</label>
                                <?php
                                // Logika warna status
                                $statusLabel = ucfirst($pegawai['status_aktif']); // Aktif / Tidak aktif
                                $statusColor = (strtolower($pegawai['status_aktif']) == 'aktif' || $pegawai['status_aktif'] == 1) ? 'green' : 'red';
                                ?>
                                <input type="text" disabled
                                    value="<?php echo $statusLabel; ?>"
                                    style="font-weight: bold; color: <?php echo $statusColor; ?>;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-heart"></i> Status Kawin</label>

                            <?php
                            $status_kawin = $pegawai['status_kawin'] ?? '-';

                            if ($status_kawin == 'Belum Pernah') {
                                $warna = '#0d6efd';
                            } elseif ($status_kawin == 'Pernah') {
                                $warna = '#198754';
                            } elseif ($status_kawin == 'Rahasia') {
                                $warna = '#6f42c1';
                            } else {
                                $warna = '#6c757d';
                            }
                            ?>

                            <input type="text"
                                disabled
                                value="<?php echo htmlspecialchars($status_kawin); ?>"
                                style="font-weight:bold;color:<?php echo $warna; ?>;">
                        </div>



                        <div class="card-header" style="background-color: #f8f9fa; border-top: 1px solid #ddd; padding: 15px;">
                            <a href="index.php?page=pegawai" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <a href="edit_pegawai.php?id=<?php echo $pegawai['id_pegawai']; ?>" class="btn btn-primary" style="margin-left: 10px;">
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