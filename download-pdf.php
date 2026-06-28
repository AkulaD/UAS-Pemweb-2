<?php
session_start();
if (!isset($_SESSION['status_login'])) {
    header("location:login.php");
    exit;
}

require 'koneksi.php';
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

$html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Proyek IT</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #333; padding: 6px; }
        th { background-color: #f4f4f4; text-align: center; }
        .text-center { text-align: center; }
        img { max-width: 60px; height: auto; }
        h2 { text-align: center; margin-bottom: 5px; }
        .desc-text { text-align: center; margin-bottom: 20px; color: #555; font-size: 12px; }
    </style>
</head>
<body>
    <h2>Laporan Data Proyek IT</h2>
    <div class="desc-text">
        Dokumen ini merupakan laporan rekapitulasi data proyek IT yang mencakup detail pelaksanaan, alokasi anggaran, status pekerjaan, serta deskripsi spesifik dari masing-masing proyek.
    </div>
    <hr style="margin-bottom: 15px; border-color: #ccc;">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Gambar</th>
                <th>ID Proyek</th>
                <th>Nama Proyek</th>
                <th>Manajer</th>
                <th>Deskripsi</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Anggaran</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>';

$query = "SELECT * FROM proyek_it ORDER BY tanggal_mulai DESC";
$result = mysqli_query($koneksi, $query);
$no = 1;

while ($row = mysqli_fetch_assoc($result)) {
    $anggaran = 'Rp ' . number_format($row['anggaran'], 0, ',', '.');
    $tgl_mulai = date('d-m-Y', strtotime($row['tanggal_mulai']));
    $tgl_selesai = !empty($row['tanggal_selesai']) ? date('d-m-Y', strtotime($row['tanggal_selesai'])) : '-';
    
    $gambarPath = 'data/uploads/' . $row['gambar'];
    $gambarHtml = '-';
    
    if (!empty($row['gambar']) && file_exists($gambarPath)) {
        $type = pathinfo($gambarPath, PATHINFO_EXTENSION);
        $data = file_get_contents($gambarPath);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        $gambarHtml = '<img src="' . $base64 . '" alt="Gambar">';
    }

    $html .= '<tr>
        <td class="text-center">' . $no++ . '</td>
        <td class="text-center">' . $gambarHtml . '</td>
        <td class="text-center">' . $row['id'] . '</td>
        <td>' . $row['nama_proyek'] . '</td>
        <td>' . $row['manajer_proyek'] . '</td>
        <td>' . nl2br(htmlspecialchars($row['deskripsi'])) . '</td>
        <td class="text-center">' . $tgl_mulai . '</td>
        <td class="text-center">' . $tgl_selesai . '</td>
        <td>' . $anggaran . '</td>
        <td class="text-center">' . $row['status'] . '</td>
    </tr>';
}

$html .= '</tbody>
    </table>
</body>
</html>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("Laporan_Proyek_IT.pdf", array("Attachment" => 0));
?>