#!/bin/bash

cd /data/www/ainicheng.com

mkdir -p  public/storage/img

rsync -P -e ssh -r root@ainicheng.com:/data/www/ainicheng.com/public/storage/img/* public/storage/img/


mkdir -p  public/storage/image

rsync -P -e ssh -r root@ainicheng.com:/data/www/ainicheng.com/public/storage/image/* public/storage/image/
