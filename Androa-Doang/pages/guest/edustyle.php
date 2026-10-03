<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../../db_connect.php'; 
include '../../components/header.php'; 

$login_url = "../guest/login.php"; 
?>

<link rel="stylesheet" href="guest.css">

<body class="guest-edustyle-page">

<header class="gallery-header">
    <section class="header-banner">
        <h1>EDUSTYLE ARTICLES</h1>
        <p>Kumpulan artikel edukatif tentang fashion genderless.</p>
    </section>
</header>

<main>
    <section class="gallery-list">
        <h2>Latest Articles</h2>

        <div class="gallery-grid">
            <?php
            $query = "SELECT a.*, u.username 
                      FROM articles a 
                      JOIN users u ON a.author_id = u.user_id
                      WHERE a.status = 'published' 
                      ORDER BY a.created_at DESC";
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {

                while ($row = mysqli_fetch_assoc($result)) {
                    $article_id  = $row['article_id'];
                    $title       = htmlspecialchars($row['title']);
                    $author_name = htmlspecialchars($row['username']);

                    $content_raw     = strip_tags($row['content']); 
                    $content_preview = mb_strimwidth($content_raw, 0, 100, "...");

                    $img_src = !empty($row['image_path']) 
                        ? "../../Asset/" . $row['image_path'] 
                        : "../../Asset/default-article.jpg";

                    // HITUNG LIKE 
                    $query_likes = "SELECT COUNT(*) AS total 
                                    FROM likes 
                                    WHERE post_type='article' 
                                      AND article_id='$article_id'";
                    $res_likes   = mysqli_query($conn, $query_likes);
                    $total_likes = ($res_likes && mysqli_num_rows($res_likes) > 0)
                        ? mysqli_fetch_assoc($res_likes)['total']
                        : 0;

                    // HITUNG KOMENTAR
                    $query_comments = "SELECT COUNT(*) AS total 
                                       FROM comments 
                                       WHERE post_type='article' 
                                         AND article_id='$article_id'";
                    $res_comments   = mysqli_query($conn, $query_comments);
                    $total_comments = ($res_comments && mysqli_num_rows($res_comments) > 0)
                        ? mysqli_fetch_assoc($res_comments)['total']
                        : 0;
            ?>

                <article class="card">
                    <a href="detail-artikel.php?article_id=<?= $article_id; ?>">
                        <img src="<?= $img_src; ?>" 
                             alt="<?= $title; ?>" 
                             style="width:100%; height:260px; object-fit:cover;">
                    </a>
                    
                    <div class="post-info">
                        <p class="ig-caption" style="margin-bottom: 5px;">
                            <b>@<?= $author_name; ?></b> <br>
                            <span style="font-weight:600; font-size: 1.1em;">
                                <?= mb_strimwidth($title, 0, 50, "..."); ?>
                            </span>
                        </p>
                        
                        <p class="article-content" style="font-size: 0.9em; color: #555; margin-top: 5px; line-height: 1.4;">
                            <?= $content_preview; ?>
                        </p>

                        <div class="actions" style="margin-top: 10px;">

                            <a href="<?= $login_url; ?>" style="text-decoration:none;">
                                <button class="like-button" style="cursor:pointer;">
                                    🤍 <?= $total_likes; ?>
                                </button>
                            </a>

                            <a href="detail-artikel.php?article_id=<?= $article_id; ?>#comments">
                                <button class="comment-button">💬 <?= $total_comments; ?></button>
                            </a>
                        </div>

                        <div class="stats-bar">
                            <span id="stats-likes-<?= $article_id; ?>">
                                <?= $total_likes; ?> likes
                            </span>
                            
                            <span id="stats-comments-<?= $article_id; ?>">
                                <?= $total_comments; ?> comments
                            </span>
                        </div>
                    </div>

                    <a href="detail-artikel.php?article_id=<?= $article_id; ?>" class="read-more-btn">
                        READ MORE
                    </a>
                </article>

            <?php 
                } 
            } else { 
            ?>
                <div>
                    <h3>Belum ada artikel yang tersedia.</h3>
                    <p>Nantikan update terbaru dari kami!</p>
                </div>
            <?php } ?>
        </div>
    </section>
</main>

<?php include '../../components/footer.php'; ?>
</body>
