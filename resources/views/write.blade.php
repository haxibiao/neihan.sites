@extends('layouts.write')

@section('content')
   <write></write>
@stop

@push('scripts')
		<script>
		$(function(){
			$('.dropdown-toggle').dropdown();
    		$('[data-toggle="tooltip"]').tooltip();
    		$('[data-toggle="popover"]').popover();
		})
		</script>
@endpush