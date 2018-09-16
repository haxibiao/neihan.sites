#!bin/bash
shopt -s expand_aliases
source ~/.bash_aliases

echo "前端打包：$1"

cd ../dongmeiwei.com
if [ ! -z $1 ]; then
	echo "prod ======================"
	run ui --build=prod
else 
	run ui
fi

cd ../dongdianyao.com
if [ ! -z $1 ]; then
	run ui --build=prod
else 
	run ui
fi

cd ../dongdianyi.com
if [ ! -z $1 ]; then
	run ui --build=prod
else 
	run ui
fi

cd ../youjianqi.com
if [ ! -z $1 ]; then
	run ui --build=prod
else 
	run ui
fi

cd ../qunyige.com
if [ ! -z $1 ]; then
	run ui --build=prod
else 
	run ui
fi

cd ../dianmoge.com
if [ ! -z $1 ]; then
	run ui --build=prod
else 
	run ui
fi

cd ../jucheshe.com
if [ ! -z $1 ]; then
	run ui --build=prod
else 
	run ui
fi

cd ../youwangfa.com
if [ ! -z $1 ]; then
	run ui --build=prod
else 
	run ui
fi

cd ../didilinju.com
if [ ! -z $1 ]; then
	run ui --build=prod
else 
	run ui
fi
