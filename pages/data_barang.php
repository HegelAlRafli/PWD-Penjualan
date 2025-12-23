<?php
    // Query data barang
    if (!isset($koneksi)) {
        include 'koneksi.php';
    }
    $query = "SELECT * FROM barang ORDER BY id DESC";
    $result = mysqli_query($koneksi, $query);
?>

<style>
    /* Styling Search Bar (Sama seperti Data Pegawai) */
    .search-wrapper {
        position: relative;
        margin-right: 15px;
    }

    .search-input {
        padding: 8px 15px 8px 40px; /* Padding kiri besar untuk icon */
        border: 1px solid #ddd;
        border-radius: 50px;       /* Bentuk Pill */
        width: 250px;
        transition: all 0.3s ease;
        outline: none;
        background-color: #fafafa;
    }

    .search-input:focus {
        border-color: #4e73df;
        background-color: #fff;
        box-shadow: 0 0 8px rgba(78, 115, 223, 0.2);
        width: 280px; /* Efek melebar */
    }

    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #aaa;
        pointer-events: none;
    }
</style>

<div class="card">
    <div class="card-header">
        <h3>DATA BARANG</h3>
        <div class="card-actions" style="display: flex; align-items: center;">
            
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="searchInputBarang" class="search-input" placeholder="Cari kode, nama barang...">
            </div>

            <a href="tambah.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Barang
            </a>
            <button class="btn btn-secondary" onclick="window.print()" style="margin-left: 5px;">
                <i class="fas fa-print"></i> Cetak
            </button>
        </div>
    </div>
    
    <div class="card-body">
                
        <div class="table-container">
            <table class="data-table" id="tableBarang">
            <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Kode Barang</th>                        
                        <th>Foto</th>
                        <th>Nama Barang</th>
                        <th>Stok</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($result) > 0): ?>
                        <?php $no = 1; while($row = mysqli_fetch_assoc($result)): ?>
                        <tr class="data-row">
                            <td><?php echo $no++; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($row['kode_barang']); ?></strong>
                            </td>
                            <td>
                                <?php 
                                    $foto = !empty($row['foto']) ? $row['foto'] : 'default_barang.jpg';
                                ?>
                                <img src="uploads/<?php echo htmlspecialchars($foto); ?>" 
                                     alt="Img" 
                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px; border: 1px solid #ddd;">
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['nama_barang']); ?>
                            </td>
                            <td>
                                <span class="badge <?php echo $row['stok'] > 10 ? 'badge-success' : ($row['stok'] > 0 ? 'badge-warning' : 'badge-danger'); ?>">
                                    <?php echo $row['stok']; ?> unit
                                </span>
                            </td>
                            <td>
                                <span class="text-primary">
                                    Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?>
                                </span>
                            </td>
                            <td>
                                <span class="status <?php echo $row['status'] == 'aktif' ? 'status-active' : 'status-inactive'; ?>">
                                    <i class="fas fa-circle"></i>
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </td>
                            <td class="action-buttons">
                                <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn-action btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="hapus.php?id=<?php echo $row['id']; ?>" 
                                   class="btn-action btn-delete" 
                                   title="Hapus"
                                   onclick="return confirm('Yakin hapus barang ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <a href="detail_barang.php?id=<?php echo $row['id']; ?>" class="btn-action btn-view" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>

                        <tr id="noDataRow" style="display: none;">
                            <td colspan="8" class="text-center" style="padding: 20px; color: #666;">
                                <i class="fas fa-search" style="font-size: 20px; color: #ccc; margin-bottom: 5px; display:block;"></i>
                                Data barang tidak ditemukan
                            </td>
                        </tr>

                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-box-open fa-3x"></i>
                                    <h4>Belum ada data barang</h4>
                                    <p>Mulai dengan menambahkan barang baru</p>
                                    <a href="tambah.php" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Tambah Barang Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.getElementById('searchInputBarang').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#tableBarang tbody .data-row'); // Target baris data
    let noDataRow = document.getElementById('noDataRow');
    let hasVisibleRow = false;

    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        if(text.includes(filter)) {
            row.style.display = ''; // Tampilkan    
            hasVisibleRow = true;
        } else {
            row.style.display = 'none'; // Sembunyikan
        }
    });

    // Tampilkan pesan "Tidak ditemukan" jika hasil kosong
    if (noDataRow) {
        if (!hasVisibleRow && filter !== '') {
            noDataRow.style.display = '';
        } else {
            noDataRow.style.display = 'none';
        }
    }
});
</script>