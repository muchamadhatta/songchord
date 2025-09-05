@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Welcome Section -->
    <div class="welcome-section">
        <div class="welcome-content">
            <h1 class="welcome-title">Welcome back, {{ Auth::user()->name }}! 🎵</h1>
            <p class="welcome-subtitle">
                Ready to manage your musical world? Let's make some beautiful music together!
            </p>
            <p>Last login: {{ Auth::user()->last_login_at ? Auth::user()->last_login_at->diffForHumans() : 'First time login' }}</p>
        </div>
    </div>

    <!-- Dashboard Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Songs</h3>
            <div class="stat-value">1,247</div>
        </div>
        
        <div class="stat-card">
            <h3>Artists</h3>
            <div class="stat-value">89</div>
        </div>
        
        <div class="stat-card">
            <h3>Albums</h3>
            <div class="stat-value">156</div>
        </div>
        
        <div class="stat-card">
            <h3>Active Users</h3>
            <div class="stat-value">{{ Auth::user()->is_active ? '1' : '0' }}</div>
        </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <div class="card-header">
                <h3 class="card-title">Recent Activity</h3>
                <div class="card-icon">📈</div>
            </div>
            <div class="card-value">+24%</div>
            <p class="card-description">Increase in user engagement this month</p>
        </div>
        
        <div class="dashboard-card">
            <div class="card-header">
                <h3 class="card-title">Popular Songs</h3>
                <div class="card-icon">🔥</div>
            </div>
            <div class="card-value">532</div>
            <p class="card-description">Total plays in the last 7 days</p>
        </div>
        
        <div class="dashboard-card">
            <div class="card-header">
                <h3 class="card-title">New Releases</h3>
                <div class="card-icon">🎶</div>
            </div>
            <div class="card-value">12</div>
            <p class="card-description">Songs added this week</p>
        </div>
    </div>
@endsection