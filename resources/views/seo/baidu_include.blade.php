@extends('layouts.app') 
@section('title') 百度收录查询结果 @stop 

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">备案站收录查询(缓存1天)</h3>
        </div>
    
        <div class="panel-body">
          <table class="table">
            <tr>
              <td>域名</td>
              <td>已收录</td>
              <td>索引量</td>
            </tr>
            @foreach($beian as $item)
            <tr>
              <td>{{ $item["url"] }}</td>
              <td>{{ $item['收录']??0 > 0 ? '是':'' }}</td>
              @if($item['收录'] ?? 0)
                <td>
                  <a target="_blank" href="https://www.baidu.com/s?wd=site:{{ $item["url"] }}" style="color:blue">{{ $item['收录']??0 }}</a>
                </td>
              @else
                <td>{{ $item['收录']??0 }}</td>
              @endif
            </tr>
            @endforeach
          </table>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">内涵站收录查询(缓存1天)</h3>
        </div>
    
        <div class="panel-body">
          <table class="table">
            <tr>
              <td>域名</td>
              <td>已收录</td>
              <td>索引量</td>
            </tr>
            @foreach($neihan as $item)
            <tr>
              <td>{{ $item["url"] }}</td>
              <td>{{ $item['收录']??0 > 0 ? '是':'' }}</td>
              @if($item['收录'] ?? 0)
                <td>
                  <a target="_blank" href="https://www.baidu.com/s?wd=site:{{ $item["url"] }}" style="color:blue">{{ $item['收录']??0 }}</a>
                </td>
              @else
                <td>{{ $item['收录']??0 }}</td>
              @endif
            </tr>
            @endforeach
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
