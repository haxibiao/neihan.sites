#!/bin/bash


#有数据更新的时候，填写下面的 artisan commands 执行命令

# echo "修复数据(看来每次需要tag修数据时，都需要记得清理这里上一次的代码)..."
php artisan db:seed --class=VersionSeeder