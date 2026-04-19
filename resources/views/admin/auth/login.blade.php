<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - ARN Constructions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { background: #fff; border-radius: 20px; padding: 50px; width: 100%; max-width: 420px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        .login-card h2 { font-family: 'Playfair Display', serif; color: #1a1a2e; text-align: center; margin-bottom: 5px; }
        .login-card h2 span { color: #b8860b; }
        .login-card .subtitle { text-align: center; color: #888; font-size: 14px; margin-bottom: 30px; }
        .login-card .form-control { padding: 12px 15px; border-radius: 10px; border: 1px solid #e0e0e0; }
        .login-card .form-control:focus { border-color: #b8860b; box-shadow: 0 0 0 0.2rem rgba(184,134,11,0.15); }
        .login-card .btn-login { background: linear-gradient(135deg, #b8860b, #d4a843); color: #fff; border: none; padding: 12px; border-radius: 10px; font-weight: 600; width: 100%; transition: all 0.3s; }
        .login-card .btn-login:hover { transform: translateY(-2px); box-shadow: 0 5px 20px rgba(184,134,11,0.4); }
        .login-card .logo-icon { width: 70px; height: 70px; border-radius: 50%; background: linear-gradient(135deg, #b8860b, #d4a843); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }
        .login-card .logo-icon i { color: #fff; font-size: 2rem; }
    </style>
</head>
<body>
    <div class="login-card">
        <img src="{{ asset('images/logo.jpeg') }}" alt="ARN Constructions" style="height: 80px; margin-bottom: 15px; border-radius: 50%;">
        <h2><span>ARN</span> Admin</h2>
        <p class="subtitle">Sign in to your super admin panel</p>

        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="admin@arnconstructions.com" required autofocus>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                </div>
            </div>
            <button type="submit" class="btn btn-login"><i class="fas fa-sign-in-alt me-2"></i>Sign In</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
