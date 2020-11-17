#!/bin/bash

echo "拉取代码..."
git checkout master
git pull

echo "更新子模块git代码..."
git submodule init && git submodule update && git pull --recurse-submodules

echo "安装依赖..."
composer install
