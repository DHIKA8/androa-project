<?php

// Konfigurasi Database
$host = "localhost";
$db_user = "root";      
$db_pass = "";          
$db_name = "db_androa"; 

// buat koneksi
$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>