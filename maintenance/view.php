<?php require_once '../includes/header.php'; ?>
<?php
// maintenance/view.php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../includes/config.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}
$id = $_GET['id'];

// جلب بيانات الصيانة + بيانات المركبة
$stmt = $conn->prepare("SELECT m.*, v.plate_number, v.vehicle_type, v.model 
                       FROM maintenance m
                       JOIN vehicles v ON m.vehicle_id = v.id
                       WHERE m.id = ?");
$stmt->execute([$id]);
$maintenance = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$maintenance) {
    // لم يتم العثور على الصيانة
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>تفاصيل الصيانة</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.rtl.min.css">
  <link rel="stylesheet" href="../assets/css/cairo-font.css">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container mt-4">
    <h4>تفاصيل الصيانة رقم <?php echo $maintenance['id']; ?></h4>
    <hr>
    <p><strong>المركبة:</strong> <?php echo $maintenance['plate_number']." / ".$maintenance['vehicle_type']." / ".$maintenance['model']; ?></p>
    <p><strong>اسم الفني:</strong> <?php echo $maintenance['technician_name']; ?></p>
    <p><strong>نوع العطل:</strong> <?php echo $maintenance['fault_type']; ?></p>
    <p><strong>الإجراء عند الدخول:</strong> <?php echo $maintenance['action_in']; ?></p>
    <p><strong>الإجراء عند الخروج:</strong> <?php echo $maintenance['action_out']; ?></p>
    <p><strong>ملاحظات:</strong> <?php echo $maintenance['notes']; ?></p>
    <p><strong>تاريخ الدخول:</strong> <?php echo $maintenance['entry_date']; ?></p>
    <p><strong>تاريخ الخروج:</strong> <?php echo $maintenance['exit_date']; ?></p>
    <p><strong>المنطقة:</strong> <?php echo $maintenance['region']; ?></p>
    <p><strong>الحالة:</strong> <?php echo $maintenance['status']; ?></p>
</div>
</body>
</html>
