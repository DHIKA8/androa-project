<?php
session_start();
include '../../db_connect.php';


if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    
    $post_id = $_GET['id'];

    mysqli_query($conn, "DELETE FROM likes WHERE post_type='gallery' AND post_id='$post_id'");
    mysqli_query($conn, "DELETE FROM comments WHERE post_type='gallery' AND post_id='$post_id'");
    
    $query_delete = "DELETE FROM gallery_posts WHERE post_id = '$post_id'";

    if (mysqli_query($conn, $query_delete)) {
        header("Location: manage-gallery.php"); 
        exit();
    } else {
        echo "<script>
                alert('Gagal menghapus postingan: " . mysqli_error($conn) . "');
                window.location.href = 'manage-gallery.php';
              </script>";
    }
}
?>