@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    You are logged in!
                    
                    <h2>本站最新注册用户:</h2>
                    <div class="list-group">
                        @foreach($users as $user)
                        <a href="/user/{{ $user->id }}" class="list-group-item active">
                            <h4 class="list-group-item-heading">{{ $user->name }}</h4>
                            <p class="list-group-item-text">{{ $user->email }}</p>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
