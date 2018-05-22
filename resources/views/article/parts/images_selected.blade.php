<div class="form-group">
    {!! Form::label('article_images', '已选配图') !!}
    <div class="row" id="article_images">
        <div class="col-xs-3 hide" id="article_image_template">
            <p class="text-center">
                <img src="/logo/{{ get_domain() }}.png" alt="" class="img img-responsive">
                
                <label class="radio text-center">
                  <input type="radio" name="primary_image" value="/logo/{{ get_domain() }}.png">
                  设为主要图
                </label>
                
            </p>
        </div>   
        @foreach($images as $image)
        @if(!str_contains($image->path_small(), 'storage/video'))
            <div class="col-xs-3">
                <p class="text-center">
                    <img src="{{ $image->url_small() }}" alt="" class="img img-responsive">
                    
                    <label class="radio text-center">
                      <input type="radio" name="primary_image" value="{{ $image->url_small() }}" {{ $image->url_small() == $article->image_url ? 'checked':'' }}>
                      设为主要图
                    </label>
                    
                </p>
            </div> 
        @endif
        @endforeach
    </div>
</div>