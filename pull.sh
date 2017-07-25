#!bin/bash


sudo chmod -R 777 .
git config core.filemode false
git pull
composer install

cd ../dongdianyao.com
sudo chmod -R 777 .
git config core.filemode false
git pull
composer install

cd ../dongmeiwei.com
sudo chmod -R 777 .
git config core.filemode false
git pull
composer install