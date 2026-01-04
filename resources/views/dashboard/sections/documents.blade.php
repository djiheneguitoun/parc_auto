<section class="panel section" id="documents">

    <div class="doc-panels">
        <div class="doc-panel active" data-doc-panel="assurance">
            <div class="section-subheader">
                <div>
                    <h3>
                        <span class="doc-title-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </span>
                        Assurances
                    </h3>
                </div>
                <div class="section-actions doc-filter-actions">
                    <select id="document-vehicule-filter-assurance" class="document-vehicule-filter">
                        <option value="">🚗 Tous les véhicules</option>
                    </select>
                    <button class="btn secondary" data-doc-add="assurance" type="button">Ajouter</button>
                </div>
            </div>
            <div class="table-wrapper table-card">
                <table class="table-clickable">
                    <thead>
                    <tr><th>Numéro</th><th>Libellé</th><th>Partenaire</th><th>Début</th><th>Expiration</th><th>Valeur</th><th>Facture</th><th>Date facture</th><th>Actions</th></tr>
                    </thead>
                    <tbody id="document-rows-assurance"></tbody>
                </table>
            </div>
        </div>

        <div class="doc-panel" data-doc-panel="vignette">
            <div class="section-subheader">
                <div>
                    <h3>
                        <span class="doc-title-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/><line x1="9" y1="4" x2="9" y2="10"/></svg>
                        </span>
                        Vignettes
                    </h3>
                </div>
                <div class="section-actions doc-filter-actions">
                    <select id="document-vehicule-filter-vignette" class="document-vehicule-filter">
                        <option value="">🚗 Tous les véhicules</option>
                    </select>
                    <button class="btn secondary" data-doc-add="vignette" type="button">Ajouter</button>
                </div>
            </div>
            <div class="table-wrapper table-card">
                <table class="table-clickable">
                    <thead>
                    <tr><th>Numéro</th><th>Libellé</th><th>Partenaire</th><th>Début</th><th>Expiration</th><th>Valeur</th><th>Facture</th><th>Date facture</th><th>Actions</th></tr>
                    </thead>
                    <tbody id="document-rows-vignette"></tbody>
                </table>
            </div>
        </div>

        <div class="doc-panel" data-doc-panel="controle">
            <div class="section-subheader">
                <div>
                    <h3>
                        <span class="doc-title-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                        </span>
                        Contrôles
                    </h3>
                </div>
                <div class="section-actions doc-filter-actions">
                    <select id="document-vehicule-filter-controle" class="document-vehicule-filter">
                        <option value="">🚗 Tous les véhicules</option>
                    </select>
                    <button class="btn secondary" data-doc-add="controle" type="button">Ajouter</button>
                </div>
            </div>
            <div class="table-wrapper table-card">
                <table class="table-clickable">
                    <thead>
                    <tr><th>Numéro</th><th>Libellé</th><th>Partenaire</th><th>Début</th><th>Expiration</th><th>Valeur</th><th>Facture</th><th>Date facture</th><th>Actions</th></tr>
                    </thead>
                    <tbody id="document-rows-controle"></tbody>
                </table>
            </div>
        </div>

        <div class="doc-panel" data-doc-panel="entretien">
            <div class="section-subheader">
                <div>
                    <h3>
                        <span class="doc-title-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                        </span>
                        Entretiens
                    </h3>
                </div>
                <div class="section-actions doc-filter-actions">
                    <select id="document-vehicule-filter-entretien" class="document-vehicule-filter">
                        <option value="">🚗 Tous les véhicules</option>
                    </select>
                    <button class="btn secondary" data-doc-add="entretien" type="button">Ajouter</button>
                </div>
            </div>
            <div class="table-wrapper table-card">
                <table class="table-clickable">
                    <thead>
                    <tr><th>Numéro</th><th>Libellé</th><th>Partenaire</th><th>Début</th><th>Expiration</th><th>Vidange</th><th>Km</th><th>Valeur</th><th>Facture</th><th>Date facture</th><th>Actions</th></tr>
                    </thead>
                    <tbody id="document-rows-entretien"></tbody>
                </table>
            </div>
        </div>

        <div class="doc-panel" data-doc-panel="reparation">
            <div class="section-subheader">
                <div>
                    <h3>
                        <span class="doc-title-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                        </span>
                        Réparations
                    </h3>
                </div>
                <div class="section-actions doc-filter-actions">
                    <select id="document-vehicule-filter-reparation" class="document-vehicule-filter">
                        <option value="">🚗 Tous les véhicules</option>
                    </select>
                    <button class="btn secondary" data-doc-add="reparation" type="button">Ajouter</button>
                </div>
            </div>
            <div class="table-wrapper table-card">
                <table class="table-clickable">
                    <thead>
                    <tr><th>Numéro</th><th>Pièce</th><th>Réparateur</th><th>Type</th><th>Date</th><th>Valeur</th><th>Facture</th><th>Date facture</th><th>Actions</th></tr>
                    </thead>
                    <tbody id="document-rows-reparation"></tbody>
                </table>
            </div>
        </div>

        <div class="doc-panel" data-doc-panel="bon_essence">
            <div class="section-subheader">
                <div>
                    <h3>
                        <span class="doc-title-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 22h12"/><path d="M4 9h10"/><path d="M4 22V4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v18"/><path d="M14 15h3a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-1l-2-3"/><circle cx="9" cy="13" r="3"/></svg>
                        </span>
                        Bons d'essence
                    </h3>
                </div>
                <div class="section-actions doc-filter-actions">
                    <select id="document-vehicule-filter-bon_essence" class="document-vehicule-filter">
                        <option value="">🚗 Tous les véhicules</option>
                    </select>
                    <button class="btn secondary" data-doc-add="bon_essence" type="button">Ajouter</button>
                </div>
            </div>
            <div class="table-wrapper table-card">
                <table class="table-clickable">
                    <thead>
                    <tr><th>Numéro</th><th>Date</th><th>Carburant</th><th>Km</th><th>Utilisation</th><th>Valeur</th><th>Facture</th><th>Date facture</th><th>Actions</th></tr>
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
