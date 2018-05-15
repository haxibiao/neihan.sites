@extends('layouts.app')

@section('content')
	<div id="notification">
		<section class="left-aside clearfix">
			<notification-aside></notification-aside>
			<div class="main">
				<router-view></router-view>
			</div>
		</section>
	</div>
@endsection