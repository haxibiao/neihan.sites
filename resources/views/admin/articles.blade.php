@extends('layouts.app')

@section('content')
<div class="container">
    <ol class="breadcrumb">
        <li>
            <a href="/admin">
                后台管理
            </a>
        </li>
        <li class="active">
            文章批量管理
        </li>
    </ol>
    <div class="panel panel-default">
        <table class="table">
            <tr>
                <th class="text-center">
                    <input type="checkbox" id="checks"/>
                </th>
                <th class="text-center">
                    id
                </th>
                <th class="text-center">
                    title
                </th>
                <th class="text-center">
                    type
                </th>
                <th class="text-center">
                    status
                </th>
            </tr>
            @foreach($data['articles'] as $article)
            <tr>
                <td class="text-center">
                    <input type="checkbox" value="{{ $article->id }}" class="checkboxs"/>
                </td>
                <td class="text-center">
                    {{ $article->id }}
                </td>
                <td class="text-center">
                    <a href="/article/{{ $article->id }}" style="color:#000" target="_blank">
                        {{ $article->get_title() }}
                    </a>
                </td>
                @php
                    $css = 'label-primary';
                    $type = '文章';
                    if($article->type == 'video'){
                        $css = 'label-success';
                        $type = '视频';
                    }
                    if($article->type == 'post'){
                        $css = 'label-info';
                        $type = '动态';
                    } 
                @endphp
                <td class="text-center">
                    <span class="label {{ $css }}">
                        {{ $type }}
                    </span>
                </td>
                <td class="text-center">
                    {{ $article->status }}
                </td>
            </tr>
            @endforeach
        </table>
        <div class="btn-group col-md-offset-4">
            <button type="button" class="btn btn-success" behavior="changeCategory">更改主专题</button>
            <button type="button" class="btn btn-primary" behavior="addCategory">收录到新专题</button>
            <button type="button" class="btn btn-danger"  behavior="deleteArticles">软删除</button>
            <button type="button" class="btn btn-warning" behavior="sendArctiles">恢复</button>
        </div>
        {{ $data['articles']->links() }}
    </div>
    <div class="modal fade" id="modalPost" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">标题</h4>
                </div>
                {!! Form::open(['url'=>'/admin/articles','method'=>'post']) !!}
                <div class="modal-body" style="padding:15px">
                    <p id="tip-message"></p>
                    <input type="hidden" name="article_ids">
                    <input type="text" class="form-control" name="category" placeholder="专题名称">
                    <input type="hidden" name="type">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary">提交</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@stop

@push('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        //全选/反选操作
        var checks = $('#checks');
        checks.click(function(){
            var checkBoxs = $('.checkboxs');
            var checked = true;
            if(!checks.is(':checked')){
                checked = false;
            }
            checkBoxs.each(function(){
                $(this).prop('checked',checked);
            })
        })

        //唤醒模态框
        var buttons = $('.btn-group button');
        buttons.each(function(){
            $(this).click(function(){
                var type = $(this).attr('behavior');
                $("input[name='type']").val(type);
                //articles id
                var articles = [];
                $('.checkboxs').each(function(){
                    if($(this).is(':checked')){
                        articles.push($(this).val());
                    }
                });

                if(type == 'deleteArticles' || type == 'sendArctiles'){
                    $('input[name="category"]').hide();
                    var message = type == 'deleteArticles' ? '删除' : '恢复';
                    $('#tip-message').text('你将要'+message+articles.length+'篇作品');
                }else{
                    $('input[name="category"]').show();
                    $('#tip-message').text('你选择了'+articles.length+'篇作品');
                }

                if(articles.length < 1){
                    return alert('请先选择作品');
                }
                //放入隐藏表单域
                $("input[name='article_ids']").val(articles);

                //modalPost show 
                $('#modalPost #myModalLabel').text($(this).text());
                $('#modalPost').modal('show');
            })
        })
    });
</script>
@endpush
