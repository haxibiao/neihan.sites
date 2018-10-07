#!bin/bash
#主要修复站点的图片

echo '开始迁移本地资源到cos......'
cd ../../
echo '处理默认头像与老视频的封面图'
php artisan fix:data images10

echo '处理articles表中冗余的image_url'
php artisan fix:data images2

echo '开始修改本地数据路径......'
php artisan fix:data images3

echo '替换文章体中的图片路径......'
php artisan fix:data images4