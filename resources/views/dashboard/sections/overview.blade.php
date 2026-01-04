<section class="panel section" id="overview">
    <div class="section-header">
        <div>
            <h2>Tableau de bord</h2>
            <p>Vision rapide des métriques clés et de l'état de votre flotte.</p>
        </div>
        <div class="section-actions">
            <button class="btn secondary sm" id="refresh-metrics" type="button" data-tooltip="Actualiser les données">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 16h5v5"/></svg>
                <span>Actualiser</span>
            </button>
        </div>
    </div>
    
    <div class="grid" id="metric-grid">
        <div class="card metric-card">
            <div class="metric-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <p class="muted">Chauffeurs</p>
            <p class="metric" id="metric-chauffeurs">-</p>
            <span class="badge">actifs</span>
        </div>
        <div class="card metric-card">
            <div class="metric-icon" style="background: var(--success-100); color: var(--success-600);">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>
            </div>
            <p class="muted">Véhicules</p>
            <p class="metric" id="metric-vehicules">-</p>
            <span class="badge">total</span>
        </div>
        <div class="card metric-card">
            <div class="metric-icon" style="background: var(--warning-100); color: var(--warning-600);">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><circle cx="10" cy="13" r="2"/><path d="m20 17-1.09-1.09a2 2 0 0 0-2.82 0L10 22"/></svg>
            </div>
            <p class="muted">Documents</p>
            <p class="metric" id="metric-documents">-</p>
            <span class="badge">expirations</span>
        </div>
        <div class="card metric-card">
            <div class="metric-icon" style="background: var(--info-100); color: var(--info-600);">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 21a8 8 0 0 0-16 0"/><circle cx="10" cy="8" r="5"/><path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3"/></svg>
            </div>
            <p class="muted">Utilisateurs</p>
            <p class="metric" id="metric-users">-</p>
            <span class="badge">actifs</span>
        </div>
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
