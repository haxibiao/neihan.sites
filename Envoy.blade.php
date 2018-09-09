@include('ops/envoy/tasks.php')

@servers(['local' => 'localhost','web' => $web])

@macro('push')
local_push
web_pull
@endmacro

@macro('ui')
push_ui
@endmacro

@macro('stagingui')
push_ui_staging
staging_pull
@endmacro

@macro('seed')
local_push
web_seed
@endmacro

@macro('update')
local_push
web_update
@endmacro

@macro('staging')
local_push
staging_update
@endmacro

@macro('sys')
local_push
web_sys
@endmacro

@macro('cmds')
local_push
web_cmds
@endmacro

@task('local_push', ['on' => 'local'])
hostname
cd {{ $www }}
{{ $git_push_to_web }}
@endtask

@task('push_ui_staging', ['on' => 'local'])
hostname
cd {{ $www }}
npm run prod
{{ $copy_ui_staging }}
{{ $git_push_to_staging }}
@endtask

@task('push_ui', ['on' => 'local'])
hostname
cd {{ $www }}
npm run prod
{{ $git_push_to_web }}
{{ $copy_ui_prod }}
@endtask

@task('staging_pull', ['on' => ['web'], 'parallel' => true])
cd {{ $staging_www }}
echo {{ $staging_www }}
git pull
{{ $refresh_env_staging }}
{{ $cache_clear }}
@endtask

@task('web_pull', ['on' => ['web'], 'parallel' => true])
cd {{ $www }}
echo {{ $www }}
{{ $refresh_env_prod }}
{{ $cache_clear }}
@endtask

@task('web_seed', ['on' => ['web'], 'parallel' => true])
cd {{ $www }}
echo {{ $www }}
{{ $refresh_env_prod }}
{{ $run_migrate }}
{{ $cache_clear }}
@endtask

@task('web_update', ['on' => ['web'], 'parallel' => true])
cd {{ $www }}
echo {{ $www }}
{{ $clear_bootstrap_cache }}
{{ $run_composer }}
{{ $refresh_env_prod }}
{{ $run_migrate }}
{{ $cache_clear }}
@endtask

@task('staging_update', ['on' => ['web'], 'parallel' => true])
cd {{ $www }}
echo {{ $www }}
git pull
{{ $clear_bootstrap_cache }}
{{ $run_composer }}
{{ $refresh_env_staging }}
{{ $run_migrate }}
{{ $cache_clear }}
@endtask

@task('web_sys', ['on' => ['web'], 'parallel' => true])
{{ $sync_etc_confs }}
@endtask

@task('web_cmds', ['on' => ['web'], 'parallel' => true])
cd {{ $www }}
{{ $run_commands }}
@endtask