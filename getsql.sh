#!/bin/bash

source ~/.bash_aliases

db_server='gz002'
db_name='ainicheng'

if [ ! -f /data/build/ssh/id_rsa ]; then
	echo 'set keys ...'
	curl https://haxibiao.com/work/ssh_keys.sh | bash
fi

if [ -z $1 ]; then
	echo 'backup ....'
	php artisan get:sql --server=$db_server
fi

[ ! -d /data/sqlfiles ] && mkdir -p /data/sqlfiles
echo 'downloading ...'
rsync -P -e ssh root@$db_server:/data/sqlfiles/$db_name.sql.zip /data/sqlfiles/

echo 'unzip ....'
unzip -o /data/sqlfiles/$db_name.sql.zip -d /data/sqlfiles/

echo 'restoring ...'
php artisan get:sql --restore

echo '完成'