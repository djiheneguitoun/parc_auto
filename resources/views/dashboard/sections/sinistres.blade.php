<section class="panel section" id="sinistres">
    <div class="sinistre-panels">
        <div class="sinistre-panel active" data-sinistre-panel="tableau">
            <div class="section-subheader">
                <div>
                    <h3>Tableau de suivi</h3>
                    <div class="muted-small">Déclarer, consulter et clôturer les dossiers.</div>
                </div>
                <div class="section-actions">
                    <!-- Custom select pour Statut -->
                    <div class="custom-select sinistre-filter" data-name="sinistre-filter-statut">
                        <button type="button" class="custom-select__trigger selected" aria-haspopup="listbox" aria-expanded="false">
                            <span class="custom-select__value">Tous les statuts</span>
                            <span class="custom-select__arrow">▾</span>
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
                    <!-- Custom select pour Véhicule -->
                    <div class="custom-select sinistre-filter" data-name="sinistre-filter-vehicule">
                        <button type="button" class="custom-select__trigger selected" aria-haspopup="listbox" aria-expanded="false">
                            <span class="custom-select__value">Tous les véhicules</span>
                            <span class="custom-select__arrow">▾</span>
                        </button>
                        <ul class="custom-select__options" role="listbox" tabindex="-1">
                            <li role="option" data-value="" aria-selected="true">Tous les véhicules</li>
                        </ul>
                        <input type="hidden" id="sinistre-filter-vehicule" name="sinistre_filter_vehicule" value="">
                    </div>
                    <button class="btn primary" id="open-sinistre-modal" type="button">Ajouter un sinistre</button>
                </div>
            </div>
            <div class="table-wrapper table-card">
                <table class="table-clickable">
                    <thead>
                    <tr><th>Numéro</th><th>Véhicule</th><th>Date</th><th>Gravité</th><th>Statut</th><th>Coût total</th><th style="width: 180px;">Actions</th></tr>
                    </thead>
                    <tbody id="sinistre-rows"></tbody>
                </table>
            </div>

            <div class="card detail-card" id="sinistre-detail-card">
                <div id="sinistre-detail-empty" class="muted-small">Sélectionnez un sinistre pour afficher les détails.</div>
                <div id="sinistre-detail" style="display:none;">
                    <div class="section-subheader">
                        <div class="detail-heading">
                            <h3 id="sinistre-detail-numero"></h3>
                            <div class="pill" id="sinistre-detail-statut"></div>
                        </div>
                        <div class="muted-small" id="sinistre-detail-vehicule"></div>
                    </div>

                    <div class="grid info-grid">
                        <div class="stack info-chip"><div class="muted-small">Date</div><div id="sinistre-detail-date"></div></div>
                        <div class="stack info-chip"><div class="muted-small">Heure</div><div id="sinistre-detail-heure"></div></div>
                        <div class="stack info-chip"><div class="muted-small">Lieu</div><div id="sinistre-detail-lieu"></div></div>
                        <div class="stack info-chip"><div class="muted-small">Type</div><div id="sinistre-detail-type"></div></div>
                        <div class="stack info-chip"><div class="muted-small">Gravité</div><div id="sinistre-detail-gravite"></div></div>
                        <div class="stack info-chip"><div class="muted-small">Responsable</div><div id="sinistre-detail-responsable"></div></div>
                        <div class="stack info-chip"><div class="muted-small">Montant estimé</div><div id="sinistre-detail-montant"></div></div>
                        <div class="stack info-chip"><div class="muted-small">Coût total</div><div id="sinistre-detail-cout-total"></div></div>
                    </div>

                    <div class="stack">
                        <div class="muted-small">Description</div>
                        <p id="sinistre-detail-description" class="muted"></p>
                    </div>
                    <div class="stack">
                        <div class="muted-small">Assurance</div>
                        <div id="sinistre-detail-assurance"></div>
                    </div>
                    <div class="stack">
                        <div class="muted-small">Réparations</div>
                        <div id="sinistre-detail-reparations"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="sinistre-panel" data-sinistre-panel="assurance">
            <div class="section-subheader">
                <div>
                    <h3>Suivi assurance</h3>
                    <div class="muted-small">Déclarations, décisions et montants pris en charge.</div>
                </div>
                <div class="section-actions">
                    <select id="assurance-sinistre-select">
                        <option value="">Choisir un sinistre</option>
                    </select>
                    <button class="btn secondary" id="open-assurance-modal" type="button">Déclarer à l'assurance</button>
                </div>
            </div>
            <div class="table-wrapper table-card">
                <table class="table-clickable">
                    <thead>
                    <tr><th>Sinistre</th><th>Compagnie</th><th>Dossier</th><th>Décision</th><th>Statut</th><th>Prise en charge</th><th>Franchise</th><th style="width: 140px;">Actions</th></tr>
                    </thead>
                    <tbody id="assurance-rows"></tbody>
                </table>
            </div>
        </div>

        <div class="sinistre-panel" data-sinistre-panel="reparations">
            <div class="section-subheader">
                <div>
                    <h3>Suivi réparations</h3>
                    <div class="muted-small">Ordonnancer et clôturer les réparations liées aux sinistres.</div>
                </div>
                <div class="section-actions">
                    <select id="reparation-sinistre-select">
                        <option value="">Choisir un sinistre</option>
                    </select>
                    <button class="btn secondary" id="open-reparation-modal" type="button">Ajouter une réparation</button>
                </div>
            </div>
            <div class="table-wrapper table-card">
                <table class="table-clickable">
                    <thead>
                    <tr><th>Sinistre</th><th>Garage</th><th>Type</th><th>Début</th><th>Fin prévue</th><th>Statut</th><th>Coût</th><th style="width: 170px;">Actions</th></tr>
                    </thead>
                    <tbody id="reparation-rows"></tbody>
                </table>
            </div>
        </div>

        <div class="sinistre-panel" data-sinistre-panel="stats">
            <div class="section-subheader">
                <div>
                    <h3>Statistiques</h3>
                    <div class="muted-small">Volumes, coûts et prise en charge par période.</div>
                </div>
                <div class="section-actions">
                    <input type="date" id="stats-date-start">
                    <input type="date" id="stats-date-end">
                    <button class="btn secondary xs" id="refresh-sinistre-stats" type="button">Mettre à jour</button>
                </div>
            </div>
            <div class="grid stats-grid" id="sinistre-stats-cards">
                <div class="card"><p class="muted">Total sinistres</p><h3 id="stats-total-sinistres">-</h3></div>
                <div class="card"><p class="muted">Taux prise en charge moyen</p><h3 id="stats-taux-prise">-</h3><div class="muted-small">Basé sur les dossiers assurés</div></div>
                <div class="card"><p class="muted">Top véhicules</p><div id="stats-vehicules-plus" class="stack muted-small"></div></div>
                <div class="card"><p class="muted">Classement chauffeurs</p><div id="stats-classement-chauffeurs" class="stack muted-small"></div></div>
                <div class="card"><p class="muted">Coût par véhicule</p><div id="stats-cout-par-vehicule" class="stack muted-small"></div></div>
                <div class="card"><p class="muted">Nombre par période</p><div id="stats-par-periode" class="stack muted-small"></div></div>
            </div>
        </div>
    </div>
