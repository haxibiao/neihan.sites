#!/bin/bash
scope=seed

seeder="seed"

#有数据更新的时候，填写下面的 artisan commands 执行命令

if [ $scope = $seeder ];
then
echo "修复数据..."
php artisan migrate --seed
fi