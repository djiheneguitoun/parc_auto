<section class="panel section" id="overview">
    <h2>Tableau de bord</h2>
    <p>Vision rapide des métriques clés.</p>
    <div class="grid" id="metric-grid">
        <div class="card"><p class="muted">Chauffeurs</p><p class="metric" id="metric-chauffeurs">-</p><span class="badge">actifs</span></div>
        <div class="card"><p class="muted">Véhicules</p><p class="metric" id="metric-vehicules">-</p><span class="badge">total</span></div>
        <div class="card"><p class="muted">Documents</p><p class="metric" id="metric-documents">-</p><span class="badge">expirations</span></div>
        <div class="card"><p class="muted">Utilisateurs</p><p class="metric" id="metric-users">-</p><span class="badge">actifs</span></div>
                <div class="card"><p class="muted">Utilisateurs</p><p class="metric" id="metric-users">-</p><span class="badge">actifs</span></div>

    </div>

    <div class="grid charts-grid" id="charts-grid">
        <div class="card chart-card">
            <div class="card-head">
                <div>
                    <p class="muted">Documents</p>
                    <h3>Échéances < 30 jours</h3>
                </div>
                <span class="badge badge-soft">Par type</span>
            </div>
            <canvas id="chart-documents-soon" height="180"></canvas>
        </div>
        <div class="card chart-card">
            <div class="card-head">
                <div>
                    <p class="muted">Véhicules</p>
                    <h3>Coût par véhicule</h3>
                </div>
                <span class="badge badge-soft">Par catégorie</span>
            </div>
            <canvas id="chart-vehicules-cout" height="180"></canvas>
        </div>
    </div>
</section>
