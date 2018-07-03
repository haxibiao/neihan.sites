@extends('layouts.app')

@section('content')
<div class="container">
    <div class="jumbotron">
        <div class="container">
            <h1>
                出错了
            </h1>
            <p class="lead">
                @if(!empty($exception))
                    {{ $exception->getMessage() }}
                @endif
            </p>
            <p class="well">
                服务器遇到了一点搞不定的事情，抱歉... 我们已经记录下来，马上就有工程师来修复....
            </p>
            <p>
                <a class="btn btn-primary btn-lg" href="{{ url()->previous() }}">
                    返回
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
