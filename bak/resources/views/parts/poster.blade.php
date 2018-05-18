{{-- 轮播图 --}}
<div class="col-xs-12 posters s_s_hide" id="poster">
</div>
@push('scripts')
<script>
    var dataImage = [];

    @foreach($data->carousel as $article)
        dataImage.push(['/article/{{ $article->id }}','{{ $article->image_top }}']);
    @endforeach

    if(dataImage.length<8){
         dataImage=      [
             ['javascript:;','/images/carousel001.jpg'],
             ['javascript:;','/images/carousel002.jpg'],
             ['javascript:;','/images/carousel003.jpg'],
             ['javascript:;','/images/carousel004.jpg'],
             ['javascript:;','/images/carousel005.jpg'],
             ['javascript:;','/images/carousel006.jpg'],
             ['javascript:;','/images/carousel007.jpg'],
             ['javascript:;','/images/carousel008.jpg']
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
