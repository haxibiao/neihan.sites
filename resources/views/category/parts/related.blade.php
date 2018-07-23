<div class="atten-people">
	<p class="plate-title">相关专题</p>
    <ul>
    	@foreach($data['related_category'] as $category)         
	    	 <li class="single-media">
	    	 	<a href="/{{ $category->name_en }}" class="avatar-category">
		    	 	<img src="{{ $category->logo }}" alt="{{ $category->name }}">
		    	 </a>
	    	 	<a href="/{{ $category->name_en }}" class="info">{{ $category->name }}</a>
	    	</li>
    	@endforeach
    </ul>
</div>