@extends('layouts.app')

@section('content')
	<div class="container">      
		<div class="col-md-8 col-md-offset-2">

			@if(session('saved'))
			<div style="padding-top: 20px">
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<strong>提示:</strong> 提交成功...
				</div>
			</div>
			@endif

			{!! Form::open(['method' => 'post', 'route' => ['admin.save_seo_config'], 'class' => 'form-horizontal']) !!}

			    <div class="form-group{{ $errors->has('seo_meta') ? ' has-error' : '' }}">
			        {!! Form::label('seo_meta', 'SEO站点验证meta') !!}
			        {!! Form::textarea('seo_meta',$config->seo_meta, ['class' => 'form-control']) !!}
			        <small class="text-danger">{{ $errors->first('seo_meta') }}</small>
			    </div>
			    <div class="form-group{{ $errors->has('seo_push') ? ' has-error' : '' }}">
			        {!! Form::label('seo_push', 'SEO站点push代码(可以多个搜索平台的一起输入,换行分开即可)') !!}
			        {!! Form::textarea('seo_push',$config->seo_push, ['class' => 'form-control']) !!}
			        <small class="text-danger">{{ $errors->first('seo_push') }}</small>
			    </div>
			    <div class="form-group{{ $errors->has('seo_tj') ? ' has-error' : '' }}">
			        {!! Form::label('seo_tj', 'SEO站点统计代码(同上)') !!}
			        {!! Form::textarea('seo_tj',$config->seo_tj, ['class' => 'form-control']) !!}
			        <small class="text-danger">{{ $errors->first('seo_tj') }}</small>
			    </div>
			
			    <div class="btn-group">
			        {!! Form::submit("提交", ['class' => 'btn btn-success']) !!}
			    </div>			
			{!! Form::close() !!}

			<div style="padding-top: 20px">
				<div class="alert alert-warning">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<strong>注意:</strong> SEO站点验证meta 用完验证网站后,可以清除的...
				</div>
			</div>
		</div>
	</div>
@stop