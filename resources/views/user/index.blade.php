@extends('layouts.app')

@section('title')
	全部用户
@stop

@section('content')
      <ol class="breadcrumb">
        <li><a href="/">{{ config('app.name') }}</a></li>
        <li class="active">全部用户</li>
      </ol>
	
	<div class="container">
		 <div class="panel panel-default">
                <div class="panel-heading">                    
                    <h3 class="panel-title">全部用户</h3 class="panel-title">
                </div>
                <div class="panel-body">
                    
                        @foreach($users as $user)
                        <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
                            <a href="/user/{{ $user->id }}" class="thumbnail">
                                <img src="{{ get_avatar($user) }}" alt="" class="img img-circle">
                                <p class="strip_title">{{ $user->name }}</p>
                            </a>
                        </div>
                        @endforeach
                </div>
                <div class="panel-footer">
                    {!! $users->render() !!}
                </div>
            </div>
	</div>

@stop