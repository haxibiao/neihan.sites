{{-- 回答问题 --}}
<div class="question_single">
    <div class="question_tags">
      {{-- @foreach($question->categories as $category)
        <a class="collection" href="/{{ $category->name_en }}" target="_blank">
            <div class="name">
                {{ $category->name }}
            </div>
        </a>
       @endforeach --}}
       <a class="collection" href="" target="_blank">
            <div class="name">
                王者荣耀
            </div>
        </a>
        <a class="collection" href="" target="_blank">
            <div class="name">
                技能点
            </div>
        </a>
    </div>
    <h1 class="headline">
        {{ $question->title }}
    </h1>
    <div class="inquisitorial">
        {{-- <div class="author">
            <a class="avatar" href="" target="_blank">
                <img src="/images/photo_03.jpg"/>
            </a>
            <div class="info_meta">
                <a href="" target="_blank" class="nickname">
                    小小
                </a>
                <span class="time">
                    2分钟 前
                </span>
            </div>
        </div> --}}
        {{-- 匿名用户 --}}
        @if($question->is_anonymous==0)
        <div class="author">
            <a class="avatar" href="/user/{{ $question->user->id }}" target="_blank">
                <img src="{{ $question->user->getLatestAvatar() }}"/>
            </a>
            <div class="info_meta">
                <a href="/user/{{ $question->user->id }}" target="_blank" class="nickname">
                    {{ $question->user->name }}
                </a>
                <span class="time">
                  {{ diffForHumansCN($question->created_at) }}
                </span>
            </div>
        </div>
        @else
        <div class="author">
            <a class="avatar" href="javascript:;" target="_blank">
                <img src="/images/photo_user.png"/>
            </a>
            <div class="info_meta">
                <a href="javascript:;" target="_blank" class="nickname">
                    匿名用户
                </a>
                <span class="time">
                  {{ diffForHumansCN($question->created_at) }}
                </span>
            </div>
        </div>
        @endif
    </div>
    <div class="question_text">
        {{ $question->background }}
    </div>
    @if(!empty($question->image1))
    <div class="question_img">
        {{-- <img src="{{ $question->image }}"/> --}}
        <img src="{{ $question->image1 }}" />
     @if(!empty($question->image2))
        <img src="{{ $question->image2 }}" />
     @endif
     @if(!empty($question->image3))
        <img src="{{ $question->image3 }}" />
     @endif
      {{--   <img src="/images/details_04.jpeg" />
        <img src="/images/details_17.jpeg" /> --}}
    </div>
    @endif
    {{-- 问题的工具 --}}
    <question-tool question-id="{{ $question->id }}"></question-tool>


    @if(Auth::check())
    <div class="answer_write simditor_submi">
      <form method="post"  action="{{ route('answer.store') }}">
        <input type="hidden" value="{{ csrf_token() }}" name="_token">
        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
        <input type="hidden" value="{{ $question->id }}" name="question_id">
        <div class="optimum">
            <div class="suggest_title">
                最佳回答文章
                <span class="depict">(可选，编辑人员可复制文章地址或文章ID填入)</span>
            </div>
            <div class="input_box">
                <input type="text" class="form-control" name="article_id" id="article_id" />
            </div>
        </div>
        <editor name="answer"></editor>
        <div class="submitbar">
            <div class="pull-right">
                <button class="btn_base btn_creation">发表答案</button>
            </div>
        </div>
            <div class="form-group{{ $errors->has('answer') ? ' has-error' : '' }}">
                <small class="text-danger">{{ $errors->first('answer') }}</small>
            </div>
      </form>
    </div>
    @endif
    <div class="answers">
    	<div class="note_title">
    		<div class="litter_title title_line">
		        <span class="title_active">
		            {{ $question->answers->count() }}个回答
		        </span>
		    </div>
    	</div>
    	<div class="note_answers">
            @foreach($answers as $answer)
            <div class="answer_item">
                 <div class="answer_user">
                    <div class="author">
                        <a class="avatar avatar_xs" href="#">
                            <img src="{{ $answer->user->avatar }}"/>
                        </a>
                        @if(Auth::check())
                        <follow followed="{{ Auth::user()->isFollow('users', $answer->user->id)}}" id="{{ $answer->user->id }}" type="users" user-id="{{ Auth::user()->id }}">
                        </follow>
                        @endif
                        <div class="info_meta">
                            <a class="nickname" href="#">
                                {{ $answer->user->name }}
                            </a>
                            <div class="meta">
                                <span>
                                    {{ $answer->created_at }}
                                </span>
                            </div>
                        </div>
                    </div>
                 </div>
                <div class="article_content fold">
                    <div class="answer_text_full">
                        {!! $answer->answer !!}
                    </div>
                    <a href="javascript:;" class="expand_bottom">展开全部</a>
                </div>
                <answer-tool answer-id={{ $answer->id }} is-login={{ Auth::check() }}></answer-tool>
            </div>
            @endforeach
    	</div>
    </div>
</div>

@push('scripts')
    <script>
        $(function(){
            $('.answer_item').each(function(index, el) {
                if($(el).height()<300) {
                    $(el).find('.article_content').removeClass('fold');
                };
            });
            $('.answer_item .expand_bottom').on('click',function(){
                $(this).parent('.article_content').removeClass('fold');
            })
        })
    </script>
@endpush