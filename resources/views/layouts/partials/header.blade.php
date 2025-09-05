<header class="main-header">
    <nav class="navbar">
        <div class="navbar-brand">
            <a href="{{ route('dashboard') }}" class="logo">
                <span>🎵</span>
                Songchord Admin
            </a>
        </div>
        
        <div class="user-menu">
            @auth
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role">{{ ucfirst(Auth::user()->role) }}</div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <span>🚪</span> Logout
                    </button>
                </form>
            @endauth
        </div>
    </nav>
</header>
