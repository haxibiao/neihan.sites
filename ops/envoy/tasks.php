<?php

require_once 'commands.php';
require_once 'settings.php';

$git_push_to_web = 'git push origin master';
$git_push_to_staging = 'git push staging staging';
$www = '/data/www/' . $domain;
$staging_www = '/data/www/staging.' . $domain;
$web   = 'root@' . $domain;

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

$refresh_env_prod = <<<EOT
chmod -R 777 storage/
chown -R www:www storage/
php artisan env:refresh --env=prod --db_host=$db_server --db_database=$db_database
EOT;

$refresh_env_staging = <<<EOT
chmod -R 777 storage/
chown -R www:www storage/
php artisan env:refresh --env=prod --db_host=$db_server_staging --db_database=$db_database_staging
EOT;

$run_composer = <<<EOT
composer install
composer dump-autoload
EOT;

$run_migrate = <<<EOT
composer dump-autoload
php artisan migrate --seed --force
EOT;

$cache_clear = <<<EOT
composer dump-autoload
php artisan cache:clear
php artisan view:clear
php artisan config:clear
EOT;

$copy_ui_prod = <<<EOT
rsync -e ssh -P /data/www/$domain/public/css/* root@$domain:/data/www/$domain/public/css/
rsync -e ssh -P /data/www/$domain/public/js/* root@$domain:/data/www/$domain/public/js/
rsync -e ssh -P /data/www/$domain/public/mix-manifest.json root@$domain:/data/www/$domain/public/
EOT;

$copy_ui_staging = <<<EOT
rsync -e ssh -P $www/public/css/* root@$domain:$staging_www/public/css/
rsync -e ssh -P $www/public/js/* root@$domain:$staging_www/public/js/
rsync -e ssh -P $www/public/mix-manifest.json root@$domain:$staging_www/public/
EOT;

$domain_key = str_replace(".com", "", $domain);
$sync_etc_confs = <<<EOT
cp -rf /data/www/$domain/ops/etc/* /etc/

echo "restart queue worker"
cd /data/www/$domain/
php artisan queue:restart

echo 'supervisor restart ...'
supervisorctl reread
supervisorctl update
supervisorctl start laravel-worker-$domain_key:*

echo 'cron restart ...'
service cron restart
EOT;
