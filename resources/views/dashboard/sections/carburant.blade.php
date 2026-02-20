<section class="panel section" id="carburant">
    <!-- Panel: Enregistrement des pleins -->
    <div class="carburant-panel active" data-carburant-panel="pleins">
        <div class="section-header">
            <div>
                <h2>
                    <span class="icon-box" style="background: var(--accent-soft); color: var(--accent);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 22h12"/><path d="M4 9h10"/><path d="M4 22V4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v18"/><path d="M14 15a2 2 0 1 0 4 0v-3a2 2 0 0 0-2-2h-2"/><path d="M16 10V4"/></svg>
                    </span>
                    Gestion Carburant
                </h2>
                <p>Enregistrez et suivez les pleins carburant de votre flotte.</p>
            </div>
            <div class="section-actions">
                <button class="btn secondary sm" id="export-carburant-pleins-pdf" type="button" title="Exporter en PDF">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7,10 12,15 17,10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Export PDF
                </button>
                <button class="btn primary" id="open-carburant-modal" type="button">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                    Nouveau plein
                </button>
            </div>
        </div>

        <div class="card table-card">
            <div class="section-subheader">
                <h3>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    Liste des pleins carburant
                </h3>
                <div class="filter-actions">
                    <div class="filter-item" style="display:inline-flex;align-items:center;gap:4px;margin-right:6px;">
                        <label style="font-size:12px;color:var(--text-secondary);">Du</label>
                        <input type="date" id="carburant-filter-date-start" class="stats-date-input" style="width:130px;font-size:12px;">
                    </div>
                    <div class="filter-item" style="display:inline-flex;align-items:center;gap:4px;margin-right:8px;">
                        <label style="font-size:12px;color:var(--text-secondary);">au</label>
                        <input type="date" id="carburant-filter-date-end" class="stats-date-input" style="width:130px;font-size:12px;">
                    </div>
                    <div class="custom-select carburant-filter" data-name="carburant-filter-type" data-default-label="Tous les types">
                        <button type="button" class="custom-select__trigger selected" aria-haspopup="listbox" aria-expanded="false">
                            <span class="custom-select__value">Tous les types</span>
                            <span class="custom-select__arrow">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                            </span>
                        </button>
                        <ul class="custom-select__options" role="listbox" tabindex="-1">
                            <li role="option" data-value="" aria-selected="true">Tous les types</li>
                            <li role="option" data-value="diesel">Diesel</li>
                            <li role="option" data-value="essence">Essence</li>
                            <li role="option" data-value="gpl">GPL</li>
                            <li role="option" data-value="electrique">Electrique</li>
                        </ul>
                        <input type="hidden" id="carburant-filter-type" name="carburant_filter_type" value="">
                    </div>
                    <div class="custom-select carburant-filter" data-name="carburant-filter-vehicule" data-default-label="Tous les véhicules">
                        <button type="button" class="custom-select__trigger selected" aria-haspopup="listbox" aria-expanded="false">
                            <span class="custom-select__value">Tous les véhicules</span>
                            <span class="custom-select__arrow">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                            </span>
                        </button>
                        <ul class="custom-select__options" role="listbox" tabindex="-1">
                            <li role="option" data-value="" aria-selected="true">Tous les véhicules</li>
                        </ul>
                        <input type="hidden" id="carburant-filter-vehicule" name="carburant_filter_vehicule" value="">
                    </div>
                    <div class="custom-select carburant-filter" data-name="carburant-filter-mode" data-default-label="Tous les modes">
                        <button type="button" class="custom-select__trigger selected" aria-haspopup="listbox" aria-expanded="false">
                            <span class="custom-select__value">Tous les modes</span>
                            <span class="custom-select__arrow">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                            </span>
                        </button>
                        <ul class="custom-select__options" role="listbox" tabindex="-1">
                            <li role="option" data-value="" aria-selected="true">Tous les modes</li>
                            <li role="option" data-value="especes">Espèces</li>
                            <li role="option" data-value="carte_carburant">Carte carburant</li>
                            <li role="option" data-value="bon">Bon</li>
                            <li role="option" data-value="cheque">Chèque</li>
                        </ul>
                        <input type="hidden" id="carburant-filter-mode" name="carburant_filter_mode" value="">
                    </div>
                    <span class="stat-badge" id="carburant-count">
                        <span class="count">0</span> pleins
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
                            <th style="width: 80px;">Km</th>
                            <th style="width: 80px;">Quantité (L)</th>
                            <th style="width: 90px;">Prix/L</th>
                            <th style="width: 100px;">Montant</th>
                            <th style="width: 90px;">Mode</th>
                            <th style="width: 100px;">Conducteur</th>
                            <th style="width: 80px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="carburant-rows"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Panel: Suivi Consommation -->
    <div class="carburant-panel" data-carburant-panel="consommation">
        <div class="section-header">
            <div>
                <h2>
                    <span class="icon-box" style="background: var(--accent-soft); color: var(--accent);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                    </span>
                    Suivi de la consommation
                </h2>
                <p>Consommation moyenne, coût par km, coût mensuel par véhicule, coût global du parc.</p>
            </div>
            <div class="section-actions">
                <button class="btn secondary sm" id="export-carburant-stats-pdf" type="button" title="Export PDF">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7,10 12,15 17,10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Export PDF
                </button>
                <button class="btn secondary" id="refresh-carburant-consommation" type="button">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/></svg>
                    Actualiser
                </button>
            </div>
        </div>

        <!-- Coût global du parc KPIs -->
        <div class="stats-kpi-grid">
            <div class="kpi-card kpi-accent">
                <div class="kpi-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <div class="kpi-content">
                    <span class="kpi-value" id="conso-cout-global">0 DA</span>
                    <span class="kpi-label">Coût global du parc</span>
                </div>
            </div>
            <div class="kpi-card kpi-success">
                <div class="kpi-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                <div class="kpi-content">
                    <span class="kpi-value" id="conso-cout-mensuel-parc">0 DA</span>
                    <span class="kpi-label">Coût mensuel moyen (parc)</span>
                </div>
            </div>
            <div class="kpi-card kpi-warning">
                <div class="kpi-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/></svg>
                </div>
                <div class="kpi-content">
                    <span class="kpi-value" id="conso-nb-vehicules">0</span>
                    <span class="kpi-label">Véhicules concernés</span>
                </div>
            </div>
            <div class="kpi-card kpi-danger">
                <div class="kpi-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                </div>
                <div class="kpi-content">
                    <span class="kpi-value" id="conso-moyenne-cout-km">0 DA</span>
                    <span class="kpi-label">Moyenne coût/km</span>
                </div>
            </div>
        </div>

        <!-- Tableau consommation par véhicule -->
        <div class="card table-card">
            <div class="section-subheader">
                <h3>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/></svg>
                    Consommation par véhicule
                </h3>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 130px;">Véhicule</th>
                            <th style="width: 100px;">Km parcourus</th>
                            <th style="width: 80px;">Pleins</th>
                            <th style="width: 90px;">Litres</th>
                            <th style="width: 110px;">Dépense (DA)</th>
                            <th style="width: 100px;">Coût/km (DA)</th>
                            <th style="width: 110px;">Conso (L/km)</th>
                        </tr>
                    </thead>
                    <tbody id="consommation-vehicule-rows"></tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Panel: Alertes & Contrôles -->
    <div class="carburant-panel" data-carburant-panel="alertes">
        <div class="section-header">
            <div>
                <h2>
                    <span class="icon-box" style="background: var(--accent-soft); color: var(--accent);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    </span>
                    Alertes & Contrôles
                </h2>
                <p>Surconsommation anormale, kilométrage incohérent, pleins trop rapprochés.</p>
            </div>
            <div class="section-actions">
                <button class="btn secondary" id="refresh-carburant-alertes" type="button">
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
                    <span class="kpi-value" id="carburant-alertes-surconso">0</span>
                    <span class="kpi-label">Surconsommations</span>
                </div>
            </div>
            <div class="kpi-card kpi-warning">
                <div class="kpi-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>
                </div>
                <div class="kpi-content">
                    <span class="kpi-value" id="carburant-alertes-km">0</span>
                    <span class="kpi-label">Km incohérents</span>
                </div>
            </div>
            <div class="kpi-card kpi-accent">
                <div class="kpi-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div class="kpi-content">
                    <span class="kpi-value" id="carburant-alertes-rapproches">0</span>
                    <span class="kpi-label">Pleins trop rapprochés</span>
                </div>
            </div>
        </div>

        <!-- Tableau alertes -->
        <div class="card table-card">
            <div class="section-subheader">
                <h3>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>
                    Détail des alertes
                </h3>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 18%;">Véhicule</th>
                            <th style="width: 15%;">Type alerte</th>
                            <th style="width: 14%;">Date plein</th>
                            <th style="width: 53%;">Message</th>
                        </tr>
                    </thead>
                    <tbody id="carburant-alertes-rows"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Panel: Comparaison Véhicules -->
    <div class="carburant-panel" data-carburant-panel="comparaison">
        <div class="section-header">
            <div>
                <h2>
                    <span class="icon-box" style="background: var(--accent-soft); color: var(--accent);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><rect x="7" y="10" width="3" height="8"/><rect x="14" y="6" width="3" height="12"/></svg>
                    </span>
                    Comparaison véhicules
                </h2>
                <p>Identifiez les véhicules les plus coûteux, détectez les surconsommations, aidez au renouvellement du parc.</p>
            </div>
            <div class="section-actions">
                <button class="btn secondary sm" id="export-carburant-comparaison-pdf" type="button" title="Export PDF">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7,10 12,15 17,10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Export PDF
                </button>
                <button class="btn secondary" id="refresh-carburant-comparaison" type="button">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/></svg>
                    Actualiser
                </button>
            </div>
        </div>

        <!-- Filtres comparaison -->
        <div class="stats-filters-bar">
            <div class="stats-filters-group">
                <div class="filter-item">
                    <label>Du</label>
                    <input type="date" id="comparaison-date-start" class="stats-date-input">
                </div>
                <div class="filter-item">
                    <label>au</label>
                    <input type="date" id="comparaison-date-end" class="stats-date-input">
                </div>
                <div class="filter-item">
                    <label>Carburant</label>
                    <select id="comparaison-type-carburant" class="stats-date-input">
                        <option value="">Tous</option>
                        <option value="diesel">Diesel</option>
                        <option value="essence">Essence</option>
                        <option value="gpl">GPL</option>
                        <option value="electrique">Electrique</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label>Type véhicule</label>
                    <select id="comparaison-categorie" class="stats-date-input">
                        <option value="">Tous</option>
                        <option value="leger">Léger</option>
                        <option value="lourd">Lourd</option>
                        <option value="transport">Transport</option>
                        <option value="tracteur">Tracteur</option>
                        <option value="engins">Engins</option>
                    </select>
                </div>
            </div>
            <button class="btn primary sm" id="apply-comparaison-filters" type="button">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Appliquer
            </button>
        </div>

        <!-- Tableau comparaison -->
        <div class="card table-card">
            <div class="section-subheader">
                <h3>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/></svg>
                    Tableau comparatif
                </h3>
                <div class="filter-actions">
                    <span class="stat-badge" id="comparaison-moyenne-badge">
                        Moyenne parc : <span class="count" id="comparaison-moyenne-cout">0</span> DA/km
                    </span>
                </div>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 130px;">Véhicule</th>
                            <th style="width: 100px;">Km parcourus</th>
                            <th style="width: 110px;">Dépense (DA)</th>
                            <th style="width: 100px;">Coût/km (DA)</th>
                            <th style="width: 110px;">Conso (L/km)</th>
                            <th style="width: 80px;">Pleins</th>
                            <th style="width: 100px;">Statut</th>
                        </tr>
                    </thead>
                    <tbody id="comparaison-rows"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Panel: Statistiques & Rapports -->
    <div class="carburant-panel" data-carburant-panel="stats">
        <div class="section-header">
            <div>
                <h2>
                    <span class="icon-box" style="background: var(--accent-soft); color: var(--accent);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                    </span>
                    Statistiques Carburant
                </h2>
                <p>Analysez les dépenses et tendances de consommation carburant.</p>
            </div>
            <div class="section-actions">
                <button class="btn secondary sm" id="export-carburant-global-stats-pdf" type="button" title="Export PDF">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7,10 12,15 17,10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Export PDF
                </button>
                <button class="btn secondary" id="refresh-carburant-stats" type="button">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/></svg>
                    Actualiser
                </button>
            </div>
        </div>

        <!-- Filtres globaux stats -->
        <div class="stats-filters-bar">
            <div class="stats-filters-group">
                <div class="filter-item">
                    <label>Du</label>
                    <input type="date" id="carburant-stats-date-start" class="stats-date-input">
                </div>
                <div class="filter-item">
                    <label>au</label>
                    <input type="date" id="carburant-stats-date-end" class="stats-date-input">
                </div>
            </div>
            <button class="btn primary sm" id="apply-carburant-stats-filters" type="button">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Appliquer
            </button>
        </div>

        <!-- KPIs Cards -->
        <div class="stats-kpi-grid">
            <div class="kpi-card kpi-accent">
                <div class="kpi-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 22h12"/><path d="M4 9h10"/><path d="M4 22V4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v18"/><path d="M14 15a2 2 0 1 0 4 0v-3a2 2 0 0 0-2-2h-2"/><path d="M16 10V4"/></svg>
                </div>
                <div class="kpi-content">
                    <span class="kpi-value" id="stats-total-pleins">0</span>
                    <span class="kpi-label">Total pleins</span>
                </div>
            </div>
            <div class="kpi-card kpi-success">
                <div class="kpi-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20"/><path d="M2 12h20"/></svg>
                </div>
                <div class="kpi-content">
                    <span class="kpi-value" id="stats-total-litres">0 L</span>
                    <span class="kpi-label">Total litres</span>
                </div>
            </div>
            <div class="kpi-card kpi-danger">
                <div class="kpi-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <div class="kpi-content">
                    <span class="kpi-value" id="stats-total-depenses">0 DA</span>
                    <span class="kpi-label">Dépenses totales</span>
                </div>
            </div>
            <div class="kpi-card kpi-warning">
                <div class="kpi-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                </div>
                <div class="kpi-content">
                    <span class="kpi-value" id="stats-prix-moyen-litre">0 DA</span>
                    <span class="kpi-label">Prix moyen / litre</span>
                </div>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="stats-charts-grid">
            <!-- Répartition par type carburant -->
            <div class="chart-card chart-small">
                <div class="chart-header">
                    <div class="chart-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"/><path d="M22 12A10 10 0 0 0 12 2v10z"/></svg>
                        <h3>Répartition par type</h3>
                    </div>
                </div>
                <div class="chart-body">
                    <canvas id="chart-carburant-type"></canvas>
                </div>
            </div>

            <!-- Dépenses par véhicule -->
            <div class="chart-card chart-medium">
                <div class="chart-header">
                    <div class="chart-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/></svg>
                        <h3>Dépenses par véhicule</h3>
                    </div>
                    <span class="chart-badge" id="depense-vehicule-count">0 véhicules</span>
                </div>
                <div class="chart-body chart-body-scroll">
                    <canvas id="chart-carburant-vehicule"></canvas>
                </div>
            </div>

            <!-- Répartition par mode de paiement -->
            <div class="chart-card chart-small">
                <div class="chart-header">
                    <div class="chart-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                        <h3>Par mode de paiement</h3>
                    </div>
                </div>
                <div class="chart-body">
                    <canvas id="chart-carburant-mode"></canvas>
                </div>
            </div>

            <!-- Évolution mensuelle des dépenses -->
            <div class="chart-card chart-large">
                <div class="chart-header">
                    <div class="chart-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                        <h3>Évolution mensuelle</h3>
                    </div>
                </div>
                <div class="chart-body">
                    <canvas id="chart-carburant-evolution"></canvas>
                </div>
            </div>

            <!-- Classement véhicules les plus gourmands -->
            <div class="chart-card chart-small">
                <div class="chart-header">
                    <div class="chart-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><rect x="7" y="10" width="3" height="8"/><rect x="14" y="6" width="3" height="12"/></svg>
                        <h3>Top véhicules gourmands</h3>
                    </div>
                </div>
                <div class="chart-body">
                    <div class="ranking-list" id="ranking-vehicules-gourmands">
                        <!-- Populated by JS -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Plein Carburant -->
