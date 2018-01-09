@extends('layouts.app')

@section('title')
   所有被搜索的关键词
@stop

@section('content')
<div class="container">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>
                    关键词
                </th>
                <th>
                    搜索次数
                </th>
                <th>
                    创建时间
                </th>
                <th>
                    最近被搜索的时间
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($querys as $query)
            <tr>
                <td>
                    {{ $query->query }}
                </td>
                <td>
                    {{ $query->hits }}
                </td>
                <td>
                    {{ $query->created_at }}
                </td>
                <td>
                    {{ $query->updated_at }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $querys->render() }}
    <ul class="list-group">
        <li class="list-group-item">
            最近被搜索的十个关键词
        </li>
        @foreach($data['update'] as $up)
        <li class="list-group-item">
            {{ $up->query }}
            <div class="pull-right">
                最后一次搜索时间:{{ $up->updated_at }}
            </div>
        </li>
        @endforeach
        {{ $data['update']->render() }}
    </ul>
</div>
@stop
