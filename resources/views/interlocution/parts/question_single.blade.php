{{-- 回答问题 --}}
<div class="question_single">
    <div class="question_tags">
      @foreach($question->categories as $category)
        <a class="collection" href="/{{ $category->name_en }}" target="_blank">
            <div class="name">
                {{ $category->name }}
            </div>
        </a>
       @endforeach
    </div>
    <h1 class="headline">
        {{ $question->title }}
    </h1>
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
    </div>
    @endif

    <div class="question_bottom">
                <a href="javascript:;" class="action_btn">
          {{-- <i class="iconfont icon-shoucang"></i> --}}
{{--             <i class="iconfont icon-shoucang"></i>
            <span>收藏问题{{ $question->count_favorites }}</span> --}}
            <favorite-question question-id="{{ $question->id }}"></favorite-question>
            <span>收藏问题({{ $question->count_favorites }})</span>
        </a>



    	<a href="javascript:;" class="action_btn">
    		<i class="iconfont icon-guanzhu"></i>
    		<span>邀请回答</span>
    	</a>
        <share class="action_btn" placement="top">
            <span>分享</span>
        </share>
    	<div class="inform">
    		<i class="iconfont icon-jinggao"></i>
    		<span>举报</span>
    	</div>
    </div>
    @if(Auth::check())
    <div class="answer_write">
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