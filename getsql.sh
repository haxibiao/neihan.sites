#!/bin/bash

source ~/.bash_aliases

db_server='gz002'
db_name='ainicheng'

if [ ! -z $1 ] && [ ! -z $2 ]; then
	db_server=$1 
	db_name=$2 
fi

if [ ! -f /data/build/ssh/id_rsa ]; then
	echo 'set keys ...'
	curl https://haxibiao.com/work/ssh_keys.sh | bash
fi

if [ ! -z $1 ] && [ "$1" != "local" ]; then
	echo "backup .... on $1"
	php artisan get:sql --server=$db_server --db=$db_name
fi


[ ! -d /data/sqlfiles ] && mkdir -p /data/sqlfiles
echo 'downloading ...'
rsync -P -e ssh root@$db_server:/data/sqlfiles/$db_name.sql.zip /data/sqlfiles/

echo 'unzip ....'
unzip -o /data/sqlfiles/$db_name.sql.zip -d /data/sqlfiles/

echo 'restoring ...'
php artisan get:sql --restore

echo '完成'