#!/bin/bash

chmod -R 777 ./storage/
chmod -R 777 .env*
git config core.filemode false

echo "更新 env ..."
php artisan set:env --db_host=ngz014 --db_database=neihan_sites

echo "自动更新数据结构..."
php artisan migrate