@extends('layouts.app')

@section('title'){{ $user->name }}-{{ env('APP_NAME') }}@endsection

@section('content')
	<div id="user">
		<div class="clearfix">
			<div class="main sm-left">
				{{-- 用户信息 --}}
				@include('user.parts.information')
				{{-- 内容 --}}
				{{-- 关注、粉丝 --}}
				<div class="content">
					<!-- Nav tabs -->
					 <ul id="trigger-menu" class="nav nav-tabs" role="tablist">
					   <li role="presentation" {!! ends_with(request()->path(), 'followings') ? 'class="active"' : '' !!}>
					   	<a href="#following" aria-controls="following" role="tab" data-toggle="tab">
					   		关注用户 {{ $data['follows']->total() }}
					   	</a>
					   </li>
					   <li role="presentation" >
					   	<a href="#followers" aria-controls="followers" role="tab" data-toggle="tab">
					   		粉丝 {{ $data['followers']->total() }}
					   	</a>
					   </li>
					 </ul>
					 <!-- Tab panes -->
					 <div class="article-list tab-content">
					   <ul role="tabpanel" class="fade in active  user-list follow-user-list tab-pane" id="following">
					   		@foreach($data['follows'] as $follow)					   			
					   			@include('user.parts.follow_item', ['user'=>$follow->followed])
					   		@endforeach
					   		<follow-list api="/api/user/{{ $user->id }}/follow?followings=1" start-page="2" not-empty="{{count($data['follows'])>0}}">
					   		</follow-list>
					   </ul>
					   <ul role="tabpanel" class="fade user-list follow-user-list tab-pane" id="followers">
					   		@foreach($data['followers'] as $follow)
					   			@include('user.parts.follow_item', ['user'=>$follow->user])
					   		@endforeach
					   		<follow-list api="/api/user/{{ $user->id }}/follow?followers=1" start-page="2" not-empty="{{count($data['followers'])>0}}">
					   		</follow-list>
					   </ul>
					 </div>
				</div> 
			</div>
			{{-- 侧栏 --}}
			@include('user.parts.aside') 
		</div>
	</div> 
@endsection

@push('scripts')
	<script type="text/javascript">
	  $(function(){
	    var url = window.location.href;
	    if( url.includes("followers") ){
	    	$("[href='#followers']").click();
	    }
	  });
	</script>
@endpush
