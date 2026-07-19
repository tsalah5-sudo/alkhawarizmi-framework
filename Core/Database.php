<?php
namespace Core;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $pdo;

    // منع إنشاء كائنات مباشرة عبر new من خارج الكلاس
    private function __construct() {
        // جلب الإعدادات من ملف الإعدادات
        $config = require __DIR__ . '/../config/database.php';

        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // تفعيل الأخطاء عبر الاستثناءات
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // جلب البيانات كمصفوفات مفتاحية
            PDO::ATTR_EMULATE_PREPARES   => false,                  // تعطيل المحاكاة للحماية الكبرى
        ];

        try {
            $this->pdo = new PDO($dsn, $config['username'], $config['password'], $options);
        } catch (PDOException $e) {
            die("خطأ في اتصال الخوارزمي بقاعدة البيانات: " . $e->getMessage());
        }
    }

    // خوارزمية جلب مثيلة الاتصال (تضمن اتصالاً واحداً فقط)
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // دالة لتنفيذ الاستعلامات بشكل آمن ومبسط
    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
	public function getConnection() {
    return $this->pdo;
}
}