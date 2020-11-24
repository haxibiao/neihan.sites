 {{-- 轮播图 --}}
  <div id="poster" class="hidden-xs">
  </div>

  @push('scripts')
    <script>
      var poster_items = [];
      @foreach($data->carousel as $index => $item)
          poster_items.push(['/movie/{{$item->id}}','{{$item->coverUrl}}','{{ $item->name }}']);
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
