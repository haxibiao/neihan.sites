#!/bin/bash

echo "修复git modules的777问题.. "

cd /data/www/neihan.sites/packages/haxibiao/store && git config core.filemode false
cd /data/www/neihan.sites/packages/haxibiao/helpers && git config core.filemode false
cd /data/www/neihan.sites/packages/haxibiao/task && git config core.filemode false
cd /data/www/neihan.sites/packages/haxibiao/matomo && git config core.filemode false
cd /data/www/neihan.sites/packages/haxibiao/content && git config core.filemode false
cd /data/www/neihan.sites/packages/haxibiao/media && git config core.filemode false
cd /data/www/neihan.sites/packages/haxibiao/base && git config core.filemode false
cd /data/www/neihan.sites/packages/haxibiao/config && git config core.filemode false
cd /data/www/neihan.sites/packages/haxibiao/live && git config core.filemode false
cd /data/www/neihan.sites/packages/haxibiao/question && git config core.filemode false
cd /data/www/neihan.sites/packages/haxibiao/tag && git config core.filemode false
cd /data/www/neihan.sites/packages/haxibiao/sns && git config core.filemode false
cd /data/www/neihan.sites/packages/haxibiao/wallet && git config core.filemode false