#!/bin/bash

echo "更新队列 ..."
rm -rf /etc/supervisor/conf.d/laravel-worker-ainicheng*
/bin/cp -rf ./ops/etc/supervisor/conf.d/laravel-worker-ainicheng* /etc/supervisor/conf.d/
supervisorctl reread
supervisorctl update

echo "重启队列 ... (TODO: 未包含提现队列，可能需要单独手动处理)"
# php artisan queue:restart # 重启所有队列
supervisorctl restart laravel-worker-ainicheng:*