<?php require_once '../includes/header.php'; ?>
<?php
// maintenance/add.php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../includes/config.php';

// جلب قائمة المركبات لاستخدامها في select2
$vehiclesStmt = $conn->prepare("SELECT id, plate_number, vehicle_type, model FROM vehicles");
$vehiclesStmt->execute();
$vehiclesList = $vehiclesStmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['save_maintenance'])) {
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

    $stmt = $conn->prepare("INSERT INTO maintenance
      (vehicle_id, technician_name, fault_type, action_in, action_out, notes, entry_date, exit_date, region, status)
      VALUES (?,?,?,?,?,?,?,?,?,?)");
    $stmt->execute([
      $vehicle_id,
      $technician_name,
      $fault_type,
      $action_in,
      $action_out,
      $notes,
      $entry_date,
      $exit_date,
      $region,
      $status
    ]);
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>إضافة صيانة</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.rtl.min.css">
  <link rel="stylesheet" href="../assets/css/cairo-font.css">
  <link rel="stylesheet" href="../assets/css/style.css">

  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-4">
  <h4>إضافة صيانة</h4>
  <form method="POST" action="">
    <div class="mb-3">
      <label class="form-label">اختر المركبة</label>
      <select name="vehicle_id" id="vehicle_id" class="form-select" required>
        <option value="">-- اختر المركبة --</option>
        <?php foreach($vehiclesList as $v): ?>
          <option value="<?php echo $v['id']; ?>">
            <?php echo $v['plate_number']." - ".$v['vehicle_type']." - ".$v['model']; ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">اسم الفني</label>
      <input type="text" name="technician_name" id="technician_name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">نوع العطل</label>
      <input type="text" name="fault_type" id="fault_type" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">الإجراء عند الدخول</label>
      <input type="text" name="action_in" id="action_in" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">الإجراء عند الخروج</label>
      <input type="text" name="action_out" id="action_out" class="form-control">
    </div>
    <div class="mb-3">
      <label class="form-label">ملاحظات</label>
      <textarea name="notes" id="notes" class="form-control"></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label">تاريخ الدخول</label>
      <input type="date" name="entry_date" id="entry_date" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">تاريخ الخروج</label>
      <input type="date" name="exit_date" id="exit_date" class="form-control">
    </div>
    <div class="mb-3">
      <label class="form-label">المنطقة</label>
      <input type="text" name="region" id="region" class="form-control">
    </div>
    <div class="mb-3">
      <label class="form-label">الحالة</label>
      <select name="status" id="status" class="form-select">
        <option value="جارية">جارية</option>
        <option value="منتهية">منتهية</option>
        <option value="معلقة">معلقة</option>
      </select>
    </div>
    <button type="submit" name="save_maintenance" class="btn btn-primary">حفظ الصيانة</button>
  </form>
</div>

<!-- jQuery + Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function(){
  $('#vehicle_id').select2();
});
</script>
</body>
</html>
