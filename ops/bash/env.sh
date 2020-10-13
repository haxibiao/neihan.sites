#!/bin/bash

branch="master"
db="yanjiao"
db_host="gz03" #默认线上数据库

if [ ! -z $1 ];then
    branch=$1
    #hotfix和线上一样的数据库
    if [ "$branch" !="develop" ]; then
        db="yanjiao_staging" #其他环境的都用测试数据库吧...
        db_host="localhost" #非线上环境数据库
    fi
fi

echo "更新env ... ${branch} ${db} ${db_host}"
chmod -R 777 ./storage/
chmod -R 777 .env*
git config core.filemode false

git checkout $branch
git pull

echo "更新子模块git代码..."
git submodule init && git submodule update && git pull --recurse-submodules

composer install --ignore-platform-reqs

#线上正式调试启用支付
if [ "$branch" = "hotfix" -o "$branch" = "master" ]; then
    php artisan set:env --db_host=${db_host} --db_database=${db} --pay
else
    php artisan set:env --db_host=${db_host} --db_database=${db}
fi

php artisan migrate


