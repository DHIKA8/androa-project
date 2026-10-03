<?php
session_start();

// Cek Autentikasi
if (!isset($_SESSION['user_id'])) {
    header("Location: ../guest/login.php");
    exit();
}

require '../../db_connect.php';

$user_id    = $_SESSION['user_id'];
$user_data  = null;
$user_posts = [];

// Ambil data profil user
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ? LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result_profile = $stmt->get_result();

if ($result_profile->num_rows > 0) {
    $user_data = $result_profile->fetch_assoc();
} else {
    header("Location: logout.php");
    exit();
}
$stmt->close();

// Ambil 4 unggahan terbaru yang sudah disetujui
$stmt2 = $conn->prepare("
    SELECT post_id, image_path, caption, created_at 
    FROM gallery_posts
    WHERE user_id = ? AND is_approved = 1
    ORDER BY created_at DESC
    LIMIT 4
");
$stmt2->bind_param("i", $user_id);
$stmt2->execute();
$result_posts = $stmt2->get_result();

while ($row = $result_posts->fetch_assoc()) {
    $user_posts[] = $row;
}

$stmt2->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Androa – My Profile</title>
    <link rel="stylesheet" href="../../components/components.css">
    <link rel="stylesheet" href="admin.css">
</head>

<body class="user-profile-page">

<?php include '../../components/header_admin.php'; ?>  
<header class="profile-header">
    <section class="header-banner">
        <h1>My Profile</h1>
        <p>Kelola informasi akun dan lihat riwayat unggahan outfit Anda.</p>
    </section>
</header>

<main>
    <section class="profile-box">
        <img
            src="<?= htmlspecialchars($user_data['avatar_path'] ?: '../../Asset/profile-default.jpg'); ?>"
            alt="Profile picture"
            class="profile-avatar"
        >

        <div class="profile-details">
            <h3><?= htmlspecialchars($user_data['full_name'] ?: $user_data['username']); ?></h3>
            <p class="username">@<?= htmlspecialchars($user_data['username']); ?></p>
            <p>Bio: <?= htmlspecialchars($user_data['bio'] ?: 'Belum ada bio.'); ?></p>
            <p>Email: <?= htmlspecialchars($user_data['email']); ?></p>
            <p class="meta">Member since: <?= date('F Y', strtotime($user_data['created_at'])); ?></p>

            <div class="profile-actions">
                <a href="edit-profile.php" class="btn edit-btn">Edit Profil</a>
                <a href="my-uploads.php" class="btn secondary-btn">Lihat Semua Unggahan</a>
            </div>
        </div>
    </section>

</main>

<?php 
$conn->close();
include '../../components/footer.php'; 
?>

</body>
</html>
