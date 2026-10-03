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
        <a href="/androa-doang/pages/user/home.php">
            <img src="/androa-doang/Asset/logoandroa.png" class="logo">
        </a>
        <span class="separator"></span>
        <a href="/androa-doang/pages/user/home.php" class="navbar-title">Androa</a>
    </div>

    <div class="navbar-right">

        <div class="navigation">
            <a href="/androa-doang/pages/user/edustyle.php">EduStyle</a>
            <a href="/androa-doang/pages/user/gallery.php">Gallery</a>
            <a href="/androa-doang/pages/user/about.php">About</a>
        </div>

        <div class="grupauth">
            <a href="profile.php" class="user-name">
                Hi, <?php echo htmlspecialchars($_SESSION['user_username'] ?? 'Guest'); ?>
            </a>
            <a href="/androa-doang/pages/user/logout.php" class="auth-btn">Logout</a>
        </div>

    </div>
</nav>
