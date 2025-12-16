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
                                $onclick = ''; // Tidak ada fungsi klik
                                $cursor = 'default';
                            } else {
                                $src = $fotoPath;
                                $onclick = 'onclick="openModal(this)"'; // Panggil fungsi JS
                                $cursor = 'pointer';
                            }
                            ?>
                            
                            <img src="<?php echo $src; ?>" alt="Foto Profil" <?php echo $onclick; ?>
                                style="width: 150px; height: 150px; object-fit: cover; border: 1px solid #ddd; padding: 5px; border-radius: 50%; cursor: <?php echo $cursor; ?>; transition: 0.3s;">

                            <?php if (!empty($pegawai['foto_profil']) && file_exists($fotoPath)) : ?>
                                <p class="text-muted" style="font-size: 12px; margin-top: 5px;">Klik gambar untuk memperbesar</p>
                            <?php endif; ?>
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

<div id="myImageModal" class="image-modal">
    <span class="close-modal" onclick="closeModal()">&times;</span>
    <img class="modal-content-img" id="img01">
    <div id="caption"></div>
</div>

<style>
    /* Style untuk Modal Latar Belakang */
    .image-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        padding-top: 50px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.85); /* Hitam transparan */
    }

    /* Style Gambar di dalam Modal */
    .modal-content-img {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        max-height: 85vh; /* Agar tidak kepanjangan di layar */
        object-fit: contain;
    }

    /* Style Caption */
    #caption {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        text-align: center;
        color: #ccc;
        padding: 10px 0;
        font-size: 18px;
    }

    /* Animasi Zoom In */
    .modal-content-img,
    #caption {
        -webkit-animation-name: zoom;
        animation-name: zoom;
    }

    @-webkit-keyframes zoom {
        from { -webkit-transform: scale(0) }
        to { -webkit-transform: scale(1) }
    }

    @keyframes zoom {
        from { transform: scale(0) }
        to { transform: scale(1) }
    }

    /* Tombol Close (X) */
    .close-modal {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
        cursor: pointer;
    }

    .close-modal:hover,
    .close-modal:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }

    /* Responsif Mobile */
    @media only screen and (max-width: 700px) {
        .modal-content-img {
            width: 100%;
        }
    }
</style>

<script>
    // Ambil elemen modal
    var modal = document.getElementById("myImageModal");

    // Ambil elemen gambar di dalam modal & caption
    var modalImg = document.getElementById("img01");
    var captionText = document.getElementById("caption");

    // Fungsi membuka modal (dipanggil saat gambar diklik)
    function openModal(element) {
        modal.style.display = "block";
        modalImg.src = element.src; 
        captionText.innerHTML = element.alt; 
    }

    // Fungsi menutup modal
    function closeModal() {
        modal.style.display = "none";
    }

    // Tutup jika user klik area gelap (background)
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Tutup jika tombol ESC ditekan
    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            modal.style.display = "none";
        }
    });
</script>

<?php include 'includes/footer.php'; ?>