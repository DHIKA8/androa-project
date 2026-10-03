<?php
session_start();

if (!isset($_SESSION['user_id']) || (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin')) {

}

include '../../db_connect.php'; 
include '../../components/header_admin.php'; 

$kategori_pilihan = isset($_GET['category']) ? $_GET['category'] : 'Semua';
$list_kategori = ['Semua', 'Casual', 'Sporty', 'Formal', 'Experimental', 'Streetwear'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Gallery - Androa</title>

    <link rel="stylesheet" href="admin.css">

</head>
<body class="manage-gallery-admin"> 

<header class="admin-header">
    <section class="header-banner">
        <h1>Manage Community Gallery</h1>
        <p>Pantau dan kelola semua postingan user.</p>
    </section>
</header>

<main class="admin-main">

    <section class="gallery-list">
        <div class="gallery-header-section">
            <div class="category-filter">
                <?php foreach ($list_kategori as $kat): ?>
                    <?php 
                        $active_class = ($kategori_pilihan == $kat) ? 'active' : '';
                        $url_filter = "?category=" . urlencode($kat);
                    ?>
                    <a href="<?php echo $url_filter; ?>" class="<?php echo $active_class; ?>">
                        <?php echo $kat; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="gallery-grid">
            <?php 
            $sql = "SELECT gp.*, u.username, u.avatar_path 
                    FROM gallery_posts gp
                    JOIN users u ON gp.user_id = u.user_id";

            if ($kategori_pilihan != 'Semua') {
                $cat_safe = $conn->real_escape_string($kategori_pilihan);
                $sql .= " WHERE gp.style_category = '$cat_safe'";
            }

            $sql .= " ORDER BY gp.created_at DESC";
            $result_posts = $conn->query($sql);

            if ($result_posts && $result_posts->num_rows > 0) {
                while ($post = $result_posts->fetch_assoc()) {
                    $post_id = $post['post_id'];
                    $caption = htmlspecialchars($post['caption']);
                    $image_path = "../../Asset/" . $post['image_path'];
                    $username = htmlspecialchars($post['username']);
                    $category = htmlspecialchars($post['style_category']);
                    
                    $likes_q = $conn->query("SELECT COUNT(*) as total FROM likes WHERE post_type='gallery' AND post_id='$post_id'");
                    $likes = ($likes_q) ? $likes_q->fetch_assoc()['total'] : 0;

                    $comments_q = $conn->query("SELECT COUNT(*) as total FROM comments WHERE post_type='gallery' AND post_id='$post_id'");
                    $comments = ($comments_q) ? $comments_q->fetch_assoc()['total'] : 0;
            ?>
                    <article class="card">
                        <img src="<?= $image_path; ?>" alt="Post by <?= $username; ?>" class="card-img" onerror="this.src='../../Asset/default.jpg'">
                        
                        <div class="post-info">
                            <div class="meta-info">
                                <span>@<?= $username; ?></span>
                                <span class="cat-badge"><?= $category; ?></span>
                            </div>

                            <p class="ig-caption">
                                <?= mb_strimwidth($caption, 0, 80, "..."); ?>
                            </p>
                            
                            <div class="stats">
                                ❤️ <?= $likes; ?> &nbsp; 💬 <?= $comments; ?>
                            </div>

                            <div class="admin-actions">
                                <button type="button" 
                                        class="admin-btn danger" 
                                        onclick="openDeleteModal(<?= $post_id; ?>, '<?= $username; ?>')">
                                    <span></span> Delete Post
                                </button>
                            </div>
                        </div>
                    </article>
            <?php 
                } 
            } else { 
            ?>
                <div class="empty-state">
                    <h3>Tidak ada postingan ditemukan untuk kategori ini.</h3>
                </div>
            <?php } ?>
        </div>
    </section>

</main>

<div id="deleteModal" class="modal-overlay">
    <div class="modal-box">
        <h3>Konfirmasi Hapus</h3>
        <p>Apakah Anda yakin ingin menghapus postingan milik <b id="modalUser">User</b>?</p>
        <p style="color: #999; font-size: 0.8rem; margin-top: 5px;">Tindakan ini tidak bisa dibatalkan.</p>
        
        <div class="modal-actions">
            <button class="btn-cancel" onclick="closeDeleteModal()">Batal</button>
            <a href="#" id="confirmDeleteLink" class="btn-confirm">Ya, Hapus</a>
        </div>
    </div>
</div>

<script>
    // Fungsi Buka Modal
    function openDeleteModal(postId, username) {
        document.getElementById('modalUser').innerText = '@' + username;
        document.getElementById('confirmDeleteLink').href = "gallery-action.php?action=delete&id=" + postId;
        document.getElementById('deleteModal').style.display = 'flex';
    }

    // Fungsi Tutup Modal
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }

    // Tutup modal jika klik di luar kotak
    window.onclick = function(event) {
        var modal = document.getElementById('deleteModal');
        if (event.target == modal) {
            closeDeleteModal();
        }
    }
</script>

</body>
</html>
<?php include '../../components/footer.php'; ?>