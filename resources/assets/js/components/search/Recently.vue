<template>
	<div class="search_recent">
        <div class="litter_title" v-if="!hideTitle">
            最近搜索
            <a href="javascript:;" @click="clear">
                清空
            </a>
        </div>
        <ul class="search_recent_item_wrap" v-if="this.history">
            <li>
                <a href="javascript:;" target="_blank">
                    <i class="iconfont icon-unie646 browse"></i>
                    <span class="single_line">{{ this.history }}</span>
                    <i class="iconfont icon-cha clear" @click="clear"></i>
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
export default {

  name: 'Recently',

  props: ['hideTitle'],

  mounted(){
     this.fetchData();
  },

  methods:{
     fetchData(){
        var api =window.tokenize('/api/user/'+window.current_user_id+'/serach_history');
        var vm=this;
        window.axios.get(api).then(function(response){
              vm.history=response.data;
        });
     },
     clear(){
        var vm=this;
        vm.history=null;
        var api=window.tokenize('/api/user/'+window.current_user_id+'/clear_serach_history');
        window.axios.get(api).then();
     }
  },

  data () {
    return {
        history:null
    }
  }
}
</script>

<style lang="scss" scoped>
.search_recent {
    .litter_title {
        line-height: 2;
        margin-bottom: 10px;
    }
    .search_recent_item_wrap {
        li {
            a {
                padding: 10px 15px;
                height: 40px;
                line-height: 20px;
                display: inline-block;
                font-size: 14px;
                width: 100%;
                i {
                    font-size: 18px;
                    color: #787878;
                    vertical-align: middle;
                }
                .browse {
                    float: left;
                    margin-right: 10px;
                }
                .clear {
                    float: right;
                    font-size: 12px;
                    font-weight: 700;
                    display: none;
                    &:hover {
                        color: #333;
                    }
                }
                span {
                    display: inline-block;
                    width: 70%;
                }
                &:hover {
                    background-color: #f0f0f0;
                    border-radius: 4px;
                    .clear {
                        display: block;
                    }
                }
            }
        }
    }
}

</style>