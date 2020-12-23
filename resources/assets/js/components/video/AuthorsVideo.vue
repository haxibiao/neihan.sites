<template>
  <div class="related-video">
        <div class="title">
            <h4 v-if="userId">作者其他视频</h4>
            <div v-if="categoryId" class="recommend">其他推荐</div>
            <a v-if="last_page>1"   href="javascript:;" class="font" @click="fetchData()"><i class="iconfont icon-shuaxin" ref="fresh"></i>换一批</a>
        </div>
        <ul class="video-list">
              <li class="video-item" v-for="post in posts">
                  <a :href="'/video/'+post.video.id+'?related_page='+page" class="link">  
                      <div class="cover">
                          <img :src="post.video.cover" alt=""/>
                          <i class="hover-play"></i>
                          <span class="duration">{{post.video.duration}}秒</span>
                      </div>
                      <div class="info">
                          <div class="recommend-video-title">{{post.content}}</div> 
                          <span class="amount">
                          {{post.count_likes+"次点赞"}}
                          </span>
                      </div>
                  </a> 
              </li>
        </ul>
    </div>
</template>

<script>
export default {

  name: 'AuthorsVideo',

  props: ["userId","categoryId","videoId","relatedPage"],

  mounted() {
    console.log('mounted');
      this.fetchData(this.relatedPage);
  },


  methods: {
  	fetchData(relatedPage) {
  		var vm = this;

      this.counter++;
      this.page++;
      //PM需求 点击后作者其他视频后 视频往下一页翻,故传入一个relatedPage来控制
      if(relatedPage){
        this.page = relatedPage;
      }

      $(this.$refs.fresh).css('transform',`rotate(${360*this.counter}deg)`);
      let apiUser = '/api/user/'+this.userId+'/videos/relatedVideos?num=4&page='+this.page+'&video_id='+this.videoId;
      // let apiCategory= '/api/category/'+this.categoryId+'/videos?video_id='+this.videoId+'&num=4&page='+this.page;
      let apiCategory= '/api/collection/'+this.categoryId+'/posts?video_id='+this.videoId+'&num=4&page='+this.page;
      if(this.userId){
        window.axios.get(apiUser).then(function(response){
        vm.posts = response.data.data;
        console.log('posts', vm.posts);
        vm.last_page=response.data.last_page;

        if(vm.page==vm.last_page){
            vm.page=1;
          }
        });
      }else if(this.categoryId){
        console.log('取专题');
        console.log(this.categoryId);
         window.axios.get(apiCategory).then(function(response){
            vm.posts = response.data.data
            vm.last_page=response.data.last_page;

            if(vm.page==vm.last_page){
                vm.page=1;
              }
            });
      }
      
  	},
  },

  data () {
    return {
    	posts:null,
      counter:-1,
      page:0,
      last_page:null,
    }
  }
}
</script>

<style lang="scss">
    .title{

      >h4{
        color: #d0d0d6;
        font-size: 17px;
        font-weight: 600;
        display: inline-block;
        padding-left:20px;
        margin:15px 0;
      }
      .recommend{
        font-weight: 300;
        font-size: 17px;
        margin: 10px 0 20px 15px;
        line-height: 25px;
        color: #515151;
        display: inline-block;
      }
      .font{
        float: right;
        margin-top: 10px;
        margin-right: 15px;
        color:#969696;
        .icon-shuaxin{
          font-size: 14px;
          display: inline-block;
          vertical-align: middle;
          margin: -2px 5px 0 0;
          transition: all .5s ease-in-out;
        }
        &:hover{
          color:#d96a5f;
        }
      }
    }
    .video-list{
      padding:5px 0 14px 20px;
      // background-color:#26262b;
        .video-item{
          .link{
            display: flex;
            align-items:center;
            width: 100%;
              .cover{
                width:42%;
                height: 101px;
                float: left;
                display: inline-block;
                position: relative;
                overflow: hidden;
                background-color: #000;
              &:hover{
                img{
                  transform: scale(1.1);
                  transition: all .3s ease-in-out;
                }
                .hover-play{
                    opacity: 1;
                    transform: scale(.8);
                    transition: all .3s ease-in-out;
                }
              }
              .hover-play{
                background: url(/images/play-icon.png) no-repeat;
                font-size: 14px;
                color: #777;
                font-weight: 400;
                position:absolute;
                top:50%;
                left:50%;
              }
              img{
                width:100%;
                background-color: #000;
              }
              .duration{
                position: absolute;
                left: 4px;
                bottom: 8px;
                padding: 0 6px;
                height: 16px;
                line-height: 16px;
                font-size: 12px;
                color: #fff;
                background-color: rgba(0,0,0,.4);
                border-radius: 2px;
              }
          }
          .info{
            width: 57%;
            padding: 0 5%;
            float: right;
            display: inline-block;
                        height:101px;
            &:hover{
              .recommend-video-title{
                color:#d96a5f !important;

              }
            }
              .recommend-video-title{
                color:#d0d0d6;
                font-size: 14px;
                line-height: 20px;
                max-height: 40px;
                display: -webkit-box;
                -webkit-box-orient: vertical;
                -webkit-line-clamp: 2;
                overflow: hidden;
                text-overflow:ellipsis
              }
              .amount{
                white-space: nowrap;
                font-size: 12px;
                color: #969696;
              }
          }
        }
        
      }
    }
</style>