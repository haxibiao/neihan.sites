#!bin/bash

echo "sync to $1..."
cd ../$1.com/
sudo chmod -R 777 .
git config core.filemode false

echo "sass code except theme ..."
sudo /bin/cp -rf /data/www/ainicheng.com/resources/assets/sass /data/www/$1.com/resources/assets/
git checkout resources/assets/sass/_theme.scss

echo 'webpack.mix.js ...'
sudo /bin/cp -rf /data/www/ainicheng.com/webpack.mix.js /data/www/$1.com/

sudo /bin/cp -rf /data/www/ainicheng.com/app /data/www/$1.com/
rm -rf /data/www/$1.com/database/migrations/*
sudo /bin/cp -rf /data/www/ainicheng.com/database/migrations /data/www/$1.com/database/
sudo /bin/cp -rf /data/www/ainicheng.com/resources/views /data/www/$1.com/resources/
sudo /bin/cp -rf /data/www/ainicheng.com/resources/assets/js /data/www/$1.com/resources/assets/
sudo /bin/cp -rf /data/www/ainicheng.com/routes /data/www/$1.com/
sudo /bin/cp -rf /data/www/ainicheng.com/composer.* /data/www/$1.com/
