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
				<div class="media">
					<a class="pull-left" href="/article/{{ $article->id }}">
						<img class="media-object" src="{{ get_small_article_image($article->image_url) }}" alt="{{ $article->title }}" style="max-width: 200px">
					</a>
					<div class="media-body">
						<a href="/article/{{ $article->id }}">
							<h4 class="media-heading">{{ $article->title }}</h4>
						</a>
						<p>{{ $article->description }}</p>
					</div>
				</div>
    		@endforeach
            <p>
                {!! $articles->render() !!}
            </p>
    	</div>
    </div>
</div>
@endsection
