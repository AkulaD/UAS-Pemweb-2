<?php
session_start();
if (!isset($_SESSION['status_login'])) {
    header("location:login.php");
    exit;
}

require 'koneksi.php';

class HapusProyek {
    private $db;

    public function __construct($koneksi) {
        $this->db = $koneksi;
    }

    public function eksekusiHapus($id) {
        $id_clean = mysqli_real_escape_string($this->db, $id);
        
        $query_pilih = "SELECT gambar FROM proyek_it WHERE id = '$id_clean'";
        $result_pilih = mysqli_query($this->db, $query_pilih);
        
        if (mysqli_num_rows($result_pilih) > 0) {
            $data = mysqli_fetch_assoc($result_pilih);
            $nama_gambar = $data['gambar'];
            $folder_tujuan = 'data/uploads/';

            if (!empty($nama_gambar) && file_exists($folder_tujuan . $nama_gambar)) {
                unlink($folder_tujuan . $nama_gambar);
            }
        }

        $query_hapus = "DELETE FROM proyek_it WHERE id = '$id_clean'";
        if (mysqli_query($this->db, $query_hapus)) {
            return true;
        }
        return false;
    }
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $penghapus = new HapusProyek($koneksi);
    $sukses = $penghapus->eksekusiHapus($_GET['id']);

    if ($sukses) {
        echo "<script>
            alert('Data proyek dan gambar berhasil dihapus!'); 
            window.location='data.php';
            </script>";
    } else {
        echo "<script>
            alert('Gagal menghapus data proyek!'); 
            window.location='data.php';
            </script>";
    }
} else {
    header("location:data.php");
    exit;
}
?>