@if(Auth::check())
	@include('parts.header_user')
@else
	@include('parts.header_guest')
@endif

@push('scripts')
	@include('parts.js_for_header')
@endpush