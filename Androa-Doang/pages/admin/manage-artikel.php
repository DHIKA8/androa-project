<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {

}

include '../../db_connect.php'; 

$articles = [];

$sql_articles = "SELECT a.*, u.username 
                 FROM articles a
                 JOIN users u ON a.author_id = u.user_id
                 ORDER BY a.created_at DESC";

$result_articles = $conn->query($sql_articles);

if ($result_articles && $result_articles->num_rows > 0) {
    while ($row = $result_articles->fetch_assoc()) {
        $articles[] = $row;
    }
}

include '../../components/header_admin.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Artikel - EduStyle</title>
    
    <link rel="stylesheet" href="admin.css">

</head>
<body class="manage-artikel-page">
    <header class="admin-header">
        <section class="header-banner">
            <h1>Manage Artikel EduStyle</h1>
            <p>Kelola konten edukasi yang akan ditampilkan di halaman EduStyle.</p>
        </section>
    </header>

    <main class="admin-main">

        <section class="admin-section">
            <div class="header-action">
                <h2>Daftar Artikel (<?= count($articles); ?>)</h2>
                <a href="article-form.php?action=create" style="text-decoration:none;">
                    <button class="primary-btn">+ Buat Artikel Baru</button>
                </a>
            </div>

            <table class="admin-table article-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($articles)): ?>
                        <?php foreach ($articles as $article): ?>
                            <tr>
                                <td><?= $article['article_id']; ?></td>
                                <td style="font-weight: 600;"><?= htmlspecialchars($article['title']); ?></td>
                                <td>@<?= htmlspecialchars($article['username']); ?></td>
                                
                                <td>
                                    <?php 
                                        $statusClass = strtolower($article['status']) == 'published' ? 'status-published' : 'status-draft';
                                    ?>
                                    <span class="status-badge <?= $statusClass; ?>">
                                        <?= ucfirst($article['status']); ?>
                                    </span>
                                </td>
                                
                                <td><?= date('d M Y', strtotime($article['created_at'])); ?></td>
                                
                                <td>
                                    <a href="article-form.php?action=edit&id=<?= $article['article_id']; ?>" style="text-decoration:none;">
                                        <button class="edit-btn">Edit</button>
                                    </a>
                                    
                                    <button type="button" 
                                            class="trigger-btn danger"
                                            onclick="openDeleteModal(<?= $article['article_id']; ?>, '<?= addslashes(htmlspecialchars($article['title'])); ?>')">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: #666;">
                                Belum ada artikel yang tersedia.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </section>

    </main>

    <div id="deleteArticleModal" class="modal-overlay">
        <div class="modal-box">
            <h3>Konfirmasi Hapus</h3>
            
            <p>
                Yakin ingin menghapus artikel: <br> 
                <span id="delArticleTitle" class="highlight-text">Judul Artikel</span>?
            </p>
            
            <span class="warning-text">Data yang dihapus tidak dapat dikembalikan!</span>
            
            <div class="modal-actions">
                <button class="btn-modal btn-cancel" onclick="closeDeleteModal()">Batal</button>
                
                <form action="process-article.php" method="POST" style="margin:0;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="article_id" id="modalArticleId" value="">
                    
                    <button type="submit" class="btn-modal btn-delete-confirm">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal(id, title) {
            document.getElementById('delArticleTitle').innerText = title;
            document.getElementById('modalArticleId').value = id;
            
            document.getElementById('deleteArticleModal').style.display = 'flex';
        }

        function closeDeleteModal() {
            document.getElementById('deleteArticleModal').style.display = 'none';
        }

        window.onclick = function(event) {
            var modal = document.getElementById('deleteArticleModal');
            if (event.target == modal) {
                closeDeleteModal();
            }
        }
    </script>

<?php 
$conn->close();
include '../../components/footer.php'; 
?>
</body>
</html>