<section class="panel section" id="vehicules">
    <div class="section-header">
        <div>
            <h2>
                <span class="icon-box">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.4 10.6 16 8 16 8h-5s-2.4 2.6-4.5 3.1C5.7 11.3 5 12.1 5 13v3c0 .6.4 1 1 1h2"/><circle cx="7.5" cy="17" r="2.5"/><circle cx="16.5" cy="17" r="2.5"/></svg>
                </span>
                Véhicules
            </h2>
            <p>Gérez les véhicules du parc automobile.</p>
        </div>
        <div class="section-actions">
            <button class="btn primary" id="open-vehicule-modal" type="button">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                Nouveau
            </button>
        </div>
    </div>
    
    <div class="card table-card">
        <div class="section-subheader">
            <h3>Liste des véhicules</h3>
            <span class="stat-badge" id="vehicules-count">
                <span class="count">0</span> véhicules
            </span>
        </div>
        <div class="table-wrapper">
            <table>
                <colgroup>
                    <col style="width: 10%">
                    <col style="width: 12%">
                    <col style="width: 18%">
                    <col style="width: 14%">
                    <col style="width: 12%">
                    <col style="width: 12%">
                    <col style="width: 10%">
                    <col style="width: 12%">
                </colgroup>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Numéro</th>
                        <th>Marque / Modèle</th>
                        <th>Chauffeur</th>
                        <th>État</th>
                        <th>Statut</th>
                        <th>Catégorie</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="vehicule-rows"></tbody>
            </table>
        </div>
    </div>

    <div class="modal hidden" id="vehicule-detail-modal">
        <div class="modal-backdrop" data-close="vehicule-detail-modal"></div>
        <div class="modal-dialog detail-dialog">
            <div class="modal-header">
                <h3>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.4 10.6 16 8 16 8h-5s-2.4 2.6-4.5 3.1C5.7 11.3 5 12.1 5 13v3c0 .6.4 1 1 1h2"/><circle cx="7.5" cy="17" r="2.5"/><circle cx="16.5" cy="17" r="2.5"/></svg>
                    Détails du véhicule
                </h3>
                <button class="close-btn" id="close-vehicule-detail-modal" type="button">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="detail-header">
                    <div class="detail-avatar">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.4 10.6 16 8 16 8h-5s-2.4 2.6-4.5 3.1C5.7 11.3 5 12.1 5 13v3c0 .6.4 1 1 1h2"/><circle cx="7.5" cy="17" r="2.5"/><circle cx="16.5" cy="17" r="2.5"/></svg>
                    </div>
                    <div class="detail-info">
                        <h3 id="vehicule-detail-title"></h3>
                        <div class="detail-badges">
                            <div class="pill" id="vehicule-detail-etat"></div>
                            <div class="pill" id="vehicule-detail-statut"></div>
                        </div>
                    </div>
                </div>
                <div class="detail-grid">
                    <div class="detail-item"><label>Code</label><span id="detail-code">-</span></div>
                    <div class="detail-item"><label>Numéro</label><span id="detail-numero">-</span></div>
                    <div class="detail-item"><label>Marque / Modèle</label><span id="detail-modele">-</span></div>
                    <div class="detail-item"><label>Année</label><span id="detail-annee">-</span></div>
                    <div class="detail-item"><label>Couleur</label><span id="detail-couleur">-</span></div>
                    <div class="detail-item"><label>Châssis</label><span id="detail-chassis">-</span></div>
                    <div class="detail-item"><label>Catégorie</label><span id="vehicule-detail-category">-</span></div>
                    <div class="detail-item"><label>Énergie</label><span id="detail-energie">-</span></div>
                    <div class="detail-item"><label>Boîte</label><span id="detail-boite">-</span></div>
                    <div class="detail-item"><label>Option</label><span id="detail-option">-</span></div>
                    <div class="detail-item"><label>Utilisation</label><span id="detail-utilisation">-</span></div>
                    <div class="detail-item"><label>Leasing</label><span id="detail-leasing">-</span></div>
                    <div class="detail-item"><label>Affectation</label><span id="detail-affectation">-</span></div>
                    <div class="detail-item"><label>Valeur</label><span id="detail-valeur">-</span></div>
                    <div class="detail-item"><label>Date acquisition</label><span id="detail-date-acquisition">-</span></div>
                    <div class="detail-item"><label>État fonctionnel</label><span id="detail-etat">-</span></div>
                    <div class="detail-item"><label>Statut</label><span id="detail-statut">-</span></div>
                    <div class="detail-item"><label>Date création</label><span id="detail-date-creation">-</span></div>
                </div>
                <div class="chauffeur-section">
                    <h4>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Chauffeur affecté
                    </h4>
                    <div class="chauffeur-grid">
                        <div class="detail-item"><label>Nom</label><span id="detail-chauffeur-nom">-</span></div>
                        <div class="detail-item"><label>Téléphone</label><span id="detail-chauffeur-telephone">-</span></div>
                        <div class="detail-item"><label>Statut</label><span id="detail-chauffeur-statut">-</span></div>
                    </div>
                </div>
                <div class="images-section">
                    <h4>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                        Images
                    </h4>
                    <div id="vehicule-images" class="image-grid"></div>
                </div>
                <div class="description-section">
                    <h4>Description</h4>
                    <p id="detail-description">Aucune description.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal hidden" id="vehicule-modal">
        <div class="modal-backdrop" data-close="vehicule-modal"></div>
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 id="vehicule-form-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                    Nouveau véhicule
                </h3>
                <button class="close-btn" id="close-vehicule-modal" type="button">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                </button>
            </div>
            <form id="vehicule-form">
                <div class="form-grid">
                    <div class="form-group"><label>Numéro *</label><input name="numero" required placeholder="ABC-123"></div>
                    <div class="form-group"><label>Code *</label><input name="code" required placeholder="VH-001"></div>
                    <div class="form-group"><label>Marque</label><input name="marque" placeholder="Toyota"></div>
                    <div class="form-group"><label>Modèle</label><input name="modele" placeholder="Corolla"></div>
                    <div class="form-group"><label>Année</label><input name="annee" type="number" min="1900" max="2100" placeholder="2024"></div>
                    <div class="form-group"><label>Couleur</label><input name="couleur" placeholder="Blanc"></div>
                    <div class="form-group"><label>Châssis</label><input name="chassis" placeholder="N° châssis"></div>
                    <div class="form-group"><label>Chauffeur</label><select name="chauffeur_id"><option value="">- Choisir -</option></select></div>
                    <div class="form-group"><label>Date acquisition</label><input name="date_acquisition" type="date"></div>
                    <div class="form-group"><label>Valeur</label><input name="valeur" type="number" min="0" step="0.01" placeholder="0.00"></div>
                    <div class="form-group"><label>État fonctionnel *</label>
                        <select name="etat_fonctionnel" required>
                            <option value="">-</option>
                            <option value="disponible">Disponible</option>
                            <option value="utilisation">Utilisation</option>
                            <option value="technique">Technique</option>
                            <option value="reglementaire">Réglementaire</option>
                            <option value="incident">Incident</option>
                            <option value="fin_de_vie">Fin de vie</option>
                        </select>
                    </div>
                    <div class="form-group"><label>Statut *</label>
                        <select name="statut" required>
                            <option value="">-</option>
                            <option value="disponible">Disponible</option>
                            <option value="en_service">En service</option>
                            <option value="reserve">Réservé</option>
                            <option value="en_maintenance">En maintenance</option>
                            <option value="en_panne">En panne</option>
                            <option value="en_reparation">En réparation</option>
                            <option value="non_conforme">Non conforme</option>
                            <option value="interdit">Interdit</option>
                            <option value="sinistre">Sinistré</option>
                            <option value="en_expertise">En expertise</option>
                            <option value="reforme">Réformé</option>
                            <option value="sorti_du_parc">Sorti du parc</option>
                        </select>
                    </div>
                    <div class="form-group"><label>Date création</label><input name="date_creation" type="date"></div>
                    <div class="form-group"><label>Catégorie</label>
                        <select name="categorie">
                            <option value="">-</option>
                            <option value="leger">Léger</option>
                            <option value="lourd">Lourd</option>
                            <option value="transport">Transport</option>
                            <option value="tracteur">Tracteur</option>
                            <option value="engins">Engins</option>
                        </select>
                    </div>
                    <div class="form-group"><label>Option véhicule</label>
                        <select name="option_vehicule">
                            <option value="">-</option>
                            <option value="base">Base</option>
                            <option value="base_clim">Base clim</option>
                            <option value="toutes_options">Toutes options</option>
                        </select>
                    </div>
                    <div class="form-group"><label>Énergie</label>
                        <select name="energie">
                            <option value="">-</option>
                            <option value="essence">Essence</option>
                            <option value="diesel">Diesel</option>
                            <option value="gpl">GPL</option>
                            <option value="electrique">Électrique</option>
                        </select>
                    </div>
                    <div class="form-group"><label>Boîte</label>
                        <select name="boite">
                            <option value="">-</option>
                            <option value="semiauto">Semi-auto</option>
                            <option value="auto">Auto</option>
                            <option value="manuel">Manuel</option>
                        </select>
                    </div>
                    <div class="form-group"><label>Leasing</label>
                        <select name="leasing">
                            <option value="">-</option>
                            <option value="location">Location</option>
                            <option value="acquisition">Acquisition</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    <div class="form-group"><label>Utilisation</label>
                        <select name="utilisation">
                            <option value="">-</option>
                            <option value="personnel">Personnel</option>
                            <option value="professionnel">Professionnel</option>
                        </select>
                    </div>
                    <div class="form-group"><label>Affectation</label><input name="affectation" placeholder="Service / site"></div>
                </div>
                <div class="form-group full-width">
                    <label>Description</label>
                    <textarea name="description" placeholder="Notes générales" rows="2"></textarea>
                </div>
                <div class="form-group full-width">
                    <label>Photos</label>
                    <input id="vehicule-images-input" name="images[]" type="file" accept="image/*" multiple>
                </div>
                <div class="form-actions">
                    <button class="btn secondary" type="button" id="cancel-vehicule-form">Annuler</button>
                    <button class="btn primary" id="vehicule-form-submit" type="submit">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal hidden" id="image-viewer-modal">
        <div class="modal-backdrop" data-close="image-viewer-modal"></div>
        <div class="modal-dialog image-viewer-dialog">
            <div class="modal-header">
                <h3>Photo du véhicule</h3>
                <button class="close-btn" id="close-image-viewer" type="button">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                </button>
            </div>
            <div class="image-viewer-body">
                <img id="image-viewer-img" alt="Aperçu">
            </div>
            <div class="image-viewer-caption" id="image-viewer-caption"></div>
        </div>
    </div>
</section>
