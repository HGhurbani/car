<?php require_once '../includes/header.php'; ?>
<?php
// vehicles/edit.php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../includes/config.php';

// جلب بيانات المركبة الحالية
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}
$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM vehicles WHERE id = ?");
$stmt->execute([$id]);
$vehicle = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$vehicle) {
    // لم يتم العثور على المركبة
    header("Location: index.php");
    exit();
}

// إذا تم إرسال النموذج لتحديث البيانات
if (isset($_POST['update_vehicle'])) {
    $vehicle_type            = $_POST['vehicle_type'];
    $model                   = $_POST['model'];
    $plate_number            = $_POST['plate_number'];
    $chassis_number          = $_POST['chassis_number'];
    $general_security_number = $_POST['general_security_number'];
    $sub_entity              = $_POST['sub_entity'];
    $category                = $_POST['category'];

    $updateStmt = $conn->prepare("UPDATE vehicles SET 
        vehicle_type = ?, 
        model = ?, 
        plate_number = ?, 
        chassis_number = ?, 
        general_security_number = ?, 
        sub_entity = ?, 
        category = ?
        WHERE id = ?
    ");
    $updateStmt->execute([
        $vehicle_type,
        $model,
        $plate_number,
        $chassis_number,
        $general_security_number,
        $sub_entity,
        $category,
        $id
    ]);

    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>تعديل مركبة</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.rtl.min.css">
  <link rel="stylesheet" href="../assets/css/cairo-font.css">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container mt-4">
    <h4>تعديل مركبة</h4>
    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label">نوع العربة</label>
            <input type="text" name="vehicle_type" class="form-control" value="<?php echo $vehicle['vehicle_type']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">الموديل</label>
            <input type="text" name="model" class="form-control" value="<?php echo $vehicle['model']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">رقم اللوحة</label>
            <input type="text" name="plate_number" class="form-control" value="<?php echo $vehicle['plate_number']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">رقم الهيكل</label>
            <input type="text" name="chassis_number" class="form-control" value="<?php echo $vehicle['chassis_number']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">رقم الأمن العام</label>
            <input type="text" name="general_security_number" class="form-control" value="<?php echo $vehicle['general_security_number']; ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">الجهة الفرعية</label>
            <input type="text" name="sub_entity" class="form-control" value="<?php echo $vehicle['sub_entity']; ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">الفئة</label>
            <input type="text" name="category" class="form-control" value="<?php echo $vehicle['category']; ?>">
        </div>
        <button type="submit" name="update_vehicle" class="btn btn-success">تحديث</button>
    </form>
</div>
</body>
</html>
