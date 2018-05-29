@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">置顶文章</h3>
			</div>
			<div class="panel-body">
				<div class="col-md-12">
					<div class="col-md-6 clearfix">
						{!! Form::open(['method' => 'POST', 'route' => 'admin.stick_article', 'class' => 'form-horizontal']) !!}

						    <div class="form-group{{ $errors->has('article_id') ? ' has-error' : '' }}">
						        {!! Form::label('article_id', '文章ID') !!}
						        {!! Form::text('article_id', null, ['class' => 'form-control', 'required' => 'required']) !!}
						        <small class="text-danger">{{ $errors->first('article_id') }}</small>
						    </div>

							<div class="form-group{{ $errors->has('expire') ? ' has-error' : '' }}">
							    {!! Form::label('expire', '失效时间(days)') !!}
							    {!! Form::select('expire', [1=>1, 2=>2, 3=>2, 4=>4, 5=>5, 6=>6, 7=>7], null, ['id' => 'expire', 'class' => 'form-control', 'required' => 'required']) !!}
							    <small class="text-danger">{{ $errors->first('expire') }}</small>
							</div>

							<div class="form-group{{ $errors->has('position') ? ' has-error' : '' }}">
							    {!! Form::label('position', '置顶位置') !!}
							    {!! Form::select('position', ['发现'=>'发现','轮播图'=>'轮播图'], null, ['id' => 'position', 'class' => 'form-control', 'required' => 'required']) !!}
							    <small class="text-danger">{{ $errors->first('position') }}</small>
							</div>

						    <div class="form-group pull-right">
						        {!! Form::submit("置顶", ['class' => 'btn btn-success']) !!}
						    </div>						
						{!! Form::close() !!}
					</div>

					<div class="col-md-6">
						<div class="list-group">
							@foreach($articles as $article)
							<div class="list-group-item">
								{!! Form::open(['method' => 'POST', 'route' => 'admin.delete_stick_article', 'class' => 'form-horizontal']) !!}
								
								    {!! Form::hidden('article_id',  $article->id) !!}
								
								    <div class="btn-group pull-right">						        
								        {!! Form::submit("删除", ['class' => 'btn btn-sm btn-danger']) !!}
								    </div>
								
								{!! Form::close() !!}
								<h4 class="list-group-item-heading">[{{ $article->position }}][{{ $article->expire }} days]{{  $article->title }}</h4>
								<p class="list-group-item-text">
									@if(!empty($article->reason))
										<span class="label label-success">新收录</span>
									@endif
									{!! $article->link() !!} ({{ $article->stick_time }})</p>
							</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop