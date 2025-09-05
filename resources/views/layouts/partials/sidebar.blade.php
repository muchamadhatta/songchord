<aside class="admin-sidebar">
    <nav class="sidebar-nav">
        <ul class="nav-list">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="nav-icon">📊</span>
                    Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('songs.index') }}"
                    class="nav-link {{ request()->routeIs('songs.*') ? 'active' : '' }}">
                    <span class="nav-icon">🎵</span>
                    Songs
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <span class="nav-icon">🎤</span>
                    Artists
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <span class="nav-icon">🎸</span>
                    Albums
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <span class="nav-icon">🎼</span>
                    Genres
                </a>
            </li>

            @can('manage-users')
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <span class="nav-icon">👥</span>
                    Users
                </a>
            </li>
            @endcan

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <span class="nav-icon">📈</span>
                    Analytics
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <span class="nav-icon">⚙️</span>
                    Settings
                </a>
            </li>
        </ul>
    </nav>
</aside>