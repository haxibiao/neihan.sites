<div class="wd-contact">
  <h3 class="plate-title underline">
   <span>
     联系我们
   </span>
  </h3>
  <div class="contact-info">
      <p> © 2018 {{ config('app.name') }}</p>
      <p class="cooperate">
          {{ config('app.name') }}问答合作邮箱：{{ 'wendahz@'.get_domain() }}
      </p>
      <p>
          {{ config('app.name') }}问答内容举报邮箱：{{ 'jubao@'.get_domain() }}
      </p>
      <p>
          {{ env('ICPB') }}
      </p>
      <p>
          {{ env('ICPGA') }}
      </p>
      <p>
          网络文化经营许可证
      </p>
      <p>
          跟帖评论自律管理承诺书
      </p>
      <p>
          违法和不良信息举报电话：028-69514351
      </p>
      <p>
          公司名称：{{ env('COMPANY') }}
      </p>
  </div>
</div>