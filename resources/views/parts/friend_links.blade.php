@php
	$links = [];
    if (Storage::exists("friend_links")) {
        $json  = Storage::get('friend_links');
        $links = json_decode($json, true);
    }
@endphp

@foreach($links as $link)
	<a href="{{ $link['website_domain'] }}" target="_blank">{{ $link['website_name'] }}</a><em> · </em>
@endforeach