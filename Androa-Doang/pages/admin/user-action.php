<?php
session_start();

include '../../db_connect.php'; 

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action == 'delete' && isset($_GET['id'])) {
        $id = $_GET['id'];
        
        // Query Delete
        $query = "DELETE FROM users WHERE user_id = '$id'";
        
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('User berhasil dihapus!'); window.location.href='user-list.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus user: " . mysqli_error($conn) . "'); window.location.href='user-list.php';</script>";
        }
    }

    elseif ($action == 'change_role' && isset($_GET['id']) && isset($_GET['role'])) {
        $id = $_GET['id'];
        $new_role = $_GET['role'];

        // Validasi input role agar aman
        $allowed_roles = ['user', 'admin', 'guest'];
        
        if (!in_array($new_role, $allowed_roles)) {
            echo "<script>alert('Role tidak valid!'); window.location.href='user-list.php';</script>";
            exit;
        }

        // Query Update
        $query = "UPDATE users SET role = '$new_role' WHERE user_id = '$id'";

        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Role berhasil diubah menjadi $new_role!'); window.location.href='user-list.php';</script>";
        } else {
            echo "<script>alert('Gagal mengubah role: " . mysqli_error($conn) . "'); window.location.href='user-list.php';</script>";
        }
    }
} else {
    header("Location: user-list.php");
}
?>