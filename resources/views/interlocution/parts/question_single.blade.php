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
    		<i class="iconfont icon-shoucang"></i>
    		<span>收藏问题{{ $question->count_favorites }}</span>
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
    <div class="answer_write">
      <form  method="post"  action="{{ route('answer.store') }}">
        <input type="hidden" value="{{ csrf_token() }}" name="_token">
        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
        <input type="hidden" value="{{ $question->id }}" name="question_id">
        <editor name="answer"></editor>
        <div class="submitbar">
            <div class="pull-right">
                <button class="btn_base btn_creation">发表答案</button>
            </div>
        </div>
      </form>
    </div>
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
            <div class="comment">
                <div>
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
                    <div class="comment_wrap">
                        <div class="article_content">
                            {!! $answer->answer !!}
                        </div>
                        <answer-tool answer-id={{ $answer->id }} is-login={{ Auth::check() }}></answer-tool>
                    </div>
                </div>
            </div>
            @endforeach
    	</div>
    </div>
</div>