<?php
// 1. Query Data Utama (Tabel Suppliers)
include 'koneksi.php';
$query = "SELECT * FROM suppliers ORDER BY id_supplier DESC";
$result = mysqli_query($koneksi, $query);

// 2. Query Khusus Statistik (Berdasarkan Model Bisnis)
$query_stats = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN model_bisnis = 'produsen' THEN 1 ELSE 0 END) as produsen,
    SUM(CASE WHEN model_bisnis IN ('grosir', 'importir') THEN 1 ELSE 0 END) as distributor
    FROM suppliers";
$result_stats = mysqli_query($koneksi, $query_stats);
$stats = mysqli_fetch_assoc($result_stats);

$total_supplier = $stats['total'] ?? 0;
$jml_produsen = $stats['produsen'] ?? 0;
$jml_distributor = $stats['distributor'] ?? 0;
?>

<style>
    /* --- CSS TETAP SAMA DENGAN MODEL SEBELUMNYA --- */
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
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-left: 5px solid #ddd;
        transition: transform 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-blue {
        border-left-color: #4e73df;
    }

    .stat-green {
        border-left-color: #1cc88a;
    }

    .stat-orange {
        border-left-color: #f6c23e;
    }

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

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: #f8f9fc;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    /* Search Bar */
    .search-wrapper {
        position: relative;
        margin-right: 15px;
    }

    .search-input {
        padding: 8px 15px 8px 40px;
        border: 1px solid #ddd;
        border-radius: 20px;
        width: 250px;
        outline: none;
    }

    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #aaa;
    }

    /* Tabel Style */
    .badge-info {
        background: #36b9cc;
        color: white;
        padding: 4px 8px;
        border-radius: 5px;
        font-size: 11px;
    }

    .badge-dark {
        background: #5a5c69;
        color: white;
        padding: 4px 8px;
        border-radius: 5px;
        font-size: 11px;
    }
</style>

<div class="stats-container">
    <div class="stat-card stat-blue">
        <div class="stat-content">
            <h4>Total Supplier</h4>
            <span class="number"><?php echo $total_supplier; ?></span>
        </div>
        <div class="stat-icon" style="color: #4e73df;">
            <i class="fas fa-truck" style="color: white;"></i>
        </div>
    </div>

    <div class="stat-card stat-green">
        <div class="stat-content">
            <h4>Produsen</h4>
            <span class="number"><?php echo $jml_produsen; ?></span>
        </div>
        <div class="stat-icon" style="color: #1cc88a;">
            <i class="fas fa-boxes" style="color: white;"></i>
        </div>
    </div>

    <div class="stat-card stat-orange">
        <div class="stat-content">
            <h4>Grosir/Importir</h4>
            <span class="number"><?php echo $jml_distributor; ?></span>
        </div>
        <div class="stat-icon" style="color: #f6c23e;">
            <i class="fas fa-boxes" style="color: white;"></i>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>DATA SUPPLIER</h3>

        <div class="card-actions" style="display: flex; align-items: center;">
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="searchInput" class="search-input" placeholder="Cari nama, kota, produk...">
            </div>

            <a href="tambah_supplier.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Supplier
            </a>
            <button class="btn btn-secondary" onclick="window.print()" style="margin-left: 5px;">
                <i class="fas fa-print"></i> Cetak
            </button>
        </div>
    </div>

    <div class="card-body">
        <div class="table-container" style="overflow-x: auto;">
            <table class="data-table" id="tableSupplier">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Perusahaan</th>
                        <th>Kontak Person</th>
                        <th>Info Kontak</th>
                        <th>Lokasi</th>
                        <th>Jenis & Model</th>
                        <th width="12%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php $no = 1;
                        while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr class="data-row">
                                <td><?php echo $no++; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($row['nama_perusahaan']); ?></strong><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($row['keterangan']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($row['nama_kontak']); ?></td>
                                <td>
                                    <small>
                                        <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($row['email']); ?><br>
                                        <i class="fas fa-phone"></i> <?php echo htmlspecialchars($row['telepon']); ?>
                                    </small>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($row['kota']); ?>,
                                    <?php echo htmlspecialchars($row['provinsi']); ?><br>
                                    <small><?php echo htmlspecialchars($row['kode_pos']); ?></small>
                                </td>
                                <td>
                                    <span class="badge-info"><?php echo ucfirst($row['jenis_produk']); ?></span><br>
                                    <span class="badge-dark" style="display:inline-block; margin-top:4px;">
                                        <?php echo ucfirst($row['model_bisnis']); ?>
                                    </span>
                                </td>
                                <td class="action-buttons">
                                    <a href="detail_supplier.php?id=<?php echo $row['id_supplier']; ?>"
                                        class="btn-action btn-view" title="Detail"><i class="fas fa-eye"></i></a>
                                    <a href="edit_supplier.php?id=<?php echo $row['id_supplier']; ?>"
                                        class="btn-action btn-edit" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="hapus_supplier.php?id=<?php echo $row['id_supplier']; ?>"
                                        class="btn-action btn-delete" title="Hapus"
                                        onclick="return confirm('Hapus supplier ini?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>

                        <tr id="noDataRow" style="display: none;">
                            <td colspan="7" class="text-center" style="padding: 20px; color: #666;">Data tidak ditemukan
                            </td>
                        </tr>

                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-truck-loading fa-3x"></i>
                                    <h4>Belum ada data supplier</h4>
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
    // Fungsi Search Otomatis (Client Side)
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#tableSupplier tbody .data-row');
        let noDataRow = document.getElementById('noDataRow');
        let hasVisibleRow = false;

        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            if (text.includes(filter)) {
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