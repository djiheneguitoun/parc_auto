<section class="panel section" id="interventions">
    <!-- Panel: Tableau des interventions -->
    <div class="intervention-panel active" data-intervention-panel="tableau">
        <div class="section-header">
            <div>
                <h2>
                    <span class="icon-box" style="background: var(--accent-soft); color: var(--accent);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                    </span>
                    Historique des interventions
                </h2>
                <p>Gérez l'ensemble des entretiens et réparations de votre flotte.</p>
            </div>
            <div class="section-actions">
                <button class="btn primary" id="open-intervention-modal" type="button">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                    Nouvelle intervention
                </button>
            </div>
        </div>

        <div class="card table-card">
            <div class="section-subheader">
                <h3>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    Liste des interventions
                </h3>
                <div class="filter-actions">
                    <div class="custom-select intervention-filter" data-name="intervention-filter-type">
                        <button type="button" class="custom-select__trigger selected" aria-haspopup="listbox" aria-expanded="false">
                            <span class="custom-select__value">Tous les types</span>
                            <span class="custom-select__arrow">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                            </span>
                        </button>
                        <ul class="custom-select__options" role="listbox" tabindex="-1">
                            <li role="option" data-value="" aria-selected="true">Tous les types</li>
                            <li role="option" data-value="ENT">Entretien</li>
                            <li role="option" data-value="REP">Réparation</li>
                        </ul>
                        <input type="hidden" id="intervention-filter-type" name="intervention_filter_type" value="">
                    </div>
                    <div class="custom-select intervention-filter" data-name="intervention-filter-vehicule">
                        <button type="button" class="custom-select__trigger selected" aria-haspopup="listbox" aria-expanded="false">
                            <span class="custom-select__value">Tous les véhicules</span>
                            <span class="custom-select__arrow">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                            </span>
                        </button>
                        <ul class="custom-select__options" role="listbox" tabindex="-1">
                            <li role="option" data-value="" aria-selected="true">Tous les véhicules</li>
                        </ul>
                        <input type="hidden" id="intervention-filter-vehicule" name="intervention_filter_vehicule" value="">
                    </div>
                    <div class="custom-select intervention-filter" data-name="intervention-filter-categorie">
                        <button type="button" class="custom-select__trigger selected" aria-haspopup="listbox" aria-expanded="false">
                            <span class="custom-select__value">Toutes catégories</span>
                            <span class="custom-select__arrow">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                            </span>
                        </button>
                        <ul class="custom-select__options" role="listbox" tabindex="-1">
                            <li role="option" data-value="" aria-selected="true">Toutes catégories</li>
                        </ul>
                        <input type="hidden" id="intervention-filter-categorie" name="intervention_filter_categorie" value="">
                    </div>
                    <span class="stat-badge" id="interventions-count">
                        <span class="count">0</span> interventions
                    </span>
                </div>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 100px;">Date</th>
                            <th style="width: 130px;">Véhicule</th>
                            <th style="width: 80px;">Type</th>
                            <th style="width: 200px;">Opération</th>
                            <th style="width: 90px;">Catégorie</th>
                            <th style="width: 80px;">Km</th>
                            <th style="width: 100px;">Coût</th>
                            <th style="width: 80px;">Statut</th>
                            <th style="width: 80px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="intervention-rows"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Panel: Alertes & Suivi -->
    <div class="intervention-panel" data-intervention-panel="alertes">
        <div class="section-header">
            <div>
                <h2>
                    <span class="icon-box" style="background: var(--warning-100); color: var(--warning-600);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    </span>
                    Alertes & Échéances
                </h2>
                <p>Suivez les entretiens à venir et les échéances dépassées.</p>
            </div>
            <div class="section-actions">
                <button class="btn secondary" id="refresh-alertes" type="button">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/></svg>
                    Actualiser
                </button>
            </div>
        </div>

        <!-- KPIs Alertes -->
        <div class="stats-kpi-grid">
            <div class="kpi-card kpi-danger">
                <div class="kpi-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
                <div class="kpi-content">
                    <span class="kpi-value" id="alertes-depassees">0</span>
                    <span class="kpi-label">Échéances dépassées</span>
                </div>
            </div>
            <div class="kpi-card kpi-warning">
                <div class="kpi-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>
                </div>
                <div class="kpi-content">
                    <span class="kpi-value" id="alertes-proches">0</span>
                    <span class="kpi-label">Échéances à venir (30j)</span>
                </div>
            </div>
            <div class="kpi-card kpi-success">
                <div class="kpi-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <div class="kpi-content">
                    <span class="kpi-value" id="alertes-ok">0</span>
                    <span class="kpi-label">Véhicules à jour</span>
                </div>
            </div>
        </div>

        <!-- Tableau des alertes -->
        <div class="card table-card">
            <div class="section-subheader">
                <h3>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Entretiens planifiés
                </h3>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 130px;">Véhicule</th>
                            <th style="width: 200px;">Opération</th>
                            <th style="width: 100px;">Dernier km</th>
                            <th style="width: 110px;">Dernière date</th>
                            <th style="width: 100px;">Prochain km</th>
                            <th style="width: 110px;">Prochaine date</th>
                            <th style="width: 100px;">Statut</th>
                            <th style="width: 80px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="alertes-rows"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Panel: Catalogue Opérations -->
    <div class="intervention-panel" data-intervention-panel="catalogue">
        <div class="section-header">
            <div>
                <h2>
                    <span class="icon-box" style="background: var(--info-100); color: var(--info-600);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                    </span>
                    Catalogue des opérations
                </h2>
                <p>Gérez les types d'opérations d'entretien et de réparation.</p>
            </div>
            <div class="section-actions">
                <button class="btn secondary" id="open-categorie-modal" type="button">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                    Nouvelle catégorie
                </button>
                <button class="btn primary" id="open-operation-modal" type="button">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                    Nouvelle opération
                </button>
            </div>
        </div>

        <!-- Catégories -->
        <div class="card table-card">
            <div class="section-subheader">
                <h3>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                    Catégories techniques
                </h3>
                <span class="stat-badge" id="categories-count">
                    <span class="count">0</span> catégories
                </span>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 100px;">Code</th>
                            <th>Libellé</th>
                            <th style="width: 100px;">Opérations</th>
                            <th style="width: 80px;">Actif</th>
                            <th style="width: 80px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="categories-rows"></tbody>
                </table>
            </div>
        </div>

        <!-- Opérations -->
        <div class="card table-card">
            <div class="section-subheader">
                <h3>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                    Opérations
                </h3>
                <div class="filter-actions">
                    <div class="custom-select" data-name="catalogue-filter-type">
                        <button type="button" class="custom-select__trigger selected" aria-haspopup="listbox" aria-expanded="false">
                            <span class="custom-select__value">Tous les types</span>
                            <span class="custom-select__arrow">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                            </span>
                        </button>
                        <ul class="custom-select__options" role="listbox" tabindex="-1">
                            <li role="option" data-value="" aria-selected="true">Tous les types</li>
                            <li role="option" data-value="ENT">Entretien</li>
                            <li role="option" data-value="REP">Réparation</li>
                        </ul>
                        <input type="hidden" id="catalogue-filter-type" name="catalogue_filter_type" value="">
                    </div>
                    <span class="stat-badge" id="operations-count">
                        <span class="count">0</span> opérations
                    </span>
                </div>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 100px;">Code</th>
                            <th>Libellé</th>
                            <th style="width: 90px;">Type</th>
                            <th style="width: 100px;">Catégorie</th>
                            <th style="width: 90px;">Km</th>
                            <th style="width: 70px;">Mois</th>
                            <th style="width: 80px;">Actif</th>
                            <th style="width: 80px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="operations-rows"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Panel: Statistiques -->
    <div class="intervention-panel" data-intervention-panel="stats">
        <div class="section-header">
            <div>
                <h2>
                    <span class="icon-box" style="background: var(--accent-soft); color: var(--accent);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                    </span>
                    Statistiques Interventions
                </h2>
                <p>Analysez les coûts et tendances des entretiens et réparations.</p>
            </div>
            <div class="section-actions">
                <button class="btn secondary" id="refresh-intervention-stats" type="button">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/></svg>
                    Actualiser
                </button>
            </div>
        </div>

        <!-- Filtres globaux -->
        <div class="stats-filters-bar">
            <div class="stats-filters-group">
                <div class="filter-item">
                    <label>Du</label>
                    <input type="date" id="intervention-stats-date-start" class="stats-date-input">
                </div>
                <div class="filter-item">
                    <label>au</label>
                    <input type="date" id="intervention-stats-date-end" class="stats-date-input">
                </div>
            </div>
            <button class="btn primary sm" id="apply-intervention-stats-filters" type="button">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Appliquer
            </button>
        </div>

        <!-- KPIs Cards -->
        <div class="stats-kpi-grid">
            <div class="kpi-card kpi-accent">
                <div class="kpi-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                </div>
                <div class="kpi-content">
                    <span class="kpi-value" id="stats-total-interventions">0</span>
                    <span class="kpi-label">Total interventions</span>
                </div>
            </div>
            <div class="kpi-card kpi-success">
                <div class="kpi-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                </div>
                <div class="kpi-content">
                    <span class="kpi-value" id="stats-total-entretiens">0</span>
                    <span class="kpi-label">Entretiens</span>
                </div>
            </div>
            <div class="kpi-card kpi-warning">
                <div class="kpi-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                </div>
                <div class="kpi-content">
                    <span class="kpi-value" id="stats-total-reparations">0</span>
                    <span class="kpi-label">Réparations</span>
                </div>
            </div>
            <div class="kpi-card kpi-danger">
                <div class="kpi-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <div class="kpi-content">
                    <span class="kpi-value" id="stats-cout-total-interv">0 DH</span>
                    <span class="kpi-label">Coût total</span>
                </div>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="stats-charts-grid">
            <!-- Répartition Entretien vs Réparation -->
            <div class="chart-card chart-small">
                <div class="chart-header">
                    <div class="chart-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"/><path d="M22 12A10 10 0 0 0 12 2v10z"/></svg>
                        <h3>Répartition par type</h3>
                    </div>
                </div>
                <div class="chart-body">
                    <canvas id="chart-intervention-type"></canvas>
                </div>
            </div>

            <!-- Coût par catégorie -->
            <div class="chart-card chart-medium">
                <div class="chart-header">
                    <div class="chart-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        <h3>Coût par catégorie</h3>
                    </div>
                </div>
                <div class="chart-body chart-body-scroll">
                    <canvas id="chart-cout-categorie"></canvas>
                </div>
            </div>

            <!-- Coût par véhicule -->
            <div class="chart-card chart-medium">
                <div class="chart-header">
                    <div class="chart-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/></svg>
                        <h3>Coût par véhicule</h3>
                    </div>
                    <span class="chart-badge" id="cout-vehicule-interv-count">0 véhicules</span>
                </div>
                <div class="chart-body chart-body-scroll">
                    <canvas id="chart-cout-vehicule-interv"></canvas>
                </div>
            </div>

            <!-- Évolution mensuelle -->
            <div class="chart-card chart-large">
                <div class="chart-header">
                    <div class="chart-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                        <h3>Évolution mensuelle</h3>
                    </div>
                </div>
                <div class="chart-body">
                    <canvas id="chart-evolution-mensuelle"></canvas>
                </div>
            </div>

            <!-- Opérations les plus fréquentes -->
            <div class="chart-card chart-small">
                <div class="chart-header">
                    <div class="chart-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><rect x="7" y="10" width="3" height="8"/><rect x="14" y="6" width="3" height="12"/></svg>
                        <h3>Top opérations</h3>
                    </div>
                </div>
                <div class="chart-body">
                    <div class="ranking-list" id="ranking-operations">
                        <!-- Populated by JS -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Custom select init for intervention filters
    ;(function(){
        function setupCustomSelect(root){
            var trigger = root.querySelector('.custom-select__trigger');
            var options = root.querySelector('.custom-select__options');
            var valueElem = root.querySelector('.custom-select__value');
            var hidden = root.querySelector('input[type="hidden"]');

            var pre = options.querySelector('li[aria-selected="true"]') || options.querySelector('li');
            if (pre) {
                var pv = pre.getAttribute('data-value') || '';
                hidden.value = pv;
                valueElem.textContent = pre.textContent.trim();
                options.querySelectorAll('li').forEach(function(x){ x.setAttribute('aria-selected','false'); });
                pre.setAttribute('aria-selected','true');
                trigger.classList.add('selected');
            }

            function open(){
                root.classList.add('open');
                trigger.setAttribute('aria-expanded','true');
            }
            function close(){
                root.classList.remove('open');
                trigger.setAttribute('aria-expanded','false');
            }

            trigger.addEventListener('click', function(e){
                e.stopPropagation();
                if(root.classList.contains('open')) close(); else open();
            });

            options.addEventListener('click', function(e){
                var li = e.target.closest('li');
                if(!li) return;
                var v = li.getAttribute('data-value') || '';
                var text = li.textContent.trim();
                hidden.value = v;
                valueElem.textContent = text;
                options.querySelectorAll('li').forEach(function(x){ x.setAttribute('aria-selected','false'); });
                li.setAttribute('aria-selected','true');
                close();
                var nativeEv = new Event('change', { bubbles: true });
                hidden.dispatchEvent(nativeEv);
            });

            document.addEventListener('click', function(){ close(); });
            document.addEventListener('keydown', function(e){ if(e.key === 'Escape') close(); });
        }

        document.addEventListener('DOMContentLoaded', function(){
            var elems = document.querySelectorAll('#interventions .custom-select');
            elems.forEach(setupCustomSelect);
        });
    })();
    </script>
</section>

<!-- Modal Intervention -->
<div class="modal hidden" id="intervention-modal">
    <div class="modal-backdrop" data-close="intervention-modal"></div>
    <div class="modal-dialog modal-lg">
        <div class="modal-header">
            <h3 id="intervention-form-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                Nouvelle intervention
            </h3>
            <button class="close-btn" id="close-intervention-modal" type="button">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
            </button>
        </div>
        <form id="intervention-form">
            <div class="form-grid">
                <div class="form-group">
                    <label>Véhicule *</label>
                    <select name="vehicule_id" id="intervention-vehicule-select" required>
                        <option value="">- Choisir un véhicule -</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Type *</label>
                    <select id="intervention-type-select" required>
                        <option value="">- Choisir le type -</option>
                        <option value="ENT">Entretien</option>
                        <option value="REP">Réparation</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Opération *</label>
                    <select name="operation_id" id="intervention-operation-select" required>
                        <option value="">- Choisir une opération -</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Date intervention *</label>
                    <input type="date" name="date_intervention" id="intervention-date" required>
                </div>
                <div class="form-group">
                    <label>Kilométrage</label>
                    <input type="number" name="kilometrage" id="intervention-km" min="0" placeholder="Km au compteur">
                </div>
                <div class="form-group">
                    <label>Coût (DH)</label>
                    <input type="number" name="cout" id="intervention-cout" min="0" step="0.01" placeholder="0.00">
                </div>
                <div class="form-group">
                    <label>Prestataire / Garage</label>
                    <input name="prestataire" id="intervention-prestataire" placeholder="Nom du garage">
                </div>
                <div class="form-group">
                    <label>Jours d'immobilisation</label>
                    <input type="number" name="immobilisation_jours" id="intervention-immob" min="0" value="0">
                </div>
                <div class="form-group">
                    <label>Statut</label>
                    <select name="statut" id="intervention-statut">
                        <option value="termine">Terminé</option>
                        <option value="planifie">Planifié</option>
                        <option value="en_cours">En cours</option>
                        <option value="annule">Annulé</option>
                    </select>
                </div>
            </div>
            <div class="form-group full-width">
                <label>Description</label>
                <textarea name="description" id="intervention-description" rows="2" placeholder="Détails de l'intervention..."></textarea>
            </div>
            <div class="form-group full-width">
                <label>Pièces changées</label>
                <textarea name="pieces_changees" id="intervention-pieces" rows="2" placeholder="Liste des pièces remplacées..."></textarea>
            </div>
            <div class="form-group full-width">
                <label>Observations</label>
                <textarea name="observations" id="intervention-observations" rows="2" placeholder="Remarques, recommandations..."></textarea>
            </div>
            <div class="form-actions">
                <button class="btn secondary" type="button" id="cancel-intervention-form">Annuler</button>
                <button class="btn primary" id="intervention-form-submit" type="submit">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Catégorie -->
<div class="modal hidden" id="categorie-modal">
    <div class="modal-backdrop" data-close="categorie-modal"></div>
    <div class="modal-dialog">
        <div class="modal-header">
            <h3 id="categorie-form-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                Nouvelle catégorie
            </h3>
            <button class="close-btn" id="close-categorie-modal" type="button">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
            </button>
        </div>
        <form id="categorie-form">
            <div class="form-grid">
                <div class="form-group">
                    <label>Code *</label>
                    <input name="code" id="categorie-code" required maxlength="20" placeholder="Ex: MOT, FRE, ELE...">
                </div>
                <div class="form-group">
                    <label>Libellé *</label>
                    <input name="libelle" id="categorie-libelle" required maxlength="100" placeholder="Nom de la catégorie">
                </div>
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="actif" id="categorie-actif" checked>
                        <span>Actif</span>
                    </label>
                </div>
            </div>
            <div class="form-actions">
                <button class="btn secondary" type="button" id="cancel-categorie-form">Annuler</button>
                <button class="btn primary" id="categorie-form-submit" type="submit">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Opération -->
<div class="modal hidden" id="operation-modal">
    <div class="modal-backdrop" data-close="operation-modal"></div>
    <div class="modal-dialog">
        <div class="modal-header">
            <h3 id="operation-form-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                Nouvelle opération
            </h3>
            <button class="close-btn" id="close-operation-modal" type="button">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
            </button>
        </div>
        <form id="operation-form">
            <div class="form-grid">
                <div class="form-group">
                    <label>Code *</label>
                    <input name="code" id="operation-code" required maxlength="30" placeholder="Ex: VID_MOT, REP_EMBR...">
                </div>
                <div class="form-group">
                    <label>Libellé *</label>
                    <input name="libelle" id="operation-libelle" required maxlength="150" placeholder="Description de l'opération">
                </div>
                <div class="form-group">
                    <label>Type *</label>
                    <select name="type_id" id="operation-type-select" required>
                        <option value="">- Choisir -</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Catégorie *</label>
                    <select name="categorie_id" id="operation-categorie-select" required>
                        <option value="">- Choisir -</option>
                    </select>
                </div>
                <div class="form-group" id="periodicite-km-group">
                    <label>Périodicité (km)</label>
                    <input type="number" name="periodicite_km" id="operation-periodicite-km" min="0" placeholder="Ex: 10000">
                    <p class="muted-small">Uniquement pour entretien</p>
                </div>
                <div class="form-group" id="periodicite-mois-group">
                    <label>Périodicité (mois)</label>
                    <input type="number" name="periodicite_mois" id="operation-periodicite-mois" min="0" placeholder="Ex: 12">
                    <p class="muted-small">Uniquement pour entretien</p>
                </div>
                <div class="form-group">
                    <label>Coût estimé (DH)</label>
                    <input type="number" name="cout_estime" id="operation-cout-estime" min="0" step="0.01" placeholder="0.00">
                </div>
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="actif" id="operation-actif" checked>
                        <span>Actif</span>
                    </label>
                </div>
            </div>
            <div class="form-actions">
                <button class="btn secondary" type="button" id="cancel-operation-form">Annuler</button>
                <button class="btn primary" id="operation-form-submit" type="submit">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Détail Intervention -->
