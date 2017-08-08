@extends('layouts.app')

@section('title')
	片段
@stop

@push('css')
    @include('article.parts.upload_css')
@endpush

@section('content')
<div class="container">
  <ol class="breadcrumb">
    <li><a href="/">{{ config('app.name') }}</a></li>
    <li class="active">片段</li>
  </ol>
    <div class="panel panel-default">
        <div class="panel-body">
			@foreach($snippets as $snippet)
			<div class="pull-right">
				{!! Form::open(['method' => 'delete', 'route' => ['snippet.destroy', $snippet->id], 'class' => 'form-horizontal pull-left right10']) !!}
                {!! Form::submit('删除', ['class' => 'btn btn-danger']) !!}                
              {!! Form::close() !!}
				<a class="btn btn-info" href="/snippet/{{ $snippet->id }}/edit" role="button">编辑</a>
			</div>
			<div class="media">
				<a class="pull-left" href="/snippet/{{ $snippet->id }}">
					<img class="media-object" src="{{ get_img($snippet->image) }}" alt="{{ $snippet->title }}">
				</a>
				<div class="media-body">
					<h4 class="media-heading">{{ $snippet->title }}</h4>
					<p>{!! $snippet->body !!}</p>
				</div>
			</div>
			<hr>
			@endforeach

			<p>
				{!! $snippets->render() !!}
			</p>
        </div>
    </div>
</div>
@stop
