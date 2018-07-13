@extends('layouts.app')

@section('title'){{ $question->title }}-{{ env('APP_NAME') }}问答@stop
@section('description') {{config('seo.'.get_domain_key().'.description')  }} @stop
@section('keywords') {{ config('seo.'.get_domain_key().'.keywords') }} @stop

@push('seo_metatags')
<meta property="og:title" content="{{ $question->title }}" />
@endpush

@section('content')
 <div id="answer">
  <div class="clearfix">
     <div class="main sm-left">
       <div class="question-tags">
   		   @foreach($question->categories as $category)
         <a href="/question?cid={{ $category->id }}" target="_blank" class="tags tag"><span class="name">{{ $category->name }}</span></a>
   		   @endforeach
  	   </div>
  	   <h1 class="question-name">
  	      {{ $question->title }}
  	   </h1>
       @if($question->isPay())
          @if(!$question->closed)
            @if($question->deadline)
              <div class="question-tip">
                  <p>如果未在{{ $question->leftHours() }}小时内选中回答，前10位抢答者平分赏金</p>
              </div>
            @else
              <div class="question-tip">
                  <p>本问题提问者无限制时间...</p>
              </div>
            @endif
          @else
          <div class="question-tip">
              已选择有价值的回答，并分发了奖金
          </div>
          @endif
       @endif
       <div class="inquisitorial">
         <div class="author">
          @if(!$question->is_anonymous)
          <a target="_blank" href="/user/{{ $question->user->id }}" class="avatar"><img src="{{ $question->user->avatar() }}" alt=""></a>
          @endif
          <div class="info">
            @if($question->is_anonymous)
              匿名用户
            @else
              <a target="_blank" href="/user/{{ $question->user->id }}" class="nickname">{{ $question->user->name }}</a>
            @endif
            <span class="time">{{ $question->timeAgo() }}</span></div>
            @if($question->isPay())
              <span class="amount pull-right">赏金<i class="iconfont icon-jinqian1"></i>{{ $question->bonus }}元</span>
            @endif
         </div>
       </div>
  	   <div class="question-text">
          {!! $question->background !!}
       </div>
  	   <div class="question-img-preview">
        @if(!empty($question->image1))
  	   	  <img src="{{ $question->image1() }}">
        @endif
        @if(!empty($question->image2))
  	   	  <img src="{{ $question->image2() }}">
        @endif
        @if(!empty($question->image3))
  	   	  <img src="{{ $question->image3() }}">
        @endif
  	   </div>

      @if(Auth::check() && !$question->closed)
        <answer-tool
        question-id="{{ $question->id }}"
        is-self="{{ $question->isSelf() }}"
        url="{{ url('/question/'.$question->id) }}"
        author="{{ $question->user->name }}"
        title="{{ $question->title }}"></answer-tool>

      {!! Form::open(['method' => 'POST', 'route' => $question->isSelf() ? 'question.add':'answer.store', 'class' => 'form-horizontal']) !!}

        @if(!$question->isSelf())
          @editor
          <div style="padding-bottom: 10px">
            {!! Form::label('article_id', '最佳回答文章') !!}
            <span class="label label-default">(可选，编辑人员可复制文章地址或文章ID填入)</span>
            {!! Form::text('article_id', null, ['class' => 'form-control']) !!}
            <small class="text-danger">{{ $errors->first('article_id') }}</small>
          </div>
          @endeditor
        @endif

        <div class="answer-write">
  	     <editor name="answer"　placeholder="{{ $question->isSelf() ? '请补充您的信息' : '请开始您的回答' }}"></editor>
         <div class="submitbar">
            <div class="pull-right">
              @if($question->isSelf())
                <input type="submit" class="btn-base btn-theme btn-md" value="补充信息">
              @else
                <input type="submit" class="btn-base btn-theme btn-md" value="发表答案">
              @endif
            </div>
         </div>
        </div>

         {!! Form::hidden('question_id', $question->id) !!}
         {!! Form::hidden('user_id', Auth::id()) !!}
      {!! Form::close() !!}

      @endif

  	   <h3 class="plate-title underline"><span>{{ $question->answers()->count() }}个回答</span></h3>
  	   <div class="all-answers">
           <div class="answer-items">
            @foreach($answers as $answer)
           		@include('question.parts.answer_item')
            @endforeach
           </div>
           <div class="paging">
              {!! $answers->links() !!}
           </div>
  	   </div>
     </div>
     <div class="aside sm-right hidden-xs">
       {{-- @include('question.parts.carousel') --}}
       <div class="qrcode-sidebar">
	       <h3 class="plate-title underline">
	        <span>
	          手机版下载
	        </span>
	       </h3>
	       @include('index.parts.download_app')
       </div>
        @include('question.parts.guess')
     </div>

     @if($question->isSelf() && $question->isPay() && !$question->closed)
      <question-bottom question-id="{{ $question->id }}"></question-bottom>
     @endif

   </div>
   <modal-ask-question></modal-ask-question>
 </div>
@stop

@push('css')
    <link rel="stylesheet" type="text/css" href="/css/e.css">
@endpush

@push('modals')
  {{-- 分享到微信 --}}
  <modal-share-wx url="{{ url()->full() }}" aid="{{ $question->id }}"></modal-share-wx>
@endpush
