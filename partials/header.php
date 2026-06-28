<?php
session_start();
if (!isset($_SESSION['status_login'])) {
    header("location:login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IRiS-PK</title>
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="data/style.css">
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.min.js" defer></script>
    <script src="data/main.js" defer></script>
</head>
<body>

<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        IRiS-PK
    </div>
    <nav class="sidebar-nav">
        <div class="nav-top">
            <a href="index.php" class="nav-link">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="data.php" class="nav-link">
                <i class="bi bi-folder"></i> Data
            </a>
            <a href="user.php" class="nav-link">
                <i class="bi bi-people"></i> User
            </a>
        </div>
        <div class="nav-bottom">
            <div class="profile">
                <div class="profile-img">
                    <i class="bi bi-person-circle"></i>
                    <span class="profile-name"><?php echo $_SESSION['username']; ?></span>
                </div>
            </div>
            <a href="logout.php" class="nav-link logout-link">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </nav>
</div>

<div class="main-content">
    <button class="mobile-toggle" id="menuToggle">
        <i class="bi bi-list"></i>
    </button>