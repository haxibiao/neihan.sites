<a href="/user/{{ $user->id }}" class="thumbnail">
  <img src="{{ $user->avatar() }}" alt="" class="img img-circle">
  <h4 class="list-group-item-heading">{{ $user->name }}</h4>
  {{-- <p class="list-group-item-text">{{ $user->email }}</p> --}}
</a>