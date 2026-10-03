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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $caption = $conn->real_escape_string(trim($_POST['caption']));
    $style_category = $conn->real_escape_string($_POST['style_category']);
    
    if (isset($_FILES['outfit_photo']) && $_FILES['outfit_photo']['error'] === UPLOAD_ERR_OK) {
        
        $file_tmp = $_FILES['outfit_photo']['tmp_name'];
        $file_name = $_FILES['outfit_photo']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png'];
        
        if (in_array($file_ext, $allowed_ext)) {
            
            $new_file_name = "post_" . $user_id . "_" . time() . "." . $file_ext;
            $upload_dir = "../../Asset/gallery_uploads/"; 
            $target_file = $upload_dir . $new_file_name;
            $db_image_path = "gallery_uploads/" . $new_file_name; 

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            if (move_uploaded_file($file_tmp, $target_file)) {
                
                $insert_sql = "INSERT INTO gallery_posts (user_id, caption, image_path, style_category, is_approved) 
                               VALUES ('$user_id', '$caption', '$db_image_path', '$style_category', 0)";
                
                if ($conn->query($insert_sql) === TRUE) {
                    $success_message = "Unggahan Anda berhasil dikirim!";
                } else {
                    $error_message = "Gagal menyimpan data ke database: " . $conn->error;
                    unlink($target_file);
                }
                
            } else {
                $error_message = "Gagal memindahkan file ke direktori server.";
            }

        } else {
            $error_message = "Ekstensi file tidak valid. Hanya JPG, JPEG, dan PNG yang diizinkan.";
        }

    } else {
        $error_message = "Anda harus memilih foto outfit untuk diunggah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Androa – Upload Outfit</title>
    <link rel="stylesheet" href="user.css">
</head>
<body class="user-upload-page">
<?php include '../../components/header_user.php'; ?>

<header class="upload-header">
    <section class="header-banner">
        <h1>Upload Outfit Baru</h1>
        <p>Bagikan gaya Anda yang bebas gender ke komunitas!</p>
    </section>
</header>

<main class="upload-main">
    <section class="upload-form-box">
        
        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?= $error_message; ?></p>
        <?php endif; ?>
        
        <?php if (!empty($success_message)): ?>
            <p class="success-message"><?= $success_message; ?></p>
        <?php endif; ?>
        
        <h2>Form Unggahan</h2>
        
        <form method="POST" action="" enctype="multipart/form-data">
            
            <label for="outfit_photo">Foto Outfit Anda *</label>
            <input id="outfit_photo" type="file" name="outfit_photo" accept="image/*" required>
            
            <label for="caption">Deskripsi Gaya *</label>
            <textarea id="caption" name="caption" required placeholder="Jelaskan inspirasi dan komponen outfit Anda..."></textarea>
            
            <label for="style_category">Kategori Gaya *</label>
            <select id="style_category" name="style_category" required>
                <option value="">Pilih kategori…</option>
                <option value="Casual">Casual</option>
                <option value="Sporty">Sporty</option>
                <option value="Formal">Formal</option>
                <option value="Streetwear">Streetwear</option>
                <option value="Experimental">Experimental</option>
            </select>
            
            <button type="submit" class="primary-btn">Unggah ke Galeri</button>
        </form>
    </section>
</main>

<?php 
$conn->close();
include '../../components/footer.php'; 
?>
</body>
</html>