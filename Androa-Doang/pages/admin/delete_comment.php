<?php
session_start();
include '../../db_connect.php'; 

// 1. Cek Login
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Silakan login terlebih dahulu.'); window.location.href='../../guest/login.php';</script>";
    exit;
}

// 2. Cek Parameter Wajib
if (isset($_GET['comment_id'])) {
    $comment_id = mysqli_real_escape_string($conn, $_GET['comment_id']);
    $current_user_id = $_SESSION['user_id'];
    
    $redirect_url = "";
    
    if (isset($_GET['article_id'])) {
        $article_id = mysqli_real_escape_string($conn, $_GET['article_id']);
        $redirect_url = "detail-artikel.php?article_id=" . $article_id . "&msg=deleted";
    } 
    elseif (isset($_GET['post_id']) && isset($_GET['type']) && $_GET['type'] == 'gallery') {
        $post_id = mysqli_real_escape_string($conn, $_GET['post_id']);
        
        $redirect_url = "detail-gallery.php?post_id=" . $post_id . "&msg=deleted"; 
    } 
    else {
        echo "<script>alert('Data ID Postingan tidak lengkap.'); window.history.back();</script>";
        exit;
    }

    $check_query = mysqli_query($conn, "SELECT user_id FROM comments WHERE comment_id = '$comment_id'");
    
    if (mysqli_num_rows($check_query) > 0) {
        $comment_data = mysqli_fetch_assoc($check_query);
        $owner_id = $comment_data['user_id'];

        $user_query = mysqli_query($conn, "SELECT role FROM users WHERE user_id = '$current_user_id'");
        $user_data = mysqli_fetch_assoc($user_query);
        $user_role = $user_data['role'];

        if ($current_user_id == $owner_id || $user_role === 'admin') {
            
            $delete_query = "DELETE FROM comments WHERE comment_id = '$comment_id'";
            
            if (mysqli_query($conn, $delete_query)) {
                header("Location: " . $redirect_url);
                exit();
            } else {
                echo "Error database: " . mysqli_error($conn);
            }

        } else {
            echo "<script>alert('Anda tidak punya izin menghapus komentar ini.'); window.history.back();</script>";
        }

    } else {
        echo "<script>alert('Komentar tidak ditemukan.'); window.history.back();</script>";
    }

} else {
    echo "<script>alert('ID Komentar tidak ditemukan.'); window.history.back();</script>";
}
?>