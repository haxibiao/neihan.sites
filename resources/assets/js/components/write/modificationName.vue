<template>
    <div class="modal fade modification-name">
        <div class="modal-dialog simple">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">请输入新文集名</h4>
                </div>
                <div class="modal-body">
                  <input type="text" class="input_style" v-model="name">
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

  name: 'modificationName',

  computed: {
    name: {
      get() {
        return this.$store.state.currentCollection.name;
      },
      set(name) {
        this.$store.commit('RENAME_COLLECTION', name);
        console.log('set..');
        console.log(this.name);
      }
    }
  },

  methods: {
  	submit() {
  		this.$store.dispatch('renameCollection', { name: this.name });
  	}
  },

  data () {
    return {}
  }
}
</script>

<style lang="scss">
.modification-name {
    .modal-dialog.simple {
        width: 320px;
        .modal-header {
            padding: 15px 20px;
            .modal-title {
                font-weight: 500;
            }
        }
        .modal-body {
            padding: 20px;
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