<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name', 'Songchord') }} - @yield('title', 'Dashboard')</title>

<!-- Favicon -->
<link rel="icon" type="image/svg+xml" href="{{ secure_asset('music_favicon.svg') }}">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

<!-- Admin Styles -->
<link href="{{ asset('css/admin.css') }}" rel="stylesheet">

<!-- Additional page styles -->
@stack('styles')