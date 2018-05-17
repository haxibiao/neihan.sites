#!bin/bash


cd ../dongmeiwei.com
git checkout .
git pull
composer install
php artisan env:refresh --local

cd ../dongdianyao.com
git checkout .
git pull
composer install
php artisan env:refresh --local

cd ../dongmeiwei.com
git checkout .
git pull
composer install
php artisan env:refresh --local

cd ../qunyige.com
git checkout .
git pull
composer install
php artisan env:refresh --local

cd ../youjianqi.com
git checkout .
git pull
composer install
php artisan env:refresh --local

cd ../dianmoge.com
git checkout .
git pull
composer install
php artisan env:refresh --local