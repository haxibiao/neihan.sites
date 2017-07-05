<a href="/user/{{ $user->id }}" class="thumbnail text-center">
  <img src="{{ get_avatar($user) }}" alt="" class="img img-circle">
  <p class="list-group-item-heading">{{ $user->name }}</p>
  {{-- <p class="list-group-item-text">{{ $user->email }}</p> --}}
</a>