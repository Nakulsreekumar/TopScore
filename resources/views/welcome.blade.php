<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TopScore - The Ultimate Quiz Platform</title>
    
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 640 512%22><path fill=%22%23ffc107%22 d=%22M622.3 271.1l-115.2-45L512 367.5c0 14.3-17.3 25.4-42.1 31.8l-1.9 .5c-43.2 10.6-96.1 16.2-148 16.2s-104.8-5.6-148-16.2l-1.9-.5c-24.8-6.4-42.1-17.5-42.1-31.8L128 226.1l-110.3 43.1C6.2 273.6 0 284 0 295.1V416c0 17.7 14.3 32 32 32s32-14.3 32-32V311.2l91.6 35.8C182.1 364.5 244.6 384 320 384s137.9-19.5 164.4-37l91.6-35.8V416c0 17.7 14.3 32 32 32s32-14.3 32-32V295.1c0-11.1-6.2-21.5-17.7-24zM320 32C143.3 32 0 89.3 0 160c0 62.1 114.9 114.6 270.8 126.1l-4.4 11.2c-5.7 14.6 1.5 31.1 16.1 36.8s31.1-1.5 36.8-16.1l9.1-23.3C326 294.9 323 295 320 295c176.7 0 320-57.3 320-128S496.7 32 320 32z%22/></svg>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* --- STYLES FOR WELCOME PAGE ONLY --- */
        @if(Route::is('home'))
            body {
                margin: 0;
                padding: 0;
                /* Animated Gradient Background */
                background: linear-gradient(-45deg, #4b6cb7, #182848, #667eea, #764ba2);
                background-size: 400% 400%;
                animation: gradientBG 15s ease infinite;
                color: white;
                overflow-x: hidden;
                position: relative;
                min-height: 100vh;
            }

            @keyframes gradientBG {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }

            /* Floating Particles Animation (Glass squares/circles) */
            .particles {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                overflow: hidden;
                z-index: 0;
                margin: 0;
                padding: 0;
            }

            .particles li {
                position: absolute;
                display: block;
                list-style: none;
                width: 20px;
                height: 20px;
                background: rgba(255, 255, 255, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.2);
                backdrop-filter: blur(5px);
                animation: floatUp 25s linear infinite;
                bottom: -150px;
                border-radius: 20%;
            }

            .particles li:nth-child(1) { left: 25%; width: 80px; height: 80px; animation-delay: 0s; }
            .particles li:nth-child(2) { left: 10%; width: 20px; height: 20px; animation-delay: 2s; animation-duration: 12s; }
            .particles li:nth-child(3) { left: 70%; width: 20px; height: 20px; animation-delay: 4s; }
            .particles li:nth-child(4) { left: 40%; width: 60px; height: 60px; animation-delay: 0s; animation-duration: 18s; border-radius: 50%; }
            .particles li:nth-child(5) { left: 65%; width: 20px; height: 20px; animation-delay: 0s; }
            .particles li:nth-child(6) { left: 75%; width: 110px; height: 110px; animation-delay: 3s; border-radius: 50%; }
            .particles li:nth-child(7) { left: 35%; width: 150px; height: 150px; animation-delay: 7s; }
            .particles li:nth-child(8) { left: 50%; width: 25px; height: 25px; animation-delay: 15s; animation-duration: 45s; }
            .particles li:nth-child(9) { left: 20%; width: 15px; height: 15px; animation-delay: 2s; animation-duration: 35s; border-radius: 50%; }
            .particles li:nth-child(10) { left: 85%; width: 150px; height: 150px; animation-delay: 0s; animation-duration: 11s; }

            @keyframes floatUp {
                0% { transform: translateY(0) rotate(0deg); opacity: 1; border-radius: 20%; }
                100% { transform: translateY(-1000px) rotate(720deg); opacity: 0; border-radius: 50%; }
            }

            /* Main Content Container Layering */
            .content-wrapper {
                position: relative;
                z-index: 1;
            }
            
            /* Floating Animation for Logo */
            @keyframes floatLogo {
                0% { transform: translateY(0px); }
                50% { transform: translateY(-15px); }
                100% { transform: translateY(0px); }
            }

            /* Fade In Animation for Text */
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
            }

            .hero-logo {
                font-size: 5.5rem;
                color: #ffd700; /* Gold */
                text-shadow: 0 10px 20px rgba(0,0,0,0.3);
                animation: floatLogo 3.5s ease-in-out infinite;
                margin-bottom: 10px;
            }

            .hero-title {
                font-weight: 800;
                font-size: 4rem;
                text-shadow: 0 4px 10px rgba(0,0,0,0.2);
                animation: fadeIn 1s ease-out;
            }

            .hero-subtitle {
                font-size: 1.2rem;
                opacity: 0.9;
                margin-bottom: 40px;
                line-height: 1.6;
                animation: fadeIn 1.5s ease-out;
            }

            /* Glassmorphism Feature Cards */
            .feature-card {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(15px);
                -webkit-backdrop-filter: blur(15px);
                border: 1px solid rgba(255, 255, 255, 0.3);
                border-radius: 20px;
                padding: 30px 20px;
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                color: white;
                height: 100%;
                box-shadow: 0 15px 25px rgba(0,0,0,0.1);
            }

            .feature-card:hover {
                transform: translateY(-15px) scale(1.03);
                background: rgba(255, 255, 255, 0.2);
                box-shadow: 0 25px 35px rgba(0,0,0,0.2);
                border-color: #ffd700;
            }

            .btn-custom {
                background-color: #ffd700;
                color: #212529;
                font-weight: bold;
                padding: 14px 35px;
                border-radius: 50px;
                border: none;
                transition: all 0.3s ease;
                box-shadow: 0 8px 20px rgba(255, 215, 0, 0.4);
            }
            .btn-custom:hover {
                background-color: #ffed4a;
                transform: translateY(-3px);
                box-shadow: 0 12px 25px rgba(255, 215, 0, 0.6);
            }
            .btn-outline-custom {
                border: 2px solid white;
                color: white;
                font-weight: bold;
                padding: 12px 35px;
                border-radius: 50px;
                transition: all 0.3s ease;
            }
            .btn-outline-custom:hover {
                background-color: white;
                color: #764ba2;
                transform: translateY(-3px);
            }

        @else
            /* --- STYLES FOR DASHBOARDS --- */
            body {
                background-color: #f4f6f9; /* Soft Light Grey */
                color: #333;
            }
            .navbar-custom {
                background: linear-gradient(90deg, #4b6cb7 0%, #182848 100%);
                padding: 15px 0;
            }
            .navbar-brand {
                color: white !important;
                font-weight: 800;
                font-size: 1.6rem;
                letter-spacing: 1px;
            }
        @endif
    </style>
</head>
<body>

    @if(Route::is('home'))
        <ul class="particles">
            <li></li><li></li><li></li><li></li><li></li>
            <li></li><li></li><li></li><li></li><li></li>
        </ul>

        <div class="container text-center content-wrapper" style="padding-top: 10vh; padding-bottom: 50px;">
            
            <div class="hero-logo">
                <i class="fas fa-graduation-cap"></i>
            </div>

            <h1 class="hero-title mb-3">TopScore</h1>
            
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <p class="hero-subtitle">
                        An intelligent quiz management system designed to bridge the gap between teaching and learning. 
                        Create exams, track progress, and achieve academic excellence.
                    </p>
                </div>
            </div>

            <div class="mb-5" style="animation: fadeIn 2s ease-out;">
                @auth
                    @php
                        // Determine which dashboard to send the user to
                        $dashboardRoute = route('student.dashboard');
                        if(Auth::user()->role == 'admin') $dashboardRoute = route('admin.dashboard');
                        if(Auth::user()->role == 'teacher') $dashboardRoute = route('teacher.dashboard');
                    @endphp
                    <a href="{{ $dashboardRoute }}" class="btn btn-custom btn-lg">
                       <i class="fas fa-tachometer-alt me-2"></i> Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-custom btn-lg me-3">
                        <i class="fas fa-sign-in-alt me-2"></i> Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-custom btn-lg">
                        <i class="fas fa-user-plus me-2"></i> Register
                    </a>
                @endauth
            </div>

            <div class="row mt-5" style="animation: fadeIn 2.5s ease-out;">
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="mb-3">
                            <i class="fas fa-chalkboard-teacher fa-3x text-warning"></i>
                        </div>
                        <h4 class="fw-bold">For Teachers</h4>
                        <p class="small mb-0 opacity-75">Create secure quizzes, set timers, bulk-import questions via CSV, and auto-grade students instantly.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="mb-3">
                            <i class="fas fa-user-graduate fa-3x" style="color: #4df0ff;"></i>
                        </div>
                        <h4 class="fw-bold">For Students</h4>
                        <p class="small mb-0 opacity-75">Join instantly with a unique code, take interactive timed tests, and track your historical performance.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="mb-3">
                            <i class="fas fa-chart-pie fa-3x" style="color: #69f0ae;"></i>
                        </div>
                        <h4 class="fw-bold">Real-Time Insights</h4>
                        <p class="small mb-0 opacity-75">Receive instant grading, detailed score breakdowns, and transparent result tracking to boost motivation.</p>
                    </div>
                </div>
            </div>

            <footer class="mt-5 text-white-50" style="animation: fadeIn 3s ease-out;">
                <small>&copy; {{ date('Y') }} TopScore Project. Developed for Academic Excellence.</small>
            </footer>
        </div>

    @else
        <nav class="navbar navbar-expand-md navbar-custom shadow-sm">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="/">
                    <i class="fas fa-graduation-cap text-warning me-2"></i> TopScore
                </a>
                
                @auth
                <div class="d-flex align-items-center ms-auto">
                    <span class="text-white me-3 d-none d-md-inline-block opacity-75">
                        <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-light text-primary fw-bold rounded-pill px-3">
                            <i class="fas fa-sign-out-alt me-1"></i> Logout
                        </button>
                    </form>
                </div>
                @endauth
            </div>
        </nav>

        @yield('content')

    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>