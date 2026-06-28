<?php
require 'koneksi.php';
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$id = mysqli_real_escape_string($koneksi, $_GET['id']);
$query = mysqli_query($koneksi, "SELECT * FROM proyek_it WHERE id = '$id'");
$row = mysqli_fetch_assoc($query);

$html = '<!DOCTYPE html>
<html>
<head>
    <title>Detail Proyek - ' . $row['id'] . '</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .judul { text-align: center; margin-bottom: 20px; font-size: 18px; font-weight: bold; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { padding: 8px 10px; border: none; text-align: left; vertical-align: top; }
        th { width: 20%; }
        .text-center { text-align: center; }
        .img-box { text-align: center; margin: 20px 0; border: none; padding: 10px; }
        .img-box img { max-width: 100%; max-height: 300px; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 11px; color: white; }
        .bg-success { background-color: #198754; }
        .bg-warning { background-color: #ffc107; color: #000; }
    </style>
</head>
<body>

    <div class="judul">Laporan Detail</div>
    <hr style="margin-bottom: 15px; border-color: #ccc;">
    <table>
        <tr>
            <th>ID Proyek</th>
            <td style="width: 30%;"><strong>' . htmlspecialchars($row['id']) . '</strong></td>
            <th style="width: 20%;">Nama Proyek</th>
            <td style="width: 30%;">' . htmlspecialchars($row['nama_proyek']) . '</td>
        </tr>
    </table>';

if (!empty($row['gambar'])) {
    $imagePath = 'data/uploads/' . $row['gambar'];
    if (file_exists($imagePath)) {
        $type = pathinfo($imagePath, PATHINFO_EXTENSION);
        $data = file_get_contents($imagePath);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        $html .= '<div class="img-box"><img src="' . $base64 . '" alt="Gambar Proyek"></div>';
    } else {
        $html .= '<div class="img-box"><em>File gambar tidak ditemukan di server.</em></div>';
    }
} else {
    $html .= '<div class="img-box"><em>Tidak ada gambar yang dilampirkan.</em></div>';
}

$statusBadge = (strtolower($row['status']) == 'selesai') ? '<span class="badge bg-success">'.$row['status'].'</span>' : '<span class="badge bg-warning">'.$row['status'].'</span>';
$tglSelesai = !empty($row['tanggal_selesai']) ? date('d F Y', strtotime($row['tanggal_selesai'])) : '-';

$html .= '<table>
        <tr>
            <th>Tanggal Dimulai</th>
            <td style="width: 30%;">' . date('d F Y', strtotime($row['tanggal_mulai'])) . '</td>
            <th style="width: 20%;">Status Pekerjaan</th>
            <td style="width: 30%;">' . $statusBadge . '</td>
        </tr>
        <tr>
            <th>Tanggal Selesai</th>
            <td>' . $tglSelesai . '</td>
            <th>Manajer Proyek</th>
            <td>' . htmlspecialchars($row['manajer_proyek']) . '</td>
        </tr>
        <tr>
            <th>Anggaran</th>
            <td colspan="3" style="color: #198754; font-weight: bold;">Rp ' . number_format($row['anggaran'], 0, ',', '.') . '</td>
        </tr>
        <tr>
            <th>Deskripsi</th>
            <td colspan="3" style="line-height: 1.5;">' . nl2br(htmlspecialchars($row['deskripsi'])) . '</td>
        </tr>
    </table>

</body>
</html>';

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$filename = "Proyek_IT_" . $row['id'] . ".pdf";
$dompdf->stream($filename, array("Attachment" => false));
?>