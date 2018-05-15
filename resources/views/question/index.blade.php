@extends('layouts.app')

@section('title'){{ env('APP_NAME') }} 问答-{{ config('seo.title') }} @stop
@section('description') {{config('seo.description')  }} @stop
@section('keywords') {{ config('seo.keywords') }} @stop

@push('section')
 <div id="questions">
    {{-- 问答专题 --}}
    <div class="questions-list">
      <div class="container clearfix">
        <div class="col-xs-12">
          @include('question.parts.recommend_categories')
        </div>
      </div>
    </div>
   <section class="container">
     <div class="main col-sm-8">
       @each('question.parts.question_item',  $questions ,'question')
       <div class="pager">
         {!! $questions->appends(['cid' => request('cid')])->links() !!}
       </div>
     </div>
     <div class="aside col-sm-4 hidden-xs">
       {{-- @include('question.parts.carousel') --}}
       @include('question.parts.hot_questions')
       @include('question.parts.contact')
     </div>
   </section>
     {{-- 网站底部信息 --}}
  @include('parts.footer')
  <modal-ask-question></modal-ask-question>
 </div>
@endpush
