<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials.head')
    @stack('styles')
</head>
<body class="auth-layout">
    <div class="auth-container">
        @yield('content')
    </div>
    @stack('scripts')
</body>
</html>