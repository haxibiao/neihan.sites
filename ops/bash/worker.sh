#!/bin/bash

echo "重启队列 ..."
php artisan queue:restart

if [ "queue" == "$1" ]; then
    echo "更新 queue 队列 ..."
    rm -rf /etc/supervisor/conf.d/laravel-worker-ainicheng*
    /bin/cp -rf ./ops/workers/queue/*.conf /etc/supervisor/conf.d/
fi

if [ "web" == "$1" ]; then
    echo "更新 web 服务器需要的 workers..."
    rm -rf /etc/supervisor/conf.d/laravel-worker-ainicheng*
    /bin/cp -rf ./ops/workers/web/*.conf /etc/supervisor/conf.d/
fi

if [ "socket" == "$1" ]; then
    echo "更新 socket 服务器需要的 workers..."
    rm -rf /etc/supervisor/conf.d/laravel-worker-ainicheng*
    /bin/cp -rf ./ops/workers/socket/*.conf /etc/supervisor/conf.d/
fi

if [ "all" == "$1" ]; then
    echo "更新 全部 队列 ..."
    echo " -- web ..."
    /bin/cp -rf ./ops/workers/web/*.conf /etc/supervisor/conf.d/
    echo " -- socket ..."
    /bin/cp -rf ./ops/workers/socket/*.conf /etc/supervisor/conf.d/
    echo " -- queue ..."
    /bin/cp -rf ./ops/workers/queue/*.conf /etc/supervisor/conf.d/
fi

echo "全部跑 worker matomo proxy ..."
supervisorctl stop laravel-worker-ainicheng-matomo-proxy:*
supervisorctl start laravel-worker-ainicheng-matomo-proxy:*
pkill matomo

supervisorctl reread
supervisorctl update