<div class="modal hidden" id="intervention-detail-modal">
    <div class="modal-backdrop blur" data-close="intervention-detail-modal"></div>
    <div class="modal-dialog detail-dialog">
        <div class="modal-header">
            <h3>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                Détails de l'intervention
            </h3>
            <button class="close-btn" id="close-intervention-detail-modal" type="button">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="detail-header">
                <div class="detail-avatar" id="intervention-detail-avatar" style="background: var(--primary-soft); color: var(--primary);">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                </div>
                <div class="detail-info">
                    <h3 id="intervention-detail-operation"></h3>
                    <div class="detail-badges">
                        <span class="pill" id="intervention-detail-type-badge"></span>
                        <span class="pill" id="intervention-detail-statut"></span>
                    </div>
                </div>
            </div>
            <div class="detail-vehicule-badge" id="intervention-detail-vehicule"></div>
            <div class="detail-grid">
                <div class="detail-item"><label>Date</label><span id="intervention-detail-date">-</span></div>
                <div class="detail-item"><label>Catégorie</label><span id="intervention-detail-categorie">-</span></div>
                <div class="detail-item"><label>Kilométrage</label><span id="intervention-detail-km">-</span></div>
                <div class="detail-item"><label>Coût</label><span id="intervention-detail-cout">-</span></div>
                <div class="detail-item"><label>Prestataire</label><span id="intervention-detail-prestataire">-</span></div>
                <div class="detail-item"><label>Immobilisation</label><span id="intervention-detail-immob">-</span></div>
            </div>
            <div class="detail-section">
                <h4>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    Description
                </h4>
                <p id="intervention-detail-description" class="detail-description">Aucune description.</p>
            </div>
            <div class="detail-section">
                <h4>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4"/><path d="m6.8 14-3.5 2"/><path d="m20.7 16-3.5-2"/><path d="M6.8 10 3.3 8"/><path d="m20.7 8-3.5 2"/><path d="m9 22 3-8 3 8"/><path d="M8 6a6 6 0 0 1 12 0c0 2.22-.6 4.1-2 6-1.78 2.4-4 4-6 4.5-2-0.5-4.22-2.1-6-4.5-1.4-1.9-2-3.78-2-6Z"/></svg>
                    Pièces changées
                </h4>
                <p id="intervention-detail-pieces" class="detail-description">Aucune pièce enregistrée.</p>
            </div>
            <div class="detail-section">
                <h4>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                    Observations
                </h4>
                <p id="intervention-detail-observations" class="detail-description">Aucune observation.</p>
            </div>
            <div class="detail-section" id="intervention-detail-echeance-section" style="display:none;">
                <h4>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Prochaine échéance
                </h4>
                <div class="detail-grid">
                    <div class="detail-item"><label>Prochain km</label><span id="intervention-detail-prochain-km">-</span></div>
                    <div class="detail-item"><label>Prochaine date</label><span id="intervention-detail-prochaine-date">-</span></div>
                </div>
            </div>
        </div>
    </div>
</div>
