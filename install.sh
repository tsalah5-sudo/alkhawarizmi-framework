#!/bin/bash

# الألوان لتنسيق مخرجات التيرمنال
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m'

echo -e "${BLUE}🚀 جاري بدء تحميل إطار عمل الخوارزمي من GitHub...${NC}"

# 1. تحديد اسم مجلد المشروع الجديد (الافتراضي: khwarizmi-app)
PROJECT_NAME=${1:-"khwarizmi-app"}
USERNAME="YOUR_USERNAME" # ضع اسم حسابك في جيتهاب هنا
REPO="alkhwarizmi-framework"
VERSION="v1.0.0"

# 2. تحميل النسخة المستقرة
echo -e "${BLUE}📦 جاري جلب ملفات الإصدار ${VERSION}...${NC}"
curl -sL "https://github.com/${USERNAME}/${REPO}/archive/refs/tags/${VERSION}.zip" -o khwarizmi.zip

if [ $? -ne 0 ]; then
    echo -e "${RED}❌ خطأ: فشل تحميل الملف من GitHub. تأكد من اتصالك بالإنترنت.${NC}"
    exit 1
fi

# 3. فك الضغط ونقل الملفات للمجلد المطلوب
echo -e "${BLUE}📂 جاري إعداد مجلد المشروع [${PROJECT_NAME}]...${NC}"
unzip -q khwarizmi.zip
# جيتهاب يفك الضغط داخل مجلد باسم المستودع + رقم الإصدار، هنا نقوم بإعادة تسميته للمجلد المطلوب
mv "${REPO}-${VERSION//v/}" "$PROJECT_NAME"
rm khwarizmi.zip

# 4. الانتقال وتجهيز الصلاحيات وملف الإعدادات الافتراضي
cd "$PROJECT_NAME"
chmod +x khwarizmi

# إنشاء مجلد الكونفيج وملف افتراضي إن لم يكن موجوداً
mkdir -p config
if [ ! -f config/database.php ]; then
    echo "<?php return ['host'=>'localhost','dbname'=>'','username'=>'root','password'=>'']; ?>" > config/database.php
fi

echo -e "${GREEN}=========================================${NC}"
echo -e "${GREEN}🎉 تهانينا! تم تثبيت إطار الخوارزمي بنجاح!${NC}"
echo -e "${GREEN}=========================================${NC}"
echo -e "💡 ابدأ الآن بكتابة الأوامر التالية:"
echo -e "  ${BLUE}cd ${PROJECT_NAME}${NC}"
echo -e "  ${BLUE}php khwarizmi serve${NC}"