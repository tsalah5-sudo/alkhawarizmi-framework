<?php
namespace Core;

class Controller {
    /**
     * دالة لعرض ملف الواجهة وتمرير البيانات إليه
     * @param string $viewName اسم ملف الواجهة (مثال: home)
     * @param array $data المصفوفة التي تحتوي على البيانات المرسلة للمصمم
     */
    protected function view($viewName, $data = []) {
        $file = __DIR__ . '/../views/' . $viewName . '.view.php';
        
        if (file_exists($file)) {
            // خوارزمية استخراج البيانات: تحول مفاتيح المصفوفة إلى متغيرات عادية
            // مثلاً: ['title' => 'الرئيسية'] تصبح $title = 'الرئيسية'
            extract($data);
            
            // تضمين ملف التصميم ليقرأ المتغيرات المستخرجة
            require_once $file;
        } else {
            die("خطأ في الخوارزمي: ملف الواجهة [ $viewName ] غير موجود!");
        }
    }
}