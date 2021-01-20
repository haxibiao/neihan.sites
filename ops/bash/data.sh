#!/bin/bash

echo "修复数据..."

echo " - 同步爱你城的合集视频动态"
php artisan video:sync --source=爱你城 --collectable

echo " - 同步印象视频 老张的视频动态"
php artisan video:sync --source=印象视频 --author=老张

echo " - 同步娜视频全部的视频动态"
php artisan video:sync --source=娜视频



