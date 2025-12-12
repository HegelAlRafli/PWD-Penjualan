<?php
// Query data pegawai
include 'koneksi.php';
$query = "SELECT * FROM pegawai ORDER BY id_pegawai DESC";
$result = mysqli_query($koneksi, $query);
?>

<div class="card">
    <div class="card-header">
        <h3>DATA PEGAWAI</h3>
        <div class="card-actions">
            <a href="tambah_pegawai.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Pegawai
            </a>
            <button class="btn btn-secondary" onclick="window.print()">
                <i class="fas fa-print"></i> Cetak
            </button>
        </div>
    </div>

    <div class="card-body">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%">Foto</th>
                        <th>Nama Pegawai</th>
                        <th>Jabatan</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php $no = 1;
                        while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td>
                            <img src="uploads/<?php echo htmlspecialchars($row['foto_profil']); ?>" alt="Foto"
                                style="width: 45px; height: 45px; object-fit: cover; border-radius: 50%;">
                        </td>
                        <td>
                            <strong><?php echo htmlspecialchars($row['nama']); ?></strong>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($row['jabatan']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($row['email']); ?>
                        </td>
                        <td>
                            <span
                                class="status <?php echo $row['status_aktif'] == 1 ? 'status-active' : 'status-inactive'; ?>">
                                <i class="fas fa-circle"></i>
                                <?php echo $row['status_aktif'] == 1 ? 'Aktif' : 'Non-Aktif'; ?>
                            </span>
                        </td>
                        <td class="action-buttons">
                            <a href="detail.php?id=<?php echo $row['id_pegawai']; ?>" class="btn-action btn-view"
                                title="Detail Lengkap">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="edit_pegawai.php?id=<?php echo $row['id_pegawai']; ?>" class="btn-action btn-edit"
                                title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="hapus_pegawai.php?id=<?php echo $row['id_pegawai']; ?>"
                                class="btn-action btn-delete" title="Hapus"
                                onclick="return confirm('Yakin hapus pegawai ini?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">
                            <div class="empty-state">
                                <i class="fas fa-users fa-3x"></i>
                                <h4>Tidak ada data pegawai</h4>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>