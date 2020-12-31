#!/bin/bash

echo "设置 github 代码仓库... "

cd /data/www/neihan.sites/packages/haxibiao/helpers && git remote add github https://github.com/haxibiao/haxibiao-helpers
cd /data/www/neihan.sites/packages/haxibiao/matomo && git remote add github https://github.com/haxibiao/haxibiao-matomo
cd /data/www/neihan.sites/packages/haxibiao/content && git remote add github https://github.com/haxibiao/haxibiao-content
cd /data/www/neihan.sites/packages/haxibiao/media && git remote add github https://github.com/haxibiao/haxibiao-media
cd /data/www/neihan.sites/packages/haxibiao/config && git remote add github https://github.com/haxibiao/haxibiao-config
cd /data/www/neihan.sites/packages/haxibiao/cms && git remote add github https://github.com/haxibiao/haxibiao-cms
cd /data/www/neihan.sites/packages/haxibiao/dimension && git remote add github https://github.com/haxibiao/haxibiao-dimension