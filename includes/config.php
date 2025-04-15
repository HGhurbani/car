<?php
// includes/config.php

$host     = "localhost";
$db       = "u637755375_car1";
$user     = "u637755375_car2";
$password = "$;kKUCm3Bq4";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "فشل الاتصال بقاعدة البيانات: " . $e->getMessage();
    exit();
}

// يمكنك استخدام هذا المتغير $conn في بقية الملفات لإجراء العمليات على قاعدة البيانات
?>
