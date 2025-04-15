<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>نظام الصيانة</title>

  <!-- Bootstrap RTL -->
  <link rel="stylesheet" href="../assets/css/bootstrap.rtl.min.css">
  
  <!-- خطوط وأيقونات -->
  <link rel="stylesheet" href="../assets/css/cairo-font.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

  <!-- ملف تنسيق مخصص -->
  <link rel="stylesheet" href="../assets/css/style.css">

  <style>
    .sidebar { 
      position: fixed; right: 0; top: 0; width: 240px; height: 100vh;
      background: #343a40; color: #fff; padding: 20px; 
      overflow-y: auto; 
    }
    .main-content {
      margin-right: 240px;
      padding: 20px;
    }
    .sidebar .brand {
      font-size: 20px;
      font-weight: 600;
      margin-bottom: 30px;
    }
    .sidebar a {
      display: block;
      color: #fff;
      text-decoration: none;
      margin-bottom: 15px;
      transition: 0.3s;
    }
    .sidebar a:hover {
      background-color: #495057;
      border-radius: 4px;
      padding: 8px;
    }
    .sidebar .user-info {
      font-size: 15px;
      margin-bottom: 15px;
      background: rgba(255,255,255,0.1);
      padding: 10px;
      border-radius: 4px;
    }
  </style>
</head>
<body>

<!-- القائمة الجانبية الثابتة -->
<div class="sidebar">
  <div class="brand">نظام الصيانة</div>

  <div class="user-info">
    <i class="fa-solid fa-user"></i>
    <?php echo $_SESSION['user']; ?>
  </div>

  <!-- روابط التنقل -->
  <a href="/car_workshop//dashboard.php"><i class="fa-solid fa-home"></i> الرئيسية</a>
  <a href="/car_workshop/vehicles/index.php"><i class="fa-solid fa-car"></i> إدارة المركبات</a>
  <a href="/car_workshop/maintenance/index.php"><i class="fa-solid fa-wrench"></i> إدارة الصيانات</a>
  <a href="/car_workshop/reports/vehicles_report.php"><i class="fa-solid fa-file-lines"></i> التقارير</a>
  <hr style="border-color: rgba(255,255,255,0.3);">
  <a href="/car_workshop/logout.php"><i class="fa-solid fa-sign-out-alt"></i> تسجيل الخروج</a>
</div>

<!-- بداية قسم المحتوى -->
<div class="main-content">
<!-- يمكنك أن تُغلقه في footer.php -->
