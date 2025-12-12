<?php
    // Query data barang
    include 'koneksi.php';
    $query = "SELECT * FROM barang ORDER BY id DESC";
    $result = mysqli_query($koneksi, $query);
?>

<div class="card">
    <div class="card-header">
        <h3>DATA BARANG</h3>
        <div class="card-actions">
            <a href="tambah.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Barang
            </a>
            <button class="btn btn-secondary" onclick="window.print()">
                <i class="fas fa-print"></i> Cetak
            </button>
        </div>
    </div>
    
    <div class="card-body">
                
        <!-- Tabel Data Barang -->
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
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
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($row['kode_barang']); ?></strong>
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
                                <a href="#" class="btn-action btn-view" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
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
