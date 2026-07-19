<?php
namespace Core;

use Core\Database;

class Model {
    protected static $table; // اسم الجدول في قاعدة البيانات

    /**
     * خوارزمية جلب جميع السجلات من الجدول تلقائياً
     */
    public static function all() {
        $db = Database::getInstance();
        // جلب اسم الجدول من الكلاس الابن الذي استدعى الدالة
        $table = static::$table; 
        
        return $db->query("SELECT * FROM {$table}")->fetchAll();
    }

    /**
     * خوارزمية البحث عن سجل معين بواسطة المعرف (ID)
     */
    public static function find($id) {
        $db = Database::getInstance();
        $table = static::$table;

        return $db->query("SELECT * FROM {$table} WHERE id = ?", [$id])->fetch();
    }
	/**
     * خوارزمية إدخال بيانات جديدة تلقائياً في الجدول
     */
    public static function create($data) {
        $db = Database::getInstance();
        $table = static::$table;

        // استخراج أسماء الأعمدة وصناعة علامات الاستفهام (?) لمنع ثغرات SQL Injection
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $values = array_values($data);

        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        
        return $db->query($sql, $values);
    }
	/**
     * خوارزمية حذف سجل من الجدول بواسطة المعرف (ID)
     */
    public static function delete($id) {
        $db = Database::getInstance();
        $table = static::$table;

        $sql = "DELETE FROM {$table} WHERE id = ?";
        return $db->query($sql, [$id]);
    }
	/**
     * خوارزمية تحديث سجل في الجدول بواسطة المعرف (ID)
     */
    public static function update($id, $data) {
        $db = Database::getInstance();
        $table = static::$table;

        // بناء استعلام التحديث آلياً (مثال: name = ?, price = ?)
        $fields = [];
        foreach (array_keys($data) as $column) {
            $fields[] = "{$column} = ?";
        }
        $fieldsStr = implode(', ', $fields);

        // تجميع القيم الممررة مع وضع الـ ID في النهاية للاستعلام
        $values = array_values($data);
        $values[] = $id;

        $sql = "UPDATE {$table} SET {$fieldsStr} WHERE id = ?";
        return $db->query($sql, $values);
    }
}