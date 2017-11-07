@if(!empty($article->data_lol['data']))
@if(!empty($article->data_lol['data']['image']))
<img src="http://ossweb-img.qq.com/images/lol/web201310/skin/big{{ $article->data_lol['data']['key']}}000.jpg" alt="">
@endif


@if(!empty($article->data_lol['data']['spells']))
<p>
    <h3>
         技能介绍:
    </h3>
      <img src="http://ossweb-img.qq.com/images/lol/img/passive/{{ $article->data_lol['data']['id'] }}_Passive.png">
      <img src="http://ossweb-img.qq.com/images/lol/img/spell/{{ $article->data_lol['data']['id'] }}Q.png">
      <img src="http://ossweb-img.qq.com/images/lol/img/spell/{{ $article->data_lol['data']['id'] }}W.png">
      <img src="http://ossweb-img.qq.com/images/lol/img/spell/{{ $article->data_lol['data']['id'] }}E.png">
      <img src="http://ossweb-img.qq.com/images/lol/img/spell/{{ $article->data_lol['data']['id'] }}R.png">

    @foreach($article->data_lol['data']['spells'] as $skill)
    	{!!  $skill['name'] !!}
    	{!!  $skill['description']!!}
    	{!!  $skill['tooltip']!!}
    @endforeach
</p>
@endif

@if(!empty($article->data_lol['data']['allytips']))

 <p>
    <h3>
         使用技巧：
    </h3>

    @foreach($article->data_lol['data']['allytips'] as $info)
           {!! $info !!}
    @endforeach

    <h3>
         对抗技巧：
    </h3>

    @foreach($article->data_lol['data']['enemytips'] as $info)
           {!! $info !!}
    @endforeach
</p>
@endif

@if(!empty($article->data_lol['data']['blocks']))

<p>
    <h3>
        推荐出装(极地大乱斗)
    </h3>
    @foreach($article->data_lol['data']['blocks'] as $mode)
    	<h4>{{ $mode['mode'] == 'ARAM' ? '极地大乱动' : '召唤师峡谷' }}</h4>
	    @foreach($mode['recommended'] as $recommended)
	    	<h5>@lang('hero.'.$recommended['type'])</h5>
    		@foreach($recommended['items'] as $item)
	           	<img src="http://ossweb-img.qq.com/images/lol/img/item/{{ $item['id'] }}.png" alt="">
            @endforeach
	    @endforeach
    @endforeach

@endif
@endif