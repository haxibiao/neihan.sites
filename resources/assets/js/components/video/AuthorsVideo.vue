<template>
  <div class="related-video">
        <div class="title">
            <h4 v-if="userId">作者其他视频</h4>
            <div v-if="categoryId" class="recommend">其他推荐</div>
            <a v-if="last_page>1" target="_blank" href="javascript:;" class="font" @click="fetchData"><i class="iconfont icon-shuaxin" ref="fresh"></i>换一批</a>
        </div>
        <ul class="video-list">
              <li class="video-item" v-for="video in videos">
                  <a :href="'/video/'+video.video.id" class="link clearfix">  
                      <div class="cover">
                          <img :src="video.image_url" alt=""/>
                          <i class="hover-play"></i>
                          <span class="duration">{{video.duration}}</span>
                      </div>
                      <div class="info">
                          <div class="recommend-video-title">{{video.title}}</div> 
                          <span class="amount">
                          {{video.hits+"次播放"}}
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

  props: ["userId","categoryId"],

  mounted() {
      this.fetchData();
  },


  methods: {
  	fetchData() {
  		var vm = this;

      this.counter++;
      this.page++;

      $(this.$refs.fresh).css('transform',`rotate(${360*this.counter}deg)`);
      let apiUser = '/api/user/'+this.userId+'/videos/relatedVideos?num=2&page='+this.page;
      let apiCategory= '/api/user/'+this.categoryId+'/videos/sameVideos?num=4&page='+this.page;
      if(this.userId){
        window.axios.get(apiUser).then(function(response){
        vm.videos = response.data.data
        vm.last_page=response.data.last_page;

        if(vm.page==vm.last_page){
            vm.page=0;
          }
        });
        }else if(this.categoryId){
         window.axios.get(apiCategory).then(function(response){
            vm.videos = response.data.data
            vm.last_page=response.data.last_page;

            if(vm.page==vm.last_page){
                vm.page=0;
              }
            });
        }
      
  	},
  },

  data () {
    return {
    	videos:null,
      counter:-1,
      page:0,
      last_page:null,
    }
  }
}
</script>

<style lang="scss">
    .title{
      margin: 10px 5px;
      >h4{
        color: #fff;
        display: inline-block;
      }
      .recommend{
        font-weight: 300;
        font-size: 25px;
        margin: 0 0 20px;
        line-height: 25px;
        color: #515151;
        display: inline-block;
      }
      .font{
        float: right;
        margin-top: 6px;
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
    .video-item{
      .link{
        display: inline-block;
        width: 100%;
          .cover{
            width: 50%;
            float: left;
            display: inline-block;
            position: relative;
            overflow: hidden;
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
            background: url(https://www.ainicheng.com/images/play-icon.png) no-repeat;
            font-size: 14px;
            color: #777;
            font-weight: 400;
            position:absolute;
            top:50%;
            left:50%;
          }
          img{
            width:100%;
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
        width: 50%;
        padding: 0 5%;
        float: right;
        display: inline-block;
        &:hover{
          .recommend-video-title{
            color:#d96a5f !important;
          }
        }
          .recommend-video-title{
            color:#969696;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            overflow: hidden;
          }
          .amount{
            white-space: nowrap;
            font-size: 12px;
            color: #969696;
          }
      }
      }
      
    }
</style>