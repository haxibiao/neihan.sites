#!/bin/bash

echo "测试 breeze 的所有 submodules"

php artisan test packages/haxibiao/content
php artisan test packages/haxibiao/media
php artisan test packages/haxibiao/sns
php artisan test packages/haxibiao/breeze

# php artisan test packages/haxibiao/task
# php artisan test packages/haxibiao/helpers
# php artisan test packages/haxibiao/config
# php artisan test packages/haxibiao/dimension
# php artisan test packages/haxibiao/matomo
# php artisan test packages/haxibiao/wallet
# php artisan test packages/haxibiao/live
# php artisan test packages/haxibiao/store
# php artisan test packages/haxibiao/cms
# php artisan test packages/haxibiao/question

