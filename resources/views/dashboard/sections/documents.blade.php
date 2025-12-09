<section class="panel section" id="documents">
    <style>
        #documents .doc-tabs { display: none; }
        #documents .doc-tab { padding: 10px 14px; border-radius: 12px; border: 1px solid var(--border); background: #eef0f7; font-weight: 700; cursor: pointer; transition: all 0.15s ease; }
        #documents .doc-tab.active { background: var(--accent); color: #fff; border-color: var(--accent); box-shadow: 0 10px 22px rgba(24, 38, 110, 0.18); }
        #documents .doc-panel { display: none; gap: 16px; }
        #documents .doc-panel.active { display: block; }
        #documents .table-card { margin-top: 6px; }
        #documents table th, #documents table td { font-size: 11px; padding: 7px 5px; }
        #documents table th { font-size: 10px; }
    </style>


    <div class="doc-tabs">
        <button class="doc-tab active" data-doc-tab="assurance" type="button">Assurance</button>
        <button class="doc-tab" data-doc-tab="vignette" type="button">Vignette</button>
        <button class="doc-tab" data-doc-tab="controle" type="button">Contrôle</button>
        <button class="doc-tab" data-doc-tab="entretien" type="button">Entretien</button>
        <button class="doc-tab" data-doc-tab="reparation" type="button">Réparation</button>
        <button class="doc-tab" data-doc-tab="bon_essence" type="button">Bon d'essence</button>
    </div>

    <div class="doc-panels">
        <div class="doc-panel active" data-doc-panel="assurance">
            <div class="section-subheader">
                <div>
                    <h3>Assurances</h3>
                    <div class="muted-small">Numéros, partenaire, période et facturation.</div>
                </div>
                <div class="section-actions">
                    <button class="btn secondary" data-doc-add="assurance" type="button">Ajouter</button>
                </div>
            </div>
            <div class="table-wrapper table-card">
                <table class="table-clickable">
                    <thead>
                    <tr><th>Véhicule</th><th>Numéro</th><th>Partenaire</th><th>Début</th><th>Expiration</th><th>Valeur</th><th>Facture</th><th>Actions</th></tr>
                    </thead>
                    <tbody id="document-rows-assurance"></tbody>
                </table>
            </div>
        </div>

        <div class="doc-panel" data-doc-panel="vignette">
            <div class="section-subheader">
                <div>
                    <h3>Vignettes</h3>
                    <div class="muted-small">Suivi des vignettes et échéances.</div>
                </div>
                <div class="section-actions">
                    <button class="btn secondary" data-doc-add="vignette" type="button">Ajouter</button>
                </div>
            </div>
            <div class="table-wrapper table-card">
                <table class="table-clickable">
                    <thead>
                    <tr><th>Véhicule</th><th>Numéro</th><th>Partenaire</th><th>Début</th><th>Expiration</th><th>Valeur</th><th>Facture</th><th>Actions</th></tr>
                    </thead>
                    <tbody id="document-rows-vignette"></tbody>
                </table>
            </div>
        </div>

        <div class="doc-panel" data-doc-panel="controle">
            <div class="section-subheader">
                <div>
                    <h3>Contrôles</h3>
                    <div class="muted-small">Contrôles techniques et leurs échéances.</div>
                </div>
                <div class="section-actions">
                    <button class="btn secondary" data-doc-add="controle" type="button">Ajouter</button>
                </div>
            </div>
            <div class="table-wrapper table-card">
                <table class="table-clickable">
                    <thead>
                    <tr><th>Véhicule</th><th>Numéro</th><th>Partenaire</th><th>Début</th><th>Expiration</th><th>Valeur</th><th>Facture</th><th>Actions</th></tr>
                    </thead>
                    <tbody id="document-rows-controle"></tbody>
                </table>
            </div>
        </div>

        <div class="doc-panel" data-doc-panel="entretien">
            <div class="section-subheader">
                <div>
                    <h3>Entretiens</h3>
                    <div class="muted-small">Vidange, kilométrage, période et factures.</div>
                </div>
                <div class="section-actions">
                    <button class="btn secondary" data-doc-add="entretien" type="button">Ajouter</button>
                </div>
            </div>
            <div class="table-wrapper table-card">
                <table class="table-clickable">
                    <thead>
                    <tr><th>Véhicule</th><th>Numéro</th><th>Partenaire</th><th>Début</th><th>Expiration</th><th>Vidange</th><th>Km</th><th>Valeur</th><th>Facture</th><th>Actions</th></tr>
                    </thead>
                    <tbody id="document-rows-entretien"></tbody>
                </table>
            </div>
        </div>

        <div class="doc-panel" data-doc-panel="reparation">
            <div class="section-subheader">
                <div>
                    <h3>Réparations</h3>
                    <div class="muted-small">Pièces, réparateur, type, dates et factures.</div>
                </div>
                <div class="section-actions">
                    <button class="btn secondary" data-doc-add="reparation" type="button">Ajouter</button>
                </div>
            </div>
            <div class="table-wrapper table-card">
                <table class="table-clickable">
                    <thead>
                    <tr><th>Véhicule</th><th>Numéro</th><th>Pièce</th><th>Réparateur</th><th>Type</th><th>Date</th><th>Valeur</th><th>Facture</th><th>Actions</th></tr>
                    </thead>
                    <tbody id="document-rows-reparation"></tbody>
                </table>
            </div>
        </div>

        <div class="doc-panel" data-doc-panel="bon_essence">
            <div class="section-subheader">
                <div>
                    <h3>Bons d'essence</h3>
                    <div class="muted-small">Type de carburant, kilométrage, utilisation et factures.</div>
                </div>
                <div class="section-actions">
                    <button class="btn secondary" data-doc-add="bon_essence" type="button">Ajouter</button>
                </div>
            </div>
            <div class="table-wrapper table-card">
                <table class="table-clickable">
                    <thead>
                    <tr><th>Véhicule</th><th>Numéro</th><th>Date</th><th>Carburant</th><th>Km</th><th>Utilisation</th><th>Valeur</th><th>Facture</th><th>Actions</th></tr>
                    </thead>
                    <tbody id="document-rows-bon_essence"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal hidden" id="document-modal">
        <div class="modal-backdrop" data-close="document-modal"></div>
        <div class="modal-dialog">
            <div class="section-subheader">
                <div>
                    <h3 id="document-form-title">Ajouter un document</h3>
                    <div class="muted-small" id="document-form-description"></div>
                </div>
                <div class="section-actions">
                    <button class="btn secondary xs" id="close-document-modal" type="button">Fermer</button>
                </div>
            </div>
            <form id="document-form">
                <input type="hidden" name="type" id="document-type-input">
                <div class="grid" id="document-form-fields"></div>
                <div class="section-actions">
                    <button class="btn primary" id="document-form-submit" type="submit">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</section>
