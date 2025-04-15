<?php
session_start();
require_once 'includes/config.php';

$error = "";

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['username'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "اسم المستخدم أو كلمة المرور غير صحيحة";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>تسجيل الدخول</title>
  <link rel="stylesheet" href="assets/css/cairo-font.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      background: linear-gradient(135deg, #e0f2fe, #ffffff);
      height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-box {
      width: 400px;
      background: #fff;
      padding: 35px;
      border-radius: 14px;
      box-shadow: 0 0 25px rgba(0, 0, 0, 0.08);
      transition: 0.3s ease-in-out;
    }

    .login-box:hover {
      box-shadow: 0 0 35px rgba(0, 0, 0, 0.15);
    }

    .login-icon {
      font-size: 50px;
      color: #0d6efd;
      text-align: center;
      margin-bottom: 15px;
    }

    h4 {
      text-align: center;
      color: #0d6efd;
      margin-bottom: 25px;
      font-weight: bold;
      font-size: 22px;
    }

    label {
      font-weight: 500;
      margin-bottom: 6px;
      display: block;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 10px 14px;
      margin-bottom: 16px;
      border: 1px solid #ced4da;
      border-radius: 10px;
      font-size: 15px;
      background-color: #f9f9f9;
      transition: 0.3s;
    }

    input:focus {
      background-color: #fff;
      border-color: #0d6efd;
      outline: none;
      box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.1);
    }

    .login-btn {
      width: 100%;
      padding: 12px;
      font-size: 16px;
      background: linear-gradient(to right, #0d6efd, #3e9eff);
      color: white;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: 0.3s ease;
    }

    .login-btn:hover {
      background: linear-gradient(to left, #0b5ed7, #319bff);
      transform: scale(1.01);
    }

    .login-btn:focus {
      box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.2);
      outline: none;
    }

    .alert {
      background-color: #f8d7da;
      color: #842029;
      padding: 10px 15px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-size: 14px;
      text-align: center;
    }
  </style>
</head>
<body>
<div class="login-box">
  <div class="login-icon"><i class="fas fa-lock"></i></div>
  <h4>تسجيل الدخول</h4>

  <?php if ($error): ?>
    <div class="alert"><?= $error ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <label for="username">اسم المستخدم</label>
    <input type="text" name="username" id="username" required autofocus>

    <label for="password">كلمة المرور</label>
    <input type="password" name="password" id="password" required>

    <button type="submit" name="login" class="login-btn">
      <i class="fas fa-sign-in-alt"></i> دخول
    </button>
  </form>
</div>
</body>
</html>
