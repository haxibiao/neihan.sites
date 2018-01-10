{{-- 轮播图 --}}
<div class="col-xs-12 posters" id="poster">
</div>
@push('scripts')
<script>
    var dataImage = [];

    @foreach($data->carousel as $article)
        dataImage.push(['/article/{{ $article->id }}','{{ $article->image_top }}']);
    @endforeach

    if(dataImage.length<8){
         dataImage=      [
             ['/v1/detail','/images/carousel001.jpg'],
             ['/v1/detail','/images/carousel002.jpg'],
             ['/v1/detail','/images/carousel003.jpg'],
             ['/v1/detail','/images/carousel004.jpg'],
             ['/v1/detail','/images/carousel005.jpg'],
             ['/v1/detail','/images/carousel006.jpg'],
             ['/v1/detail','/images/carousel007.jpg'],
             ['/v1/detail','/images/carousel008.jpg']
            ];
      }
      var options = {
       'container':'#poster',
       'data': dataImage,
       'speed':'5000',
       'auto':true
     }
     let poster = new Poster(options);
     poster.init();
</script>
@endpush
