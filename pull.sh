#!bin/bash


cd ../dongmeiwei.com
git stash -u
git pull
composer install
php artisan env:refresh --local

cd ../dongdianyao.com
git stash -u
git pull
composer install
php artisan env:refresh --local

cd ../dongmeiwei.com
git stash -u
git pull
composer install
php artisan env:refresh --local

cd ../qunyige.com
git stash -u
git pull
composer install
php artisan env:refresh --local

cd ../youjianqi.com
git stash -u
git pull
composer install
php artisan env:refresh --local

cd ../dianmoge.com
git stash -u
git pull
composer install
php artisan env:refresh --local