<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../includes/config.php';
require_once '../includes/header.php';

$technicians = $conn->query("SELECT DISTINCT technician_name FROM maintenance WHERE technician_name != ''")->fetchAll(PDO::FETCH_COLUMN);
$regions = $conn->query("SELECT DISTINCT region FROM maintenance WHERE region != ''")->fetchAll(PDO::FETCH_COLUMN);
$statuses = ['جديدة', 'قيد الإصلاح', 'منتهية'];

$where = [];
$params = [];

if (!empty($_GET['technician_name'])) {
    $where[] = "m.technician_name = :technician_name";
    $params[':technician_name'] = $_GET['technician_name'];
}
if (!empty($_GET['status'])) {
    $where[] = "m.status = :status";
    $params[':status'] = $_GET['status'];
}
if (!empty($_GET['entry_date'])) {
    $where[] = "m.entry_date = :entry_date";
    $params[':entry_date'] = $_GET['entry_date'];
}
if (!empty($_GET['exit_date'])) {
    $where[] = "m.exit_date = :exit_date";
    $params[':exit_date'] = $_GET['exit_date'];
}
if (!empty($_GET['region'])) {
    $where[] = "m.region = :region";
    $params[':region'] = $_GET['region'];
}

$sql = "SELECT m.*, v.plate_number, v.vehicle_type, v.model 
        FROM maintenance m
        JOIN vehicles v ON m.vehicle_id = v.id";

if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY m.id DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>تقرير الصيانات</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.rtl.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
  <link rel="stylesheet" href="../assets/css/cairo-font.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root {
      --primary-color: #3b5bdb;
      --secondary-color: #4263eb;
      --report-bg: #f8f9fa;
    }

    body {
      font-family: 'Cairo', sans-serif;
      background-color: var(--report-bg);
    }

    .report-container {
      background: #ffffff;
      padding: 2rem;
      margin: 2rem auto;
      border-radius: 1rem;
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
      max-width: 1400px;
    }

    .report-header {
      text-align: center;
      margin-bottom: 2rem;
      padding-bottom: 1.5rem;
      border-bottom: 2px solid #f1f3f5;
    }

    .report-title {
      color: #2b2d42;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 1rem;
      justify-content: center;
    }

    .filter-card {
      background: #f8f9fa;
      border-radius: 0.75rem;
      padding: 1.5rem;
      margin-bottom: 2rem;
    }

    .filter-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 1rem;
      align-items: end;
    }

    .form-control, .form-select {
      border: 1px solid #e9ecef;
      border-radius: 0.75rem;
      padding: 0.75rem 1rem;
      transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(59, 91, 219, 0.15);
    }

    .btn {
      border-radius: 0.75rem;
      padding: 0.75rem 1.25rem;
      font-weight: 600;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 0.5rem;
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
      font-weight: 700;
    }

    .table tbody td {
      vertical-align: middle;
      padding: 1rem;
      background: white;
      border-bottom: 1px solid #e9ecef;
    }

    .status-badge {
      padding: 0.5rem 0.75rem;
      border-radius: 0.5rem;
      font-size: 0.875rem;
      font-weight: 600;
    }

    .status-new { background: #e3f2fd; color: #1976d2; }
    .status-inprogress { background: #fff3e0; color: #ef6c00; }
    .status-completed { background: #e8f5e9; color: #2e7d32; }

    @media (max-width: 768px) {
      .report-container {
        padding: 1rem;
        margin: 1rem;
      }
      
      .filter-grid {
        grid-template-columns: 1fr;
      }
      
      .btn span {
        display: none;
      }
    }
  </style>
</head>
<body>

<div class="report-container">
  <div class="report-header">
    <h1 class="report-title">
      <i class="fas fa-file-contract fa-xl" style="color: var(--primary-color);"></i>
      تقرير الصيانات
    </h1>
  </div>

  <div class="filter-card">
    <form method="GET" class="filter-grid">
      <select name="technician_name" class="form-select">
        <option value="">اسم الفني...</option>
        <?php foreach ($technicians as $tech): ?>
          <option value="<?= $tech ?>" <?= ($_GET['technician_name'] ?? '') === $tech ? 'selected' : '' ?>><?= $tech ?></option>
        <?php endforeach; ?>
      </select>

      <select name="status" class="form-select">
        <option value="">حالة الصيانة...</option>
        <?php foreach ($statuses as $st): ?>
          <option value="<?= $st ?>" <?= ($_GET['status'] ?? '') === $st ? 'selected' : '' ?>><?= $st ?></option>
        <?php endforeach; ?>
      </select>

      <!--<input type="date" name="entry_date" class="form-control" value="<?= $_GET['entry_date'] ?? '' ?>" placeholder="تاريخ الدخول">-->

      <!--<input type="date" name="exit_date" class="form-control" value="<?= $_GET['exit_date'] ?? '' ?>" placeholder="تاريخ الخروج">-->

      <select name="region" class="form-select">
        <option value="">المنطقة...</option>
        <?php foreach ($regions as $r): ?>
          <option value="<?= $r ?>" <?= ($_GET['region'] ?? '') === $r ? 'selected' : '' ?>><?= $r ?></option>
        <?php endforeach; ?>
      </select>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary w-100">
          <i class="fas fa-filter"></i>
          <span>فلترة</span>
        </button>
        <a href="vehicles_report.php" class="btn btn-outline-secondary w-100">
          <i class="fas fa-sync-alt"></i>
          <span>إعادة تعيين</span>
        </a>
      </div>
    </form>
  </div>

  <div class="table-wrapper">
    <table id="maintenanceTable" class="table table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>اللوحة</th>
          <th>نوع العربة</th>
          <th>الموديل</th>
          <th>الفني</th>
          <th>نوع العطل</th>
          <th>الدخول</th>
          <th>الخروج</th>
          <th>الحالة</th>
          <th>المنطقة</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($records as $row): ?>
          <tr>
            <td><?= $row['id']; ?></td>
            <td><span class="badge bg-secondary"><?= $row['plate_number']; ?></span></td>
            <td><?= $row['vehicle_type']; ?></td>
            <td><?= $row['model']; ?></td>
            <td><?= $row['technician_name']; ?></td>
            <td><?= $row['fault_type']; ?></td>
            <td><?= $row['entry_date']; ?></td>
            <td><?= $row['exit_date']; ?></td>
            <td>
              <?php
                $statusClass = match($row['status']) {
                  'جديدة' => 'status-new',
                  'قيد الإصلاح' => 'status-inprogress',
                  'منتهية' => 'status-completed',
                  default => ''
                };
              ?>
              <span class="status-badge <?= $statusClass ?>">
                <?= $row['status']; ?>
              </span>
            </td>
            <td><?= $row['region']; ?></td>
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
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script>
$(document).ready(function() {
    $('#maintenanceTable').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json"
        },
        dom: 'Bfrtip',
        buttons: [
            // {
            //     extend: 'copy',
            //     exportOptions: { columns: ':visible' }
            // },
            {
                extend: 'excel',
                exportOptions: { columns: ':visible' }
            },
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
        ],
        columnDefs: [
            { targets: [], visible: true } // لتفعيل إخفاء أعمدة إذا أردت
        ]
    });
});
</script>
</body>
</html>
