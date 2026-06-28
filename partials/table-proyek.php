<?php 
require 'koneksi.php';
$queryStat = "SELECT 
            SUM(CASE WHEN status = 'Perencanaan' THEN 1 ELSE 0 END) AS jml_perencanaan,
            SUM(CASE WHEN status = 'Proses' THEN 1 ELSE 0 END) AS jml_proses,
            SUM(CASE WHEN status = 'On Hold' THEN 1 ELSE 0 END) AS jml_onhold,
            SUM(CASE WHEN status = 'Selesai' THEN 1 ELSE 0 END) AS jml_selesai,
            COUNT(*) AS total_semua
            FROM proyek_it";

$resultStat = mysqli_query($koneksi, $queryStat);
$dataStat = mysqli_fetch_assoc($resultStat);
?>

<div class="row g-2 g-md-3 mb-4">
    <div class="col-4 col-sm-4 col-md-2">
        <div class="card p-2 p-md-3 shadow-sm border-0 bg-primary text-white h-100 filter-card" data-status="Perencanaan" style="cursor: pointer;">
            <div class="d-flex flex-column flex-sm-row align-items-center text-center text-sm-start">
                <i class="bi bi-clipboard-plus fs-4 fs-sm-2 mb-1 mb-sm-0 me-sm-3"></i>
                <div>
                    <h6 class="m-0 text-white-50 small" style="font-size: 0.75rem;">Perencanaan</h6>
                    <h5 class="m-0 fw-bold fs-6 fs-sm-4"><?= $dataStat['jml_perencanaan'] ?></h5>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-4 col-sm-4 col-md-2">
        <div class="card p-2 p-md-3 shadow-sm border-0 bg-warning text-white h-100 filter-card" data-status="Proses" style="cursor: pointer;">
            <div class="d-flex flex-column flex-sm-row align-items-center text-center text-sm-start">
                <i class="bi bi-gear-wide-connected fs-4 fs-sm-2 mb-1 mb-sm-0 me-sm-3"></i>
                <div>
                    <h6 class="m-0 text-white-50 small" style="font-size: 0.75rem;">Proses</h6>
                    <h5 class="m-0 fw-bold fs-6 fs-sm-4"><?= $dataStat['jml_proses'] ?></h5>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-4 col-sm-4 col-md-2">
        <div class="card p-2 p-md-3 shadow-sm border-0 bg-danger text-white h-100 filter-card" data-status="On Hold" style="cursor: pointer;">
            <div class="d-flex flex-column flex-sm-row align-items-center text-center text-sm-start">
                <i class="bi bi-pause-circle fs-4 fs-sm-2 mb-1 mb-sm-0 me-sm-3"></i>
                <div>
                    <h6 class="m-0 text-white-50 small" style="font-size: 0.75rem;">On Hold</h6>
                    <h5 class="m-0 fw-bold fs-6 fs-sm-4"><?= $dataStat['jml_onhold'] ?></h5>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-4 col-sm-4 col-md-2">
        <div class="card p-2 p-md-3 shadow-sm border-0 bg-success text-white h-100 filter-card" data-status="Selesai" style="cursor: pointer;">
            <div class="d-flex flex-column flex-sm-row align-items-center text-center text-sm-start">
                <i class="bi bi-check-circle fs-4 fs-sm-2 mb-1 mb-sm-0 me-sm-3"></i>
                <div>
                    <h6 class="m-0 text-white-50 small" style="font-size: 0.75rem;">Selesai</h6>
                    <h5 class="m-0 fw-bold fs-6 fs-sm-4"><?= $dataStat['jml_selesai'] ?></h5>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-4 col-sm-4 col-md-2">
        <div class="card p-2 p-md-3 shadow-sm border-0 bg-dark text-white h-100 filter-card" data-status="Total" style="cursor: pointer;">
            <div class="d-flex flex-column flex-sm-row align-items-center text-center text-sm-start">
                <i class="bi bi-database fs-4 fs-sm-2 mb-1 mb-sm-0 me-sm-3"></i>
                <div>
                    <h6 class="m-0 text-white-50 small" style="font-size: 0.75rem;">Total</h6>
                    <h5 class="m-0 fw-bold fs-6 fs-sm-4"><?= $dataStat['total_semua'] ?></h5>
                </div>
            </div>
        </div>
    </div>
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
                    <td class="text-center align-middle">
                        <?php if (!empty($row['gambar'])): ?>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalGambar<?php echo $row['id']; ?>" class="text-decoration-none">
                                <img src="data/uploads/<?php echo $row['gambar']; ?>" alt="Gambar" class="img-thumbnail rounded" style="max-width: 60px; height: auto;">
                            </a>

                            <div class="modal fade" id="modalGambar<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="labelGambar<?php echo $row['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-body text-center">
                                            <img src="data/uploads/<?php echo $row['gambar']; ?>" class="img-fluid rounded shadow-sm" alt="Gambar Full">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
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
                                    <div class="modal-content border-0 shadow text-start"> 
                                        <div class="modal-header bg-light border-bottom">
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
                            
                            <a href="pdf-proyek.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success text-white shadow-sm">
                                <i class="bi bi-file-pdf-fill"></i> PDF
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

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
var $dt = jQuery.noConflict(true);

$dt(document).ready(function() {
    var table = $dt('#tabelData').DataTable({
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

    $dt('.filter-card').on('click', function() {
        var status = $dt(this).attr('data-status');
        
        if (status === 'Total') {
            table.column(7).search('').draw();
        } else {
            table.column(7).search(status).draw();
        }
    });
});
</script>