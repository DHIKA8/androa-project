<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../db_connect.php'; 
include '../../components/header_admin.php'; 

// CEK LOGIN & ROLE
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Androa</title>

<link rel="stylesheet" href="admin.css">    

</head>
<body class="user-list-admin">

    <header class="admin-header">
        <section class="header-banner">
            <h1>Manage User</h1>
            <p>Control roles, permissions, and user access.</p>
        </section>
    </header>

    <main class="admin-main">
        <section class="admin-section">
            <h2>All Users</h2>

            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Profile</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($conn)) {
                        $query = "SELECT * FROM users ORDER BY created_at DESC";
                        $result = mysqli_query($conn, $query);

                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($user = mysqli_fetch_assoc($result)) {
                                $id = $user['user_id'];
                                $username = htmlspecialchars($user['username']);
                                $email = htmlspecialchars($user['email']);
                                $role = $user['role'];
                                $date = date("F Y", strtotime($user['created_at']));
                                $avatar = !empty($user['avatar_path']) ? "../../" . $user['avatar_path'] : '../../assets/profile-default.jpg';
                    ?>
                                <tr>
                                    <td><?php echo $id; ?></td>
                                    <td>
                                        <img src="<?php echo $avatar; ?>" class="user-avatar" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                    </td>
                                    <td>@<?php echo $username; ?></td>
                                    <td><?php echo $email; ?></td>
                                    <td>
                                        <select class="role-select" onchange="changeRole(<?php echo $id; ?>, this.value)">
                                            <option value="user" <?php if($role == 'user') echo 'selected'; ?>>User</option>
                                            <option value="admin" <?php if($role == 'admin') echo 'selected'; ?>>Admin</option>
                                            <option value="guest" <?php if($role == 'guest') echo 'selected'; ?>>Guest</option>
                                        </select>
                                    </td>
                                    <td><?php echo $date; ?></td>
                                    <td>
                                        <button type="button" 
                                                class="trigger-btn danger" 
                                                onclick="showDeleteModal(<?php echo $id; ?>, '@<?php echo $username; ?>')">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                    <?php 
                            } 
                        } else { 
                    ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: #666;">
                                Tidak ada user yang ditemukan di database.
                            </td>
                        </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='7'>Koneksi database gagal.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>

    <div id="deleteModal" class="modal-overlay">
        <div class="modal-box">
            <h3>Konfirmasi Hapus</h3>
            
            <p>Apakah Anda yakin ingin menghapus user <span id="modalUsername" class="highlight-user">@username</span>?</p>
            <span class="warning-text">Tindakan ini tidak bisa dibatalkan.</span>
            
            <div class="modal-actions">
                <button class="btn-modal btn-cancel" onclick="closeDeleteModal()">Batal</button>
                <a id="btnConfirmDelete" href="#" class="btn-modal btn-delete">Ya, Hapus</a>
            </div>
        </div>
    </div>

    <script>
        // 1. Fungsi Buka Modal
        function showDeleteModal(userId, username) {
            document.getElementById('modalUsername').innerText = username;
            document.getElementById('btnConfirmDelete').href = "user-action.php?action=delete&id=" + userId;
            document.getElementById('deleteModal').style.display = 'flex';
        }

        // 2. Fungsi Tutup Modal
        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        // 3. Fungsi Ubah Role
        function changeRole(userId, newRole) {
            if(confirm("Apakah Anda yakin ingin mengubah role user ini menjadi " + newRole + "?")) {
                window.location.href = "user-action.php?action=change_role&id=" + userId + "&role=" + newRole;
            } else {
                location.reload(); 
            }
        }

        // 4. Tutup jika klik area luar
        window.onclick = function(event) {
            const modal = document.getElementById('deleteModal');
            if (event.target == modal) {
                closeDeleteModal();
            }
        }
    </script>
</body>
</html>

<?php include '../../components/footer.php'; ?>