<?php 
include 'partials/header.php'; 
require 'koneksi.php';

class ProyekIT {
    private $db;

    public function __construct($koneksi) {
        $this->db = $koneksi;
    }

    public function ambilDataBerdasarkanId($id) {
        $id_clean = mysqli_real_escape_string($this->db, $id);
        $query = "SELECT * FROM proyek_it WHERE id = '$id_clean'";
        $result = mysqli_query($this->db, $query);
        return mysqli_fetch_assoc($result);
    }

    public function updateData($postData, $fileData, $dataLama) {
        $id = mysqli_real_escape_string($this->db, $postData['id']);
        $nama = mysqli_real_escape_string($this->db, $postData['nama_proyek']);
        $manajer = mysqli_real_escape_string($this->db, $postData['manajer_proyek']);
        $deskripsi = mysqli_real_escape_string($this->db, $postData['deskripsi']);
        $tanggal_mulai = mysqli_real_escape_string($this->db, $postData['tanggal_mulai']);
        $anggaran = mysqli_real_escape_string($this->db, $postData['anggaran']);
        $status = mysqli_real_escape_string($this->db, $postData['status']);
        $tanggal_selesai = !empty($postData['tanggal_selesai']) ? "'" . mysqli_real_escape_string($this->db, $postData['tanggal_selesai']) . "'" : "NULL";
        
        $folder_tujuan = 'data/uploads/';
        $nama_file_db = $dataLama['gambar'];

        if (!empty($fileData['gambar']['name'])) {
            $nama_file_asli = $fileData['gambar']['name'];
            $tmp_name = $fileData['gambar']['tmp_name'];
            $extension = pathinfo($nama_file_asli, PATHINFO_EXTENSION);
            
            $nama_file_baru = $id . '.' . $extension; 

            if (!empty($dataLama['gambar']) && file_exists($folder_tujuan . $dataLama['gambar'])) {
                unlink($folder_tujuan . $dataLama['gambar']);
            }

            if (move_uploaded_file($tmp_name, $folder_tujuan . $nama_file_baru)) {
                $nama_file_db = $nama_file_baru;
            } else {
                return false;
            }
        }

        $query = "UPDATE proyek_it SET 
            nama_proyek = '$nama', 
            manajer_proyek = '$manajer', 
            deskripsi = '$deskripsi', 
            tanggal_mulai = '$tanggal_mulai', 
            anggaran = '$anggaran', 
            status = '$status', 
            gambar = '$nama_file_db', 
            tanggal_selesai = $tanggal_selesai 
        WHERE id = '$id'";
        
        if (mysqli_query($this->db, $query)) {
            return true;
        }
        return false;
    }
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID Proyek tidak ditemukan!'); window.location='data.php';</script>";
    exit;
}

$proyek = new ProyekIT($koneksi);
$dataLama = $proyek->ambilDataBerdasarkanId($_GET['id']);

if (!$dataLama) {
    echo "<script>alert('Data tidak ditemukan di database!'); window.location='data.php';</script>";
    exit;
}

if (isset($_POST['update'])) {
    $hasil = $proyek->updateData($_POST, $_FILES, $dataLama);

    if ($hasil) {
        echo "<script>alert('Data proyek berhasil diperbarui!'); window.location='data.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data atau upload gambar!');</script>";
    }
}
?>

<div class="container-fluid px-4">
    <h2 class="mt-4">Edit Proyek IT</h2>
    <div class="p-4 bg-white rounded mt-3">
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary">ID Proyek <span class="small text-danger">(Tidak dapat diubah)</span></label>
                        <input type="text" name="id" class="form-control bg-light" readonly value="<?php echo htmlspecialchars($dataLama['id']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Proyek</label>
                        <input type="text" name="nama_proyek" class="form-control" required value="<?php echo htmlspecialchars($dataLama['nama_proyek']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Manajer Proyek</label>
                        <input type="text" name="manajer_proyek" class="form-control" required value="<?php echo htmlspecialchars($dataLama['manajer_proyek']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"><?php echo htmlspecialchars($dataLama['deskripsi']); ?></textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary">Tanggal Mulai <span class="small text-danger">(Tidak dapat diubah)</span></label>
                        <input type="date" name="tanggal_mulai" class="form-control bg-light" readonly value="<?php echo htmlspecialchars($dataLama['tanggal_mulai']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Selesai <span class="text-muted fw-normal">(Opsional)</span></label>
                        <input type="date" name="tanggal_selesai" class="form-control" value="<?php echo !empty($dataLama['tanggal_selesai']) ? htmlspecialchars($dataLama['tanggal_selesai']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Anggaran (Rp)</label>
                        <input type="number" name="anggaran" class="form-control" required value="<?php echo htmlspecialchars($dataLama['anggaran']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="Perencanaan" <?php echo ($dataLama['status'] == 'Perencanaan') ? 'selected' : ''; ?>>Perencanaan</option>
                            <option value="Proses" <?php echo ($dataLama['status'] == 'Proses') ? 'selected' : ''; ?>>Proses</option>
                            <option value="On Hold" <?php echo ($dataLama['status'] == 'On Hold') ? 'selected' : ''; ?>>On Hold</option>
                            <option value="Selesai" <?php echo ($dataLama['status'] == 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Upload Gambar Baru</label>
                        <input type="file" name="gambar" class="form-control mb-2" accept="image/*">
                        
                        <?php if (!empty($dataLama['gambar']) && file_exists('data/uploads/' . $dataLama['gambar'])): ?>
                            <div class="mt-2 p-2 border rounded bg-light d-inline-block">
                                <span class="d-block small text-muted mb-1 fw-medium">Gambar Saat Ini:</span>
                                <img src="data/uploads/<?php echo $dataLama['gambar']; ?>" alt="Current Image" class="img-thumbnail" style="max-height: 120px;">
                            </div>
                        <?php endif; ?>
                    </div>
                    <hr>
                    <button type="submit" name="update" class="btn btn-primary px-4 me-2">Perbarui Data</button>
                    <a href="data.php" class="btn btn-secondary px-4">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include 'partials/footer.php'; ?>