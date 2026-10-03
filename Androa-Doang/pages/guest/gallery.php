<?php
session_start();

// 1. CEK LOGIN 
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Silakan login terlebih dahulu untuk mengakses Galeri Komunitas.";
    header("Location: ../guest/login.php"); 
    exit();
}

include '../../db_connect.php'; 
include '../../components/header_user.php'; 

$current_user_id = $_SESSION['user_id'];

// 2. LOGIKA FILTER KATEGORI
$kategori_pilihan = isset($_GET['category']) ? $_GET['category'] : 'Semua';
$list_kategori = ['Semua', 'Casual', 'Sporty', 'Formal', 'Experimental', 'Streetwear'];
?>
<link rel="stylesheet" href="guest.css">
<body class="admin-gallery-page">
<header class="gallery-header">
    <section class="header-banner">
        <h1>Community Gallery</h1>
        <p>Lihat dan berinteraksi dengan outfit bebas gender dari komunitas kami.</p>
    </section>
</header>

<main>
    <section class="upload-section">
        <h2>Share Your Outfit</h2>
        <a href="upload-gallery.php"><button class="primary-btn">Upload Foto Baru</button></a>
    </section>

    <section class="gallery-list">
        <h2>Latest Community Posts</h2>
        
        <div class="category-filter">
            <?php foreach ($list_kategori as $kat): ?>
                <?php 
                    $active_class = ($kategori_pilihan == $kat) ? 'active' : '';
                    $url_filter = "?category=" . urlencode($kat);
                ?>
                <a href="<?php echo $url_filter; ?>" class="filter-btn <?php echo $active_class; ?>">
                    <?php echo $kat; ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="gallery-grid">
            <?php 
            // 3. QUERY SQL
            $sql = "SELECT gp.*, u.username, u.avatar_path 
                    FROM gallery_posts gp
                    JOIN users u ON gp.user_id = u.user_id";

            if ($kategori_pilihan != 'Semua') {
                $cat_safe = $conn->real_escape_string($kategori_pilihan);
                $sql .= " WHERE gp.style_category = '$cat_safe'";
            }

            $sql .= " ORDER BY gp.created_at DESC";
            
            $result_posts = $conn->query($sql);

            // 4. CEK & TAMPILKAN DATA
            if ($result_posts->num_rows > 0) {
                while ($post = $result_posts->fetch_assoc()) {
                    $post_id = $post['post_id'];
                    $caption = htmlspecialchars($post['caption']);
                    $image_path = "../../Asset/" . $post['image_path']; 
                    $username = htmlspecialchars($post['username']);
                    
                    // --- Hitung Like ---
                    $q_likes = "SELECT COUNT(*) as total FROM likes WHERE post_type='gallery' AND post_id='$post_id'";
                    $res_likes = $conn->query($q_likes);
                    $total_likes = $res_likes->fetch_assoc()['total'];

                    // --- Cek Like User ---
                    $check_like = "SELECT * FROM likes WHERE user_id='$current_user_id' AND post_type='gallery' AND post_id='$post_id'";
                    $is_liked = $conn->query($check_like)->num_rows > 0;
                    $like_class = $is_liked ? 'liked' : ''; 

                    // --- Hitung Comment ---
                    $q_comments = "SELECT COUNT(*) as total FROM comments WHERE post_type='gallery' AND post_id='$post_id'";
                    $res_comments = $conn->query($q_comments);
                    $total_comments = $res_comments->fetch_assoc()['total'];
            ?>
                    
                    <article class="card">
                        <a href="detail-gallery.php?post_id=<?= $post_id; ?>">
                            <img src="<?= $image_path; ?>" 
                                 alt="<?= $caption; ?>" 
                                 style="width:100%; height:300px; object-fit:cover;"
                                 onerror="this.onerror=null;this.src='../../Asset/default.jpg';">
                        </a>
                        
                        <div class="post-info">
                            <p class="ig-caption">
                                <b>@<?= $username; ?></b>
                                <?= mb_strimwidth($caption, 0, 50, "..."); ?>
                            </p>
                            
                            <span style="font-size: 10px; background: #eee; padding: 2px 6px; border-radius: 4px; color: #555;">
                                <?= htmlspecialchars($post['style_category']); ?>
                            </span>

                            <div class="actions" style="margin-top: 10px;">
                                <a href="process_like_gallery.php?id=<?= $post_id; ?>" style="text-decoration:none;">
                                    <button class="like-button <?= $like_class; ?>" style="cursor:pointer;">
                                        <?= $is_liked ? '❤️' : '🤍'; ?> <?= $total_likes; ?>
                                    </button>
                                </a>

                                <a href="detail-gallery.php?post_id=<?= $post_id; ?>">
                                    <button class="comment-button">💬 <?= $total_comments; ?></button>
                                </a>
                            </div>
                        </div>
                    </article>

            <?php 
                } 
            } else { 
            ?>
                <div class="gallery-empty">
                    <h3>Tidak ada postingan di kategori "<?= htmlspecialchars($kategori_pilihan); ?>"</h3>
                    <p>Jadilah yang pertama mengupload!</p>
                    <br>
                    <a href="upload-gallery.php"><button class="primary-btn">Upload Foto</button></a>
                    <?php if($kategori_pilihan != 'Semua'): ?>
                        <br><br>
                        <a href="gallery.php" style="color: blue; text-decoration: underline;">Kembali ke Semua</a>
                    <?php endif; ?>
                </div>
            <?php } ?>
        </div>
    </section>
</main>
</body>

<?php 
$conn->close();
include '../../components/footer.php'; 
?>