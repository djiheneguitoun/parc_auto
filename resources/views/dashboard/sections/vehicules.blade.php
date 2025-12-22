<section class="panel section" id="vehicules">
    <div class="section-header">
        <div>
            <h2>Véhicules</h2>
            <p>Ajoutez, modifiez et consultez tous les détails des véhicules.</p>
        </div>
        <div class="section-actions">
            <button class="btn secondary" id="open-vehicule-modal" type="button">Nouveau véhicule</button>
        </div>
    </div>

    <div class="card table-card">
        <div class="section-subheader">
            <div>
                <h3>Liste des véhicules</h3>
                <div class="muted-small">Cliquez sur une ligne pour afficher tous les détails ou utilisez les actions.</div>
            </div>
        </div>
        <div class="table-wrapper">
            <table class="table-clickable">
                <thead>
                    <tr><th>Code</th><th>Numéro</th><th>Marque / Modèle</th><th>Chauffeur</th><th>Statut</th><th>Catégorie</th><th>Actions</th></tr>
                </thead>
                <tbody id="vehicule-rows"></tbody>
            </table>
        </div>
    </div>

    <div class="card detail-card" id="vehicule-detail-card">
        <div id="vehicule-detail-empty" class="muted-small">Sélectionnez un véhicule pour afficher tous les détails.</div>
        <div id="vehicule-detail" style="display:none;">
            <div class="section-subheader">
                <div class="detail-heading">
                    <h3 id="vehicule-detail-title"></h3>
                    <div class="pill" id="vehicule-detail-statut"></div>
                </div>
                <div class="muted-small" id="vehicule-detail-category"></div>
            </div>

            <div class="grid info-grid">
                <div class="stack info-chip"><div class="muted-small">Code</div><div id="detail-code"></div></div>
                <div class="stack info-chip"><div class="muted-small">Numéro</div><div id="detail-numero"></div></div>
                <div class="stack info-chip"><div class="muted-small">Marque / Modèle</div><div id="detail-modele"></div></div>
                <div class="stack info-chip"><div class="muted-small">Année</div><div id="detail-annee"></div></div>
                <div class="stack info-chip"><div class="muted-small">Couleur</div><div id="detail-couleur"></div></div>
                <div class="stack info-chip"><div class="muted-small">Châssis</div><div id="detail-chassis"></div></div>
                <div class="stack info-chip"><div class="muted-small">Energie</div><div id="detail-energie"></div></div>
                <div class="stack info-chip"><div class="muted-small">Boîte</div><div id="detail-boite"></div></div>
                <div class="stack info-chip"><div class="muted-small">Option</div><div id="detail-option"></div></div>
                <div class="stack info-chip"><div class="muted-small">Utilisation</div><div id="detail-utilisation"></div></div>
                <div class="stack info-chip"><div class="muted-small">Leasing</div><div id="detail-leasing"></div></div>
                <div class="stack info-chip"><div class="muted-small">Affectation</div><div id="detail-affectation"></div></div>
                <div class="stack info-chip"><div class="muted-small">Valeur</div><div id="detail-valeur"></div></div>
                <div class="stack info-chip"><div class="muted-small">Date d'acquisition</div><div id="detail-date-acquisition"></div></div>
                <div class="stack info-chip"><div class="muted-small">Date de création</div><div id="detail-date-creation"></div></div>
            </div>

            <div class="section-subheader">
                <div>
                    <h4>Chauffeur affecté</h4>
                    <div class="muted-small">Information du chauffeur lié.</div>
                </div>
            </div>
            <div class="grid info-grid">
                <div class="stack info-chip"><div class="muted-small">Nom</div><div id="detail-chauffeur-nom"></div></div>
                <div class="stack info-chip"><div class="muted-small">Téléphone</div><div id="detail-chauffeur-telephone"></div></div>
                <div class="stack info-chip"><div class="muted-small">Statut</div><div id="detail-chauffeur-statut"></div></div>
            </div>

            <div class="section-subheader">
                <div>
                    <h4>Images</h4>
                </div>
            </div>
            <div id="vehicule-images" class="image-grid"></div>

            <div class="section-subheader">
                <div>
                    <h4>Description</h4>
                </div>
            </div>
            <p id="detail-description" class="muted"></p>
        </div>
    </div>

    <div class="modal hidden" id="vehicule-modal">
        <div class="modal-backdrop" data-close="vehicule-modal"></div>
        <div class="modal-dialog">
            <div class="section-subheader">
                <div>
                    <h3 id="vehicule-form-title">Ajouter un véhicule</h3>
                    <div class="muted-small">Renseignez toutes les informations du véhicule.</div>
                </div>
                <div class="section-actions">
                    <button class="btn secondary xs" id="close-vehicule-modal" type="button">Fermer</button>
                </div>
            </div>
            <form id="vehicule-form">
                <div class="grid">
                    <div><label>Numéro</label><input name="numero" required></div>
                    <div><label>Code</label><input name="code" required></div>
                    <div><label>Marque</label><input name="marque"></div>
                    <div><label>Modèle</label><input name="modele"></div>
                    <div><label>Année</label><input name="annee" type="number" min="1900" max="2100"></div>
                    <div><label>Couleur</label><input name="couleur"></div>
                    <div><label>Châssis</label><input name="chassis"></div>
                    <div>
                        <label>Chauffeur</label>
                        <select name="chauffeur_id" aria-label="Chauffeur">
                            <option value="">- Choisir un chauffeur -</option>
                        </select>
                    </div>
                    <div><label>Date d'acquisition</label><input name="date_acquisition" type="date"></div>
                    <div><label>Valeur</label><input name="valeur" type="number" min="0" step="0.01"></div>
                    <div><label>Statut</label>
                        <select name="statut">
                            <option value="1">Actif</option>
                            <option value="0">Inactif</option>
                        </select>
                    </div>
                    <div><label>Date de création</label><input name="date_creation" type="date"></div>
                    <div><label>Catégorie</label>
                        <select name="categorie">
                            <option value="">-</option>
                            <option value="leger">Léger</option>
                            <option value="lourd">Lourd</option>
                            <option value="transport">Transport</option>
                            <option value="tracteur">Tracteur</option>
                            <option value="engins">Engins</option>
                        </select>
                    </div>
                    <div><label>Option véhicule</label>
                        <select name="option_vehicule">
                            <option value="">-</option>
                            <option value="base">Base</option>
                            <option value="base_clim">Base clim</option>
                            <option value="toutes_options">Toutes options</option>
                        </select>
                    </div>
                    <div><label>Energie</label>
                        <select name="energie">
                            <option value="">-</option>
                            <option value="essence">Essence</option>
                            <option value="diesel">Diesel</option>
                            <option value="gpl">GPL</option>
                            <option value="electrique">Électrique</option>
                        </select>
                    </div>
                    <div><label>Boîte</label>
                        <select name="boite">
                            <option value="">-</option>
                            <option value="semiauto">Semi-auto</option>
                            <option value="auto">Auto</option>
                            <option value="manuel">Manuel</option>
                        </select>
                    </div>
                    <div><label>Leasing</label>
                        <select name="leasing">
                            <option value="">-</option>
                            <option value="location">Location</option>
                            <option value="acquisition">Acquisition</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    <div><label>Utilisation</label>
                        <select name="utilisation">
                            <option value="">-</option>
                            <option value="personnel">Personnel</option>
                            <option value="professionnel">Professionnel</option>
                        </select>
                    </div>
                    <div><label>Affectation</label><input name="affectation" placeholder="Service / site"></div>
                </div>
                <div class="stack">
                    <label>Description</label>
                    <textarea name="description" placeholder="Notes générales"></textarea>
                </div>
                <div class="stack">
                    <label>Photos du véhicule</label>
                    <input id="vehicule-images-input" name="images[]" type="file" accept="image/*" multiple>
                    <div class="muted-small">Vous pouvez sélectionner plusieurs images (jpg, png, webp...).</div>
                </div>
                <div class="section-actions">
                    <button class="btn primary" id="vehicule-form-submit" type="submit">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal hidden" id="image-viewer-modal">
        <div class="modal-backdrop" data-close="image-viewer-modal"></div>
        <div class="modal-dialog image-viewer-dialog">
            <div class="section-subheader">
                <div>
                    <h3>Photo du véhicule</h3>
                    <div class="muted-small" id="image-viewer-caption"></div>
                </div>
                <div class="section-actions">
                    <button class="btn secondary xs" id="close-image-viewer" type="button">Fermer</button>
                </div>
            </div>
            <div class="image-viewer-body">
                <img id="image-viewer-img" alt="Aperçu du véhicule">
            </div>
        </div>
    </div>
</section>
