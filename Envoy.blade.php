@include('ops/envoy/tasks.php')

@servers(['local' => 'localhost','hk001' => $hk001, 'gz001' => $gz001,'gz002' => $gz002, 'gz003' => $gz003, 'gz004' => $gz004, 'gz005' => $gz005, 'gz006' => $gz006, 'web' => $web])

@macro('push')
local_push
web_pull
@endmacro

@macro('ui')
local_push_ui
@endmacro

@macro('stagingui')
local_push_ui_staging
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

@task('local_push_ui_staging', ['on' => 'local'])
hostname
cd {{ $www }}
npm run prod
{{ $copy_ui_build_staging }}
{{ $git_push_to_staging }}
@endtask

@task('local_push_ui', ['on' => 'local'])
hostname
cd {{ $www }}
{{-- npm run prod --}}
{{ $git_push_to_web }}
{{ $copy_ui_build }}
@endtask

@task('staging_pull', ['on' => ['gz005'], 'parallel' => true])
cd {{ $staging_www }}
echo {{ $staging_www }}
git pull
{{ $refresh_staging_config }}
{{ $cache_clear }}
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
{{ $refresh_env_config }}
{{ $run_migrate }}
{{ $cache_clear }}
@endtask

@task('web_update', ['on' => ['web'], 'parallel' => true])
cd {{ $www }}
echo {{ $www }}
{{ $clear_bootstrap_cache }}
{{ $run_composer }}
{{ $refresh_env_config }}
{{ $run_migrate }}
{{ $cache_clear }}
@endtask

@task('staging_update', ['on' => ['gz002'], 'parallel' => true])
cd {{ $www }}
echo {{ $www }}
git pull
{{ $clear_bootstrap_cache }}
{{ $run_composer }}
{{ $refresh_staging_config }}
{{ $run_migrate }}
{{ $cache_clear }}
@endtask

@task('web_sys', ['on' => ['web'], 'parallel' => true])
{{ $copy_worker_conf }}
{{ $copy_crontab }}
@endtask

@task('web_cmds', ['on' => ['web'], 'parallel' => true])
cd {{ $www }}
{{ $run_commands }}
@endtask