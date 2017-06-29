<?php

$git = 'root@hk002:/data/www/dongdianyi.com';
$www = '/data/www/dongdianyi.com';

$hk001 = 'root@hk001';
$hk002 = 'root@hk002';
$hk003 = 'root@hk003';

$git_pull_force = <<<EOT
chmod -R 777 .
chown -R www:www .
git config core.filemode false
git checkout .
git pull
EOT;

$clear_bootstrap_cache = <<<EOT
rm -rf bootstrap/cache/*.php
EOT;

$refresh_env_config = <<<EOT
chmod -R 777 .
chown -R www:www .
/bin/cp -rf .env.prod .env
EOT;

$run_composer = <<<EOT
composer install
composer dump-autoload
EOT;

$run_migrate = <<<EOT
php artisan migrate --seed --force
EOT;

$cache_clear = <<<EOT
php artisan cache:clear
EOT;

?>