<template>
	<div id="left">
        <!-- 搜索页的左侧 -->
        <ul class="left_link">
            <li>
                <router-link to="/note" class="action_link">
                	<div class="search_icon">
                		<i class="iconfont icon-icon_article"></i>
                	</div>
                	<span class="name">文章</span>
                </router-link>
            </li>
            <li>
                <router-link to="/user" class="action_link">
                	<div class="search_icon">
                		<i class="iconfont icon-yonghu01"></i>
                	</div>
                	<span class="name">用户</span>
                </router-link>
            </li>
            <li>
                <router-link to="/collection" class="action_link">
                	<div class="search_icon">
                		<i class="iconfont icon-zhuanti1"></i>
                	</div>
                	<span class="name">专题</span>
                </router-link>
            </li>
            <li>
                <router-link to="/notebook" class="action_link">
                	<div class="search_icon">
                		<i class="iconfont icon-wenji"></i>
                	</div>
                	<span class="name">文集</span>
                </router-link>
            </li>
        </ul>
        <div class="split_line"></div>
        <hot-search></hot-search>
        <div class="split_line"></div>
        <recently-search></recently-search>
    </div>
</template>

<script>
export default {

  name: 'SearchLeft',

    created(){
    //default timeline
    this.$router.push({path:"/note"});
    var route_path = window.location.hash.replace("#/","");
    this.route_path = route_path;

    this.fetchData();
  },

  mounted(){
     this.fetchData();
  },

  methods:{
     change(){
        this.changes++;
        this.fetchData();
     },
     fetchData(){
        $(this.$refs.fresh).css('transform',`rotate(${360*this.changes}deg)`);

        var api='/api/search/hot-queries?page='+this.changes;
        var vm=this;
        window.axios.get(api).then(function(response){
            if(response.data.length){
                vm.queries=response.data;
            }
        });
     }
  },

  data () {
    return {
    	changes:1,
        queries:[]
    }
  }
}
</script>

<style lang="scss">
#left {
    .left_link {
        padding-bottom: 20px;
        li {
            .action_link {
                padding: 10px 25px;
                display: block;
                width: 100%;
                font-size: 15px;
                border-radius: 4px;
                .search_icon {
                    width: 32px;
                    height: 32px;
                    display: inline-block;
                    margin-right: 15px;
                    vertical-align: middle;
                    background-color: #a0a0a0;
                    border-radius: 4px;
                    text-align: center;
                    color: #fff;
                    i {
                        font-size: 18px;
                        margin: 6px 0 0 1px;
                        display: block;
                    }
                }
                span {
                    vertical-align: middle;
                }
                &:hover,&.router-link-active {
                    background-color: #f0f0f0;
                }
                @media screen and (max-width: 768px) {
                    padding: 10px 15px;
                    i {
                        margin-right: 3px;
                    }
                    .name {
                        display: block;
                        padding-top: 5px;
                    }
                }
                @media screen and (max-width: 768px) {
                    text-align: center;
                    .search_icon {
                        margin-right: 0;
                    }
                }
            }
        }
    }
    .split_line {
        margin: -5px 0 20px;
    }
    .search_trending {
        padding: 0 15px 20px;
    }
    .search_recent {
        .litter_title {
            padding: 0 15px!important;
        }
    }
    @media screen and (max-width: 768px) {
        .split_line,.search_trending,.search_recent {
            display: none;
        }
    }
}
</style>