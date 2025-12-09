<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion · Elbiometria Solution</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f2f4fb;
            --card: #ffffff;
            --accent: #1e2d78;
            --accent-2: #15235d;
            --accent-soft: #e7e9f4;
            --muted: #53657c;
            --border: #d9dfed;
            --radius: 18px;
            --shadow: 0 18px 42px rgba(24, 38, 110, 0.12);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #0f1f2d;
            background:
                radial-gradient(120% 120% at 20% 16%, rgba(30, 45, 120, 0.12), transparent 46%),
                radial-gradient(120% 120% at 84% 8%, rgba(21, 35, 93, 0.12), transparent 38%),
                linear-gradient(160deg, #f9fbff 0%, #eef2f8 42%, #f9fbff 100%);
        }
        .hero {
            position: relative;
            min-height: 100vh;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 32px;
            padding: 48px 6vw;
            align-items: center;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(40% 40% at 20% 30%, rgba(15, 122, 217, 0.14), transparent 60%),
                radial-gradient(42% 42% at 82% 16%, rgba(11, 92, 171, 0.12), transparent 58%),
                linear-gradient(135deg, rgba(255,255,255,0.85), rgba(240,245,252,0.9));
            z-index: 0;
        }
        .blur { position: absolute; inset: 0; backdrop-filter: blur(2px); z-index: 0; }
        .card {
            position: relative;
            z-index: 1;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 32px;
            box-shadow: var(--shadow);
            max-width: 520px;
            width: 100%;
        }
        .logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 18px;
        }
        .logo img { width: 148px; height: auto; display: block; }
        .pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            background: var(--accent-soft);
            color: #111b49;
            border: 1px solid #c9cedf;
            font-weight: 600;
            font-size: 13px;
        }
        .title {
            margin: 18px 0 6px;
            font-size: 30px;
            font-weight: 700;
            letter-spacing: -0.4px;
            color: #0f1f2d;
        }
        .subtitle { margin: 0 0 22px; color: var(--muted); line-height: 1.45; }
        label { display: block; margin-bottom: 6px; font-weight: 600; color: #27364a; font-size: 14px; }
        input {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: #f7faff;
            color: #0f1f2d;
            font-size: 15px;
        }
        input::placeholder { color: #9ca3af; }
        .field { margin-bottom: 16px; }
        .row { display: flex; justify-content: space-between; align-items: center; gap: 12px; }
        .link { color: var(--accent); text-decoration: none; font-weight: 600; font-size: 13px; }
        .btn {
            width: 100%;
            border: none;
            border-radius: 12px;
            padding: 14px 16px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            color: #ffffff;
            background: var(--accent);
            box-shadow: 0 12px 22px rgba(24, 38, 110, 0.24);
            transition: transform 0.12s ease, box-shadow 0.12s ease, filter 0.12s ease;
        }
        .btn:hover { filter: brightness(1.03); }
        .btn:active { transform: translateY(1px); box-shadow: 0 8px 16px rgba(24, 38, 110, 0.2); }
        .status { margin-top: 12px; font-size: 13px; color: var(--muted); }
        .aside { position: relative; z-index: 1; color: #0f1f2d; }
        .aside-card {
            background: linear-gradient(135deg, rgba(30,45,120,0.14), rgba(21,35,93,0.22));
            border: 1px solid rgba(30,45,120,0.2);
            box-shadow: 0 18px 48px rgba(24,38,110,0.18);
            border-radius: 18px;
            padding: 24px 26px;
            backdrop-filter: blur(6px);
            max-width: 560px;
        }
        .flare {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(30, 45, 120, 0.12);
            color: #111b49;
            border: 1px solid rgba(30, 45, 120, 0.28);
            font-weight: 700;
            letter-spacing: 0.1px;
            box-shadow: 0 10px 24px rgba(24,38,110,0.16);
        }
        .headline {
            font-size: 34px;
            line-height: 1.2;
            margin: 0 0 10px;
            max-width: 520px;
            font-weight: 750;
            color: var(--accent);
            text-shadow: none;
        }
        .subline {
            margin: 0;
            color: #273454;
            max-width: 520px;
            line-height: 1.6;
            font-size: 16px;
        }
        .shadow-card { position: absolute; inset: 10%; background: rgba(30,45,120,0.08); filter: blur(34px); z-index: 0; }
        @media (max-width: 900px) {
            .hero { padding: 32px 18px; }
            .headline { font-size: 30px; }
        }
    </style>
</head>
<body>
<div class="hero">
    <div class="card">
        <div class="logo">
            <img src="/images/logo_elbiometria.png" alt="Elbiometria">
            <div class="pill">Elbiometria Solution</div>
        </div>
        <h1 class="title">Connexion Elbiometria Solution</h1>
        <form id="login-form">
            <div class="field">
                <label for="login">Identifiant</label>
                <input id="login" name="login" type="text" placeholder="ex: responsable@garage.dz" required>
            </div>
            <div class="field">
                <label for="password">Mot de passe</label>
                <input id="password" name="password" type="password" placeholder="••••••••" required>
            </div>
            <div class="row" style="justify-content: flex-end;">
                <span class="link" aria-disabled="true">Mot de passe oublié ?</span>
            </div>
            <div style="margin-top: 16px;">
                <button class="btn" type="submit" id="submit-btn">Se connecter</button>
            </div>
            <div class="status" id="status">Saisissez vos identifiants.</div>
        </form>
    </div>
    <div class="aside">
        <div class="aside-card">
            <div class="flare">👋 Bienvenue</div>
            <h2 class="headline">Elbiometria Solution</h2>
            <p class="subline">Plateforme de gestion claire et fiable. Retrouvez vos véhicules, chauffeurs et documents en un coup d'œil.</p>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const token = localStorage.getItem('token');
    if (token) {
        window.location.href = '/app';
    }

    const form = document.getElementById('login-form');
    const statusEl = document.getElementById('status');
    const submitBtn = document.getElementById('submit-btn');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        submitBtn.disabled = true;
        submitBtn.textContent = 'Connexion...';
        statusEl.textContent = 'Vérification des accès...';
        const payload = Object.fromEntries(new FormData(form).entries());
        try {
            const res = await axios.post('/api/auth/login', payload);
            const { token: apiToken } = res.data;
            localStorage.setItem('token', apiToken);
            statusEl.textContent = 'Connexion réussie, redirection en cours...';
            setTimeout(() => window.location.href = '/app', 350);
        } catch (error) {
            const message = error?.response?.data?.message || 'Identifiants invalides. Réessayez.';
            statusEl.textContent = message;
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Se connecter';
        }
    });
</script>
</body>
</html>
