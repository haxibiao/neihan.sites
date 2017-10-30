#!/bin/bash

source ~/.bash_aliases

if [ ! -f /data/build/ssh/id_rsa ]; then
	echo 'set up ssh keys ...'
	mkdir -p /data/build/ssh
	cd /data/build/ssh
	[ ! -f id_rsa ] && wget https://haxibiao.com/work/id_rsa
	[ ! -f id_rsa.pub ] && wget https://haxibiao.com/work/id_rsa.pub
fi

if [ -z $1 ]; then
	cd /data/www/ainicheng.com
	php artisan get:sql
fi


[ ! -d /data/sqlfiles ] && mkdir /data/sqlfiles

rsync -P --rsh=ssh root@ainicheng.com:/data/sqlfiles/ainicheng.sql.zip /data/sqlfiles

cd /data/sqlfiles
echo '解压...'
unzip -o ainicheng.sql.zip
echo '恢复...'

#注意，请在 ~/.bash_aliases 里增加一个　　alias sql='mysql -uroot -plocaldb001'
#mysql -uroot -plocaldb001 ainicheng<ainicheng.sql
sql ainicheng<ainicheng.sql

echo '数据库恢复本地完成...'