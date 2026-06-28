<?php
include 'partials/header.php';
require 'koneksi.php';
?>

<div class="mb-4">
    <h2 class="title m-0 fw-bold">Data Proyek IT</h2>
    <p class="subtitle text-muted m-0">Data proyek IT yang ada</p>
</div>

<div class="d-flex mb-4">
    <a class="btn btn-primary me-2 px-3 shadow-sm" href="tambah-proyek.php">
        <i class="bi bi-plus-lg"></i> Add Data
    </a>
    <a class="btn btn-secondary px-3 shadow-sm" href="download-pdf.php" target="_blank">
        <i class="bi bi-file-earmark-pdf"></i> Report PDF
    </a>
</div>

<div class="bg-white p-4 rounded">
    <div class="table-responsive">
        <table id="tabelData" class="table table-striped table-hover align-middle w-100 m-0 border">
            <thead class="table-light text-secondary">
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th class="text-center" width="10%">Gambar</th>
                    <th>ID Proyek</th>
                    <th>Nama Proyek</th>
                    <th>Manajer</th>
                    <th>Tanggal Mulai</th>
                    <th>Anggaran</th>
                    <th class="text-center">Status</th>
                    <th class="text-center" width="20%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $query = mysqli_query($koneksi, "SELECT * FROM proyek_it ORDER BY id DESC");
                
                if (mysqli_num_rows($query) > 0) {
                    while ($row = mysqli_fetch_assoc($query)) {
                ?>
                <tr>
                    <td class="text-center fw-bold"><?php echo $no++; ?></td>
                    <td class="text-center">
                        <?php if (!empty($row['gambar'])): ?>
                            <img src="data/uploads/<?php echo $row['gambar']; ?>" alt="Gambar" class="img-thumbnail rounded" style="max-width: 60px; height: auto;">
                        <?php else: ?>
                            <span class="text-muted"><i class="bi bi-image" style="font-size: 1.5rem;"></i></span>
                        <?php endif; ?>
                    </td>
                    <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($row['id']); ?></span></td>
                    <td class="fw-semibold"><?php echo htmlspecialchars($row['nama_proyek']); ?></td>
                    <td><?php echo htmlspecialchars($row['manajer_proyek']); ?></td>
                    <td><?php echo date('Y-m-d', strtotime($row['tanggal_mulai'])); ?></td>
                    <td class="fw-medium">Rp <?php echo number_format($row['anggaran'], 0, ',', '.'); ?></td>
                    <td class="text-center">
                        <?php 
                        $status = $row['status'];
                        $badgeClass = (strtolower($status) == 'selesai') ? 'bg-success text-white' : 'bg-warning text-dark';
                        ?>
                        <span class="badge <?php echo $badgeClass; ?> px-2 py-1"><?php echo $status; ?></span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            
                            <button type="button" class="btn btn-sm btn-info text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#modalDetail<?php echo $row['id']; ?>">
                                <i class="bi bi-info-circle"></i> Detail
                            </button>

                            <div class="modal fade" id="modalDetail<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="label<?php echo $row['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content border-0 shadow text-start"> <div class="modal-header bg-light border-bottom">
                                            <h5 class="modal-title fw-bold text-dark" id="label<?php echo $row['id']; ?>">
                                                <i class="bi bi-folder-symlink me-2 text-info"></i>Detail <?php echo htmlspecialchars($row['nama_proyek']); ?>
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        
                                        <div class="modal-body p-4">
                                            <div class="row g-4 align-items-center">
                                                
                                                <div class="col-md-4 text-center border-end">
                                                    <div class="p-2 border rounded bg-light d-inline-block w-100">
                                                        <?php if (!empty($row['gambar'])): ?>
                                                            <img src="data/uploads/<?php echo $row['gambar']; ?>" alt="Flowchart" class="img-fluid rounded" style="max-height: 220px; object-fit: contain;">
                                                        <?php else: ?>
                                                            <div class="text-muted p-4">
                                                                <i class="bi bi-image" style="font-size: 3.5rem;"></i>
                                                                <p class="small m-0 mt-2">Tidak ada gambar</p>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-8">
                                                    <table class="table table-borderless align-middle m-0">
                                                        <tr>
                                                            <td width="35%" class="text-secondary fw-medium">ID Proyek</td>
                                                            <td width="5%">:</td>
                                                            <td><span class="badge bg-light text-dark border fw-bold px-2 py-1"><?php echo htmlspecialchars($row['id']); ?></span></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-secondary fw-medium">Nama Proyek</td>
                                                            <td>:</td>
                                                            <td class="fw-semibold text-dark"><?php echo htmlspecialchars($row['nama_proyek']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-secondary fw-medium">Manajer Proyek</td>
                                                            <td>:</td>
                                                            <td><?php echo htmlspecialchars($row['manajer_proyek']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-secondary fw-medium">Tanggal Mulai</td>
                                                            <td>:</td>
                                                            <td class="text-muted"><?php echo date('d F Y', strtotime($row['tanggal_mulai'])); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-secondary fw-medium">Tanggal Selesai</td>
                                                            <td>:</td>
                                                            <td class="text-muted"><?php if(!empty($row['tanggal_selesai'])){ echo date('d F Y', strtotime($row['tanggal_selesai']));}else{ echo '-';}; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-secondary fw-medium">Anggaran Proyek</td>
                                                            <td>:</td>
                                                            <td class="fw-bold text-success">Rp <?php echo number_format($row['anggaran'], 0, ',', '.'); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-secondary fw-medium">Status Pekerjaan</td>
                                                            <td>:</td>
                                                            <td>
                                                                <span class="badge <?php echo (strtolower($row['status']) == 'selesai') ? 'bg-success text-white' : 'bg-warning text-dark'; ?> px-2.5 py-1.5 fs-7">
                                                                    <?php echo $row['status']; ?>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <p class="fw-medium text-secondary mb-0 mt-1">Deskripsi:</p>
                                                    <p><?php echo $row['deskripsi']; ?></p>
                                                </div>

                                            </div>
                                        </div>
                                        
                                        <div class="modal-footer bg-light border-top">
                                            <button type="button" class="btn btn-secondary px-4 shadow-sm" data-bs-dismiss="modal">Tutup</button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            
                            <a href="edit-proyek.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning text-white shadow-sm">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <a href="hapus-proyek.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger shadow-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data proyek ini?')">
                                <i class="bi bi-trash"></i> Hapus
                            </a>
                        </div>
                    </td>
                </tr>
                <?php 
                    }
                } 
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'partials/footer.php'; ?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
var $dt = jQuery.noConflict(true);

$dt(document).ready(function() {
    $dt('#tabelData').DataTable({
        "destroy": true,
        "language": {
            "lengthMenu": "Show _MENU_ entries",
            "search": "Search:",
            "zeroRecords": "Data tidak ditemukan",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty": "Showing 0 to 0 of 0 entries",
            "infoFiltered": "(difilter dari _MAX_ total data)",
            "paginate": {
                "first": "First",
                "last": "Last",
                "next": "Next",
                "previous": "Previous"
            }
        }
    });
});
</script>
