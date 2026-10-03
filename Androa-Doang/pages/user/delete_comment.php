<?php
session_start();
include '../../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../guest/login.php");
    exit();
}

if (isset($_GET['id'])) {
    $comment_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];

    // Hapus Komentar
    $sql = "DELETE FROM comments WHERE comment_id = '$comment_id' AND user_id = '$user_id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        echo "Error: Gagal menghapus komentar.";
    }
} else {
    header("Location: " . $_SERVER['HTTP_REFERER']);
}

$conn->close();
?>