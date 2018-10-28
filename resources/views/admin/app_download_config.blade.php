@extends('layouts.app')

@section('content')
<div class="container">
        <ol class="breadcrumb">
            <li>
                <a href="/admin">后台管理</a>
            </li>
            <li class="active">APP下载页设置</li>
        </ol>
    <div class="panel panel-default download-config">
        <div class="panel-heading">
            <h3 class="panel-title">
                APP下载页设置  (点击图片选择本地图片或输入图片网络地址)
            </h3>
        </div>
        <div class="panel-body">
            {!! Form::open(['method'=>'post', 'url'=>'/admin/app-download-config-save']) !!}
            <div class="col-md-12">
                <div class="row">
                    <label class="form-inline">
                        <div >LOGO地址:</div>
                    {{--     <input type="text" class="form-control" id="textMark" placeholder="请输入logo地址" name="logo" value="{{ isset($data['logo']) ? $data['logo'] : '' }}">
                        <img src="{{ isset($data['logo']) ? $data['logo'] : '//'.env('APP_DOMAIN').'/logo/'.env('APP_DOMAIN').'.small.png' }}" width="60" height="60" id="previewImage">
                        <input type="file" id="inputLogo">
                        {{$data['logo']}} --}}
                         <upload-image text-value="{{"logoInfo"}}"  image-value="{{"logoImage"}}" text-name="{{"logo"}}" data-image="{{isset($data['logo']) ? $data['logo'] : '//'.env('APP_DOMAIN').'/logo/'.env('APP_DOMAIN').'.small.png' }}"> </upload-image>
                    </label>
                </div>
                <div class="row">
                    <label class="form-inline" >
                        <div >H1 标题设置:</div>
                        <textarea class="form-control" name="h1_title">{{ isset($data['h1_title']) ? $data['h1_title'] : '' }}</textarea>
                    </label>
                </div>
                <div class="row">
                    <label class="form-inline">
                        <div>H1 小标语设置:</div>
                        <textarea class="form-control" name="h1_slogan">{{ isset($data['h1_slogan']) ? $data['h1_slogan'] : '' }}</textarea>
                    </label>
                </div>
                <div class="row">
                    <label class="form-inline">
                        <div>二维码地址:</div>
                         <upload-image text-value="{{"qrcodeInfo"}}"  image-value="{{"qrcodeImage"}}" text-name="{{"qrcode"}}" data-image="{{isset($data['qrcode']) ? $data['qrcode'] : '//'.env('APP_DOMAIN').'/logo/'.env('APP_DOMAIN').'.small.png' }}"> </upload-image>
                    </label>
                </div>
                <div class="row">
                    <label class="form-inline">
                        <div>二维码 小标题设置:</div>
                        <textarea class="form-control" name="qrcode_title">{{ isset($data['qrcode_title']) ? $data['qrcode_title'] : '' }}</textarea>
                    </label>
                </div>
                <div class="row">
                    <label class="form-inline">
                        <div>二维码 小标语设置:</div>
                        <textarea class="form-control" name="qrcode_slogan">{{ isset($data['qrcode_slogan']) ? $data['qrcode_slogan'] : '' }}</textarea>
                    </label>
                </div>
                <div class="row">
                    <label class="form-inline">
                        <div>主展示图地址:</div>
                         <upload-image text-value="{{"images1Info"}}"  image-value="{{"images1Image"}}" text-name="{{"show_images1"}}" data-image="{{ isset($data['show_images1']) ? $data['show_images1'] : '//'.env('APP_DOMAIN').'/logo/'.env('APP_DOMAIN').'.small.png' }}"> </upload-image>
                    </label>
                </div>
                <div class="row">
                    <label class="form-inline">
                        <div>中部 标题:</div>
                        <textarea class="form-control" name="center_title">{{ isset($data['center_title']) ? $data['center_title'] : '' }}</textarea>
                    </label>
                </div>
                <div class="row">
                    <label class="form-inline">
                        <div>中部 标语:</div>
                        <textarea class="form-control" name="center_slogan">{{ isset($data['center_slogan']) ? $data['center_slogan'] : '' }}</textarea>
                    </label>
                </div>
                <div class="row">
                    <label class="form-inline">
                        <div>展示图二地址:</div>
                        <upload-image text-value="{{"images2Info"}}"  image-value="{{"images2Image"}}" text-name="{{"show_images2"}}" data-image="{{isset($data['show_images2']) ? $data['show_images2']  : '//'.env('APP_DOMAIN').'/logo/'.env('APP_DOMAIN').'.small.png'}}"> </upload-image>
                    </label>
                </div>
                <div class="row">
                    <label class="form-inline">
                        <div>展示图二 小标题:</div>
                        <textarea class="form-control" name="show_images2_title">{{ isset($data['show_images2_title']) ? $data['show_images2_title'] : '' }}</textarea>
                    </label>
                </div>
                <div class="row">
                    <label class="form-inline">
                        <div>展示图二 小标语:</div>
                        <textarea class="form-control" name="show_images2_slogan">{{ isset($data['show_images2_slogan']) ? $data['show_images2_slogan'] : '' }}</textarea>
                    </label>
                </div>
                <div class="row">
                    <label class="form-inline">
                        <div>展示图三地址:</div>
                        <upload-image text-value="{{"images3Info"}}"  image-value="{{"images3Image"}}" text-name="{{"show_images3"}}" data-image="{{ isset($data['show_images3']) ? $data['show_images2']  : '//'.env('APP_DOMAIN').'/logo/'.env('APP_DOMAIN').'.small.png'}}"> </upload-image>
                    </label>
                </div>
                <div class="row">
                    <label class="form-inline">
                        <div>展示图三 小标题:</div>
                        <textarea class="form-control" name="show_images3_title">{{ isset($data['show_images3_title']) ? $data['show_images3_title'] : '' }}</textarea>
                    </label>
                </div>
                <div class="row">
                    <label class="form-inline">
                        <div>展示图三 小标语:</div>
                        <textarea class="form-control" name="show_images3_slogan">{{ isset($data['show_images3_slogan']) ? $data['show_images3_slogan'] : '' }}</textarea>
                    </label>
                </div>
                <div class="row">
                    <label class="form-inline">
                        <div>展示图四地址:</div>
                        <upload-image text-value="{{"images4Info"}}"  image-value="{{"images4Image"}}" text-name="{{"show_images4"}}" data-image="{{isset($data['show_images4']) ? $data['show_images4']  : '//'.env('APP_DOMAIN').'/logo/'.env('APP_DOMAIN').'.small.png'}}"> </upload-image>
                    </label>
                </div>
                <div class="row">
                    <label class="form-inline">
                        <div>展示图四 小标题:</div>
                        <textarea class="form-control" name="show_images4_title">{{ isset($data['show_images4_title']) ? $data['show_images4_title'] : '' }}</textarea>
                    </label>
                </div>
                <div class="row">
                    <label class="form-inline">
                        <div>展示图四 小标语:</div>
                        <textarea class="form-control" name="show_images4_slogan">{{ isset($data['show_images4_slogan']) ? $data['show_images4_slogan'] : '' }}</textarea>
                    </label>
                </div>
                <div class="row">
                    <label class="form-inline">
                        <div></div>
                        <button type="submit" class="btn btn-success">保  存</button>
                    </label>
                </div>            
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop
