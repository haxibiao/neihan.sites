<template>
	<form class="navbar-form navbar-left" role="search" action="/search" method="get" id='searchForm'>
		<div class="form-group">
			<div class="search-wrapper">
				<input type="text" id="search-input" class="form-control" placeholder="搜索" name="q">
				<span class="iconfont icon-sousuo search-btn" @click="search"></span>
				<div class="hot-search-wrp hidden-xs">
					<hot-search></hot-search>
					<recently v-if="user" hide-title="true"></recently>
				</div>
			</div>
		</div>
	</form>
</template>

<script>
export default {

  name: 'SearchBox',

  computed: {
    user() {
      return window.user;
    }
  },

  methods: {
  	search() {
  		$('#searchForm').submit();
  	}
  },

  data () {
    return {
    }
  }
}
</script>

<style lang="scss">
.hot-search-wrp {
    margin-top: 9px;
    width: 220px;
    left: 0;
    top: 100%;
    visibility: hidden;
    opacity: 0;
    border-radius: 4px;
    z-index: 999;
    &,&::before {
    	position: absolute;
	    background-color: #fff;
	    box-shadow: 0 0 8px rgba(0,0,0,.2);
    }
    &::before {
    	content: "";
    	left: 27px;
    	width: 10px;
    	height: 10px;
    	transform: rotate(45deg);
    	top: -5px;
    	z-index: -1;
    }
    .hot-search {
    	border-bottom: 1px solid #f0f0f0;
    }
    .search-recent {
    	padding: 10px 15px;
    }
}
</style>