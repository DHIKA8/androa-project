<?php
session_start();
include '../../db_connect.php'; 

$message = "";
$message_class = ""; 
$show_success_popup = false; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // 1. Cek Validasi Password
    if ($new_password !== $confirm_password) {
        $message = "Konfirmasi password tidak cocok.";
        $message_class = "error";
    } else {
        // 2. Cek apakah email ada di database
        $check_sql = "SELECT user_id FROM users WHERE email = '$email' LIMIT 1";
        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows > 0) {
            // 3. Update Password
            $final_password = $conn->real_escape_string($new_password);
            $update_sql = "UPDATE users SET password = '$final_password' WHERE email = '$email'";
            
            if ($conn->query($update_sql)) {
                $show_success_popup = true;
                
            } else {
                $message = "Terjadi kesalahan sistem saat mengupdate data.";
                $message_class = "error";
            }
        } else {
            $message = "Email tidak ditemukan / belum terdaftar.";
            $message_class = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password Langsung - Androa</title>
    <link rel="stylesheet" href="guest.css">
</head>
<body class="login-page">
<?php include '../../components/header.php'; ?>

<main class="auth-main">
    <section class="login-box">
        <h2>Ganti Password</h2>
        
        <p class="reset-info">
            Masukkan email Anda dan password baru yang diinginkan.
        </p>

        <?php if (!empty($message)): ?>
            <div class="message-box <?= $message_class; ?>">
                <?= $message; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="field">
                <input id="email" type="email" name="email" required placeholder=" ">
                <label for="email">Email Terdaftar</label>
            </div>

            <div class="field">
                <input id="new_password" type="password" name="new_password" required placeholder=" ">
                <label for="new_password">Password Baru</label>
            </div>

            <div class="field">
                <input id="confirm_password" type="password" name="confirm_password" required placeholder=" ">
                <label for="confirm_password">Konfirmasi Password Baru</label>
            </div>

            <button type="submit" class="primary-btn">Ubah Password</button>
        </form>

        <a href="login.php" class="back-link">← Batal & Kembali ke Login</a>
    </section>
</main>

<?php if ($show_success_popup): ?>
<div class="popup-overlay" style="display: flex;">
    <div class="popup-box">
        <div class="popup-title">Berhasil!</div>
        <p class="popup-message">Password Anda telah berhasil diperbarui. Silakan login kembali.</p>
        
        <button class="primary-btn" onclick="window.location.href='login.php'">
            Login Sekarang
        </button>
    </div>
</div>
<?php endif; ?>

<?php include '../../components/footer.php'; ?>
</body>
</html>