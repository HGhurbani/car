<?php require_once '../includes/header.php'; ?>
<?php
// vehicles/add.php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../includes/config.php';

if (isset($_POST['save_vehicle'])) {
    $vehicle_type            = $_POST['vehicle_type'];
    $model                   = $_POST['model'];
    $plate_number            = $_POST['plate_number'];
    $chassis_number          = $_POST['chassis_number'];
    $general_security_number = $_POST['general_security_number'];
    $sub_entity              = $_POST['sub_entity'];
    $category                = $_POST['category'];

    $stmt = $conn->prepare("INSERT INTO vehicles 
        (vehicle_type, model, plate_number, chassis_number, general_security_number, sub_entity, category)
        VALUES (?,?,?,?,?,?,?)");
    $stmt->execute([
        $vehicle_type,
        $model,
        $plate_number,
        $chassis_number,
        $general_security_number,
        $sub_entity,
        $category
    ]);

    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>إضافة مركبة</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.rtl.min.css">
  <link rel="stylesheet" href="../assets/css/cairo-font.css">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container mt-4">
    <h4>إضافة مركبة</h4>
    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label">نوع العربة</label>
            <input type="text" name="vehicle_type" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">الموديل</label>
            <input type="text" name="model" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">رقم اللوحة</label>
            <input type="text" name="plate_number" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">رقم الهيكل</label>
            <input type="text" name="chassis_number" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">رقم الأمن العام</label>
            <input type="text" name="general_security_number" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">الجهة الفرعية</label>
            <input type="text" name="sub_entity" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">الفئة</label>
            <input type="text" name="category" class="form-control">
        </div>
        <button type="submit" name="save_vehicle" class="btn btn-primary">حفظ</button>
    </form>
</div>
</body>
</html>
