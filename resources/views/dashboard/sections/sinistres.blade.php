<section class="panel section" id="sinistres">
    <!-- Panel: Tableau de suivi -->
    <div class="sinistre-panel active" data-sinistre-panel="tableau">
        <div class="section-header">
            <div>
                <h2>
                    <span class="icon-box" style="background: var(--accent-soft); color: var(--accent);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    </span>
                    Tableau de suivi des sinistres
                </h2>
                <p>Visualisez et gérez l'ensemble des dossiers de sinistres.</p>
            </div>
            <div class="section-actions">
                <button class="btn primary" id="open-sinistre-modal" type="button">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                    Déclarer un sinistre
                </button>
            </div>
        </div>

        <div class="card table-card">
            <div class="section-subheader">
                <h3>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    Liste des sinistres
                </h3>
                <div class="filter-actions">
                    <div class="custom-select sinistre-filter" data-name="sinistre-filter-statut">
                        <button type="button" class="custom-select__trigger selected" aria-haspopup="listbox" aria-expanded="false">
                            <span class="custom-select__value">Tous les statuts</span>
                            <span class="custom-select__arrow">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                            </span>
                        </button>
                        <ul class="custom-select__options" role="listbox" tabindex="-1">
                            <li role="option" data-value="" aria-selected="true">Tous les statuts</li>
                            <li role="option" data-value="declare">Déclaré</li>
                            <li role="option" data-value="en_cours">En cours</li>
                            <li role="option" data-value="en_reparation">En réparation</li>
                            <li role="option" data-value="clos">Clos</li>
                        </ul>
                        <input type="hidden" id="sinistre-filter-statut" name="sinistre_filter_statut" value="">
                    </div>
                    <div class="custom-select sinistre-filter" data-name="sinistre-filter-vehicule">
                        <button type="button" class="custom-select__trigger selected" aria-haspopup="listbox" aria-expanded="false">
                            <span class="custom-select__value">Tous les véhicules</span>
                            <span class="custom-select__arrow">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                            </span>
                        </button>
                        <ul class="custom-select__options" role="listbox" tabindex="-1">
                            <li role="option" data-value="" aria-selected="true">Tous les véhicules</li>
                        </ul>
                        <input type="hidden" id="sinistre-filter-vehicule" name="sinistre_filter_vehicule" value="">
                    </div>
                    <span class="stat-badge" id="sinistres-count">
                        <span class="count">0</span> sinistres
                    </span>
                </div>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 120px;">Numéro</th>
                            <th style="width: 140px;">Véhicule</th>
                            <th style="width: 100px;">Date</th>
                            <th style="width: 100px;">Type</th>
                            <th style="width: 100px;">Gravité</th>
                            <th style="width: 120px;">Statut</th>
                            <th style="width: 110px;">Coût total</th>
                            <th style="width: 90px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="sinistre-rows"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Panel: Suivi Assurance -->
    <div class="sinistre-panel" data-sinistre-panel="assurance">
        <div class="section-header">
            <div>
                <h2>
                    <span class="icon-box" style="background: var(--accent-soft); color: var(--accent);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </span>
                    Suivi Assurance
                </h2>
                <p>Gérez les déclarations et suivis d'assurance liés aux sinistres.</p>
            </div>
            <div class="section-actions">
                <button class="btn primary" id="open-assurance-modal" type="button">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    Déclarer Assurance
                </button>
            </div>
        </div>

        <div class="card table-card">
            <div class="section-subheader">
                <h3>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    Liste des dossiers assurance
                </h3>
                <div class="filter-actions">
                    <div class="custom-select sinistre-filter" data-name="assurance-sinistre-select">
                        <button type="button" class="custom-select__trigger selected" aria-haspopup="listbox" aria-expanded="false">
                            <span class="custom-select__value">Tous les sinistres</span>
                            <span class="custom-select__arrow">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                            </span>
                        </button>
                        <ul class="custom-select__options" role="listbox" tabindex="-1">
                            <li role="option" data-value="" aria-selected="true">Tous les sinistres</li>
                        </ul>
                        <input type="hidden" id="assurance-sinistre-select" name="assurance_sinistre_select" value="">
                    </div>
                </div>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 130px;">Sinistre</th>
                            <th style="width: 140px;">Compagnie</th>
                            <th style="width: 120px;">N° Dossier</th>
                            <th style="width: 100px;">Décision</th>
                            <th style="width: 120px;">Montant PEC</th>
                            <th style="width: 100px;">Franchise</th>
                            <th style="width: 100px;">Statut</th>
                            <th style="width: 80px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="assurance-rows"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Panel: Suivi Réparations -->
    <div class="sinistre-panel" data-sinistre-panel="reparations">
        <div class="section-header">
            <div>
                <h2>
                    <span class="icon-box" style="background: var(--accent-soft); color: var(--accent);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                    </span>
                    Suivi Réparations
                </h2>
                <p>Suivez les réparations liées aux sinistres.</p>
            </div>
            <div class="section-actions">
                <button class="btn primary" id="open-reparation-modal" type="button">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                    Nouvelle réparation
                </button>
            </div>
        </div>

        <div class="card table-card">
            <div class="section-subheader">
                <h3>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                    Liste des réparations
                </h3>
                <div class="filter-actions">
                    <div class="custom-select sinistre-filter" data-name="reparation-sinistre-select">
                        <button type="button" class="custom-select__trigger selected" aria-haspopup="listbox" aria-expanded="false">
                            <span class="custom-select__value">Tous les sinistres</span>
                            <span class="custom-select__arrow">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                            </span>
                        </button>
                        <ul class="custom-select__options" role="listbox" tabindex="-1">
                            <li role="option" data-value="" aria-selected="true">Tous les sinistres</li>
                        </ul>
                        <input type="hidden" id="reparation-sinistre-select" name="reparation_sinistre_select" value="">
                    </div>
                </div>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 130px;">Sinistre</th>
                            <th style="width: 140px;">Garage</th>
                            <th style="width: 100px;">Type</th>
                            <th style="width: 100px;">Début</th>
                            <th style="width: 100px;">Fin prévue</th>
                            <th style="width: 110px;">Coût</th>
                            <th style="width: 100px;">Statut</th>
                            <th style="width: 80px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="reparation-rows"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Panel: Statistiques -->
    <div class="sinistre-panel" data-sinistre-panel="stats">
        <div class="section-header">
            <div>
                <h2>
                    <span class="icon-box" style="background: var(--accent-soft); color: var(--accent);">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                    </span>
                    Statistiques Sinistres
                </h2>
                <p>Analysez les données et tendances des sinistres.</p>
            </div>
            <div class="section-actions">
                <button class="btn secondary" id="refresh-sinistre-stats" type="button">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/></svg>
                    Actualiser
                </button>
            </div>
        </div>

        <div class="stats-filters">
            <div class="form-row">
                <div class="form-group">
                    <label>Du</label>
                    <input type="date" id="stats-date-start">
                </div>
                <div class="form-group">
                    <label>au</label>
                    <input type="date" id="stats-date-end">
                </div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: var(--accent-soft); color: var(--accent);">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>
                </div>
                <div class="stat-content">
                    <span class="stat-value" id="stats-total-sinistres">0</span>
                    <span class="stat-label">Total sinistres</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: var(--success-100); color: var(--success-600);">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div class="stat-content">
                    <span class="stat-value" id="stats-taux-prise">0%</span>
                    <span class="stat-label">Taux prise en charge</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: var(--warning-100); color: var(--warning-600);">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                </div>
                <div class="stat-content">
                    <span class="stat-value" id="stats-vehicules-plus">-</span>
                    <span class="stat-label">Véhicule le + sinistré</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: var(--danger-100); color: var(--danger-600);">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <div class="stat-content">
                    <span class="stat-value" id="stats-cout-total">0 DH</span>
                    <span class="stat-label">Coût total</span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="section-subheader">
                <h3>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                    Répartition par type
                </h3>
            </div>
            <div class="stats-chart" id="stats-type-chart"></div>
        </div>
    </div>

    <script>
    // Custom select init for sinistre filter
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

            var searchInput = null;
            if (root.dataset.searchable === 'true') {
                searchInput = options.querySelector('.custom-select__search');
                if (searchInput) {
                    searchInput.addEventListener('input', function(e){
                        var q = (e.target.value || '').toLowerCase();
                        options.querySelectorAll('li[role="option"]').forEach(function(li){
                            var txt = li.textContent.trim().toLowerCase();
                            li.style.display = txt.indexOf(q) !== -1 ? '' : 'none';
                        });
                    });
                    searchInput.addEventListener('click', function(e){ e.stopPropagation(); });
                }
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
                var ev = new CustomEvent('sinistreFilterChange', { detail: { name: hidden.id, value: v } });
                root.dispatchEvent(ev);
                var nativeEv = new Event('change', { bubbles: true });
                hidden.dispatchEvent(nativeEv);
            });

            document.addEventListener('click', function(){ close(); });
            document.addEventListener('keydown', function(e){ if(e.key === 'Escape') close(); });
        }

        document.addEventListener('DOMContentLoaded', function(){
            var elems = document.querySelectorAll('.custom-select');
            elems.forEach(setupCustomSelect);
        });
    })();
    </script>
