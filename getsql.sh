#!/bin/bash

source ~/.bash_aliases

if [ -z $1 ]; then
	echo '请指定数据库服务器，比如　bash getsql.sh hk001'
	exit
fi

if [ ! -f /data/build/ssh/id_rsa ]; then
	echo 'set up ssh keys ...'
	mkdir -p /data/build/ssh
	cd /data/build/ssh
	[ ! -f id_rsa ] && wget https://haxibiao.com/work/id_rsa
	[ ! -f id_rsa.pub ] && wget https://haxibiao.com/work/id_rsa.pub
fi

if [ -z $2 ]; then
	php artisan get:sql --server=$1
fi


[ ! -d /data/sqlfiles ] && mkdir /data/sqlfiles

rsync -P --rsh=ssh root@$1:/data/sqlfiles/dianmoge.sql.zip /data/sqlfiles

cd /data/sqlfiles
echo '解压...'
unzip -o dianmoge.sql.zip
echo '恢复...'

#注意，请在 ~/.bash_aliases 里增加一个　　alias sql='mysql -uroot -plocaldb001'
#mysql -uroot -plocaldb001 dianmoge<dianmoge.sql
sql dianmoge<dianmoge.sql

echo '数据库恢复本地完成...'