<template>
	<div class="modal fade delete-notebook">
	    <div class="modal-dialog simple">
	        <div class="modal-content">
	            <div class="modal-body">
	             <p>确认删除文集《{{ noteName }}》，文章将被移动到回收站。</p>
	            </div>
	            <footer class="clearfix">
	            	<a class="submit btn-base btn-hollow btn-md" @click="submit">确认</a>
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
  	noteName() {
  		return this.$store.state.currentCollection.name;
  	}
  },

  methods: {
  	submit() {
  		$('.delete-notebook').modal('hide');
  		this.$store.dispatch('removeCollection',this.$store.state.currentCollection.id);
  	}
  },

  data () {
    return {

    }
  }
}
</script>

<style lang="scss">
.delete-notebook {
	.modal-dialog {
		.modal-body {
			p {
				font-size: 14px;
			}
		}
	}
}
</style>