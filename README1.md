# 1. تشغيل السيرفر المحلي
php khwarizmi serve

# 2. إنشاء ملف ترحيل جديد (يدعم الحماية التلقائية)
php khwarizmi make:migration create_products_table

# 3. تنفيذ الترحيلات الذكية (يدعم المصفوفات والكائنات والتنفيذ الداخلي)
php khwarizmi migrate

# 4. توليد لوحة CRUD كاملة بناءً على أعمدة الجدول الفعلي
php khwarizmi products