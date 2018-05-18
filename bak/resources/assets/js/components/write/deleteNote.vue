<template>
  <div class="modal fade delete-note">
      <div class="modal-dialog simple">
          <div class="modal-content">
              <div class="modal-body">
               <p>确认删除文章《{{ article.title }}》，文章将被移动到回收站，您可以在那里恢复它。60天后将被彻底删除。</p>
              </div>
              <footer class="clearfix">
                <a class="submit btn_base btn_hollow btn_follow_xs" @click="submit">确认</a>
                <a class="cancel" data-dismiss="modal" aria-label="Close" href="javascript:;">取消</a>
              </footer>
          </div>
      </div>
  </div>
</template>

<script>
export default {

  name: 'deleteNotebook',

  computed: {
    article() {
      return this.$store.state.currentArticle ? this.$store.state.currentArticle : {};
    }
  },

  methods: {
    submit() {
      this.$store.dispatch('removeArticle');
      $('.delete-note').modal('hide');
    }
  },

  data () {
    return {

    }
  }
}
</script>

<style lang="scss">
.delete-note {
	.modal-dialog.simple {
        width: 320px;
        .modal-body {
            padding: 20px;
            p {
				font-size: 14px;
			}
        }
        footer {
            padding: 16px;
            .submit,.cancel {
                float: right;
                height: 30px;
                line-height: 20px;
                padding: 4px 12px;
                font-size: 14px;
                min-width: auto;
            }
            .cancel {
                color: #969696;
                &:hover {
                    color: unset;
                }
            }
        }
    }
}
</style>