<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../includes/config.php';
require_once '../includes/header.php';

$techStmt = $conn->prepare("SELECT DISTINCT technician_name FROM maintenance WHERE technician_name IS NOT NULL AND technician_name != ''");
$techStmt->execute();
$technicians = $techStmt->fetchAll(PDO::FETCH_COLUMN);

$regionStmt = $conn->prepare("SELECT DISTINCT region FROM maintenance WHERE region IS NOT NULL AND region != ''");
$regionStmt->execute();
$regions = $regionStmt->fetchAll(PDO::FETCH_COLUMN);

$where = [];
$params = [];

if (!empty($_GET['vehicle_type'])) {
    $where[] = "v.vehicle_type = :vehicle_type";
    $params[':vehicle_type'] = $_GET['vehicle_type'];
}
if (!empty($_GET['technician_name'])) {
    $where[] = "m.technician_name = :technician_name";
    $params[':technician_name'] = $_GET['technician_name'];
}
if (!empty($_GET['entry_date'])) {
    $where[] = "m.entry_date = :entry_date";
    $params[':entry_date'] = $_GET['entry_date'];
}
if (!empty($_GET['exit_date'])) {
    $where[] = "m.exit_date = :exit_date";
    $params[':exit_date'] = $_GET['exit_date'];
}
if (!empty($_GET['status'])) {
    $where[] = "m.status = :status";
    $params[':status'] = $_GET['status'];
}
if (!empty($_GET['region'])) {
    $where[] = "m.region = :region";
    $params[':region'] = $_GET['region'];
}

