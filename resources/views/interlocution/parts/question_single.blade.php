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
    <div class="question_img">
        {{-- <img src="{{ $question->image }}"/> --}}
        <img src="/images/details_17.jpeg" />
    </div>
    <div class="question_bottom">
    	<a href="javascript:;" class="action_btn">
    		{{-- <i class="iconfont icon-shoucang"></i> --}}
    		<i class="iconfont icon-shoucang1"></i>
    		<span>收藏问题</span>
    		(
    			<span class="count">{{ $question->count_favorites }}</span>
    		)
    	</a>
    	<a href="javascript:;" class="action_btn">
    		<i class="iconfont icon-guanzhu"></i>
    		<span>邀请回答</span>
    	</a>
        <share-modal class="action_btn" placement="top">
            <span>分享</span>
        </share-modal>
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
{{--             <div class="comment">
                <div>
                    <div class="author">
                        <a class="avatar avatar_xs" href="#">
                            <img src="/images/photo_02.jpg"/>
                        </a>
                        <a class="btn_base btn_follow" href="javascript:;">
                            <span>
                                ＋ 关注
                            </span>
                        </a>
                        <div class="info_meta">
                            <a class="nickname" href="#">
                                东尘roam
                            </a>
                            <div class="meta">
                                <span>
                                    2017.07.05 07:47
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="comment_wrap">
                    	<div class="article_content">
                    		<p>在王者荣耀里面或多或少都有自己喜欢的英雄，如果给你一个愿望可以成为自己喜欢的英雄会是谁呢？是我们的短腿小鲁班么？ 毕竟他的亲戚那么多~ 谁敢欺负他呢</p>
                    		<p>
	                        	<img src="/images/details_22.jpeg" />
                    		</p>
                    		<p>还是孙尚香啦，大小姐驾到，通通跪下。</p>
                    		<p>
	                        	<img src="/images/details_23.jpeg" />
                    		</p>
                    		<p>还是我们的孙大圣</p>
                    		<p>
	                        	<img src="/images/details_24.jpeg" />
                    		</p>
                    	</div>
                        <div class="tool_group">
                            <a href="#">
                                <i class="iconfont icon-fabulous">
                                </i>
                                <span>
                                    237 赞
                                </span>
                            </a>
                            <a href="/detail" target="_blank" class="count count_link">
                                <i class="iconfont icon-dianzan1">
                                </i>
                                2 踩
                            </a>
                            <a href="#">
                                <i class="iconfont icon-xinxi">
                                </i>
                                <span>
                                    88 评论
                                </span>
                            </a>
                            <a class="report" href="#">举报</a>
                        </div>
                    </div>
                </div>
                <div class="sub_comment_list">
                	<div class="comment_wrap">
                		<new-comment></new-comment>
                	</div>
                    <div class="comment_wrap">
                        <p>
                        	<a class="avatar avatar_xs" href="#">
	                            <img src="/images/photo_02.jpg"/>
	                        </a>
                            <a href="#" class="moleskine_author">
                                智_先生
                            </a>
                            ：
                            <span>
                                <a href="#" class="moleskine_author">
                                    @哈尼
                                </a>
                                物以类聚，人以群分，不同的圈子文化对一个人的影响是比较大
                            </span>
                        </p>
                        <div class="tool_group">
                            <span class="comment_time">
                                2017.07.05 08:45
                            </span>
                            <a href="#">
                                <i class="iconfont icon-xinxi">
                                </i>
                                <span>
                                    回复
                                </span>
                            </a>
                            <a class="report" href="#">举报</a>
                        </div>
                    </div>
                    <div class="comment_wrap">
                        <p>
                        	<a class="avatar avatar_xs" href="#">
	                            <img src="/images/photo_02.jpg"/>
	                        </a>
                            <a href="#" class="moleskine_author">
                                心若冰清_
                            </a>：<span>
                                这个写的确实很真实
                            </span>
                        </p>
                        <div class="tool_group">
                            <span class="comment_time">
                                2017.07.05 08:45
                            </span>
                            <a href="#">
                                <i class="iconfont icon-xinxi">
                                </i>
                                <span>
                                    回复
                                </span>
                            </a>
                            <a class="report" href="#">举报</a>
                        </div>
                    </div>
                    <div class="comment_wrap">
                        <p>
                        	<a class="avatar avatar_xs" href="#">
	                            <img src="/images/photo_02.jpg"/>
	                        </a>
                            <a href="#" class="moleskine_author">
                                吕岳阳
                            </a>
                            ：
                            <span>
                                我很费解啊，竟然有东西比撸代码还有意思。
                            </span>
                        </p>
                        <div class="tool_group">
                            <span class="comment_time">
                                2017.07.05 08:45
                            </span>
                            <a href="#">
                                <i class="iconfont icon-xinxi">
                                </i>
                                <span>
                                    回复
                                </span>
                            </a>
                            <a class="report" href="#">举报</a>
                        </div>
                    </div>
                    <div class="comment_wrap more_comment">
                        <span class="line_warp">
                            还有67条评论，
                            <a href="#">
                                展开查看
                            </a>
                        </span>
                    </div>
                </div>
            </div> --}}
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
                        <div class="tool_group">
                            <a href="#">
                                <i class="iconfont icon-fabulous">
                                </i>
                                <span>
                                    {{ $answer->count_likes }} 赞
                                </span>
                            </a>
                            <a href="/detail" target="_blank" class="count count_link">
                                <i class="iconfont icon-dianzan1">
                                </i>
                                {{ $answer->count_unlikes }} 踩
                            </a>
                            <a href="#">
                                <i class="iconfont icon-xinxi">
                                </i>
                                <span>
                                    {{ $answer->count_comments }} 评论
                                </span>
                            </a>
                            <a class="report" href="#">举报</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
    	</div>
    </div>
</div>