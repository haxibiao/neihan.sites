@include('ops/envoy/tasks.php')

@servers(['local' => 'localhost','staging' => $staging,'prod' => $prod])

@macro('push')
@if($env && $env == "staging")
	staging_push
	staging_refresh
@else
	prod_push
	prod_refresh
@endif
@endmacro

@macro('seed')
@if($env && $env == "staging")
	staging_push
	staging_seed
@else
	prod_push
	prod_seed
@endif
@endmacro

@macro('update')
@if($env && $env == "staging")
	staging_push
	staging_update
@else
	prod_push
	prod_update
@endif
@endmacro

@macro('ui')
@if($env && $env == "staging")
	staging_ui
@else
	prod_ui
@endif
@endmacro

@macro('sys')
prod_push
prod_sys
@endmacro

@macro('cmds')
@if($env && $env == "staging")
	staging_push
	staging_cmds
@else
	prod_push
	prod_cmds
@endif
@endmacro

@task('prod_push', ['on' => 'local'])
cd {{ $www }}
{{ $git_push_prod }}
@endtask

@task('staging_push', ['on' => 'local'])
cd {{ $www }}
{{ $git_push_staging }}
@endtask

@task('prod_refresh', ['on' => ['prod'], 'parallel' => true])
cd {{ $www }}
{{ $refresh_env_prod }}
{{ $cache_clear }}
@endtask

@task('staging_refresh', ['on' => ['staging'], 'parallel' => true])
cd {{ $www }}
{{ $refresh_env_staging }}
{{ $cache_clear }}
@endtask

@task('prod_seed', ['on' => ['prod'], 'parallel' => true])
cd {{ $www }}
{{ $refresh_env_prod }}
{{ $run_migrate }}
{{ $cache_clear }}
@endtask

@task('staging_seed', ['on' => ['staging'], 'parallel' => true])
cd {{ $www }}
{{ $refresh_env_staging }}
{{ $run_migrate }}
{{ $cache_clear }}
@endtask

@task('prod_update', ['on' => ['prod'], 'parallel' => true])
cd {{ $www }}
{{ $clear_bootstrap_cache }}
{{ $refresh_env_prod }}
{{ $run_composer }}
{{ $run_migrate }}
{{ $cache_clear }}
@endtask

@task('staging_update', ['on' => ['staging'], 'parallel' => true])
cd {{ $www }}
{{ $clear_bootstrap_cache }}
{{ $refresh_env_staging }}
{{ $run_composer }}
{{ $run_migrate }}
{{ $cache_clear }}
@endtask

@task('prod_sys', ['on' => ['prod'], 'parallel' => true])
{{ sync_etc($domain) }}
@endtask

@task('prod_cmds', ['on' => ['prod'], 'parallel' => true])
cd {{ $www }}
{{ $run_commands }}
@endtask

@task('staging_cmds', ['on' => ['staging'], 'parallel' => true])
cd {{ $www }}
{{ $run_commands }}
@endtask

@task('prod_ui', ['on' => 'local'])
cd {{ $www }}
@if ($build)
	npm run prod
@endif
{{ copy_ui($prod, $domain) }}
@endtask

@task('staging_ui', ['on' => 'local'])
cd {{ $www }}
@if ($build)
	npm run prod
@endif
{{ copy_ui($staging, $domain) }}
@endtask