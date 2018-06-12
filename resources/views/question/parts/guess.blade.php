<div class="guess-wrapper">
	<h3 class="plate-title underline">
	 <span>
	   猜你喜欢
	 </span>
	</h3>
	<ul class="guess-list">
		@each('question.parts.guess_item',$guess,'question')
	</ul>
</div>