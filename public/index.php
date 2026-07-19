<?php

/**
 * 🌀 إطار عمل الخوارزمي - النسخة الخام الذكية
 * نقطة الانطلاق المركزية وتحميل الملفات تلقائياً
 */

// 1. تفعيل عرض الأخطاء أثناء التطوير
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. تسجيل آلية التحميل التلقائي للكلاسات (Autoloader)
spl_autoload_register(function ($className) {
    // تحويل الـ Namespace إلى مسار ملف حقيقي (مثل App\Controllers\HomeController -> App/Controllers/HomeController.php)
    $file = dirname(__DIR__) . '/' . str_replace('\\', '/', $className) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// 3. استدعاء الموجه المركزي
use Core\Router;

// ==========================================
// 🚀 سجل مسارات مشروعك الجديد هنا:
// ==========================================

// مسار افتراضي ترحيبي (يمكنك تعديله أو حذفه)
Router::get('/', 'MainController@welcome');

// --- Dynamic CRUD Routes for stu (Auto-Generated) ---
\Core\Router::get('/stu', 'StuController@index');
\Core\Router::get('/stu/create', 'StuController@create');
\Core\Router::post('/stu/store', 'StuController@store');
\Core\Router::get('/stu/edit', 'StuController@edit');
\Core\Router::post('/stu/update', 'StuController@update');
\Core\Router::post('/stu/delete', 'StuController@delete');

// --- Dynamic CRUD Routes for users (Auto-Generated) ---
\Core\Router::get('/users', 'UsersController@index');
\Core\Router::get('/users/create', 'UsersController@create');
\Core\Router::post('/users/store', 'UsersController@store');
\Core\Router::get('/users/edit', 'UsersController@edit');
\Core\Router::post('/users/update', 'UsersController@update');
\Core\Router::post('/users/delete', 'UsersController@delete');

\Core\Router::dispatch();
