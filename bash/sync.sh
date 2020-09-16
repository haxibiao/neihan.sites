#!/bin/bash

from="ainicheng.com"
tosite=$1
scope="gql" # 同步范畴,默认只同步gql相关
pathPrefix="/data/www/"

if [ ! -z $2 ]; then
	scope=$2
fi

function codesync() {

echo "===================================================== sync to $pathPrefix/$tosite ... "
cd $pathPrefix/$tosite/
sudo chmod -R 777 .
git config core.filemode false


echo 'sync 数据库迁移 ...'
rm -rf $pathPrefix/$tosite/database/migrations/*
/bin/cp -rf $pathPrefix/$from/database/migrations $pathPrefix/$tosite/database/

echo 'sync EnvRefresh ...'
/bin/cp -rf $pathPrefix/$from/app/Console/Commands/SetEnv.php $pathPrefix/$tosite/app/Console/Commands

echo 'sync config ...'
rm -rf $pathPrefix/$tosite/config
/bin/cp -rf $pathPrefix/$from/config $pathPrefix/$tosite

echo 'sync nova-components ...'
rm -rf $pathPrefix/$tosite/nova-components
/bin/cp -rf $pathPrefix/$from/nova-components $pathPrefix/$tosite

echo 'sync lighthouse graphql ...'
rm -rf $pathPrefix/$tosite/graphql
/bin/cp -rf $pathPrefix/$from/graphql $pathPrefix/$tosite

rm -rf $pathPrefix/$tosite/app/Http/GraphQL
/bin/cp -rf $pathPrefix/$from/app/Http/GraphQL $pathPrefix/$tosite/app/Http

echo "composer.json"
rm -rf $pathPrefix/$tosite/composer.*
/bin/cp -rf $pathPrefix/$from/composer.* $pathPrefix/$tosite/

echo 'sync gql相关php代码 ...'
echo ' - Model  ...'
rm -rf $pathPrefix/$tosite/app/*.php
/bin/cp -rf $pathPrefix/$from/app/*.php $pathPrefix/$tosite/app

echo ' - Traits  ...'
rm -rf $pathPrefix/$tosite/app/Traits
/bin/cp -rf $pathPrefix/$from/app/Traits $pathPrefix/$tosite/app

echo ' - Observers  ...'
rm -rf $pathPrefix/$tosite/app/Observers
/bin/cp -rf $pathPrefix/$from/app/Observers $pathPrefix/$tosite/app

echo ' - Nova  ...'
rm -rf $pathPrefix/$tosite/app/Nova
/bin/cp -rf $pathPrefix/$from/app/Nova $pathPrefix/$tosite/app

echo ' - Notifications  ...'
rm -rf $pathPrefix/$tosite/app/Notifications
/bin/cp -rf $pathPrefix/$from/app/Notifications $pathPrefix/$tosite/app

echo ' - Listeners  ...'
rm -rf $pathPrefix/$tosite/app/Listeners
/bin/cp -rf $pathPrefix/$from/app/Listeners $pathPrefix/$tosite/app

echo ' - Jobs  ...'
rm -rf $pathPrefix/$tosite/app/Jobs
/bin/cp -rf $pathPrefix/$from/app/Jobs $pathPrefix/$tosite/app

echo ' - Helpers  ...'
rm -rf $pathPrefix/$tosite/app/Helpers
/bin/cp -rf $pathPrefix/$from/app/Helpers $pathPrefix/$tosite/app

echo ' - Events  ...'
rm -rf $pathPrefix/$tosite/app/Events
/bin/cp -rf $pathPrefix/$from/app/Events $pathPrefix/$tosite/app

echo ' - Exceptions  ...'
rm -rf $pathPrefix/$tosite/app/Exceptions
/bin/cp -rf $pathPrefix/$from/app/Exceptions $pathPrefix/$tosite/app

echo ' - Test  ...'
rm -rf $pathPrefix/$tosite/tests
/bin/cp -rf $pathPrefix/$from/tests $pathPrefix/$tosite/

echo ' - Factories  ...'
rm -rf $pathPrefix/$tosite/database/factories
/bin/cp -rf $pathPrefix/$from/database/factories $pathPrefix/$tosite/database

echo ' - Factories  ...'
rm -rf $pathPrefix/$tosite/database/seeds
/bin/cp -rf $pathPrefix/$from/database/seeds $pathPrefix/$tosite/database

if [ $scope != "gql" ]; then

echo "sync web 相关"
rm -rf $pathPrefix/$tosite/config
rm -rf $pathPrefix/$tosite/routes
rm -rf $pathPrefix/$tosite/resources/views
/bin/cp -rf $pathPrefix/$from/routes $pathPrefix/$tosite/
/bin/cp -rf $pathPrefix/$from/config $pathPrefix/$tosite/
/bin/cp -rf $pathPrefix/$from/resources/views $pathPrefix/$tosite/resources/
git checkout resources/views/app.blade.php

echo "sync 运维脚本(ops)"
/bin/cp -rf $pathPrefix/$from/ops $pathPrefix/$tosite/
/bin/cp -rf $pathPrefix/$from/Envoy.blade.php $pathPrefix/$tosite/
git checkout ops/envoy/config.php

echo "sync 前端(源码) ..."
/bin/cp -rf $pathPrefix/$from/resources/assets/js $pathPrefix/$tosite/resources/assets/
/bin/cp -rf $pathPrefix/$from/resources/assets/sass $pathPrefix/$tosite/resources/assets/
git checkout resources/assets/sass/_theme.scss

echo "sync 前端js(compiled) ..."
/bin/cp -rf $pathPrefix/$from/public/js $pathPrefix/$tosite/public/

echo "sync php js 的包管理依赖"
/bin/cp -rf $pathPrefix/$from/composer.* $pathPrefix/$tosite/
/bin/cp -rf $pathPrefix/$from/package.* $pathPrefix/$tosite/
/bin/cp -rf $pathPrefix/$from/webpack.mix.js $pathPrefix/$tosite/

echo '.env.prod ...'
rm -rf $pathPrefix/$tosite/.env.prod
/bin/cp -rf $pathPrefix/$from/.env.prod $pathPrefix/$tosite/

fi

}

codesync $1


