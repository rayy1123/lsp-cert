<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator - LSP System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/astro-dark.css') }}">
    <style>
        .login-wrap {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 1.5rem;
            background: radial-gradient(circle at 50% 0%, rgba(255, 93, 1, 0.08), transparent 40%), var(--bg-canvas);
        }
        .login-box {
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>
<body>
    <div class="login-wrap">
        <div class="astro-card login-box">
            <div style="text-align: center; margin-bottom: 2rem;">
                <div class="brand-icon" style="margin: 0 auto 1rem; width: 44px; height: 44px; font-size: 1.25rem;">▲</div>
                <h1 style="font-size: 1.25rem; font-weight: 700;">LSP CERTIFICATION</h1>
                <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">Portal Administrator LSP / TUK</p>
            </div>

            @if($errors->has('login'))
                <div style="background: rgba(244, 63, 94, 0.1); border: 1px solid rgba(244, 63, 94, 0.2); color: var(--accent-rose); padding: 0.75rem; border-radius: var(--radius-sm); font-size: 0.8rem; margin-bottom: 1.25rem;">
                    {{ $errors->first('login') }}
                </div>
            @endif

            <form action="{{ route('lsp.login.post') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Username / Email</label>
                    <input type="text" name="email" class="form-control" value="admin" required autofocus>
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" value="admin123" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 0.75rem;">
                    Masuk Portal LSP
                </button>
            </form>

            <div style="margin-top: 1.5rem; text-align: center; font-size: 0.75rem; color: var(--text-dim); font-family: var(--font-mono);">
                LSP Certification System &copy; 2026
            </div>
        </div>
    </div>
</body>
</html>
