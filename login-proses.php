<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($koneksi, "SELECT * FROM login WHERE username='$username' AND password='$password'");
    $ketemu = mysqli_num_rows($query);

    if ($ketemu > 0) {
        $data = mysqli_fetch_assoc($query);
        $_SESSION['status_login'] = true;
        $_SESSION['user_id'] = $data['id'];
        $_SESSION['username'] = $data['username'];
        header("location:index.php");
    } else {
        echo "<script>alert('Username atau Password salah!'); window.location='login.php';</script>";
    }
}
?>