#!/bin/bash

branch="master"
db="ainicheng"
db_host="ngz009" #默认线上数据库

if [ ! -z $1 ];then
    branch=$1
    #hotfix和线上一样的数据库
    if [ "$branch" != "hotfix" ]; then
        db="ainicheng_staging" #其他环境的都用测试数据库吧...
        db_host="localhost" #非线上环境数据库
    fi
fi

echo "更新env ... ${branch} ${db} ${db_host}"
chmod -R 777 ./storage/
chmod -R 777 .env*
git config core.filemode false

git checkout $branch
git pull

composer install

#线上正式调试启用支付
if [ "$branch" = "hotfix" -o "$branch" = "master" ]; then
    php artisan set:env --db_host=${db_host} --db_database=${db} --pay
else
    php artisan set:env --db_host=${db_host} --db_database=${db}
fi

php artisan migrate


