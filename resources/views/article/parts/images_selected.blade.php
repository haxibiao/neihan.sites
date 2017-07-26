<div class="form-group">
    {!! Form::label('article_images', '已选配图') !!}
    <div class="row" id="article_images">
        <div class="col-xs-3 hide" id="article_image_template">
            <p class="text-center">
                <img src="/img/1.jpg" alt="" class="img img-responsive">
                
                <label class="radio text-center">
                  <input type="radio" name="primary_image" value="/img/1.jpg">
                  设为主要图
                </label>
                
            </p>
        </div>   
        @foreach($article_images as $image)
            <div class="col-xs-3">
                <p class="text-center">
                    <img src="{{ get_img($image->path_small) }}" alt="" class="img img-responsive">
                    
                    <label class="radio text-center">
                      <input type="radio" name="primary_image" value="{{ get_img($image->path) }}" {{ get_img($image->path) == $article->image_url ? 'checked':'' }}>
                      设为主要图
                    </label>
                    
                </p>
            </div> 
        @endforeach
    </div>
</div>