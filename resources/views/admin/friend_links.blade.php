@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">友情链接</h3>
			</div>
			<div class="panel-body">
				<div class="col-md-12">
					<div class="col-md-6">
						{!! Form::open(['method' => 'POST', 'route' => 'admin.add_friend_link', 'class' => 'form-horizontal']) !!}
				
						    <div class="form-group{{ $errors->has('website_name') ? ' has-error' : '' }}">
						        {!! Form::label('website_name', '网站名') !!}
						        {!! Form::text('website_name', null, ['class' => 'form-control', 'required' => 'required']) !!}
						        <small class="text-danger">{{ $errors->first('website_name') }}</small>
						    </div>

						    <div class="form-group{{ $errors->has('website_domain') ? ' has-error' : '' }}">
						        {!! Form::label('website_domain', '网站域名') !!}
						        {!! Form::text('website_domain', null, ['class' => 'form-control', 'required' => 'required']) !!}
						        <small class="text-danger">{{ $errors->first('website_domain') }}</small>
						    </div>
						
						    <div class="btn-group pull-right">
						        {!! Form::submit("添加", ['class' => 'btn btn-success']) !!}
						    </div>
						
						{!! Form::close() !!}
					</div>

					<div class="col-md-6">
						<div class="list-group">
							@foreach($links as $link)
							<a href="#" class="list-group-item">
								{!! Form::open(['method' => 'POST', 'route' => 'admin.delete_friend_link', 'class' => 'form-horizontal']) !!}
								
								    {!! Form::hidden('website_domain', $link['website_domain']) !!}
								
								    <div class="btn-group pull-right">						        
								        {!! Form::submit("删除", ['class' => 'btn btn-sm btn-danger']) !!}
								    </div>
								
								{!! Form::close() !!}
								<h4 class="list-group-item-heading">{{ $link["website_name"] }}</h4>
								<p class="list-group-item-text">{{ $link['website_domain'] }}</p>
							</a>
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop