<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Songchord - Your Musical Companion</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
            overflow-x: hidden;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header */
        .header {
            padding: 20px 0;
            position: relative;
            z-index: 100;
        }
        
        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 28px;
            font-weight: 600;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .logo::before {
            content: "🎵";
            font-size: 32px;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }
        
        .nav-links {
            display: flex;
            gap: 30px;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 10px 20px;
            border-radius: 25px;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
        }
        
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        /* Hero Section */
        .hero {
            text-align: center;
            padding: 80px 0;
            position: relative;
        }
        
        .hero h1 {
            font-size: 4rem;
            font-weight: 600;
            color: white;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            animation: fadeInUp 1s ease-out;
        }
        
        .hero p {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
            animation: fadeInUp 1s ease-out 0.2s both;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeInUp 1s ease-out 0.4s both;
        }
        
        .btn {
            padding: 15px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            border-radius: 50px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(255, 107, 107, 0.6);
        }
        
        .btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }
        
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        /* Features Section */
        .features {
            background: rgba(255, 255, 255, 0.95);
            padding: 80px 0;
            margin-top: 50px;
            backdrop-filter: blur(10px);
        }
        
        .features h2 {
            text-align: center;
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 50px;
            position: relative;
        }
        
        .features h2::after {
            content: '';
            width: 80px;
            height: 4px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 2px;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            margin-top: 60px;
        }
        
        .feature-card {
            background: white;
            padding: 40px 30px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(45deg, #667eea, #764ba2);
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            display: block;
        }
        
        .feature-card h3 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .feature-card p {
            color: #666;
            line-height: 1.6;
            font-size: 1rem;
        }
        
        /* Stats Section */
        .stats {
            background: rgba(0, 0, 0, 0.1);
            padding: 60px 0;
            backdrop-filter: blur(10px);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
        }
        
        .stat-item {
            text-align: center;
            color: white;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 600;
            display: block;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Footer */
        .footer {
            background: rgba(0, 0, 0, 0.2);
            padding: 40px 0;
            text-align: center;
            color: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }
        
        /* Floating Animation */
        .floating-notes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }
        
        .note {
            position: absolute;
            color: rgba(255, 255, 255, 0.1);
            font-size: 2rem;
            animation: float 20s infinite linear;
        }
        
        @keyframes float {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) rotate(360deg);
                opacity: 0;
            }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero p {
                font-size: 1rem;
                padding: 0 20px;
            }
            
            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 250px;
                justify-content: center;
            }
            
            .nav-links {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Floating Musical Notes -->
    <div class="floating-notes">
        <div class="note" style="left: 10%; animation-delay: 0s;">♪</div>
        <div class="note" style="left: 20%; animation-delay: 3s;">♫</div>
        <div class="note" style="left: 30%; animation-delay: 6s;">♪</div>
        <div class="note" style="left: 40%; animation-delay: 9s;">♬</div>
        <div class="note" style="left: 50%; animation-delay: 12s;">♪</div>
        <div class="note" style="left: 60%; animation-delay: 15s;">♫</div>
        <div class="note" style="left: 70%; animation-delay: 18s;">♪</div>
        <div class="note" style="left: 80%; animation-delay: 21s;">♬</div>
        <div class="note" style="left: 90%; animation-delay: 24s;">♪</div>
    </div>

    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="nav">
                <a href="/" class="logo">Songchord</a>
                <div class="nav-links">
                    <a href="#" class="nav-link">Browse Songs</a>
                    <a href="#" class="nav-link">API Docs</a>
                    <a href="#" class="nav-link">Login</a>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Welcome to Songchord</h1>
            <p>Your ultimate destination for song chords and musical collaboration. Discover, contribute, and share chord progressions with musicians worldwide.</p>
            <div class="cta-buttons">
                <a href="#" class="btn btn-primary">
                    🎸 Explore Songs
                </a>
                <a href="#" class="btn btn-secondary">
                    📝 API Documentation
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <h2>Powerful Features</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">🎵</div>
                    <h3>Vast Song Library</h3>
                    <p>Access thousands of songs with detailed chord progressions, organized by sections and lines for easy navigation.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">👥</div>
                    <h3>Collaborative Platform</h3>
                    <p>Contributors and admins can add new songs, create versions, and structure content with role-based permissions.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔧</div>
                    <h3>RESTful API</h3>
                    <p>Modern Laravel-powered API with authentication, CRUD operations, and structured endpoints for developers.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📱</div>
                    <h3>Mobile Friendly</h3>
                    <p>Responsive design that works perfectly on all devices, from desktop to mobile phones.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔐</div>
                    <h3>Secure Access</h3>
                    <p>Laravel Sanctum authentication ensures secure access with role-based permissions for contributors.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>Structured Content</h3>
                    <p>Songs organized with versions, sections, and lines - perfect for chord sheets and musical arrangements.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-number">1000+</span>
                    <span class="stat-label">Songs</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">500+</span>
                    <span class="stat-label">Contributors</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">10K+</span>
                    <span class="stat-label">Chord Progressions</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">24/7</span>
                    <span class="stat-label">API Access</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 ATA Songchord. Built with ❤️ using Laravel. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Add some interactive sparkle effects
        document.addEventListener('mousemove', function(e) {
            if (Math.random() > 0.95) {
                createSparkle(e.clientX, e.clientY);
            }
        });

        function createSparkle(x, y) {
            const sparkle = document.createElement('div');
            sparkle.style.position = 'fixed';
            sparkle.style.left = x + 'px';
            sparkle.style.top = y + 'px';
            sparkle.style.width = '4px';
            sparkle.style.height = '4px';
            sparkle.style.background = 'white';
            sparkle.style.borderRadius = '50%';
            sparkle.style.pointerEvents = 'none';
            sparkle.style.zIndex = '1000';
            sparkle.style.animation = 'sparkle 1s ease-out forwards';
            
            document.body.appendChild(sparkle);
            
            setTimeout(() => {
                sparkle.remove();
            }, 1000);
        }
        
        // Add sparkle keyframes
        const style = document.createElement('style');
        style.textContent = `
            @keyframes sparkle {
                0% {
                    opacity: 1;
                    transform: scale(1);
                }
                100% {
                    opacity: 0;
                    transform: scale(0) translateY(-20px);
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>