</section>

<!-- Modal Sinistre -->
<div class="modal hidden" id="sinistre-modal">
    <div class="modal-backdrop" data-close="sinistre-modal"></div>
    <div class="modal-dialog">
        <div class="modal-header">
            <h3 id="sinistre-form-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                Déclarer un sinistre
            </h3>
            <button class="close-btn" id="close-sinistre-modal" type="button">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
            </button>
        </div>
        <form id="sinistre-form">
            <div class="form-grid">
                <div class="form-group">
                    <label>Numéro de dossier</label>
                    <input name="numero_sinistre" id="sinistre-numero" placeholder="Généré automatiquement" readonly aria-readonly="true">
                    <p class="muted-small">Généré automatiquement </p>
                </div>
                <div class="form-group">
                    <label>Véhicule *</label>
                    <select name="vehicule_id" id="sinistre-vehicule-select" required>
                        <option value="">- Choisir -</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Chauffeur</label>
                    <select name="chauffeur_id" id="sinistre-chauffeur-select">
                        <option value="">Aucun</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Date *</label>
                    <input type="date" name="date_sinistre" required>
                </div>
                <div class="form-group">
                    <label>Heure</label>
                    <input type="time" name="heure_sinistre">
                </div>
                <div class="form-group">
                    <label>Lieu</label>
                    <input name="lieu_sinistre" placeholder="Ville, axe, site...">
                </div>
                <div class="form-group">
                    <label>Type *</label>
                    <select name="type_sinistre" required>
                        <option value="accident">Accident</option>
                        <option value="panne">Panne</option>
                        <option value="vol">Vol</option>
                        <option value="incendie">Incendie</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Gravité *</label>
                    <select name="gravite" required>
                        <option value="mineur">Mineur</option>
                        <option value="moyen">Moyen</option>
                        <option value="grave">Grave</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Responsable *</label>
                    <select name="responsable" required>
                        <option value="inconnu">Inconnu</option>
                        <option value="chauffeur">Chauffeur</option>
                        <option value="tiers">Tiers</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Montant estimé</label>
                    <input name="montant_estime" type="number" min="0" step="0.01" placeholder="0.00">
                </div>
                <div class="form-group">
                    <label>Statut</label>
                    <input type="hidden" name="statut_sinistre" id="sinistre-statut-hidden" value="declare">
                    <select id="sinistre-statut-select" disabled aria-disabled="true">
                        <option value="declare">Déclaré</option>
                        <option value="en_cours">En cours</option>
                        <option value="en_reparation">En réparation</option>
                        <option value="clos">Clos</option>
                    </select>
                    <p class="muted-small">Non modifiable.</p>
                </div>
            </div>
            <div class="form-group full-width">
                <label>Description</label>
                <textarea name="description" rows="3" placeholder="Contexte, dégâts constatés, témoins..."></textarea>
            </div>
            <div class="form-actions">
                <button class="btn secondary" type="button" id="cancel-sinistre-form">Annuler</button>
                <button class="btn primary" id="sinistre-form-submit" type="submit">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Assurance -->
