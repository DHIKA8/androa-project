<?php
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../guest/login.php");
    exit();
}

include '../../db_connect.php';

$article_id = $_GET['article_id'] ?? null;
$user_id = $_SESSION['user_id'];

if (!$article_id || !is_numeric($article_id)) {
    header("Location: edustyle.php");
    exit();
}

$article_id = $conn->real_escape_string($article_id);
$post_type = 'article'; 

$sql_article = "SELECT a.*, u.username, u.avatar_path 
                FROM articles a
                JOIN users u ON a.author_id = u.user_id
                WHERE a.article_id = '$article_id' AND a.status = 'published' LIMIT 1";

$result_article = $conn->query($sql_article);
if ($result_article->num_rows === 0) {
    echo "Artikel tidak ditemukan.";
    exit();
}
$article_data = $result_article->fetch_assoc();

//  Cek Status Like
$check_like = "SELECT * FROM likes WHERE user_id = '$user_id' AND post_type = '$post_type' AND article_id = '$article_id'";
$res_check = mysqli_query($conn, $check_like);
$is_liked = mysqli_num_rows($res_check) > 0;

//  Hitung Total Like
$sql_count_like = "SELECT COUNT(*) AS total_likes FROM likes 
                   WHERE post_type = '$post_type' AND article_id = '$article_id'";
$total_likes = $conn->query($sql_count_like)->fetch_assoc()['total_likes'];

//  Ambil Komentar
$sql_comments = "SELECT c.*, u.username, u.avatar_path 
                 FROM comments c
                 LEFT JOIN users u ON c.user_id = u.user_id
                 WHERE c.post_type = '$post_type' AND c.article_id = '$article_id' 
                 ORDER BY c.created_at DESC";
$result_comments = $conn->query($sql_comments);

include '../../components/header_user.php'; 
?>

<link rel="stylesheet" href="user.css">

<main class="gallery-detail-main">

    <div class="article-image-container">
        <?php $img = !empty($article_data['image_path']) ? "../../Asset/" . $article_data['image_path'] : "../../Asset/default-article.jpg"; ?>
        <img src="<?= $img; ?>" alt="Artikel Image" class="article-img">
    </div>

    <div class="article-info">
        
        <h3>@<?= htmlspecialchars($article_data['username']); ?></h3>
        
        <p class="article-text"><?= $article_data['content']; ?></p>
        
        <p class="article-date">
            Diunggah: <?= date('d M Y', strtotime($article_data['created_at'])); ?>
        </p>

        <div class="like-section">
            <button 
                id="like-button" 
                class="like-btn <?= $is_liked ? 'liked' : ''; ?>" 
                onclick="toggleLikeDetail(this, '<?= $post_type; ?>', <?= $article_id; ?>)"
            >
                <?= $is_liked ? '❤️ Liked' : '♡ Like'; ?>
            </button>
            <span id="like-count" class="like-count"><?= $total_likes; ?> likes</span>
        </div>
    </div>

    <section class="comment-section">
        <h3>Tulis Komentar</h3>
        
        <form action="process_comment.php" method="POST">
            <input type="hidden" name="type" value="article">
            <input type="hidden" name="id" value="<?= $article_id; ?>">
            
            <textarea name="content" rows="4" placeholder="Tulis komentar..." required></textarea>
            <br>
            <button type="submit" class="primary-btn">
                Kirim Komentar
            </button>
        </form>

        <div class="comment-list">
            <?php if ($result_comments && $result_comments->num_rows > 0): ?>
                <?php while ($comment = $result_comments->fetch_assoc()): ?>
                    
                    <div class="comment-item">
                        <div class="comment-header">
                            <strong>@<?= htmlspecialchars($comment['username']); ?></strong> - 
                            <span>
                                <?= date('H:i, d M', strtotime($comment['created_at'])); ?>
                            </span>
                        </div>
                        
                        <p><?= htmlspecialchars($comment['content']); ?></p>

                        <?php if ($comment['user_id'] == $_SESSION['user_id']) { ?>
                            <a href="javascript:void(0)" 
                               onclick="showConfirmPopup('Yakin ingin menghapus komentar ini??', 'delete_comment.php?id=<?= $comment['comment_id']; ?>')"
                               class="delete-link">
                               [Hapus]
                            </a>
                        <?php } ?>
                        
                        <hr>
                    </div>

                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </section>

</main>

<div id="customPopup" class="popup-overlay">
    <div class="popup-box">
        <p id="popupMessage" class="popup-message">Pesan...</p>
        
        <div id="popupButtons">
            <button class="popup-btn btn-cancel" onclick="closePopup()">Batal</button>
            <button id="btnConfirm" class="popup-btn btn-confirm">Ya</button>
        </div>
        
        <div id="popupOkButton" style="display:none;">
            <button class="popup-btn btn-confirm" onclick="closePopup()">OK</button>
        </div>
    </div>
</div>

<?php 
$conn->close();
include '../../components/footer.php'; 
?>

<script>
// Fungsi Popup 
function showConfirmPopup(message, actionUrl) {
    const popup = document.getElementById('customPopup');
    const msg = document.getElementById('popupMessage');
    const btnGroup = document.getElementById('popupButtons');
    const btnOk = document.getElementById('popupOkButton');
    const btnConfirm = document.getElementById('btnConfirm');

    msg.innerText = message;
    btnGroup.style.display = 'block';
    btnOk.style.display = 'none';

    btnConfirm.onclick = function() {
        window.location.href = actionUrl;
    };
    popup.style.display = 'flex';
}

function showAlertPopup(message, redirectUrl = null) {
    const popup = document.getElementById('customPopup');
    const msg = document.getElementById('popupMessage');
    const btnGroup = document.getElementById('popupButtons');
    const btnOk = document.getElementById('popupOkButton');

    msg.innerText = message;
    btnGroup.style.display = 'none';
    btnOk.style.display = 'block';

    if (redirectUrl) {
        btnOk.querySelector('button').onclick = function() {
            window.location.href = redirectUrl;
        };
    } else {
        btnOk.querySelector('button').onclick = function() {
            closePopup();
        };
    }
    popup.style.display = 'flex';
}

function closePopup() {
    document.getElementById('customPopup').style.display = 'none';
}

//  Fungsi Like Detail 
function toggleLikeDetail(btn, type, id) {
    event.preventDefault();

    fetch(`process_like.php?type=${type}&id=${id}`)
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            const countElement = document.getElementById('like-count');
            countElement.textContent = data.total_likes + ' likes';
            
            if (data.is_liked) {
                btn.classList.add('liked');
                btn.innerHTML = '❤️ Liked';
            } else {
                btn.classList.remove('liked');
                btn.innerHTML = '♡ Like';
            }
        } else if (data.status === 'error' && data.message.includes('login')) {
            showAlertPopup("Silakan login untuk menyukai postingan.", "../guest/login.php");
        } else {
            showAlertPopup('Gagal: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlertPopup('Terjadi kesalahan koneksi ke server.');
    });
}
</script>