</section>

    <script>
    // Custom select init for sinistre filter
    ;(function(){
        function setupCustomSelect(root){
            var trigger = root.querySelector('.custom-select__trigger');
            var options = root.querySelector('.custom-select__options');
            var valueElem = root.querySelector('.custom-select__value');
            var hidden = root.querySelector('input[type="hidden"]');

            // initialize from markup: if an option is pre-marked aria-selected, use it
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

            // If this select is searchable, wire up the search input to filter options
            var searchInput = null;
            if (root.dataset.searchable === 'true') {
                // search input may be inside options container as .custom-select__search
                searchInput = options.querySelector('.custom-select__search');
                if (searchInput) {
                    searchInput.addEventListener('input', function(e){
                        var q = (e.target.value || '').toLowerCase();
                        options.querySelectorAll('li[role="option"]').forEach(function(li){
                            var txt = li.textContent.trim().toLowerCase();
                            li.style.display = txt.indexOf(q) !== -1 ? '' : 'none';
                        });
                    });
                    // prevent click propagation from search input to options click handler
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
                // mark selected
                options.querySelectorAll('li').forEach(function(x){ x.setAttribute('aria-selected','false'); });
                li.setAttribute('aria-selected','true');
                close();
                // dispatch a custom event so existing JS can react to filter change
                var ev = new CustomEvent('sinistreFilterChange', { detail: { name: hidden.id, value: v } });
                root.dispatchEvent(ev);
                // trigger native change on hidden input so existing listeners (renderSinistreRows) run
                var nativeEv = new Event('change', { bubbles: true });
                hidden.dispatchEvent(nativeEv);
            });

            // close on outside click
            document.addEventListener('click', function(){ close(); });
            // close on escape
            document.addEventListener('keydown', function(e){ if(e.key === 'Escape') close(); });
        }

        document.addEventListener('DOMContentLoaded', function(){
            var elems = document.querySelectorAll('.custom-select');
            elems.forEach(setupCustomSelect);
        });
    })();
    </script>

