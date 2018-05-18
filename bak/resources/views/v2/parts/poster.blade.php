{{-- 轮播图 --}}
<div class="col-xs-12 posters" id="poster">
</div>
@push('scripts')
<script>
    var options = {
    'container':'#poster',
    'data':[
        ['/v2/detail','/images/carousel001.jpg'],
        ['/v2/detail','/images/carousel002.jpg'],
        ['/v2/detail','/images/carousel003.jpg'],
        ['/v2/detail','/images/carousel004.jpg'],
        ['/v2/detail','/images/carousel005.jpg'],
        ['/v2/detail','/images/carousel006.jpg'],
        ['/v2/detail','/images/carousel007.jpg'],
        ['/v2/detail','/images/carousel008.jpg']
    ],
    'speed':'5000',
    'auto':true
    }
    let poster = new Poster(options);
    poster.init();
</script>
@endpush
