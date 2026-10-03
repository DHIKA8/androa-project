<?php

session_start();

if (isset($_SESSION['user_id'])) {
    $current_role = $_SESSION['user_role'] ?? 'user';
    
    if ($current_role === 'admin') {
        header("Location: ../admin/home.php");
    } else {
        header("Location: ../user/home.php");
    }
    exit();
}

include '../../db_connect.php'; 

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password_input = $_POST['password'];

    $sql = "SELECT user_id, username, email, password, role FROM users WHERE email = '$email' AND is_active = 1 LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
       $hashed_password_db = $user['password'];

        if ($password_input === $hashed_password_db) {

            
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_username'] = $user['username']; 
            $_SESSION['user_role'] = trim($user['role']); 

            
            if ($_SESSION['user_role'] === 'admin') {
                header("Location: ../admin/home.php");
            } else {
                header("Location: ../user/home.php");
            }
            exit();

        } else {
            $error_message = "Email atau Password salah.";
        }
    } else {
        $error_message = "Email tidak terdaftar atau akun tidak aktif.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Androa – Login</title>
    <link rel="stylesheet" href="guest.css">
</head>
<body class="login-page">
<?php include '../../components/header.php'; ?>

<main class="auth-main">
    <section class="login-box">
        <h2>Masuk ke Akun Anda</h2>
        
        <?php if (!empty($error_message)): ?>
            <p class="error-message" style="color: red; font-weight: bold;"><?= $error_message; ?></p>
        <?php endif; ?>
        
    <form method="POST" action="">
    <div class="field">
        <input id="email" type="email" name="email" required placeholder=" ">
        <label for="email">Email</label>
    </div>

    <div class="field">
        <input id="password" type="password" name="password" required placeholder=" ">
        <label for="password">Password</label>
    </div>

    <button type="submit" class="primary-btn">Login</button>
    </form>

        <p class="switch-auth">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
        <p class="forgot-password"><a href="forgot-password.php">Lupa Password?</a></p>
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