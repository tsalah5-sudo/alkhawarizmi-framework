<?php
namespace Core;

class Router {
    private static $routes = [];

    public static function get($route, $controllerAction) {
        self::$routes['GET'][$route] = $controllerAction;
    }

    public static function post($route, $controllerAction) {
        self::$routes['POST'][$route] = $controllerAction;
    }

  public static function dispatch() {
	  //echo "<pre>📊 Registered Routes:\n"; print_r(self::$routes ?? 'No routes array found'); echo "</pre>"; exit;
        $method = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // 🔥 خوارزمية ذكية لاكتشاف مسار المجلد الحالي وتنظيف الرابط تلقائياً
        $scriptName = dirname($_SERVER['SCRIPT_NAME']); // يعطينا مسار مجلد public
        $projectPath = dirname($scriptName); // يعطينا المجلد الرئيسي للمشروع مهما كان اسمه
        
        // تنظيف الرابط ديناميكياً من مسار المشروع ومجلد public
        $url = str_replace($scriptName, '', $requestUri);
        $url = str_replace($projectPath, '', $url);
        
        // توحيد شكل الرابط بنظام الشرطة المائلة
        $url = '/' . trim($url, '/');
        if ($url === '//' || $url === '') {
            $url = '/';
        }

        // البحث عن تطابق في المسارات المسجلة
        if (isset(self::$routes[$method][$url])) {
            $callback = self::$routes[$method][$url];
            
            if (strpos($callback, '@') !== false) {
                list($controller, $action) = explode('@', $callback);
            } else {
                die("❌ خطأ في النظام: المسار المسجل لـ [$url] مكتوب بنمط خاطئ. تأكد من استخدام علامة '@'");
            }
            
            $controllerClass = "App\Controllers\\" . $controller;
            
            if (class_exists($controllerClass)) {
                $controllerInstance = new $controllerClass();
                if (method_exists($controllerInstance, $action)) {
                    $controllerInstance->$action();
                    return;
                }
                die("❌ خطأ: الدالة [$action] غير موجودة في الكلاس [$controllerClass]");
            }
            die("❌ خطأ: لم يتم العثور على ملف المتحكم [$controllerClass].");
        }

        self::abort(404);
    }
    public static function abort($code = 404) {
        http_response_code($code);
        echo "<h1>خطأ $code: الصفحة غير موجودة في نظام الخوارزمي!</h1>";
        exit;
    }
}