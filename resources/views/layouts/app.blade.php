<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials.head')
    @stack('styles')
</head>
<body class="@yield('body-class')">
    @include('layouts.partials.header')
    
    <main class="main-content">
        @include('layouts.partials.breadcrumb')
        @include('layouts.partials.alerts')
        @yield('content')
    </main>
    
    @include('layouts.partials.footer')
    
    @stack('scripts')
</body>
</html>