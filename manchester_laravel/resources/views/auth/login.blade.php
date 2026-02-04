<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - IQC Sense</title>
    <link href="{{ asset('css/modern.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="bg-pattern"></div>

    <div class="login-container">
        <div class="login-card glass-panel">
            <div class="login-logo">
                <img src="{{ asset('uploads/logo.png') }}" alt="IQC Logo">
            </div>

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                
                <div class="form-group">
                    <input type="text" name="email" class="form-input" placeholder="Username / Email" required autofocus value="{{ old('email') }}">
                </div>

                <div class="form-group">
                    <input type="password" name="password" class="form-input" placeholder="Password" required>
                    @error('email')
                        <div class="error-msg visible">
                            <i class="fa-solid fa-triangle-exclamation"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn-primary">
                        Login <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
