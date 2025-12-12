<?php
// Data menu
$menu_items = array(
    'dashboard' => array(
        'icon' => 'fas fa-home',
        'title' => 'Dashboard',
        'link' => 'index.php',
        'active' => (basename($_SERVER['PHP_SELF']) == 'index.php')
    ),
    'data_barang' => array(
        'icon' => 'fas fa-box',
        'title' => 'Data Barang',
        'link' => 'index.php?page=data_barang',
        'active' => (isset($_GET['page']) && $_GET['page'] == 'data_barang') ||
            (basename($_SERVER['PHP_SELF']) == 'tambah.php') ||
            (basename($_SERVER['PHP_SELF']) == 'edit.php')
    ),
    'pegawai' => array(
        'icon' => 'fas fa-user',
        'title' => 'Pegawai',
        'link' => 'index.php?page=pegawai',
        'active' => isset($_GET['page']) && $_GET['page'] == 'pegawai'
    ),
    'kategori' => array(
        'icon' => 'fas fa-tags',
        'title' => 'Kategori',
        'link' => 'index.php?page=kategori',
        'active' => isset($_GET['page']) && $_GET['page'] == 'kategori'
    ),
    'laporan' => array(
        'icon' => 'fas fa-chart-bar',
        'title' => 'Laporan',
        'link' => 'index.php?page=laporan',
        'active' => isset($_GET['page']) && $_GET['page'] == 'laporan'
    ),
    'pengaturan' => array(
        'icon' => 'fas fa-cog',
        'title' => 'Pengaturan',
        'link' => 'index.php?page=pengaturan',
        'active' => isset($_GET['page']) && $_GET['page'] == 'pengaturan'
    ),
);
?>

<!-- Sidebar Menu -->
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
    </nav>

    <div class="sidebar-footer">
        <a href="logout.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>
            <span>Keluar</span>
        </a>
    </div>
</aside>