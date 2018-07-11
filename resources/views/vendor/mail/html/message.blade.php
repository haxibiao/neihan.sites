@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    {{-- Body --}}
    
    @php 
        $slot = str_replace('Reset Password', '重置密码', $slot);
        $slot = str_replace('Reset Password', '重置密码', $slot);
        $slot = str_replace('Reset Password', '重置密码', $slot);
        $slot = str_replace('Reset Password', '重置密码', $slot);
        $slot = str_replace('button-blue', 'button-red', $slot);
    @endphp

    <style type="text/css">
        .button-red {
            
        }
    </style>

    {!! $slot !!}

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        @endcomponent
    @endslot
@endcomponent
