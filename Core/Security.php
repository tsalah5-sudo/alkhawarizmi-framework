<?php
namespace Core;

class Security {
    /**
     * بدء الجلسة آمنة وتوليد رمز حماية فريد وعشوائي
     */
    public static function generateCsrfToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            // توليد 32 بايت من الرموز العشوائية الصارمة وتحويلها لنص
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * التحقق من تطابق الرمز القادم من المتصفح مع المخزن في السيرفر
     */
    public static function checkCsrfToken($token) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}