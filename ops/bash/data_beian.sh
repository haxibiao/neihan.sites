#!/bin/bash

echo "修复备案站群数据..."
php artisan db:seed --class=SiteBeianSeeder

