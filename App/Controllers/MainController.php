<?php
namespace App\Controllers;

use Core\Controller;

class MainController extends Controller {

    // دالة الترحيب الافتراضية للنسخة الخام
    public function welcome() {
        $this->view('welcome', ['title' => 'الخوارزمي - جاهز للانطلاق']);
    }
}