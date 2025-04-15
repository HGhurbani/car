<?php require_once '../includes/header.php'; ?>
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../includes/config.php';

$stmt = $conn->prepare("SELECT * FROM vehicles ORDER BY id DESC");
$stmt->execute();
$vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>إدارة المركبات</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.rtl.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
  <link rel="stylesheet" href="../assets/css/cairo-font.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root {
      --primary-color: #3b5bdb;
      --secondary-color: #4263eb;
      --success-color: #37b24d;
      --background-color: #f8f9fa;
      --card-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
    }

    body {
      font-family: 'Cairo', sans-serif;
      background-color: var(--background-color);
    }

    .main-container {
      background: #ffffff;
      padding: 2rem;
      margin: 2rem auto;
      border-radius: 1rem;
      box-shadow: var(--card-shadow);
      border: 1px solid #e9ecef;
      max-width: 1400px;
    }

    .page-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
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

    .table-controls {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .form-select, .form-control {
      border: 1px solid #e9ecef;
      border-radius: 0.75rem;
      padding: 0.75rem 1rem;
      transition: all 0.3s ease;
    }

    .form-select:focus, .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(59, 91, 219, 0.15);
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

    .btn-success {
      background: var(--success-color);
      border: none;
    }

    .table-wrapper {
      border-radius: 0.75rem;
      overflow: hidden;
      box-shadow: var(--card-shadow);
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

    .table-hover tbody tr:hover td {
      background-color: #f8f9fa !important;
    }

    .action-buttons .btn {
      padding: 0.5rem 0.75rem;
      margin: 0 0.25rem;
      min-width: 80px;
    }

    .dataTables_wrapper .dataTables_filter input {
      border-radius: 0.75rem;
      border: 1px solid #ced4da;
      padding: 0.5rem 1rem;
    }

    @media (max-width: 768px) {
      .main-container {
        padding: 1rem;
        margin: 1rem;
      }
      
      .page-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
      }
      
      .table-controls {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>

<div class="main-container">
  <div class="page-header">
    <h1 class="page-title">
      <i class="fas fa-car-side fa-xl" style="color: var(--primary-color);"></i>
      إدارة المركبات
    </h1>
    <a href="add.php" class="btn btn-primary" style="color: white;">
      <i class="fas fa-plus-circle me-2" ></i>إضافة مركبة
    </a>
  </div>

  <div class="filter-card">
    <div class="table-controls">
      <select id="filterType" class="form-select">
        <option value="">كل الأنواع</option>
        <?php
        $types = array_unique(array_column($vehicles, 'vehicle_type'));
        foreach ($types as $type) {
            echo "<option value=\"$type\">$type</option>";
        }
        ?>
      </select>

      <select id="filterModel" class="form-select">
        <option value="">كل الموديلات</option>
        <?php
        $models = array_unique(array_column($vehicles, 'model'));
        foreach ($models as $model) {
            echo "<option value=\"$model\">$model</option>";
        }
        ?>
      </select>
    </div>
  </div>

  <div class="table-wrapper">
    <table id="vehiclesTable" class="table table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>نوع العربة</th>
          <th>الموديل</th>
          <th>رقم اللوحة</th>
          <th>رقم الهيكل</th>
          <th>رقم الأمن العام</th>
          <th>الجهة الفرعية</th>
          <th>الفئة</th>
          <th>الإجراءات</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($vehicles as $v): ?>
        <tr>
          <td><?= $v['id']; ?></td>
          <td><?= $v['vehicle_type']; ?></td>
          <td><?= $v['model']; ?></td>
          <td><span class="badge bg-secondary"><?= $v['plate_number']; ?></span></td>
          <td><?= $v['chassis_number']; ?></td>
          <td><?= $v['general_security_number']; ?></td>
          <td><?= $v['sub_entity']; ?></td>
          <td><span class="badge bg-primary"><?= $v['category']; ?></span></td>
          <td class="action-buttons">
            <a href="edit.php?id=<?= $v['id']; ?>" class="btn btn-sm btn-success">
              <i class="fas fa-edit"></i>
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

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
    var table = $('#vehiclesTable').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json"
        },
        dom: 'Bfrtip',
        buttons: [
            // { extend: 'copy', exportOptions: { columns: [0,1,2,3,4,5,6,7] } },
            { extend: 'excel', exportOptions: { columns: [0,1,2,3,4,5,6,7] } },
            // { extend: 'pdf', exportOptions: { columns: [0,1,2,3,4,5,6,7] } },
            { extend: 'print', exportOptions: { columns: [0,1,2,3,4,5,6,7] } }
        ]
    });

    $('#filterType, #filterModel').on('change', function () {
        var type = $('#filterType').val().toLowerCase();
        var model = $('#filterModel').val().toLowerCase();

        table.rows().every(function () {
            var rowData = this.data();
            var matchesType = !type || rowData[1].toLowerCase() === type;
            var matchesModel = !model || rowData[2].toLowerCase() === model;

            if (matchesType && matchesModel) {
                $(this.node()).show();
            } else {
                $(this.node()).hide();
            }
        });
    });
});
</script>
</body>
</html>