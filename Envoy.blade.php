@include('config/envoy.php')

@servers(['local' => 'localhost','hk001' => $hk001, 'hk002' => $hk002, 'hk002' => $hk002])

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

@task('push', ['on' => 'local'])
hostname
cd {{ $www }}
git push
@endtask

@task('web_pull', ['on' => ['hk002'], 'parallel' => true])
cd {{ $www }}
{{ $refresh_env_config }}
{{ $cache_clear }}
@endtask

@task('web_seed', ['on' => ['hk002'], 'parallel' => true])
cd {{ $www }}
{{ $refresh_env_config }}
{{ $run_migrate }}
{{ $cache_clear }}
@endtask

@task('web_update', ['on' => ['hk002'], 'parallel' => true])
cd {{ $www }}
{{ $clear_bootstrap_cache }}
{{ $run_composer }}
{{ $refresh_env_config }}
{{ $run_migrate }}
{{ $cache_clear }}
@endtask