#!/bin/bash

echo "修复数据..."
php artisan migrate --seed

# php artisan fix:data movies
# php artisan movie:sync
