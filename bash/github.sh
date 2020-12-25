#!/bin/bash

echo "发布到github开源 ... "

cd /data/www/neihan.sites/packages/haxibiao/store && git push github master
cd /data/www/neihan.sites/packages/haxibiao/helpers && git push github master
cd /data/www/neihan.sites/packages/haxibiao/task && git push github master
cd /data/www/neihan.sites/packages/haxibiao/matomo && git push github master
cd /data/www/neihan.sites/packages/haxibiao/content && git push github master
cd /data/www/neihan.sites/packages/haxibiao/media && git push github master
cd /data/www/neihan.sites/packages/haxibiao/base && git push github master
cd /data/www/neihan.sites/packages/haxibiao/config && git push github master
cd /data/www/neihan.sites/packages/haxibiao/live && git push github master
cd /data/www/neihan.sites/packages/haxibiao/question && git push github master
cd /data/www/neihan.sites/packages/haxibiao/tag && git push github master
cd /data/www/neihan.sites/packages/haxibiao/sns && git push github master
cd /data/www/neihan.sites/packages/haxibiao/wallet && git push github master
cd /data/www/neihan.sites/packages/haxibiao/cms && git push github master
cd /data/www/neihan.sites/packages/haxibiao/dimension && git push github master