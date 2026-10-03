<?php
session_start();
include '../../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../guest/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $content = $conn->real_escape_string($_POST['content']);
    $type    = $_POST['type']; 
    $id      = intval($_POST['id']);
    
    // Validasi 
    if (!empty($content) && ($type == 'gallery' || $type == 'article')) {
        
        if ($type == 'gallery') {
            $col_target = "post_id";
        } else {
            $col_target = "article_id";
        }

        $sql = "INSERT INTO comments (user_id, post_type, content, $col_target) 
                VALUES ('$user_id', '$type', '$content', '$id')";

        if ($conn->query($sql) === TRUE) {
            header("Location: " . $_SERVER['HTTP_REFERER']); 
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }
}
$conn->close();
?>