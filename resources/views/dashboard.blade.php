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
<script type="module" src="{{ asset('js/app.js') }}?v={{ time() }}"></script>


</head>
<body>
<div class="veil" aria-hidden="true"></div>
<header>
    <div class="brand">
        <img class="logo-mark" src="/images/logo_elbiometria.png" alt="Parc Auto">
        <div class="brand-text">
            <div>Parc Auto Manager</div>
        </div>
    </div>
    <div class="actions">
        <button class="btn secondary" id="logout-btn" type="button">Déconnexion</button>
    </div>
</header>
<div class="layout">
    <nav>
        <h4>Menu</h4>
        <button class="nav-btn" data-target="overview">Tableau de bord</button>
        <button class="nav-btn" data-target="chauffeurs">Chauffeurs</button>
        <button class="nav-btn" data-target="vehicules">Véhicules</button>
        <div class="nav-dropdown">
            <button class="nav-btn" id="documents-dropdown-btn" type="button" data-target="documents">
                <span>Documents</span>
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
        <button class="nav-btn" data-target="utilisateurs">Utilisateurs</button>
        <button class="nav-btn" data-target="parametres">Paramètres</button>
    </nav>
    <main>
        @include('dashboard.sections.overview')
        @include('dashboard.sections.chauffeurs')
        @include('dashboard.sections.vehicules')
        @include('dashboard.sections.documents')
        @include('dashboard.sections.utilisateurs')
        @include('dashboard.sections.parametres')
    </main>
</div>

</body>
</html>
