@extends('layouts.app')

@section('content')
<div class="container">
	<div class="panel panel-success">
		<div class="panel-heading">
			<h3 class="panel-title">{{ $user->name }}</h3>
		</div>
		<div class="panel-body">			
			<p>{{ $user->email }}</p>
		</div>
	</div>	
</div>
@endsection