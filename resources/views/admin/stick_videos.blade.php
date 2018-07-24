@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">置顶视频</h3>
			</div>
			<div class="panel-body">
				<div class="col-md-12">
					<div class="col-md-6 clearfix">
						{!! Form::open(['method' => 'POST', 'route' => 'admin.stick_video', 'class' => 'form-horizontal']) !!}

						    <div class="form-group{{ $errors->has('video_id') ? ' has-error' : '' }}">
						        {!! Form::label('video_id', '视频ID') !!}
						        {!! Form::text('video_id', null, ['class' => 'form-control', 'required' => 'required']) !!}
						        <small class="text-danger">{{ $errors->first('video_id') }}</small>
						    </div>

							<div class="form-group{{ $errors->has('expire') ? ' has-error' : '' }}">
							    {!! Form::label('expire', '失效时间(days)') !!}
							    {!! Form::select('expire', [1=>1, 2=>2, 3=>3, 4=>4, 5=>5, 6=>6, 7=>7], null, ['id' => 'expire', 'class' => 'form-control', 'required' => 'required']) !!}
							    <small class="text-danger">{{ $errors->first('expire') }}</small>
							</div>

							<div class="form-group{{ $errors->has('position') ? ' has-error' : '' }}">
							    {!! Form::label('position', '置顶位置') !!}
							    {!! Form::select('position', ['视频列表'=>'视频列表'], null, ['id' => 'position', 'class' => 'form-control', 'required' => 'required']) !!}
							    <small class="text-danger">{{ $errors->first('position') }}</small>
							</div>

						    <div class="form-group pull-right">
						        {!! Form::submit("置顶", ['class' => 'btn btn-success']) !!}
						    </div>						
						{!! Form::close() !!}
					</div>

					<div class="col-md-6">
						<div class="list-group">
							@foreach($videos as $video)
								<div class="list-group-item">
									{!! Form::open(['method' => 'POST', 'route' => 'admin.delete_stick_video', 'class' => 'form-horizontal']) !!}
									
									    {!! Form::hidden('video_id',  $video->id) !!}
									
									    <div class="btn-group pull-right">						        
									        {!! Form::submit("删除", ['class' => 'btn btn-sm btn-danger']) !!}
									    </div>
									
									{!! Form::close() !!}
									<h4 class="list-group-item-heading">[{{ $video->position }}][{{ $video->expire }} days]{{  $video->title }}</h4>
									<p class="list-group-item-text">
										@if(!empty($video->reason))
											<span class="label label-success">新收录</span>
										@endif
										{!! $video->article->link() !!} ({{ $video->stick_time }})</p>
								</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop