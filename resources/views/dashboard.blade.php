<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parc Auto · Tableau</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="module" src="{{ asset('js/app.js') }}?v={{ time() }}"></script>
</head>
<body>
<div class="veil" aria-hidden="true"></div>
<header>
    <div class="brand">
        <img class="logo-mark" src="/images/logo_elbiometria.png" alt="Parc Auto">
        <div class="brand-text">
            <div>Parc Auto Manager</div>
            <div>Gestion de flotte</div>
        </div>
    </div>
    <div class="actions">
        <button class="btn secondary sm" id="logout-btn" type="button">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
            <span>Déconnexion</span>
        </button>
    </div>
</header>
<div class="layout">
    <nav>
        <h4>Menu Principal</h4>
        <button class="nav-btn" data-target="overview">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
            <span>Tableau de bord</span>
        </button>
        <button class="nav-btn" data-target="chauffeurs">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <span>Chauffeurs</span>
        </button>
        <button class="nav-btn" data-target="vehicules">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>
            <span>Véhicules</span>
        </button>
        <button class="nav-btn" data-target="rapports">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
            <span>Rapports</span>
        </button>
        <div class="nav-dropdown">
            <button class="nav-btn" id="documents-dropdown-btn" type="button" data-target="documents">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                <span>Opération</span>
            </button>
            <div class="nav-submenu" id="documents-submenu">
                <button class="nav-submenu-btn" data-target="documents" data-doc-type="assurance">Assurance</button>
                <button class="nav-submenu-btn" data-target="documents" data-doc-type="vignette">Vignette</button>
                <button class="nav-submenu-btn" data-target="documents" data-doc-type="controle">Contrôle</button>
                <button class="nav-submenu-btn" data-target="documents" data-doc-type="entretien">Entretien</button>
                <button class="nav-submenu-btn" data-target="documents" data-doc-type="reparation">Réparation</button>
                <button class="nav-submenu-btn" data-target="documents" data-doc-type="bon_essence">Bon d'essence</button>
            </div>
        </div>
        <div class="nav-dropdown">
            <button class="nav-btn" id="sinistres-dropdown-btn" type="button" data-target="sinistres">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                <span>Sinistres</span>
            </button>
            <div class="nav-submenu" id="sinistres-submenu">
                <button class="nav-submenu-btn" data-target="sinistres" data-sinistre-tab="tableau">Tableau de suivi</button>
                <button class="nav-submenu-btn" data-target="sinistres" data-sinistre-tab="assurance">Suivi assurance</button>
                <button class="nav-submenu-btn" data-target="sinistres" data-sinistre-tab="reparations">Suivi réparations</button>
                <button class="nav-submenu-btn" data-target="sinistres" data-sinistre-tab="stats">Statistiques</button>
            </div>
        </div>
        <button class="nav-btn" data-target="utilisateurs">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 21a8 8 0 0 0-16 0"/><circle cx="10" cy="8" r="5"/><path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3"/></svg>
            <span>Utilisateurs</span>
        </button>
        <button class="nav-btn" data-target="parametres">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
            <span>Paramètres</span>
        </button>
    </nav>
    <main>
        @include('dashboard.sections.overview')
        @include('dashboard.sections.chauffeurs')
        @include('dashboard.sections.vehicules')
        @include('dashboard.sections.rapports')
        @include('dashboard.sections.documents')
        @include('dashboard.sections.sinistres')
        @include('dashboard.sections.utilisateurs')
        @include('dashboard.sections.parametres')
    </main>
</div>
</body>
</html>
