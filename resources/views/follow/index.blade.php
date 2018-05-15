@extends('layouts.app')

@section('content')
	<div id="follow">
		<section class="left-aside clearfix">
			<follow-aside></follow-aside>
			<div class="main">
				<router-view></router-view>
			</div>
		</section>
	</div>

	<!-- Modal -->
	<modal-contribute></modal-contribute>
@endsection