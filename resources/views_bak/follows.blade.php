@extends('layouts.app')

@section('title')
    关注 - 爱你城
@stop
@section('content')
<div id="follow">
    <div class="container">
        <div class="follow_wrp clearfix">
            <div class="aside">
                <follow-left>
                </follow-left>
            </div>
            <div class="main">
                <router-view>
                </router-view>
            </div>
        </div>
    </div>
</div>
@stop