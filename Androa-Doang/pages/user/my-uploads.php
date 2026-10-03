<?php
session_start();

// Cek Autentikasi
if (!isset($_SESSION['user_id'])) {
    header("Location: ../guest/login.php");
    exit();
}

include '../../db_connect.php'; 

$user_id = $_SESSION['user_id'];
$my_uploads = [];

// Query ambil data
$sql_uploads = "SELECT * FROM gallery_posts 
                WHERE user_id = '$user_id' 
                ORDER BY created_at DESC";
$result_uploads = $conn->query($sql_uploads);

if ($result_uploads->num_rows > 0) {
    while ($row = $result_uploads->fetch_assoc()) {
        $my_uploads[] = $row;
    }
}

$show_delete_success = false;
if (isset($_GET['status']) && $_GET['status'] == 'deleted') {
    $show_delete_success = true;
}

include '../../components/header_user.php'; 
?>
<link rel="stylesheet" href="user.css">

<body class="user-uploads-page">
<header class="profile-header">
    <section class="header-banner">
        <h1>Riwayat Unggahan Saya</h1>
        <p>Kelola dan pantau status moderasi outfit yang Anda bagikan.</p>
    </section>
</header>
<main>
    <section class="gallery-list">
        
        <div class="upload-actions">
            <a href="upload-gallery.php"><button class="primary-btn">+ Unggah Baru</button></a>
            <a href="profile.php"><button class="secondary-btn">← Kembali ke Profil</button></a>
        </div>

        <div class="gallery-grid">
            <?php if (!empty($my_uploads)): ?>
                <?php foreach ($my_uploads as $post): ?>
                    <article class="card">
                        
                        <img src="../../Asset/<?= htmlspecialchars($post['image_path']); ?>" alt="<?= htmlspecialchars($post['caption']); ?>">
                        
                        <div class="card-content" style="padding: 10px;">
                            <p class="desc" style="font-weight: bold; margin-bottom: 5px;"><?= htmlspecialchars($post['caption']); ?></p>
                            
                            <p class="date">
                                Diunggah: <?= date('d M Y, H:i', strtotime($post['created_at'])); ?>
                            </p>

                            <div class="post-management">
                                <a href="edit-post.php?post_id=<?= $post['post_id']; ?>"><button class="edit-btn">Edit</button></a>
                                
                                <button type="button" class="delete-trigger" onclick="openDeleteModal('<?= $post['post_id']; ?>')">Hapus</button>
                            </div>
                        </div>

                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-content">Anda belum memiliki unggahan di galeri. <a href="upload-gallery.php">Ayo upload outfit pertama Anda!</a></p>
            <?php endif; ?>
        </div>
        
    </section>
</main>

<div id="deleteConfirmModal" class="modal-overlay">
    <div class="modal-box">
        <div style="margin-bottom: 10px;">
            <svg width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="#dc3545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"></polyline>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                <line x1="10" y1="11" x2="10" y2="17"></line>
                <line x1="14" y1="11" x2="14" y2="17"></line>
            </svg>
        </div>

        <h3>Hapus Postingan?</h3>
        <p>Tindakan ini tidak bisa dibatalkan. Postingan akan hilang permanen dari galeri.</p>
        
        <form action="delete-post.php" method="POST">
            <input type="hidden" name="post_id" id="modal_post_id" value="">
            
            <div class="modal-actions">
                <button type="button" class="btn-modal btn-cancel" onclick="closeDeleteModal()">Batal</button>
                <button type="submit" class="btn-modal btn-delete-confirm">Ya, Hapus</button>
            </div>
        </form>
    </div>
</div>

<?php if ($show_delete_success): ?>
<div id="successModal" class="modal-overlay active">
    <div class="modal-box">
        <div style="margin-bottom: 15px;">
            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#28a745" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="9 11 12 14 22 4"></polyline> <path d="M8 12L11 15L16 9"></path>
            </svg>
        </div>

        <h3>Berhasil Dihapus!</h3>
        <p>Postingan Anda telah berhasil dihapus dari sistem.</p>
        
        <div class="modal-actions">
            <a href="my-uploads.php" class="btn-modal btn-ok">Tutup</a>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
    // Fungsi Buka Modal Konfirmasi
    function openDeleteModal(postId) {
        document.getElementById('modal_post_id').value = postId;
        document.getElementById('deleteConfirmModal').style.display = 'flex';
    }

    // Fungsi Tutup Modal Konfirmasi
    function closeDeleteModal() {
        document.getElementById('deleteConfirmModal').style.display = 'none';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('deleteConfirmModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

</body>
<?php 
$conn->close();
include '../../components/footer.php'; 
?>