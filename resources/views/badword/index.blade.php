@extends('layouts.app')

@section('title')
  屏蔽词列表
@stop

@section('content')
<div class="container">
    <div class="col-md 12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    所有屏蔽词
                </h3>
            </div>
            <div class="panel-body">
                @foreach($badwords as $badword)
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>
                                <a href="/badword/{{ $badword->id }}">
                                    {{ $badword->word }}
                                </a>

                                 <div class="pull-right">
                                      创建人:{{ get_user_name($badword->user_id) }}
                                 </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                创建时间：{{ $badword->created_at }}

                                {!! Form::open(['method' => 'DELETE', 'route' => ['badword.destroy',$badword->id], 'class' => 'form-horizontal']) !!}
                                    <div class="btn-group pull-right">
                                        {!! Form::submit("删除", ['class' => 'btn btn-danger']) !!}
                                    </div>
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    </tbody>
                </table>
                @endforeach
            </div>
            {{ $badwords->render() }}
        </div>
    </div>
</div>
@stop
