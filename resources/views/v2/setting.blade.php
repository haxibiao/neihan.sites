@extends('v2.layouts.app')

@section('title')
	设置 - 爱你城
@stop
@section('content')
<div id="setting">
    <div class="container">
        <div class="setting_wrp">
            <div class="aside">
                <setting-left>
                </setting-left>
            </div>
            <div class="main">
                <router-view>
                </router-view>
            </div>
        </div>
    </div>
</div>
@stop
