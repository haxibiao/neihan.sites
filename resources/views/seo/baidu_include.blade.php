@extends('layouts.app') 
@section('title') 百度收录查询结果 @stop 

@section('content')
<div class="container">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">百度收录查询结果(缓存1天)</h3>
    </div>

    <div class="panel-body">
      <table class="table">
        <tr>
          <td>域名</td>
          <td>索引量</td>
        </tr>
        @foreach($items as $item)
        <tr>
          <td>{{ $item["url"] }}</td>
          @if($item['收录']??0)
            <td style="color:blue">{{ $item['收录']??0 }}</td>
          @else
            <td>{{ $item['收录']??0 }}</td>
          @endif
        </tr>
        @endforeach
      </table>
    </div>
  </div>
</div>
@stop
