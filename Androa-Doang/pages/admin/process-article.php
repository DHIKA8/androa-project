<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../guest/login.php");
    exit();
}

include '../../db_connect.php'; 

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? null;
$article_id = $_POST['article_id'] ?? null;
$redirect_to = "manage-artikel.php";

$success = false;

if ($action) {
    
    //  DELETE 
    if ($action === 'delete' && $article_id) {
        $article_id = $conn->real_escape_string($article_id);
        
        // Ambil path gambar untuk dihapus
        $sql_select = "SELECT image_path FROM articles WHERE article_id = '$article_id' LIMIT 1";
        $result = $conn->query($sql_select);
        $article = $result->fetch_assoc();
        
        //  Hapus artikel dari DB
        $sql = "DELETE FROM articles WHERE article_id = '$article_id'";
        if ($conn->query($sql) === TRUE) {
            //  Hapus file fisik
            if ($article && $article['image_path'] && file_exists("../../Asset/" . $article['image_path'])) {
                unlink("../../Asset/" . $article['image_path']);
            }
            $success = true;
        }
    }
    
    //  CREATE / UPDATE 
    elseif ($action === 'create' || $action === 'update') {
        
        $title = $conn->real_escape_string(trim($_POST['title']));
        $content = $conn->real_escape_string($_POST['content']);
        $status = $conn->real_escape_string($_POST['status']);
        $image_path = $_POST['current_image'] ?? null; 
        
        $upload_success = true;
        
        // Proses Upload Gambar Baru
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['image']['tmp_name'];
            $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $new_file_name = "article_" . time() . "." . $file_ext;
            $upload_dir = "../../Asset/article_images/";
            
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
                if ($image_path && file_exists("../../Asset/" . $image_path)) {
                    unlink("../../Asset/" . $image_path);
                }
                $image_path = "article_images/" . $new_file_name;
            } else {
                $upload_success = false;
                $error_message = "Gagal mengunggah gambar artikel.";
            }
        }
        
        if ($upload_success) {
            
            if ($action === 'create') {
                $sql = "INSERT INTO articles (author_id, title, content, image_path, status) 
                        VALUES ('$user_id', '$title', '$content', '$image_path', '$status')";
            } elseif ($action === 'update' && $article_id) {
                $article_id = $conn->real_escape_string($article_id);
                $sql = "UPDATE articles SET title='$title', content='$content', status='$status', image_path='$image_path', updated_at=NOW() 
                        WHERE article_id='$article_id'";
            }
            
            if (!empty($sql) && $conn->query($sql) === TRUE) {
                $success = true;
            } else {
                $error_message = "Error database: " . $conn->error;
            }
        }
    }
}

$conn->close();

$status_param = $success ? 'success' : 'error';
$final_redirect = $redirect_to . "?status=" . $status_param . "&action=" . $action;

if (isset($error_message)) {
    $final_redirect .= "&msg=" . urlencode($error_message);
}

header("Location: " . $final_redirect);
exit();
?>