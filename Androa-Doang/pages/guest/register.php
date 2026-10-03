<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: ../user/home.php");
    exit();
}

include '../../db_connect.php'; 

$error_message = "";
$full_name = $username = $email = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (!isset($_POST['full_name'], $_POST['username'], $_POST['email'], $_POST['password'], $_POST['confirm_password'])) {
        $error_message = "Data form tidak lengkap.";
    } else {
        
        $full_name = $conn->real_escape_string(trim($_POST['full_name']));
        $username = $conn->real_escape_string(trim($_POST['username']));
        $email = $conn->real_escape_string(trim($_POST['email']));
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validasi
        if (empty($full_name) || empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            $error_message = "Semua kolom wajib diisi.";
        } elseif ($password !== $confirm_password) {
            $error_message = "Konfirmasi password tidak cocok.";
        } elseif (strlen($password) < 6) {
            $error_message = "Password minimal 6 karakter.";
        } else {
            
            //  Cek Duplikasi
            $check_sql = "SELECT user_id FROM users WHERE email = '$email' OR username = '$username' LIMIT 1";
            $check_result = $conn->query($check_sql);

            if ($check_result && $check_result->num_rows > 0) {
                $error_message = "Username atau Email sudah terdaftar.";
            } else {
                
               $plain_password = $password;

                $insert_sql = "INSERT INTO users (full_name, username, email, password, role) 
                VALUES ('$full_name', '$username', '$email', '$plain_password', 'user')";

                
                if ($conn->query($insert_sql) === TRUE) {
                    header("Location: login.php?status=success");
                    exit();
                } else {
                    $error_message = "Gagal Mendaftar. SQL Error: " . $conn->error;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Androa – Register</title>
    <link rel="stylesheet" href="guest.css">
</head>
<body class="regis-guest">
<?php include '../../components/header.php'; ?>

<main class="auth-main">
    <section class="register-box">
        <h2>Buat Akun Baru</h2>
        
        <?php if (!empty($error_message)): ?>
            <p class="error-message" style="color: red; font-weight: bold;"><?= $error_message; ?></p>
        <?php endif; ?>
        
        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
             <p class="success-message">Pendaftaran berhasil! Silakan login.</p>
        <?php endif; ?>
        
        <form method="POST" action="">

            <div class="field">
                <input type="text" id="full_name" name="full_name" placeholder=" " required>
                <label for="full_name">Nama Lengkap</label>
        </div>

            <div class="field">
                <input type="text" id="username" name="username" placeholder=" " required>
                <label for="username">Username</label>
            </div>

            <div class="field">
                <input type="email" id="email" name="email" placeholder=" " required>
                <label for="email">Email</label>
            </div>

            <div class="field">
                <input type="password" id="password" name="password" placeholder=" " required>
                <label for="password">Password</label>
            </div>

            <div class="field">
                <input type="password" id="confirm_password" name="confirm_password" placeholder=" " required>
                <label for="confirm_password">Konfirmasi Password</label>
            </div>


            <div class="terms">
                <input type="checkbox" id="agree" required>
                <label for="agree">Saya setuju dengan <a href="#">Syarat & Ketentuan</a></label>
            </div>

            <button type="submit" class="primary-btn">Daftar</button>

        </form>

        <p class="switch-auth">Sudah punya akun? <a href="login.php">Masuk di sini</a></p>
    </section>
</main>

<?php 
if (isset($conn)) {
    $conn->close();
}
include '../../components/footer.php'; 
?>
</body>
</html>