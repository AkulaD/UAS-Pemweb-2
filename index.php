<?php
include 'partials/header.php'; 
?>

<div>
    <h2 class="title m-0 fw-bold">Selamat Datang, <?php echo $_SESSION['username']; ?></h2>
    <p class="subtitle text-muted mb-2">Gunakan menu di sebelah kiri untuk mengelola.</p>
</div>



<?php
include 'partials/table-proyek.php';
include 'partials/footer.php'; 
?>