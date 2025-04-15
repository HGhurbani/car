<?php
// import_export/export_pdf.php
require_once '../includes/config.php';

// تأكد من رفع ملف fpdf.php في نفس المجلد أو استخدام مكتبة Composer
require 'fpdf/fpdf.php'; // مثال: قم بتضمين مكتبة FPDF

$query = $conn->prepare("SELECT * FROM vehicles");
$query->execute();
$vehicles = $query->fetchAll(PDO::FETCH_ASSOC);

class PDF extends FPDF {
    // يمكنك إضافة ترويسة وتذييل الصفحة هنا
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'تقرير المركبات', 0, 1, 'C');

$pdf->SetFont('Arial', '', 12);
foreach ($vehicles as $v) {
    $pdf->Cell(0, 10, "ID: {$v['id']} - نوع: {$v['vehicle_type']} - اللوحة: {$v['plate_number']}", 0, 1);
}

$pdf->Output(); // سيعرض ملف PDF
exit;
