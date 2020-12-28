#!/bin/bash

branch="master"
db="neihan_sites"
db_host="ngz014" #默认线上数据库

echo "更新 env ... ${branch} ${db} ${db_host}"
chmod -R 777 ./storage/
chmod -R 777 .env*
git config core.filemode false

#线上正式调试启用支付
if [ "$branch" = "hotfix" -o "$branch" = "master" ]; then
    php artisan set:env --db_host=${db_host} --db_database=${db} --pay
else
    php artisan set:env --db_host=${db_host} --db_database=${db}
fi

# gz013上安装了opcache
lnmp php-fpm restart

echo "自动更新数据结构..."
php artisan migrate