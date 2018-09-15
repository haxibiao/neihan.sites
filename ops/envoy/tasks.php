<?php

require_once 'commands.php';
require_once 'settings.php';

$www = '/data/www/' . $domain;

$git_push_prod     = 'git checkout master && git push origin master';
$git_push_staging = 'git push staging master';

$staging = 'root@' . $domain_staging;
$prod    = 'root@' . $domain;

$git_pull_force = <<<EOT
git stash -u
chmod -R 777 storage/
chown -R www:www storage/
git config core.filemode false
git pull
EOT;

$clear_bootstrap_cache = <<<EOT
rm -rf bootstrap/cache/*.php
EOT;

$refresh_env_prod = <<<EOT
php artisan env:refresh --env=prod --db_host=$db_server --db_database=$db_database
EOT;

$refresh_env_staging = <<<EOT
php artisan env:refresh --env=staging --db_host=$db_server_staging --db_database=$db_database_staging
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
php artisan view:clear
php artisan config:clear
EOT;

function copy_ui($server, $domain)
{
    $copy_ui_sh = <<<EOT
echo '上传UI代码到:$server ....'
rsync -e ssh -P /data/www/$domain/public/css/* $server:/data/www/$domain/public/css/
rsync -e ssh -P /data/www/$domain/public/js/* $server:/data/www/$domain/public/js/
rsync -e ssh -P /data/www/$domain/public/mix-manifest.json $server:/data/www/$domain/public/
EOT;
    return $copy_ui_sh;
}

function sync_etc($domain)
{
    $domain_key     = str_replace(".com", "", $domain);
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
    return $sync_etc_confs;
}
