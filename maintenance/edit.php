<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../includes/config.php';
require_once '../includes/header.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit();
}

// جلب قائمة المركبات
$vehiclesStmt = $conn->prepare("SELECT id, plate_number, vehicle_type, model FROM vehicles");
$vehiclesStmt->execute();
$vehiclesList = $vehiclesStmt->fetchAll(PDO::FETCH_ASSOC);

// جلب بيانات الصيانة الحالية
$stmt = $conn->prepare("SELECT * FROM maintenance WHERE id = ?");
$stmt->execute([$id]);
$maintenance = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$maintenance) {
    header("Location: index.php");
    exit();
}

// تحديث البيانات عند الإرسال
if (isset($_POST['update_maintenance'])) {
    $vehicle_id      = $_POST['vehicle_id'];
    $technician_name = $_POST['technician_name'];
    $fault_type      = $_POST['fault_type'];
    $action_in       = $_POST['action_in'];
    $action_out      = $_POST['action_out'];
    $notes           = $_POST['notes'];
    $entry_date      = $_POST['entry_date'];
    $exit_date       = $_POST['exit_date'];
    $region          = $_POST['region'];
    $status          = $_POST['status'];

    $stmt = $conn->prepare("UPDATE maintenance SET
        vehicle_id = ?, technician_name = ?, fault_type = ?, action_in = ?, action_out = ?,
        notes = ?, entry_date = ?, exit_date = ?, region = ?, status = ?
        WHERE id = ?");
    $stmt->execute([
        $vehicle_id, $technician_name, $fault_type, $action_in, $action_out,
        $notes, $entry_date, $exit_date, $region, $status, $id
    ]);
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>تعديل الصيانة</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.rtl.min.css">
  <link rel="stylesheet" href="../assets/css/cairo-font.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
</head>
<body>
<div class="container mt-4">
  <h4>تعديل بيانات الصيانة</h4>
  <form method="POST" action="">
    <div class="mb-3">
      <label class="form-label">اختر المركبة</label>
      <select name="vehicle_id" id="vehicle_id" class="form-select" required>
        <?php foreach($vehiclesList as $v): ?>
          <option value="<?= $v['id'] ?>" <?= $v['id'] == $maintenance['vehicle_id'] ? 'selected' : '' ?>>
            <?= $v['plate_number']." - ".$v['vehicle_type']." - ".$v['model'] ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">اسم الفني</label>
      <input type="text" name="technician_name" class="form-control" value="<?= $maintenance['technician_name'] ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">نوع العطل</label>
      <input type="text" name="fault_type" class="form-control" value="<?= $maintenance['fault_type'] ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">الإجراء عند الدخول</label>
      <input type="text" name="action_in" class="form-control" value="<?= $maintenance['action_in'] ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">الإجراء عند الخروج</label>
      <input type="text" name="action_out" class="form-control" value="<?= $maintenance['action_out'] ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">ملاحظات</label>
      <textarea name="notes" class="form-control"><?= $maintenance['notes'] ?></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label">تاريخ الدخول</label>
      <input type="date" name="entry_date" class="form-control" value="<?= $maintenance['entry_date'] ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">تاريخ الخروج</label>
      <input type="date" name="exit_date" class="form-control" value="<?= $maintenance['exit_date'] ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">المنطقة</label>
      <input type="text" name="region" class="form-control" value="<?= $maintenance['region'] ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">الحالة</label>
      <select name="status" class="form-select">
        <option value="جارية" <?= $maintenance['status'] == 'جارية' ? 'selected' : '' ?>>جارية</option>
        <option value="منتهية" <?= $maintenance['status'] == 'منتهية' ? 'selected' : '' ?>>منتهية</option>
        <option value="معلقة" <?= $maintenance['status'] == 'معلقة' ? 'selected' : '' ?>>معلقة</option>
      </select>
    </div>
    <button type="submit" name="update_maintenance" class="btn btn-warning">تحديث الصيانة</button>
  </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function(){
  $('#vehicle_id').select2();
});
</script>
</body>
</html>
