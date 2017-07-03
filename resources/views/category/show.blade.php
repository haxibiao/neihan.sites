@extends('layouts.app')

@section('content')
	<div class="container">
		<h1>{{ $category->name }} ({{ $category->name_en }})</h1>
		<p class="list-group-item-text"> {{ $category->description }} {{ $category->created_at->diffForHumans() }} - {{ $category->user->name }}</p>
	</div>
@stop