<div class="modal hidden" id="assurance-modal">
    <div class="modal-backdrop" data-close="assurance-modal"></div>
    <div class="modal-dialog">
        <div class="modal-header">
            <h3 id="assurance-form-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Déclaration assurance
            </h3>
            <button class="close-btn" id="close-assurance-modal" type="button">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
            </button>
        </div>
        <form id="assurance-form">
            <input type="hidden" name="sinistre_id" id="assurance-sinistre-input">
            <div class="form-grid">
                <div class="form-group">
                    <label>Sinistre *</label>
                    <select name="sinistre_id_select" id="assurance-sinistre-select-modal" required>
                        <option value="">- Choisir un sinistre -</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Numéro dossier</label>
                    <input name="numero_dossier" id="assurance-numero-dossier" placeholder="Généré automatiquement" readonly>
                    <p class="muted-small">Généré automatiquement</p>
                </div>
                <div class="form-group">
                    <label>Compagnie</label>
                    <input name="compagnie_assurance" placeholder="Nom de la compagnie">
                </div>
                <div class="form-group">
                    <label>Date déclaration</label>
                    <input type="date" name="date_declaration">
                </div>
                <div class="form-group">
                    <label>Expert</label>
                    <input name="expert_nom" placeholder="Nom de l'expert">
                </div>
                <div class="form-group">
                    <label>Date expertise</label>
                    <input type="date" name="date_expertise">
                </div>
                <div class="form-group">
                    <label>Décision</label>
                    <select name="decision" id="assurance-decision-select">
                        <option value="en_attente">En attente</option>
                        <option value="accepte">Accepté</option>
                        <option value="refuse">Refusé</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Montant pris en charge</label>
                    <input name="montant_pris_en_charge" type="number" min="0" step="0.01" placeholder="0.00">
                </div>
                <div class="form-group">
                    <label>Franchise</label>
                    <input name="franchise" type="number" min="0" step="0.01" placeholder="0.00">
                </div>
                <div class="form-group">
                    <label>Date validation</label>
                    <input type="date" name="date_validation">
                </div>
                <div class="form-group">
                    <label>Statut</label>
                    <input type="hidden" name="statut_assurance" id="assurance-statut-hidden" value="en_cours">
                    <select id="assurance-statut-select" disabled>
                        <option value="en_cours">En cours</option>
                        <option value="valide">Validé</option>
                        <option value="refuse">Refusé</option>
                    </select>
                    <p class="muted-small">Mis à jour automatiquement selon la décision.</p>
                </div>
            </div>
            <div class="form-actions">
                <button class="btn secondary" type="button" id="cancel-assurance-form">Annuler</button>
                <button class="btn primary" id="assurance-form-submit" type="submit">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Réparation -->
