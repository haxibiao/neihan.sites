@extends('layouts.app')

@section('title'){{ env('APP_NAME') }} 问答-{{ config('seo.title') }} @stop
@section('description') {{config('seo.description')  }} @stop
@section('keywords') {{ config('seo.keywords') }} @stop

@push('section')
 <div id="questions">
    {{-- 问答专题 --}}
    <div class="questions-list">
      <div class="container">
        <div class="row">
          <div class="col-xs-12">
            @include('question.parts.recommend_categories')
          </div>
        </div>
      </div>
    </div>
   <div class="container">
     <div class="wrap row">
       <div class="main sm-left">
         @each('question.parts.question_item',  $questions ,'question')
         <div class="pager">
           {!! $questions->appends(['cid' => request('cid')])->links() !!}
         </div>
       </div>
       <div class="aside sm-right hidden-xs">
         {{-- @include('question.parts.carousel') --}}
         @include('question.parts.hot_questions')
         @include('question.parts.contact')
       </div>
     </div>
   </div>
     {{-- 网站底部信息 --}}
  @include('parts.footer')
  <modal-ask-question></modal-ask-question>
 </div>
@endpush
