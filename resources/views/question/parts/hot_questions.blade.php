<div class="hot-questions">
   <div class="recommended-questions">
     <h3 class="plate-title wd">
      <span>
        精选回答
      </span>
       {{-- <a href="/question" class="right">
         更多<i class="iconfont icon-youbian"></i>
       </a> --}}
     </h3>
     <div class="hot-questions-list">
        @each('question.parts.hot_question_item', $hot , 'question')
     </div>
   </div>
 </div>