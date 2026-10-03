<?php
// Cek apakah session sudah dimulai, jika belum, mulai sessionnya.
// Ini penting agar variabel $_SESSION bisa dibaca di file ini.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<link rel="stylesheet" href="../../components/components.css">

<nav class="navbar">
    <div class="navbar-left">
        <a href="home.php">
            <img src="../../Asset/logoandroa.png" alt="AndroaLogo" class="logo">
        </a>
        <span class="separator"></span>
        <a href="/androa-doang/pages/admin/home.php" class="navbar-title">Androa</a>
    </div>

    <div class="navbar-right">
        <div class="navigation">
            <a href="/androa-doang/pages/admin/edustyle.php">EduStyle</a>
            <a href="/androa-doang/pages/admin/gallery.php">Gallery</a>
            <a href="/androa-doang/pages/admin/about.php">About</a>
        </div>

        <div class="grupauth">
            <div class="admin-dropdown">
                <button class="admin-dropbtn">Manage ▾</button>
                <div class="admin-dropdown-content">
                    <a href="/androa-doang/pages/admin/user-list.php">Manage Users</a>
                    <a href="/androa-doang/pages/admin/manage-gallery.php">Manage Gallery</a>
                    <a href="/androa-doang/pages/admin/manage-artikel.php">Manage Articles</a>
                </div>
            </div>

            <a href="profile.php" class="user-name">
                Hi, <?= htmlspecialchars($_SESSION['user_username'] ?? 'Guest'); ?>
            </a>

            <a href="logout.php" class="auth-btn">Logout</a>
        </div>
    </div>
</nav>
