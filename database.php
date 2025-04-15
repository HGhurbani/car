<?php
// database.php
require_once 'config.php';

class Database {
    private static $instance = null;
    private $pdo;

    // دالة البنية الخاصة - منع إنشاء أكثر من نسخة واحدة
    private function __construct(){
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS);
            // إعداد خصائص PDO للتعامل مع الأخطاء والنتائج
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e){
            die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
        }
    }

    // الدالة المسؤولة عن إعادة نسخة الاتصال
    public static function getInstance(){
        if (self::$instance == null){
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(){
        return $this->pdo;
    }
}
