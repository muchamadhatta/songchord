<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials.head')
    @stack('styles')
</head>
<body class="admin-layout">
    @include('layouts.partials.header')
    
    <div class="admin-container">
        @include('layouts.partials.sidebar')
        
        <main class="admin-content">
            @include('layouts.partials.breadcrumb')
            @include('layouts.partials.alerts')
            @yield('content')
        </main>
    </div>
    
    @stack('scripts')
</body>
</html>