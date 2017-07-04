#!bin/bash

sudo /bin/cp -rf app/* ../dongdianyao.com/app
sudo /bin/cp -rf config/* ../dongdianyao.com/config
sudo /bin/cp -rf database/* ../dongdianyao.com/database
sudo /bin/cp -rf public/* ../dongdianyao.com/public
sudo /bin/cp -rf resources/* ../dongdianyao.com/resources
sudo /bin/cp -rf routes/* ../dongdianyao.com/routes

sudo /bin/cp -rf .gitattributes ../dongdianyao.com/
sudo /bin/cp -rf .gitignore ../dongdianyao.com/
sudo /bin/cp -rf Envoy.blade.php ../dongdianyao.com/
sudo /bin/cp -rf composer.* ../dongdianyao.com/
sudo /bin/cp -rf package.json ../dongdianyao.com/
sudo /bin/cp -rf webpack.mix.js ../dongdianyao.com/

cd ../dongdianyao.com/
git checkout config/envoydomain.php
git checkout database/seeds/*