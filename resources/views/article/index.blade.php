@extends('layouts.app')

@section('title')
    管理文章
@endsection
@section('keywords')
  
@endsection
@section('description')
  
@endsection

@section('content')

<div class="container">
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-left header-checkbox">
        <input type="checkbox" id="checks"/>
      </div>
      <h3 style="margin:0;display:inline-block">管理文章</h3>
      <basic-search api="/article?q="></basic-search>
    </div>
    
    <div class="panel-body">
      @foreach($data['articles'] as $article)
      <div class="media">
        @if($article->cover)
        <div class="text-center pull-left">
            <input type="checkbox" value="{{ $article->id }}" class="checkboxs"/>
        </div>
        <a class="pull-left" href="/article/{{ $article->id }}">
            <img class="media-object" src="{{ $article->cover }}" alt="{{ $article->subject }}" style="max-width: 200px">
        </a>
        @endif
        <div class="media-body">
          <a href="/article/{{ $article->id }}">
            <h4 class="media-heading">{{ $article->subject }}</h4>
          </a>
          <p>{{ $article->summary }}</p>

          <div class="pull-right">
            <a class="btn btn-sm btn-primary" href="/article/{{ $article->id }}/edit" role="button"  >编辑</a> 
            <br/>
            <br/>
            {!! Form::open(['method' => 'delete', 'route' => ['article.destroy', $article->id], 'class' => 'form-horizontal']) !!}
            {!! Form::submit('删除', ['class' => 'btn btn-sm btn-danger']) !!}                
            {!! Form::close() !!}
          </div>
          
        </div>
      </div>
      @endforeach
      <div class="btn-group col-md-offset-4">
          <button type="button" class="btn btn-success" behavior="changeCategory">更改主专题</button>
          <button type="button" class="btn btn-primary" behavior="addCategory">收录到新专题</button>
          <button type="button" class="btn btn-danger"  behavior="deleteArticles">软删除</button>
          <button type="button" class="btn btn-warning" behavior="sendArctiles">恢复</button>
      </div>
      <p>
        {{ $data['articles']->appends(['q'=>$data['keywords']])->render() }}
      </p>
    </div>
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

@endsection

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