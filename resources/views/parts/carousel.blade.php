@if(!empty($carousel_items))
<div class="bs-example" data-example-id="simple-carousel">
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
      <ol class="carousel-indicators">
        @foreach($carousel_items as $item)
        <li data-target="#carousel-example-generic" data-slide-to="{{ $item['index'] }}" class="{{ $item['index'] == 0 ? 'active' : '' }}"></li>
        @endforeach
        {{-- <li data-target="#carousel-example-generic" data-slide-to="1" class=""></li>
        <li data-target="#carousel-example-generic" data-slide-to="2" class=""></li> --}}
      </ol>
      <div class="carousel-inner" role="listbox">

        {{-- <div class="item next left">
          <img data-src="holder.js/900x500/auto/#777:#555/text:First slide" alt="First slide [900x500]" src="" data-holder-rendered="true">
        </div> --}}
        @foreach($carousel_items as $item)
        <div class="item {{ $item['index'] == 0 ? 'next left' : '' }}  {{ $item['index'] == count($carousel_items)-1 ? 'active left' : '' }}">
          <a href="/article/{{ $item['id'] }}">
          <img data-src="holder.js/900x500/auto/#666:#444/text:{{ $item['title'] }}" alt="{{ $item['title'] }} [900x500]" src="{{ $item['image_url'] }}" data-holder-rendered="true">
          </a>
        </div>
        @endforeach
        {{-- <div class="item active left">
          <img data-src="holder.js/900x500/auto/#555:#333/text:Third slide" alt="Third slide [900x500]" src="" data-holder-rendered="true">
        </div> --}}
      </div>
      <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
  </div>
  @endif