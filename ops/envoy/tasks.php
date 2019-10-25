<?php

require_once 'commands.php';
require_once 'config.php';

$www         = '/data/www/' . $domain;
$www_staging = '/data/staging/' . $domain;

$git_push_prod    = 'git checkout master && git push origin master';
$git_push_staging = 'git push staging master';

$staging = 'root@' . $domain_staging;
$prod    = 'root@' . $domain;

$git_pull_force = <<<EOT
chmod -R 777 storage/
chown -R www:www storage/
git config core.filemode false
git pull
EOT;

$clear_bootstrap_cache = <<<EOT
rm -rf bootstrap/cache/*.php
EOT;

$refresh_env_prod = <<<EOT
chmod -R 777 storage/
chown -R www:www storage/
git config core.filemode false
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

function copy_ui($server, $domain, $env = "prod")
{
    $webroot    = $env == "prod" ? "www" : "staging";
    $copy_ui_sh = <<<EOT
echo '上传UI代码到:$server ....'
rsync -e ssh -P /data/www/$domain/public/css/* $server:/data/$webroot/$domain/public/css/
rsync -e ssh -P /data/www/$domain/public/js/* $server:/data/$webroot/$domain/public/js/
rsync -e ssh -P /data/www/$domain/public/mix-manifest.json $server:/data/$webroot/$domain/public/
EOT;
    return $copy_ui_sh;
}

function sync_etc($domain, $env = "prod")
{
    $webroot        = $env == "prod" ? "www" : "staging";
    $domain_key     = str_replace(".com", "", $domain);
    $sync_etc_confs = <<<EOT
cp -rf /data/$webroot/$domain/ops/etc/* /etc/

echo "restart queue worker"
cd /data/$webroot/$domain/
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
