<?php
session_start();

// Cek Login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../guest/login.php");
    exit();
}

include '../../db_connect.php'; 

if (!isset($_GET['post_id'])) {
    header("Location: profile.php"); 
    exit();
}

$post_id = $_GET['post_id'];
$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM gallery_posts WHERE post_id = '$post_id' AND user_id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "<script>alert('Postingan tidak ditemukan atau Anda tidak memiliki izin.'); window.location.href='profile.php';</script>";
    exit();
}

$post_data = $result->fetch_assoc();

$update_success = false;

if (isset($_POST['update_post'])) {
    $caption_raw = $_POST['caption'];
    $caption = $conn->real_escape_string($caption_raw); 
    
    $category = $conn->real_escape_string($_POST['style_category']);
    
    $image_query_part = "";
    
    if (!empty($_FILES['new_image']['name'])) {
        $target_dir = "../../Asset/"; 
        $file_name = time() . '_' . basename($_FILES["new_image"]["name"]); 
        $target_file = $target_dir . $file_name;
        
        $check = getimagesize($_FILES["new_image"]["tmp_name"]);
        if($check !== false) {
            if (move_uploaded_file($_FILES["new_image"]["tmp_name"], $target_file)) {
                $image_query_part = ", image_path = '$file_name'";
            } else {
                echo "<script>alert('Gagal mengupload gambar.');</script>";
            }
        } else {
            echo "<script>alert('File bukan gambar valid.');</script>";
        }
    }

    $sql_update = "UPDATE gallery_posts 
                   SET caption = '$caption', 
                       style_category = '$category' 
                       $image_query_part 
                   WHERE post_id = '$post_id' AND user_id = '$user_id'";

    if ($conn->query($sql_update) === TRUE) {
        $update_success = true;
        
        $post_data['caption'] = htmlspecialchars($caption_raw);
        $post_data['style_category'] = htmlspecialchars($_POST['style_category']);
        
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

include '../../components/header_admin.php'; 
?>
<link rel="stylesheet" href="admin.css">

<body class="admin-edit-page">

<header class="profile-header">
    <section class="header-banner">
        <h1>Edit Postingan</h1>
    </section>
</header>

<style>
    .modal-overlay {
        display: flex;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(2px);
        align-items: center;
        justify-content: center;
    }

    .modal-box {
        background-color: #fff;
        padding: 30px;
        border-radius: 8px;
        width: 90%;
        max-width: 400px;
        text-align: center;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        animation: slideDown 0.3s ease-out; 
        font-family: Arial, sans-serif;
    }

    @keyframes slideDown { 
        0% { transform: translateY(-50px); opacity: 0; } 
        100% { transform: translateY(0); opacity: 1; } 
    }

    .modal-box h3 {
        margin-top: 0;
        color: #333;
        font-size: 1.25rem;
        margin-bottom: 10px;
        font-weight: bold;
    }

    .modal-box p {
        color: #666;
        margin-bottom: 20px;
        line-height: 1.5;
    }

    .modal-actions {
        display: flex;
        justify-content: center;
    }

    .btn-confirm {
        background-color: #333;
        color: white;
        padding: 10px 30px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        transition: 0.2s;
        display: inline-block;
    }
    .btn-confirm:hover {
        background-color: #000;
    }
</style>

<main>
    
    <?php if ($update_success): ?>
    <div class="modal-overlay">
        <div class="modal-box">
            <div style="margin-bottom: 15px;">
                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="10" stroke="#28a745" stroke-width="2"/>
                    <path d="M8 12L11 15L16 9" stroke="#28a745" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>

            <h3>Berhasil Diperbarui!</h3>
            <p>Postingan Anda telah berhasil diperbarui dan disimpan ke database.</p>
            
            <div class="modal-actions">
                <a href="profile.php" class="btn-confirm">OK</a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="edit-container">
        <form action="" method="POST" enctype="multipart/form-data">
            
            <div class="form-group">
                <label>Gambar Saat Ini:</label><br>
                <img src="../../Asset/<?= htmlspecialchars($post_data['image_path']); ?>" width="200" style="border-radius: 8px; margin-top:5px;">
            </div>
            <br>

            <div class="form-group">
                <label>Ganti Gambar (Opsional):</label><br>
                <input type="file" name="new_image" accept="image/*">
            </div>
            <br>

            <div class="form-group">
                <label>Kategori Gaya:</label><br>
                <select name="style_category" required>
                    <option value="Casual" <?= ($post_data['style_category'] == 'Casual') ? 'selected' : ''; ?>>Casual</option>
                    <option value="Sporty" <?= ($post_data['style_category'] == 'Sporty') ? 'selected' : ''; ?>>Sporty</option>
                    <option value="Formal" <?= ($post_data['style_category'] == 'Formal') ? 'selected' : ''; ?>>Formal</option>
                    <option value="Experimental" <?= ($post_data['style_category'] == 'Experimental') ? 'selected' : ''; ?>>Experimental</option>
                    <option value="Streetwear" <?= ($post_data['style_category'] == 'Streetwear') ? 'selected' : ''; ?>>Streetwear</option>
                </select>
            </div>
            <br>

            <div class="form-group">
                <label>Caption:</label><br>
                <textarea name="caption" rows="4" cols="50" required><?= htmlspecialchars($post_data['caption']); ?></textarea>
            </div>
            <br>

            <button type="submit" name="update_post" >Simpan Perubahan</button>
            <a href="profile.php" style="margin-left: 10px; text-decoration: none; color: #333;">Batal</a>
        </form>
    </div>
</main>
</body>
<?php 
$conn->close();
include '../../components/footer.php'; 
?>