$sql = "SELECT m.*, v.plate_number, v.vehicle_type, v.model 
        FROM maintenance m
        JOIN vehicles v ON m.vehicle_id = v.id";
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY m.id DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$maintenanceRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>إدارة الصيانات</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.rtl.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
  <link rel="stylesheet" href="../assets/css/cairo-font.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root {
      --primary-color: #4361ee;
      --secondary-color: #3f37c9;
      --success-color: #06d6a0;
      --info-color: #4cc9f0;
      --warning-color: #ffd60a;
      --danger-color: #ef476f;
    }

    body {
      font-family: 'Cairo', sans-serif;
      background-color: #f8f9fa;
    }

    .main-container {
      background: #ffffff;
      padding: 2rem;
      margin: 2rem auto;
      border-radius: 1rem;
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
      border: 1px solid #e9ecef;
    }

    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
      padding-bottom: 1.5rem;
      border-bottom: 2px solid #f1f3f5;
    }

    .page-title {
      color: #2b2d42;
      font-weight: 700;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .filter-card {
      background: #f8f9fa;
      border-radius: 0.75rem;
      padding: 1.5rem;
      margin-bottom: 2rem;
    }

    .filter-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      align-items: end;
    }

    .btn-add {
      background: #4361ee;
      border: none;
      padding: 0.75rem 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .btn-add:hover {
      background: #4361ee;
    }

    .filter-card {
      background: #f8f9fa;
      border-radius: 0.75rem;
      padding: 1.5rem;
      margin-bottom: 2rem;
    }

    .form-control, .form-select {
      border: 1px solid #e9ecef;
      border-radius: 0.75rem;
      padding: 0.75rem 1rem;
      transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
    }

    .btn {
      border-radius: 0.75rem;
      padding: 0.75rem 1.5rem;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-primary {
      background: var(--primary-color);
      border: none;
    }

    .btn-primary:hover {
      background: var(--secondary-color);
    }

    .table-wrapper {
      border-radius: 0.75rem;
      overflow: hidden;
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
    }

    .table {
      margin-bottom: 0;
      border-collapse: separate;
      border-spacing: 0;
    }

    .table thead th {
      background: var(--primary-color);
      color: white;
      border-bottom: none;
      padding: 1rem;
    }

    .table tbody td {
      vertical-align: middle;
      padding: 1rem;
      background: white;
      border-bottom: 1px solid #e9ecef;
    }

    .table-hover tbody tr:hover td {
      background-color: #f8f9fa;
    }

    .status-badge {
      padding: 0.5rem 0.75rem;
      border-radius: 0.5rem;
      font-size: 0.875rem;
      font-weight: 600;
    }

    .badge-new {
      background: #e3f2fd;
      color: #1976d2;
    }

    .badge-inprogress {
      background: #fff3e0;
      color: #ef6c00;
    }

    .badge-completed {
      background: #e8f5e9;
      color: #2e7d32;
    }

    .action-btns .btn {
      padding: 0.5rem 0.75rem;
      margin: 0 0.25rem;
    }
  </style>
</head>
<body>

<div class="main-container">
  <div class="page-header">
    <h1 class="page-title">
      <i class="fas fa-tools fa-xl" style="color: var(--primary-color);"></i>
      إدارة سجلات الصيانة
    </h1>
    <a href="add.php" class="btn btn-add" style="color:white">
      <i class="fas fa-plus-circle"></i>
      إضافة صيانة جديدة
    </a>
  </div>

  <div class="filter-card">
    <form method="GET" class="filter-grid">
      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary w-100">
          <i class="fas fa-filter me-2"></i>فلترة النتائج
        </button>
        <a href="index.php" class="btn btn-outline-secondary w-100">
          <i class="fas fa-sync-alt me-2"></i>إعادة تعيين
        </a>
      </div>

      <select name="technician_name" class="form-select">
        <option value="">جميع الفنيين...</option>
        <?php foreach ($technicians as $name): ?>
          <option value="<?= $name ?>" <?= ($_GET['technician_name'] ?? '') === $name ? 'selected' : '' ?>><?= $name ?></option>
        <?php endforeach; ?>
      </select>

      <select name="vehicle_type" class="form-select">
        <option value="">جميع الأنواع...</option>
        <option value="سيارة" <?= ($_GET['vehicle_type'] ?? '') == 'سيارة' ? 'selected' : '' ?>>سيارة</option>
        <option value="دراجة" <?= ($_GET['vehicle_type'] ?? '') == 'دراجة' ? 'selected' : '' ?>>دراجة</option>
      </select>

      <select name="status" class="form-select">
        <option value="">جميع الحالات...</option>
        <option value="جديدة" <?= ($_GET['status'] ?? '') == 'جديدة' ? 'selected' : '' ?>>جديدة</option>
        <option value="قيد الإصلاح" <?= ($_GET['status'] ?? '') == 'قيد الإصلاح' ? 'selected' : '' ?>>قيد الإصلاح</option>
        <option value="منتهية" <?= ($_GET['status'] ?? '') == 'منتهية' ? 'selected' : '' ?>>منتهية</option>
      </select>

      <select name="region" class="form-select">
        <option value="">جميع المناطق...</option>
        <?php foreach ($regions as $region): ?>
          <option value="<?= $region ?>" <?= ($_GET['region'] ?? '') === $region ? 'selected' : '' ?>><?= $region ?></option>
        <?php endforeach; ?>
      </select>

      <!--<div class="date-filters">-->
      <!--  <input type="date" name="entry_date" class="form-control" -->
      <!--         value="<?= $_GET['entry_date'] ?? '' ?>" -->
      <!--         placeholder="تاريخ البدء">-->
      <!--  <input type="date" name="exit_date" class="form-control" -->
      <!--         value="<?= $_GET['exit_date'] ?? '' ?>" -->
      <!--         placeholder="تاريخ الانتهاء">-->
      <!--</div>-->
    </form>
  </div>

  <div class="table-wrapper">
    <table id="maintenanceTable" class="table table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>رقم اللوحة</th>
          <th>نوع العربة</th>
          <th>الفني</th>
          <th>نوع العطل</th>
          <th>تاريخ الدخول</th>
          <th>تاريخ الخروج</th>
          <th>الحالة</th>
          <th>المنطقة</th>
          <th>الإجراءات</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($maintenanceRecords as $m): ?>
          <tr>
            <td><?= $m['id']; ?></td>
            <td><?= $m['plate_number']; ?></td>
            <td><?= $m['vehicle_type']; ?></td>
            <td><?= $m['technician_name']; ?></td>
            <td><?= $m['fault_type']; ?></td>
            <td><?= $m['entry_date']; ?></td>
            <td><?= $m['exit_date']; ?></td>
            <td>
              <?php 
                $statusClass = match($m['status']) {
                  'جديدة' => 'badge-new',
                  'قيد الإصلاح' => 'badge-inprogress',
                  'منتهية' => 'badge-completed',
                  default => ''
                };
              ?>
              <span class="status-badge <?= $statusClass ?>">
                <?= $m['status']; ?>
              </span>
            </td>
            <td><?= $m['region']; ?></td>
            <td class="action-btns">
              <a href="view.php?id=<?= $m['id']; ?>" class="btn btn-sm btn-info">
                <i class="fas fa-eye"></i>
              </a>
              <a href="edit.php?id=<?= $m['id']; ?>" class="btn btn-sm btn-warning">
                <i class="fas fa-edit"></i>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script>
$(document).ready(function() {
    $('#maintenanceTable').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json"
        },
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'print',
                text: 'طباعة',
                exportOptions: { columns: ':visible' },
                customize: function (win) {
                    $(win.document.body)
                        .css('direction', 'rtl')
                        .css('font-family', 'Cairo')
                        .prepend('<h3 style="text-align:center;">تقرير الصيانات</h3>');
                }
            }
        ]
    });
});
</script>
</body>
</html>