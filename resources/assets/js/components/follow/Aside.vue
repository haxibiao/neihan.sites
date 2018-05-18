<template>
	<div class="aside">
		<a href="javascript:;" class="change-type" data-toggle="dropdown" aria-expanded="false"><span class="hidden-xs">全部关注</span><i class="iconfont icon-xialaxuan"></i></a>
		<ul class="dropdown-menu">
			<li>
					<a href="javascript:;" class="link" @click="show('all')">全部关注</a>
			</li>
			<li>
					<a href="javascript:;" class="link" @click="show('users')">只看作者</a>
			</li>
			<li>
					<a href="javascript:;" class="link" @click="show('categories')">只看专题</a>
			</li>
			<li>
					<a href="javascript:;" class="link" @click="show('collections')">只看文集</a>
			</li>
			<li>
					<a href="javascript:;" class="link">只看<b class="hidden-xs" style="font-weight:400">推送</b>更新</a>
			</li>
		</ul>
		<router-link to="/recommend" active-class="active-add-people">
			<a href="javascript:;" class="add-people"><i class="iconfont icon-guanzhu"></i><span class="hidden-xs">添加关注</span></a>
		</router-link>
		<ul class="follow-list">
			
			<router-link to="/timeline"  tag="li" active-class="active">
				<a href="javascript:;" class="follow-warp">
					<div class="avatar">
						<img src="/images/timeline.png" alt="">
					</div>
					<div class="name">兴趣圈</div>
				</a>
			</router-link>
				
			<router-link 
				v-for="follow in follows_showing"  
				:to="'/'+follow.type+'/'+follow.id" 
				tag="li" 
				active-class="active" 
				:key="follow.id">
				<a href="javascript:;" class="follow-warp">
					<div :class="follow.type=='users'?'avatar':'avatar-category'">
						<img :src="follow.img" alt="">
					</div>
					<div class="name single-line">{{ follow.name }}</div>
					<span class="count hidden-xs">{{ follow.updates }}</span>
				</a>
			</router-link>	
			
		</ul>
	</div>
</template>

<script>
export default {

  name: 'Aside',

  created() {

  	//默认选择朋友圈...
  	this.$router.push({path:'/timeline'});
  	var route_path = window.location.hash.replace('#/','');
  	this.route_path = route_path; 

  	this.fetchData();

  },

  computed: {
  },

  methods: {
  	show(type) {
  		if(type == 'all') {
  			this.follows_showing = this.follows;
  		} else {
	  		this.follows_showing = [];
	  		for(var i in this.follows) {
	  			var follow = this.follows[i];
	  			if(follow.type == type) {
	  				this.follows_showing.push(follow);
	  			}
	  		}
  		}
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



<style lang="scss" scoped>
	.aside {
		border-right: 1px solid #f0f0f0;
		.change-type {
			margin-left: 20px;
			font-size: 15px;
			color: #252525;
			i {
				margin-left: 5px;
				color: #c8c8c8;
			}
		}
		.dropdown-menu {
			width: 130px;
			a {
				text-align: center;
			}
		}
		.add-people {
			float: right;
			margin-right: 6px;
			font-size: 13px;
			color: #252525;
			i {
				margin-right: 4px;
			}
		}
		.active-add-people {
			.add-people {
				color: #FF9D23;
			}
		}
		.follow-list {
			margin-top: 7px;
			border-top: 1px solid #f0f0f0;
		  li {
		    &.active {
		      background-color: #f0f0f0;
		      border-radius: 4px 0 0 4px;
		    }
		    &:first-child {
		    	 border-radius: 0 0 0 4px;
		    }
		    .follow-warp {
		      position: relative;
		      height: auto;
		      padding: 10px 20px;
		      line-height: 30px;
		      display: block;
		      font-size: 15px;
		      color: #252525;
		      .avatar,.avatar-category {
		      	width: 40px;
		      	height: 40px;
		      }
		      i {
		        margin-right: 15px;
		        font-size: 22px;
		        color: #FF9D23;
		        vertical-align: middle;
		      }
		      .name {
		      	display: inline-block;
		      	vertical-align: middle;
		      	max-width: 150px;
		      	@media screen and (max-width:1050px) {
		      		max-width: 79px;
		      	}
		      }
		      .count {	
		      	float: right;
		      	margin-top: 4px;
		      	font-size: 14px;
		      	color: #969696;
		      }
		    }
		    &:hover {
		    	background-color: #f0f0f0;
		    }
		  }
		}
		@media screen and (max-width:768px) {
			.change-type {
				margin: 0;
			}
			.follow-list {
				li {
				  .follow-warp {
				  	padding: 10px 0 0 0;
				  	text-align: center;
				  	.avatar {
				  		display: block;
				  		width: auto;
				  		height: auto;
				  		img {
							width: 40px;
							height: 40px;
				  		}
				  	}
				  	.name {
				  	   padding-right: 5px;
				  	   width: 70px;
				  	 }
				  }
				}
			}
			.dropdown-menu {
				width: 70px;
				min-width: 70px;
				li {
					margin-bottom: 10px;
					.link {
						padding: 10px 0 !important;
						text-align: center;
					}
				}
			}
		}
	}
</style>