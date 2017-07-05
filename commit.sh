#!bin/bash

git add --all
git commit -am 'bash commit: $1'

cd ../dongdianyao.com
git add --all
git commit -am 'bash commit: $1'

cd../dongmeiwei.com
git add --all
git commit -am 'bash commit: $1'