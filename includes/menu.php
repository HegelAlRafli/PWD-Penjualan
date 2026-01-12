<?php
// 1. Ambil nilai page saat ini biar kodingan di bawah lebih rapi
$current_page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Data menu
$menu_items = array(
    'dashboard' => array(
        'icon' => 'fas fa-home',
        'title' => 'Dashboard',
        'link' => 'index.php',
        'active' => (!isset($_GET['page']) && basename($_SERVER['PHP_SELF']) == 'index.php') || (isset($_GET['page']) && $_GET['page'] == 'dashboard')
    ),
    'data_barang' => array(
        'icon' => 'fas fa-box',
        'title' => 'Data Barang',
        'link' => 'index.php?page=data_barang',
        'active' => $current_page == 'data_barang' ||
            basename($_SERVER['PHP_SELF']) == 'tambah.php' ||
            basename($_SERVER['PHP_SELF']) == 'edit.php' ||
            basename($_SERVER['PHP_SELF']) == 'detail_barang.php'
    ),
    'data_pegawai' => array(
        'icon' => 'fas fa-user',
        'title' => 'Pegawai',
        'link' => 'index.php?page=data_pegawai',
        'active' => $current_page == 'data_pegawai' ||
            basename($_SERVER['PHP_SELF']) == 'tambah_pegawai.php' ||
            basename($_SERVER['PHP_SELF']) == 'edit_pegawai.php' ||
            basename($_SERVER['PHP_SELF']) == 'detail_pegawai.php'
    ),
    // --- TAMBAHAN MENU SUPPLIER ---
    'data_supplier' => array(
        'icon' => 'fas fa-truck',
        'title' => 'Data Supplier',
        'link' => 'index.php?page=data_supplier',
        'active' => $current_page == 'data_supplier' ||
            basename($_SERVER['PHP_SELF']) == 'tambah_supplier.php' ||
            basename($_SERVER['PHP_SELF']) == 'edit_supplier.php' ||
            basename($_SERVER['PHP_SELF']) == 'detail_supplier.php'
    ),
    // // ------------------------------
    // 'kategori' => array(
    //     'icon' => 'fas fa-tags',
    //     'title' => 'Kategori',
    //     'link' => 'index.php?page=kategori',
    //     'active' => $current_page == 'kategori'
    // ),
    // 'laporan' => array(
    //     'icon' => 'fas fa-chart-bar',
    //     'title' => 'Laporan',
    //     'link' => 'index.php?page=laporan',
    //     'active' => $current_page == 'laporan'
    // ),
    // 'pengaturan' => array(
    //     'icon' => 'fas fa-cog',
    //     'title' => 'Pengaturan',
    //     'link' => 'index.php?page=pengaturan',
    //     'active' => $current_page == 'pengaturan'
    // ),
);
?>

<aside class="sidebar">
    <nav class="main-menu">
        <ul>
            <?php foreach ($menu_items as $item): ?>
                <li>
                    <a href="<?php echo $item['link']; ?>" class="<?php echo $item['active'] ? 'active' : ''; ?>">
                        <i class="<?php echo $item['icon']; ?>"></i>
                        <span><?php echo $item['title']; ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <ul class="logout-menu">
            <li>
                <a style="color: red;" href="logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>