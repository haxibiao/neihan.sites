@extends('layouts.app')

@section('title')
	视频列表
@stop

@section('content')
<div class="container">
      <ol class="breadcrumb">
        <li><a href="/">{{ seo_site_name() }}</a></li>
        <li class="active">视频</li>
      </ol>
    <div class="panel panel-default">
        <div class="panel-heading">
            @if(checkEditor())
            <div class="pull-right">
                <a class="btn btn-primary" data-target=".modal-post" data-toggle="modal" role="button">
                    添加视频
                </a>
            </div>
            @endif
            <div class="pull-left header-checkbox">
                <input type="checkbox" id="checks"/>
            </div>
            <h3 class="panel-title" style="line-height: 30px">
                视频列表
                <basic-search api="/video/list?q="></basic-search>
            </h3>
        </div>
        <div class="panel-body">
            @foreach($data['videos'] as $article)
                @php
                    $video = $article->video;
                     
                @endphp
                @if( !empty($video) )
                    <div class="media">
                        <div class="text-center pull-left">
                            <input type="checkbox" value="{{ $article->id }}" class="checkboxs"/>
                        </div>
                        <a class="pull-left" href="/video/{{ $video->id }}">
                            <img alt="{{ $article->subject }}" class="img img-thumbnail img-responsive"
                                src="{{ $article->cover }}" style="max-width: 300px">
                            </img>
                        </a>
                        <div class="media-body">
                            @if(checkEditor())
                            <div class="pull-right">
                              {!! Form::open(['method' => 'PUT', 'route' => ['video.update', $video->id], 'class' => 'form-horizontal pull-left']) !!}
                                @if($article->status == 0)
                                {!! Form::hidden('status', 1) !!}
                                {!! Form::submit('上架', ['class' => 'btn btn-success btn-small']) !!}
                                @else
                                {!! Form::hidden('status', 0) !!}
                                {!! Form::submit('下架', ['class' => 'btn btn-default btn-small']) !!}
                                @endif
                              {!! Form::close() !!}
                                <a class="btn btn-primary btn-small" href="/video/{{ $video->id }}/edit" role="button" style="margin-left: 5px">
                                    编辑
                                </a>
                            </div>
                            @endif
                            <h4 class="media-heading">
                                <a href="/article/{{ $article->id }}">
                                {{ $article->subject }}
                                </a>
                            </h4>
                            <p>
                                主分类: {{ !empty($article->category) ? $article->category->name : '暂无(该视频未发布)' }}
                            </p>
                            @if($video->user)
                            <p>
                                上传用户:　<a href="/user/{{ $video->user_id }}">{{ $video->user->name }}</a>
                            </p>
                            @endif
                            <p>
                                最后更新: {{ $video->updatedAt() }}
                            </p>
                            <p>
                                @if(!empty($article->covers) && count($article->covers) >= 8)
                                    <span class="label label-success">截图已完成</span>
                                @else
                                    <span class="label label-default">截图ing...</span>
                                @endif

                                @if($article->status == 0)
                                <span class="label label-info">已下架</span>
                                @endif
                            </p>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        <div class="btn-group col-md-offset-4">
            <button type="button" class="btn btn-success" behavior="changeCategory">更改主专题</button>
            <button type="button" class="btn btn-primary" behavior="addCategory">收录到新专题</button>
            <button type="button" class="btn btn-danger"  behavior="deleteArticles">软删除</button>
            <button type="button" class="btn btn-warning" behavior="sendArctiles">恢复</button>
        </div>
        <p>
            {{ $data['videos']->appends(['q'=>$data['keywords']])->render() }}
        </p>
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
                console.log("articles",articles);
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
