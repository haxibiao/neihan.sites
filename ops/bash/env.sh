#!/bin/bash

echo "更新env ..."
sudo chmod -R 777 ./storage
git config core.filemode false
art storage:link

php artisan env:refresh --env=prod --db_host=gz002 --db_database=ainicheng