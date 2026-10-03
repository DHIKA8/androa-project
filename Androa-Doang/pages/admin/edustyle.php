<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../../db_connect.php'; 
include '../../components/header_admin.php'; 

$current_user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
?>
<link rel="stylesheet" href="admin.css">

<body class="admin-edustyle-page">

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

            if (mysqli_num_rows($result) > 0) {
                
                while ($row = mysqli_fetch_assoc($result)) {
                    $article_id = $row['article_id'];
                    $title = htmlspecialchars($row['title']);
                    $author_name = htmlspecialchars($row['username']);
                    
                    // Content Preview
                    $content_raw = strip_tags($row['content']); 
                    $content_preview = mb_strimwidth($content_raw, 0, 100, "...");

                    // Path Gambar
                    $img_src = !empty($row['image_path']) ? "../../Asset/" . $row['image_path'] : "../../Asset/default-article.jpg";

                    // Hitung Like
                    $query_likes = "SELECT COUNT(*) as total FROM likes WHERE post_type='article' AND article_id='$article_id'";
                    $res_likes = mysqli_query($conn, $query_likes);
                    $total_likes = mysqli_fetch_assoc($res_likes)['total'];

                    // Cek Like User
                    $is_liked = false;
                    $like_class = '';
                    if ($current_user_id) {
                        $q_check_like = "SELECT * FROM likes WHERE user_id='$current_user_id' AND post_type='article' AND article_id='$article_id'";
                        if (mysqli_num_rows(mysqli_query($conn, $q_check_like)) > 0) {
                            $is_liked = true;
                            $like_class = 'liked';
                        }
                    }

                    // Hitung Comment
                    $query_comments = "SELECT COUNT(*) as total FROM comments WHERE post_type='article' AND article_id='$article_id'";
                    $res_comments = mysqli_query($conn, $query_comments);
                    $total_comments = mysqli_fetch_assoc($res_comments)['total'];
            ?>

                    <article class="card">
                        <a href="detail-artikel.php?article_id=<?= $article_id; ?>">
                            <img src="<?= $img_src; ?>" alt="<?= $title; ?>" style="width:100%; height:300px; object-fit:cover;">
                        </a>
                        
                        <div class="post-info">
                            <p class="ig-caption" style="margin-bottom: 5px;">
                                <b>@<?= $author_name; ?></b> <br>
                                <span style="font-weight:600; font-size: 1.1em;"><?= mb_strimwidth($title, 0, 50, "..."); ?></span>
                            </p>
                            
                            <p class="article-content" style="font-size: 0.9em; color: #555; margin-top: 5px; line-height: 1.4;">
                                <?= $content_preview; ?>
                            </p>

                            <div class="actions" style="margin-top: 10px;">
                                <a href="javascript:void(0)" onclick="toggleLike(this, 'article', <?= $article_id; ?>)" style="text-decoration:none;">
                                    <button class="like-button <?= $like_class; ?>" style="cursor:pointer;">
                                        <?= $is_liked ? '❤️' : '🤍'; ?> <?= $total_likes; ?>
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
                    <h3>Belum ada artikel yang tersedia!</h3>
                    <p>Nantikan update terbaru dari kami.</p>
                </div>
            <?php }  ?>
        </div>
    </section>
</main>

<script>
function toggleLike(element, type, id) {
    event.preventDefault(); 
    
    fetch(`process_like.php?type=${type}&id=${id}`)
    .then(response => {
        if (!response.ok) { throw new Error("File process_like.php tidak ditemukan!"); }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            let iconHeart = data.is_liked ? '❤️' : '🤍';
            let btn = element.tagName === 'BUTTON' ? element : element.querySelector('button');
            
            if(btn) {
                btn.innerHTML = `${iconHeart} ${data.total_likes}`;
                if (data.is_liked) {
                    btn.classList.add('liked');
                } else {
                    btn.classList.remove('liked');
                }
            }

            let statsLikeText = document.getElementById(`stats-likes-${id}`);
            
            if (statsLikeText) {
                statsLikeText.innerText = `${data.total_likes} likes`;
            }

        } else if (data.status === 'error' && data.message.includes('login')) {
            window.location.href = "../../guest/login.php"; 
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>

<?php include '../../components/footer.php'; ?>

</body>