@extends('layouts.app')

@section('title')
	片段: {{ $snippet->title }}
@stop

@push('css')
    @include('article.parts.upload_css')
@endpush

@section('content')
<div class="container">
  <ol class="breadcrumb">
    <li><a href="/">{{ config('app.name') }}</a></li>
    <li><a href="/snippet">片段</a></li>
    <li class="active">{{ $snippet->title }}</li>
  </ol>
    <div class="panel panel-default">
        <div class="panel-body">
			<div class="media">
				<a class="pull-left" href="/snippet/{{ $snippet->id }}">
					<img class="media-object" src="{{ get_img($snippet->image) }}" alt="{{ $snippet->title }}">
				</a>
				<div class="media-body">
					<h4 class="media-heading">{{ $snippet->title }}</h4>
					<p>{!! $snippet->body !!}</p>
				</div>
			</div>
        </div>
    </div>
</div>
@stop
