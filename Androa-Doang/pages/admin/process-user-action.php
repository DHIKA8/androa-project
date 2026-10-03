<?php
session_start();

// Cek Autentikasi dan Role Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../guest/login.php");
    exit();
}

include '../../db_connect.php'; 

$self_id = $_SESSION['user_id'];
$success = false;
$user_id = $_POST['user_id'] ?? null;
$action = $_POST['action'] ?? null;
$error_message = "";

if ($user_id && $action) {
    $user_id = $conn->real_escape_string($user_id);

    // Memblokir Admin dari mengubah status/menghapus dirinya sendiri
    if ($user_id == $self_id && ($action == 'ban' || $action == 'delete' || $action == 'update_role')) {
        $error_message = "Anda tidak dapat melakukan aksi pada akun Administrator Anda sendiri.";
    } else {
        $sql = "";
        
        switch ($action) {
            case 'update_role':
                $new_role = $_POST['new_role'] ?? 'user';
                $new_role = $conn->real_escape_string($new_role);
                $sql = "UPDATE users SET role = '$new_role' WHERE user_id = '$user_id'";
                break;

            case 'ban':
                $sql = "UPDATE users SET is_active = 0 WHERE user_id = '$user_id'";
                break;
                
            case 'unban':
                $sql = "UPDATE users SET is_active = 1 WHERE user_id = '$user_id'";
                break;

            case 'delete':
                $sql = "DELETE FROM users WHERE user_id = '$user_id'";
                break;
        }

        if (!empty($sql)) {
            if ($conn->query($sql) === TRUE) {
                $success = true;
            } else {
                $error_message = "Gagal memproses aksi. SQL Error: " . $conn->error;
            }
        }
    }
}

$conn->close();

$status_param = $success ? 'success' : 'error';
$redirect_url = "user-list.php?status=" . $status_param . "&action=" . $action;

if (isset($error_message) && !$success) {
    $redirect_url .= "&msg=" . urlencode($error_message);
}

header("Location: " . $redirect_url);
exit();