<div class="modal hidden" id="carburant-modal">
    <div class="modal-backdrop" data-close="carburant-modal"></div>
    <div class="modal-dialog modal-lg">
        <div class="modal-header">
            <h3 id="carburant-form-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 22h12"/><path d="M4 9h10"/><path d="M4 22V4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v18"/><path d="M14 15a2 2 0 1 0 4 0v-3a2 2 0 0 0-2-2h-2"/><path d="M16 10V4"/></svg>
                Nouveau plein carburant
            </h3>
            <button class="close-btn" id="close-carburant-modal" type="button">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
            </button>
        </div>
        <form id="carburant-form">
            <div class="form-grid">
                <div class="form-group">
                    <label>Véhicule *</label>
                    <select name="vehicule_id" id="carburant-vehicule-select" required>
                        <option value="">- Choisir un véhicule -</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Date du plein *</label>
                    <input type="date" name="date_plein" id="carburant-date" required>
                </div>
                <div class="form-group">
                    <label>Kilométrage *</label>
                    <input type="number" name="kilometrage" id="carburant-km" min="0" required placeholder="Km au compteur">
                </div>
                <div class="form-group">
                    <label>Quantité (L) *</label>
                    <input type="number" name="quantite" id="carburant-quantite" min="0.01" step="0.01" required placeholder="Litres">
                </div>
                <div class="form-group">
                    <label>Prix unitaire (DA/L) *</label>
                    <input type="number" name="prix_unitaire" id="carburant-prix" min="0.01" step="0.01" required placeholder="Prix par litre">
                </div>
                <div class="form-group">
                    <label>Montant total (DA)</label>
                    <input type="number" name="montant_total" id="carburant-montant" readonly placeholder="Calcul automatique" class="input-readonly">
                </div>
                <div class="form-group">
                    <label>Type carburant *</label>
                    <select name="type_carburant" id="carburant-type-select" required>
                        <option value="">- Choisir -</option>
                        <option value="diesel">Diesel</option>
                        <option value="essence">Essence</option>
                        <option value="gpl">GPL</option>
                        <option value="electrique">Electrique</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Station-service</label>
                    <input name="station" id="carburant-station" placeholder="Nom de la station">
                </div>
                <div class="form-group">
                    <label>Mode de paiement *</label>
                    <select name="mode_paiement" id="carburant-mode-select" required>
                        <option value="">- Choisir -</option>
                        <option value="especes">Espèces</option>
                        <option value="carte_carburant">Carte carburant</option>
                        <option value="bon">Bon</option>
                        <option value="cheque">Chèque</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Conducteur</label>
                    <select name="chauffeur_id" id="carburant-chauffeur-select">
                        <option value="">- Optionnel -</option>
                    </select>
                </div>
            </div>
            <div class="form-group full-width">
                <label>Observation</label>
                <textarea name="observation" id="carburant-observation" rows="2" placeholder="Observation (optionnel)..."></textarea>
            </div>
            <div class="form-actions">
                <button class="btn secondary" type="button" id="cancel-carburant-form">Annuler</button>
                <button class="btn primary" id="carburant-form-submit" type="submit">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Détail Plein Carburant -->
