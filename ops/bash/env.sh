#!/bin/bash

branch="master"
db="neihan_sites"
db_host="localhost" #默认线上数据库


echo "更新env ... ${branch} ${db} ${db_host}"
chmod -R 777 ./storage/
chmod -R 777 .env*
git config core.filemode false

git checkout $branch
git pull

echo "更新子模块git代码..."
git submodule init && git submodule update && git pull --recurse-submodules

composer install

#线上正式调试启用支付
if [ "$branch" = "hotfix" -o "$branch" = "master" ]; then
    php artisan set:env --db_host=${db_host} --db_database=${db} --pay
else
    php artisan set:env --db_host=${db_host} --db_database=${db}
fi

php artisan migrate


