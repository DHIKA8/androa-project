<?php
session_start();
$_SESSION['role'] = "guest";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Guest</title>
</head>
<body class="guest-profile-page">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Androa – Profile (Guest)</title>
    <link rel="stylesheet" href="guest.css">
    
</head>

<body>

<?php include '../../components/header.php'; ?>


<section class="profile-box">
    <h2>You Are Not Logged In</h2>
    <p>To access your profile and upload history, please <a href="login.php">login</a>.</p>
</section>

<?php include '../../components/footer.php'; ?>

</body>
</html>


