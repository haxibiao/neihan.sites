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
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $querys->render() }}
</div>
@stop
