#!/bin/bash

source ~/.bash_aliases

server="gz002"
db="ainicheng"

if [ "$1" != "local" ] && [ "$1" != "refresh" ]; then
	if [ ! -z $1 ]; then
		server=$1
	fi

	if [ ! -z $2 ]; then
		db=$2
	fi

	if [ ! -f /data/build/ssh/id_rsa ]; then
		wget https://haxibiao.com/work/ssh_keys.sh -O ~/ssh_keys.sh && bash ~/ssh_keys.sh
	fi

echo '服务器上备份数据库...'
ssh root@$server 2>&1 << eeooff
	cd /data/sqlfiles
	sqld $db>$db.sql
	zip -r $db.sql.zip $db.sql
eeooff


	[ ! -d /data/sqlfiles ] && mkdir /data/sqlfiles

	rsync -P --rsh=ssh root@$server:/data/sqlfiles/$db.sql.zip /data/sqlfiles
fi

cd /data/sqlfiles
echo '解压...'
unzip -o $db.sql.zip
echo '恢复...'

#注意，请在 ~/.bash_aliases 里增加一个　　alias sql='mysql -uroot -plocaldb001'
mysql -uroot -plocaldb001 $db<$db.sql

echo '数据库恢复本地完成...'