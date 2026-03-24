<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TopScore</title>
    
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 640 512%22><path fill=%22%23ffc107%22 d=%22M622.3 271.1l-115.2-45L512 367.5c0 14.3-17.3 25.4-42.1 31.8l-1.9 .5c-43.2 10.6-96.1 16.2-148 16.2s-104.8-5.6-148-16.2l-1.9-.5c-24.8-6.4-42.1-17.5-42.1-31.8L128 226.1l-110.3 43.1C6.2 273.6 0 284 0 295.1V416c0 17.7 14.3 32 32 32s32-14.3 32-32V311.2l91.6 35.8C182.1 364.5 244.6 384 320 384s137.9-19.5 164.4-37l91.6-35.8V416c0 17.7 14.3 32 32 32s32-14.3 32-32V295.1c0-11.1-6.2-21.5-17.7-24zM320 32C143.3 32 0 89.3 0 160c0 62.1 114.9 114.6 270.8 126.1l-4.4 11.2c-5.7 14.6 1.5 31.1 16.1 36.8s31.1-1.5 36.8-16.1l9.1-23.3C326 294.9 323 295 320 295c176.7 0 320-57.3 320-128S496.7 32 320 32z%22/></svg>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            /* Matches the welcome and register page background perfectly */
            background: linear-gradient(-45deg, #4b6cb7, #182848, #667eea, #764ba2);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            /* Force black mouse arrow */
            cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path d="M7 2l12 11.2-5.8.5 3.3 7.3-2.2.9-3.2-7.4-4.4 5.2z" fill="black"/></svg>'), auto !important;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
            padding: 40px;
            transform: translateY(20px);
            opacity: 0;
            animation: slideUp 0.6s ease forwards;
        }

        @keyframes slideUp {
            to { transform: translateY(0); opacity: 1; }
        }

        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .auth-logo {
            font-size: 3.5rem;
            color: #ffd700;
            margin-bottom: 10px;
            text-shadow: 0 4px 10px rgba(255, 215, 0, 0.3);
        }

        .auth-title {
            font-weight: 800;
            color: #182848;
            font-size: 2rem;
        }

        /* Styling the inputs to look modern and fixing text color */
        input, .form-control {
            color: #000000 !important;       /* Makes the typed text black */
            caret-color: #000000 !important; /* Makes the blinking line black */
        }

        .form-floating > .form-control {
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            box-shadow: none;
        }

        .form-floating > .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        }

        .btn-custom {
            background-color: #ffd700;
            color: #212529;
            font-weight: bold;
            padding: 12px;
            border-radius: 50px;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
            width: 100%;
            font-size: 1.1rem;
            margin-top: 15px;
        }

        .btn-custom:hover {
            background-color: #ffed4a;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 215, 0, 0.5);
        }

        .login-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .login-link:hover {
            color: #182848;
        }

        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }

        /* Ensure links and buttons still show the standard pointer */
        button, a, .btn, .form-check-input, .form-check-label {
            cursor: pointer !important; 
        }
    </style>
</head>
<body>

    <div class="container px-3">
        <div class="d-flex justify-content-center">
            <div class="auth-card">
                
                <div class="auth-header">
                    <div class="auth-logo">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h2 class="auth-title">Welcome Back</h2>
                    <p class="text-muted">Log in to continue to TopScore.</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-floating mb-3">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="name@example.com" value="{{ old('email') }}" required autofocus>
                        <label for="email"><i class="far fa-envelope me-2 text-muted"></i>Email Address</label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password" required>
                        <label for="password"><i class="fas fa-lock me-2 text-muted"></i>Password</label>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label text-muted small" for="remember">
                                Remember me
                            </label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="small login-link fw-normal">Forgot password?</a>
                        @endif
                    </div>

                    <button type="submit" class="btn-custom">
                        <i class="fas fa-sign-in-alt me-2"></i> Log In
                    </button>

                    <div class="text-center mt-4 pt-2 border-top">
                        <p class="mb-0 text-muted small">Don't have an account? 
                            <a href="{{ route('register') }}" class="login-link ms-1">Register here</a>
                        </p>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>