@extends('layouts.app')

@section('title')
	搜索 - {{ $query }} - 爱你城
@stop
@section('content')
<div id="search">
    <div class="container">
        <div class="search_wrp">
            <div class="aside">
                <search-left query="{{ $query }}">
                </search-left>
            </div>
            <div class="main">
                <router-view>
                </router-view>
            </div>
        </div>
    </div>
</div>
@stop