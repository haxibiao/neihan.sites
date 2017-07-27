@extends('layouts.app')

@section('title')
	视频: {{ $video->title }} - 
@stop

@section('content')
 	<div class="container">
 		<div class="panel panel-default">
 			<div class="panel-heading">
 				<h3 class="panel-title">{{  $video->title }}</h3>
 			</div>
 			<div class="panel-body">
 				<div class="embed-responsive embed-responsive-16by9">
 					<video preload="auto" poster="{{ get_img($video->cover) }}" controls autoplay="true">
 						<source src="{{ get_img($video->path) }}" type="video/mp4" />
 					</video>
 				</div>
 			</div>
 			<div class="panel-footer">
 				<a class="btn btn-default" href="/video" role="button">返回列表</a>
 			</div>
 		</div>
 	</div>
@stop