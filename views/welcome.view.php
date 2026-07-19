<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f7fa; color: #2c3e50; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; text-align: center; }
        .welcome-box { background: white; padding: 50px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border-top: 6px solid #3498db; max-width: 600px; }
        h1 { color: #2c3e50; margin-top: 0; font-size: 2.5em; }
        p { color: #7f8c8d; font-size: 1.2em; line-height: 1.6; }
        .badge { background: ##70210f; color: white; padding: 6px 15px; border-radius: 20px; font-weight: bold; font-size: 0.9em; display: inline-block; margin-bottom: 20px; }
        .footer-note { font-size: 0.85em; color: #bdc3c7; margin-top: 30px; border-top: 1px solid #eee; padding-top: 15px; }
    </style>
</head>
<body>

<div class="welcome-box">
<img class="log" src="img/logo.png " width="300px">
    <div class="badge">أطار عمل الخوارزمي </div>
    <h1>أهلاً بك في بيئة تطوير الخوارزمي</h1>
    <p>لقد قمت بتشغيل النسخة الخام النظيفة بنجاح. النواة الذكية، نظام التوجيه، حماية الـ CSRF، والاتصال بقاعدة البيانات مستعدة بالكامل لبناء مشروعك القادم بلمح البصر وبأعلى درجات الأمان.</p>
    
    <div class="footer-note">
        ابدأ الآن بتعديل ملف <code>public/index.php</code> لتسجيل مسارات نظامك الجديد.
    </div>
</div>

</body>
</html>