<div class="modal hidden" id="reparation-modal">
    <div class="modal-backdrop" data-close="reparation-modal"></div>
    <div class="modal-dialog">
        <div class="modal-header">
            <h3 id="reparation-form-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                Ajouter une réparation
            </h3>
            <button class="close-btn" id="close-reparation-modal" type="button">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
            </button>
        </div>
        <form id="reparation-form">
            <div class="form-grid">
                <div class="form-group">
                    <label>Sinistre *</label>
                    <select id="reparation-sinistre-select-modal" name="sinistre_id" required>
                        <option value="">- Choisir -</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Garage</label>
                    <input name="garage" placeholder="Nom du garage">
                </div>
                <div class="form-group">
                    <label>Type</label>
                    <select name="type_reparation">
                        <option value="">- Choisir -</option>
                        <option value="mecanique">Mécanique</option>
                        <option value="carrosserie">Carrosserie</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Date début</label>
                    <input type="date" name="date_debut">
                </div>
                <div class="form-group">
                    <label>Date fin prévue</label>
                    <input type="date" name="date_fin_prevue">
                </div>
                <div class="form-group">
                    <label>Date fin réelle</label>
                    <input type="date" name="date_fin_reelle">
                </div>
                <div class="form-group">
                    <label>Coût réparation</label>
                    <input type="number" name="cout_reparation" min="0" step="0.01" placeholder="0.00">
                </div>
                <div class="form-group">
                    <label>Prise en charge</label>
                    <input type="hidden" name="prise_en_charge" id="reparation-prise-en-charge-hidden" value="societe">
                    <select id="reparation-prise-en-charge-select" name="prise_en_charge_display">
                        <option value="societe">Société (sans assurance)</option>
                        <option value="assurance">Assurance</option>
                    </select>
                    <p class="muted-small" id="reparation-prise-en-charge-help">Valeur par défaut selon le statut de l'assurance.</p>
                </div>
                <div class="form-group">
                    <label>Statut</label>
                    <input type="hidden" name="statut_reparation" id="reparation-statut-hidden" value="en_cours">
                    <select id="reparation-statut-select" name="statut_reparation_display">
                        <option value="en_attente">En attente</option>
                        <option value="en_cours">En cours</option>
                        <option value="termine">Terminé</option>
                    </select>
                    <p class="muted-small" id="reparation-statut-help">Valeur par défaut : En cours.</p>
                </div>
                <div class="form-group">
                    <label>Facture / référence</label>
                    <input name="facture_reference" placeholder="N° facture">
                </div>
            </div>
            <div class="form-actions">
                <button class="btn secondary" type="button" id="cancel-reparation-form">Annuler</button>
                <button class="btn primary" id="reparation-form-submit" type="submit">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Détail Sinistre -->
