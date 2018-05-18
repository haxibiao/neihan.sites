@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">置顶专题</h3>
			</div>
			<div class="panel-body">
				<div class="col-md-12">
					<div class="col-md-6">
						{!! Form::open(['method' => 'POST', 'route' => 'admin.stick_category', 'class' => 'form-horizontal']) !!}

						    <div class="form-group{{ $errors->has('category_name') ? ' has-error' : '' }}">
						        {!! Form::label('category_name', '专题ID') !!}
						        {!! Form::text('category_name', null, ['class' => 'form-control', 'required' => 'required']) !!}
						        <small class="text-danger">{{ $errors->first('category_name') }}</small>
						    </div>

							<div class="form-group{{ $errors->has('expire') ? ' has-error' : '' }}">
							    {!! Form::label('expire', '失效时间(days)') !!}
							    {!! Form::select('expire', [1=>1, 2=>2, 3=>2, 4=>4, 5=>5, 6=>6, 7=>7], null, ['id' => 'expire', 'class' => 'form-control', 'required' => 'required']) !!}
							    <small class="text-danger">{{ $errors->first('expire') }}</small>
							</div>

						    <div class="btn-group pull-right">
						        {!! Form::submit("置顶", ['class' => 'btn btn-success']) !!}
						    </div>						
						{!! Form::close() !!}
					</div>

					<div class="col-md-6">
						<div class="list-group">
							@foreach($categories as $category)
							<div class="list-group-item">
								{!! Form::open(['method' => 'POST', 'route' => 'admin.delete_stick_category', 'class' => 'form-horizontal']) !!}
								
								    {!! Form::hidden('category_id',  $category->id) !!}
								
								    <div class="btn-group pull-right">						        
								        {!! Form::submit("删除", ['class' => 'btn btn-sm btn-danger']) !!}
								    </div>
								
								{!! Form::close() !!}
								<h4 class="list-group-item-heading">[{{ $category->expire }} days]{{  $category->name }}</h4>
								<p class="list-group-item-text">
									@if(!empty($category->reason))
										<span class="label label-success">新收录</span>
									@endif
									{!! $category->link() !!} ({{ $category->stick_time }})</p>
							</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop