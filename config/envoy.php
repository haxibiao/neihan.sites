<?php

require 'envoydomain.php';

$git = 'root@hk001:/data/www/' . $domain;
$www = '/data/www/' . $domain;

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
php artisan env:refresh --local
php artisan env:refresh --prod
EOT;

$run_composer = <<<EOT
composer install
composer dump-autoload
EOT;

$run_migrate = <<<EOT
php artisan migrate --seed --force


php artisan fix:data --articles
php artisan fix:data --traffic
EOT;

$cache_clear = <<<EOT
php artisan cache:clear
EOT;

?>