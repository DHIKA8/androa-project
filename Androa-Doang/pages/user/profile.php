<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../guest/login.php");
    exit();
}

include '../../db_connect.php'; 

$user_id = $_SESSION['user_id'];
$user_data = null;
$user_posts = [];

$sql_profile = "SELECT * FROM users WHERE user_id = '$user_id' LIMIT 1";
$result_profile = $conn->query($sql_profile);

if ($result_profile->num_rows > 0) {
    $user_data = $result_profile->fetch_assoc();
} else {
    header("Location: logout.php");
    exit();
}

$sql_posts = "SELECT * FROM gallery_posts 
              WHERE user_id = '$user_id' AND is_approved = 1 
              ORDER BY created_at DESC LIMIT 4";
$result_posts = $conn->query($sql_posts);

if ($result_posts->num_rows > 0) {
    while ($row = $result_posts->fetch_assoc()) {
        $user_posts[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Androa – My Profile</title>
    <link rel="stylesheet" href="../../components/components.css"> 
    <link rel="stylesheet" href="user.css"> 
</head>

<body class="user-profile-page"> <?php include '../../components/header_user.php'; ?>


<header class="profile-header">
    <section class="header-banner">
        <h1>My Profile</h1>
        <p>Kelola informasi akun Anda dan lihat riwayat unggahan Anda.</p>
    </section>
</header>

<main>
    <section class="profile-box">
        <img src="<?= htmlspecialchars($user_data['avatar_path'] ?? '../../Asset/profile-default.jpg'); ?>" class="profile-avatar">

        <div class="profile-details">
            <h3><?= htmlspecialchars($user_data['full_name'] ?? $user_data['username']); ?></h3>
            <p class="username">@<?= htmlspecialchars($user_data['username']); ?></p>
            <p>Bio: <?= htmlspecialchars($user_data['bio'] ?? 'Belum ada bio.'); ?></p>
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