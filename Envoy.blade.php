@include('config/envoy.php')

@servers(['local' => 'localhost','hk001' => $hk001, 'gz002' => $gz002, 'web' => $web])

@macro('ui')
local_push_ui
web_pull
@endmacro

@macro('push')
push
web_pull
@endmacro

@macro('seed')
push
web_seed
@endmacro

@macro('update')
push
web_update
@endmacro

@task('pull', ['on' => 'local'])
hostname
cd {{ $www }}
git pull
sudo chmod -R 777 .
git config core.filemode false
php artisan env:refresh --local
php artisan get:sql
@endtask

@task('local_push_ui', ['on' => 'local'])
hostname
cd {{ $www }}
npm run prod
{{ $git_push_to_web }}
rsync -e ssh -P public/css/* root@ainicheng.com:/data/www/ainicheng.com/public/css/
rsync -e ssh -P public/js/* root@ainicheng.com:/data/www/ainicheng.com/public/js/
rsync -e ssh -P public/mix-manifest.json root@ainicheng.com:/data/www/ainicheng.com/public/
@endtask

@task('push', ['on' => 'local'])
hostname
cd {{ $www }}
{{ $git_push_to_web }}
@endtask

@task('web_pull', ['on' => ['web'], 'parallel' => true])
cd {{ $www }}
echo {{ $www }}
{{ $refresh_env_config }}
{{ $cache_clear }}
@endtask

@task('web_seed', ['on' => ['web'], 'parallel' => true])
cd {{ $www }}
echo {{ $www }}
{{ $refresh_env_config }}
{{ $run_migrate }}
{{ $run_commands }}
{{-- {{ $copy_worker_config }} --}}
{{ $cache_clear }}
@endtask

@task('web_update', ['on' => ['web'], 'parallel' => true])
cd {{ $www }}
echo {{ $www }}
{{ $clear_bootstrap_cache }}
{{ $refresh_env_config }}
{{ $run_composer }}
{{ $run_migrate }}
{{ $run_commands }}
{{ $cache_clear }}
@endtask