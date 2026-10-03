<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../guest/login.php");
    exit();
}

include '../../db_connect.php'; 

$user_id = $_SESSION['user_id'];
$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['post_id']) && is_numeric($_POST['post_id'])) {
        $post_id = $conn->real_escape_string($_POST['post_id']);

        $sql_select = "SELECT image_path FROM gallery_posts WHERE post_id = '$post_id' AND user_id = '$user_id' LIMIT 1";
        $result = $conn->query($sql_select);

        if ($result->num_rows > 0) {
            $post = $result->fetch_assoc();
            $image_path_db = $post['image_path'];
            
            $sql_delete = "DELETE FROM gallery_posts WHERE post_id = '$post_id' AND user_id = '$user_id'";
            
            if ($conn->query($sql_delete) === TRUE) {
                
                $full_file_path = "../../Asset/" . $image_path_db;
                
                if (file_exists($full_file_path)) {
                    unlink($full_file_path);
                }
                
                $success_message = "Postingan berhasil dihapus.";
                
            } else {
                $error_message = "Gagal menghapus postingan dari database: " . $conn->error;
            }
            
        } else {
            $error_message = "Postingan tidak ditemukan atau Anda tidak memiliki izin untuk menghapusnya.";
        }
        
    } else {
        $error_message = "Permintaan tidak valid.";
    }
}

header("Location: my-uploads.php?status=" . ($success_message ? 'deleted' : 'error'));

exit();
?>