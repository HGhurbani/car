<?php
// import_export/export_excel.php
require_once '../includes/config.php';
require 'vendor/autoload.php'; // تأكد أنك ثبّتت مكتبة phpoffice/phpspreadsheet عبر Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// عناوين الأعمدة
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'نوع المركبة');
$sheet->setCellValue('C1', 'الموديل');
$sheet->setCellValue('D1', 'رقم اللوحة');

$stmt = $conn->prepare("SELECT * FROM vehicles");
$stmt->execute();
$vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);

$row = 2;
foreach ($vehicles as $v) {
    $sheet->setCellValue('A'.$row, $v['id']);
    $sheet->setCellValue('B'.$row, $v['vehicle_type']);
    $sheet->setCellValue('C'.$row, $v['model']);
    $sheet->setCellValue('D'.$row, $v['plate_number']);
    $row++;
}

$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="vehicles.xlsx"');
$writer->save('php://output');
exit;