<div class="modal hidden" id="sinistre-detail-modal">
    <div class="modal-backdrop blur" data-close="sinistre-detail-modal"></div>
    <div class="modal-dialog detail-dialog">
        <div class="modal-header">
            <h3>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                Détails du sinistre
            </h3>
            <button class="close-btn" id="close-sinistre-detail-modal" type="button">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="detail-header">
                <div class="detail-avatar" style="background: var(--accent-soft); color: var(--accent);">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>
                </div>
                <div class="detail-info">
                    <h3 id="sinistre-detail-numero"></h3>
                    <div class="detail-badges">
                        <div class="pill" id="sinistre-detail-statut"></div>
                    </div>
                </div>
            </div>
            <div class="detail-vehicule-badge" id="sinistre-detail-vehicule"></div>
            <div class="detail-grid">
                <div class="detail-item"><label>Date</label><span id="sinistre-detail-date">-</span></div>
                <div class="detail-item"><label>Heure</label><span id="sinistre-detail-heure">-</span></div>
                <div class="detail-item"><label>Lieu</label><span id="sinistre-detail-lieu">-</span></div>
                <div class="detail-item"><label>Type</label><span id="sinistre-detail-type">-</span></div>
                <div class="detail-item"><label>Gravité</label><span id="sinistre-detail-gravite">-</span></div>
                <div class="detail-item"><label>Responsable</label><span id="sinistre-detail-responsable">-</span></div>
                <div class="detail-item"><label>Montant estimé</label><span id="sinistre-detail-montant">-</span></div>
                <div class="detail-item"><label>Coût total</label><span id="sinistre-detail-cout-total">-</span></div>
            </div>
            <div class="detail-section">
                <h4>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    Description
                </h4>
                <p id="sinistre-detail-description" class="detail-description">Aucune description.</p>
            </div>
            <div class="detail-section">
                <h4>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    Assurance
                </h4>
                <div id="sinistre-detail-assurance" class="detail-subsection"></div>
            </div>
            <div class="detail-section">
                <h4>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                    Réparations
                </h4>
                <div id="sinistre-detail-reparations" class="detail-subsection"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Détail Assurance -->
<div class="modal hidden" id="assurance-detail-modal">
    <div class="modal-backdrop blur" data-close="assurance-detail-modal"></div>
    <div class="modal-dialog detail-dialog">
        <div class="modal-header">
            <h3>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Détails assurance
            </h3>
            <button class="close-btn" id="close-assurance-detail-modal" type="button">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="detail-header">
                <div class="detail-avatar" style="background: var(--success-100); color: var(--success-600);">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div class="detail-info">
                    <h3 id="assurance-detail-sinistre">Dossier assurance</h3>
                    <div class="detail-badges">
                        <span id="assurance-detail-statut"></span>
                    </div>
                </div>
            </div>
            <div class="detail-grid">
                <div class="detail-item"><label>Compagnie</label><span id="assurance-detail-compagnie">-</span></div>
                <div class="detail-item"><label>N° Dossier</label><span id="assurance-detail-dossier">-</span></div>
                <div class="detail-item"><label>Date déclaration</label><span id="assurance-detail-date-declaration">-</span></div>
                <div class="detail-item"><label>Expert</label><span id="assurance-detail-expert">-</span></div>
                <div class="detail-item"><label>Date expertise</label><span id="assurance-detail-date-expertise">-</span></div>
                <div class="detail-item"><label>Décision</label><span id="assurance-detail-decision">-</span></div>
                <div class="detail-item"><label>Montant pris en charge</label><span id="assurance-detail-montant">-</span></div>
                <div class="detail-item"><label>Franchise</label><span id="assurance-detail-franchise">-</span></div>
                <div class="detail-item"><label>Date validation</label><span id="assurance-detail-date-validation">-</span></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Détail Réparation -->
<div class="modal hidden" id="reparation-detail-modal">
    <div class="modal-backdrop blur" data-close="reparation-detail-modal"></div>
    <div class="modal-dialog detail-dialog">
        <div class="modal-header">
            <h3>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                Détails réparation
            </h3>
            <button class="close-btn" id="close-reparation-detail-modal" type="button">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="detail-header">
                <div class="detail-avatar" style="background: var(--warning-100); color: var(--warning-600);">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                </div>
                <div class="detail-info">
                    <h3 id="reparation-detail-sinistre">Réparation</h3>
                    <div class="detail-badges">
                        <span id="reparation-detail-statut"></span>
                    </div>
                </div>
            </div>
            <div class="detail-grid">
                <div class="detail-item"><label>Garage</label><span id="reparation-detail-garage">-</span></div>
                <div class="detail-item"><label>Type</label><span id="reparation-detail-type">-</span></div>
                <div class="detail-item"><label>Date début</label><span id="reparation-detail-date-debut">-</span></div>
                <div class="detail-item"><label>Date fin prévue</label><span id="reparation-detail-date-fin-prevue">-</span></div>
                <div class="detail-item"><label>Date fin réelle</label><span id="reparation-detail-date-fin-reelle">-</span></div>
                <div class="detail-item"><label>Coût</label><span id="reparation-detail-cout">-</span></div>
                <div class="detail-item"><label>Prise en charge</label><span id="reparation-detail-prise-en-charge">-</span></div>
                <div class="detail-item"><label>Facture/Référence</label><span id="reparation-detail-facture">-</span></div>
            </div>
        </div>
    </div>
</div>