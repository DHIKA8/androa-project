<?php
session_start();
include '../../db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Silakan login terlebih dahulu']);
    exit();
}

$user_id = $_SESSION['user_id'];
$type    = isset($_GET['type']) ? $_GET['type'] : '';
$id      = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0 && ($type == 'gallery' || $type == 'article')) {

    if ($type == 'gallery') {
        $col_target = "post_id";
        $other_col  = "article_id";
    } else {
        $col_target = "article_id";
        $other_col  = "post_id";
    }

    //   Toggle Like
    $check_sql = "SELECT like_id FROM likes WHERE user_id = '$user_id' AND post_type = '$type' AND $col_target = '$id'";
    $result = $conn->query($check_sql);

    $is_liked = false; 

    if ($result->num_rows > 0) {
        // UNLIKE
        $sql = "DELETE FROM likes WHERE user_id = '$user_id' AND post_type = '$type' AND $col_target = '$id'";
        $conn->query($sql);
        $is_liked = false;
    } else {
        // LIKE
        if ($type == 'gallery') {
            $sql = "INSERT INTO likes (user_id, post_type, post_id, article_id) VALUES ('$user_id', '$type', '$id', NULL)";
        } else {
            $sql = "INSERT INTO likes (user_id, post_type, post_id, article_id) VALUES ('$user_id', '$type', NULL, '$id')";
        }
        $conn->query($sql);
        $is_liked = true;
    }

    //  Hitung Total Like Terbaru
    $q_count = "SELECT COUNT(*) as total FROM likes WHERE post_type='$type' AND $col_target='$id'";
    $res_count = $conn->query($q_count);
    $total_likes = $res_count->fetch_assoc()['total'];

    echo json_encode([
        'status' => 'success',
        'is_liked' => $is_liked,
        'total_likes' => $total_likes
    ]);

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Request']);
}

$conn->close();
?>