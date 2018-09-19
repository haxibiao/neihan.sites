#!bin/bash

function pull() {
	domain=$1;
	echo "============ $domain ============"
	if [ ! -d /data/www/$domain ]; then
		cd /data/www
		git clone ssh://root@$domain:/data/www/$domain
	fi
	cd /data/www/$domain
	if [ -z $2 ]; then
		git stash -u
	else 
		echo "刷新.git ..."
		scp root@$domain:/data/www/$domain/git.zip .
		rm -rf .git
		unzip -q git.zip
		git remote add origin ssh://root@$domain:/data/www/$domain
	fi 
	git pull origin master
	git push origin master -u
	[ ! -d ./vendor ] && composer install -q
	php artisan env:refresh
}

pull "dongmeiwei.com" $*
pull "dongdianyi.com" $*
pull "dongdianyao.com" $*

pull "dianmoge.com" $*
pull "qunyige.com" $*
pull "youjianqi.com" $*

pull "jucheshe.com" $*
pull "youwangfa.com" $*
pull "didilinju.com" $*