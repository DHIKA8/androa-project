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

$post_id = $_GET['post_id'] ?? null;
$user_id = $_SESSION['user_id'];

if (!$post_id || !is_numeric($post_id)) {
    header("Location: gallery.php");
    exit();
}

$post_id   = $conn->real_escape_string($post_id);
$post_type = 'gallery'; 

// Query Data Postingan
$sql_post = "SELECT gp.*, u.username, u.avatar_path 
             FROM gallery_posts gp
             JOIN users u ON gp.user_id = u.user_id
             WHERE gp.post_id = '$post_id' LIMIT 1";

$result_post = $conn->query($sql_post);
if ($result_post->num_rows === 0) {
    echo "Postingan tidak ditemukan.";
    exit();
}
$post_data = $result_post->fetch_assoc();

// Cek Status Like
$check_like = "SELECT * FROM likes 
               WHERE user_id = '$user_id' 
                 AND post_id = '$post_id' 
                 AND post_type = 'gallery'";
$res_check = mysqli_query($conn, $check_like);
$is_liked  = mysqli_num_rows($res_check) > 0;

// Hitung Total Like
$sql_count_like = "SELECT COUNT(*) AS total_likes 
                   FROM likes 
                   WHERE post_type = '$post_type' 
                     AND post_id = '$post_id'";
$total_likes = $conn->query($sql_count_like)->fetch_assoc()['total_likes'];

// Ambil Komentar
$sql_comments = "SELECT c.*, u.username 
                 FROM comments c
                 LEFT JOIN users u ON c.user_id = u.user_id
                 WHERE c.post_type = '$post_type' 
                   AND post_id = '$post_id' 
                 ORDER BY c.created_at DESC";
$result_comments = $conn->query($sql_comments);

include '../../components/header_admin.php'; 
?>

<link rel="stylesheet" href="admin.css">

<body class="admin-gallery-page">
    
<main class="gallery-detail-main">

    <div class="ig-detail-wrapper">
        <div class="ig-media">
            <img src="../../Asset/<?= htmlspecialchars($post_data['image_path']); ?>" 
                 alt="Gallery Photo" 
                 class="photo-detail-img">
        </div>

        <div class="ig-side">

            <div class="photo-info">
                <h2 class="photo-title">@<?= htmlspecialchars($post_data['username']); ?></h2>

                <p class="photo-desc">
                    <?= htmlspecialchars($post_data['caption']); ?>
                </p>

                <div class="photo-meta">
                    <p>Style: <span class="category"><?= htmlspecialchars($post_data['style_category']); ?></span></p>
                    <p>Diunggah: <?= date('d M Y', strtotime($post_data['created_at'])); ?></p>
                </div>

                <div class="photo-likes">
                    <button 
                        id="like-button" 
                        class="like-btn <?= $is_liked ? 'liked' : ''; ?>" 
                        onclick="toggleLikeDetail(this, '<?= $post_type; ?>', <?= $post_id; ?>)"
                    >
                        <?= $is_liked ? '❤️ Liked' : '♡ Like'; ?>
                    </button>
                    <span id="like-count" class="like-count"><?= $total_likes; ?> likes</span>
                </div>
            </div>

            <div class="comment-list">
                <?php if ($result_comments && $result_comments->num_rows > 0): ?>
                    <?php while ($comment = $result_comments->fetch_assoc()): ?>
                        
                        <div class="comment-item">
                            <p class="comment-meta">
                                <span class="comment-left">
                                    <strong>@<?= htmlspecialchars($comment['username']); ?></strong> 
                                    <span class="comment-time">
                                        <?= date('H:i, d M', strtotime($comment['created_at'])); ?>
                                    </span>
                                </span>

                                <a href="javascript:void(0)" 
                                   onclick="showConfirmPopup('Yakin ingin menghapus komentar ini?', 'delete_comment.php?comment_id=<?= $comment['comment_id']; ?>&post_id=<?= $post_id; ?>&type=gallery')"
                                   class="delete-link">
                                   Hapus (Admin)
                                </a>
                            </p>
                            
                            <p class="comment-content"><?= htmlspecialchars($comment['content']); ?></p>
                        </div>

                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="no-comment-text">Belum ada komentar. Jadilah yang pertama!</p>
                <?php endif; ?>
            </div>

            <div class="comment-form">
                <form action="process_comment.php" method="POST">
                    <input type="hidden" name="type" value="gallery">
                    <input type="hidden" name="id" value="<?= $post_id; ?>"> 
                    
                    <textarea name="content"
                              rows="1"
                              placeholder="Tambahkan komentar..."
                              required
                              class="comment-input"></textarea>

                    <button type="submit" class="comment-send-btn">Kirim</button>
                </form>
            </div>

        </div>

    </div>

</main>

<div id="customPopup" class="popup-overlay">
    <div class="popup-box">
        <p id="popupMessage" class="popup-message">Pesan...</p>
        
        <div id="popupButtons">
            <button class="popup-btn btn-cancel" onclick="closePopup()">Batal</button>
            <button id="btnConfirm" class="popup-btn btn-confirm">Ya</button>
        </div>
        
        <div id="popupOkButton" class="hidden-initial">
            <button class="popup-btn btn-confirm" onclick="closePopup()">OK</button>
        </div>
    </div>
</div>

<?php 
$conn->close();
include '../../components/footer.php'; 
?>

<script>
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
    });
}
</script>
</body>
