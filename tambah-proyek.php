<?php 
include 'partials/header.php'; 
require 'koneksi.php';

class ProyekIT {
    private $db;

    public function __construct($koneksi) {
        $this->db = $koneksi;
    }

    public function simpanData($postData, $fileData) {
        $id = mysqli_real_escape_string($this->db, $postData['id']);
        $nama = mysqli_real_escape_string($this->db, $postData['nama_proyek']);
        $manajer = mysqli_real_escape_string($this->db, $postData['manajer_proyek']);
        $deskripsi = mysqli_real_escape_string($this->db, $postData['deskripsi']);
        $tanggal_mulai = mysqli_real_escape_string($this->db, $postData['tanggal_mulai']);
        $anggaran = mysqli_real_escape_string($this->db, $postData['anggaran']);
        $status = mysqli_real_escape_string($this->db, $postData['status']);
        $tanggal_selesai = !empty($postData['tanggal_selesai']) ? "'" . mysqli_real_escape_string($this->db, $postData['tanggal_selesai']) . "'" : "NULL";
        $nama_file_asli = $fileData['gambar']['name'];
        $tmp_name = $fileData['gambar']['tmp_name'];
        $extension = pathinfo($nama_file_asli, PATHINFO_EXTENSION);
        
        $nama_file_baru = $id . '.' . $extension;
        
        $folder_tujuan = 'data/uploads/'; 

        if (move_uploaded_file($tmp_name, $folder_tujuan . $nama_file_baru)) {
            $query = "INSERT INTO proyek_it (id, nama_proyek, manajer_proyek, deskripsi, tanggal_mulai, anggaran, status, gambar, tanggal_selesai) 
                    VALUES ('$id', '$nama', '$manajer', '$deskripsi', '$tanggal_mulai', '$anggaran', '$status', '$nama_file_baru', $tanggal_selesai)";
            
            if (mysqli_query($this->db, $query)) {
                return true;
            }
        }
        return false;
    }
}

if (isset($_POST['simpan'])) {
    $proyek = new ProyekIT($koneksi);
    
    $hasil = $proyek->simpanData($_POST, $_FILES);

    if ($hasil) {
        echo "<script>alert('Data berhasil disimpan!'); window.location='data.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data atau upload gambar!');</script>";
    }
}
?>

<div class="container-fluid px-4">
    <h2 class="mt-4">Tambah Proyek IT</h2>
    <div class="p-4 bg-white rounded mt-3">
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">ID Proyek</label>
                        <input type="text" name="id" class="form-control" required placeholder="Contoh: PRJ-001">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Proyek</label>
                        <input type="text" name="nama_proyek" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Manajer Proyek</label>
                        <input type="text" name="manajer_proyek" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi singkat proyek..."></textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Selesai <span class="text-muted fw-normal">(Opsional)</span></label>
                        <input type="date" name="tanggal_selesai" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Anggaran (Rp)</label>
                        <input type="number" name="anggaran" class="form-control" required placeholder="Contoh: 5000000">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="" disabled selected>Pilih Status...</option>
                            <option value="Perencanaan">Perencanaan</option>
                            <option value="Proses">Proses</option>
                            <option value="On Hold">On Hold</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Upload Gambar (Flowchart/Lainnya)</label>
                        <input type="file" name="gambar" class="form-control" accept="image/*" required>
                    </div>
                    <hr>
                    <button type="submit" name="simpan" class="btn btn-primary px-4 me-2">Simpan</button>
                    <a href="data.php" class="btn btn-secondary px-4">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include 'partials/footer.php'; ?>