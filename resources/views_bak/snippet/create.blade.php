@extends('layouts.app')

@section('title')
	创建片段
@stop

@push('css')
    @include('article.parts.upload_css')
@endpush

@section('content')
	<div class="container">
		  <ol class="breadcrumb">
		    <li><a href="/">{{ config('app.name') }}</a></li>
		    <li><a href="/snippet">片段</a></li>
		    <li class="active">创建片段</li>
		  </ol>
		<div class="panel panel-default">
			<div class="panel-body">
				
					<div class="col-md-8">
						{!! Form::open(['method' => 'POST', 'route' => 'snippet.store', 'class' => 'form-horizontal', 'enctype' => "multipart/form-data"]) !!}
				
						    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
						        {!! Form::label('title', '片段标题') !!}
						        {!! Form::text('title', null, ['class' => 'form-control', 'required' => 'required']) !!}
						        <small class="text-danger">{{ $errors->first('title') }}</small>
						    </div>

						    <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
						        {!! Form::label('image', '图标') !!}
						        {!! Form::file('image', ['required' => 'required']) !!}
						        <p class="help-block">可以是某药材，食材，英雄物品，道具等的图片(建议尺寸100*100,或者200*200)...</p>
						        <small class="text-danger">{{ $errors->first('image') }}</small>
						    </div>

						    <div class="form-group{{ $errors->has('body') ? ' has-error' : '' }}">
						        {!! Form::label('body', '正文内容') !!}
						        {!! Form::hidden('body', null, ['class' => 'form-control', 'required' => 'required']) !!}
						        <div class="editable"></div>
						        <p class="help-block">可以是一段编辑好格式的小文本，也可以是图片，视频等...</p>
						        <small class="text-danger">{{ $errors->first('body') }}</small>
						    </div>	
						
						    <div class="btn-group pull-right">
						        {!! Form::reset("Reset", ['class' => 'btn btn-warning']) !!}
						        {!! Form::submit("保存", ['class' => 'btn btn-success']) !!}
						    </div>
						
						{!! Form::close() !!}
					</div>
					<div class="col-md-4">
						@include('snippet.parts.add_panel')
					</div>
				
			</div>
		</div>
	</div>
@stop

@push('scripts')

@include('article.parts.upload_js')

@include('article.parts.summernote_init')

<script type="text/javascript">

  $(function() {

    // $('.editable').trigger('focus');

    //prevent double click try to create twice ...
    $('input[type="submit"]').click(function() {
        // $('input[type="submit"]').attr('disabled',true);
        window.setTimeout(function(){
                $('input[type="submit"]').attr('disabled',true);
            },100);
        window.setTimeout(function(){
                $('input[type="submit"]').attr('disabled',false);
            },2000);
    });
    
  });

</script>

<style>
    .bootstrap-tagsinput {
        width: 100%;
    }
    #keywords {
        width: 100%;
    }
</style>
@endpush