#!bin/bash

echo "============ dongmeiwei.com ============"
cd ../dongmeiwei.com
git stash -u
git pull
php artisan env:refresh

echo "============ dongdianyao.com ============"
cd ../dongdianyao.com
git stash -u
git pull
php artisan env:refresh

echo "============ dongdianyi.com ============"
cd ../dongdianyi.com
git stash -u
git pull
php artisan env:refresh

echo "============ qunyige.com ============"
cd ../qunyige.com
git stash -u
git pull
php artisan env:refresh

echo "============ youjianqi.com ============"
cd ../youjianqi.com
git stash -u
git pull
php artisan env:refresh

echo "============ dianmoge.com ============"
cd ../dianmoge.com
git stash -u
git pull
php artisan env:refresh

echo "============ jucheshe.com ============"
cd ../jucheshe.com
git stash -u
git pull
php artisan env:refresh

echo "============ youwangfa.com ============"
cd ../youwangfa.com
git stash -u
git pull
php artisan env:refresh

echo "============ jinlinle.com ============"
cd ../jinlinle.com
git stash -u
git pull
php artisan env:refresh