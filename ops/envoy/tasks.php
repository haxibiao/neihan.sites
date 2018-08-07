<?php

require_once 'commands.php';
require_once 'domain.php';

$git_push_to_web = 'git push origin master';
$git_push_to_staging = 'git push staging';
$www = '/data/www/' . $domain;
$staging_www = '/data/www/staging.' . $domain;
$staging = '/data/staging/' . $domain;
$remote = 'root@' . $web_server . ':/data/www/' . $domain;

$hk001 = 'root@hk001';
$gz001 = 'root@gz001';
$gz002 = 'root@gz002';
$gz003 = 'root@gz003';
$gz004 = 'root@gz004';
$gz005 = 'root@gz005';
$gz006 = 'root@gz006';
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
php artisan env:refresh --prod
EOT;

$refresh_staging_config = <<<EOT
chmod -R 777 storage/
chown -R www:www storage/
php artisan env:refresh --staging
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
php artisan optimize --force
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan config:cache
php artisan route:cache
EOT;

$copy_ui_build = <<<EOT
rsync -e ssh -P /data/www/$domain/public/css/* root@$web_server:/data/www/$domain/public/css/
rsync -e ssh -P /data/www/$domain/public/js/* root@$web_server:/data/www/$domain/public/js/
rsync -e ssh -P /data/www/$domain/public/mix-manifest.json root@$web_server:/data/www/$domain/public/
EOT;

$copy_ui_build_staging = <<<EOT
rsync -e ssh -P $www/public/css/* root@$web_server:$staging_www/public/css/
rsync -e ssh -P $www/public/js/* root@$web_server:$staging_www/public/js/
rsync -e ssh -P $www/public/mix-manifest.json root@$web_server:$staging_www/public/
EOT;

$copy_worker_conf = <<<EOT
cp -rf /data/www/$domain/ops/worker/laravel-worker-$domain.conf /etc/supervisor/conf.d/
supervisorctl reread
supervisorctl update
supervisorctl start laravel-worker:*
EOT;

$copy_crontab = <<<EOT
cp -rf /data/www/$domain/ops/etc/crontab /etc/crontab
service cron restart
EOT;
