@foreach (['danger', 'warning', 'success', 'info'] as $msg)
  @if(session()->has($msg))
    <div class="flash-message">
      <p class="alert alert-{{ $msg }} text-center">
        {{ session()->get($msg) }}
      </p>
    </div>
  @endif
@endforeach