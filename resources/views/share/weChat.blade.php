@extends('layouts.app')

@section('title')

@stop

@section('content')
<div class="container">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title text-center">
                请使用微信扫描下方的二维码,在打开的链接右上角点击分享
            </h3>
        </div>
        <div class="panel-body text-center">
            <img src="{{ $data['share_file_dir'] }}">
            </img>
        </div>
    </div>
</div>
@stop
