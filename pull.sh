#!bin/bash


cd ../dongmeiwei.com
git pull
composer install
php artisan env:refresh --local

cd ../dongdianyao.com
git pull
composer install
php artisan env:refresh --local

cd ../dongmeiwei.com
git pull
composer install
php artisan env:refresh --local

cd ../qunyige.com
git pull
composer install
php artisan env:refresh --local

cd ../youjianqi.com
git pull
composer install
php artisan env:refresh --local

cd ../dianmoge.com
git pull
composer install
php artisan env:refresh --local