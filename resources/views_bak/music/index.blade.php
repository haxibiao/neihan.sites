@extends('layouts.app')

@section('title')
      所有的音乐
@stop

@section('content')
     <div class="container">
     	  <div class="panel panel-info">
     	  	<div class="panel-heading">
     	  		<h3 class="panel-title">所有的音乐</h3>
     	  	</div>
     	  	<div class="panel-body">
     	  	  @foreach($musics as $music)
     	  		<div class="pull-left">
     	  			   名字:{{ $music->title }}
     	  		</div> 

                    <div class="text-center">
                          音乐id:{{ $music->id }}
                    </div>               

     	  		<div class="pull-right">
     	  			   创建时间 {{ $music->created_at }}
     	  		</div>
     	  		<br>
                <div class="row">
     	  	      <a class="btn btn-info pull-right" href="/music/{{ $music->id }}/edit" style="margin: 2%">编辑</a>
     	  	   </div>
     	  	  @endforeach
     	  	</div>
     	  </div>
     </div>
@stop