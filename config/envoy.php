<?php

require 'envoydomain.php';

$git = 'root@' . $web_server . ':/data/www/' . $domain;

$hk001 = 'root@hk001';
$gz002 = 'root@gz002';
$web   = 'root@' . $web_server;

$git_pull_force = <<<EOT
chmod -R 777 storage/
chown -R www:www storage/
git config core.filemode false
git checkout .
git pull
EOT;

$clear_bootstrap_cache = <<<EOT
rm -rf bootstrap/cache/*.php
EOT;

$refresh_env_config = <<<EOT
chmod -R 777 storage/
chown -R www:www storage/
php artisan env:refresh --local
php artisan env:refresh --prod
EOT;

$run_composer = <<<EOT
composer install
composer dump-autoload
EOT;

$run_migrate = <<<EOT
php artisan migrate --seed --force
EOT;

$run_commands = <<<EOT
EOT;

$cache_clear = <<<EOT
php artisan cache:clear
EOT;


$copy_worker_confi = <<<EOT
cp -rf ./config/worker/* /etc/supervisor/conf.d/
supervisorctl reread
supervisorctl update
supervisorctl start laravel-worker:*
EOT;

