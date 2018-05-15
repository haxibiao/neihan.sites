@extends('layouts.app')

@section('content')
	<div id="setting">
		<section class="left-aside clearfix">
			<setting-aside></setting-aside>
			<div class="main">
				<router-view></router-view>
			</div>
		</section>
	</div>
@endsection