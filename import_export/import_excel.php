<?php
// import_export/import_excel.php
require_once '../includes/config.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_POST['import_excel'])) {
    if (isset($_FILES['excel_file']['tmp_name']) && $_FILES['excel_file']['tmp_name'] != '') {
        $filePath = $_FILES['excel_file']['tmp_name'];

        // قراءة ملف Excel
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // افترض أن الصف الأول عناوين
        unset($rows[0]);

        foreach($rows as $row) {
            // على حسب ترتيب الأعمدة
            $vehicle_type = $row[0];
            $model        = $row[1];
            $plate_number = $row[2];

            // أدخل البيانات لجدول vehicles مثلاً
            $stmt = $conn->prepare("INSERT INTO vehicles (vehicle_type, model, plate_number)
                                    VALUES (?,?,?)");
            $stmt->execute([$vehicle_type, $model, $plate_number]);
        }
    }
    header("Location: ../vehicles/index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>استيراد Excel</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.rtl.min.css">
  <link rel="stylesheet" href="../assets/css/cairo-font.css">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container mt-4">
    <h4>استيراد بيانات من Excel</h4>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">اختر ملف Excel</label>
            <input type="file" name="excel_file" class="form-control" required>
        </div>
        <button type="submit" name="import_excel" class="btn btn-primary">استيراد</button>
    </form>
</div>
</body>
</html>
