@if(!empty($article->data_wz))
@if(!empty($article->data_wz['banner']))
<img src="{{ $article->data_wz['banner'] }}" alt="" class="img img-responsive">
@endif

@if(!empty($article->data_wz['技能说明']))
<p>
    <h3>
        技能说明
    </h3>
    {{-- @foreach($article->data_wz['技能说明'] as $skill)
    	{!!  $skill !!}
    @endforeach --}}
    {!! $article->data_wz['技能说明'] !!}
</p>
@endif

@if(!empty($article->data_wz['技能加点建议']))
<p>
    <h3>
        技能加点建议
    </h3>
    {{-- @foreach($article->data_wz['技能加点建议'] as $skill)
    	{!!  $skill !!}
    @endforeach --}}
    {!! $article->data_wz['技能加点建议'] !!}
</p>
@endif

@if(!empty($article->data_wz['铭文搭配建议']))
<p>
    <h3>
        铭文搭配建议
    </h3>
    <ul>
    @foreach($article->data_wz['铭文搭配建议'] as $ming_item)
    	<li>
    		<img src="{{ $ming_item->image_url }}" alt="{{ $ming_item->ming_name }}"><br/>
    		{{ $ming_item->ming_name }}<br/>
    		{!! $ming_item->ming_des !!}
    	</li>
    @endforeach
    </ul>
</p>
@endif

@if(!empty($article->data_wz['英雄关系']))
<p>
    <h3>
        英雄关系
    </h3>
    <ul>
    @foreach($article->data_wz['英雄关系'] as $relation)
        <li>
        <h3>{{ $relation['title'] }}</h3>
            <ul>
            @foreach($relation['items'] as $item)
                <li>
                    <img src="{{ $item['img'] }}" alt="">
                    <p>
                        {{ $item['desc'] }}
                    </p>
                </li>
            @endforeach
            </ul>
        </li>
    @endforeach
    </ul>
</p>
@endif

<div class="row">
    <div class="col-xs-12">
    生存：
    <div class="progress">
      <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: {{ $article->data_wz['生存'] }}0%;">
        {{ $article->data_wz['生存'] }}0
      </div>
    </div>
    </div>
    <div class="col-xs-12">
    攻击：
    <div class="progress">
      <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: {{ $article->data_wz['攻击'] }}0%;">
        {{ $article->data_wz['攻击'] }}0
      </div>
    </div>
    </div>
    <div class="col-xs-12">
    技能：
    <div class="progress">
      <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: {{ $article->data_wz['技能'] }}0%;">
        {{ $article->data_wz['技能'] }}0
      </div>
    </div>
    </div>
    <div class="col-xs-12">
    难度：
    <div class="progress">
      <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: {{ $article->data_wz['难度'] }}0%;">
        {{ $article->data_wz['难度'] }}0
      </div>
    </div>
    </div>
</div>
@endif