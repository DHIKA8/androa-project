<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../guest/login.php");
    exit();
}

include '../../db_connect.php'; 

$user_id = $_SESSION['user_id'];
$error_message = "";
$success_message = "";

$sql_fetch = "SELECT * FROM users WHERE user_id = '$user_id' LIMIT 1";
$result_fetch = $conn->query($sql_fetch);

if ($result_fetch->num_rows > 0) {
    $user_data = $result_fetch->fetch_assoc();
} else {
    header("Location: logout.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $full_name = $conn->real_escape_string(trim($_POST['full_name']));
    $username = $conn->real_escape_string(trim($_POST['username']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $bio = $conn->real_escape_string(trim($_POST['bio']));
    $new_password = $_POST['new_password'];
    $current_avatar = $user_data['avatar_path'];

    $update_fields = [];
    
    if ($full_name !== $user_data['full_name']) {
        $update_fields[] = "full_name = '$full_name'";
    }
    if ($bio !== $user_data['bio']) {
        $update_fields[] = "bio = '$bio'";
    }
    if (!empty($new_password)) {
        if (strlen($new_password) >= 6) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_fields[] = "password = '$hashed_password'";
        } else {
            $error_message = "Password baru harus minimal 6 karakter.";
        }
    }
    
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['profile_photo']['tmp_name'];
        $file_ext = strtolower(pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png'];
        
        if (in_array($file_ext, $allowed_ext)) {
            $new_file_name = "avatar_" . $user_id . "_" . time() . "." . $file_ext;
            $upload_dir = "../../Asset/avatars/"; 
            
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
                $new_avatar_path = $upload_dir . $new_file_name;
                $update_fields[] = "avatar_path = '$new_avatar_path'";
                
                if ($current_avatar !== '/assets/profile-default.jpg' && file_exists($current_avatar)) {
                    unlink($current_avatar);
                }
            } else {
                $error_message = "Gagal mengunggah foto profil.";
            }
        } else {
            $error_message = "Ekstensi file tidak diizinkan. Hanya JPG, JPEG, dan PNG.";
        }
    }
    if (empty($error_message) && !empty($update_fields)) {
        $set_clause = implode(", ", $update_fields);
        $sql_update = "UPDATE users SET $set_clause WHERE user_id = '$user_id'";
        
        if ($conn->query($sql_update) === TRUE) {
            $success_message = "Profil berhasil diperbarui!";

            $user_data = $conn->query($sql_fetch)->fetch_assoc();
        } else {
            $error_message = "Gagal memperbarui database: " . $conn->error;
        }
    } elseif (empty($error_message)) {
        $success_message = "Tidak ada perubahan yang dilakukan.";
    }
}

include '../../components/header_admin.php'; 
?>

<link rel="stylesheet" href="admin.css">
<body class="edit-profile-admin-body">

<header class="profile-header">
    <section class="header-banner">
        <h1>Edit Profil</h1>
        <p>Ubah informasi akun Anda.</p>
    </section>
</header>
<main class="edit-profile-main">
    <section class="edit-profile-box">
        
        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?= $error_message; ?></p>
        <?php endif; ?>
        
        <?php if (!empty($success_message)): ?>
            <p class="success-message"><?= $success_message; ?></p>
        <?php endif; ?>
        
        <h2>Ubah Detail Profil</h2>
        
 <form method="POST" action="" enctype="multipart/form-data">
    
    <div class="profile-avatar-upload">
        <img src="<?= htmlspecialchars($user_data['avatar_path'] ?? '../../Asset/profile-default.jpg'); ?>" class="profile-avatar-large">
        <label for="profile-photo" style="position:static; transform:none; pointer-events:auto;">Ganti Foto Profil</label>
        <input id="profile-photo" type="file" name="profile_photo" accept="image/*">
    </div>

    <div class="input-group">
        <input id="full_name" type="text" name="full_name" value="<?= htmlspecialchars($user_data['full_name']); ?>" placeholder=" " required>
        <label for="full_name">Nama Lengkap</label>
    </div>

    <div class="input-group">
        <input id="username" type="text" name="username" value="<?= htmlspecialchars($user_data['username']); ?>" placeholder=" " required>
        <label for="username">Username</label>
    </div>

    <div class="input-group">
        <input id="email" type="email" name="email" value="<?= htmlspecialchars($user_data['email']); ?>" placeholder=" " required>
        <label for="email">Email</label>
    </div>

    <div class="input-group">
        <textarea id="bio" name="bio" placeholder=" "><?= htmlspecialchars($user_data['bio']); ?></textarea>
        <label for="bio">Bio Singkat</label>
    </div>

    <div class="input-group">
        <input id="new_password" type="password" name="new_password" placeholder=" ">
        <label for="new_password">Password Baru (Opsional)</label>
    </div>

    <div class="action-buttons">
        <button type="submit" class="primary-btn">Simpan Perubahan</button>
        <a href="profile.php"><button type="button" class="secondary-btn">Batal</button></a>
    </div>
</form>
    </section>
</main>
</body>
<?php 
$conn->close();
include '../../components/footer.php'; 
?>
