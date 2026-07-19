#!/usr/bin/env php
<?php
/**
 * Al-Khwarizmi Framework CLI Engine
 * File location: alkhwarizmi-framework/khwarizmi.php
 */

if (php_sapi_name() !== 'cli') {
    die("This script can only be run from the command line!");
}

// 1. Capture inputs
$command = $argv[1] ?? 'help';
$argument = $argv[2] ?? null;

// 2. Command Switcher
switch ($command) {
    case 'help':
        showHelp();
        break;

    case 'make:controller':
        if (!$argument) {
            echo "❌ Error: Controller name is required. Example: khwarizmi make:controller ProductController\n";
            exit(1);
        }
        createController($argument);
        break;

    case 'make:model':
        if (!$argument) {
            echo "❌ Error: Model name is required. Example: khwarizmi make:model Product\n";
            exit(1);
        }
        createModel($argument);
        break;

    case 'make:migration':
        if (!$argument) {
            echo "❌ Error: Migration name is required. Example: khwarizmi make:migration create_users_table\n";
            exit(1);
        }
        createMigration($argument);
        break;

    case 'migrate':
        runMigrations();
        break;

    case 'serve':
        $port = $argument ?? '8000';
        echo "🚀 Starting Al-Khwarizmi development server on: http://localhost:$port\n";
        echo "Press Ctrl+C to stop the server...\n";
        passthru("php -S localhost:$port -t public");
        break;

    default:
        echo "❌ Error: Command [ $command ] is not recognized.\n";
        echo "Type: khwarizmi help to see available commands.\n";
        break;
}

// ==========================================================
// CLI Functions
// ==========================================================

function showHelp() {
    echo "==================================================\n";
    echo "🏛️  Al-Khwarizmi Framework CLI Console \n";
    echo "==================================================\n";
    echo "Usage: khwarizmi [command] [arguments]\n\n";
    echo "Available Commands:\n";
    echo "  serve [port]            Start the local development server (Default: 8000)\n";
    echo "  make:controller [name]  Create a new controller file in app/Controllers/\n";
    echo "  make:model [name]       Create a new model file in app/Models/\n";
    echo "  make:migration [name]   Create a new migration file in database/migrations/\n";
    echo "  migrate                 Run all outstanding migrations\n";
    echo "  help                    Display this help screen\n";
    echo "==================================================\n";
}

function createController($name) {
    $filePath = __DIR__ . "/app/Controllers/{$name}.php";
    if (file_exists($filePath)) { echo "⚠️  Warning: Controller [ $name ] already exists.\n"; return; }
    $template = "<?php\nclass {$name} extends Controller {\n    public function index() {\n        // Code\n    }\n}\n";
    if (@file_put_contents($filePath, $template)) echo "✅ Controller created: app/Controllers/{$name}.php\n";
}

function createModel($name) {
    $filePath = __DIR__ . "/app/Models/{$name}.php";
    if (file_exists($filePath)) { echo "⚠️  Warning: Model [ $name ] already exists.\n"; return; }
    $template = "<?php\nclass {$name} {\n    private \$db;\n    public function __construct() {\n        \$this->db = Database::getInstance();\n    }\n}\n";
    if (@file_put_contents($filePath, $template)) echo "✅ Model created: app/Models/{$name}.php\n";
}

/**
 * 📦 إنشاء ملف مايجريشن جديد طابع زمني (Timestamp)
 */
function createMigration($name) {
    $dirPath = __DIR__ . "/database/migrations";
    
    // إنشاء المجلد إذا لم يكن موجوداً
    if (!is_dir($dirPath)) {
        mkdir($dirPath, 0777, true);
    }

    $timestamp = date('Y_m_d_His');
    $fileName = "m_{$timestamp}_{$name}.php";
    $filePath = $dirPath . '/' . $fileName;

    $template = "<?php\n" .
                "/**\n" .
                " * Migration generated via Al-Khwarizmi CLI\n" .
                " */\n\n" .
                "return new class {\n" .
                "    public function up(\$db) {\n" .
                "        // Write your SQL query here to create tables\n" .
                "        \$sql = \"CREATE TABLE IF NOT EXISTS {$name} (\n" .
                "            id INT AUTO_INCREMENT PRIMARY KEY,\n" .
                "            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP\n" .
                "        ) ENGINE=InnoDB;\";\n\n" .
                "        \$db->exec(\$sql);\n" .
                "    }\n\n" .
                "    public function down(\$db) {\n" .
                "        // Write your SQL query here to drop tables\n" .
                "        \$db->exec(\"DROP TABLE IF EXISTS {$name};\");\n" .
                "    }\n" .
                "};\n";

    if (@file_put_contents($filePath, $template)) {
        echo "✅ Migration created: database/migrations/{$fileName}\n";
    } else {
        echo "❌ Error: Failed to create migration file.\n";
    }
}

/**
 * 🚀 تنفيذ ملفات المايجريشن في قاعدة البيانات
 */
function runMigrations() {
    // 1. تضمين ملف الاتصال بقاعدة البيانات الخاص بفريمورك الخوارزمي
    // ملاحظة: قم بتعديل هذا المسار ليطابق ملف الإعدادات/الاتصال لديك
    require_once __DIR__ . '/app/Core/Database.php'; 
    
    try {
        $db = Database::getInstance(); // أو حسب طريقة جلب كائن الـ PDO لديك
    } catch (Exception $e) {
        echo "❌ Database Connection Error: " . $e->getMessage() . "\n";
        exit(1);
    }

    // 2. إنشاء جدول داخلي لتسجيل المايجريشنز المنفذة مسبقاً لمنع التكرار
    $db->exec("CREATE TABLE IF NOT EXISTS khwarizmi_migrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        migration VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;");

    // 3. جلب المايجريشنز المنفذة سابقاً
    $stmt = $db->query("SELECT migration FROM khwarizmi_migrations");
    $executedMigrations = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // 4. قراءة الملفات من مجلد migrations
    $dirPath = __DIR__ . "/database/migrations";
    if (!is_dir($dirPath) || !$files = scandir($dirPath)) {
        echo "⚠️  No migrations folder or files found.\n";
        return;
    }

    $applied = 0;
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;

        // إذا لم يكن الملف قد نُفّذ من قبل
        if (!in_array($file, $executedMigrations)) {
            echo "📐 Migrating: $file\n";
            
            // استدعاء ملف المايجريشن وتشغيل دالة up
            $migrationInstance = require_once $dirPath . '/' . $file;
            $migrationInstance->up($db);

            // تسجيل الملف في الجدول كـ "منفذ"
            $stmtInsert = $db->prepare("INSERT INTO khwarizmi_migrations (migration) VALUES (:migration)");
            $stmtInsert->execute([':migration' => $file]);
            
            echo "✅ Migrated:  $file\n";
            $applied++;
        }
    }

    if ($applied === 0) {
        echo "Nothing to migrate. Database is up to date!\n";
    } else {
        echo "🏁 Success: $applied migration(s) applied.\n";
    }
}