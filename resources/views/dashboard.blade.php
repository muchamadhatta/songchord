<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Songchord</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
        }

        .navbar {
            background: linear-gradient(45deg, #667eea, #764ba2);
            padding: 1rem 2rem;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h1 {
            margin: 0;
            font-size: 1.5rem;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
        }

        .user-role {
            font-size: 0.8rem;
            opacity: 0.8;
        }

        .logout-form {
            margin: 0;
        }

        .logout-btn {
            background: transparent;
            border: 1px solid white;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: white;
            color: #764ba2;
        }

        .dashboard-content {
            padding: 2rem;
        }

        .welcome-card {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .stat-card h3 {
            margin: 0 0 0.5rem 0;
            color: #666;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 600;
            color: #764ba2;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>Songchord Dashboard</h1>
        <div class="navbar-right">
            <div class="user-info">
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-role">{{ Auth::user()->role }}</div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </nav>

    <main class="dashboard-content">
        <div class="welcome-card">
            <h2>Welcome back, {{ Auth::user()->name }}!</h2>
            <p>Last login: {{ Auth::user()->last_login_at ? Auth::user()->last_login_at->diffForHumans() : 'First time login' }}</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Your Role</h3>
                <div class="stat-value">{{ Auth::user()->role }}</div>
            </div>
            <div class="stat-card">
                <h3>Account Status</h3>
                <div class="stat-value">{{ Auth::user()->is_active ? 'Active' : 'Inactive' }}</div>
            </div>
            <div class="stat-card">
                <h3>Member Since</h3>
                <div class="stat-value">{{ Auth::user()->created_at->format('M Y') }}</div>
            </div>
        </div>
    </main>
</body>
</html>
