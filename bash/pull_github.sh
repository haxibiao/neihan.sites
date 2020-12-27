#!/bin/bash

echo "更新github开源pull request ... "

cd /data/www/neihan.sites/packages/haxibiao/helpers && git pull github master
cd /data/www/neihan.sites/packages/haxibiao/matomo && git pull github master
cd /data/www/neihan.sites/packages/haxibiao/content && git pull github master
cd /data/www/neihan.sites/packages/haxibiao/media && git pull github master
cd /data/www/neihan.sites/packages/haxibiao/config && git pull github master
cd /data/www/neihan.sites/packages/haxibiao/cms && git pull github master
cd /data/www/neihan.sites/packages/haxibiao/dimension && git pull github master