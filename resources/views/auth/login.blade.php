<!DOCTYPE html>
<html lang="es" data-theme="system">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistema Restaurante</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <!-- Icon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <style>
        :root {
            --primary: #5a54ea;            /* Azul violeta para botones principales */
            --primary-dark: #423fc7;
            --bg-light: #f6f8fa;
            --bg-dark: #181d29;
            --text-light: #222;
            --text-dark: #eaeaea;
            --card-bg-light: #fff;
            --card-bg-dark: #23283a;
            --border-radius: 16px;
            --input-bg-light: #ececec;
            --input-bg-dark: #22263b;
            --input-border: #b0b5ca;
            --error: #e53935;
            --accent: #21c87a;            /* Verde acento (éxito) */
        }
        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: var(--bg-light);
            color: var(--text-light);
            transition: background 0.3s, color 0.3s;
        }
        [data-theme='dark'] body {
            background: var(--bg-dark);
            color: var(--text-dark);
        }
        .login-bg {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(120deg, var(--primary) 0%, #fff0 100%);
        }
        .login-card {
            background: var(--card-bg-light);
            border-radius: var(--border-radius);
            box-shadow: 0 8px 40px rgba(90,84,234,0.18);
            padding: 42px 36px 32px 36px;
            width: 100%;
            max-width: 370px;
            transition: background 0.3s;
        }
        [data-theme='dark'] .login-card {
            background: var(--card-bg-dark);
            box-shadow: 0 8px 32px rgba(90,84,234,0.24);
        }
        .login-title {
            font-size: 2.1rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--primary);
            text-align: center;
            letter-spacing: 1px;
        }
        .subtitle {
            text-align: center;
            color: #6070a0;
            font-size: 1rem;
            margin-bottom: 26px;
        }
        [data-theme='dark'] .subtitle {
            color: #aebbe6;
        }
        .form-group {
            margin-bottom: 18px;
        }
        label {
            display: block;
            font-weight: 500;
            margin-bottom: 5px;
        }
        input[type="email"], input[type="password"], select {
            width: 100%;
            padding: 11px 13px;
            border-radius: 8px;
            border: 1px solid var(--input-border);
            background: var(--input-bg-light);
            font-size: 1rem;
            font-family: inherit;
            transition: background 0.3s, color 0.3s;
        }
        [data-theme='dark'] input[type="email"], [data-theme='dark'] input[type="password"], [data-theme='dark'] select {
            background: var(--input-bg-dark);
            color: var(--text-dark);
            border: 1px solid #40476f;
        }
        select:focus, input:focus {
            outline: 2px solid var(--primary);
        }
        .login-btn {
            width: 100%;
            padding: 12px 0;
            background: var(--primary);
            color: #fff;
            font-size: 1.1rem;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 8px;
            box-shadow: 0 2px 14px rgba(90,84,234,0.11);
            letter-spacing: 0.5px;
            transition: background 0.25s;
        }
        .login-btn:hover, .login-btn:focus {
            background: var(--primary-dark);
        }
        .theme-toggle {
            display: flex;
            justify-content: flex-end;
            position: absolute;
            top: 18px;
            right: 24px;
        }
        .theme-toggle button {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--primary);
            font-size: 1.4rem;
            transition: color 0.3s;
        }
        .error-list {
            margin: 0 0 14px 0;
            padding: 0;
            color: var(--error);
            font-size: 0.97rem;
            list-style: none;
        }
        .restaurant-logo {
            display: block;
            margin: 0 auto 18px auto;
            width: 84px;
            aspect-ratio: 1/1;
            border-radius: 50%;
            background: #fff3;
            object-fit: cover;
            box-shadow: 0 2px 12px rgba(90,84,234,0.10);
        }
        /* Colorful accent for focus */
        input:focus, select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 2px #21c87a44;
        }
        @media (max-width: 500px) {
            .login-card { padding: 24px 8px 18px 8px; }
            .login-title { font-size: 1.45rem; }
            .restaurant-logo { width: 56px; }
        }
    </style>
    <script>
        // Theme toggle logic
        document.addEventListener('DOMContentLoaded', () => {
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const root = document.documentElement;

            function setTheme(theme) {
                root.setAttribute('data-theme', theme);
                localStorage.setItem('theme', theme);
            }
            // Initial theme
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                setTheme(savedTheme);
            } else {
                setTheme(prefersDark ? 'dark' : 'light');
            }
            // Toggle theme on click
            window.toggleTheme = () => {
                const current = root.getAttribute('data-theme');
                setTheme(current === 'dark' ? 'light' : 'dark');
            };
        });
    </script>
</head>
<body>
    <div class="login-bg">
        <div class="theme-toggle">
            <button type="button" onclick="toggleTheme()" title="Cambiar tema">
                <span id="theme-icon">&#9788;</span>
            </button>
        </div>
        <form method="POST" action="{{ route('login') }}" class="login-card" autocomplete="off">
            @csrf
            <img src="{{ asset('images/logo-ofummelli-login.png') }}"
                 alt="Logo Restaurante"
                 class="restaurant-logo"
                 onerror="this.style.display='none';"
            >
            <div class="login-title">Bienvenido</div>
            <div class="subtitle">King's-Throne | OFUMMELLI</div>
            @if ($errors->any())
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            <div class="form-group">
                <label for="email">Email:</label>
                <input autofocus type="email" name="email" id="email" required autocomplete="username" placeholder="usuario@ejemplo.com" value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" name="password" id="password" required autocomplete="current-password" placeholder="Contraseña">
            </div>
            <div class="form-group">
                <label for="area">Área de trabajo:</label>
                <select name="area" id="area" required>
                    <option value="">Seleccione área</option>
                    @foreach($areas as $key => $nombre)
                        <option value="{{ $key }}" @if(old('area') === $key) selected @endif>{{ $nombre }}</option>
                    @endforeach
                </select>
            </div>
            <button class="login-btn" type="submit">Iniciar sesión</button>
        </form>
    </div>
    <script>
        // Dynamic icon for theme (sun/moon)
        document.addEventListener('DOMContentLoaded', function() {
            const icon = document.getElementById('theme-icon');
            function updateIcon() {
                icon.textContent = document.documentElement.getAttribute('data-theme') === 'dark' ? '🌙' : '☀️';
            }
            updateIcon();
            const observer = new MutationObserver(updateIcon);
            observer.observe(document.documentElement, { attributes: true });
        });
    </script>
</body>
</html>