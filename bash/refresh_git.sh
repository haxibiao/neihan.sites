#!/bin/bash

echo "更新子模块并 修复 git modules 的 filemode 问题 ... "

cd /data/www/neihan.sites/packages/haxibiao/store && git config core.filemode false && git pull origin master
cd /data/www/neihan.sites/packages/haxibiao/helpers && git config core.filemode false && git pull origin master
cd /data/www/neihan.sites/packages/haxibiao/task && git config core.filemode false && git pull origin master
cd /data/www/neihan.sites/packages/haxibiao/matomo && git config core.filemode false && git pull origin master
cd /data/www/neihan.sites/packages/haxibiao/content && git config core.filemode false && git pull origin master
cd /data/www/neihan.sites/packages/haxibiao/media && git config core.filemode false && git pull origin master
cd /data/www/neihan.sites/packages/haxibiao/breeze && git config core.filemode false && git pull origin master
cd /data/www/neihan.sites/packages/haxibiao/config && git config core.filemode false && git pull origin master
cd /data/www/neihan.sites/packages/haxibiao/live && git config core.filemode false && git pull origin master
cd /data/www/neihan.sites/packages/haxibiao/question && git config core.filemode false && git pull origin master
cd /data/www/neihan.sites/packages/haxibiao/sns && git config core.filemode false && git pull origin master
cd /data/www/neihan.sites/packages/haxibiao/wallet && git config core.filemode false && git pull origin master
cd /data/www/neihan.sites/packages/haxibiao/cms && git config core.filemode false && git pull origin master
cd /data/www/neihan.sites/packages/haxibiao/dimension && git config core.filemode false && git pull origin master
