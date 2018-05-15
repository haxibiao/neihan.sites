@if(!empty($data['json_lists']))
<div class="row">
    @foreach($data['json_lists'] as $list)
    <div class="{{ $list['col'] }}">
      <div class="panel panel-default">
        <div class="panel-heading">
          {{ $list['title'] }}
        </div>
        <div class="panel-body">
        @foreach($list['items'] as $item)
          <div class="col-xs-6 {{ get_items_col($list['items']) }}">
            <a href="{!! app_link_with_title($item['id'], $item['title']) !!}"><img src="{{ get_small_image($item['image_url']) }}" class="img img-responsive"></a>
            <p class="strip_title">
              {{ $item['title'] }}
            </p>
          </div>
        @endforeach
        </div>
      </div>
    </div>
    @endforeach
</div>
@endif