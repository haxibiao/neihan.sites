<div class="aside">
	<div>
		<ul class="aside-menu">
		  <li>
		    <a href="/search{{ request('q') ? '?q='.request('q') : '' }}"><div class="icon-wrp"><i class="iconfont icon-icon_article"></i></div> <span>文章</span></a>
		  </li>
		  <li>
		    <a href="/search/video{{ request('q') ? '?q='.request('q') : '' }}"><div class="icon-wrp"><i class="iconfont icon-shipin"></i></div> <span>视频</span></a>
		  </li>
		  <li>
		    <a href="/search/users{{ request('q') ? '?q='.request('q') : '' }}"><div class="icon-wrp"><i class="iconfont icon-yonghu01"></i></div> <span>用户</span></a>
		  </li> 
		  <li>
		    <a href="/search/categories{{ request('q') ? '?q='.request('q') : '' }}"><div class="icon-wrp"><i class="iconfont icon-zhuanti1"></i></div> <span>专题</span></a>
		  </li>
		  <li>
		    <a href="/search/collections{{ request('q') ? '?q='.request('q') : '' }}"><div class="icon-wrp"><i class="iconfont icon-wenji"></i></div> <span>文集</span></a>
		  </li>
		</ul>
	</div>

	<hot-search class="hidden-xs"></hot-search>

	{{-- 未登录用户不显示最近搜索管理 --}}
	@if(Auth::check())
		<recently class="hidden-xs"></recently>
	@endif
</div>