<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../guest/login.php");
    exit();
}

include '../../db_connect.php'; 

$is_edit = false;
$article_id = $_GET['id'] ?? null;
$page_title = "Buat Artikel Baru";
$article = ['title' => '', 'content' => '', 'image_path' => '', 'status' => 'draft'];
$error_message = "";

if ($article_id && isset($_GET['action']) && $_GET['action'] == 'edit') {
    $article_id = $conn->real_escape_string($article_id);
    $sql = "SELECT * FROM articles WHERE article_id = '$article_id' LIMIT 1";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $article = $result->fetch_assoc();
        $is_edit = true;
        $page_title = "Edit Artikel: " . htmlspecialchars(substr($article['title'], 0, 40)) . "...";
    } else {
        $error_message = "Artikel tidak ditemukan.";
    }
}

include '../../components/header_admin.php'; 
?>
<link rel="stylesheet" href="admin.css">
<body class="article-form-admin">

<header class="admin-header">
    <section class="header-banner">
        <h1><?= $page_title; ?></h1>
        <p>Tulis dan kelola konten edukasi Anda.</p>
        <link rel="stylesheet" href="admin.css">
    </section>
</header>

<main class="admin-main">
    
    <section class="article-form-container">

        <?php if (!empty($error_message)): ?>
            <p class="error-message" style="color: red;"><?= $error_message; ?></p>
        <?php endif; ?>

        <form action="process-article.php" method="POST" enctype="multipart/form-data" class="article-form">
            
            <input type="hidden" name="article_id" value="<?= $is_edit ? $article_id : ''; ?>">
            <input type="hidden" name="action" value="<?= $is_edit ? 'update' : 'create'; ?>">

            <label for="title">Judul Artikel</label>
            <input type="text" id="title" name="title" required value="<?= htmlspecialchars($article['title']); ?>">

            <label for="content">Isi Artikel</label>
            <textarea id="content" name="content" rows="15" required><?= htmlspecialchars($article['content']); ?></textarea>
            
            <label for="image">Gambar Utama</label>
            <?php if ($is_edit && $article['image_path']): ?>
                <p>Gambar saat ini: <img src="../../Asset/<?= htmlspecialchars($article['image_path']); ?>" style="width: 150px; display: block; margin: 10px 0;"></p>
                <input type="hidden" name="current_image" value="<?= htmlspecialchars($article['image_path']); ?>">
            <?php endif; ?>
            <label class="file-upload-wrapper">
    <span class="file-upload-btn">Pilih Gambar</span>
    <span class="file-upload-text">Belum ada file</span>
    <input type="file" id="image" name="image" accept="image/*" onchange="showFileName(this)">
</label>

            
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="draft" <?= $article['status'] == 'draft' ? 'selected' : ''; ?>>Draft</option>
                <option value="published" <?= $article['status'] == 'published' ? 'selected' : ''; ?>>Published</option>
            </select>
            
            <div class="form-actions">
                <button type="submit" class="primary-btn"><?= $is_edit ? 'Simpan Perubahan' : 'Publish Artikel'; ?></button>
                <a href="manage-artikel.php"><button type="button" class="secondary-btn">Batal</button></a>
            </div>

        </form>
    </section>

</main>
</body>

<?php 
$conn->close();
include '../../components/footer.php'; 
?>
