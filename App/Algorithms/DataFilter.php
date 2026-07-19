<?php
namespace App\Algorithms;

class DataFilter {
    /**
     * خوارزمية الترتيب السريع (QuickSort) لمصفوفات البيانات بناءً على مفتاح محدد (مثلاً السعر أو التاريخ)
     */
    public static function sortByColumn(array $array, $column, $order = 'ASC') {
        usort($array, function ($a, $b) use ($column, $order) {
            if (!isset($a[$column]) || !isset($b[$column])) return 0;
            
            if ($a[$column] == $b[$column]) return 0;
            
            if ($order === 'ASC') {
                return ($a[$column] < $b[$column]) ? -1 : 1;
            } else {
                return ($a[$column] > $b[$column]) ? -1 : 1;
            }
        });
        
        return $array;
    }

    /**
     * خوارزمية البحث المتقدم (البحث النصي المرن) لتصفية المنتجات أو المقالات
     */
    public static function search($array, $searchTerm, $column) {
        return array_filter($array, function($item) use ($searchTerm, $column) {
            return stripos($item[$column], $searchTerm) !== false;
        });
    }
}