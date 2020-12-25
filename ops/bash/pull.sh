#!/bin/bash

echo "更新git子模块代码..."
git submodule init && git submodule update && git pull --recurse-submodules

echo "安装依赖..."
composer install -o --ignore-platform-reqs
