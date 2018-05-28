<template>
	<div :class="['tool-cabinet', answer&&answer.deleted ? 'deleted-answer':'']" v-if="loaded">
		<div class="bottom-tool question-tool" v-if="questionId">
		    <a href="javascript:;" class="action-btn" @click="favoriteQuestion"><i :class="['iconfont', question.favorited ? 'icon-shoucang1' : 'icon-shoucang']"></i><span class="text">收藏问题{{ question.count_favorites ? '('+question.count_favorites+')' : '' }}</span></a>
		    
		    <a href="javascript:;" class="action-btn" @click="inviteAnswer"><i class="iconfont icon-guanzhu"></i><span class="text hidden-xs">邀请回答</span></a>
		    <share placement="top" class="action-btn no-margin"><span class="text">分享</span></share>
		    
		    <a href="javascript:;" class="action-btn report pull-right" @click="reportQuestion"><i class="iconfont icon-jinggao"></i><span class="text hidden-xs">{{ question.reported ? '已举报':'举报' }}{{ question.count_reports ? '('+question.count_reports+')' : '' }}</span></a>
		</div>
		<div class="bottom-tool answer-tool" v-else>
		    <a href="javascript:;" :class="['action-btn','like',answer.liked?'active':'']" @click="likeAnswer"><i :class="['iconfont', answer.liked ? 'icon-dianzan' : 'icon-fabulous' ]"></i><span class="text hidden-xs">{{ answer.count_likes }}赞</span></a>
		    <a href="javascript:;" class="action-btn" @click="unlikeAnswer"><i :class="['iconfont', answer.unliked ? 'icon-zan2' : 'icon-dianzan1']"></i><span class="text hidden-xs">{{ answer.count_unlikes }}踩</span></a>
		    <a href="javascript:;" class="action-btn" @click="showComment"><i class="iconfont icon-svg37"></i>{{ answer.count_comments }}<span class="text hidden-xs">评论</span></a>
		    <share placement="top" class="action-btn no-margin"><span class="text">分享</span></share>
		    
		    <!-- 看别人的是举报，自己是删除 -->
		    <!-- 删除按钮view组件-->
		    <a v-if="isSelf" class="pull-right">
		      <delete-button api="/api/delete-answer-" :id="answerId">
			  </delete-button>
		    </a>
		    <!-- <a v-if="isSelf" href="javascript:;" class="action-btn delete pull-right"  @click="deleteAnswer"><i class="iconfont icon-lajitong"></i><span class="text hidden-xs">{{ answer.deleted ? '已删除':'删除' }}</span></a>-->
		    <a href="javascript:;" class="action-btn report pull-right" v-else @click="reportAnswer"><i class="iconfont icon-jinggao"></i><span class="text hidden-xs">{{ answer.reported ? '已举报':'举报' }}</span></a>
		    
		    <a v-if="isPayer && ! answered" href="javascript:;" :class="['action-btn','accept','pull-right',isAccept?'already':'']" @click="checkAccept">
		    	<i :class="['iconfont',isAccept?'icon-weibiaoti12':'']"></i>
		    	<i class="iconfont icon-cha chacha"></i>
		    	<span class="text">{{ isAccept ? '已采纳':'采纳该回答' }}</span>
		    </a>

		</div>
		<!-- 邀请列表 -->
		<div class="invite-answer" v-if="questionId" v-show="showInvite">
			<div class="invite-user">
				<div class="invite-status">立即邀请用户，更快获得回答</div>
				<ul class="invite-list"> 
					<li class="note-info info-xs" v-for="user in uninvited">
						<a :href="'/user/'+user.id" class="avatar"  v-if="!user.invited">
							<img :src="user.avatar" alt=""></a>
							<a class="btn-base btn-follow btn-md" @click="inviteUser(user)"><i class="iconfont icon-guanzhu"></i><span>邀请</span></a>
							<div class="title"><a :href="'/user/'+user.id" class="name">{{ user.name }}</a></div>
							<div class="info"><p>{{ user.introduction }}</p></div>
					</li>
				</ul>
			</div>
		</div>
		<!-- 评论中心 -->
		<div class="comment-container" v-if="answerId" v-show="showComments">
			<comments :id="answerId" type="answers"></comments>
		</div>
	</div>
</template>

