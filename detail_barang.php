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
                            <label style="display:block; font-weight:bold;">Foto Barang (Klik untuk memperbesar)</label>
                            <?php
                            $fotoPath = !empty($barang['foto']) ? 'uploads/' . $barang['foto'] : '';

                            // Logika cek file untuk menentukan src dan fungsi klik
                            if (!file_exists($fotoPath) || empty($barang['foto'])) {
                                $src = 'https://via.placeholder.com/200?text=No+Image';
                                $onclick = ''; // Tidak bisa diklik jika placeholder
                                $cursor = 'default';
                            } else {
                                $src = $fotoPath;
                                $onclick = 'onclick="openModal(this)"'; // Panggil fungsi JS
                                $cursor = 'pointer';
                            }
                            ?>

                            <img src="<?php echo $src; ?>" alt="Foto Barang" <?php echo $onclick; ?>
                                style="max-width: 200px; height: auto; border: 1px solid #ddd; padding: 5px; border-radius: 5px; cursor: <?php echo $cursor; ?>; transition: 0.3s;">

                            <?php if (!empty($barang['foto']) && file_exists($fotoPath)) : ?>
                                <p class="text-muted" style="font-size: 12px; margin-top: 5px;">Klik gambar untuk melihat
                                    ukuran penuh</p>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="id">ID Database</label>
                            <input type="text" class="form-control" disabled value="<?php echo $barang['id']; ?>">
                        </div>

                        <div class="form-row">
                            <div class="form-group" style="flex: 1; margin-right: 10px;">
                                <label><i class="fas fa-barcode"></i> Kode Barang</label>
                                <input type="text" disabled
                                    value="<?php echo htmlspecialchars($barang['kode_barang']); ?>">
                            </div>

                            <div class="form-group" style="flex: 1;">
                                <label><i class="fas fa-tag"></i> Kategori</label>
                                <input type="text" disabled
                                    value="<?php echo htmlspecialchars($barang['kategori']); ?>">
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
                                <input type="text" disabled
                                    value="Rp <?php echo number_format($barang['harga'], 0, ',', '.'); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-info-circle"></i> Status</label>
                            <input type="text" disabled value="<?php echo ucfirst($barang['status']); ?>"
                                style="font-weight: bold; color: <?php echo ($barang['status'] == 'aktif') ? 'green' : 'red'; ?>;">
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-align-left"></i> Deskripsi</label>
                            <textarea rows="4" disabled><?php echo htmlspecialchars($barang['deskripsi']); ?></textarea>
                        </div>

                        <div class="card-header"
                            style="background-color: #f8f9fa; border-top: 1px solid #ddd; padding: 15px;">
                            <a href="index.php?page=data_barang" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>

                            <a href="edit.php?id=<?php echo $barang['id']; ?>" class="btn btn-primary"
                                style="margin-left: 10px;">
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
    /* Memaksa Modal agar melayang (Popup) */
    .image-modal {
        display: none;
        /* Wajib hidden dulu */
        position: fixed;
        /* Wajib fixed agar melayang */
        z-index: 9999;
        /* Agar berada paling depan */
        padding-top: 50px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.85);
        /* Latar belakang agak gelap */
    }

    .modal-content-img {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        max-height: 85vh;
        /* Dibatasi agar tidak terlalu tinggi */
        object-fit: contain;
    }

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

    /* Animasi Zoom simpel */
    .modal-content-img,
    #caption {
        -webkit-animation-name: zoom;
        animation-name: zoom;
    }

    @-webkit-keyframes zoom {
        from {
            -webkit-transform: scale(0)
        }

        to {
            -webkit-transform: scale(1)
        }
    }

    @keyframes zoom {
        from {
            transform: scale(0)
        }

        to {
            transform: scale(1)
        }
    }

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

    /* Responsif untuk layar HP */
    @media only screen and (max-width: 700px) {
        .modal-content-img {
            width: 100%;
        }
    }
</style>

<script>
    // Ambil elemen modal
    var modal = document.getElementById("myImageModal");

    // Ambil elemen gambar di dalam modal
    var modalImg = document.getElementById("img01");
    var captionText = document.getElementById("caption");

    // Fungsi untuk membuka modal
    function openModal(element) {
        modal.style.display = "block";
        modalImg.src = element.src; // Mengambil src dari gambar yang diklik
        captionText.innerHTML = element.alt; // Mengambil alt text sebagai caption
    }

    // Fungsi untuk menutup modal
    function closeModal() {
        modal.style.display = "none";
    }

    // Menutup modal jika user klik di area hitam (bukan gambar)
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Menutup modal dengan tombol ESC keyboard
    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            modal.style.display = "none";
        }
    });
</script>

<?php include 'includes/footer.php'; ?>