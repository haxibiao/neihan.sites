#!/bin/bash

source ~/.bash_aliases

echo 'set up ssh keys ...'
mkdir -p /data/build/ssh
cd /data/build/ssh
[ ! -f id_rsa ] && wget https://ainicheng.com/work/id_rsa
[ ! -f id_rsa.pub ] && wget https://ainicheng.com/work/id_rsa.pub

if [ -z "$1" ]; then
	cd /data/www/ainicheng.com
	php artisan get:sql
fi


[ ! -d /data/sqlfiles ] && mkdir /data/sqlfiles

rsync -P --rsh=ssh root@hk001:/data/sqlfiles/ainicheng.sql.zip /data/sqlfiles

cd /data/sqlfiles
echo '解压...'
unzip -o ainicheng.sql.zip
echo '恢复...'
mysql -uroot -plocaldb001 ainicheng<ainicheng.sql

echo '数据库恢复本地完成...'