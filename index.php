<?php
// dashboard.php

// استدعاء الهيدر الموحّد (يفترض أنه يستدعي session_start() + يتحقق من تسجيل الدخول)
require_once 'includes/header.php';

// استدعاء ملف الاتصال بقاعدة البيانات (إن لم يكن مستدعى مسبقًا في header)
require_once 'includes/config.php';

/**
 * جلب البيانات من الجداول لحساب الإحصائيات
 */

// 1) عدد المركبات
$stmt = $conn->query("SELECT COUNT(*) FROM vehicles");
$totalVehicles = $stmt->fetchColumn();  // يعطينا رقم فقط

// 2) إجمالي الصيانات
$stmt = $conn->query("SELECT COUNT(*) FROM maintenance");
$totalMaintenance = $stmt->fetchColumn();

// 3) الصيانات الجارية (مثال: الحالة = 'جارية')
$stmt = $conn->query("SELECT COUNT(*) FROM maintenance WHERE status = 'جارية'");
$ongoingMaintenance = $stmt->fetchColumn();

// 4) الصيانات المنتهية (مثال: الحالة = 'منتهية')
$stmt = $conn->query("SELECT COUNT(*) FROM maintenance WHERE status = 'منتهية'");
$finishedMaintenance = $stmt->fetchColumn();

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>لوحة التحكم - نظام الصيانة</title>
  
  <!-- Bootstrap RTL -->
  <link rel="stylesheet" href="assets/css/bootstrap.rtl.min.css">
  
  <!-- خط Cairo -->
  <link rel="stylesheet" href="assets/css/cairo-font.css">
  
  <!-- أيقونات Font Awesome (مثال) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" 
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
  
  <!-- ملف التنسيق الخاص بك -->
  <link rel="stylesheet" href="assets/css/style.css">

  <style>
  body {
    font-family: 'Cairo', sans-serif;
    background-color: #f8f9fa;
  }

  .dashboard-container {
    margin-top: 30px;
    padding-right: 40px;
  }

  .stats-card {
    border-radius: 12px;
    padding: 25px;
    color: #fff;
    margin-bottom: 25px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
    overflow: hidden;
  }

  .stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
  }

  .stats-card i {
    font-size: 30px;
    opacity: 0.3;
    position: absolute;
    top: 10px;
    left: 15px;
  }

  .stats-card h5 {
    font-size: 17px;
    font-weight: 600;
    margin-bottom: 15px;
  }

  .stats-card .count {
    font-size: 30px;
    font-weight: bold;
  }

  .bg-primary-2 {
    background: linear-gradient(135deg, #007bff, #0056b3);
  }

  .bg-success-2 {
    background: linear-gradient(135deg, #28a745, #1c7c2c);
  }

  .bg-warning-2 {
    background: linear-gradient(135deg, #ffc107, #e0a800);
    color: #000;
  }

  .bg-info-2 {
    background: linear-gradient(135deg, #17a2b8, #117a8b);
  }

  .btn {
    font-size: 16px;
    font-weight: 500;
    padding: 10px 0;
  }

  .btn i {
    margin-left: 5px;
  }
  </style>

</head>
<body>

<!-- حاوية المحتوى الرئيسي -->
<div class="container dashboard-container">
  <h3 class="mb-4">لوحة التحكم الرئيسية</h3>
  
  <!-- التقارير المختصرة (Cards) -->
  <div class="row">
    <!-- الصف الأول -->
    <div class="col-md-6 mb-4">
      <div class="stats-card bg-primary-2">
        <h5><i class="fa-solid fa-car"></i> إجمالي المركبات</h5>
        <div class="count"><?php echo $totalVehicles; ?></div>
      </div>
    </div>

    <div class="col-md-6 mb-4">
      <div class="stats-card bg-info-2">
        <h5><i class="fa-solid fa-toolbox"></i> إجمالي الصيانات</h5>
        <div class="count"><?php echo $totalMaintenance; ?></div>
      </div>
    </div>

    <!-- الصف الثاني -->
    <div class="col-md-6 mb-4">
      <div class="stats-card bg-warning-2">
        <h5><i class="fa-solid fa-spinner"></i> الصيانات الجارية</h5>
        <div class="count"><?php echo $ongoingMaintenance; ?></div>
      </div>
    </div>

    <div class="col-md-6 mb-4">
      <div class="stats-card bg-success-2">
        <h5><i class="fa-solid fa-check"></i> الصيانات المنتهية</h5>
        <div class="count"><?php echo $finishedMaintenance; ?></div>
      </div>
    </div>
  </div>

</div>

<!-- ملف JS لـBootstrap -->
<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
