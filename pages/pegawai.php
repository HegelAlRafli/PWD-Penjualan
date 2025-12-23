<?php
// 1. Query Data Utama
include 'koneksi.php';
$query = "SELECT * FROM pegawai ORDER BY id_pegawai DESC";
$result = mysqli_query($koneksi, $query);

// 2. Query Khusus Statistik
$query_stats = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status_aktif = 1 THEN 1 ELSE 0 END) as aktif,
    SUM(CASE WHEN status_aktif = 0 OR status_aktif IS NULL THEN 1 ELSE 0 END) as non_aktif
    FROM pegawai";
$result_stats = mysqli_query($koneksi, $query_stats);
$stats = mysqli_fetch_assoc($result_stats);

$total_pegawai = $stats['total'] ?? 0;
$jml_aktif = $stats['aktif'] ?? 0;
$jml_non_aktif = $stats['non_aktif'] ?? 0;
?>

<style>
    /* --- CSS KHUSUS DASHBOARD (STATS) --- */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }

    .stat-card {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-left: 5px solid #ddd;
        transition: transform 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    /* Warna Border Kiri */
    .stat-blue { border-left-color: #4e73df; }
    .stat-green { border-left-color: #1cc88a; }
    .stat-red { border-left-color: #e74a3b; }

    /* Teks Statistik */
    .stat-content h4 {
        margin: 0;
        font-size: 13px;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
    }

    .stat-content .number {
        font-size: 28px;
        font-weight: bold;
        color: #333;
        margin-top: 5px;
        display: block;
    }

    /* --- UPDATE: ICON BULAT (LINGKARAN) --- */
    .stat-icon {
        width: 60px;        /* Lebar Lingkaran */
        height: 60px;       /* Tinggi Lingkaran */
        border-radius: 50%; /* Membuat Bulat Sempurna */
        background-color: #f9f9f9; /* Warna Background Icon */
        
        display: flex;
        align-items: center;
        justify-content: center;
        
        font-size: 24px;    /* Ukuran Icon */
    }


    /* --- CSS KHUSUS SEARCH BAR --- */
    .search-wrapper {
        position: relative;
        margin-right: 15px;
    }

    .search-input {
        padding: 8px 15px 8px 40px; /* Padding kiri besar untuk icon */
        border: 1px solid #ddd;
        border-radius: 20px;
        width: 250px;
        transition: all 0.3s ease;
        outline: none;
    }

    .search-input:focus {
        border-color: #4e73df;
        box-shadow: 0 0 8px rgba(78, 115, 223, 0.2);
        width: 280px;
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

<div class="stats-container">
    <div class="stat-card stat-blue">
        <div class="stat-content">
            <h4>Total Pegawai</h4>
            <span class="number"><?php echo $total_pegawai; ?></span>
        </div>
        <div class="stat-icon" style="color: white;">
            <i class="fas fa-users"></i>
        </div>
    </div>

    <div class="stat-card stat-green">
        <div class="stat-content">
            <h4>Pegawai Aktif</h4>
            <span class="number"><?php echo $jml_aktif; ?></span>
        </div>
        <div class="stat-icon" style="color: white;">
            <i class="fas fa-user-check"></i>
        </div>
    </div>

    <div class="stat-card stat-red">
        <div class="stat-content">
            <h4>Non Aktif</h4>
            <span class="number"><?php echo $jml_non_aktif; ?></span>
        </div>
        <div class="stat-icon" style="color: white;">
            <i class="fas fa-user-times"></i>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>DATA PEGAWAI</h3>
        
        <div class="card-actions" style="display: flex; align-items: center;">
            
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="searchInput" class="search-input" placeholder="Cari nama, jabatan...">
            </div>

            <a href="tambah_pegawai.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Pegawai
            </a>
            <button class="btn btn-secondary" onclick="window.print()" style="margin-left: 5px;">
                <i class="fas fa-print"></i> Cetak
            </button>
        </div>
    </div>

    <div class="card-body">
        <div class="table-container">
            <table class="data-table" id="tablePegawai">
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
                            <tr class="data-row">
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
                                    <a href="detail_pegawai.php?id=<?php echo $row['id_pegawai']; ?>" class="btn-action btn-view" title="Detail"><i class="fas fa-eye"></i></a>
                                    <a href="edit_pegawai.php?id=<?php echo $row['id_pegawai']; ?>" class="btn-action btn-edit" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="hapus_pegawai.php?id=<?php echo $row['id_pegawai']; ?>" class="btn-action btn-delete" title="Hapus" onclick="return confirm('Yakin hapus pegawai ini?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        
                        <tr id="noDataRow" style="display: none;">
                            <td colspan="7" class="text-center" style="padding: 20px; color: #666;">
                                Data tidak ditemukan
                            </td>
                        </tr>

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

<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#tablePegawai tbody .data-row');
    let noDataRow = document.getElementById('noDataRow');
    let hasVisibleRow = false;

    rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        if(text.includes(filter)) {
            row.style.display = '';
            hasVisibleRow = true;
        } else {
            row.style.display = 'none';
        }
    });

    if (noDataRow) {
        noDataRow.style.display = (hasVisibleRow || filter === '') ? 'none' : '';
    }
});
</script>