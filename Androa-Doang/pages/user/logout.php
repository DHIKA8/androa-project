<?php
session_start();

// Hapus semua session user
session_unset();
session_destroy();

// Redirect ke halaman login (atau index)
header("Location: ../guest/login.php");
exit;
?>
