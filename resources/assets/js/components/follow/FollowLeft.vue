<template>
    <div>
        <!-- 关注页的左侧 -->
        <div class="dropdown change_type">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                全部关注
                <i class="iconfont icon-xialaxuan">
                </i>
            </a>
            <ul class="dropdown-menu">
                <li>
                    <a href="#" @click="show('all')">
                        全部关注
                    </a>
                </li>
                <li>
                    <a href="#" @click="show('users')">
                        只看作者
                    </a>
                </li>
                <li>
                    <a href="#" @click="show('categories')">
                        只看专题
                    </a>
                </li>
                <li>
                    <a href="#" @click="show('collection')">
                        只看文集
                    </a>
                </li>
                <li>
                    <a href="#">
                        只看推送更新
                    </a>
                </li>
            </ul>
        </div>
        <router-link to="/add" class="add_people">
            <i class="iconfont icon-guanzhu">
            </i>
            添加关注
        </router-link>
        <ul class="js_subscription_list">
            <li>
                <router-link to="/timeline">
                    <div class="avatar">
                        <img src="/images/photo_02.jpg"/>
                    </div>
                    <div class="name">
                        爱你城朋友圈
                    </div>
                </router-link>
            </li>
            <li v-for="follow in follows_showing" @click="skip">
                <router-link :to="'/'+follow.type+'/'+follow.id">
                    <a href="javascript:;" class="category">
                        <div class="avatar">
                            <img :src="follow.img" alt="">
                        </div>
                        <div class="name">{{ follow.name }}</div>
                        <span class="count">18</span>
                    </a>
                </router-link>
            </li>
        </ul>
    </div>
</template>

<script>
export default {

  name: 'FollowLeft',

  mounted(){
    //default timeline
    this.$router.push({path:"/timeline"});
    var route_path = window.location.hash.replace("#/","");
    this.route_path = route_path;

    this.fetchData();
  },

  computed:{

  },

  methods:{
      show(type){
         console.log(this.follows);
         console.log(type);
         if(type=='all'){
            this.follows_showing=this.follows;
         }else{
            this.follows_showing=[];
            //javascript recursion
            for(var i in this.follows){
                var follow=this.follows[i];
                console.log(follow.type);
                if(follow.type==type){
                    this.follows_showing.push(follow);
                }
            }
            console.log(this.follows_showing);
         }
      },


    skip(e) {
        $(e.target).parents("li").addClass('active').siblings().removeClass('active');
    },

    fetchData() {
        var vm = this;
        var api_url = window.tokenize('/api/follows');
        window.axios.get(api_url).then(function(response){
            vm.follows = response.data;
            vm.follows_showing = response.data;
        });
    }
  },

  data () {
    return {

        route_path: 'timeline',
        follows: [],
        follows_showing: [],

    }
  }
}
</script>

<style lang="css" scoped>
</style>