<div class="modal hidden" id="carburant-detail-modal">
    <div class="modal-backdrop blur" data-close="carburant-detail-modal"></div>
    <div class="modal-dialog detail-dialog">
        <div class="modal-header">
            <h3>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 22h12"/><path d="M4 9h10"/><path d="M4 22V4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v18"/><path d="M14 15a2 2 0 1 0 4 0v-3a2 2 0 0 0-2-2h-2"/><path d="M16 10V4"/></svg>
                Détails du plein carburant
            </h3>
            <button class="close-btn" id="close-carburant-detail-modal" type="button">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="detail-header">
                <div class="detail-avatar" style="background: var(--accent-soft); color: var(--accent);">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 22h12"/><path d="M4 9h10"/><path d="M4 22V4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v18"/><path d="M14 15a2 2 0 1 0 4 0v-3a2 2 0 0 0-2-2h-2"/><path d="M16 10V4"/></svg>
                </div>
                <div class="detail-info">
                    <h3 id="carburant-detail-vehicule-name"></h3>
                    <div class="detail-badges">
                        <span class="pill" id="carburant-detail-type-badge"></span>
                        <span class="pill" id="carburant-detail-mode-badge"></span>
                    </div>
                </div>
            </div>
            <div class="detail-grid">
                <div class="detail-item"><label>Date</label><span id="carburant-detail-date">-</span></div>
                <div class="detail-item"><label>Kilométrage</label><span id="carburant-detail-km">-</span></div>
                <div class="detail-item"><label>Quantité</label><span id="carburant-detail-quantite">-</span></div>
                <div class="detail-item"><label>Prix unitaire</label><span id="carburant-detail-prix">-</span></div>
                <div class="detail-item"><label>Montant total</label><span id="carburant-detail-montant">-</span></div>
                <div class="detail-item"><label>Station</label><span id="carburant-detail-station">-</span></div>
                <div class="detail-item"><label>Conducteur</label><span id="carburant-detail-chauffeur">-</span></div>
            </div>
            <div class="detail-section">
                <h4>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    Observation
                </h4>
                <p id="carburant-detail-observation" class="detail-description">Aucune observation.</p>
            </div>
        </div>
    </div>
</div>
