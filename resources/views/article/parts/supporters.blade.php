@php
    $tips_count = $article->tips()->count();
@endphp
@if($tips_count)
<div class="supporter">
    <ul class="list-people">
        @foreach($article->tips()->with('user')->take(8)->get() as $tip)
        <li>
            <a target="_blank" href="/user/{{ $tip->user->id }}" class="avatar"><img src="{{ $tip->user->avatar() }}"></a>
        </li>
        @endforeach
    </ul>
    <span class="reward-user">等{{ $tips_count }}人</span>
</div>
@endif