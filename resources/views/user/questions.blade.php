@extends('layouts.app')
@section('title')
    我的问答 - {{ env('APP_NAME') }}
@stop
@section('content')
        <div id="bookmarks">
                <div class="main center">
                    <div class="page-banner">
                        {{-- <img src="/images/collect-note.png" alt=""> --}}
                        <div class="banner-img question-note">
                            <div>
                                <i class="iconfont icon-help"></i>
                                <span>我的问答</span>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel">
                        <!-- Nav tabs -->
                        <ul id="trigger-menu" class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#articles" aria-controls="articles" role="tab" data-toggle="tab">我提的问题</a>
                            </li>
                            <li role="presentation">
                                <a href="#questions_tab" aria-controls="questions_tab" role="tab" data-toggle="tab">我回答的问题</a>
                            </li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="fade in tab-pane active" id="articles">
                                <ul class="article-list">
                                    @if(count($data['questions'])==0)
                                       <blank-content></blank-content>
                                    @endif 
                                     @foreach($data['questions'] as $question)
                                      <!-- {!! Form::open(['method' => 'delete', 'route' => ['question.destroy', $question->id], 'class' => 'form-horizontal']) !!}
                                        {!! Form::submit('删除', ['class' => 'btn btn-sm btn-danger']) !!}
                                        {!! Form::close() !!} -->
                                      @include('user.parts.user_question', ['question' => $question])
                                    @endforeach
                                </ul>
                            </div>
                            <div role="tabpanel" class="fade tab-pane" id="questions_tab">
                                  <div>
                                    @php
                                        $x=0;
                                    @endphp
                                    @foreach($data['answer_questions'] as $question)
                                        @if($question&&$question->status>=0)
                                            @php
                                                $x++;
                                            @endphp
                                            @include('question.parts.question_item', ['question' => $question])   
                                        @endif
                                    @endforeach
                                    @if($x==0)
                                       <blank-content></blank-content>
                                    @endif
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
        </div>
@stop