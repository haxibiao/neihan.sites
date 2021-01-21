#!/bin/bash

echo "准备 breeze 需要的所有 sub modules"

git submodule add -f http://code.haxibiao.cn/packages/haxibiao-helpers ./packages/haxibiao/helpers
git submodule add -f http://code.haxibiao.cn/packages/haxibiao-config ./packages/haxibiao/config
git submodule add -f http://code.haxibiao.cn/packages/haxibiao-dimension ./packages/haxibiao/dimension
git submodule add -f http://code.haxibiao.cn/packages/haxibiao-matomo ./packages/haxibiao/matomo
git submodule add -f http://code.haxibiao.cn/packages/haxibiao-wallet ./packages/haxibiao/wallet
git submodule add -f http://code.haxibiao.cn/packages/haxibiao-task ./packages/haxibiao/task
git submodule add -f http://code.haxibiao.cn/packages/haxibiao-store ./packages/haxibiao/store
git submodule add -f http://code.haxibiao.cn/packages/haxibiao-sns ./packages/haxibiao/sns
git submodule add -f http://code.haxibiao.cn/packages/haxibiao-media ./packages/haxibiao/media
git submodule add -f http://code.haxibiao.cn/packages/haxibiao-live ./packages/haxibiao/live
git submodule add -f http://code.haxibiao.cn/packages/haxibiao-content ./packages/haxibiao/content
git submodule add -f http://code.haxibiao.cn/packages/haxibiao-cms ./packages/haxibiao/cms
git submodule add -f http://code.haxibiao.cn/packages/haxibiao-breeze ./packages/haxibiao/breeze