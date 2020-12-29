#!/bin/bash

echo "修复数据..."
php artisan db:seed --class=SiteSeeder

# php artisan fix:data movies
# php artisan movie:sync
