@extends('layouts.app')

@section('title') {{ $collection->user->name }}的{{ $collection->name }}  @endsection
@section('keywords') {{ $collection->user->name }}的{{ $collection->name }} @endsection
@section('description') {{ $collection->user->name }}的{{ $collection->name }} @endsection

@section('content')
	<div id="collection">
		<div class="clearfix">
			<div class="main sm-left">
				{{-- 信息 --}}
				@include('collection.parts.information')
				{{-- 内容 --}}
				<div class="content">
					<!-- Nav tabs -->
					 <ul id="trigger-menu" class="nav nav-tabs" role="tablist">
					   <li role="presentation" class="active">
					   	<a href="#article" aria-controls="article" role="tab" data-toggle="tab"><i class="iconfont icon-wenji"></i>最新发表</a>
					   </li>
					   <li role="presentation">
					   	<a href="#comment" aria-controls="comment" role="tab" data-toggle="tab"><i class="iconfont icon-svg37"></i>最新评论</a>
					   </li>
					   <li role="presentation">
					   	<a href="#catalog" aria-controls="catalog" role="tab" data-toggle="tab"><i class="iconfont icon-duoxuan"></i>目录</a>
					   </li>
					 </ul>
					 <!-- Tab panes -->
					 <div class="article_list tab-content">
					   <ul role="tabpanel" class="fade in tab-pane active" id="article">
			 					@each('parts.article_item', $data['new'], 'article')
					   </ul>
					   <ul role="tabpanel" class="fade tab-pane" id="comment">
	   						@each('parts.article_item', $data['commented'], 'article')
					   </ul>
					   <ul role="tabpanel" class="fade tab-pane" id="catalog">
					   		@each('parts.article_item', $data['old'], 'article')
					   </ul>
					 </div>
				</div>
			</div>
			<div class="aside sm-right hidden-xs">
				<div class="share distance">
					<span>分享到</span>
					<a data-action="weixin-share"><i class="weibo iconfont icon-weixin1"></i></a>
					<a href="javascript:void((function(s,d,e,r,l,p,t,z,c){var%20f='http://v.t.sina.com.cn/share/share.php?appkey=1881139527',u=z||d.location,p=['&amp;url=',e(u),'&amp;title=',e(t||d.title),'&amp;source=',e(r),'&amp;sourceUrl=',e(l),'&amp;content=',c||'gb2312','&amp;pic=',e(p||'')].join('');function%20a(){if(!window.open([f,p].join(''),'mb',['toolbar=0,status=0,resizable=1,width=440,height=430,left=',(s.width-440)/2,',top=',(s.height-430)/2].join('')))u.href=[f,p].join('');};if(/Firefox/.test(navigator.userAgent))setTimeout(a,0);else%20a();})(screen,document,encodeURIComponent,'','','', '推荐 @ {{$collection->user->name}} 的文章《{{$collection->name}}》（ 分享自 @爱你城 ）','{{ url('/collection/'.$collection->id) }}?source=weibo','页面编码gb2312|utf-8默认gb2312'));"><i class="weixin iconfont icon-sina"></i></a>
				</div>
				<div class="administrator">
					<p class="plate-title">文集作者</p>
					<ul>
						<li><a href="/user/{{ $collection->user->id }}" class="avatar"><img src="{{ $collection->user->avatar() }}" alt=""></a><a href="/user/{{ $collection->user->id }}" class="name">{{ $collection->user->name }}</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('modals')
  {{-- 分享到微信 --}}
  <modal-share-wx url="{{ url()->full() }}" aid="{{ $collection->id }}"></modal-share-wx>
@endpush