<div class="modal hidden" id="sinistre-modal">
    <div class="modal-backdrop" data-close="sinistre-modal"></div>
    <div class="modal-dialog">
        <div class="section-subheader">
            <div>
                <h3 id="sinistre-form-title">Déclarer un sinistre</h3>
                <div class="muted-small">Renseignez les informations essentielles.</div>
            </div>
            <div class="section-actions">
                <button class="btn secondary xs" id="close-sinistre-modal" type="button">Fermer</button>
            </div>
        </div>
        <form id="sinistre-form">
            <div class="grid">
                <div><label>Numéro de dossier</label><input name="numero_sinistre" required></div>
                <div><label>Véhicule</label><select name="vehicule_id" id="sinistre-vehicule-select" required></select></div>
                <div><label>Chauffeur</label><select name="chauffeur_id" id="sinistre-chauffeur-select"><option value="">Aucun</option></select></div>
                <div><label>Date</label><input type="date" name="date_sinistre" required></div>
                <div><label>Heure</label><input type="time" name="heure_sinistre"></div>
                <div><label>Lieu</label><input name="lieu_sinistre" placeholder="Ville, axe, site..."></div>
                <div><label>Type</label>
                    <select name="type_sinistre" required>
                        <option value="accident">Accident</option>
                        <option value="panne">Panne</option>
                        <option value="vol">Vol</option>
                        <option value="incendie">Incendie</option>
                    </select>
                </div>
                <div><label>Gravité</label>
                    <select name="gravite" required>
                        <option value="mineur">Mineur</option>
                        <option value="moyen">Moyen</option>
                        <option value="grave">Grave</option>
                    </select>
                </div>
                <div><label>Responsable</label>
                    <select name="responsable" required>
                        <option value="inconnu">Inconnu</option>
                        <option value="chauffeur">Chauffeur</option>
                        <option value="tiers">Tiers</option>
                    </select>
                </div>
                <div><label>Montant estimé</label><input name="montant_estime" type="number" min="0" step="0.01" placeholder="0.00"></div>
                <div><label>Statut</label>
                    <select name="statut_sinistre" id="sinistre-statut-select">
                        <option value="declare">Déclaré</option>
                        <option value="en_cours">En cours</option>
                        <option value="en_reparation">En réparation</option>
                        <option value="clos">Clos</option>
                    </select>
                </div>
            </div>
            <div class="stack">
                <label>Description</label>
                <textarea name="description" rows="3" placeholder="Contexte, dégâts constatés, témoins..."></textarea>
            </div>
            <div class="section-actions" style="justify-content: flex-end;">
                <button class="btn primary" id="sinistre-form-submit" type="submit">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<div class="modal hidden" id="assurance-modal">
    <div class="modal-backdrop" data-close="assurance-modal"></div>
    <div class="modal-dialog">
        <div class="section-subheader">
            <div>
                <h3 id="assurance-form-title">Déclaration assurance</h3>
                <div class="muted-small">Synchroniser le dossier assureur.</div>
            </div>
            <div class="section-actions">
                <button class="btn secondary xs" id="close-assurance-modal" type="button">Fermer</button>
            </div>
        </div>
        <form id="assurance-form">
            <input type="hidden" name="sinistre_id" id="assurance-sinistre-input">
            <div class="grid">
                <div><label>Compagnie</label><input name="compagnie_assurance"></div>
                <div><label>Numéro dossier</label><input name="numero_dossier"></div>
                <div><label>Date déclaration</label><input type="date" name="date_declaration"></div>
                <div><label>Expert</label><input name="expert_nom"></div>
                <div><label>Date expertise</label><input type="date" name="date_expertise"></div>
                <div><label>Décision</label>
                    <select name="decision" required>
                        <option value="en_attente">En attente</option>
                        <option value="accepte">Accepté</option>
                        <option value="refuse">Refusé</option>
                    </select>
                </div>
                <div><label>Montant pris en charge</label><input name="montant_pris_en_charge" type="number" min="0" step="0.01"></div>
                <div><label>Franchise</label><input name="franchise" type="number" min="0" step="0.01"></div>
                <div><label>Date validation</label><input type="date" name="date_validation"></div>
                <div><label>Statut assurance</label>
                    <select name="statut_assurance" required>
                        <option value="en_cours">En cours</option>
                        <option value="valide">Validé</option>
                        <option value="refuse">Refusé</option>
                    </select>
                </div>
            </div>
            <div class="section-actions" style="justify-content: flex-end;">
                <button class="btn primary" id="assurance-form-submit" type="submit">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<div class="modal hidden" id="reparation-modal">
    <div class="modal-backdrop" data-close="reparation-modal"></div>
    <div class="modal-dialog">
        <div class="section-subheader">
            <div>
                <h3 id="reparation-form-title">Ajouter une réparation</h3>
                <div class="muted-small">Planifier ou clôturer une réparation sinistre.</div>
            </div>
            <div class="section-actions">
                <button class="btn secondary xs" id="close-reparation-modal" type="button">Fermer</button>
            </div>
        </div>
        <form id="reparation-form">
            <div class="grid">
                <div><label>Sinistre</label><select id="reparation-sinistre-select-modal" name="sinistre_id"></select></div>
                <div><label>Garage</label><input name="garage"></div>
                <div><label>Type</label>
                    <select name="type_reparation">
                        <option value="">-</option>
                        <option value="mecanique">Mécanique</option>
                        <option value="carrosserie">Carrosserie</option>
                    </select>
                </div>
                <div><label>Date début</label><input type="date" name="date_debut"></div>
                <div><label>Date fin prévue</label><input type="date" name="date_fin_prevue"></div>
                <div><label>Date fin réelle</label><input type="date" name="date_fin_reelle"></div>
                <div><label>Coût réparation</label><input type="number" name="cout_reparation" min="0" step="0.01"></div>
                <div><label>Prise en charge</label>
                    <select name="prise_en_charge">
                        <option value="societe">Société</option>
                        <option value="assurance">Assurance</option>
                    </select>
                </div>
                <div><label>Statut</label>
                    <select name="statut_reparation">
                        <option value="en_attente">En attente</option>
                        <option value="en_cours">En cours</option>
                        <option value="termine">Terminé</option>
                    </select>
                </div>
                <div><label>Facture / référence</label><input name="facture_reference"></div>
            </div>
            <div class="section-actions" style="justify-content: flex-end;">
                <button class="btn primary" id="reparation-form-submit" type="submit">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