<script>
export default {
	name: "AnswerTool",

	//有questionId是问题下的，有answerId是回答下的
	props: ["questionId", "answerId", "isSelf", "isPayer", "closed"],

	computed: {
		answered() {
			return this.isJustAnswered !== null ? this.isJustAnswered : this.closed;
		}
	},

	created() {
		$bus.$on("questionAnswered", function() {
			this.isJustAnswered = 1;
		});
		this.fetchData();
	},

	methods: {
		checkAccept() {
			if ($bus.state.answer.answerIds.length < 10) {
				this.isAccept = !this.isAccept;
				var answer_id = {
					isAccept: this.isAccept,
					answerId: this.answerId
				};
				if (this.isAccept) {
					$bus.state.answer.answerIds.push(answer_id);
				} else {
					$bus.state.answer.answerIds = $bus.state.answer.answerIds.filter(
						item => item.answerId != this.answerId
					);
				}
				$bus.$emit("clickAnswer");
			}
		},
		favoriteQuestion() {
			this.question.favorited = !this.question.favorited;
			this.question.count_favorites += this.question.favorited ? 1 : -1;
			window.axios.get(
				window.tokenize("/api/favorite-question-" + this.question.id)
			);
		},
		reportQuestion() {
			this.question.reported = !this.question.reported;
			this.question.count_reports += this.question.reported ? 1 : -1;
			window.axios.get(
				window.tokenize("/api/report-question-" + this.question.id)
			);
		},
		reportAnswer() {
			this.answer.reported = !this.answer.reported;
			this.answer.count_reports += this.answer.reported ? 1 : -1;
			window.axios.get(window.tokenize("/api/report-answer-" + this.answer.id));
		},
		deleteAnswer() {
			//调用Vue.set来触发数据响应，deleted默认loaded的时候没这个属性...
			this.$set(this.answer, "deleted", !this.answer.deleted);
			window.axios.get(window.tokenize("/api/delete-answer-" + this.answer.id));
		},
		unlikeAnswer() {
			this.answer.unliked = !this.answer.unliked;
			this.answer.count_unlikes += this.answer.unliked ? 1 : -1;
			window.axios.get(window.tokenize("/api/unlike-answer-" + this.answer.id));
		},
		likeAnswer() {
			this.answer.liked = !this.answer.liked;
			this.answer.count_likes += this.answer.liked ? 1 : -1;
			window.axios.get(window.tokenize("/api/like-answer-" + this.answer.id));
		},
		inviteUser(user) {
			user.invited = 1;
			this.uninvited = _.filter(this.users, ["invited", 0]);
			window.axios.get(
				window.tokenize(
					"/api/question-" + this.questionId + "-invite-user-" + user.id
				)
			);
		},
		inviteAnswer() {
			this.showInvite = !this.showInvite;
		},
		showComment() {
			this.showComments = !this.showComments;
		},
		fetchData() {
			var _this = this;
			if (this.questionId) {
				//邀请
				window.axios
					.get(
						window.tokenize("/api/question-" + this.questionId + "-uninvited")
					)
					.then(function(response) {
						_this.users = response.data;
						_this.uninvited = _this.users;
					});
				//问题信息
				window.axios
					.get(window.tokenize("/api/question/" + this.questionId))
					.then(function(response) {
						_this.question = response.data;
						_this.loaded = true;
					});
			} else if (this.answerId) {
				//回答信息
				window.axios
					.get(window.tokenize("/api/answer/" + this.answerId))
					.then(function(response) {
						_this.answer = response.data;
						_this.loaded = true;
					});
			}
		}
	},

	data() {
		return {
			loaded: false,
			answer: null,
			question: null,
			showInvite: false,
			showComments: false,
			users: [],
			uninvited: [],
			isAccept: false,
			isJustAnswered: null
		};
	}
};
</script>

<style lang="scss">
.deleted-answer {
	opacity: 0.2;
}
.tool-cabinet {
	.bottom-tool {
		font-size: 0;
		margin: 20px 0;
		.action-btn {
			background: none;
			height: 20px;
			line-height: 20px;
			color: #969696;
			margin-right: 36px;
			font-size: 14px;
			&.no-margin {
				margin-right: 0;
			}
			i {
				margin-right: 4px;
			}
			&.answer-btn {
				color: #2b89ca;
			}
			&.like {
				&:hover {
					color: #d96a5f;
				}
				&.active {
					color: #d96a5f;
				}
			}
			&.report,
			&.delete {
				margin-right: 0;
			}
			&:hover {
				color: #515151;
			}
			@media screen and (max-width: 600px) {
				margin-right: 20px;
			}
			&.accept {
				margin-right: 20px;
				color: #d96a5f;
				i {
					margin-right: 0;
					&.chacha {
						display: none;
						font-size: 15px;
					}
				}
				&.already {
					i.chacha {
						display: none;
					}
					&:hover {
						i {
							display: none;
						}
						i.chacha {
							display: inline-block;
						}
						span {
							display: none;
						}
						&::after {
							content: "取消采纳";
						}
					}
				}
			}
		}
		&.question-tool {
			margin: 0 0 24px 0;
		}
	}
	.invite-answer {
		margin-bottom: 36px;
		margin-top: -11px;
		overflow: hidden;
		.invite-user {
			border-top: 1px solid #efefef;
			.invite-status {
				height: 50px;
				line-height: 50px;
				color: #666;
				font-size: 14px;
			}
			.invite-list {
				max-height: 338px;
				overflow: auto;
				border-top: 1px solid #f8f8f8;
				.note-info {
					width: 98%;
					margin-top: 20px;
					.btn-base {
						margin: 4px 0 0 10px;
						padding: 7px 18px;
						float: right;
						&.btn-follow {
							background-color: #2b89ca !important;
							border-color: #2b89ca !important;
						}
						i {
							font-size: 16px;
						}
					}
				}
			}
		}
	}
	.comment-container {
		background-color: #f7f7f7;
		border-radius: 2px;
		padding: 0 20px;
		.comment-item:last-of-type {
			border-bottom: none !important;
		}
		.btn-base.btn-handle {
			background-color: #2b89ca !important;
			border-color: #2b89ca !important;
		}
	}
}
</style>
