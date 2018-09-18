#!bin/bash

echo "===================================================== sync to $1 ... "
cd ../$1.com/
sudo chmod -R 777 .
git config core.filemode false

echo 'sync laravel code ...'
sudo /bin/cp -rf /data/www/ainicheng.com/app /data/www/$1.com/
sudo /bin/cp -rf /data/www/ainicheng.com/config /data/www/$1.com/
sudo /bin/cp -rf /data/www/ainicheng.com/resources/views /data/www/$1.com/resources/
git checkout resources/views/app.blade.php
sudo /bin/cp -rf /data/www/ainicheng.com/resources/assets/js /data/www/$1.com/resources/assets/
sudo /bin/cp -rf /data/www/ainicheng.com/routes /data/www/$1.com/

echo "sync 运维脚本(ops)"
sudo /bin/cp -rf /data/www/ainicheng.com/ops/commands/FixData.php /data/www/$1.com/ops/commands/
sudo /bin/cp -rf /data/www/ainicheng.com/ops/envoy/commands.php /data/www/$1.com/ops/envoy/
sudo /bin/cp -rf /data/www/ainicheng.com/ops/envoy/tasks.php /data/www/$1.com/ops/envoy/
sudo /bin/cp -rf /data/www/ainicheng.com/Envoy.blade.php /data/www/$1.com/

echo "sync 前端scss js(vue) ..."
sudo /bin/cp -rf /data/www/ainicheng.com/resources/assets/js /data/www/$1.com/resources/assets/
sudo /bin/cp -rf /data/www/ainicheng.com/resources/assets/sass /data/www/$1.com/resources/assets/
git checkout resources/assets/sass/_theme.scss

echo 'sync 数据库迁移 ...'
rm -rf /data/www/$1.com/database/migrations/*
sudo /bin/cp -rf /data/www/ainicheng.com/database/migrations /data/www/$1.com/database/

echo "sync php js 的包管理依赖"
sudo /bin/cp -rf /data/www/ainicheng.com/composer.* /data/www/$1.com/
sudo /bin/cp -rf /data/www/ainicheng.com/package.* /data/www/$1.com/
sudo /bin/cp -rf /data/www/ainicheng.com/webpack.mix.js /data/www/$1.com/

# echo 'sync assets ...'
# sudo /bin/cp -rf /data/www/ainicheng.com/public/assets /data/www/$1.com/public

# echo 'sync fonts ...'
# sudo /bin/cp -rf /data/www/ainicheng.com/public/fonts /data/www/$1.com/public

# echo 'sync sdk ...'
# sudo /bin/cp -rf /data/www/ainicheng.com/public/sdk /data/www/$1.com/public

echo 'sync some images ...'
sudo /bin/cp -rf /data/www/ainicheng.com/public/qrcode /data/www/$1.com/public/
sudo /bin/cp -rf /data/www/ainicheng.com/public/logo /data/www/$1.com/public/
sudo /bin/cp -rf /data/www/ainicheng.com/public/images/app /data/www/$1.com/public/images/
