<?php
namespace App\Algorithms;

class Pagination {
    /**
     * حساب بيانات التقسيم لأي مصفوفة أو استعلام
     * $totalItems: العدد الإجمالي للمواد
     * $perPage: عدد المواد المراد عرضها في الصفحة الواحدة
     * $currentPage: الصفحة الحالية
     */
    public static function calculate($totalItems, $perPage = 10, $currentPage = 1) {
        $totalPages = ceil($totalItems / $perPage);
        
        // التأكد من أن الصفحة الحالية ضمن النطاق الصحيح
        $currentPage = max(1, min($totalPages, (int)$currentPage));
        
        // حساب نقطة الانطلاق (Offset) لقاعدة البيانات
        $offset = ($currentPage - 1) * $perPage;

        return [
            'current_page' => $currentPage,
            'total_pages'  => $totalPages,
            'per_page'     => $perPage,
            'offset'       => $offset,
            'has_next'     => $currentPage < $totalPages,
            'has_prev'     => $currentPage > 1
        ];
    }
}