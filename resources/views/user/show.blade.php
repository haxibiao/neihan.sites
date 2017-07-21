@extends('layouts.app')

@section('content')
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                {{ $user->name }}
            </h3>
        </div>
        <div class="panel-body">
            <img alt="" class="img img-circle" src="{{ get_avatar($user) }}">
                <h4>
                    {{ $user->name }}
                </h4>
                <p>
                    {{ $user->email }}
                </p>
                <p>
                	{{ $user->introduction }}
                </p>
            </img>
        </div>
    </div>

    <div class="panel panel-default">
    	<div class="panel-heading">
    		<h3 class="panel-title">文章({{ $data['total'] }})</h3>
    	</div>
    	<div class="panel-body">
    		@foreach($articles as $article)
				@include('article.parts.article_item')
    		@endforeach
            <p>
                {!! $articles->render() !!}
            </p>
    	</div>
    </div>
</div>
@endsection
