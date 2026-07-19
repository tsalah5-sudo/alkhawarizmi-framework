#!/bin/bash

# تحديد اسم المجلد الافتراضي إذا لم يدخله المستخدم
PROJECT_NAME=${1:-"alkhawarizmi-app"}

echo "🚀 جاري جلب ملفات إطار عمل الخوارزمي v1.0.0..."

# استنساخ المستودع مباشرة باستخدام git clone لتجنب مشاكل الـ zip
if git clone --depth 1 https://github.com/tsalah5-sudo/alkhawarizmi-framework.git "$PROJECT_NAME"; then
    cd "$PROJECT_NAME" || exit
    
    # حذف مجلد الـ git الخاص بالإطار لكي يبدأ المطور بمستودع نظيف خاص به
    rm -rf .git
    
    # إعداد ملف البيئة إذا كان متوفراً
    if [ -f .env.example ]; then
        cp .env.example .env
        echo "📂 تم إعداد ملف الإعدادات البيئية .env"
    fi
    
    # إعطاء صلاحيات التنفيذ لأداة الخوارزمي الـ CLI
    if [ -f khwarizmi ]; then
        chmod +x khwarizmi
    fi
    
    echo "✨ تم التثبيت بنجاح!"
    echo "--------------------------------"
    echo "للبدء، اكتب الأوامر التالية:"
    echo "cd $PROJECT_NAME"
    echo "php khwarizmi serve"
    echo "--------------------------------"
else
    echo "❌ خطأ: فشل تحميل إطار العمل. تأكد من اتصالك بالإنترنت ومن الرابط."
    exit 1
fi