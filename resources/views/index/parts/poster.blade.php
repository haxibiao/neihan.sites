 {{-- 轮播图 --}}
  <div id="poster" class="hidden-xs">
  </div>

  @push('scripts')
    <script>
      var poster_items = [];
      @foreach($data->carousel as $index => $article)
          poster_items.push(['/movie/{{ $index+1 }}','/images/movie/carousel/movie{{ $index+1 }}.jpg','{{ $article->subject }}']);
      @endforeach

      var options = {
        'container':'#poster',
        'data':poster_items,
        'speed':'5000',
        'auto':true
      }
      let poster = new Poster(options);
      poster.init();
    </script>
  @endpush