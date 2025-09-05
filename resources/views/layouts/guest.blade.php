<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials.head')
    @stack('styles')
</head>
<body class="guest-layout">
    @include('layouts.partials.header')
    
    <main class="guest-content">
        @yield('content')
    </main>
    
    @include('layouts.partials.footer')
    @stack('scripts')
</body>
</html>