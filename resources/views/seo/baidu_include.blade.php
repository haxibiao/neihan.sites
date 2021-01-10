@extends('layouts.app') 
@section('title') 百度收录查询结果 @stop 

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">今天</h3>
        </div>
    
        <div class="panel-body">
          <table class="table">
            <tr>
              <td>域名</td>
              <td>已收录</td>
              <td>索引量</td>
            </tr>
            @foreach($today as $item)
            <tr>
              <td>{{ $item["url"] }}</td>
              <td>{{ $item['收录'] > 0 ? '是':'' }}</td>
              @if($item['收录'])
                @if($item['up'] < 0)
                  <td>
                    <a target="_blank" href="https://www.baidu.com/s?wd=site:{{ $item["url"] }}" style="color:blue">{{ $item['收录'] }}</a>
                  </td>
                @elseif($item['up'] > 0)
                  <td>
                    <a target="_blank" href="https://www.baidu.com/s?wd=site:{{ $item["url"] }}" style="color:red">{{ $item['收录'] }}</a>
                  </td>
                @else
                  <td>
                    <a target="_blank" href="https://www.baidu.com/s?wd=site:{{ $item["url"] }}">{{ $item['收录'] }}</a>
                  </td>
                @endif
              @else
                <td>{{ $item['收录'] }}</td>
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
          <h3 class="panel-title">昨天</h3>
        </div>
    
        <div class="panel-body">
          <table class="table">
            <tr>
              <td>域名</td>
              <td>已收录</td>
              <td>索引量</td>
            </tr>
            @foreach($yesterday as $item)
            <tr>
              <td>{{ $item["url"] }}</td>
              <td>{{ $item['收录'] > 0 ? '是':'' }}</td>
              @if($item['收录'])
                @if($item['up'] < 0)
                  <td>
                    <a target="_blank" href="https://www.baidu.com/s?wd=site:{{ $item["url"] }}" style="color:blue">{{ $item['收录'] }}</a>
                  </td>
                @elseif($item['up'] > 0)
                  <td>
                    <a target="_blank" href="https://www.baidu.com/s?wd=site:{{ $item["url"] }}" style="color:red">{{ $item['收录'] }}</a>
                  </td>
                @else
                  <td>
                    <a target="_blank" href="https://www.baidu.com/s?wd=site:{{ $item["url"] }}" style="color:black">{{ $item['收录'] }}</a>
                  </td>
                @endif
              @else
                <td>{{ $item['收录'] }}</td>
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
          <h3 class="panel-title">前天</h3>
        </div>
        <div class="panel-body">
          <table class="table">
            <tr>
              <td>域名</td>
              <td>已收录</td>
              <td>索引量</td>
            </tr>
            @foreach($third as $item)
            <tr>
              <td>{{ $item["url"] }}</td>
              <td>{{ $item['收录'] > 0 ? '是':'' }}</td>
              @if($item['收录'])
                <td>
                  <a target="_blank" href="https://www.baidu.com/s?wd=site:{{ $item["url"] }}" style="color:black">{{ $item['收录'] }}</a>
                </td>
              @else
                <td>{{ $item['收录'] }}</td>
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
